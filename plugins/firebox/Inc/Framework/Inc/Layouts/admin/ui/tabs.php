<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}
if (!$tabs = (array) $this->data->get('tabs'))
{
	return;
}
$id = $this->data->get('config.id', '');
$vertical = (bool) $this->data->get('config.vertical', false);
$tabs_menu_sticky = (bool) $this->data->get('config.tabs_menu_sticky', false);
$mobile_menu = (bool) $this->data->get('config.mobile_menu', false);
$class = $this->data->get('config.class', ['grid-x']);
$tabs_content_class = $this->data->get('config.tabs_content_class', null);
if ($mobile_menu)
{
	$class[] = 'has-mobile-menu';
}
if ($vertical)
{
	$class[] = 'grid-margin-x';
	$class[] = 'fpf-vertical-tabs';
}
else
{
	$tabs_content_class[] = 'margin-top-1';
}
$selected_tab = $this->data->get('config.selected_tab', '');
$idAttribute = !empty($id) ? ' id="' . esc_attr($id) . '"' : '';
$tabs_menu_class = $this->data->get('config.tabs_menu_class', null);
$tabs_menu_class = $vertical && !$tabs_menu_class ? ['large-2'] : $tabs_menu_class;

// get currently active tab
if ($id)
{
	// We check via cookie to find which tab has been selected
	$cookie = new \FPFramework\Libs\Cookie();
	// first check that we don't have a selected tab already given to us
	$selected_tab = $selected_tab ? $selected_tab : $cookie->get($id);
	// if selected tab is not null and exists in tabs list, use it, otherwise select first tab key if given tabs are not empty
	$first_tabs_key = '';
	if ($tabs && count($tabs))
	{
		$first_tabs_value = reset($tabs);
		$first_tabs_key = key($tabs);
	}
	$selected_tab = $selected_tab ? (isset($tabs[$selected_tab]) ? $selected_tab : $first_tabs_key) : $first_tabs_key;
}
?>
<div class="fpf-tabs-wrapper<?php echo $class ? ' ' . esc_attr(implode(' ', $class)) : ''; ?>"<?php echo wp_kses_data($idAttribute); ?>>
	<div class="cell<?php echo $tabs_menu_class ? ' ' . esc_attr(implode(' ', $tabs_menu_class)) : ''; ?> fpf-tabs-nav-wrapper">
		<?php if ($mobile_menu) { ?>
			<a href="#" class="fpf-tabs-nav-mobile-toggle">
				<span class="text-outer"></span>
				<span class="dashicons dashicons-arrow-down-alt2"></span>
			</a>
		<?php } ?>
		<div class="fpf-tabs-nav<?php echo $tabs_menu_sticky ? ' sticky' : ''; ?>">
			<ul class="fpf-tabs">
			<?php
			$i = 0;
			foreach ($tabs as $key => $value)
			{
				?><li id="fpf-tab-<?php echo esc_attr($key); ?>" class="fpf-tabs-nav-item<?php echo (($i == 0 && empty($selected_tab)) || $key == $selected_tab) ? ' is-active' : ''; ?>"><span class="text"><?php echo esc_html($value); ?></span></li><?php
				$i++;
			}
			?>
			</ul>
		</div>
	</div>
	<?php
	fpframework()->renderer->admin->render('ui/tabs_items', [
		'tabs' => $tabs,
		'vertical' => $vertical,
		'tabs_content_class' => $tabs_content_class,
		'content' => $this->data->get('content', ''),
		'selected_tab' => $selected_tab]
	);
	?>
</div>