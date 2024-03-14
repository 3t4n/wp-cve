<?php

namespace Photonic_Plugin\Layouts;

use Photonic_Plugin\Components\Album_List;
use Photonic_Plugin\Components\Grid_Anchor;
use Photonic_Plugin\Components\Grid_Figure;
use Photonic_Plugin\Components\Grid_Image;
use Photonic_Plugin\Components\Pagination;
use Photonic_Plugin\Components\Photo_List;
use Photonic_Plugin\Layouts\Features\Can_Use_Lightbox;
use Photonic_Plugin\Platforms\Base;
use Photonic_Plugin\Components\Album;
use Photonic_Plugin\Components\Photo;

require_once 'Level_One_Gallery.php';
require_once 'Level_Two_Gallery.php';

require_once PHOTONIC_PATH . '/Components/Grid_Image.php';

require_once 'Features/Can_Use_Lightbox.php';

/**
 * Class Grid
 * This is the generic Grid layout for Photonic. This handles all grids except for slideshows. The following approaches are used for layouts:
 *  - CSS: Used for square, circle, masonry and justified grids (only when all images have sizes - pretty much every case except Instagram or SmugMug / Zenfolio galleries with missing thumbnails)
 *  - JS: Used for justified grids (only when at least one image is missing a size, e.g. Instagram with all sizes missing, or SmugMug with missing album thumbnails) and Mosaic
 * The Justified Grid CSS approach is based on the solution provided here: https://stackoverflow.com/a/49107319
 *
 * @package Photonic_Plugin\Layouts
 */
class Grid extends Core_Layout implements Level_One_Gallery, Level_Two_Gallery {
	use Can_Use_Lightbox;

