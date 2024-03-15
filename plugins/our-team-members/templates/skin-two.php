<?php 
	$designation     = wpb_otm_get_post_meta( '_wpb_team_members_options', 'designation', get_the_id() );
	$location        = wpb_otm_get_post_meta( '_wpb_team_members_options', 'location', get_the_id() );
	$social_icons    = wpb_otm_get_post_meta( '_wpb_team_members_options', 'social_icons',  get_the_id() );
	$content         = wp_trim_words( get_the_content(), $excerpt_length );
?>


<div class="wpb-otm-skin-two wpb_otm_single-member">
	<div class="wpb-otm-skin-two-member">
		<div class="person_image">		
			<div class="person_image_wrapper">								
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( 'full' ) ?>
				</a>											
			</div>
			<?php if( is_array( $social_icons ) ) : ?>
				<div class="social-buttons">
					<ul class="social-links">
		            	<?php 
		               		foreach ( $social_icons as $icon ) {
								printf( '<li><a href="%s"><i class="%s"></i></a></li>', esc_url( $icon['url'] ), esc_attr( $icon['icon'] ) );
							}
		               	?>
					</ul>
				</div>	
			<?php endif; ?>
		</div>	

		<div class="person-info">
			<div class="wpb-otm-heading">
				<h3><?php the_title(); ?></h3>

				<?php if($designation): ?>
					<span class="designation"><?php echo esc_html( $designation ); ?></span>
				<?php endif; ?>
			</div>

			<?php if($location): ?>
				<div class="wpb-otm-location">
					<span><i class="fa fa-map-marker"></i></span>
					<span class="wpb-otm-address"><?php echo esc_html( $location ); ?></span>
				</div>
			<?php endif; ?>

			<?php if($content): ?>
				<div class="wpb-otm-description">
					<p><?php echo wp_kses_post( $content ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</div>				
</div>




