<?php
class OTW_Form extends OTW_Component{
	
	/**
	 * Init in front
	 */
	public $init_in_front = false;
	
	/**
	 * component url
	 */
	public static $static_component_url;
	
	/**
	 * js version
	 */
	public static $static_js_version;

	/**
	 * css version
	 */
	public static $static_css_version;
	
	/**
	 *  Init 
	 */
	public function init(){
		
		self::$static_component_url = $this->component_url;
		
		self::$static_js_version = $this->js_version;
		
		self::$static_css_version = $this->css_version;
		
		if( is_admin() ){
			
			if( method_exists( $this, 'add_lib' ) ){
				
				$this->add_lib( 'js', 'colorpicker_admin', $this->component_url.'js/colorpicker.js' , 'admin', 50, array( 'jquery' ) );
				$this->add_lib( 'js', 'select2_admin', $this->component_url.'js/colorpicker.js' , 'admin', 50, array( 'jquery' ) );
				$this->add_lib( 'js', 'datetimepicker_admin', $this->component_url.'js/datetimepicker.js' , 'admin', 50,  array( 'jquery' ) );
				$this->add_lib( 'js', 'otw_form_admin', $this->component_url.'js/otw_form_admin.js' , 'admin', 50,  array( 'jquery' ) );
				
				$this->add_lib( 'css', 'colorpicker_admin', $this->component_url.'css/colorpicker.css', 'admin', 50, array( ) );
				$this->add_lib( 'css', 'select2_admin', $this->component_url.'css/select2.min.css', 'admin', 50, array( ) );
				$this->add_lib( 'css', 'datetimepicker_admin', $this->component_url.'css/datetimepicker.css', 'admin', 50, array( ) );
				$this->add_lib( 'css', 'otw_form_admin', $this->component_url.'css/otw_form_admin.css', 'admin', 50, array( ) );
				
			}else{
				wp_enqueue_script('colorpicker_admin', $this->component_url.'js/colorpicker.js' , array( 'jquery' ), $this->js_version );
				wp_enqueue_script('select2_admin', $this->component_url.'js/select2.full.min.js' , array( 'jquery' ), $this->js_version );
				wp_enqueue_script('datetimepicker_admin', $this->component_url.'js/datetimepicker.js' , array( 'jquery' ), $this->js_version );
				wp_enqueue_script('otw_form_admin', $this->component_url.'js/otw_form_admin.js' , array( 'jquery' ), $this->js_version );
				
				wp_enqueue_style( 'colorpicker_admin', $this->component_url.'css/colorpicker.css', array( ), $this->css_version );
				wp_enqueue_style( 'select2_admin', $this->component_url.'css/select2.min.css', array( ), $this->css_version );
				wp_enqueue_style( 'datetimepicker_admin', $this->component_url.'css/datetimepicker.css', array( ), $this->css_version );
				wp_enqueue_style( 'otw_form_admin', $this->component_url.'css/otw_form_admin.css', array( ), $this->css_version );
			}
			
		}elseif( $this->init_in_front ){
			
			wp_enqueue_script('cpicker', $this->component_url.'js/colorpicker.js' , array( 'jquery' ), $this->js_version );
			wp_enqueue_script('select2', $this->component_url.'js/select2.full.min.js' , array( 'jquery' ), $this->js_version );
			wp_enqueue_script('otw_form', $this->component_url.'js/otw_form_admin.js' , array( 'jquery' ), $this->js_version );
			
			wp_enqueue_style( 'cpicker', $this->component_url.'css/colorpicker.css', array( ), $this->css_version );
			wp_enqueue_style( 'select2', $this->component_url.'css/select2.min.css', array( ), $this->css_version );
			wp_enqueue_style( 'otw_form', $this->component_url.'css/otw_form_admin.css', array( ), $this->css_version );
		}
		
		parent::init();
	}
	
