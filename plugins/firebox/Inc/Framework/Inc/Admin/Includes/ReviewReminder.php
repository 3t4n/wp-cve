<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Admin\Includes;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class ReviewReminder
{
	/**
	 * Plugin slug, used to determine whether to display review reminder
	 * only on plugin-specific pages.
	 * 
	 * @var  string
	 */
	private $plugin_slug;

	/**
	 * Plugin name
	 * 
	 * @var  string
	 */
	private $plugin_name;

	/**
	 * Plugin admin assets URL
	 * 
	 * @var  string
	 */
	private $plugin_admin_assets_url;

	/**
	 * The days when the review reminder will reappear after the user selects "Remind me later"
	 * 
	 * @var  string
	 */
	private $reappear_after_days = 7;
	
	public function __construct($plugin_slug = '', $plugin_name = '', $plugin_admin_assets_url = '', $reappear_after_days = 7)
	{
		if (!$plugin_slug || !$plugin_name || !$plugin_admin_assets_url || !$reappear_after_days)
		{
			return;
		}
		
		$this->plugin_slug = $plugin_slug;
		$this->plugin_name = $plugin_name;
		$this->plugin_admin_assets_url = $plugin_admin_assets_url;
		$this->reappear_after_days = (int) $reappear_after_days;
		
		if (!$this->canRun())
		{
			return;
		}
		
		$this->loadAssets();
		
		// Rate reminder actions.
		add_action('admin_notices', [$this, 'show_rate_reminder']);
		add_action('upgrader_process_complete', [$this, 'set_update_rate_reminder'], 1, 2);
		add_action('wp_ajax_fpf_update_rate_reminder', [$this, 'update_rate_reminder']);
	}

	/**
	 * Ensure the review handler appears only on provided plugin pages
	 * 
	 * @return  boolean
	 */
	private function canRun()
	{
		// skip check if we are calling from ajax
		if (wp_doing_ajax())
		{
			return true;
		}
		
		$page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : false; //phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if (!$page)
		{
			return false;
		}

		if (strpos($page, $this->plugin_slug) !== 0)
		{
			return false;
		}

		return true;
	}
	
	/**
	 * Load assets
	 * 
	 * @return  void
	 */
	protected function loadAssets()
	{
		wp_register_script(
			'fpframework-review-handler-lib',
			FPF_MEDIA_URL . 'admin/js/fpf_review_handler.js',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_script( 'fpframework-review-handler-lib' );
	}

	/**
	 * Set reminder transients on plugins update.
	 *
	 * @return  void
	 */
	function set_update_rate_reminder($upgrader_object, $options)
	{
		if ($options['action'] != 'update' || $options['type'] != 'plugin')
		{
			return;
		}

		$transient = 'fpf_' . $this->plugin_slug . 'rate';
		
		if (get_transient($transient . '_reminder_deleted'))
		{
			return;
		}

		$date = new \DateTime('2020-02-10');
		set_transient( $transient . '_reminder', $date->format( 'Y-m-d' ) );
	}

	/**
	 * Show reminder.
	 *
	 * @return  void
	 */
	function show_rate_reminder()
	{
		$transient = 'fpf_' . $this->plugin_slug . '_rate_reminder';
		
		if(!$start_date = new \DateTime( get_transient( $transient ) ) )
		{
			return;
		}

		$current_date = new \DateTime();

		if( $current_date < $start_date )
		{
			return;
		}

		$image = sprintf( esc_html( '%1$s' ), '<img src="' . esc_url($this->plugin_admin_assets_url) . 'images/logo.svg" width="100" alt="FirePlugins Logo" />' );
		$message = sprintf( esc_html( fpframework()->_('FPF_REVIEW_REMINDER_MSG1') ), '<p style="margin-top: 0;">', '<b>', esc_html($this->plugin_name), '</b>', '&ndash;', '</br>', '<em>', '</em>', '</p>' );
		$message .= sprintf( esc_html( fpframework()->_('FPF_REVIEW_REMINDER_MSG2') ), '<span>', '<a class="button button-primary fpf-clear-rate-reminder" href="https://wordpress.org/support/plugin/' . esc_attr($this->plugin_slug) . '/reviews/?filter=5" target="_blank">', '</a>', '<a class="button fpf-ask-later" data-plugin-slug="' . esc_attr($this->plugin_slug) . '" href="#">', '<a class="button fpf-delete-rate-reminder" data-plugin-slug="' . esc_attr($this->plugin_slug) . '" href="#">', '</span>' );
		printf( '<div class="notice fpf-review-reminder"><div class="fpf-review-author-avatar">%1$s</div><div class="fpf-review-message">%2$s</div></div>', wp_kses_post( $image ), wp_kses_post( $message ) );
	}

	/**
	 * Delete or update the rate reminder admin notice.
	 *
	 * @return  void
	 */
	function update_rate_reminder()
	{
		check_ajax_referer('fpf_js_nonce');

		if (!isset($_POST['update']))
		{
			return;
		}
	
		$transient_prefix = 'fpf_' . $this->plugin_slug;
		$transient_rate = $transient_prefix . '_rate_reminder';
		
		if ($_POST['update'] === $transient_prefix . '_delete_rate_reminder')
		{
			// set a very long future date to ensure review reminder no longer re-appears
			$date = new \DateTime();
			$date->add(new \DateInterval('P999D'));
			$date_format = $date->format('Y-m-d');
			set_transient($transient_rate, $date_format);

			delete_transient($transient_prefix . '_rate_reminder_deleted');

			if (set_transient($transient_prefix . '_rate_reminder_deleted', 'No reminder to show'))
			{
				$response = [
					'error' => false
				];
			}
			else
			{
				$response = [
					'error' => true
				];
			}
		}

		if ($_POST['update'] === $transient_prefix . '_ask_later')
		{
			$date = new \DateTime();
			$date->add(new \DateInterval('P' . $this->reappear_after_days . 'D'));
			$date_format = $date->format('Y-m-d');

			delete_transient($transient_rate);

			if (set_transient($transient_rate, $date_format))
			{
				$response = [
					'error' => false
				];
			}
			else
			{
				$response = [
					'error' => true,
					'error_type' => set_transient($transient_rate, $date_format)
				];
			}
		}

		wp_send_json($response);
	}
}