<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.kybernetik-services.com/
 * @since      1.0.0
 *
 * @package    WP_Sitemaps_Config
 * @subpackage WP_Sitemaps_Config/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    WP_Sitemaps_Config
 * @subpackage WP_Sitemaps_Config/public
 * @author     Kybernetik Services <wordpress@kybernetik.com.de>
 */
if ( ! class_exists( 'WP_Sitemaps_Config_Public' ) ) {
class WP_Sitemaps_Config_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The slug of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_slug    The slug of this plugin.
	 */
	private $plugin_slug;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_version    The current version of this plugin.
	 */
	private $plugin_version;

	/**
	 * Stored settings in an array
	 *
	 *
	 * @since    1.0
	 *
	 * @var      array
	 */
	private $stored_settings;

	/**
	 * iDs of excluded posts in an array
	 *
	 *
	 * @since    2.0.0
	 *
	 * @var      array
	 */
	private $excluded_posts;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      array     $args    Parameters of this plugin
	 */
	public function __construct( $args ) {

		$this->plugin_name		= $args['name'];
		$this->plugin_slug		= $args['slug'];
		$this->plugin_version	= $args['plugin_version'];

		// get settings
		$this->stored_settings = $this->get_stored_settings();
		// get IDs of excluded posts; empty array if no results
		$this->excluded_posts = $this->get_excluded_post_ids();
    }

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_Sitemaps_Config_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Sitemaps_Config_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-sitemaps-config-public.css', array(), $this->plugin_version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_Sitemaps_Config_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Sitemaps_Config_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-sitemaps-config-public.js', array( 'jquery' ), $this->plugin_version, false );

	}
	
	/**
	 * Get current or default settings
	 *
	 * @since    1.0.0
	 */
	public function get_stored_settings() {
		// try to load current settings. If they are not in the DB return default settings
		$settings = get_option( WP_SITEMAPS_CONFIG_OPTION_NAME );

        // if proper settings, then return them
		if ( is_array( $settings ) and ! empty( $settings ) ) {
			return $settings;
		}
		
		// else return empty array
		return array();
	}
	
	/**
	 * 	Add or remove sitemaps at all
	 *
	 * @since    1.0.0
	 * @return  bool              Whether the sitemaps availability is allowed
	 */
	public function is_sitemaps_enabled () {
		if ( isset( $this->stored_settings[ 'remove_all_sitemaps' ] ) &&  '1' === $this->stored_settings[ 'remove_all_sitemaps' ] ) {
			return false;
		}
		return true;
	}

	/**
	 * Add or remove sitemap providers
	 *
	 * @since    1.0.0
	 * @param   object   $provider Instance of a WP_Sitemaps_Provider
	 * @param   string   $name     Name of the sitemap provider.
	 * @return  bool|object        Provider instance or false
	 */
	public function change_sitemaps_provider ( $provider, $name ) {
		if ( isset( $this->stored_settings[ 'remove_provider_' . $name ] ) && '1' === $this->stored_settings[ 'remove_provider_' . $name ] ) {
			return false;
		}
		return $provider;
	}
	
	/**
	 * Add or remove sitemaps for certain post types
	 *
	 * @since    1.0.0
	 * @param   WP_Post_Type[] $post_types Array of registered post type objects keyed by their name.
	 * @return  WP_Post_Type[]             Edited list of post types
	 */
	public function change_sitemaps_post_types ( $post_types ) {

       foreach ( $post_types as $name => $data ) {
			if ( isset( $this->stored_settings[ 'remove_sitemap_posts_' . $name ] ) && '1' === $this->stored_settings[ 'remove_sitemap_posts_' . $name ] ) {
				unset( $post_types[ $name ] );
			}
		}

        return $post_types;
	}

	/**
	 * Add or remove sitemaps for certain taxonomies
	 *
	 * @since    1.0.0
	 * @param   WP_Taxonomy[] $taxonomies Array of registered taxonomy objects keyed by their name.
	 * @return  WP_Taxonomy[]             Edited list of taxonomies
	 */
	public function change_sitemaps_taxonomies ( $taxonomies ) {
		foreach ( $taxonomies as $name => $data ) {
			if ( isset( $this->stored_settings[ 'remove_sitemap_taxonomies_' . $name ] ) && '1' === $this->stored_settings[ 'remove_sitemap_taxonomies_' . $name ] ) {
				unset( $taxonomies[ $name ] );
			}
		}
		return $taxonomies;
	}

    /**
     * Add or remove tags to sitemap providers
     *
     * @param $entry
     * @param WP_Post $post Post object.
     *
     * @return array                 Edited sitemap entry for the post.
     * @since    1.0.0
     */
	public function change_sitemaps_posts_entry ( $entry, $post ) {

		// setting of the entry's visibility in the sitemap
		if ( isset( $this->stored_settings[ 'add_lastmod' ] ) && '1' === $this->stored_settings[ 'add_lastmod' ] ) {
			// date & time of the last modification of the post
			$entry['lastmod'] = date( DATE_ATOM, strtotime( $post->post_modified_gmt ) );
		}

        // setting of the change frequency of the post
		if ( isset( $this->stored_settings[ 'add_changefreq' ] ) && '1' === $this->stored_settings[ 'add_changefreq' ] ) {
			// set default
			$entry['changefreq'] = 'weekly';
			// set stored value if avaiable and valid
			$post_meta = get_post_meta( $post->ID, WP_SITEMAPS_CONFIG_META_KEY );
			if ( isset( $post_meta[0]['changefreq'] ) && in_array( $post_meta[0]['changefreq'], array( 'always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never' ) ) ) {
				$entry['changefreq'] = $post_meta[0]['changefreq'];
			}
		}

		// setting of the priority of the post
		if ( isset( $this->stored_settings[ 'add_priority' ] ) && '1' === $this->stored_settings[ 'add_priority' ] ) {
			// set default
			$entry['priority'] = 0.5;
			// set stored value if available and valid
			$post_meta = get_post_meta( $post->ID, WP_SITEMAPS_CONFIG_META_KEY );
			if ( isset( $post_meta[0]['priority'] ) ) {
				$prio = (float) $post_meta[0]['priority'];
				if ( 0 <= $prio && 1 >= $prio ) {
					$entry['priority'] = $prio;
				}
			}
		}

        // return
		return $entry;
	}
	
	/** === All functions for the tab 'Posts' === */

	/**
	 * Remove excluded posts and pages from the sitemap
	 *
     * @param  array  $args
     * @param  string $post_type The post type of the current sitemap
	 * @since  2.0.0
     * @updated 2.0.3
     * @updated 2.0.4
	 * @return array             Edited array arguments
	 */
	public function exclude_single_posts ( $args, $post_type ) {

        $allowed_post_types = array( 'post', 'page', 'product' );

        // bail if it is the wrong post type
        if ( !in_array( $args[ 'post_type' ], $allowed_post_types ) || empty( $this->excluded_posts ) ) {
            return $args;
        }

        foreach ( $this->excluded_posts as $exclude_id ) {

            $args['post__not_in'] = isset( $args['post__not_in'] ) ? $args['post__not_in'] : array();
            $args['post__not_in'][] = $exclude_id; // $exclude_id is the ID of the post to exclude.

        }

        return $args;

	}
	
	/**
	 * Retrieve the IDs of excluded posts
	 *
	 * @since    2.0.0
	 * @return   array            Array of post IDs or empty array
	 */
	private function get_excluded_post_ids () {
		global $wpdb;
		$ids = array();
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT `post_id`, `meta_value` FROM $wpdb->postmeta WHERE `meta_key` = '%s'", WP_SITEMAPS_CONFIG_META_KEY ) );
		if ( $results ) {
			foreach ( $results as $result  ) {
				$meta_value = maybe_unserialize( $result->meta_value );
				if ( isset( $meta_value[ 'excluded' ] ) && '1' === $meta_value[ 'excluded' ] ) {
					$ids[] = absint( $result->post_id );
				}
			}
		}
		return $ids;
	}

}
}
