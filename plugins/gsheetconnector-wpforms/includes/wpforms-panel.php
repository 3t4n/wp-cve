<?php

/**
 * Class FormBuilder handles functionality inside the form builder.
 *
 * @since 1.0.0
 */
class WPGS_FormBuilder {
	
	
	public $_wpgs_googlesheet = false;
	/**
	 * White list of field types to allow for mapping select.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $allowed_field_types = [];

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		$this->hooks();
	}
	
	/**
	 * Hooks.
	 *
	 * @since 1.0.0
	 */
	protected function hooks() {

		add_action( 'wpforms_form_settings_panel_content', [ $this, 'panel_content' ],   50 );
		 // save entry with posted data
        add_action('wpforms_process_entry_save', array($this, 'entry_save'), 30, 4);

		add_action( 'wpforms_builder_enqueues',            [ $this, 'enqueue_assets' ],  10 );
		add_filter( 'wpforms_builder_settings_sections',   [ $this, 'panel_sidebar' ],   50, 2 );
		add_filter( 'wpforms_builder_strings',             [ $this, 'builder_strings' ], 50, 2 );
		add_filter( 'wpforms_helpers_templates_include_html_located', [ $this, 'templates' ], 10, 4 );
		
		add_filter( 'wpforms_save_form_args', [ $this, 'save_form_args' ], 11, 3 );
		
	}
	 

	  public function entry_save($fields, $entry, $form_id, $form_data = '') { 
      
      $data = array();
      
      // Get Entry Id
      $entry_id = wpforms()->process->entry_id;
               
      // get form data
      $new_setting = get_post_meta($form_id, 'wpform_gs_settings_new');


      // By mistake added wpform_gs_settings_new setting so moved new setting to old. 
      if(!empty($new_setting)){
      	update_post_meta($form_id, 'wpform_gs_settings', $new_setting[0]);
      }
      // By mistake added wpform_gs_settings_new setting so moved new setting to old.

      $form_data_get = get_post_meta($form_id, 'wpform_gs_settings');

      if(!empty($form_data_get)){
      	

      $sheet_name = isset( $form_data_get[0]['gs_sheet_manuals_sheet_name'] ) ? $form_data_get[0]['gs_sheet_manuals_sheet_name'] : "";

      $sheet_id = isset( $form_data_get[0]['gs_sheet_manuals_sheet_id'] ) ? $form_data_get[0]['gs_sheet_manuals_sheet_id'] : "";

      $sheet_tab_name = isset( $form_data_get[0]['gs_sheet_manuals_sheet_tab_name'] ) ? $form_data_get[0]['gs_sheet_manuals_sheet_tab_name'] : "";

      $tab_id = isset( $form_data_get[0]['gs_sheet_manuals_sheet_tab_id'] ) ? $form_data_get[0]['gs_sheet_manuals_sheet_tab_id'] : "";
      
      $payment_type = array( "payment-single", "payment-multiple", "payment-select", "payment-total" );

      if ((!empty($sheet_name) ) && (!empty($sheet_tab_name) )) {
         try {
            include_once( WPFORMS_GOOGLESHEET_ROOT . "/lib/google-sheets.php" );
            $doc = new wpfgsc_googlesheet();
            $doc->auth();
            $doc->setSpreadsheetId($sheet_id);
            $doc->setWorkTabId($tab_id);

            //$timestamp = strtotime(date("Y-m-d H:i:s"));
            // Fetched local date and time instaed of unix date and time
            $data['date'] = date_i18n(get_option('date_format'));
            $data['time'] = date_i18n(get_option('time_format'));
            
            foreach ($fields as $k => $v) {
               $get_field = $fields[$k];
               $key = $get_field['name'];
               $value = $get_field['value'];
               if( in_array( $get_field['type'], $payment_type ) ) {
                  $value =  html_entity_decode( $get_field['value'] );
               }
               $data[$key] = $value;
            }             
            $doc->add_row($data);
         } catch (Exception $e) {
            $data['ERROR_MSG'] = $e->getMessage();
            $data['TRACE_STK'] = $e->getTraceAsString();
            Wpform_gs_Connector_Utility::gs_debug_log($data);
         }
      }
  }
   }
   
   
	
	public function get_data_to_submit( $entry_cells, $googlesheet, $spreadsheet_id, $tab_id, $header_cells = false ) {
		
		try {
			
			if( ! $header_cells ) {
				$wpgs_googlesheet = $this->get_wpgs_googlesheet();
				$header_cells = $wpgs_googlesheet->get_header_row($spreadsheet_id, $tab_id);
			}
			$send_row_data = array();
			
			if( $entry_cells && $header_cells && $spreadsheet_id ) {
					
				foreach( $header_cells as $index => $cellName ) {
					
					if( isset( $entry_cells[$cellName] ) ) {
						$send_row_data[$index] = $entry_cells[$cellName];
					}
				}
				
				foreach( $header_cells as $index => $cellName ) {
					if( ! isset( $send_row_data[$index] ) ) {
						$send_row_data[$index] = "";
					}
				}
			}		
		}
		catch(Exception $e) {			
			Wpform_gs_Connector_Utility::gs_debug_log( __METHOD__ . " Error while adding the entry to sheet: \n " . $e->getMessage() );
			return array();
		}
		return $send_row_data;
	}
	
