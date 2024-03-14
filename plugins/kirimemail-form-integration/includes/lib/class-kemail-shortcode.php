<?php if (!defined('ABSPATH')) {
    exit;
}

class Kemail_Shortcode
{
    public static function register()
    {
        function keForm($attr)
        {
            return load_view('shortcode', [
                'url' => $attr['url'],
                'title' => $attr['title'],
                'with_name' => $attr['with_name']
            ], true);
        }

        add_shortcode('keform', 'keForm');
    }
}
