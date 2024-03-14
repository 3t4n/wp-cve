<?php
$section = 'service';
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
  $items = spwp_service_default_contents();
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
<section id="service" class="home_section service wow zoomIn <?php echo esc_attr($class); ?>" <?php echo $section_attributes; ?>>
  <div class="<?php echo esc_attr($container); ?>">

    <?php do_action('spawp_frontpage_section_header',$subtitle,$subtitle_color,$title,$title_color,$desc,$desc_color,$divider_show,$divider_type,$divider_width); ?>

    <div class="row">
      <?php foreach ($items as $key => $item) { 
        $title = ! empty( $item->title ) ? apply_filters( 'spawp_translate_single_string', $item->title, 'Service section' ) : '';
        $text = ! empty( $item->text ) ? apply_filters( 'spawp_translate_single_string', $item->text, 'Service section' ) : '';
        $button_text = ! empty( $item->button_text ) ? apply_filters( 'spawp_translate_single_string', $item->button_text, 'Service section' ) : '';
        $link = ! empty( $item->link ) ? apply_filters( 'spawp_translate_single_string', $item->link, 'Service section' ) : '';
        $checkbox_val = ! empty( $item->checkbox_val ) ? $item->checkbox_val : false;
        $currency = ! empty( $item->currency ) ? $item->currency : '$';
        $price = ! empty( $item->price ) ? $item->price : '';
      ?>
      <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
        <div class="service-box">
          <?php if($item->image_url): ?>
          <div class="thumbnail_image">
            <img src="<?php echo esc_url($item->image_url); ?>" alt="<?php echo esc_attr($title); ?>"/>
          </div>
          <?php endif; ?>
          <div class="service_content text-center">

            <?php if($title): ?><h5 class="service_title"><a href="<?php echo esc_url($link); ?>"><?php echo wp_kses_post( html_entity_decode( $title ) ); ?></a></h5><?php endif; ?>

            <?php if($text): ?><p class="sercice_desc"><?php echo wp_kses_post( html_entity_decode( $text ) ); ?></p><?php endif; ?>
            
            <?php if($price): ?>
            <span class="item_pricing"><?php echo esc_html($currency . $price); ?></span>
            <?php endif; ?>

            <?php if($link): ?>
            <a href="<?php echo esc_url($link); ?>" class="link_btn" <?php if($checkbox_val==true || $checkbox_val== '1') { echo "target='_blank'"; } ?>><?php echo esc_html($button_text); ?> <i class="fa fa-long-arrow-right"></i></a>
            <?php endif; ?>
          </div>              
        </div>
      </div>
      <?php } ?>          
    </div>
  </div>
</section><!-- .service -->
<?php } ?>