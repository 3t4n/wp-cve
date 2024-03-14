<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //

// inc_file_copy.php

// Returns the $obj_id of the new document

function dms_file_copy($obj_id, $dest_obj_owner)
	{
	global $dms_config,$dmsdb,$dms_user_id;

	// Determine the type of folder the document is being copied into.
	$query  = "SELECT obj_type FROM ".$dmsdb->prefix("dms_objects")." WHERE obj_id='".$dest_obj_owner."'";
	$dest_obj_type = $dmsdb->query($query,"obj_type");

	if($dest_obj_type == DISKDIR)
		{
		$query  = "SELECT data FROM ".$dmsdb->prefix("dms_object_misc")." ";
		$query .= "WHERE obj_id='".$dest_obj_owner."' AND data_type='".PATH."'";
		$dest_directory = $dmsdb->query($query,"data");

		//$dest_path_and_file = $dest_directory."/".$file_sys_root."/".$partial_path_and_file;

		// Get the name, path and file of the source file.
		$query  = "SELECT current_version_row_id FROM ".$dmsdb->prefix("dms_objects")." ";
		$query .= "WHERE obj_id='".$obj_id."'";
		$source_object_cvr_id = $dmsdb->query($query,"current_version_row_id");

		$query  = "SELECT file_path, file_name, file_type, file_size FROM ".$dmsdb->prefix("dms_object_versions")." ";
		$query .= "WHERE row_id='".$source_object_cvr_id."'";
		$source_file_info = $dmsdb->query($query,"ROW");

		// Fix for dingbats who do not name their files with an extention.
		$dest_filename = $source_file_info->file_name;
		if(!strrchr($source_file_info->file_name,"."))
		  $dest_filename = dms_filename_plus_ext($source_file_info->file_name,$source_file_info->file_type);
		else $dest_filename = $source_file_info->file_name;

		$source_path_and_file = $dms_config['doc_path']."/".$source_file_info->file_path;
		$dest_path_and_file = $dest_directory."/".$dest_filename;

		//print $source_path_and_file." TO ".$dest_path_and_file;

		// Copy the file.
		if (!copy($source_path_and_file,$dest_path_and_file))
			{
			print _DMS_FAILURE_TO_COPY . "\r";
			print "<BR>";
			print "S:".$source_path_and_file;
			print "<BR>";
			print "D:".$dest_path_and_file;
			exit(0);
			}

		chmod($dest_path_and_file, 0777);    // Set document to rwxrwxrwx
		}
	else
		{
		$source_obj_id = $obj_id;

		$partial_path_and_file = dest_path_and_file();

		// Get the location of the document repository
		$file_sys_root = $dms_config['doc_path'];

		$dest_path_and_file = $file_sys_root."/".$partial_path_and_file;

		// Get the name, path and file of the source file.
		$query  = "SELECT template_obj_id, obj_name, current_version_row_id FROM ".$dmsdb->prefix("dms_objects")." ";
		$query .= "WHERE obj_id='".$obj_id."'";
		$source_object = $dmsdb->query($query,"ROW");

		$query  = "SELECT file_path, file_name, file_type, file_size FROM ".$dmsdb->prefix("dms_object_versions")." ";
		$query .= "WHERE row_id='".$source_object->current_version_row_id."'";
		$source_file_info = $dmsdb->query($query,"ROW");

		$source_path_and_file = $file_sys_root."/".$source_file_info->file_path;

		$query  = "SELECT * FROM ".$dmsdb->prefix("dms_object_properties")." WHERE obj_id='".$obj_id."'";
		$source_file_properties = $dmsdb->query($query,"ROW");

		//print $source_path_and_file." TO ".$dest_path_and_file;

		// Copy the file.
		if (!copy($source_path_and_file,$dest_path_and_file))
			{
			print _DMS_FAILURE_TO_COPY . "\r";
			exit(0);
			}

		// Create the new object in dms_objects
		$query  = "INSERT INTO ".$dmsdb->prefix('dms_objects')." (template_obj_id, obj_type,obj_name,obj_owner,time_stamp_create)";
		$query .= " VALUES ('";
		$query .= $source_object->template_obj_id."','";
		$query .= "0','";
		$query .= $source_object->obj_name."','";
		$query .= $dest_obj_owner."','";
		$query .= time()."')";

		$dmsdb->query($query);

		// Get the obj_id of the new object
		$obj_id = $dmsdb->getid();




		// Copy all of the document permissions.
		if( ($dms_config['inherit_perms'] == 1) && ($dest_obj_owner > 0) )
			{
			// Use the permissions inherited from the destination folder
			$perms_source = $dest_obj_owner;
			}
		else
			{
			// Use the permissions copied from the original document
			$perms_source = $source_obj_id;
			}

		$query = "SELECT * from ".$dmsdb->prefix('dms_object_perms')." WHERE ptr_obj_id='".$source_obj_id."'";
		$result = $dmsdb->query($query);
		$num_rows = $dmsdb->getnumrows();

		if($num_rows > 0)
			{
			while($result_data = $dmsdb->getarray($result))
				{
				$query  = "INSERT INTO ".$dmsdb->prefix('dms_object_perms')." ";
				$query .= "(ptr_obj_id,user_id, group_id, user_perms, group_perms, everyone_perms) VALUES ('";
				$query .= $obj_id."','";
				$query .= $result_data['user_id']."','";
				$query .= $result_data['group_id']."','";
				$query .= $result_data['user_perms']."','";
				$query .= $result_data['group_perms']."','";
				$query .= $result_data['everyone_perms']."')";

				$dmsdb->query($query);
				}
			}

		// Create an entry in dms_object_properties and store additional properties information.
		$query  = "INSERT INTO ".$dmsdb->prefix('dms_object_properties')." ";
		$query .= " VALUES (";
		$query .= "'".$obj_id."',";
		$query .= "'".$source_file_properties->property_0."',";
		$query .= "'".$source_file_properties->property_1."',";
		$query .= "'".$source_file_properties->property_2."',";
		$query .= "'".$source_file_properties->property_3."',";
		$query .= "'".$source_file_properties->property_4."',";
		$query .= "'".$source_file_properties->property_5."',";
		$query .= "'".$source_file_properties->property_6."',";
		$query .= "'".$source_file_properties->property_7."',";
		$query .= "'".$source_file_properties->property_8."',";
		$query .= "'".$source_file_properties->property_9."')";

		//  print $query;
		$dmsdb->query($query);

		// Create an entry in dms_object_versions and store the appropriate information.
		$query  = "INSERT INTO ".$dmsdb->prefix('dms_object_versions')." (obj_id,file_path,file_name,file_type,file_size,";
		$query .= "major_version,minor_version,sub_minor_version,time_stamp,file_location)";
		$query .= " VALUES ('";
		$query .= $obj_id."','";
		$query .= $partial_path_and_file."','";
		$query .= $source_file_info->file_name."','";
		$query .= $source_file_info->file_type."','";
		$query .= $source_file_info->file_size."','";
		$query .= "1"."','";
		$query .= "0"."','";
		$query .= "0"."','";
		$query .= time()."','";
		$query .= DMS."')";

		$dmsdb->query($query);

		// Find the row_id of the entry just created in dms_object_versions.
		$dms_object_versions_row_id = $dmsdb->getid();

		// Enter the row_id of the entry for the current version into dms_objects
		$query  = "UPDATE ".$dmsdb->prefix('dms_objects');
		$query .= " SET current_version_row_id='".$dms_object_versions_row_id."' ";
		$query .= " WHERE obj_id='".$obj_id."'";

		$dmsdb->query($query);
		}

	dms_document_name_sync($obj_id);

	return $obj_id;
	}
?>
