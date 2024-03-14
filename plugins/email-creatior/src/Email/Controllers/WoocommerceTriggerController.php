<?php

namespace WilokeEmailCreator\Email\Controllers;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use WilokeEmailCreator\Email\Shared\TraitHandleReplaceSubjectEmail;
use WilokeEmailCreator\Illuminate\Prefix\AutoPrefix;
use WilokeEmailCreator\Shared\RenderHtmlTemplate;
use WilokeEmailCreator\Shared\TraitGetCurrency;
use WilokeEmailCreator\Shared\TraitHandleGeneralSettings;
use WilokeEmailCreator\Shared\TraitHandleRulesTemplateEmail;
use WilokeEmailCreator\Shared\TraitProductOptions;
use WilokeEmailCreator\Templates\Shared\TraitHandleSaveEmailTypeUser;

class WoocommerceTriggerController
{
	private static ?WoocommerceTriggerController $oSelf = null;
	use TraitHandleSaveEmailTypeUser, TraitHandleReplaceSubjectEmail, TraitGetCurrency, TraitHandleGeneralSettings;
	use TraitProductOptions, TraitHandleGeneralSettings, TraitHandleRulesTemplateEmail;

	public string $subject = '';

	public static function initCreateSingleton(): ?WoocommerceTriggerController
	{
		if (self::$oSelf == null) {
			self::$oSelf = new self();
		}
		return self::$oSelf;
	}

	public function __construct()
	{
		add_action(WILOKE_EMAIL_CREATOR_HOOK_PREFIX . 'src/Email/Templates/admin-new-order.php',
			[$this, 'adminNewOrder']);
		add_action(WILOKE_EMAIL_CREATOR_HOOK_PREFIX . 'src/Email/Templates/admin-cancelled-order.php',
			[$this, 'adminCancelledOrder']);
		add_action(WILOKE_EMAIL_CREATOR_HOOK_PREFIX . 'src/Email/Templates/admin-failed-order.php',
			[$this, 'adminFailedOrder']);
		add_action(WILOKE_EMAIL_CREATOR_HOOK_PREFIX . 'src/Email/Templates/customer-processing-order.php',
			[$this, 'customerProcessingOrder']);
		add_action(WILOKE_EMAIL_CREATOR_HOOK_PREFIX . 'src/Email/Templates/customer-completed-order.php',
			[$this, 'customerCompletedOrder']);
		add_action(WILOKE_EMAIL_CREATOR_HOOK_PREFIX . 'src/Email/Templates/customer_on_hold_order.php',
			[$this, 'customerOnHoldOrder']);
		add_action(WILOKE_EMAIL_CREATOR_HOOK_PREFIX . 'src/Email/Templates/customer-refunded-order.php',
			[$this, 'customerRefundedOrder']);
		add_action(WILOKE_EMAIL_CREATOR_HOOK_PREFIX . 'src/Email/Templates/customer-note.php',
			[$this, 'customerNote']);
		add_action(WILOKE_EMAIL_CREATOR_HOOK_PREFIX . 'src/Email/Templates/customer-invoice.php',
			[$this, 'customerInvoice']);
		add_action(WILOKE_EMAIL_CREATOR_HOOK_PREFIX . 'src/Email/Templates/customer-new-account.php',
			[$this, 'customerNewAccount']);
		add_action(WILOKE_EMAIL_CREATOR_HOOK_PREFIX . 'src/Email/Templates/customer-reset-password.php',
			[$this, 'customerResetPassword']);
		add_action(WILOKE_EMAIL_CREATOR_HOOK_PREFIX . 'src/Email/Templates/resend-verification-key.php',
			[$this, 'resendVerificationKey']);
		add_action('woocommerce_thankyou', [$this, 'customWoocommerceAutoCompleteOrder']);
	}

	public function resendVerificationKey($aArgs)
	{
		$aUserEmailType = $this->getListEmailTypeUser();
		foreach ($aUserEmailType as $postID => $aItem) {
			if ($aItem['value'] != 'resend_verification_key') {
				continue;
			}
			$body = '';
			if (!isset($aArgs['email_body']) &&
				in_array(
					'sb-woocommerce-email-verification/sb-woocommerce-email-verification.php',
					get_option('active_plugins'))
			) {
				$oUser = get_user_by('login', $aArgs['user_login']);
				$verification_link = get_verification_link($oUser->user_login);
				$aConfig = get_option('sbwev_settings');
				$body = str_replace([
					'{{verification_link}}',
					'{{verification_url}}',
					'{{user_email}}',
					'{{user_login}}',
					'{{user_firstname}}',
					'{{user_lastname}}',
					'{{user_displayname}}'
				], [
					'<a target="_blank" href="' . $verification_link . '">' . $verification_link . '</a>',
					esc_url($verification_link),
					$oUser->user_email,
					$oUser->user_login,
					$oUser->user_firstname,
					$oUser->last_name,
					$oUser->display_name,
				], $aConfig['registration_email_body']);
			} else {
				$body = $aArgs['email_body'];
			}
			$aHtmlTemplate = get_post_meta($postID, AutoPrefix::namePrefix('html_template'), true);
			$rawHtml = str_replace(['__OPEN__', '__CLOSE__'], ['<', '>'], $aHtmlTemplate);

			echo str_replace('{{body_resend_verification_key}}', $body, $rawHtml);
		}
	}

