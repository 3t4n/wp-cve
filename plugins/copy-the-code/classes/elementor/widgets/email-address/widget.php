<?php

/**
 * Elementor Email Address Block
 *
 * @package Copy the Code
 * @since 3.1.0
 */
namespace CopyTheCode\Elementor\Block\Email;

use  CopyTheCode\Helpers ;
use  Elementor\Widget_Base ;
use  Elementor\Controls_Manager ;
/**
 * Email Address Block
 *
 * @since 3.1.0
 */
class Address extends Widget_Base
{
    public function __construct( $data = array(), $args = null )
    {
        parent::__construct( $data, $args );
        // Core.
        wp_enqueue_style(
            'ctc-blocks',
            COPY_THE_CODE_URI . 'classes/blocks/assets/css/style.css',
            [],
            COPY_THE_CODE_VER,
            'all'
        );
        wp_enqueue_script(
            'ctc-clipboard',
            COPY_THE_CODE_URI . 'assets/js/clipboard.js',
            [ 'jquery' ],
            COPY_THE_CODE_VER,
            true
        );
        // Block.
        wp_enqueue_style(
            'ctc-el-email-address',
            COPY_THE_CODE_URI . 'classes/elementor/widgets/email-address/style.css',
            [ 'ctc-blocks' ],
            COPY_THE_CODE_VER,
            'all'
        );
    }
    
    public function get_script_depends()
    {
        return [ 'ctc-el-email-address' ];
    }
    
    public function get_style_depends()
    {
        return [ 'ctc-el-email-address' ];
    }
    
    public function get_name()
    {
        return 'ctc_copy_email_address';
    }
    
    public function get_title()
    {
        return esc_html__( 'Email Address', 'copy-the-code' );
    }
    
    public function get_icon()
    {
        return 'eicon-email-field';
    }
    
    public function get_categories()
    {
        return Helpers::get_categories();
    }
    
    public function get_keywords()
    {
        return Helpers::get_keywords( [
            'email',
            'copy',
            'content',
            'address'
        ] );
    }
    
    public function render()
    {
        $email = $this->get_settings( 'email' );
        if ( empty($email) ) {
            return;
        }
        ?>
		<span class="ctc-block ctc-email-address">
			<a href="mailto:<?php 
        echo  esc_attr( $email ) ;
        ?>" class="ctc-block-content">
				<?php 
        echo  esc_html( $email ) ;
        ?>
			</a>
			<span class="ctc-block-copy ctc-block-copy-icon" role="button" aria-label="Copied">
				<?php 
        echo  Helpers::get_svg_copy_icon() ;
        ?>
				<?php 
        echo  Helpers::get_svg_checked_icon() ;
        ?>
			</span>
			<?php 
        Helpers::render_copy_content( $this, [
            'content' => $email,
        ] );
        ?>
		</span>
		<?php 
    }
    
    protected function _register_controls()
    {
        $this->start_controls_section( 'email_address_section', [
            'label' => esc_html__( 'Email', 'copy-the-code' ),
        ] );
        $this->add_control( 'email', [
            'label'   => esc_html__( 'Email Address', 'copy-the-code' ),
            'type'    => Controls_Manager::TEXT,
            'default' => 'contact@clipboard.agency',
            'dynamic' => [
            'active' => true,
        ],
        ] );
        $this->end_controls_section();
        Helpers::register_pro_sections( $this, [ 'Email Address', 'Icon' ] );
    }

}