<?php

/**
 * Activity Form
 */
class Youzify_Activity_Form {

	function __construct() {

		// Add Action Tools.
		add_action( 'youzify_activity_form_post_types', array( $this, 'post_types_buttons' ) );

		// Handle Save Form Post - Normal Request.
		add_action( 'bp_actions', array( $this, 'action_post_update' ) );

		// Handle Save Form Post - Ajax Request.
		add_action( 'wp_ajax_youzify_post_update', array( $this, 'legacy_theme_post_update' ) );

		// Validate Wall Form
		add_action( 'youzify_before_adding_wall_post', array( $this, 'validate' ), 10 );

		// Save Activity Meta.
		add_action( 'youzify_after_adding_wall_post', array( $this, 'save_meta' ), 10 );

		// Save Activity Liver Preview Url.
		add_action( 'youzify_after_adding_wall_post', array( $this, 'save_live_preview' ), 10 );

		// Moderate Comment Content.
		add_action( 'bp_activity_before_save', array( $this, 'moderate_post' ), 10 );

	}

	/**
	 * Wall Form Post Types Options.
	 */
	function post_types_buttons() {

		$checked = true;

		$post_types = apply_filters( 'youzify_wall_form_post_types_buttons',
			array(
				'activity_status' => array(
					'uploader' => 'off',
					'icon' 	=> 'fas fa-comment-dots',
					'name'  => __( 'Status', 'youzify' ),
				),
				'activity_photo' => array(
					'icon' 	=> 'fas fa-camera-retro',
					'uploader' => 'on',
					'name'  => __( 'Photo', 'youzify' ),
				),
				'activity_slideshow' => array(
					'uploader' => 'on',
					'icon' 	=> 'fas fa-film',
					'name'  => __( 'Slideshow', 'youzify' ),
				),
				'activity_quote' => array(
					'uploader' => 'on',
					'icon' 	=> 'fas fa-quote-right',
					'name'  => __( 'Quote', 'youzify' ),
				),
				'activity_giphy' => array(
					'uploader' => 'off',
					'icon' 	=> 'fas fa-images',
					'name'  => __( 'GIF', 'youzify' ),
				),
				'activity_file' => array(
					'uploader' => 'on',
					'icon' 	=> 'fas fa-cloud-download-alt',
					'name'  => __( 'File', 'youzify' ),
				),
				'activity_video' => array(
					'uploader' => 'on',
					'icon' 	=> 'fas fa-video',
					'name'  => __( 'Video', 'youzify' ),
				),
				'activity_audio' => array(
					'uploader' => 'on',
					'icon' 	=> 'fas fa-volume-up',
					'name'  => __( 'Audio', 'youzify' ),
				),
				'activity_link' => array(
					'uploader' => 'on',
					'icon' 	=> 'fas fa-link',
					'name'  => __( 'Link', 'youzify' ),
				)
			)
		);

		// Get Unallowed Activities.
		$unallowed_activities = youzify_option( 'youzify_unallowed_activities' );

		if ( ! empty( $unallowed_activities ) ) {
			$unallowed_activities = (array) array_flip( $unallowed_activities );
		}

		$count = count( $post_types );

		foreach ( $post_types as $post_type => $data ) :

			if ( isset( $unallowed_activities[ $post_type ] ) ) {
				$count--;
				continue;
			} ?>

			<div class="youzify-wall-opts-item">
				<input type="radio" value="<?php echo $post_type; ?>" name="post_type" id="youzify-wall-add-<?php echo $post_type; ?>" <?php if ( $checked ) echo 'checked'; ?> data-uploader="<?php echo $data['uploader']; ?>">
				<label class="youzify-wall-add-<?php echo $post_type; ?> youzify-btn" for="youzify-wall-add-<?php echo $post_type; ?>">
					<i class="<?php echo $data['icon']; ?>"></i><span><?php echo $data['name']; ?></span>
				</label>
			</div>

			<?php $checked = false; ?>

		<?php endforeach;

		// After Printing Buttons.
		do_action( 'youzify_wall_form_post_types' );

		if ( $count > 5 ) : ?>
			<div class="youzify-wall-opts-item youzify-wall-opts-show-all"><label class="youzify-wall-form-show-all"><i class="fas fa-ellipsis-h"></i></label></div>
		<?php endif;

	}

