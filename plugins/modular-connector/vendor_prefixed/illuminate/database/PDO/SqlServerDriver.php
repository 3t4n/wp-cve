<?php

namespace Modular\ConnectorDependencies\Illuminate\Database\PDO;

use Modular\ConnectorDependencies\Doctrine\DBAL\Driver\AbstractSQLServerDriver;
/** @internal */
class SqlServerDriver extends AbstractSQLServerDriver
{
    /**
     * @return \Doctrine\DBAL\Driver\Connection
     */
    public function connect(array $params)
    {
        return new SqlServerConnection(new Connection($params['pdo']));
    }
}
