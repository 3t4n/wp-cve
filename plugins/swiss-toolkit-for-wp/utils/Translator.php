<?php
	// If uninstall not called from WordPress, then exit.
	if (!defined('ABSPATH')) {
		exit;
	}

	class Translator
	{
		public static function encode(string ...$items): string
		{
			$single = "";
			foreach ($items as $item) {
				$single .= $item . " ";
			}

			return trim(base64_encode($single));
		}

		public static function decode(string $data): array
		{
			$decodedData = base64_decode($data);
            return explode(" ", $decodedData);
		}
	}