	public function customWoocommerceAutoCompleteOrder($orderID)
	{
		if ($this->getAutomatically() == 'active') {
			$oOrder = wc_get_order($orderID);
			$orderStatus = $oOrder->get_status();
			if (($orderStatus == 'processing')) {
				$oOrder->update_status('completed');
			}
		}
	}

	/**
	 * @throws RuntimeError
	 * @throws SyntaxError
	 * @throws LoaderError
	 */
	public function adminNewOrder($aArgs)
	{
		$aUserEmailType = $this->getListEmailTypeUser();
		foreach ($aUserEmailType as $postID => $aItem) {
			if ($aItem['value'] != 'new_order') {
				continue;
			}
			echo $this->handleReplaceContentEmail($postID, $aArgs);
		}
	}

	/**
	 * @throws SyntaxError
	 * @throws RuntimeError
	 * @throws LoaderError
	 */
	public function handleReplaceContentEmail($postId, $aArgs)
	{
		$postId = (int)$postId;
		$aTemplate = $this->getDataTemplateWithTemplateId($postId);
		$aItemsOrder = $aArgs['order']->get_items();
		$aOrderProducts = $this->handleOrderProducts($aItemsOrder);
		$content = RenderHtmlTemplate::init()
			->renderHtmlTemplate($aTemplate['sections'], $aOrderProducts, $aArgs['order']);
		$aHtmlTemplate = get_post_meta($postId, AutoPrefix::namePrefix('html_template'), true);
		$rawHtml = str_replace(['__OPEN__', '__CLOSE__'], ['<', '>'], $aHtmlTemplate);
		return preg_replace('/ <!-- Start\/Content -->[\s\S]*<!-- End\/Content -->/', $content, $rawHtml);
	}

	public function handleOrderProducts($aRawOrderProducts): array
	{
		$aOrderProducts = [];
		foreach ($aRawOrderProducts as $oProduct) {
			$aData = $oProduct->get_data();
			$quantity = (int)$aData['quantity'];
			$productId = $oProduct->get_product_id();
			$aOrderProducts[] = $this->handleProductsWithListId($productId, $quantity);
		}
		return $aOrderProducts;
	}

	public function handleProductsWithListId($productId, $quantity): array
	{
		$oProduct = wc_get_product($productId);
		$price = (float)$oProduct->get_price();
		$compareAtPrice = (float)$oProduct->get_sale_price();

		$aCategories = get_the_terms($productId, 'product_cat');
		$aTerms = wp_get_post_terms($productId, 'product_tag');
		$aUpSellIds = get_post_meta($productId, '_upsell_ids', true);
		$aCrossSellIds = get_post_meta($productId, '_crosssell_ids', true);
		$aDataCategories = [];
		$aDataTags = [];
		if (!empty($aTerms)) {
			foreach ($aTerms as $oTerms) {
				$aDataTags[] = $oTerms->slug;
			}
		}
		if (!empty($aCategories)) {
			foreach ($aCategories as $aItem) {
				$aDataCategories[$aItem->term_id] = $aItem->name;
			}
		}

		return [
			'id'             => $productId,
			'image'          => get_the_post_thumbnail_url($productId),
			'title'          => get_the_title($productId),
			'price'          => wc_price($price),
			'compareAtPrice' => wc_price($compareAtPrice),
			'quantity'       => $quantity,
			'description'    => get_the_content($productId),
			'createdAt'      => get_the_date('Y-m-d', $productId),
			'updatedAt'      => get_the_modified_date('Y-m-d', $productId),
			'categories'     => array_values($aDataCategories),
			'catIds'         => array_keys($aDataCategories),
			'upsellIds'      => $aUpSellIds ?: [],
			'crosssellIds'   => $aCrossSellIds ?: [],
			'tags'           => $aDataTags,
			'link'           => get_permalink($productId),
		];
	}

	public function getDataTemplateWithTemplateId($templateID): array
	{
		return apply_filters(WILOKE_EMAIL_CREATOR_HOOK_PREFIX .
			'src/Email/Controllers/WoocommerceTriggerController/getDataTemplateWithTemplateId', [], $templateID);
	}

	/**
	 * @throws SyntaxError
	 * @throws RuntimeError
	 * @throws LoaderError
	 */
	public function adminCancelledOrder($aArgs)
	{
		$aUserEmailType = $this->getListEmailTypeUser();
		foreach ($aUserEmailType as $postID => $aItem) {
			if ($aItem['value'] != 'cancelled_order') {
				continue;
			}
			echo $this->handleReplaceContentEmail($postID, $aArgs);
		}
	}

	/**
	 * @throws SyntaxError
	 * @throws RuntimeError
	 * @throws LoaderError
	 */
	public function adminFailedOrder($aArgs)
	{
		$aUserEmailType = $this->getListEmailTypeUser();
		foreach ($aUserEmailType as $postID => $aItem) {
			if ($aItem['value'] != 'failed_order') {
				continue;
			}
			echo $this->handleReplaceContentEmail($postID, $aArgs);
		}
	}

