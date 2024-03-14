<?php
namespace Aws\TimestreamWrite;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Timestream Write** service.
 * @method \Aws\Result createDatabase(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createDatabaseAsync(array $args = [])
 * @method \Aws\Result createTable(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createTableAsync(array $args = [])
 * @method \Aws\Result deleteDatabase(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteDatabaseAsync(array $args = [])
 * @method \Aws\Result deleteTable(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteTableAsync(array $args = [])
 * @method \Aws\Result describeDatabase(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeDatabaseAsync(array $args = [])
 * @method \Aws\Result describeEndpoints(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeEndpointsAsync(array $args = [])
 * @method \Aws\Result describeTable(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeTableAsync(array $args = [])
 * @method \Aws\Result listDatabases(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listDatabasesAsync(array $args = [])
 * @method \Aws\Result listTables(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTablesAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateDatabase(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateDatabaseAsync(array $args = [])
 * @method \Aws\Result updateTable(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateTableAsync(array $args = [])
 * @method \Aws\Result writeRecords(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise writeRecordsAsync(array $args = [])
 */
class TimestreamWriteClient extends AwsClient {}
