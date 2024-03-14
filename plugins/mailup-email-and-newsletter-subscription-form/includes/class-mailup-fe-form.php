<?php

declare(strict_types=1);

class Mailup_FE_Form
{
    public $list_id;

    public $title;

    public $description;

    public $submit_text;

    public $fields;

    public $terms;

    public $placeholder;

    public $confirm;

    public $custom_css;

    public $group;

    public function __construct($args = null)
    {
        if (is_object($args)) {
            $this->list_id = $args->list_id;
            $this->title = $args->title;
            $this->description = $args->description;
            $this->submit_text = $args->submit_text;
            $this->placeholder = filter_var($args->placeholder, FILTER_VALIDATE_BOOLEAN) ?? $this->placeholder;
            $this->confirm = filter_var($args->confirm, FILTER_VALIDATE_BOOLEAN) ?? $this->confirm;
            $this->custom_css = $args->custom_css;
            $this->fields = $this->set_fields($args->fields);
            $this->terms = $args->terms;
            $this->group = $args->group;
        }
    }

    public function set_form($array)
    {
        $this->list_id = $array['list_id'];
        $this->title = trim($array['title']);
        $this->fields = $this->set_fields($array['fields'] ?? null);

        return $this;
    }

    public function get_group()
    {
        return $this->group;
    }

    protected function set_fields($fields)
    {
        $fields_obj = [];

        if (isset($fields)) {
            foreach ($fields as $field) {
                $fields_obj[] = $this->get_field($field);
            }
        }

        return $fields_obj;
    }

    protected function get_field($field)
    {
        return (object) [
            'id' => $field->id,
            'name' => $field->name,
            'label' => $field->name,
            'required' => (bool) $field->required,
            'type' => $field->type ?? 'text',
        ];
    }
}
