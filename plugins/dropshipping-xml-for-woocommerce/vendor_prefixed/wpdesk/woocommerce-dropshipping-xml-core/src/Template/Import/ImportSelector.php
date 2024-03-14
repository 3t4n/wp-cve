<?php

namespace DropshippingXmlFreeVendor;

/**
 * @var string $title
 * @var bool $edit
 * @var string $mode
 * @var string $previous_step
 * @var WPDesk\View\Renderer\Renderer $renderer
 * @var \WPDesk\Library\DropshippingXmlCore\Infrastructure\View\FormView $form
 * @var string $format
 * @var int $items
 * @var int|null $item_nr
 * @var array|null $table_data
 * @var string|null $item_element
 * @var array|null $all_elements
 * @var array|null $rendered_xml
 */
$renderer->output_render('Header', ['title' => $title]);
?>
	<h2>
	<?php 
// TRANSLATORS: %s: page step.
echo \esc_html(\sprintf(\__('Step %s', 'dropshipping-xml-for-woocommerce'), '2/4'));
?>
	</h2>

<?php 
if ($format === 'csv') {
    ?>
	<p><?php 
    echo \wp_kses_post(\__('Below you will find a preview of the imported CSV file. You can check the number of records in the file by switching between the next / previous record.', 'dropshipping-xml-for-woocommerce'));
    ?></p>
<?php 
} else {
    ?>
	<p><?php 
    echo \wp_kses_post(\__('Below you will find a preview of the imported file. From the column on the left, select a tag that contains all product data. A suggested tag has been automatically chosen. Make sure that it contains all parameters needed for import process. For preview, you can switch between the next / previous product using the arrows.', 'dropshipping-xml-for-woocommerce'));
    ?></p>
<?php 
}
?>
		<p style="font-weight: bold;"><?php 
echo \wp_kses_post(\__('Read more in the <a href="https://wpde.sk/dropshipping-import" class="docs-url" target="_blank">plugin documentation &rarr;</a>', 'dropshipping-xml-for-woocommerce'));
?></p>
	<hr>

<?php 
$form->form_start(['method' => 'POST', 'template' => 'form-start-no-table']);
?>
	<div id="dropshipping-table-wrapper">
		<table class="form-table import-preview-table bg-white">
			<tbody>
			<tr valign="top">
				<td class="forminp txt-center" colspan="2">
					<fieldset>
						<a href="#" class="dashicons dashicons-arrow-left-alt2 to-left" id="dropshipping-item-position-arrow-left"></a>
						<?php 
$form->show_field('dropshipping-item-nr');
?> <span
							id="dropshipping-item-position-separator"><?php 
echo \esc_html(\__('of', 'dropshipping-xml-for-woocommerce'));
?></span> <span
							id="dropshipping-item-position"><?php 
echo \esc_html($items);
?></span>
						<a href="#" class="dashicons dashicons-arrow-right-alt2 to-right" id="dropshipping-item-position-arrow-right"></a>
					</fieldset>
				</td>
			</tr>
			<?php 
if ($format === 'csv') {
    $renderer->output_render('Import/ImportSelectorCsv', ['form' => $form, 'table_data' => $table_data, 'renderer' => $renderer]);
} else {
    $renderer->output_render('Import/ImportSelectorXML', ['form' => $form, 'all_elements' => $all_elements, 'item_element' => $item_element, 'rendered_xml' => $rendered_xml, 'renderer' => $renderer]);
}
?>
			</tbody>
		</table>

		<div id="dropshipping-table-loader" class="fixed-loader" style="display: none">
			<div class="absolute-centered">
				<span class="loading-text"><?php 
echo \esc_html(\__('Please wait, loading...', 'dropshipping-xml-for-woocommerce'));
?></span>
				<div class="loading-wrapper"><img src="<?php 
echo \esc_url(\includes_url() . 'js/tinymce/skins/lightgray/img/loader.gif');
?>"/></div>
			</div>
		</div>
	</div>
<?php 
$renderer->output_render('Steps', ['edit' => $edit, 'mode' => $mode, 'form' => $form, 'previous_step' => $previous_step]);
$form->form_fields_complete();
$form->form_end(['form-end-no-table']);
?>


<?php 
$renderer->output_render('Footer');
