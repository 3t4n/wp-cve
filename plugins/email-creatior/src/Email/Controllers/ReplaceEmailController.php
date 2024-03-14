<?php

namespace WilokeEmailCreator\Email\Controllers;

use WilokeEmailCreator\Email\Shared\TraitHandleReplaceSubjectEmail;
use WilokeEmailCreator\Illuminate\Prefix\AutoPrefix;
use WilokeEmailCreator\Shared\GetFieldPlaceholderSubjectEmail;
use WilokeEmailCreator\Shared\Helper;
use WilokeEmailCreator\Shared\Middleware\TraitMainMiddleware;
use WilokeEmailCreator\Shared\TraitHandleRulesTemplateEmail;
use WilokeEmailCreator\Shared\TraitHandleScheduleCartAbandonment;
use WilokeEmailCreator\Templates\Shared\TraitHandleSaveEmailTypeUser;
use WP_User;

class ReplaceEmailController
{
	private static ?ReplaceEmailController $oSelf = null;
	use TraitHandleReplaceSubjectEmail, TraitHandleSaveEmailTypeUser, TraitHandleRulesTemplateEmail,
		TraitHandleScheduleCartAbandonment, TraitMainMiddleware;

	public array $convertMiddleware
		= [
			'ruleCategories' => 'IsApplyBillingToCategoriesMiddleware',
			'ruleCountries'  => 'IsApplyToBillingCountriesMiddleware',
			'ruleMaxOrder'   => 'IsApplyBillingToMaxOrderMiddleware',
			'ruleMinOrder'   => 'IsApplyBillingToMinOrderMiddleware',
		];

	public function __construct()
	{
		add_action('woocommerce_email', [$this, 'getEmailIds'], 10);
		add_filter('wc_get_template', [$this, 'replaceEmails'], 99, 3);
		add_action('woocommerce_thankyou', [$this, 'handleCartAbandonment'], 10, 1);
		add_action('woocommerce_order_status_failed', [$this, 'handleCartAbandonmentOrderFailed'], 10, 2);
	}

	public function handleCartAbandonmentOrderFailed($orderID, $oOrder)
	{
		$aKeyEmailType = $this->handleListKeyEmailTypeUsedUser();
		if (!empty($postID = array_search('cart_abandonment', $aKeyEmailType))) {
			if ($this->getIsAfterOrderStatusFailed($postID)) {
				$this->handleSetScheduleCartAbandonment($postID, $orderID);
			}
		}
	}

	public function getEmailIds($oEmail)
	{
		if (is_array($oEmail->emails)) {
			foreach ($oEmail->emails as $email) {
				$replaceRecipientMethod = 'replaceRecipient' . ucfirst(Helper::snakeToCamel($email->id));
				if (method_exists($this, $replaceRecipientMethod)) {
					add_filter(
						'woocommerce_email_recipient_' . $email->id,
						[$this, $replaceRecipientMethod],
						10,
						3
					);
				}

				$replaceSubjectMethod = 'replaceSubject' . ucfirst(Helper::snakeToCamel($email->id));
				if (method_exists($this, $replaceSubjectMethod)) {
					add_filter('woocommerce_email_subject_' . $email->id, [$this, $replaceSubjectMethod], 10, 2);
				}
			}
		}
	}

	public static function initCreateSingleton(): ?ReplaceEmailController
	{
		if (self::$oSelf == null) {
			self::$oSelf = new self();
		}
		return self::$oSelf;
	}

	public function handleListKeyEmailTypeUsedUser(): array
	{
		$aKey = [];
		$aEmailType = $this->getListEmailTypeUser();
		if (!empty($aEmailType)) {
			foreach ($aEmailType as $postID => $aItem) {
				$aKey[$postID] = $aItem['value'] ?? '';
			}
		}
		return $aKey;
	}

	public function handleVerifyMiddlewareOrder(int $postId, $aArgs): bool
	{
		$aItems = $aArgs['order']->get_items();
		$aOrder = $aArgs['order']->get_data();
		$aProductIds = [];
		$orderCountry = [];
		$orderSubTotal = 0;
		if (!empty($aItems)) {
			foreach ($aItems as $oProduct) {
				$aDataProduct = $oProduct->get_data();
				$aProductIds[] = $aDataProduct['product_id'];
				$orderSubTotal += $aDataProduct['subtotal'];
			}
		}
		if (!empty($aOrder)) {
			$orderCountry = $aOrder['billing']['country'] ?? '';
		}
		$aTemplateEmailUseRules = $this->getTemplateEmailUseRules($postId);
		if (!empty($aTemplateEmailUseRules)) {
			$aMiddleware = [];
			foreach ($aTemplateEmailUseRules as $rule) {
				$aMiddleware[] = $this->convertMiddleware[$rule];
			}
			$aResponseMiddleware = $this->processMiddleware($aMiddleware, [
				'postID'        => $postId,
				'aProductIds'   => $aProductIds,
				'orderSubTotal' => $orderSubTotal,
				'orderCountry'  => $orderCountry
			]);
			if ($aResponseMiddleware['status'] == 'success') {
				return true;
			}
			return false;
		}
		return true;
	}

