![ProxmoxVE_PHP_API](https://upload.wikimedia.org/wikipedia/en/thumb/2/25/Proxmox-VE-logo.svg/600px-Proxmox-VE-logo.svg.png)

# ProxmoxVE PHP API

## Table of Contents
- [ProxmoxVE PHP API](#proxmoxve-php-api)
  - [Table of Contents](#table-of-contents)
  - [Installation](#installation)
  - [Usage](#usage)
    - [Quick Usage](#quick-usage)
    - [Use API Token](#use-api-token)
    - [Example](#example)
    - [Create LXC container](#create-lxc-container)
    - [Delete LXC container](#delete-lxc-container)
    - [Create VM](#create-vm)
    - [Delete VM](#delete-vm)
  - [Request](#request)
  - [Access](#access)
  - [Domains](#domains)
  - [Groups](#groups)
  - [Roles](#roles)
  - [Users](#users)
  - [Cluster](#cluster)
  - [Backup](#backup)
  - [Config](#config)
  - [Firewall](#firewall)
  - [HA](#ha)
  - [Replication](#replication)
  - [Pools](#pools)
  - [Storage](#storage)
  - [Nodes](#nodes)
  - [Apt](#apt)
  - [Ceph](#ceph)
  - [Disks](#disks)
  - [Nodes Firewall](#nodes-firewall)
  - [Lxc](#lxc)
  - [Network](#network)
  - [Qemu](#qemu)
  - [Nodes Replication](#nodes-replication)
  - [Scan](#scan)
  - [Service](#service)
  - [Nodes Storage](#nodes-storage)
  - [Tasks](#tasks)
  - [Vzdump](#vzdump)

## Requirements
* ``php >= 8.0``
* ``php-curl``
* ``php-mbstring``

## Installation
To install ProxmoxVE_PHP_API, simply:

```bash
composer require makkmarci13/proxmox-ve_php_api
```

## Usage

### Quick Usage

```php
require __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload
use Proxmox\Request;

$configure = [
    'hostname' => '0.0.0.0',
    'username' => 'root',
    'password' => 'password'
];
Request::Login($configure); // Login ..

// Request($path, array $params = null, $method="GET")
print_r( Request::Request('/nodes', null, 'GET') ); // List Nodes
```

### Use API Token
```php
require __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload
use Proxmox\Request;
use Proxmox\Access;
use Proxmox\Cluster;
use Proxmox\Nodes;
use Proxmox\Pools;
use Proxmox\Storage;

$configure = [
    'hostname' => '0.0.0.0',
    'username' => 'root',
    'token_name' => 'apitoken',
    'token_value' => '00000000-0000-0000-0000-000000000000'
];
Request::Login($configure); // Login ..
print_r( Access::listNodes() ); // List Nodes
```

### Example
```php
require __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload
use Proxmox\Request;
use Proxmox\Access;
use Proxmox\Cluster;
use Proxmox\Nodes;
use Proxmox\Pools;
use Proxmox\Storage;

$configure = [
    'hostname' => '0.0.0.0',
    'username' => 'root',
    'password' => 'password',
];
Request::Login($configure); // Login ..
print_r( Access::listNodes() ); // List Nodes
```

### Create LXC container

```php
require __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload
use Proxmox\Request;
use Proxmox\Cluster;
use Proxmox\Nodes;

$configure = [
  'hostname' => '0.0.0.0',
  'username' => 'root',
  'password' => 'password',
];
Request::Login($configure); // Login ..

# Create container
$nextId = Cluster::nextVmid(); // get next vmid
$create = [
  'vmid'        => $nextId->data,
  'cores'       => 1,
  'hostname'    => 'testApi',
  'rootfs'      => 'local:8',
  'memory'      => 512,
  'swap'        => 512,
  'ostemplate'  => 'local:vztmpl/ubuntu-16.04-standard_16.04-1_amd64.tar.gz',
  'net0'        => 'bridge=vmbr0,hwaddr=00:00:00:00:00:00,name=eth0,ip=0.0.0.0/32,gw=0.0.0.0'
];
# Get first node name.
$firstNode = Nodes::listNodes()->data[0]->node;
print_r( Nodes::createLxc($firstNode, $create) );
// print_r( Nodes::createLxc('Name_Nodes', $create) );

```

### Delete LXC container

```php
require __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload
use Proxmox\Request;
use Proxmox\Nodes;

$configure = [
  'hostname' => '0.0.0.0',
  'username' => 'root',
  'password' => 'password',
];
Request::Login($configure); // Login ..

# Get first node name.
$firstNode = Nodes::listNodes()->data[0]->node;
# Delete container
$vmid = 106;
print_r( Nodes::deleteLxc($firstNode, $vmid) );
// print_r( Nodes::deleteLxc('Name_Nodes', $vmid) );
```

### Create VM

```php
require __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload
use Proxmox\Request;
use Proxmox\Cluster;
use Proxmox\Nodes;

$configure = [
  'hostname' => '0.0.0.0',
  'username' => 'root',
  'password' => 'password',
];
Request::Login($configure); // Login ..

# Create VM
$nextId = Cluster::nextVmid(); // get next vmid
$create = [
  'vmid'        => $nextId->data,
  'cores'       => 1,
  'name'        => 'testApi',
  'scsi0'       => 'local:32,format=qcow2'
];
# Get first node name.
$firstNode = Nodes::listNodes()->data[0]->node;
print_r( Nodes::createQemu($firstNode, $create) );
// print_r( Nodes::createQemu('Name_Nodes', $create) );
```

### Delete VM

```php
require __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload
use Proxmox\Request;
use Proxmox\Nodes;

$configure = [
  'hostname' => '0.0.0.0',
  'username' => 'root',
  'password' => 'password',
];
Request::Login($configure); // Login ..

# Get first node name.
$firstNode = Nodes::listNodes()->data[0]->node;
# Delete VM
$vmid = 104;
print_r( Nodes::deleteQemu($firstNode, $vmid) );
// print_r( Nodes::deleteQemu('Name_Nodes', $vmid) );
```
## Request

```php
Request::Login(array $configure, $verifySSL = false)
Request::Request($path, array $params = null, $method="GET")
```

## Access

```php
Access::Access()
Access::Acl()
Access::updateAcl($data = [])
Access::createTicket($data = [])
```

## Domains

```php
Access::Domains()
Access::addDomain($data = [])
Access::domainsRealm($realm)
Access::updateDomain($realm, $data = [])
Access::deleteDomain($realm)
```

## Groups

```php
Access::Groups()
Access::createGroup($data = [])
Access::GroupId($groupid)
Access::updateGroup($groupid, $data = [])
Access::deleteGroup($groupid)
```

## Roles

```php
Access::Roles()
Access::createRole($data = [])
Access::RoleId($roleid)
Access::updateRole($roleid, $data = [])
Access::deleteRole($roleid)
```

## Users

```php
Access::Users()
Access::createUser($data = [])
Access::getUser($userid)
Access::updateUser($userid, $data = [])
Access::deleteUser($userid)
Access::changeUserPassword($data = [])
```

## Cluster

```php
Cluster::Cluster()
Cluster::Log($max = null)
Cluster::nextVmid($vmid = null)
Cluster::Options()
Cluster::setOptions($data = [])
Cluster::Resources($type = null)
Cluster::Status()
Cluster::Tasks()
```

## Backup

```php
Cluster::ListBackup()
Cluster::createBackup($data = [])
Cluster::BackupId($id)
Cluster::updateBackup($id, $data = [])
Cluster::deleteBackup($id)
```

## Config

```php
Cluster::Config()
Cluster::listConfigNodes()
Cluster::configTotem()
```

## Firewall

```php
Cluster::Firewall()
Cluster::firewallListAliases()
Cluster::createFirewallAliase($data = [])
Cluster::getFirewallAliasesName($name)
Cluster::updateFirewallAliase($name, $data = [])
Cluster::removeFirewallAliase($name)
Cluster::firewallListGroups()
Cluster::createFirewallGroup($data = [])
Cluster::firewallGroupsGroup($group)
Cluster::createRuleFirewallGroup($group, $data = [])
Cluster::removeFirewallGroup($group)
Cluster::firewallGroupsGroupPos($group, $pos)
Cluster::setFirewallGroupPos($group, $pos, $data = [])
Cluster::removeFirewallGroupPos($group, $pos)
Cluster::firewallListIpset()
Cluster::createFirewallIpset($data = [])
Cluster::firewallIpsetName($name)
Cluster::addFirewallIpsetName($name, $data = [])
Cluster::deleteFirewallIpsetName($name)
Cluster::firewallListRules()
Cluster::createFirewallRules($data = [])
Cluster::firewallRulesPos($pos)
Cluster::setFirewallRulesPos($pos, $data = [])
Cluster::deleteFirewallRulesPos($pos)
Cluster::firewallListMacros()
Cluster::firewallListOptions()
Cluster::setFirewallOptions($data = [])
Cluster::firewallListRefs()
```

## HA

```php
Cluster::getHaGroups()
Cluster::HaGroups($group)
Cluster::HaResources()
```

## Replication

```php
Cluster::Replication()
Cluster::createReplication($data = [])
Cluster::replicationId($id)
Cluster::updateReplication($id, $data = [])
Cluster::deleteReplication($id)
```

## Pools

```php
Pools::Pools()
Pools::PoolsID($poolid)
Pools::PutPool($poolid, $data = [])
```

## Storage

```php
Storage::Storage($type = null)
Storage::createStorage($data = [])
Storage::getStorage($storage)
Storage::updateStorage($storage, $data = [])
Storage::deleteStorage($storage)
```

## Nodes

```php
Nodes::listNodes()
Nodes::Aplinfo($node)
Nodes::downloadTemplate($node, $data = [])
Nodes::Dns($node)
Nodes::setDns($node, $data = [])
Nodes::Execute($node, $data = [])
Nodes::MigrateAll($node, $data = [])
Nodes::Netstat($node)
Nodes::Report($node)
Nodes::Rrd($node, $ds = null, $timeframe = null)
Nodes::Rrddata($node, $timeframe = null)
Nodes::SpiceShell($node, $data = [])
Nodes::StartAll($node, $data = [])
Nodes::Reboot($node, $data = [])
Nodes::StopAll($node, $data = [])
Nodes::Subscription($node)
Nodes::updateSubscription($node, $data = [])
Nodes::setSubscription($node, $data = [])
Nodes::Syslog($node, $limit = null, $start = null, $since = null, $until = null)
Nodes::Time($node)
Nodes::setTime($node, $data = [])
Nodes::Version($node)
Nodes::createVNCShell($node, $data = [])
Nodes::VNCWebSocket($node, $port = null, $vncticket = null)
```

## Apt

```php
Nodes::Apt($node)
Nodes::updateApt($node, $data = [])
Nodes::AptChangelog($node, $name = null)
Nodes::AptUpdate($node)
Nodes::createAptUpdate($data = [])
```

## Ceph

```php
Nodes::Ceph($node)
Nodes::CephFlags($node)
Nodes::setCephFlags($node, $flag, $data = [])
Nodes::unsetCephFlags($node, $flag)
Nodes::createCephMgr($node, $data = [])
Nodes::destroyCephMgr($node, $id)
Nodes::CephMon($node)
Nodes::createCephMon($node, $data = [])
Nodes::destroyCephMon($node, $monid)
Nodes::CephOsd($node)
Nodes::createCephOsd($node, $data = [])
Nodes::destroyCephOsd($node, $osdid)
Nodes::CephOsdIn($node, $osdid, $data = [])
Nodes::CephOsdOut($node, $osdid, $data = [])
Nodes::getCephPools($node)
Nodes::createCephPool($node, $data = [])
Nodes::destroyCephPool($node)
Nodes::CephConfig($node)
Nodes::CephCrush($node)
Nodes::CephDisks($node)
Nodes::createCephInit($node, $data = [])
Nodes::CephLog($node, $limit = null, $start = null)
Nodes::CephRules($node)
Nodes::CephStart($node, $data = [])
Nodes::CephStop($node, $data = [])
Nodes::CephStatus($node)
```

## Disks

```php
Nodes::getDisks($node)
Nodes::Disk($node, $data = [])
Nodes::disksList($node)
Nodes::disksSmart($node, $disk = null)
```

## Nodes Firewall

```php
Nodes::Firewall($node)
Nodes::firewallRules($node)
Nodes::createFirewallRule($node, $data = [])
Nodes::firewallRulesPos($node, $pos)
Nodes::setFirewallRulePos($node, $pos, $data = [])
Nodes::deleteFirewallRulePos($node, $pos)
Nodes::firewallRulesLog($node)
Nodes::firewallRulesOptions($node)
Nodes::setFirewallRuleOptions($node, $data = [])
```

## Lxc

```php
Nodes::Lxc($node)
Nodes::createLxc($node, $data = [])
Nodes::LxcVmid($node, $vmid)
Nodes::deleteLxc($node, $vmid)
Nodes::lxcFirewall($node, $vmid)
Nodes::lxcFirewallAliases($node, $vmid)
Nodes::createLxcFirewallAliase($node, $vmid, $data = [])
Nodes::lxcFirewallAliasesName($node, $vmid, $name)
Nodes::updateLxcFirewallAliaseName($node, $vmid, $name, $data = [])
Nodes::deleteLxcFirewallAliaseName($node, $vmid, $name)
Nodes::lxcFirewallIpset($node, $vmid)
Nodes::createLxcFirewallIpset($node, $vmid, $data = [])
Nodes::lxcFirewallIpsetName($node, $vmid, $name)
Nodes::addLxcFirewallIpsetName($node, $vmid, $name, $data = [])
Nodes::deleteLxcFirewallIpsetName($node, $vmid, $name)
Nodes::lxcFirewallIpsetNameCidr($node, $vmid, $name, $cidr)
Nodes::updateLxcFirewallIpsetNameCidr($node, $vmid, $name, $cidr, $data = [])
Nodes::deleteLxcFirewallIpsetNameCidr($node, $vmid, $name, $cidr)
Nodes::lxcFirewallRules($node, $vmid)
Nodes::createLxcFirewallRules($node, $vmid, $data = [])
Nodes::lxcFirewallRulesPos($node, $vmid, $pos)
Nodes::setLxcFirewallRulesPos($node, $vmid, $pos, $data = [])
Nodes::deleteLxcFirewallRulesPos($node, $vmid, $pos)
Nodes::lxcFirewallLog($node, $vmid, $limit = null, $start = null)
Nodes::lxcFirewallOptions($node, $vmid)
Nodes::setLxcFirewallOptions($node, $vmid, $data = [])
Nodes::lxcSnapshot($node, $vmid)
Nodes::createLxcSnapshot($node, $vmid, $data = [])
Nodes::lxcSnapname($node, $vmid, $snapname)
Nodes::deleteLxcSnapshot($node, $vmid, $snapname)
Nodes::lxcSnapnameConfig($node, $vmid, $snapname)
Nodes::lxcSnapshotConfig($node, $vmid, $snapname, $data = [])
Nodes::lxcSnapshotRollback($node, $vmid, $snapname, $data = [])
Nodes::lxcStatus($node, $vmid)
Nodes::lxcCurrent($node, $vmid)
Nodes::lxcResume($node, $vmid, $data = [])
Nodes::lxcShutdown($node, $vmid, $data = [])
Nodes::lxcStart($node, $vmid, $data = [])
Nodes::lxcStop($node, $vmid, $data = [])
Nodes::lxcReboot($node, $vmid, $data = [])
Nodes::lxcSuspend($node, $vmid, $data = [])
Nodes::lxcClone($node, $vmid, $data = [])
Nodes::lxcConfig($node, $vmid)
Nodes::setLxcConfig($node, $vmid, $data = [])
Nodes::lxcFeature($node, $vmid)
Nodes::lxcMigrate($node, $vmid, $data = [])
Nodes::lxcResize($node, $vmid, $data = [])
Nodes::lxcRrd($node, $vmid, $ds = null, $timeframe = null)
Nodes::lxcRrddata($node, $vmid, $timeframe = null)
Nodes::lxcSpiceproxy($node, $vmid, $data = [])
Nodes::createLxcTemplate($node, $vmid, $data = [])
Nodes::createLxcVncproxy($node, $vmid, $data = [])
Nodes::lxcVncwebsocket($node, $vmid, $port = null, $vncticket = null)
```

## Network

```php
Nodes::Network($node, $type = null)
Nodes::createNetwork($node, $data = [])
Nodes::revertNetwork($node)
Nodes::networkIface($node, $iface)
Nodes::updateNetworkIface($node, $iface, $data = [])
Nodes::deleteNetworkIface($node, $iface)
```

## Qemu

```php
Nodes::Qemu($node)
Nodes::createQemu($node, $data = [])
Nodes::QemuVmid($node, $vmid)
Nodes::deleteQemu($node, $vmid, $data = [])
Nodes::qemuFirewall($node, $vmid)
Nodes::qemuFirewallAliases($node, $vmid)
Nodes::createQemuFirewallAliase($node, $vmid, $data = [])
Nodes::qemuFirewallAliasesName($node, $vmid, $name)
Nodes::updateQemuFirewallAliaseName($node, $vmid, $name, $data = [])
Nodes::deleteQemuFirewallAliaseName($node, $vmid, $name)
Nodes::qemuFirewallIpset($node, $vmid)
Nodes::createQemuFirewallIpset($node, $vmid, $data = [])
Nodes::qemuFirewallIpsetName($node, $vmid, $name)
Nodes::addQemuFirewallIpsetName($node, $vmid, $name, $data = [])
Nodes::deleteQemuFirewallIpsetName($node, $vmid, $name)
Nodes::qemuFirewallIpsetNameCidr($node, $vmid, $name, $cidr)
Nodes::updateQemuFirewallIpsetNameCidr($node, $vmid, $name, $cidr, $data = [])
Nodes::deleteQemuFirewallIpsetNameCidr($node, $vmid, $name, $cidr)
Nodes::qemuFirewallRules($node, $vmid)
Nodes::createQemuFirewallRules($node, $vmid, $data = [])
Nodes::qemuFirewallRulesPos($node, $vmid, $pos)
Nodes::updateQemuFirewallRulesPos($node, $vmid, $pos, $data = [])
Nodes::deleteQemuFirewallRulesPos($node, $vmid, $pos)
Nodes::qemuFirewallLog($node, $vmid, $limit = null, $start = null)
Nodes::qemuFirewallOptions($node, $vmid)
Nodes::setQemuFirewallOptions($node, $vmid, $data = [])
Nodes::qemuFirewallRefs($node, $vmid)
Nodes::qemuSnapshot($node, $vmid)
Nodes::createQemuSnapshot($node, $vmid, $data = [])
Nodes::qemuSnapname($node, $vmid, $snapname)
Nodes::deleteQemuSnapshot($node, $vmid, $snapname)
Nodes::qemuSnapnameConfig($node, $vmid, $snapname)
Nodes::updateQemuSnapshotConfig($node, $vmid, $snapname, $data = [])
Nodes::QemuSnapshotRollback($node, $vmid, $snapname, $data = [])
Nodes::qemuStatus($node, $vmid)
Nodes::qemuCurrent($node, $vmid)
Nodes::qemuResume($node, $vmid, $data = [])
Nodes::qemuReset($node, $vmid, $data = [])
Nodes::qemuShutdown($node, $vmid, $data = [])
Nodes::qemuStart($node, $vmid, $data = [])
Nodes::qemuStop($node, $vmid, $data = [])
Nodes::qemuReboot($node, $vmid, $data = [])
Nodes::qemuSuspend($node, $vmid, $data = [])
Nodes::qemuAgent($node, $vmid, $data = [])
Nodes::qemuAgentExec($node, $vmid, $data = [])
Nodes::qemuAgentSetUserPassword($node, $vmid, $data = [])
Nodes::qemuClone($node, $vmid, $data = [])
Nodes::qemuConfig($node, $vmid)
Nodes::createQemuConfig($node, $vmid, $data = [])
Nodes::setQemuConfig($node, $vmid, $data = [])
Nodes::qemuFeature($node, $vmid)
Nodes::qemuMigrate($node, $vmid, $data = [])
Nodes::qemuMonitor($node, $vmid, $data = [])
Nodes::qemuMoveDisk($node, $vmid, $data = [])
Nodes::qemuPending($node, $vmid)
Nodes::qemuResize($node, $vmid, $data = [])
Nodes::qemuRrd($node, $vmid, $ds = null, $timeframe = null)
Nodes::qemuRrddata($node, $vmid, $timeframe = null)
Nodes::qemuSendkey($node, $vmid, $data = [])
Nodes::qemuSpiceproxy($node, $vmid, $data = [])
Nodes::createQemuTemplate($node, $vmid, $data = [])
Nodes::qemuUnlink($node, $vmid, $data = [])
Nodes::createQemuVncproxy($node, $vmid, $data = [])
Nodes::qemuVncwebsocket($node, $vmid, $port = null, $vncticket = null)
Nodes::qemuCloudInit($node, $vmid)
Nodes::regenerateQemuCloudInit($node, $vmid)
```

## Nodes Replication

```php
Nodes::Replication($node)
Nodes::replicationId($node, $id)
Nodes::replicationLog($node, $id)
Nodes::replicationScheduleNow($node, $id, $data = [])
Nodes::replicationStatus($node, $id)
```

## Scan

```php
Nodes::Scan($node)
Nodes::scanGlusterfs($node)
Nodes::scanIscsi($node)
Nodes::scanLvm($node)
Nodes::scanLvmthin($node)
Nodes::scanUsb($node)
Nodes::scanZfs($node)
```

## Service

```php
Nodes::Services($node)
Nodes::listService($node, $service)
Nodes::servicesReload($node, $service, $data = [])
Nodes::servicesRestart($node, $service, $data = [])
Nodes::servicesStart($node, $service, $data = [])
Nodes::servicesStop($node, $service, $data = [])
Nodes::servicesState($node, $service)
```

## Nodes Storage

```php
Nodes::Storage($node, $content = null, $storage = null, $target = null, $enabled = null)
Nodes::getStorage($node, $storage)
Nodes::listStorageContent($node, $storage)
Nodes::storageContent($node, $storage, $data = [])
Nodes::storageContentVolume($node, $storage, $volume)
Nodes::copyStorageContentVolume($node, $storage, $volume, $data = [])
Nodes::deleteStorageContentVolume($node, $storage, $volume)
Nodes::storageRRD($node)
Nodes::storageRRDdata($node)
Nodes::storageStatus($node)
Nodes::storageUpload($node, $data = [])
```

## Tasks

```php
Nodes::Tasks($node, $errors = null, $limit = null, $vmid = null, $start = null)
Nodes::tasksUpid($node, $upid)
Nodes::tasksStop($node, $upid)
Nodes::tasksLog($node, $upid, $limit = null, $start = null)
Nodes::tasksStatus($node, $upid)
```

## Vzdump

```php
Nodes::createVzdump($node, $data = [])
Nodes::VzdumpExtractConfig($node)
```

## Capabilities

```php
Nodes::QemuCpuCapabilities(string $node)
Nodes::QemuMachineCapabilities(string $node)
Nodes::QemuMigrationCapabilities(string $node)
```
