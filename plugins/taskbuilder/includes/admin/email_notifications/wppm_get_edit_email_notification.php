<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wppmfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}
$id = isset($_POST) && isset($_POST['id']) ? intval(sanitize_text_field($_POST['id'])) : 0;
if(!$id) die();
$user_role = get_option('wppm_user_role');
$wppm_email_notificatins = get_option('wppm_email_notification');
$notification_types = $wppmfunction->get_email_notification_types();
$directionality = $wppmfunction->check_rtl();
if(!empty($wppm_email_notificatins)){
  foreach($wppm_email_notificatins as $key=>$email){
    if($id==$key){
      $type = $email['type'];
      $subject = $email['subject'];
      $body = $email['body'];
      $recipients = $email['recipients'];
    }
  }
}
?>
<h4 style="margin-bottom:20px;"><?php echo esc_html_e('Edit email notification','taskbuilder');?></h4>
<form id="wppm_edit_email_notification_settings" method="post" action="javascript:wppm_set_edit_email_notification();">
  <div class="form-group">
    <label for="wppm_en_type"><?php echo esc_html_e('Type','taskbuilder');?></label>
    <p class="help-block"><?php echo esc_html_e('Select event to send this email.','taskbuilder');?></p>
    <select class="form-control" name="wppm_en_type" id="wppm_en_type">
      <?php foreach ($notification_types as $key => $value) :?>
        <option <?php echo esc_attr($key)==esc_attr($type) ?'selected="selected"':''?> value="<?php echo esc_attr($key)?>"><?php echo (esc_attr($value))?></option>
      <?php endforeach;?>
    </select>
  </div>
  <div class="form-group">
    <label for="wppm_en_subject"><?php echo esc_html_e('Email Subject','taskbuilder');?></label>
    <p class="help-block"><?php echo esc_html_e('Subject for email to send.','taskbuilder');?></p>
    <input type="text" class="form-control" name="wppm_en_subject" id="wppm_en_subject" value="<?php echo (stripcslashes(esc_attr($subject))) ?>" />
  </div>
  <div class="form-group">
    <label for="wppm_en_body"><?php echo esc_html_e('Email Body','taskbuilder');?></label>
    <p class="help-block"><?php echo esc_html_e('Body for email to send. Use macros for project and task specific details. Macros will get replaced by its value while sending an email.','taskbuilder');?></p>
    <div class="text-right">
      <button id="visual" class="btn btn-primary btn-xs" type="button" onclick="wppm_get_tinymce('wppm_en_body','email_body');"><?php echo esc_html_e('Visual', 'taskbuilder');?></button>
      <button id="text" class="btn btn-default btn-xs" type="button" onclick="wppm_get_textarea()"><?php echo esc_html_e('Text', 'taskbuilder');?></button>
    </div>
    <?php $allowed_tags = array( 'br' => array(), 'abbr' => array('title' => array(),), 'p' => array(), 'strong' => array(), 'a' => array('href' => array(), 'title' => array(),'target'=> array(), 'rel'=>array()),'em' =>array(),'span' =>array(), 'blockquote'=>array('cite'  => array(),),'div' => array('class' => array(),'title' => array(),'style' => array(),),'ul'=>array(),'li'=>array(),'ol'=>array(),'img' => array( 'alt'=> array(),'class' => array(),'height' => array(),'src'=> array(),'width'=> array(),)); ?>
		<textarea type="text" class="form-control" name="wppm_en_body" id="wppm_en_body"><?php echo htmlentities(wp_kses($body,$allowed_tags))?></textarea>
    <div class="row attachment_link">
        <span onclick="wppm_get_templates(); "><?php echo esc_html_e('Insert Macros','taskbuilder') ?></span>
    </div>
  </div>
  <div class="form-group">
    <label for=""><?php echo esc_html_e('Recipients','taskbuilder');?></label>
    <p class="help-block"><?php echo esc_html_e('Select roles who will receive email notifications.','taskbuilder');?></p>
    <div class="row">
      <?php foreach ($user_role as $key => $role ) : ?>
        <div class="col-sm-4" style="margin-bottom:10px; display:flex;">
          <div style="width:25px;"><input type="checkbox" <?php echo esc_attr(in_array($key,$recipients))?'checked="checked"':''?> name="wppm_en_recipients[]" value="<?php echo esc_attr($key) ?>" /></div>
          <div style="padding-top:3px;"><?php echo esc_html($role['label']) ?></div>
        </div>
      <?php endforeach;?>
      <?php if($type=='new_project' || $type=='new_task'){ 
            $style='display:none';
        } else{
            $style='display:flex';
        }?>
      <div class="col-sm-4 prev_assigned" style="margin-bottom:10px;<?php echo esc_attr($style) ?>">
				<div style="width:25px;"><input type="checkbox" <?php echo esc_attr(in_array('previously_assigned_user',$recipients))?'checked="checked"':''?> name="wppm_en_recipients[]" value="previously_assigned_user" /></div>
				<div style="padding-top:3px;"><?php echo esc_html_e('Previously Assigned Users','taskbuilder')?></div>
			</div>
			<?php do_action('wppm_en_after_edit_recipients',$recipients);?>
		</div>
  </div>
  <?php do_action('wppm_get_edit_email_notification',$id);?>
  
  <button type="submit" class="wppm-submit-btn"><?php echo esc_html_e('Save Changes','taskbuilder');?></button>
  <img class="wppm_submit_wait" style="display:none;" src="<?php echo WPPM_PLUGIN_URL.'asset/images/ajax-loader@2x.gif';?>">
  <input type="hidden" name="action" value="wppm_set_edit_email_notification" />
	<input type="hidden" name="id" value="<?php echo htmlentities(esc_attr($id)) ?>" />
</form>

<script>
  tinymce.remove();
  tinymce.init({ 
    selector:'#wppm_en_body',
    body_id: 'email_body',
    menubar: false,
    statusbar: false,
    height : '200',
    plugins: [
        'lists link image directionality'
    ],
    image_advtab: true,
    toolbar: 'bold italic underline blockquote | alignleft aligncenter alignright | bullist numlist | rtl | link image',
    branding: false,
    autoresize_bottom_margin: 20,
    browser_spellcheck : true,
    relative_urls : false,
    remove_script_host : false,
    convert_urls : true,
    setup: function (editor) {
    }
  });
</script>
<script>
  jQuery('#wppm_en_type').on('change', function(){  
    if(jQuery('#wppm_en_type').val() == 'new_ticket'){
      jQuery('.prev_assigned').css({"display" : "none"});
    } else{
      jQuery('.prev_assigned').css({"display" : "flex"});
    }
  });
</script>