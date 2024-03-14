<?php


namespace WilokeEmailCreator\Shared\Middleware\Middlewares;


use WilokeEmailCreator\Illuminate\Message\MessageFactory;
use WP_User;

class IsUserLoggedIn implements IMiddleware
{

	public function validation(array $aAdditional = []): array
	{
		if (!is_user_logged_in()) {
			return MessageFactory::factory()->error(
				esc_html__('Sorry, The account is not permission', 'emailcreator'),
				400
			);
		}

		return MessageFactory::factory()->success('Passed');
	}
}
