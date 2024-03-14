<?php

/**
 * Group Notifications Widget
 */

class Youzify_Notifications_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'youzify_notifications_widget',
			__( 'Youzify - Notifications', 'youzify' ),
			array( 'description' => __( 'User notifications widget.', 'youzify' ) )
		);
	}

	/**
	 * Back-end widget form.
	 */
	public function form( $instance ) {

	    // Get Widget Data.
	    $instance = wp_parse_args( (array) $instance,
		    array(
		    	'title' => __( 'Notifications', 'youzify' ),
		        'limit' => '5'
		    )
	     );

	    // Get Input's Data.
		$limit = absint( $instance['limit'] );
		$title = strip_tags( $instance['title'] );

		?>

		<!-- Title. -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'youzify' ); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="widefat" value="<?php echo esc_attr( $title ); ?>">
		</p>

		<!-- Notifications Number. -->
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Notifications Limit:', 'bp-group-suggest' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="text" value="<?php echo esc_attr( $limit ); ?>" style="width: 30%">
			</label>
		</p>

		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();

		$instance = $old_instance;
		$instance['limit'] = absint( $new_instance['limit'] );
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Widget Content
	 */
	public function widget( $args, $instance ) {

		// Hide Widget if notifications not enabled and for Not Logged-In users.
		if ( ! bp_is_active( 'notifications' ) || ! is_user_logged_in() ) {
			return;
		}

		// Get Notifications
		$notifications_nbr = bp_notifications_get_unread_notification_count( bp_loggedin_user_id() );

		if ( $notifications_nbr <= 0  ) {
			return;
		}

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'];
			echo apply_filters( 'widget_title', $instance['title'] );
			echo $args['after_title'];
		}

		$this->get_notifications( $instance );

		echo $args['after_widget'];

	}

	/**
	 * Get User Notifications.
	 */
	function get_notifications( $args ) {

		// Get All User Notifications.
		$notifications = bp_notifications_get_notifications_for_user( bp_loggedin_user_id(), 'object' );

		// Get Notifications Count.
		$notifications_nbr = count( $notifications );

		// Limit Notifications Number
		$notifications = array_slice( $notifications, 0, $args['limit'] );

		?>

		<div class="youzify-notifications-widget youzify-notif-icons-circle youzify-notif-icons-colorful">

		<?php foreach ( $notifications as $notification ) : ?>

		<div class="youzify-notif-item youzify-notif-<?php echo $notification->component_action; ?>">
			<a href="<?php echo $notification->href; ?>" class="youzify-notif-icon"><?php echo youzify_get_notification_icon( $notification ); ?></a>
			<div class="youzify-notif-content">
				<a href="<?php echo $notification->href; ?>" class="youzify-notif-desc"><?php echo $notification->content; ?></a>
				<div class="youzify-notif-time"><i class="far fa-clock"></i><span class="youzify-notif-date"><?php echo bp_core_time_since( $notification->date_notified ); ?></span></div>
			</div>
		</div>

		<?php endforeach; ?>

        <?php if ( $notifications_nbr > $args['limit'] ) : ?>
            <div class="youzify-more-items">
                <a href="<?php echo bp_nav_menu_get_item_url( bp_get_notifications_slug() ); ?>"><i class="far fa-bell"></i><?php echo sprintf( __( 'Show All Notifications ( %s )', 'youzify' ), $notifications_nbr ); ?></a>
            </div>
        <?php endif; ?>

		</div>

		<?php
	}

}