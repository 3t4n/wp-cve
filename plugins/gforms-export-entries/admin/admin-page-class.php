<?php
/**
 * Include Gravity Forms addon framework
*/
GFForms::include_addon_framework();

/**
 * Extend GFAddOn class to add our admin scripts, styles, global settings and admin page
*/
class GFEEAddon extends GFAddOn {
	protected $_version = '1.0';
	protected $_min_gravityforms_version = '1.3';
	protected $_slug = 'gforms-export-entries';
	protected $_path = 'gforms-export-entries/gforms-export-entries.php';
	protected $_full_path = __FILE__;
	protected $_title = 'Gravity Forms Export Entries';
	protected $_short_title = 'Export Entries';

	private $settings;

    // ------------ Permissions -----------
    /**
     * @var string|array A string or an array of capabilities or roles that have access to the settings page
     */
    protected $_capabilities_settings_page = array( 'gravityforms_edit_settings' );
    /**
     * @var string|array A string or an array of capabilities or roles that have access to the form settings
     */
    protected $_capabilities_form_settings = array( 'gravityforms_edit_settings' );
    /**
     * @var string|array A string or an array of capabilities or roles that have access to the plugin page
     */
    protected $_capabilities_plugin_page = array( 'gravityforms_edit_settings' );
    /**
     * @var string|array A string or an array of capabilities or roles that have access to the app menu
     */
    protected $_capabilities_app_menu = array( 'gravityforms_edit_settings' );
    /**
     * @var string|array A string or an array of capabilities or roles that have access to the app settings page
     */
    protected $_capabilities_app_settings = array( 'gravityforms_edit_settings' );
    /**
     * @var string|array A string or an array of capabilities or roles that can uninstall the plugin
     */
    protected $_capabilities_uninstall = array( 'gravityforms_edit_settings' );
    private static $_instance = null;

	private $current_export = 'Default';

    public static function get_instance() {
        if ( self::$_instance == null ) {
            self::$_instance = new GFEEAddon();
        }

        return self::$_instance;
    }

    public function init() {
		parent::init();
		$this->current_export = $this->get_first_export();
		//= Load Custom Export
		if ( isset( $_GET['current_export'] ) ) {
			$this->current_export = sanitize_text_field( $_GET['current_export'] );
		}
		$this->settings = get_option( 'gfee_settings', array() );
		//= check if site is using old format
		if ( ! isset( $this->settings['exports'] ) ) {
			//= convert old settings format to new settings format
			$this->update_settings();
		}
	}

	/**
	 * Load js for our admin page
	*/
    public function scripts() {
		$path = $this->get_base_url();
		$path = str_replace( 'admin', '', $path );
        $scripts = array(
            array(
                'handle'  => 'gfee_admin_js',
                'src'     => $path . '/js/gfee-admin.js',
                'version' => $this->_version,
                'deps'    => array( 'jquery' ),
                'strings' => array(
                    'ajaxurl'  => admin_url( 'admin-ajax.php' ),
                ),
                'enqueue' => array(
                    array(
                        'admin_page' => array( 'plugin_settings' ),
                        'tab'        => 'gforms-export-entries'
                    )
                )
            ),

            array(
                'handle'  => 'gfee_calendar_js',
                'src'     => $path . '/js/CalendarPopup.js',
                'version' => $this->_version,
                'deps'    => array( 'jquery' ),
				'callback' => array( $this, 'localize_scripts' ),
                'strings' => array(
                    'ajaxurl'  => admin_url( 'admin-ajax.php' ),
					'jan' => __( 'January', 'car-demon' ),
					'feb' => __( 'February', 'car-demon' ),
					'mar' => __( 'March', 'car-demon' ),
					'apr' => __( 'April', 'car-demon' ),
					'may' => __( 'May', 'car-demon' ),
					'jun' => __( 'June', 'car-demon' ),
					'jul' => __( 'July', 'car-demon' ),
					'aug' => __( 'August', 'car-demon' ),
					'sep' => __( 'September', 'car-demon' ),
					'oct' => __( 'October', 'car-demon' ),
					'nov' => __( 'November', 'car-demon' ),
					'dec' => __( 'December', 'car-demon' ),
					'clear' => __( 'Clear', 'car-demon' ), 
					'close_it' => __( 'Close', 'car-demon' ),
					'plugin_path' => GFEE_PATH,
                ),
                'enqueue' => array(
                    array(
                        'admin_page' => array( 'plugin_settings' ),
                        'tab'        => 'gforms-export-entries'
                    )
                )
            ),

        );

        return array_merge( parent::scripts(), $scripts );
    }

