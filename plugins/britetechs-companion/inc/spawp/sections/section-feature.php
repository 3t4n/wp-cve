<?php 
$section = 'feature';
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
  $items = spwp_feature_default_contents();
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
<section id="feature" class="home_section feature wow zoomIn <?php echo esc_attr($class); ?>" <?php echo $section_attributes; ?>>
  <div class="<?php echo esc_attr($container); ?>">
    
    <?php do_action('spawp_frontpage_section_header',$subtitle,$subtitle_color,$title,$title_color,$desc,$desc_color,$divider_show,$divider_type,$divider_width); ?>

    <div class="row">
      <?php foreach ($items as $key => $item) { 
        $title = ! empty( $item->title ) ? apply_filters( 'spawp_translate_single_string', $item->title, 'Feature section' ) : '';
        $text = ! empty( $item->text ) ? apply_filters( 'spawp_translate_single_string', $item->text, 'Feature section' ) : '';
        $icon = ! empty( $item->icon_value ) ? $item->icon_value : '';
      ?>
      <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
        <div class="feature-box">
          <div class="media">
            <?php if($icon): ?>
            <div class="feature_icon mr-3"><a href=""><i class="fa <?php echo esc_attr( $icon ); ?>"></i></a></div>
            <?php endif; ?>
            <div class="media-body">
              <?php if($title): ?>
              <h5 class="feature_title"><a href=""><?php echo wp_kses_post( html_entity_decode( $title ) ); ?></a></h5>
              <?php endif; ?>

              <?php if($text): ?>
              <p class="feature_desc"><?php echo wp_kses_post( html_entity_decode( $text ) ); ?></p>
              <?php endif; ?>
            </div>
          </div>              
        </div>
      </div>
      <?php } ?>       
    </div>
  </div>
</section><!-- .feature -->
<?php } ?>