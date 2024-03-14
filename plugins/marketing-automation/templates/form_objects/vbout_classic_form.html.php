<?php
	if (!function_exists('is_serialized')) {
		function is_serialized( $data ) {
			// if it isn't a string, it isn't serialized
			if ( !is_string( $data ) )
				return false;
			$data = trim( $data );
			if ( 'N;' == $data )
				return true;
			if ( !preg_match( '/^([adObis]):/', $data, $badions ) )
				return false;
			switch ( $badions[1] ) {
				case 'a' :
				case 'O' :
				case 's' :
					if ( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ) )
						return true;
					break;
				case 'b' :
				case 'i' :
				case 'd' :
					if ( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ) )
						return true;
					break;
			}
			return false;
		}
	}
	
	$captcha = false;
	$seq = "";
	
	if (!function_exists('maybe_unserialize')) {
		function maybe_unserialize( $original ) {
			if ( is_serialized( $original ) ) // don't attempt to unserialize data that wasn't serialized going in
				return @unserialize( $original );
			return $original;
		}
	}
	
	if (!function_exists('optionchecked')) {
		function optionchecked($value, $match) {
			if ($value == $match) // don't attempt to unserialize data that wasn't serialized going in
				return 'checked="checked"';
				
			return '';
		}
	}
	
	if (!function_exists('optionselected')) {
		function optionselected($value, $match) {
			if ($value == $match) // don't attempt to unserialize data that wasn't serialized going in
				return 'selected="selected"';
				
			return '';
		}
	}
?>

<script async src="https://vbt.io/ext/vbtforms.js"></script>
<link href="https://vbt.io/ext/vbtforms.css?formid=<?php echo $form['id']; ?>" rel="stylesheet" type="text/css">
<link href="https://vbt.io/ext/flatpickr.min.css" rel="stylesheet" type="text/css">
<style>.vboutEmbedFormField .field label.vfb-choice{display: inline-block;}</style>

