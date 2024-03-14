<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //

// i_file_upload.php


// Initialize magic_number.  This number is used to create unique file names in order to guarantee that 2 file names
// will not be identical if 2 users upload a file at the exact same time.  100000 will allow almost 100000 users to use
// this system.  Ok, the odds of this happening are slim; but, I want the odds to be zero.
$magic_number = 100000;

// Determine temporary file name to use for uploads.
$temp_file_name = "tfn".(string) time().(string) ($magic_number + $dms_user_id);

 function dms_get_file_upload_error_message($error_code)
	{
    // Get the maximum size of a file, that can be uploaded, from the php.ini configuration.
    $upload_max_filesize = ini_get("upload_max_filesize");

	$error_message = "";

	if($error_code > 0)
		{
		// Determine Error Message
		switch($error_code)
			{
			case UPLOAD_ERR_INI_SIZE:
				$error_message = "The document is too large to upload.  (UPLOAD_ERR_INI_SIZE) (MFS:".$upload_max_filesize.")";
				break;

			case UPLOAD_ERR_FORM_SIZE:
				$error_message = "The document is too large to upload.  (UPLOAD_ERR_FORM_SIZE) (MFS:".$upload_max_filesize.")";
				break;

			case UPLOAD_ERR_PARTIAL:
				$error_message = "The document was only partially uploaded.  (UPLOAD_ERR_PARTIAL)";
				break;

			case UPLOAD_ERR_NO_FILE:
				$error_message = "No document was uploaded.  (UPLOAD_ERR_NO_FILE)";
				break;

			case UPLOAD_ERR_NO_TMP_DIR:
				$error_message = "Temporary directory is not available.  (UPLOAD_ERR_NO_TMP_DIR)";
				break;

			case UPLOAD_ERR_CANT_WRITE:
				$error_message = "Unable to write document.  (UPLOAD_ERR_CANT_WRITE)";
				break;
			}
		}

	return($error_message);
	}

?>