	/** dynamic select
	 *
	 *  @param array
	 *
	 *  @return string
	 */
	public static function dynamic_select( $attributes = array() ){
		
		$html = '';
		
		$attributes = self::parse_attributes( $attributes, 'dynamic-select' );
		
		if( isset( $attributes['parse'][ $attributes['id'] ] ) ){
			$attributes['value'] = $attributes['parse'][ $attributes['id'] ];
		}
		
		switch( $attributes['format'] ){
		
			case 'drop_mask':
					$html .= "<div class=\"otw-form-control\">";
					if( $attributes['label'] || $attributes['show_empty_label'] ){
						$html .= "<label".self::format_attribute( 'for', 'id', $attributes ).">".$attributes['label']."</label>";
					}
					
					$html .= "<div class=\"otw-dynamic-select-wrapper\">";
					$html .= "<select ".self::format_attributes( array('id','name','class','style'), array(), $attributes ).self::format_attribute( 'data-value', 'value', $attributes )."></select>";
					$html .= "<input type=\"hidden\" id=\"".$attributes['id']."_json\" value=\"".otw_htmlentities( $attributes['json_value'] )."\" />";
					$html .= "<div class=\"otw-clear\">- OR -</div>";
					
					$value = '';
					
					if( isset( $attributes['parse'] ) && isset( $attributes['parse'][ $attributes['input_name'] ] ) ){
						$value = $attributes['parse'][ $attributes['input_name'] ];
					}
					
					$html .= "<div class=\"otw-clear\"><input type=\"text\" id=\"".$attributes['input_name']."\" value=\"".$value."\" class=\"otw-form-text-input\" /></div>";
					$html .= "</div>";
					if( $attributes['description'] ){
							$html .= "<span class=\"otw-form-hint\">".$attributes['description']."</span>";
					}
					
					$html .= "</div>";
				break;
			default:
					$html .= "<div class=\"otw-form-control\">";
					if( $attributes['label'] || $attributes['show_empty_label'] ){
						$html .= "<label".self::format_attribute( 'for', 'id', $attributes ).">".$attributes['label']."</label>";
					}
					
					$html .= "<div class=\"otw-dynamic-select-wrapper\">";
					$html .= "<select ".self::format_attributes( array('id','name','class','style'), array(), $attributes ).self::format_attribute( 'data-value', 'value', $attributes )." multiple=\"multiple\"></select>";
					
					$html .= "<div class=\"otw-clear\">- OR -</div>";
					
					$checked = '';
					
					if( isset( $attributes['parse'] ) && isset( $attributes['parse'][ $attributes['id'].'_allitems' ] ) && ( $attributes['parse'][ $attributes['id'].'_allitems' ] == '1' ) ){
						$checked = ' checked="checked"';
					}
					
					$html .= "<div class=\"otw-clear\"><input type=\"checkbox\" id=\"".$attributes['id']."_allitems\" ".$checked." value=\"1\" class=\"otw-all-list\" /><label for=\"".$attributes['id']."_allitems\">Select All</label></div>";
					$html .= "</div>";
					if( $attributes['description'] ){
							$html .= "<span class=\"otw-form-hint\">".$attributes['description']."</span>";
					}
					$html .= "</div>";
				break;
		}
		
		return $html;
	}
	

	/** select
	 *
	 *  @param array
	 *
	 *  @return string
	 */
	public static function select( $attributes = array() ){
		
		$html = '';
		
		$attributes = self::parse_attributes( $attributes, 'select' );
		
		if( isset( $attributes['parse'][ $attributes['id'] ] ) ){
			$attributes['value'] = $attributes['parse'][ $attributes['id'] ];
		}
		
		switch( $attributes['format'] ){
		
			case 'no_form_control':
					if( $attributes['label'] || $attributes['show_empty_label'] ){
						$html .= "<label".self::format_attribute( 'for', 'id', $attributes ).">".$attributes['label']."</label>";
					}
					
					$html .= "<div class=\"otw-select-wrapper\">";
					$html .= "<span>";
					if( isset( $attributes['options'][ $attributes['value'] ] ) ){
						$html .= $attributes['options'][ $attributes['value'] ];
					}else{
						foreach( $attributes['options'] as $key => $value ){
							$html .= $value;
							break;
						}
					}
					$html .= "</span>";
					$html .= "<select ".self::format_attributes( array('id','name','class','style', 'data-reload'), array(), $attributes )." ".$attributes['extra'].">";
					
					foreach( $attributes['options'] as $key => $value ){
						$selected = "";
						
						if( strnatcasecmp( $key, $attributes['value'] ) === 0 ){
							$selected = " selected=\"selected\"";
						}
						$html .= "<option value=\"".$key."\"".$selected.">".$value."</option>";
					}
					$html .= "</select>";
					$html .= "</div>";
					
					if( $attributes['description'] ){
							$html .= "<span class=\"otw-form-hint\">".$attributes['description']."</span>";
					}
				break;
			case 'select2':
					$html .= "<div class=\"otw-form-control\">";
					if( $attributes['label'] || $attributes['show_empty_label'] ){
						$html .= "<label".self::format_attribute( 'for', 'id', $attributes ).">".$attributes['label']."</label>";
					}
					
					$html .= "<div class=\"otw-select-wrapper\">";
					
					$html .= "<select ".self::format_attributes( array('id','name','class','style', 'data-reload'), array(), $attributes )." ".$attributes['extra'].">";
					
					foreach( $attributes['options'] as $key => $value ){
						$selected = "";
						
						if( strnatcasecmp( $key, $attributes['value'] ) === 0 ){
							$selected = " selected=\"selected\"";
						}
						$html .= "<option value=\"".$key."\"".$selected.">".$value."</option>";
					}
					$html .= "</select>";
					$html .= "</div>";
					
					if( $attributes['description'] ){
							$html .= "<span class=\"otw-form-hint\">".$attributes['description']."</span>";
					}
					$html .= "</div>";
				break;
			case 'wpui':
					$html .= "<select ".self::format_attributes( array('id','name','class','style', 'data-reload'), array(), $attributes )." ".$attributes['extra'].">";
					
					foreach( $attributes['options'] as $key => $value ){
						$selected = "";
						
						if( strnatcasecmp( $key, $attributes['value'] ) === 0 ){
							$selected = " selected=\"selected\"";
						}
						$html .= "<option value=\"".$key."\"".$selected.">".$value."</option>";
					}
					$html .= "</select>";
				break;
			default:
					$html .= "<div class=\"otw-form-control\">";
					if( $attributes['label'] || $attributes['show_empty_label'] ){
						$html .= "<label".self::format_attribute( 'for', 'id', $attributes ).">".$attributes['label']."</label>";
					}
					
					$html .= "<div class=\"otw-select-wrapper\">";
					$html .= "<span>";
					if( isset( $attributes['options'][ $attributes['value'] ] ) ){
						$html .= $attributes['options'][ $attributes['value'] ];
					}else{
						foreach( $attributes['options'] as $key => $value ){
							$html .= $value;
							break;
						}
					}
					$html .= "</span>";
					$html .= "<select ".self::format_attributes( array('id','name','class','style', 'data-reload'), array(), $attributes )." ".$attributes['extra'].">";
					
					foreach( $attributes['options'] as $key => $value ){
						$selected = "";
						
						if( strnatcasecmp( $key, $attributes['value'] ) === 0 ){
							$selected = " selected=\"selected\"";
						}
						$html .= "<option value=\"".$key."\"".$selected.">".$value."</option>";
					}
					$html .= "</select>";
					$html .= "</div>";
					
					if( $attributes['description'] ){
							$html .= "<span class=\"otw-form-hint\">".$attributes['description']."</span>";
					}
					$html .= "</div>";
				break;
		}
		
		return $html;
	}
	
