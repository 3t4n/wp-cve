<?php
/* --------------------------------------
=========================================
Slider Section
=========================================
-----------------------------------------*/
if ( ! function_exists( 'icycp_yoga_slider' ) ) :
	function icycp_yoga_slider() {
		$slider_image_one = get_theme_mod('slider_image_one');
		$yoga_slider_overlay_color = get_theme_mod('yoga_slider_overlay_color',);
		$yoga_slider_title_one = get_theme_mod('yoga_slider_title_one','We are Best in Premium Consulting Services');
		$yoga_slider_discription_one = get_theme_mod('yoga_slider_discription_one','we bring the proper people along to challenge established thinking and drive transformation.');
		$yoga_slider_btn_txt_one = get_theme_mod('yoga_slider_btn_txt_one','Read More');
		$yoga_slider_btn_link_one = get_theme_mod('yoga_slider_btn_link_one',esc_url('#'));
		$yoga_slider_btn_target_one = get_theme_mod('yoga_slider_btn_target_one',false);



    $defaults = array(
      array(
      'slider_title'      => esc_html__( 'You can simply control what goes ahead inside', 'yoga' ),
      'slider_desc'       => esc_html__( 'One morning, when Gregor Samsa woke from troubled dreams, he found himself transformed in his bed into a horrible vermin..', 'yoga' ),
      'btnone'      => __('Read More','yoga'),
      'btnonelink'       => '#',
      'image_uri'  => ICYCP_PLUGIN_URL .'inc/yoga/images/slider/slider1.jpg',
      'open_btnone_new_window' => 'no',
      ),

      array(
      'slider_title'      => esc_html__( 'Transform your body with a yoga coach', 'yoga' ),
      'slider_desc'       => esc_html__( 'One morning, when Gregor Samsa woke from troubled dreams, he found himself transformed in his bed into a horrible vermin..', 'yoga' ),
      'btnone'      => __('Read More','yoga'),
      'btnonelink'       => '#',
      'image_uri'  => ICYCP_PLUGIN_URL .'inc/yoga/images/slider/slider2.jpg',
      'open_btnone_new_window' => 'no',
      ),

      array(
      'slider_title'      => esc_html__( 'Confinement and find your brain', 'yoga' ),
      'slider_desc'       => esc_html__( 'One morning, when Gregor Samsa woke from troubled dreams, he found himself transformed in his bed into a horrible vermin..', 'yoga' ),
      'btnone'      => __('Read More','yoga'),
      'btnonelink'       => '#',
      'image_uri'  => ICYCP_PLUGIN_URL .'inc/yoga/images/slider/slider1.jpg',
      'open_btnone_new_window' => 'no',
      ),
    );

    
    $slider_widget_data = get_option('widget_yoga_slider-widget', $defaults );

    $arr = array(); //create empty array

    $i = 0;
    foreach(array_reverse($slider_widget_data) as $widget_data) {

      if($i == 4){
        break;
      }
      $i++;

      if(isset($slider_widget_data['_multiwidget'])){


        if($widget_data == $slider_widget_data['_multiwidget']){


        }else{

          $arr[] = array(
            'title' => isset($widget_data['slider_title']) ? $widget_data['slider_title'] : 0,
            'text' => isset($widget_data['slider_desc']) ? $widget_data['slider_desc'] : 0,
            'button_text' => isset($widget_data['btnone']) ? $widget_data['btnone'] : 0,
            'link' => isset($widget_data['btnonelink']) ? $widget_data['btnonelink'] : 0,
            'image_url' => isset($widget_data['image_uri']) ? $widget_data['image_uri'] : 0,
            'open_new_tab' => isset($widget_data['open_btnone_new_window']) ? $widget_data['open_btnone_new_window'] : 0,
          );//assign each sub-array to the newly created array
          
        }

      }else{

        $arr[] = array(
          'title' => isset($widget_data['slider_title']) ? $widget_data['slider_title'] : 0,
          'text' => isset($widget_data['slider_desc']) ? $widget_data['slider_desc'] : 0,
          'button_text' => isset($widget_data['btnone']) ? $widget_data['btnone'] : 0,
          'link' => isset($widget_data['btnonelink']) ? $widget_data['btnonelink'] : 0,
          'image_url' => isset($widget_data['image_uri']) ? $widget_data['image_uri'] : 0,
          'open_new_tab' => isset($widget_data['open_btnone_new_window']) ? $widget_data['open_btnone_new_window'] : 0,
        );

      }

      
      
    } 

    $defaults = json_encode( $arr);

    $slide_options = get_theme_mod('yoga_slider_content', $defaults);

    
    $yoga_slider_enable = get_theme_mod('yoga_slider_enable', 1);
		if( $yoga_slider_enable != '0' ){	
		
		?>
	
	<!--== Home Slider ==-->
<section  class="yoga-slider-warraper">
              <!--== ta-slider ==-->
              <div id="yoga-slider" class="bs swiper-container"> 
                    <!--item-->
                    <div class="swiper-wrapper">
              <?php  $slide_options = json_decode($slide_options); ?>

                  

         <?php     if( $slide_options!='' )
			{
		foreach($slide_options as $slide_iteam){ 

      $title = ! empty( $slide_iteam->title ) ?  $slide_iteam->title : '';

      $description =  ! empty( $slide_iteam->text ) ?  $slide_iteam->text : '';

      $button_text =  ! empty( $slide_iteam->button_text ) ?  $slide_iteam->button_text : '';

      $image_url =  ! empty( $slide_iteam->image_url ) ?  $slide_iteam->image_url : '';

      $open_new_tab =  ! empty( $slide_iteam->open_new_tab ) ?  $slide_iteam->open_new_tab : '';

      $button_link =  ! empty( $slide_iteam->link ) ?  $slide_iteam->link : '';

      ?>
  
                    <div class="bs-slide swiper-slide" style="background-image: url('<?php echo $image_url; ?>');">
                      <!--slide inner-->
                      <div class="yoga-slider-inner" style="background:<?php echo $yoga_slider_overlay_color;?>;">
                        <div class="container inner-table">
                          <div class="inner-table-cell">
                            <!--slide content area-->
                            <div class="slide-caption">
                              <!--slide box style-->
                              
                                      <?php 
                                if ( ! empty( $title )) { ?>
                                <h1><?php echo $title;  ?></h1>
                                <?php } 
                                if ( ! empty( $description )  ) {
                                ?>

                                        <div class="description hidden-xs">
                                          <p><?php echo $description; ?></p>
                                        </div>
                              <?php } if($button_text) {?>
                                        <a <?php if($button_link) { ?> href="<?php echo $button_link; } ?>" 
                                  <?php if($open_new_tab) { ?> target="_blank" <?php } ?> class="btn btn-tislider hidden-xs">
                                  <?php if($button_text) { echo $button_text; } ?></a>
                              <?php } ?>	
                                <!--/slide box style-->
                            <!--/slide content area-->
                          </div>
                        </div>
                     </div>
                      <!--/slide inner-->
              </div> 
        </div> 
	<?php 	}  }   else { }


         ?> </div>
              
            <!--/ta-slider--> 
            <!-- Add Arrows -->
                
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <!-- swiper-pagination -->
                
                <div class="swiper-pagination"></div>
                
                <!-- /swiper-pagination -->
  </section>

            
<div class="clearfix"></div>

                <?php  } ?>                
	
		<?php }
endif;

if ( function_exists( 'icycp_yoga_slider' ) ) {
$homepage_section_priority = apply_filters( 'icycp_yoga_homepage_section_priority', 10, 'icycp_yoga_slider' );
add_action( 'icycp_yoga_homepage_sections', 'icycp_yoga_slider', absint( $homepage_section_priority ) );
}
/* --------------------------------------
=========================================
Service Section
=========================================
-----------------------------------------*/
if ( ! function_exists( 'icycp_yoga_service' ) ) :

	function icycp_yoga_service() {
    $yoga_service_overlay_color = get_theme_mod('yoga_service_overlay_color','#fff');
    $yoga_service_text_color = get_theme_mod('yoga_service_text_color', '#000');
		$yoga_service_title = get_theme_mod('yoga_service_title',__('Why We Best in Yoga','icyclub'));
		$yoga_service_subtitle = get_theme_mod('yoga_service_subtitle',' Lorem ipsum dolor sit amet, consectetur adipiscing elit Pull in ten extra bodies to help.');
	

    $defaults = array(
      array(
      'fa_icon' => 'fa fa-child',	
      'service_title'      => esc_html__( 'Lotus position', 'yoga' ),
      'service_desc'       => esc_html__( 'laoreet ipsum eu laoreet. ugiignissimat Vivamus.', 'yoga' ),
      'btnmore'      => __('Read More','yoga'),
      'btnlink'       => '#',
      'open_new_window' => 'no',
      ),

      array(
        'fa_icon' => 'fa fa-handshake-o',	
        'service_title'      => esc_html__( 'Bakasana', 'yoga' ),
        'service_desc'       => esc_html__( 'laoreet Pellentesque molestie laoreet laoreet.', 'yoga' ),
        'btnmore'      => __('Read More','yoga'),
        'btnlink'       => '#',
        'open_new_window' => 'no',
      ),
  
      array(
        'fa_icon' => 'fa fa-thumbs-up',	
        'service_title'      => esc_html__( 'Handstand', 'yoga' ),
        'service_desc'       => esc_html__( 'laoreet Pellentesque molestie laoreet laoreet.', 'yoga' ),
        'btnmore'      => __('Read More','yoga'),
        'btnlink'       => '#',
        'open_new_window' => 'no',
      ),		



    );


    $service_widget_data = get_option( 'widget_yoga_service_widget', $defaults );

    $arr = array(); //create empty array

    $i = 0;

    foreach(array_reverse($service_widget_data) as $widget_data) {

      if($i == 4){
        break;
      }
      $i++;

      if(isset($service_widget_data['_multiwidget'])){

        if($widget_data == $service_widget_data['_multiwidget']){


        }else{

          $arr[] = array(
            'icon_value' => isset($widget_data['fa_icon']) ? $widget_data['fa_icon'] : 0,
            'title' => isset($widget_data['service_title']) ? $widget_data['service_title'] : 0,
            'text' => isset($widget_data['service_desc']) ? $widget_data['service_desc'] : 0,
            'button_text' => isset($widget_data['btnmore']) ? $widget_data['btnmore'] : 0,
            'link' => isset($widget_data['btnlink']) ? $widget_data['btnlink'] : 0,
            'open_new_tab' => isset($widget_data['open_new_window']) ? $widget_data['open_new_window'] : 0,
          );//assign each sub-array to the newly created array

        }
        
      }else{

        $arr[] = array(
          'icon_value' => isset($widget_data['fa_icon']) ? $widget_data['fa_icon'] : 0,
          'title' => isset($widget_data['service_title']) ? $widget_data['service_title'] : 0,
          'text' => isset($widget_data['service_desc']) ? $widget_data['service_desc'] : 0,
          'button_text' => isset($widget_data['btnmore']) ? $widget_data['btnmore'] : 0,
          'link' => isset($widget_data['btnlink']) ? $widget_data['btnlink'] : 0,
          'open_new_tab' => isset($widget_data['open_new_window']) ? $widget_data['open_new_window'] : 0,
        );

      }
      
    }
     
      
    $defaults = json_encode( $arr);


    $yoga_service_enable = get_theme_mod('yoga_service_enable', 1);
    $service_option = get_theme_mod('yoga_service_content', $defaults);

		if($yoga_service_enable != 0)
		{	
		
      $service_option = json_decode($service_option);

    ?>
<!-- Section Title -->
<style>
  #service .yoga-heading h3 , #service .yoga-heading p{
    color: <?php echo $yoga_service_text_color;  ?>;
  }
</style>
<section id="service" class="yoga-section text-center" style="background-color: <?php echo $yoga_service_overlay_color; ?>">
<?php if($service_option != ''){ ?>
  <div class="overlay">
	  <div class="container">		
      <?php if( ($yoga_service_title) || ($yoga_service_subtitle)!='' ) { ?>
        <div class="row">
          <div class="col-md-12 wow fadeInDown animated padding-bottom-20" >
            <div class="yoga-heading">
              <h3 class="yoga-heading-inner"><?php echo $yoga_service_title; ?></h3>
              <p><?php echo $yoga_service_subtitle; ?></p>
            </div>
          </div>
        </div>
	  	  <!-- /Section Title -->
		  <?php } ?>
		  <div class="row">
        <?php foreach($service_option as $service_item){ 

          $icon_value =  ! empty( $service_item->icon_value ) ?  $service_item->icon_value : 'fa fa-thumbs-up';   

          $title = ! empty( $service_item->title ) ?  $service_item->title : '';

          $description =  ! empty( $service_item->text ) ?  $service_item->text : '';

          $button_text =  ! empty( $service_item->button_text ) ?  $service_item->button_text : '';

          $open_new_tab =  ! empty( $service_item->open_new_tab ) ?  $service_item->open_new_tab : '';

          $button_link =  ! empty( $service_item->link ) ?  $service_item->link : '';
         ?>

			   <div class="col-sm-4 col-md-4 swing animated ">
          <div class="yoga-service">
            <div class="yoga-service-inner">
              <div class="ser-icon"> <i class="<?php echo  $icon_value; ?>"></i> </div>
              <h3><?php echo $title; ?></h3>
              <p><?php echo $description; ?></p>
              <?php 
              if($button_text !='')
              { ?>
              <a href="<?php echo esc_url($button_link); ?>" <?php if($open_new_tab == true){ echo 'target="_blank"';}?> class="btn btn-theme-three"><?php echo $button_text; ?></a>	
              <?php } ?>
					  </div>
          </div>
			   </div>  
       <?php   } ?>
      </div>
	  </div>
  </div>
<?php } ?>
</section>
<?php		} }

