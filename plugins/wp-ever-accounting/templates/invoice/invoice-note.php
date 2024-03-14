<?php
/**
 * Displays invoice note.
 *
 * This template can be overridden by copying it to yourtheme/eaccounting/invoice/invoice-note.php.
 *
 * @var $invoice Invoice
 * @version 1.1.0
 * @package EverAccounting
 */

use EverAccounting\Models\Invoice;
defined( 'ABSPATH' ) || exit;
?>

<?php if ( ! empty( $invoice->get_note() ) ) : ?>
	<div class="ea-document__note">
		<span class="ea-document__note-label"><?php esc_html_e( 'Note', 'wp-ever-accounting' ); ?>:</span>
		<p><?php echo wp_kses( $invoice->get_note(), array( 'br' => array() ) ); ?></p>
	</div>
<?php endif; ?>
