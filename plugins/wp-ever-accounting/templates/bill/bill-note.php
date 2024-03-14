<?php
/**
 * Displays bill note.
 *
 * This template can be overridden by copying it to yourtheme/eaccounting/bill/bill-note.php.
 *
 * @var $bill Bill
 * @version 1.1.0
 * @package EverAccounting
 */

use EverAccounting\Models\Bill;
defined( 'ABSPATH' ) || exit;
?>

<?php if ( ! empty( $bill->get_note() ) ) : ?>
	<div class="ea-document__note">
		<span class="ea-document__note-label"><?php esc_html_e( 'Note', 'wp-ever-accounting' ); ?>:</span>
		<p><?php echo wp_kses( $bill->get_note(), array( 'br' => array() ) ); ?></p>
	</div>
<?php endif; ?>
