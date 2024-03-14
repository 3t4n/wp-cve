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
$control_inner_class = $this->data->get('control_inner_class', []);

$exclude_rules = $this->data->get('exclude_rules', []);
$exclude_rules_pro = $this->data->get('exclude_rules_pro', false);

$multiple = $this->data->get('multiple', true);
$multiple = !$multiple ? 0 : 1;

// If we only accept single values, make the results auto appear by default without requiring a click.
if (!$multiple)
{
	$control_inner_class[] = 'clicked';
}

$control_inner_class = count($control_inner_class) ? ' ' . implode(' ', $control_inner_class) : '';

$lazyload = $this->data->get('lazyload', false);

$local_search = $this->data->get('local_search', false);
$local_search = !$local_search ? 0 : 1;

$hide_ids = $this->data->get('hide_ids', true);
$hide_ids = !$hide_ids ? 0 : 1;

$hide_flags = $this->data->get('hide_flags', false);
$hide_flags = !$hide_flags ? 0 : 1;

$path = $this->data->get('path', '\FPFramework\Helpers\SearchDropdownBaseHelper');
if (!$path)
{
	return;
}

$items = $this->data->get('items', []);
if ($items)
{
	$items = \FPFramework\Helpers\SearchDropdownHelper::parseData($path, $items);
}

$selected_items = $this->data->get('selected_items', []);
if (empty($selected_items))
{
	$default = $this->data->get('default', []);
	$value = $this->data->get('value');
	$selected_items = \FPFramework\Helpers\SearchDropdownHelper::getSelectedItems($path, $value, $items);
}

$placeholder = $multiple ? ' placeholder="' . esc_attr($this->data->get('placeholder')). '"' : '';
$is_multiple = $multiple ? ' is-multiple' : '';

