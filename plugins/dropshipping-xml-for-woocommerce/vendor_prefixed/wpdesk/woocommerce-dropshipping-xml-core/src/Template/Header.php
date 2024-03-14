<?php

namespace DropshippingXmlFreeVendor;

/**
 * @var string $title
 * @var null|string $header_url
 */
?>
	<div class="wrap" id="dropshipping-xml-wrapper">
		<h1 class="wp-heading-inline"><?php 
echo \esc_html($title);
echo isset($header_url) ? \wp_kses_post($header_url) : '';
?></h1>
		<hr class="wp-header-end">
<?php 
