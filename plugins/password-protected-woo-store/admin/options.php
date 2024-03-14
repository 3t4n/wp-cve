<?php
include(WPPS_PLUGIN_DIR_PATH . 'admin/setting/general-setting.php');
include(WPPS_PLUGIN_DIR_PATH . 'admin/setting/page-setting.php');
include(WPPS_PLUGIN_DIR_PATH . 'admin/setting/product-categories-setting.php');
include(WPPS_PLUGIN_DIR_PATH . 'admin/setting/form-content.php');
include(WPPS_PLUGIN_DIR_PATH . 'admin/setting/form-desgin.php');
include(WPPS_PLUGIN_DIR_PATH . 'admin/setting/advanced.php');

$default_tab = null;
$tab = "";

$tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : $default_tab;

if (!class_exists('ppws_password_protected_store_settings')) {
    if ($tab == null) { //ppws-whole-site
        $ppws_whole_site_class = new ppws_whole_site_settings();
        add_action('admin_init', array($ppws_whole_site_class, 'ppws_whole_site_register_settings_init'));
    }

    if ($tab == 'page-setting') {
        $ppws_page_class = new ppws_page_settings();
        add_action('admin_init', array($ppws_page_class, 'ppws_page_register_settings_init'));
    }

    if ($tab == 'product-categories') {
        $ppws_product_categories_class = new ppws_product_categories_settings();
        add_action('admin_init', array($ppws_product_categories_class, 'ppws_product_categories_register_settings_init'));
    }

    if ($tab == 'form-content') {        
        $ppws_form_class = new ppws_form_settings();
        add_action('admin_init', array($ppws_form_class, 'ppws_form_settings_register_settings_init'));
    }

    if ($tab == 'form-desgin') {
        $ppws_form_style_class = new ppws_form_style_settings();
        add_action('admin_init', array($ppws_form_style_class, 'ppws_form_style_settings_register_settings_init'));
    }

    if ($tab == 'advanced') {
        $ppws_advanced_class = new ppws_advanced_settings();
        add_action('admin_init', array($ppws_advanced_class, 'ppws_advanced_settings_init'));
    }

    class ppws_password_protected_store_settings 
    {
        public function __construct() 
        {
             add_action('admin_menu', array($this, 'ppws_admin_menu_setting_page'));
        }

         function ppws_admin_menu_setting_page()
        {
            add_submenu_page('woocommerce', 'Password Protected', 'Password Protected', 'manage_options', 'ppws-option-page', array($this, 'password_protected_store_callback'));
        }

        function password_protected_store_callback() 
        {
            $default_tab = null;
            $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : $default_tab;
            ?>
            <div class='ppws-main-box'>
                <div class='ppws-container'>
                    <div class='ppws-header'>
                        <h1 class='ppws-h1'> <?php _e('Password Protected Store for WooCommerce', 'password-protected-store-for-woocommerce'); ?> </h1>
                    </div>
                    <div class="ppws-option-section">
                        <div class="ppws-tabbing-box">
                            <ul class="ppws-tab-list nav-tab-wrapper">
                                <li>
                                    <a href="?page=ppws-option-page" class="nav-tab <?php if ($tab === null) :  ?>nav-tab-active <?php endif; ?>"> <?php _e('General', 'password-protected-store-for-woocommerce'); ?> </a>
                                </li>
                                <li>
                                    <a href="?page=ppws-option-page&tab=page-setting" class="nav-tab <?php if ($tab === 'page-setting') : ?>nav-tab-active<?php endif; ?>"><?php _e('Page', 'password-protected-store-for-woocommerce'); ?></a>
                                </li>
                                <li>
                                    <a href="?page=ppws-option-page&tab=product-categories" class="nav-tab <?php if ($tab === 'product-categories') : ?>nav-tab-active<?php endif; ?>"><?php _e('Product Categories', 'password-protected-store-for-woocommerce'); ?></a>
                                </li>                                
                                <li>
                                    <a href="?page=ppws-option-page&tab=form-content" class="nav-tab <?php if ($tab === 'form-content') : ?>nav-tab-active<?php endif; ?>"><?php _e('Form Content', 'password-protected-store-for-woocommerce'); ?></a>
                                </li>
                                <li>
                                    <a href="?page=ppws-option-page&tab=form-desgin" class="nav-tab <?php if ($tab === 'form-desgin') : ?>nav-tab-active<?php endif; ?>"><?php _e('Form Design', 'password-protected-store-for-woocommerce'); ?></a>
                                </li>
                                <li>
                                    <a href="?page=ppws-option-page&tab=advanced" class="nav-tab <?php if ($tab === 'advanced') : ?>nav-tab-active<?php endif; ?>"><?php _e('Advanced', 'password-protected-store-for-woocommerce'); ?></a>
                                </li>
                                <li class="ppws-pro-tab">
                                    <a href="javascript:void(0);" class="nav-tab"><?php _e('Single Categories', 'password-protected-store-for-woocommerce'); ?><div class="ppws-pro-tag-wp"><span class="ppws-pro-tag">pro</span></div></a>
                                </li>
                                <li class="ppws-pro-tab">
                                    <a href="javascript:void(0);" class="nav-tab"><?php _e('Single Product', 'password-protected-store-for-woocommerce'); ?><div class="ppws-pro-tag-wp"><span class="ppws-pro-tag">pro</span></div></a>
                                </li>
                                <li>
                                    <a href="?page=ppws-option-page&tab=get-pro" class="nav-tab <?php if ($tab === 'get-pro') : ?>nav-tab-active<?php endif; ?>"><?php _e('Get Pro', 'password-protected-store-for-woocommerce'); ?> <img width="17" height="17" src="<?php echo esc_url(WPPS_PLUGIN_URL . '/assets/images/crown.svg'); ?>" alt="Crown"></a>
                                </li>
                            </ul>
                        </div>
                        <div class="ppws-tabbing-option">
                            <?php
                            if ($tab == null) {
                                $ppws_whole_site_class = new ppws_whole_site_settings();
                                $ppws_whole_site_class->ppws_whole_site_callback();
                            }

                            if ($tab == 'page-setting') {
                                $ppws_page_class = new ppws_page_settings();
                                $ppws_page_class->ppws_page_callback();
                            }

                            if ($tab == 'product-categories') {
                                $ppws_product_categories_class = new ppws_product_categories_settings();
                                $ppws_product_categories_class->ppws_product_categories_callback();
                            }

                            if ($tab == 'form-content') {
                                $ppws_form_class = new ppws_form_settings();
                                $ppws_form_class->ppws_form_settings_callback();
                            }

                            if ($tab == 'form-desgin') {
                                $ppws_form_style_class = new ppws_form_style_settings();
                                $ppws_form_style_class->ppws_form_style_settings_callback();
                            }

                            if ($tab == 'advanced') {
                                $ppws_advanced_class = new ppws_advanced_settings();
                                $ppws_advanced_class->ppws_advanced_settings_callback();
                            }

                            if ($tab == 'get-pro') {
                                $this->ppws_pro_features();
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }

        function ppws_pro_features() {
            ?>
            <div class="ppws-get-pro-content">
                <div class="ppws-section">
                    <h2>Get Pro *</h2>
                    <div class="ppws-get-pro-list">
                        <ul class="ppws-get-pro-ul">
                            <li>
                                <div class="ppws-check-title">
                                    <span class="ppws-check-icon">✓</span> 
                                    <h4><?php _e( 'Protect Single Category', 'password-protected-store-for-woocommerce' ); ?></h4>
                                </div>
                                <div class="ppws-check-description-wrap">
                                    <div class="ppws-check-description">
                                        <span class="ppws-check-icon">✓</span>
                                        <p><?php _e( 'Set specific password to category', 'password-protected-store-for-woocommerce' ); ?></p>
                                    </div>
                                    <div class="ppws-check-description">
                                        <span class="ppws-check-icon">✓</span>
                                        <p><?php _e( 'Select user role to protect specific category', 'password-protected-store-for-woocommerce' ); ?></p>
                                    </div>
                                    <div class="ppws-check-description">
                                        <span class="ppws-check-icon">✓</span>
                                        <p><?php _e( 'Option to protect archive page', 'password-protected-store-for-woocommerce' ); ?></p>
                                    </div>
                                    <div class="ppws-check-description">
                                        <span class="ppws-check-icon">✓</span>
                                        <p><?php _e( 'Option to hide category products from loop', 'password-protected-store-for-woocommerce' ); ?></p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="ppws-check-title">
                                    <span class="ppws-check-icon">✓</span> 
                                    <h4><?php _e( 'Protect Single Product', 'password-protected-store-for-woocommerce' ); ?></h4>
                                </div>
                                <div class="ppws-check-description-wrap">
                                    <div class="ppws-check-description">
                                        <span class="ppws-check-icon">✓</span>
                                        <p><?php _e( 'Set specific password to product', 'password-protected-store-for-woocommerce' ); ?></p>
                                    </div>
                                    <div class="ppws-check-description">
                                        <span class="ppws-check-icon">✓</span>
                                        <p><?php _e( 'Select user role to protect specific product', 'password-protected-store-for-woocommerce' ); ?></p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="ppws-check-title">
                                    <span class="ppws-check-icon">✓</span> 
                                    <span><?php _e( 'Timely', 'password-protected-store-for-woocommerce' ); ?> <a href="https://geekcodelab.com/contact/" target="_blank"><?php _e( 'Support', 'password-protected-store-for-woocommerce' ); ?></a> 24/7.</span>
                                </div>
                            </li>
                            <li>
                                <div class="ppws-check-title">
                                    <span class="ppws-check-icon">✓</span> 
                                    <span><?php _e( 'Regular Updates.', 'password-protected-store-for-woocommerce' ); ?></span>
                                </div>
                            </li>
                            <li>
                                <div class="ppws-check-title">
                                    <span class="ppws-check-icon">✓</span> 
                                    <span><?php _e( 'Well Documented.', 'password-protected-store-for-woocommerce' ); ?></span>
                                </div>
                            </li>
                        </ul>
                        <a href="https://geekcodelab.com/wordpress-plugins/password-protected-store-for-woocommerce-pro/" target="_blank" class="ppws-sec-btn"><?php _e( 'Upgrade To Premium', 'password-protected-store-for-woocommerce' ); ?></a>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    new ppws_password_protected_store_settings();
}