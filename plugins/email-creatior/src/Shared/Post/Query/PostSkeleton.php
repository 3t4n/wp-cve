<?php


namespace WilokeEmailCreator\Shared\Post\Query;


use WilokeEmailCreator\Illuminate\Prefix\AutoPrefix;
use WilokeEmailCreator\Shared\TraitHandleCheckSectionsDataObjects;
use WilokeEmailCreator\Shared\TraitHandleRulesTemplateEmail;
use WP_Post;

class PostSkeleton
{
	use TraitHandleCheckSectionsDataObjects, TraitHandleRulesTemplateEmail;

	protected array   $aPluck
		= [
			'id',
			'BeId',
			'label',
			'date',
			'status',
			'sections',
			'htmlTemplate',
			'postType',
			'emailType',
			'image',
			'emailSubject',
			'imageBase64',
			'background',
			'rules',
			'html',
			'package'
		];
	protected WP_Post $oPost;

	public function getSections(): array
	{
		$jSections = get_post_meta($this->getBeId(), AutoPrefix::namePrefix('sections'), true);
		$aSections = !empty($jSections) ? json_decode(base64_decode($jSections), true) : [];
		return $this->handleCheckSectionsDataObjects($aSections);
	}

	public function getHtml()
	{
		$rawHtml = get_post_meta($this->getBeId(), AutoPrefix::namePrefix('html_template'), true);
		return str_replace(['__OPEN__', '__CLOSE__'], ['<', '>'], $rawHtml);
	}

	public function getEmailSubject()
	{
		return get_post_meta($this->getBeId(), AutoPrefix::namePrefix('emailSubject'), true) ?: '';
	}

	public function getBackground()
	{
		return get_post_meta($this->getBeId(), AutoPrefix::namePrefix('background'), true) ?: '';
	}

	public function getId()
	{
		return get_post_meta($this->getBeId(), AutoPrefix::namePrefix('feId'), true) ?: '';
	}

	public function getImage()
	{
		return $this->getImageBase64();
	}

	public function getHtmlTemplate()
	{
		return get_post_meta($this->getBeId(), AutoPrefix::namePrefix('html_template'), true) ?: '';
	}

	public function getPackage()
	{
		return get_post_meta($this->getBeId(), AutoPrefix::namePrefix('package'), true) ?: '';
	}

	public function getImageBase64()
	{
		return get_post_meta($this->getBeId(), AutoPrefix::namePrefix('imageBase64'), true) ?: '';
	}

	public function getEmailType()
	{
		$jConfig = get_post_meta($this->getBeId(), AutoPrefix::namePrefix('emailType'), true);
		return !empty($jConfig) ? json_decode($jConfig, true) : [];
	}

	public function getPostType()
	{
		return AutoPrefix::namePrefix('my_projects');
	}

	public function checkMethodExists($pluck): bool
	{
		$method = 'get' . ucfirst($pluck);

		return method_exists($this, $method);
	}

	public function getBeId(): int
	{
		return $this->oPost->ID;
	}

	public function getLabel(): string
	{
		return $this->oPost->post_title;
	}

	public function getStatus(): string
	{
		return ($this->oPost->post_status == 'publish') ? 'enabled' : 'disabled';
	}

	public function getDate(): string
	{
		return (string)strtotime(date(get_option('date_format'), strtotime($this->oPost->post_date)));
	}

	public function getRules(): array
	{
		$postID = $this->getBeId();
		return [
			'ruleCategories'          => $this->getRuleCategories($postID),
			'ruleCountries'           => $this->getRuleCountries($postID),
			'ruleMaxOrder'            => $this->getRuleMaxOrder($postID),
			'ruleMinOrder'            => $this->getRuleMinOrder($postID),
			'addedToCartXMinutes'     => $this->getAddedToCartXMinutes($postID),
			'afterOrderStatusPending' => $this->getAfterOrderStatusPending($postID),
			'afterOrderStatusFailed'  => $this->getAfterOrderStatusFailed($postID)
		];
	}

	public function setPost(WP_Post $oPost): PostSkeleton
	{
		$this->oPost = $oPost;

		return $this;
	}

	public function getPostData($pluck, array $aAdditionalInfo = []): array
	{
		$aData = [];

		if (empty($pluck)) {
			$aPluck = $this->aPluck;
		} else {
			$aPluck = $this->sanitizePluck($pluck);
		}

		foreach ($aPluck as $pluck) {
			$method = 'get' . ucfirst($pluck);
			if (method_exists($this, $method)) {
				$aData[$pluck] = call_user_func_array([$this, $method], [$aAdditionalInfo]);
			}
		}

		return $aData;
	}

	private function sanitizePluck($rawPluck): array
	{
		$aPluck = is_array($rawPluck) ? $rawPluck : explode(',', $rawPluck);
		return array_map(function ($pluck) {
			return trim($pluck);
		}, $aPluck);
	}
}
