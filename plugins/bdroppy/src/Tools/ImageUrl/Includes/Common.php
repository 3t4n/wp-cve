<?php
namespace BDroppy\Tools\ImageUrl\Includes;

use BDroppy\Init\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

class Common {

    public $image_meta_url = '_bdroppy_url';
    public $image_meta_alt = '_bdroppy_alt';

	public function __construct(Core $core)
    {
        $this->core = $core;
        $this->loader = $core->getLoader();

        $this->loader->addFilter('get_post_metadata',$this,'set_thumbnail_true',10, 4 );
        $this->loader->addFilter('get_page_metadata',$this,'set_thumbnail_true',10, 4 );
        $this->loader->addFilter('get_product_metadata',$this,'set_thumbnail_true',10, 4 );
        $this->loader->addAction('post_thumbnail_html',$this,'overwrite_thumbnail_with_url',999, 5 );
        $this->loader->addAction('woocommerce_structured_data_product',$this,'woo_structured_data_product_support',99, 2 );
        $this->loader->addAction('facebook_for_woocommerce_integration_prepare_product',$this,'facebook_for_woocommerce_support',99, 2 );



		if( !is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) )
		{
            $this->loader->addAction('wp_get_attachment_image_src',$this,'replace_attachment_image_src',10,4);
            $this->loader->addAction('woocommerce_product_get_gallery_image_ids',$this,'set_customized_gallary_ids',99,2);
		}

        $this->loader->addAction('admin_init',$this,'woo_thumb_support');




