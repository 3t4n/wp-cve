<?php
/**
 * BuddyPress Birthdays widgets.
 *
 * @package  BP_Birthdays/assets/inc
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

/**
 * BuddyPress Birthdays widget class.
 */
class Widget_Buddypress_Birthdays extends WP_Widget {

	/**
	 * Set up optional widget args.
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_bp_birthdays widget buddypress',
			'description' => __( 'BuddyPress Birthdays widget to display the birthdays of the member in an elegant way.', 'buddypress-birthdays' ),
		);

		/* Set up the widget. */
		parent::__construct(
			false,
			__( '(BuddyPress) Birthdays', 'buddypress-birthdays' ),
			$widget_ops
		);
	}
	/**
	 * Display the widget fields.
	 *
	 * @param array $args Arguments.
	 * @param array $instance Instance.
	 */
	public function widget( $args, $instance ) {

		$birthdays = $this->bbirthdays_get_array( $instance );

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( ! empty( $birthdays ) ) {
			echo $args['before_title'] . $instance['title'] . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$max_items = (int) $instance['birthdays_to_display'];
			$c         = 0;
			$date_ymd  = gmdate( 'Ymd' );

			echo '<ul class="bp-birthday-users-list">';
			foreach ( $birthdays as $user_id => $birthday ) {
				if ( $c === $max_items ) {
					break;
				}

				$activation_key = get_user_meta( $user_id, 'activation_key' );
				if ( empty( $activation_key ) ) {
					$name_to_display = $this->get_name_to_display( $user_id );

					$age = $birthday['years_old'];

					$emoji             = isset( $instance['emoji'] ) ? $instance['emoji'] : '';
					$display_name_type = empty( $instance['display_name_type'] ) ? '' : $instance['display_name_type'];
					// We don't display negative ages.
					if ( $age > 0 ) {
						echo '<li class="bp-birthday-users">';
						if ( function_exists( 'bp_is_active' ) ) :
							echo '<a href="' . esc_url( bp_core_get_user_domain( $user_id ) ) . '">';
							echo get_avatar( $user_id );
							echo '</a>';
							else :
								echo get_avatar( $user_id );
							endif;
							echo '<span class="birthday-item-content">'; ?>
						<strong>
							<?php
							if ( 'user_name' === $display_name_type ) {
								echo esc_html( bp_core_get_username( $user_id ) );
							} elseif ( 'nick_name' === $display_name_type ) {
								echo esc_html( get_user_meta( $user_id, 'nickname', true ) );
							} elseif ( 'first_name' === $display_name_type ) {
								echo esc_html( get_user_meta( $user_id, 'first_name', true ) );
							}
							?>
						</strong>
							<?php
							if ( isset( $instance['display_age'] ) && 'yes' === $instance['display_age'] ) {
								echo '<i class="bp-user-age">(' . esc_html( $age ) . ')</i>';
							}
							switch ( $emoji ) {
								case 'none':
									echo '';
									break;
								case 'cake':
									echo '<span>&#x1F382;</span>';
									break;
								case 'party':
									echo '<span>&#x1F389;</span>';
									break;
								default:
									echo '<span>&#x1F388;</span>';
							}
							echo '<div class="bbirthday_action">';
							echo '<span class="badge-wrap"> ', esc_html_x( 'on ', 'happy birthday ON 25-06', 'buddypress-birthdays' );
							$date_format = $instance['birthday_date_format'];
							$date_format = ( ! empty( $date_format ) ) ? $date_format : 'F d';

								// First, get the formatted date string
								$formatted_date = wp_date( $date_format, $birthday['datetime']->getTimestamp() );

								// Then, translate and escape the string
								$translated_date = esc_html__( $formatted_date, 'buddypress-birthdays' );

								// Finally, echo the span with the translated and escaped date
								echo '<span class="badge badge-primary badge-pill">' . $translated_date . '</span></span>';
								$happy_birthday_label = '';

							if ( $birthday['next_celebration_comparable_string'] === $date_ymd ) {
								$happy_birthday_message = __( 'Happy Birthday!', 'buddypress-birthdays' );
								$happy_birthday_label   = '<span class="badge badge-primary badge-pill">' . esc_html( $happy_birthday_message ) . '</span>';
							}

							if ( 'yes' === $instance['birthday_send_message'] && bp_is_active( 'messages' ) && is_user_logged_in() ) {
								echo '<a class="send_wishes" href=" ' . esc_url( $this->bbirthday_get_send_private_message_to_user_url( $user_id ) ) . '"/><span class="dashicons dashicons-email"></span><div class="tooltip_wishes">Send my wishes</div></a>';
							}
							echo '</div>';
							/**
							 * The label "Happy birthday", if today is the birthday of an user
							 *
							 * @param string $happy_birthday_label The text of the label (contains some HTML)
							 * @param int $user_id
							 */
							$happy_birthday_label = apply_filters( 'bbirthdays_today_happy_birthday_label', $happy_birthday_label, $user_id );
							echo wp_kses_post( $happy_birthday_label );
							echo '</span>';
							echo '</li>';

							$c++;
					}
				}
			}
			echo '</ul>';
		} else {
			if ( 'friends' === $instance['show_birthdays_of'] ) {
				if ( ! bp_is_active( 'friends' ) ) {
					esc_html_e( 'BuddyPress Friends Component is not activate.', 'buddypress-birthdays' );
				} else {
					esc_html_e( 'You don\'t have any friends. Make Friends and wish them!', 'buddypress-birthdays' );
				}
			} elseif ( 'followers' === $instance['show_birthdays_of'] ) {
				esc_html_e( 'You don\'t have any followings. Follow users to wish them!', 'buddypress-birthdays' );
			} elseif ( 'all' === $instance['show_birthdays_of'] ) {
				esc_html_e( 'Not a single user has updated their birthday yet. Tell them to update their birthday and wish them!', 'buddypress-birthdays' );
			}
		}
		echo wp_kses_post( $args['after_widget'] );
	}


	/**
	 * Get a link to send PM to the given User.
	 *
	 * @param int $user_id user id.
	 *
	 * @return string
	 */
	public function bbirthday_get_send_private_message_to_user_url( $user_id ) {
		return wp_nonce_url( bp_loggedin_user_domain() . bp_get_messages_slug() . '/compose/?r=' . bp_core_get_username( $user_id ) );
	}

	/**
	 * Action performed for get BuddyPress Birthdays users fields data.
	 *
	 * @param string $data Get a Birthday field name.
	 */
	public function bbirthdays_get_array( $data ) {

		$members = array();
		if ( isset( $data['show_birthdays_of'] ) && 'friends' === $data['show_birthdays_of'] && bp_is_active( 'friends' ) ) {
			$members = friends_get_friend_user_ids( get_current_user_id() );
		} elseif ( isset( $data['show_birthdays_of'] ) && 'followers' === $data['show_birthdays_of'] ) {
			if ( function_exists( 'bp_follow_get_following' ) ) {
				$members = bp_follow_get_following(
					array(
						'user_id' => bp_loggedin_user_id(),
					)
				);
			} elseif ( function_exists( 'bp_get_following_ids' ) ) {
				$members = bp_get_following_ids(
					array(
						'user_id' => bp_loggedin_user_id(),
					)
				);

				$members = explode( ',', $members );
			}
		}

		$members_birthdays = array();
		// Get the Birthday field name.
		$field_name   = isset( $data['birthday_field_name'] ) ? $data['birthday_field_name'] : '';
		$wp_time_zone = ! empty( get_option( 'timezone_string' ) ) ? new DateTimeZone( get_option( 'timezone_string' ) ) : wp_timezone();
		$field_name   = str_replace( "'", "\'", $field_name );

		// Get the Birthday field ID.
		$field_id = $field_name;

		// Set all data for the date limit check.
		$birthdays_limit = isset( $data['birthdays_range_limit'] ) ? $data['birthdays_range_limit'] : '';
		$today           = new DateTime( 'now', $wp_time_zone );
		$end             = new DateTime( 'now', $wp_time_zone );

		if ( 'monthly' === $birthdays_limit ) {
			$end->modify( '+30 days' );
		} elseif ( 'weekly' === $birthdays_limit ) {
			$end->modify( '+7 days' );
		} else {
			$end->modify( '+365 days' );
		}

		if ( ! empty( $members ) || ( isset( $data['show_birthdays_of'] ) && 'all' === $data['show_birthdays_of'] ) ) {

			$buddypress_wp_users = get_users(
				array(
					'fields'  => array( 'ID' ),
					'include' => $members,
				)
			);

			// Create a DatePeriod instance for the next 30 days
			$period = new DatePeriod( $today, new DateInterval( 'P1D' ), $end );

			foreach ( $period as $max_date ) {

				// We check if the member has a birthday set.
				foreach ( $buddypress_wp_users as $buddypress_wp_user ) {

					$birthday_string = maybe_unserialize( BP_XProfile_ProfileData::get_value_byid( $field_id, $buddypress_wp_user->ID ) );

					if ( empty( $birthday_string ) ) {
						continue;
					}

					// We transform the string in a date.
					$birthday = new DateTime( $birthday_string, $wp_time_zone );

					/**
					 * Filter if the current birthday (in the birthdays widget) can be displayed
					 *
					 * @param bool $is_displayed
					 * @param int $user_id
					 * @param DateTime $birthday
					 */
					$display_this_birthday = apply_filters( 'bbirthdays_display_this_birthday', true, $buddypress_wp_user->ID, $birthday );

					if ( false !== $birthday && $display_this_birthday ) {

						// Skip if birth date is not in the selected limit range..
						if ( ! $this->bbirthday_is_in_range_limit( $birthday, $max_date ) ) {
							continue;
						}
						if ( 'no_limit' === $birthdays_limit ) {
							$celebration_year = ( gmdate( 'md', $birthday->getTimestamp() ) >= gmdate( 'md' ) ) ? gmdate( 'Y' ) : gmdate( 'Y', strtotime( '+1 years' ) );
						} else {
							$celebration_year = ( gmdate( 'md', $birthday->getTimestamp() ) >= gmdate( 'md' ) ) ? gmdate( 'Y' ) : gmdate( 'Y', strtotime( 'now' ) );
						}

						$years_old = (int) $celebration_year - (int) gmdate( 'Y', $birthday->getTimestamp() );

						// If gone for this year already, we remove one year.
						if ( gmdate( 'md', $birthday->getTimestamp() ) >= gmdate( 'md' ) ) {
							--$years_old;
							// $years_old = $years_old - 1;
						}

						/**
						 * Filter bbirthdays_date_format
						 *
						 * Let you change the date format in which the birthday is displayed
						 * See: http://php.net/manual/en/function.date.php
						 *
						 * @param string - the date format PHP value
						 *
						 * @return string
						 */
						$format = apply_filters( 'bbirthdays_date_format', 'md' );
						if ( 'no_limit' === $birthdays_limit ) {
							$celebration_string = $celebration_year . gmdate( $format, $birthday->getTimestamp() );
						} else {
							$celebration_string = $celebration_year . $birthday->format( $format );
						}

						$members_birthdays[ $buddypress_wp_user->ID ] = array(
							'datetime'  => $birthday,
							'next_celebration_comparable_string' => $celebration_string,
							'years_old' => $years_old,
						);
					}
				}
			}
		}

		// uasort( $members_birthdays, array( $this, 'date_comparison' ) );

		return $members_birthdays;
	}

	/**
	 * Display the user name.
	 *
	 * @param string $user Get a user info.
	 */
	public function get_name_to_display( $user = null ) {

		if ( is_object( $user ) ) {
			$user_info = $user;
		} elseif ( is_numeric( $user ) ) {
			$user_info = get_userdata( $user );
		} else {
			$user_info = wp_get_current_user();
		}

		if ( ! isset( $user_info->user_login ) ) {
			return 'N/A';
		}

		if ( ( ! empty( $user_info->user_firstname ) || ! empty( $user_info->user_lastname ) ) ) {
			$display = $user_info->user_firstname . ' ' . $user_info->user_lastname;
		} else {
			$display = $user_info->user_login;
		}

		return esc_html( apply_filters( 'bbirthdays_get_name_to_display', $display, $user_info ) );
	}
	/**
	 * Action performed for Date comparison.
	 *
	 * @param string $a Next celebration comparable string.
	 * @param string $b Next celebration comparable string.
	 */
	public function date_comparison( $a, $b ) {
		return ( $a['next_celebration_comparable_string'] > $b['next_celebration_comparable_string'] ) ? 1 : -1;
	}
	/**
	 * BuddyPress Birthdays user birthday date range.
	 *
	 * @param string $birth_date Birthday date.
	 * @param  string $max_date Birthday max dates.
	 */
	public function bbirthday_is_in_range_limit( $birthdate, $max_date ) {

		if ( 'all' === $max_date ) {
			return true;
		}

		// Format the date for comparison
		$formatted_date = $max_date->format( 'm-d' );

		// Compare the month and day
		if ( $birthdate->format( 'm-d' ) == $formatted_date ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Update the user birthday data.
	 *
	 * @param  mixed $new_instance New instance.
	 * @param  mixed $old_instance Old instance.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();
		// $instance = wp_parse_args( (array) $new_instance, $old_instance );
		$instance['title']                 = ( ! empty( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['birthday_date_format']  = ( ! empty( $new_instance['birthday_date_format'] ) ) ? $new_instance['birthday_date_format'] : '';
		$instance['display_age']           = ( ! empty( $new_instance['display_age'] ) ) ? $new_instance['display_age'] : '';
		$instance['birthdays_range_limit'] = ( ! empty( $new_instance['birthdays_range_limit'] ) ) ? $new_instance['birthdays_range_limit'] : '';
		$instance['show_birthdays_of']     = ( ! empty( $new_instance['show_birthdays_of'] ) ) ? $new_instance['show_birthdays_of'] : '';
		$instance['birthdays_to_display']  = ( ! empty( $new_instance['birthdays_to_display'] ) ) ? $new_instance['birthdays_to_display'] : '';
		$instance['birthday_field_name']   = ( ! empty( $new_instance['birthday_field_name'] ) ) ? $new_instance['birthday_field_name'] : '';
		$instance['emoji']                 = ( ! empty( $new_instance['emoji'] ) ) ? $new_instance['emoji'] : '';
		$instance['birthday_send_message'] = ( ! empty( $new_instance['birthday_send_message'] ) ) ? $new_instance['birthday_send_message'] : '';
		$instance['display_name_type']     = ( ! empty( $new_instance['display_name_type'] ) ) ? $new_instance['display_name_type'] : '';

		return $instance;
	}

	/**
	 * Widget settings form.
	 *
	 * @param array $instance Saved values from database.
	 */
	public function form( $instance ) {

		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'                 => __( 'Upcoming Birthdays', 'buddypress-birthdays' ),
				'display_age'           => 'yes',
				'birthday_send_message' => 'yes',
				'birthday_date_format'  => 'F d',
				'birthdays_range_limit' => 'no_limit',
				'show_birthdays_of'     => 'all',
				'display_name_type'     => 'user_name',
				'birthdays_to_display'  => 5,
				'emoji'                 => 'balloon',
				'birthday_field_name'   => 'datebox',

			)
		);

		$profile_groups = bp_xprofile_get_groups(
			array(
				'fetch_fields'     => true,
				'fetch_field_data' => true,
			)
		);

		$fields = array();
		foreach ( $profile_groups as $single_group_details ) {
			if ( empty( $single_group_details->fields ) ) {
				continue;
			}
			foreach ( $single_group_details->fields as $group_single_field ) {
				if ( 'datebox' === $group_single_field->type || 'birthdate' === $group_single_field->type ) {
					$fields[ $group_single_field->id ] = $group_single_field->name;
				}
			}
		}

		// Buddyboss follow functionality support
		$bb_follow_buttons = false;
		if ( function_exists( 'bp_admin_setting_callback_enable_activity_follow' ) ) {
			$bb_follow_buttons = bp_is_activity_follow_active();
		}

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'buddypress-birthdays' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>"/>
		</p>

		<p>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'display_age' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_age' ) ); ?>" type="checkbox" value="<?php echo esc_attr( 'yes' ); ?>" <?php echo checked( 'yes', $instance['display_age'] ); ?>/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'display_age' ) ); ?>"><?php esc_html_e( 'Show the age of the person', 'buddypress-birthdays' ); ?></label>
		</p>
		<p>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'birthday_send_message' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'birthday_send_message' ) ); ?>" type="checkbox" value="<?php echo esc_attr( 'yes' ); ?>" <?php echo checked( 'yes', $instance['birthday_send_message'] ); ?>/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'birthday_send_message' ) ); ?>"><?php esc_html_e( 'Enable option to wish them', 'buddypress-birthdays' ); ?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'birthday_date_format' ) ); ?>"><?php esc_html_e( 'Date Format', 'buddypress-birthdays' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'birthday_date_format' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'birthday_date_format' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['birthday_date_format'] ); ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'birthdays_range_limit' ) ); ?>"><?php esc_html_e( 'Birthday range limit', 'buddypress-birthdays' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'birthdays_range_limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'birthdays_range_limit' ) ); ?>">
				<option value="no_limit" <?php echo selected( 'no_limit', $instance['birthdays_range_limit'] ); ?>><?php esc_html_e( 'No Limit', 'buddypress-birthdays' ); ?></option>
				<option value="weekly" <?php echo selected( 'weekly', $instance['birthdays_range_limit'] ); ?>><?php esc_html_e( 'Weekly', 'buddypress-birthdays' ); ?></option>
				<option value="monthly" <?php echo selected( 'monthly', $instance['birthdays_range_limit'] ); ?>><?php esc_html_e( 'Monthly', 'buddypress-birthdays' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_birthdays_of' ) ); ?>"><?php esc_html_e( 'Show Birthdays of', 'buddypress-birthdays' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'show_birthdays_of' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_birthdays_of' ) ); ?>">
				<?php if ( bp_is_active( 'follow' ) ) : ?>
					<option value="followers" <?php echo selected( 'followers', $instance['show_birthdays_of'] ); ?>><?php esc_html_e( 'Followings', 'buddypress-birthdays' ); ?></option>
				<?php elseif ( $bb_follow_buttons && function_exists( 'bp_add_follow_button' ) ) : ?>
					<option value="followers" <?php echo selected( 'followers', $instance['show_birthdays_of'] ); ?>><?php esc_html_e( 'Followings', 'buddypress-birthdays' ); ?></option>
				<?php endif; ?>
				<?php if ( bp_is_active( 'friends' ) ) : ?>
					<option value="friends" <?php echo selected( 'friends', $instance['show_birthdays_of'] ); ?>><?php esc_html_e( 'Friends', 'buddypress-birthdays' ); ?></option>
				<?php endif; ?>
					<option value="all" <?php echo selected( 'all', $instance['show_birthdays_of'] ); ?>><?php esc_html_e( 'All Members', 'buddypress-birthdays' ); ?></option>
			</select>
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'display_name_type' ) ); ?>"><?php esc_html_e( 'Display Name Type', 'buddypress-birthdays' ); ?></label>
			<select class='widefat' id="<?php echo esc_attr( $this->get_field_id( 'display_name_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_name_type' ) ); ?>">
				<option value="user_name" <?php echo selected( $instance['display_name_type'], 'user_name' ); ?>><?php esc_html_e( 'User name', 'buddypress-birthdays' ); ?></option>
				<option value="nick_name" <?php echo selected( $instance['display_name_type'], 'nick_name' ); ?>><?php esc_html_e( 'Nick name', 'buddypress-birthdays' ); ?></option>
				<option value="first_name" <?php echo selected( $instance['display_name_type'], 'first_name' ); ?>><?php esc_html_e( 'First Name', 'buddypress-birthdays' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'birthday_field_name' ) ); ?>"><?php esc_html_e( 'Field\'s name', 'buddypress-birthdays' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'birthday_field_name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'birthday_field_name' ) ); ?>">
				<?php foreach ( $fields as $key => $field ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php echo selected( $instance['birthday_field_name'], $key ); ?>><?php echo esc_attr( $field ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'birthdays_to_display' ) ); ?>"><?php esc_html_e( 'Number of birthdays to show', 'buddypress-birthdays' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'birthdays_to_display' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'birthdays_to_display' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['birthdays_to_display'] ); ?>"/>
		</p>
		<label><?php esc_html_e( 'Select Emoji', 'buddypress-birthdays' ); ?></label>
		<div class="bbirthday_emojis">
			<p style="display: inline-block; padding: 0 5px;">
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'emoji' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'emoji' ) ); ?>" type="radio" value="none" <?php checked( $instance['emoji'], 'none' ); ?>/>
				<label for="<?php echo esc_attr( $this->get_field_id( 'emoji' ) ); ?>"><?php esc_html_e( 'None', 'buddypress-birthdays' ); ?></label>
			</p>
			<p style="display: inline-block; padding: 0 5px;">
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'emoji' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'emoji' ) ); ?>" type="radio" value="cake" <?php checked( $instance['emoji'], 'cake' ); ?>/>
				<label for="<?php echo esc_attr( $this->get_field_id( 'emoji' ) ); ?>">&#x1F382;</label>
			</p>
			<p style="display: inline-block; padding: 0 5px;">
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'emoji' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'emoji' ) ); ?>" type="radio" value="balloon" <?php checked( $instance['emoji'], 'balloon' ); ?>/>
				<label for="<?php echo esc_attr( $this->get_field_id( 'emoji' ) ); ?>">&#x1F388;</label>
			</p>
			<p style="display: inline-block; padding: 0 5px;">
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'emoji' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'emoji' ) ); ?>" type="radio" value="party" <?php checked( $instance['emoji'], 'party' ); ?>/>
				<label for="<?php echo esc_attr( $this->get_field_id( 'emoji' ) ); ?>">&#127881;</label>
			</p>
	</div>
				<?php

	}

}

/**
 * Register BuddPress Birthdays widget.
 */
function buddypress_birthdays_register_widget() {
	register_widget( 'Widget_Buddypress_Birthdays' );
}
add_action( 'widgets_init', 'buddypress_birthdays_register_widget' );
