<?php
/**
 * Author: Vitaly Kukin
 * Date: 26.02.2018
 * Time: 8:46
 */

namespace dm;


class dmMedia {
	
	private $wpdb;
	
	public static $additional_image_sizes = [
		'ads-thumb'  => [ 'width' => 50, 'height' => 50, 'crop' => true ],
		'ads-medium' => [ 'width' => 220, 'height' => 220, 'crop' => true ],
		'ads-big'    => [ 'width' => 350, 'height' => 350, 'crop' => true ],
		'ads-large'  => [ 'width' => 640, 'height' => 640, 'crop' => true ],
	];
	
	private $image_sizes = [ 'ads-thumb', 'ads-medium', 'ads-big', 'ads-large' ];
	
	private $generate = false;
	
	public function __construct() {
		
		global $wpdb;
		
		$this->wpdb = $wpdb;
		
		add_filter( 'intermediate_image_sizes_advanced', function( $sizes, $metadata ) {
			
			if( ! $this->generate ) {
				return $sizes;
			}
			
			return self::$additional_image_sizes;
			
		}, 9999, 2 );
	}
	
	/**
	 * @param $post_id
	 * @param $img64
	 * @param bool $name
	 * @param bool $replace
	 *
	 * @return array
	 */
	public function attachmentImage64($post_id, $img64, $name = false, $replace = false){
		
		list($type, $data) = explode(';', $img64);
		list(, $data64)      = explode(',', $data);
		
		$ext = str_replace('data:image/', '', $type);
		
		$p = $name ? explode('.', $name): $this->generateRandomString();
		
		$filename = $post_id . '-' . md5( sanitize_title( $p[0] ) ). '.'.$ext;
		
		$wp_filetype = wp_check_filetype( $filename );
		
		if( !$wp_filetype ){
			return [
				'url' => false,
				'id'  => false
			];
		}
		
		$attach_id = $this->getAttachImageId( basename( $filename ) );
		
		if ( empty( $attach_id ) || $replace) {
			$attach_id = $this->loadImages($post_id, $data64, $filename, basename( $filename ), $replace);
		}
		
		return [
			'url' => $this->getImageById( $attach_id, 'full' ),
			'id'  => $attach_id
		];
	}
	
	
	public function crop($imgUrl , $crop){
		
		$pr = 'cropped-';
		
		$src_file =  $this->urlToDirImg($imgUrl);
		$src_file = str_replace(  $pr, '', $src_file );
		$dst_file = str_replace( basename( $src_file ), $pr . basename( $src_file ), $src_file );
		
		$editor = wp_get_image_editor( $src_file);
		
		$editor->crop( $crop['x'], $crop['y'], $crop['cw'], $crop['ch'], null, null, false );
		
		$editor->save( $dst_file );
		
		return [
			'url' => $this->getUrlImg($dst_file)
		];
	}
	
	/**
	 * Upload Image by URL and Attach its to post by post ID
	 *
	 * @param $post_id - post_id. The images will attached to ths post
	 * @param $name - title for images (to 'alt' tag)
	 * @param $url - url of image
	 * @param $size - return image url for this size ('thumbnail' || 'medium' || 'large' || 'full' || etc.)
	 *
	 * @return mixed
	 */
	public function attachmentImage( $post_id, $url, $size = 'full', $name = '' ) {
		
		$url = $this->getFullUrlImg($url);
		
		preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png|webp)\b/i', $url, $matches );
		if ( ! $matches ) {
			return false;
		}
		
		$p = strrpos( $url, "." );
		$r = substr( $url, $p );
		
		if ( $name != '' ) {
			$filename = $post_id . '-' . sanitize_title( $name ) . $r;
		} else {
			$filename = $post_id . '-' . sanitize_title( md5( $url ) ) . $r;
			$name     = basename( $filename );
		}
		
		$attach_id = $this->getAttachImageId( basename( $filename ) );
		
		if ( empty( $attach_id ) ) {
			$attach_id = $this->loadImages($post_id, $url, $filename, $name);
		}
		
