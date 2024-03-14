<?php /**
 * Slider section
 */
if ( ! function_exists( 'icycp_industryup_slider' ) ) :
	function icycp_industryup_slider() {
		$slider_image = get_theme_mod('slider_image',ICYCP_PLUGIN_URL .'inc/industryup/images/slider/banner.jpg');
		$slider_overlay_section_color = get_theme_mod('slider_overlay_section_color');
		$slider_title = get_theme_mod('slider_title','We are');
		$slider_subtitle = get_theme_mod('slider_subtitle','We Are Industrial Manufacturer');
		$slider_discription = get_theme_mod('slider_discription','One morning, when Gregor Samsa woke from troubled dreams, he found himself transformed in his bed into a horrible vermin..');
		$slider_btn_txt = get_theme_mod('slider_btn_txt','Read More');
		$slider_btn_link = get_theme_mod('slider_btn_link',esc_url('#'));
		$slider_btn_target = get_theme_mod('slider_btn_target',false);
		$home_page_slider_enabled         = get_theme_mod('home_page_slider_enabled','1');
    if($home_page_slider_enabled == '1') {
		?>
	
  <!--== Home Slider ==-->
  <section class="bs-slider-warraper" id="slider-section">
  	 <div id="bs-slider" class="bs-slider" > 
    <!--== consultup-slider ==-->
    <div class="bs-slide" style="background-image: url(<?php echo $slider_image; ?>);"> 
       <!--item-->
        <!--slide inner-->
        <div class="container">
        	<div class="row justify-content-center justify-content-md-start">
		        <div class="col-10 col-md-7 static">
	              <!--slide content area-->
	              <div class="slide-caption">
	              <?php if ( ! empty( $slider_title ) || is_customize_preview() ) { ?>
	               <h6 class="slide-subtitle"> <?php echo $slider_title;  ?> </h6>
                 <?php  } 
					 if ( ! empty( $slider_subtitle ) || is_customize_preview() ) { ?>
					<h2 class="slide-title"><?php echo $slider_subtitle;  ?></h2>
					<?php } 
					if ( ! empty( $slider_discription ) || is_customize_preview() ) {
				  ?>
                  <div class="description mb-3">
                    <p><?php echo $slider_discription; ?></p>
                  </div>
				 <?php } if($slider_btn_txt) {?>
                  <div class="slider-btns"><a <?php if($slider_btn_link) { ?> href="<?php echo $slider_btn_link; } ?>" 
					 <?php if($slider_btn_target) { ?> target="_blank" <?php } ?> class="btn btn-0">
					 <?php if($slider_btn_txt) { echo $slider_btn_txt; } ?>
				 				 	</a>
								</div>
				 <?php } ?>	
                  <!--/slide box style-->
				</div>
              <!--/slide content area-->
            </div>
          </div>
        </div>
        <!--/slide inner-->
      </div>
      </div>
    <!--/consultup-slider--> 
  </section>
<div class="clearfix"></div>	
	
<?php
}
	}

endif;

if ( function_exists( 'icycp_industryup_slider' ) ) {
$homepage_section_priority = apply_filters( 'icycp_industryup_homepage_section_priority', 10, 'icycp_industryup_slider' );
add_action( 'icycp_industryup_homepage_sections', 'icycp_industryup_slider', absint( $homepage_section_priority ) );
}