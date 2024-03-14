<?php
add_action('widgets_init', 'bupr_members_review_widget');

function bupr_members_review_widget()
{
	register_widget('bupr_members_review_setting');
}

class bupr_members_review_setting extends WP_Widget
{

	/** constructor -- name this the same as the class above */
	function __construct()
	{
		$widget_ops  = array(
			'classname'   => 'bupr_members_review_setting',
			'description' => esc_html__('Display members list according to members reviews.', 'bp-member-reviews'),
		);
		$control_ops = array(
			'width'   => 280,
			'height'  => 350,
			'id_base' => 'bupr_members_review_setting',
		);
		parent::__construct('bupr_members_review_setting', esc_html__('BP Member Review Widget', 'bp-member-reviews'), $widget_ops, $control_ops);
	}

	function widget($args, $instance)
	{
		extract($args);

		global $wpdb;
		global $bupr;
		$bupr_type       = 'integer';
		$bupr_avg_rating = 0;
		$user_id         = get_current_user_id();
		// Our variables from the widget settings.
		$bupr_title  = '';
		$memberLimit = 5;
		$topMember   = 'top-rated';
		$avatar      = 'Show';
		if (isset($instance['bupr_title'])) {
			$bupr_title = apply_filters('widget_title', $instance['bupr_title']);
		}
		if (isset($instance['bupr_member'])) {
			$memberLimit = $instance['bupr_member'];
		}
		if (isset($instance['top_member'])) {
			$topMember = $instance['top_member'];
		}
		if (isset($instance['avatar'])) {
			$avatar = $instance['avatar'];
		}

		$bupr_users              = get_users();
		$bupr_max_review         = array();
		$bupr_star_rating        = array();
		$bupr_member_count       = 0;
		$bupr_total_review_count = '';
		foreach ($bupr_users as $user) {
			$id              = $user->data->ID;
			$bupr_type       = 'integer';
			$bupr_avg_rating = 0;
			/* Gather all the members reviews */
			$bupr_args = array(
				'post_type'      => 'review',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'category'       => 'bp-member',
				'meta_query'     => array(
					array(
						'key'     => 'linked_bp_member',
						'value'   => $id,
						'compare' => '=',
					),
				),
			);

			$reviews                 = get_posts($bupr_args);
			$bupr_total_rating       = $rate_counter             = 0;
			$bupr_reviews_count      = count($reviews);
			$bupr_total_review_count = '';
			if ($bupr_reviews_count != 0) {
				foreach ($reviews as $review) {
					$rate                = 0;
					$reviews_field_count = 0;
					$review_ratings      = get_post_meta($review->ID, 'profile_star_rating', false);
					if (!empty($review_ratings[0])) {

						if (!empty($bupr['active_rating_fields']) && !empty($review_ratings[0])) :
							foreach ($review_ratings[0] as $field => $value) {
								if (array_key_exists($field, $bupr['active_rating_fields'])) {
									$rate += $value;
									$reviews_field_count++;
								}
							}
							if ($reviews_field_count != 0) {
								$bupr_total_rating += (int) $rate / $reviews_field_count;
								$bupr_total_review_count++;
								$rate_counter++;
							}
						endif;
					}
				}

				if ($bupr_total_review_count != 0) {
					$bupr_avg_rating = $bupr_total_rating / $bupr_total_review_count;
					$bupr_type       = gettype($bupr_avg_rating);
				}

				$bupr_stars_on = $stars_off        = $stars_half       = '';
				if ($bupr_total_review_count != 0) {
					$bupr_avg_rating = $bupr_total_rating / $bupr_total_review_count;
					$bupr_type       = gettype($bupr_avg_rating);
				}

				$bupr_max_review[$user->data->ID]  = array(
					'user_id'      => $user->data->ID,
					'max_review'   => $bupr_reviews_count,
					'avg_rating'   => $bupr_avg_rating,
					'member_name'  => $user->data->user_nicename,
					'avr_type'     => $bupr_type,
					'rate_counter' => $rate_counter,
				);
				$bupr_star_rating[$user->data->ID] = array(
					'user_id'      => $user->data->ID,
					'max_review'   => $bupr_reviews_count,
					'avg_rating'   => $bupr_avg_rating,
					'member_name'  => $user->data->user_nicename,
					'avr_type'     => $bupr_type,
					'rate_counter' => $rate_counter,
				);
				$bupr_member_count++;
			}
		}

		$bupr_members_ratings_data = array();
		if ($topMember === 'top rated') {
			usort($bupr_star_rating, array($this, 'bupr_sort_max_stars'));
			$bupr_members_ratings_data = $bupr_star_rating;
		} elseif ($topMember === 'top view') {
			usort($bupr_max_review, array($this, 'bupr_sort_max_review'));
			$bupr_members_ratings_data = $bupr_max_review;
		}
?>
		<input type="hidden" value="<?php echo esc_attr($bupr['rating_color']); ?>" class="bupr-display-rating-color">
		<?php
		echo $before_widget; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$bupr_user_count = 0;
		echo $before_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo esc_html($bupr_title);
		echo $after_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<ul class="bupr-member-main">';
		if ($bupr_member_count != 0) {
			foreach ($bupr_members_ratings_data as $buprKey => $buprValue) {
				if ($bupr_user_count == $memberLimit) {
					break;
				} else {
					if ($avatar == 'Show') {
						echo '<li class="bupr-members"><div class="bupr-img-widget">';
						echo get_avatar($buprValue['user_id'], 50);
						echo '</div>';
						echo '<div class="bupr-content-widget">';
					} else {
						echo '<li class="bupr-members bupr-hide"><div class="bupr-content-widget">';
					}
					$members_profile = bp_core_get_userlink($buprValue['user_id']);
					echo '<div class="bupr-member-title">';
					echo wp_kses_post($members_profile);
					echo '</div>';

					$bupr_avg_rating    = $buprValue['avg_rating'];
					$bupr_reviews_count = $buprValue['max_review'];
					$stars_on           = $stars_off            = $stars_half           = '';
					$remaining          = $bupr_avg_rating - (int) $bupr_avg_rating;
					if ($remaining > 0) {
						$stars_on        = intval($bupr_avg_rating);
						$stars_half      = 1;
						$bupr_half_squar = 1;
						$stars_off       = 5 - ($stars_on + $stars_half);
					} else {
						$stars_on   = $bupr_avg_rating;
						$stars_off  = 5 - $bupr_avg_rating;
						$stars_half = 0;
					}
					echo '<div class="bupr-member-rating">';
					if ($bupr_avg_rating > 0) {
		?>
						<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
							<span itemprop="ratingValue" content="<?php echo esc_attr($bupr_avg_rating); ?>"></span>
							<span itemprop="bestRating" content="5"></span>
							<span itemprop="ratingCount" content="<?php echo esc_attr($buprValue['rate_counter']); ?>"></span>
							<span itemprop="reviewCount" content="<?php echo esc_attr($bupr_reviews_count); ?>"></span>
							<span itemprop="itemReviewed" content="Person"></span>
							<span itemprop="name" content="<?php
															// Check if the BuddyPress version function exists and compare the version
															if (function_exists('bp_get_version') && version_compare(bp_get_version(), '12.0.0', '>=')) {
																// Use bp_members_get_user_slug() for BuddyPress v12.0.0 and above
																echo esc_attr(bp_members_get_user_slug($buprValue['user_id']));
															} else {
																// Use bp_core_get_username() for older versions
																echo esc_attr(bp_core_get_username($buprValue['user_id']));
															}
															?>"></span>

							<span itemprop="url" content="<?php echo esc_attr(bp_core_get_userlink($buprValue['user_id'], false, true)); ?>"></span>
						</div>
					<?php
					}

					for ($i = 1; $i <= $stars_on; $i++) {
					?>
						<span class="fas fa-star bupr-star-rate"></span>
					<?php
					}

					for ($i = 1; $i <= $stars_half; $i++) {
					?>
						<span class="fas fa-star-half-alt bupr-star-rate"></span>
					<?php
					}

					for ($i = 1; $i <= $stars_off; $i++) {
					?>
						<span class="far fa-star bupr-star-rate"></span>
		<?php
					}
					echo '</div>';

					$bupr_avg_rating = round($bupr_avg_rating, 2);
					echo '<span class="bupr-meta">';
					/* translators: %s: */
					echo sprintf(esc_html__('Rating : ( %1$s )', 'bp-member-reviews'), esc_html($bupr_avg_rating));
					echo '</span><span class="bupr-meta">';
					/* translators: %s: */
					echo sprintf(esc_html__('Total %1$s : %2$s', 'bp-member-reviews'), esc_html($bupr['review_label']), esc_attr($bupr_reviews_count));
					echo '</span></div></li>';
				}

				$bupr_user_count++;
			}
		} else {
			echo '<p>';
			esc_html_e('No member has been reviewed yet.', 'bp-member-reviews');
			echo '</p>';
		}
		echo '</ul>';
		echo $after_widget; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/* wbcom sort member list acording to max review */

	function bupr_sort_max_review($bupr_rating1, $bupr_rating2)
	{
		return strcmp($bupr_rating2['max_review'], $bupr_rating1['max_review']);
	}

	/* wbcom sort member list according to max star */

	function bupr_sort_max_stars($bupr_rating1, $bupr_rating2)
	{
		return strcmp($bupr_rating2['avg_rating'], $bupr_rating1['avg_rating']);
	}

	/** @see WP_Widget::update -- do not rename this */
	function update($new_instance, $old_instance)
	{
		$instance                = $old_instance;
		$instance['bupr_title']  = strip_tags($new_instance['bupr_title']);
		$instance['bupr_member'] = $new_instance['bupr_member'];
		$instance['top_member']  = $new_instance['top_member'];
		$instance['avatar']      = $new_instance['avatar'];
		return $instance;
	}

	/** @see WP_Widget::form -- do not rename this */
	function form($instance)
	{
		$defaults   = array(
			'bupr_title'  => esc_html__('Top Members', 'bp-member-reviews'),
			'bupr_member' => 5,
			'top_member'  => 'top rated',
			'avatar'      => 'Show',
		);
		$instance   = wp_parse_args((array) $instance, $defaults);
		$title      = esc_attr($instance['bupr_title']);
		$member     = esc_attr($instance['bupr_member']);
		$topmembers = esc_attr($instance['top_member']);
		$avatar     = esc_attr($instance['avatar']);
		?>
		<div class="bupr-widget-class">
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('bupr_title')); ?>"><?php esc_html_e('Enter Title', 'bp-member-reviews'); ?>:</label>
				<input class="regular_text" id="<?php echo esc_attr($this->get_field_id('bupr_title')); ?>" name="<?php echo esc_attr($this->get_field_name('bupr_title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('bupr_member')); ?>"><?php esc_html_e('Display Members', 'bp-member-reviews'); ?>:</label>
				<input class="regular_text" id="<?php echo esc_attr($this->get_field_id('bupr_member')); ?>" name="<?php echo esc_attr($this->get_field_name('bupr_member')); ?>" type="number" value="<?php echo esc_attr($member); ?>" />
			</p>

			<p>
				<span>
					<input class="regular_text" id="<?php echo esc_attr($this->get_field_id('top_rated')); ?>" name="<?php echo esc_attr($this->get_field_name('top_member')); ?>" value="top rated" type="radio" <?php checked($topmembers, 'top rated'); ?> />
					<label for="<?php echo esc_attr($this->get_field_id('Top rated')); ?>"><?php esc_html_e('Top Rated ', 'bp-member-reviews'); ?>
					</label>
				</span>
				<span>
					<input class="regular_text" id="<?php echo esc_attr($this->get_field_id('top_viewed')); ?>" name="<?php echo esc_attr($this->get_field_name('top_member')); ?>" value="top view" type="radio" <?php checked($topmembers, 'top view'); ?> />
					<label for="<?php echo esc_attr($this->get_field_id('Top Viewed')); ?>"><?php esc_html_e('Most Reviewed', 'bp-member-reviews'); ?>
					</label>
				</span>
			</p>



			<p>
				<label for="<?php echo esc_attr($this->get_field_id('avatar')); ?>"><?php esc_html_e('Display Avatar ', 'bp-member-reviews'); ?>
				</label>
				<?php
				if (!empty($avatar) && $avatar == 'Show') {
					$bupr_options = array('Show', 'Hide');
				} elseif (!empty($avatar) && $avatar == 'Hide') {
					$bupr_options = array('Hide', 'Show');
				} else {
					$bupr_options = array('Show', 'Hide');
				}
				?>
				<select id="<?php echo esc_attr($this->get_field_id('avatar')); ?>" name="<?php echo esc_attr($this->get_field_name('avatar')); ?>">
					<?php
					foreach ($bupr_options as $bupr_option) {
					?>
						<option value="<?php echo esc_attr($bupr_option); ?>"><?php echo esc_html($bupr_option); ?></option>
					<?php
					}
					?>
				</select>
			</p>
		</div>
<?php
	}
}
