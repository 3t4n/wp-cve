<?php
namespace Aws\MWAA;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AmazonMWAA** service.
 * @method \Aws\Result createCliToken(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createCliTokenAsync(array $args = [])
 * @method \Aws\Result createEnvironment(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createEnvironmentAsync(array $args = [])
 * @method \Aws\Result createWebLoginToken(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createWebLoginTokenAsync(array $args = [])
 * @method \Aws\Result deleteEnvironment(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteEnvironmentAsync(array $args = [])
 * @method \Aws\Result getEnvironment(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getEnvironmentAsync(array $args = [])
 * @method \Aws\Result listEnvironments(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listEnvironmentsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result publishMetrics(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise publishMetricsAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateEnvironment(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateEnvironmentAsync(array $args = [])
 */
class MWAAClient extends AwsClient {}
