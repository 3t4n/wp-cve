<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportFileForm;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportManagerForm;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\SettingsForm;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction\DependencyResolverInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportCsvSelectorForm;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportXmlSelectorForm;
use RuntimeException;
/**
 * Class FormFactory, form factory.
 * @package WPDesk\Library\DropshippingXmlCore\Factory
 */
class FormFactory
{
    /**
     * @var DependencyResolverInterface
     */
    private $resolver;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction\DependencyResolverInterface $dependency_resolver)
    {
        $this->resolver = $dependency_resolver;
    }
    public function create_by_id($id)
    {
        $forms = $this->get_all_forms();
        foreach ($forms as $form) {
            if ($id === $form::get_id()) {
                return $this->resolver->resolve($form);
            }
        }
        throw new \RuntimeException('Error, form id ' . $id . ' not found.');
    }
    private function get_all_forms()
    {
        return [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportFileForm::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportCsvSelectorForm::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportXmlSelectorForm::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportManagerForm::class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\SettingsForm::class];
    }
}
