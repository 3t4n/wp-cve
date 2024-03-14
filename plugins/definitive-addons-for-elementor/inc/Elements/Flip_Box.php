<?php
/**
 * Flip Box
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
namespace Definitive_Addons_Elementor\Elements;
use Elementor\Group_Control_Background;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use \Elementor\Widget_Base;

defined('ABSPATH') || die();

/**
 * Flip Box
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Flip_Box extends Widget_Base
{
    /**
     * Get widget name.
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'dafe_flip_box';
    }
    
    /**
     * Get element title.
     *
     * @access public
     *
     * @return string element title.
     */
    public function get_title()
    {
        return __('DA: Flip Box', 'definitive-addons-for-elementor');
    }
    
    /**
     * Get element icon.
     *
     * @access public
     *
     * @return string element icon.
     */
    public function get_icon()
    {
        return 'eicon-lightbox';
    }
    
    /**
     * Get element categories.
     *
     * @access public
     *
     * @return string element categories.
     */
    public function get_categories()
    {
        return [ 'definitive-addons' ];
    }
    
    /**
     * Registering widget content controls
     *
     * @return void.
     */
    protected function register_controls()
    {

        
        $this->start_controls_section(
            'flip_box_label',
            [
            'label' =>__('Flip Box', 'definitive-addons-for-elementor')
            ]
        );
        

        $this->add_control(
            'box_image',
            [
            'label' => __('Upload Image', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::MEDIA,
            'default' => [
            'url' => Utils::get_placeholder_image_src(),
            ],
            ]
        );
        
        $this->add_control(
            'show_hide_title',
            [
                'label' => __('Show/Hide Title', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
            'return_value' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'box_title',
            [
            'label' =>__('Flip Box Title', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'condition' => [
            'show_hide_title' => 'yes'
            ],
            'default' =>__('John Doe', 'definitive-addons-for-elementor'),
            ]
        );
        
        $this->add_control(
            'title_tag',
            [
                'label' => __('Title HTML Tag', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
               
                'options' => [
                    'h1'  => [
                        'title' => __('H1', 'definitive-addons-for-elementor'),
                        'icon' => 'eicon-editor-h1'
                    ],
                    'h2'  => [
                        'title' => __('H2', 'definitive-addons-for-elementor'),
                        'icon' => 'eicon-editor-h2'
                    ],
                    'h3'  => [
                        'title' => __('H3', 'definitive-addons-for-elementor'),
                        'icon' => 'eicon-editor-h3'
                    ],
                    'h4'  => [
                        'title' => __('H4', 'definitive-addons-for-elementor'),
                        'icon' => 'eicon-editor-h4'
                    ],
                    'h5'  => [
                        'title' => __('H5', 'definitive-addons-for-elementor'),
                        'icon' => 'eicon-editor-h5'
                    ],
                    'h6'  => [
                        'title' => __('H6', 'definitive-addons-for-elementor'),
                        'icon' => 'eicon-editor-h6'
                    ]
                ],
                'condition' => [
                'show_hide_title' => 'yes'
                ],
                'default' => 'h3',
               
            ]
        );
        
        $this->add_control(
            'show_hide_subtitle',
            [
                'label' => __('Show/Hide Sub Title', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
            'return_value' => 'yes',
                'frontend_available' => true,
            ]
        );
        
        $this->add_control(
            'box_subtitle',
            [
            'label' =>__('Sub Title', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::TEXT,
            'condition' => [
            'show_hide_subtitle' => 'yes'
            ],
            'default' =>__('Add subtitle or leave it blank.', 'definitive-addons-for-elementor'),
            ]
        );
        
        $this->add_control(
            'show_hide_text',
            [
                'label' => __('Show/Hide Text', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
            'return_value' => 'yes',
                'frontend_available' => true,
            ]
        );
        
        $this->add_control(
            'box_text',
            [
            'label' =>__('Flip Box Text', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::TEXTAREA,
            'condition' => [
            'show_hide_text' => 'yes'
            ],
            'default' =>__('Add flip-box text here or leave it blank.', 'definitive-addons-for-elementor'),
            ]
        );
        
        

        $this->end_controls_section();

        // style
       
        $this->start_controls_section(
            'flip_style_size',
            [
                'label' =>__('Flip Box Size', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'flip_box_width',
            [
                'label' => __('Flip Box Width', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
            'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1200,
                    ],
                ],
               
                'default' => [
                'size' => 300
                ],
                'selectors' => [
                    '{{WRAPPER}} .flip-box-container' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'flip_box_height',
            [
                'label' =>__('Flip Box Height', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
            'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1200,
                    ],
                ],
                'default' => [
                'size' => 300
                ],
                'selectors' => [
                    '{{WRAPPER}} .flip-box-container' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'flip_box_img_width',
            [
                'label' => __('Flip Box Image Width', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
            'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1200,
                    ],
                ],
               
                'default' => [
                'size' => 300
                ],
                'selectors' => [
                    '{{WRAPPER}} .flip-box-frontend img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'flip_box_img_height',
            [
                'label' => __('Flip Box Image Height', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
            'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1200,
                    ],
                ],
                'default' => [
                'size' => 300
                ],
                'selectors' => [
                    '{{WRAPPER}} .flip-box-frontend img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'flip_style_title',
            [
                'label' => __('Flip Box Title', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __('Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
            'default' => [
            'size' => 15
                ],
                'selectors' => [
                    '{{WRAPPER}} .title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'title_hover_color',
            [
                'label' => __('Title Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .title:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_font',
                'selector' => '{{WRAPPER}} .title',
                
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
            'name' => 'title_shadow',
            'selector' => '{{WRAPPER}} .title',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
            'name' => 'title_stroke',
            'selector' => '{{WRAPPER}} .title',
            ]
        );

          $this->end_controls_section();

        $this->start_controls_section(
            'flip_style_subtitle',
            [
                'label' =>__('Flip Box Subtitle', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        

        $this->add_responsive_control(
            'subtitle_spacing',
            [
                'label' => __('Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __('Subtitle Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .subtitle' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_font',
                'selector' => '{{WRAPPER}} .subtitle',
                
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section(
            'flip_style_text',
            [
                'label' =>__('Flip Box Text', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
 
        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '.site-main {{WRAPPER}} .flip-cta p' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_font',
                'selector' => '.site-main {{WRAPPER}} .flip-cta p',
                
            ]
        );
        
        

        $this->end_controls_section();
        
        $this->start_controls_section(
            'flip_style_content',
            [
                'label' => __('Flip Box Container', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'content_background',
                'selector' => '{{WRAPPER}} .flip-box-container,{{WRAPPER}} .flip-box-backend',
                'exclude' => [
                    'image'
                ]
            ]
        );
        $this->add_control(
            'container_bg_hvr_color',
            [
                'label' => __('Container Hover Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .flip-box-backend:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Container Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'flip_box_shadow',

            'selector' => '{{WRAPPER}} .flip-box-container',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Container Hover Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'flip_box_hvr_shadow',

            'selector' => '{{WRAPPER}} .flip-box-container:hover',
            ]
        );
        
        
        $this->end_controls_section();
        

    }

	protected function render( ) {
		
      $settings = $this->get_settings_for_display();
	  $title = $this->get_settings_for_display('box_title');
	  $subtitle = $this->get_settings_for_display('box_subtitle');
	  
	  $flip_box_alignment = $this->get_settings_for_display('flip_box_alignment');

	  $title_tag = $this->get_settings_for_display( 'title_tag' );
	  $image = $settings['box_image']['url'];
	
     
                ?>

               <div class="flip-box-container">
					<div class="flip-box">
						<div class="flip-box-frontend">
							<img src="<?php echo esc_url($image); ?>" alt="<?php esc_attr_e('Avatar','definitive-addons-for-elementor'); ?>">
						</div>
						<div class="flip-box-backend">
						  <div class="flip-cta">
						  <?php if ( $settings['box_title'] ) : ?>
							<<?php echo esc_attr($title_tag) ?> class="title"><?php echo esc_html($title) ?></<?php echo esc_attr($title_tag) ?>> 
							<?php endif; ?>
							<?php if ( $settings['box_subtitle'] ) : ?>
							<h5 class="subtitle"><?php echo esc_html($subtitle) ?></h5> 
							<?php endif; ?>
							<?php if ( $settings['box_text'] ) : ?>
							<p class="box_text"><?php echo esc_html($settings['box_text']) ?></p>
							<?php endif; ?>
						  </div>
						</div>
					</div>
				</div>

	<?php
	}
}