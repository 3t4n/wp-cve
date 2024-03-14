<?php
namespace BDroppy\Tools\ImageUrl\Includes;

use BDroppy\Init\Core;

if ( ! defined( 'ABSPATH' ) ) exit;


class Admin {

	public $image_meta_url = '_bdroppy_url';
	public $image_meta_alt = '_bdroppy_alt';

	public function __construct(Core $core) {
		if ( is_admin() ){

		    $this->core = $core;
		    $this->loader = $core->getLoader();


		    $this->loader->addAction('add_meta_boxes',$this,'add_metabox',10, 2);
		    $this->loader->addAction('save_post',$this,'save_image_url_data',10, 2);
		    $this->loader->addAction('admin_enqueue_scripts',$this,'enqueue_admin_styles');
		    $this->loader->addAction('admin_enqueue_scripts',$this,'enqueue_admin_scripts');
//		    $this->loader->addAction('woocommerce_product_after_variable_attributes',$this,'add_product_variation_image_selector');
//	        $this->loader->addAction('woocommerce_save_product_variation',$this,'save_product_variation_image');
//          $this->loader->addFilter('manage_edit-product_columns',$this,'editProductColumns');

		}
	}

    public function editProductColumns($columns)
    {
        $thumb = $columns['thumb'];
        unset($columns['thumb']);
        return array_slice($columns, 0, 1, true) +
            ["thumb2" => $thumb] +
            array_slice($columns, 1, count($columns) - 1, true) ;
    }

    public function productPostsCustomColumn($column)
    {
        if ( 'thumb2' === $column )
        {
            global $post;
            $image_meta = $this->get_image_meta(  $post->ID );
            echo '<img width="40" height="40" src="'.$image_meta['img_url'].'" >';
        }
    }


	public function add_metabox( $post_type, $post ) {

		if( $post_type !== 'product' ){
			return;
		}

		add_meta_box( 'bdroppy_metabox',
						__('BDroppy Image URL', 'bdroppy-image-url' ),
						array( $this, 'render_metabox' ),
                        'product',
						'side',
						'low'
					);

		add_meta_box( 'bdroppy_wcgallary_metabox',
						__('BDroppy Product gallery by URLs', 'bdroppy-image-url' ),
						array( $this, 'render_wcgallary_metabox' ),
						'product',
						'side',
						'low'
					);

	}

	public function render_metabox($post )
    {
		$image_meta = $this->get_image_meta(  $post->ID );
		include BDROPPY_PATH .'src/Tools/ImageUrl/templates/metabox.php';
	}

	public function render_wcgallary_metabox(  $post )
    {
        $gallary_images = $this->get_wcgallary_meta($post->ID);
		include BDROPPY_PATH .'src/Tools/ImageUrl/templates/wcgallary-metabox.php';
	}

