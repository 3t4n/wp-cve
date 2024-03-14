<?php
/**
 * Plugin Name: Hide Any Page
 * Plugin URI: https://techpilipinas.com
 * Description: Allows you to hide any page from your website's visitors and search engines such as Google. It's great for hiding pages that you don't want to appear on your website, such as thank you pages and download pages.
 * Author: Luis Reginaldo Medilo
 * Author URI: https://techpilipinas.com
 * Text Domain: hide-any-page
 * Domain Path: /languages/
 * Version: 1.0.1
 */
 
 /*
 Hide Any Page is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 2 of the License, or
 any later version.
  
 Hide Any Page is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU General Public License for more details.
  
 You should have received a copy of the GNU General Public License
 along with Hide Any Page. If not, see https://www.gnu.org/licenses/gpl-3.0.html.
 */

if (!class_exists('HAP_HIDE_ANY_PAGE')) {

    /**
     * HAP_HIDE_ANY_PAGE class.
     */
    class HAP_HIDE_ANY_PAGE
    {
        /**
         * __construct function.
         *
         * @access public
         * @return void
         */
        public function __construct()
        {
            if ( is_admin() )
            include_once 'class-hap-page-walker.php';

            add_action('admin_menu', [$this, 'hap_add_settings_page'] );
            add_action('admin_init', [$this, 'hap_register_settings'] );
            
            add_filter( 'get_pages', [$this, 'hap_get_pages'] );
            add_filter( 'pre_get_posts', [$this, 'hap_pre_get_posts'] );
            add_filter( 'wp_nav_menu_objects', [$this, 'hap_nav_menu_objects'] );
            add_action( 'wp_head', [$this, 'hap_wp_head'] );
        }
        
        /**
         * hap_get_pages function.
         *
         * @param $input
         * @return array
         */
        public function hap_get_pages( $pages ) {
            if ( is_admin() )
                return $pages;
            $options = self::get_options();
            $hap_hideanypage = $options['hap_hideanypage'];
            if (  count( $hap_hideanypage ) && $total = count( $pages ) ) {
                for ( $i = 0; $i < $total; $i++ ) {
                    if ( in_array( $pages[$i]->ID, $hap_hideanypage ) )
                        unset( $pages[$i] );
                }
            }
            return $pages;
        }
        
        /**
         * hap_nav_menu_objects function.
         *
         * @param $input
         * @return array
         */
        public function hap_nav_menu_objects( $sorted_menu_items ) {
            $options = self::get_options();
            $hap_hideanypage = $options['hap_hideanypage'];
            if (  count( $hap_hideanypage ) && $total = count( $sorted_menu_items ) ) {
                for ( $i = 1; $i <= $total; $i++ ) {
                    if ( ( 'page' == $sorted_menu_items[$i]->object ) && in_array( $sorted_menu_items[$i]->object_id, $hap_hideanypage ) ) {
                        unset( $sorted_menu_items[$i] );
                    }
                }
            }
            return $sorted_menu_items;
        }
        
        /**
         * hap_pre_get_posts function.
         *
         * @param $input
         * @return object
         */
        public function hap_pre_get_posts( $query ) {
            // Exclude pages from search results.
            if ( is_search() ) {
                $options = self::get_options();
                $hap_hideanypage = $options['hap_hideanypage'];
                if ( count( $hap_hideanypage ) )
                    $query->set( 'post__not_in', $hap_hideanypage );
            }
            return $query;
        }
        
        /**
         * hap_wp_head function.
         *
         * @param $input
         * @return void
         */
        public function hap_wp_head() {
            if ( is_page() ) {
                global $post;
                $options = self::get_options();
                $hap_hideanypage = $options['hap_hideanypage'];
                if ( count( $hap_hideanypage ) && in_array( $post->ID, $hap_hideanypage ) )
                    echo '<meta name="robots" content="noindex, noarchive, nosnippet" />'."\n";
            }
        }
        
        /**
         * get_options function.
         *
         * @param $input
         * @return array
         */
        public static function get_options() {
            $options = get_option( 'hap_hideanypage_settings' );
            if ( !is_array( $options ) )
                $options = self::set_defaults();
            return $options;
        }
        
        /**
         * set_defaults function.
         *
         * @param $input
         * @return array
         */
        public static function set_defaults() {
            $options = array( 'hap_hideanypage_settings' => array() );
            update_option( 'hap_hideanypage_settings', $options );
            return $options;
        }
        
        /**
         * hap_add_settings_page function.
         *
         * @return void
         */
        public function hap_add_settings_page()
        {
            global $hide_any_page;
            $optionpage = add_options_page(
                __('Hide Any Page Settings', 'hide-any-page'),
                __('Hide Any Page Settings', 'hide-any-page'),
                'manage_options',
                'hide-any-page',
                [$this, 'hap_render_settings_page']
            );
            add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( &$this, 'settings_link' ) );
            add_action( 'admin_enqueue_scripts', array( &$this, 'admin_plugin_scripts' ) );
        }

        /**
         * hap_render_settings_page function.
         *
         * @return void
         */
        public function hap_render_settings_page()
        {
            ?>
            <form action="options.php" method="post">
                <?php
                settings_fields('hap_hideanypage_settings');
                do_settings_sections('hap_hideanypage_plugin');
                ?>
                <input
                        type="submit"
                        name="submit"
                        class="button button-primary"
                        value="<?php esc_attr_e('Save'); ?>"
                />
            </form>
            <?php
        }

        /**
         * hap_register_settings function.
         *
         * @return void
         */
        public function hap_register_settings()
        {
            register_setting(
                'hap_hideanypage_settings',
                'hap_hideanypage_settings',
                [$this, 'hap_validate_hideanypage_settings']
            );
            add_settings_section(
                'section_one',
                __('Hide Any Page Settings', 'hide-any-page'),
                [$this, 'hap_section_one_text'],
                'hap_hideanypage_plugin'
            );
            add_settings_field(
                'hap_hideanypage',
                __('Your Website\'s Pages', 'hide-any-page'),
                [$this, 'hap_render_hap_hideanypage'],
                'hap_hideanypage_plugin',
                'section_one'
            );
        }

        /**
         * hap_section_one_text function.
         *
         * @return void
         */
        public function hap_section_one_text()
        {
            echo '<p class="hide-any-page-para">' . __('The Hide Any Page plugin allows you to hide any page on your WordPress website or blog.', 'hide-any-page') . '</p>';
            echo '<p class="hide-any-page-para">' . __('The plugin lets you select pages that you want to prevent from appearing in standard menus, lists and searches. It will add meta tags to your selected page which tell search engines such as Google not to index the page or to keep a cached copy.', 'hide-any-page') . '</p>';
            echo '<p class="hide-any-page-para">' . __('The Hide Any Page plugin is useful for download pages, thank you pages and other pages that you want to hide from your visitors.', 'hide-any-page') . '</p>';
            echo '<p class="hide-any-page-para instructions">' . __('Note: Tick the checkbox for the page that you want to hide, and then click the Save button.', 'hide-any-page') . '</p>';
        }

        /**
         * hap_render_hap_hideanypage function.
         *
         * @return void
         */
        public function hap_render_hap_hideanypage()
        {
            wp_list_pages( array( 'title_li' => '', 'walker' => new hap_walker() ) );
        }

        /**
         * hap_validate_hideanypage_settings function.
         *
         * @param $input
         * @return array
         */
        public function hap_validate_hideanypage_settings($input)
        {
            $output['hap_hideanypage'] = is_array( $input['hap_hideanypage'] ) ? $input['hap_hideanypage'] : [];
            return $output;
        }
        
        /**
         * settings_link function.
         *
         * @param $input
         * @return array
         */
        public function settings_link( $links ) {
            
            $settings_link = '<a href="'. admin_url( 'options-general.php?page=hide-any-page' ) .'">'. __('Settings', 'hide-any-page') .'</a>';
            array_unshift( $links, $settings_link );
            return $links;
            
        }
        
        /**
         * admin_plugin_scripts function.
         *
         * @param $input
         * @return void
         */
        public function admin_plugin_scripts( $hook ) {
            if ( 'settings_page_hide-any-page' != $hook ) {
                return;
            }
            wp_enqueue_style( 'hap-admin', plugin_dir_url( __FILE__ ) . 'css/hap-admin.css', [], '1.0' );
        }

        /**
         * init function.
         *
         * @access public
         * @static
         * @return void
         */
        public static function init()
        {
            $class = __CLASS__;
            new $class;
        }

    }

    add_action('plugins_loaded', array('HAP_HIDE_ANY_PAGE', 'init'));
}
