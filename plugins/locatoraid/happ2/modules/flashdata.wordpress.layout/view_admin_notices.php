<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Flashdata_Wordpress_Layout_View_Admin_Notices_HC_MVC extends _HC_MVC
{
	public function render()
	{
		$session = $this->app->make('/session/lib');

		$message = $session->flashdata('message');
		$error = $session->flashdata('error');
		$warning = $session->flashdata('warning');

		$form_errors = $session->flashdata('form_errors');
		$debug = $session->flashdata('debug');

		$out = NULL;

		if( $form_errors OR $error OR $message OR $warning ){
			$out = $this->app->make('/html/list')
				->set_gutter(1)
				;
		}

		if( $form_errors ){
			$this_out = $this->app->make('/html/element')->tag('div')
				->add_attr('class', 'hc-p2')
				->add_attr('class', 'notice')
				->add_attr('class', 'notice-error')
				->add_attr('class', 'is-dismissible')
				;
			$this_out
				->add(
					$this->app->make('/html/element')->tag('p')
						->add( __('Please correct the form errors and try again', 'locatoraid') )
					)
				;
			$out
				->add( $this_out )
				;
		}

		if( $error ){
			if( ! is_array($error) ){
				$error = array( $error );
			}

			$this_out = $this->app->make('/html/element')->tag('div')
				->add_attr('class', 'hc-p2')
				->add_attr('class', 'notice')
				->add_attr('class', 'notice-error')
				->add_attr('class', 'is-dismissible')
				;
			foreach( $error as $e ){
				$this_out
					->add(
						$this->app->make('/html/element')->tag('p')
							->add( $e )
						)
					;
			}
			$out
				->add( $this_out )
				;
		}

		if( $message ){
			if( ! is_array($message) ){
				$message= array( $message );
			}

			$this_out = $this->app->make('/html/element')->tag('div')
				->add_attr('class', 'hc-p2')
				->add_attr('class', 'notice')
				->add_attr('class', 'notice-success')
				->add_attr('class', 'is-dismissible')
				;

			foreach( $message as $e ){
				$this_out
					->add(
						$this->app->make('/html/element')->tag('p')
							->add( $e )
						)
					;
			}
			$out
				->add( $this_out )
				;
		}

		if( $warning ){
			if( ! is_array($warning) ){
				$warning= array( $warning );
			}

			$this_out = $this->app->make('/html/element')->tag('div')
				->add_attr('class', 'hc-p2')
				->add_attr('class', 'notice')
				->add_attr('class', 'notice-warning')
				->add_attr('class', 'is-dismissible')
				;

			foreach( $warning as $e ){
				$this_out
					->add(
						$this->app->make('/html/element')->tag('p')
							->add( $e )
						)
					;
			}
			$out
				->add( $this_out )
				;
		}

		if( $out ){
			echo $out;
		}
	}
}