<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Html_Icon_HC_MVC extends _HC_MVC
{
	protected $icon = NULL;

	public function __toString()
	{
		return '' . $this->render();
	}

	public function icon( $icon = NULL )
	{
		$icon_for = $this->app->config->get('icons');
		if( isset($icon_for[$icon]) ){
			$icon = $icon_for[$icon];
		}
		$this->icon = $icon;
		return $this;
	}

	public function render()
	{
		switch( $this->icon ){
			case 'spinner':
				$return = $this->app->make('/html/icon')->icon('spin')
					->render()
					;

				$return = $this->app->make('/html/element')->tag('div')
					->add( $return )
					->add_attr('class', 'hc-spin')
					->add_attr('class', 'hc-inline-block')
					->add_attr('class', 'hc-m0')
					->add_attr('class', 'hc-p0')
					;
				break;

			default:
				$return = $this->icon;
		}

	// should be extended by concrete icon modules
		$return = $this->app
			->after( $this, $return, $this )
			;

		return $return;
	}
}
