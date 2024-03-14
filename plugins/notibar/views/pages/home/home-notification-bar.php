<?php
  defined('ABSPATH') || exit;
  $textContent = '';
  $buttonText = '';
  $buttonUrl = '';
  $isNewWindown = false;
  $isDisplayButton = false;

  $classDeskop = '';
  $classMobile = '';
  if(get_theme_mod('njt_nofi_content_mobile', 0)) {
    $classDeskop = 'njt-display-deskop';
    $classMobile = 'njt-display-mobile';
  }

  $textContentMobile = get_option('njt_nofi_text_mobile_wpml_translate');
  $buttonTextMobile = get_option('njt_nofi_lb_text_mobile_wpml_translate');
  $buttonUrlMobile = get_option('njt_nofi_lb_url_mobile_wpml_translate');
  $buttonFontWeightMobile = get_theme_mod('njt_nofi_lb_font_weight_mobile', 400);
  $isNewWindownMobile = get_theme_mod('njt_nofi_open_new_windown_mobile', $this->valueDefault['new_windown_mobile']);
  $isDisplayButtonMobile = get_theme_mod('njt_nofi_handle_button_mobile', 1);

  $textContent = get_option('njt_nofi_text_wpml_translate');
  $buttonText = get_option('njt_nofi_lb_text_wpml_translate');
  $buttonUrl = get_option('njt_nofi_lb_url_wpml_translate');
  $buttonFontWeight = get_theme_mod('njt_nofi_lb_font_weight', 400);
  $isNewWindown = get_theme_mod('njt_nofi_open_new_windown', $this->valueDefault['new_windown']);
  $isDisplayButton = get_theme_mod('njt_nofi_handle_button', 1);
?>
<div class="njt-nofi-container-content">
<div class="njt-nofi-container" >
  <div class="njt-nofi-notification-bar njt-nofi-bgcolor-notification" style="<?php echo('background:'.esc_attr($bgColorNotification)) ?>">
    
    <div class="njt-nofi-content njt-nofi-text-color njt-nofi-align-content njt-nofi-content-deskop <?php echo ($classDeskop)?>" style="max-width:<?php echo esc_attr($contentWidth) ?>">
      <div class="njt-nofi-text njt-nofi-padding-text"><?php echo wp_kses_post(do_shortcode($textContent))?></div>
      <div class="njt-nofi-button njt-nofi-padding-text " style="<?php if(!$isDisplayButton) { echo ('display: none');}?>">
          <a <?php if($isNewWindown) {echo ("target='_blank'");}?>  href="<?php echo esc_url($buttonUrl)?>" class="njt-nofi-button-text njt-nofi-padding-text" style="<?php if($isDisplayButton) { echo ('background:' .esc_attr($lbColorNotification).';border-radius:3px;font-weight:'.esc_attr($buttonFontWeight));}?>"><?php echo esc_html($buttonText)?></a>
      </div>
    </div>

    <div class="njt-nofi-content njt-nofi-text-color njt-nofi-align-content njt-display-none njt-nofi-content-mobile <?php echo ($classMobile)?>" style="max-width:<?php echo esc_attr($contentWidth) ?>">
      <div class="njt-nofi-text njt-nofi-padding-text"><?php echo wp_kses_post(do_shortcode($textContentMobile))?></div>
      <div class="njt-nofi-button njt-nofi-padding-text " style="<?php if(!$isDisplayButtonMobile) { echo ('display: none');}?>">
          <a <?php if($isNewWindownMobile) {echo ("target='_blank'");}?>  href="<?php echo esc_url($buttonUrlMobile)?>" class="njt-nofi-button-text njt-nofi-padding-text" style="<?php if($isDisplayButtonMobile) { echo ('background:' .esc_attr($lbColorNotification).';border-radius:3px;font-weight:'.esc_attr($buttonFontWeightMobile));}?>"><?php echo esc_html($buttonTextMobile)?></a>
      </div>
    </div>

    <a href="javascript:void(0)" class="njt-nofi-toggle-button njt-nofi-hide njt-nofi-text-color njt-nofi-hide-admin-custom">
      <span>
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" version="1.1" width="512" height="512" x="0" y="0" viewBox="0 0 386.667 386.667" style="enable-background:new 0 0 512 512" xml:space="preserve" class="njt-nofi-close-icon"><g><path xmlns="http://www.w3.org/2000/svg" d="m386.667 45.564-45.564-45.564-147.77 147.769-147.769-147.769-45.564 45.564 147.769 147.769-147.769 147.77 45.564 45.564 147.769-147.769 147.769 147.769 45.564-45.564-147.768-147.77z" fill="#ffffff" data-original="#000000" style="" class=""/></g></svg>
      </span>
    </a>
    <a href="javascript:void(0)" class="njt-nofi-close-button njt-nofi-hide njt-nofi-text-color njt-nofi-hide-admin-custom">
      <span>
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" version="1.1" width="512" height="512" x="0" y="0" viewBox="0 0 386.667 386.667" style="enable-background:new 0 0 512 512" xml:space="preserve" class="njt-nofi-close-icon"><g><path xmlns="http://www.w3.org/2000/svg" d="m386.667 45.564-45.564-45.564-147.77 147.769-147.769-147.769-45.564 45.564 147.769 147.769-147.769 147.77 45.564 45.564 147.769-147.769 147.769 147.769 45.564-45.564-147.768-147.77z" fill="#ffffff" data-original="#000000" style="" class=""/></g></svg>
      </span>
    </a>  
  </div>
  <div>
    <a href="javascript:void(0)" class="njt-nofi-display-toggle njt-nofi-text-color njt-nofi-bgcolor-notification" style="<?php echo('background:'.esc_attr($bgColorNotification)) ?>">
      <span>
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" version="1.1" width="512" height="512" x="0" y="0" viewBox="0 0 386.667 386.667" style="enable-background:new 0 0 512 512" xml:space="preserve" class="njt-nofi-display-toggle-icon"><g><path xmlns="http://www.w3.org/2000/svg" d="m386.667 45.564-45.564-45.564-147.77 147.769-147.769-147.769-45.564 45.564 147.769 147.769-147.769 147.77 45.564 45.564 147.769-147.769 147.769 147.769 45.564-45.564-147.768-147.77z" fill="#ffffff" data-original="#000000" style="" class=""/></g></svg>
      </span>
    </a>
  </div>
</div>
</div>