	/**
	 * Load css for our admin page
	*/
    public function styles() {
		$path = $this->get_base_url();
		$path = str_replace( 'admin', '', $path );
        $styles = array(
            array(
                'handle'  => 'gfee_admin_css',
                'src'     => $path . '/css/gfee-admin.css',
                'version' => $this->_version,
                'enqueue' => array(
                    array(
                        'admin_page' => array( 'plugin_settings' ),
                        'tab'        => 'gforms-export-entries'
                    )
                )
            ),
			
            array(
                'handle'  => 'gfee_calendar_css',
                'src'     => $path . '/css/CalendarControl.css',
                'version' => $this->_version,
                'enqueue' => array(
                    array(
                        'admin_page' => array( 'plugin_settings' ),
                        'tab'        => 'gforms-export-entries'
                    )
                )
            )

        );

       return array_merge( parent::styles(), $styles );
    }

	/**
	 * Setup global settings
	*/
    public function plugin_settings_fields() {
        return array(
            array(
                'title'  => esc_html__( 'Export Form Entries', 'gforms-export-entries' ),
                'fields' => array(

					//= add our own custom "setting type" for the current export
					array(
						'label' => esc_html__( 'Welcome', 'gforms-export-entries' ),
						'type'  => 'gfee_select_welcome_type',
						'name'  => 'gfee_settings_welcome',
						'args'  => array(),
					),

                    //= add our own custom "setting type" for the current export
					array(
						'label' => esc_html__( 'Select Export', 'gforms-export-entries' ),
						'type'  => 'gfee_select_export_type',
						'name'  => 'gfee_select_export',
						'args'  => array(),
					),

					//= add our own custom "setting type" for a header row
					array(
						'label' => esc_html__( 'Custom Header Row', 'gforms-export-entries' ),
						'type'  => 'gfee_file_header_type',
						'name'  => 'gfee_file_header',
						'args'  => array(),
					),

					//= add our own custom "setting type" that outputs our bulk form selector
					array(
						'label' => esc_html__( 'Select Forms', 'gforms-export-entries' ),
						'type'  => 'gfee_select_forms_type',
						'name'  => 'gfee_select_forms',
						'args'  => array(),
						'tooltip' => esc_html__('Select the forms you want to export entries for.', 'gforms-export-entries' ),
					),

					//= add our own custom "setting type" that selects our start date
					array(
						'label' => esc_html__( 'Manual Export', 'gforms-export-entries' ),
						'type'  => 'gfee_start_date_type',
						'name'  => 'gfee_start_date',
						'args'  => array(),
						'tooltip' => esc_html__( 'NOTE: You must save your form settings before running a manual export. The Date Range selected does not need to be saved.', 'gforms-export-entries' ),
					),

					//= add our own custom "setting type" that selects our stop date
					array(
						'label' => '',
						'type'  => 'gfee_stop_date_type',
						'name'  => 'gfee_stop_date',
						'args'  => array(),
					),

					//= add our own custom "setting type" that exports selected entries
					array(
						'label' => '',
						'type'  => 'gfee_export_now_type',
						'name'  => 'gfee_export_now',
						'args'  => array(),
					),

					//= add our own custom "setting type" for a header row
					array(
						'label' => esc_html__( 'Schedule Export', 'gforms-export-entries' ),
						'type'  => 'gfee_schedule_type',
						'name'  => 'gfee_schedule',
						'args'  => array(),
						'tooltip' => esc_html__( 'NOTE: You must check the box to set your schedule.', 'gforms-export-entries' ),
					),
					
					//= add our own custom "setting type" to save the send to email address(s)
					array(
                        'tooltip'           => esc_html__( 'Send report to this email address. For more than one address separate your list with commas.', 'gforms-export-entries' ),
                        'label'             => esc_html__( 'Email report to', 'gforms-export-entries' ),
						'type'  => 'gfee_email_to_type',
						'name'  => 'gfee_email_to_settings',
						'args'  => array(),
					),

					//= schedule file removal
					array(
                        'tooltip'           => esc_html__( 'Select the schedule to clean out saved exports. NOTE: If this is set it will run immediately after saving and clean all existing files.', 'gforms-export-entries' ),
                        'label'             => esc_html__( 'Schedule cleaning out saved exports', 'gforms-export-entries' ),
						'type'  => 'gfee_cleaning_days_type',
						'name'  => 'gfee_cleaning_days_settings',
						'args'  => array(),
					),

					//= add our own custom "setting type" that imports / exports settings
					array(
						'label' => esc_html__( 'Export / Import All Settings', 'gforms-export-entries' ),
						'type'  => 'gfee_export_settings_type',
						'name'  => 'gfee_export_settings',
						'args'  => array(),
						'tooltip' => esc_html__( 'NOTE: You must have the same form names with the same fields names for the import settings to work correctly. <b>This tool will export ALL exports.</b>', 'gforms-export-entries' ),
					),

                )

            )
        );
    }

