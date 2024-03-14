<?php
$slider_show = spawp_get_option('slider_show');
$slider_overlay_show = spawp_get_option('slider_overlay_show');

$items = array();
$items = spawp_get_option('slider_content');
if(!$items){
  $items = spwp_slider_default_contents();
}

if(is_string($items)){
  $items = json_decode($items);
}

if($slider_show==true):

  $_overlay_class = '';
  if($slider_overlay_show){
     $_overlay_class = 'overlay';
  }

  $slider_loop = false;
  if(count($items)>1){
    $slider_loop = true;
  }

?>
<section id="slider" class="slider">
  <div id="banner-slider" class="owl-carousel owl-theme" data-collg="1" data-colmd="1" data-colsm="1" data-colxs="1" data-itemspace="0" data-loop="<?php echo esc_attr($slider_loop); ?>" data-autoplay="true" data-smartspeed="<?php echo esc_attr(spawp_get_option('slider_smart_speed')); ?>" data-scrollspeed="<?php echo esc_attr(spawp_get_option('slider_scroll_speed')); ?>" data-nav="<?php echo esc_attr(spawp_get_option('slider_nav_show')); ?>" data-dots="<?php echo esc_attr(spawp_get_option('slider_pagination_show')); ?>" data-mousedrag="<?php echo esc_attr(spawp_get_option('slider_mouse_drag')); ?>" data-animatein="<?php echo esc_attr(spawp_get_option('slider_animatein')); ?>" data-animateout="<?php echo esc_attr(spawp_get_option('slider_animateout')); ?>">
    <?php foreach ($items as $key => $item) { 
      $title = ! empty( $item->title ) ? apply_filters( 'spawp_translate_single_string', $item->title, 'Slider section' ) : '';
      $subtitle = ! empty( $item->subtitle ) ? apply_filters( 'spawp_translate_single_string', $item->subtitle, 'Slider section' ) : '';
      $text = ! empty( $item->text ) ? apply_filters( 'spawp_translate_single_string', $item->text, 'Slider section' ) : '';
      $button_text = ! empty( $item->button_text ) ? apply_filters( 'spawp_translate_single_string', $item->button_text, 'Slider section' ) : '';
      $link = ! empty( $item->link ) ? apply_filters( 'spawp_translate_single_string', $item->link, 'Slider section' ) : '';
      $checkbox_val = ! empty( $item->checkbox_val ) ? $item->checkbox_val : false;
      $text_align = ! empty( $item->content_align ) ? $item->content_align : 'left';
    ?>
    <div class="item">
      <div class="slide <?php echo esc_attr($_overlay_class); ?>" style="background-image:url(<?php echo $item->image_url; ?>);">
        <img src="<?php echo esc_url($item->image_url); ?>" alt="<?php echo esc_attr($title); ?>">
        <div class="slide__text h-100">
          <div class="<?php echo esc_attr(spawp_get_option('slider_container_width')); ?> h-100 d-flex">
            <div class="row w-100 align-self-center">
              <div class="col-lg-12">
                <div class="w-100 text-<?php echo esc_attr($text_align); ?>">
                  <?php if($subtitle){ ?>
                  <span class="slide_subtitle"><?php echo wp_kses_post( html_entity_decode( $subtitle ) ); ?></span>
                  <?php } ?>

                  <?php if($title){ ?>
                  <h3 class="slide_title"><?php echo wp_kses_post( html_entity_decode( $title ) ); ?></h3>
                  <?php } ?>

                  <?php if($text){ ?>
                  <p class="slide_decription"><?php echo wp_kses_post( html_entity_decode( $text ) ); ?></p>
                  <?php } ?>

                  <?php if($link){ ?>
                  <a class="slide_btn" href="<?php echo esc_url($link); ?>" <?php if($checkbox_val==true || $checkbox_val== '1') { echo "target='_blank'"; } ?>><?php echo esc_html($button_text); ?></a>
                  <?php } ?>
                </div>
              </div>
            </div>            
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>
</section><!-- .slider -->
<?php endif; ?>