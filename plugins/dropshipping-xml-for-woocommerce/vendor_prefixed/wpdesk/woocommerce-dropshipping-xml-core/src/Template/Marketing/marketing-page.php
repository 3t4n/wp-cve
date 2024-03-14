<?php

namespace DropshippingXmlFreeVendor;

use DropshippingXmlFreeVendor\WPDesk\Library\FlexibleQuantityCore\PluginConfig;
use DropshippingXmlFreeVendor\WPDesk\Library\Marketing\Boxes\MarketingBoxes;
/**
 * @var MarketingBoxes $boxes
 * @var bool $is_pl
 * @var string $marketing_slug
 */
$boxes = $params['boxes'] ?? \false;
if (!$boxes) {
    return;
}
if ($marketing_slug === 'woocommerce-dropshipping-xml') {
    $get_support_url = $is_pl ? 'https://wpde.sk/dropshipping-pro-support-pl' : 'https://wpde.sk/dropshipping-pro-support';
} else {
    $get_support_url = 'https://wordpress.org/support/plugin/dropshipping-xml-for-woocommerce/';
}
$share_ideas_url = $is_pl ? 'https://wpde.sk/dropshipping-pro-idea-pl' : 'https://wpde.sk/dropshipping-pro-idea';
?>
<div class="wrap">
	<div id="marketing-page-wrapper">
		<?php 
echo $boxes->get_boxes()->get_all();
//phpcs:ignore
?>

		<div class="marketing-buttons">
			<a class="button button-primary button-support" target="_blank" href="<?php 
echo \esc_url($get_support_url);
?>"><?php 
\esc_html_e('Get support', 'dropshipping-xml-for-woocommerce');
?></a>
			<a class="button button-primary button-idea" target="_blank" href="<?php 
echo \esc_url($share_ideas_url);
?>"><?php 
\esc_html_e('Share idea', 'dropshipping-xml-for-woocommerce');
?></a>
		</div>
	</div>
</div>
<?php 
