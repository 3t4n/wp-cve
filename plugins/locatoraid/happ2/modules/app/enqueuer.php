<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class App_Enqueuer_HC_MVC extends _HC_MVC
{
	protected $scripts = array();
	protected $localize_scripts = array();
	protected $enqueue_scripts = array();
	protected $styles = array();
	protected $enqueue_styles = array();

	public function single_instance()
	{
	}

	public function _init()
	{
		static $already = FALSE;
		if( ! $already ){
			$this->app
				->after( $this, $this )
				;
		}
		$already = TRUE;
		return $this;
	}

	public function register_script( $handle, $path )
	{
		$this->scripts[ $handle ] = $path;

		$this->app
			->after( array($this, __FUNCTION__), $handle, $path )
			;

		return $this;
	}

	public function register_style( $handle, $path )
	{
		$this->styles[ $handle ] = $path;

		$this->app
			->after( array($this, __FUNCTION__), $handle, $path )
			;

		return $this;
	}

	public function localize_script( $handle, $params )
	{
		if( array_key_exists($handle, $this->localize_scripts) ){
			$this->localize_scripts[$handle] = array_merge( $this->localize_scripts[$handle], $params );
		}
		else {
			$this->localize_scripts[$handle] = $params;
		}

		$this->app
			->after( array($this, __FUNCTION__), $handle, $params )
			;

		return $this;
	}

	public function enqueue_script( $handle )
	{
		$this->enqueue_scripts[ $handle ] = $handle;

		$this->app
			->after( array($this, __FUNCTION__), $handle )
			;

		return $this;
	}

	public function enqueue_style( $handle, $prepend = FALSE )
	{
		if( $prepend ){
			$add = array(
				$handle => $handle,
				);
			$this->enqueue_styles = array_merge( $add, $this->enqueue_styles );
		}
		else {
			$this->enqueue_styles[$handle] = $handle;
		}

		$this->app
			->after( array($this, __FUNCTION__), $handle )
			;

		return $this;
	}

	public function get_localize_scripts()
	{
		return $this->localize_scripts;
	}

	public function get_scripts()
	{
		$return = array();

		foreach( $this->enqueue_scripts as $handle ){
			if( ! array_key_exists($handle, $this->scripts) ){
				continue;
			}
			$src = $this->scripts[$handle];
			$return[ $handle ] = $src;
		}

		if( isset($return['jquery']) ){
			$jquery = $return['jquery'];
			unset( $return['jquery'] );
			$return = array('jquery' => $jquery) + $return;
		}

		return $return;
	}

	public function get_styles()
	{
		$return = array();

		foreach( $this->enqueue_styles as $handle ){
			if( ! array_key_exists($handle, $this->styles) ){
				continue;
			}
			$src = $this->styles[$handle];
			$return[ $handle ] = $src;
		}

		return $return;
	}
}