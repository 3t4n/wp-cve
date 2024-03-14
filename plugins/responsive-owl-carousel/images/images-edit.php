<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$guid = isset($_GET['guid']) ? $_GET['guid'] : '0';
owlc_cls_security::owlc_check_guid($guid);

// First check if ID exist with requested ID
$result = owlc_cls_dbquery::owlc_image_count($guid);
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
	$data = owlc_cls_dbquery::owlc_image_view($guid, 0, 1);

	// Preset the form fields
	$form = array(
		'owl_guid' => $data[0]['owl_guid'],
		'owl_title' => $data[0]['owl_title'],
		'owl_image' => stripslashes($data[0]['owl_image']),
		'owl_galleryguid' => $data[0]['owl_galleryguid'],
		'owl_order' => $data[0]['owl_order']
	);
}

// Form submitted, check the data
if (isset($_POST['owlc_form_submit']) && $_POST['owlc_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('owlc_form_add');
	
	$form['owl_galleryguid'] 	= isset($_POST['owl_galleryguid']) ? sanitize_text_field($_POST['owl_galleryguid']) : '';	
	$form['owl_image'] 			= isset($_POST['owl_image']) ? sanitize_text_field($_POST['owl_image']) : '';
	$form['owl_title'] 			= isset($_POST['owl_title']) ? sanitize_text_field($_POST['owl_title']) : '';
	$form['owl_order'] 			= isset($_POST['owl_order']) ? intval($_POST['owl_order']) : '';
	$form['owl_guid'] 			= isset($_POST['owl_guid']) ? sanitize_text_field($_POST['owl_guid']) : '';	

	if ($form['owl_galleryguid'] == '')
	{
		$owlc_errors[] = __('Please select your carousel image gallery.', 'owl-carousel-responsive');
		$owlc_error_found = true;
	}
	
	if ($form['owl_image'] == '')
	{
		$owlc_errors[] = __('Please upload image for this carousel gallery.', 'owl-carousel-responsive');
		$owlc_error_found = true;
	}
	
	//	No errors found, we can add this Group to the table
	if ($owlc_error_found == false)
	{
		$action = false;
			
		$action = owlc_cls_dbquery::owlc_image_action($form, "update");
		if($action == "sus")
		{
			$owlc_success = __('Image successfully uploaded.', 'owl-carousel-responsive');
		}
		elseif($action == "ext")
		{
			$owlc_errors[] = __('Image upload error.', 'owl-carousel-responsive');
		}
		
		// Reset the form fields
		$form = array(
			'owl_guid' 	=> '',
			'owl_title' => '',
			'owl_image' => '',
			'owl_galleryguid' => '',
			'owl_order' => ''
		);
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
            var owl_path = uploaded_image.toJSON().url;
			var owl_title = uploaded_image.toJSON().title;
            // Let's assign the url value to the input field
            $('#owl_image').val(owl_path);
			$('#owl_title').val(owl_title);
        });
    });
});
</script>
<?php
wp_enqueue_script('jquery');  // jQuery
wp_enqueue_media(); // This will enqueue the Media Uploader script
?>
<div class="form-wrap">
	<h3><?php _e('Add Images', 'owl-carousel-responsive'); ?></h3>
	<form name="owlc_form" method="post" action="#" onsubmit="return _owlc_insert()"  >
		
		<label for="tag"><?php _e('Select Gallery', 'owl-carousel-responsive'); ?></label>
		<select name="owl_galleryguid" id="owl_galleryguid">
			<option value=''><?php _e('Select', 'owl-carousel-responsive'); ?></option>
			<?php
			$gallery = array();
			$gallery = owlc_cls_dbquery::owlc_gallery_view("", 0, 100);
			if(count($gallery) > 0)
			{
				foreach ($gallery as $img)
				{
					if($img['owl_guid'] == $form['owl_galleryguid'] ) 
					{ 
						$thisselected = "selected='selected'" ; 
					}
					?>
					<option value='<?php echo $img['owl_guid']; ?>' <?php echo $thisselected; ?>>
						<?php echo esc_html(stripslashes($img['owl_title'])); ?>
					</option>
					<?php
					$thisselected = "";
				}
			}
			?>
		</select>
		<p><?php _e('Please select your carousel image gallery.', 'owl-carousel-responsive'); ?></p>
		
		<label for="tag"><?php _e('Select Carousel Image', 'owl-carousel-responsive'); ?></label>
		<input type="text" name="owl_image" id="owl_image"  size="70%">
		<input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="Upload File">
		<p><?php _e('Please upload your carousel image.', 'owl-carousel-responsive'); ?></p>
		<p><img style="text-align:left;max-height:150px;max-width:150px;height:auto;width:auto;" src="<?php echo $form['owl_image']; ?>" /></p>
		
		<label for="tag"><?php _e('Image Title', 'owl-carousel-responsive'); ?></label>
		<input name="owl_title" type="text" id="owl_title" value="<?php echo $form['owl_title']; ?>" maxlength="225" size="50"  />
		<p><?php _e('Please enter your gallery title.', 'owl-carousel-responsive'); ?></p>
		
		<label for="tag"><?php _e('Image Order', 'owl-carousel-responsive'); ?></label>
		<input name="owl_order" type="text" id="owl_order" value="<?php echo $form['owl_order']; ?>" maxlength="2" />
		<p><?php _e('What order should the picture be played in. only number', 'owl-carousel-responsive'); ?></p>
		
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