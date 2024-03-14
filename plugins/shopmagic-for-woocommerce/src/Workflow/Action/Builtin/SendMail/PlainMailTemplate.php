<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Action\Builtin\SendMail;

/**
 * Plain content as is.
 */
final class PlainMailTemplate implements MailTemplate {
	public const NAME = 'plain';

	public function wrap_content( string $html_content, array $args = [] ): string {
		return $html_content;
	}

}
