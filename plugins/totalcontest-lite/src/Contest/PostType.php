<?php

namespace TotalContest\Contest;

use TotalContestVendors\TotalCore\PostTypes\PostType as AbstractPostType;

/**
 * Contest post type.
 * @package TotalContest\Contest
 * @since   1.0.0
 */
class PostType extends AbstractPostType {
	/**
	 * Contest post type constructor.
	 */
	public function __construct() {
		parent::__construct();
		// Hook into WordPress to add some rewrite rules.
		add_action( 'init', [ $this, 'rewriteRules' ] );
		// Capabilities
		add_action( 'totalcontest/actions/activated', [ $this, 'capabilities' ] );
	}

	/**
	 * Contest rewrite rules.
	 * @since 1.0.0
	 */
	public function rewriteRules() {
		add_rewrite_rule( '^contest/(.*)/page/([^/]+)/?$', 'index.php?post_type=' . $this->getName() . '&name=$matches[1]&tc_current_page=$matches[2]', 'top' );
	}

	/**
	 * Capabilities mapping.
	 */
	public function capabilities() {
		$map = [
			'edit_contest'   => [ 'administrator', 'editor', 'author' ],
			'read_contest'   => [ 'administrator', 'editor', 'author' ],
			'delete_contest' => [ 'administrator', 'editor', 'author' ],

			'edit_contests'    => [ 'administrator', 'editor', 'author' ],
			'delete_contests'  => [ 'administrator', 'editor', 'author' ],
			'publish_contests' => [ 'administrator', 'editor', 'author' ],

			'edit_others_contests'   => [ 'administrator', 'editor' ],
			'delete_others_contests' => [ 'administrator', 'editor' ],

			'edit_published_contests'   => [ 'administrator', 'editor', 'author' ],
			'delete_published_contests' => [ 'administrator', 'editor', 'author' ],

			'read_private_contests'   => [ 'administrator', 'editor' ],
			'edit_private_contests'   => [ 'administrator', 'editor' ],
			'delete_private_contests' => [ 'administrator', 'editor' ],
			'create_contests'         => [ 'administrator', 'editor', 'author' ],
		];

		foreach ( $map as $capability => $roles ):
			foreach ( $roles as $role ):
				$role = get_role( $role );
				if ( $role ):
					$role->add_cap( $capability );
				endif;
			endforeach;
		endforeach;
	}

	/**
	 * @return string
	 */
	public function getName() {
		/**
		 * Filters the name of contest CPT.
		 *
		 * @param string $name CPT name.
		 *
		 * @return string
		 * @since 2.0.0
		 */
		return apply_filters( 'totalcontest/filters/contest/cpt/name', 'contest' );
	}

	/**
	 * @return array|string
	 */
	public function getArguments() {
		/**
		 * Filters the arguments of contest CPT.
		 *
		 * @param array $args CPT arguments.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		return apply_filters( 'totalcontest/filters/contest/cpt/args', [
				'labels'              => [
					'name'               => esc_html__( 'Contests', 'totalcontest' ),
					'singular_name'      => esc_html__( 'Contest', 'totalcontest' ),
					'add_new'            => esc_html__( 'Create Contest', 'totalcontest' ),
					'add_new_item'       => esc_html__( 'New Contest', 'totalcontest' ),
					'edit_item'          => esc_html__( 'Edit Contest', 'totalcontest' ),
					'new_item'           => esc_html__( 'New Contest', 'totalcontest' ),
					'all_items'          => esc_html__( 'Contests', 'totalcontest' ),
					'view_item'          => esc_html__( 'View Contest', 'totalcontest' ),
					'search_items'       => esc_html__( 'Search Contests', 'totalcontest' ),
					'not_found'          => esc_html__( 'No contests found', 'totalcontest' ),
					'not_found_in_trash' => esc_html__( 'No contests found in Trash', 'totalcontest' ),
					'menu_name'          => esc_html__( 'TotalContest', 'totalcontest' ),
				],
				'public'              => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'query_var'           => false,
				'rewrite'             => [
					'slug'  => _x( 'contest', 'slug', 'totalcontest' ),
					'feeds' => false,
					'pages' => false,
				],
				'capabilities'        => [
					'edit_post'              => 'edit_contest',
					'read_post'              => 'read_contest',
					'delete_post'            => 'delete_contest',
					'edit_posts'             => 'edit_contests',
					'edit_others_posts'      => 'edit_others_contests',
					'publish_posts'          => 'publish_contests',
					'read_private_posts'     => 'read_private_contests',
					'create_posts'           => 'create_contests',
					'edit_published_posts'   => 'edit_published_contests',
					'delete_published_posts' => 'delete_published_contests',
				],
				'map_meta_cap'        => true,
				'has_archive'         => false,
				'menu_position'       => null,
				'hierarchical'        => false,
				'menu_icon'           => 'dashicons-megaphone',
				'supports'            => [ 'title', 'revisions', 'excerpt' ],
				'show_in_rest'        => false,
			]
		);
	}

	/**
	 * Setup contest post type messages.
	 *
	 * @param \WP_Post $post
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function getMessages( $post ) {
		return [
			0  => '', // Unused. Messages start at index 1.
			1  => sprintf( wp_kses(__( 'Contest updated. <a href="%s">View contest</a>', 'totalcontest' ), ['a' => ['href' => [], 'target' => []], 'strong' => []]), esc_url( get_permalink( $post->ID ) ) ),
			2  => esc_html__( 'Custom field updated.', 'totalcontest' ),
			3  => esc_html__( 'Custom field deleted.', 'totalcontest' ),
			4  => esc_html__( 'Contest updated.', 'totalcontest' ),
			5  => isset( $_GET['revision'] ) ? sprintf( esc_html__( 'Contest restored to revision from %s', 'totalcontest' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => sprintf( wp_kses(__( 'Contest published. <a href="%s">View contest</a>', 'totalcontest' ), ['a' => ['href' => [], 'target' => []], 'strong' => []]), esc_url( get_permalink( $post->ID ) ) ),
			7  => esc_html__( 'Contest saved.', 'totalcontest' ),
			8  => sprintf( wp_kses(__( 'Contest submitted. <a target="_blank" href="%s">Preview contest</a>', 'totalcontest' ), ['a' => ['href' => [], 'target' => []], 'strong' => []]),
				esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) ),
			9  => sprintf( wp_kses(__( 'Contest scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview contest</a>', 'totalcontest' ), ['a' => ['href' => [], 'target' => []], 'strong' => []]),
				date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post->ID ) ) ),
			10 => sprintf( wp_kses(__( 'Contest draft updated. <a target="_blank" href="%s">Preview contest</a>', 'totalcontest' ), ['a' => ['href' => [], 'target' => []], 'strong' => []]),
				esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) ),
		];
	}

	/**
	 * Register post type.
	 *
	 * @return \WP_Error|\WP_Post_Type WP_Post_Type on success, WP_Error otherwise.
	 * @since 1.0.0
	 */
	public function register() {
		define( 'TC_CONTEST_CPT_NAME', $this->getName() );

		return parent::register();
	}

}
