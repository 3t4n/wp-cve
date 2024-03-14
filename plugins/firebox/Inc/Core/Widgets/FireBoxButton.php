<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\Widgets;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

require_once FBOX_PLUGIN_DIR . 'Inc/Framework/Inc/Includes/Abstracts/FPF_Widget.php';

class FireBoxButton extends \FPF_Widget
{
	public function __construct() {
		$this->widget_cssclass    = '';
		$this->widget_description = firebox()->_('FB_CHOOSE_BOX_TO_HANDLE');
		$this->widget_id          = 'firebox_add_button_widget';
		$this->widget_name        = firebox()->_('FB_ADD_A_FIREBOX_BUTTON');
		$this->settings           = [
			'box' => [
				'type'    => 'select',
				'default' => '',
                'label'   => firebox()->_('FB_SELECT_A_CAMPAIGN'),
                'options' => \FireBox\Core\Helpers\BoxHelper::getAllBoxesParsedByKeyValue()
            ],
			'action' => [
				'type'    => 'select',
				'default' => 'close',
                'label'   => firebox()->_('FB_FIREBOX_ACTION'),
                'options' => [
                    'open' => firebox()->_('FB_OPEN'),
                    'close' => firebox()->_('FB_CLOSE'),
                    'toggle' => firebox()->_('FB_TOGGLE'),
                ]
            ],
			'button_label' => [
				'type'    => 'text',
				'default' => firebox()->_('FB_CLOSE'),
                'label'   => firebox()->_('FB_BUTTON_LABEL')
            ],
			'button_classes' => [
				'type'    => 'text',
				'default' => 'button',
                'label'   => firebox()->_('FB_BUTTON_CLASSES')
            ],
			'button_link' => [
				'type'    => 'text',
				'default' => '',
                'label'   => firebox()->_('FB_BUTTON_LINK')
            ],
			'prevent_default' => [
				'type'    => 'checkbox',
				'default' => true,
                'label'   => firebox()->_('FB_METABOX_PREVENTDEFAULT')
            ],
        ];

		parent::__construct();
    }
    
	/**
	 * Widget Output
	 *
	 * @param   array  $args
	 * @param   array  $instance
     * 
     * @return  void
	 */
    public function widget($args, $instance)
    {
        if ($this->get_cached_widget($args))
        {
			return;
		}

		ob_start();

		$box = !empty($instance['box']) ? absint($instance['box']) : '';
		$action = !empty($instance['action']) ? strval($instance['action']) : '';
		$button_label = !empty($instance['button_label']) ? strval($instance['button_label']) : '';
		$button_classes = !empty($instance['button_classes']) ? strval($instance['button_classes']) : '';
		$button_link = !empty($instance['button_link']) ? $instance['button_link'] : '';
        $prevent_default = !empty($instance['prevent_default']) ? $instance['prevent_default'] : false;
        
        if (empty($box) || empty($action) || empty($button_label))
        {
            return;
        }

        $box = ' data-fbox="' . esc_attr($box) . '"';
        $action = ' data-fbox-cmd="' . esc_attr($action) . '"';
        $prevent_default = ' data-fbox-prevent="' . ($prevent_default ? '1' : '0') . '"';

        $this->widget_start($args, $instance);
        ?>
        <a href="<?php echo esc_url($button_link); ?>"<?php echo wp_kses_data($box . $action . $prevent_default); ?> class="<?php echo esc_attr($button_classes); ?>"><?php echo esc_html($button_label); ?></a>
        <?php
        $this->widget_end($args );

		$content = ob_get_clean();

		echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		$this->cache_widget($args, $content);
	}
}