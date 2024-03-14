<?php
namespace Aws\ManagedGrafana;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Managed Grafana** service.
 * @method \Aws\Result associateLicense(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise associateLicenseAsync(array $args = [])
 * @method \Aws\Result createWorkspace(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createWorkspaceAsync(array $args = [])
 * @method \Aws\Result createWorkspaceApiKey(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createWorkspaceApiKeyAsync(array $args = [])
 * @method \Aws\Result deleteWorkspace(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteWorkspaceAsync(array $args = [])
 * @method \Aws\Result deleteWorkspaceApiKey(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteWorkspaceApiKeyAsync(array $args = [])
 * @method \Aws\Result describeWorkspace(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeWorkspaceAsync(array $args = [])
 * @method \Aws\Result describeWorkspaceAuthentication(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeWorkspaceAuthenticationAsync(array $args = [])
 * @method \Aws\Result disassociateLicense(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise disassociateLicenseAsync(array $args = [])
 * @method \Aws\Result listPermissions(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listPermissionsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result listWorkspaces(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listWorkspacesAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updatePermissions(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updatePermissionsAsync(array $args = [])
 * @method \Aws\Result updateWorkspace(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateWorkspaceAsync(array $args = [])
 * @method \Aws\Result updateWorkspaceAuthentication(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateWorkspaceAuthenticationAsync(array $args = [])
 */
class ManagedGrafanaClient extends AwsClient {}