	/**
	 * @throws SyntaxError
	 * @throws RuntimeError
	 * @throws LoaderError
	 */
	public function customerProcessingOrder($aArgs)
	{
		$aUserEmailType = $this->getListEmailTypeUser();
		foreach ($aUserEmailType as $postID => $aItem) {
			if ($aItem['value'] != 'customer_processing_order') {
				continue;
			}
			echo $this->handleReplaceContentEmail($postID, $aArgs);
		}
	}

	/**
	 * @throws SyntaxError
	 * @throws RuntimeError
	 * @throws LoaderError
	 */
	public function customerOnHoldOrder($aArgs)
	{
		$aUserEmailType = $this->getListEmailTypeUser();
		foreach ($aUserEmailType as $postID => $aItem) {
			if ($aItem['value'] != 'customer_on_hold_order') {
				continue;
			}
			echo $this->handleReplaceContentEmail($postID, $aArgs);
		}
	}

	/**
	 * @throws SyntaxError
	 * @throws RuntimeError
	 * @throws LoaderError
	 */
	public function customerCompletedOrder($aArgs)
	{
		$aUserEmailType = $this->getListEmailTypeUser();
		foreach ($aUserEmailType as $postID => $aItem) {
			if ($aItem['value'] != 'customer_completed_order') {
				continue;
			}
			echo $this->handleReplaceContentEmail($postID, $aArgs);
		}
	}

	/**
	 * @throws RuntimeError
	 * @throws SyntaxError
	 * @throws LoaderError
	 */
	public function customerRefundedOrder($aArgs)
	{
		$aUserEmailType = $this->getListEmailTypeUser();
		foreach ($aUserEmailType as $postID => $aItem) {
			if ($aItem['value'] != 'customer_refunded_order') {
				continue;
			}
			echo $this->handleReplaceContentEmail($postID, $aArgs);
		}
	}

	/**
	 * @throws RuntimeError
	 * @throws SyntaxError
	 * @throws LoaderError
	 */
	public function customerNote($aArgs)
	{
		$aUserEmailType = $this->getListEmailTypeUser();
		foreach ($aUserEmailType as $postID => $aItem) {
			if ($aItem['value'] != 'customer_note') {
				continue;
			}
			echo $this->handleReplaceContentEmail($postID, $aArgs);
		}
	}

	/**
	 * @throws RuntimeError
	 * @throws SyntaxError
	 * @throws LoaderError
	 */
	public function customerInvoice($aArgs)
	{
		$aUserEmailType = $this->getListEmailTypeUser();
		foreach ($aUserEmailType as $postID => $aItem) {
			if ($aItem['value'] != 'customer_invoice') {
				continue;
			}
			echo $this->handleReplaceContentEmail($postID, $aArgs);
		}
	}

	/**
	 * @throws RuntimeError
	 * @throws SyntaxError
	 * @throws LoaderError
	 */
	public function customerNewAccount($aArgs)
	{
		$aUserEmailType = $this->getListEmailTypeUser();
		foreach ($aUserEmailType as $key => $aItem) {
			if ($aItem['value'] != 'customer_new_account') {
				continue;
			}
			$aTemplate = $this->getDataTemplateWithTemplateId($key);
			$content = RenderHtmlTemplate::init()
				->renderHtmlTemplate($aTemplate['sections'], [], $aArgs['email']->object);
			$postId = (int)$key;
			$aHtmlTemplate = get_post_meta($postId, AutoPrefix::namePrefix('html_template'), true);
			$bodyHtml = str_replace(['__OPEN__', '__CLOSE__'], ['<', '>'], $aHtmlTemplate);
			echo preg_replace('/<!-- Start\/Content -->[\s\S]*<!-- End\/Content -->/', $content, $bodyHtml);
		}
	}

	/**
	 * @throws RuntimeError
	 * @throws SyntaxError
	 * @throws LoaderError
	 */
	public function customerResetPassword($aArgs)
	{
		$aUserEmailType = $this->getListEmailTypeUser();
		foreach ($aUserEmailType as $key => $aItem) {
			if ($aItem['value'] != 'customer_reset_password') {
				continue;
			}
			$aTemplate = $this->getDataTemplateWithTemplateId($key);
			update_user_meta($aArgs['email']->user_id, AutoPrefix::namePrefix('reset_key'), $aArgs['reset_key'] ?? '');
			$content = RenderHtmlTemplate::init()
				->renderHtmlTemplate($aTemplate['sections'], [], (new \WP_User($aArgs['email']->user_id)));
			$postId = (int)$key;
			$aHtmlTemplate = get_post_meta($postId, AutoPrefix::namePrefix('html_template'), true);
			$bodyHtml = str_replace(['__OPEN__', '__CLOSE__'], ['<', '>'], $aHtmlTemplate);
			echo preg_replace('/<!-- Start\/Content -->[\s\S]*<!-- End\/Content -->/', $content, $bodyHtml);
		}
	}
}
