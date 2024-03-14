<?php
if( !function_exists('acwp_dashboard_widgets_array') ){
    function acwp_dashboard_widgets_array(){
        $status = array(
            'accessible-poetry' => is_plugin_active( 'accessible-poetry/accessible-wp-toolbar.php' ),
            'accessiblewp-images' => is_plugin_active( 'accessiblewp-images/accessiblewp-images.php' ),
            'accessiblewp-skiplinks' => is_plugin_active( 'accessiblewp-skiplinks/accessiblewp-skiplinks.php' ),
        );
        
        return array(
            'accessible-poetry' => array(
                'name' => 'Accessibility Toolbar',
                'desc' => 'Add an accessibility toolbar to your WordPress site to provides professional features that make it easier for users with disabilities and help you adhere to WCAG accessibility guidelines.',
                'status' => $status['accessible-poetry'],
                'link' => 'https://wordpress.org/plugins/accessible-poetry/',
            ),
            'accessiblewp-images' => array(
                'name' => 'ALT Detector',
                'desc' => 'An effective solution for accessible images on WordPress sites, allows you to find easily images that have no alternative text and provides additional accessibility settings for images.',
                'status' => $status['accessiblewp-images'],
                'link' => 'https://wordpress.org/plugins/accessiblewp-images/',
            ),
            'accessiblewp-skiplinks' => array(
                'name' => 'Skip-Links',
                'desc' => 'Allow the users of your WordPress site to navigate between primary page sections in an accessible and with compliance to WCAG as required from at all levels.',
                'status' => $status['accessiblewp-skiplinks'],
                'link' => 'https://wordpress.org/plugins/accessiblewp-skiplinks/',
            ),
        );
    }
}

///
// register the ajax action for authenticated users
add_action('wp_ajax_acwp_toolbar_connect_callback', 'acwp_toolbar_connect_callback');

// register the ajax action for unauthenticated users
add_action('wp_ajax_nopriv_acwp_toolbar_connect_callback', 'acwp_toolbar_connect_callback');

// handle the ajax request
function acwp_toolbar_connect_callback() {
    $set_active = $_REQUEST['res']['set_active'];

    if( $set_active == 'true' ){
        update_option('acwp_toolbar_api_status', 'yes');
    } else {
        update_option('acwp_toolbar_api_status', 'no');
    }
    // add your logic here...

    // in the end, returns success json data
    wp_send_json_success(array(
        'req' => $_REQUEST['res'],
        'indeed' => 'tat'
    ));

    // or, on error, return error json data
    wp_send_json_error([/* some data here */]);
}

/**
 * Class ACWP_AdminPanel
 *
 * Register our admin pages and our settings for the toolbar
 *
 * @since 4.0.0
 */
class ACWP_AdminPanel {

    public function __construct() {

        // Register admin pages
        add_action( 'admin_menu', array(&$this, 'register_pages') );

        // Register settings
        add_action( 'admin_init', array(&$this, 'register_settings') );
    }

    public function register_pages() {

        // Check if we already got the primary page of AccessibleWP
        // if not we will add it ...
        if ( empty ($GLOBALS['admin_page_hooks']['accessible-wp'] ) ) {
            add_menu_page(
                __('AccessibleWP', 'acwp'),
                'AccessibleWP', 'read', 
                'accessible-wp', 
                array($this, 'main_page_callback'), 
                'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB3aWR0aD0iMjJweCIgaGVpZ2h0PSIyNHB4IiB2aWV3Qm94PSIwIDAgMjIgMjQiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+CiAgICA8IS0tIEdlbmVyYXRvcjogU2tldGNoIDUyLjUgKDY3NDY5KSAtIGh0dHA6Ly93d3cuYm9oZW1pYW5jb2RpbmcuY29tL3NrZXRjaCAtLT4KICAgIDx0aXRsZT5TaGFwZTwvdGl0bGU+CiAgICA8ZGVzYz5DcmVhdGVkIHdpdGggU2tldGNoLjwvZGVzYz4KICAgIDxnIGlkPSJQYWdlLTEiIHN0cm9rZT0ibm9uZSIgc3Ryb2tlLXdpZHRoPSIxIiBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPgogICAgICAgIDxnIGlkPSJBcnRib2FyZC1Db3B5LTUiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC00My4wMDAwMDAsIC0zOS4wMDAwMDApIiBmaWxsPSIjOUJBMkE2IiBmaWxsLXJ1bGU9Im5vbnplcm8iPgogICAgICAgICAgICA8cGF0aCBkPSJNNTMuOTA5MDkwOSwzOSBDNTUuMjgwOTkxNywzOSA1Ni4zODg0Mjk4LDQwLjEwNTMyNzMgNTYuMzg4NDI5OCw0MS40NzQ2MTMzIEM1Ni4zODg0Mjk4LDQyLjg0Mzg5OTMgNTUuMjgwOTkxNyw0My45NjU3MjQgNTMuOTA5MDkwOSw0My45NjU3MjQgQzUyLjU1MzcxOSw0My45NjU3MjQgNTEuNDI5NzUyMSw0Mi44NDM4OTkzIDUxLjQyOTc1MjEsNDEuNDc0NjEzMyBDNTEuNDI5NzUyMSw0MC4xMDUzMjczIDUyLjU1MzcxOSwzOSA1My45MDkwOTA5LDM5IFogTTQzLDQ2Ljg1Mjc3MjggQzQzLDQ1Ljc4MDQ0MDQgNTMuOTA5MDkwOSw0NS41OTg5Njg3IDUzLjkwOTA5MDksNDUuNTk4OTY4NyBDNTMuOTA5MDkwOSw0NS41OTg5Njg3IDY0LjgxODE4MTgsNDUuNzgwNDQwNCA2NC44MTgxODE4LDQ2Ljg1Mjc3MjggQzY0LjgxODE4MTgsNDcuOTI1MTA1MiA1Ny40Mjk3NTIxLDQ5LjE2MjQxMTggNTcuNDI5NzUyMSw0OS4xNjI0MTE4IEM1Ny40Mjk3NTIxLDQ5LjE2MjQxMTggNjAuMjIzMTQwNSw2Mi41OTEzMTMyIDU5LjE4MTgxODIsNjIuOTg3MjUxMyBDNTguMTU3MDI0OCw2My4zODMxODk0IDUzLjkwOTA5MDksNTQuNDI1MDg5NCA1My45MDkwOTA5LDU0LjQyNTA4OTQgQzUzLjkwOTA5MDksNTQuNDI1MDg5NCA0OS42Nzc2ODYsNjMuMzgzMTg5NCA0OC42NTI4OTI2LDYyLjk4NzI1MTMgQzQ3LjYxMTU3MDIsNjIuNTkxMzEzMiA1MC40MDQ5NTg3LDQ5LjE2MjQxMTggNTAuNDA0OTU4Nyw0OS4xNjI0MTE4IEM1MC40MDQ5NTg3LDQ5LjE2MjQxMTggNDMsNDcuOTI1MTA1MiA0Myw0Ni44NTI3NzI4IFoiIGlkPSJTaGFwZSI+PC9wYXRoPgogICAgICAgIDwvZz4KICAgIDwvZz4KPC9zdmc+', '2.1'
            );
        }

        // Add our sub page for the Toolbar
        add_submenu_page('accessible-wp', 'AccessibleWP Toolbar', 'Toolbar', 'manage_options', 'accessiblewp-toolbar', array(&$this, 'submenu_page_callback'));
    }

