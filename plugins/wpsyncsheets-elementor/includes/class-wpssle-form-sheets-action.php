<?php
/**
 * Custom elementor form action after submit to add a records to
 * Google Spreadsheet
 *
 * @since 1.0.0
 * @package wpsyncsheets-elementor
 */

use ElementorPro\Plugin;
use Elementor\Controls_Manager;
use ElementorPro\Modules\Forms\Classes\Action_Base;
use ElementorPro\Modules\Forms\Controls\Fields_Map;
use WPSyncSheetsElementor\WPSSLE_Google_API_Functions;
/**
 * Class WPSSLE_Form_Sheets_Action
 */
class WPSSLE_Form_Sheets_Action extends \ElementorPro\Modules\Forms\Classes\Action_Base {
	/**
	 * Get Name
	 * Return the action name
	 *
	 * @access public
	 * @return string
	 */
	public function get_name() {
		return esc_html( 'WPSyncSheets' );
	}
	/**
	 * Get Label
	 * Returns the action label
	 *
	 * @access public
	 * @return string
	 */
	public function get_label() {
		return esc_html__( 'WPSyncSheets', 'wpsse' );
	}
	/**
	 * Run
	 * Runs the action after submit
	 *
	 * @access public
	 * @param \ElementorPro\Modules\Forms\Classes\Form_Record  $record Record.
	 * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler Ajax handler.
	 */
	public function run( $record, $ajax_handler ) {
		$wpssle_settings = $record->get( 'form_settings' );
		// Get sumitetd Form data.
		$wpssle_raw_fields = $record->get( 'fields' );
		$instance_api      = new WPSyncSheetsElementor\WPSSLE_Google_API_Functions();
		if ( ! $instance_api->checkcredenatials() ) {
			return;
		}
		if ( isset( $wpssle_settings['submit_actions'] ) && in_array( $this->get_name(), $wpssle_settings['submit_actions'], true ) ) {
			$wpssle_spreadsheetid = $wpssle_settings['spreadsheetid'];
			$wpssle_sheetname     = $wpssle_settings['sheet_name'];
			$wpssle_sheetarray    = $instance_api->get_spreadsheet_listing();
			if ( ! empty( $wpssle_spreadsheetid ) && ! array_key_exists( $wpssle_spreadsheetid, $wpssle_sheetarray ) ) {
				return;
			} elseif ( ! empty( $wpssle_spreadsheetid ) ) {
				$response = $instance_api->get_sheet_listing( $wpssle_spreadsheetid );
				foreach ( $response->getSheets() as $s ) {
					$wpssle_sheets[] = $s['properties']['title'];
				}
				if ( ! empty( $wpssle_sheetname ) && ! in_array( $wpssle_sheetname, $wpssle_sheets, true ) ) {
					return;
				}
			}
			if ( empty( $wpssle_spreadsheetid ) || empty( $wpssle_sheetname ) ) {
				return;
			}
			// Normalize the Form Data.
			$wpssle_fields = array();
			foreach ( $wpssle_raw_fields as $id => $field ) {
				$wpssle_fields[ $id ] = $field['value'];
			}
			try {
				$wpssle_headers    = $wpssle_settings['sheet_headers'];
				$wpssle_value_data = array();
				foreach ( $wpssle_headers as $wpssle_fieldvalue ) {
					if ( array_key_exists( $wpssle_fieldvalue, $wpssle_fields ) ) {
						if ( is_array( $wpssle_fields[ $wpssle_fieldvalue ] ) ) {
							$wpssle_value_data[] = implode( ',', $wpssle_fields[ $wpssle_fieldvalue ] );
						} else {
							$wpssle_value_data[] = $wpssle_fields[ $wpssle_fieldvalue ];
						}
					} else {
						$wpssle_value_data[] = '';
					}
				}
				$wpssle_sheet         = "'" . $wpssle_sheetname . "'!A:A";
				$wpssle_allentry      = $instance_api->get_row_list( $wpssle_spreadsheetid, $wpssle_sheet );
				$wpssle_data          = $wpssle_allentry->getValues();
				$wpssle_data          = array_map(
					function( $wpssle_element ) {
						if ( isset( $wpssle_element['0'] ) ) {
							return $wpssle_element['0'];
						} else {
							return '';
						}
					},
					$wpssle_data
				);
				$wpssle_rangetoupdate = $wpssle_sheetname . '!A' . ( count( $wpssle_data ) + 1 );
				$wpssle_requestbody   = $instance_api->valuerangeobject( array( $wpssle_value_data ) );
				$wpssle_params        = WPSSLE_Plugin_Setting::get_row_format();
				$param                = $instance_api->setparamater( $wpssle_spreadsheetid, $wpssle_rangetoupdate, $wpssle_requestbody, $wpssle_params );
				$instance_api->updateentry( $param );
			} catch ( Exception $e ) {
				$ajax_handler->add_admin_error_message( 'WPSyncSheets ' . $e->getMessage() );
			}
		}
	}
	/**
	 * Register Settings Section
	 * Registers the Action controls
	 *
	 * @access public
	 * @param \Elementor\Widget_Base $widget settings.
	 */
	public function register_settings_section( $widget ) {
		$instance_api           = new \WPSyncSheetsElementor\WPSSLE_Google_API_Functions();
		$wpssle_google_settings = $instance_api->wpssle_option( 'wpsse_google_settings' );
		global $wpssle_headers, $wpssle_exclude_headertype;
		global $wpssle_spreadsheetid , $wpssle_sheetname, $wpssle_sheet_headers, $wpssle_sheetheaders, $existincurrentpage, $wpssle_sheetheaders_new ,$wpssle_form_fields;
		$existincurrentpage        = 'no';
		$wpssle_sheetheaders       = array();
		$wpssle_sheetheaders_new   = array();
		$wpssle_form_fields        = array();
		$wpssle_exclude_headertype = array( 'honeypot', 'recaptcha', 'recaptcha_v3', 'html' );
		$wpssle_document           = Plugin::elementor()->documents->get( get_the_ID() );
		if ( $wpssle_document ) {
			$wpssle_data        = $wpssle_document->get_elements_data();
			$wpssle_data_global = $wpssle_data;
			global $wpssle_type;
			$wpssle_type = '';
			$wpssle_data = Plugin::elementor()->db->iterate_data(
				$wpssle_data,
				function( $element ) use ( &$do_update ) {
					if ( isset( $element['widgetType'] ) && 'form' === (string) $element['widgetType'] ) {
						global $wpssle_headers, $wpssle_exclude_headertype;
						global $wpssle_spreadsheetid , $wpssle_sheetname, $wpssle_sheet_headers;
						$wpssle_exclude_headertype = array( 'honeypot', 'recaptcha', 'recaptcha_v3', 'html' );
						if ( isset( $element['settings']['spreadsheetid'] ) ) {
							$wpssle_spreadsheetid = $element['settings']['spreadsheetid'];
						}
						if ( isset( $element['settings']['sheet_name'] ) ) {
							$wpssle_sheetname = $element['settings']['sheet_name'];
						}
						if ( isset( $element['settings']['sheet_headers'] ) ) {
							$wpssle_sheet_headers = $element['settings']['sheet_headers'];
						}
						foreach ( $element['settings']['form_fields'] as $formdata ) {
							if ( ! isset( $formdata['field_type'] ) || ( isset( $formdata['field_type'] ) && ! in_array( $formdata['field_type'], $wpssle_exclude_headertype, true ) ) ) {
								$wpssle_headers[ $formdata['custom_id'] ] = $formdata['field_label'] ? $formdata['field_label'] : ucfirst( $formdata['custom_id'] );
							}
						}
						return $wpssle_headers;
					}
				}
			);
			if ( empty( $wpssle_headers ) ) {
				Plugin::elementor()->db->iterate_data(
					$wpssle_data_global,
					function( $element ) use ( &$do_update ) {
						if ( isset( $element['widgetType'] ) && 'global' === (string) $element['widgetType'] ) {
							if ( ! empty( $element['templateID'] ) ) {
								$global_form      = get_post_meta( $element['templateID'], '_elementor_data', true );
								$global_form_meta = json_decode( $global_form, true );
								if ( $global_form_meta ) {
									global $wpssle_headers, $wpssle_exclude_headertype;
									global $wpssle_spreadsheetid , $wpssle_sheetname, $wpssle_sheet_headers;
									$wpssle_exclude_headertype = array( 'honeypot', 'recaptcha', 'recaptcha_v3', 'html' );
									if ( isset( $global_form_meta[0]['settings']['spreadsheetid'] ) ) {
										$wpssle_spreadsheetid = $global_form_meta[0]['settings']['spreadsheetid'];
									}
									if ( isset( $global_form_meta[0]['settings']['sheet_name'] ) ) {
										$wpssle_sheetname = $global_form_meta[0]['settings']['sheet_name'];
									}
									if ( isset( $global_form_meta[0]['settings']['sheet_headers'] ) ) {
										$wpssle_sheet_headers = $global_form_meta[0]['settings']['sheet_headers'];
									}
									if ( is_array( $global_form_meta[0]['settings']['form_fields'] ) ) {
										foreach ( $global_form_meta[0]['settings']['form_fields'] as $formdata ) {
											if ( ! isset( $formdata['field_type'] ) || ( isset( $formdata['field_type'] ) && ! in_array( $formdata['field_type'], $wpssle_exclude_headertype, true ) ) ) {
												$wpssle_headers[ $formdata['custom_id'] ] = $formdata['field_label'] ? $formdata['field_label'] : ucfirst( $formdata['custom_id'] );
											}
										}
									}
									return $wpssle_headers;
								}
							}
						}
					}
				);
			}
		}
		if ( ! is_array( $wpssle_sheetheaders ) ) {
			$wpssle_sheetheaders = array();
		}
		if ( empty( $wpssle_google_settings[2] ) ) {
			$wpssle_html = sprintf(
				'<div class="elementor-control-raw-html elementor-panel-alert elementor-panel-alert-danger">%1$s<a href="admin.php?page=wpsyncsheets-elementor"> <strong>%2$s</strong></a>.</div>',
				esc_html__( 'Please genearate authentication code from Google Sheet Setting', 'wpsse' ),
				esc_html__( 'Click Here', 'wpsse' )
			);
			$widget->start_controls_section(
				'section_notice_wpsse',
				array(
					'label'     => esc_attr__( 'WPSyncSheets', 'wpsse' ),
					'condition' => array(
						'submit_actions' => $this->get_name(),
					),
				)
			);
			$widget->add_control(
				'setup_clientidsecret',
				array(
					'type' => \Elementor\Controls_Manager::RAW_HTML,
					'raw'  => $wpssle_html,
				)
			);
			$widget->end_controls_section();
		} elseif ( ! empty( $wpssle_google_settings[2] ) && ! $instance_api->checkcredenatials() ) {
			$wpssle_error = $instance_api->getClient( 1 );
			if ( 'Invalid token format' === (string) $wpssle_error || 'invalid_grant' === (string) $wpssle_error ) {
				$wpssle_html = sprintf(
					'<div class="elementor-control-raw-html elementor-panel-alert elementor-panel-alert-danger">%1$s<a href="admin.php?page=wpsyncsheets-elementor"> <strong>%2$s</strong></a>.</div>',
					esc_html__( 'Error: Invalid Token - Revoke Token with Google Sheet Setting and try again.', 'wpsse' ),
					esc_html__( 'Click Here', 'wpsse' )
				);
			} else {
				$wpssle_html = sprintf(
					'<div class="elementor-control-raw-html elementor-panel-alert elementor-panel-alert-danger">%1$s</div>',
					'Error: ' . $wpssle_error
				);
			}
			$widget->start_controls_section(
				'section_notice_wpsse',
				array(
					'label'     => esc_attr__( 'WPSyncSheets', 'wpsse' ),
					'condition' => array(
						'submit_actions' => $this->get_name(),
					),
				)
			);
			$widget->add_control(
				'setup_clientidsecret',
				array(
					'type' => \Elementor\Controls_Manager::RAW_HTML,
					'raw'  => $wpssle_html,
				)
			);
			$widget->end_controls_section();
		} else {
			$wpssle_spreadsheets = $instance_api->get_spreadsheet_listing();
			$wpssle_sheets       = array();
			if ( ! empty( $wpssle_spreadsheetid ) && array_key_exists( $wpssle_spreadsheetid, $wpssle_spreadsheets ) ) {
				$response = $instance_api->get_sheet_listing( $wpssle_spreadsheetid );
				foreach ( $response->getSheets() as $s ) {
					$wpssle_sheets[] = $s['properties']['title'];
				}
			}
			$widget->start_controls_section(
				'section_wpsse',
				array(
					'label'     => esc_attr__( 'WPSyncSheets', 'wpsse' ),
					'condition' => array(
						'submit_actions' => $this->get_name(),
					),
				)
			);
			$widget->add_control(
				'spreadsheetid',
				array(
					'label'       => esc_attr__( 'Select Spreadsheet', 'wpsse' ),
					'type'        => \Elementor\Controls_Manager::SELECT,
					'options'     => $wpssle_spreadsheets,
					'label_block' => true,
					'separator'   => 'before',
				)
			);
			$widget->add_control(
				'new_spreadsheet_name',
				array(
					'label'       => esc_attr__( 'Spreadsheet Name', 'wpsse' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'label_block' => true,
					'condition'   => array(
						'spreadsheetid' => 'new',
					),
				)
			);
			$widget->add_control(
				'sheet_name',
				array(
					'label'       => esc_attr__( 'Sheet Name', 'wpsse' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'label_block' => true,
				)
			);
			$widget->add_control(
				'sheet_list',
				array(
					'label'       => esc_attr__( 'Select Sheet Name', 'wpsse' ),
					'type'        => \Elementor\Controls_Manager::SELECT,
					'label_block' => true,
					'options'     => $wpssle_sheets,
				)
			);
			$widget->add_control(
				'sheet_headers',
				array(
					'label'       => esc_attr__( 'Sheet Headers', 'wpsse' ),
					'type'        => \Elementor\Controls_Manager::SELECT2,
					'multiple'    => true,
					'options'     => $wpssle_headers,
					'label_block' => true,
				)
			);
			$widget->add_control(
				'freeze_header',
				array(
					'label'        => esc_attr__( 'Freeze Headers', 'wpsse' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'label_off'    => 'No',
					'label_on'     => 'Yes',
					'return_value' => 'yes',
				)
			);
			$widget->end_controls_section();
		}
	}
	/**
	 * On Export
	 * Clears form settings on export
	 *
	 * @access public
	 * @param array $element_sheets clear settings.
	 */
	public function on_export( $element_sheets ) {
	}
}
