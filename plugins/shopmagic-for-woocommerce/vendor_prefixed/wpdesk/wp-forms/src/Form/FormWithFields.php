<?php

namespace ShopMagicVendor\WPDesk\Forms\Form;

use ShopMagicVendor\Psr\Container\ContainerInterface;
use ShopMagicVendor\WPDesk\Forms\ContainerForm;
use ShopMagicVendor\WPDesk\Forms\Field;
use ShopMagicVendor\WPDesk\Forms\FieldProvider;
use ShopMagicVendor\WPDesk\Forms\Form;
use ShopMagicVendor\WPDesk\Persistence\ElementNotExistsException;
use ShopMagicVendor\WPDesk\Persistence\PersistentContainer;
use ShopMagicVendor\WPDesk\View\Renderer\Renderer;
class FormWithFields implements Form, ContainerForm, FieldProvider
{
    use Field\Traits\HtmlAttributes;
    /** @var string Unique form_id. */
    protected $form_id = 'form';
    /** @var array Updated data. */
    private $updated_data;
    /** @var Field[] Form fields. */
    private $fields;
    /**
     * FormWithFields constructor.
     *
     * @param Field[] $fields
     * @param string  $form_id
     */
    public function __construct(array $fields, string $form_id = 'form')
    {
        $this->fields = $fields;
        $this->form_id = $form_id;
        $this->set_action('');
        $this->set_method('POST');
    }
    /** Set Form action attribute. */
    public function set_action(string $action) : self
    {
        $this->attributes['action'] = $action;
        return $this;
    }
    public function get_action() : string
    {
        return $this->attributes['action'];
    }
    /** Set Form method attribute ie. GET/POST. */
    public function set_method(string $method) : self
    {
        $this->attributes['method'] = $method;
        return $this;
    }
    public function get_method() : string
    {
        return $this->attributes['method'];
    }
    public function is_submitted() : bool
    {
        return null !== $this->updated_data;
    }
    /** @return void */
    public function add_field(Field $field)
    {
        $this->fields[] = $field;
    }
    public function is_active() : bool
    {
        return \true;
    }
    /**
     * Add more fields to form.
     *
     * @param Field[] $fields Field to add to form.
     *
     * @return void
     */
    public function add_fields(array $fields)
    {
        \array_map([$this, 'add_field'], $fields);
    }
    public function is_valid() : bool
    {
        foreach ($this->fields as $field) {
            $field_value = $this->updated_data[$field->get_name()] ?? $field->get_default_value();
            $field_validator = $field->get_validator();
            if (!$field_validator->is_valid($field_value)) {
                return \false;
            }
        }
        return \true;
    }
    /**
     * Add array to update data.
     */
    public function handle_request(array $request = [])
    {
        if ($this->updated_data === null) {
            $this->updated_data = [];
        }
        foreach ($this->fields as $field) {
            $data_key = $field->get_name();
            if (isset($request[$data_key])) {
                $this->updated_data[$data_key] = $field->get_sanitizer()->sanitize($request[$data_key]);
            }
        }
    }
    /**
     * Data could be saved in some place. Use this method to transmit them to form.
     *
     * @return void
     */
    public function set_data(ContainerInterface $data)
    {
        foreach ($this->fields as $field) {
            $data_key = $field->get_name();
            if ($data->has($data_key)) {
                try {
                    $this->updated_data[$data_key] = $data->get($data_key);
                } catch (ElementNotExistsException $e) {
                    $this->updated_data[$data_key] = \false;
                }
            }
        }
    }
    /** Renders only fields without form. */
    public function render_fields(Renderer $renderer) : string
    {
        $content = '';
        $fields_data = $this->get_data();
        foreach ($this->get_fields() as $field) {
            $content .= $renderer->render($field->should_override_form_template() ? $field->get_template_name() : 'form-field', ['field' => $field, 'renderer' => $renderer, 'name_prefix' => $this->get_form_id(), 'value' => $fields_data[$field->get_name()] ?? $field->get_default_value(), 'template_name' => $field->get_template_name()]);
        }
        return $content;
    }
    public function render_form(Renderer $renderer) : string
    {
        $content = $renderer->render('form-start', [
            'form' => $this,
            'method' => $this->get_method(),
            // backward compat.
            'action' => $this->get_action(),
        ]);
        $content .= $this->render_fields($renderer);
        $content .= $renderer->render('form-end');
        return $content;
    }
    public function put_data(PersistentContainer $container)
    {
        foreach ($this->get_fields() as $field) {
            $data_key = $field->get_name();
            if (empty($data_key)) {
                continue;
            }
            if (!isset($this->updated_data[$data_key])) {
                $container->set($data_key, $field->get_default_value());
            } else {
                $container->set($data_key, $this->updated_data[$data_key]);
            }
        }
    }
    public function get_data() : array
    {
        if (empty($this->get_fields())) {
            return [];
        }
        $data = $this->updated_data;
        foreach ($this->get_fields() as $field) {
            $data_key = $field->get_name();
            if (!isset($data[$data_key])) {
                $data[$data_key] = $field->get_default_value();
            }
        }
        return $data;
    }
    public function get_fields() : array
    {
        $fields = $this->fields;
        \usort($fields, static function (Field $a, Field $b) {
            return $a->get_priority() <=> $b->get_priority();
        });
        return $fields;
    }
    public function get_form_id() : string
    {
        return $this->form_id;
    }
    public function get_normalized_data() : array
    {
        return $this->get_data();
    }
}
