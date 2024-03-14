<?php
namespace Aws\MarketplaceCatalog;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Marketplace Catalog Service** service.
 * @method \Aws\Result cancelChangeSet(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise cancelChangeSetAsync(array $args = [])
 * @method \Aws\Result describeChangeSet(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeChangeSetAsync(array $args = [])
 * @method \Aws\Result describeEntity(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeEntityAsync(array $args = [])
 * @method \Aws\Result listChangeSets(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listChangeSetsAsync(array $args = [])
 * @method \Aws\Result listEntities(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listEntitiesAsync(array $args = [])
 * @method \Aws\Result startChangeSet(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise startChangeSetAsync(array $args = [])
 */
class MarketplaceCatalogClient extends AwsClient {}
