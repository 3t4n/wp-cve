<?php

/**
 * Notices class.
 *
 * @since      1.0.0
 * @package    Surror
 * @author     Surror <dev@surror.com>
 */
namespace FAL\Surror;

use FAL\Surror\Notices\Base;
\defined('ABSPATH') || exit;
/**
 * Page class.
 */
class Notices extends Base
{
    /**
     * Version.
     */
    public $version = '1.0.1';
    /**
     * Notices
     */
    private static $notices = [];
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        global $surror;
        // Not yet defined define with default values.
        if (empty($surror) || !isset($surror['notices'])) {
            $surror = ['notices' => ['path' => '', 'uri' => '', 'version' => '', 'hooks' => []]];
        }
        // Version check before execute latest code.
        if (!empty($surror['notices']['version'])) {
            if (\version_compare($surror['notices']['version'], $this->version, '>=')) {
                return;
            }
            $this->remove_hooks($surror['notices']['hooks']);
        }
        $hook_data = ['actions' => [['admin_notices', [$this, 'show_notices'], 50], ['admin_enqueue_scripts', [$this, 'enqueue_scripts']], ['wp_ajax_surror_notices_dismiss', [$this, 'dismiss_notice']]], 'filters' => []];
        // Store hooks in the global array.
        global $surror;
        $surror['notices'] = ['path' => $this->path, 'uri' => $this->uri, 'version' => $this->version, 'hooks' => $hook_data];
        // Add hooks.
        foreach ($hook_data['actions'] as $action_info) {
            $hook = $action_info[0];
            $method = $action_info[1];
            $priority = isset($action_info[2]) ? $action_info[2] : 10;
            $args = isset($action_info[3]) ? $action_info[3] : 1;
            add_action($hook, $method, $priority, $args);
        }
        // Add filters.
        foreach ($hook_data['filters'] as $filter_info) {
            $hook = $filter_info[0];
            $method = $filter_info[1];
            $priority = isset($filter_info[2]) ? $filter_info[2] : 10;
            $args = isset($filter_info[3]) ? $filter_info[3] : 1;
            add_filter($hook, $method, $priority, $args);
        }
    }
    public function dismiss_notice()
    {
        check_ajax_referer('surror-notices', 'nonce');
        $notice_id = sanitize_text_field($_POST['notice_id']);
        if (empty($notice_id)) {
            wp_send_json_error('Invalid notice id');
        }
        $db = sanitize_text_field($_POST['db']);
        if (empty($db)) {
            wp_send_json_error('Invalid db');
        }
        if ('option' === $db) {
            update_option('surror_notices_dismissed_' . $notice_id, \true);
        } else {
            update_user_meta(get_current_user_id(), 'surror_notices_dismissed_' . $notice_id, \true);
        }
    }
    /**
     * Enqueue scripts
     */
    public function enqueue_scripts()
    {
        wp_enqueue_style('surror-notices', $this->uri . 'assets/css/style.css', [], $this->version, 'all');
        wp_enqueue_script('surror-notices', $this->uri . 'assets/js/script.js', ['jquery'], $this->version, \true);
        wp_localize_script('surror-notices', 'surror_notices', ['ajax_url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('surror-notices')]);
    }
    /**
     * Register
     */
    public static function register($notice = [])
    {
        if (empty($notice)) {
            return;
        }
        self::$notices[] = $notice;
    }
    /**
     * Show notices
     */
    public function show_notices()
    {
        if (empty(self::$notices)) {
            return;
        }
        $this->show_notice(self::$notices[0]);
    }
    /**
     * Show notice
     */
    public function show_notice($notice)
    {
        $id = isset($notice['id']) ? $notice['id'] : '';
        $db = isset($notice['db']) ? $notice['db'] : 'user';
        if ('option' === $db) {
            $expired = get_option('surror_notices_dismissed_' . $id, \true);
        } else {
            $expired = get_user_meta(get_current_user_id(), 'surror_notices_dismissed_' . $id, \true);
        }
        if ($expired) {
            return;
        }
        $type_class = isset($notice['type']) ? $notice['type'] : 'info';
        $is_dismissible = isset($notice['dismissible']) ? $notice['dismissible'] : \true;
        $message = isset($notice['message']) ? $notice['message'] : '';
        $id = isset($notice['id']) ? $notice['id'] : '';
        $dismiss_text = isset($notice['dismiss_text']) ? $notice['dismiss_text'] : '';
        $confirm_message = isset($notice['confirm_message']) ? $notice['confirm_message'] : '';
        ?>
		<div id="<?php 
        echo esc_attr($id);
        ?>" data-notice-id="<?php 
        echo esc_attr($id);
        ?>" class="s-notice notice notice-<?php 
        echo esc_attr($type_class);
        ?>" data-confirm-message="<?php 
        echo esc_attr($confirm_message);
        ?>" data-db="<?php 
        echo esc_attr($db);
        ?>">
			<?php 
        if ($is_dismissible) {
            ?>
				<button type="button" class="notice-dismiss s-notice-close">
					<span><?php 
            echo esc_html($dismiss_text);
            ?></span>
				</button>
			<?php 
        }
        ?>
			<div class="s-notice-content"><?php 
        echo wp_kses_post($message);
        ?></div>
		</div>
		<?php 
    }
    /**
     * Clean notices
     */
    public function clean_all()
    {
        foreach ($this->notices as $notice) {
            $id = isset($notice['id']) ? $notice['id'] : '';
            $db = isset($notice['db']) ? $notice['db'] : 'user';
            if ('option' === $db) {
                delete_option('surror_notices_dismissed_' . $id);
            } else {
                delete_user_meta(get_current_user_id(), 'surror_notices_dismissed_' . $id);
            }
        }
    }
}
