<?php
/**
 * The file used to display the "Pro Version" menu in the admin area.
 *
 * @package hreflang-manager-lite
 */

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'hreflang-manager-lite' ) );
}

?>

<!-- output -->

<div class="wrap">

	<h2><?php esc_html_e( 'Hreflang Manager - Pro Version', 'hreflang-manager-lite' ); ?></h2>

	<div id="daext-menu-wrapper">

		<p><?php echo esc_html__( 'For professional users, we distribute a', 'hreflang-manager-lite' ) . ' <a href="https://daext.com/hreflang-manager/">' . esc_html__( 'Pro Version', 'hreflang-manager-lite' ) . '</a> ' . esc_attr__( 'of this plugin.', 'hreflang-manager-lite' ) . '</p>'; ?>
		<h2><?php esc_html_e( 'Additional Features Included in the Pro Version', 'hreflang-manager-lite' ); ?></h2>
		<ul>
			<li><?php esc_html_e( 'Sync the hreflang data between different websites', 'hreflang-manager-lite' ); ?></li>
			<li><?php echo '<strong>' . esc_html__( 'Import', 'hreflang-manager-lite' ) . '</strong> ' . esc_html__( 'and', 'hreflang-manager-lite' ) . ' <strong>' . esc_html__( 'Export', 'hreflang-manager-lite' ) . '</strong> ' . esc_html__( 'menus to instantly move the implementation of hreflang in all the websites of the network', 'hreflang-manager-lite' ); ?></li>
			<li><?php esc_html_e( 'A maximum of 100 alternative versions of the page per connection', 'hreflang-manager-lite' ); ?></li>
			<li><?php echo '<strong>' . esc_html__( 'Wizard', 'hreflang-manager-lite' ) . '</strong> ' . esc_html__( 'menu menu to mass import hreflang data from a spreadsheet', 'hreflang-manager-lite' ); ?></li>
			<li><?php echo '<strong>' . esc_html__( 'Maintenance', 'hreflang-manager-lite' ) . '</strong> ' . esc_html__( 'menu to perform bulk operations on the plugin data', 'hreflang-manager-lite' ); ?></li>
			<li><?php echo esc_html__( 'The', 'hreflang-manager-lite' ) . ' <strong>' . esc_html__( 'Hreflang Manager', 'hreflang-manager-lite' ) . '</strong> ' . esc_html__( 'meta-box to edit hreflang in the post editor', 'hreflang-manager-lite' ); ?></li>
			<li><?php echo esc_html__( 'The', 'hreflang-manager-lite' ) . ' <strong>' . esc_html__( 'Hreflang Manager', 'hreflang-manager-lite' ) . '</strong> ' . esc_html__( 'section in the sidebar of the Gutenberg editor to edit hreflang in the post editor', 'hreflang-manager-lite' ); ?></li>
			<li><?php esc_html_e( 'Additional options to set custom menu capabilities for all the plugin menus', 'hreflang-manager-lite' ); ?></li>
			<li><?php esc_html_e( 'Additional options to customize the pagination system of the plugin', 'hreflang-manager-lite' ); ?></li>
			<li><?php esc_html_e( 'Other additional advanced options', 'hreflang-manager-lite' ); ?></li>
		</ul>
		<h2><?php esc_html_e( 'Additional Benefits of the Pro Version', 'hreflang-manager-lite' ); ?></h2>
		<ul>
			<li><?php esc_html_e( '24 hours support provided seven days a week', 'hreflang-manager-lite' ); ?></li>
			<li><?php echo esc_html__( '30 day money back guarantee (more information is available on the', 'hreflang-manager-lite' ) . ' <a href="https://daext.com/refund-policy/">' . esc_html__( 'Refund Policy', 'hreflang-manager-lite' ) . '</a> ' . esc_html__( 'page', 'hreflang-manager-lite' ) . ')'; ?></li>
		</ul>
		<h2><?php esc_html_e( 'Get Started', 'hreflang-manager-lite' ); ?></h2>
		<p><?php echo esc_html__( 'Download the', 'hreflang-manager-lite' ) . ' <a href="https://daext.com/hreflang-manager/">' . esc_html__( 'Pro Version', 'hreflang-manager-lite' ) . '</a> ' . esc_html__( 'now by selecting one of the available licenses.', 'hreflang-manager-lite' ); ?></p>
	</div>