	/**
	 * Generates the HTML for the lowest level gallery, i.e. the photos. This is used for local, modal and template displays.
	 * The code for the random layouts is handled in JS, but just the HTML markers for it are provided here.
	 *
	 * @param Photo_List $photo_list
	 * @param array $short_code
	 * @param $module Base
	 * @return string
	 */
	public function generate_level_1_gallery(Photo_List $photo_list, array $short_code, Base $module): string {
		$module->push_to_stack('Generate level 1 gallery');

		$short_code = array_merge($this->common_parameters, $short_code);

		$photos = $photo_list->photos;

		$lightbox = self::get_lightbox();

		$layout = $short_code['layout'];
		$columns = sanitize_text_field(!empty($short_code['columns']) && ('random' !== $layout && 'mosaic' !== $layout) ? $short_code['columns'] : 'auto');
		$more = !empty($short_code['more']) ? sanitize_text_field($short_code['more']) : '';
		$more = (empty($more) && !empty($short_code['photo_more'])) ? sanitize_text_field($short_code['photo_more']) : $more;
		$title_position = empty($short_code['title_position']) ? $photo_list->title_position : sanitize_text_field($short_code['title_position']);
		$row_constraints = $photo_list->row_constraints;
		$pagination = $photo_list->pagination;
		$indent = $photo_list->indent;

		$display = sanitize_text_field(!empty($short_code['display']) ? $short_code['display'] : 'local');
		$panel = !empty($short_code['panel']) ? sanitize_text_field($short_code['panel']) : '';
		$parent = $photo_list->parent;

		list($container_start_id, $container_end_id) = $this->get_container_details($short_code, $module);
		$non_standard = 'random' === $layout || 'masonry' === $layout || 'mosaic' === $layout;
		$gallery_column_class = $this->get_gallery_column_class($non_standard, $columns, $row_constraints);
		$effect = esc_attr($this->get_thumbnail_effect($short_code, $layout, $title_position));

		$gallery_figure_classes = ['photonic-level-1', 'photonic-thumb'];

		$lightbox_attributes  = $lightbox->get_gallery_attributes($panel, $module); // Function returns escaped values
		$anchor_classes       = $lightbox_attributes['class'] ?: [];
		$anchor_rel           = $lightbox_attributes['rel'] ?: [];
		$anchor_lightbox_data = $lightbox_attributes['specific'] ?: [];

		$ret = '';

		global $photonic_external_links_in_new_tab;
		if (!empty($photonic_external_links_in_new_tab)) {
			$target = " target='_blank' ";
			$anchor_lightbox_data['target'] = '_blank';
		}
		else {
			$target = '';
		}

		$counter = 0;

		$layout_engine = $this->get_layout_engine($module, $short_code);
		$all_sizes_present = strtolower($layout_engine) === 'css';

		$grid_figures = [];

		/** @var Photo $photo */
		foreach ($photos as $photo) {
			$counter++;

			$additional_image_data = [];
			$styles = [];
			if ($non_standard && 'local' === $display) {
				$thumb = $photo->tile_image ?: $photo->main_image;
				if (!empty($photo->tile_size)) {
					$inbuilt_sizes = $photo->tile_size;
				}
				elseif (!empty($photo->main_size)) {
					$inbuilt_sizes = $photo->main_size;
				}
			}
			else {
				$thumb = $photo->thumbnail;
				if (!empty($photo->thumb_size)) {
					$inbuilt_sizes = $photo->thumb_size;
				}
			}

			if (!empty($inbuilt_sizes)) {
				if ('random' === $layout) {
					$styles['--dw'] = $inbuilt_sizes['w'];
					$styles['--dh'] = $inbuilt_sizes['h'];
				}
			}
			else {
				$all_sizes_present = false;
			}

			$deep_value = 'gallery[photonic-' . $module->provider . '-' . $parent . '-' . ($panel ?: $module->gallery_index) . ']/' . ($photo->id ?: $counter) . '/';
			$additional_image_data['data-photonic-deep'] = esc_attr($deep_value);

			if (!empty($photo->buy_link) && $module->show_buy_link) {
				$additional_image_data['data-photonic-buy'] = esc_url($photo->buy_link);
			}

			$title       = wp_kses_post($photo->title);
			$description = wp_kses_post($photo->description);
			$alt         = wp_kses_post($photo->alt_title);

			if (!empty($short_code['caption']) && ('desc' === $short_code['caption'] || ('title-desc' === $short_code['caption'] && empty($title)) || ('desc-title' === $short_code['caption'] && !empty($description)))) {
				$title = $description;
			}
			elseif (empty($short_code['caption']) || 'none' === $short_code['caption']) {
				$title = '';
			}

			$title_markup = $lightbox->get_lightbox_title($photo, $module, $title, $alt, $target);
			$additional_image_data['data-title'] = $title_markup;

			$shown_title = '';
			if (in_array($title_position, ['below', 'hover-slideup-show', 'hover-slidedown-show', 'slideup-stick'], true) && !empty($title)) {
				// Convoluted... we want to remove any funky markup from $title, so wp_filter_nohtml_kses is used. But that does an `addslashes`, which causes more funky markup. So we use stripslashes, but then we decode the special characters.
				$shown_title = '<figcaption class="photonic-title-info"><div class="photonic-photo-title photonic-title">' . wp_specialchars_decode(stripslashes(wp_filter_nohtml_kses($title)), ENT_QUOTES) . '</div></figcaption>';
			}

			$photo_data = ['title' => $title_markup, 'deep' => $deep_value, 'raw_title' => esc_attr($title)];
			if (!empty($photo->download)) {
				$photo_data['download'] = esc_url($photo->download);
			}
			if (!empty($photo->video)) {
				$photo_data['video'] = esc_url($photo->video);
				$photo_data['poster'] = esc_url($photo->main_image);
			}
			else {
				$photo_data['image'] = esc_url($photo->main_image);
			}
			$photo_data['id'] = $photo->id;

			if (!empty($photo->main_size)) {
				$photo_data['width'] = $photo->main_size['w'];
				$photo_data['height'] = $photo->main_size['h'];
			}

			$image_lightbox_data = $lightbox->get_photo_attributes($photo_data, $module);

			if ('tooltip' === $title_position) {
				$additional_image_data['data-photonic-tooltip'] = esc_attr($title);
			}

			$grid_image             = new Grid_Image();
			$grid_image->alt        = esc_attr($alt);
			$grid_image->src        = $thumb;
			$grid_image->classes    = [sanitize_html_class($layout)];
			$grid_image->dimensions = $inbuilt_sizes ?? [];

			$grid_anchor             = new Grid_Anchor();
			$grid_anchor->href       = $lightbox->get_grid_link($photo, $short_code, $module); // CANNOT esc_url $lightbox->get_grid_link(...), since it sometimes returns a URL, and sometimes a "#" location. Instead, within get_grid_link there is either esc_attr or esc_url
			$grid_anchor->classes    = $anchor_classes;
			$grid_anchor->rel        = $anchor_rel;
			$grid_anchor->data       = array_merge(
				$anchor_lightbox_data,
				$image_lightbox_data,
				$additional_image_data
			);
			$grid_anchor->title      = 'none' !== $title_position ? esc_attr($title) : '';
			$grid_anchor->figcaption = $shown_title;
			$grid_anchor->image      = $grid_image;
			$grid_anchor->indent     = $indent;

			$grid_figure               = new Grid_Figure();
			$grid_figure->classes      = $gallery_figure_classes;
			$grid_figure->styles       = $styles;
			$grid_figure->video_markup = $lightbox->get_video_markup($photo, $module, $indent);
			$grid_figure->anchor       = $grid_anchor;
			$grid_figure->indent       = $indent;

			$grid_figures[] = $grid_figure;
		}

		if (!empty($grid_figures)) {
			$this->set_lazy_loading_attributes($all_sizes_present, $grid_figures);

			$container_class_list = $this->get_common_gallery_classes(1, $title_position, $non_standard, $layout, $effect, $all_sizes_present, $gallery_column_class, $display);
			$container_class_list[] = $lightbox->get_container_classes();

			$data_query = $this->get_updated_data_query($short_code, $pagination);
			$container_data_attributes = [
				'data-photonic-platform' => esc_attr($module->provider),
				'data-photonic-gallery-columns' => esc_attr($columns),
				'data-photonic-query' => esc_attr($data_query),
			];

			$ret = $this->generate_container_markup($module, $pagination, $container_class_list, $container_data_attributes, $grid_figures, $container_start_id, $container_end_id, $more, 1, $indent);
		}

		$module->pop_from_stack();
		return $ret;
	}

