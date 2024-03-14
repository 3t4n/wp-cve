<?php

namespace DropshippingXmlFreeVendor;

/**
 * @var \WPDesk\Forms\Field $field
 * @var \WPDesk\View\Renderer\Renderer $renderer
 * @var string $name_prefix
 * @var string $value
 * @var string $template_name
 */
?>
	<tr>
		<td style="padding-left:0;">
			<p class="submit">
				<?php 
$renderer->output_render($template_name, ['field' => $field, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $value]);
?>
			</p>
		</td>
	</tr>
<?php 
