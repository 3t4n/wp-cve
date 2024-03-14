<?php defined('ABSPATH') || exit;
require_once('helper.php');
extract($settings);
// button settings

$btn_text			= !empty($shopengine_comparison_btn_text['desktop']) ? $shopengine_comparison_btn_text['desktop'] : '';
$icon				= !empty($shopengine_comparison_btn_icon['desktop']) ? $shopengine_comparison_btn_icon['desktop'] : '';
$icon_position		= !empty($shopengine_comparison_btn_icon_position['desktop']) ? $shopengine_comparison_btn_icon_position['desktop'] : 'left';
$show_counter		= $shopengine_comparison_btn_show_counter['desktop'] == true ? true : false;
$counter_position	= $shopengine_comparison_btn_counter_position['desktop'] ? $shopengine_comparison_btn_counter_position['desktop'] : 'right';
$show_counter_badge	= $shopengine_comparison_btn_show_counter_badge['desktop'] === true ? true : false;
$comparison_lists	= !empty($_COOKIE['shopengine_comparison_id_list']) ? explode(',', sanitize_text_field( wp_unslash( $_COOKIE['shopengine_comparison_id_list'] ) )) : [];
$total_products		= count(array_filter($comparison_lists));
$counter_class		= ($show_counter_badge === true) ? 'shopengine-comparison-counter comparison-counter-badge' : 'shopengine-comparison-counter';

$button = [
	'href' => '#',
	'data-payload' => [
		"pid" => 0
	],
	'class' => 'shopengine_comparison_add_to_list_action comparison-button'
];
$counter = [
	'class' => $counter_class,
]

?>

<div class="shopengine shopengine-widget">
	<div class="shopengine-comparison-button">
		<a <?php shopengine_content_render(print_attribute($button)); ?>>
			<?php if ($show_counter === true && $counter_position === 'left') : ?>
				<span <?php shopengine_content_render(print_attribute($counter)); ?>><?php echo esc_html($total_products); ?></span>
			<?php endif; ?>

			<?php if ($shopengine_comparison_btn_show_icon['desktop'] == true && $icon_position === 'left') :
				render_icon($icon, ['aria-hidden' => 'true']);
			endif; ?>

			<?php if (!empty($btn_text)) :
				echo esc_html($btn_text);
			endif; ?>

			<?php if ($shopengine_comparison_btn_show_icon['desktop'] === true && $icon_position === 'right') :
				render_icon($icon, ['aria-hidden' => 'true']);
			endif; ?>

			<?php if ($show_counter === true && $counter_position === 'right') : ?>
				<span <?php shopengine_content_render(print_attribute($counter)); ?>><?php echo esc_html($total_products); ?></span>
			<?php endif; ?>
		</a>

	</div>

</div>