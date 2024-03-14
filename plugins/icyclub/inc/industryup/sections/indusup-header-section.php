<?php 
if ( ! function_exists( 'icycp_industryup_header' ) ) :
    function icycp_industryup_header() {
  ?>

  <!--top-bar-->
    <!--top-bar-->
    <?php $header_contact_info_enable = get_theme_mod('header_contact_info_enable','1');
        $header_social_icon_enable = get_theme_mod('header_social_icon_enable','1'); 
      ?>


        <div class="ms-auto header_widgets_inner col-md-8">
        <?php $header_contact_info_enable = get_theme_mod('header_contact_info_enable','1');
          $industryup_head_info_icon_one = get_theme_mod('industryup_head_info_icon_one','fa-phone');
          $industryup_head_info_text_one = get_theme_mod('industryup_head_info_text_one','+ (007) 548 58 5400');
          $industryup_head_info_text_two = get_theme_mod('industryup_head_info_text_two','+ (007) 548 58 5400');

          $industryup_head_info_icon_two = get_theme_mod('industryup_head_info_icon_two','fa-clock');
          $industryup_head_info_text_three = get_theme_mod('industryup_head_info_text_three','7:30 AM - 7:30 PM');
          $industryup_head_info_text_four = get_theme_mod('industryup_head_info_text_four','Monday to Saturday');
            ?>

          <?php if($header_contact_info_enable == '1') { ?>
          <div class="media head_widget mr-3">
            <i class="mr-3 fas <?php echo esc_attr($industryup_head_info_icon_one); ?>"></i>
            <div class="media-body">
              <h5 class="mt-0"><?php echo esc_attr($industryup_head_info_text_one); ?></h5>
              <?php echo esc_attr($industryup_head_info_text_two); ?>
            </div>
          </div>
          <div class="media head_widget">
            <i class="mr-3 fas <?php echo esc_attr($industryup_head_info_icon_two); ?>"></i>
            <div class="media-body">
              <h5 class="mt-0"><?php echo esc_attr($industryup_head_info_text_three); ?></h5>
              <?php echo esc_attr($industryup_head_info_text_four); ?>
            </div>
          </div>
          <?php } if($header_social_icon_enable == 1)
                  { ?>
              <ul class="bs-social info-left">
                <?php
                  $industryup_header_fb_link = get_theme_mod('industryup_header_fb_link','#');
                  $industryup_header_fb_target = get_theme_mod('industryup_header_fb_target',1);
                  $industryup_header_twt_link = get_theme_mod('industryup_header_twt_link','#');
                  $industryup_header_twt_target = get_theme_mod('industryup_header_twt_target',1);
                  $industryup_header_lnkd_link = get_theme_mod('industryup_header_lnkd_link','#');
                  $industryup_twitter_lnkd_target = get_theme_mod('industryup_twitter_lnkd_target',1);
                  $industryup_header_insta_link = get_theme_mod('industryup_header_insta_link','#');
                  $industryup_insta_lnkd_target = get_theme_mod('industryup_insta_lnkd_target',1);
                ?>
                <?php if($industryup_header_fb_link !=''){?>
                <li>
                <a class="icon-soci" <?php if($industryup_header_fb_target) { ?> target="_blank" <?php } ?>
                href="<?php echo esc_url($industryup_header_fb_link); ?>"><i class="fab fa-facebook-f"></i></a> </li>
                <?php } if($industryup_header_twt_link !=''){ ?>
                <li><a class="icon-soci" <?php if($industryup_header_twt_target) { ?>target="_blank" <?php } ?>
                href="<?php echo esc_url($industryup_header_twt_link);?>"><i class="fab fa-twitter"></i></a></li>
                <?php } if($industryup_header_lnkd_link !=''){ ?>
                <li><a class="icon-soci" <?php if($industryup_twitter_lnkd_target) { ?>target="_blank" <?php } ?> 
                href="<?php echo esc_url($industryup_header_lnkd_link); ?>"><i class="fab fa-linkedin"></i></a></li>
                <?php } 
                if($industryup_header_insta_link !=''){ ?>
                <li><a class="icon-soci" <?php if($industryup_insta_lnkd_target) { ?>target="_blank" <?php } ?> 
                href="<?php echo esc_url($industryup_header_insta_link); ?>"><i class="fab fa-instagram"></i></a></li>
                <?php } ?>
                </ul>
                <?php } ?>
      <!--/top-bar-->
      </div>
    
  <?php
}
endif;
add_action('icycp_industryup_top_header', 'icycp_industryup_header');