<?php

if (!class_exists('Icon')) {
    class Icon
    {
        public function __construct()
        {
            add_filter('elementor/icons_manager/additional_tabs', array($this, 'custom_icon'));
        }

        public function custom_icon($array)
        {
            $theme_assets_dir = get_template_directory_uri() . '/assets';

            // Enqueue the custom stylesheet
            wp_enqueue_style('custom-icon-styles', $theme_assets_dir . '/customicon/flaticon.css', array(), '1.0', 'all');

            return array(
                'custom-icon' => array(
                    'name'          => 'custom-icon',
                    'label'         => 'Theme Icon',
                    'url'           => '',
                    'enqueue'       => array(
                        'custom-icon-styles',
                    ),
                    'prefix'        => '',
                    'displayPrefix' => '',
                    'labelIcon'     => 'custom-icon',
                    'ver'           => '',
                    'fetchJson'     => $theme_assets_dir . '/customicon/icons.json',
                    'native'        => 1,
                ),
            );
        }
    }

    new Icon();
}
