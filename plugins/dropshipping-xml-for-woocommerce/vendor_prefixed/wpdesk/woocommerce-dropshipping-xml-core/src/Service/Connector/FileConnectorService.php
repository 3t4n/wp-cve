<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Connector;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\ConnectorClientFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject;
/**
 * Class FileConnectorService, connect and get file.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Service\Connector
 */
class FileConnectorService
{
    /**
     * @var FileLocatorService
     */
    private $file_locator;
    /**
     * @var ConnectorClientFactory
     */
    private $connector_factory;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\ConnectorClientFactory $connector_factory, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Locator\FileLocatorService $file_locator)
    {
        $this->connector_factory = $connector_factory;
        $this->file_locator = $file_locator;
    }
    public function get_file(string $tmp_file_path, array $parameters) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject
    {
        $path_info = \pathinfo($tmp_file_path);
        $this->file_locator->create_directory_path_if_not_exists($path_info['dirname']);
        $client = $this->connector_factory->create_client($parameters);
        return $client->get_file($tmp_file_path);
    }
}
