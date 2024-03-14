<?php /**
 *
 */
// Weaver 4 legacy interface options
function weaverx_admin_subthemes4(): void
{
    weaverx_tab_title(__('Predefined Weaver Xtreme Subthemes', 'weaver-xtreme'), 'help.html#PredefinedThemes', esc_html__('Help for Weaver Xtreme Predefined Themes', 'weaver-xtreme' /*adm*/)); ?>
    <small style="font-weight:normal;font-size:10px;"><?php esc_html_e('You can click the ?\'s found throughout Weaver Xtreme admin pages for context specific help.', 'weaver-xtreme' /*adm*/); ?></small>

    <?php echo wp_kses_post(__('<h3>Welcome to Weaver Xtreme</h3>', 'weaver-xtreme' /*adm*/)); ?>

    <?php echo wp_kses_post(__('<p>Weaver Xtreme gives you extreme control of your WordPress blog appearance using the
different admin tabs here. This tab lets you get a quick start by picking one of the many
predefined subthemes. Once you\'ve picked a starter theme, use the <em>Main Options</em> and <em>Advanced Options</em>
tabs to tweak the theme to be whatever you like. After you have a theme you\'re happy with,
you can save it from the Save/Restore tab. The <em>Help</em> tab has much more <b>useful</b> information.</p>', 'weaver-xtreme' /*adm*/)); ?>


    <h3 class="atw-option-subheader"><span style="color:black;padding:.2em;"
                                           class="dashicons dashicons-images-alt2"></span>
        <?php esc_html_e('Get started by trying one of the predefined subthemes!', 'weaver-xtreme' /*adm*/); ?>
    </h3>
    <?php
    $theme_dir = trailingslashit(WP_CONTENT_DIR) . 'themes/' . get_template() . '/subthemes/';
    $theme_list = array();
    if ($media_dir = opendir($theme_dir)) {        // build the list of themes from directory
        while ($m_file = readdir($media_dir)) {
            $len = strlen($m_file);
            $base = substr($m_file, 0, $len - 4);
            $ext = $len > 4 ? substr($m_file, $len - 4, 4) : '';
            if ($ext == '.wxt' || $ext == '.wxb') {
                $theme_list[] = $base;
            }
        }
    }

    if (!empty($theme_list)) {
        echo '<p style="font-size:120%;font-weight:bold;">';
        echo wp_kses_post(__('Please remember: these subthemes are only starting points!
You can use <em>Weaver Xtreme</em> options to change virtually any part of these subthemes.
You can change colors, sidebar layouts, font family and sizes, borders, spacing - really, everything.', 'weaver-xtreme' /*adm*/));
        echo '</p>';
        weaverx_st_pick_theme($theme_list);    // show the theme picker
    } else {
        if (WEAVERX_SETTINGS_VERSION == 'WvrX5:2.0') {
            echo wp_kses_post(__("<h3>IMPORTANT NOTE: Weaver Xtreme Version 5 only supports picking subthemes from the Customizer.</h3>\n", 'weaver-xtreme' /*adm*/));
        } else {
            echo wp_kses_post(__("<h3>WARNING: Your version of Weaver Xtreme is likely installed incorrectly. Unable to find subtheme definitions.</h3>\n", 'weaver-xtreme' /*adm*/));
        }
    }
}

function weaverx_st_pick_theme($list_in): void
{
    // output the form to select a file list from weaverx-subthemes directory
    $list = $list_in;
    natcasesort($list);
    $cur_theme = weaverx_getopt('theme_filename');
    if (!$cur_theme) {
        $cur_theme = WEAVERX_DEFAULT_THEME;
    }    // the default theme
    ?>
    <form enctype="multipart/form-data" name='pick_theme' method='post'
          onSubmit="return confirm('<?php esc_html_e('Are you sure you want select a new theme?\r\n\r\nSelecting a new subtheme will overwrite your existing theme settings. You should save your existing settings on the Save/Restore menu if you have made changes.', 'weaver-xtreme'); ?>');">
        &nbsp;&nbsp;<strong><?php esc_html_e('Click a Radio Button below to select a subtheme:', 'weaver-xtreme' /*adm*/); ?>
            &nbsp;</strong>
        <span style="padding-left:100px;"><?php esc_html_e('Current theme:', 'weaver-xtreme' /*adm*/); ?> <strong>
<?php
$cur_addon = weaverx_getopt('addon_name');
if ($cur_addon == '') {
    echo ucwords(str_replace('-', ' ', $cur_theme));
} else {
    echo esc_html__('Add-on Subtheme: ', 'weaver-xtreme') . ucwords(str_replace('-', ' ', $cur_addon));
    $cur_theme = '';
}
?>
	</strong></span>

        <br/><br/>
        <?php
        //weaverx_confirm_select_theme();
        ?>
        <input class="button-primary" name="set_subtheme" type="submit"
               value="<?php esc_html_e('Set to Selected Subtheme', 'weaver-xtreme'); ?>"/>

        <p style="color:#b00;font-weight:bold;font-size:120%">
            <br/><?php echo wp_kses_post(__('<em>Note:</em> Before switching to any subtheme, you must Save and download a copy of your settings using the Save / Restore page, in order to be able to go back to them if required.', 'weaver-xtreme' /*adm*/)); ?>
        </p>
        <?php
        weaverx_nonce_field('set_subtheme');

        $thumbs = weaverx_relative_url('subthemes/');

        foreach ($list

        as $addon) {
        $name = ucwords(str_replace('-', ' ', $addon));
        ?>
        <div style="float:left; width:200px;">
            <label><input type="radio" name="theme_picked"
                <?php echo 'value="' . $addon . '" ' . ($cur_theme == $addon ? 'checked' : '') .
                    '/> <strong>' . $name . '</strong><br />';
                if (!weaverx_getopt('_hide_theme_thumbs')) {
                    echo '<img style="border: 1px solid gray; margin: 5px 0px 10px 0px;" src="' . esc_url($thumbs . $addon . '.jpg') . '" width="150px" height="113px" alt="thumb" /></label></div>' . "\n";
                } else {
                    echo "</label></div>\n";
                }
                }

                if (!weaverx_getopt_checked('_hide_theme_thumbs')) {
                    weaverx_clear_both();
                    ?>
                    <span class='submit' style='padding-top:6px;'><input class="button-primary" name="set_subtheme"
                                                                         type="submit"
                                                                         value="<?php esc_html_e('Set to Selected Subtheme', 'weaver-xtreme' /*adm*/); ?>"/></span>
                    <?php
                }
                ?>

    </form>
    <div style="clear:both;padding-top:6px;"></div>

    <form enctype="multipart/form-data" name='hide_thumbs_form' method='post'>
        <?php
        $hide_msg = (weaverx_getopt('_hide_theme_thumbs')) ? esc_html__('Show Subtheme Thumbnails', 'weaver-xtreme' /*adm*/) :
            esc_html__('Hide Subtheme Thumbnails', 'weaver-xtreme' /*adm*/);
        ?>
        <input class="button-primary" name="hide_thumbs" type="submit" value="<?php echo $hide_msg; ?>"/>
        <?php weaverx_nonce_field('hide_thumbs'); ?>
    </form>
    <div style="clear:both;"></div>
    <hr/>
    <?php
    do_action('weaverx_child_show_extrathemes');
    do_action('weaverxplus_admin', 'show_subthemes');
}

function weaverx_confirm_select_theme(): void
{
    ?>

    <br/>
    <input class="button-primary" type="submit"
           onSubmit="return confirm('<?php esc_html_e('Are you sure you want select a new theme? This will overwrite you existing theme settings.', 'weaver-xtreme'); ?>');"
           name="set_subtheme" value="<?php esc_html_e('Set to Selected Subtheme', 'weaver-xtreme' /*adm*/); ?>"/>
    <?php weaverx_nonce_field('set_subtheme');
}
