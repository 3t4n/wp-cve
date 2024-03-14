<?php
// Function to allow users to submit testimonials through a form
function iq_testimonials_form(){
	global $wpdb;

	// Variables
	$clientname = $_POST['clientname'];
	$email = $_POST['email'];
	$location = $_POST['location'];
	$testimonial = trim($_POST['testimonial']);
	$testimonial = nl2br($testimonial);
	$testimonial = stripslashes($testimonial);
	$website = $_POST['website'];
    $recaptcha_challenge = $_POST["recaptcha_challenge_field"];
    $recaptcha_response = $_POST["recaptcha_response_field"];
	$bloginfo = get_bloginfo('name');
	$html = '';
	$filename = $_FILES['imagefile']['name'];
	
	// Grabbing DB prefix and settings table names to variable
	$testimonials = $wpdb->prefix . "iq_testimonials";
	$iq_testimonials_settings = $wpdb->prefix . "iq_testimonials_settings";
	
	$form_name = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='form_name'", ARRAY_A);
	$form_name = $form_name['value'];
	
	$form_email = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='form_email'", ARRAY_A);
	$form_email = $form_email['value'];
	
	$form_location = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='form_location'", ARRAY_A);
	$form_location = $form_location['value'];
	
	$form_website = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='form_website'", ARRAY_A);
	$form_website = $form_website['value'];

	$name_field = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='name_field'", ARRAY_A);
	$name_field = $name_field['value'];
	
	$email_field = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='email_field'", ARRAY_A);
	$email_field = $email_field['value'];
	
	$location_field = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='location_field'", ARRAY_A);
	$location_field = $location_field['value'];
	
	$website_field = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='website_field'", ARRAY_A);
	$website_field = $website_field['value'];
	
	$image_field = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='image_field'", ARRAY_A);
	$image_field = $image_field['value'];
	
	$recaptcha_toggle = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='recaptcha_toggle'", ARRAY_A);
	$recaptcha_toggle = $recaptcha_toggle['value'];
	
	$recaptcha_public_key = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='recaptcha_public_key'", ARRAY_A);
	$recaptcha_public_key = $recaptcha_public_key['value'];
	
	$recaptcha_private_key = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='recaptcha_private_key'", ARRAY_A);
	$recaptcha_private_key = $recaptcha_private_key['value'];
	
	$hide_section = ' style="display: none;" ';

	$paragraph = '<p class="iq-testimonials-info">Please fill in the form below to submit your testimonial<br /><em>Required fields are marked *</em></p>';
	
		// Ensuring the website URL has http://
	$website_final = '';
	if (!empty($website)){
		$website_final = (substr(ltrim($website), 0, 7) != 'http://' ? 'http://' : '') . $website;
	}

	if (isset($_POST['submit'])){ // check if form has been submitted

		// start an empty array
		$formerrors = array();
		$formerrorBits = array();

		if ((empty($clientname)) && ($form_name == 'true') && (empty($name_field))){
			$formerrorBits['clientname'] = true;
			$form_errors_present = true;
		}
	
		if ((empty($email)) && ($form_email == 'true') && (empty($email_field))){
			$formerrorBits['email'] = true;
			$form_errors_present = true;
		}

		if ((empty($location)) && ($form_location == 'true') && (empty($location_field))){
			$formerrorBits['location'] = true;
			$form_errors_present = true;
		}
		
		if ((empty($website)) && ($form_website == 'true') && (empty($website_field))){
			$formerrorBits['website'] = true;
			$form_errors_present = true;
		}
		
		if (!empty($recaptcha_response)){
			require_once('recaptchalib.php');
  			$resp = recaptcha_check_answer($recaptcha_private_key,
            $_SERVER["REMOTE_ADDR"],
            $recaptcha_challenge,
            $recaptcha_response);
			if (!$resp->is_valid) {
				$formerrorBits['recaptcha'] = true;
				$form_errors[] = "<p class=\"iq-testimonials-error\">".$resp->error."</p>\n";
				$form_errors_present = true;
			}
		}
		
		if (empty($testimonial)){
			$formerrorBits['testimonial'] = true;
			$form_errors_present = true;
		}
		
		if ($form_errors_present == true) {
			$formerrors[] = "<p class=\"iq-testimonials-error\">Please fill in all required fields.</p>\n";
		}
	
		if (!empty($email) && !eregi("^[_a-z0-9-]+(\.[_a-z0-9-]*)*@[a-z0-9-]+(\.[a-z0-9-]+)+$",$email)){
			$formerrors[] = "<p class=\"iq-testimonials-error\">Please enter a valid email address.</p>\n";
			$formerrorBits['email'] = true;
		}
		
		if (!empty($filename)) {

		if (($_FILES["imagefile"]["type"] == "image/gif")
  		|| ($_FILES["imagefile"]["type"] == "image/jpeg")
  		|| ($_FILES["imagefile"]["type"] == "image/png" )) {
			$imagelink = process_image_upload('imagefile');
		}
		else
		{
			$formerrorBits['imagefile'] = true;	
			$formerrors[] = "<p class=\"iq-testimonials-error\">File must be .gif, .jpg, or .png</p>\n";
		}
		}

		// Check for errors before_title sending email
		if (!count($formerrors)){
			$insert = "INSERT INTO " . $testimonials .
			" (id, status, name, location, quote, website, imagelink) " .
			"VALUES ('','" . $wpdb->escape('2') . "','" . $wpdb->escape($clientname) . "','" . $wpdb->escape($location) . "','" . $wpdb->escape($testimonial) . "','" . $wpdb->escape($website_final) . "','" . $wpdb->escape($imagelink) . "')";
			$results = $wpdb->query($insert);
			
			$to = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='admin_email'", ARRAY_A);		
			$to = $to['value'];
			$subject = "Testimonial Added on ".$bloginfo;
			$body = "Name: ".$clientname."\n"
					."Email: ".$email."\n"
					."Location: ".$location."\n"
					."Testimonial: ".stripcslashes($testimonial)."\n"
					."Website: ".$website."\n"
					."Image Link: ".$imagelink."\n"
					."\n"
					."Please log into your WordPress Dashboard and approve the testimonial by clicking on Edit, and setting the status to \"Public\".";
			
			if (!empty($email)) {		
			$from = "From: $name <$email>\r\n";
			}
			else
			{
			$from = "From: <$to>\r\n";
			}
			mail($to, $subject, $body, $from);

			$html .= "<p class=\"iq-testimonials-success\">Thanks for adding your Testimonial. Please wait for an administrator to approve it.</p>";
		}

		else {
			$html .= $paragraph;
			// now output the errors for user to see
			foreach ($formerrors as $msg)
			$html .= $msg;
		}
	}

	if (!isset($_POST['submit'])){
		$html .= $paragraph;
	}

	// if the form hasn't been submitted or it has and there were errors, the form will be written
	if (!isset($_POST['submit']) || (isset($formerrorBits) && count($formerrorBits))){

	$html .= '<form enctype="multipart/form-data" action="" id="iq-testimonials-form" name="iq-testimonials" method="post">
		<p';
		
		if ($name_field == 'true') {$html .= $hide_section;} 

		if($formerrorBits['clientname'] == true){$html .= ' class="iq-testimonials-error"';}
	
	$html .= '><label for="clientname" id="iq-testimonials-label">Name: ';
	if ($form_name == 'true') {
		$html .= '*';
	}
	$html .='</label>
		<input type="text" name="clientname" id="iq-testimonials-input" value="';
		
		if(isset($clientname)){$html .= $clientname;}
	
	$html .= '" /></p>
		
		<p';
		
		if ($email_field == 'true') {$html .= $hide_section;} 
	
		if($formerrorBits['email'] == true){$html .= ' class="iq-testimonials-error"';}
	
	$html .= '><label for="email" id="iq-testimonials-label">Email: ';
	if ($form_email == 'true') {
		$html .= '*';
	}
	$html .='</label>
		<input type="text" name="email" id="iq-testimonials-input" value="';
		
		if(isset($email)){$html .= $email;}
	
	$html .= '" /></p>
		
		<p';

		if ($location_field == 'true') {$html .= $hide_section;} 
	
		if($formerrorBits['location'] == true){$html .= ' class="iq-testimonials-error"';}
	
	$html .= '><label for="location" id="iq-testimonials-label">Location: ';
	if ($form_location == 'true') {
		$html .= '*';
	}
	$html .='</label>
		<input type="text" name="location" id="iq-testimonials-input" value="';
		
		if(isset($location)){$html .= $location;}
		
	$html .= '" /></p>
		
		<p';

		if ($website_field == 'true') {$html .= $hide_section;} 
		
		if($formerrorBits['website'] == true){$html .= ' class="iq-testimonials-error"';}
		
	$html .= '><label for="website" id="iq-testimonials-label">Website: ';
	if ($form_website == 'true') {
		$html .= '*';
	}
	$html .='</label>
		<input type="text" name="website" id="iq-testimonials-input" value="';
		
		if(isset($website)){$html .= $website;}
	
	$html .= '" /></p>
	
		<p';

		if ($image_field == 'true') {$html .= $hide_section;} 
		
		if($formerrorBits['imagelink'] == true){$html .= ' class="iq-testimonials-error"';}
	
	$html .= '><label for="imagefile" id="iq-testimonials-label">Your image: </label><input type="file" name="imagefile" id="iq-testimonials-input" /></p>
	
		<p';

		if($formerrorBits['testimonial'] == true){$html .= ' class="iq-testimonials-error"';}
	
	$html .= '><label for="testimonial" id="iq-testimonials-label">Testimonial: *</label>
		<textarea name="testimonial" id="iq-testimonials-input" rows="8">';
		
		if(isset($testimonial)){$html .= $testimonial;}
		
	$html .= '</textarea></p>

		<div';
		
		if ($recaptcha_toggle != 'true') {$html .= $hide_section;} 

		if($formerrorBits['recaptcha'] == true){$html .= ' class="iq-testimonials-error"';}
	
	$html .= '>';
	
		if($formerrorBits['recaptcha'] == true){$html .= '<p class="iq-testimonials-error">The text you entered was incorrect. Please try again.</p>';}
	
    require_once('recaptchalib.php');
    $html .= recaptcha_get_html($recaptcha_public_key);
		
	$html .= '</div>';
	
	$html .= '<p><input type="submit" name="submit" value="Submit" id="submit" /></p>
	</form>';
		
	}

	// mysql_free_result($results);
	
	return $html;
}

  function process_image_upload( $Field )
  {
  			$target_path = ABSPATH.'wp-content/uploads/iqt-images';

			if(!file_exists($target_path)) { @mkdir($target_path."/"); }
			
    $temp_file_path = $_FILES[ $Field ][ 'tmp_name' ];
    $temp_file_name = $_FILES[ $Field ][ 'name' ];
	
	if (!is_dir(CREATED_IMAGE_PATH)) {
			mkdir(CREATED_IMAGE_PATH, 0755);
	}

    list( , , $temp_type ) = getimagesize( $temp_file_path );

    if ( $temp_type === NULL )
    {
      return false;
    }

    switch ( $temp_type )
    {
      case IMAGETYPE_GIF:
        break;
      case IMAGETYPE_JPEG:
        break;
      case IMAGETYPE_PNG:
        break;
      default:
        return false;
    }
    $uploaded_file_path = UPLOADED_IMAGE_DESTINATION . $temp_file_name;
    $processed_file_path = PROCESSED_IMAGE_DESTINATION . $temp_file_name;
	$processed_file_path = strtolower($processed_file_path);
	$processed_file_path = str_replace(' ', '-', $processed_file_path);
	$uploaded_file_path = strtolower($uploaded_file_path);
	$uploaded_file_path = str_replace(' ', '-', $uploaded_file_path);

	if (file_exists($uploaded_file_path)) {
		$file_extn = substr($uploaded_file_path, strrpos($uploaded_file_path, '.')+1);
//		$temp_processed_file_path = str_replace('.'.$file_extn, '', $processed_file_path);
//		$temp_uploaded_file_path = str_replace('.'.$file_extn, '', $uploaded_file_path);
		while (file_exists($uploaded_file_path)) {
			$i++;
			if ($i == 1) {
			$uploaded_file_path = str_replace('.'.$file_extn, '-'.$i.'.'.$file_extn, $uploaded_file_path);
			$processed_file_path = str_replace('.'.$file_extn, '-'.$i.'.'.$file_extn, $processed_file_path);				
			}
			else
			{
			$uploaded_file_path = str_replace('-'.($i-1).'.'.$file_extn, '-'.$i.'.'.$file_extn, $uploaded_file_path);
			$processed_file_path = str_replace('-'.($i-1).'.'.$file_extn, '-'.$i.'.'.$file_extn, $processed_file_path);
			}
		}
	}


if (!move_uploaded_file($temp_file_path, $uploaded_file_path)) echo "CANNOT MOVE {$_FILES["imagefile"]["name"]}" . PHP_EOL;
	$result = resize_uploaded_image($uploaded_file_path);

    if ( $result === false )
    {
      return false;
    }
    else
    {
      return $processed_file_path;
    }
  }

  //--------------------------------
  // END OF FUNCTIONS
  //--------------------------------