	function get_google_sheet_settings($googlesheet, $formdata = false) {
		
		$connection_mode = "manual";
		
		$wpgs_googlesheet = $this->get_wpgs_googlesheet();
		
		
			$spreadsheet_id = $googlesheet['gs_sheet_manuals_sheet_id'];
			$tab_id = $googlesheet['gs_sheet_manuals_sheet_tab_id'];
			$tab_title = $googlesheet['gs_sheet_manuals_sheet_tab_name'];
			$spreadsheet_title = $googlesheet['gs_sheet_manuals_sheet_name'];
		
		$sheet_info = array(
			"spreadsheet_id" => $spreadsheet_id,
			"tab_id" => $tab_id,
			"tab_title" => $tab_title,
			"spreadsheet_title" => $spreadsheet_title,
		);
		$sheet_info = apply_filters( "gcgf_filter_sheet_info", $sheet_info );
		return $sheet_info;
	}
	
	public function update_entry_meta_googlesheet( $wpgs_feed_id, $googlesheet, $entry_id, $form_id, $user_id ) {
		
		$meta = wpforms()->entry_meta->add(
			array(
				'entry_id' => $entry_id,
				'form_id'  => $form_id,
				'user_id'  => get_current_user_id(),
				'type'     => 'entry_added',
				'status'     => 'success',
				'data'     => json_encode ( [
						"wpgs_feed_id" => $wpgs_feed_id,
						"googlesheet_name" => $googlesheet['name'],
						"googlesheet" => $googlesheet,
					]
				),
			),
			'entry_meta'
		);
		
	}
	
	/**
	 * Process Conditional Logic for the webhook.
	 *
	 * @since 1.0.0
	 *
	 * @param array $webhook Webhook data.
	 *
	 * @return bool False if CL rules stopped the connection execution, true otherwise.
	 */
	protected function is_conditionals_passed( $googlesheet, $fields, $entry, $form_data, $entry_id ) {
		
		// if( ! wpforms()->pro ) {
		// 	return true;
		// }
		
		if (
			empty( $googlesheet['conditional_logic'] ) ||
			empty( $googlesheet['conditionals'] )
		) {
			return true;
		}

		$pass = wpforms_conditional_logic()->process( $fields, $form_data, $googlesheet['conditionals'] );

		if (
			! empty( $googlesheet['conditional_type'] ) &&
			'stop' === $googlesheet['conditional_type']
		) {
			$pass = ! $pass;
		}

		// Check for conditional logic.
		if ( ! $pass ) {
			wpforms_log(
				esc_html__( 'Publishing processing stopped by conditional logic.', 'wpforms-webhooks' ),
				$fields,
				[
					'type'    => [ 'provider', 'conditional_logic' ],
					'parent'  => $entry_id,
					'form_id' => $form_data['id'],
				]
			);
		}

		return $pass;
	}
	
	/**
	 * Preprocess data before saving it in form_data when editing form.
	 *
	 * @since 1.0.0
	 *
	 * @param array $form Form array, usable with wp_update_post.
	 * @param array $data Data retrieved from $_POST and processed.
	 * @param array $args Empty by default, may have custom data not intended to be saved, but used for processing.
	 *
	 * @return array
	 */
	public function save_form_args( $form, $data, $args ) {
	
		$token = get_option('wpform_gs_access_code');
		if( ! $token ) {
			return $form;
		}
		
		$enable_filter = false;
		remove_filter('wpforms_builder_save_form_response_data', [ $this, 'set_reload_parameters_in_ajax_response' ], 10, 3);
		
		$form_data = json_decode( stripslashes( $form['post_content'] ), true );
		

		$wpgs_spreadsheets = isset ( $form_data['settings']['wpgs_spreadsheets'] ) ? $form_data['settings']['wpgs_spreadsheets'] : false;
		
		// $wfgs_google_client = $this->get_wpgs_googlesheet();	
	
		if( $wpgs_spreadsheets && is_array($wpgs_spreadsheets) && ! empty($wpgs_spreadsheets)  ){
			// echo '<pre>';print_r($wpgs_spreadsheets);die;

			foreach( $wpgs_spreadsheets as $wpgs_feed_id => $gsheet_feed ) {
				
				$integration_method = $gsheet_feed['gs_sheet_integration_mode'];
				// $gs_sheet_select_name = $gsheet_feed['gs_sheet_select_name'];				
				
				$google_sheet_settings = $this->get_google_sheet_settings($gsheet_feed, $form);

				$spreadsheet_id = $google_sheet_settings['spreadsheet_id'];
				$tab_id = $google_sheet_settings['tab_id'];
				
				if( ! $spreadsheet_id ) {
					continue;
				}
				
				 $form_id = $form_data['id'];

          $get_existing_data = get_post_meta($form_id, 'wpform_gs_settings');


         $gs_sheet_name = $gsheet_feed['gs_sheet_manuals_sheet_name'];
         $gs_sheet_id = $gsheet_feed['gs_sheet_manuals_sheet_id'];
         $gs_tab_name = $gsheet_feed['gs_sheet_manuals_sheet_tab_name'];
         $gs_tab_id = $gsheet_feed['gs_sheet_manuals_sheet_tab_id'];
         // If data exist and user want to disconnect
         if (!empty($get_existing_data) && $gs_sheet_name == "") {
            update_post_meta($form_id, 'wpform_gs_settings', "");
         }

         if (!empty($gs_sheet_name) && (!empty($gs_tab_name) )) {
            update_post_meta($form_id, 'wpform_gs_settings', $gsheet_feed);
         }
       
	    $enable_filter = true;

			}
		}
		
		if( isset ( $form_data['settings']['wfgs_force_reload'] ) && $form_data['settings']['wfgs_force_reload'] == 1 ) {
			$enable_filter = true;
			$form_data['settings']['wfgs_force_reload'] = 0;
		}
		
		if( $enable_filter ) {	
		add_filter('wpforms_builder_save_form_response_data', [ $this, 'set_reload_parameters_in_ajax_response' ], 10, 3);
		}
	
		$form['post_content'] = wpforms_encode( $form_data );
		return $form;
	}
	
	
	
