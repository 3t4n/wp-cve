<?php if ( ! defined( 'WPINC' ) ) die; ?>

<div class="wrap">
	<select name="product_brand">
	    <option value="0"><?php _e('Not specified', 'premmerce-brands'); ?></option>

	<?php foreach ($terms as $term) : ?>
	    <option value="<?php esc_attr_e($term->name); ?>" <?php selected($term->name, $name); ?>><?php esc_html_e($term->name); ?></option>
	<?php endforeach ?>
	</select>
</div>