<?php


namespace WilokeEmailCreator\Templates\Services\Post;


use Exception;
use WilokeEmailCreator\Illuminate\Message\MessageFactory;
use WilokeEmailCreator\Shared\Post\IDeleteUpdateService;
use WilokeEmailCreator\Shared\Post\TraitIsPostAuthor;
use WilokeEmailCreator\Shared\Post\TraitIsPostType;
use WP_Post;

class DeletePostService implements IDeleteUpdateService
{
	use TraitIsPostAuthor;
	use TraitIsPostType;

	private string $postID;

	public function setID($id): self
	{
		$this->postID = $id;

		return $this;
	}


	public function delete(): array
	{
		try {

			$oPost = wp_delete_post($this->postID,true);

			if ($oPost instanceof WP_Post) {
				return MessageFactory::factory()->success(esc_html__('Congrats, the project has been deleted.',
					'emailcreator'), [
					'id' => $oPost->ID
				]);
			}

			return MessageFactory::factory()->error(esc_html__('Sorry, We could not delete this project.',
				'emailcreator'), 400);
		}
		catch (Exception $oException) {
			return MessageFactory::factory()->error(
				$oException->getMessage(),
				$oException->getCode()
			);
		}

	}
}
