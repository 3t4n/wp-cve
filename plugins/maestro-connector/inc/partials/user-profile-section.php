<h2><?php esc_html_e( 'Bluehost Maestro', 'maestro-connector' ); ?></h2>
<table class="form-table" role="presentation">
	<tbody>
		<tr class="user-maestro-added-date">
			<th><?php esc_html_e( 'Date Added', 'maestro-connector' ); ?></th>
			<td><?php echo esc_html( gmdate( get_option( 'date_format' ), (int) $webpro->added_time ) ); ?></td>
		</tr>
		<tr class="user-maestro-added-by">
			<th><?php esc_html_e( 'Added By', 'maestro-connector' ); ?></th>
			<td><?php echo esc_html( $webpro->added_by ); ?></td>
		</tr>
		<tr class="user-maestro-revoke">
			<th><?php esc_html_e( 'Revoke', 'maestro-connector' ); ?></th>
			<td><a href="<?php echo esc_url( $revoke_url ); ?>" class="button button-secondary"><?php esc_html_e( 'Revoke Maestro Access', 'maestro-connector' ); ?></a></td>
		</tr>
	</tbody>
</table>
