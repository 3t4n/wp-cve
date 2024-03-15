<?php
if (! defined ( 'ABSPATH' )) {
	die ();
} // Cannot access pages directly.
/**
 *
 * Field: Sorter
 *
 * @since 1.0.0
 * @version 1.0.0
 *         
 */
class WPSFramework_Option_Sorter extends WPSFramework_Options {
	public function __construct($field, $value = '', $unique = '') {
		parent::__construct ( $field, $value, $unique );
	}
    
    public function get_element_values(){
        $defaults = $this->field['default'];
        if(empty($this->element_value ())){
            return $defaults;
        }
        
        $saved = $this->element_value ();
        
        foreach($defaults['enabled'] as $i => $val){
            if(isset($saved['enabled'][$i]) === false &&  isset($saved['disabled'][$i]) === false){
                $saved['disabled'][$i] = $val;
            }
        }
        
        
        foreach($defaults['disabled'] as $i => $val){
            if(isset($saved['enabled'][$i]) === false &&  isset($saved['disabled'][$i]) === false){
                $saved['disabled'][$i] = $val;
            }
        }
        return $saved;
    }
    
	public function output() {
		echo $this->element_before ();
		$value = $this->get_element_values();
		$enabled = (! empty ( $value ['enabled'] )) ? $value ['enabled'] : array ();
		$disabled = (! empty ( $value ['disabled'] )) ? $value ['disabled'] : array ();
		$enabled_title = (isset ( $this->field ['enabled_title'] )) ? $this->field ['enabled_title'] : esc_html__ ( 'Enabled Modules', 'wpsf-framework' );
		$disabled_title = (isset ( $this->field ['disabled_title'] )) ? $this->field ['disabled_title'] : esc_html__ ( 'Disabled Modules', 'wpsf-framework' );
		
		echo '<div class="wpsf-modules">';
		echo '<h3>' . $enabled_title . '</h3>';
		echo '<ul class="wpsf-enabled">';
		if (! empty ( $enabled )) {
			foreach ( $enabled as $en_id => $en_name ) {
				echo '<li><input type="hidden" name="' . $this->element_name ( '[enabled][' . $en_id . ']' ) . '" value="' . $en_name . '"/><label>' . $en_name . '</label></li>';
			}
		}
		echo '</ul>';
		echo '</div>';
		
		echo '<div class="wpsf-modules">';
		echo '<h3>' . $disabled_title . '</h3>';
		echo '<ul class="wpsf-disabled">';
		if (! empty ( $disabled )) {
			foreach ( $disabled as $dis_id => $dis_name ) {
				echo '<li><input type="hidden" name="' . $this->element_name ( '[disabled][' . $dis_id . ']' ) . '" value="' . $dis_name . '"/><label>' . $dis_name . '</label></li>';
			}
		}
		echo '</ul>';
		echo '</div>';
		echo '<div class="clear"></div>';
		
		echo $this->element_after ();
	}
}
