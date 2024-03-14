<div class="wrap">
  <div class="form-wrap">
    <div id="icon-plugins" class="icon32 icon32-posts-post"><br>
    </div>
    <h2><?php _e('Tagwebs Beauty Contact Popup Form', 'tag-popup'); ?></h2>
		 <h3><?php _e('Contact Form Information', 'tag-popup'); ?></h3>
	<ol>
		<li><?php _e('Drag and drop the plugin widget to your sidebar.', 'tag-popup'); ?></li>
		<li><?php _e('Use shortcode [show_tagwebs_beauty_contact_popup_form id="1"] to display the form in posts or pages.', 'tag-popup'); ?></li>
		<p>(Be sure to use straight quotes.)</p>
		<p>Sometime the mail will be stored in spam too, so <span style="color: red; font-weight: bold"> please check spam / junk folder </span>if mail not received means and change option to <i>"its not spam and remember for future mail."</i></p>
		
  <p class="description"><?php _e('For live Demo please check our official website', 'tag-popup'); ?> </p>
    <a target="_blank" href="http://demo.uholder.com/plugin/beauty-contact-popup-form/"><input type="submit" class="button add-new-h2" value="Live demo"></a>
	<a target="_blank" href="http://demo.uholder.com/plugin/beauty-contact-popup-form/"><input type="submit" class="button add-new-h2" value="Need Help?"></a>
		
<hr>

    <?php
	$TagPopup_FormTitle = get_option('TagPopup_FormTitle');
	$TagPopup_title = get_option('TagPopup_title');
	$TagPopup_On_MyEmail = get_option('TagPopup_On_MyEmail');
	$TagPopup_On_Subject = get_option('TagPopup_On_Subject');
	$TagPopup_ButtonLink = get_option('TagPopup_ButtonLink');
	
	if (isset($_POST['TagPopup_form_submit']) && $_POST['TagPopup_form_submit'] == 'yes')
	{
		//	Just security thing that wordpress offers us
		check_admin_referer('TagPopup_form_setting');
		
		$TagPopup_FormTitle = stripslashes($_POST['TagPopup_FormTitle']);
		$TagPopup_title = stripslashes($_POST['TagPopup_title']);
		$TagPopup_On_MyEmail = stripslashes($_POST['TagPopup_On_MyEmail']);
		$TagPopup_On_Subject = stripslashes($_POST['TagPopup_On_Subject']);
		$TagPopup_ButtonLink = stripslashes($_POST['TagPopup_ButtonLink']);
		
		update_option('TagPopup_FormTitle', $TagPopup_FormTitle );
		update_option('TagPopup_title', $TagPopup_title );
		update_option('TagPopup_On_MyEmail', $TagPopup_On_MyEmail );
		update_option('TagPopup_On_Subject', $TagPopup_On_Subject );
		update_option('TagPopup_ButtonLink', $TagPopup_ButtonLink );
		
		?>
		<div class="updated fade">
			<p><strong><?php _e('Details successfully updated.', 'tag-popup'); ?></strong></p>
		</div>
		<?php
	}
	?>

  
	<h3><?php _e('Contact Popup Mail Setting', 'tag-popup'); ?></h3>
	<form name="sdp_form" method="post" action="">
	
		<label for="tag-image"><?php _e('Popup form Title', 'tag-popup'); ?></label>
		<input name="TagPopup_FormTitle" type="text" id="TagPopup_FormTitle" value="<?php echo $TagPopup_FormTitle; ?>" size="75" />
		<p><?php _e('Please enter the popup form title.', 'tag-popup'); ?></p>
	
		<label for="tag-image"><?php _e('Email address', 'tag-popup'); ?></label>
		<input name="TagPopup_On_MyEmail" type="text" id="TagPopup_On_MyEmail" value="<?php echo $TagPopup_On_MyEmail; ?>" size="75" />
		<p><?php _e('Please enter your email address to receive mails.', 'tag-popup'); ?></p>
		
		<label for="tag-image"><?php _e('Email subject', 'tag-popup'); ?></label>
		<input name="TagPopup_On_Subject" type="text" id="TagPopup_On_Subject" value="<?php echo $TagPopup_On_Subject; ?>" size="75"  />
		<p><?php _e('Please enter the subject in which subject you like to receive emails .', 'tag-popup'); ?></p>
		
		<label for="tag-image"><?php _e('Link Button / Text', 'tag-popup'); ?></label>
		<input name="TagPopup_ButtonLink" type="text" id="TagPopup_ButtonLink" value="<?php echo $TagPopup_ButtonLink; ?>" size="75"  />
		<p><?php _e('Please enter the Image button url - (use single quotes to display button image) or Text to display in front end.', 'tag-popup'); ?> 
		<br /><b><span style="color: red; padding: 5px;" ><u>Troubleshooting</u>: If the popup form display as broken in page or post where shortcode placed means, please use text as a link (remove above image link and type text - use text as link in the field)</span></b></p>		
		<br />		
		<input type="hidden" name="TagPopup_form_submit" value="yes"/>
		<input name="TagPopup_submit" id="TagPopup_submit" class="button add-new-h2" value="<?php _e('Save Changes', 'tag-popup'); ?>" type="submit" />
		<?php wp_nonce_field('TagPopup_form_setting'); ?>
	</form><hr>
	<br /><ul><br /><ol><span style="color: red; border: 2px solid green; padding: 9px;"" ><u>Troubleshooting</u>: If the popup form display as broken means, please use text as a link in <b>the text field "Link Button / Text"</b></span></ol>
		</ol></ul><br />
		<hr>
		<h3>Contact form Support:</h3>
	<p>
		Example Code:<br />
Use below code or paste below code into the text box and change "contact us" as you like in <b>Link Button / Text</b> field (to display text):<br /></p>
	<textarea disabled rows="4" cols="100">
<span style=' padding: 10px 20px !important;  background-color: #3e3e3e !important; color: white !important;  border-radius: 5px !important; box-shadow: 2px 2px 2px gray !important; text-shadow: black 1px 2px !important; font-size: 18px !important;'>Contact us</span></textarea>
	<p>
		(you can customise span code as you like in text field)<br />
Use below code for link field - <b>change "your site name" to your site url</b> in <b>Link Button / Text</b> field (to display image):<br /></p>
	<textarea disabled rows="4" cols="100">
<img src='your site name /wp-content/plugins/beauty-contact-popup-form/inc/img/contact-tag.png' /></textarea>
<br />
<p>or</p>
<textarea disabled rows="4" cols="100"><img src=' image path ' />


</textarea>
<hr>
	<h3>Do you like this Plugin?</h3>
	<p>This plugin is developed, maintained, supported and contributed by <a target="_blank" href="http://www.tagwebs.in/">Tagwebs Technologies</a>
with a lot of love. Any kind of contribution or suggestion would be highly appreciated. Thanks! </p>
	<ul>
		<li>
		<a target="_blank" href="http://www.uholder.com">Visit the plugin homepage</a>
		</li>
	</ul>
	
  </div>

</div>