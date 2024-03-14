<?php
if ( ! defined( 'WPINC' ) ) die;

$settings = get_option("sform_settings");
$admin_notices = ! empty( $settings['admin_notices'] ) ? esc_attr($settings['admin_notices']) : 'false';
$color = ! empty( $settings['admin_color'] ) ? esc_attr($settings['admin_color']) : 'default';
$embed_in = isset( $_REQUEST['post'] ) ? absint($_REQUEST['post']) : '';
$notice = '';
$extra_option = '';
?>

<div id="sform-wrap" class="sform">

<div id="new-release" class="<?php if ( $admin_notices == 'true' ) {echo 'invisible';} ?>"><?php echo apply_filters( 'sform_update', $notice ); ?>&nbsp;</div>

<div class="full-width-bar <?php echo $color ?>"><h1 class="title <?php echo $color ?>"><span class="dashicons dashicons-plus-alt responsive"></span><?php _e( 'Add New', 'simpleform' ); ?>


<a href="<?php echo esc_url(get_admin_url(get_current_blog_id(), 'admin.php?page=sform-forms')) ?>"><span class="dashicons dashicons-list-view icon-button admin <?php echo $color ?>"></span><span class="wp-core-ui button admin back-list <?php echo $color ?>"><?php _e( 'Back to forms', 'simpleform' ) ?></span></a>

</h1></div>

<div id="page-description"><p><?php _e( 'Adding a new form is quick and easy. Do it whenever you need it!','simpleform') ?></p></div>

<div id="editor-tabs"><a class="nav-tab nav-tab-active" id="builder"><?php _e( 'Form Builder','simpleform') ?></a><a class="nav-tab" id="appearance"><?php _e( 'Form Appearance','simpleform') ?></a></div>
						
<form id="attributes" method="post" class="<?php echo $color ?>">

<div id="tab-builder" class="navtab">
		
<?php
$show_for = isset($_GET['showfor']) && in_array($_GET['showfor'], array('all', 'in', 'out')) ? sanitize_text_field($_GET['showfor']) : 'all';
$name_field = $show_for == 'out' ? 'anonymous' : 'registered';
$email_field = $show_for == 'out' ? 'anonymous' : 'registered';
$subject_field = $show_for == 'out' ? 'anonymous' : 'registered';
$consent_field = $show_for == 'out' ? 'anonymous' : 'registered';
$target = $show_for == 'out' ? __( 'logged-out users','simpleform') : __( 'logged-in users','simpleform');
?>	
		
<h2 id="h2-specifics" class="options-heading"><span class="heading" section="specifics"><?php _e( 'Specifics', 'simpleform' ); ?><span class="toggle dashicons dashicons-arrow-up-alt2 specifics"></span></span></h2>

<div class="section specifics"><table class="form-table specifics"><tbody>

<tr><th class="option"><span><?php _e('Form Name','simpleform') ?></span></th><td class="text"><input type="text" name="form-name" id="form-name" class="sform" placeholder="<?php esc_attr_e('Enter a name for this Form','simpleform') ?>" value=""></td></tr>

<tr><th class="option"><span><?php _e('Show for','simpleform') ?></span></th><td class="select <?php if ( $show_for !='in') {echo 'last';} ?>"><select name="show-for" id="show-for" class="sform"><option value="all" <?php selected( $show_for, 'all') ?>><?php _e('Everyone','simpleform') ?></option><option value="in" <?php selected( $show_for, 'in') ?>><?php _e('Logged-in users','simpleform') ?></option><option value="out" <?php selected( $show_for, 'out') ?>><?php _e('Logged-out users','simpleform') ?></option></select></td></tr>

<tr class="trlevel <?php if ( $show_for !='in') {echo 'unseen';} ?>"><th class="option"><span><?php _e('Role','simpleform') ?></span></th><td class="last select"><select name="user-role" id="user-role" class="sform"><option value="any" selected="selected"><?php _e('Any','simpleform') ?></option><?php wp_dropdown_roles('any'); ?></select></td></tr>

<input type="hidden" id="newform" name="newform" value="true">
<input type="hidden" id="embed-in" name="embed-in" value="<?php echo $embed_in ?>">

</tbody></table></div>

