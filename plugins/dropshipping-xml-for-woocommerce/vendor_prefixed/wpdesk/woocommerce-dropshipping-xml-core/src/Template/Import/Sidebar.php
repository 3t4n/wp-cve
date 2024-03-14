<?php

namespace DropshippingXmlFreeVendor;

/**
 * @var string $title
 * @var int    $item_nr item nr (page nr)
 * @var int    $items   all items count
 * @var string $element
 * @var string $content
 */
?>
<div id="sidebar-box" class="postbox ">
	<h2 class="hndle ui-sortable-handle"><span><?php 
echo \esc_html($title);
?></span></h2>
	<div class="inside">
		<?php 
$form->show_field('import_name');
?>
	</div>
</div>
<?php 
