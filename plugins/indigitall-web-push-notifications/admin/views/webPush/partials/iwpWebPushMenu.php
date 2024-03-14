<?php
	$menuItems = isset($menuItems) ? $menuItems : array();
?>

<div class="iwp-admin-webPush-menu">
	<?php
		foreach ($menuItems as $item) {
			echo("<a href='{$item['link']}' target='_self' class='iwp-admin-webPush-menu-item {$item['currentPage']}'>{$item['name']}</a>");
		}
	?>
</div>