<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<?php if(!$this->get_option('agent_key')): ?>

	<a href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=integration&section=wc_szamlazz' ); ?>" class="wc-szamlazz-metabox-settings">
		<span><?php esc_html_e('To create an invoice, you must enter the Agent Key in the settings','wc-szamlazz'); ?></span>
		<span class="dashicons dashicons-arrow-right-alt2"></span>
	</a>

<?php else: ?>

	<div class="wc-szamlazz-metabox-content" data-order="<?php echo $order->get_id(); ?>" data-nonce="<?php echo wp_create_nonce( "wc_szamlazz_generate_invoice" ); ?>">
		<div class="wc-szamlazz-metabox-messages wc-szamlazz-metabox-messages-success" style="display:none;">
			<div class="wc-szamlazz-metabox-messages-content">
				<ul></ul>
				<a href="#"><span class="dashicons dashicons-no-alt"></span></a>
			</div>
		</div>

		<div class="wc-szamlazz-metabox-disabled <?php if($order->get_meta('_wc_szamlazz_own')): ?>show<?php endif; ?>">
			<?php $note = $order->get_meta('_wc_szamlazz_own'); ?>
			<p>
				<?php esc_html_e('Invoicing has been disabled for this order because:','wc-szamlazz'); ?> <span><?php echo esc_html($note); ?></span>
			</p>
			<p>
				<a class="wc-szamlazz-invoice-toggle on" href="#" data-nonce="<?php echo wp_create_nonce( "wc_szamlazz_toggle_invoice" ); ?>" data-order="<?php echo $order->get_id(); ?>">
					<?php esc_html_e('Turn back on','wc-szamlazz'); ?>
				</a>
			</p>
		</div>

		<?php
		$has_invoice = $order->get_meta( '_wc_szamlazz_invoice' );
		$has_voidable_invoice = ($has_invoice || $order->get_meta( '_wc_szamlazz_proform' ) || $order->get_meta( '_wc_szamlazz_deposit' ));
		$is_receipt = $order->get_meta('_wc_szamlazz_type_receipt');
		$has_receipt = $order->get_meta( '_wc_szamlazz_receipt' );
		$has_void_receipt = $order->get_meta( '_wc_szamlazz_void_receipt' );
		$has_corrected_invoice = $order->get_meta( '_wc_szamlazz_corrected' );
		$document_types = WC_Szamlazz_Helpers::get_document_types();
		$is_pro = WC_Szamlazz_Pro::is_pro_enabled();
		?>

		<ul class="wc-szamlazz-metabox-rows">

			<?php foreach ($document_types as $document_type => $document_label): ?>
				<li class="wc-szamlazz-metabox-rows-invoice wc-szamlazz-metabox-invoices-<?php echo $document_type; ?> <?php if($order->get_meta('_wc_szamlazz_'.$document_type)): ?>show<?php endif; ?>">
					<a target="_blank" href="<?php echo $this->generate_download_link($order, $document_type); ?>">
						<span><?php echo $document_label; ?></span>
						<strong><?php echo esc_html($order->get_meta('_wc_szamlazz_'.$document_type)); ?></strong>
					</a>
				</li>
			<?php endforeach; ?>

			<li class="wc-szamlazz-metabox-rows-data wc-szamlazz-metabox-rows-data-complete <?php if($has_invoice && !$has_receipt): ?>show<?php endif; ?>">
				<div class="wc-szamlazz-metabox-rows-data-inside">
					<span><?php esc_html_e('Paid','wc-szamlazz'); ?></span>
					<a href="#" data-trigger-value="<?php esc_attr_e('Mark as paid','wc-szamlazz'); ?>" <?php if($order->get_meta('_wc_szamlazz_completed')): ?>class="completed"<?php endif; ?>>
						<?php if(!$order->get_meta('_wc_szamlazz_completed')): ?>
							<?php esc_html_e('Mark as paid','wc-szamlazz'); ?>
						<?php else: ?>
							<?php if($order->get_meta('_wc_szamlazz_completed') == 1): ?>
								<?php esc_html_e('Paid','wc-szamlazz'); ?>
							<?php else: ?>
								<?php
								$paid_date = $order->get_meta('_wc_szamlazz_completed');
								if (strpos($paid_date, '-') == false) {
									$paid_date = date('Y-m-d', $paid_date);
								}
								?>
								<?php echo esc_html($paid_date); ?>
							<?php endif; ?>
						<?php endif; ?>
					</a>
				</div>
			</li>
			<li class="wc-szamlazz-metabox-rows-data wc-szamlazz-metabox-rows-data-void plugins <?php if($has_voidable_invoice || $has_receipt): ?>show<?php endif; ?>">
				<div class="wc-szamlazz-metabox-rows-data-inside">
					<a href="#" data-trigger-value="<?php esc_attr_e('Reverse invoice','wc-szamlazz'); ?>" data-question="<?php echo esc_attr_x('Are you sure?', 'Reverse invoice', 'wc-szamlazz'); ?>" class="delete"><?php esc_html_e('Reverse invoice','wc-szamlazz'); ?></a>
				</div>
			</li>
			<li class="wc-szamlazz-metabox-rows-data wc-szamlazz-metabox-rows-data-correct plugins <?php if($has_voidable_invoice && !$has_corrected_invoice && $this->get_option('corrected', 'no') == 'yes'): ?>show<?php endif; ?>">
				<div class="wc-szamlazz-metabox-rows-data-inside">
					<a href="#" data-trigger-value="<?php esc_attr_e('Reverse with correction','wc-szamlazz'); ?>" data-question="<?php echo esc_attr_x('Are you sure?', 'Correction invoice', 'wc-szamlazz'); ?>" class="delete"><?php esc_html_e('Reverse with correction','wc-szamlazz'); ?></a>
				</div>
			</li>
		</ul>
		<?php if($this->should_generate_auto_invoice($order) && $this->get_option('auto_invoice_custom', 'no') != 'yes'): ?>
		<div class="wc-szamlazz-metabox-auto-msg <?php if(!$order->get_meta('_wc_szamlazz_own') && !$has_invoice): ?>show<?php endif; ?>">
			<div class="wc-szamlazz-metabox-auto-msg-text">
				<p><?php esc_html_e( 'The invoice will be created automatically if the status of the order changes to:', 'wc-szamlazz' ); ?>
					<?php foreach ($this->should_generate_auto_invoice($order) as $status_name): ?>
						<strong><?php echo wc_get_order_status_name($status_name); ?></strong>
					<?php endforeach; ?>
				</p>
				<span class="dashicons dashicons-yes-alt"></span>
			</div>
		</div>
		<?php endif; ?>

		<div class="wc-szamlazz-metabox-generate <?php if(!$order->get_meta('_wc_szamlazz_own') && !$has_invoice && !$has_receipt): ?>show<?php endif; ?>">
			<?php do_action('wc_szamlazz_metabox_generate_before'); ?>
			<?php if($is_receipt): ?>
				<?php if(!$has_void_receipt): ?>
					<div class="wc-szamlazz-metabox-generate-buttons">
						<a href="#" id="wc_szamlazz_receipt_generate" class="button button-primary" target="_blank" data-question="<?php echo esc_attr_e('Are you sure you want to create the receipt?','wc-szamlazz'); ?>">
							<?php esc_html_e('Create receipt','wc-szamlazz'); ?>
						</a>
					</div>
				<?php endif; ?>
			<?php else: ?>
				<div class="wc-szamlazz-metabox-generate-buttons">
					<a href="#" id="wc_szamlazz_invoice_options"><span class="dashicons dashicons-admin-generic"></span><span><?php esc_html_e('Options','wc-szamlazz'); ?></span></a>

					<?php if($is_pro): ?>
						<?php $preview_url = add_query_arg('wc_szamlazz_preview', $order->get_id(), get_admin_url() ); ?>
						<a href="#" data-url="<?php echo esc_url($preview_url); ?>" target="_blank" class="button button-preview tips" id="wc_szamlazz_invoice_preview" data-tip="<?php echo esc_attr_x('Preview', 'Invoice preview', 'wc-szamlazz'); ?>" target="_blank"><span class="dashicons dashicons-visibility"></span></a>
					<?php endif; ?>

					<a href="#" id="wc_szamlazz_invoice_generate" class="button button-primary" target="_blank" data-question="<?php echo esc_attr_x('Are you sure?', 'Invoice', 'wc-szamlazz'); ?>">
						<?php esc_html_e('Create invoice','wc-szamlazz'); ?>
					</a>

				</div>
			<?php endif; ?>

			<?php do_action('wc_szamlazz_metabox_generate_after'); ?>

			<ul class="wc-szamlazz-metabox-generate-options" style="display:none">
				<?php do_action('wc_szamlazz_metabox_generate_options_before'); ?>
				<?php if(count($this->get_szamlazz_accounts()) > 1): ?>
				<li>
					<label for="wc_szamlazz_invoice_account"><?php esc_html_e('Számlázz.hu account','wc-szamlazz'); ?></label>
					<select id="wc_szamlazz_invoice_account">
						<?php foreach ($this->get_szamlazz_accounts() as $account_key => $account_name): ?>
							<option value="<?php echo esc_attr($account_key); ?>" <?php selected( $this->get_szamlazz_agent_key($order), $account_key); ?>><?php echo esc_html($account_name); ?> - <?php echo substr(esc_html($account_key), 0, 16); ?>...</option>
						<?php endforeach; ?>
					</select>
				</li>
				<?php endif; ?>
				<li class="wc-szamlazz-metabox-generate-options-group">
					<ul>
						<li>
							<label for="wc_szamlazz_invoice_lang"><?php esc_html_e('Language','wc-szamlazz'); ?></label>
							<select id="wc_szamlazz_invoice_lang">
								<?php foreach (WC_Szamlazz_Helpers::get_supported_languages() as $language_code => $language_label): ?>
									<option value="<?php echo esc_attr($language_code); ?>" <?php selected( WC_Szamlazz_Helpers::get_order_language($order), $language_code); ?>><?php echo esc_html($language_label); ?></option>
								<?php endforeach; ?>
							</select>
						</li>
						<li>
							<label for="wc_szamlazz_invoice_doc_type"><?php esc_html_e('Type','wc-szamlazz'); ?></label>
							<select id="wc_szamlazz_invoice_doc_type">
								<?php $invoice_type = WC_Szamlazz_Helpers::get_invoice_type($order); ?>
								<option value="paper" <?php selected( $invoice_type, 'false'); ?>><?php esc_html_e('Paper','wc-szamlazz'); ?></option>
								<option value="electronic" <?php selected( $invoice_type, 'true'); ?>><?php esc_html_e('Electronic','wc-szamlazz'); ?></option>
							</select>
						</li>
					</ul>
				</li>
				<li>
					<label for="wc_szamlazz_invoice_note"><?php esc_html_e('Note','wc-szamlazz'); ?></label>
					<textarea id="wc_szamlazz_invoice_note" placeholder="<?php esc_html_e('Here you can override the note specified in settings','wc-szamlazz'); ?>"></textarea>
				</li>
				<li class="wc-szamlazz-metabox-generate-options-group">
					<ul>
						<li>
							<label for="wc_szamlazz_invoice_deadline"><?php esc_html_e('Payment deadline','wc-szamlazz'); ?></label>
							<input type="number" id="wc_szamlazz_invoice_deadline" value="<?php echo absint($this->get_payment_method_deadline($order->get_payment_method())); ?>" />
							<em>nap</em>
						</li>
						<li>
							<label for="wc_szamlazz_invoice_completed"><?php esc_html_e('Completion date','wc-szamlazz'); ?></label>
							<input type="text" class="date-picker" id="wc_szamlazz_invoice_completed" maxlength="10" value="<?php echo date('Y-m-d'); ?>" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])">
						</li>
					</ul>
				</li>
				<li class="wc-szamlazz-metabox-generate-options-type">
					<label><?php esc_html_e('Document type','wc-szamlazz'); ?></label>
					<label for="wc_szamlazz_invoice_normal">
						<input type="radio" name="invoice_extra_type" id="wc_szamlazz_invoice_normal" value="1" checked="checked" />
						<span><?php esc_html_e('Invoice','wc-szamlazz'); ?></span>
					</label>
					<label for="wc_szamlazz_invoice_proform">
						<input type="radio" name="invoice_extra_type" id="wc_szamlazz_invoice_proform" value="1" />
						<span><?php esc_html_e('Proforma invoice','wc-szamlazz'); ?></span>
					</label>
					<label for="wc_szamlazz_invoice_deposit">
						<input type="radio" name="invoice_extra_type" id="wc_szamlazz_invoice_deposit" value="1" />
						<span><?php esc_html_e('Deposit invoice','wc-szamlazz'); ?></span>
					</label>
					<label for="wc_szamlazz_invoice_delivery">
						<input type="radio" name="invoice_extra_type" id="wc_szamlazz_invoice_delivery" value="1" />
						<span><?php esc_html_e('Delivery note','wc-szamlazz'); ?></span>
					</label>
				</li>
				<li>
					<a class="wc-szamlazz-invoice-upload" href="#"><span class="dashicons dashicons-cloud-upload"></span> <span class="label"><?php esc_html_e('Upload document manually','wc-szamlazz'); ?></span></a>
					<a class="wc-szamlazz-invoice-toggle off" href="#"><?php esc_html_e('Disable invoicing','wc-szamlazz'); ?></a>
				</li>
				<?php do_action('wc_szamlazz_metabox_generate_options_after'); ?>
			</ul>

		</div>
		<div class="wc-szamlazz-metabox-receipt-void-note <?php if($is_receipt && $has_void_receipt): ?>show<?php endif; ?>"><small><?php esc_html_e('It is no longer possible to create a new receipt for this order.','wc-szamlazz'); ?><a href="#" id="wc_szamlazz_reverse_receipt"><?php esc_html_e('Create an invoice instead','wc-szamlazz'); ?></a></small></div>
	</div>

	<script type="text/template" id="tmpl-wc-szamlazz-modal-upload">
		<div class="wc-backbone-modal wc-szamlazz-modal-upload">
			<div class="wc-backbone-modal-content">
				<section class="wc-backbone-modal-main" role="main">
					<header class="wc-backbone-modal-header">
						<h1><?php echo esc_html_e('Upload document manually', 'wc-szamlazz'); ?></h1>
						<button class="modal-close modal-close-link dashicons dashicons-no-alt">
							<span class="screen-reader-text"><?php esc_html_e( 'Close modal panel', 'woocommerce' ); ?></span>
						</button>
					</header>
					<form id="wc-szamlazz-modal-upload-form">
						<article class="wc-szamlazz-modal-upload-form">
							<div class="wc-szamlazz-metabox-messages wc-szamlazz-metabox-messages-success wc-szamlazz-modal-uploader-results" style="display:none;">
								<div class="wc-szamlazz-metabox-messages-content">
									<ul></ul>
								</div>
							</div>
							<label><?php esc_html_e('Document type','wc-szamlazz'); ?></label>
							<ul>
								<li>
									<label for="wc_szamlazz_document_normal">
										<input type="radio" name="document_upload_type" id="wc_szamlazz_document_normal" value="invoice" checked="checked" />
										<span><?php esc_html_e('Invoice','wc-szamlazz'); ?></span>
									</label>
								</li>
								<li>
									<label for="wc_szamlazz_document_proform">
										<input type="radio" name="document_upload_type" id="wc_szamlazz_document_proform" value="proform" />
										<span><?php esc_html_e('Proforma invoice','wc-szamlazz'); ?></span>
									</label>
								</li>
								<li>
									<label for="wc_szamlazz_document_deposit">
										<input type="radio" name="document_upload_type" id="wc_szamlazz_document_deposit" value="deposit" />
										<span><?php esc_html_e('Deposit invoice','wc-szamlazz'); ?></span>
									</label>
								</li>
								<li>
									<label for="wc_szamlazz_document_delivery">
										<input type="radio" name="document_upload_type" id="wc_szamlazz_document_delivery" value="delivery" />
										<span><?php esc_html_e('Delivery note','wc-szamlazz'); ?></span>
									</label>
								</li>
							</ul>
							<p>
								<label for="wc_szamlazz_document_upload_file"><?php esc_html_e('PDF file','wc-szamlazz'); ?></label>
								<input type="file" name="document_upload_file" id="wc_szamlazz_document_upload_file">
							</p>
							<p>
								<label for="wc_szamlazz_document_upload_name"><?php esc_html_e('Document name','wc-szamlazz'); ?></label>
								<input type="text" name="document_upload_name" id="wc_szamlazz_document_upload_name">
							</p>
							<p>
								<label for="wc_szamlazz_mark_paid_date"><?php esc_html_e('Payment date','wc-szamlazz'); ?></label>
								<input type="text" class="date-picker" id="wc_szamlazz_mark_paid_date" name="document_payment_date" maxlength="10" value="" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])"><br>
								<small class="description"><?php esc_html_e('Leave this field empty, if the invoice is not paid yet.','wc-szamlazz'); ?></small>
							</p>
						</article>
						<footer>
							<div class="inner">
								<button type="submit" class="button button-primary button-large" id="wc_szamlazz_upload_document"><?php esc_html_e( 'Upload document', 'wc-szamlazz' ); ?></button>
							</div>
						</footer>
					</form>
				</section>
			</div>
		</div>
		<div class="wc-backbone-modal-backdrop modal-close"></div>
	</script>

<?php endif; ?>
