<?php
namespace Aws\DataPipeline;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Data Pipeline** service.
 *
 * @method \Aws\Result activatePipeline(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise activatePipelineAsync(array $args = [])
 * @method \Aws\Result addTags(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise addTagsAsync(array $args = [])
 * @method \Aws\Result createPipeline(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createPipelineAsync(array $args = [])
 * @method \Aws\Result deactivatePipeline(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deactivatePipelineAsync(array $args = [])
 * @method \Aws\Result deletePipeline(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deletePipelineAsync(array $args = [])
 * @method \Aws\Result describeObjects(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeObjectsAsync(array $args = [])
 * @method \Aws\Result describePipelines(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describePipelinesAsync(array $args = [])
 * @method \Aws\Result evaluateExpression(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise evaluateExpressionAsync(array $args = [])
 * @method \Aws\Result getPipelineDefinition(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getPipelineDefinitionAsync(array $args = [])
 * @method \Aws\Result listPipelines(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listPipelinesAsync(array $args = [])
 * @method \Aws\Result pollForTask(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise pollForTaskAsync(array $args = [])
 * @method \Aws\Result putPipelineDefinition(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putPipelineDefinitionAsync(array $args = [])
 * @method \Aws\Result queryObjects(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise queryObjectsAsync(array $args = [])
 * @method \Aws\Result removeTags(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise removeTagsAsync(array $args = [])
 * @method \Aws\Result reportTaskProgress(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise reportTaskProgressAsync(array $args = [])
 * @method \Aws\Result reportTaskRunnerHeartbeat(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise reportTaskRunnerHeartbeatAsync(array $args = [])
 * @method \Aws\Result setStatus(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise setStatusAsync(array $args = [])
 * @method \Aws\Result setTaskStatus(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise setTaskStatusAsync(array $args = [])
 * @method \Aws\Result validatePipelineDefinition(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise validatePipelineDefinitionAsync(array $args = [])
 */
class DataPipelineClient extends AwsClient {}
