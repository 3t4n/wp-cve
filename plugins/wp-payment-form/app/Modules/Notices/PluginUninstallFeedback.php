<?php


namespace WPPayForm\App\Modules\Notices;

use WPPayForm\Framework\Support\Arr;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * DashboardNotices
 * @since 3.7.1
 */
use WPPayForm\App\App;

class PluginUninstallFeedback
{
    public function renderFeedBackForm()
    {
        // Bailout.
        // if (!current_user_can('delete_plugins')) {
        //     give_die();
        // }

        // $app = App::getInstance();
        // return $app->view->render('admin/notice/deactivate-form', [
        //     'logo' => WPPAYFORM_URL . 'assets/images/icon.png',
        //     'nonce' => wp_create_nonce( 'paymattic_deactivation_nonce' )
        // ]);
    }
   
}
