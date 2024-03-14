<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Setup_Wordpress_Index_View_HC_MVC extends _HC_MVC
{
	public function render()
	{
		$form = $this->app->make('/conf/controller')
			->form('wordpress-users')
			;

		$return = $this->app->make('/html/list')
			->set_gutter(2)
			;

		$nts_app_title = isset($this->app->app_config['nts_app_title']) ? $this->app->app_config['nts_app_title'] : '';
		if( $nts_app_title ){
			$header1 = $this->app->make('/html/element')->tag('h1')
				->add( $nts_app_title )
				;
			$return
				->add( $header1 )
				;
		}

		$header = $this->app->make('/html/element')->tag('h2')
			->add( __('Installation', 'locatoraid') )
			;
		$return
			->add( $header )
			;

		$model = $this->app->make('/setup/model');
		$old_version = $model->get_old_version();

		if( $old_version ){
			$link = $this->app->make('/http/uri')
				->url('setup/upgrade')
				;
			$return->add(
				$this->app->make('/html/element')->tag('a')
					->add_attr('href', $link)
					->add('You seem to have an older version already installed. Please click here to upgrade.')
				);
			$return->add(
				'Or continue below to install from scratch.'
				);
		}

		$link = $this->app->make('/http/uri')
			->url('setup/run')
			;

		$display_form = $this->app->make('/html/view/form')
			->add_attr('action', $link )
			->set_form( $form )
			;

		$label = $this->app->make('/html/element')->tag('h4')
			->add( __('Please define which WordPress user roles will be able to access the plugin.', 'locatoraid') )
			;

		$display_form
			->add( $label )
			;

		$inputs = $form->inputs();
		foreach( $inputs as $input_name => $input ){
			$row = $this->app->make('/html/label-input')
				->set_label( $input->label() )
				->set_content( $input )
				->set_error( $input->error() )
				;
			$display_form
				->add( $row )
				;
		}

		$buttons = $this->app->make('/html/list-inline')
			->set_gutter(2)
			;

		$buttons->add(
			$this->app->make('/html/element')->tag('input')
				->add_attr('type', 'submit')
				->add_attr('title', __('Click To Proceed', 'locatoraid') )
				->add_attr('value', __('Click To Proceed', 'locatoraid') )
				->add_attr('class', 'hc-theme-btn-submit')
				->add_attr('class', 'hc-theme-btn-primary')
			);
		$display_form->add( $buttons );

		$return
			->add( $display_form )
			;

		return $return;
	}
}