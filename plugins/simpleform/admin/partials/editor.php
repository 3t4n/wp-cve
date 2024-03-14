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
<h1 class="title <?php echo $color ?>"><span class="dashicons dashicons-editor-table responsive"></span><?php _e( 'Editor', 'simpleform' );
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
</h1>
</div>

<?php if ( in_array($id, $shortcode_ids) ) { ?>

<div id="page-description"><p><?php _e( 'Change easily the way your contact form is displayed. Choose which fields to use and who should see them:','simpleform') ?></p></div>

<div id="editor-tabs"><a class="nav-tab nav-tab-active" id="builder"><?php _e( 'Form Builder','simpleform') ?></a><a class="nav-tab" id="appearance"><?php _e( 'Form Appearance','simpleform') ?></a><a class="form-button last <?php echo $color ?>" href="<?php $arg = $id != '1' ? '&form='. $id : ''; echo admin_url('admin.php?page=sform-settings') . $arg; ?>" target="_blank"><span><span class="dashicons dashicons-admin-settings"></span><span class="text"><?php _e( 'Settings', 'simpleform' ) ?></span></span></a><a class="form-button form-page <?php echo $color ?>" href="<?php $arg = $id != '1' ? '&id='. $id : ''; echo admin_url('admin.php?page=sform-form') . $arg; ?>" target="_blank"><span><span class="dashicons dashicons-tag"></span><span class="text"><?php _e( 'More specifics', 'simpleform' ) ?></span></span></a></div>
						
<form id="attributes" method="post" class="<?php echo $color ?>">
		
<input type="hidden" id="form-id" name="form-id" value="<?php echo $id ?>">
	
<div id="tab-builder" class="navtab">

<?php
$shortcode = $id == '1' ? 'simpleform' : 'simpleform id="'.$id.'"';
$attributes = get_option("sform_{$id}_attributes") != false ? get_option("sform_{$id}_attributes") : get_option("sform_attributes");
$contact_form_name = ! empty( $attributes['form_name'] ) ? esc_attr($attributes['form_name']) : esc_attr__( 'Contact Us Page','simpleform');
$introduction_text = ! empty ( $attributes['introduction_text'] ) ? esc_attr($attributes['introduction_text']) : '';
$bottom_text = ! empty( $attributes['bottom_text'] ) ? esc_attr($attributes['bottom_text']) : '';
$name_visibility = ! empty( $attributes['name_visibility'] ) ? esc_attr($attributes['name_visibility']) : 'visible';
$name_label = ! empty( $attributes['name_label'] ) ? stripslashes(esc_attr($attributes['name_label'])) : esc_attr__( 'Name', 'simpleform' );
$name_placeholder = ! empty( $attributes['name_placeholder'] ) ? stripslashes(esc_attr($attributes['name_placeholder'])) : '';
$name_minlength = isset( $attributes['name_minlength'] ) ? esc_attr($attributes['name_minlength']) : '2';
$name_maxlength = isset( $attributes['name_maxlength'] ) ? esc_attr($attributes['name_maxlength']) : '0';
$name_requirement = ! empty( $attributes['name_requirement'] ) ? esc_attr($attributes['name_requirement']) : 'required';
$lastname_visibility = ! empty( $attributes['lastname_visibility'] ) ? esc_attr($attributes['lastname_visibility']) : 'visible';
$lastname_label = ! empty( $attributes['lastname_label'] ) ? stripslashes(esc_attr($attributes['lastname_label'])) : esc_attr__( 'Last Name', 'simpleform' );
$lastname_placeholder = ! empty( $attributes['lastname_placeholder'] ) ? stripslashes(esc_attr($attributes['lastname_placeholder'])) : '';
$lastname_minlength = isset( $attributes['lastname_minlength'] ) ? esc_attr($attributes['lastname_minlength']) : '2';
$lastname_maxlength = isset( $attributes['lastname_maxlength'] ) ? esc_attr($attributes['lastname_maxlength']) : '0';
$lastname_requirement = ! empty( $attributes['lastname_requirement'] ) ? esc_attr($attributes['lastname_requirement']) : 'optional';
$email_visibility = ! empty( $attributes['email_visibility'] ) ? esc_attr($attributes['email_visibility']) : 'visible';
$email_label = ! empty( $attributes['email_label'] ) ? stripslashes(esc_attr($attributes['email_label'])) : esc_attr__( 'Email', 'simpleform' );
$email_placeholder = ! empty( $attributes['email_placeholder'] ) ? stripslashes(esc_attr($attributes['email_placeholder'])) : '';
$email_requirement = ! empty( $attributes['email_requirement'] ) ? esc_attr($attributes['email_requirement']) : 'required';
$phone_visibility = ! empty( $attributes['phone_visibility'] ) ? esc_attr($attributes['phone_visibility']) : 'visible';
$phone_label = ! empty( $attributes['phone_label'] ) ? stripslashes(esc_attr($attributes['phone_label'])) : esc_attr__( 'Phone', 'simpleform' );
$phone_placeholder = ! empty( $attributes['phone_placeholder'] ) ? stripslashes(esc_attr($attributes['phone_placeholder'])) : '';
$phone_requirement = ! empty( $attributes['phone_requirement'] ) ? esc_attr($attributes['phone_requirement']) : 'optional';
$subject_visibility = ! empty( $attributes['subject_visibility'] ) ? esc_attr($attributes['subject_visibility']) : 'visible';
$subject_label = ! empty( $attributes['subject_label'] ) ? stripslashes(esc_attr($attributes['subject_label'])) : esc_attr__( 'Subject', 'simpleform' );
$subject_placeholder = ! empty( $attributes['subject_placeholder'] ) ? stripslashes(esc_attr($attributes['subject_placeholder'])) : '';
$subject_minlength = isset( $attributes['subject_minlength'] ) ? esc_attr($attributes['subject_minlength']) : '5';
$subject_maxlength = isset( $attributes['subject_maxlength'] ) ? esc_attr($attributes['subject_maxlength']) : '0';
$subject_requirement = ! empty( $attributes['subject_requirement'] ) ? esc_attr($attributes['subject_requirement']) : 'required';
$message_visibility = ! empty( $attributes['message_visibility'] ) ? esc_attr($attributes['message_visibility']) : 'visible';
$message_label = ! empty( $attributes['message_label'] ) ? stripslashes(esc_attr($attributes['message_label'])) : esc_attr__( 'Message', 'simpleform' );
$message_placeholder = ! empty( $attributes['message_placeholder'] ) ? stripslashes(esc_attr($attributes['message_placeholder'])) : '';
$message_minlength = isset( $attributes['message_minlength'] ) ? esc_attr($attributes['message_minlength']) : '10';
$message_maxlength = isset( $attributes['message_maxlength'] ) ? esc_attr($attributes['message_maxlength']) : '0';
$consent_label = ! empty( $attributes['consent_label'] ) ? stripslashes(esc_attr($attributes['consent_label'])) : __( 'I have read and consent to the privacy policy', 'simpleform' ); 
$privacy_link = ! empty( $attributes['privacy_link'] ) ? esc_attr($attributes['privacy_link']) : 'false';
$privacy_page = ! empty( $attributes['privacy_page'] ) ? esc_attr($attributes['privacy_page']) : '0';
$edit_page = '<a href="' . get_edit_post_link($privacy_page) . '" target="_blank" style="text-decoration: none; color: #9ccc79;">' . __( 'Publish now','simpleform') . '</a>';
$privacy_url = $privacy_page != '0' ? get_page_link($privacy_page) : '';
/* translators: It is used in place of placeholder %1$s in the string: "%1$s or %2$s the page content" */
$edit = __( 'Edit','simpleform');
/* translators: It is used in place of placeholder %2$s in the string: "%1$s or %2$s the page content" */
$view = __( 'view','simpleform');
$post_url = $privacy_page != '0' ? sprintf( __('%1$s or %2$s the page content', 'simpleform'), '<strong><a href="' . get_edit_post_link($privacy_page) .'" target="_blank" style="text-decoration: none;">'. $edit .'</a></strong>', '<strong><a href="' . get_page_link($privacy_page) . '" target="_blank" style="text-decoration: none;">'. $view .'</a></strong>' ) : '&nbsp;'; 
$privacy_status = $privacy_page != '0' && get_post_status($privacy_page) == 'draft' ? __( 'Page in draft status not yet published','simpleform').'&nbsp;-&nbsp;'.$edit_page : $post_url;
$consent_requirement = ! empty( $attributes['consent_requirement'] ) ? esc_attr($attributes['consent_requirement']) : 'required'; 
$math_captcha_label = ! empty( $attributes['captcha_label'] ) ? stripslashes(esc_attr($attributes['captcha_label'])) : esc_attr__( 'I\'m not a robot', 'simpleform' ); 
$submit_label = ! empty( $attributes['submit_label'] ) ? stripslashes(esc_attr($attributes['submit_label'])) : esc_attr__( 'Submit', 'simpleform' );
$label_position = ! empty( $attributes['label_position'] ) ? esc_attr($attributes['label_position']) : 'top';
$label_size = ! empty( $attributes['label_size'] ) ? esc_attr($attributes['label_size']) : 'default';
$required_sign = ! empty( $attributes['required_sign'] ) ? esc_attr($attributes['required_sign']) : 'true';
$required_word = ! empty( $attributes['required_word'] ) ? esc_attr($attributes['required_word']) : esc_attr__( '(required)', 'simpleform' );
$word_position = ! empty( $attributes['word_position'] ) ? esc_attr($attributes['word_position']) : 'required';
$lastname_alignment = ! empty( $attributes['lastname_alignment'] ) ? esc_attr($attributes['lastname_alignment']) : 'name';
$phone_alignment = ! empty( $attributes['phone_alignment'] ) ? esc_attr($attributes['phone_alignment']) : 'email';
$submit_position = ! empty( $attributes['submit_position'] ) ? esc_attr($attributes['submit_position']) : 'centred';
$form_direction = ! empty( $attributes['form_direction'] ) ? esc_attr($attributes['form_direction']) : 'ltr';
$additional_css = ! empty( $attributes['additional_css'] ) ? stripslashes(esc_attr($attributes['additional_css'])) : '';
$table_post= "{$wpdb->prefix}posts"; 
$allpagesid = $wpdb->get_col( "SELECT id FROM $table_post WHERE post_type != 'attachment' AND post_type != 'revision' AND post_status != 'trash' AND post_title != '' AND post_content != '' ORDER BY post_title ASC" );
?>	

<h2 id="h2-specifics" class="options-heading"><span class="heading" section="specifics"><?php _e( 'Specifics', 'simpleform' ); ?><span class="toggle dashicons dashicons-arrow-up-alt2 specifics"></span></span></h2>

<div class="section specifics"><table class="form-table specifics"><tbody>

<?php
// Contact forms embedded in page 
if ( in_array($id, $page_ids) ) { 

$show_for_value = isset($_GET['showfor']) ? $_GET['showfor'] : 'all';
$show_for = ! empty( $attributes['show_for'] ) && !isset($_GET['showfor']) ? esc_attr($attributes['show_for']) : $show_for_value;
$user_role = ! empty( $attributes['user_role'] ) ? esc_attr($attributes['user_role']) : 'any';
$icon = SIMPLEFORM_URL . 'admin/img/copy_icon.png';
?>	 

<tr><th class="option"><span><?php _e('Form Name','simpleform') ?></span></th><td class="text"><input class="sform" name="form-name" placeholder="<?php esc_attr_e('Enter a name for this Form','simpleform') ?>" id="form-name" type="text" value="<?php echo $contact_form_name; ?>"></td></tr>

<tr><th class="option"><span><?php _e('Visible to','simpleform') ?></span></th><td class="select <?php if ( $show_for != 'in' ) { echo 'last'; } ?>"><select name="show-for" id="show-for" class="sform"><option value="all" <?php selected( $show_for, 'all'); ?>><?php _e('Everyone','simpleform') ?></option><option value="in" <?php selected( $show_for, 'in'); ?>><?php _e('Logged-in users','simpleform') ?></option><option value="out" <?php selected( $show_for, 'out'); ?>><?php _e('Logged-out users','simpleform') ?></option></select></td></tr>

<tr class="trlevel <?php if ( $show_for !='in') {echo 'unseen';} ?>"><th class="option"><span><?php _e('Restricted to','simpleform') ?></span></th><td class="select <?php if ( $show_for == 'in' ) { echo 'last'; } ?>"><select name="user-role" id="user-role" class="sform"><option value="any" <?php selected( $user_role, 'any'); ?>><?php _e('Any','simpleform') ?></option><?php wp_dropdown_roles($user_role); ?></select></td></tr>

<?php
}

// Contact forms embedded in widget area 
if ( in_array($id, $widget_ids) ) { 
$sform_widget = get_option('widget_sform_widget');
$widget_id = $wpdb->get_var( "SELECT widget FROM $table_name WHERE id = {$id}" );
if ( in_array($widget_id, array_keys($sform_widget)) ) { 
$widget_for = ! empty($sform_widget[$widget_id]['sform_widget_audience']) ? $sform_widget[$widget_id]['sform_widget_audience'] : 'all';
$role = ! empty($sform_widget[$widget_id]['sform_widget_role']) ? $sform_widget[$widget_id]['sform_widget_role'] : 'any';

global $wp_roles;
$role_name = $role == 'any' ? __( 'Any','simpleform') : translate_user_role($wp_roles->roles[$role]['name']);
if ( $widget_for == 'out' ) {
/* translators: Used in place of %s in the string: "You set the widget as visible only for %s" */
$audience = __( 'Logged-out users','simpleform');
}
elseif ( $widget_for == 'in' ) {
/* translators: Used in place of %s in the string: "You set the widget as visible only for %s" */
$audience = __( 'Logged-in users','simpleform');
}
else {
$audience = __( 'Everyone','simpleform');
}
?>	

<tr><th class="option"><span><?php _e('Form Name','simpleform') ?></span></th><td class="text"><input class="sform" name="form-name" placeholder="<?php esc_attr_e('Enter a name for this Form','simpleform') ?>" id="form-name" type="text" value="<?php echo $contact_form_name; ?>"></td></tr>

<tr class="textbutton"><th class="option"><span><?php _e('Visible to','simpleform') ?></span></th><td class="plaintext"><?php echo $audience; ?></td></tr>

<?php if ($widget_for == 'in') { ?>
<tr class="textbutton"><th class="option"><span><?php _e('Restricted to','simpleform') ?></span></th><td class="plaintext"><?php echo $role_name; ?></td></tr>
<?php } ?>

<input type="hidden" id="widget-id" name="widget-id" value="<?php echo $widget_id ?>">

<?php	 
$show_for = $widget_for;	
} 
}
 
if ( $show_for == 'out' ) {
  $name_field = ! empty( $attributes['name_field'] ) ? esc_attr($attributes['name_field']) : 'anonymous';
  $lastname_field = ! empty( $attributes['lastname_field'] ) ? esc_attr($attributes['lastname_field']) : 'hidden';
  $email_field = ! empty( $attributes['email_field'] ) ? esc_attr($attributes['email_field']) : 'anonymous';
  $phone_field = ! empty( $attributes['phone_field'] ) ? esc_attr($attributes['phone_field']) : 'hidden';
  $subject_field = ! empty( $attributes['subject_field'] ) ? esc_attr($attributes['subject_field']) : 'anonymous';
  $consent_field = ! empty( $attributes['consent_field'] ) ? esc_attr($attributes['consent_field']) : 'anonymous';
  $captcha_field = ! empty( $attributes['captcha_field'] ) ? esc_attr($attributes['captcha_field']) : 'hidden';
  /* translators: Used in place of %s in the string: "You set the widget as visible only for %s" */
  $target = __( 'logged-out users','simpleform');
}
elseif ( $show_for == 'in' ) {
  $name_field = ! empty( $attributes['name_field'] ) ? esc_attr($attributes['name_field']) : 'registered';
  $lastname_field = ! empty( $attributes['lastname_field'] ) ? esc_attr($attributes['lastname_field']) : 'hidden';
  $email_field = ! empty( $attributes['email_field'] ) ? esc_attr($attributes['email_field']) : 'registered';
  $phone_field = ! empty( $attributes['phone_field'] ) ? esc_attr($attributes['phone_field']) : 'hidden';
  $subject_field = ! empty( $attributes['subject_field'] ) ? esc_attr($attributes['subject_field']) : 'registered';
  $consent_field = ! empty( $attributes['consent_field'] ) ? esc_attr($attributes['consent_field']) : 'registered';
  $captcha_field = ! empty( $attributes['captcha_field'] ) ? esc_attr($attributes['captcha_field']) : 'hidden';
  /* translators: Used in place of %s in the string: "You set the widget as visible only for %s" */
  $target = __( 'logged-in users','simpleform');
}
else {
  $name_field = ! empty( $attributes['name_field'] ) ? esc_attr($attributes['name_field']) : 'visible';
  $lastname_field = ! empty( $attributes['lastname_field'] ) ? esc_attr($attributes['lastname_field']) : 'hidden';
  $email_field = ! empty( $attributes['email_field'] ) ? esc_attr($attributes['email_field']) : 'visible';
  $phone_field = ! empty( $attributes['phone_field'] ) ? esc_attr($attributes['phone_field']) : 'hidden';
  $subject_field = ! empty( $attributes['subject_field'] ) ? esc_attr($attributes['subject_field']) : 'visible';
  $consent_field = ! empty( $attributes['consent_field'] ) ? esc_attr($attributes['consent_field']) : 'visible';
  $captcha_field = ! empty( $attributes['captcha_field'] ) ? esc_attr($attributes['captcha_field']) : 'hidden';
  $target = '';
}

$field_for = sprintf( __('You have set the form as visible only for %s', 'simpleform' ), $target );
?>

</tbody></table></div>

<h2 id="h2-formdescription" class="options-heading"><span class="heading" section="formdescription"><?php _e( 'Description', 'simpleform' ); ?><span class="toggle dashicons dashicons-arrow-up-alt2 formdescription"></span></span></h2>

<div class="section formdescription"><table class="form-table formdescription"><tbody>

<tr><th class="option"><span><?php _e( 'Text above Form', 'simpleform' ) ?></span></th><td class="textarea"><textarea name="introduction-text" id="introduction-text" class="sform description" placeholder="<?php esc_attr_e( 'Enter the text that must be displayed above the form. It can be used to provide a description or instructions for filling in the form.', 'simpleform' ) ?>" ><?php echo $introduction_text ?></textarea><p class="description"><?php _e( 'The HTML tags for formatting message are allowed', 'simpleform' ) ?></p></td></tr>

<tr><th class="option"><span><?php _e( 'Text below Form', 'simpleform' ) ?></span></th><td class="textarea last"><textarea class="sform description" name="bottom-text" id="bottom-text" placeholder="<?php esc_attr_e( 'Enter the text that must be displayed below the form. It can be used to provide additional information.', 'simpleform' ) ?>" ><?php echo $bottom_text ?></textarea><p class="description"><?php _e( 'The HTML tags for formatting message are allowed', 'simpleform' ) ?></p></td></tr>

</tbody></table></div>

<h2 id="h2-formfields" class="options-heading"><span class="heading" section="formfields"><?php _e( 'Fields', 'simpleform' ); ?><span class="toggle dashicons dashicons-arrow-up-alt2 formfields"></span></span></h2>

<div class="section formfields"><table class="form-table formfields"><tbody>

<?php if ( $show_for == 'all' ) { ?>
<tr><th class="option"><span><?php _e('Name Field','simpleform') ?></span></th><td class="select"><select name="name-field" id="name-field" class="sform"><option value="visible" <?php selected( $name_field, 'visible'); ?>><?php _e('Display to all users','simpleform') ?></option><option value="registered" <?php selected( $name_field, 'registered'); ?>><?php _e('Display only to registered users','simpleform') ?></option><option value="anonymous" <?php selected( $name_field, 'anonymous'); ?>><?php _e('Display only to anonymous users','simpleform') ?></option><option value="hidden" <?php selected( $name_field, 'hidden'); ?>><?php _e('Do not display','simpleform') ?></option></select></td></tr>
<?php } else { ?>
<tr><th class="option"><span><?php _e('Name Field','simpleform') ?></span></th><td class="checkbox-switch notes"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="name-field" id="name-field" field="name" class="sform-switch cbfield" value="visible" <?php checked( $name_field, 'hidden'); ?>><span></span></label><label for="name-field" class="switch-label"><?php _e('Do not display','simpleform') ?></label></div><p class="description"><?php printf( __('You have set the form as visible only for %s', 'simpleform' ), $target ) ?></p></td></tr>
<?php } ?>

<tr class="trname <?php if ( $name_field =='hidden') {echo 'unseen';} ?>"><th class="option"><span><?php _e('Name Field Label Visibility','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="name-visibility" id="namelabel" class="sform-switch field-label" value="visible" <?php checked( $name_visibility, 'hidden'); ?>><span></span></label><label for="namelabel" class="switch-label"><?php _e('Hide label for name field','simpleform') ?></label></div></td></tr>

<tr class="trname namelabel <?php if ( $name_field =='hidden' || $name_visibility =='hidden' ) {echo 'unseen';}?>" ><th class="option"><span><?php _e('Name Field Label','simpleform') ?></span></th><td class="text"><input class="sform" name="name-label" placeholder="<?php esc_attr_e('Enter a label for the name field','simpleform') ?>" id="name-label" type="text" value="<?php echo $name_label; ?>"</td></tr>		
		
<tr class="trname <?php if ( $name_field =='hidden' ) {echo 'unseen';}?>" ><th class="option"><span><?php _e('Name Field Placeholder','simpleform') ?></span></th><td class="text"><input class="sform" name="name-placeholder" placeholder="<?php esc_attr_e('Enter a placeholder for the name field. If blank, it will not be used!','simpleform') ?>" id="name-placeholder" type="text" value='<?php echo $name_placeholder; ?>'</td></tr>
	
<tr class="trname <?php if ( $name_field =='hidden' ) {echo 'unseen';}?>" ><th class="option"><span><?php _e( 'Name\'s Minimum Length', 'simpleform' ) ?></span></th><td class="text"><input name="name-minlength" id="name-minlength" type="number" class="sform" min="0" max="80" value="<?php echo $name_minlength;?>"><span class="description left"><?php _e('Notice that 0 means no minimum limit','simpleform') ?></span></td></tr>

<tr class="trname <?php if ( $name_field =='hidden' ) {echo 'unseen';}?>" ><th class="option"><span><?php _e( 'Name\'s Maximum Length', 'simpleform' ) ?></span></th><td class="text"><input name="name-maxlength" id="name-maxlength" type="number" class="sform" min="0" max="80" value="<?php echo $name_maxlength;?>"><span class="description left"><?php _e('Notice that 0 means no maximum limit','simpleform') ?></span></td></tr>

<tr class="trname <?php if ( $name_field =='hidden') {echo 'unseen';} ?>"><th class="option"><span><?php _e('Name Field Requirement','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="name-requirement" id="name-requirement" class="sform-switch" value="required" <?php checked( $name_requirement, 'required'); ?>><span></span></label><label for="name-requirement" class="switch-label"><?php _e('Make this a required field','simpleform') ?></label></div></td></tr>

<?php if ( $show_for == 'all' ) { ?>
<tr><th class="option"><span><?php _e('Last Name Field','simpleform') ?></span></th><td class="select"><select name="lastname-field" id="lastname-field" class="sform"><option value="visible" <?php selected( $lastname_field, 'visible'); ?>><?php _e('Display to all users','simpleform') ?></option><option value="registered" <?php selected( $lastname_field, 'registered'); ?>><?php _e('Display only to registered users','simpleform') ?></option><option value="anonymous" <?php selected( $lastname_field, 'anonymous'); ?>><?php _e('Display only to anonymous users','simpleform') ?></option><option value="hidden" <?php selected( $lastname_field, 'hidden'); ?>><?php _e('Do not display','simpleform') ?></option></select></td></tr>
<?php } else { ?>
<tr><th class="option"><span><?php _e('Last Name Field','simpleform') ?></span></th><td class="checkbox-switch notes"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="lastname-field" id="lastname-field" field="lastname" class="sform-switch cbfield" value="hidden" <?php checked( $lastname_field, 'hidden'); ?>><span></span></label><label for="lastname-field" class="switch-label"><?php _e('Do not display','simpleform') ?></label></div><p class="description"><?php printf( __('You have set the form as visible only for %s', 'simpleform' ), $target ) ?></p></td></tr>
<?php } ?>

<tr class="trlastname <?php if ( $lastname_field == 'hidden') {echo 'unseen';} ?>"><th class="option"><span><?php _e('Last Name Field Label Visibility','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="lastname-visibility" id="lastnamelabel" class="sform-switch field-label" value="visible" <?php checked( $lastname_visibility, 'hidden'); ?>><span></span></label><label for="lastnamelabel" class="switch-label"><?php _e('Hide label for last name field','simpleform') ?></label></div></td></tr>

<tr class="trlastname lastnamelabel <?php if ( $lastname_field =='hidden' || $lastname_visibility =='hidden' ) {echo 'unseen';}?>" ><th class="option"><span><?php _e('Last Name Field Label','simpleform') ?></span></th><td class="text"><input class="sform" name="lastname-label" placeholder="<?php esc_attr_e('Enter a label for the last name field','simpleform') ?>" id="lastname-label" type="text" value='<?php echo $lastname_label; ?>'</td></tr>		

<tr class="trlastname <?php if ( $lastname_field =='hidden' ) {echo 'unseen';}?>" ><th class="option"><span><?php _e('Last Name Field Placeholder','simpleform') ?></span></th><td class="text"><input class="sform" name="lastname-placeholder" placeholder="<?php esc_attr_e('Enter a placeholder for the last name field. If blank, it will not be used!','simpleform') ?>" id="lastname-placeholder" type="text" value='<?php echo $lastname_placeholder; ?>'</td></tr>	

<tr class="trlastname <?php if ( $lastname_field =='hidden' ) {echo 'unseen';}?>" ><th class="option"><span><?php _e( 'Last Name\'s Minimum Length', 'simpleform' ) ?></span></th><td class="text"><input name="lastname-minlength" id="lastname-minlength" type="number" class="sform" min="0" max="80" value="<?php echo $lastname_minlength;?>"><span class="description left"><?php _e('Notice that 0 means no minimum limit','simpleform') ?></span></td></tr>

<tr class="trlastname <?php if ( $lastname_field =='hidden' ) {echo 'unseen';}?>" ><th class="option"><span><?php _e( 'Last Name\'s Maximum Length', 'simpleform' ) ?></span></th><td class="text"><input name="lastname-maxlength" id="lastname-maxlength" type="number" class="sform" min="0" max="80" value="<?php echo $lastname_maxlength;?>"><span class="description left"><?php _e('Notice that 0 means no maximum limit','simpleform') ?></span></td></tr>

<tr class="trlastname <?php if ( $lastname_field =='hidden') {echo 'unseen';} ?>"><th class="option"><span><?php _e('Last Name Field Requirement','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="lastname-requirement" id="lastname-requirement" class="sform-switch" value="optional" <?php checked( $lastname_requirement, 'required'); ?>><span></span></label><label for="lastname-requirement" class="switch-label"><?php _e('Make this a required field','simpleform') ?></label></div></td></tr>

<?php if ( $show_for == 'all' ) { ?>
<tr><th class="option"><span><?php _e('Email Field','simpleform') ?></span></th><td class="select"><select name="email-field" id="email-field" class="sform"><option value="visible" <?php selected( $email_field, 'visible'); ?>><?php _e('Display to all users','simpleform') ?></option><option value="registered" <?php selected( $email_field, 'registered'); ?>><?php _e('Display only to registered users','simpleform') ?></option><option value="anonymous" <?php selected( $email_field, 'anonymous'); ?>><?php _e('Display only to anonymous users','simpleform') ?></option><option value="hidden" <?php selected( $email_field, 'hidden'); ?>><?php _e('Do not display','simpleform') ?></option></select></td></tr>
<?php } else { ?>
<tr><th class="option"><span><?php _e('Email Field','simpleform') ?></span></th><td class="checkbox-switch notes"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="email-field" id="email-field" field="email" class="sform-switch cbfield" value="visible" <?php checked( $email_field, 'hidden'); ?>><span></span></label><label for="email-field" class="switch-label"><?php _e('Do not display','simpleform') ?></label></div><p class="description"><?php printf( __('You have set the form as visible only for %s', 'simpleform' ), $target ) ?></p></td></tr>
<?php } ?>

<tr class="tremail <?php if ( $email_field =='hidden') {echo 'unseen';} ?>"><th class="option"><span><?php _e('Email Field Label Visibility','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="email-visibility" id="emaillabel" class="sform-switch field-label" value="visible" <?php checked( $email_visibility, 'hidden'); ?>><span></span></label><label for="emaillabel" class="switch-label"><?php _e('Hide label for email field','simpleform') ?></label></div></td></tr>

<tr class="tremail emaillabel <?php if ( $email_field =='hidden' || $email_visibility =='hidden' ) {echo 'unseen';}?>" ><th class="option"><span><?php _e('Email Field Label','simpleform') ?></span></th><td class="text"><input class="sform" name="email-label" placeholder="<?php esc_attr_e('Enter a label for the email field','simpleform') ?>" id="email-label" type="text" value='<?php echo $email_label; ?>'</td></tr>		
	
<tr class="tremail <?php if ( $email_field =='hidden' ) {echo 'unseen';}?>" ><th class="option"><span><?php _e('Email Field Placeholder','simpleform') ?></span></th><td class="text"><input class="sform" name="email-placeholder" placeholder="<?php esc_attr_e('Enter a placeholder for the email field. If blank, it will not be used!','simpleform') ?>" id="email-placeholder" type="text" value='<?php echo $email_placeholder; ?>'</td></tr>		
<tr class="tremail <?php if ( $email_field =='hidden') {echo 'unseen';} ?>"><th class="option"><span><?php _e('Email Field Requirement','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="email-requirement" id="email-requirement" class="sform-switch" value="required" <?php checked( $email_requirement, 'required'); ?>><span></span></label><label for="email-requirement" class="switch-label"><?php _e('Make this a required field','simpleform') ?></label></div></td></tr>

<?php if ( $show_for == 'all' ) { ?>
<tr><th class="option"><span><?php _e('Phone Field','simpleform') ?></span></th><td class="select"><select name="phone-field" id="phone-field" class="sform"><option value="visible" <?php selected( $phone_field, 'visible'); ?>><?php _e('Display to all users','simpleform') ?></option><option value="registered" <?php selected( $phone_field, 'registered'); ?>><?php _e('Display only to registered users','simpleform') ?></option><option value="anonymous" <?php selected( $phone_field, 'anonymous'); ?>><?php esc_html_e('Display only to anonymous users','simpleform') ?></option><option value="hidden" <?php selected( $phone_field, 'hidden'); ?>><?php _e('Do not display','simpleform') ?></option></select></td></tr>
<?php } else { ?>
<tr><th class="option"><span><?php _e('Phone Field','simpleform') ?></span></th><td class="checkbox-switch notes"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="phone-field" id="phone-field" field="phone" class="sform-switch cbfield" value="hidden" <?php checked( $phone_field, 'hidden'); ?>><span></span></label><label for="phone-field" class="switch-label"><?php _e('Do not display','simpleform') ?></label></div><p class="description"><?php printf( __('You have set the form as visible only for %s', 'simpleform' ), $target ) ?></p></td></tr>
<?php } ?>

<tr class="trphone <?php if ( $phone_field =='hidden') {echo 'unseen';} ?>"><th class="option"><span><?php _e('Phone Field Label Visibility','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="phone-visibility" id="phonelabel" class="sform-switch field-label" value="visible" <?php checked( $phone_visibility, 'hidden'); ?>><span></span></label><label for="phonelabel" class="switch-label"><?php _e('Hide label for phone field','simpleform') ?></label></div></td></tr>

<tr class="trphone phonelabel <?php if ( $phone_field =='hidden' || $phone_visibility =='hidden' ) {echo 'unseen';}?>" ><th class="option"><span><?php _e('Phone Field Label','simpleform') ?></span></th><td class="text"><input class="sform" name="phone-label" placeholder="<?php esc_attr_e('Enter a label for the phone field','simpleform') ?>" id="phone-label" type="text" value='<?php echo $phone_label; ?>'</td></tr>		

<tr class="trphone <?php if ( $phone_field =='hidden' ) {echo 'unseen';}?>" ><th class="option"><span><?php _e('Phone Field Placeholder','simpleform') ?></span></th><td class="text"><input class="sform" name="phone-placeholder" placeholder="<?php esc_attr_e('Enter a placeholder for the phone field. If blank, it will not be used!','simpleform') ?>" id="phone-placeholder" type="text" value='<?php echo $phone_placeholder; ?>'</td></tr>	

<tr class="trphone <?php if ( $phone_field =='hidden') {echo 'unseen';} ?>"><th class="option"><span><?php _e('Phone Field Requirement','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="phone-requirement" id="phone-requirement" class="sform-switch" value="optional" <?php checked( $phone_requirement, 'required'); ?>><span></span></label><label for="phone-requirement" class="switch-label"><?php _e('Make this a required field','simpleform') ?></label></div></td></tr>

<?php if ( $show_for == 'all' ) { ?>
<tr><th class="option"><span><?php _e('Subject Field','simpleform') ?></span></th><td class="select"><select name="subject-field" id="subject-field" class="sform"><option value="visible" <?php selected( $subject_field, 'visible'); ?>><?php _e('Display to all users','simpleform') ?></option><option value="registered" <?php selected( $subject_field, 'registered'); ?>><?php _e('Display only to registered users','simpleform') ?></option><option value="anonymous" <?php selected( $subject_field, 'anonymous'); ?>><?php _e('Display only to anonymous users','simpleform') ?></option><option value="hidden" <?php selected( $subject_field, 'hidden'); ?>><?php _e('Do not display','simpleform') ?></option></select></td></tr>
<?php } else { ?>
<tr><th class="option"><span><?php _e('Subject Field','simpleform') ?></span></th><td class="checkbox-switch notes"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="subject-field" id="subject-field" field="subject" class="sform-switch cbfield" value="visible" <?php checked( $subject_field, 'hidden'); ?>><span></span></label><label for="subject-field" class="switch-label"><?php _e('Do not display','simpleform') ?></label></div><p class="description"><?php printf( __('You have set the form as visible only for %s', 'simpleform' ), $target ) ?></p></td></tr>
<?php } ?>

<tr class="trsubject <?php if ( $subject_field =='hidden') {echo 'unseen';} ?>"><th class="option"><span><?php _e('Subject Field Label Visibility','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="subject-visibility" id="subjectlabel" class="sform-switch field-label" value="visible" <?php checked( $subject_visibility, 'hidden'); ?>><span></span></label><label for="subjectlabel" class="switch-label"><?php _e('Hide label for subject field','simpleform') ?></label></div></td></tr>

<tr class="trsubject subjectlabel <?php if ($subject_field =='hidden' || $subject_visibility =='hidden' ) {echo 'unseen';}?>" ><th class="option"><span><?php _e('Subject Field Label','simpleform') ?></span></th><td class="text"><input class="sform" name="subject-label" placeholder="<?php esc_attr_e('Enter a label for the subject field','simpleform') ?>" id="subject-label" type="text" value="<?php echo $subject_label; ?>"></td></tr>

<tr class="trsubject <?php if ( $subject_field =='hidden' ) {echo 'unseen';}?>" ><th class="option"><span><?php _e('Subject Field Placeholder','simpleform') ?></span></th><td class="text"><input class="sform" name="subject-placeholder" placeholder="<?php esc_attr_e('Enter a placeholder for the subject field. If blank, it will not be used!','simpleform') ?>" id="subject-placeholder" type="text" value='<?php echo $subject_placeholder; ?>'</td></tr>		

<tr class="trsubject <?php if ( $subject_field =='hidden' ) {echo 'unseen';}?>" ><th class="option"><span><?php _e( 'Subject\'s Minimum Length', 'simpleform' ) ?></span></th><td class="text"><input name="subject-minlength" id="subject-minlength" type="number" class="sform" min="0" max="80" value="<?php echo $subject_minlength;?>"><span class="description left"><?php _e('Notice that 0 means no minimum limit','simpleform') ?></span></td></tr>

<tr class="trsubject <?php if ( $subject_field =='hidden' ) {echo 'unseen';}?>" ><th class="option"><span><?php _e( 'Subject\'s Maximum Length', 'simpleform' ) ?></span></th><td class="text"><input name="subject-maxlength" id="subject-maxlength" type="number" class="sform" min="0" max="80" value="<?php echo $subject_maxlength;?>"><span class="description left"><?php _e('Notice that 0 means no maximum limit','simpleform') ?></span></td></tr>

<tr class="trsubject <?php if ( $subject_field =='hidden') {echo 'unseen';} ?>"><th class="option"><span><?php _e('Subject Field Requirement','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="subject-requirement" id="subject-requirement" class="sform-switch" value="required" <?php checked( $subject_requirement, 'required'); ?>><span></span></label><label for="subject-requirement" class="switch-label"><?php _e('Make this a required field','simpleform') ?></label></div></td></tr>

<tr><th class="option"><span><?php _e('Message Field Label Visibility','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="message-visibility" id="messagelabel" class="sform-switch field-label" value="visible" <?php checked( $message_visibility, 'hidden'); ?>><span></span></label><label for="messagelabel" class="switch-label"><?php _e('Hide label for message field','simpleform') ?></label></div></td></tr>

<tr class="messagelabel <?php if ( $message_visibility =='hidden' ) {echo 'unseen';}?>"><th class="option"><span><?php _e('Message Field Label','simpleform') ?></span></th><td class="text"><input type="text" class="sform" name="message-label" id="message-label" placeholder="<?php esc_attr_e('Enter a label for the message field','simpleform') ?>" value="<?php echo $message_label; ?>"</td></tr>

<tr><th class="option"><span><?php _e('Message Field Placeholder','simpleform') ?></span></th><td class="text"><input type="text" name="message-placeholder" id="message-placeholder" class="sform" placeholder="<?php esc_attr_e('Enter a placeholder for the message field. If blank, it will not be used!','simpleform') ?>" value="<?php echo $message_placeholder; ?>"</td></tr>

<tr><th class="option"><span><?php _e( 'Message\'s Minimum Length', 'simpleform' ) ?></span></th><td class="text"><input type="number" name="message-minlength" id="message-minlength" class="sform" min="5" max="80" value="<?php echo $message_minlength;?>"></td></tr>

<tr><th class="option"><span><?php _e( 'Message\'s Maximum Length', 'simpleform' ) ?></span></th><td class="text"><input type="number" name="message-maxlength" id="message-maxlength" class="sform" min="0" max="80" value="<?php echo $message_maxlength;?>"><span class="description left"><?php _e('Notice that 0 means no maximum limit','simpleform') ?></span></td></tr>

<?php if ( $show_for == 'all' ) { ?>
<tr><th class="option"><span><?php _e('Consent Field','simpleform') ?></span></th><td class="select"><select name="consent-field" id="consent-field" class="sform"><option value="visible" <?php selected( $consent_field, 'visible'); ?>><?php _e('Display to all users','simpleform') ?></option><option value="registered" <?php selected( $consent_field, 'registered'); ?>><?php _e('Display only to registered users','simpleform') ?></option><option value="anonymous" <?php selected( $consent_field, 'anonymous'); ?>><?php _e('Display only to anonymous users','simpleform') ?></option><option value="hidden" <?php selected( $consent_field, 'hidden'); ?>><?php _e('Do not display','simpleform') ?></option></select></td></tr>
<?php } else { ?>
<tr><th class="option"><span><?php _e('Consent Field','simpleform') ?></span></th><td class="checkbox-switch notes"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="consent-field" id="consent-field" field="consent" class="sform-switch cbfield" value="visible" <?php checked( $consent_field, 'hidden'); ?>><span></span></label><label for="consent-field" class="switch-label"><?php _e('Do not display','simpleform') ?></label></div><p class="description"><?php printf( __('You have set the form as visible only for %s', 'simpleform' ), $target ) ?></p></td></tr>
<?php } ?>

<tr class="trconsent <?php if ($consent_field =='hidden') {echo 'unseen';}?>"><th class="option"><span><?php _e('Consent Field Label','simpleform') ?></span></th><td class="textarea"><textarea name="consent-label" id="consent-label" class="sform labels" placeholder="<?php esc_attr_e( 'Enter a label for the consent field', 'simpleform' ) ?>" ><?php echo $consent_label ?></textarea><p class="description"><?php _e( 'The HTML tags for formatting the consent field label are allowed', 'simpleform' ) ?></p></td></tr>

<?php $pages = get_pages( array( 'sort_column' => 'post_title', 'sort_order' => 'ASC', 'post_type' => 'page', 'post_status' =>  array('publish','draft') ) ); 
if ( $pages ) { ?>
<tr class="trconsent <?php if ( $consent_field =='hidden') {echo 'unseen';} ?>"><th class="option"><span><?php _e('Link to Privacy Policy','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="privacy-link" id="privacy-link" class="sform-switch" value="false" <?php checked( $privacy_link, 'true'); ?>><span></span></label><label for="privacy-link" class="switch-label"><?php _e('Insert a link to the Privacy Policy page in the consent field label','simpleform') ?></label></div></td></tr>

<tr class="trconsent trpage <?php if ($consent_field =='hidden' || $privacy_link == 'false') {echo 'unseen';}?>" ><th class="option"><span><?php _e( 'Privacy Policy Page', 'simpleform' ) ?></span><span id="label-error-top"></span></th><td class="select notes"><select name="privacy-page" class="sform" id="privacy-page"><option value=""><?php _e( 'Select the page', 'simpleform' ) ?></option><?php foreach ($pages as $page) { ?><option value="<?php echo $page->ID; ?>" tag="<?php echo $page->post_status; ?>" <?php selected( $privacy_page, $page->ID ); ?>><?php echo $page->post_title; ?></option><?php } ?></select><input type="hidden" id="page-id" name="page-id" value=""><input type="submit" name="submit" id="set-page" class="privacy-setting button unseen" value="<?php esc_attr_e('Use This Page', 'simpleform' ) ?>" page="<?php echo $privacy_page ?>"><span id="label-error"></span><p id="post-status" class="description"><?php echo $privacy_status ?></p><span id="set-page-icon" class="privacy-setting dashicons dashicons-plus unseen <?php echo $color ?>" page="<?php echo $privacy_page ?>"></span></td></tr>
<?php }	?>

<tr class="trconsent <?php if ($consent_field == 'hidden') {echo 'unseen';} ?>"><th class="option"><span><?php _e('Consent Field Requirement','simpleform') ?></span></th><td class="checkbox-switch notes"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="consent-requirement" id="consent-requirement" class="sform-switch" value="required" <?php checked( $consent_requirement, 'required'); ?>><span></span></label><label for="consent-requirement" class="switch-label"><?php _e('Make this a required field','simpleform') ?></label></div><p class="description"><?php _e('If you\'re collecting personal data, this field is required for requesting the user\'s explicit consent','simpleform') ?></p></td></tr>

<?php if ( $show_for == 'all' ) { ?>
<tr><th class="option"><span><?php _e('Captcha Field','simpleform') ?></span></th><td class="select"><select name="captcha-field" id="captcha-field" class="sform"><option value="visible" <?php selected( $captcha_field, 'visible'); ?>><?php _e('Display to all users','simpleform') ?></option><option value="registered" <?php selected( $captcha_field, 'registered'); ?>><?php _e('Display only to registered users','simpleform') ?></option><option value="anonymous" <?php selected( $captcha_field, 'anonymous'); ?>><?php _e('Display only to anonymous users','simpleform') ?></option><option value="hidden" <?php selected( $captcha_field, 'hidden'); ?>><?php _e('Do not display','simpleform') ?></option></select></td></tr>
<?php } else { ?>
<tr><th class="option"><span><?php _e('Captcha Field','simpleform') ?></span></th><td class="checkbox-switch notes"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="captcha-field" id="captcha-field" field="captcha" class="sform-switch cbfield" value="hidden" <?php checked( $captcha_field, 'hidden'); ?>><span></span></label><label for="captcha-field" class="switch-label"><?php _e('Do not display','simpleform') ?></label></div><p class="description"><?php printf( __('You have set the form as visible only for %s', 'simpleform' ), $target ) ?></p></td></tr>
<?php } ?>

<?php
$extra_field = '';
if (has_action('sform_recaptcha_field')):
do_action('sform_recaptcha_field', $attributes);
endif;
$gcaptcha = apply_filters( 'sform_captcha_type', $attributes, $extra_field );
$extra_class = $captcha_field == 'hidden' ? 'unseen' : '';
$extra_class .= !has_action('sform_recaptcha_field') ? ' trcaptchalabel' : ' clabel';
$extra_class .= !is_array($gcaptcha) && $gcaptcha != '' ? ' unseen' : '';
?>

<tr class="trcaptcha <?php echo $extra_class ?>"><th class="option"><span><?php _e('Captcha Field Label','simpleform') ?></span></th><td class="text"><input type="text" id="captcha-label" name="captcha-label" class="sform" placeholder="<?php esc_attr_e('Enter a label for the captcha field','simpleform') ?>" value="<?php echo $math_captcha_label ?>"></td></tr>

<tr><th class="option"><span><?php _e('Submit Button Label','simpleform') ?></span></th><td class="last text"><input type="text" name="submit-label" id="submit-label" class="sform" placeholder="<?php esc_attr_e('Enter a label for the submit field','simpleform') ?>" value="<?php echo $submit_label ?>"</td></tr>

</tbody></table></div>
</div>

<div id="tab-appearance" class="navtab unseen">
	
<h2 id="h2-layout" class="options-heading"><span class="heading" section="layout"><?php _e( 'Layout', 'simpleform' ); ?><span class="toggle dashicons dashicons-arrow-up-alt2 layout"></span></span></h2>

<div class="section layout"><table class="form-table layout"><tbody>

<tr><th class="option"><span><?php _e( 'Label Position', 'simpleform' ) ?></span></th><td class="radio"><fieldset><label for="top-position"><input type="radio" name="label-position" id="top-position" value="top" <?php checked( $label_position, 'top'); ?> ><?php _e( 'Top', 'simpleform' ) ?></label><label for="inline-position"><input type="radio" name="label-position" id="inline-position" value="inline" <?php checked( $label_position, 'inline'); ?>><?php _e( 'Inline', 'simpleform' ) ?></label></fieldset></td></tr>

<tr class="trname trlastname <?php if ( $name_field =='hidden' || $lastname_field =='hidden') {echo 'unseen';}?>" ><th class="option" ><span><?php _e( 'Single Column Last Name Field', 'simpleform' ) ?></span></th><td class="radio"><fieldset><label for="single-line-lastname"><input type="radio" name="lastname-alignment" id="single-line-lastname" value="alone" <?php checked( $lastname_alignment, 'alone'); ?> ><?php _e( 'Place on a single line','simpleform') ?></label><label for="name-line" ><input type="radio" name="lastname-alignment" id="name-line" value="name" <?php checked( $lastname_alignment, 'name'); ?>><?php _e( 'Place next to name field on the same line','simpleform') ?></label></fieldset></td></tr>

<tr class="tremail trphone <?php if ( $email_field =='hidden' || $phone_field =='hidden') {echo 'unseen';}?>" ><th class="option" ><span><?php _e( 'Single Column Phone Field', 'simpleform' )?></span></th><td class="radio"><fieldset><label for="single-line-phone"><input type="radio" name="phone-alignment" id="single-line-phone"value="alone" <?php checked( $phone_alignment, 'alone'); ?> ><?php _e( 'Place on a single line','simpleform') ?></label><label for="email-line" ><input type="radio" name="phone-alignment" id="email-line" value="email" <?php checked( $phone_alignment, 'email'); ?>><?php _e( 'Place next to email field on the same line','simpleform') ?></label></fieldset></td></tr>

<tr><th class="option"><span><?php _e('Submit Button Position','simpleform') ?></span></th><td class="select last"><select name="submit-position" id="submit-position" class="sform"><option value="left" <?php selected( $submit_position, 'left'); ?>><?php _e('Left','simpleform') ?></option><option value="right" <?php selected( $submit_position, 'right'); ?>><?php _e('Right','simpleform') ?></option><option value="centred" <?php selected( $submit_position, 'centred'); ?>><?php _e('Centred','simpleform') ?></option><option value="full" <?php selected( $submit_position, 'full'); ?>><?php _e('Full Width','simpleform') ?></option></select></td></tr>

</tbody></table></div>

<h2 id="h2-style" class="options-heading"><span class="heading" section="style"><?php _e( 'Style', 'simpleform' ); ?><span class="toggle dashicons dashicons-arrow-up-alt2 style"></span></span></h2>

<div class="section style"><table class="form-table style"><tbody>

<tr><th class="option"><span><?php _e( 'Label Size', 'simpleform' ) ?></span></th><td class="select"><select name="label-size" id="label-size" class="sform"><option value="smaller" <?php selected( $label_size, 'smaller'); ?>><?php _e('Smaller','simpleform') ?></option><option value="default" <?php selected( $label_size, 'default'); ?>><?php _e('Default','simpleform') ?></option><option value="larger" <?php selected( $label_size, 'larger'); ?>><?php _e('Larger','simpleform') ?></option></select></td></tr>

<tr><th class="option"><span><?php _e('Required Field Symbol','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="required-sign" id="required-sign" class="sform-switch field-label" value="true" <?php checked( $required_sign, 'true'); ?>><span></span></label><label for="required-sign" class="switch-label"><?php _e('Use an asterisk at the end of the label to mark a required field','simpleform') ?></label></div></td></tr>

<tr class="trsign <?php if ($required_sign =='true') { echo 'unseen'; } ?>"><th class="option"><span><?php _e( 'Replacement Word', 'simpleform' ) ?></span></th><td class="text notes"><input type="text" name="required-word" id="required-word" class="sform" placeholder="<?php esc_attr_e( 'Enter a word to mark a required field or an optional field', 'simpleform' ) ?>" value="<?php echo $required_word; ?>" \><p class="description"><?php _e( 'The replacement word will be placed at the end of the field label, except for the consent and captcha fields. If you hide the label, remember to include it in the placeholder!', 'simpleform' ) ?></p></td></tr>

<tr class="trsign <?php if ($required_sign =='true') { echo 'unseen'; } ?>" ><th class="option" ><span><?php _e( 'Required/Optional Field Labelling', 'simpleform' ) ?></span></th><td class="radio"><fieldset><label for="required-labelling"><input type="radio" name="word-position" id="required-labelling" value="required" <?php checked( $word_position, 'required'); ?> ><?php _e( 'Use the replacement word to mark a required field','simpleform') ?></label><label for="optional-labelling"><input type="radio" name="word-position" id="optional-labelling" value="optional" <?php checked( $word_position, 'optional'); ?>><?php _e( 'Use the replacement word to mark an optional field','simpleform') ?></label></fieldset></td></tr>

<tr><th class="option"><span><?php _e( 'Form Direction', 'simpleform' ) ?></span></th><td class="radio"><fieldset><label for="ltr-direction"><input type="radio" name="form-direction" id="ltr-direction" value="ltr" <?php checked( $form_direction, 'ltr'); ?> ><?php _e( 'Left to Right', 'simpleform' ) ?></label><label for="rtl-direction"><input type="radio" name="form-direction" id="rtl-direction" value="rtl" <?php checked( $form_direction, 'rtl'); ?> ><?php _e( 'Right to Left', 'simpleform' ) ?></label></fieldset></td></tr>

<tr><th class="option"><span><?php _e( 'Additional CSS', 'simpleform' ) ?></span></th><td class="textarea last"><textarea class="sform" name="additional-css" id="additional-css" placeholder="<?php esc_attr_e( 'Add your own CSS code to customize the appearance of your form', 'simpleform' ) ?>" ><?php echo $additional_css; ?></textarea><p class="description"><?php _e('Be careful to correctly identify the form elements using their id, otherwise the CSS rules apply to all your forms!', 'simpleform' ) ?></p></td></tr>

</tbody></table></div>
</div>	

<div id="submit-wrap"><div id="alert-wrap">
<noscript><div id="noscript"><?php _e('You need JavaScript enabled to edit form. Please activate it. Thanks!', 'simpleform' ) ?></div></noscript>
<div id="message-wrap" class="message"></div>
</div>

<input type="submit" name="save-attributes" id="save-attributes" class="submit-button" value="<?php esc_attr_e( 'Save Changes', 'simpleform' ) ?>">

<?php  wp_nonce_field( 'ajax-verification-nonce', 'verification_nonce'); ?>
</form></div>

<?php
} else { ?>
<span><?php _e('It seems the form is no longer available!', 'simpleform' ) ?></span><p><span class="wp-core-ui button unavailable <?php echo $color ?>"><a href="<?php echo menu_page_url( 'sform-editor', false ); ?>"><?php _e('Reload the Form Editor page','simpleform') ?></a></span><span class="wp-core-ui button unavailable <?php echo $color ?>"><a href="<?php echo menu_page_url( 'sform-creation', false ); ?>"><?php _e('Add New Form','simpleform') ?></a></span><span class="wp-core-ui button unavailable <?php echo $color ?>"><a href="<?php echo self_admin_url('widgets.php'); ?>"><?php _e('Activate SimpleForm Contact Form Widget','simpleform') ?></a></span></p>
<?php }