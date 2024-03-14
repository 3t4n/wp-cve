<?php

if( ! function_exists('bc_businesswp_testimonial') ):
  function bc_businesswp_testimonial(){
      $section = 'testimonial';
      $testimonial_layout = businesswp_get_option($section.'_layout');
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
      $bg_color = businesswp_get_option($section.'_bg_color');
      $bg_image = businesswp_get_option($section.'_bg_image');
      $container = businesswp_get_option($section.'_container_width');


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
      <section id="testimonial" class="home_section theme_testimonial testimonial <?php echo esc_attr($class); ?>" <?php echo $section_attributes; ?>>
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
            $section = 'testimonial';
            $items = array();
            $items = businesswp_get_option($section.'_content');
            if(!$items){
              $items = businesswp_testimonial_default_contents();
            }
            if(is_string($items)){
            $items = json_decode($items);
            }
             foreach ($items as $key => $item) { 
                      $title = ! empty( $item->title ) ? apply_filters( 'businesswp_translate_single_string', $item->title, 'Testimonial section' ) : '';
                      $designation = ! empty( $item->designation ) ? apply_filters( 'businesswp_translate_single_string', $item->designation, 'Testimonial section' ) : '';
                      $text = ! empty( $item->text ) ? apply_filters( 'businesswp_translate_single_string', $item->text, 'Testimonial section' ) : '';
              
            ?>
            <div class="col-lg-4 col-md-6 col-12 wow animate__animated animate__fadeInUp">
                <div class="testimonial-wrap text-center">
                  <figure class="testimonial-pic">
                    <img src="<?php echo esc_url($item->image_url); ?>" alt="<?php echo esc_attr($title); ?>">
                  </figure>
                   <div class="testimonial-description">
                    <h3><?php echo wp_kses_post( html_entity_decode( $text ) ); ?></h3>
                  </div>
                  <figcaption class="testimonial-auther">
                    <cite class="testimonial-auther-name"><?php echo wp_kses_post( html_entity_decode( $title ) ); ?></cite> /
                    <span class="testimonial-auther-post"><?php echo wp_kses_post( html_entity_decode( $designation ) ); ?></span>
                  </figcaption>
                   <i class="testimonial-bg-icon fa fa-quote-right"></i>
                </div>
            </div>
            <?php }
            ?>
          </div>
        </div>
      </section><!-- .testimonial -->
      <?php }
  }
endif;

if ( function_exists( 'bc_businesswp_testimonial' ) ) {
  $section_priority = apply_filters( 'businesswp_section_priority', 15, 'bc_businesswp_testimonial' );
  add_action( 'businesswp_sections', 'bc_businesswp_testimonial', absint( $section_priority ) );
}