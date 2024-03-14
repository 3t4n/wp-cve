<?php
namespace Aws\MigrationHub;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Migration Hub** service.
 * @method \Aws\Result associateCreatedArtifact(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise associateCreatedArtifactAsync(array $args = [])
 * @method \Aws\Result associateDiscoveredResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise associateDiscoveredResourceAsync(array $args = [])
 * @method \Aws\Result createProgressUpdateStream(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createProgressUpdateStreamAsync(array $args = [])
 * @method \Aws\Result deleteProgressUpdateStream(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteProgressUpdateStreamAsync(array $args = [])
 * @method \Aws\Result describeApplicationState(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeApplicationStateAsync(array $args = [])
 * @method \Aws\Result describeMigrationTask(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeMigrationTaskAsync(array $args = [])
 * @method \Aws\Result disassociateCreatedArtifact(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise disassociateCreatedArtifactAsync(array $args = [])
 * @method \Aws\Result disassociateDiscoveredResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise disassociateDiscoveredResourceAsync(array $args = [])
 * @method \Aws\Result importMigrationTask(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise importMigrationTaskAsync(array $args = [])
 * @method \Aws\Result listApplicationStates(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listApplicationStatesAsync(array $args = [])
 * @method \Aws\Result listCreatedArtifacts(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listCreatedArtifactsAsync(array $args = [])
 * @method \Aws\Result listDiscoveredResources(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listDiscoveredResourcesAsync(array $args = [])
 * @method \Aws\Result listMigrationTasks(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listMigrationTasksAsync(array $args = [])
 * @method \Aws\Result listProgressUpdateStreams(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listProgressUpdateStreamsAsync(array $args = [])
 * @method \Aws\Result notifyApplicationState(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise notifyApplicationStateAsync(array $args = [])
 * @method \Aws\Result notifyMigrationTaskState(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise notifyMigrationTaskStateAsync(array $args = [])
 * @method \Aws\Result putResourceAttributes(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putResourceAttributesAsync(array $args = [])
 */
class MigrationHubClient extends AwsClient {}
