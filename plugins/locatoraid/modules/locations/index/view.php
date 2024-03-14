<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Index_View_LC_HC_MVC extends _HC_MVC
{
	public function render( $entries, $total_count, $page = 1, $search = '', $per_page = 5 )
	{
		$header = $this->header();

		$rows = array();
		reset( $entries );
		foreach( $entries as $e ){
			$rows[ $e['id'] ] = $this->row( $e );
		}

		$out = $this->app->make('/html/list')
			->set_gutter(2)
			;

		$submenu = $this->app->make('/html/list-inline')
			->set_gutter(2)
			;

		if( $rows ){
			$helper = $this->app->make('/form/helper');
			$bulk_form = $this->bulk_form();
			$submenu
				->add( $bulk_form )
				;
		}

		$minPerPage = 20;
		if( $total_count > $minPerPage ){
			$pager = $this->app->make('/html/pager')
				->set_total_count( $total_count )
				->set_current_page( $page )
				->set_per_page($per_page)
				;

			$submenu
				->add( $pager )
				;
		}


		if( $rows ){
			$table = $this->app->make('/html/table-responsive')
				->set_no_footer(FALSE)
				->set_header($header)
				->set_rows($rows)
				;

			$firstRow = reset( $rows );
			if( isset($firstRow['icon']) ){
				$table
					->set_width( 'title', 5 )
					->set_width( 'coordinates', 3 )
					->set_width( 'icon', 1 )
					->set_width( 'product', 3 )
					->set_style( 'icon', 'text-align: center;' )
					;
			}
			else {
				$table
					->set_width( 'title', 8 )
					->set_width( 'coordinates', 4 )
					;
			}

			$table = $this->app->make('/html/element')->tag('div')
				->add( $table )
				->add_attr('class', 'hc-border')
				;

			$out
				->add( $table )
				;
		}
		elseif( $search ){
			$msg = __('No Matches', 'locatoraid');
			$out
				->add( $msg )
				;
		}

		$out
			->add( $submenu )
			;

	// add bulk form
		if( $rows ){
			// $helper = $this->app->make('/form/helper');
			// $bulk_form = $this->bulk_form();

			// $out = $this->app->make('/html/list')
				// ->set_gutter(1)
				// ->add( $bulk_form )
				// ->add( $out )
				// ;

			$link = $this->app->make('/http/uri')
				->url('/locations/bulk')
				;
			$out = $helper
				->render( array('action' => $link) )
				->add( $out )
				;
		}


		$search_view = $this->app->make('/modelsearch/view');

		$out = $this->app->make('/html/list')
			->set_gutter(1)
			->add( $search_view )
			->add( $out )
			;

		// $submenu
			// ->add( $search_view->render($search) )
			// ;

		return $out;
	}

	public function bulk_form()
	{
		$form = $this->app->make('/locations/bulk/form');

		$helper = $this->app->make('/form/helper');
		$inputs_view = $helper->prepare_render( $form->inputs() );

		$btn = $this->app->make('/html/element')->tag('input')
			->add_attr('type', 'submit')
			->add_attr('title', __('Apply', 'locatoraid') )
			->add_attr('value', __('Apply', 'locatoraid') )
			->add_attr('class', 'hc-theme-btn-submit')
			->add_attr('class', 'button-primary')
			->add_attr('class', 'hc-xs-block')
			;

		$out = $this->app->make('/html/list-inline')
			->set_gutter(1)
			->add( $inputs_view['action'] )
			->add( $btn )
			;

		return $out;
	}

	public function header()
	{
		$title_view = __('Location', 'locatoraid');

	// add checkbox
		$checkbox = $this->app->make('/form/checkbox')
			->render('')
			->add_attr('class', 'hcj2-all-checker')
			->add_attr('data-collect', 'hc-id[]')
			;

		$title_view = $this->app->make('/html/list-inline')
			->set_gutter(1)
			->add( $checkbox )
			->add( $title_view )
			;

		$return = array(
			'title' 	=> $title_view,
			);

		$return = $this->app
			->after( array($this, __FUNCTION__), $return )
			;

		return $return;
	}

	public function row( $e )
	{
		$return = array();
		if( ! $e ){
			return $return;
		}

		$p = $this->app->make('/locations/presenter');

		$title_view = $p->present_title( $e );
		$title_view = $this->app->make('/html/ahref')
			->to('/locations/' . $e['id'] . '/edit')
			->add( $title_view )
		// imitate wordpress
			->add_attr('class', 'hc-bold')
			->add_attr('class', 'hc-fs4')
			->add_attr('class', 'hc-decoration-none')
			->add_attr('title', htmlspecialchars($title_view))
			;

		$return['id'] = $e['id'];
		$id_view = $this->app->make('/html/element')->tag('span')
			->add_attr('class', 'hc-fs2')
			->add_attr('class', 'hc-muted2')
			->add( 'id: ' . $e['id'] )
			;

		$address_view = $p->present_address( $e );

	// add checkbox
		$checkbox = $this->app->make('/form/checkbox')
			->set_value( $e['id'] )
			->render( 'id[]' )
			;

		$title_view = $this->app->make('/html/list-inline')
			->set_gutter(1)
			->add( $checkbox )
			->add( $title_view )
			;

		$title_view = $this->app->make('/html/list')
			->set_gutter(0)
			->add( $title_view )
			->add( $address_view )
			->add( $id_view )
			;

	// add checkbox
		// $checkbox = $this->app->make('/form/checkbox')
			// ->set_value( $e['id'] )
			// ->render( 'id[]' )
			// ;

		// $title_view = $this->app->make('/html/list-inline')
			// ->set_gutter(1)
			// ->add( $checkbox )
			// ->add( $title_view )
			// ;

		$title_view = $this->app->make('/html/element')->tag('div')
			->add( $title_view )
			->add_attr('class', 'hc-nowrap')
			;

		// $return['id'] = $checkbox;
		$return['id_view'] = $id_view;
		$return['title'] = $title_view;
		$return['address'] = $address_view;

		$return = $this->app
			->after( array($this, __FUNCTION__), $return, $e )
			;

		return $return;
	}
}