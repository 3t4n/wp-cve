<?php

class CLP_Customizer_Editor_Control extends WP_Customize_Control {

    public $type = 'editor';
    /**
    ** Render the content on the theme customizer page
    */
    public function render_content() { ?>
        <label>
          <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
          <?php
            $settings = array(
              'media_buttons' => false,
              'quicktags' => false,
              'editor_height' => 200
            );
            $this->filter_editor_setting_link();
            wp_editor($this->value(), $this->id, $settings );
          ?>
        </label>
    <?php
        do_action('admin_footer');
        do_action('admin_print_footer_scripts');
    }

    private function filter_editor_setting_link() {
        add_filter( 'the_editor', function( $output ) { return preg_replace( '/<textarea/', '<textarea ' . $this->get_link(), $output, 1 ); } );
    }
}


function editor_customizer_script() {
    wp_enqueue_script( 'wp-editor-customizer', CLP_PLUGIN_PATH . 'assets/js/customizer-tinymce.js', array( 'jquery' ), '1.0', true );
}
add_action( 'customize_controls_enqueue_scripts', 'editor_customizer_script' );