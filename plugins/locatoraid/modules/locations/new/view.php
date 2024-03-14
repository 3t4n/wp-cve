<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_New_View_LC_HC_MVC extends _HC_MVC
{
	public function render()
	{
		$form = $this->app->make('/locations/form');

		$helper = $this->app->make('/form/helper');

		$inputs_view = $helper->prepare_render( $form->inputs() );
		$out_inputs = $helper->render_inputs( 
			$inputs_view,
			array(
				array('name'),
				array(
					array('street1', 'street2', 'city'),
					array('state', 'zip', 'country'),
					)
				)
			);

		$out_buttons = $this->app->make('/html/list-inline')
			->add(
				$this->app->make('/html/element')->tag('input')
					->add_attr('type', 'submit')
					->add_attr('title', __('Add New Location', 'locatoraid') )
					->add_attr('value', __('Add New Location', 'locatoraid') )
					->add_attr('class', 'hc-theme-btn-submit')
					->add_attr('class', 'hc-theme-btn-primary')
					->add_attr('class', 'hc-xs-block')
				)
			;

		$link = $this->app->make('/http/uri')
			->url('/locations/add')
			;
		$out = $helper
			->render( array('action' => $link) )
			->add(
				$this->app->make('/html/list')
					->set_gutter(2)
					->add( $out_inputs )
					->add( $out_buttons )
				)
			;

		return $out;
	}
}