<?php

/**
 * Elementor Email Sample Block
 *
 * @package Copy the Code
 * @since 3.1.0
 */
namespace CopyTheCode\Elementor\Block\Email;

use  CopyTheCode\Helpers ;
use  Elementor\Widget_Base ;
use  Elementor\Controls_Manager ;
/**
 * Email Sample Block
 *
 * @since 3.1.0
 */
class Sample extends Widget_Base
{
    /**
     * Constructor
     *
     * @param array $data
     * @param array $args
     *
     * @since 3.1.0
     */
    public function __construct( $data = array(), $args = null )
    {
        parent::__construct( $data, $args );
        // Core.
        wp_enqueue_style(
            'ctc-blocks-core',
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
        wp_enqueue_script(
            'ctc-blocks-core',
            COPY_THE_CODE_URI . 'classes/blocks/assets/js/core.js',
            [ 'ctc-clipboard' ],
            COPY_THE_CODE_VER,
            true
        );
        // Block.
        wp_enqueue_style(
            'ctc-el-email-sample',
            COPY_THE_CODE_URI . 'classes/elementor/widgets/email-sample/style.css',
            [ 'ctc-blocks-core' ],
            COPY_THE_CODE_VER,
            'all'
        );
    }
    
    /**
     * Get script dependencies
     */
    public function get_script_depends()
    {
        return [ 'ctc-el-email-sample' ];
    }
    
    /**
     * Get style dependencies
     */
    public function get_style_depends()
    {
        return [ 'ctc-blocks-core' ];
    }
    
    /**
     * Get name
     */
    public function get_name()
    {
        return 'ctc_copy_email_sample';
    }
    
    /**
     * Get title
     */
    public function get_title()
    {
        return esc_html__( 'Email Sample', 'copy-the-code' );
    }
    
    /**
     * Get icon
     */
    public function get_icon()
    {
        return 'eicon-facebook-comments';
    }
    
    /**
     * Get categories
     */
    public function get_categories()
    {
        return Helpers::get_categories();
    }
    
    /**
     * Get keywords
     */
    public function get_keywords()
    {
        return Helpers::get_keywords( [
            'email',
            'copy',
            'content',
            'template'
        ] );
    }
    
    /**
     * Render
     */
    public function render()
    {
        $sample_email = $this->get_settings_for_display( 'sample_email' );
        if ( empty($sample_email) ) {
            return;
        }
        $display_content = preg_replace( '/\\[([^\\]]*)\\]/', '<span class="ctc-email-highlight">[$1]</span>', $sample_email );
        $display_content = wpautop( $display_content );
        ?>
		<div class="ctc-block ctc-email-sample">
			<div class="ctc-block-content">
				<?php 
        echo  wp_kses_post( $display_content ) ;
        ?>
			</div>
			<div class="ctc-block-actions">
				<?php 
        Helpers::render_copy_button( $this );
        ?>
			</div>
            <?php 
        Helpers::render_copy_content( $this, [
            'content' => wp_kses_post( $sample_email ),
        ] );
        ?>
		</div>
		<?php 
    }
    
    /**
     * Register controls
     */
    protected function _register_controls()
    {
        /**
         * Group: Email Section
         */
        $this->start_controls_section( 'email_section', [
            'label' => esc_html__( 'Email Sample', 'copy-the-code' ),
        ] );
        $this->add_control( 'sample_email', [
            'label'       => esc_html__( 'Email Sample', 'copy-the-code' ),
            'type'        => Controls_Manager::TEXTAREA,
            'default'     => "Subject: Application for [Job Title] - [Your Name]\r\n\r\nDear [Hiring Manager's Name],\r\n\r\nI hope this email finds you well. I am writing to express my strong interest in the [Job Title] position at [Company Name], as advertised on your website. With my background in [Relevant Skill/Experience] and a passion for [Company's Mission or Industry], I believe I am a strong fit for your team.\r\n\r\nSincerely,\r\n[Your Name]\r\n[Your Contact Information]",
            'rows'        => 10,
            'description' => esc_html__( 'Use [ ] to highlight the text.', 'copy-the-code' ),
        ] );
        $this->end_controls_section();
        Helpers::register_copy_button_section( $this );
        Helpers::register_pro_sections( $this, [ 'Email Sample', 'Highlight Text' ] );
        Helpers::register_copy_button_style_section( $this );
    }

}