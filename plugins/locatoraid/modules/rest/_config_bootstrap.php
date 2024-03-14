<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['bootstrap'][] = function( $app )
{
	$plugin = new Rest_Plugin_LC_HC_MVC( $app );
};

if( ! class_exists('Rest_Plugin_LC_HC_MVC') )
{

class Rest_Plugin_LC_HC_MVC extends _HC_MVC
{
	private $hcapp;
	public $authCode;
	public $enabled = 1;

	public function __construct( $hcapp )
	{
		$this->hcapp = $hcapp;

		add_action( 'rest_api_init', array($this, 'routes') );

		register_setting( 'locatoraid-rest', 'locatoraid-rest_auth_code' );
		register_setting( 'locatoraid-rest', 'locatoraid-rest_enabled' );

		$this->initOption();
	}

	public function initOption()
	{
		$authOptionName = 'locatoraid-rest_auth_code';
		$v = get_option( $authOptionName, '' );

		if( ! strlen($v) ){
			// $salt .= 'abcdefghijklmnopqrstuvxyz';
			// $salt = '123456789abcdef';
			$salt = '123456789';
			$len = 12;

			$v = array();
			$i = 1;
			while ( $i <= $len ){
				$num = rand() % strlen($salt);
				$tmp = substr($salt, $num, 1);
				$v[] = $tmp;
				$i++;
			}
			shuffle( $v );
			$v = join( '', $v );

			update_option( $authOptionName, $v );
		}

		$this->authCode = $v;

		$enabledOptionName = 'locatoraid-rest_enabled';
		$v = get_option( $enabledOptionName, 1 );
		$this->enabled = $v;
	}

	public function routes()
	{
		$enabledOptionName = 'locatoraid-rest_enabled';
		$v = get_option( $enabledOptionName, 1 );
		if( ! $v ){
			return;
		}

		register_rest_route( 'locatoraid/v3', '/locations',
			array(
				array(
					'methods'	=> WP_REST_Server::READABLE,
					'callback'	=> array($this, 'locationsGet'),
					'permission_callback'	=> '__return_true',
					),
				array(
					'methods'	=> WP_REST_Server::CREATABLE,
					'callback'	=> array($this, 'locationsCreate'),
					'permission_callback'	=> array( $this, 'checkAdmin' ),
					),
				)
		);

		register_rest_route( 'locatoraid/v3', '/locations/(?P<id>\d+)',
			array(
				array(
					'methods'	=> WP_REST_Server::READABLE,
					'callback'	=> array( $this, 'locationsIdGet' ),
					'permission_callback'	=> '__return_true',
				),
				array(
					'methods'	=> WP_REST_Server::DELETABLE,
					'callback'	=> array( $this, 'locationsIdDelete' ),
					'permission_callback'	=> array( $this, 'checkAdmin' ),
				),
			)
		);
	}

	public function checkAdmin( $request )
	{
		$authCode = $request->get_header( 'X-WP-Locatoraid-AuthCode' );

		if( $authCode && ($authCode == $this->authCode) ){
			$ret = TRUE;
			return $ret;
		}

		$errors = 'not allowed';
		$ret = new WP_Error( 'error', $errors, array('status' => 500) );
		sleep( 1 );

		return $ret;
	}

	public function locationsGet( $request )
	{
		$command = $this->hcapp->make('/locations/commands/read');

		$queryParams = $request->get_query_params();
		$page = isset($queryParams['page']) ? $queryParams['page'] : 1;
		$perPage = isset($queryParams['per_page']) ? $queryParams['per_page'] : 20;
		$search = isset($queryParams['search']) ? $queryParams['search'] : NULL;

		$countArgs = array();
		$countArgs[] = 'count';
		if( $search ){
			$countArgs[] = array('search', $search);
		}

		$totalCount = $command
			->execute( $countArgs )
			;

		$limit = $perPage;
		$numberOfPages = 1;

		if( $totalCount > $perPage ){
			$pager = $this->hcapp->make('/html/pager')
				->set_total_count( $totalCount )
				->set_per_page( $perPage )
				;

			$numberOfPages = $pager->number_of_pages();

			if( $page > $numberOfPages ){
				$page = $numberOfPages;
			}
		}

		$commandArgs = array();
		$commandArgs[] = array('with', '-all-');

		if( $page && $page > 1 ){
			$commandArgs[] = array('limit', $perPage, ($page - 1) * $perPage);
		}
		else {
			$commandArgs[] = array('limit', $perPage);
		}

		if( $search ){
			$commandArgs[] = array('search', $search);
		}

		$entries = $command
			->execute( $commandArgs )
			;

		$response = new WP_REST_Response( $entries );
		$response->header( 'X-WP-Total', (int) $totalCount );
		$response->header( 'X-WP-TotalPages', (int) $numberOfPages );
		return $response;
	}

	public function locationsCreate( $request )
	{
		$values = $request->get_body_params();
		if( ! $values ){
			$body = $request->get_body();
			parse_str( $body, $values );
		}

// _print_r( $values );
// return;

		$keys = array_keys($values);
		foreach( $keys as $k ){
			if( 'product:' == substr($k, 0, strlen('product:')) ){
				$newK = str_replace( '_', ' ', $k );
				$values[ $newK ] = $values[ $k ];
				unset( $values[$k] );
			}
		}

		$cm = $this->hcapp->make('/commands/manager');

		$command = $this->hcapp->make('/locations/commands/create');
		$command
			->execute( $values )
			;

		$errors = $cm->errors( $command );
		if( $errors ){
			return new WP_Error( 'error', $errors, array('status' => 500) );
		}

		$results = $cm->results( $command );
		return $results['id'];
	}

	public function locationsIdGet( $request )
	{
		$command = $this->hcapp->make('/locations/commands/read');

		$id = $request['id'];
		$args[] = $id;
		$args[] = array('with', '-all-', 'flat');

		$model = $command
			->execute( $args )
			;

		if( ! $model ){
			return new WP_Error( 'no_location', 'Invalid Location', array('status' => 404) );
		}

		return $model;
	}

	public function locationsIdDelete( $request )
	{
		$command = $this->hcapp->make('/locations/commands/read');

		$id = $request['id'];
		$args[] = $id;

		$model = $command
			->execute( $args )
			;

		if( ! $model ){
			return new WP_Error( 'no_location', 'Invalid Location', array('status' => 404) );
		}

		$command = $this->hcapp->make('/locations/commands/delete');
		$response = $command
			->execute( $id )
			;

		if( isset($response['errors']) ){
			return new WP_Error( 'error', $response['errors'], array('status' => 500) );
		}

		return;
	}
}
}