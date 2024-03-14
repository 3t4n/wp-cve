<?php
namespace Aws\EMRContainers;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon EMR Containers** service.
 * @method \Aws\Result cancelJobRun(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise cancelJobRunAsync(array $args = [])
 * @method \Aws\Result createManagedEndpoint(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createManagedEndpointAsync(array $args = [])
 * @method \Aws\Result createVirtualCluster(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createVirtualClusterAsync(array $args = [])
 * @method \Aws\Result deleteManagedEndpoint(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteManagedEndpointAsync(array $args = [])
 * @method \Aws\Result deleteVirtualCluster(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteVirtualClusterAsync(array $args = [])
 * @method \Aws\Result describeJobRun(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeJobRunAsync(array $args = [])
 * @method \Aws\Result describeManagedEndpoint(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeManagedEndpointAsync(array $args = [])
 * @method \Aws\Result describeVirtualCluster(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeVirtualClusterAsync(array $args = [])
 * @method \Aws\Result listJobRuns(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listJobRunsAsync(array $args = [])
 * @method \Aws\Result listManagedEndpoints(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listManagedEndpointsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result listVirtualClusters(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listVirtualClustersAsync(array $args = [])
 * @method \Aws\Result startJobRun(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise startJobRunAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 */
class EMRContainersClient extends AwsClient {}
