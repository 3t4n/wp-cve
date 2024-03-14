<?php

namespace ShopMagicVendor\WPDesk\Forms\Field;

use BadMethodCallException;
use ShopMagicVendor\WPDesk\Forms\Field;
use ShopMagicVendor\WPDesk\Forms\Sanitizer;
use ShopMagicVendor\WPDesk\Forms\Sanitizer\NoSanitize;
use ShopMagicVendor\WPDesk\Forms\Serializer;
use ShopMagicVendor\WPDesk\Forms\Validator;
use ShopMagicVendor\WPDesk\Forms\Validator\ChainValidator;
use ShopMagicVendor\WPDesk\Forms\Validator\RequiredValidator;
/**
 * Base class for fields. Is responsible for settings all required field values and provides standard implementation for
 * the field interface.
 */
abstract class BasicField implements Field
{
    use Field\Traits\HtmlAttributes;
    const DEFAULT_PRIORITY = 10;
    /** @var array{default_value: string, possible_values?: string[], sublabel?: string, priority: int, label: string, description: string, description_tip: string, data: array<string|int>} */
    protected $meta = ['priority' => self::DEFAULT_PRIORITY, 'default_value' => '', 'label' => '', 'description' => '', 'description_tip' => '', 'data' => [], 'type' => 'text'];
    public function should_override_form_template() : bool
    {
        return \false;
    }
    public function get_type() : string
    {
        return $this->meta['type'];
    }
    public function set_type(string $type) : Field
    {
        $this->meta['type'] = $type;
        return $this;
    }
    public function get_validator() : Validator
    {
        $chain = new ChainValidator();
        if ($this->is_required()) {
            $chain->attach(new RequiredValidator());
        }
        return $chain;
    }
    public function get_sanitizer() : Sanitizer
    {
        return new NoSanitize();
    }
    public function has_serializer() : bool
    {
        return \false;
    }
    public function get_serializer() : Serializer
    {
        throw new BadMethodCallException('You must define your serializer in a child class.');
    }
    public final function get_name() : string
    {
        return $this->attributes['name'] ?? '';
    }
    public final function get_label() : string
    {
        return $this->meta['label'] ?? '';
    }
    public final function set_label(string $value) : Field
    {
        $this->meta['label'] = $value;
        return $this;
    }
    public final function get_description_tip() : string
    {
        return $this->meta['description_tip'] ?? '';
    }
    public final function has_description_tip() : bool
    {
        return !empty($this->meta['description_tip']);
    }
    public final function get_description() : string
    {
        return $this->meta['description'] ?? '';
    }
    public final function has_label() : bool
    {
        return !empty($this->meta['label']);
    }
    public final function has_description() : bool
    {
        return !empty($this->meta['description']);
    }
    public final function set_description(string $value) : Field
    {
        $this->meta['description'] = $value;
        return $this;
    }
    public final function set_description_tip(string $value) : Field
    {
        $this->meta['description_tip'] = $value;
        return $this;
    }
    public final function set_placeholder(string $value) : Field
    {
        $this->attributes['placeholder'] = $value;
        return $this;
    }
    public final function has_placeholder() : bool
    {
        return !empty($this->attributes['placeholder']);
    }
    public final function get_placeholder() : string
    {
        return $this->attributes['placeholder'] ?? '';
    }
    public final function set_name(string $name) : Field
    {
        $this->attributes['name'] = $name;
        return $this;
    }
    public final function get_meta_value(string $name)
    {
        return $this->meta[$name] ?? '';
    }
    public final function get_classes() : string
    {
        return \implode(' ', $this->attributes['class'] ?? []);
    }
    public final function has_classes() : bool
    {
        return !empty($this->attributes['class']);
    }
    public final function has_data() : bool
    {
        return !empty($this->meta['data']);
    }
    public final function get_data() : array
    {
        return $this->meta['data'] ?? [];
    }
    public final function get_possible_values()
    {
        return !empty($this->meta['possible_values']) ? $this->meta['possible_values'] : [];
    }
    public final function get_id() : string
    {
        return $this->attributes['id'] ?? sanitize_title($this->get_name());
    }
    public final function is_multiple() : bool
    {
        return isset($this->attributes['multiple']);
    }
    public final function set_disabled() : Field
    {
        $this->attributes['disabled'] = 'disabled';
        return $this;
    }
    public final function is_disabled() : bool
    {
        return $this->attributes['disabled'] ?? \false;
    }
    public final function set_readonly() : Field
    {
        $this->attributes['readonly'] = 'readonly';
        return $this;
    }
    public final function is_readonly() : bool
    {
        return $this->attributes['readonly'] ?? \false;
    }
    public final function set_required() : Field
    {
        $this->attributes['required'] = 'required';
        return $this;
    }
    public final function add_class(string $class_name) : Field
    {
        $this->attributes['class'][$class_name] = $class_name;
        return $this;
    }
    public final function unset_class(string $class_name) : Field
    {
        unset($this->attributes['class'][$class_name]);
        return $this;
    }
    public final function add_data(string $data_name, string $data_value) : Field
    {
        if (empty($this->meta['data'])) {
            $this->meta['data'] = [];
        }
        $this->meta['data'][$data_name] = $data_value;
        return $this;
    }
    public final function unset_data(string $data_name) : Field
    {
        unset($this->meta['data'][$data_name]);
        return $this;
    }
    public final function is_meta_value_set(string $name) : bool
    {
        return !empty($this->meta[$name]);
    }
    public final function is_class_set(string $name) : bool
    {
        return !empty($this->attributes['class'][$name]);
    }
    public final function get_default_value() : string
    {
        return $this->meta['default_value'] ?? '';
    }
    public final function set_default_value(string $value) : Field
    {
        $this->meta['default_value'] = $value;
        return $this;
    }
    public final function is_required() : bool
    {
        return isset($this->attributes['required']);
    }
    public final function get_priority() : int
    {
        return $this->meta['priority'];
    }
    /**
     * Fields are sorted by lowest priority value first, when getting FormWithFields
     *
     * @see FormWithFields::get_fields()
     */
    public final function set_priority(int $priority) : Field
    {
        $this->meta['priority'] = $priority;
        return $this;
    }
}
