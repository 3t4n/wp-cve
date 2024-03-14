<?php

namespace WilokeEmailCreator\Templates\Controllers;

use Exception;
use WilokeEmailCreator\Illuminate\Message\MessageFactory;
use WilokeEmailCreator\Illuminate\Prefix\AutoPrefix;
use WilokeEmailCreator\Shared\Helper;
use WilokeEmailCreator\Shared\Middleware\TraitMainMiddleware;
use WilokeEmailCreator\Shared\Post\Query\PostSkeleton;
use WilokeEmailCreator\Shared\TraitHandleCheckSectionsDataObjects;
use WilokeEmailCreator\Shared\TraitHandleRulesTemplateEmail;
use WilokeEmailCreator\Templates\Model\TemplateModel;
use WilokeEmailCreator\Templates\Services\Post\CreatePostService;
use WilokeEmailCreator\Templates\Services\Post\DeletePostService;
use WilokeEmailCreator\Templates\Services\Post\TemplateQueryService;
use WilokeEmailCreator\Templates\Services\Post\UpdatePostService;
use WilokeEmailCreator\Templates\Services\PostMeta\AddPostMetaService;
use WilokeEmailCreator\Templates\Services\PostMeta\UpdatePostMetaService;
use WilokeEmailCreator\Templates\Shared\TraitGetPostsTemplates;
use WilokeEmailCreator\Templates\Shared\TraitHandleSaveEmailTypeUser;


class TemplateController
{
	use TraitMainMiddleware, TraitHandleSaveEmailTypeUser, TraitGetPostsTemplates, TraitHandleRulesTemplateEmail,
		TraitHandleCheckSectionsDataObjects;

	public function __construct()
	{
		add_action('wp_ajax_' . WILOKE_EMAIL_CREATOR_PREFIX . 'createTemplate', [$this, 'createTemplate']);
		add_action('wp_ajax_' . WILOKE_EMAIL_CREATOR_PREFIX . 'updateTemplate', [$this, 'updateTemplate']);
		add_action('wp_ajax_' . WILOKE_EMAIL_CREATOR_PREFIX . 'deleteTemplate', [$this, 'deleteTemplate']);
		add_action('wp_ajax_' . WILOKE_EMAIL_CREATOR_PREFIX . 'checkPackage', [$this, 'checkPackage']);
		add_action('wp_ajax_' . WILOKE_EMAIL_CREATOR_PREFIX . 'duplicateTemplate', [$this, 'duplicateTemplate']);
		add_action('wp_ajax_' . WILOKE_EMAIL_CREATOR_PREFIX . 'checkEmailTypeExist', [$this, 'checkEmailTypeExist']);
		add_action('wp_ajax_' . WILOKE_EMAIL_CREATOR_PREFIX . 'updateForceActive', [$this, 'updateForceActive']);
		add_action('wp_ajax_' . WILOKE_EMAIL_CREATOR_PREFIX . 'updateStatusTemplate',
			[$this, 'updateStatusTemplate']);
		add_filter(WILOKE_EMAIL_CREATOR_HOOK_PREFIX . 'src/DataFactory/Config/DataImportService/getCustomerTemplates',
			[$this, 'getMyTemplates']);
		add_filter(WILOKE_EMAIL_CREATOR_HOOK_PREFIX . 'src/Dashboard/DashboardControllers/getTemplateDetail',
			[$this, 'getMyTemplate'], 10, 2);
		add_filter(WILOKE_EMAIL_CREATOR_HOOK_PREFIX .
			'src/Email/Controllers/WoocommerceTriggerController/getDataTemplateWithTemplateId',
			[$this, 'getMyTemplate'], 10, 2);
		add_filter(WILOKE_EMAIL_CREATOR_HOOK_PREFIX .
			'src/Email/Controllers/TemplateController/getDataTemplates',
			[$this, 'getDataTemplates'], 10, 2);
	}
	/*
	 * data $aArgs:
	 * postId: string (is template id )
	 * emailType: string (type template)
	 * status: 'active|deactive|any' (status template)
	 * limit : int (number template, default 10)
	 * pluck : string ( default: label,emailType,image,BeId,emailSubject,background,package,html)
	 */
	public function getDataTemplates($aResponse, $aArgs)
	{
		$slug = $aArgs['pluck'] ?? 'label,emailType,image,BeId,emailSubject,background,package,html';
		if (isset($aArgs['emailType'])) {
			$aPostIds = TemplateModel::getListIdTemplateWishTypeEmail($aArgs['emailType']);
			$aArgsQuery = [
				'ids'            => $aPostIds,
				'posts_per_page' => $aArgs['limit'] ?? 10
			];
			if (isset($aArgs['status'])) {
				$aArgsQuery['status'] = $aArgs['status'];
			}
			$aResponse = $this->handleGetDataTemplates(
				$aArgsQuery,
				$slug
			);
		}
		if (isset($aArgs['postId'])) {
			$aResponseData = $this->handleGetDataTemplates(
				[
					'id'             => (int)$aArgs['postId'],
					'posts_per_page' => $aArgs['limit'] ?? 10
				],
				$slug
			);
			$aResponse = $aResponseData['data']['items'][0] ?? [];
		}
		return $aResponse;
	}

