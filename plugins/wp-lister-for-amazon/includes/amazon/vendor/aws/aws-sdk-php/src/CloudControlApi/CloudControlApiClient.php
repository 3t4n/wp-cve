<?php
namespace Aws\CloudControlApi;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Cloud Control API** service.
 * @method \Aws\Result cancelResourceRequest(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise cancelResourceRequestAsync(array $args = [])
 * @method \Aws\Result createResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createResourceAsync(array $args = [])
 * @method \Aws\Result deleteResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteResourceAsync(array $args = [])
 * @method \Aws\Result getResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getResourceAsync(array $args = [])
 * @method \Aws\Result getResourceRequestStatus(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getResourceRequestStatusAsync(array $args = [])
 * @method \Aws\Result listResourceRequests(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listResourceRequestsAsync(array $args = [])
 * @method \Aws\Result listResources(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listResourcesAsync(array $args = [])
 * @method \Aws\Result updateResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateResourceAsync(array $args = [])
 */
class CloudControlApiClient extends AwsClient {}
