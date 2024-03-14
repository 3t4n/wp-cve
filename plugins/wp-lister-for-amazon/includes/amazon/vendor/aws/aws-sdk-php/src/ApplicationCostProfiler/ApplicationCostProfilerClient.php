<?php
namespace Aws\ApplicationCostProfiler;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Application Cost Profiler** service.
 * @method \Aws\Result deleteReportDefinition(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteReportDefinitionAsync(array $args = [])
 * @method \Aws\Result getReportDefinition(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getReportDefinitionAsync(array $args = [])
 * @method \Aws\Result importApplicationUsage(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise importApplicationUsageAsync(array $args = [])
 * @method \Aws\Result listReportDefinitions(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listReportDefinitionsAsync(array $args = [])
 * @method \Aws\Result putReportDefinition(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putReportDefinitionAsync(array $args = [])
 * @method \Aws\Result updateReportDefinition(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateReportDefinitionAsync(array $args = [])
 */
class ApplicationCostProfilerClient extends AwsClient {}
