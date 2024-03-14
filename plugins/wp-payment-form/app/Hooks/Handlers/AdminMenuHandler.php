<?php

namespace WPPayForm\App\Hooks\Handlers;

use WPPayForm\App\App;
use WPPayForm\App\Models\Submission;
use WPPayForm\App\Models\Form;
use WPPayForm\App\Services\GeneralSettings;
use WPPayForm\App\Services\AccessControl;
use WPPayForm\App\Services\CountryNames;
use WPPayForm\App\Modules\AddOnModules\AddOnModule;
use WPPayForm\App\Services\ProRoutes;
use WPPayForm\App\Services\TransStrings;
use WPPayForm\App\Services\GlobalTools;
class AdminMenuHandler
{
    public function add()
    {
        $menuPermission = AccessControl::hasTopLevelMenuPermission();
        if (!current_user_can($menuPermission)) {
            $accessStatus = AccessControl::giveCustomAccess();

            if ($accessStatus['has_access']) {
                $menuPermission = $accessStatus['role'];
            } else {
                return;
            }
        }
        // Check is paymattic user 
        // $current_user = wp_get_current_user();
        // $userRole = $current_user->roles[0];
        $title = __('Paymattic', 'wp-payment-form');
        if (defined('WPPAYFORMHASPRO')) {
            $title .= ' Pro';
        }
        global $submenu;
        add_menu_page(
            $title,
            $title,
            $menuPermission,
            'wppayform.php',
            array($this, 'render'),
            $this->getMenuIcon(),
            25
        );

        if (AccessControl::isPaymatticUser()) {
            $submenu['wppayform.php']['paymattic_dashboard'] = array(
                __('Dashboard', 'wp-payment-form'),
                $menuPermission,
                'admin.php?page=wppayform.php#/paymattic-dashboard',
            );
        }
        if (!AccessControl::isPaymatticUser()) {
            if (defined('WPPAYFORMHASPRO')) {
                $license = get_option('_wppayform_pro_license_status');

                if ('valid' != $license) {
                    $submenu['wppayform.php']['activate_license'] = array(
                        sprintf(
                            '<span style="color:#f39c12;">%s</span>',
                            esc_html__('Activate License', 'wp-payment-form')
                        ),
                        $menuPermission,
                        'admin.php?page=wppayform_settings#license',
                        '',
                        'wppayform_license_menu',
                    );
                }
            }
            $submenu['wppayform.php']['all_forms'] = array(
                __('All Forms', 'wp-payment-form'),
                $menuPermission,
                'admin.php?page=wppayform.php#/',
            );

            $submenu['wppayform.php']['create_new_forms'] = array(
                __('Create Form', 'wp-payment-form'),
                $menuPermission,
                'admin.php?page=wppayform.php#/new-form',
            );

            $entriesTitle = __('Entries', 'wp-payment-form');
            if (isset($_GET['page']) && in_array($_GET['page'], ['wppayform.php', 'wppayform_settings'])) {
                $entriesCount = 0;
                $entriesCount = (new Submission())->getNewEntriesCount();
                if ($entriesCount) {
                    $entriesTitle .= ' <span class="wpf_unread_count" style="background: #e89d2d;color: white;border-radius: 8px;padding: 1px 8px;">' . $entriesCount . '</span>';
                }
            }

            $submenu['wppayform.php']['entries'] = array(
                $entriesTitle,
                $menuPermission,
                'admin.php?page=wppayform.php#/entries',
            );

            // if (defined('WPPAYFORMHASPRO')) {
            $submenu['wppayform.php']['reports'] = array(
                __('Reports', 'wp-payment-form'),
                $menuPermission,
                'admin.php?page=wppayform.php#/reports',
            );
            $submenu['wppayform.php']['customers'] = array(
                __('Customers', 'wp-payment-form'),
                $menuPermission,
                'admin.php?page=wppayform.php#/customers',
            );
            // }

            $submenu['wppayform.php']['integrations'] = array(
                __('Integrations', 'wp-payment-form'),
                $menuPermission,
                'admin.php?page=wppayform.php#/integrations',
            );

            $submenu['wppayform.php']['gateways'] = array(
                __('Payment Gateway', 'wp-payment-form'),
                $menuPermission,
                'admin.php?page=wppayform.php#/gateways/stripe',
            );

            add_submenu_page(
                'wppayform.php',
                __('Settings', 'wp-payment-form'),
                __('Settings', 'wp-payment-form'),
                $menuPermission,
                'wppayform_settings',
                array($this, 'renderGlobalSettings')
            );

            if (!defined('WPPAYFORMHASPRO')) {
                $submenu['wppayform.php']['upgrade_to_pro'] = array(
                    '<span style="color: #e89d2c;">Upgrade To Pro</span>',
                    $menuPermission,
                    'https://wpmanageninja.com/downloads/wppayform-pro-wordpress-payments-form-builder/?utm_source=plugin&utm_medium=menu&utm_campaign=upgrade',
                );
            }

            $submenu['wppayform.php']['support'] = array(
                __('Support & Debug', 'wp-payment-form'),
                $menuPermission,
                'admin.php?page=wppayform.php#/support',
            );
        }
    }

