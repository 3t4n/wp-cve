<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// contact form
$email_form = '<form name="vscf_'.$rand_suffix.'" id="vscf" class="'.$form_class.'" method="post">
	<div class="form-group vscf-name-group">
		<label for="vscf_name" class="'.(($hide_labels == 'yes') ? "vscf-hide" : "vscf-label").'">'.esc_attr($name_label).' </label>
		<input type="text" name="vscf_name_'.$rand_suffix.'" id="vscf_name" '.((isset($error_class['form_name']) || isset($error_class['form_name_banned_words'])) ? ' class="form-control vscf-error"' : ' class="form-control"').' placeholder="'.esc_attr($name_placeholder).'" value="'.esc_attr($form_data['form_name']).'" maxlength="'.$input_max_length.'" aria-required="true" />
		'.(isset($error_class['form_name']) ? '<label for="vscf_name" class="vscf-label vscf-error" >'.esc_attr($error_name_label).'</label>' : '').(isset($error_class['form_name_banned_words']) ? '<label for="vscf_name" class="vscf-label vscf-error" >'.esc_attr($error_banned_words_label).'</label>' : '').'
	</div>
	<div class="form-group vscf-email-group">
		<label for="vscf_email" class="'.(($hide_labels == 'yes') ? "vscf-hide" : "vscf-label").'">'.esc_attr($email_label).' </label>
		<input type="email" name="vscf_email_'.$rand_suffix.'" id="vscf_email" '.((isset($error_class['form_email']) || isset($error_class['form_email_banned_words'])) ? ' class="form-control vscf-error"' : ' class="form-control"').' placeholder="'.esc_attr($email_placeholder).'" value="'.esc_attr($form_data['form_email']).'" maxlength="'.$input_max_length.'" aria-required="true" />
		'.(isset($error_class['form_email']) ? '<label for="vscf_email" class="vscf-label vscf-error" >'.esc_attr($error_email_label).'</label>' : '').(isset($error_class['form_email_banned_words']) ? '<label for="vscf_email" class="vscf-label vscf-error" >'.esc_attr($error_banned_words_label).'</label>' : '').'
	</div>
	'.(($disable_subject != 'yes') ? '
		<div class="form-group vscf-subject-group">
			<label for="vscf_subject" class="'.(($hide_labels == 'yes') ? "vscf-hide" : "vscf-label").'">'.esc_attr($subject_label).' </label>
			<input type="text" name="vscf_subject_'.$rand_suffix.'" id="vscf_subject" '. ((isset($error_class['form_subject']) || isset($error_class['form_subject_banned_words'])) ? ' class="form-control vscf-error"' : ' class="form-control"').' placeholder="'.esc_attr($subject_placeholder).'" value="'.esc_attr($form_data['form_subject']).'" maxlength="'.$input_max_length.'" aria-required="true" />
			'.(isset($error_class['form_subject']) ? '<label for="vscf_subject" class="vscf-label vscf-error" >'.esc_attr($error_subject_label).'</label>' : '').(isset($error_class['form_subject_banned_words']) ? '<label for="vscf_subject" class="vscf-label vscf-error" >'.esc_attr($error_banned_words_label).'</label>' : '').'
		</div>
	' : '').'
	'.(($disable_sum == 'yes') ? '
		<div class="form-group vscf-display-none">
			<input type="hidden" name="vscf_sum" id="vscf_sum" class="form-control" value="'.esc_attr($vscf_hidden_sum).'" />
		</div>
	' : '
		<div class="form-group vscf-sum-group">
			<label for="vscf_sum" class="'.(($hide_labels == 'yes') ? "vscf-hide" : "vscf-label").'">'.esc_attr($vscf_rand_one).' + '.esc_attr($vscf_rand_two).' =</label>
			<input type="text" name="vscf_sum_'.$rand_suffix.'" id="vscf_sum" '.(isset($error_class['form_sum']) ? ' class="form-control vscf-error"' : ' class="form-control"').' placeholder="'.esc_attr($sum_placeholder).'" value="'.esc_attr($form_data['form_sum']).'" maxlength="'.$input_max_length.'" aria-required="true" />
			'.(isset($error_class['form_sum']) ? '<label for="vscf_sum" class="vscf-label vscf-error" >'.esc_attr($error_sum_label).'</label>' : '') .'
		</div>
	').'
	<div class="form-group vscf-hide">
		<label for="vscf_first_random">'.esc_attr__( 'Please ignore this field', 'very-simple-contact-form' ).'</label>
		<input type="text" name="vscf_first_random_'.$rand_suffix.'" id="vscf_first_random" class="form-control" tabindex="-1" value="'.esc_attr($form_data['form_first_random']).'" maxlength="'.$input_max_length.'" />
	</div>
	<div class="form-group vscf-hide">
		<label for="vscf_second_random">'.esc_attr__( 'Please ignore this field', 'very-simple-contact-form' ).'</label>
		<input type="text" name="vscf_second_random_'.$rand_suffix.'" id="vscf_second_random" class="form-control" tabindex="-1" value="'.esc_attr($form_data['form_second_random']).'" maxlength="'.$input_max_length.'" />
	</div>
	<div class="form-group vscf-message-group">
		<label for="vscf_message" class="'.(($hide_labels == 'yes') ? "vscf-hide" : "vscf-label").'">'.esc_attr($message_label).' </label>
		<textarea name="vscf_message_'.$rand_suffix.'" id="vscf_message" rows="10" '.((isset($error_class['form_message']) || isset($error_class['form_message_banned_words']) || isset($error_class['form_message_has_links']) || isset($error_class['form_message_has_email'])) ? ' class="form-control vscf-error"' : ' class="form-control"').' placeholder="'.esc_attr($message_placeholder).'" maxlength="'.$textarea_max_length.'" aria-required="true">'.esc_textarea($form_data['form_message']).'</textarea>
		'.((isset($error_class['form_message']) || isset($error_class['form_message_banned_words']) || isset($error_class['form_message_has_links']) || isset($error_class['form_message_has_email'])) ? '
			<label for="vscf_message" class="vscf-label vscf-error">'.(isset($error_class['form_message']) ? esc_attr($error_message_label) : '').(isset($error_class['form_message_banned_words']) ? esc_attr($error_banned_words_label) : '').(isset($error_class['form_message_has_links']) ? esc_attr($error_message_has_links_label) : '').(isset($error_class['form_message_has_email']) ? esc_attr($error_message_has_email_label) : '').'</label>
		' : '').'
	</div>
	<div class="form-group vscf-display-none">
		<input type="hidden" name="vscf_time_'.$rand_suffix.'" id="vscf_time" class="form-control" value="'.esc_attr($vscf_time_field).'" />
	</div>
	'.(($disable_privacy != 'yes') ? '
		<div class="form-group vscf-privacy-group">
			<input type="hidden" name="vscf_privacy_'.$rand_suffix.'" id="vscf_privacy_hidden" value="no" />
			<input type="checkbox" name="vscf_privacy_'.$rand_suffix.'" id="vscf_privacy" class="custom-control-input" value="yes" '.checked( esc_attr($form_data['form_privacy']), "yes", false ).' />
			<label for="vscf_privacy">'.esc_attr($privacy_label).'</label>
			'.(isset($error_class['form_privacy']) ? '<label for="vscf_privacy" class="vscf-label vscf-error" >'.esc_attr($error_privacy_label).'</label>' : '') .'
		</div>
	' : '').'
	<div class="form-group vscf-display-none">
		'.$vscf_nonce_field.'
	</div>
	<div class="form-group vscf-submit-group">
		<button type="submit" name="'.$submit_name.'" id="'.$submit_id.'" class="btn btn-primary">'.esc_attr($submit_label).'</button>
	</div>
	'.(($display_errors == 'yes') ?
	((isset($error_class['form_first_random']) || isset($error_class['form_second_random'])) ? '<p class="vscf-error" >'.esc_attr__( 'Error: hidden field', 'very-simple-contact-form' ).'</p>' : '').
	(isset($error_class['form_time']) ? '<p class="vscf-error" >'.esc_attr__( 'Error: time', 'very-simple-contact-form' ).'</p>' : '').
	(isset($error_class['form_nonce']) ? '<p class="vscf-error" >'.esc_attr__( 'Error: nonce', 'very-simple-contact-form' ).'</p>' : '').
	(isset($error_class['form_sum_hidden']) ? '<p class="vscf-error" >'.esc_attr__( 'Error: sum', 'very-simple-contact-form' ).'</p>' : '').
	(isset($error_class['form_transient']) ? '<p class="vscf-error" >'.esc_attr__( 'Error: transient', 'very-simple-contact-form' ).'</p>' : '')
	: '').'
</form>';