    public function register_settings(){

        register_setting('acwp', 'acwp_heading_title',      array('show_in_rest' => true));
        register_setting('acwp', 'acwp_toolbar_token',      array('show_in_rest' => true));
        register_setting('acwp', 'acwp_toolbar_tokenemail',      array('show_in_rest' => true));
        register_setting('acwp', 'acwp_fontsize_customexcludetags',      array('show_in_rest' => true));
        register_setting('acwp', 'acwp_fontsize_excludetags',      array('show_in_rest' => true));
        register_setting('acwp', 'awp_nocookies',      array('show_in_rest' => true));
        
        register_setting('acwp', 'acwp_hide_icons',      array('show_in_rest' => true));
        register_setting('acwp', 'acwp_no_btn_drage',      array('show_in_rest' => true));
        register_setting('acwp', 'acwp_custom_color',      array('show_in_rest' => true));
        register_setting('acwp', 'acwp_custom_color_allow',      array('show_in_rest' => true));
        
        register_setting('acwp', 'acwp_toolbar_style',      array('show_in_rest' => true));
        register_setting('acwp', 'acwp_no_toolbar_animation',    array('show_in_rest' => true));
        
        register_setting('acwp', 'acwp_toolbar_side',       array('show_in_rest' => true));
        register_setting('acwp', 'acwp_toolbar_stickness',       array('show_in_rest' => true));
        register_setting('acwp', 'acwp_toggle_fromtop',     array('show_in_rest' => true));
        register_setting('acwp', 'acwp_toggle_fromside',    array('show_in_rest' => true));

        // Keyboard Navigation
        register_setting('acwp', 'acwp_keyboard_noarrows',  array('show_in_rest' => true));
        register_setting('acwp', 'acwp_hide_keyboard',      array('show_in_rest' => true));

        // Contrast
        register_setting('acwp', 'acwp_contrast_custom',    array('show_in_rest' => true));
        register_setting('acwp', 'acwp_contrast_bgs',       array('show_in_rest' => true));
        register_setting('acwp', 'acwp_contrast_txt',       array('show_in_rest' => true));
        register_setting('acwp', 'acwp_contrast_links',     array('show_in_rest' => true));
        register_setting('acwp', 'acwp_hide_contrast',      array('show_in_rest' => true));
        register_setting('acwp', 'acwp_contrast_mode',      array('show_in_rest' => true));
        register_setting('acwp', 'acwp_contrast_exclude',      array('show_in_rest' => true));

        // Font Size
        register_setting('acwp', 'acwp_fontsize_nolineheight',  array('show_in_rest' => true));
        register_setting('acwp', 'acwp_incfont_size',           array('show_in_rest' => true));
        register_setting('acwp', 'acwp_decfont_size',           array('show_in_rest' => true));
        register_setting('acwp', 'acwp_hide_fontsize',          array('show_in_rest' => true));
        register_setting('acwp', 'acwp_fontsize_customtags',    array('show_in_rest' => true));
        register_setting('acwp', 'acwp_fontsize_tags',          array('show_in_rest' => true));
        register_setting('acwp', 'acwp_toolbar_fromside',          array('show_in_rest' => true));
        register_setting('acwp', 'acwp_toolbar_fromtop',          array('show_in_rest' => true));

        // Disable Animations
        register_setting('acwp', 'acwp_animations_hard',    array('show_in_rest' => true));
        register_setting('acwp', 'acwp_hide_animations',    array('show_in_rest' => true));

        // Readable font
        register_setting('acwp', 'acwp_readable_font',      array('show_in_rest' => true));
        register_setting('acwp', 'acwp_readable_custom',    array('show_in_rest' => true));
        register_setting('acwp', 'acwp_hide_readable',      array('show_in_rest' => true));
        register_setting('acwp', 'acwp_readable_mode',      array('show_in_rest' => true));

        // Mark Titles
        register_setting('acwp', 'acwp_titles_bg',              array('show_in_rest' => true));
        register_setting('acwp', 'acwp_titles_txt',             array('show_in_rest' => true));
        register_setting('acwp', 'acwp_hide_titles',            array('show_in_rest' => true));
        register_setting('acwp', 'acwp_titles_customcolors',    array('show_in_rest' => true));
        register_setting('acwp', 'acwp_titles_mode',            array('show_in_rest' => true));

        // Underline links
        register_setting('acwp', 'acwp_underline_mode',  array('show_in_rest' => true));
        register_setting('acwp', 'acwp_hide_underline',     array('show_in_rest' => true));

        // Mobile Visibility
        register_setting('acwp', 'acwp_mobile',     array('show_in_rest' => true));

        // additional links
        register_setting('acwp', 'acwp_statement',  array('show_in_rest' => true));
        register_setting('acwp', 'acwp_feedback',   array('show_in_rest' => true));
        register_setting('acwp', 'acwp_sitemap',   array('show_in_rest' => true));
        
        register_setting('acwp', 'acwp_statement_label',  array('show_in_rest' => true));
        register_setting('acwp', 'acwp_feedback_label',   array('show_in_rest' => true));
        register_setting('acwp', 'acwp_sitemap_label',   array('show_in_rest' => true));
    }