    public function isPaymatticUser($userRole, $menuPermission)
    {
        if ($userRole == 'paymattic_donor' || $userRole == 'paymattic_user' || $userRole == 'paymattic_subscriber') {
            $this->is_paymattic_user = true;
            return true;
        }
        $this->is_paymattic_user = false;
        return false;
    }

    public function renderGlobalSettings()
    {
        if (function_exists('wp_enqueue_editor')) {
            add_filter('user_can_richedit', '__return_true');
            wp_enqueue_editor();
            wp_enqueue_media();
        }

        // Fire an event letting others know the current component
        // that wppayform is rendering for the global settings
        // page. So that they can hook and load their custom
        // components on this page dynamically & easily.
        // N.B. native 'components' will always use
        // 'settings' as their current component.
        $paymatticPages = isset($_REQUEST['page']) ? $_REQUEST['page'] : [];
        $currentComponent = apply_filters(
            'wppayform_global_settings_current_component',
            $paymatticPages
        );

        $currentComponent = sanitize_key($currentComponent);
        $components = apply_filters('wppayform_global_settings_components', []);

        $assetUrl = WPPAYFORM_URL . 'assets/images/global';

        $defaultCom = array(
            'coupons' => [
                'hash' => 'coupons',
                'title' => 'Coupons',
                'svg' => '<img src="' . $assetUrl . '/coupon.svg"/>',
            ],
            'reCAPTCHA' => [
                'hash' => 're_captcha',
                'title' => 'reCAPTCHA',
                'svg' => '<img src="' . $assetUrl . '/reCAPTCHA.svg"/>',
            ],
            'turnstile' => [
                'hash' => 'turnstile',
                'title' => 'Turnstile(beta)',
                'svg' => '<img src="' . $assetUrl . '/turnstile.svg"/>',
            ],
            'tool' => [
                'hash' => 'tools',
                'title' => 'Tools',
                'svg' => '<img  src="' . $assetUrl . '/tools.svg"/>',
            ],
            'permission' => [
                'hash' => 'permission',
                'title' => 'Permission',
                'svg' => '<img src="' . $assetUrl . '/permission.svg"/>',
            ],
            'user_dashboard' => [
                'hash' => 'user_dashboard',
                'title' => 'User Dashboard',
                'svg' => '<img src="' . $assetUrl . '/user_dashboard.svg"/>',
            ],
            'donor_leaderboard' => [
                'hash' => 'donor_leaderboard',
                'title' => 'Donor Leaderboard',
                'svg' => '<img src="' . $assetUrl . '/donor_leaderboard.svg"/>',
            ],
            'pdf_settings' => [
                "hash" => "pdf_settings",
                "title" => __("PDF Settings", 'wp-payment-form'),
                "svg"   => '<img  src="' . WPPAYFORM_URL . 'assets/images/form/pdf.svg"/>' 
            ]
        );


        if (defined('WPPAYFORMHASPRO')) {
            $defaultCom['licensing'] = [
                'hash' => 'license',
                'title' => 'Licensing',
                'svg' => '<img  src="' . $assetUrl . '/licensing.svg"/>',
            ];
        }

        $components = array_merge($defaultCom, $components);
        App::make('view')->render('admin.settings.index', [
            'components' => $components,
            'currentComponent' => $currentComponent,
        ]);
    }