	public function handleCartAbandonment($orderID)
	{
		$oOrder = wc_get_order($orderID);
		$aKeyEmailType = $this->handleListKeyEmailTypeUsedUser();
		if (!empty($postID = array_search('cart_abandonment', $aKeyEmailType))) {
			$isAfterOrderStatusPending = $this->getIsAfterOrderStatusPending($postID);
			if ('on-hold' == $oOrder->get_status()) {
				$this->handleSetScheduleCartAbandonment($postID, $orderID);
			}
			if ('pending' == $oOrder->get_status() && $isAfterOrderStatusPending) {
				$this->handleSetScheduleCartAbandonment($postID, $orderID);
			}
		}
	}

	public function handleSetScheduleCartAbandonment($postID, $orderID)
	{
		$addedToCartXMinutes = $this->getAddedToCartXMinutes($postID);
		$time = strtotime('+' . $addedToCartXMinutes . ' minutes', time());
		$this->setScheduleCartAbandonment($time, $postID, $orderID);
	}

	public function replaceEmails($located, $template_name, $args)
	{
		$aKeyEmailType = $this->handleListKeyEmailTypeUsedUser();
		switch ($located) {
			case preg_match('/emails\/admin-new-order\.php/', $located) && in_array('new_order', $aKeyEmailType):
				if ($this->handleVerifyMiddlewareOrder(array_search('new_order', $aKeyEmailType), $args)) {
					$located = WILOKE_EMAIL_CREATOR_PATH . 'src/Email/Templates/admin-new-order.php';
				} else {
					$located = WILOKE_EMAIL_CREATOR_PATH . 'src/Email/Templates/default.php';
				}
				break;
			case preg_match('/emails\/customer-new-account\.php/', $located) &&
				in_array('customer_new_account', $aKeyEmailType):
				$located = WILOKE_EMAIL_CREATOR_PATH . 'src/Email/Templates/customer-new-account.php';
				break;
			case preg_match('/emails\/admin-cancelled-order\.php/', $located) &&
				in_array('cancelled_order', $aKeyEmailType):
				if ($this->handleVerifyMiddlewareOrder(array_search('cancelled_order', $aKeyEmailType), $args)) {
					$located = WILOKE_EMAIL_CREATOR_PATH . 'src/Email/Templates/admin-cancelled-order.php';
				}
				break;
			case preg_match('/emails\/admin-failed-order\.php/', $located) && in_array('failed_order', $aKeyEmailType):
				if ($this->handleVerifyMiddlewareOrder(array_search('failed_order', $aKeyEmailType), $args)) {
					$located = WILOKE_EMAIL_CREATOR_PATH . 'src/Email/Templates/admin-failed-order.php';
				}
				break;
			case preg_match('/emails\/customer-completed-order\.php/', $located) &&
				in_array('customer_completed_order', $aKeyEmailType):
				if ($this->handleVerifyMiddlewareOrder(array_search('customer_completed_order', $aKeyEmailType),
					$args)) {
					$located = WILOKE_EMAIL_CREATOR_PATH . 'src/Email/Templates/customer-completed-order.php';
				} else {
					$located = WILOKE_EMAIL_CREATOR_PATH . 'src/Email/Templates/default.php';
				}
				break;
			case preg_match('/emails\/customer-invoice\.php/', $located) &&
				in_array('customer_invoice', $aKeyEmailType):
				if ($this->handleVerifyMiddlewareOrder(array_search('customer_invoice', $aKeyEmailType), $args)) {
					$located = WILOKE_EMAIL_CREATOR_PATH . 'src/Email/Templates/customer-invoice.php';
				}
				break;
			case preg_match('/emails\/customer-note\.php/', $located) && in_array('customer_note', $aKeyEmailType):
				if ($this->handleVerifyMiddlewareOrder(array_search('customer_note', $aKeyEmailType), $args)) {
					$located = WILOKE_EMAIL_CREATOR_PATH . 'src/Email/Templates/customer-note.php';
				}
				break;
			case preg_match('/emails\/customer-on-hold-order\.php/', $located) &&
				in_array('customer_on_hold_order', $aKeyEmailType):
				if ($this->handleVerifyMiddlewareOrder(array_search('customer_on_hold_order', $aKeyEmailType), $args)) {
					$located = WILOKE_EMAIL_CREATOR_PATH . 'src/Email/Templates/customer-on-hold-order.php';
				}
				break;
			case preg_match('/emails\/customer-processing-order\.php/', $located) &&
				in_array('customer_processing_order', $aKeyEmailType):
				if ($this->handleVerifyMiddlewareOrder(array_search('customer_processing_order', $aKeyEmailType),
					$args)) {
					$located = WILOKE_EMAIL_CREATOR_PATH . 'src/Email/Templates/customer-processing-order.php';
				}
				break;
			case preg_match('/emails\/customer-refunded-order\.php/', $located) &&
				in_array('customer_refunded_order', $aKeyEmailType):
				if ($this->handleVerifyMiddlewareOrder(array_search('customer_refunded_order', $aKeyEmailType),
					$args)) {
					$located = WILOKE_EMAIL_CREATOR_PATH . 'src/Email/Templates/emails/customer-refunded-order.php';
				}
				break;
			case preg_match('/emails\/customer-reset-password\.php/', $located) &&
				in_array('customer_reset_password', $aKeyEmailType):
				$located = WILOKE_EMAIL_CREATOR_PATH . 'src/Email/Templates/customer-reset-password.php';
				break;
			case preg_match('/emails\/customer-new-account\.php/', $located) &&
				in_array('resend_verification_key', $aKeyEmailType):
			case preg_match('/emails\/resend\-verification\-key\.php/', $located) &&
				in_array('resend_verification_key', $aKeyEmailType):
				$located = WILOKE_EMAIL_CREATOR_PATH . 'src/Email/Templates/resend-verification-key.php';
				break;
		}
		return $located;
	}

