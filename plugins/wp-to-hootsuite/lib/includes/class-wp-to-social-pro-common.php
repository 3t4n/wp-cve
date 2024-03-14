<?php
/**
 * Common class.
 *
 * @package WP_To_Social_Pro
 * @author WP Zinc
 */

/**
 * Common functions that don't fit into other classes.
 *
 * @package WP_To_Social_Pro
 * @author  WP Zinc
 * @version 3.0.0
 */
class WP_To_Social_Pro_Common {

	/**
	 * Holds the base class object.
	 *
	 * @since   3.4.7
	 *
	 * @var     object
	 */
	public $base;

	/**
	 * Constructor
	 *
	 * @since   3.4.7
	 *
	 * @param   object $base    Base Plugin Class.
	 */
	public function __construct( $base ) {

		// Store base class.
		$this->base = $base;

	}

	/**
	 * Helper method to retrieve schedule options
	 *
	 * @since   3.0.0
	 *
	 * @param   mixed $post_type          Post Type (false | string).
	 * @param   bool  $is_post_screen     Displaying the Post Screen.
	 * @return  array                       Schedule Options
	 */
	public function get_schedule_options( $post_type = false, $is_post_screen = false ) {

		// Build schedule options, depending on the Plugin.
		switch ( $this->base->plugin->name ) {

			case 'wp-to-buffer':
				$schedule = array(
					'queue_bottom' => sprintf(
						/* translators: Social Media Service Name (Buffer, Hootsuite, SocialPilot) */
						__( 'Add to End of %s Queue', 'wp-to-hootsuite' ),
						$this->base->plugin->account
					),
				);
				break;

			case 'wp-to-hootsuite':
				$schedule = array(
					'now' => __( 'Post Immediately', 'wp-to-hootsuite' ),
				);
				break;

		}

		/**
		 * Defines the available schedule options for each individual status.
		 *
		 * @since   3.0.0
		 *
		 * @param   array   $schedule           Schedule Options.
		 * @param   string  $post_type          Post Type.
		 * @param   bool    $is_post_screen     On Post Edit Screen.
		 */
		$schedule = apply_filters( $this->base->plugin->filter_name . '_get_schedule_options', $schedule, $post_type, $is_post_screen );

		// Return filtered results.
		return $schedule;

	}

	/**
	 * Helper method to retrieve Google Business Start Date options
	 *
	 * @since   4.9.0
	 *
	 * @param   mixed $post_type          Post Type (false | string).
	 * @return  array   Start Date Options
	 */
	public function get_google_business_start_date_options( $post_type = false ) {

		// Build schedule options.
		$schedule = array(
			'custom' => __( 'Custom Field / Post Meta Value', 'wp-to-hootsuite' ),
		);

		/**
		 * Defines the available start date options for a Google Business Profile status.
		 *
		 * @since   4.9.0
		 *
		 * @param   array   $schedule   Schedule Options.
		 */
		$schedule = apply_filters( $this->base->plugin->filter_name . '_get_google_business_start_date_options', $schedule, $post_type );

		// Return filtered results.
		return $schedule;

	}

	/**
	 * Helper method to retrieve Google Business Start Date options
	 *
	 * @since   4.9.0
	 *
	 * @param   mixed $post_type          Post Type (false | string).
	 * @return  array   End Date Options
	 */
	public function get_google_business_end_date_options( $post_type = false ) {

		// Build schedule options.
		$schedule = array(
			'custom' => __( 'Custom Field / Post Meta Value', 'wp-to-hootsuite' ),
		);

		/**
		 * Defines the available start date options for a Google Business Profile status.
		 *
		 * @since   4.9.0
		 *
		 * @param   array   $schedule   Schedule Options.
		 */
		$schedule = apply_filters( $this->base->plugin->filter_name . '_get_google_business_end_date_options', $schedule, $post_type );

		// Return filtered results.
		return $schedule;

	}

	/**
	 * Helper method to retrieve public Post Types
	 *
	 * @since   3.0.0
	 *
	 * @return  array   Public Post Types
	 */
	public function get_post_types() {

		// Get public Post Types.
		$types = get_post_types(
			array(
				'public' => true,
			),
			'objects'
		);

		// Filter out excluded post types.
		$excluded_types = $this->get_excluded_post_types();
		if ( is_array( $excluded_types ) ) {
			foreach ( $excluded_types as $excluded_type ) {
				unset( $types[ $excluded_type ] );
			}
		}

		/**
		 * Defines the available Post Type Objects that can have statues defined and be sent to social media.
		 *
		 * @since   3.0.0
		 *
		 * @param   array   $types  Post Types.
		 */
		$types = apply_filters( $this->base->plugin->filter_name . '_get_post_types', $types );

		// Return filtered results.
		return $types;

	}

