<?php
if ( !function_exists( 'bc_js_testimonial' ) ) :
	function bc_js_testimonial(){
		$option = wp_parse_args(  get_option( 'jewelrystore_option', array() ), jewelry_store_reset_data() );
		$items = $option['testimonial_contents'];

		if(is_string($items)){
		    $items = json_decode($items);
		}

		if ( empty( $items ) || !is_array( $items ) ) {
		    $items = array();
		}

		$testimonials = array();
		if (!empty($items) && is_array($items)) {
			foreach ($items as $k => $v) {
				$testimonials[] = wp_parse_args($v, array(
		                    'image'=> array(
									'url' => plugin_dir_url( __FILE__ ) . 'images/testi'.$k++.'.jpg',
									'id' => ''
								),
							'title'=> 'Title',
							'position'=> 'Manager',
							'desc'=> __('Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet.','britetechs-companion'),
							'link'=> '#',
		                ));
			}
		}else{
			$testimonials = bc_testimonial_default_contents();
		}

		$sectionClass = '';
		if($option['testimonial_bg_image']!=''){
		    $sectionClass = 'has_section_bg_image';
		}

		$sectionClass = trim($sectionClass);

		$containerClass = '';
		if($option['testimonial_container_width']!=''){
		    $containerClass = $option['testimonial_container_width'];
		}

		if($option['testimonial_enable']==true){
		?>
		<div id="testimonial" class="section testimonial_section <?php echo esc_attr( $sectionClass ); ?>">
			<?php if($option['testimonial_bg_image']!=''){ ?>
			 	<div class="section_bg_container">
			      	<img src="<?php echo esc_url($option['testimonial_bg_image']); ?>">
			  	</div>
			<?php } ?>
		    <div class="<?php echo esc_attr( $containerClass ); ?>">
		    		<?php if( $option['testimonial_subtitle'] != '' || $option['testimonial_title'] != '' || $option['testimonial_desc'] != '' ){ ?>
		          	<div class="row">
		              <div class="col-12">
		              	<div class="header_section wow animated fadeInUp">
		            		<div class="header_section_container">
			            		<div class="header_section_details">
			            			<?php if( $option['testimonial_subtitle'] != '' || $option['testimonial_title'] != '' ){ ?>
	                                    <h2 class="section_title_wrap">
	                                        <?php if( $option['testimonial_subtitle'] != '' ){ ?>
	                                        <span class="section_subtitle"><?php echo wp_kses_post($option['testimonial_subtitle']); ?></span>
	                                        <?php } ?>
	                                        <?php if( $option['testimonial_title'] != '' ){ ?>
	                                        <span class="section_title"><?php echo wp_kses_post($option['testimonial_title']); ?></span>
	                                        <?php } ?>
	                                    </h2>
	                                <?php } ?>
	                                <?php if($option['testimonial_desc']!=''){ ?>
	                                    <p class="section_desc"><?php echo wp_kses_post($option['testimonial_desc']); ?></p>
	                                <?php } ?>
			            		</div>		            		
			            	</div>
		            	</div>
		              </div>                    
		          </div>
		          <?php } ?>
		          <div class="row">
		            <div class="col-12">
		                <div id="testimonial_slider" class="owl-carousel owl-theme" data-collg="<?php echo esc_attr( $option['testimonial_column'] ); ?>" data-colmd="3" data-colsm="2" data-colxs="1" data-itemspace="30" data-loop="true" data-autoplay="true" data-smartspeed="800" data-nav="true" data-dots="true">
		                    <?php 
		                    foreach ($testimonials as $testimonial) {
		                    	$testimonial_m = wp_parse_args($testimonial,array('image'=>''));
		                    	$imgurl = jewelry_store_get_media_url( $testimonial_m['image'] , 'thumbnail' ); 
		                    ?>
		                    <div class="item wow animated fadeInUp">
		                      	<div class="testimonial">
			                        <div class="testimonial_container">
		                                <div class="testimonial_content">
		                                  	<div class="testimonial_desc">
		                                  		<?php echo wp_kses_post($testimonial['desc']); ?>
		                                  	</div>
		                                  	<div class="testimonial_footer">
		                                  		<a class="testimonial_image" href="<?php echo esc_url($testimonial['link']); ?>">
					                            	<img class="align-self-start " src="<?php echo esc_url($imgurl); ?>" alt="<?php echo esc_attr($testimonial['title']); ?>">
					                            </a>
					                            <div class="testimonial_details">
					                            	<h5 class="testimonial_title">
					                            		<?php if( function_exists('is_pro') ) { ?>
					                                	<a href="<?php echo esc_url($testimonial['link']); ?>">
					                                	<?php } ?>
					                                		<?php echo esc_html($testimonial['title']); ?>
					                                	<?php if( function_exists('is_pro') ) { ?>
					                                	</a>
					                                	<?php } ?>
					                                </h5>
					                                <span class="testimonial_designation">
					                                	<?php echo esc_html($testimonial['position']); ?>
					                                </span>
					                            </div>
		                                  	</div>
		                                </div>
			                        </div>
		                      	</div>
		                    </div>
		                    <?php } ?>
		              	</div>                            
		            </div>
		        </div>
		    </div><!-- .container -->
		</div>
		<?php }
	}
endif;
if ( function_exists( 'bc_js_testimonial' ) ) {
	$section_priority = apply_filters( 'jewelry_store_section_priority', 4, 'bc_js_testimonial' );
	add_action( 'jewelry_store_sections', 'bc_js_testimonial', absint( $section_priority ) );
}