	public function checkPackage()
	{
		$plan = Helper::getPackagePlan();
		if ($plan == 'free') {
			return MessageFactory::factory('ajax')->error('not', 401);
		} else {
			return MessageFactory::factory('ajax')->success('Passed');
		}
	}

	public function updateStatusTemplate()
	{
		try {
			$aData = $_POST ?? [];
			if (!isset($aData['beId'])) {
				throw new Exception(esc_html__('The beId is required', 'emailcreator'));
			}

			$aPostResponse = (new UpdatePostService())
				->setID($aData['beId'])
				->setRawData([
					'status' => $aData['status'],
				])
				->performSaveData();

			if ($aPostResponse['status'] == 'error') {
				throw new Exception($aPostResponse['message'], $aPostResponse['code']);
			}
			$aEmailType = json_decode(get_post_meta((int)$aData['beId'], AutoPrefix::namePrefix('emailType'), true)
				?: []);
			$this->updateListEmailTypeUser([
				$aData['beId'] => $aEmailType
			]);
			return MessageFactory::factory('ajax')->success(esc_html__('Passed', 'emailcreator'), [
				'id' => $aData['beId']
			]);
		}
		catch (Exception $exception) {
			return MessageFactory::factory('ajax')->error($exception->getMessage(), $exception->getCode());
		}
	}

	public function updateForceActive()
	{
		try {
			$aData = $_POST ?? [];
			if (!isset($aData['ids'])) {
				throw new Exception(esc_html__('The ids is required', 'emailcreator'));
			}

			$aIds = array_map(function ($id) {
				return (int)$id;
			}, explode(',', $aData['ids']));

			foreach ($aIds as $id) {
				$aPostResponse = (new UpdatePostService())
					->setID($id)
					->setRawData([
						'status' => 'disabled',
					])
					->performSaveData();

				if ($aPostResponse['status'] == 'error') {
					throw new Exception($aPostResponse['message'], $aPostResponse['code']);
				}
				$this->deleteListEmailTypeUserAfterDeletePost([
					$id => ''
				]);
			}

			return MessageFactory::factory('ajax')->success(esc_html__('found it', "emailcreator"), [
				'ids' => $aData['ids']
			]);
		}
		catch (Exception $exception) {
			return MessageFactory::factory('ajax')->error($exception->getMessage(), $exception->getCode());
		}
	}

	public function checkEmailTypeExist()
	{
		try {
			$aData = $_POST ?? [];

			if (!isset($aData['emailType'])) {
				throw new Exception(esc_html__('The emailType is required', "emailcreator"));
			}
			$aArgs = [
				'meta_key'     => AutoPrefix::namePrefix('topicEmailType'),
				'meta_value'   => $aData['emailType']['value'],
				'meta_compare' => '='
			];
			if (isset($aData['id']) && !empty($aData['id'])) {
				$aArgs['post__not_in'] = [$aData['id']];
			}
			$aResponse = $this->getTemplatesOfEmailType($aArgs);

			return MessageFactory::factory('ajax')->success(esc_html__('found it'),
				[
					'isExist' => $aResponse['isExist'],
					'ids'     => $aResponse['ids'],
					'feIds'   => $aResponse['feIds'],
					'titles'  => $aResponse['titles']
				]
			);
		}
		catch (Exception $exception) {
			return MessageFactory::factory('ajax')->error($exception->getMessage(), $exception->getCode());
		}
	}

	public function getMyTemplates()
	{
		return $this->handleGetDataTemplates([]);
	}