<h2 id="h2-formdescription" class="options-heading"><span class="heading" section="formdescription"><?php _e( 'Description', 'simpleform' ); ?><span class="toggle dashicons dashicons-arrow-up-alt2 formdescription"></span></span></h2>

<div class="section formdescription"><table class="form-table formdescription"><tbody>

<tr><th class="option"><span><?php _e( 'Text above Form', 'simpleform' ) ?></span></th><td class="textarea"><textarea name="introduction-text" id="introduction-text" class="sform description" placeholder="<?php esc_attr_e( 'Enter the text that must be displayed above the form. It can be used to provide a description or instructions for filling in the form.', 'simpleform' ) ?>" ><p><?php _e( 'Please fill out the form below and we will get back to you as soon as possible. Mandatory fields are marked with (*).', 'simpleform' ) ?></p></textarea><p class="description"><?php _e( 'The HTML tags for formatting message are allowed', 'simpleform' ) ?></p></td></tr>

<tr><th class="option"><span><?php _e( 'Text below Form', 'simpleform' ) ?></span></th><td class="textarea last"><textarea name="bottom-text" id="bottom-text" class="sform description" placeholder="<?php  esc_attr_e( 'Enter the text that must be displayed below the form. It can be used to provide additional information.', 'simpleform' ) ?>" ></textarea><p class="description"><?php _e( 'The HTML tags for formatting message are allowed', 'simpleform' ) ?></p></td></tr>

</tbody></table></div>

<h2 id="h2-formfields" class="options-heading"><span class="heading" section="formfields"><?php _e( 'Fields', 'simpleform' ); ?><span class="toggle dashicons dashicons-arrow-up-alt2 formfields"></span></span></h2>

<div class="section formfields"><table class="form-table formfields"><tbody>

<?php if ( $show_for == 'all' ) { ?>
<tr><th class="option"><span><?php _e('Name Field','simpleform') ?></span></th><td class="select"><select name="name-field" id="name-field" class="sform"><option value="visible" selected="selected"><?php _e('Display to all users','simpleform') ?></option><option value="registered"><?php _e('Display only to registered users','simpleform') ?></option><option value="anonymous"><?php _e('Display only to anonymous users','simpleform') ?></option><option value="hidden"><?php _e('Do not display','simpleform') ?></option></select></td></tr>
<?php } else { ?>
<tr><th class="option"><span><?php _e('Name Field','simpleform') ?></span></th><td class="checkbox-switch notes"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="name-field" id="name-field" field="name" class="sform-switch cbfield" value="<?php echo $name_field ?>"><span></span></label><label for="name-field" class="switch-label"><?php _e('Do not display','simpleform') ?></label></div><p class="description"><?php printf( __('You have set the form as visible only for %s', 'simpleform' ), $target ) ?></p></td></tr>
<?php } ?>