	public function get_google_sheet_settings_dep( $feed, $form ) {
		
		$connection_mode = $feed['gs_sheet_integration_mode'];
		
		$wpgs_googlesheet = $this->get_wpgs_googlesheet();
		
			$spreadsheet_id = $feed['gs_sheet_manuals_sheet_id'];
			$tab_id = $feed['gs_sheet_manuals_sheet_tab_id'];
			$tab_title = $feed['gs_sheet_manuals_sheet_tab_name'];
			$spreadsheet_title = $feed['gs_sheet_manuals_sheet_name'];
				
		$sheet_info = array(
			"spreadsheet_id" => $spreadsheet_id,
			"tab_id" => $tab_id,
			"tab_title" => $tab_title,
			"spreadsheet_title" => $spreadsheet_title,
		);
		$sheet_info = apply_filters( "wpgs_filter_sheet_info", $sheet_info, $form, $feed_id );
		
		return $sheet_info;
		
	}
	
	public function get_wpgs_googlesheet() {
		
		if( $this->_wpgs_googlesheet ) {
			return $this->_wpgs_googlesheet;
		}
		
		$google_sheet = new wpfgsc_googlesheet();
		$google_sheet->auth();		
		
		$this->_wpgs_googlesheet = $google_sheet;		
		return $google_sheet;
	}
	
	/**
	 * Add forece reload parameter for new added option
	 *
	 * @since 1.0.0
	 *
	 * @param array $return_params, default parameters to be returned
	 * @param int $form_id form id
	 * @param array $data Data retrieved from $_POST and processed.
	 *
	 * @return array
	 */
	public function set_reload_parameters_in_ajax_response( $return_params, $form_id, $data ) {
		
		if( ! is_array( $return_params ) ) {
			$return_params = array( $return_params );
		}
		
		$return_params['force_reload'] = 1;
	
		return $return_params;
	}
	
	/**
	 * Change a template location.
	 *
	 * @since 1.0.0
	 *
	 * @param string $located  Template location.
	 * @param string $template Template.
	 * @param array  $args     Arguments.
	 * @param bool   $extract  Extract arguments.
	 *
	 * @return string
	 */
	public function templates( $located, $template, $args, $extract ) {

		// Checking if `$template` is an absolute path and passed from this plugin.
		if (
			( 0 === strpos( $template, WPFORMS_GOOGLESHEET_PATH.'includes' ) ) &&
			is_readable( $template )
		) {
			return $template;
		}

		return $located;
	}
	
	
	public function get_spreadsheet_id( $name ) {
		
		$wpforms_gs_sheetId = $this->get_spreadsheet_options();
		
		$spreadsheet_id = "";
		
		if( $wpforms_gs_sheetId ) {
			
			foreach($wpforms_gs_sheetId as $spreadsheet_name => $spreadsheet) {
				
				if( $spreadsheet_name == $name ) {
					
					$spreadsheet_id = $spreadsheet['id'];
				}
			}
		}
		
		return $spreadsheet_id;
	}
	
	public function get_spreadsheet_tab_id( $spreadsheet_id, $name ) {
		
		$wpforms_gs_sheetId = $this->get_spreadsheet_options();
		
		$spreadsheet_tab_id = 0;
		
		if( $wpforms_gs_sheetId ) {
			
			foreach($wpforms_gs_sheetId as $spreadsheet_name => $spreadsheet) {
				
				if( $spreadsheet_id == $spreadsheet['id'] ) {
					
					$available_tabs = $spreadsheet['tabId'];
					foreach( $available_tabs as $tab_name => $tab_id ) {
						
						if( $name == $tab_name ) {
							$spreadsheet_tab_id = $tab_id;
						}
					}
				}
			}
		}
		
		return $spreadsheet_tab_id;
	}
	
	public function get_available_spreadsheets() {
		
		$wpforms_gs_sheetId = $this->get_spreadsheet_options();
		
		if( $wpforms_gs_sheetId && is_array( $wpforms_gs_sheetId ) ) {
			
			foreach( $wpforms_gs_sheetId as $spreadsheet_name => $spreadsheet ) {
				$spreadsheet_id = $spreadsheet['id'];
				$spreadsheets[$spreadsheet_id] = $spreadsheet_name;
			}
		}
		
		$spreadsheets["create_new"] = "Create New";
		return $spreadsheets;
	}
	
	public function get_available_tabs( $selected_spreadsheet_id ) {
		
		$wpforms_gs_sheetId = $this->get_spreadsheet_options();
		
		$spreadsheet_tabs = array();
		
		if( $wpforms_gs_sheetId && is_array( $wpforms_gs_sheetId ) ) {
			
			foreach( $wpforms_gs_sheetId as $spreadsheet_name => $spreadsheet ) {
				$spreadsheet_id = $spreadsheet['id'];
				if( $spreadsheet_id == $selected_spreadsheet_id ) {
					
					$available_tabs = $spreadsheet['tabId'];
					foreach( $available_tabs as $tab_name => $tab_id ) {
						$spreadsheet_tabs[$tab_id] = $tab_name;
					}
				}
				
			}
		}
		if( ! $spreadsheet_tabs ) {
			$spreadsheet_tabs = array( "0" => "Select" );
		}
		return $spreadsheet_tabs;
	}
	