	/**
	 * Post user/group activity update.
	 */
	function action_post_update() {

		// Do not proceed if user is not logged in, not viewing activity, or not posting.
		if ( ! is_user_logged_in() || ! bp_is_activity_component() || ! bp_is_current_action( 'post' ) ) {
			return false;
		}

		do_action( 'youzify_before_wall_post_update' );

		// Check the nonce.
		check_admin_referer( 'youzify_post_update', '_youzify_wpnonce_post_update' );

		// Init Vars.
		$post_type = sanitize_text_field( $_POST['post_type'] );

		/**
		 * Filters the content provided in the activity input field.
		 */
		$content = apply_filters( 'youzify_bp_activity_post_update_content', sanitize_textarea_field( $_POST['status'] ) );

		if ( ! empty( $_POST['whats-new-post-object'] ) ) {

			/**
			 * Filters the item type that the activity update should be associated with.
			 *
			 * @since 1.2.0
			 *
			 * @param string $value Item type to associate with.
			 */
			$object = apply_filters( 'bp_activity_post_update_object', sanitize_text_field( $_POST['whats-new-post-object'] ) );
		}

		if ( ! empty( $_POST['whats-new-post-in'] ) ) {

			/**
			 * Filters what component the activity is being to.
			 *
			 * @since 1.2.0
			 *
			 * @param string $value Chosen component to post activity to.
			 */
			$item_id = apply_filters( 'bp_activity_post_update_item_id', sanitize_text_field( $_POST['whats-new-post-in'] ) );
		}

		do_action( 'youzify_before_adding_wall_post' );

		// No existing item_id.
		if ( empty( $item_id ) ) {

			$activity_id = $this->activity_post_update( array(
				'content' => $content,
				'type'    => $post_type,
			) );

		// Post to groups object.
		} elseif ( 'groups' == $object && bp_is_active( 'groups' ) ) {
			if ( (int) $item_id ) {
				$activity_id = $this->groups_post_update(
					array(
						'content' => $content,
						'group_id' => $item_id,
						'type' => $post_type
					)
				);
			}
		} else {

			/**
			 * Filters activity object for BuddyPress core and plugin authors before posting activity update.
			 *
			 * @since 1.2.0
			 *
			 * @param string $object  Activity item being associated to.
			 * @param string $item_id Component ID being posted to.
			 * @param string $content Activity content being posted.
			 */
			$activity_id = apply_filters( 'bp_activity_custom_update', $object, $item_id, $content );
		}

		do_action( 'youzify_after_adding_wall_post', $activity_id );

		// Provide user feedback.
		if ( ! empty( $activity_id ) ) {
			bp_core_add_message( __( 'Update Posted!', 'youzify' ) );
		} else {
			bp_core_add_message( __( 'There was an error when posting your update. Please try again.', 'youzify' ), 'error' );
		}

		// Redirect.
		bp_core_redirect( wp_get_referer() );
	}

