<?php

defined('ABSPATH') or die("Walk away.");

/* this is the base class for all backends; the actual workers
 * are included at the end of the file from backends/ directory */

if (!class_exists('WP_FFPC_Backend')) :


abstract class WP_FFPC_Backend {

	const host_separator  = ',';
	const port_separator  = ':';

	protected $connection = NULL;
	protected $alive = false;
	protected $options = array();
	protected $status = array();
	public $cookies = array();
	protected $urimap = array();

	/**
	* constructor
	*
	* @param mixed $config Configuration options
	*
	*/
	public function __construct( $config ) {

		/* no config, nothing is going to work */
		if ( empty ( $config ) ) {
			return false;
			//die ( __translate__ ( 'WP-FFPC Backend class received empty configuration array, the plugin will not work this way', 'wp-ffpc') );
		}

		$this->options = $config;

		/* these are the list of the cookies to look for when looking for logged in user */
		$this->cookies = array ( 'comment_author_' , 'wordpressuser_' , 'wp-postpass_', 'wordpress_logged_in_' );

		/* map the key with the predefined schemes */
		$ruser = isset ( $_SERVER['REMOTE_USER'] ) ? $_SERVER['REMOTE_USER'] : '';
		$ruri = isset ( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
		$rhost = isset ( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : '';
		$scookie = isset ( $_COOKIE['PHPSESSID'] ) ? $_COOKIE['PHPSESSID'] : '';

		if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' )
			$_SERVER['HTTPS'] = 'on';

		$scheme = ( isset($_SERVER['HTTPS']) && (( strtolower($_SERVER['HTTPS']) == 'on' )  || ( $_SERVER['HTTPS'] == '1' ) )) ? 'https://' : 'http://';

		$this->urimap = array(
			'$scheme' => str_replace ( '://', '', $scheme ),
			'$host' => $rhost,
			'$request_uri' => $ruri,
			'$remote_user' => $ruser,
			'$cookie_PHPSESSID' => $scookie,
		);

		/* split single line hosts entry */
		$this->set_servers();

		/* info level */
		$this->log ( 'init starting' );

		/* call backend initiator based on cache type */
		$init = $this->_init();

		if (is_admin() && function_exists('add_filter')) {
			add_filter('wp_ffpc_clear_keys_array', function($to_clear, $options) {
				$filtered_result = array();
				foreach ( $to_clear as $link => $dummy ) {
					/* clear feeds, meta and data as well */
					$filtered_result[ $options[ 'prefix_meta' ] . $link ] = true;
					$filtered_result[ $options[ 'prefix_data' ] . $link ] = true;
					$filtered_result[ $options[ 'prefix_meta' ] . $link . 'feed' ] = true;
					$filtered_result[ $options[ 'prefix_data' ] . $link . 'feed' ] = true;
				}
				return $filtered_result;
			}, 10, 2);
		}
	}

	/*
	 * @param string $uri
	 * @param mixed $default_urimap
	 */
	public static function parse_urimap($uri, $default_urimap=null) {
		$uri_parts = parse_url( $uri );

		$uri_map = array(
			'$scheme' => $uri_parts['scheme'],
			'$host' => $uri_parts['host'],
			'$request_uri' => $uri_parts['path']
		);

		if (is_array($default_urimap)) {
			$uri_map = array_merge($default_urimap, $uri_map);
		}

		return $uri_map;
	}

	/**
	 * @param array $urimap
	 * @param string $subject
	 */
	public static function map_urimap($urimap, $subject) {
		return str_replace(array_keys($urimap), $urimap, $subject);
	}


	/**
	 * build key to make requests with
	 *
	 * @param string $prefix prefix to add to prefix
	 * @param array $customUrimap to override defaults
	 *
	 */
	public function key ( $prefix, $customUrimap = null ) {
		$urimap = $customUrimap ?: $this->urimap;

		$key_base = self::map_urimap($urimap, $this->options['key']);

		if (( isset($this->options['hashkey']) && $this->options['hashkey'] == true) || $this->options['cache_type'] == 'redis' )
			$key_base = sha1($key_base);

		$key = $prefix . $key_base;

		$this->log ( sprintf( 'original key configuration: %s',  $this->options['key'] ) );
		$this->log ( sprintf( 'setting key for: %s',  $key_base ) );
		$this->log ( sprintf( 'setting key to: %s',  $key ) );
		return $key;
	}


	/**
	 * public get function, transparent proxy to internal function based on backend
	 *
	 * @param string $key Cache key to get value for
	 *
	 * @return mixed False when entry not found or entry value on success
	 */
	public function get ( &$key ) {
		/* look for backend aliveness, exit on inactive backend */
		if ( ! $this->is_alive() ) {
			$this->log ('WARNING: Backend offline');
			return false;
		}

		/* log the current action */
		$this->log ( sprintf( 'GET %s',  $key ) );

		$result = $this->_get( $key );

		if ( $result === false || $result === null )
			$this->log ( sprintf( 'failed to get entry: %s',  $key ) );

		return $result;
	}

	/**
	 * public set function, transparent proxy to internal function based on backend
	 *
	 * @param string $key Cache key to set with ( reference only, for speed )
	 * @param mixed $data Data to set ( reference only, for speed )
	 *
	 * @return mixed $result status of set function
	 */
	public function set ( &$key, &$data, $expire = false ) {
		/* look for backend aliveness, exit on inactive backend */
		if ( ! $this->is_alive() )
			return false;

		/* log the current action */
		$this->log ( sprintf( 'set %s expiration time: %s', $key, $this->options['expire'] ) );

		/* expiration time based is based on type from now on */
		/* fallback */
		if ( $expire === false )
			$expire = empty ( $this->options['expire'] ) ? 0 : $this->options['expire'];

		if (( is_home() || is_feed() ) && isset($this->options['expire_home']))
			$expire = (int) $this->options['expire_home'];
		elseif (( is_tax() || is_category() || is_tag() || is_archive() ) && isset($this->options['expire_taxonomy']))
			$expire = (int) $this->options['expire_taxonomy'];

		/* log the current action */
		$this->log ( sprintf( 'SET %s',  $key ) );
		/* proxy to internal function */
		$result = $this->_set( $key, $data, $expire );

		/* check result validity */
		if ( $result === false || $result === null )
			$this->log ( sprintf( 'failed to set entry: %s', $key ), LOG_WARNING );

		return $result;
	}

	/*
	 * next generation clean
	 *
	 *
	 */
	public function clear_ng ( $new_status, $old_status, $post ) {
		$this->clear ( $post->ID );
	}

	/**
	 * public get function, transparent proxy to internal function based on backend
	 *
	 * @param string $post_id	ID of post to invalidate
	 * @param boolean $force 	Force flush cache
	 *
	 */
	public function clear ( $post_id = false, $force = false ) {

		/* look for backend aliveness, exit on inactive backend */
		if ( ! $this->is_alive() )
			return false;

		/* exit if no post_id is specified */
		if ( empty ( $post_id ) && $force === false ) {
			$this->log ( 'not clearing unidentified post', LOG_WARNING );
			return false;
		}

		/* if invalidation method is set to full, flush cache */
		if ( ( $this->options['invalidation_method'] === 0 || $force === true ) ) {
			/* log action */
			$this->log ( 'flushing cache' );

			/* proxy to internal function */
			$result = $this->_flush();

			if ( $result === false )
				$this->log ( 'failed to flush cache', LOG_WARNING );

			return $result;
		}

		/* storage for entries to clear */
		$to_clear = array();

		/* clear taxonomies if settings requires it */
		if ( $this->options['invalidation_method'] == 2 ) {
			/* this will only clear the current blog's entries */
			$this->taxonomy_links( $to_clear );
		}

		/* clear pasts index page if settings requires it */
		if ( $this->options['invalidation_method'] == 3 ) {
			$posts_page_id = get_option( 'page_for_posts' );
			$post_type = get_post_type( $post_id );

			if ($post_type === 'post' && $posts_page_id != $post_id) {
				$this->clear($posts_page_id, $force);
			}
		}


		/* if there's a post id pushed, it needs to be invalidated in all cases */
		if ( !empty ( $post_id ) ) {

			/* need permalink functions */
			if ( !function_exists('get_permalink') )
				include_once ( ABSPATH . 'wp-includes/link-template.php' );

			/* get permalink */
			$permalink = get_permalink( $post_id );

			/* no path, don't do anything */
			if ( empty( $permalink ) && $permalink != false ) {
				$this->log ( sprintf( 'unable to determine path from Post Permalink, post ID: %s',  $post_id ), LOG_WARNING );
				return false;
			}

			/*
			 * It is possible that post/page is paginated with <!--nextpage-->
			 * Wordpress doesn't seem to expose the number of pages via API.
			 * So let's just count it.
			 */
			$content_post = get_post( $post_id );
			$content = $content_post->post_content;
			$number_of_pages = 1 + (int)preg_match_all('/<!--nextpage-->/', $content, $matches);

			$current_page_id = '';
			do {
				/* urimap */
				$urimap = self::parse_urimap($permalink, $this->urimap);
				$urimap['$request_uri'] = $urimap['$request_uri'] . ($current_page_id ? $current_page_id . '/' : '');

				$clear_cache_key = self::map_urimap($urimap, $this->options['key']);

				$to_clear[ $clear_cache_key ] = true;

				$current_page_id = 1+(int)$current_page_id;
			} while ($number_of_pages>1 && $current_page_id<=$number_of_pages);
		}

		/* Hook to custom clearing array. */
		$to_clear = apply_filters('wp_ffpc_to_clear_array', $to_clear, $post_id);

		/* run clear */
		$this->clear_keys( $to_clear );
	}

	/*
	 * unset entries by key
	 * @param array $keys
	 */
	public function clear_keys( $keys ) {
		$to_clear = apply_filters('wp_ffpc_clear_keys_array', $keys, $this->options);
		$this->_clear ( $to_clear );
	}

	/**
	 * clear cache triggered by new comment
	 *
	 * @param $comment_id	Comment ID
	 * @param $comment_object	The whole comment object ?
	 */
	public function clear_by_comment ( $comment_id, $comment_object ) {
		if ( empty( $comment_id ) )
			return false;

		$comment = get_comment( $comment_id );
		$post_id = $comment->comment_post_ID;
		if ( !empty( $post_id ) )
			$this->clear ( $post_id );

		unset ( $comment );
		unset ( $post_id );
	}

	/**
	 * to collect all permalinks of all taxonomy terms used in invalidation & precache
	 *
	 * @param array &$links Passed by reference array that has to be filled up with the links
	 * @param mixed $site Site ID or false; used in WordPress Network
	 *
	 */
	public function taxonomy_links ( &$links, $site = false ) {

		if ( $site !== false ) {
			$current_blog = get_current_blog_id();
			switch_to_blog( $site );

			$url = get_blog_option ( $site, 'siteurl' );
			if ( substr( $url, -1) !== '/' )
				$url = $url . '/';

			$links[ $url ] = true;
		}

		/* we're only interested in public taxonomies */
		$args = array(
			'public'   => true,
		);

		/* get taxonomies as objects */
		$taxonomies = get_taxonomies( $args, 'objects' );

		if ( !empty( $taxonomies ) ) {
			foreach ( $taxonomies  as $taxonomy ) {
				/* reset array, just in case */
				$terms = array();

				/* get all the terms for this taxonomy, only if not empty */
				$sargs = array(
					'hide_empty'    => true,
					'fields'        => 'all',
					'hierarchical'  =>false,
				);
				$terms = get_terms ( $taxonomy->name , $sargs );

				if ( !empty ( $terms ) ) {
					foreach ( $terms as $term ) {

						/* skip terms that have no post associated and somehow slipped
						 * throught hide_empty */
						if ( $term->count == 0)
							continue;

						/* get the permalink for the term */
						$link = get_term_link ( $term->slug, $taxonomy->name );
						/* add to container */
						$links[ $link ] = true;
						/* remove the taxonomy name from the link, lots of plugins remove this for SEO, it's better to include them than leave them out in worst case, we cache some 404 as well
						*/
						$link = str_replace ( '/'.$taxonomy->rewrite['slug'], '', $link  );
						/* add to container */
						$links[ $link ] = true;
					}
				}
			}
		}

		/* switch back to original site if we navigated away */
		if ( $site !== false ) {
			switch_to_blog( $current_blog );
		}

	}

	/**
	 * get backend aliveness
	 *
	 * @return array Array of configured servers with aliveness value
	 *
	 */
	public function status () {

		/* look for backend aliveness, exit on inactive backend */
		if ( ! $this->is_alive() )
			return false;

		$internal = $this->_status();
		return $this->status;
	}

	/**
	 * function to check backend aliveness
	 *
	 * @return boolean true if backend is alive, false if not
	 *
	 */
	protected function is_alive() {
		if ( ! $this->alive ) {
			$this->log ( "backend is not active, exiting function " . __FUNCTION__, LOG_WARNING );
			return false;
		}

		return true;
	}

	/**
	 * split hosts string to backend servers
	 *
	 *
	 */
	protected function set_servers () {
		if ( empty ($this->options['hosts']) )
			return false;

		/* replace servers array in config according to hosts field */
		$servers = explode( self::host_separator , $this->options['hosts']);

		$options['servers'] = array();

		foreach ( $servers as $snum => $sstring ) {

			if ( stristr($sstring, 'unix://' ) ) {
				$host = str_replace('unix:/','',$sstring);
				$port = 0;
			}
			else {
				$separator = strpos( $sstring , self::port_separator );
				$host = substr( $sstring, 0, $separator );
				$port = substr( $sstring, $separator + 1 );
			}

			$this->options['servers'][$sstring] = array (
				'host' => $host,
				'port' => $port
			);
		}

	}

	/**
	 * get current array of servers
	 *
	 * @return array Server list in current config
	 *
	 */
	public function get_servers () {
		$r = isset ( $this->options['servers'] ) ? $this->options['servers'] : '';
		return $r;
	}

	/**
	 * log wrapper to include options
	 *
	 * @var mixed $message Message to log
	 * @var int $log_level Log level
	 */
	protected function log ( $message, $level = LOG_NOTICE ) {
		if ( @is_array( $message ) || @is_object ( $message ) )
			$message = json_encode($message);


		switch ( $level ) {
			case LOG_ERR :
				wp_die( '<h1>Error:</h1>' . '<p>' . $message . '</p>' );
				exit;
			default:
				if ( !defined( 'WP_DEBUG' ) || WP_DEBUG != true || !defined( 'WP_FFPC__DEBUG_MODE' ) || WP_FFPC__DEBUG_MODE != true )
					return;
				break;
		}

		error_log(  __CLASS__ . ": " . $message );
	}


	abstract protected function _init ();
	abstract protected function _status ();
	abstract protected function _get ( &$key );
	abstract protected function _set ( &$key, &$data, &$expire );
	abstract protected function _flush ();
	abstract protected function _clear ( &$keys );
}

endif;


$wp_ffpc_backends = glob( dirname( __FILE__ ) . "/backends/*.php" );
foreach ( $wp_ffpc_backends as $backend )
	include_once $backend;
unset( $wp_ffpc_backends, $backend );
