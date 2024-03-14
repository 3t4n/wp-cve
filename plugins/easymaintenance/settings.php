<?php
$easyMaintenanceNonceCheck = wp_verify_nonce( $_POST['createMaintenanceNonce'], 'easyMaintenanceNonceGet' );
if( current_user_can('administrator') ){
	
	// nonce security
	if($easyMaintenanceNonceCheck){
	
	
	// post validation
	$mantainanceFormbutton = sanitize_text_field($_POST['mantainanceFormbutton']) ? sanitize_text_field($_POST['mantainanceFormbutton']) : '';
	
	$maintainchoicePlugin_messageType = sanitize_text_field($_POST['maintainchoicePlugin_messageType']) ? sanitize_text_field($_POST['maintainchoicePlugin_messageType']) : '';
	
	$maintainchoicePlugin_message_page = sanitize_text_field($_POST['maintainchoicePlugin_message_page']) ? sanitize_text_field($_POST['maintainchoicePlugin_message_page']) : '';
	
	$maintainchoicePlugin_TextToDisplay = sanitize_text_field($_POST['maintainchoicePlugin_TextToDisplay']) ? sanitize_text_field($_POST['maintainchoicePlugin_TextToDisplay']) : '';
	
	$maintainchoicePluginMood = sanitize_text_field($_POST['maintainchoicePluginMood']) ? sanitize_text_field($_POST['maintainchoicePluginMood']) : '';
	

	
	
	
	
// if form submit  then update data	
if($mantainanceFormbutton){
	
	if($maintainchoicePlugin_messageType!=NULL){
		 update_option( "maintainchoicePlugin_messageType", $maintainchoicePlugin_messageType );
	}

	if($maintainchoicePlugin_message_page != NULL){
		 update_option( "maintainchoicePlugin_message_page", $maintainchoicePlugin_message_page);
	}

	if($maintainchoicePlugin_TextToDisplay != NULL){
		update_option( "maintainchoicePlugin_TextToDisplay", $maintainchoicePlugin_TextToDisplay);
	}

	if($maintainchoicePluginMood != NULL){
		 update_option( "maintainchoicePluginMood", $maintainchoicePluginMood );
	}else{
		 update_option( "maintainchoicePluginMood", 'deactive' );
	}
	
}







// validation //
	}
$maintainchoicePluginMoodoption ='';
$maintainchoicePlugin_messageTypeoption ='';
$maintainchoicePlugin_TextToDisplayoption ='';


$maintainchoicePluginMoodoption = get_option("maintainchoicePluginMood");
$maintainchoicePlugin_messageTypeoption = get_option("maintainchoicePlugin_messageType");
$maintainchoicePlugin_TextToDisplayoption = stripslashes(get_option("maintainchoicePlugin_TextToDisplay"));
 
 
	 
 
?>
<h2>Easy Maintenance Settings</h2>
 <form method="post" name="mantainanceForm">
 <?php $easyMaintenanceNonceCreate = wp_create_nonce( 'easyMaintenanceNonceGet' ); ?>
<input type="hidden" name="createMaintenanceNonce" value="<?php echo $easyMaintenanceNonceCreate ?>" />


 <label><input name="maintainchoicePluginMood" <?php if($maintainchoicePluginMoodoption == 'active'){ echo 'checked="checked"';} ?>  type="checkbox" value="active" /> Maintenance Mood Active</label>
 <br /><br />
 
     <label><input name="maintainchoicePlugin_messageType" type="radio" value="up" <?php if($maintainchoicePlugin_messageTypeoption == 'up'){ echo 'checked="checked"';} ?> /> Use Page</label>
    <label><input name="maintainchoicePlugin_messageType" type="radio" value="ut" <?php if($maintainchoicePlugin_messageTypeoption == 'ut'){ echo 'checked="checked"';} ?>/> Use Custom Text</label>
    <br /><br />
    
    <select class="form-control" id="maintainchoicePlugin_message_page" name="maintainchoicePlugin_message_page">
    <option value="">-- Select Maitain Message Page--</option>
<?php
$pages = get_pages(); 
foreach ( $pages as $page ) {

?>
<option value="<?php echo $page->post_name; ?>" <?php  echo (get_option("maintainchoicePlugin_message_page")=="$page->post_name") ? 'selected' : ''; ?>><?php echo $page->post_title; ?></option>

<?php
}
?>
  </select>
  <br /><br />
  
  
<?php 

wp_editor(  $maintainchoicePlugin_TextToDisplayoption , 'maintainchoicePlugin_TextToDisplay', $settings = array (


    'mode'                              => 'none',


    'onpageload'                        => 'switchEditors.edInit',


    'width'                             => '50%',


    'theme'                             => 'advanced',


    'skin'                              => 'wp_theme',


    'theme_advanced_buttons1'           => "$mce_buttons",


    'theme_advanced_buttons2'           => "$mce_buttons_2",


    'theme_advanced_buttons3'           => "$mce_buttons_3",


    'theme_advanced_buttons4'           => "$mce_buttons_4",


    'language'                          => "$mce_locale",


    'spellchecker_languages'            => "$mce_spellchecker_languages",


    'theme_advanced_toolbar_location'   => 'top',


    'theme_advanced_toolbar_align'      => 'left',


    'theme_advanced_statusbar_location' => 'bottom',


    'theme_advanced_resizing'           => true,


    'theme_advanced_resize_horizontal'  => false,


    'dialog_type'                       => 'modal',


    'relative_urls'                     => false,


    'remove_script_host'                => false,


    'convert_urls'                      => false,


    'apply_source_formatting'           => false,


    'remove_linebreaks'                 => true,


    'paste_convert_middot_lists'        => true,


    'paste_remove_spans'                => true,


    'paste_remove_styles'               => true,


    'gecko_spellcheck'                  => true,


    'entities'                          => '38,amp,60,lt,62,gt',


    'accessibility_focus'               => true,


    'tab_focus'                         => ':prev,:next',


    'content_css'                       => "$mce_css",


    'save_callback'                     => 'switchEditors.saveCallback',


    'wpeditimage_disable_captions'      => $no_captions,


    'plugins'                           => "$plugins"


) ); ?> 
<br /><br />
 <button type="submit" name="mantainanceFormbutton" value="submitdone" class="btn btn-default">Save Settings</button>



</form> 
<?php } 
 
?>