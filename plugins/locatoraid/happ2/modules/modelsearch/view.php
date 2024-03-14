<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class ModelSearch_View_HC_MVC extends _HC_MVC
{
	public function render( $search = '', $ajax = FALSE )
	{
		$current_slug = $this->app->make('/http/uri')
			->slug()
			;

		$form = $this->app->make('/modelsearch/form');
		$helper = $this->app->make('/form/helper');

		$inputs_view = $helper->prepare_render( $form->inputs(), array('search' => $search) );
		$out_inputs = $helper->render_inputs( $inputs_view );

		$out_buttons = $this->app->make('/html/list-inline')
			->set_gutter(1)
			;

		$out_buttons->add(
			$this->app->make('/html/element')->tag('input')
				->add_attr('type', 'submit')
				->add_attr('title', __('Search', 'locatoraid') )
				->add_attr('value', __('Search', 'locatoraid') )
				->add_attr('class', 'hc-theme-btn-submit')
				->add_attr('class', 'hc-theme-btn-secondary')
				->add_attr('class', 'hc-mt1')
			);

		if( $search ){
			$out_buttons->add(
				$this->app->make('/html/ahref')
					->to('-', array('search' => NULL))
					->add( __('Reset', 'locatoraid') ) 
					->add_attr('class', 'hc-theme-btn-submit')
					// ->add_attr('class', 'hc-theme-btn-primary')
					->add_attr('class', 'hc-theme-btn-secondary')
					->add_attr('class', 'hc-mt1')
				);
		}

		$out = $this->app->make('/html/list-inline')
			->set_gutter(1)
			->add( $out_inputs )
			->add( $out_buttons )
			;

		$link = $this->app->make('/http/uri')
			->url('/modelsearch')
			;

		$out = $helper
			->render( array('action' => $link) )
			->add( $out )
			;
		return $out;
	}
}