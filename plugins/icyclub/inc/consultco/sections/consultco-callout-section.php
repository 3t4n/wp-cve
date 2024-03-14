<?php /**
 * Callout section
 */
if ( ! function_exists( 'icycp_consultco_callout' ) ) :

	function icycp_consultco_callout() {
		
		$callout_background_image = get_theme_mod('callout_background_image',ICYCP_PLUGIN_URL .'inc/agencyup/images/callout/callout-back.jpg');
		$callout_back_overlay_color = get_theme_mod('callout_back_overlay_color');
		$callout_title = get_theme_mod('callout_title',__('Trusted By Over 10,000 Worldwide Businesses. Try Today!
','agencyup'));
		$callout_discription = get_theme_mod('callout_discription','looking For Professional Approach & Qaulity Services!');
		$callout_btn_txt = get_theme_mod('callout_btn_txt',__('Get Started Now!','agencyup'));
		$callout_btn_link = get_theme_mod('callout_btn_link','https://themeansar.com/themes/consultco-pro/');
		$callout_btn_target = get_theme_mod('callout_btn_target',true); 
		$homepage_callout_show         = get_theme_mod('homepage_callout_show','1');
    	if($homepage_callout_show == '1') {
		?>
<div class="container">
    <?php if($callout_background_image != '') { ?>
<section class="bs-section calltoaction back-img" id="callout-section" style="background-image:url('<?php echo esc_url($callout_background_image);?>');">
<?php } else { ?>
<section class="bs-section calltoaction" id="callout-section">
<?php } ?>
<div class="overlay" style="background:<?php echo esc_attr($callout_back_overlay_color);?>;">
      <!--container-->
        <!--row-->
        <div class="row align-items-center">
          <!--consultup-callout-inner-->
          <div class="col-md-8">
            <div class="bs-heading text-left">
              <h3 class="bs-subtitle"><?php echo $callout_title;  ?></h3>
              	<h2 class="bs-title"><?php echo $callout_discription;  ?></h2>
			</div>
		   </div>

		   	<div class="col-md-4">
            <?php if($callout_btn_txt) {?>
                  <a <?php if($callout_btn_link) { ?> href="<?php echo $callout_btn_link; } ?>" 
						<?php if($callout_btn_target) { ?> target="_blank" <?php } ?> class="btn btn-theme-two">
						<?php if($callout_btn_txt) { echo $callout_btn_txt; } ?></a>
			<?php } ?>
			</div>
        	</div>
          <!--consultup-callout-inner-->
        </div>
        <!--/row-->
      <!--/conconsultupiner-->
</section>

</div>
<!-- /Portfolio Section -->

<div class="clearfix"></div>	
<?php } }

endif;

		if ( function_exists( 'icycp_consultco_callout' ) ) {
		$section_priority = apply_filters( 'icycp_consultup_homepage_section_priority', 15, 'icycp_consultco_callout' );
		add_action( 'icycp_consultco_homepage_sections', 'icycp_consultco_callout', absint( $section_priority ) );

		}
