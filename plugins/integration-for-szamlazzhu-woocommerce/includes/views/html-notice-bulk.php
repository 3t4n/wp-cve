<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="notice notice-info wc-szamlazz-notice wc-szamlazz-bulk-actions wc-szamlazz-print">
	<?php if($action == 'print'): ?>
		<p>
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#FF6630" d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
			<span><?php echo sprintf( esc_html__( '%s invoice(s) selected for printing.', 'wc-szamlazz' ), $print_count); ?></span>
			<a href="<?php echo $pdf_file_url; ?>" id="wc-szamlazz-bulk-print" data-pdf="<?php echo $pdf_file_url; ?>"><?php esc_html_e('Print', 'wc-szamlazz'); ?></a>
		</p>
	<?php elseif($action == 'download'): ?>
		<p>
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path fill="#FF6630" d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM17 13l-5 5-5-5h3V9h4v4h3z"/></svg>
			<span><?php echo sprintf( esc_html__( '%s invoice(s) selected for download.', 'wc-szamlazz' ), $print_count); ?></span>
			<a href="<?php echo $pdf_file_url; ?>" id="wc-szamlazz-bulk-download" download data-pdf="<?php echo $pdf_file_url; ?>"><?php esc_html_e('Download', 'wc-szamlazz'); ?></a>
		</p>
	<?php elseif($action == 'generate'): ?>
		<?php
		$document_types = WC_Szamlazz_Helpers::get_document_types();
		$document_label = $document_types[$document_type];
		?>
		<?php if(count($documents) > apply_filters('wc_szamlazz_bulk_generate_defer_limit', 2)): ?>
			<p>
				<svg height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="m9.375 0 5.625 5.4v10.8c0 .99-.84375 1.8-1.875 1.8h-11.259375c-1.03125 0-1.865625-.81-1.865625-1.8v-14.4c0-.99.84375-1.8 1.875-1.8zm4.125 16.5v-10.3125h-5v-4.6875h-7v15zm-9.06-5.4458599 1.72666667 1.6433122 4.39333333-4.1974523.94.9044586-5.33333333 5.0955414-2.66666667-2.5477708z" fill="#FF6630"/></svg>
				<span><?php echo sprintf( esc_html__( '%1$s order(s) selected to create: %2$s. Documents are being created.', 'wc-szamlazz' ), count($documents), $document_label); ?></span>
			</p>
		<?php else: ?>
			<p>
				<svg height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="m9.375 0 5.625 5.4v10.8c0 .99-.84375 1.8-1.875 1.8h-11.259375c-1.03125 0-1.865625-.81-1.865625-1.8v-14.4c0-.99.84375-1.8 1.875-1.8zm4.125 16.5v-10.3125h-5v-4.6875h-7v15zm-9.06-5.4458599 1.72666667 1.6433122 4.39333333-4.1974523.94.9044586-5.33333333 5.0955414-2.66666667-2.5477708z" fill="#FF6630"/></svg>
				<span><?php echo sprintf( esc_html__( '%1$s order(s) selected:', 'wc-szamlazz' ), count($documents)); ?></span>
			</p>
			<?php foreach ($documents as $order_id): ?>
				<?php $temp_order = wc_get_order($order_id); ?>
				<?php if($temp_order): ?>
					<p>
						<a href="<?php echo esc_url($temp_order->get_edit_order_url()); ?>"><?php echo esc_html($temp_order->get_order_number()); ?></a> -
						<?php if($temp_order->get_meta('_wc_szamlazz_'.$document_type)): ?>
							<?php esc_html_e($document_label); ?>: <?php echo esc_html($temp_order->get_meta('_wc_szamlazz_'.$document_type)); ?>
						<?php else: ?>
							<?php echo sprintf( esc_html__( 'No %1$s has been made', 'wc-szamlazz' ), $document_label); ?>
						<?php endif; ?>
					</p>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	<?php endif; ?>
</div>
