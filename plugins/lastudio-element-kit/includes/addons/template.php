<?php

/**
 * Class: LaStudioKit_Template
 * Name: Template
 * Slug: lakit-template
 */

namespace Elementor;

use Elementor\Core\Files\CSS\Post as Post_CSS;

if ( ! defined( 'WPINC' ) ) {
  die;
}


/**
 * Template Widget
 */
class LaStudioKit_Template extends LaStudioKit_Base {

  public function get_name() {
    return 'lakit-template';
  }

  protected function get_html_wrapper_class() {
    return 'elementor-' . $this->get_name();
  }

  protected function get_widget_title() {
    return esc_html__( 'Template', 'lastudio-kit' );
  }

  public function get_icon() {
    return 'eicon-document-file';
  }

  protected function register_controls() {

    $this->_start_controls_section(
      'section_template',
      array(
        'label' => esc_html__( 'Template', 'lastudio-kit' ),
      )
    );

    $this->add_control(
      'panel_template_id',
      array(
        'label'       => esc_html__( 'Choose Template', 'lastudio-kit' ),
        'label_block' => 'true',
        'type'        => 'lastudiokit-query',
        'object_type' => \Elementor\TemplateLibrary\Source_Local::CPT,
        'filter_type' => 'by_id',
      )
    );

    $this->_end_controls_section();

  }

  protected function render() {

    $this->_context = 'render';

    $panel_settings = $this->get_settings();

    $template_id = isset( $panel_settings['panel_template_id'] ) ? $panel_settings['panel_template_id'] : '0';

    $template_id = apply_filters('wpml_object_id', $template_id, 'elementor_library', true);

    if ( ! empty( $template_id ) ) {
        ob_start();
        if(Plugin::instance()->editor->is_edit_mode()){
            $css_file = Post_CSS::create( $template_id );
            echo sprintf('<link rel="stylesheet" id="elementor-post-%1$s-css" href="%2$s" type="text/css" media="all" />', $template_id, $css_file->get_url() );
        }
        echo Plugin::$instance->frontend->get_builder_content( $template_id, false );
        $content_html = ob_get_clean();
    } else {
      $content_html = $this->no_templates_message();
    }

    ?>
    <div class="lakit-template">
      <?php

      if ( ! empty( $template_id ) ) {
        $link = add_query_arg(
          array(
            'elementor' => '',
          ),
          get_permalink( $template_id )
        );

        if ( lastudio_kit_integration()->in_elementor() ) {
          echo sprintf( '<div class="lakit-tabs__edit-cover" data-template-edit-link="%s"><i class="eicon-edit"></i><span>%s</span></div>', $link, esc_html__( 'Edit Template', 'lastudio-kit' ) );
        }
      }

      echo sprintf( '<div class="lakit-template-inner">%1$s</div>', $content_html );
      ?>
    </div>
    <?php
  }

  /**
   * Empty templates message description
   *
   * @return string
   */
  public function empty_templates_message() {
    return '<div id="elementor-widget-template-empty-templates">
				<div class="elementor-widget-template-empty-templates-icon"><i class="eicon-nerd"></i></div>
				<div class="elementor-widget-template-empty-templates-title">' . esc_html__( 'You Haven’t Saved Templates Yet.', 'lastudio-kit' ) . '</div>
				<div class="elementor-widget-template-empty-templates-footer">' . esc_html__( 'What is Library?', 'lastudio-kit' ) . ' <a class="elementor-widget-template-empty-templates-footer-url" href="https://trk.elementor.com/docs-library/" target="_blank">' . esc_html__( 'Read our tutorial on using Library templates.', 'lastudio-kit' ) . '</a></div>
				</div>';
  }

  /**
   * No templates message
   *
   * @return string
   */
  public function no_templates_message() {
    $message = '<span>' . esc_html__( 'Template is not defined. ', 'lastudio-kit' ) . '</span>';

    $url = add_query_arg(
      array(
        'post_type'     => 'elementor_library',
        'action'        => 'elementor_new_post',
        '_wpnonce'      => wp_create_nonce( 'elementor_action_new_post' ),
        'template_type' => 'section',
      ),
      esc_url( admin_url( '/edit.php' ) )
    );

    $new_link = '<span>' . esc_html__( 'Select an existing template or create a ', 'lastudio-kit' ) . '</span><a class="lakit-tabs-new-template-link elementor-clickable" href="' . $url . '" target="_blank">' . esc_html__( 'new one', 'lastudio-kit' ) . '</a>';

    return sprintf(
      '<div class="lakit-tabs-no-template-message">%1$s%2$s</div>',
      $message,
      lastudio_kit_integration()->in_elementor() ? $new_link : ''
    );
  }

}