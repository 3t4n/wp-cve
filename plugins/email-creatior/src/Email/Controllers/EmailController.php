<?php

namespace WilokeEmailCreator\Email\Controllers;

use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use WilokeEmailCreator\Email\Shared\TraitHandleReplaceSubjectEmail;
use WilokeEmailCreator\Illuminate\Message\MessageFactory;
use WilokeEmailCreator\Illuminate\Prefix\AutoPrefix;
use WilokeEmailCreator\Shared\GetFieldPlaceholderSubjectEmail;
use WilokeEmailCreator\Shared\Middleware\TraitMainMiddleware;
use WilokeEmailCreator\Shared\RenderHtmlTemplate;
use WilokeEmailCreator\Shared\TraitGetUserIP;
use WilokeEmailCreator\Shared\TraitHandleRulesTemplateEmail;
use WilokeEmailCreator\Shared\TraitHandleScheduleCartAbandonment;

class EmailController
{
	use TraitMainMiddleware, TraitHandleReplaceSubjectEmail, TraitHandleScheduleCartAbandonment, TraitGetUserIP, TraitHandleRulesTemplateEmail;

	private array $aActivatePlugins = [];

	public function __construct()
	{
		add_action('wp_ajax_' . WILOKE_EMAIL_CREATOR_PREFIX . 'testingSendEmail', [$this, 'testingSendEmail']);
		add_action('wp_ajax_' . WILOKE_EMAIL_CREATOR_PREFIX . 'sendEmailTemplate', [$this, 'testingSendEmail']);
		add_action('wp_ajax_' . WILOKE_EMAIL_CREATOR_PREFIX . 'checkFormCreatedPurchaseCode',
			[$this, 'checkFormCreatedPurchaseCode']);
		add_filter(WILOKE_EMAIL_CREATOR_HOOK_PREFIX . 'src/Email/Controllers/EmailController/handleGetTemplateEmail',
			[$this, 'handleGetTemplateEmailWithPostID'], 10, 2);
		add_action('woocommerce_add_to_cart', [$this, 'handleActionAddToCart']);
		add_action($this->getScheduleKeyCartAbandonment(), [$this, 'handleSendEmailCartAbandonment'], 10, 2);
		add_action($this->getScheduleKeyCartAbandonmentWithUserGuest(), [$this, 'handleCartAbandonmentWithUserGuest'],
			10, 3);
		add_action('mskpss/after/subscribed', [$this, 'handleActionEmailCartAbandonmentWithUserGuest']);
		add_filter('wp_kses_allowed_html', [$this, 'handleAddAllowedHtml'], 10, 2);
		add_filter('wp_mail', [$this, 'handleEditHeadersMail'], 10, 1);
		add_action('init', [$this, 'maybePluginActive']);
	}


	public function maybePluginActive()
	{
		if (in_array('sb-woocommerce-email-verification/sb-woocommerce-email-verification.php',
			get_option('active_plugins'))
		) {
			add_filter(
				WILOKE_EMAIL_CREATOR_HOOK_PREFIX . 'src/Shared/TraitEmailTypes/addedEmailType',
				[$this, 'addTypeSbWoocommerceEmailVerification']
			);
		}
	}

	public function addTypeSbWoocommerceEmailVerification($aEmailType)
	{
		$aEmailType[] = [
			"label" => esc_html__("(SB WooCommerce) Resend Verification Key", "emailcreator"),
			"value" => "resend_verification_key",
			"type"  => "resend_verification_key"
		];

		return $aEmailType;
	}

	public function handleEditHeadersMail($aArgs)
	{
		$aArgs['headers'] = [
			'Content-Type: text/html; charset=UTF-8'
		];
		return $aArgs;
	}

