<?php
namespace Aws\KinesisVideoSignalingChannels;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Kinesis Video Signaling Channels** service.
 * @method \Aws\Result getIceServerConfig(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getIceServerConfigAsync(array $args = [])
 * @method \Aws\Result sendAlexaOfferToMaster(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise sendAlexaOfferToMasterAsync(array $args = [])
 */
class KinesisVideoSignalingChannelsClient extends AwsClient {}