	/** select with subfields
	 *
	 *  @param array
	 *
	 *  @return string
	 */
	public static function select_subfields( $attributes = array() ){
		
		$html = '';
		
		$parsed_attributes = self::parse_attributes( $attributes, 'select_input' );
		
		if( isset( $attributes['value_from'][ $attributes['id'] ] ) ){
			$parsed_attributes['value'] = $attributes['value_from'][ $attributes['id'] ];
		}
		
		switch( $parsed_attributes['format'] ){
		
			default:
					$html .= "<div class=\"otw-form-control\" data-name=\"".$attributes['name']."\">";
						
						$html .= "<div class=\"otw-form-mainfield\">";
							
							$html .= OTW_Form::select( 
									array(  'id' => $attributes['id'], 
										'name' => $attributes['name'], 
										'label' => $attributes['label'],
										'parse' => $attributes['parse'], 
										'class' => 'otw-form-select otw_with_subfield',
										'options' => $attributes['options'], 
										'value' => $parsed_attributes['value'],
										'format' => 'no_form_control')  );
							
						$html .= "</div>";
						
						foreach( $attributes['subfields'] as $subfield => $subfield_data ){
							
							$position = 'before_description';
							
							if( isset( $subfield_data['position'] ) ){
							
								$position = $subfield_data['position'];
							}
							
							if( $position == 'before_description' ){
							
								$html .= "<div id=\"".$attributes['name']."_".$subfield_data['option']."\" class=\"otw-form-subfield\">";
								
								$subfield_value = '';
								
								if( !empty( $attributes['value_from'][ $attributes['name']."_".$subfield ] ) ){
									$subfield_value = $attributes['value_from'][ $attributes['name']."_".$subfield ];
								}
								
								switch( $subfield_data['type'] ){
									
									case 'text_input':
											
											$html .= OTW_Form::text_input( array( 
												'id' => $attributes['name']."_".$subfield, 
												'name' => $attributes['name']."_".$subfield, 
												'label' => $subfield_data['label'],
												'description' => isset( $subfield_data['description'] )?$subfield_data['description']:'', 
												'value' => $subfield_value,
												'parse' => $attributes['parse'],
												'format' => 'no_form_control' ) );
										break;
								}
								$html .= "</div>";
							}
						}
						
						if( !empty( $attributes['description'] ) ){
							$html .= "<span class=\"otw-form-hint\">".$attributes['description']."</span>";
						}
						
						foreach( $attributes['subfields'] as $subfield => $subfield_data ){
							
							$position = 'before_description';
							
							if( isset( $subfield_data['position'] ) ){
							
								$position = $subfield_data['position'];
							}
							
							if( $position == 'after_description' ){
							
								$html .= "<div id=\"".$attributes['name']."_".$subfield_data['option']."\" class=\"otw-form-subfield\">";
								
								$subfield_value = '';
								
								if( !empty( $attributes['value_from'][ $attributes['name']."_".$subfield ] ) ){
									$subfield_value = $attributes['value_from'][ $attributes['name']."_".$subfield ];
								}
								
								switch( $subfield_data['type'] ){
									
									case 'text_input':
											
											$html .= OTW_Form::text_input( array( 
												'id' => $attributes['name']."_".$subfield, 
												'name' => $attributes['name']."_".$subfield, 
												'label' => $subfield_data['label'],
												'description' => isset( $subfield_data['description'] )?$subfield_data['description']:'', 
												'value' => $subfield_value,
												'parse' => $attributes['parse'],
												'format' => 'no_form_control' ) );
										break;
								}
								$html .= "</div>";
							}
						}
					$html .= "</div>";
				break;
		}
		
		return $html;
	}
	
	/** textarea
	 *
	 *  @param array
	 *
	 *  @return string
	 */
	public static function text_area( $attributes = array() ){
		
		$html = '';
		
		$attributes = self::parse_attributes( $attributes, 'text-area' );
		
		if( isset( $attributes['parse'][ $attributes['id'] ] ) ){
			$attributes['value'] = $attributes['parse'][ $attributes['id'] ];
		}
		
		
		switch( $attributes['format'] ){
		
			default:
					$html .= "<div class=\"otw-form-control\">";
					if( $attributes['label'] || $attributes['show_empty_label'] ){
						$html .= "<label".self::format_attribute( 'for', 'id', $attributes ).">".$attributes['label']."</label>";
					}
					$html .= "<textarea ".self::format_attributes( array('id','name','class','style'), array(), $attributes )." ".$attributes['extra'].">".otw_stripslashes( $attributes['value'] )."</textarea>";
					
					if( $attributes['description'] ){
							$html .= "<span class=\"otw-form-hint\">".$attributes['description']."</span>";
					}
					$html .= "</div>";
				break;
		}
		
		return $html;
	}
	
