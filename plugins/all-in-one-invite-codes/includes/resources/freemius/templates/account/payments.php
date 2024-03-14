<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

 if ( ! defined( 'ABSPATH' ) ) { exit; } $fs = freemius( $VARS['id'] ); $payments = $VARS['payments']; $slug = $fs->get_slug(); ?>
<div class="postbox">
	<div id="fs_payments">
		<h3><span class="dashicons dashicons-paperclip"></span> <?php fs_esc_html_echo_inline( 'Payments', 'payments', $slug ) ?></h3>

		<div class="inside">
			<table class="widefat">
				<thead>
				<tr>
					<th><?php fs_esc_html_echo_inline( 'ID', 'id', $slug ) ?></th>
					<th><?php fs_esc_html_echo_inline( 'Date', 'date', $slug ) ?></th>
					<th><?php fs_esc_html_echo_inline( 'Amount', 'amount', $slug ) ?></th>
					<th><?php fs_esc_html_echo_inline( 'Invoice', 'invoice', $slug ) ?></th>
				</tr>
				</thead>
				<tbody>
				<?php $odd = true ?>
				<?php foreach ( $payments as $payment ) : ?>
					<tr<?php echo $odd ? ' class="alternate"' : '' ?>>
						<td><?php echo $payment->id ?></td>
						<td><?php echo date( 'M j, Y', strtotime( $payment->created ) ) ?></td>
						<td><?php echo $payment->formatted_gross() ?></td>
						<td><?php if (! $payment->is_migrated() ) : ?><a href="<?php echo $fs->_get_invoice_api_url( $payment->id ) ?>"
						       class="button button-small"
						       target="_blank" rel="noopener"><?php fs_esc_html_echo_inline( 'Invoice', 'invoice', $slug ) ?></a><?php endif ?></td>
					</tr>
					<?php $odd = ! $odd; endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php
