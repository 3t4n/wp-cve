<?php 

namespace app\Admin\Meta;

/* Exit if accessed directly. */
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Taxonomy class for meta box. This contains the meta box,
 * HTML, form fields, everything to have SimpleSEO within a
 * taxonomy.
 *
 * @since  2.0.0
 */
class Taxonomy {
	
	/**
	 * Adds meta fields for a editing a taxonomy
	 * Includes canonical URL
	 *
	 * @since  2.0.0
	 */
    public function editMetaBox($term) {
		$term_meta = get_option("taxonomy_".$term->term_id);
				
		$meta_title = null;
		if (isset($term_meta['sseo_title'])) {
			$meta_title = $term_meta['sseo_title'];
		}
		
		$meta_description = null;
		if (isset($term_meta['sseo_description'])) {
			$meta_description = $term_meta['sseo_description'];
		}
		
		$sseo_robot_noindex = null;
		if (isset($term_meta['sseo_robot_noindex'])) {
			$sseo_robot_noindex = $term_meta['sseo_robot_noindex'];
		}
		
		$sseo_robot_nofollow = null;
		if (isset($term_meta['sseo_robot_nofollow'])) {
			$sseo_robot_nofollow = $term_meta['sseo_robot_nofollow'];
		}
		
		$canonical_url = null;
		if (!empty($term_meta['sseo_canonical_url'])) {
			$canonical_url = $term_meta['sseo_canonical_url'];
		}

		echo '
		<tr class="form-field">
			<th scope="row" valign="top"><label for="sseo_title">'.__('SEO Title', SSEO_TXTDOMAIN).'</label></th>
			<td><input type="text" name="term_meta[sseo_title]" id="sseo_title" value="'.esc_attr($meta_title).'"></td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="sseo_description">'.__('SEO Description', SSEO_TXTDOMAIN).'</label></th>
			<td><input type="text" name="term_meta[sseo_description]" id="sseo_description" value="'.esc_attr($meta_description).'"></td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="sseo_robot_noindex">'.__('Robots Meta NOINDEX', SSEO_TXTDOMAIN).'</label></th>
			<td><input type="hidden" name="term_meta[sseo_robot_noindex]" value="0" /><input type="checkbox" name="term_meta[sseo_robot_noindex]" id="sseo_robot_noindex" value="1" '.(!empty($sseo_robot_noindex) ? 'checked="checked"' : '').'></td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="sseo_robot_nofollow">'.__('Robots Meta NOFOLLOW', SSEO_TXTDOMAIN).'</label></th>
			<td><input type="hidden" name="term_meta[sseo_robot_nofollow]" value="0" /><input type="checkbox" name="term_meta[sseo_robot_nofollow]" id="sseo_robot_nofollow" value="1" '.(!empty($sseo_robot_nofollow) ? 'checked="checked"' : '').'></td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="sseo_canonical_url">'.__('Canonical URL', SSEO_TXTDOMAIN).'</label></th>
			<td><input type="text" name="term_meta[sseo_canonical_url]" id="sseo_canonical_url" value="'.esc_attr($canonical_url).'"></td>
		</tr>
		';
	}
	
	/**
	 * Adds meta fields for a new taxonomy
	 * Includes canonical URL
	 *
	 * @since  2.0.0
	 */
	public function newMetaBox() {
		echo '<div class="form-field">
			<label for="term_meta[sseo_title]">'.__('SEO Title', SSEO_TXTDOMAIN).'</label>
			<input type="text" name="term_meta[sseo_title]" id="term_meta[sseo_title]" value="">
		</div>
		<div class="form-field">
			<label for="term_meta[sseo_description]">'.__('SEO Description', SSEO_TXTDOMAIN).'</label>
			<input type="text" name="term_meta[sseo_description]" id="term_meta[sseo_description]" value="">
		</div>
		<div class="form-field">
			<label for="term_meta[sseo_robot_noindex]" style="display: inline-block;">'.__('Robots Meta NOINDEX', SSEO_TXTDOMAIN).'</label>
			<input type="checkbox" name="term_meta[sseo_robot_noindex]" id="term_meta[sseo_robot_noindex]" value="1">
		</div>
		<div class="form-field">
			<label for="term_meta[sseo_robot_nofollow]" style="display: inline-block;">'.__('Robots Meta NOFOLLOW', SSEO_TXTDOMAIN).'</label>
			<input type="checkbox" name="term_meta[sseo_robot_nofollow]" id="term_meta[sseo_robot_nofollow]" value="1">
		</div>
		<div class="form-field">
			<label for="term_meta[sseo_canonical_url]">'.__('Canonical URL', SSEO_TXTDOMAIN).'</label>
			<input type="text" name="term_meta[sseo_canonical_url]" id="term_meta[sseo_canonical_url]" value="">
		</div>';
	}
	
}

?>