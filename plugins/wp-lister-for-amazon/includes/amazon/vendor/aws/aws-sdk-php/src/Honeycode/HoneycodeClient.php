<?php
namespace Aws\Honeycode;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Honeycode** service.
 * @method \Aws\Result batchCreateTableRows(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise batchCreateTableRowsAsync(array $args = [])
 * @method \Aws\Result batchDeleteTableRows(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise batchDeleteTableRowsAsync(array $args = [])
 * @method \Aws\Result batchUpdateTableRows(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise batchUpdateTableRowsAsync(array $args = [])
 * @method \Aws\Result batchUpsertTableRows(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise batchUpsertTableRowsAsync(array $args = [])
 * @method \Aws\Result describeTableDataImportJob(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeTableDataImportJobAsync(array $args = [])
 * @method \Aws\Result getScreenData(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getScreenDataAsync(array $args = [])
 * @method \Aws\Result invokeScreenAutomation(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise invokeScreenAutomationAsync(array $args = [])
 * @method \Aws\Result listTableColumns(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTableColumnsAsync(array $args = [])
 * @method \Aws\Result listTableRows(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTableRowsAsync(array $args = [])
 * @method \Aws\Result listTables(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTablesAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result queryTableRows(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise queryTableRowsAsync(array $args = [])
 * @method \Aws\Result startTableDataImportJob(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise startTableDataImportJobAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 */
class HoneycodeClient extends AwsClient {}
