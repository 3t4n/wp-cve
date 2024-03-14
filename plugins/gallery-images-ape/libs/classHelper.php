<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ){ exit;  }

class apeGalleryHelper {

	static function array_insert_after( array $array, $key, array $new ) {
		$keys = array_keys( $array );
		$index = array_search( $key, $keys );
		$pos = false === $index ? count( $array ) : $index + 1;
		return array_merge( array_slice( $array, 0, $pos ), $new, array_slice( $array, $pos ) );
	}

	static function checkEvent(){
		if( WPAPE_GALLERY_PREMIUM ) return false;

		/*if(  
			! defined('APE_GALLERY_EVENT') ||
			! APE_GALLERY_EVENT ||
			! defined('APE_GALLERY_EVENT_DATE') ||
			! defined('APE_GALLERY_EVENT_HOUR') 
		) return false;


		$eventDate = strtotime(APE_GALLERY_EVENT_DATE);
		$eventHour = APE_GALLERY_EVENT_HOUR * 60 * 60;
		if( 
			( time() - $eventDate < 0 ) ||  
			( time() - $eventDate > $eventHour ) 
		) return false;*/

		return true;
	}

	static function writeLog($message){
		return ;  /* need only for debug  */
		$log_file = WPAPE_GALLERY_PATH.'/error.log';
		if( !file_exists($log_file) ) file_put_contents($log_file, "====== Log start ==== \n");
		if( $handle = fopen($log_file, 'a') ){
			fwrite($handle, "\n".$message);
			fclose($handle);
		}
	}
	
	static public function isUserAdmin(){  //apeGalleryHelper::isUserAdmin()
		if( !is_admin() ) return false;
		if( !current_user_can( 'manage_options' ) ) return false;
		return true;
	}

	static public function isAdminArea($allowAjax = 0){ //apeGalleryHelper::isAdminArea()
		if( !is_admin() ) return false;		
		if( !$allowAjax && defined('DOING_AJAX') && DOING_AJAX ) return false;  
		if( !$allowAjax &&  function_exists('wp_doing_ajax') && wp_doing_ajax() ) return false;
		if( isset($_REQUEST['doing_wp_cron']) ) return false;
		return true;
	}	

	static function load( $filesList, $folder='' ){
		if(empty($filesList)) return;
		if(!$folder)$folder=WPAPE_GALLERY_INCLUDES_PATH;
		if(!is_array($filesList)) $filesList=array($filesList);
		for( $j=0;$j<count($filesList);$j++){ 
			$fileName = $filesList[$j];
			if(!$fileName) next();
			if(file_exists($folder.$fileName))require_once $folder.$fileName;
		}
	}

	static function getPostType() {
        global $post, $typenow, $current_screen;
        if ( $post && $post->post_type )                         					return $post->post_type;
          elseif( $typenow )                                      					return $typenow;
          elseif( $current_screen && $current_screen->post_type ) 					return $current_screen->post_type;
          elseif( isset( $_REQUEST['post_type'] ) )               					return sanitize_key( $_REQUEST['post_type'] );
          elseif (isset( $_REQUEST['post'] ) && get_post_type($_REQUEST['post']))	return get_post_type($_REQUEST['post']);
        return null;
    }

    static function is_edit( $new_edit = null ){
        global $pagenow;

        if ( !self::isAdminArea() ) return false;
        
        $page = '';
        if( isset($_GET['page']) &&  $_GET['page'] ) $page = $_GET['page'];

        if($new_edit == "list")             return  !$page && in_array( $pagenow, array( 'edit.php' ) );
            elseif($new_edit == "edit")     return in_array( $pagenow, array( 'post.php' ) );
                elseif($new_edit == "new")  return in_array( $pagenow, array( 'post-new.php' ) );
                    else  return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
    }
	
    static function check_new_edit_page( $new_edit = null ){
        global $pagenow;
        if ( !self::isAdminArea() ) return false;
        if( $new_edit=="list" ) return in_array( $pagenow, array( 'edit.php',  ) );
            elseif( $new_edit=="edit" ) return in_array( $pagenow, array( 'post.php' ) );
                elseif($new_edit == "new") return in_array( $pagenow, array( 'post-new.php' ) );
                    else  return in_array( $pagenow, array( 'post.php', 'post-new.php', 'edit.php' ) );
    }

