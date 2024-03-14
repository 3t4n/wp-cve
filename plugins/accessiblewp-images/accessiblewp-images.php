<?php
/**
 * Plugin Name: AccessibleWP - ALT Detector
 * Plugin URI: https://wordpress.org/plugins/accessiblewp-images/
 * Description: Allows you to display more accessible images on your site according to the WCAG 2.0 Accessibility guidelines.
 * Author: Codenroll
 * Author URI: https://www.codenroll.co.il/
 * Version: 1.0.2
 * Text Domain: acwp
 * License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
if( ! defined( 'ABSPATH' ) ) {
    return;
}

/**
 * Class ACWP_ImagesAdminPanel
 *
 * Register our admin pages and our settings for the toolbar
 *
 * @since 4.0.0
 */
class ACWP_ImagesAdminPanel {

    /**
     * constructor
     */
    public function __construct() {

        // Register admin pages
        add_action( 'admin_menu', array(&$this, 'register_pages') );

        // Register settings
        add_action( 'admin_init', array(&$this, 'register_settings') );
        add_action( 'rest_api_init', array(&$this, 'register_settings') );
    }

    /**
     * Register pages
     */
    public function register_pages() {

        // Check if we already got the primary page of AccessibleWP if not we will add it
        if ( empty ($GLOBALS['admin_page_hooks']['accessible-wp'] ) ) {
            add_menu_page('AccessibleWP', 'AccessibleWP', 'read', 'accessible-wp', array($this, 'main_page_callback'), plugins_url( 'accessiblewp-images/assets/svg/accessible.svg' ), '2.1');
        }

        // Add our sub page for the Toolbar
        add_submenu_page('accessible-wp', 'AccessibleWP Images', __('Images', 'acwp'), 'manage_options', 'acwp-images', array(&$this, 'submenu_page_callback'));
    }

    /**
     * Register settings
     */
    public function register_settings(){
        register_setting('acwp', 'acwp_emptyalt', array('show_in_rest' => true));
    }

