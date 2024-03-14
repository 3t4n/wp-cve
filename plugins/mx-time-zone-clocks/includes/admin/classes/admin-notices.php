<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class MXMTZCAdminNotices
{

    public static function registerAjaxActions()
    {

        add_action('wp_ajax_mxmtzc_dismiss_admin_notice', ['MXMTZCAdminNotices', 'dismissAdminNotice'], 10, 1);

        add_action('wp_ajax_olena_theme_notice_viewed', ['MXMTZCAdminNotices','ajax_olena_theme_notice_viewed']);
    }

    /**
     * Set Admin Notice as Viewed.
     *
     * @return void
     */
    public static function ajax_olena_theme_notice_viewed()
    {
        update_user_meta(get_current_user_id(), '_olena_theme_install_notice_viewed', 'true');
        die;
    }

    public static function dismissAdminNotice()
    {

        // Checked POST nonce is not empty
        if (empty($_POST['nonce'])) wp_die('0');

        // Checked or nonce match
        if (wp_verify_nonce($_POST['nonce'], 'mxmtzc_nonce_request_admin')) {

            $noticeType = sanitize_text_field($_POST['notice']);

            if ($noticeType == 'hire_developer') {
                update_option('mxmtzc_hire_developer', 'dismissed');
            }
        }

        wp_die();
    }

    // 
    public static function intNotices()
    {
        add_action('admin_notices', ['MXMTZCAdminNotices', 'hireDeveloper']);
        add_action('admin_notices', ['MXMTZCAdminNotices', 'olenaTheme']);
    }

    public static function hireDeveloper()
    {

        if (isset($_GET['page'])) {

            if ($_GET['page'] == 'mxmtzc-mx-time-zone-clocks-menu') return;
        }

        if (get_option('mxmtzc_hire_developer')) return; //_olena_theme_install_notice_viewed
?>
        <div class="notice notice-success is-dismissible mxmtzc-admin-notice">
            <?php mxmtzc_include_view('components/hire-developer'); ?>
        </div>
<?php

    }

    public static function olenaTheme()
    {

        if (isset($_GET['page'])) {

            if ($_GET['page'] == 'mxmtzc-mx-time-zone-clocks-menu') return;
        }

        if ('true' === get_user_meta(get_current_user_id(), '_olena_theme_install_notice_viewed', true)) return;
?>
    <div class="notice notice-success is-dismissible olena-notification">
        <?php mxmtzc_include_view('components/olena-theme'); ?>
    </div>
        
<?php

    }

}
