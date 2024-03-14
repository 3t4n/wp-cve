<?php
/**
 * Page: Item
 *
 * @var array  $tabs
 * @var string $current_tab
 * @package EverAccouting
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wrap eaccounting ea-items">
	<nav class="nav-tab-wrapper ea-nav-tab-wrapper">
		<?php
		foreach ( $tabs as $name => $label ) {
			echo '<a href="' . esc_url( admin_url( 'admin.php?page=ea-items&tab=' . $name ) ) . '" class="nav-tab ';
			if ( $current_tab === $name ) {
				echo 'nav-tab-active';
			}
			echo '">' . esc_html( $label ) . '</a>';
		}
		?>
	</nav>
	<h1 class="screen-reader-text"><?php echo esc_html( $tabs[ $current_tab ] ); ?></h1>
	<div class="ea-admin-page">
		<?php do_action( 'eaccounting_items_page_tab_' . $current_tab ); ?>
	</div>
</div>
