<?php 
if ( ! function_exists( 'icycp_cargoup_top_header' ) ) :
    function icycp_cargoup_top_header() {

    $header_contact_info_enable = get_theme_mod('header_contact_info_enable','1');
    $header_social_icon_enable = get_theme_mod('header_social_icon_enable','1');
    $cargoup_head_info_icon_one = get_theme_mod('cargoup_head_info_icon_one','fa-clock');
    $cargoup_head_info_text_one = get_theme_mod('cargoup_head_info_text_one','Open-Hours:10 am to 7pm');
    $cargoup_head_info_icon_two = get_theme_mod('cargoup_head_info_icon_two','fa-envelope-open');
    $cargoup_head_info_text_three = get_theme_mod('cargoup_head_info_text_three','info@yoursite.com');
?>
<div class="bs-head-detail d-none d-md-block">
        <div class="container">
          <div class="row align-items-center">
            <?php if($header_contact_info_enable == '1') { ?>
            <div class="col-md-6 col-xs-12">
              <ul class="info-left">
                <li><a><i class="fas <?php echo $cargoup_head_info_icon_one; ?>"></i> <?php echo $cargoup_head_info_text_one; ?></a></li>
                <li><a><i class="fas <?php  echo $cargoup_head_info_icon_two; ?>"></i><?php echo $cargoup_head_info_text_three; ?></a></li>
              </ul>
            </div>
          <?php } ?>
            <!--/col-md-6-->
            <div class="col-md-6 col-xs-12">
              <?php if($header_social_icon_enable == 1)
                  { ?>
              <ul class="bs-social info-right">
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
            </div>
            <!--/col-md-6-->
          </div>
        </div>
      </div>
<?php
}
endif;
add_action('icycp_action_cargoup_top_header', 'icycp_cargoup_top_header');

if ( ! function_exists( 'icycp_cargoup_info_header' ) ) :
    function icycp_cargoup_info_header() {

    $header_right_contact_info_enable = get_theme_mod('header_right_contact_info_enable','1');
    $cargoup_contact_icon_one = get_theme_mod('cargoup_contact_icon_one','fa-phone');
    $cargoup_contact_text_two = get_theme_mod('cargoup_contact_text_two','+ (007) 548 58 5400');
    $cargoup_contact_text_three = get_theme_mod('cargoup_contact_text_three','+ (007) 548 58 5400');
    $cargoup_contact_icon_four = get_theme_mod('cargoup_contact_icon_four','fa-phone');
    $cargoup_contact_text_five = get_theme_mod('cargoup_contact_text_five','7:30 AM - 7:30 PM');
    $cargoup_contact_text_six = get_theme_mod('cargoup_contact_text_six','Monday to Saturday');
    $cargoup_contact_icon_seven = get_theme_mod('cargoup_contact_icon_seven','fa-phone');
    $cargoup_contact_text_eight = get_theme_mod('cargoup_contact_text_eight','info@yoursite.com');
    $cargoup_contact_text_nine = get_theme_mod('cargoup_contact_text_nine','fa-clock');
    if($header_right_contact_info_enable == 1) { 
?>
<div class="ms-auto header_widgets_inner col-md-8">
      <div class="media head_widget mr-3">
        <i class="mr-3 fas <?php echo $cargoup_contact_icon_one; ?>"></i>
        <div class="media-body">
          <h5 class="mt-0"><?php echo $cargoup_contact_text_two; ?></h5>
          <?php echo $cargoup_contact_text_three; ?>
        </div>
      </div>
      <div class="media head_widget">
        <i class="mr-3 fas <?php echo $cargoup_contact_icon_four; ?>"></i>
        <div class="media-body">
          <h5 class="mt-0"><?php echo $cargoup_contact_text_five; ?></h5>
          <?php echo $cargoup_contact_text_six; ?>
        </div>
      </div>
      <div class="media head_widget">
        <i class="mr-3 fas <?php echo $cargoup_contact_icon_seven; ?>"></i>
        <div class="media-body">
          <h5 class="mt-0"><?php echo $cargoup_contact_text_eight; ?></h5>
          <?php echo $cargoup_contact_text_nine; ?>
        </div>
      </div>
  </div>
<?php
} }
endif;
add_action('icycp_action_cargoup_info_header', 'icycp_cargoup_info_header');
?>