<?php

add_action( 'admin_menu', 'fhw_dsgvo_kommentar_adminmenu' );

function fhw_dsgvo_kommentar_adminmenu() {
	global $_wp_last_object_menu;
	$_wp_last_object_menu++;
	add_submenu_page( 'edit-comments.php', __( 'GDPR tools: comment ip', 'dsgvo-tools-kommentar-ip-entfernen' ), __( 'GDPR tools: comment ip', 'dsgvo-tools-kommentar-ip-entfernen' ), 'administrator', 'fhw_dsgvo_kommentar_options', 'fhw_dsgvo_kommentar_options_seite' );
}

add_action( 'admin_init', 'fhw_dsgvo_kommmentar_options' );
function fhw_dsgvo_kommmentar_options() {
	register_setting( 'fhw_dsgvo_kommentar_options_group1', 'fhw_dsgvo_kommentar_time_removement' );
	register_setting( 'fhw_dsgvo_kommentar_options_group1', 'fhw_dsgvo_kommentar_removement_time' );
}
	
function fhw_dsgvo_kommentar_options_seite() {
	global $wpdb;
	if( !is_admin() ) {
		wp_die( _e( 'No permissions', 'dsgvo-tools-kommentar-ip-entfernen' ) );
	}	
?>
	<style>
		input[type=submit] { 
			padding: 15px; 
			outline: none;
			border: 1px solid silver;
            cursor: pointer;
		}
		
		form {
			border-bottom: 1px solid silver;
			width: 33.33%;
			padding: 1%;
		}
		
		label { display: block; }
	</style>
	<script>
		var ready = ( callback ) => {if ( document.readyState != "loading" )callback();else document.addEventListener( "DOMContentLoaded", callback );}
		ready( () => {toggle();document.querySelector( 'input[type=checkbox]' ).addEventListener( 'change', toggle);});
		function toggle() {if( document.querySelector( 'input[type=checkbox]:checked' ) == null )document.querySelector( 'input[name=fhw_dsgvo_kommentar_removement_time]' ).disabled = true;else document.querySelector( 'input[name=fhw_dsgvo_kommentar_removement_time]' ).disabled = false;}
	</script>
	<div class="wrap">
	<?php
		if( isset( $_POST[ 'fhw_dsgvo_kommentar_removeIP' ] ) && $_POST[ 'fhw_dsgvo_kommentar_removeIP' ] && check_admin_referer( 'fhw_dsgvo_kommentar_nonce', 'nonce1' ) && current_user_can( 'administrator' ) ) {
			$testt = $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->comments SET comment_author_IP = %s", null ) );
			if( !$testt ) { ?>
				<div class="notice notice-error is-dismissible"><p style="color: black; height: 60px;">
					<?php _e( 'error in database query!', 'dsgvo-tools-kommentar-ip-entfernen' ); ?>
				</p></div>
			<?php 
			} else { ?>
				<div class="notice notice-success is-dismissible"><p style="color: black; height: 60px;">
				<?php _e( 'All existing ip adresses has been deleted from database!', 'dsgvo-tools-kommentar-ip-entfernen' ); ?>
				</p></div>
			<?php
			}
		}
	?>
		<h1><?php _e( 'GDPR tools: comment ip removement', 'dsgvo-tools-kommentar-ip-entfernen' ); ?></h1>
		<form method="post" action="">
		<?php $savedIPs = $wpdb->get_var( "SELECT COUNT(comment_author_IP) FROM $wpdb->comments WHERE comment_author_IP != ''" ); ?>
					<p><?php echo _e( 'Stored ip addresses', 'dsgvo-tools-kommentar-ip-entfernen' ) . ': ' . $savedIPs; ?></p>
					<?php wp_nonce_field( 'fhw_dsgvo_kommentar_nonce', 'nonce1' ); ?>
				<input type="submit" name="fhw_dsgvo_kommentar_removeIP" value="<?php _e( 'Delete all ip adresses!', 'dsgvo-tools-kommentar-ip-entfernen' ); ?>" <?php if( $savedIPs == 0 ) echo "disabled"; ?> />
		</form>
		<form method="post" action="options.php">
			<?php settings_fields( 'fhw_dsgvo_kommentar_options_group1' ); ?>
			<?php do_settings_sections( 'fhw_dsgvo_kommentar_options_group1' ); ?>
			<?php echo checked( 1, get_option( 'fhw_dsgvo_kommentar_time_removement' ) ); ?>
			<label><input type="checkbox" name="fhw_dsgvo_kommentar_time_removement" <?php if( 'on' == get_option( 'fhw_dsgvo_kommentar_time_removement' ) ) echo "checked"; ?> /><?php _e( 'Delete comment ip addresses after time', 'dsgvo-tools-kommentar-ip-entfernen' ); ?></label>
			<label><?php _e( 'Delete after', 'dsgvo-tools-kommentar-ip-entfernen' ); ?> <input type="number" name="fhw_dsgvo_kommentar_removement_time" value="<?php echo get_option( 'fhw_dsgvo_kommentar_removement_time', 180 ); ?>" /> <?php _e( 'Days', 'dsgvo-tools-kommentar-ip-entfernen' ); ?></label>
			<?php submit_button(); ?>
		</form>
	</div>
<?php
}
	