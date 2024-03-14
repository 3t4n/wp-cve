<?php
namespace Aws\Health;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Health APIs and Notifications** service.
 * @method \Aws\Result describeAffectedAccountsForOrganization(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeAffectedAccountsForOrganizationAsync(array $args = [])
 * @method \Aws\Result describeAffectedEntities(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeAffectedEntitiesAsync(array $args = [])
 * @method \Aws\Result describeAffectedEntitiesForOrganization(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeAffectedEntitiesForOrganizationAsync(array $args = [])
 * @method \Aws\Result describeEntityAggregates(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeEntityAggregatesAsync(array $args = [])
 * @method \Aws\Result describeEventAggregates(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeEventAggregatesAsync(array $args = [])
 * @method \Aws\Result describeEventDetails(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeEventDetailsAsync(array $args = [])
 * @method \Aws\Result describeEventDetailsForOrganization(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeEventDetailsForOrganizationAsync(array $args = [])
 * @method \Aws\Result describeEventTypes(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeEventTypesAsync(array $args = [])
 * @method \Aws\Result describeEvents(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeEventsAsync(array $args = [])
 * @method \Aws\Result describeEventsForOrganization(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeEventsForOrganizationAsync(array $args = [])
 * @method \Aws\Result describeHealthServiceStatusForOrganization(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeHealthServiceStatusForOrganizationAsync(array $args = [])
 * @method \Aws\Result disableHealthServiceAccessForOrganization(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise disableHealthServiceAccessForOrganizationAsync(array $args = [])
 * @method \Aws\Result enableHealthServiceAccessForOrganization(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise enableHealthServiceAccessForOrganizationAsync(array $args = [])
 */
class HealthClient extends AwsClient {}
