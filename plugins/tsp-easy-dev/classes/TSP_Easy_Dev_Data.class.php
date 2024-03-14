<?php
if ( !class_exists( 'TSP_Easy_Dev_Data' ) )
{
	/**
	 * Class to manipulate Easy Dev fields for widget_fields, post_fields, settings_fields and category_fields
	 * @package 	TSP_Easy_Dev
	 * @author 		sharrondenice, letaprodoit
	 * @author 		Sharron Denice, Let A Pro Do IT!
	 * @copyright 	2021 Let A Pro Do IT!
	 * @license 	APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
	 * @version 	1.3.0
	 */
	final class TSP_Easy_Dev_Data
	{
		/**
		 * The fields that this class will manage
		 * fields expects the array to be in the following format:
		 * 
		 * 'show_names' 	=> array( 
		 *		'value' 		=> 'Y',
		 *		'html'			=> true,
		 * 	),	
		 *	
		 * value: 	is the default value for the field
		 * html:	TRUE or FALSE (is html allowed for the value)
		 *
		 * Other keys can be added but will not be used
		 *
		 * @var array
		 */
		private $fields 	= array();
		/**
		 * A boolean to turn debugging on for this class
		 *
		 * @ignore
		 *
		 * @var boolean
		 */
		private $debugging 	= false;
		
		/**
		 * Constructor
		 *
		 * @since 1.0
		 *
		 * @param array $fields Required - Sets the fields
         * @param string $field_type Required - The field type
         * @param integer|false $post_id optional - the post id
         * @param integer|false $term_id optional - the term id
		 *
		 * @return void
		 */
		public function __construct( $fields, $field_type, $post_id = false, $term_id = false)
		{
			$this->update_realtime_fields( $fields, $field_type );
			$this->update_dynamic_fields( $fields, $field_type );
			$this->set( $fields );

            if ($post_id)
            {
                $default_fields = $this->get_values();
                $data = array();
                foreach ( $default_fields as $key => $value )
                {
                    $data[$key]    = get_post_meta( $post_id, $key, 1 );
                }//end foreach
                $this->set_values( $data );
            }
            if ($term_id)
            {
                $default_fields = $this->get_values();
                $data = array();
                foreach ( $default_fields as $key => $value )
                {
                    $data[$key]    = get_term_meta( $term_id, $key, 1 );
                }//end foreach
                $this->set_values( $data );
            }
		}//end __construct

		/**
		 * Replace settings with values in instance
		 *
		 * @api
		 *
		 * @since 1.0
		 *
		 * @param array $settings Required - Settings to store in globals
		 *
		 * @return void
		 */
		public function set ( $fields )
		{
			$this->fields = $fields;
		}//end set

		/**
		 * Set and process field values
		 *
		 * @api
		 *
		 * @since 1.0
		 *
		 * @param array $settings Required - Settings to store in globals
		 *
		 * @return array $fields array of field values
		 */
		public function set_values ( $fields )
		{
			if (!empty( $fields ) && !empty( $this->fields) )
			{
				// don't just assign the settings to global
				// process it to make sure its formatted correctly
				foreach ( $this->fields as $key => $opts )
				{
		        	if ( array_key_exists( $key, $fields ))
		        	{
                        $value = null;

	        			if (is_array($fields[$key]))
                            $value = $fields[$key];
                        else
                            $value = $this->encode_html( $fields[$key], $this->html_ok ( $opts ) );
			        	
			        	$this->fields[$key]['value'] = $value;
		        	}//end if
				}//end foreach
			}//end if
		}//end set_values

		/**
		 * Set a global settings with specified key with value
		 *
		 * @api
		 *
		 * @since 1.0
		 *
		 * @param string $key Required - The setting key to be set
		 * @param string $value Optional - The value to set the key
		 *
		 * @return void
		 */
		public function set_value_by_key ( $key, $value = null )
		{
			if ( array_key_exists($key, $this->fields ))
        	{
	        	$opts = $this->fields[$key];
	        	
    			if (!is_array($value))
                    $value = $this->encode_html ( $value, $this->html_ok ( $opts ) );
	        	
	        	$this->fields[$key]['value'] = $value;	
        	}//endif
		}//end set_by_key

		/**
		 * Get the current field values
		 *
		 * @api
		 *
		 * @since 1.0
		 *
		 * @param void
		 *
		 * @return array The assigned global values
		 */
		public function get ()
		{
			return $this->fields;
		}//end get

		
		/**
		 * Get the field values
		 *
		 * @api
		 *
		 * @since 1.0
		 *
		 * @param bool $include_options Optional - Return array that is in $key/value or all field attributes
		 * if options are included update id, name with the name of the key
		 *
		 * @return array $fields array of field values
		 */
		public function get_values ( $include_options = false )
		{
			$fields = array();
			
			// if we want all field attributes 
			if ( $include_options )
			{
				$fields = $this->fields;
				
			}//endif
			
			if (!empty($this->fields))
			{
				foreach ( $this->fields as $key => $opts )
				{
					$value = null;
				
					if (is_array($opts['value']))
						$value = $opts['value'];
					else
						$value = $this->decode_html ( $opts['value'] );
						
					// in addition to updating the value we also
					// need to add an id and a name for form fields
					if ( $include_options )
					{
						$fields[$key]['id'] 	= $key;
						$fields[$key]['name'] 	= $key;
						$fields[$key]['value'] 	= $value;
					}//endif
					else
					{
						$fields[$key] = $value;
					}//endelse
				
				}//end foreach
			}
											
			return $fields;
		}//end get_values

		/**
		 * Get the global settings given a key
		 *
		 * @api
		 *
		 * @since 1.0
		 *
		 * @param string $key Required - The setting key to be used to get the entire array
		 *
		 * @return object The assigned global settings with the specified key
		 */
		public function get_options_by_key ( $key )
		{
			$options = array();

			if ( array_key_exists($key, $this->fields ))
			{
				$options = $this->fields[$key];
			}//end if
			
			return $options;
		}//end get_by_key

		/**
		 * Get the global settings value given a key
		 *
		 * @api
		 *
		 * @since 1.0
		 *
		 * @param string $key Required - The setting key to be used to get value
		 *
		 * @return string|int The assigned global settings value with the specified key
		 */
		public function get_value_by_key ( $key )
		{
        	$value = "";
			
			if ( array_key_exists($key, $this->fields ))
			{
	        	$opts = $this->fields[$key];
	        	$value = $this->decode_html ( $opts['value'] );
        	}//end if
        	
			return $value;
		}//end get_value_by_key
		
		/**
		 * Process all entries in array for display
		 *
		 * @api
		 *
		 * @since 1.0
		 *
		 * @param void
		 *
		 * @return void
		 */
		public function decode_values ()
		{
			foreach ( $this->fields as $key => $opts )
			{
    			$value = $this->decode_html ( $opts['value'] );
	        	
	        	$this->fields[$key]['value'] = $value;
			}//end foreach
		}//end decode_values

		
		/**
		 * Process all entries in array for database save
		 *
		 * @api
		 *
		 * @since 1.0
		 *
		 * @param void
		 *
		 * @return void
		 */
		public function encode_values ()
		{
			foreach ( $this->fields as $key => $opts )
			{
    			$value = $this->encode_html ( $opts['value'], $this->html_ok ( $opts ) );
	        	
	        	$this->fields[$key]['value'] = $value;
			}//end foreach
		}//end encode_values

		/**
		 * Process string for viewing on screen
		 *
		 * @since 1.0
		 *
		 * @param string $str Required - String to process
		 *
		 * @return string $str Required - Processed string
		 */
		private function decode_html ( $str )
		{
        	$str = stripslashes ( $str );
        	$str = preg_replace( '/"/', "'", $str );
        	$str = html_entity_decode( $str, ENT_QUOTES );

			return $str;
		}//end decode_html

		/**
		 * Process string for saving to database
		 *
		 * @since 1.0
		 *
		 * @param string $str Required - String to process
		 *
		 * @return string $str Required - Processed string
		 */
		private function encode_html ( $str, $tags = false )
		{
        	if ( !$tags )
        	{
        		$str = strip_tags ( $str );
        	}//endif
        	
        	$str = preg_replace( '/"/', "'", $str );
        	$str = htmlentities( $str, ENT_QUOTES );

			return $str;
		}//end decode_html
		
		/**
		 * Determine if its ok to store html in the field
		 *
		 * @since 1.0
		 *
		 * @param string $str Required - String to process
		 *
		 * @return boolean $html_ok Required - html status
		 */
		private function html_ok ( $arr )
		{
        	$html_ok = false;
        	
        	if ( array_key_exists('html', $arr ))
        	{
        		$html_ok = $arr['html'];
        	}//end if
        	
        	return $html_ok;
		}//end html_ok

		/**
		 * Update fields that have realtime data updates
		 *
		 * @api
		 *
		 * @since 1.2.9
		 *
		 * @param array $fields Required - The fields array
		 * @param string $field_type Required - The field type - post, cateogry, shortcode or widget
		 *
		 * @return void
		 */
		private function update_realtime_fields ( &$fields, $field_type )
		{
			global $easy_dev_settings;
		
			if (!empty($fields))
			{
				foreach ($fields as $key => $value)
				{
					if (is_array($value) && isset($value['realtime']))
					{
						$fields[$key]['options'] = $easy_dev_settings['plugin_options'][$field_type . '_fields'][$key]['options'];
					}//end if
				}//end foreach
			}//end if
		}//end update_realtime_fields

		/**
		 * Update fields that have dynamic data types
		 *
		 * @api
		 *
		 * @since 1.3.0
		 *
		 * @param array $fields Required - The fields array
		 * @param string $field_type Required - The field type - post, cateogry, shortcode or widget
		 *
		 * @return void
		 */
		private function update_dynamic_fields ( &$fields, $field_type )
		{
			global $easy_dev_settings;
		
			if (!empty($fields))
			{
				foreach ($fields as $key => $value)
				{
					if ($value['type'] == 'SELECT_PAGES')
					{
						$pages = get_pages();
						$options = array();
						
						foreach ($pages as $index => $page)
							$options[$page->ID] = $page->post_title;

						$fields[$key]['options'] = $options;
					}//end if
				}//end foreach
			}//end if
		}//end update_dynamic_fields
	}//end TSP_Easy_Dev_Data
}//endif