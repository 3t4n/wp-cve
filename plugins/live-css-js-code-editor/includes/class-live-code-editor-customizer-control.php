<?php

/**
 * Customizer Controls 
 *
 * @link       http://www.ozanwp.com
 * @since      1.0.0
 *
 * @package    Live_Code_Editor
 * @subpackage Live_Code_Editor/includes
 * @author     Ozan Canakli <ozan@ozanwp.com>
 */

/**
 * Live code editor custom customizer control
 *
 * @since 1.0.0
 */

if ( class_exists( 'WP_Customize_Control' ) ) {

    final class Live_Code_Editor_Customizer_Control extends WP_Customize_Control {

        /**
         * If true, the preview button for a control will be rendered.
         *
         * @since 1.0.0
         */
        public $preview_button = false;

        /**
         * Set the default mode for code controls.
         *
         * @since 1.0.0
         */
        public $mode = 'html';

        /**
         * Renders the code customizer control.
         *
         * @since 1.0.0
         * @access protected
         */
        protected function render_content()
        {
            switch($this->type) {

                case 'code':

                    /* Renders title */
                    if(!empty($this->label)) {
                        echo '<span class="customize-control-title">' . esc_html($this->label) . '</span>';
                    }

                    /* Renders derscription */
                    if(!empty($this->description)) {
                        echo '<span class="customize-control-description">' . $this->description . '</span>';
                    }
                    
                    /* Renders preview button */
                    if ( $this->preview_button ) {
                        echo '<input type="button" name="lce-preview-button" class="button lce-preview-button" value="' . __('Live Preview', 'live-css-js-code-editor') . '" />';
                    }
                    
                    echo '<label>';
                    echo '<textarea rows="20" style="width:100%" ';
                    $this->link();
                    echo '>' . $this->value() . '</textarea>';
                    echo '<div class="lce-code-editor" data-mode="' . $this->mode . '"></div>';
                    echo '</label>';

                break;

            }
        }

    }
}