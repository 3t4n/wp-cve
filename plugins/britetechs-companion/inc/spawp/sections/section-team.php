<?php 
$section = 'team';
$show = spawp_get_option($section.'_show');
$subtitle = spawp_get_option($section.'_subtitle');
$subtitle_color = spawp_get_option($section.'_subtitle_color');
$title = spawp_get_option($section.'_title');
$title_color = spawp_get_option($section.'_title_color');
$desc = spawp_get_option($section.'_desc');
$desc_color = spawp_get_option($section.'_desc_color');
$divider_show = spawp_get_option($section.'_divider_show');
$divider_type = spawp_get_option($section.'_divider_type');
$divider_width = spawp_get_option($section.'_divider_width');
$container = spawp_get_option($section.'_container_width');
$bg_color = spawp_get_option($section.'_bg_color');
$bg_image = '';

$items = array();
$items = spawp_get_option($section.'_content');
if(!$items){
  $items = spwp_team_default_contents();
}

if(is_string($items)){
  $items = json_decode($items);
}

$section_attributes = '';
$class = '';

if($bg_color && $bg_image==''){
    $section_attributes .= 'style="';
    $section_attributes .= 'background-color:'.$bg_color.';';
    $section_attributes .= '"';
}

if($bg_image){
    $section_attributes .= 'data-parallax="scroll" data-image-src="'.esc_url_raw($bg_image).'"';
    $class .= 'background_image overlay';
}

if($show){
?>
<section id="team" class="home_section team <?php echo esc_attr($class); ?>" <?php echo $section_attributes; ?>>
  <div class="<?php echo esc_attr($container); ?>">
    
    <?php do_action('spawp_frontpage_section_header',$subtitle,$subtitle_color,$title,$title_color,$desc,$desc_color,$divider_show,$divider_type,$divider_width); ?>
    
    <div class="row">
      <?php foreach ($items as $key => $item) { 
        $title = ! empty( $item->title ) ? apply_filters( 'spawp_translate_single_string', $item->title, 'Team section' ) : '';
        $designation = ! empty( $item->designation ) ? apply_filters( 'spawp_translate_single_string', $item->designation, 'Team section' ) : '';
        $text = ! empty( $item->text ) ? apply_filters( 'spawp_translate_single_string', $item->text, 'Team section' ) : '';
        $link = ! empty( $item->link ) ? apply_filters( 'spawp_translate_single_string', $item->link, 'Team section' ) : '#';
      ?>
      <div class="col-xl-6 col-lg-6 col-md-6 col-12 wow zoomIn">
        <div class="team-box team-animated">
          <div class="media">
            <div class="team-image mr-3"><a href="<?php echo esc_url($link); ?>"><img class="rounded-circle" src="<?php echo esc_url($item->image_url); ?>" alt="<?php echo esc_attr($title); ?>"/></a></div>
            <div class="media-body">
              <h5 class="team-title"><a href="<?php echo esc_url($link); ?>"><?php echo wp_kses_post( html_entity_decode( $title ) ); ?></a></h5>
              <cite class="team-position"><?php echo wp_kses_post( html_entity_decode( $designation ) ); ?></cite>
              <p class="team_desc"><?php echo wp_kses_post( html_entity_decode( $text ) ); ?></p>
              <div class="item-social">
                <ul>
                  <?php 
                   if ( ! empty( $item->social_repeater ) ) {
                      $icons = html_entity_decode( $item->social_repeater );
                      $icons_decoded = json_decode( $icons, true );
                         if ( ! empty( $icons_decoded ) ) {
                          foreach( $icons_decoded as $value ){ 
                            $social_icon = ! empty( $value['icon'] ) ? $value['icon'] : '';
                            $social_link = ! empty( $value['link'] ) ? $value['link'] : '';

                            if($social_icon==''){
                              continue;
                            }
                          ?>
                  <li><a href="<?php echo esc_url( $value['link'] ); ?>"><i class="fa <?php echo esc_attr( $value['icon'] ); ?>"></i></a></li>
                  <?php } 
                    } 
                  }else{ ?>
                    <li><a href="#"><i class="fa fa-facebook-f"></i></a></li>
                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                    <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                  <?php } ?>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</section><!-- .team -->
<?php } ?>