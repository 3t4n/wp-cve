<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class LocatoraidRest extends hcWpBase6
{
	public $authCode;

	public function __construct()
	{
		register_activation_hook( __FILE__, array($this, 'onActivate') );

		$parent = $this->findParent();
		if( ! $parent ){
			$error = 'Locatorad REST requires either Locatoraid or Locatoraid Pro active';
			return;
		}

		parent::__construct(
			array($parent, 'lc'),	// app
			__FILE__,	// path,
			'',			// hc product,
			'locatoraid',	// slug
			'lctr2'		// db prefix
			);

		add_action(	'init', array($this, '_this_init') );
	}

	public function onActivate()
	{
		$parent = $this->findParent();
		if( ! $parent ){
			deactivate_plugins( __FILE__ );
			$error = 'Locatorad REST requires either Locatoraid or Locatoraid Pro active';
			die( $error );
		}
	}

	public function findParent()
	{
		$ret = NULL;

		$parents = array( 'locatoraid-pro', 'locatoraid' );
		foreach( $parents as $thisParent ){
			$confFile = dirname( __FILE__ ) . '/config/' . $thisParent . '.php';
			$className = $thisParent;
			// if( class_exists($className) && file_exists($confFile) ){
			if( file_exists($confFile) ){
				$ret = $thisParent;
				break;
			}
		}

		return $ret;
	}

	public function _this_init()
	{
		$this->hcapp_start();
		add_action( 'rest_api_init', array($this, 'routes') );

		$authOptionName = 'locatoraid-rest_auth_code';
		register_setting( 'locatoraid-rest', $authOptionName );
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
	}

	public function admin_menu()
	{
		$menuSlug = 'locatoraid-rest';
		$menuTitle = 'Locatoraid REST';

		$page = add_menu_page( 
			$menuTitle,
			$menuTitle,
			'read',
			$menuSlug,
			array($this, 'adminView'),
			'',
			31
			);
	}

	public function routes()
	{
		register_rest_route( 'locatoraid/v3', '/locations',
			array(
				array(
					'methods'	=> WP_REST_Server::READABLE,
					'callback'	=> array($this, 'locationsGet')
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
					'callback'	=> array( $this, 'locationsIdGet' )
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

	public function adminView()
	{
		$app = 'locatoraid-rest';

		if( isset($_POST[$app . '_submit']) ){
			if( isset($_POST[$app]) ){
				foreach( (array)$_POST[$app] as $key => $value ){
					$option_name = $app . '_' . $key;
					$value = sanitize_text_field( $value );
					update_option( $option_name, $value );
				}
			}
		}

		// $this->initOption();

		$current = array();
		$current['auth_code'] = get_option( $app . '_auth_code', '' );

		// spaghetti starts here
?>

<div class="wrap">
<h2>Locatoraid REST Interface</h2>

<?php if( isset($_POST[$app . '_submit']) ) : ?>
	<div id="message" class="updated fade">
		<p>
			<?php _e( 'Settings Saved', 'locatoraid' ) ?>
		</p>
	</div>
<?php endif; ?>

<form method="post" action="">
	<?php settings_fields( $app ); ?>
	<?php //do_settings_sections( $this->app ); ?>
	<table class="form-table">
		<tr valign="top">
		<th scope="row">X-WP-Locatoraid-AuthCode</th>
		<td>
			<input type="text" name="<?php echo $app; ?>[auth_code]" value="<?php echo esc_attr( $current['auth_code'] ); ?>" />
			<input name="<?php echo $app; ?>_submit" type="submit" class="button-primary" value="Save" />

			<div>
			For create, update and delete operations.
			</div>
		</td>
		<td>
		</td>
		</tr>

	</table>
</form>

<h3 style="text-decoration: underline;">List locations</h3>
<?php
$url = '/locatoraid/v3/locations';
$fullUrl = get_rest_url( NULL, $url );
?>

<p>
<strong>
GET <?php echo $url; ?>
</strong>
</p>

<p>
<strong>Arguments</strong>
</p>

<p>
page<br/>
per_page<br/>
search<br/>
</p>

<p>
<strong>Examples</strong>
</p>

<p>
<code>
GET <?php echo $fullUrl; ?>
</code>
</p>

<p>
<code>
GET <?php echo $fullUrl; ?>?page=2&per_page=100
</code>
</p>

<p>
<code>
GET <?php echo $fullUrl; ?>?search=helsinki
</code>
</p>

<h3 style="text-decoration: underline;">Retrieve a location</h3>
<?php
$url = '/locatoraid/v3/locations/&lt;id&gt;';
$fullUrl = get_rest_url( NULL, $url );
?>

<p>
<strong>
GET <?php echo $url; ?>
</strong>
</p>

<p>
<strong>Examples</strong>
</p>

<p>
<code>
GET <?php echo $fullUrl; ?>
</code>
</p>


<h3 style="text-decoration: underline;">Delete a location</h3>
<?php
$url = '/locatoraid/v3/locations/&lt;id&gt;';
$fullUrl = get_rest_url( NULL, $url );
?>

<p>
<strong>
DELETE <?php echo $url; ?>
</strong>
</p>

<p>
<strong>Headers</strong>
</p>

<p>
X-WP-Locatoraid-AuthCode [required]
</p>

<p>
<strong>Examples</strong>
</p>

<p>
<code>
DELETE <?php echo $fullUrl; ?>
</code>
</p>



<h3 style="text-decoration: underline;">Create a location</h3>
<?php
$url = '/locatoraid/v3/locations';
$fullUrl = get_rest_url( NULL, $url );
?>

<p>
<strong>
POST <?php echo $url; ?>
</strong>
</p>

<p>
<strong>Headers</strong>
</p>

<p>
X-WP-Locatoraid-AuthCode [required]
</p>

<p>
<strong>Arguments</strong>
</p>

<?php
$p = $this->hcapp->make('/locations/presenter');
$fields = $p->database_fields();
?>

<p>
<?php foreach( $fields as $f ) : ?>
	<?php echo $f; ?><br/>
<?php endforeach; ?>
</p>

<p>
<strong>Examples</strong>
</p>

<p>
<code>
POST <?php echo $fullUrl; ?>
</code>
</p>

</div>

<?php
	}
}

$locatoraidRest = new LocatoraidRest();
