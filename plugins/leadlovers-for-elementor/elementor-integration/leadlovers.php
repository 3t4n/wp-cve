<?php
namespace LeadloversPlugin\Actions;

use Elementor\Controls_Manager;
use Elementor\Settings;
use ElementorPro\Modules\Forms\Classes\Form_Record;
use ElementorPro\Modules\Forms\Controls\Fields_Map;
use ElementorPro\Modules\Forms\Classes\Integration_Base;
use LeadloversPlugin\Classes\Leadlovers_Handler as Leadlovers_Handler;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

include_once( __DIR__ .'/leadlovers_handler.php' );

class Leadlovers extends Integration_Base {
	
	const OPTION_NAME_API_KEY = 'pro_leadlovers_api_key';

	private function get_global_api_key() {
		return get_option( 'elementor_' . self::OPTION_NAME_API_KEY );
	}

	public function get_name() {
		return 'leadlovers';
	}

	public function get_label() {
		return __( 'Leadlovers', 'elementor-pro' );
	}

	public function register_settings_section( $widget ) {
		$widget->start_controls_section(
			'section_leadlovers',
			[
				'label' => __( 'Leadlovers', 'elementor-pro' ),
				'condition' => [
					'submit_actions' => $this->get_name(),
				],
			]
		);

		self::global_api_control(
			$widget,
			$this->get_global_api_key(),
			'Leadlovers API Key',
			[
				'leadlovers_api_key_source' => 'default',
			],
			$this->get_name()
		);

		$widget->add_control(
			'leadlovers_api_key_source',
			[
				'label' => __( 'API Key', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
					'default' => 'Default',
					'custom' => 'Custom',
				],
				'default' => 'default',
			]
		);

		$widget->add_control(
			'leadlovers_custom_api_key',
			[
				'label' => __( 'Custom API Key', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'leadlovers_api_key_source' => 'custom',
				],
				'description' => __( 'Use this field to set a custom API Key for the current form', 'elementor-pro' ),
			]
		);

		$widget->add_control(
			'leadlovers_machine',
			[
				'label' => __( 'Máquina', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [],
				'render_type' => 'none',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'leadlovers_custom_api_key',
							'operator' => '!==',
							'value' => '',
						],
						[
							'name' => 'leadlovers_api_key_source',
							'operator' => '=',
							'value' => 'default',
						],
					],
				],
				'default' => '',
			]
		);
		
		$widget->add_control(
			'leadlovers_funnel',
			[
				'label' => __( 'Funil', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [],
				'render_type' => 'none',
				'condition' => [
					'leadlovers_machine!' => '',
				],
				'default' => '',
			]
		);
		
		$widget->add_control(
			'leadlovers_sequence',
			[
				'label' => __( 'Sequência', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [],
				'render_type' => 'none',
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'leadlovers_machine',
							'operator' => '!==',
							'value' => '',
						],
						[
							'name' => 'leadlovers_funnel',
							'operator' => '!==',
							'value' => '',
						]
					],
				],
				'default' => '',
			]
		);

		$widget->add_control(
			'leadlovers_lead_tags',
			[
				'label' => __( 'Lead Tags', 'elementor-pro' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT2,
				'separator' => 'before',
				'multiple' => true,
				'options' => [],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'leadlovers_custom_api_key',
							'operator' => '!==',
							'value' => '',
						],
						[
							'name' => 'leadlovers_api_key_source',
							'operator' => '=',
							'value' => 'default',
						],
					],
				],
			]
		);
		
		$widget->add_control(
			'leadlovers_input_fields',
			[
				'label' => __( 'Mapeamento dos campos fixos', 'elementor-pro' ),
				'type' => Fields_Map::CONTROL_TYPE,
				'separator' => 'before',
				'fields' => [
					[
						'name' => 'remote_id',
						'type' => Controls_Manager::HIDDEN,
					],
					[
						'name' => 'local_id',
						'type' => Controls_Manager::SELECT,
					],
				],
				'condition' => [
					'leadlovers_machine!' => '',
				],
			]
        );
        
        $widget->add_control(
			'leadlovers_dynamic_input_fields',
			[
				'label' => __( 'Mapeamento dos campos dinâmicos', 'elementor-pro' ),
				'type' => Fields_Map::CONTROL_TYPE,
				'separator' => 'before',
				'fields' => [
					[
						'name' => 'remote_id',
						'type' => Controls_Manager::HIDDEN,
					],
					[
						'name' => 'local_id',
						'type' => Controls_Manager::SELECT,
					],
				],
				'condition' => [
					'leadlovers_machine!' => '',
				],
			]
		);

		$widget->add_control(
			'leadlovers_utm_parameters',
			[
				'label' => __( 'Mapeamento dos parametros de UTM', 'elementor-pro' ),
				'type' => Fields_Map::CONTROL_TYPE,
				'separator' => 'before',
				'fields' => [
					[
						'name' => 'remote_id',
						'type' => Controls_Manager::HIDDEN,
					],
					[
						'name' => 'local_id',
						'type' => Controls_Manager::SELECT,
					],
				],
				'condition' => [
					'leadlovers_machine!' => '',
				],
			]
        );
		
		$widget->add_control(
			'leadlovers_machine_type',
			[
				'label' => __( 'Machine Type', 'elementor-pro' ),
				'type' => Controls_Manager::HIDDEN,
				'condition' => [
					'leadlovers_machine!' => '',
				],
			]
		);

		$widget->add_control(
			'leadlovers_not_allow_lead_exisist',
			[
				'label' => __( 'Mensagem de erro para leads já existentes?', 'elementor-pro' ),
				'description' => 'Os leads que já estiverem ativos na mesma máquina e/ou funil receberão uma mensagem de erro personalizada.',
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'label_on' => __( 'Sim', 'elementor-pro' ),
				'label_off' => __( 'Não', 'elementor-pro' ),
				'return_value' => 'yes',
				'default' => 'yes'
			]
		);

		$widget->add_control(
			'leadlovers_not_allow_lead_exisist_note',
			[
				'label' => __( 'Important Note', 'elementor-pro' ),
				'show_label' => false,
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'Importante: Mesmo com essa opção desativada, os leads que já estiverem ativos na mesma máquina e/ou funil <b>não serão cadastrados novamente na leadlovers.</b> Eles apenas não receberão mais uma mensagem de erro.', 'plugin-name' ),
				'content_classes' => 'elementor-control-field-description',
				'conditions' => [
					'terms' => [
						[
							'name' => 'leadlovers_not_allow_lead_exisist',
							'operator' => '!in',
							'value' => [
								'yes',
							],
						],
					],
				],
			]
		);

		$widget->add_control(
			'leadlovers_lead_exisist_message',
			[
				'label' => __( 'Mensagem de erro para leads já existentes', 'elementor-pro' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Já existe um cadastro com os dados informados.', 'elementor-pro' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'leadlovers_not_allow_lead_exisist',
							'operator' => 'in',
							'value' => [
								'yes',
							],
						],
					],
				],
			]
		);

		$widget->end_controls_section();
	}

	public function on_export( $element ) {
		unset(
			$element['settings']['leadlovers_api_key_source'],
			$element['settings']['leadlovers_custom_api_key'],
			$element['settings']['leadlovers_machine'],
			$element['settings']['leadlovers_funnel'],
			$element['settings']['leadlovers_sequence'],
			$element['settings']['leadlovers_input_fields'],
			$element['settings']['leadlovers_utm_parameters'],
			$element['settings']['leadlovers_dynamic_input_fields'],
			$element['settings']['leadlovers_lead_tags'],
			$element['settings']['leadlovers_machine_type'],
			$element['settings']['leadlovers_not_allow_lead_exisist'],
			$element['settings']['leadlovers_lead_exisist_message']
		);

		return $element;
	}

	public function run( $record, $ajax_handler ) {
		
		$form_settings = $record->get( 'form_settings' );
		$lead = $this->create_lead_object( $record, $form_settings, $ajax_handler );


		$ajax_handler->add_response_data( "lead", $lead );

		if ( !$lead ) { 
			$ajax_handler->add_admin_error_message( __( 'Leadlovers Integration requires an ' . $this->MACHINE_TYPE_ERROR_MSG . ' field', 'elementor-pro' ) );
			return;
		}

		if ( 'default' === $form_settings['leadlovers_api_key_source'] ) {
			$api_key = $this->get_global_api_key();
		} else {
			$api_key = $form_settings['leadlovers_custom_api_key'];
		}

		try {
			$handler = new Leadlovers_Handler( $api_key );
			$handlerResponse = $handler->create_lead($lead);
			$ajax_handler->add_response_data( "ll_response", $handlerResponse['body'] );

			if (!empty($handlerResponse['body']) && $handlerResponse['body'][0]['code']) {
				switch($handlerResponse['body'][0]['code']) {
					case 'LEAD_ALREADY_EXISTS':
						if ($form_settings['leadlovers_not_allow_lead_exisist'] === 'yes') {
							$message = sanitize_text_field($form_settings['leadlovers_lead_exisist_message']);
							$ajax_handler->add_error_message($message);
							$ajax_handler->add_admin_error_message('You are trying register an lead already exists.');
						}
						break;
					case 'LEAD_INVALID':
						$message = $ajax_handler->get_default_message('invalid', $form_settings);
						$ajax_handler->add_error_message($message);
						$ajax_handler->add_admin_error_message('You are trying register an invalid lead.');
						break;
					case 'LEAD_FORBIDDEN':
						$message = $ajax_handler->get_default_message('invalid', $form_settings);
						$ajax_handler->add_error_message($message);
						$ajax_handler->add_admin_error_message('You are trying register an forbidden lead.');
						break;
					default: 
						break;
				}
			}

		} catch ( \Exception $exception ) {
			$ajax_handler->add_admin_error_message( 'Leadlovers' . $exception->getMessage() );
		}
	}

	private function create_lead_object( Form_Record $record, $form_settings, $ajax_handler ) {
		$email = $this->get_mapped_field( $record, 'email' );
		$phone = $this->get_mapped_field( $record, 'phone' );
		$machine_type = $form_settings['leadlovers_machine_type'];

		// $ajax_handler->add_response_data( "fields", $record->get( 'fields') );
		// $ajax_handler->add_response_data( "form_settings",  $record->get( 'form_settings') );
		// $ajax_handler->add_response_data( "leadlovers_input_fields", $record->get_form_settings( 'leadlovers_input_fields' ) );
		
		switch ($machine_type) {
			case 'Email':
				if ( ! $email ) {
					$this->MACHINE_TYPE_ERROR_MSG = 'email';
					return false;
				}
				break;
				
			case 'SMS':
			case 'Whatsapp':
				if ( ! $phone ) {
					$this->MACHINE_TYPE_ERROR_MSG = 'phone';
					return false;
				}
				break;
		}
		
		$lead = $this->get_leadlovers_custom_fields( $record, $ajax_handler );
		
    $lead['email'] = sanitize_email($email);
		$lead['phone'] = sanitize_text_field($phone);

		if ($form_settings['leadlovers_lead_tags'] !== "") {
			for ( $i =0; $i < count( $form_settings['leadlovers_lead_tags'] ); $i++ ) {    
				$lead['tags'][$i] = (int)$form_settings['leadlovers_lead_tags'][$i];
			}
		}
		
		
		$lead['machineId'] = (int)$form_settings['leadlovers_machine'];
		$lead['funnelId'] = (int)$form_settings['leadlovers_funnel'];
		$lead['funnelStatus'] = (int)$form_settings['leadlovers_sequence'];

		return $lead;
	}

	private function get_leadlovers_custom_fields( Form_Record $record, $ajax_handler ) {			
		$custom_fields = [];
		$capture_fields = [];
		$parameters_fields = [];

		$form_fields = $record->get( 'fields' );
		
		$leadlovers_fixed_fields = array($record->get_form_settings( 'leadlovers_input_fields' ));
		$leadlovers_dynamic_fields = array($record->get_form_settings( 'leadlovers_dynamic_input_fields' ));
		$leadlovers_utm_parameters = array($record->get_form_settings( 'leadlovers_utm_parameters' ));

		$field_mapping = array_merge($leadlovers_fixed_fields[0], $leadlovers_dynamic_fields[0], $leadlovers_utm_parameters[0]);


		foreach ( $field_mapping as $map_item ) {
			if ( in_array( $map_item['remote_id'], [ 'email', 'phone' ] ) ) {
				continue;
			}

			if ( empty( $map_item['local_id'] ) ) {
				continue;
			}

			foreach ( $form_fields as $id => $field ) {
				if ( $id !== $map_item['local_id'] ) {
					continue;
				}
				if(is_numeric($map_item['remote_id'])) {
					$capture_field['id'] = $map_item['remote_id']; 
					$capture_field['value'] =  sanitize_text_field($field['value']); 
					array_push($capture_fields, $capture_field);
				} elseif (in_array( $map_item['remote_id'], [ 'utm_source', 'utm_term', 'utm_medium', 'utm_campaign', 'utm_content' ] )) {
					$parameters_fields[ $map_item['remote_id'] ] = sanitize_text_field($field['value']);
				} else {
					$custom_fields[ $map_item['remote_id'] ] = sanitize_text_field($field['value']);
				}
			}
		}

		if (sizeof($parameters_fields) > 0) {
			if (!empty($parameters_fields['utm_source']) || 
				!empty($parameters_fields['utm_term']) ||
				!empty($parameters_fields['utm_medium']) ||
				!empty($parameters_fields['utm_campaign']) ||
				!empty($parameters_fields['utm_content'])) {
					$custom_fields['parameters'] = $parameters_fields;
				}
		}

		$custom_fields['captureFields'] = $capture_fields;

		return $custom_fields;
  }


	private function get_mapped_field( Form_Record $record, $field_id ) {
		$fields = $record->get( 'fields' );
		foreach ( $record->get_form_settings( 'leadlovers_input_fields' ) as $map_item ) {
			if ( empty( $fields[ $map_item['local_id'] ]['value'] ) ) {
				continue;
			}

			if ( $field_id === $map_item['remote_id'] ) {
				return $fields[ $map_item['local_id'] ]['value'];
			}
        }
        
        foreach ( $record->get_form_settings( 'leadlovers_dynamic_input_fields' ) as $map_item ) {
			if ( empty( $fields[ $map_item['local_id'] ]['value'] ) ) {
				continue;
			}

			if ( $field_id === $map_item['remote_id'] ) {
				return $fields[ $map_item['local_id'] ]['value'];
			}
		}

		foreach ( $record->get_form_settings( 'leadlovers_utm_parameters' ) as $map_item ) {
			if ( empty( $fields[ $map_item['local_id'] ]['value'] ) ) {
				continue;
			}

			if ( $field_id === $map_item['remote_id'] ) {
				return $fields[ $map_item['local_id'] ]['value'];
			}
        }

		return '';
	}

	public function handle_panel_request( array $data ) {
		if ( ! empty( $data['api_key'] ) && 'default' === $data['api_key'] ) {
			$api_key = $this->get_global_api_key();
		} elseif ( ! empty( $data['custom_api_key'] ) ) {
			$api_key = $data['custom_api_key'];
		}

		if ( empty( $api_key ) ) {
			throw new \Exception( '`api_key` is required', 400 );
		}

		$handler = new Leadlovers_Handler( $api_key );
		
		switch ($data['leadlovers_action']) {
			
			case 'machines':
				return $handler->get_machines();
			    break;
			    
			case 'machine_infos':
				if ( empty( $data['machine'] ) ) {
					throw new \Exception( '`machine` is required', 400 );
				}
				return $handler->get_machine_infos($data['machine']);
			    break;
			    
			case 'funnels':
				if ( empty( $data['machine'] ) ) {
					throw new \Exception( '`machine` is required', 400 );
				}
				return $handler->get_funnels($data['machine']);
			    break;
			    
			case 'sequences':
				if ( empty( $data['machine'] ) || empty( $data['funnel'] ) ) {
					throw new \Exception( '`machine and funnel` is required', 400 );
				}
				return $handler->get_sequences($data['machine'], $data['funnel']);
					break;
			
			case 'lead_tags':
				return $handler->get_tags();
				break;

			case 'captureFields':
				return $handler->get_capture_fields();
					break;
		}
	}
	
	public function ajax_validate_api_token() {
		check_ajax_referer( self::OPTION_NAME_API_KEY, '_nonce' );
		if ( ! isset( $_POST['api_key'] ) ) {
			wp_send_json_error();
		}
		try {
			$sanitized_api_key = sanitize_key($_POST['api_key']);
			new Leadlovers_Handler($sanitized_api_key);
			
		} catch ( \Exception $exception ) {
			wp_send_json_error();
		}
		wp_send_json_success();
	}

	public function register_admin_fields( Settings $settings ) {
		$settings->add_section( Settings::TAB_INTEGRATIONS, 'leadlovers', [
			'callback' => function() {
				echo '<hr><h2>' . esc_html__( 'Leadlovers', 'elementor-pro' ) . '</h2>';
			},
			'fields' => [
				self::OPTION_NAME_API_KEY => [
					'label' => __( 'Token Pessoal', 'elementor-pro' ),
					'field_args' => [
						'type' => 'text',
						'desc' => sprintf( __( 'Para integrar seus formulários com a leadlovers, você precisa informar seu <a href="%s" target="_blank">Token Pessoal</a> disponibilizado na sua conta leadlovers.', 'elementor-pro' ), 'https://app.leadlovers.com/settings' ),
					],
				],
				'validate_api_data' => [
					'field_args' => [
						'type' => 'raw_html',
						'html' => sprintf( '<button data-action="%s" data-nonce="%s" class="button elementor-button-spinner" id="elementor_pro_leadlovers_api_key_button">%s</button>', self::OPTION_NAME_API_KEY . '_validate', wp_create_nonce( self::OPTION_NAME_API_KEY ), __( 'Validar Token Pessoal', 'elementor-pro' ) ),
					],
				],
			],
		]);
	}

	public function __construct() {
		if ( is_admin() ) {
			add_action( 'elementor/admin/after_create_settings/' . Settings::PAGE_ID, [ $this, 'register_admin_fields' ], 15 );
		}
		add_action( 'wp_ajax_' . self::OPTION_NAME_API_KEY . '_validate', [ $this, 'ajax_validate_api_token' ] );
	}
}
