<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * Create the plugin Settings page
 *
 * @link       https://walterpinem.me/
 * @since      1.0.0
 *
 * @package    Wp_Mylinks
 * @subpackage Wp_Mylinks/admin/partials
 * @author     Walter Pinem <hello@walterpinem.me>
 * @copyright  Copyright (c) 2020, Walter Pinem, Seni Berpikir
 */

// Donate button
function wp_mylinks_donate_button_shortcode($atts, $content = null)
{
    return '<center>
<div class="donate-container">
<p>To keep this plugin free, I spent cups of coffee building it. If you love and find it really useful for you or your business, you can always</p>
<a href="https://www.paypal.me/WalterPinem" target="_blank">
<button class="donatebutton">
  ☕ Buy Me a Coffee
  </button>
</a>
</div>
</center>';
}
add_shortcode('donate', 'wp_mylinks_donate_button_shortcode');

// Begin creating the plugin Settings page
function wp_mylinks_create_admin_page()
{
    // Extend the menu by creating a sub-menu
    add_submenu_page('edit.php?post_type=mylink', 'WP MyLinks Settings', 'Settings', 'manage_options', 'welcome', 'wp_mylinks_admin_page', 26);
    // Begin building
    add_action('admin_init', 'wp_mylinks_register_settings');
}
add_action('admin_menu', 'wp_mylinks_create_admin_page');
function wp_mylinks_register_settings()
{
    // Register the settings
    register_setting('mylinks-global', 'mylinks_theme');
    register_setting('mylinks-global', 'mylinks_meta_title');
    register_setting('mylinks-global', 'mylinks_meta_description');
    register_setting('mylinks-global', 'mylinks_upload_favicon');
    register_setting('mylinks-global', 'wp_mylinks_nofollow');
    register_setting('mylinks-global', 'wp_mylinks_noindex');
    register_setting('mylinks-global', 'wp_mylinks_credits');
    register_setting('mylinks-global', 'wp_mylinks_hide_notice');
    register_setting('mylinks-custom-scripts', 'wp_mylinks_analytics');
    register_setting('mylinks-custom-scripts', 'wp_mylinks_header_script');
    register_setting('mylinks-custom-scripts', 'wp_mylinks_footer_script');
    register_setting('mylinks-custom-scripts', 'wp_mylinks_custom_css');
    register_setting('mylinks-custom-scripts', 'wp_mylinks_dequeue');
}

