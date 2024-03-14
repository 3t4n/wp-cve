<?php

namespace Modular\Connector\Services\Backup\Dumper;

use Modular\Connector\Facades\Database;
use Modular\ConnectorDependencies\Spatie\DbDumper\Databases\MySql;

class ShellDumper
{
    public static function dump(string $path, array $connection, array $excluded)
    {
        $database = $connection['database'];
        $username = $connection['username'];
        $password = $connection['password'];

        $host = $connection['host'];
        $port = $connection['port'];

        if (Database::engine() !== 'MariaDB') {
            MySql::create()
                ->setHost($host)
                ->setPort($port)
                ->setDbName($database)
                ->setUserName($username)
                ->setPassword($password)
                ->excludeTables($excluded)
                ->doNotUseColumnStatistics()
                ->dumpToFile($path);
        } else {
            // MariaDB don't use variable 'column-statistics=0' in the mysqldump,
            // so we need re-try without this variable
            MySql::create()
                ->setHost($host)
                ->setPort($port)
                ->setDbName($database)
                ->setUserName($username)
                ->setPassword($password)
                ->excludeTables($excluded)
                ->dumpToFile($path);
        }
    }
}
