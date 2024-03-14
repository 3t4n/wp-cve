<?php

/**
 * WordPress settings API demo class
 *
 * @author Tareq Hasan
 */
if (!class_exists('WeDevs_Settings_API_Test')):
    class WeDevs_Settings_API_Test
    {

        private $settings_api;

        function __construct()
        {
            $this->settings_api = new WeDevs_Settings_API;

            add_action('admin_init', array($this, 'admin_init'));
            add_action('admin_menu', array($this, 'admin_menu'));
        }

        function admin_init()
        {

            //set the settings
            $this->settings_api->set_sections($this->get_settings_sections());
            $this->settings_api->set_fields($this->get_settings_fields());

            //initialize settings
            $this->settings_api->admin_init();
            register_setting('wedevs_basics', 'asb_gradient_color', array($this, 'sanitize_gradient_color'));
        }
        function sanitize_gradient_color($input)
        {
            $sanitized_input = array(
                'start' => sanitize_hex_color($input['start']),
                'mid' => sanitize_hex_color($input['mid']),
                'end' => sanitize_hex_color($input['end']),
            );

            return $sanitized_input;
        }

        function admin_menu()
        {
            add_options_page('Advanced Scrollbar Settings', 'Advanced Scrollbar Settings', 'delete_posts', 'c_s_b_setting', array($this, 'plugin_page'));
        }

        function get_settings_sections()
        {
            $sections = array(
                array(
                    'id' => 'wedevs_basics',
                    'title' => __('Scrollbar Basic Settings', 'wedevs')
                ),
                array(
                    'id' => 'wedevs_advanced',
                    'title' => __('Scrollbar Custom Style Settings', 'wedevs')
                ),
                array(
                    'id' => 'wedevs_cursor_options',
                    'title' => __('Cursor Settings', 'wedevs')
                ),

            );
            return $sections;
        }

        /**
         * Returns all the settings fields
         *
         * @return array settings fields
         */
        function get_settings_fields()
        {
            $className = asbIsPremium() ? "premium" : 'free';

            $settings_fields = array(
                'wedevs_basics' => array(
                    array(
                        'name' => 'asb_showscrollbar',
                        'label' => __('Show Scrollbar ?', 'wedevs'),
                        'desc' => __('Select show option to show scrollbar', 'wedevs'),
                        'type' => 'radio',
                        'default' => 'false',
                        'options' => array(
                            'true' => 'Show',
                            'false' => 'Hide',
                        )
                    ),
                    array(
                        'name' => 'asb_color',
                        'label' => __('Scrollbar Color', 'wedevs'),
                        'desc' => __('Change Scrollbar Color.', 'wedevs'),
                        'type' => 'color',
                        'default' => '#46b3e6',
                    ),
                    array(
                        'name' => 'asb_background',
                        'label' => __('Scrollbar Rail Background Color', 'wedevs'),
                        'desc' => __('Change the Rail Background Color.', 'wedevs'),
                        'type' => 'color',
                        'default' => '',
                    ),
                    array(
                        'name' => 'asb_mousescrollstep',
                        'label' => __('Mouse Scroll Step', 'wedevs'),
                        'desc' => __(' scrolling speed with mouse wheel, default value is 40 (pixel)', 'wedevs'),
                        'type' => 'text',
                        'default' => '40',
                    ),
                    array(
                        'name' => 'asb_autohidemode',
                        'label' => __('Auto Hide', 'wedevs'),
                        'desc' => __(' how hide the scrollbar works', 'wedevs'),
                        'type' => 'radio',
                        'default' => 'false',
                        'options' => array(
                            'true' => 'ON',
                            'false' => 'Off',
                            'coursor' => 'Cursor Only'
                        )
                    ),

                    array(
                        'name' => 'asb_scrollspeed',
                        'label' => __('Scroll Speed', 'wedevs'),
                        'desc' => __('Change the speed of scrollbar during scroll', 'wedevs'),
                        'type' => 'text',
                        'default' => '60',
                    ),
                    array(
                        'name' => 'asb_railalign',
                        'label' => __('Rail Align', 'wedevs'),
                        'desc' => __('Alignment of vertical rail', 'wedevs'),
                        'type' => 'radio',
                        'default' => 'right',
                        'options' => array(
                            'right' => 'Right',
                            'left' => 'Left'
                        )
                    ),

                    array(
                        'name' => 'asb_touchbehavior',
                        'label' => __('Enable Touch Behavior', 'wedevs'),
                        'desc' => __('enable cursor-drag scrolling like touch devices in desktop computer (default:Off) ', 'wedevs'),
                        'type' => 'checkbox',
                    ),
                    array(
                        'name' => 'asb_dynamic_height_scrollbar',
                        'label' => __('Dynamic Full Height Scrollbar', 'wedevs'),
                        'desc' => __('Select on option for showing dynamic scrollbar', 'wedevs'),
                        'type' => 'radio',
                        'default' => 'off',
                        'classname'=>$className,
                        'options' => array(
                            'on' => 'ON',
                            'off' => 'Off',
                        ),
                    ),
                    array(
                        'name' => 'asb_gradient_color',
                        'label' => __('Full Height Scrollbar Gradient Color', 'wedevs'),
                        'desc' => __('Change Scrollbar Color.', 'wedevs'),
                        'type' => 'gradient_color',
                        'classname'=>$className,
                        'default' => array(
                            'start' => '#fcb054',
                            'mid' => '#fd1d1d',
                            'end' => '#833ab4',
                        ),
                    ),
                    array(
                        'name' => 'asb_floating_scrollbar',
                        'label' => __('Show Floating Scrollbar?', 'wedevs'),
                        'desc' => __('Select on option for showing floating scrollbar', 'wedevs'),
                        'type' => 'radio',
                        'default' => 'off',
                        'classname'=>$className,
                        'options' => array(
                            'on' => 'ON',
                            'off' => 'Off',
                        ),
                    ),
                    array(
                        'name' => 'asb_floating_scrollbar_bg_color',
                        'label' => __('Floating Scrollbar BG Color', 'wedevs'),
                        'desc' => __('Change Scrollbar BG Color.', 'wedevs'),
                        'classname'=>$className,
                        'type' => 'color',
                        'default' => '#46b3e6',
                    ),
                    array(
                        'name' => 'asb_floating_scrollbar_text_color',
                        'label' => __('Floating Scrollbar Text Color', 'wedevs'),
                        'desc' => __('Change Scrollbar Text Color.', 'wedevs'),
                        'classname'=>$className,
                        'type' => 'color',
                        'default' => '#fff',
                    ),

                ),
                'wedevs_advanced' => array(
                    array(
                        'name' => 'asb_width',
                        'label' => __('Scrollbar Width', 'wedevs'),
                        'desc' => __('Change the width of the scrollbar. Enter a value in pixel', 'wedevs'),
                        'type' => 'text',
                        'default' => '10px'
                    ),
                    array(
                        'name' => 'asb_border',
                        'label' => __('Scrollbar Border CSS', 'wedevs'),
                        'desc' => __('Css definition for cursor borde', 'wedevs'),
                        'type' => 'text',
                        'default' => '1px solid #fff'
                    ),

                    array(
                        'name' => 'asb_border_radius',
                        'label' => __('Scrollbar Border Radius', 'wedevs'),
                        'desc' => __('border radius in pixel', 'wedevs'),
                        'type' => 'text',
                        'default' => '4px'
                    ),
                ),
                'wedevs_cursor_options' => array(
                    array(
                        'name' => 'asb_cursor_source',
                        'label' => __('Select Cursor Source', 'wedevs'),
                        'desc' => __('Select any option for showing cursor', 'wedevs'),
                        'type' => 'radio',
                        'classname'=>$className,
                        'default' => 'cursor',
                        'options' => array(
                            'cursor' => 'Cursor Only',
                            'predefined' => 'Pre Defined',
                            'customUrl' => 'Custom Url',
                        ),
                    ),
                    array(
                        'name' => 'asb_cursor_image',
                        'label' => __('Custom Cursor image', 'wedevs'),
                        'desc' => __('Change the default cursor', 'wedevs'),
                        'type' => 'file',
                        'classname'=>$className,
                        'default' => '',
                    ),

                    array(
                        'name' => 'asb_predefined_img',
                        'label' => __('Select Cursor Source', 'wedevs'),
                        'desc' => __('Select any option for showing cursor', 'wedevs'),
                        'type' => 'radio',
                        'classname'=>$className,
                        'options' => array(
                            CSB_DIR_URL . 'img/cursor1.png' => '<img src="' . CSB_DIR_URL . 'img/cursor1.png" />',
                            CSB_DIR_URL . 'img/cursor2.png' => '<img src="' . CSB_DIR_URL . 'img/cursor2.png" />',
                            CSB_DIR_URL . 'img/cursor3.png' => '<img src="' . CSB_DIR_URL . 'img/cursor3.png" />',
                            CSB_DIR_URL . 'img/cursor4.png' => '<img src="' . CSB_DIR_URL . 'img/cursor4.png" />',
                        ),
                        'default' => '',
                    ),


                )

            );

            return $settings_fields;
        }




        function plugin_page()
        {
            echo '<div class="wrap">';

            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();

            echo '</div>';
        }

        /**
         * Get all the pages
         *
         * @return array page names with key value pairs
         */
        function get_pages()
        {
            $pages = get_pages();
            $pages_options = array();
            if ($pages)
            {
                foreach ($pages as $page)
                {
                    $pages_options[$page->ID] = $page->post_title;
                }
            }

            return $pages_options;
        }

    }
endif;
new WeDevs_Settings_API_Test();