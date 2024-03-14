<?php
/**
 * It includes the code for every page of the backend.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

add_filter( 'plugin_row_meta', 'eos_dp_plugin_row_meta', 20, 2 );
// Add links to the plugins page.
function eos_dp_plugin_row_meta( $links, $file ) {
	if ( EOS_DP_PLUGIN_BASE_NAME === $file ) {
		$lang = function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
		if ( false === strpos( $lang, 'en_' ) ) {
			$path               = 'https://translate.wordpress.org/projects/wp-plugins/freesoul-deactivate-plugins/';
			$links['translate'] = '<a href="' . $path . '" target="_blank" aria-label="' . esc_attr__( 'Translate', 'freesoul-deactivate-plugins' ) . '">' . esc_html__( 'Translate', 'freesoul-deactivate-plugins' ) . '</a>';
		}
	}
	return $links;
}

// It adds the plugin setting page under plugins menu.
add_action( 'admin_menu', 'eos_dp_options_page', 999 );

// Check if it's a major release and return the upgrade notice.
add_action( 'in_plugin_update_message-freesoul-deactivate-plugins/freesoul-deactivate-plugins.php', 'eos_dp_get_update_notice' );

add_action( 'admin_footer', 'eos_dp_add_admin_inline_script' );
// Add admin inline script.
function eos_dp_add_admin_inline_script() {
	?>
	<script id="fdp-admin-base">
	var fdp_close_menu = document.getElementById('wp-admin-bar-fdp-close-admin-menu');
	if(fdp_close_menu ){
		fdp_close_menu.addEventListener('click',function(){
			var fdp_admin_menu = document.getElementById('wp-admin-bar-eos-dp-menu');
			fdp_admin_menu.className = fdp_admin_menu.className.replace(' hover','').replace('hover','');
		});
	}
	function fdp_correct_fdp_admin_menu_links(){
		var fdp_links=document.getElementById("toplevel_page_eos_dp_menu").getElementsByTagName("a"),k=0;
		if(fdp_links && fdp_links.length > 0){
			for(k;k<fdp_links.length;++k){
				fdp_links[k].addEventListener("click",function(){
					if(this.href==="<?php echo esc_url ( FDP_STORE_URL ); ?>")this.target="_fdp_store";
				});
			}
		}
	}
	fdp_correct_fdp_admin_menu_links();	
	</script>
	<?php
}

add_action( 'wp_trash_post', 'eos_dp_on_post_trashing' );
add_action( 'untrash_post', 'eos_dp_on_post_untrashing' );
add_action( 'transition_post_status', 'eos_dp_on_post_status_transition', 10, 3 );
// Fire when a post is trasheed.
function eos_dp_on_post_trashing( $post_id ) {
	eos_dp_on_post_transition_status( $post_id, 'trash' );
}

// Fire when a post is untrasheed.
function eos_dp_on_post_untrashing( $post_id ) {
	eos_dp_on_post_transition_status( $post_id, 'untrash' );
}

// Fire when a post status transition.
function eos_dp_on_post_status_transition( $new_status, $old_status, $post ) {
	eos_dp_on_post_transition_status( $post->ID, $old_status . '_' . $new_status );
}

// Fire when a post is trasheed or untrashed.
function eos_dp_on_post_transition_status( $post_id, $action ) {
	$post = get_post( $post_id );
	$post_status = $post->post_status;
	$post->post_status = 'publish';
	$upload_dirs = wp_upload_dir();
	$permalink = get_the_permalink( $post );
	$path  = esc_attr( str_replace( get_option( 'home' ), '', $permalink ) );
	$path  = ltrim( rtrim( $path, '/' ), '/' );
	$parts = explode( '/', $path );
	$path  = $upload_dirs['basedir'] . '/FDP/fdp-single-options';
	foreach ( $parts as $part ) {
		$path .= function_exists( 'eos_dp_sanitize_file_name' ) ? '/' . substr( md5( eos_dp_sanitize_file_name( $part ) ), 0, 8 ) : '/' . substr( md5( sanitize_file_name( $part ) ), 0, 8 );
	}
	$path .= '/opts.json';
	$trashed_path = str_replace( 'opts.json', 'opts-trashed.json', $path );
	if( 'trash' === $action ) {
		// Transition from untrashed to trashed.
		if( file_exists( $path ) ) {
			rename( $path, $trashed_path );
		}
		$post_meta = get_post_meta( $post_id,'_eos_deactive_plugins_key',true );
		if( $post_meta ) update_post_meta( $post_id,'_eos_deactive_plugins_key_trashed', sanitize_text_field( $post_meta ) );
		delete_post_meta( $post_id,'_eos_deactive_plugins_key' );
	}
	elseif( 'untrash' === $action ) {
		// Transition from trashed to untrashed.
		if( file_exists( $trashed_path ) ) {
			rename( $trashed_path, $path );
		}
		$post_meta = get_post_meta( $post_id,'_eos_deactive_plugins_key_trashed',true );
		if( $post_meta ) update_post_meta( $post_id,'_eos_deactive_plugins_key', sanitize_text_field( $post_meta ) );
		delete_post_meta( $post_id,'_eos_deactive_plugins_key_trashed' );		
	}
	elseif( in_array( $action, array( 'publish_draft' ) ) ) {
		// Transition from public to draft.
	}
	elseif( in_array( $action, array( 'draft_publish' ) ) ) {
		// Transition from draft to public.
	}
}
