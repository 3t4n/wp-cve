<?php
namespace Aws\HealthLake;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon HealthLake** service.
 * @method \Aws\Result createFHIRDatastore(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createFHIRDatastoreAsync(array $args = [])
 * @method \Aws\Result deleteFHIRDatastore(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteFHIRDatastoreAsync(array $args = [])
 * @method \Aws\Result describeFHIRDatastore(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeFHIRDatastoreAsync(array $args = [])
 * @method \Aws\Result describeFHIRExportJob(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeFHIRExportJobAsync(array $args = [])
 * @method \Aws\Result describeFHIRImportJob(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeFHIRImportJobAsync(array $args = [])
 * @method \Aws\Result listFHIRDatastores(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listFHIRDatastoresAsync(array $args = [])
 * @method \Aws\Result listFHIRExportJobs(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listFHIRExportJobsAsync(array $args = [])
 * @method \Aws\Result listFHIRImportJobs(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listFHIRImportJobsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result startFHIRExportJob(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise startFHIRExportJobAsync(array $args = [])
 * @method \Aws\Result startFHIRImportJob(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise startFHIRImportJobAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 */
class HealthLakeClient extends AwsClient {}
