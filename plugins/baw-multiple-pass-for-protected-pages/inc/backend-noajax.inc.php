<?php
if( !defined( 'ABSPATH' ) )
	die( 'Cheatin\' uh?' );

add_filter( 'plugin_row_meta', 'bawmpp_plugin_row_meta', 10, 2 );
function bawmpp_plugin_row_meta( $plugin_meta, $plugin_file )
{
	if( plugin_basename( BAWMPP__FILE__ ) == $plugin_file ):
		$plugin_meta[] = '<a href="http://wordpress.org/support/plugin/' . BAWMPP_SLUG . '/" target="_blank">' . __( 'Support Forum', 'bawmpp' ) .'</a>';
		$plugin_meta[] = '<a href="http://wordpress.org/extend/plugins/' . BAWMPP_SLUG . '/" target="_blank">' . __( 'Rate the Plugin', 'bawmpp' ) .'</a>';
		$plugin_meta[] = '<a href="http://wordpress.org/support/view/plugin-reviews/' . BAWMPP_SLUG . '/" target="_blank">' . __( 'Write a Review', 'bawmpp' ) .'</a>';
	endif;
	return $plugin_meta;
}

add_filter( 'plugin_action_links_' . plugin_basename( BAWMPP__FILE__ ), 'bawmpp_settings_action_links' );
function bawmpp_settings_action_links( $links )
{
	array_unshift( $links, '<a href="' . admin_url( 'admin.php?page=bawmpp_settings' ) . '">' . __( 'Settings' ) . '</a>' );
	return $links;
}

add_action( 'admin_menu', 'bawmpp_create_menu' );
function bawmpp_create_menu()
{
	add_options_page( BAWMPP_FULLNAME, BAWMPP_FULLNAME, 'manage_options', 'bawmpp_settings', 'bawmpp_settings_page' );
	register_setting( 'bawmpp_settings', 'bawmpp' );
}

function bawmpp_field_checkbox( $args )
{
	extract( $args );
	global $bawmpp_options;
?>
	<label for="<?php echo $name; ?>"><input type="checkbox" id="<?php echo $name; ?>" name="bawmpp[<?php echo $name; ?>]" <?php checked( $bawmpp_options[$name], 'on' ); ?> /> <em><?php echo $description; ?></em></label>
<?php
}

function bawmpp_settings_page()
{
	add_settings_section( 'bawmpp_settings_page', __( 'General', 'bawmpp' ), '__return_false', 'bawmpp_settings' );
		add_settings_field( 'bawmpp_field_no_admin', __( 'Do not ask for a password for administrators', 'bawmpp' ), 'bawmpp_field_checkbox', 'bawmpp_settings', 'bawmpp_settings_page', array( 'name'=>'no_admin', 'description'=>__( 'Administrators does not need the password anymore.', 'bawmpp' ) ) );
		add_settings_field( 'bawmpp_field_no_author', __( 'Do not ask for a password for post\'s authors', 'bawmpp' ), 'bawmpp_field_checkbox', 'bawmpp_settings', 'bawmpp_settings_page', array( 'name'=>'no_author', 'description'=>__( 'Posts authors does not need the password anymore.', 'bawmpp' ) ) );
		add_settings_field( 'bawmpp_field_no_member', __( 'Do not ask for a password for any other logged member', 'bawmpp' ), 'bawmpp_field_checkbox', 'bawmpp_settings', 'bawmpp_settings_page', array( 'name'=>'no_member', 'description'=>__( 'Logged members does not need the password anymore.', 'bawmpp' ) ) );
		add_settings_field( 'bawmpp_field_clone_pass', __( 'Do not duplicate main password throught the post hierarchy, from parent to children', 'bawmpp' ), 'bawmpp_field_checkbox', 'bawmpp_settings', 'bawmpp_settings_page', array( 'name'=>'clone_pass', 'description'=>__( 'Check me if you do not want that when adding or updating a main password, all parent and child pages/posts are updated with this password.', 'bawmpp' ) ) );
	add_settings_section( 'bawmpp_settings_page', __( 'About', 'bawmpp' ), '__return_false', 'bawmpp_settings_about' );
		add_settings_field( 'bawmpp_field_about', '', create_function( '', "include( dirname( BAWMPP__FILE__ ) . '/inc/about.inc.php' );" ), 'bawmpp_settings_about', 'bawmpp_settings_page' );
?>
	<div class="wrap">
		<div id="icon-bawmpp" class="icon32" style="background: url(<?php echo BAWMPP_PLUGIN_URL; ?>img/icon32.png) 0 0 no-repeat;"><br/></div> 
		<h2><?php echo BAWMPP_FULLNAME; ?> <small>v<?php echo BAWMPP_VERSION; ?></small></h2>

		<form action="options.php" method="post">
			<?php settings_fields( 'bawmpp_settings' ); ?>
			<?php submit_button(); ?>
			<?php do_settings_sections( 'bawmpp_settings' ); ?>
			<?php submit_button(); ?>
			<?php do_settings_sections( 'bawmpp_settings_about' ); ?>
		</form>
	</div>
<?php
}

