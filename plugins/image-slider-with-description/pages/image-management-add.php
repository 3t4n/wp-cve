<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$ImgSlider_errors = array();
$ImgSlider_success = '';
$ImgSlider_error_found = FALSE;

// Preset the form fields
$form = array(
	'ImgSlider_path' 	=> '',
	'ImgSlider_link' 	=> '',
	'ImgSlider_target' 	=> '',
	'ImgSlider_title' 	=> '',
	'ImgSlider_desc' 	=> '',
	'ImgSlider_order' 	=> '',
	'ImgSlider_status' 	=> '',
	'ImgSlider_type' 	=> ''
);

// Form submitted, check the data
if (isset($_POST['ImgSlider_form_submit']) && $_POST['ImgSlider_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('ImgSlider_form_add');
	
	$form['ImgSlider_path'] 	= isset($_POST['ImgSlider_path']) ? sanitize_text_field($_POST['ImgSlider_path']) : '';
	$form['ImgSlider_link'] 	= isset($_POST['ImgSlider_link']) ? sanitize_text_field($_POST['ImgSlider_link']) : '';
	$form['ImgSlider_target'] 	= isset($_POST['ImgSlider_target']) ? sanitize_text_field($_POST['ImgSlider_target']) : '';
	$form['ImgSlider_title'] 	= isset($_POST['ImgSlider_title']) ? sanitize_text_field($_POST['ImgSlider_title']) : '';
	$form['ImgSlider_desc'] 	= isset($_POST['ImgSlider_desc']) ? sanitize_text_field($_POST['ImgSlider_desc']) : '';
	$form['ImgSlider_order'] 	= isset($_POST['ImgSlider_order']) ? sanitize_text_field($_POST['ImgSlider_order']) : '';
	$form['ImgSlider_status'] 	= isset($_POST['ImgSlider_status']) ? sanitize_text_field($_POST['ImgSlider_status']) : '';
	$form['ImgSlider_type'] 	= isset($_POST['ImgSlider_type']) ? sanitize_text_field($_POST['ImgSlider_type']) : '';
	
	$form['ImgSlider_path']	= esc_url_raw($form['ImgSlider_path']);
	if ($form['ImgSlider_path'] == '')
	{
		$ImgSlider_errors[] = __('Please enter the image path.', 'image-slider-with-description');
		$ImgSlider_error_found = TRUE;
	}
	
	$form['ImgSlider_link']	= esc_url_raw($form['ImgSlider_link']);	
	if ($form['ImgSlider_link'] == '')
	{
		$ImgSlider_errors[] = __('Please enter the target link.', 'image-slider-with-description');
		$ImgSlider_error_found = TRUE;
	}
	
	if($form['ImgSlider_target'] != "_blank" && $form['ImgSlider_target'] != "_parent" && $form['ImgSlider_target'] != "_self" && $form['ImgSlider_target'] != "_new")
	{
		$form['ImgSlider_target'] = "_blank";
	}
	
	if ($form['ImgSlider_title'] == '')
	{
		$ImgSlider_errors[] = __('Please enter the image title.', 'image-slider-with-description');
		$ImgSlider_error_found = TRUE;
	}
	
	if(!is_numeric($form['ImgSlider_order'])) { $form['ImgSlider_order'] = 0; }
	
	if($form['ImgSlider_status'] != "YES" && $form['ImgSlider_status'] != "NO")
	{
		$form['ImgSlider_status'] = "YES";
	}

	//	No errors found, we can add this Group to the table
	if ($ImgSlider_error_found == FALSE)
	{
		$sql = $wpdb->prepare(
			"INSERT INTO `".WP_ImgSlider_TABLE."`
			(`ImgSlider_path`, `ImgSlider_link`, `ImgSlider_target`, `ImgSlider_title`, `ImgSlider_desc`, `ImgSlider_order`, `ImgSlider_status`, `ImgSlider_type`)
			VALUES(%s, %s, %s, %s, %s, %d, %s, %s)",
			array($form['ImgSlider_path'], $form['ImgSlider_link'], $form['ImgSlider_target'], $form['ImgSlider_title'], $form['ImgSlider_desc'], $form['ImgSlider_order'], $form['ImgSlider_status'], $form['ImgSlider_type'])
		);
		$wpdb->query($sql);
		
		$ImgSlider_success = __('New image details was successfully added.', 'image-slider-with-description');
		
		// Reset the form fields
		$form = array(
			'ImgSlider_path' 	=> '',
			'ImgSlider_link' 	=> '',
			'ImgSlider_target' 	=> '',
			'ImgSlider_title' 	=> '',
			'ImgSlider_desc' 	=> '',
			'ImgSlider_order' 	=> '',
			'ImgSlider_status' 	=> '',
			'ImgSlider_type' 	=> ''
		);
	}
}

if ($ImgSlider_error_found == TRUE && isset($ImgSlider_errors[0]) == TRUE)
{
	?>
	<div class="error fade">
		<p><strong><?php echo $ImgSlider_errors[0]; ?></strong></p>
	</div>
	<?php
}
if ($ImgSlider_error_found == FALSE && strlen($ImgSlider_success) > 0)
{
	?>
	  <div class="updated fade">
		<p><strong><?php echo $ImgSlider_success; ?></strong></p>
	  </div>
	  <?php
	}
?>
<script type="text/javascript">
jQuery(document).ready(function($){
    $('#upload-btn').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            console.log(uploaded_image);
            var img_imageurl = uploaded_image.toJSON().url;
			var img_imagetitle = uploaded_image.toJSON().title;
            // Let's assign the url value to the input field
            $('#ImgSlider_path').val(img_imageurl);
			 $('#ImgSlider_title').val(img_imagetitle);
        });
    });
});
</script>
<?php
wp_enqueue_script('jquery'); // jQuery
wp_enqueue_media(); // This will enqueue the Media Uploader script
?>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php _e('Image slider with description', 'image-slider-with-description'); ?></h2>
	<form name="ImgSlider_form" method="post" action="#" onsubmit="return ImgSlider_submit()"  >
      <h3><?php _e('Add New image', 'image-slider-with-description'); ?></h3>
      <label for="tag-image"><?php _e('Enter image path', 'image-slider-with-description'); ?></label>
      <input name="ImgSlider_path" type="text" id="ImgSlider_path" value="" size="80" />
	  <input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="Upload Image">
      <p><?php _e('Where is the picture located on the internet', 'image-slider-with-description'); ?> (Example: http://www.gopiplus.com/work/wp-content/uploads/pluginimages/600x400/600x400_1.jpg)</p>
      <label for="tag-link"><?php _e('Enter target link', 'image-slider-with-description'); ?></label>
      <input name="ImgSlider_link" type="text" id="ImgSlider_link" value="#" size="80" />
      <p><?php _e('When someone clicks on the picture, where do you want to send them', 'image-slider-with-description'); ?></p>
      <label for="tag-target"><?php _e('Enter target option', 'image-slider-with-description'); ?></label>
      <select name="ImgSlider_target" id="ImgSlider_target">
        <option value='_blank' selected="selected">_blank</option>
        <option value='_parent'>_parent</option>
        <option value='_self'>_self</option>
        <option value='_new'>_new</option>
      </select>
      <p><?php _e('Do you want to open link in new window?', 'image-slider-with-description'); ?></p>
      <label for="tag-title"><?php _e('Enter image title', 'image-slider-with-description'); ?></label>
      <input name="ImgSlider_title" type="text" id="ImgSlider_title" value="" size="80" />
      <p><?php _e('Enter the image title. The title will display on the image within the slideshow.', 'image-slider-with-description'); ?></p>
      <label for="tag-desc"><?php _e('Enter image description', 'image-slider-with-description'); ?></label>
      <input name="ImgSlider_desc" type="text" id="ImgSlider_desc" value="" size="80" />
      <p><?php _e('Enter image description', 'image-slider-with-description'); ?></p>
      <label for="tag-select-gallery-group"><?php _e('Select gallery group', 'image-slider-with-description'); ?></label>
      <select name="ImgSlider_type" id="ImgSlider_type">
        <option value='GROUP1'>Group1</option>
        <option value='GROUP2'>Group2</option>
        <option value='GROUP3'>Group3</option>
        <option value='GROUP4'>Group4</option>
        <option value='GROUP5'>Group5</option>
        <option value='GROUP6'>Group6</option>
        <option value='GROUP7'>Group7</option>
        <option value='GROUP8'>Group8</option>
        <option value='GROUP9'>Group9</option>
        <option value='GROUP0'>Group0</option>
      </select>
      <p><?php _e('This is to group the images. Select your slideshow group.', 'image-slider-with-description'); ?></p>
      <label for="tag-display-status"><?php _e('Display status', 'image-slider-with-description'); ?></label>
      <select name="ImgSlider_status" id="ImgSlider_status">
        <option value='YES'>Yes</option>
        <option value='NO'>No</option>
      </select>
      <p><?php _e('Do you want the picture to show in your galler?', 'image-slider-with-description'); ?></p>
      <label for="tag-display-order"><?php _e('Display order', 'image-slider-with-description'); ?></label>
      <input name="ImgSlider_order" type="text" id="ImgSlider_order" size="10" value="1" maxlength="3" />
      <p><?php _e('What order should the picture be played in. should it come 1st, 2nd, 3rd, etc.', 'image-slider-with-description'); ?></p>
      <input name="ImgSlider_id" id="ImgSlider_id" type="hidden" value="">
      <input type="hidden" name="ImgSlider_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button-primary" value="<?php _e('Insert Details', 'image-slider-with-description'); ?>" type="submit" />
        <input name="publish" lang="publish" class="button-primary" onclick="ImgSlider_redirect()" value="<?php _e('Cancel', 'image-slider-with-description'); ?>" type="button" />
        <input name="Help" lang="publish" class="button-primary" onclick="ImgSlider_help()" value="<?php _e('Help', 'image-slider-with-description'); ?>" type="button" />
      </p>
	  <?php wp_nonce_field('ImgSlider_form_add'); ?>
    </form>
</div>
</div>