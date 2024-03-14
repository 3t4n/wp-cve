<?php

/**
 * Handles plugin settings panels.
 *
 * @link       https://boomdevs.com
 * @since      1.0.0
 *
 * @package    Wp_Bnav
 * @subpackage Wp_Bnav/includes
 */

/**
 * Register plugin settings panels.
 *
 * This class defines all code necessary to manage plugin settings.
 *
 * @since      1.0.0
 * @package    Wp_Bnav
 * @subpackage Wp_Bnav/includes
 * @author     BOOM DEVS <contact@boomdevs.com>
 */
class Wp_Bnav_Settings {

    /**
     * Plugin settings prefix.
     *
     * @var string
     */
    public static $prefix = WP_BNAV_SLUG;

    /**
     * Plugin settings prefix.
     *
     * @var string
     */
    public static $menuPrefix = WP_BNAV_SLUG . '-menu';

    /**
     * Plugin settings slug.
     *
     * @var string
     */
    public static $slug = WP_BNAV_SLUG;

    /**
     * Plugin url.
     *
     * @var string
     */
    public static $plugin_file_url  = WP_BNAV_URL;

    public function __construct() {
        add_filter( 'wp_bnav_register_options_panel', array( $this, 'register_options_panel' ), 1, 2 );
    }

    /**
     * Default config for settings panel.
     *
     * @param $options_panel_func string Settings panel function name.
     * @param $options_panel_config array Settings panel configurations.
     *
     * @return array
     */
    public function register_options_panel($options_panel_func, $options_panel_config ) {
        return array(
            'func'   => $options_panel_func,
            'config' => $options_panel_config,
        );
    }

