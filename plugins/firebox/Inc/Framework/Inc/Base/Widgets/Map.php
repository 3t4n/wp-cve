<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Base\Widgets;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

/**
 *  Map
 */
class Map extends Widget
{
	/**
	 * Widget default options
	 *
	 * @var array
	 */
	protected $widget_options = [
		'latitude' => '',
		'longitude' => '',

		'markers' => [],

		'scale' => '',

		'zoomControl' => true,

		'zoom' => 5,

		/**
		 * Block
		 */
		// Padding
		'padding' => null,

		// Margin
		'margin' => null,

		/**
		 * Border
		 */
		'borderColor' => null,
		'borderWidth' => null,
		'borderStyle' => null,
		'borderRadius' => null,

		/**
		 * Colors
		 */
		'backgroundColor' => null,

		/**
		 * Box Shadow
		 */
		'boxShadow' => null
	];

	/**
	 * Class constructor
	 *
	 * @param array $options
	 */
	public function __construct($options = [])
	{
		parent::__construct($options);

		$this->setCSSVars();
		$this->setResponsiveCSS();
	}

	/**
	 * Set widget CSS vars
	 * 
	 * @return  mixed
	 */
	private function setCSSVars()
	{
		if (!$this->options['load_css_vars'])
		{
			return;
		}

		$atts = [];

		if (!empty($this->options['backgroundColor']))
		{
			$atts['background-color'] = $this->options['backgroundColor'];
		}

		if (!empty($this->options['borderWidth']) && !empty($this->options['borderStyle']) && !empty($this->options['borderColor']))
		{
			$atts['border'] = $this->options['borderWidth'] . 'px ' . $this->options['borderStyle'] . ' ' . $this->options['borderColor'];
		}

		if (empty($atts))
		{
			return;
		}

		if (!$css = \FPFramework\Helpers\CSS::cssVarsToString($atts, '.fpf-map-widget.' . $this->options['id']))
		{
			return;
		}

		$this->options['custom_css'] = $css;
	}

	/**
	 * Sets the CSS for the responsive settings.
	 * 
	 * @return  void
	 */
	private function setResponsiveCSS()
	{
		$initial_breakpoints = [
			'desktop' => [],
			'tablet' => [],
			'mobile' => []
		];
		$responsive_css = $initial_breakpoints;

		// Add block padding
		if ($padding = \FPFramework\Helpers\Controls\Spacing::getResponsiveSpacingControlValue($this->options['padding'], 'padding', 'px'))
		{
			$responsive_css = array_merge_recursive($responsive_css, $padding);
		}

		// Add block margin
		if ($margin = \FPFramework\Helpers\Controls\Spacing::getResponsiveSpacingControlValue($this->options['margin'], 'margin', 'px'))
		{
			$responsive_css = array_merge_recursive($responsive_css, $margin);
		}

		// Add block border radius
		if ($borderRadius = \FPFramework\Helpers\Controls\BorderRadius::getResponsiveSpacingControlValue($this->options['borderRadius'], '--border-radius', 'px'))
		{
			$responsive_css = array_merge_recursive($responsive_css, $borderRadius);
		}

		// Add block box shadow
		if ($boxShadow = \FPFramework\Helpers\Controls\BoxShadow::getResponsiveControlValue($this->options['boxShadow'], '--box-shadow', 'px'))
		{
			$responsive_css = array_merge_recursive($responsive_css, $boxShadow);
		}

		if ($css = \FPFramework\Helpers\Responsive::renderResponsiveCSS($responsive_css, '.fpf-map-widget.' . $this->options['id']))
		{
			$this->options['custom_css'] .= $css;
		}
	}

	/**
	 * Registers assets
	 * 
	 * @return  void
	 */
	public static function register_assets($script = true)
	{
		wp_register_style(
			'fpframework-leaflet',
			FPF_MEDIA_URL . 'public/css/vendor/leaflet.css',
			[],
			FPF_VERSION,
			false
		);

		wp_register_script(
			'fpframework-leaflet',
			FPF_MEDIA_URL . 'public/js/vendor/leaflet.js',
			[],
			FPF_VERSION,
			true
		);
		
		wp_register_style(
			'fpframework-widget',
			FPF_MEDIA_URL . 'public/css/widget.css',
			[],
			FPF_VERSION,
			false
		);

		if ($script)
		{
			wp_register_script(
				'fpframework-map-widget',
				FPF_MEDIA_URL . 'public/js/widgets/map.js',
				['fpframework-leaflet'],
				FPF_VERSION,
				true
			);
		}

		wp_register_style(
			'fpframework-map-widget',
			FPF_MEDIA_URL . 'public/css/widgets/map.css',
			['fpframework-leaflet'],
			FPF_VERSION,
			false
		);
	}

	/**
	 * Enqueues assets
	 * 
	 * @return  void
	 */
	public function enqueue_assets()
	{
		wp_enqueue_style('fpframework-leaflet');
		wp_enqueue_script('fpframework-leaflet');

		if ($this->options['load_stylesheet'])
		{
			wp_enqueue_style('fpframework-map-widget');
			wp_enqueue_style('fpframework-widget');
		}
		else
		{
			wp_deregister_style('fpframework-map-widget');
			wp_deregister_style('fpframework-widget');
		}

		if (!$this->options['readonly'] && !$this->options['disabled'])
		{
			wp_enqueue_script('fpframework-map-widget');
		}
		else
		{
			wp_deregister_script('fpframework-map-widget');
		}
	}

	/**
	 * Registers & enqueues assets
	 * 
	 * @return  void
	 */
	public function public_assets()
	{
		self::register_assets();
		$this->enqueue_assets();
	}
}