	/**
	 * Generates the HTML for a group of level-2 items, i.e. Photosets (Albums) and Galleries for Flickr, Albums for Google Photos,
	 * Albums for SmugMug, and Photosets (Galleries and Collections) for Zenfolio. No concept of albums
	 * exists in native WP and Instagram.
	 *
	 * @param Album_List $album_list
	 * @param array $short_code
	 * @param $module Base
	 * @return string
	 */
	public function generate_level_2_gallery(Album_List $album_list, array $short_code, Base $module): string {
		$module->push_to_stack('Generate Level 2 Gallery');

		$short_code = array_merge($this->common_parameters, $short_code);

		$objects = $album_list->albums;

		$layout = esc_attr($short_code['layout'] ?? 'square');
		$columns = $short_code['columns'];
		$more = !empty($short_code['more']) ? esc_attr($short_code['more']) : '';
		$title_position = esc_attr(empty($short_code['title_position']) ? $album_list->title_position : $short_code['title_position']);
		$row_constraints = $album_list->row_constraints;
		$pagination = $album_list->pagination;
		$indent = $album_list->indent;

		$singular_type = esc_attr($album_list->singular_type);
		$level_1_count_display = $album_list->level_1_count_display;
		$provider = esc_attr($module->provider);

		$gallery_data = array_merge(
			[
				'platform'  => $provider,
				'singular'  => $singular_type,
				'popup' => esc_attr($short_code['popup']),
			],
			$album_list->gallery_attributes
		);

		list($container_start_id, $container_end_id) = $this->get_container_details($short_code, $module);
		$non_standard = 'random' === $layout || 'masonry' === $layout || 'mosaic' === $layout;
		$gallery_column_class = $this->get_gallery_column_class($non_standard, $columns, $row_constraints);
		$effect = esc_attr($this->get_thumbnail_effect($short_code, $layout, $title_position));

		$gallery_figure_classes = ['photonic-level-2', 'photonic-thumb'];

		$gallery_anchor_classes = ['photonic-level-2-thumb'];
		if ($album_list->album_opens_gallery) {
			$gallery_anchor_classes[] = 'gallery-page';
		}

		$counter = 0;

		$layout_engine = $this->get_layout_engine($module, $short_code);
		$all_sizes_present = strtolower($layout_engine) === 'css';

		$ret = '';
		$grid_figures = [];

		/** @var Album $object */
		foreach ($objects as $idx => $object) {
			$data_attributes = $object->data_attributes ?: [];
			$styles = [];

			$anchor_data = [];
			foreach ($data_attributes as $attr => $value) {
				$anchor_data['data-photonic-' . $attr] = $value;
			}

			$id = empty($object->id) ? '' : $object->id . '-';
			$id = esc_attr($id . $module->gallery_index);
			$title = wp_kses_post($object->title);

			$anchor_data['data-title'] = esc_attr($title);

			if ('tooltip' === $title_position) {
				$anchor_data['data-photonic-tooltip'] = esc_attr($title);
			}

			if ($non_standard && !empty($object->tile_image)) {
				$img_src = $object->tile_image;
				$inbuilt_sizes = $object->tile_size ?: [];
			}
			else {
				$img_src = $object->thumbnail;
				$inbuilt_sizes = $object->thumb_size ?: [];
			}

			if (!empty($inbuilt_sizes)) {
				if ('random' === $layout) {
					$styles['--dw'] = $inbuilt_sizes['w'];
					$styles['--dh'] = $inbuilt_sizes['h'];
				}
			}
			else {
				$all_sizes_present = false;
			}

			$shown_title = '';
			if (in_array($title_position, ['below', 'hover-slideup-show', 'hover-slidedown-show', 'slideup-stick'], true)) {
				$shown_title = "\n{$indent}\t\t\t<figcaption class='photonic-title-info'>\n{$indent}\t\t\t\t<div class='photonic-$singular_type-title photonic-title'>" . wp_specialchars_decode(stripslashes(wp_filter_nohtml_kses($title)), ENT_QUOTES) . "";
				if (!$level_1_count_display && !empty($object->counter)) {
					$shown_title .= '<span class="photonic-title-photo-count photonic-' . $singular_type . '-photo-count">' . sprintf(esc_html__('%s photos', 'photonic'), $object->counter) . '</span>';
				}
				$shown_title .= "</div>\n{$indent}\t\t\t</figcaption>";
			}

			$grid_image             = new Grid_Image();
			$grid_image->alt        = esc_attr($title);
			$grid_image->src        = esc_url($img_src);
			$grid_image->classes    = [sanitize_html_class($layout)];
			$grid_image->dimensions = $inbuilt_sizes ?? [];

			$grid_anchor             = new Grid_Anchor();
			$grid_anchor->id         = "photonic-{$provider}-$singular_type-thumb-$id";
			$grid_anchor->href       = esc_url($object->gallery_url ?? $object->main_page);
			$grid_anchor->classes    = array_merge($gallery_anchor_classes, $object->classes);
			$grid_anchor->data       = $anchor_data;
			$grid_anchor->title      = 'none' !== $title_position ? esc_attr($title) : '';
			$grid_anchor->figcaption = $shown_title;
			$grid_anchor->image      = $grid_image;
			$grid_anchor->indent     = $indent;

			$grid_figure                  = new Grid_Figure();
			$grid_figure->id              = "photonic-{$provider}-$singular_type-$id";
			$grid_figure->classes         = $gallery_figure_classes;
			$grid_figure->styles          = $styles;
			$grid_figure->anchor          = $grid_anchor;
			$grid_figure->indent          = $indent;
			$grid_figure->prompter_markup = $this->get_password_prompter($object, $provider, $singular_type, $id);

			$grid_figures[] = $grid_figure;
			$counter++;
		}

		if (!empty($grid_figures)) {
			$this->set_lazy_loading_attributes($all_sizes_present, $grid_figures);
			$gallery_class_list = $this->get_common_gallery_classes(2, $title_position, $non_standard, $layout, $effect, $all_sizes_present, $gallery_column_class);

			$data_query = $this->get_updated_data_query($short_code, $pagination);
			$container_data_attributes = [
				'data-photonic-platform' => $provider,
				'data-photonic-gallery-columns' => esc_attr($columns),
				'data-photonic-query' => esc_attr($data_query),
				'data-photonic' => esc_attr(wp_json_encode($gallery_data)),
			];

			$ret = $this->generate_container_markup($module, $pagination, $gallery_class_list, $container_data_attributes, $grid_figures, $container_start_id, $container_end_id, $more, 2, $indent);
		}

		$module->pop_from_stack();
		return $ret;
	}

