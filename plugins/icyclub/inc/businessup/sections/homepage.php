<?php
/**
 * Slider section
 */
if ( ! function_exists( 'icycp_businessup_slider' ) ) :
	function icycp_businessup_slider() {
		$slider_image_one = get_theme_mod('slider_image_one',ICYCP_PLUGIN_URL .'inc/businessup/images/slider/slider1.jpg');
		$businessup_slider_overlay_color = get_theme_mod('businessup_slider_overlay_color', 'rgba(0, 0,0, 0.4)');
		$businessup_slider_title_one = get_theme_mod('businessup_slider_title_one','We are Best in Premium Consulting Services');
		$businessup_slider_discription_one = get_theme_mod('businessup_slider_discription_one','we bring the proper people along to challenge established thinking and drive transformation.');
		$businessup_slider_btn_txt_one = get_theme_mod('businessup_slider_btn_txt_one','Read More');
		$businessup_slider_btn_link_one = get_theme_mod('businessup_slider_btn_link_one',esc_url('#'));
		$businessup_slider_btn_target_one = get_theme_mod('businessup_slider_btn_target_one',false);



    $defaults = array(
      array(
      'slider_title'      => esc_html__( 'We help from our fleet Send it anywhere', 'businessup' ),
      'slider_desc'       => esc_html__( 'we bring the proper people along to challenge established thinking and drive transformation.', 'businessup' ),
      'btnone'      => __('Read More','businessup'),
      'btnonelink'       => '#',
      'image_uri'  => ICYCP_PLUGIN_URL .'inc/businessup/images/slider/slider1.jpg',
      'open_btnone_new_window' => 'no',
      ),

      array(
        'slider_title'      => esc_html__( 'Transport your goods Around the World', 'businessup' ),
        'slider_desc'       => esc_html__( 'we bring the proper people along to challenge established thinking and drive transformation.', 'businessup' ),
        'btnone'      => __('Read More','businessup'),
        'btnonelink'       => '#',
        'image_uri'  => ICYCP_PLUGIN_URL .'inc/businessup/images/slider/slider2.jpg',
        'open_btnone_new_window' => 'no',
        ),

      array(
        'slider_title'      => esc_html__( 'Transport your goods Around the World', 'businessup' ),
        'slider_desc'       => esc_html__( 'we bring the proper people along to challenge established thinking and drive transformation.', 'businessup' ),
        'btnone'      => __('Read More','businessup'),
        'btnonelink'       => '#',
        'image_uri'  => ICYCP_PLUGIN_URL .'inc/businessup/images/slider/slider3.jpg',
        'open_btnone_new_window' => 'no',
        ),

    );

    
    $service_widget_data = get_option('widget_businessup_slider-widget', $defaults );

    $arr = array(); //create empty array

    $i = 0;

    foreach(array_reverse($service_widget_data) as $widget_data) {

      if($i == 3){
        break;
      }
      $i++;

      if(isset($service_widget_data['_multiwidget'])){


        if($widget_data == $service_widget_data['_multiwidget']){


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

    $slide_options = get_theme_mod('businessup_slider_content', $defaults);

    
    $businessup_slider_enable = get_theme_mod('businessup_slider_enable', 1);
		if( $businessup_slider_enable != 0 ){	
		
		?>
	
	<!--== Home Slider ==-->
<section id="businessup-slider" class="businessup-slider-warraper bs swiper-container">
              <!--== ta-slider ==-->
              <div class="swiper-wrapper"> 
                    <!--item-->
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
      <div class="swiper-slide item">
                    <div class="ti-slide" style="background-image: url('<?php echo $image_url; ?>');">
                      <!--slide inner-->
                      <div class="businessup-slider-inner"  style="background: <?php echo $businessup_slider_overlay_color;?>; ">
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

if ( function_exists( 'icycp_businessup_slider' ) ) {
$homepage_section_priority = apply_filters( 'icycp_businessup_homepage_section_priority', 10, 'icycp_businessup_slider' );
add_action( 'icycp_businessup_homepage_sections', 'icycp_businessup_slider', absint( $homepage_section_priority ) );

}


/**
 * calltoaction
 */
if ( ! function_exists( 'icycp_businessup_calltoaction' ) ) :

  function icycp_businessup_calltoaction() {
    
    $businessup_calltoaction_title = get_theme_mod('businessup_calltoaction_title',__('Make A Difference With Expert Team','icyclub'));
    $businessup_calltoaction_subtitle = get_theme_mod('businessup_calltoaction_subtitle','laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.');
    $calltoaction_section_enable = get_theme_mod('calltoaction_section_enable', 1);
    $businessup_calltoaction_overlay_color = get_theme_mod('businessup_calltoaction_overlay_color',  'rgba(0, 41, 84, 0.8)');
    $businessup_calltoaction_text_color = get_theme_mod('businessup_calltoaction_text_color', '#fff');
    if($calltoaction_section_enable != 0){
    ?>
    <style>
      .ta-calltoaction-box-info h5, .ta-calltoaction-box-info p{
        color: <?php echo $businessup_calltoaction_text_color; ?>;
      }
    </style>
<section class="ta-calltoaction" >
    <div class="overlay" style="background-color: <?php echo $businessup_calltoaction_overlay_color; ?>;" >
    <div class="container">
      <div class="row">
        <div class="col-md-9 text-xs">
          <?php if( ($businessup_calltoaction_title) || ($businessup_calltoaction_subtitle)!='' ) { ?>
          <!-- Section Title -->
          <div class="ta-calltoaction-box-info">
            <h5><?php echo $businessup_calltoaction_title; ?></h5>
            <p><?php echo $businessup_calltoaction_subtitle; ?></p>
         </div>
         <!-- /Section Title -->
         <?php } ?>

        </div>
        <div class="col-md-3 text-xs">
          <?php 
          $businessup_calltoaction_button_one_link = get_theme_mod('businessup_calltoaction_button_one_link','#');
          $businessup_calltoaction_button_one_label = get_theme_mod('businessup_calltoaction_button_one_label',__('Lets Start'));
          $businessup_calltoaction_button_one_target = get_theme_mod('businessup_calltoaction_button_one_target',false);
          if($businessup_calltoaction_button_one_label !='')
          { ?>
          <a href="<?php echo esc_url($businessup_calltoaction_button_one_link); ?>" <?php if($businessup_calltoaction_button_one_target == true){ echo 'target="_blank"';}?> class="btn btn-theme-two"><?php echo $businessup_calltoaction_button_one_label ; ?></a>  
          <?php } ?> 
        </div>
      </div>
    </div>
  </div>
</section>

<div class="clearfix"></div>  
<?php } }

endif;

    if ( function_exists( 'icycp_businessup_calltoaction' ) ) {
    $section_priority = apply_filters( 'icycp_businessup_homepage_section_priority', 11, 'icycp_businessup_calltoaction' );
    add_action( 'icycp_businessup_homepage_sections', 'icycp_businessup_calltoaction', absint( $section_priority ) );

    } 

/*** Service */
if ( ! function_exists( 'icycp_businessup_service' ) ) :

	function icycp_businessup_service() {
    $service_overlay_section_color = get_theme_mod('service_overlay_section_color','#fff');
    $businessup_service_text_color = get_theme_mod('businessup_service_text_color', '#000');
		$businessup_service_title = get_theme_mod('businessup_service_title',__('Why We Best in Business Services','icyclub'));
		$businessup_service_subtitle = get_theme_mod('businessup_service_subtitle',' Lorem ipsum dolor sit amet, consectetur adipiscing elit Pull in ten extra bodies to help.');
	

    $defaults = array(
      array(
      'fa_icon' => 'fa fa-thumbs-up',	
      'service_title'      => esc_html__( 'Why We Best in Business Services', 'businessup' ),
      'service_desc'       => esc_html__( 'laoreet ipsum eu laoreet. ugiignissimat Vivamus.', 'businessup' ),
      'btnmore'      => __('Read More','businessup'),
      'btnlink'       => '#',
      'open_new_window' => 'no',
      ),

      array(
        'fa_icon' => 'fa fa-bank',	
        'service_title'      => esc_html__( 'Business Planning', 'businessup' ),
        'service_desc'       => esc_html__( 'laoreet Pellentesque molestie laoreet laoreet.', 'businessup' ),
        'btnmore'      => __('Read More','businessup'),
        'btnlink'       => '#',
        'open_new_window' => 'no',
      ),
  
      array(
        'fa_icon' => 'fa fa-bank',	
        'service_title'      => esc_html__( 'Financial Planning', 'businessup' ),
        'service_desc'       => esc_html__( 'laoreet Pellentesque molestie laoreet laoreet.', 'businessup' ),
        'btnmore'      => __('Read More','businessup'),
        'btnlink'       => '#',
        'open_new_window' => 'no',
      ),		



    );


    $service_widget_data = get_option( 'widget_businessup_service_widget', $defaults );

    $arr = array(); //create empty array

    $i = 0;

    foreach(array_reverse($service_widget_data) as $widget_data) {

      if($i == 3){
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


    $businessup_service_enable = get_theme_mod('businessup_service_enable', 1);
    $service_option = get_theme_mod('businessup_service_content', $defaults);

		if($businessup_service_enable != 0)
		{	
		
      $service_option = json_decode($service_option);

    ?>
	    <!-- Section Title -->
<section id="service" class="businessup-section text-center" style="background-color: <?php echo $service_overlay_section_color; ?>">
<?php if($service_option != ''){ ?>
  <div class="overlay">
	<div class="container">		
		<?php if( ($businessup_service_title) || ($businessup_service_subtitle)!='' ) { ?>
		<div class="row">
			<div class="col-md-12 wow fadeInDown animated padding-bottom-20" >
            <div class="businessup-heading" style="color: <?php echo $businessup_service_text_color;  ?>;">
              <h3 class="businessup-heading-inner"><?php echo $businessup_service_title; ?></h3>
			  <p><?php echo $businessup_service_subtitle; ?></p>
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

			  <div class="col-md-4 swing animated ">
                <div class="businessup-service">
                  <div class="businessup-service-inner">
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
  <?php } ?>
  </div>
</section>
<?php		} }

endif;
if ( function_exists( 'icycp_businessup_service' ) ) {
	$section_priority = apply_filters( 'icycp_businessup_homepage_section_priority', 11, 'icycp_businessup_service' );
	add_action( 'icycp_businessup_homepage_sections', 'icycp_businessup_service', absint( $section_priority ) );
}


/**
 * Callout section
 */
if ( ! function_exists( 'icycp_businessup_callout' ) ) :

	function icycp_businessup_callout() {
		
		$businessup_callout_background = get_theme_mod('businessup_callout_background',ICYCP_PLUGIN_URL .'inc/businessup/images/callout/callout-back.jpg');
		$businessup_callout_overlay_color = get_theme_mod('businessup_callout_overlay_color');
		$businessup_callout_title = get_theme_mod('businessup_callout_title',__('Trusted By Over 10,000 Worldwide Businesses. Try Today!','businessup'));
		$businessup_callout_description = get_theme_mod('businessup_callout_description','We must explain to you how all this misbusinessupken idea of denouncing pleasure');
		$businessup_callout_button_one_label = get_theme_mod('businessup_callout_button_one_label',__('Get Started Now!','businessup'));
		$businessup_callout_button_one_link = get_theme_mod('businessup_callout_button_one_link','#');
		$businessup_callout_button_one_target = get_theme_mod('businessup_callout_button_one_target',true);
    $businessup_callout_button_two_label = get_theme_mod('businessup_callout_button_two_label',__('Read More','businessup'));
		$businessup_callout_button_two_link = get_theme_mod('businessup_callout_button_two_link','#');
		$businessup_callout_button_two_target = get_theme_mod('businessup_callout_button_two_target',true);
		$businessup_callout_enable = get_theme_mod('businessup_callout_enable', 1 );
		if($businessup_callout_enable != 0){
		if($businessup_callout_background != '') { 
		?>		
<section class="businessup-callout" style="background-image:url('<?php echo esc_url($businessup_callout_background);?>');">
<?php } else { ?>
<section class="businessup-callout">
<?php } ?>
<div class="overlay" style="background:<?php echo $businessup_callout_overlay_color;?>">
      <!--container-->
      <div class="container">
        <!--row-->
        <div class="row">
          <!--ta-callout-inner-->
            <div class="businessup-callout-inner text-xs text-center">
                <h3 class="padding-bottom-30"><?php echo $businessup_callout_title;  ?></h3>
    	        <p class="padding-bottom-50"><?php echo $businessup_callout_description;  ?></p>
            <a href="<?php echo $businessup_callout_button_one_link ?>"  <?php if($businessup_callout_button_one_target) { ?>target="_blank" <?php } ?> class="btn btn-theme margin-bottom-10"><?php echo $businessup_callout_button_one_label; ?></a>
            <a href="<?php echo $businessup_callout_button_two_link ?>"  <?php if($businessup_callout_button_two_target) { ?>target="_blank" <?php } ?> class="btn btn-theme-two margin-bottom-10"><?php echo $businessup_callout_button_two_label; ?></a>
            </div>
        </div>
          <!--ta-callout-inner-->
        </div>
        <!--/row-->
</div>
      <!--/conbusinessupiner-->
</section>
<!-- /callout Section -->

<div class="clearfix"></div>	
<?php } }

endif;

		if ( function_exists( 'icycp_businessup_callout' ) ) {
		$section_priority = apply_filters( 'icycp_businessup_homepage_section_priority', 12, 'icycp_businessup_callout' );
		add_action( 'icycp_businessup_homepage_sections', 'icycp_businessup_callout', absint( $section_priority ) );

		}
	
//Testimonial
if ( ! function_exists( 'icycp_businessup_testimonial' ) ) :

	function icycp_businessup_testimonial() {

$testimonial_section_enable = get_theme_mod('testimonial_section_enable', 1);
if($testimonial_section_enable != 0)
{
  $defaults = array(
    array(
    'title'      => esc_html__( 'Professional Team', 'businessup' ),
    'text'       => esc_html__('Vestibulum quis porttitor dui! viverra nunc mi, Aliquam condimentum mattis neque sed pretium Aliquam condimentum mattis neque sed pretiumAliquam condimentum mattis neque sed pretium.', 'businessup' ),
    'subtitle'      => __('Ronald Thompson','businessup'),
    'designation'       => ' Developer',
    'image_url' => ICYCP_PLUGIN_URL .'/inc/businessup/images/testimonial/testi1.jpg',
    ),

    array(
    'title'      => esc_html__( 'Professional Team', 'businessup' ),
    'text'       => esc_html__('Vestibulum quis porttitor dui! viverra nunc mi, Aliquam condimentum mattis neque sed pretium Aliquam condimentum mattis neque sed pretiumAliquam condimentum mattis neque sed pretium.', 'businessup' ),
    'subtitle'      => __('Laura Walker','businessup'),
    'designation'       => ' Co-Founder',
    'image_url' => ICYCP_PLUGIN_URL .'/inc/businessup/images/testimonial/testi3.jpg',
    ),

    array(
    'title'      => esc_html__( 'Professional Team', 'businessup' ),
    'text'       => esc_html__('Vestibulum quis porttitor dui! viverra nunc mi, Aliquam condimentum mattis neque sed pretium Aliquam condimentum mattis neque sed pretiumAliquam condimentum mattis neque sed pretium.', 'businessup' ),
    'subtitle'      => __('Williams Moore','businessup'),
    'designation'       => ' Designer',
    'image_url' => ICYCP_PLUGIN_URL .'/inc/businessup/images/testimonial/testi2.jpg',
    ),

  );
  $defaults = json_encode($defaults);
$businessup_testimonials_title = get_theme_mod('businessup_testimonials_title','Our Clients Says');
$businessup_testimonials_subtitle= get_theme_mod('businessup_testimonials_subtitle','laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.');
$businessup_testimonials_overlay_color = get_theme_mod('businessup_testimonials_overlay_color', '#f5f5f5');
$businessup_testimonials_text_color = get_theme_mod('businessup_testimonials_text_color', '#000');
$testimonial_bg_overlay_enable = get_theme_mod('testimonial_bg_overlay_enable', true);
$businessup_testimonials_background = get_theme_mod('businessup_testimonials_background',ICYCP_PLUGIN_URL .'inc/businessup/images/callout/callout-back.jpg');
$businessup_testimonial_content = get_theme_mod('businessup_testimonial_content', $defaults);

$businessup_testimonial_content = json_decode($businessup_testimonial_content);
if($testimonial_bg_overlay_enable) {?>
<section class="testimonials-section businessup-section" style=" background-image: url('<?php echo esc_url($businessup_testimonials_background);?>')">
<!--overlay-->
<div class="overlay" style="background: <?php echo $businessup_testimonials_overlay_color; ?>;">
<?php } else { ?>
<section class="testimonials-section businessup-section" >
<!--overlay-->
<div class="overlay" style="background: <?php echo $businessup_testimonials_overlay_color; ?>;">
<?php } ?>

      <!--container-->
      <div class="container">
        <!--row-->
        <div class="row">
          <div class="col-md-12 wow fadeInDown  padding-bottom-20">
            <div class="businessup-heading" style="color: <?php echo $businessup_testimonials_text_color?>;">
              <h3 class="businessup-heading-inner"  style="color: <?php echo $businessup_testimonials_text_color?>;"><?php echo $businessup_testimonials_title; ?></h3>
			  <p><?php echo $businessup_testimonials_subtitle; ?></p>
            </div>
          </div>
        </div>
        <!--/row-->
        <!--content-testimonials-->
        <div class="row">
            <!--item-->
           <?php 
				 $testimonial_one_title=  get_theme_mod('testimonial_one_title','Professional Team');
				 $testimonial_one_desc = get_theme_mod('testimonial_one_desc','Vestibulum quis porttitor dui! viverra nunc mi, Aliquam condimentum mattis neque sed pretium Aliquam condimentum mattis neque sed pretiumAliquam condimentum mattis neque sed pretium');
				 $testimonial_one_thumb = get_theme_mod('testimonial_one_thumb',ICYCP_PLUGIN_URL .'inc/businessup/images/testimonial/testi1.jpg');
				 $testimonial_one_name = get_theme_mod('testimonial_one_name','Williams Moore');
				 $testimonial_one_designation = get_theme_mod('testimonial_one_designation','Creative Designer');
				 
				 $testimonial_two_title=  get_theme_mod('testimonial_two_title','Professional Team');
				 $testimonial_two_desc = get_theme_mod('testimonial_two_desc','Vestibulum quis porttitor dui! viverra nunc mi, Aliquam condimentum mattis neque sed pretium Aliquam condimentum mattis neque sed pretiumAliquam condimentum mattis neque sed pretium');
				 $testimonial_two_thumb = get_theme_mod('testimonial_two_thumb',ICYCP_PLUGIN_URL .'inc/businessup/images/testimonial/testi2.jpg');
				 $testimonial_two_name = get_theme_mod('testimonial_two_name','Williams Moore');
				 $testimonial_two_designation = get_theme_mod('testimonial_two_designation','Creative Designer');
		   
         $allowed_html = array(
          'br'     => array(),
          'em'     => array(),
          'strong' => array(),
          'b'      => array(),
          'i'      => array(),
          );

      foreach($businessup_testimonial_content as $testimonial_item){ 
					
          $title = ! empty( $testimonial_item->title ) ? apply_filters( 'businessup_translate_single_string',
           $testimonial_item->title, 'Testimonial section' ) : '';	
          $discription = ! empty( $testimonial_item->text ) ? apply_filters( 'businessup_translate_single_string',
           $testimonial_item->text, 'Testimonial section' ) : '';
          $designation = ! empty( $testimonial_item->designation ) ? apply_filters( 'businessup_translate_single_string',
           $testimonial_item->designation, 'Testimonial section' ) : '';
          $name = ! empty( $testimonial_item->subtitle ) ? apply_filters( 'businessup_translate_single_string',
           $testimonial_item->subtitle, 'Testimonial section' ) : '';
           $image = ! empty( $testimonial_item->image_url ) ? apply_filters( 'businessup_translate_single_string',
            $testimonial_item->image_url, 'Testimonial section' ) :  get_template_directory_uri().'/images/client-thumb.jpg';?>
            
          
            <!--item-->
            <div class="col-md-4">
              <div class="testimonials_qute">
              <div class="sub-qute">
              <div class="context">
                  <div class="clearfix"></div>
                  <h5><?php echo $title ?></h5>
                  <p><?php echo wp_kses( html_entity_decode( $discription ), $allowed_html ); ?></p>             
                </div>
              <div class="ta-client-qute">
                <div class="ta-client-details">
                  <span class="user-title">
                  <?php echo $name; ?>
                  </span>
                
                <span class="user-designation">
                  <?php echo $designation; ?>
                </span>
              </div>
              <span class="ta-client">
              <img src="<?php echo $testimonial_item->image_url; ?>"/>
              </span>
              </div>

              </div>
            </div>
          </div>
          <!--/item-->

<?php } ?>
            
			 
          <!--/testimonial-->
        </div>
        <!--/content-testimonials-->
      </div>
      <!--/conbusinessupiner-->
    </div>
    <!--/overlay-->
  </section>

		<?php
	} }

endif;

		if ( function_exists( 'icycp_businessup_testimonial' ) ) {
		$section_priority = apply_filters( 'icycp_businessup_homepage_section_priority', 14, 'icycp_businessup_testimonial' );
		add_action( 'icycp_businessup_homepage_sections', 'icycp_businessup_testimonial', absint( $section_priority ) );
}

//Testimonial
if ( ! function_exists( 'icycp_businessup_news' ) ) :

	function icycp_businessup_news() {

    $businessup_news_enable = get_theme_mod('businessup_news_enable', 1);
    $businessup_disable_news_meta = get_theme_mod('businessup_disable_news_meta', false);
    
    $businessup_news_subtitle = get_theme_mod('businessup_news_subtitle', 'laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.');
    $businessup_news_text_color1 = get_theme_mod('businessup_news_text_color', '#212121');

    $businessup_news_overlay_color = get_theme_mod('businessup_news_overlay_color', '#fff');
    
    $businessup_news_background = get_theme_mod('businessup_news_background',ICYCP_PLUGIN_URL .'inc/businessup/images/testimonial/testi1.jpg');

      if($businessup_news_enable !=0)
      { $businessup_total_posts = get_option('posts_per_page'); /* number of latest posts to show */
      
      if( !empty($businessup_total_posts) && ($businessup_total_posts > 0) ):
      ?>
    
    <!--==================== BLOG SECTION ====================-->
    <style type="text/css">

      .businessup-blog-section .overlay {

      background-color: <?php echo $businessup_news_overlay_color; ?>;

      }
      
      .businessup-blog-section .businessup-heading  h3, .businessup-blog-section .businessup-heading p{
        color: <?php echo $businessup_news_text_color1; ?>;
      }
    </style>

    
    <section  >
    <?php if($businessup_news_background) { ?>
<section id="blog" class="businessup-blog-section" style="background-image:url('<?php echo esc_url($businessup_news_background);?>');" style="background-color: <?php echo $businessup_news_overlay_color; ?>;">
<?php } else { ?>
<section id="blog" class="businessup-blog-section" style="background-color: <?php echo $businessup_news_overlay_color; ?>;">
<?php } ?>
      <div class="overlay" style="">
        <div class="container">
          <div class="row">
            <div class="col-md-12 padding-bottom-50 text-center">
              <div class="businessup-heading"  >
                <?php $businessup_news_title = get_theme_mod('businessup_news_title', 'Latest News');
              
                if( !empty($businessup_news_title) ):
                  echo '<h3 class="businessup-heading-inner">'.esc_attr($businessup_news_title).'</h3>';
                endif;  
              
               if( !empty($businessup_news_subtitle) ):
    
                  echo '<p class="subtitle">'.esc_attr($businessup_news_subtitle).'</p>';
    
                endif; ?> 
              </div>
            </div>
          </div>
          <div class="clear"></div>
          <div class="row">
            <div id="home-news">
            <?php $news_select = get_theme_mod('news_select',3);
             $businessup_latest_loop = new WP_Query(array( 'post_type' => 'post', 'posts_per_page' => $news_select, 'order' => 'DESC','ignore_sticky_posts' => true, ''));
              if ( $businessup_latest_loop->have_posts() ) :
               while ( $businessup_latest_loop->have_posts() ) : $businessup_latest_loop->the_post();?>
           <div class="col-md-4">
              <div class="businessup-blog-post-box">
                <?php if(has_post_thumbnail()): ?>
                <a title="<?php the_title_attribute(); ?>" href="<?php esc_url(the_permalink()); ?>" class="ta-blog-thumb"> 
                  <?php $defalt_arg =array('class' => "img-fluid"); ?>
                  <?php the_post_thumbnail('', $defalt_arg); ?>
                </a>  
                <?php endif; ?>
                <article class="small">
                  <h1 class="title"> <a href="<?php echo esc_url(get_permalink()); ?>" title="<?php the_title_attribute(); ?>"><?php echo get_the_title() ?></a> </h1>
                  <?php if($businessup_disable_news_meta !== false) { ?>
            <div class="ta-blog-category">
                    <span class="ta-blog-date"><?php echo get_the_date('M'); ?> <?php echo get_the_date('j'); ?>, </span>
                    <?php $cat_list = get_the_category_list();
                    if(!empty($cat_list)) { ?>
                    <?php the_category(' '); ?>
                    <?php } ?>
                    <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) );?>"> <?php echo esc_html_e('by','businessup'); ?>
                    <?php the_author(); ?>
                    </a> 
                  </div>
            <?php } ?>
          <?php the_excerpt(); ?>
                </article>
              </div>
            </div>
        <?php endwhile; endif;	wp_reset_postdata(); ?>
          </div>
            
          </div></div>
        </div>
        <!-- /.container --> 
      </div>
    </section>
    <?php endif; ?>
    <?php } 

  }

endif;

    if ( function_exists( 'icycp_businessup_news' ) ) {
      $section_priority = apply_filters( 'icycp_businessup_homepage_section_priority', 14, 'icycp_businessup_news' );
      add_action( 'icycp_businessup_homepage_sections', 'icycp_businessup_news', absint( $section_priority ) );
  }