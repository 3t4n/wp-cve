<?php

class PL_Theme_Corposet_Default_setup {


	public function __construct() {
		 $this->set_up();
	}

	public function set_up() {
		/* Homepage */
		 $this->home();

		 /* About us */
		 $this->about();

		 /* Blog page */
		 $this->blog();

		 /* Portfolio */
		 $this->portfolio();

		 /* Contact */
		 $this->contact();

		 /* widget */
		 $this->widget();

		 update_option( 'pl_default_setup', true );

	}

	public function home() {
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

	public function about() {
		$post = array(
			'post_type'      => 'page',
			'post_title'     => 'About',
			'post_name'      => 'About',
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_author'    => 1,
			'post_date'      => date( 'Y-m-d H:i:s' ),
			'post_status'    => 'publish',
			'post_content'   => '<div class="col-md-6 col-sm-6 col-xs-12">
			<div class="about-img-area" ><img class="img-responsive" src="' . PL_PLUGIN_URL . 'assets/images/corposet/img.jpg" alt="Image" /></div>
			  </div>
			  <div class="col-md-6 col-sm-6 col-xs-12">
			  <h2>Our Services
			  </h2>
			  There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don’t look even slightly believable.
			  If you are going to use a passage of Lorem Ipsum, you need to be sure there isn’t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet.
			  </div>',
		);
		/* insert new page and get id */
		$pl_id_default = wp_insert_post( $post, false );
		if ( $pl_id_default && ! is_wp_error( $pl_id_default ) ) {
			update_post_meta( $pl_id_default, '_wp_page_template', 'template/template-about.php' );
		}
	}

	public function blog() {
		$post = array(
			'post_name'      => 'Blog',
			'post_title'     => 'Blog',
			'post_type'      => 'page',
			'post_author'    => 1,
			'ping_status'    => 'closed',
			'post_status'    => 'publish',
			'post_date'      => date( 'Y-m-d H:i:s' ),
			'comment_status' => 'closed',
		);
		/* insert new page and get id */
		$pl_id_default = wp_insert_post( $post, false );
		if ( $pl_id_default && ! is_wp_error( $pl_id_default ) ) {
			update_post_meta( $pl_id_default, '_wp_page_template', 'index.php' );

			// Use a static front page
			$page = get_page_by_title( 'Blog' );
			update_option( 'show_on_front', 'page' );
			update_option( 'page_for_posts', $page->ID );

		}
	}

	public function portfolio() {

		// Create page
		 $post = array(
			 'post_name'      => 'Portfolio',
			 'post_type'      => 'page',
			 'post_title'     => 'Portfolio',
			 'post_status'    => 'publish',
			 'comment_status' => 'closed',
			 'ping_status'    => 'closed',
			 'post_author'    => 1,
			 'post_date'      => date( 'Y-m-d H:i:s' ),
		 );
		 // create page and get id
		 $pl_id_default = wp_insert_post( $post, false );
		 if ( $pl_id_default && ! is_wp_error( $pl_id_default ) ) {
			 update_post_meta( $pl_id_default, '_wp_page_template', 'template/template-portfolio.php' );
		 }
	}

	public function contact() {
		// Create page
		$post = array(
			'post_name'      => 'Contact',
			'post_type'      => 'page',
			'post_title'     => 'Contact',
			'post_status'    => 'publish',
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_author'    => 1,
			'post_date'      => date( 'Y-m-d H:i:s' ),
		);
		/* create page and get id */
		$pl_id_default = wp_insert_post( $post, false );
		if ( $pl_id_default && ! is_wp_error( $pl_id_default ) ) {
			update_post_meta( $pl_id_default, '_wp_page_template', 'template/contact.php' );
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