<div id="vboutEmbedFormWrapper">
	<form action="https://vbt.io/embedcode/submit/<?php echo $form['id']; ?>/?_format=page&uid=<?php echo time(); ?>" target="_blank" method="post" data-vboutform="<?php echo $form['id']; ?>" id="vboutEmbedForm-<?php echo $form['id']; ?>" name="vboutEmbedForm-<?php echo $form['id']; ?>">
		<h1><?php echo $form['name']; ?></h1>

		<div id="vboutEmbedFormResponse-<?php echo $form['id']; ?>" style="display: none;"></div>
		<fieldset>
			<?php $vboutEmbedFormRow = array(); ?>
			<?php if (isset($fields) && $fields != NULL): $dates = array(); ?>
			<?php		foreach ($fields as $field): ?>
			<?php			

			$field_id		= $field['meta']['field_id'];
			$field_type 	= $field['meta']['field_type'];
			$field_name		= stripslashes($field['meta']['field_name']);
			$required_span 	= (!empty($field['meta']['field_required']) && $field['meta']['field_required'] === 'yes')?'<span class="required-asterisk">*</span>' : '';
			$required 		= (!empty($field['meta']['field_required']) && $field['meta']['field_required'] === 'yes')?' required':'';
			$validation 	= (!empty($field['meta']['field_validation']))? $field['meta']['field_validation']:'';
			$css 			= (!empty($field['meta']['field_css']))? $field['meta']['field_css']:'';
			$id_attr 		= "custom-{$field_id}";
			$default 		= (!empty($field['meta']['field_default']))?stripslashes($field['meta']['field_default']):'';
			$description	= (!empty($field['meta']['field_description']))? 'data-error=' . json_encode($field['meta']['field_description']):'';
			$placeHolder	= (!empty($field['meta']['field_description']))? 'placeholder=' . json_encode($field['meta']['field_description']):'';
			$field_sequence	= (!empty($field['meta']['field_sequence']))?$field['meta']['field_sequence']:0;
			
			$form_item = '';

			if(strtolower($field['meta']['field_type']) == 'captcha') {
				$captcha = true;
				$seq = $field_sequence;
				continue;
			}

			switch ($field_type) {
				case 'text' :
				case 'email' :
				case 'url' :
				case 'currency' :
				case 'number' :
				case 'phone' :
					// HTML5 types
					if (in_array($field_type, array('email'))) {
						$type = $field_type;
						$css .= $field_type;
					} elseif ('phone' == $field_type)
					$type = 'tel';
					else
						$type = 'text';
							
						$form_item = sprintf(
								'<div class="vboutEmbedFormField"><input type="%7$s" name="vbout_EmbedForm[field][%1$d]" id="%2$s" value="%3$s" class="vfb-text %4$s %5$s %6$s" %8$s %9$s /></div>',
								$field_id,
								$id_attr,
								$default,
								$required,
								$validation,
								$css,
								$type,
								$description,
								$placeHolder
								);
			
						break;
			
				case 'textarea' :
					$form_item = sprintf(
					'<div class="vboutEmbedFormField"><textarea name="vbout_EmbedForm[field][%1$d]" id="%2$s" class="vfb-textarea %4$s %5$s" %6$s %7$s>%3$s</textarea></div>',
					$field_id,
					$id_attr,
					$default,
					$required,
					$css,
					$description,
					$placeHolder
					);
			
					break;
			
				case 'select' :
					$field_options = maybe_unserialize($field['meta']['field_options']);
					
					if(!is_array($field_options)) {
						$field_options = array();
					}
			
					$options = '';
			
					//CHECK IF EMPTY THE DROPDOWNS
					if(empty($field_options) || count($field_options)==0){
						$options .= sprintf( '<option value="" selected disabled>---- SELECT ---- </option>');
					}

					// Loop through each option and output
					foreach ( $field_options as $option => $value ) {
						$options .= sprintf( '<option value="%1$s"%2$s>%1$s</option>', trim(stripslashes($value)), optionselected($default, ++$option));
					}
			
					$form_item = sprintf(
							'<div class="vboutEmbedFormField"><select name="vbout_EmbedForm[field][%1$d]" id="%2$s" data-type="%8$s" class="vfb-select %3$s %4$s %5$s" %7$s>%6$s</select></div>',
							$field_id,
							$id_attr,
							(isset($size)) ? $size : 0,
							$required,
							$css,
							$options,
							$description,
							$validation
							);
			
					break;
			
				case 'radio' :
					$field_options = maybe_unserialize($field['meta']['field_options']);
					if(!is_array($field_options)) {
						$field_options = array();
					}
			
					$options = '<div class="vboutEmbedFormField">';
			
					// Loop through each option and output
					foreach ( $field_options as $option => $value ) {
						$option++;
			
						$options .= sprintf(
								'<div class="field"><input type="radio" name="vbout_EmbedForm[field][%1$d]" id="%2$s-%3$d" value="%6$s" class="vfb-radio %4$s %5$s" %8$s %9$s /><label for="%2$s-%3$d" class="vfb-choice">%7$s</label></div>',
								$field_id,
								$id_attr,
								$option,
								$required,
								$css,
								trim(stripslashes($value)),
								stripslashes($value),
								optionchecked($default, $option),
								$description
								);
					}
					$options .= '</div>';
			
					$form_item = $options;
			
					break;
			
				case 'checkbox' :
					$field_options = maybe_unserialize($field['meta']['field_options']);
					if(!is_array($field_options)) {
						$field_options = array();
					}
			
					$options = '<div class="vboutEmbedFormField">';
			
					// Loop through each option and output
					foreach ( $field_options as $option => $value ) {
						$options .= sprintf(
								'<div class="field"><input type="checkbox" name="vbout_EmbedForm[field][%1$d][]" id="%2$s-%3$d" value="%6$s" class="vfb-checkbox %4$s %5$s" %8$s %9$s /><label for="%2$s-%3$d" class="vfb-choice">%7$s</label></div>',
								$field_id,
								$id_attr,
								$option,
								$required,
								$css,
								trim(stripslashes($value)),
								stripslashes($value),
								optionchecked($default, ++$option),
								$description
								);
					}
					$options .= '</div>';
			
					$form_item = $options;
			
					break;
			
				case 'date' :
					$options = maybe_unserialize( $field['meta']['field_options'] );
					if(!is_array($options)) {
						$options = array();
					}
					$dateFormat = ( $options ) ? $options['dateFormat'] : '';
						
					$form_item = sprintf(
							'<div class="vboutEmbedFormField"><input type="text" name="vbout_EmbedForm[field][%1$d]" id="%2$s" value="%3$s" class="vfb-text date vboutEmbedFormDatePicker %4$s %5$s" data-format="%6$s" %7$s %8$s /></div>',
							$field_id,
							$id_attr,
							$default,
							$required,
							$css,
							$dateFormat,
							$description,
							$placeHolder
							);
			
					break;
						
				case 'gdpr':
					$field_options = maybe_unserialize($field['meta']['field_options']);
					if(!is_array($field_options)) {
						$field_options = array();
					}

					$options = '<div class="vboutEmbedFormField">';

					// Loop through each option and output
					foreach ( $field_options as $option => $value ) {
						$requiredOption = !empty($value['required']) ? ' required requiredOption':'';
						$defaultOption = !empty($value['default']) ? ' checked="checked"':'';

						$options .= sprintf(
							'<div class="field"><input type="checkbox" name="vbout_EmbedForm[field][%1$d][%3$s]" id="%2$s-%3$s" value="%6$s" class="vfb-gdpr vfb-checkbox %4$s %5$s" %8$s /><label for="%2$s-%3$s" class="vfb-choice">%7$s</label></div>',
							$field_id,
							$id_attr,
							(string) $option,
							$requiredOption,
							$css,
							'yes',
							trim(stripslashes($value['label'])),
							$defaultOption
						);
					}

					$options .= '<span class="note"><span class="vfb-gdpr-disclaimer">'. $field['meta']['field_description'] .'</span></span>';

					$options .= '</div>';

					$form_item = $options;

					break;
			}
			
				$vboutEmbedFormRow[(int)$field_sequence] = '<div class="vboutEmbedFormRow seq'.$field_sequence.'">
				<label class="title" for="'.$id_attr.'">'.$field_name.$required_span.'</label>'.$form_item.'</div>';
				
			endforeach; 
			ksort($vboutEmbedFormRow,SORT_NUMERIC);
			if($captcha) { // If we have reCAPTCHA enabled for this form
				$vboutEmbedFormRow[(int)$seq] = '<div class="vboutEmbedFormRow seq'.$seq.'"><div class="recaptchaField"></div></div>';
			}
			foreach ($vboutEmbedFormRow as $fieldrow):
				echo $fieldrow;
			endforeach; ?>
			<div class="vboutEmbedFormRow"><input type="submit" value="<?php echo $form['submit']; ?>"></div>
			<?php endif; ?>
		</fieldset>
	</form>
</div>
