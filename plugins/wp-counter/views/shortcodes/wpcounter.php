<?php
/**
 * Shortcode view for wpcounterr
 *
 * @package Haruncpi\WpCounter
 * @subpackage Views
 * @author Harun<harun.cox@gmail.com>
 * @link https://learn24bd.com
 * @since 1.2
 */
?>

<table width="100%">
	<tr>
		<td colspan="2"><strong><span class="dashicons dashicons-chart-area"></span> <?php echo esc_html( $options['headline'] == '' ? __( 'Visitor Status', 'wpcounter' ) : $options['headline'] ); ?></strong></td>
	</tr>
	<tr>
		<td><span class="dashicons dashicons-calendar-alt"></span> <?php esc_html_e( 'Today', 'wpcounter' ); ?></td>
		<td><?php echo esc_html( $data['today'] ); ?></td>
	</tr>
	<tr>
		<td><span class="dashicons dashicons-calendar-alt"></span> <?php esc_html_e( 'Yesterday', 'wpcounter' ); ?></td>
		<td><?php echo esc_html( $data['yesterday'] ); ?></td>
	</tr>
	<tr>
		<td><span class="dashicons dashicons-calendar-alt"></span> <?php esc_html_e( 'This Week', 'wpcounter' ); ?></td>
		<td><?php echo esc_html( $data['thisWeek'] ); ?></td>
	</tr>
	<tr>
		<td><span class="dashicons dashicons-calendar-alt"></span> <?php esc_html_e( 'This Month', 'wpcounter' ); ?></td>
		<td><?php echo esc_html( $data['thisMonth'] ); ?></td>
	</tr>
	<tr>
		<td><span class="dashicons dashicons-calendar-alt"></span> <?php esc_html_e( 'Total', 'wpcounter' ); ?></td>
		<td><?php echo esc_html( $data['totalVisitor'] ); ?></td>
	</tr>
</table>
