<?php
namespace Aws\KafkaConnect;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Managed Streaming for Kafka Connect** service.
 * @method \Aws\Result createConnector(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createConnectorAsync(array $args = [])
 * @method \Aws\Result createCustomPlugin(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createCustomPluginAsync(array $args = [])
 * @method \Aws\Result createWorkerConfiguration(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createWorkerConfigurationAsync(array $args = [])
 * @method \Aws\Result deleteConnector(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteConnectorAsync(array $args = [])
 * @method \Aws\Result deleteCustomPlugin(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteCustomPluginAsync(array $args = [])
 * @method \Aws\Result describeConnector(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeConnectorAsync(array $args = [])
 * @method \Aws\Result describeCustomPlugin(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeCustomPluginAsync(array $args = [])
 * @method \Aws\Result describeWorkerConfiguration(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeWorkerConfigurationAsync(array $args = [])
 * @method \Aws\Result listConnectors(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listConnectorsAsync(array $args = [])
 * @method \Aws\Result listCustomPlugins(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listCustomPluginsAsync(array $args = [])
 * @method \Aws\Result listWorkerConfigurations(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listWorkerConfigurationsAsync(array $args = [])
 * @method \Aws\Result updateConnector(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateConnectorAsync(array $args = [])
 */
class KafkaConnectClient extends AwsClient {}