	/**
	 * Helper method to retrieve excluded Post Types, which should not send
	 * statuses to the API
	 *
	 * @since   3.0.0
	 *
	 * @return  array   Excluded Post Types
	 */
	public function get_excluded_post_types() {

		// Get excluded Post Types.
		$types = array(
			'attachment',
			'revision',
			'elementor_library',
		);

		/**
		 * Defines the Post Type Objects that cannot have statues defined and not be sent to social media.
		 *
		 * @since   3.0.0
		 *
		 * @param   array   $types  Post Types.
		 */
		$types = apply_filters( $this->base->plugin->filter_name . '_get_excluded_post_types', $types );

		// Return filtered results.
		return $types;

	}

	/**
	 * Helper method to retrieve excluded Taxonomies
	 *
	 * @since   3.0.5
	 *
	 * @return  array   Excluded Post Types
	 */
	public function get_excluded_taxonomies() {

		// Get excluded Post Types.
		$taxonomies = array(
			'post_format',
			'nav_menu',
		);

		/**
		 * Defines taxonomies to exclude from the Conditions: Taxonomies dropdowns for each individual status.
		 *
		 * @since   3.0.5
		 *
		 * @param   array   $taxonomies     Excluded Taxonomies.
		 */
		$taxonomies = apply_filters( $this->base->plugin->filter_name . '_get_excluded_taxonomies', $taxonomies );

		// Return filtered results.
		return $taxonomies;

	}

	/**
	 * Helper method to retrieve a Post Type's taxonomies
	 *
	 * @since   3.0.0
	 *
	 * @param   string $post_type  Post Type.
	 * @return  array               Taxonomies
	 */
	public function get_taxonomies( $post_type ) {

		// Get Post Type Taxonomies.
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );

		// Get excluded Taxonomies.
		$excluded_taxonomies = $this->get_excluded_taxonomies();

		// If excluded taxonomies exist, remove them from the taxonomies array now.
		if ( is_array( $excluded_taxonomies ) && count( $excluded_taxonomies ) > 0 ) {
			foreach ( $excluded_taxonomies as $excluded_taxonomy ) {
				unset( $taxonomies[ $excluded_taxonomy ] );
			}
		}

		/**
		 * Defines available taxonomies for the given Post Type, which are used in the Conditions: Taxonomies dropdowns
		 * for each individual status.
		 *
		 * @since   3.0.0
		 *
		 * @param   array   $taxonomies             Taxonomies.
		 * @param   string  $post_type              Post Type.
		 */
		$taxonomies = apply_filters( $this->base->plugin->filter_name . '_get_taxonomies', $taxonomies, $post_type );

