<?php
/* controlling view of cache settings*/
if ( !defined( 'ABSPATH' ) ) {
    die;
}
$errors = '';
if (!get_option('aeh_expires_headers_minify_settings')){
	$aeh_expires_headers_minify_settings = $defaults;
  update_option('aeh_expires_headers_minify_settings',$defaults);
}else{
	$aeh_expires_headers_minify_settings = get_option('aeh_expires_headers_minify_settings');
}
if (isset($_POST['aeh_expires_headers_minify_settings']['aeh_expires_headers_minify_submit']) && wp_verify_nonce( $_POST['aeh_nonce_header'], 'aeh_minify_submit' )){
  if($_POST['aeh_expires_headers_minify_settings']['escape_minify']){
    $aeh_expires_headers_settings_escape_string = AEH_Settings::get_instance()->parse_expires_headers_minify_escape_settings($_POST['aeh_expires_headers_minify_settings']['escape_minify']);
    unset($_POST['aeh_expires_headers_minify_settings']['escape_minify']);
  }
  $aeh_expires_headers_minify_settings = AEH_Settings::get_instance()->parse_expires_headers_minify_settings($_POST['aeh_expires_headers_minify_settings']);
  if($aeh_expires_headers_minify_settings){
    if(isset($aeh_expires_headers_settings_escape_string) && !empty($aeh_expires_headers_settings_escape_string)){
      $aeh_expires_headers_minify_settings['escape_minify'] =  $aeh_expires_headers_settings_escape_string;
      $_POST['aeh_expires_headers_minify_settings']['escape_minify'] = $aeh_expires_headers_settings_escape_string;
    }
    update_option ('aeh_expires_headers_minify_settings',$aeh_expires_headers_minify_settings);
    echo "<script>
            jQuery(document).ready(function(){
               M.toast({html: 'Setting Saved!', classes: 'rounded teal', displayLength:4000});
            });
        </script>";
        $aeh_expires_headers_minify_settings= $_POST['aeh_expires_headers_minify_settings'];
  }else {
      $errors .= 'Some error occured while saving your settings please try again!<br>/';
      $aeh_expires_headers_minify_settings= $_POST['aeh_expires_headers_minify_settings'];
  }

}
?>
<div id="test-2" class="col s12 aeh-options">
  <div class="col s12" style="margin-top:15px;vertical-align:bottom">
    <h5 class="left margin-zero" style="margin:0px">Minify Settings</h5>
    <a href="https://www.addexpiresheaders.com/pro-features/" target="_blank" style="margin-left:15px" class="waves-effect waves-light btn-small right"><i class="material-icons left">book</i>Guide</a>
  </div>
  <div class="clearfix" style="clear:both"></div>
  <div class="divider" style="margin-top:15px"></div>
  <form action="" method="POST">
    <div class="switch"  style="margin-top:15px">
      <label>
        <input type="checkbox" name="aeh_expires_headers_minify_settings[process_css]" <?php checked(isset($aeh_expires_headers_minify_settings['process_css']) && (!empty($aeh_expires_headers_minify_settings['process_css']))); ?>>
        <span class="lever"></span>
        <span>Enable CSS Settings</span>
      </label>
    </div>
    <div style="margin:15px 0px 0px 25px">
      <label>
        <input type="checkbox" name="aeh_expires_headers_minify_settings[min_css]" <?php checked(isset($aeh_expires_headers_minify_settings['min_css']) && (!empty($aeh_expires_headers_minify_settings['min_css']))); ?>>
        <span>Enable CSS Minification</span>
      </label>
    </div>
    <div style="margin:15px 0px 0px 25px">
      <label>
        <input type="checkbox" name="aeh_expires_headers_minify_settings[inline_footer_css]" <?php checked(isset($aeh_expires_headers_minify_settings['inline_footer_css']) && (!empty($aeh_expires_headers_minify_settings['inline_footer_css']))); ?>>
        <span>Inline Footer CSS</span>
      </label>
    </div>
    <div style="margin:15px 0px 0px 25px">
      <label>
        <input type="checkbox" name="aeh_expires_headers_minify_settings[async_css]" <?php checked(isset($aeh_expires_headers_minify_settings['async_css']) && (!empty($aeh_expires_headers_minify_settings['async_css']))); ?>>
        <span>Async CSS Loading</span>
      </label>
    </div>
    <div class="divider" style="margin-top:15px"></div>
    <div class="switch"  style="margin-top:15px">
      <label>
        <input type="checkbox" name="aeh_expires_headers_minify_settings[min_html]" <?php checked(isset($aeh_expires_headers_minify_settings['min_html']) && (!empty($aeh_expires_headers_minify_settings['min_html']))); ?>>
        <span class="lever"></span>
        <span>Enable HTML Minification</span>
      </label>
    </div>
    <div class="switch"  style="margin-top:15px">
      <label>
        <input type="checkbox" name="aeh_expires_headers_minify_settings[inline_gfonts]" <?php checked(isset($aeh_expires_headers_minify_settings['inline_gfonts']) && (!empty($aeh_expires_headers_minify_settings['inline_gfonts']))); ?>>
        <span class="lever"></span>
        <span>Inline Google Fonts</span>
      </label>
    </div>
    <div class="divider" style="margin-top:15px"></div>
    <div class="switch"  style="margin-top:15px">
      <label>
        <input type="checkbox" name="aeh_expires_headers_minify_settings[escape_admin]" <?php checked(isset($aeh_expires_headers_minify_settings['escape_admin']) && (!empty($aeh_expires_headers_minify_settings['escape_admin']))); ?>>
        <span class="lever"></span>
        <span>Escape Admin Users in Minification Process</span>
      </label>
    </div>
    <div class="input-field col s12" style="margin-top:30px">
      <textarea placeholder="for example:- dfg.css, someabc.css" name="aeh_expires_headers_minify_settings[escape_minify]" id="textarea1" class="materialize-textarea"><?php if(isset($aeh_expires_headers_minify_settings['escape_minify'])){echo $aeh_expires_headers_minify_settings['escape_minify'];}?></textarea>
      <label for="textarea1">Prevent these resources from processing during minification process</label>
    </div>
    <div class="row center-align">
      <button class="btn waves-effect waves-light" style="margin-top:15px"  type="submit" name="aeh_expires_headers_minify_settings[aeh_expires_headers_minify_submit]">Submit</button>
    </div>
    <?php wp_nonce_field( 'aeh_minify_submit', 'aeh_nonce_header' ); ?>
  </form>
  <div id="test-2" class="col s12 aeh-options">
		<div class="col s12" style="margin-top:15px;vertical-align:bottom">
			<h5 class="left margin-zero" style="margin:0px">Advance Minify Features</h5>
			<a class="waves-effect waves-light btn-small right" href="<?php echo dd_aeh()->get_upgrade_url(); ?>"><i class="material-icons left">local_offer</i>Sign-up for Pro Version!</a>
		</div>
		<div class="clearfix" style="clear:both"></div>
		<div class="divider" style="margin-top:15px"></div>
		<div id="test-2" class="col s6">
	    <ul class="row">
				<li class="valign-wrapper" style="margin-bottom:15px">
				    <i class="material-icons teal-text">check_circle</i>
				    <span style="margin-left:5px">Javascript files Merging</span>
				</li>
				<li class="valign-wrapper" style="margin-bottom:15px">
				    <i class="material-icons teal-text">check_circle</i>
				    <span style="margin-left:5px">Minification of Javascript Files</span>
				</li>
			</ul>
		</div>
		<div id="test-2" class="col s6">
	    <ul class="row">
        <li class="valign-wrapper" style="margin-bottom:15px">
				    <i class="material-icons teal-text">check_circle</i>
				    <span style="margin-left:5px">Defering Processed Javascript Files</span>
				</li>
				<li class="valign-wrapper" style="margin-bottom:15px">
				    <i class="material-icons teal-text">check_circle</i>
				    <span style="margin-left:5px">Stop Defering jQuery Files</span>
				</li>
			</ul>
		</div>
		<div class="clearfix" style="clear:both"></div>
		<div class="divider"></div>
		<div class="row center-align">
			<a class="waves-effect waves-light btn-small top-mar-30" target="_blank" href="https://www.addexpiresheaders.com/pro-features/"><i class="material-icons left">list</i>Learn More About Pro Version!</a>
		</div>
	</div>
</div>