	/**
	 * Processes Activity updates received via a POST request.
	 *
	 */
	function legacy_theme_post_update() {

		$bp = buddypress();

		if ( ! bp_is_post_request() ) {
			return;
		}

		do_action( 'youzify_before_adding_wall_post', true );

		// Check the nonce.
		check_admin_referer( 'youzify_post_update', '_youzify_wpnonce_post_update' );

		/**
		 * Filters the content provided in the activity input field.
		 */
		$content = apply_filters( 'youzify_bp_activity_post_update_content', wp_kses_post( $_POST['status'] ) );

		$activity_id = 0;
		$item_id     = 0;
		$object      = '';

		// Try to get the item id from posted variables.
		if ( ! empty( $_POST['item_id'] ) ) {
			$item_id = absint( $_POST['item_id'] );
		}

		// Try to get the object from posted variables.
		if ( ! empty( $_POST['object'] ) ) {
			$object  = sanitize_key( $_POST['object'] );

		// If the object is not set and we're in a group, set the item id and the object
		} elseif ( bp_is_group() ) {
			$item_id = bp_get_current_group_id();
			$object = 'groups';
		}

		if ( isset( $_POST['youzify_share_form'] ) && $_POST['item_id'] == 0  ) {
			$object = 'user';
		}

		if ( ( ! $object && bp_is_active( 'activity' ) ) || $object == 'user' ) {

			$activity_id = $this->activity_post_update( array(
				'content' => $content,
				'type'    => sanitize_key( $_POST['post_type'] ),
			) );

		} elseif ( 'groups' === $object ) {
			if ( $item_id && bp_is_active( 'groups' ) ) {

				$activity_id = $this->groups_post_update(
					array(
						'content' => $content,
						'group_id' => $item_id,
						'type' => sanitize_key( $_POST['post_type'] )
					)
				);

			}

		} else {

			/** This filter is documented in bp-activity/bp-activity-actions.php */
			$activity_id = apply_filters( 'bp_activity_custom_update', false, $object, $item_id, sanitize_textarea_field( $_POST['content'] ) );
		}

		do_action( 'youzify_after_adding_wall_post', $activity_id );

		if ( false === $activity_id ) {
			wp_send_json_error( array( 'error' => __( 'There was a problem posting your update. Please try again.', 'youzify' ) ) );
		} elseif ( is_wp_error( $activity_id ) && $activity_id->get_error_code() ) {
			wp_send_json_error( array( 'error' => $activity_id->get_error_message() ) );
		}

		$last_recorded = ! empty( $_POST['since'] ) ? date( 'Y-m-d H:i:s', intval( $_POST['since'] ) ) : 0;
		if ( $last_recorded ) {
			$activity_args = array( 'since' => $last_recorded );
			$bp->activity->last_recorded = $last_recorded;
			add_filter( 'bp_get_activity_css_class', 'bp_activity_newest_class', 10, 1 );
		} else {
			$activity_args = array( 'include' => $activity_id );
		}

		$add_post_live = true;

		// Prevent adding post if it do not belong to current group.
		if ( isset( $_POST['youzify_share_form_current_group'] ) && $_POST['youzify_share_form_current_group'] != $_POST['item_id']  ) {
			$add_post_live = false;
		}

		// Remove Effect Class.
		remove_filter( 'bp_get_activity_css_class', 'youzify_add_activity_css_class' );

		if ( $add_post_live &&  bp_has_activities ( $activity_args ) ) {
			while ( bp_activities() ) {
				bp_the_activity();
				bp_get_template_part( 'activity/entry' );
			}
		}

		if ( ! empty( $last_recorded ) ) {
			remove_filter( 'bp_get_activity_css_class', 'bp_activity_newest_class', 10 );
		}

		exit;
	}

	/**
	 * Post an activity update.
	 */
	function activity_post_update( $args = '' ) {

		$r = wp_parse_args( $args, array(
			'content'    => false,
			'type'    	 => 'activity_update',
			'user_id'    => bp_loggedin_user_id(),
			'error_type' => 'bool',
		) );

		if ( bp_is_user_inactive( $r['user_id'] ) ) {
			return false;
		}

		// Record this on the user's profile.
		$activity_content = $r['content'];
		$primary_link     = bp_core_get_userlink( $r['user_id'], false, true );

		/**
		 * Filters the new activity content for current activity item.
		 */
		$add_content = apply_filters( 'bp_activity_new_update_content', $activity_content );

		/**
		 * Filters the activity primary link for current activity item.
		 */
		$add_primary_link = apply_filters( 'youzify_activity_new_update_primary_link', $primary_link );

		$activity_args = array(
			'user_id'      => $r['user_id'],
			'content'      => $add_content,
			'primary_link' => $add_primary_link,
			'component'    => buddypress()->activity->id,
			'type'         => $r['type'],
			'error_type'   => $r['error_type']
		);

		if ( isset( $_POST['secondary_item_id'] ) && ! empty( $_POST['secondary_item_id'] ) ) {
			$activity_args['secondary_item_id'] = absint( $_POST['secondary_item_id'] );
		}

		// Now write the values.
		$activity_id = bp_activity_add( $activity_args );

		// Bail on failure.
		if ( false === $activity_id || is_wp_error( $activity_id ) ) {
			return $activity_id;
		}

		/**
		 * Filters the latest update content for the activity item.
		 */
		$activity_content = apply_filters( 'youzify_activity_latest_update_content', $r['content'], $activity_content );

		// Add this update to the "latest update" usermeta so it can be fetched anywhere.
		bp_update_user_meta( bp_loggedin_user_id(), 'bp_latest_update', array(
			'id'      => $activity_id,
			'content' => $activity_content
		) );

		/**
		 * Fires at the end of an activity post update, before returning the updated activity item ID.
		 *
		 */
		do_action( 'youzify_activity_posted_update', $r['content'], $r['user_id'], $activity_id );
		do_action( 'bp_activity_posted_update', $r['content'], $r['user_id'], $activity_id );

		return $activity_id;
	}

