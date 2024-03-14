<?php

namespace Dotdigital_WordPress_Vendor;

/**
 * @package    Dotdigital_WordPress
 *
 * @var array $tabs
 * @var string $active_tab
 * @var string $page_slug
 */
?>

<h2 class='nav-tab-wrapper'>
	<?php 
foreach ($tabs as $tab_key => $tab_item) {
    ?>
		<a
			href='?page=<?php 
    echo esc_attr($page_slug);
    ?>&tab=<?php 
    echo esc_attr($tab_item->get_url_slug());
    ?>'
			class="nav-tab <?php 
    echo $active_tab == $tab_item->get_url_slug() ? 'nav-tab-active' : '';
    ?>">
			<?php 
    echo esc_html($tab_item->get_title());
    ?>
		</a>
	<?php 
}
?>
</h2>
<?php 
