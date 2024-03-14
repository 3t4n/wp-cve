<?php

namespace IfSo\PublicFace\Services\TriggersService\Filters\Hooks;

interface IHook {
	public function apply($text, $rule);
}