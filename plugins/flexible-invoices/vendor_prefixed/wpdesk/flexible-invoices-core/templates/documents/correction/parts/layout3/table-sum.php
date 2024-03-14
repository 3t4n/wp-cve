<?php

namespace WPDeskFIVendor;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks;
$col1_styles = 'width:78%;text-align:' . \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left') . ';';
$col2_styles = 'width:22%;text-align:' . \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right') . ';';
$table_sum_styles = 'width:300px;text-align:' . \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right') . ';';
?>
<table class="table-without-margin">
	<tr>
		<td style="<?php 
echo \esc_attr($col1_styles);
?>">
			<?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::template_exchange_vertical_filter($correction, $products, $client);
?>
			<?php 
require \dirname(__DIR__, 2) . '/parts/footer.php';
?>
		</td>
		<td style="<?php 
echo \esc_attr($col2_styles);
?>">
			<?php 
require \dirname(__DIR__, 2) . '/parts/totals/' . $correction->get_type() . '-vertical.php';
?>
		</td>
	</tr>
</table>
<?php 
