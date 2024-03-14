<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Http_View_Response_HC_MVC extends _HC_MVC
{
	protected $view = NULL;
	protected $redirect = NULL;
	protected $params = array();
	protected $status_code = NULL;

	public function __toString()
	{
		return '' . $this->render();
	}

	public function set_view( $view )
	{
		$this->view = $view;
		return $this;
	}

	public function set_redirect( $redirect )
	{
		$this->redirect = $redirect;
		return $this;
	}

	public function redirect()
	{
		return $this->redirect;
	}

	public function view()
	{
		return $this->view;
	}

	public function set_param( $k, $v )
	{
		$this->params[$k] = $v;
		return $this;
	}
	public function param( $k )
	{
		$return = isset($this->params[$k]) ? $this->params[$k] : NULL;
		return $return;
	}

	public function set_status_code( $status_code )
	{
		$this->status_code = $status_code;
		return $this;
	}

	public function status_code()
	{
		return $this->status_code;
	}

	public function prepare_redirect( $to )
	{
		$to = $this->app
			->after( array($this, __FUNCTION__), $to )
			;
		return $to;
	}

	public function render()
	{
		$code = $this->status_code();
		if( $code ){
			hc_http_status_code( $code );
		}

		$view = $this->view();
		$redirect = $this->redirect();
		if( $redirect ){
			if( ! HC_Lib2::is_full_url($redirect) ){
				$redirect = $this->app->make('/http/uri')
					->url($redirect)
					;
			}

			$redirect = $this
				->prepare_redirect( $redirect )
				;

			if( 1 OR (! headers_sent()) ){
				// wordpress?
				if( defined('WPINC') ){
					wp_redirect( $redirect );
				}
				else {
					header('Location: ' . $redirect);
				}
			}
			else {
				$html = "<META http-equiv=\"refresh\" content=\"0;URL=$redirect\">";
				echo $html;
				exit;
			}
		}
		elseif( $view ){
			if( is_object($view) && method_exists($view, 'render') ){
				return $view->render();
			}
			else {
				return $view;
			}
		}
	}
}