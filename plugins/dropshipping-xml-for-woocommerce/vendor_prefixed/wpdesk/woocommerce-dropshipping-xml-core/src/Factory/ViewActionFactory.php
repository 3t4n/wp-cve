<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Config\MenuConfig;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction\DependencyResolverInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Renderable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Viewer\CsvSidebarViewerService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Viewer\XmlSidebarViewerService;
use RuntimeException;
/**
 * Class ViewActionFactory, view action factory.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Factory
 */
class ViewActionFactory
{
    /**
     * @var DependencyResolverInterface
     */
    private $resolver;
    /**
     * @var PluginHelper
     */
    private $plugin_helper;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction\DependencyResolverInterface $resolver, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper $plugin_helper)
    {
        $this->resolver = $resolver;
        $this->plugin_helper = $plugin_helper;
    }
    public function create_from_request(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request $request) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable
    {
        $page = $request->get_param('get.page')->getAsString();
        $action = empty($request->get_param('get.action')->getAsString()) ? \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Config\MenuConfig::ACTION_DEFAULT : $request->get_param('get.action')->getAsString();
        if ($this->plugin_helper->is_plugin_page($page, $action)) {
            return $this->resolver->resolve($this->plugin_helper->get_view_by_page_action($page, $action));
        }
        throw new \RuntimeException('View service not exists');
    }
    public function create_sidebar_by_data_type(string $data_type, array $parameters) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable
    {
        $view = $data_type == \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\DataFormat::XML ? \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Viewer\XmlSidebarViewerService::class : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Viewer\CsvSidebarViewerService::class;
        return $this->resolver->resolve($view, $parameters);
    }
}
