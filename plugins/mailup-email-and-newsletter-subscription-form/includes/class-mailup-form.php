<?php

declare(strict_types=1);

class Mailup_Form
{
    public $list_id;

    public $group;

    public $title;

    public $description;

    public $submit_text;

    public $fields;

    public $terms;

    public $confirm;

    public $placeholder;

    public $custom_css;

    public function __construct($args = null)
    {
        $this->group = __('Wordpress MailUp Plugin', 'mailup');
        $this->submit_text = __('Send', 'mailup');

        if (is_object($args)) {
            $this->list_id = $args->list_id;
            $this->group = $args->group;
            $this->title = $args->title;
            $this->description = $args->description;
            $this->submit_text = $args->submit_text;
            $this->fields = $this->set_fields($args->fields);
        }
    }

    public function set_form($array)
    {
        if (preg_match('/([%\$\&]+)/', $array['group']) || strlen($array['group']) > 45) {
            throw new \Exception('', 422);
        }

        $this->list_id = $array['list_id'];
        $this->group = $array['group'];
        $this->title = trim(stripslashes($array['title']));
        $this->description = wp_kses_post(stripslashes($array['description'])) ?? null;
        $this->submit_text = trim(stripslashes($array['submit_text']));
        $this->fields = $this->set_fields($array['fields'] ?? null);

        return $this;
    }

    protected function set_fields($fields)
    {
        $fields_obj = [];

        if (isset($fields)) {
            foreach ($fields as $field) {
                $fields_obj[] = new Mailup_FormField($field);
            }
        }

        return $fields_obj;
    }
}
