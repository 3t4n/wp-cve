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
 * Countdown
 */
class Countdown extends Widget
{
	/**
	 * Widget default options
	 *
	 * @var array
	 */
	protected $widget_options = [
		/**
		 * The Countdown type:
		 * 
		 * - static: Counts down to a specific date and time. Universal deadline for all visitors.
		 * - dynamic: Set-and-forget solution. The countdown starts when your visitor sees the offer.
		 */
		'countdown_type' => 'static',

		// The Static Countdown Date
		'value' => '',

		/**
		 * The timezone that will be used.
		 * 
		 * - server - Use server's timezone
		 * - client - Use client's timezone
		 */
		'timezone' => 'server',

		// Dynamic Days
		'dynamic_days' => 0,

		// Dynamic Hours
		'dynamic_hours' => 0,

		// Dynamic Minutes
		'dynamic_minutes' => 0,

		// Dynamic Seconds
		'dynamic_seconds' => 0,
		
		/**
		 * The countdown format.
		 * 
		 * Available tags:
		 * {years}
		 * {months}
		 * {days}
		 * {hours}
		 * {minutes}
		 * {seconds}
		 */
		'format' => '{days} days, {hours} hours, {minutes} minutes and {seconds} seconds',

		/**
		 * The countdown theme.
		 * 
		 * Available themes:
		 * default
		 * custom
		 */
		'theme' => 'default',

		/**
		 * Set the action once countdown finishes.
		 * 
		 * Available values:
		 * keep 	- Keep the countdown visible
		 * hide 	- Hide the countdown
		 * restart 	- Restart the countdown
		 * message	- Show a message
		 * redirect	- Redirect to a URL
		 */
		'countdown_action' => 'keep',

		/**
		 * The message appearing after the countdown has finished.
		 * 
		 * Requires `countdown_action` to be set to `message`
		 * 
		 * Example: Countdown finished.
		 */
		'finish_text' => '',

		/**
		 * The redirect URL once the countdown expires.
		 * 
		 * Requires `countdown_action` to be set to `redirect`
		 */
		'redirect_url' => '',

		/**
		 * Block Settings
		 */
		// Alignment
		'align' => '',

		// Padding
		'padding' => null,

		// Margin
		'margin' => null,

		// Gap
		'gap' => 20,
		
		// Background Color
		'background_color' => '',

		/**
		 * Unit Display Settings
		 */
		// Whether to display Days
		'days' => true,

		// Days Label
		'days_label' => 'Days',
		
		// Whether to display Hours
		'hours' => true,

		// Hours Label
		'hours_label' => 'Hrs',
		
		// Whether to display Minutes
		'minutes' => true,

		// Minutes Label
		'minutes_label' => 'Mins',
		
		// Whether to display Seconds
		'seconds' => true,
		
		// Seconds Label
		'seconds_label' => 'Secs',
		
		// Whether to display a separator between the units
		'separator' => false,
		
		// Whether to display numbers in 00 or 0 format
		'double_zeroes_format' => true,

		/**
		 * Unit Item Settings
		 */
		// The size (width, height) of the unit item in pixels
		'item_size' => null,
		
		// The unit item border width
		'item_border_width' => '',

		// The unit item border style
		'item_border_style' => '',

		// The unit item border color
		'item_border_color' => '',

		// The unit item border radius
		'item_border_radius' => null,

		// Item Background Color
		'item_background_color' => '',

		/**
		 * Unit Digits Container Settings
		 */
		// Digits wrapper Min Width
		'digits_wrapper_min_width' => 0,

		// The digits wrapper padding
		'digits_wrapper_padding' => null,

		// The digits wrapper border radius
		'digits_wrapper_border_radius' => null,

		// The digits wrapper background color.
		'digits_wrapper_background_color' => '',

		/**
		 * Unit Digit Settings
		 */
		// Digit Min Width
		'digit_min_width' => 0,

		// Digits Font Size
		'digits_font_size' => 25,

		// Digits Font Weight
		'digits_font_weight' => '400',

		// The digits padding
		'digits_padding' => null,

		// The digits border radius
		'digit_border_radius' => null,

		// Digits Gap
		'digits_gap' => null,

		// Digit Item Background Color. This applies for each of the 2 digits on a unit.
		'digit_background_color' => '',

		// Digit Item Text Color
		'digit_text_color' => '',

		/**
		 * Unit Label Settings
		 */
		// Label Font Size
		'label_font_size' => 13,

		// Label Font Weight
		'label_font_weight' => '400',

		// Unit Label Margin Top. The spacing between the unit and its label.
		'unit_label_margin_top' => 5,

		// Unit Label Color
		'unit_label_text_color' => '',

		// Extra attributes added to the widget
		'atts' => '',

		// Custom CSS printed after the widget assets
		'custom_css' => '',

		// Preview HTML used prior to JS initializing the Countdown
		'preview_html' => ''
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
	 * Prepares the countdown.
	 * 
	 * @return  void
	 */
	private function prepare()
	{
		$this->options['css_class'] .= ' is-preview ' . $this->options['theme'] . ' ' . $this->options['align'];

		if (!empty($this->options['value']) && $this->options['value'] !== '0000-00-00 00:00:00')
		{
			if ($this->options['countdown_type'] === 'static' && $this->options['timezone'] === 'server')
			{
				// Convert given date time to UTC
				$this->options['value'] = get_gmt_from_date($this->options['value'], 'c');
				
				// Get timezone
				$tz = new \DateTimeZone(wp_timezone()->getName());

				// Apply server timezone
				$this->options['value'] = (new \DateTime($this->options['value']))->setTimezone($tz)->format('c');
			}
		}

		$this->options['preview_html'] = $this->getPreviewHTML();

		// Set countdown payload
		$payload = [
			'data-countdown-type="' . esc_attr($this->options['countdown_type']) . '"',
			'data-value="' . esc_attr($this->options['value']) . '"',
			'data-timezone="' . esc_attr($this->options['timezone']) . '"',
			'data-separator="' . esc_attr(var_export($this->options['separator'], true)) . '"',
			'data-double-zeroes-format="' . esc_attr(var_export($this->options['double_zeroes_format'], true)) . '"',
			'data-dynamic-days="' . esc_attr($this->options['dynamic_days']) . '"',
			'data-dynamic-hours="' . esc_attr($this->options['dynamic_hours']) . '"',
			'data-dynamic-minutes="' . esc_attr($this->options['dynamic_minutes']) . '"',
			'data-dynamic-seconds="' . esc_attr($this->options['dynamic_seconds']) . '"',
			'data-finish-text="' . esc_attr(htmlspecialchars($this->options['finish_text'])) . '"',
			'data-redirect-url="' . esc_attr($this->options['redirect_url']) . '"',
			'data-theme="' . esc_attr($this->options['theme']) . '"',
			'data-countdown-action="' . esc_attr($this->options['countdown_action']) . '"',
			'data-days="' . esc_attr(var_export($this->options['days'], true)) . '"',
			'data-days-label="' . esc_attr($this->options['days_label']) . '"',
			'data-hours="' . esc_attr(var_export($this->options['hours'], true)) . '"',
			'data-hours-label="' . esc_attr($this->options['hours_label']) . '"',
			'data-minutes="' . esc_attr(var_export($this->options['minutes'], true)) . '"',
			'data-minutes-label="' . esc_attr($this->options['minutes_label']) . '"',
			'data-seconds="' . esc_attr(var_export($this->options['seconds'], true)) . '"',
			'data-seconds-label="' . esc_attr($this->options['seconds_label']) . '"'
		];

		// Only set the format for custom-themed countdown instances
		if ($this->options['theme'] === 'custom')
		{
			$payload[] = 'data-format="' . esc_attr(htmlspecialchars($this->options['format'])) . '"';
		}
		
		$this->options['atts'] = implode(' ', $payload);
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

		if (!empty($this->options['background_color']))
		{
			$atts['background-color'] = $this->options['background_color'];
		}

		if ($this->options['theme'] !== 'custom')
		{
			if (!empty($this->options['digits_wrapper_background_color']))
			{
				$atts['digits-background-color'] = $this->options['digits_wrapper_background_color'];
			}

			if (!empty($this->options['item_background_color']))
			{
				$atts['item-background-color'] = $this->options['item_background_color'];
			}

			if (!empty($this->options['unit_label_text_color']))
			{
				$atts['unit-label-text-color'] = $this->options['unit_label_text_color'];
			}

			if (!empty($this->options['digit_background_color']))
			{
				$atts['digit-background-color'] = $this->options['digit_background_color'];
			}

			if (!empty($this->options['digit_text_color']))
			{
				$atts['digit-text-color'] = $this->options['digit_text_color'];
			}

			if (!empty($this->options['unit_label_margin_top']))
			{
				$atts['unit-label-margin-top'] = $this->options['unit_label_margin_top'] . 'px';
			}

			if (!empty($this->options['digits_wrapper_min_width']))
			{
				$atts['digits-wrapper-min-width'] = $this->options['digits_wrapper_min_width'] . 'px';
			}

			if (!empty($this->options['digit_min_width']))
			{
				$atts['digit-min-width'] = $this->options['digit_min_width'] . 'px';
			}

			if (!empty($this->options['digits_font_weight']))
			{
				$atts['digits-font-weight'] = $this->options['digits_font_weight'];
			}

			if (!empty($this->options['label_font_weight']))
			{
				$atts['label-font-weight'] = $this->options['label_font_weight'];
			}

			if (!empty($this->options['item_border_width']) && !empty($this->options['item_border_style']) && !empty($this->options['item_border_color']))
			{
				$atts['item-border'] = $this->options['item_border_width'] . 'px ' . $this->options['item_border_style'] . ' ' . $this->options['item_border_color'];
			}
		}

		if (empty($atts))
		{
			return;
		}

		if (!$css = \FPFramework\Helpers\CSS::cssVarsToString($atts, '.fpf-countdown.' . $this->options['id']))
		{
			return;
		}

		$this->options['custom_css'] .= $css;
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
		
		if ($this->options['theme'] !== 'custom')
		{
			// Add digits wrapper padding
			if ($digits_wrapper_padding = \FPFramework\Helpers\Controls\Spacing::getResponsiveSpacingControlValue($this->options['digits_wrapper_padding'], '--digits-padding', 'px'))
			{
				$responsive_css = array_merge_recursive($responsive_css, $digits_wrapper_padding);
			}
			
			// Add gap
			if ($gap = \FPFramework\Helpers\Controls\Responsive::getResponsiveControlValue($this->options['gap'], '--gap', 'px'))
			{
				$responsive_css = array_merge_recursive($responsive_css, $gap);
			}
			
			// Add digits gap
			if ($gap = \FPFramework\Helpers\Controls\Responsive::getResponsiveControlValue($this->options['digits_gap'], '--digits-gap', 'px'))
			{
				$responsive_css = array_merge_recursive($responsive_css, $gap);
			}

			// Add Item Size
			if ($item_size = \FPFramework\Helpers\Controls\Responsive::getResponsiveControlValue($this->options['item_size'], '--item-size', 'px'))
			{
				$responsive_css = array_merge_recursive($responsive_css, $item_size);
			}

			// Add Digits Font Size
			if ($digits_font_size = \FPFramework\Helpers\Controls\Responsive::getResponsiveControlValue($this->options['digits_font_size'], '--digits-font-size', 'px'))
			{
				$responsive_css = array_merge_recursive($responsive_css, $digits_font_size);
			}

			// Add Label Font Size
			if ($label_font_size = \FPFramework\Helpers\Controls\Responsive::getResponsiveControlValue($this->options['label_font_size'], '--label-font-size', 'px'))
			{
				$responsive_css = array_merge_recursive($responsive_css, $label_font_size);
			}

			// Add Digits Padding
			if ($digitsPadding = \FPFramework\Helpers\Controls\Spacing::getResponsiveSpacingControlValue($this->options['digits_padding'], '--digit-padding', 'px'))
			{
				$responsive_css = array_merge_recursive($responsive_css, $digitsPadding);
			}

			// Add item border radius
			if ($itemBorderRadius = \FPFramework\Helpers\Controls\BorderRadius::getResponsiveSpacingControlValue($this->options['item_border_radius'], '--item-border-radius', 'px'))
			{
				$responsive_css = array_merge_recursive($responsive_css, $itemBorderRadius);
			}

			// Add digits wrapper border radius
			if ($borderRadius = \FPFramework\Helpers\Controls\BorderRadius::getResponsiveSpacingControlValue($this->options['digits_wrapper_border_radius'], '--digits-border-radius', 'px'))
			{
				$responsive_css = array_merge_recursive($responsive_css, $borderRadius);
			}

			// Add digits wrapper border radius
			if ($borderRadius = \FPFramework\Helpers\Controls\BorderRadius::getResponsiveSpacingControlValue($this->options['digit_border_radius'], '--digit-border-radius', 'px'))
			{
				$responsive_css = array_merge_recursive($responsive_css, $borderRadius);
			}
		}

		if ($css = \FPFramework\Helpers\Responsive::renderResponsiveCSS($responsive_css, '.fpf-countdown.' . $this->options['id']))
		{
			$this->options['custom_css'] .= $css;
		}
	}

	/**
	 * Returns preview HTML.
	 * 
	 * @return  string
	 */
	private function getPreviewHTML()
	{
		if ($this->options['theme'] === 'custom')
		{
			return $this->options['format'];
		}

		$format_items = [
			'days' => $this->options['days'],
			'hours' => $this->options['hours'],
			'minutes' => $this->options['minutes'],
			'seconds' => $this->options['seconds']
		];

		$html = '';

		foreach ($format_items as $key => $value)
		{
			$labelStr = !empty($this->options[$key . '_label']) ? '<span class="countdown-digit-label">' . $this->options[$key . '_label'] . '</span>' : '';
			$html .= '<span class="countdown-item"><span class="countdown-digit ' . $key . '"><span class="digit-number digit-1">0</span><span class="digit-number digit-2">0</span></span>' . $labelStr . '</span>';
		}
		
		return $html;
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

		wp_register_script(
			'fpframework-countdown-widget',
			FPF_MEDIA_URL . 'public/js/widgets/countdown.js',
			[],
			FPF_VERSION,
			true
		);

		wp_register_style(
			'fpframework-countdown-widget',
			FPF_MEDIA_URL . 'public/css/widgets/countdown.css',
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
		if ($this->options['load_stylesheet'])
		{
			if ($this->options['theme'] !== 'custom')
			{
				wp_enqueue_style('fpframework-countdown-widget');
			}
			else
			{
				// Ensure digits appear without jumping
				$atts = [
					'font-variant-numeric' => 'tabular-nums'
				];
				$default_css = \FPFramework\Helpers\CSS::cssVarsToString($atts, '.fpf-countdown.' . $this->options['id'] . ' .digit-number');
				$this->options['custom_css'] .= $default_css;

				wp_enqueue_style('fpframework-widget');
			}
		}
		else
		{
			wp_deregister_style('fpframework-countdown-widget');
			wp_deregister_style('fpframework-widget');
		}

		wp_enqueue_script('fpframework-countdown-widget');
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

		self::localize_script();
	}

	/**
	 * Adds widget-specific JS object on the DOM.
	 * 
	 * @return  void
	 */
	public static function localize_script()
	{
		wp_localize_script('fpframework-countdown-widget', 'fpframework_countdown_widget', [
			'AND' => fpframework()->_('FPF_AND')
		]);
	}
}