<?php

namespace WPPayForm\App\Modules\Dashboard;

use WPPayForm\App\App;
use WPPayFormPro\Classes\Export;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ajax Handler Class
 * @since 1.0.0
 */
class Render
{
    public function render()
    {
        wp_enqueue_style(
            'wppayform_user_dashboard',
            WPPAYFORM_URL . 'assets/css/wppayform_user_dashboard.css',
            array(),
            WPPAYFORM_VERSION
        );

        wp_enqueue_script('wppayform_user_dashboard', WPPAYFORM_URL . 'assets/js/payforms-user_dashboard.js', array('jquery'), WPPAYFORM_VERSION, true);
        $donationItems = (new Export())->getDonationItem();

        ob_start();
        App::make('view')->render('user.dashboard');
        $view = ob_get_clean();
        return $view;
    }
}