    /**
     * Generate settings with Codestar framework.
     */
    public function generate_settings() {
        // Plugin settings basic configurations
        $options_panel_func   = 'createOptions';
        $options_panel_config = [
            'menu_title' => WP_BNAV_SHORT_NAME . ' ' . __( 'Settings', 'wp-bnav' ),
            'menu_slug'  => self::$slug . '-settings',
            'framework_title' => WP_BNAV_SHORT_NAME . ' ' . __( 'Settings', 'wp-bnav' ),
            'footer_text'     => sprintf(
                __( 'Visit our plugin usage <a href="%s">documentation</a>', 'wp-bnav' ),
                esc_url( 'https://boomdevs.com/docs/wp-bottom-bar-navigation/' )
            ),
            'footer_credit'   => sprintf(
                __( 'A proud creation of <a href="%s">BOOM DEVS</a>', 'wp-bnav' ),
                esc_url( 'https://boomdevs.com/' )
            ),
            'database'        => 'option',
            'transport'       => 'refresh',
            'capability'      => 'manage_options',
            'save_defaults'   => true,
            'enqueue_webfont' => true,
            'async_webfont'   => true,
            'output_css'      => true,
        ];

        // Register settings panel type
        $options_panel_builder = apply_filters( 'wp_bnav_register_options_panel', $options_panel_func, $options_panel_config );

        CSF::{$options_panel_builder['func']}( self::$prefix, $options_panel_builder['config'] );

        $parent = '';

        if ( $options_panel_builder['func'] == 'createCustomizeOptions' ) {
            // Add to level section if in customizer mode
            CSF::createSection( self::$prefix, array(
                'id'    => self::$prefix,
                'title' => WP_BNAV_SHORT_NAME,
            ) );

            $parent = self::$prefix;
        }

        $scroll_hide_premium_settings = apply_filters( 'wp_bnav_register_menu_hide_premium_settings', [] );
        $show_bottom_menu_page_settings = apply_filters( 'wp_bnav_register_show_bottom_menu_page_settings', [] );
        $hide_bottom_menu_page_settings = apply_filters( 'wp_bnav_register_hide_bottom_menu_page_settings', [] );

        CSF::createSection( self::$prefix, array(
            'parent' => $parent,
            'title'  => __('General settings', 'wp-bnav'),
            'fields' => array(
                array(
                    'id'    => 'enabled',
                    'type'  => 'switcher',
                    'title' => __( 'Enabled', 'wp-bnav' ),
                    'default' => true,
                    'desc'  => 'Set a BNAV Bottom Menu for showing a mobile menu from here <a href="/wp-admin/nav-menus.php">Menu</a>',
                ),
                ...$show_bottom_menu_page_settings,
                ...$hide_bottom_menu_page_settings,
                ...$scroll_hide_premium_settings,
                array(
                    'id'    => 'global_padding_bottom',
                    'type'  => 'number',
                    'title' => __( 'Padding From Bottom', 'wp-bnav' ),
                    'default' => '300',
                    'unit'        => 'px',
                ),
                array(
                    'id'    => 'breakpoint',
                    'type'  => 'number',
                    'title' => __( 'Breakpoint', 'wp-bnav' ),
                    'default' => '768'
                ),
                array(
                    'id'    => 'z-index',
                    'type'  => 'number',
                    'title' => __( 'Z-Index', 'wp-bnav' ),
                    'default' => ''
                ),
                array(
                    'id'          => 'wrap-background-type',
                    'type'        => 'select',
                    'title'       => __('Background type', 'wp-bnav'),
                    'options'     => array(
                        'background'  => __('Background color', 'wp-bnav'),
                        'gradiant'  => __('Gradiant', 'wp-bnav'),
                        'background-image'  => __('Background image', 'wp-bnav'),
                    ),
                    'default'     => 'background'
                ),
                array(
                    'id' => 'main-wrap-bg',
                    'type' => 'background',
                    'title' => __( 'Background color', 'wp-bnav' ),
                    'output_mode' => 'background-color',
                    'background_image' => false,
                    'background_position' => false,
                    'background_attachment' => false,
                    'background_repeat' => false,
                    'background_size' => false,
                    'output' =>  array('.bnav_bottom_nav_wrapper'),
                    'dependency' => ['wrap-background-type', '==', 'background'],
                    'default'                         => array(
                        'background-color'              => '#1e1e1e',
                      )
                ),
                array(
                    'id' => 'main-wrap-gradiant-bg',
                    'type' => 'background',
                    'title' => __( 'Gradiant', 'wp-bnav' ),
                    'background_gradient' => true,
                    'background_image' => false,
                    'background_position' => false,
                    'background_attachment' => false,
                    'background_repeat' => false,
                    'background_size' => false,
                    'output' =>  array('.bnav_bottom_nav_wrapper'),
                    'dependency' => ['wrap-background-type', '==', 'gradiant'],
                ),
                array(
                    'id' => 'main-wrap-bg-image',
                    'type' => 'background',
                    'title' => __( 'Background image', 'wp-bnav' ),
                    'background_color' => false,
                    'background_gradient' => false,
                    'background_origin' => true,
                    'background_clip' => true,
                    'background_blend_mode' => true,
                    'output' =>  array('.bnav_bottom_nav_wrapper'),
                    'dependency' => ['wrap-background-type', '==', 'background-image'],
                ),
                array(
                    'id'    => 'wrap-blur',
                    'type'    => 'number',
                    'title' => __('Blur', 'wp-bnav'),
                    'default' => 5,
                    'unit'        => 'px',
                ),
                array(
                    'id'     => 'main-wrap-border',
                    'type'   => 'border',
                    'title'  => __( 'Border', 'wp-bnav' ),
                    'output' => '.bnav_bottom_nav_wrapper'
                ),
                array(
                    'id'    => 'main-wrap-border-radius',
                    'type'  => 'spacing',
                    'output' =>  array('.bnav_bottom_nav_wrapper'),
                    'output_mode' => 'border-radius',
                    'title' => __( 'Border radius', 'wp-bnav' ),
                ),
                array(
                    'id'     => 'main-wrap-shadow',
                    'type'   => 'fieldset',
                    'title'  => __( 'Shadow', 'wp-bnav'),
                    'fields' => array(
                        array(
                            'id'    => 'enable-main-wrap-shadow',
                            'type'  => 'switcher',
                            'title' => __( 'Enabled', 'wp-bnav' ),
                            'default' => false,
                        ),
                        array(
                            'id'      => 'main-wrap-shadow-horizontal',
                            'type'    => 'number',
                            'unit'    => 'px',
                            'default' => '0',
                            'title'   => __( 'Horizontal', 'wp-bnav' ),
                        ),
                        array(
                            'id'      => 'main-wrap-shadow-vertical',
                            'type'    => 'number',
                            'unit'    => 'px',
                            'default' => '0',
                            'title'   => __( 'Vertical', 'wp-bnav' ),
                        ),
                        array(
                            'id'      => 'main-wrap-shadow-blur',
                            'type'    => 'number',
                            'unit'    => 'px',
                            'default' => '0',
                            'title'   => __( 'Blur', 'wp-bnav' ),
                        ),
                        array(
                            'id'      => 'main-wrap-shadow-spread',
                            'type'    => 'number',
                            'unit'    => 'px',
                            'default' => '0',
                            'title'   => __( 'Spread', 'wp-bnav' ),
                        ),
                        array(
                            'id'      => 'main-wrap-shadow-color',
                            'type'    => 'color',
                            'title'   => __( 'Color', 'wp-bnav' ),
                        ),
                    ),
                ),
                array(
                    'id'    => 'main-wrap-offset',
                    'type'  => 'spacing',
                    'output_mode' => 'margin',
                    'output' =>  array('.bnav_bottom_nav_wrapper'),
                    'title' => __( 'Margin', 'wp-bnav' ),
                ),
                array(
                    'id'    => 'main-wrap-padding',
                    'type'  => 'spacing',
                    'output_mode' => 'padding',
                    'output' => array('.bnav_bottom_nav_wrapper'),
                    'title' => __( 'Padding', 'wp-bnav' ),
                    'default'  => array(
                        'top'    => '0',
                        'right'  => '0',
                        'bottom' => '0',
                        'left'   => '0',
                        'unit'   => 'px',
                    ),
                ),
            )
        ) );


        // Cart menu settings
        $cart_menu_settings = apply_filters( 'wp_bnav_register_cart_menu_settings', [] );

        if ( ! $cart_menu_settings ) {
            $cart_menu_settings = array(
                array(
                    'type'    => 'subheading',
                    'content' => $this->get_premium_alert_message(),
                ),
            );
        }

        // Wishlist menu settings
        $wishlist_menu_settings = apply_filters( 'wp_bnav_register_wishlist_menu_settings', [] );

        if ( ! $wishlist_menu_settings ) {
            $wishlist_menu_settings = array(
                array(
                    'type'    => 'subheading',
                    'content' => $this->get_premium_alert_message(),
                ),
            );
        }



        // Sub menu settings
        $sub_menu_settings = apply_filters( 'wp_bnav_register_sub_menu_settings', [] );

        if ( ! $sub_menu_settings ) {
            $sub_menu_settings = array(
                array(
                    'type'    => 'subheading',
                    'content' => $this->get_premium_alert_message(),
                ),
            );
        }

        // Child menu settings
        $child_menu_settings = apply_filters( 'wp_bnav_register_child_menu_settings', [] );

        if ( ! $child_menu_settings ) {
            $child_menu_settings = array(
                array(
                    'type'    => 'subheading',
                    'content' => $this->get_premium_alert_message(),
                ),
            );
        }

        CSF::createSection( self::$prefix, array(
            'parent' => $parent,
            'title'  => __('Menu styles', 'wp-bnav'),
            'fields' => array(
                // Main menu
                array(
                    'type'    => 'heading',
                    'content' => __( 'Main menu', 'wp-bnav' ),
                ),
                array(
                    'id'    => 'main-nav-grid',
                    'type'  => 'number',
                    'title' => __('Number of grids', 'wp-bnav' ),
                    'default' => 6
                ),
                array(
                    'id'          => 'main-nav-alignment',
                    'type'        => 'select',
                    'title'       => __( 'Alignment', 'wp-bnav' ),
                    'options'     => array(
                      'center'  => 'Center',
                      'flex-start'  => 'Left',
                      'end'  => 'Right',
                    ),
                    'default'     => 'flex-start'
                ),
                array(
                    'id'       => 'main-nav-scrollbar',
                    'type'     => 'switcher',
                    'title'    => 'Menu item scrollbar',
                    'text_on'  => 'Yes',
                    'text_off' => 'No',
                  ),
                array(
                    'id'          => 'main-menu-background-type',
                    'type'        => 'select',
                    'title'       => __('Background type', 'wp-bnav'),
                    'options'     => array(
                        'background'  => __('Background color', 'wp-bnav'),
                        'gradiant'  => __('Gradiant', 'wp-bnav'),
                        'background-image'  => __('Background image', 'wp-bnav'),
                    ),
                    'default'     => 'background'
                ),
                array(
                    'id' => 'main-menu-nav-bg',
                    'type' => 'background',
                    'title' => __( 'Background color', 'wp-bnav' ),
                    'output_mode' => 'background-color',
                    'background_image' => false,
                    'background_position' => false,
                    'background_attachment' => false,
                    'background_repeat' => false,
                    'background_size' => false,
                    'output' =>  array('.bnav_main_menu_container ul.bnav_main_menu'),
                    'dependency' => ['main-menu-background-type', '==', 'background'],
                    'default'                         => array(
                        'background-color'              => 'rgba(45,45,59,0.95)',
                      )
                ),
                array(
                    'id' => 'main-nav-gradiant-bg',
                    'type' => 'background',
                    'title' => __( 'Gradiant', 'wp-bnav' ),
                    'background_gradient' => true,
                    'background_image' => false,
                    'background_position' => false,
                    'background_attachment' => false,
                    'background_repeat' => false,
                    'background_size' => false,
                    'output' =>  array('.bnav_main_menu_container ul.bnav_main_menu'),
                    'dependency' => ['main-menu-background-type', '==', 'gradiant'],
                ),
                array(
                    'id' => 'main-nav-bg-image',
                    'type' => 'background',
                    'title' => __( 'Background image', 'wp-bnav' ),
                    'background_color' => false,
                    'background_gradient' => false,
                    'background_origin' => true,
                    'background_clip' => true,
                    'background_blend_mode' => true,
                    'output' =>  array('.bnav_main_menu_container ul.bnav_main_menu'),
                    'dependency' => ['main-menu-background-type', '==', 'background-image'],
                ),
                array(
                    'id'    => 'main-nav-blur',
                    'type'    => 'number',
                    'title' => __('Blur', 'wp-bnav'),
                    'default' => 7.5,
                    'unit'        => 'px',
                ),
                array(
                    'id'    => 'main-menu-padding',
                    'type'  => 'spacing',
                    'output'      => '.bnav_main_menu_container ul.bnav_main_menu',
                    'output_mode' => 'padding',
                    'title' => __( 'Padding', 'wp-bnav' ),
                    'default'  => array(
                        'top'    => '15',
                        'right'  => '5',
                        'bottom' => '35',
                        'left'   => '5',
                        'unit'   => 'px',
                      ),
                ),
                array(
                    'id'    => 'main-menu-margin',
                    'type'  => 'spacing',
                    'output'      => '.bnav_main_menu_container ul.bnav_main_menu',
                    'output_mode' => 'margin',
                    'title' => __( 'Margin', 'wp-bnav' ),
                    'default'  => array(
                        'top'    => '0',
                        'right'  => '0',
                        'bottom' => '0',
                        'left'   => '0',
                        'unit'   => 'px',
                      ),
                ),
                array(
                    'id'    => 'main-menu-border',
                    'type'  => 'border',
                    'output'      => array('.bnav_bottom_nav_wrapper .bnav_main_menu'),
                    'title' => __( 'Border', 'wp-bnav' ),
                ),
                array(
                    'id'    => 'main-menu-border-radius',
                    'type'  => 'spacing',
                    'output_mode' => 'border-radius',
                    'output'      => array('.bnav_bottom_nav_wrapper .bnav_main_menu'),
                    'title' => __( 'Border radius', 'wp-bnav' ),
                ),
                array(
                    'id'    => 'main-nav-item-padding',
                    'type'  => 'spacing',
                    'output'      => '.bnav_bottom_nav_wrapper ul li > a .bnav_menu_items',
                    'output_mode' => 'padding',
                    'title' => __( 'Item padding', 'wp-bnav' ),
                    'default'  => array(
                        'right'    => '0',
                        'left' => '0',
                        'unit'   => 'px',
                      ),
                ),
                array(
                    'id'    => 'main-nav-item-margin',
                    'type'  => 'spacing',
                    'output'      => '.bnav_bottom_nav_wrapper ul li > a .bnav_menu_items',
                    'output_mode' => 'margin',
                    'title' => __( 'Item offset', 'wp-bnav' ),
                ),
                array(
                    'id'    => 'main-nav-item-border',
                    'type'  => 'border',
                    'output'      => '.bnav_bottom_nav_wrapper ul li > a .bnav_menu_items',
                    'title' => __( 'Item border', 'wp-bnav' ),
                ),
                array(
                    'id'    => 'main-nav-active-item-border',
                    'type'  => 'border',
                    'output'      => array('.bnav_bottom_nav_wrapper ul li > a .bnav_menu_items:hover'),
                    'title' => __( 'Item active border', 'wp-bnav' ),
                ),
                array(
                    'id'    => 'main-nav-item-border-radius',
                    'type'  => 'spacing',
                    'output'      => '.bnav_bottom_nav_wrapper ul li > a .bnav_menu_items',
                    'output_mode' => 'border-radius',
                    'title' => __( 'Item border radius', 'wp-bnav' ),
                ),
                array(
                    'id' => 'main-nav-item-bg',
                    'type' => 'background',
                    'title' => __( 'Item background', 'wp-bnav' ),
                    'output'      => '.bnav_bottom_nav_wrapper ul li > a .bnav_menu_items',
                    'output_mode' => 'background-color',
                    'background_image' => false,
                    'background_position' => false,
                    'background_attachment' => false,
                    'background_repeat' => false,
                    'background_size' => false,
                    'background_origin' => false,
                    'background_clip' => false,
                    'background_blend_mode' => false,
                    'background_image_preview' => false,
                ),
                array(
                    'id' => 'main-nav-active-item-bg',
                    'type' => 'background',
                    'title' => __( 'Item active background', 'wp-bnav' ),
                    'output'      => array('.bnav_bottom_nav_wrapper ul li > a .bnav_menu_items:hover', '.bnav_bottom_nav_wrapper ul li.current_page_item a .bnav_menu_items', '.bnav_bottom_nav_wrapper ul li.current_page_parent a .bnav_menu_items'),
                    'output_mode' => 'background-color',
                    'background_image' => false,
                    'background_position' => false,
                    'background_attachment' => false,
                    'background_repeat' => false,
                    'background_size' => false,
                    'background_origin' => false,
                    'background_clip' => false,
                    'background_blend_mode' => false,
                    'background_image_preview' => false
                ),
                array(
                    'id'          => 'main-nav-item-icon-visibility',
                    'type'        => 'select',
                    'title'       => __( 'Icon visibility', 'wp-bnav' ),
                    'options'     => array(
                        'show'  => __( 'Show', 'wp-bnav' ),
                        'hide'  => __( 'Hide', 'wp-bnav' ),
                        'active'  => __( 'Show when active', 'wp-bnav' ),
                        'hide-active'  => __( 'Hide when active', 'wp-bnav' ),
                    ),
                    'default'     => 'show',
                ),
                array(
                    'id'          => 'main-nav-item-icon-position',
                    'type'        => 'select',
                    'title'       => __( 'Icon position', 'wp-bnav' ),
                    'options'     => array(
                        'top'  => __( 'Top', 'wp-bnav' ),
                        'right'  => __( 'Right', 'wp-bnav' ),
                        'bottom'  => __( 'Bottom', 'wp-bnav' ),
                        'left'  => __( 'Left', 'wp-bnav' ),
                    ),
                    'default'     => 'top',
                ),
                array(
                    'id'    => 'main-nav-item-icon-offset',
                    'type'  => 'spacing',
                    'output'      => '.bnav_bottom_nav_wrapper ul li a .icon_wrapper',
                    'output_mode' => 'margin',
                    'title' => __( 'Icon offset', 'wp-bnav' ),
                    'default'  => array(
                        'top'    => '0',
                        'right'  => '0',
                        'bottom' => '10',
                        'left'   => '0',
                        'unit'   => 'px',
                      ),
                ),
                array(
                    'id'    => 'main-nav-item-icon-typography',
                    'type'  => 'typography',
                    'title' => __( 'Icon typography', 'wp-bnav' ),
                    'output'      => '.bnav_bottom_nav_wrapper ul li a .icon_wrapper i',
                    'font_family' => false,
                    'font_style' => false,
                    'line_height' => false,
                    'letter_spacing' => false,
                    'text_align' => false,
                    'text_transform' => false,
                    'default' => array(
                        'color'       => '#818799',
                        'font-size'   => '24',
                        'unit'        => 'px',
                      ),
                ),
                array(
                    'id'    => 'main-nav-active-item-icon-typography',
                    'type'  => 'typography',
                    'title' => __( 'Active icon typography', 'wp-bnav' ),
                    'output'      => array('.bnav_bottom_nav_wrapper ul li > a .bnav_menu_items:hover .icon_wrapper i', '.bnav_bottom_nav_wrapper ul li.current_page_item a .icon_wrapper i', '.bnav_bottom_nav_wrapper ul li.current_page_parent a .icon_wrapper i'),
                    'font_family' => false,
                    'font_style' => false,
                    'line_height' => false,
                    'letter_spacing' => false,
                    'text_align' => false,
                    'text_transform' => false,
                    'default' => array(
                        'color'       => '#d5ee9b',
                        'font-size'   => '24',
                        'unit'        => 'px',
                      ),
                ),
                array(
                    'id'          => 'main-nav-item-text-visibility',
                    'type'        => 'select',
                    'title'       => __( 'Text visibility', 'wp-bnav' ),
                    'options'     => array(
                        'show'  => __( 'Show', 'wp-bnav' ),
                        'hide'  => __( 'Hide', 'wp-bnav' ),
                        'active'  => __( 'Show when active', 'wp-bnav' ),
                        'hide-active'  => __( 'Hide when active', 'wp-bnav' ),
                    ),
                    'default'     => 'show',
                ),
                array(
                    'id'    => 'main-nav-item-typography',
                    'type'  => 'typography',
                    'output'      => array('.bnav_bottom_nav_wrapper ul li a .text_wrapper', '.bnav_main_menu_container .bnav_main_menu .bnav_menu_items .cart_total'),
                    'title' => __( 'Text typography', 'wp-bnav' ),
                    'default' => array(
                        'color'          => '#818797',
                        'font-size'      => '14',
                        'line-height'    => '17',
                        'letter-spacing' => '.48',
                        'unit'           => 'px',
                      ),
                ),

                array(
                    'id'    => 'main-nav-active-item-typography',
                    'type'  => 'typography',
                    'output'      => array('.bnav_bottom_nav_wrapper ul li a .bnav_menu_items:hover .text_wrapper', '.bnav_bottom_nav_wrapper ul li.current_page_item a .text_wrapper', '.bnav_bottom_nav_wrapper ul li.current_page_parent a .text_wrapper', '.bnav_bottom_nav_wrapper ul li.current_page_item a .bnav_menu_items .cart_total'),
                    'title' => __( 'Active text typography', 'wp-bnav' ),
                    'default' => array(
                        'color'          => '#FFFFFF',
                        'font-size'      => '14',
                        'line-height'    => '17',
                        'letter-spacing' => '.48',
                        'unit'           => 'px',
                      ),
                ),


                // Cart Menu
                array(
                    'type'    => 'heading',
                    'content' => __( 'Cart menu counter', 'wp-bnav' ),
                ),
                ...$cart_menu_settings,

                // Wishlist Menu
                array(
                    'type'    => 'heading',
                    'content' => __( 'Wishlist menu counter', 'wp-bnav' ),
                ),
                ...$wishlist_menu_settings,


                // Sub menu
                array(
                    'type'    => 'heading',
                    'content' => __( 'Sub menu', 'wp-bnav' ),
                ),
                ...$sub_menu_settings,

                // Child menu
                array(
                    'type'    => 'heading',
                    'content' => __( 'Child menu', 'wp-bnav' ),
                ),
                ...$child_menu_settings,
            )
        ) );

        // Search box
        $search_box_settings = apply_filters( 'wp_bnav_register_search_box_settings', [] );

        if ( ! $search_box_settings ) {
            $search_box_settings = array(
                array(
                    'type'    => 'subheading',
                    'content' => $this->get_premium_alert_message(),
                ),
            );
        }
        CSF::createSection( self::$prefix, array(
            'parent' => $parent,
            'title'  => __('Search box', 'wp-bnav'),
            'fields' => array(
                ...$search_box_settings,
            )
        ) );

        // List of available skins.
        $default_skins = array(
            'default_skin' => WP_BNAV_URL . 'admin/img/default_layout_preview_dark.png',
            'skin_one' => WP_BNAV_URL . 'admin/img/layout_preview_light_skin_one.png',
            'skin_two' => WP_BNAV_URL . 'admin/img/preview_layout_skin_two.png',
            'skin_three' => WP_BNAV_URL . 'admin/img/preview_layout_skin_three.png',
            'skin_four' => WP_BNAV_URL . 'admin/img/preview_layout_skin_four.png',
            'skin_five' => WP_BNAV_URL . 'admin/img/preview_layout_skin_five.png',
            'skin_six' => WP_BNAV_URL . 'admin/img/preview_layout_skin_six.png',
            'skin_seven' => WP_BNAV_URL . 'admin/img/preview_layout_skin_seven.png',
            'skin_eight' => WP_BNAV_URL . 'admin/img/preview_layout_skin_eight.png',
            'skin_nine' => WP_BNAV_URL . 'admin/img/preview_layout_skin_nine.png',
        );

        $skins = apply_filters( 'wp_bnav_get_skins', $default_skins );

        if ( ! $skins ) {
            $skins = $default_skins;
        }

        CSF::createSection( self::$prefix, array(
            'parent' => $parent,
            'title'  => __( 'Pre-made themes', 'wp-bnav' ),
            'fields' => array(
                array(
                    'id'       => 'premade_skins',
                    'type'     => 'fieldset',
                    'title'    => __( 'Click to import a skin', 'wp-bnav' ),
                    'subtitle' => sprintf( '<strong>%s</strong>: %s', __( 'Warning', 'wp-bnav' ), __( 'This is an irreversible action and will replace all your settings to match the selected skin', 'wp-bnav' ) ),
                    'class'    => 'premade_skins',
                    'fields'   => array(
                        array(
                            'id'      => 'premade_skin',
                            'type'    => 'image_select',
                            'class'   => 'image_selects',
                            'options' => $skins,
                            'default' => 'default_skin',
                        ),
                    ),
                ),
            ),
        ) );

        if( ! WP_BNAV_Utils::isProActivated() ) {
            // Free Vs Pro
            CSF::createSection( self::$prefix, array(
                'parent' => $parent,
                'title'  => __( 'Free Vs Pro', 'wp-bnav' ),
                'fields' => array(
                    array(
                        'type'    => 'subheading',
                        'content' => $this->Free_VS_Pro(),
                    ),
                ),
            ) );
        }
    }

