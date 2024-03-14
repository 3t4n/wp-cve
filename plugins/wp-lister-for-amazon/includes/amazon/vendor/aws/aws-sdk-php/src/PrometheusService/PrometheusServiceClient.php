<?php
namespace Aws\PrometheusService;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Prometheus Service** service.
 * @method \Aws\Result createAlertManagerDefinition(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createAlertManagerDefinitionAsync(array $args = [])
 * @method \Aws\Result createRuleGroupsNamespace(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createRuleGroupsNamespaceAsync(array $args = [])
 * @method \Aws\Result createWorkspace(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createWorkspaceAsync(array $args = [])
 * @method \Aws\Result deleteAlertManagerDefinition(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteAlertManagerDefinitionAsync(array $args = [])
 * @method \Aws\Result deleteRuleGroupsNamespace(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteRuleGroupsNamespaceAsync(array $args = [])
 * @method \Aws\Result deleteWorkspace(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteWorkspaceAsync(array $args = [])
 * @method \Aws\Result describeAlertManagerDefinition(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeAlertManagerDefinitionAsync(array $args = [])
 * @method \Aws\Result describeRuleGroupsNamespace(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeRuleGroupsNamespaceAsync(array $args = [])
 * @method \Aws\Result describeWorkspace(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeWorkspaceAsync(array $args = [])
 * @method \Aws\Result listRuleGroupsNamespaces(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listRuleGroupsNamespacesAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result listWorkspaces(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listWorkspacesAsync(array $args = [])
 * @method \Aws\Result putAlertManagerDefinition(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putAlertManagerDefinitionAsync(array $args = [])
 * @method \Aws\Result putRuleGroupsNamespace(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putRuleGroupsNamespaceAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateWorkspaceAlias(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateWorkspaceAliasAsync(array $args = [])
 */
class PrometheusServiceClient extends AwsClient {}