	/**
	 * Post an Activity status update affiliated with a group.
	 */
	function groups_post_update( $args = '' ) {

		if ( ! bp_is_active( 'activity' ) ) {
			return false;
		}

		$bp = buddypress();

		$defaults = array(
			'content'    => false,
			'type'    	 => 'activity_update',
			'user_id'    => bp_loggedin_user_id(),
			'group_id'   => 0,
			'error_type' => 'bool'
		);

		$r = wp_parse_args( $args, $defaults );
		extract( $r, EXTR_SKIP );

		if ( empty( $group_id ) && !empty( $bp->groups->current_group->id ) )
			$group_id = $bp->groups->current_group->id;

		if ( empty( $user_id ) || empty( $group_id ) )
			return false;

		$bp->groups->current_group = groups_get_group( $group_id );

		// Be sure the user is a member of the group before posting.
		if ( ! bp_current_user_can( 'bp_moderate' ) && ! groups_is_user_member( $user_id, $group_id ) ) {
			return false;
		}

		// Record this in activity streams.
		$activity_action  = sprintf( __( '%1$s posted an update in the group %2$s', 'youzify' ), bp_core_get_userlink( $user_id ), '<a href="' . bp_get_group_url( $bp->groups->current_group ) . '">' . esc_attr( $bp->groups->current_group->name ) . '</a>' );
		$activity_content = $content;

		/**
		 * Filters the action for the new group activity update.
		 */
		$action = apply_filters( 'youzify_groups_activity_new_update_action',  $activity_action, $user_id, $group_id );

		/**
		 * Filters the content for the new group activity update.
		 */
		$content_filtered = apply_filters( 'youzify_groups_activity_new_update_content', $activity_content );

		$activity_args = array(
			'user_id'    => $user_id,
			'action'     => $action,
			'content'    => $content_filtered,
			'type'       => $r['type'],
			'item_id'    => $group_id,
			'error_type' => $error_type
		);

		if ( isset( $_POST['secondary_item_id'] ) && ! empty( $_POST['secondary_item_id'] ) ) {
			$activity_args['secondary_item_id'] = absint( $_POST['secondary_item_id'] );
		}

		$activity_id = groups_record_activity( $activity_args );

		groups_update_groupmeta( $group_id, 'last_activity', bp_core_current_time() );

		/**
		 * Fires after posting of an Activity status update affiliated with a group.
		 */
		do_action( 'youzify_groups_posted_update', $content, $user_id, $group_id, $activity_id );
		do_action( 'bp_groups_posted_update', $content, $user_id, $group_id, $activity_id );

		return $activity_id;
	}

