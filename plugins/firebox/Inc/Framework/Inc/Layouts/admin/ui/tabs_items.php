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
$vertical = (bool) $this->data->get('vertical', false);
$selected_tab = $this->data->get('selected_tab', '');
$tabs_content_class = $this->data->get('tabs_content_class', null);
$tabs_content_class = $vertical && !$tabs_content_class ? ['large-10'] : $tabs_content_class;
$content = $this->data->get('content');
?>
<div class="cell<?php echo $tabs_content_class ? ' ' . esc_attr(implode(' ', $tabs_content_class)) : ''; ?> fpf-tabs-content">
	<?php
	$i = 0;
	foreach ($tabs as $tab_name => $tab_title)
	{
		$tab_data = $content->$tab_name;
		$class = $tab_data->class;
		?>
		<div
			id="fpf-tab-<?php echo esc_attr($tab_name); ?>"
			class="fpf-tab-item<?php echo $class ? ' ' . esc_attr($class) : ''; ?><?php echo (($i == 0 && empty($selected_tab)) || $tab_name == $selected_tab) ? ' is-active' : ''; ?>"
			>
			<?php echo $tab_data->content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<?php
		$i++;
	}
	?>
</div>