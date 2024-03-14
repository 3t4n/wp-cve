<?php
namespace Aws\Route53RecoveryCluster;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Route53 Recovery Cluster** service.
 * @method \Aws\Result getRoutingControlState(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getRoutingControlStateAsync(array $args = [])
 * @method \Aws\Result listRoutingControls(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listRoutingControlsAsync(array $args = [])
 * @method \Aws\Result updateRoutingControlState(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateRoutingControlStateAsync(array $args = [])
 * @method \Aws\Result updateRoutingControlStates(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateRoutingControlStatesAsync(array $args = [])
 */
class Route53RecoveryClusterClient extends AwsClient {}
