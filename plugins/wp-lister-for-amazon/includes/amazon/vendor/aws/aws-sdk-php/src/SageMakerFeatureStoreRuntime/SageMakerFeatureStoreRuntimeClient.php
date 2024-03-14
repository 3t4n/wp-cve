<?php
namespace Aws\SageMakerFeatureStoreRuntime;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon SageMaker Feature Store Runtime** service.
 * @method \Aws\Result batchGetRecord(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise batchGetRecordAsync(array $args = [])
 * @method \Aws\Result deleteRecord(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteRecordAsync(array $args = [])
 * @method \Aws\Result getRecord(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getRecordAsync(array $args = [])
 * @method \Aws\Result putRecord(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putRecordAsync(array $args = [])
 */
class SageMakerFeatureStoreRuntimeClient extends AwsClient {}
