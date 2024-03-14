<?php
/**
 * Subscription
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
namespace Definitive_Addons_Elementor\Elements;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use \Elementor\Widget_Base;

defined('ABSPATH') || die();
/**
 * Subscription
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developers.elementor.com/docs/
 */
class Subscription extends Widget_Base
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
        return __('DA: subscription', 'definitive-addons-for-elementor');
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
        return 'dafe_subscription';
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
         return 'eicon-mailchimp';
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
        return [ 'subscription', 'mail', 'email','MailChimp' ];
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
            'dafe_section_subscription',
            [
                'label' => __('MailChimp Subscription', 'definitive-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'subscription_text',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' => __('Subscription text', 'definitive-addons-for-elementor'),
                'default' => __('Subscription text', 'definitive-addons-for-elementor')
            ]
        );
        $this->add_control(
            'title_tag',
            [
            'label' => __('Label Tag', 'definitive-addons-for-elementor'),
            'type' =>Controls_Manager::SELECT,
            'default' => 'h6',
                
            'options' => [
            'h1' => __('H1', 'definitive-addons-for-elementor'),
            'h2' => __('H2', 'definitive-addons-for-elementor'),
            'h3' => __('H3', 'definitive-addons-for-elementor'),
            'h4' => __('H4', 'definitive-addons-for-elementor'),
            'h5' => __('H5', 'definitive-addons-for-elementor'),
            'h6' => __('H6', 'definitive-addons-for-elementor'),
            'span' =>__('Span', 'definitive-addons-for-elementor')
            ],
            ]
        );

        $this->add_control(
            'mailchimp_form_action_url',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            'description' =>__('Please follow our documentation.', 'definitive-addons-for-elementor') .' <a href="https://the-gap-docs.themenextlevel.com/subscription/" target="_blank">'. __('Visit', 'definitive-addons-for-elementor') .'</a>',
                'label' => __('Insert action form url', 'definitive-addons-for-elementor'),
            ]
        );
        
        $this->add_control(
            'subscription_alignment',
            [
            'label' =>__('Set Alignment', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'label_block' => true,
            'options' => [
                    
            'left' => [
            'title' =>__('Left', 'definitive-addons-for-elementor'),
            'icon' => 'fa fa-align-left',
            ],
            'center' => [
            'title' =>__('Center', 'definitive-addons-for-elementor'),
            'icon' => 'fa fa-align-center',
            ],
            'right' => [
            'title' =>__('Right', 'definitive-addons-for-elementor'),
            'icon' => 'fa fa-align-right',
            ],
            ],
            'default' => 'center',
                
            ]
        );
        
        $this->add_control(
            'subscription_style',
            [
            'label' =>__('Subscription Layout', 'definitive-addons-for-elementor'),
            'type' => Controls_Manager::SELECT2,
                
            'options' => [
                    
            'inline' =>__('Inline', 'definitive-addons-for-elementor'),
            'block' =>__('Block', 'definitive-addons-for-elementor'),
                    
                    
            ],
            'default' => 'inline',
                
            ]
        );
        
        
    
        $this->end_controls_section();
    
        // style
        $this->start_controls_section(
            'subscription_section_style_button',
            [
                'label' => __('Button Style', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->start_controls_tabs(
            'dafe_button_tab_colors',
            [
            'label' => __('Button Colors', 'definitive-addons-for-elementor'),
            ]
        );

        $this->start_controls_tab(
            'dafe_button_normal_color_tab',
            [
            'label' => __('Normal', 'definitive-addons-for-elementor'),
            ]
        );
        
        $this->add_control(
            'button_color',
            [
                'label' => __('Text Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} input[type="submit"]' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'button_background',
                'selector' => '{{WRAPPER}} input[type="submit"]',
                'exclude' => [
                    'image'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Button Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'btn_box_shadow',

            'selector' => '{{WRAPPER}} input[type="submit"]',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} input[type="submit"]',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'dafe_button_hover_tab',
            [
            'label' => __('Hover', 'definitive-addons-for-elementor'),
            ]
        );
        
        $this->add_control(
            'button_hover_color',
            [
                'label' =>__('Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} input[type="submit"]:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_hover_bg_color',
            [
                'label' =>__('Hover Background Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} input[type="submit"]:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
            'label' => __('Hover Shadow', 'definitive-addons-for-elementor'),
            'name'     => 'btn_hvr_shadow',

            'selector' => '{{WRAPPER}} input[type="submit"]:hover',
            ]
        );
        
        $this->add_control(
            'button_border_hvr_color',
            [
                'label' => __('Border Hover Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                
                'selectors' => [
                    '{{WRAPPER}} input[type="submit"]:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        
        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => __('Border Radius', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );
        
        

        $this->end_controls_section();
        
        // Text style
        
        $this->start_controls_section(
            'title_section_style',
            [
                'label' => __('Label Text', 'definitive-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'title_bottom_spacing',
            [
                'label' => __('Text Bottom Spacing', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .subscription-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Text Color', 'definitive-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .subscription-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_font',
                'selector' => '{{WRAPPER}} .subscription-text',
                
            ]
        );


        $this->end_controls_section();
    }
    
	

	protected function render() {
        $settings = $this->get_settings_for_display();
		$subscription_alignment = $this->get_settings_for_display('subscription_alignment');
		
		$title_tag = $this->get_settings_for_display('title_tag');
		$subscription_style = $this->get_settings_for_display('subscription_style');
		$styles = 'text-align:'.$subscription_alignment.';';
		
        ?>

        <div class="news-letter-widget" style="<?php echo esc_attr($styles); ?>">
			<div id="mc_embed_signup">										
				<form action="<?php echo wp_kses_post($settings['mailchimp_form_action_url']); ?>" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
					<div id="mc_embed_signup_scroll">
					<<?php echo esc_attr($title_tag	); ?> class="subscription-text <?php echo esc_attr($subscription_style); ?>"> <?php echo esc_html($settings['subscription_text']); ?> </<?php echo esc_attr($title_tag	); ?>>
						<input type="email" value="" name="EMAIL" class="email subscription-email <?php echo esc_attr($subscription_style); ?>" id="mce-EMAIL" placeholder="email address" required>
						<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
    
						<div style="position:absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_36ed95383025fad333259809d_c621a6885d" tabindex="-1" value="">
						</div>
    
						<input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe1" class="subscribe-button <?php echo esc_attr($subscription_style); ?>">
					</div>
    
				</form>
			</div>
		</div>

        <?php
    }
	
}
