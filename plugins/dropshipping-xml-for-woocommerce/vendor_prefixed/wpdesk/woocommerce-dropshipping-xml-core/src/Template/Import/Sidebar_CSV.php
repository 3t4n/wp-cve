<?php

namespace DropshippingXmlFreeVendor;

/**
 * @var WPDesk\View\Renderer\Renderer $renderer
 * @var string $title
 * @var int    $item_nr item nr (page nr)
 * @var int    $items   all items count
 * @var array $table_data
 */
?>
<div id="sidebar-csv" class="postbox ">
	<h2 class="hndle ui-sortable-handle"><span><?php 
echo \esc_html($title);
?></span></h2>
	<div class="inside">
		<div id="dropshipping-table-wrapper">
			<table class="form-table import-preview-table margin-auto bg-white">
				<tbody>
				<tr valign="top">
					<td class="forminp txt-center" colspan="2">
						<fieldset>
							<a href="#" class="dashicons dashicons-arrow-left-alt2 to-left" id="dropshipping-item-position-arrow-left"></a>
							<input type="number" class="input-text regular-input padding-xs" name="item_nr" id="dropshipping-item-nr"
								value="<?php 
echo \esc_html($item_nr);
?>"> <span
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
				<tr>
					<td class="forminp width-100 padding-xs" colspan="2">
						<div id="dropshipping-sidebar-content">
							<div id="dropshipping-csv-table">
								<?php 
$renderer->output_render('Import/ImportSelectorCsvTable', $table_data);
?>
							</div>
						</div>
					</td>
				</tr>
				</tbody>
			</table>
			<div id="dropshipping-table-loader" style="display: none">
				<div class="absolute-centered">
					<img src="<?php 
echo \esc_url(\includes_url() . 'js/tinymce/skins/lightgray/img/loader.gif');
?>"/>
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
