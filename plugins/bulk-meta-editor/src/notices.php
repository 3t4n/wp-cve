<?php

namespace BulkMetaEditor;

class Notices 
{
    const OPTION_NAME = 'arva_bme_notices';

    public static function get()
    {
        $option = get_option(self::OPTION_NAME);
        echo '<div class="notice '. esc_html($option['error_type']) .' '. esc_html($option['dismissible']) .'">';
        echo '<p>'. esc_html($option['message']) .'</p>';
        echo '</div>';
    }

    public static function set($message, $error_type, $dismissible = true)
    {
        update_option(self::OPTION_NAME, [
            'message' => $message,
            'error_type' => $error_type,
            'dismissible' => ($dismissible) ? 'is-dismissible' : null,
        ]);
    }
}