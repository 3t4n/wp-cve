<?php
/**
 * Author: Vitaly Kukin
 * Date: 25.01.2017
 * Time: 12:38
 */

if ( ! function_exists( 'pr' ) ) {
	
	function pr( $any ) {
		
		print_r( "<pre><code>" );
		print_r( $any );
		print_r( "</code></pre>" );
	}
}

function dm_autoload( $className ) {

    $className = ltrim( $className, '\\' );
    $fileName  = '';

    if ( $lastNsPos = strrpos( $className, '\\' ) ) {
        $namespace = substr( $className, 0, $lastNsPos );
        $className = substr( $className, $lastNsPos + 1 );

        $fileName = str_replace( '\\', DIRECTORY_SEPARATOR, $namespace ) . DIRECTORY_SEPARATOR;
    }

    $fileName .= $className . '.php';

    $file = DM_PATH . 'includes/' . $fileName;

    if ( file_exists( $file ) ) {
        require( $file );
    }
}
spl_autoload_register( 'dm_autoload' );

function dm_admin_menu() {
	
	$foo = dm_config_menu();
	
	add_menu_page(
		$foo[ 'title' ],
		$foo[ 'title' ],
		$foo[ 'capability' ],
		$foo[ 'key' ],
		$foo[ 'action' ],
		$foo[ 'icon' ]
	);
	
	if( isset( $foo[ 'submenu' ] ) ) foreach( $foo[ 'submenu' ] as $k => $v ) {
		
		add_submenu_page(
			$foo[ 'key' ],
			$v[ 'title' ],
			$v[ 'title' ],
			$v[ 'capability' ],
			$k,
			$v[ 'action' ]
		);
	}
}
add_action( 'admin_menu', 'dm_admin_menu' );

function dm_config_menu() {
	
	return [
		'key'         => 'dpme',
		'title'       => 'Dropship Me',
		'action'      => 'dm_admin_dpme',
		'icon'        => 'dashicons-screenoptions',
		'capability'  => 'activate_plugins',
		'submenu'     => [
			'dpme'      => [
				'title'       => __( 'Import Products', 'dm' ),
				'capability'  => 'activate_plugins',
				'action'      => 'dm_admin_dpme',
			],
			'dpme_history'      => [
				'title'       => __( 'Imports History', 'dm' ),
				'capability'  => 'activate_plugins',
				'action'      => 'dm_admin_dpme_history',
			],
			'dmreviews'      => [
				'title'       => __( 'Import Reviews', 'dm' ),
				'capability'  => 'activate_plugins',
				'action'      => 'dm_admin_review',
			],
			'dmpackage'      => [
				'title'       => __( 'Get More Products', 'dm' ),
				'capability'  => 'activate_plugins',
				'action'      => 'dm_admin_package',
			],
			'dmlicense'      => [
				'title'       => __( 'Activation', 'dm' ),
				'capability'  => 'activate_plugins',
				'action'      => 'dm_admin_license',
			]
		]
	];
}

function dm_admin_dpme_history() {
	
	$_GET['mystore'] = true;
	wp_enqueue_style( 'dm-bootstrap' );
	wp_enqueue_style( 'dm-fontawesome' );
	wp_enqueue_style( 'dm-kit' );
	wp_enqueue_style( 'dm-alids-main' );
	wp_enqueue_style( 'dm-bootstrap-select' );
	wp_enqueue_style( 'dm-fancy-box3' );
	wp_enqueue_style( 'dm-flags' );
	wp_enqueue_style( 'dm-d3' );
	wp_enqueue_style( 'dpme' );
	
	wp_enqueue_script( 'dpme' );
	
	require( DM_PATH . 'admin/dpme.php' );
}
function dm_admin_dpme() {
	
	wp_enqueue_style( 'dm-bootstrap' );
	wp_enqueue_style( 'dm-fontawesome' );
	wp_enqueue_style( 'dm-kit' );
	wp_enqueue_style( 'dm-alids-main' );
	wp_enqueue_style( 'dm-bootstrap-select' );
	wp_enqueue_style( 'dm-fancy-box3' );
	wp_enqueue_style( 'dm-flags' );
	wp_enqueue_style( 'dm-d3' );
	wp_enqueue_style( 'dpme' );
	
	wp_enqueue_script( 'dpme' );
	
	require( DM_PATH . 'admin/dpme.php' );
}

function dm_admin_license() {
	
	wp_enqueue_style( 'dm-bootstrap' );
	wp_enqueue_style( 'dm-fontawesome' );
	wp_enqueue_style( 'dm-kit' );
	
	wp_enqueue_script( 'dmlicense' );
	wp_enqueue_script( 'dmpackage' );
	
	require( DM_PATH . 'admin/license.php' );
}

function dm_admin_review() {

	wp_enqueue_style( 'dm-bootstrap' );
	wp_enqueue_style( 'dm-bootstrap-select' );
	wp_enqueue_style( 'dm-fontawesome' );
	wp_enqueue_style( 'dm-kit' );
	
	wp_enqueue_script( 'dm-reviews' );
	
	require( DM_PATH . 'admin/reviews.php' );
}

function dm_admin_package() {
	
	wp_enqueue_style( 'dm-bootstrap' );
	wp_enqueue_style( 'dm-fontawesome' );
	wp_enqueue_style( 'dm-kit' );
	
	require( DM_PATH . 'admin/package.php' );
}

function dm_submenu_page() {
	
	add_submenu_page(
		'edit.php?post_type=product',
		__( 'Not Available', 'dm' ),
		__( 'Not Available', 'dm' ),
		'manage_options',
		'dm_not_available',
		'dm_not_available'
	);
}
//add_action( 'admin_menu', 'dm_submenu_page' );

function dm_not_available() {
	
	wp_enqueue_style( 'dm-bootstrap' );
	wp_enqueue_style( 'dm-fontawesome' );
	wp_enqueue_style( 'dm-kit' );
	wp_enqueue_style( 'dm-alids-main' );
	wp_enqueue_style( 'dm-bootstrap-select' );
	wp_enqueue_style( 'dm-fancy-box3' );
	wp_enqueue_style( 'dm-flags' );
	wp_enqueue_style( 'dpme' );
	
	wp_enqueue_script( 'dpme' );
	
	require( DM_PATH . 'admin/notavailable.php' );
}

function dm_css_filter() {
	
	$foo = [
		'dm-bootstrap'        => DM_URL . '/src/css/bootstrap4/bootstrap.min.css',
		'dm-bootstrap-select' => DM_URL . '/src/css/bootstrap4/bootstrap-select.min.css',
		'dm-kit'              => DM_URL . '/src/css/bootstrap4/dm-kit.min.css',
		'dm-flags'            => DM_URL . '/src/css/bootstrap4/flags.css',
		'dm-d3'               => DM_URL . '/src/css/chart/d3.min.css',
		'dm-alids-main'       => DM_URL . '/src/css/alids-main.min.css',
		'dpme'                => DM_URL . '/src/css/dpme.min.css',
		'dm-product'          => DM_URL . '/src/css/product-post.min.css',
		'dm-fontawesome'      => DM_URL . '/src/css/icons/fontawesome/style.css',
		'dm-fancy-box3'       => 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css',
	];
	
	foreach( $foo as $key => $val ) {
		
		wp_register_style( $key, $val, DM_VERSION );
	}
}
add_action( 'admin_init', 'dm_css_filter' );

