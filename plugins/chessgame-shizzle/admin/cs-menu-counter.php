<?php
/*
 * Menu counter for waiting/pending chessgames.
 */


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Menu counter for waiting/pending chessgames.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_menu_counter() {
	$query = new WP_Query(
		array(
			'post_status'            => 'pending',
			'post_type'              => 'cs_chessgame',
			'posts_per_page'         => -1,
			'nopaging'               => true,
			'fields'                 => 'ids',
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
		)
	);
	if ( ! empty( $query->post_count ) ) {
		$count = $query->post_count;
		?>
		<script>
		jQuery( document ).ready( function( $ ) {
			var string = "<span class='update-plugins count-<?php echo (int) $count; ?>'><span class='theme-count'><?php echo (int) $count; ?></span></span>";
			jQuery( 'a.menu-icon-cs_chessgame div.wp-menu-name' ).append( string );
		});
		</script>
		<?php
	}
}
add_action( 'admin_footer', 'chessgame_shizzle_menu_counter' );
