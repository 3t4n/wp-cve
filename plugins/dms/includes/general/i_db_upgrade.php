<?php
/*
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //
*/

// i_db_upgrade.php


function dms_update_tables()
	{
	global $dmsdb;

	//  Get the old version.
    $old_version = dms_get_old_version();

    //$update_exit_counter = 0;  //  Counter to prevent infinite loops if version numbers do not match up.

	while($old_version != DMS_VERSION) // || $update_exit_counter < 100)
		{
		if ($old_version==0.96) dms_update_0096();  //  First public release.
		if ($old_version==0.97) dms_update_0097();
		if ($old_version==0.98) dms_update_0098();
		if ($old_version==0.99) dms_update_0099();
		if ($old_version==1.00) dms_update_0100();
		if ($old_version==1.01) dms_update_0101();
		if ($old_version==1.02) dms_update_0102();
		if ($old_version==1.03) dms_update_0103();
		if ($old_version==1.04) dms_update_0104();
		if ($old_version==1.05) dms_update_0105();
		if ($old_version==1.06) dms_update_0106();
		if ($old_version==1.10) dms_update_0110();
		if ($old_version==1.11) dms_update_0111();
		if ($old_version==1.12) dms_update_0112();
		if ($old_version==1.13) dms_update_0113();
		if ($old_version==1.14) dms_update_0114();
		if ($old_version==1.15) dms_update_0115();
		if ($old_version==1.16) dms_update_0116();
		if ($old_version==1.17) dms_update_0117();
		if ($old_version==1.20) dms_update_0120();
		if ($old_version==1.21) dms_update_0121();
		if ($old_version==1.22) dms_update_0122();
		if ($old_version==1.23) dms_update_0123();


		$old_version = dms_get_old_version();

		//$update_exit_counter++;
		}

    //  Reset time stamp to force re-load config.
	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='0' WHERE name='time_stamp'";
	$dmsdb->query($query);

	return;
	}

function dms_get_old_version()
	{
	global $dmsdb;

	$query = 'SELECT data FROM '.$dmsdb->prefix("dms_config")." WHERE name='version'";
	$old_version = $dmsdb->query($query,'data');
	return($old_version);
	}

//  Update functions by version.

function dms_update_0096()
	{
	global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='0.97' WHERE name='version'";
	$dmsdb->query($query);
	}

function dms_update_0097()
	{
	global $dmsdb;

	$query  = "ALTER TABLE ".$dmsdb->prefix("dms_objects")." ";
	$query .= "CHANGE file_type file_type varchar(150) not null default 'unchecked'";
	$dmsdb->query($query);

	$query  = "ALTER TABLE ".$dmsdb->prefix("dms_object_versions")." ";
	$query .= "CHANGE file_type file_type varchar(150) not null default 'unchecked'";
	$dmsdb->query($query);

	$query  = "ALTER TABLE ".$dmsdb->prefix("dms_object_misc")." ";
	$query .= "ADD INDEX (obj_id)";
	$dmsdb->query($query);

	$query  = "INSERT INTO ".$dmsdb->prefix("dms_config")." ";
	$query .= "(name,data) VALUES ('global_thumbnail_enable','0')";
	$dmsdb->query($query);

	$query  = "INSERT INTO ".$dmsdb->prefix("dms_config")." ";
	$query .= "(name,data) VALUES ('global_thumbnail_width','100')";
	$dmsdb->query($query);

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='0.98' WHERE name='version'";
	$dmsdb->query($query);
	}

function dms_update_0098()
	{
	global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='0.99' WHERE name='version'";
	$dmsdb->query($query);

	$query  = "INSERT INTO ".$dmsdb->prefix("dms_config")." ";
	$query .= "(name,data) VALUES ('pi_upload_max_filesize','128M')";
	$dmsdb->query($query);

	$query  = "INSERT INTO ".$dmsdb->prefix("dms_config")." ";
	$query .= "(name,data) VALUES ('pi_post_max_size','128M')";
	$dmsdb->query($query);

	$query  = "INSERT INTO ".$dmsdb->prefix("dms_config")." ";
	$query .= "(name,data) VALUES ('pi_max_et','300')";
	$dmsdb->query($query);
    }

