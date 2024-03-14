<?php
namespace Aws\PI;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Performance Insights** service.
 * @method \Aws\Result describeDimensionKeys(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeDimensionKeysAsync(array $args = [])
 * @method \Aws\Result getDimensionKeyDetails(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getDimensionKeyDetailsAsync(array $args = [])
 * @method \Aws\Result getResourceMetadata(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getResourceMetadataAsync(array $args = [])
 * @method \Aws\Result getResourceMetrics(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getResourceMetricsAsync(array $args = [])
 * @method \Aws\Result listAvailableResourceDimensions(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listAvailableResourceDimensionsAsync(array $args = [])
 * @method \Aws\Result listAvailableResourceMetrics(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listAvailableResourceMetricsAsync(array $args = [])
 */
class PIClient extends AwsClient {}