	private function replaceEmailSubjectCommon($postID, array $aVariables, $oldSubject)
	{
		$emailSubject = get_post_meta($postID, AutoPrefix::namePrefix('emailSubject'), true);
		if (empty($emailSubject)) {
			return $oldSubject;
		}

		return str_replace(array_keys($aVariables), array_values($aVariables), $emailSubject);
	}

	public function replaceSubjectNewOrder($subject, $oOrder)
	{
		$aVariables = GetFieldPlaceholderSubjectEmail::getFieldPlaceholder($oOrder);
		$aKeyEmailType = $this->handleListKeyEmailTypeUsedUser();
		if ($postID = (int)array_search('new_order', $aKeyEmailType)) {
			return $this->replaceEmailSubjectCommon($postID, $aVariables, $subject);
		}

		return $subject;
	}

	public function replaceSubjectCancelledOrder($subject, $oOrder)
	{
		$aVariables = GetFieldPlaceholderSubjectEmail::getFieldPlaceholder($oOrder);
		$aKeyEmailType = $this->handleListKeyEmailTypeUsedUser();
		if ($postID = (int)array_search('cancelled_order', $aKeyEmailType)) {
			return $this->replaceEmailSubjectCommon($postID, $aVariables, $subject);
		}
		return $subject;
	}

	public function replaceSubjectFailedOrder($subject, $oOrder)
	{
		$aVariables = GetFieldPlaceholderSubjectEmail::getFieldPlaceholder($oOrder);
		$aKeyEmailType = $this->handleListKeyEmailTypeUsedUser();
		if ($postID = (int)array_search('failed_order', $aKeyEmailType)) {
			return $this->replaceEmailSubjectCommon($postID, $aVariables, $subject);
		}
		return $subject;
	}

	public function replaceSubjectCustomerOnHoldOrder($subject, $oOrder)
	{
		$aVariables = GetFieldPlaceholderSubjectEmail::getFieldPlaceholder($oOrder);
		$aKeyEmailType = $this->handleListKeyEmailTypeUsedUser();
		if ($postID = (int)array_search('customer_on_hold_order', $aKeyEmailType)) {
			return $this->replaceEmailSubjectCommon($postID, $aVariables, $subject);
		}
		return $subject;
	}

	public function replaceSubjectCustomerProcessingOrder($subject, $oOrder)
	{
		$aVariables = GetFieldPlaceholderSubjectEmail::getFieldPlaceholder($oOrder);
		$aKeyEmailType = $this->handleListKeyEmailTypeUsedUser();
		if ($postID = (int)array_search('customer_processing_order', $aKeyEmailType)) {
			return $this->replaceEmailSubjectCommon($postID, $aVariables, $subject);
		}
		return $subject;
	}

	public function replaceSubjectCustomerCompletedOrder($subject, $oOrder)
	{
		$aVariables = GetFieldPlaceholderSubjectEmail::getFieldPlaceholder($oOrder);
		$aKeyEmailType = $this->handleListKeyEmailTypeUsedUser();
		if ($postID = (int)array_search('customer_completed_order', $aKeyEmailType)) {
			return $this->replaceEmailSubjectCommon($postID, $aVariables, $subject);
		}
		return $subject;
	}

