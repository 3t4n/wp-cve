<?php

namespace IfSo\PublicFace\Services\TriggersService\Filters;

interface IFilter {
	public function change_text($text);
	public function before_apply();
	public function after_apply();
}