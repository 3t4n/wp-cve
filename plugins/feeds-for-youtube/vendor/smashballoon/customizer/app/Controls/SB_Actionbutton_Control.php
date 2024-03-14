<?php

/**
 * Customizer Builder
 * Action Button Control
 *
 * @since 6.0
 */
namespace Smashballoon\Customizer\Controls;

if (!\defined('ABSPATH')) {
    exit;
}
class SB_Actionbutton_Control extends \Smashballoon\Customizer\Controls\SB_Controls_Base
{
    /**
     * Get control type.
     *
     * Getting the Control Type
     *
     * @since 6.0
     * @access public
     *
     * @return string
     */
    public function get_type()
    {
        return 'actionbutton';
    }
    /**
     * Output Control
     *
     *
     * @since 6.0
     * @access public
     */
    public function get_control_output($controlEditingTypeModel)
    {
        ?>
		<button class="sb-control-action-button sb-btn sbc-fb-fs sb-btn-grey">
			<div v-if="control.buttonIcon" v-html="svgIcons[control.buttonIcon]"></div>
			<span class="sb-small-p sb-bold sb-dark-text">{{control.label}}</span>
		</button>
		<?php 
    }
}
