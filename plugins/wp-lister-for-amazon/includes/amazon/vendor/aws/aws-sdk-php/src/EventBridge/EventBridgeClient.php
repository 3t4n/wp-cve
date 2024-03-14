<?php
namespace Aws\EventBridge;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon EventBridge** service.
 * @method \Aws\Result activateEventSource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise activateEventSourceAsync(array $args = [])
 * @method \Aws\Result cancelReplay(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise cancelReplayAsync(array $args = [])
 * @method \Aws\Result createApiDestination(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createApiDestinationAsync(array $args = [])
 * @method \Aws\Result createArchive(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createArchiveAsync(array $args = [])
 * @method \Aws\Result createConnection(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createConnectionAsync(array $args = [])
 * @method \Aws\Result createEndpoint(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createEndpointAsync(array $args = [])
 * @method \Aws\Result createEventBus(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createEventBusAsync(array $args = [])
 * @method \Aws\Result createPartnerEventSource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createPartnerEventSourceAsync(array $args = [])
 * @method \Aws\Result deactivateEventSource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deactivateEventSourceAsync(array $args = [])
 * @method \Aws\Result deauthorizeConnection(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deauthorizeConnectionAsync(array $args = [])
 * @method \Aws\Result deleteApiDestination(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteApiDestinationAsync(array $args = [])
 * @method \Aws\Result deleteArchive(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteArchiveAsync(array $args = [])
 * @method \Aws\Result deleteConnection(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteConnectionAsync(array $args = [])
 * @method \Aws\Result deleteEndpoint(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteEndpointAsync(array $args = [])
 * @method \Aws\Result deleteEventBus(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteEventBusAsync(array $args = [])
 * @method \Aws\Result deletePartnerEventSource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deletePartnerEventSourceAsync(array $args = [])
 * @method \Aws\Result deleteRule(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteRuleAsync(array $args = [])
 * @method \Aws\Result describeApiDestination(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeApiDestinationAsync(array $args = [])
 * @method \Aws\Result describeArchive(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeArchiveAsync(array $args = [])
 * @method \Aws\Result describeConnection(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeConnectionAsync(array $args = [])
 * @method \Aws\Result describeEndpoint(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeEndpointAsync(array $args = [])
 * @method \Aws\Result describeEventBus(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeEventBusAsync(array $args = [])
 * @method \Aws\Result describeEventSource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeEventSourceAsync(array $args = [])
 * @method \Aws\Result describePartnerEventSource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describePartnerEventSourceAsync(array $args = [])
 * @method \Aws\Result describeReplay(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeReplayAsync(array $args = [])
 * @method \Aws\Result describeRule(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeRuleAsync(array $args = [])
 * @method \Aws\Result disableRule(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise disableRuleAsync(array $args = [])
 * @method \Aws\Result enableRule(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise enableRuleAsync(array $args = [])
 * @method \Aws\Result listApiDestinations(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listApiDestinationsAsync(array $args = [])
 * @method \Aws\Result listArchives(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listArchivesAsync(array $args = [])
 * @method \Aws\Result listConnections(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listConnectionsAsync(array $args = [])
 * @method \Aws\Result listEndpoints(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listEndpointsAsync(array $args = [])
 * @method \Aws\Result listEventBuses(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listEventBusesAsync(array $args = [])
 * @method \Aws\Result listEventSources(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listEventSourcesAsync(array $args = [])
 * @method \Aws\Result listPartnerEventSourceAccounts(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listPartnerEventSourceAccountsAsync(array $args = [])
 * @method \Aws\Result listPartnerEventSources(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listPartnerEventSourcesAsync(array $args = [])
 * @method \Aws\Result listReplays(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listReplaysAsync(array $args = [])
 * @method \Aws\Result listRuleNamesByTarget(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listRuleNamesByTargetAsync(array $args = [])
 * @method \Aws\Result listRules(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listRulesAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result listTargetsByRule(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTargetsByRuleAsync(array $args = [])
 * @method \Aws\Result putEvents(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putEventsAsync(array $args = [])
 * @method \Aws\Result putPartnerEvents(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putPartnerEventsAsync(array $args = [])
 * @method \Aws\Result putPermission(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putPermissionAsync(array $args = [])
 * @method \Aws\Result putRule(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putRuleAsync(array $args = [])
 * @method \Aws\Result putTargets(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putTargetsAsync(array $args = [])
 * @method \Aws\Result removePermission(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise removePermissionAsync(array $args = [])
 * @method \Aws\Result removeTargets(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise removeTargetsAsync(array $args = [])
 * @method \Aws\Result startReplay(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise startReplayAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result testEventPattern(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise testEventPatternAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateApiDestination(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateApiDestinationAsync(array $args = [])
 * @method \Aws\Result updateArchive(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateArchiveAsync(array $args = [])
 * @method \Aws\Result updateConnection(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateConnectionAsync(array $args = [])
 * @method \Aws\Result updateEndpoint(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateEndpointAsync(array $args = [])
 */
class EventBridgeClient extends AwsClient {
    public function __construct(array $args)
    {
        parent::__construct($args);
        $stack = $this->getHandlerList();
        $isCustomEndpoint = isset($args['endpoint']);
        $stack->appendBuild(
            EventBridgeEndpointMiddleware::wrap(
                $this->getRegion(),
                [
                    'use_fips_endpoint' =>
                        $this->getConfig('use_fips_endpoint')->isUseFipsEndpoint(),
                    'dual_stack' =>
                        $this->getConfig('use_dual_stack_endpoint')->isUseDualStackEndpoint(),
                ],
                $this->getConfig('endpoint_provider'),
                $isCustomEndpoint
            ),
            'eventbridge.endpoint_middleware'
        );
    }
}
