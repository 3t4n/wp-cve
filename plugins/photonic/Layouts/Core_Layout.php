<?php

namespace Photonic_Plugin\Layouts;

use Photonic_Plugin\Components\Header;
use Photonic_Plugin\Components\Single_Photo;
use Photonic_Plugin\Core\Photonic;
use Photonic_Plugin\Platforms\Base;

/**
 * Layout Manager to generate the grid layouts and the "Justified Grid" layout, all of which use the same markup. The Justified Grid layout is
 * modified by JS on the front-end, however the base markup for it is similar to the square and circular thumbnails layout.
 *
 * All other layout managers extend this, and might implement their own versions of generate_level_1_gallery and generate_level_2_gallery
 */
abstract class Core_Layout {
	protected $library;
	protected $layout;
	protected $common_parameters = [];
	protected $prompt_title;
	protected $prompt_submit;
	protected $prompt_text;

	protected function __construct() {
		$this->library = esc_attr(Photonic::$library);

		$this->prompt_title = esc_attr__('Protected Content', 'photonic');
		$this->prompt_submit = esc_attr__('Access', 'photonic');
		$this->prompt_text = esc_attr__('This album is password-protected. Please provide a valid password.', 'photonic');
	}

	final public static function get_instance() {
		static $instances = array();
		$called_class = get_called_class();

		if (!isset($instances[$called_class])) {
			$instances[$called_class] = new $called_class();
		}
		return $instances[$called_class];
	}

	/**
	 * Generates the markup for a single photo.
	 *
	 * @param Single_Photo $photo Pertinent pieces of information about the photo - the source (src), the photo page (href), title and caption.
	 * @param Base $module The object calling this. A CSS class is created in the header, photonic-single-<code>$module->provider</code>-photo-header.
	 * @return string
	 */
	public function generate_single_photo_markup(Single_Photo $photo, Base $module): string {
		$module->push_to_stack('Generate single photo markup');
		$ret = '';

		if (empty($photo->src)) {
			$module->pop_from_stack();
			return $ret;
		}

		global $photonic_external_links_in_new_tab;
		if (!empty($photo->title)) {
			$ret .= "\t" . '<h3 class="photonic-single-photo-header photonic-single-' . $module->provider . '-photo-header">' . $photo->title . "</h3>\n";
		}

		$img = '<img src="' . esc_url($photo->src) . '" alt="' . esc_attr($photo->caption ?: $photo->title) . '" loading="eager"/>';
		if (!empty($photo->href)) {
			$img = '<a href="' . esc_url($photo->href) . '" title="' . esc_attr($photo->caption ?: $photo->title) . '" ' .
				(!empty($photonic_external_links_in_new_tab) ? ' target="_blank" ' : '') . '>' . $img . '</a>';
		}

		if (!empty($photo->caption)) {
			$ret .= "\t" . '<div class="wp-caption">' . "\n\t\t" . $img . "\n\t\t" . '<div class="wp-caption-text">' . wp_kses_post($photo->caption) . "</div>\n\t</div><!-- .wp-caption -->\n";
		}
		else {
			$ret .= $img;
		}

		$module->pop_from_stack();
		return $ret;
	}

