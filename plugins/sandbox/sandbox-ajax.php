<?php
add_action('wp_ajax_sandbox_edit_verify', 'sandbox_edit_verify_ajax');

function sandbox_edit_verify_ajax(){
    global $sandbox_errors;
    
    $name = $_REQUEST['name'];
    $shortname = $_REQUEST['shortname'];
    $description = $_REQUEST['description'];
    $action = $_REQUEST['edit_action'];
    try {
        Sandbox::verify_parameters($action, $name, $shortname);
    } catch (Sandbox_Exception $sandbox_exception) {
        $sandbox_exception->sandbox_error->print_error();
    }
    die();
}

add_action('wp_ajax_export_download', 'export_download_callback');

function export_download_callback(){
	global $sandboxes;
	error_log("downlaod");
	if (!isset($sandboxes[$_REQUEST['shortname']])) die();

	$sandbox = $sandboxes[$_REQUEST['shortname']];
	
	try {
		$export_file = $sandbox->export_file(); 
		header("Content-Disposition: attachment; filename=".basename(str_replace(' ', '_', $export_file)));
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Description: File Transfer");
		header("Content-Length: " . filesize($export_file));
		flush(); // this doesn't really matter.

		$fp = fopen($export_file, "r");
		while (!feof($fp))
		{
				echo fread($fp, 65536);
				flush(); 
		}
		fclose($fp);
		
		unlink($export_file);
	} catch (Exception $ex) {
		error_log($ex);
		die();
	}
	
	die();
}

    

?>