endif;
if ( function_exists( 'icycp_yoga_service' ) ) {
	$section_priority = apply_filters( 'icycp_yoga_homepage_section_priority', 11, 'icycp_yoga_service' );
	add_action( 'icycp_yoga_homepage_sections', 'icycp_yoga_service', absint( $section_priority ) );
}

/* --------------------------------------
=========================================
Callout Section
=========================================
-----------------------------------------*/
if ( ! function_exists( 'icycp_yoga_callout' ) ) :

	function icycp_yoga_callout() {
		
		$yoga_callout_background = get_theme_mod('yoga_callout_background',ICYCP_PLUGIN_URL .'inc/yoga/images/callout/callout-back.jpg');
		$yoga_callout_overlay_color = get_theme_mod('yoga_callout_overlay_color','#070b2be0');
		$yoga_callout_title = get_theme_mod('yoga_callout_title',__('Certified Yoga Professionals. Try Today!','yoga'));
    $yoga_callout_text_color = get_theme_mod('yoga_callout_text_color','#fff');
		$yoga_callout_description = get_theme_mod('yoga_callout_description','We must explain to you how all this mistaken idea of denouncing pleasure');
		$yoga_callout_button_one_label = get_theme_mod('yoga_callout_button_one_label',__('Get Started Now!','yoga'));
		$yoga_callout_button_one_link = get_theme_mod('yoga_callout_button_one_link','#');
		$yoga_callout_button_one_target = get_theme_mod('yoga_callout_button_one_target',true);
		$yoga_callout_enable = get_theme_mod('yoga_callout_enable', 1 );
		if($yoga_callout_enable != 0){
		if($yoga_callout_background != '') { 
		?>
<style>.yoga-callout h3, .yoga-callout p { color: <?php echo esc_url($yoga_callout_text_color);?>;} </style>		
<section class="yoga-callout" style="background-image:url('<?php echo esc_url($yoga_callout_background);?>');">
<?php } else { ?>
<section class="yoga-callout">
<?php } ?>
<div class="overlay" style="background:<?php echo $yoga_callout_overlay_color;?>">
      <!--container-->
      <div class="container">
        <!--row-->
        <div class="row">
          <!--ta-callout-inner-->
            <div class="yoga-callout-inner text-xs text-center">
                <h3 class="padding-bottom-30"><?php echo $yoga_callout_title;  ?></h3>
    	        <p class="padding-bottom-50"><?php echo $yoga_callout_description;  ?></p>
            <a href="<?php echo $yoga_callout_button_one_link ?>"  target="_blank" class="btn btn-theme margin-bottom-10"><?php echo $yoga_callout_button_one_label; ?></a>
            </div>
        </div>
          <!--ta-callout-inner-->
        </div>
        <!--/row-->
</div>
      <!--/container-->
</section>
<!-- /callout Section -->

<div class="clearfix"></div>	
<?php } }