    public function main_page_callback() {
        $widgets = acwp_dashboard_widgets_array();
        ?>
        <div class="wrap">
            <div id="welcome-panel" class="welcome-panel welcome-panel-accessiblewp">
                <div class="welcome-panel-header">
                    <div class="welcome-content">
                        <h1><?php _e('Welcome to <span>AccessibleWP</span> Dashboard!', 'acwp');?></h1>
                        <p class="about-description"><?php _e('Accessibility solutions for websites based on the WordPress.', 'acwp');?></p>
                        <nav id="acwp-welcome-nav">
                            <a href="https://www.codenroll.co.il/" target="_blank"><?php _e('Who we are?', 'acwp'); ?></a>
                            <a href="https://www.amitmoreno.com/" target="_blank"><?php _e('About the author', 'acwp'); ?></a> 
                            <a href="https://www.w3.org/WAI/standards-guidelines/" target="_blank"><?php _e('W3C accessibility standards overview', 'acwp'); ?></a>
                            <a href="https://www.codenroll.co.il/contact" target="_blank"><?php _e('Send feedback', 'acwp'); ?></a>
                        </nav>
                    </div>
                    <div id="acwp-welcome-communities">
                        <h2><?php _e('<span>Why struggle alone with accessibility challenges?</span> Join our communities.', 'acwp');?></h2>
                        <p><?php _e('Weֿֿֿ\'ve created communities that allow you to learn, help, and ask questions about our plugins and web accessibility in general.', 'acwp'); ?></p>
                        <nav>
                            <a href="https://www.facebook.com/groups/457560742846331" target="_blank" aria-label="Join AccessibleWP Facebook group"><?php _e('Facebook Group', 'acwp'); ?></a>
                            <a href="https://chat.whatsapp.com/BD8bMLfUGyt0aVzsaKEnB1" target="_blank" aria-label="Join AccessibleWP Whatsapp group"><?php _e('Whatsapp Group', 'acwp'); ?></a>
                            <a href="https://t.me/AccessibleWP" target="_blank" aria-label="Join AccessibleWP Telegram Channel"><?php _e('Telegram Channel', 'acwp'); ?></a>
                        </nav>
                    </div>
                </div>
            </div>
            <?php if( !empty($widgets) ) : ?>
            <h2>Our Plugins</h2>
            <div id="acwp-dashboard-widgets">
                
                <?php foreach($widgets as $widget) : ?>
                <div class="acwp-dashboard-widget">
                    <h3><?php echo $widget['name'];?></h3>
                    <p><?php echo $widget['desc'];?></p>
                    <nav>
                        <p class="status">
                            <span class="acwp-indicator <?php 
                                $class = $widget['status'] ? 'active' : '';
                                echo $class;
                            ?>"></span>
                            <span class="txt"><?php
                            $txt = $widget['status'] ? __('Active', 'acwp') : __('Not active', 'acwp');
                            echo $txt;
                            ?></span>
                        </p>
                        
                        <?php if( !$widget['status'] ) : ?>
                        <div class="btn-wrap"><a href="<?php echo $widget['link'];?>" target="_blank" aria-label="<?php _e('Download the plugin', 'acwp');?> <?php echo $widget['name'];?>, <?php _e('This link will be open in a new tab', 'acwp');?>">Download</a></div>
                        <?php endif; ?>
                    </nav>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php
    }

