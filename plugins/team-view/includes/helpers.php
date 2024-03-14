<?php
/**
 * Helpers.
 *
 * @package Team_View
 */

function team_view_render_team_items( $args = array() ) {

	$defaults = array(
		'limit'         => 4,
		'column'        => 4,
		'echo'          => true,
		'show_position' => true,
		'show_social'   => true,
	);

	$defaults = apply_filters( 'team_view_default_args', $defaults );

	$args = wp_parse_args( $args, $defaults );

	$output = null;

	ob_start();

	$qargs = array(
		'post_type'      => 'tv_member',
		'no_found_rows'  => true,
		'posts_per_page' => $args['limit'],
		'orderby'        => 'menu_order date',
		'order'          => 'DESC',
	);

	$the_query = new WP_Query( $qargs );

	if ( $the_query->have_posts() ) {

		echo '<div class="team-view-members">';
		echo '<div class="team-view-members-inner">';
		echo '<div class="team-view-members-items column-' . absint( $args['column'] ) . '">';

		while ( $the_query->have_posts() ) {

			$the_query->the_post();

			$extra_class = '';
			if ( ! has_post_thumbnail() ) {
				$extra_class .= 'no-image';
			}
			?>
			<div <?php post_class( $extra_class ); ?>>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="team-member-picture">
						<?php $image_size = apply_filters( 'team_view_filter_team_member_image_size', 'medium' ); ?>
						<?php the_post_thumbnail( $image_size ); ?>
					</div><!-- .team-member-picture -->
				<?php else : ?>
					<div class="team-member-picture">
						<?php $default_image_url = apply_filters( 'team_view_filter_team_member_default_image', TEAM_VIEW_URL . '/public/images/no-image.jpg' ); ?>
						<img src="<?php echo esc_url( $default_image_url ); ?>" alt="" />
					</div><!-- .team-member-picture -->
				<?php endif; ?>
				<div class="team-member-content">
					<?php the_title( '<div class="member-name">', '</div>' ); ?>

					<?php $position = get_post_meta( get_the_ID() , '_team_view_position', true ); ?>
					<?php if ( ! empty( $position ) && true === $args['show_position'] ) : ?>
						<div class="member-position"><?php echo esc_html( $position ); ?></div>
					<?php endif; ?>

					<?php if ( true === $args['show_social'] ) : ?>
						<?php team_view_render_social_links( get_the_ID() ); ?>
					<?php endif; ?>
				</div><!-- .team-member-content -->
			</div>
			<?php

		} // End while have_posts.

		wp_reset_postdata();

		echo '</div><!-- .team-view-members-items -->';
		echo '</div><!-- .team-view-members-inner -->';
		echo '</div><!-- .team-view-members -->';

	} // End if have_posts.

	$output = ob_get_contents();
	ob_end_clean();

	if ( $args['echo'] ) {
		echo $output;
	} else {
		return $output;
	}

}

add_shortcode( 'team_view', 'team_view_shortcode_callback' );

function team_view_shortcode_callback( $atts ) {

	$defaults = array(
		'limit'         => 4,
		'column'        => 4,
		'echo'          => true,
		'show_position' => true,
		'show_social'   => true,
	);

	$args = shortcode_atts( $defaults, $atts );

	// Make sure we return and don't echo.
	$args['echo'] = false;

	// Fix integers.
	if ( isset( $args['limit'] ) ) {
		$args['limit'] = intval( $args['limit'] );
	}

	if ( isset( $args['column'] ) ) {
		$args['column'] = absint( $args['column'] );
		if ( 0 === $args['column'] || $args['column'] > 4 ) {
			$args['column'] = 4;
		}
	}

	// Fix booleans.
	foreach ( array( 'show_social', 'show_position' ) as $k => $v ) {
		if ( isset( $args[ $v ] ) && ! is_bool( $args[ $v ] ) ) {
			if ( 'true' === $args[ $v ] ) {
				$args[ $v ] = true;
			} else {
				$args[ $v ] = false;
			}
		}
	}

	return team_view_render_team_items( $args );

}

function team_view_render_social_links( $post_id ) {

	$social = get_post_meta( $post_id , '_team_view_social', true );

	if ( empty( $social ) ) {
		return;
	}
	?>
	<div class="team-member-social-links">
		<ul class="team-member-social-links-list">
			<?php foreach ( $social as $s ) : ?>
				<li><a href="<?php echo esc_url( $s ); ?>" target="_blank"></a></li>
			<?php endforeach; ?>
		</ul><!-- .team-member-social-links-list -->
	</div><!-- .team-member-social-links -->
	<?php

}

