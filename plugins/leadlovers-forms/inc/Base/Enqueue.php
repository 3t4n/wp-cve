<?php 
/**
 * @package  LeadloversPlugin
 */
namespace LeadloversInc\Base;

use LeadloversInc\Base\BaseController;

/**
* 
*/
class Enqueue extends BaseController
{
	private function getUrlParams() { 
		return "?xhr_url=" . admin_url('admin-ajax.php') .
			"&leadlovers-save-integration_nonce=" . wp_create_nonce('leadlovers-save-integration_nonce') .
			"&leadlovers-get-integrations_nonce=" . wp_create_nonce('leadlovers-get-integrations_nonce') .
			"&leadlovers-save-capture-log_nonce=" . wp_create_nonce('leadlovers-save-capture-log_nonce') .
			"&leadlovers-save-error-log_nonce=" . wp_create_nonce('leadlovers-save-error-log_nonce') .
			"&leadlovers-save-lead_nonce=" . wp_create_nonce('leadlovers-save-lead_nonce') .
			"&plugin-url=" . $this->plugin_url;
	}

	public function register() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_admin' ) );
		add_filter('script_loader_tag', array( $this, 'add_type_attribute') , 10, 3);
	}

	function add_type_attribute($tag, $handle, $src) {
		// if not your script, do nothing and return original $tag
		if ( 'leadlovers-webcomponent-script' !== $handle ) {
			return $tag;
		}
		// change the script tag by adding type="module" and return it.
		$tag = '<script id="' . $handle . '-js"  type="module" crossorigin src="' . esc_url( $src ) . '"></script>';
		return $tag;
	}

	function enqueue_admin() {
		if (current_user_can( 'manage_options' )) { 
			wp_enqueue_script(
				'leadlovers-webcomponent-script',
				'https://public-libs.leadlovers.com/wpplugin-react/index.js' . $this->getUrlParams() . "&api-access-key=" . get_option( 'leadlovers_api_key' )
				);
		}
	}

	function enqueue() {
		wp_enqueue_script(
			'leadlovers-functions-script',
			$this->plugin_url . '/assets/js/functions.js'
			);
		wp_enqueue_script(
			'leadlovers-capture-lead-script',
			$this->plugin_url . '/assets/js/capture-lead.js' . $this->getUrlParams()
			);
	    if (current_user_can( 'manage_options' )) {
			wp_enqueue_script(
				'leadlovers-load-script',
				$this->plugin_url . '/assets/js/load.js'
				);
		}
	}
}