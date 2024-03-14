<?php	
if ( ! defined( 'WPINC' ) ) die;

$id = isset( $_REQUEST['form'] ) ? absint($_REQUEST['form']) : '1'; 
$attributes = get_option("sform_{$id}_attributes") != false ? get_option("sform_{$id}_attributes") : get_option("sform_attributes");
$main_settings = get_option('sform_settings'); 
$settings = get_option("sform_{$id}_settings") != false ? get_option("sform_{$id}_settings") : $main_settings;
$admin_notices = ! empty( $main_settings['admin_notices'] ) ? esc_attr($main_settings['admin_notices']) : 'false';
$color = ! empty( $settings['admin_color'] ) ? esc_attr($settings['admin_color']) : 'default';
$notice = '';
$extra_option = '';
?>

<div id="sform-wrap" class="sform">

<div id="new-release" class="<?php if ( $admin_notices == 'true' ) {echo 'invisible';} ?>"><?php echo apply_filters( 'sform_update', $notice ); ?>&nbsp;</div>
	
<div class="full-width-bar <?php echo $color ?>">
<h1 class="title <?php echo $color ?>"><span class="dashicons dashicons-admin-settings responsive"></span><?php _e( 'Settings', 'simpleform' );
global $wpdb; 
$table_name = "{$wpdb->prefix}sform_shortcodes"; 
$page_forms = $wpdb->get_results( "SELECT id, name FROM $table_name WHERE widget = '0' AND status != 'trash' ORDER BY name ASC", 'ARRAY_A' );
$widget_forms = $wpdb->get_results( "SELECT id, name FROM $table_name WHERE widget != '0' AND status != 'trash' ORDER BY name ASC", 'ARRAY_A' );
$page_ids = array_column($page_forms, 'id');
$widget_ids = array_column($widget_forms, 'id');
$shortcode_ids = array_merge($page_ids, $widget_ids);
$all_forms = count($page_forms) + count($widget_forms);
if ( $all_forms > 1 ) { ?>
<div class="selector"><div id="wrap-selector" class="responsive"><?php echo _e( 'Select Form', 'simpleform' ) ?>:</div><div class="form-selector"><select name="form" id="form" class="<?php echo $color ?>"><?php if ( $page_forms && $widget_forms ) {  echo '<optgroup label="'.esc_attr__( 'Embedded in page', 'simpleform' ).'">'; } foreach($page_forms as $form) { $form_id = $form['id']; $form_name = $form['name']; echo '<option value="'.$form_id.'" '.selected( $id, $form_id ) .'>'.$form_name.'</option>'; } if ( $page_forms && $widget_forms ) {  echo '</optgroup>'; } if ( $page_forms && $widget_forms ) {  echo '<optgroup label="'.esc_attr__( 'Embedded in widget area', 'simpleform' ).'">'; } foreach($widget_forms as $form) { $form_id = $form['id']; $form_name = $form['name']; echo '<option value="'.$form_id.'" '.selected( $id, $form_id ) .'>'.$form_name.'</option>'; } if ( $page_forms && $widget_forms ) {  echo '</optgroup>'; }?></select></div></div>
<?php } ?>
</h1></div>