	public function getMyTemplate($aData, $templateId)
	{
		if (isset($templateId)) {
			$aResponse = $this->handleGetDataTemplates([
				'id' => $templateId
			], 'id,label,emailType,image,BeId,sections,emailSubject,background,rules,package');
			$aData = $aResponse['data']['items'][0] ?? [];
		}
		return $aData;
	}

	private function handleGetDataTemplates(
		array $aArgs,
		      $pluck = 'id,label,emailType,image,BeId,background,status'
	)
	{
		try {
			$aArgs = wp_parse_args($aArgs, [
				'postType' => AutoPrefix::namePrefix('templates')
			]);
			$aResponse = (new TemplateQueryService())
				->setRawArgs(
					$aArgs
				)
				->parseArgs()
				->query(new PostSkeleton(), $pluck);

			if ($aResponse['status'] == 'error') {
				throw new Exception(esc_html__('Sorry, We could not find your product',
					"emailcreator"), 401);
			}
			$maxPages = $aResponse['data']['maxPages'];
			$items = $aResponse['data']['items'];
			$maxPosts = count($items);

			return MessageFactory::factory()->success($aResponse['message'],
				[
					'maxPages' => $maxPages,
					'maxPosts' => $maxPosts,
					'items'    => $items
				]
			);
		}
		catch (Exception $exception) {
			return MessageFactory::factory()->error($exception->getMessage(), $exception->getCode());
		}
	}

	public function handleValidateHtmlTemplate($aContentData)
	{
		return isset(
			$aContentData['html']) ? str_replace(['<', '>'], ['__OPEN__', '__CLOSE__'],
			$aContentData['html']
		) : '';
	}

	public function createTemplate()
	{
		try {
			$aContentData = json_decode(file_get_contents('php://input'), true);
			$aData = $aContentData['data'];
			$aData['html_template'] = $this->handleValidateHtmlTemplate($aContentData);
			$aResponseMiddleware = $this->processMiddleware(
				[
					'IsUserLoggedIn'
				],
				[
					'userID' => get_current_user_id()
				]
			);
			if ($aResponseMiddleware['status'] == 'error') {
				throw new Exception($aResponseMiddleware['message'], 401);
			}
			if (empty($aData['image'])) {
				throw new Exception(esc_html__('The image src is required', "emailcreator"), 401);
			}

			$aPostResponse = (new CreatePostService())
				->setRawData([
					'label'  => $aData['label'],
					'status' => 'enabled'
				])
				->performSaveData();

			if ($aPostResponse['status'] == 'error') {
				throw new Exception($aPostResponse['message'], $aPostResponse['code']);
			}

			$beID = $aPostResponse['data']['id'];
			$aPostMetaResponse = (new AddPostMetaService())
				->setID($beID)
				->setRawData([
					'sections'      => base64_encode(json_encode($aData['sections'] ?? [])),
					'html_template' => $aData['html_template'] ?? '',
					'package'       => $aData['package'] ?? '',
					'emailType'     => json_encode($aData['emailType'] ?? []),
					'background'    => $aData['background'] ?? [],
					'feId'          => $aData['id'] ?? '',
					'emailSubject'  => $aData['emailSubject'] ?? '',
					'imageBase64'   => $aData['image']
				])
				->performSaveData();
			if (isset($aData['rules'])) {
				$this->handleRulesTemplateEmail($beID, $aData['rules']);
			}

			if (isset($aData['emailType']['value'])) {
				// handle update topic email type
				update_post_meta($beID, AutoPrefix::namePrefix('topicEmailType'), $aData['emailType']['value']);
			}
			if ($aPostMetaResponse['status'] == 'error') {
				throw new Exception($aPostMetaResponse['message'], $aPostMetaResponse['code']);
			}

			$this->updateListEmailTypeUser([
				$beID => $aData['emailType']
			]);
			return MessageFactory::factory('ajax')->success($aPostResponse['message'],
				[
					'BeId'       => $beID,
					'id'         => $aData['id'] ?? '',
					'image'      => $aData['image'],
					'sections'   => $aData['sections'] ?? [],
					'emailType'  => $aData['emailType'] ?? '',
					'background' => $aData['background'] ?? [],
					'label'      => get_the_title($aPostResponse['data']['id']),
					'rules'      => $aData['rules']
				]
			);
		}
		catch (Exception $exception) {
			return MessageFactory::factory('ajax')->error($exception->getMessage(), $exception->getCode());
		}
	}