	public function replaceSubjectCustomerRefundedOrder($subject, $oOrder)
	{
		$aVariables = GetFieldPlaceholderSubjectEmail::getFieldPlaceholder($oOrder);
		$aKeyEmailType = $this->handleListKeyEmailTypeUsedUser();
		if ($postID = (int)array_search('customer_refunded_order', $aKeyEmailType)) {
			return $this->replaceEmailSubjectCommon($postID, $aVariables, $subject);
		}
		return $subject;
	}

	public function replaceSubjectCustomerInvoice($subject, $oOrder)
	{
		$aVariables = GetFieldPlaceholderSubjectEmail::getFieldPlaceholder($oOrder);
		$aKeyEmailType = $this->handleListKeyEmailTypeUsedUser();
		if ($postID = (int)array_search('customer_invoice', $aKeyEmailType)) {
			return $this->replaceEmailSubjectCommon($postID, $aVariables, $subject);
		}
		return $subject;
	}

	public function replaceSubjectCustomerNote($subject, $oOrder)
	{
		$aVariables = GetFieldPlaceholderSubjectEmail::getFieldPlaceholder($oOrder);
		$aKeyEmailType = $this->handleListKeyEmailTypeUsedUser();
		if ($postID = (int)array_search('customer_note', $aKeyEmailType)) {
			return $this->replaceEmailSubjectCommon($postID, $aVariables, $subject);
		}

		return $subject;
	}

	public function replaceSubjectCustomerResetPassword($subject, $oUser)
	{
		$aVariables = GetFieldPlaceholderSubjectEmail::getFieldPlaceholder($oUser);
		$aKeyEmailType = $this->handleListKeyEmailTypeUsedUser();
		if ($postID = (int)array_search('customer_reset_password', $aKeyEmailType)) {
			return $this->replaceEmailSubjectCommon($postID, $aVariables, $subject);
		}
		return $subject;
	}

	public function replaceSubjectCustomerNewAccount($subject, $oOrder)
	{
		$aVariables = GetFieldPlaceholderSubjectEmail::getFieldPlaceholder($oOrder);
		$aKeyEmailType = $this->handleListKeyEmailTypeUsedUser();

		if ($postID = (int)array_search('customer_new_account', $aKeyEmailType)) {
			return $this->replaceEmailSubjectCommon($postID, $aVariables, $subject);
		}

		return $subject;
	}


	//replace recipient
	public function handleReplaceRecipientEmailOrder($recipient, $oOrder): string
	{
		$aRecipient = [$recipient];
		if (!empty($oOrder)) {
			$orderData = $oOrder->get_data();
		}
		if (!empty($orderData['billing'])) {
			$aRecipient[] = sanitize_email($orderData['billing']['email']);
		}
		return implode(',', array_unique($aRecipient));
	}

	public function handleReplaceRecipientEmailUser($recipient, WP_User $oUser): string
	{
		return $recipient;
	}

	public function replaceRecipientNewOrder($recipient, $oOrder, $class_email): string
	{
		return $this->handleReplaceRecipientEmailOrder($recipient, $oOrder);
	}

	public function replaceRecipientCancelledOrder($recipient, $oOrder, $class_email): string
	{
		return $this->handleReplaceRecipientEmailOrder($recipient, $oOrder);
	}

	public function replaceRecipientFailedOrder($recipient, $oOrder, $class_email): string
	{
		return $this->handleReplaceRecipientEmailOrder($recipient, $oOrder);
	}

	public function replaceRecipientCustomerOnHoldOrder($recipient, $oOrder, $class_email): string
	{
		return $this->handleReplaceRecipientEmailOrder($recipient, $oOrder);
	}

	public function replaceRecipientCustomerProcessingOrder($recipient, $oOrder, $class_email): string
	{
		return $this->handleReplaceRecipientEmailOrder($recipient, $oOrder);
	}

	public function replaceRecipientCustomerCompletedOrder($recipient, $oOrder, $class_email): string
	{
		return $this->handleReplaceRecipientEmailOrder($recipient, $oOrder);
	}

	public function replaceRecipientCustomerRefundedOrder($recipient, $oOrder, $class_email): string
	{
		return $this->handleReplaceRecipientEmailOrder($recipient, $oOrder);
	}

	public function replaceRecipientCustomerInvoice($recipient, $oOrder, $class_email): string
	{
		return $this->handleReplaceRecipientEmailOrder($recipient, $oOrder);
	}

	public function replaceRecipientCustomerNote($recipient, $oOrder, $class_email): string
	{
		return $this->handleReplaceRecipientEmailOrder($recipient, $oOrder);
	}

	public function replaceRecipientCustomerResetPassword($recipient, $oUser, $class_email): string
	{
		return $this->handleReplaceRecipientEmailUser($recipient, $oUser);
	}

	public function replaceRecipientCustomerNewAccount($recipient, $oUser, $class_email): string
	{
		return $this->handleReplaceRecipientEmailUser($recipient, $oUser);
	}
}
