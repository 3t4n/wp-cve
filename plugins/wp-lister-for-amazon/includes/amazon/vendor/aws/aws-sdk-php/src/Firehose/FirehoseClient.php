<?php
namespace Aws\Firehose;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Kinesis Firehose** service.
 *
 * @method \Aws\Result createDeliveryStream(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createDeliveryStreamAsync(array $args = [])
 * @method \Aws\Result deleteDeliveryStream(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteDeliveryStreamAsync(array $args = [])
 * @method \Aws\Result describeDeliveryStream(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeDeliveryStreamAsync(array $args = [])
 * @method \Aws\Result listDeliveryStreams(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listDeliveryStreamsAsync(array $args = [])
 * @method \Aws\Result listTagsForDeliveryStream(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForDeliveryStreamAsync(array $args = [])
 * @method \Aws\Result putRecord(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putRecordAsync(array $args = [])
 * @method \Aws\Result putRecordBatch(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putRecordBatchAsync(array $args = [])
 * @method \Aws\Result startDeliveryStreamEncryption(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise startDeliveryStreamEncryptionAsync(array $args = [])
 * @method \Aws\Result stopDeliveryStreamEncryption(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise stopDeliveryStreamEncryptionAsync(array $args = [])
 * @method \Aws\Result tagDeliveryStream(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagDeliveryStreamAsync(array $args = [])
 * @method \Aws\Result untagDeliveryStream(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagDeliveryStreamAsync(array $args = [])
 * @method \Aws\Result updateDestination(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateDestinationAsync(array $args = [])
 */
class FirehoseClient extends AwsClient {}
