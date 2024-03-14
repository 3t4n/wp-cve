<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */

namespace InvisibleReCaptcha\MchLib\Exceptions;

class MchLibException extends \Exception
{
	public function __construct($errMessage, $flag = 0)
	{
		parent::__construct($errMessage, $flag);
	}
}