		// Return filtered results.
		return $taxonomies;

	}

	/**
	 * Helper method to retrieve all taxonomies
	 *
	 * @since   3.6.7
	 *
	 * @return  array               Taxonomies
	 */
	public function get_all_taxonomies() {

		// Get Post Type Taxonomies.
		$taxonomies = get_taxonomies( false, 'objects' );

		// Get excluded Taxonomies.
		$excluded_taxonomies = $this->get_excluded_taxonomies();

		// If excluded taxonomies exist, remove them from the taxonomies array now.
		if ( is_array( $excluded_taxonomies ) && count( $excluded_taxonomies ) > 0 ) {
			foreach ( $excluded_taxonomies as $excluded_taxonomy ) {
				unset( $taxonomies[ $excluded_taxonomy ] );
			}
		}

		/**
		 * Defines available taxonomies, regardless of Post Type, which are used in the Conditions: Taxonomies dropdowns
		 * for each individual status.
		 *
		 * @since   3.6.7
		 *
		 * @param   array   $taxonomies             Taxonomies.
		 */
		$taxonomies = apply_filters( $this->base->plugin->filter_name . '_get_all_taxonomies', $taxonomies );

		// Return filtered results.
		return $taxonomies;

	}

	/**
	 * Helper method to retrieve available tags for status updates
	 *
	 * @since   3.0.0
	 *
	 * @param   string $post_type  Post Type.
	 * @return  array               Tags
	 */
	public function get_tags( $post_type ) {

		// Get post type.
		$post_types = $this->get_post_types();

		// Build tags array.
		$tags = array(
			'post' => array(
				'{sitename}'              => __( 'Site Name', 'wp-to-hootsuite' ),
				'{title}'                 => __( 'Post Title', 'wp-to-hootsuite' ),
				'{excerpt}'               => __( 'Post Excerpt (Full)', 'wp-to-hootsuite' ),
				'{excerpt:characters(?)}' => array(
					'question'      => __( 'Enter the maximum number of characters the Post Excerpt should display.', 'wp-to-hootsuite' ),
					'default_value' => '150',
					'replace'       => '?',
					'label'         => __( 'Post Excerpt (Character Limited)', 'wp-to-hootsuite' ),
				),
				'{excerpt:words(?)}'      => array(
					'question'      => __( 'Enter the maximum number of words the Post Excerpt should display.', 'wp-to-hootsuite' ),
					'default_value' => '55',
					'replace'       => '?',
					'label'         => __( 'Post Excerpt (Word Limited)', 'wp-to-hootsuite' ),
				),
				'{excerpt:sentences(?)}'  => array(
					'question'      => __( 'Enter the maximum number of sentences the Post Excerpt should display.', 'wp-to-hootsuite' ),
					'default_value' => '1',
					'replace'       => '?',
					'label'         => __( 'Post Excerpt (Sentence Limited)', 'wp-to-hootsuite' ),
				),
				'{content}'               => __( 'Post Content (Full)', 'wp-to-hootsuite' ),
				'{content_more_tag}'      => __( 'Post Content (Up to More Tag)', 'wp-to-hootsuite' ),
				'{content:characters(?)}' => array(
					'question'      => __( 'Enter the maximum number of characters the Post Content should display.', 'wp-to-hootsuite' ),
					'default_value' => '150',
					'replace'       => '?',
					'label'         => __( 'Post Content (Character Limited)', 'wp-to-hootsuite' ),
				),
				'{content:words(?)}'      => array(
					'question'      => __( 'Enter the maximum number of words the Post Content should display.', 'wp-to-hootsuite' ),
					'default_value' => '55',
					'replace'       => '?',
					'label'         => __( 'Post Content (Word Limited)', 'wp-to-hootsuite' ),
				),
				'{content:sentences(?)}'  => array(
					'question'      => __( 'Enter the maximum number of sentences the Post Content should display.', 'wp-to-hootsuite' ),
					'default_value' => '1',
					'replace'       => '?',
					'label'         => __( 'Post Content (Sentence Limited)', 'wp-to-hootsuite' ),
				),
				'{date}'                  => __( 'Post Date', 'wp-to-hootsuite' ),
				'{url}'                   => __( 'Post URL', 'wp-to-hootsuite' ),
				'{url_short}'             => __( 'Post URL, Shortened', 'wp-to-hootsuite' ),
				'{id}'                    => __( 'Post ID', 'wp-to-hootsuite' ),
			),
		);

		// Add any taxonomies for the given Post Type, if the Post Type exists.
		$taxonomies = array();
		if ( isset( $post_types[ $post_type ] ) ) {
			// Get taxonomies specific to the Post Type.
			$taxonomies = $this->get_taxonomies( $post_type );
		} else {
			// We're on the Bulk Publishing Settings, so return all Taxonomies.
			$taxonomies = $this->get_all_taxonomies();
		}

		if ( count( $taxonomies ) > 0 ) {
			$tags['taxonomy'] = array();

			foreach ( $taxonomies as $tax => $details ) {
				$tags['taxonomy'][ '{taxonomy_' . $tax . '}' ] = sprintf(
					/* translators: Taxonomy Name, Singular */
					__( 'Taxonomy: %s: Hashtag Format', 'wp-to-hootsuite' ),
					$details->labels->singular_name
				);
			}
		}

		/**
		 * Defines Dynamic Status Tags that can be inserted into status(es) for the given Post Type.
		 * These tags are also added to any 'Insert Tag' dropdowns.
		 *
		 * @since   3.0.0
		 *
		 * @param   array   $tags       Dynamic Status Tags.
		 * @param   string  $post_type  Post Type.
		 */
		$tags = apply_filters( $this->base->plugin->filter_name . '_get_tags', $tags, $post_type );

		// Return filtered results.
		return $tags;

	}

	/**
	 * Helper method to retrieve available tags for status updates, in a flattened
	 * key/value array
	 *
	 * @since   4.5.7
	 *
	 * @param   string $post_type  Post Type.
	 * @return  array               Tags
	 */
	public function get_tags_flat( $post_type ) {

		$tags_flat = array();
		foreach ( $this->get_tags( $post_type ) as $tag_group => $tag_group_tags ) {
			foreach ( $tag_group_tags as $tag => $tag_attributes ) {
				$tags_flat[] = array(
					'key'   => $tag,
					'value' => $tag,
				);
			}
		}

		return $tags_flat;

	}

	/**
	 * Helper method to retrieve Post actions
	 *
	 * @since   3.0.0
	 *
	 * @return  array           Post Actions
	 */
	public function get_post_actions() {

		// Build post actions.
		$actions = array(
			'publish' => __( 'Publish', 'wp-to-hootsuite' ),
			'update'  => __( 'Update', 'wp-to-hootsuite' ),
		);

		/**
		 * Defines the Post actions which trigger status(es) to be sent to social media.
		 *
		 * @since   3.0.0
		 *
		 * @param   array   $actions    Post Actions.
		 */
		$actions = apply_filters( $this->base->plugin->filter_name . '_get_post_actions', $actions );

		// Return filtered results.
		return $actions;

	}

	/**
	 * Helper method to retrieve Post actions, with labels in the past tense.
	 *
	 * @since   3.7.2
	 *
	 * @return  array           Post Actions
	 */
	public function get_post_actions_past_tense() {

		// Build post actions.
		$actions = array(
			'publish' => __( 'Published', 'wp-to-hootsuite' ),
			'update'  => __( 'Updated', 'wp-to-hootsuite' ),
		);

		/**
		 * Defines the Post actions which trigger status(es) to be sent to social media,
		 * with labels set to the past tense.
		 *
		 * @since   3.0.0
		 *
		 * @param   array   $actions    Post Actions.
		 */
		$actions = apply_filters( $this->base->plugin->filter_name . '_get_post_actions_past_tense', $actions );

		// Return filtered results.
		return $actions;

	}

	/**
	 * Helper method to return template tags that cannot have a character limit applied to them.
	 *
	 * @since   3.7.8
	 *
	 * @return  array   Tags.
	 */
	public function get_tags_excluded_from_character_limit() {

		$tags = array(
			'date',
			'url',
			'id',
			'author_user_email',
			'author_user_url',
		);

		/**
		 * Defines the tags that cannot have a character limit applied to them, as doing so would
		 * wrongly concatenate data (e.g. a URL would become malformed).
		 *
		 * @since   3.7.8
		 *
		 * @param   array   $tags   Tags.
		 */
		$tags = apply_filters( $this->base->plugin->filter_name . '_get_tags_excluded_from_character_limit', $tags );

		// Return filtered results.
		return $tags;

	}

	/**
	 * Helper method to retrieve transient expiration time
	 *
	 * @since   3.0.0
	 *
	 * @return  int     Expiration Time (seconds)
	 */
	public function get_transient_expiration_time() {

		// Set expiration time for all transients = 12 hours.
		$expiration_time = ( 12 * HOUR_IN_SECONDS );

		/**
		 * Defines the number of seconds before expiring transients.
		 *
		 * @since   3.0.0
		 *
		 * @param   int     $expiration_time    Transient Expiration Time, in seconds.
		 */
		$expiration_time = apply_filters( $this->base->plugin->filter_name . '_get_transient_expiration_time', $expiration_time );

		// Return filtered results.
		return $expiration_time;

	}

	/**
	 * Defines the registered filters that can be used on the Log WP_List_Table
	 *
	 * @since   3.9.8
	 *
	 * @return  array   Filters
	 */
	public function get_log_filters() {

		// Define filters.
		$filters = array(
			'action',
			'profile_id',
			'result',
			'request_sent_start_date',
			'request_sent_end_date',
			'orderby',
			'order',
		);

		/**
		 * Defines the registered filters that can be used on the Log WP_List_Tables.
		 *
		 * @since   3.9.8
		 *
		 * @param   array   $filters    Filters.
		 */
		$filters = apply_filters( $this->base->plugin->filter_name . '_get_log_filters', $filters );

		// Return filtered results.
		return $filters;

	}

}