    public function submenu_tab_heading() {
        ?>
        <div id="acwp_heading" class="acwp-tab active">
            <h2><?php _e('Toolbar Heading', 'acwp');?></h2>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_heading_title"><?php _e('Toolbar title', 'acwp');?></label>
                    </th>
                    <td>
                        <input type="text" id="acwp_heading_title" name="acwp_heading_title" value="<?php echo esc_attr( get_option('acwp_heading_title') ); ?>" placeholder="<?php _e('Accessibility Toolbar', 'acwp');?>" />
                        <p><?php _e('Change the default toolbar title. Please note that if you change the title you will not be able to translate it into other languages', 'acwp');?></p>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }
    public function submenu_tab_options() {
        ?>
        <div id="acwp_options" class="acwp-tab">
            <h2><?php _e('Options', 'acwp');?></h2>

            <table class="form-table">
                <tr class="acwp-tr-heading">
                    <th>
                        <h4><?php _e('Keyboard Navigation', 'acwp');?></h4>
                    </th>
                    <td>
                        <ul class="acwp-list">
                            <li><?php _e('Add effect to components in focus mode.', 'acwp');?></li>
                            <li><?php _e('Allow navigation between components using keyboard arrows & TAB.', 'acwp');?></li>
                        </ul>
                    </td>
                </tr>
                
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_keyboard_noarrows"><?php _e("Disable arrows navigation", 'acwp');?></label>
                    </th>
                    <td><input type="checkbox" id="acwp_keyboard_noarrows" name="acwp_keyboard_noarrows" value="yes" <?php checked( esc_attr( get_option('acwp_keyboard_noarrows') ), 'yes' ); ?> /></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr />
                    </td>
                </tr>
                <tr valign="top" class="acwp-tr-last">
                    <th scope="row">
                        <label for="acwp_hide_keyboard"><?php _e('Hide option', 'acwp');?></label>
                    </th>
                    <td><input type="checkbox" id="acwp_hide_keyboard" name="acwp_hide_keyboard" value="yes" <?php checked( esc_attr( get_option('acwp_hide_keyboard') ), 'yes' ); ?> /></td>
                </tr>

                <tr class="acwp-tr-heading">
                    <th>
                        <h4><?php _e('Disable Animations', 'acwp');?></h4>
                    </th>
                    <td>
                        <ul class="acwp-list">
                            <li><?php _e('Disables CSS3 animations.', 'acwp');?></li>
                        </ul>
                    </td>
                </tr>
                
                <tr valign="top" class="acwp-tr-last">
                    <th scope="row">
                        <label for="acwp_hide_animations"><?php _e('Hide option', 'acwp');?></label>
                    </th>
                    <td><input type="checkbox" id="acwp_hide_animations" name="acwp_hide_animations" value="yes" <?php checked( esc_attr( get_option('acwp_hide_animations') ), 'yes' ); ?> /></td>
                </tr>

                <tr class="acwp-tr-heading">
                    <th>
                        <h4><?php _e('High Contrast', 'acwp');?></h4>
                    </th>
                    <td>
                        <ul class="acwp-list">
                            <li><?php _e('Changes the colors of backgrounds, texts and links.', 'acwp');?></li>
                            <li><?php _e('Disables background images.', 'acwp');?></li>
                        </ul>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_contrast_custom"><?php _e('Use custom colors', 'acwp');?></label>
                    </th>
                    <td><input type="checkbox" name="acwp_contrast_custom" id="acwp_contrast_custom" value="yes" <?php checked( esc_attr( get_option('acwp_contrast_custom') ), 'yes' ); ?> /></td>
                </tr>
                <tr valign="top" id="acwp-contrast-bgcolor-row" class="acwp-hide-row">
                    <th scope="row">
                        <label for="acwp_contrast_bgs"><?php _e('Backgrounds color', 'acwp');?></label>
                    </th>
                    <td><input type="color" id="acwp_contrast_bgs" name="acwp_contrast_bgs" class="color-field" value="<?php echo esc_attr( get_option('acwp_contrast_bgs') ); ?>" data-default-color="#000000" /></td>
                </tr>
                <tr valign="top" id="acwp-contrast-txtcolor-row" class="acwp-hide-row">
                    <th scope="row">
                        <label for="acwp_contrast_txt"><?php _e('Text color', 'acwp');?></label>
                    </th>
                    <td><input type="color" id="acwp_contrast_txt" name="acwp_contrast_txt" class="color-field" value="<?php echo esc_attr( get_option('acwp_contrast_txt') ); ?>" data-default-color="#ffffff" /></td>
                </tr>
                <tr valign="top" id="acwp-contrast-linkscolor-row" class="acwp-hide-row">
                    <th scope="row">
                        <label for="acwp_contrast_links"><?php _e('Links color', 'acwp');?></label>
                    </th>
                    <td><input type="color" id="acwp_contrast_links" name="acwp_contrast_links" class="color-field" value="<?php echo esc_attr( get_option('acwp_contrast_links') ); ?>" data-default-color="#ffff00" /></td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="acwp_contrast_mode"><?php _e('Mode', 'acwp');?></label>
                    </th>
                    <td>
                        <select name="acwp_contrast_mode" id="acwp_contrast_mode">
                            <option value="">-- <?php _e('Normal CSS (default)', 'acwp');?> --</option>
                            <option value="hard-css" <?php selected('hard-css', get_option('acwp_contrast_mode'))?>><?php _e('Hard CSS', 'acwp');?></option>
                            <option value="js" <?php selected('js', get_option('acwp_contrast_mode'))?>><?php _e('JS', 'acwp');?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top" id="acwp-row-contrast-exclude" class="acwp-hide-row">
                    <th scope="row">
                        <label for="acwp_contrast_exclude"><?php _e('Exclude items', 'acwp');?></label>
                    </th>
                    <td>
                        <input type="text" name="acwp_contrast_exclude" id="acwp_contrast_exclude" value="<?php echo esc_attr( get_option('acwp_contrast_exclude') ); ?>" />
                        <p><?php _e('Exclude items by setting the item tag name, class or id. Separate items with comma please, For example:', 'acwp');?></p>
                        <p><code>h3, #my-section, .card</code></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr />
                    </td>
                </tr>
                <tr valign="top" class="acwp-tr-last">
                    <th scope="row">
                        <label for="acwp_hide_contrast"><?php _e('Hide option', 'acwp');?></label>
                    </th>
                    <td><input type="checkbox" name="acwp_hide_contrast" id="acwp_hide_contrast" value="yes" <?php checked( esc_attr( get_option('acwp_hide_contrast') ), 'yes' ); ?> /></td>
                </tr>

                <tr class="acwp-tr-heading">
                    <th>
                        <h4><?php _e('Font Size', 'acwp');?></h4>
                    </th>
                    <td>
                        <ul class="acwp-list">
                            <li><?php _e('Allow users to increase or decrease font sizes.', 'acwp');?></li>
                        </ul>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_incfont_size"><?php _e('By how many percent to <b>increase</b> the font size?', 'acwp');?></label>
                    </th>
                    <td><input type="number" name="acwp_incfont_size" id="acwp_incfont_size" value="<?php echo esc_attr( get_option('acwp_incfont_size') ); ?>" placeholder="160" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_decfont_size"><?php _e('By how many percent to <b>decrease</b> the font size?', 'acwp');?></label>
                    </th>
                    <td><input type="number" name="acwp_decfont_size" id="acwp_decfont_size" value="<?php echo esc_attr( get_option('acwp_decfont_size') ); ?>" placeholder="80" /></td>
                </tr>
                
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_fontsize_customtags"><?php _e('Use custom tags?', 'acwp');?></label>
                    </th>
                    <td><input type="checkbox" id="acwp_fontsize_customtags" name="acwp_fontsize_customtags" value="yes" <?php checked( esc_attr( get_option('acwp_fontsize_customtags') ), 'yes' ); ?> /></td>
                </tr>
                <tr valign="top" class="acwp-hide-row" id="acwp-fontsize-tags-row">
                    <th scope="row">
                        <label for="acwp_fontsize_tags"><?php _e('Seperate tags with comma', 'acwp');?></label>
                    </th>
                    <td><input type="text" name="acwp_fontsize_tags" id="acwp_fontsize_tags" value="<?php echo esc_attr( get_option('acwp_fontsize_tags') ); ?>" placeholder="p,h1,h2,h3,h4,h5,h6,label" /></td>
                </tr>
                
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_fontsize_customexcludetags"><?php _e('Exclude tags?', 'acwp');?></label>
                    </th>
                    <td><input type="checkbox" id="acwp_fontsize_customexcludetags" name="acwp_fontsize_customexcludetags" value="yes" <?php checked( esc_attr( get_option('acwp_fontsize_customexcludetags') ), 'yes' ); ?> /></td>
                </tr>
                <tr valign="top" class="acwp-hide-row" id="acwp-fontsize-excludetags-row">
                    <th scope="row">
                        <label for="acwp_fontsize_excludetags"><?php _e('Seperate tags with comma', 'acwp');?></label>
                    </th>
                    <td><input type="text" name="acwp_fontsize_excludetags" id="acwp_fontsize_excludetags" value="<?php echo esc_attr( get_option('acwp_fontsize_excludetags') ); ?>" placeholder="p,h1,h2,h3,h4,h5,h6,label" /></td>
                </tr>
                
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_fontsize_nolineheight"><?php _e('Disable line-height reset when font size is changing?', 'acwp');?></label>
                    </th>
                    <td><input type="checkbox" name="acwp_fontsize_nolineheight" id="acwp_fontsize_nolineheight" value="yes" <?php checked( esc_attr( get_option('acwp_fontsize_nolineheight') ), 'yes' ); ?> /></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr />
                    </td>
                </tr>
                <tr valign="top" class="acwp-tr-last">
                    <th scope="row">
                        <label for="acwp_hide_fontsize"><?php _e('Hide option', 'acwp');?></label>
                    </th>
                    <td><input type="checkbox" name="acwp_hide_fontsize" id="acwp_hide_fontsize" value="yes" <?php checked( esc_attr( get_option('acwp_hide_fontsize') ), 'yes' ); ?> /></td>
                </tr>

                <tr class="acwp-tr-heading">
                    <th>
                        <h4><?php _e('Readable Font', 'acwp');?></h4>
                    </th>
                    <td>
                        <ul class="acwp-list">
                            <li><?php _e('Change the font family of all text to readable font.', 'acwp');?></li>
                        </ul>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_readable_mode"><?php _e('Mode', 'acwp');?></label>
                    </th>
                    <td>
                        <select name="acwp_readable_mode" id="acwp_readable_mode">
                            <option value="">-- <?php _e('Normal CSS (default)', 'acwp');?> --</option>
                            <option value="hard-css" <?php selected('hard-css', get_option('acwp_readable_mode'))?>><?php _e('Hard CSS', 'acwp');?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_readable_font"><?php _e('Choose your readable font:', 'acwp');?></label>
                    </th>
                    <td>
                        <select name="acwp_readable_font" id="acwp_readable_font">
                            <option value="">-- <?php _e('Default (Arial)', 'acwp');?> --</option>
                            <option value="Tahoma" <?php selected('Tahoma', get_option('acwp_readable_font'))?>><?php _e('Tahoma', 'acwp');?></option>
                            <option value="custom" <?php selected('custom', get_option('acwp_readable_font'))?>><?php _e('Custom Family', 'acwp');?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top" id="acwp-row-readable-custom" class="acwp-hide-row">
                    <th scope="row">
                        <label for="acwp_readable_custom"><?php _e('Custom Font Family', 'acwp');?></label>
                    </th>
                    <td><input type="text" name="acwp_readable_custom" id="acwp_readable_custom" value="<?php echo esc_attr( get_option('acwp_readable_custom') ); ?>" /></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr />
                    </td>
                </tr>
                <tr valign="top" class="acwp-tr-last">
                    <th scope="row">
                        <label for="acwp_hide_readable"><?php _e('Hide option', 'acwp');?></label>
                    </th>
                    <td><input type="checkbox" name="acwp_hide_readable" id="acwp_hide_readable" value="yes" <?php checked( esc_attr( get_option('acwp_hide_readable') ), 'yes' ); ?> /></td>
                </tr>

                <tr class="acwp-tr-heading">
                    <th>
                        <h4><?php _e('Mark Titles', 'acwp');?></h4>
                    </th>
                    <td>
                        <ul class="acwp-list">
                            <li><?php _e('Add custom background color and text color to heading components.', 'acwp');?></li>
                        </ul>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_titles_mode"><?php _e('Mode', 'acwp');?></label>
                    </th>
                    <td>
                        <select name="acwp_titles_mode" id="acwp_titles_mode">
                            <option value="">-- <?php _e('Normal CSS (default)', 'acwp');?> --</option>
                            <option value="hard-css" <?php selected('hard-css', get_option('acwp_titles_mode'))?>><?php _e('Hard CSS', 'acwp');?></option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_titles_customcolors"><?php _e('Use custom colors?', 'acwp');?></label>
                    </th>
                    <td><input type="checkbox" name="acwp_titles_customcolors" id="acwp_titles_customcolors" value="yes" <?php checked( esc_attr( get_option('acwp_titles_customcolors') ), 'yes' ); ?> /></td>
                </tr>
                <tr valign="top" class="acwp-hide-row" id="acwp-titles-bg-row">
                    <th scope="row">
                        <label for="acwp_titles_bg"><?php _e('Titles Background Color', 'acwp');?></label>
                    </th>
                    <td><input type="color" name="acwp_titles_bg" id="acwp_titles_bg" class="color-field" value="<?php echo esc_attr( get_option('acwp_titles_bg') ); ?>" data-default-color="#ffff00" /></td>
                </tr>
                <tr valign="top" class="acwp-hide-row" id="acwp-titles-txt-row">
                    <th scope="row">
                        <label for="acwp_titles_txt"><?php _e('Titles Text Color', 'acwp');?></label>
                    </th>
                    <td><input type="color" name="acwp_titles_txt" id="acwp_titles_txt" class="color-field" value="<?php echo esc_attr( get_option('acwp_titles_txt') ); ?>" data-default-color="#000000" /></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr />
                    </td>
                </tr>
                <tr valign="top" class="acwp-tr-last">
                    <th scope="row">
                        <label for="acwp_hide_titles"><?php _e('Hide option', 'acwp');?></label>
                    </th>
                    <td><input type="checkbox" name="acwp_hide_titles" id="acwp_hide_titles" value="yes" <?php checked( esc_attr( get_option('acwp_hide_titles') ), 'yes' ); ?> /></td>
                </tr>

                <tr class="acwp-tr-heading">
                    <th>
                        <h4><?php _e('Underline Links', 'acwp');?></h4>
                    </th>
                    <td>
                        <ul class="acwp-list">
                            <li><?php _e('Add line under all link components.', 'acwp');?></li>
                        </ul>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_underline_mode"><?php _e('Mode', 'acwp');?></label>
                    </th>
                    <td>
                        <select name="acwp_underline_mode" id="acwp_underline_mode">
                            <option value="">-- <?php _e('Normal CSS (default)', 'acwp');?> --</option>
                            <option value="hard-css" <?php selected('hard-css', get_option('acwp_underline_mode'))?>><?php _e('Hard CSS', 'acwp');?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr />
                    </td>
                </tr>
                <tr valign="top" class="acwp-tr-last">
                    <th scope="row">
                        <label for="acwp_hide_underline"><?php _e('Hide option', 'acwp');?></label>
                    </th>
                    <td><input type="checkbox" name="acwp_hide_underline" id="acwp_hide_underline" value="yes" <?php checked( esc_attr( get_option('acwp_hide_underline') ), 'yes' ); ?> /></td>
                </tr>
            </table>
        </div>
        <?php
    }
    public function submenu_tab_settings(){
        ?>
        <div id="acwp_settings" class="acwp-tab">
            <h2><?php _e('Settings', 'acwp');?></h2>
            
            

            <table class="form-table">
                
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_hide_icons"><?php _e("Hide the icons of the accessibility options", 'acwp');?></label>
                    </th>
                    <td><input type="checkbox" name="acwp_hide_icons" id="acwp_hide_icons" value="yes" <?php checked( esc_attr( get_option('acwp_hide_icons') ), 'yes' ); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_no_btn_drage"><?php _e("Disable button dragging", 'acwp');?></label>
                    </th>
                    <td><input type="checkbox" name="acwp_no_btn_drage" id="acwp_no_btn_drage" value="yes" <?php checked( esc_attr( get_option('acwp_no_btn_drage') ), 'yes' ); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_mobile"><?php _e('Show toolbar also on mobile devices', 'acwp');?></label>
                    </th>
                    <td><input type="checkbox" name="acwp_mobile" id="acwp_mobile" value="yes" <?php checked( esc_attr( get_option('acwp_mobile') ), 'yes' ); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="awp_nocookies"><?php _e('Disable the use of cookies', 'acwp');?></label>
                    </th>
                    <td><input type="checkbox" name="awp_nocookies" id="awp_nocookies" value="yes" <?php checked( esc_attr( get_option('awp_nocookies') ), 'yes' ); ?> /></td>
                </tr>
            </table>
        </div>
        <?php
    }
    public function submenu_tab_style(){
        ?>
        <div id="acwp_style" class="acwp-tab">
            <h2><?php _e('Style', 'acwp');?></h2>

            <table class="form-table" id="acwp-styling">
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_toolbar_style"><?php _e('Toolbar style', 'acwp');?></label>
                    </th>
                    <td>
                        <select name="acwp_toolbar_style" id="acwp_toolbar_style">
                            <option value=""><?php _e('App style (default)', 'acwp');?></option>
                            <option value="columns" <?php selected('columns', get_option('acwp_toolbar_style'))?>><?php _e('Columns style', 'acwp');?></option>
                        </select>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_no_toolbar_animation"><?php _e("Disable toolbar animation", 'acwp');?></label>
                    </th>
                    <td><input type="checkbox" name="acwp_no_toolbar_animation" id="acwp_no_toolbar_animation" value="yes" <?php checked( esc_attr( get_option('acwp_no_toolbar_animation') ), 'yes' ); ?> /></td>
                </tr>
                
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_custom_color_allow"><?php _e("Use custom color for the toolbar?", 'acwp');?></label>
                    </th>
                    <td><input type="checkbox" name="acwp_custom_color_allow" id="acwp_custom_color_allow" value="yes" <?php checked( esc_attr( get_option('acwp_custom_color_allow') ), 'yes' ); ?> /></td>
                </tr>
                
                <tr valign="top" id="acwp-toolbar-custom-color" class="acwp-hide-row">
                    <th scope="row">
                        <label for="acwp_custom_color"><?php _e('Toolbar color', 'acwp');?></label>
                    </th>
                    <td><input type="color" name="acwp_custom_color" class="color-field" id="acwp_custom_color" value="<?php echo esc_attr( get_option('acwp_custom_color') );?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_toolbar_side"><?php _e('Toolbar side', 'acwp');?></label>
                    </th>
                    <td>
                        <select name="acwp_toolbar_side" id="acwp_toolbar_side">
                            <option value=""><?php _e('Left (default)', 'acwp');?></option>
                            <option value="right" <?php selected('right', get_option('acwp_toolbar_side'))?>><?php _e('Right', 'acwp');?></option>
                        </select>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_toolbar_stickness"><?php _e('Toolbar vertical stickness', 'acwp');?></label>
                    </th>
                    <td>
                        <select name="acwp_toolbar_stickness" id="acwp_toolbar_stickness">
                            <option value=""><?php _e('To top (default)', 'acwp');?></option>
                            <option value="bottom" <?php selected('bottom', get_option('acwp_toolbar_stickness'))?>><?php _e('To bottom', 'acwp');?></option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <th colspan="2">
                        <h3><?php _e('Toggle Button Position', 'acwp');?></h3>
                    </th>
                </tr>
                
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_toggle_fromtop"><?php _e('Button vertical space', 'acwp');?></label>
                    </th>
                    <td><input type="number" name="acwp_toggle_fromtop" id="acwp_toggle_fromtop" value="<?php echo esc_attr( get_option('acwp_toggle_fromtop') );?>" /> (<?php _e('in pixels', 'acwp'); ?>)</td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_toggle_fromside"><?php _e('Button horizontal space', 'acwp');?></label>
                    </th>
                    <td><input type="number" name="acwp_toggle_fromside" id="acwp_toggle_fromside" value="<?php echo esc_attr( get_option('acwp_toggle_fromside') );?>" /> (<?php _e('in pixels', 'acwp'); ?>)</td>
                </tr>
                
                <tr>
                    <th colspan="2">
                        <h3><?php _e('Toolbar Position', 'acwp');?></h3>
                    </th>
                </tr>
                
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_toolbar_fromtop"><?php _e('Toolbar vertical space', 'acwp');?></label>
                    </th>
                    <td><input type="number" name="acwp_toolbar_fromtop" id="acwp_toolbar_fromtop" value="<?php echo esc_attr( get_option('acwp_toolbar_fromtop') );?>" /> (<?php _e('in pixels', 'acwp'); ?>)</td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_toolbar_fromside"><?php _e('Toolbar horizontal space', 'acwp');?></label>
                    </th>
                    <td><input type="number" name="acwp_toolbar_fromside" id="acwp_toolbar_fromside" value="<?php echo esc_attr( get_option('acwp_toolbar_fromside') );?>" /> (<?php _e('in pixels', 'acwp'); ?>)</td>
                </tr>
            </table>
        </div>
        <?php
    }
    public function submenu_tab_additional(){
        ?>
        <div id="acwp_additional" class="acwp-tab">
            <h2><?php _e('Additional Links', 'acwp');?></h2>
            <h3><?php _e('Accessibility Statement', 'acwp');?></h3>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_statement_label"><?php _e('Label', 'acwp');?></label>
                    </th>
                    <td>
                        <input type="text" name="acwp_statement_label" id="acwp_statement_label" value="<?php echo esc_attr( get_option('acwp_statement_label') ); ?>" placeholder="<?php _e('Accessibility Statement', 'acwp');?>" />
                        <p><?php _e('Change the default label of the statement link. Please note that if you change the default label you will not be able to translate it into other languages', 'acwp');?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_statement"><?php _e('Link', 'acwp');?></label>
                    </th>
                    <td>
                        <input type="url" name="acwp_statement" id="acwp_statement" value="<?php echo esc_attr( get_option('acwp_statement') ); ?>" placeholder="http://" />
                    </td>
                </tr>
            </table>

            <h3><?php _e('Send Feedback', 'acwp');?></h3>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_feedback_label"><?php _e('Label', 'acwp');?></label>
                    </th>
                    <td>
                        <input type="text" name="acwp_feedback_label" value="<?php echo esc_attr( get_option('acwp_feedback_label') ); ?>" placeholder="<?php _e('Send Feedback', 'acwp');?>" />
                        <p><?php _e('Change the default label of the feedback link. Please note that if you change the default label you will not be able to translate it into other languages', 'acwp');?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_feedback"><?php _e('Link', 'acwp');?></label>
                    </th>
                    <td><input type="url" name="acwp_feedback" value="<?php echo esc_attr( get_option('acwp_feedback') ); ?>" placeholder="http://" /></td>
                </tr>
            </table>
            
            <h3><?php _e('Site Map', 'acwp');?></h3>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_sitemap_label"><?php _e('Label', 'acwp');?></label>
                    </th>
                    <td>
                        <input type="text" name="acwp_sitemap_label" value="<?php echo esc_attr( get_option('acwp_sitemap_label') ); ?>" placeholder="<?php _e('Site Map', 'acwp');?>" />
                        <p><?php _e('Change the default label of the sitemap link. Please note that if you change the default label you will not be able to translate it into other languages', 'acwp');?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_sitemap"><?php _e('Link', 'acwp');?></label>
                    </th>
                    <td><input type="url" name="acwp_sitemap" value="<?php echo esc_attr( get_option('acwp_sitemap') ); ?>" placeholder="http://" /></td>
                </tr>
            </table>
        </div>
        <?php
    }
    public function submenu_tab_connect() {
        $status = 'Not active';
        $statusClass = '';
        
        if( get_option('acwp_toolbar_api_status') == 'yes' ){
            $status = 'Active';
            $statusClass = 'acwp-active';
        }
        ?>
        <div id="acwp_connect" class="acwp-tab">
            <h2><?php _e('Connect', 'acwp');?></h2>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        <label><?php _e('Status', 'acwp');?></label>
                    </th>
                    <td>
                        <p id="acwp_toolbar_api_status" class="<?php echo $statusClass;?>">
                            <span class="acwp-indicator"></span>
                            <span class="acwp-indicator-label"><?php echo $status; ?></span>
                        </p>
                    </td>
                </tr>
            </table>
            <h3><?php _e('Activate toolbar monitoring service', 'acwp'); ?></h3>
            <p><?php _e('You can connect the plugin to the AccessibleWP platform to monitor all your accessibility plugins from all your websites in one place and enjoy additional features alongside with PRO versions to members only, to open an account navigate to <a href="https://www.accessible-wp.com" target="_blank">Accessible-WP.com</a>. This feature is not required in order to activate the component.', 'acwp');?></p>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_toolbar_token"><?php _e('Token', 'acwp');?></label>
                    </th>
                    <td>
                        <input type="text" id="acwp_toolbar_token" name="acwp_toolbar_token" value="<?php echo esc_attr( get_option('acwp_toolbar_token') ); ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="acwp_toolbar_tokenemail"><?php _e('Account Email', 'acwp');?></label>
                    </th>
                    <td>
                        <input type="email" id="acwp_toolbar_tokenemail" name="acwp_toolbar_tokenemail" value="<?php echo esc_attr( get_option('acwp_toolbar_tokenemail') ); ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"></th>
                    <td>
                        <button id="acwp_connect_api" type="button" class="button">Connect</button>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }
    
