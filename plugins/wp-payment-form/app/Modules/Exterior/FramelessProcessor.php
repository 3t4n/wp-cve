<?php

namespace WPPayForm\App\Modules\Exterior;

use WPPayForm\Framework\Foundation\App;
use WPPayForm\Framework\Support\Arr;

class FramelessProcessor
{
    public function init()
    {
        if (!isset($_GET['wpf_page']) || $_GET['wpf_page'] != 'frameless') {
            // It's not our page. so skip altogether
            return;
        }
        $action = sanitize_text_field($_REQUEST['wpf_action']);
        $this->processFramePage($action);
    }


    public function processFramePage($action = '')
    {
        if (!$action) {
            return;
        }
        status_header(200);
        do_action('wppayform/frameless_pre_render_page', $action);
        do_action('wppayform/frameless_pre_render_page_' . $action, $action);

        ob_start();
        do_action('wppayform/frameless_body', $action);
        do_action('wppayform/frameless_body_' . $action, $action);
        $body = ob_get_clean();

        $this->loadHeader($action);
        echo wppayform_sanitize_html($body);
        $this->loadFooter($action);
        exit(200);
    }

    private function loadHeader($action)
    {
        $app = App::getInstance();
        $title = get_bloginfo('name');

        $title = __('Payment Success', 'wp-payment-form') . ' - ' . $title;

        $title = apply_filters('wppayform/frameless_browser_title', $title, $action);

        $headerJsFiles = [
            includes_url('js/jquery/jquery.js?ver=1.12.4-wp'),
            includes_url('js/jquery/jquery-migrate.min.js?ver=1.4.1')
        ];

        $headerJsFiles = apply_filters('wppayform/frameless_header_scripts', $headerJsFiles, $action);

        $cssFiles = [
            WPPAYFORM_URL . 'assets/css/frameless.css'
        ];


        $stripeSettings = get_option('wppayform_stripe_payment_settings', array());

        $companyName = Arr::get($stripeSettings, 'company_name');
        $checkoutLogo = Arr::get($stripeSettings, 'checkout_logo');

        if (!$companyName) {
            $companyName = get_bloginfo('name');
        }


        $cssFiles = apply_filters('wppayform/frameless_header_css_files', $cssFiles, $action);

        $app->view->render('frameless.header', [
            'css_files'    => $cssFiles,
            'js_files'     => $headerJsFiles,
            'title'        => $title,
            'action'       => $action,
            'site_logo'    => $checkoutLogo,
            'company_name' => $companyName
        ]);
    }

    private function loadFooter($action)
    {
        $app = App::getInstance();
        $footerJsFiles = [];
        $footerJsFiles = apply_filters('wppayform/frameless_header_scripts', $footerJsFiles, $action);

        $app->view->render('frameless.footer', [
            'js_files' => $footerJsFiles,
            'action'   => $action
        ]);
    }
}
