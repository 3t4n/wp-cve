<?php if ( ! function_exists( 'icycp_industryup_top_contact' ) ) :

	function icycp_industryup_top_contact() {

	$contact_one_icon = get_theme_mod('contact_one_icon','fa-map-marker');
	$contact_one_title = get_theme_mod('contact_one_title','Head Office');
	$contact_one_description = get_theme_mod('contact_one_description','4578 Marmora Road, Glasgow');

	$contact_two_icon = get_theme_mod('contact_two_icon','fa-phone');
	$contact_two_title = get_theme_mod('contact_two_title','Call Us');
	$contact_two_description = get_theme_mod('contact_two_description','(+81) 123-456-7890	');

	$contact_three_icon = get_theme_mod('contact_three_icon','fa-envelope-open');
	$contact_three_title = get_theme_mod('contact_three_title','7:30 AM - 7:30 PM');
	$contact_three_description = get_theme_mod('contact_three_description','Monday to Saturday');
  $contact_info_section_show         = get_theme_mod('contact_info_section_show','1');
  if($contact_info_section_show == '1') {      
?>
<div class="clearfix"></div>
  <div class="top-ct-section">
    <div class="overlay">
    <div class="container">
      <div class="row">
        <div class="col-md-4 contact-info-one">
          <div class="media feature_widget">
            <i class="mr-3 fa <?php echo esc_attr($contact_one_icon); ?>"></i>
            <div class="media-body">
              <h5 class="mt-0"><?php echo esc_html($contact_one_title); ?></h5>
              <?php echo esc_html($contact_one_description); ?>
            </div>
          </div>
        </div>
        <div class="col-md-4 contact-info-two">
          <div class="media feature_widget">
            <i class="mr-3 fa <?php echo esc_attr($contact_two_icon); ?>"></i>
            <div class="media-body">
              <h5 class="mt-0"><?php echo esc_html($contact_two_title); ?></h5>
              <?php echo esc_html($contact_two_description); ?>
            </div>
          </div>
        </div>
        <div class="col-md-4 contact-info-three">
          <div class="media feature_widget">
            <i class="mr-3 fa <?php echo esc_attr($contact_three_icon); ?>"></i>
            <div class="media-body">
              <h5 class="mt-0"><?php echo esc_html($contact_three_title); ?></h5>
              <?php echo esc_html($contact_three_description); ?>
            </div>
          </div>
        </div>
      </div> 
    </div>
  </div>
</div>
<?php
}
}

endif;

if ( function_exists( 'icycp_industryup_top_contact' ) ) {
$homepage_section_priority = apply_filters( 'icycp_industryup_homepage_section_priority', 11, 'icycp_industryup_top_contact' );
add_action( 'icycp_industryup_homepage_sections', 'icycp_industryup_top_contact', absint( $homepage_section_priority ) );
}