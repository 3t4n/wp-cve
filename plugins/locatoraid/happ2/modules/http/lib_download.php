<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Http_Lib_Download_HC_MVC extends _HC_MVC
{
	public function download_file( $filename, $short_name )
	{
		if( ob_get_contents() ){
			ob_end_clean();
		}

		$file_size = filesize( $filename );

		header("Type: application/force-download");
		header("Content-Type: application/force-download");
		header("Content-Length: $file_size");

		header("Content-Transfer-Encoding: binary");
		header("Content-Disposition: attachment; filename=\"$short_name\"");

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Connection: close");

		readfile( $filename );
		exit;
	}

	public function download( $filename, $data )
	{
	// Try to determine if the filename includes a file extension.
	// We need it in order to set the MIME type
		if (FALSE === strpos($filename, '.')){
			return FALSE;
		}

	// Grab the file extension
		$x = explode('.', $filename);
		$extension = end($x);

		// Load the mime types
		$mimes = array();

		// Set a default mime if we can't find it
		if ( ! isset($mimes[$extension])){
			$mime = 'application/octet-stream';
		}
		else {
			$mime = (is_array($mimes[$extension])) ? $mimes[$extension][0] : $mimes[$extension];
		}

	// Generate the server headers
		if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") !== FALSE){
			header('Content-Type: "'.$mime.'"');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header("Content-Transfer-Encoding: binary");
			header('Pragma: public');
			header("Content-Length: ".strlen($data));
		}
		else {
			header('Content-Type: "'.$mime.'"');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header("Content-Transfer-Encoding: binary");
			header('Expires: 0');
			header('Pragma: no-cache');
			header("Content-Length: ".strlen($data));
		}

		exit($data);
	}
}