add_action( 'post_submitbox_misc_actions', 'bawmpp_post_submitbox_misc_actions' );
function bawmpp_post_submitbox_misc_actions()
{ 
	global $post;
	$post_type = $post->post_type;
	$post_type_object = get_post_type_object( $post_type );
	if( !current_user_can( $post_type_object->cap->publish_posts ) )
		return;
?>
<div class="misc-pub-section" id="multiple_password">
	<label for="multiple-password"><?php _e( 'Multiple passwords:', 'bawmpp' ); ?></label>
	<?php
	wp_nonce_field( 'add-multiplepasswords_' . $post->ID, '_wpnonce_bawmpp' );
	$nbLines = 1;
	$pass = get_post_meta( $post->ID, '_morepasswords', true );
		if( !empty( $pass ) && is_array( $pass ) ):
			$nbLines = min( count( $pass ), 6 );
			$pass = esc_textarea( implode( "\n", $pass ) );
		else:
			$pass = '';
		endif;
	?>
	<textarea rows="<?php echo $nbLines; ?>" id="multiple-password" class="more-passwords" cols="40" name="multiple_passwords" placeholder="<?php _e( '1 password per line', 'bawmpp' ); ?>"><?php echo $pass; ?></textarea>
</div>
<?php 
}

add_action( 'admin_print_styles-post.php', 'bawmpp_admin_print_styles' );
add_action( 'admin_print_styles-post-new.php', 'bawmpp_admin_print_styles' );
function bawmpp_admin_print_styles()
{ ?>
<style>
	::-webkit-input-placeholder { color:#9c9c9c !important; font-style:italic !important; }
	:-moz-placeholder { color:#9c9c9c !important; font-style:italic !important; }
</style>
<?php
}

add_action( 'admin_footer-post.php', 'bawmpp_admin_print_scripts' );
add_action( 'admin_footer-post-new.php', 'bawmpp_admin_print_scripts' );
function bawmpp_admin_print_scripts()
{ ?>
<script>
	jQuery(document).ready(function(){
		jQuery( '#password-span' ).append( jQuery( '#multiple_password *' ) );
		jQuery( '#multiple_password' ).remove();
	});
</script>
<?php
}

function bawmpp_get_top_most_parent( $post_id )
{
	$parent_id = get_post( $post_id )->post_parent;
	return $parent_id==0 ? $post_id : bawmpp_get_top_most_parent( $parent_id );
}

function bawmpp_get_all_children( $post_id )
{
	$post_id = bawmpp_get_top_most_parent( $post_id );
	$ids = array( $post_id );
	foreach( bawmpp_get_child_ids( $post_id ) as $id ):
		$ids[] = $id;
		$post_types = implode( '","', get_post_types( array( 'public'=>true, 'show_ui'=>true ), 'names' ) );
		$ids = array_merge( bawmpp_get_child_ids( $id, $post_types ), $ids );
	endforeach;
	return $ids;
}

function bawmpp_get_child_ids( $post_id )
{
	global $wpdb;
	$post_types = implode( '","', get_post_types( array( 'public'=>true, 'show_ui'=>true ), 'names' ) );
	$children = $wpdb->get_col( 'SELECT ID FROM ' . $wpdb->posts . ' WHERE post_status = "publish" AND post_type IN ("' . $post_types . '") AND post_parent = ' . (int)$post_id );
	return $children;
}

add_action( 'save_post', 'bawmpp_update_password' );
function bawmpp_update_password( $post_id )
{
	global $wpdb, $bawmpp_options;
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return $post_id;

	if( isset( $_POST['post_ID'] ) )
		$a_ids = array_filter( array_map( 'intval', bawmpp_get_all_children( $_POST['post_ID'] ) ) );

	if( isset( $_POST['multiple_passwords'], $_POST['post_ID'], $_POST['post_password'] ) ){
		check_admin_referer( 'add-multiplepasswords_' . $_POST['post_ID'], '_wpnonce_bawmpp' );
		if( $bawmpp_options['clone_pass']!='on' ){
			$ids = implode( ',', $a_ids );
			$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->posts . ' SET post_password = %s WHERE ID in ( ' . $ids . ' )', $_POST['post_password'] ) );
			$multiple_passwords = array_filter( array_map( 'trim', explode( "\n", $_POST['multiple_passwords'] ) ) );
			foreach( $a_ids as $post_id )
				update_post_meta( $post_id, '_morepasswords', $multiple_passwords );
		}
	}elseif( isset( $_POST['post_ID'], $_POST['post_password'] ) ){
		if( defined( 'DOING_AJAX' ) && DOING_AJAX )
			check_admin_referer( 'inlineeditnonce', '_inline_edit' );
		$_POST['hidden_post_password'] = isset( $_POST['hidden_post_password'] ) ? $_POST['hidden_post_password']  : '';
		if( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( $bawmpp_options['clone_pass']!='on' && $_POST['post_password']!=$_POST['hidden_post_password'] ) ){
			$ids = implode( ',', array_filter( array_map( 'intval', bawmpp_get_all_children( $_POST['post_ID'] ) ) ) );
			$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->posts . ' SET post_password = %s WHERE ID in ( ' . $ids . ' )', $_POST['post_password'] ) );
			$multiple_passwords = get_post_meta( $post_id, '_morepasswords', true );
			foreach( $a_ids as $post_id )
				update_post_meta( $post_id, '_morepasswords', $multiple_passwords );
		}
	}
}
