<?php
namespace Aws\EC2InstanceConnect;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS EC2 Instance Connect** service.
 * @method \Aws\Result sendSSHPublicKey(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise sendSSHPublicKeyAsync(array $args = [])
 * @method \Aws\Result sendSerialConsoleSSHPublicKey(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise sendSerialConsoleSSHPublicKeyAsync(array $args = [])
 */
class EC2InstanceConnectClient extends AwsClient {}
