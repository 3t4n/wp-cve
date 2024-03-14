<?php
/**
 * Handle AJAX Request
 */

namespace Reuse\Builder;


class Ajax_Handler {
	/**
     * Action hook used by the AJAX class.
     *
     * @var string
     */
    const ACTION = 'reuseb_ajax';

    /**
     * Action argument used by the nonce validating the AJAX request.
     *
     * @var string
     */
    const NONCE = 'reuseb_ajax_nonce';

  /**
   * Register the AJAX handler class with all the appropriate WordPress hooks.
   */
  public function __construct() {
    add_action('wp_ajax_'. self::ACTION, array($this, 'handle_ajax'));
    add_action('wp_ajax_nopriv_' . self::ACTION, array($this, 'handle_ajax'));
  }

  /**
   * Handles the AJAX request for my plugin.
   */
  public function handle_ajax() {
    // Make sure we are getting a valid AJAX request
    check_ajax_referer(self::NONCE, 'nonce');
		$ajax_data = $_POST;
		unset($ajax_data['nonce']);
		unset($ajax_data['action']);
		switch ($ajax_data['action_type']) {
			case 'update_option':
				$this->update_option($ajax_data);
				break;
		}
    die();
  }

  public function update_option($ajax_data)
  {
    update_option('reuseb_settings', $ajax_data['reuseb_settings']);
  }
}
