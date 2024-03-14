<?php
add_action( 'widgets_init', 'bupr_member_rating_widget' );

function bupr_member_rating_widget() {
	register_widget( 'bupr_single_member_rating_widget' );
}

class bupr_single_member_rating_widget extends WP_Widget {

	/** constructor -- name this the same as the class above */
	function __construct() {
		$widget_ops  = array(
			'classname'   => 'bupr_single_member_rating_widget buddypress',
			'description' => esc_html__( 'Display displayed member ratings.', 'bp-member-reviews' ),
		);
		$control_ops = array(
			'width'   => 280,
			'height'  => 350,
			'id_base' => 'bupr_single_member_rating_widget',
		);
		parent::__construct( 'bupr_single_member_rating_widget', esc_html__( 'BP Displayed Member Rating Widget', 'bp-member-reviews' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		if ( ! bp_is_my_profile() ) {
			return;
		}

		global $wpdb;
		global $bupr;
		$bupr_type       = 'integer';
		$bupr_avg_rating = 0;
		$user_id         = bp_displayed_user_id();
		// Our variables from the widget settings.
		$bupr_title     = '';
		$rating_limit   = 5;
		$rating_default = 'latest';

		if ( ! empty( $instance['bupr_title'] ) ) {
			$bupr_title = apply_filters( 'widget_title', $instance['bupr_title'] );
		} else {
			$link       = trailingslashit( bp_displayed_user_domain() . bp_get_friends_slug() );
			/* translators: %s: */
			$bupr_title = sprintf( __( "%s's Ratings", 'bp-member-reviews' ), bp_get_displayed_user_fullname() );
		}

		if ( isset( $instance['rating_default'] ) ) {
			$rating_default = $instance['rating_default'];
		}

		if ( isset( $instance['rating_limit'] ) ) {
			$rating_limit = $instance['rating_limit'];
		} else {
			$rating_limit = 5;
		}

		$bupr_users              = get_users();
		$bupr_max_review         = array();
		$bupr_star_rating        = array();
		$bupr_member_count       = 0;
		$bupr_total_review_count = '';

		/*		 * * Displayed user Reviews start ** */

		$bupr_type       = 'integer';
		$bupr_avg_rating = 0;
		/* Gather all the members reviews */
		$bupr_args = array(
			'post_type'   => 'review',
			'post_status' => 'publish',
			'category'    => 'bp-member',
			'meta_query'  => array(
				array(
					'key'     => 'linked_bp_member',
					'value'   => $user_id,
					'compare' => '=',
				),
			),
		);

		/*		 * * Displayed user Reviews end ** */

		$reviews            = get_posts( $bupr_args );
		$bupr_total_rating  = 0;
		$bupr_reviews_count = count( $reviews );
		$final_review_arr   = array();
		$final_review_obj   = array();
		if ( $bupr_reviews_count != 0 ) {
			foreach ( $reviews as $review ) {
				$rate                = 0;
				$reviews_field_count = 0;

				$review_ratings = get_post_meta( $review->ID, 'profile_star_rating', false );

				if ( ! empty( $review_ratings[0] ) ) {

					if ( ! empty( $bupr['active_rating_fields'] ) && ! empty( $review_ratings[0] ) ) {
						foreach ( $review_ratings[0] as $field => $value ) {
							if ( array_key_exists( $field, $bupr['active_rating_fields'] ) ) {
								$rate += $value;
								$reviews_field_count++;
							}
						}
						if ( $reviews_field_count != 0 ) {
							$final_review_arr[ $review->ID ] = (int) $rate / $reviews_field_count;
							$final_review_obj[ $review->ID ] = $review;
						}
					}
				}
			}
		}
		?>
		<?php
		echo $before_widget; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$bupr_user_count = 0;
		echo $before_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo esc_html( $bupr_title );
		echo $after_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		if ( ! empty( $final_review_arr ) ) {
			if ( 'highest' === $rating_default ) {
				arsort( $final_review_arr );
			} elseif ( 'lowest' === $rating_default ) {
				asort( $final_review_arr );
			} else {
				$final_review_arr = $final_review_arr;
			}

			?>
			<div class="item-options" id="bp-member-rating-list-options">
				<a href="#" attr-val="latest" id="member_latest_reviews"
				<?php
				if ( $rating_default == 'latest' ) :
					?>
					class="selected"<?php endif; ?>><?php esc_html_e( 'Latest', 'bp-member-reviews' ); ?></a>
				| <a href="#" attr-val="highest" id="member_good_reviews"
				<?php
				if ( $rating_default == 'highest' ) :
					?>
					class="selected"<?php endif; ?>><?php esc_html_e( 'Highest', 'bp-member-reviews' ); ?></a>
				| <a href="#" attr-val="lowest" id="member_bad_reviews"
				<?php
				if ( $rating_default == 'lowest' ) :
					?>
					class="selected"<?php endif; ?>><?php esc_html_e( 'Lowest', 'bp-member-reviews' ); ?></a>
			</div>
			<?php
			echo '<ul class="item-list" id="bp-member-rating">';
			foreach ( $final_review_arr as $buprKey => $buprValue ) {
				$user_anonymous_review = get_post_meta( $buprKey, 'bupr_anonymous_review_post', true );
				if ( $bupr_user_count == $rating_limit ) {
					break;
				} else {					
					echo '<li class="vcard"><div class="item-avatar">';
					echo get_avatar( $final_review_obj[ $buprKey ]->post_author, 50 );
					echo '</div>';
					echo '<div class="item">';
					$members_profile = bp_core_get_userlink( $final_review_obj[ $buprKey ]->post_author );
					echo '<div class="item-title">';
					echo ( $user_anonymous_review != 'yes' ) ? wp_kses_post( $members_profile ) : 'anonymous';
					echo '</div>';

					$bupr_avg_rating = $buprValue;
					$stars_on        = $stars_off        = $stars_half       = '';
					$remaining       = $bupr_avg_rating - (int) $bupr_avg_rating;
					if ( $remaining > 0 ) {
						$stars_on        = intval( $bupr_avg_rating );
						$stars_half      = 1;
						$bupr_half_squar = 1;
						$stars_off       = 5 - ( $stars_on + $stars_half );
					} else {
						$stars_on   = $bupr_avg_rating;
						$stars_off  = 5 - $bupr_avg_rating;
						$stars_half = 0;
					}
					echo '<div class="item-meta">';

					for ( $i = 1; $i <= $stars_on; $i++ ) { 
                                            ?><span class="fas fa-star bupr-star-rate"></span><?php
					}
					for ( $i = 1; $i <= $stars_half; $i++ ) { 
                                            ?><span class="fas fa-star-half-alt bupr-star-rate"></span><?php
					}
					for ( $i = 1; $i <= $stars_off; $i++ ) { 
                                            ?><span class="far fa-star bupr-star-rate"></span><?php
					}
                                        
					echo '</div>';

					$bupr_avg_rating = round( $bupr_avg_rating, 2 );
					echo '<span class="bupr-meta">';
					/* translators: %s: */
					echo sprintf( esc_html__( 'Rating : ( %1$s )', 'bp-member-reviews' ), esc_html( $bupr_avg_rating ) );
					echo '</span>';
					echo '</div></li>';
				}

				$bupr_user_count++;
			}
		} else {
			echo '<p>';
			esc_html_e( 'No member has been rated yet.', 'bp-member-reviews' );
			echo '</p>';
		}
		?>
		</ul>
		<input type="hidden" value="<?php echo esc_attr( $bupr['rating_color'] ); ?>" class="bupr-display-rating-color">
		<input type="hidden" value="<?php echo esc_attr( $rating_limit ); ?>" class="member-rating-limit">
		<?php
		echo $after_widget; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/* wbcom sort member list acording to max review */

	function bupr_sort_max_review( $bupr_rating1, $bupr_rating2 ) {
		return strcmp( $bupr_rating2['max_review'], $bupr_rating1['max_review'] );
	}

	/* wbcom sort member list according to max star */

	function bupr_sort_max_stars( $bupr_rating1, $bupr_rating2 ) {
		return strcmp( $bupr_rating2['avg_rating'], $bupr_rating1['avg_rating'] );
	}

	/** @see WP_Widget::update -- do not rename this */
	function update( $new_instance, $old_instance ) {
		$instance                   = $old_instance;
		$instance['bupr_title']     = strip_tags( $new_instance['bupr_title'] );
		$instance['bupr_member']    = $new_instance['bupr_member'];
		$instance['rating_default'] = $new_instance['rating_default'];
		return $instance;
	}

	/** @see WP_Widget::form -- do not rename this */
	function form( $instance ) {
		$defaults       = array(
			'bupr_title'     => esc_html__( '', 'bp-member-reviews' ),
			'bupr_member'    => 5,
			'rating_default' => 'latest',
		);
		$instance       = wp_parse_args( (array) $instance, $defaults );
		$title          = esc_attr( $instance['bupr_title'] );
		$member         = esc_attr( $instance['bupr_member'] );
		$rating_default = esc_attr( $instance['rating_default'] );
		?>
		<div class="bupr-widget-class">
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'bupr_title' ) ); ?>"><?php esc_html_e( 'Enter Title', 'bp-member-reviews' ); ?>:</label>
				<input class="regular_text" id="<?php echo esc_attr( $this->get_field_id( 'bupr_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'bupr_title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'bupr_member' ) ); ?>"><?php esc_html_e( 'Display Members', 'bp-member-reviews' ); ?>:</label>
				<input class="regular_text" id="<?php echo esc_attr( $this->get_field_id( 'bupr_member' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'bupr_member' ) ); ?>" type="number" value="<?php echo esc_attr( $member ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'rating_default' ) ); ?>"><?php esc_html_e( 'Default ratings to show:', 'bp-member-reviews' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'rating_default' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'rating_default' ) ); ?>">
					<option value="latest" <?php selected( $rating_default, 'latest' ); ?>><?php esc_html_e( 'Latest', 'bp-member-reviews' ); ?></option>
					<option value="highest" <?php selected( $rating_default, 'highest' ); ?>><?php esc_html_e( 'Highest', 'bp-member-reviews' ); ?></option>
					<option value="lowest"  <?php selected( $rating_default, 'lowest' ); ?>><?php esc_html_e( 'Lowest', 'bp-member-reviews' ); ?></option>
				</select>
			</p>
		</div>
		<?php
	}

}
