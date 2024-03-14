<?php
/**
 * Status Widget
 *
 * @package Haruncpi\WpCounter
 * @subpackage Views
 * @author Harun<harun.cox@gmail.com>
 * @link https://learn24bd.com
 * @since 1.2
 */

use Haruncpi\WpCounter\DB;
$data = DB::get_visitor_data();
?>
<table class="widefat striped">
	<tr>
		<td><span class="dashicons dashicons-calendar-alt"></span> <?php esc_html_e( 'Today', 'wpcounter' ); ?> (<?php echo getToday(); ?>)</td>
		<td><?php echo $data['today']; ?></td>
	</tr>
	<tr>
		<td><span class="dashicons dashicons-calendar-alt"></span> <?php esc_html_e( 'Yesterday', 'wpcounter' ); ?> (<?php echo getYesterday(); ?>)</td>
		<td><?php echo $data['yesterday']; ?></td>
	</tr>
	<tr>
		<td><span class="dashicons dashicons-calendar-alt"></span> <?php esc_html_e( 'This Week', 'wpcounter' ); ?>
			(<?php echo getLast( 'week', 'first' ) . ' - ' . getCurrent( 'week', 'last' ); ?>)
		</td>
		<td><?php echo $data['thisWeek']; ?></td>
	</tr>
	<tr>
		<td><span class="dashicons dashicons-calendar-alt"></span> <?php esc_html_e( 'This Month', 'wpcounter' ); ?>
			(<?php echo getCurrent( 'month', 'first' ) . ' - ' . getCurrent( 'month', 'last' ); ?>)
		</td>
		<td><?php echo $data['thisMonth']; ?></td>
	</tr>
	<tr>
		<td><span class="dashicons dashicons-calendar-alt"></span> <?php esc_html_e( 'Total Visitor', 'wpcounter' ); ?></td>
		<td><?php echo $data['totalVisitor']; ?></td>
	</tr>
</table>