	public function get_wcgallary_meta($post_id)
    {
        $image_meta  = array();

        $gallary_images = get_post_meta( $post_id, "_bdroppy_wcgallary", true );

        if( !is_array( $gallary_images ) && $gallary_images != '' ){
            $gallary_images = explode( ',', $gallary_images );
            if( !empty( $gallary_images ) ){
                $gallarys = array();
                foreach ($gallary_images as $gallary_image ) {
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

	public function enqueue_admin_styles( $hook ) {
		
		$css_dir = BDROPPY_URL . 'src/Tools/ImageUrl/assets/css/';
	 	wp_enqueue_style('bdroppy-admin', $css_dir . 'admin.css', false, "" );
		
	}

	public function enqueue_admin_scripts( $hook ) {

		$js_dir  = BDROPPY_URL . 'src/Tools/ImageUrl/assets/js/';
		wp_register_script( 'bdroppy-admin', $js_dir . 'admin.js', array('jquery' ) );
		$strings = array(
			'invalid_image_url' => __('Error in Image URL', 'bdroppy-image-by-url'),
		);
		wp_localize_script( 'bdroppy-admin', 'bdroppy', $strings );
		wp_enqueue_script( 'bdroppy-admin' );

	}

	public function save_image_url_data( $post_id, $post )
    {
		$cap = $post->post_type === 'page' ? 'edit_page' : 'edit_post';
		if ( ! current_user_can( $cap, $post_id ) || ! post_type_supports( $post->post_type, 'thumbnail' ) || defined( 'DOING_AUTOSAVE' ) ) {
			return;
		}

		if( isset( $_POST['bdroppy_url'] ) ){
			// Update Featured Image URL
			$image_url = isset( $_POST['bdroppy_url'] ) ? esc_url( $_POST['bdroppy_url'] ) : '';
			$image_alt = isset( $_POST['bdroppy_alt'] ) ? wp_strip_all_tags( $_POST['bdroppy_alt'] ): '';

			if ( $image_url != '' )
			{
				if( get_post_type( $post_id ) == 'product' )
				{
					$img_url = get_post_meta( $post_id, $this->image_meta_url , true );
					if( is_array( $img_url ) && isset( $img_url['img_url'] ) && $image_url == $img_url['img_url'] ){
							$image_url = array(
								'img_url' => $image_url,
								'width'	  => $img_url['width'],
								'height'  => $img_url['height']
							);
					}else{
						$imagesize = @getimagesize( $image_url );
						$image_url = array(
							'img_url' => $image_url,
							'width'	  => isset( $imagesize[0] ) ? $imagesize[0] : '',
							'height'  => isset( $imagesize[1] ) ? $imagesize[1] : ''
						);
					}
				}

				update_post_meta( $post_id, $this->image_meta_url, $image_url );
				if( $image_alt ){
					update_post_meta( $post_id, $this->image_meta_alt, $image_alt );
				}
			}else{
				delete_post_meta( $post_id, $this->image_meta_url );
				delete_post_meta( $post_id, $this->image_meta_alt );
			}
		}

		if( isset( $_POST['bdroppy_wcgallary'] ) ){
			// Update WC Gallery
			$bdroppy_wcgallary = isset( $_POST['bdroppy_wcgallary'] ) ? (array) $_POST['bdroppy_wcgallary'] : array();
            array_map( 'esc_attr', $bdroppy_wcgallary );

			if( empty( $bdroppy_wcgallary ) || $post->post_type != 'product' )
			{
				return;
			}

			$old_images = $this->get_wcgallary_meta( $post_id );
			if( !empty( $old_images ) ){
				foreach ($old_images as $key => $value) {
					$old_images[$value['url']] = $value;
				}
			}

			$gallary_images = array();
			if( !empty( $bdroppy_wcgallary ) )
			{
				foreach ($bdroppy_wcgallary as $bdroppy_gallary )
				{
					if( isset( $bdroppy_gallary['url'] ) && $bdroppy_gallary['url'] != '' )
					{
						$gallary_image = array();
						$gallary_image['url'] = $bdroppy_gallary['url'];

						if( isset( $old_images[$gallary_image['url']]['width'] ) && $old_images[$gallary_image['url']]['width'] != '' )
						{
							$gallary_image['width'] = isset( $old_images[$gallary_image['url']]['width'] ) ? $old_images[$gallary_image['url']]['width'] : '';
							$gallary_image['height'] = isset( $old_images[$gallary_image['url']]['height'] ) ? $old_images[$gallary_image['url']]['height'] : '';

						}else{
							$imagesizes = @getimagesize( $bdroppy_gallary['url'] );
							$gallary_image['width'] = isset( $imagesizes[0] ) ? $imagesizes[0] : '';
							$gallary_image['height'] = isset( $imagesizes[1] ) ? $imagesizes[1] : '';
						}

						$gallary_images[] = $gallary_image;
					}
				}
			}

			if( !empty( $gallary_images ) )
			{
				update_post_meta( $post_id, "_bdroppy_wcgallary", $gallary_images );
			}else{
				delete_post_meta( $post_id, "_bdroppy_wcgallary" );
			}
		}
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

	public function section_callback( $args ) {
		// Do some HTML here.
	}

	public function add_product_variation_image_selector( $loop, $variation_data, $variation ){
		$bdroppy_url = '';
		if( isset( $variation_data['_bdroppy_url'][0] ) ){
			$bdroppy_url = $variation_data['_bdroppy_url'][0];
			$bdroppy_url = maybe_unserialize( $bdroppy_url );
			if( is_array( $bdroppy_url ) ){
				$bdroppy_url = $bdroppy_url['img_url'];
			}
		}
		?>
		<div id="bdroppy_product_variation_<?php echo $variation->ID; ?>" class="bdroppy_product_variation form-row form-row-first">
			<label for="bdroppy_pvar_url_<?php echo $variation->ID; ?>">
				<strong><?php _e('Product Variation Image by URL', 'featured-image-by-url') ?></strong>
			</label>

			<div id="bdroppy_pvar_img_wrap_<?php echo $variation->ID; ?>" class="bdroppy_pvar_img_wrap" style="<?php if( $bdroppy_url == '' ){ echo 'display:none'; } ?>" >
				<span href="#" class="bdroppy_pvar_remove" data-id="<?php echo $variation->ID; ?>"></span>
				<img id="bdroppy_pvar_img_<?php echo $variation->ID; ?>" class="bdroppy_pvar_img" data-id="<?php echo $variation->ID; ?>" src="<?php echo $bdroppy_url; ?>" />
			</div>
			<div id="bdroppy_url_wrap_<?php echo $variation->ID; ?>" style="<?php if( $bdroppy_url != '' ){ echo 'display:none'; } ?>" >
				<input id="bdroppy_pvar_url_<?php echo $variation->ID; ?>" class="bdroppy_pvar_url" type="text" name="bdroppy_pvar_url[<?php echo $variation->ID; ?>]" placeholder="<?php _e('Product Variation Image URL', 'featured-image-by-url'); ?>" value="<?php echo $bdroppy_url; ?>"/>
				<a id="bdroppy_pvar_preview_<?php echo $variation->ID; ?>" class="bdroppy_pvar_preview button" data-id="<?php echo $variation->ID; ?>">
					<?php _e( 'Preview', 'featured-image-by-url' ); ?>
				</a>
			</div>
		</div>
		<?php
	}

	public function save_product_variation_image( $variation_id, $i ){

		$image_url = isset( $_POST['bdroppy_pvar_url'][$variation_id] ) ? esc_url( $_POST['bdroppy_pvar_url'][$variation_id] ) : '';
		if( $image_url != '' ){
			$img_url = get_post_meta( $variation_id, $this->image_meta_url , true );
			if( is_array( $img_url ) && isset( $img_url['img_url'] ) && $image_url == $img_url['img_url'] ){
					$image_url = array(
						'img_url' => $image_url,
						'width'	  => $img_url['width'],
						'height'  => $img_url['height']
					);
			}else{
				$imagesize = @getimagesize( $image_url );
				$image_url = array(
					'img_url' => $image_url,
					'width'	  => isset( $imagesize[0] ) ? $imagesize[0] : '',
					'height'  => isset( $imagesize[1] ) ? $imagesize[1] : ''
				);
			}
			update_post_meta( $variation_id, $this->image_meta_url, $image_url );
		}else{
			delete_post_meta( $variation_id, $this->image_meta_url );
		}
	}

}