    public function settings_gfee_select_welcome_type( $field, $echo = true ) {
        $x = '';
        $updated = gfee_update_settings();
        if ( $updated ) {
            $url = '/wp-admin/admin.php?page=gf_settings&subview=gforms-export-entries';
            wp_redirect( $url );
            exit;
        }
        $x .= '<h2>';
            $x .= __( 'Need help? Learn how to setup your exports ', 'gforms-export-entries' );
            $x .= '<a href="https://optireto.com/how-to-export-all-gravity-form-entries/" target="_blank"/>';
                $x .= __( 'here', 'gforms-export-entries' );
            $x .= '</a>';
            $x .= __( '.', 'gforms-export-entries' );
        $x .= '</h2>';
        echo $x;
    }
    
	public function settings_gfee_select_export_type( $field, $echo = true ) {
		//= save settings
		if ( isset( $_POST['gfee_save_settings'] ) ) {
			$this->gfee_save_settings();
		} else {
			$this->settings = get_option( 'gfee_settings', array() );
		}

		$x = '<label>' . __( 'Export Name', 'gforms_export-entries' ) . '</label>';
		$x .= '<br />';
		$x .= '<input type="text" name="export_name" class="export_name" list="export_name" value="' . $this->current_export . '" data-default="' . $this->current_export . '" autocomplete="off" onClick="value = \'\';">';
				$x .= '<datalist id="export_name">';
					foreach( $this->settings['exports'] as $key=>$value ) {
						$x .= '<option data-default="' . $this->current_export . ' " value="' . $key . '">';
					}
				$x .= '</datalist>
		';
		$x .= '<input type="button" class="delete_export" value="'. __( 'Delete This Export', 'gforms-export-entries' ) . '" />';
		$x .= '<br />';
		$x .= '<small>' . __( 'Select an existing export to load its settings or enter a custom name and begin creating a new export.', 'gforms-export-entries' ) . '</small>';
		echo $x;
	}

	/**
	 * Create a custom header row
	*/
    public function settings_gfee_file_header_type( $field, $echo = true ) {

		if ( isset( $this->settings['exports'][ $this->current_export ]['file_header'] ) ) {
			$value = $this->settings['exports'][ $this->current_export ]['file_header'];
		} else {
			$value = '';
		}

		$x = __( 'If you would like to add a custom header row to your export then enter your list here. Separate each item with a comma.', 'gforms-export-entries' );
		$x .= '<br />';
		$x .= __( 'Example: Date Sent, Email Address, First Name, Last Name, Comment', 'gforms-export-entries' );
		$x .= '<br />';
		$x .= '<input type="text" name="file_header" id="file_header" class="gfee_file_header" value="' . $value . '" placeholder="' . __( 'Date Sent, Email Address, First Name, Last Name, Comment', 'gforms-export-entries' ) .'" />';
		echo $x;
    }

