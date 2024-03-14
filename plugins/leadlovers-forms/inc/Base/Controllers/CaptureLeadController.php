<?php 
/**
 * @package  LeadloversPlugin
 */
namespace  LeadloversInc\Base\Controllers;

use LeadloversInc\Base\BaseController;

class CaptureLeadController extends BaseController
{

    public function register() {
		add_action('wp_ajax_leadlovers-save-lead', array( $this, 'save'));
		add_action('wp_ajax_nopriv_leadlovers-save-lead', array( $this, 'save'));
	}
	
    function save()
	{
		if (! DOING_AJAX  && ! wp_verify_nonce( $_POST['nonce'], 'leadlovers-save-lead_nonce' )) {
			return 400;
		}

		$data = $_POST;
		unset($data['nonce']);
		unset($data['action']);
		$data['source'] = 'Wordpress';
		$data['tags'] = json_decode(str_replace('\\', '', $data['tags']));
		$data['dynamicFields'] = json_decode(str_replace('\\', '', $data['dynamicFields']));

		$response = wp_remote_post(
			'https://llapi.leadlovers.com/webapi/lead?token=' . get_option( 'leadlovers_api_key' ),
			[
				'method' => 'PUT',
				'headers' => [
					'Content-Type' => 'application/json',
				],
				'body'    => json_encode($data),
			]
		);

		if ( is_wp_error( $response ) ) {
			return wp_send_json(array(
				'status' => 400, 
				'data' => $response->get_error_message()
			));
		}
		

		return wp_send_json(array(
			'status' => 200, 
			'data' => $response
		));
	}
}