	/** html textarea
	 *
	 *  @param array
	 *
	 *  @return string
	 */
	public static function html_area( $attributes = array() ){
		
		$html = '';
		
		$attributes = self::parse_attributes( $attributes, 'html-area' );
		
		if( isset( $attributes['parse'][ $attributes['id'] ] ) ){
			$attributes['value'] = $attributes['parse'][ $attributes['id'] ];
		}
		
		
		switch( $attributes['format'] ){
		
			case 'tmce_holder':
					$html .= "<textarea ".self::format_attributes( array('id','name','class'), array(), $attributes )." ".$attributes['extra']." data-type=\"tmce\" style=\"display: none;\" >".otw_stripslashes( $attributes['value'] )."</textarea>";
					$html .= '<div id="'.$attributes['id'].'-holder" class="otw-html-area-holder"></div>';
				break;
			case 'tmce':
					$html .= "<div class=\"otw-tmce-form-control\" id=\"".$attributes['id']."-form-control\" style=\"display: none;\">";
					$html .= "<input type=\"hidden\" class=\"otw-html-area\" value=\"".$attributes['id']."\" />";
					
					if( $attributes['label'] || $attributes['show_empty_label'] ){
						$html .= "<label".self::format_attribute( 'for', 'id', $attributes ).">".$attributes['label']."</label>";
					}
					$settings = array();
					$settings['media_buttons'] = false;
					$settings['teeny'] = true;
					
					ob_start();
					wp_editor( otw_stripslashes( $attributes['value'] ), $attributes['id'], $settings );
					
					$html .= '<div>'.ob_get_contents().'</div>';
					ob_end_clean();
					
					if( $attributes['description'] ){
							$html .= "<span class=\"otw-form-hint\">".$attributes['description']."</span>";
					}
					$html .= "</div>";

				break;
			default:
					$html .= "<div class=\"otw-form-control\" id=\"".$attributes['id']."-form-control\">";
					$html .= "<input type=\"hidden\" class=\"otw-html-area\" value=\"".$attributes['id']."\" />";
					
					if( $attributes['label'] || $attributes['show_empty_label'] ){
						$html .= "<label".self::format_attribute( 'for', 'id', $attributes ).">".$attributes['label']."</label>";
					}
					$settings = array();
					$settings['media_buttons'] = false;
					
					ob_start();
					wp_editor( otw_stripslashes( $attributes['value'] ), $attributes['id'], $settings );
					
					$html .= '<div>'.ob_get_contents().'</div>';
					ob_end_clean();
					
					if( $attributes['description'] ){
							$html .= "<span class=\"otw-form-hint\">".$attributes['description']."</span>";
					}
					$html .= "</div>";
				break;
		}
		
		return $html;
	}
	
	/** input type text
	 *
	 *  @param array
	 *
	 *  @return string
	 */
	public static function text_input( $attributes = array() ){
		
		$html = '';
		
		$attributes = self::parse_attributes( $attributes, 'text-input' );
		
		if( isset( $attributes['parse'][ $attributes['id'] ] ) ){
			$attributes['value'] = $attributes['parse'][ $attributes['id'] ];
		}
		
		switch( $attributes['format'] ){
		
			case 'no_form_control':
					if( $attributes['label'] || $attributes['show_empty_label'] ){
						$html .= "<label".self::format_attribute( 'for', 'id', $attributes ).">".$attributes['label']."</label>";
					}
					$html .= "<input type=\"text\"".self::format_attributes( array('id','name','value','class','style','data-reload'), array(), $attributes, array(), 'text_input' )." ".$attributes['extra'].">";
					
					if( $attributes['description'] ){
							$html .= "<span class=\"otw-form-hint\">".$attributes['description']."</span>";
					}
				break;
			default:
					$html .= "<div class=\"otw-form-control\">";
					if( $attributes['label'] || $attributes['show_empty_label'] ){
						$html .= "<label".self::format_attribute( 'for', 'id', $attributes ).">".$attributes['label']."</label>";
					}
					$html .= "<input type=\"text\"".self::format_attributes( array('id','name','value','class','style','data-reload'), array(), $attributes, array(), 'text_input' )." ".$attributes['extra'].">";
					
					if( $attributes['description'] ){
							$html .= "<span class=\"otw-form-hint\">".$attributes['description']."</span>";
					}
					$html .= "</div>";
				break;
		}
		
		return $html;
	}
	
