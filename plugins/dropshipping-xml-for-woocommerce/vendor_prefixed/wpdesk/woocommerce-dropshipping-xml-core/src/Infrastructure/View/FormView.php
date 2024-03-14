<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View;

use DropshippingXmlFreeVendor\WPDesk\Forms\Field;
use DropshippingXmlFreeVendor\WPDesk\Forms\Form\FormWithFields;
use DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer;
use InvalidArgumentException;
/**
 * Class FormView, form view class. Shows rendered parts of form fields.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\View
 */
class FormView
{
    /**
     * @var FormWithFields
     */
    private $form;
    /**
     * @var Renderer
     */
    private $renderer;
    /**
     * @var array
     */
    private $used = [];
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Forms\Form\FormWithFields $form, \DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer $renderer)
    {
        $this->form = $form;
        $this->renderer = $renderer;
    }
    public function is_valid() : bool
    {
        return $this->form->is_valid();
    }
    public function form_start(array $parameters = [])
    {
        $template = isset($parameters['template']) ? $parameters['template'] : 'form-start';
        $merge = \array_merge(['method' => 'POST', 'action' => ''], $parameters);
        $this->renderer->output_render($template, $merge);
    }
    public function show_field($id, array $parameters = [])
    {
        $field = $this->get_field_by_id($id);
        $this->add_to_used($field);
        $this->render_field($field, $parameters);
    }
    public function has_field(string $id) : bool
    {
        try {
            $field = $this->get_field_by_id($id);
            return $field instanceof \DropshippingXmlFreeVendor\WPDesk\Forms\Field;
        } catch (\InvalidArgumentException $e) {
            return \false;
        }
    }
    public function show_label(string $id, array $parameters = [])
    {
        $field = $this->get_field_by_id($id);
        $this->render_field_property($field, $parameters, $field->has_label() ? $field->get_label() : '');
    }
    public function show_description(string $id, array $parameters = [])
    {
        $field = $this->get_field_by_id($id);
        $this->render_field_property($field, $parameters, $field->has_description() ? $field->get_description() : '');
    }
    public function form_fields_complete(array $parameters = [])
    {
        $content = '';
        foreach ($this->form->get_fields() as $field) {
            if (!$this->is_used($field->get_id())) {
                $this->add_to_used($field);
                $content .= $this->render_field($field, $parameters);
            }
        }
        return $content;
    }
    public function form_end(array $parameters = [])
    {
        $template = isset($parameters['template']) ? $parameters['template'] : 'form-end';
        $this->renderer->output_render($template);
    }
    private function get_field_by_id(string $id) : \DropshippingXmlFreeVendor\WPDesk\Forms\Field
    {
        if ($this->is_used($id)) {
            return $this->used[$id];
        }
        foreach ($this->form->get_fields() as $field) {
            if ($id === $field->get_id()) {
                return $field;
            }
        }
        throw new \InvalidArgumentException('Field with the id ' . $id . ' doesn\'t exists');
    }
    private function render_field(\DropshippingXmlFreeVendor\WPDesk\Forms\Field $field, array $parameters)
    {
        $fields_data = $this->form->get_data();
        $template = isset($parameters['template']) ? $parameters['template'] : $field->get_template_name();
        $render_parameters = ['field' => $field, 'renderer' => $this->renderer, 'name_prefix' => $this->form->get_form_id(), 'value' => isset($fields_data[$field->get_name()]) ? $fields_data[$field->get_name()] : $field->get_default_value()];
        if (isset($parameters['parent_template'])) {
            $render_parameters['template_name'] = $template;
            $template = $parameters['parent_template'];
            unset($parameters['parent_template']);
        }
        $this->renderer->output_render($template, \array_merge($parameters, $render_parameters));
    }
    private function render_field_property(\DropshippingXmlFreeVendor\WPDesk\Forms\Field $field, array $parameters, $default = '')
    {
        if (isset($parameters['template'])) {
            $this->renderer->output_render($parameters['template'], \array_merge(['field' => $field], $parameters));
        } else {
            echo \wp_kses_post($default);
        }
    }
    private function add_to_used(\DropshippingXmlFreeVendor\WPDesk\Forms\Field $field)
    {
        $this->used[$field->get_id()] = $field;
    }
    private function is_used(string $id) : bool
    {
        return isset($this->used[$id]);
    }
}
