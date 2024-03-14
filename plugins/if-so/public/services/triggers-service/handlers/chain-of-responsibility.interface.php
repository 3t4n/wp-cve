<?php

namespace IfSo\PublicFace\Services\TriggersService\Handlers;

interface IChainOfResponsibility {
	public function set_next($next);
	public function handle_next($request);
}