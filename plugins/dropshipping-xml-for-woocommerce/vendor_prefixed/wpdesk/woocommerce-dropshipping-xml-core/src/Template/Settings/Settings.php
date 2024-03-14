<?php

namespace DropshippingXmlFreeVendor;

/**
 * @var \WPDesk\Library\DropshippingXmlCore\Infrastructure\View\FormView $form
 * $var string $title
 */
$renderer->output_render('Header', ['title' => $title]);
$form->form_start();
?>
		<tr>
			<td colspan="2" style="padding:0">
				<h3 class="wc-settings-sub-title "><?php 
echo \esc_html(\__('Products', 'dropshipping-xml-for-woocommerce'));
?></h3>
			</td>
		</tr>
		<?php 
$form->show_field('products_in_batch', ['parent_template' => 'form-field']);
?>

		<?php 
$form->show_field('save', ['parent_template' => 'submit']);
$form->form_fields_complete();
$form->form_end();
?>

<?php 
$renderer->output_render('Footer');
