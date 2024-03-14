<?php
global $arflite_font_awesome_loaded;
if ( in_array( $field['type'], array( 'website', 'phone', 'date', 'email', 'url', 'number' ) ) ) { ?>
	<?php
		$input_cls = '';
		$inp_cls   = '';
	if ( ( isset( $field['enable_arf_prefix'] ) && $field['enable_arf_prefix'] == 1 ) or ( isset( $field['enable_arf_suffix'] ) && $field['enable_arf_suffix'] == 1 ) ) {
					$arflite_font_awesome_loaded = 1;
			echo "<div class='arf_editor_prefix_suffix_wrapper' id='prefix_suffix_wrapper_'" . esc_attr( $field['id'] ) . "'>";
		if ( $field['enable_arf_prefix'] == 1 && $field['enable_arf_suffix'] == 1 ) {
			$inp_cls = 'arf_both_pre_suffix';
		} elseif ( $field['enable_arf_prefix'] == 1 ) {
			$inp_cls = 'arf_prefix_only';
		} elseif ( $field['enable_arf_suffix'] == 1 ) {
			$inp_cls = 'arf_suffix_only';
		}

		if ( $field['enable_arf_prefix'] == 1 ) {
			echo "<span class='arf_editor_prefix' id='arf_editor_prefix_'".esc_attr($field['id'])."'><i class='" . esc_attr( $field['arf_prefix_icon'] ) . "'></i></span>";
		}
			$input_cls = 'arf_prefix_suffix';

	}
	?>
	<input type="text" class="<?php echo esc_attr( $input_cls ) . ' ' . esc_attr( $inp_cls ); ?>" name="<?php echo esc_attr( $field_name ); ?>" id="itemmeta_<?php echo esc_attr( $field['id'] ); ?>" onkeyup="arflitechangeitemmeta('<?php echo esc_attr( $field['id'] ); ?>');" value="<?php echo esc_attr( $field['default_value'] ); ?>" />

	<?php
	if ( ( isset( $field['enable_arf_prefix'] ) && $field['enable_arf_prefix'] ) == 1 or ( isset( $field['enable_arf_suffix'] ) && $field['enable_arf_suffix'] == 1 ) ) {
		$arflite_font_awesome_loaded = 1;
		if ( $field['enable_arf_suffix'] == 1 ) {
			echo "<span class='arf_editor_suffix' id='arf_editor_suffix_'".esc_attr($field['id'])."'><i class='" . esc_attr( $field['arf_suffix_icon'] ) . "'></i></span>";
		}
		echo '</div>';
	}
	?>
<?php } elseif ( $field['type'] == 'hidden' ) { ?>

	<input type="text" name="<?php echo esc_attr( $field_name ); ?>" id="itemmeta_<?php echo esc_attr( $field['id'] ); ?>" onkeyup="arflitechangeitemmeta('<?php echo esc_attr( $field['id'] ); ?>');" value="<?php echo esc_attr( $field['default_value'] ); ?>"/> 

	<p class="howto clear"><?php echo esc_html__( 'Note: This field will not show in the form. Enter the value to be hidden.', 'arforms-form-builder' ); ?><br/>
	[ARF_current_user_id], [ARF_current_user_name], [ARF_current_user_email], [ARF_current_date]</p>
	

<?php } elseif ( $field['type'] == 'time' ) { ?>

<div  id="field_default_hour_<?php echo esc_attr( $field['field_key'] ); ?>" class="arf_field_default_time_element arflite_float_left">
<select name="field_options[default_hour_<?php echo esc_attr( $field['id'] ); ?>]" id="field_<?php echo esc_attr( $field['field_key'] ); ?>" >
	<?php
	for ( $i = 0; $i <= $field['clock']; $i++ ) {
		?>
	<option value="<?php echo esc_attr( $i ); ?>" 
							  <?php
								if ( $i == $field['default_hour'] ) {
									echo 'selected=selected';}
								?>
		><?php echo esc_html( $i ); ?></option>
	<?php } ?>
</select>
<br /> <div class="howto">&nbsp;(HH)</div>
</div>

	<div class="arf_field_default_time_element arflite_float_left">
<select name="field_options[default_minutes_<?php echo esc_attr( $field['id'] ); ?>]" id="field_<?php echo esc_attr( $field['field_key'] ); ?>" >
	<?php for ( $j = 0; $j <= 59; $j++ ) { ?>
	<option value="<?php echo esc_attr( $j ); ?>" 
							  <?php
								if ( $j == $field['default_minutes'] ) {
									echo 'selected=selected';}
								?>
		><?php echo esc_html( $j ); ?></option>
	<?php } ?>
</select>
<br /> <div class="howto">&nbsp;(MM)</div>
</div> 

<?php } elseif ( $field['type'] == 'image' ) { ?>

	<?php
		$input_cls = '';
		$inp_cls   = '';
	if ( $field['enable_arf_prefix'] == 1 or $field['enable_arf_suffix'] == 1 ) {
				$arflite_font_awesome_loaded = 1;
		echo "<div class='arf_editor_prefix_suffix_wrapper' id='prefix_suffix_wrapper_'" . esc_attr( $field['id'] ) . "'>";
		if ( $field['enable_arf_prefix'] == 1 && $field['enable_arf_suffix'] == 1 ) {
			$inp_cls = 'arf_both_pre_suffix';
		} elseif ( $field['enable_arf_prefix'] == 1 ) {
			$inp_cls = 'arf_prefix_only';
		} elseif ( $field['enable_arf_suffix'] == 1 ) {
			$inp_cls = 'arf_suffix_only';
		}

		if ( $field['enable_arf_prefix'] == 1 ) {

			echo "<span class='arf_editor_prefix' id='arf_editor_prefix_'".esc_attr($field['id'])."'><i class='" . esc_attr( $field['arf_prefix_icon'] ) . "'></i></span>";
		}
		$input_cls = 'arf_prefix_suffix';

	}
	?>
	<input type="text" name="<?php echo esc_attr( $field_name ); ?>" id="itemmeta_<?php echo esc_attr( $field['id'] ); ?>" onkeyup="arflitechangeitemmeta('<?php echo esc_html( $field['id'] ); ?>');" value="<?php echo esc_attr( $field['default_value'] ); ?>" class="<?php echo esc_attr( $input_cls ) . ' ' . esc_attr( $inp_cls ); ?>" />
	<?php
	if ( $field['enable_arf_prefix'] == 1 or $field['enable_arf_suffix'] == 1 ) {
				$arflite_font_awesome_loaded = 1;
		if ( $field['enable_arf_suffix'] == 1 ) {
			echo "<span class='arf_editor_suffix' id='arf_editor_suffix_'".esc_attr($field['id'])."'><i class='" . esc_attr( $field['arf_suffix_icon'] ) . "'></i></span>";
		}
		echo '</div>';
	}
	?>

	<?php
} elseif ( $field['type'] == 'html' ) {
	?>

<p class="howto clear"><?php echo esc_html__( 'Note: Set your custom html content', 'arforms-form-builder' ); ?></p>

<?php } elseif ( $field['type'] == 'form' ) {

	echo 'FORM';

} ?>
