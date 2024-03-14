<?php
/*
Plugin Name: Wisepops
Description: Add Wisepops popups to your WordPress to effortlessly capture and engage web visitors and turn them into leads and happy customers.
Version: 1.3.0
Author: Wisepops
Author URI: https://wisepops.com
Licence: GPLv2
Requires PHP: 5.6
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class WisepopsPlugin
{
    const VERSION = '1.3.0';

    const APP_DOMAIN = [
        'production' => 'id.wisepops.com',
        'staging' => 'id.wisepops.tech',
        'local' => 'id.wisepops.localhost:3100'
    ];

    const LOADER_DOMAIN = [
        'production' => 'wisepops.net',
        'staging' => 'wisepops.ninja',
        'local' => 'wisepops.localhost:3200'
    ];

    public function __construct()
    {
        // This will call `add_admin_menu` function when the admin load
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('wp_footer', [$this, 'add_wp_footer']);
    }

    public function add_admin_menu()
    {
        // This is adding a top level menu in the admin, `menu_html` is called to create the page
        add_menu_page('Wisepops Configuration Page', 'Wisepops', 'manage_options', 'wisepops', [$this, 'menu_html']);
    }

    // Allow Admin to link his Wisepops account to his Wordpress site
    public function menu_html()
    {
        $redirect = false;
        if (isset($_GET['wisepops_unlink'])) {
            delete_option('wisepops_website_hash');
            $redirect = true;
        } else if (isset($_GET['wisepops_website_hash'])) {
            add_option('wisepops_website_hash', $_GET['wisepops_website_hash']);
            $redirect = true;
        } else if (isset($_GET['wisepops_env'])) {
            add_option('wisepops_env', $_GET['wisepops_env']);
            $redirect = true;
        }

        if ($redirect) {
            $adminUrl = admin_url('admin.php?page=wisepops');
            echo <<<HTML
                <script type="text/javascript">
                    document.location = "$adminUrl";
                </script>
HTML;
        } else {
            $this->printStatusPage();
        }
    }

    protected function printStatusPage()
    {
        echo '<h1>' . get_admin_page_title() .'</h1>';

        $websiteIdentifier = $this->getWebsiteIdentifier();
        $appDomain = $this->getAppDomain();

        // Acount not linked
        if (!$websiteIdentifier) {
            $linkUrl = '//' . $appDomain . '/api/wordpress/link'
                . '?redirect_url=' . rawurlencode(admin_url('admin.php?page=wisepops'))
                . '&website_url=' . rawurlencode(site_url())
                . '&v=' . self::VERSION;

            echo <<<HTML
                <p>Your Wisepops account is not linked to Wordpress.<p>
                <a href="$linkUrl">
                    <button class="button button-primary">Link it now!</button>
                </a>
HTML;

            return;
        }

        // Account is linked
        $hint = 'Your Wisepops account is currently linked to Wordpress ' . $websiteIdentifier;
        $unlinkUrl = admin_url() . 'admin.php?page=wisepops&wisepops_unlink=1';

        echo <<<HTML
            <p>$hint<p>
            <a href="$unlinkUrl">
                <button class="button button-danger">Unlink it</button>
            </a>
HTML;
    }

    public function add_wp_footer()
    {
        $websiteIdentifier = $this->getWebsiteIdentifier();
        if ($websiteIdentifier) {
            $loaderDomain = $this->getLoaderDomain();
            $trackingUrl = '//' . $loaderDomain . '/loader.js?v=2&' . $websiteIdentifier;

            echo <<<HTML
            <script data-cfasync="false">
                (function(w,i,s,e){window[w]=window[w]||function(){(window[w].q=window[w].q||[]).push(arguments)};window[w].l=Date.now();s=document.createElement('script');e=document.getElementsByTagName('script')[0];s.defer=1;s.src=i;e.parentNode.insertBefore(s, e)})
                ('wisepops', '$trackingUrl');
            </script>
HTML;
        }
    }

    private function getWebsiteIdentifier()
    {
        if ($wisepopsWebsiteHash = get_option("wisepops_website_hash")) {
            return 'h=' . $wisepopsWebsiteHash;
        } else {
            return null;
        }
    }

    private function getAppDomain()
    {
        $env = get_option("wisepops_env", "production");
        return self::APP_DOMAIN[$env];
    }

    private function getLoaderDomain()
    {
        $env = get_option("wisepops_env", "production");
        return self::LOADER_DOMAIN[$env];
    }
}

new WisepopsPlugin();
