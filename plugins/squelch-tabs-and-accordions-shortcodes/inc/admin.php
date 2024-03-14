<?php

// Security
if (!$squelch_taas_admin) exit;
if (!current_user_can( 'manage_options' )) exit;

$theme = get_option( 'squelch_taas_jquery_ui_theme' );
$vanity_url = get_option( 'squelch_taas_vanity_url' );
if ($vanity_url === false) $vanity_url = 'squelch-taas-';
$vanity_url = trim( $vanity_url );
if ( empty($vanity_url) ) $vanity_url = 'squelch-taas-';

$disable_magic_url = get_option( 'squelch_taas_disable_magic_url' );


/* Save changes
 */
if (!empty($_POST['submit']) && $_POST['submit'] == "Save Changes") {
    $valid = true;

    $new_theme      = $_POST['jquery_ui_theme'];
    $new_vanity_url = $_POST['vanity_url'];
    if ($new_vanity_url === false) $new_vanity_url = 'squelch-taas-';
    $new_vanity_url = trim( $new_vanity_url );
    if ( empty($new_vanity_url) ) $new_vanity_url = 'squelch-taas-';

    $new_disable_magic_url = $_POST['disable_magic_url'] && true;

    ////$custom_css     = $_POST['custom_css_url'];
    ////if (('custom' == $new_theme) && (empty($custom_css))) {
    ////    $GLOBALS['squelch_taas_admin_msg'] .= '<div class="error"><p>Custom CSS URL cannot be empty, please enter a URL or upload a stylesheet.</p></div>';
    ////    $valid = false;
    ////}

    if ($valid) {
        update_option( 'squelch_taas_jquery_ui_theme',   $new_theme             );
        update_option( 'squelch_taas_vanity_url',        $new_vanity_url        );
        update_option( 'squelch_taas_disable_magic_url', $new_disable_magic_url );
        ////update_option( 'squelch_taas_custom_css_url',   $custom_css );

        $msg  = isset($GLOBALS['squelch_taas_admin_msg']) ? $GLOBALS['squelch_taas_admin_msg'] : '';
        $msg .= '<div class="updated"><p>Changes saved.</p></div>';
        $GLOBALS['squelch_taas_admin_msg'] = $msg;

        $theme = $new_theme;
        $vanity_url = $new_vanity_url;
        $disable_magic_url = $new_disable_magic_url;
    }
}


// Detect whether a custom theme has been uploaded or not:

$upload_dir = wp_upload_dir();
$upload_dir = $upload_dir['basedir'];

$custom_theme_dir = trailingslashit( $upload_dir ) . 'jquery-ui-1.9.2.custom';
$custom_theme_detected = false;
if (file_exists( $custom_theme_dir )) $custom_theme_detected = true;

$custom_theme_dir = trailingslashit( $upload_dir ) . 'jquery-ui-1.11.4.custom';
$custom_theme_1_11_4_detected = false;
if (file_exists( $custom_theme_dir )) $custom_theme_1_11_4_detected = true;



global $squelch_taas_admin_msg;
$custom_css = get_option('squelch_taas_custom_css_url');

?>

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('.advanced-option').hide();
    });
