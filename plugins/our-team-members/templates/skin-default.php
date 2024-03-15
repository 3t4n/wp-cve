<?php 
	$designation     = wpb_otm_get_post_meta( '_wpb_team_members_options', 'designation', get_the_id() );
	$img_link        = $gallery_large = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );;
	$img_link        = $img_link[0];
	$social_icons    = wpb_otm_get_post_meta( '_wpb_team_members_options', 'social_icons',  get_the_id() );
	$content         = wp_trim_words( get_the_content(), $excerpt_length );

?>

<div class="team-item wpb_otm_single-member">	
	<div class="image" style="background-image: url(<?php echo esc_url($img_link); ?>);">
		<?php $content ? printf( '<blockquote>%s</blockquote>', wp_kses_post( $content ) ) : ''; ?>
	</div>
	<?php the_title('<h5>', '</h5>'); ?>
	<?php $designation ? printf( '<div class="otm-designation">%s</div>', wp_kses_post( $designation ) ) : ''; ?>
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
