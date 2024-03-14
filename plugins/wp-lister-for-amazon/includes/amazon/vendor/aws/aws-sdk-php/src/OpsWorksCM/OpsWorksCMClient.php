<?php
namespace Aws\OpsWorksCM;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS OpsWorks for Chef Automate** service.
 * @method \Aws\Result associateNode(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise associateNodeAsync(array $args = [])
 * @method \Aws\Result createBackup(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createBackupAsync(array $args = [])
 * @method \Aws\Result createServer(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createServerAsync(array $args = [])
 * @method \Aws\Result deleteBackup(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteBackupAsync(array $args = [])
 * @method \Aws\Result deleteServer(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteServerAsync(array $args = [])
 * @method \Aws\Result describeAccountAttributes(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeAccountAttributesAsync(array $args = [])
 * @method \Aws\Result describeBackups(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeBackupsAsync(array $args = [])
 * @method \Aws\Result describeEvents(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeEventsAsync(array $args = [])
 * @method \Aws\Result describeNodeAssociationStatus(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeNodeAssociationStatusAsync(array $args = [])
 * @method \Aws\Result describeServers(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeServersAsync(array $args = [])
 * @method \Aws\Result disassociateNode(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise disassociateNodeAsync(array $args = [])
 * @method \Aws\Result exportServerEngineAttribute(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise exportServerEngineAttributeAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result restoreServer(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise restoreServerAsync(array $args = [])
 * @method \Aws\Result startMaintenance(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise startMaintenanceAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateServer(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateServerAsync(array $args = [])
 * @method \Aws\Result updateServerEngineAttributes(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateServerEngineAttributesAsync(array $args = [])
 */
class OpsWorksCMClient extends AwsClient {}