	/**
	 * Save Activity Meta
	 */
	function save_meta( $activity_id ) {

		if ( isset( $_POST['youzify-activity-privacy'] ) && ! empty( $_POST['youzify-activity-privacy'] ) ) {

			if ( isset( $_POST['object'] ) && $_POST['object'] == 'groups' ) {
				$_POST['youzify-activity-privacy'] = 'public';
			}

			// Save Post Privacy.
			$this->save_privacy( $activity_id, sanitize_text_field( $_POST['youzify-activity-privacy'] ) );

		}

		// Save Post Tagged Users.
		if ( isset( $_POST['tagged_users'] ) && ! empty( $_POST['tagged_users'] ) ) {
			$tagged_users = array_map( 'intval', $_POST['tagged_users'] );
			bp_activity_update_meta( $activity_id, 'tagged_users', $tagged_users );
			do_action( 'youzify_after_activity_tagged_users_save', $activity_id, $tagged_users );
		}

		// Save Post Tagged Users.
		if ( isset( $_POST['checkin_place_id'] ) && ! empty( $_POST['checkin_place_id'] ) ) {
			bp_activity_update_meta( $activity_id, 'youzify_checkin_place_id', $_POST['checkin_place_id'] );
			bp_activity_update_meta( $activity_id, 'youzify_checkin_label', $_POST['checkin_label'] );
			bp_activity_update_meta( $activity_id, 'youzify_checkin', array( 'place_id' => $_POST['checkin_place_id'], 'label' => $_POST['checkin_label'] ) );
			do_action( 'youzify_after_activity_checkin_save', $activity_id );
		}

		// Save Post Feeling / Activity.
		if ( isset( $_POST['mood_value'] ) && ! empty( $_POST['mood_value'] ) ) {
			bp_activity_update_meta(
				$activity_id,
				'mood',
				array(
					'type' => sanitize_text_field( $_POST['mood_type'] ),
					'value' => sanitize_text_field( $_POST['mood_value'] )
				)
			);
		}

		if ( ! isset( $_POST['post_type'] ) ) {
			return;
		}

		switch ( sanitize_key( $_POST['post_type'] ) ) {

			case 'activity_link':

				// Init Vars.
				$link_url = esc_url_raw( $_POST['link_url'] );
				$link_desc = sanitize_textarea_field( $_POST['link_desc'] );
				$link_title = sanitize_text_field( trim( $_POST['link_title'] ) );

				// Save Data
				bp_activity_update_meta( $activity_id, 'youzify-link-url', $link_url );
				bp_activity_update_meta( $activity_id, 'youzify-link-desc', $link_desc );
				bp_activity_update_meta( $activity_id, 'youzify-link-title', $link_title );

				break;

			case 'activity_quote':

				// Init Vars.
				$quote_text = sanitize_textarea_field( $_POST['quote_text'] );
				$quote_owner = sanitize_text_field( $_POST['quote_owner'] );

				// Save Data.
				bp_activity_update_meta( $activity_id, 'youzify-quote-text', $quote_text );
				bp_activity_update_meta( $activity_id, 'youzify-quote-owner', $quote_owner );

				break;


			case 'activity_giphy':

				if ( ! empty( $_POST['giphy_image'] ) ) {
					bp_activity_update_meta( $activity_id, 'giphy_image', esc_url_raw( $_POST['giphy_image'] ) );
				}

				break;
		}

	}

	/**
	 * Save Activity Privacy.
	 */
	function save_privacy( $activity_id, $privacy ) {

		global $wpdb, $bp;

		// Prepare SQL
		$sql = $wpdb->prepare( "UPDATE {$bp->activity->table_name} SET privacy = %s WHERE id = %d", $privacy, $activity_id );

		// Update Privacy
		$wpdb->query( $sql );

	}

	/**
	 * Save Activity Meta
	 */
	function save_live_preview( $activity_id ) {

		if ( ! isset( $_POST['url_preview_link'] ) || empty( $_POST['url_preview_link'] ) ) {
			return;
		}

		// Check if use thumbnail
		$use_thumbnail = isset( $_POST['url_preview_use_thumbnail'] ) ? sanitize_text_field( $_POST['url_preview_use_thumbnail'] ) : 'off';

		$url_preview_args = array(
			'use_thumbnail' => $use_thumbnail,
			'image' 		=> esc_url_raw( $_POST['url_preview_img'] ),
			'link'  		=> esc_url_raw( $_POST['url_preview_link'] ),
			'site'  		=> sanitize_text_field( $_POST['url_preview_site'] ),
			'description'   => stripslashes( sanitize_textarea_field( $_POST['url_preview_desc'] ) ),
			'title' 		=> stripslashes( sanitize_text_field( $_POST['url_preview_title'] ) )
		);

		// Save Url Data.
		bp_activity_update_meta( $activity_id, 'url_preview', base64_encode( serialize( $url_preview_args ) ) );

		do_action( 'youzify_after_saving_activity_live_preview', $activity_id, $url_preview_args );

	}

