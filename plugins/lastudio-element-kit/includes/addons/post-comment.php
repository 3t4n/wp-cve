<?php

/**
 * Class: LaStudioKit_Post_Comment
 * Name: Post Comment
 * Slug: lakit-post-comment
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}


/**
 * Post Comment Widget
 */
class LaStudioKit_Post_Comment extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    $this->add_style_depends( 'lastudio-kit-base' );
	    }
    }

    public function get_name() {
        return 'lakit-post-comment';
    }

    protected function get_widget_title() {
        return esc_html__( 'Post Comment', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'eicon-comments';
    }

    public function get_categories() {
        return [ 'lastudiokit-builder' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Post Comments', 'lastudio-kit' ),
            ]
        );

        $this->add_control(
            'info',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( 'This widget displays the default Comments Template included in the current Theme.', 'lastudio-kit' ) .
                    '<br><br>' .
                    __( 'No custom styling can be applied as each theme uses it\'s own CSS classes and IDs.', 'lastudio-kit' ),
                'content_classes' => 'elementor-descriptor',
            ]
        );

        $this->end_controls_section();
    }

    public function render() {

        if ( ! comments_open() && ( lastudio_kit()->elementor()->preview->is_preview_mode() || lastudio_kit()->elementor()->editor->is_edit_mode() ) ) :
            ?>
            <div class="elementor-alert elementor-alert-danger" role="alert">
				<span class="elementor-alert-title">
					<?php esc_html_e( 'Comments are closed.', 'lastudio-kit' ); ?>
				</span>
                <span class="elementor-alert-description">
					<?php esc_html_e( 'Switch on comments from either the discussion box on the WordPress post edit screen or from the WordPress discussion settings.', 'lastudio-kit' ); ?>
				</span>
            </div>
        <?php
        else :
            comments_template();
        endif;

    }
    
}