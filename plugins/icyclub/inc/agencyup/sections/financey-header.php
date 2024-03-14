<?php 
if ( ! function_exists( 'icycp_financey_header' ) ) :
    function icycp_financey_header() {
  ?>

<?php $header_social_icon_enable = get_theme_mod('header_social_icon_enable','1'); ?>
   <!--top-bar-->
    <div class="bs-head-detail two d-none d-md-block">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-6 col-xs-12">
            <?php if($header_social_icon_enable == 1) { ?>
      <ul class="bs-social info-left">
      <?php
      $agencyup_header_fb_link = get_theme_mod('agencyup_header_fb_link','#');
      $agencyup_header_fb_target = get_theme_mod('agencyup_header_fb_target',1);
      $agencyup_header_twt_link = get_theme_mod('agencyup_header_twt_link','#');
      $agencyup_header_twt_target = get_theme_mod('agencyup_header_twt_target',1);
      $agencyup_header_lnkd_link = get_theme_mod('agencyup_header_lnkd_link','#');
      $agencyup_twitter_lnkd_target = get_theme_mod('agencyup_twitter_lnkd_target',1);
      $agencyup_header_insta_link = get_theme_mod('agencyup_header_insta_link','#');
      $agencyup_insta_lnkd_target = get_theme_mod('agencyup_insta_lnkd_target',1);
       
      if($agencyup_header_fb_link !=''){?>
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
          $header_contact_info_enable = get_theme_mod('header_contact_info_enable','1');
          $financey_head_info_icon_one = get_theme_mod('financey_head_info_icon_one','fa-map-marker');
          $financey_head_info_icon_one_text = get_theme_mod('financey_head_info_icon_one_text','1240 Park Avenue,');
          $financey_head_info_icon_one_two_text = get_theme_mod('financey_head_info_icon_one_two_text','NYC, USA 256323');
          $financey_head_info_icon_two = get_theme_mod('financey_head_info_icon_two','fa-phone-alt');
          $financey_head_info_icon_two_text = get_theme_mod('financey_head_info_icon_two_text','Free Consult');
          $financey_head_info_icon_two_two_text = get_theme_mod('financey_head_info_icon_two_two_text','+ (007) 548 58 5400');
          $financey_head_info_icon_three = get_theme_mod('financey_head_info_icon_three','fa-clock');
          $financey_head_info_icon_three_text = get_theme_mod('financey_head_info_icon_three_text','Mon - Sat :');
          $financey_head_info_icon_three_two_text = get_theme_mod('financey_head_info_icon_three_two_text','10:00AM - 7:00PM');

          if($header_contact_info_enable == '1')
          {

          
          ?>
          <!--/col-md-6-->
          <div class="col-md-6 col-xs-12">
            <ul class="info-right">                
                                    <li>
                                        <div class="info_widget ">
                                             <i class="fas <?php echo esc_attr($financey_head_info_icon_one); ?>"></i> <div class="inner"><strong><?php echo esc_html($financey_head_info_icon_one_text); ?></strong> <?php echo esc_html($financey_head_info_icon_one_two_text); ?></div>
                                        </div>
                                      
                                    </li>
                                     <li>
                                           <div class="info_widget">
                                             <i class="fas <?php echo esc_attr($financey_head_info_icon_two); ?>"></i> <div class="inner"><strong><?php echo esc_html($financey_head_info_icon_two_text); ?></strong><?php echo esc_html($financey_head_info_icon_two_two_text); ?></div>
                                        </div>
                                      
                                    </li>
                                    <li>
                                         <div class="info_widget">
                                             <i class="fas <?php echo esc_attr($financey_head_info_icon_three); ?>"></i><div class="inner"><strong><?php echo esc_html($financey_head_info_icon_three_text); ?></strong> <?php echo esc_html($financey_head_info_icon_three_two_text); ?></div>
                                        </div>
                                      
                                    </li>

            </ul>
          </div>
          <!--/col-md-6--> 
        <?php } ?>
        </div>
      </div>
    </div>
    <!--/top-bar-->
  <?php 
}
endif;
add_action('icycp_financey_top_header', 'icycp_financey_header');