	/**
	 * Validate Wall Form.
	 */
	function validate( $is_ajax = false ) {

		// Get Vars.
		$post_type = sanitize_text_field( $_POST['post_type'] );
		$post_content = wp_kses_post( $_POST['status'] );

		// Check Post Type.
		if ( apply_filters( 'youzify_validate_wall_form_post_type', true ) ) {

			// Get Allowed Post Types.
			$allowed_post_types = apply_filters( 'youzify_allowed_form_post_types', array( 'activity_status', 'activity_photo', 'activity_video' , 'activity_audio', 'activity_link', 'activity_slideshow','activity_file', 'activity_quote', 'activity_giphy', 'activity_share' ) );

			if ( ! in_array( $post_type, $allowed_post_types ) ) {
				$this->show_error( 'invalid_post_type' );
			}

		}

		// Check Attachments.
		if ( apply_filters( 'youzify_validate_wall_form_attachments', true ) ) {
			// Get Attachments Post Types.
			$attachments_post_types = array( 'activity_photo', 'activity_video', 'activity_audio', 'activity_slideshow', 'activity_file' );
			if ( in_array( $post_type, $attachments_post_types ) && empty( $_POST['attachments_files'] ) ) {
				$this->show_error( 'no_attachments' );
			}
		}


		// Check if status is empty.
		if ( 'activity_status' == $post_type || 'activity_comment' == $post_type ) {

			$checkin = isset( $_POST['checkin_place_id'] ) ? $_POST['checkin_place_id'] : '';

			if ( empty( $checkin ) ) {

				if ( ( empty( $post_content ) || ! strlen( trim( $post_content ) ) ) && 'off' == youzify_option( 'youzify_enable_wall_url_preview', 'on' ) ) {
					$this->show_error( 'empty_status' );
				}

				if ( ( empty( $post_content ) || ! strlen( trim( $post_content ) ) ) && 'on' == youzify_option( 'youzify_enable_wall_url_preview', 'on' ) && empty( $_POST['url_preview_link'] ) ) {
					$this->show_error( 'empty_status' );
				}
			}

		}

		if ( apply_filters( 'youzify_validate_wall_form_slideshow', true ) ) {
			// Check Slideshow Post.
			if ( 'activity_slideshow' == $post_type && count( $_POST['attachments_files'] ) < 2 ) {
				$this->show_error( 'slideshow_need_images' );
			}
		}

		// Check Quote Post.
		if ( 'activity_quote' == $post_type ) {

			// Init Vars.
			$quote_text = sanitize_textarea_field( $_POST['quote_text'] );
			$quote_owner = sanitize_text_field( trim( $_POST['quote_owner'] ) );

			// Validate Quote Owner.
			if ( empty( $quote_owner ) ) {
				$this->show_error( 'empty_quote_owner' );
			}

			// Validate Quote text.
			if ( empty( $quote_text ) ) {
				$this->show_error( 'empty_quote_text' );
			}

		}

		// Check Link Post.
		if ( 'activity_link' == $post_type ) {

			// Init Vars.
			$link_url = esc_url_raw( $_POST['link_url'] );
			$link_desc = sanitize_textarea_field( $_POST['link_desc'] );
			$link_title = sanitize_text_field( trim( $_POST['link_title'] ) );

			// Validate Link Url.
			if (  empty( $link_url ) ) {
				$this->show_error( 'empty_link_url' );
			}

			// Validate Link Url.
			if ( filter_var( $link_url, FILTER_VALIDATE_URL ) === false ) {
				$this->show_error( 'invalid_link_url' );
			}

			// Validate Link title.
			if ( empty( $link_title ) ) {
				$this->show_error( 'empty_link_title' );
			}

			// Validate Link Description.
			if ( empty( $link_desc ) ) {
				$this->show_error( 'empty_link_desc' );
			}
		}

		// Check Giphy Post.
		if ( 'activity_giphy' == $post_type ) {

			// Get Image.
			$giphy_image = isset( $_POST['giphy_image'] ) ? esc_url_raw( $_POST['giphy_image'] ) : '';

			// Check if image is empty.
			if ( empty( $giphy_image ) ) {
				$this->show_error( 'select_image' );
			}

			// Get Uploaded File extension
			$ext = strtolower( pathinfo( $giphy_image, PATHINFO_EXTENSION ) );

			// Check if image is gif.
			if ( 'gif' != $ext ) {
				$this->show_error( 'select_gif_image' );
			}

		}

	}

