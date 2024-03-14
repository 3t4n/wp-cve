<?php

namespace Modular\ConnectorDependencies\Illuminate\Database\PDO;

use Modular\ConnectorDependencies\Doctrine\DBAL\Driver\AbstractMySQLDriver;
use Modular\ConnectorDependencies\Illuminate\Database\PDO\Concerns\ConnectsToDatabase;
/** @internal */
class MySqlDriver extends AbstractMySQLDriver
{
    use ConnectsToDatabase;
}
