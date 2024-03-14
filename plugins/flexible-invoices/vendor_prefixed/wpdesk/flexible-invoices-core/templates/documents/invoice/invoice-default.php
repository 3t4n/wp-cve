<?php

namespace WPDeskFIVendor;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks;
require __DIR__ . '/invoice-header.php';
require __DIR__ . '/parts/header.php';
require __DIR__ . '/parts/table.php';
/**
 * Exchange table
 */
$exchange_table = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::template_exchange_vertical_filter($invoice, $products, $client);
if (!empty($exchange_table)) {
    ?>
<table class="table-without-margin" style="margin-top: 10px;">
	<tr>
		<td style="width:70%">
			<?php 
    echo $exchange_table;
    ?>
		</td>
		<td style="width:30%; padding-left: 10px;">
			<?php 
    require __DIR__ . '/parts/totals/vertical.php';
    ?>
		</td>
	</tr>
</table>
<?php 
} else {
    require __DIR__ . '/parts/totals/horizontal.php';
}
require __DIR__ . '/parts/signatures.php';
require __DIR__ . '/parts/footer.php';
require __DIR__ . '/invoice-footer.php';
