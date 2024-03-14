<?php
declare(strict_types=1);

namespace TreBiMeteo\Http;

final class StatusCode {

	const OK = 200;
	const UNAUTHORIZED = 401;

	const MESSAGES = [
		StatusCode::OK => '',
	];
}