// Delete option upon deactivation
function wp_mylinks_deactivation()
{
    delete_option('mylinks_theme');
    delete_option('mylinks_meta_title');
    delete_option('mylinks_meta_description');
    delete_option('mylinks_upload_favicon');
    delete_option('wp_mylinks_nofollow');
    delete_option('wp_mylinks_noindex');
    delete_option('wp_mylinks_credits');
    delete_option('wp_mylinks_hide_notice');
    delete_option('wp_mylinks_analytics');
    delete_option('wp_mylinks_header_script');
    delete_option('wp_mylinks_footer_script');
    delete_option('wp_mylinks_custom_css');
    delete_option('wp_mylinks_dequeue');
}
register_deactivation_hook(__FILE__, 'wp_mylinks_deactivation');
// Begin Building the Admin Option
function wp_mylinks_admin_page()
{
    if ($active_tab = isset($_GET['tab'])) {
        $active_tab = esc_attr($_GET['tab']);
    } else if ($active_tab == 'global') {
        $active_tab = 'global';
    } else if ($active_tab == 'script') {
        $active_tab = 'script';
    } else if ($active_tab == 'tutorial_support') {
        $active_tab = 'tutorial_support';
    } else {
        $active_tab = 'welcome';
    } // end if/else
?>
    <div class="wrap wp_mylinks_pluginpage_title">
        <h1><?php _e('WP MyLinks', 'mylinks'); ?></h1>
        <hr>
        <h2 class="nav-tab-wrapper">
            <a href="?post_type=mylink&page=welcome" class="nav-tab <?php echo esc_attr($active_tab == 'welcome') ? 'nav-tab-active' : ''; ?>"><?php _e('Welcome', 'wp-mylinks'); ?></a>
            <a href="?post_type=mylink&page=welcome&tab=global" class="nav-tab <?php echo esc_attr($active_tab == 'global') ? 'nav-tab-active' : ''; ?>"><?php _e('Global', 'wp-mylinks'); ?></a>
            <a href="?post_type=mylink&page=welcome&tab=script" class="nav-tab <?php echo esc_attr($active_tab == 'script') ? 'nav-tab-active' : ''; ?>"><?php _e('Custom Script', 'wp-mylinks'); ?></a>
            <a href="?post_type=mylink&page=welcome&tab=tutorial_support" class="nav-tab <?php echo esc_attr($active_tab == 'tutorial_support') ? 'nav-tab-active' : ''; ?>"><?php _e('Support', 'wp-mylinks'); ?></a>
        </h2>
        <?php if ($active_tab == 'script') { ?>
            <!-- Custom Script & Style -->
            <form method="post" action="options.php">
                <?php settings_errors(); ?>
                <?php settings_fields('mylinks-custom-scripts'); ?>
                <?php do_settings_sections('mylinks-custom-scripts'); ?>
                <h1 class="section_wp_mylinks"><?php _e('Custom Scripts & Styles', 'wp-mylinks'); ?></h1>
                <p>
                    <?php _e('Add custom scripts and styles for the MyLinks page.', 'wp-mylinks'); ?>
                    <br />
                </p>
                <hr>
                <h2 class="section_wp_mylinks"><?php _e('Analytics Tracking Scripts', 'wp-mylinks'); ?></h2>
                <p>
                    <?php _e('Track how the MyLinks page performs with Google Analytics and any other analytics scripts.', 'wp-mylinks'); ?>
                    <br />
                </p>
                <table class="form-table">
                    <tbody>
                        <tr class="wp_mylinks_options">
                            <th scope="row">
                                <label class="mylinks_misc" for="meta_title"><b><?php _e('Analytics Script', 'wp-mylinks'); ?></b></label>
                            </th>
                            <td>
                                <textarea name="wp_mylinks_analytics" class="mylinks_input_textarea" rows="5"><?php echo get_option('wp_mylinks_analytics'); ?></textarea>
                                <p class="input-description">
                                    <?php _e('Please include the <code>&lt;script&gt;</code>...<code>&lt;/script&gt;</code> tags.', 'wp-mylinks'); ?>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <h2 class="section_wp_mylinks"><?php _e('Custom Scripts', 'wp-mylinks'); ?></h2>
                <p>
                    <?php _e('You can put about anything you want from Google Tag Manager to Facebook Pixel script in the header and footer sections of the<br> MyLinks page.', 'wp-mylinks'); ?>
                    <br />
                </p>
                <table class="form-table">
                    <tbody>
                        <tr class="wp_mylinks_options">
                            <th scope="row">
                                <label class="mylinks_misc" for="custom_scripts"><b><?php _e('Header', 'wp-mylinks'); ?></b></label>
                            </th>
                            <td>
                                <textarea name="wp_mylinks_header_script" class="mylinks_input_textarea" rows="5"><?php echo get_option('wp_mylinks_header_script'); ?></textarea>
                                <p class="input-description">
                                    <?php _e('Anything you put here will be included in <code>&lt;head&gt;</code>. Please include <code>&lt;script&gt;</code> etc.', 'wp-mylinks'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr class="wp_mylinks_options">
                            <th scope="row">
                                <label class="mylinks_misc" for="custom_scripts"><b><?php _e('Footer', 'wp-mylinks'); ?></b></label>
                            </th>
                            <td>
                                <textarea name="wp_mylinks_footer_script" class="mylinks_input_textarea" rows="5"><?php echo get_option('wp_mylinks_footer_script'); ?></textarea>
                                <p class="input-description">
                                    <?php _e('Anything you put here will be placed just before <code>&lt;/body&gt;</code>. Please include <code>&lt;script&gt;</code> etc.', 'wp-mylinks'); ?>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <h2 class="section_wp_mylinks"><?php _e('Custom Styles', 'wp-mylinks'); ?></h2>
                <p>
                    <?php _e('You can set custom styles for the MyLinks page.', 'wp-mylinks'); ?>
                    <br />
                </p>
                <table class="form-table">
                    <tbody>
                        <tr class="wp_mylinks_options">
                            <th scope="row">
                                <label class="mylinks_misc" for="custom_scripts"><b><?php _e('Custom CSS', 'wp-mylinks'); ?></b></label>
                            </th>
                            <td>
                                <textarea name="wp_mylinks_custom_css" class="mylinks_input_textarea" rows="5"><?php echo get_option('wp_mylinks_custom_css'); ?></textarea>
                                <p class="input-description">
                                    <?php _e('Add your custom css code without the <code>&lt;style&gt;</code> tag.', 'wp-mylinks'); ?>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <h2 class="section_wp_mylinks"><?php _e('Dequeue Other Scripts and Styles', 'wp-mylinks'); ?></h2>
                <p>
                    <?php _e('<strong>Experimental!</strong> Some plugins might add additional scripts and styles into the MyLink page, which could result in display issues. By activating this feature, this plugin will forcibly remove all scripts and styles added by other plugins.', 'wp-mylinks'); ?>
                    <br />
                </p>
                <p>
                    <?php _e('Should you encounter issues like images not displaying properly, missing images, or styling problems, you may need to enable this feature.', 'wp-mylinks'); ?>
                    <br />
                </p>
                <table class="form-table">
                    <tbody>
                        <tr class="wp_mylinks_options">
                            <th scope="row">
                                <label class="wp_mylinks_dequeue" for="wp_mylinks_dequeue"><b><?php _e('Dequeue', 'wp-mylinks'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wp_mylinks_dequeue" class="my_links_checkbox" value="yes" <?php checked(get_option('wp_mylinks_dequeue'), 'yes'); ?>>
                                <?php _e('Dequeue All Scripts and Styles', 'wp-mylinks'); ?>
                                <br>
                                <p class="input-description">
                                    <?php _e('This will dequeue other plugins\' scripts and styles only on MyLink page.', 'wp-mylinks'); ?>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <?php submit_button(); ?>
            </form>
            <!-- End - Custom Scripts & Styles -->
        <?php } elseif ($active_tab == 'global') { ?>
            <!-- Global Configurations -->
            <form method="post" action="options.php">
                <?php settings_errors(); ?>
                <?php settings_fields('mylinks-global'); ?>
                <?php do_settings_sections('mylinks-global'); ?>
                <h1 class="section_wp_mylinks"><?php _e('Global Configurations', 'wp-mylinks'); ?></h1>
                <p>
                    <?php _e('Unless set on individual MyLinks page, below configurations will be implemented globally.', 'wp-mylinks'); ?>
                    <br />
                </p>
                <hr>
                <h2 class="section_wp_mylinks"><?php _e('Display Settings', 'wp-mylinks'); ?></h2>
                <p>
                    <?php _e('Determine how you want the MyLinks page to look like globally. If you set an individual MyLinks page\'s theme to <code>None</code>,<br> this setting will be used instead.', 'wp-mylinks'); ?>
                    <br />
                </p>
                <table class="form-table">
                    <tbody>
                        <tr class="wp_mylinks_options">
                            <th scope="row">
                                <label class="mylinks_theme" for="meta_title"><b><?php _e('Global Theme', 'wp-mylinks'); ?></b></label>
                            </th>
                            <td>
                                <?php $theme_options = get_option('mylinks_theme'); ?>
                                <select name="mylinks_theme" class="mylinks_input_select">
                                    <?php $selected = (isset($theme_options) && $theme_options === 'default') ? 'selected' : ''; ?>
                                    <option name="mylinks_theme" value='default' <?php echo $selected; ?>>Default</option>
                                    <?php $selected = (isset($theme_options) && $theme_options === 'merbabu') ? 'selected' : ''; ?>
                                    <option name="mylinks_theme" value='merbabu' <?php echo $selected; ?>>Merbabu</option>
                                    <?php $selected = (isset($theme_options) && $theme_options === 'cikuray') ? 'selected' : ''; ?>
                                    <option name="mylinks_theme" value='cikuray' <?php echo $selected; ?>>Cikuray</option>
                                    <?php $selected = (isset($theme_options) && $theme_options === 'ciremai') ? 'selected' : ''; ?>
                                    <option name="mylinks_theme" value='ciremai' <?php echo $selected; ?>>Ciremai</option>
                                    <?php $selected = (isset($theme_options) && $theme_options === 'slamet') ? 'selected' : ''; ?>
                                    <option name="mylinks_theme" value='slamet' <?php echo $selected; ?>>Slamet</option>
                                    <?php $selected = (isset($theme_options) && $theme_options === 'papandayan') ? 'selected' : ''; ?>
                                    <option name="mylinks_theme" value='papandayan' <?php echo $selected; ?>>Papandayan</option>
                                    <?php $selected = (isset($theme_options) && $theme_options === 'sindoro') ? 'selected' : ''; ?>
                                    <option name="mylinks_theme" value='sindoro' <?php echo $selected; ?>>Sindoro</option>
                                    <?php $selected = (isset($theme_options) && $theme_options === 'krakatau') ? 'selected' : ''; ?>
                                    <option name="mylinks_theme" value='krakatau' <?php echo $selected; ?>>Krakatau</option>
                                    <?php $selected = (isset($theme_options) && $theme_options === 'bromo') ? 'selected' : ''; ?>
                                    <option name="mylinks_theme" value='bromo' <?php echo $selected; ?>>Bromo</option>
                                    <?php $selected = (isset($theme_options) && $theme_options === 'prau') ? 'selected' : ''; ?>
                                    <option name="mylinks_theme" value='prau' <?php echo $selected; ?>>Prau</option>
                                    <?php $selected = (isset($theme_options) && $theme_options === 'polos') ? 'selected' : ''; ?>
                                    <option name="mylinks_theme" value='polos' <?php echo $selected; ?>>Polos</option>
                                    <?php $selected = (isset($theme_options) && $theme_options === 'datar') ? 'selected' : ''; ?>
                                    <option name="mylinks_theme" value='datar' <?php echo $selected; ?>>Datar</option>
                                    <?php $selected = (isset($theme_options) && $theme_options === 'pastel') ? 'selected' : ''; ?>
                                    <option name="mylinks_theme" value='pastel' <?php echo $selected; ?>>Pastel</option>
                                    <?php $selected = (isset($theme_options) && $theme_options === 'kopi-hitam') ? 'selected' : ''; ?>
                                    <option name="mylinks_theme" value='kopi-hitam' <?php echo $selected; ?>>Kopi Hitam</option>
                                    <?php $selected = (isset($theme_options) && $theme_options === 'kopi-susu') ? 'selected' : ''; ?>
                                    <option name="mylinks_theme" value='kopi-susu' <?php echo $selected; ?>>Kopi Susu</option>
                                </select>
                                <p class="input-description">
                                    <?php _e('Set the theme for the MyLinks page.', 'wp-mylinks'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr class="wp_mylinks_options">
                            <th scope="row">
                                <label class="wp_mylinks_credits" for="mylinks_nofollow"><b><?php _e('Show Some ❤ to Support Me?', 'wp-mylinks'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wp_mylinks_credits" class="my_links_checkbox" value="yes" <?php checked(get_option('wp_mylinks_credits'), 'yes'); ?>>
                                <?php _e('Yes, I Definitely Want to Support You', 'wp-mylinks'); ?>
                                <br>
                                <p class="input-description">
                                    <?php _e('This will add credits on the footer of MyLinks page.', 'wp-mylinks'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr class="wp_mylinks_options">
                            <th scope="row">
                                <label class="wp_mylinks_hide_notice" for="mylinks_notice"><b><?php _e('Hide Admin Notice?', 'wp-mylinks'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wp_mylinks_hide_notice" class="my_links_checkbox" value="yes" <?php checked(get_option('wp_mylinks_hide_notice'), 'yes'); ?>>
                                <?php _e('Everything\'s Alright, Hide Notice Now', 'wp-mylinks'); ?>
                                <br>
                                <p class="input-description">
                                    <?php _e('This will hide admin notice to flush your permalinks.', 'wp-mylinks'); ?>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <table class="form-table">
                    <tbody>
                        <h2 class="section_wp_mylinks"><?php _e('Setup Global Meta Tags', 'wp-mylinks'); ?></h2>
                        <p>
                            <?php _e('Meta tags for the MyLinks page, will be shown both on search engine result and browser tab. If you use Yoast SEO or built-in<br> <strong>Setup Meta Tags</strong> form and already set both the <code>meta title</code> and <code>description</code> on MyLinks post editor, they will be used<br> instead of below values.', 'wp-mylinks'); ?>
                            <br />
                        </p>
                        <tr class="wp_mylinks_options">
                            <th scope="row">
                                <label class="mylinks_meta_title" for="meta_title"><b><?php _e('Meta Title', 'wp-mylinks'); ?></b></label>
                            </th>
                            <td>
                                <input type="text" name="mylinks_meta_title" class="mylinks_input_text" value="<?php echo get_option('mylinks_meta_title'); ?>" placeholder="<?php _e('e.g. Your MyLinks Title | Your Site Title', 'wp-mylinks'); ?>">
                                <p class="input-description">
                                    <?php _e('Set the meta title for the MyLinks page.', 'wp-mylinks'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr class="wp_mylinks_options">
                            <th scope="row">
                                <label class="mylinks_meta_description" for="meta_description"><b><?php _e('Meta Description', 'wp-mylinks'); ?></b></label>
                            </th>
                            <td>
                                <textarea name="mylinks_meta_description" class="mylinks_input_textarea" rows="5"><?php echo get_option('mylinks_meta_description'); ?></textarea>
                                <p class="input-description">
                                    <?php _e('Set the meta description of the MyLinks page.', 'wp-mylinks'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr class="wp_mylinks_options">
                            <th scope="row">
                                <label class="mylinks_upload_favicon" for="meta_description"><b><?php _e('Custom Favicon', 'wp-mylinks'); ?></b></label>
                            </th>
                            <td>
                                <input id="mylinks_upload_favicon" class="wp-mylinks-uploader-url" type="text" name="mylinks_upload_favicon" value="<?php echo get_option('mylinks_upload_favicon'); ?>" />
                                <input id="upload_image_button" type="button" class="button-primary" value="Choose Favicon" />
                                <p class="input-description">
                                    <?php _e('Set a favicon for the MyLinks page.', 'wp-mylinks'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr class="wp_mylinks_options">
                            <th scope="row">
                                <label class="wp_mylinks_noindex" for="mylinks_noindex"><b><?php _e('Set to <code>noindex</code>?', 'wp-mylinks'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wp_mylinks_noindex" class="my_links_checkbox" value="yes" <?php checked(get_option('wp_mylinks_noindex'), 'yes'); ?>>
                                <?php _e('Yes, Set to <code>noindex</code>', 'wp-mylinks'); ?>
                                <br>
                                <p class="input-description">
                                    <?php _e('This will prevent MyLinks page from being indexed on search engine.', 'wp-mylinks'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr class="wp_mylinks_options">
                            <th scope="row">
                                <label class="wp_mylinks_nofollow" for="mylinks_nofollow"><b><?php _e('Set to <code>nofollow</code>?', 'wp-mylinks'); ?></b></label>
                            </th>
                            <td>
                                <input type="checkbox" name="wp_mylinks_nofollow" class="my_links_checkbox" value="yes" <?php checked(get_option('wp_mylinks_nofollow'), 'yes'); ?>>
                                <?php _e('Yes, Set to <code>nofollow</code>', 'wp-mylinks'); ?>
                                <br>
                                <p class="input-description">
                                    <?php _e('This will ban crawlers to follow all the links on the MyLinks page.', 'wp-mylinks'); ?>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <?php submit_button(); ?>
            </form>
        <?php } elseif ($active_tab == 'tutorial_support') { ?>
            <!-- Tutorial & Support tab -->
            <div class="wrap">
                <div class="feature-section one-col wrap about-wrap">

                    <div class="about-text">
                        <h4><?php printf(__("<strong>WP MyLinks</strong> is Waiting for Your Feedback", 'wp-mylinks')); ?></h>
                    </div>
                    <div class="indo-about-description">
                        <?php printf(__("<strong>WP MyLinks</strong> is my fourth plugin and it's open source. I acknowledge that there are still a lot to fix, here and there, that's why I really need your feedback. <br>Send a feedback through some of below options to contact me:", 'wp-mylinks')); ?>
                    </div>

                    <table class="tg" style="table-layout: fixed; width: 269px">
                        <colgroup>
                            <col style="width: 105px">
                            <col style="width: 164px">
                        </colgroup>
                        <tr>
                            <th class="tg-kiyi">
                                <?php _e('Author:', 'wp-mylinks'); ?></th>
                            <th class="tg-fymr">
                                <?php _e('Walter Pinem', 'wp-mylinks'); ?></th>
                        </tr>
                        <tr>
                            <td class="tg-kiyi">
                                <?php _e('Website:', 'wp-mylinks'); ?></td>
                            <td class="tg-fymr"><a href="https://walterpinem.me/" target="_blank">
                                    <?php _e('walterpinem.me', 'wp-mylinks'); ?></a></td>
                        </tr>
                        <tr>
                            <td class="tg-kiyi" rowspan="2"></td>
                            <td class="tg-fymr"><a href="https://www.seniberpikir.com/" target="_blank">
                                    <?php _e('www.seniberpikir.com', 'wp-mylinks'); ?></a></td>
                        </tr>
                        <tr>
                            <td class="tg-fymr"><a href="https://kerjalepas.com/" target="_blank">
                                    <?php _e('www.kerjalepas.com', 'wp-mylinks'); ?></a></td>
                        </tr>
                        <tr>
                            <td class="tg-kiyi">
                                <?php _e('Email:', 'wp-mylinks'); ?></td>
                            <td class="tg-fymr"><a href="mailto:hello@walterpinem.me" target="_blank">
                                    <?php _e('hello@walterpinem.me', 'wp-mylinks'); ?></a></td>
                        </tr>
                        <tr>
                            <td class="tg-kiyi"><?php _e('More:', 'wp-mylinks'); ?></td>
                            <td class="tg-fymr"><a href="https://www.youtube.com/watch?v=WK03GS5rM0Q&list=PLwazGJFvaLnCZrBRuDeDsbkpjjOPKz4pC" target="_blank">
                                    <?php _e('Video Tutorial', 'wp-mylinks'); ?></a></td>
                        </tr>
                        <tr>
                            <td class="tg-kiyi"></td>
                            <td class="tg-fymr"><a href="https://walterpinem.me/projects/customization-service/?utm_source=mylink-support-tab&utm_medium=sidebar&utm_campaign=WP-MyLinks" target="_blank">
                                    <?php _e('Customization Service', 'wp-mylinks'); ?></a></td>
                        </tr>
                        <tr>
                            <td class="tg-kiyi"></td>
                            <td class="tg-fymr"><a href="https://walterpinem.me/projects/contact/?utm_source=mylink-support-tab&utm_medium=sidebar&utm_campaign=WP-MyLinks" target="_blank">
                                    <?php _e('Support & Feature Request', 'wp-mylinks'); ?></a></td>
                        </tr>
                        <tr>
                            <td class="tg-kiyi" rowspan="3"></td>
                            <td class="tg-fymr"><a href="https://walterpinem.me/projects/?utm_source=mylink-support-tab&utm_medium=sidebar&utm_campaign=WP-MyLinks" target="_blank">
                                    <?php _e('Other Projects', 'wp-mylinks'); ?></a></td>
                        </tr>
                        <tr>
                            <td class="tg-fymr"><a href="https://walterpinem.me/portfolio/?utm_source=mylink-support-tab&utm_medium=sidebar&utm_campaign=WP-MyLinks" target="_blank">
                                    <?php _e('Portfolio', 'wp-mylinks'); ?></a></td>
                        </tr>
                        <tr>
                            <td class="tg-fymr"><a href="https://www.linkedin.com/in/walterpinem/" target="_blank">
                                    <?php _e('Linkedin', 'wp-mylinks'); ?></a></td>
                        </tr>
                        <tr>
                            <td class="tg-kiyi" rowspan="3"></td>
                            <td class="tg-fymr"><a href="https://www.paypal.me/WalterPinem" target="_blank">
                                    <?php _e('Donate', 'wp-mylinks'); ?></a></td>
                        </tr>
                    </table>
                    <br>

                    <?php echo do_shortcode("[donate]"); ?>

                    <center>
                        <p><?php printf(__("Created with ❤️ and ☕ in Jakarta, Indonesia by <a href=\"https://walterpinem.me\" target=\"_blank\"><strong>Walter Pinem</strong></a>", 'wp-mylinks')); ?></p>
                    </center>
                </div>
            </div>
        <?php } elseif ($active_tab == 'welcome') { ?>
            <!-- Begin creating plugin admin page -->
            <div class="wrap">
                <div class="feature-section one-col wrap about-wrap">
                    <!-- <div class="wp-badge welcome__logo"></div> -->
                    <div class="mylinks-title">
                        <h2><?php printf(__('Thank You For Using<br> WP MyLinks', 'wp-mylinks')); ?></h2>
                        <img src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/wp-mylinks.png'; ?>" />
                    </div>

                    <div class="feature-section one-col about-text">
                        <h3><?php printf(__("Build Fully Customizable Micro Landing Pages For Your Brand!", 'wp-mylinks')); ?></h3>
                    </div>
                    <div class="feature-section one-col indo-about-description">
                        <?php printf(__("<strong>WP MyLinks</strong> can help you create a micro landing page that contains all the links you want to share to your audience with the tool you're currently using and the domain name that reflects your own brand. Share one single link for everything!", 'wp-mylinks')); ?>
                        <h4><?php printf(__("Want to Expand the WP MyLinks' Features?", 'wp-mylinks')); ?></h4>
                        <?php printf(__("Imagine having the power to transform the WP MyLinks plugin into a personalized powerhouse based on your imagination, ideas, and needs. Sounds too good to be true? Well, buckle up, because now I'm offering customization service to expand WP MyLinks' features and unlock its true potentials!", 'wp-mylinks')); ?>
                        <?php printf(__("All customization service fees are highly affordable and negotiable.", 'wp-mylinks')); ?><br>
                        <a href="https://walterpinem.me/projects/customization-service/?utm_source=mylink-welcome-tab&utm_medium=sidebar&utm_campaign=WP-MyLinks" target="_blank" class="button button-primary button-hero" style="text-align:center;display:block;margin:20px auto;">
                            <?php printf(__("Send Inquiry", 'wp-mylinks')); ?>
                        </a>
                    </div>
                    <div class="clear"></div>
                    <hr />
                    <div class="feature-section one-col">
                        <h3 style="text-align: center;"><?php _e('Watch the Complete Overview and Tutorial', 'wp-mylinks'); ?></h3>
                        <div class="headline-feature feature-video">
                            <div class='embed-container'>
                                <iframe src='//www.youtube.com/embed/?listType=playlist&list=PLwazGJFvaLnCZrBRuDeDsbkpjjOPKz4pC' frameborder='0' allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <hr />
                    <div class="feature-section one-col">
                        <div class="indo-get-started">
                            <h3><?php _e('Let\'s Get Started', 'wp-mylinks'); ?></h3>
                            <ul>
                                <li><strong><?php _e('Step #1:', 'wp-mylinks'); ?></strong> <?php _e('Build your very first micro landing page on <a href="post-new.php?post_type=mylink" target="_blank"><strong>New MyLink</strong></a> page.', 'wp-mylinks'); ?></li>
                                <li><strong><?php _e('Step #2:', 'wp-mylinks'); ?></strong> <?php _e('Setup your profile including avatar, description, and social media links.', 'wp-mylinks'); ?></li>
                                <li><strong><?php _e('Step #3:', 'wp-mylinks'); ?></strong> <?php _e('Add unlimited number of links you want to share to your audience.', 'wp-mylinks'); ?></li>
                                <li><strong><?php _e('Step #4:', 'wp-mylinks'); ?></strong> <?php _e('Choose a theme that matches your personal or business brand.', 'wp-mylinks'); ?></li>
                                <li><strong><?php _e('Step #5:', 'wp-mylinks'); ?></strong> <?php _e('Setup global settings for your micro landing pages on <a href="edit.php?post_type=mylink&page=welcome&tab=global" target="_blank"><strong>Global Configurations</strong></a> setting panel.', 'wp-mylinks'); ?></li>
                                <li><strong><?php _e('Step #6:', 'wp-mylinks'); ?></strong> <?php _e('Add custom styles and scripts to your micro landing pages on <a href="edit.php?post_type=mylink&page=welcome&tab=script" target="_blank"><strong>Custom Script</strong></a> setting panel.', 'wp-mylinks'); ?></li>
                                <li><strong><?php _e('Step #7:', 'wp-mylinks'); ?></strong> <?php _e('<strong>Have an inquiry?</strong> Find out how to reach out to me on <a href="edit.php?post_type=mylink&page=welcome&tab=tutorial_support" target="_blank"><strong>Support</strong></a> panel.', 'wp-mylinks'); ?></li>
                            </ul>
                            <hr />
                            <p>If you encounter 404 page not found issue, please follow the steps below:</p>
                            <ol>
                                <li>Go to <b>Settings</b> => <a href="options-permalink.php"><b>Permalinks</b></a> page.</li>
                                <li>Click the <b>Save Changes</b> button without having to change anything.</li>
                                <li>Recheck your MyLink page. The issue will most likely disappear.</li>
                            </ol>
                            <p>If the problem persists, you might also want to make sure that you are using pretty permalinks (Post name), but be careful! Changing your current permalinks structure to another will affect your entire URLs, and will be very bad for SEO!</p>
                        </div>
                    </div>
                    <hr>
                    <div class="feature-section two-col">
                        <div class="col">
                            <img src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/unlimited.png'; ?>" />
                            <h3><?php _e('Unlimited Landing Pages', 'wp-mylinks'); ?></h3>
                            <p><?php _e('Build unlimited number of micro landing pages that host unlimited number of links.', 'wp-mylinks'); ?></p>
                        </div>
                        <div class="col">
                            <img src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/one-link.png'; ?>" />
                            <h3><?php _e('One Link for Everything', 'wp-mylinks'); ?></h3>
                            <p><?php _e('Every created micro landing page will have one link that you can share anywhere on your networks.', 'wp-mylinks'); ?></p>
                        </div>
                    </div>

                    <div class="feature-section two-col">
                        <div class="col">
                            <img src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/own-brand.png'; ?>" />
                            <h3><?php _e('Use Your Own Brand', 'wp-mylinks'); ?></h3>
                            <p><?php _e('You already have your own brand through a domain name. Use it on your micro landing page and get boosted!', 'wp-mylinks'); ?></p>
                        </div>

                        <div class="col">
                            <img src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/custom-themes.png'; ?>" />
                            <h3><?php _e('15+ Themes to Choose From', 'wp-mylinks'); ?></h3>
                            <p><?php _e('Choose a theme that can represent your brand or taste. Or you can also add custom CSS to use your own.', 'wp-mylinks'); ?></p>
                        </div>
                    </div>

                    <div class="feature-section two-col">
                        <div class="col">
                            <img src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/custom-scripts.png'; ?>" />
                            <h3><?php _e('Custom Scripts & Styles', 'wp-mylinks'); ?></h3>
                            <p><?php _e('Track how every landing page performs easily with Google Analytics, Facebook Pixel etc or customize the look. You have the options.', 'wp-mylinks'); ?></p>
                        </div>

                        <div class="col">
                            <img src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'images/documentation.png'; ?>" />
                            <h3><?php _e('Comprehensive Documentation', 'wp-mylinks'); ?></h3>
                            <p><?php _e('You will not be left alone. My complete documentation or tutorial will always help and support all your needs to get started.', 'wp-mylinks'); ?></p>
                        </div>
                    </div>
                    <br>
                    <?php echo do_shortcode("[donate]"); ?>
                    <center>
                        <p><?php printf(__("Created with ❤️ and ☕ in Jakarta, Indonesia by <a href=\"https://walterpinem.me\" target=\"_blank\"><strong>Walter Pinem</strong></a>", 'wp-mylinks')); ?></p>
                    </center>

                </div>
            </div>
            <br>
    </div>
<?php
        }
    }
