<?php

class CRP_Meta_Box {

    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'add_meta_box' ), 10 );
    }

    public function add_meta_box()
    {
	    $post_types = CustomRelatedPosts::setting( 'general_post_types' );

	    foreach( $post_types as $post_type ) {
		    add_meta_box(
			    'crp_meta_box',
			    'Custom Related Posts',
			    array( $this, 'meta_box_shortcode' ),
			    $post_type,
			    'normal',
			    'high',
                array(
                    '__back_compat_meta_box' => true,
                )
		    );
	    }
    }

    public function meta_box_shortcode( $post )
    {
        include( CustomRelatedPosts::get()->coreDir . '/helpers/meta_box_content.php' );
    }
}