	/**
	 * @param array $short_code
	 * @param Base $module
	 * @return array
	 */
	private function get_container_details(array $short_code, Base $module): array {
		if ('modal' !== $short_code['display']) {
			$start_id = "photonic-" . esc_attr($module->provider) . "-stream-" . esc_attr($module->gallery_index) . "-container";
			$end_id   = "photonic-" . esc_attr($module->provider) . "-stream-" . esc_attr($module->gallery_index) . "-container-end";
		}
		else {
			$start_id = "photonic-" . esc_attr($module->provider) . "-panel-" . esc_attr(sanitize_text_field($short_code['panel'])) . "-container";
			$end_id   = "photonic-" . esc_attr($module->provider) . "-panel-" . esc_attr(sanitize_text_field($short_code['panel'])) . "-container-end";
		}
		return [$start_id, $end_id];
	}

	/**
	 * @param array      $short_code
	 * @param Pagination $pagination
	 * @return string
	 */
	private function get_updated_data_query(array $short_code, Pagination $pagination): string {
		$to_be_glued = '';

		if (!empty($short_code)) {
			$to_be_glued = [];
			foreach ($short_code as $name => $value) {
				if (is_scalar($value)) {
					if ('next_token' !== $name) {
						$to_be_glued[] = $name . '=' . $value;
					}
				}
			}

			if (!empty($pagination->next_token)) {
				$to_be_glued[] = 'next_token=' . $pagination->next_token;
			}

			$to_be_glued = implode('&', $to_be_glued);
			$to_be_glued = esc_attr($to_be_glued);
		}
		return $to_be_glued;
	}

