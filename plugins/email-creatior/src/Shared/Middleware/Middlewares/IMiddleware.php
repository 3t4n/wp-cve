<?php


namespace WilokeEmailCreator\Shared\Middleware\Middlewares;


interface IMiddleware {
	public function validation(array $aAdditional= []): array;
}