    public function submenu_page_callback() {
        ?>
        <div id="accessible-wp-toolbar" class="wrap">
            <h1><?php _e('AccessibleWP Accessibility Toolbar', 'acwp');?></h1>
            <form method="post" action="options.php">
                <?php settings_fields( 'acwp' ); ?>
                <?php do_settings_sections( 'acwp' ); ?>

                <div class="nav-tab-wrapper">
                    <a href="#acwp_heading" class="nav-tab nav-tab-active"><?php _e('Heading', 'acwp');?></a>
                    <a href="#acwp_options" class="nav-tab"><?php _e('Options', 'acwp');?></a>
                    <a href="#acwp_additional" class="nav-tab"><?php _e('Additional Links', 'acwp');?></a>
                    <a href="#acwp_settings" class="nav-tab"><?php _e('Settings', 'acwp');?></a>
                    <a href="#acwp_style" class="nav-tab"><?php _e('Style', 'acwp');?></a>
                    <a href="#acwp_connect" class="nav-tab"><?php _e('Connect', 'acwp');?></a>
                </div>
                <?php
                echo $this->submenu_tab_heading();
                echo $this->submenu_tab_options();
                echo $this->submenu_tab_additional();
                echo $this->submenu_tab_settings();
                echo $this->submenu_tab_style();
                echo $this->submenu_tab_connect();
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
if( is_admin() )
    new ACWP_AdminPanel();