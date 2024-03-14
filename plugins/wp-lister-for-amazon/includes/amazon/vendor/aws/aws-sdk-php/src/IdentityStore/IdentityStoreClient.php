<?php
namespace Aws\IdentityStore;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS SSO Identity Store** service.
 * @method \Aws\Result describeGroup(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeGroupAsync(array $args = [])
 * @method \Aws\Result describeUser(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeUserAsync(array $args = [])
 * @method \Aws\Result listGroups(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listGroupsAsync(array $args = [])
 * @method \Aws\Result listUsers(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listUsersAsync(array $args = [])
 */
class IdentityStoreClient extends AwsClient {}
