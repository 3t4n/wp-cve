<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the add-ons listing related to this plugin.
 *
 * @link       https://www.madebytribe.com
 * @since      1.0.0
 *
 * @package    Caddy
 * @subpackage Caddy/admin/partials
 */

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
}

$addon_tab = ( ! empty( $_GET['tab'] ) ) ? esc_attr( $_GET['tab'] ) : 'addons';

$addon_tabs = array(
	'addons' => array(
		'tab_name' => __( 'Premium Add-ons', 'caddy' ),
		'tab_icon' => 'dashicons dashicons-admin-plugins',
	),
);
/**
 * Filters the add-on tab names.
 *
 * @param array $addon_tabs Caddy tab names.
 *
 * @since 1.4.0
 *
 */
$addon_tab_name = apply_filters( 'caddy_add_on_tab_names', $addon_tabs );

$addon_sections       = array(
	'' => __( 'Add-ons', 'caddy' ),
);
$caddy_addon_sections = apply_filters( 'caddy_get_addons_sections', $addon_sections );

?>

<div class="wrap">
	<div class="cc-header-wrap">
		<img src="<?php echo plugin_dir_url( __DIR__ ) ?>img/caddy-logo.svg" width="110" height="32" class="cc-logo">
		<div class="cc-version"><?php echo CADDY_VERSION; ?></div>
		<?php do_action( 'caddy_header_links' ); ?>
	</div>
	<h2 class="nav-tab-wrapper">
		<?php
		foreach ( $addon_tab_name as $key => $value ) {
			$active_tab_class = ( $key == $addon_tab ) ? ' nav-tab-active' : '';
			?>
			<a class="nav-tab<?php echo $active_tab_class; ?>" href="?page=caddy-addons&amp;tab=<?php echo $key; ?>"><i class="<?php echo $value['tab_icon']; ?>"></i>&nbsp;<?php
				echo $value['tab_name']; ?></a>
		<?php } ?>
	</h2>
	<?php do_action( 'cc_addons_html' ); // Display add-ons html ?>
</div>