    /**
     * Generate settings for menu with Codestar framework.
     */
    public function register_menu_settings() {
        CSF::createNavMenuOptions( self::$menuPrefix, [] );

        // Get premium settings
        $premium_settings = apply_filters( 'wp_bnav_register_menu_premium_settings', [] );

        CSF::createSection( self::$menuPrefix, array(
            'title'  => __( 'WP BNav', 'wp-bnav' ),
            'fields' => array(
                array(
                    'id'    => 'hide-text',
                    'type'  => 'switcher',
                    'title' => __( 'Hide text', 'wp-bnav' ),
                    'default' => false,
                ),
                array(
                    'id'    => 'show-icon',
                    'type'  => 'switcher',
                    'title' => __( 'Show icon', 'wp-bnav' ),
                    'default' => true,
                ),
                array(
                    'id'    => 'icon-mode',
                    'type'  => 'switcher',
                    'title' => __( 'Icon mode', 'wp-bnav' ),
                    'text_on' => __( 'Icon', 'wp-bnav'),
                    'text_off' => __( 'Image', 'wp-bnav'),
                    'text_width' => 80,
                    'default' => true,
                    'dependency' => ['show-icon', '==', 'true'],
                ),
                array(
                    'id'          => 'icon-position',
                    'type'        => 'select',
                    'title'       => __( 'Icon position', 'wp-bnav' ),
                    'options'     => array(
                        'none' => __('From global', 'wp-bnav'),
                        'top'  => __( 'Top', 'wp-bnav' ),
                        'right'  => __( 'Right', 'wp-bnav' ),
                        'bottom'  => __( 'Bottom', 'wp-bnav' ),
                        'left'  => __( 'Left', 'wp-bnav' ),
                    ),
                    'default'     => 'none',
                    'dependency' => ['show-icon', '==', 'true'],
                ),
                array(
                    'id'    => 'icon',
                    'type'  => 'icon',
                    'title' => __( 'Icon', 'wp-bnav' ),
                    'default' => 'fa fa-home',
                    'dependency' => [
                        ['show-icon', '==', 'true'],
                        ['icon-mode', '==', 'true'],
                    ]
                ),
                array(
                    'id'      => 'image',
                    'type'    => 'media',
                    'title'   => __( 'Image', 'wp-bnav' ),
                    'library' => 'image',
                    'dependency' => [
                        ['show-icon', '==', 'true'],
                        ['icon-mode', '==', 'false'],
                    ],
                ),
                array(
                    'id'    => 'active-icon',
                    'type'  => 'icon',
                    'title' => __( 'Active icon', 'wp-bnav' ),
                    'default' => 'fa fa-home',
                    'dependency' => [
                        ['show-icon', '==', 'true'],
                        ['icon-mode', '==', 'true'],
                    ]
                ),
                array(
                    'id'      => 'active-image',
                    'type'    => 'media',
                    'title'   => __( 'Active image', 'wp-bnav' ),
                    'library' => 'image',
                    'dependency' => [
                        ['show-icon', '==', 'true'],
                        ['icon-mode', '==', 'false'],
                    ],
                ),
                ...$premium_settings,
            )
        ) );
    }

