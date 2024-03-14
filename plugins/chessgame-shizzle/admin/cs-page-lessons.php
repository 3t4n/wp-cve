<?php
/*
 * Lessons page for Chessgame Shizzle admin.
 */


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Register main lesson page with overview.
 *
 * @since 1.1.8
 */
function chessgame_shizzle_lesson_pages() {
	/*
	 * How to add new menu-entries:
	 * add_menu_page( $page_title, $menu_title, $access_level, $file, $function = '', $icon_url = '', $position = null )
	 */

	add_menu_page(
		/* translators: Menu entry */
		esc_html__('Chesslessons', 'chessgame-shizzle'),
		/* translators: Menu entry */
		esc_html__('Chesslessons', 'chessgame-shizzle'),
		'read',
		C_SHIZZLE_FOLDER . '/lessons-overview.php',
		'chessgame_shizzle_lessons_overview',
		'dashicons-screenoptions',
		26
	);

	/*
	 * How to add sub-new menu-entries:
	 * add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = '', $position = null )
	 */

	/* translators: Menu entry */
	//add_submenu_page(C_SHIZZLE_FOLDER . '/lessons-overview.php', esc_html__('Lessons', 'chessgame-shizzle'), esc_html__('Lessons', 'chessgame-shizzle'), 'read', C_SHIZZLE_FOLDER . '/cs_lessons', 'chessgame_shizzle_page_about');

}
add_action( 'admin_menu', 'chessgame_shizzle_lesson_pages' );


/*
 * Main lesson page with overview.
 *
 * @since 1.1.8
 */
function chessgame_shizzle_lessons_overview() {
	?>
	<div class='wrap'>
		<h1><?php esc_html_e('Chesslessons', 'chessgame-shizzle'); ?></h1>
		<div id="poststuff" class="metabox-holder">
			<div class="widget cs-lesson">

				<h2 class="widget-top"><?php esc_html_e('Chesslessons', 'chessgame-shizzle'); ?></h2>
				<?php
				echo chessgame_shizzle_get_lesson();
				?>

			</div>
		</div>
	</div>
<?php
}