	/** datetimepicker
	 *
	 *  @param array
	 *
	 *  @return string
	 */
	public static function datetimepicker( $attributes = array() ){
		
		$html = '';
		
		$attributes = self::parse_attributes( $attributes, 'datetimepicker' );
		
		if( isset( $attributes['parse'][ $attributes['id'] ] ) ){
			$attributes['value'] = $attributes['parse'][ $attributes['id'] ];
		}
		
		switch( $attributes['format'] ){
		
			case 'from_to':
					if( isset( $attributes['parse'][ $attributes['id'].'_from' ] ) ){
						$attributes['value_from'] = $attributes['parse'][ $attributes['id'].'_from' ];
					}
					if( isset( $attributes['parse'][ $attributes['id'].'_to' ] ) ){
						$attributes['value_to'] = $attributes['parse'][ $attributes['id'].'_to' ];
					}
					
					$html .= "<div class=\"otw-form-control otw-form-datetimepicker\">";
					if( $attributes['label'] || $attributes['show_empty_label'] ){
						$html .= "<label".self::format_attribute( 'for', 'id_from', $attributes ).">".$attributes['label']."</label>";
					}
					$html .= "<input type=\"text\"".self::format_attributes( array( 'id', 'name', 'value', 'class', 'style'), array('id_from','name_from','value_from','class_from','style_from'), $attributes, array(), 'datetimepicker' )." ".$attributes['extra'].">";
					$html .= "<input type=\"text\"".self::format_attributes( array( 'id', 'name', 'value', 'class', 'style'), array('id_to','name_to','value_to','class_to','style_to'), $attributes, array(), 'datetimepicker' )." ".$attributes['extra'].">";
					
					if( $attributes['description'] ){
							$html .= "<span class=\"otw-form-hint\">".$attributes['description']."</span>";
					}
					$html .= "</div>";
				break;
			default:
					$html .= "<div class=\"otw-form-control otw-form-datetimepicker\">";
					if( $attributes['label'] || $attributes['show_empty_label'] ){
						$html .= "<label".self::format_attribute( 'for', 'id', $attributes ).">".$attributes['label']."</label>";
					}
					$html .= "<input type=\"text\"".self::format_attributes( array('id','name','value','class','style'), array(), $attributes, array(), 'text_input' )." ".$attributes['extra'].">";
					
					if( $attributes['description'] ){
							$html .= "<span class=\"otw-form-hint\">".$attributes['description']."</span>";
					}
					$html .= "</div>";
				break;
		}
		
		wp_enqueue_style('datetimepicker', self::$static_component_url.'css/datetimepicker.css' ,array(), self::$static_css_version);
		wp_enqueue_script('datetimepicker', self::$static_component_url.'js/datetimepicker.js' , array( 'jquery' ), self::$static_js_version);
		
		return $html;
	}
	
	/** input type checkbox
	 *
	 *  @param array
	 *
	 *  @return string
	 */
	public static function checkbox( $attributes = array() ){
		
		$html = '';
		
		$attributes = self::parse_attributes( $attributes, 'checkbox' );
		
		if( isset( $attributes['parse'][ $attributes['id'] ] ) ){
			
			if( $attributes['value'] == $attributes['parse'][ $attributes['id'] ] ){
				$attributes['checked'] = 'checked';
			}
			
		}elseif( isset( $attributes['data-id'] ) && ( isset( $attributes['parse'][ $attributes['data-id'] ] ) ) ){
		
			if( $attributes['value'] == $attributes['parse'][ $attributes['data-id'] ] ){
				$attributes['checked'] = 'checked';
			}
		}
		
		switch( $attributes['format'] ){
		
			case 'otw-form-control-checkbox-group':
					$html .= "<div class=\"otw-form-control-checkbox-group\">";
					
					$html .= "<input type=\"checkbox\"".self::format_attributes( array('id','name','value','class','style','checked'), array(), $attributes, array(), 'checkbox' )." ".$attributes['extra'].">";
					
					if( $attributes['label'] || $attributes['show_empty_label'] ){
						$html .= "<label".self::format_attribute( 'for', 'id', $attributes ).">".$attributes['label']."</label>";
					}
					
					if( $attributes['description'] ){
							$html .= "<span class=\"otw-form-hint\">".$attributes['description']."</span>";
					}
					$html .= "</div>";
				break;
			default:
					$html .= "<div class=\"otw-form-control-checkbox\">";
					
					$html .= "<input type=\"checkbox\"".self::format_attributes( array('id','name','value','class','style','checked'), array(), $attributes, array(), 'checkbox' )." ".$attributes['extra'].">";
					
					if( $attributes['label'] || $attributes['show_empty_label'] ){
						$html .= "<label".self::format_attribute( 'for', 'id', $attributes ).">".$attributes['label']."</label>";
					}
					if( $attributes['description'] ){
							$html .= "<span class=\"otw-form-hint\">".$attributes['description']."</span>";
					}
					$html .= "</div>";
				break;
		}
		
		return $html;
	}
	