	public function handleAddAllowedHtml($aAllowedPostTags, $context)
	{
		$aAllowedPostTags['body'] = [
			'class' => true,
			'style' => true
		];
		$aAllowedPostTags['html'] = [
			'xmlns'   => true,
			'xmlns:v' => true,
			'xmlns:o' => true,
		];
		$aAllowedPostTags['style'] = [
			'media' => true,
			'type'  => true,
		];
		$aAllowedPostTags['meta'] = [
			'name'       => true,
			'content'    => true,
			'http-equiv' => true,
		];
		$aAllowedPostTags['img'] = [
			'style'    => true,
			'height'   => true,
			'width'    => true,
			'src'      => true,
			'alt'      => true,
			'align'    => true,
			'border'   => true,
			'hspace'   => true,
			'loading'  => true,
			'longdesc' => true,
			'vspace'   => true,
			'usemap'   => true
		];
		$aAllowedPostTags['td'] = isset($aAllowedPostTags['td']) && is_array($aAllowedPostTags['td']) ? array_merge
		($aAllowedPostTags['td'], ['style' => true]) : ['style' => true];
		return $aAllowedPostTags;
	}

	/**
	 * @throws SyntaxError
	 * @throws RuntimeError
	 * @throws LoaderError
	 */
	public function handleCartAbandonmentWithUserGuest($postID, $aRawOrderProducts, $rawEmail)
	{
		$email = sanitize_email($rawEmail);
		$aTemplate = apply_filters(WILOKE_EMAIL_CREATOR_HOOK_PREFIX .
			'src/Email/Controllers/WoocommerceTriggerController/getDataTemplateWithTemplateId', [],
			$postID);
		$aOrderProducts = [];
		$orderTotal = 0;
		if (!empty($aRawOrderProducts)) {
			foreach ($aRawOrderProducts as $productID => $quantity) {
				$oProduct = wc_get_product($productID);
				$price = (float)$oProduct->get_price();
				$orderTotal += $price * $quantity;

				$aOrderProducts[] = WoocommerceTriggerController::initCreateSingleton()->handleProductsWithListId
				($productID, $quantity);
			}
		}
		$username = explode("@", $email);
		$aVariable = GetFieldPlaceholderSubjectEmail::getFieldPlaceholderCaseUserGuest($orderTotal, $username[0] ?? '');

		$content = RenderHtmlTemplate::init()
			->renderHtmlTemplate($aTemplate['sections'], $aOrderProducts, [], $aVariable);
		$aHtmlTemplate = get_post_meta($postID, AutoPrefix::namePrefix('html_template'), true);
		$html = str_replace(['__OPEN__', '__CLOSE__'], ['<', '>'], $aHtmlTemplate);
		$bodyHtml = preg_replace('/ <!-- Start\/Content -->[\s\S]*<!-- End\/Content -->/', $content, $html);
		$subject = $this->handleSubject($postID, $aVariable);
		if (is_email($email)) {
			$this->actionSendEmail($email, $subject, $bodyHtml);
		}
	}

	public function handleActionEmailCartAbandonmentWithUserGuest($aData)
	{
		if (isset($aData['isCampaignShowOnPageCart']) && $aData['isCampaignShowOnPageCart']) {
			$email = $aData['email'] ?? '';
			$ip = $aData['ip'] ?? '';
			$aKeyEmailType = ReplaceEmailController::initCreateSingleton()->handleListKeyEmailTypeUsedUser();
			if (!empty($postID = array_search('cart_abandonment', $aKeyEmailType))) {
				$addedToCartXMinutes = $this->getAddedToCartXMinutes($postID);
				$time = strtotime('+' . $addedToCartXMinutes . ' minutes', time());
				$this->setScheduleCartAbandonmentWithUserGuest($time, $postID, get_option($ip), $email);
			}
		}
	}

	/**
	 * @throws SyntaxError
	 * @throws RuntimeError
	 * @throws LoaderError
	 */
	public function handleSendEmailCartAbandonment($postId, $orderID)
	{
		$oOrder = wc_get_order($orderID);
		$aTemplate = apply_filters(WILOKE_EMAIL_CREATOR_HOOK_PREFIX .
			'src/Email/Controllers/WoocommerceTriggerController/getDataTemplateWithTemplateId', [],
			$postId);
		$aOrderProducts = WoocommerceTriggerController::initCreateSingleton()
			->handleOrderProducts($oOrder->get_items());
		$content = RenderHtmlTemplate::init()
			->renderHtmlTemplate($aTemplate['sections'], $aOrderProducts, [], $oOrder);
		$aHtmlTemplate = get_post_meta($postId, AutoPrefix::namePrefix('html_template'), true);
		$html = str_replace(['__OPEN__', '__CLOSE__'], ['<', '>'], $aHtmlTemplate);
		$bodyHtml = preg_replace('/ <!-- Start\/Content -->[\s\S]*<!-- End\/Content -->/', $content, $html);
		$subject = $this->handleSubject($postId, $oOrder);
		$email = $oOrder->get_data()['billing']['email'];
		if (is_email($email)) {
			$this->actionSendEmail(sanitize_email($email), $subject, $bodyHtml);
		}
	}