	/**
	 * Select forms for export
	*/
	public function settings_gfee_select_forms_type( $field, $echo = true ) {

		$x = '<input type="hidden" name="gfee_save_settings" value="1" />';

		$x .= '<div class="gfee_advanced_wrapper">';
			$x .= '<div class="gfee_advanced_title">';
				$x .= __( 'Advanced', 'gforms-export-entries' );
			$x .= '</div>';
			$x .= '<div class="gfee_advanced_options">';
				$x .= '<label>';
					$x .= __( 'Custom field Offset', 'gforms-export-entries' );
				$x .= '</label>';
				$x .= '<div class="gfee_custom_field_offset">';
					$offset = '0';
					if ( isset( $this->settings['exports'][ $this->current_export ]['custom_field_offset'] ) ) {
						$offset = $this->settings['exports'][ $this->current_export ]['custom_field_offset'];
					}
					$x .= '<input type="text" value="' . $offset . '" name="gfee_custom_field_offset" />';
					$x .= '<br />';
					$x .= '<small>';
						$x .= __( 'This is an advanced feature to offset the id# of custom fields.', 'gforms-export-entries' );
					$x .= '</small>';
				$x .= '</div>';
			$x .= '</div>';
		$x .= '</div>';
        
		//= get all forms
		$forms = GFAPI::get_forms();
		$x .= '<fieldset>';
			if ( count( $forms ) > 0 ) {
				foreach( $forms as $key=>$form ) {
					$value = 0;

					$form['title'] = gfee_clean_title( $form['title'] );

					if ( isset( $this->settings['exports'][ $this->current_export ]['forms'][ $form['id'] ] ) ) {
						$value = 1;
					}
					else {
					}
					$x .= '<div class="gfee_form gfee_form_' . $form['id'] . '">';
						$x .= '<span class="gfee_open_fields" title="' . __( 'Show Fields', 'gforms-export-entries' ) . '" data-form-id="' . $form['id'] . '">';
							$x .= '+';
						$x .= '</span>';
						$x .= '<input type="checkbox"'. ( ( $value == 1 ) ? ' checked="checked" ' : '' ) .' value="' . $form['id'] . '" name="form_title[' . $form['id'] . ']" />';
						$x .= '<label class="gfee_form_label">';
							$x .= $form['title'];
							$x .= ' ( ';
								$x .= GFAPI::count_entries( $form['id'] );
							$x .= ' )';
						$x .= '</label>';
						$x .= '<div id="form_fields_' . $form['id'] . '" class="gfee_form_fields">';

							$custom_fields = array(
									__( 'Form ID', 'gforms-export-entries' ) => 'form_id',
									__( 'Form Name', 'gforms-export-entries' ) => 'form_name',
									__( 'Entry ID', 'gforms-export-entries' ) => 'entry_id',
									__( 'Date Sent', 'gforms-export-entries' ) => 'form_date',
									__( 'Source URL', 'gforms-export-entries' ) => 'source_url',
									__( 'Confirmation Page', 'gforms-export-entries' ) => 'confirmation_page',
								);
							
							$custom_fields = apply_filters( 'gfee_custom_fields_filter', $custom_fields );
							
							foreach( $custom_fields as $label=>$custom_field ) {
                                $custom_field_css = sanitize_title( $custom_field );
								$x .= '<div data-form-id="' . $form["id"] . '" class="gfee_field gfee_field_' . $custom_field . '_' . $form['id'] . '_wrap gfee_field_' . $custom_field . '_wrap">';
									if ( isset( $this->settings['exports'][ $this->current_export ]['forms'][$form['id']][$custom_field] ) ) {
										$form_name = $this->settings['exports'][ $this->current_export ]['forms'][$form['id']][$custom_field];
									} else {
										$form_name = '0';
									}
                                    $x .= '<span data-form-id="' . $form["id"] . '" data-field="' . $custom_field . '" class="gfee_copy_value fa fa-angle-double-down" title="' . __( 'Copy this value to all forms that have a field with this name.', 'gforms-export-entries' ) . '"></span>';
									$x .= '<label>';
										$x .= $label;
									$x .= '</label>';
									$x .= '<input name="form_item[' . $form["id"] . '][' . $custom_field . ']" type="text" class="gfee_field_column gfee_field_' . $custom_field_css . '" value="' . $form_name . '" title="' . __( 'Enter the column number for export - use 0 to exclude', 'gforms-export-entries' ) . '" />';
								$x .= '</div>';
                                $x .= '<div class="clear"></div>';
							}

							$x .= $this->select_form_fields( $form['id'], $custom_fields );
						$x .= '</div>';
					$x .= '</div>';
				}
			}
		$x .= '</fieldset>';

		echo $x;
	}

	private function select_form_fields( $form_id, $custom_fields ) {
		$x = '';
		$form = GFAPI::get_form( $form_id );
		$form['title'] = gfee_clean_title( $form['title'] );
	
		foreach( $form["fields"] as &$field ) {

			//see if this is a multi-field, like name or address
			if ( is_array($field["inputs"] ) ) {

				//loop through inputs
				foreach( $field["inputs"] as $input ) {
					if ( empty( $input["label"] ) ) continue;

					//= make sure no saved field has the same name as a custom field
					if ( in_array( $input['label'], $custom_fields ) ) continue;

                    $slug = $input['label'];
                    $slug = strtolower( $slug );
                    $slug = str_replace( ' ', '_', $slug );
                    $x .= '<div data-form-id="' . $input["id"] . '" class="gfee_field gfee_field_' . $slug . '_' . $form['id'] . '_wrap gfee_field_' . $slug . '_wrap">';
						if ( isset( $this->settings['exports'][ $this->current_export ]['forms'][$form['id']][$input['label']] ) ) {
							$value = $this->settings['exports'][ $this->current_export ]['forms'][$form['id']][$input['label']];
						} else {
							$value = '0';
						}
                        $custom_field_css = sanitize_title( $slug );
                        $x .= '<span data-form-id="' . $form["id"] . '" data-field="' . $slug . '" class="gfee_copy_value fa fa-angle-double-down" title="' . __( 'Copy this value to all forms that have a field with this name.', 'gforms-export-entries' ) . '"></span>';
                        $x .= '<label>';
							$x .= $input["id"] . ' - ' . $input["label"];
						$x .= '</label>';
						$x .= '<input name="form_item[' . $form['id'] .'][' . $input['label'] . ']" type="text" class="gfee_field_column gfee_field_' . $custom_field_css . '" value="' . $value . '" title="' . __( 'Enter the column number for export - use 0 to exclude', 'gforms-export-entries' ) . '" />';
					$x .= '</div>';
                    $x .= '<div class="clear"></div>';
					
				}

			} else {
				//= list single input
				if ( empty( $field["label"] ) ) continue;

				//= make sure no saved field has the same name as a custom field
				if ( in_array( $field['label'], $custom_fields ) ) continue;

                    $slug = $field['label'];
                    $slug = strtolower( $slug );
                    $slug = str_replace( ' ', '_', $slug );
                    $x .= '<div data-form-id="' . $field["id"] . '" class="gfee_field gfee_field_' . $slug . '_' . $form['id'] . '_wrap gfee_field_' . $slug . '_wrap">';

					if ( isset( $this->settings['exports'][ $this->current_export ]['forms'][$form['id']][$field['label']] ) ) {
						$value = $this->settings['exports'][ $this->current_export ]['forms'][$form['id']][$field['label']];
					} else {
						$value = '0';
					}
                    $custom_field_css = sanitize_title( $slug );
                    $x .= '<span data-form-id="' . $form["id"] . '" data-field="' . $slug . '" class="gfee_copy_value fa fa-angle-double-down" title="' . __( 'Copy this value to all forms that have a field with this name.', 'gforms-export-entries' ) . '"></span>';
					$x .= '<label>';
						$x .= $field["id"] . ' - ' . $field["label"];
					$x .= '</label>';
					$x .= '<input name="form_item[' . $form['id'] .'][' . $field['label'] . ']" type="text" class="gfee_field_column gfee_field_' . $custom_field_css . '" value="' . $value . '" />';
				$x .= '</div>';
                $x .= '<div class="clear"></div>';
			}
		}
		
		return $x;
	}

