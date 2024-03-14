<?php

namespace WPDeskFIVendor;

/**
 * @var array $params
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration;
$params = isset($params) ? $params : [];
?>
<div class="ocs-meta-box">
	<div class="ocs-meta-col">
		<h3><?php 
\esc_html_e('Seller', 'flexible-invoices');
?>
			<button class="edit-ocs-data" type="button"><span class="dashicons dashicons-edit"></span></button>
		</h3>
		<?php 
require __DIR__ . '/owner.php';
?>
	</div>
	<div class="ocs-meta-col">
		<h3><?php 
\esc_html_e('Customer', 'flexible-invoices');
?>
			<button class="edit-ocs-data" type="button"><span class="dashicons dashicons-edit"></span></button>
		</h3>
		<?php 
require __DIR__ . '/customer.php';
?>
	</div>
	<?php 
if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration::is_super()) {
    ?>
		<div class="ocs-meta-col">
			<h3><?php 
    \esc_html_e('Recipient', 'flexible-invoices');
    ?>
				<button class="edit-ocs-data" type="button"><span class="dashicons dashicons-edit"></span></button>
			</h3>
			<?php 
    require __DIR__ . '/recipient.php';
    ?>
		</div>
	<?php 
}
?>
</div>
<?php 
