<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$guid = isset($_GET['guid']) ? $_GET['guid'] : '0';
owlc_cls_security::owlc_check_guid($guid);

// First check if ID exist with requested ID
$result = owlc_cls_dbquery::owlc_gallery_count($guid);
if ($result != '1') {
	?><div class="error fade">
		<p><strong>
			<?php echo __( 'Oops, selected details does not exists.', 'owl-carousel-responsive' ); ?>
		</strong></p>
	</div><?php
} else {
	$owlc_errors = array();
	$owlc_success = '';
	$owlc_error_found = FALSE;

	$data = array();
	$data = owlc_cls_dbquery::owlc_gallery_view($guid, 0, 1);

	// Preset the form fields
	$form = array(
		'owl_guid' => $data[0]['owl_guid'],
		'owl_title' => stripslashes($data[0]['owl_title']),
		'owl_setting' => $data[0]['owl_setting']
	);
}

// Form submitted, check the data
if (isset($_POST['owlc_form_submit']) && $_POST['owlc_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('owlc_form_add');
	
	$form['owl_title'] 	= isset($_POST['owl_title']) ? sanitize_text_field($_POST['owl_title']) : '';	
	$items_1000 		= isset($_POST['owl_items_1000']) ? intval($_POST['owl_items_1000']) : '';
	$items_800 			= isset($_POST['owl_items_800']) ? intval($_POST['owl_items_800']) : '';
	$items_600 			= isset($_POST['owl_items_600']) ? intval($_POST['owl_items_600']) : '';
	$items_0 			= isset($_POST['owl_items_0']) ? intval($_POST['owl_items_0']) : '';
	$nav 				= isset($_POST['owl_nav']) ? sanitize_text_field($_POST['owl_nav']) : '';
	$loop 				= isset($_POST['owl_loop']) ? sanitize_text_field($_POST['owl_loop']) : '';
	$margin 			= isset($_POST['owl_margin']) ? intval($_POST['owl_margin']) : '';
	$autoheight 		= isset($_POST['owl_autoheight']) ? sanitize_text_field($_POST['owl_autoheight']) : '';
	$autowidth 			= isset($_POST['owl_autowidth']) ? sanitize_text_field($_POST['owl_autowidth']) : '';
	$autoplay 			= isset($_POST['owl_autoplay']) ? sanitize_text_field($_POST['owl_autoplay']) : '';
	$timeout 			= isset($_POST['owl_autoplaytimeout']) ? intval($_POST['owl_autoplaytimeout']) : '';
	$form['owl_guid'] 	= isset($_POST['owl_guid']) ? sanitize_text_field($_POST['owl_guid']) : '';	
	
	if ($form['owl_title'] == '')
	{
		$owlc_errors[] = __('Please enter your gallery name.', 'owl-carousel-responsive');
		$owlc_error_found = true;
	}
	
	if ( ($nav <> 'true' && $nav <> 'false') || ($loop <> 'true' && $loop <> 'false') 
			|| ($autowidth <> 'true' && $autowidth <> 'false') || ($autoheight <> 'true' && $autoheight <> 'false')
				|| ($autoplay <> 'true' && $autoplay <> 'false') )
	{
		$owlc_errors[] = __('Please select valid option (Nav, Loop, Autoheight, Autowidth, Autoplay).', 'owl-carousel-responsive');
		$owlc_error_found = true;
	}
	
	//	No errors found, we can add this Group to the table
	if ($owlc_error_found == false)
	{
		$action = false;
		
		$setting = "{items_1000: ".trim($items_1000)."},{items_800: ".trim($items_800)."},{items_600: ".trim($items_600)."},{items_0: ".trim($items_0)."},";
		$setting = $setting . "{nav: ".trim($nav)."},{loop: ".$loop."},{margin: ".trim($margin)."},";
		$setting = $setting . "{autoHeight: ".trim($autoheight)."},{autoWidth: ".trim($autowidth)."},";
		$setting = $setting . "{autoplay: ".trim($autoplay)."},{autoplayTimeout: ".trim($timeout)."}";
		$form['owl_setting'] = $setting;
		
		$action = owlc_cls_dbquery::owlc_gallery_action($form, "update");
		if($action == "sus")
		{
			$owlc_success = __('Gallery successfully updated.', 'owl-carousel-responsive');
		}
		elseif($action == "ext")
		{
			$owlc_errors[] = __('Gallery name already exists.', 'owl-carousel-responsive');
		}
		
		// Reset the form fields
		//$form = array(
		//	'owl_guid' => '',
		//	'owl_title' => '',
		//	'owl_setting' => ''
		//);
	}
}

if ($owlc_error_found == true && isset($owlc_errors[0]) == true)
{
	?><div class="error fade"><p><strong><?php echo $owlc_errors[0]; ?></strong></p></div><?php
}

if ($owlc_error_found == false && strlen($owlc_success) > 0)
{
	?>
	<div class="updated fade">
		<p><strong><?php echo $owlc_success; ?></strong></p>
	</div>
	<?php
}
?>
<div class="form-wrap">
	<h3><?php _e('Update Gallery Details', 'owl-carousel-responsive'); ?></h3>
	<form name="owlc_form" method="post" action="#" onsubmit="return _owlc_insert()"  >
		
		<label for="tag"><?php _e('Gallery Name', 'owl-carousel-responsive'); ?></label>
		<input name="owl_title" type="text" id="owl_title" value="<?php echo esc_html(stripslashes($form['owl_title'])); ?>" maxlength="225" size="50"  />
		<p><?php _e('Please enter your gallery name.', 'owl-carousel-responsive'); ?></p>
		
		<?php
		$settings = array();
		$owl_setting = $form['owl_setting'];
		$settings = owlc_cls_common::owlc_split_settings($owl_setting);
		?>
		<label for="tag"><?php _e('Images (Screen size +1000px)', 'owl-carousel-responsive'); ?></label>
		<input name="owl_items_1000" type="text" id="owl_items_1000" value="<?php echo $settings["items_1000"]; ?>" maxlength="2"  />
		<p><?php _e('The number of images you want to see on the screen.', 'owl-carousel-responsive'); ?></p>
		
		<label for="tag"><?php _e('Images (Screen size +800px)', 'owl-carousel-responsive'); ?></label>
		<input name="owl_items_800" type="text" id="owl_items_800" value="<?php echo $settings["items_800"]; ?>" maxlength="2" />
		<p><?php _e('The number of images you want to see on the screen.', 'owl-carousel-responsive'); ?></p>
		
		<label for="tag"><?php _e('Images (Screen size +600px)', 'owl-carousel-responsive'); ?></label>
		<input name="owl_items_600" type="text" id="owl_items_600" value="<?php echo $settings["items_600"]; ?>" maxlength="2" />
		<p><?php _e('The number of images you want to see on the screen.', 'owl-carousel-responsive'); ?></p>
		
		<label for="tag"><?php _e('Images (Mobile screen)', 'owl-carousel-responsive'); ?></label>
		<input name="owl_items_0" type="text" id="owl_items_0" value="<?php echo $settings["items_0"]; ?>" maxlength="1" />
		<p><?php _e('The number of images you want to see on the screen.', 'owl-carousel-responsive'); ?></p>
		
		<label for="tag"><?php _e('Navigation Button', 'owl-carousel-responsive'); ?></label>
		<select name="owl_nav" id="owl_nav">
			<option value='true' <?php if( trim($settings["nav"]) == 'true' ) { echo 'selected="selected"' ; } ?>>YES</option>
			<option value='false' <?php if( trim($settings["nav"]) == 'false' ) { echo 'selected="selected"' ; } ?>>NO</option>
		</select>
		<p><?php _e('Show next/prev buttons in the gallery.', 'owl-carousel-responsive'); ?></p>
		
		<label for="tag"><?php _e('Infinity Loop', 'owl-carousel-responsive'); ?></label>
		<select name="owl_loop" id="owl_loop">
			<option value='true' <?php if( trim($settings['loop']) == 'true' ) { echo 'selected="selected"' ; } ?>>YES</option>
			<option value='false' <?php if( trim($settings['loop']) == 'false' ) { echo 'selected="selected"' ; } ?>>NO</option>
		</select>
		<p><?php _e('Duplicate last and first image in the gallery to get loop illusion.', 'owl-carousel-responsive'); ?></p>
		
		<label for="tag"><?php _e('Margin', 'owl-carousel-responsive'); ?></label>
		<input name="owl_margin" type="text" id="owl_margin" value="<?php echo $settings["margin"]; ?>" maxlength="2" />
		<p><?php _e('Margin right(px) on each image in the gallery.', 'owl-carousel-responsive'); ?></p>
		
		<label for="tag"><?php _e('Auto Height', 'owl-carousel-responsive'); ?></label>
		<select name="owl_autoheight" id="owl_autoheight">
			<option value='true' <?php if( trim($settings['autoHeight']) == 'true' ) { echo 'selected="selected"' ; } ?>>YES</option>
			<option value='false' <?php if( trim($settings['autoHeight']) == 'false' ) { echo 'selected="selected"' ; } ?>>NO</option>
		</select>
		<p><?php _e('Automatically align the height of the image in the gallery.', 'owl-carousel-responsive'); ?></p>
		
		<label for="tag"><?php _e('Auto Width', 'owl-carousel-responsive'); ?></label>
		<select name="owl_autowidth" id="owl_autowidth">
			<option value='true' <?php if( trim($settings['autoWidth']) == 'true' ) { echo 'selected="selected"' ; } ?>>YES</option>
			<option value='false' <?php if( trim($settings['autoWidth']) == 'false' ) { echo 'selected="selected"' ; } ?>>NO</option>
		</select>
		<p><?php _e('Automatically align the width of the image in the gallery.', 'owl-carousel-responsive'); ?></p>
		
		<label for="tag"><?php _e('Autoplay', 'owl-carousel-responsive'); ?></label>
		<select name="owl_autoplay" id="owl_autoplay">
			<option value='true' <?php if( trim($settings['autoplay']) == 'true' ) { echo 'selected="selected"' ; } ?>>YES</option>
			<option value='false' <?php if( trim($settings['autoplay']) == 'false' ) { echo 'selected="selected"' ; } ?>>NO</option>
		</select>
		<p><?php _e('Automatically play the gallery.', 'owl-carousel-responsive'); ?></p>
		
		<label for="tag"><?php _e('Autoplay Timeout', 'owl-carousel-responsive'); ?></label>
		<input name="owl_autoplaytimeout" type="text" id="owl_autoplaytimeout" value="<?php echo $settings["autoplayTimeout"]; ?>" maxlength="4" />
		<p><?php _e('Autoplay interval timeout.', 'owl-carousel-responsive'); ?></p>
		
		<input type="hidden" name="owlc_form_submit" value="yes"/>
		<input type="hidden" name="owl_guid" id="owl_guid" value="<?php echo $form['owl_guid']; ?>"/>
		<p class="submit">
		<input name="publish" lang="publish" class="button add-new-h2" value="<?php _e('Submit', 'owl-carousel-responsive'); ?>" type="submit" />
		<input name="publish" lang="publish" class="button add-new-h2" onclick="_owlc_redirect()" value="<?php _e('Cancel', 'owl-carousel-responsive'); ?>" type="button" />
		<input name="Help" lang="publish" class="button add-new-h2" onclick="_owlc_help()" value="<?php _e('Help', 'owl-carousel-responsive'); ?>" type="button" /><br />
		<?php _e('For more information about this plugin', 'owl-carousel-responsive'); ?>
		<a target="_blank" href="<?php echo OWLC_FAVURL; ?>"><?php _e('click here', 'owl-carousel-responsive'); ?></a><br />
		</p>
		<?php wp_nonce_field('owlc_form_add'); ?>
		
	</form>
</div>
</div>