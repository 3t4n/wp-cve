<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       catchplugins.com
 * @since      1.0.0
 *
 * @package    Catch_Gallery
 * @subpackage Catch_Gallery/admin
 */

?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e( 'Catch Gallery', 'catch-gallery' ); ?></h1>
    <div id="plugin-description">
        <p><?php esc_html_e( 'Catch Gallery - Make your Galleries more Appealing!', 'catch-gallery' ); ?></p>
    </div>
    <div class="catch-content-wrapper">
        <div class="catch_widget_settings">

            <h2 class="nav-tab-wrapper">
                <a class="nav-tab nav-tab-active" id="dashboard-tab" href="#dashboard"><?php esc_html_e( 'Settings', 'catch-gallery' ); ?></a>
                <a class="nav-tab" id="features-tab" href="#features"><?php esc_html_e( 'Features', 'catch-gallery' ); ?></a>
            </h2>

            <div id="dashboard" class="wpcatchtab nosave active">

                <?php require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/display-dashboard.php'; ?>
                <div id="ctp-switch" class="content-wrapper col-3 catch-gallery-main">
                        <div class="header">
                            <h2><?php esc_html_e( 'Catch Themes & Catch Plugins Tabs', 'catch-gallery' ); ?></h2>
                        </div> <!-- .Header -->

                        <div class="content">

                            <p><?php echo esc_html__( 'If you want to turn off Catch Themes & Catch Plugins tabs option in Add Themes and Add Plugins page, please uncheck the following option.', 'catch-gallery' ); ?>
                            </p>
                            <table>
                                <tr>
                                    <td>
                                        <?php echo esc_html__( 'Turn On Catch Themes & Catch Plugin tabs', 'catch-gallery' );  ?>
                                    </td>
                                    <td>
                                        <?php $ctp_options = ctp_get_options(); ?>
                                        <div class="module-header <?php echo $ctp_options['theme_plugin_tabs'] ? 'active' : 'inactive'; ?>">
                                            <div class="switch">
                                                <input type="hidden" name="ctp_tabs_nonce" id="ctp_tabs_nonce" value="<?php echo esc_attr( wp_create_nonce( 'ctp_tabs_nonce' ) ); ?>" />
                                                <input type="checkbox" id="ctp_options[theme_plugin_tabs]" class="ctp-switch" rel="theme_plugin_tabs" <?php checked( true, $ctp_options['theme_plugin_tabs'] ); ?> >
                                                <label for="ctp_options[theme_plugin_tabs]"></label>
                                            </div>
                                            <div class="loader"></div>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                        </div>
                    </div><!-- #ctp-switch -->

            </div><!-- .dashboard -->

            <div id="features" class="wpcatchtab save">
                <div class="content-wrapper col-3">
                    <div class="header">
                        <h3><?php esc_html_e( 'Features', 'catch-gallery' ); ?></h3>

                    </div><!-- .header -->
                    <div class="content">
                        <ul class="catch-lists">
                            <li>
                                <strong><?php esc_html_e( 'Tiled Mosiac', 'catch-gallery' ); ?></strong>
                                <p><?php esc_html_e( 'A normal photo gallery would often have regular spacing with unpleasant and uneven gaps between each image. With Tiled Mosaic layout, your galleries will reduce the gap between each image to produce a tiled effect. This will show your images in a beautiful mosaic layout.', 'catch-gallery' ); ?></p>
                            </li>

                            <li>
                                <strong><?php esc_html_e( 'Square Tiles', 'catch-gallery' ); ?></strong>
                                <p><?php esc_html_e( 'As the name suggests, Square Tiles layout allows you to display your galleries with square tiles. The spacing between the images are even and your galleries look sleeker than ever.', 'catch-gallery' ); ?></p>
                            </li>

                            <li>
                                <strong><?php esc_html_e( 'Circles', 'catch-gallery' ); ?></strong>
                                <p><?php esc_html_e( 'With Catch Gallery layout options, you can display all your gallery pictures in cool circular tiles. If you want your galleries to look a little fancier, you can select your gallery layout as Circles.', 'catch-gallery' ); ?></p>
                            </li>

                            <li>
                                <strong><?php esc_html_e( 'Lightweight', 'catch-gallery' ); ?></strong>
                                <p><?php esc_html_e( 'Catch Gallery, a simple gallery plugin for WordPress is extremely lightweight. It means you will not have to worry about your website getting slower because of the plugin.', 'catch-gallery' ); ?></p>
                            </li>

                            <li>
                                <strong><?php esc_html_e( 'Responsive Design', 'catch-gallery' ); ?></strong>
                                <p><?php esc_html_e( 'Catch Gallery comes with a responsive design, which means, your galleries will look beautiful on all devices. Your visitors will definitely enjoy strolling through your galleries that look elegant and aesthetic.', 'catch-gallery' ); ?></p>
                            </li>

                            <li>
                                <strong><?php esc_html_e( 'Compatible with all Themes', 'catch-gallery' ); ?></strong>
                                <p><?php esc_html_e( 'Catch Gallery has been crafted in a way that supports all the themes on WordPress. The plugin functions smoothly on every WordPress theme. Therefore, you will not have to worry about the plugin not being compatible with your current WordPress theme.', 'catch-gallery' ); ?></p>
                            </li>

                            <li>
                                <strong><?php esc_html_e( 'Incredible Support', 'catch-gallery' ); ?></strong>
                                <p><?php esc_html_e( 'No matter the device your users are using, galleries and portfolios put together with Catch Gallery will adapt to the screen like a dream. You don’t need to worry about the user experience since it will be intact. It is an extremely easy-to-use gallery plugin that provides an eye-friendly gallery for your users.', 'catch-gallery' ); ?></p>
                            </li>
                        </ul>
                    </div><!-- .content -->
                </div><!-- content-wrapper -->
            </div> <!-- Featured -->

        </div><!-- .catch_widget_settings -->


        <?php require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/sidebar.php'; ?>
    </div> <!-- .catch-content-wrapper -->

    <?php require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/footer.php'; ?>
</div><!-- .wrap -->
