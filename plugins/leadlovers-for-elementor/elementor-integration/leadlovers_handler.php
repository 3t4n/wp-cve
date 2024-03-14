<?php
namespace LeadloversPlugin\Classes;

use ElementorPro\Modules\Forms\Classes\Rest_Client;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Leadlovers_Handler {
	private $rest_client = null;
	private $api_key = '';	
	private $machineInfosList = [];

	public function __construct( $api_key ) {
		if ( empty( $api_key ) ) {
			throw new \Exception( 'Invalid API key' );
		}
		$this->init_rest_client( $api_key );
		if ( ! $this->is_valid_api_key() ) {
			throw new \Exception( 'Invalid API key' );
		}
	}

	private function init_rest_client( $api_key ) {
		$this->api_key = $api_key;
		$authBasic = 'Basic '.base64_encode("leadlovers-api:$api_key");
		
 		$this->rest_client = new Rest_Client( 'https://elementorapi.leadlovers.com.br/Elementor/' );
		// $this->rest_client = new Rest_Client( 'http://host.docker.internal:9999/Elementor/' );
		$this->rest_client->add_headers('cache-control', 'no-cache');
		$this->rest_client->add_headers('Authorization', $authBasic);
		$this->rest_client->add_headers('Content-Type', 'application/json');
	}

	private function is_valid_api_key() {
		$machines = $this->get_machines();
		
		if ( ! empty( $machines ) ) {
			return true;
		}
		$this->api_key = '';
		return false;
	}

	public function get_machines() {
		$results = $this->rest_client->get( 'machines' );
		
		$machines = [
			'' => __( 'Selecione...', 'elementor-pro' ),
		];

		if ( ! empty( $results['body']['machines'] ) ) {
			foreach ( $results['body']['machines'] as $index => $machine ) {
				if ( !is_array($machine) ) {
					continue;
				}
				switch($machine['type']) {
					case 'Email':
					case 'Whatsapp':
					case 'Sms':
						$machines[ $machine['id'] ] = $machine['name'];
						$this->machineInfosList[$machine['id']] = [
							"name" => $machine['name'],
							"type" => $machine['type']
						];
						break;
						
					default: 
						break;
				}
			}
		}

		$return_array = [
			'machines' => $machines,
		];

		return $return_array;
	}
	
	public function get_machine_infos($machineCode) {
		
		return $this->machineInfosList[$machineCode];
	}
	
	public function get_funnels($machineCode) {
		$results = $this->rest_client->get( 'machine/' . $machineCode . '/funnels' );
		
		$funnels = [
			'' => __( 'Selecione...', 'elementor-pro' ),
		];

		if ( ! empty( $results['body']['machines'] ) ) {
			foreach ( $results['body']['machines'] as $index => $funnel ) {
				if ( ! is_array( $funnel ) ) {
					continue;
				}
				$funnels[ $funnel['id'] ] = $funnel['name'];
			}
		}

		$return_array = [
			'funnels' => $funnels,
		];

		return $return_array;
	}
	
	public function get_sequences($machineCode, $funnelCode) {
		$results = $this->rest_client->get( 'machine/' . $machineCode . '/funnel/' . $funnelCode . '/sequences');
		
		$sequences = [
			'' => __( 'Selecione...', 'elementor-pro' ),
		];

		if ( ! empty( $results['body']['machines'] ) ) {
			foreach ( $results['body']['machines'] as $index => $sequence ) {
				if ( ! is_array( $sequence ) ) {
					continue;
				}
				$sequences[ $sequence['id'] ] = $sequence['name'];
			}
		}

		$return_array = [
			'sequences' => $sequences,
		];

		return $return_array;
	}

	public function get_tags() {
		$results = $this->rest_client->get( 'tags');
		
		$tags = [];

		if ( ! empty( $results['body']['tags'] ) ) {
			foreach ( $results['body']['tags'] as $index => $tag ) {
				if ( ! is_array( $tag ) ) {
					continue;
				}
				$tags[ $tag['id'] ] = $tag['name'];
			}
		}

		$return_array = [
			'tags' => $tags,
		];

		return $return_array;
	}

	public function get_capture_fields() {
		$results = $this->rest_client->get( 'captureFields');
		
		if($results['body']['captureFields']) {
			return $results['body'];
		}
		
		return [
			'captureFields' => []
		];
	}
	
	public function create_lead( $lead = [] ) {
		return $this->rest_client->post( 'lead/1.8', $lead );
	}
}