	/**
	 * Select a start date for the export
	*/
    public function settings_gfee_start_date_type( $field, $echo = true ) {
		$date = date( 'm-d-y', strtotime( '-30 days' ) );
		$x = __( 'Export entries after this date:', 'gforms-export-entries' );
		$x .= '<br />';
		$x .= '<input type="text" name="start_date" id="start_date" class="gfee_start_date" value="' . $date . '" onfocus="showCalendarControl(this);" />';
		echo $x;
    }

	/**
	 * Select a stop date for the export
	*/
    public function settings_gfee_stop_date_type( $field, $echo = true ) {
		$date = date( 'm-d-y' );
		$x = __( 'Export entries before this date:', 'gforms-export-entries' );
		$x .= '<br />';
		$x .= '<input type="text" name="stop_date" id="stop_date" class="gfee_stop_date" value="' . $date . '" onfocus="showCalendarControl(this);" />';
		echo $x;
    }

	/**
	 * Export now button
	*/
    public function settings_gfee_export_now_type( $field, $echo = true ) {
		$x = '<input type="button" class="btn_gfee_export_now" value="' . __( 'Export Entries Now', 'gforms-export-entries' ) . '" />';
		$x .= '<div class="gfee_export_msg"></div>';
		echo $x;
    }

	/**
	 * Setup export schedule
	*/
    public function settings_gfee_schedule_type( $field, $echo = true ) {
		if ( isset( $this->settings['exports'][ $this->current_export ]['gfee_schedule_frequency'] ) ) {
			$value = $this->settings['exports'][ $this->current_export ]['gfee_schedule_frequency'];
		} else {
			$value = '';
		}
		$x = '<div class="gfee_file_schedule_wrap">';
			$x .= '<input type="checkbox" class="gfee_file_schedule" name="gfee_file_schedule" value="1" />';
			$x .= '<b>' . __(' Check this box to set your schedule', 'gforms-export-entries') . '</b>';;
		$x .= '</div>';
		$x .= '<div class="gfee_clear"></div>';
		$x .= '<br />';
		$x .= __( 'Select how often you would like this report generated and emailed', 'gforms-export-entries' );
		$x .= '<br />';
		$x .= '<select class="gfee_schedule_frequency" name="gfee_schedule_frequency">';
			$x .= '<option value="">'. __( 'No Schedule', 'gforms-export-entries' ) .'</option>';
			$x .= '<option value="daily"' . ( ( $value == 'daily' ) ? ' selected' : '' ) . '>'. __( 'Send Daily', 'gforms-export-entries' ) .'</option>';
			$x .= '<option value="two_days"' . ( ( $cleaning_days == 'two_days' ) ? ' selected' : '' ) . '>'. __( 'Every other day', 'gforms-export-entries' ) .'</option>';
            $x .= '<option value="weekly"' . ( ( $value == 'weekly' ) ? ' selected' : '' ) . '>'. __( 'Send Weekly', 'gforms-export-entries' ) .'</option>';
            $x .= '<option value="gfee_monthly"' . ( ( $value == 'gfee_monthly' ) ? ' selected' : '' ) . '>'. __( 'Send Monthly', 'gforms-export-entries' ) .'</option>';
		$x .= '</select>';
		
		$x .= '<br />';
		$x .= '<br />';		
		$x .= __( 'Select Start Date', 'gforms-export-entries' );
		$x .= '<br />';

        if ( ! isset( $this->settings['exports'][ $this->current_export ]['schedule_start_date'] ) ) {
			$date = date( 'm-d-y' );
		} else {
			$date = $this->settings['exports'][ $this->current_export ]['schedule_start_date'];
		}
		
		if ( empty( $date ) ) {
			$date = date( 'm-d-y' );
		}

		$x .= '<input type="text" name="schedule_start_date" id="schedule_start_date" class="gfee_start_date" value="' . $date . '" onfocus="showCalendarControl(this);" />';
		$x .= '<br />';		
		$x .= '<br />';
		$x .= __( 'Set schedule time', 'gforms-export-entries' );
		$x .= '<br />';
		$x .= $this->select_schedule_time();

		echo $x;
    }

