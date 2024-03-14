<?php
namespace Aws\IoTDeviceAdvisor;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS IoT Core Device Advisor** service.
 * @method \Aws\Result createSuiteDefinition(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createSuiteDefinitionAsync(array $args = [])
 * @method \Aws\Result deleteSuiteDefinition(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteSuiteDefinitionAsync(array $args = [])
 * @method \Aws\Result getEndpoint(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getEndpointAsync(array $args = [])
 * @method \Aws\Result getSuiteDefinition(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getSuiteDefinitionAsync(array $args = [])
 * @method \Aws\Result getSuiteRun(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getSuiteRunAsync(array $args = [])
 * @method \Aws\Result getSuiteRunReport(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getSuiteRunReportAsync(array $args = [])
 * @method \Aws\Result listSuiteDefinitions(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listSuiteDefinitionsAsync(array $args = [])
 * @method \Aws\Result listSuiteRuns(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listSuiteRunsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result startSuiteRun(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise startSuiteRunAsync(array $args = [])
 * @method \Aws\Result stopSuiteRun(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise stopSuiteRunAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateSuiteDefinition(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateSuiteDefinitionAsync(array $args = [])
 */
class IoTDeviceAdvisorClient extends AwsClient {}
