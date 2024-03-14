<?php 

if( ! function_exists('bc_businesswp_team') ): 
    function bc_businesswp_team(){
        $section = 'team';
        $team_layout = businesswp_get_option($section.'_layout');
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
        <section id="team" class="home_section theme_team team <?php echo esc_attr($class); ?>" <?php echo $section_attributes; ?>>
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
                  $section = 'team';
                  $items = array();
                  $items = businesswp_get_option($section.'_content');
                  if(!$items){
                    $items = businesswp_team_default_contents();
                  }

                  if(is_string($items)){
                    $items = json_decode($items);
                  }

                  foreach ($items as $key => $item) {  

                    $title = ! empty( $item->title ) ? apply_filters( 'businesswp_translate_single_string', $item->title, 'Team section' ) : '';
                    $designation = ! empty( $item->designation ) ? apply_filters( 'businesswp_translate_single_string', $item->designation, 'Team section' ) : '';
                    $text = ! empty( $item->text ) ? apply_filters( 'businesswp_translate_single_string', $item->text, 'Team section' ) : '';
                    $link = ! empty( $item->link ) ? apply_filters( 'businesswp_translate_single_string', $item->link, 'Team section' ) : '#';
                 ?>
                  <div class="col-lg-3 col-md-6 col-12 wow animate__animated animate__fadeInUp">
                              <div class="team-wrap-2">
                                <div class="team-img-2 text-center">
                                 <a>
                                  <img class="align-self-start" src="<?php echo esc_url($item->image_url); ?>" alt="<?php echo esc_attr($title); ?>"/></a>
                                </div>
                                <div class="team-detail">
                                   <h5 class="team-title"><a><?php echo wp_kses_post( html_entity_decode( $title ) ); ?></a></h5>
                                   <span class="team-designation"><?php echo wp_kses_post( html_entity_decode( $designation ) ); ?></span>
                                </div>
                              </div>
                          </div>
                         <?php 
                    }
              ?>
            </div>
          </div>
        </section><!-- End .theme_team -->
        <?php
        }
    }
endif;

if ( function_exists( 'bc_businesswp_team' ) ) {
  $section_priority = apply_filters( 'businesswp_section_priority', 20, 'bc_businesswp_team' );
  add_action( 'businesswp_sections', 'bc_businesswp_team', absint( $section_priority ) );
}