endif;

  if ( function_exists( 'icycp_yoga_callout' ) ) {
  $section_priority = apply_filters( 'icycp_yoga_homepage_section_priority', 12, 'icycp_yoga_callout' );
  add_action( 'icycp_yoga_homepage_sections', 'icycp_yoga_callout', absint( $section_priority ) );
	}


/* --------------------------------------
=========================================
Testimonial Section
=========================================
-----------------------------------------*/
if ( ! function_exists( 'icycp_yoga_testimonial' ) ) :

	function icycp_yoga_testimonial() {


$yoga_testimonial_section_title = get_theme_mod('yoga_testimonial_section_title','Happy Customers');
$yoga_testimonial_section_discription= get_theme_mod('yoga_testimonial_section_discription','laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.');
$yoga_testimonial_bg_img = get_theme_mod('yoga_testimonial_bg_img');
$yoga_testimonial_overlay_color = get_theme_mod('yoga_testimonial_overlay_color', '#fff');
$yoga_testimonial_text_color = get_theme_mod('yoga_testimonial_text_color',);
  
$defaults = array(
  array(
  'title' => 'Williams Moore',	
  'designation2'      => ' Company inc',
  'text'       => 'Vestibulum quis porttitor dui! viverra nunc mi, Aliquam condimentum mattis neque sed pretium Aliquam condimentum mattis neque sed pretiumAliquam condimentum mattis neque sed pretium',
  'designation' => __('Creative Designer','yoga'),
  'image_url'  => ICYCP_PLUGIN_URL .'inc/yoga/images/testimonial/testi1.jpg',
  'open_new_tab' => 'no',
  ),

  array(
  'title' => 'Sara Williams ',	
  'designation2'      => ' Company inc',
  'text'       => 'Vestibulum quis porttitor dui! viverra nunc mi, Aliquam condimentum mattis neque sed pretium Aliquam condimentum mattis neque sed pretiumAliquam condimentum mattis neque sed pretium',
  'designation' => __('Creative Designer','yoga'),
  'image_url'  => ICYCP_PLUGIN_URL .'inc/yoga/images/testimonial/testi3.jpg',
  'open_new_tab' => 'no',
  ),

  array(
  'title' => 'Williams Moore',	
  'designation2'      => ' Company inc',
  'text'       => 'Vestibulum quis porttitor dui! viverra nunc mi, Aliquam condimentum mattis neque sed pretium Aliquam condimentum mattis neque sed pretiumAliquam condimentum mattis neque sed pretium',
  'designation' => __('Creative Designer','yoga'),
  'image_url'  => ICYCP_PLUGIN_URL .'inc/yoga/images/testimonial/testi2.jpg',
  'open_new_tab' => 'no',
  ),		
);
  $defaults = json_encode($defaults);
  $testimonial_option = get_theme_mod('yoga_testimonial_content', $defaults);
  $testimonial_option = json_decode($testimonial_option);

  $yoga_testimonial_section_enable = get_theme_mod('yoga_testimonial_section_enable', 1);
  if($yoga_testimonial_section_enable != 0)
  { 
 
?>

<section class="testimonials-section yoga-section" style="background-image:url('<?php echo esc_url($yoga_testimonial_bg_img);?>');">
<?php } else { ?>
<section class="testimonials-section">
<?php } ?>    
    <!--overlay-->
    <div class="overlay"style="background-color:<?php echo $yoga_testimonial_overlay_color; ?>;">
      <!--container-->
      <div class="container">
        <!--row-->
        <div class="row">
          <div class="col-md-12 wow fadeInDown  padding-bottom-20">
            <div class="yoga-heading" style="color: <?php echo $yoga_testimonial_text_color?>;">
              <h3 class="yoga-heading-inner"  style="color: <?php echo $yoga_testimonial_text_color?>;"><?php echo $yoga_testimonial_section_title; ?></h3>
			  <p><?php echo $yoga_testimonial_section_discription; ?></p>
            </div>
          </div>
        </div>
        <!--/row-->
        <!--content-testimonials-->
        <div class="row">
        <?php  foreach($testimonial_option as $testimonial_iteam){ 
					
          $title = ! empty( $testimonial_iteam->title ) ?
           $testimonial_iteam->title : '';	
          $test_desc = ! empty( $testimonial_iteam->text ) ?
           $testimonial_iteam->text : '';
          $designation = ! empty( $testimonial_iteam->designation ) ?
           $testimonial_iteam->designation : '';
          $designation2 = ! empty( $testimonial_iteam->designation2 ) ?
           $testimonial_iteam->designation2 : '';
           $image_url = ! empty( $testimonial_iteam->image_url ) ? $testimonial_iteam->image_url : '';?>
            <!--item-->
 
            <div class="col">
            <div class="testimonials_qute">
              <div class="sub-qute">
               <div class="qute_icons">
                 <i aria-hidden="true" class="fa fa-quote-left"></i>
               </div>
                <div class="context">
                  <div class="clearfix"></div>
                  <p><?php echo $test_desc?></p>             
                </div>
                <div class="ta-client-qute">
                  <span class="ta-client">
                    <img src="<?php echo $image_url; ?>" alt="<?php ?>">
                  </span>
                  <div class="ti-client-info">  
                    <span class="user-title">
                      <?php echo $title; ?>
                      </span>
                      <span class="user-designation">
                      <?php echo $designation ?> ,
                      <a href="#"><?php echo $designation2 ?></a>
                      </span> 
                    </div>
                </div>
              </div> 
            </div>
          </div>
          <?php
        } 
		   ?>
         
			 
          <!--/testimonial-->
        </div>
        <!--/content-testimonials-->
      </div>
      <!--/container-->
    </div>
    <!--/overlay-->
  </section>

		<?php
	} 