</script>
<div class="wrap">
    <div id="icon-options-general" class="icon32"><br /></div>

    <?php echo $squelch_taas_admin_msg; ?>

    <h2>Squelch Tabs And Accordions Shortcodes</h2>
    <p>
        Squelch Tabs and Accordions Shortcodes provides shortcodes for adding stylish Web 2.0 style accordions and tabs to your WordPress website: Horizontal accordions, vertical accordions and tabs.
    </p>
    <a href="http://squelchdesign.com/web-development/free-wordpress-plugins/squelch-tabs-and-accordions-shortcodes/" target="_blank" class="button">Theme Documentation</a>
    <a href="https://wordpress.org/support/topic/please-read-this-before-you-post-5?replies=2" target="_blank" class="button">Support Forum</a>
    <a href="https://wordpress.org/plugins/squelch-tabs-and-accordions-shortcodes/" target="_blank" class="button">Rate on WordPress.org</a>
    <a href="http://squelchdesign.com/uncategorized/roll-theme-squelch-tabs-accordions-shortcodes-plugin/" target="_blank" class="button">How to create a custom theme</a>
    <form method="post" action="">
        <div>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row">
                            <label for="jquery_ui_theme">
                                jQuery UI theme
                            </label>
                        </th>
                        <td valign="top">
                            <select id="jquery_ui_theme" name="jquery_ui_theme">
                                <option<?php selected($theme, 'none'); ?> value="none">No jQuery UI theme</option>
                                <!-- option <?php /*selected($theme, 'custom');*/ ?> value="custom">Use your own custom CSS</option -->
                                <option<?php selected($theme, 'base'); ?> value="base">jQuery Base Styles Only</option>
                                <?php if ($custom_theme_detected) : ?>
                                    <option<?php selected($theme, 'custom'); ?> value="custom">Custom jQuery theme (jQuery 1.9.2)</option>
                                <?php endif; ?>
                                <?php if ($custom_theme_1_11_4_detected) : ?>
                                    <option<?php selected($theme, 'custom1114'); ?> value="custom1114">Custom jQuery theme (jQuery 1.11.4)</option>
                                <?php endif; ?>
                                <option<?php selected($theme, 'black-tie'); ?> value="black-tie">Black Tie</option>
                                <option<?php selected($theme, 'blitzer'); ?> value="blitzer">Blitzer</option>
                                <option<?php selected($theme, 'cupertino'); ?> value="cupertino">Cupertino</option>
                                <option<?php selected($theme, 'dark-hive'); ?> value="dark-hive">Dark Hive</option>
                                <option<?php selected($theme, 'dot-luv'); ?> value="dot-luv">Dot Luv</option>
                                <option<?php selected($theme, 'eggplant'); ?> value="eggplant">Eggplant</option>
                                <option<?php selected($theme, 'excite-bike'); ?> value="excite-bike">Excite Bike</option>
                                <option<?php selected($theme, 'flick'); ?> value="flick">Flick</option>
                                <option<?php selected($theme, 'hot-sneaks'); ?> value="hot-sneaks">Hot Sneaks</option>
                                <option<?php selected($theme, 'humanity'); ?> value="humanity">Humanity</option>
                                <option<?php selected($theme, 'le-frog'); ?> value="le-frog">Le Frog</option>
                                <option<?php selected($theme, 'mint-choc'); ?> value="mint-choc">Mint Choc</option>
                                <option<?php selected($theme, 'overcast'); ?> value="overcast">Overcast</option>
                                <option<?php selected($theme, 'pepper-grinder'); ?> value="pepper-grinder">Pepper Grinder</option>
                                <option<?php selected($theme, 'redmond'); ?> value="redmond">Redmond</option>
                                <option<?php selected($theme, 'smoothness'); ?> value="smoothness">Smoothness</option>
                                <option<?php selected($theme, 'south-street'); ?> value="south-street">South Street</option>
                                <option<?php selected($theme, 'start'); ?> value="start">Start</option>
                                <option<?php selected($theme, 'sunny'); ?> value="sunny">Sunny</option>
                                <option<?php selected($theme, 'swanky-purse'); ?> value="swanky-purse">Swanky Purse</option>
                                <option<?php selected($theme, 'trontastic'); ?> value="trontastic">Trontastic</option>
                                <option<?php selected($theme, 'ui-darkness'); ?> value="ui-darkness">Darkness</option>
                                <option<?php selected($theme, 'ui-lightness'); ?> value="ui-lightness">Lightness</option>
                                <option<?php selected($theme, 'vader'); ?> value="vader">Vader</option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="show_advanced_options">
                                Show advanced options
                            </label>
                        </th>
                        <td valign="top">
                            <input type="button" name="show_advanced_options" id="show_advanced_options" onclick="jQuery('.advanced-option').toggle('fast');" value="Show advanced options" />
                        </td>
                    </tr>
                    <tr valign="top" class="advanced-option">
                        <th scope="row">
                            <label for="vanity_url">
                                Disable magic URLs
                            </label>
                        </th>
                        <td valign="top">
                            <input type="checkbox" name="disable_magic_url" id="disable_magic_url"<?php checked( $disable_magic_url ); ?> />
                            <br />
                            <em>When clicking a widget generated by this plugin the URL will be appended with something like #<?php echo esc_html( $vanity_url); ?>tab-content-0-1 ... We call these "Magic URLs". You can disable this feature by ticking the box above.</em>
                            <?php if ($disable_magic_url) : ?>
                                <p>
                                    Magic URLs are currently <strong>disabled</strong> (untick the box above if you wish to enable them). Clicking a tab or an accordion will NOT modify the page's URL.
                                </p>
                            <?php else : ?>
                                <p>
                                    Magic URLs are currently <strong>enabled</strong> (tick the box above if you wish to disable them). Clicking a tab or an accordion will modify the page's URL.
                                </p>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr valign="top" class="advanced-option">
                        <th scope="row">
                            <label for="vanity_url">
                                Vanity URL prefix
                            </label>
                        </th>
                        <td valign="top">
                            <input type="text" name="vanity_url" id="vanity_url" value="<?php echo esc_attr( $vanity_url ); ?>" />
                            <br />
                            <em>Magic URLs generated by the widgets look like: #<?php echo esc_html( $vanity_url); ?>tab-content-0-1 ... You can change the first part of that by entering a value here. If you choose to change this value then any existing links to specific tabs/accordions may stop working. If you do not understand what this setting does then leave it as default. To restore the default value leave this box empty and save changes.</em>
                            <p>
                                Links to tabs will look something like: <?php echo get_option( 'siteurl' ); ?>/#<?php echo esc_html( $vanity_url ); ?>tab-content-0-1
                            </p>
                        </td>
                    </tr>
                    <!--tr valign="top" style="display: none;" id="custom-css-row">
                        <th scope="row">
                            <label for="upload_css">
                                Use your own custom CSS
                            </label>
                        </th>
                        <td>
                            <label for="upload_css">
                                <input id="upload_css" type="text" size="36" name="custom_css_url" value="<?php /*echo $custom_css;*/ ?>" />
                                <input id="upload_css_button" type="button" value="Upload CSS" />
                                <br />Upload your own custom CSS as built by the <a href="http://jqueryui.com/themeroller/" target="_blank">jQuery UI Theme Roller</a>, or enter the URL of your custom CSS in the box above.
                        </label></td>
                    </tr -->
                </tbody>
            </table>
            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes" />
            </p>
        </div>
    </form>
</div>
