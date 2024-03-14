<?php

namespace Element_Ready\Widgets\countdown;
use \Element_Ready\Base\Controls\Widget_Control\Element_ready_common_control as Content_Style;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;

require_once( ELEMENT_READY_DIR_PATH . '/inc/style_controls/common/common.php' );
require_once( ELEMENT_READY_DIR_PATH . '/inc/style_controls/position/position.php' );
require_once( ELEMENT_READY_DIR_PATH . '/inc/style_controls/box/box_style.php' );
require_once( ELEMENT_READY_DIR_PATH . '/inc/content_controls/common.php' );

class Element_Ready_Countdown_Widget extends Widget_Base {

    use \Elementor\Element_Ready_Common_Style;
    use \Elementor\Element_ready_common_content;
    use \Elementor\Element_Ready_Box_Style;

    public $base;

    public function get_name() {
        return 'element--ready--lite--countdown';
    }

    public function get_title() {
        return esc_html__( 'ER Coundown Lite', 'element-ready-lite' );
    }

    public function get_icon() { 
        return "eicon-cloud-check";
    }

   public function get_categories() {
      return [ 'element-ready-addons' ];
   }

   public function get_style_depends() {

    wp_register_style( 'eready-countdown-lite' , ELEMENT_READY_ROOT_CSS. 'widgets/countdown.css' );
    return [ 'eready-countdown-lite' ];
  }

  
   public function get_script_depends(){
         
         return [
            'element-ready-core',
         ];
   }
  
    protected function register_controls() {

        $this->start_controls_section(
            'element_ready_countdown__content',
            [
                'label' => esc_html__( 'Content', 'element-ready-lite' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'element_ready_countdown_day',
            [
                'label'       => __( 'Day', 'element-ready-lite' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __( 'days', 'element-ready-lite' ),
                'label_block' => true,
               
            ]
        );
     
        $this->add_control(
            'element_ready_countdown_hour',
            [
                'label'       => __( 'Hour', 'element-ready-lite' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __( 'Hour', 'element-ready-lite' ),
                'label_block' => true,
               
            ]
        );

        $this->add_control(
            'element_ready_countdown_min',
            [
                'label'       => __( 'Min', 'element-ready-lite' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __( 'Min', 'element-ready-lite' ),
                'label_block' => true,
               
            ]
        );

        
        $this->add_control(
            'element_ready_countdown_sec',
            [
                'label'       => __( 'Second', 'element-ready-lite' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __( 'Sec', 'element-ready-lite' ),
                'label_block' => true,
               
            ]
        );

        $this->add_control(
            'element_ready_countdown_date',
            [
                'label' => __( 'Countdown Date', 'element-ready-lite' ),
                'type'  => \Elementor\Controls_Manager::DATE_TIME,
                'default'  => (new \DateTime('+1 day'))->format('Y-m-d H:i:s'),
            ]
        );
  

        $this->end_controls_section();
 
        $this->box_css(
            [
                'title'          => esc_html__( 'Wrapper', 'element-ready-lite' ),
                'slug'           => 'wready_counproduct_wrapper',
                'element_name'   => '_wready_counproduct_wrapper',
                'hover_selector' => false,
                'selector'       => '{{WRAPPER}} .element--ready--lite--countdown',
            ]
        );
        
        $this->box_css(
            [
                'title'          => esc_html__( 'Inner Container', 'element-ready-lite' ),
                'slug'           => 'wready_counproduct_inner_wrapper',
                'element_name'   => '_wready_counproduct_inner_wrapper',
                'hover_selector' => false,
                'selector'       => '{{WRAPPER}} .element--ready--lite--countdown--time',
            ]
        );

        $this->text_css(
            [
                'title'          => esc_html__( 'Time', 'element-ready-lite' ),
                'slug'           => 'wready_couproduct_time',
                'element_name'   => '_wready_counproduct_time',
                'selector'       => '{{WRAPPER}} .element--ready--lite--countdown--time .er-countdown--num',
                'hover_selector' => '{{WRAPPER}} .element--ready--lite--countdown--time:hover .er-countdown--num',
            ]
        );

        $this->text_css(
            [
                'title'          => esc_html__( 'Word', 'element-ready-lite' ),
                'slug'           => 'wready_couproduct_word',
                'element_name'   => '_wready_counproduct_word',
                'selector'       => '{{WRAPPER}} .element--ready--lite--countdown--time .er-countdown--word',
                'hover_selector' => '{{WRAPPER}} .element--ready--lite--countdown--time:hover .er-countdown--word',
            ]
        );



    }

    /**
     * Override By elementor render method
     * @return void
     *
     */
    protected function render() {

        $settings           = $this->get_settings_for_display();
        $coundown_date_time = explode( ' ', $settings['element_ready_countdown_date'] );
        $countdown_date     = $coundown_date_time[0];
        $countdown_time     = $coundown_date_time[1];

    ?>       
       
        <div class="element--ready--lite--countdown"
            data-sec="<?php echo esc_attr($settings['element_ready_countdown_sec']); ?>"
            data-min="<?php echo esc_attr($settings['element_ready_countdown_min']); ?>"
            data-hour="<?php echo esc_attr($settings['element_ready_countdown_hour']); ?>"
            data-day="<?php echo esc_attr($settings['element_ready_countdown_day']); ?>"
            data-date="<?php echo esc_attr($countdown_date); ?>"
            data-time="<?php echo esc_attr($countdown_time); ?>">
            <div class="element--ready--lite--day element--ready--lite--countdown--time"><span class="er-countdown--num"></span><span class="er-countdown--word"></span></div>
            <div class="element--ready--lite--hour element--ready--lite--countdown--time"><span class="er-countdown--num"></span><span class="er-countdown--word"></span></div>
            <div class="element--ready--lite--min element--ready--lite--countdown--time"><span class="er-countdown--num"></span><span class="er-countdown--word"></span></div>
            <div class="element--ready--lite--sec element--ready--lite--countdown--time"><span class="er-countdown--num"></span><span class="er-countdown--word"></span></div>
        </div>
       
         
	<?php }

}