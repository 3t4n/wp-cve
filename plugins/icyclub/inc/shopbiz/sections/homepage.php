<?php
/**
 * Slider section
 */
if ( ! function_exists( 'icycp_shopbiz_slider' ) ) :
	function icycp_shopbiz_slider() {
		$slider_image_one = get_theme_mod('slider_image_one',ICYCP_PLUGIN_URL .'inc/shopbiz/images/slider/slider1.jpg');
		$slider_overlay_color_one = get_theme_mod('slider_overlay_color_one', 'rgba(0,0,0,0.8)');
		$shopbiz_slider_title_one = get_theme_mod('shopbiz_slider_title_one','We are Best in Premium Consulting Services');
		$shopbiz_slider_discription_one = get_theme_mod('shopbiz_slider_discription_one','we bring the proper people along to challenge Shopbiz thinking and drive transformation.');
		$shopbiz_slider_btn_txt_one = get_theme_mod('shopbiz_slider_btn_txt_one','Read More');
		$shopbiz_slider_btn_link_one = get_theme_mod('shopbiz_slider_btn_link_one',esc_url('#'));
		$shopbiz_slider_btn_target_one = get_theme_mod('shopbiz_slider_btn_target_one',false);

    $slider_image_two = get_theme_mod('slider_image_two',ICYCP_PLUGIN_URL .'inc/shopbiz/images/slider/slider2.jpg');
    $slider_overlay_color_two = get_theme_mod('slider_overlay_color_two','rgba(0,0,0,0.8)');
    $shopbiz_slider_title_two = get_theme_mod('shopbiz_slider_title_two','We are Best in Premium Consulting Services');
    $shopbiz_slider_discription_two = get_theme_mod('shopbiz_slider_discription_two','we bring the proper people along to challenge Shopbiz thinking and drive transformation.');
    $shopbiz_slider_btn_txt_two = get_theme_mod('shopbiz_slider_btn_txt_two','Read More');
    $shopbiz_slider_btn_link_two = get_theme_mod('shopbiz_slider_btn_link_two',esc_url('#'));
    $shopbiz_slider_btn_target_two = get_theme_mod('shopbiz_slider_btn_target_two',false);

    $slider_image_three = get_theme_mod('slider_image_three',ICYCP_PLUGIN_URL .'inc/shopbiz/images/slider/slider3.jpg');
    $slider_overlay_color_three = get_theme_mod('slider_overlay_color_three', 'rgba(0,0,0,0.8)');
    $shopbiz_slider_title_three = get_theme_mod('shopbiz_slider_title_three','We are Best in Premium Consulting Services');
    $shopbiz_slider_discription_three = get_theme_mod('shopbiz_slider_discription_three','we bring the proper people along to challenge Shopbiz thinking and drive transformation.');
    $shopbiz_slider_btn_txt_three = get_theme_mod('shopbiz_slider_btn_txt_three','Read More');
    $shopbiz_slider_btn_link_three = get_theme_mod('shopbiz_slider_btn_link_three',esc_url('#'));
    $shopbiz_slider_btn_target_three = get_theme_mod('shopbiz_slider_btn_target_three',false);

    
		$shopbiz_slider_enable = get_theme_mod('shopbiz_slider_enable','on');
		if($shopbiz_slider_enable !='off'){	
		?>
	
	<!--== Home Slider ==-->
  <section class="ta-slider-warraper">
    <!--== shopbiz-slider ==-->
    <div id="ta-slider" > 
       <!--item-->
		 <div class="item">
        <!--slide image-->
        <figure> <img src="<?php echo $slider_image_one; ?>" alt="image description"> </figure>
        <!--/slide image-->
        <!--slide inner-->
        <div class="ta-slider-inner" style="background:<?php echo esc_attr($slider_overlay_color_one);?>">
          <div class="container inner-table">
            <div class="inner-table-cell">
              <!--slide content area-->
              <div class="slide-caption">
                <!--slide box style-->
                
                 <?php 
					 if ( ! empty( $shopbiz_slider_title_one ) || is_customize_preview() ) { ?>
					<h1><?php echo $shopbiz_slider_title_one;  ?></h1>
					<?php } 
					if ( ! empty( $shopbiz_slider_discription_one ) || is_customize_preview() ) {
				  ?>
                  <div class="description hidden-xs">
                    <p><?php echo $shopbiz_slider_discription_one; ?></p>
                  </div>
				 <?php } if($shopbiz_slider_btn_txt_one) {?>
                  <a <?php if($shopbiz_slider_btn_link_one) { ?> href="<?php echo $shopbiz_slider_btn_link_one; } ?>" 
						<?php if($shopbiz_slider_btn_target_one) { ?> target="_blank" <?php } ?> class="btn btn-tislider hidden-xs">
						<?php if($shopbiz_slider_btn_txt_one) { echo $shopbiz_slider_btn_txt_one; } ?></a>
				 <?php } ?>	
                  <!--/slide box style-->
              <!--/slide content area-->
            </div>
          </div>
        </div>
        <!--/slide inner-->
      </div>
		
		
		
        <!--/slide inner-->
      </div>
      <!--/item-->

      <!--item-->
     <div class="item">
        <!--slide image-->
        <figure> <img src="<?php echo $slider_image_two; ?>" alt="image description"> </figure>
        <!--/slide image-->
        <!--slide inner-->
        <div class="ta-slider-inner" style="background:<?php echo esc_attr($slider_overlay_color_two);?>">
          <div class="container inner-table">
            <div class="inner-table-cell">
              <!--slide content area-->
              <div class="slide-caption">
                <!--slide box style-->
                
                 <?php 
           if ( ! empty( $shopbiz_slider_title_two ) || is_customize_preview() ) { ?>
          <h1><?php echo $shopbiz_slider_title_two;  ?></h1>
          <?php } 
          if ( ! empty( $shopbiz_slider_discription_two ) || is_customize_preview() ) {
          ?>
                  <div class="description hidden-xs">
                    <p><?php echo $shopbiz_slider_discription_two; ?></p>
                  </div>
         <?php } if($shopbiz_slider_btn_txt_two) {?>
                  <a <?php if($shopbiz_slider_btn_link_two) { ?> href="<?php echo $shopbiz_slider_btn_link_two; } ?>" 
            <?php if($shopbiz_slider_btn_target_two) { ?> target="_blank" <?php } ?> class="btn btn-tislider hidden-xs">
            <?php if($shopbiz_slider_btn_txt_two) { echo $shopbiz_slider_btn_txt_two; } ?></a>
         <?php } ?> 
                  <!--/slide box style-->
              <!--/slide content area-->
            </div>
          </div>
        </div>
        <!--/slide inner-->
      </div>
    
    
    
        <!--/slide inner-->
      </div>
      <!--/item-->

      <!--item-->
     <div class="item">
        <!--slide image-->
        <figure> <img src="<?php echo $slider_image_three; ?>" alt="image description"> </figure>
        <!--/slide image-->
        <!--slide inner-->
        <div class="ta-slider-inner" style="background:<?php echo esc_attr($slider_overlay_color_three);?>">
          <div class="container inner-table">
            <div class="inner-table-cell">
              <!--slide content area-->
              <div class="slide-caption">
                <!--slide box style-->
                
                 <?php 
           if ( ! empty( $shopbiz_slider_title_three ) || is_customize_preview() ) { ?>
          <h1><?php echo $shopbiz_slider_title_three;  ?></h1>
          <?php } 
          if ( ! empty( $shopbiz_slider_discription_three ) || is_customize_preview() ) {
          ?>
                  <div class="description hidden-xs">
                    <p><?php echo $shopbiz_slider_discription_three; ?></p>
                  </div>
         <?php } if($shopbiz_slider_btn_txt_three) {?>
                  <a <?php if($shopbiz_slider_btn_link_three) { ?> href="<?php echo $shopbiz_slider_btn_link_three; } ?>" 
            <?php if($shopbiz_slider_btn_target_three) { ?> target="_blank" <?php } ?> class="btn btn-tislider hidden-xs">
            <?php if($shopbiz_slider_btn_txt_three) { echo $shopbiz_slider_btn_txt_three; } ?></a>
         <?php } ?> 
                  <!--/slide box style-->
              <!--/slide content area-->
            </div>
          </div>
        </div>
        <!--/slide inner-->
      </div>
    
    
    
        <!--/slide inner-->
      </div>
      <!--/item-->


    </div>
    <!--/shopbiz-slider--> 
  </section>
<div class="clearfix"></div>	
	
		<?php
}
	}

endif;

if ( function_exists( 'icycp_shopbiz_slider' ) ) {
$homepage_section_priority = apply_filters( 'icycp_shopbiz_homepage_section_priority', 10, 'icycp_shopbiz_slider' );
add_action( 'icycp_shopbiz_homepage_sections', 'icycp_shopbiz_slider', absint( $homepage_section_priority ) );

}


/*** Service */
if ( ! function_exists( 'icycp_shopbiz_service' ) ) :

	function icycp_shopbiz_service() {

		$service_section_title = get_theme_mod('service_section_title',__('Why We Best in Business Services','icyclub'));
		$service_section_discription = get_theme_mod('service_section_discription','laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.');
		$shopbiz_service_enable = get_theme_mod('shopbiz_service_enable','on');
		if($shopbiz_service_enable !='off')
		{	
		?>
	    <!-- Section Title -->
<section id="service" class="ta-section ta-service text-center">
	<div class="container">		
		<?php if( ($service_section_title) || ($service_section_discription)!='' ) { ?>
		<div class="row">
			<div class="col-md-12 wow fadeInDown animated padding-bottom-20">
            <div class="shopbiz-heading">
              <h3 class="shopbiz-heading-inner"><?php echo $service_section_title; ?></h3>
			  <p><?php echo $service_section_discription; ?></p>
            </div>
          </div>
		</div>
		<!-- /Section Title -->
		<?php } ?>
			<div class="row">
			  <div class="col-sm-4 col-md-4 swing animated service-one">
                <div class="ta-service two text-left">
                  <div class="ta-service-inner">
					<?php  $service_one_icon = get_theme_mod('service_one_icon','fa fa-thumbs-up'); ?>
                    <div class="ser-icon"> <i class="<?php echo  $service_one_icon; ?>"></i> </div>
					 <?php  $service_one_title = get_theme_mod('service_one_title','Market Analysis'); ?>
                    <h3><?php echo $service_one_title; ?></h3>
					<?php  $service_one_description = get_theme_mod('service_one_description','laoreet Pellentesque molestie laoreet laoreet.'); ?>
                    <p><?php echo $service_one_description; ?></p>
                    <?php 
					$ser_one_btn_link = get_theme_mod('ser_one_btn_link','#');
					$ser_one_btn_text = get_theme_mod('ser_one_btn_text',__('Read More','#'));
					$ser_one_btn_tab = get_theme_mod('ser_one_btn_tab',false);
					if($ser_one_btn_text !='')
					{ ?>
					<a href="<?php echo esc_url($ser_one_btn_link); ?>" <?php if($ser_one_btn_tab == true){ echo 'target="_blank"';}?> class="btn btn-theme"><?php echo $ser_one_btn_text ; ?></a>	
					<?php } ?>
					
					
					
					</div>
                </div>
              </div>
			  <div class="col-sm-4 col-md-4 swing animated service-two">
                <div class="ta-service two text-left">
                  <div class="ta-service-inner">
				    <?php  $service_two_icon = get_theme_mod('service_two_icon','fa fa-bank'); ?>
                    <div class="ser-icon"> <i class="<?php echo $service_two_icon; ?>"></i> </div>
                    <?php  $service_two_title = get_theme_mod('service_two_title','Market Analysis'); ?>
					<h3><?php echo $service_two_title; ?></h3>
					<?php  $service_two_description = get_theme_mod('service_two_description','laoreet Pellentesque molestie laoreet laoreet.'); ?>
                     <p><?php echo $service_two_description; ?></p>
                    <?php 
					$ser_two_btn_link = get_theme_mod('ser_two_btn_link','#');
					$ser_two_btn_text = get_theme_mod('ser_two_btn_text',__('Read More','#'));
					$ser_two_btn_tab = get_theme_mod('ser_two_btn_tab',false);
					if($ser_two_btn_text !='')
					{ ?>
					<a href="<?php echo esc_url($ser_two_btn_link); ?>" <?php if($ser_two_btn_tab == true){ echo 'target="_blank"';}?> class="btn btn-theme"><?php echo $ser_two_btn_text ; ?></a>	
					<?php } ?>
					</div>
				</div>
			  </div>
			  <div class="col-sm-4 col-md-4 swing animated service-three">
                <div class="ta-service two text-left">
                  <div class="ta-service-inner">
					<?php  $service_three_icon = get_theme_mod('service_three_icon','fa fa-bank'); ?>
                    <div class="ser-icon"> <i class="<?php echo $service_three_icon; ?>"></i> </div>
                     <?php  $service_three_title = get_theme_mod('service_three_title','Market Analysis'); ?>
					<h3><?php echo $service_three_title; ?></h3>
					<?php  $service_three_description = get_theme_mod('service_three_description','laoreet Pellentesque molestie laoreet laoreet.'); ?>
                     <p><?php echo $service_three_description; ?></p>
                    <?php 
					$ser_three_btn_link = get_theme_mod('ser_three_btn_link','#');
					$ser_three_btn_text = get_theme_mod('ser_three_btn_text',__('Read More','#'));
					$ser_three_btn_tab = get_theme_mod('ser_three_btn_tab',false);
					if($ser_three_btn_text !='')
					{ ?>
					<a href="<?php echo esc_url($ser_three_btn_link); ?>" <?php if($ser_three_btn_tab == true){ echo 'target="_blank"';}?> class="btn btn-theme"><?php echo $ser_three_btn_text ; ?></a>	
					<?php } ?>
                </div>
              </div>
			</div>
	</div>
</section>
<?php		} }

endif;
if ( function_exists( 'icycp_shopbiz_service' ) ) {
	$section_priority = apply_filters( 'icycp_shopbiz_homepage_section_priority', 11, 'icycp_shopbiz_service' );
	add_action( 'icycp_shopbiz_homepage_sections', 'icycp_shopbiz_service', absint( $section_priority ) );
}


/**
 * Callout section
 */
if ( ! function_exists( 'icycp_shopbiz_callout' ) ) :

	function icycp_shopbiz_callout() {
		
		$shopbiz_callout_background = get_theme_mod('shopbiz_callout_background',ICYCP_PLUGIN_URL .'inc/shopbiz/images/callout/callout-back.jpg');
		$shopbiz_overlay_callout_color_control = get_theme_mod('shopbiz_overlay_callout_color_control');
		$shopbiz_callout_title = get_theme_mod('shopbiz_callout_title',__('Trusted By Over 10,000 Worldwide Businesses. Try Today!','shopbiz'));
		$shopbiz_callout_description = get_theme_mod('shopbiz_callout_description','We must explain to you how all this misshopbizken idea of denouncing pleasure');
		$shopbiz_callout_button_one_label = get_theme_mod('shopbiz_callout_button_one_label',__('Get Started Now!','shopbiz'));
		$shopbiz_callout_button_one_link = get_theme_mod('shopbiz_callout_button_one_link','#');
		$shopbiz_callout_button_one_target = get_theme_mod('shopbiz_callout_button_one_target',true);
		$shopbiz_callout_enable = get_theme_mod('shopbiz_callout_enable','on');
		if($shopbiz_callout_enable !='off'){
		if($shopbiz_callout_background != '') { 
		?>		
<section class="ta-callout" style="background-image:url('<?php echo esc_url($shopbiz_callout_background);?>');">
<?php } else { ?>
<section class="ta-callout">
<?php } ?>
<div class="overlay" style="background:<?php echo $shopbiz_overlay_callout_color_control;?>">
      <!--container-->
      <div class="container">
        <!--row-->
        <div class="row">
          <!--shopbiz-callout-inner-->
            <div class="col-md-8  col-md-offset-2 fadeInDown animated">
             <div class="ta-heading"> 
                <h3 class="padding-bottom-30"><?php echo $shopbiz_callout_title;  ?></h3>
             </div>
    	        <p class="padding-bottom-50"><?php echo $shopbiz_callout_description;  ?></p>
            <a href="#"  target="_blank" class="btn btn-theme"><?php echo $shopbiz_callout_button_one_label; ?></a>
            </div>
        </div>
          <!--shopbiz-callout-inner-->
        </div>
        <!--/row-->
</div>
      <!--/conshopbiziner-->
</section>
<!-- /Portfolio Section -->

<div class="clearfix"></div>	
<?php } }

endif;

		if ( function_exists( 'icycp_shopbiz_callout' ) ) {
		$section_priority = apply_filters( 'icycp_shopbiz_homepage_section_priority', 12, 'icycp_shopbiz_callout' );
		add_action( 'icycp_shopbiz_homepage_sections', 'icycp_shopbiz_callout', absint( $section_priority ) );

		}

/**
 * Portfolio
 */
if ( ! function_exists( 'icycp_shopbiz_portfolio' ) ) :

	function icycp_shopbiz_portfolio() {
		
		$portfolio_section_title = get_theme_mod('portfolio_section_title',__('Our Portfolio','icyclub'));
		$portfolio_section_discription = get_theme_mod('portfolio_section_discription','laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.');
		$project_section_enable = get_theme_mod('project_section_enable','on');
		if($project_section_enable !='off'){
		?>		
<!-- Portfolio Section -->
<section class="ta-section ta-portfolio grey-bg no-padding text-center">
    <div class="container">
      <div class="row">
        <?php if( ($portfolio_section_title) || ($portfolio_section_discription)!='' ) { ?>
		<!-- Section Title -->
		<div class="col-md-12 wow fadeInDown animated padding-bottom-20">
            <div class="shopbiz-heading">
              <h3 class="shopbiz-heading-inner"><?php echo $portfolio_section_title; ?></h3>
			  <p><?php echo $portfolio_section_discription; ?></p>
			</div>
            
         </div>
		<!-- /Section Title -->
		<?php } ?>
      </div>
    </div>
    <!--container-->
    <div class="container">
      <!--row-->
      <div class="row">
        <!--portfolio-->
        <div id="portfolio">
        </div>
          <!--item-->
          <?php 
          $portfolio_image_one = get_theme_mod('portfolio_image_one',ICYCP_PLUGIN_URL .'inc/shopbiz/images/portfolio/portfolio1.jpg');
          $portfolio_image_title_one= get_theme_mod('portfolio_image_title_one','Portfolio One');

          $portfolio_image_two = get_theme_mod('portfolio_image_two',ICYCP_PLUGIN_URL .'inc/shopbiz/images/portfolio/portfolio2.jpg');
          $portfolio_image_title_two = get_theme_mod('portfolio_image_title_two','Portfolio Two');

           $portfolio_image_three = get_theme_mod('portfolio_image_three',ICYCP_PLUGIN_URL .'inc/shopbiz/images/portfolio/portfolio3.jpg');
          $portfolio_image_title_three = get_theme_mod('portfolio_image_title_three','Portfolio Three');

          ?>
          <div class="col-md-4 col-sm-6 zoomIn animated">
            <div class="ta-portfolio-box">
              <div class="ta-portfolio-box-caption"> <a class="ta-portfolio-view ta-link-caption" href="#"><i class="fa fa-plus"></i></a>
                <h3><a href="#"> <?php echo $portfolio_image_title_one; ?> </a></h3>
              </div>
              <div class="ta-portfolio-box-cover">
                <img src="<?php echo $portfolio_image_one; ?>" class="img-responsive" alt="">             
              </div>
            </div>
          </div>
          
          <div class="col-md-4 col-sm-6 zoomIn animated">
            <div class="ta-portfolio-box">
              <div class="ta-portfolio-box-caption"> <a class="ta-portfolio-view ta-link-caption" href="#"><i class="fa fa-plus"></i></a>
                <h3><a href="#"> <?php echo $portfolio_image_title_two;  ?> </a></h3>
              </div>
              <div class="ta-portfolio-box-cover">
                <img src="<?php echo $portfolio_image_two; ?>" class="img-responsive" alt="">             
              </div>
            </div>
          </div>
          
          <div class="col-md-4 col-sm-6 zoomIn animated">
            <div class="ta-portfolio-box">
              <div class="ta-portfolio-box-caption"> <a class="ta-portfolio-view ta-link-caption" href="#"><i class="fa fa-plus"></i></a>
                <h3><a href="#"> <?php echo $portfolio_image_title_three; ?> </a></h3>
              </div>
              <div class="ta-portfolio-box-cover">
                <img src="<?php echo $portfolio_image_three; ?>" class="img-responsive" alt="">             
              </div>
            </div>
          </div>
            
            
          <!--/item-->
        </div>
        <!--/portfolio-->
      </div>
      <!--/row-->
    </div>
    <!--/conshopbiziner-->
  </section>
<!-- /Portfolio Section -->

<div class="clearfix"></div>	
<?php } }

endif;

		if ( function_exists( 'icycp_shopbiz_portfolio' ) ) {
		$section_priority = apply_filters( 'icycp_shopbiz_homepage_section_priority', 13, 'icycp_shopbiz_portfolio' );
		add_action( 'icycp_shopbiz_homepage_sections', 'icycp_shopbiz_portfolio', absint( $section_priority ) );

		}
		
//Testimonial
if ( ! function_exists( 'icycp_shopbiz_testimonial' ) ) :

	function icycp_shopbiz_testimonial() {

$testimonial_section_enable = get_theme_mod('testimonial_section_enable','on');
if($testimonial_section_enable !='off')
{
$testimonial_section_title = get_theme_mod('testimonial_section_title','Our Clients Says');
$testimonial_section_discription= get_theme_mod('testimonial_section_discription','laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.')
?>
<section class="testimonials-section">
    <!--overlay-->
    <div class="overlay">
      <!--container-->
      <div class="container">
        <!--row-->
        <div class="row">
          <div class="col-md-12 wow fadeInDown  padding-bottom-20">
            <div class="shopbiz-heading">
              <h3 class="shopbiz-heading-inner"><?php echo $testimonial_section_title; ?></h3>
			  <p><?php echo $testimonial_section_discription; ?></p>
            </div>
          </div>
        </div>
        <!--/row-->
        <!--content-testimonials-->
        <div class="content-testimonials">
          <!--testimonial-->
          <div id="ta-testimonial">
            <!--item-->
           <?php 
				 $testimonial_one_title=  get_theme_mod('testimonial_one_title','Professional Team');
				 $testimonial_one_desc = get_theme_mod('testimonial_one_desc','Vestibulum quis porttitor dui! viverra nunc mi, Aliquam condimentum mattis neque sed pretium Aliquam condimentum mattis neque sed pretiumAliquam condimentum mattis neque sed pretium');
				 $testimonial_one_thumb = get_theme_mod('testimonial_one_thumb',ICYCP_PLUGIN_URL .'inc/shopbiz/images/testimonial/testi1.jpg');
				 $testimonial_one_name = get_theme_mod('testimonial_one_name','Williams Moore');
				 $testimonial_one_designation = get_theme_mod('testimonial_one_designation','Creative Designer');
				 
				 $testimonial_two_title=  get_theme_mod('testimonial_two_title','Professional Team');
				 $testimonial_two_desc = get_theme_mod('testimonial_two_desc','Vestibulum quis porttitor dui! viverra nunc mi, Aliquam condimentum mattis neque sed pretium Aliquam condimentum mattis neque sed pretiumAliquam condimentum mattis neque sed pretium');
				 $testimonial_two_thumb = get_theme_mod('testimonial_two_thumb',ICYCP_PLUGIN_URL .'inc/shopbiz/images/testimonial/testi2.jpg');
				 $testimonial_two_name = get_theme_mod('testimonial_two_name','Williams Moore');
				 $testimonial_two_designation = get_theme_mod('testimonial_two_designation','Creative Designer');
		   ?>
			
              <div class="col-md-6 testimonial-one">
			  <div class="testimonials_qute  bounceInUp animated"> 
                <div class="sub-qute">
                  <div class="icon-quote"><i class="fa fa-quote-left"></i></div>
                  <h5><?php echo $testimonial_one_title ?></h5>
                  <p><?php echo $testimonial_one_desc ?></p>
                  </div>
                  <div class="ta-client-info-row">
                        <div class="ta-client-info">
                          <h6 class="user-title"><?php echo $testimonial_one_name; ?></h6>
                         </div>
                         <span class="ta-client"> <div class="client-image hidden-xs"> <img src="<?php echo $testimonial_one_thumb; ?>" alt="client"></div></span>
                    </div>
                  
                </div>
                
              </div>
              
              <div class="col-md-6 testimonial-two">
			  <div class="testimonials_qute  bounceInUp animated"> 
                <div class="sub-qute">
                  <div class="icon-quote"><i class="fa fa-quote-left"></i></div>
                  <h5><?php echo $testimonial_two_title ?></h5>
                  <p><?php echo $testimonial_two_desc ?></p>
                  </div>
                  <div class="ta-client-info-row">
                        <div class="ta-client-info">
                          <h6 class="user-title"><?php echo $testimonial_two_name; ?></h6>
                         </div>
                         <span class="ta-client"> <div class="client-image hidden-xs"> <img src="<?php echo $testimonial_two_thumb; ?>" alt="client"></div></span>
                    </div>
                </div>
              </div>
			  </div>
           
			 
          <!--/testimonial-->
        </div>
        <!--/content-testimonials-->
      </div>
      <!--/conshopbiziner-->
    </div>
    <!--/overlay-->
  </section>

		<?php
	} }

endif;

		if ( function_exists( 'icycp_shopbiz_testimonial' ) ) {
		$section_priority = apply_filters( 'icycp_shopbiz_homepage_section_priority', 14, 'icycp_shopbiz_testimonial' );
		add_action( 'icycp_shopbiz_homepage_sections', 'icycp_shopbiz_testimonial', absint( $section_priority ) );
}