	/**
	 * Output schedule time selections
	*/
	private function select_schedule_time() {

		if ( ! isset( $this->settings['exports'][ $this->current_export ]['hour'] ) ) {
			$this->settings['exports'][ $this->current_export ]['hour'] = '1';
		}

		if ( ! isset( $this->settings['exports'][ $this->current_export ]['minute'] ) ) {
			$this->settings['exports'][ $this->current_export ]['minute'] = '1';
		}

		$x = 'Time: <select class="gfee_schedule_hour" name="gfee_schedule_hour">';
		$i = 1;
			do {
				if ($i == $this->settings['exports'][ $this->current_export ]['hour']) {
					$x .= '<option value="'.$i.'" selected>'.$i.'</option>';
				} else {
					$x .= '<option value="'.$i.'">'.$i.'</option>';
				}
				++$i;
			} while ($i < 24);		
		$x .= '</select>';
		$x .= '&nbsp;<select class="gfee_schedule_minute" name="gfee_schedule_minute">';
		$x .= '<option value="00">00</option>';
		$i = 10;
			do {
				if ($i == $this->settings['exports'][ $this->current_export ]['minute']) {
					$x .= '<option value="'.$i.'" selected>'.$i.'</option>';
				} else {
					$x .= '<option value="'.$i.'">'.$i.'</option>';
				}
				$i = $i + 1;
			} while ($i < 60);
		$x .= '</select>';
		$x .= '<br />';
		$x .= 'Current Server Time: ';
        $current_server_time = date('Y-m-d H:i');
		$x .= $current_server_time;
		$x .= '<br />';
		$x .= 'Current WordPress Time: ';
        $timezone_format = _x( 'Y-m-d H:i:s', 'timezone date format' );
        $x .= date_i18n( $timezone_format );
        $x .= '<br />';

		$scheduled = wp_next_scheduled( 'export_gfee_entries', array( $this->current_export ) );

		if ( $scheduled ) {
			$x .= '<div class="gfee_next_scheduled">Next Scheduled: '. date( 'm-d-Y H:i', $scheduled ) .'</div>';
			$x .= '<br />';
			$x .= '<div class="gfee_remove_file_schedule_wrap">';
				$x .= '<input type="checkbox" class="gfee_remove_file_schedule" name="gfee_remove_file_schedule" value="1" />';
				$x .= __(' Remove Schedule', 'gforms-export-entries');
			$x .= '</div>';
			$x .= '<div class="gfee_clear"></div>';
		} else {
			$x .= '<br /><b>Export has not been scheduled.</b>';
		}

        //= Set our timezone from the settings
        if ( function_exists( 'wp_timezone' ) ) {
            $wp_timezone = wp_timezone();
            $offset = $wp_timezone->timezone;
            //wp_timezone_string()
        } else {
            $offset = get_option('gmt_offset');
            //echo get_option('timezone_string');
        }
        
		return $x;	
	}

	/**
	 * Custom function to save email address(s)
	*/
	public function settings_gfee_email_to_type( $field, $echo = true ) {
		if ( isset( $this->settings['exports'][ $this->current_export ]['email_to'] ) ) {
			$value = $this->settings['exports'][ $this->current_export ]['email_to'];
		} else {
			$value = '';
		}

		if ( isset( $this->settings['exports'][ $this->current_export ]['email_subject'] ) ) {
			$subject = $this->settings['exports'][ $this->current_export ]['email_subject'];
		} else {
			$site_name = get_bloginfo( 'name' );
			$subject = __( 'Form Entry Report for ', 'gforms-export-entries' ) . $site_name;
		}

		if ( isset( $this->settings['exports'][ $this->current_export ]['email_template'] ) ) {
			$template = html_entity_decode( $this->settings['exports'][ $this->current_export ]['email_template'] );
		} else {
			$template = $this->email_template();
		}

		$x = '<input type="text" name="email_to" class="gfee_email_to" value="' . $value . '" />';
		$x .= '<br />';
		$x .= '<small class="gfee_edit_template">';
			$x .= __( 'Edit Email Template', 'gforms-export-entries' );
		$x .= '</small>';
		$x .= '<div class="gfee_email_template_div">';

			$x .= __( 'Subject', 'gforms-export-entries' );
			$x .= '<br />';
			$x .= '<input type="text" name="email_subject" class="gfee_subject" value="' . $subject . '" />';
			$x .= '<br />';
			$x .= '<br />';
			$x .= '<textarea class="gfee_email_template" name="gfee_email_template">';
				$x .= $template;
			$x .= '</textarea>';
		$x .= '</div>';
		echo $x;
	}

