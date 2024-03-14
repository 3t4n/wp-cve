<?php

class Element_Ready_Page
{

    private static $instance = null;

    public function get_servey_questions()
    {

        return [
            'no-longer' => 'I no longer need the plugin',
            'found-better-plugin' => 'I found a better plugin',
            'plugin-not-working' => "I couldn't get the plugin to work",
            'temporary-deactivation' => "It's a temporary deactivation",
            'have-elementor-pro' => "I have Elementor Pro",
            'need-better-design' => "need better design and presets",
        ];
    }

    // The constructor is private
    // to prevent initiation with outer code.
    private function __construct()
    {

        add_action('admin_head', [$this, 'meta_admin_head']);
        add_action('admin_footer', [$this, 'servey_footer']);
        add_action('admin_enqueue_scripts', [$this, 'add_admin_scripts']);
        add_action('admin_menu', [$this, 'dashboard_menu_page']);
        add_action('network_admin_menu', [$this, 'dashboard_menu_page']);
        add_action('admin_post_element_ready_components_options', [$this, 'element_ready_components_options']);
        add_action('wp_ajax_element_ready_components_options', [$this, 'element_ready_components_options_ajax']);
        add_action('admin_post_element_ready_modules_options', [$this, 'element_ready_modules_options']);
        add_action('wp_ajax_element_ready_modules_options', [$this, 'element_ready_modules_options_ajax']);
        add_action('admin_post_element_ready_api_data_options', [$this, 'element_ready_api_data']);
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
        <div class="element-ready-deactivate-servey-overlay" id="element-ready-deactivate-servey-overlay"></div>
        <div class="element-ready-deactivate-servey-modal" id="element-ready-deactivate-servey-modal">
            <header>
                <div class="element-ready-deactivate-servey-header">
                    <img src="<?php echo esc_url(ELEMENT_READY_ROOT_IMG . 'icon.png'); ?>" />
                    <h3>
                        <?php echo esc_html__('ElementsReady', 'elements-ready-lite'); ?>
                    </h3>
                </div>
            </header>
            <div class="element-ready-deactivate-info">
                <?php echo esc_html__('If you have a moment, please share why you are deactivating Element Ready Lite:', 'element-ready-lite') ?>
            </div>
            <div class="element-ready-deactivate-content-wrapper">
                <form onSubmit="document.getElementById('submit').disabled=true;" action="#"
                    class="element-ready-deactivate-form-wrapper">
                    <?php foreach ($this->get_servey_questions() as $key => $label): ?>
                        <div class="element-ready-deactivate-input-wrapper">
                            <input id="element-ready-deactivate-feedback-<?php echo esc_attr($key); ?>"
                                class="element-ready-deactivate-feedback-dialog-input" type="radio" name="reason_key"
                                value="<?php echo esc_attr($label); ?>">
                            <label for="element-ready-deactivate-feedback-<?php echo esc_attr($key); ?>"
                                class="element-ready-deactivate-feedback-dialog-label">
                                <?php echo esc_html($label); ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                    <div class="element-ready-deactivate-input-wrapper">
                        <input id="element-ready-deactivate-feedback-other"
                            class="element-ready-deactivate-feedback-dialog-input" type="radio" name="reason_key" value="other">
                        <div class="other">
                            <label for="element-ready-deactivate-feedback-other"
                                class="element-ready-deactivate-feedback-dialog-label">
                                <?php echo esc_html__('Others', 'element-raedy-lite'); ?>
                            </label>
                            <input class="element-ready-feedback-text" type="text" name="reason_other"
                                placeholder="Please share the reason">
                        </div>
                    </div>
                    <div class="element-ready-deactivate-footer">
                        <button id="element-ready-dialog-lightbox-submit" class="element-ready-dialog-lightbox-submit">
                            <?php echo esc_html__('Submit &amp; Deactivate', 'element-ready-lite'); ?>
                        </button>
                        <button id="element-ready-dialog-lightbox-skip" class="element-ready-dialog-lightbox-skip">
                            <?php echo esc_html__('Skip & Deactivate', 'element-ready-lite'); ?>
                        </button>
                    </div>
                </form>
            </div>

        </div>
        <?php
    }