	/**
	 * Returns the thumbnail effect that should be used for a gallery. Not all effects can be used by all types of layouts.
	 *
	 * @param $short_code
	 * @param $layout
	 * @param $title_position
	 * @return string
	 */
	private function get_thumbnail_effect($short_code, $layout, $title_position): string {
		if (!empty($short_code['thumbnail_effect'])) {
			$effect = $short_code['thumbnail_effect'];
		}
		else {
			global $photonic_standard_thumbnail_effect, $photonic_justified_thumbnail_effect, $photonic_mosaic_thumbnail_effect, $photonic_masonry_thumbnail_effect;
			$effect = 'mosaic' === $layout ? $photonic_mosaic_thumbnail_effect :
				('masonry' === $layout ? $photonic_masonry_thumbnail_effect :
					('random' === $layout ? $photonic_justified_thumbnail_effect :
						$photonic_standard_thumbnail_effect));
			$effect = esc_attr($effect);
		}

		if ('circle' === $layout && 'opacity' !== $effect) { // "Zoom" doesn't work for circle
			$thumbnail_effect = 'none';
		}
		elseif (('square' === $layout || 'launch' === $layout || 'masonry' === $layout) && 'below' === $title_position) { // For these combinations, Zoom doesn't work
			$thumbnail_effect = 'none';
		}
		else {
			$thumbnail_effect = $effect;
		}
		return apply_filters('photonic_thumbnail_effect', $thumbnail_effect, $short_code, $layout, $title_position);
	}

	/**
	 * Returns the layout engine to be used, i.e. js or css.
	 *
	 * @param Base $module
	 * @param $short_code
	 * @return string
	 */
	private function get_layout_engine(Base $module, $short_code): string {
		$layout_engine = 'photonic_' . $module->provider . '_layout_engine';
		global ${$layout_engine};
		return $short_code['layout_engine'] ?? ${$layout_engine};
	}

	/**
	 * @param Album  $object
	 * @param string $provider
	 * @param string $singular_type
	 * @param string $id
	 * @return string
	 */
	private function get_password_prompter(Album $object, string $provider, string $singular_type, string $id): string {
		$password_prompt = '';
		if (!empty($object->passworded)) {
			$password_prompt = "
							<div class='photonic-password-prompter' id='photonic-{$provider}-$singular_type-prompter-$id' title='{$this->prompt_title}' data-photonic-prompt='password'>
								<div class='photonic-password-prompter-content'>
									<div class='photonic-prompt-head'>
										<h3>
											<span class='title'>{$this->prompt_title}</span>
											<button class='close'>&times;</button>
										</h3>
									</div>
									<div class='photonic-prompt-body'>
										<p>{$this->prompt_text}</p>
										<input type='password' name='photonic-{$provider}-password' />
										<button class='photonic-{$provider}-submit photonic-password-submit confirm'>{$this->prompt_submit}</button>
									</div>
								</div>
							</div>";
		}
		return $password_prompt;
	}

