<?php if ( ! defined( 'ABSPATH' ) ) exit;
$opt = get_option('ulm_single_template_options');
if(isset($_POST['save_ulm_settings']) && wp_verify_nonce( $_POST['ulm_settings_nonce_field'], 'ulm_settings_action' )) {
	  _e("<strong>Saving Please wait...</strong>", 'post-template-select');
	  $ulm_settings_page_options['ulm_single_template_cpt'] = array_map( 'sanitize_text_field', wp_unslash( $_POST['ulm_allowed_cpt'] ) );
	  $ulm_settings_page_options['ult_single_template_type'] = sanitize_text_field($_POST['ult_single_template_type']);
	  $save = update_option('ulm_single_template_options', $ulm_settings_page_options );
	  if($save) { $this->redirect('admin.php?page=ulm_post_template&msg=1'); } else { $this->redirect('admin.php?page=ulm_post_template&msg=2'); }
} ?>
<div class="wrap ulm_single_wrap">
<h2><?php _e('Settings', 'post-template-select');?></h2>
<?php if(isset($_GET['msg'])) {
    if($_GET['msg'] == '1') { $this->message(1, 'Settings Saved!');} else {$this->message(2, 'Settings Not Saved!');}
 } ?>
<table width="100%">
	<tbody><tr>
    	<td valign="top">          

<table class="wp-list-table widefat fixed bookmarks">
    <thead>
        <tr>
            <th><?php _e('Post Template Select', 'post-template-select');?></th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>
			<form method="post" action="" name="sp_settings_page_form">
            <?php 
/* Nonce Field */
wp_nonce_field('ulm_settings_action', 'ulm_settings_nonce_field'); 
?>
            <table class="form-table">
               <tbody><tr valign="top">
               <th scope="row"><?php _e('Choose Post Types: ', 'post-template-select');?></th>
                <td scope="row">
                <fieldset>
<?php foreach($this->ulm_get_post_types() as $post_type) {
	if(!empty($opt['ulm_single_template_cpt']) && is_array($opt['ulm_single_template_cpt'])) {
	  if(in_array($post_type, $opt['ulm_single_template_cpt'])) { ?>
		<label for="ulm_allowed_cpt">
        <input name="ulm_allowed_cpt[]" id="<?php echo $post_type; ?>" value="<?php echo $post_type; ?>" type="checkbox" checked ><?php echo ucfirst($post_type); ?>
        </label>
		<?php } else { ?>
        <label for="ulm_allowed_cpt">
<input name="ulm_allowed_cpt[]" id="<?php echo $post_type; ?>" value="<?php echo $post_type; ?>" type="checkbox"><?php echo ucfirst($post_type); ?>
</label>
        <?php } 
	} else { ?>
 <label for="ulm_allowed_cpt">
<input name="ulm_allowed_cpt[]" id="<?php echo $post_type; ?>" value="<?php echo $post_type; ?>" type="checkbox"><?php echo ucfirst($post_type); ?>
</label>
   
<?php } }?>
<p class="description"><?php _e('Choose post types where to use template selector.', 'post-template-select');?></p>
</fieldset></td>
                </tr>
                <tr>
<th scope="row"><?php _e('Templates Type', 'post-template-select');?></th>
<td><input type="radio" name="ult_single_template_type" value="page" <?php echo(isset($opt['ult_single_template_type']) && $opt['ult_single_template_type'] == 'page') ? 'checked="checked"' : ''?>><?php _e('Page Templates (Default) <p><code>/*<br> Template Name: Test <br>*/</code></p>', 'post-template-select');?></option>
<input type="radio" name="ult_single_template_type" value="custom" <?php echo(isset($opt['ult_single_template_type']) && $opt['ult_single_template_type'] == 'custom') ? 'checked="checked"' : ''?>><?php _e('Custom Templates <p><code>/*<br>Post Template Name: Test <br>*/</code></p>', 'post-template-select');?></option>
</td>
</tr>
                            </tbody></table>
            
            <p class="submit">
            <input type="submit" name="submit-bpu" class="button-primary" value="Save Changes">
            </p>
        </form>
            
</td>
</tr>
</tbody>
</table><br>        <table class="wp-list-table widefat fixed bookmarks">
            	<thead>
                <tr>
                	<th><?php _e('Instruction', 'post-template-select');?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                	<td>
                    	<ol>
                        	<li><?php _e('Tick Checkbox of Choose Post Types Option. ', 'post-template-select');?></li>
                            
                            <li><?php _e('Select Template Types Option.', 'post-template-select');?></li>

							<li><?php _e('Then click save changes', 'post-template-select');?></li>
                            
                            <li><?php _e('Choose Template will display on selected post types', 'post-template-select');?></li>
                            
                        </ol>
                    </td>
                </tr>
                </tbody>
            </table>
        
        </td>
        <td width="15">&nbsp;</td>
        <td width="250" valign="top">
        	<table class="wp-list-table widefat fixed bookmarks">
            	<thead>
                <tr>
                	<th><?php _e('Support Open Source Developers', 'post-template-select');?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                	<td align="center">
                    <p><?php _e('If you like my work, please send me a donation to encourage me to do more. Thanks', 'post-template-select');?></p>
                    	<form name="_xclick" action="https://www.paypal.com/yt/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="munishthedeveloper48@gmail.com">
    <input type="hidden" name="item_name" value="Post Template Select">
    <input type="hidden" name="currency_code" value="USD">
    <code>$</code> <input type="text" name="amount" value="" required placeholder="Enter amount">
    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="Make Donations with Paypal">
    </form>
                    </td>
                </tr>
                </tbody>
            </table>
            <br>
            <table class="wp-list-table widefat fixed bookmarks">
            	<thead>
                <tr>
                	<th><?php _e('Support', 'post-template-select');?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                	<td>
                    <ol class="uaf_list">
                        <li><a href="https://wordpress.org/support/plugin/post-template-select/reviews/?filter=5" target="_blank"><?php _e('Rate Us', 'post-template-select');?></a></li>
                    	<li><a href="https://wordpress.org/support/plugin/post-template-select" target="_blank"><?php _e('View Support Forum', 'post-template-select');?></a></li>
                    </ol>
                    </td>
                </tr>
                </tbody>
            </table>
            <br>
            
        </td>
    </tr>
</tbody></table>


</div>