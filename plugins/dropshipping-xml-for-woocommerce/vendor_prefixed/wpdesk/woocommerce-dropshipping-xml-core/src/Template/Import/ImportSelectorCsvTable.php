<?php

namespace DropshippingXmlFreeVendor;

/**
 * @var SimpleXMLElement[] $node
 */
?>

<table class="form-table import-preview-table-csv bg-white width-100">
	<tbody>
	<?php 
foreach ($node as $key => $val) {
    ?>
		<tr valign="top">
			<th style="width:30%"><span class="draggable" xpath="//node/<?php 
    echo \esc_html($key);
    ?>"><?php 
    echo \esc_html($key);
    ?></span></th>
			<td><?php 
    echo \esc_html($val->__toString());
    ?></td>
		</tr>
	<?php 
}
?>
	</tbody>
</table>
<?php 