	/**
	 * @param bool  $all_sizes_present
	 * @param array $grid_figures
	 */
	private function set_lazy_loading_attributes(bool $all_sizes_present, array $grid_figures): void {
		if ($all_sizes_present) {
			foreach ($grid_figures as $figure) {
				$figure->anchor->image->lazy_load = 'lazy';
				$figure->anchor->image->src_attr  = 'data-src';
			}
		}
		else {
			foreach ($grid_figures as $figure) {
				$figure->anchor->image->lazy_load = 'eager';
				$figure->anchor->image->src_attr  = 'src';
			}
		}
	}

	private function get_common_gallery_classes($level, $title_position, $non_standard, $layout, $thumbnail_effect, $all_sizes_present, $gallery_column_class, $display = null): array {
		$classes = [
			'title-display-' . $title_position,
			'photonic-level-' . $level . '-container',
			$all_sizes_present ? 'sizes-present' : 'sizes-missing',
			$gallery_column_class,
		];

		if ('modal' === $display) {
			$classes[] = 'modal-gallery';
		}
		else {
			$classes[] = $non_standard ? 'photonic-' . $layout . '-layout' : 'photonic-standard-layout';
			$classes[] = 'photonic-thumbnail-effect-' . $thumbnail_effect;
		}

		return $classes;
	}

	/**
	 * @param bool   $non_standard
	 * @param string $columns
	 * @param array  $row_constraints
	 * @return string
	 */
	private function get_gallery_column_class(bool $non_standard, string $columns, array $row_constraints): string {
		$gallery_column_class = '';
		if (!$non_standard) {
			if (absint($columns)) {
				$gallery_column_class = 'photonic-gallery-' . esc_attr($columns) . 'c';
			}
			elseif ('padding' === $row_constraints['constraint-type']) {
				$gallery_column_class = 'photonic-auto-padded';
			}
			elseif (!empty($row_constraints['count'])) {
				$gallery_column_class = 'photonic-gallery-' . esc_attr($row_constraints['count']) . 'c';
			}
		}
		return $gallery_column_class;
	}

	private function glue_data_attributes(array $map): string {
		$data_pieces = [];
		if (!empty($map)) {
			$data_pieces = array_map(
				function (string $key, string $value): string {
					return $key . '="' . $value . '"';
				},
				array_keys($map),
				array_values($map)
			);
		}
		return implode(' ', $data_pieces);
	}

	private function generate_container_markup(Base $module, Pagination $pagination, array $container_class_list, array $container_data_attributes, array $grid_figures, string $start_id, string $end_id, string $more, int $level, string $indent): string {
		$ret = '';
		if (!empty($grid_figures)) {
			global $photonic_tile_min_height;
			$container_class = str_replace('  ', ' ', trim(implode(' ', $container_class_list)));

			if (!is_numeric($photonic_tile_min_height)) {
				$photonic_tile_min_height = 200;
			}
			$photonic_tile_min_height = esc_attr($photonic_tile_min_height);

			$container_data_string = $this->glue_data_attributes($container_data_attributes);
			$ret = "\n$indent<div id='" . esc_attr($start_id) . "' class='" . esc_attr($container_class) . "' $container_data_string style='--tile-min-height: {$photonic_tile_min_height}px'>\n";
			foreach ($grid_figures as $figure) {
				$ret .= $figure->html($module, $this, false);
			}

			$ret .= "\n$indent</div> <!-- ./photonic-level-{$level}-container -->";
			$ret .= "\n$indent<span id='$end_id'></span>";

			if (!empty($pagination) && isset($pagination->end) && isset($pagination->total) && $pagination->total > $pagination->end) {
				$ret .= !empty($more) ? "\n$indent<a href='#' class='photonic-more-button photonic-more-dynamic'>$more</a>\n" : '';
			}
		}
		return $ret;
	}
}
