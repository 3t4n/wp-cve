<?php

namespace Photonic_Plugin\Admin\Wizard;

use Photonic_Plugin\Core\Utilities;

/**
 * Contains the fields used by the gallery builder. Cannot be overridden by a theme file
 * Screen 1: Provider selection: Pick from Flickr, Google Photos etc.
 * Screen 2: Display Type selection; Input: Provider. Pick from: Single Photo
 * Screen 3: Gallery object selection; input: Display Type
 * Screen 4: Layout selection; input: Gallery & Display Type
 * Screen 5: Layout configuration; Set <code>count</code>, <code>more</code> etc.
 * Screen 6: Final shortcode display
 *
 * @since 2.00
 */
class Screen_Fields {
	public $layout_options;
	private $paths;

	public function __construct() {
		require_once 'Source.php';
		$this->paths = [
			'wp'        => 'WP.php',
			'flickr'    => 'Flickr.php',
			'smugmug'   => 'SmugMug.php',
			'google'    => 'Google_Photos.php',
			'zenfolio'  => 'Zenfolio.php',
			'instagram' => 'Instagram.php',
		];
		$this->layout_options = [
			'square'    => esc_html__('Square Grid', 'photonic'),
			'circle'    => esc_html__('Circular Icon Grid', 'photonic'),
			'random'    => esc_html__('Justified Grid', 'photonic'),
			'masonry'   => esc_html__('Masonry', 'photonic'),
			'mosaic'    => esc_html__('Mosaic', 'photonic'),
			'slideshow' => esc_html__('Slideshow', 'photonic'),
		];
	}

	/**
	 * @return array
	 */
	public function get_layout_options(): array {
		return $this->layout_options;
	}

	/**
	 * @param $provider
	 * @return array
	 */
	public function get_screen_2_fields($provider): array {
		$source = $this->get_source($provider);
		return $source->get_screen_2();
	}

	public function get_screen_3_fields($provider): array {
		$source = $this->get_source($provider);
		return $source->get_screen_3();
	}

