<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //


// i_dest_path_and_file.php

//  Writes a blank index.php file to block browsing of the directory.
function block_browse($path_and_file)
    {
    $fp = fopen($path_and_file,'w') or die("<BR><BR>Unable to open $path_and_file.  Most likely, the server does not have write access.");

    $line = "<?php\n";
    fputs($fp,$line);

    fclose($fp);
    }



//  Determines the destination path and file name for a new file that is to be added to the document repository.  Also
//  creates the appropriate destination directory, increments the file system counters, and stores the file system
//  counters for future use.
function dest_path_and_file()
{
	global $dms_config, $dmsdb, $dms_user_id, $dms_global;

	// Initialize magic_number.  This number is used to create unique file names in order to guarantee that 2 file names
	// will not be identical if 2 users upload a file at the exact same time.  100000 will allow almost 100000 users to use
	// this system.  Ok, the odds of this happening are slim; but, I want the odds to be zero.
	$magic_number = 100000;

	// Get the location of the document repository
	$file_sys_root = $dms_config['doc_path'];

	// Get the current value of max_file_sys_counter
	$max_file_sys_counter = (integer) $dms_config['max_file_sys_counter'];

	// Determine the path and filename of the new file
	$query = "SELECT * from ".$dmsdb->prefix("dms_file_sys_counters");
	$dms_file_sys_counters = $dmsdb->query($query,'ROW');

	$file_sys_dir_1     = $dms_file_sys_counters->layer_1;
	$file_sys_dir_2     = $dms_file_sys_counters->layer_2;
	$file_sys_dir_3     = $dms_file_sys_counters->layer_3;
	$file_sys_file      = $dms_file_sys_counters->file;
	$file_sys_file_name = ($file_sys_file * $magic_number) + $dms_user_id;

	$path_and_file = $file_sys_file_name.".dat";
	$file_sys_file++;

	$query =  "UPDATE ".$dmsdb->prefix("dms_file_sys_counters")." SET ";
	$query .= "file = '".(integer) $file_sys_file. "' ";
	$dmsdb->query($query);

	return($path_and_file);
}


?>



