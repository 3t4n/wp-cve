<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Html_Collapse_HC_MVC extends _HC_MVC
{
	protected $title = NULL;
	protected $content = NULL;

	public function set_title( $title )
	{
		$this->title = $title;
		return $this;
	}

	public function set_content( $content )
	{
		$this->content = $content;
		return $this;
	}

	public function render()
	{
		$out = $this->app->make('/html/element')->tag('div')
			->add_attr('class', 'hcj2-collapse-container')
			;

		$trigger = $this->app->make('/html/element')->tag('a')
			->add( $this->title )
			->add_attr('title', strip_tags($this->title) )

			->add_attr('href', '#')
			->add_attr('class', 'hcj2-collapse-next')
			// ->add_attr('class', 'hc-btn')
			;

		$content = $this->app->make('/html/element')->tag('div')
			->add( $this->content )
			->add_attr('class', 'hcj2-collapse')

			->add_attr('class', 'hc-mt1')
			;

		$out
			->add( $trigger )
			->add( $content )
			;

		return $out;
	}
}