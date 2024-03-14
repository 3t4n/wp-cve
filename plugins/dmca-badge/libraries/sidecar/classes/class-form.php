<?php

/**
 *
 */
class Sidecar_Form {
	/**
	 * @var Sidecar_Plugin_Base
	 */
	var $plugin;

	/**
	 * @var Sidecar_Admin_Page
	 */
	var $admin_page;

	/**
	 * @var string
	 */
	var $form_name;

	/**
	 * @var string
	 */
	var $form_label;

	/**
	 * @var bool
	 */
	var $requires_api;

	/**
	 * @var Sidecar_Form_Settings
	 */
	private $_settings;

	/**
	 * @var array
	 */
	private $_default_settings_values;

	/**
	 * @var array
	 */
	private $_fields = array();

	/**
	 * @var array
	 */
	private $_sections = array();

	/**
	 * @var array
	 */
	private $_buttons = array();

	/**
	 * @var bool
	 */
	private $_initialized = false;

	/**
	 * @var array
	 */
	private $_required_field_names;

	/**
	 * @param string $form_name
	 * @param array $args
	 */
	function __construct( $form_name, $args = array() ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			$this->form_name = $form_name;
			/**
			 * Copy properties in from $args, if they exist.
			 */
			foreach ( $args as $property => $value ) {
				if ( property_exists( $this, $property ) ) {
					$this->$property = $value;
				} else if ( property_exists( $this, $property = "form_{$property}" ) ) {
					$this->$property = $value;
				}
			}
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}

	}

	/**
	 * @param string $field_name
	 *
	 * @return bool|Sidecar_Field
	 */
	function get_field( $field_name ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			return isset( $this->_fields[ $field_name ] ) ? $this->_fields[ $field_name ] : false;
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}

	}

	/**
	 * @param string $field_name
	 *
	 * @return bool
	 */
	function has_field( $field_name ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try {

			return isset( $this->_fields[ $field_name ] );
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}

	}

	/**
	 * @return bool
	 */
	function has_sections() {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			return 0 < count( $this->_sections );
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}

	}

	/**
	 * @param string $section_name
	 *
	 * @return array
	 */
	function get_field_names( $section_name ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			return array_keys( $this->_sections[ $section_name ]->fields );
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}

	}

	/**
	 * @param bool|string $section_name
	 *
	 * @return bool
	 */
	function has_section_fields( $section_name = false ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			$has_fields = false;
			if ( $section_name && $this->has_section( $section_name ) ) {
				$has_fields = 0 < count( $this->_sections[ $section_name ]->fields );
			}

			return $has_fields;
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}

	}

	/**
	 * @param string $section_name
	 *
	 * @return bool
	 */
	function has_section( $section_name ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			return isset( $this->_sections[ $section_name ] );
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}

	}

	/**
	 * @param bool|string $section_name
	 *
	 * @return array
	 */
	function get_section_fields( $section_name = false ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			$fields = array();
			if ( $section_name && $this->has_section( $section_name ) ) {
				$fields = $this->_sections[ $section_name ]->fields;
			}

			return $fields;
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}

	}

	/**
	 * @return array
	 */
	function the_form() {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			echo $this->get_html();

			return $this;
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}

	}

	/**
	 * @return string
	 */
	function get_html() {
		$error_path = plugin_dir_url(__FILE__) ;
		try {

					
					ob_start();
					/**
					 * Get the HTML for the hidden fields from the Settings API
					 */
					settings_fields( $settings_group = $this->plugin->option_name );

					/**
					 * Hide hidden fields from Settings API by removing them from global $wp_settings_fields
					 * Adding to internal $hidden_fields
					 */
					global $wp_settings_fields;
					$save_wp_settings_fields = $wp_settings_fields;
					$hidden_fields           = array();
					$fields                  = $this->get_fields();
					if ( isset( $wp_settings_fields[ $this->plugin->option_name ] ) ) {
						foreach ( $wp_settings_fields[ $this->plugin->option_name ] as $section_name => $section ) {
							foreach ( $section as $field_name => $field ) {
								$unset = false;
								if ( ! isset( $fields[ $field_name ] ) ) {
									$unset = true;
								} else if ( 'hidden' == $field['args']['field']->field_type ) {
									$hidden_fields[] = $field['args']['field'];
									$unset           = true;
								}
								if ( $unset ) {
									unset( $wp_settings_fields[ $this->plugin->option_name ][ $section_name ][ $field_name ] );
								} else {
									/**
									 * @var Sidecar_Field $field_object
									 */
									$field_object                                                                                          = $field['args']['field'];
									$wp_settings_fields[ $this->plugin->option_name ][ $section_name ][ $field_name ]['args']['label_for'] = $field_object->get_wrapper_id();
								}
							}
						}
					}
					/**
					 * Extract the hidden fields so they won't display
					 * @var Sidecar_Field $hidden_field
					 */
					$hidden_fields_html = array();
					foreach ( $hidden_fields as $hidden_field ) {
						$hidden_fields_html[] = $hidden_field->get_html();
					}
					$hidden_fields_html = implode( "\n", $hidden_fields_html );

					/**
					 * Output each of the sections.
					 */
					do_settings_sections( $this->plugin->option_name );

					$form_fields_html = ob_get_clean();

					$options_url = admin_url( 'options.php' );

					if ( ! $this->admin_page->has_tabs() ) {
						$hidden_section_input_html = '';
					} else {
						$tab_slug = $this->admin_page->get_current_tab()->tab_slug;

						$hidden_section_input_html = <<<HTML
			<input type="hidden" name="{$this->plugin->option_name}[_sidecar_form_meta][plugin]" value="{$this->plugin->plugin_name}" />
			<input type="hidden" name="{$this->plugin->option_name}[_sidecar_form_meta][page]" value="{$this->admin_page->page_name}" />
			<input type="hidden" name="{$this->plugin->option_name}[_sidecar_form_meta][tab]" value="{$tab_slug}" />
			<input type="hidden" name="{$this->plugin->option_name}[_sidecar_form_meta][form]" value="{$this->form_name}" />
			HTML;
					}
					$buttons_html = array();
					foreach ( $this->_buttons as $button ) {
						$button_html    = get_submit_button(
							$button->button_text,
							$button->button_type,
							$button->input_name,
							$button->button_wrap,
							$button->other_attributes
						);
						$buttons_html[] = preg_replace( '#^<p(.*)</p>#', '<span$1</span>', $button_html );
					}
					$hidden_section_input_html .= sprintf( '<div class="dmca-extra-submit">%s</div>', get_submit_button( esc_html__( 'Save Changes', 'dmca-badge' ), 'primary', 'submit', false ) );
					$buttons_html       = implode( "\n", $buttons_html );
					$form               = <<<HTML
			<form action="{$options_url}" method="POST">
			{$hidden_section_input_html}
			<div class="form-fields">
				{$hidden_fields_html}
				{$form_fields_html}
			</div>
			<div class="form-buttons">
				{$buttons_html}
			</div>
			</form>
			HTML;
					$wp_settings_fields = $save_wp_settings_fields;

					return $form;
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}
	}

	/**
	 * @return array
	 */
	function get_fields() {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			return $this->_fields;
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}

	}

	/**
	 * @param string $button_name
	 * @param string $button_text
	 * @param array $args
	 *  string $type The type of button. One of: primary, secondary, delete
	 *  string $name The HTML name of the submit button. Defaults to "submit". If no id attribute
	 *               is given in $other_attributes below, $name will be used as the button's id.
	 *  bool $wrap True if the output button should be wrapped in a paragraph tag,
	 *               false otherwise. Defaults to true
	 *  array|string $other_attributes Other attributes that should be output with the button,
	 *                     mapping attributes to their values, such as array( 'tabindex' => '1' ).
	 *                     These attributes will be output as attribute="value", such as tabindex="1".
	 *                     Defaults to no other attributes. Other attributes can also be provided as a
	 *                     string such as 'tabindex="1"', though the array format is typically cleaner.   */
	function add_button( $button_name, $button_text, $args = array() ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			$args['button_name'] = $button_name;
			$args['button_text'] = $button_text;

			$this->_buttons[ $button_name ] = (object) $args;
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}
	}

	/**
	 * @param string $button_name
	 *
	 * @return bool
	 */
	function get_button( $button_name ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			return isset( $this->_buttons[ $button_name ] ) ? $this->_buttons[ $button_name ] : false;
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}
	}

	/**
	 * @param Sidecar_Plugin_Base $plugin
	 */
	function initialize_sections( $plugin ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			$settings = $this->get_settings();
			foreach ( $this->get_sections() as $section_name => $section ) {
				if ( ! $section->section_handler ) {
					$section->section_handler = array( $plugin, 'the_form_section' );
				}
				add_settings_section( $section_name, $section->section_title, $section->section_handler, $this->plugin->option_name, array(
					'section'  => $section,
					'form'     => $this,
					'plugin'   => $plugin,
					'settings' => $settings,
				) );
				foreach ( $section->fields as $field_name => $field ) {
					if ( ! $field->field_handler ) {
						$field->field_handler = array( $this, '_the_form_field_callback' );
					}
					$field_label = 'checkbox' != $field->field_type ? $field->field_label : false;
					add_settings_field( $field_name, $field_label, $field->field_handler, $this->plugin->option_name, $section_name, array(
						'field'    => $field,
						'section'  => $section,
						'form'     => $this,
						'plugin'   => $plugin,
						'settings' => $settings,
					) );
				}
			}
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}

	}

	/**
	 * @return Sidecar_Form_Settings
	 */
	function get_settings() {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			if ( ! isset( $this->_settings ) ) {
				$this->_settings = $this->plugin->get_form_settings( $this->form_name );
			}

			return $this->_settings;
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}

	}

	/**
	 * @return array
	 */
	function get_sections() {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			return $this->_sections;
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}
	}

	/**
	 * @param array $args
	 */
	function _the_form_field_callback( $args ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			$this->plugin->the_form_field( $args['field']->field_name, $args['form']->form_name );
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}
	}

	/**
	 * @param Sidecar_Plugin_Base $plugin
	 */
	function initialize_buttons( $plugin ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			foreach ( $this->_buttons as $button_name => $button ) {

				if ( ! isset( $button->button_type ) ) {
					$button->button_type = 'primary';
				}

				if ( ! isset( $button->input_name ) ) {
					if ( 'primary' == $button->button_type ) {
						$button->input_name = 'submit';
					} else {
						$button->input_name = "{$this->plugin->option_name}[action][{$button_name}]";
					}
				}

				if ( ! isset( $button->button_wrap ) ) {
					$button->button_wrap = true;
				}

				if ( ! isset( $button->other_attributes ) ) {
					$button->other_attributes = false;
				}

			}
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}
	}

	/**
	 * @param string $field_name
	 * @param array $args
	 *
	 * @return object
	 */
	function add_field( $field_name, $args = array() ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			if ( 0 == count( $this->_sections ) ) {
				/**
				 * We don't have any sections, set them, to 'default' and register
				 */
				$section_name = 'default';
				$this->add_section( $section_name );
			} else if ( ! isset( $args['section_name'] ) ) {
				/**
				 * Get the name of the last section added to the sections array.
				 */
				end( $this->_sections );
				$section_name = key( $this->_sections );
			} else {
				/**
				 * If was passed, grab it.
				 */
				$section_name = $args['section_name'];
			}
			/**
			 * Luke, I am your father!
			 * (or for those who don't get the reference, assign reference to $args['form'] so it will know it's form.)
			 */
			$args['form']                                            = $this;
			$args['plugin']                                          = $this->plugin;
			$args['section']                                         = $this->get_section( $section_name );
			$field                                                   = new Sidecar_Field( $field_name, $args );
			$this->_fields[ $field_name ]                            = &$field;
			$this->_sections[ $section_name ]->fields[ $field_name ] = &$field;

			$this->get_settings()->register_setting( $field_name );
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}

	}

	/**
	 * @param string $section_name
	 * @param array $args
	 *
	 * @return object
	 */
	function add_section( $section_name, $args = array() ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			$args = (object) $args;

			$args->section_name = $section_name;
			$args->form         = $this;

			if ( ! isset( $args->fields ) ) {
				$args->fields = array();
			}

			if ( ! isset( $args->section_title ) ) {
				$args->section_title = false;
			}

			if ( ! isset( $args->section_handler ) ) {
				$args->section_handler = false;
			}

			return $this->_sections[ $section_name ] = $args;
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}
	}

	/**
	 * @param string $section_name
	 *
	 * @return bool
	 */
	function get_section( $section_name ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			return isset( $this->_sections[ $section_name ] ) ? $this->_sections[ $section_name ] : false;
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}
	}

	/**
	 * Get an array of new setting values (empty string; '').
	 *
	 * Override in subclass to add more specific setting defaults.
	 * @return array
	 *
	 * @todo Cache new settings in a setting so that loading from front end does...
	 * @todo ...require setting the admin form and traversing through these fields.
	 */
	function get_default_settings_values() {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			if ( ! isset( $this->_default_settings_values ) ) {
				$default_settings_values = array();
				foreach ( $this->get_fields() as $field ) {
					$default_settings_values[ $field->field_name ] = isset( $field->field_default ) ? $field->field_default : '';
				}
				$this->_default_settings_values = $default_settings_values;
			}

			return $this->_default_settings_values;
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}
	}

	/**
	 * @return array
	 */
	function get_empty_field_values() {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			return $this->get_settings()->get_empty_field_values();
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}
	}

	/**
	 * @return array
	 */
	function get_settings_values() {
			
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			return $this->get_settings()->get_values();
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}
	}

	/**
	 * @param string $setting_name
	 *
	 * @return bool
	 */
	function has_setting( $setting_name ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			return $this->get_settings()->has_setting( $setting_name );
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}
	}

	/**
	 * @param string $setting_name
	 *
	 * @return mixed
	 */
	function get_setting( $setting_name ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			return $this->get_settings()->get_setting( $setting_name );
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}
	}

	/**
	 * @param string $setting_name
	 * @param mixed $value
	 *
	 * @return array
	 */
	function update_settings_value( $setting_name, $value ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			$this->get_settings()->update_settings_value( $setting_name, $value );
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}
	}

	/**
	 * @param array $form_settings
	 *
	 * @return array
	 */
	function update_settings( $form_settings ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			$this->get_settings()->update_settings( $form_settings );
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}
	}

	/**
	 * @param array $form_settings
	 *
	 * @return array
	 */
	function update_settings_values( $form_settings ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			$this->get_settings()->set_values( $form_settings );
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}
	}

	/**
	 * @return array
	 */
	function get_required_field_names() {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			if ( ! isset( $this->_required_field_names ) ) {
				$required_field_names = array();
				$this->initialize();
				$form_settings = $this->get_settings();
				foreach ( $this->get_fields() as $field_name => $field ) {
					if ( $field->field_required && $form_settings->has_setting( $field_name ) ) {
						$required_field_names[] = $field_name;
					}
				}
				if ( method_exists( $this, 'filter_required_field_names' ) ) {
					$required_field_names = $this->filter_required_field_names( $required_field_names, $settings );
				}
				$this->_required_field_names = $required_field_names;
			}

			return $this->_required_field_names;
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}
	}

	/**
	 */
	function initialize() {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			if ( ! $this->_initialized ) {

				if ( ! $this->has_fields() ) {
					$this->plugin->initialize_form( $this );
				}
				$this->_initialized = true;

			}
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}
	}

	/**
	 * @return bool
	 */
	function has_fields() {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			return 0 < count( $this->_fields );
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}
	}

	/**
	 * Ensure their is a default value for every field w/o a matching array element.
	 *
	 * This is used to ensure forms POSTed back always have all fields. In cases of
	 * checkboxes or radio buttons they may not.
	 *
	 * @param array $field_values
	 *
	 * @return array
	 */
	function ensure_default_values( $field_values ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			foreach ( $this->_fields as $field_name => $field ) {
				if ( ! isset( $field_values[ $field_name ] ) ) {
					$field_values[ $field_name ] = ! is_null( $field->field_default ) ? $field->field_default : false;
				}
			}

			return $field_values;
		}
		catch (Exception $e) 
		{  
		  echo 'Exception Message: ' .$e->getMessage();  
		  if ($e->getSeverity() === E_ERROR) {
			  echo("E_ERROR triggered.\n");
		  } else if ($e->getSeverity() === E_WARNING) {
			  echo("E_WARNING triggered.\n");
		  }
		  echo "<br> $error_path";
		}  
		catch (ErrorException  $er)
		{  
		  echo 'ErrorException Message: ' .$er->getMessage();  
		  echo "<br> $error_path";
		}  
		catch ( Throwable $th){
		  echo 'ErrorException Message: ' .$th->getMessage();
		  echo "<br> $error_path";
		}
	}

}
