<?php

class Smart_Blocks_Blocks_Manager {

    static function get_instance($block_type) {
        if ($block_type) {
            /* Modules */
            require_once SMART_BLOCKS_PATH . 'inc/blocks/modules/' . str_replace('_', '-', $block_type) . '.php';
            $block_class = '\Smart_Blocks\\' . self::get_class_name($block_type);
            if (class_exists($block_class)) {
                $new_instance = new $block_class();
                return $new_instance;
            }
        }
        return false;
    }

    static function get_class_name($template_id) {
        return 'Smart_Blocks_' . ucwords($template_id, '_');
    }

}
