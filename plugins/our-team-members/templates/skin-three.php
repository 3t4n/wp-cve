<?php 
	$designation  = wpb_otm_get_post_meta( '_wpb_team_members_options', 'designation', get_the_id() );
	$location     = wpb_otm_get_post_meta( '_wpb_team_members_options', 'location', get_the_id() );
	$social_icons = wpb_otm_get_post_meta( '_wpb_team_members_options', 'social_icons',  get_the_id() );
	$skills       = wpb_otm_get_post_meta( '_wpb_team_members_options', 'skills',  get_the_id() );
?>

<div class="wpb-otm-skin-three wpb_otm_single-member">
	<div class="wpb_otm_row">		
		<div class="person_image wpb_otm_col-md-5">		
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

		<div class="person-info wpb_otm_col-md-7">
			<div class="wpb-otm-heading">
				<span class="wpb-otm-name"><?php the_title(); ?></span>
				<?php $designation ? printf('<span class="designation"> - %s</span>', esc_html( $designation ) ) : ''; ?>
			</div>

			<?php if( is_array( $skills ) ) : ?>
				<div class="wpb-otm-skills">
					<div class="bar_group" data-max="100">
			    	<?php 
			    		foreach ( $skills as $skill ) :
						$skill_name  = $skill['skill'];
						$skill_value = $skill['skill_value'];
			       	?>
	       			
		       			 <div class="bar_group__bar thin elastic" data-value="<?php echo esc_attr( $skill_value ); ?>" data-label="<?php echo esc_attr( $skill_name ); ?>"></div>
		       		
					<?php endforeach; ?>
				</div> 
				
				</div>
			<?php endif; ?>	
		</div> 	
	</div> 				
</div>








