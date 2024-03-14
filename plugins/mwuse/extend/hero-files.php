<?php
function mwuse_hero_files_saver()
{
	if ( ! session_id() ) 
	{
      @session_start();
   	}
   	
	if( is_404() && $_SERVER['REQUEST_URI'] && $_SESSION['mwuse_muse_projects'] )
	{
		$mwuse_hero_filetofind = basename( $_SERVER['REQUEST_URI'] );
		$array_path = mwuse_hero_files_seeker( $mwuse_hero_filetofind, $_SESSION['mwuse_muse_projects'] );
		$txt_path = "";
		if( is_array( $array_path ) )
		{
			foreach ( $array_path as $key => $value ) 
			{
				if( !is_int($value) )
				{
					$txt_path .= $value."/";
				}
			}
		}
		if( $txt_path != "" )
		{
			global $wp_filesystem;
			$mwuse_hero_path .= $key;
			WP_Filesystem();
			$file_path = TTR_MW_TEMPLATES_PATH . $txt_path . $mwuse_hero_filetofind;
			if( strpos($mwuse_hero_filetofind, ".html") == false )
			{
				$file = $wp_filesystem->get_contents( $file_path );
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mime_content_type = finfo_file($finfo, $file_path);
				finfo_close($finfo);

				header('Content-Type: '. $mime_content_type);
				echo $file;
				exit();
			}
			else
			{
				$search_page = ttr_get_page_by_template( $txt_path . $mwuse_hero_filetofind );
				if( !empty( $search_page ) )
				{
					wp_redirect( get_permalink( $search_page[0]['ID'] ) );
					exit();
				}				
			}			
		}
	}
}
add_action( 'wp', 'mwuse_hero_files_saver' );


function mwuse_hero_files_seeker( $needle, $haystack, $strict=false, $path=array() )
{
    if( !is_array($haystack) ) {
        return false;
    }
    foreach( $haystack as $key => $val ) {
        if( is_array($val) && $subPath = mwuse_hero_files_seeker($needle, $val, $strict, $path) ) {
            $path = array_merge($path, array($key), $subPath);
            return $path;
        } elseif( (!$strict && $val == $needle) || ($strict && $val === $needle) ) {
            $path[] = $key;
            return $path;
        }
    }
    return false;
}


?>