<?php
/**
 * Unregistered user for widget
 */

$dashboard = fed_get_dashboard_url();

$dashboard = false === $dashboard ? get_dashboard_url() : $dashboard;

$dashboard_title = apply_filters( 'fed_frontend_dashboard_title_btn',
	__( 'Visit Dashboard', 'frontend-dashboard-templates' ) );

?>
<div class="bc_fed">
	<a href="<?php echo esc_url( $dashboard ); ?>">
		<button class="btn btn-primary">
			<?php
			esc_attr_e( $dashboard_title, 'frontend-dashboard-templates' );
			?>
		</button>
	</a>
</div>

