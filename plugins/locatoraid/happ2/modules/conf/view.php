<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Conf_View_HC_MVC extends _HC_MVC
{
	public function render( $form, $to )
	{
		$values = $this->app->make('/app/settings')
			->get()
			;

		$helper = $this->app->make('/form/helper');
		$inputs_view = $helper->prepare_render( $form->inputs(), $values );
		$out_inputs = $helper->render_inputs( $inputs_view );

		$out_buttons = $this->app->make('/html/list')
			->set_gutter(2)
			->add(
				$this->app->make('/html/element')->tag('input')
					->add_attr('type', 'submit')
					->add_attr('title', __('Save', 'locatoraid') )
					->add_attr('value', __('Save', 'locatoraid') )
					->add_attr('class', 'hc-theme-btn-submit')
					->add_attr('class', 'hc-theme-btn-primary')
					->add_attr('class', 'hc-block')
				)
			;

		$link = $this->app->make('/http/uri')
			->url( $to )
			;

		$out = $helper
			->render( array('action' => $link) )
			->add( 
				$this->app->make('/html/grid')
					->set_gutter(2)
					->add( $out_inputs, 9, 12 )
					->add( $out_buttons, 3, 12 )
				)
			;


		return $out;
	}
}