    public function meta_admin_head()
    {
        $allow_kses = [
            'meta' => [
                'NAME' => [],
                'content' => [],
            ],
            'link' => [
                'href' => [],
                'id' => [],
                'class' => []
            ]
        ];
        if (isset($_GET['page']) && $_GET['page'] == 'element_ready_elements_dashboard_page') {

            echo wp_kses('<meta NAME="robots" content="noindex, nofollow">
         <link href="https://elementsready.com/" />', $allow_kses);

        }

    }

    public function add_admin_scripts($handle)
    {

        if ($handle == 'toplevel_page_' . ELEMENT_READY_SETTING_PATH) {

            wp_enqueue_script('jquery-ui-tabs');
            wp_enqueue_style('element-ready-grid', ELEMENT_READY_ROOT_CSS . 'grid.css');
            wp_enqueue_style('magnific-popup', ELEMENT_READY_ROOT_CSS . 'magnific-popup.css');
            wp_enqueue_style('element-ready-admin', ELEMENT_READY_ROOT_CSS . 'admin.css');
            wp_enqueue_script('magnific-popup', ELEMENT_READY_ROOT_JS . 'jquery.magnific-popup.min.js', array('jquery'), ELEMENT_READY_VERSION, true);
            wp_enqueue_script('element-ready-admin', ELEMENT_READY_ROOT_JS . 'admin' . ELEMENT_READY_SCRIPT_VAR . 'js', array('jquery', 'jquery-ui-tabs', 'magnific-popup'), ELEMENT_READY_VERSION, true);

            wp_localize_script('element-ready-admin', 'element_ready_obj', [
                'active' => isset($_GET['tabs']) ? sanitize_text_field($_GET['tabs']) : 0,
                'ajax_url' => esc_url_raw(admin_url('admin-ajax.php'))
            ]);

        }

        if ('plugins.php' == $handle) {
            wp_enqueue_style('element-plugin-servey-admin', ELEMENT_READY_ROOT_CSS . 'plugin-servey.css');
            wp_enqueue_script('element-plugin-servey', ELEMENT_READY_ROOT_JS . 'plugin-servey.js', array('jquery'), ELEMENT_READY_VERSION, true);
        }

    }

    function element_ready_api_data()
    {

        if (!isset($_POST['_element_ready_api_data']) || !wp_verify_nonce($_POST['_element_ready_api_data'], 'element-ready-api-data')) {
            wp_redirect(sanitize_url($_SERVER["HTTP_REFERER"]));
        }

        if (!isset($_POST['element-ready-api-data'])) {
            wp_redirect(sanitize_url($_SERVER["HTTP_REFERER"]));
        }

        // Save
        $data = map_deep($_POST['element-ready-api-data'], 'sanitize_text_field');
        $validate_options = $this->validate_options($data);

        update_option('element_ready_api_data', $validate_options);

        if (wp_doing_ajax()) {
            wp_die();
        } else {

            $url = $_SERVER["HTTP_REFERER"];
            $return_url = add_query_arg(
                array(
                    'tabs' => 3,
                ),
                $url
            );

            wp_redirect(sanitize_url($return_url));

        }
    }

    function element_ready_components_options_ajax()
    {

        if (!isset($_POST['_element_ready_components']) || !wp_verify_nonce($_POST['_element_ready_components'], 'element-ready-components')) {
            wp_send_json_error(__('Error!', 'element-ready-lite'));
        }

        if (isset($_POST['element-ready-components'])) {
            $value = map_deep($_POST['element-ready-components'], 'sanitize_text_field');
            $validate_options = $this->validate_options($value);
        } else {
            $validate_options = false;
        }

        update_option('element_ready_components', $validate_options);
        wp_send_json_success(__('Saved!', 'element-ready-lite'));
    }


    function element_ready_components_options()
    {

        // Verify if the nonce is valid
        if (!isset($_POST['_element_ready_components']) || !wp_verify_nonce($_POST['_element_ready_components'], 'element-ready-components')) {
            wp_redirect(sanitize_url($_SERVER["HTTP_REFERER"]));
        }

        if (!isset($_POST['element-ready-components'])) {
            wp_redirect(sanitize_url($_SERVER["HTTP_REFERER"]));
        }
        // Save
        if (isset($_POST['element-ready-components']['all-enable'])) {
            $validate_options = $this->validate_all_options($this->components(true), true);
        } else {
            $value = map_deep($_POST['element-ready-components'], 'sanitize_text_field');
            $validate_options = $this->validate_options($value);
        }
        update_option('element_ready_components', $validate_options);
        if (wp_doing_ajax()) {

            wp_die();
        } else {

            $url = sanitize_url($_SERVER["HTTP_REFERER"]);
            $return_url = add_query_arg(
                array(
                    'tabs' => 1,
                ),
                $url
            );
            wp_redirect(esc_url_raw($return_url));
        }

    }

    function element_ready_modules_options_ajax()
    {

        if (!isset($_POST['_element_ready_modules']) || !wp_verify_nonce($_POST['_element_ready_modules'], 'element-ready-modules')) {
            wp_send_json_error(__('Error!', 'element-ready-lite'));
        }

        if (!isset($_POST['element-ready-modules'])) {
            update_option('element_ready_modules', []);

            wp_send_json_error(__('Disable all Modules!', 'element-ready-lite'));
        }

        if (isset($_POST['element-ready-modules'])) {
            $values = map_deep($_POST['element-ready-modules'], 'sanitize_text_field');
            $validate_options = $this->validate_options($values);
        } else {
            $validate_options = false;
        }

        update_option('element_ready_modules', $validate_options);
        wp_send_json_success(__('Saved!', 'element-ready-lite'));
    }
    function element_ready_modules_options()
    {

        // Verify if the nonce is valid
        if (!isset($_POST['_element_ready_modules']) || !wp_verify_nonce($_POST['_element_ready_modules'], 'element-ready-modules')) {
            wp_redirect(sanitize_url($_SERVER["HTTP_REFERER"]));
        }

        if (!isset($_POST['element-ready-modules'])) {
            wp_redirect(sanitize_url($_SERVER["HTTP_REFERER"]));
        }

        if (!isset($_POST['element-ready-modules'])) {
            wp_redirect(sanitize_url($_SERVER["HTTP_REFERER"]));
        }

        // Save
        if (isset($_POST['element-ready-modules']['all-enable'])) {
            $validate_options = $this->validate_all_options($this->modules(true), true);
        } else {
            $values = map_deep($_POST['element-ready-modules'], 'sanitize_text_field');
            $validate_options = $this->validate_options($values);
        }
        update_option('element_ready_modules', $validate_options);

        if (wp_doing_ajax()) {
            wp_die();
        } else {

            $url = sanitize_url($_SERVER["HTTP_REFERER"]);
            $return_url = add_query_arg(
                array(
                    'tabs' => 2,
                ),
                $url
            );

            wp_redirect(esc_url_raw($return_url));
        }
    }

    public function validate_options($options = [], $all = false)
    {

        if (!is_array($options)) {
            return $options;
        }
        $return_options = [];
        foreach ($options as $key => $value) {
            if ($all) {
                if (isset($value['is_pro']) && $value['is_pro'] == 1) {
                    $return_options[$key] = 'on';
                } else {
                    $return_options[$key] = '';
                }
            } else {
                $return_options[$key] = sanitize_text_field($value);
            }
        }
        return $return_options;
    }

    public function validate_all_options($options = [], $all = false)
    {

        if (!is_array($options)) {
            return $options;
        }
        foreach ($options as $key => $value) {
            if ($all) {
                if (isset($value['is_pro']) && $value['is_pro'] == 1) {
                    unset($options[$key]);
                } else {
                    $options[$key] = 'on';
                }
            } else {
                $options[$key] = 'on';
            }
        }
        return $options;
    }

    public function get_transform_options($options = [], $key = false)
    {

        if (!is_array($options) || $key == false) {
            return $options;
        }
        $db_option = get_option($key);
        $return_options = $options;
        foreach ($options as $key => $value) {
            if (isset($db_option[$key])) {
                $return_options[$key]['default'] = 1;
            } else {
                $return_options[$key]['default'] = 0;
            }
        }
        return $return_options;
    }

    public function get_transform_inputs_options($options = [], $key = false)
    {

        if (!is_array($options) || $key == false) {
            return $options;
        }

        $db_option = get_option($key);
        $return_options = $options;
        foreach ($options as $key => $value) {
            if (isset($db_option[$key])) {
                $return_options[$key]['default'] = $db_option[$key];
            } else {
                $return_options[$key]['default'] = '';
            }
        }
        return $return_options;
    }

    public function components($all = false)
    {
        include(dirname(__FILE__) . '/controls/Components.php');
        if ($all) {
            return $return_arr;
        }
        $return_arr = $this->get_transform_options($return_arr, 'element_ready_components');
        return $return_arr;
    }

    public function modules($all = false)
    {
        include(dirname(__FILE__) . '/controls/Modules.php');
        if ($all) {
            return $return_arr;
        }
        $return_arr = $this->get_transform_options($return_arr, 'element_ready_modules');
        return $return_arr;
    }

    public function api_data()
    {
        include(dirname(__FILE__) . '/controls/Api.php');
        $return_arr = $this->get_transform_inputs_options($return_arr, 'element_ready_api_data');
        return $return_arr;
    }
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Element_Ready_Page();
        }
        return self::$instance;
    }

    public function dashboard_menu_page_content()
    {

        require_once(__DIR__ . '/views/dashboard.php');
    }

    function dashboard_menu_page()
    {

        add_menu_page(
            esc_html__('ElementsReady', 'element-ready-lite'),
            esc_html__('ElementsReady', 'element-ready-lite'),
            'manage_options',
            ELEMENT_READY_SETTING_PATH,
            [$this, 'dashboard_menu_page_content'],
            esc_url(ELEMENT_READY_ROOT_IMG . 'icon.png'),
            4
        );

        $installed_plugins = array_keys(get_plugins());
        if (!did_action('element_ready_pro_init')) {
            add_submenu_page(
                ELEMENT_READY_SETTING_PATH,
                esc_html__('Go Pro', 'element-ready-lite'),
                esc_html__('Go Pro 🔥', 'element-ready-lite'),
                'manage_options',
                esc_url(ELEMENT_READY_DEMO_URL . '#er-pricing'),
                '',
                100
            );
        }

        add_submenu_page(
            ELEMENT_READY_SETTING_PATH,
            esc_html__('Support', 'element-ready-lite'),
            esc_html__('Support', 'element-ready-lite'),
            'manage_options',
            'https://support.quomodosoft.com/support/login',
            '',
            500
        );

        add_submenu_page(
            ELEMENT_READY_SETTING_PATH,
            esc_html__('Documentation', 'element-ready-lite'),
            esc_html__('Documentation', 'element-ready-lite'),
            'manage_options',
            'https://elementsready.com/docs/element-ready',
            '',
            500
        );

        if (did_action('element_ready_pro_init')) {

            $path = 'https://elementsready.com/account/';
            if ('element-ready-pro' == ELEMENT_READY_PRO_BASE) {
                $path = 'https://elementsready.com/account/';
            } else {
                $path = 'https://quomodosoft.com/my-account/';
            }

            add_submenu_page(
                ELEMENT_READY_SETTING_PATH,
                esc_html__('MyAccount', 'element-ready-lite'),
                esc_html__('MyAccount', 'element-ready-lite'),
                'manage_options',
                esc_url($path),
                '',
                499
            );

        }


    }

    public function _submenu_order($_ord)
    {

        global $submenu;

        foreach ($submenu as $slug => $menu) {

            if (ELEMENT_READY_SETTING_PATH == $slug) {

                $arr = array();

                $arr[] = $submenu[$slug][0];
                $arr[] = $submenu[$slug][2];
                $arr[] = $submenu[$slug][3];
                if (isset($submenu[$slug][4])) {
                    $arr[] = $submenu[$slug][4];
                }
                if (isset($submenu[$slug][5])) {
                    $arr[] = $submenu[$slug][5];
                }
                if (isset($submenu[$slug][6])) {
                    $arr[] = $submenu[$slug][6];
                }
                if (isset($submenu[$slug][7])) {
                    $arr[] = $submenu[$slug][7];
                }
                $arr[] = $submenu[$slug][1];

                // // // Remove the originals
                unset($submenu[$slug][0]);
                unset($submenu[$slug][1]);
                unset($submenu[$slug][2]);
                unset($submenu[$slug][3]);
                if (isset($submenu[$slug][4])) {
                    unset($submenu[$slug][4]);
                }
                if (isset($submenu[$slug][5])) {
                    unset($submenu[$slug][5]);
                }
                if (isset($submenu[$slug][6])) {
                    unset($submenu[$slug][6]);
                }
                if (isset($submenu[$slug][7])) {
                    unset($submenu[$slug][7]);
                }
                // // // Add newly items to the list
                $submenu[$slug] += $arr;
            }
        }

        return $_ord;
    }

}
Element_Ready_Page::getInstance();