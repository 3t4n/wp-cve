<?php

/**
 *
 */
class Sidecar_Field {

	/**
	 * @var Sidecar_Plugin_Base
	 */
	var $plugin;

	/**
	 * @var Sidecar_Form
	 */
	var $form;

	/**
	 * @var array
	 */
	var $section;

	/**
	 * @var string
	 */
	var $field_name;

	/**
	 * @var string
	 */
	var $field_slug;

	/**
	 * @var string
	 */
	var $field_label;

	/**
	 * @var string
	 */
	var $field_type;

	/**
	 * @var string
	 */
	var $field_help;

	/**
	 * @var int
	 */
	var $field_size;

	/**
	 * @var int|array
	 */
	var $field_validator;

	/**
	 * @var string
	 */
	var $field_default;

	/**
	 * @var array
	 */
	var $field_options;

	/**
	 * @var bool
	 */
	var $field_required = false;

	/**
	 * @var bool|callable
	 */
	var $field_handler = false;

	/**
	 * Indicates this field value if used for an API.
	 * Defaults to $this->field_name, can be see to another name
	 * (i.e. field_name = 'content_type', api_var = 'type'
	 * but if set to false it will cause the API to ignore this field.
	 * @var null|bool|string
	 */
	var $api_var;

	/**
	 * @var array
	 */
	var $_extra = array();

	/**
	 * @var array
	 */
	var $field_allow_html = false;

	/**
	 * @param string $field_name
	 * @param array $args
	 */
	function __construct( $field_name, $args = array() ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			$this->field_name = $field_name;
			/**
			 * Copy properties in from $args, if they exist.
			 */
			foreach ( $args as $property => $value ) {
				if ( property_exists( $this, $property ) ) {
					$this->$property = $value;
				} else if ( property_exists( $this, $property = "field_{$property}" ) ) {
					$this->$property = $value;
				} else {
					$this->_extra[ $property ] = $value;
				}
			}
			if ( ! $this->field_type ) {
				$this->field_type = 'password' == $this->field_name ? 'password' : 'text';
			}

			if ( 'hidden' == $this->field_type ) {
				$this->field_label = false;
			} else if ( ! $this->field_label ) {
				$this->field_label = ucwords( $this->field_name );
			}

			if ( ! $this->field_size ) {
				$this->field_size = preg_match( '#(text|password)#', $this->field_type ) ? 40 : false;
			}

			if ( ! $this->field_slug ) {
				$this->field_slug = str_replace( array( '_', ' ' ), '-', $this->field_name );
			}

			if ( is_null( $this->api_var ) ) {
				$this->api_var = $this->field_name;
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
	 * @return string
	 */
	function get_input_name() {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			return "{$this->plugin->option_name}[{$this->form->form_name}][{$this->field_name}]";
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
	 * Sets HTML id like the following:
	 *
	 * @return string
	 * @example
	 *  HTML name = my_plugin_settings[_form-name}[field-name]
	 *  HTML id =>  my-plugin-settings--form-name-field-name
	 *
	 */
	function get_input_id() {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			$input_name = $this->get_input_name();
			$input_id   = str_replace( array( '[_', '_', '][', '[', ']' ), array( '--', '-', '-', '-', '' ), $input_name );

			return $input_id;
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
	function get_input_size_html() {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			return $this->field_size ? " size=\"{$this->field_size}\"" : '';
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
	function get_input_help_html() {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			return $this->field_help ? "\n<br />\n<p class=\"{$this->plugin->css_base}-field-help\">{$this->field_help}</p>" : false;
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
					
					/**
					 * @todo: Get options with all expected elements initialized
					 */
					$form       = $this->form;
					$value      = $form->get_setting( $this->field_name );
					$input_name = $this->get_input_name();
					$input_id   = $this->get_input_id();
					$size_html  = $this->get_input_size_html();
					$css_base   = $this->plugin->css_base;
					$help_html  = $this->get_input_help_html();

					if ( 'radio' == $this->field_type ) {
						$html = array( "<ul id=\"{$input_id}-radio-field-options\" class=\"radio-field-options\">" );
						foreach ( $this->field_options as $option_value => $option_label ) {
							$checked      = ( ! empty( $value ) && $option_value == $value ) ? 'checked="checked" ' : false;
							$selected     = ( ! empty( $value ) && $option_value == $value ) ? 'selected' : '';
							$option_value = esc_attr( $option_value );
							$html[]       = <<<HTML
			<li><label for={$input_id}-{$option_value} class={$selected}><input type="radio" id="{$input_id}-{$option_value}" class="{$css_base}-field" name="{$input_name}" value="{$option_value}" {$checked}/> {$option_label}</label></li>
			HTML;
						}
						$html = implode( "\n", $html ) . "</ul>{$help_html}";
					} else if ( 'select' == $this->field_type ) {
						$html = array( "<select id=\"{$input_id}-select-field-options\" name=\"{$input_name}\" class=\"select-field-options\">" );
						foreach ( $this->field_options as $option_value => $option_label ) {
							$selected     = ( ! empty( $value ) && $option_value == $value ) ? ' selected="selected"' : false;
							$option_value = esc_attr( $option_value );
							$html[]       = <<<HTML
			<option value="{$option_value}"{$selected}>{$option_label}</option>
			HTML;
						}
						$html = implode( "\n", $html ) . "</select>{$help_html}";
					} else if ( 'checkbox' == $this->field_type ) {
						$checked = ! empty( $value ) ? 'checked="checked" ' : false;
						$html    = <<<HTML
			<input type="checkbox" id="{$input_id}" class="{$css_base}-field" name="{$input_name}" value="1" {$checked}/>
			<label for="{$input_id}">{$this->field_label}</label>
			HTML;
					} else if ( 'hidden' == $this->field_type ) {
						$html = <<<HTML
			<input type="hidden" id="{$input_id}" name="{$input_name}" value="{$value}" />
			HTML;
					} else if ( 'textarea' == $this->field_type ) {
			//      $value = htmlentities( $value );
						if ( $rows = $this->get_extra( 'rows' ) ) {
							$rows = " rows=\"{$rows}\"";
						}
						if ( $cols = $this->get_extra( 'cols' ) ) {
							$cols = " cols=\"{$cols}\"";
						}
						$html = <<<HTML
			<textarea id="{$input_id}" name="{$input_name}"{$rows}{$cols}>{$value}</textarea>{$help_html}
			HTML;
					} else {
						$html = <<<HTML
			<input type="{$this->field_type}" id="{$input_id}" name="{$input_name}" value="{$value}" class="{$css_base}-field"{$size_html}/>{$help_html}
			HTML;
					}
					$field_wrapper_id = $this->get_wrapper_id();
					$html             = <<<HTML
			<div id="{$field_wrapper_id}" class="{$this->field_type}">{$html}</div>
			HTML;

					return apply_filters( 'dmca_filters_get_form_field_html', $html, $this->field_name, $this );
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
	 * @param string|$property_name
	 *
	 * @return null|int|string
	 */
	function get_extra( $property_name ) {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			$value = null;
			if ( isset( $this->_extra[ $prefixed_name = "field_{$property_name}" ] ) ) {
				$value = $this->_extra[ $prefixed_name ];
			} else if ( isset( $this->_extra[ $property_name ] ) ) {
				$value = $this->_extra[ $property_name ];
			}

			return $value;
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
	function get_wrapper_id() {
		$error_path = plugin_dir_url(__FILE__) ;
		try {
			
			return "field-{$this->field_slug}-input";
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