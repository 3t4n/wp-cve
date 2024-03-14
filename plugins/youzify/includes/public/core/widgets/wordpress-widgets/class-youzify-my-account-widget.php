<?php

/**
 * User Account Menu Widget
 */

class Youzify_My_Account_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'youzify_my_account_widget',
			__( 'Youzify - My Account', 'youzify' ),
			array( 'description' => __( 'User account menu', 'youzify' ) )
		);
	}

	/**
	 * Back-end widget form.
	 */
	public function form( $instance ) {

	    // Get Widget Data.
	    $instance = wp_parse_args( (array) $instance, $this->default_options() );

		?>

		<!-- Hide Sections. -->
		<p>
			<label><?php _e( 'Hide Sections:', 'youzify' ); ?></label><br>
			<?php foreach( $instance['hide_sections'] as $name => $item ) : ?>
		    <input id="<?php echo $this->get_field_id( 'hide_sections' ) . $name; ?>" name="<?php echo $this->get_field_name( 'hide_sections' ); ?>[<?php echo $name; ?>]" type="checkbox" <?php checked( $instance['hide_sections'][ $name ]['hide'], 'on' ); ?>><label for="<?php echo $this->get_field_id( 'hide_sections' ) . $name; ?>"><?php echo $item['name']; ?></label><br>
		    <?php endforeach; ?>
		</p>

		<!-- Hide Links. -->
		<p>
			<label><?php _e( 'Hide Links:', 'youzify' ); ?></label><br>
			<?php foreach( $instance['hide_links'] as $name => $item ) : ?>
		    <input id="<?php echo $this->get_field_id( 'hide_links' ) . $name; ?>" name="<?php echo $this->get_field_name( 'hide_links' ); ?>[<?php echo $name; ?>]" type="checkbox" <?php checked( $instance['hide_links'][ $name ]['hide'], 'on' ); ?>>
		    <label for="<?php echo $this->get_field_id( 'hide_links' ) . $name; ?>"><?php echo $item['name']; ?></label><br>
		    <?php endforeach; ?>
		</p>

		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();

		// Update Fields..
		$instance = $old_instance;

		// Save Hide Links
		foreach ( $this->hide_links() as $name => $item ) {
			// Get Value.
			$value = $new_instance['hide_links'][ $name ];
			// Save Values.
			$instance['hide_links'][ $name ] = $item;
			$instance['hide_links'][ $name ]['hide'] = ! empty( $value ) ? $value : 'off';
		}

		// Save Hide Sections
		foreach ( $this->hide_sections() as $name => $item ) {
			// Get Value.
			$value = $new_instance['hide_sections'][ $name ];
			// Save Values.
			$instance['hide_sections'][ $name ] = $item;
			$instance['hide_sections'][ $name ]['hide'] = ! empty( $value ) ? $value : 'off';
		}

		return $instance;
	}

	/**
	 * Widget Content
	 */
	public function widget( $args, $instance ) {

		// Hide Widget User Not Logged-In.
		if ( ! is_user_logged_in() ) {
			return false;
		}

		// Get Account Widget.
		$this->get_account_menu( $instance );

	}

	/**
	 * Get User Account Menu.
	 */
	function get_account_menu( $args ) {

		// Init Vars.
		$hide_links = isset( $args['hide_links'] ) ? $args['hide_links'] : array();
		$hide_sections = isset( $args['hide_sections'] ) ? $args['hide_sections'] : array();

		// Get User id.
		$user_id = get_current_user_id();

		// Get User Avatar.
		$avatar = bp_core_fetch_avatar( array( 'item_id' => $user_id, 'type' => 'full' ) );

		// Get User Profile Link.
		$profile_url = bp_members_get_user_url( $user_id );

		?>

		<div class="youzify-my-account-widget">

			<div class="youzify-widget-header">
				<a href="<?php echo $profile_url; ?>" class="youzify-head-avatar youzify-avatar-border-radius"><?php echo $avatar; ?></a>
				<div class="youzify-widget-head">
					<span class="youzify-hello"><?php _e( 'Hello!', 'youzify' ); ?></span>
					<a href="<?php echo $profile_url; ?>" class="youzify-user-name"><?php echo bp_core_get_user_displayname( $user_id ); ?></a>
				</div>
			</div>

			<div class="youzify-menu-links youzify-menu-icon-circle youzify-menu-icon-colorful">

			<?php if ( 'off' == $hide_sections['account']['hide'] ) : ?>

			<div class="youzify-links-section">

				<span class="youzify-section-title"><?php _e( 'Account', 'youzify' ); ?></span>

	        	<?php if ( bp_is_active( 'messages' ) && 'off' == $hide_links['messages']['hide'] ) : ?>

	            	<?php $msgs_nbr = bp_get_total_unread_messages_count(); ?>
	            	<?php $msg_title = ( $msgs_nbr > 0 ) ? sprintf( __( 'Messages %s' , 'youzify' ), '<span class="youzify-link-count">' . $msgs_nbr . '</span>' ) : __( 'Messages' , 'youzify' ); ?>

					<a href="<?php echo bp_nav_menu_get_item_url( bp_get_messages_slug() ); ?>" class="youzify-link-item youzify-link-inbox">
						<i class="fas fa-inbox"></i>
						<div class="youzify-link-title"><?php echo $msg_title ;?></div>
					</a>

				<?php endif; ?>

		        <?php if ( bp_is_active( 'notifications' ) && 'off' == $hide_links['notifications']['hide'] ) : ?>

		            <?php $notification_nbr = bp_notifications_get_unread_notification_count(); ?>

					<?php $notifications_title = ( $notification_nbr > 0 ) ? sprintf( __( 'Notifications %s' , 'youzify' ), '<span class="youzify-link-count">' . $notification_nbr . '</span>' ) : __( 'Notifications' , 'youzify' ); ?>

					<a href="<?php echo bp_nav_menu_get_item_url( bp_get_notifications_slug() ); ?>" class="youzify-link-item youzify-link-notifications">
						<i class="fas fa-bell"></i>
						<div class="youzify-link-title"><?php echo $notifications_title ;?></div>
					</a>

				<?php endif; ?>

	   			<?php if ( bp_is_active( 'friends' ) && 'off' == $hide_links['friendship-requests']['hide'] ) : ?>

		            <?php

		            // Get Buttons Data
	                $friend_requests = bp_friend_get_total_requests_count();
	                $requests_link = trailingslashit( bp_loggedin_user_domain() . bp_get_friends_slug() ) . 'requests';

		            ?>

		            <?php if (  $friend_requests > 0 ) : ?>

						<a href="<?php echo $requests_link; ?>" class="youzify-link-item youzify-link-friendship-requests">
							<i class="fas fa-handshake"></i>
							<div class="youzify-link-title"><?php echo sprintf( __( 'Friendship Requests %s' , 'youzify' ), '<span class="youzify-link-count">' . $friend_requests . '</span>' ); ?></div>
						</a>

					<?php endif; ?>

				<?php endif; ?>

				<?php do_action( 'youzify_after_myaccount_widget_account_links' ); ?>
			</div>

			<?php endif; ?>

   			<?php if ( 'off' == $hide_sections['settings']['hide'] ) : ?>

			<div class="youzify-links-section">

				<span class="youzify-section-title"><?php _e( 'Settings', 'youzify' ); ?></span>

				<?php if ( 'off' == $hide_links['profile-settings']['hide'] ) : ?>
				<a href="<?php echo youzify_get_profile_settings_url( false, $user_id ); ?>" class="youzify-link-item youzify-link-profile-settings">
					<i class="fas fa-user"></i>
					<div class="youzify-link-title"><?php _e( 'Profile Settings' , 'youzify' ); ?></div>
				</a>
				<?php endif; ?>

				<?php if (  bp_is_active( 'settings' ) && 'off' == $hide_links['account-settings']['hide'] ) : ?>
				<a href="<?php echo bp_members_get_user_url( $user_id ) . bp_get_settings_slug(); ?>" class="youzify-link-item youzify-link-account-settings">
					<i class="fas fa-cogs"></i>
					<div class="youzify-link-title"><?php _e( 'Account Settings' , 'youzify' ); ?></div>
				</a>
				<?php endif; ?>

				<?php if ( 'off' == $hide_links['widgets-settings']['hide'] ) : ?>
				<a href="<?php echo youzify_get_widgets_settings_url( false, $user_id ); ?>" class="youzify-link-item youzify-link-widgets-settings">
					<i class="fas fa-th"></i>
					<div class="youzify-link-title"><?php _e( 'Widgets Settings' , 'youzify' ); ?></div>
				</a>
				<?php endif; ?>

				<?php do_action( 'youzify_after_myaccount_widget_settings_links' ); ?>

			</div>

			<?php endif; ?>


			<?php if ( 'off' == $hide_links['logout']['hide'] ) : ?>
			<a href="<?php echo wp_logout_url(); ?>" class="youzify-link-item youzify-link-logout">
				<i class="fas fa-power-off"></i>
				<div class="youzify-link-title"><?php _e( 'Log Out' , 'youzify' ); ?></div>
			</a	>
			<?php endif; ?>

			</div>

		</div>

		<?php
	}

	/**
	 * Hide Sections Options
 	 */
	function hide_sections() {
		$options = array(
			'account' => array( 'name' => __( 'Account', 'youzify' ), 'hide' => 'off' ),
			'settings' => array( 'name' => __( 'Settings', 'youzify' ), 'hide' => 'off' ),
		);
		return $options;
	}

	/**
	 * Hide Links Options
 	 */
	function hide_links() {

		$options = array(
			'messages' => array( 'name' => __( 'Messages', 'youzify' ), 'hide' => 'off' ),
			'notifications' => array( 'name' => __( 'Notifications', 'youzify' ), 'hide' => 'off' ),
			'friendship-requests' => array( 'name' => __( 'Friendship Requests', 'youzify' ), 'hide' => 'off' ),
			'profile-settings' => array( 'name' => __( 'Profile Settings', 'youzify' ), 'hide' => 'off' ),
			'account-settings' => array( 'name' => __( 'Account Settings', 'youzify' ), 'hide' => 'off' ),
			'widgets-settings' => array( 'name' => __( 'Widgets Settings', 'youzify' ), 'hide' => 'off' ),
			'logout' => array( 'name' => __( 'Logout', 'youzify' ), 'hide' => 'off' )
		);

		return $options;
	}

	/**
	 * Default Options
 	 */
	function default_options() {

		$default_options = array(
	        'hide_sections' => $this->hide_sections(),
	        'hide_links' => $this->hide_links(),
	    );

		return $default_options;
	}

}