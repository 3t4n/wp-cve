<?php

if ( !defined( 'ABSPATH' ) ) exit;

require( dirname( __FILE__ ) . '/bp-group-chatroom-db-functions.php' );


if ( file_exists( dirname( __FILE__ ) . '/languages/bp-group-chatr0om-' . get_locale() . '.mo' ) )
	load_textdomain( 'bp-group-chatroom', dirname( __FILE__ ) . '/languages/bp-group-chatroom-' . get_locale() . '.mo' );

/**
 * bp_group_chat_setup_globals()
 *
 * Sets up global variables for your component.
 */
function bp_group_chat_setup_globals() {
	global $bp, $wpdb;

	/* For internal identification */
	$bp->chat->id = 'chat';

	$bp->chat->table_name = $wpdb->base_prefix . 'bp_group_chat';
	$bp->chat->format_notification_function = 'bp_group_chat_format_notifications';
	$bp->chat->slug = BP_GROUP_CHATROOM_SLUG;

	/* Register this in the active components array */
	$bp->active_components[$bp->chat->slug] = $bp->chat->id;
}
add_action( 'bp_setup_globals', 'bp_group_chat_setup_globals' );


class BP_Group_Chat extends BP_Group_Extension {	

	function __construct() {
		global $bp;
		
		$this->name = 'Chat';
		$this->slug = 'chat';

		$this->create_step_position = 16;
		$this->nav_item_position = 31;
		if ( isset( $bp->groups->current_group->id ) ) {
			$settings = groups_get_groupmeta( $bp->groups->current_group->id, 'bp_group_chat_enabled' );
		}
		if ( isset( $settings['enabled'] ) && $settings['enabled'] == '1' ) {
			$this->enable_nav_item = true;
		} else {
			$this->enable_nav_item = false;
		}
		
				
	}	
	
	function create_screen( $group_id = null) {
		global $bp;
		
		if ( ! $group_id ) {
			$group_id = $bp->groups->current_group->id;
		}
		
		if ( !bp_is_group_creation_step( $this->slug ) )
			return false;
			
		wp_nonce_field( 'groups_create_save_' . $this->slug );
		$settings = groups_get_groupmeta( $group_id, 'bp_group_chat_enabled' );
		?>
		<input type="checkbox" name="bp_group_chat_enabled" id="bp_group_chat_enabled" value="1"  
			<?php 
			if ( isset( $settings['enabled'] ) && $settings['enabled'] == '1' ) {
				echo 'checked=1';
			}
			?>
		/>
		<?php echo sanitize_text_field( __( 'Enable Group Chat', 'bp-group-chatroom' ) ); ?>
		
		<hr>
		<?php
	}

	function create_screen_save( $group_id = null) {
		global $bp;
		
		if ( ! $group_id ) {
			$group_id = $bp->groups->current_group->id;
		}

		check_admin_referer( 'groups_create_save_' . $this->slug );	
		
		if ( sanitize_text_field( $_POST['bp_group_chat_enabled'] ) == 1 ) {
			groups_update_groupmeta( $group_id, 'bp_group_chat_enabled', array( 'enabled' => 1 ) );
		}

	}

