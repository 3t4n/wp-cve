<?php

/* --------------------------------------------------------- */
/* !Return a re-formatted id - 2.0.13 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings_id') ) {
function mtphr_galleries_settings_id( $id ) {
	
	$id = preg_replace( '%\[%', '_', $id );
	$id = preg_replace( '%\]\[%', '_', $id );
	$id = preg_replace( '%\]%', '', $id );
	
	return $id;
}
}


/* --------------------------------------------------------- */
/* !Number - 2.0.13 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings_number') ) {
function mtphr_galleries_settings_number( $args=array() ) {

	if( isset($args['name']) ) {
		
		$name = $args['name'];
		$id = isset($args['id']) ? $args['id'] : mtphr_galleries_settings_id($name);
		$value = isset($args['value']) ? $args['value'] : '';
		$width = isset($args['width']) ? intval($args['width']) : '80';
		$before = isset($args['before']) ? $args['before'].' ' : '';
		$after = isset($args['after']) ? ' '.$args['after'] : '';
		
		echo '<div id="'.$id.'">';
			echo '<label>'.$before.'<input type="number" name="'.$name.'" value="'.$value.'" style="width:'.$width.'px" />'.$after.'</label>';
		echo '</div>';

	} else {
		echo __('Missing required data', 'mtphr-galleries');
	}
}
}


/* --------------------------------------------------------- */
/* !Select - 2.0.13 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings_select') ) {
function mtphr_galleries_settings_select( $args=array() ) {

	if( isset($args['name']) ) {
		
		$name = $args['name'];
		$id = isset($args['id']) ? $args['id'] : mtphr_galleries_settings_id($name);
		$value = isset($args['value']) ? $args['value'] : '';
		$options = isset($args['options']) ? $args['options'] : '';
		
		echo '<div id="'.$id.'">';
			echo '<select name="'.$name.'">';
				if( is_array($options) && count($options) > 0 ) {
					foreach( $options as $i=>$option ) {
						echo '<option value="'.$i.'" '.selected($i, $value, false).'>'.$option.'</option>';
					}
				}
			echo '</select>';
		echo '</div>';

	} else {
		echo __('Missing required data', 'mtphr-galleries');
	}
}
}



/* --------------------------------------------------------- */
/* !Textarea - 2.0.13 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings_textarea') ) {
function mtphr_galleries_settings_textarea( $args=array() ) {

	if( isset($args['name']) ) {
		
		$name = $args['name'];
		$id = isset($args['id']) ? $args['id'] : mtphr_galleries_settings_id($name);
		$value = isset($args['value']) ? $args['value'] : '';
		$cols = isset($args['cols']) ? $args['cols'] : 60;
		$rows = isset($args['rows']) ? $args['rows'] : 4;
		
		echo '<div id="'.$id.'">';
			echo '<textarea name="'.$name.'" cols="'.$cols.'" rows="'.$rows.'">'.$value.'</textarea>';
		echo '</div>';

	} else {
		echo __('Missing required data', 'mtphr-galleries');
	}
}
}


/* --------------------------------------------------------- */
/* !Text - 2.0.13 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings_text') ) {
function mtphr_galleries_settings_text( $args=array() ) {

	if( isset($args['name']) ) {
		
		$name = $args['name'];
		$id = isset($args['id']) ? $args['id'] : mtphr_galleries_settings_id($name);
		$value = isset($args['value']) ? $args['value'] : '';
		$width = isset($args['width']) ? ' style="width:'.$args['width'].';"' : '';
		$before = isset($args['before']) ? $args['before'].' ' : '';
		$after = isset($args['after']) ? ' '.$args['after'] : '';
		
		echo '<div id="'.$id.'">';
			echo '<label>'.$before.'<input type="text" name="'.$name.'" value="'.$value.'"'.$width.' />'.$after.'</label>';
		echo '</div>';

	} else {
		echo __('Missing required data', 'mtphr-galleries');
	}
}
}


/* --------------------------------------------------------- */
/* !Codemirror - 2.0.13 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings_codemirror') ) {
function mtphr_galleries_settings_codemirror( $args=array() ) {

	if( isset($args['name']) && isset($args['modes']) ) {
		
		$name = $args['name'];
		$id = isset($args['id']) ? $args['id'] : mtphr_galleries_settings_id($name);
		$value = isset($args['value']) ? $args['value'] : '';
		$cols = isset($args['cols']) ? $args['cols'] : 60;
		$rows = isset($args['rows']) ? $args['rows'] : 4;		
		$modes = isset($args['modes']) ? $args['modes'] : '';
		
		$mode_classes = 'mtphr-galleries-codemirror';
		if( is_array($modes) && count($modes) > 0 ) {
			foreach( $modes as $i=>$mode ) {
				$mode_classes .= ' mtphr-galleries-codemirror-'.$mode;
			}
		}
		
		echo '<div id="'.$id.'">';
			echo '<div class="'.$mode_classes.'">';
				echo '<textarea name="'.$name.'" cols="'.$cols.'" rows="'.$rows.'">'.$value.'</textarea>';
			echo '</div>';		
		echo '</div>';

	} else {
		echo __('Missing required data', 'mtphr-galleries');
	}
}
}


/* --------------------------------------------------------- */
/* !Checkbox - 2.0.13 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings_checkbox') ) {
function mtphr_galleries_settings_checkbox( $args=array() ) {

	if( isset($args['name']) ) {
		
		$name = $args['name'];
		$id = isset($args['id']) ? $args['id'] : mtphr_galleries_settings_id($name);
		$value = isset($args['value']) ? $args['value'] : '';
		$label = isset($args['label']) ? $args['label'] : '';
		
		echo '<div id="'.$id.'">';
			echo '<label><input type="checkbox" name="'.$name.'" value="on" '.checked('on', $value, false).' /> '.$label.'</label>';
		echo '</div>';

	} else {
		echo __('Missing required data', 'mtphr-galleries');
	}
}
}


/* --------------------------------------------------------- */
/* !Radio buttons - 2.0.13 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings_radio_buttons') ) {
function mtphr_galleries_settings_radio_buttons( $args=array() ) {

	if( isset($args['name']) && isset($args['options']) ) {
		
		$name = $args['name'];
		$id = isset($args['id']) ? $args['id'] : mtphr_galleries_settings_id($name);
		$value = isset($args['value']) ? $args['value'] : '';
		$options = isset($args['options']) ? $args['options'] : '';
		
		echo '<div id="'.$id.'">';
			if( is_array($options) && count($options) > 0 ) {
				foreach( $options as $i=>$option ) {
					echo '<label style="margin-right:20px;"><input type="radio" name="'.$name.'" value="'.$i.'" '.checked($i, $value, false).' /> '.$option.'</label>';
				}
			}
		echo '</div>';

	} else {
		echo __('Missing required data', 'mtphr-galleries');
	}
}
}


/* --------------------------------------------------------- */
/* !Rotation type - 2.0.13 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings_rotation_type') ) {
function mtphr_galleries_settings_rotation_type( $args=array() ) {

	if( isset($args['name']) ) {
		
		$name = $args['name'];
		$id = isset($args['id']) ? $args['id'] : mtphr_galleries_settings_id($name);
		$value = isset($args['value']) ? $args['value'] : '';
		
		$name_reverse = isset($args['name_reverse']) ? $args['name_reverse'] : '';
		$value_reverse = isset($args['value_reverse']) ? $args['value_reverse'] : '';
		
		echo '<div id="'.$id.'">';
			
			echo '<label class="mtphr-galleries-radio"><input type="radio" name="'.$name.'" value="fade" '.checked('fade', $value, false).' /> '.__('Fade', 'mtphr-galleries').'</label>';
			echo '<label class="mtphr-galleries-radio"><input type="radio" name="'.$name.'" value="slide_left" '.checked('slide_left', $value, false).' /> '.__('Slide left', 'mtphr-galleries').'</label>';
			echo '<label class="mtphr-galleries-radio"><input type="radio" name="'.$name.'" value="slide_right" '.checked('slide_right', $value, false).' /> '.__('Slide right', 'mtphr-galleries').'</label>';
			echo '<label class="mtphr-galleries-radio"><input type="radio" name="'.$name.'" value="slide_up" '.checked('slide_up', $value, false).' /> '.__('Slide up', 'mtphr-galleries').'</label>';
			echo '<label style="margin-right:20px;" class="mtphr-galleries-radio"><input type="radio" name="'.$name.'" value="slide_down" '.checked('slide_down', $value, false).' /> '.__('Slide down', 'mtphr-galleries').'</label>';
			if( isset($args['name_reverse']) ) {
				echo '<label class="mtphr-galleries-checkbox"><input type="checkbox" name="'.$name_reverse.'" value="on" '.checked('on', $value_reverse, false).' /> '.__('Dynamic slide direction', 'mtphr-galleries').'</label>';
			}
			
		echo '</div>';

	} else {
		echo __('Missing required data', 'mtphr-galleries');
	}
}
}



/* --------------------------------------------------------- */
/* !Auto rotate - 2.0.13 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings_auto_rotate') ) {
function mtphr_galleries_settings_auto_rotate( $args=array() ) {

	if( isset($args['name']) ) {
		
		$name = $args['name'];
		$id = isset($args['id']) ? $args['id'] : mtphr_galleries_settings_id($name);
		$value = isset($args['value']) ? $args['value'] : '';
		
		$name_delay = isset($args['name_delay']) ? $args['name_delay'] : '';
		$value_delay = isset($args['value_delay']) ? $args['value_delay'] : '';
		
		$name_pause = isset($args['name_pause']) ? $args['name_pause'] : '';
		$value_pause = isset($args['value_pause']) ? $args['value_pause'] : '';
		
		echo '<div id="'.$id.'">';
			
			echo '<label style="margin-right:20px;" class="mtphr-galleries-checkbox"><input type="checkbox" name="'.$name.'" value="on" '.checked('on', $value, false).' /> '.__('Enable', 'mtphr-galleries').'</label>';
			if( isset($args['name_delay']) ) {
				echo '<label style="margin-right:20px;" class="mtphr-galleries-checkbox"><input style="width:50px;" type="number" name="'.$name_delay.'" value="'.$value_delay.'" /> '.__('Seconds delay', 'mtphr-galleries').'</label>';
			}
			if( isset($args['name_pause']) ) {
				echo '<label class="mtphr-galleries-checkbox"><input type="checkbox" name="'.$name_pause.'" value="on" '.checked('on', $value_pause, false).' /> '.__('Pause on mouse over', 'mtphr-galleries').'</label>';
			}
			
		echo '</div>';

	} else {
		echo __('Missing required data', 'mtphr-galleries');
	}
}
}



/* --------------------------------------------------------- */
/* !Rotate speed - 2.0.13 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings_rotate_speed') ) {
function mtphr_galleries_settings_rotate_speed( $args=array() ) {

	if( isset($args['name']) ) {
		
		$name = $args['name'];
		$id = isset($args['id']) ? $args['id'] : mtphr_galleries_settings_id($name);
		$value = isset($args['value']) ? $args['value'] : '';
		
		$name_ease = isset($args['name_ease']) ? $args['name_ease'] : '';
		$value_ease = isset($args['value_ease']) ? $args['value_ease'] : '';
		
		echo '<div id="'.$id.'">';
			
			echo '<label style="margin-right:20px;" class="mtphr-galleries-checkbox"><input style="width:50px;" type="number" name="'.$name.'" value="'.$value.'" /> '.__('Tenths of a second', 'mtphr-galleries').'</label>';
			if( isset($args['name_ease']) ) {
				echo '<select name="'.$name_ease.'">';
					$eases = array('linear','swing','jswing','easeInQuad','easeInCubic','easeInQuart','easeInQuint','easeInSine','easeInExpo','easeInCirc','easeInElastic','easeInBack','easeInBounce','easeOutQuad','easeOutCubic','easeOutQuart','easeOutQuint','easeOutSine','easeOutExpo','easeOutCirc','easeOutElastic','easeOutBack','easeOutBounce','easeInOutQuad','easeInOutCubic','easeInOutQuart','easeInOutQuint','easeInOutSine','easeInOutExpo','easeInOutCirc','easeInOutElastic','easeInOutBack','easeInOutBounce');
					foreach( $eases as $ease ) {
						echo '<option '.selected($ease, $value_ease, false).'>'.$ease.'</option>';
					}
				echo '</select>';
			}
			
		echo '</div>';

	} else {
		echo __('Missing required data', 'mtphr-galleries');
	}
}
}



/* --------------------------------------------------------- */
/* !Directional navigation - 2.0.13 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings_directional_navigation') ) {
function mtphr_galleries_settings_directional_navigation( $args=array() ) {

	if( isset($args['name']) ) {
		
		$name = $args['name'];
		$id = isset($args['id']) ? $args['id'] : mtphr_galleries_settings_id($name);
		$value = isset($args['value']) ? $args['value'] : '';
		
		$name_hide = isset($args['name_hide']) ? $args['name_hide'] : '';
		$value_hide = isset($args['value_hide']) ? $args['value_hide'] : '';
		
		echo '<div id="'.$id.'">';
			
			echo '<label style="margin-right:20px;" class="mtphr-galleries-checkbox"><input type="checkbox" name="'.$name.'" value="on" '.checked('on', $value, false).' /> '.__('Enable', 'mtphr-galleries').'</label>';
			echo '<label class="mtphr-galleries-checkbox"><input type="checkbox" name="'.$name_hide.'" value="on" '.checked('on', $value_hide, false).' /> '.__('Autohide navigation', 'mtphr-galleries').'</label>';
			
		echo '</div>';

	} else {
		echo __('Missing required data', 'mtphr-galleries');
	}
}
}

