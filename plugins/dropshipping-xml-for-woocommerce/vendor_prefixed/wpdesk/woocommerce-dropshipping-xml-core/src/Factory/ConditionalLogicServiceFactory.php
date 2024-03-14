<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction\DependencyResolverInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\ConditionalLogic\ConditionalLogicService;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\ConditionalLogic\Abstraction\ConditionalLogicServiceInterface;
use RuntimeException;
/**
 * Class ConditionalLogicFactory, factory for conditional logic.
 * @package WPDesk\Library\DropshippingXmlCore\Factory
 */
class ConditionalLogicServiceFactory
{
    /**
     * @var DependencyResolverInterface
     */
    private $resolver;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction\DependencyResolverInterface $dependency_resolver)
    {
        $this->resolver = $dependency_resolver;
    }
    public function create_by_class_name(string $class_name, array $arguments = array()) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\ConditionalLogic\Abstraction\ConditionalLogicServiceInterface
    {
        $conditional_logic_services = $this->get_all();
        foreach ($conditional_logic_services as $service) {
            if ($class_name === $service) {
                return $this->resolver->resolve($service, $arguments);
            }
        }
        throw new \RuntimeException('Error, data conditiona logic service ' . $class_name . ' not found.');
    }
    private function get_all() : array
    {
        return [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\ConditionalLogic\ConditionalLogicService::class];
    }
}