endif;

		if ( function_exists( 'icycp_yoga_testimonial' ) ) {
		$section_priority = apply_filters( 'icycp_yoga_homepage_section_priority', 13, 'icycp_yoga_testimonial' );
		add_action( 'icycp_yoga_homepage_sections', 'icycp_yoga_testimonial', absint( $section_priority ) );
}

/* --------------------------------------
=========================================
Latest News Section
=========================================
-----------------------------------------*/
if ( ! function_exists( 'icycp_yoga_news' ) ) :

	function icycp_yoga_news() {

    $yoga_news_enable = get_theme_mod('yoga_news_enable', 1);
    $disable_news_meta = get_theme_mod('disable_news_meta','false');
    $yoga_news_title = get_theme_mod('yoga_news_title', 'Latest News');
    $yoga_news_subtitle = get_theme_mod('yoga_news_subtitle', 'laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.');
    $yoga_news_background = get_theme_mod('yoga_news_background',);
    $yoga_news_overlay_color = get_theme_mod('yoga_news_overlay_color','#fafafa' );
    $yoga_news_text_color = get_theme_mod('yoga_news_text_color');
      if($yoga_news_enable !=0)
      { $yoga_total_posts = get_option('posts_per_page'); /* number of latest posts to show */
      
      if( !empty($yoga_total_posts) && ($yoga_total_posts > 0) ):
      ?>
    
    <style>
      
      .yoga-blog-section .yoga-heading  h3, .yoga-blog-section .yoga-heading p{
        color: <?php echo $yoga_news_text_color; ?>;
      }
    </style>

    <section id="blog" class="yoga-blog-section" style="background-image:url('<?php echo esc_url($yoga_news_background);?>');">
      <div class="overlay" style="background:<?php echo $yoga_news_overlay_color; ?>;">
        <div class="container">
          <div class="row">
            <div class="col-md-12 padding-bottom-50 text-center">
              <div class="yoga-heading"  >
                <?php 
                if( !empty($yoga_news_title) ):
                  echo '<h3 class="yoga-heading-inner">'.esc_attr($yoga_news_title).'</h3>';
                endif;  
                if( !empty($yoga_news_subtitle) ):
                  echo '<p class="title">'.esc_attr($yoga_news_subtitle).'</p>';
                endif; ?> 
              </div>
            </div>
          </div>
          <div class="clear"></div>
          <div class="row">
            <div id="home-news">
            <?php $news_select = get_theme_mod('news_select',3);
             $yoga_latest_loop = new WP_Query(array( 'post_type' => 'post', 'posts_per_page' => $news_select, 'order' => 'DESC','ignore_sticky_posts' => true, ''));
              if ( $yoga_latest_loop->have_posts() ) :
               while ( $yoga_latest_loop->have_posts() ) : $yoga_latest_loop->the_post();?>
           <div class="col-md-4">
              <div class="yoga-blog-post-box">
                <?php if(has_post_thumbnail()): ?>
                <a title="<?php the_title_attribute(); ?>" href="<?php esc_url(the_permalink()); ?>" class="yoga-blog-thumb"> 
                  <?php $defalt_arg =array('class' => "img-fluid"); ?>
                  <?php the_post_thumbnail('', $defalt_arg); ?>
                  <span class="yoga-blog-date"><?php echo get_the_date('M'); ?> <?php echo get_the_date('j'); ?>,<?php echo get_the_date('Y'); ?> </span>
                </a>  
                <?php endif; ?>
              <article class="small">
                  <h1 class="title"> <a href="<?php echo esc_url(get_permalink()); ?>" title="<?php the_title_attribute(); ?>"><?php echo get_the_title() ?></a> </h1>
                  <?php if($disable_news_meta !=true) {?>
               <div class="yoga-blog-category">
                <?php $cat_list = get_the_category_list();
                if(!empty($cat_list)) { ?>
                <?php the_category(', '); ?>
                <?php } ?>
                <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) );?>"> <?php echo esc_html_e('by','yoga'); ?>
                <?php the_author(); ?>
                </a> 
               </div>
               <?php } ?>
              </article>
              </div>
            </div>
        <?php endwhile; endif;	wp_reset_postdata(); ?>
          </div>
          </div>
          </div>
        </div>
        <!-- /.container --> 
      </div>
    </section>
    <?php endif; ?>
    <?php } 

  }

endif;

if ( function_exists( 'icycp_yoga_news' ) ) {
  $section_priority = apply_filters( 'icycp_yoga_homepage_section_priority', 14, 'icycp_yoga_news' );
  add_action( 'icycp_yoga_homepage_sections', 'icycp_yoga_news', absint( $section_priority ) );
}