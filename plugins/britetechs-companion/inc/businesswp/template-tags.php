<?php


/**************************************************
**** Slider Default Contents
***************************************************/

if ( ! function_exists( 'businesswp_slider_default_contents' ) ):

      function businesswp_slider_default_contents(){

            return json_encode( array(
                  array(
                  'subtitle'      => esc_html__( 'Hurry! Multipurpose Website Template', 'britetechs-companion' ),
                  'title'      => esc_html__( 'Business<b>WP</b> is Power.', 'britetechs-companion' ),
                  'text'       => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry remaining essentially unchanged dummy text typesetting.', 'britetechs-companion' ),
                  'button_text'      => __('Make A Website','britetechs-companion'),
                  'link'       => '#',
                  'image_url'  => bc_plugin_url .'/inc/businesswp/img/slide1.png',
                  'checkbox_val' => false,
                  'content_align' => 'left',
                  'id'         => 'customizer_repeater_58d7gh7f20b10',
                  ),
                  array(
                  'subtitle'      => esc_html__( 'Amazing Website WP Template', 'britetechs-companion' ),
                  'title'      => esc_html__( 'Wonderful Website a Real Winner', 'britetechs-companion' ),
                  'text'       => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry remaining essentially unchanged dummy text typesetting.', 'britetechs-companion' ),
                  'button_text'      => __('Buy Now','britetechs-companion'),
                  'link'       => '#',
                  'image_url'  => bc_plugin_url .'/inc/businesswp/img/slide2.png',
                  'checkbox_val' => false,
                  'content_align' => 'center',
                  'id'         => 'customizer_repeater_58d7gh7f20b20',
                  ),
              ) );

      }

endif;

/**************************************************
**** Service Default Contents
***************************************************/

if ( ! function_exists( 'businesswp_service_default_contents' ) ):

      function businesswp_service_default_contents(){

            return json_encode( array(
                  array(
                  'title'      => esc_html__( 'Marketing', 'britetechs-companion' ),
                  'text'       => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting.', 'britetechs-companion' ),
                  'icon_value'  => 'fa-street-view',
                  'link'  => '#',
                  'id'         => 'customizer_repeater_58d7gh7f20b10',
                  ),
                  array(
                  'title'      => esc_html__( 'Professional', 'britetechs-companion' ),
                  'text'       => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting.', 'britetechs-companion' ),
                  'icon_value'  => 'fa-mortar-board',
                  'link'  => '#',
                  'id'         => 'customizer_repeater_58d7gh7f20b20',
                  ),
                  array(
                  'title'      => esc_html__( 'Developing', 'britetechs-companion' ),
                  'text'       => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting.', 'britetechs-companion' ),
                  'icon_value'  => 'fa-cubes',
                  'link'  => '#',
                  'id'         => 'customizer_repeater_58d7gh7f20b30',
                  ),
              ) );

      }

endif;

/**************************************************
**** Portfolio Default Contents
***************************************************/

if ( ! function_exists( 'businesswp_portfolio_default_contents' ) ):

      function businesswp_portfolio_default_contents(){

            return json_encode( array(
                  array(
                  'title'      => esc_html__( 'Marketing Strategy', 'britetechs-companion' ),
                  'text'       => esc_html__( 'Lorem Ipsum is simply dummy text.', 'britetechs-companion' ),
                  'image_url'  => bc_plugin_url .'/inc/businesswp/img/project1.png',
                  'id'         => 'customizer_repeater_58d7gh7f20b10',
                  ),
                  array(
                  'title'      => esc_html__( 'Business Consulting', 'britetechs-companion' ),
                  'text'       => esc_html__( 'Lorem Ipsum is simply dummy text.', 'britetechs-companion' ),
                  'image_url'  => bc_plugin_url .'/inc/businesswp/img/project2.png',
                  'id'         => 'customizer_repeater_58d7gh7f20b20',
                  ),
                  array(
                  'title'      => esc_html__( 'Digital Marketing', 'britetechs-companion' ),
                  'text'       => esc_html__( 'Lorem Ipsum is simply dummy text.', 'britetechs-companion' ),
                  'image_url'  => bc_plugin_url .'/inc/businesswp/img/project3.png',
                  'id'         => 'customizer_repeater_58d7gh7f20b30',
                  ),
              ) );

      }

endif;

/**************************************************
**** Testimonial Default Contents
***************************************************/

if ( ! function_exists( 'businesswp_testimonial_default_contents' ) ):

      function businesswp_testimonial_default_contents(){

            return json_encode( array(
                  array(
                  'title'      => esc_html__( 'Laura Michelle', 'britetechs-companion' ),
                  'designation'      => esc_html__( 'Co Founder', 'britetechs-companion' ),
                  'text'       => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting', 'britetechs-companion' ),
                  'image_url'  => bc_plugin_url .'/inc/businesswp/img/testi1.png',
                  'id'         => 'customizer_repeater_58d7gh7f20b10',
                  ),
                  array(
                  'title'      => esc_html__( 'Laura Michelle', 'britetechs-companion' ),
                  'designation'      => esc_html__( 'Co Founder', 'britetechs-companion' ),
                  'text'       => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting', 'britetechs-companion' ),
                  'image_url'  => bc_plugin_url .'/inc/businesswp/img/testi2.png',
                  'id'         => 'customizer_repeater_58d7gh7f20b10',
                  ),
                  array(
                  'title'      => esc_html__( 'Laura Michelle', 'britetechs-companion' ),
                  'designation'      => esc_html__( 'Co Founder', 'britetechs-companion' ),
                  'text'       => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting', 'britetechs-companion' ),
                  'image_url'  => bc_plugin_url .'/inc/businesswp/img/testi3.png',
                  'id'         => 'customizer_repeater_58d7gh7f20b10',
                  ),
              ) );

      }