function dms_update_0099()
	{
	global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='1.00' WHERE name='version'";
	$dmsdb->query($query);

    $query  = "ALTER TABLE ".$dmsdb->prefix("dms_object_versions")." ";
    $query .= "ADD COLUMN file_location smallint(5) NOT NULL DEFAULT '0'";
    $dmsdb->query($query);

    $query  = "ALTER TABLE ".$dmsdb->prefix("dms_object_versions")." ";
    $query .= "ADD COLUMN alt_file_location_path varchar(255) NOT NULL DEFAULT ''";
    $dmsdb->query($query);

    $query  = "UPDATE ".$dmsdb->prefix("dms_object_versions")." ";
    $query .= "SET file_location = '10'";
    $dmsdb->query($query);
    }

function dms_update_0100()
	{
	global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='1.01' WHERE name='version'";
	$dmsdb->query($query);
    }

function dms_update_0101()
	{
	global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='1.02' WHERE name='version'";
	$dmsdb->query($query);
    }

function dms_update_0102()
	{
	global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='1.03' WHERE name='version'";
	$dmsdb->query($query);
    }

function dms_update_0103()
	{
	global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='1.04' WHERE name='version'";
	$dmsdb->query($query);

	$query  = "INSERT INTO ".$dmsdb->prefix("dms_config")." ";
	$query .= "(name,data) VALUES ('root_folder','0')";
	$dmsdb->query($query);
    }

function dms_update_0104()
	{
	global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='1.05' WHERE name='version'";
	$dmsdb->query($query);
    }

function dms_update_0105()
	{
	global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='1.06' WHERE name='version'";
	$dmsdb->query($query);
    }

function dms_update_0106()
    {
    global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='1.10' WHERE name='version'";
	$dmsdb->query($query);
    }

function dms_update_0110()
    {
    global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='1.11' WHERE name='version'";
	$dmsdb->query($query);

	$query  = "INSERT INTO ".$dmsdb->prefix("dms_config")." ";
	$query .= "(name,data) VALUES ('comments_main_screen','0')";
	$dmsdb->query($query);
    }

function dms_update_0111()
    {
    global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='1.12' WHERE name='version'";
	$dmsdb->query($query);
    }

function dms_update_0112()
    {
    global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='1.13' WHERE name='version'";
	$dmsdb->query($query);
    }

function dms_update_0113()
    {
    global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='1.14' WHERE name='version'";
	$dmsdb->query($query);
    }

function dms_update_0114()
    {
    global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='1.15' WHERE name='version'";
	$dmsdb->query($query);
    }

function dms_update_0115()
    {
    global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='1.16' WHERE name='version'";
	$dmsdb->query($query);

	$query  = "INSERT INTO ".$dmsdb->prefix("dms_config")." ";
	$query .= "(name,data) VALUES ('auto_folder_creation','0')";
	$dmsdb->query($query);

    $query  = "CREATE TABLE dms_auto_folder_creation (";
    $query .= "row_id bigint(14) UNSIGNED NOT NULL AUTO_INCREMENT,";
    $query .= "parent_obj_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',";
    //$query .= "user_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',";
    $query .= "group_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',";
    $query .= "user_perms tinyint(2) NOT NULL DEFAULT '0',";
    $query .= "group_perms tinyint(2) NOT NULL DEFAULT '0',";
    $query .= "everyone_perms tinyint(2) NOT NULL DEFAULT '0',";
    $query .= "PRIMARY KEY  (row_id)";
    $query .= ") ". $charset_collate . ";";
	$dmsdb->query($query);
    }


function dms_update_0116()
    {
    global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='1.17' WHERE name='version'";
	$dmsdb->query($query);

	$query  = "INSERT INTO ".$dmsdb->prefix("dms_config")." ";
	$query .= "(name,data) VALUES ('admin_only_perms','0')";
	$dmsdb->query($query);

	$query  = "INSERT INTO ".$dmsdb->prefix("dms_config")." ";
	$query .= "(name,data) VALUES ('admin_only_manage_notify','0')";
	$dmsdb->query($query);
    }

function dms_update_0117()
    {
    global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='1.20' WHERE name='version'";
	$dmsdb->query($query);
    }

function dms_update_0120()
    {
    global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='1.21' WHERE name='version'";
	$dmsdb->query($query);
    }

function dms_update_0121()
    {
    global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='1.22' WHERE name='version'";
	$dmsdb->query($query);
    }

function dms_update_0122()
    {
    global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='1.23' WHERE name='version'";
	$dmsdb->query($query);
    }

function dms_update_0123()
    {
    global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='1.24' WHERE name='version'";
	$dmsdb->query($query);
    }


dms_update_tables();

?>
