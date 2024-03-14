<?php
/**
 * Header
 * Main plugin header
 * php version 7.2
 *
 * @category   Plugin
 * @package    Wp_Custom_Cursors
 * @subpackage Wp_Custom_Cursors/includes
 * @author     Hamid Reza Sepehr <hamidsepehr4@gmail.com>
 * @license    GPLv2 or later (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @link       https://hamidrezasepehr.com/
 * @since      2.1.0
 */

?>

<!-- Header -->
<div class="wt-header bg-white rounded d-flex justify-content-between align-items-center">
	<div class="d-flex align-items-center">
		<div class="wt-logo mr-3">
			<img src="<?php echo esc_url( plugins_url( '../img/thumbnail.svg', __FILE__ ) ); ?>" alt="<?php echo esc_html__( 'WP Custom Cursors', 'wpcustom-cursors' ); ?>" title="<?php echo esc_html__( 'WP Custom Cursors', 'wpcustom-cursors' ); ?>" />
		</div>
		<div class="wt-header-title ms-3">
			<h2 class="h5 d-inline-block"><?php echo esc_html__( 'WP Custom Cursors ', 'wpcustom-cursors' ); ?> </h2>  <span class="badge rounded-pill bg-warning text-dark"><?php echo esc_html( WP_CUSTOM_CURSORS_VERSION ); ?></span>
			<p class="mb-0">
				<a href="<?php echo esc_url( 'https://codecanyon.net/item/wp-custom-cursors/24442004' ); ?>" class="text-muted text-decoration-none wt-link" target="_blank" title="<?php echo esc_html__( 'Purchase Premium', 'wpcustom-cursors' ); ?>"><i class="ri-links-fill"></i> <?php echo esc_html__( 'Purchase Premium', 'wpcustom-cursors' ); ?>
				</a>
			</p>
		</div>
	</div>
	<div class="navigation d-flex align-items-center">
	<?php
	$screen  = get_current_screen();
	$page_id = $screen->id;
	switch ( $page_id ) {
		case 'toplevel_page_wp_custom_cursors':
			?>
				<a href="<?php menu_page_url( 'wpcc_add_new', true ); ?>" class="text-decoration-none link-dark d-inline-flex align-items-center"><?php echo esc_html__( 'Add Cursor', 'wpcustom-cursors' ); ?> <i class="nav-icon ms-2 ri-arrow-right-s-line"></i></a>
			<?php
			break;
		case 'custom-cursor_page_wpcc_add_new':
			?>
				<a href="<?php menu_page_url( 'wp_custom_cursors', true ); ?>" class="text-decoration-none link-dark d-inline-flex align-items-center"><?php echo esc_html__( 'Home', 'wpcustom-cursors' ); ?> <i class="nav-icon ms-2 ri-arrow-left-s-line"></i></a>
			<?php
			break;
		case 'custom-cursor_page_wpcc_cursor_maker':
			?>
				<a href="<?php menu_page_url( 'wp_custom_cursors', true ); ?>" class="text-decoration-none link-dark"><?php echo esc_html__( 'Home', 'wpcustom-cursors' ); ?> </a> <i class="ri-arrow-right-s-fill"></i> <a href="<?php menu_page_url( 'wpcc_add_new', true ); ?>" class="text-decoration-none link-dark d-inline-flex align-items-center"><?php echo esc_html__( 'Add Cursor', 'wpcustom-cursors' ); ?>  <i class="nav-icon ms-2 ri-arrow-right-s-line"></i></a>
			<?php
			break;
	}
	?>
	</div>
</div>
<!-- End Header -->