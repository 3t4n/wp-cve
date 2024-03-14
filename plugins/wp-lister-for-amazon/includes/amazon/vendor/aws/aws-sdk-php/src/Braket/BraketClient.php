<?php
namespace Aws\Braket;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Braket** service.
 * @method \Aws\Result cancelJob(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise cancelJobAsync(array $args = [])
 * @method \Aws\Result cancelQuantumTask(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise cancelQuantumTaskAsync(array $args = [])
 * @method \Aws\Result createJob(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createJobAsync(array $args = [])
 * @method \Aws\Result createQuantumTask(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createQuantumTaskAsync(array $args = [])
 * @method \Aws\Result getDevice(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getDeviceAsync(array $args = [])
 * @method \Aws\Result getJob(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getJobAsync(array $args = [])
 * @method \Aws\Result getQuantumTask(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getQuantumTaskAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result searchDevices(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise searchDevicesAsync(array $args = [])
 * @method \Aws\Result searchJobs(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise searchJobsAsync(array $args = [])
 * @method \Aws\Result searchQuantumTasks(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise searchQuantumTasksAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 */
class BraketClient extends AwsClient {}
