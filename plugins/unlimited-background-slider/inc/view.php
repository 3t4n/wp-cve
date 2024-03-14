<?php
class ED_BG_SLIDER {
	 /**
     * Default Option key
     * @var string
     */
   // private $url = 'my_options';
	
	
	public function __construct() {
		//$this->url = __( 'Theme Options', 'theme_textdomain' );
		add_action('wp_enqueue_scripts', array( $this, 'hook_javascript_css' ),50);
		add_action('wp_footer', array( $this, 'ed_bg_slider_show' ),52);
		
	}
	
	function hook_javascript_css() {
	    if (!is_admin()) { 
			wp_enqueue_style( 'ed-responsive-background-slider', ED_BG_SLIDE_URL . '/assets/ed-responsive-background-slider.css' );
			wp_enqueue_script('ed-responsive-background', ED_BG_SLIDE_URL . '/assets/jquery.mb.bgndGallery.js' , array('jquery'), '1.0', true);
			wp_enqueue_script('ed-responsive-background');
		    
		}
	}
	function ed_bg_slider_show() {
		global $post;
		$dataid = $post->ID;
	
		wp_reset_postdata();
        wp_reset_query();
		$arg = array(
				'post_type' => array('ed_bg_slider'),
				'posts_per_page' =>5000,
				'order' =>'DESC',
				'suppress_filters' => true
			);
		$the_query = new WP_Query( $arg );
		if ( $the_query->have_posts() ) :
			while ( $the_query->have_posts() ) : $the_query->the_post();
			
				  if( get_post_meta( get_the_ID(),'ed_bg_assign_page_post', true) != "" && get_post_meta( get_the_ID(),'ed_bg_assign_page_post', true) == $dataid){
						$meta_query = array(
							'key'     => 'ed_bg_assign_page_post',
							'value'   => get_post_meta( get_the_ID(),'ed_bg_assign_page_post', true),
							'compare' => '='
						);
						break;
					}elseif( get_post_meta( get_the_ID(),'ed_bg_query_pages', true) != "" && is_page() &&  get_post_meta( get_the_ID(),'ed_bg_query_pages', true) == 'on'){
						$meta_query = array(
							'key'     => 'ed_bg_query_pages',
							'value'   => get_post_meta( get_the_ID(),'ed_bg_query_pages', true),
							'compare' => '='
						);
						break;
						
					}elseif( get_post_meta( get_the_ID(),'ed_bg_query_posts', true) != "" && is_single() &&  get_post_meta( get_the_ID(),'ed_bg_query_posts', true) == 'on'){
						$meta_query = array(
							'key'     => 'ed_bg_query_posts',
							'value'   => get_post_meta( get_the_ID(),'ed_bg_query_posts', true),
							'compare' => '='
						);
						break;
						
					}elseif( get_post_meta( get_the_ID(),'ed_bg_query_home', true) != "" && is_front_page() &&  get_post_meta( get_the_ID(),'ed_bg_query_home', true) == 'on'){
						$meta_query = array(
							'key'     => 'ed_bg_query_home',
							'value'   => get_post_meta( get_the_ID(),'ed_bg_query_home', true),
							'compare' => '='
						);
						break;
						
					}elseif( get_post_meta( get_the_ID(),'ed_bg_query_blog', true)!= "" && is_front_page() &&  get_post_meta( get_the_ID(),'ed_bg_query_blog', true) == 'on'){
						$meta_query = array(
							'key'     => 'ed_bg_query_blog',
							'value'   => get_post_meta( get_the_ID(),'ed_bg_query_blog', true),
							'compare' => '='
						);
						break;
					}elseif( get_post_meta( get_the_ID(),'ed_bg_query_blog', true)!= "" && is_home() &&  get_post_meta( get_the_ID(),'ed_bg_query_blog', true) == 'on'){
						$meta_query = array(
							'key'     => 'ed_bg_query_blog',
							'value'   => get_post_meta( get_the_ID(),'ed_bg_query_blog', true),
							'compare' => '='
						);
						break;
					}elseif( get_post_meta( get_the_ID(),'ed_bg_query_custom_tax', true)!= "" && is_category() &&  get_post_meta( get_the_ID(),'ed_bg_query_custom_tax', true) == 'on'){
						$meta_query = array(
							'key'     => 'ed_bg_query_custom_tax',
							'value'   => get_post_meta( get_the_ID(),'ed_bg_query_custom_tax', true),
							'compare' => '='
						);
						break;
					}
					elseif( get_post_meta( get_the_ID(),'ed_bg_query_custom_post', true)!= "" && is_tax() &&  get_post_meta( get_the_ID(),'ed_bg_query_custom_post', true) == 'on'){
						$meta_query = array(
							'key'     => 'ed_bg_query_custom_post',
							'value'   => get_post_meta( get_the_ID(),'ed_bg_query_custom_post', true),
							'compare' => '='
						);
						break;
					}elseif( get_post_meta( get_the_ID(),'ed_bg_query_tags', true)!= "" && is_tag() &&  get_post_meta( get_the_ID(),'ed_bg_query_tags', true) == 'on'){
						$meta_query = array(
							'key'     => 'ed_bg_query_tags',
							'value'   => get_post_meta( get_the_ID(),'ed_bg_query_tags', true),
							'compare' => '='
						);
						break;
					}elseif( get_post_meta( get_the_ID(),'ed_bg_query_date', true)!= "" && is_date() &&  get_post_meta( get_the_ID(),'ed_bg_query_date', true) == 'on'){
						$meta_query = array(
							'key'     => 'ed_bg_query_date',
							'value'   => get_post_meta( get_the_ID(),'ed_bg_query_date', true),
							'compare' => '='
						);
						break;
					}elseif( get_post_meta( get_the_ID(),'ed_bg_query_auth', true)!= "" && is_author() &&  get_post_meta( get_the_ID(),'ed_bg_query_auth', true) == 'on'){
						$meta_query = array(
							'key'     => 'ed_bg_query_auth',
							'value'   => get_post_meta( get_the_ID(),'ed_bg_query_auth', true),
							'compare' => '='
						);
						break;
					}elseif( get_post_meta( get_the_ID(),'ed_bg_query_search', true)!= "" && is_search() &&  get_post_meta( get_the_ID(),'ed_bg_query_search', true) == 'on'){
						$meta_query = array(
							'key'     => 'ed_bg_query_search',
							'value'   => get_post_meta( get_the_ID(),'ed_bg_query_search', true),
							'compare' => '='
						);
						break;
					}
					
			endwhile;
				wp_reset_postdata(); 
		else:
				wp_reset_query();
				 return false;
		endif;	wp_reset_query();
		
		
		
		if( isset($meta_query) && count($meta_query) > 0 ){
		$args = array(
				'post_type' => array('ed_bg_slider'),
				'posts_per_page' =>1,
				'order' =>'DESC',
				'suppress_filters' => true,
				'meta_query' => array(
					$meta_query
				)
			);
			
		}else{
			return false;
			
		}
		// the query
		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) :
			while ( $the_query->have_posts() ) : $the_query->the_post(); 
			if( get_post_meta( get_the_ID(),'bg_overlay_color', true) != "" && get_post_meta( get_the_ID(),'bg_overlay_opacity', true) != "" ){
				echo '<style>
				.mbBgndGallery div{
					background:' .$this->hex2rgba(get_post_meta( get_the_ID(),'bg_overlay_color', true),get_post_meta( get_the_ID(),'bg_overlay_opacity', true)). ';	
				}
				</style>';
			}
				 $files = get_post_meta( get_the_ID(),'vdw_gallery_id', true);
			
