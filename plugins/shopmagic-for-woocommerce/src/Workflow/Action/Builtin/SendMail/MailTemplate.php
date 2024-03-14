<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Action\Builtin\SendMail;

/**
 * Represents a template that can render a content inside.
 */
interface MailTemplate {

	/**
	 * Can wrap given content in a template
	 *
	 * @param string $html_content
	 * @param array  $args
	 *
	 * @return string
	 */
	public function wrap_content( string $html_content, array $args = [] ): string;
}
