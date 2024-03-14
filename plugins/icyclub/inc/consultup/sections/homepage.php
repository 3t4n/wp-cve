<?php
/**
 * Slider section
 */
if ( ! function_exists( 'icycp_consultup_slider' ) ) :
	function icycp_consultup_slider() {
		$slider_image = get_theme_mod('slider_image',ICYCP_PLUGIN_URL .'inc/consultup/images/slider/banner.jpg');
		$slider_overlay_section_color = get_theme_mod('slider_overlay_section_color');
		$slider_title = get_theme_mod('slider_title','We are Best in Premium Consulting Services');
		
		$slider_discription = get_theme_mod('slider_discription','we bring the proper people along to challenge esconsultupblished thinking and drive transformation.');
		$slider_btn_txt = get_theme_mod('slider_btn_txt','Read More');
		$slider_btn_link = get_theme_mod('slider_btn_link',esc_url('#'));
		$slider_btn_target = get_theme_mod('slider_btn_target',false);
		$home_page_slider_enabled         = get_theme_mod('home_page_slider_enabled','1');
        if($home_page_slider_enabled == '1') {	
		?>
	
	<!--== Home Slider ==-->
  <section class="consultup-slider-warraper" id="slider-section">
    <!--== consultup-slider ==-->
    <div id="consultup-slider" class="owl-carousel"> 
       <!--item-->
		 <div class="item">
        <!--slide image-->
        <figure> <img src="<?php echo $slider_image; ?>" alt="image description"> </figure>
        <!--/slide image-->
        <!--slide inner-->
        <div class="consultup-slider-inner" style="background:<?php echo esc_attr($slider_overlay_section_color);?>">
          <div class="container inner-table">
            <div class="inner-table-cell">
              <!--slide content area-->
              <div class="slide-caption slide-c">
                <!--slide box style-->
                <div>
                 <?php 
					 if ( ! empty( $slider_title ) || is_customize_preview() ) { ?>
					<h1><?php echo $slider_title;  ?></h1>
					<?php } 
					if ( ! empty( $slider_discription ) || is_customize_preview() ) {
				  ?>
                  <div class="description">
                    <p><?php echo $slider_discription; ?></p>
                  </div>
				 <?php } if($slider_btn_txt) {?>
                  <a <?php if($slider_btn_link) { ?> href="<?php echo $slider_btn_link; } ?>" 
						<?php if($slider_btn_target) { ?> target="_blank" <?php } ?> class="btn btn-tislider">
						<?php if($slider_btn_txt) { echo $slider_btn_txt; } ?></a>
				 <?php } ?>	
                  <!--/slide box style-->
				</div>
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
    <!--/consultup-slider--> 
  </section>
<div class="clearfix"></div>	
	
		<?php
}
	}

endif;

if ( function_exists( 'icycp_consultup_slider' ) ) {
$homepage_section_priority = apply_filters( 'icycp_consultup_homepage_section_priority', 10, 'icycp_consultup_slider' );
add_action( 'icycp_consultup_homepage_sections', 'icycp_consultup_slider', absint( $homepage_section_priority ) );

}


/*** Service */
if ( ! function_exists( 'icycp_consultup_service' ) ) :

	function icycp_consultup_service() {

		$service_section_title = get_theme_mod('service_section_title',__('Why We Best in Business Services','icyclub'));
		$service_section_discription = get_theme_mod('service_section_discription','laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.');
		$service_section_show         = get_theme_mod('service_section_show','1');
        if($service_section_show == '1') {		
		?>
	    <!-- Section Title -->
<section class="consultup-service-section" id="service-section">
	<div class="container">		
		<?php if( ($service_section_title) || ($service_section_discription)!='' ) { ?>
		<div class="row">
			<div class="col-md-12 wow fadeInDown animated padding-bottom-20">
            <div class="consultup-heading">
              <h3 class="consultup-heading-inner"><?php echo $service_section_title; ?></h3>
			  <p><?php echo $service_section_discription; ?></p>
            </div>
          </div>
		</div>
		<!-- /Section Title -->
		<?php } ?>
			<div class="row">
			  <div class="col-md-4 swing animated service-one">
                <div class="consultup-service three text-left">
                  <div class="consultup-service-inner">
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
			  <div class="col-md-4 swing animated service-two">
                <div class="consultup-service three text-left">
                  <div class="consultup-service-inner">
				    <?php  $service_two_icon = get_theme_mod('service_two_icon','far fa-newspaper'); ?>
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
			  <div class="col-md-4 swing animated service-three">
                <div class="consultup-service three text-left">
                  <div class="consultup-service-inner">
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
if ( function_exists( 'icycp_consultup_service' ) ) {
	$section_priority = apply_filters( 'icycp_consultup_homepage_section_priority', 11, 'icycp_consultup_service' );
	add_action( 'icycp_consultup_homepage_sections', 'icycp_consultup_service', absint( $section_priority ) );
}


/**
 * Callout section
 */
if ( ! function_exists( 'icycp_consultup_callout' ) ) :

	function icycp_consultup_callout() {
		
		$callout_background_image = get_theme_mod('callout_background_image',ICYCP_PLUGIN_URL .'inc/consultup/images/callout/callout-back.jpg');
		$callout_back_overlay_color = get_theme_mod('callout_back_overlay_color');
		$callout_title = get_theme_mod('callout_title',__('Trusted By Over 10,000 Worldwide Businesses. Try Today!','consultup'));
		$callout_discription = get_theme_mod('callout_discription','We must explain to you how all this misconsultupken idea of denouncing pleasure');
		$callout_btn_txt = get_theme_mod('callout_btn_txt',__('Get Started Now!','consultup'));
		$callout_btn_link = get_theme_mod('callout_btn_link','https://themeansar.com/themes/consultup-pro/');
		$callout_btn_target = get_theme_mod('callout_btn_target',true);
		$homepage_callout_show = get_theme_mod('homepage_callout_show','1');
        if($homepage_callout_show == '1') {
		if($callout_background_image != '') { 
		?>		
<section class="consultup-callout" id="callout-section" style="background-image:url('<?php echo esc_url($callout_background_image);?>');">
<?php } else { ?>
<section class="consultup-callout" id="callout-section">
<?php } ?>
<div class="overlay" style="background:<?php echo esc_attr($callout_back_overlay_color);?>;">	
	
	
	
      <!--container-->
      <div class="container">
        <!--row-->
        <div class="row">
          <!--consultup-callout-inner-->
          <div class="consultup-callout-inner text-center text-xs">
            <h3 class="padding-bottom-30"><?php echo $callout_title;  ?></h3>
			<p><?php echo $callout_discription;  ?></p>
            <?php if($callout_btn_txt) {?>
                  <a <?php if($callout_btn_link) { ?> href="<?php echo $callout_btn_link; } ?>" 
						<?php if($callout_btn_target) { ?> target="_blank" <?php } ?> class="btn btn-theme margin-top-20">
						<?php if($callout_btn_txt) { echo $callout_btn_txt; } ?></a>
			<?php } ?>
        </div>
          </div>
          <!--consultup-callout-inner-->
        </div>
        <!--/row-->
  </div>
      <!--/conconsultupiner-->
</section>
<!-- /Portfolio Section -->

<div class="clearfix"></div>	
<?php } }

endif;

		if ( function_exists( 'icycp_consultup_callout' ) ) {
		$section_priority = apply_filters( 'icycp_consultup_homepage_section_priority', 12, 'icycp_consultup_callout' );
		add_action( 'icycp_consultup_homepage_sections', 'icycp_consultup_callout', absint( $section_priority ) );

		}

/**
 * Portfolio
 */
if ( ! function_exists( 'icycp_consultup_portfolio' ) ) :

	function icycp_consultup_portfolio() {
		
		$portfolio_section_title = get_theme_mod('portfolio_section_title',__('Our Portfolio','icyclub'));
		$portfolio_section_discription = get_theme_mod('portfolio_section_discription','laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.');
		
		$project_image_one = get_theme_mod('project_image_one',ICYCP_PLUGIN_URL .'inc/consultup/images/portfolio/portfolio1.jpg');
		$project_title_one = get_theme_mod('project_title_one',__('Financial Project','consultup'));
		$project_desc_one = get_theme_mod('project_desc_one','Lorem ipsum dolor sit amet, consectetur adipisicing elit..');
		
		$project_image_two = get_theme_mod('project_image_two',ICYCP_PLUGIN_URL .'inc/consultup/images/portfolio/portfolio2.jpg');
		$project_title_two = get_theme_mod('project_title_two',__('Investment','consultup'));
		$project_desc_two = get_theme_mod('project_desc_two','Lorem ipsum dolor sit amet, consectetur adipisicing elit..');
		
		
		$project_image_three = get_theme_mod('project_image_three',ICYCP_PLUGIN_URL .'inc/consultup/images/portfolio/portfolio3.jpg');
		$project_title_three = get_theme_mod('project_title_three',__('Invoicing','consultup'));
		$project_desc_three = get_theme_mod('project_desc_three','Lorem ipsum dolor sit amet, consectetur adipisicing elit..');
		
		$project_section_enable = get_theme_mod('project_section_enable','1');
        if($project_section_enable == '1') {
		?>		
<!-- Portfolio Section -->
<section class="consultup-portfolio consultup-section grey-bg no-padding text-center" id="portfolio-section">
    <div class="container">
      <div class="row">
        <?php if( ($portfolio_section_title) || ($portfolio_section_discription)!='' ) { ?>
		<!-- Section Title -->
		<div class="col-md-12 wow fadeInDown animated padding-bottom-20">
            <div class="consultup-heading">
              <h3 class="consultup-heading-inner"><?php echo $portfolio_section_title; ?></h3>
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
          <!--item-->
			<!--col-md-12-->
            <div class="col-md-4 project-one">
              <!--portfolio-->
              <div class="consultup-portfolio-block">
                  <img src="<?php echo $project_image_one; ?>" alt="">
                  <div class="inner-content">
                    <div class="text clearfix">
                      <div class="text-left">
                        <h5><a href="#"><?php echo $project_title_one; ?></a></h5>
                        <div class="consultup-portfolio-category"><p><?php echo $project_desc_one; ?></p></div>
                      </div>
                    </div> <!-- /.text -->
                  </div> <!-- /.hover-content -->
                </div>
              <!--/portfolio-->
            </div>
            <!--col-md-12-->
		  <div class="col-md-4 project-two">
              <!--portfolio-->
              <div class="consultup-portfolio-block">
                  <img src="<?php echo $project_image_two; ?>" alt="">
                  <div class="inner-content">
                    <div class="text clearfix">
                      <div class="text-left">
                        <h5><a href="#"><?php echo $project_title_two; ?></a></h5>
                        <div class="consultup-portfolio-category"><p><?php echo $project_desc_two; ?></p></div>
                      </div>
                    </div> <!-- /.text -->
                  </div> <!-- /.hover-content -->
                </div>
              <!--/portfolio-->
            </div>
			<div class="col-md-4 project-three">
              <!--portfolio-->
              <div class="consultup-portfolio-block">
                  <img src="<?php echo $project_image_three; ?>" alt="">
                  <div class="inner-content">
                    <div class="text clearfix">
                      <div class="text-left">
                        <h5><a href="#"><?php echo $project_title_three; ?></a></h5>
                        <div class="consultup-portfolio-category"><p><?php echo $project_desc_three; ?></p></div>
                      </div>
                    </div> <!-- /.text -->
                  </div> <!-- /.hover-content -->
                </div>
              <!--/portfolio-->
            </div>
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

$testimonial_section_enable = get_theme_mod('testimonial_section_enable','1');
if($testimonial_section_enable == '1') {
$testimonial_section_title = get_theme_mod('testimonial_section_title','Our Clients Says');
$testimonial_section_discription= get_theme_mod('testimonial_section_discription','laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.')
?>
<section class="testimonials-section" id="testimonial-section">
    <!--overlay-->
    <div class="overlay">
      <!--container-->
      <div class="container">
        <!--row-->
        <div class="row">
          <div class="col-md-12 wow fadeInDown  padding-bottom-20">
            <div class="consultup-heading">
              <h3 class="consultup-heading-inner"><?php echo $testimonial_section_title; ?></h3>
			  <p><?php echo $testimonial_section_discription; ?></p>
            </div>
          </div>
        </div>
        <!--/row-->
        <!--content-testimonials-->
        <div class="content-testimonials">
          <!--testimonial-->
          <div id="consultup-testimonial">
            <!--item-->
           <?php 
				 $testimonial_one_title=  get_theme_mod('testimonial_one_title','Professional Team');
				 $testimonial_one_desc = get_theme_mod('testimonial_one_desc','Vestibulum quis porttitor dui! viverra nunc mi, Aliquam condimentum mattis neque sed pretium Aliquam condimentum mattis neque sed pretiumAliquam condimentum mattis neque sed pretium');
				 $testimonial_one_thumb = get_theme_mod('testimonial_one_thumb',ICYCP_PLUGIN_URL .'inc/consultup/images/testimonial/testi1.jpg');
				 $testimonial_one_name = get_theme_mod('testimonial_one_name','Williams Moore');
				 $testimonial_one_designation = get_theme_mod('testimonial_one_designation','Creative Designer');
				 
				 $testimonial_two_title=  get_theme_mod('testimonial_two_title','Professional Team');
				 $testimonial_two_desc = get_theme_mod('testimonial_two_desc','Vestibulum quis porttitor dui! viverra nunc mi, Aliquam condimentum mattis neque sed pretium Aliquam condimentum mattis neque sed pretiumAliquam condimentum mattis neque sed pretium');
				 $testimonial_two_thumb = get_theme_mod('testimonial_two_thumb',ICYCP_PLUGIN_URL .'inc/consultup/images/testimonial/testi2.jpg');
				 $testimonial_two_name = get_theme_mod('testimonial_two_name','Williams Moore');
				 $testimonial_two_designation = get_theme_mod('testimonial_two_designation','Creative Designer');
		   ?>
			
              <div class="col-md-6 testimonial-one">
			  <div class="testimonials_qute"> 
                <div class="sub-qute">
                  <i class="fa fa-quote-left"></i>
                  <h5><?php echo $testimonial_one_title ?></h5>
                  <p><?php echo $testimonial_one_desc ?></p>
                  <div class="consultup-client-qute">
                    <span class="consultup-client"><img src="<?php echo $testimonial_one_thumb; ?>" alt="client"></span>
                    <div class="consultup-client-info">
                      <h6 class="user-title"><?php echo $testimonial_one_name; ?></h6>
                      <p class="user-designation"><?php echo $testimonial_one_designation; ?></p>
                    </div>
                  </div>
                </div>
              </div>
			  </div>
           
			<div class="col-md-6 testimonial-two">
			  <div class="testimonials_qute"> 
                <div class="sub-qute">
                  <i class="fa fa-quote-left"></i>
                  <h5><?php echo $testimonial_two_title ?></h5>
                  <p><?php echo $testimonial_two_desc ?></p>
                  <div class="consultup-client-qute">
                    <span class="consultup-client"><img src="<?php echo $testimonial_two_thumb; ?>" alt="client"></span>
                    <div class="consultup-client-info">
                      <h6 class="user-title"><?php echo $testimonial_two_name; ?></h6>
                      <p class="user-designation"><?php echo $testimonial_two_designation; ?></p>
                    </div>
                  </div>
                </div>
              </div>
			  </div> 
          <!--/testimonial-->
        </div>
        <!--/content-testimonials-->
      </div>
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
$news_section_show = get_theme_mod('news_section_show','1');
if($news_section_show == '1') {
$news_section_title = get_theme_mod('news_section_title',__('Latest News','consultup'));
$news_section_description= get_theme_mod('news_section_description','laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.');
$news_section_post_count = get_theme_mod('news_section_post_count', __('3','consultup'));
?>
<!--==================== BLOG SECTION ====================-->
  <section id="news-section" class="consultup-blog-section">
    <!--overlay-->
    <div class="overlay">
      <!--container-->
      <div class="container">
        <!--row-->
        <div class="row">
          <div class="col-md-12 wow fadeInDown animated padding-bottom-20">
            <div class="consultup-heading">
			<?php $news_section_title = get_theme_mod('news_section_title',__('Latest News','consultup'));?>
              <h3 class="consultup-heading-inner"><?php echo esc_html($news_section_title);?></h3>
			  <?php $news_section_description = get_theme_mod('news_section_description','laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.');?>
			  <p><?php echo esc_html($news_section_description);?></p>
			</div>
			
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
		  <div class="col-md-4 col-sm-6 pulse animated">
            <div class="consultup-blog-post-box"> 
              <div  class="consultup-blog-thumb">
                 <?php if(has_post_thumbnail()){ ?>
				<div class="consultup-blog-category"> <?php $cat_list = get_the_category_list();
				if(!empty($cat_list)) { ?>
                <?php the_category(' '); ?>
                <?php } ?> </div>
				 <?php } else { ?>
				<div class="consultup-blog-category-left"> <?php $cat_list = get_the_category_list();
								if(!empty($cat_list)) { ?>
                <?php the_category(' '); ?>
                <?php } ?> </div>
				 <?php } if(has_post_thumbnail()): ?>
				<a title="<?php the_title_attribute(); ?>" href="<?php esc_url(the_permalink()); ?>" class="consultup-blog-thumb img-responsive"> 
				  <?php $defalt_arg =array('class' => "img-responsive"); ?>
				  <?php the_post_thumbnail('', $defalt_arg); ?>
				</a>
				<?php endif; ?>				
				</div>
              <article class="small">
                <h1 class="title"> <a href="<?php echo esc_url(get_permalink()); ?>" title="<?php the_title_attribute(); ?>"><?php echo get_the_title() ?></a> </h1>
                  <div class="consultup-blog-meta"><span class="consultup-blog-date"><i class="far fa-clock"></i><a href="<?php echo esc_url(get_month_link(get_post_time('Y'),get_post_time('m'))); ?>">
				  <?php echo esc_html(get_the_date('M j, Y')); ?></a></span>
				  <a class="consultup-icon" href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>"><i class="far fa-newspaper"></i> <?php esc_html_e('by','consultup'); ?>
				<?php the_author(); ?>
				</a></div>

				<?php $consultup_post_content_type = get_theme_mod('consultup_post_content_type','content'); 
				if($consultup_post_content_type == 'content') {
				 the_content(__('Read More','consultup'));
					wp_link_pages( array( 'before' => '<div class="link btn-theme">' . __( 'Pages:', 'consultup' ), 'after' => '</div>' ) ); }
					elseif($consultup_post_content_type == 'excerpt')
					{ ?>
						<p><?php echo icyclub_news_excerpt(); ?></p>

					<?php }
					?>
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
		$section_priority = apply_filters( 'icycp_consultup_homepage_section_priority', 15, 'icycp_consultup_news' );
		add_action( 'icycp_consultup_homepage_sections', 'icycp_consultup_news', absint( $section_priority ) );
}