	public function updateTemplate()
	{
		try {
			$aRawData = json_decode(file_get_contents('php://input'), true);
			$aData = $aRawData['data'];
			$aData['html_template'] = $this->handleValidateHtmlTemplate($aRawData);
			$aResponseMiddleware = $this->processMiddleware(
				[
					'IsUserLoggedIn',
					'IsPostExistMiddleware'
				],
				[
					'userID' => get_current_user_id(),
					'postID' => $aData['BeId']
				]
			);

			if ($aResponseMiddleware['status'] == 'error') {
				throw new Exception($aResponseMiddleware['message'], 401);
			}

			if (empty($aData['image'])) {
				throw new Exception(esc_html__('The image src is required', 'wiloke-email-template'), 401);
			}

			$aPostResponse = (new UpdatePostService())
				->setID($aData['BeId'])
				->setRawData([
					'label' => $aData['label'],
				])
				->performSaveData();

			if ($aPostResponse['status'] == 'error') {
				throw new Exception($aPostResponse['message'], $aPostResponse['code']);
			}
			$beID = $aPostResponse['data']['id'];
			$aPostMetaResponse = (new UpdatePostMetaService())
				->setID($beID)
				->setRawData([
					'sections'      => base64_encode(json_encode($aData['sections'] ?? [])),
					'html_template' => $aData['html_template'] ?? '',
					'emailType'     => json_encode($aData['emailType'] ?? []),
					'background'    => $aData['background'] ?? [],
					'feId'          => $aData['id'] ?? '',
					'emailSubject'  => $aData['emailSubject'] ?? '',
					'imageBase64'   => $aData['image']
				])
				->performSaveData();

			if ($aPostMetaResponse['status'] == 'error') {
				throw new Exception($aPostMetaResponse['message'], $aPostMetaResponse['code']);
			}
			if (isset($aData['emailType']['value'])) {
				// handle update topic email type
				update_post_meta($beID, AutoPrefix::namePrefix('topicEmailType'), $aData['emailType']['value']);
			}
			if (isset($aData['rules'])) {
				$this->handleRulesTemplateEmail($beID, $aData['rules']);
			}

			$this->updateListEmailTypeUser([
				$beID => $aData['emailType']
			]);
			return MessageFactory::factory('ajax')->success($aPostResponse['message'],
				[
					'BeId'       => (int)$beID,
					'id'         => $aData['id'] ?? '',
					'image'      => $aData['image'],
					'sections'   => $aData['sections'] ?? [],
					'emailType'  => $aData['emailType'] ?? '',
					'background' => $aData['background'] ?? [],
					'label'      => get_the_title($aPostResponse['data']['id']),
					'rules'      => $aData['rules']
				]
			);
		}
		catch (Exception $exception) {
			return MessageFactory::factory('ajax')->error($exception->getMessage(), $exception->getCode());
		}
	}

	/**
	 * @throws Exception
	 */
	public function handleValidated($aRawData, $aKeyFields): array
	{
		$aData = [];
		foreach ($aKeyFields as $key => $isRequired) {
			if ($isRequired) {
				if (!isset($aRawData[$key])) {
					throw new Exception(sprintf(esc_html__('The %s is required', "emailcreator"), $key));
				}
				if (empty($aRawData[$key])) {
					throw new Exception(sprintf(esc_html__('The %s is not empty', "emailcreator"), $key));
				}
			}
			$data = $aRawData[$key] ?? null;
			if (empty($data)) {
				continue;
			}
			if (in_array($key, ['image', 'emailSubject'])) {
				$aData[$key] = $data;
				continue;
			}
			switch (gettype($data)) {
				case 'string':
					$aData[$key] = sanitize_title($data);
					break;
				case 'integer':
				case 'double':
					$aData[$key] = abs($data);
					break;
				case 'array':
					if ($key == 'sections') {
						$aData[$key] = $data;
						break;
					}
					$aData[$key] = $this->handleValidatedRecursion($data);
					break;
				case 'json':
					$aData[$key] = is_array($data) ? $data : [];
					break;
				default:
					$aData[$key] = $data;
			}
		}
		return $aData;
	}

	public function removeScript($html): string
	{
		return str_replace(["<script"], ["&lt;script"], $html);
	}

	public function handleValidatedRecursion($data)
	{
		switch (gettype($data)) {
			case 'string':
				$responseData = sanitize_text_field($data);
				break;
			case 'integer':
			case 'double':
				$responseData = abs($data);
				break;
			case 'array':
				$aData = [];
				foreach ($data as $key => $value) {
					if (in_array($key, ['template', 'twig'])) {
						$aData[$key] = $value;
					} else {
						$aData[$key] = $this->handleValidatedRecursion($value);
					}
				}
				$responseData = $aData;
				break;
			case 'json':
			default:
				$responseData = $data;
				break;
		}
		return $responseData;
	}

