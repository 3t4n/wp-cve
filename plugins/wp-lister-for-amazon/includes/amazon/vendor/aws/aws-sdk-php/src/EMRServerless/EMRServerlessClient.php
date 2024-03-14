<?php
namespace Aws\EMRServerless;

use Aws\AwsClient;

/**
 * This client is used to interact with the **EMR Serverless** service.
 * @method \Aws\Result cancelJobRun(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise cancelJobRunAsync(array $args = [])
 * @method \Aws\Result createApplication(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createApplicationAsync(array $args = [])
 * @method \Aws\Result deleteApplication(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteApplicationAsync(array $args = [])
 * @method \Aws\Result getApplication(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getApplicationAsync(array $args = [])
 * @method \Aws\Result getJobRun(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getJobRunAsync(array $args = [])
 * @method \Aws\Result listApplications(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listApplicationsAsync(array $args = [])
 * @method \Aws\Result listJobRuns(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listJobRunsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result startApplication(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise startApplicationAsync(array $args = [])
 * @method \Aws\Result startJobRun(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise startJobRunAsync(array $args = [])
 * @method \Aws\Result stopApplication(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise stopApplicationAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateApplication(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateApplicationAsync(array $args = [])
 */
class EMRServerlessClient extends AwsClient {}
