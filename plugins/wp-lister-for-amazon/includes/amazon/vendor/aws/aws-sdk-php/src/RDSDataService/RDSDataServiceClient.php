<?php
namespace Aws\RDSDataService;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS RDS DataService** service.
 * @method \Aws\Result batchExecuteStatement(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise batchExecuteStatementAsync(array $args = [])
 * @method \Aws\Result beginTransaction(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise beginTransactionAsync(array $args = [])
 * @method \Aws\Result commitTransaction(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise commitTransactionAsync(array $args = [])
 * @method \Aws\Result executeSql(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise executeSqlAsync(array $args = [])
 * @method \Aws\Result executeStatement(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise executeStatementAsync(array $args = [])
 * @method \Aws\Result rollbackTransaction(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise rollbackTransactionAsync(array $args = [])
 */
class RDSDataServiceClient extends AwsClient {}
