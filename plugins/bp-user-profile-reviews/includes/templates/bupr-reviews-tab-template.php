<?php
/**
 * Reviews tab template.
 *
 * @package BuddyPress_Member_Reviews
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

global $bp,$post;
global $allowedtags,$allowedposttags;
global $bupr;

$bupr_review_succes = false;
$current_user       = wp_get_current_user();
$member_id          = $current_user->ID;

// Gather all the bp member reviews.
$args = array(
	'post_type'      => 'review',
	'post_status'    => 'publish',
	'posts_per_page' => $bupr['reviews_per_page'],
	'paged'          => get_query_var( 'page', 1 ),
	'category'       => 'bp-member',
	'meta_query'     => array(
		array(
			'key'     => 'linked_bp_member',
			'value'   => bp_displayed_user_id(),
			'compare' => '=',
		),
	),
);

$reviews = new WP_Query( $args );
?>

<?php do_action( 'bupr_before_member_review_list' ); ?>

<div class="bupr-bp-member-reviews-block">
	<div class="select-wrap">
		<select id="bp-reviews-filter-by">
			<option value="latest"><?php esc_html_e( 'Latest', 'bp-member-reviews' ); ?></option>
			<option value="highest"><?php esc_html_e( 'Highest', 'bp-member-reviews' ); ?></option>
			<option value="lowest"><?php esc_html_e( 'Lowest', 'bp-member-reviews' ); ?></option>
		</select>
	</div>
	<!-- MODAL FOR USER LOGIN -->
	<input type="hidden" id="reviews_pluginurl" value="<?php echo esc_attr( BUPR_PLUGIN_URL ); ?>">
	<input type="hidden" class="member-rating-limit" value="<?php echo esc_attr( $bupr['reviews_per_page'] ); ?>">

	<div class="bp-member-reviews">
		<div id="bp-member-reviews-list" cellspacing="0">
			<div id="request-review-list" class="item-list">
				<?php
				if ( $reviews->have_posts() ) {
					while ( $reviews->have_posts() ) :
						$reviews->the_post();
						$anonymous_post_review = get_post_meta( $post->ID, 'bupr_anonymous_review_post', true );
						$member_review_ratings = get_post_meta( $post->ID, 'profile_star_rating', false );
						$author_id             = get_post_field( 'post_author', get_the_ID() );

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
						<div class="bupr-row">
							<?php if ( ! empty( $bupr['allow_update'] ) && 'yes' === $bupr['allow_update'] ) : ?>
								<?php if ( bp_loggedin_user_id() == $author_id ) : ?>
									<div id="bupr-reiew-edit" class="bupr-edit-review-button-wrapper" data-review="<?php echo esc_attr( $post->ID ); ?>">
										<button type="button" class="bupr-edit-review-button"><?php 
										/* translators: %s: */
										echo sprintf( esc_html__( 'Edit %s', 'bp-member-reviews' ), esc_html( bupr_profile_review_singular_tab_name() ) ); 
										?></button>
									</div>
								<?php endif; ?>
							<?php endif; ?>
							<div class="bupr-members-profiles">
								<div class="item-avatar">
									<?php
									$author = $reviews->post->post_author;
									if ( 'yes' === $anonymous_post_review ) {
										if ( function_exists( 'buddypress' ) && ! isset( buddypress()->buddyboss ) ) {
											$avatar_url = bp_core_avatar_default(
												$type   = 'local',
												array(
													'height' => 96,
													'width'  => 96,
													'html'   => true,
												)
											);
										} elseif ( function_exists( 'buddypress' ) && isset( buddypress()->buddyboss ) ) {
											$size = '';
											if ( isset( $params['type'] ) && 'thumb' === $params['type'] ) {
												$size = bp_core_avatar_thumb_width();
											} else {
												$size = bp_core_avatar_full_width();
											}
											$avatar_url = 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mm&amp;s=' . $size;
										}
										echo "<img src='" . esc_url( $avatar_url ) . "' class='avatar avatar-96 photo' alt='Profile Photo' width='96' height='96'></img>";
									} else {

										echo bp_core_fetch_avatar( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											array(
												'item_id' => $author,
												'height'  => 96,
												'width'   => 96,
											)
										);
									}
									?>
								</div>
								<div class="reviewer">
									<h4>
										<?php
										if ( 'yes' === $anonymous_post_review ) {
											esc_html_e( 'anonymous', 'bp-member-reviews' );
										} else {
											echo wp_kses( bp_core_get_userlink( $author ), $allowedtags );
										}
										?>
									</h4>
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
											<?php
											  $review_date        = date_i18n( 'M Y', strtotime( $reviews->post->post_date ) );
											echo sprintf(
												/* translators: 1: User registered date. */
												esc_html( $review_date )
											);
											?>
										</time>
								</span>
								<?php echo '</div>'; ?>
								<?php
								$user_id = bp_displayed_user_id();
								$url     = bp_core_get_userlink( $user_id, false, true ) . bupr_profile_review_tab_plural_slug() . '/view/' . get_the_id();
								?>
								<div class="bupr-review-description">
									<div class="bupr-full-description">
										<?php
										$trimcontent = get_the_content();
										if ( ! empty( $trimcontent ) ) {
											$len = strlen( $trimcontent );
											if ( $len > 150 ) {
												$shortexcerpt = substr( $trimcontent, 0, 150 );
												?>
												<div class="description">
													<?php echo wp_kses_post( wpautop( $shortexcerpt ) ); ?>
													<a href="<?php echo esc_url( $url ); ?> " class="bupr-read-more">
														<i><?php esc_html_e( 'read more...', 'bp-member-reviews' ); ?></i>
													</a>
												</div>
											<?php } else { ?>
												<div class="description"><?php echo wp_kses_post( wpautop( $trimcontent ) ); ?></div>
												<?php
											}
										}

										if ( ! empty( $member_review_rating_fields ) && ! empty( $member_review_ratings[0] ) ) :
											foreach ( $member_review_ratings[0] as $field => $bupr_value ) {

												if ( in_array( $field, $bupr_rating_criteria, true ) ) {
													echo '<div class="multi-review inline-content"><div class="bupr-col-4 ">' . esc_attr( $field ) . '</div>';
													/*** Star rating Ratings */
													$stars_on  = $bupr_value;
													$stars_off = 5 - $stars_on;
													echo '<div class="bupr-col-4 ">';
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

													echo '</div>';
													echo '</div>';
												}
											}
										endif;
										?>
									</div>
								</div>
							</div>
						</div>
						<?php
					endwhile;

					$total_pages = $reviews->max_num_pages;
					if ( $total_pages > 1 ) {
						?>
						<div class="bupr-pagination">
							<?php
							/*** Posts pagination ***/
							echo "<div class='bupr-posts-pagination'>";
							echo wp_kses(
								paginate_links(
									array(
										'base'    => add_query_arg( 'page', '%#%' ),
										'format'  => '',
										'current' => max( 1, get_query_var( 'page' ) ),
										'total'   => $reviews->max_num_pages,
									)
								),
								$allowedposttags
							);
							echo '</div>';
							?>
						</div>
						<?php
					}
					wp_reset_postdata();
				} else {
					$bp_template_option = bp_get_option( '_bp_theme_package_id' );
					if ( 'nouveau' === $bp_template_option ) {
						?>
					<div id="message" class="info bp-feedback bp-messages bp-template-notice">
						<span class="bp-icon" aria-hidden="true"></span>
					<?php } else { ?>
						<div id="message" class="info">
					<?php } ?>
						<p><?php 
						/* translators: %s: */
						echo sprintf( esc_html__( 'Unfortunately, there are no %1$s found.', 'bp-member-reviews' ), esc_html( strtolower( $bupr['review_label'] ) ) ); 
						?>

						</p>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</div>
<?php do_action( 'bupr_after_member_review_list' ); ?>
