<?php
namespace Aws\IoT1ClickDevicesService;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS IoT 1-Click Devices Service** service.
 * @method \Aws\Result claimDevicesByClaimCode(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise claimDevicesByClaimCodeAsync(array $args = [])
 * @method \Aws\Result describeDevice(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeDeviceAsync(array $args = [])
 * @method \Aws\Result finalizeDeviceClaim(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise finalizeDeviceClaimAsync(array $args = [])
 * @method \Aws\Result getDeviceMethods(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getDeviceMethodsAsync(array $args = [])
 * @method \Aws\Result initiateDeviceClaim(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise initiateDeviceClaimAsync(array $args = [])
 * @method \Aws\Result invokeDeviceMethod(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise invokeDeviceMethodAsync(array $args = [])
 * @method \Aws\Result listDeviceEvents(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listDeviceEventsAsync(array $args = [])
 * @method \Aws\Result listDevices(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listDevicesAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result unclaimDevice(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise unclaimDeviceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateDeviceState(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateDeviceStateAsync(array $args = [])
 */
class IoT1ClickDevicesServiceClient extends AwsClient {}
