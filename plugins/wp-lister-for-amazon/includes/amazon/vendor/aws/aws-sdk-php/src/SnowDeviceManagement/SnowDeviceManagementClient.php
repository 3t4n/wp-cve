<?php
namespace Aws\SnowDeviceManagement;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Snow Device Management** service.
 * @method \Aws\Result cancelTask(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise cancelTaskAsync(array $args = [])
 * @method \Aws\Result createTask(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createTaskAsync(array $args = [])
 * @method \Aws\Result describeDevice(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeDeviceAsync(array $args = [])
 * @method \Aws\Result describeDeviceEc2Instances(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeDeviceEc2InstancesAsync(array $args = [])
 * @method \Aws\Result describeExecution(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeExecutionAsync(array $args = [])
 * @method \Aws\Result describeTask(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeTaskAsync(array $args = [])
 * @method \Aws\Result listDeviceResources(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listDeviceResourcesAsync(array $args = [])
 * @method \Aws\Result listDevices(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listDevicesAsync(array $args = [])
 * @method \Aws\Result listExecutions(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listExecutionsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result listTasks(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTasksAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 */
class SnowDeviceManagementClient extends AwsClient {}
