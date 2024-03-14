<?php
add_action('plugins_loaded', 'sandbox_load_errors', 0);
function sandbox_load_errors() {
    global $sandbox_errors;
    
    $sandbox_errors = array();
    
    $sandbox_errors['table_exists'] = new Sandbox_Error('table_exists', 'Could not create sandbox table because it already exists.'); 
    $sandbox_errors['path_exists_not_dir'] = new Sandbox_Error('path_exists_not_dir', 'The sandbox directory path exists but is not a directory'); 
    $sandbox_errors['dir_no_create'] = new Sandbox_Error('dir_no_create', 'Could not create directory for sandboxes.');
    $sandbox_errors['prefix_exists'] = new Sandbox_Error('prefix_exists', 'Could not create sandbox because existing table has a prefix collision.');
    $sandbox_errors['no_prefix'] = new Sandbox_Error('no_prefix', 'Prefix is not available. Cannot safely continue.');
    $sandbox_errors['create_table'] = new Sandbox_Error('create_table', 'Could not create table for sandbox.');
    $sandbox_errors['insert_table'] = new Sandbox_Error('insert_table', 'Could not insert data into table.');
    $sandbox_errors['update_options'] = new Sandbox_Error('update_options', 'Could not update options table to reflect prefix change.');
    $sandbox_errors['update_usermeta'] = new Sandbox_Error('update_usermeta', 'Could not update usermeta table to reflect prefix change.');
    $sandbox_errors['no_table_list'] = new Sandbox_Error('no_table_list', 'Could not get table listing.');
    $sandbox_errors['no_tables_found'] = new Sandbox_Error('no_tables_found', 'No tables were found to perform migration.');
    $sandbox_errors['no_shortname'] = new Sandbox_Error('no_shortname', 'Shortname must be set.');
    $sandbox_errors['no_name'] = new Sandbox_Error('no_name', 'Name must be set.');
    $sandbox_errors['bad_shortname'] = new Sandbox_Error('bad_shortname', 'Shortname must only contain numbers and letters');
    $sandbox_errors['inuse_shortname'] = new Sandbox_Error('inuse_shortname', 'A sandbox shortname already exists.');
    $sandbox_errors['invalid_action'] = new Sandbox_Error('invalid_action', 'Invalid action. What are you trying to do?');
    $sandbox_errors['no_main_sandbox_dir'] = new Sandbox_Error('no_main_sandbox_dir', 'Sandbox main directory does not exist.');
    $sandbox_errors['corrupt_saved_sandboxes'] = new Sandbox_Error('corrupt_saved_sandboxes', 'Curropt saved sandboxes.');
    $sandbox_errors['sandbox_folder_exists'] = new Sandbox_Error('sandbox_folder_exists', 'Sandbox folder exists. Will not overwrite.');
    $sandbox_errors['sandbox_file_exists'] = new Sandbox_Error('sandbox_file_exists', 'Copying file would result in overwrite.');
    $sandbox_errors['table_prefix_match'] = new Sandbox_Error('table_prefix_match', 'Table exists with new sandbox prefix. This would cause unwanted deletion during sandbox removal.');
    $sandbox_errors['htaccess_denied'] = new Sandbox_Error('htaccess_denied', 'Could not access .htaccess. Please provide temporary .htaccess access during plugin activation.'); 
    $sandbox_errors['htaccess_no_create'] = new Sandbox_Error('htaccess_no_create', 'Could not create .htaccess. Please create .htaccess in wordpress base directory and provide temporary edit permissions during activation.'); 
		$sandbox_errors['sqldump_no_create'] = new Sandbox_Error('sqldump_no_create', 'Could not create SQL dump file in Sandbox.');
		$sandbox_errors['export_dir_no_create'] = new Sandbox_Error('export_dir_no_create', 'Could not find or create export directory.');
		$sandbox_errors['zip_no_create'] = new Sandbox_Error('zip_no_create', 'Could not create zip file.');
}

?>