<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\MailTracking;

class EmailStatsCounter {

	private $opens = 0;
	private $clicks = 0;
	private $sent = 0;

	public function increase_open() {
		$this->opens ++;
	}

	public function increase_click() {
		$this->clicks ++;
	}

	public function increase_sent() {
		$this->sent ++;
	}

	public function get_opens(): int {
		return $this->opens;
	}

	public function get_clicks(): int {
		return $this->clicks;
	}

	public function get_sent(): int {
		return $this->sent;
	}

	public function get_open_rate(): float {
		return $this->opens / $this->sent;
	}

	public function get_click_rate(): float {
		return $this->clicks / $this->sent;
	}
}
