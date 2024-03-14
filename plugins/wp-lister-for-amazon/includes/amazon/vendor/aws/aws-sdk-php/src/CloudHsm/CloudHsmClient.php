<?php
namespace Aws\CloudHsm;

use Aws\Api\ApiProvider;
use Aws\Api\DocModel;
use Aws\Api\Service;
use Aws\AwsClient;

/**
 * This client is used to interact with **AWS CloudHSM**.
 *
 * @method \Aws\Result addTagsToResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise addTagsToResourceAsync(array $args = [])
 * @method \Aws\Result createHapg(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createHapgAsync(array $args = [])
 * @method \Aws\Result createHsm(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createHsmAsync(array $args = [])
 * @method \Aws\Result createLunaClient(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createLunaClientAsync(array $args = [])
 * @method \Aws\Result deleteHapg(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteHapgAsync(array $args = [])
 * @method \Aws\Result deleteHsm(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteHsmAsync(array $args = [])
 * @method \Aws\Result deleteLunaClient(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteLunaClientAsync(array $args = [])
 * @method \Aws\Result describeHapg(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeHapgAsync(array $args = [])
 * @method \Aws\Result describeHsm(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeHsmAsync(array $args = [])
 * @method \Aws\Result describeLunaClient(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeLunaClientAsync(array $args = [])
 * @method \Aws\Result getConfigFiles(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getConfigFilesAsync(array $args = [])
 * @method \Aws\Result listAvailableZones(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listAvailableZonesAsync(array $args = [])
 * @method \Aws\Result listHapgs(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listHapgsAsync(array $args = [])
 * @method \Aws\Result listHsms(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listHsmsAsync(array $args = [])
 * @method \Aws\Result listLunaClients(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listLunaClientsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result modifyHapg(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise modifyHapgAsync(array $args = [])
 * @method \Aws\Result modifyHsm(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise modifyHsmAsync(array $args = [])
 * @method \Aws\Result modifyLunaClient(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise modifyLunaClientAsync(array $args = [])
 * @method \Aws\Result removeTagsFromResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise removeTagsFromResourceAsync(array $args = [])
 */
class CloudHsmClient extends AwsClient {}
