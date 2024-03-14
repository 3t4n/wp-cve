<?php
require_once WP_BNAV_PATH . 'includes/class-wp-bnav-settings.php';
require_once WP_BNAV_PATH . 'includes/class-wp-bnav-utils.php';
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://boomdevs.com
 * @since      1.0.0
 *
 * @package    Wp_Bnav
 * @subpackage Wp_Bnav/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Bnav
 * @subpackage Wp_Bnav/public
 * @author     BOOM DEVS <contact@boomdevs.com>
 */
class Wp_Bnav_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /* Print dynamic style */
        $settings = Wp_Bnav_Settings::get_settings();
        if ($settings['enabled']):?>
            <style>

                .bnav_bottom_nav_wrapper {
                <?php if(!empty($settings['main-wrap-shadow'])):?> box-shadow: <?php echo esc_html($settings['main-wrap-shadow']['main-wrap-shadow-horizontal']); ?>px <?php echo esc_html($settings['main-wrap-shadow']['main-wrap-shadow-vertical']); ?>px <?php echo esc_html($settings['main-wrap-shadow']['main-wrap-shadow-blur']); ?>px <?php echo esc_html($settings['main-wrap-shadow']['main-wrap-shadow-spread']); ?>px <?php echo esc_html($settings['main-wrap-shadow']['main-wrap-shadow-color']); ?>;
                <?php endif; ?>
                }
                .bnav_bottom_nav_wrapper {
                    <?php if(array_key_exists('z-index', $settings)): ?>
                    z-index: <?php echo esc_html($settings['z-index']);?>!important;
                    <?php endif; ?>
                }
                .bnav_bottom_nav_wrapper {
                    -webkit-backdrop-filter: blur(<?php echo esc_html($settings['wrap-blur']);?>px);
                    backdrop-filter: blur(<?php echo esc_html($settings['wrap-blur']);?>px);
                }

                .bnav_bottom_nav_wrapper ul.bnav_main_menu {
                    justify-content: <?php echo esc_html($settings['main-nav-alignment']);?>
                }
                /* main-nav-alignment */

                /* Main nav icon and text visibility */
                /*.bnav_bottom_nav_wrapper ul.bnav_main_menu li .icon_wrapper {*/
                /*    display: none;*/
                /*}*/

                /*.bnav_bottom_nav_wrapper ul.bnav_main_menu li.current_page_item .icon_wrapper.active {*/
                /*    display: flex;*/
                /*}*/

                .bnav_bottom_nav_wrapper ul.bnav_main_menu li .text_wrapper {
                    display: flex;
                }
                
                <?php if(isset($settings['main-nav-scrollbar']) && $settings['main-nav-scrollbar'] === '1') : ?>
                .bnav_bottom_nav_wrapper ul {
                overflow-x: auto;
                justify-content: flex-start !important;
                }
                .bnav_bottom_nav_wrapper ul li {
                    flex: none !important;
                }
                <?php endif; ?>

                <?php if($settings['main-nav-item-icon-visibility'] === 'hide'): ?>
                .bnav_bottom_nav_wrapper ul.bnav_main_menu li a .icon_wrapper {
                    display: none !important;
                }

                <?php endif; ?>
                <?php if($settings['main-nav-item-icon-visibility'] === 'show'): ?>
                .bnav_bottom_nav_wrapper ul.bnav_main_menu li a .icon_wrapper.normal {
                    display: flex;
                }
                .bnav_bottom_nav_wrapper ul.bnav_main_menu li.current_page_item .icon_wrapper.normal {
                    display: none;
                }

                <?php endif; ?>
                <?php if($settings['main-nav-item-icon-visibility'] === 'active'): ?>
                .bnav_bottom_nav_wrapper ul.bnav_main_menu li a .icon_wrapper.normal {
                    display: none !important;
                }
                .bnav_bottom_nav_wrapper ul.bnav_main_menu li.current_page_item .icon_wrapper.normal {
                    display: none;
                }

                .bnav_bottom_nav_wrapper ul.bnav_main_menu li.current_page_item .icon_wrapper.active {
                    display: flex;
                }

                <?php endif?>
                <?php if($settings['main-nav-item-icon-visibility'] === 'hide-active'): ?>
                .bnav_bottom_nav_wrapper ul.bnav_main_menu li .icon_wrapper {
                    display: flex;
                }

                .bnav_bottom_nav_wrapper ul.bnav_main_menu li.current_page_item .icon_wrapper {
                    display: none !important;
                }

                <?php endif; ?>

                /* Main nav text visibility */
                <?php if($settings['main-nav-item-text-visibility'] === 'show'): ?>
                .bnav_bottom_nav_wrapper ul.bnav_main_menu li a .text_wrapper {
                    display: flex;
                }

                <?php endif;?>

                <?php if($settings['main-nav-item-text-visibility'] === 'hide'): ?>
                .bnav_bottom_nav_wrapper ul.bnav_main_menu li a .text_wrapper {
                    display: none !important;
                }

                <?php endif;?>

                <?php if($settings['main-nav-item-text-visibility'] === 'active'): ?>
                .bnav_bottom_nav_wrapper ul.bnav_main_menu li .text_wrapper {
                    display: none;
                }

                .bnav_bottom_nav_wrapper ul li.current_page_item .text_wrapper {
                    display: flex;
                }

                <?php endif;?>
                <?php if($settings['main-nav-item-text-visibility'] === 'hide-active'): ?>
                .bnav_bottom_nav_wrapper ul.bnav_main_menu li .text_wrapper {
                    display: flex;
                }

                .bnav_bottom_nav_wrapper ul.bnav_main_menu li.current_page_item .text_wrapper {
                    display: none;
                }

                /*hide-active*/
                <?php endif; ?>

                /* End icon and text visibility css */

                /* Show total number of items */
                .bnav_bottom_nav_wrapper ul.bnav_main_menu li {
                    display: none !important;
                }

                .bnav_bottom_nav_wrapper ul.bnav_main_menu li:nth-child(-n+<?php echo esc_html($settings['main-nav-grid']); ?>) {
                    display: flex !important;
                }

                /* Typography for image */
                .bnav_bottom_nav_wrapper ul.bnav_main_menu li a .bnav_menu_items .img_icon img {
                    width: <?php echo esc_html($settings['main-nav-item-icon-typography']['font-size'].$settings['main-nav-item-icon-typography']['unit']); ?>
                }

            <?php
            switch ($settings['main-nav-item-icon-position']) {
                case 'top': ?>
                .bnav_main_menu_container .bnav_menu_items .bnav_flex {
                    flex-direction: column;
                }

                <?php break;
                case 'bottom': ?>
                .bnav_bottom_nav_wrapper ul.bnav_main_menu li .bnav_menu_items .bnav_flex {
                    flex-direction: column;
                }

                .bnav_bottom_nav_wrapper ul.bnav_main_menu li .bnav_menu_items .bnav_flex .icon_wrapper {
                    order: 2;
                }

                <?php break;
                case 'left':
                    ?>
                .bnav_bottom_nav_wrapper ul.bnav_main_menu li .bnav_menu_items .bnav_flex {
                    flex-direction: row;
                }

                <?php break;
                case 'right': ?>
                .bnav_bottom_nav_wrapper ul.bnav_main_menu li .icon_wrapper {
                    order: 2;
                }

                .bnav_bottom_nav_wrapper ul.bnav_main_menu li .bnav_menu_items .bnav_flex {
                    flex-direction: row;
                }

                <?php break;
            }?>
            /* Show total number of items */
            .bnav_bottom_nav_wrapper ul.bnav_main_menu li, .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li, .bnav_bottom_nav_wrapper ul.sub-menu.bnav_child_sub_menu li {
                display: none !important;
            }
                .bnav_bottom_nav_wrapper ul.bnav_main_menu li:nth-child(-n+<?php echo esc_html($settings['main-nav-grid']); ?>){
                display: flex !important;
            }
            <?php

            if(WP_BNAV_Utils::isProActivated()) { ?>
                .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li:nth-child(-n+<?php echo esc_html($settings['sub-nav-grid']); ?>),
                .bnav_bottom_nav_wrapper ul.sub-menu.bnav_child_sub_menu li:nth-child(-n+<?php echo esc_html($settings['child-nav-grid']); ?>) {
                    display: flex !important;
                }
                .bnav_sub_menu_wrapper ul.sub-menu.depth-0 {
                    justify-content: <?php echo esc_html($settings['sub-nav-alignment']);?>
                }
                .bnav_bottom_nav_wrapper .bnav_sub_menu_wrapper ul.bnav_child_sub_menu {
                    <?php if(array_key_exists('child-nav-alignment', $settings)): ?>
                    justify-content: <?php echo esc_html($settings['child-nav-alignment']);?>
                    <?php endif; ?>
                }

                /* Sub nav icon and text visibility */
                .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li a .bnav_menu_items .icon_wrapper {
                    display: none;
                }

                /*.bnav_sub_menu_wrapper ul.sub-menu.depth-0 li a .bnav_menu_items .icon_wrapper.normal*/

                .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li.current_page_item .icon_wrapper.active {
                    display: flex;
                }
                .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li a .bnav_menu_items .text_wrapper {
                    display: flex;
                }

                <?php if($settings['sub-nav-item-text-visibility'] === 'hide'): ?>
                .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li a .bnav_menu_items .text_wrapper{
                    display: none !important;
                }

                <?php endif; ?>

                /* Sub nav text visibility */
                <?php if($settings['sub-nav-item-text-visibility'] === 'show'): ?>
                .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li a .bnav_menu_items .text_wrapper{
                    display: flex;
                }
                <?php endif;?>

                <?php if($settings['sub-nav-item-text-visibility'] === 'active'): ?>
                .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li.current_page_item a .text_wrapper{
                    display: flex;
                }
                .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li a .bnav_menu_items .text_wrapper{
                    display: none;
                }
                <?php endif;?>

                <?php if($settings['sub-nav-item-text-visibility'] === 'hide-active'): ?>
                .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li.current_page_item a .text_wrapper{
                    display: none;
                }
                .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li a .text_wrapper{
                    display: flex;
                }
                /*hide-active*/
                <?php endif; ?>

                .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li a .bnav_menu_items .icon_wrapper.active {
                    display: none;
                }
                <?php if($settings['sub-nav-item-icon-visibility'] === 'show'): ?>
                .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li a .bnav_menu_items .icon_wrapper.normal{
                    display: flex;
                }
                <?php endif;?>

                <?php if($settings['sub-nav-item-icon-visibility'] === 'hide'): ?>
                .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li a .bnav_menu_items .icon_wrapper{
                    display: none !important;
                }
                <?php endif;?>
                <?php if($settings['sub-nav-item-icon-visibility'] === 'active'): ?>
                .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li.current_page_item a .bnav_menu_items .icon_wrapper.active{
                    display: flex;
                }
                .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li.current_page_item a .bnav_menu_items .icon_wrapper.normal{
                    display: none;
                }
                <?php endif;?>

                <?php if($settings['sub-nav-item-icon-visibility'] === 'hide-active'): ?>
                .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li.current_page_item a .bnav_menu_items .icon_wrapper.normal{
                    display: flex;
                }
                .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li.current_page_item a .bnav_menu_items .icon_wrapper.active{
                    display: none;
                }
                /*hide-active*/
                <?php endif; ?>
                <?php
                switch ($settings['sub-nav-item-icon-position']) {
                    case 'top': ?>
                    .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li .bnav_menu_items .bnav_flex {
                        flex-direction: column;
                    }

                    <?php break;
                    case 'bottom': ?>
                    .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li .bnav_menu_items .bnav_flex {
                        flex-direction: column;
                    }

                    .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li .bnav_menu_items .bnav_flex .icon_wrapper {
                        order: 2;
                    }

                    <?php break;
                    case 'left': ?>
                    .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li .bnav_menu_items .bnav_flex {
                        flex-direction: row;
                    }

                    <?php break;
                    case 'right': ?>
                    .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li .bnav_menu_items .icon_wrapper {
                        order: 2;
                    }

                    .bnav_sub_menu_wrapper ul.sub-menu.depth-0 li .bnav_menu_items .bnav_flex {
                        flex-direction: row;
                    }

                    <?php break;
                } ?>
                /* Child nav icon and text visibility */

                .bnav_bottom_nav_wrapper ul.sub-menu.bnav_child_sub_menu li a .icon_wrapper {
                    display: none;
                }

                .bnav_bottom_nav_wrapper ul.sub-menu.bnav_child_sub_menu li a .icon_wrapper.active {
                    display: flex;
                }
                .bnav_bottom_nav_wrapper ul.sub-menu.bnav_child_sub_menu li .text_wrapper {
                    display: flex;
                }

                <?php if($settings['child-nav-item-text-visibility'] === 'hide'): ?>
                .bnav_bottom_nav_wrapper ul.sub-menu.bnav_child_sub_menu li a .text_wrapper{
                    display: none !important;
                }

                <?php endif; ?>

                /* Sub nav text visibility */
                <?php if($settings['child-nav-item-text-visibility'] === 'show'): ?>
                .bnav_bottom_nav_wrapper ul.sub-menu.bnav_child_sub_menu li a .text_wrapper{
                    display: flex;
                }
                <?php endif;?>

                <?php if($settings['child-nav-item-text-visibility'] === 'active'): ?>
                .bnav_bottom_nav_wrapper ul.sub-menu.bnav_child_sub_menu li.current_page_item a .text_wrapper{
                    display: flex;
                }
                .bnav_bottom_nav_wrapper ul.sub-menu.bnav_child_sub_menu li a .text_wrapper{
                    display: none;
                }
                <?php endif;?>

                <?php if($settings['child-nav-item-text-visibility'] === 'hide-active'): ?>
                .bnav_bottom_nav_wrapper ul.sub-menu.bnav_child_sub_menu li.current_page_item a .text_wrapper{
                    display: none;
                }
                .bnav_bottom_nav_wrapper ul.sub-menu.bnav_child_sub_menu li a .text_wrapper{
                    display: flex;
                }
                /*hide-active*/
                <?php endif; ?>

                .bnav_bottom_nav_wrapper ul.sub-menu.bnav_child_sub_menu li a .bnav_menu_items .icon_wrapper.active {
                    display: none;
                }
                <?php if($settings['child-nav-item-icon-visibility'] === 'show'): ?>
                .bnav_bottom_nav_wrapper ul.sub-menu.bnav_child_sub_menu li a .bnav_menu_items .icon_wrapper.normal{
                    display: flex;
                }
                <?php endif;?>

                <?php if($settings['child-nav-item-icon-visibility'] === 'hide'): ?>
                .bnav_bottom_nav_wrapper ul.sub-menu.bnav_child_sub_menu li a .bnav_menu_items .icon_wrapper{
                    display: none !important;
                }
                <?php endif;?>
                <?php if($settings['child-nav-item-icon-visibility'] === 'active'): ?>
                .bnav_bottom_nav_wrapper ul.sub-menu.bnav_child_sub_menu li a .bnav_menu_items .icon_wrapper.active{
                    display: flex;
                }
                .bnav_bottom_nav_wrapper ul.sub-menu.bnav_child_sub_menu li a .bnav_menu_items .icon_wrapper{
                    display: none;
                }
                <?php endif;?>

                <?php if($settings['child-nav-item-icon-visibility'] === 'hide-active'): ?>
                .bnav_bottom_nav_wrapper ul.sub-menu.bnav_child_sub_menu li a .bnav_menu_items .icon_wrapper.normal{
                    display: flex;
                }
                .bnav_bottom_nav_wrapper ul.sub-menu.bnav_child_sub_menu li.current_page_item a .bnav_menu_items .icon_wrapper{
                    display: none;
                }
                /*hide-active*/
                <?php endif; ?>

                <?php
                switch ($settings['child-nav-item-icon-position']) {
                    case 'top': ?>
                    .bnav_bottom_nav_wrapper .bnav_sub_menu_wrapper ul.bnav_child_sub_menu li a .bnav_menu_items .bnav_flex {
                        flex-direction: column;
                    }

                    <?php break;
                    case 'bottom': ?>
                    .bnav_bottom_nav_wrapper .bnav_sub_menu_wrapper ul.bnav_child_sub_menu li a .bnav_menu_items .bnav_flex {
                        flex-direction: column;
                    }

                    .bnav_bottom_nav_wrapper .bnav_sub_menu_wrapper ul.bnav_child_sub_menu li a .bnav_menu_items .bnav_flex .icon_wrapper {
                        order: 2;
                    }

                    <?php break;
                    case 'left': ?>
                    .bnav_bottom_nav_wrapper .bnav_sub_menu_wrapper ul.bnav_child_sub_menu li a .bnav_menu_items .bnav_flex {
                        flex-direction: row;
                    }

                    <?php break;
                    case 'right': ?>
                    .bnav_bottom_nav_wrapper .bnav_sub_menu_wrapper ul.bnav_child_sub_menu li .bnav_menu_items .icon_wrapper {
                        order: 2;
                    }

                    .bnav_bottom_nav_wrapper .bnav_sub_menu_wrapper ul.bnav_child_sub_menu li a .bnav_menu_items .bnav_flex {
                        flex-direction: row;
                    }

                    <?php break;
                } ?>
                <?php if(!empty($settings['search-box-shadow']['enable-search-box-shadow'])): ?>
                .bnav_search_input {
                <?php echo $settings['search-box-shadow-horizontal']; ?>
                    box-shadow: <?php echo esc_html($settings['search-box-shadow']['search-box-shadow-horizontal']); ?>px <?php echo esc_html($settings['search-box-shadow']['search-box-shadow-vertical']); ?>px <?php echo esc_html($settings['search-box-shadow']['search-box-shadow-blur']); ?>px <?php echo esc_html($settings['search-box-shadow']['search-box-shadow-spread']); ?>px <?php echo esc_html($settings['search-box-shadow']['search-box-shadow-color']); ?>
                }
                <?php endif?>
                <?php if(!empty($settings['search-boxfocus--shadow']['enable-search-boxfocus--shadow'])):?>
                .bnav_search_input.input_focused {
                    box-shadow: <?php echo esc_html($settings['search-boxfocus--shadow']['search-box-focus-shadow-horizontal']); ?>px <?php echo esc_html($settings['search-boxfocus--shadow']['search-box-focus-shadow-vertical']); ?>px <?php echo esc_html($settings['search-boxfocus--shadow']['search-box-focus-shadow-blur']); ?>px <?php echo esc_html($settings['search-boxfocus--shadow']['search-box-focus-shadow-spread']); ?>px <?php echo esc_html($settings['search-boxfocus--shadow']['search-box-focus-shadow-color']); ?>
                }
                <?php endif; ?>

                <?php if(!empty($settings['show-search-icon'])): ?>
                .bnav_sub_menu_search .bnav_search_input i {

                <?php if(!empty($settings['icon-search-mode']) && !empty($settings['search-icon'])): ?>
                    font-size: <?php echo esc_html($settings['search-box-typography']['font-size'].$settings['search-box-typography']['unit'])?>;
                    color: <?php echo esc_html($settings['search-box-typography']['color']) ?>;
                <?php endif; ?>
                }
                .bnav_sub_menu_search .bnav_search_input img {
                <?php if(!empty($settings['search-image']['url'])): ?>
                    width: <?php echo esc_html($settings['search-box-typography']['font-size'].$settings['search-box-typography']['unit'])?>;
                <?php endif; ?>
                }

                <?php endif; ?>
            <?php } ?>
        </style>
        <style>
            .bnav_bottom_nav_wrapper {
                display: none !important;
            }

            @media only screen and (max-width: <?php echo esc_html(intval($settings['breakpoint'])); ?>px) {
                body {
                    padding-bottom: <?php echo $settings['global_padding_bottom'] ; ?>px !important;
                }

                .bnav_bottom_nav_wrapper {
                    display: block !important;
                }
            }
        </style>
        <?php
        endif;
        wp_enqueue_style('fa5', 'https://use.fontawesome.com/releases/v5.13.0/css/all.css', array(), '5.13.0', 'all');
        wp_enqueue_style('fa5-v4-shims', 'https://use.fontawesome.com/releases/v5.13.0/css/v4-shims.css', array(), '5.13.0', 'all');
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wp-bnav-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wp_Bnav_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wp_Bnav_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wp-bnav-public.js', array('jquery'), $this->version, true);

    }

}