	public function handleSubject($postID, $oOrder)
	{
		$aVariables = GetFieldPlaceholderSubjectEmail::getFieldPlaceholder($oOrder);
		$emailSubject = get_post_meta($postID, AutoPrefix::namePrefix('emailSubject'), true);
		return str_replace(array_keys($aVariables), array_values($aVariables), $emailSubject);
	}

	public function handleActionAddToCart()
	{
		global $woocommerce;
		$aItems = $woocommerce->cart->get_cart();
		$aDataOrder = [];
		foreach ($aItems as $aOder) {
			$aDataOrder [$aOder['product_id']] = $aOder['quantity'];
		}
		if (!empty($aDataOrder)) {
			update_option($this->getUserIp(), $aDataOrder);
		}
	}

	public function handleGetTemplateEmailWithPostID(array $aResponse, array $aParam): array
	{
		try {
			if (!empty($aResponse)) {
				return $aResponse;
			}

			if (!isset($aParam['postID'])) {
				throw new Exception(esc_html__('The $aParam[\'postID\'] is required!', 'emailcreator'), 401);
			}
			$postId = abs($aParam['postID']);
			$aResponseMiddleware = $this->processMiddleware(
				[
					'IsPostExistMiddleware'
				],
				[
					'postID' => $postId
				]
			);
			if ($aResponseMiddleware['status'] == 'error') {
				throw new Exception($aResponseMiddleware['message'], 401);
			}
			$aHtmlTemplate = get_post_meta($postId, AutoPrefix::namePrefix('html_template'), true);
			$rawHtml = preg_replace('/ <!-- Start\/Content -->[\s\S]*<!-- End\/Content -->/', '',
				$aHtmlTemplate);


			return MessageFactory::factory()->success(esc_html__('Fount it', ''), [
				'html' => str_replace(['__OPEN__', '__CLOSE__'], ['<', '>'], $rawHtml)
			]);
		}
		catch (Exception $exception) {
			return MessageFactory::factory()->error($exception->getMessage(), $exception->getCode());
		}
	}

	/**
	 * @throws Exception
	 */

	public function testingSendEmail()
	{
		try {
			$aData = $_POST ?? [];

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
			if (!isset($aData['email'])) {
				throw new Exception(esc_html__('The email is required', "emailcreator"), 401);
			}
			if (!isset($aData['emailSubject'])) {
				throw new Exception(esc_html__('The email subject is required', "emailcreator"), 401);
			}
			$subject = $aData['emailSubject'];
			$email = sanitize_email(wp_unslash($aData['email']));
			$rawBody = stripslashes(html_entity_decode(sanitize_text_field(htmlentities($aData['html'])))) ?? '';
			if (!is_email($email)) {
				throw new Exception(esc_html__('Please enter a valid official email address', "emailcreator"),
					401);
			}

			$result = $this->actionSendEmail($email, $subject, $rawBody);
			$isReview = get_option(AutoPrefix::namePrefix('review'));
			if (empty($isReview)) {
				update_option(AutoPrefix::namePrefix('review'), true);
			}
			if ($result) {
				MessageFactory::factory('ajax')->success(esc_html__('Email was sent successfully',
					"emailcreator"), [
					'isReview' => empty($isReview)
				]);
			}
			throw new Exception(
				'error-config-mail',
				401
			);
		}
		catch (Exception $exception) {
			MessageFactory::factory('ajax')->error($exception->getMessage(), $exception->getCode());
		}
	}

	public function actionSendEmail(
		$email, $subject, $body, $aHeader
	= [
		'Content-Type: text/html; charset=UTF-8'
	]
	)
	{
		return wp_mail(
			sanitize_email($email),
			wp_kses(html_entity_decode(wp_unslash($subject)), [
				"img" => [
					"src"        => true,
					"alt"        => true,
					"class"      => true,
					"aria-label" => true,
					"loading"    => true
				]
			]),
			$body,
			$aHeader
		);
	}
}
