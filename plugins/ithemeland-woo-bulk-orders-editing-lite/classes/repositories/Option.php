<?php

namespace wobel\classes\repositories;

class Option
{
    private $update_key;
    private $update_option_name;

    public function __construct()
    {
        $this->update_option_name = "_wobel_options_update_key";
        $this->update_key = "wobel-1020";
    }

    public function update_options($system_key, $except_options = [])
    {
        if ($this->has_update()) {
            $this->delete_options_with_like_name($system_key, $except_options);
            update_option($this->update_option_name, $this->update_key);
        }
    }

    public function has_update()
    {
        $option_key = get_option($this->update_option_name);
        if (empty($option_key)) {
            return true;
        }

        return (!empty($option_key) && !empty($this->update_key) && $this->update_key != $option_key);
    }

    public function get_options_with_like_name($name)
    {
        global $wpdb;
        $name = esc_sql(sanitize_text_field($name));
        return $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->options} WHERE option_name LIKE %s", "$name%"));
    }

    public function delete_options_with_like_name($name, $except = [])
    {
        $options = $this->get_options_with_like_name($name);
        if (!empty($options) && is_array($options)) {
            foreach ($options as $option) {
                if (isset($option->option_name) && !in_array($option->option_name, $except)) {
                    delete_option($option->option_name);
                }
            }
        }
    }
}
