<?php


namespace WilokeEmailCreator\Shared\Post\Query;


use WilokeEmailCreator\Illuminate\Message\MessageFactory;
use WilokeEmailCreator\Illuminate\Prefix\AutoPrefix;
use WP_Query;


class QueryPost
{

	protected array     $aArgs           = [];
	protected string    $postType        = '';
	protected ?WP_Query $oQuery;
	protected bool      $isStatusConfig  = false;
	protected bool      $isSetCountItems = false;
	private array       $aRawArgs        = [];

	public function setRawArgs(array $aRawArgs): IQueryPost
	{
		$this->aRawArgs = $aRawArgs;
		return $this;
	}

	public function commonParseArgs(): array
	{
		$aArgs = wp_parse_args($this->aRawArgs, $this->defineArgs());
		if (isset($this->aRawArgs['parent']) && $this->aRawArgs['parent'] == 0) {
			$this->aArgs['post_parent'] = 0;
		}

		if (isset($aArgs['status']) && !empty($aArgs['status'])) {
			if (($aArgs['status'] != 'any') && in_array($aArgs['status'], ['active', 'deactive'])) {
				$this->aArgs['post_status'] = $aArgs['status'] == 'active' ? 'publish' : 'draft';
			} elseif ($aArgs['status'] == 'trash') {
				$this->aArgs['post_status'] = 'trash';
			} else {
				$this->aArgs['post_status'] = ['draft', 'publish'];
			}
			unset($aArgs['status']);
		} else {
			$this->aArgs['post_status'] = ['draft', 'publish'];
		}

		if (isset($aArgs['emailType']) && is_array($aArgs['emailType'])) {
			$this->aArgs['meta_query'] = [
				[
					'key'     => AutoPrefix::namePrefix('emailType'),
					'value'   => json_encode($aArgs['emailType']),
					'compare' => 'LIKE'
				]
			];
		}

		if (isset($aArgs['posts_per_page']) && $aArgs['posts_per_page'] <= 50) {
			$this->aArgs['posts_per_page'] = $aArgs['posts_per_page'];
		} else {
			$this->aArgs['posts_per_page'] = 200;
		}
		unset($aArgs['limit']);

		if (isset($aArgs['page']) && $aArgs['page']) {
			$this->aArgs['paged'] = $aArgs['page'];
		} else {
			$this->aArgs['paged'] = 1;
		}

		if (isset($aArgs['s'])) {
			$this->aArgs['s'] = $aArgs['s'];
			unset($aArgs['s']);
		}
		if (!empty($aArgs['postType'])) {
			$this->aArgs['post_type'] = $aArgs['postType'];
		}

		if (!empty($aArgs['ids'])) {
			if (is_array($aArgs['ids'])) {
				$this->aArgs['post__in'] = $aArgs['ids'];
			} else {
				$this->aArgs['post__in'] = explode(',', $aArgs['ids']);
			}
		} else {
			if (!empty($aArgs['id'])) {
				$this->aArgs['p'] = $aArgs['id'];
			}
		}

		unset($aArgs['ids']);
		unset($aArgs['id']);

		return $this->aArgs;
	}

	private function defineArgs(): array
	{
		return [
			'posts_per_page' => 50,
			'paged'          => 1,
			'orderby'        => 'name',
			'order'          => 'DESC',
			'status'         => 'any',
		];
	}

	/**
	 * @param PostSkeleton $oPostSkeleton
	 * @param string $pluck
	 * @param bool $isSingle
	 *
	 * @return array
	 */
	public function query(PostSkeleton $oPostSkeleton, string $pluck = '', bool $isSingle = false): array
	{
		$this->oQuery = new WP_Query($this->aArgs);
		$aResponse['maxPages'] = 0;
		$aResponse['items'] = [];
		if (!$this->oQuery->have_posts()) {
			wp_reset_postdata();

			return MessageFactory::factory()->success(
				esc_html__('We found no items', 'emailcreator'),
				$aResponse
			);
		}

		$aItems = [];
		while ($this->oQuery->have_posts()) {
			$this->oQuery->the_post();
			$aItems[] = $oPostSkeleton->setPost($this->oQuery->post)->getPostData($pluck);
		}
		wp_reset_postdata();
		$aResponse['maxPages'] = $this->oQuery->max_num_pages;
		if (!$isSingle) {
			$aResponse['items'] = $aItems;
		} else {
			$aResponse = array_merge($aItems[0], $aResponse);
		}

		if ($isSingle) {
			unset($aResponse['maxPages']);
		}

		return MessageFactory::factory()->success(
			sprintf(esc_html__('We found %s items', 'emailcreator'), count($aItems)),
			$aResponse
		);
	}

	public function getArgs(): array
	{
		return $this->aArgs;
	}

	public function setCountItems(bool $isSetCountItems): QueryPost
	{
		if ($isSetCountItems) {
			$this->isSetCountItems = $isSetCountItems;
		}
		return $this;
	}
}