    /**
     * Return plugin all settings.
     *
     * @return string|array Settings values.
     */
    public static function get_settings() {
        return get_option( Wp_Bnav_Settings::$prefix );
    }

    /**
     * Premium version alert.
     *
     * @return string
     */
    protected function get_premium_alert_message() {
        return sprintf( '%s <a href="https://boomdevs.com/products/wp-mobile-bottom-menu/">%s</a>',
            __( 'This is a premium feature of WP Mobile Bottom Menu and requires the pro version of this plugin to unlock.', 'wp-bnav' ),
            __( 'Download Pro Now', 'wp-bnav' )
        );
    }

    /**
     * Free vs Pro.
     *
     * @return string
     */
    protected function Free_VS_Pro(){
        ob_start();
        ?>
        <div class="wp_bnav_main_wrapper">
            <div class="wp_bnav_header_wrapper">
                <div class="container">
                    <div class="title">
                        <h1>Unlock the pro features now</h1>
                    </div>
                    <div class="text">
                        <p>Confirm a well-crafted WP Mobile Bottom Menu that engages readers and search engines.</p>
                    </div>
                    <div class="header_btn">
                        <div class="left_btn">
                            <a class="button button-primary" target="_blank" href="https://wp-mobile-bottom-menu.boomdevs.com/bottom-menu/">View Demo</a>
                        </div>
                        <div class="right_btn">
                            <a class="button button-secondary" target="_blank" href="https://boomdevs.com/products/wp-mobile-bottom-menu/">Get Pro Now</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wp_bnav_money_back_guarantee_wrapper">
                <div class="container">
                    <div class="money_back_guarantee_logo">
                       <img src="<?php echo self::$plugin_file_url . 'admin/img/money-back-logo.png' ?>" alt="money-back-logo">
                    </div>
                    <div class="money_back_guarantee_text">
                        <h3>14 Days Money Back Guarantee!</h3>
                        <p>Your satisfaction is guaranteed under our 100% No-Risk Double Guarantee. We will<br> happily <a target="_blank" href="https://boomdevs.com/refund-policy/">refund</a> 100% of your money if you don’t think our plugin works well within 14 days.</p>
                    </div>
                    <div class="money_back_guarantee_btn">
                        <a class="button button-primary" target="_blank" href="https://boomdevs.com/product-category/wordpress/wordpress-plugins/">View All Products</a>
                    </div>
                </div>
            </div>
            <div class="wp_bnav_pricing_wrapper">
                <div class="container">
                    <div class="wp_bnav_pricing_content">
                        <div class="wp_bnav_pricing_content_header">
                            <span>Get a quote</span>
                            <h2>Compare Plan</h2>
                            <p>It’s all here!  Check out the comparison of the pricing and features<br> before moving on to the pro version.</p>
                        </div>
                        <div class="wp_bnav_pricing_content_table">
                            <table class="pricing-table">
                                <thead>
                                <tr>
                                    <th>Feature</th>
                                    <th>Free</th>
                                    <th>Premium</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>More Eye-Catching & Stunning Premade Templates.</td>
                                    <td class="cross">X</td>
                                    <td><span class="tick">✓</span></td>
                                </tr>
                                <tr>
                                    <td>Sidebar TOC On Scroll.</td>
                                    <td class="cross">X</td>
                                    <td><span class="tick">✓</span></td>
                                </tr>
                                <tr>
                                    <td>Sticky Special TOC On Scroll.</td>
                                    <td class="cross">X</td>
                                    <td><span class="tick">✓</span></td>
                                </tr>
                                <tr>
                                    <td>Floating TOC with Navigation.</td>
                                    <td class="cross">X</td>
                                    <td><span class="tick">✓</span></td>
                                </tr>
                                <tr>
                                    <td>Sub-Heading Toggle Options.</td>
                                    <td class="cross">X</td>
                                    <td><span class="tick">✓</span></td>
                                </tr>
                                <tr>
                                    <td>Progress bar with TOC.</td>
                                    <td class="cross">X</td>
                                    <td><span class="tick">✓</span></td>
                                </tr>
                                <tr>
                                    <td>Active Heading Navigation.</td>
                                    <td class="cross">X</td>
                                    <td><span class="tick">✓</span></td>
                                </tr>
                                <tr>
                                    <td>Collapse/Expand Options For Subheadings.</td>
                                    <td class="cross">X</td>
                                    <td><span class="tick">✓</span></td>
                                </tr>
                                <tr>
                                    <td>Customization Panel With Live Preview.</td>
                                    <td class="cross">X</td>
                                    <td><span class="tick">✓</span></td>
                                </tr>
                                <tr>
                                    <td>A lot more </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wp_bnav_testimonial_wrapper">
                <div class="container">
                    <div class="wp_bnav_testimonial_content">
                        <div class="wp_bnav_testimonial_content_header">
                            <span>Testimonials</span>
                            <h2>What People Say</h2>
                            <p>We're dedicated to providing the best possible experience for our customers.<br> Here's what a few of them have to say about us</p>
                        </div>
                        <div class="testimonial-cards">
                            <div class="card">
                                <div class="logo">
                                    <img src="<?php echo self::$plugin_file_url . 'admin/img/Alex.png' ?>" alt="mark-hugh">
                                </div>
                                <div class="content">
                                    <p>"It's easy to use, and the fact that it's compatible with all types of posts and pages is amazing. Highly recommended."</p>
                                </div>
                                <div class="details">
                                    <div class="name">
                                        <p>Alex</p>
                                        <span>Web Developer</span>
                                    </div>
                                    <div class="rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="logo">
                                    <img src="<?php echo self::$plugin_file_url . 'admin/img/Jessica.png' ?>" alt="cody-fisher">
                                </div>
                                <div class="content">
                                    <p>"The Pro features are amazing. It makes it easy for readers to find what they're looking for on my site. Thank you, TOP WP Mobile Bottom Menu."</p>
                                </div>
                                <div class="details">
                                    <div class="name">
                                        <p>Jessica</p>
                                        <span> Blogger</span>
                                    </div>
                                    <div class="rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="logo">
                                    <img src="<?php echo self::$plugin_file_url . 'admin/img/John.png' ?>" alt="john-doe">
                                </div>
                                <div class="content">
                                    <p>"TOP WP Mobile Bottom Menu is a game-changer for SEO. It's easy to use and customize, and it's SEO-friendly. Highly recommended."</p>
                                </div>
                                <div class="details">
                                    <div class="name">
                                        <p>John</p>
                                        <span>Marketer</span>
                                    </div>
                                    <div class="rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wp_bnav_coupon_wrapper">
                <div class="container">
                    <div class="wp_bnav_coupon_content">
                        <div class="wp_bnav_coupon_content_header">
                            <h2>What People Say About us</h2>
                            <p>We're dedicated to providing the best possible experience for our customers.<br> Here's what a few of them have to say about us</p>
                            <a class="button button-primary" href="#">View Demo</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}