				foreach($files as $img){
					$images = wp_get_attachment_image_src( $img, 'full' );
					if( isset($img_list) ){
						$img_list .= '"'. $images[0] .'",';
					}else{
						$img_list = '"'. $images[0] .'",';
					}
				}
				$ed_id = get_the_ID();
				
				?>
				  
                <?php
				
			endwhile;
			
		?>
       
			 <script type="text/javascript">
                    jQuery(document).ready(function() {
                        jQuery.mbBgndGallery.buildGallery({
                            containment:"body",
							<?php if( trim(get_post_meta( $ed_id,'ed_bg_duration', true)) && is_numeric( trim(get_post_meta( $ed_id,'ed_bg_duration', true)) ) ):?>
                           	timer:<?php echo trim(get_post_meta( $ed_id,'ed_bg_duration', true)); ?>,
							<?php else:?>
							timer: 4000,
							<?php endif;?>
							<?php if( trim(get_post_meta( $ed_id,'ed_bg_transition', true)) && is_numeric( trim(get_post_meta( $ed_id,'ed_bg_transition', true)) ) ):?>
                           	effTimer:<?php echo trim(get_post_meta( $ed_id,'ed_bg_transition', true)); ?>,
							<?php else:?>
							effTimer: 5000,
							<?php endif;?>
                        	 autoStart: <?php echo trim(get_post_meta( $ed_id,'ed_bg_autoplay', true)); ?>,
							<?php if( get_post_meta( $ed_id,'ed_bg_animation', true) ):?>
                           	effect:"<?php echo get_post_meta( $ed_id,'ed_bg_animation', true); ?>",
							<?php endif;?>
							
							<?php if( get_post_meta( $ed_id,'ed_bg_thumbs', true) == 'true' ):?>
							thumbs:{folderPath:"wp-content/uploads/2016/09/", placeholder:"#ed_bg_thumbnails"},
							<?php endif;?>
                            images:[<?php echo $img_list;?>],
							<?php if( get_post_meta( $ed_id,'ed_bg_show_nav', true) == 'true' ):?>
                            controls:"#ed_controls",
							onStart:function(){},
							onPause:function(){},
							onPlay:function(opt){},
							onChange:function(opt,idx){},
							onNext:function(opt){},
							onPrev:function(opt){}
							<?php endif;?> 
							
                        })
                    });
                    </script>
                    