function dm_js_filter() {
	
	$args = [
		'dm-ajaxQueue' => [
			'url'    => DM_URL . '/src/js/global/jquery.ajaxQueue.min.js',
			'parent' => [ 'jquery' ],
			'ver'    => '0.1.2'
		],
		'dm-handlebars' => [
			'url'    => DM_URL . '/src/js/handlebars/handlebars.min.js',
			'parent' => [ 'jquery' ],
			'ver'    => '4.0.5'
		],
		'dm-switchery' => [
			'url'    => DM_URL . '/src/js/global/switchery.min.js',
			'parent' => [ 'jquery' ],
			'ver'    => '0.8.2'
		],
		'dm-uniform' => [
			'url'    => DM_URL . '/src/js/global/uniform.min.js',
			'parent' => [ 'jquery' ],
			'ver'    => '4.0'
		],
		'dm-touchSwipe' => [
			'url'    => DM_URL . '/src/js/global/jquery.touchSwipe.min.js',
			'parent' => [ 'jquery' ],
			'ver'    => DM_VERSION
		],
		'dm-jqpagination' => [
			'url'    => DM_URL . '/src/js/global/jquery.jqpagination.min.js',
			'parent' => [ 'jquery' ],
			'ver'    => '1.4.1'
		],
		'dm-clipboard' => [ //хз надо ли
			'url'    => DM_URL . '/src/js/global/clipboard.min.js',
			'parent' => [ 'jquery' ],
			'ver'    => '1.6.1'
		],
		'dm-popper' => [
			'url'    => 'https://unpkg.com/popper.js/dist/umd/popper.min.js',
			'parent' => false,
			'ver'    => '1.0.0'
		],
		'dm-bootstrap' => [
			'url'    => DM_URL . '/src/js/bootstrap4/bootstrap.min.js',
			'parent' => [ 'jquery', 'dm-popper' ],
			'ver'    => '3.3.7'
		],
		'dm-bootstrap-select' => [
			'url'    => DM_URL . '/src/js/bootstrap4/bootstrap-select.min.js',
			'parent' => [ 'jquery', 'dm-bootstrap' ],
			'ver'    => '1.13.2'
		],
		'dm-fancy-box3' => [
			'url'    => 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js',
			'parent' => [ 'jquery' ],
			'ver'    => '3.3.5'
		],
		'dm-main' => [
			'url'    => DM_URL . '/src/js/admin/main.min.js',
			'parent' => [
				'dm-handlebars',
				'dm-ajaxQueue',
				'dm-switchery',
				'dm-uniform',
				'dm-bootstrap-select',
				'dm-jqpagination',
				'dm-clipboard'
			],
			'ver'    => DM_VERSION
		],
		'dm-d3' => [
			'url'    => DM_URL . '/src/js/chart/d3.js',
			'parent' => [ 'jquery' ],
			'ver'    => DM_VERSION
		],
		'dm-d3_tooltip' => [
			'url'    => DM_URL . '/src/js/chart/d3_tooltip.min.js',
			'parent' => [ 'jquery' ],
			'ver'    => DM_VERSION
		],
		'dm-chart' => [
			'url'    => DM_URL . '/src/js/chart/chart.min.js',
			'parent' => [ 'dm-d3', 'dm-d3_tooltip' ],
			'ver'    => DM_VERSION
		],
		'dm-availableExtensions' => [
			'url'    => DM_URL . '/src/js/global/availableExtensions.min.js',
			'parent' => [ 'jquery' ],
			'ver'    => DM_VERSION
		],
		'dm-aliExtension' => [
			'url'    => DM_URL . '/src/js/global/aliExtension.min.js',
			'parent' => [ 'jquery', 'dm-availableExtensions' ],
			'ver'    => DM_VERSION
		],
		'dpme' => [
			'url'    => DM_URL . '/src/js/dpme.min.js',
			'parent' => [ 'dm-main', 'dm-touchSwipe', 'dm-fancy-box3', 'dm-chart', 'dm-aliExtension' ],
			'ver'    => DM_VERSION
		],
		'dm-reviews' => [
			'url'    => DM_URL . '/src/js/review.min.js',
			'parent' => [ 'dm-main' ],
			'ver'    => DM_VERSION
		],
		'productPost' => [
			'url'    => DM_URL . '/src/js/admin/product-post.min.js',
			'parent' => [ 'dm-main' ],
			'ver'    => DM_VERSION
		],
		'dmlicense' => [
			'url'    => DM_URL . '/src/js/license.min.js',
			'parent' => [ 'dm-main' ],
			'ver'    => DM_VERSION
		],
		'dmpackage' => [
			'url'    => DM_URL . '/src/js/package.min.js',
			'parent' => [ 'dm-main' ],
			'ver'    => DM_VERSION
		],
	];
	
	wp_deregister_script( 'ellk-aliExpansion' );
	foreach( $args as $key => $val ) {
		
		wp_register_script(
			$key,
			$val[ 'url' ],
			$val[ 'parent' ],
			$val[ 'ver' ],
			true
		);
	}
}
add_action( 'admin_print_scripts', 'dm_js_filter' );

function dm_alidropship_api() {
	
	$obj      = new \dm\dmAliDropshipApi();
	$response = $obj->actions( $_POST );
	
	echo json_encode( $response );
	die();
}
add_action( 'wp_ajax_dm_alidropship_api', 'dm_alidropship_api' );

function dm_action_license() {
	
	$obj      = new \dm\dmHandlers();
	$response = $obj->actions( $_POST );
	
	echo json_encode( $response );
	die();
}
add_action( 'wp_ajax_dm_action_license', 'dm_action_license' );

function dm_action_package() {
	
	$obj      = new \dm\dmHandlers();
	$response = $obj->actions( $_POST );
	
	echo json_encode( $response );
	die();
}
add_action( 'wp_ajax_dm_action_package', 'dm_action_package' );

function dm_ajax_action_reviews() {
	
	$obj      = new \dm\dmReviews();
	$response = $obj->actions( $_POST );
	
	echo json_encode( $response );
	die();
}
add_action('wp_ajax_dm_action_reviews', 'dm_ajax_action_reviews');

function dm_pays_handler_notify() {

	if ( ! isset( $_GET[ 'dm_pays_notify' ] ) ) {
		return false;
	}

	$key    = 'key';
	$prefix = 'dm';

	$handlers_p = $_GET[ $prefix . '_' . $key ];
	$note       = get_option( $prefix . '-license' );
	$uri        = get_bloginfo( 'url' ) . '/';
	$vendor     = md5( md5( $note . $uri ) . md5( $uri ) );

	if ( isset( $handlers_p ) ) {
		if ( md5( $_GET[ 'dm_pays_notify' ] . $handlers_p ) == $vendor ) {
			update_option( '_random_hash_dm', $vendor );
		}
	}
	die();
}
add_action( 'init', 'dm_pays_handler_notify', 1 );

add_action( 'admin_init', function() {
	
	if( isset( $_GET[ 'page' ] ) && in_array( $_GET[ 'page' ], [ 'dpme', 'dmpackage', 'dmreviews' ] ) )
		dm_manage_posts();
} );

function dm_check_hash() {
	
	$site  = get_bloginfo('url');
	
	try{
		$key    = get_option( 'dm-license' );
		$uri    = $site . '/';
		$vendor = get_option( '_random_hash_dm' );
		
		if( $vendor && md5( md5( $key . $uri ) . md5( $uri ) ) === $vendor )
			return true;
	}
	catch( Exception $e ){}
	
	return false;
}

function dm_manage_posts() {
	
	if( ! dm_check_hash() )
		dm_pays_init_handler();
}

function dm_pays_init_handler() {

	$redirect = false;

	if( is_admin() && isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] != 'dmlicense' ) {

		$slug = $_GET[ 'page' ];

		foreach( [ 'dpme', 'dmlicense', 'dmpackage', 'dmreviews' ] as $key => $val ) {

			if( isset( $val[ 'submenu' ] ) ) {
				foreach( $val[ 'submenu' ] as $k => $v )
					if( $k == $slug ) {
						$redirect = true;
					}
			} elseif( $key == $slug ) {
				$redirect = true;
			}
		}
	}

	if( $redirect ) {
		wp_redirect( admin_url( 'admin.php?page=dmlicense' ) );

		exit;
	}
}

function dm_get_domain() {
	
	$url  = get_bloginfo( 'url' );
	$path = parse_url( $url );
	
	$host = isset( $path[ 'host' ] ) ? $path[ 'host' ] : $path[ 'path' ];
	$pos  = strpos( $host, '/' );
	if( $pos ) {
		$host = substr( $host, 0, $pos );
	}
	
	$m = preg_match( "/[^\.\/]+\.[^\.\/]+$/", $host, $matches );
	
	if( $m === FALSE ) {
		return false;
	}
	
	$domain = $matches[ 0 ];
	$main_domain = explode( '.', $domain );
	
	if( strlen( $main_domain[0] ) <= 3 ) {
		
		if( substr_count( $host, '.' ) == 2 ) {
			$domain = $host;
		} else {
			$d = explode( '.', $host );
			$d = array_reverse( $d );
			
			$domain = "{$d[2]}.{$d[1]}.{$d[0]}";
		}
	}
	
	return $domain;
}

/**
 * Parse any str to float
 *
 * @param $value
 *
 * @return string
 */
function dm_floatvalue( $value ) {

	$value = html_entity_decode( $value, ENT_QUOTES, "UTF-8" );

	$value = preg_replace('/[^0-9,.]/', '', $value, -1 );

	if( preg_match('/(\d+\,\d+)+\.\d+/', $value ) ) {
		$value = str_replace( ',', '', $value );
	}

	$value = str_replace( ',', '.', $value );

	return number_format( floatval( $value ), 2, '.', '' );
}

function dm_integer( $int ) {

	return preg_replace( '/\D/', '', $int );
}

function dm_prepare_var_slug( $str ) {
	
	return substr( md5( $str ), 0, 22 );
}

/**
 * Validate is URL
 *
 * @param $url
 *
 * @return int
 */
function dm_is_url( $url ) {
	
	return (bool) preg_match( '|(\/\/)(www\.)?(.)*[\.](.)*$|iu', $url );
}

/**
 * Parse 2 arrays
 * @param array $defaults
 * @param array $args
 *
 * @return array
 */
function dm_parse_args( $defaults, $args ) {
	
	$foo = [];
	
	foreach( $defaults as $key => $val )
		$foo[ $key ] = isset( $args[ $key ] ) && $args[ $key ] ? $args[ $key ] : $val;
	
	return $foo;
}

function dm_prepare_options( $data = [] ) {
	
	$foo = [];
	
	foreach( $data as $key => $val )
		$foo[] = [
			'value' => $key,
			'title' => $val
		];
	
	return $foo;
}

