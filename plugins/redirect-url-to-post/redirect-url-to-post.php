<?php
/*
Plugin Name: Redirect URL to Post
Plugin URI: https://chattymango.com/redirect-url-to-post/
Description: Redirects to a post based on parameters in the URL
Author: Christoph Amthor
Author URI: https://chattymango.com/
Version: 0.23.0
License: GNU GENERAL PUBLIC LICENSE, Version 3
Text Domain: redirect-url-to-post
 */

// Don't call this file directly

if ( ! defined( 'ABSPATH' ) ) {
  die;
}

class RedirectURLToPost {

  /**
   * Target for the redirection
   *
   * @var string
   */
  private $redirect_to;

  /**
   * Post query arguments that filter posts
   *
   * @var array
   */
  private $args_filter;

  /**
   * Post query arguments that sort posts
   *
   * @var array
   */
  private $args_sorting;

  /**
   * Allowed post query arguments
   *
   * @var array
   */
  private $query_args_whitelist;

  /**
   * Arguments that need to converted from a comma-separated list to an array
   *
   * @var array
   */
  private $explode_to_array;

  /**
   * Defaults of pass-through GET query parameters
   *
   * @var array
   */
  private $query_args_pass_through_defaults;

  /**
   * Actual pass-through GET query parameters
   *
   * @var array
   */
  private $query_args_pass_through;

  /**
   * Whitelisted orderby parameters
   *
   * @var array
   */
  private $orderby_whitelist;

  /**
   * Whitelisted order parameters
   *
   * @var array
   */
  private $order_whitelist;

  /**
   * Number of posts to pick a random from, or number of posts receiving a bias treatment
   *
   * @var integer
   */
  private $count;

  /**
   * Percentage that the posts inside $count should be preferred over the rest when using random (1-99)
   *
   * @var integer
   */
  private $random_bias;

  /**
   * Time in seconds that the post lock should remain locked on one post
   *
   * @var integer
   */
  private $post_lock;

  /**
   * Time in seconds that a post (or a pool of posts for random) should be kept in the transient cache
   *
   * @var integer
   */
  private $caching_seconds;

  /**
   * Whether we write more details to the debug.log
   *
   * @var boolean
   */
  private $verbose_debug;

  /**
   * The final post ID where we try to redirect to
   *
   * @var integer
   */
  private $post_id;

  /**
   * Pool of post IDs that serve as base to pick a post
   *
   * @var array
   */
  private $post_ids;

  /**
   * ID of the post found in the lock
   *
   * @var integer
   */
  private $locked_post_id;

  /**
   * Whether we will pick a random post
   *
   * @var boolean
   */
  private $php_rand;

  /**
   * Offset from the beginning of the list of posts
   *
   * @var integer
   */
  private $offset;

  /**
   * Turn on the behavior that we pick each random post only once in a series and tells what to do when we are done: rewind or go to a post
   *
   * @var integer|string
   */
  private $each_once;

  /**
   * Key that is unique for each set of relevant parameters to save the cache
   *
   * @var string
   */
  private $cache_key;

  /**
   * URL where we will redirect to
   *
   * @var string
   */
  private $redirection_target;

  /**
   * Directory determining the scope of cookies
   *
   * @var string
   */
  private $cookie_path;

  /**
   * Whether we have to request post objects from WP_Query
   *
   * @var boolean
   */
  private $request_post_objects;

  /**
   * Arguments for WP_Query
   *
   * @var array
   */
  private $query_args;

  /**
   * List of post IDs that we have seen in a random series
   *
   * @var array
   */
  private $done_post_ids;

  /**
   * Whether we have already sent HTTP headers; determines how we can redirect
   *
   * @var boolean
   */
  private $headers_sent;

  /**
   * If we use debugging instead of redirecting and how detailled
   *
   * @var integer
   */
  private $debug_mode;

  /**
   * Queue of debug messages
   *
   * @var array
   */
  private $debug_messages;

