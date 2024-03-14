<?php

namespace WilokeEmailCreator\Shared\Middleware\Middlewares;

use Exception;
use WilokeEmailCreator\Illuminate\Message\MessageFactory;

class IsPostAuthorMiddleware implements IMiddleware
{
	/**
	 * @throws Exception
	 */
	public function validation(array $aAdditional = []): array
	{
		$postID = $aAdditional['postID'] ?? '';
		$userID = $aAdditional['userID'] ?? '';
		if (empty($postID)) {
			throw new Exception(esc_html__('Sorry, the post id is required', 'emailcreator'), 400);
		}
		if (empty($userID)) {
			throw new Exception(esc_html__('Sorry, the user id is required', 'emailcreator'),400);
		}
		if (get_post_field('post_author', $postID) != $userID) {
			throw new Exception(esc_html__('Unfortunately, You were not post author',
				'emailcreator'));
		}
		return MessageFactory::factory()->success('Passed');
	}
}