function dm_do_show_notify() {
	
	echo '<div id="dm-notify"></div>';
}
add_action( 'admin_footer', 'dm_do_show_notify' );

function dm_init_product() {
	
	if( DM_PLUGIN == 'woocommerce' )
		add_action( 'delete_post', 'dm_delete_product', 10 );
}
add_action( 'admin_init', 'dm_init_product' );

function dm_delete_product( $pid ) {
	
	global $wpdb;
	
	$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}adsw_ali_meta WHERE post_id = %d", $pid ) );
}

function dm_generate_taxonomy_options( $tax_slug, $parent = '', $level = 0 ) {
	
	$args = [
		'hide_empty' => false,
		'get'        => 'all'
	];
	
	if( ! is_null( $parent ) )
		$args[ 'parent' ] = $parent;
	
	$terms 	= get_terms( $tax_slug, $args );
	$tab	= '';
	
	for( $i = 0; $i < $level; $i++ )
		$tab .= '--';
	
	$foo = [];
	
	foreach ( $terms as $term ) {
		
		$foo[] = [
			'value' => $term->slug,
			'title' => $tab . ' ' .  htmlspecialchars_decode( $term->name ) . ' (' . $term->count . ')'
		];
		
		$foo = array_merge( $foo, dm_generate_taxonomy_options( $tax_slug, $term->term_id, $level+1 ) );
	}
	
	return $foo;
}

function dm_list_countries() {
	
		$foo = [
		'AF'  => __('Afghanistan', 'dm'),
		'ALA' => __('Aland Islands', 'dm'),
		'AL'  => __('Albania', 'dm'),
		'GBA' => __('Alderney', 'dm'),
		'DZ'  => __('Algeria', 'dm'),
		'AS'  => __('American Samoa', 'dm'),
		'AD' => __('Andorra', 'dm'),
		'AO' => __('Angola', 'dm'),
		'AI' => __('Anguilla', 'dm'),
		'AG' => __('Antigua and Barbuda', 'dm'),
		'AR' => __('Argentina', 'dm'),
		'AM' => __('Armenia', 'dm'),
		'AW' => __('Aruba', 'dm'),
		'ASC' => __('Ascension Island', 'dm'),
		'AU' => __('Australia', 'dm'),
		'AT' => __('Austria', 'dm'),
		'AZ' => __('Azerbaijan', 'dm'),
		'BS' => __('Bahamas', 'dm'),
		'BH' => __('Bahrain', 'dm'),
		'BD' => __('Bangladesh', 'dm'),
		'BB' => __('Barbados', 'dm'),
		'BY' => __('Belarus', 'dm'),
		'BE' => __('Belgium', 'dm'),
		'BZ' => __('Belize', 'dm'),
		'BJ' => __('Benin', 'dm'),
		'BM' => __('Bermuda', 'dm'),
		'BT' => __('Bhutan', 'dm'),
		'BO' => __('Bolivia', 'dm'),
		'BA' => __('Bosnia and Herzegovina', 'dm'),
		'BW' => __('Botswana', 'dm'),
		'BR' => __('Brazil', 'dm'),
		'BN' => __('Brunei Darussalam', 'dm'),
		'BG' => __('Bulgaria', 'dm'),
		'BF' => __('Burkina Faso', 'dm'),
		'BI' => __('Burundi', 'dm'),
		'KH' => __('Cambodia', 'dm'),
		'CM' => __('Cameroon', 'dm'),
		'CA' => __('Canada', 'dm'),
		'CV' => __('Cape Verde', 'dm'),
		'KY' => __('Cayman Islands', 'dm'),
		'CF' => __('Central African Republic', 'dm'),
		'TD' => __('Chad', 'dm'),
		'CL' => __('Chile', 'dm'),
		'CN' => __('China', 'dm'),
		'CX' => __('Christmas Island', 'dm'),
		'CC' => __('Cocos (Keeling) Islands', 'dm'),
		'CO' => __('Colombia', 'dm'),
		'KM' => __('Comoros', 'dm'),
		'ZR' => __('Congo, The Democratic Republic Of The', 'dm'),
		'CG' => __('Congo, The Republic of Congo', 'dm'),
		'CK' => __('Cook Islands', 'dm'),
		'CR' => __('Costa Rica', 'dm'),
		'CI' => __('Cote D\'Ivoire', 'dm'),
		'HR' => __('Croatia (local name: Hrvatska)', 'dm'),
		'CU' => __('Cuba', 'dm'),
		'CY' => __('Cyprus', 'dm'),
		'CZ' => __('Czech Republic', 'dm'),
		'DK' => __('Denmark', 'dm'),
		'DJ' => __('Djibouti', 'dm'),
		'DM' => __('Dominica', 'dm'),
		'DO' => __('Dominican Republic', 'dm'),
		'TP' => __('East Timor', 'dm'),
		'EC' => __('Ecuador', 'dm'),
		'EG' => __('Egypt', 'dm'),
		'SV' => __('El Salvador', 'dm'),
		'GQ' => __('Equatorial Guinea', 'dm'),
		'ER' => __('Eritrea', 'dm'),
		'EE' => __('Estonia', 'dm'),
		'ET' => __('Ethiopia', 'dm'),
		'FK' => __('Falkland Islands (Malvinas)', 'dm'),
		'FO' => __('Faroe Islands', 'dm'),
		'FJ' => __('Fiji', 'dm'),
		'FI' => __('Finland', 'dm'),
		'FR' => __('France', 'dm'),
		'GF' => __('French Guiana', 'dm'),
		'PF' => __('French Polynesia', 'dm'),
		'GA' => __('Gabon', 'dm'),
		'GM' => __('Gambia', 'dm'),
		'GE' => __('Georgia', 'dm'),
		'DE' => __('Germany', 'dm'),
		'GH' => __('Ghana', 'dm'),
		'GI' => __('Gibraltar', 'dm'),
		'GR' => __('Greece', 'dm'),
		'GL' => __('Greenland', 'dm'),
		'GD' => __('Grenada', 'dm'),
		'GP' => __('Guadeloupe', 'dm'),
		'GU' => __('Guam', 'dm'),
		'GT' => __('Guatemala', 'dm'),
		'GGY' => __('Guernsey', 'dm'),
		'GN' => __('Guinea', 'dm'),
		'GW' => __('Guinea-Bissau', 'dm'),
		'GY' => __('Guyana', 'dm'),
		'HT' => __('Haiti', 'dm'),
		'HN' => __('Honduras', 'dm'),
		'HK' => __('Hong Kong', 'dm'),
		'HU' => __('Hungary', 'dm'),
		'IS' => __('Iceland', 'dm'),
		'IN' => __('India', 'dm'),
		'ID' => __('Indonesia', 'dm'),
		'IR' => __('Iran (Islamic Republic of)', 'dm'),
		'IQ' => __('Iraq', 'dm'),
		'IE' => __('Ireland', 'dm'),
		'IL' => __('Israel', 'dm'),
		'IT' => __('Italy', 'dm'),
		'JM' => __('Jamaica', 'dm'),
		'JP' => __('Japan', 'dm'),
		'JEY' => __('Jersey', 'dm'),
		'JO' => __('Jordan', 'dm'),
		'KZ' => __('Kazakhstan', 'dm'),
		'KE' => __('Kenya', 'dm'),
		'KI' => __('Kiribati', 'dm'),
		'KW' => __('Kuwait', 'dm'),
		'KG' => __('Kyrgyzstan', 'dm'),
		'KS' => __('Kosovo', 'dm'),
		'LA' => __('Lao People\'s Democratic Republic', 'dm'),
		'LV' => __('Latvia', 'dm'),
		'LB' => __('Lebanon', 'dm'),
		'LS' => __('Lesotho', 'dm'),
		'LR' => __('Liberia', 'dm'),
		'LY' => __('Libya', 'dm'),
		'LI' => __('Liechtenstein', 'dm'),
		'LT' => __('Lithuania', 'dm'),
		'LU' => __('Luxembourg', 'dm'),
		'MO' => __('Macau', 'dm'),
		'MK' => __('Macedonia', 'dm'),
		'MG' => __('Madagascar', 'dm'),
		'MW' => __('Malawi', 'dm'),
		'MY' => __('Malaysia', 'dm'),
		'MV' => __('Maldives', 'dm'),
		'ML' => __('Mali', 'dm'),
		'MT' => __('Malta', 'dm'),
		'MH' => __('Marshall Islands', 'dm'),
		'MQ' => __('Martinique', 'dm'),
		'MR' => __('Mauritania', 'dm'),
		'MU' => __('Mauritius', 'dm'),
		'YT' => __('Mayotte', 'dm'),
		'MX' => __('Mexico', 'dm'),
		'FM' => __('Micronesia', 'dm'),
		'MD' => __('Moldova', 'dm'),
		'MC' => __('Monaco', 'dm'),
		'MN' => __('Mongolia', 'dm'),
		'MNE' => __('Montenegro', 'dm'),
		'MS' => __('Montserrat', 'dm'),
		'MA' => __('Morocco', 'dm'),
		'MZ' => __('Mozambique', 'dm'),
		'MM' => __('Myanmar', 'dm'),
		'NA' => __('Namibia', 'dm'),
		'NR' => __('Nauru', 'dm'),
		'NP' => __('Nepal', 'dm'),
		'NL' => __('Netherlands', 'dm'),
		'AN' => __('Netherlands Antilles', 'dm'),
		'NC' => __('New Caledonia', 'dm'),
		'NZ' => __('New Zealand', 'dm'),
		'NI' => __('Nicaragua', 'dm'),
		'NE' => __('Niger', 'dm'),
		'NG' => __('Nigeria', 'dm'),
		'NU' => __('Niue', 'dm'),
		'NF' => __('Norfolk Island', 'dm'),
		'KP' => __('North Korea', 'dm'),
		'MP' => __('Northern Mariana Islands', 'dm'),
		'NO' => __('Norway', 'dm'),
		'OM' => __('Oman', 'dm'),
		'PK' => __('Pakistan', 'dm'),
		'PW' => __('Palau', 'dm'),
		'PS' => __('Palestine', 'dm'),
		'PA' => __('Panama', 'dm'),
		'PG' => __('Papua New Guinea', 'dm'),
		'PY' => __('Paraguay', 'dm'),
		'PE' => __('Peru', 'dm'),
		'PH' => __('Philippines', 'dm'),
		'PL' => __('Poland', 'dm'),
		'PT' => __('Portugal', 'dm'),
		'PR' => __('Puerto Rico', 'dm'),
		'QA' => __('Qatar', 'dm'),
		'RE' => __('Reunion', 'dm'),
		'RO' => __('Romania', 'dm'),
		'RU' => __('Russian Federation', 'dm'),
		'RW' => __('Rwanda', 'dm'),
		'BLM' => __('Saint Barthelemy', 'dm'),
		'KN' => __('Saint Kitts and Nevis', 'dm'),
		'LC' => __('Saint Lucia', 'dm'),
		'MAF' => __('Saint Martin', 'dm'),
		'VC' => __('Saint Vincent and the Grenadines', 'dm'),
		'WS' => __('Samoa', 'dm'),
		'SM' => __('San Marino', 'dm'),
		'ST' => __('Sao Tome and Principe', 'dm'),
		'SA' => __('Saudi Arabia', 'dm'),
		'SCT' => __('Scotland', 'dm'),
		'SN' => __('Senegal', 'dm'),
		'SRB' => __('Serbia', 'dm'),
		'SC' => __('Seychelles', 'dm'),
		'SL' => __('Sierra Leone', 'dm'),
		'SG' => __('Singapore', 'dm'),
		'SK' => __('Slovakia (Slovak Republic)', 'dm'),
		'SI' => __('Slovenia', 'dm'),
		'SB' => __('Solomon Islands', 'dm'),
		'SO' => __('Somalia', 'dm'),
		'ZA' => __('South Africa', 'dm'),
		'SGS' => __('South Georgia and the South Sandwich Islands', 'dm'),
		'KR' => __('South Korea', 'dm'),
		'SS' => __('South Sudan', 'dm'),
		'ES' => __('Spain', 'dm'),
		'LK' => __('Sri Lanka', 'dm'),
		'PM' => __('St. Pierre and Miquelon', 'dm'),
		'SD' => __('Sudan', 'dm'),
		'SR' => __('Suriname', 'dm'),
		'SZ' => __('Swaziland', 'dm'),
		'SE' => __('Sweden', 'dm'),
		'CH' => __('Switzerland', 'dm'),
		'SY' => __('Syrian Arab Republic', 'dm'),
		'TW' => __('Taiwan', 'dm'),
		'TJ' => __('Tajikistan', 'dm'),
		'TZ' => __('Tanzania', 'dm'),
		'TH' => __('Thailand', 'dm'),
		'TLS' => __('Timor-Leste', 'dm'),
		'TG' => __('Togo', 'dm'),
		'TO' => __('Tonga', 'dm'),
		'TT' => __('Trinidad and Tobago', 'dm'),
		'TN' => __('Tunisia', 'dm'),
		'TR' => __('Turkey', 'dm'),
		'TM' => __('Turkmenistan', 'dm'),
		'TC' => __('Turks and Caicos Islands', 'dm'),
		'TV' => __('Tuvalu', 'dm'),
		'UG' => __('Uganda', 'dm'),
		'UA' => __('Ukraine', 'dm'),
		'AE' => __('United Arab Emirates', 'dm'),
		'UK' => __('United Kingdom', 'dm'),
		'US' => __('United States', 'dm'),
		'UY' => __('Uruguay', 'dm'),
		'UZ' => __('Uzbekistan', 'dm'),
		'VU' => __('Vanuatu', 'dm'),
		'VA' => __('Vatican City State (Holy See)', 'dm'),
		'VE' => __('Venezuela', 'dm'),
		'VN' => __('Vietnam', 'dm'),
		'VG' => __('Virgin Islands (British)', 'dm'),
		'VI' => __('Virgin Islands (U.S.)', 'dm'),
		'WF' => __('Wallis And Futuna Islands', 'dm'),
		'YE' => __('Yemen', 'dm'),
		'ZM' => __('Zambia', 'dm'),
		'EAZ' => __('Zanzibar', 'dm'),
		'ZW' => __('Zimbabwe', 'dm'),
        'BV' => __( 'Bouvet Island', 'dm' ),
        'IO' => __( 'British Indian Ocean Territory', 'dm' ),
        'DM' => __( 'Dominica', 'dm' ),
        'PN' => __( 'Pitcairn Islands', 'dm' ),
        'SH' => __( 'Saint Helena', 'dm' ),
        'SJ' => __( 'Svalbard and Jan Mayen', 'dm' ),
        'TK' => __( 'Tokelau', 'dm' ),
        'UM' => __( 'United States Minor Outlying Islands', 'dm' ),
        'EH' => __( 'Western Sahara', 'dm' ),
        'FX' => __( 'France, Metropolitan', 'dm' ),
        'TF' => __( 'French Southern Territories', 'dm' ),
        'HM' => __( 'Heard Island and McDonald Islands', 'dm' ),
        'IM' => __( 'Isle of Man', 'dm' ),
    ];

    asort( $foo );

	return $foo;
}

