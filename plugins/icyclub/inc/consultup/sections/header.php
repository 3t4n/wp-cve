<?php 
if ( ! function_exists( 'icycp_consultup_header' ) ) :
    function icycp_consultup_header() {
  ?>

  <?php 
      $consultup_head_info_icon_one = get_theme_mod('consultup_head_info_icon_one','fa-clock');
      $consultup_head_info_icon_one_text = get_theme_mod('consultup_head_info_icon_one_text','Open-Hours:10 am to 7pm');
      $consultup_head_info_icon_two = get_theme_mod('consultup_head_info_icon_two','fa-envelope');
      $consultup_head_info_icon_two_text = get_theme_mod('consultup_head_info_icon_two_text','info@themeansar.com');
      $header_social_icon_enable = get_theme_mod('header_social_icon_enable','on');
      $consultup_header_fb_link = get_theme_mod('consultup_header_fb_link','#');
      $consultup_header_fb_target = get_theme_mod('consultup_header_fb_target',1);
      $consultup_header_twt_link = get_theme_mod('consultup_header_twt_link','#');
      $consultup_header_twt_target = get_theme_mod('consultup_header_twt_target',1);
      $consultup_header_lnkd_link = get_theme_mod('consultup_header_lnkd_link','#');
      $consultup_twitter_lnkd_target = get_theme_mod('consultup_twitter_lnkd_target',1);
      $consultup_header_insta_link = get_theme_mod('consultup_header_insta_link','#');
      $consultup_insta_lnkd_target = get_theme_mod('consultup_insta_lnkd_target',1);
    if(($consultup_head_info_icon_one) || ($consultup_head_info_icon_two_text) || ($consultup_head_info_icon_one_text) || ($consultup_head_info_icon_two) || ($consultup_header_twt_link) || ($consultup_header_lnkd_link) || ($consultup_header_insta_link) || ($consultup_header_fb_link) !=''){ 
      ?>
    <div class="ti-head-detail d-none d-lg-block">
      <div class="row">
    
        <div class="col-md-6 col-xs-12 col-sm-6">
         <ul class="info-left">
          <li><i class="far <?php echo esc_attr( $consultup_head_info_icon_one ); ?> "></i> <?php echo esc_html( $consultup_head_info_icon_one_text );?></li>
          <li><i class="far <?php echo esc_attr( $consultup_head_info_icon_two ); ?> "></i> <?php echo esc_html( $consultup_head_info_icon_two_text ); ?></li>
          </ul>
        </div>
   
      <div class="col-md-6 col-xs-12">
      <?php 
      if($header_social_icon_enable !='off')
      {
      ?>
      <ul class="ti-social-icon ti-social info-right">
      <?php if($consultup_header_fb_link !=''){?>
      <li><span class="icon-soci"><a <?php if($consultup_header_fb_target) { ?> target="_blank" <?php } ?>href="<?php echo esc_url($consultup_header_fb_link); ?>"><i class="fab fa-facebook"></i></a></span> </li>
      <?php } if($consultup_header_twt_link !=''){ ?>
      <li><span class="icon-soci"><a <?php if($consultup_header_twt_target) { ?>target="_blank" <?php } ?>href="<?php echo esc_url($consultup_header_twt_link);?>"><i class="fab fa-twitter"></i></a></span></li>
      <?php } if($consultup_header_lnkd_link !=''){ ?>
      <li><span class="icon-soci"><a <?php if($consultup_twitter_lnkd_target) { ?>target="_blank" <?php } ?> href="<?php echo esc_url($consultup_header_lnkd_link); ?>"><i class="fab fa-linkedin"></i></a></span></li>
      <?php } 
      if($consultup_header_insta_link !=''){ ?>
      <li><span class="icon-soci"><a <?php if($consultup_insta_lnkd_target) { ?>target="_blank" <?php } ?> href="<?php echo esc_url($consultup_header_insta_link); ?>"><i class="fab fa-instagram"></i></a></span></li>
      <?php } ?>
      </ul>
      <?php } ?>
    </div>
      </div>
    </div>
    <?php } 
}
endif;
add_action('icycp_consultup_top_header', 'icycp_consultup_header');



if ( ! function_exists( 'icycp_consultup_widget_header' ) ) :
    function icycp_consultup_widget_header() {
  ?>

   <div class="col-md-9 col-sm-8">
            <div class="header-widget row">
              <div class="col-md-3 offset-md-3 col-sm-3 col-xs-6 hidden-sm hidden-xs">
                <div class="ti-header-box">
                  <div class="ti-header-box-icon">
                    <?php $consultup_header_widget_one_icon = get_theme_mod('consultup_header_widget_one_icon','fa-clock');
                    if( !empty($consultup_header_widget_one_icon) ):
                      echo '<i class="far '.esc_attr($consultup_header_widget_one_icon).'">'.'</i>';
                    endif; ?>
                   </div>
                  <div class="ti-header-box-info">
                    <?php $consultup_header_widget_one_title = get_theme_mod('consultup_header_widget_one_title','Call Us:'); 
                    if( !empty($consultup_header_widget_one_title) ):
                      echo '<h4>'.esc_html($consultup_header_widget_one_title).'</h4>';
                    endif; ?>
                    <?php $consultup_header_widget_one_description = get_theme_mod('consultup_header_widget_one_description','+ 007 548 58');
                    if( !empty($consultup_header_widget_one_description) ):
                      echo '<p>'.esc_html($consultup_header_widget_one_description).'</p>';
                    endif; ?> 
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-6 hidden-sm hidden-xs">
                <div class="ti-header-box">
                  <div class="ti-header-box-icon">
                    <?php $consultup_header_widget_two_icon = get_theme_mod('consultup_header_widget_two_icon','fa-envelope');
                    if( !empty($consultup_header_widget_two_icon) ):
                      echo '<i class="fas '.esc_attr($consultup_header_widget_two_icon).'">'.'</i>';
                    endif; ?>
                   </div>
                  <div class="ti-header-box-info">
                    <?php $consultup_header_widget_two_title = get_theme_mod('consultup_header_widget_two_title','Email Us:'); 
                    if( !empty($consultup_header_widget_two_title) ):
                      echo '<h4>'.esc_html($consultup_header_widget_two_title).'</h4>';
                    endif; ?>
                    <?php $consultup_header_widget_two_description = get_theme_mod('consultup_header_widget_two_description','info@themeansar.com');
                    if( !empty($consultup_header_widget_two_description) ):
                      echo '<p>'.esc_html($consultup_header_widget_two_description).'</p>';
                    endif; ?> 
                  </div>
                </div>
              </div>
         <div class="col-md-3 col-sm-6 col-xs-12 hidden-sm hidden-xs">
                <div class="ti-header-box ti-header-read-btn text-right"> 
                  <?php $consultup_header_widget_four_label = get_theme_mod('consultup_header_widget_four_label','Get Quote'); 
                  $consultup_header_widget_four_link = get_theme_mod('consultup_header_widget_four_link','#');
                  $consultup_header_widget_four_target = get_theme_mod('consultup_header_widget_four_target'); 
          if( !empty($consultup_header_widget_four_label) ):?>
          <a href="<?php echo esc_url($consultup_header_widget_four_link); ?>" <?php if( $consultup_header_widget_four_target ==true) { echo "target='_blank'"; } ?> class="btn btn-theme"><?php echo esc_html($consultup_header_widget_four_label); ?></a> 
          <?php endif; ?>
                </div>
         </div>
            </div>
          </div>
    <?php } 
endif;
add_action('icycp_top_widget_header', 'icycp_consultup_widget_header');