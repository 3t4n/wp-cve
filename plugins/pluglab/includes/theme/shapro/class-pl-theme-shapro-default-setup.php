<?php

class PL_Theme_Shapro_Default_setup
{

	public function __construct()
	{
		$this->set_up();
	}

    public function set_up()
    {
         /* Homepage */
         $this->home();
		 
		 update_option('pl_default_setup', true);

    }

    public function home()
    {   
        $postArg = array(
			'post_date'      => date( 'Y-m-d H:i:s' ),
			'comment_status' => 'closed',
			'post_author'    => 1,
			'ping_status'    => 'closed',
			'post_name'      => 'Home',
			'post_status'    => 'publish',
			'post_title'     => 'Home',
			'post_type'      => 'page',
		);
		/**
		 * Create Page
		 */
		$postID = wp_insert_post( $postArg, false );
		if ( $postID && ! is_wp_error( $postID ) ) {
			update_post_meta( $postID, '_wp_page_template', 'homepage-template.php' );

			/**
			 * Homepage
			 */
			$page = get_page_by_title( 'Home' );
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $page->ID );
		}
    }

}
