<?php
$section = 'testimonial';
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
$bg_image = spawp_get_option($section.'_bg_image');

$items = array();
$items = spawp_get_option($section.'_content');
if(!$items){
  $items = spwp_testimonial_default_contents();
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

  $slider_loop = false;
  if(count($items)>1){
    $slider_loop = true;
  }

if($bg_image){
?>
<style type="text/css">
  .home_section.testimonial .section_title,
  .home_section.testimonial.overlay{
    color:#fff;
  }
</style>
<?php } ?>
<section id="testimonial" class="home_section testimonial wow zoomIn <?php echo esc_attr($class); ?>" style="background-image: url('<?php echo esc_url_raw($bg_image); ?>');" <?php echo $section_attributes; ?>>
  <div class="<?php echo esc_attr($container); ?>">

    <?php do_action('spawp_frontpage_section_header',$subtitle,$subtitle_color,$title,$title_color,$desc,$desc_color,$divider_show,$divider_type,$divider_width); ?>
    
    <div class="row">
      <div class="col-12">
        <div id="testimonial-slider" class="owl-carousel owl-theme" data-collg="1" data-colmd="1" data-colsm="1" data-colxs="1" data-itemspace="0" data-loop="<?php echo esc_attr($slider_loop); ?>" data-autoplay="false" data-smartspeed="500" data-nav="true" data-dots="true">
          <?php foreach ($items as $key => $item) { 
            $title = ! empty( $item->title ) ? apply_filters( 'spawp_translate_single_string', $item->title, 'Testimonial section' ) : '';
            $designation = ! empty( $item->designation ) ? apply_filters( 'spawp_translate_single_string', $item->designation, 'Testimonial section' ) : '';
            $text = ! empty( $item->text ) ? apply_filters( 'spawp_translate_single_string', $item->text, 'Testimonial section' ) : '';
          ?>
          <div class="item">
            <div class="testimonial-box text-center">
              <div class="testimonial-content">
                <h3 class="testimonial-title"><?php echo wp_kses_post( html_entity_decode( $text ) ); ?></h3>
              </div>
              <figure class="testimonial-image">
                <a><img class="rounded-circle" src="<?php echo esc_url($item->image_url); ?>" alt="<?php echo esc_attr($title); ?>"></a>
              </figure>
              <figcaption>
                <cite class="testi-name"><a><?php echo wp_kses_post( html_entity_decode( $title ) ); ?></a></cite>
                <span class="position"><?php echo wp_kses_post( html_entity_decode( $designation ) ); ?></span>
              </figcaption>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</section><!-- .testimonial -->
<?php } ?>