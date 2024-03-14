<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.sanil.com.np
 * @since      1.0.0
 *
 * @package    Sticky_Social_Icons
 * @subpackage Sticky_Social_Icons/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sticky_Social_Icons
 * @subpackage Sticky_Social_Icons/public
 * @author     Sanil Shakya <sanilshakya@gmail.com>
 */

class Sticky_Social_Icons_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


	/**
	 * Enable Tooltip
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $enable_tooltip
	 */
	private $enable_tooltip;

	/**
	 * Enable Animation
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $enable_animation
	 */
	private $enable_animation;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		if (!is_admin()) {
			$this->enable_tooltip = get_option(STICKY_SOCIAL_ICONS_DB_INITIALS . 'enable_tooltip', 1);
			$this->enable_animation = get_option(STICKY_SOCIAL_ICONS_DB_INITIALS . 'enable_animation', 1);
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		if (is_rtl()) {
			wp_enqueue_style(
				$this->plugin_name,
				plugin_dir_url(__FILE__) . 'assets/build/css/sticky-social-icons-public-rtl.css',
				array(),
				STICKY_SOCIAL_ICONS_ENVIRONMENT === 'dev' ? time() : $this->version,
				'all'
			);
		} else {
			wp_enqueue_style(
				$this->plugin_name,
				plugin_dir_url(__FILE__) . 'assets/build/css/sticky-social-icons-public.css',
				array(),
				STICKY_SOCIAL_ICONS_ENVIRONMENT === 'dev' ? time() : $this->version,
				'all'
			);
		}


		if (get_option(STICKY_SOCIAL_ICONS_DB_INITIALS . 'load_fontawesome_icons', 1)) {

			$sel_icon_pack = get_option(STICKY_SOCIAL_ICONS_DB_INITIALS . 'icon_package', 'fontawesome5');

			if ($sel_icon_pack == 'fontawesome5') {
				// fontawesome5
				wp_enqueue_style('font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css');
			} elseif ($sel_icon_pack == 'fontawesome6') {
				// fontawesome6
				wp_enqueue_style('font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css');
			} else {
				// fontawesome6
				wp_enqueue_style('font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');
			}
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
	}



	/**
	 * Output contents for front end website
	 *
	 * @since    1.0.0
	 */

	public function show_template() {
		ob_start();

		$icons_data 		= get_option(STICKY_SOCIAL_ICONS_DB_INITIALS . 'selected_icons');
		$design 			= get_option(STICKY_SOCIAL_ICONS_DB_INITIALS . 'design', 'rounded');
		$alignment 			= get_option(STICKY_SOCIAL_ICONS_DB_INITIALS . 'alignment', 'left');
		$hide_in_mobile 	= get_option(STICKY_SOCIAL_ICONS_DB_INITIALS . 'hide_in_mobile', 'left');
		$enable_animation 	= $this->enable_animation;
		$enable_tooltip 	= $this->enable_tooltip;

		if ($icons_data) {
			require_once dirname(__FILE__) . '/templates/' . $this->plugin_name . '-template.php';
		}

		echo ob_get_clean();
	}


	/**
	 * Generate <style> tag and output to <head> of the front end website
	 *
	 * @since    1.0.0
	 */

	public function generate_styles() {
		$icons_data = get_option(STICKY_SOCIAL_ICONS_DB_INITIALS . 'selected_icons');

		$styles_markup = '<style id="' . $this->plugin_name . '-styles">';

		if ($icons_data) {

			$offset_from_top	= get_option(STICKY_SOCIAL_ICONS_DB_INITIALS . 'offset_from_top', STICKY_SOCIAL_ICONS_DEFAULTS[0]);
			$icon_font_size 	= get_option(STICKY_SOCIAL_ICONS_DB_INITIALS . 'icon_font_size', STICKY_SOCIAL_ICONS_DEFAULTS[1]);
			$icon_width 		= get_option(STICKY_SOCIAL_ICONS_DB_INITIALS . 'width', STICKY_SOCIAL_ICONS_DEFAULTS[2]);
			$icon_height 		= get_option(STICKY_SOCIAL_ICONS_DB_INITIALS . 'height', STICKY_SOCIAL_ICONS_DEFAULTS[3]);

			$styles_markup 		.= '#sticky-social-icons-container{';
			$styles_markup 		.= 'top: ' . $offset_from_top . 'px';
			$styles_markup 		.= '}';

			$styles_markup 		.= '#sticky-social-icons-container li a{';
			$styles_markup 		.= 'font-size: ' . $icon_font_size . 'px; ';
			$styles_markup 		.= 'width: ' . $icon_width . 'px; ';
			$styles_markup 		.= 'height:' . $icon_height . 'px; ';
			$styles_markup 		.= '}';

			// hover.
			$styles_markup 		.= '#sticky-social-icons-container.with-animation li a:hover{';
			$styles_markup 		.= 'width: ' . ($icon_width + 10) . 'px; ';
			$styles_markup 		.= '}';

			foreach (json_decode($icons_data) as $icon) {
				$single_icon = json_decode($icon);

				$styles_markup 		.= '#sticky-social-icons-container li a.' . str_replace(' ', '-', $single_icon->icon) . '{';
				$styles_markup 		.= 'color: ' . $single_icon->icon_color . '; ';
				$styles_markup 		.= 'background: ' . $single_icon->bck_color . '; ';
				$styles_markup 		.= '}';

				$styles_markup 		.= '#sticky-social-icons-container li a.' . str_replace(' ', '-', $single_icon->icon) . ':hover{';
				$styles_markup 		.= 'color: ' . $single_icon->icon_color_on_hover . '; ';
				$styles_markup		 .= 'background: ' . $single_icon->bck_color_on_hover . '; ';
				$styles_markup 		.= '}';
			}

			// responsive makups
			$styles_markup .= '@media( max-width: 415px ){';


			$styles_markup 		.= '#sticky-social-icons-container li a{';
			$styles_markup 		.= 'font-size: ' . ($icon_font_size - ($icon_font_size * 0.15)) . 'px; ';
			$styles_markup 		.= 'width: ' . ($icon_width - ($icon_width * 0.15)) . 'px; ';
			$styles_markup 		.= 'height:' . ($icon_height - ($icon_height * 0.15)) . 'px; ';
			$styles_markup 		.= '}';

			$styles_markup .= '}';
		}

		$styles_markup .= '</style>';

		echo $styles_markup;
	}
}
