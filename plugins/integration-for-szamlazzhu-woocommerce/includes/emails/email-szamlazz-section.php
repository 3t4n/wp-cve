<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php foreach ($wc_szamlazz_invoices as $invoice): ?>
<div style="margin-bottom: 40px;">
	<?php if($invoice['type'] == 'invoice'): ?>
		<h2><?php esc_html_e('Invoice', 'wc-szamlazz'); ?></h2>
		<p><?php esc_html_e('The invoice for the order can be downloaded from here:', 'wc-szamlazz'); ?> <a href="<?php echo esc_url($invoice['link']); ?>" target="_blank"><?php echo esc_html($invoice['name']); ?></a></p>
	<?php endif; ?>

	<?php if($invoice['type'] == 'proform'): ?>
		<h2><?php esc_html_e('Proforma invoice', 'wc-szamlazz'); ?></h2>
		<p><?php esc_html_e('The proforma invoice for the order can be downloaded from here:', 'wc-szamlazz'); ?> <a href="<?php echo esc_url($invoice['link']); ?>" target="_blank"><?php echo esc_html($invoice['name']); ?></a></p>
	<?php endif; ?>

	<?php if($invoice['type'] == 'deposit'): ?>
		<h2><?php esc_html_e('Deposit invoice', 'wc-szamlazz'); ?></h2>
		<p><?php esc_html_e('The deposit invoice for the order can be downloaded from here:', 'wc-szamlazz'); ?> <a href="<?php echo esc_url($invoice['link']); ?>" target="_blank"><?php echo esc_html($invoice['name']); ?></a></p>
	<?php endif; ?>

	<?php if($invoice['type'] == 'void'): ?>
		<h2><?php esc_html_e('Reverse invoice', 'wc-szamlazz'); ?></h2>
		<p><?php esc_html_e('The previous invoice has been canceled. The reverse invoice for the order can be downloaded from here:', 'wc-szamlazz'); ?> <a href="<?php echo esc_url($invoice['link']); ?>" target="_blank"><?php echo esc_html($invoice['name']); ?></a></p>
	<?php endif; ?>

	<?php if($invoice['type'] == 'receipt'): ?>
		<h2><?php esc_html_e('Receipt', 'wc-szamlazz'); ?></h2>
		<p><?php esc_html_e('The receipt for the order can be downloaded from here:', 'wc-szamlazz'); ?> <a href="<?php echo esc_url($invoice['link']); ?>" target="_blank"><?php echo esc_html($invoice['name']); ?></a></p>
	<?php endif; ?>

	<?php if($invoice['type'] == 'delivery'): ?>
		<h2><?php esc_html_e('Delivery note', 'wc-szamlazz'); ?></h2>
		<p><?php esc_html_e('The delivery note for the order can be downloaded from here:', 'wc-szamlazz'); ?> <a href="<?php echo esc_url($invoice['link']); ?>" target="_blank"><?php echo esc_html($invoice['name']); ?></a></p>
	<?php endif; ?>
</div>
<?php endforeach; ?>