	function edit_screen( $group_id = null ) {
		global $bp;
		
		if ( !groups_is_user_admin( $bp->loggedin_user->id, $bp->groups->current_group->id ) && ! current_user_can( 'bp_moderate' ) ) {
			return false;
		}
		
		if ( !bp_is_group_admin_screen( $this->slug ) )
			return false;
			
		if ( ! $group_id ) {
			$group_id = $bp->groups->current_group->id;
		}

		$delete_time_option_array = array(
			'1800' => sanitize_text_field( __( '30 Mins', 'bp-group-chatroom' ) ),
			'3600' =>  sanitize_text_field( __( '1 Hour', 'bp-group-chatroom' ) ),
			'7200' =>  sanitize_text_field( __( '2 Hours', 'bp-group-chatroom' ) ),
			'21600' =>  sanitize_text_field( __( '6 hours', 'bp-group-chatroom' ) ),
			'86400' =>  sanitize_text_field( __( '1 Day', 'bp-group-chatroom' ) ),
			'172800' =>  sanitize_text_field( __( '2 Days', 'bp-group-chatroom' ) ),
			'345600' =>  sanitize_text_field( __( '4 Days', 'bp-group-chatroom' ) ),
			'864000' =>  sanitize_text_field( __( '10 Days', 'bp-group-chatroom' ) ),
			'1728000' =>  sanitize_text_field( __( '20 Days', 'bp-group-chatroom' ) ),
			'2592000' =>  sanitize_text_field( __( '30 Days', 'bp-group-chatroom' ) )
		);
			
		wp_nonce_field( 'groups_edit_save_' . $this->slug );
		$settings = groups_get_groupmeta( $group_id, 'bp_group_chat_enabled' );
		?>
		<input type="checkbox" name="bp_group_chat_enabled" id="bp_group_chat_enabled" value="1"  
			<?php 
			if ( isset( $settings['enabled'] ) && $settings['enabled'] == '1' ) {
				echo 'checked="checked"';
			}
			?>
		/>
		<?php echo sanitize_text_field( __( 'Enable Group Chat', 'bp-group-chatroom' ) ); ?>
		
		<hr>

		<br/>

		<?php 
		if ( isset( $settings['group_chat_hide_time'] ) ) {
			$hide_time = $settings['group_chat_hide_time'];
		} else {
			$hide_time = 2592000;
		}
		?>

		<?php echo sanitize_text_field( __( 'Hide Chat messages older than ...', 'bp-group-chatroom' ) ); ?>
		
		<select name="bpgc_hide_time" id="bpgc_hide_time" value="<?php echo $hide_time; ?>>
		
		<?php foreach ( $delete_time_option_array as $key => $option ) : ?>
		
			<option  value="<?php echo $key; ?>" <?php if ( $key == $hide_time ) echo 'selected="selected"'; ?>> <?php echo $option ?></option>
		
		<?php endforeach; ?>
		
		</select>
		
		<hr>

		<br/>
		
		<?php 
		if ( isset( $settings['delete_enabled'] ) && $settings['delete_enabled'] != 1 ) {
			$delete_time = $settings['delete_enabled'];
		} else {
			$delete_time = 2592000;
		}
		?>

		<?php echo sanitize_text_field( __( 'Delete Chat messages older than ...', 'bp-group-chatroom' ) ); ?>
		
		<select name="bpgc_deletion_time" id="bpgc_deletion_time" value="<?php echo $delete_time; ?>>
		
		<?php foreach ( $delete_time_option_array as $key => $option ) : ?>
		
			<option  value="<?php echo $key; ?>" <?php if ( $key == $delete_time ) echo 'selected="selected"'; ?>> <?php echo $option ?></option>
		
		<?php endforeach; ?>
		
		</select>
		
		<hr>

		<input type="checkbox" name="bp_group_chat_activity_enabled" id="bp_group_chat_activity_enabled" value="1"  
			<?php 
			if ( isset( $settings['activity_enabled'] ) && $settings['activity_enabled'] == '1' ) {
				echo 'checked="checked"';
			}
			?>
		/>
		<?php echo sanitize_text_field( __( 'Save chat threads to group activity', 'bp-group-chatroom' ) ); ?>


		<hr>

		<br/>

		<input type="checkbox" name="bp_group_chat_extra_emojis" id="bp_group_chat_extra_emojis" value="1"  
			<?php 
			if ( isset( $settings['bp_group_chat_extra_emojis'] ) && $settings['bp_group_chat_extra_emojis'] == '1' ) {
				echo 'checked="checked"';
			}
			?>
		/>
		<?php echo sanitize_text_field( __( 'Enable full set of Emojis (longer initial page load)', 'bp-group-chatroom' ) ); ?>


		<hr>

		<br/>

		<?php 
		$my_chat_color = '#ffffff';
		if ( isset( $settings['my_chat_color'] ) ) {
			$my_chat_color = $settings['my_chat_color'];
		}
		?>

		<?php echo sanitize_text_field( __( 'Background color of "my" chat messages', 'bp-group-chatroom' ) ); ?>
		
		<input class="color-picker fallback" style="width:45%;" type="text" data-show-opacity="true" data-palette="true" data-default-color="#ffffff" id="bpgc_my_chat_color" name="bpgc_my_chat_color" value="<?php echo $my_chat_color; ?>" />
		
		<hr>

		<?php
		$your_chat_color = '#ffffff';
		if ( isset( $settings['your_chat_color'] ) ) {
			$your_chat_color = $settings['your_chat_color'];
		}
		?>

		<?php echo sanitize_text_field( __( 'Background color of "your" chat messages', 'bp-group-chatroom' ) ); ?>
		
		<input class="color-picker fallback" style="width:45%;" type="text" data-show-opacity="true" data-palette="true" data-default-color="#ffffff" id="bpgc_your_chat_color" name="bpgc_your_chat_color" value="<?php echo $your_chat_color; ?>" />
		
		<hr>

		<input type="submit" name="save" value="Save" />
		<?php
	}