  /**
   *   Initial setup: Register the filters and actions
   *
   */
  public function __construct() {

    add_action( 'admin_notices', array( $this, 'admin_notice' ) );

    add_action( 'send_headers', array( $this, 'redirect_post_send_headers' ) );

    add_action( 'wp', array( $this, 'redirect_using_post_id' ) );

    // redirect_to_random_button deprecated since versions after 0.5.2, only kept for backward compatibility
    add_shortcode( 'redirect_to_random_button', array( $this, 'redirect_to_post_button' ) );

    add_shortcode( 'redirect_to_post_button', array( $this, 'redirect_to_post_button' ) );

    add_filter( 'widget_text', 'do_shortcode' );

    add_action( 'transition_post_status', array( $this, 'clear_cache'));

    if ( is_admin() ) {

      add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'add_help_link' ), 10 );

    }

    if ( isset( $_GET['rutpdebug'] ) ) {
      // also rutpdebug without a value turns on debugging

      $this->debug_mode = 1;

      $this->debug_messages = array();

      if ( (int) $_GET['rutpdebug'] > 1 ) {

        $this->debug_mode = (int) $_GET['rutpdebug'];

      }

      $this->add_debug_message( "<b>" .
        __( 'Debug mode is on. We will only display the resulting URL, without redirecting.', 'redirect-url-to-post' ) .
        "</b><br>" );

    } else {

      $this->debug_mode = 0;

    }

    $this->request_post_objects = false;

    $this->headers_sent = false;

  }

  /**
   * Main callback for all parts that don't need a post ID
   *
   * @param  void
   * @return void
   */
  public function redirect_post_send_headers() {

    global $redirect_post_query_run;

    // Use sanitized $_GET to be independent from current state of WP Query and possible unavailability of GET parameters.
    if ( ! empty( $_GET['redirect_to'] ) ) {

      // Can use sanitize_key because only small letters and underscores needed
      $this->redirect_to = sanitize_key( $_GET['redirect_to'] );

      // For some parameters we will need to be inside the loop
      if ( $this->requires_post_id() ) {

        return;

      }

      // Prevent being run again when executing the query
      if ( $redirect_post_query_run > 0 ) {

        return;

      }

      $this->php_rand = false;

      $redirect_post_query_run++;

      $this->load_defaults();

      $this->retrieve_get_parameters();

      $this->maybe_add_debug_message( sprintf( __( 'We use redirect_to=%s.', 'redirect-url-to-post' ), $this->redirect_to ) );

      // Set up the search query depending on the criteria
      switch ( $this->redirect_to ) {

      // Show the latest post
      case 'latest':
      case 'last':

        $this->args_sorting = array(
          'orderby' => 'date',
          'order'   => 'DESC',
        );

        break;

      // Show the oldest post
      case 'oldest':
      case 'first':

        $this->args_sorting = array(
          'orderby' => 'date',
          'order'   => 'ASC',
        );

        break;

      // Show a random post
      case 'random':

        $this->php_rand = true;

        break;

      // find a post based on orderby (and order)
      case 'custom':

        if ( empty( $this->args_filter['orderby'] ) ) {

          _e( "Error: The parameter 'custom' requires also 'orderby'", "redirect-url-to-post" );

          exit;

        }

        if ( ! in_array( strtoupper( $this->args_filter['order'] ), $this->order_whitelist ) ) {

          _e( "Error: Unrecognized value of parameter 'order'", "redirect-url-to-post" );

          exit;

        }

        if ( ! in_array( $this->args_filter['orderby'], $this->orderby_whitelist ) ) {
          // WP_QUERY::parse_orderby() treats $orderby as case-sensitive

          _e( "Error: Unrecognized value of parameter 'orderby'", "redirect-url-to-post" );

          exit;

        }

        $this->args_sorting = array();

        break;

      default:

        /**
         * Unrecognized value of 'redirect_to' => finish processing and let other plugins do their work
         */
        return;

        break;
      }

      $this->determine_post();

      $this->determine_target();

      $this->redirect();

    }

  }

  /**
   * Main callback for all parts that need a post ID
   * Currently used for: redirect_to=prev, redirect_to=next
   *
   * @param  void
   * @return void
   */
  public function redirect_using_post_id() {

    global $redirect_post_query_run;

    if ( empty( $_GET['redirect_to'] ) ) {

      return;

    }

    // Use sanitized $_GET to be independent from current state of WP Query and possible unavailability of GET parameters.
    // Can use sanitize_key because only small letters and underscores needed
    $this->redirect_to = sanitize_key( $_GET['redirect_to'] );

    // Return if we are dealing with parameters that should have been processed earlier
    if ( ! $this->requires_post_id() ) {

      return;

    }

    // Prevent being run again when executing the query
    if ( $redirect_post_query_run > 0 ) {

      return;

    }

    $redirect_post_query_run++;

    $this->headers_sent = true; // It is possible that at this stage some plugin has already sent a header

    $this->load_defaults();

    $this->retrieve_get_parameters();

    $this->maybe_add_debug_message( sprintf( __( 'We use redirect_to=%s.', 'redirect-url-to-post' ), $this->redirect_to ) );

    $current_post_data = $this->get_current_post_data();

    if ( false === $current_post_data ) {

      return;

    }

    $date_query_items = array();

    // Set up the search query depending on the criteria
    switch ( $this->redirect_to ) {

    // Go to the previous post
    case 'previous':
    case 'prev':

      $this->args_sorting = array(
        'orderby' => 'date',
        'order'   => 'DESC',
      );

      $date_query_items['before'] = $current_post_data['post_date'];

      $this->args_filter['post_type'] = $current_post_data['post_type'];

      break;

    // Go to the next post
    case 'next':

      $this->args_sorting = array(
        'orderby' => 'date',
        'order'   => 'ASC',
      );

      $date_query_items['after'] = $current_post_data['post_date'];

      $this->args_filter['post_type'] = $current_post_data['post_type'];

      break;

    default:

      /**
       * Unrecognized value of 'redirect_to' => finish processing and let other plugins do their work
       */
      return;

      break;
    }

    /**
     * Construct the 'date_query' array for redirecting to previous or next
     */

    if ( ! empty( $date_query_items ) ) {

      $this->args_filter['date_query'] = array(
        $date_query_items,
      );

    }

    $this->determine_post();

    $this->determine_target();

    $this->redirect();

  }

  /**
   * Load all default values for the query
   *
   * @param  void
   * @return void
   */
  private function load_defaults() {

    $this->args_filter = array(
      'ignore_sticky_posts' => true,
      'order'               => 'DESC',
      'post_status'         => 'publish',
      'post_type'           => 'post',
      'suppress_filters'    => true,
    );

    /**
     *  WP Post query arguments and own shortcuts
     */
    $this->query_args_whitelist = array(
      'after', // translates to date_query construct
      'author',
      'author_name',
      'author__in',
      'author__not_in',
      'before', // translates to date_query construct
      'cat', // strangely 'category' not working
      'category_name',
      'category__and',
      'category__in',
      'category__not_in',
      // 'comment_count', // treated separatedly
      'custom_taxonomy_field',
      'custom_taxonomy_slug',
      'custom_taxonomy_term',
      'date_query_after', // alias for after
      'date_query_before', // alias for before
      'day',
      'exclude',
      'has_password',
      'hour',
      'ignore_sticky_posts',
      'include',
      'minute',
      'monthnum',
      'order',
      'orderby',
      'post_type', // requires has_archive for that post_type
      'post__in',
      'post__not_in',
      'post_name__in',
      'post_parent',
      'post_parent__in',
      'post_parent__not_in',
      's',
      'second',
      'suppress_filters',
      'tag',
      'tag_id',
      'tag__and',
      'tag__in',
      'tag__not_in',
      'tag_slug__and',
      'tag_slug__in',
      'w',
    );

    /**
     * Which of $this->query_args_whitelist require arrays
     */
    $this->explode_to_array = array(
      'author__in',
      'author__not_in',
      'category__and',
      'category__in',
      'category__not_in',
      'comment_count',
      'post__in',
      'post__not_in',
      'post_name__in',
      'post_parent__in',
      'post_parent__not_in',
      'tag__and',
      'tag__in',
      'tag__not_in',
      'tag_slug__and',
      'tag_slug__in',
    );

    /**
     * Get parameters that don't affect the retrieval of posts and that will be passed through to the final query
     */
    $this->query_args_pass_through_defaults = array(
      'utm_source', // Google Analytics
      'utm_campaign', // Google Analytics
      'utm_medium', // Google Analytics
      'utm_term', // Google Analytics
      'utm_content', // Google Analytics
      'pk_campaign', // Matomo Analytics
      'pk_kwd', // Matomo Analytics
      'pk_source', // Matomo Analytics
      'pk_medium', // Matomo Analytics
      'pk_content', // Matomo Analytics
    );

    if ( defined( 'CHATTY_MANGO_RUTP_PASS_THROUGH' ) && is_string( CHATTY_MANGO_RUTP_PASS_THROUGH ) ) {

      $this->maybe_add_debug_message( sprintf( 'We add pass-through parameters from CHATTY_MANGO_RUTP_PASS_THROUGH: %s', CHATTY_MANGO_RUTP_PASS_THROUGH ) );

      $this->query_args_pass_through_defaults = array_merge( $this->query_args_pass_through_defaults, explode( ',', CHATTY_MANGO_RUTP_PASS_THROUGH ) );

    }

    // Whitelisted orderby parameters
    $this->orderby_whitelist = array(
      'author',
      'comment_count',
      'date',
      'ID',
      'menu_order',
      'modified',
      'name',
      'none',
      'parent',
      'rand',
      'title',
      'type',
    );

    // Whitelisted order parameter
    $this->order_whitelist = array(
      'ASC',
      'DESC',
    );

  }

  /**
   * Read the GET parameters
   *
   *
   * @param  void
   * @return void
   */
  private function retrieve_get_parameters() {

    foreach ( $this->query_args_whitelist as $query_arg ) {

      if ( isset( $_GET[$query_arg] ) && '' !== $_GET[$query_arg] ) {

        if ( 'exclude' == $query_arg ) {

          $this->args_filter['post__not_in'] = array_map( 'intval', array_map( 'trim', explode( ',', $_GET['exclude'] ) ) );

        } elseif ( 'include' == $query_arg ) {

          $this->args_filter['post__in'] = array_map( 'intval', array_map( 'trim', explode( ',', $_GET['include'] ) ) );

        } elseif ( 'suppress_filters' == $query_arg ) {

          $this->args_filter['suppress_filters'] = $_GET['suppress_filters'] ? true : false;

        } elseif ( 'ignore_sticky_posts' == $query_arg ) {
          
          // getting rid of strings
          $this->args_filter['ignore_sticky_posts'] = intval( $_GET['ignore_sticky_posts'] ) ? true : false;

          if ( ! $this->args_filter['ignore_sticky_posts'] ) {

            $this->request_post_objects = true; // ignore_sticky_posts=0 works only if we retrieve post objects

          }

        } else {

          if ( in_array( $query_arg, $this->explode_to_array ) ) {

            $this->args_filter[$query_arg] = array_map( 'sanitize_text_field', explode( ',', $_GET[$query_arg] ) );

          } else {

            // Sanitized with sanitize_text_field because some values may be uppercase or spaces
            $this->args_filter[$query_arg] = sanitize_text_field( $_GET[$query_arg] );

          }

        }

      }

    }

    /**
     * post_type can be string or array
     */
    if ( ! empty( $this->args_filter['post_type'] ) && strpos( $this->args_filter['post_type'], ',' ) !== false ) {

      $this->args_filter['post_type'] = array_map( 'trim', explode( ',', $this->args_filter['post_type'] ) );

    }

    /**
     * Special processing for 'date_query_before' and 'date_query_after'
     */
    $date_query_items = array();

    if ( ! empty( $this->args_filter['date_query_before'] ) ) {

      $date_query_items['before'] = $this->args_filter['date_query_before'];

      unset( $this->args_filter['date_query_before'] );

    }

    if ( ! empty( $this->args_filter['before'] ) ) {

      $date_query_items['before'] = $this->args_filter['before'];

      unset( $this->args_filter['before'] );

    }

    if ( ! empty( $this->args_filter['date_query_after'] ) ) {

      $date_query_items['after'] = $this->args_filter['date_query_after'];

      unset( $this->args_filter['date_query_after'] );

    }

    if ( ! empty( $this->args_filter['after'] ) ) {

      $date_query_items['after'] = $this->args_filter['after'];

      unset( $this->args_filter['after'] );

    }

    /**
     * Construct the 'date_query' array
     */

    if ( ! empty( $date_query_items ) ) {

      $this->args_filter['date_query'] = array(
        $date_query_items,
      );

    }

    /**
     * Special processing for comment count
     */

    if ( isset( $_GET['comment_count'] ) ) {

      $compare = ( substr( $_GET['comment_count'], 0, 1 ) == '-' ) ? '!=' : '=';

      $this->args_filter['comment_count'] = array(
        'value'   => abs( intval( $_GET['comment_count'] ) ),
        'compare' => $compare,
      );

    } elseif ( isset( $_GET['comment_count_min'] ) ) {

      $this->args_filter['comment_count'] = array(
        'value'   => abs( intval( $_GET['comment_count_min'] ) ),
        'compare' => '>=',
      );

    } elseif ( isset( $_GET['comment_count_max'] ) ) {

      $this->args_filter['comment_count'] = array(
        'value'   => abs( intval( $_GET['comment_count_max'] ) ),
        'compare' => '<=',
      );

    }

    /**
     * Parameters that set properties
     */

    if ( isset( $_GET['each_once'] ) ) {

      $this->each_once = sanitize_title( $_GET['each_once'] );

      $this->maybe_add_debug_message( __( 'Picking each random post only once.', 'redirect-url-to-post' ) );

    } else {

      $this->each_once = false;

    }

    if ( ! empty( $_GET['count'] ) ) {

      $this->count = intval( $_GET['count'] );

    } else {

      $this->count = 0;

    }

    if ( ! empty( $_GET['bias'] ) ) {

      $this->random_bias = intval( $_GET['bias'] );

    } else {

      $this->random_bias = false;

    }

    if ( ! empty( $_GET['lock'] ) ) {

      $this->post_lock = intval( $_GET['lock'] );

    } else {

      $this->post_lock = false;

    }

    if ( ! empty( $_GET['offset'] ) ) {

      $this->offset = abs( intval( $_GET['offset'] ) );

    } else {

      $this->offset = 0;

    }

    /**
     * caching
     * We can override the settings with a constant so that visitors cannot maliciously overuse the database.
     */

    if ( defined( 'CHATTY_MANGO_RUTP_CACHE' ) ) {

      $this->caching_seconds = intval( CHATTY_MANGO_RUTP_CACHE );
      
      $this->maybe_add_debug_message( sprintf( 'We set the cache from CHATTY_MANGO_RUTP_CACHE to %d seconds', $this->caching_seconds ) );

    } else {

      if ( isset( $_GET['cache'] ) ) {

        $this->caching_seconds = intval( $_GET['cache'] );

      } else {

        $this->caching_seconds = 60;

      }

    }

    if ( isset( $_GET['verbose_debug'] ) ) {

      $this->verbose_debug = true;

    } else {

      $this->verbose_debug = false;

    }

    if ( isset( $_GET['directory_cookie'] ) ) {

      $this->cookie_path = null;

    } else {

      $this->cookie_path = '/';

    }

    /**
     * Read parameters that don't affect the selection of posts but will appear again in the resulting URL
     */

    foreach ( $this->query_args_pass_through_defaults as $query_arg ) {

      if ( isset( $_GET[$query_arg] ) ) {

        // Sanitized with sanitize_text_field because some values may be uppercase or spaces
        $this->query_args_pass_through[$query_arg] = sanitize_text_field( $_GET[$query_arg] );

      }

    }

  }

  /**
   * Find the post that matches the parameters
   *
   *
   * @param  void
   * @return integer
   */
  private function determine_post() {

    if ( isset( $this->args_sorting ) ) {

      $this->query_args = array_merge( $this->args_filter, $this->args_sorting );

    } else {

      $this->query_args = $this->args_filter;

    }

    if ( $this->debug_mode ) {

      $this->report_common_mistakes();

    }

    if ( ! empty( $this->query_args['custom_taxonomy_slug'] ) && ! empty( $this->query_args['custom_taxonomy_term'] ) ) {

      $custom_taxonomy_field_options = array(
        'term_id', 'name', 'slug', 'term_taxonomy_id',
      );

      if ( empty( $this->query_args['custom_taxonomy_field'] ) || ! in_array( $this->query_args['custom_taxonomy_field'], $custom_taxonomy_field_options ) ) {

        $this->query_args['custom_taxonomy_field'] = 'slug';

      }

      $terms = explode( ',', $this->query_args['custom_taxonomy_term'] );

      /**
       * Sort array to avoid duplicate cache for identical queries
       */
      asort( $terms );

      $this->query_args['tax_query'] = array(
        array(
          'taxonomy' => $this->query_args['custom_taxonomy_slug'],
          'field'    => $this->query_args['custom_taxonomy_field'],
          'terms'    => array_values( $terms ),
        ),
      );

      unset( $this->query_args['custom_taxonomy_slug'] );

      unset( $this->query_args['custom_taxonomy_field'] );

      unset( $this->query_args['custom_taxonomy_term'] );

    }

    $this->configure_return_values();

    /**
     * Sort by key to avoid duplicate cache for identical queries
     */
    ksort( $this->query_args );

    if ( $this->debug_mode > 1 ) {

      $this->add_debug_message( __( 'Query parameters:', 'redirect-url-to-post' ) );

      ob_start();

      var_dump( $this->query_args );

      $output = ob_get_contents();

      ob_end_clean();

      $this->add_debug_message( "<pre>" . $output . "</pre>\n" );

    }

    /**
     * Retrieve the post and redirect to its permalink
     */
    $this->cache_key = md5( serialize( $this->query_args ) . '-' . serialize( $this->php_rand ) . '-' . $this->count . '-' . $this->offset . '-' . serialize( $this->caching_seconds ) );

    $cache_result = $this->maybe_get_cache();

    if ( empty( $this->post_ids ) ) {

      $this->get_post_ids_from_db();

      /**
       * In most cases we need only one element
       */

      if ( ! $this->php_rand ) {

        $this->post_ids = array_slice( $this->post_ids, $this->offset, 1 );

      }

      /**
       * Random and not using bias: we need a subsection
       */

      if ( $this->php_rand && empty( $this->random_bias ) ) {

        if ( $this->count > 0 ) {

          $this->post_ids = array_slice( $this->post_ids, $this->offset, $this->count );

        } elseif ( $this->count < 0 ) {

          $this->post_ids = array_slice( $this->post_ids, $this->count - $this->offset, abs( $this->count ) );

        } else {

          $this->post_ids = array_slice( $this->post_ids, $this->offset );

        }

      }

    }

    if ( ! $cache_result ) {

      $this->maybe_set_cache();

    }

    if ( ! empty( $this->post_ids ) ) {

      if ( $this->post_lock ) {

        $this->get_locked_post();

      }

      if ( $this->php_rand && empty( $this->post_id ) ) {

        $this->post_id = $this->get_random_post_id();

      } elseif ( empty( $this->post_id ) ) {

        $this->post_id = reset( $this->post_ids );

      }

    }

  }

  /**
   * Configure the data type and size depending on what we need and optimizing with regards to memory usage
   *
   * @return void
   */
  private function configure_return_values() {

    if ( $this->request_post_objects ) {
      // ignore_sticky_posts=0

      $this->query_args['fields'] = 'all';
      // WP considers sticky posts only when requesting objects

      if ( $this->php_rand ) {

        $this->query_args['posts_per_page'] = -1; // the random post could be any => unavoidable risk of memory exhaustion

      } else {

        $this->query_args['posts_per_page'] = $this->offset + 100; // assuming that even with sticky posts we have well enough items

      }

    } else {

      $this->query_args['fields'] = 'ids'; // reduce memory usage

      $this->query_args['posts_per_page'] = -1; // we need all posts for picking a random; retrieving all post IDs should not cause memory exhaustion

    }

  }

  /**
   * Query the database and, if needed, extract IDs
   *
   * @return void
   */
  private function get_post_ids_from_db() {

    global $wpdb;

    /**
     * Restore query parameter to main post query
     */
    wp_reset_postdata();

    /**
     * Remove all hooks that might cancel out our 'orderby'
     */
    remove_all_actions( 'pre_get_posts' );

    /**
     * Reduce the risk of interference from other plugins
     * (It can be turned off, for example on multilingual websites)
     */

    if ( $this->args_filter['suppress_filters'] ) {

      remove_all_filters( 'posts_clauses' );
      remove_all_filters( 'posts_orderby' );
      remove_all_filters( 'posts_where' );
      remove_all_filters( 'posts_join' );
      remove_all_filters( 'posts_groupby' );

    }

    /**
     * WP_Query is supposed to sanitize $this->query_args
     */
    $the_query = new WP_Query( $this->query_args );

    if ( $this->debug_mode > 1 ) {

      $this->add_debug_message( __( 'Database request (generated by WordPress):', 'redirect-url-to-post' ) );
      $this->add_debug_message( __( '(Replace [table_prefix]_ with your WordPress Database Table prefix from wp-config.php.)', 'redirect-url-to-post' ) );
      
      $prefix = $wpdb->prefix;
      
      $db_request = preg_replace('/\b' . $prefix . '/', '[table_prefix]_', $the_query->request);
      
      $this->add_debug_message( "<pre>" . str_replace( "\t", "", $db_request ) . "</pre>\n" );


    }

    if ( ! empty( $the_query->posts ) ) {

      $this->maybe_add_debug_message( sprintf( __( 'We found %d matching post(s) in the database.', 'redirect-url-to-post' ), count( $the_query->posts ) ) );

    }

    if ( $this->request_post_objects ) {

      $this->post_ids = array();

      foreach ( $the_query->posts as $post ) {

        $this->post_ids[] = $post->ID;

      }

    } else {

      $this->post_ids = $the_query->posts;

    }

  }

  /**
   * Try to get the ID of the locked post from the cookie, set $this->locked_post_id to false if not found
   *
   * @return void
   */
  private function get_locked_post() {

    $this->locked_post_id = empty( $_COOKIE['chatty_mango_rutp_lock'] ) ? false : intval( $_COOKIE['chatty_mango_rutp_lock'] );

    if ( in_array( $this->locked_post_id, $this->post_ids ) ) {

      $this->post_id = $this->locked_post_id;

      $this->maybe_add_debug_message( sprintf( __( 'The redirection is locked to post ID %d.', 'redirect-url-to-post' ), $this->post_id, $this->post_lock ) );

    } else {

      $this->locked_post_id = false;

    }

  }

  /**
   * Try to set the locked post's ID in a cookie
   *
   * @return void
   */
  private function set_locked_post() {

    $result = setcookie( 'chatty_mango_rutp_lock', $this->post_id, time() + intval( $this->post_lock ), $this->cookie_path );

    if ( $result ) {

      $this->maybe_add_debug_message( sprintf( __( 'We lock the redirection to post ID %d for %d seconds.', 'redirect-url-to-post' ), $this->post_id, $this->post_lock ) );

    }

  }

  /**
   * Determine the URL that should be redirected to
   *
   * @return string
   */
  private function determine_target() {

    $this->redirection_target = '';

    if ( isset( $this->post_id ) ) {

      if ( $this->post_lock && false === $this->locked_post_id ) {

        $this->set_locked_post();

      }

      $permalink = get_permalink( $this->post_id );

      /**
       * Add query parameters that appear in the final URL
       */

      if ( ! empty( $this->query_args_pass_through ) ) {

        $permalink = esc_url_raw( add_query_arg( $this->query_args_pass_through, $permalink ) );

      }

      $this->redirection_target = $permalink;

    } else {

      /**
       *  Nothing found, go to post with ID as specified by redirect_to_default, or home
       */

      if ( isset( $_GET['default_redirect_to'] ) ) {

        $default_redirect_to = sanitize_key( $_GET['default_redirect_to'] );

      }

      if ( empty( $default_redirect_to ) ) {

        $this->maybe_add_debug_message( __( 'Nothing found, going to site URL.', 'redirect-url-to-post' ) );

        // no valid default given => go to home page
        $this->redirection_target = site_url();

      } else {

        $permalink = get_permalink( $default_redirect_to );

        if ( false === $permalink ) {

          $this->maybe_add_debug_message( __( 'Nothing found and default_redirect_to not found, going to site URL.', 'redirect-url-to-post' ) );

          // default post or page does not exist => go to home page
          $this->redirection_target = site_url();

        } else {

          $this->maybe_add_debug_message( __( 'Nothing found, going to default_redirect_to.', 'redirect-url-to-post' ) );

          $this->redirection_target = $permalink;

        }

      }

    }

  }

  /**
   * Return all data from the current post or page that is required for redirecting to the previous or next item
   *
   * @return array|boolean
   */
  private function get_current_post_data() {

    global $wp_query;

    if ( is_home() ) {

      $page_id = get_option( 'page_for_posts' );

      $current_post_data = array(
        'post_date' => get_the_date( '', $page_id ), // virtual front page for blog posts has date "0"
        'post_type' => 'page',
      );

    } else {

      if ( empty( $wp_query ) || empty( $wp_query->post ) ) {

        // something terrible has happened

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {

          error_log( '[Redirect URL to Post] Cannot identify the current post.' );

        }

        // steal away and pretend to be invisible

        return false;

      }

      $current_post_data = array(
        'post_date' => $wp_query->post->post_date,
        'post_type' => $wp_query->post->post_type,
      );

    }

    return $current_post_data;

  }

  /**
   * Display an admin notice
   *
   *
   * @return void
   */
  public function admin_notice() {
    /**
     * Display only for first-time activation, not after updates
     * Don't use on_activation, because then you cannot use localization
     */

    if ( get_option( 'redirect-url-to-post-onboarding', false ) === false ) {

      $plugin_link = 'https://chattymango.com/redirect-url-to-post/';

      $documentation_link = 'https://documentation.chattymango.com/documentation/redirect-url-to-post/';

      $random_post_link                = get_site_url( null, '/?redirect_to=random' );
      $latest_post_link                = get_site_url( null, '/?redirect_to=latest' );
      $date_query_before_post_link     = get_site_url( null, '/?redirect_to=latest&before=10%20minute%20ago' );
      $date_query_after_post_link      = get_site_url( null, '/?redirect_to=random&after=1%20month%20ago&cache=20' );
      $random_post_link_each_once_lock = get_site_url( null, '/?redirect_to=random&each_once=rewind&lock=15' );

      $html = '<div class="notice notice-info is-dismissible"><p>' .
      '<h3>' . sprintf( __( 'Thank you for installing Redirect URL to Post!', 'redirect-url-to-post' ), 'href="' . esc_url( $plugin_link ) . '?pk_campaign=rutp&pk_kwd=onboarding" target="_blank"' ) . '</h3>' .
      '<p>' . __( "This plugin doesn't have any settings. You configure it entirely through the URL query parameters.", 'redirect-url-to-post' ) . '</p>' .
      '<h4>' . __( 'Here are some examples:', 'redirect-url-to-post' ) . '</h4>' .
      '<ul style="list-style-type:disc; padding-left: 20px;">
      <li>' . sprintf( __( 'Go to a random post: <a %1$s>%2$s</a>', 'redirect-url-to-post' ), 'href="' . esc_url( $random_post_link ) . '" target="_blank"', $random_post_link ) . '</li>
      <li>' . sprintf( __( 'Go to your latest post: <a %1$s>%2$s</a>', 'redirect-url-to-post' ), 'href="' . esc_url( $latest_post_link ) . '" target="_blank"', $latest_post_link ) . '</li>
      <li>' . sprintf( __( 'Go to your latest post that was created at least 10 minutes ago: <a %1$s>%2$s</a> (We replaced all spaces by "%%20".)', 'redirect-url-to-post' ), 'href="' . esc_url( $date_query_before_post_link ) . '" target="_blank"', $date_query_before_post_link ) . '</li>
      <li>' . sprintf( __( 'Go to a random post from the past month and set the cache to 20 seconds: <a %1$s>%2$s</a> (or to the front page, if nothing was found)', 'redirect-url-to-post' ), 'href="' . esc_url( $date_query_after_post_link ) . '" target="_blank"', $date_query_after_post_link ) . '</li>
      <li>' . sprintf( __( 'Go to a random post while avoiding to show the same post twice until all posts are done; keep showing the same post for 15 seconds: <a %1$s>%2$s</a>', 'redirect-url-to-post' ), 'href="' . esc_url( $random_post_link_each_once_lock ) . '" target="_blank"', $random_post_link_each_once_lock ) . '</li>
      </ul>' .
      '<p>' . __( 'You can use these links anywhere in your posts and pages, in menus or in buttons. You can even send them in a newsletter. Or you could try the shortcode [redirect_to_post_button] in a page or post.', 'redirect-url-to-post' ) . '</p>' .
      '<p>' . sprintf( __( 'Please find a description of all parameters in the <a %s>plugin documentation</a>.', 'redirect-url-to-post' ), 'href="' . esc_url( $documentation_link ) . '?pk_campaign=rutp&pk_kwd=onboarding" target="_blank"' ) . '</p>' .
      '<p>' . __( 'Have fun!', 'redirect-url-to-post' ) . '</p>' .
        '</p></div><div clear="all" /></div>';

      echo $html;

      update_option( 'redirect-url-to-post-onboarding', 1 );

    }

  }

  /**
   * Redirect to a URL
   *
   * Using own redirection so that we can add a header.
   * Firefox 57 needs to be told not to cache.
   *
   * @return void
   */
  private function redirect() {

    if ( $this->debug_mode ) {

      $this->add_debug_message( sprintf( '<br><b>=> ' . __( 'The resulting URL is: <a href="%s" target="_blank">%s</a>', 'redirect-url-to-post' ) . "</b><br>", $this->redirection_target, $this->redirection_target ) );

      $this->add_debug_message( sprintf( __( 'For more help visit the <a href="%s" target="_blank">documentation</a>.', 'redirect-url-to-post' ), 'https://documentation.chattymango.com/documentation/redirect-url-to-post/?pk_campaign=rutp&pk_kwd=debug' ) );

      $this->output_debug_messages();

      exit; // Don't just return because we don't want other code to hide our debug messages

    }

    if ( $this->headers_sent ) {

      wp_redirect( $this->redirection_target, 307 );

    } else {

      /**
       * best experience with code 307 for preventing caching in browser
       * create own redirect to be able to add own header
       */
      header( 'Cache-Control: no-cache, must-revalidate' );
      header( 'Location: ' . $this->redirection_target, true, 307 );

    }

    exit;

  }

  /**
   * Set the cache if required
   *
   * @return void
   */
  private function maybe_set_cache() {

    if ( empty( $this->post_ids ) || empty( $this->cache_key ) || empty( $this->caching_seconds ) || -1 == $this->caching_seconds ) {

      return;

    }

    /**
     * Save the result to the transient cache
     */
    set_transient( 'chatty_mango_rutp_post_ids-' . $this->cache_key, $this->post_ids, $this->caching_seconds );

    $this->add_transient_key();

    $this->maybe_add_debug_message( sprintf( __( 'We filled the cache with a lifetime of %d seconds.', 'redirect-url-to-post' ), $this->caching_seconds ) );

  }

  /**
   * Get the cache if required
   *
   * @return boolean
   */
  private function maybe_get_cache() {

    if ( empty( $this->caching_seconds ) ) {

      return false;

    } elseif ( -1 == $this->caching_seconds ) {

      delete_transient( 'chatty_mango_rutp_post_ids-' . $this->cache_key );

      $this->remove_transient_key();

      $this->maybe_add_debug_message( __( 'We deleted the cache for these parameters.', 'redirect-url-to-post' ) );

      return false;

    }

    /**
     * We save every $this->cache_key as own transient so that they can have different lifetimes.
     */
    $this->post_ids = get_transient( 'chatty_mango_rutp_post_ids-' . $this->cache_key );

    if ( ! empty( $this->post_ids ) ) {

      $this->maybe_add_debug_message( sprintf( __( 'We found %d matching post(s) in the cache.', 'redirect-url-to-post' ), count( $this->post_ids ) ) );

      return true;

    }

    $this->remove_transient_key();

    return false;

  }

  /**
   * Clear the entire cache
   *
   * @return void
   */
  public function clear_cache() {
  
    $transient_keys = get_transient( 'chatty_mango_rutp_transient_keys');
    
    if ( ! is_array( $transient_keys ) ) {

      return;

    }

    foreach ( $transient_keys as $transient_key ) {

      delete_transient( 'chatty_mango_rutp_post_ids-' . $transient_key );
    
    }

    delete_transient('chatty_mango_rutp_transient_keys');

  }

  /**
   * Add the cache key to the list of used transient keys
   *
   * @return void
   */
  private function add_transient_key() {

    $transient_keys = get_transient('chatty_mango_rutp_transient_keys');

    if ( ! is_array( $transient_keys ) ) {

      $transient_keys = array();

    }

    if ( ! in_array( $this->cache_key, $transient_keys ) ) {

      $transient_keys[] = $this->cache_key;
      
      set_transient('chatty_mango_rutp_transient_keys', $transient_keys);

    }

  }

  /**
   * Remove the cache key from the list of used transient keys
   *
   * @return void
   */
  private function remove_transient_key() {

    $transient_keys = get_transient('chatty_mango_rutp_transient_keys');

    if ( is_array( $transient_keys ) && array_key_exists( $this->cache_key, $transient_keys ) ) {
      
      unset( $transient_keys[ $this->cache_key ] );

      set_transient('chatty_mango_rutp_transient_keys', $transient_keys);

    }

  }

  /**
   * Add a debug message to the queue, if debugging is on
   *
   * @param  string $message
   * @return void
   */
  private function maybe_add_debug_message( $message ) {

    if ( defined( 'WP_DEBUG' ) && WP_DEBUG && $this->verbose_debug ) {

      error_log( '[Redirect URL to Post] ' . $message );

    }

    if ( ! $this->debug_mode ) {

      return;

    }

    $this->add_debug_message( $message );

  }

  /**
   * Add a debug message to the queue
   *
   * @param  string $message
   * @return void
   */
  private function add_debug_message( $message ) {

    $this->debug_messages[] = $message;

  }

  /**
   * Display the debug messages on the screen
   *
   * @param  void
   * @return void
   */
  private function output_debug_messages() {

    echo implode( "<br>\n", $this->debug_messages );

  }

  /**
   * Add Help link to plugins page
   *
   * @param  array   $links
   * @return array
   */
  public function add_help_link( $links ) {

    $settings_link = '<a href="https://documentation.chattymango.com/documentation/redirect-url-to-post/?pk_campaign=rutp&pk_kwd=settings" target="_blank">' . __( "Help", "redirect-url-to-post" ) . '</a>';

    array_unshift( $links, $settings_link );

    return $links;

  }

  /**
   * Implement a shortcode to add a random button
   * Requires Javascript
   *
   * @param  array  $atts Shortcode parameters
   * @return string HTML to replace the shortcode
   */
  public function redirect_to_post_button( $atts = array() ) {

    extract( shortcode_atts( array(
      'div_class'    => null,
      'button_class' => null,
      'text'         => __( 'Random Post', 'redirect-url-to-post' ),
      'redirect_to'  => 'random',
      'params'       => null,
    ), $atts ) );

    $url = get_site_url( null, '/?redirect_to=' . $redirect_to );

    /**
     * Add optional paramters
     */

    if ( ! empty( $params ) ) {

      if ( strpos( $params, '=>' ) !== false ) {

        // old format; split the string by commas
        $params_array = explode( ',', $params );

        foreach ( $params_array as $item ) {

          $item_array = explode( '=>', $item );

          if ( count( $item_array ) == 2 ) {

            $item_array[0] = sanitize_key( trim( $item_array[0] ) );
            $item_array[1] = sanitize_title( trim( $item_array[1] ) );

            $url .= '&' . $item_array[0] . '=' . $item_array[1];

          }

        }

      } else {

        // split the string by |
        $params_array = explode( '|', $params );

        foreach ( $params_array as $item ) {

          $item_array = explode( '=', $item );

          if ( count( $item_array ) == 2 ) {

            $item_array[0] = sanitize_key( trim( $item_array[0] ) );
            $item_array[1] = sanitize_text_field( trim( $item_array[1] ) );

            $url .= '&' . $item_array[0] . '=' . $item_array[1];

          } elseif ( count( $item_array ) == 1 ) {

            $item_array[0] = sanitize_key( trim( $item_array[0] ) );

            $url .= '&' . $item_array[0];

          }

        }

      }

    }

    $html = '<div';

    if ( ! empty( $div_class ) ) {

      $html .= ' class="' . sanitize_html_class( $div_class ) . '"';

    }

    $html .= '>';

    /**
     * Construct the actual button
     */
    $html .= '<button';

    if ( ! empty( $button_class ) ) {

      $html .= ' class="' . sanitize_html_class( $button_class ) . '"';

    }

    $url = str_replace( '"', '\"', $url );

    $url = str_replace( "'", '\"', $url );

    $html .= ' onclick="window.location.href=\'' . esc_url( $url ) . '\'">' . sanitize_text_field( $text ) . '</button>';

    $html .= '</div>';

    return $html;

  }

  /**
   * pick a random post ID from a list of IDs
   *
   * @return integer
   */
  private function get_random_post_id() {

    if ( $this->each_once ) {

      $this->remove_done_posts();

    }

    if ( count( $this->post_ids ) ) {

      if ( empty( $this->random_bias ) ) {

        /**
         * Pick a random post from the array
         */
        $this->post_id = $this->post_ids[array_rand( $this->post_ids )];

      } else {

        /**
         * Get the subsection of posts that will receive the bias
         */

        if ( $this->count > 0 ) {

          $bias_post_ids = array_slice( $this->post_ids, $this->offset, $this->count );

        } elseif ( $this->count < 0 ) {

          $bias_post_ids = array_slice( $this->post_ids, $this->count - $this->offset, abs( $this->count ) );

        } else {

          return 0;

        }

        if ( rand( 0, 99 ) < $this->random_bias ) {
          /**
           * Pick a post from $bias_post_ids
           */
          $this->post_id = $bias_post_ids[array_rand( $bias_post_ids )];

        } else {
          /**
           * Pick a post from the rest
           */
          $post_ids_outside_bias = array_diff( $this->post_ids, $bias_post_ids );

          $this->post_id = $post_ids_outside_bias[array_rand( $post_ids_outside_bias )];

        }

      }

      if ( $this->each_once ) {

        $this->set_done_posts();

      }

      $this->maybe_add_debug_message( __( 'We picked a random post.', 'redirect-url-to-post' ) );

    } else {

      if ( is_numeric( $this->each_once ) && $this->each_once > 0 ) {

        $this->post_id = (int) $this->each_once;

      }

    }

    return $this->post_id;

  }

  /**
   * Remove posts that are done from the list of post IDs
   *
   * @return void
   */
  private function remove_done_posts() {

    if ( $this->debug_mode ) {

      $test_max_cookie_content = implode( '-', $this->post_ids );

      if ( strlen( $test_max_cookie_content ) > 4096 ) {

        $this->add_debug_message( sprintf( __( 'Warning: Size of concatenated post IDs (up to %d Bytes) may be more than the maximum size of a cookie (4096 Bytes).', 'redirect-url-to-post' ), strlen( $test_max_cookie_content ) ) );

      }

    }

    // post ID is never 0
    $this->done_post_ids = empty( $_COOKIE['chatty_mango_rutp_done'] ) ? array() : explode( '-', $_COOKIE['chatty_mango_rutp_done'] );

    $this->done_post_ids = array_map( 'intval', $this->done_post_ids );

    $post_ids_left = array_diff( $this->post_ids, $this->done_post_ids );

    if ( ! count( $post_ids_left ) ) {

      $this->maybe_add_debug_message( __( 'We have seen all random posts.', 'redirect-url-to-post' ) );

      setcookie( 'chatty_mango_rutp_done', '', time(), $this->cookie_path );

      $this->done_post_ids = array();

      if ( 'rewind' === $this->each_once ) {

        $this->maybe_add_debug_message( __( 'Rewinding the random posts.', 'redirect-url-to-post' ) );

      } else {

        /**
         * We finish
         */
        $this->post_ids = array();

      }

    } else {

      $this->post_ids = $post_ids_left;

    }

  }

  /**
   * Save the list of done post IDs to the cookie
   *
   * @return void
   */
  private function set_done_posts() {

    $this->done_post_ids[] = $this->post_id;

    $cookie_content = implode( '-', $this->done_post_ids );

    setcookie( 'chatty_mango_rutp_done', $cookie_content, time() + WEEK_IN_SECONDS, $this->cookie_path );

  }

  /**
   * Check if the requested redirect requires that we can retrieve the ID of the currently viewed post
   *
   * @return boolean
   */
  private function requires_post_id() {

    $redirect_targets_requiring_post_id = array(
      'prev',
      'previous',
      'next',
    );

    return in_array( $this->redirect_to, $redirect_targets_requiring_post_id );

  }


  /**
   * We report errors that help debugging problems caused by wrong parameters
   *
   * @return void
   */
  private function report_common_mistakes() {

    if ( ! empty( $this->query_args['post_type'] ) ) {
      
      /**
       * Wrong post type
       */
      $post_types = get_post_types();
  
      if ( is_string( $this->query_args['post_type'] ) && ! in_array( $this->query_args['post_type'], $post_types ) ) {
  
        $this->add_debug_message( sprintf( 'Unknown post type "%s"', $this->query_args['post_type'] ) );
  
        $this->add_debug_message( sprintf( 'List of registered post types: %s', implode( ', ', $post_types ) ) );
  
      }
  
      if ( is_array( $this->query_args['post_type'] ) ) {

        $unknown_post_type_found = false;

        foreach ( $this->query_args['post_type'] as $post_type ) {
          
          if ( ! in_array( $post_type, $post_types ) ) {
  
            $this->add_debug_message( sprintf( 'Unknown post type "%s"', $post_type ) );
      
            $unknown_post_type_found = true;

          }
            
        }
          
        if ( $unknown_post_type_found ) {

          $this->add_debug_message( sprintf( 'List of registered post types: %s', implode( ', ', $post_types ) ) );

        }

      }

    }

    if ( ! empty( $this->query_args['custom_taxonomy_slug'] ) && ! empty( $this->query_args['custom_taxonomy_term'] ) ) {

      /**
       * Wrong custom taxonomy
       */
      $taxonomies = get_taxonomies();

      if ( ! in_array( $this->query_args['custom_taxonomy_slug'], $taxonomies ) ) {

        $this->add_debug_message( sprintf( 'Unknown taxonomy slug "%s"', $this->query_args['custom_taxonomy_slug'] ) );

        $this->add_debug_message( sprintf( 'List of registered taxonomy slugs: %s', implode( ', ', $taxonomies ) ) );

      }

    }

  }

}

/**
 * Launch the plugin: add actions and filters
 */
$RedirectURLToPost = new RedirectURLToPost();

/**
 * Internationalization before WP 4.6
 *
 * @param  void
 * @return void
 */
function redirect_url_to_post_load_plugin_textdomain() {

  load_plugin_textdomain( 'redirect-url-to-post', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

}

add_action( 'init', 'redirect_url_to_post_load_plugin_textdomain' );
