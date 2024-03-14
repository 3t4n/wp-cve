<?php
class OTW_Shortcode_Widget extends WP_Widget{
	
	/**
	  * Labes
	  */
	public $labels = array();
	
	public static $shortcode_names = array();
	
	function __construct(){
		
		global $otw_components;
		
		$widget_ops = array(
			'customize_selective_refresh' => true,
			'show_instance_in_rest'       => true
		);
		
		parent::__construct( 'otw_shortcode_widget', esc_html__('OTW Shortcode Widget', 'otw-shortcode-widget' ), $widget_ops );
	}
	
	/**
	 * Admin backend form
	 */
	public function form( $instance ){
		
		global $otw_components;
		
		$output = '';
		
		$otw_sw_code = '';
		$otw_sw_code_object = false;
		
		if( isset( $instance['otw-sw-code'] ) ){
			$otw_sw_code = $instance['otw-sw-code'];
			
			$otw_sw_code_object = json_decode( $otw_sw_code );
			
			if( !isset( $otw_sw_code_object->id ) || empty( $otw_sw_code_object->id ) || !isset( $otw_sw_code_object->shortcodes ) || !count( $otw_sw_code_object->shortcodes ) ){
				$otw_sw_code_object = false;
			}
		}
		
		$shortcode_names = array();
		
		$output .= "\n<div class=\"otw-ws-content\">";
			$output .= "\n<div id=\"".$this->get_field_id( 'otw-sw-controls' )."\" ".( ( $otw_sw_code_object ) ? ' style="display:none;" ':'' )." >";
				$output .= "\n<label for=\"".$this->get_field_id( 'otw-shortcode-type' )."\">".__( 'Select Shortcode', 'otw-shortcode-widget' )."</label><br />";
				$output .= "\n<select id=\"".$this->get_field_id( 'otw-shortcode-type' )."\">";
				if( isset( $otw_components['loaded'] ) && isset( $otw_components['loaded']['otw_shortcode'] ) ){
					
					foreach( $otw_components['loaded']['otw_shortcode'] as $otw_shortcode_component ){
						
						foreach( $otw_shortcode_component['objects'][1]->shortcodes as $shortcode_key => $shortcode ){
						
							if( !is_array( $shortcode['children'] ) ){
							
								if( !preg_match( "/^widget_shortcode/", $shortcode_key ) ){
									$output .= "\n<option value=\"".$shortcode_key."\" >".$shortcode['title']."</option>";
									
									$shortcode_names[ $shortcode_key ] = $shortcode['title'];
								}
							}
						}
						break;
					}
				}
				
				$output .= "\n</select>";
				$output .= "<br /><div><button class=\"button button-primary\" id=\"".$this->get_field_id( 'otw-sw-add-shortcode' )."\" onclick=\"otw_sw_add_shortcode( this ); return false;\">".__( 'Add', 'otw-shortcode-widget' )."</button></div>";
			$output .= "\n</div>";
			$output .= "\n<div id=\"".$this->get_field_id( 'otw-sw-selected-shortcodes' )."\">";
			
			if( $otw_sw_code_object ){
				
				
				foreach( $otw_sw_code_object->shortcodes as $cS => $saved_shortcode ){
					
					$output .= "<div class=\"otw-sw-item\">";
					
					$output .= "<div class=\"otw-sw-header\">";
					
					$output .= "<a href=\"javascript:;\" class=\"otw-sw-remove\" onClick=\"otw_sw_delete( '". $otw_sw_code_object->id ."', ".$cS.");\"><span>".__( 'Remove', 'otw-shortcode-widget' )."</span></a>";
					$output .= "<a href=\"javascript:;\" class=\"otw-sw-edit\" onClick=\"otw_sw_settings( '". $otw_sw_code_object->id ."', ".$cS.");\"><span>".__( 'Settings', 'otw-shortcode-widget' )."</span></a>";
					
					$output .= "</div>";
					
					$output .= "<div class=\"otw-sw-body\">";
					
					if( isset( $shortcode_names[ $saved_shortcode->type ] ) ){
					
						$output .= $shortcode_names[ $saved_shortcode->type ];
					
					}else{
						$output .= "Unknown OTW shortcode";
					}
					
					$output .= "</div>";
				}
			}
			
			$output .= "\n</div>";
		
		$output .= "\n</div>";
		
		$output .= "\n<input type=\"hidden\" id=\"".$this->get_field_id( 'otw-sw-code' )."\" value=\"".otw_htmlentities( $otw_sw_code )."\" name=\"".$this->get_field_name( 'otw-sw-code' )."\"/>";
		$output .= "\n<script type=\"text/javascript\">";
		$output .= "\notw_sw_dislpay_code('".$this->get_field_id( 'otw-sw-code' )."')";
		$output .= "\n</script>";
		echo $output;
	}
	
	public function update( $new_instance, $old_instance ){
		return $new_instance;
	}
	
	public function widget( $args, $instance ){
		
		$output = '';
		
		if( isset( $instance['otw-sw-code'] ) ){
			
			$otw_sw_object = json_decode( $instance['otw-sw-code'] );
			
			if( isset( $otw_sw_object->shortcodes ) ){
				
				foreach( $otw_sw_object->shortcodes as $shortcode_object ){
				
					if( is_admin() ){
						$output .= 'OTW Shortcode Widget ( '.$this->get_shortcode_name( $shortcode_object->type ).' )';
						
					}else{
						if( isset( $shortcode_object->shortcode ) && isset( $shortcode_object->shortcode->shortcode_code ) ){
							$output .= do_shortcode( $shortcode_object->shortcode->shortcode_code );
						}
					}
				}
			}
		}
		
		echo $output;
	}
	
	
	public function get_shortcode_name( $shortcode_type )
	{
		if( !count( self::$shortcode_names ) ){
			
			global $otw_components;
			
			if( isset( $otw_components['loaded'] ) && isset( $otw_components['loaded']['otw_shortcode'] ) ){
				
				foreach( $otw_components['loaded']['otw_shortcode'] as $otw_shortcode_component ){
				
					foreach( $otw_shortcode_component['objects'][1]->shortcodes as $shortcode_key => $shortcode ){
					
						if( !is_array( $shortcode['children'] ) ){
						
							self::$shortcode_names[ $shortcode_key ] = $shortcode['title'];
						}
					}
				}
			}
		}
		
		if( isset( self::$shortcode_names[ $shortcode_type ] ) ){
			return self::$shortcode_names[ $shortcode_type ];
		}
		
		return 'N/A';
	}
}