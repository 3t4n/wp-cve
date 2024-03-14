<?php

namespace WilokeEmailCreator\Shared\Middleware\Middlewares;

use Exception;
use WilokeEmailCreator\Illuminate\Message\MessageFactory;

class IsPostTypeExistMiddleware implements IMiddleware
{

	/**
	 * @throws Exception
	 */
	public function validation(array $aAdditional = []): array
	{
		$postID = $aAdditional['postID'] ?? '';
		$postType = $aAdditional['postType'] ?? '';
		if (empty($postID)) {
			throw new Exception(esc_html__('Sorry, the id is required', 'emailcreator'),400);
		}
		if (get_post_field('post_type', $postID) != $postType) {
			throw new Exception(sprintf(esc_html__('Unfortunately, this item is not a %s',
				'emailcreator'), $postType));
		}
		return MessageFactory::factory()->success('Passed');
	}
}
