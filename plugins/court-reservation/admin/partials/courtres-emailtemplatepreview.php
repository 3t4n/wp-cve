<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://webmuehle.at
 * @since      1.1.0
 *
 * @package    UI - colors and others
 * @subpackage Courtres/admin/ui
 */

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die();
}

global $wpdb;
$table_settings = $this->getTable( 'settings' );

require 'courtres-notice-upgrade.php';
require 'courtres-notice-message.php';

$email_template = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'option_email_template'" )->option_value;
$option_email_1 = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'option_email_1'" )->option_value;
$option_email_2 = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'option_email_2'" )->option_value;
$option_email_3 = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'option_email_3'" )->option_value;
$option_email_4 = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'option_email_4'" )->option_value;
$option_email_5 = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'option_email_5'" )->option_value;
$option_email_6 = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'option_email_6'" )->option_value;
$option_email_7 = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'option_email_7'" )->option_value;
$option_email_8 = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'option_email_8'" )->option_value;

if (!isset($option_email_5) || $option_email_8=="") { $option_email_5="#2b87da"; }
if (!isset($option_email_6) || $option_email_8=="") { $option_email_6="white"; }
if (!isset($option_email_7) || $option_email_8=="") { $option_email_7="white"; }
if (!isset($option_email_8) || $option_email_8=="") { $option_email_8="#4e4e4e"; } ?>

<div class="wrap">
	<h1 class="wp-heading-inline">
		<?php echo esc_html__( 'E-mail Notifications', 'court-reservation' ); ?>
	</h1>
	<hr class="wp-header-end">

	<div class="cr-tabs-wrap">
		<div class="item1">
			<div class="cr-widget-right">
				<?php
					require 'courtres-widget-upgrade.php';
				?>
			</div>

		</div>
		<div  class="item2">
			<h2 class="nav-tab-wrapper wp-clearfix">
				<a href="<?php echo esc_html(admin_url( 'admin.php?page=courtres&tab=0' )); ?>" class="nav-tab">
					<?php echo esc_html__( 'Courts', 'court-reservation' ); ?>
				</a>
				<a href="<?php echo esc_html(admin_url( 'admin.php?page=courtres&tab=1' )); ?>" class="nav-tab">
					<?php echo esc_html__( 'Pyramids', 'court-reservation' ); ?>
				</a>
				<a href="<?php echo esc_html(admin_url( 'admin.php?page=courtres&tab=2' )); ?>" class="nav-tab"><?php echo esc_html__( 'Settings', 'court-reservation' ); ?></a>
				<a href="<?php echo esc_html(admin_url( 'admin.php?page=courtres&tab=3' )); ?>" class="nav-tab">
					<?php echo esc_html__( 'User Interface', 'court-reservation' ); ?>
				</a>
				<a href="<?php echo esc_html(admin_url( 'admin.php?page=courtres&tab=5' )); ?>" class="nav-tab nav-tab-active">
					<?php echo esc_html__( 'E-mail Notifications', 'court-reservation' ); ?>
				</a>
				<?php if ( ! cr_fs()->is_plan( 'ultimate' ) ) { ?>
					<a href="<?php echo esc_html(admin_url( 'admin.php?page=courtres&tab=4' )); ?>" class="nav-tab">
						<?php echo esc_html__( 'Upgrade', 'court-reservation' ); ?>
					</a>
				<?php } ?>
			</h2> 
			<style>
				table,tbody,tr,td { display: block !important; width: 100% !important; box-sizing: border-box; }
				#header_wrapper, #header_wrapper2 { display: table-cell !important; }
			</style>

			<div style="width: 100%; background: #f7f7f7;"> 
				<div style="width: 100%; max-width: 600px; margin-left: auto; margin-right: auto;"> 
<?php
					$message=email_message($email_template,$option_email_3,$option_email_4,$option_email_5,$option_email_6,$option_email_7,$option_email_8);
					echo wp_kses_post($message);
?>
				</div>
			</div>
			<div style="width: 100%; margin-bottom: 20px;"> &nbsp; </div>

				<table>
					<tr>
						<td>
							<a class="button" href="<?php echo esc_html(admin_url( 'admin.php?page=courtres&tab=5' )); ?>"><?php echo esc_html__( 'Back', 'court-reservation' ); ?></a>
						</td>
						<td></td>
					</tr>
				</table>
