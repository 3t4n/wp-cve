<?php
if (! defined ( 'ABSPATH' )) {
	die ();
} // Cannot access pages directly.
/**
 *
 * Field: Content
 *
 * @since 1.0.0
 * @version 1.0.0
 *         
 */
class WPSFramework_Option_content extends WPSFramework_Options {
	public function __construct($field, $value = '', $unique = '') {
		parent::__construct ( $field, $value, $unique );
	}
	public function output() {
		echo $this->element_before ();
        
        if(empty($this->field ['content']) && isset($this->field ['callback_hook'])){
            echo do_action($this->field ['callback_hook'],$this);
        } else {
            echo $this->field ['content'];
        }
		echo $this->element_after ();
	}
}