    static function showError( 
    		$errorCode = '403', 
    		$errorNumber = '210', 
    		$errorDesc = 'Please post ticket with this Error ID into support section.'
    ){
    	$errorMessage = 'Error #'.$errorNumber.' '.$errorDesc;
		wp_die( $errorMessage, $errorCode);
    }

    private static function getLicenseFile(){
		$premiumPath 	= '';
		$key_dir  	= 'wpapegallerylicence';
		$key_file 	= 'wpape-licence.php';
		$premiumPath = WPAPE_GALLERY_PATH.$key_file;
		if( file_exists($premiumPath) ) return $premiumPath;
		for($i=-1;$i<6;$i++){ 
			$premiumPath = WP_PLUGIN_DIR.'/'.$key_dir.($i!=-1?'-'.$i:'').'/'.$key_file;
			if ( file_exists($premiumPath) ) return $premiumPath;
		}
		for($i=0;$i<6;$i++){ 
			$premiumPath = WP_PLUGIN_DIR.'/'.$key_dir.$i.'/'.$key_file;
			if ( file_exists($premiumPath) ) return $premiumPath;
		}
		return false;
	}


	public static function checkVersion(){

		if( defined('WPAPE_GALLERY_PREMIUM') ) return ;

		$premiumPath = self::getLicenseFile();
		if( $premiumPath ){
			define("WPAPE_GALLERY_PREMIUM", 1);
			define("WPAPE_GALLERY_LICENCE_PATH", $premiumPath );
			define("WPAPE_GALLERY_LICENCE_PATH_DIR", dirname($premiumPath).'/' );
			require_once WPAPE_GALLERY_LICENCE_PATH;
		} else {
			define("WPAPE_GALLERY_PREMIUM", 0);
		}
	}

	public static function compareVersion( $version ){
		if( !WPAPE_GALLERY_PREMIUM ) return false;
		if( !defined("WPAPE_GALLERY_KEY_VERSION") ) return false;
		return version_compare( WPAPE_GALLERY_KEY_VERSION , $version , '>=' );
	}


	public static function getUpdateButton( $label ){
		if( !WPAPE_GALLERY_PREMIUM ) return '';
		return '<div class="content small-12 columns text-center" style="margin: 25px 0 -5px;">
					<a href="'.WPAPE_GALLERY_URL_UPDATEKEY.'" target="_blank" class="hollow warning button">'.$label.'</a>
				</div>';
	}	


	public static function getAddonButton( $label ){
		if( WPAPE_GALLERY_PREMIUM ) return '';
		return '<div class="content small-12 columns text-center" style="margin: 25px 0 -5px;">
					<a href="'.WPAPE_GALLERY_URL_ADDONS.'" target="_blank" class="warning button">+ '.$label.'</a>
				</div>';
	}


	static function clearString( $str1 = '' ){
		if($str1){
			$str1 = str_replace( 
						array(
						 	'"', "'", '\\', '/', '|', '?',  '!', '@', '#', '<', '>', '&', '^', '%',  '$',  ':', ';', '{', '}', '[', ']',  
						), '', $str1 );
		}
		return $str1;
	}


	static function getThemeType(){
		$typeField = WPAPE_GALLERY_NAMESPACE.'type';
		$type = isset($_REQUEST[$typeField]) && trim($_REQUEST[$typeField]) ? trim($_REQUEST[$typeField]) : '';
		if( isset($_REQUEST['post']) && (int) $_REQUEST['post'] ){
			$type = get_post_meta( (int) $_REQUEST['post'], $typeField, true );
		}
		$type = preg_replace( '/[^a-z]/i', '', $type );
		return $type;
	}

	static function renderGalleryId( $id ){
		if( ! $id ) return 'Ape Gallery Error: #3333 - id empty';
		return self::renderGalleryAttr( array( 'id' => $id ) );
	}	

	static function renderGalleryAttr( $attr ){
		if( !class_exists('apeGalleryRender') ) return 'Ape Gallery Error: #4444 - class missing';
		return apeGalleryRender::getContent( $attr );
	}

	static public function getThemeIdFromGallery( $id = 0 ){
		$themeId = -1; // default theme
		if( $id ) $themeId = (int) get_post_meta( $id, WPAPE_GALLERY_NAMESPACE.'themeId', true );
		if( $themeId <= 0 ) $themeId = (int) get_option( WPAPE_GALLERY_PREFIX.'default_theme', 0 );
		return $themeId;
	}

	static function getThemeId( $galleryId ){
		
	}

}