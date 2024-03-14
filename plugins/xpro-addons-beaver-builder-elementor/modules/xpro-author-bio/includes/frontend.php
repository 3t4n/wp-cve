<?php
$link_tag    = 'div';
$link_url    = '';
$link_target = '';

$post_id   = $module->get_post_id( $i );
$author_id = get_post_field( 'post_author', $post_id );
$user_id   = $author_id;

?>

<!-- Current -->
<div class="xpro-author-box xpro-author-box-alignment-<?php echo esc_attr( $settings->align ); ?>">

	<?php if ( '1' === $settings->show_avatar && 'current' === $settings->source ) : ?>
		<!-- Image -->
		<a href="">
			<div class="xpro-author-box-avatar">
				<?php echo get_avatar( get_the_author_meta( 'ID', 300 ) ); ?>
			</div>
		</a>
	<?php endif; ?>

	<div class="xpro-author-box-text">
		<?php if ( '1' === $settings->show_name && 'current' === $settings->source ) : ?>
			<!-- Title -->
			<<?php echo esc_attr( $settings->title_tag ); ?> class="xpro-author-box-name"><?php echo esc_attr( get_the_author_meta( 'display_name', $user_id ) ); ?></<?php echo esc_attr( $settings->title_tag ); ?>>
		<?php endif; ?>

		<?php if ( '1' === $settings->show_biography && 'current' === $settings->source ) : ?>
			<!-- Description -->
			<div class="xpro-author-box-bio">
				<?php echo esc_attr( get_the_author_meta( 'description', $user_id ) ); ?>
			</div>
		<?php endif; ?>
		<?php
		if ( 'current' === $settings->source && '1' === $settings->show_link && $settings->link_text ) :
			if ( $settings->posts_url ) :
				$post_url = $settings->posts_url;
				if ( ! empty( $settings->posts_url_target ) ) :
					$link_target = $settings->posts_url_target;
				else :
					$link_target = '';
				endif;
				if ( ! empty( $settings->posts_url_nofollow ) ) :
					$link_follow = 'rel=nofollow';
				else :
					$link_follow = '';
				endif;
			else :
				$post_url = get_author_posts_url( $user_id );
			endif;
			?>
			<!-- Button -->
			<a class="xpro-author-box-button" href="<?php echo esc_url( $post_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>" <?php echo esc_attr( $link_follow ); ?>>
				<?php echo esc_attr( $settings->link_text ); ?>
			</a>
		<?php endif; ?>
	</div>

</div>

<!-- Custom -->
<div class="xpro-author-box xpro-author-box-alignment-<?php echo esc_attr( $settings->align ); ?>">

	<?php if ( 'custom' === $settings->source ) : ?>
		<!-- Image -->
		<div class="xpro-author-box-avatar">
			<?php $module->render_image(); ?>
		</div>
	<?php endif; ?>

	<div class="xpro-author-box-text">
		<?php if ( 'custom' === $settings->source && $settings->author_name ) : ?>
			<!-- Title -->
			<<?php echo esc_attr( $settings->title_tag ); ?> class="xpro-author-box-name"><?php echo esc_attr( $settings->author_name ); ?></<?php echo esc_attr( $settings->title_tag ); ?>>
		<?php endif; ?>

		<?php if ( 'custom' === $settings->source && $settings->author_bio ) : ?>
			<!-- Description -->
			<div class="xpro-author-box-bio">
				<?php echo esc_attr( $settings->author_bio ); ?>
			</div>
		<?php endif; ?>

		<?php
		if ( 'custom' === $settings->source && $settings->link_text && '1' === $settings->show_link ) :
			if ( $settings->posts_url ) :
				$post_url = $settings->posts_url;
				if ( ! empty( $settings->posts_url_target ) ) :
					$link_target = $settings->posts_url_target;
				else :
					$link_target = '';
				endif;
				if ( ! empty( $settings->posts_url_nofollow ) ) :
					$link_follow = 'rel=nofollow';
				else :
					$link_follow = '';
				endif;
			else :
				$post_url = get_author_posts_url( $user_id );
			endif;
			?>
			<!-- Button -->
			<a class="xpro-author-box-button" href="<?php echo esc_url( $post_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>" <?php echo esc_attr( $link_follow ); ?>>
				<?php echo esc_attr( $settings->link_text ); ?>
			</a>
		<?php endif; ?>

	</div>

</div>