	/**
	 *
	*/
	private function email_template() {
		$site_name = get_bloginfo( 'name' );
		$x = __( 'Form Entry Report is attached for ', 'gforms-export-entries' ) . $site_name;
		return $x;
	}
	
	/**
	 * Output the cleaning days field
	*/
	public function settings_gfee_cleaning_days_type( $field, $echo = true ) {
		$cleaning_days = '0';
		if ( isset( $this->settings['exports'][ $this->current_export ]['cleaning_days'] ) ) {
			$cleaning_days = $this->settings['exports'][ $this->current_export ]['cleaning_days'];
		}
		$x = '';
		$x .= '<select name="gfee_cleaning_days" class="gfee_cleaning_days">';
			$x .= '<option value="0">'. __( 'No Schedule', 'gforms-export-entries' ) .'</option>';
			$x .= '<option value="daily"' . ( ( $cleaning_days == 'daily' ) ? ' selected' : '' ) . '>'. __( 'Daily', 'gforms-export-entries' ) .'</option>';
			$x .= '<option value="two_days"' . ( ( $cleaning_days == 'two_days' ) ? ' selected' : '' ) . '>'. __( 'Every other day', 'gforms-export-entries' ) .'</option>';
			$x .= '<option value="weekly"' . ( ( $cleaning_days == 'weekly' ) ? ' selected' : '' ) . '>'. __( 'Weekly', 'gforms-export-entries' ) .'</option>';
			$x .= '<option value="gfee_monthly"' . ( ( $cleaning_days == 'gfee_monthly' ) ? ' selected' : '' ) . '>'. __( 'Once a Month', 'gforms-export-entries' ) .'</option>';
		$x .= '</select>';
		
		echo $x;
	}

	/**
	 * Export or Import Settings
	*/
    public function settings_gfee_export_settings_type( $field, $echo = true ) {
		if ( is_array( $this->settings ) ) {
			$json = json_encode( $this->settings );
		} else {
			$json = '';
		}

		$x = '<input type="button" class="btn_gfee_export_settings" data-type="export" value="' . __( 'Export Settings', 'gforms-export-entries' ) . '" />';
		$x .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		$x .= '<input type="button" class="btn_gfee_import_settings" data-type="import" value="' . __( 'Import Settings', 'gforms-export-entries' ) . '" />';
		$x .= '<div class="gfee_export_import">';
			$x .= '<input type="hidden" value=\'' . $json .'\' class="gfee_export_settings" />';
			$x .= '<textarea class="gfee_export_import_settings">';
				$x .= $json;
			$x .= '</textarea>';
			$x .= '<br />';
			$x .= '<input type="button" class="btn_gfee_cancel_import_export" value="' . __( 'Cancel', 'gforms-export-entries' ) . '" />';
			$x .= '<input type="button" class="btn_gfee_import_now" value="' . __( 'Import Now', 'gforms-export-entries' ) . '" />';
			$x .= '<div class="gfee_export_import_msg">';
			$x .= '</div>';
		$x .= '</div>';
		echo $x;
    }