endif;

/**************************************************
**** Team Defaults Contents
***************************************************/

if ( ! function_exists( 'businesswp_team_default_contents' ) ):

      function businesswp_team_default_contents(){

            return json_encode( array(
                  array(
                  'title'      => esc_html__( 'Alivia Latimer', 'britetechs-companion' ),
                  'designation'      => esc_html__( 'CEO and Co-Founder', 'britetechs-companion' ),
                  'text'       => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting.', 'britetechs-companion' ),
                  'link'       => '#',
                  'image_url'  => bc_plugin_url .'/inc/businesswp/img/team1.png',
                  'id'         => 'customizer_repeater_58d7gh7f20b10',
                  ),
                  array(
                  'title'      => esc_html__( 'Katana Clark', 'britetechs-companion' ),
                  'designation'      => esc_html__( 'CEO and Co-Founder', 'britetechs-companion' ),
                  'text'       => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting.', 'britetechs-companion' ),
                  'link'       => '#',
                  'image_url'  => bc_plugin_url .'/inc/businesswp/img/team2.png',
                  'id'         => 'customizer_repeater_58d7gh7f20b20',
                  ),
                  array(
                  'title'      => esc_html__( 'Marshawn', 'britetechs-companion' ),
                  'designation'      => esc_html__( 'CEO and Co-Founder', 'britetechs-companion' ),
                  'text'       => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting.', 'britetechs-companion' ),
                  'link'       => '#',
                  'image_url'  => bc_plugin_url .'/inc/businesswp/img/team3.png',
                  'id'         => 'customizer_repeater_58d7gh7f20b30',
                  ),
                  array(
                  'title'      => esc_html__( 'Taylan Austin', 'britetechs-companion' ),
                  'designation'      => esc_html__( 'CEO and Co-Founder', 'britetechs-companion' ),
                  'text'       => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting.', 'britetechs-companion' ),
                  'link'       => '#',
                  'image_url'  => bc_plugin_url .'/inc/businesswp/img/team4.png',
                  'id'         => 'customizer_repeater_58d7gh7f20b30',
                  ),
              ) );

      }

endif;

/**************************************************
**** Contact Defaults Contents
***************************************************/

if ( ! function_exists( 'businesswp_contact_default_contents' ) ):

      function businesswp_contact_default_contents(){

            return json_encode( array(
                  array(
                  'icon_value'  => 'fa-phone',
                  'title'      => esc_html__( 'Call Us', 'britetechs-companion' ),
                  'text'       => esc_html__( '+01 - 123 456 7890', 'britetechs-companion' ),
                  'id'         => 'customizer_repeater_58d7gh7f20b10',
                  ),
                  array(
                  'icon_value'  => 'fa-envelope',
                  'title'      => esc_html__( 'Email Us', 'britetechs-companion' ),
                  'text'       => esc_html__( 'info@example.com', 'britetechs-companion' ),
                  'id'         => 'customizer_repeater_58d7gh7f20b10',
                  ),
                  array(
                  'icon_value'  => 'fa-clock-o',
                  'title'      => esc_html__( 'Store Hours', 'britetechs-companion' ),
                  'text'       => esc_html__( 'Opening Hours - 9am to 5pm', 'britetechs-companion' ),
                  'id'         => 'customizer_repeater_58d7gh7f20b10',
                  ),
                  array(
                  'icon_value'  => 'fa-globe',
                  'title'      => esc_html__( 'Visit Us', 'britetechs-companion' ),
                  'text'       => esc_html__( '725 Green St San Francisco, California(CA), 94133', 'britetechs-companion' ),
                  'id'         => 'customizer_repeater_58d7gh7f20b10',
                  ),
              ) );
            
      }

endif;

if ( ! function_exists( 'businesswp_portfolio_layout3' ) ) :

 function businesswp_portfolio_layout3( $colum = 'col-lg-4 col-md-6 col-12' ){
    $section = 'portfolio';
    $items = array();
    $items = businesswp_get_option($section.'_content');
    if(!$items){
      $items = businesswp_portfolio_default_contents();
    }

    if(is_string($items)){
      $items = json_decode($items);
    }
   foreach ($items as $key => $item) { 
    $title = ! empty( $item->title ) ? apply_filters( 'businesswp_translate_single_string', $item->title, 'Portfolio section' ) : '';
    $text = ! empty( $item->text ) ? apply_filters( 'businesswp_translate_single_string', $item->text, 'Portfolio section' ) : '';
  ?>
  <div class="<?php echo $colum ; ?> project-item wow animate__animated animate__pulse">
    <div class="portfolio-wrap-2">
      <div class="portfolio-thumbnail">
        <img src="<?php echo esc_url($item->image_url); ?>" alt="<?php echo esc_attr($title); ?>">
      </div>
      <div class="portfolio-content-area">
        <h6 class="portfolio-title-2"><a><?php echo wp_kses_post( html_entity_decode( $title ) ); ?></a></h6>
        <div class="portfolio-category-2">
          <?php echo wp_kses_post( html_entity_decode( $text ) ); ?>
        </div>  
      </div>
    </div>
  </div>
  <?php 
      }
}
endif;