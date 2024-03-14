<?php

namespace ElementinvaderAddonsForElementor\Widgets;

use ElementinvaderAddonsForElementor\Core\Elementinvader_Base;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Typography;
use Elementor\Editor;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Core\Schemes;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use ElementinvaderAddonsForElementor\Modules\Forms\Ajax_Handler;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class EliCurrentDate extends Elementinvader_Base {

    public $view_folder = 'current_date';

    // Default widget settings
    public function __construct($data = array(), $args = null) {

        \Elementor\Controls_Manager::add_tab(
                'tab_content',
                esc_html__('Main', 'elementinvader-addons-for-elementor')
        );

        parent::__construct($data, $args);
    }

    /**
     * Retrieve the widget name.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'eli-currentdate';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('Eli Current Date', 'elementinvader-addons-for-elementor');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-date';
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function register_controls() {

        /* START Section settings_source */
        $this->start_controls_section(
            'tab_settings_section_basic',
            [
                'label' => esc_html__('Basic', 'elementinvader-addons-for-elementor'),
                'tab' => '1',
            ]
        );

        $this->add_control(
            'currentdate_format',
            [
                'label' => __( 'Date Format', 'elementinvader-addons-for-elementor' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Y',
                'description' => '<span class="eli_control_get_pro">'.sprintf(esc_html__(' %1$sExample Date Format%2$s','elementinvader-addons-for-elementor'),'<a href="https://www.php.net/manual/en/datetime.format.php" target="_blank">','</a>').'</span>'
            ]
        );

        $this->add_control(
            'baseTimestamp',
            [
                'label' => __( 'Custom baseTimestamp', 'elementinvader-addons-for-elementor' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'description' => '<span class="eli_control_get_pro">'.sprintf(esc_html__(' %1$sGet Examples%2$s','elementinvader-addons-for-elementor'),'<a href="https://www.php.net/manual/ru/function.strtotime.php" target="_blank">','</a>').'
                                 <br><br><b>'.esc_html__('Examples','elementinvader-addons-for-elementor').': </b>
                                 <br><b> +1 day </b> - '.esc_html__('Date +1 day','elementinvader-addons-for-elementor').'
                                 <br><b> +1 week </b> - '.esc_html__('Date +1 week','elementinvader-addons-for-elementor').'
                                 <br><b> +1 year </b> - '.esc_html__('Date +1 year','elementinvader-addons-for-elementor').'
                                 <br><b> +1 week 2 days 4 hours 2 seconds </b> - '.esc_html__('+1 week 2 days 4 hours 2 seconds','elementinvader-addons-for-elementor').'
                                 <br><b> last Monday </b> - '.esc_html__('last Monday','elementinvader-addons-for-elementor').'
                                 </span>'
            ]
        );

        $this->add_control(
			'prefix',
			[
				'label' => esc_html__( 'Before Date', 'elementinvader-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
			]
		);

        $this->add_control(
			'suffix',
			[
				'label' => esc_html__( 'After Date', 'elementinvader-addons-for-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
			]
		);


        $this->end_controls_section();

        $this->start_controls_section(
            'styles',
            [
                'label' => esc_html__('Styles', 'elementinvader-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
            );

        $selectors = array(
            'normal' => '{{WRAPPER}} .eli_currentdate',
            'hover'=>'{{WRAPPER}} .eli_currentdate%1$s'
        );
        $this->generate_renders_tabs($selectors, 'text_dynamic', 'full');

        $this->end_controls_section();

        $this->start_controls_section(
            'styles_date',
            [
                'label' => esc_html__('Special For Date', 'elementinvader-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
            );

        $selectors = array(
            'normal' => '{{WRAPPER}} .eli_currentdate .current_date',
            'hover'=>'{{WRAPPER}} .eli_currentdate .current_date%1$s'
        );
        $this->generate_renders_tabs($selectors, 'currentdate_dynamic', 'full', ['align']);

        $this->end_controls_section();

        parent::register_controls();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function render() {
        parent::render();

        $id_int = substr($this->get_id_int(), 0, 3);
        $settings = $this->get_settings();

        $object = ['settings'=>$settings, 'id_int'=>$id_int];

        $object['is_edit_mode'] = false;          
        if(Plugin::$instance->editor->is_edit_mode())
            $object['is_edit_mode'] = true;

        $object['baseTimestamp'] = time();
        if(!empty($settings['baseTimestamp'])) {
            $object['baseTimestamp'] = strtotime($settings['baseTimestamp']);
        }

        echo $this->view('widget_layout', $object); 
    }

}
