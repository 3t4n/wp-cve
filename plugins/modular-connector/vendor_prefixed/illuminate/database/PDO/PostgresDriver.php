<?php

namespace Modular\ConnectorDependencies\Illuminate\Database\PDO;

use Modular\ConnectorDependencies\Doctrine\DBAL\Driver\AbstractPostgreSQLDriver;
use Modular\ConnectorDependencies\Illuminate\Database\PDO\Concerns\ConnectsToDatabase;
/** @internal */
class PostgresDriver extends AbstractPostgreSQLDriver
{
    use ConnectsToDatabase;
}
