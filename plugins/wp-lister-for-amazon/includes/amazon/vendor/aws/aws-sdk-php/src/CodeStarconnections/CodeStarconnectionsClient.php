<?php
namespace Aws\CodeStarconnections;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS CodeStar connections** service.
 * @method \Aws\Result createConnection(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createConnectionAsync(array $args = [])
 * @method \Aws\Result createHost(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createHostAsync(array $args = [])
 * @method \Aws\Result deleteConnection(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteConnectionAsync(array $args = [])
 * @method \Aws\Result deleteHost(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteHostAsync(array $args = [])
 * @method \Aws\Result getConnection(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getConnectionAsync(array $args = [])
 * @method \Aws\Result getHost(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getHostAsync(array $args = [])
 * @method \Aws\Result listConnections(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listConnectionsAsync(array $args = [])
 * @method \Aws\Result listHosts(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listHostsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateHost(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateHostAsync(array $args = [])
 */
class CodeStarconnectionsClient extends AwsClient {}