	public function deleteTemplate()
	{
		try {
			$aData = $_POST ?? [];
			if (empty($aData)) {
				$aContentData = json_decode(file_get_contents('php://input'), true);
				$aData = $this->handleValidated($aContentData['data'], [
					'BeId' => 'int'
				]);
			}
			$aResponseMiddleware = $this->processMiddleware(
				[
					'IsUserLoggedIn',
					'IsPostExistMiddleware'
				],
				[
					'userID' => get_current_user_id(),
					'postID' => abs($aData['BeId'])
				]
			);

			if ($aResponseMiddleware['status'] == 'error') {
				throw new Exception($aResponseMiddleware['message'], 401);
			}

			$aPostResponse = (new DeletePostService())
				->setID($aData['BeId'])
				->delete();

			if ($aPostResponse['status'] == 'error') {
				throw new Exception($aPostResponse['message'], $aPostResponse['code']);
			}
			$this->deleteListEmailTypeUserAfterDeletePost([
				$aData['BeId'] => ""
			]);
			return MessageFactory::factory('ajax')->success($aPostResponse['message'],
				[
					'id' => (int)$aPostResponse['data']['id']
				]
			);
		}
		catch (Exception $exception) {
			return MessageFactory::factory('ajax')->error($exception->getMessage(), $exception->getCode());
		}
	}

	public function getSections(int $postID): array
	{
		$jSections = get_post_meta($postID, AutoPrefix::namePrefix('sections'), true);
		$aSections = !empty($jSections) ? json_decode(base64_decode($jSections), true) : [];
		return $this->handleCheckSectionsDataObjects($aSections);
	}

	public function duplicateTemplate()
	{
		try {
			$aContentData = json_decode(file_get_contents('php://input'), true);
			$aData = $aContentData['data'] ?? [];
			if (isset($aData['BeId'])) {
				$beID = (int)$aData['BeId'];
				$aData['html_template'] = $aContentData;
				$aData['sections'] = $this->getSections($beID);
			}
			$aResponseMiddleware = $this->processMiddleware(
				[
					'IsUserLoggedIn'
				],
				[
					'userID' => get_current_user_id()
				]
			);

			if ($aResponseMiddleware['status'] == 'error') {
				throw new Exception($aResponseMiddleware['message'], 401);
			}

			$aPostResponse = (new CreatePostService())
				->setRawData([
					'label'  => $aData['label'],
					'status' => 'disabled'
				])
				->performSaveData();

			if ($aPostResponse['status'] == 'error') {
				throw new Exception($aPostResponse['message'], $aPostResponse['code']);
			}
			$beID = $aPostResponse['data']['id'];
			$feId = uniqid($aData['id']);
			$emailSubject = get_post_meta($feId, AutoPrefix::namePrefix('emailSubject'), true);
			$aPostMetaResponse = (new AddPostMetaService())
				->setID($beID)
				->setRawData([
					'sections'      => base64_encode(json_encode($aData['sections'] ?? [])),
					'html_template' => $aData['html_template'] ?? '',
					'emailType'     => json_encode($aData['emailType'] ?? []),
					'feId'          => $feId,
					'background'    => $aData['background'] ?? [],
					'emailSubject'  => $emailSubject ?? '',
					'imageBase64'   => $aData['image']
				])
				->performSaveData();

			if ($aPostMetaResponse['status'] == 'error') {
				throw new Exception($aPostMetaResponse['message'], $aPostMetaResponse['code']);
			}
			$this->updateListEmailTypeUser([
				$beID => $aData['emailType']
			]);
			return MessageFactory::factory('ajax')->success($aPostResponse['message'],
				[
					'BeId'       => (int)$beID,
					'id'         => $feId ?? '',
					'status'     => 'disabled',
					'image'      => $aData['image'],
					'sections'   => $aData['sections'] ?? [],
					'background' => $aData['background'] ?? [],
					'emailType'  => $aData['emailType'] ?? '',
					'label'      => get_the_title($aPostResponse['data']['id'])
				]
			);
		}
		catch (Exception $exception) {
			return MessageFactory::factory('ajax')->error($exception->getMessage(), $exception->getCode());
		}
	}
}
