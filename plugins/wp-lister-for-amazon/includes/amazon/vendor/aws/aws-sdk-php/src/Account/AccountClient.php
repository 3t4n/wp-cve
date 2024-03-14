<?php
namespace Aws\Account;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Account** service.
 * @method \Aws\Result deleteAlternateContact(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteAlternateContactAsync(array $args = [])
 * @method \Aws\Result getAlternateContact(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getAlternateContactAsync(array $args = [])
 * @method \Aws\Result getContactInformation(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getContactInformationAsync(array $args = [])
 * @method \Aws\Result putAlternateContact(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putAlternateContactAsync(array $args = [])
 * @method \Aws\Result putContactInformation(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putContactInformationAsync(array $args = [])
 */
class AccountClient extends AwsClient {}
