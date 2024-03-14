<?php
namespace Aws\AppIntegrationsService;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon AppIntegrations Service** service.
 * @method \Aws\Result createDataIntegration(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createDataIntegrationAsync(array $args = [])
 * @method \Aws\Result createEventIntegration(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createEventIntegrationAsync(array $args = [])
 * @method \Aws\Result deleteDataIntegration(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteDataIntegrationAsync(array $args = [])
 * @method \Aws\Result deleteEventIntegration(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteEventIntegrationAsync(array $args = [])
 * @method \Aws\Result getDataIntegration(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getDataIntegrationAsync(array $args = [])
 * @method \Aws\Result getEventIntegration(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getEventIntegrationAsync(array $args = [])
 * @method \Aws\Result listDataIntegrationAssociations(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listDataIntegrationAssociationsAsync(array $args = [])
 * @method \Aws\Result listDataIntegrations(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listDataIntegrationsAsync(array $args = [])
 * @method \Aws\Result listEventIntegrationAssociations(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listEventIntegrationAssociationsAsync(array $args = [])
 * @method \Aws\Result listEventIntegrations(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listEventIntegrationsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateDataIntegration(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateDataIntegrationAsync(array $args = [])
 * @method \Aws\Result updateEventIntegration(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateEventIntegrationAsync(array $args = [])
 */
class AppIntegrationsServiceClient extends AwsClient {}
