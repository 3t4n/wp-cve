<?php

namespace IfSo\PublicFace\Services\TriggersService\Handlers;

require_once('chain-of-responsibility.interface.php');

abstract class ChainHandlerBase implements IChainOfResponsibility {
	private $next_handler;
	
	public function set_next($next_handler) {
		$this->next_handler = $next_handler;
		return $next_handler;
	}
	
	public function handle_next($request) {
		if ($this->next_handler != NULL) {
			return $this->next_handler->handle($request);
		} else {
			return null;
		}
	}
	
	abstract public function handle($request);
}