<?php
namespace Aws\BackupGateway;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Backup Gateway** service.
 * @method \Aws\Result associateGatewayToServer(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise associateGatewayToServerAsync(array $args = [])
 * @method \Aws\Result createGateway(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createGatewayAsync(array $args = [])
 * @method \Aws\Result deleteGateway(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteGatewayAsync(array $args = [])
 * @method \Aws\Result deleteHypervisor(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteHypervisorAsync(array $args = [])
 * @method \Aws\Result disassociateGatewayFromServer(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise disassociateGatewayFromServerAsync(array $args = [])
 * @method \Aws\Result getGateway(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getGatewayAsync(array $args = [])
 * @method \Aws\Result importHypervisorConfiguration(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise importHypervisorConfigurationAsync(array $args = [])
 * @method \Aws\Result listGateways(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listGatewaysAsync(array $args = [])
 * @method \Aws\Result listHypervisors(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listHypervisorsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result listVirtualMachines(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listVirtualMachinesAsync(array $args = [])
 * @method \Aws\Result putMaintenanceStartTime(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putMaintenanceStartTimeAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result testHypervisorConfiguration(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise testHypervisorConfigurationAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateGatewayInformation(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateGatewayInformationAsync(array $args = [])
 * @method \Aws\Result updateGatewaySoftwareNow(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateGatewaySoftwareNowAsync(array $args = [])
 * @method \Aws\Result updateHypervisor(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateHypervisorAsync(array $args = [])
 */
class BackupGatewayClient extends AwsClient {}
