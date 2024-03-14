<?php

namespace IfSo\PublicFace\Services\TriggersService\Filters;

require_once('filter.interface.php');

abstract class FilterBase implements IFilter {

	public function change_text($text) {
		return $text;
	}

	abstract public function before_apply();
	abstract public function after_apply();
}