	/** uploader
	 *
	 *  @param array
	 *
	 *  @return string
	 */
	public static function uploader( $attributes = array() ){
	
		if( otw_comprare_blog_version( '3.4.1' ) >= 0 ){
			
			if( isset( $attributes['alternative_description'] ) ){
				$attributes['description'] = $attributes['alternative_description'];
			}
			return self::text_input( $attributes );
		}
		
		$html = '';
		
		$attributes = self::parse_attributes( $attributes, 'uploader' );
		
		if( isset( $attributes['parse'][ $attributes['id'] ] ) ){
			$attributes['value'] = $attributes['parse'][ $attributes['id'] ];
		}
		
		switch( $attributes['format'] ){
		
			default:
					$html .= "<div class=\"otw-form-control\">";
					
					if( $attributes['label'] || $attributes['show_empty_label'] ){
						$html .= "<label".self::format_attribute( 'for', 'id', $attributes ).">".$attributes['label']."</label>";
					}
					$html .= '<div class="wp-media-buttons" ><a title="'.esc_attr( $attributes['label'] ).'" data-editor="'.esc_attr( $attributes['id'] ).'" class="button otw-form-uploader-control insert-media add_media" href="#"><span class="wp-media-buttons-icon"></span></a>';
					$html .= "<input type=\"text\"".self::format_attributes( array('id','name','value','class','style','maxlength'), array(), $attributes )." ".$attributes['extra'].">";
					$html .= "</div>";
					
					if( $attributes['description'] ){
						$html .= "<span class=\"otw-form-hint\">".$attributes['description']."</span>";
					}
					$html .= "</div>";
				break;
		}
		
		return $html;
	}
	
	/**
	 * Active / Inactive elements. Dragable interface
	 *
	 *  @param array
	 *
	 * @return string
	 */
	public static function active_elements( $attributes = array() ){
		
		$html = '';
		
		$attributes = self::parse_attributes( $attributes, 'acitve_elements' );
		
		if( isset( $attributes['parse'][ $attributes['id'] ] ) ){
			$attributes['value'] = $attributes['parse'][ $attributes['id'] ];
		}
		
		if( !isset( $attributes['label_active_elements'] ) ){
			$attributes['label_active_elements'] = '';
		}
		
		if( !isset( $attributes['label_inactive_elements'] ) ){
			$attributes['label_inactive_elements'] = '';
		}
		
		if( !isset( $attributes['items'] ) ){
			$attributes['items'] = array();
		}
		
		switch( $attributes['format'] ){
		
			default:
					if( !isset( $attributes['height'] ) ){
						$elements_height = ( count( $attributes['items'] ) * 21 ) + ( count( $attributes['items'] ) * 6 );
					}else{
						$elements_height = $attributes['height'];
					}
					
					$html .= "<div class=\"otw-form-control otw-ac-elements\">";
					$html .= "\n<div class=\"otw-ac-active-elements\">";
					$html .= "\n<label>".$attributes['label_active_elements']."</label>";
					$html .= "\n<input type=\"hidden\"".self::format_attributes( array('id','name','value'), array(), $attributes )." class=\"otw-ac-items\">";
					$html .= "\n<ul class=\"otw-form-items-box js-ac-items-active\" style=\"height: ".$elements_height."px;\">";
					
					$html .= "\n</ul>";
					$html .= "\n</div>";
					$html .= "\n<div class=\"otw-ac-inactive-elements\">";
					$html .= "\n<label>".$attributes['label_inactive_elements']."</label>";
					$html .= "\n<ul class=\"otw-form-items-box js-ac-items-inactive\" style=\"height: ".$elements_height."px;\">";
						foreach( $attributes['items'] as $item_key => $item_name ){
							$html .= "<li data-value=\"".$item_key."\" class=\"otw-form-ac-item\">".$item_name."</li>";
						}
					$html .= "\n</ul>";
					$html .= "\n</div>";
					if( $attributes['description'] ){
						$html .= "<span class=\"otw-form-hint\">".$attributes['description']."</span>";
					}
					$html .= "</div>";
				break;
		}
		
		return $html;
	}
	
	/** color picker
	 *
	 *  @param array
	 *
	 *  @return string
	 */
	public static function color_picker( $attributes = array() ){
		
		global $paletteList;
		
		$html = '';
		
		$attributes = self::parse_attributes( $attributes, 'color-picker' );
		
		if( isset( $attributes['parse'][ $attributes['id'] ] ) ){
			$attributes['value'] = $attributes['parse'][ $attributes['id'] ];
		}
		
		if( !isset( $attributes['maxlength'] ) ){
			$attributes['maxlength'] = 7;
		}
		
		if( !strlen( $attributes['value'] ) ){
			$attributes['value'] = '';
		}
		
		switch( $attributes['format'] ){
			case 'with_selector':
					$html .= "<div class=\"otw-form-control\">";
					if ($attributes['label'] || $attributes['show_empty_label']) {
						$html .= "<label" . self::format_attribute('for', 'id', $attributes) . ">" . $attributes['label'] . "</label>";
					}
					
					$html .= '<select name="otw-color-palette-' . $attributes['name'] . '" class="otw-bmp-color-palette" data-target-element="' . $attributes['id'] . '">';
					$html .= '<option value="">---</option>';
					foreach ($paletteList as $palette) {
						$html .= '<optgroup label="' . $palette['label'] . '">';
							foreach ($palette['items'] as $color => $colorName) {
								$selected = '';
								if ($color === $attributes['value']) {
									$selected = ' selected="selected"';
								}
								$html .= '<option value="' . $color . '"' . $selected .'>' . $colorName . '</option>';
							}
						$html .= '</optgroup>';
					}
					$html .= '</select>';
					
					$html .= "<div class=\"otw-marker-colourpicker-control\">";
					$html .= "<div class=\"otw-color-selector\">";
					$html .= "<div class=\"otw-color-holder\" style=\"background-color: " . $attributes['value'] . "\"></div>";
					$html .= "</div>";
					
					if( isset( $attributes['value'] ) && !preg_match( "/^\#([a-z0-9]{6})$/", $attributes['value'] ) ){
						$attributes['value'] = '';
					}
					
					$html .= "<input type=\"text\"" . self::format_attributes(array( 'id', 'name', 'value', 'class', 'style','maxlength', 'data-target', 'data-property', 'data-group', 'autocomplete' ), array(), $attributes) . " " . $attributes['extra'] . ">";
					$html .= "</div>";
					
					if ($attributes['description']) {
						$html .= "<span class=\"otw-form-hint\">" . $attributes['description'] . "</span>";
					}
					$html .= "</div>";
				break;
			default:
					$html .= "<div class=\"otw-form-control\">";
					if( $attributes['label'] || $attributes['show_empty_label'] ){
						$html .= "<label".self::format_attribute( 'for', 'id', $attributes ).">".$attributes['label']."</label>";
					}
					
					$html .= "<div class=\"otw-marker-colourpicker-control\">";
					$html .= "<div class=\"otw-color-selector\">";
					$html .= "<div style=\"background-color: ".$attributes['value']."\"></div>";
					$html .= "</div>";
					$html .= "<input type=\"text\"".self::format_attributes( array('id','name','value','class','style','maxlength'), array(), $attributes )." ".$attributes['extra'].">";
					$html .= "</div>";
					
					if( $attributes['description'] ){
						$html .= "<span class=\"otw-form-hint\">".$attributes['description']."</span>";
					}
					$html .= "</div>";
				break;
		}
		
		return $html;
	}

	
	
