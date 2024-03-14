<?php

namespace DropshippingXmlFreeVendor;

/**
 * @var WPDesk\View\Renderer\Renderer $renderer
 * @var \WPDesk\Library\DropshippingXmlCore\Infrastructure\View\FormView $form
 * @var array $table_data
 */
?>

<tr>
	<td class="forminp txt-center" colspan="2">
		<fieldset>
			<p class="font-size-16px"><b><?php 
echo \esc_html(\__('Set separator', 'dropshipping-xml-for-woocommerce'));
?></b>
				<?php 
$form->show_field('dropshipping-item-separator');
?>
				<?php 
$form->show_field('dropshipping-item-separator-button');
?>
			</p>
		</fieldset>
	</td>
</tr>
<tr>
	<td class="forminp width-100 padding-md" colspan="2" id="dropshipping-csv-table">
		<?php 
$renderer->output_render('Import/ImportSelectorCsvTable', $table_data);
?>
	</td>
</tr>
<?php 
