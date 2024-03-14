<?php
/**
 * Slider section
 */
if ( ! function_exists( 'icycp_agencyup_slider' ) ) :
	function icycp_agencyup_slider() {
		$slider_image = get_theme_mod('slider_image',ICYCP_PLUGIN_URL .'inc/agencyup/images/slider/banner.jpg');
		$slider_overlay_section_color = get_theme_mod('slider_overlay_section_color');
		$slider_title = get_theme_mod('slider_title','We are Best in Consulting Services');
		
		$slider_discription = get_theme_mod('slider_discription','we bring the proper people along to challenge esconsultupblished thinking and drive transformation.');
		$slider_btn_txt = get_theme_mod('slider_btn_txt','Read More');
		$slider_btn_link = get_theme_mod('slider_btn_link',esc_url('#'));
		$slider_align = get_theme_mod('agencyup_slider_align','start');
		$slider_btn_target = get_theme_mod('slider_btn_target',false);
    $home_page_slider_enabled         = get_theme_mod('home_page_slider_enabled','1');
    if($home_page_slider_enabled == '1') {
		?>
	
  <!--== Home Slider ==-->
  <section class="bs-slider-warraper" id="slider-section">
  	 <div id="bs-slider"> 
    <!--== consultup-slider ==-->
    <div class="bs-slide" style="background-image: url(<?php echo $slider_image; ?>);"> 
       <!--item-->
        <!--slide inner-->
        <div class="container">
        	<div class="row justify-content-<?php echo $slider_align; ?> text-<?php echo $slider_align; ?>">
		        <div class="col-10 col-md-7 static">
	              <!--slide content area-->
	              <div class="slide-caption">
                 <?php 
					 if ( ! empty( $slider_title ) || is_customize_preview() ) { ?>
					<h2 class="slide-title"><?php echo $slider_title;  ?></h2>
					<?php } 
					if ( ! empty( $slider_discription ) || is_customize_preview() ) {
				  ?>
                  <div class="description mb-3">
                    <p><?php echo $slider_discription; ?></p>
                  </div>
				 <?php } if($slider_btn_txt) {?>
                  <a <?php if($slider_btn_link) { ?> href="<?php echo $slider_btn_link; } ?>" 
					 <?php if($slider_btn_target) { ?> target="_blank" <?php } ?> class="btn btn-tislider">
					 <?php if($slider_btn_txt) { echo $slider_btn_txt; } ?>
				  </a>
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

if ( function_exists( 'icycp_agencyup_slider' ) ) {
$homepage_section_priority = apply_filters( 'icycp_consultup_homepage_section_priority', 10, 'icycp_agencyup_slider' );
add_action( 'icycp_consultup_homepage_sections', 'icycp_agencyup_slider', absint( $homepage_section_priority ) );

}

/*** Service */
if ( ! function_exists( 'icycp_agencyup_top_contact' ) ) :

	function icycp_agencyup_top_contact() {

	$contact_one_icon = get_theme_mod('contact_one_icon','fa-map-marker');
	$contact_one_title = get_theme_mod('contact_one_title','Head Office');
	$contact_one_description = get_theme_mod('contact_one_description','4578 Marmora Road, Glasgow');

	$contact_two_icon = get_theme_mod('contact_two_icon','fa-phone');
	$contact_two_title = get_theme_mod('contact_two_title','Call Us');
	$contact_two_description = get_theme_mod('contact_two_description','(+81) 123-456-7890	');

	$contact_three_icon = get_theme_mod('contact_three_icon','fa-envelope-open');
	$contact_three_title = get_theme_mod('contact_three_title','7:30 AM - 7:30 PM');
	$contact_three_description = get_theme_mod('contact_three_description','Monday to Saturday');
  $contact_info_section_show         = get_theme_mod('contact_info_section_show','1');
  if($contact_info_section_show == '1') {
?> 
  <section class="top-ct-section">
    <div class="overlay">
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <div class="media feature_widget" style="background-color: #000;">
            <i class="mr-3 fa <?php echo esc_attr($contact_one_icon); ?>"></i>
            <div class="media-body">
              <h5 class="mt-0"><?php echo esc_html($contact_one_title); ?></h5>
              <?php echo esc_html($contact_one_description); ?>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="media feature_widget" style="background-color: #000;">
            <i class="mr-3 fa <?php echo esc_attr($contact_two_icon); ?>"></i>
            <div class="media-body">
              <h5 class="mt-0"><?php echo esc_html($contact_two_title); ?></h5>
              <?php echo esc_html($contact_two_description); ?>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="media feature_widget" style="background-color: #000;">
            <i class="mr-3 fa <?php echo esc_attr($contact_three_icon); ?>"></i>
            <div class="media-body">
              <h5 class="mt-0"><?php echo esc_html($contact_three_title); ?></h5>
              <?php echo esc_html($contact_three_description); ?>
            </div>
          </div>
        </div>
      </div> 
    </div>
  </div>
</section>
<?php
} }

endif;

if ( function_exists( 'icycp_agencyup_top_contact' ) ) {
$homepage_section_priority = apply_filters( 'icycp_consultup_homepage_section_priority', 11, 'icycp_agencyup_top_contact' );
add_action( 'icycp_consultup_homepage_sections', 'icycp_agencyup_top_contact', absint( $homepage_section_priority ) );

}

/*** Service */
if ( ! function_exists( 'icycp_consultup_service' ) ) :

	function icycp_consultup_service() {

		$service_section_title = get_theme_mod('service_section_title',__('We Create Digital Opportunities
','icyclub'));
		$service_section_discription = get_theme_mod('service_section_discription','laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis. ');

		$service_section_subtitle = get_theme_mod('service_section_subtitle','SERVICE WE PROVIDE');

    $service_image_1 = get_theme_mod('service_image_1',ICYCP_PLUGIN_URL .'inc/agencyup/images/service/service1.jpg');
    $service_image_2 = get_theme_mod('service_image_2',ICYCP_PLUGIN_URL .'inc/agencyup/images/service/service2.jpg');
    $service_image_3 = get_theme_mod('service_image_3',ICYCP_PLUGIN_URL .'inc/agencyup/images/service/service3.jpg');

    $service_section_show         = get_theme_mod('service_section_show','1');
    if($service_section_show == '1') {
		?>
	    <!-- Section Title -->
<section class="bs-section service" id="service-section">
	<div class="overlay">
	<div class="container">		
		<?php if( ($service_section_title) || ($service_section_discription) || ($service_section_subtitle)!='' ) { ?>
			<div class="col text-center">
            <div class="bs-heading">
              <h3 class="bs-subtitle"><?php echo $service_section_subtitle; ?></h3>
              <h2 class="bs-title"><?php echo $service_section_title; ?></h2>
			  <p><?php echo $service_section_discription; ?></p>
            </div>
          </div>
		<!-- /Section Title -->
		<?php } ?>
			<div class="row">
			  <div class="col-md-4 swing animated service-one">
			  	<div class="bs-sevice two text-center shd mb-md-0" style="background-image: url('<?php echo $service_image_1; ?>');">
                 <div class="bs-sevice-inner">
					<?php  $service_one_icon = get_theme_mod('service_one_icon','fas fa-hands-helping'); ?>
                    <i class="fas <?php echo  $service_one_icon; ?>"></i>
					 <?php  $service_one_title = get_theme_mod('service_one_title','Business Consulting'); ?>
                    <h4><a href="#"><?php echo $service_one_title; ?></a></h4>
					<?php  $service_one_description = get_theme_mod('service_one_description','We’re the leading consulting explain to you how all this mista ke idea of denouncing pleasure and praising pain was born'); ?>
                    <p class="service-one-desc"><?php echo $service_one_description; ?></p>
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

              <div class="col-md-4 swing animated service-two">
			  	<div class="bs-sevice two text-center shd mb-md-0" style="background-image: url('<?php echo $service_image_2; ?>');">
                 <div class="bs-sevice-inner">
					<?php  $service_two_icon = get_theme_mod('service_two_icon','fas fa-chart-line'); ?>
                    <i class="fas <?php echo  $service_two_icon; ?>"></i>
					 <?php  $service_two_title = get_theme_mod('service_two_title','Market Analysis'); ?>
                    <h4><a href="#"><?php echo $service_two_title; ?></a></h4>
					<?php  $service_two_description = get_theme_mod('service_two_description','We’re the leading consulting explain to you how all this mista ke idea of denouncing pleasure and praising pain was born'); ?>
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

              <div class="col-md-4 swing animated service-three">
			  	<div class="bs-sevice two text-center shd mb-md-0" style="background-image: url('<?php echo $service_image_1; ?>');">
                 <div class="bs-sevice-inner">
					<?php  $service_three_icon = get_theme_mod('service_three_icon','fas fa-briefcase'); ?>
                    <i class="fas <?php echo  $service_three_icon; ?>"></i>
					 <?php  $service_three_title = get_theme_mod('service_three_title','Financial Planning'); ?>
                    <h4><a href="#"><?php echo $service_three_title; ?></a></h4>
					<?php  $service_three_description = get_theme_mod('service_three_description','We’re the leading consulting explain to you how all this mista ke idea of denouncing pleasure and praising pain was born'); ?>
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
</div>
</section>
<?php		} }

endif;
if ( function_exists( 'icycp_consultup_service' ) ) {
	$section_priority = apply_filters( 'icycp_consultup_homepage_section_priority', 11, 'icycp_consultup_service' );
	add_action( 'icycp_consultup_homepage_sections', 'icycp_consultup_service', absint( $section_priority ) );
}



/**
 * Portfolio
 */
if ( ! function_exists( 'icycp_consultup_portfolio' ) ) :

	function icycp_consultup_portfolio() {
		
		$portfolio_section_title = get_theme_mod('portfolio_section_title',__('Our Portfolio','icyclub'));
		$portfolio_section_discription = get_theme_mod('portfolio_section_discription','laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.');
		$portfolio_section_subtitle = get_theme_mod('portfolio_section_subtitle','OUR PORTFOLIO');
		
		$project_image_one = get_theme_mod('project_image_one',ICYCP_PLUGIN_URL .'inc/agencyup/images/portfolio/portfolio1.jpg');
		$project_title_one = get_theme_mod('project_title_one',__('Financial Project','agencyup'));
		
		$project_image_two = get_theme_mod('project_image_two',ICYCP_PLUGIN_URL .'inc/agencyup/images/portfolio/portfolio2.jpg');
		$project_title_two = get_theme_mod('project_title_two',__('Investment','agencyup'));
		
		
		$project_image_three = get_theme_mod('project_image_three',ICYCP_PLUGIN_URL .'inc/agencyup/images/portfolio/portfolio3.jpg');
		$project_title_three = get_theme_mod('project_title_three',__('Invoicing','agencyup'));

    $project_image_four = get_theme_mod('project_image_four',ICYCP_PLUGIN_URL .'inc/agencyup/images/portfolio/portfolio4.jpg');
    $project_title_four = get_theme_mod('project_title_four',__('Team Management','agencyup'));
		
    $project_section_enable         = get_theme_mod('project_section_enable','1');
    if($project_section_enable == '1') {
		?>		
<!-- Portfolio Section -->
<section class="bs-section portfolios" id="portfolio-section">
   <div class="overlay">
    <div class="container">
     <?php if( ($portfolio_section_title) || ($portfolio_section_discription) || ($portfolio_section_subtitle)!='' ) { ?>
      <div class="col text-center">
		<!-- Section Title -->
		 <div class="bs-heading">
		 	  <h3 class="bs-subtitle"><?php echo $portfolio_section_subtitle; ?></h3>
              <h2 class="bs-title"><?php echo $portfolio_section_title; ?></h2>
			  <p><?php echo $portfolio_section_discription; ?></p>
			</div> 
         </div>
		<!-- /Section Title -->
		<?php } ?>
    <!--container-->
      <!--row-->
      <div class="row">
        <!--portfolio-->
          <!--item-->
			<!--col-md-12-->
            <div class="col-md-3 project-one">
              <!--portfolio-->
               <div class="bs-portfolio-block mb-md-0" style="background-image: url(<?php echo $project_image_one;?>);">
                  <div class="inner-content">
                    <div class="text clearfix">
                     <div class="bottom_text">
                        <h2><a href="#"><?php echo $project_title_one; ?></a></h2>
                      </div>
                    </div> <!-- /.text -->
                  </div> <!-- /.hover-content -->
                </div>
              <!--/portfolio-->
            </div>
            <!--col-md-12-->
		  <div class="col-md-3 project-two">
              <!--portfolio-->
               <div class="bs-portfolio-block mb-md-0" style="background-image: url(<?php echo $project_image_two;?>);">
                  <div class="inner-content">
                    <div class="text clearfix">
                     <div class="bottom_text">
                        <h2><a href="#"><?php echo $project_title_two; ?></a></h2>
                      </div>
                    </div> <!-- /.text -->
                  </div> <!-- /.hover-content -->
                </div>
              <!--/portfolio-->
            </div>
			<div class="col-md-3 project-three">
              <!--portfolio-->
               <div class="bs-portfolio-block mb-md-0" style="background-image: url(<?php echo $project_image_three;?>);">
                  <div class="inner-content">
                    <div class="text clearfix">
                     <div class="bottom_text">
                        <h2><a href="#"><?php echo $project_title_three; ?></a></h2>
                      </div>
                    </div> <!-- /.text -->
                  </div> <!-- /.hover-content -->
                </div>
              <!--/portfolio-->
            </div>
            <div class="col-md-3 project-four">
              <!--portfolio-->
               <div class="bs-portfolio-block mb-md-0" style="background-image: url(<?php echo $project_image_four;?>);">
                  <div class="inner-content">
                    <div class="text clearfix">
                     <div class="bottom_text">
                        <h2><a href="#"><?php echo $project_title_four; ?></a></h2>
                      </div>
                    </div> <!-- /.text -->
                  </div> <!-- /.hover-content -->
                </div>
              <!--/portfolio-->
            </div>
          <!--/item-->
          <!--item-->
          <!--/item-->
        </div>
        <!--/portfolio-->
      </div>
      <!--/row-->
    </div>
    <!--/conconsultupiner-->
  </section>
<!-- /Portfolio Section -->

<div class="clearfix"></div>	
<?php } }

endif;

		if ( function_exists( 'icycp_consultup_portfolio' ) ) {
		$section_priority = apply_filters( 'icycp_consultup_homepage_section_priority', 13, 'icycp_consultup_portfolio' );
		add_action( 'icycp_consultup_homepage_sections', 'icycp_consultup_portfolio', absint( $section_priority ) );

		}
		
//Testimonial
if ( ! function_exists( 'icycp_consultup_testimonial' ) ) :

	function icycp_consultup_testimonial() {
$testimonial_section_enable         = get_theme_mod('testimonial_section_enable','1');
if($testimonial_section_enable == '1') {
$testimonial_background_image = get_theme_mod('testimonial_background_image',ICYCP_PLUGIN_URL .'inc/agencyup/images/callout/callout-back.jpg');
$testimonial_back_overlay_color = get_theme_mod('testimonial_back_overlay_color');
$testimonial_back_image_overlay = get_theme_mod('testimonial_back_image_overlay');
$testimonial_section_subtitle = get_theme_mod('testimonial_section_subtitle','TESTIMONIALS');
$testimonial_section_title = get_theme_mod('testimonial_section_title','Our Clients Says');
$testimonial_section_discription= get_theme_mod('testimonial_section_discription','laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.');
if($testimonial_background_image != '') { 
?>
<section  class="bs-section testimonials one top" id="testimonial-section" style="background-image:url('<?php echo esc_url($testimonial_background_image);?>');">
<?php } else { ?>
<section class="bs-section testimonials one top" id="testimonial-section">
<?php } ?>
<div class="overlay" style="background:<?php echo esc_attr($testimonial_back_overlay_color);?>;">
      <!--container-->
      <div class="container">
        <!--row-->
        <div class="col text-center">
            <div class="bs-heading">
              <h3 class="bs-subtitle"><?php echo esc_html($testimonial_section_subtitle); ?></h3>
              <h2 class="bs-title"><?php echo esc_html($testimonial_section_title); ?></h2>
              <p><?php echo esc_html($testimonial_section_discription); ?></p>
            </div> 
          </div>
        <!--/row-->
        <!--content-testimonials-->
          	<?php 
				 $testimonial_one_title=  get_theme_mod('testimonial_one_title','Professional Team');
				 $testimonial_one_desc = get_theme_mod('testimonial_one_desc','Vestibulum quis porttitor dui! viverra nunc mi, Aliquam condimentum mattis neque sed pretium Aliquam condimentum mattis neque sed pretiumAliquam condimentum mattis neque sed pretium');
				 $testimonial_one_thumb = get_theme_mod('testimonial_one_thumb',ICYCP_PLUGIN_URL .'inc/consultup/images/testimonial/testi1.jpg');
				 $testimonial_one_name = get_theme_mod('testimonial_one_name','Williams Moore');
				 $testimonial_one_designation = get_theme_mod('testimonial_one_designation','Creative Designer');
		    ?>
            <!--item-->
     		<div class="testi">
      			<img class="clg" src="<?php echo $testimonial_one_thumb; ?>">
     			 <h6><?php echo $testimonial_one_name; ?></h6>
  				<div class="details"><?php echo $testimonial_one_designation; ?></div>
  				<p class="testimonial-dec"><?php echo $testimonial_one_desc; ?></p>
    		</div>
          <!--/testimonial-->
        <!--/content-testimonials-->
      <!--/conconsultupiner-->
    </div>
    <!--/overlay-->
  </section>

		<?php
	} }

endif;

		if ( function_exists( 'icycp_consultup_testimonial' ) ) {
		$section_priority = apply_filters( 'icycp_consultup_homepage_section_priority', 14, 'icycp_consultup_testimonial' );
		add_action( 'icycp_consultup_homepage_sections', 'icycp_consultup_testimonial', absint( $section_priority ) );
}


//News
if ( ! function_exists( 'icycp_consultup_news' ) ) :

function icycp_consultup_news() {
$news_section_show         = get_theme_mod('news_section_show','1');
if($news_section_show == '1') {
$news_section_subtitle = get_theme_mod('news_section_subtitle','Our Blog');
$news_section_title = get_theme_mod('news_section_title',__('Latest News','icyclub'));
$news_section_description= get_theme_mod('news_section_description','laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.');
$news_section_post_count = get_theme_mod('news_section_post_count', __('3','icyclub'));
?>
<!--==================== BLOG SECTION ====================-->
  <section id="news-section" class="bs-section blog">
    <!--overlay-->
    <div class="overlay">
      <!--container-->
      <div class="container">
        <!--row-->
        <div class="col text-center">
          <div class="bs-heading">
          	<h3 class="bs-subtitle"><?php echo $news_section_subtitle; ?></h3>
			<?php $news_section_title = get_theme_mod('news_section_title',__('Latest News','consultup'));?>
              <h2 class="bs-title"><?php echo $news_section_title;?></h2>
			  <?php $news_section_description = get_theme_mod('news_section_description','laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.');?>
			  <p><?php echo esc_html($news_section_description);?></p>
          </div>
        </div>
        <!--/row-->
        <!--row-->
        <div class="row">
          <!--col-md-4-->
          <?php $consultup_latest_loop = new WP_Query(array( 'post_type' => 'post', 'posts_per_page' => $news_section_post_count, 'order' => 'DESC','ignore_sticky_posts' => true, ''));
			if ( $consultup_latest_loop->have_posts() ) :
			$i = 1;
			 while ( $consultup_latest_loop->have_posts() ) : $consultup_latest_loop->the_post();?>
		  <div class="col-md-4">
            <div class="bs-blog-post shd mb-md-0"> 
              <div class="bs-blog-thumb">
                <?php 
                if(has_post_thumbnail()){
                echo '<a  href="'.esc_url(get_the_permalink()).'">';
                the_post_thumbnail( '', array( 'class'=>'img-fluid' ) );
                echo '</a>';
                ?>
                <?php } ?>
              </div>
              <article class="small">
                  <div class="bs-blog-category"> <a href="<?php the_permalink(); ?>"><?php $cat_list = get_the_category_list();
				if(!empty($cat_list)) { ?>
                <?php the_category(' '); ?>
                <?php } ?></a> </div>
                  <h4 class="title sm"><a title="<?php the_title(); ?>" href="<?php the_permalink();?>"><?php the_title(); ?></a></h4>
                    <div class="bs-blog-meta"> 
                      <span class="bs-blog-date"><a href="<?php echo esc_url(get_month_link(get_post_time('Y'),get_post_time('m'))); ?>">
				  <?php echo esc_html(get_the_date('M j, Y')); ?></a></span> 
                      <span class="bs-author"><a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>"><?php the_author(); ?></a> </span>
                      <span class="comments-link"> <a href="#">2 Comments</a> </span>
                    </div>
                    <?php $consultup_post_content_type = get_theme_mod('consultup_post_content_type','content'); 
				if($consultup_post_content_type == 'content') {
				 the_content(__('Read More','consultup'));
					wp_link_pages( array( 'before' => '<div class="link btn-theme">' . __( 'Pages:', 'agencyup' ), 'after' => '</div>' ) ); }
					elseif($consultup_post_content_type == 'excerpt')
					{ ?>
						<p><?php echo icyclub_news_excerpt(); ?></p>

					<?php } ?>
			</article>
            </div>
          </div>
		  <?php 
		  if($i==3)
			  { 
			     echo '<div class="clearfix"></div>';
				 $i=0;
			  }$i++;

		endwhile; endif;	wp_reset_postdata(); ?>
          <!--/col-md-4-->
        </div>
        <!--/row-->
      </div>
      <!--/col-md-6-->
    </div>
    <!--/col-md-6-->
  </section>
<?php } }
endif;

		if ( function_exists( 'icycp_consultup_news' ) ) {
		$section_priority = apply_filters( 'icycp_consultup_homepage_section_priority', 14, 'icycp_consultup_news' );
		add_action( 'icycp_consultup_homepage_sections', 'icycp_consultup_news', absint( $section_priority ) );
}

/**
 * Callout section
 */
if ( ! function_exists( 'icycp_consultup_callout' ) ) :

	function icycp_consultup_callout() {
		
		$callout_background_image = get_theme_mod('callout_background_image',ICYCP_PLUGIN_URL .'inc/agencyup/images/callout/callout-back.jpg');
		$callout_back_overlay_color = get_theme_mod('callout_back_overlay_color');
		$callout_title = get_theme_mod('callout_title',__('Trusted By Over 10,000 Worldwide Businesses. Try Today!
','agencyup'));
		$callout_discription = get_theme_mod('callout_discription','looking For Professional Approach & Qaulity Services!');
		$callout_btn_txt = get_theme_mod('callout_btn_txt',__('Get Started Now!','agencyup'));
		$callout_btn_link = get_theme_mod('callout_btn_link','https://themeansar.com/themes/agencyup-pro/');
		$callout_btn_target = get_theme_mod('callout_btn_target',true);
    $homepage_callout_show         = get_theme_mod('homepage_callout_show','1');
    if($homepage_callout_show == '1') {
    if($callout_background_image != '') {
     ?>
<section class="bs-section calltoaction" id="callout-section" style="background-image:url('<?php echo esc_url($callout_background_image);?>');">
<?php } else { ?>
<section class="bs-section calltoaction" id="callout-section">
<?php } ?>
<div class="overlay" style="background:<?php echo esc_attr($callout_back_overlay_color);?>;">
      <!--container-->
      <div class="container">
        <!--row-->
        <div class="row align-items-center">
          <!--consultup-callout-inner-->
          <div class="col-md-8">
            <div class="bs-heading text-left">
              <h3 class="bs-subtitle"><?php echo $callout_title;  ?></h3>
              	<h2 class="bs-title"><?php echo $callout_discription;  ?></h2>
			</div>
		   </div>

		   	<div class="col-md-4">
            <?php if($callout_btn_txt) {?>
                  <a <?php if($callout_btn_link) { ?> href="<?php echo $callout_btn_link; } ?>" 
						<?php if($callout_btn_target) { ?> target="_blank" <?php } ?> class="btn btn-theme-two">
						<?php if($callout_btn_txt) { echo $callout_btn_txt; } ?></a>
			<?php } ?>
			</div>
        	</div>
          </div>
          <!--consultup-callout-inner-->
        </div>
        <!--/row-->
      <!--/conconsultupiner-->
</section>
<!-- /Portfolio Section -->

<div class="clearfix"></div>	
<?php } }

endif;

		if ( function_exists( 'icycp_consultup_callout' ) ) {
		$section_priority = apply_filters( 'icycp_consultup_homepage_section_priority', 15, 'icycp_consultup_callout' );
		add_action( 'icycp_consultup_homepage_sections', 'icycp_consultup_callout', absint( $section_priority ) );

		}