	/**
	 * Generates the markup for a <code>Header</code> element. A header typically consists of a thumbnail, a title and some
	 * information about the child counts.
	 *
	 * @param Header $header
	 * @param Base $module
	 * @return string
	 */
	public function generate_header_markup(Header $header, Base $module): string {
		if ('lightbox' === $header->display_location || $this instanceof Slideshow) {
			return '';
		}

		$ret = '';
		global $photonic_gallery_template_page, $photonic_page_content;

		if (!empty($photonic_gallery_template_page) && is_page($photonic_gallery_template_page) && in_array($photonic_page_content, ['replace-if-available', 'append-if-available'], true)) {
			$ret = wptexturize(wp_kses_post($header->description));
		}
		elseif ((empty($photonic_gallery_template_page) ||
				!is_page() ||
				(is_page() && !empty($photonic_gallery_template_page) && !is_page($photonic_gallery_template_page))) &&
			!empty($header->title)) {
			global $photonic_external_links_in_new_tab;
			$title = esc_attr($header->title);

			if (!empty($photonic_external_links_in_new_tab)) {
				$target = ' target="_blank" ';
			}
			else {
				$target = '';
			}

			$anchor = '';
			if (!empty($header->thumb_url)) {
				$image = '<img src="' . esc_url($header->thumb_url) . '" alt="' . $title . '" />';

				if ($header->enable_link) {
					$anchor = "<a href='" . esc_url($header->page_url) . "' class='photonic-header-thumb photonic-{$module->provider}-{$header->header_for}-solo-thumb' title='" . $title . "' $target>" . $image . "</a>";
				}
				else {
					$anchor = "<div class='photonic-header-thumb photonic-{$module->provider}-{$header->header_for}-solo-thumb'>$image</div>";
				}
			}

			if (empty($header->hidden_elements['thumbnail']) || empty($header->hidden_elements['title']) || empty($header->hidden_elements['counter']) || empty($header->iterate_level_3)) {
				$popup_header_class = '';
				if ('modal' === $header->display_location) {
					$popup_header_class = 'photonic-panel-header';
				}
				$ret .= "<header class='photonic-object-header photonic-{$module->provider}-{$header->header_for} $popup_header_class'>";

				if (empty($header->hidden_elements['thumbnail'])) {
					$ret .= $anchor;
				}
				if (empty($header->hidden_elements['title']) || empty($header->hidden_elements['counter']) || empty($header->iterate_level_3)) {
					$ret .= "<div class='photonic-header-details photonic-{$header->header_for}-details'>";
					if (empty($header->hidden_elements['title']) || empty($header->iterate_level_3)) {
						$expand = empty($header->iterate_level_3) ? '<a href="#" title="' . esc_attr__('Show', 'photonic') . '" class="photonic-level-3-expand photonic-level-3-expand-plus" data-photonic-level-3="' . $module->provider . '-' . $header->header_for . '-' . $header->id . '" data-photonic-layout="' . $header->layout . '">&nbsp;</a>' : '';

						if ($header->enable_link) {
							$ret .= "<div class='photonic-header-title photonic-{$header->header_for}-title'><a href='" . esc_url($header->page_url) . "' $target>" . wptexturize($title) . '</a>' . $expand . '</div>';
						}
						else {
							$ret .= "<div class='photonic-header-title photonic-{$header->header_for}-title'>" . wptexturize($title) . $expand . '</div>';
						}
					}

					if (empty($header->hidden_elements['counter'])) {
						$counter_texts = [];
						if (!empty($header->counters['groups'])) {
							$counter_texts[] = esc_html(sprintf(_n('%s group', '%s groups', $header->counters['groups'], 'photonic'), $header->counters['groups']));
						}
						if (!empty($header->counters['sets'])) {
							$counter_texts[] = esc_html(sprintf(_n('%s set', '%s sets', $header->counters['sets'], 'photonic'), $header->counters['sets']));
						}
						if (!empty($header->counters['photos'])) {
							$counter_texts[] = esc_html(sprintf(_n('%s photo', '%s photos', $header->counters['photos'], 'photonic'), $header->counters['photos']));
						}
						if (!empty($header->counters['videos'])) {
							$counter_texts[] = esc_html(sprintf(_n('%s video', '%s videos', $header->counters['videos'], 'photonic'), $header->counters['videos']));
						}

						apply_filters('photonic_modify_counter_texts', $counter_texts, $header->counters);

						if (!empty($counter_texts)) {
							$ret .= "<span class='photonic-header-info photonic-{$header->header_for}-photos'>" . implode(', ', $counter_texts) . '</span>';
						}
					}

					$ret .= "</div><!-- .photonic-{$header->header_for}-details -->";
				}
				$ret .= "</header>";
			}
		}
		return $ret;
	}

	/**
	 * @return string|void
	 */
	public function get_library(): string {
		return $this->library;
	}
}
