<?php

namespace WilokeEmailCreator\Shared\Middleware\Middlewares;


use Exception;
use WilokeEmailCreator\Illuminate\Message\MessageFactory;
use WilokeEmailCreator\Shared\Helper;

class IsUserPackageFreeMiddleware implements IMiddleware
{

	/**
	 * @throws Exception
	 */
	public function validation(array $aAdditional = []): array
	{
		$plan = Helper::getPackagePlan();
		if ($plan != 'free') {
			throw new Exception(esc_html__('Sorry, the free id is required', 'emailcreator'), 400);
		}
		return MessageFactory::factory()->success('Passed');
	}
}
