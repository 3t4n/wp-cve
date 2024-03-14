<?php
namespace Aws\PinpointSMSVoice;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Pinpoint SMS and Voice Service** service.
 * @method \Aws\Result createConfigurationSet(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createConfigurationSetAsync(array $args = [])
 * @method \Aws\Result createConfigurationSetEventDestination(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createConfigurationSetEventDestinationAsync(array $args = [])
 * @method \Aws\Result deleteConfigurationSet(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteConfigurationSetAsync(array $args = [])
 * @method \Aws\Result deleteConfigurationSetEventDestination(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteConfigurationSetEventDestinationAsync(array $args = [])
 * @method \Aws\Result getConfigurationSetEventDestinations(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getConfigurationSetEventDestinationsAsync(array $args = [])
 * @method \Aws\Result listConfigurationSets(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listConfigurationSetsAsync(array $args = [])
 * @method \Aws\Result sendVoiceMessage(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise sendVoiceMessageAsync(array $args = [])
 * @method \Aws\Result updateConfigurationSetEventDestination(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateConfigurationSetEventDestinationAsync(array $args = [])
 */
class PinpointSMSVoiceClient extends AwsClient {}
