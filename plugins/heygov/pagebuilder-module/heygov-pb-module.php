<?php 

class HeyGovModule extends FLBuilderModule {

    public function __construct() {
        parent::__construct(array(
            'name'            => __( 'Heygov Widget', 'fl-builder' ),
            'description'     => __( 'Display heygov widget', 'fl-builder' ),
            'category'        => __( 'Heygov', 'fl-builder' ),
            'dir'             => HEYGOV_DIR . 'pagebuilder-module/',
            'url'             => HEYGOV_DIR . 'pagebuilder-module/',
            'icon'            => 'button.svg',
            'editor_export'   => true, // Defaults to true and can be omitted.
            'enabled'         => true, // Defaults to true and can be omitted.
            'partial_refresh' => true, // Defaults to false and can be omitted.
        ));
    }
}

FLBuilder::register_module( 'HeyGovModule',  array(
    'general'      => array(
        'title'         => __( 'General', 'fl-builder' ),
        'sections'      => array(
            'my-section'  => array(
                'title'         => __( 'Basic Settings', 'fl-builder' ),
                'fields'        => array(
                    'title'     => array(
                        'type'          => 'text',
                        'label'         => __( 'Title', 'fl-builder' ),
                        'default'       => 'Report Issue',
                        'preview' => array(
                            'type' => 'callback',
                            'callback' => 'heygovCallback',
                        )
                    ),
                    'description'     => array(
                        'type'          => 'textarea',
                        'label'         => __( 'Description', 'fl-builder' ),
                        'default'       => 'Use the form below to submit an issue.',
                        'preview' => array(
                            'type' => 'callback',
                            'callback' => 'heygovCallback',
                        )
                    )
                )
            )
        )
    )
) );
