<?php
/**
 * Author: Vitaly Kukin
 * Date: 26.02.2018
 * Time: 8:44
 */

namespace dm;


class dmImages {
	
	protected $media;
	
	public function __construct() {
		$this->media = new dmMedia();
	}
	
	protected function actions_list_images_post( $post ) {
		
		$post_id = intval( $post[ 'args' ][ 'post_id' ] );
		
		return [
			'links'        => $this->list_images_post( $post_id ),
			'current_link' => 0,
			'message'      => __( 'Upload images. Progress', 'dm' )
		];
	}
	
	protected function list_images_post( $post_id ) {
		
		$post_id = intval( $post_id );
		
		$links = [];
		
		if( $post_id == 0 ) {
			return $links;
		}
		
		$links += $this->linkFeature( $post_id );
		$links += $this->linkGallery( $post_id );
		$links += $this->linkVariation( $post_id );
		$links = array_values( array_unique( $links ) );
		$links = $links ? $links : [];
		
		return $links;
	}
	
	protected function load_image_post( $post ) {
		
		$current_link = (int) $post[ 'args' ][ 'current_link' ];
		$links        = $post[ 'args' ][ 'links' ];
		$post_id      = (int) $post[ 'args' ][ 'post_id' ];
		
		if( $post_id == 0 ) {
			return [ 'error' => __( 'not post_id', 'dm' ) ];
		}
		
		$link   = $links[ $current_link ];
		$status = $this->attachmentImage( $post_id, $link );
		
		if( $status === false ) {
			return [ 'error' => 'error attachmentImage', 'current_link' => $current_link, 'link' => $link ];
		}
		
		$current_link++;
		
		$countPercent = round($current_link / count($links) * 100) ;
		
		return [
			'links'        => $links,
			'current_link' => $current_link,
			'attachment'   => $status,
			'message'      => __( 'Upload images. Progress', 'dm' ) . ': ' . $countPercent . '%' ];
	}
	
	protected function actions_load_image_post( $post ) {
		
		return $this->load_image_post( $post );
	}
	
	protected function actions_load_image( $post ) {
		
		$post['args']['post_id'] = $this->getCurrentPostId();
		
		return $this->load_image_post( $post );
	}
	
	protected function linkFeature( $post_id ) {
		
		$product = $this->getProduct( $post_id );
		$url     = $product[ 'imageUrl' ];
		$url     = $url && $this->isExternal( $url ) ? [ $url ] : [];
		
		return $url;
	}
	
	protected function linkGallery( $post_id ) {
		
		$product = $this->getProduct( $post_id );
		$gallery = $product[ 'gallery' ];
		$gallery = @unserialize( $gallery );
		if( ! $gallery && ! is_array( $gallery ) )
			return [];
		
		$foo = [];
		
		foreach( $gallery as $k => $v ) {
			
			if( $this->is_url_img( $v ) && $this->isExternal( $v ) )
				array_push( $foo, $v );
		}
		
		return $foo;
	}
	
	protected function linkVariation( $post_id ) {
		
		$product = $this->getProduct( $post_id );
		$sku     = $product[ 'sku' ];
		$sku     = @unserialize( $sku );
		if( ! $sku && ! is_array( $sku ) )
			return [];
		
		$foo = [];
		
		foreach( $sku as $k => $v ) {
			
			if( $this->is_url_img( $v[ 'img' ] ) && $this->isExternal( $v[ 'img' ] ) )
				array_push( $foo, $v[ 'img' ] );
		}
		
		return $foo;
	}
	
	private function is_url_img( $url ) {
		
		return  preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png|webp)\b/i', $url, $matches );
	}
	
	protected function actions_apply( $post = '' ) {
		
		$post_id = $this->getCurrentPostId();
		$this->upload( $post_id );
		
		return parent::actions_change();
	}
	
	protected function actions_apply_post( $post ) {
		
		$post_id =  (int)$post['args']['post_id'];
		
		$this->upload( $post_id );
		
		return [ 'message' => __( 'Images upload has been completed', 'dm' ) ];
	}
	
	
	public function upload( $post_id ) {
		
		if( ! $post_id ) {
			return false;
		}
		
		$this->uploadSku( $post_id );
		$this->uploadGallery( $post_id );
		$this->uploadImage( $post_id );
	}
	
	/**
	 * @param $post_id
	 *
	 * @return bool
	 */
	public function uploadSku( $post_id ) {
		
		$product = $this->getProduct( $post_id );
		$sku     = $product[ 'sku' ];
		$sku     = @unserialize( $sku );
		
		if( ! $sku && ! is_array( $sku ) )
			return true;
		
		foreach( $sku as $k => $v ) {
			
			$loadImg = $this->attachmentImage( $post_id, $v[ 'img' ] );
			
			if( $loadImg && $loadImg[ 'id' ] ) {
				
				$sku[ $k ][ 'img' ] = $loadImg[ 'id' ];
				
				$this->db->update( $this->db->ads_products_meta,
					[ 'sku'     => serialize( $sku ) ],
					[ 'post_id' => $post_id ]
				);
			}
		}
		
		return true;
	}
	
	/**
	 * @param $post_id
	 *
	 * @return bool
	 */
	public function uploadGallery( $post_id ) {
		
		$product = $this->getProduct( $post_id );
		$gallery = $product[ 'gallery' ];
		$gallery = @unserialize( $gallery );
		
		if( ! $gallery && ! is_array( $gallery ) )
			return true;
		
		foreach( $gallery as $k => $v ) {
			
			$loadImg = $this->attachmentImage( $post_id, $v );
			
			if( $loadImg && $loadImg[ 'id' ] ) {
				
				$gallery[ $k ] = $loadImg[ 'id' ];
				
				$this->db->update( $this->db->ads_products_meta,
					[ 'gallery' => serialize( $gallery ) ],
					[ 'post_id' => $post_id ]
				);
			}
		}
		
		return true;
	}
	
	/**
	 * @param $post_id
	 *
	 * @return bool|false|int
	 */
	public function uploadImage( $post_id ) {
		
		$product = $this->getProduct( $post_id );
		$url     = $product[ 'imageUrl' ];
		$loadImg = $this->attachmentImage( $post_id, $url );
		
		if ( $loadImg && $loadImg[ 'url' ] ) {
			
			set_post_thumbnail( $post_id, $loadImg[ 'id' ] );
			
			return $this->db->update( $this->db->ads_products,
				[ 'imageUrl' => $loadImg[ 'url' ] ],
				[ 'post_id' => $post_id ]
			);
		}
		
		return false;
	}
	
	private function isExternal( $url ) {
		
		return strripos( $url, $_SERVER[ 'SERVER_NAME' ] ) === false;
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
	private function attachmentImage( $post_id, $url, $size = 'full', $name = '' ) {
		
		$img = $this->media->attachmentImage( $post_id, $url, $size , $name );
		
		return $img[ 'id' ] ? $img : false;
	}
}