<?php
/**
 * Displays bill terms.
 *
 * This template can be overridden by copying it to yourtheme/eaccounting/bill/bill-terms.php.
 *
 * @var $bill Bill
 * @version 1.1.0
 * @package EverAccounting
 */

use EverAccounting\Models\Bill;

defined( 'ABSPATH' ) || exit;
?>

<?php if ( ! empty( $bill->get_terms() ) ) : ?>
	<div class="ea-document__terms">
		<span class="ea-document__terms-label"><?php esc_html_e( 'Terms & Conditions', 'wp-ever-accounting' ); ?>:</span>
		<p><?php echo wp_kses( $bill->get_terms(), array( 'br' => array() ) ); ?></p>
	</div>
<?php endif; ?>
