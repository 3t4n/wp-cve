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

global $wpdb;
$table_name = $this->getTable( 'courts' );
// 18.01.2019, astoian - if not premium, show only 1 court
$courts = array();
if ( $this->isCourtsAdd() ) {
	$courts = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY name" );
} else {
	$court = $wpdb->get_row( "SELECT * FROM $table_name ORDER BY id" );
	if ( isset( $court ) ) {
		$courts = array( $court );
	}
}
?>

<?php
require 'courtres-notice-upgrade.php';
?>

<div class="wrap">
  <h1 class="wp-heading-inline"><?php echo esc_html__( 'Manage Courts', 'court-reservation' ); ?></h1>
  <?php if ( $this->isCourtsAdd() ) { ?>
	<a class="page-title-action" href="<?php echo esc_url(admin_url( 'admin.php?page=courtres-court' )); ?>"><?php echo esc_html__( 'Create', 'court-reservation' ); ?></a>
  <?php } ?>
  <hr class="wp-header-end">

  <div class="cr-tabs-wrap">
			<div class="item1">
				<div class="cr-widget-right">

					<div class="cr-widget-head nav-tab-wrapper">
						<span class="nav-tab"><?php echo esc_html__( 'Use these Shortcodes to integrate Reservation Tables anywhere on your website.', 'court-reservation' ); ?></span>
					</div>
					<div class="cr-widget-item"><?php echo esc_html__( '+ Full View- All-courts:', 'court-reservation' ); ?></div>
					<div class="cr-widget-item"><?php echo esc_html__( '[courtreservation-full-view]', 'court-reservation' ); ?></div>
					<div class="cr-widget-item"><?php echo esc_html__( '+ Full View – Specific courts:', 'court-reservation' ); ?></div>
					<div class="cr-widget-item"><?php echo esc_html__( '[courtreservation-full-view id=1,2,3]', 'court-reservation' ); ?></div>
					<div class="cr-widget-item"><?php echo esc_html__( '+ Single court view:', 'court-reservation' ); ?></div>
					<div class="cr-widget-item"><?php echo esc_html__( '[courtreservation id=1]', 'court-reservation' ); ?></div>

					<div class="cr-widget-item">
						<a href="https://www.courtreservation.io/documentation" class="button" target="_blank"><?php echo esc_html__( 'Go to Documentation', 'court-reservation' ); ?></a>
					</div>

			<?php
				require 'courtres-widget-upgrade.php';
			?>
				</div>
			</div>
	  <div  class="item2">
		<h2 class="nav-tab-wrapper wp-clearfix">
			<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres&tab=0' )); ?>" class="nav-tab nav-tab-active">
				<?php echo esc_html__( 'Courts', 'court-reservation' ); ?>
			</a>
			<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres&tab=1' )); ?>" class="nav-tab">
				<?php echo esc_html__( 'Pyramids', 'court-reservation' ); ?>
			</a>
			<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres&tab=2' )); ?>" class="nav-tab"><?php echo esc_html__( 'Settings', 'court-reservation' ); ?></a>
			<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres&tab=3' )); ?>" class="nav-tab">
				<?php echo esc_html__( 'User Interface', 'court-reservation' ); ?>
			</a>
			<a href="<?php echo esc_html(admin_url( 'admin.php?page=courtres&tab=5' )); ?>" class="nav-tab">
				<?php echo esc_html__( 'E-mail Notification', 'court-reservation' ); ?>
			</a>
			<?php if ( ! cr_fs()->is_plan( 'ultimate' ) ) { ?>
				<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres&tab=4' )); ?>" class="nav-tab">
					<?php echo esc_html__( 'Upgrade', 'court-reservation' ); ?>
				</a>
			<?php } ?>
		</h2>
		<p></p>
		<table class="wp-list-table widefat fixed striped posts">
		  <thead>
			<tr>
			  <th class="manage-column column-title column-primary"><?php echo esc_html__( 'Name', 'court-reservation' ); ?></th>
			  <th class="manage-column column-title column-primary"><?php echo esc_html__( 'Openinghours', 'court-reservation' ); ?></th>
			  <th class="manage-column column-title column-primary"><?php echo esc_html__( 'Reservation Days in Advance', 'court-reservation' ); ?></th>
			  <th class="manage-column column-title column-primary"><?php echo esc_html__( 'Shortcode', 'court-reservation' ); ?></th>
			  <th class="manage-column column-title"><?php echo esc_html__( 'Action', 'court-reservation' ); ?></th>
			</tr>
		  </thead>
		  <tbody>
			<?php
			for ( $i = 0; $i < sizeof( $courts );
			$i++ ) {
														 $item = $courts[ $i ];
				?>
			  <tr>
				<td><?php echo esc_html( $item->name ); ?></td>
				<td><?php echo esc_html( $item->open ); ?>-<?php echo esc_html( $item->close ); ?> <?php echo esc_html__( 'Hour', 'court-reservation' ); ?></td>
				<td><?php echo esc_html( $item->days ); ?></td>
				<td><code>[courtreservation id=<?php echo esc_html($item->id); ?>]</code></td>
				<td><a class="page-action" href="<?php echo esc_url(admin_url( "admin.php?page=courtres-court&courtID={$item->id}" )); ?>"><?php echo esc_html__( 'Edit', 'court-reservation' ); ?></a>
			  </tr>
			<?php } ?>
		  </tbody>
		</table>
		<p></p>
	  </div>
</div>
