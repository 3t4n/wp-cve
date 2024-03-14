<?php
/**
 * SBY_New_User.
 *
 * @since 2.18
 */

namespace SmashBalloon\YouTubeFeed\Admin;

class SBY_New_User extends SBY_Notifications {
	/**
	 * Source of notifications content.
	 *
	 * @since 2.18
	 *
	 * @var string
	 */
	const SOURCE_URL = 'https://plugin.smashballoon.com/newuser.json';

	/**
	 * @var string
	 */
	const OPTION_NAME = 'sby_newuser_notifications';

	/**
	 * Register hooks.
	 *
	 * @since 2.18
	 */
	public function hooks() {
		add_action( 'admin_notices', array( $this, 'output' ), 8 );

		add_action( 'admin_init', array( $this, 'dismiss' ) );
		add_action( 'wp_ajax_sby_review_notice_consent_update', array( $this, 'review_notice_consent' ) );
	}

	public function option_name() {
		return self::OPTION_NAME;
	}

	public function source_url() {
		return self::SOURCE_URL;
	}

	/**
	 * Verify notification data before it is saved.
	 *
	 * @param array $notifications Array of notifications items to verify.
	 *
	 * @return array
	 *
	 * @since 2.18
	 */
	public function verify( $notifications ) {
		$data = array();

		if ( ! is_array( $notifications ) || empty( $notifications ) ) {
			return $data;
		}

		$option = $this->get_option();

		foreach ( $notifications as $key => $notification ) {

			// The message should never be empty, if they are, ignore.
			if ( empty( $notification['content'] ) ) {
				continue;
			}

			// Ignore if notification has already been dismissed.
			if ( ! empty( $option['dismissed'] ) && in_array( $notification['id'], $option['dismissed'] ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
				continue;
			}

			$data[ $key ] = $notification;
		}

		return $data;
	}

	/**
	 * Verify saved notification data for active notifications.
	 *
	 * @since 2.18
	 *
	 * @param array $notifications Array of notifications items to verify.
	 *
	 * @return array
	 */
	public function verify_active( $notifications ) {
		if ( ! is_array( $notifications ) || empty( $notifications ) ) {
			return array();
		}

		$sby_statuses_option = get_option( 'sby_statuses', array() );
		$current_time = sby_get_current_time();

		// rating notice logic
		$sby_rating_notice_option = get_option( 'sby_rating_notice', false );
		$sby_rating_notice_waiting = get_transient( 'feeds_for_youtube_rating_notice_waiting' );
		$should_show_rating_notice = ($sby_rating_notice_waiting !== 'waiting' && $sby_rating_notice_option !== 'dismissed');

		// new user discount logic
		$in_new_user_month_range = true;
		$should_show_new_user_discount = false;
		$has_been_one_month_since_rating_dismissal = isset( $sby_statuses_option['rating_notice_dismissed'] ) ? ((int)$sby_statuses_option['rating_notice_dismissed'] + ((int)$notifications['review']['wait'] * DAY_IN_SECONDS)) < $current_time + 1: true;

		if ( isset( $sby_statuses_option['first_install'] ) && $sby_statuses_option['first_install'] === 'from_update' ) {
			global $current_user;
			$user_id = $current_user->ID;
			$ignore_new_user_sale_notice_meta = get_user_meta( $user_id, 'sby_ignore_new_user_sale_notice' );
			$ignore_new_user_sale_notice_meta = isset( $ignore_new_user_sale_notice_meta[0] ) ? $ignore_new_user_sale_notice_meta[0] : '';
			if ( $ignore_new_user_sale_notice_meta !== 'always' ) {
				$should_show_new_user_discount = true;
			}
		} elseif ( $in_new_user_month_range && $has_been_one_month_since_rating_dismissal && $sby_rating_notice_waiting !== 'waiting' ) {
			global $current_user;
			$user_id = $current_user->ID;
			$ignore_new_user_sale_notice_meta = get_user_meta( $user_id, 'sby_ignore_new_user_sale_notice' );
			$ignore_new_user_sale_notice_meta = isset( $ignore_new_user_sale_notice_meta[0] ) ? $ignore_new_user_sale_notice_meta[0] : '';

			if ( $ignore_new_user_sale_notice_meta !== 'always'
			     && isset( $sby_statuses_option['first_install'] )
			     && $current_time > (int)$sby_statuses_option['first_install'] + ((int)$notifications['discount']['wait'] * DAY_IN_SECONDS) ) {
				$should_show_new_user_discount = true;
			}
		}

		if ( sby_is_pro_version() ) {
			$should_show_new_user_discount = false;
		}

		if ( isset( $notifications['review'] ) && $should_show_rating_notice ) {
			return array( $notifications['review'] );
		} elseif ( isset( $notifications['discount'] ) && $should_show_new_user_discount ) {
			return array( $notifications['discount'] );
		}

		return array();
	}

	/**
	 * Get notification data.
	 *
	 * @since 2.18
	 *
	 * @return array
	 */
	public function get() {
		if ( ! $this->has_access() ) {
			return array();
		}

		$option = $this->get_option();

		// Only update if does not exist.
		if ( empty( $option['update'] ) ) {
			$this->update();
		}

		$events = ! empty( $option['events'] ) ? $this->verify_active( $option['events'] ) : array();
		$feed   = ! empty( $option['feed'] ) ? $this->verify_active( $option['feed'] ) : array();

		return array_merge( $events, $feed );
	}

	/**
	 * Add a manual notification event.
	 *
	 * @since 2.18
	 *
	 * @param array $notification Notification data.
	 */
	public function add( $notification ) {
		if ( empty( $notification['id'] ) ) {
			return;
		}

		$option = $this->get_option();

		if ( in_array( $notification['id'], $option['dismissed'] ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			return;
		}

		foreach ( $option['events'] as $item ) {
			if ( $item['id'] === $notification['id'] ) {
				return;
			}
		}

		$notification = $this->verify( array( $notification ) );

		update_option(
			$this->option_name(),
			array(
				'update'    => $option['update'],
				'feed'      => $option['feed'],
				'events'    => array_merge( $notification, $option['events'] ),
				'dismissed' => $option['dismissed'],
			)
		);
	}

	/**
	 * Update notification data from feed.
	 *
	 * @since 2.18
	 */
	public function update() {
		$feed   = $this->fetch_feed();
		$option = $this->get_option();

		update_option(
			$this->option_name(),
			array(
				'update'    => time(),
				'feed'      => $feed,
				'events'    => $option['events'],
				'dismissed' => $option['dismissed'],
			)
		);
	}

	/**
	 * Do not enqueue anything extra.
	 *
	 * @since 2.18
	 */
	public function enqueues() {

	}

	public function review_notice_consent() {
		//Security Checks
		check_ajax_referer( 'sby-admin', 'sby_nonce' );
		$cap = current_user_can( 'manage_youtube_feed_options' ) ? 'manage_youtube_feed_options' : 'manage_options';

		$cap = apply_filters( 'sby_settings_pages_capability', $cap );
		if ( ! current_user_can( $cap ) ) {
			wp_send_json_error(); // This auto-dies.
		}

		$consent = isset( $_POST[ 'consent' ] ) ? sanitize_text_field( $_POST[ 'consent' ] ) : '';

		update_option( 'sby_review_consent', $consent );

		if ( $consent == 'no' ) {
			$sby_statuses_option = get_option( 'sby_statuses', array() );
			update_option( 'sby_rating_notice', 'dismissed', false );
			$sby_statuses_option['rating_notice_dismissed'] = sby_get_current_time();
			update_option( 'sby_statuses', $sby_statuses_option, false );
		}
		wp_die();
	}

	/**
	 * Output notifications on Form Overview admin area.
	 *
	 * @since 2.18
	 */
	public function output() {
		// If the Instagram Feed plugin is active, notices only shown on SBY Settings pages
		if ( function_exists( 'sb_instagram_activate' )
		     && ! function_exists( 'sb_instagram_feed_pro_init' ) ) {
			return;
		}

		if ( function_exists( 'cff_check_for_db_updates' ) ) {
			return;
		}

		if ( function_exists( 'ctf_check_for_db_updates' ) ) {
			return;
		}

		$notifications = $this->get();

		if ( empty( $notifications ) ) {
			return;
		}

		// new user notices included in regular settings page notifications so this
		// checks to see if user is one of those pages
		if ( ! empty( $_GET['page'] )
			 && ( strpos( $_GET['page'], 'sby' ) !== false || strpos( $_GET['page'], 'youtube-feed' ) !== false ) ) {
			return;
		}

		$content_allowed_tags = array(
			'em'     => array(),
			'strong' => array(),
			'span'   => array(
				'style' => array(),
			),
			'a'      => array(
				'href'   => array(),
				'target' => array(),
				'rel'    => array(),
			),
		);
		$image_overlay = '';

		foreach ( $notifications as $notification ) {
			$img_src = SBY_PLUGIN_URL . 'img/' . sanitize_text_field( $notification['image'] );
			$type = sanitize_text_field( $notification['id'] );
			// check if this is a review notice
			if( $type == 'review' ) {
				$review_consent = get_option( 'sby_review_consent' );
				$sby_open_feedback_url = 'https://smashballoon.com/feedback/?plugin=youtube-free';
				// step #1 for the review notice
				if ( ! $review_consent ) {
					?>
					<div class="sby_notice sby_review_notice_step_1">
						<div class="sby_thumb">
							<img src="<?php echo esc_url( str_replace( array( 'sbi', 'png' ), array( 'sby', 'svg' ), $img_src ) ); ?>" alt="notice">
						</div>
						<div class="sby-notice-text">
							<p class="sby-notice-text-p"><?php echo __( 'Are you enjoying the YouTube Feed Plugin?', 'feeds-for-youtube' ); ?></p>
						</div>
						<div class="sby-notice-consent-btns">
							<?php
							printf(
								'<button class="sby-btn-link" id="sby_review_consent_yes">%s</button>',
								__( 'Yes', 'feeds-for-youtube' )
							);

							printf(
								'<a href="%s" target="_blank" rel="nofollow noopener" class="sby-btn-link"  id="sby_review_consent_no">%s</a>',
								$sby_open_feedback_url,
								__( 'No', 'feeds-for-youtube' )
							);
							?>
						</div>
					</div>
					<?php
				}
			}
			$close_href = add_query_arg( array( 'sby_dismiss' => $type ) );

			$title = $this->get_notice_title( $notification );
			$content = $this->get_notice_content( $notification, $content_allowed_tags );

			$buttons = array();
			if ( ! empty( $notification['btns'] ) && is_array( $notification['btns'] ) ) {
				foreach ( $notification['btns'] as $btn_type => $btn ) {
					if ( ! is_array( $btn['url'] ) ) {
						$buttons[ $btn_type ]['url'] = $this->replace_merge_fields( $btn['url'], $notification );
					} elseif ( is_array( $btn['url'] ) ) {
						$buttons[ $btn_type ]['url'] = add_query_arg( $btn['url'] );
					}

					$buttons[ $btn_type ]['attr'] = '';
					if ( ! empty( $btn['attr'] ) ) {
						$buttons[ $btn_type ]['attr'] = ' target="_blank" rel="noopener noreferrer"';
					}

					$buttons[ $btn_type ]['class'] = '';
					if ( ! empty( $btn['class'] ) ) {
						$buttons[ $btn_type ]['class'] = ' ' . $btn['class'];
					}

					$buttons[ $btn_type ]['text'] = '';
					if ( ! empty( $btn['text'] ) ) {
						$buttons[ $btn_type ]['text'] = wp_kses( $btn['text'], $content_allowed_tags );
					}
				}
			}
		}

		$review_consent = get_option( 'sby_review_consent' );
		$review_step2_style = '';
		if ( $type == 'review' && ! $review_consent ) {
			$review_step2_style = 'style="display: none;"';
		}
		?>

		<div class="sby_notice_op sby_notice sby_<?php echo esc_attr( $type ); ?>_notice" <?php echo !empty( $review_step2_style ) ? $review_step2_style : ''; ?>>
			<div class="sby_thumb">
				<img src="<?php echo esc_url( str_replace( array( 'sbi', 'png' ), array( 'sby', 'svg' ), $img_src ) ); ?>" alt="notice">
				<?php echo $image_overlay; ?>
			</div>
			<div class="sby-notice-text">
				<div class="sby-notice-text-inner">
					<h3 class="sby-notice-text-header"><?php echo $title; ?></h3>
					<p class="sby-notice-text-p"><?php echo $content; ?></p>
				</div>
				<div class="sby-notice-btns-wrap">
					<p class="sby-notice-links">
						<?php
						foreach ( $buttons as $type => $button ) :
							$btn_classes = array('sby-btn');
							$btn_classes[] = esc_attr( $button['class'] );
							if ( $type == 'primary' ) {
								$btn_classes[] = 'sby-btn-blue';
							} else {
								$btn_classes[] = 'sby-btn-grey';
							}
							?>
							<a class="<?php echo implode(' ', $btn_classes); ?>" href="<?php echo esc_attr( str_replace( 'sbi_', 'sby_', $button['url'] ) ); ?>"<?php echo $button['attr']; ?>><?php echo $button['text']; ?></a>
						<?php endforeach; ?>
					</p>
				</div>
			</div>
			<div class="sby-notice-dismiss">
				<a href="<?php echo esc_url( $close_href ); ?>">
					<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M14 1.41L12.59 0L7 5.59L1.41 0L0 1.41L5.59 7L0 12.59L1.41 14L7 8.41L12.59 14L14 12.59L8.41 7L14 1.41Z" fill="#141B38"></path>
					</svg>
				</a>
			</div>
		</div>
		<?php
	}

	/**
	 * SBY Get Notice Title depending on the notice type
	 *
	 * @since 2.0
	 *
	 * @param array $notification
	 *
	 * @return string $title
	 */
	public function get_notice_title( $notification ) {
		$type = $notification['id'];
		$title = '';

		// Notice title depending on notice type
		if ( $type == 'review' ) {
			$title = __( 'Glad to hear you are enjoying it. Would you consider leaving a positive review?', 'feeds-for-youtube' );
		} else if ( $type == 'discount' ) {
			$title =  __( 'Exclusive Offer! 60% OFF', 'feeds-for-youtube' );
		} else {
			$title = $this->replace_merge_fields( $notification['title'], $notification );
		}

		return $title;
	}

	/**
	 * SBY Get Notice Content depending on the notice type
	 *
	 * @since 2.0
	 *
	 * @param array $notification
	 * @param array $content_allowed_tags
	 *
	 * @return string $content
	 */
	public function get_notice_content( $notification, $content_allowed_tags ) {
		$type = $notification['id'];
		$content = '';

		// Notice content depending on notice type
		if ( $type == 'review' ) {
			$content = __( 'It really helps to support the plugin and help others to discover it too!', 'feeds-for-youtube' );
		} else if ( $type == 'discount' ) {
			$content =  __( 'We don’t run promotions very often, but for a limited time we’re offering 60% Off our Pro version to all users of our free YouTube Feed.', 'feeds-for-youtube' );
		} else {
			if ( ! empty( $notification['content'] ) ) {
				$content = wp_kses( $this->replace_merge_fields( $notification['content'], $notification ), $content_allowed_tags );
			}
		}
		return $content;
	}

	/**
	 * Hide messages permanently or some can be dismissed temporarily
	 *
	 * @since 2.18
	 */
	public function dismiss() {
		global $current_user;
		$user_id = $current_user->ID;
		$sby_statuses_option = get_option( 'sby_statuses', array() );

		if ( isset( $_GET['sby_ignore_rating_notice_nag'] ) ) {
			if ( (int)$_GET['sby_ignore_rating_notice_nag'] === 1 ) {
				update_option( 'sby_rating_notice', 'dismissed', false );
				$sby_statuses_option['rating_notice_dismissed'] = sby_get_current_time();
				update_option( 'sby_statuses', $sby_statuses_option, false );

			} elseif ( $_GET['sby_ignore_rating_notice_nag'] === 'later' ) {
				set_transient( 'feeds_for_youtube_rating_notice_waiting', 'waiting', 2 * WEEK_IN_SECONDS );
				update_option( 'sby_rating_notice', 'pending', false );
			}
		}

		if ( isset( $_GET['sby_ignore_new_user_sale_notice'] ) ) {
			$response = sanitize_text_field( $_GET['sby_ignore_new_user_sale_notice'] );
			if ( $response === 'always' ) {
				update_user_meta( $user_id, 'sby_ignore_new_user_sale_notice', 'always' );

				$current_month_number = (int)date('n', sby_get_current_time() );
				$not_early_in_the_year = ($current_month_number > 5);

				if ( $not_early_in_the_year ) {
					update_user_meta( $user_id, 'sby_ignore_bfcm_sale_notice', date( 'Y', sby_get_current_time() ) );
				}

			}
		}

		if ( isset( $_GET['sby_ignore_bfcm_sale_notice'] ) ) {
			$response = sanitize_text_field( $_GET['sby_ignore_bfcm_sale_notice'] );
			if ( $response === 'always' ) {
				update_user_meta( $user_id, 'sby_ignore_bfcm_sale_notice', 'always' );
			} elseif ( $response === date( 'Y', sby_get_current_time() ) ) {
				update_user_meta( $user_id, 'sby_ignore_bfcm_sale_notice', date( 'Y', sby_get_current_time() ) );
			}
			update_user_meta( $user_id, 'sby_ignore_new_user_sale_notice', 'always' );
		}

		if ( isset( $_GET['sby_dismiss'] ) ) {
			if ( $_GET['sby_dismiss'] === 'review' ) {
				update_option( 'sby_rating_notice', 'dismissed', false );
				$sby_statuses_option['rating_notice_dismissed'] = sby_get_current_time();
				update_option( 'sby_statuses', $sby_statuses_option, false );
			} elseif ( $_GET['sby_dismiss'] === 'discount' ) {
				update_user_meta( $user_id, 'sby_ignore_new_user_sale_notice', 'always' );

				$current_month_number = (int)date('n', sby_get_current_time() );
				$not_early_in_the_year = ($current_month_number > 5);

				if ( $not_early_in_the_year ) {
					update_user_meta( $user_id, 'sby_ignore_bfcm_sale_notice', date( 'Y', sby_get_current_time() ) );
				}
			}
			update_user_meta( $user_id, 'sby_ignore_new_user_sale_notice', 'always' );
		}
	}
}