    /**
     * Dashboard callback
     */
    public function main_page_callback() {
        ?>
        <div class="wrap">
            <h1><?php _e('Accessibility', 'acwp');?> <small style="font-size: 14px;">(<?php _e('by', 'acwp');?> AccessibleWP)</small></h1>
            <div id="welcome-panel" class="welcome-panel">
                <div class="welcome-panel-content">
                    <h2><?php _e('Welcome to AccessibleWP Dashboard!', 'acwp');?></h2>
                    <p class="about-description" style="max-width: 800px"><?php _e('This plugin was developed as part of our Accessibility plugins package to allow as much accessibility as possible to users across the network. we offer a variety of Accessibility plugins as you will find below.', 'acwp');?></p>
                    <div class="welcome-panel-column-container">
                        <div class="welcome-panel-column">
                            <h3><?php _e('Contact us', 'acwp');?></h3>
                            <a class="button button-primary button-hero load-customize hide-if-no-customize" href="https://www.codenroll.co.il/contact/" target="_blank"><?php _e('Our website', 'acwp');?></a>
                        </div>
                        <div class="welcome-panel-column">
                            <h3><?php _e('Our accessibility plugins', 'acwp');?></h3>
                            <ul>
                                <li><a href="https://www.wordpress.org/plugins/accessible-poetry/"><?php _e('Toolbar', 'acwp');?></a></li>
                                <li><a href="https://www.wordpress.org/plugins/accessiblewp-images/"><?php _e('Images', 'acwp');?></a></li>
                            </ul>
                        </div>
                        <div class="welcome-panel-column"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Missing ALT tab
     */
    public function submenu_tab_alt() {
        ?>
        <div id="acwp_alt" class="acwp-tab active">
            <h2><?php _e('Inaccessible Images', 'acwp');?></h2>
            <?php
            $images_query = new WP_Query(array(
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'post_status' => 'inherit',
                'posts_per_page' => -1,
            ));

            /**
             * Create array with all our images data
             */

            $images = array();

            if( !empty($images_query->posts) ){
                foreach ($images_query->posts as $img) {
                    $alt = get_post_meta($img->ID, '_wp_attachment_image_alt', true);

                    if(strlen($alt) === 0) {
                        $images[] = array(
                            'id' => $img->ID,
                            'name' => $img->post_title,
                            'url' => wp_get_attachment_thumb_url($img->ID)
                        );
                    }
                }
            }

            /**
             * Update post meta with the new Alternative text
             */
            if( isset($_POST['acwpAddAlt']) ){
                update_post_meta(sanitize_text_field($_POST['thumb_id']), '_wp_attachment_image_alt', sanitize_text_field($_POST['thumb_alt']));
                echo '<meta http-equiv="refresh" content="0">';
            }
            ?>
            <div id="acwp_missing_alts_platform" class="wrap">
                <table class="widefat">
                    <thead>
                    <tr>
                        <th><?php _e('ID', 'acwp');?></th>
                        <th><?php _e('Thumbnail', 'acwp'); ?></th>
                        <th><?php _e('Image Name', 'acwp');?></th>
                        <th><?php _e('Add your ALT', 'acwp');?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if( $images != null ) : ?>
                        <?php foreach($images as $key => $value) : ?>
                            <tr class="alternate">
                                <td style="max-width: 120px"><?php echo $value['id']; ?></td>
                                <td>
                                    <img style="max-height: 60px; width: auto" src="<?php echo $value['url']; ?>" class="acwp-thumb" alt="<?php _e('Thumbnail of', 'acwp');?> <?php echo esc_html($value['name']); ?>" />
                                </td>
                                <td><?php echo esc_html($value['name']); ?></td>
                                <td>
                                    <form method="post" action="">
                                        <?php settings_fields( 'acwp' ); ?>
                                        <?php do_settings_sections( 'acwp' ); ?>
                                        <input type="hidden" name="thumb_id" value="<?php echo $value['id']; ?>" />
                                        <input type="text" name="thumb_alt" value="" />
                                        <input type="submit" name="acwpAddAlt" class="button button-primary" value="Save ALT">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr class="alternate">
                            <td colspan="4">
                                <h3 style="font-weight:400;"><i class="dashicons-before dashicons-yes"></i><?php echo __('Congratulation! You don\'t have images without ALT text.', 'acwp');?></h3>
                            </td>
                        </tr>
                    <?php endif;?>
                    </tbody>
                </table>
            </div>
            <?php
            ?>
        </div>
        <?php
    }

    /**
     * Settings tab
     */
    public function submenu_tab_settings(){
        ?>

        <div id="acwp_settings" class="acwp-tab">

            <h2><?php _e('Settings', 'acwp');?></h2>
            <form method="post" action="options.php">
                <?php settings_fields( 'acwp' ); ?>
                <?php do_settings_sections( 'acwp' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <td colspan="2">
                        <p style="max-width: 600px;"><?php _e('By default, if image without ALT is presented through Gutenberg, it will get an empty ALT from the core of WordPress (which mean this option is not necessary). the option below is when Gutenberg is not enough or there is no use on it.', 'acwp'); ?></p></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Add empty ALT to images without ALT attribute', 'acwp');?></th>
                    <td><input type="checkbox" name="acwp_emptyalt" value="yes" <?php checked( esc_attr( get_option('acwp_emptyalt') ), 'yes' ); ?> /></td>
                </tr>
            </table>
            <?php submit_button();?>
        </form>
        </div>

        <?php
    }

    /**
     * Images admin page
     */
    public function submenu_page_callback() {
        ?>
        <div id="accessiblewp-images" class="wrap">
            <h1><?php _e('Images', 'acwp');?></h1>

                <div class="nav-tab-wrapper">
                    <a href="#acwp_alt" class="nav-tab nav-tab-active"><?php _e('Inaccessible Images', 'acwp');?></a>
                    <a href="#acwp_settings" class="nav-tab"><?php _e('Settings', 'acwp');?></a>
                </div>
                <?php
                echo $this->submenu_tab_settings();
                echo $this->submenu_tab_alt();
                ?>
        </div>
        <?php
    }
}
if( is_admin() )
    new ACWP_ImagesAdminPanel();


function acwp_images_admin_assets() {
    wp_enqueue_script( 'acwp-images-admin',    plugin_dir_url( __FILE__ ) . 'assets/js/admin.js', array( 'jquery' ), '', true );
    wp_enqueue_style( 'acwp-images-admin-css', plugin_dir_url( __FILE__ ) . 'assets/admin.css' );
    //wp_enqueue_media();
    //wp_enqueue_style( 'wp-color-picker');
   // wp_enqueue_script( 'wp-color-picker');
}
add_action('admin_enqueue_scripts', 'acwp_images_admin_assets');


function acwp_images_front_assets() {
    wp_enqueue_script(  'acwp-alt',     plugin_dir_url( __FILE__ )  . 'assets/js/acwp-images.js', array( 'jquery' ), '', true );
}
if( get_option('acwp_emptyalt') == 'yes' )
    add_action( 'wp_enqueue_scripts', 'acwp_images_front_assets' );
