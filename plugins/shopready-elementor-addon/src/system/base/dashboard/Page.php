<?php
namespace Shop_Ready\system\base\dashboard;

class Page
{

    public function get_servey_questions()
    {

        return [
            'no-longer' => 'I no longer need the plugin',
            'found-better-plugin' => 'I found a better plugin',
            'plugin-not-working' => "I couldn't get the plugin to work",
            'temporary-deactivation' => "It's a temporary deactivation",
            'have-another-builder-plugin' => "I have another builder plugin",
            'need-better-design' => "need better design and presets"
        ];

    }
    public function register()
    {

        add_action('admin_enqueue_scripts', [$this, 'add_admin_scripts']);
        add_action('admin_menu', [$this, 'dashboard_menu_page']);
        add_action('network_admin_menu', [$this, 'dashboard_menu_page']);
        add_action('woo_ready_admin_message', [$this, 'admin_footer_popup_html']);
        add_action('admin_footer', [$this, 'servey_footer']);
    }

    public function proceed()
    {

        global $current_screen;
        if (
            isset($current_screen->parent_file)
            && $current_screen->parent_file == 'plugins.php'
            && isset($current_screen->id)
            && $current_screen->id == 'plugins'
        ) {
            return true;
        }
        return false;

    }
    public function servey_footer()
    {
        if (!$this->proceed()) {
            return;
        }
        ?>
        <div class="shop-ready-deactivate-servey-overlay" id="shop-ready-deactivate-servey-overlay"></div>
        <div class="shop-ready-deactivate-servey-modal" id="shop-ready-deactivate-servey-modal">
            <header>
                <div class="shop-ready-deactivate-servey-header">
                    <img src="<?php echo esc_url(SHOP_READY_PUBLIC_ROOT_IMG . 'logo.svg'); ?>" />
                    <h3>
                        <?php echo esc_html__('ShopReady', 'shopready-elementor-addon'); ?>
                    </h3>
                </div>
            </header>
            <div class="shop-ready-deactivate-info">
                <?php echo esc_html__('If you have a moment, please share why you are deactivating ShopReady:', 'shopready-elementor-addon') ?>
            </div>
            <div class="shop-ready-deactivate-content-wrapper">
                <form onSubmit="document.getElementById('submit').disabled=true;" action="#"
                    class="shop-ready-deactivate-form-wrapper">
                    <?php foreach ($this->get_servey_questions() as $key => $label): ?>
                        <div class="shop-ready-deactivate-input-wrapper">
                            <input id="shop-ready-deactivate-feedback-<?php echo esc_attr($key); ?>"
                                class="shop-ready-deactivate-feedback-dialog-input" type="radio" name="reason_key"
                                value="<?php echo esc_attr($label); ?>">
                            <label for="shop-ready-deactivate-feedback-<?php echo esc_attr($key); ?>"
                                class="shop-ready-deactivate-feedback-dialog-label"><?php echo esc_html($label); ?></label>
                        </div>
                    <?php endforeach; ?>
                    <div class="shop-ready-deactivate-input-wrapper">
                        <input id="shop-ready-deactivate-feedback-other" class="shop-ready-deactivate-feedback-dialog-input"
                            type="radio" name="reason_key" value="other">
                        <div class="other">
                            <label for="shop-ready-deactivate-feedback-other"
                                class="shop-ready-deactivate-feedback-dialog-label">Others</label>
                            <input class="shop-ready-feedback-text" type="text" name="reason_other"
                                placeholder="Please share the reason">
                        </div>
                    </div>
                    <div class="shop-ready-deactivate-footer">
                        <button id="shop-ready-dialog-lightbox-submit" class="shop-ready-dialog-lightbox-submit">Submit &amp;
                            Deactivate</button>
                        <button id="shop-ready-dialog-lightbox-skip" class="shop-ready-dialog-lightbox-skip">Skip &
                            Deactivate</button>
                    </div>
                </form>
            </div>

        </div>
        <?php
    }

    public function admin_footer_popup_html()
    {
        require_once(__DIR__ . '/views/modal-content.php');
    }

    public function add_admin_scripts($handle)
    {

        if ($handle == 'toplevel_page_' . SHOP_READY_SETTING_PATH) {
            wp_enqueue_style('shop-ready-admin-base');
            wp_enqueue_style('shop-ready-admin-grid');
            wp_enqueue_style('bvselect');
            wp_enqueue_script('bvselect');
            wp_enqueue_script('shop-ready-admin-dashboard');
        }

        if ('plugins.php' == $handle) {
            wp_enqueue_style('shopready-plugin-servey-admin', SHOP_READY_URL . 'assets/admin/css/plugin-servey.css');
            wp_enqueue_script('shopready-plugin-servey', SHOP_READY_URL . 'assets/admin/js/plugin-servey.js', array('jquery'), time(), true);
        }
    }

    public function dashboard_content()
    {
        require_once(__DIR__ . '/views/dashboard.php');
    }

    function dashboard_menu_page()
    {

        if (!current_user_can('edit_users')) {
            return;
        }

        add_menu_page(
            esc_html__('ShopReady', 'shopready-elementor-addon'),
            esc_html__('ShopReady', 'shopready-elementor-addon'),
            'manage_options',
            SHOP_READY_SETTING_PATH,
            [$this, 'dashboard_content'],
            SHOP_READY_PUBLIC_ROOT_IMG . 'logo.svg',
            4
        );

        add_submenu_page(
            SHOP_READY_SETTING_PATH,
            esc_html__('Support', 'shopready-elementor-addon'),
            esc_html__('Support', 'shopready-elementor-addon'),
            'manage_options',
            'https://support.quomodosoft.com/support/login',
            '',
            500
        );

        add_submenu_page(
            SHOP_READY_SETTING_PATH,
            esc_html__('Documentation', 'shopready-elementor-addon'),
            esc_html__('Documentation', 'shopready-elementor-addon'),
            'manage_options',
            'https://quomodosoft.com/plugins-docs/',
            '',
            500
        );

        if (defined('SHOP_READY_PRO_VERSION')) {

            add_submenu_page(
                SHOP_READY_SETTING_PATH,
                esc_html__('MyAccount', 'shopready-elementor-addon'),
                esc_html__('My Account', 'shopready-elementor-addon'),
                'manage_options',
                'https://quomodosoft.com/my-account/',
                '',
                499
            );

        }

        $installed_plugins = array_keys(get_plugins());

        if (in_array('shop-ready-pro/shop-ready-pro.php', $installed_plugins)) {
            return;
        }

        if (in_array('shop-ready-pro-bundle/shop-ready-pro.php', $installed_plugins)) {
            return;
        }

        if (defined('SHOP_READY_PRO')) {
            return;
        }

        add_submenu_page(
            SHOP_READY_SETTING_PATH,
            esc_html__('Go Pro', 'shopready-elementor-addon'),
            esc_html__('Go Pro 🔥', 'shopready-elementor-addon'),
            'manage_options',
            SHOP_READY_DEMO_URL,
            '',
            100
        );

    }

}