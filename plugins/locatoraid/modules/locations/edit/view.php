<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Edit_View_LC_HC_MVC extends _HC_MVC
{
	public function render( $model )
	{
		$id = $model['id'];

		$form = $this->app->make('/locations/edit/form');
		$helper = $this->app->make('/form/helper');

		$inputs_view = $helper->prepare_render( $form->inputs(), $model );
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
			->set_gutter(2)
			;

		$out_buttons->add(
			$this->app->make('/html/element')->tag('input')
				->add_attr('type', 'submit')
				->add_attr('title', __('Save', 'locatoraid') )
				->add_attr('value', __('Save', 'locatoraid') )
				->add_attr('class', 'hc-theme-btn-submit')
				->add_attr('class', 'hc-theme-btn-primary')
			);

		$out_buttons->add(
			$this->app->make('/html/ahref')
				->to('/locations/' . $model['id'] . '/delete')
				->add_attr('class', 'hcj2-confirm')
				->add( __('Delete', 'locatoraid') )
				->add_attr('class', 'hc-right')
				->add_attr('class', 'hc-theme-btn-submit')
				->add_attr('class', 'hc-theme-btn-danger')
			);

		$link = $this->app->make('/http/uri')
			->url('/locations/' . $id . '/update')
			;

		$shortcode_view = $this->app->make('/html/label-input')
			->set_label( __('Shortcode', 'locatoraid') )
			->set_content( '[locatoraid id="' . $model['id'] . '"]' )
			;

		$out = $helper
			->render( array('action' => $link) )
			->add(
				$this->app->make('/html/list')
					->set_gutter(2)
					->add( $out_inputs )
					->add( $out_buttons )
					->add( $shortcode_view )
				)
			;

		$return = $this->app
			->after( $this, $out, $model )
			;

		return $return;
	}
}