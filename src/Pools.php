<?php

/**
 * ProxmoxVE PHP API
 *
 * @copyright 2017 Saleh <Saleh7@protonmail.ch>
 * @license http://opensource.org/licenses/MIT The MIT License.
 */

namespace Proxmox;

/**
 *
 * https://pve.proxmox.com/pve-docs/api-viewer/#/pools
 *
 * /api2/json/pools
 *
 */
class Pools
{
    /**
     * Read system log
     *
     * GET /api2/json/pools
     *
     * @throws ProxmoxException
     */
    public function Pools()
    {
        return Request::Request("/pools");
    }

    /**
     * Read system log
     *
     * GET /api2/json/pools/{poolId}
     *
     * @param string $pool_id
     * @return mixed
     * @throws ProxmoxException
     */
    public function PoolsID(string $pool_id): mixed
    {
        return Request::Request("/pools/$pool_id");
    }

    /**
     * Read system log
     *
     * GET /api2/json/pools/{poolId}
     *
     * @param string $pool_id
     * @param array $data
     * @return mixed
     * @throws ProxmoxException
     */
    public function PutPool(string $pool_id, array $data = []): mixed
    {
        return Request::Request("/pools/$pool_id", $data, "PUT");
    }
}
