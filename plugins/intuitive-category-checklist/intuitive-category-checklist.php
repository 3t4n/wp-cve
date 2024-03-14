<?php
/*
  Plugin Name: Intuitive Category Checklist
  Plugin URI: http://www.crunchingpixels.com/portfolio/2011/07/intuitive-category-checklist/
  Description: Intuitive Category Checklist makes selecting categories easier for sites with a large amount of categories and sub-categorization. This plugin works in both quick edit mode and stand full post mode.
  Author: Dave Bergschneider
  Version: 1.0.2
  Author URI: http://www.cmscode.com
 */

define('ICC_PATH', plugin_dir_path(__FILE__));
define('ICC_URL', plugins_url('', __FILE__));

add_action('admin_print_scripts', 'icc_print_scripts');
add_action('admin_print_styles', 'icc_print_styles');

function icc_print_scripts() {
	wp_enqueue_script('icc_script', ICC_URL . '/assets/icc.js');
}

function icc_print_styles() {
	wp_enqueue_style('icc_style', ICC_URL . '/assets/icc.css');
}

add_action('add_meta_boxes', 'icc_taxonomy_replace_box');

function icc_taxonomy_replace_box($post_type) {
	foreach (get_object_taxonomies($post_type) as $tax_name) {
		$taxonomy = get_taxonomy($tax_name);
		if (!$taxonomy->show_ui || !$taxonomy->hierarchical)
			continue;

		$label = isset($taxonomy->label) ? esc_attr($taxonomy->label) : $tax_name;

		remove_meta_box($tax_name . 'div', $post_type, 'side');

		// don't use 'core' as priority
		add_meta_box($tax_name . 'div', $label, 'icc_taxonomy_meta_box', $post_type, 'side', 'high', array('taxonomy' => $tax_name));
	}
}

function icc_taxonomy_meta_box($post, $box) {
	$defaults = array('taxonomy' => 'category');
	if (!isset($box['args']) || !is_array($box['args']))
		$args = array();
	else
		$args = $box['args'];
	extract(wp_parse_args($args, $defaults), EXTR_SKIP);
	$tax = get_taxonomy($taxonomy);
	?>
	<div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">
		<ul id="<?php echo $taxonomy; ?>-tabs" class="category-tabs">
			<li class="tabs"><a href="#<?php echo $taxonomy; ?>-all" tabindex="3"><?php echo $tax->labels->all_items; ?></a></li>
			<li class="hide-if-no-js"><a href="#<?php echo $taxonomy; ?>-pop" tabindex="3"><?php _e('Most Used'); ?></a></li>
		</ul>

		<div id="<?php echo $taxonomy; ?>-pop" class="tabs-panel" style="display: none;">
			<ul id="<?php echo $taxonomy; ?>checklist-pop" class="categorychecklist form-no-clear" >
				<?php $popular_ids = wp_popular_terms_checklist($taxonomy); ?>
			</ul>
		</div>

		<div id="<?php echo $taxonomy; ?>-all" class="tabs-panel">
			<?php
			$name = ( $taxonomy == 'category' ) ? 'post_category' : 'tax_input[' . $taxonomy . ']';
			echo "<input type='hidden' name='{$name}[]' value='0' />"; // Allows for an empty term set to be sent. 0 is an invalid Term ID and will be ignored by empty() checks.
			?>
			<ul id="<?php echo $taxonomy; ?>checklist" class="list:<?php echo $taxonomy ?> categorychecklist form-no-clear">
				<?php wp_terms_checklist($post->ID, array('taxonomy' => $taxonomy, 'popular_cats' => $popular_ids, 'checked_ontop' => false)) ?>
				<?php /* ^ only change */ ?>
			</ul>
		</div>
		<?php if (!current_user_can($tax->cap->assign_terms)) : ?>
			<p><em><?php _e('You cannot modify this taxonomy.'); ?></em></p>
		<?php endif; ?>
		<?php if (current_user_can($tax->cap->edit_terms)) : ?>
			<div id="<?php echo $taxonomy; ?>-adder" class="wp-hidden-children">
				<h4>
					<a id="<?php echo $taxonomy; ?>-add-toggle" href="#<?php echo $taxonomy; ?>-add" class="hide-if-no-js" tabindex="3">
						<?php
						/* translators: %s: add new taxonomy label */
						printf(__('+ %s'), $tax->labels->add_new_item);
						?>
					</a>
				</h4>
				<p id="<?php echo $taxonomy; ?>-add" class="category-add wp-hidden-child">
					<label class="screen-reader-text" for="new<?php echo $taxonomy; ?>"><?php echo $tax->labels->add_new_item; ?></label>
					<input type="text" name="new<?php echo $taxonomy; ?>" id="new<?php echo $taxonomy; ?>" class="form-required form-input-tip" value="<?php echo esc_attr($tax->labels->new_item_name); ?>" tabindex="3" aria-required="true"/>
					<label class="screen-reader-text" for="new<?php echo $taxonomy; ?>_parent">
						<?php echo $tax->labels->parent_item_colon; ?>
					</label>
					<?php wp_dropdown_categories(array('taxonomy' => $taxonomy, 'hide_empty' => 0, 'name' => 'new' . $taxonomy . '_parent', 'orderby' => 'name', 'hierarchical' => 1, 'show_option_none' => '&mdash; ' . $tax->labels->parent_item . ' &mdash;', 'tab_index' => 3)); ?>
					<input type="button" id="<?php echo $taxonomy; ?>-add-submit" class="add:<?php echo $taxonomy ?>checklist:<?php echo $taxonomy ?>-add button category-add-sumbit" value="<?php echo esc_attr($tax->labels->add_new_item); ?>" tabindex="3" />
					<?php wp_nonce_field('add-' . $taxonomy, '_ajax_nonce-add-' . $taxonomy, false); ?>
					<span id="<?php echo $taxonomy; ?>-ajax-response"></span>
				</p>
			</div>
		<?php endif; ?>
	</div>
	<?php
}