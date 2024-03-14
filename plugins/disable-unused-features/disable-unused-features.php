<?php
/*
Plugin Name: Disable Unused Pages
Plugin URI: https://www.andreadegiovine.it/risorse/plugin/disable-unused-features/?utm_source=wordpress_org&utm_medium=plugin_link&utm_campaign=disable_unused_features
Description: Redirect to 404: Author archives, Date archives, Attachment page, Category archives, Tag archives or Search page. Easy, safe and fast!
Version: 1.2
Author: Andrea De Giovine
Author URI: https://www.andreadegiovine.it/?utm_source=wordpress_org&utm_medium=plugin_details&utm_campaign=disable_unused_features
Text Domain: disable-unused-features
 */

if ( ! defined( 'ABSPATH' ) ) {
    die( 'OH YEAH!' );
}



if ( ! class_exists( 'Disable_Unused_Features' ) ) {
    class Disable_Unused_Features {
        public function __construct(){
            add_action( 'init', array( $this, 'init_load_textdomain' ), -1 );
            add_filter( 'plugin_action_links', array( $this, 'init_plugin_action_links' ), 10, 2);
            add_action( 'admin_menu', array( $this, 'init_options_page' ), -1 );
            add_action( 'admin_enqueue_scripts', array( $this, 'init_admin_enqueue') );
            // Return 404 & Return error on feed xml
            add_action( 'wp', array( $this, 'init_disabled_page') );
            // Disable feed link
            add_filter( 'author_feed_link', array( $this, 'filter_author_url'), PHP_INT_MAX );
            add_filter( 'category_feed_link', array( $this, 'filter_category_feed'), PHP_INT_MAX );
            add_filter( 'tag_feed_link', array( $this, 'filter_tag_feed'), PHP_INT_MAX );            
            add_filter( 'search_feed_link', array( $this, 'filter_search_feed'), PHP_INT_MAX );
            // Disable content link
            add_filter( 'author_link', array( $this, 'filter_author_url'), PHP_INT_MAX, 1 );
            add_filter( 'year_link', array( $this, 'filter_date_url'), PHP_INT_MAX, 1 );            
            add_filter( 'month_link', array( $this, 'filter_date_url'), PHP_INT_MAX, 1 );
            add_filter( 'post_link', array( $this, 'filter_media_url'), PHP_INT_MAX, 2);            
            add_filter( 'term_link', array( $this, 'filter_term_url'), PHP_INT_MAX, 3 );
        }

        public function init_load_textdomain() {
            load_plugin_textdomain( 'disable-unused-features', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
        }

        public function init_plugin_action_links($links, $file){
            if ( $file == 'disable-unused-features/disable-unused-features.php' ) {
                $links[] = sprintf( '<a href="%s" target="_blank"> %s </a>', 'https://wordpress.org/support/plugin/disable-unused-features', __( 'Support', 'disable-unused-features' ) );
                $links[] = sprintf( '<a href="%s"> %s </a>', admin_url('options-general.php?page=disable-unused-features'), __( 'Settings', 'disable-unused-features' ) );
            }
            return $links;
        }

        public function init_admin_enqueue() {
            wp_register_style( 'disable-unused-features', plugin_dir_url( __FILE__ ) . 'assets/admin-ui.css', false, '1.0.0' );
            wp_enqueue_style( 'disable-unused-features' );
        }

        public function init_disabled_page() {
            $duf_options_array = get_option('duf_settings', array() );
            $duf_settings_enable = isset( $duf_options_array['enable'] ) && !empty( $duf_options_array['enable'] ) ? $duf_options_array['enable'] : 0;
            $duf_settings_pages = isset( $duf_options_array['pages'] ) && !empty( $duf_options_array['pages'] ) ? $duf_options_array['pages'] : array();

            $block_access = false;

            if( is_author() && in_array('author' , $duf_settings_pages ) ){
                $block_access = true;
            }

            if( is_date() && in_array('date' , $duf_settings_pages ) ){
                $block_access = true;
            }

            if( is_attachment() && in_array('media' , $duf_settings_pages ) ){
                $block_access = true;
            }

            if( is_category() && in_array('category' , $duf_settings_pages ) ){
                $block_access = true;
            }

            if( is_tag() && in_array('post_tag' , $duf_settings_pages ) ){
                $block_access = true;
            }

            if( is_search() && in_array('search' , $duf_settings_pages ) ){
                $block_access = true;
            }

            if( $duf_settings_enable !== '1' ){
                $block_access = false;
            }

            if( $block_access && is_feed() ){
                wp_die( __( 'This content is disabled.', 'disable-unused-features' ) );
                exit;
            }

            if ( $block_access ) {
                global $wp_query;
                $wp_query->set_404();
                status_header(404);
            }
        }

        public function filter_author_url($link){
            $duf_options_array = get_option('duf_settings', array() );
            $duf_settings_enable = isset( $duf_options_array['enable'] ) && !empty( $duf_options_array['enable'] ) ? $duf_options_array['enable'] : 0;
            $duf_settings_pages = isset( $duf_options_array['pages'] ) && !empty( $duf_options_array['pages'] ) ? $duf_options_array['pages'] : array();

            if( $duf_settings_enable == '1' && in_array('author' , $duf_settings_pages ) ){
                return $this->return_disabled_link();
            }

            return $link;
        }

        public function filter_date_url($link){
            $duf_options_array = get_option('duf_settings', array() );
            $duf_settings_enable = isset( $duf_options_array['enable'] ) && !empty( $duf_options_array['enable'] ) ? $duf_options_array['enable'] : 0;
            $duf_settings_pages = isset( $duf_options_array['pages'] ) && !empty( $duf_options_array['pages'] ) ? $duf_options_array['pages'] : array();

            if( $duf_settings_enable == '1' && in_array('date' , $duf_settings_pages ) ){
                return $this->return_disabled_link();
            }

            return $link;
        }

        public function filter_media_url($link, $post){
            $duf_options_array = get_option('duf_settings', array() );
            $duf_settings_enable = isset( $duf_options_array['enable'] ) && !empty( $duf_options_array['enable'] ) ? $duf_options_array['enable'] : 0;
            $duf_settings_pages = isset( $duf_options_array['pages'] ) && !empty( $duf_options_array['pages'] ) ? $duf_options_array['pages'] : array();

            if( $post->post_type == 'attachment' && $duf_settings_enable == '1' && in_array('media' , $duf_settings_pages ) ){
                return $this->return_disabled_link();
            }

            return $link;
        }

        public function filter_term_url($link, $term, $tax){
            $duf_options_array = get_option('duf_settings', array() );
            $duf_settings_enable = isset( $duf_options_array['enable'] ) && !empty( $duf_options_array['enable'] ) ? $duf_options_array['enable'] : 0;
            $duf_settings_pages = isset( $duf_options_array['pages'] ) && !empty( $duf_options_array['pages'] ) ? $duf_options_array['pages'] : array();

            if( $tax == 'category' && $duf_settings_enable == '1' && in_array('category' , $duf_settings_pages ) ){
                return $this->return_disabled_link();
            }

            if( $tax == 'post_tag' && $duf_settings_enable == '1' && in_array('post_tag' , $duf_settings_pages ) ){
                return $this->return_disabled_link();
            }

            return $link;
        }

        public function filter_category_feed($link){
            $duf_options_array = get_option('duf_settings', array() );
            $duf_settings_enable = isset( $duf_options_array['enable'] ) && !empty( $duf_options_array['enable'] ) ? $duf_options_array['enable'] : 0;
            $duf_settings_pages = isset( $duf_options_array['pages'] ) && !empty( $duf_options_array['pages'] ) ? $duf_options_array['pages'] : array();

            if( $duf_settings_enable == '1' && in_array('category' , $duf_settings_pages ) ){
                return $this->return_disabled_link();
            }

            return $link;
        }

        public function filter_tag_feed($link){
            $duf_options_array = get_option('duf_settings', array() );
            $duf_settings_enable = isset( $duf_options_array['enable'] ) && !empty( $duf_options_array['enable'] ) ? $duf_options_array['enable'] : 0;
            $duf_settings_pages = isset( $duf_options_array['pages'] ) && !empty( $duf_options_array['pages'] ) ? $duf_options_array['pages'] : array();

            if( $duf_settings_enable == '1' && in_array('post_tag' , $duf_settings_pages ) ){
                return $this->return_disabled_link();
            }

            return $link;
        }

        public function filter_search_feed($link){
            $duf_options_array = get_option('duf_settings', array() );
            $duf_settings_enable = isset( $duf_options_array['enable'] ) && !empty( $duf_options_array['enable'] ) ? $duf_options_array['enable'] : 0;
            $duf_settings_pages = isset( $duf_options_array['pages'] ) && !empty( $duf_options_array['pages'] ) ? $duf_options_array['pages'] : array();

            if( $duf_settings_enable == '1' && in_array('search' , $duf_settings_pages ) ){
                return $this->return_disabled_link();
            }

            return $link;
        }

        public function return_disabled_link() {
            $link = home_url();
            return $link;              
        }

        public function init_options_page() {			            
            add_submenu_page( 'options-general.php', __( 'Disable Unused Pages settings', 'disable-unused-features' ), __( 'Disable pages', 'disable-unused-features' ), 'administrator', 'disable-unused-features', array( $this, 'render_options_page'));

            add_action( 'admin_init', array( $this, 'init_options_settings') );
        }

        public function init_options_settings() {
            register_setting( 'disable-unused-features', 'duf_settings' );
        }

        public function render_options_page() { ?>

<div class="wrap">
    <h1><?php _e( 'Disable Unused Pages settings', 'disable-unused-features' );?></h1>
    <form method="post" action="options.php">
        <?php
                                               settings_fields( 'disable-unused-features' );
                                               do_settings_sections( 'disable-unused-features' );
                                               $duf_options_array = get_option('duf_settings', array() );
                                               $duf_settings_enable = isset( $duf_options_array['enable'] ) && !empty( $duf_options_array['enable'] ) ? $duf_options_array['enable'] : 0;
                                               $duf_settings_pages = isset( $duf_options_array['pages'] ) && !empty( $duf_options_array['pages'] ) ? $duf_options_array['pages'] : array();
        ?>
        <p><?php _e( 'This page allows you to <strong>disable some WordPress default pages</strong> if they are not used in your project.', 'disable-unused-features' );?></p>
        <p><?php _e( '<u>Remember to make a backup of the website</u> (files and databases) before enabling the plugin, and if something goes wrong restore the backup and report the bug to the developer.', 'disable-unused-features' );?></p>
        <hr>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e( 'Enable plugin', 'disable-unused-features' );?></th>
                <td><label class="switch">
                    <input type="checkbox" name="duf_settings[enable]" value="1"<?php checked( $duf_settings_enable, 1 ); ?> />
                    <span class="slider"></span>
                    </label></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e( 'Disable pages', 'disable-unused-features' );?></th>
                <td><label class="switch">
                    <input type="checkbox" name="duf_settings[pages][]" value="author"<?php echo (in_array('author' , $duf_settings_pages ) ? ' checked' : ''); ?> />
                    <span class="slider"></span>
                    </label> <?php _e( 'Author archives', 'disable-unused-features' );?><br><br>
                    <label class="switch">
                        <input type="checkbox" name="duf_settings[pages][]" value="date"<?php echo (in_array('date' , $duf_settings_pages ) ? ' checked' : ''); ?> />
                        <span class="slider"></span>
                    </label> <?php _e( 'Date archives', 'disable-unused-features' );?><br><br>
                    <label class="switch">
                        <input type="checkbox" name="duf_settings[pages][]" value="media"<?php echo (in_array('media' , $duf_settings_pages ) ? ' checked' : ''); ?> />
                        <span class="slider"></span>
                    </label> <?php _e( 'Attachment pages', 'disable-unused-features' );?><br><br>
                    <label class="switch">
                        <input type="checkbox" name="duf_settings[pages][]" value="category"<?php echo (in_array('category' , $duf_settings_pages ) ? ' checked' : ''); ?> />
                        <span class="slider"></span>
                    </label> <?php _e( 'Category archives', 'disable-unused-features' );?><br><br>
                    <label class="switch">
                        <input type="checkbox" name="duf_settings[pages][]" value="post_tag"<?php echo (in_array('post_tag' , $duf_settings_pages ) ? ' checked' : ''); ?> />
                        <span class="slider"></span>
                    </label> <?php _e( 'Tag archives', 'disable-unused-features' );?><br><br>
                    <label class="switch">
                        <input type="checkbox" name="duf_settings[pages][]" value="search"<?php echo (in_array('search' , $duf_settings_pages ) ? ' checked' : ''); ?> />
                        <span class="slider"></span>
                    </label> <?php _e( 'Search page', 'disable-unused-features' );?></td>
            </tr>
        </table>
        <hr>
        <?php submit_button(__('Update Settings', 'disable-unused-features'),'primary button-hero', 'submit', false); ?>

    </form>
</div>

<?php }


    }
    new Disable_Unused_Features();
}



