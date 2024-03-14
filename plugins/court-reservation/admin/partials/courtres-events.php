<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://webmuehle.at
 * @since      1.0.3
 *
 * @package    Courtres
 * @subpackage Courtres/admin/partials
 */
?>

<?php
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die();
}
if ( isset( $_REQUEST['export-expired'] ) && $_REQUEST['export-expired'] == 1 ) {
	$events = $this->get_expired( 'events' );
	$this->export_csv( $this->prepare_to_csv( $events ) );
}
if ( isset( $_REQUEST['view-expired'] ) && $_REQUEST['view-expired'] == 1 ) {
	$events = $this->get_expired( 'events' );
} else {
	$days = $this->getWeekdays();

	$tab       = ( ! empty( $_GET['tab'] ) ) ? sanitize_text_field( $_GET['tab'] ) : '0';
	$tab_where = ' and weekly_repeat = 0 ';
	if ( $tab == '1' ) {
		$tab_where = ' and weekly_repeat = 1 ';
	}

	global $wpdb;
	$events = $wpdb->get_results(
		"SELECT events.*, courts.name as courtname FROM {$this->getTable('events')} as events, 
			{$this->getTable('courts')} as courts
			WHERE courts.id=events.courtid {$tab_where} ORDER BY name"
	);
}

?>

<?php
require 'courtres-notice-upgrade.php';
?>

<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo esc_html__( 'Manage Events', 'court-reservation' ); ?></h1>
	<a class="page-title-action" href="<?php echo esc_url(admin_url( 'admin.php?page=courtres-event&tab=' . $tab )); ?>">
		<?php
		if ( $tab == '1' ) {
			?>
			<?php echo esc_html__( 'Create weekly event', 'court-reservation' ); ?>
				<?php
		} else {
			?>
			<?php echo esc_html__( 'Create individual event', 'court-reservation' ); ?>
				<?php
		}
		?>
	</a>
	<div class="cr-head-right">
		<form method="post" action="<?php echo esc_url(admin_url( 'admin-ajax.php')); ?>">
		   <?php wp_nonce_field( 'export_expired', 'export_expired_nonce' ); ?>
			<input type="hidden" name="target" value="events" />
			<input type="hidden" name="action" value="download_csv" />
		   <?php submit_button( __( 'Export Expired', 'court-reservation' ) ); ?>
		</form>
	</div>
	<div style="clear: both;"></div>
	<hr class="wp-header-end">

	<div class="cr-tabs-wrap">
		<div class="item1">
			<div class="cr-widget-right">
				<?php require 'courtres-widget-upgrade.php'; ?>
			</div>
		</div>
		<div  class="item2">
			<h2 class="nav-tab-wrapper wp-clearfix cr-nav-tab-wrapper">
				<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres-events&tab=0' )); ?>" class="nav-tab <?php echo $tab == '0' ? 'nav-tab-active' : ''; ?>">
					<?php echo esc_html__( 'Individual events', 'court-reservation' ); ?>
				</a>
				<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres-events&tab=1' )); ?>" class="nav-tab <?php echo $tab == '1' ? 'nav-tab-active' : ''; ?>">
					<?php echo esc_html__( 'Weekly events', 'court-reservation' ); ?>
				</a>
			</h2>

			<table class="wp-list-table widefat fixed striped posts">
				<thead>
					<tr>
						<th class="manage-column column-title column-primary"><?php echo esc_html__( 'Name', 'court-reservation' ); ?></th>
						<th class="manage-column column-title column-primary"><?php echo esc_html__( 'Court', 'court-reservation' ); ?></th>
						<?php
						if ( $tab == '1' ) {
							?>
								<th class="manage-column column-title column-primary">
								<?php echo esc_html__( 'Weekday', 'court-reservation' ); ?>
								</th>
								<?php
						} else {
							?>
								<th class="manage-column column-title column-primary">
								<?php echo esc_html__( 'Date', 'court-reservation' ); ?>
								</th>
								<?php
						}
						?>
						<th class="manage-column column-title column-primary"><?php echo esc_html__( 'Period', 'court-reservation' ); ?></th>
						<th class="manage-column column-title"><?php echo esc_html__( 'Action', 'court-reservation' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $events as $item ) : ?>
						<?php
							$period = $item->start . '-' . $item->end . '&nbsp;' . __( 'Hour', 'court-reservation' );
						if ( property_exists( $item, 'start_ts' ) && $item->start_ts ) {
							$period = date_i18n( 'H:i', $item->start_ts );
						}
						if ( property_exists( $item, 'end_ts' ) && $item->end_ts ) {
							$period .= ' - ' . date_i18n( 'H:i', $item->end_ts );
						}
						?>
						<tr class="<?php echo ( ( $item->weekly_repeat != 1 ) && $item->event_date < date( 'Y-m-d' ) ) ? 'event-expired' : ''; ?>">
							<td>
								<?php echo esc_html( $item->name ); ?>
							</td>
							<td><?php echo esc_html( $item->courtname ); ?></td>
							<?php
							if ( $tab == '1' ) {
								?>
									<td><?php echo esc_html($days[ date( 'w', strtotime( $item->event_date ) ) ]); ?></td>
									<?php
							} else {
								?>
									<td><?php echo esc_html(date_i18n( get_option( 'date_format' ), strtotime( $item->event_date ) )); ?></td>
									<?php
							}
							?>
							<td>
								<?php echo esc_html( $period ); ?>
							</td>
							<td>
								<?php if ( $item->type != 'challenge' ) : ?>
									<a class="page-action" href="<?php echo esc_url(admin_url( "admin.php?page=courtres-event&eventID={$item->id}&tab=$tab" )); ?>"><?php echo esc_html__( 'Edit', 'court-reservation' ); ?></a>
								<?php endif; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
