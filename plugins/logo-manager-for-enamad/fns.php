<?php
/*
* No script kiddies please!
*/
defined('ABSPATH') or die("اللهم صل علی محمد و آل محمد و عجل فرجهم");


/**
 * plugin shortcode
 * Enamad
 * @since 0.1
 */
function enamadlogo_shortcode()
{
    $print_output = 1;
    $is_widget = true;
    $html = enamad_logo_html(array(
        'print_output' => $print_output,
        'is_widget' => $is_widget,
        '_enamad_code_type' => 'enamad'
    ));
    return $html;
}

add_shortcode('enamadlogo_shortcode', 'enamadlogo_shortcode');

/**
 * plugin shortcode
 * shamed
 * @since 0.6
 */
function enamadlogo_shamed_shortcode()
{
    $print_output = false;
    $is_widget = true;
    $html = enamad_logo_html(array(
        'print_output' => $print_output,
        'is_widget' => $is_widget,
        '_enamad_code_type' => 'shamed'
    ));
    return $html;
}

add_shortcode('enamadlogo_shamed_shortcode', 'enamadlogo_shamed_shortcode');

/**
 * plugin shortcode
 * Custom
 * @since 0.6
 */
function enamadlogo_custom_shortcode()
{
    $print_output = false;
    $is_widget = true;
    $html = enamad_logo_html(array(
        'print_output' => $print_output,
        'is_widget' => $is_widget,
        '_enamad_code_type' => 'custom'
    ));
    return $html;
}

add_shortcode('enamadlogo_custom_shortcode', 'enamadlogo_custom_shortcode');


/**
 * add enamad html to site
 * @param boolean $print_output whether echo output or not
 * @param boolean $is_widget whether is in widget , shortcode or not
 * @return string
 * @since 1.0
 */
add_action('wp_footer', 'enamad_logo_html', 10, 1);
function enamad_logo_html($_arg = array())
{
    if (!is_array($_arg)) {
        $_arg = array();
    }
    if (!isset($_arg['print_output'])) {
        $_arg['print_output'] = true;
    }

    if (!isset($_arg['is_widget'])) {
        $_arg['is_widget'] = false;
    }

    extract($_arg);
    $ignore_replace_image = isset($ignore_replace_image) ? $ignore_replace_image : false;
    $settings = get_option('enamad_logo');
    $replace_with_img_content = '<a target="_blank" href="' . get_bloginfo('url') . '/?show-enamad-logo=1"><img src="' . _enamadlogo_PATH . '/logo.png"  alt="enemad-logo" style="cursor:pointer" ></a>';
    if (!$_arg['is_widget']) {
        if (isset($settings['enamad-enable']) && $settings['enamad-enable'] != 1) {
            return;
        }

        if (isset($settings['enamad-view-method']) && $settings['enamad-view-method'] == 'front-page' && !is_front_page()) {
            return;
        }
    }

    $top = ($settings['enamad-position'] == 'top-right' || $settings['enamad-position'] == 'top-left') ? '0' : 'auto';
    $bottom = ($settings['enamad-position'] == 'bottom-right' || $settings['enamad-position'] == 'bottom-left') ? '0' : 'auto';
    $right = ($settings['enamad-position'] == 'top-right' || $settings['enamad-position'] == 'bottom-right') ? '0' : 'auto';
    $left = ($settings['enamad-position'] == 'top-left' || $settings['enamad-position'] == 'bottom-left') ? '0' : 'auto';
    $width = $settings['enamad-width'];
    $html = '';
    if (!$is_widget) {
        $html .= '<div class="enamad-logo-wrapper none-widget" style="width:' . $width . 'px !important;z-index:999999;height:auto; position:fixed; top:' . $top . '; right:' . $right . '; left:' . $left . ';bottom:' . $bottom . ';">';
    }

    $code_content = [];

    if ($is_widget) {
        if (isset($_arg['_enamad_code_type']) && $_arg['_enamad_code_type'] == 'shamed') {
            $code_content[] = (isset($settings['enamad-shamed-code']) && !empty($settings['enamad-shamed-code'])) ? $settings['enamad-shamed-code'] : '';
        } elseif (isset($_arg['_enamad_code_type']) && $_arg['_enamad_code_type'] == 'custom') {
            $code_content[] = (isset($settings['enamad-custom-code']) && !empty($settings['enamad-custom-code'])) ? $settings['enamad-custom-code'] : '';

        } else {
            if (!$ignore_replace_image && isset($settings['enamad-replace-with-img']) && $settings['enamad-replace-with-img'] == 1) {
                $code_content[] = $replace_with_img_content;
            } else {
                $code_content[] = (isset($settings['enamad-code']) && !empty($settings['enamad-code'])) ? $settings['enamad-code'] : '';

            }
        }

    } else {

        // $code_content[] = (isset($settings['enamad-shamed-code']) && !empty($settings['enamad-shamed-code']) ) ? $settings['enamad-shamed-code'] :'';
        // $code_content[] = (isset($settings['enamad-custom-code']) && !empty($settings['enamad-custom-code']) ) ? $settings['enamad-custom-code'] :'';
        if (!$ignore_replace_image && isset($settings['enamad-replace-with-img']) && $settings['enamad-replace-with-img'] == 1) {
            $code_content[] = $replace_with_img_content;
        } else {
            $code_content[] = (isset($settings['enamad-code']) && !empty($settings['enamad-code'])) ? $settings['enamad-code'] : '';

        }

    }


    if (!empty($code_content)) {
        $html .= stripcslashes(implode(' ', $code_content));
    } else {
        $html .= '<iframe src="/eNamadLogo.htm" frameborder="0" scrolling="no" allowtransparency="true" style="width: 150px; height:150px;"></iframe>';
    }


    if (!$is_widget && isset($settings['enamad-disable-mobile']) && $settings['enamad-disable-mobile'] == 1) {
        $html .= '<style>
		@media screen and (max-width: 600px) {
			.enamad-logo-wrapper.none-widget {
			  visibility: hidden !important;
			  display: none !important;
			}
		  }
		</style>';
    }
    if (!$is_widget) {
        $html .= '</div>';
    }
    if ($print_output) {
        echo $html;
    } else {
        return $html;
    }

}

