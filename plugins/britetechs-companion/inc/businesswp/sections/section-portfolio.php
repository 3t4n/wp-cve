<?php 

if( ! function_exists('bc_businesswp_portfolio') ):
  function bc_businesswp_portfolio(){
      $section = 'portfolio';
      $portfolio_layout = 'layout2';
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

      if($container=='container-fluid'){
        $container.=' p-0';
      }

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
      <style>
        .portfolio-thumbnail::before{
          display: none;
        }
      </style>
      <section id="portfolio" class="home_section theme_portfolio portfolio <?php echo esc_attr($class); ?>" <?php echo $section_attributes; ?>>
        <div class="<?php echo esc_attr($container); ?>">
          
          <?php do_action('businesswp_frontpage_section_header',$subtitle,$subtitle_color,$title,$title_color,$desc,$desc_color,$divider_show,$divider_type); ?>
      
          <div class="row project-isotop photobox_lib">
            <?php
              businesswp_portfolio_layout3();
            ?>
          </div>
        </div>
      </section><!-- End .theme_portfolio -->
      <?php
      }
  }
endif;


if ( function_exists( 'bc_businesswp_portfolio' ) ) {
  $section_priority = apply_filters( 'businesswp_section_priority', 11, 'bc_businesswp_portfolio' );
  add_action( 'businesswp_sections', 'bc_businesswp_portfolio', absint( $section_priority ) );
}