<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/layout/top-menu'][] = function( $app, $ret )
{
	$label = __('Add-ons', 'locatoraid');

	$link = $app->make('/html/ahref')
		// ->to( 'http://www.locatoraid.com/order/' )
		->to('/promo')
		// ->set_outside( TRUE )
		// ->add( $app->make('/html/icon')->icon('star') )
		->add( $label )
		// ->add_attr( 'target', '_blank' )
		;
	$ret['promo1'] = array( $link, 1001 );

	return $ret;
};

$config['after']['/layout/top-menu'][] = function( $app, $ret )
{
	// check admin
	$loggedIn = $app->make('/auth/lib')
		->logged_in()
		;
	$isAdmin = $app->make('/acl/roles')
		->has_role( $loggedIn, 'admin')
		;
	if( ! $isAdmin ){
		return $ret;
	}

	// return $ret;
	$label = 'Locatoraid Pro &nearr;';

	$link = $app->make('/html/ahref')
		->to( 'https://www.locatoraid.com/order/' )
		->set_outside( TRUE )
		->add( $app->make('/html/icon')->icon('star') )
		->add( $label )
		->add_attr( 'target', '_blank' )
		;
	$ret['promo2'] = array( $link, 1002 );

	return $ret;
};

$config['after']['/app.conf/form'][] = function( $app, $ret )
{
	// $ret['front:show_credits'] = array(
		// 'input'	=> $app->make('/form/checkbox'),
		// 'label'	=> __('Show Credits?', 'locatoraid'),
		// 'help'	=> __('This will place a small Powered By link below the map', 'locatoraid'),
		// );
	// return $ret;

	$setting = $app->make('/app/settings');
	$pname = 'front:show_credits';
	$v = $setting->get( $pname );

	$checked = $v ? ' checked' : '';

	$html = '';
	$html .= '<label style="margin-bottom: .5em; display: block;"><input style="display: inline-block;" type="checkbox" value="1" name="hc-front:show_credits" ' . $checked . '/><span style="display: inline-block;">' . __('Show Credits?', 'locatoraid') . '</span>';
	$html .= '<div class="hc-block hc-muted1 hc-italic">' . __('This will place a small Powered By link below the map', 'locatoraid') . '</div>';
	$html .= '</label>';

	$ret = $ret + array(
		'front:show_credits' => $html
	);
	return $ret;
};

$config['after']['/maps-google.conf/form'][] = function( $app, $ret )
{
	$setting = $app->make('/app/settings');
	$pname = 'front:show_credits';
	$v = $setting->get( $pname );

	$checked = $v ? ' checked' : '';

	$html = '';
	$html .= '<label style="margin-bottom: .5em; display: block;"><input style="display: inline-block;" type="checkbox" value="1" name="hc-front:show_credits" ' . $checked . '/><span style="display: inline-block;">' . __('Show Credits?', 'locatoraid') . '</span>';
	$html .= '<div class="hc-block hc-muted1 hc-italic">' . __('This will place a small Powered By link below the map', 'locatoraid') . '</div>';
	$html .= '</label>';

	$ret = array(
		'front:show_credits' => $html
	) + $ret;

	return $ret;
};

$config['after']['/front/view'][] = function( $app, $ret )
{
	$setting = $app->make('/app/settings');
	$pname = 'front:show_credits';
	$v = $setting->get( $pname );

	if( ! $v ){
		return $ret;
	}

	$keys = array( 
		'WordPress store locator',
		'WordPress store locator plugin',
		'Store locator plugin',
		'Store locator plugin for WordPress',
		'Create store locator',
		'Location management plugin',
		'Location management software',
	);
	$id = strlen( __FILE__ );
	$id = $id % count( $keys );
	$k = isset( $keys[$id] ) ? $keys[$id] : current( $keys );

	// $out = '<div style="margin: 1em auto; text-align: center; font-size: .7em; opacity: .5;">WordPress store locator by <a target="_blank" href="https://www.locatoraid.com/">Locatoraid</a></div>'; 
	$out = '<div style="margin: 1em auto; text-align: center; font-size: .7em; opacity: .5;"><a target="_blank" href="https://www.locatoraid.com/">' . $k . '</a> by Locatoraid</div>'; 

	$ret
		->add( $out )
		;

	return $ret;
};