function dm_list_company() {
	
	return [
		"9999" => "Any",
		"14" => "ePacket",
		"64" => "139 ECONOMIC Package",
		"8"  => "4PX Singapore Post OM Pro",
		"4"  => "Aliexpress Direct",
		"19" => "AliExpress Premium Shipping",
		"1"  => "AliExpress Standard Shipping",
		"13" => "AliExpress Saver Shipping",
		"5"  => "Aramex",
		"56" => "AUSPOST",
		"16" => "Cainiao Super Economy",
		"31" => "China Post Air Parcel",
		"7"  => "China Post Ordinary Small Packet Plus",
		"11" => "China Post Registered Air Mail",
		"58" => "Correios",
		"18" => "Correos Economy",
		"40" => "CORREOS PAQ 72",
		"2"  => "DHL",
		"48" => "DHL e-commerce",
		"28" => "DPEX",
		"22" => "e-EMS",
		"3"  => "EMS",
		"63" => "Entrega Local",
		"44" => "eTotal",
		"21" => "Fedex IE",
		"20" => "Fedex IP",
		"32" => "GATI",
		"42" => "HongKong Post Air Mail",
		"55" => "IML Express",
		"34" => "J-NET",
		"43" => "POS Malaysia",
		"33" => "Posti Finland",
		"46" => "PostNL",
		"41" => "RETS-EXPRESS",
		"38" => "Royal Mail Economy",
		"30" => "Russia Express-SPSR",
		"17" => "Russian Air",
		"59" => "Russian Post",
		"15" => "SF Economic Air Mail",
		"35" => "SF eParcel",
		"26" => "SF Express",
		"10" => "Singapore Post",
		"29" => "Special Line-YW",
		"9"  => "SunYou Economic Air Mail",
		"54" => "Sweden Post",
		"53" => "Swiss Post",
		"24" => "TNT",
		"45" => "Turkey Post",
		"60" => "Ukrposhta",
		"57" => "UPS",
		"25" => "UPS Expedited",
		"27" => "UPS Express Saver",
		"47" => "USPS",
		"12" => "Yanwen Economic Air Mail"
	];
}

/**
 * List of Currency
 * @return array
 */
