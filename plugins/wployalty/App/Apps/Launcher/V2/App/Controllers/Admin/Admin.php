<?php
/**
 * @author      Wployalty (Ilaiyaraja)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wll\V2\App\Controllers\Admin;

use Wll\V2\App\Controllers\Base;
use Wll\V2\App\Controllers\Guest;
use Wll\V2\App\Controllers\Member;
use Wlr\App\Helpers\EarnCampaign;
use Wlr\App\Helpers\Woocommerce;
use Wlr\App\Models\Levels;

defined('ABSPATH') or die();

class Admin extends Base
{
    /**
     * Plugin Activate
     * @return bool
     */
    public function activatePlugin()
    {
        /*  $check = new LauncherCompatibleCheck();
          $check->init_check(true);*/
        return true;
    }

    /**
     * Adding main menu
     *
     * @return void
     */
    public function addAdminMenu()
    {
        if (Woocommerce::hasAdminPrivilege()) {
            add_menu_page(__('WPLoyalty-Launcher', 'wp-loyalty-rules'), __('WPLoyalty-Launcher', 'wp-loyalty-rules'), 'manage_woocommerce', WLL_PLUGIN_SLUG, array($this, 'addMenuPage'), 'dashicons-megaphone', 57);
        }
    }

    /**
     * Adding styles and scripts
     * @return void
     */
    public function enqueueAdminAssets()
    {
        if (self::$input->get('page', NULL) != WLL_PLUGIN_SLUG) {
            return;
        }
        $suffix = '.min';
        if (defined('SCRIPT_DEBUG')) {
            $suffix = SCRIPT_DEBUG ? '' : '.min';
        }
        $this->removeAdminNotice();
        // media library for launcher icon image
        wp_enqueue_media();
        wp_register_style(WLL_PLUGIN_SLUG . '-wlr-font', WLR_PLUGIN_URL . 'Assets/Site/Css/wlr-fonts' . $suffix . '.css', array(), WLR_PLUGIN_VERSION . '&t=' . time());
        wp_enqueue_style(WLL_PLUGIN_SLUG . '-wlr-font');
        wp_enqueue_style(WLR_PLUGIN_SLUG . '-alertify', WLR_PLUGIN_URL . 'Assets/Admin/Css/alertify.css', array(), WLR_PLUGIN_VERSION);
        wp_enqueue_script(WLR_PLUGIN_SLUG . '-alertify', WLR_PLUGIN_URL . 'Assets/Admin/Js/alertify.js', array(), WLR_PLUGIN_VERSION . '&t=' . time());
        $common_path = WLL_PLUGIN_DIR . '/V2/launcher-admin-ui/dist';
        $js_files = self::$woocommerce->getDirFileLists($common_path);
        $localize_name = "";
        foreach ($js_files as $file) {
            $path = str_replace(WLR_PLUGIN_PATH, '', $file);
            $js_file_name = str_replace($common_path . '/', '', $file);
            $js_name = WLR_PLUGIN_SLUG . '-react-ui-' . substr($js_file_name, 0, -3);
            $js_file_url = WLR_PLUGIN_URL . $path;
            if ($js_file_name == 'main.bundle.js') {
                $localize_name = $js_name;
                wp_register_script($js_name, $js_file_url, array('jquery'), WLR_PLUGIN_VERSION . '&t=' . time());
                wp_enqueue_script($js_name);
            }
        }

        //register the scripts
        $wll_localize_data = array(
            'ajax_url' => admin_url('admin-ajax.php'),
            //nonce
            'reset_setting_nonce' => wp_create_nonce('reset_settings'),
            'local_data_nonce' => wp_create_nonce('local_data'),
            'render_page_nonce' => wp_create_nonce('render_page_nonce'),
        );
        wp_localize_script($localize_name, 'wll_settings_form', $wll_localize_data);
    }

    function removeAdminNotice()
    {
        remove_all_actions('admin_notices');
    }

    /**
     * Adding menu page
     * @return void
     */
    public function addMenuPage()
    {
        if (!Woocommerce::hasAdminPrivilege()) {
            return;
        }
        $params = array();
        $params = apply_filters("wll_before_launcher_admin_page", $params);
        $path = WLL_PLUGIN_DIR . '/V2/App/Views/Admin/main.php';
        self::$template->setData($path, $params)->display();
    }

    /**
     * Getting launcher settings like design,content,popup button
     * @return void
     */
    function getLauncherSettings()
    {
        $response = array(
            'success' => false,
            'data' => array()
        );
        if (!$this->isLauncherSecurityValid('wll_launcher_settings')) {
            $response['data']['message'] = __('Security check failed', 'wp-loyalty-rules');
            wp_send_json($response);
        }
        $is_admin_side = $this->checkIsAdminSide();
        //design
        $design_settings = $this->getDesignSettings();
        //content admin side not translated values fetch
        $guest_base = new Guest();
        $guest_content = $guest_base->getGuestContentData($is_admin_side);
        $member_base = new Member();
        $member_content = $member_base->getMemberContentData($is_admin_side);
        $content_settings = array('content' => array_merge($guest_content, $member_content));
        //popup button
        $popup_button_settings = $this->getLauncherButtonContentData($is_admin_side);
        $response["success"] = true;
        $response['data'] = array_merge($design_settings, $content_settings, $popup_button_settings);
        wp_send_json($response);
    }

    /**
     * Save design settings
     * @return void
     */
    public function saveDesignSettings()
    {
        $data = array(
            'success' => false,
            'data' => array(
                'message' => __('Settings not saved!', 'wp-loyalty-rules')
            ),
        );
        if (!$this->isLauncherSecurityValid('wll_design_settings')) {
            $data['data']['message'] = __('Security check failed', 'wp-loyalty-rules');
            wp_send_json($data);
        }
        $post_data = self::$input->post();
        $post_data['design'] = isset($post_data['design']) && !empty($post_data['design']) ? json_decode(stripslashes(base64_decode($post_data['design'])), true) : array();
        if (empty($post_data['design'])) wp_send_json($data);
        //validation
        $validate_data = self::$validation->validateDesignTab(array("design" => $post_data['design']));
        if (is_array($validate_data)) {
            foreach ($validate_data as $key => $validate) {
                $validate_data[$key] = array(current($validate));
            }
            $data['data']['field_error'] = $validate_data;
            wp_send_json($data);
        }
        //save in option table
        update_option('wll_launcher_design_settings', array('design' => $post_data['design']));
        $data['success'] = true;
        $data['data']['message'] = __('Settings saved!', 'wp-loyalty-rules');
        wp_send_json($data);
    }

    /**
     * Save content settings
     * @return void
     */
    public function saveContentSettings()
    {
        $data = array(
            'success' => false,
            'data' => array(
                'message' => __('Settings not saved!', 'wp-loyalty-rules')
            ),
        );
        if (!$this->isLauncherSecurityValid('wll_content_settings')) {
            $data['data']['message'] = __('Security check failed', 'wp-loyalty-rules');
            wp_send_json($data);
        }
        $post_data = self::$input->post();
        $settings = isset($post_data['content']) && !empty($post_data['content']) ? json_decode(stripslashes($post_data['content']), true) : array();
        if (empty($settings)) wp_send_json($data);
        //validation
        $validate_data = self::$validation->validateContentTab(array("content" => $settings));
        if (is_array($validate_data)) {
            foreach ($validate_data as $key => $validate) {
                $validate_data[$key] = array(current($validate));
            }
            $data['data']['field_error'] = $validate_data;
            wp_send_json($data);
        }
        $this->processMemberUpdateData($settings);

        //save in option table
        update_option('wll_launcher_content_settings', array('content' => $settings));
        $data['success'] = true;
        $data['data']['message'] = __('Settings saved!', 'wp-loyalty-rules');
        wp_send_json($data);
    }

    function processMemberUpdateData(&$settings)
    {
        if (empty($settings)) return;
        $short_code_list = self::$settings->shortCodesWithLabels();
        if (isset($settings['guest']['welcome']['shortcodes']) && empty($settings['guest']['welcome']['shortcodes'])) {
            $settings['guest']['welcome']['shortcodes'] = array_merge($short_code_list['common'], $short_code_list['guest']);
        }
        if (isset($settings['member']['banner']['shortcodes']) && empty($settings['member']['banner']['shortcodes'])) {
            $settings['member']['banner']['shortcodes'] = array_merge($short_code_list['common'], $short_code_list['member']);
        }
        if (isset($settings['member']['referrals']['shortcodes']) && empty($settings['member']['referrals']['shortcodes'])) {
            $settings['member']['referrals']['shortcodes'] = array_merge($short_code_list['common'], $short_code_list['referral']);
        }
        if (!isset($settings['member']['banner']['levels']['is_levels_available'])) {
            $level_modal = new Levels();
            $settings['member']['banner']['levels']['is_levels_available'] = $level_modal->checkLevelsAvailable();
        }
    }

    /**
     * Save popup button settings
     * @return void
     */
    public function saveLauncherSettings()
    {
        $data = array(
            'success' => false,
            'data' => array(
                'message' => __('Settings not saved!', 'wp-loyalty-rules')
            ),
        );
        if (!$this->isLauncherSecurityValid('wll_launcher_settings')) {
            $data['data']['message'] = __('Security check failed', 'wp-loyalty-rules');
            wp_send_json($data);
        }
        $post_data = self::$input->post();
        $settings = isset($post_data['launcher']) && !empty($post_data['launcher']) ? json_decode(stripslashes(base64_decode($post_data['launcher'])), true) : array();
        if (empty($settings)) wp_send_json($data);
        //validation
        $validate_data = self::$validation->validateLauncherTab(array('launcher' => $settings));
        if (is_array($validate_data)) {
            foreach ($validate_data as $key => $validate) {
                $validate_data[$key] = array(current($validate));
            }
            $data['data']['field_error'] = $validate_data;
            wp_send_json($data);
        }
        //save in option table
        update_option('wll_launcher_icon_settings', array('launcher' => $settings));
        $data['success'] = true;
        $data['data']['message'] = __('Settings saved!', 'wp-loyalty-rules');
        wp_send_json($data);
    }

    /**
     * Getting app details
     *
     * @param $data
     * @return mixed
     */
    function getAppDetails($data)
    {
        if (is_array($data)) {
            $data[] = array(
                'icon' => '',
                'title' => WLL_PLUGIN_NAME,
                'version' => WLL_PLUGIN_VERSION,
                'author' => WLL_PLUGIN_AUTHOR,
                'description' => __('Launcher widget for WPLoyalty. Let your customers easily discover your loyalty rewards.', 'wp-loyalty-rules'),
                'document_link' => '',
                'is_active' => in_array(get_option('wlr_launcher_active', 'yes'), array(1, 'yes')),
                'plugin' => 'wlr_app_launcher',
                'page_url' => admin_url('admin.php?' . http_build_query(array('page' => WLL_PLUGIN_SLUG)))
            );
        }
        return $data;
    }

}