<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Config;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Abstraction\AbstractSingleConfig;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Binder\DependencyBinderCollection;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\DependencyResolver;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\ConditionalServiceListener;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\HookableServiceListener;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable\InitableServiceListener;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Registrable\RegistrableServiceListener;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Container\ServiceContainer;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\ListenerCollection;
use DropshippingXmlFreeVendor\WPDesk\View\Resolver\ChainResolver;
use DropshippingXmlFreeVendor\WPDesk\View\Resolver\DirResolver;
use DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer;
use DropshippingXmlFreeVendor\WPDesk\View\Renderer\SimplePhpRenderer;
use DropshippingXmlFreeVendor\WPDesk\View\Resolver\Resolver;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\FormView;
use DropshippingXmlFreeVendor\Monolog\Logger;
use DropshippingXmlFreeVendor\WPDesk\Logger\WPDeskLoggerFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Viewer\SidebarViewerService;
/**
 * Class ServiceConfig, configuration class for services and it's dependencies.
 * @package WPDesk\Library\DropshippingXmlCore\Config
 */
class ServiceConfig extends \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Abstraction\AbstractSingleConfig
{
    const ID = 'service';
    public function get() : array
    {
        $config = $this->get_config();
        $request = new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request();
        return ['container' => \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Container\ServiceContainer::class, 'resolver' => \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\DependencyResolver::class, 'binder_collection' => \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Binder\DependencyBinderCollection::class, 'listener_collection' => \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\ListenerCollection::class, 'bind' => [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config::class => $config, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request::class => $request, \DropshippingXmlFreeVendor\WPDesk\View\Resolver\Resolver::class => function () use($config) {
            $resolver = new \DropshippingXmlFreeVendor\WPDesk\View\Resolver\ChainResolver();
            $resolver->appendResolver(new \DropshippingXmlFreeVendor\WPDesk\View\Resolver\DirResolver($config->get_param('templates.dir')->get()));
            $resolver->appendResolver(new \DropshippingXmlFreeVendor\WPDesk\View\Resolver\DirResolver($config->get_param('templates.form_fields_dir')->get()));
            $resolver->appendResolver(new \DropshippingXmlFreeVendor\WPDesk\View\Resolver\DirResolver($config->get_param('templates.core_dir')->get()));
            $resolver->appendResolver(new \DropshippingXmlFreeVendor\WPDesk\View\Resolver\DirResolver($config->get_param('templates.core_form_fields_dir')->get()));
            return $resolver;
        }, \DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer::class => \DropshippingXmlFreeVendor\WPDesk\View\Renderer\SimplePhpRenderer::class, \DropshippingXmlFreeVendor\Monolog\Logger::class => (new \DropshippingXmlFreeVendor\WPDesk\Logger\WPDeskLoggerFactory())->createWPDeskLogger(), \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::class => ['uid' => $this->get_uid($request)], \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Viewer\SidebarViewerService::class => ['uid' => $this->get_uid($request)]], 'forbidden' => [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\FormView::class], 'listeners' => [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\ConditionalServiceListener::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Registrable\RegistrableServiceListener::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\HookableServiceListener::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Initable\InitableServiceListener::class]];
    }
    public function get_id() : string
    {
        return self::ID;
    }
    protected function get_uid(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request $request) : string
    {
        $uid = $request->get_param('post.uid')->get();
        if (empty($uid)) {
            $uid = $request->get_param('get.uid')->get();
        }
        return !empty($uid) ? $uid : '';
    }
}