function dm_list_currency() {
	
	return [
		'AED' => [
			'flag'   => 'AE',
			'symbol' => 'AED ',
			'pos'    => 'before',
			'title'  => __( 'United Arab Emirates Dirham (AED)', 'dm' )
		],
		'ALL' => [
			'flag'   => 'AL',
			'symbol' => 'Lek',
			'pos'    => 'before',
			'title'  => __( 'Albanian Lek (ALL)', 'dm' )
		],
		'AUD' => [
			'flag'   => 'AU',
			'symbol' => 'AUD ',
			'pos'    => 'before',
			'title'  => __( 'Australian Dollar (AUD)', 'dm' )
		],
		'BDT' => [
			'flag'   => 'BD',
			'symbol' => ' ৳',
			'pos'    => 'after',
			'title'  => __( 'Bangladeshi Taka (BDT)', 'dm' )
		],
		'BRL' => [
			'flag'   => 'BR',
			'symbol' => 'R$ ',
			'pos'    => 'before',
			'title'  => __( 'Brazilian Real (R$)', 'dm' )
		],
		'CAD' => [
			'flag'   => 'CA',
			'symbol' => 'CA$ ',
			'pos'    => 'before',
			'title'  => __( 'Canadian Dollar (CA$)', 'dm' )
		],
		'CHF' => [
			'flag'   => 'CH',
			'symbol' => 'CHF ',
			'pos'    => 'before',
			'title'  => __( 'Swiss Franc (CHF)', 'dm' )
		],
		'CLP' => [
			'flag'   => 'CL',
			'symbol' => 'CLP $ ',
			'pos'    => 'before',
			'title'  => __( 'Chilean Peso (CLP $)', 'dm' )
		],
		'CNY' => [
			'flag'   => 'CN',
			'symbol' => ' ¥',
			'pos'    => 'after',
			'title'  => __( 'Chinese Yuan (CN¥)', 'dm' )
		],
		'COP' => [
			'flag'   => 'CO',
			'symbol' => ' COP',
			'pos'    => 'after',
			'title'  => __( 'Colombian Peso (COP)', 'dm' )
		],
		'CZK' => [
			'flag'   => 'CZ',
			'symbol' => ' Kč',
			'pos'    => 'after',
			'title'  => __( 'Czech Republic Koruna (CZK)', 'dm' )
		],
		'DZD' => [
			'flag'   => 'DZ',
			'symbol' => 'DZD ',
			'pos'    => 'before',
			'title'  => __( 'Algerian Dinars (DZD)', 'dm' )
		],
		'EUR' => [
			'flag'   => 'EU',
			'symbol' => ' €',
			'pos'    => 'after',
			'title'  => __( 'Euro', 'dm' )
		],
		'GBP' => [
			'flag'   => 'GB',
			'symbol' => '£ ',
			'pos'    => 'before',
			'title'  => __( 'British Pound Sterling (£)', 'dm' )
		],
		'HRK' => [
			'flag'   => 'HR',
			'symbol' => ' HRK',
			'pos'    => 'after',
			'title'  => __( 'Croatian Kuna (HRK)', 'dm' )
		],
		'HUF' => [
			'flag'   => 'HU',
			'symbol' => ' HUF',
			'pos'    => 'after',
			'title'  => __( 'Hungarian Forint (HUF)', 'dm' )
		],
		'IDR' => [
			'flag'   => 'ID',
			'symbol' => 'Rp ',
			'pos'    => 'before',
			'title'  => __( 'Indonesian Rupiah (IDR)', 'dm' )
		],
		'ILS' => [
			'flag'   => 'IL',
			'symbol' => 'ILS ',
			'pos'    => 'before',
			'title'  => __( 'Israeli Shekel (ILS)', 'dm' )
		],
		'INR' => [
			'flag'   => 'IN',
			'symbol' => '₹ ',
			'pos'    => 'before',
			'title'  => __( 'Indian Rupee (Rs.)', 'dm' )
		],
		'KRW' => [
			'flag'   => 'KR',
			'symbol' => '₩ ',
			'pos'    => 'before',
			'title'  => __( 'South Korean Won (₩)', 'dm' )
		],
		'MAD' => [
			'flag'   => 'MA',
			'symbol' => 'MAD ',
			'pos'    => 'before',
			'title'  => __( 'Moroccan dirham (MAD)', 'dm' )
		],
		'MYR' => [
			'flag'   => 'MY',
			'symbol' => 'RM ',
			'pos'    => 'before',
			'title'  => __( 'Malaysian Ringgit (MYR)', 'dm' )
		],
		'NGN' => [
			'flag'   => 'NG',
			'symbol' => '₦ ',
			'pos'    => 'before',
			'title'  => __( 'Nigerian Naira (₦)', 'dm' )
		],
		'NOK' => [
			'flag'   => 'NO',
			'symbol' => ' kr',
			'pos'    => 'after',
			'title'  => __( 'Norwegian Krone (NOK)', 'dm' )
		],
		'NZD' => [
			'flag'   => 'NZ',
			'symbol' => 'NZ$ ',
			'pos'    => 'before',
			'title'  => __( 'New Zealand Dollar (NZ$)', 'dm' )
		],
		'OMR' => [
			'flag'   => 'OM',
			'symbol' => ' OMR',
			'pos'    => 'after',
			'title'  => __( 'Omani Rial (OMR)', 'dm' )
		],
		'PHP' => [
			'flag'   => 'PH',
			'symbol' => '₱ ',
			'pos'    => 'before',
			'title'  => __( 'Philippine Peso (PHP)', 'dm' )
		],
		'PKR' => [
			'flag'   => 'PK',
			'symbol' => '₨.',
			'pos'    => 'before',
			'title'  => __( 'Pakistan Rupee (PKR)', 'dm' )
		],
		'PLN' => [
			'flag'   => 'PL',
			'symbol' => ' zł',
			'pos'    => 'after',
			'title'  => __( 'Polish Zloty (PLN)', 'dm' )
		],
		'RUB' => [
			'flag'   => 'RU',
			'symbol' => ' руб.',
			'pos'    => 'after',
			'title'  => __( 'Russian Ruble (RUB)', 'dm' )
		],
		'SAR' => [
			'flag'   => 'SA',
			'symbol' => ' SR',
			'pos'    => 'after',
			'title'  => __( 'Saudi Riyal (SAR)', 'dm' )
		],
		'SEK' => [
			'flag'   => 'SE',
			'symbol' => ' SEK',
			'pos'    => 'after',
			'title'  => __( 'Swedish Krona (SEK)', 'dm' )
		],
		'SGD' => [
			'flag'   => 'SG',
			'symbol' => ' SGD',
			'pos'    => 'after',
			'title'  => __( 'Singapore Dollar (SGD)', 'dm' )
		],
		'LKR' => [
			'flag'   => 'LK',
			'symbol' => ' LKR',
			'pos'    => 'after',
			'title'  => __( 'Sri Lankan Rupee (LKR)', 'dm' )
		],
		'THB' => [
			'flag'   => 'TH',
			'symbol' => '฿ ',
			'pos'    => 'before',
			'title'  => __( 'Thai Baht (฿)', 'dm' )
		],
		'TND' => [
			'flag'   => 'TN',
			'symbol' => 'TND ',
			'pos'    => 'before',
			'title'  => __( 'Tunisian Dinars (TND)', 'dm' )
		],
		'QAR' => [
			'flag'   => 'QA',
			'symbol' => 'ر.ق ',
			'pos'    => 'before',
			'title'  => __( 'Qatari Riyals (QAR)', 'dm' )
		],
		'UAH' => [
			'flag'   => 'UA',
			'symbol' => ' грн.',
			'pos'    => 'after',
			'title'  => __( 'Ukrainian Hryvnia (грн.)', 'dm' )
		],
		'USD' => [
			'flag'   => 'US',
			'symbol' => 'US $',
			'pos'    => 'before',
			'title'  => __( 'US Dollar ($)', 'dm' )
		],
		'ZAR' => [
			'flag'   => 'ZA',
			'symbol' => ' ZAR',
			'pos'    => 'after',
			'title'  => __( 'South African Rands (ZAR)', 'dm' )
		],
		'ANG' => [
			'flag'   => 'AN',
			'symbol' => 'ƒ ',
			'pos'    => 'before',
			'title'  => __( 'Netherlands Antillean Guilder (ANG)', 'dm' )
		],
		'AOA' => [
			'flag'   => 'AO',
			'symbol' => 'Kz ',
			'pos'    => 'before',
			'title'  => __( 'Angolan Kwanza (AOA)', 'dm' )
		],
		'ARS' => [
			'flag'   => 'AR',
			'symbol' => 'ARS $',
			'pos'    => 'before',
			'title'  => __( 'Argentine Peso (ARS)', 'dm' )
		],
		'AWG' => [
			'flag'   => 'AW',
			'symbol' => 'Afl. ',
			'pos'    => 'before',
			'title'  => __( 'Aruban Florin (AWG)', 'dm' )
		],
		'AZN' => [
			'flag'   => 'AZ',
			'symbol' => 'ман ',
			'pos'    => 'before',
			'title'  => __( 'Azerbaijani Manat (AZN)', 'dm' )
		],
		'BAM' => [
			'flag'   => 'BA',
			'symbol' => 'KM ',
			'pos'    => 'before',
			'title'  => __( 'Bosnia-Herzegovina Convertible Mark (BAM)', 'dm' )
		],
		'BBD' => [
			'flag'   => 'BB',
			'symbol' => 'BBD $',
			'pos'    => 'before',
			'title'  => __( 'Barbadian Dollar (BBD)', 'dm' )
		],
		'BGN' => [
			'flag'   => 'BG',
			'symbol' => ' лв',
			'pos'    => 'after',
			'title'  => __( 'Bulgarian Lev (BGN)', 'dm' )
		],
		'BHD' => [
			'flag'   => 'BH',
			'symbol' => 'BD ',
			'pos'    => 'before',
			'title'  => __( 'Bahraini Dinar (BHD)', 'dm' )
		],
		'BIF' => [
			'flag'   => 'BI',
			'symbol' => 'FBu ',
			'pos'    => 'before',
			'title'  => __( 'Burundian Franc (BIF)', 'dm' )
		],
		'BMD' => [
			'flag'   => 'BM',
			'symbol' => 'BMD $',
			'pos'    => 'before',
			'title'  => __( 'Bermudan Dollar (BMD)', 'dm' )
		],
		'BND' => [
			'flag'   => 'BN',
			'symbol' => 'B$ ',
			'pos'    => 'before',
			'title'  => __( 'Brunei Dollar (BND)', 'dm' )
		],
		'BOB' => [
			'flag'   => 'BO',
			'symbol' => 'Bs. ',
			'pos'    => 'before',
			'title'  => __( 'Bolivian Boliviano (BOB)', 'dm' )
		],
		'BSD' => [
			'flag'   => 'BS',
			'symbol' => 'BSD $',
			'pos'    => 'before',
			'title'  => __( 'Bahamian Dollar (BSD)', 'dm' )
		],
		'BTN' => [
			'flag'   => 'BT',
			'symbol' => 'Nu. ',
			'pos'    => 'before',
			'title'  => __( 'Bhutanese Ngultrum (BTN)', 'dm' )
		],
		'BWP' => [
			'flag'   => 'BW',
			'symbol' => 'P ',
			'pos'    => 'before',
			'title'  => __( 'Botswanan Pula (BWP)', 'dm' )
		],
		'BYN' => [
			'flag'   => 'BY',
			'symbol' => ' p.',
			'pos'    => 'after',
			'title'  => __( 'Belarusian Ruble (BYN)', 'dm' )
		],
		'BZD' => [
			'flag'   => 'BZ',
			'symbol' => 'BZD $',
			'pos'    => 'before',
			'title'  => __( 'Belize Dollar (BZD)', 'dm' )
		],
		'CLF' => [
			'flag'   => 'CL',
			'symbol' => 'UF ',
			'pos'    => 'before',
			'title'  => __( 'Chilean Unit of Account (UF) (CLF)', 'dm' )
		],
		'CNH' => [
			'flag'   => 'CN',
			'symbol' => ' CNH',
			'pos'    => 'after',
			'title'  => __( 'CNH (CNH)', 'dm' )
		],
		'CRC' => [
			'flag'   => 'CR',
			'symbol' => '₡ ',
			'pos'    => 'before',
			'title'  => __( 'Costa Rican Colón (CRC)', 'dm' )
		],
		'CUP' => [
			'flag'   => 'CU',
			'symbol' => '₱ ',
			'pos'    => 'before',
			'title'  => __( 'Cuban Peso (CUP)', 'dm' )
		],
		'CVE' => [
			'flag'   => 'CV',
			'symbol' => 'CVE $',
			'pos'    => 'before',
			'title'  => __( 'Cape Verdean Escudo (CVE)', 'dm' )
		],
		'DJF' => [
			'flag'   => 'DJ',
			'symbol' => ' Fdj',
			'pos'    => 'after',
			'title'  => __( 'Djiboutian Franc (DJF)', 'dm' )
		],
		'DKK' => [
			'flag'   => 'DK',
			'symbol' => 'kr ',
			'pos'    => 'before',
			'title'  => __( 'Danish Krone (DKK)', 'dm' )
		],
		'DOP' => [
			'flag'   => 'DO',
			'symbol' => 'RD$ ',
			'pos'    => 'before',
			'title'  => __( 'Dominican Peso (DOP)', 'dm' )
		],
		"EGP" => [
			"flag"   => "EG",
			"symbol" => " EGP",
			"pos"    => "after",
			"title"  => __( "Egyptian Pound (EGP)", "ads" )
		],
		'ERN' => [
			'flag'   => 'ER',
			'symbol' => 'Nfk ',
			'pos'    => 'before',
			'title'  => __( 'Eritrean Nakfa (ERN)', 'dm' )
		],
		'ETB' => [
			'flag'   => 'ET',
			'symbol' => 'Br ',
			'pos'    => 'before',
			'title'  => __( 'Ethiopian Birr (ETB)', 'dm' )
		],
		'FJD' => [
			'flag'   => 'FJ',
			'symbol' => 'FJD $',
			'pos'    => 'before',
			'title'  => __( 'Fijian Dollar (FJD)', 'dm' )
		],
		'FKP' => [
			'flag'   => 'FK',
			'symbol' => '‎£ ',
			'pos'    => 'before',
			'title'  => __( 'Falkland Islands Pound (FKP)', 'dm' )
		],
		'GEL' => [
			'flag'   => 'GE',
			'symbol' => ' GEL',
			'pos'    => 'after',
			'title'  => __( 'Georgian Lari (GEL)', 'dm' )
		],
		"GHS" => [
			"flag"   => "GH",
			"symbol" => "GH₵ ",
			"pos"    => "before",
			"title"  => __( "Ghanaian Cedi (GHS)", "ads" )
		],
		'GIP' => [
			'flag'   => 'GI',
			'symbol' => '£ ',
			'pos'    => 'before',
			'title'  => __( 'Gibraltar Pound (GIP)', 'dm' )
		],
		'GMD' => [
			'flag'   => 'GM',
			'symbol' => 'D ',
			'pos'    => 'before',
			'title'  => __( 'Gambian Dalasi (GMD)', 'dm' )
		],
		'GNF' => [
			'flag'   => 'GN',
			'symbol' => 'FG ',
			'pos'    => 'before',
			'title'  => __( 'Guinean Franc (GNF)', 'dm' )
		],
		'GTQ' => [
			'flag'   => 'GT',
			'symbol' => 'Q ',
			'pos'    => 'before',
			'title'  => __( 'Guatemalan Quetzal (GTQ)', 'dm' )
		],
		'GYD' => [
			'flag'   => 'GY',
			'symbol' => 'GYD $',
			'pos'    => 'before',
			'title'  => __( 'Guyanaese Dollar (GYD)', 'dm' )
		],
		'HKD' => [
			'flag'   => 'HK',
			'symbol' => 'HK$ ',
			'pos'    => 'before',
			'title'  => __( 'Hong Kong Dollar (HK$)', 'dm' )
		],
		'HNL' => [
			'flag'   => 'HN',
			'symbol' => 'L ',
			'pos'    => 'before',
			'title'  => __( 'Honduran Lempira (HNL)', 'dm' )
		],
		'HTG' => [
			'flag'   => 'HT',
			'symbol' => 'G ',
			'pos'    => 'before',
			'title'  => __( 'Haitian Gourde (HTG)', 'dm' )
		],
		'IQD' => [
			'flag'   => 'IQ',
			'symbol' => ' د.ع',
			'pos'    => 'after',
			'title'  => __( 'Iraqi Dinar (IQD)', 'dm' )
		],
		'IRR' => [
			'flag'   => 'IR',
			'symbol' => ' ﷼',
			'pos'    => 'after',
			'title'  => __( 'Iranian Rial (IRR)', 'dm' )
		],
		'ISK' => [
			'flag'   => 'IS',
			'symbol' => 'kr ',
			'pos'    => 'before',
			'title'  => __( 'Icelandic Króna (ISK)', 'dm' )
		],
		"JMD" => [
			"flag"   => "JM",
			"symbol" => "J$ ",
			"pos"    => "before",
			"title"  => __( "Jamaican Dollar (JMD)", "ads" )
		],
		'JOD' => [
			'flag'   => 'JO',
			'symbol' => ' JOD',
			'pos'    => 'after',
			'title'  => __( 'Jordanian Dinar (JOD)', 'dm' )
		],
		"JPY" => [
			"flag"   => "JP",
			"symbol" => "¥ ",
			"pos"    => "before",
			"title"  => __( "Japanese Yen (¥)", "ads" )
		],
		'KES' => [
			'flag'   => 'KE',
			'symbol' => 'KSh ',
			'pos'    => 'before',
			'title'  => __( 'Kenyan Shilling (KES)', 'dm' )
		],
		'KGS' => [
			'flag'   => 'KG',
			'symbol' => ' сом',
			'pos'    => 'after',
			'title'  => __( 'Kyrgystani Som (KGS)', 'dm' )
		],
		'KHR' => [
			'flag'   => 'KH',
			'symbol' => '៛ ',
			'pos'    => 'before',
			'title'  => __( 'Cambodian Riel (KHR)', 'dm' )
		],
		'KMF' => [
			'flag'   => 'KM',
			'symbol' => 'CF ',
			'pos'    => 'before',
			'title'  => __( 'Comorian Franc (KMF)', 'dm' )
		],
		'KPW' => [
			'flag'   => 'KP',
			'symbol' => '₩ ',
			'pos'    => 'before',
			'title'  => __( 'North Korean Won (KPW)', 'dm' )
		],
		'KWD' => [
			'flag'   => 'KW',
			'symbol' => ' ك',
			'pos'    => 'after',
			'title'  => __( 'Kuwaiti Dinar (KWD)', 'dm' )
		],
		'KYD' => [
			'flag'   => 'KY',
			'symbol' => 'KYD $',
			'pos'    => 'before',
			'title'  => __( 'Cayman Islands Dollar (KYD)', 'dm' )
		],
		'KZT' => [
			'flag'   => 'KZ',
			'symbol' => '‎₸ ',
			'pos'    => 'before',
			'title'  => __( 'Kazakhstani Tenge (KZT)', 'dm' )
		],
		'LAK' => [
			'flag'   => 'LA',
			'symbol' => '₭ ',
			'pos'    => 'before',
			'title'  => __( 'Laotian Kip (LAK)', 'dm' )
		],
		'LBP' => [
			'flag'   => 'LB',
			'symbol' => ' ل.ل',
			'pos'    => 'after',
			'title'  => __( 'Lebanese Pound (LBP)', 'dm' )
		],
		'LRD' => [
			'flag'   => 'LR',
			'symbol' => 'LRD $',
			'pos'    => 'before',
			'title'  => __( 'Liberian Dollar (LRD)', 'dm' )
		],
		'LSL' => [
			'flag'   => 'LS',
			'symbol' => 'M ',
			'pos'    => 'before',
			'title'  => __( 'Lesotho Loti (LSL)', 'dm' )
		],
		'LYD' => [
			'flag'   => 'LY',
			'symbol' => 'LD ',
			'pos'    => 'before',
			'title'  => __( 'Libyan Dinar (LYD)', 'dm' )
		],
		'MDL' => [
			'flag'   => 'MD',
			'symbol' => ' MDL',
			'pos'    => 'after',
			'title'  => __( 'Moldovan Leu (MDL)', 'dm' )
		],
		'MGA' => [
			'flag'   => 'MG',
			'symbol' => 'Ar ',
			'pos'    => 'before',
			'title'  => __( 'Malagasy Ariary (MGA)', 'dm' )
		],
		'MKD' => [
			'flag'   => 'MK',
			'symbol' => 'ден ',
			'pos'    => 'before',
			'title'  => __( 'Macedonian Denar (MKD)', 'dm' )
		],
		'MMK' => [
			'flag'   => 'MM',
			'symbol' => 'K ',
			'pos'    => 'before',
			'title'  => __( 'Myanmar Kyat (MMK)', 'dm' )
		],
		'MNT' => [
			'flag'   => 'MN',
			'symbol' => '‎₮ ',
			'pos'    => 'before',
			'title'  => __( 'Mongolian Tugrik (MNT)', 'dm' )
		],
		'MOP' => [
			'flag'   => 'MO',
			'symbol' => 'MOP$ ',
			'pos'    => 'before',
			'title'  => __( 'Macanese Pataca (MOP)', 'dm' )
		],
		'MRO' => [
			'flag'   => 'MR',
			'symbol' => 'UM ',
			'pos'    => 'before',
			'title'  => __( 'Mauritanian Ouguiya (MRO)', 'dm' )
		],
		'MUR' => [
			'flag'   => 'MU',
			'symbol' => '₨ ',
			'pos'    => 'before',
			'title'  => __( 'Mauritian Rupee (MUR)', 'dm' )
		],
		'MVR' => [
			'flag'   => 'MV',
			'symbol' => 'Rf. ',
			'pos'    => 'before',
			'title'  => __( 'Maldivian Rufiyaa (MVR)', 'dm' )
		],
		'MWK' => [
			'flag'   => 'MW',
			'symbol' => 'MK ',
			'pos'    => 'before',
			'title'  => __( 'Malawian Kwacha (MWK)', 'dm' )
		],
		'MXN' => [
			'flag'   => 'MX',
			'symbol' => 'MX$ ',
			'pos'    => 'before',
			'title'  => __( 'Mexican Peso (MX$)', 'dm' )
		],
		'MZN' => [
			'flag'   => 'MZ',
			'symbol' => 'MT ',
			'pos'    => 'before',
			'title'  => __( 'Mozambican Metical (MZN)', 'dm' )
		],
		'NAD' => [
			'flag'   => 'NA',
			'symbol' => 'NAD $',
			'pos'    => 'before',
			'title'  => __( 'Namibian Dollar (NAD)', 'dm' )
		],
		'NIO' => [
			'flag'   => 'NI',
			'symbol' => 'C$ ',
			'pos'    => 'before',
			'title'  => __( 'Nicaraguan Córdoba (NIO)', 'dm' )
		],
		'NPR' => [
			'flag'   => 'NP',
			'symbol' => '₨ ',
			'pos'    => 'before',
			'title'  => __( 'Nepalese Rupee (NPR)', 'dm' )
		],
		'PAB' => [
			'flag'   => 'PA',
			'symbol' => 'B/. ',
			'pos'    => 'before',
			'title'  => __( 'Panamanian Balboa (PAB)', 'dm' )
		],
		'PEN' => [
			'flag'   => 'PE',
			'symbol' => 'S/. ',
			'pos'    => 'before',
			'title'  => __( 'Peruvian Nuevo Sol (PEN)', 'dm' )
		],
		'PGK' => [
			'flag'   => 'PG',
			'symbol' => 'K ',
			'pos'    => 'before',
			'title'  => __( 'Papua New Guinean Kina (PGK)', 'dm' )
		],
		'PYG' => [
			'flag'   => 'PY',
			'symbol' => 'Gs ',
			'pos'    => 'before',
			'title'  => __( 'Paraguayan Guarani (PYG)', 'dm' )
		],
		'RON' => [
			'flag'   => 'RO',
			'symbol' => 'lei ',
			'pos'    => 'before',
			'title'  => __( 'Romanian Leu (RON)', 'dm' )
		],
		'RSD' => [
			'flag'   => 'RS',
			'symbol' => ' RSD',
			'pos'    => 'after',
			'title'  => __( 'Serbian Dinar (RSD)', 'dm' )
		],
		'RWF' => [
			'flag'   => 'RW',
			'symbol' => ' RWF',
			'pos'    => 'after',
			'title'  => __( 'Rwandan Franc (RWF)', 'dm' )
		],
		'SBD' => [
			'flag'   => 'SB',
			'symbol' => 'SI$ ',
			'pos'    => 'before',
			'title'  => __( 'Solomon Islands Dollar (SBD)', 'dm' )
		],
		'SCR' => [
			'flag'   => 'SC',
			'symbol' => 'SR ',
			'pos'    => 'before',
			'title'  => __( 'Seychellois Rupee (SCR)', 'dm' )
		],
		'SDG' => [
			'flag'   => 'SD',
			'symbol' => 'SD',
			'pos'    => 'before',
			'title'  => __( 'Sudanese Pound (SDG)', 'dm' )
		],
		'SLL' => [
			'flag'   => 'SL',
			'symbol' => 'Le ',
			'pos'    => 'before',
			'title'  => __( 'Sierra Leonean Leone (SLL)', 'dm' )
		],
		'SOS' => [
			'flag'   => 'SO',
			'symbol' => 'S ',
			'pos'    => 'before',
			'title'  => __( 'Somali Shilling (SOS)', 'dm' )
		],
		'SRD' => [
			'flag'   => 'SR',
			'symbol' => 'SRD $',
			'pos'    => 'before',
			'title'  => __( 'Surinamese Dollar (SRD)', 'dm' )
		],
		'STD' => [
			'flag'   => 'ST',
			'symbol' => 'Db ',
			'pos'    => 'before',
			'title'  => __( 'São Tomé &amp; Príncipe Dobra (STD)', 'dm' )
		],
		'SVC' => [
			'flag'   => 'SV',
			'symbol' => '₡ ',
			'pos'    => 'before',
			'title'  => __( 'Salvadoran Colón (SVC)', 'dm' )
		],
		'SYP' => [
			'flag'   => 'SY',
			'symbol' => '£ ',
			'pos'    => 'before',
			'title'  => __( 'Syrian Pound (SYP)', 'dm' )
		],
		'SZL' => [
			'flag'   => 'SZ',
			'symbol' => 'E ',
			'pos'    => 'before',
			'title'  => __( 'Swazi Lilangeni (SZL)', 'dm' )
		],
		'TJS' => [
			'flag'   => 'TJ',
			'symbol' => ' TJS',
			'pos'    => 'after',
			'title'  => __( 'Tajikistani Somoni (TJS)', 'dm' )
		],
		'TMT' => [
			'flag'   => 'TM',
			'symbol' => 'T ',
			'pos'    => 'before',
			'title'  => __( 'Turkmenistani Manat (TMT)', 'dm' )
		],
		'TOP' => [
			'flag'   => 'TO',
			'symbol' => 'T$ ',
			'pos'    => 'before',
			'title'  => __( 'Tongan Paʻanga (TOP)', 'dm' )
		],
		'TRY' => [
			'flag'   => 'TR',
			'symbol' => ' TL',
			'pos'    => 'after',
			'title'  => __( 'Turkish Lira (TRY)', 'dm' )
		],
		'TTD' => [
			'flag'   => 'TT',
			'symbol' => 'TTD $',
			'pos'    => 'before',
			'title'  => __( 'Trinidad &amp; Tobago Dollar (TTD)', 'dm' )
		],
		'TWD' => [
			'flag'   => 'TW',
			'symbol' => 'NT$ ',
			'pos'    => 'before',
			'title'  => __( 'New Taiwan Dollar (NT$)', 'dm' )
		],
		'TZS' => [
			'flag'   => 'TZ',
			'symbol' => 'TSh ',
			'pos'    => 'before',
			'title'  => __( 'Tanzanian Shilling (TZS)', 'dm' )
		],
		'UGX' => [
			'flag'   => 'UG',
			'symbol' => 'USh ',
			'pos'    => 'before',
			'title'  => __( 'Ugandan Shilling (UGX)', 'dm' )
		],
		'UYU' => [
			'flag'   => 'UY',
			'symbol' => '$U ',
			'pos'    => 'before',
			'title'  => __( 'Uruguayan Peso (UYU)', 'dm' )
		],
		'UZS' => [
			'flag'   => 'UZ',
			'symbol' => " so'm",
			'pos'    => 'after',
			'title'  => __( 'Uzbekistani Som (UZS)', 'dm' )
		],
		'VEF' => [
			'flag'   => 'VE',
			'symbol' => 'Bs. ',
			'pos'    => 'before',
			'title'  => __( 'Venezuelan Bolívar (VEF)', 'dm' )
		],
		'VND' => [
			'flag'   => 'VN',
			'symbol' => '₫ ',
			'pos'    => 'before',
			'title'  => __( 'Vietnamese Dong (₫)', 'dm' )
		],
		'VUV' => [
			'flag'   => 'VU',
			'symbol' => ' VT',
			'pos'    => 'after',
			'title'  => __( 'Vanuatu Vatu (VUV)', 'dm' )
		],
		'WST' => [
			'flag'   => 'WS',
			'symbol' => 'WST $',
			'pos'    => 'before',
			'title'  => __( 'Samoan Tala (WST)', 'dm' )
		],
		'YER' => [
			'flag'   => 'YE',
			'symbol' => ' ﷼',
			'pos'    => 'after',
			'title'  => __( 'Yemeni Rial (YER)', 'dm' )
		],
		'ZWL' => [
			'flag'   => 'ZW',
			'symbol' => 'ZWL $',
			'pos'    => 'before',
			'title'  => __( 'Zimbabwean Dollar (2009) (ZWL)', 'dm' )
		]
	];
}

