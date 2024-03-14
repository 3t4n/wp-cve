<?php 
if ( !function_exists( 'bc_hp_callout' ) ) :
	function bc_hp_callout(){
		global $hotelone_options_default;
		$disable_callout = get_theme_mod( 'hotelone_calltoaction_hide', $hotelone_options_default['hotelone_calltoaction_hide']);
		$callout_title = get_theme_mod( 'hotelone_calltoaction_title', $hotelone_options_default['hotelone_calltoaction_title']);
		$callout_subtitle  = get_theme_mod( 'hotelone_calltoaction_subtitle', $hotelone_options_default['hotelone_calltoaction_subtitle']);
		$callout_button_text = get_theme_mod( 'hotelone_calltoaction_btn_text', $hotelone_options_default['hotelone_calltoaction_btn_text']);
		$callout_button_url = get_theme_mod( 'hotelone_calltoaction_btn_URL', $hotelone_options_default['hotelone_calltoaction_btn_URL']);
		$bgcolor = get_theme_mod( 'hotelone_calltoaction_bgcolor', $hotelone_options_default['hotelone_calltoaction_bgcolor']);
		$bgimage = get_theme_mod( 'hotelone_calltoaction_bgimage', $hotelone_options_default['hotelone_calltoaction_bgimage']);

		$class = '';
		if( !empty( $bgimage ) ){
			$class = 'section-overlay';
		}

		if( ! $disable_callout ){
		?>
		<div id="callout" class="callout_section section <?php echo esc_attr( $class ); ?>" style="background-color: <?php echo esc_attr( $bgcolor ); ?>; background-image: url(<?php echo esc_url( $bgimage ); ?>);">
			
			<?php do_action('hotelone_section_before_inner', 'callout'); ?>
			
			<?php if( !empty( $bgimage ) ){ ?>
			<div class="sectionOverlay">
			<?php } ?>
			
				<div class="container">
					<div class="row">
						<div class="col-md-12 text-center">
							
							<?php if( !empty( $callout_title ) ) { ?>
							<h2 class="section-title mb-4 wow animated fadeInDown"><?php echo wp_kses_post( $callout_title ); ?></h2>
							<?php } ?>
							
							<?php if( !empty( $callout_subtitle ) ){ ?>
							<p class="callout-subtitle wow animated fadeInDown"><?php echo wp_kses_post( $callout_subtitle ); ?></p>
							<?php } ?>
							
							<?php if(!empty($callout_button_url)){ ?>
							<a class="theme-btn wow animated fadeInUp" href="<?php echo esc_url( $callout_button_url ); ?>"><?php echo esc_html( $callout_button_text ); ?></a>
							<?php } ?>
						</div>
					</div>		
				</div><!-- .container -->
				
			<?php if( !empty( $bgimage ) ){ ?>
			</div>
			<?php } ?>
			
			<?php do_action('hotelone_section_after_inner', 'callout'); ?>
			
		</div><!-- .callout_section --> 
		<div class="clearfix"></div>
		<?php }
	}
endif;

if ( function_exists( 'bc_hp_callout' ) ) {
	$section_priority = apply_filters( 'hotelone_section_priority', 40, 'bc_hp_callout' );
	add_action( 'hotelone_sections', 'bc_hp_callout', absint( $section_priority ) );
}