$allowed_img_tags = [
	'img' => [
		'src' => true,
		'alt' => true,
		'style' => true
	]
];
?>
<div class="fpf-field-item fpf-control-group-field fpf-searchdropdown-wrapper<?php esc_attr_e($is_multiple . $control_inner_class); ?>"
	<?php if ($local_search): ?>
	data-local-search="<?php esc_attr_e($local_search); ?>"
	<?php endif; ?>
	data-hide-flags="<?php esc_attr_e($hide_flags); ?>"
	data-hide-ids="<?php esc_attr_e($hide_ids); ?>"
	data-multiple="<?php esc_attr_e($multiple); ?>">
	<?php
	if (!$multiple)
	{
		$single_value_label = $selected_items && count($selected_items) && isset($selected_items[0]['title']) ? $selected_items[0]['title'] : '';
		?>
		<div class="fpf-searchdropdown-popup-toggle<?php echo !empty($single_value_label) && $single_value_label !== $this->data->get('placeholder') ? ' has-value' : ''; ?>">
			<span class="label" data-placeholder="<?php esc_attr_e($this->data->get('placeholder', '&nbsp;')); ?>"><?php echo $single_value_label ? esc_attr($single_value_label) : esc_attr($this->data->get('placeholder', '&nbsp;')); ?></span>
			<div class="actions">
				<?php if ($this->data->get('can_clear')): ?>
					<i class="remove dashicons fpf-searchdropdown-remove-single-value-button dashicons-no-alt"></i>
				<?php endif; ?>
				<i class="toggle dashicons dashicons-arrow-down-alt2"></i>
			</div>
		</div>
		<div class="fpf-searchdropdown-popup <?php esc_attr_e(implode(' ', $this->data->get('popup_class', []))); ?>">
		<?php
	}
	?>
	<span class="fpf-searchdropdown-spinner"></span>
	<ul class="fpf-searchdropdown-search-choices">
		<li class="hidden-template">
			<span class="text"></span>
			<a href="#" class="dashicons dashicons-dismiss icon fpf-searchdropdown-search-results-remove-item"></a>
			<input type="hidden" data-name="<?php esc_attr_e($this->data->get('name') . ($multiple ? '[]' : '')); ?>" value="" />
		</li>
		<?php
		if (is_array($selected_items) && count($selected_items))
		{
			foreach ($selected_items as $item)
			{
				$item = (array) $item;
				$lang = isset($item['lang']) ? $item['lang'] : '';

				$lang_img = !$hide_flags && !empty($lang) ? \FPFramework\Helpers\WPHelper::getWPMLFlagUrlFromCode($lang) : '';
				?>
				<li class="result-item<?php echo !$multiple ? ' hide' : ''; ?>">
					<span class="text"><?php echo esc_html($item['title']); ?><span class="meta"><?php echo wp_kses($lang_img, $allowed_img_tags); ?></span></span>
					<a href="#" class="dashicons dashicons-dismiss icon fpf-searchdropdown-search-results-remove-item"></a>
					<input type="hidden" class="fpf-selected-dropdown-value" name="<?php esc_attr_e($this->data->get('name') . ($multiple ? '[]' : '')); ?>" value="<?php esc_attr_e($item['id']); ?>" />
				</li>
				<?php
			}
		}
		?>
		<li>
			<input type="text"<?php echo wp_kses_data($placeholder); ?> placeholder="<?php esc_attr_e($this->data->get('search_query_placeholder', '')); ?>" class="fpf-control-input-item fpf-searchdropdown-search-input<?php esc_attr_e($this->data->get('input_class')); ?>"<?php echo ($this->data->get('path')) ? ' data-path="' . esc_attr($path) . '"' : ''; ?> />
		</li>
	</ul>
	<!-- Holds results retrieved after typing a keyword -->
	<div class="fpf-searchdropdown-search-results"></div>
	<!-- Holds fixed set of results retrieved on page load -->
	<div class="fpf-searchdropdown-search-results fpf-searchdropdown-search-results-on-click on-click<?php echo (!$multiple ? ' is-visible' : '') . ($lazyload ? ' lazyload' : ''); ?>">
	<?php if (count($items)): ?>
		<ul>
		<?php
		$lock_key = '<i class="dashicons dashicons-lock"></i>';
		foreach ($items as $key => $value)
		{
			$selected_items_ids = $selected_items ? array_column($selected_items, 'id') : [];

			$value = (array) $value;
			$index = $selected_items ? strval(array_search($value['id'], $selected_items_ids)) : '';
			$item_class = $index != '' ? 'is-disabled' : '';
			$lang = isset($value['lang']) ? $value['lang'] : '';

			$lang_img = !$hide_flags && !empty($lang) ? \FPFramework\Helpers\WPHelper::getWPMLFlagUrlFromCode($lang) : '';

			if (is_object($value['title']))
			{
				?><li class="is-separator"><?php esc_html_e($value['id']); ?></li><?php
				foreach ((array) $value['title'] as $_key => $_value)
				{
					$is_selected = strval(array_search($_key, $selected_items_ids));;
					$inner_item_class = $is_selected != '' ? 'is-disabled' : '';
					$label = $_value;
					$data_id = $_key;
					$extra_atts = '';
					if ($exclude_rules_pro && in_array($_key, $exclude_rules))
					{
						$label = $label . $lock_key;
						$inner_item_class .= ' prevent-click is-pro fpf-modal-opener';
						$extra_atts = ' data-fpf-modal-item="' . esc_attr($_value . ' ' . fpframework()->_('FPF_CONDITION')) . '" data-fpf-modal="#fpfUpgradeToPro" data-fpf-plugin="' . esc_attr(fpframework()->_($this->data->get('plugin'))) . '"';
					}
		
					?><li data-id="<?php esc_attr_e($data_id); ?>" class="<?php esc_attr_e($inner_item_class); ?>"<?php echo wp_kses_data($extra_atts); ?>><span class="title"><?php echo wp_kses($label, wp_kses_allowed_html('post')); ?></span><?php echo (!$hide_ids) ? '<span class="meta"><span class="text fpf-badge">' . esc_html($_key) . '</span></span>' : ''; ?></li><?php
				}
			}
			else
			{
				?><li data-id="<?php esc_attr_e($value['id']); ?>" class="<?php esc_attr_e($item_class); ?>"><span class="title"><?php echo esc_html($value['title']); ?></span><?php echo (!$hide_ids) ? '<span class="meta"><span class="text fpf-badge">' . esc_html($value['id']) . '</span>' . wp_kses($lang_img, $allowed_img_tags) . '</span>' : ''; ?></li><?php
			}
		}
		?>
		</ul>
	<?php else: ?>
		<ul><li class="skip"><?php echo esc_html(fpframework()->_('FPF_NO_ITEMS_FOUND')); ?></li></ul>
	<?php endif; ?>
	</div>
	<input type="hidden" class="nonce_hidden" value="<?php echo wp_create_nonce( 'fpf-pa-search-nonce' ); ?>" />
	<?php
	if (!$multiple)
	{
		?></div><?php
	}
	?>
</div>