/**
 * Converter currency from USD to selected
 *
 * @param $price
 * @param string $to
 * @return float|string
 */
function dm_convert_price( $price, $to = 'USD' ) {
	
	$price     = dm_floatvalue( $price );
	$curValues = @unserialize( dm_currencies_values() );
	
	if( 'USD' == $to )
		return $price;
	
	if( ! $curValues || ! isset( $curValues[ $to ] ) )
		return $price;
	
	return round( $price * $curValues[ $to ], 2 );
}

/**
 * Converter currency from selected to USD
 *
 * @param $price
 * @param string $from
 * @return float|string
 */
function dm_reconvert_price( $price, $from = 'USD' ) {
	
	$price     = dm_floatvalue( $price );
	$curValues = @unserialize( dm_currencies_values() );
	
	if( 'USD' == $from )
		return $price;
	
	if( ! $curValues || ! isset( $curValues[ $from ] ) )
		return $price;
	
	return round( $price / $curValues[ $from ], 2 );
}

function dm_currencies_values() {
	
	if ( ! defined( 'DM_CURRENCY_VAL' ) ) {
		
		define( 'DM_CURRENCY_VAL',
			DM_PLUGIN == 'alidropship' ? ADS_CUVALUE :
			serialize( [ get_option( 'dm_currency_code', 'USD' ) => get_option( 'dm_currency_value', 1 ) ] )
		);
	}
	
	return DM_CURRENCY_VAL;
}

