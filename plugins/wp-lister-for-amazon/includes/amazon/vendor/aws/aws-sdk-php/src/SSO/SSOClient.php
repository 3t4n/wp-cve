<?php
namespace Aws\SSO;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Single Sign-On** service.
 * @method \Aws\Result getRoleCredentials(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getRoleCredentialsAsync(array $args = [])
 * @method \Aws\Result listAccountRoles(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listAccountRolesAsync(array $args = [])
 * @method \Aws\Result listAccounts(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listAccountsAsync(array $args = [])
 * @method \Aws\Result logout(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise logoutAsync(array $args = [])
 */
class SSOClient extends AwsClient {}
