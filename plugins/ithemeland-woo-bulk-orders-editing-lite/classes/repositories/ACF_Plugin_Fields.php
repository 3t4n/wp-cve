<?php

namespace wobel\classes\repositories;

class ACF_Plugin_Fields
{
    private static $instance;
    private $grouped_fields;
    private $fields;
    private $post_type;

    public static function get_instance($post_type)
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($post_type);
        }
        return self::$instance;
    }

    private function __construct($post_type)
    {
        $this->post_type = esc_sql($post_type);
        $this->set_fields();
    }

    public function get_fields()
    {
        return $this->fields;
    }
    public function get_grouped_fields()
    {
        return $this->grouped_fields;
    }

    private function set_fields()
    {
        if (!function_exists('acf_get_field_groups')) {
            $this->fields = [];
            return false;
        }

        $grouped_fields = [];
        $fields = [];
        $acf_groups = acf_get_field_groups(array('post_type' => $this->post_type));
        if (!empty($acf_groups) && is_array($acf_groups)) {
            foreach ($acf_groups as $acf_group) {
                if (isset($acf_group['key'])) {
                    $group_fields = acf_get_fields($acf_group['key']);
                    if (!empty($group_fields) && is_array($group_fields)) {
                        $grouped_fields[$acf_group['key']] = $acf_group;
                        foreach ($group_fields as $field) {
                            $grouped_fields[$acf_group['key']]['fields'][] = $field;
                            $fields[$field['name']] = $field;
                        }
                    }
                }
            }
        }

        $this->grouped_fields = $grouped_fields;
        $this->fields = $fields;
        return true;
    }

    public function __wakeup()
    {
    }

    public function __clone()
    {
    }
}