<tr class="trname"><th class="option"><span><?php _e('Name Field Label Visibility','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="name-visibility" id="namelabel" class="sform-switch field-label" value="visible"><span></span></label><label for="namelabel" class="switch-label"><?php _e('Hide label for name field','simpleform') ?></label></div></td></tr>

<tr class="trname namelabel" ><th class="option"><span><?php _e('Name Field Label','simpleform') ?></span></th><td class="text"><input class="sform" name="name-label" placeholder="<?php esc_attr_e('Enter a label for the name field','simpleform') ?>" id="name-label" type="text" value="<?php esc_attr_e( 'Name', 'simpleform' ) ?>"</td></tr>		
		
<tr class="trname" ><th class="option"><span><?php _e('Name Field Placeholder','simpleform') ?></span></th><td class="text"><input class="sform" name="name-placeholder" placeholder="<?php esc_attr_e('Enter a placeholder for the name field. If blank, it will not be used!','simpleform') ?>" id="name-placeholder" type="text" value=""</td></tr>
	
<tr class="trname" ><th class="option"><span><?php _e( 'Name\'s Minimum Length', 'simpleform' ) ?></span></th><td class="text"><input name="name-minlength" id="name-minlength" type="number" class="sform" min="0" max="80" value="2"><span class="description left"><?php _e('Notice that 0 means no minimum limit','simpleform') ?></span></td></tr>

<tr class="trname" ><th class="option"><span><?php _e( 'Name\'s Maximum Length', 'simpleform' ) ?></span></th><td class="text"><input name="name-maxlength" id="name-maxlength" type="number" class="sform" min="0" max="80" value="0"><span class="description left"><?php _e('Notice that 0 means no maximum limit','simpleform') ?></span></td></tr>

<tr class="trname"><th class="option"><span><?php _e('Name Field Requirement','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="name-requirement" id="name-requirement" class="sform-switch" value="required" checked="checked"><span></span></label><label for="name-requirement" class="switch-label"><?php _e('Make this a required field','simpleform') ?></label></div></td></tr>

<?php if ( $show_for == 'all' ) { ?>
<tr><th class="option"><span><?php _e('Last Name Field','simpleform') ?></span></th><td class="select"><select name="lastname-field" id="lastname-field" class="sform"><option value="visible"><?php _e('Display to all users','simpleform') ?></option><option value="registered"><?php _e('Display only to registered users','simpleform') ?></option><option value="anonymous"><?php _e('Display only to anonymous users','simpleform') ?></option><option value="hidden" selected="selected"><?php _e('Do not display','simpleform') ?></option></select></td></tr>
<?php } else { ?>
<tr><th class="option"><span><?php _e('Last Name Field','simpleform') ?></span></th><td class="checkbox-switch notes"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="lastname-field" id="lastname-field" field="lastname" class="sform-switch cbfield" value="hidden" checked="checked"><span></span></label><label for="lastname-field" class="switch-label"><?php _e('Do not display','simpleform') ?></label></div><p class="description"><?php printf( __('You have set the form as visible only for %s', 'simpleform' ), $target ) ?></p></td></tr>
<?php } ?>

<tr class="trlastname unseen"><th class="option"><span><?php _e('Last Name Field Label Visibility','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="lastname-visibility" id="lastnamelabel" class="sform-switch field-label" value="visible"><span></span></label><label for="lastnamelabel" class="switch-label"><?php _e('Hide label for last name field','simpleform') ?></label></div></td></tr>

<tr class="trlastname lastnamelabel unseen"><th class="option"><span><?php _e('Last Name Field Label','simpleform') ?></span></th><td class="text"><input class="sform" name="lastname-label" placeholder="<?php esc_attr_e('Enter a label for the last name field','simpleform') ?>" id="lastname-label" type="text" value='<?php esc_attr_e( 'Last Name', 'simpleform' ) ?>'</td></tr>		
<tr class="trlastname unseen"><th class="option"><span><?php _e('Last Name Field Placeholder','simpleform') ?></span></th><td class="text"><input class="sform" name="lastname-placeholder" placeholder="<?php esc_attr_e('Enter a placeholder for the last name field. If blank, it will not be used!','simpleform') ?>" id="lastname-placeholder" type="text" value=""</td></tr>	
<tr class="trlastname unseen"><th class="option"><span><?php _e( 'Last Name\'s Minimum Length', 'simpleform' ) ?></span></th><td class="text"><input name="lastname-minlength" id="lastname-minlength" type="number" class="sform" min="0" max="80" value="2"><span class="description left"><?php _e('Notice that 0 means no minimum limit','simpleform') ?></span></td></tr>

<tr class="trlastname unseen"><th class="option"><span><?php _e( 'Last Name\'s Maximum Length', 'simpleform' ) ?></span></th><td class="text"><input name="lastname-maxlength" id="lastname-maxlength" type="number" class="sform" min="0" max="80" value="0"><span class="description left"><?php _e('Notice that 0 means no maximum limit','simpleform') ?></span></td></tr>

<tr class="trlastname unseen"><th class="option"><span><?php _e('Last Name Field Requirement','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="lastname-requirement" id="lastname-requirement" class="sform-switch" value="optional"><span></span></label><label for="lastname-requirement" class="switch-label"><?php _e('Make this a required field','simpleform') ?></label></div></td></tr>

<?php if ( $show_for == 'all' ) { ?>
<tr><th class="option"><span><?php _e('Email Field','simpleform') ?></span></th><td class="select"><select name="email-field" id="email-field" class="sform"><option value="visible" selected="selected"><?php _e('Display to all users','simpleform') ?></option><option value="registered"><?php _e('Display only to registered users','simpleform') ?></option><option value="anonymous"><?php _e('Display only to anonymous users','simpleform') ?></option><option value="hidden"><?php _e('Do not display','simpleform') ?></option></select></td></tr>
<?php } else { ?>
<tr><th class="option"><span><?php _e('Email Field','simpleform') ?></span></th><td class="checkbox-switch notes"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="email-field" id="email-field" field="email" class="sform-switch cbfield" value="<?php echo $email_field ?>"><span></span></label><label for="email-field" class="switch-label"><?php _e('Do not display','simpleform') ?></label></div><p class="description"><?php printf( __('You have set the form as visible only for %s', 'simpleform' ), $target ) ?></p></td></tr>
<?php } ?>

<tr class="tremail"><th class="option"><span><?php _e('Email Field Label Visibility','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="email-visibility" id="emaillabel" class="sform-switch field-label" value="visible"><span></span></label><label for="emaillabel" class="switch-label"><?php _e('Hide label for email field','simpleform') ?></label></div></td></tr>

<tr class="tremail emaillabel" ><th class="option"><span><?php _e('Email Field Label','simpleform') ?></span></th><td class="text"><input class="sform" name="email-label" placeholder="<?php esc_attr_e('Enter a label for the email field','simpleform') ?>" id="email-label" type="text" value='<?php echo esc_attr_e( 'Email', 'simpleform' ) ?>'</td></tr>		
	
<tr class="tremail" ><th class="option"><span><?php _e('Email Field Placeholder','simpleform') ?></span></th><td class="text"><input class="sform" name="email-placeholder" placeholder="<?php esc_attr_e('Enter a placeholder for the email field. If blank, it will not be used!','simpleform') ?>" id="email-placeholder" type="text" value=""</td></tr>		
		
<tr class="tremail"><th class="option"><span><?php _e('Email Field Requirement','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="email-requirement" id="email-requirement" class="sform-switch" value="required" checked="checked"><span></span></label><label for="email-requirement" class="switch-label"><?php _e('Make this a required field','simpleform') ?></label></div></td></tr>

<?php if ( $show_for == 'all' ) { ?>
<tr><th class="option"><span><?php _e('Phone Field','simpleform') ?></span></th><td class="select"><select name="phone-field" id="phone-field" class="sform"><option value="visible"><?php _e('Display to all users','simpleform') ?></option><option value="registered"><?php _e('Display only to registered users','simpleform') ?></option><option value="anonymous"><?php esc_html_e('Display only to anonymous users','simpleform') ?></option><option value="hidden" selected="selected"><?php _e('Do not display','simpleform') ?></option></select></td></tr>
<?php } else { ?>
<tr><th class="option"><span><?php _e('Phone Field','simpleform') ?></span></th><td class="checkbox-switch notes"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="phone-field" id="phone-field" field="phone" class="sform-switch cbfield" value="hidden" checked="checked"><span></span></label><label for="phone-field" class="switch-label"><?php _e('Do not display','simpleform') ?></label></div><p class="description"><?php printf( __('You have set the form as visible only for %s', 'simpleform' ), $target ) ?></p></td></tr>
<?php } ?>

<tr class="trphone unseen"><th class="option"><span><?php _e('Phone Field Label Visibility','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="phone-visibility" id="phonelabel" class="sform-switch field-label" value="visible"><span></span></label><label for="phonelabel" class="switch-label"><?php _e('Hide label for phone field','simpleform') ?></label></div></td></tr>

<tr class="trphone phonelabel unseen" ><th class="option"><span><?php _e('Phone Field Label','simpleform') ?></span></th><td class="text"><input class="sform" name="phone-label" placeholder="<?php esc_attr_e('Enter a label for the phone field','simpleform') ?>" id="phone-label" type="text" value='<?php esc_attr_e( 'Phone', 'simpleform' ) ?>'</td></tr>		

<tr class="trphone unseen" ><th class="option"><span><?php _e('Phone Field Placeholder','simpleform') ?></span></th><td class="text"><input class="sform" name="phone-placeholder" placeholder="<?php esc_attr_e('Enter a placeholder for the phone field. If blank, it will not be used!','simpleform') ?>" id="phone-placeholder" type="text" value=""</td></tr>	

<tr class="trphone unseen"><th class="option"><span><?php _e('Phone Field Requirement','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="phone-requirement" id="phone-requirement" class="sform-switch" value="optional"><span></span></label><label for="phone-requirement" class="switch-label"><?php _e('Make this a required field','simpleform') ?></label></div></td></tr>

<?php if ( $show_for == 'all' ) { ?>
<tr><th class="option"><span><?php _e('Subject Field','simpleform') ?></span></th><td class="select"><select name="subject-field" id="subject-field" class="sform"><option value="visible" selected="selected"><?php _e('Display to all users','simpleform') ?></option><option value="registered"><?php _e('Display only to registered users','simpleform') ?></option><option value="anonymous"><?php _e('Display only to anonymous users','simpleform') ?></option><option value="hidden"><?php _e('Do not display','simpleform') ?></option></select></td></tr>
<?php } else { ?>
<tr><th class="option"><span><?php _e('Subject Field','simpleform') ?></span></th><td class="checkbox-switch notes"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="subject-field" id="subject-field" field="subject" class="sform-switch cbfield" value="<?php echo $subject_field ?>"><span></span></label><label for="subject-field" class="switch-label"><?php _e('Do not display','simpleform') ?></label></div><p class="description"><?php printf( __('You have set the form as visible only for %s', 'simpleform' ), $target ) ?></p></td></tr>
<?php } ?>

<tr class="trsubject"><th class="option"><span><?php _e('Subject Field Label Visibility','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="subject-visibility" id="subjectlabel" class="sform-switch field-label" value="visible"><span></span></label><label for="subjectlabel" class="switch-label"><?php _e('Hide label for subject field','simpleform') ?></label></div></td></tr>

<tr class="trsubject subjectlabel" ><th class="option"><span><?php _e('Subject Field Label','simpleform') ?></span></th><td class="text"><input class="sform" name="subject-label" placeholder="<?php esc_attr_e('Enter a label for the subject field','simpleform') ?>" id="subject-label" type="text" value="<?php esc_attr_e( 'Subject', 'simpleform' ) ?>"></td></tr>

<tr class="trsubject" ><th class="option"><span><?php _e('Subject Field Placeholder','simpleform') ?></span></th><td class="text"><input class="sform" name="subject-placeholder" placeholder="<?php esc_attr_e('Enter a placeholder for the subject field. If blank, it will not be used!','simpleform') ?>" id="subject-placeholder" type="text" value=""</td></tr>		
<tr class="trsubject" ><th class="option"><span><?php _e( 'Subject\'s Minimum Length', 'simpleform' ) ?></span></th><td class="text"><input name="subject-minlength" id="subject-minlength" type="number" class="sform" min="0" max="80" value="5"><span class="description left"><?php _e('Notice that 0 means no minimum limit','simpleform') ?></span></td></tr>

<tr class="trsubject" ><th class="option"><span><?php _e( 'Subject\'s Maximum Length', 'simpleform' ) ?></span></th><td class="text"><input name="subject-maxlength" id="subject-maxlength" type="number" class="sform" min="0" max="80" value="0"><span class="description left"><?php _e('Notice that 0 means no maximum limit','simpleform') ?></span></td></tr>

<tr class="trsubject"><th class="option"><span><?php _e('Subject Field Requirement','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="subject-requirement" id="subject-requirement" class="sform-switch" value="required" checked="checked"><span></span></label><label for="subject-requirement" class="switch-label"><?php _e('Make this a required field','simpleform') ?></label></div></td></tr>

<tr><th class="option"><span><?php _e('Message Field Label Visibility','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="message-visibility" id="messagelabel" class="sform-switch field-label" value="visible"><span></span></label><label for="messagelabel" class="switch-label"><?php _e('Hide label for message field','simpleform') ?></label></div></td></tr>

<tr class="messagelabel"><th class="option"><span><?php _e('Message Field Label','simpleform') ?></span></th><td class="text"><input type="text" class="sform" name="message-label" id="message-label" placeholder="<?php esc_attr_e('Enter a label for the message field','simpleform') ?>" value="<?php esc_attr_e( 'Message', 'simpleform' ) ?>"</td></tr>

<tr><th class="option"><span><?php _e('Message Field Placeholder','simpleform') ?></span></th><td class="text"><input type="text" name="message-placeholder" id="message-placeholder" class="sform" placeholder="<?php esc_attr_e('Enter a placeholder for the message field. If blank, it will not be used!','simpleform') ?>" value=""</td></tr>

<tr><th class="option"><span><?php _e( 'Message\'s Minimum Length', 'simpleform' ) ?></span></th><td class="text"><input type="number" name="message-minlength" id="message-minlength" class="sform" min="5" max="80" value="10"></td></tr>

<tr><th class="option"><span><?php _e( 'Message\'s Maximum Length', 'simpleform' ) ?></span></th><td class="text"><input type="number" name="message-maxlength" id="message-maxlength" class="sform" min="0" max="80" value="0"><span class="description left"><?php _e('Notice that 0 means no maximum limit','simpleform') ?></span></td></tr>

<?php if ( $show_for == 'all' ) { ?>
<tr><th class="option"><span><?php _e('Consent Field','simpleform') ?></span></th><td class="select"><select name="consent-field" id="consent-field" class="sform"><option value="visible" selected="selected"><?php _e('Display to all users','simpleform') ?></option><option value="registered"><?php _e('Display only to registered users','simpleform') ?></option><option value="anonymous"><?php _e('Display only to anonymous users','simpleform') ?></option><option value="hidden"><?php _e('Do not display','simpleform') ?></option></select></td></tr>
<?php } else { ?>
<tr><th class="option"><span><?php _e('Consent Field','simpleform') ?></span></th><td class="checkbox-switch notes"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="consent-field" id="consent-field" field="consent" class="sform-switch cbfield" value="<?php echo $consent_field ?>"><span></span></label><label for="consent-field" class="switch-label"><?php _e('Do not display','simpleform') ?></label></div><p class="description"><?php printf( __('You have set the form as visible only for %s', 'simpleform' ), $target ) ?></p></td></tr>
<?php } ?>

<tr class="trconsent"><th class="option"><span><?php _e('Consent Field Label','simpleform') ?></span></th><td class="textarea"><textarea name="consent-label" id="consent-label" class="sform labels" placeholder="<?php esc_attr_e( 'Enter a label for the consent field', 'simpleform' ) ?>" ><?php _e( 'I have read and consent to the privacy policy', 'simpleform' ) ?></textarea><p class="description"><?php _e( 'The HTML tags for formatting consent field label are allowed', 'simpleform' ) ?></p></td></tr>

<?php $pages = get_pages( array( 'sort_column' => 'post_title', 'sort_order' => 'ASC', 'post_type' => 'page', 'post_status' =>  array('publish','draft') ) ); 
if ( $pages ) { ?>
<tr class="trconsent"><th class="option"><span><?php _e('Link to Privacy Policy','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="privacy-link" id="privacy-link" class="sform-switch" value="false"><span></span></label><label for="privacy-link" class="switch-label"><?php _e('Insert a link to the Privacy Policy page in the consent field label','simpleform') ?></label></div></td></tr>

<tr class="trconsent trpage unseen" ><th class="option"><span><?php _e( 'Privacy Policy Page', 'simpleform' ) ?></span><span id="label-error-top"></span></th><td class="select notes"><select name="privacy-page" class="sform" id="privacy-page"><option value=""><?php _e( 'Select the page', 'simpleform' ) ?></option><?php foreach ($pages as $page) { ?><option value="<?php echo $page->ID; ?>" tag="<?php echo $page->post_status; ?>"><?php echo $page->post_title; ?></option><?php } ?></select><input type="hidden" id="page-id" name="page-id" value=""><input type="submit" name="submit" id="set-page" class="privacy-setting button unseen" value="<?php esc_attr_e('Use This Page', 'simpleform' ) ?>" page="0"><span id="label-error"></span><p id="post-status" class="description">&nbsp;</p><span id="set-page-icon" class="privacy-setting dashicons dashicons-plus unseen <?php echo $color ?>" page="0"></span></td></tr>
<?php }	?>

<tr class="trconsent"><th class="option"><span><?php _e('Consent Field Requirement','simpleform') ?></span></th><td class="checkbox-switch notes"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="consent-requirement" id="consent-requirement" class="sform-switch" value="required" checked="checked"><span></span></label><label for="consent-requirement" class="switch-label"><?php _e('Make this a required field','simpleform') ?></label></div><p class="description"><?php _e('If you\'re collecting personal data, this field is required for requesting the user\'s explicit consent','simpleform') ?></p></td></tr>

<?php if ( $show_for == 'all' ) { ?>
<tr><th class="option"><span><?php _e('Captcha Field','simpleform') ?></span></th><td class="select"><select name="captcha-field" id="captcha-field" class="sform"><option value="visible"><?php _e('Display to all users','simpleform') ?></option><option value="registered"><?php _e('Display only to registered users','simpleform') ?></option><option value="anonymous"><?php _e('Display only to anonymous users','simpleform') ?></option><option value="hidden" selected="selected"><?php _e('Do not display','simpleform') ?></option></select></td></tr>
<?php } else { ?>
<tr><th class="option"><span><?php _e('Captcha Field','simpleform') ?></span></th><td class="checkbox-switch notes"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="captcha-field" id="captcha-field" field="captcha" class="sform-switch cbfield" value="hidden" checked="checked"><span></span></label><label for="captcha-field" class="switch-label"><?php _e('Do not display','simpleform') ?></label></div><p class="description"><?php printf( __('You have set the form as visible only for %s', 'simpleform' ), $target ) ?></p></td></tr>
<?php } 

$attributes = array( 'captcha_field' => 'hidden', 'captcha_type' => 'math' );
if ( has_action('sform_recaptcha_field') ) {
do_action('sform_recaptcha_field', $attributes);
$extra_class = 'clabel';
} else {
$extra_class = 'trcaptchalabel';
}
?>

<tr class="trcaptcha unseen <?php echo $extra_class ?>"><th class="option"><span><?php _e('Captcha Field Label','simpleform') ?></span></th><td class="text"><input type="text" id="captcha-label" name="captcha-label" class="sform" placeholder="<?php esc_attr_e('Enter a label for the captcha field','simpleform') ?>" value="<?php esc_attr_e( 'I\'m not a robot', 'simpleform' ) ?>"></td></tr>

<tr><th class="option"><span><?php _e('Submit Button Label','simpleform') ?></span></th><td class="last text"><input type="text" name="submit-label" id="submit-label" class="sform" placeholder="<?php esc_attr_e('Enter a label for the submit field','simpleform') ?>" value="<?php esc_attr_e( 'Submit', 'simpleform' ) ?>"</td></tr>

</tbody></table></div>
</div>

<div id="tab-appearance" class="navtab unseen">
		
<h2 id="h2-layout" class="options-heading"><span class="heading" section="layout"><?php _e( 'Layout', 'simpleform' ); ?><span class="toggle dashicons dashicons-arrow-up-alt2 layout"></span></span></h2>

<div class="section layout"><table class="form-table layout"><tbody>
	
<tr><th class="option"><span><?php _e( 'Label Position', 'simpleform' ) ?></span></th><td class="radio"><fieldset><label for="top-position"><input type="radio" name="label-position" id="top-position" value="top" checked="checked"><?php _e( 'Top', 'simpleform' ) ?></label><label for="inline-position"><input type="radio" name="label-position" id="inline-position" value="inline"><?php _e( 'Inline', 'simpleform' ) ?></label></fieldset></td></tr>

<tr class="trname trlastname unseen"><th class="option" ><span><?php _e( 'Single Column Last Name Field', 'simpleform' ) ?></span></th><td class="radio"><fieldset><label for="single-line-lastname"><input type="radio" name="lastname-alignment" id="single-line-lastname" value="alone"><?php _e( 'Place on a single line','simpleform') ?></label><label for="name-line" ><input type="radio" name="lastname-alignment" id="name-line" value="name" checked="checked"><?php _e( 'Place next to name field on the same line','simpleform') ?></label></fieldset></td></tr>

<tr class="tremail trphone unseen" ><th class="option" ><span><?php _e( 'Single Column Phone Field', 'simpleform' )?></span></th><td class="radio"><fieldset><label for="single-line-phone"><input type="radio" name="phone-alignment" id="single-line-phone"value="alone"><?php _e( 'Place on a single line','simpleform') ?></label><label for="email-line" ><input type="radio" name="phone-alignment" id="email-line" value="email" checked="checked"><?php _e( 'Place next to email field on the same line','simpleform') ?></label></fieldset></td></tr>

<tr><th class="option"><span><?php _e('Submit Button Position','simpleform') ?></span></th><td class="select last"><select name="submit-position" id="submit-position" class="sform"><option value="left"><?php _e('Left','simpleform') ?></option><option value="right"><?php _e('Right','simpleform') ?></option><option value="centred" selected="selected"><?php _e('Centred','simpleform') ?></option><option value="full"><?php _e('Full Width','simpleform') ?></option></select></td></tr>

</tbody></table></div>

<h2 id="h2-style" class="options-heading"><span class="heading" section="style"><?php _e( 'Style', 'simpleform' ); ?><span class="toggle dashicons dashicons-arrow-up-alt2 style"></span></span></h2>

<div class="section style"><table class="form-table style"><tbody>

<tr><th class="option"><span><?php _e( 'Label Size', 'simpleform' ) ?></span></th><td class="select"><select name="label-size" id="label-size" class="sform"><option value="smaller"><?php _e('Smaller','simpleform') ?></option><option value="default" selected="selected"><?php _e('Default','simpleform') ?></option><option value="larger"><?php _e('Larger','simpleform') ?></option></select></td></tr>

<tr><th class="option"><span><?php _e('Required Field Symbol','simpleform') ?></span></th><td class="checkbox-switch"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="required-sign" id="required-sign" class="sform-switch field-label" value="true" checked="checked"><span></span></label><label for="required-sign" class="switch-label"><?php _e('Use an asterisk at the end of the label to mark a required field','simpleform') ?></label></div></td></tr>

<tr class="trsign unseen"><th class="option"><span><?php _e( 'Replacement Word', 'simpleform' ) ?></span></th><td class="text notes"><input type="text" name="required-word" id="required-word" class="sform" placeholder="<?php esc_attr_e( 'Enter a word to mark a required field or an optional field', 'simpleform' ) ?>" value="<?php esc_attr_e( '(required)', 'simpleform' ) ?>" \><p class="description"><?php _e( 'The replacement word will be placed at the end of the field label, except for the consent and captcha fields. If you hide the label, remember to place it into the placeholder!', 'simpleform' ) ?></p></td></tr>

<tr class="trsign unseen" ><th class="option" ><span><?php _e( 'Required/Optional Field Labelling', 'simpleform' ) ?></span></th><td class="radio"><fieldset><label for="required-labelling"><input type="radio" name="word-position" id="required-labelling" value="required" checked="checked"><?php _e( 'Use the replacement word to mark a required field','simpleform') ?></label><label for="optional-labelling"><input type="radio" name="word-position" id="optional-labelling" value="optional"><?php _e( 'Use the replacement word to mark an optional field','simpleform') ?></label></fieldset></td></tr>

<tr><th class="option"><span><?php _e( 'Form Direction', 'simpleform' ) ?></span></th><td class="radio"><fieldset><label for="ltr-direction"><input type="radio" name="form-direction" id="ltr-direction" value="ltr" checked="checked"><?php _e( 'Left to Right', 'simpleform' ) ?></label><label for="rtl-direction"><input type="radio" name="form-direction" id="rtl-direction" value="rtl"><?php _e( 'Right to Left', 'simpleform' ) ?></label></fieldset></td></tr>

<tr><th class="option"><span><?php _e( 'Additional CSS', 'simpleform' ) ?></span></th><td class="textarea last"><textarea name="additional-css" id="additional-css" class="sform description" placeholder="<?php esc_attr_e( 'Add your own CSS code to customize the appearance of your form', 'simpleform' ) ?>" ></textarea><p class="description"></p></td></tr>

</tbody></table></div>
</div>	

<div id="submit-wrap"><div id="alert-wrap">
<noscript><div id="noscript"><?php _e('You need JavaScript enabled to edit form. Please activate it. Thanks!', 'simpleform' ) ?></div></noscript>
<div id="message-wrap" class="message"></div>
</div>

<input type="submit" name="save-attributes" id="save-attributes" class="submit-button" value="<?php esc_attr_e( 'Create Form', 'simpleform' ) ?>">

<?php  wp_nonce_field( 'ajax-verification-nonce', 'verification_nonce'); ?>
</form></div>