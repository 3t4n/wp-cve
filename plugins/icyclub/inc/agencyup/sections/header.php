<?php 
if ( ! function_exists( 'icycp_agencyup_header' ) ) :
    function icycp_agencyup_header() {
  ?>

  <?php $header_contact_info_enable = get_theme_mod('header_contact_info_enable','1');
        $header_social_icon_enable = get_theme_mod('header_social_icon_enable','1'); ?>
    <div class="bs-head-detail hidden-xs hidden-sm">
      <div class="container">
        
        <div class="row align-items-center">
          <div class="col-md-6 col-xs-12">
          <?php 
          if($header_contact_info_enable == '1')
          { ?>
            <ul class="info-left">
                  <?php $agencyup_head_info_icon_one = get_theme_mod('agencyup_head_info_icon_one','fa-envelope');
                  $agencyup_head_info_icon_one_text = get_theme_mod('agencyup_head_info_icon_one_text','dummyxyz@gmail.com');
                  ?>
                  <li class="top-one"><a><i class="fas <?php echo esc_attr($agencyup_head_info_icon_one); ?>"></i> 
                    <?php echo esc_html($agencyup_head_info_icon_one_text);?></a>
                  </li>
                  <?php $agencyup_head_info_icon_two = get_theme_mod('agencyup_head_info_icon_two','fa-phone');
                    $agencyup_head_info_icon_two_text = get_theme_mod('agencyup_head_info_icon_two_text','9876543210');
                  ?>
                  <li class="top-two"><a><i class="fas <?php echo esc_attr($agencyup_head_info_icon_two); ?>"></i>
                    <?php echo esc_html($agencyup_head_info_icon_two_text); ?></a>
                  </li>
              </ul>
      <?php } ?>

          </div>
          <!--/col-md-6-->
          <div class="col-md-6 col-xs-12">
      <ul class="bs-social info-right">
      <?php if($header_social_icon_enable == 1)
      {
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
          <!--/col-md-6--> 
        </div>
      </div>
    </div>
    <!--/top-bar-->
    
  <?php
}
endif;
add_action('icycp_top_header', 'icycp_agencyup_header');