<?php

namespace DropshippingXmlFreeVendor;

/**
 * @var string $title
 * @var bool $edit
 * @var \WPDesk\Library\DropshippingXmlCore\Entity\Import $import
 * @var string $import_manager_url
 * @var string $products_url
 * @var WPDesk\View\Renderer\Renderer $renderer
 */
$renderer->output_render('Header', ['title' => $title]);
?>
<hr>
	<table class="form-table import-status-table bg-white">
		<tbody>
		<tr valign="top">
			<td class="forminp txt-center">
				<h2><?php 
echo \esc_html(\__('Please wait, import in progress', 'dropshipping-xml-for-woocommerce'));
?></h2>
			</td>
		</tr>
		<tr valign="top">
			<td class="forminp txt-center">
				<p><?php 
echo \esc_html(\__('If you close your browser, the cron scheduler will complete the import process (it will take longer).', 'dropshipping-xml-for-woocommerce'));
?></p>
			</td>
		</tr>
		<tr valign="top">
			<td>
				<table class="form-table status-table">
					<tbody>
					<tr>
						<td class="width-40 txt-left"><p><?php 
echo \esc_html(\__('Time elapsed', 'dropshipping-xml-for-woocommerce'));
?>: <span id="timer">00:00:00</span></p></td>
						<td class="width-20 txt-center"><span id="import-progress"><?php 
echo \esc_html($import->get_formated_progress());
?></span></td>
						<td class="width-40 txt-right"><p>
								<?php 
echo \esc_html(\__('Created', 'dropshipping-xml-for-woocommerce'));
?>: <span id="import-created"><?php 
echo \esc_html($import->get_created());
?></span> |
								<?php 
echo \esc_html(\__('Updated', 'dropshipping-xml-for-woocommerce'));
?>: <span id="import-updated"><?php 
echo \esc_html($import->get_updated());
?></span> |
								<?php 
echo \esc_html(\__('Skipped', 'dropshipping-xml-for-woocommerce'));
?>: <span id="import-skipped"><?php 
echo \esc_html($import->get_skipped());
?></span> |
								<?php 
echo \esc_html(\__('of', 'dropshipping-xml-for-woocommerce'));
?> <span id="import-total"><?php 
echo \esc_html($import->get_products_count());
?></span></p></td>
					</tr>
					<tr>
						<td class="txt-left" colspan="3">
							<ul id="import-player" class="start">
								<li id="start-import"><a href="#"><?php 
echo \esc_html(\__('Start import', 'dropshipping-xml-for-woocommerce'));
?></a></li>
								<li id="stop-import"><a style="font-weight: bold; color: red;" href="#"><?php 
echo \esc_html(\__('Stop import', 'dropshipping-xml-for-woocommerce'));
?> </a></li>
							</ul>
						</td>
					</tr>
					<tr>
						<td class="txt-left" colspan="3">
							<p><?php 
echo \wp_kses_post(\__('Have you encountered any problems with the import or want to know more? <a href="https://wpde.sk/dropshipping-faq" class="docs-url" target="_blank">Visit FAQ &rarr;</a>.', 'dropshipping-xml-for-woocommerce'));
?></p>
						</td>
					</tr>
					</tbody>
				</table>
			</td>
		</tr>
		<tr valign="top">
			<td class="forminp">
				<fieldset id="log-container">
					<legend><?php 
echo \esc_html(\__('Import log', 'dropshipping-xml-for-woocommerce'));
?>:</legend>
					<div class="log-wrapper">
						<textarea class="width-100" id="log-viewer" rows="20" readonly></textarea>
					</div>
				</fieldset>
			</td>
		</tr>
		</tbody>
	</table>
<div id="navigation-wrapper" class="hidden">
	<hr>
	<table class="form-table">
		<tbody>
		<tr valign="top">
			<td class="forminp txt-center">
					<a href="<?php 
echo \esc_url($import_manager_url);
?>" class="button button-hero button-primary" name="button button-hero"><?php 
echo \esc_html(\__('Go to the import manager', 'dropshipping-xml-for-woocommerce'));
?></a>
					<a href="<?php 
echo \esc_url($products_url);
?>" class="button button-hero button-primary" name="button button-hero"><?php 
echo \esc_html(\__('Go to Products', 'dropshipping-xml-for-woocommerce'));
?></a>
			</td>
		</tr>
		</tbody>
	</table>
</div>
<?php 
$renderer->output_render('Footer');
