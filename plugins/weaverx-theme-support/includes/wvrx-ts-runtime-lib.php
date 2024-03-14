<?php

// # Weaver X SW Globals ==============================================================
$wvrx_ts_opts_cache = false;    // internal cache for all settings

function wvrx_ts_help_link($ref, $label): void
{

    $t_dir = wvrx_ts_plugins_url('/help/' . $ref, '');
    $pp_help = '<a style="text-decoration:none;" href="' . $t_dir . '" target="_blank" title="' . $label . '">'
        . '<span style="color:red; vertical-align: middle; margin-left:.25em;" class="dashicons dashicons-editor-help"></span></a>';
    echo $pp_help;
}


// ===============================  options =============================

add_filter('widget_text', 'do_shortcode');        // add shortcode processing to standard text widget


// Interface to Weaver Xtreme

function wvrx_ts_fix_short($prefix, $msg): void
{
    if ($prefix) {
        $m = str_replace('[/', '////', $msg);
        $m = str_replace('[', '[' . $prefix, $m);
        echo str_replace('////', '[/' . $prefix, $m);
    } else {
        echo $msg;
    }
}


add_action('weaverx_theme_support_addon', 'wvrx_ts_theme_support_addon');
function wvrx_ts_theme_support_addon(): void
{

    $theme = get_template_directory();

    $is_xtreme = strpos($theme, '/weaver-xtreme') !== false;

    ?>
    <div class="a-plus">
        <p><strong style="font-size:110%;"><?php
                if ($is_xtreme) {
                    esc_html_e('You have Weaver Xtreme Theme Support installed.', 'weaverx-theme-support' /*adm*/);
                }

                echo ' (V ' . WVRX_TS_VERSION . ')'; ?></strong><br/>
            <?php echo wp_kses_post(__('This section shows the shortcodes and widgets available with Weaver Xtreme Theme Support.
Click the<span style="color:red; vertical-align: middle; margin-left:.25em;" class="dashicons dashicons-editor-help"></span> button to open help entry.', 'weaverx-theme-support' /*adm*/)); ?>
        </p>

        <?php
        $prefix = get_option('wvrx_toggle_shortcode_prefix');
        if ($prefix) {
            echo '<h3 style="color:red;">' . esc_html__("Weaver Xtreme Theme Support Shortcodes now prefixed with 'wvrx_'", 'weaverx-theme-support') . '</h3>';
        }
        ?>

        <h3><?php _e('Shortcodes', 'weaverx-theme-support' /*adm*/); ?></h3>
        <ul>
            <li><?php wvrx_ts_fix_short($prefix, wp_kses_post(__('<span class="atw-blue">Blog Info - [bloginfo]</span> - Display blog info as provided by WordPress bloginfo function', 'weaverx-theme-support' /*adm*/))); ?>
                <?php wvrx_ts_help_link('help.html#bloginfo', esc_html__('Help for Blog Info', 'weaverx-theme-support' /*adm*/)); ?>
                <br/>
                <code><?php wvrx_ts_fix_short($prefix, esc_html__("[bloginfo name='WP bloginfo name' style='style-rules']", 'weaverx-theme-support' /*adm*/)); ?></code>
            </li>
            <li><?php wvrx_ts_fix_short($prefix, wp_kses_post(__('<span class="atw-blue">Box - [box]</span> - Display content in a Box', 'weaverx-theme-support' /*adm*/))); ?>
                <?php wvrx_ts_help_link('help.html#box', esc_html__('Help for Box', 'weaverx-theme-support' /*adm*/)); ?>
                <br/>
                <code><?php wvrx_ts_fix_short($prefix, esc_html__("[box background=#fff align=left border=true border_rule='border-css' border_radius=4 color=#000 margin=1 padding=1 shadow=1 style='style-rules' width=100]text[/box]", 'weaverx-theme-support' /*adm*/)); ?></code>
            </li>
            <li><?php wvrx_ts_fix_short($prefix, wp_kses_post(__('<span class="atw-blue">DIV - [div]text[/div]</span> - Wrap content in a &lt;div&gt; tag', 'weaverx-theme-support' /*adm*/))); ?>
                <?php wvrx_ts_help_link('help.html#scdiv', esc_html__('Help for Header Div', 'weaverx-theme-support' /*adm*/)); ?>
                <br/>
                <code><?php wvrx_ts_fix_short($prefix, esc_html__("[div id='class_id' class='class_name' style='style_values']text[/div]", 'weaverx-theme-support' /*adm*/)); ?></code>
            </li>
            <li<?php wvrx_ts_fix_short($prefix, wp_kses_post(__('><span class="atw-blue">Header Image - [header_image]</span> - Display default header image', 'weaverx-theme-support' /*adm*/))); ?>
            <?php wvrx_ts_help_link('help.html#headerimage', esc_html__('Help for Header Image', 'weaverx-theme-support' /*adm*/)); ?>
            <br/>
            <code><?php wvrx_ts_fix_short($prefix, esc_html__("[header_image h='size' w='size' style='inline-style']", 'weaverx-theme-support' /*adm*/)); ?></code>
            </li>

            <li><?php wvrx_ts_fix_short($prefix, wp_kses_post(__('<span class="atw-blue">HTML - [html]</span> - Wrap content in any HTML tag', 'weaverx-theme-support' /*adm*/))); ?>
                <?php wvrx_ts_help_link('help.html#schtml', esc_html__('Help for HTML', 'weaverx-theme-support' /*adm*/)); ?>
                <br/>
                <code><?php wvrx_ts_fix_short($prefix, esc_html__("[html html-tag args='parameters']", 'weaverx-theme-support' /*adm*/)); ?></code>
            </li>
            <li><?php wvrx_ts_fix_short($prefix, wp_kses_post(__('<span class="atw-blue">iFrame - [iframe]</span> - Display external content in an iframe', 'weaverx-theme-support' /*adm*/))); ?>
                <?php wvrx_ts_help_link('help.html#sciframe', esc_html__('Help for iframe', 'weaverx-theme-support' /*adm*/)); ?>
                <br/>
                <code><?php wvrx_ts_fix_short($prefix, esc_html__("[iframe src='//example.com' height=300 width=400 style='style'][/iframe]", 'weaverx-theme-support' /*adm*/)); ?></code>
            </li>
            <li><?php wvrx_ts_fix_short($prefix, wp_kses_post(__('<span class="atw-blue">Login - [login style="CSS Style"]</span> - Show simple Login/Logout link', 'weaverx-theme-support' /*adm*/))); ?>
                <?php wvrx_ts_help_link('help.html#sclogin', esc_html__('Help for login', 'weaverx-theme-support' /*adm*/)); ?>
                <br/>
                <code><?php wvrx_ts_fix_short($prefix, esc_html__("[login style=\"CSS Style\"]", 'weaverx-theme-support' /*adm*/)); ?></code>
            </li>

            <li><?php wvrx_ts_fix_short($prefix, wp_kses_post(__('<span class="atw-blue">Show If- [show_if]</span> - Show content only if args meet specified conditions', 'weaverx-theme-support' /*adm*/))); ?>
                <?php wvrx_ts_help_link('help.html#scshowif', esc_html__('Help for Show/Hide If', 'weaverx-theme-support' /*adm*/)); ?>
                <br/>
                <code><?php wvrx_ts_fix_short($prefix, esc_html__('[show|hide_if device=device logged_in=true/false not_post_id=id-list post_id=id-list user_can=what]text[/show|hide_if]', 'weaverx-theme-support' /*adm*/)); ?></code>
            </li>
            <li><?php wvrx_ts_fix_short($prefix, wp_kses_post(__('<span class="atw-blue">Hide If - [hide_if]</span> - Hide content', 'weaverx-theme-support' /*adm*/))); ?>
            </li>

            <li><?php wvrx_ts_fix_short($prefix, wp_kses_post(__('<span class="atw-blue">Site Tagline - [site_tagline style="style" matchtheme=false]</span> - Display the site tagline', 'weaverx-theme-support' /*adm*/))); ?>
                <?php wvrx_ts_help_link('help.html#sitetitlesc', esc_html__('Help for Site Tagline', 'weaverx-theme-support' /*adm*/)); ?>
                <br/>
                <code><?php wvrx_ts_fix_short($prefix, esc_html__("[site_tagline style='inline-style']", 'weaverx-theme-support' /*adm*/)); ?></code>
            </li>
            <li><?php wvrx_ts_fix_short($prefix, wp_kses_post(__('<span class="atw-blue">Site Title - [site_title style="style" matchtheme=false]</span> - Display the site title', 'weaverx-theme-support' /*adm*/))); ?>
                <?php wvrx_ts_help_link('help.html#sitetitlesc', esc_html__('Help for Site Title', 'weaverx-theme-support' /*adm*/)); ?>
                <br/>
                <code><?php wvrx_ts_fix_short($prefix, esc_html__("[site_title style='inline-style']", 'weaverx-theme-support' /*adm*/)); ?></code>
            </li>
            <li><?php wvrx_ts_fix_short($prefix, wp_kses_post(__('<span class="atw-blue">SPAN - [span]text[/span]</span> - Wrap content in a &lt;span&gt; tag', 'weaverx-theme-support' /*adm*/))); ?>
                <?php wvrx_ts_help_link('help.html#scdiv', esc_html__('Help for Span', 'weaverx-theme-support' /*adm*/)); ?>
                <br/>
                <code><?php wvrx_ts_fix_short($prefix, esc_html__("[span id='class_id' class='class_name' style='style_values']text[/span]", 'weaverx-theme-support' /*adm*/)); ?></code>
            </li>
            <li><?php wvrx_ts_fix_short($prefix, wp_kses_post(__('<span class="atw-blue">Tab Group - [tab_group]</span> - Display content on separate tabs', 'weaverx-theme-support' /*adm*/))); ?>
                <?php wvrx_ts_help_link('help.html#tab_group', esc_html__('Help for Tab Group', 'weaverx-theme-support' /*adm*/)); ?>
                <br/>
                <code><?php wvrx_ts_fix_short($prefix, esc_html__('[tab_group][tab]...[/tab][tab]...[/tab][/tab_group]', 'weaverx-theme-support' /*adm*/)); ?></code>
            </li>
            <li>
                <?php wvrx_ts_fix_short($prefix, wp_kses_post(__('<span class="atw-blue">Video - [vimeo], [youtube] shared</span> - Options for both Vimeo/YouTube shortcodes. Click the<span style="color:red; vertical-align: middle; margin-left:.25em;" class="dashicons dashicons-editor-help"></span> for specific help.', 'weaverx-theme-support' /*adm*/))); ?>
                <br/>
                <code><?php wvrx_ts_fix_short($prefix, esc_html__('[vimeo or youtube aspect=hd center=1 percent=100 sd=0 vertical=0]', 'weaverx-theme-support' /*adm*/)); ?></code>
            </li>
            <li><?php wvrx_ts_fix_short($prefix, wp_kses_post(__('<span class="atw-blue">Vimeo - [vimeo]</span> - Display video from Vimeo responsively, with options', 'weaverx-theme-support' /*adm*/))); ?>
                <?php wvrx_ts_help_link('help.html#video', esc_html__('Help for Video', 'weaverx-theme-support' /*adm*/)); ?>
                <br/>
                <code><?php wvrx_ts_fix_short($prefix, esc_html__('[vimeo vimeo-url id=videoid sd=0 percent=100 center=1 color=#hex autoplay=0 loop=0 portrait=1 title=1 byline=1]', 'weaverx-theme-support' /*adm*/)); ?></code>
            </li>

            <li><?php wvrx_ts_fix_short($prefix, wp_kses_post(__('<span class="atw-blue">YouTube - [youtube]</span> - Display video from YouTube responsively, with options', 'weaverx-theme-support' /*adm*/))); ?>
                <?php wvrx_ts_help_link('help.html#video', esc_html__('Help for Video', 'weaverx-theme-support' /*adm*/)); ?>
                <br/>
                <code><?php wvrx_ts_fix_short($prefix, esc_html__('[youtube youtube-url id=videoid sd=0 percent=100 center=1 rel=0 privacy=0  see_help_for_others]', 'weaverx-theme-support' /*adm*/)); ?></code>
            </li>
        </ul>
        <form enctype="multipart/form-data" name='toggle_shortcode' action="<?php echo $_SERVER["REQUEST_URI"]; ?>"
              method='post'>

            <?php
            if ($is_xtreme) {
            if ($prefix) {
                $button = esc_html__("Remove 'wvrx_' prefix from shortcode names: [ bloginfo ], etc.", 'weaverx-theme-support');
            } else {
                $button = esc_html__("Add 'wvrx_' to shortcode names: [ wvrx_bloginfo ], etc.", 'weaverx-theme-support');
            }
            ?>
            <div style="clear:both;"></div>
            <span class='submit'><input class="button-primary" name="toggle_shortcode_prefix" type="submit"
                                        value="<?php echo $button; ?>"/></span>
            <br/><small> <?php esc_html_e("To avoid conflicts with other plugins, you can add a 'wvrx_' prefix to these shortcodes.", 'weaver-xtreme /*adm*/'); ?> </small>
            <?php weaverx_nonce_field('toggle_shortcode_prefix'); ?>
        </form>
    <?php } ?>
        <br/>

        <h3><?php esc_html_e('Widgets', 'weaverx-theme-support' /*adm*/); ?></h3>
        <ul>
            <li><?php echo wp_kses_post(__('<span class="atw-blue">Weaver Login Widget</span> - Simplified login widget', 'weaverx-theme-support' /*adm*/)); ?>
                <?php wvrx_ts_help_link('help.html#widg-login', esc_html__('Help for Login Widget', 'weaverx-theme-support' /*adm*/)); ?>
            </li>

            <li><?php echo wp_kses_post(__('<span class="atw-blue">Weaver Per Page Text</span> - Display text on a per page basis, based on a Custom Field value', 'weaverx-theme-support' /*adm*/)); ?>
                <?php wvrx_ts_help_link('help.html##widg_pp_text', esc_html__('Help for Per Page Text Widget', 'weaverx-theme-support' /*adm*/)); ?>
            </li>

            <li><?php echo wp_kses_post(__('<span class="atw-blue">Weaver Text 2 Col</span> - Display text in two columns - great for wide top/bottom widgets', 'weaverx-theme-support' /*adm*/)); ?>
                <?php wvrx_ts_help_link('help.html#widg_text_2', esc_html__('Help for Two Column Text Widget', 'weaverx-theme-support' /*adm*/)); ?>
            </li>
        </ul>

        <?php if ($is_xtreme) { ?>
            <h3><?php esc_html_e('Per Page/Post Settings', 'weaverx-theme-support' /*adm*/); ?></h3>
            <p> <?php esc_html_e("Click the following button to produce a list of links to all pages and posts that have Per Page or Per Post settings.", 'weaver-xtreme /*adm*/'); ?></p>
            <div style="clear:both;"></div>
            <form enctype="multipart/form-data" name='toggle_shortcode' action="<?php echo $_SERVER["REQUEST_URI"]; ?>"
                  method='post'>
                <span class='submit'><input class="button-primary" name="show_per_page_report" type="submit"
                                            value="<?php esc_html_e('Show Pages and Posts with Per Page/Post Settings', 'weaver-xtreme /*adm*/'); ?>"/></span>
                <?php weaverx_nonce_field('show_per_page_report'); ?>
            </form><br/><br/>
        <?php } ?>
    </div>

    <?php
}


add_action('weaverx_more_help', 'weaverx_ts_more_help');
function weaverx_ts_more_help(): void
{
    ?>
    <div style="clear:both;"></div>
    <hr/>
    <script>jQuery(document).ready(function () {
            jQuery('#wvrx-sysinfo').click(function () {
                jQuery('#wvrx-sysinfo').copyme();
            });
            jQuery('#btn-sysinfo').click(function () {
                jQuery('#wvrx-sysinfo').copyme();
            });
        });</script>

    <h3><?php esc_html_e('Your System and Configuration Info', 'weaverx-theme-support' /*adm*/); ?></h3>
    <?php
    $sys = weaverx_ts_get_sysinfo();
    ?>
    <div style="float:left;max-width:60%;"><textarea id="wvrx-sysinfo" readonly class="wvrx-sysinfo no-autosize"
                                                     style="font-family:monospace;" rows="12"
                                                     cols="50"><?php echo $sys; ?></textarea></div>
    <div style="margin-left:20px;max-width:40%;float:left;"><?php echo wp_kses_post(__('<p>This information can be used to help us diagnose issues you might be having with Weaver Xtreme.
If you are asked by a moderator on the <a href="//forum.weavertheme.com" target="_blank">Weaver Xtreme Support Forum</a>, please use the "Copy to Clipboard"
button and then Paste the Sysinfo report directly into a Forum post.</p>
<p>Please note that there is no personally identifying data in this report except your site\'s URL. Having your site URL is important to help us
diagnose the problem, but you can delete it from your forum post right after you paste if you need to.</p>', 'weaverx-theme-support')); ?></div>
    <div style="clear:both;margin-bottom:20px;"></div>

    <button id="btn-sysinfo" class="button-primary">Copy To Clipboard</button>
    <?php
    //if (WEAVERX_DEV_MODE && isset($GLOBALS['POST_COPY']) && $GLOBALS['POST_COPY'] != false ) {
    //	echo '<pre>$_POST:'; var_dump($GLOBALS['POST_COPY']); echo '</pre>';
    //}
}

add_action('weaverx_ts_show_version', 'weaverx_ts_show_version_action');
function weaverx_ts_show_version_action(): void
{
    echo "<!-- Weaver Xtreme Theme Support " . WVRX_TS_VERSION . " --> ";
}


function weaverx_ts_get_sysinfo(): string
{

    global $wpdb;

    $theme = wp_get_theme()->Name . ' (' . wp_get_theme()->Version . ')';
    $frontpage = get_option('page_on_front');
    $frontpost = get_option('page_for_posts');
    $fr_page = $frontpage ? get_the_title($frontpage) . ' (ID# ' . $frontpage . ')' . '' : 'n/a';
    $fr_post = $frontpage ? get_the_title($frontpost) . ' (ID# ' . $frontpost . ')' . '' : 'n/a';
    $jquchk = wp_script_is('jquery', 'registered') ? $GLOBALS['wp_scripts']->registered['jquery']->ver : 'n/a';

    $return = '### Weaver System Info ###' . "\n\n";

    // Basic site info
    $return .= '        -- WordPress Configuration --' . "\n\n";
    $return .= 'Site URL:                 ' . site_url() . "\n";
    $return .= 'Home URL:                 ' . home_url() . "\n";
    $return .= 'Multisite:                ' . (is_multisite() ? 'Yes' : 'No') . "\n";
    $return .= 'Version:                  ' . get_bloginfo('version') . "\n";
    $return .= 'Language:                 ' . get_locale() . "\n";
    //$return .= 'Table Prefix:             ' . 'Length: ' . strlen( $wpdb->prefix ) . "\n";
    $return .= 'WP_DEBUG:                 ' . (defined('WP_DEBUG') ? WP_DEBUG ? 'Enabled' : 'Disabled' : 'Not set') . "\n";
    $return .= 'WP Memory Limit:          ' . WP_MEMORY_LIMIT . "\n";
    $return .= 'Permalink:                ' . get_option('permalink_structure') . "\n";
    $return .= 'Show On Front:            ' . get_option('show_on_front') . "\n";
    $return .= 'Page On Front:            ' . $fr_page . "\n";
    $return .= 'Page For Posts:           ' . $fr_post . "\n";
    $return .= 'Current Theme:            ' . $theme . "\n";
    $return .= 'Post Types:               ' . implode(', ', get_post_types('', 'names')) . "\n";

    // Plugin Configuration
    $return .= "\n" . '        -- Weaver Xtreme Configuration --' . "\n\n";
    $return .= 'Weaver Xtreme Version:    ' . WEAVERX_VERSION . "\n";
    $return .= '   Theme Support Version: ' . WVRX_TS_VERSION . "\n";
    if (defined('WEAVER_XPLUS_VERSION')) {
        $return .= '   Xtreme Plus Version:   ' . WEAVER_XPLUS_VERSION . "\n";
    }

    // Server Configuration
    $return .= "\n" . '        -- Server Configuration --' . "\n\n";
    $return .= 'Operating System:         ' . php_uname('s') . "\n";
    $return .= 'PHP Version:              ' . PHP_VERSION . "\n";
    $return .= 'MySQL Version:            ' . $wpdb->db_version() . "\n";
    $return .= 'jQuery Version:           ' . $jquchk . "\n";

    $return .= 'Server Software:          ' . $_SERVER['SERVER_SOFTWARE'] . "\n";

    // PHP configs... now we're getting to the important stuff
    $return .= "\n" . '        -- PHP Configuration --' . "\n\n";
    //$return .= 'Safe Mode:                ' . ( ini_get( 'safe_mode' ) ? 'Enabled' : 'Disabled' . "\n" );
    $return .= 'Local Memory Limit:       ' . ini_get('memory_limit') . "\n";
    $return .= 'Server Memory Limit:      ' . get_cfg_var('memory_limit') . "\n";
    $return .= 'Post Max Size:            ' . ini_get('post_max_size') . "\n";
    $return .= 'Upload Max Filesize:      ' . ini_get('upload_max_filesize') . "\n";
    $return .= 'Time Limit:               ' . ini_get('max_execution_time') . "\n";
    $return .= 'Max Input Vars:           ' . ini_get('max_input_vars') . "\n";
    $return .= 'Display Errors:           ' . (ini_get('display_errors') ? 'On (' . ini_get('display_errors') . ')' : 'N/A') . "\n";

    // WordPress active plugins
    $return .= "\n" . '        -- WordPress Active Plugins --' . "\n\n";
    $plugins = get_plugins();
    $active_plugins = get_option('active_plugins', array());
    foreach ($plugins as $plugin_path => $plugin) {
        if (!in_array($plugin_path, $active_plugins)) {
            continue;
        }
        $return .= $plugin['Name'] . ': ' . $plugin['Version'] . "\n";
    }

    // WordPress inactive plugins
    $return .= "\n" . '        -- WordPress Inactive Plugins --' . "\n\n";
    foreach ($plugins as $plugin_path => $plugin) {
        if (in_array($plugin_path, $active_plugins)) {
            continue;
        }
        $return .= $plugin['Name'] . ': ' . $plugin['Version'] . "\n";
    }

    if (is_multisite()) {
        // WordPress Multisite active plugins
        $return .= "\n" . '        -- Network Active Plugins --' . "\n\n";
        $plugins = wp_get_active_network_plugins();
        $active_plugins = get_site_option('active_sitewide_plugins', array());
        foreach ($plugins as $plugin_path) {
            $plugin_base = plugin_basename($plugin_path);
            if (!array_key_exists($plugin_base, $active_plugins)) {
                continue;
            }
            $plugin = get_plugin_data($plugin_path);
            $return .= $plugin['Name'] . ': ' . $plugin['Version'] . "\n";
        }
    }

    $return .= "\n" . '### End System Info ###' . "\n";

    return $return;
}

// ======== fallback file support ======

function weaverx_ts_write_to_upload($filename, $output): bool
{

    // first, try using WP_Filesystem - it should be "safer"
    global $wp_filesystem;

    if (empty($wp_filesystem)) {                                /* load if not already present */
        require_once(ABSPATH . '/wp-admin/includes/file.php');
        WP_Filesystem();
    }

    $upload_dir = wp_upload_dir(); // Grab uploads folder array
    $dir = trailingslashit($upload_dir['basedir']) . WEAVERX_SUBTHEMES_DIR . DIRECTORY_SEPARATOR; // Set storage directory path

    WP_Filesystem(); // Initial WP file system
    $wp_filesystem->mkdir($dir); // Make a new folder 'weaverx-subthemes' for storing our file if not created already.

    if (!@is_writable($dir)) {        // direct php access
        if (function_exists('weaverx_ts_altwrite_to_upload')) {
            return weaverx_ts_altwrite_to_upload($filename, $output);
        } else {
            weaverx_f_file_access_fail(esc_html__('Directory not writable to save editor style file. Please install and activate the Weaver Xtreme Theme Support plugin. Directory: ', 'weaver-xtreme') . $dir);
        }

        return false;
    }

    $wp_filesystem->put_contents($dir . $filename, $output, 0744); // Store in the file.
    return true;
}

function weaverx_ts_altwrite_to_upload($filename, $output): bool
{
    // some systems fail using $wp_filesystem to create editor file. This will use direct PHP I/O (not allowed in themes) to avoid the problem.

    // recreate the directory name

    $upload_dir = wp_upload_dir(); // Grab uploads folder array
    $dir = trailingslashit($upload_dir['basedir']) . WEAVERX_SUBTHEMES_DIR . DIRECTORY_SEPARATOR; // Set storage directory path

    if (!wp_mkdir_p($dir)) {
        weaverx_f_file_access_fail(esc_html__('Directory not writable to save editor style file. You will have to check with your hosting company to have your installation fixed to allow directories to be created. Directory: ', 'weaver-xtreme' /*adm*/) . $dir);
        return false;
    }

    $file = fopen($dir . $filename, 'w');
    if (!fwrite($file, $output)) {
        weaverx_f_file_access_fail(esc_html__('Unable to save editor style file. You will have to check with your hosting company to have your installation fixed to allow files to be created.', 'weaver-xtreme' /*adm*/) . $dir);
        return false;
    }
    fclose($file);
    return true;
}
