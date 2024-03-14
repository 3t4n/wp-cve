<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 * Class Courtres_Admin in class-courtres-admin.php
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

$piramids = Courtres_Entity_Piramid::get_list();
?>

<?php
require 'courtres-notice-upgrade.php';
?>

<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo esc_html__( 'Manage Pyramids', 'court-reservation' ); ?></h1>
	<?php if ( $this->isPiramidsAdd() ) { ?>
		<a class="page-title-action" href="<?php echo esc_url(admin_url( 'admin.php?page=courtres-piramid' )); ?>"><?php echo esc_html__( 'Create', 'court-reservation' ); ?></a>
	<?php } ?>
	<hr class="wp-header-end">

	<div class="cr-tabs-wrap">
			<div class="item1">
				<div class="cr-widget-right">
						<?php
								require 'courtres-widget-upgrade.php';
						?>
				</div>
			</div>
			<div class="item2">
				<h2 class="nav-tab-wrapper wp-clearfix">
						<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres&tab=0' )); ?>" class="nav-tab">
								<?php echo esc_html__( 'Courts', 'court-reservation' ); ?>
						</a>
						<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres&tab=1' )); ?>" class="nav-tab nav-tab-active">
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
							<?php foreach ( Courtres_Entity_Piramid::get_db_fields() as $key => $field ) : ?>
								<?php if ( $field['show_in_admin'] ) : ?>
									<th class="manage-column column-title column-primary"><?php echo esc_html( $field['title'] ); ?></th>
								<?php endif; ?>
							<?php endforeach; ?>
							<th class="manage-column column-title"><?php echo esc_html__( 'Shortcode', 'court-reservation' ); ?></th>
							<th class="manage-column column-title"><?php echo esc_html__( 'Action', 'court-reservation' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if ( $piramids ) : ?>
							<?php foreach ( $piramids as $key => $piramid ) : ?>
								<tr>
									<?php foreach ( Courtres_Entity_Piramid::get_db_fields() as $key => $field ) : ?>
										<?php if ( $field['show_in_admin'] ) : ?>
											<?php
											switch ( $field['code'] ) {
												case 'duration_ts':
												case 'lifetime_ts':
												case 'locktime_ts':
													?>
													<td><?php echo esc_html(date_i18n( 'g:i', $piramid[ $field['code'] ] )); ?></td>
													<?php
													break;

												default:
													?>
													<td><?php echo esc_html( $piramid[ $field['code'] ] ); ?></td>
													<?php
													break;
											}
											?>
										<?php endif; ?>
									<?php endforeach; ?>
									<td><code>[courtpyramid id=<?php echo esc_html( $piramid['id'] ); ?>]</code></td>
									<td><a class="page-action" href="<?php echo esc_url(admin_url( "admin.php?page=courtres-piramid&piramidID={$piramid["id"]}&action=edit" )); ?>"><?php echo esc_html__( 'Edit', 'court-reservation' ); ?></a>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
				<p></p>
			</div>
</div>
