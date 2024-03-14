<?php
/**
 * Single Reviews tab template.
 *
 * @package BuddyPress_Member_Reviews
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
global $allowedtags,$allowedposttags;
global $bupr;
$url = filter_input( INPUT_SERVER, 'REQUEST_URI' );
preg_match_all( '!\d+!', $url, $matches );
$review_id = (int) basename( $url );
if ( ! empty( $review_id ) ) {
	$review = get_post( $review_id );

	$review_title = $review->post_title;
	$review_url   = get_permalink( $review_id );

	$author            = $review->post_author;
	$author_details    = get_userdata( $author );
	$review_author     = $author_details->data->user_login;
	$author_id         = $author_details->data->ID;
	$member_profile    = bp_core_get_userlink( $author_id );
	$review_author_url = home_url() . '/author/' . $review_author;
	$review_date_time  = $review->post_date;
	$review_date       = date_i18n( 'M Y', $review_date_time );
	// $review_date       = human_time_diff( strtotime( $review_date_time ), current_time( 'timestamp' ) ) . ' ago';

	$anonymous_post_review = get_post_meta( $review_id, 'bupr_anonymous_review_post', true );
	$member_review_ratings = get_post_meta( $review->ID, 'profile_star_rating', false );
	/* Hide user avatar and username if it was a anonymous review. */
	if ( 'yes' === $anonymous_post_review ) {
			$avatar   = bp_core_avatar_default(
				$type = 'local',
				array(
					'height' => 96,
					'width'  => 96,
					'html'   => false,
				)
			);
			$member_profile = esc_html__( 'anonymous', 'bp-member-reviews' );
	} else {
		// Author Thumbnail.
		$avatar = bp_core_fetch_avatar(
			array(
				'item_id' => $author,
				'object'  => 'user',
				'html'    => false,
			)
		);
	}

	if ( ! empty( $bupr['active_rating_fields'] ) ) {
		$member_review_rating_fields = $bupr['active_rating_fields'];
	}
	$bupr_rating_criteria = array();
	if ( ! empty( $member_review_rating_fields ) ) {
		foreach ( $member_review_rating_fields as $bupr_keys => $bupr_fields ) {
			if ( 'yes' === $bupr_fields ) {
				$bupr_rating_criteria[] = $bupr_keys;
			}
		}
	}
	?>
		<!-- wbcom Display members review on review tab -->
		<div class="bgr-single-review">
			<article id="post-<?php echo esc_attr( $review_id ); ?>" class="post-<?php echo esc_attr( $review_id ); ?> post type-review status-publish format-standard hentry bupr-single-reivew">
				<div class="bupr-row">
					<div class="bupr-members-profiles">
						<div class="author">
							<img src="<?php echo esc_url( $avatar ); ?>" class="avatar user-<?php echo esc_attr( $author ); ?>-avatar avatar-128 photo" alt="Profile photo of <?php echo esc_attr( $review_author ); ?>" width="50" height="50">
							<div class="reviewer">
								<h4>
									<?php echo wp_kses( $member_profile, $allowedtags ); ?>
								</h4>
							</div>
						</div>
					</div>
					<div class="bupr-members-content">
						<?php

						if ( ! empty( $member_review_ratings ) ) {
							$member_review_ratings_new = array();
							echo "<div class='rating-sec'>";
							foreach ( $bupr_rating_criteria as $bupr_rating_each_criteria ) {
								if ( array_key_exists( $bupr_rating_each_criteria, $member_review_ratings[0] ) ) {
									$member_review_ratings_new[ $bupr_rating_each_criteria ] = $member_review_ratings[0][ $bupr_rating_each_criteria ];
								}
							}
							if ( ! empty( $member_review_ratings_new ) ) {
								$aggregate = array_sum( $member_review_ratings_new ) / count( $member_review_ratings_new );
								echo "<div class='rating-star'>";
								for ( $i = 0; $i < 5; $i++ ) {
									if ( floor( $aggregate ) - $i >= 1 ) {
										?>
										<span class="fas fa-star bupr-star-rate"></span>
										<?php
									} elseif ( $aggregate - $i > 0 ) {
										?>
										<span class="fa fa-star-half-o bupr-star-rate"></span>
										<?php
									} else {
										?>
										<span class="far fa-star stars bupr-star-rate"></span>
										<?php
									}
								}
								echo '</div>';
								echo '<span class="rating-num">' . number_format( $aggregate, 2 ) . '</span>';
							}
						}
						?>
						<span class="posted-on list">
								<time class="entry-date published updated">
									<?php echo esc_html( $review_date ); ?>
								</time>
						</span>
						<?php echo '</div>'; ?>
						<div class="bupr-col-12 description"><?php echo wp_kses( $review->post_content, $allowedposttags ); ?></div>
						<?php
						// $bupr_rating_criteria = array();
						// if ( ! empty( $bupr['active_rating_fields'] ) ) {
						// foreach ( $bupr['active_rating_fields'] as $bupr_keys => $bupr_fields ) {
						// $bupr_rating_criteria[] = $bupr_keys;
						// }
						// }


						if ( ! empty( $bupr['active_rating_fields'] ) && ! empty( $member_review_ratings[0] ) ) :
							foreach ( $member_review_ratings[0] as $field => $bupr_value ) {

								if ( in_array( $field, $bupr_rating_criteria, true ) ) {

									echo '<div class="multi-review inline-content"><div class="bupr-col-6 ">' . esc_attr( $field ) . '</div>';
									/*** Star rating Ratings */
									$stars_on  = $bupr_value;
									$stars_off = 5 - $stars_on;
									echo '<div class="bupr-col-6 ">';
									for ( $i = 1; $i <= $stars_on; $i++ ) {
										?>
										<span class="fas fa-star bupr-star-rate"></span>
										<?php
									}

									for ( $i = 1; $i <= $stars_off; $i++ ) {
										?>
										<span class="far fa-star stars bupr-star-rate"></span>
										<?php
									}
									/*star rating end */
									echo '</div></div>';
								}
							}
						endif;
						?>
					</div>
				</div>
		</article>
	</div>

	<?php } ?>
