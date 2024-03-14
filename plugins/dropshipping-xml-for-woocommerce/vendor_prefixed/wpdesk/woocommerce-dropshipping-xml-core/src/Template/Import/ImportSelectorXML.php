<?php

namespace DropshippingXmlFreeVendor;

/**
 * @var array  $all_elements
 * @var string $item_element
 * @var string $rendered_xml
 */
?>

<tr>
	<td class="forminp v-align-top" style="width:25%">
		<ul id="dropshipping-preview-item-list">
			<?php 
foreach ($all_elements as $value) {
    ?>
				<li><a href="#" class="dropshipping-preview-item-button <?php 
    echo \esc_attr($value['full_name'] === $item_element ? 'active' : '');
    ?>"
					data-id="<?php 
    echo \esc_attr($value['full_name']);
    ?>"><?php 
    echo \esc_html(\str_replace(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Analyser\XmlAnalyser::DEFAULT_PREFIX_NAME . ':', '', $value['full_name']));
    ?> <span class="preview-item-counter"><?php 
    echo \esc_html($value['count']);
    ?></span></a></li>
			<?php 
}
?>
		</ul>
	</td>
	<td class="forminp v-align-top">
		<div id="xml-view"></div>
		<script>
			var dropshipping_xml_data = <?php 
echo \json_encode($rendered_xml);
?>;
		</script>
	</td>
</tr>
<?php 
