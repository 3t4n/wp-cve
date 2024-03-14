<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Http_Uri_HC_MVC extends _HC_MVC
{
	protected $hca = 'hca';
	protected $hcs = 'hcs';

	// protected $joinArray = '|';
	protected $joinArray = '_';

	protected $mode_urls = array();
	protected $current_mode = NULL;

	private $base_url = '';
	private $base_params = array();
	public $raw_params;
	private $params = array();
	private $slug = '';
	private $args = NULL;

	public function __construct()
	{
		$this->from_url( $this->current() );
	}
	public function single_instance()
	{
	}

	public function set_mode_url( $k, $v )
	{
		$this->mode_urls[$k] = $v;
		return $this;
	}

	public function mode( $mode )
	{
		$this->current_mode = $mode;
		return $this;
	}

	public function current()
	{
		$return = 'http';
		if(
			( isset($_SERVER['HTTPS']) && ( $_SERVER['HTTPS'] == 'on' ) )
			OR
			( defined('NTS_HTTPS') && NTS_HTTPS )
			){
			$return .= 's';
		}
		$return .= "://";
		// if( isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != '80'){
			// $return .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'];
		// }
		// else {
			// $return .= $_SERVER['SERVER_NAME'];
		// }

		if( isset($_SERVER['HTTP_HOST']) && $_SERVER['SERVER_PORT'] != '80'){
			$return .= $_SERVER['HTTP_HOST'] . ':' . $_SERVER['SERVER_PORT'];
		}
		else {
			$return .= $_SERVER['HTTP_HOST'];
		}

		if ( ! empty($_SERVER['REQUEST_URI']) ){
			$return .= $_SERVER['REQUEST_URI'];
		}
		else {
			$return .= $_SERVER['SCRIPT_NAME'];
		}

		$return = urldecode( $return );
		return $return;
	}

	public function from_url( $url )
	{
		$parsed = $this->parse_url( $url );

		$this->base_url		= $parsed['base_url'];
		$this->slug			= $parsed['slug'];
		$this->raw_params	= $parsed['raw_params'];
		$this->params		= $parsed['params'];
		$this->base_params	= $parsed['base_params'];

		return $this;
	}

	public function parse_url( $url )
	{
		$return = array(
			'base_url'		=> '',
			'slug'			=> '',
			'raw_params'	=> array(),
			'params'		=> array(),
			'base_params'	=> array(),
			);

		$purl = parse_url( $url );
		$return['base_url'] = $purl['scheme'] . '://'. $purl['host'] . $purl['path'];

		if( isset($purl['query']) && $purl['query']){
			parse_str( $purl['query'], $base_params );

		/* grab our hca */
			if( isset($base_params[$this->hca]) ){
				$hca = $base_params[$this->hca];
				$hca = sanitize_text_field( $hca );

				list( $slug, $params ) = $this->get_slug_and_params( $hca );

				$return['slug'] = $slug;
				$return['raw_params'] = $params;
				$return['params'] = hc2_parse_args($params);

				foreach( array_keys($return['params']) as $k ){
					$v = $return['params'][$k];
					if( is_array($v) ){
						continue;
					}

					$pos = strpos( $v, $this->joinArray );

					if( FALSE === $pos ){
						continue;
					}
					if( 0 === $pos ){
						continue;
					}

					$v = explode( $this->joinArray, $v );
					$return['params'][$k] = $v;
				}
			}

		/* store base params */
			$return['base_params'] = $base_params;
		}

		return $return;
	}

	public function slug()
	{
		return $this->slug;
	}
	public function params()
	{
		return $this->params;
	}
	public function param( $k )
	{
		$return = array_key_exists($k, $this->params) ? $this->params[$k] : '';
		return $return;
	}

	public function __toString()
	{
		return $this->url();
	}

	public function base_url()
	{
		return $this->base_url;
	}

	public function get_slug_and_params( $string )
	{
// echo "GET SLUG AND PARAMS FROM '$string'<br>";
		$slug = array();
		$params = array();

	// trim slashes
		$string = trim($string, '/');

		$slug = $string;
		$full_slug = $slug;
		$params = array();

		if( strpos($string, ':') !== FALSE ){
			list( $slug, $params ) = explode(':', $string, 2);
			$params = explode('/', $params);
		}

		$return = array( $slug, $params );
		return $return;
	}

	public function url_param( $slug = NULL, $params = array() )
	{
		$return = '';
		$hca_param = $this->hca_param( $slug, $params );
		if( $hca_param ){
			$return .= $this->hca . '=' . $hca_param;
		}
		return $return;
	}

	public function url( $slug = '', $params = array() )
	{
		if( HC_Lib2::is_full_url($slug) ){
			return $slug;
		}

		if( null === $slug ) $slug = '';

	// trim slashes
		$slug = trim($slug, '/');

		if( $slug == '-referrer-' ){
			$slug = ( ! isset($_SERVER['HTTP_REFERER']) OR $_SERVER['HTTP_REFERER'] == '') ? '' : trim($_SERVER['HTTP_REFERER']);
			if( $slug ){
				$this->from_url( $slug );
				$parsed_slug = $this->slug();
				if( $parsed_slug ){
					$return = $this->url( $parsed_slug, $params );
					return $return;
				}
				return $slug;
			}
			$slug = '/';
		}

		$href_params = array();

		unset( $this->base_params[$this->hca] );
		$hca_param = $this->hca_param( $slug, $params );
		if( $hca_param ){
			$href_params[ $this->hca ] = $hca_param;
		}

		if( ! ($this->current_mode && isset($this->mode_urls[$this->current_mode])) ){
			$href_params = array_merge( $this->base_params, $href_params );
		}

	// mode url - used in WP for api/web type urls
		if( $this->current_mode && isset($this->mode_urls[$this->current_mode]) ){
			$base_href = $this->mode_urls[$this->current_mode];
		}
	// not mode
		else {
			$base_href = $this->base_url();
		}

		$return = $base_href;

		if( $href_params ){
			$href_params = http_build_query( $href_params );
			$glue = (strpos($return, '?') === FALSE) ? '?' : '&';
			$return .= $glue . $href_params;
		}

		if( $this->current_mode ){
			$this->current_mode = NULL;
		}

		return $return;
	}

	public function hca_param( $slug = NULL, $params = array() )
	{
		if( $slug == '/' ){
			$slug = '';
		}

		$current_slug = $this->slug();
		if( $slug == '-' ){
			$slug = $current_slug;
		}

	// pesist params within the same slug
		if( $slug == $current_slug && ($params !== NULL) ){
			$current_params = $this->params();
			$params = array_merge( $current_params, $params );
		}

		$return = $slug;

		if( $params ){
			$final_params = array();
			foreach( $params as $k => $p ){
				if( $p === NULL ){
					continue;
				}
				if( is_array($p) && (! $p) ){
					continue;
				}

				$final_params[] = $k;
				if( is_array($p) ){
					$final_p = array();
					foreach( $p as $p2 ){
						if( is_array($p2) ){
							$p2 = join($this->joinArray, $p2);
						}
						$p2 = urlencode( $p2 ); 
						$final_p[] = $p2;
					}
					$p = join($this->joinArray, $final_p);
				}
				else {
					$p = urlencode($p);
				}
				$final_params[] = $p;
			}
			$final_params = join('/', $final_params);
			if( strlen($final_params) ){
				$return .= ':' . $final_params;
			}
		}

		return $return;
	}
}