<?php if ( in_array($id, $shortcode_ids) ) { 
$transient_notice = get_transient('sform_action_newform');
if ( isset( $_REQUEST['status'] ) && $_REQUEST['status'] == 'new' && $transient_notice != '' ) { echo '<div class="notice notice-success is-dismissible"><p>' . __( 'The new contact form has been successfully created. Customize settings before you start using it!', 'simpleform' ) .'</p></div>'; delete_transient( 'sform_action_newform' ); }

$sidebars_widgets = get_option('sidebars_widgets');
$simpleform_widgets = '';
foreach ( $sidebars_widgets as $sidebar => $widgets ) { if ( is_array( $widgets ) ) { foreach ( $widgets as $key => $widget_id ) { if ( strpos($widget_id, 'sform_widget-' ) !== false ) { $simpleform_widgets .= '1'; }}}}
?>

<div id="page-description"><p><?php _e( 'Customize messages and whatever settings you want to better match your needs:','simpleform') ?></p></div>

<div id="settings-tabs"><a class="nav-tab nav-tab-active" id="general"><?php _e( 'General','simpleform') ?></a><a class="nav-tab" id="messages"><?php _e( 'Validation','simpleform') ?></a><a class="nav-tab" id="email"><?php _e( 'Notifications','simpleform') ?></a><a class="nav-tab" id="spam"><?php _e( 'Anti-Spam','simpleform') ?></a><?php echo apply_filters( 'sform_itab', $extra_option ) ?><a class="form-button last <?php echo $color ?>" href="<?php $arg = $id != '1' ? '&form='. $id : ''; echo admin_url('admin.php?page=sform-editor') . $arg; ?>" target="_blank"><span><span class="dashicons dashicons-editor-table"></span><span class="text"><?php _e( 'Editor', 'simpleform' ) ?></span></span></a><a class="form-button form-page <?php echo $color ?>" href="<?php $arg = $id != '1' ? '&id='. $id : ''; echo admin_url('admin.php?page=sform-form') . $arg; ?>" target="_blank"><span><span class="dashicons dashicons-tag"></span><span class="text"><?php _e( 'Specifics', 'simpleform' ) ?></span></span></a></div>
						
<form id="settings" method="post" class="<?php echo $color ?>">
		
<input type="hidden" id="form-id" name="form-id" value="<?php echo $id ?>">
	
<div id="tab-general" class="navtab">

<?php
$disabled_class = $id == '1' ? '' : 'class="disabled"';
$html5_validation = ! empty( $settings['html5_validation'] ) ? esc_attr($settings['html5_validation']) : 'false';
$out_error = ! empty( $settings['outside_error'] ) ? esc_attr($settings['outside_error']) : 'bottom';
$focus = ! empty( $settings['focus'] ) ? esc_attr($settings['focus']) : 'field';
$focus_notes = $out_error == 'none' ? __('Do not move focus', 'simpleform' ) : __('Set focus to error message outside', 'simpleform' );
$ajax = ! empty( $settings['ajax_submission'] ) ? esc_attr($settings['ajax_submission']) : 'false';
$spinner = ! empty( $settings['spinner'] ) ? esc_attr($settings['spinner']) : 'false';
$form_template = ! empty( $settings['form_template'] ) ? esc_attr($settings['form_template']) : 'default';
$style_notes = $form_template == 'customized' ? __('Create a directory inside your active theme\'s directory, name it "simpleform", copy one of the template files, and name it "custom-template.php"', 'simpleform' ) : '&nbsp;';
$stylesheet = ! empty( $settings['stylesheet'] ) ? esc_attr($settings['stylesheet']) : 'false';
$cssfile  = ! empty( $settings['stylesheet_file'] ) ? esc_attr($settings['stylesheet_file']) : 'false';
$javascript = ! empty( $settings['javascript'] ) ? esc_attr($settings['javascript']) : 'false';
$css_notes_on = __('Create a directory inside your active theme\'s directory, name it "simpleform", add your CSS stylesheet file, and name it "custom-style.css"', 'simpleform' );
$css_notes_off = __('Keep unchecked if you want to use your personal CSS code and include it somewhere in your theme\'s code without using an additional file', 'simpleform' );
$css_notes = $cssfile == 'false' ? $css_notes_off : $css_notes_on;
$js_notes_on = __('Create a directory inside your active theme\'s directory, name it "simpleform", add your JavaScript file, and name it "custom-script.js"', 'simpleform' );
$js_notes_off = __('Keep unchecked if you want to use your personal JavaScript code and include it somewhere in your theme\'s code without using an additional file', 'simpleform' );
$js_notes = $javascript == 'false' ? $js_notes_off : $js_notes_on;
$uninstall = ! empty( $settings['deletion_data'] ) ? esc_attr($settings['deletion_data']) : 'true';
$disabled = 'disabled="disabled"';
$frontend_notice = ! empty( $settings['frontend_notice'] ) ? esc_attr($settings['frontend_notice']) : 'true';
?>		
	
<h2 id="h2-admin" class="options-heading"><span class="heading" section="admin"><?php _e( 'Management Preferences', 'simpleform' ) ?><span class="toggle dashicons dashicons-arrow-up-alt2 admin"></span></span><?php if ( $id != '1' ) { ?><a href="<?php echo menu_page_url( 'sform-settings', false ); ?>"><span class="dashicons dashicons-edit icon-button admin <?php echo $color ?>"></span><span class="settings-page wp-core-ui button admin"><?php _e( 'Go to main settings for edit', 'simpleform' ) ?></span></a><?php } ?></h2>

<div class="section admin"><table class="form-table admin"><tbody>

<tr><th class="option"><span><?php _e('Admin Notices','simpleform') ?></span></th><td class="checkbox-switch notes"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="admin-notices" id="admin-notices" class="sform-switch" value="false" <?php checked( $admin_notices, 'true'); if ( $id != '1' ) { echo $disabled; } ?>><span></span></label><label for="admin-notices" class="switch-label <?php if ( $id != '1' ) { echo 'disabled'; } ?>"><?php _e('Never display notices on the SimpleForm related admin pages','simpleform') ?></label></div><p class="description"><?php _e('Admin notices may include, but are not limited to, reminders, update notifications, calls to action, and links to documentation','simpleform') ?></p></td></tr>

<tr><th class="option"><span><?php _e('Front-end Admin Notice','simpleform') ?></span></th><td class="checkbox-switch notes"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="frontend-notice" id="frontend-notice" class="sform-switch" value="true" <?php checked( $frontend_notice, 'true'); if ( $id != '1' ) { echo $disabled; } ?>><span></span></label><label for="frontend-notice" class="switch-label <?php if ( $id != '1' ) { echo 'disabled'; } ?>"><?php _e('Display an admin notice when the form cannot be seen by the admin when visiting the website\'s front end','simpleform') ?></label></div></td></tr>

<tr><th class="option"><span><?php _e( 'Admin Color Scheme', 'simpleform' ) ?></span></th><td class="last select"><select name="admin-color" id="admin-color" class="sform" <?php if ( $id != '1' ) { echo $disabled; } ?>><option value="default" <?php selected( $color, 'default'); ?>><?php _e('Default','simpleform') ?></option><option value="light" <?php selected( $color, 'light'); ?>><?php _e('Light','simpleform') ?></option><option value="modern" <?php selected( $color, 'modern'); ?>><?php _e('Modern','simpleform') ?></option><option value="blue" <?php selected( $color, 'blue'); ?>><?php _e('Blue','simpleform') ?></option><option value="coffee" <?php selected( $color, 'coffee'); ?>><?php _e('Coffee','simpleform') ?></option><option value="ectoplasm" <?php selected( $color, 'ectoplasm'); ?>><?php _e('Ectoplasm','simpleform') ?></option><option value="midnight" <?php selected( $color, 'midnight'); ?>><?php _e('Midnight','simpleform') ?></option><option value="ocean" <?php selected( $color, 'ocean'); ?>><?php _e('Ocean','simpleform') ?></option><option value="sunrise" <?php selected( $color, 'sunrise'); ?>><?php _e('Sunrise','simpleform') ?></option><option value="foggy" <?php selected( $color, 'foggy'); ?>><?php _e('Foggy','simpleform') ?></option><option value="polar" <?php selected( $color, 'polar'); ?>><?php _e('Polar','simpleform') ?></option></select></td></tr>

</tbody></table></div>

<?php
if ( has_filter('submissions_settings_filter') ) {
echo apply_filters( 'submissions_settings_filter', $id, $extra_option ); 
}
?>

<h2 id="h2-submission" class="options-heading"><span class="heading" section="submission"><?php _e( 'Form Submission', 'simpleform' ); ?><span class="toggle dashicons dashicons-arrow-up-alt2 submission"></span></span></h2>

<div class="section submission"><table class="form-table submission"><tbody>

<tr><th class="option"><span><?php _e('AJAX Submission','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="ajax-submission" id="ajax-submission" class="sform-switch" value="true" <?php checked( $ajax, 'true'); ?>><span></span></label><label for="ajax-submission" class="switch-label"><?php _e( 'Perform form submission via AJAX instead of a standard HTML request','simpleform') ?></label></div></td></tr>

<tr class="trajax <?php if ($ajax != 'true') { echo 'unseen'; } ?>"><th class="option"><span><?php _e('Loading Spinner','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="spinner" id="spinner" class="sform-switch" value="false" <?php checked( $spinner, 'true'); ?>><span></span></label><label for="spinner" class="switch-label"><?php _e( 'Use a CSS animation to let users know that their request is being processed','simpleform') ?></label></div></td></tr>

<tr><th class="option"><span><?php _e('HTML5 Browser Validation','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="html5-validation" id="html5-validation" class="sform-switch" value="false" <?php checked( $html5_validation, 'true'); ?>><span></span></label><label for="html5-validation" class="switch-label"><?php _e( 'Disable the browser default form validation','simpleform') ?></label></div></td></tr>

<tr><th class="option"><span><?php _e( 'Focus on Form Errors', 'simpleform' ) ?></span></th><td class="last radio"><fieldset><label for="field" class="radio"><input id="field" type="radio" name="focus" value="field" <?php checked( $focus, 'field'); ?> ><?php _e( 'Set focus to first invalid field', 'simpleform' ) ?></label><label id="focusout" for="alert" class="radio"><input id="alert" type="radio" name="focus" value="alert" <?php checked( $focus, 'alert'); ?> ><?php echo $focus_notes; ?></label></fieldset></td></tr>

</tbody></table></div>

<h2 id="h2-formstyle" class="options-heading"><span class="heading" section="formstyle"><?php _e( 'Form Style', 'simpleform' ); ?><span class="toggle dashicons dashicons-arrow-up-alt2 formstyle"></span></span></h2>

<div class="section formstyle"><table class="form-table formstyle"><tbody>

<tr><th class="option"><span><?php _e('Style','simpleform') ?></span></th><td class="select notes last"><select name="form-template" id="form-template" class="sform"><option value="default" <?php selected( $form_template, 'default'); ?>><?php _e('Default','simpleform') ?></option><option value="basic" <?php selected( $form_template, 'basic'); ?>><?php _e('Basic','simpleform') ?></option><option value="rounded" <?php selected( $form_template, 'rounded'); ?>><?php _e('Rounded','simpleform') ?></option><option value="minimal" <?php selected( $form_template, 'minimal'); ?>><?php _e('Minimal','simpleform') ?></option><option value="transparent" <?php selected( $form_template, 'transparent'); ?>><?php _e('Transparent','simpleform') ?></option><option value="highlighted" <?php selected( $form_template, 'highlighted'); ?>><?php _e('Highlighted','simpleform') ?></option><option value="customized" <?php selected( $form_template, 'customized'); ?>><?php _e('Customized','simpleform') ?></option></select><p id="template-notice" class="description"><?php echo $style_notes; ?></p></td></tr>

</tbody></table></div>

<h2 id="h2-custom" class="options-heading"><span class="heading" section="custom"><?php _e( 'Customization', 'simpleform' ); ?><span class="toggle dashicons dashicons-arrow-up-alt2 custom"></span></span><?php if ( $id != '1' ) { ?><a href="<?php echo menu_page_url( 'sform-settings', false ); ?>"><span class="dashicons dashicons-edit icon-button <?php echo $color ?>"></span><span class="settings-page wp-core-ui button"><?php _e( 'Go to main settings for edit', 'simpleform' ) ?></span></a><?php } ?></h2>

<div class="section custom"><table class="form-table custom"><tbody>
	
<tr><th class="option"><span><?php _e('Form CSS Stylesheet','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="stylesheet" id="stylesheet" class="sform-switch" value="false" <?php checked( $stylesheet, 'true'); if ( $id != '1' ) { echo $disabled; } ?>><span></span></label><label for="stylesheet" class="switch-label <?php if ( $id != '1' ) { echo 'disabled'; } ?>"><?php _e( 'Disable the SimpleForm CSS stylesheet and use your own CSS stylesheet','simpleform') ?></label></div></td></tr>

<tr class="trstylesheet <?php if ($stylesheet !='true') { echo 'unseen'; } ?>"><th class="option"><span><?php _e( 'CSS Stylesheet File', 'simpleform' ) ?></span></th><td class="checkbox-switch notes"><div class="switch-box"><label class="switch-input"><input type="checkbox" id="stylesheet-file" name="stylesheet-file" class="sform-switch" value="false" <?php checked( $cssfile, 'true'); if ( $id != '1' ) { echo $disabled; } ?>><span></span></label><label for="stylesheet-file" class="switch-label <?php if ( $id != '1' ) { echo 'disabled'; } ?>"><?php _e( 'Include custom CSS code in a separate file', 'simpleform' ); ?></label></div><p id="stylesheet-description" class="description"><?php echo $css_notes; ?></p></td></tr>

<tr><th class="option"><span><?php _e( 'Custom JavaScript Code', 'simpleform' ) ?></span></th><td class="checkbox-switch last notes"><div class="switch-box"><label class="switch-input"><input type="checkbox" id="javascript" name="javascript" class="sform-switch" value="false" <?php checked( $javascript, 'true'); if ( $id != '1' ) { echo $disabled; } ?>><span></span></label><label for="javascript" class="switch-label <?php if ( $id != '1' ) { echo 'disabled'; } ?>"><?php _e( 'Add your custom JavaScript code to your form', 'simpleform' ); ?></label></div><p id="javascript-description" class="description"><?php echo $js_notes; ?></p></td></tr>

</tbody></table></div>

<h2 id="h2-uninstall" class="options-heading"><span class="heading" section="uninstall"><?php _e( 'Uninstall', 'simpleform' ); ?><span class="toggle dashicons dashicons-arrow-up-alt2 uninstall"></span></span><?php if ( $id != '1' ) { ?><a href="<?php echo menu_page_url( 'sform-settings', false ); ?>"><span class="dashicons dashicons-edit icon-button <?php echo $color ?>"></span><span class="settings-page wp-core-ui button"><?php _e( 'Go to main settings for edit', 'simpleform' ) ?></span></a><?php } ?></h2>

<div class="section uninstall"><table class="form-table uninstall"><tbody>

<tr><th class="option"><span><?php _e('Data Stored','simpleform') ?></span></th><td class="checkbox-switch last"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="deletion" id="deletion" class="sform-switch" value="false" <?php checked( $uninstall, 'true'); if ( $id != '1' ) { echo $disabled; } ?>><span></span></label><label for="deletion" class="switch-label <?php if ( $id != '1' ) { echo 'disabled'; } ?>"><?php _e( 'Delete all data and settings when the plugin is uninstalled','simpleform') ?></label></div></td></tr>

</tbody></table></div>

</div>

<div id="tab-messages" class="navtab unseen">
	
<?php
$out_error = ! empty( $settings['outside_error'] ) ? esc_attr($settings['outside_error']) : 'bottom'; 
switch ($out_error) {
  case 'top':
  $error_notes = __('Display an error message above the form in case of one or more errors in the fields', 'simpleform' );
  break;
  case 'bottom':
  $error_notes = __('Display an error message below the form in case of one or more errors in the fields', 'simpleform' );
  break;
  default:
  $error_notes = "&nbsp;";
}
$multiple_spaces = ! empty( $settings['multiple_spaces'] ) ? esc_attr($settings['multiple_spaces']) : 'false';
$empty_fields = ! empty( $settings['empty_fields'] ) ? stripslashes(esc_attr($settings['empty_fields'])) : esc_attr__ ( 'There were some errors that need to be fixed', 'simpleform' );
$chars_length = ! empty( $settings['characters_length'] ) ? esc_attr($settings['characters_length']) : 'true';
$chars_length_on = __('Keep unchecked if you want to use a generic error message without showing the minimum number of required characters', 'simpleform' );
$chars_length_off = __('Keep checked if you want to show the minimum number of required characters and you want to make sure that\'s exactly the number you set for that specific field', 'simpleform' );
$chars_notes = $chars_length == 'true' ? $chars_length_on : $chars_length_off;
$name_field = ! empty( $attributes['name_field'] ) ? esc_attr($attributes['name_field']) : 'visible';
$lastname_field = ! empty( $attributes['lastname_field'] ) ? esc_attr($attributes['lastname_field']) : 'hidden';
$required_name = ! empty( $attributes['name_requirement'] ) ? esc_attr($attributes['name_requirement']) : 'required';
$required_lastname = ! empty( $attributes['lastname_requirement'] ) ? esc_attr($attributes['lastname_requirement']) : 'optional';
$required_email = ! empty( $attributes['email_requirement'] ) ? esc_attr($attributes['email_requirement']) : 'required';
$required_phone = ! empty( $attributes['phone_requirement'] ) ? esc_attr($attributes['phone_requirement']) : 'optional';
$required_subject = ! empty( $attributes['subject_requirement'] ) ? esc_attr($attributes['subject_requirement']) : 'required';
$email_field = ! empty( $attributes['email_field'] ) ? esc_attr($attributes['email_field']) : 'visible';
$phone_field = ! empty( $attributes['phone_field'] ) ? esc_attr($attributes['phone_field']) : 'hidden';
$subject_field = ! empty( $attributes['subject_field'] ) ? esc_attr($attributes['subject_field']) : 'visible';
$consent_field = ! empty( $attributes['consent_field'] ) ? esc_attr($attributes['consent_field']) : 'visible';
$captcha_field = ! empty( $attributes['captcha_field'] ) ? esc_attr($attributes['captcha_field']) : 'hidden';  
$empty_name = isset( $attributes['empty_name'] ) ? esc_attr($attributes['empty_name']) : esc_attr__( 'Please provide your name', 'simpleform' );
$empty_lastname = isset( $attributes['empty_lastname'] ) ? esc_attr($attributes['empty_lastname']) : esc_attr__( 'Please provide your last name', 'simpleform' );
$empty_email = ! empty( $settings['empty_email'] ) ? stripslashes(esc_attr($settings['empty_email'])) : esc_attr__( 'Please provide your email address', 'simpleform' );
$empty_phone = ! empty( $settings['empty_phone'] ) ? stripslashes(esc_attr($settings['empty_phone'])) : esc_attr__( 'Please provide your phone number', 'simpleform' );
$empty_subject = ! empty( $settings['empty_subject'] ) ? stripslashes(esc_attr($settings['empty_subject'])) : esc_attr__( 'Please enter the request subject', 'simpleform' );
$empty_message = ! empty( $settings['empty_message'] ) ? stripslashes(esc_attr($settings['empty_message'])) : esc_attr__( 'Please enter your message', 'simpleform' );
$empty_captcha = ! empty( $settings['empty_captcha'] ) ? stripslashes(esc_attr($settings['empty_captcha'])) : esc_attr__( 'Please enter an answer', 'simpleform' );
$name_length = isset( $attributes['name_minlength'] ) ? esc_attr($attributes['name_minlength']) : '2';
$name_numeric_error = $chars_length == 'true' && ! empty( $settings['incomplete_name'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_name']) == $name_length ? stripslashes(esc_attr($settings['incomplete_name'])) : sprintf( __('Please enter at least %d characters', 'simpleform' ), $name_length );
$name_generic_error = $chars_length != 'true' && ! empty( $settings['incomplete_name'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_name']) == '' ? stripslashes(esc_attr($settings['incomplete_name'])) : esc_attr__('Please type your full name', 'simpleform' );
$incomplete_name = $chars_length == 'true' ? $name_numeric_error : $name_generic_error;
$lastname_length = isset( $attributes['lastname_minlength'] ) ? esc_attr($attributes['lastname_minlength']) : '2';
$subject_length = isset( $attributes['subject_minlength'] ) ? esc_attr($attributes['subject_minlength']) : '5';
$message_length = isset( $attributes['message_minlength'] ) ? esc_attr($attributes['message_minlength']) : '10';
$invalid_name = ! empty( $settings['invalid_name'] ) ? stripslashes(esc_attr($settings['invalid_name'])) : esc_attr__( 'The name contains invalid characters', 'simpleform' );
$name_error = ! empty( $settings['name_error'] ) ? stripslashes(esc_attr($settings['name_error'])) : esc_attr__( 'Error occurred validating the name', 'simpleform' );
$lastname_numeric_error = $chars_length == 'true' && ! empty( $settings['incomplete_lastname'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_lastname']) == $lastname_length ? stripslashes(esc_attr($settings['incomplete_lastname'])) : sprintf( __('Please enter at least %d characters', 'simpleform' ), $lastname_length );
$lastname_generic_error = $chars_length != 'true' && ! empty( $settings['incomplete_lastname'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_lastname']) == '' ? stripslashes(esc_attr($settings['incomplete_lastname'])) : esc_attr__('Please type your full last name', 'simpleform' );
$incomplete_lastname = $chars_length == 'true' ? $lastname_numeric_error : $lastname_generic_error;
$invalid_lastname = ! empty( $settings['invalid_lastname'] ) ? stripslashes(esc_attr($settings['invalid_lastname'])) : esc_attr__( 'The last name contains invalid characters', 'simpleform' );
$lastname_error = ! empty( $settings['lastname_error'] ) ? stripslashes(esc_attr($settings['lastname_error'])) : esc_attr__( 'Error occurred validating the last name', 'simpleform' );
$invalid_email = ! empty( $settings['invalid_email'] ) ? stripslashes(esc_attr($settings['invalid_email'])) : esc_attr__( 'Please enter a valid email', 'simpleform' );
$email_error = ! empty( $settings['email_error'] ) ? stripslashes(esc_attr($settings['email_error'])) : esc_attr__( 'Error occurred validating the email', 'simpleform' );
$invalid_phone = ! empty( $settings['invalid_phone'] ) ? stripslashes(esc_attr($settings['invalid_phone'])) : esc_attr__( 'The phone number contains invalid characters', 'simpleform' );
$phone_error = ! empty( $settings['phone_error'] ) ? stripslashes(esc_attr($settings['phone_error'])) : esc_attr__( 'Error occurred validating the phone number', 'simpleform' );
$subject_numeric_error = $chars_length == 'true' && ! empty( $settings['incomplete_subject'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_subject']) == $subject_length ? stripslashes(esc_attr($settings['incomplete_subject'])) : sprintf( __('Please enter a subject at least %d characters long', 'simpleform' ), $subject_length );
$subject_generic_error = $chars_length != 'true' && ! empty( $settings['incomplete_subject'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_subject']) == '' ? stripslashes(esc_attr($settings['incomplete_subject'])) : esc_attr__('Please type a short and specific subject', 'simpleform' );
$incomplete_subject = $chars_length == 'true' ? $subject_numeric_error : $subject_generic_error;
$invalid_subject = ! empty( $settings['invalid_subject'] ) ? stripslashes(esc_attr($settings['invalid_subject'])) : esc_attr__( 'Enter only alphanumeric characters and punctuation marks', 'simpleform' );
$subject_error = ! empty( $settings['subject_error'] ) ? stripslashes(esc_attr($settings['subject_error'])) : esc_attr__( 'Error occurred validating the subject', 'simpleform' );
$message_numeric_error = $chars_length == 'true' && ! empty( $settings['incomplete_message'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_message']) == $message_length ? stripslashes(esc_attr($settings['incomplete_message'])) : sprintf( __('Please enter a message at least %d characters long', 'simpleform' ), $message_length );
$message_generic_error = $chars_length != 'true' && ! empty( $settings['incomplete_message'] ) && preg_replace('/[^0-9]/', '', $settings['incomplete_message']) == '' ? stripslashes(esc_attr($settings['incomplete_message'])) : esc_attr__('Please type a clearer message so we can respond appropriately', 'simpleform' );
$incomplete_message = $chars_length == 'true' ? $message_numeric_error : $message_generic_error;
$invalid_message = ! empty( $settings['invalid_message'] ) ? stripslashes(esc_attr($settings['invalid_message'])) : esc_attr__( 'Enter only alphanumeric characters and punctuation marks', 'simpleform' );
$message_error = ! empty( $settings['message_error'] ) ? stripslashes(esc_attr($settings['message_error'])) : esc_attr__( 'Error occurred validating the message', 'simpleform' );
$consent_error = ! empty( $settings['consent_error'] ) ? stripslashes(esc_attr($settings['consent_error'])) : esc_attr__( 'Please accept our privacy policy before submitting form', 'simpleform' );
$invalid_captcha = ! empty( $settings['invalid_captcha'] ) ? stripslashes(esc_attr($settings['invalid_captcha'])) : esc_attr__( 'Please enter a valid captcha value', 'simpleform' );
$captcha_error = ! empty( $settings['captcha_error'] ) ? stripslashes(esc_attr($settings['captcha_error'])) : esc_attr__( 'Error occurred validating the captcha', 'simpleform' );
$honeypot_error = ! empty( $settings['honeypot_error'] ) ? stripslashes(esc_attr($settings['honeypot_error'])) : esc_attr__( 'Failed honeypot validation', 'simpleform' );
$server_error = ! empty( $settings['server_error'] ) ? stripslashes(esc_attr($settings['server_error'])) : esc_attr__( 'Error occurred during processing data. Please try again!', 'simpleform' );
$ajax_error = ! empty( $settings['ajax_error'] ) ? stripslashes(esc_attr($settings['ajax_error'])) : esc_attr__( 'Error occurred during AJAX request. Please contact support!', 'simpleform' );
$success_action = ! empty( $settings['success_action'] ) ? esc_attr($settings['success_action']) : 'message';
$confirmation_img = SIMPLEFORM_URL . 'public/img/confirmation.png';
$confirmation_page = ! empty( $settings['confirmation_page'] ) ? esc_attr($settings['confirmation_page']) : '';
$edit_post_link = '<strong><a href="' . get_edit_post_link($confirmation_page) . '" target="_blank" class="publish-link">' . __( 'Publish now','simpleform') . '</a></strong>';
/* translators: Used in place of %1$s in the string: "%1$s or %2$s the page content" */
$edit = __( 'Edit','simpleform');
/* translators: Used in place of %2$s in the string: "%1$s or %2$s the page content" */
$view = __( 'view','simpleform');
$post_url = $confirmation_page != '' ? sprintf( __('%1$s or %2$s the page content', 'simpleform'), '<strong><a href="' . get_edit_post_link($confirmation_page) .'" target="_blank" style="text-decoration: none;">'. $edit .'</a></strong>', '<strong><a href="' . get_page_link($confirmation_page) . '" target="_blank" style="text-decoration: none;">'. $view .'</a></strong>' ) : '&nbsp;'; 
$post_status = $confirmation_page != '' && get_post_status($confirmation_page) == 'draft' ? __( 'Page in draft status not yet published','simpleform').'&nbsp;-&nbsp;'.$edit_post_link : $post_url;
$thank_string1 = __( 'We have received your request!', 'simpleform' );
$thank_string2 = __( 'Your message will be reviewed soon, and we\'ll get back to you as quickly as possible.', 'simpleform' );
$thank_you_message = ! empty( $settings['success_message'] ) ? stripslashes(esc_attr($settings['success_message'])) : '<div class="form confirmation" tabindex="-1"><h4>' . $thank_string1 . '</h4><br>' . $thank_string2 . '</br><img src="'.$confirmation_img.'" alt="message received"></div>';
if ( $out_error == 'top' ) {
/* translators: Used in place of %s in the string: "Please enter an error message to be displayed on %s of the form" */
$error_position = __('top', 'simpleform');
} else {
/* translators: Used in place of %s in the string: "Please enter an error message to be displayed on %s of the form" */
$error_position = __('bottom', 'simpleform');
}
$duplicate_error = ! empty( $settings['duplicate_error'] ) ? stripslashes(esc_attr($settings['duplicate_error'])) : esc_attr__( 'The form has already been submitted. Thanks!', 'simpleform' );
$duplicate = ! empty( $settings['duplicate'] ) ? esc_attr($settings['duplicate']) : 'true';	
?>	

<h2 id="h2-rules" class="options-heading"><span class="heading" section="rules"><?php _e( 'Fields Validation Rules', 'simpleform' ); ?><span class="toggle dashicons dashicons-arrow-up-alt2 rules"></span></span></h2>

<div class="section rules"><table class="form-table rules"><tbody>
	
<tr><th class="option"><span><?php _e('Multiple spaces','simpleform') ?></span></th><td class="checkbox-switch last"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="multiple-spaces" id="multiple-spaces" class="sform-switch" value="false" <?php checked( $multiple_spaces, 'true'); ?>><span></span></label><label for="multiple-spaces" class="switch-label"><?php _e( 'Prevent the user from entering multiple white spaces in the fields','simpleform') ?></label></div></td></tr>

</tbody></table></div>

<h2 id="h2-fields" class="options-heading"><span class="heading" section="fields"><?php _e( 'Fields Error Messages', 'simpleform' ); ?><span class="toggle dashicons dashicons-arrow-up-alt2 fields"></span></span></h2>

<div class="section fields"><table class="form-table fields"><tbody>

<tr><th class="option"><span><?php _e('Error Message Outside','simpleform') ?></span></th><td class="select notes"><select name="outside-error" id="outside-error" class="sform"><option value="top" <?php selected( $out_error, 'top'); ?>><?php _e('Above the form','simpleform') ?></option><option value="bottom" <?php selected( $out_error, 'bottom'); ?>><?php _e('Below the form','simpleform') ?></option><option value="none" <?php selected( $out_error, 'none'); ?>><?php _e('Do not display','simpleform') ?></option></select><p id="outside-notice" class="description"><?php echo $error_notes ?></p></td></tr>

<tr class="trout <?php if ( $out_error == 'none' ) {echo 'removed';}?>"><th id="" class="option"><span><?php _e('Multiple Fields Error','simpleform') ?></span></th><td id="" class="text"><input id="empty-fields" name="empty-fields" class="sform out" placeholder="<?php printf( __( 'Please enter an error message to be displayed on %s of the form in case of multiple empty fields', 'simpleform' ), $error_position ) ?>"  type="text" value="<?php echo $empty_fields; ?>" \></td></tr>

<tr><th class="option"><span><?php _e( 'Length Error Type', 'simpleform' ) ?></span></th><td class="checkbox-switch notes"><div class="switch-box"><label class="switch-input"><input type="checkbox" id="characters-length" name="characters-length" class="sform-switch" value="true" <?php checked( $chars_length, 'true'); ?>><span></span></label><label for="characters-length" class="switch-label"><?php _e( 'Include the minimum number of required characters in length error message', 'simpleform' ); ?></label></div><p id="characters-description" class="description"><?php echo $chars_notes; ?></p></td></tr>

<tr class="<?php if ( $name_field =='hidden' || $required_name == 'optional' ) {echo 'unseen';}?>"><th class="option"><span><?php _e('Empty Name Field Error','simpleform') ?></span></th><td class="text"><input class="sform" name="empty-name" placeholder="<?php esc_attr_e('Please enter an inline error message to be displayed below the field if the name field is empty','simpleform') ?>" id="empty-name" type="text" value="<?php echo $empty_name; ?>" \></td></tr>

<tr class="<?php if ( $name_field =='hidden' || $name_length == 0) {echo 'unseen';}?>"><th class="option"><span><?php _e('Name Length Error','simpleform') ?></span></th><td class="text"><input class="sform" name="incomplete-name" placeholder="<?php esc_attr_e('Please enter an inline error message to be displayed below the field if the name is not long enough','simpleform') ?>" id="incomplete-name" type="text" value="<?php echo $incomplete_name; ?>" \></td></tr>
        
<tr class="<?php if ( $name_field =='hidden' ) {echo 'unseen';}?>"><th class="option"><span><?php _e('Invalid Name Error','simpleform') ?></span></th><td class="text"><input type="text" class="sform" id="invalid-name" name="invalid-name" placeholder="<?php esc_attr_e('Please enter an inline error message to be displayed below the field in case of an invalid name','simpleform') ?>" value="<?php echo $invalid_name; ?>" \></td></tr>

<tr class="trout <?php if ( $name_field =='hidden' ) {echo 'unseen ';} if ( $out_error == 'none' ) {echo 'removed';} ?>"><th class="option"><span><?php _e('Name Field Error','simpleform') ?></span></th><td class="text"><input class="sform out" name="name-error" placeholder="<?php printf( __( 'Please enter an error message to be displayed on %s of the form in case of an error in the name field', 'simpleform' ), $error_position ) ?>" id="name-error" type="text" value="<?php echo $name_error; ?>" \></td></tr>

<tr class="<?php if ( $lastname_field =='hidden' || $required_lastname == 'optional' ) {echo 'unseen';}?>"><th class="option"><span><?php _e('Empty Last Name Field Error','simpleform') ?></span></th><td class="text"><input class="sform" name="empty-lastname" placeholder="<?php esc_attr_e('Please enter an inline error message to be displayed below the field if the last name field is empty','simpleform') ?>" id="empty-lastname" type="text" value="<?php echo $empty_lastname; ?>" \></td></tr>
		
<tr class="<?php if ( $lastname_field =='hidden' || $lastname_length == 0) {echo 'unseen';}?>"><th class="option"><span><?php _e('Last Name Length Error','simpleform') ?></span></th><td class="text"><input class="sform" name="incomplete-lastname" placeholder="<?php esc_attr_e('Please enter an inline error message to be displayed below the field if the last name is not long enough','simpleform') ?>" id="incomplete-lastname" type="text" value="<?php echo $incomplete_lastname; ?>" \></td></tr>
        
<tr class="<?php if ( $lastname_field =='hidden' ) {echo 'unseen';}?>"><th class="option"><span><?php _e('Invalid Last Name Error','simpleform') ?></span></th><td class="text"><input type="text" class="sform" id="invalid-lastname" name="invalid-lastname" placeholder="<?php esc_attr_e('Please enter an inline error message to be displayed below the field in case of an invalid last name','simpleform') ?>" value="<?php echo $invalid_lastname; ?>" \></td></tr>

<tr class="trout <?php if ( $lastname_field =='hidden' ) {echo 'unseen ';} if ( $out_error == 'none' ) {echo 'removed';} ?>"><th class="option"><span><?php _e('Last Name Field Error','simpleform') ?></span></th><td class="text"><input class="sform out" name="lastname-error" placeholder="<?php printf( __( 'Please enter an error message to be displayed on %s of the form in case of an error in the last name field', 'simpleform' ), $error_position ) ?>" id="lastname-error" type="text" value="<?php echo $lastname_error; ?>" \></td></tr>

<tr class="<?php if ( $email_field =='hidden' || $required_email == 'optional' ) {echo 'unseen';}?>"><th class="option"><span><?php _e('Empty Email Field Error','simpleform') ?></span></th><td class="text"><input class="sform" name="empty-email" placeholder="<?php esc_attr_e('Please enter an inline error message to be displayed below the field if the email field is empty','simpleform') ?>" id="empty-email" type="text" value="<?php echo $empty_email; ?>" \></td></tr>

<tr class="<?php if ( $email_field =='hidden' ) {echo 'unseen';}?>"><th class="option"><span><?php _e('Invalid Email Error','simpleform') ?></span></th><td class="text"><input class="sform" name="invalid-email" placeholder="<?php esc_attr_e('Please enter an inline error message to be displayed below the field in case of an invalid email address','simpleform') ?>" id="invalid-email" type="text" value="<?php echo $invalid_email; ?>" \></td></tr>

<tr class="trout <?php if ( $email_field =='hidden' ) {echo 'unseen ';} if ( $out_error == 'none' ) {echo 'removed';} ?>"><th class="option"><span><?php _e('Email Field Error','simpleform') ?></span></th><td class="text"><input class="sform out" name="email-error" placeholder="<?php printf( __( 'Please enter an error message to be displayed on %s of the form in case of an error in the email field', 'simpleform' ), $error_position ) ?>" id="email-error" type="text" value="<?php echo $email_error; ?>" \></td></tr>

<tr class="<?php if ( $phone_field =='hidden' || $required_phone == 'optional' ) {echo 'unseen';}?>"><th class="option"><span><?php _e('Empty Phone Field Error','simpleform') ?></span></th><td class="text"><input class="sform" name="empty-phone" placeholder="<?php esc_attr_e('Please enter an inline error message to be displayed below the field if the phone field is empty','simpleform') ?>" id="empty-phone" type="text" value="<?php echo $empty_phone; ?>" \></td></tr>

<tr class="<?php if ( $phone_field =='hidden' ) {echo 'unseen';}?>"><th class="option"><span><?php _e('Invalid Phone Error','simpleform') ?></span></th><td class="text"><input class="sform" name="invalid-phone" placeholder="<?php esc_attr_e('Please enter an inline error message to be displayed below the field in case of an invalid phone number','simpleform') ?>" id="invalid-phone" type="text" value="<?php echo $invalid_phone; ?>" \></td></tr>

<tr class="trout <?php if ( $phone_field =='hidden' ) {echo 'unseen ';} if ( $out_error == 'none' ) {echo 'removed';} ?>"><th class="option"><span><?php _e('Phone Field Error','simpleform') ?></span></th><td class="text"><input class="sform out" name="phone-error" placeholder="<?php printf( __( 'Please enter an error message to be displayed on %s of the form in case of an error in the phone field', 'simpleform' ), $error_position ) ?>" id="phone-error" type="text" value="<?php echo $phone_error; ?>" \></td></tr>

<tr class="<?php if ( $subject_field =='hidden' || $required_subject == 'optional') {echo 'unseen';}?>"><th class="option"><span><?php _e('Empty Subject Field Error','simpleform') ?></span></th><td class="text"><input class="sform" name="empty-subject" placeholder="<?php esc_attr_e('Please enter an inline error message to be displayed below the field if the subject field is empty','simpleform') ?>" id="empty-subject" type="text" value="<?php echo $empty_subject; ?>" \></td></tr>

<tr class="<?php if ( $subject_field =='hidden' || $subject_length == 0) {echo 'unseen';}?>"><th class="option"><span><?php _e('Subject Length Error','simpleform') ?></span></th><td class="text"><input type="text" class="sform" id="incomplete-subject" name="incomplete-subject" placeholder="<?php esc_attr_e('Please enter an inline error message to be displayed below the field if the subject is not long enough','simpleform') ?>" value="<?php echo $incomplete_subject; ?>" \></td></tr>

<tr class="<?php if ( $subject_field =='hidden' ) {echo 'unseen';}?>"><th class="option"><span><?php _e('Invalid Subject Error','simpleform') ?></span></th><td class="text"><input class="sform" name="invalid-subject" placeholder="<?php esc_attr_e('Please enter an inline error message to be displayed below the field in case of an invalid subject','simpleform') ?>" id="invalid-subject" type="text" value="<?php echo $invalid_subject; ?>" \></td></tr>

<tr class="trout <?php if ( $subject_field =='hidden' ) {echo 'unseen ';} if ( $out_error == 'none' ) {echo 'removed';} ?>"><th class="option"><span><?php _e('Subject Field Error','simpleform') ?></span></th><td class="text"><input class="sform out" name="subject-error" placeholder="<?php printf( __( 'Please enter an error message to be displayed on %s of the form in case of an error in the subject field', 'simpleform' ), $error_position ) ?>" id="subject-error" type="text" value="<?php echo $subject_error; ?>" \></td></tr>

<tr><th class="option"><span><?php _e('Empty Message Field Error','simpleform') ?></span></th><td class="text"><input class="sform" name="empty-message" placeholder="<?php esc_attr_e('Please enter an inline error message to be displayed below the field if the message field is empty','simpleform') ?>" id="empty-message" type="text" value="<?php echo $empty_message; ?>" \></td></tr>

<tr><th class="option"><span><?php _e('Message Length Error','simpleform') ?></span></th><td class="text"><input type="text" class="sform" name="incomplete-message" placeholder="<?php esc_attr_e('Please enter an inline error message to be displayed below the field if the message is not long enough','simpleform') ?>" id="incomplete-message"  value="<?php echo $incomplete_message; ?>" \></td></tr>
		
<tr><th class="messagecell option"><span><?php _e('Invalid Message Error','simpleform') ?></span></th><td class="messagecell text <?php if ( $out_error == 'none' && $captcha_field == 'hidden' ) {echo 'last';} ?>"><input class="sform" name="invalid-message" placeholder="<?php esc_attr_e('Please enter an inline error message to be displayed below the field in case of an invalid message','simpleform') ?>" id="invalid-message" type="text" value="<?php echo $invalid_message; ?>" \></td></tr>

<tr class="trout <?php if ( $out_error == 'none' ) {echo 'removed';}?>"><th class="option"><span><?php _e('Message Field Error','simpleform') ?></span></th><td class="text <?php if ( $consent_field == 'hidden' && $captcha_field =='hidden' ) {echo 'last';} ?>"><input class="sform out" name="message-error" placeholder="<?php printf( __( 'Please enter an error message to be displayed on %s of the form in case of an error in the message field', 'simpleform' ), $error_position ) ?>" id="message-error" type="text" value="<?php echo $message_error; ?>" \></td></tr>

<tr class="trout <?php if ( $consent_field =='hidden' ) {echo 'unseen ';} if ( $out_error == 'none' ) {echo 'removed';} ?>"><th class="option"><span><?php _e('Consent Field Error','simpleform') ?></span></th><td class="text <?php if ( $captcha_field =='hidden') {echo 'last';}?>"><input class="sform out" name="consent-error" placeholder="<?php printf( __( 'Please enter an error message to be displayed on %s of the form in case the consent is not provided', 'simpleform' ), $error_position ) ?>" id="consent-error" type="text" value="<?php echo $consent_error; ?>" \></td></tr>

<tr class="<?php if ( $captcha_field =='hidden') {echo 'unseen';}?>"><th class="option"><span><?php _e('Empty Captcha Field Error','simpleform') ?></span></th><td class="text"><input class="sform" name="empty-captcha" placeholder="<?php esc_attr_e('Please enter an inline error message to be displayed below the field in case of an empty captcha field','simpleform') ?>" id="empty-captcha" type="text" value="<?php echo $empty_captcha; ?>" \></td></tr>

<tr id="trcaptcha" class="<?php if ( $captcha_field =='hidden' ) {echo 'unseen';}?>"><th class="captchacell option"><span><?php _e('Invalid Captcha Error','simpleform') ?></span></th><td class="captchacell text <?php if ( $out_error == 'none' ) {echo 'last';} ?>"><input class="sform" name="invalid-captcha" placeholder="<?php esc_attr_e('Please enter an inline error message to be displayed below the field in case of invalid captcha value','simpleform') ?>" id="invalid-captcha" type="text" value="<?php echo $invalid_captcha; ?>" \></td></tr>

<tr class="trout <?php if ( $captcha_field =='hidden' ) {echo 'unseen ';} if ( $out_error == 'none' ) {echo 'removed';} ?>"><th class="option"><span><?php _e('Captcha Field Error','simpleform') ?></span></th><td class="text last"><input class="sform out" name="captcha-error" placeholder="<?php printf( __( 'Please enter an error message to be displayed on %s of the form in case of error in captcha field', 'simpleform' ), $error_position ) ?>" id="captcha-error" type="text" value="<?php echo $captcha_error; ?>" \></td></tr>

</tbody></table></div>

<h2 id="h2-sending" class="options-heading"><span class="heading" section="sending"><?php _e( 'Submission Error Messages', 'simpleform' ); ?><span class="toggle dashicons dashicons-arrow-up-alt2 sending"></span></span></h2>

<div class="section sending"><table class="form-table sending"><tbody>
	
<tr><th class="option"><span><?php _e('Honeypot Error','simpleform') ?></span></th><td class="text"><input class="sform out" name="honeypot-error" placeholder="<?php printf( __( 'Please enter an error message to be displayed on %s of the form in case a honeypot field is filled in', 'simpleform' ), $error_position ) ?>" id="honeypot-error" type="text" value="<?php echo $honeypot_error; ?>" \></td></tr>

<tr class="trduplicate <?php if ( $duplicate != 'true' ) {echo 'unseen';}?>"><th class="option"><span><?php _e( 'Duplicate Submission Error','simpleform') ?></span></th><td id="tdduplicate" class="text"><input class="sform out" name="duplicate-error" placeholder="<?php printf( __( 'Please enter an error message to be displayed on %s of the form in case of duplicate form submission', 'simpleform' ), $error_position ) ?>" id="duplicate-error" type="text" value="<?php echo $duplicate_error; ?>" \></td></tr>

<?php
echo apply_filters( 'akismet_error_message', $extra_option );
echo apply_filters( 'recaptcha_error_message', $extra_option );
?>

<tr class="trajax <?php if ( $ajax != 'true' ) {echo 'unseen';}?>"><th class="option"><span><?php _e('AJAX Error','simpleform') ?></span></th><td class="text"><input class="sform out" name="ajax-error" placeholder="<?php printf( __( 'Please enter an error message to be displayed on %s of the form when the AJAX request fails', 'simpleform' ), $error_position ) ?>" id="ajax-error" type="text" value="<?php echo $ajax_error; ?>" \></td></tr>

<tr><th class="option"><span><?php _e( 'Server Error','simpleform') ?></span></th><td class="text last"><input class="sform out" name="server-error" placeholder="<?php printf( __( 'Please enter an error message to be displayed on %s of the form in case an error occurs during data processing', 'simpleform' ), $error_position ) ?>" id="server-error" type="text" value="<?php echo $server_error; ?>" \></td></tr>

</tbody></table></div>

<h2 id="h2-success" class="options-heading"><span class="heading" section="success"><?php _e( 'Success Message', 'simpleform' ); ?><span class="toggle dashicons dashicons-arrow-up-alt2 success"></span></span></h2>

<div class="section success"><table class="form-table success"><tbody>

<tr><th class="option"><span><?php _e( 'Action After Submission', 'simpleform' ) ?></span></th><td class="radio"><fieldset><label for="confirmation-message"><input id="confirmation-message" type="radio" name="success-action" value="message" <?php checked( $success_action, 'message'); ?> ><?php _e( 'Display confirmation message','simpleform') ?></label><label for="success-redirect"><input id="success-redirect" type="radio" name="success-action" value="redirect" <?php checked( $success_action, 'redirect'); ?> ><?php _e( 'Redirect to confirmation page','simpleform') ?></label></fieldset></td></tr>
 
<tr class="trsuccessmessage <?php if ($success_action !='message') { echo 'unseen'; }?>"><th class="option"><span><?php _e( 'Confirmation Message', 'simpleform' ) ?></span></th><td class="textarea"><textarea class="sform" name="success-message" id="success-message" placeholder="<?php esc_attr_e( 'Please enter a thank you message when the form is submitted', 'simpleform' ) ?>" ><?php echo $thank_you_message; ?></textarea><p class="description"><?php _e( 'The HTML tags for formatting message are allowed', 'simpleform' ) ?></p></td></tr>
				
<tr class="trsuccessredirect <?php if ($success_action !='redirect') { echo 'unseen'; }?>" ><th class="option"><span><?php _e( 'Confirmation Page', 'simpleform' ) ?></span></th><td class="last select notes"><?php $pages = get_pages( array( 'sort_column' => 'post_title', 'sort_order' => 'ASC', 'post_type' => 'page', 'post_status' =>  array('publish','draft') ) ); if ( $pages ) { ?><select name="confirmation-page" class="sform" id="confirmation-page"><option value=""><?php _e( 'Select the page to which the user is redirected when the form is sent', 'simpleform' ) ?></option><?php foreach ($pages as $page) { ?><option value="<?php echo $page->ID; ?>" tag="<?php echo $page->post_status; ?>" slug="<?php echo $page->post_name; ?>"<?php selected( $confirmation_page, $page->ID ); ?>><?php echo $page->post_title; ?></option><?php } ?></select><?php } ?><p id="post-status" class="description"><?php echo $post_status ?></p></td></tr>

</tbody></table></div>

</div>

<div id="tab-email" class="navtab unseen">

<?php
$smtp = ! empty( $settings['server_smtp'] ) ? esc_attr($settings['server_smtp']) : 'false';
$smtp_host = ! empty( $settings['smtp_host'] ) ? esc_attr($settings['smtp_host']) : '';
$smtp_notes = $smtp == 'true' ? __('Uncheck if you want to use a dedicated plugin to take care of outgoing email', 'simpleform' ) : '&nbsp;';
$smtp_encryption = ! empty( $settings['smtp_encryption'] ) ? esc_attr($settings['smtp_encryption']) : 'ssl';
$smtp_port = ! empty( $settings['smtp_port'] ) ? esc_attr($settings['smtp_port']) : '465';
$smtp_authentication = ! empty( $settings['smtp_authentication'] ) ? esc_attr($settings['smtp_authentication']) : 'true';
$smtp_username = ! empty( $settings['smtp_username'] ) ? stripslashes(esc_attr($settings['smtp_username'])) : '';
$smtp_password = ! empty( $settings['smtp_password'] ) ? stripslashes(esc_attr($settings['smtp_password'])) : '';
$username_placeholder = defined( 'SFORM_SMTP_USERNAME' ) && ! empty(trim(SFORM_SMTP_USERNAME)) ? SFORM_SMTP_USERNAME : esc_attr__( 'Enter the username for SMTP authentication', 'simpleform' ); 
$password_placeholder = defined( 'SFORM_SMTP_PASSWORD' ) && ! empty(trim(SFORM_SMTP_PASSWORD)) ? '•••••••••••••••' : esc_attr__( 'Enter the password for SMTP authentication', 'simpleform' );
$notification = ! empty( $settings['notification'] ) ? esc_attr($settings['notification']) : 'true';
$notification_recipient = ! empty( $settings['notification_recipient'] ) ? esc_attr($settings['notification_recipient']) : esc_attr( get_option( 'admin_email' ) );
$notification_email = ! empty( $settings['notification_email'] ) ? esc_attr($settings['notification_email']) : esc_attr( get_option( 'admin_email' ) );
$notification_name = ! empty( $settings['notification_name'] ) ? esc_attr($settings['notification_name']) : 'requester';
$custom_sender = ! empty( $settings['custom_sender'] ) ? stripslashes(esc_attr($settings['custom_sender'])) : esc_attr( get_bloginfo( 'name' ) );
$notification_subject = ! empty( $settings['notification_subject'] ) ? esc_attr($settings['notification_subject']) : 'request';
$custom_subject = ! empty( $settings['custom_subject'] ) ? stripslashes(esc_attr($settings['custom_subject'])) : esc_attr__('New Contact Request', 'simpleform');
$notification_reply = ! empty( $settings['notification_reply'] ) ? esc_attr($settings['notification_reply']) : 'true';
$bcc = ! empty( $settings['bcc'] ) ? esc_attr($settings['bcc']) : '';
$submission_number = ! empty( $settings['submission_number'] ) ? esc_attr($settings['submission_number']) : 'visible';
$auto = ! empty( $settings['autoresponder'] ) ? esc_attr($settings['autoresponder']) : 'false';	
$auto_email = ! empty( $settings['autoresponder_email'] ) ? esc_attr($settings['autoresponder_email']) : esc_attr( get_option( 'admin_email' ) );
$auto_name = ! empty( $settings['autoresponder_name'] ) ? stripslashes(esc_attr($settings['autoresponder_name'])) : esc_attr( get_bloginfo( 'name' ) );
$auto_subject = ! empty( $settings['autoresponder_subject'] ) ? stripslashes(esc_attr($settings['autoresponder_subject'])) : esc_attr__( 'Your request has been received. Thanks!', 'simpleform' );
$code_name = '[name]';
$auto_message = ! empty( $settings['autoresponder_message'] ) ? stripslashes(esc_attr($settings['autoresponder_message'])) : sprintf(__( 'Hi %s', 'simpleform' ),$code_name) . ',<p>' . __( 'We have received your request. It will be reviewed soon and we\'ll get back to you as quickly as possible.', 'simpleform' ) . '<p>' . __( 'Thanks,', 'simpleform' ) . '<br>' . __( 'The Support Team', 'simpleform' );          
$auto_reply = ! empty( $settings['autoresponder_reply'] ) ? esc_attr($settings['autoresponder_reply']) : $auto_email;
?>	  	
	
<h2 id="h2-smtp" class="options-heading"><span class="heading" section="smtp"><?php _e( 'SMTP Server Configuration', 'simpleform' ); ?><span class="toggle dashicons dashicons-arrow-up-alt2 smtp"></span></span><?php if ( $id != '1' ) { ?><a href="<?php echo menu_page_url( 'sform-settings', false ); ?>"><span class="dashicons dashicons-edit icon-button <?php echo $color ?>"></span><span class="settings-page wp-core-ui button"><?php _e( 'Go to main settings for edit', 'simpleform' ) ?></span></a><?php } else { ?> <span class="notice-toggle"><span class="dashicons dashicons-editor-help icon-button <?php echo $color ?>"></span><span id="smpt-warnings" class="text wp-core-ui button <?php echo $color ?>"><?php _e( 'Show Configuration Warnings', 'simpleform' ) ?></span></span><?php } ?></h2>

<div class="section smtp"><table class="form-table smtp"><tbody>

<tr class="smtp smpt-warnings unseen"><td colspan="2"><div class="description"><h4><?php _e( 'Improve the email deliverability from your website by configuring WordPress to work with an SMTP server', 'simpleform' ) ?></h4><?php _e( 'By default, WordPress uses the PHP mail() function to send emails; a basic feature in built-in PHP. However, if your own website is hosted on a shared server, it is very likely that the mail() function has been disabled by your own hosting provider, due to the abuse risk it presents. If you are experiencing problems with email reception, that may be exactly the reason why you\'re not receiving emails. The best and recommended solution is to use an SMTP server to send all outgoing emails; a dedicated machine that takes care of the whole email delivery process. One important function of the SMTP server is to prevent spam, by using authentication mechanisms that only allow authorized users to deliver emails. So, using an SMTP server for outgoing email makes it less likely that email sent out from your website is marked as spam, and lowers the risk of email getting lost somewhere. As the sender, you have a choice of multiple SMTP servers to forward your emails: you can choose your internet service provider, your email provider, your hosting service provider, you can use a specialized provider, or you can even use your personal SMTP server. Obviously, the best option would be the specialized provider, but it is not necessary to subscribe to a paid service to have a good service, especially if you do not have any special needs, and you do not need to send marketing or transactional emails. We suggest you use your own hosting service provider\'s SMTP server, or your own email provider, initially. If you have a hosting plan, you just need to create a new email account that uses your domain name, if you haven\'t done so already. Then use the configuration information that your hosting provider gives you to connect to its own SMTP server, by filling all the fields in this section. If you haven\'t got a hosting plan yet, and your website is still running on a local host, you can use your preferred email address to send email; just enter the data provided by your email provider (Gmail, Yahoo, Hotmail, etc...). Don\'t forget to enable less secure apps on your email account. Furthermore, be careful to enter only your email address for that account, or an alias, into the "From Email" and the "Reply To" fields, since public SMTP servers have particularly strong spam filters, and do not allow you to override the email headers. Always remember to change the configuration data as soon as your website is put online, because your hosting provider may block outgoing SMTP connections. If you want to continue using your preferred email address, ask your hosting provider if the port used is open for outbound traffic.', 'simpleform' ) ?><p><?php printf( __('The SMPT login credentials are stored in your website database. We highly recommend that you set up your login credentials in your WordPress configuration file for improved security. To do this, leave the %1$s field and the %2$s field blank and add the lines below to your %3$s file:', 'simpleform'), '<i>SMTP Username</i>', '<i>SMTP Password</i>', '<code>wp-config.php</code>' ) ?></p><pre><?php echo 'define( \'SFORM_SMTP_USERNAME\', \'email\' ); // ' .  __('Your full email address (e.g. name@domain.com)', 'simpleform' ) ?><br><?php echo 'define( \'SFORM_SMTP_PASSWORD\', \'password\' ); // '. __('Your account\'s password', 'simpleform' ); ?></pre><?php _e( 'Anyway, this section is optional. Ignore it and do not enter data if you want to use a dedicated plugin to take care of outgoing email or if you don\'t have to.', 'simpleform' ) ?></p></div></td></tr>

<tr id="trsmtpon" class="smtp smpt-settings"><th class="option"><span><?php _e('SMTP Server','simpleform') ?></span></th><td id="tdsmtp" class="checkbox-switch notes  <?php if ($smtp !='true') { echo 'last'; } ?>"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="server-smtp" id="server-smtp" class="sform-switch" value="false" <?php checked( $smtp, 'true'); if ( $id != '1' ) { echo $disabled; } ?>><span></span></label><label for="server-smtp" class="switch-label <?php if ( $id != '1' ) { echo 'disabled'; } ?>"><?php _e('Enable an SMTP server for outgoing email, if you haven\'t done so already','simpleform') ?></label></div><p id="smtp-notice" class="description"><?php echo $smtp_notes ?></p></td></tr>

<tr class="smtp smpt-settings trsmtp <?php if ($smtp !='true') { echo 'unseen'; }?>"><th class="option"><span><?php _e( 'SMTP Host Address', 'simpleform' ) ?></span></th><td class="text notes"><input class="sform" name="smtp-host" placeholder="<?php esc_attr_e( 'Enter the server address for outgoing email', 'simpleform' ) ?>" id="smtp-host" type="text" value="<?php echo $smtp_host; ?>" <?php if ( $id != '1' ) { echo $disabled; } ?>\><p class="description"><?php _e( 'Your outgoing email server address', 'simpleform' ) ?></p></td></tr>	
		
<tr class="smtp smpt-settings trsmtp <?php if ($smtp !='true') { echo 'unseen'; }?>" ><th class="option" ><span><?php _e( 'Type of Encryption', 'simpleform' ) ?></span></th><td class="radio notes"><fieldset><label for="no-encryption" class="radio <?php if ( $id != '1' ) { echo 'disabled'; } ?>"><input id="no-encryption" type="radio" name="smtp-encryption" value="none" <?php checked( $smtp_encryption, 'none'); if ( $id != '1' ) { echo $disabled; } ?> ><?php _e( 'None','simpleform') ?></label><label for="ssl-encryption" class="radio <?php if ( $id != '1' ) { echo 'disabled'; } ?>"><input id="ssl-encryption" type="radio" name="smtp-encryption" value="ssl" <?php checked( $smtp_encryption, 'ssl'); if ( $id != '1' ) { echo $disabled; } ?> ><?php _e( 'SSL','simpleform') ?></label><label for="tls-encryption" class="radio <?php if ( $id != '1' ) { echo 'disabled'; } ?>"><input id="tls-encryption" type="radio" name="smtp-encryption" value="tls" <?php checked( $smtp_encryption, 'tls'); if ( $id != '1' ) { echo $disabled; } ?> ><?php _e( 'TLS','simpleform') ?></label></fieldset><p class="description"><?php _e( 'If your SMTP provider supports both SSL and TLS options, we recommend using TLS encryption', 'simpleform' ) ?></p></td></tr>

<tr class="smtp smpt-settings trsmtp <?php if ($smtp !='true') { echo 'unseen'; }?>" ><th class="option"><span><?php _e( 'SMTP Port', 'simpleform' ) ?></span></th><td class="text notes"><input name="smtp-port" id="smtp-port" type="number" class="sform" value="<?php echo $smtp_port;?>" maxlength="4" <?php if ( $id != '1' ) { echo $disabled; } ?>><p class="description"><?php _e( 'The port that will be used to relay outgoing email to your email server', 'simpleform' ) ?></p></td></tr>
	
<tr id="form-fields-label" class="smtp smpt-settings trsmtp <?php if ($smtp !='true') { echo 'unseen'; }?>"><th class="option"><span><?php _e( 'SMTP Authentication', 'simpleform' ) ?></span></th><td id="tdauthentication" class="checkbox-switch notes <?php if ($smtp_authentication !='true') { echo 'last'; }?>"><div class="switch-box"><label class="switch-input"><input type="checkbox" id="smtp-authentication" name="smtp-authentication" class="sform-switch" value="true" <?php checked( $smtp_authentication, 'true'); if ( $id != '1' ) { echo $disabled; } ?>><span></span></label><label for="smtp-authentication" class="switch-label <?php if ( $id != '1' ) { echo 'disabled'; } ?>"><?php _e( 'Enable SMTP Authentication', 'simpleform' ); ?></label></div><p class="description"><?php _e('This option should always be checked', 'simpleform' ); ?></p></td></tr>

<tr valign="top" class="smtp smpt-settings trsmtp trauthentication <?php if ($smtp !='true' || $smtp_authentication !='true' ) { echo 'unseen'; }?>"><th class="option"><span><?php _e( 'SMTP Username','simpleform') ?></span></th><td class="text notes"><input class="sform" name="smtp-username" placeholder="<?php echo $username_placeholder ?>" id="smtp-username" type="text" value="<?php echo $smtp_username; ?>" <?php if ( $id != '1' ) { echo $disabled; } ?>\><p class="description"><?php _e( 'The username to log in to the SMTP email server (your email). Please read the above warnings for improved security','simpleform') ?></p></td></tr>	
		
<tr class="smtp smpt-settings trsmtp trauthentication <?php if ($smtp !='true' || $smtp_authentication !='true' ) { echo 'unseen'; }?>"><th class="option"><span><?php _e( 'SMTP Password','simpleform') ?></span></th><td class="last text notes"><input class="sform" name="smtp-password" placeholder="<?php echo $password_placeholder ?>" id="smtp-password" type="text" value="<?php echo $smtp_password; ?>" <?php if ( $id != '1' ) { echo $disabled; } ?>\><p class="description"><?php _e( 'The password to log in to the SMTP email server (your password). Please read the above warnings for improved security','simpleform') ?></p></td></tr>	

</tbody></table></div>

<h2 id="h2-notification" class="options-heading"><span class="heading" section="notification"><?php _e( 'Contact Alert', 'simpleform' ) ?><span class="toggle dashicons dashicons-arrow-up-alt2 notification"></span></span></h2>

<div class="section notification"><table class="form-table notification"><tbody>

<tr><th class="option"><span><?php _e('Alert Email','simpleform') ?></span></th><td id="tdnotification" class="checkbox-switch <?php if ($notification !='true') { echo 'last'; } ?>"><div class="switch-box">
<label class="switch-input"><input type="checkbox" name="notification" id="notification" class="sform-switch" value="true" <?php checked( $notification, 'true'); ?>><span></span></label><label for="notification" class="switch-label"><?php _e('Send email to alert the admin, or person responsible for managing contacts, when the form has been successfully submitted','simpleform') ?></label></div></td></tr>

<tr class="trnotification <?php if ($notification !='true') { echo 'unseen'; }?>"><th class="option"><span><?php _e( 'Send To', 'simpleform' ) ?></span></th><td class="text notes"><input class="sform" name="notification-recipient" placeholder="<?php esc_attr_e( 'Enter the email address to which the admin notification is sent', 'simpleform' ) ?>" id="notification-recipient" type="text" value="<?php echo $notification_recipient; ?>" ><p class="description"><?php _e( 'Use a comma-separated list of email addresses to send to more than one address', 'simpleform' ) ?></p></td></tr>
	       
<tr class="trnotification <?php if ($notification !='true') { echo 'unseen'; }?>"><th class="option"><span><?php _e( 'BCC', 'simpleform' ) ?></span></th><td class="text notes"><input class="sform" name="bcc" placeholder="<?php esc_attr_e( 'Enter the email address to which a copy of the admin notification is sent', 'simpleform' ) ?>" id="bcc" type="text" value="<?php echo $bcc; ?>" ><p class="description"><?php _e( 'Use a comma-separated list of email addresses to send to more than one address', 'simpleform' ) ?></p></td></tr>

<tr class="trnotification trfromemail <?php if ($notification !='true') { echo 'unseen'; }?>"><th class="option"><span><?php _e( 'From Email', 'simpleform' ) ?></span></th><td class="text"><input class="sform" name="notification-email" placeholder="<?php esc_attr_e( 'Enter the email address from which the admin notification is sent', 'simpleform' ) ?>" id="notification-email" type="text" value="<?php echo $notification_email; ?>" \></td></tr>      
       
<tr class="trnotification trfromemail <?php if ($notification !='true') { echo 'unseen'; }?>" ><th class="option"><span><?php _e( 'From Name', 'simpleform' ) ?></span></th><td class="radio"><fieldset><label for="requester-name" class="radio"><input id="requester-name" type="radio" name="notification-name" value="requester" <?php checked( $notification_name, 'requester'); ?> ><?php _e( 'Use submitter name', 'simpleform' ) ?></label><label for="form-name" class="radio"><input id="form-name" type="radio" name="notification-name" value="form" <?php checked( $notification_name, 'form'); ?> ><?php _e( 'Use contact form name', 'simpleform' ) ?></label><label for="custom-name" class="radio"><input id="custom-name" type="radio" name="notification-name" value="custom" <?php checked( $notification_name, 'custom'); ?> ><?php _e( 'Use default name', 'simpleform' ) ?></label><br></fieldset></td></tr>
	
<tr class="trnotification trcustomname <?php if ( $notification != 'true' || $notification_name != 'custom') { echo 'unseen'; }?>" ><th class="option"><span><?php _e( 'Default Name', 'simpleform' ) ?></span></th><td class="text"><input class="sform" name="custom-sender" placeholder="<?php esc_attr_e( 'Enter the name from which the admin notification is sent', 'simpleform' ) ?>" id="custom-sender" type="text" value="<?php echo $custom_sender; ?>" \></td></tr>

<tr class="trnotification <?php if ($notification !='true') { echo 'unseen'; }?>" ><th class="option"><span><?php _e( 'Email Subject', 'simpleform' ) ?></span></th><td class="radio"><fieldset><label for="request-subject" class="radio"><input id="request-subject" type="radio" name="notification-subject" value="request" <?php checked( $notification_subject, 'request'); ?> ><?php _e( 'Use submission subject', 'simpleform' ) ?></label><label for="default-subject" class="radio"><input id="default-subject" type="radio" name="notification-subject" value="custom" <?php checked( $notification_subject, 'custom'); ?> ><?php _e( 'Use default subject', 'simpleform' ) ?></label></fieldset></td></tr>

<tr class="trnotification trcustomsubject <?php if ( $notification != 'true' || $notification_subject != 'custom') { echo 'unseen'; }?>" ><th class="option"><span><?php _e( 'Default Subject', 'simpleform' ) ?></span></th><td class="text"><input class="sform" name="custom-subject" placeholder="<?php esc_attr_e( 'Enter the subject with which the admin notification is sent', 'simpleform' ) ?>" id="custom-subject" type="text" value="<?php echo $custom_subject; ?>" \></td></tr>

<tr class="trnotification <?php if ($notification !='true') { echo 'unseen'; }?>"><th class="option"><span><?php _e('Reply To','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="notification-reply" id="notification-reply" class="sform-switch" value="true" <?php checked( $notification_reply, 'true'); ?>><span></span></label><label for="notification-reply" class="switch-label"><?php _e( 'Use the email address of the person who filled in the form for reply-to if email is provided','simpleform') ?></label></div></td></tr>

<tr class="trnotification <?php if ($notification !='true') { echo 'unseen'; }?>"><th class="option"><span><?php _e('Submission ID','simpleform') ?></span></th><td class="checkbox-switch last"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="submission-number" id="submission-number" class="sform-switch" value="hidden" <?php checked( $submission_number, 'hidden'); ?>><span></span></label><label for="submission-number" class="switch-label"><?php _e( 'Hide submission ID inside email subject','simpleform') ?></label></div></td></tr>

</tbody></table></div>

<h2 id="h2-auto" class="options-heading"><span class="heading" section="auto"><?php _e( 'Auto Responder', 'simpleform' ) ?><span class="toggle dashicons dashicons-arrow-up-alt2 auto"></span></span></h2>

<div class="section auto"><table class="form-table auto"><tbody>

<tr class="trname"><th class="option"><span><?php _e('Auto-Reply Email','simpleform') ?></span></th><td id="tdconfirmation" class="checkbox-switch <?php if ($auto !='true') { echo 'last'; } ?>"><div class="switch-box">
<label class="switch-input"><input type="checkbox" name="autoresponder" id="autoresponder" class="sform-switch" value="false" <?php checked( $auto, 'true'); ?>><span></span></label><label for="autoresponder" class="switch-label"><?php _e('Send a confirmation email to users who have successfully submitted the form','simpleform') ?></label></div></td></tr>

<tr class="trauto <?php if ($auto !='true') { echo 'unseen'; }?>"><th class="option" ><span><?php _e( 'From Email', 'simpleform' ) ?></span></th><td class="text"><input class="sform" name="autoresponder-email" placeholder="<?php esc_attr_e( 'Enter the email address from which the auto-reply is sent', 'simpleform' ) ?>" id="autoresponder-email" type="text" value="<?php echo $auto_email; ?>" \></td></tr>

<tr class="trauto <?php if ($auto !='true') { echo 'unseen'; }?>"><th class="option"><span><?php _e( 'From Name', 'simpleform' ) ?></span></th><td class="text"><input class="sform" name="autoresponder-name" placeholder="<?php esc_attr_e( 'Enter the name from which the auto-reply is sent', 'simpleform' ) ?>" id="autoresponder-name" type="text" value="<?php echo $auto_name; ?>" \></td></tr>

<tr class="trauto <?php if ($auto !='true') { echo 'unseen'; }?>"><th class="option"><span><?php _e( 'Email Subject', 'simpleform' ) ?></span></th><td class="text"><input class="sform" name="autoresponder-subject" placeholder="<?php esc_attr_e( 'Enter the subject with which auto-reply is sent', 'simpleform' ) ?>" id="autoresponder-subject" type="text" value="<?php echo $auto_subject; ?>" \></td></tr>

<tr class="trauto <?php if ($auto !='true') { echo 'unseen'; }?>"><th class="option"><span><?php _e( 'Email Message', 'simpleform' ) ?></span></th><td class="textarea"><textarea name="autoresponder-message" id="autoresponder-message" class="sform" placeholder="<?php esc_attr_e( 'Enter the content for your auto-reply message', 'simpleform' ) ?>" ><?php echo $auto_message; ?></textarea><p class="description"><?php _e( 'You are able to use HTML tags and the following smart tags:', 'simpleform' ) ?> [name], [lastname], [email], [phone], [subject], [message], [submission_id]</p></td></tr>

<tr class="trauto <?php if ($auto !='true') { echo 'unseen'; }?>"><th class="option"><span><?php _e( 'Reply To', 'simpleform' ) ?></span></th><td class="last text notes"><input class="sform" name="autoresponder-reply" placeholder="<?php esc_attr_e( 'Enter the email address to use for reply-to', 'simpleform' ) ?>" id="autoresponder-reply" type="text" value="<?php echo $auto_reply; ?>" \><p class="description"><?php _e( 'Leave it blank to use From Email as the Reply-To value', 'simpleform' ) ?></p></td></tr>

</tbody></table></div>
</div>

<div id="tab-spam" class="navtab unseen">
	
<h2 id="h2-spam" class="options-heading"><span class="heading" section="spam"><?php _e( 'Basic Protection', 'simpleform' ); ?><span class="toggle dashicons dashicons-arrow-up-alt2 spam"></span></span><?php if ( $id != '1' ) { ?><a href="<?php echo menu_page_url( 'sform-settings', false ); ?>"><span class="dashicons dashicons-edit icon-button <?php echo $color ?>"></span><span class="settings-page wp-core-ui button"><?php _e( 'Go to main settings for edit', 'simpleform' ) ?></span></a><?php } ?></h2>

<div class="section spam"><table class="form-table spam"><tbody>	
	
<tr><th class="option"><span><?php _e('Duplicate Submission','simpleform') ?></span></th><td class="checkbox-switch last"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="duplicate" id="duplicate" class="sform-switch" value="true" <?php checked( $duplicate, 'true'); if ( $id != '1' ) { echo $disabled; } ?>><span></span></label><label for="duplicate" class="switch-label <?php if ( $id != '1' ) { echo 'disabled'; } ?>"><?php _e( 'Prevent duplicate form submission','simpleform') ?></label></div></td></tr>

</tbody></table></div>

<?php
echo apply_filters( 'akismet_settings_filter', $extra_option );
echo apply_filters( 'recaptcha_settings_filter', $extra_option );
?>

</div>

<div id="submit-wrap"><div id="alert-wrap">
<noscript><div id="noscript"><?php _e('You need JavaScript enabled to save settings. Please activate it. Thanks!', 'simpleform' ) ?></div></noscript>
<div id="message-wrap" class="message"></div>
</div>

<input type="submit" class="submit-button" id="save-settings" name="save-settings" value="<?php esc_attr_e( 'Save Changes', 'simpleform' ) ?>">

<?php wp_nonce_field( 'ajax-verification-nonce', 'verification_nonce'); ?>
</form></div>

<?php
} else { ?>
<span><?php _e('It seems the form is no longer available!', 'simpleform' ) ?></span><p><span class="wp-core-ui button unavailable <?php echo $color ?>"><a href="<?php echo menu_page_url( 'sform-settings', false ); ?>"><?php _e('Reload the Settings page','simpleform') ?></a></span><span class="wp-core-ui button unavailable <?php echo $color ?>"><a href="<?php echo menu_page_url( 'sform-creation', false ); ?>"><?php _e('Add New Form','simpleform') ?></a></span><span class="wp-core-ui button unavailable <?php echo $color ?>"><a href="<?php echo self_admin_url('widgets.php'); ?>"><?php _e('Activate SimpleForm Contact Form Widget','simpleform') ?></a></span></p>
<?php }