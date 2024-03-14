<?php

namespace Dotdigital_WordPress_Vendor;

/**
 * Provide a public-facing view for lists
 *
 * @package    Dotdigital_WordPress
 *
 * @var array $lists
 * @var string $identifier
 * @var bool $has_visible_lists
 */
if (empty($lists)) {
    return;
}
if ($has_visible_lists) {
    ?>
<p style="margin:10px 0 10px 0;font-weight:bold;">Subscribe to:</p>
<?php 
}
?>
<div class="ddg-form-group">
	<?php 
foreach ($lists as $list) {
    ?>
		<?php 
    $list_id = $list['id'];
    $label = $list['label'];
    $is_visible = \is_string($list['isVisible']) ? $list['isVisible'] : $list['isVisible'];
    ?>
		<?php 
    if (!$is_visible) {
        ?>
			<input type="hidden" name="<?php 
        echo esc_attr($identifier);
        ?>[]" value="<?php 
        echo esc_attr(apply_filters('public/lists/' . $list_id . '/input/value', $list_id));
        ?>" readonly/>
		<?php 
    } else {
        ?>
			<div class="ddg-checkbox-group">
				<label for="<?php 
        echo esc_attr($identifier);
        ?>_<?php 
        echo esc_attr($list_id);
        ?>">
					<input
						type="checkbox"
						class="<?php 
        echo esc_attr(apply_filters('public/lists/' . $list_id . '/input/class', 'form-control list'));
        ?>"
						id="<?php 
        echo esc_attr($identifier);
        ?>_<?php 
        echo esc_attr($list_id);
        ?>"
						name="<?php 
        echo esc_attr($identifier);
        ?>[]"
						value="<?php 
        echo esc_attr(apply_filters('public/lists/' . $list_id . '/input/value', $list_id));
        ?>"
						<?php 
        echo esc_attr(apply_filters('public/lists/' . $list_id . '/input/attributes', ''));
        ?>
					/>
					<?php 
        echo esc_html($label);
        ?>
				</label>
			</div>
		<?php 
    }
    ?>
	<?php 
}
?>
</div>
<?php 
