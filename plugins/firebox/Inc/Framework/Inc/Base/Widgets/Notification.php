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
 *  Notification
 */
class Notification extends Widget
{
	/**
	 * Widget default options
	 *
	 * @var array
	 */
	protected $widget_options = [
		/**
		 * The type.
		 * 
		 * Available values:
		 * - information
		 * - success
		 * - error
		 * - warning
		 */
		'type' => 'information',

		/**
		 * The theme.
		 * 
		 * Available values:
		 * 
		 * - theme1
		 * - theme2
		 * - theme3
		 */
		'theme' => 'theme1',

		/**
		 * Whether to display the title.
		 * 
		 * If null, the theme provides the value.
		 */
		'enable_title' => null,

		// The title
		'title' => '',

		// Title margin bottom
		'title_margin_bottom' => 10,

		// The message
		'message' => '',

		/**
		 * The skin.
		 * 
		 * Available values:
		 * 
		 * - light
		 * - color
		 * - dark
		 * - custom (Allows to select custom text and background color)
		 */
		'skin' => 'light',

		/**
		 * The border radius.
		 */
		'border_radius' => null,

		/**
		 * Whether to display the close button or not.
		 * 
		 * If null, the theme provides the value.
		 */
		'show_close_button' => null,

		// The text color
		'text_color' => null,

		// The background color
		'background_color' => null,

		// Margin
		'margin' => null,

		// Title Font Size
		'title_font_size' => 17,

		// Message Font Size
		'message_font_size' => 16,

		// Whether to enable or disable setting of cookies when closing the notification
		'cookies' => false,

		// The cookie name
		'cookie_id' => '',

		// The total days when the notification will reappear once closed
		'close_cookie_days' => 1,

		// The close button color
		'close_button_color' => 'rgba(0, 0, 0, .3)',

		// The close button hover color
		'close_button_hover_color' => '#000',

		// Extra attributes added to the widget
		'atts' => '',

		// Custom CSS printed after the widget assets
		'custom_css' => ''
	];

	/**
	 * Class constructor
	 *
	 * @param array $options
	 */
	public function __construct($options = [])
	{
		parent::__construct($options);

		$this->options['css_class'] .= ' ' . $this->options['theme'] . ' type-' . $this->options['type'] . ' ' . $this->options['skin'];

		// On readonly, do not allow interaction (closing of notification via close button)
		if ($this->options['readonly'])
		{
			$this->options['css_class'] .= ' readonly';
		}

		if (is_null($this->options['enable_title']))
		{
			if (in_array($this->options['theme'], ['theme2', 'theme3']))
			{
				$this->options['enable_title'] = true;
			}
		}

		$this->setCSSVars();
		$this->setResponsiveCSS();
	}

	/**
	 * Sets CSS variables.
	 * 
	 * @return  void
	 */
	private function setCSSVars()
	{
		if (!$this->options['load_css_vars'])
		{
			return;
		}
		
		$css_vars = [];

		// Custom colors apply only to custom skin
		if ($this->options['skin'] === 'custom')
		{
			if (!empty($this->options['text_color']))
			{
				$css_vars['text-color'] = $this->options['text_color'];
			}

			if (!empty($this->options['background_color']))
			{
				$css_vars['background-color'] = $this->options['background_color'];
			}
		}

		if (!empty($this->options['close_button_color']))
		{
			$css_vars['close-button-color'] = $this->options['close_button_color'];
		}

		if (!empty($this->options['close_button_hover_color']))
		{
			$css_vars['close-button-hover-color'] = $this->options['close_button_hover_color'];
		}

		if (empty($css_vars))
		{
			return;
		}

		if (!$css = \FPFramework\Helpers\CSS::cssVarsToString($css_vars, '.fpf-notification.' . $this->options['id']))
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
		$responsive_css = [
			'desktop' => [],
			'tablet' => [],
			'mobile' => []
		];
		
		// Add title margin bottom
		if ($title_margin_bottom = \FPFramework\Helpers\Controls\Responsive::getResponsiveControlValue($this->options['title_margin_bottom'], '--title-margin-bottom', 'px'))
		{
			$responsive_css = array_merge_recursive($responsive_css, $title_margin_bottom);
		}
		
		// Add border radius
		if ($border_radius = \FPFramework\Helpers\Controls\BorderRadius::getResponsiveSpacingControlValue($this->options['border_radius'], '--border-radius', 'px'))
		{
			$responsive_css = array_merge_recursive($responsive_css, $border_radius);
		}

		// Add margin
		if ($margin = \FPFramework\Helpers\Controls\Spacing::getResponsiveSpacingControlValue($this->options['margin'], 'margin', 'px'))
		{
			$responsive_css = array_merge_recursive($responsive_css, $margin);
		}

		// Add Title Font Size
		if ($title_font_size = \FPFramework\Helpers\Controls\Responsive::getResponsiveControlValue($this->options['title_font_size'], '--title-font-size', 'px'))
		{
			$responsive_css = array_merge_recursive($responsive_css, $title_font_size);
		}

		// Add Message Font Size
		if ($message_font_size = \FPFramework\Helpers\Controls\Responsive::getResponsiveControlValue($this->options['message_font_size'], '--message-font-size', 'px'))
		{
			$responsive_css = array_merge_recursive($responsive_css, $message_font_size);
		}

		if (!$css = \FPFramework\Helpers\Responsive::renderResponsiveCSS($responsive_css, '.fpf-notification.' . $this->options['id']))
		{
			return;
		}

		$this->options['custom_css'] .= $css;
	}

	/**
	 * Registers assets
	 * 
	 * @param   boolean  $js   Whether to register the JS assets
	 * 
	 * @return  void
	 */
	public static function register_assets($js = true)
	{
		wp_register_style(
			'fpframework-widget',
			FPF_MEDIA_URL . 'public/css/widget.css',
			[],
			FPF_VERSION,
			false
		);

		wp_register_style(
			'fpframework-notification-widget',
			FPF_MEDIA_URL . 'public/css/widgets/notification.css',
			[],
			FPF_VERSION,
			false
		);

		if ($js)
		{
			wp_register_script(
				'fpframework-notification-widget',
				FPF_MEDIA_URL . 'public/js/widgets/notification.js',
				[],
				FPF_VERSION,
				true
			);
		}
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
			wp_enqueue_script('fpframework-notification-widget');
		}
		else
		{
			wp_deregister_script('fpframework-notification-widget');
		}

		if ($this->options['load_stylesheet'])
		{
			wp_enqueue_style('fpframework-notification-widget');
		}
		else
		{
			wp_deregister_style('fpframework-notification-widget');
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