	/**
	 * Save our custom settings
	*/
	private function gfee_save_settings() {
		gfee_log( '=============== START UPDATING SETTINGS ==================' );
		$settings = get_option( 'gfee_settings', array() );
		$old_settings = $settings;
		$field_count = count( $_POST );
		gfee_log( 'Number of fields: ' . $_POST['total_form_fields'] );
		gfee_log( 'Number of fields sent: ' . $_POST['total_form_fields_sent'] );

		if ( isset( $_POST['export_name'] ) ) {
			$this->current_export = sanitize_text_field( $_POST['export_name'] );
			$settings['exports'][ $this->current_export ] = array();
		}

		if ( isset( $_POST['file_header'] ) ) {
			$settings['exports'][ $this->current_export ]['file_header'] = sanitize_text_field( $_POST['file_header'] );
		}

		if ( isset( $_POST['gfee_custom_field_offset'] ) ) {
			$settings['exports'][ $this->current_export ]['custom_field_offset'] = sanitize_text_field( $_POST['gfee_custom_field_offset'] );
		}

		if ( isset( $_POST['form_title'] ) ) {
			foreach( $_POST['form_title'] as $form ) {
				$fields = array();
				if ( isset( $_POST['form_item'][$form] ) ) {
					foreach( $_POST['form_item'][$form] as $field=>$value ) {
						if ( $value != 0 && ! empty( $value ) ) {
							$fields[$field] = $value;
						}
					}
				}
				
				$settings['exports'][ $this->current_export ]['forms'][sanitize_text_field( $form )] = $fields;
				
			}
		}

		if ( isset( $_POST['email_to'] ) ) {
			$settings['exports'][ $this->current_export ]['email_to'] = sanitize_text_field( $_POST['email_to'] );
		} else {
			$settings['exports'][ $this->current_export ]['email_to'] = '';
		}

		//= save email template
		if ( isset( $_POST['gfee_email_template'] ) ) {
			$settings['exports'][ $this->current_export ]['email_template'] = esc_html( $_POST['gfee_email_template'] );
		}

		//= save email template
		if ( isset( $_POST['email_subject'] ) ) {
			$settings['exports'][ $this->current_export ]['email_subject'] = $_POST['email_subject'];
		}

		//= schedule export
		$settings['exports'][ $this->current_export ]['gfee_schedule_frequency'] = sanitize_text_field( $_POST['gfee_schedule_frequency'] );
		$settings['exports'][ $this->current_export ]['hour'] = sanitize_text_field( $_POST['gfee_schedule_hour'] );
		$settings['exports'][ $this->current_export ]['minute'] = sanitize_text_field( $_POST['gfee_schedule_minute'] );
		$settings['exports'][ $this->current_export ]['schedule_start_date'] = sanitize_text_field( $_POST['schedule_start_date'] );

		if ( isset( $_POST['gfee_file_schedule'] ) ) {
			if ( isset( $_POST['gfee_schedule_frequency'] ) ) {
				if ( ! empty( $_POST['gfee_schedule_frequency'] ) ) {
					gfee_set_schedule( $settings, $this->current_export );
				} else {
					$settings['exports'][ $this->current_export ]['gfee_schedule_frequency'] = '';
					$settings['exports'][ $this->current_export ]['hour'] = '';
					$settings['exports'][ $this->current_export ]['minute'] = '';
					$settings['exports'][ $this->current_export ]['schedule_start_date'] = '';
					wp_clear_scheduled_hook( 'export_gfee_entries', $this->current_export );
				}
			}
		}

		if ( isset( $_POST['gfee_remove_file_schedule'] ) ) {
			if ( ! empty( $_POST['gfee_remove_file_schedule'] ) ) {
				$settings['exports'][ $this->current_export ]['gfee_schedule_frequency'] = '';
				$settings['exports'][ $this->current_export ]['hour'] = '';
				$settings['exports'][ $this->current_export ]['minute'] = '';
				$settings['exports'][ $this->current_export ]['schedule_start_date'] = '';
				wp_clear_scheduled_hook( 'export_gfee_entries', $this->current_export );
			}
		}
        
        //= Manage file cleaning schedule
		if ( isset( $_POST['gfee_cleaning_days'] ) ) {
			gfee_log( 'Schedule to clean.' );
			if ( ! empty( $_POST['gfee_cleaning_days'] ) && '0' !== $_POST['gfee_cleaning_days'] ) {
				$settings['exports'][ $this->current_export ]['cleaning_days'] = sanitize_text_field( $_POST['gfee_cleaning_days'] );
				if ( $_POST['gfee_cleaning_days'] !== $old_settings['exports'][ $this->current_export ]['cleaning_days'] ) {
					gfee_clear_schedule_clean_files( $settings, $this->current_export );
					gfee_set_schedule_clean_files( $settings, $this->current_export );
				} else {
					gfee_log( 'Schedule cleaning old files, not updated, already set to: ' . $_POST['gfee_cleaning_days'] );
				}
			}
			if ( '0' === $_POST['gfee_cleaning_days'] ) {
				gfee_log( 'Schedule to clean files turned OFF.' );
				$settings['exports'][ $this->current_export ]['cleaning_days'] = '';
				gfee_clear_schedule_clean_files( $settings, $this->current_export );
			}
		} else {
			gfee_log( 'Schedule to clean files turned OFF - no value sent.' );
			$settings['exports'][ $this->current_export ]['cleaning_days'] = '';
			gfee_clear_schedule_clean_files( $settings, $this->current_export );
		}
        
		update_option( 'gfee_settings', $settings, false );
		$this->settings = $settings;
		gfee_log( '=============== DONE UPDATING SETTINGS ==================' );
	}

	public function get_first_export() {
		$settings = get_option( 'gfee_settings', array() );
		reset( $settings );
		if ( isset( $settings['exports'] ) ) {
			$first = key( $settings['exports'] );
			if ( empty( $first ) ) {
				$first = __( 'Default', 'gforms-export-entries' );
			}
		} else {
			$first = '';
		}
		return $first;
	}

	/**
	 * Convert settings to new settings group
	*/
	private function update_settings() {
		//= get old settings
		$old_settings = get_option( 'gfee_settings' );
		//= save old settings just in case
		update_option( 'gfee_old_settings', $old_settings, false );
		//= start building settings in the new format
		$new_settings = array (
			'exports' => array(
				'Default' => $this->settings,
			),
		);
		//= make our new settings active
		$this->settings = $new_settings;
		//= update the settings with the new format
		update_option( 'gfee_settings', $this->settings, false );
	}

}

/**
 * Register our Gravity Forms add-on
*/
GFAddOn::register( 'GFEEAddon' );

?>