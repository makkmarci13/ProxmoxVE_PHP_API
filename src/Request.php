<?php

/**
 * ProxmoxVE PHP API
 *
 * @copyright 2017 Saleh <Saleh7@protonmail.ch>
 * @license http://opensource.org/licenses/MIT The MIT License.
 */

namespace Proxmox;

use Curl\Curl;

class Request
{
    protected static string $hostname;
    protected static string $username;
    protected static string $password;
    protected static ?string $token_name = null;
    protected static ?string $token_value = null;
    protected static string $realm;
    protected static int $port;
    protected static Curl $Client;

    /**
     * Proxmox Api client
     *
     * @param array $configure hostname, username, password, realm, port
     * @param bool $verifySSL
     * @param bool $verifyHost
     * @return void
     * @throws ProxmoxException
     */
    public static function Login(array $configure, bool $verifySSL = false, bool $verifyHost = false): void
    {
        $check = false;

        if (empty($configure['password'])) {
            if (empty($configure['token_name']) || empty($configure['token_value'])) {
                $check = true;
            } else {
                self::$token_name = $configure['token_name'];
                self::$token_value = $configure['token_value'];
            }
        } else {
            self::$password = $configure['password'];
        }

        self::$hostname = !empty($configure['hostname']) ? $configure['hostname'] : $check = true;
        self::$username = !empty($configure['username']) ? $configure['username'] : $check = true;
        self::$realm = !empty($configure['realm']) ? $configure['realm'] : 'pam'; // pam - pve - ..
        self::$port = !empty($configure['port']) ? $configure['port'] : 8006;

        if ($check) {
            throw new ProxmoxException('Require in array [hostname], [username], [password] or [token_name] and [token_value], [realm], [port]');
        }

        self::ticket($verifySSL, $verifyHost);
    }

    /**
     * Create or verify authentication ticket.
     *
     * POST /api2/json/access/ticket
     *
     * @param bool $verifySSL
     * @param bool $verifyHost
     * @return bool
     * @throws ProxmoxException
     */
    protected static function ticket(bool $verifySSL, bool $verifyHost): bool
    {
        self::$Client = new Curl();

        self::$Client->setOpts([
            CURLOPT_SSL_VERIFYPEER => $verifySSL,
            CURLOPT_SSL_VERIFYHOST => $verifyHost ? 2 : 0,
        ]);

        if (!empty(self::$token_name) && !empty(self::$token_value)) {
            self::$Client->setHeader('Authorization', sprintf(
                'PVEAPIToken=%s!%s=%s',
                self::$username . '@' . self::$realm,
                self::$token_name,
                self::$token_value
            ));
        } else {
            $data = [
                'username' => self::$username,
                'password' => self::$password,
                'realm' => self::$realm,
            ];

            $response = self::$Client->post("https://" . self::$hostname . ":" . self::$port . "/api2/json/access/ticket", $data);

            if (!$response) {
                throw new ProxmoxException('Request params empty');
            }

            // Set header
            self::$Client->setHeader('CSRFPreventionToken', $response->data->CSRFPreventionToken);

            // Set cookie
            self::$Client->setCookie('PVEAuthCookie', $response->data->ticket);
        }

        return true;
    }

    /**
     * Sends native request manually to ProxmoxVE API
     *
     * @param string $path
     * @param array|null $params
     * @param string $method
     * @return mixed
     * @throws ProxmoxException
     */
    public static function Request(string $path, ?array $params = null, string $method = "GET"): mixed
    {
        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }

        $api = sprintf('https://%s:%s/api2/json%s', self::$hostname, self::$port, $path);

        switch ($method) {
            case "GET":
                return self::$Client->get($api, $params);
            case "PUT":
                return self::$Client->put($api, $params);
            case "POST":
                return self::$Client->post($api, $params);
            case "DELETE":
                self::$Client->removeHeader('Content-Length');
                return self::$Client->delete($api, $params);
            default:
                throw new ProxmoxException('HTTP Request method not allowed.');
        }
    }
}
