<?php
namespace Ari_Cf7_Button\Forms;

use Ari\Forms\Form as Form;

class Settings extends Form {
    function __construct( $options = array() ) {
        if ( ! isset( $options['prefix'] ) ) {
            $options['prefix'] = ARICF7BUTTON_SETTINGS_NAME;
        }

        parent::__construct( $options );
    }

    protected function setup() {
        $this->register_groups(
            array(
                'general',
            )
        );

        $this->register_fields(
            array(
                array(
                    'id' => 'order_by',

                    'label' => __( 'Order by', 'contact-form-7-editor-button' ),

                    'description' => __( 'Forms will be sorted by the selected field.', 'contact-form-7-editor-button' ),

                    'type' => 'select',

                    'options' => array(
                        'id' => __( 'ID', 'contact-form-7-editor-button' ),

                        'date' => __( 'Date', 'contact-form-7-editor-button'),

                        'modified' => __( 'Last modified', 'contact-form-7-editor-button'),

                        'title' => __( 'Title', 'contact-form-7-editor-button' ),
                    ),

                    'postfix' => true,
                ),

                array(
                    'id' => 'order_dir',

                    'label' => __( 'Order direction', 'contact-form-7-editor-button' ),

                    'description' => __( 'Forms will be sorted in the selected direction.', 'contact-form-7-editor-button' ),

                    'type' => 'select',

                    'options' => array(
                        'ASC' => __( 'Ascending', 'contact-form-7-editor-button' ),

                        'DESC' => __( 'Descending', 'contact-form-7-editor-button' ),
                    ),

                    'postfix' => true,
                ),

                array(
                    'id' => 'load_via_ajax',

                    'label' => __( 'Load data via AJAX', 'contact-form-7-editor-button' ),

                    'description' => __( 'If the parameter is enabled, contact form list will be loaded via AJAX when the button is clicked. Better for performance, but requires a little time to load data.', 'contact-form-7-editor-button' ),

                    'type' => 'checkbox',

                    'postfix' => true,
                ),
            ),

            'general'
        );
    }
}
