<?php

namespace ZPOS\Admin\Woocommerce;

class Categories
{
	const DEFAULT_CATEGORY_NAME_COLOR = '#1E1E1E';
	const DEFAULT_CATEGORY_COUNT_COLOR = '#737373';
	const DEFAULT_CATEGORY_TILE_BACKGROUND_COLOR = '#FFFFFF';

	const CATEGORY_OVERRIDE_GLOBAL_STYLING_NAME = 'pos_category_override_global_styling';
	const CATEGORY_NAME_COLOR_NAME = 'pos_category_name_color';
	const CATEGORY_COUNT_COLOR_NAME = 'pos_category_count_color';
	const CATEGORY_TILE_BACKGROUND_COLOR_NAME = 'pos_category_tile_background_color';

	public function __construct()
	{
		add_action('product_cat_add_form_fields', [$this, 'add_new_meta_field'], 1, 1);
		add_action('product_cat_edit_form_fields', [$this, 'edit_meta_field'], 10, 1);
		add_action('edited_product_cat', [$this, 'save_taxonomy_meta'], 10, 1);
		add_action('create_product_cat', [$this, 'save_taxonomy_meta'], 10, 1);
	}

	public function add_new_meta_field()
	{
		?>
				<div class="form-field">
						<label><?php _e('POS category tile', 'zpos-wp-api'); ?></label>
						<?php $this->render_meta_fields(); ?>
				</div>
				<?php
	}

	public function edit_meta_field($term)
	{
		?>
				<tr class="form-field">
						<th scope="row"><label><?php _e('POS category tile', 'zpos-wp-api'); ?></label></th>
						<td><?php $this->render_meta_fields($term); ?> </td>
				</tr>
				<?php
	}

	public function render_meta_fields($term = null)
	{
		$inputs = self::get_stylization_inputs();
		foreach ($inputs as $input) {

			$meta_value = $term ? get_term_meta($term->term_id, $input['name'], true) : null;
			$value = $meta_value ? $meta_value : $input['default_value'];
			$is_checkbox = isset($input['checkbox']) && $input['checkbox'];
			?>
						<div class="<?= $is_checkbox ? 'zpos-styling-checkbox' : '' ?>">
								<?php if ($is_checkbox) { ?>
										<input type="checkbox" class="zpos-styling-checkbox" name="<?= $input['name'] ?>" <?= $value
	? 'checked'
	: '' ?> />
								<?php } else { ?>
										<input type="text" name="<?= $input['name'] ?>" class="zpos-color-picker" value="<?= $value ?>">
								<?php } ?>
								<span><?= $input['label'] ?></span>
						</div>
				<?php
		}
	}

	public function save_taxonomy_meta($term_id)
	{
		$names = [
			self::CATEGORY_OVERRIDE_GLOBAL_STYLING_NAME,
			self::CATEGORY_NAME_COLOR_NAME,
			self::CATEGORY_COUNT_COLOR_NAME,
			self::CATEGORY_TILE_BACKGROUND_COLOR_NAME,
		];
		foreach ($names as $name) {
			if (isset($_POST[$name])) {
				update_term_meta($term_id, $name, $_POST[$name]);
			} elseif ($name === self::CATEGORY_OVERRIDE_GLOBAL_STYLING_NAME) {
				update_term_meta($term_id, $name, '');
			}
		}
	}

	public static function get_stylization($category_id)
	{
		$styles = self::get_stylization_inputs();
		$response = [];
		foreach ($styles as $style) {
			$name = $style['name'];
			$value = get_term_meta($category_id, $style['name'], true);
			$response[$name] = $value ? $value : $style['default_value'];
		}
		return $response;
	}

	public static function get_stylization_inputs()
	{
		return [
			[
				'label' => __('Override Global Color Styling', 'zpos-wp-api'),
				'name' => self::CATEGORY_OVERRIDE_GLOBAL_STYLING_NAME,
				'default_value' => false,
				'checkbox' => true,
			],
			[
				'label' => __('Category name', 'zpos-wp-api'),
				'name' => self::CATEGORY_NAME_COLOR_NAME,
				'default_value' => self::DEFAULT_CATEGORY_NAME_COLOR,
			],
			[
				'label' => __('Category count', 'zpos-wp-api'),
				'name' => self::CATEGORY_COUNT_COLOR_NAME,
				'default_value' => self::DEFAULT_CATEGORY_COUNT_COLOR,
			],
			[
				'label' => __('Tile background', 'zpos-wp-api'),
				'name' => self::CATEGORY_TILE_BACKGROUND_COLOR_NAME,
				'default_value' => self::DEFAULT_CATEGORY_TILE_BACKGROUND_COLOR,
			],
		];
	}

	public static function normalize_slug($slug)
	{
		return str_replace('%', '_', $slug);
	}
}
