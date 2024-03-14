<?php

namespace Dropp;

use JetBrains\PhpStorm\ArrayShape;

class Admin_Notice
{
	protected bool $enabled   = false;
	protected bool $dismissed = false;

	public function __construct(public string $subject, public string $message, public array $replacements)
	{
	}

	public function render(string $code): string
	{
		$args = [
			sprintf(
				'<div id="%s" class="dropp-admin-notice %s"><p><strong>%s</strong><hr>%s</p></div>',
				esc_attr($code),
				'notice notice-error is-dismissible',
				esc_html($this->subject),
				nl2br(esc_html($this->message))
			)
		];

		/** @var Admin_Notice_Link $replacement */
		foreach ($this->replacements as $replacement) {
			$args[] = $replacement->render();
		}

		return call_user_func_array('sprintf', $args);
	}

	public function is_enabled(): bool
	{
		return $this->enabled;
	}

	public function is_dismissed(): bool
	{
		return $this->dismissed;
	}

	public function enable($enabled = true): static
	{
		$this->enabled = $enabled;
		return $this;
	}

	public function dismiss($dismissed = true): static
	{
		$this->dismissed = $dismissed;
		return $this;
	}

	#[ArrayShape(['dismissed' => "bool", 'enabled' => "bool"])]
	public function get_options(): array
	{
		return [
			'dismissed' => $this->dismissed,
			'enabled' => $this->enabled,
		];
	}

	public function set_options(mixed $notice_options): bool
	{
		$this->dismissed = $notice_options['dismissed'];
		$this->enabled = $notice_options['enabled'];
		return true;
	}
}
