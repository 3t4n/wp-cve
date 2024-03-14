<?php
namespace Aws\CloudWatchRUM;

use Aws\AwsClient;

/**
 * This client is used to interact with the **CloudWatch RUM** service.
 * @method \Aws\Result createAppMonitor(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createAppMonitorAsync(array $args = [])
 * @method \Aws\Result deleteAppMonitor(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteAppMonitorAsync(array $args = [])
 * @method \Aws\Result getAppMonitor(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getAppMonitorAsync(array $args = [])
 * @method \Aws\Result getAppMonitorData(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getAppMonitorDataAsync(array $args = [])
 * @method \Aws\Result listAppMonitors(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listAppMonitorsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result putRumEvents(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putRumEventsAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateAppMonitor(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateAppMonitorAsync(array $args = [])
 */
class CloudWatchRUMClient extends AwsClient {}
