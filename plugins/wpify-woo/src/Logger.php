<?php

namespace WpifyWoo;

use WpifyWooDeps\Wpify\Core\Abstracts\AbstractLogger;
use WpifyWooDeps\Wpify\Core\WordpressMonologHandler;

class Logger extends AbstractLogger {
	public function channel(): string {
		return 'wpify-woo';
	}

	public function handler() {
		$wordPressHandler              = new WordpressMonologHandler( $this->wpdb, $this->table(), array(), $this::DEBUG );
		$wordPressHandler->initialized = true;

		return $wordPressHandler;
	}

	public function table(): string {
		return 'wpify_woo_log';
	}
}
