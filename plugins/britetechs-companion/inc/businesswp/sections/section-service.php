<?php 

if( ! function_exists('bc_businesswp_service') ):
  function bc_businesswp_service(){
      $section = 'service';
      $service_layout = businesswp_get_option($section.'_layout');
      $show = businesswp_get_option($section.'_show');
      $back_animation_show = businesswp_get_option($section.'_back_animation_show');
      $subtitle = businesswp_get_option($section.'_subtitle');

      $subtitle_color = businesswp_get_option($section.'_subtitle_color');
      $title = businesswp_get_option($section.'_title');
      $title_color = businesswp_get_option($section.'_title_color');
      $desc = businesswp_get_option($section.'_desc');
      $desc_color = businesswp_get_option($section.'_desc_color');
      $divider_show = businesswp_get_option($section.'_divider_show');
      $divider_type = businesswp_get_option($section.'_divider_type');
      $container = businesswp_get_option($section.'_container_width');
      $bg_color = businesswp_get_option($section.'_bg_color');
      $bg_image = businesswp_get_option($section.'_bg_image');


      $section_attributes = '';
      $class = '';

      if($bg_color && $bg_image==''){
          $section_attributes .= 'style="';
          $section_attributes .= 'background-color:'.$bg_color.';';
          $section_attributes .= '"';
      }

      if($bg_image){
          $section_attributes .= 'style="background-image:url('.esc_url_raw($bg_image).');"';
          $class .= 'background_image overlay';
      }

      if($show){
      ?> 
       <section id="service" class="home_section service <?php echo esc_attr($class); ?>" <?php echo $section_attributes; ?>>
        <object class="ss-style-triangles">
       <?php if ($back_animation_show) { ?>
        <div class="animation-area">
          <div class="shape_box_1"></div>
          <div class="shape_box_2"></div>
          <div class="shape_box_3"></div>
          <div class="shape_box_4"></div>
          <div class="shape_box_5"></div>
          <div class="shape_box_6"></div>
          <div class="shape_box_7"></div>
        </div>
         <?php } ?>
          <div class="<?php echo esc_attr($container); ?>">
            <?php do_action('businesswp_frontpage_section_header',$subtitle,$subtitle_color,$title,$title_color,$desc,$desc_color,$divider_show,$divider_type); ?>
            <div class="row">

              <?php  
                  switch ($service_layout) {
                  case 'layout1':
                  businesswp_service_layout1();
                  break;

                  case 'layout2':
                  businesswp_service_layout2();
                  break;

                  default:
                  businesswp_service_layout3();
                  break;
                  }
            ?>
            </div>
          </div>
        </object>
       </section><!-- End .theme_services -->
      <?php
      }
  }
endif;


if ( function_exists( 'bc_businesswp_service' ) ) {
  $section_priority = apply_filters( 'businesswp_section_priority', 10, 'bc_businesswp_service' );
  add_action( 'businesswp_sections', 'bc_businesswp_service', absint( $section_priority ) );
}