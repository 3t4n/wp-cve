<?php
namespace Aws\IoTJobsDataPlane;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS IoT Jobs Data Plane** service.
 * @method \Aws\Result describeJobExecution(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeJobExecutionAsync(array $args = [])
 * @method \Aws\Result getPendingJobExecutions(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getPendingJobExecutionsAsync(array $args = [])
 * @method \Aws\Result startNextPendingJobExecution(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise startNextPendingJobExecutionAsync(array $args = [])
 * @method \Aws\Result updateJobExecution(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateJobExecutionAsync(array $args = [])
 */
class IoTJobsDataPlaneClient extends AwsClient {}
