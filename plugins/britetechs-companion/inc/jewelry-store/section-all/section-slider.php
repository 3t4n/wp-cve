<?php
if ( !function_exists( 'bc_js_slider' ) ) :
	function bc_js_slider(){
		$option = wp_parse_args(  get_option( 'jewelrystore_option', array() ), jewelry_store_reset_data() );

		$_images = $option['slider_images'];

		if (is_string($_images)) {
		    $_images = json_decode($_images, true);
		}

		if ( empty( $_images ) || !is_array( $_images ) ) {
		    $_images = array();
		}

		$slides = array();
		if (!empty($_images) && is_array($_images)) {
		    foreach ($_images as $k => $v) {
		        $slides[] = wp_parse_args($v, array(
		                    'image'=> get_template_directory_uri().'/images/slide1.jpg',
		                    'subtitle'=>'',
		                    'large_text'=>'',
		                    'small_text' => '',
		                    'btn_text' => '',
		                    'btn_link' => '',
		                    'btn_target' => true,
		                    'btn_text2'=> '',
		                    'btn_link2'=> '',
		                    'btn_target2'=> true,
		                    'content_align' => 'left'
		                ));
		    }
		}else{
		    $slides = bc_slider_default_contents();
		}

		$containerClass = '';
		if($option['slider_container_width']!=''){
		    $containerClass = $option['slider_container_width'];
		}

		if( $option['slider_enable'] == true ){
		?>
		<div class="big_banner">
		    <div id="banner_slider" class="owl-carousel owl-theme" data-collg="1" data-colmd="1" data-colsm="1" data-colxs="1" data-itemspace="0" data-loop="true" data-autoplay="true" data-smartspeed="<?php echo esc_attr($option['slider_smart_speed']); ?>" data-nav="<?php echo esc_attr($option['slider_arrow_show']); ?>" data-dots="<?php echo esc_attr($option['slider_pagination_show']); ?>">

		        <?php 
		        foreach( $slides as $slide ){

		        $slide_m = wp_parse_args($slide,array('image'=>''));
		        $imgurl = jewelry_store_get_media_url( $slide_m['image'] ); 
		        ?>
		        <div class="item">
		            <div class="banner">
		                <img src="<?php echo esc_url($imgurl ); ?>" alt="<?php echo esc_attr($slide['large_text']); ?>">
		                <div class="banner_content_area">
		                    <div class="<?php echo esc_attr( $containerClass ); ?>">
		                        <div class="row">
		                        	<div class="col-12">
		                        		<div class="banner_content text-<?php echo esc_attr( $slide['content_align'] ); ?>">

		                        			<?php if( $slide['subtitle'] != '' ){ ?>
				                            <h5 class="banner_subtitle"><?php echo wp_kses_post($slide['subtitle']); ?></h5>
				                            <?php } ?>

				                            <?php if( $slide['large_text'] != '' ){ ?>
				                            <h3 class="banner_title"><?php echo wp_kses_post($slide['large_text']); ?></h3>
				                            <?php } ?>

				                            <?php if( $slide['small_text'] != '' ){ ?>
				                            <p class="banner_desc"><?php echo wp_kses_post($slide['small_text']); ?></p>
				                            <?php } ?>

				                            <?php if( $slide['btn_link'] != '' ){ ?>
				                            <a class="btn btn-primary btn-lg" href="<?php echo esc_url( $slide['btn_link'] ); ?>" <?php if($slide['btn_target']==true){ echo 'target="_blank"';} ?> ><?php echo wp_kses_post($slide['btn_text']); ?> <i class="fa fa-long-arrow-right"></i></a>
				                            <?php } ?>

				                            <?php if( $slide['btn_link2'] != '' && function_exists('is_pro') ){ ?>
				                            <a class="btn btn-outline-light btn-lg" href="<?php echo esc_url( $slide['btn_link2'] ); ?>" <?php if($slide['btn_target2']==true){ echo 'target="_blank"';} ?> ><?php echo wp_kses_post($slide['btn_text2']); ?> <i class="fa fa-long-arrow-right"></i></a>
				                            <?php } ?>
				                        </div>
		                        	</div>
		                        </div>
		                    </div>                            
		                </div>
		            </div>
		        </div>
		        <?php } ?>
		    </div>
		</div><!-- end .big_banner -->
		<?php }
	}
endif;
if ( function_exists( 'bc_js_slider' ) ) {
	$section_priority = apply_filters( 'jewelry_store_section_priority', 1, 'bc_js_slider' );
	add_action( 'jewelry_store_sections', 'bc_js_slider', absint( $section_priority ) );
}