<?php
/*
Plugin Name: Reject Urls And Emails In Textarea For (Gravity Forms)
Plugin URI: https://github.com/InternalError503/reject-urls-and-emails-in-textarea
Description: Prevent URLS & Emails in textarea field.
Version: 1.1
Author: 8pecxstudios
Author URI: https://8pecxstudios.com
License: GPL
Text Domain: reject-urls-and-emails-in-textarea
*/

// Die if accessed directly.
if (!defined('ABSPATH'))
{
	die();
}

add_filter('gform_validation', 'reject_urls_in_textarea', 10, 2);

function reject_urls_in_textarea($validation_result)
{

	// Get the form object from the validation result
	$form = $validation_result["form"];
		
	//Loop through the form fields
	foreach($form['fields'] as &$field)
	{
	
		if ($field["type"] == 'textarea')
		{
			// Get the submitted value from the $_POST
			$field_value = rgpost("input_{$field['id']}");
			$pattern = '#[-a-zA-Z0-9@:%_\+.~\#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~\#?&//=]*)?#si';
			
			if (preg_match_all($pattern, $field_value))
			{
				//Fail the validation for the entire form
				$validation_result['is_valid'] = false;
				
				//Mark the specific field that failed and add a custom validation message
				$field['failed_validation'] = true;
				$field['validation_message'] = 'Urls [Links] or [Emails] are not allowed in this field.';
				
				//Assign our modified $form object back to the validation result
				$validation_result['form'] = $form;
			}
			else
			{
				//Huston we are a go!
				continue;
			}			
		}
		else
		{
			//!textarea 
			continue;
		}
	}	
	//Return validated result
	return $validation_result;
}
?>
