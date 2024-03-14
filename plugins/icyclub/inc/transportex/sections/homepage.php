<?php
/**
 * Slider section
 */
if ( ! function_exists( 'icycp_transportex_slider' ) ) :
	function icycp_transportex_slider() {

    $defaults = array(
      array(
      'slider_title'      => '  We take care of your goods deliver World Wide',
      'slider_desc'       => esc_html__( ' Global logistics and transportation services via sea, land and air. We will protect you from risk and liability.', 'transportex' ),
      'btnone'      => __('Read More','transportex'),
      'btnonelink'       => '#',
      'image_uri'  => ICYCP_PLUGIN_URL .'inc/transportex/images/slider/slide01.jpg',
      'open_btnone_new_window' => 'no',
      'id'         => 'customizer_repeater_56d7ea7f40b96',
      ),
      array(
      'slider_title'      => 'Transport your goods Around the World',
      'slider_desc'       => esc_html__( ' Global logistics and transportation services via sea, land and air. We will protect you from risk and liability.', 'transportex' ),
      'btnone'      => __('Read More','transportex'),
      'btnonelink'       => '#',
      'image_uri'  => ICYCP_PLUGIN_URL .'inc/transportex/images/slider/slide02.jpg',
      'open_btnone_new_window' => 'no',
      'id'         => 'customizer_repeater_56d7ea7f40b97',
      ),
      array(
      'slider_title'      => 'We help world Wide  from our fleet Send it anywhere',
      'slider_desc'       => esc_html__( ' Global logistics and transportation services via sea, land and air. We will protect you from risk and liability.', 'transportex' ),
      'btnone'      => __('Read More','transportex'),
      'btnonelink'       => '#',
      'image_uri'  => ICYCP_PLUGIN_URL .'inc/transportex/images/slider/slide03.jpg',
      'open_btnone_new_window' => 'no',
      'id'         => 'customizer_repeater_56d7ea7f40b98',
      ),

      );

    
    $slider_widget_data = get_option('widget_transportex_slider-widget', $defaults );

    $arr = array(); //create empty array

    $i = 0;

    foreach(array_reverse($slider_widget_data) as $widget_data) {

      if($i == 3){
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

    $slide_options = get_theme_mod('transportex_slider_content', $defaults);
    $slider_overlay_color = get_theme_mod('slider_overlay_color', 'rgba(0,0,0,0.8)');
    
		$transportex_slider_enable = get_theme_mod('transportex_slider_enable', 1 );
		if($transportex_slider_enable != 0){	
		?>

	
	<!--== Home Slider ==-->
  <section class="ta-slider-warraper">
    <!--== ta-slider ==-->
    <div id="ta-slider" > 


    <?php  $slide_options = json_decode($slide_options); ?>
       <?php     if( $slide_options !='' )
			{
		foreach($slide_options as $slide_iteam){ 

      $title = ! empty( $slide_iteam->title ) ?  $slide_iteam->title : '';

      $description =  ! empty( $slide_iteam->text ) ?  $slide_iteam->text : '';

      $button_text =  ! empty( $slide_iteam->button_text ) ?  $slide_iteam->button_text : '';

      $image_url =  ! empty( $slide_iteam->image_url ) ?  $slide_iteam->image_url : '';

      $open_new_tab =  ! empty( $slide_iteam->open_new_tab ) ?  $slide_iteam->open_new_tab : '';

      $button_link =  ! empty( $slide_iteam->link ) ?  $slide_iteam->link : '';

      ?>
      <!--item-->
		 <div class="item">
        <!--slide image-->
        <figure> <img src="<?php echo $image_url; ?>" alt="image description"> </figure>
        <!--/slide image-->
        <!--slide inner-->
        <div class="ta-slider-inner" style="background:<?php echo esc_attr($slider_overlay_color);?>">
          <div class="container inner-table">
            <div class="inner-table-cell">
              <!--slide content area-->
              <div class="slide-caption">
                <!--slide box style-->
                
                 <?php 
					 if ( ! empty( $title ) || is_customize_preview() ) { ?>
					<h1><?php echo $title;  ?></h1>
					<?php } 
					if ( ! empty( $description ) || is_customize_preview() ) {
				  ?>
                  <div class="description hidden-xs">
                    <p><?php echo $description; ?></p>
                  </div>
				 <?php } if($button_text) {?>
                  <a <?php if($button_link) { ?> href="<?php echo $button_link;  ?>"<?php } ?> 
						<?php if($open_new_tab == 'yes' || $open_new_tab== '1') { ?> target="_blank" <?php } ?> class="btn btn-tislider hidden-xs">
						<?php if($button_text) { echo $button_text; } ?></a>
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
	<?php 	} ?>


    </div>
    <!--/ta-slider--> 
  </section>
<div class="clearfix"></div>	
	
		<?php  } }  } 
endif;

if ( function_exists( 'icycp_transportex_slider' ) ) {
$homepage_section_priority = apply_filters( 'icycp_transportex_homepage_section_priority', 10, 'icycp_transportex_slider' );
add_action( 'icycp_transportex_homepage_sections', 'icycp_transportex_slider', absint( $homepage_section_priority ) );

}


/**
 * calltoaction
 */
if ( ! function_exists( 'icycp_transportex_calltoaction' ) ) :

  function icycp_transportex_calltoaction() {
    
    $transportex_calltoaction_title = get_theme_mod('transportex_calltoaction_title',__('Make A Difference With Expert Team','icyclub'));
    $transportex_calltoaction_subtitle = get_theme_mod('transportex_calltoaction_subtitle','laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.');
    $transportex_calltoaction_overlay_color = get_theme_mod('transportex_calltoaction_overlay_color', '#50b9ce');
    $transportex_calltoaction_text_color = get_theme_mod('transportex_calltoaction_text_color', '#fff');
    $transportex_calltoaction_background = get_theme_mod('transportex_calltoaction_background', );
    $calltoaction_section_enable = get_theme_mod('calltoaction_section_enable', 1 );
    if($calltoaction_section_enable != 0){
    ?>
<section class="ta-calltoaction wow fadeIn animated" style="background-image:url('<?php echo $transportex_calltoaction_background; ?>');">
    <div class="overlay" style="background-color:<?php echo $transportex_calltoaction_overlay_color; ?> ;">
    <div class="container">
      <div class="row">
        <div class="col-md-9 text-xs">
          <?php if( ($transportex_calltoaction_title) || ($transportex_calltoaction_subtitle)!='' ) { ?>
          <!-- Section Title -->
          <div class="ta-calltoaction-box-info">
            <h5><?php echo $transportex_calltoaction_title; ?></h5>
            <p><?php echo $transportex_calltoaction_subtitle; ?></p>
         </div>
         <!-- /Section Title -->
         <?php } ?>

        </div>
        <div class="col-md-2 text-xs">
          <?php 
          $transportex_calltoaction_button_one_link = get_theme_mod('transportex_calltoaction_button_one_link','#');
          $transportex_calltoaction_button_one_label = get_theme_mod('transportex_calltoaction_button_one_label',__('Lets Start'));
          $transportex_calltoaction_button_one_target = get_theme_mod('transportex_calltoaction_button_one_target',false);
          if($transportex_calltoaction_button_one_label !='')
          { ?>
          <a href="<?php echo esc_url($transportex_calltoaction_button_one_link); ?>" <?php if($transportex_calltoaction_button_one_target == true){ echo 'target="_blank"';}?> class="btn btn-theme"><?php echo $transportex_calltoaction_button_one_label ; ?></a>  
          <?php } ?> 
        </div>
      </div>
    </div>
  </div>
</section>

<div class="clearfix"></div>  
<?php } }

endif;

    if ( function_exists( 'icycp_transportex_calltoaction' ) ) {
    $section_priority = apply_filters( 'icycp_transportex_homepage_section_priority', 11, 'icycp_transportex_calltoaction' );
    add_action( 'icycp_transportex_homepage_sections', 'icycp_transportex_calltoaction', absint( $section_priority ) );

    } 

/*** Service */
if ( ! function_exists( 'icycp_transportex_service' ) ) :

	function icycp_transportex_service() {

    $defaults = array(
      array(
        'fa_icon' => 'fa fa-plane ',
        'service_title'      => esc_html__( 'Air Freight', 'transportex' ),
        'service_desc'       => "looks there isn't anything embarrassing hidden in the middle of text",
        'btnmore'      => __('Read More','transportex'),
        'btnlink'       => '#',
        'open_new_window' => 'no',
        'id'         => 'customizer_repeater_56d7ea7f40b56',
      ),
      array(	
        'fa_icon' => 'fa fa-truck',
        'service_title'      => esc_html__( 'Groung Shipping', 'transportex' ),
        'service_desc'       => "looks there isn't anything embarrassing hidden in the middle of text",
        'btnmore'      => __('Read More','transportex'),
        'btnlink'       => '#',
        'open_new_window' => 'no',
        'id'         => 'customizer_repeater_56d7ea7f40b86',
      ),
      array(	
        'fa_icon' => 'fa fa-ship',
        'service_title'      => esc_html__( 'Sea Delivery', 'transportex' ),
        'service_desc'       => "looks there isn't anything embarrassing hidden in the middle of text",
        'btnmore'      => __('Read More','transportex'),
        'btnlink'       => '#',
        'open_new_window' => 'no',
        'id'         => 'customizer_repeater_56d7ea7f40b86',
        ),

    );


    $service_widget_data = get_option('widget_transportex_service_widget', $defaults );

    $arr = array(); //create empty array

    $i = 0;

    foreach(array_reverse($service_widget_data) as $widget_data) {

      if($i == 3){
        break;
      }
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
      
      $i++;
      
    } 
  $default = json_encode( $arr);

		$transportex_service_title = get_theme_mod('transportex_service_title',__('Why We Best in Business Services','icyclub'));
		$transportex_service_subtitle = get_theme_mod('transportex_service_subtitle','laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.');
		$transportex_service_enable = get_theme_mod('transportex_service_enable', 1 );
    $transportex_service_background = get_theme_mod('transportex_service_background');
   

    $transportex_service_content = get_theme_mod('transportex_service_content', $default);

		if($transportex_service_enable != 0)
		{	
		?>
	    <!-- Section Title -->
<section id="service" class="ta-service-section text-center" style="background-image:url('<?php echo $transportex_service_background;?>');">
	<div class="overlay">
<div class="container">		
		<?php if( ($transportex_service_title) || ($transportex_service_subtitle)!='' ) { ?>
		<div class="row">
			<div class="col-md-12 wow fadeInDown animated padding-bottom-20">
            <div class="ta-heading">
              <h3 class="ta-heading-inner"><?php echo $transportex_service_title; ?></h3>
			  <p><?php echo $transportex_service_subtitle; ?></p>
            </div>
          </div>
		</div>
		<!-- /Section Title -->
		<?php } ?>
			<div class="row">


		<?php	if ( ! empty( $transportex_service_content ) ) {
		$allowed_html = array(
		'br'     => array(),
		'em'     => array(),
		'strong' => array(),
		'b'      => array(),
		'i'      => array(),
		);
		$transportex_service_content = json_decode( $transportex_service_content );
		foreach ( $transportex_service_content as $service_item ) {
			$icon = ! empty( $service_item->icon_value ) ? apply_filters( 'transportex_translate_single_string', $service_item->icon_value, 'Service section' ) : '';
			$title = ! empty( $service_item->title ) ? apply_filters( 'transportex_translate_single_string', $service_item->title, 'Service section' ) : '';
			$text = ! empty( $service_item->text ) ? apply_filters( 'transportex_translate_single_string', $service_item->text, 'Service section' ) : '';
			$readmore_button = ! empty( $service_item->button_text ) ? apply_filters( 'transportex_translate_single_string', $service_item->button_text, 'Service section' ) : '';
			$link = ! empty( $service_item->link ) ? apply_filters( 'transportex_translate_single_string', $service_item->link, 'Service section' ) : '';
			$open_new_tab = ! empty( $service_item->open_new_tab ) ? apply_filters( 'transportex_translate_single_string', $service_item->open_new_tab, 'Service section' ) : '';

		?>
        <!--col-md-4-->
        <div class="col-md-4 swing animated service-one">
          <div class="ta-service two text-left mb-md-0">
            <div class="ta-service-inner">
             
              <div class=""> <i class="<?php echo  $icon; ?>"></i> </div>
             
              <h3><?php echo $title; ?></h3>
              <p><?php echo $text; ?></p>
              <?php 
                if($readmore_button !='')
                { ?>
                <a href="<?php echo esc_url($link); ?>" <?php if($open_new_tab == 'yes' || $open_new_tab== '1') { ?> target="_blank" <?php } ?> class="btn btn-theme"><?php echo $readmore_button ; ?></a>	
                <?php } ?>
					
            </div>
          </div>
        </div>
			<?php
			} }
		
			  ?>
	</div>
  </div>
  </div>
</section>

<div class="clearfix"></div>	
<?php		} }

endif;
if ( function_exists( 'icycp_transportex_service' ) ) {
	$section_priority = apply_filters( 'icycp_transportex_homepage_section_priority', 11, 'icycp_transportex_service' );
	add_action( 'icycp_transportex_homepage_sections', 'icycp_transportex_service', absint( $section_priority ) );
}


/**
 * Callout section
 */
if ( ! function_exists( 'icycp_transportex_callout' ) ) :

	function icycp_transportex_callout() {
		
		$transportex_callout_background = get_theme_mod('transportex_callout_background',ICYCP_PLUGIN_URL .'inc/transportex/images/callout/callout-back.jpg');
		$transportex_callout_overlay_color = get_theme_mod('transportex_callout_overlay_color', '#50b9ce');
		$transportex_callout_title = get_theme_mod('transportex_callout_title',__('Trusted By Over 10,000 Worldwide Businesses. Try Today!','transportex'));
		$transportex_callout_description = get_theme_mod('transportex_callout_description','We must explain to you how all this mistransportexken idea of denouncing pleasure');
		$button_one_label = get_theme_mod('transportex_callout_button_one_label',__('Explore Now','transportex'));
		$button_one_link = get_theme_mod('transportex_callout_button_one_link','#');
		$button_one_target = get_theme_mod('transportex_callout_button_one_target',false);
    $button_two_label = get_theme_mod('transportex_callout_button_two_label',__('Buy Now!','transportex'));
		$button_two_link = get_theme_mod('transportex_callout_button_two_link','#');
		$button_two_target = get_theme_mod('transportex_callout_button_two_target',false);
		$transportex_callout_enable = get_theme_mod('transportex_callout_enable', 1 );
		if($transportex_callout_enable != 0){
		if($transportex_callout_background != '') { 
		?>		
<section class="ta-callout" style="background-image:url('<?php echo esc_url($transportex_callout_background);?>');">
<?php } else { ?>
<section class="ta-callout">
<?php } ?>
<div class="overlay" style="background:<?php echo $transportex_callout_overlay_color;?>">
      <!--container-->
      <div class="container">
        <!--row-->
        <div class="row">
          <!--ta-callout-inner-->
            <div class="ta-callout-inner text-xs text-center">
                <h3 class=""><?php echo $transportex_callout_title;  ?></h3>
    	        <p class="padding-bottom-50"><?php echo $transportex_callout_description;  ?></p>
            <a href="<?php echo $button_one_link; ?>" <?php if($button_one_target == true) {?> target="_blank" <?php } ?> class="btn btn-theme-two margin-bottom-10"><?php echo $button_one_label; ?></a>
            <a href="<?php echo $button_two_link; ?>" <?php if($button_two_target == true) {?> target="_blank" <?php } ?> class="btn btn-theme margin-bottom-10"><?php echo $button_two_label; ?></a>
             
          </div>
        </div>
          <!--ta-callout-inner-->
        </div>
        <!--/row-->
</div>
      <!--/contransportexiner-->
</section>
<!-- /Portfolio Section -->

<div class="clearfix"></div>	
<?php } }

endif;

		if ( function_exists( 'icycp_transportex_callout' ) ) {
		$section_priority = apply_filters( 'icycp_transportex_homepage_section_priority', 12, 'icycp_transportex_callout' );
		add_action( 'icycp_transportex_homepage_sections', 'icycp_transportex_callout', absint( $section_priority ) );

		}
	
//Testimonial
if ( ! function_exists( 'icycp_transportex_testimonial' ) ) :

	function icycp_transportex_testimonial() {
    $defaults = array(
							array(
					'subtitle'      => 'Linda Guthrie',
					'text'       => 'We have put the apim bol, temporarily so that we looking quick do your web search manager caught you and you are fured eat our own dog food golden goose',
					'designation' => __('UI Developer','transportex'),
					'test_title'      => esc_html__( 'Professional Team', 'transportex' ),
					'link'       => '#',
					'image_url'  => ICYCP_PLUGIN_URL .'inc/transportex/images/testimonial/testi1.jpg',
					'open_new_tab' => 'no',
					'id'         => 'customizer_repeater_56d7ea7f40b96',
					
					),
					array(
					'subtitle'      => 'Matt John',
					'text'       => 'but if you want to motivate these clowns, try less carrot and more stick you better eat a reality sandwich before you walk back in that boardroom.',
					'designation' => __('Manager','transportex'),
					'test_title'      => esc_html__( 'Professional Team', 'transportex' ),
					'link'       => '#',
					'image_url'  => ICYCP_PLUGIN_URL .'inc/transportex/images/testimonial/testi2.jpg',
					'open_new_tab' => 'no',
					'id'         => 'customizer_repeater_56d7ea7f40b97',
					),
							

						);


    
  $default = json_encode( $defaults);
$testimonial_options = get_theme_mod('transportex_testimonial_content', $default);
$testimonial_section_enable = get_theme_mod('testimonial_section_enable', 1 );
if($testimonial_section_enable != 0)
{
$testimonial_section_title = get_theme_mod('testimonial_section_title','Our Clients Says');
$testimonial_section_discription= get_theme_mod('testimonial_section_discription','');
$testimonial_callout_bg = get_theme_mod('testimonial_callout_bg');
$background_enable = get_theme_mod('testimonial_bg_overlay_enable', true);
?>
<?php if($background_enable != false) { 
		?>		
<section class="testimonials-section" style="background-image:url('<?php echo esc_url($testimonial_callout_bg);?>');">
<?php } else { ?>
<section class="testimonials-section">
<?php } ?>  <!--overlay-->
    <div class="overlay">
      <!--container-->
      <div class="container">
        <!--row-->
        <div class="row">
          <div class="col-md-12 wow fadeInDown  padding-bottom-20">
            <div class="ta-heading">
              <h3 class="ta-heading-inner"><?php echo $testimonial_section_title; ?></h3>
			  <p><?php echo $testimonial_section_discription; ?></p>
            </div>
          </div>
        </div>
        <!--/row-->
        <!--content-testimonials-->
        <div class="row">
            

        <?php  $testimonial_options = json_decode($testimonial_options); ?>
       <?php     if( $testimonial_options !='' )
			{
		foreach($testimonial_options as $testimonial_item){ 

      $test_title = ! empty( $testimonial_item->test_title ) ? apply_filters( 'businessup_translate_single_string',
      $testimonial_item->test_title, 'Testimonial section' ) : '';	

     $discription = ! empty( $testimonial_item->text ) ? apply_filters( 'businessup_translate_single_string',
      $testimonial_item->text, 'Testimonial section' ) : '';

     $designation = ! empty( $testimonial_item->designation ) ? apply_filters( 'businessup_translate_single_string',
      $testimonial_item->designation, 'Testimonial section' ) : '';
      
     $name = ! empty( $testimonial_item->subtitle ) ? apply_filters( 'businessup_translate_single_string',
      $testimonial_item->subtitle, 'Testimonial section' ) : '';
      
      $image = ! empty( $testimonial_item->image_url ) ? apply_filters( 'businessup_translate_single_string',
       $testimonial_item->image_url, 'Testimonial section' ) :  get_template_directory_uri().'/images/client-thumb.jpg';

      ?>
      <!--item-->
          
			
      <div class="col-md-6">
            <div class="testimonials_qute mb-md-0">
              <div class="sub-qute">
                <div></div>
                <div class="qute_icons">
                  <i class="fa fa-quote-left"></i>
                </div>
              </div> 
              <div class="testi_discription">
                <div class="clearfix"></div>
                <h5><?php echo $test_title ?></h5>
                <p><?php echo $discription ?></p>             
              </div>
              <div class="ta-client-qute">
                <span class="ta-client">
                  <img src="<?php echo $image; ?>">
                </span>
                <div class="ti-client-info">  
                  <p class="user-title">
                    <?php echo $name; ?>
                    </p>
                    <p class="user-designation"> <?php  echo $designation; ?> </p>
                  </div>
              </div>
            </div>
          </div>
			 
          <!--/item-->  
	<?php 	} ?>


        </div>
        <!--/content-testimonials-->
      </div>
      <!--/contransportexiner-->
    </div>
    <!--/overlay-->
  </section>

		<?php
	} } }

endif;

		if ( function_exists( 'icycp_transportex_testimonial' ) ) {
		$section_priority = apply_filters( 'icycp_transportex_homepage_section_priority', 14, 'icycp_transportex_testimonial' );
		add_action( 'icycp_transportex_homepage_sections', 'icycp_transportex_testimonial', absint( $section_priority ) );
}

//Latest News
if ( ! function_exists( 'icycp_transportex_latest_news' ) ) :

	function icycp_transportex_latest_news() {

$transportex_news_enable = get_theme_mod('transportex_news_enable', 1 );
if($transportex_news_enable != 0)
{

  $transportex_total_posts = get_option('posts_per_page'); /* number of latest posts to show */
	
	if( !empty($transportex_total_posts) && ($transportex_total_posts > 0) ):

    $transportex_news_background = get_theme_mod('transportex_news_background');
    $transportex_news_overlay_color = get_theme_mod('transportex_news_overlay_color');
    $transportex_news_text_color = get_theme_mod('transportex_news_text_color'); 
   $transportex_new_slider_category = get_theme_mod('slider_category'); 
   $disable_news_meta = get_theme_mod('disable_news_meta', false);
   ?>
<style>
.ta-blog-section .ta-heading h3.ta-heading-inner {
color: <?php echo $transportex_news_text_color ?>;
}
.ta-blog-section .ta-heading .ta-heading-inner::before {
border-color: <?php echo $transportex_news_text_color ?>;
}
</style>
<!--==================== BLOG SECTION ====================-->
<?php if($transportex_news_background != '') { ?>

<section id="blog" class="ta-blog-section" style="background-image:url('<?php echo $transportex_news_background;?>');!important">
<?php } else { ?>
<section id="blog" class="ta-blog-section">
  <?php } ?>
  <div class="overlay" style="background-color: <?php echo esc_attr($transportex_news_overlay_color);?>;">
    <div class="container">
      <div class="row">
        <div class="col-md-12 wow fadeInDown animated padding-bottom-50 text-center">
          <div class="ta-heading">
            <?php $transportex_news_title = get_theme_mod('transportex_news_title',__('Reach Your Place Sure & Safe','transportex'));
          
            if( !empty($transportex_news_title) ):
              echo '<h3 class="ta-heading-inner">'.$transportex_news_title.'</h3>';
            endif; ?>

            <?php  $transportex_news_subtitle = get_theme_mod('transportex_news_subtitle','We take care with merchandise and deliver your order where you are on time');

            if( !empty($transportex_news_subtitle) ): ?>
          <p style="color: <?php echo $transportex_news_text_color ?>;"><?php echo $transportex_news_subtitle ?> </p>
          <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="clear"></div>
      <div class="row">
        <?php $news_select = get_theme_mod('news_select',3);
			   $news_setting = get_theme_mod('slider_post_enable',true);
			
			   if( $news_setting == false )
			   {
			     $transportex_latest_loop = new WP_Query(array( 'post_type' => 'post', 'posts_per_page' => $news_select, 'order' => 'DESC',  'ignore_sticky_posts' => true , 'category__not_in'=>$transportex_new_slider_category));
			   }
			   else
			   {
			     $transportex_latest_loop = new WP_Query(array( 'post_type' => 'post', 'posts_per_page' => $news_select, 'order' => 'DESC','ignore_sticky_posts' => true, ''));
			   }
			    if ( $transportex_latest_loop->have_posts() ) :
			     while ( $transportex_latest_loop->have_posts() ) : $transportex_latest_loop->the_post();?>
        <div class="col-md-4 wow pulse animated">
          <div class="ta-blog-post-box mb-md-0">
            <div class="ta-blog-thumb"> 
              <div class="ta-blog-category"> 
                    <?php   $cat_list = get_the_category_list();
                    if(!empty($cat_list)) { ?>
                    <?php the_category(' '); ?>
                   <?php } ?>
                  
                </div>
              <?php if(has_post_thumbnail()): ?>
                <a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>" >
                  <?php $defalt_arg =array('class' => "img-fluid"); ?>
                  <?php the_post_thumbnail('', $defalt_arg); ?>
                </a>
                  <?php endif; ?>
            </div>

           
			<article class="small">
               <?php if($disable_news_meta !=true) { ?>
			  <span class="ta-blog-date"> 
                <?php echo esc_attr(get_the_date('j')); ?>
                <?php echo esc_attr(get_the_date('M')); ?>
              </span> 
              <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) );?>"><?php _e('by','transportex'); ?>
              <?php the_author(); ?>
              </a>
			 <?php } ?>			  
              <h1 class="title"><a title<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>"> <?php the_title(); ?> </a> </h1>
            </article>
			
          </div>
        </div>
        <?php endwhile; endif;	wp_reset_postdata(); ?>
      </div>
    </div>
    <!-- /.container --> 
  </div>
</section>
<?php endif; ?>
<?php

	} }

endif;

		if ( function_exists( 'icycp_transportex_latest_news' ) ) {
		$section_priority = apply_filters( 'icycp_transportex_homepage_section_priority', 14, 'icycp_transportex_latest_news' );
		add_action( 'icycp_transportex_homepage_sections', 'icycp_transportex_latest_news', absint( $section_priority ) );
}