	/**
	 * parse attributes
	 *
	 *  @param array
	 *
	 *  @return array
	 */
	public static function parse_attributes( $attributes, $type ){
	
		if( !isset( $attributes['format'] ) ){
			$attributes['format'] = '';
		}
		if( !isset( $attributes['id'] ) ){
			$attributes['id'] = '';
		}
		if( !isset( $attributes['name'] ) ){
			$attributes['name'] = '';
		}
		if( !isset( $attributes['show_empty_label'] ) ){
			$attributes['show_empty_label'] = false;
		}
		if( !isset( $attributes['class'] ) ){
			switch( $type ){
				case 'text-input':
				case 'select':
				case 'color-picker':
				case 'uploader':
						$attributes['class'] = 'otw-form-'.$type;
					break;
				case 'dynamic-select':
						$attributes['class'] = 'otw-form-'.$type;
						
						if( strlen( $attributes['format'] ) ){
							$attributes['class'] .= ' '.$attributes['format'];
						}
					break;
				default:
						$attributes['class'] = '';
					break;
			}
		}
		if( !isset( $attributes['style'] ) ){
			$attributes['style'] = '';
		}
		if( !isset( $attributes['extra'] ) ){
			$attributes['extra'] = '';
		}
		if( !isset( $attributes['label'] ) ){
			$attributes['label'] = '';
		}
		if( !isset( $attributes['value'] ) ){
			$attributes['value'] = '';
		}
		if( !isset( $attributes['options'] ) || !is_array( $attributes['options'] ) ){
			$attributes['options'] = array();
		}
		if( !isset( $attributes['description'] ) ){
			$attributes['description'] = '';
		}
		if( !isset( $attributes['parse'] ) ){
			$attributes['parse'] = '';
		}
		
		return $attributes;
	}
	
	/**
	 *  format attribute
	 *  
	 *  @param string name
	 *
	 *  @param string key
	 *
	 *  @param array with attributes
	 *
	 *  @param boolean create attribute if no value
	 *
	 *  @return string
	 */
	public static function format_attribute( $attribute_name, $attribute_key, $attributes, $show_empty = false, $element_type = '' ){
	
		if( isset( $attributes[ $attribute_key ] ) && is_array( $attributes[ $attribute_key ] ) ){
			if( in_array( $element_type, array( 'text_input' ) ) && $attribute_key == 'value' ){
				return ' '.$attribute_name.'="'.otw_htmlentities( otw_stripslashes( implode( ',', $attributes[ $attribute_key ] ) ) ).'"';
			}else{
				return ' '.$attribute_name.'="'.implode( ',', $attributes[ $attribute_key ] ).'"';
			}
		}elseif( isset( $attributes[ $attribute_key ] ) && strlen( trim( $attributes[ $attribute_key ] ) ) ){
			if( in_array( $element_type, array( 'text_input' ) ) && $attribute_key == 'value' ){
				return ' '.$attribute_name.'="'.otw_htmlentities( otw_stripslashes( $attributes[ $attribute_key ] ) ).'"';
			}else{
				return ' '.$attribute_name.'="'.$attributes[ $attribute_key ].'"';
			}
		}elseif( $show_empty ){
			return ' '.$attribute_name.'=""';
		}
	}
	
	/**
	 *  format attributes
	 *  
	 *  @param string array
	 *
	 *  @param string array
	 *
	 *  @param array with attributes
	 *
	 *  @param array create attribute if no value
	 *
	 *  @return string
	 */
	public static function format_attributes( $attribute_names, $attribute_keys, $attributes, $show_empty = array(), $element_type = '' ){
	    
		$html = '';
		foreach( $attribute_names as $a_key => $name ){
		
			$key = $name;
			if( isset( $attribute_keys[ $a_key ] ) ){
				$key = $attribute_keys[ $a_key ];
			}
			$empty = false;
			if( isset( $show_empty[ $a_key ] ) ){
				$empty =$show_empty[ $a_key ];
			}
			$html .= self::format_attribute( $name, $key, $attributes, $empty, $element_type );
		}
		return $html;
	}
	
