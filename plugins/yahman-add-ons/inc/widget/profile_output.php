<?php
defined( 'ABSPATH' ) || exit;
/**
 * Widget Profile output
 *
 * @package YAHMAN Add-ons
 */
function yahman_addons_profile_widget_output($settings){


  $has_bg = $has_bg_div = $has_bg_style = '';
  if($settings['image_bg'] != ''){ $has_bg = ' has_bg'; $has_bg_div = ' left0 right0 absolute'; $has_bg_style = ' style="top:50%;"';}
  ?>

  <div class="pf_img_wrap mb_M relative">
    <?php if($settings['image_bg'] != ''):
      $settings['fit_widget'] = ' fit_widget';
      if ( $settings['title'] ) {
        $settings['fit_widget'] = '';
      }
      ?>

      <div class="pf_bg fit_box_img_wrap<?php echo $settings['fit_widget']; ?>">
        <?php
        echo '<img src="'.esc_url($settings['image_bg']).'" height="160" width="310" alt="'.esc_html($settings['name']).'" />';
        ?>
      </div>

      <?php
    endif;
    if($settings['image'] != ''): ?>
     <div class="<?php echo esc_attr($has_bg); ?>">
      <div class="pf_img mla mra ta_c<?php echo esc_attr($has_bg_div);echo esc_attr($settings['image_shape']); ?>"<?php echo $has_bg_style; ?>>
        <?php
        echo '<img src="'.esc_url($settings['image']).'" class="'.esc_attr($settings['image_shape']).'" height="120" width="120" alt="'.esc_attr( apply_filters('yahman_addons_profile_name', $settings['name'] ) ).'" />';
        ?>
      </div>
    </div>
    <?php
  endif;
  ?>
</div>

<div class="pf_wrap">
  <div class="pf_name fw8 ta_c mb_S"><?php echo esc_html( apply_filters('yahman_addons_profile_name', $settings['name'] ) ); ?></div>
  <p class="pf_txt mb10 mb_M"><?php echo nl2br( apply_filters('yahman_addons_profile_text', $settings['text'] ) ); ?>
  <?php
  if($settings['read_more_url'] != ''){
    echo '<br /><a href="'.esc_url( apply_filters('yahman_addons_profile_read_more_url', $settings['read_more_url'] ) ).'" class="pf_rm"'.($settings['read_more_blank']).'>'.esc_html( apply_filters('yahman_addons_profile_read_more_text', $settings['read_more_text'] ) ).'</a>';
  }

  ?>
</p>

<?php

}

