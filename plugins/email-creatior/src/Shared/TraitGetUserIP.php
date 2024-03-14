<?php

namespace WilokeEmailCreator\Shared;

trait TraitGetUserIP
{
	public function getUserIp(): string
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = sanitize_text_field($_SERVER['HTTP_CLIENT_IP']);
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = sanitize_text_field($_SERVER['HTTP_X_FORWARDED_FOR']);
		} else {
			$ip = sanitize_text_field($_SERVER['REMOTE_ADDR']);
		}
		return $ip;
	}
}
