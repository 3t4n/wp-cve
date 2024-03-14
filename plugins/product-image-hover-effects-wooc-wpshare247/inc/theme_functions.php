<?php
/**
 * Overide WooCommerce Template
 *
 * @class   Ws247_Piew_Theme
 */
 
if( !class_exists('Ws247_Piew_Theme') ):
	class Ws247_Piew_Theme{
		const SMALL_GALLERY_LIMIT = 4;
		/**
		 * Constructor
		 */
		function __construct() {
			$this->init();
		}
		
		public function init(){
			$my_theme = wp_get_theme();
			$this->theme_name = $my_theme->Name;
			$this->is_flatsome = ( $this->theme_name == 'Flatsome' || $this->theme_name == 'Flatsome Child' ) ? true : false;
			
			$this->effect = Ws247_piew::class_get_option('hover_effect');
			add_action( 'woocommerce_before_shop_loop_item', array($this,'before_product_content'), 1);
			add_action( 'woocommerce_after_shop_loop_item', array($this,'after_product_content'), 11);
			
			if($this->is_flatsome){
				add_action( 'flatsome_woocommerce_shop_loop_images', array($this,'add_img_container'), 9);
				add_action( 'flatsome_woocommerce_shop_loop_images', array($this,'add_hover_img'), 11);
			}else{
				add_action( 'woocommerce_before_shop_loop_item_title', array($this,'add_img_container'), 9);
				add_action( 'woocommerce_before_shop_loop_item_title', array($this,'add_hover_img'), 11);
			}
			$this->special_effects();

			
			add_filter('woocommerce_loop_add_to_cart_args', array($this,'woocommerce_loop_add_to_cart_link'), 9999, 3);
					
			$this->add_to_cart_hover();
		}
		
		public function add_to_cart_hover(){
			$add_to_cart_bg_hover = Ws247_piew::class_get_option('add_to_cart_bg_hover');
			$add_to_cart_color_hover = Ws247_piew::class_get_option('add_to_cart_color_hover');
			if($add_to_cart_bg_hover || $add_to_cart_color_hover){
				add_action('wp_head', array($this, 'wp_head_style'));
			}
		}
		
		public function wp_head_style(){
			$add_to_cart_bg_hover = Ws247_piew::class_get_option('add_to_cart_bg_hover');
			$add_to_cart_color_hover = Ws247_piew::class_get_option('add_to_cart_color_hover');
			?>
            <style>
            	.ws247-piew-hover .ws247_piew_atc:hover{
					<?php 
					if($add_to_cart_bg_hover){
					?>
					background: <?php echo $add_to_cart_bg_hover;?> !important;
					<?php
					}
					?>
					
					<?php 
					if($add_to_cart_color_hover){
					?>
					color: <?php echo $add_to_cart_color_hover;?> !important;
					<?php
					}
					?>
				}
            </style>
            <?php
		}
		
		public function woocommerce_loop_add_to_cart_link($args, $product){ 
			$args['class'] = $args['class'] . ' ws247_piew_atc';
			
			$link_style = '';
			 $add_to_cart_bg = Ws247_piew::class_get_option('add_to_cart_bg');
			 if($add_to_cart_bg){
			 	$link_style .= 'background: '.$add_to_cart_bg.';';
			 }
			 
			 $add_to_cart_radius = Ws247_piew::class_get_option('add_to_cart_radius');
			  if($add_to_cart_radius){
			 	$link_style .= 'border-radius: '.$add_to_cart_radius.'px;';
			 }
			 
			 $add_to_cart_color = Ws247_piew::class_get_option('add_to_cart_color');
			  if($add_to_cart_color){
			 	$link_style .= 'color: '.$add_to_cart_color.';';
			 }
			
			
			if($link_style){
				$attributes = $args['attributes']; 
				if( isset($attributes['style']) ){
					$style = $attributes['style'];
					$attributes['style'] = $style . $link_style;
				}else{
					$attributes['style'] = $link_style;
				}
				$args['attributes'] = $attributes;
				
				$args['class'] = $args['class'] . ' ws247_piew_link_attr';
			}
			
			return $args;
		}
		
		public function get_product(){
			return  wc_get_product();
		}
		
		public function special_effects(){
			$effect = $this->effect;
			switch($effect){
				case 'effect-overflow':
					add_action( 'woocommerce_after_shop_loop_item', array($this,'before_shop_loop_item_l'), 9);
					add_action( 'woocommerce_after_shop_loop_item', array($this,'after_shop_loop_item_l'), 11);
					add_action( 'init', array($this, 'theme_init'), 11 );
				break;
			}
		}
		
		public function theme_init(){
			remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
			remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
			remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
			add_action( 'ws247_piew_effect_overflow', array($this,'wooc_new_data_group'));
			
			add_filter( 'woocommerce_loop_add_to_cart_args', array($this, 'wooc_loop_add_to_cart_args'), 10, 2 );
		}
		
		public function wooc_loop_add_to_cart_args( $args, $product ){
			$atc_bg_color = Ws247_piew::class_get_option('atc_bg_color');
			$atc_color = Ws247_piew::class_get_option('atc_color');
			$atc_border_color = Ws247_piew::class_get_option('atc_border_color');
			$att_style = '';
		
			if($atc_bg_color){
				$att_style .= 'background: '.$atc_bg_color.';';
			}
			
			if($atc_color){
				$att_style .= 'color: '.$atc_color.';';
			}
			
			if($atc_border_color){
				$att_style .= 'boder-color: '.$atc_border_color.';';
			}
			
			if($att_style){
				$attributes = $args['attributes'];
				if(isset($attributes['style'])){
					$n_style = $attributes['style'] . '; '.$att_style;
					$attributes['style'] =  $n_style;
				}else{
					$attributes['style'] = $att_style;
				}
				
				$args['attributes'] = $attributes;
				
			}
			
			return $args;
		}
		
		public function wooc_new_data_group(){
			woocommerce_template_loop_product_title();
			woocommerce_template_loop_rating();
			woocommerce_template_loop_price();
			$this->small_gallery();
		}
		
		public function before_shop_loop_item_l(){
			$effect_bg_color = Ws247_piew::class_get_option('effect_bg_color');
			//$effect_bg_opacity = Ws247_piew::class_get_option('effect_bg_opacity');
			$effect_text_color = Ws247_piew::class_get_option('effect_text_color');
			
			$style = ''; $att_style = '';
			
			if($effect_bg_color){
				$att_style .= 'background: '.$effect_bg_color.';';
			}
			
			if($effect_text_color){
				$att_style .= 'color: '.$effect_text_color.';';
			}
			
			if($att_style){
				$style = 'style="'.$att_style.'"';
			}
			
			if($this->is_flatsome){ $flatsome = 'is-flatsome'; }
			
			echo '<div '.$style.' class="ws247-piew-effect-overflow-container '.$flatsome.'">';
				echo '<div class="ws247-piew-effect-overflow-out">';
				do_action( 'ws247_piew_effect_overflow' );
		}
		
		public function after_shop_loop_item_l(){
				echo '</div>';
			echo '</div>';
		}
		
		public function before_product_content(){
			$product = $this->get_product();
			$effect = $this->effect;
			$product_border = Ws247_piew::class_get_option('product_border');  
			$border = ($product_border=='on') ? 'border' : '';
			
			$style = 'style="';
			$product_border_color =  Ws247_piew::class_get_option('product_border_color'); 
			if($product_border_color){
				$style .= 'border-color:'.$product_border_color.';';
			}
			
			$product_shadow = Ws247_piew::class_get_option('product_shadow'); 
			$shadow = ($product_shadow=='on') ? 'shadow' : '';
			
			$product_border_radius =  (int)Ws247_piew::class_get_option('product_border_radius');
			if($product_border_radius){
				$style .= 'border-radius:'.$product_border_radius.'px;';
			}
			
			$product_pad_bottom =  (int)Ws247_piew::class_get_option('product_pad_bottom');
			if($product_pad_bottom){
				$style .= 'padding-bottom:'.$product_pad_bottom.'px;';
			}
			
			$style .= '"';

			echo '<div '.$style.' id="ws247-piew-product-'.$product->get_id().'" class="'.$shadow.' '.$border.' ws247-piew-hover '.$effect.'">';
		}
		
		public function after_product_content(){
			echo '</div>';
		}
		
		public function add_img_container(){ 
			echo '<div class="ws247-piew-imgs-container">';
			$effect = $this->effect;
			if($effect=='effect-description'){
				echo '<div class="short-description">'.wp_strip_all_tags($this->get_product()->get_short_description( 'view' )).'</div>';
			}
		}
		
		public function add_hover_img(){
			$product = $this->get_product();
			
			$attachment_ids = $product->get_gallery_image_ids();
			if($attachment_ids){
				$post_thumbnail_id = $attachment_ids[0];
			}else{
				$post_thumbnail_id = $product->get_image_id();
			}
			
			$arr_img_src = wp_get_attachment_image_src( $post_thumbnail_id, 'woocommerce_thumbnail', false );
			
			echo '<img src="'.$arr_img_src[0].'" alt="'.$product->get_title().'" class="ws-hover-img" />';
			
			if($this->effect != 'effect-overflow'){ 
				$this->small_gallery();
			}
			
			echo '</div><!-- .ws247-piew-imgs-container -->';
		}
		
		public function small_gallery(){  
			$gallery_show = Ws247_piew::class_get_option('gallery_show');
			if($gallery_show!='on'){ return ''; }
			
			$limit = apply_filters( 'ws247_piew_small_gallery_limit', self::SMALL_GALLERY_LIMIT );
			$product = $this->get_product();
			$attachment_ids = $product->get_gallery_image_ids(); 
			if($attachment_ids){
				$gallery_radius = Ws247_piew::class_get_option('gallery_radius');
				$radius = ($gallery_radius=='on') ? 'radius' : '';
				
				$style = '';
				$gallery_border_color = Ws247_piew::class_get_option('gallery_border_color');
				if($gallery_border_color){
					$style = 'style="border-color:'.$gallery_border_color.';"';
				}
				
				
				$gallery_location = Ws247_piew::class_get_option('gallery_location');
				if($gallery_location){ $gallery_location .= ' has-location'; }
				
				echo '<ul class="ws247-piew-small-gallery '.$gallery_location.' '.$radius.'">';
				foreach($attachment_ids as $i => $thumbnail_id){
					if($i < $limit){
						$arr_img_src = wp_get_attachment_image_src( $thumbnail_id, 'thumbnail', false );
						$arr_img_full = wp_get_attachment_image_src( $thumbnail_id, '', false );
						echo '<li '.$style.' data-fancybox="gallery-'.$product->get_id().'" href="'.$arr_img_full[0].'"><img src="'.$arr_img_src[0].'" alt="'.$product->get_title().'" /></li>';
					}
				}
				echo '</ul>';
			}
		}
	
	//End class------------------------
	}
	
	//Init
	$Ws247_Piew_Theme = new Ws247_Piew_Theme();
	
endif;