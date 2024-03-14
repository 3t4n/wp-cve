<?php
namespace Aws\FIS;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Fault Injection Simulator** service.
 * @method \Aws\Result createExperimentTemplate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createExperimentTemplateAsync(array $args = [])
 * @method \Aws\Result deleteExperimentTemplate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteExperimentTemplateAsync(array $args = [])
 * @method \Aws\Result getAction(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getActionAsync(array $args = [])
 * @method \Aws\Result getExperiment(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getExperimentAsync(array $args = [])
 * @method \Aws\Result getExperimentTemplate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getExperimentTemplateAsync(array $args = [])
 * @method \Aws\Result getTargetResourceType(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getTargetResourceTypeAsync(array $args = [])
 * @method \Aws\Result listActions(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listActionsAsync(array $args = [])
 * @method \Aws\Result listExperimentTemplates(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listExperimentTemplatesAsync(array $args = [])
 * @method \Aws\Result listExperiments(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listExperimentsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result listTargetResourceTypes(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTargetResourceTypesAsync(array $args = [])
 * @method \Aws\Result startExperiment(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise startExperimentAsync(array $args = [])
 * @method \Aws\Result stopExperiment(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise stopExperimentAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateExperimentTemplate(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateExperimentTemplateAsync(array $args = [])
 */
class FISClient extends AwsClient {}