	function edit_screen_save( $group_id = null ) {
		global $bp;

		if ( sanitize_text_field( $_POST['save'] == null ) )
			return false;

		if ( ! $group_id ) {
			$group_id = $bp->groups->current_group->id;
		}

		check_admin_referer( 'groups_edit_save_' . $this->slug );
		$settings = array();
		if ( sanitize_text_field( $_POST['bp_group_chat_enabled'] ) == 1 ) {
			$settings['enabled'] = 1;
		} else {
			$settings['enabled'] = 0;
		}
		
		$settings['delete_enabled'] = sanitize_text_field( $_POST['bpgc_deletion_time'] );
		$settings['group_chat_hide_time'] = sanitize_text_field( $_POST['bpgc_hide_time'] );

		if ( isset( $_POST['bp_group_chat_activity_enabled'] ) && sanitize_text_field( $_POST['bp_group_chat_activity_enabled'] ) == 1 ) {
			$settings['activity_enabled'] = 1;
		} else {
			$settings['activity_enabled'] = 0;
		}

		if ( isset( $_POST['bp_group_chat_extra_emojis'] ) && sanitize_text_field( $_POST['bp_group_chat_extra_emojis'] ) == 1 ) {
			$settings['bp_group_chat_extra_emojis'] = 1;
		} else {
			$settings['bp_group_chat_extra_emojis'] = 0;
		}
		
		$settings['my_chat_color'] = sanitize_text_field( __( $_POST['bpgc_my_chat_color'] ) );
		
		$settings['your_chat_color'] = sanitize_text_field( __( $_POST['bpgc_your_chat_color'] ) );
		
		groups_update_groupmeta( $group_id, 'bp_group_chat_enabled', $settings );

		bp_core_add_message( __( 'Settings saved successfully', 'buddypress' ) );
		
		bp_core_redirect( bp_get_group_permalink( $bp->groups->current_group ) . 'admin/' . $this->slug );

	}

	function display( $group_id = null ) {
		global $bp;
		
		if ( ! $group_id ) {
			$group_id = $bp->groups->current_group->id;
		}

		if ( groups_is_user_member( $bp->loggedin_user->id, $group_id )
			 || groups_is_user_mod( $bp->loggedin_user->id, $group_id ) 
			 || groups_is_user_admin( $bp->loggedin_user->id, $group_id )
			 || is_super_admin() ) {
			
			$chat_display = false;
			$settings = groups_get_groupmeta( $group_id, 'bp_group_chat_enabled' );
			if ( isset( $settings['enabled'] ) && $settings['enabled'] == 1 ) {
				$chat_display = true;
			}
			require( dirname( __FILE__ ) . '/bp-group-chatroom-display.php' );
		} else {
			echo '<div id="message" class="error"><p>This content is only available to group members.</p></div>';
		}
	}

	function widget_display() { 
		// Not used
	}
}
if ( bp_is_active( 'groups' ) ) {
	bp_register_group_extension( 'BP_Group_Chat' );
}
?>