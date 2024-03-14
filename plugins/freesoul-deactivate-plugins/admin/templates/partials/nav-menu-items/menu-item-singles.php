<?php
/**
 * Template Menu Items Singles.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! empty( $plugins_table ) ) {
	if ( 'page' === eos_dp_get_option( 'show_on_front' ) ) { ?>
  <li data-section="eos-dp-<?php echo esc_attr( $sec ); ?>" class="eos-dp-submenu-item hover eos-dp-setts-menu-item">
	<span class="dashicons dashicons-admin-home"></span>
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $page . '&eos_dp_home=true' ) ); ?>"><?php esc_html_e( 'Homepage', 'freesoul-deactivate-plugins' ); ?></a>
  </li>
		<?php
	}
	foreach ( $plugins_table as $pt  => $arr ) {
		$postTypeObj = get_post_type_object( $pt );
		if ( 'page' === $pt || ( ! in_array( $pt, array( 'attachment' ) ) && is_object( $postTypeObj ) && isset( $postTypeObj->publicly_queryable ) && $postTypeObj->publicly_queryable ) ) {
			$labels      = get_post_type_labels( $postTypeObj );
			$labels_name = isset( $labels->name ) ? $labels->name : esc_html( $pt );
			?>
	  <li class="eos-dp-submenu-item" data-post-type="<?php echo esc_attr( $pt ); ?>">
			<?php if ( in_array( $pt, array( 'page', 'post' ) ) ) { ?>
		<span class="dashicons dashicons-admin-<?php echo esc_attr( $pt ); ?>"></span>
		<?php } elseif ( 'product' === $pt ) { ?>
		<span class="dashicons dashicons-cart"></span>
		<?php } elseif ( 'event' === $pt ) { ?>
		<span class="dashicons dashicons-calendar"></span>
		<?php } ?>
		<a class="eos-dp-single-item-<?php echo esc_attr( $pt ); ?>" href="<?php echo esc_url( add_query_arg( 'eos_dp_post_type', esc_attr( $pt ), admin_url( 'admin.php?page=eos_dp_menu' ) ) ); ?>"><?php echo esc_html( $labels_name ); ?></a>
			<?php
			if ( 'page' === $pt ) {
				?>
	  <span class="dashicons dashicons-arrow-right"></span>
	  <ul class="eos-dp-sub-menu">
		<li class="eos-dp-sub-sub-menu<?php echo isset( $_GET['eos_dp_relevant_pages'] ) && 'true' === $_GET['eos_dp_relevant_pages'] ? ' eos-active' : ''; ?>"><a href="
												 <?php
													echo esc_url(
														add_query_arg(
															array(
																'eos_dp_relevant_pages' => 'true',
																'eos_dp_post_type'      => 'page',
															),
															admin_url( 'admin.php?page=eos_dp_menu' )
														)
													);
													?>
													"><?php esc_html_e( 'Relevant pages', 'freesoul-deactivate-plugins' ); ?></a></li>
	  </ul>
				<?php
			}
			?>
	  </li>
			<?php
		}
	}
}