					<?php if( get_post_meta( $ed_id,'ed_bg_thumbs', true) == 'true' ):?>
                         <div id="ed_bg_thumbnails"  class="<?php echo get_post_meta( $ed_id,'ed_bg_thumbs_position', true);?>">
                         </div>
                    <?php endif;?>
                    <?php if( get_post_meta( $ed_id,'ed_bg_show_nav', true) == 'true' ):?>
                    <div id="ed_controls" class="<?php echo get_post_meta( $ed_id,'ed_bg_nav_position', true);?>">
                        <div class="pause">pause</div>
                        <div class="play">play</div>
                        <div class="prev">prev</div>
                        <div class="next">next</div>
                        <div class="counter"></div>
                        <div class="fullscreen">fullscreen</div>
                    </div>
                    <?php endif;?> 
			<?php
				wp_reset_postdata();  
				endif;	wp_reset_query();
            ?>
		<?php 
		}


		
		function hex2rgba($color, $opacity = false) {
			$default = 'rgb(0,0,0)';
		
			//Return default if no color providedli.menu-item-has-children a i.eds-arrows::after, li.menu-item-has-children .eds-arrows-back::after
			if(empty($color))
			  return $default; 
			
			//Sanitize $color if "#" is provided 
			if ($color[0] == '#' ) {
				$color = substr( $color, 1 );
			}
			
			//Check if color has 6 or 3 characters and get values
			if (strlen($color) == 6) {
					$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
			} elseif ( strlen( $color ) == 3 ) {
					$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
			} else {
					return $default;
			}
			
			//Convert hexadec to rgb
			$rgb =  array_map('hexdec', $hex);
			
			//Check if opacity is set(rgba or rgb)
			if($opacity){
				if(abs($opacity) > 1)
					$opacity = 1.0;
				$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
			} else {
				$output = 'rgb('.implode(",",$rgb).')';
			}
			
			//Return rgb(a) color string
			return $output;
		}
}





