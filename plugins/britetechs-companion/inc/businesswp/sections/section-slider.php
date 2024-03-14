<?php

if( ! function_exists('bc_businesswp_slider') ):
  function bc_businesswp_slider(){
      $slider_show = businesswp_get_option('slider_show');
      $slider_overlay_show = businesswp_get_option('slider_overlay_show');

      $items = array();
      $items = businesswp_get_option('slider_content');
      if(!$items){
        $items = businesswp_slider_default_contents();
      }

      if(is_string($items)){
        $items = json_decode($items);
      }

      if($slider_show==true):

        $_overlay_class = '';
        if($slider_overlay_show){
           $_overlay_class = 'overlay';
        }
      ?>
      <section id="slider" class="home_slider slider">
        <div class="main_slider owl-carousel owl-theme">

           <?php foreach ($items as $key => $item) { 
            $title = ! empty( $item->title ) ? apply_filters( 'businesswp_translate_single_string', $item->title, 'Slider section' ) : '';
            $subtitle = ! empty( $item->subtitle ) ? apply_filters( 'businesswp_translate_single_string', $item->subtitle, 'Slider section' ) : '';
            $text = ! empty( $item->text ) ? apply_filters( 'businesswp_translate_single_string', $item->text, 'Slider section' ) : '';
            $button_text = ! empty( $item->button_text ) ? apply_filters( 'businesswp_translate_single_string', $item->button_text, 'Slider section' ) : '';
            $link = ! empty( $item->link ) ? apply_filters( 'businesswp_translate_single_string', $item->link, 'Slider section' ) : '';
            $checkbox_val = ! empty( $item->checkbox_val ) ? $item->checkbox_val : false;
            $text_align = ! empty( $item->content_align ) ? $item->content_align : 'left';

            $contnet_col_class = 'col-lg-8 col-md-8 col-12';

            if( $text_align == 'center' ){
              $contnet_col_class = 'col-lg-8 col-md-8 col-12 offset-lg-2 offset-md-2';
            }else if( $text_align == 'right' ){
              $contnet_col_class = 'col-lg-8 col-md-8 ml-auto mr-0';
            }
          ?>
          <div class="item">
            <div class="slide <?php echo esc_attr($_overlay_class); ?>" style="background-image:url(<?php echo $item->image_url; ?>);">
              <img src="<?php echo esc_url($item->image_url); ?>" alt="<?php echo esc_attr($title); ?>">
              <div class="slide__text h-100">
                <div class="<?php echo esc_attr(businesswp_get_option('slider_container_width')); ?> h-100 d-flex">
                  <div class="row w-100 align-self-center">
                    <div class="<?php echo esc_attr( $contnet_col_class ); ?>">
                      <div class="w-100 text-<?php echo esc_attr($text_align); ?> slide_border">
                      <?php if($subtitle){ ?>
                        <span class="slide-subtitle"><?php echo wp_kses_post( html_entity_decode( $subtitle ) ); ?></span>
                        <?php } ?>

                        <?php if($title){ ?>
                        <h3 class="slide-title"><?php echo wp_kses_post( html_entity_decode( $title ) ); ?></h3>
                        <?php } ?>

                        <?php if($text){ ?>
                        <p class="slide-content"><?php echo wp_kses_post( html_entity_decode( $text ) ); ?></p>
                        <?php } ?>

                      <div class="slider_footer">
                        <?php if($link){ ?>
                        <a class="button slider_btn" href="<?php echo esc_url($link); ?>" <?php if($checkbox_val==true || $checkbox_val== '1') { echo "target='_blank'"; } ?> > <?php echo esc_html($button_text); ?></a>
                          <?php } ?>
                      </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php } ?>       
        </div>
      </section><!-- End .home_slider -->
      <?php endif;
  }
endif;

if ( function_exists( 'bc_businesswp_slider' ) ) {
  $section_priority = apply_filters( 'businesswp_section_priority', 5, 'bc_businesswp_slider' );
  add_action( 'businesswp_sections', 'bc_businesswp_slider', absint( $section_priority ) );
}
?>