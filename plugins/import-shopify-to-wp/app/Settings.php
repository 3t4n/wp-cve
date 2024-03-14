<?php

namespace S2WPImporter;

class Settings
{
    public function addSetting($id, $type = 'string', $args = [])
    {
        register_setting(
            'shopify2wp_settings',
            "shopify2wp_{$id}",
            wp_parse_args($args, [
                'type' => $type,
                'show_in_rest' => true,
            ])
        );
    }

    public function addString($id, $args = [])
    {
        $this->addSetting($id, 'string', $args);
    }

    public function addBoolean($id, $args = [])
    {
        $this->addSetting($id, 'boolean', $args);
    }

    public function addNumber($id, $args = [])
    {
        $this->addSetting($id, 'number', $args);
    }

    public function addInteger($id, $args = [])
    {
        $this->addSetting($id, 'integer', $args);
    }
}
