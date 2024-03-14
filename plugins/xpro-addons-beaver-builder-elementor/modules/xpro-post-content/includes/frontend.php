<?php
$limit   = ( 'excerpt' === $settings->content_type && $settings->limit ? $settings->limit : '100' );
$post_id = $module->get_post_id( $i );
?>

<?php if ( is_home() || is_archive() || is_singular( array( 'post', 'page' ) ) ) : ?>
	<div class="xpro-post-content">
		<?php
		if ( 'excerpt' === $settings->content_type ) {
			echo wp_trim_words( wp_strip_all_tags( get_the_excerpt() ), $limit );
		} else {

			$content = apply_filters( 'the_content', get_post_field( 'post_content', get_the_ID() ) );
			if ( $content ) {
				echo esc_attr( $content );
			} elseif ( ! $content ) {
				if ( FLBuilderModel::is_builder_active() ) {
					echo 'This is a dummy text to demonstration purposes. It will be replaced with the post content or excerpt.';
				}
			}
		}
		?>
	</div>

<?php else : ?>
	<!-- if singular page -->
	<div class="xpro-post-content">
		<?php
		if ( 'excerpt' === $settings->content_type ) {
			$post = get_post( $post_id );
			if ( $post ) :
				$excerpt = ( $post->post_excerpt );
				echo wp_trim_words( wp_strip_all_tags( $excerpt ), $limit );
			else :
				if ( FLBuilderModel::is_builder_active() ) :
					echo 'This is a dummy text to demonstration purposes. It will be replaced with the  excerpt.';
				endif;
			endif;
		} else {
			$content = apply_filters( 'the_content', get_post_field( 'post_content', $post_id ) );
			if ( $content ) :
				echo esc_attr( $content );
			else :
				if ( FLBuilderModel::is_builder_active() ) :
					echo 'This is a dummy text to demonstration purposes. It will be replaced with the  content.';
				endif;
			endif;
		}
		?>
	</div>

<?php endif; ?>
