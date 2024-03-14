<?php if (!defined('ABSPATH')) {
    exit;
}

abstract class Kemail_Metabox
{
    public static function register()
    {
        self::register_meta_box();
    }

    public static function register_meta_box()
    {
        add_action('add_meta_boxes', array(self::class, 'add_meta_box'));
    }

    public static function add_meta_box()
    {
        $screens = ['post', 'page'];
        foreach ($screens as $screen) {
            add_meta_box(
                'ke_box_id',           // Unique ID
                __('Kirim.Email Form', 'Kirimemail_Wordpress_Meta'),  // Box title
                array(self::class, 'meta_box_load'),  // Content callback, must be of type callable
                $screen,                   // Post type
                'side'
            );
        }
    }

    public static function meta_box_load()
    {
        wp_enqueue_style('kirimemail-form', get_asset('css/style.css'), false, KIRIMEMAIL_PLUGIN_VERSION, 'all');
        wp_enqueue_style('select2', get_asset('css/select2.min.css'), false, KIRIMEMAIL_PLUGIN_VERSION, 'all');
        wp_enqueue_script('select2', get_asset('js/select2.min.js'), 'jQuery');
        wp_enqueue_script('kirimemail-metabox', get_asset('js/kirimemail-metabox.js'), 'jQuery');
        $post_id = get_the_ID();
        $post_form = new Kemail_Post_Form();
        $active_data = $post_form->get($post_id);
        load_view('metabox', array(
            'post_id' => $post_id,
            'widget_selected' => !empty($active_data) && !empty($active_data->widget) ? $active_data->widget : '',
            'widget_selected_name' => !empty($active_data) && !empty($active_data->widget) ? json_decode($active_data->widget, false)->name : '',
            'bar_selected' => !empty($active_data) && !empty($active_data->bar) ? $active_data->bar : '',
            'bar_selected_name' => !empty($active_data) && !empty($active_data->bar) ? json_decode($active_data->bar, false)->name : '',
        ));
    }
}
