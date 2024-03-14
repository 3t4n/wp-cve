<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Html_Ahref_HC_MVC extends _HC_MVC
{
	protected $el = NULL;
	protected $to = array( NULL, array() );
	protected $outside = FALSE;

	public function _init()
	{
		$this->el = $this->app->make('/html/element')->tag('a');
		return $this;
	}

	public function __toString()
	{
		return '' . $this->render();
	}

	public function set_outside( $outside = TRUE )
	{
		$this->outside = $outside;
		return $this;
	}

	public function is_outside()
	{
		return $this->outside;
	}

	public function href()
	{
		list( $slug, $params ) = $this->to;

		if( HC_Lib2::is_full_url($slug) ){
			return $slug;
		}

	// check if is allowed
		$root = $this->app->make('/root/link');
		$slug = $root->execute( $slug );
		if( ! $slug ){
			return;
		}

		$return = $this->app->make('/http/uri')
			->mode('web')
			->url( $slug, $params )
			;

		return $return;
	}

	public function render()
	{
		$href = $this->href();
		if( ! $href ){
			return;
		}

		$this->el
			->reset_attr('href')
			->add_attr('href', $href)
			;
		return $this->el->render();
	}

	public function to( $slug = '/', $params = array() )
	{
		$this->to = array( $slug, $params );
		return $this;
	}

	public function add( $child ){
		$this->el->add( $child );
		return $this;
	}

	public function add_attr( $key, $value )
	{
		$this->el->add_attr( $key, $value );
		return $this;
	}

	public function attr( $key = NULL )
	{
		return $this->el->attr( $key );
	}

	public function content()
	{
		return $this->el->content();
	}
}