		return [
			'url' => $this->getImageById( $attach_id, $size ),
			'id'  => $attach_id
		];
	}
	
	public function getAttachIdByUrl($url){
		
		$path = $this->getPathAttachByUrl($url);
		
		$wpdb = $this->wpdb;
		
		$sql = $wpdb->prepare(
			"SELECT * FROM {$wpdb->postmeta} 
			WHERE meta_key = '_wp_attached_file' AND meta_value = '%s'", $path );
		
		$results = $wpdb->get_results( $sql, ARRAY_A);
		
		return isset($results[0]['post_id']) ? $results[0]['post_id'] : false;
	}
	
	private function getImageById( $id, $size = 'thumbnail' ) {
		
		$img = wp_get_attachment_image_src( $id, $size, false );
		
		if ( $img ) {
			return $img[ 0 ];
		}
		
		return false;
	}
	
	private function getFullUrlImg( $url ) {
		$url = preg_replace('/(_\d+x\d+\.jpe?g)/','',$url);
		
		return $url;
	}
	
	private function getAttachImageId( $likeTitleNameFile ) {
		
		$attachment = $this->wpdb->get_col(
			$this->wpdb->prepare(
				"SELECT ID FROM {$this->wpdb->posts} WHERE post_name LIKE '%s' AND post_type='attachment';", '%' . $this->wpdb->esc_like( $likeTitleNameFile ) . '%'
			)
		);
		
		return isset( $attachment[ 0 ] ) ? $attachment[ 0 ] : false;
	}
	
	private function file_get_contents( $file ) {
		
		$response = wp_remote_get( $file, [
			'timeout'   => 15,
			'sslverify' => false
		] );
		
		if ( ! is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) === 200 ){
			return $response['body'];
		}
		
		return false;
	}
	
	private function getContentData($data){
		
		if(substr($data, 0,2) == '//'){
			$data = 'http:'. $data;
		}
		
		if(substr($data, 0,4) == 'http'){
			return $this->file_get_contents($data);
		}
		
		return base64_decode($data);
	}
	
	private function getBytesFromHexString($hexdata)
	{
		for($count = 0; $count < strlen($hexdata); $count+=2)
			$bytes[] = chr(hexdec(substr($hexdata, $count, 2)));
		
		return implode($bytes);
	}
	
	private function getImageMimeType( $imagedata ) {
		
		$imagemimetypes = [
			"jpeg" => "FFD8",
			"png"  => "89504E470D0A1A0A",
			"gif"  => "474946",
			"bmp"  => "424D",
		];
		
		foreach ($imagemimetypes as $mime => $hexbytes)
		{
			$bytes = $this->getBytesFromHexString($hexbytes);
			if (substr($imagedata, 0, strlen($bytes)) == $bytes)
				return $mime;
		}
		
		return NULL;
	}
	
	/**
	 * @param $url
	 *
	 * @return array
	 */
	public  function urlToBase64( $url ) {
		
		$data = $this->getContentData( getFullUrlImg( $url ) );
		
		$ext = $this->getImageMimeType($data);
		if($ext){
			return [ 'data' => 'data:image/' . $ext . ';base64,' . base64_encode($data) ];
		}
		return [ 'data' => false ];
	}
	
	private function loadImages( $post_id, $data , $filename, $name, $replace = false) {
		
		$uploaddir  = wp_upload_dir();
		$uploadfile = $uploaddir[ 'path' ] . '/' . $filename;
		
		if ( ! file_exists( $uploadfile ) || $replace) {
			
			$contents = $this->getContentData($data);
			
			if($contents == false)
				return false;
			$savefile = fopen( $uploadfile, 'w' );
			fwrite( $savefile, $contents );
			fclose( $savefile );
		}
		
		
		if ( !file_exists( $uploadfile ) ) {
			return false;
		}
		
		if ( filesize( $uploadfile ) == 0 ) {
			unlink($uploadfile);
			return false;
		}
		
		$wp_filetype = wp_check_filetype( basename( $filename ), null );
		
		$name = apply_filters( 'media_attachment_title', $name, $post_id );
		
		$attachment_data = [
			'post_mime_type' => $wp_filetype[ 'type' ],
			'post_title'     => $name,
			'post_content'   => '',
			'post_status'    => 'inherit'
		];
		
		$attach_id    = wp_insert_attachment( $attachment_data, $uploadfile, $post_id );
		
		$this->generateAttachment( $attach_id, $name );
		
		update_post_meta( $attach_id, '_wp_attachment_image_alt', $name );
		
		return $attach_id;
	}
	
	public function generateAttachment( $attach_id, $name = false, $image_sizes = [ 'ads-thumb', 'ads-medium', 'ads-big', 'ads-large' ] ) {
		
		$this->generate = true;
		
		$this->image_sizes      = array_merge( [ 'thumbnail' ], $image_sizes );
		$additional_image_sizes = [];
		
		foreach( $this->image_sizes as $key ) {
			
			if( isset( self::$additional_image_sizes[ $key ] ) ) {
				
				$additional_image_sizes[ $key ] = self::$additional_image_sizes[ $key ];
			}
		}
		
		global $_wp_additional_image_sizes;
		
		$imagenew     = get_post( $attach_id );
		$fullsizepath = get_attached_file( $imagenew->ID );
		
		if( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
			include( ABSPATH . 'wp-admin/includes/image.php' );
		}
		
		$_temp_wp_additional_image_sizes = wp_get_additional_image_sizes();
		$_wp_additional_image_sizes      = $additional_image_sizes;
		
		$attach_data = \wp_generate_attachment_metadata( $attach_id, $fullsizepath );
		
		if( $name ) {
			$attach_data[ 'image_meta' ][ 'title' ] = $name;
		}
		
		\wp_update_attachment_metadata( $attach_id, $attach_data );
		
		$_wp_additional_image_sizes = $_temp_wp_additional_image_sizes;
		
		$this->generate = false;
	}
	
	static public function setParentAttachment( $attach_id, $post_id ) {
		
		$arg = [
			'ID'          => $attach_id,
			'post_parent' => $post_id
		];
		
		wp_update_post( $arg );
	}
	
	private function getPathAttachByUrl( $url ) {
		
		return preg_replace( '/(^.*uploads\/)/', '', $url );
	}
	
	private function urlToDirImg( $imgUrl ) {
		
		$file    = $this->getPathAttachByUrl( $imgUrl );
		
		$uploads = wp_get_upload_dir();
		
		return $uploads[ 'basedir' ] . "/$file";
	}
	
	private function dirToUrlImg( $cropped ) {
		
		$url = wp_get_upload_dir();
		
		return $url['baseurl'].'/'.$this->getPathAttachByUrl( $cropped );
	}
	
	/**
	 * @param array $post
	 *
	 * @return array
	 */
	public function actions( $post ) {
		
		if ( isset( $post[ 'ads_action' ] ) && current_user_can( 'activate_plugins' ) ) {
			
			$ads_actions = 'action_' . $post[ 'ads_action' ];
			
			if( method_exists( $this, $ads_actions ) ) {
				return $this->$ads_actions( $post );
			}
		}
		
		return [ 'error' => __( 'Undefined action', 'dm' ) ];
	}
	
	private function action_save_image64($post){
		
		$img64 = $post['file64'];
		$imgUrl = $post['src'];
		
		$pr = isset($post['crop_name']) && $post['crop_name'] ? basename($post['crop_name']).'-' : 'cropped-';
		
		$src_file =  $this->urlToDirImg($imgUrl);
		
		$src_file = str_replace(  $pr, '', $src_file );
		
		$dst_file = str_replace( basename( $src_file ), $pr . basename( $src_file ), $src_file );
		
		list($type, $data) = explode(';', $img64);
		list(, $data)      = explode(',', $data);
		
		$contents = base64_decode($data);
		
		if($contents == false)
			return false;
		$savefile = fopen( $dst_file, 'w' );
		fwrite( $savefile, $contents );
		fclose( $savefile );
		
		return [
			'url' => $this->dirToUrlImg($dst_file)
		];
		
	}
	
	public function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}