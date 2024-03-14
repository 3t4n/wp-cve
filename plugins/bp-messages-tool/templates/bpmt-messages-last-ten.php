<?php

/**
 * Last 10 Message
 * Custom template for BuddyPress Messages Tool
 * Cannot be overloaded
 *
 */


?>

<div id="messages">

	<br>
	<h3><?php _e( 'LAST 10 MESSAGES', 'bpmt' )?></h3>

	<table class="widefat fixed" cellspacing="10">
		<thead>
			<tr>
				<th scope="col"><?php _e( 'DATE', 'bpmt' )?></th>
				<th scope="col"><?php _e( 'FROM : USER ID', 'bpmt' )?></th>
				<th scope="col"><?php _e( 'TO :  USER ID', 'bpmt' )?></th>
				<th scope="col"><?php _e( 'SUBJECT', 'bpmt' )?></th>
				<th scope="col"><?php _e( 'CONTENT', 'bpmt' )?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th scope="col"><?php _e( 'DATE', 'bpmt' )?></th>
				<th scope="col"><?php _e( 'FROM :  USER ID', 'bpmt' )?></th>
				<th scope="col"><?php _e( 'TO :  USER ID', 'bpmt' )?></th>
				<th scope="col"><?php _e( 'SUBJECT', 'bpmt' )?></th>
				<th scope="col"><?php _e( 'CONTENT', 'bpmt' )?></th>
			</tr>
		</tfoot>

		<tbody>

		<?php

		$alter = true;

		foreach( $last_ten as $message  ) {

			if ( $alter ) {
				echo '<tr class="alternate">';
			} else {
				echo '<tr>';
			}

			$alter = ! $alter;

			echo '<td>' . date_i18n( get_option( 'date_format' ), strtotime( $message->date_sent  ) ) . '</td>';

			echo '<td>' . bp_core_get_userlink( $message->sender_id ) . ' : ' . $message->sender_id . '</td>';

			echo '<td>' . bp_core_get_userlink( $message->receip_id ) . ' : ' . $message->receip_id . '</td>';

			echo '<td>' . $message->subject . '</td>';

			echo '<td>' . wp_trim_words( $message->message, 20 ) . '</td>';

			echo '</tr>';

		}

		?>

		</tbody>
	</table>

</div>
