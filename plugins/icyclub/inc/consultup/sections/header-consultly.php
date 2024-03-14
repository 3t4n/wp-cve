<?php if ( ! function_exists( 'icycp_consultly_widget_header' ) ) :
    function icycp_consultly_widget_header() {
  ?>
          <div class="desk-header ml-auto my-2 my-lg-0 position-relative align-items-center"> 
                  <?php $consultup_header_widget_four_label = get_theme_mod('consultup_header_widget_four_label','Get Quote'); 
                  $consultup_header_widget_four_link = get_theme_mod('consultup_header_widget_four_link','#');
                  $consultup_header_widget_four_target = get_theme_mod('consultup_header_widget_four_target'); 
          if( !empty($consultup_header_widget_four_label) ):?>
          <a href="<?php echo esc_url($consultup_header_widget_four_link); ?>" <?php if( $consultup_header_widget_four_target ==true) { echo "target='_blank'"; } ?> class="btn btn-theme"><?php echo esc_html($consultup_header_widget_four_label); ?></a> 
          <?php endif; ?> 
          </div>
    <?php } 
endif;
add_action('icycp_btn_widget_header', 'icycp_consultly_widget_header');