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
 *  Video
 */
class Video extends Widget
{
	/**
	 * Widget default options
	 *
	 * @var array
	 */
	protected $widget_options = [
		// The default value of the widget. 
		'value' => '',

		// Video URL
		'videoUrl' => '',

		/**
		 * Video
		 */
		// Padding
		'padding' => null,

		// Margin
		'margin' => null,

		/**
		 * Player
		 */
		// Autoplay
		'autoplay' => false,

		// Autopause
		'autopause' => false,

		// Mute
		'mute' => false,

		// Loop
		'loop' => false,

		// Start Time
		'startTime' => false,

		// End Time
		'endTime' => false,

		// Show Branding
		'branding' => true,

		// Show Controls
		'controls' => true,

		// Privacy-Enhanced Mode
		'privacyMode' => true,

		/**
		 * Cover Image
		 */
		/**
		 * Cover Image Type
		 * 
		 * none: No Cover Image
		 * auto: Automatically retrieve cover image from service
		 * custom: Select image from Media Manager
		 */
		'coverImageType' => 'none',

		// Cover Image URL
		'coverImage' => null,

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

		$this->prepare();

		$this->setCSSVars();
		$this->setResponsiveCSS();
	}

	/**
	 * Prepares the widget.
	 * 
	 * @return  void
	 */
	private function prepare()
	{
		$videoDetails = \FPFramework\Helpers\Video::getDetails($this->options['videoUrl']);
		$videoID = isset($videoDetails['id']) ? $videoDetails['id'] : '';
		$videoProvider = isset($videoDetails['provider']) ? $videoDetails['provider'] : '';
		
		$atts = [
			'data-video-id="' . esc_attr($videoID) . '"',
			'data-video-controls="' . esc_attr(var_export($this->options['controls'], true)) . '"',
			'data-video-type="' . esc_attr($videoProvider) . '"',
			'data-video-mute="' . esc_attr(var_export($this->options['mute'], true)) . '"',
			'data-video-loop="' . esc_attr(var_export($this->options['loop'], true)) . '"',
			'data-video-start="' . esc_attr($this->options['startTime']) . '"',
			'data-video-autoplay="' . esc_attr(var_export($this->options['autoplay'], true)) . '"',
			'data-video-autopause="' . esc_attr(var_export($this->options['autopause'], true)) . '"',
			'data-video-privacy="' . esc_attr(var_export($this->options['privacyMode'], true)) . '"'
		];

		if ($videoProvider === 'youtube')
		{
			$atts = array_merge($atts, [
				'data-video-end="' . esc_attr($this->options['endTime']) . '"',
				'data-video-branding="' . esc_attr(var_export($this->options['branding'], true)) . '"',
			]);
		}

		$this->options['atts'] = implode(' ', $atts);
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

		if (!empty($this->options['coverImage']))
		{
			$atts['cover-image'] = 'url(' . $this->options['coverImage'] . ')';
		}

		if (!empty($this->options['borderWidth']) && !empty($this->options['borderStyle']) && !empty($this->options['borderColor']))
		{
			$atts['border'] = $this->options['borderWidth'] . 'px ' . $this->options['borderStyle'] . ' ' . $this->options['borderColor'];
		}

		if (empty($atts))
		{
			return;
		}

		if (!$css = \FPFramework\Helpers\CSS::cssVarsToString($atts, '.fpf-video.' . $this->options['id']))
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

		if ($css = \FPFramework\Helpers\Responsive::renderResponsiveCSS($responsive_css, '.fpf-video.' . $this->options['id']))
		{
			$this->options['custom_css'] .= $css;
		}
	}

	/**
	 * Registers assets
	 * 
	 * @return  void
	 */
	public static function register_assets()
	{
		wp_register_style(
			'fpframework-widget',
			FPF_MEDIA_URL . 'public/css/widget.css',
			[],
			FPF_VERSION,
			false
		);

		wp_register_style(
			'fpframework-video-widget',
			FPF_MEDIA_URL . 'public/css/widgets/video.css',
			[],
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
		if (!$this->options['readonly'] && !$this->options['disabled'])
		{
			wp_enqueue_script('fpframework-video-widget');
		}
		else
		{
			wp_deregister_script('fpframework-video-widget');
		}

		if ($this->options['load_stylesheet'])
		{
			wp_enqueue_style('fpframework-video-widget');
		}
		else
		{
			wp_deregister_style('fpframework-video-widget');
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