	public function get_spreadsheet_options() {
		
		$wpforms_gs_sheetId = get_option('wpforms_gs_sheetId', true);
		
		$blank_sheet = array(
			"Select Spreadsheet" => array(
				"id" => 0,
				"tabId" => array(
					"Select Sheet First" => "0"
				)
			)
		);
		if(isset($wpforms_gs_sheetId) && (is_array($wpforms_gs_sheetId)))				
			$wpforms_gs_sheetId = array_merge( $blank_sheet, $wpforms_gs_sheetId );
		else
			$wpforms_gs_sheetId = array();
		
		return $wpforms_gs_sheetId;
	}
	
	/**
	 * Add a content for `WFGS Googlesheet` panel.
	 *
	 * @since 1.0.0
	 *
	 * @param \WPForms_Builder_Panel_Settings $builder_panel_settings WPForms_Builder_Panel_Settings object.
	 */
	public function panel_content( $builder_panel_settings ) {

		$settings = $builder_panel_settings->form_data['settings'];
		$wpgs_spreadsheets = isset ( $settings['wpgs_spreadsheets'] ) ? $settings['wpgs_spreadsheets'] : array() ;
		
		if ( empty( $wpgs_spreadsheets ) ) {
			/* translators: %s - form name. */
			$wpgs_spreadsheets[1]['name']        = "Google Sheet";
			$wpgs_spreadsheets[1]['gsheetconnector-wpforms']        = false;
			$wpgs_spreadsheets[1]['gs_sheet_integration_mode']        = "manual";
			$wpgs_spreadsheets[1]['gs_sheet_select_name']        = "";
			$wpgs_spreadsheets[1]['gs_sheet_select_tab']        = "";
			$wpgs_spreadsheets[1]['gs_sheet_manuals_sheet_name']        = "";
			$wpgs_spreadsheets[1]['gs_sheet_manuals_sheet_id']        = "";
			$wpgs_spreadsheets[1]['gs_sheet_manuals_sheet_tab_id']        = "";
			$wpgs_spreadsheets[1]['gs_sheet_manuals_sheet_tab_name']        = "";
		}
		// echo '<pre>';print_r($wpgs_spreadsheets);die;
		$next_id = max( array_keys( $wpgs_spreadsheets ) ) + 1;
		
		$add_new_btn_classes = $this->get_html_class(
			array(
				'wpforms-builder-settings-block-add',
				'wpforms-webooks-add',
			),
			$builder_panel_settings
		);
		
		$token = get_option('wpform_gs_access_code');
		?>
				
		<div class="wpforms-panel-content-section wpforms-panel-content-section-wf_googlesheets">
			
			
			<!-- <div class="wpforms-panel-content-section-title">
				<?php esc_html_e( 'GSheetConnector Feed Settings', 'gsheetconnector-wpforms' ); ?>
				<span class='syncBtnPro wpforms-builder-settings-block-add wpforms-webooks-add' style="float: right;
    margin-left: 5px;">Pro</span>
				<button <?php echo ! $token ? "style='display: none';" : ""; ?> type="button" class="btnstylewpform wpforms-builder-settings-block-add wpforms-webooks-add" data-block-type="googlesheet" data-next-id="<?php echo absint( $next_id ); ?>"><?php esc_html_e( 'Add New Feed', 'gsheetconnector-wpforms' ); ?></button>
			</div> -->

			
			 
			<?php 
			// Check if the user is authenticated
			$authenticated =  get_option('wpform_gs_token');
			if((empty($authenticated)) || !empty(get_option('wpform_gs_verify')) && (get_option('wpform_gs_verify') == "invalid-auth") ) {
				echo "<p class='wp-gs-display-note'>".__('<strong>Authentication Required:</strong>
                  You must have to <a href="admin.php?page=wpform-google-sheet-config" target="_blank">Authenticate using your Google Account</a> along with Google Drive and Google Sheets Permissions in order to enable the settings for configuration.</p>', 'gsheetconnector-wpforms')."</h3>";
			}
			else {
				$wpforms_gs_sheetId = $this->get_spreadsheet_options();
				echo $this->get_enable_control_html( $wpgs_spreadsheets, $builder_panel_settings );
				echo $this->get_fields_html( $wpgs_spreadsheets, $builder_panel_settings );
			?>
			<input type="hidden" name="settings[wfgs_force_reload]" value=0 class="wfgs_force_reload">
			
			<!-- <input type="hidden" value='<?php echo json_encode($wpforms_gs_sheetId); ?>' id="gs_sheet_select_sheets_list">Old Code -->
			<textarea style="display: none;" id="gs_sheet_select_sheets_list"><?php echo json_encode($wpforms_gs_sheetId); ?></textarea><!-- New Code : Resolved issue : Apostrophe(') issue while fetching sheets and tabs. -->
			
			<?php } ?>
		</div>
		
		<?php				
	}


	/**
	 * Retrieve a HTML for On/Off select control.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_enable_control_html( $wpgs_spreadsheets, $builder_panel_settings ) {
// echo '<pre>';print_r($builder_panel_settings);die;
		return wpforms_panel_field(
			'select',
			'settings',
			'gsheetconnector-wpforms',
			$builder_panel_settings->form_data,
			esc_html__( 'Enable Settings', 'gsheetconnector-wpforms' ),
			[
				'default' => '0',
				'options' => [
					'1' => esc_html__( 'On', 'gsheetconnector-wpforms' ),
					'0' => esc_html__( 'Off', 'gsheetconnector-wpforms' ),
				],
			],
			false
		);
	}


	/**
	 * Retrieve a HTML for settings.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_fields_html( $wpgs_spreadsheets, $builder_panel_settings ) {

		$result   = '';
			// echo '<pre>';print_r($wpgs_spreadsheets);die;
		foreach ( $wpgs_spreadsheets as $wpgs_feed_id => $googlesheet ) {
			
			$googlesheet['wpgs_feed_id'] = $wpgs_feed_id;
// echo '<pre>';print_r( $googlesheet);
			$result .= $this->get_fields_block( $googlesheet, $builder_panel_settings );
		}

		return $result;
	}


	/**
	 * Retrieve a HTML for setting block.
	 *
	 * @since 1.0.0
	 *
	 * @param array $googlesheet Googlesheet data.
	 * @param array $builder_panel_settings form settings data.
	 *
	 * @return string
	 */
	protected function get_fields_block( $googlesheet, $builder_panel_settings ) {

		$wpgs_feed_id   = $googlesheet['wpgs_feed_id'];
		$form_data    = $builder_panel_settings->form_data;
		$toggle_state = '<i class="fa fa-chevron-up"></i>';
		$closed_state = '';
				
		if (
			! empty( $form_data['id'] ) &&
			'closed' === wpforms_builder_settings_block_get_state( $form_data['id'], $wpgs_feed_id, 'googlesheet' )
		) {
			$toggle_state = '<i class="fa fa-chevron-down"></i>';
			$closed_state = 'style="display:none;"';
		}
		
		$block_classes = $this->get_html_class(
			array(
				'wpforms-builder-settings-block',
				'wpforms-builder-settings-block-googlesheet',
				'feed-block-'.$wpgs_feed_id,
			),
			$builder_panel_settings
		);
		
		?>
		
		<div class="<?php echo esc_attr( $block_classes ); ?>" data-block-type="googlesheet" data-block-id="<?php echo absint( $wpgs_feed_id ); ?>">
			<div class="wpforms-builder-settings-block-header">
				<div class="wpforms-builder-settings-block-actions">
					<button class="wpforms-builder-settings-block-edit"><i class="fa fa-pencil"></i></button>
					<button class="wpforms-builder-settings-block-toggle"><?php echo wp_kses_post( $toggle_state ); ?></button>
					<button class="wpforms-builder-settings-block-delete"><i class="fa fa-times-circle"></i></button>
				</div>

				<div class="wpforms-builder-settings-block-name-holder">
					<span class="wpforms-builder-settings-block-name"><?php echo esc_html( $googlesheet['name'] ); ?></span>

					<div class="wpforms-builder-settings-block-name-edit">
						<input type="text" name="settings[wpgs_spreadsheets][<?php echo absint( $wpgs_feed_id ); ?>][name]" value="<?php echo esc_attr( $googlesheet['name'] ); ?>">
					</div>
				</div>
			</div><!-- .wpforms-builder-settings-block-header -->

			<div class="wpforms-builder-settings-block-content" <?php echo wp_kses_post( $closed_state ); ?>>

				<?php echo $this->get_fields_for_block( $googlesheet, $builder_panel_settings ); ?>

			</div><!-- .wpforms-builder-settings-block-content -->

		</div><!-- .wpforms-builder-settings-block -->
		
		<?php		
	}

	/**
	 * Retrieve HTML for fields.
	 *
	 * @since 1.0.0
	 *
	 * @param array $googlesheet Googlesheet data.
	 * @param array $builder_panel_settings form settings data.
	 *
	 * @return string
	 */
	protected function get_fields_for_block( $googlesheet, $builder_panel_settings ) {

		$wpgs_feed_id    = $googlesheet['wpgs_feed_id'];
		$form_data     = $builder_panel_settings->form_data;
		$form_fields   = wpforms_get_form_fields( $form_data );
		$form_fields   = empty( $form_fields ) && ! is_array( $form_fields ) ? [] : $form_fields;
		
		$result = "";
		
		$wpforms_gs_sheetId = $this->get_spreadsheet_options();
		
		
		$selected_spreadsheet = isset ($googlesheet['gs_sheet_select_name']) && $googlesheet['gs_sheet_select_name'] != "" ? $googlesheet['gs_sheet_select_name'] : "";
		
		$result .= wpforms_panel_field(
			'select',
			'wpgs_spreadsheets',
			'gs_sheet_integration_mode',
			$form_data,
			esc_html__( 'Integration Mode', 'gsheetconnector-wpforms' ),
			[
				'parent'     => 'settings',
				'subsection' => $wpgs_feed_id,
				'default'    => 'manual',
				'options'     => [
							'manual'  => esc_html__( 'Manual', '' ),
							'automatic_disabled'  => esc_html__( 'Automatic (Upgrade To Pro)'),
							
						],
						
				'tooltip'    => esc_html__( 'Selection of chosing google sheet for data submission.', 'gsheetconnector-wpforms' ),
				'disabled'=>'disabled',
				'class' => "integration_mode_wrapper",
				'input_class' => "integration_mode_input",
			],
			false
		);
		
								
		
		$visible = isset ($googlesheet['gs_sheet_integration_mode']) && $googlesheet['gs_sheet_integration_mode'] == "manual" ? "" : " style='display: none;' ";
		$result .= "<div class='integratio_manual wpgs_panel_section'>";
			
			/* MANUAL - ENTER SPREADSHEET NAME FIELD */
			$result .= "<div class='gs_sheet_manuals_sheet_name_wrapper'>";
				$result .= wpforms_panel_field(
					'text',
					'wpgs_spreadsheets',
					'gs_sheet_manuals_sheet_name',
					$form_data,
					esc_html__( 'Select Spreadsheet Name', 'gsheetconnector-wpforms' ),
					[
						'parent'      => 'settings',
						'subsection'  => $wpgs_feed_id,
						'input_id'    => 'wpforms-panel-field-wpgs_spreadsheets-request-url-' . $wpgs_feed_id,
						'input_class' => 'wpforms-required wpforms-required-url',
						'default'     => '',
						'placeholder' => esc_html__( 'Enter Spreadsheet Name', 'gsheetconnector-wpforms' ),
						'tooltip'     => esc_html__( 'Enter spreadsheet name of sheet you want to send the data', 'gsheetconnector-wpforms' ),
					],
					false
				);
			$result .= "</div>";
			
			/* MANUAL - ENTER SPREADSHEET ID FIELD */
			$result .= "<div class='gs_sheet_manuals_sheet_name_wrapper'>";
				$result .= wpforms_panel_field(
					'text',
					'wpgs_spreadsheets',
					'gs_sheet_manuals_sheet_id',
					$form_data,
					esc_html__( 'Enter Spreadsheet Id', 'gsheetconnector-wpforms' ),
					[
						'parent'      => 'settings',
						'subsection'  => $wpgs_feed_id,
						'input_id'    => 'wpforms-panel-field-wpgs_spreadsheets-request-url-' . $wpgs_feed_id,
						'input_class' => 'wpforms-required wpforms-required-url',
						'default'     => '',
						'placeholder' => esc_html__( 'Enter Spreadsheet ID ', 'gsheetconnector-wpforms' ),
						'tooltip'     => esc_html__( 'Enter spreadsheet id of sheet you want to send the data.', 'gsheetconnector-wpforms' ),
					],
					false
				);
			$result .= "</div>";
			
			/* MANUAL - ENTER TAB NAME FIELD */
			$result .= "<div class='gs_sheet_manuals_sheet_name_wrapper'>";
				$result .= wpforms_panel_field(
					'text',
					'wpgs_spreadsheets',
					'gs_sheet_manuals_sheet_tab_name',
					$form_data,
					esc_html__( 'Sheet Tab Name', 'gsheetconnector-wpforms' ),
					[
						'parent'      => 'settings',
						'subsection'  => $wpgs_feed_id,
						'input_id'    => 'wpforms-panel-field-wpgs_spreadsheets-request-url-' . $wpgs_feed_id,
						'input_class' => 'wpforms-required wpforms-required-url',
						'default'     => '',
						'placeholder' => esc_html__( 'Enter Tab Name', 'gsheetconnector-wpforms' ),
						'tooltip'     => esc_html__( 'Enter tab name from above written google sheet.', 'gsheetconnector-wpforms' ),
					],
					false
				);
			$result .= "</div>";
			
			/* MANUAL - ENTER TAB ID FIELD */
			$result .= "<div class='gs_sheet_manuals_sheet_name_wrapper'>";			
				$result .= wpforms_panel_field(
					'text',
					'wpgs_spreadsheets',
					'gs_sheet_manuals_sheet_tab_id',
					$form_data,
					esc_html__( 'Sheet Tab ID', 'gsheetconnector-wpforms' ),
					[
						'parent'      => 'settings',
						'subsection'  => $wpgs_feed_id,
						'input_id'    => 'wpforms-panel-field-wpgs_spreadsheets-request-url-' . $wpgs_feed_id,
						'input_class' => 'wpforms-required wpforms-required-url',
						'default'     => '',
						'placeholder' => esc_html__( 'Enter Tab ID', 'gsheetconnector-wpforms' ),
						'tooltip'     => esc_html__( 'Enter tab id from above written google sheet.', 'gsheetconnector-wpforms' ),
					],
					false
				);
			$result .= "</div>";

    if(isset($googlesheet['gs_sheet_manuals_sheet_name']) && isset($googlesheet['gs_sheet_manuals_sheet_id']) && isset($googlesheet['gs_sheet_manuals_sheet_tab_name']) && isset($googlesheet['gs_sheet_manuals_sheet_tab_id'])){
			 if(($googlesheet['gs_sheet_manuals_sheet_name']!="") && ($googlesheet['gs_sheet_manuals_sheet_id']!="") && ($googlesheet['gs_sheet_manuals_sheet_tab_name']!="") && ($googlesheet['gs_sheet_manuals_sheet_tab_id']!=""))
			 {
				$result .= "<div class='gs_sheet_select_tab_wrapper'>";
						$result .= "<div class='wpforms-panel-field'>";
							$result .= "<label class='sheeturl'>";
								$result .= esc_html__( 'View Google Sheet', 'gsheetconnector-wpforms' );
							
								$result .= "<a href='https://docs.google.com/spreadsheets/d/".$googlesheet['gs_sheet_manuals_sheet_id']."/edit#gid=".$googlesheet['gs_sheet_manuals_sheet_tab_id']."' target='_blank' class='button button-primary btnstylewpform'>";
									$result .= esc_html__( 'Sheet URL', 'gsheetconnector-wpforms' );
								$result .= "</a>";
							$result .= "</label>";
						$result .= "</div>";
					$result .= "</div>";
					$new_link = 'https://gsheetconnector.freshdesk.com/a/solutions/articles/84000385095';
					$result .=	"<p class='header-reference'>
     Please add header manually in google sheet.for the reference  <a target='_blank' href=$new_link>Click here.</a> 
</p>";			
			}
			}
			
		$result .= "</div>";
		
		$result .= "<div class='field-maps-wrapper wpgs_panel_section'>";
		$result .= "<div class='field-maps'>";
				
				$result .= "<div class='wpforms-panel-field'>";	
					$result .= "<label>Form Field   <a href='https://www.gsheetconnector.com/wpforms-google-sheet-connector-pro'target='_blank'>Upgrade To Pro</a></label>";
				$result .= "</div>";
				
				$result .= "<div class='wpforms-panel-field'>";	
					$result .= "<label>Column Name</label>";
				$result .= "</div>";
		$result .= "</div>";
		
		foreach( $form_fields as $field ) {
			
			$result .= "<div class='wpform-toggle-cls field-maps form-fields form-fields-disable'>";
				
				$result .= wpforms_panel_field(
					'toggle',
					'wpgs_spreadsheets',
					'enable',
					$form_data,
					esc_html__( ' ', 'gsheetconnector-wpforms' ).' '.$field['label'],
					[
						
						'parent'     => 'settings',
						
						'pro_badge'   => true,
						
						'tooltip'    => esc_html__( ' Enable '. $field['label'].' Field', 'gsheetconnector-wpforms' ),						
				       'value'       => false,
					],
					false
				);

				$result .= wpforms_panel_field(
					'text',
					'wpgs_spreadsheets1',
					'column',
					$form_data,
					'',
					[
						
						'parent'      => 'settings',
					
						
						// 'input_id'    => 'wpforms-panel-field-wpgs_spreadsheets-request-url-' . $wpgs_feed_id,
						'input_class' => ' disableColumnName',
						'default'     => $field['label'],
						//'placeholder' => esc_html__( 'Enter Column Name ', 'gsheetconnector-wpforms' ),
						'placeholder' => $field['label'].' '.'Upgrade To Pro',
								
						// 'tooltip'     => esc_html__( ' Column Name for '. $field['label'].' Field', 'gsheetconnector-wpforms' ),
						'value'       => false,						
					],
					false
				);
			$result .= "</div>";
			
		}
		$result .= "</div>";
		
		$sync_text = __( "Can't find the field you added recently?" );
		$sync_link_text = __( 'Click here to save and reload' );
		$tooltip_text = __( 'Save and reload the form to view new fields.' );
		$result .= "<div class='force_reload reload_warning wpforms-panel-field wpgs_panel_section' style='display:none;'>";				
			$result .= "<h4 class='sync-heading'>";	
				$result .= "<p>$sync_text <a href='javascript:void(0);'>$sync_link_text</a>";
				$result .= "<input type='hidden' class='wpgs_feed_id' value='$wpgs_feed_id'>";
				$result .= "<i class='fa fa-question-circle wpforms-help-tooltip' title='$tooltip_text'></i>";
				$result .= "<span class='result' style='display: none;'></span>";
				$result .= "</p>";
		$result .= "</div>";

		$result .= "<div class='request-header-disable'>";
		$result .= wpforms_render(
			WPFORMS_GOOGLESHEET_PATH . 'includes/views/settings/fields-mapping',
			[
				'title'         => esc_html__( 'Request Headers', 'gsheetconnector-wpforms' ),
				'wpgs_feed_id'    => $wpgs_feed_id,
				'fields'        => $form_fields,
				// 'allowed_types' => $allowed_types,
				'meta'          => ! empty( $googlesheet['headers'] ) ? $googlesheet['headers'] : [ false ],
				'name'          => "settings[wpgs_spreadsheets][{$wpgs_feed_id}][headers]",
				'test'          => $googlesheet,
			]
		);
		$result .= "</div>";
		
		
		$result .= "<div class='wpform-toggle-cls freeze_header misc_functions wpgs_panel_section freeze_header-disable'>";
			$result .= wpforms_panel_field(
				'toggle',
				'wpgs_spreadsheets',
				'wpgs_freeze_header',
				$form_data,
				esc_html__( 'Freeze Header ', 'gsheetconnector-wpforms' ),
				[
					'parent'     => 'settings',
				
					'pro_badge'   => true,
					'tooltip'    => esc_html__( 'Control formatting of header. Check freeze header if you want to freeze first row considered as header.', 'gsheetconnector-wpforms' ),						
				],
				false
			);
		$result .= "</div>";
		
		$result .= "<div class='wpform-toggle-cls rowcolors misc_functions wpgs_panel_section alternate_colors_disable'>";
			$result .= wpforms_panel_field(
				'toggle',
				'wpgs_spreadsheets',
				'wpgs_alternate_colors',
				$form_data,
				esc_html__( 'Alternate Colors ', 'gsheetconnector-wpforms' ),
				[
					'parent'     => 'settings',
				
					'pro_badge'   => true,
					'tooltip'    => esc_html__( 'Control background colors of odd even rows as well as background color of header row.', 'gsheetconnector-wpforms' ),						
					'input_class' => "alternate_color_input",
				],
				false
			);
		
			
		
		$result .= "<div class='wpform-toggle-cls sheetsorting misc_functions wpgs_panel_section sort_sheet_disable'>";
			$result .= wpforms_panel_field(
				'toggle',
				'wpgs_spreadsheets',
				'wpgs_sheet_sorting',
				$form_data,
				esc_html__( 'Sort Sheet ', 'gsheetconnector-wpforms' ),
				[
					'parent'     => 'settings',
				
					'pro_badge'   => true,
					'tooltip'    => esc_html__( 'Set up this field if you want data to be sorted automatically upon the submission based on column.', 'gsheetconnector-wpforms' ),						
					'input_class' => "sheet_sorting_input",
				],
				false
			);
							

         $result .= "<div class='wpform-toggle-cls sheetsorting misc_functions wpgs_panel_section conditional_logic_disable'>";
			$result .= wpforms_panel_field(
				'toggle',
				'wpgs_spreadsheets',
				'wpgs_sheet_sorting',
				$form_data,
				esc_html__( 'Enable Conditional Logic ', 'gsheetconnector-wpforms' ),
				[
					'parent'     => 'settings',
				
					'pro_badge'   => true,
					'tooltip'    => esc_html__( 'Enable Conditional Logic' ),						
					'input_class' => "sheet_sorting_input",
				],
				false
			);

       $result .= "</div>";

		
		
			$sync_text = __( 'Sync to Google Sheet' );
			$sync_link_text = __( 'Click here' );
			$sync_text1 = __( 'Pro' );
			$tooltip_text = __( 'Click here to sync all the entries to the above selected Google Sheet. Make sure it will add all the form entries filled till now. If find duplicate then remove it manually or select the new spreadsheet or a new tab in same sheet.' );
			$result .= "<div class='sync-posts wpforms-panel-field wpgs_panel_section'>";				
				$result .= "<h4 class='sync-heading'>";	
					$result .= "<p>$sync_text <a href='javascript:void(0);' class='button button-primary btnstylewpform'>$sync_link_text </a> <span class='syncBtnPro'>$sync_text1</span>";
					$result .= "<input type='hidden' class='wpgs_feed_id' value='$wpgs_feed_id'>";
					$result .= "<i class='fa fa-question-circle wpforms-help-tooltip' title='$tooltip_text'></i>";

					$result .= "<span class='result' style='display: none;'></span>";
					$result .= "</p>";
			$result .= "</div>";
		
		return apply_filters( 'wpforms_googlesheets_form_builder_get_googlesheet_fields', $result, $form_data, $wpgs_feed_id );
	}
	

	/**
	 * Retrieve string of the class names.
	 *
	 * @since 1.0.0
	 *
	 * @param array $classes Array of class names for element.
	 *
	 * @return string
	 */
	protected function get_html_class( $classes, $builder_panel_settings ) {

		if ( ! is_array( $classes ) ) {
			$classes = (array) $classes;
		}
		
		$settings = $builder_panel_settings->form_data['settings'];
		$gsheetconnector_wpforms = isset($settings['gsheetconnector-wpforms']) ? $settings['gsheetconnector-wpforms'] : false;
		
		if ( ! $gsheetconnector_wpforms ) {
			$classes[] = 'hidden';
		}

		$classes = array_unique( array_map( 'esc_attr', $classes ) );

		return implode( ' ', $classes );
	}


	/**
	 * Add a new item `Webhooks` to panel sidebar.
	 *
	 * @since 1.0.0
	 *
	 * @param array $sections  Registered sections.
	 * @param array $form_data Contains array of the form data (post_content).
	 *
	 * @return array
	 */
	public function panel_sidebar( $sections, $form_data ) {

		$sections['wf_googlesheets'] = esc_html__( 'GSheetConnector', 'gsheetconnector-wpforms' );

		return $sections;
	}


	/**
	 * Add own localized strings to the Builder.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $strings Localized strings.
	 * @param object $form    Current form.
	 *
	 * @return array
	 */
	public function builder_strings( $strings, $form ) {

		$strings['googlesheet_prompt']        = esc_html__( 'Enter a Feed Name', 'gsheetconnector-wpforms' );
		$strings['googlesheet_ph']            = '';
		$strings['googlesheet_error']         = esc_html__( 'You must provide a googlesheet name', 'gsheetconnector-wpforms' );
		$strings['googlesheet_error2']        = esc_html__( 'To disable all wpgs_spreadsheets use the "Webhooks" dropdown setting.', 'gsheetconnector-wpforms' );
		$strings['googlesheet_delete']        = esc_html__( 'Are you sure that you want to delete this googlesheet?', 'gsheetconnector-wpforms' );
		$strings['googlesheet_def_name']      = esc_html__( 'Unnamed Googlesheet', 'gsheetconnector-wpforms' );
		$strings['googlesheet_required_flds'] = esc_html__( 'Your form contains required Googlesheet settings that have not been configured. Please double-check and configure these settings to complete the connection setup.', 'gsheetconnector-wpforms' );

		return $strings;
	}


	/**
	 * Enqueue a JavaScript file and inline CSS styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_assets() {
		
		wp_enqueue_script( 'wp-color-picker' );
		
		wp_enqueue_style( 'wp-color-picker' );
		
		wp_enqueue_script(
			'wpforms-wfgs-jquery-ui',
			WPFORMS_GOOGLESHEET_URL . "assets/js/jquery-ui.min.js",
			[ 'wpforms-builder' ],
			rand(0, 1000),
			true
		);
		
		wp_enqueue_script(
			'wpforms-wfgs-admin-builder',
			WPFORMS_GOOGLESHEET_URL . "assets/js/wpforms-gs-panel.js",
			[ 'wpforms-builder' ],
			rand(0, 1000),
			true
		);

		wp_enqueue_style(
			'wpforms-wfgs-admin-builder',
			WPFORMS_GOOGLESHEET_URL . "assets/css/wpforms-gs-panel.css",
			[ 'wpforms-builder' ],
			rand(0, 1000)
		);
		
		
	}


}


add_action( 'plugins_loaded', 'wpgs_init_components', 110, 1 );
function wpgs_init_components() {
	$form_builder = new WPGS_FormBuilder();
	$form_builder->init();
}