/**
 * Set currency format for price
 *
 * @param $price 2.05
 * @param string $to
 *
 * @return string "US $price"
 */
function dm_format_price( $price, $to = 'USD' ) {
	
	$foo   = dm_get_currency_symbol( $to );
	$price = number_format( (double) $price, 2, '.', ',' );
	
	if( ! is_array( $foo ) )
		return $to . ' ' . $price;
	
	return $foo[ 'pos' ] == 'before' ? $foo[ 'symbol' ] . $price : $price . $foo[ 'symbol' ];
}

/**
 * Get currency symbol
 *
 * @param $code
 *
 * @return mixed
 */
function dm_get_currency_symbol( $code ) {
	
	$foo = dm_list_currency();
	
	return isset( $foo[ $code ] ) ? $foo[ $code ] : [
		'pos' => 'before', 'symbol' => $code, 'title' => $code, 'flag' => $code
	];
}
/**
 * Get image from product
 *
 * @param $original
 * @param string $size
 *
 * @return bool|string
 */
function dm_get_thumb_ali( $original, $size = '' ) {

    return $original == '' ? false : dm_get_size_img( $original, $size );
}

function dm_get_near_image_size ( $size ){

    $foo = [
        'thumbnail' => [
            'size' => '_50x50.jpg',
            'width' => 50,
            'height' => 50
        ],
        'medium' => [
            'size' => '_220x220.jpg',
            'width' => 220,
            'height' => 220
        ],
        'woocommerce_gallery_thumbnail' => [
            'size' => '_220x220.jpg',
            'width' => 220,
            'height' => 220
        ],
        'shop_thumbnail' => [
            'size' => '_220x220.jpg',
            'width' => 220,
            'height' => 220
        ],
        'big' => [
            'size' => '_350x350.jpg',
            'width' => 350,
            'height' => 350
        ],
        'woocommerce_thumbnail' => [
            'size' => '_350x350.jpg',
            'width' => 350,
            'height' => 350
        ],
        'shop_catalog' => [
            'size' => '_350x350.jpg',
            'width' => 350,
            'height' => 350
        ],
        'large' => [
            'size' => '_640_640.jpg',
            'width' => 640,
            'height' => 640
        ],
        'woocommerce_single' => [
            'size' => '_640_640.jpg',
            'width' => 640,
            'height' => 640
        ],
        'shop_single' => [
            'size' => '_640_640.jpg',
            'width' => 640,
            'height' => 640
        ],
    ];

    if( ! is_array($size) ){
        if( ! array_key_exists( $size, $foo ) ) {
            return '';
        }
    } else {
        if($size[0] > 640)
            return '';

        $result = '';
        foreach ($foo as $key => $val){
            if( $size[0] > $val['width'] && $size[1] > $val['height'] ){
                $result = $key;
            }
        }
        $size = $result;
    }
    return $foo[$size]['size'];
}
/**
 * List Image Size
 *
 * @param $url
 * @param string $size
 *
 * @return string
 */