	public function get_screen_5_fields($provider): array {
		global $photonic_enable_popup;
		$source = $this->get_source($provider);

		$output = $source->get_screen_5();

		$output['slideshow'] = $source->get_slideshow_options();
		$output['square'] = $source->get_square_layout_options();
		$output['random'] = $source->get_random_layout_options();

		$output['L1'] = [
			'count'        => [
				'desc' => esc_html__('Number of photos to show', 'photonic'),
				'type' => 'text',
				'hint' => esc_html__('Numeric values only. Leave blank for default.', 'photonic'),
			],
			'more'         => [
				'desc' => esc_html__('"More" button text', 'photonic'),
				'type' => 'text',
				'hint' => esc_html__('Will show a "More" button with the specified text if the number of photos is higher than the above entry. Leave blank to show no button', 'photonic'),
			],
			'show_gallery' => [
				'desc' => esc_html__('"Show Gallery" button text', 'photonic'),
				'type' => 'text',
				'hint' => esc_html__('Will show a "Show Gallery" button with the specified text. This will show a button instead of the gallery when the page loads, saving time on the load. Users can click on this button to see the gallery. Leave blank to show no button', 'photonic'),
			],
		];
		$output['L2'] = [
			'count'        => [
				'desc' => esc_html__('Number of albums to show', 'photonic'),
				'type' => 'text',
				'hint' => esc_html__('Numeric values only. Leave blank for default.', 'photonic'),
			],
			'more'         => [
				'desc' => esc_html__('"More" button text', 'photonic'),
				'type' => 'text',
				'hint' => esc_html__('Will show a "More" button with the specified text if the number of albums is higher than the above entry. Leave blank to show no button', 'photonic'),
			],
			'popup_type'   => [
				'type'      => 'field_list',
				'list_type' => 'sequence',
				'list'      => [
					'popup'        => [
						'desc'    => esc_html__('Show photos in an overlaid popup panel upon clicking on an album / photoset / gallery', 'photonic'),
						'type'    => 'select',
						'options' => [
							''     => esc_html__('Default from settings', 'photonic') . ' - ' . ($photonic_enable_popup ?: esc_html__('No overlay', 'photonic')),
							'hide' => esc_html__('No overlay - start showing photos in a lightbox', 'photonic'),
							'show' => esc_html__('Show - show photos in a modal overlay first', 'photonic'),
							'page' => esc_html__('Page - Show photos in a separate page', 'photonic'),
						],
						'std'     => '',
						'hint' => sprintf(
							esc_html__('Default settings under %4$s. Setting this to "No" would directly start up a lightbox with photos. Setting this to "Show" would show an overlaid panel that has the photos. Setting this to "Page" will take you to a separate page defined under %3$s. See %1$sdocumentation%2$s.', 'photonic'),
							'<a href="https://aquoid.com/plugins/photonic/layouts/#nested" target="_blank">',
							'</a>',
							'<em>Photonic &rarr; Settings &rarr; Generic Options &rarr; Photo Template</em>',
							'<em>Photonic &rarr; Settings &rarr; Generic Options &rarr; Overlaid Popup Panel &rarr; Enable Interim Popup for Album Thumbnails</em>'
						),
					],
					'photo_count'  => [
						'desc'       => esc_html__('Number of photos to show in overlaid popup / separate gallery page', 'photonic'),
						'type'       => 'text',
						'hint'       => esc_html__('Numeric values only. Leave blank for default.', 'photonic'),
						'conditions' => ['popup' => ['show', 'page']],
					],
					'photo_more'   => [
						'desc'       => esc_html__('"More" button text in overlaid popup / separate gallery page', 'photonic'),
						'type'       => 'text',
						'hint'       => esc_html__('Will show a "More" button with the specified text if the number of photos in the overlaid popup is higher than the above entry. Leave blank to show no button', 'photonic'),
						'conditions' => ['popup' => ['show', 'page']],
					],
					'photo_layout' => [
						'desc'       => esc_html__('Layout of photos in separate gallery page', 'photonic'),
						'type'       => 'select',
						'options'    => Utilities::layout_options(true, esc_html__('Same as main gallery page', 'photonic')),
						'conditions' => ['popup' => ['page']],
					]
				]
			],
			'show_gallery' => [
				'desc' => esc_html__('"Show Gallery" button text', 'photonic'),
				'type' => 'text',
				'hint' => esc_html__('Will show a "Show Gallery" button with the specified text. This will show a button instead of the gallery when the page loads, saving time on the load. Users can click on this button to see the gallery. Leave blank to show no button', 'photonic'),
			],
		];
		$output['L3'] = [
			'show_gallery' => [
				'desc' => esc_html__('"Show Gallery" button text', 'photonic'),
				'type' => 'text',
				'hint' => esc_html__('Will show a "Show Gallery" button with the specified text. This will show a button instead of the gallery when the page loads, saving time on the load. Users can click on this button to see the gallery. Leave blank to show no button', 'photonic'),
			],
		];

		$output['circle'] = $output['square'];
		$output['masonry'] = $output['random'];
		$output['masonry']['columns'] = $source->get_column_options();
		$output['mosaic'] = $output['random'];
		unset($output['random']['title_position']['options']['below']);
		unset($output['mosaic']['title_position']['options']['below']);
		$output['L3']['popup_type'] = $output['L2']['popup_type'];

		return $output;
	}

	/**
	 * @param $provider
	 * @return Source
	 */
	public function get_source($provider): Source {
		require_once $this->paths[$provider];
		if ('flickr' === $provider) {
			$source = Flickr::get_instance();
		}
		elseif ('smugmug' === $provider) {
			$source = SmugMug::get_instance();
		}
		elseif ('google' === $provider) {
			$source = Google_Photos::get_instance();
		}
		elseif ('zenfolio' === $provider) {
			$source = Zenfolio::get_instance();
		}
		elseif ('instagram' === $provider) {
			$source = Instagram::get_instance();
		}
		else {
			$source = WP::get_instance();
		}
		return $source;
	}
}
