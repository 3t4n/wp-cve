<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_View_LC_HC_MVC extends _HC_MVC
{
	public function params()
	{
		$ret = array(
			'layout'		=> 'map|list',
			'form-after-map'	=> 0,
			'start'			=> '',

			'map-start-address'	=> null,
			'map-start-zoom'		=> null,
			'map-max-zoom'		=> null,
			'map-hide-loc-title'	=> null,

			'limit'			=> 2000,

			'group'			=> NULL,
			'group-jump'	=> 0,
			'list-group'	=> NULL,
			'sort'			=> NULL,
			'map-style'		=> 'height: 400px; width: 100%;',
			'list-style'	=> 'height: 400px; overflow-y: scroll;',

			'search-bias-country'	=> '', // australia, uk, finland etc
			'radius'				=> '10, 25, 50, 100, 200, 500',
			'radius-select'	=> 0,
			'id'			=> NULL,
			'clustering'	=> 0,

			'locate'		=> 1, // 0,1,auto
			);

		$p = $this->app->make('/locations/presenter');
		$also_take = $p->database_fields();
		foreach( $also_take as $tk ){
			$ret[ 'where-' . $tk ] = NULL;
		}
		$ret[ 'where-product' ] = NULL;

		return $ret;
	}

	public function render( $pass_params = array() )
	{
		$enqueuer = $this->app->make('/app/enqueuer');
		$enqueuer
			->register_script( 'lc_front', 'modules/front/assets/js/front.js?hcver=' . LC3_VERSION )
			->enqueue_script( 'lc_front' )
			;

		$enqueuer
			->register_script( 'hc', 'happ2/assets/js/hc2.js?hcver=' . LC3_VERSION )
			->register_style( 'hc', 'happ2/assets/css/hc.css?hcver=' . LC3_VERSION )
			;

		$enqueuer
			->enqueue_script( 'hc' )
			->enqueue_style( 'hc' )
			;

		$enqueuer
			->register_script( 'gmaps', 'happ2/modules/maps_google/assets/js/gmaps.js' )
			;

		$app_settings = $this->app->make('/app/settings');
		$api_key = $app_settings->get('maps_google:api_key');
		if( is_array($api_key) ){
			$api_key = array_shift($api_key);
		}

		if( $api_key == 'none' ){
			$api_key = '';
		}
		$api_key = trim($api_key);

		$language = $app_settings->get('maps_google:language');
		if( is_array($language) ){
			$language = array_shift($language);
		}
		$language = trim( $language );

		$map_style = $app_settings->get('maps_google:map_style');
		$scrollwheel = $app_settings->get('maps_google:scrollwheel');
		$scrollwheel = $scrollwheel ? TRUE : FALSE;
		$more_options = $app_settings->get('maps_google:more_options');

		$icon = '';
		$icon_id = $app_settings->get('maps_google:icon');
		if( $icon_id ){
			$your_img_src = wp_get_attachment_image_src( $icon_id, 'full' );
			$have_img = is_array( $your_img_src );
			if( $have_img ){
				$icon = $your_img_src[0];
			}
		}

		$params = array(
			'api_key'		=> $api_key,
			'language'		=> $language,
			'map_style'		=> $map_style,
			'scrollwheel'	=> $scrollwheel,
			'more_options'	=> $more_options,
			'icon'			=> $icon,
			);

		$enqueuer
			->localize_script( 'gmaps', $params )
			->enqueue_script( 'gmaps' )
			;

	// parse params
		$default_params = $this->params();

// _print_r( $default_params );
// exit;
// _print_r( $pass_params );

		$params = array();
		foreach( $default_params as $k => $default_v ){
			if( ! array_key_exists($k, $pass_params) ){
				$params[$k] = $default_v;
				continue;
			}

			if( ! is_array($default_v) ){
				$params[$k] = $pass_params[$k];
				continue;
			}

			if( ! is_array($pass_params[$k]) ){
				$pass_params[$k] = array( $pass_params[$k] );
			}

			$v = array();
			foreach( $pass_params[$k] as $pass_v ){
				if( in_array($pass_v, $default_v) ){
					$v[] = $pass_v;
				}
			}

			if( ! $v ){
				$v = $default_v;
			}
			$params[$k] = $v;
		}

		if( isset($_GET['lpr-search']) ){ 
			$get_search = sanitize_text_field($_GET['lpr-search']);
			$params['start'] = $get_search;
		}

		// also can override any of the params by GET
		$keys = array_keys($default_params);
		foreach( $keys as $key ){
			$get_key = 'lctr-' . $key;
			if( ! array_key_exists($get_key, $_GET) ){
				continue;
			}
			$get = sanitize_text_field( $_GET[$get_key] );
			$params[ $key ] = $get;
		}

// _print_r( $pass_params );
// _print_r( $params );

		if( isset($params['clustering']) && $params['clustering'] ){
			$img_path = 'happ2/modules/maps_google/assets/js/images/m';
			$img_path = $this->app->make('/layout.wordpress/path')
				->full_path( $img_path )
				;
			$clusterer_params = array(
				'img_path'	=> $img_path,
				'count'		=> $params['clustering'],
				);
			$enqueuer
				->localize_script( 'gmapsclusterer', $clusterer_params )
				;
			$enqueuer
				->enqueue_script( 'gmapsclusterer' )
				;
		}

	// parse layout
		$layout_conf_setting = $params['layout'];
		$allowed_components = array('map', 'list');

		$explode_by = '';
		$layout = array();
		if( strpos($layout_conf_setting, '|') !== FALSE ){
			$explode_by = '|';
		}
		elseif( strpos($layout_conf_setting, '/') !== FALSE ){
			$explode_by = '/';
		}

		if( $explode_by ){
			$layout_setting_array = explode($explode_by, $layout_conf_setting);
			foreach( $layout_setting_array as $ls ){
				$ls = strtolower(trim($ls));
				if( ! strlen($ls) ){
					continue;
				}
				if( ! in_array($ls, $allowed_components) ){
					continue;
				}
				$layout[] = $ls;
			}
			if( count($layout) > 1 ){
				$layout[] = $explode_by;
			}
		}
		else {
			$layout[] = $layout_conf_setting;
		}

		if( ! $layout ){
			$layout = array('map', 'list', '|');
		}

		if( $params['id'] ){
			$layout = array('map');
			$layout = array('map', 'list', '/');
		}

		$view_type = 'stack';
		if( (count($layout) > 1) && ($layout[count($layout)-1] == '|') ){
			$view_type = 'grid';
		}

		$lc_front_params = array();
		if( $params['search-bias-country'] ){
			$search_bias_country = $params['search-bias-country'];
			$search_bias_country = explode(',', $search_bias_country);
			$lc_front_params['search_bias_country'] = $search_bias_country;
		}
		$enqueuer
			->localize_script( 'lc_front', $lc_front_params )
			;

	// parse radius
		if( isset($params['radius']) ){
			$supplied = $params['radius'];
			if( ! is_array($supplied) ){
				$supplied = explode(',', $supplied);
			}

			$final = array();
			foreach( $supplied as $r ){
				$r = trim($r);
				if( (string)(int) $r == $r ){
					$final[] = $r;
				}
			}
			$final = array_unique( $final );
			$params['radius'] = $final;
		}
		else {
			$params['radius'] = array();
		}

		$form = $this->app->make('/front/view/form')
			->render($params)
			;

		$form_view = $this->app->make('/html/element')->tag('div')
			->add( $form )
			->add_attr('class', 'hc-mb3')
			// ->add_attr('class', 'hc-p3')
			// ->add_attr('class', 'hc-border')
			;

		$views = array();
		if( in_array('map', $layout) ){
			$views['map'] = $this->app->make('/front/view/map')
				->render($params)
				;
			$widths['map'] = 8;
		}

		if( in_array('list', $layout) ){
			$need_list_params = array('group', 'group-jump', 'list-style');
			$list_params = array();
			foreach( $params as $k => $v ){
				if( ! in_array($k, $need_list_params) ){
					continue;
				}
				if( null === $v ){
					continue;
				}
				$v = trim($v);
				if( ! strlen($v) ){
					continue;
				}
				$list_params[$k] = $v;
			}
			$views['list'] = $this->app->make('/front/view/list')
				->render($list_params)
				;
			$widths['list'] = 4;
		}

		$formAfterMap = isset( $params['form-after-map'] ) && $params['form-after-map'] ? TRUE : FALSE;

		$form_view = $this->app->make('/html/element')->tag('div')
			->add( $form_view )
			->add_attr('id', 'locatoraid-form-container')
			;

		if( $formAfterMap ){
			$form_view->add_attr( 'class', 'hc-mt2' );
		}

		if( count($layout) > 1 ){
			switch( $view_type ){
				case 'grid':
					$grid_id = 'hclc_grid';
					$out2 = $this->app->make('/html/grid')
						->set_gutter(2)
						;

					foreach( $layout as $k ){
						if( ! isset($views[$k]) ){
							continue;
						}

						$out2
							->add( $views[$k], $widths[$k] )
							;
					}

					$out2 = $this->app->make('/html/element')->tag('div')
						->add( $out2 )
						->add_attr('id', $grid_id)
						// ->add_attr('style', 'height: 400px;')
						;

					if( $formAfterMap && isset($views['map']) ){
						$out2
							->add( $form_view )
							;
					}
					break;

				default:
					if( $formAfterMap && isset($views['map']) ){
						$views['map'] = $this->app->make('/html/element')->tag('div')
							->add( $views['map'] )
							->add( $form_view )
						;
					}

					$out2 = $this->app->make('/html/element')->tag(NULL);
					foreach( $layout as $k ){
						if( ! isset($views[$k]) ){
							continue;
						}

						$out2
							->add(
								$this->app->make('/html/element')->tag('div')
									->add( $views[$k] )
									->add_attr('class', 'hc-mb3')
								)
							;
					}
					break;
			}
		}
		else {
			$out2 = $views[$layout[0]];
		}

		$out2 = $this->app->make('/html/element')->tag('div')
			->add( $out2 )
			->add_attr('id', 'locatoraid-map-list-container')
			;

		$out = $this->app->make('/html/element')->tag(NULL);

		if( $formAfterMap && isset($views['map']) ){
		}
		else {
			$out
				->add( $form_view )
				;
		}

		$out
			->add( $out2 )
			;

		$out = $this->app
			->after( $this, $out )
			;

		return $out;
	}
}