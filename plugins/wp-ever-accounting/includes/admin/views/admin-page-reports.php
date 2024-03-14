<?php
/**
 * Admin View: Page - Reports
 *
 * @var array $tabs
 * @var string $current_tab
 * @var array $sections
 * @var string $current_section
 * @package EverAccouting
 */

defined( 'ABSPATH' ) || exit();
?>
<style>
	.ea-card {
		margin-top: 0 !important;
	}

	.wp-list-table {
		border-bottom: 0 !important;
	}

</style>
<div class="wrap eaccounting ea-reports">
	<nav class="nav-tab-wrapper ea-nav-tab-wrapper">
		<?php
		foreach ( $tabs as $name => $label ) {
			echo '<a href="' . esc_url( admin_url( 'admin.php?page=ea-reports&tab=' . $name ) ) . '" class="nav-tab ';
			if ( $current_tab === $name ) {
				echo 'nav-tab-active';
			}
			echo '">' . esc_html( $label ) . '</a>';
		}
		?>
	</nav>
	<br class="clear"/>

	<h1 class="screen-reader-text"><?php echo esc_html( $tabs[ $current_tab ] ); ?></h1>
	<div class="ea-admin-page">
		<?php do_action( 'eaccounting_reports_tab_' . $current_tab ); ?>
	</div>
</div>
