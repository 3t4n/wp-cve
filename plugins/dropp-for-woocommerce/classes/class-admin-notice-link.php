<?php

namespace Dropp;

class Admin_Notice_Link
{
    public function __construct(public string $text, public string $url)
    {
    }

	public function render(): string
	{
		return sprintf(
			'<a href="%s">%s</a>',
			esc_attr($this->url),
			esc_html($this->text)
		);
	}
}
