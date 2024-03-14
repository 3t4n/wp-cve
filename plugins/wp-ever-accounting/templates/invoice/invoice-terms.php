<?php
/**
 * Displays invoice terms.
 *
 * This template can be overridden by copying it to yourtheme/eaccounting/invoice/invoice-terms.php.
 *
 * @var $invoice Invoice
 * @version 1.1.0
 * @package EverAccounting
 */

use EverAccounting\Models\Invoice;

defined( 'ABSPATH' ) || exit;
?>

<?php if ( ! empty( $invoice->get_terms() ) ) : ?>
	<div class="ea-document__terms">
		<span class="ea-document__terms-label"><?php esc_html_e( 'Terms & Conditions', 'wp-ever-accounting' ); ?>:</span>
		<p><?php echo wp_kses( $invoice->get_terms(), array( 'br' => array() ) ); ?></p>
	</div>
<?php endif; ?>
