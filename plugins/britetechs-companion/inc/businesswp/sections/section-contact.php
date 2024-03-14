<?php 

if( ! function_exists('bc_businesswp_contact') ):
  function bc_businesswp_contact(){
      $section = 'contact';
      $show = businesswp_get_option($section.'_show');
      $back_animation_show = businesswp_get_option($section.'_back_animation_show');
      $contact_layout = businesswp_get_option($section.'_layout');
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
      $button_text = businesswp_get_option($section.'_button_text');
      $button_url = businesswp_get_option($section.'_button_url');


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
       <section id="contact" class="home_section contact <?php echo esc_attr($class); ?>" <?php echo $section_attributes; ?>>

       <?php if ($back_animation_show) { ?>
        <div class="animation-area">
         <!--  <div class="shape_box_1"></div> -->
          <div class="shape_box_2"></div>
          <div class="shape_box_3"></div>
       <!--    <div class="shape_box_4"></div> -->
          <div class="shape_box_5"></div>
          <div class="shape_box_6"></div>
          <div class="shape_box_7"></div>
        </div>
         <?php } ?>

          <div class="<?php echo esc_attr($container); ?>">
            <?php do_action('businesswp_frontpage_section_header',$subtitle,$subtitle_color,$title,$title_color,$desc,$desc_color,$divider_show,$divider_type); ?>
              
              
                <div class="row justify-content-center">
                  <div class="col-lg-6 col-md-6 col-sm-12  wow animate__animated animate__pulse d-flex justify-content-center justify-content-md-center">
                    <div class="wow animate__animated animate__fadeInUp">
                      <?php if($button_url !=''){ ?>
                      <a class="button" href="<?php echo esc_url($button_url); ?>"><?php echo esc_html($button_text); ?></a>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <div class="container contact-area">
                  <div class="row">
                  <?php
                  switch ($contact_layout) {
                  case 'layout1':
                  businesswp_contact_layout1();
                  break;

                  case 'layout2':
                  businesswp_contact_layout2();
                  break;

                  default:
                  businesswp_contact_layout3();
                  break;
                  }
                  ?>
                  </div>
                </div>
          </div>

      </section><!-- End .theme_contact -->
      <?php
      }
  }
endif;


if ( function_exists( 'bc_businesswp_contact' ) ) {
  $section_priority = apply_filters( 'businesswp_section_priority', 25, 'bc_businesswp_contact' );
  add_action( 'businesswp_sections', 'bc_businesswp_contact', absint( $section_priority ) );
}