	/**
	 * display message
	 *
	 *  @param string
	 *
	 *  @param string
	 *
	 *  @param string
	 *
	 *  @return string
	 */
	public static function message( $type, $title, $message ){
	
		$html = '';
		switch( $type ){
			case 'red_text':
					$html .= '<p class="otw-'.esc_attr( $type ).'">';
					$html .= $message;
					$html .= '</p>';
				break;
			case 'error':
					$html = '<div class="otw-sc-message closable-message';
					$html .= ' otw-'.$type;
					$html .= '">';
					$html .= '<p>';
					$html .= '<i class="general foundicon-remove"></i>';
					$html .= '<strong>'.$title.'</strong>';
					$html .= ' '.$message;
					$html .= '</p>';
					$html .= '<div class="close-message">x</div>';
					$html .= '</div>';
				break;
			case 'success':
					$html = '<div class="otw-sc-message closable-message';
					$html .= ' otw-'.$type;
					$html .= '">';
					$html .= '<p>';
					$html .= '<i class="general foundicon-checkmark"></i>';
					$html .= '<strong>'.$title.'</strong>';
					$html .= ' '.$message;
					$html .= '</p>';
					$html .= '<div class="close-message">x</div>';
					$html .= '</div>';
				break;
			case 'warning':
					$html = '<div class="otw-sc-message closable-message';
					$html .= ' otw-'.$type;
					$html .= '">';
					$html .= '<p>';
					$html .= '<i class="general foundicon-error"></i>';
					$html .= '<strong>'.$title.'</strong>';
					$html .= ' '.$message;
					$html .= '</p>';
					$html .= '<div class="close-message">x</div>';
					$html .= '</div>';
				break;
			case 'tip':
					$html = '<div class="otw-sc-message closable-message';
					$html .= ' otw-'.$type;
					$html .= '">';
					$html .= '<p>';
					$html .= '<i class="general foundicon-idea"></i>';
					$html .= '<strong>'.$title.'</strong>';
					$html .= ' '.$message;
					$html .= '</p>';
					$html .= '<div class="close-message">x</div>';
					$html .= '</div>';
				break;
			case 'neutral':
					$html = '<div class="otw-sc-message closable-message';
					$html .= ' otw-'.$type;
					$html .= '">';
					$html .= '<p>';
					$html .= '<i class="general foundicon-tools"></i>';
					$html .= '<strong>'.$title.'</strong>';
					$html .= ' '.$message;
					$html .= '</p>';
					$html .= '<div class="close-message">x</div>';
					$html .= '</div>';
				break;
				
		}
		
		
		return $html;
	}
	
	/** slider
	 *
	 * @param array
	 *
	 * @return string
	 */
	public static function slider($attributes = array() ){
		
		$html = '';
		
		$attributes = self::parse_attributes($attributes, 'slider');
		
		switch ($attributes['format']) {
			default:
				$sliderData = $attributes['slider'];
				$html .= '<div class="otw-slider-wrapper" style="width: ' . $sliderData['width'] . ';';
				if (@$attributes['auto'] === true) {
					$html .= ' display: none;';
				}
				$html .= '">';
				
				$html .= '<div id="' . $sliderData['id'] . '" ';
				$html .= 'class="otw-slider-slide" ';
				$html .= 'data-linked-element="' . $sliderData['linkedElement'] . '" ';
				$html .= 'data-slider-min="' . $sliderData['min'] . '" ';
				$html .= 'data-slider-max="' . $sliderData['max'] . '" ';
				$html .= 'data-slider-step="' . $sliderData['step'] . '">';
				$html .= "</div>";
				$html .= '<div class="otw-slider-preview-value">';
				if ($attributes['label'] || $attributes['show_empty_label']) {
					$html .= $attributes['label'] . ": ";
				}
				$html .= "<span>" . $attributes['value'] . "</span>" . $sliderData['measures'];
				$html .= "</div>";
				
				if (!isset($sliderData['section'])) {
					$sliderData['section'] = 'button_style';
				}
				$html .= '<input id="' . $sliderData['linkedElement'] . '" ';
				
				$name =  $sliderData['section'] . '[' . $attributes['property'] . ']';
				if (isset($attributes['name']) && !empty($attributes['name'])) {
					$name = $attributes['name'];
				}
				$html .= 'name="' . $name . '" ';
				$html .= 'class="slider-value" ';
				$html .= 'type="hidden" ';
				
				if (@$attributes['auto'] === true) {
					$html .= 'disabled="disabled" ';
				} else {
					$html .= 'value="' . $attributes['value'] . '" ';
				}
				$html .= 'data-target="' . $sliderData['target'] . '" ';
				$html .= 'data-property="' . $attributes['property'] . '" ';
				
				if (isset($attributes['group'])) {
					$html .= 'data-group ="' . $attributes['group'] . '" ';
				}
				$html .= "/>";
				$html .= "<div class=\"otw-form-control\">";
				
				if ($attributes['description']) {
					$html .= "<span class=\"otw-form-hint\">" . $attributes['description'] . "</span>";
				}
				$html .= '</div>';
				$html .= '</div>';
			break;
		}
		return $html;
	}
}
