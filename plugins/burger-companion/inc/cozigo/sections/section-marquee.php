<?php 
if ( ! function_exists( 'burger_cozigo_marquee' ) ) :
	function burger_cozigo_marquee() {
		$hs_marquee					= get_theme_mod('hs_marquee','1');
		$marquee_description		= get_theme_mod('marquee_description','* Advertising * Development * Design * Business * Marketing * Consultant * Advertising * Development * Design * Business * Marketing * Consultant');

		if($hs_marquee == '1'){	
			?>
			<section class="text-marquee_home_section">
				<?php if ( ! empty( $marquee_description ) ) : ?>
					<div class="scrolling-text marquee-title">
						<span><?php echo wp_kses_post($marquee_description); ?></span>
					</div>
				<?php endif; ?>	
			</section> 
			<?php	
		}}
	endif;
	if ( function_exists( 'burger_cozigo_marquee' ) ) {
		$section_priority = apply_filters( 'cozipress_section_priority', 12, 'burger_cozigo_marquee' );
		add_action( 'cozipress_sections', 'burger_cozigo_marquee', absint( $section_priority ) );
	}	