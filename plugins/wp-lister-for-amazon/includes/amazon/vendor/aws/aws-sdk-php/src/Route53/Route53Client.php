<?php
namespace Aws\Route53;

use Aws\AwsClient;
use Aws\CommandInterface;
use Psr\Http\Message\RequestInterface;

/**
 * This client is used to interact with the **Amazon Route 53** service.
 *
 * @method \Aws\Result activateKeySigningKey(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise activateKeySigningKeyAsync(array $args = [])
 * @method \Aws\Result associateVPCWithHostedZone(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise associateVPCWithHostedZoneAsync(array $args = [])
 * @method \Aws\Result changeCidrCollection(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise changeCidrCollectionAsync(array $args = [])
 * @method \Aws\Result changeResourceRecordSets(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise changeResourceRecordSetsAsync(array $args = [])
 * @method \Aws\Result changeTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise changeTagsForResourceAsync(array $args = [])
 * @method \Aws\Result createCidrCollection(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createCidrCollectionAsync(array $args = [])
 * @method \Aws\Result createHealthCheck(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createHealthCheckAsync(array $args = [])
 * @method \Aws\Result createHostedZone(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createHostedZoneAsync(array $args = [])
 * @method \Aws\Result createKeySigningKey(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createKeySigningKeyAsync(array $args = [])
 * @method \Aws\Result createQueryLoggingConfig(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createQueryLoggingConfigAsync(array $args = [])
 * @method \Aws\Result createReusableDelegationSet(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createReusableDelegationSetAsync(array $args = [])
 * @method \Aws\Result createTrafficPolicy(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createTrafficPolicyAsync(array $args = [])
 * @method \Aws\Result createTrafficPolicyInstance(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createTrafficPolicyInstanceAsync(array $args = [])
 * @method \Aws\Result createTrafficPolicyVersion(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createTrafficPolicyVersionAsync(array $args = [])
 * @method \Aws\Result createVPCAssociationAuthorization(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createVPCAssociationAuthorizationAsync(array $args = [])
 * @method \Aws\Result deactivateKeySigningKey(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deactivateKeySigningKeyAsync(array $args = [])
 * @method \Aws\Result deleteCidrCollection(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteCidrCollectionAsync(array $args = [])
 * @method \Aws\Result deleteHealthCheck(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteHealthCheckAsync(array $args = [])
 * @method \Aws\Result deleteHostedZone(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteHostedZoneAsync(array $args = [])
 * @method \Aws\Result deleteKeySigningKey(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteKeySigningKeyAsync(array $args = [])
 * @method \Aws\Result deleteQueryLoggingConfig(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteQueryLoggingConfigAsync(array $args = [])
 * @method \Aws\Result deleteReusableDelegationSet(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteReusableDelegationSetAsync(array $args = [])
 * @method \Aws\Result deleteTrafficPolicy(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteTrafficPolicyAsync(array $args = [])
 * @method \Aws\Result deleteTrafficPolicyInstance(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteTrafficPolicyInstanceAsync(array $args = [])
 * @method \Aws\Result deleteVPCAssociationAuthorization(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteVPCAssociationAuthorizationAsync(array $args = [])
 * @method \Aws\Result disableHostedZoneDNSSEC(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise disableHostedZoneDNSSECAsync(array $args = [])
 * @method \Aws\Result disassociateVPCFromHostedZone(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise disassociateVPCFromHostedZoneAsync(array $args = [])
 * @method \Aws\Result enableHostedZoneDNSSEC(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise enableHostedZoneDNSSECAsync(array $args = [])
 * @method \Aws\Result getAccountLimit(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getAccountLimitAsync(array $args = [])
 * @method \Aws\Result getChange(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getChangeAsync(array $args = [])
 * @method \Aws\Result getCheckerIpRanges(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getCheckerIpRangesAsync(array $args = [])
 * @method \Aws\Result getDNSSEC(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getDNSSECAsync(array $args = [])
 * @method \Aws\Result getGeoLocation(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getGeoLocationAsync(array $args = [])
 * @method \Aws\Result getHealthCheck(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getHealthCheckAsync(array $args = [])
 * @method \Aws\Result getHealthCheckCount(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getHealthCheckCountAsync(array $args = [])
 * @method \Aws\Result getHealthCheckLastFailureReason(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getHealthCheckLastFailureReasonAsync(array $args = [])
 * @method \Aws\Result getHealthCheckStatus(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getHealthCheckStatusAsync(array $args = [])
 * @method \Aws\Result getHostedZone(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getHostedZoneAsync(array $args = [])
 * @method \Aws\Result getHostedZoneCount(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getHostedZoneCountAsync(array $args = [])
 * @method \Aws\Result getHostedZoneLimit(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getHostedZoneLimitAsync(array $args = [])
 * @method \Aws\Result getQueryLoggingConfig(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getQueryLoggingConfigAsync(array $args = [])
 * @method \Aws\Result getReusableDelegationSet(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getReusableDelegationSetAsync(array $args = [])
 * @method \Aws\Result getReusableDelegationSetLimit(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getReusableDelegationSetLimitAsync(array $args = [])
 * @method \Aws\Result getTrafficPolicy(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getTrafficPolicyAsync(array $args = [])
 * @method \Aws\Result getTrafficPolicyInstance(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getTrafficPolicyInstanceAsync(array $args = [])
 * @method \Aws\Result getTrafficPolicyInstanceCount(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getTrafficPolicyInstanceCountAsync(array $args = [])
 * @method \Aws\Result listCidrBlocks(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listCidrBlocksAsync(array $args = [])
 * @method \Aws\Result listCidrCollections(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listCidrCollectionsAsync(array $args = [])
 * @method \Aws\Result listCidrLocations(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listCidrLocationsAsync(array $args = [])
 * @method \Aws\Result listGeoLocations(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listGeoLocationsAsync(array $args = [])
 * @method \Aws\Result listHealthChecks(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listHealthChecksAsync(array $args = [])
 * @method \Aws\Result listHostedZones(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listHostedZonesAsync(array $args = [])
 * @method \Aws\Result listHostedZonesByName(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listHostedZonesByNameAsync(array $args = [])
 * @method \Aws\Result listHostedZonesByVPC(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listHostedZonesByVPCAsync(array $args = [])
 * @method \Aws\Result listQueryLoggingConfigs(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listQueryLoggingConfigsAsync(array $args = [])
 * @method \Aws\Result listResourceRecordSets(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listResourceRecordSetsAsync(array $args = [])
 * @method \Aws\Result listReusableDelegationSets(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listReusableDelegationSetsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result listTagsForResources(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourcesAsync(array $args = [])
 * @method \Aws\Result listTrafficPolicies(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTrafficPoliciesAsync(array $args = [])
 * @method \Aws\Result listTrafficPolicyInstances(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTrafficPolicyInstancesAsync(array $args = [])
 * @method \Aws\Result listTrafficPolicyInstancesByHostedZone(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTrafficPolicyInstancesByHostedZoneAsync(array $args = [])
 * @method \Aws\Result listTrafficPolicyInstancesByPolicy(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTrafficPolicyInstancesByPolicyAsync(array $args = [])
 * @method \Aws\Result listTrafficPolicyVersions(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTrafficPolicyVersionsAsync(array $args = [])
 * @method \Aws\Result listVPCAssociationAuthorizations(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listVPCAssociationAuthorizationsAsync(array $args = [])
 * @method \Aws\Result testDNSAnswer(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise testDNSAnswerAsync(array $args = [])
 * @method \Aws\Result updateHealthCheck(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateHealthCheckAsync(array $args = [])
 * @method \Aws\Result updateHostedZoneComment(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateHostedZoneCommentAsync(array $args = [])
 * @method \Aws\Result updateTrafficPolicyComment(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateTrafficPolicyCommentAsync(array $args = [])
 * @method \Aws\Result updateTrafficPolicyInstance(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateTrafficPolicyInstanceAsync(array $args = [])
 */
class Route53Client extends AwsClient
{
    public function __construct(array $args)
    {
        parent::__construct($args);
        $this->getHandlerList()->appendInit($this->cleanIdFn(), 'route53.clean_id');
    }

    private function cleanIdFn()
    {
        return function (callable $handler) {
            return function (CommandInterface $c, RequestInterface $r = null) use ($handler) {
                foreach (['Id', 'HostedZoneId', 'DelegationSetId'] as $clean) {
                    if ($c->hasParam($clean)) {
                        $c[$clean] = $this->cleanId($c[$clean]);
                    }
                }
                return $handler($c, $r);
            };
        };
    }

    private function cleanId($id)
    {
        static $toClean = ['/hostedzone/', '/change/', '/delegationset/'];

        return str_replace($toClean, '', $id);
    }
}
