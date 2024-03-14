<?php

if (!class_exists('LH_copy_from_url_class')) {

class LH_copy_from_url_class {
    
    static function return_plugin_namespace(){
    
        return 'lh_copy_from_url_class';
    
    }


    private static function mime2ext($mime){
        
        $all_mimes = '{"png":["image\/png","image\/x-png"],"bmp":["image\/bmp","image\/x-bmp","image\/x-bitmap","image\/x-xbitmap","image\/x-win-bitmap","image\/x-windows-bmp","image\/ms-bmp","image\/x-ms-bmp","application\/bmp","application\/x-bmp","application\/x-win-bitmap"],"gif":["image\/gif"],"jpg":["image\/jpeg","image\/pjpeg"],"xspf":["application\/xspf+xml"],"vlc":["application\/videolan"],"wmv":["video\/x-ms-wmv","video\/x-ms-asf"],"au":["audio\/x-au"],"ac3":["audio\/ac3"],"flac":["audio\/x-flac"],"ogg":["audio\/ogg","video\/ogg","application\/ogg"],"kmz":["application\/vnd.google-earth.kmz"],"kml":["application\/vnd.google-earth.kml+xml"],"rtx":["text\/richtext"],"rtf":["text\/rtf"],"jar":["application\/java-archive","application\/x-java-application","application\/x-jar"],"zip":["application\/x-zip","application\/zip","application\/x-zip-compressed","application\/s-compressed","multipart\/x-zip"],"7zip":["application\/x-compressed"],"xml":["application\/xml","text\/xml"],"svg":["image\/svg+xml"],"3g2":["video\/3gpp2"],"3gp":["video\/3gp","video\/3gpp"],"mp4":["video\/mp4"],"m4a":["audio\/x-m4a"],"f4v":["video\/x-f4v"],"flv":["video\/x-flv"],"webm":["video\/webm"],"aac":["audio\/x-acc"],"m4u":["application\/vnd.mpegurl"],"pdf":["application\/pdf","application\/octet-stream"],"pptx":["application\/vnd.openxmlformats-officedocument.presentationml.presentation"],"ppt":["application\/powerpoint","application\/vnd.ms-powerpoint","application\/vnd.ms-office","application\/msword"],"docx":["application\/vnd.openxmlformats-officedocument.wordprocessingml.document"],"xlsx":["application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application\/vnd.ms-excel"],"xl":["application\/excel"],"xls":["application\/msexcel","application\/x-msexcel","application\/x-ms-excel","application\/x-excel","application\/x-dos_ms_excel","application\/xls","application\/x-xls"],"xsl":["text\/xsl"],"mpeg":["video\/mpeg"],"mov":["video\/quicktime"],"avi":["video\/x-msvideo","video\/msvideo","video\/avi","application\/x-troff-msvideo"],"movie":["video\/x-sgi-movie"],"log":["text\/x-log"],"txt":["text\/plain"],"css":["text\/css"],"html":["text\/html"],"wav":["audio\/x-wav","audio\/wave","audio\/wav"],"xhtml":["application\/xhtml+xml"],"tar":["application\/x-tar"],"tgz":["application\/x-gzip-compressed"],"psd":["application\/x-photoshop","image\/vnd.adobe.photoshop"],"exe":["application\/x-msdownload"],"js":["application\/x-javascript"],"mp3":["audio\/mpeg","audio\/mpg","audio\/mpeg3","audio\/mp3"],"rar":["application\/x-rar","application\/rar","application\/x-rar-compressed"],"gzip":["application\/x-gzip"],"hqx":["application\/mac-binhex40","application\/mac-binhex","application\/x-binhex40","application\/x-mac-binhex40"],"cpt":["application\/mac-compactpro"],"bin":["application\/macbinary","application\/mac-binary","application\/x-binary","application\/x-macbinary"],"oda":["application\/oda"],"ai":["application\/postscript"],"smil":["application\/smil"],"mif":["application\/vnd.mif"],"wbxml":["application\/wbxml"],"wmlc":["application\/wmlc"],"dcr":["application\/x-director"],"dvi":["application\/x-dvi"],"gtar":["application\/x-gtar"],"php":["application\/x-httpd-php","application\/php","application\/x-php","text\/php","text\/x-php","application\/x-httpd-php-source"],"swf":["application\/x-shockwave-flash"],"sit":["application\/x-stuffit"],"z":["application\/x-compress"],"mid":["audio\/midi"],"aif":["audio\/x-aiff","audio\/aiff"],"ram":["audio\/x-pn-realaudio"],"rpm":["audio\/x-pn-realaudio-plugin"],"ra":["audio\/x-realaudio"],"rv":["video\/vnd.rn-realvideo"],"jp2":["image\/jp2","video\/mj2","image\/jpx","image\/jpm"],"tiff":["image\/tiff"],"eml":["message\/rfc822"],"pem":["application\/x-x509-user-cert","application\/x-pem-file"],"p10":["application\/x-pkcs10","application\/pkcs10"],"p12":["application\/x-pkcs12"],"p7a":["application\/x-pkcs7-signature"],"p7c":["application\/pkcs7-mime","application\/x-pkcs7-mime"],"p7r":["application\/x-pkcs7-certreqresp"],"p7s":["application\/pkcs7-signature"],"crt":["application\/x-x509-ca-cert","application\/pkix-cert"],"crl":["application\/pkix-crl","application\/pkcs-crl"],"pgp":["application\/pgp"],"gpg":["application\/gpg-keys"],"rsa":["application\/x-pkcs7"],"ics":["text\/calendar"],"zsh":["text\/x-scriptzsh"],"cdr":["application\/cdr","application\/coreldraw","application\/x-cdr","application\/x-coreldraw","image\/cdr","image\/x-cdr","zz-application\/zz-winassoc-cdr"],"wma":["audio\/x-ms-wma"],"vcf":["text\/x-vcard"],"srt":["text\/srt"],"vtt":["text\/vtt"],"ico":["image\/x-icon","image\/x-ico","image\/vnd.microsoft.icon"],"csv":["text\/x-comma-separated-values","text\/comma-separated-values","application\/vnd.msexcel"],"json":["application\/json","text\/json"]}';
        $all_mimes = json_decode($all_mimes,true);
  
        foreach ($all_mimes as $key => $value) {
            
            if(array_search($mime,$value) !== false){
                
                return $key;    
                
            }
            
        }
        
        return false;
        
    }


    private static function get_existing_attachment_id($url){
        
        if (!self::isValidURL($url)){
            
            return false;
            
        }

        global $wpdb;
        $results = $wpdb->get_results($wpdb->prepare( "SELECT 	posts.ID, meta.meta_id, meta.meta_value FROM ".$wpdb->posts." posts, ".$wpdb->postmeta." meta WHERE posts.post_type = 'attachment' and posts.ID = meta.post_id and meta.meta_key = '_lh_copy_from_url-original_file' and meta.meta_value = %s LIMIT 1", $url) );

        if (!empty($results[0]->ID)){
        
            return $results[0]->ID;
        
        } else {
                
            return false;
           
        }
        
    }


    /*
	** Removes jetpack crap to return original image
	*/

	
    private static function maybe_strip_query_vars($url) {
        
        $subdomains = array('i0.wp.com','i1.wp.com','i2.wp.com');
        $parsed = wp_parse_url($url); 
        
        if (isset($parsed['host']) && !empty($parsed['host']) && in_array ( $parsed['host'] , $subdomains )){

            $url = remove_query_arg( array('w', 'h', 'ssl','crop','resize','fit','lb','ulb','filter','brightness','contrast','colorize','smooth','zoom','quality','strip'), $url );  
            
        }
        
        return $url;
        
    }
    
    private static function isValidURL($url){
        
        if (empty($url)){
            
            return false;
            
        } else {
            
            return (bool)wp_parse_url($url);
            
        }
    
    }
    
    /*
	** download_url clones the wordpress native method but with a spoofed user-agent
	*/

	private static function download_url( $url, $timeout = 300 ) {
    
        //WARNING: The file is not automatically deleted, The script must unlink() the file.
        
        if ( ! self::isValidURL($url) ){
            
            return new WP_Error('http_no_url', __('Invalid URL Provided.'));
            
        }
 
        $url_filename = basename(wp_parse_url( $url, PHP_URL_PATH ) );
        $tmpfname = wp_tempnam( $url_filename );
    
        if ( ! $tmpfname ){
            
            return new WP_Error('http_no_file', __('Could not create Temporary file.'));
            
        }
 
        $response = wp_safe_remote_get( $url, array( 'timeout' => $timeout, 'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:20.0) Gecko/20100101 Firefox/20.0', 'stream' => true, 'filename' => $tmpfname ) );
 
        if ( is_wp_error( $response ) ) {
            
            unlink( $tmpfname );
            return $response;
            
        }
 
        if ( 200 != wp_remote_retrieve_response_code( $response ) ){
            
            unlink( $tmpfname );
            return new WP_Error( 'http_404', trim( wp_remote_retrieve_response_message( $response ) ) );
            
        }
 
        $content_md5 = wp_remote_retrieve_header( $response, 'content-md5' );
        
        if ( $content_md5 ) {
            
            $md5_check = verify_file_md5( $tmpfname, $content_md5 );
            
            if ( is_wp_error( $md5_check ) ) {
                
                unlink( $tmpfname );
                return $md5_check;
                
            }
            
        }
 
        return $tmpfname;
	    
	}



    /*
	** Guess File extension
	*/

    public static function guess_file_extension( $url, $response ){
		
        $fileextension = @image_type_to_extension( exif_imagetype( $url ) );

        if (empty($fileextension) and isset($headers['mime'])){

            $extension = self::mime2ext( wp_remote_retrieve_header( $response, 'mime' ));

            if (!empty($extension)){
            
            $fileextension = '.'.$extension;
            
            }
            
        }
        
        if (empty($fileextension)){

            $filename_from_url = wp_parse_url($url);
            $fileextension = '.'.pathinfo($filename_from_url['path'], PATHINFO_EXTENSION);

        }

        if ($fileextension == '.jpeg'){
        
            $fileextension = '.jpg';
        
        }

        return $fileextension;

	}
	
	



    /*
    ** Download external files and upload to our server.
    */

    public static function save_external_file($url, $post_id = 0, $desc = '', $check = false){
 
        if (!function_exists('wp_crop_image')){
            
            require_once(ABSPATH . "wp-admin" . '/includes/image.php');
            
        }
        
        if (!function_exists('get_file_description')){
            
            require_once(ABSPATH . "wp-admin" . '/includes/file.php');
            
        }
        
        if (!function_exists('media_upload_tabs')){
            
            require_once(ABSPATH . "wp-admin" . '/includes/media.php');
            
        }

        $url = self::maybe_strip_query_vars($url);
        
        if (isset($check) and ($check === TRUE)){
        
            $existing = self::get_existing_attachment_id($url);
        
        }

        if (!empty($existing) and is_numeric($existing)){
        
            return $existing;
        
        } else {

            $data = array();

            //$tmp = self::download_url( $url);

            $url_filename = basename( wp_parse_url( $url, PHP_URL_PATH ) );
            $tmpfname = wp_tempnam( $url_filename );

            if ( ! $tmpfname ){
                
                return new WP_Error('http_no_file', __('Could not create Temporary file.'));
                
            }
 
            $args = apply_filters(self::return_plugin_namespace().'_remote_get_args', array( 'timeout' => 20, 'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:20.0) Gecko/20100101 Firefox/20.0', 'stream' => true, 'filename' => $tmpfname ));
            $response = wp_safe_remote_get( $url, $args);
 
            if ( is_wp_error( $response ) ) {
                
                unlink( $tmpfname );
                return $response;
                
            }
 
            if ( 200 != wp_remote_retrieve_response_code( $response ) ){
                
                unlink( $tmpfname );
                return new WP_Error( 'http_404', trim( wp_remote_retrieve_response_message( $response ) ) );
                
            }
 
            $content_md5 = wp_remote_retrieve_header( $response, 'content-md5' );
            
            if ( $content_md5 ) {
                
                $md5_check = verify_file_md5( $tmpfname, $content_md5 );
                
                if ( is_wp_error( $md5_check ) ) {
                    
                    unlink( $tmpfname );
                    return $md5_check;
                    
                }
                
            }

            $fileextension = self::guess_file_extension( $url, $response );
            $file_array = array();
            $path = pathinfo( $tmpfname );

			if( !isset( $path['extension'] ) ){

				 $tmpnew = $tmpfname . '.tmp';
				 $file_array['tmp_name'] = $tmpnew;				 
				 
			} else {
			    
				$file_array['tmp_name'] = $tmpfname;
				
			}	

			$name = pathinfo( $url, PATHINFO_FILENAME )  . $fileextension;
			$file_array['name'] = sanitize_file_name($name);
			// $file_array['type'] = mime_content_type( $file_array['tmp_name'] );		

			// If error storing temporarily, unlink

			if ( is_wp_error( $tmpfname ) ) {

				@unlink($file_array['tmp_name']);

				return $tmpfname;
			}
			
			// do the validation and storage stuff			
			$id = media_handle_sideload( $file_array, $post_id , $desc );
			
			$local_url = wp_get_attachment_url( $id );

			// If error storing permanently, unlink
			if ( is_wp_error($id) ) {

				@unlink($file_array['tmp_name']);

			} else {
			    
                $parent_post = get_post($post_id);
	
                if (isset($parent_post->ID)){
                	
                	$fix_author = array(
                        'ID'            => $id,
                        'post_author'   => $parent_post->post_author,
                    );
                  
                     // Update the post into the database
                     wp_update_post( $fix_author );
                
                }

            	// create the thumbnails
            	$attach_data = wp_generate_attachment_metadata( $id,  get_attached_file($id));
            	wp_update_attachment_metadata( $id,  $attach_data );

            	//save the original url as post meta
            	add_post_meta($id, '_lh_copy_from_url-original_file', $url, true);
			    
			}

            return $id;

		} 

    } 

} 


}

?>