        $this->loader->addAction('woocommerce_product_get_image_id',$this,'woocommerce_36_support',99,2);

//        $this->loader->addFilter('has_post_thumbnail',$this,'handle_has_post_thumbnail',999,4);


	}

    public function handle_has_post_thumbnail()
    {
        global $post;
        return (bool)$this->get_image_meta($post->ID)['img_url'];

	}
	public function woocommerce_36_support( $value, $product){
		$product_id = $product->get_id();

		if(!empty($product_id))
		{
			$post_type = get_post_type( $product_id );
			$image_data = $this->get_image_meta( $product_id );
			if ( isset( $image_data['img_url'] ) && $image_data['img_url'] != '' ){
				return  $product_id;
			}
		}
		return $value;
	}



    public function get_image_meta( $post_id, $is_single_page = false ){

        $image_meta  = array();

        $img_url = get_post_meta( $post_id, $this->image_meta_url, true );

        $img_alt = get_post_meta( $post_id, $this->image_meta_alt, true );
        if( is_array( $img_url ) && isset( $img_url['img_url'] ) ){
            $image_meta['img_url'] 	 = $img_url['img_url'];
        }else{
            $image_meta['img_url'] 	 = $img_url;
        }
        $image_meta['img_alt'] 	 = $img_alt;
        if( ( 'product_variation' == get_post_type( $post_id ) || 'product' == get_post_type( $post_id ) ) && $is_single_page ){
            if( isset( $img_url['width'] ) ){
                $image_meta['width'] 	 = $img_url['width'];
                $image_meta['height'] 	 = $img_url['height'];
            }else{

                if( isset( $image_meta['img_url'] ) && $image_meta['img_url'] != '' ){
                    $imagesize = @getimagesize( $image_meta['img_url'] );
                    $image_url = array(
                        'img_url' => $image_meta['img_url'],
                        'width'	  => isset( $imagesize[0] ) ? $imagesize[0] : '',
                        'height'  => isset( $imagesize[1] ) ? $imagesize[1] : ''
                    );
                    update_post_meta( $post_id, $this->image_meta_url, $image_url );
                    $image_meta = $image_url;
                }
            }
        }
        return $image_meta;
    }

	public function set_thumbnail_true( $value, $object_id, $meta_key, $single )
    {
		if ( $meta_key == '_thumbnail_id' )
		{
            $post_type = get_post_type( $object_id );
			$image_data = $this->get_image_meta( $object_id );
			if ( isset( $image_data['img_url'] ) && $image_data['img_url'] != '' )
			{
				if( $post_type == 'product_variation' )
				{
					if( !is_admin() ){
						return $object_id;
					}else{
						return $value;
					}
				}
				return $object_id;
			}
		}
		return $value;
	}

	public function overwrite_thumbnail_with_url( $html, $post_id, $post_image_id, $size, $attr )
    {
		if( is_singular( 'product' ) && ( 'product' == get_post_type( $post_id ) || 'product_variation' == get_post_type( $post_id ) ) ){
			return $html;
		}
		
		$image_data = $this->get_image_meta( $post_id );
		
		if( !empty( $image_data['img_url'] ) )
		{
			$image_url 		= $image_data['img_url'];
			$image_alt	= ( $image_data['img_alt'] ) ? 'alt="'.$image_data['img_alt'].'"' : '';
			$classes 	= 'external-img wp-post-image ';
			$classes   .= ( isset($attr['class']) ) ? $attr['class'] : '';
			$style 		= ( isset($attr['style']) ) ? 'style="'.$attr['style'].'"' : '';

			$html = sprintf('<img src="%s" %s class="%s" %s />', 
							$image_url, $image_alt, $classes, $style);
		}
		return $html;
	}

	public function get_image_sizes()
    {
		global $_wp_additional_image_sizes;

		$sizes = array();

		foreach ( get_intermediate_image_sizes() as $_size )
		{
			if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) )
			{
				$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
				$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
				$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
			}elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) )
            {
				$sizes[ $_size ] = array(
					'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
					'height' => $_wp_additional_image_sizes[ $_size ]['height'],
					'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
				);
			}
		}

		return $sizes;
	}

	public function get_wcgallary_meta( $post_id )
    {
		$gallary_images = get_post_meta( $post_id, "_bdroppy_wcgallary", true );
	
		if( !is_array( $gallary_images ) && $gallary_images != '' )
		{
			$gallary_images = explode( ',', $gallary_images );
			if( !empty( $gallary_images ) )
			{
				$gallarys = array();
				foreach ($gallary_images as $gallary_image )
				{
					$gallary = array();
					$gallary['url'] = $gallary_image;
					$imagesizes = @getimagesize( $gallary_image );
					$gallary['width'] = isset( $imagesizes[0] ) ? $imagesizes[0] : '';
					$gallary['height'] = isset( $imagesizes[1] ) ? $imagesizes[1] : '';
					$gallarys[] = $gallary;
				}
				$gallary_images = $gallarys;
				update_post_meta( $post_id, "_bdroppy_wcgallary", $gallary_images );
				return $gallary_images;
			}
		}else{
			if( !empty( $gallary_images ) ){
				$need_update = false;
				foreach ($gallary_images as $key => $gallary_image ) {
					if( !isset( $gallary_image['width'] ) && isset( $gallary_image['url'] ) ){
						$imagesizes1 = @getimagesize( $gallary_image['url'] );
						$gallary_images[$key]['width'] = isset( $imagesizes1[0] ) ? $imagesizes1[0] : '';
						$gallary_images[$key]['height'] = isset( $imagesizes1[1] ) ? $imagesizes1[1] : '';
						$need_update = true;
					}
				}
				if( $need_update ){
					update_post_meta( $post_id, "_bdroppy_wcgallary", $gallary_images );
				}
				return $gallary_images;
			}	
		}
		
		
		return $gallary_images;
	}

	public function set_customized_gallary_ids( $value, $product )
    {
		$product_id = $product->get_id();
		if( empty( $product_id ) )
		{
			return $value;
		}
        $gallery_ids = [];
		$gallery_images = $this->get_wcgallary_meta( $product_id );
		if( !empty( $gallery_images ) ){
			$i = 0;
			foreach ( $gallery_images as $gallery_image )
			{
			    if($i==0) {
                    $i++;
			        continue;
			    }
				$gallery_ids[] = '_bdroppy_wcgallary__'.$i.'__'.$product_id;
				$i++;
			}
			return $gallery_ids;
		}
		return $value;
	}

	public function replace_attachment_image_src( $image, $attachment_id, $size, $icon )
    {
		if( false !== strpos( $attachment_id, '_bdroppy_wcgallary' ) ){
			$attachment = explode( '__', $attachment_id );
			$image_num  = $attachment[1];
			$product_id = $attachment[2];
			if( $product_id > 0 )
			{
				$gallery_images = $this->get_wcgallary_meta( $product_id );
				if( !empty( $gallery_images ) ){
					if( !isset( $gallery_images[$image_num]['url'] ) ){
						return false;
					}
					$url = $gallery_images[$image_num]['url'];

					$image_size = $this->get_image_size( $size );
					if ($url)
					{
						if( $image_size )
						{
							if( !isset( $image_size['crop'] ) )
							{
								$image_size['crop'] = '';
							}
							return array(
										$url,
										$image_size['width'],
										$image_size['height'],
										$image_size['crop'],
								);
						}else{
							if( $gallery_images[$image_num]['width'] != '' && $gallery_images[$image_num]['width'] > 0 )
							{
								return array( $url, $gallery_images[$image_num]['width'], $gallery_images[$image_num]['height'], false );
							}else{
								return array( $url, 800, 600, false );
							}
						}
					}
				}
			}
		}

        $image_data = $this->get_image_meta( $attachment_id, true );

        if( !empty( $image_data['img_url'] ) ){

            $image_url = $image_data['img_url'];
            $width = isset( $image_data['width'] ) ? $image_data['width'] : '';
            $height = isset( $image_data['height'] ) ? $image_data['height'] : '';


            $image_size = $this->get_image_size( $size );
            if ($image_url) {
                if( $image_size ){
                    if( !isset( $image_size['crop'] ) ){
                        $image_size['crop'] = '';
                    }
                    return array(
                        $image_url,
                        $image_size['width'],
                        $image_size['height'],
                        $image_size['crop'],
                    );
                }else{
                    if( $width != '' && $height != '' ){
                        return array( $image_url, $width, $height, false );
                    }
                    return array( $image_url, 800, 600, false );
                }
            }
        }

		return $image;
	}

	public function get_image_size( $size )
    {
		$sizes = $this->get_image_sizes();

		if( is_array( $size ) )
		{
			$woo_size = array();
			$woo_size['width'] = $size[0];
			$woo_size['height'] = $size[1];
			return $woo_size;
		}
		if ( isset( $sizes[ $size ] ) ) {
			return $sizes[ $size ];
		}

		return false;
	}


	public function woo_thumb_support() {
		global $pagenow;
		if( 'edit.php' === $pagenow )
		{
			global $typenow;
			if( 'product' === $typenow && isset( $_GET['post_type'] ) && 'product' === sanitize_text_field( $_GET['post_type'] ) ){
                add_filter('wp_get_attachment_image_src',[$this,'replace_attachment_image_src'],10,4);
			}
		}
	}

	public function woo_structured_data_product_support( $markup, $product )
    {
		if ( isset($markup['image']) && empty($markup['image']) )
		{
			$product_id = $product->get_id();
			if( $product_id > 0 )
			{
				$image_data = $this->get_image_meta( $product_id );
				if( !empty($image_data) && isset($image_data['img_url']) && !empty($image_data['img_url']) )
				{
					$markup['image'] = $image_data['img_url'];
				}
			}
		}
		return $markup;
	}

	public function facebook_for_woocommerce_support( $product_data, $product_id )
    {
		if( empty( $product_data ) || empty( $product_id ) )
		{
			return $product_data;
		}

		$product_image = $this->get_image_meta( $product_id );
		if( isset( $product_image['img_url'] ) && !empty( $product_image['img_url'] ) )
		{
			$product_data['image_url'] = $product_image['img_url'];
			$image_override = get_post_meta($product_id, 'fb_product_image', true);
			if ( !empty($image_override ) )
			{
				$product_data['image_url'] = $image_override;
			}
		}
		$product_gallery_images = $this->get_wcgallary_meta( $product_id );
		if( !empty( $product_gallery_images ) )
		{
			$gallery_images = array();
			foreach ($product_gallery_images as $wc_gimage)
			{
				if( isset( $wc_gimage['url'] ) ){
					$gallery_images[] = $wc_gimage['url'];
				}
			}
			if( !empty( $gallery_images ) )
			{
				$product_data['additional_image_urls'] = $gallery_images;
			}
		}

		return $product_data;
	}
}