function resize_uploaded_image($filename) {
global $wpdb;
	
// Grabbing DB prefix and settings table names to variable
$testimonials = $wpdb->prefix . "iq_testimonials";
$iq_testimonials_settings = $wpdb->prefix . "iq_testimonials_settings";

// Set a maximum width and height
	$max_image_width = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='max_image_width'", ARRAY_A);
	$max_image_width = $max_image_width['value'];
	$max_image_height = $wpdb->get_row("SELECT value FROM $iq_testimonials_settings WHERE name='max_image_height'", ARRAY_A);
	$max_image_height = $max_image_height['value'];

// Content type
//header('Content-Type: image/jpeg');

// Get new dimensions
list($width_orig, $height_orig, $source_type) = getimagesize($filename);

    if ( $source_type === NULL )
    {
      return false;
    }

    switch ( $source_type )
    {
      case IMAGETYPE_GIF:
        $source_gd_image = imagecreatefromgif( $filename );
        break;
      case IMAGETYPE_JPEG:
        $source_gd_image = imagecreatefromjpeg( $filename );
        break;
      case IMAGETYPE_PNG:
        $source_gd_image = imagecreatefrompng( $filename );
        break;
      default:
        return false;
    }

$ratio_orig = $width_orig/$height_orig;

if ($max_image_width/$max_image_height > $ratio_orig) {
   $max_image_width = $max_image_height*$ratio_orig;
} else {
   $max_image_height = $max_image_width/$ratio_orig;
}

// Resample
$image_p = imagecreatetruecolor($max_image_width, $max_image_height);
//$image = imagecreatefromjpeg($filename);
imagecopyresampled($image_p, $source_gd_image, 0, 0, 0, 0, $max_image_width, $max_image_height, $width_orig, $height_orig);

// Output
//imagejpeg($image_p, null, 100);
    imagejpeg( $image_p, $filename, 100 );
	
	return true;
}
?>