    public function render()
    {
        $this->enqueueAssets();

        $config = App::getInstance('config');
        $name = $config->get('app.name');
        $slug = 'wppayform';

        App::make('view')->render('admin.menu', compact('name', 'slug'));
    }

    public function renderSettings()
    {
        $this->enqueueAssets();

        $config = App::getInstance('config');
        $name = $config->get('app.name');
        $slug = 'wppayform';

        App::make('view')->render('admin.settings.settings', compact('name', 'slug'));
    }

    public function enqueueAssets()
    {
        $app = App::getInstance();

        $assets = $app['url.assets'];

        $slug = 'wppayform';

        do_action($slug . '_loading_app');

        $wpfPages = ['wppayform.php', 'wppayform_settings'];

        if (isset($_GET['page']) && in_array($_GET['page'], $wpfPages)) {
            if (!apply_filters($slug . '/disable_admin_footer_alter', false)) {
                add_filter('admin_footer_text', function ($text) {
                    $link = 'https://paymattic.com/';
                    return 'Thank you for using <a target="_blank" href="' . $link . '">Paymattic</a>';
                });

                add_filter('update_footer', function ($text) {
                    $footerContent = 'Paymattic Version ' . WPPAYFORM_VERSION;
                    if (defined('WPPAYFORMPRO_VERSION')) {
                        $footerContent .= ' & Pro Version ' . WPPAYFORMPRO_VERSION;
                    }
                    return $footerContent;
                });
            }

            if (function_exists('wp_enqueue_editor')) {
                wp_enqueue_editor();
                wp_enqueue_script('thickbox');
            }
            if (function_exists('wp_enqueue_media')) {
                wp_enqueue_media();
            }

            if ('wppayform_settings' === $_GET['page']) {
                $this->loadSettingsAssets();
            } else {
                wp_enqueue_script(
                    'wppayform_boot',
                    WPPAYFORM_URL . 'assets/js/payforms-boot.js',
                    array('jquery'),
                    WPPAYFORM_VERSION,
                    true
                );

                // 3rd party developers can now add their scripts here
                do_action($slug . '/booting_admin_app');
                wp_enqueue_script(
                    $slug . '_admin_app',
                    WPPAYFORM_URL . 'assets/js/payforms-admin.js',
                    array('wppayform_boot'),
                    WPPAYFORM_VERSION,
                    true
                );
            }

            $current_user = wp_get_current_user();
            $capabilities = $current_user->allcaps;

            $payment_methods = apply_filters('wppayform_payment_method_settings', ProRoutes::getMethods());
            $payment_routes = apply_filters('wppayform_payment_method_settings_routes', ProRoutes::getRoutes());
            $paymentAddons = apply_filters('wppayform/available_payment_addons', ProRoutes::getPaymentAddons());
            $downloadable_font_files = [];
            $fluent_pdf_update_available = 'no';

            if (defined('FLUENT_PDF')) {
                require_once FLUENT_PDF_PATH . '/Classes/Controller/FontDownloader.php';
                $downloadable_font_files = (new \FluentPdf\Classes\Controller\FontDownloader())->getDownloadableFonts();
                $reuslt = (new \FluentPdf\Classes\Controller\GlobalPdfConfig())->checkForUpdate('fluent-pdf');
                $fluent_pdf_update_available = $reuslt['available'];
                $fluent_pdf_active = "yes";
                $fluent_pdf_url = $reuslt['url'] ? $reuslt['url'] : 'https://api.github.com/repos/WPManageNinja/fluent-pdf/zipball/1.0.0';
           } else {
                $fluent_pdf_active = "no";
                $fluent_pdf_url = 'https://api.github.com/repos/WPManageNinja/fluent-pdf/zipball/1.0.0';
           }

            $payformAdminVars = apply_filters(
                $slug . '/admin_app_vars',
                array(
                    // 'i18n' => array(
                    //     'All Payment Form' => __('All Payment Form', 'wp-payment-form'),
                    // ),
                    'wpf_admin_nonce' => wp_create_nonce('wpf_admin_nonce'),
                    'paymentStatuses' => GeneralSettings::getPaymentStatuses(),
                    'entryStatuses' => GeneralSettings::getEntryStatuses(),
                    'image_upload_url' => admin_url('admin-ajax.php?action=wpf_global_settings_handler&route=wpf_upload_image'),
                    'forms_count' => Form::getTotalCount(),
                    'assets_url' => WPPAYFORM_URL . 'assets/',
                    'has_pro' => defined('WPPAYFORMHASPRO') && WPPAYFORMHASPRO,
                    'hasValidLicense' => get_option('_wppayform_pro_license_status'),
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'ipn_url' => site_url('?wpf_paypal_ipn=1'),
                    'printStyles' => apply_filters($slug . '/print_styles', []),
                    'ace_path_url' => WPPAYFORM_URL . 'assets/libs/ace',
                    'icon_url' => WPPAYFORM_URL . 'assets/images/icon.png',
                    'countries' => CountryNames::getAll(),
                    'value_placeholders' => [],
                    'slug' => $slug,
                    'nonce' => wp_create_nonce('wppayform'),
                    'rest' => $this->getRestInfo($app),
                    'brand_logo' => $this->getMenuIcon(),
                    'asset_url' => $assets,
                    'wppayform_addon_modules' => AddOnModule::showAddOns(),
                    'submission_types' => GeneralSettings::getActivityTypes(),
                    'entries_count' => (new Submission())->count(),
                    'payment_methods' => $payment_methods,
                    'payment_routes' => $payment_routes,
                    'fluent_pdf' => true,
                    'fluent_pdf_active' => $fluent_pdf_active,
                    'fluent_pdf_url' => $fluent_pdf_url,
                    'downloadable_font_files' => $downloadable_font_files,
                    'fluent_pdf_update_available' => $fluent_pdf_update_available,
                    'fluent_pdf_dashboard_url' => admin_url('admin.php?page=fluent_pdf.php'),
                    'is_paymattic_user' => AccessControl::isPaymatticUser(),
                    'user_email' => wp_get_current_user()->get('user_email'),
                    'user_capabilities' => $capabilities,
                    'payment_addons' => $paymentAddons,
                    'currencies' => GeneralSettings::getCurrencies(),
                    'i18n' => TransStrings::getStrings()
                )
            );

            // $payformAdminVars = array_merge($routes, $payformAdminVars);
            wp_localize_script($slug . '_boot', 'wpPayFormsAdmin', $payformAdminVars);

        }
    }

