<?php
namespace Aws\CloudHSMV2;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS CloudHSM V2** service.
 * @method \Aws\Result copyBackupToRegion(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise copyBackupToRegionAsync(array $args = [])
 * @method \Aws\Result createCluster(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createClusterAsync(array $args = [])
 * @method \Aws\Result createHsm(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createHsmAsync(array $args = [])
 * @method \Aws\Result deleteBackup(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteBackupAsync(array $args = [])
 * @method \Aws\Result deleteCluster(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteClusterAsync(array $args = [])
 * @method \Aws\Result deleteHsm(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteHsmAsync(array $args = [])
 * @method \Aws\Result describeBackups(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeBackupsAsync(array $args = [])
 * @method \Aws\Result describeClusters(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeClustersAsync(array $args = [])
 * @method \Aws\Result initializeCluster(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise initializeClusterAsync(array $args = [])
 * @method \Aws\Result listTags(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsAsync(array $args = [])
 * @method \Aws\Result modifyBackupAttributes(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise modifyBackupAttributesAsync(array $args = [])
 * @method \Aws\Result modifyCluster(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise modifyClusterAsync(array $args = [])
 * @method \Aws\Result restoreBackup(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise restoreBackupAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 */
class CloudHSMV2Client extends AwsClient {}
