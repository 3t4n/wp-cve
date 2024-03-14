<?php 
if ( ! function_exists( 'icycp_busiage_header' ) ) :
    function icycp_busiage_header() {
  ?>

          
          <div class="ml-auto header_widgets_inner">
            <?php 
          $header_contact_info_enable = get_theme_mod('header_contact_info_enable','1');
          $header_social_icon_enable = get_theme_mod('header_social_icon_enable','1'); 
          $agencyup_head_info_icon_one = get_theme_mod('agencyup_head_info_icon_one','fa-envelope');
          $agencyup_head_info_icon_one_text = get_theme_mod('agencyup_head_info_icon_one_text','dummyxyz@gmail.com');
          $agencyup_head_info_icon_two = get_theme_mod('agencyup_head_info_icon_two','fa-phone');
          $agencyup_head_info_icon_two_text = get_theme_mod('agencyup_head_info_icon_two_text','9876543210');
         if($header_contact_info_enable == '1')
          { 
         ?>
            <div class="media head_widget mr-3">
              <i class="mr-3 fas <?php echo esc_attr($agencyup_head_info_icon_one); ?>"></i>
              <div class="media-body">
                <h5 class="mt-0"><?php echo esc_html($agencyup_head_info_icon_one_text); ?></h5>
                Cras sit amet nibh libero, in 
              </div>
            </div>
            <div class="media head_widget">
              <i class="mr-3 fas <?php echo esc_attr($agencyup_head_info_icon_two); ?>"></i>
              <div class="media-body">
                <h5 class="mt-0"> <?php echo esc_html($agencyup_head_info_icon_two_text); ?></h5>
                Monday to Saturday 
              </div>
            </div>
            <?php } if($header_social_icon_enable == 1)
    { ?>
      <ul class="bs-social info-right">
      <?php
      $agencyup_header_fb_link = get_theme_mod('agencyup_header_fb_link','#');
      $agencyup_header_fb_target = get_theme_mod('agencyup_header_fb_target',1);
      $agencyup_header_twt_link = get_theme_mod('agencyup_header_twt_link','#');
      $agencyup_header_twt_target = get_theme_mod('agencyup_header_twt_target',1);
      $agencyup_header_lnkd_link = get_theme_mod('agencyup_header_lnkd_link','#');
      $agencyup_twitter_lnkd_target = get_theme_mod('agencyup_twitter_lnkd_target',1);
      $agencyup_header_insta_link = get_theme_mod('agencyup_header_insta_link','#');
      $agencyup_insta_lnkd_target = get_theme_mod('agencyup_insta_lnkd_target',1);
      ?>
      
      <?php if($agencyup_header_fb_link !=''){?>
      <li><span class="icon-soci"><a <?php if($agencyup_header_fb_target) { ?> target="_blank" <?php } ?>
      href="<?php echo esc_url($agencyup_header_fb_link); ?>"><i class="fab fa-facebook-f"></i></a></span> </li>
      <?php } if($agencyup_header_twt_link !=''){ ?>
      <li><span class="icon-soci"><a <?php if($agencyup_header_twt_target) { ?>target="_blank" <?php } ?>
      href="<?php echo esc_url($agencyup_header_twt_link);?>"><i class="fab fa-twitter"></i></a></span></li>
      <?php } if($agencyup_header_lnkd_link !=''){ ?>
      <li><span class="icon-soci"><a <?php if($agencyup_twitter_lnkd_target) { ?>target="_blank" <?php } ?> 
      href="<?php echo esc_url($agencyup_header_lnkd_link); ?>"><i class="fab fa-linkedin"></i></a></span></li>
      <?php } 
      if($agencyup_header_insta_link !=''){ ?>
      <li><span class="icon-soci"><a <?php if($agencyup_insta_lnkd_target) { ?>target="_blank" <?php } ?> 
      href="<?php echo esc_url($agencyup_header_insta_link); ?>"><i class="fab fa-instagram"></i></a></span></li>
      <?php } ?>
      </ul>
      <?php } ?>
    </div>
    
  <?php
}
endif;
add_action('icycp_busiage_top_header', 'icycp_busiage_header');