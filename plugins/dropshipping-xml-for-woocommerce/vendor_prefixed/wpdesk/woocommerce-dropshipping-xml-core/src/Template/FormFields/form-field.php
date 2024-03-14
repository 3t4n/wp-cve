<?php

namespace DropshippingXmlFreeVendor;

/**
 * @var \WPDesk\Forms\Field $field
 * @var \WPDesk\View\Renderer\Renderer $renderer
 * @var string $name_prefix
 * @var string $value
 *
 * @var string $template_name Real field template.
 */
?>

<tr valign="top">
	<?php 
if ($field->has_label()) {
    $renderer->output_render('form-label', ['field' => $field]);
}
?>

	<td class="forminp">
		<?php 
$renderer->output_render($template_name, ['field' => $field, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $value]);
?>

		<?php 
if ($field->has_description()) {
    ?>
			<p class="description">
			<?php 
    echo \wp_kses_post($field->get_description());
    ?>
			</p>
			<?php 
}
?>
	</td>
</tr>
<?php 
