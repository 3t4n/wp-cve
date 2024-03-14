<?php
/**
 * Testimonial
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
namespace Definitive_Addons_Elementor\Elements;
use Elementor\Group_Control_Background;
use Elementor\Repeater;
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
 * Testimonial
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Testimonial extends Widget_Base
{
    
    /**
     * Get widget title.
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('DA: Testimonial', 'definitive-addons-for-elementor');
    }

      /**
       * Get element name.
       *
       * @access public
       *
       * @return string element name.
       */ 
    public function get_name()
    {
        return 'dafe_testimonial';
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
        return 'eicon-testimonial';
    }
    /**
     * Get element keywords.
     *
     * @access public
     *
     * @return string element keywords.
     */
    public function get_keywords()
    {
        return [ 'testimonial', 'image', 'review' ];
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
     * Register widget content controls
     *
     * @return void.
     */
    protected function register_controls()
    {
        
        $this->start_controls_section(
            'section_testimonial',
            [
                'label' => __('Testimonial', 'definitive-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        

        $this->add_control(
            'image',
            [
                'type' => Controls_Manager::MEDIA,
                'label' => __('Image', 'definitive-addons-for-elementor'),
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'thumbnail',
                'separator' => 'before',
                'exclude' => [
                    'custom'
                ]
            ]
        );
        
        $this->add_control(
            'link',
            [
            'label' =>__('Link', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::URL,
                
            'placeholder' =>__('https://softfirm.com', 'definitive-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'name',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' => __('Reviewer Name', 'definitive-addons-for-elementor'),
                'default' => __('John Doe', 'definitive-addons-for-elementor')
            ]
        );
        
        $this->add_control(
            'title',
            [
                'type' => Controls_Manager::TEXT,
                
                'label' => __('Title', 'definitive-addons-for-elementor'),
                'default' => __('Developer', 'definitive-addons-for-elementor')
            ]
        );
        
        $this->add_control(
            'show_hide_organization',
            [
                'label' => __('Show/Hide Organization', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
            'default' => 'no',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
            'return_value' => 'yes',
                'frontend_available' => true,
                
            ]
        );
        
        $this->add_control(
            'organization',
            [
                'type' => Controls_Manager::TEXT,
                
                'label' => __('Organization', 'definitive-addons-for-elementor'),
                'default' => __('Softfirm', 'definitive-addons-for-elementor'),
            'condition' => [
                        'show_hide_organization' => 'yes',
                ],
                
            ]
        );
        
        

        $this->add_control(
            'reviewer_text',
            [
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'label' => __('Reviewer Text', 'definitive-addons-for-elementor'),
                'default' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.', 'definitive-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'testimonial_alignment',
            [
            'label' =>__('Testimonial Layout', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::SELECT,
            'label_block' => true,
            'options' => [
            'left' =>__('Image Left - Content Right', 'definitive-addons-for-elementor'),
            'right' =>__('Image Right - Content Left', 'definitive-addons-for-elementor'),
            'top' =>__('Image Top - Content Down', 'definitive-addons-for-elementor'),
            'bottom' =>__('Image Down - Content Top', 'definitive-addons-for-elementor'),
                    
            ],
            'default' => 'top',
                
            ]
        );
        
        $this->add_control(
            'show_hide_quote',
            [
                'label' => __('Show/Hide Quote Icon', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
                'label_on' => __('Yes', 'definitive-addons-for-elementor'),
                'label_off' => __('No', 'definitive-addons-for-elementor'),
            'return_value' => 'yes',
                'frontend_available' => true,
                
            ]
        );


        $this->end_controls_section();

       
   
        // image style
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => __('Reviewer Image', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'image_size',
            [
                'label' => __('Image Size', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
            'default' => [
            'unit' => 'px',
            'size' => 100,
                ],
                'range'      => [
                        
                'px' => [
                'min' => 10,
                'max' => 200,
                ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-testimonial-image img' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .dafe-testimonial-image img',
            ]
        );

        $this->add_responsive_control(
            'entry_border_radius',
            [
                'label' => __('Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ '%', 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-testimonial-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_right_spacing',
            [
                'label' => __('Right Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px','%'],
            'default' => [
            'size' => 20
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-testimonial-image' => 'margin-right: {{SIZE}}{{UNIT}}!important;',
                ],
                'condition' => [
                        'testimonial_alignment' => 'left',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'image_left_spacing',
            [
                'label' => __('Left Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px','%'],
            'default' => [
            'size' => 20
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-testimonial-image' => 'margin-left: {{SIZE}}{{UNIT}}!important;',
                ],
                'condition' => [
                        'testimonial_alignment' => 'right',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'image_bottom_spacing',
            [
                'label' => __('Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px','%'],
            'default' => [
            'size' => 20
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-testimonial-image' => 'margin-bottom: {{SIZE}}{{UNIT}}!important;',
                ],
                'condition' => [
                        'testimonial_alignment' => ['top','bottom']
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Image Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'dafe_image_shadow',

            'selector' => '{{WRAPPER}} .dafe-testimonial-image img',
            ]
        );


        $this->end_controls_section();

        
        // Name style
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Reviewer Name', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

       

        $this->add_responsive_control(
            'reviewer_name_spacing',
            [
                'label' => __('Right Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
            'default' => [
            'size' => 10
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-author-name' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'reviewer_name_color',
            [
                'label' => __('Name Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-author-name' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'reviewer_name_hvr_color',
            [
                'label' => __('Name Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-author-name:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'reviewer_font',
                'selector' => '{{WRAPPER}} .dafe-author-name',
                
            ]
        );

        $this->add_responsive_control(
            'name_bottom_spacing',
            [
                'label' => __('Name Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px','%'],
            'default' => [
            'size' => 20
                ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-testimonial-title' => 'margin-bottom: {{SIZE}}{{UNIT}}!important;',
                ],
               
            ]
        );
        
        $this->end_controls_section();
        
        // Title style
        $this->start_controls_section(
            'section_style_reviewer_title',
            [
                'label' => __('Reviewer Title', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

  
        $this->add_control(
            'reviewer_title_color',
            [
                'label' => __('Title Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-author-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'reviewer_title_font',
                'selector' => '{{WRAPPER}} .dafe-author-title',
                
            ]
        );
        
        $this->end_controls_section();
        
        
        // organization style
        $this->start_controls_section(
            'section_style_organization',
            [
                'label' => __('Reviewer Organizaiton', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            'condition' => [
                        'show_hide_organization' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'organization_color',
            [
                'label' => __('Organization Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-author-organization' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'organization_font',
                'selector' => '{{WRAPPER}} .dafe-author-organization',
                
            ]
        );

        $this->end_controls_section();
        
        // text style
        $this->start_controls_section(
            'section_style_text',
            [
                'label' => __('Reviewer Text', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'text_left_spacing',
            [
                'label' => __('Space Between Icon and Text', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px','%'],
            'default' => [
            'size' => 20
                ],
                'selectors' => [
                    '{{WRAPPER}} .speech i' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                        'show_hide_quote' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'text_spacing',
            [
                'label' => __('Text Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .dafe-testimonial-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dafe-testimonial-description,{{WRAPPER}} .speech' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_font',
                'selector' => '{{WRAPPER}} .dafe-testimonial-description,{{WRAPPER}} .speech',
                
            ]
        );

        $this->end_controls_section();
        
        
        
        // content style
        $this->start_controls_section(
            'section_style_content',
            [
                'label' => __('Testimonial Container', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __('Container Padding', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
            'default' => [
            'top' => '10',
            'right' => '10',
            'bottom' => '10',
            'left' => '10',
    
                ],
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dafe-testimonial-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                
                'name' => 'content_background',
                'selector' => '{{WRAPPER}} .dafe-testimonial-container',
                'exclude' => [
                    'image'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                
                'name' => 'content_hvr_background',
                'selector' => '{{WRAPPER}} .dafe-testimonial-container:hover',
                'exclude' => [
                    'image'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Container Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'dafe_container_shadow',

            'selector' => '{{WRAPPER}} .dafe-testimonial-container',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Container Hover Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'dafe_container_hvr_shadow',

            'selector' => '{{WRAPPER}} .dafe-testimonial-container:hover',
            ]
        );
        
        $this->end_controls_section();
  
    }

	protected function render() {
        $settings = $this->get_settings_for_display();
		$align = $this->get_settings_for_display('testimonial_alignment');
		$link = $this->get_settings_for_display('link');
	
		if (  $link['url'] ) {
			$this->add_link_attributes( 'link', $link );
		}
		?>
		
<div class="dafe-testimonial-container dafe-testimonial-image-align-<?php echo esc_attr( $align ); ?> dafe-vertical-align-middle">

	<div class="dafe-testimonial-inner-container">
	<?php if (( $align  === 'left') || ($align  === 'right') || ($align  === 'top')) : ?>	
		<figure class="dafe-testimonial-image">
			<?php 
			$testimonial_image = Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' );
							if (  $settings['link']['url'] ) :
								$testimonial_image = '<a ' . $this->get_render_attribute_string( 'link' ) . '>' . $testimonial_image . '</a>';
							endif;
							echo wp_kses_post( $testimonial_image );
			?>
		</figure>
		<?php endif; ?>
		<div class="dafe-testimonial-content">
			<div class="dafe-testimonial-title">
			<?php if ( $settings['name'] ) :  ?>
				<span class="dafe-author-name"><?php echo esc_html( $settings['name'] ); ?></span>
			<?php endif; ?>
			<?php if ( $settings['title'] ) :  ?>
				<span class="dafe-author-title"><?php echo esc_html( $settings['title'] ); ?></span>
			<?php endif; ?>
			<?php if ( $settings['organization'] ) :  ?>
				<h5 class="dafe-author-organization"><?php echo esc_html( $settings['organization'] ); ?></h5>
			<?php endif; ?>
			</div>
			<?php if ($settings['reviewer_text'] ){ ?>
			<div class="dafe-testimonial-description">
			<p>
			<?php if ($settings['show_hide_quote'] == 'yes'){ ?>
				<blockquote class="speech"><i class="fa fa-quote-left"></i><?php echo wp_kses_post( $settings['reviewer_text'] ); ?>
			</blockquote>
			<?php
			}  else { ?>
                <blockquote class="speech"><?php echo wp_kses_post( $settings['reviewer_text'] ); ?>
                </blockquote> 
                <?php
			}
			?>
			</p>
			</div>
			<?php } ?>
		</div>
		
		<?php if ( $align  === 'bottom') : ?>
		<figure class="dafe-testimonial-image">
			<?php 
			$testimonial_image = Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' );
							if (  $settings['link']['url'] ) :
								$testimonial_image = '<a ' . $this->get_render_attribute_string( 'link' ) . '>' . $testimonial_image . '</a>';
							endif;
							echo wp_kses_post( $testimonial_image );
			?>
		</figure>
		<?php endif; ?>
	</div>

</div>
	

        <?php
    }
}
