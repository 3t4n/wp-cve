<?php 
	$designation     = wpb_otm_get_post_meta( '_wpb_team_members_options', 'designation', get_the_id() );
	$content         = wp_trim_words( get_the_content(), $excerpt_length );
?>

<div class="wpb-otm-skin-four">
	<div class="wpb-otm-skin-four-member-thumb">
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( 'thumbnail' ); ?>
		</a>
	</div>
	<div class="wpb-otm-skin-four-member-desc">
		<h3><?php the_title(); ?></h3>

		<?php if($designation): ?>
		<span><?php echo esc_html( $designation ); ?></span>
		<?php endif; ?>

		<?php if($content): ?>
			<p><?php echo wp_kses_post( $content ); ?></p>
		<?php endif; ?>
	</div>
</div>

