<?php

class EIC_Blocks {

    public function __construct()
    {
        add_action('init', array( $this, 'init' ) );
    }

    public function init()
    {
        if ( function_exists( 'register_block_type' ) ) {
			$block_settings = array(
				'attributes' => array(
                    'id' => array(
                        'type' => 'number',
                        'default' => 0,
                    ),
                    'updated' => array(
                        'type' => 'number',
                        'default' => 0,
                    ),
				),
				'render_callback' => array( $this, 'easy_image_collage_block' ),
			);

			register_block_type( 'easy-image-collage/collage', $block_settings );
        }
    }

    public function easy_image_collage_block( $atts )
    {
        return EasyImageCollage::get()->helper( 'shortcode' )->eic_shortcode( $atts );
    }
}