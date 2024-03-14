<?php

class PL_Theme_Biznol_Default_setup
{

	public function __construct()
	{
		$this->set_up();
	}

    public function set_up()
    {
         /* Homepage */
         $this->home();

		  /* widget */
		  $this->widget();
		 
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

	public function widget() {

		$activate = array(
			'footer_widget_area' => array(
				'text-1',
				'categories-3',
				'archives-1',
				'search-3',
			),
		);

		/* the default titles will appear */
		update_option(
			'widget_text',
			array(
				1 => array(
					'title' => '',
					'text'  => '<p>Set your site even further apart from the crowd using our animation options and transition effects.</p>
				<ul class="social d-inline-flex">
                      <li><a href="#" class="btn-default"><i class="fa fa-facebook"></i></a></li>
                      <li><a href="#" class="btn-default"><i class="fa fa-twitter"></i></a></li>
                      <li><a href="#" class="btn-default"><i class="fa fa-youtube"></i></a></li>
                      <li><a href="#" class="btn-default"><i class="fa fa-instagram"></i></a></li>
				</ul>',
				),
			)
		);

		update_option(
			'widget_categories',
			array(
				3 => array( 'title' => 'Categories' ),
			)
		);

		update_option(
			'widget_archives',
			array(
				1 => array( 'title' => 'Archives' ),
			)
		);

		update_option(
			'widget_search',
			array(
				3 => array( 'title' => 'Search' ),
			)
		);

		update_option( 'sidebars_widgets', $activate );
	}

}
