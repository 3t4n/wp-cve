<?php

namespace ShopMagicVendor\WPDesk\Notice;

use ShopMagicVendor\WPDesk\PluginBuilder\Plugin\HookablePluginDependant;
use ShopMagicVendor\WPDesk\PluginBuilder\Plugin\PluginAccess;
/**
 * Class AjaxHandler
 *
 * AjaxHandler for dismissible notices.
 *
 * @package WPDesk\Notice
 */
class AjaxHandler implements HookablePluginDependant
{
    use PluginAccess;
    const POST_FIELD_NOTICE_NAME = 'notice_name';
    const POST_FIELD_SOURCE = 'source';
    const POST_FIELD_SECURITY = 'security';
    const SCRIPTS_VERSION = '4';
    const SCRIPT_HANDLE = 'wpdesk_notice';
    /**
     * @var string
     */
    private $assetsURL;
    /**
     * AjaxHandler constructor.
     *
     * @param string|null $assetsURL Assets URL.
     */
    public function __construct($assetsURL = null)
    {
        $this->assetsURL = $assetsURL;
    }
    /**
     * Hooks.
     */
    public function hooks()
    {
        if ($this->assetsURL) {
            \add_action('admin_enqueue_scripts', [$this, 'enqueueAdminScripts']);
        } else {
            \add_action('admin_head', [$this, 'addScriptToAdminHead']);
        }
        \add_action('wp_ajax_wpdesk_notice_dismiss', [$this, 'processAjaxNoticeDismiss']);
    }
    /**
     * Enqueue admin scripts.
     */
    public function enqueueAdminScripts()
    {
        wp_register_script(self::SCRIPT_HANDLE, trailingslashit($this->assetsURL) . 'js/notice.js', ['jquery'], self::SCRIPTS_VERSION);
        wp_enqueue_script(self::SCRIPT_HANDLE);
    }
    /**
     * Add Java Script to admin header.
     */
    public function addScriptToAdminHead()
    {
        include __DIR__ . '/views/admin-head-js.php';
    }
    /**
     * Process AJAX notice dismiss.
     *
     * Updates corresponded WordPress option and fires wpdesk_notice_dismissed_notice action with notice name.
     */
    public function processAjaxNoticeDismiss()
    {
        if (isset($_POST[self::POST_FIELD_NOTICE_NAME])) {
            $noticeName = sanitize_text_field($_POST[self::POST_FIELD_NOTICE_NAME]);
            if (isset($_POST[self::POST_FIELD_SOURCE])) {
                $source = sanitize_text_field($_POST[self::POST_FIELD_SOURCE]);
            } else {
                $source = null;
            }
            $security = $_POST[self::POST_FIELD_SECURITY] ?? '';
            $option_name = PermanentDismissibleNotice::OPTION_NAME_PREFIX . $noticeName;
            if (wp_verify_nonce($security, $option_name)) {
                update_option($option_name, PermanentDismissibleNotice::OPTION_VALUE_DISMISSED);
                do_action('wpdesk_notice_dismissed_notice', $noticeName, $source);
                if (\defined('ShopMagicVendor\\DOING_AJAX') && DOING_AJAX) {
                    wp_send_json_success();
                }
            }
        }
        if (\defined('ShopMagicVendor\\DOING_AJAX') && DOING_AJAX) {
            wp_send_json_error();
        }
    }
}