function dm_get_size_img( $url, $size = 'medium' ) {

    $size = dm_get_near_image_size( $size );

    $url = dm_get_full_url_img( $url );

    $is_aliexpress_path = strripos( $url, 'alicdn.com' );

    if ( $is_aliexpress_path === false ) {
        return $url;
    } else {
        return $url . $size;
    }
}

function dm_get_full_url_img( $url ) {

    return preg_replace( '/_\d+x\d+\.jpg$/', '', $url );
}
function dm_get_list_images( $args = [], $size = 'thumbnail' ) {

    if ( count( $args ) <= 0 ) {
        return false;
    }

    global $wpdb;

    $fileds = implode( ',', $args );

    $result = $wpdb->get_results(
        "SELECT post_id, meta_value FROM {$wpdb->postmeta} 
          WHERE meta_key = '_wp_attachment_metadata' AND post_id IN ({$fileds}) 
          ORDER BY FIELD(post_id, {$fileds})" );

    if ( ! $result ) {
        return false;
    }

    if ( ! is_array( $size ) ) {
        $size = [ $size ];
    }

    $upload_dir = wp_upload_dir();
    $upload_dir = $upload_dir[ 'baseurl' ];

    $foo = [];

    foreach ( $result as $item ) {

        $f = maybe_unserialize( $item->meta_value );
        if ( ! $f ) {
            continue;
        }

        $folder = substr( $f[ 'file' ], 0, strrpos( $f[ 'file' ], '/' ) );

        $foo[ $item->post_id ][ 'full' ] = [
            'width'  => $f[ 'width' ],
            'height' => $f[ 'height' ],
            'url'    => $upload_dir . '/' . $f[ 'file' ]
        ];

        foreach ( $size as $i ) {
            $foo[ $item->post_id ][ $i ] = isset( $f[ 'sizes' ][ $i ] ) ? [
                'width'  => $f[ 'sizes' ][ $i ][ 'width' ],
                'height' => $f[ 'sizes' ][ $i ][ 'height' ],
                'url'    => $upload_dir . '/' . $folder . '/' . $f[ 'sizes' ][ $i ][ 'file' ]
            ] : $foo[ $item->post_id ][ 'full' ];
        }
    }

    return $foo;
}
