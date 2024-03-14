<?php
namespace Aws\IoTEventsData;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS IoT Events Data** service.
 * @method \Aws\Result batchAcknowledgeAlarm(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise batchAcknowledgeAlarmAsync(array $args = [])
 * @method \Aws\Result batchDeleteDetector(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise batchDeleteDetectorAsync(array $args = [])
 * @method \Aws\Result batchDisableAlarm(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise batchDisableAlarmAsync(array $args = [])
 * @method \Aws\Result batchEnableAlarm(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise batchEnableAlarmAsync(array $args = [])
 * @method \Aws\Result batchPutMessage(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise batchPutMessageAsync(array $args = [])
 * @method \Aws\Result batchResetAlarm(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise batchResetAlarmAsync(array $args = [])
 * @method \Aws\Result batchSnoozeAlarm(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise batchSnoozeAlarmAsync(array $args = [])
 * @method \Aws\Result batchUpdateDetector(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise batchUpdateDetectorAsync(array $args = [])
 * @method \Aws\Result describeAlarm(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeAlarmAsync(array $args = [])
 * @method \Aws\Result describeDetector(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeDetectorAsync(array $args = [])
 * @method \Aws\Result listAlarms(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listAlarmsAsync(array $args = [])
 * @method \Aws\Result listDetectors(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listDetectorsAsync(array $args = [])
 */
class IoTEventsDataClient extends AwsClient {}
