<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       www.catchplugins.com
 * @since      1.0.0
 *
 * @package    Catch_Scroll_Progress_Bar
 * @subpackage Catch_Scroll_Progress_Bar/admin/partials
 */
?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e( 'Catch Scroll Progress Bar', 'catch-scroll-progress-bar' );?></h1>
    <div id="plugin-description">
        <p><?php esc_html_e( 'This is a simple, super-light WordPress progress bar plugin that has the most essential features to show the users how far they’ve scrolled through the current page or post', 'catch-scroll-progress-bar' ); ?></p>
    </div>
    <div class="catchp-content-wrapper">
        <div class="catchp_widget_settings">

            <form id="catch-scroll-progress-bar" method="post" action="options.php">

                    <h2 class="nav-tab-wrapper">
                    <a class="nav-tab nav-tab-active" id="dashboard-tab" href="#dashboard"><?php esc_html_e( 'Dashboard', 'catch-scroll-progress-bar' ); ?></a>
                    <a class="nav-tab" id="features-tab" href="#features"><?php esc_html_e( 'Features', 'catch-scroll-progress-bar' ); ?></a>
                </h2>
                <div id="dashboard" class="wpcatchtab nosave active">
                    <?php require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/dashboard-display.php';?>
                    <div id="ctp-switch" class="content-wrapper col-3 catch-scroll-progress-main">
                        <div class="header">
                            <h2><?php esc_html_e( 'Catch Themes & Catch Plugins Tabs', 'catch-scroll-progress-bar' ); ?></h2>
                        </div> <!-- .Header -->

                        <div class="content">

                            <p><?php echo esc_html__( 'If you want to turn off Catch Themes & Catch Plugins tabs option in Add Themes and Add Plugins page, please uncheck the following option.', 'catch-scroll-progress-bar' ); ?>
                            </p>
                            <table>
                                <tr>
                                    <td>
                                        <?php echo esc_html__( 'Turn On Catch Themes & Catch Plugin tabs', 'catch-scroll-progress-bar' );  ?>
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
                </div><!---dashboard---->

                <div id="features" class="wpcatchtab save">
                    <div class="content-wrapper col-3">
                        <div class="header">
                            <h3><?php esc_html_e( 'Features', 'catch-scroll-progress-bar' );?></h3>
                        </div><!-- .header -->
                        <div class="content">
                            <ul class="catchp-lists">
                               <li>
                                    <strong><?php esc_html_e( 'Progress Bar Position', 'catch-scroll-progress-bar' ); ?></strong>
                                    <p><?php esc_html_e( 'Mostly progress bars are displayed on the top of the website. However, with our new WordPress progress bar plugin, you can exhibit your progress bar either on the top or the bottom of your website. The option is there to let you choose the position where your website would flaunt out its beauty and would not disturb other elements on your website.','catch-scroll-progress-bar' ); ?></p>
                                </li>

                                <li>
                                    <strong><?php esc_html_e( 'Color Options and Opacity', 'catch-scroll-progress-bar' ); ?></strong>
                                    <p><?php esc_html_e( 'There is an unlimited color palette to choose the background and foreground of your reading progress bar. Choose the ones that go along with your website and its look. Now, you don’t want your progress bar to look like the odd-one, do you? Also, after choosing the correct colors for your background and foreground, you can also select the opacity for both of the fields.','catch-scroll-progress-bar' ); ?></p>
                                </li>

                                 <li>
                                    <strong><?php esc_html_e( 'Progress Bar Height ', 'catch-scroll-progress-bar' ); ?></strong>
                                    <p><?php esc_html_e( 'The Progress Bar height setting allows you to choose the thickness of your reading progress bar. Some might like it thin to make it more elegant. Whereas, some might prefer the progress bar a little thick to make it bold and eye-catching. ','catch-scroll-progress-bar' ); ?></p>
                                </li>
                                <li>
                                    <strong><?php esc_html_e( 'Border Radius', 'catch-scroll-progress-bar' ); ?></strong>
                                    <p><?php esc_html_e( 'If you want to give your progress bar a tiny adornment, you can make it curvy by tweaking the Border Radius.  ','catch-scroll-progress-bar' ); ?></p>
                                </li>

                                <li>
                                    <strong><?php esc_html_e( 'Template Condition', 'catch-scroll-progress-bar' ); ?></strong>
                                    <p><?php esc_html_e( 'This is where you need to select the Template Condition for your progress bar as to where you want it to be displayed. You can choose the reading progress bar to be displayed on the front page, blog page, or posts and pages. You can also choose to display the reading bar only on posts or only on the pages of your website.  ','catch-scroll-progress-bar' ); ?></p>
                                </li>

                                <li>
                                    <strong><?php esc_html_e( 'Light Weight', 'catch-scroll-progress-bar' ); ?></strong>
                                    <p><?php esc_html_e( 'Catch Scroll Progress Bar is a simple WordPress plugin to display an elegant reading bar that is extremely lightweight. It means you will not have to worry about your website getting slower because of the plugin.','catch-scroll-progress-bar' ); ?></p>
                                </li>

                                <li>
                                    <strong><?php esc_html_e( 'Responsive Design', 'catch-scroll-progress-bar' ); ?></strong>
                                    <p><?php esc_html_e( 'Our new WordPress Progress Bar plugin comes with a responsive design, therefore, there is no need to strain about the plugin breaking your website.','catch-scroll-progress-bar' ); ?></p>
                                </li>

                                <li>
                                    <strong><?php esc_html_e( 'Compatible with all WordPress Themes', 'catch-scroll-progress-bar' ); ?></strong>
                                    <p><?php esc_html_e( 'Gutenberg Compatibility is one of the major concerns nowadays for every plugin developer. Our new Catch Scroll Progress Bar plugin has been crafted in a way that supports all the WordPress themes. The plugin functions smoothly on any WordPress theme.','catch-scroll-progress-bar' ); ?></p>
                                </li>

                                <li>
                                    <strong><?php esc_html_e( 'Incredible Support', 'catch-scroll-progress-bar' ); ?></strong>
                                    <p><?php esc_html_e( 'Catch Scroll Progress Bar comes with Incredible Support. Our plugin documentation answers most questions about using the plugin.  If you’re still having difficulties, you can post it in our Support Forum.','catch-scroll-progress-bar'); ?></p>
                                </li>


                            </ul>
                        </div><!-- .content -->
                    </div><!-- content-wrapper -->
                </div> <!-- Featured -->
            </form><!-- duplicate-page -->
        </div><!-- .catchp_widget_settings -->
        <?php require_once plugin_dir_path(dirname(__FILE__) ) .'/partials/sidebar.php';?>
    </div><!---catch-content-wrapper---->
<?php require_once plugin_dir_path( dirname( __FILE__ ) ) . '/partials/footer.php'; ?>
</div><!-- .wrap -->