    public function mapperSettings()
    {
        return array(
            'routes' => 'hello'
        );
    }

    public function loadSettingsAssets()
    {
        wp_enqueue_script(
            'wppayform_boot',
            WPPAYFORM_URL . 'assets/js/settings-app.js',
            array('jquery'),
            WPPAYFORM_VERSION,
            true
        );
    }

    protected function getRestInfo($app)
    {
        $ns = $app->config->get('app.rest_namespace');
        $ver = $app->config->get('app.rest_version');

        return [
            'base_url' => esc_url_raw(rest_url()),
            'url' => rest_url($ns . '/' . $ver),
            'nonce' => wp_create_nonce('wp_rest'),
            'namespace' => $ns,
            'version' => $ver,
        ];
    }

    public function renderGlobalMenu()
    {
        App::make('view')->render(
            'admin.global.global_menu',
            array(
                'brand_logo' => WPPAYFORM_URL . 'assets/images/icon.png',
                'is_paymattic_user' => AccessControl::isPaymatticUser(),
            )
        );
    }

    public function adminBarItem()
    {
        global $wp_admin_bar;

        $menuPermission = AccessControl::hasTopLevelMenuPermission();
        if (!current_user_can($menuPermission)) {
            $accessStatus = AccessControl::giveCustomAccess();

            if ($accessStatus['has_access']) {
                $menuPermission = $accessStatus['role'];
            } else {
                // Check also is paymattic user
                if (AccessControl::isPaymatticUser()) {
                    // Hook the custom function into the template_include action
                    $activePage = get_option('_wppayform_user_dashboard_page', 'Paymattic Dashboard');
                    $public_url = site_url('/' . GlobalTools::convertToSnakeCase($activePage));

                    $wp_admin_bar->add_menu(
                        array(
                            'id' => 'wp-payment-form',
                            'parent' => null,
                            'group' => null,
                            'title' => '<span style="display: flex;"><img style="width:18px;" src="' . $this->getMenuIcon() . '"/> <span>Paymattic Dashboard</span></span>',
                            'href' => $public_url,
                            'meta' => [
                                'title' => __('Paymattic User Profile', 'wp-payment-form')
                            ]
                        )
                    );


                    return;

                } else {
                    return;
                }
            }
        }

        if (!current_user_can('manage_options')) {
            return;
        }

        $wp_admin_bar->add_menu(
            array(
                'id' => 'wp-payment-form',
                'parent' => null,
                'group' => null,
                'title' => '<span style="display: flex;"><img style="width:18px;" src="' . $this->getMenuIcon() . '"/> <span>Paymattic</span></span>',
                'href' => admin_url('admin.php?page=wppayform.php'),
                'meta' => [
                    'title' => __('Paymattic', 'wp-payment-form')
                ]
            )
        );

        // Sub menu
        $wp_admin_bar->add_menu(
            array(
                'id' => 'wpf-entries',
                'parent' => 'wp-payment-form',
                'title' => 'Entries',
                'href' => admin_url('admin.php?page=wppayform.php#/entries'),
            )
        );

        // Sub menu
        $wp_admin_bar->add_menu(
            array(
                'id' => 'wpf-create_new_forms',
                'parent' => 'wp-payment-form',
                'title' => 'Create form',
                'href' => admin_url('admin.php?page=wppayform.php#/new-form'),
            )
        );

        // Sub menu
        $wp_admin_bar->add_menu(
            array(
                'id' => 'wpf-customers',
                'parent' => 'wp-payment-form',
                'title' => 'Customers',
                'href' => admin_url('admin.php?page=wppayform.php#/customers'),
            )
        );

        $wp_admin_bar->add_menu(
            array(
                'id' => 'wpf-new_forms',
                'parent' => 'new-content',
                'title' => 'Form',
                'href' => admin_url('admin.php?page=wppayform.php#/new-form'),
            )
        );
    }

