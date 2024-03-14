<?php 
if ( ! function_exists( 'icycp_consultco_header' ) ) :
    function icycp_consultco_header() {
  ?>

  <!--top-bar-->
    <!--top-bar-->
    <?php $header_contact_info_enable = get_theme_mod('header_contact_info_enable','1');
        $header_social_icon_enable = get_theme_mod('header_social_icon_enable','1'); 
      ?>
          <div class="row align-items-center">
            <div class="col">

              <?php 
              $consultco_head_text_enable = get_theme_mod('consultco_head_text_enable','1');
              if($consultco_head_text_enable == '1')
              { 
              $consultco_head_text = get_theme_mod('consultco_head_text','Welcome to our consulting company ConsultCorp');
              ?>
              <ul class="info-left top-text">
                <li><span><?php echo $consultco_head_text;?></span></li>
              </ul>
              <?php } ?>
              <ul class="bs-social info-left">
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
                <li>
                <a class="icon-soci" <?php if($agencyup_header_fb_target) { ?> target="_blank" <?php } ?>
                href="<?php echo esc_url($agencyup_header_fb_link); ?>"><i class="fab fa-facebook-f"></i></a> </li>
                <?php } if($agencyup_header_twt_link !=''){ ?>
                <li><a class="icon-soci" <?php if($agencyup_header_twt_target) { ?>target="_blank" <?php } ?>
                href="<?php echo esc_url($agencyup_header_twt_link);?>"><i class="fab fa-twitter"></i></a></li>
                <?php } if($agencyup_header_lnkd_link !=''){ ?>
                <li><a class="icon-soci" <?php if($agencyup_twitter_lnkd_target) { ?>target="_blank" <?php } ?> 
                href="<?php echo esc_url($agencyup_header_lnkd_link); ?>"><i class="fab fa-linkedin"></i></a></li>
                <?php } 
                if($agencyup_header_insta_link !=''){ ?>
                <li><a class="icon-soci" <?php if($agencyup_insta_lnkd_target) { ?>target="_blank" <?php } ?> 
                href="<?php echo esc_url($agencyup_header_insta_link); ?>"><i class="fab fa-instagram"></i></a></li>
                <?php } ?>
                </ul>
                <?php } ?>
            </div>
            <!--/col-md-6-->
              <?php 
            if($header_contact_info_enable == '1')
            { ?>
            <div class="ms-auto header_widgets_inner col top-one">
              <div class="media head_widget mr-3">
                <?php $agencyup_head_info_icon_one = get_theme_mod('agencyup_head_info_icon_one','fa-phone');
                  $agencyup_head_info_text_one = get_theme_mod('agencyup_head_info_text_one','+ (007) 548 58 5400');
                  $agencyup_head_info_text_two = get_theme_mod('agencyup_head_info_text_two','+ (007) 548 58 5400');
                  ?>
                <i class="mr-3 fas <?php echo esc_attr($agencyup_head_info_icon_one); ?>"></i>
                <div class="media-body">
                  <h5 class="mt-0"><?php echo esc_html($agencyup_head_info_text_one);?></h5>
                  <?php echo esc_html($agencyup_head_info_text_two);?>

                </div>
              </div>
              <div class="media head_widget top-two">
                <?php $agencyup_head_info_icon_two = get_theme_mod('agencyup_head_info_icon_two','fa-phone');
                    $agencyup_head_info_text_three = get_theme_mod('agencyup_head_info_text_three','7:30 AM - 7:30 PM');
                  $agencyup_head_info_text_four = get_theme_mod('agencyup_head_info_text_four','Monday to Saturday');
                  ?>
                <i class="fas <?php echo esc_attr($agencyup_head_info_icon_two); ?>"></i>
                <div class="media-body">
                  <h5 class="mt-0"><?php echo esc_html($agencyup_head_info_text_three); ?></h5>
                  <?php echo esc_html($agencyup_head_info_text_four);?>
                </div>
              </div>
            </div>
            <!--/col-md-6-->
          <?php } ?>
          </div>
      <!--/top-bar-->
    
  <?php
}
endif;
add_action('icycp_top_header', 'icycp_consultco_header');