/**
 * Notice After install/update
 */
add_action('admin_init', 'enamad_after_install_actions');
function enamad_after_install_actions()
{
    if (get_option('enamad_new_ver_notice_applied_0_2') != 'ok' && (version_compare(0.3, _enamadlogo_ver) > 0)) {
        add_action('admin_notices', 'enamad_update_admin_message');
    }

    //delete this option to prevent more show
    if (isset($_GET['update_enamad_new_ver_notice_applied_0_2'])) {
        update_option('enamad_new_ver_notice_applied_0_2', 'ok');
        wp_redirect(menu_page_url('enamadlogo-options', FALSE));
        die();
    }
}

function enamad_update_admin_message()
{
    $Message = sprintf(
        __('نسخه جدید نماد الکترونیکی دچار تغییراتی شده،لطفا جهت تنظیمات به %sاینجا%s رفته و کد جدید را در قسمت مربوطه وارد نمایید.<a href="' . menu_page_url('enamadlogo-options', FALSE) . '&update_enamad_new_ver_notice_applied_0_2">× حذف این پیام</a>')
        , '<a href="' . menu_page_url('enamadlogo-options', FALSE) . '">', '</a>'
    );
    echo '<div class="updated"><p>' . $Message . '</p></div>';
}


/**
 * Add action link / settings
 * @sicnce 0.6
 */


add_filter('plugin_action_links_' . _enamadlogo_BASENAME, 'enamadlogo_plugin_actions_links');

function enamadlogo_plugin_actions_links($links)
{
    $url = admin_url('options-general.php?page=enamadlogo-options');

    $_link = '<a href="' . $url . '">تنظیمات پلاگین</a>';

    $links[] = $_link;

    return $links;
}

/**
 * redirect to plugin settings after activation
 * @sicnce 0.7
 */

add_action('init', 'enamadlogo_action_init');
function enamadlogo_action_init()
{
    if (get_option('enamadlogo_redirect_after_activation_option', false)) {
        delete_option('enamadlogo_redirect_after_activation_option');
        exit(wp_redirect(admin_url('options-general.php?page=enamadlogo-options')));
    }

    /**
     * show enamad logo
     */
    if (isset($_GET['show-enamad-logo'])) {
        ?>
        <html>
    <html dir="rtl" lang="fa-IR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body style="text-align: center;">
    <?php
    $print_output = true;
    $is_widget = true;
    enamad_logo_html(array(
        'print_output' => $print_output,
        'is_widget' => $is_widget,
        '_enamad_code_type' => 'enamad',
        'ignore_replace_image' => true,
    ));
    ?>
    </body>
    </html>
        <?php
        die();
    }
}

/**
 * activaton hook
 * @sicnce 0.7
 */

function enamadlogo_hook_activate()
{
    add_option('enamadlogo_redirect_after_activation_option', true);

}