    protected function getMenuIcon()
    {
        $svg = '<?xml version="1.0" encoding="utf-8"?>
        <!-- Generator: Adobe Illustrator 24.2.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
             viewBox="0 0 20 20" style="enable-background:new 0 0 20 20;" xml:space="preserve">
        <style type="text/css">
            .st0{opacity:0.5;fill:#FFFFFF;enable-background:new;}
            .st1{fill:#FFFFFF;}
        </style>
        <g id="Layer_2_2_">
            <g id="Layer_1-2_1_">
                <path class="st0" d="M16.1,1.3v10c0,0.7-0.4,1.3-1.1,1.5l-3.8,1.3V9.9l1.4-0.5C13,9.3,13.3,9,13.3,8.6V5.9c0-0.2-0.2-0.4-0.4-0.4
                    c0,0-0.1,0-0.1,0L8.9,6.8L5,5.5l0,0c-0.4-0.1-0.7-0.6-0.5-1C4.5,4.2,4.7,4.1,4.8,4L5,3.9l9.4-3.1h0l0.7-0.2c0.4-0.1,0.9,0.1,1,0.5
                    C16.1,1.1,16.1,1.2,16.1,1.3z"/>
                <path class="st1" d="M10.1,7.2L5,5.5l0,0c-0.4-0.1-0.7-0.6-0.5-1C4.5,4.2,4.6,4,4.8,3.9c-0.6,0.3-1,0.9-1,1.5v10.8
                    c0,0.7,0.4,1.3,1.1,1.5l5.2,1.7c0.4,0.1,0.9-0.1,1-0.5c0-0.1,0-0.2,0-0.3V8.7C11.2,8,10.8,7.4,10.1,7.2z"/>
            </g>
        </g>
        </svg>';
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