	/**
	 * Check for moderation keys and too many links.
	 *
	 */
	function moderate_post( $activity ) {

		// Bail if super admin is author.
		if ( is_super_admin( bp_loggedin_user_id() ) ) {
			return false;
		}

		/**
		 * Filters whether or not to bypass checking for moderation keys and too many links.
		 */
		if ( apply_filters( 'youzify_bypass_check_for_moderation', false ) ) {
			return true;
		}

		// Check if type is under moderation or not.
		if ( ! in_array( $activity->type, $this->moderation_post_types() ) ) {
			return true;
		}

		// Define local variable(s).
		$match_out = '';

		// Check for black list words.
		if ( $this->check_for_blacklist_words( $activity->content ) ){

			if ( $activity->type == 'activity_comment' ) {
				exit( '-1<div id="message" class="error bp-ajax-message"><p>' . __( 'You have used an inappropriate word.', 'youzify' ) . '</p></div>');
			} else {
				$this->show_error( 'word_inappropriate' );
			}

		}

	}

	/**
	 * Check for black list words.
	 **/
	function check_for_blacklist_words( $content ) {

		if ( empty( $content ) ) {
			return false;
		}

		// Get the moderation keys.
		$blacklist_words = youzify_option( 'youzify_moderation_keys' );

		// Bail if blacklist is empty.
		if ( ! empty( $blacklist_words ) ) {

			// Get words separated by new lines.
			// $words = explode( "\n", $blacklist );

			// Loop through words.
			foreach ( $blacklist_words as $word ) {

				// Trim the whitespace from the word.
				$word = trim( $word );

				// Skip empty lines.
				if ( empty( $word ) ) {
					continue;
				}

				// Do some escaping magic so that '#' chars in the
				// spam words don't break things.
				// $word    = preg_quote( $word, '#' );
				// $pattern = "#$word#i";

				// Check each user data for current word.
				if ( preg_match( '/\b' . $word . '\b/i', $content ) ) {
					return true;
				}

			}
		}

		return false;
	}

	/**
	 * Set Moderation Post Types.FV
	 */
	function moderation_post_types() {

	    $types = array( 'activity_link', 'activity_file', 'activity_audio', 'activity_photo', 'activity_video', 'activity_quote', 'activity_giphy', 'activity_status', 'activity_update', 'activity_comment' );

	    return apply_filters( 'youzify_moderation_post_types', $types );

	}

	/**
	 * Display Wall Error.
	 */
	function show_error( $code ) {

		if ( apply_filters( 'youzify_show_wall_error', true, $code ) ) {
			if ( wp_doing_ajax() ) {
				wp_send_json_error( array( 'error' => $this->msg( $code ) ) );
			} else {
			    // Get Reidrect page.
			    $redirect_to = ! empty( $redirect_to ) ? $redirect_to : wp_get_referer();

			    // Add Message.
			    bp_core_add_message( $this->msg( $code ), 'error' );

				// Redirect User.
		        wp_redirect( $redirect_to );
		        exit;
			}
		}

	}

    /**
     * Get Attachments Error Message.
     */
    public function msg( $code ) {

        // Messages
        switch ( $code ) {

            case 'empty_status':
                return __( 'Please type some text before posting.', 'youzify' );

            case 'invalid_post_type':
                return __( 'Invalid post type.', 'youzify' );

            case 'invalid_link_url':
                return __( 'Invalid link URL.', 'youzify' );

            case 'empty_link_url':
                return __( 'Empty link URL.', 'youzify' );

            case 'empty_link_title':
                return __( 'Please fill the link title field.', 'youzify' );

            case 'empty_link_desc':
                return __( 'Please fill the link description field.', 'youzify' );

            case 'empty_quote_owner':
                return __( 'Please fill the quote owner field.', 'youzify' );

            case 'empty_quote_text':
                return __( 'Please fill the quote text field.', 'youzify' );

            case 'word_inappropriate':
                return __( 'You have used an inappropriate word.', 'youzify' );

            case 'no_attachments':
                return __( 'No attachment was uploaded.', 'youzify' );

            case 'slideshow_need_images':
                return __( 'Slideshows need at least 2 images to work.', 'youzify' );

            case 'select_image':
                return __( 'Please select an image image before posting.', 'youzify' );

            case 'select_gif_image':
                return __( 'Please select a GIF image.', 'youzify' );

    	    return __( 'An unknown error occurred. Please try again later.', 'youzify' );
    	}

	}

}

new Youzify_Activity_Form();