<?php
namespace Aws\EBS;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Elastic Block Store** service.
 * @method \Aws\Result completeSnapshot(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise completeSnapshotAsync(array $args = [])
 * @method \Aws\Result getSnapshotBlock(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getSnapshotBlockAsync(array $args = [])
 * @method \Aws\Result listChangedBlocks(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listChangedBlocksAsync(array $args = [])
 * @method \Aws\Result listSnapshotBlocks(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listSnapshotBlocksAsync(array $args = [])
 * @method \Aws\Result putSnapshotBlock(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putSnapshotBlockAsync(array $args = [])
 * @method \Aws\Result startSnapshot(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise startSnapshotAsync(array $args = [])
 */
class EBSClient extends AwsClient {}
