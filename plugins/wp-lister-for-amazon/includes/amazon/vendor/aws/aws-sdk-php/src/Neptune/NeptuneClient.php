<?php
namespace Aws\Neptune;

use Aws\AwsClient;
use Aws\PresignUrlMiddleware;

/**
 * This client is used to interact with the **Amazon Neptune** service.
 * @method \Aws\Result addRoleToDBCluster(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise addRoleToDBClusterAsync(array $args = [])
 * @method \Aws\Result addSourceIdentifierToSubscription(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise addSourceIdentifierToSubscriptionAsync(array $args = [])
 * @method \Aws\Result addTagsToResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise addTagsToResourceAsync(array $args = [])
 * @method \Aws\Result applyPendingMaintenanceAction(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise applyPendingMaintenanceActionAsync(array $args = [])
 * @method \Aws\Result copyDBClusterParameterGroup(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise copyDBClusterParameterGroupAsync(array $args = [])
 * @method \Aws\Result copyDBClusterSnapshot(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise copyDBClusterSnapshotAsync(array $args = [])
 * @method \Aws\Result copyDBParameterGroup(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise copyDBParameterGroupAsync(array $args = [])
 * @method \Aws\Result createDBCluster(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createDBClusterAsync(array $args = [])
 * @method \Aws\Result createDBClusterEndpoint(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createDBClusterEndpointAsync(array $args = [])
 * @method \Aws\Result createDBClusterParameterGroup(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createDBClusterParameterGroupAsync(array $args = [])
 * @method \Aws\Result createDBClusterSnapshot(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createDBClusterSnapshotAsync(array $args = [])
 * @method \Aws\Result createDBInstance(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createDBInstanceAsync(array $args = [])
 * @method \Aws\Result createDBParameterGroup(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createDBParameterGroupAsync(array $args = [])
 * @method \Aws\Result createDBSubnetGroup(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createDBSubnetGroupAsync(array $args = [])
 * @method \Aws\Result createEventSubscription(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createEventSubscriptionAsync(array $args = [])
 * @method \Aws\Result createGlobalCluster(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createGlobalClusterAsync(array $args = [])
 * @method \Aws\Result deleteDBCluster(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteDBClusterAsync(array $args = [])
 * @method \Aws\Result deleteDBClusterEndpoint(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteDBClusterEndpointAsync(array $args = [])
 * @method \Aws\Result deleteDBClusterParameterGroup(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteDBClusterParameterGroupAsync(array $args = [])
 * @method \Aws\Result deleteDBClusterSnapshot(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteDBClusterSnapshotAsync(array $args = [])
 * @method \Aws\Result deleteDBInstance(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteDBInstanceAsync(array $args = [])
 * @method \Aws\Result deleteDBParameterGroup(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteDBParameterGroupAsync(array $args = [])
 * @method \Aws\Result deleteDBSubnetGroup(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteDBSubnetGroupAsync(array $args = [])
 * @method \Aws\Result deleteEventSubscription(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteEventSubscriptionAsync(array $args = [])
 * @method \Aws\Result deleteGlobalCluster(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteGlobalClusterAsync(array $args = [])
 * @method \Aws\Result describeDBClusterEndpoints(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeDBClusterEndpointsAsync(array $args = [])
 * @method \Aws\Result describeDBClusterParameterGroups(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeDBClusterParameterGroupsAsync(array $args = [])
 * @method \Aws\Result describeDBClusterParameters(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeDBClusterParametersAsync(array $args = [])
 * @method \Aws\Result describeDBClusterSnapshotAttributes(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeDBClusterSnapshotAttributesAsync(array $args = [])
 * @method \Aws\Result describeDBClusterSnapshots(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeDBClusterSnapshotsAsync(array $args = [])
 * @method \Aws\Result describeDBClusters(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeDBClustersAsync(array $args = [])
 * @method \Aws\Result describeDBEngineVersions(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeDBEngineVersionsAsync(array $args = [])
 * @method \Aws\Result describeDBInstances(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeDBInstancesAsync(array $args = [])
 * @method \Aws\Result describeDBParameterGroups(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeDBParameterGroupsAsync(array $args = [])
 * @method \Aws\Result describeDBParameters(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeDBParametersAsync(array $args = [])
 * @method \Aws\Result describeDBSubnetGroups(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeDBSubnetGroupsAsync(array $args = [])
 * @method \Aws\Result describeEngineDefaultClusterParameters(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeEngineDefaultClusterParametersAsync(array $args = [])
 * @method \Aws\Result describeEngineDefaultParameters(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeEngineDefaultParametersAsync(array $args = [])
 * @method \Aws\Result describeEventCategories(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeEventCategoriesAsync(array $args = [])
 * @method \Aws\Result describeEventSubscriptions(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeEventSubscriptionsAsync(array $args = [])
 * @method \Aws\Result describeEvents(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeEventsAsync(array $args = [])
 * @method \Aws\Result describeGlobalClusters(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeGlobalClustersAsync(array $args = [])
 * @method \Aws\Result describeOrderableDBInstanceOptions(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeOrderableDBInstanceOptionsAsync(array $args = [])
 * @method \Aws\Result describePendingMaintenanceActions(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describePendingMaintenanceActionsAsync(array $args = [])
 * @method \Aws\Result describeValidDBInstanceModifications(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeValidDBInstanceModificationsAsync(array $args = [])
 * @method \Aws\Result failoverDBCluster(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise failoverDBClusterAsync(array $args = [])
 * @method \Aws\Result failoverGlobalCluster(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise failoverGlobalClusterAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result modifyDBCluster(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise modifyDBClusterAsync(array $args = [])
 * @method \Aws\Result modifyDBClusterEndpoint(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise modifyDBClusterEndpointAsync(array $args = [])
 * @method \Aws\Result modifyDBClusterParameterGroup(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise modifyDBClusterParameterGroupAsync(array $args = [])
 * @method \Aws\Result modifyDBClusterSnapshotAttribute(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise modifyDBClusterSnapshotAttributeAsync(array $args = [])
 * @method \Aws\Result modifyDBInstance(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise modifyDBInstanceAsync(array $args = [])
 * @method \Aws\Result modifyDBParameterGroup(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise modifyDBParameterGroupAsync(array $args = [])
 * @method \Aws\Result modifyDBSubnetGroup(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise modifyDBSubnetGroupAsync(array $args = [])
 * @method \Aws\Result modifyEventSubscription(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise modifyEventSubscriptionAsync(array $args = [])
 * @method \Aws\Result modifyGlobalCluster(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise modifyGlobalClusterAsync(array $args = [])
 * @method \Aws\Result promoteReadReplicaDBCluster(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise promoteReadReplicaDBClusterAsync(array $args = [])
 * @method \Aws\Result rebootDBInstance(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise rebootDBInstanceAsync(array $args = [])
 * @method \Aws\Result removeFromGlobalCluster(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise removeFromGlobalClusterAsync(array $args = [])
 * @method \Aws\Result removeRoleFromDBCluster(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise removeRoleFromDBClusterAsync(array $args = [])
 * @method \Aws\Result removeSourceIdentifierFromSubscription(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise removeSourceIdentifierFromSubscriptionAsync(array $args = [])
 * @method \Aws\Result removeTagsFromResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise removeTagsFromResourceAsync(array $args = [])
 * @method \Aws\Result resetDBClusterParameterGroup(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise resetDBClusterParameterGroupAsync(array $args = [])
 * @method \Aws\Result resetDBParameterGroup(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise resetDBParameterGroupAsync(array $args = [])
 * @method \Aws\Result restoreDBClusterFromSnapshot(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise restoreDBClusterFromSnapshotAsync(array $args = [])
 * @method \Aws\Result restoreDBClusterToPointInTime(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise restoreDBClusterToPointInTimeAsync(array $args = [])
 * @method \Aws\Result startDBCluster(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise startDBClusterAsync(array $args = [])
 * @method \Aws\Result stopDBCluster(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise stopDBClusterAsync(array $args = [])
 */
class NeptuneClient extends AwsClient {
    public function __construct(array $args)
    {
        $args['with_resolved'] = function (array $args) {
            $this->getHandlerList()->appendInit(
                PresignUrlMiddleware::wrap(
                    $this,
                    $args['endpoint_provider'],
                    [
                        'operations' => [
                            'CopyDBClusterSnapshot',
                            'CreateDBCluster',
                        ],
                        'service' => 'rds',
                        'presign_param' => 'PreSignedUrl',
                        'require_different_region' => true,
                        'extra_query_params' => [
                            'CopyDBClusterSnapshot' => ['DestinationRegion'],
                            'CreateDBCluster' => ['DestinationRegion'],
                        ]
                    ]
                ),
                'rds.presigner'
            );
        };
        parent::__construct($args);
    }
}
