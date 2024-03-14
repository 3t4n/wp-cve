<?php

namespace Photonic_Plugin\Core;

/**
 * Class Template
 * Used for cases where clicking on an album link opens a Photonic gallery on its own page. This is triggered when the shortcode attribute
 * <code>popup='page'</code> is set
 */
class Template {
	public function __construct() {
		add_filter('the_content', [&$this, 'load_gallery'], 100, 1);
		add_filter('the_title', [&$this, 'set_title'], 10, 2);
	}

	/**
	 * Changes the title of the template page to the title of the album being displayed.
	 *
	 * @param $title
	 * @param $id
	 * @return string|void
	 */
	public function set_title($title, $id = null) {
		global $photonic_page_title, $photonic_gallery_template_page;
		if (!empty($id)) {
			if (!empty($photonic_gallery_template_page) && is_page($photonic_gallery_template_page) && 'replace-if-available' === $photonic_page_title && absint($photonic_gallery_template_page) === absint($id)) {
				if (isset($_REQUEST['photonic_gallery_title'])) {
					return wptexturize(stripslashes_deep(wp_kses_post($_REQUEST['photonic_gallery_title'])));
				}
			}
		}
		return $title;
	}

	/**
	 * Changes the content of the template page to have the description and contents of the gallery.
	 *
	 * @param $content
	 * @return string
	 */
	public function load_gallery($content) {
		global $photonic_gallery_template_page;
		if (!empty($photonic_gallery_template_page) && is_page($photonic_gallery_template_page)) {
			// Cannot check nonce for front-end gallery, but will vet the request fully
			if (isset($_REQUEST['photonic_gallery'])) {
				global $photonic_alternative_shortcode;

				$shortcode_tag = esc_attr($photonic_alternative_shortcode ?: 'gallery');
				$shortcode = sanitize_text_field($_REQUEST['photonic_gallery']);
				$shortcode = base64_decode($shortcode); // The `encode` is defined in Core.php's get_gallery_url method. We check this in the following steps.

				// Input is coming via a URL, so we have to ensure it is safe.
				// The input is expected to be a Photonic shortcode, so the simplest way is to strip out all instances of the Photonic
				// shortcode and verify that the input is blank. If it is blank, then all that the input had was a Photonic shortcode.
				$content_without_shortcodes = strip_shortcodes($shortcode);
				if (!empty(trim($shortcode)) && has_shortcode($shortcode, $shortcode_tag) && empty(trim($content_without_shortcodes))) {
					// Looks good. Let's proceed.
					global $photonic_page_content;
					if ('replace-if-available' === $photonic_page_content) {
						$content = do_shortcode($shortcode);
					}
					elseif ('append-if-available' === $photonic_page_content) {
						$content .= do_shortcode($shortcode);
					}
				}
				else {
					// Input looks funny. Stay safe and exit
					$content .= esc_html__('You are trying to display a gallery, but no gallery was found corresponding to the input.', 'photonic');
				}
			}
			else {
				// Input is blank. Show nothing and exit
				$content .= esc_html__('You are trying to display a gallery, but no input was provided.', 'photonic');
			}
		}
		return $content;
	}
}

new Template();
