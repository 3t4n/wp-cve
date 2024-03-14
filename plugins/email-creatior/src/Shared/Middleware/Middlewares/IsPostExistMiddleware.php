<?php


namespace WilokeEmailCreator\Shared\Middleware\Middlewares;


use Exception;
use WilokeEmailCreator\Illuminate\Message\MessageFactory;


class IsPostExistMiddleware implements IMiddleware
{
	protected array $aStatusBadge = ['publish', 'draft', 'trash'];

	/**
	 * @throws Exception
	 */
	public function validation(array $aAdditional = []): array
	{
		$postID = $aAdditional['postID'] ?? '';
		if (empty($postID)) {
			throw new Exception(esc_html__('Sorry, the id is required', 'emailcreator'), 400);
		}
		if (!in_array(get_post_status($postID), $this->aStatusBadge)) {
			throw new Exception(esc_html__('Sorry, the project doest not exist at the moment', 'emailcreator'),400);
		}

		return MessageFactory::factory()->success('Passed');
	}
}
