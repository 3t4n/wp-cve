<?php

trait MM_WPFS_InlineFormView
{

	/** @var MM_WPFS_Control */
	protected $cardHolderName;
	/** @var MM_WPFS_Control */
	protected $cardHolderEmail;
	/** @var MM_WPFS_Control */
	protected $card;

	/** @var $this->fieldConfiguration array */

	public static function getInlineFields()
	{
		$fields = array(
			MM_WPFS_InlineFormViewConstants::FIELD_CARD_HOLDER_NAME => MM_WPFS_ControlUtils::input(MM_WPFS_InlineFormViewConstants::FIELD_CARD_HOLDER_NAME),
			MM_WPFS_InlineFormViewConstants::FIELD_CARD_HOLDER_EMAIL => MM_WPFS_ControlUtils::input(MM_WPFS_InlineFormViewConstants::FIELD_CARD_HOLDER_EMAIL),
			MM_WPFS_InlineFormViewConstants::FIELD_CARD_NUMBER => MM_WPFS_ControlUtils::cardInput(MM_WPFS_InlineFormViewConstants::FIELD_CARD_NUMBER)
		);

		return $fields;
	}

	public function setCurrentEmailAddress($email_address)
	{
		$this->cardHolderEmail()->setValue($email_address);
	}

	public function cardHolderEmail()
	{
		return $this->cardHolderEmail;
	}

	public function cardHolderName()
	{
		return $this->cardHolderName;
	}

	public function card()
	{
		return $this->card;
	}

	protected function initInlineFields($formHash)
	{
		$this->cardHolderName = MM_WPFS_ControlUtils::createControl(
			$formHash,
			MM_WPFS_InlineFormViewConstants::FIELD_CARD_HOLDER_NAME,
			null,
			null,
			/* translators: Form field label for card holder's name */
			__('Cardholder’s name', 'wp-full-stripe'),
			null
		);
		$this->configureTextControl($this->cardHolderName, $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_CARDHOLDERS_NAME]);

		$this->cardHolderEmail = MM_WPFS_ControlUtils::createControl(
			$formHash,
			MM_WPFS_InlineFormViewConstants::FIELD_CARD_HOLDER_EMAIL,
			null,
			null,
			/* translators: Form field label for email address */
			__('E-mail address', 'wp-full-stripe'),
			null
		);
		$this->configureTextControl($this->cardHolderEmail, $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_EMAIL]);

		$this->card = MM_WPFS_ControlUtils::createControl(
			$formHash,
			MM_WPFS_InlineFormViewConstants::FIELD_CARD_NUMBER,
			null,
			null,
			/* translators: Form field label for credit card data */
			__('Card info', 'wp-full-stripe'),
			null
		);
	}

	/**
	 * @return array
	 */
	protected function getInlineFormAttributes($form)
	{
		$attributes = array();

		$attributes[MM_WPFS_InlineFormViewConstants::ATTR_DATA_WPFS_PREFERRED_LANGUAGE] = $form->preferredLanguage;

		return $attributes;
	}

}

trait MM_WPFS_CheckoutFormView
{

	protected function getCheckoutFormAttributes($form)
	{
		$attributes = array();

		$attributes[MM_WPFS_PopupFormViewConstants::ATTR_DATA_WPFS_COMPANY_NAME] = $form->companyName;
		$attributes[MM_WPFS_PopupFormViewConstants::ATTR_DATA_WPFS_PRODUCT_DESCRIPTION] = MM_WPFS_Localization::translateLabel($form->productDesc);
		$attributes[MM_WPFS_PopupFormViewConstants::ATTR_DATA_WPFS_BUTTON_TITLE] = MM_WPFS_Localization::translateLabel($form->buttonTitle);
		$attributes[MM_WPFS_PopupFormViewConstants::ATTR_DATA_WPFS_SHOW_REMEMBER_ME] = 0;
		$attributes[MM_WPFS_PopupFormViewConstants::ATTR_DATA_WPFS_IMAGE] = $form->image;
		$attributes[MM_WPFS_PopupFormViewConstants::ATTR_DATA_WPFS_USE_ALIPAY] = 0; // tnagy remove Alipay support temporarily $form->useAlipay
		$attributes[MM_WPFS_PopupFormViewConstants::ATTR_DATA_WPFS_PREFERRED_LANGUAGE] = $form->preferredLanguage;

		return $attributes;
	}

}

/**
 * Used by WPFSM, WPFP_Mailchimp
 */
interface MM_WPFS_FormViewConstants
{

	const ATTR_ACTION = 'action';
	const ATTR_ID = 'id';
	const ATTR_CLASS = 'class';
	const ATTR_METHOD = 'method';
	const ATTR_VALUE = 'value';
	const ATTR_DATA_WPFS_FORM_ID = 'data-wpfs-form-id';
	const ATTR_DATA_WPFS_FORM_TYPE = 'data-wpfs-form-type';
	const ATTR_DATA_WPFS_FORM_HASH = 'data-wpfs-form-hash';
	const ATTR_DATA_WPFS_SHOW_ADDRESS = 'data-wpfs-show-address';
	const ATTR_DATA_WPFS_SHOW_BILLING_ADDRESS = 'data-wpfs-show-billing-address';
	const ATTR_DATA_WPFS_SHOW_SHIPPING_ADDRESS = 'data-wpfs-show-shipping-address';
	const ATTR_DATA_WPFS_CUSTOM_INPUT_TITLE = 'data-wpfs-custom-input-title';
	const ATTR_DATA_WPFS_CUSTOM_INPUTS = 'data-wpfs-custom-inputs';
	const ATTR_DATA_WPFS_CUSTOM_INPUT_REQUIRED = 'data-wpfs-custom-input-required';
	const ATTR_DATA_WPFS_SHOW_TERMS_OF_USE = 'data-wpfs-show-terms-of-use';
	const ATTR_DATA_WPFS_TERMS_OF_USE_NOT_CHECKED_ERROR_MESSAGE = 'data-wpfs-terms-of-use-not-checked-error-message';
	const ATTR_DATA_WPFS_CUSTOM_INPUT_FIELD = 'data-wpfs-custom-input-field';
	const ATTR_DATA_WPFS_CUSTOM_INPUT_LABEL = 'data-wpfs-custom-input-label';
	const ATTR_DATA_WPFS_VAT_RATE_TYPE = 'data-wpfs-vat-rate-type';
	const ATTR_DATA_WPFS_CURRENCY = 'data-wpfs-currency';
	const ATTR_DATA_WPFS_ZERO_DECIMAL_SUPPORT = 'data-wpfs-zero-decimal-support';
	const ATTR_DATA_WPFS_CURRENCY_SYMBOL = 'data-wpfs-currency-symbol';
	const ATTR_DATA_WPFS_AMOUNT = 'data-wpfs-amount';
	const ATTR_DATA_WPFS_AMOUNT_IN_SMALLEST_COMMON_CURRENCY = 'data-wpfs-amount-in-smallest-common-currency';
	const ATTR_DATA_WPFS_AMOUNT_INDEX = 'data-wpfs-amount-index';
	const ATTR_DATA_WPFS_AMOUNT_DESCRIPTION = 'data-wpfs-amount-description';
	const ATTR_DATA_WPFS_PRODUCT_NAME = 'data-wpfs-product-name';
	const ATTR_DATA_WPFS_AMOUNT_TYPE = 'data-wpfs-amount-type';
	const ATTR_DATA_WPFS_AMOUNT_PRICE_ID = 'data-wpfs-amount-price-id';
	const ATTR_DATA_WPFS_ALLOW_LIST_OF_AMOUNTS_CUSTOM = 'data-wpfs-allow-list-of-amounts-custom';
	const ATTR_DATA_WPFS_SELECTOR_STYLE = 'data-wpfs-selector-style';
	const ATTR_DATA_WPFS_BUTTON_TITLE = 'data-wpfs-button-title';
	const ATTR_DATA_WPFS_SHOW_AMOUNT = 'data-wpfs-show-amount';
	const ATTR_DATA_WPFS_STEPPER = 'data-wpfs-stepper';
	const ATTR_DATA_WPFS_DECIMAL_SEPARATOR = 'data-wpfs-decimal-separator';
	const ATTR_DATA_WPFS_SHOW_CURRENCY_SYMBOL_INSTEAD_OF_CODE = 'data-wpfs-show-currency-symbol-instead-of-code';
	const ATTR_DATA_WPFS_SHOW_CURRENCY_SIGN_AT_FIRST_POSITION = 'data-wpfs-show-currency-sign-at-first-position';
	const ATTR_DATA_WPFS_PUT_WHITESPACE_BETWEEN_CURRENCY_AND_AMOUNT = 'data-wpfs-put-whitespace-between-currency-and-amount';
	const ATTR_DATA_WPFS_SHOW_COUPON_FIELD = 'data-wpfs-show-coupon-field';
	const ATTR_DATA_WPFS_ELEMENTS_THEME = 'data-wpfs-elements-theme';
	const ATTR_DATA_WPFS_ELEMENTS_FONT = 'data-wpfs-elements-font';

	const ATTR_DATA_DEFAULT_VALUE = 'data-default-value';
	const ATTR_DATA_MINIMUM_VALUE = 'data-min';
	const ATTR_DATA_MAXIMUM_VALUE = 'data-max';

	const ATTR_ID_VALUE_PREFIX = 'wpfs-form--';
	const ATTR_ID_VALUE_WPFS_CREATE_FORM = 'wpfs-create-form';
	const ATTR_CLASS_VALUE_WPFS_FORM = 'wpfs-form';
	const ATTR_CLASS_VALUE_WPFS_FORM_WPFS_W_60 = 'wpfs-form wpfs-w-60';
	const ATTR_CLASS_VALUE_WPFS_FORM_WPFS_FORM_INLINE = 'wpfs-form wpfs-form--inline';
	const ATTR_METHOD_VALUE_POST = 'post';

	const FIELD_ACTION = 'action';
	const FIELD_FORM_NAME = 'wpfs-form-name';
	const FIELD_FORM_GET_PARAMETERS = 'wpfs-form-get-parameters';
	const FIELD_SAME_BILLING_AND_SHIPPING_ADDRESS = 'wpfs-same-billing-and-shipping-address';
	const FIELD_ADDRESS_SWITCHER = 'wpfs-address-switcher';
	const FIELD_BILLING_ADDRESS_PANEL = 'wpfs-billing-address-panel';
	const FIELD_BILLING_NAME = 'wpfs-billing-name';
	const FIELD_BILLING_ADDRESS_LINE1 = 'wpfs-billing-address-line-1';
	const FIELD_BILLING_ADDRESS_LINE2 = 'wpfs-billing-address-line-2';
	const FIELD_BILLING_ADDRESS_ZIP = 'wpfs-billing-address-zip';
	const FIELD_BILLING_ADDRESS_STATE = 'wpfs-billing-address-state';
	const FIELD_BILLING_ADDRESS_STATE_SELECT = 'wpfs-billing-address-state-select';
	const FIELD_BILLING_ADDRESS_CITY = 'wpfs-billing-address-city';
	const FIELD_BILLING_ADDRESS_COUNTRY = 'wpfs-billing-address-country';
	const FIELD_SHIPPING_ADDRESS_PANEL = 'wpfs-shipping-address-panel';
	const FIELD_SHIPPING_NAME = 'wpfs-shipping-name';
	const FIELD_SHIPPING_ADDRESS_LINE1 = 'wpfs-shipping-address-line-1';
	const FIELD_SHIPPING_ADDRESS_LINE2 = 'wpfs-shipping-address-line-2';
	const FIELD_SHIPPING_ADDRESS_ZIP = 'wpfs-shipping-address-zip';
	const FIELD_SHIPPING_ADDRESS_STATE = 'wpfs-shipping-address-state';
	const FIELD_SHIPPING_ADDRESS_STATE_SELECT = 'wpfs-shipping-address-state-select';
	const FIELD_SHIPPING_ADDRESS_CITY = 'wpfs-shipping-address-city';
	const FIELD_SHIPPING_ADDRESS_COUNTRY = 'wpfs-shipping-address-country';
	const FIELD_CUSTOM_INPUT = 'wpfs-custom-input';
	const FIELD_COUPON = 'wpfs-coupon';
	const FIELD_TERMS_OF_USE_ACCEPTED = 'wpfs-terms-of-use-accepted';
	const FIELD_GOOGLE_RECAPTCHA_RESPONSE = 'g-recaptcha-response';
	const FIELD_NONCE = 'wpfs-nonce';

	const MACRO_SUBMIT_BUTTON_CAPTION_AMOUNT = '{{amount}}';

	const BUTTON_SUBMIT = 'submit';
}

interface MM_WPFS_PaymentFormViewConstants
{

	const FIELD_CUSTOM_AMOUNT = 'wpfs-custom-amount';
	const FIELD_CUSTOM_AMOUNT_UNIQUE = 'wpfs-custom-amount-unique';

	const FIELD_ACTION_VALUE_INLINE_PAYMENT_CHARGE = 'wp_full_stripe_inline_payment_charge';
	const FIELD_ACTION_VALUE_POPUP_PAYMENT_CHARGE = 'wp_full_stripe_popup_payment_charge';

	const ATTR_DATA_WPFS_TAX_RATE_TYPE = 'data-wpfs-tax-rate-type';
}

interface MM_WPFS_SaveCardFormViewConstants
{

	const FIELD_ACTION_VALUE_INLINE_PAYMENT_CHARGE = 'wp_full_stripe_inline_payment_charge';
	const FIELD_ACTION_VALUE_POPUP_PAYMENT_CHARGE = 'wp_full_stripe_popup_payment_charge';
}

interface MM_WPFS_DonationFormViewConstants
{
	const FIELD_CUSTOM_AMOUNT = 'wpfs-custom-amount';
	const FIELD_CUSTOM_AMOUNT_UNIQUE = 'wpfs-custom-amount-unique';
	const FIELD_DONATION_FREQUENCY = 'wpfs-donation-frequency';

	const FIELD_VALUE_CUSTOM_AMOUNT_OTHER = 'other';

	const FIELD_VALUE_DONATION_FREQUENCY_ONE_TIME = "one-time";
	const FIELD_VALUE_DONATION_FREQUENCY_DAILY = "daily";
	const FIELD_VALUE_DONATION_FREQUENCY_WEEKLY = "weekly";
	const FIELD_VALUE_DONATION_FREQUENCY_MONTHLY = "monthly";
	const FIELD_VALUE_DONATION_FREQUENCY_ANNUAL = "annual";

	const FIELD_ACTION_VALUE_INLINE_DONATION_CHARGE = 'wp_full_stripe_inline_donation_charge';
	const FIELD_ACTION_VALUE_POPUP_DONATION_CHARGE = 'wp_full_stripe_popup_donation_charge';
}

interface MM_WPFS_SubscriptionFormViewConstants
{

	const FIELD_PLAN = 'wpfs-plan';
	const FIELD_PLAN_QUANTITY = 'wpfs-plan-quantity';

	const ATTR_DATA_WPFS_VALUE = 'data-wpfs-value';
	const ATTR_DATA_WPFS_PLAN_AMOUNT = 'data-wpfs-plan-amount';
	const ATTR_DATA_WPFS_PLAN_AMOUNT_IN_SMALLEST_COMMON_CURRENCY = 'data-wpfs-plan-amount-in-smallest-common-currency';
	const ATTR_DATA_WPFS_INTERVAL = 'data-wpfs-interval';
	const ATTR_DATA_WPFS_INTERVAL_COUNT = 'data-wpfs-interval-count';
	const ATTR_DATA_WPFS_CANCELLATION_COUNT = 'data-wpfs-cancellation-count';
	const ATTR_DATA_WPFS_PLAN_SETUP_FEE = 'data-wpfs-plan-setup-fee';
	const ATTR_DATA_WPFS_PLAN_SETUP_FEE_IN_SMALLEST_COMMON_CURRENCY = 'data-wpfs-plan-setup-fee-in-smallest-common-currency';
	const ATTR_DATA_WPFS_SIMPLE_BUTTON_LAYOUT = 'data-wpfs-simple-button-layout';
	const ATTR_DATA_WPFS_TAX_RATE_TYPE = 'data-wpfs-tax-rate-type';

	const FIELD_ACTION_VALUE_INLINE_SUBSCRIPTION_CHARGE = 'wp_full_stripe_inline_subscription_charge';
	const FIELD_ACTION_VALUE_POPUP_SUBSCRIPTION_CHARGE = 'wp_full_stripe_popup_subscription_charge';
}

interface MM_WPFS_InlineFormViewConstants
{

	const FIELD_CARD_HOLDER_NAME = 'wpfs-card-holder-name';
	const FIELD_CARD_HOLDER_EMAIL = 'wpfs-card-holder-email';
	/**
	 * This is the default field name that Stripe Elements uses for Cards. It should be changed only in correlation with
	 * the Stripe Elements configuration.
	 */
	const FIELD_CARD_NUMBER = 'cardnumber';

	const ATTR_DATA_WPFS_PREFERRED_LANGUAGE = 'data-wpfs-preferred-language';

}

interface MM_WPFS_PopupFormViewConstants
{

	const ATTR_DATA_WPFS_COMPANY_NAME = 'data-wpfs-company-name';
	const ATTR_DATA_WPFS_PRODUCT_DESCRIPTION = 'data-wpfs-product-description';
	const ATTR_DATA_WPFS_BUTTON_TITLE = 'data-wpfs-button-title';
	const ATTR_DATA_WPFS_SHOW_REMEMBER_ME = 'data-wpfs-show-remember-me';
	const ATTR_DATA_WPFS_IMAGE = 'data-wpfs-image';
	const ATTR_DATA_WPFS_USE_ALIPAY = 'data-wpfs-use-alipay';
	const ATTR_DATA_WPFS_PREFERRED_LANGUAGE = 'data-wpfs-preferred-language';

}

/**
 * Used by WPFSM, WPFP_Mailchimp
 */
class MM_WPFS_ControlUtils
{

	const FIELD_TYPE_INPUT = 'input';
	const FIELD_TYPE_INPUT_CUSTOM = 'input-custom';
	const FIELD_TYPE_INPUT_GROUP = 'input-group';
	const FIELD_TYPE_INPUT_GROUP_MINMAX = 'input-group-minmax';
	const FIELD_TYPE_DROPDOWN = 'dropdown';
	const FIELD_TYPE_CHECKBOX = 'checkbox';
	const FIELD_TYPE_CHECKLIST = 'checklist';
	const FIELD_TYPE_PRODUCTS = 'products';
	const FIELD_TYPE_CARD = 'card';
	const FIELD_TYPE_CAPTCHA = 'captcha';
	const FIELD_TYPE_TAGS = 'tags';

	public static final function customInput($name)
	{
		$class = 'wpfs-form-control';
		$selector = "#{fieldId}";
		$errorClass = 'wpfs-form-control--error';
		$errorSelector = ".{$class}";

		return self::descriptor(self::FIELD_TYPE_INPUT, $name, $class, $selector, $errorClass, $errorSelector);
	}

	/**
	 * @param $type
	 * @param $name
	 * @param $class
	 * @param $selector
	 * @param $errorClass
	 * @param $errorSelector
	 * @param bool $hidden
	 *
	 * @return array
	 */
	public static final function descriptor($type, $name, $class, $selector, $errorClass, $errorSelector, $hidden = false)
	{
		return array(
			'type' => $type,
			'name' => $name,
			'class' => $class,
			'selector' => $selector,
			'errorClass' => $errorClass,
			'errorSelector' => $errorSelector,
			'hidden' => $hidden
		);
	}

	/**
	 * Used by WPFSM, WPFP_Mailchimp
	 */
	public static final function input($name)
	{
		$class = 'wpfs-form-control';
		$selector = ".{$class}[name='{$name}']";
		$errorClass = 'wpfs-form-control--error';
		$errorSelector = ".{$class}";

		return self::descriptor(self::FIELD_TYPE_INPUT, $name, $class, $selector, $errorClass, $errorSelector);
	}

	public static final function inputTags($name)
	{
		$class = 'wpfs-tags-input';
		$selector = ".{$class}[name='{$name}']";
		$errorClass = 'wpfs-form-control--error';
		$errorSelector = ".{$class}";

		return self::descriptor(self::FIELD_TYPE_TAGS, $name, $class, $selector, $errorClass, $errorSelector);
	}

	/**
	 * Used by WPFSM, WPFP_Mailchimp
	 */
	public static final function inputHidden($name)
	{
		return self::descriptor(self::FIELD_TYPE_INPUT, $name, null, null, null, null, true);
	}

	public static final function selectMenu($name)
	{
		$class = 'wpfs-form-control';
		$selector = "select[name='{$name}']";
		$errorClass = 'wpfs-form-control--error';
		$errorSelector = '#{fieldId}-button,#{fieldId}-menu';

		return self::descriptor(self::FIELD_TYPE_DROPDOWN, $name, $class, $selector, $errorClass, $errorSelector);
	}

	public static final function checkbox($name)
	{
		$class = 'wpfs-form-check-input';
		$selector = ".{$class}[name='{$name}']";
		$errorClass = 'wpfs-form-check-input--error';
		$errorSelector = ".{$class}";

		return self::descriptor(self::FIELD_TYPE_CHECKBOX, $name, $class, $selector, $errorClass, $errorSelector);
	}

	public static final function checklist($name)
	{
		$class = 'wpfs-form-check-list';
		$selector = ".{$class}[data-field-name='{$name}']";
		$errorClass = 'wpfs-form-control--error';
		$errorSelector = ".{$class}";

		return self::descriptor(self::FIELD_TYPE_CHECKLIST, $name, $class, $selector, $errorClass, $errorSelector);
	}

	public static final function products($name)
	{
		$class = 'wpfs-field-list';
		$selector = ".{$class}[data-field-name='{$name}']";
		$errorClass = 'wpfs-form-control--error';
		$errorSelector = ".{$class}[data-field-name='{$name}']";

		return self::descriptor(self::FIELD_TYPE_PRODUCTS, $name, $class, $selector, $errorClass, $errorSelector);
	}

	public static final function captcha($name)
	{
		$class = 'wpfs-form-captcha';
		$selector = ".{$class}[data-wpfs-field-name='{$name}']";
		$errorClass = 'wpfs-form-control--error';
		$errorSelector = ".{$class}";

		return self::descriptor(self::FIELD_TYPE_CAPTCHA, $name, $class, $selector, $errorClass, $errorSelector);
	}

	public static final function inputGroup($name)
	{
		$class = 'wpfs-input-group-form-control';
		$selector = ".{$class}[name='{$name}']";
		$errorClass = 'wpfs-input-group--error';
		$errorSelector = '.' . 'wpfs-input-group';

		return self::descriptor(self::FIELD_TYPE_INPUT_GROUP, $name, $class, $selector, $errorClass, $errorSelector);
	}

	public static final function inputGroupMinMax($name)
	{
		$class = 'wpfs-input-group-form-control';
		$selector = ".{$class}[name='{$name}']";
		$errorClass = 'wpfs-input-group--error';
		$errorSelector = '.' . 'wpfs-input-group';

		return self::descriptor(self::FIELD_TYPE_INPUT_GROUP_MINMAX, $name, $class, $selector, $errorClass, $errorSelector);
	}

	public static final function cardInput($name)
	{
		$class = 'wpfs-form-card';
		$selector = "input[name='{$name}']";
		$errorClass = 'wpfs-form-control--error';
		$errorSelector = ".{$class}";

		return self::descriptor(self::FIELD_TYPE_CARD, $name, $class, $selector, $errorClass, $errorSelector);
	}

	public static final function stepper($name)
	{
		$class = 'wpfs-stepper';
		$selector = "input[name='{$name}']";
		$errorClass = 'wpfs-form-control--error';
		$errorSelector = ".{$class}";

		return self::descriptor(self::FIELD_TYPE_CARD, $name, $class, $selector, $errorClass, $errorSelector);
	}

	/*
	 * @param $formHash
	 * @param $name
	 * @param $placeholder
	 * @param $caption
	 * @param $label
	 * @param null $index
	 *
	 * @return MM_WPFS_Control
	 *
	 * Used by WPFSM, WPFP_Mailchimp
	 */
	public static final function createControl($formHash, $name, $placeholder, $caption, $label, $index)
	{
		$control = new MM_WPFS_Control(
			MM_WPFS_Utils::generateFormElementId($name, $formHash, $index),
			$name,
			$placeholder
		);
		$control->setCaption($caption);
		$control->setLabel($label);
		$control->setIndex($index);

		return $control;
	}

}

/**
 * Used by WPFSM, WPFP_Mailchimp
 */
class MM_WPFS_Control
{

	const ESCAPE_TYPE_NONE = 'none';
	const ESCAPE_TYPE_ATTRIBUTE = 'attribute';
	const ESCAPE_TYPE_HTML = 'html';
	const PHP_MULTI_VALUE_POST_PARAMETER_NAME_POSTFIX = '[]';

	protected $id;
	protected $name;
	protected $placeholder;
	protected $index;
	protected $caption;
	protected $value;
	protected $tooltip;
	protected $label;
	protected $labelEscape = self::ESCAPE_TYPE_HTML;
	protected $labelAttributes = array();
	protected $options = array();
	protected $attributes = array();
	protected $metadata = array();
	/** @var  boolean $multiValue */
	protected $multiValue = false;

	/**
	 * MM_WPFS_ViewBlock constructor.
	 *
	 * @param $id
	 * @param $name
	 * @param $placeholder
	 */
	public function __construct($id, $name, $placeholder)
	{
		$this->id = $id;
		$this->name = $name;
		$this->placeholder = $placeholder;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @param mixed $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @param mixed $placeholder
	 */
	public function setPlaceholder($placeholder)
	{
		$this->placeholder = $placeholder;
	}

	/**
	 * @param mixed $caption
	 */
	public function setCaption($caption)
	{
		$this->caption = $caption;
	}

	/**
	 * @param mixed $value
	 */
	public function setValue($value)
	{
		$this->value = $value;
	}

	/**
	 * @param mixed $tooltip
	 */
	public function setTooltip($tooltip)
	{
		$this->tooltip = $tooltip;
	}

	/**
	 * @param mixed $index
	 */
	public function setIndex($index)
	{
		$this->index = $index;
	}

	/**
	 * @param mixed $label
	 * @param string $labelEscape
	 */
	public function setLabel($label, $labelEscape = self::ESCAPE_TYPE_HTML)
	{
		$this->label = $label;
		if (self::ESCAPE_TYPE_NONE === $labelEscape) {
			$this->labelEscape = self::ESCAPE_TYPE_NONE;
		} else {
			$this->labelEscape = self::ESCAPE_TYPE_HTML;
		}
	}

	/**
	 * @param string $labelEscape
	 */
	public function setLabelEscape($labelEscape)
	{
		$this->labelEscape = $labelEscape;
	}

	/**
	 * @param array $labelAttributes
	 */
	public function setLabelAttributes($labelAttributes)
	{
		$this->labelAttributes = $labelAttributes;
	}

	/**
	 * @param array $options
	 */
	public function setOptions($options)
	{
		$this->options = $options;
	}

	/**
	 * @param array $attributes
	 */
	public function setAttributes($attributes)
	{
		$this->attributes = $attributes;
	}

	/**
	 * @param array $metadata
	 */
	public function setMetadata($metadata)
	{
		$this->metadata = $metadata;
	}

	/**
	 * @param boolean $multiValue
	 */
	public function setMultiValue($multiValue)
	{
		$this->multiValue = $multiValue;
	}

	/**
	 * @param bool $echo
	 *
	 * @return bool|null|string|void
	 */
	public function name($echo = true)
	{
		$name = $this->name;
		if ($this->multiValue) {
			$name .= self::PHP_MULTI_VALUE_POST_PARAMETER_NAME_POSTFIX;
		}

		return $this->echoOrReturnValue($echo, $name);
	}

	/**
	 * @param $echo
	 * @param $fieldValue
	 * @param string $escapeType
	 *
	 * @return bool|null|string|void
	 */
	protected function echoOrReturnValue($echo, $fieldValue, $escapeType = self::ESCAPE_TYPE_ATTRIBUTE)
	{
		$escapedValue = null;
		if (self::ESCAPE_TYPE_ATTRIBUTE === $escapeType) {
			$escapedValue = esc_attr($fieldValue);
		} elseif (self::ESCAPE_TYPE_HTML === $escapeType) {
			$escapedValue = esc_html($fieldValue);
		} else {
			$escapedValue = $fieldValue;
		}
		if ($echo) {
			echo ($escapedValue);

			return true;
		} else {
			return $escapedValue;
		}
	}

	/**
	 * @param bool $echo
	 *
	 * @return bool|null|string|void
	 */
	public function index($echo = true)
	{
		return $this->echoOrReturnValue($echo, $this->index);
	}

	/**
	 * @param bool $echo
	 *
	 * @return bool|null|string|void
	 */
	public function id($echo = true)
	{
		return $this->echoOrReturnValue($echo, $this->id);
	}

	/**
	 * @param bool $echo
	 *
	 * @return bool|null|string|void
	 */
	public function placeholder($echo = true)
	{
		return $this->echoOrReturnValue($echo, $this->placeholder);
	}

	/**
	 * @param bool $echo
	 *
	 * @return bool|null|string|void
	 */
	public function caption($echo = true)
	{
		return $this->echoOrReturnValue($echo, $this->caption, self::ESCAPE_TYPE_HTML);
	}

	/**
	 * @param bool $echo
	 *
	 * @return bool|null|string|void
	 */
	public function value($echo = true)
	{
		return $this->echoOrReturnValue($echo, $this->value);
	}

	/**
	 * @param bool $echo
	 *
	 * @return mixed
	 */
	public function tooltip($echo = true)
	{
		return $this->echoOrReturnValue($echo, $this->tooltip);
	}

	/**
	 * @param bool $echo
	 *
	 * @return bool|null|string|void
	 */
	public function label($echo = true)
	{
		return $this->echoOrReturnValue($echo, $this->label, $this->labelEscape);
	}

	/**
	 * @param bool $echo
	 *
	 * @return array
	 */
	public function labelAttributes($echo = true)
	{
		if ($echo) {

			$labelAttributesAsString = $this->attributesAsString($this->labelAttributes);

			echo ($labelAttributesAsString);
		}

		return $this->labelAttributes;
	}

	/**
	 * @return array
	 */
	public function metadata()
	{
		return $this->metadata;
	}

	/**
	 * @param array $attributes
	 *
	 * @return string
	 *
	 * Used by WPFSM, WPFP_Mailchimp
	 */
	public static function attributesAsString(array $attributes)
	{
		$attributesAsString = implode(
			' ',
			array_map(
				function ($value, $key) {
					return $key . '=' . '"' . esc_attr($value) . '"';
				},
				$attributes,
				array_keys($attributes)
			)
		);

		return $attributesAsString;
	}

	/**
	 * @return array
	 */
	public function options()
	{
		return $this->options;
	}

	/**
	 * @param bool $echo
	 *
	 * @return array
	 */
	public function attributes($echo = true)
	{
		if ($echo) {

			$attributesAsString = $this->attributesAsString($this->attributes);

			echo ($attributesAsString);
		}

		return $this->attributes;
	}
}

class MM_WPFS_FieldDescriptor
{

	/**
	 * @var string
	 */
	protected $type;
	/**
	 * @var string
	 */
	protected $name;
	/**
	 * @var string
	 */
	protected $class;
	/**
	 * @var string
	 */
	protected $selector;
	/**
	 * @var string
	 */
	protected $errorClass;
	/**
	 * @var string
	 */
	protected $errorSelector;

	/**
	 * MM_WPFS_FieldDescriptor constructor.
	 *
	 * @param string $type
	 * @param string $name
	 * @param string $class
	 * @param string $selector
	 * @param string $errorClass
	 * @param string $errorSelector
	 */
	public function __construct($type, $name, $class, $selector, $errorClass, $errorSelector)
	{
		$this->type = $type;
		$this->name = $name;
		$this->class = $class;
		$this->selector = $selector;
		$this->errorClass = $errorClass;
		$this->errorSelector = $errorSelector;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getClass()
	{
		return $this->class;
	}

	/**
	 * @return string
	 */
	public function getSelector()
	{
		return $this->selector;
	}

	/**
	 * @return string
	 */
	public function getErrorClass()
	{
		return $this->errorClass;
	}

	/**
	 * @return string
	 */
	public function getErrorSelector()
	{
		return $this->errorSelector;
	}

}

abstract class MM_WPFS_FormView implements MM_WPFS_FormViewConstants
{
	use MM_WPFS_Logger_AddOn;

	/** @var MM_WPFS_Control */
	protected $action;
	/** @var MM_WPFS_Control */
	protected $formName;
	/** @var MM_WPFS_Control */
	protected $formGetParameters;
	/** @var MM_WPFS_Control */
	protected $sameBillingAndShippingAddress;
	/** @var MM_WPFS_Control */
	protected $showBillingAddressPanelRadio;
	/** @var MM_WPFS_Control */
	protected $showShippingAddressPanelRadio;
	/** @var MM_WPFS_Control */
	protected $addressSwitcher;
	/** @var MM_WPFS_Control */
	protected $billingAddressPanel;
	/** @var MM_WPFS_Control */
	protected $billingName;
	/** @var MM_WPFS_Control */
	protected $billingAddressLine1;
	/** @var MM_WPFS_Control */
	protected $billingAddressLine2;
	/** @var MM_WPFS_Control */
	protected $billingAddressCity;
	/** @var MM_WPFS_Control */
	protected $billingAddressState;
	/** @var MM_WPFS_Control */
	protected $billingAddressStateSelect;
	/** @var MM_WPFS_Control */
	protected $billingAddressZip;
	/** @var MM_WPFS_Control */
	protected $billingAddressCountry;
	/** @var MM_WPFS_Control */
	protected $shippingAddressPanel;
	/** @var MM_WPFS_Control */
	protected $shippingName;
	/** @var MM_WPFS_Control */
	protected $shippingAddressLine1;
	/** @var MM_WPFS_Control */
	protected $shippingAddressLine2;
	/** @var MM_WPFS_Control */
	protected $shippingAddressCity;
	/** @var MM_WPFS_Control */
	protected $shippingAddressState;
	/** @var MM_WPFS_Control */
	protected $shippingAddressStateSelect;
	/** @var MM_WPFS_Control */
	protected $shippingAddressZip;
	/** @var MM_WPFS_Control */
	protected $shippingAddressCountry;
	/** @var MM_WPFS_Control */
	protected $coupon;
	/** @var array */
	protected $customInputs;
	/** @var MM_WPFS_Control */
	protected $termsOfUseAccepted;
	/** @var MM_WPFS_Control */
	protected $submitButton;

	protected $form;
	protected $formHash;
	protected $attributes = array();

	protected $defaultBillingCountry;
	protected $defaultShippingCountry;

	protected $__currentVATPercent;

	/** @var array */
	protected $fieldConfiguration;
	/**
	 * MM_WPFS_FormView constructor.
	 *
	 * @param $form
	 */
	public function __construct($form, $fieldConfiguration, $loggerService)
	{
		$this->initLogger($loggerService, MM_WPFS_LoggerService::MODULE_CUSTOMER_PORTAL);

		$this->form = $form;
		$this->fieldConfiguration = $fieldConfiguration;

		$this->formHash = $this->getFormHash();
		$this->attributes = $this->getFormAttributes();
		$this->action = MM_WPFS_ControlUtils::createControl($this->formHash, self::FIELD_ACTION, null, null, null, null);
		$this->formName = MM_WPFS_ControlUtils::createControl($this->formHash, self::FIELD_FORM_NAME, null, null, null, null);
		$this->formGetParameters = MM_WPFS_ControlUtils::createControl($this->formHash, self::FIELD_FORM_GET_PARAMETERS, null, null, null, null);
		$this->sameBillingAndShippingAddress = null;
		$this->showBillingAddressPanelRadio = null;
		$this->showShippingAddressPanelRadio = null;
		$this->addressSwitcher = null;
		$this->billingAddressPanel = null;
		$this->billingName = null;
		$this->billingAddressLine1 = null;
		$this->billingAddressLine2 = null;
		$this->billingAddressZip = null;
		$this->billingAddressState = null;
		$this->billingAddressStateSelect = null;
		$this->billingAddressCity = null;
		$this->billingAddressCountry = null;
		$this->shippingAddressPanel = null;
		$this->shippingName = null;
		$this->shippingAddressLine1 = null;
		$this->shippingAddressLine2 = null;
		$this->shippingAddressZip = null;
		$this->shippingAddressState = null;
		$this->shippingAddressStateSelect = null;
		$this->shippingAddressCity = null;
		$this->shippingAddressCountry = null;
		$this->coupon = MM_WPFS_ControlUtils::createControl(
			$this->formHash,
			self::FIELD_COUPON,
			/* translators: Form field placeholder for coupon code */
			__('Enter coupon code', 'wp-full-stripe'),
			null,
			/* translators: Form field label for coupon code */
			__('Coupon', 'wp-full-stripe'),
			null
		);
		$this->customInputs = array();
		$this->termsOfUseAccepted = MM_WPFS_ControlUtils::createControl($this->formHash, self::FIELD_TERMS_OF_USE_ACCEPTED, null, null, $this->form->termsOfUseLabel, null);
		$this->submitButton = MM_WPFS_ControlUtils::createControl($this->formHash, MM_WPFS_FormViewConstants::BUTTON_SUBMIT, null, $this->getSubmitButtonCaptionForForm($form), null, null);

		$this->coupon->setTooltip(
			/* translators: Tooltip label for the coupon code field */
			__('Apply a coupon or promotional code for a discount to your subscription or payment', 'wp-full-stripe')
		);

		$this->action->setAttributes(
			array(
				'type' => 'hidden'
			)
		);
		$this->formName->setAttributes(
			array(
				'type' => 'hidden'
			)
		);
		$this->formName->setValue($this->form->name);
		$this->formGetParameters->setAttributes(
			array(
				'type' => 'hidden'
			)
		);
		$this->termsOfUseAccepted->setLabelEscape(MM_WPFS_Control::ESCAPE_TYPE_NONE);

		$this->prepareCustomInputs();
		$this->prepareAddresses();

	}

	/**
	 * @return array
	 */
	public function getFieldConfiguration(): array
	{
		return $this->fieldConfiguration;
	}

	public function getFormHash()
	{
		$formType = MM_WPFS_Utils::getFormType($this->form);
		$formId = MM_WPFS_Utils::getFormId($this->form);

		return esc_attr(
			MM_WPFS_Utils::generateFormHash(
				$formType,
				$formId,
				$this->form->name
			)
		);
	}

	protected function isCheckoutForm()
	{
		return method_exists($this, 'getCheckoutFormAttributes');
	}

	/**
	 * @return array
	 */
	protected function getFormAttributes()
	{
		$attributes = array();

		$attributes[self::ATTR_ACTION] = '';
		$attributes[self::ATTR_METHOD] = self::ATTR_METHOD_VALUE_POST;
		$attributes[self::ATTR_CLASS] = self::ATTR_CLASS_VALUE_WPFS_FORM_WPFS_W_60;
		$attributes[self::ATTR_ID] = MM_WPFS_Utils::generateCSSFormID($this->formHash);
		$attributes[self::ATTR_DATA_WPFS_FORM_ID] = $this->form->name;
		$attributes[self::ATTR_DATA_WPFS_FORM_HASH] = $this->formHash;
		if (1 == $this->form->showCustomInput && $this->form->customInputs) {
			$attributes[self::ATTR_DATA_WPFS_CUSTOM_INPUT_TITLE] = $this->form->customInputTitle;
			$attributes[self::ATTR_DATA_WPFS_CUSTOM_INPUTS] = $this->form->customInputs;
			$attributes[self::ATTR_DATA_WPFS_CUSTOM_INPUT_REQUIRED] = $this->form->customInputRequired;
		}
		if (isset($this->form->showAddress) && 1 == $this->form->showAddress) {
			$attributes[self::ATTR_DATA_WPFS_SHOW_ADDRESS] = $this->form->showAddress;
		}
		if (isset($this->form->showBillingAddress) && 1 == $this->form->showBillingAddress) {
			$attributes[self::ATTR_DATA_WPFS_SHOW_BILLING_ADDRESS] = $this->form->showBillingAddress;
		}
		if (isset($this->form->showShippingAddress) && 1 == $this->form->showShippingAddress) {
			$attributes[self::ATTR_DATA_WPFS_SHOW_SHIPPING_ADDRESS] = $this->form->showShippingAddress;
		}
		$attributes[self::ATTR_DATA_WPFS_SHOW_TERMS_OF_USE] = $this->form->showTermsOfUse;
		$attributes[self::ATTR_DATA_WPFS_TERMS_OF_USE_NOT_CHECKED_ERROR_MESSAGE] = $this->form->termsOfUseNotCheckedErrorMessage;
		$attributes[self::ATTR_DATA_WPFS_DECIMAL_SEPARATOR] = $this->form->decimalSeparator;
		$attributes[self::ATTR_DATA_WPFS_SHOW_CURRENCY_SYMBOL_INSTEAD_OF_CODE] = $this->form->showCurrencySymbolInsteadOfCode;
		$attributes[self::ATTR_DATA_WPFS_SHOW_CURRENCY_SIGN_AT_FIRST_POSITION] = $this->form->showCurrencySignAtFirstPosition;
		$attributes[self::ATTR_DATA_WPFS_PUT_WHITESPACE_BETWEEN_CURRENCY_AND_AMOUNT] = $this->form->putWhitespaceBetweenCurrencyAndAmount;
		if (isset($this->form->stripeElementsTheme)) {
			$attributes[self::ATTR_DATA_WPFS_ELEMENTS_THEME] = $this->form->stripeElementsTheme;
		}
		if (isset($this->form->stripeElementsFont)) {
			$attributes[self::ATTR_DATA_WPFS_ELEMENTS_FONT] = $this->form->stripeElementsFont;
		}
		return $attributes;
	}

	/**
	 * @param $control MM_WPFS_Control
	 * @param $fieldConfig MM_WPFS_FormFieldConfiguration
	 * @return void
	 */
	protected function configureTextControl($control, $fieldConfig)
	{
		if ($fieldConfig->isConfigurable() && !is_null($fieldConfig->getValue())) {
			$control->setValue($fieldConfig->getValue());
			if ($fieldConfig->isReadonly()) {
				$control->setAttributes(
					['readonly' => 'readonly']
				);
			}
		}
	}

	protected function getSubmitButtonRawCaptionForForm($form)
	{
		$submitButtonCaption = null;

		if (
			$this instanceof MM_WPFS_CheckoutPaymentFormView ||
			$this instanceof MM_WPFS_CheckoutSaveCardFormView ||
			$this instanceof MM_WPFS_CheckoutDonationFormView ||
			$this instanceof MM_WPFS_CheckoutSubscriptionFormView
		) {
			$submitButtonCaption = MM_WPFS_Localization::translateLabel($this->form->openButtonTitle);
		} elseif (
			$this instanceof MM_WPFS_InlinePaymentFormView ||
			$this instanceof MM_WPFS_InlineSaveCardFormView ||
			$this instanceof MM_WPFS_InlineDonationFormView ||
			$this instanceof MM_WPFS_InlineSubscriptionFormView
		) {
			$submitButtonCaption = MM_WPFS_Localization::translateLabel($form->buttonTitle);
		}

		return $submitButtonCaption;
	}

	/**
	 * @param $form
	 *
	 * @return string
	 */
	protected function getSubmitButtonCaptionForForm($form)
	{
		$submitButtonCaption = $this->getSubmitButtonRawCaptionForForm($form);

		return $this->insertAmountIntoLabel($submitButtonCaption, '');
	}

	protected function containsAmountMacro($label)
	{
		return false === strpos($label, MM_WPFS_FormViewConstants::MACRO_SUBMIT_BUTTON_CAPTION_AMOUNT) ? false : true;
	}

	protected function insertAmountIntoLabel($label, $amountLabel)
	{
		if (is_null($label)) {
			return $label;
		}
		$labelWithAmount = $label;
		if (false !== strpos($labelWithAmount, MM_WPFS_FormViewConstants::MACRO_SUBMIT_BUTTON_CAPTION_AMOUNT)) {
			$labelWithAmount = trim(str_replace(MM_WPFS_FormViewConstants::MACRO_SUBMIT_BUTTON_CAPTION_AMOUNT, $amountLabel, $labelWithAmount));
		} else {
			$labelWithAmount .= ' ' . $amountLabel;
		}

		return $labelWithAmount;
	}

	protected function prepareCustomInputs()
	{
		if (1 == $this->form->showCustomInput) {
			// tnagy legacy forms have only one custom input field
			if (is_null($this->form->customInputs)) {
				$control = MM_WPFS_ControlUtils::createControl($this->formHash, self::FIELD_CUSTOM_INPUT, null, null, MM_WPFS_Localization::translateLabel($this->form->customInputTitle), null);
				array_push($this->customInputs, $control);
			} else {
				$customInputLabels = MM_WPFS_Utils::decodeCustomFieldLabels($this->form->customInputs);
				foreach ($customInputLabels as $index => $label) {
					$customInputLabel = MM_WPFS_Localization::translateLabel($label);
					$control = MM_WPFS_ControlUtils::createControl($this->formHash, self::FIELD_CUSTOM_INPUT, null, null, $customInputLabel, $index);
					$control->setMultiValue(true);
					$control->setAttributes(
						array(
							self::ATTR_DATA_WPFS_CUSTOM_INPUT_FIELD => 'input',
							self::ATTR_DATA_WPFS_CUSTOM_INPUT_LABEL => $customInputLabel
						)
					);

					/** @var MM_WPFS_FormFieldConfiguration $customFieldConfig */
					$customFieldConfig = $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_CUSTOM_PREFIX . ($index + 1)];
					if ($customFieldConfig->isConfigurable() && !is_null($customFieldConfig->getValue())) {
						$control->setValue($customFieldConfig->getValue());

						if ($customFieldConfig->isReadonly()) {
							$control->setAttributes(
								array_merge($control->attributes(false), ['readonly' => 'readonly'])
							);
						}
					}

					array_push($this->customInputs, $control);
				}
			}
		}
	}

	protected function prepareCountryFilterParams()
	{
		return [
			'formName' => $this->form->name,
			'formType' => MM_WPFS_Utils::getFormType($this->form),
		];
	}

	protected function prepareBillingCountryOptions(&$selectedCountryCode)
	{
		return $this->prepareFilteredCountryOptions($selectedCountryCode, 'fullstripe_billing_countries');
	}

	protected function prepareShippingCountryOptions(&$selectedCountryCode)
	{
		return $this->prepareFilteredCountryOptions($selectedCountryCode, 'fullstripe_shipping_countries');
	}

	protected function prepareFilteredCountryOptions(&$selectedCountryCode, $filterName)
	{
		$countryCodes = MM_WPFS_Countries::getAvailableCountryCodes();

		try {
			$countryCodes = apply_filters(
				$filterName,
				$countryCodes,
				$this->prepareCountryFilterParams()
			);
		} catch (Exception $ex) {
			$this->logger->error(__FUNCTION__, 'Cannot get country options from filter', $ex);
		}

		if (is_null($selectedCountryCode) || !array_search($selectedCountryCode, $countryCodes)) {
			$selectedCountryCode = count($countryCodes) > 0 ? $countryCodes[0] : MM_WPFS::COUNTRY_CODE_UNITED_STATES;
		}

		return $this->prepareCountryOptions($countryCodes, $selectedCountryCode);
	}

	protected function prepareCountryOptions($countryCodes, $selectedCountryCode)
	{
		$locallySelectedCountryCode = $selectedCountryCode;
		$availableCountries = MM_WPFS_Countries::getCountriesByCode($countryCodes);
		$countryOptions = array();
		$defaultCountrySelected = false;

		if (count($availableCountries) > 1) {
			$selectCountryOption = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				null,
				null,
				/* translators: Option label for the default option of the country selector dropdown */
				__('Select country', 'wp-full-stripe'),
				null,
				null
			);
			$selectCountryOption->setAttributes(
				array(
					'disabled' => 'disabled'
				)
			);

			if (!array_key_exists($locallySelectedCountryCode, $availableCountries)) {
				$selectCountryOption->setAttributes(
					array(
						'disabled' => 'disabled',
						'selected' => 'selected'
					)
				);

				$defaultCountrySelected = true;
			}

			array_push($countryOptions, $selectCountryOption);
		} else {
			$locallySelectedCountryCode = $countryCodes[0];
		}

		foreach ($availableCountries as $countryKey => $country) {
			$option = MM_WPFS_ControlUtils::createControl($this->formHash, null, null, MM_WPFS_Localization::translateLabel($country['name']), null, null);
			if (!$defaultCountrySelected && isset($locallySelectedCountryCode) && $locallySelectedCountryCode === $country['alpha-2']) {
				$defaultCountrySelected = true;
				$option->setAttributes(array('selected' => 'selected'));
			}
			$option->setValue($countryKey);
			array_push($countryOptions, $option);
		}

		return $countryOptions;
	}

	protected function prepareStateOptions($selectedStateCode)
	{
		$stateOptions = array();

		$selectStateOption = MM_WPFS_ControlUtils::createControl(
			$this->formHash,
			null,
			null,
			/* translators: Option label for the default option of the country selector dropdown */
			__('Select state', 'wp-full-stripe'),
			null,
			null
		);
		$selectStateOption->setAttributes(
			array(
				'disabled' => 'disabled'
			)
		);
		array_push($stateOptions, $selectStateOption);
		$defaultStateSelected = false;
		foreach (MM_WPFS_States::getAvailableStates() as $stateKey => $state) {
			$option = MM_WPFS_ControlUtils::createControl($this->formHash, null, null, MM_WPFS_Localization::translateLabel($state['name']), null, null);
			if (isset($selectedStateCode) && $selectedStateCode === $state['code']) {
				$defaultStateSelected = true;
				$option->setAttributes(array('selected' => 'selected'));
			}
			$option->setValue($stateKey);
			array_push($stateOptions, $option);
		}
		if (false === $defaultStateSelected) {
			$selectStateOption->setAttributes(
				array(
					'disabled' => 'disabled',
					'selected' => 'selected'
				)
			);
		}

		return $stateOptions;
	}

	protected function prepareAddresses()
	{
		$this->defaultBillingCountry = $this->form->defaultBillingCountry;
		$this->defaultShippingCountry = $this->form->defaultBillingCountry;

		if ($this->showAddressSwitcher()) {
			$this->sameBillingAndShippingAddress = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				self::FIELD_SAME_BILLING_AND_SHIPPING_ADDRESS,
				null,
				null,
				/* translators: Label for a checkbox which controls whether billing and shipping address can be entered separately  */
				__('Same billing and shipping address', 'wp-full-stripe'),
				null
			);
			$this->addressSwitcher = MM_WPFS_ControlUtils::createControl($this->formHash, self::FIELD_ADDRESS_SWITCHER, null, null, null, null);
			$this->showBillingAddressPanelRadio = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				self::FIELD_ADDRESS_SWITCHER,
				null,
				null,
				/* translators: Form field label for billing address */
				__('Billing address', 'wp-full-stripe'),
				0
			);
			$this->showShippingAddressPanelRadio = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				self::FIELD_ADDRESS_SWITCHER,
				null,
				null,
				/* translators: Form field label for shipping address */
				__('Shipping address', 'wp-full-stripe'),
				1
			);
		}

		if (isset($this->form->showAddress) && 1 == $this->form->showAddress) {
			$defaultBillingCountry = $this->defaultBillingCountry;
			/** @var  $billingCountryField MM_WPFS_FormFieldConfiguration */
			$billingCountryField = $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_BILLING_COUNTRY_CODE];

			if ($billingCountryField->isConfigurable() && !is_null($billingCountryField->getValue())) {
				$defaultBillingCountry = $billingCountryField->getValue();
			}
			$countryOptions = $this->prepareBillingCountryOptions($defaultBillingCountry);
			$this->defaultBillingCountry = $defaultBillingCountry;

			$this->billingAddressPanel = MM_WPFS_ControlUtils::createControl($this->formHash, self::FIELD_BILLING_ADDRESS_PANEL, null, null, null, null);

			$this->billingName = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				self::FIELD_BILLING_NAME,
				null,
				null,
				/* translators: Form field label for the billing name */
				__('Billing name', 'wp-full-stripe'),
				null
			);
			$this->configureTextControl($this->billingName, $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_BILLING_NAME]);

			$this->billingAddressLine1 = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				self::FIELD_BILLING_ADDRESS_LINE1,
				null,
				null,
				/* translators: Form field label for the billing address */
				__('Billing address street', 'wp-full-stripe'),
				null
			);
			$this->configureTextControl($this->billingAddressLine1, $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_BILLING_ADDRESS]);

			$this->billingAddressLine2 = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				self::FIELD_BILLING_ADDRESS_LINE2,
				null,
				null,
				/* translators: Form field label for the billing address line 2 */
				__('Billing address line 2', 'wp-full-stripe'),
				null
			);
			$this->configureTextControl($this->billingAddressLine2, $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_BILLING_ADDRESS2]);

			$this->billingAddressZip = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				self::FIELD_BILLING_ADDRESS_ZIP,
				null,
				null,
				/* translators: Form field label for the postal code of the billing address */
				__('Postal code', 'wp-full-stripe'),
				null
			);
			$this->configureTextControl($this->billingAddressZip, $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_BILLING_ZIP]);


			$this->billingAddressState = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				self::FIELD_BILLING_ADDRESS_STATE,
				null,
				null,
				/* translators: Form field label for the state of the billing address */
				__('State', 'wp-full-stripe'),
				null
			);
			$this->billingAddressStateSelect = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				self::FIELD_BILLING_ADDRESS_STATE_SELECT,
				null,
				null,
				__('State', 'wp-full-stripe'),
				null
			);

			$defaultBillingState = null;
			if ($defaultBillingCountry == MM_WPFS::COUNTRY_CODE_UNITED_STATES) {
				/** @var  $billingStateField MM_WPFS_FormFieldConfiguration */
				$billingStateField = $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_BILLING_STATE];
				if ($billingStateField->isConfigurable() && !is_null($billingStateField->getValue())) {
					$defaultBillingState = MM_WPFS_States::getStateCodeByName($billingStateField->getValue());
				}
			} else {
				$this->configureTextControl($this->billingAddressState, $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_BILLING_STATE]);
			}
			$this->billingAddressStateSelect->setOptions($this->prepareStateOptions($defaultBillingState));

			$this->billingAddressCity = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				self::FIELD_BILLING_ADDRESS_CITY,
				null,
				null,
				/* translators: Form field label for the city of the billing address */
				__('City', 'wp-full-stripe'),
				null
			);
			$this->configureTextControl($this->billingAddressCity, $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_BILLING_CITY]);


			$this->billingAddressCountry = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				self::FIELD_BILLING_ADDRESS_COUNTRY,
				null,
				null,
				/* translators: Form field label for the country of the billing address */
				__('Country', 'wp-full-stripe'),
				null
			);
			$this->billingAddressCountry->setOptions($countryOptions);

			if (isset($this->form->vatRateType)) {
				$this->billingAddressCountry->setAttributes(array(self::ATTR_DATA_WPFS_VAT_RATE_TYPE => $this->form->vatRateType));
			}
		}
		if (isset($this->form->showShippingAddress) && 1 == $this->form->showShippingAddress) {
			$defaultShippingCountry = $this->defaultShippingCountry;
			/** @var  $shippingCountryField MM_WPFS_FormFieldConfiguration */
			$shippingCountryField = $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_SHIPPING_COUNTRY_CODE];

			if ($shippingCountryField->isConfigurable() && !is_null($shippingCountryField->getValue())) {
				$defaultShippingCountry = $shippingCountryField->getValue();
			}
			$countryOptions = $this->prepareShippingCountryOptions($defaultShippingCountry);
			$this->defaultShippingCountry = $defaultShippingCountry;

			$this->shippingAddressPanel = MM_WPFS_ControlUtils::createControl($this->formHash, self::FIELD_SHIPPING_ADDRESS_PANEL, null, null, null, null);

			$this->shippingName = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				self::FIELD_SHIPPING_NAME,
				null,
				null,
				/* translators: Form field label for the shipping name */
				__('Shipping name', 'wp-full-stripe'),
				null
			);
			$this->configureTextControl($this->shippingName, $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_SHIPPING_NAME]);

			$this->shippingAddressLine1 = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				self::FIELD_SHIPPING_ADDRESS_LINE1,
				null,
				null,
				/* translators: Form field label for the shipping address */
				__('Shipping address street', 'wp-full-stripe'),
				null
			);
			$this->configureTextControl($this->shippingAddressLine1, $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_SHIPPING_ADDRESS]);

			$this->shippingAddressLine2 = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				self::FIELD_SHIPPING_ADDRESS_LINE2,
				null,
				null,
				/* translators: Form field label for the shipping address line 2 */
				__('Shipping address line 2', 'wp-full-stripe'),
				null
			);
			$this->configureTextControl($this->shippingAddressLine2, $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_SHIPPING_ADDRESS2]);

			$this->shippingAddressZip = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				self::FIELD_SHIPPING_ADDRESS_ZIP,
				null,
				null,
				/* translators: Form field label for the postal code of the shipping address */
				__('Postal code', 'wp-full-stripe'),
				null
			);
			$this->configureTextControl($this->shippingAddressZip, $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_SHIPPING_ZIP]);

			$this->shippingAddressState = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				self::FIELD_SHIPPING_ADDRESS_STATE,
				null,
				null,
				/* translators: Form field label for the state of the shipping address */
				__('State', 'wp-full-stripe'),
				null
			);
			$this->shippingAddressStateSelect = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				self::FIELD_SHIPPING_ADDRESS_STATE_SELECT,
				null,
				null,
				__('State', 'wp-full-stripe'),
				null
			);

			$defaultShippingState = null;
			if ($defaultShippingCountry == MM_WPFS::COUNTRY_CODE_UNITED_STATES) {
				/** @var  $shippingStateField MM_WPFS_FormFieldConfiguration */
				$shippingStateField = $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_SHIPPING_STATE];
				if ($shippingStateField->isConfigurable() && !is_null($shippingStateField->getValue())) {
					$defaultShippingState = MM_WPFS_States::getStateCodeByName($shippingStateField->getValue());
				}
			} else {
				$this->configureTextControl($this->shippingAddressState, $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_SHIPPING_STATE]);
			}
			$this->shippingAddressStateSelect->setOptions($this->prepareStateOptions($defaultShippingState));

			$this->shippingAddressCity = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				self::FIELD_SHIPPING_ADDRESS_CITY,
				null,
				null,
				/* translators: Form field label for the city of the shipping address */
				__('City', 'wp-full-stripe'),
				null
			);
			$this->configureTextControl($this->shippingAddressCity, $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_SHIPPING_CITY]);

			$this->shippingAddressCountry = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				self::FIELD_SHIPPING_ADDRESS_COUNTRY,
				null,
				null,
				/* translators: Form field label for the country of the shipping address */
				__('Country', 'wp-full-stripe'),
				null
			);
			$this->shippingAddressCountry->setOptions($countryOptions);
		}
	}

	/**
	 * @return bool
	 */
	protected function showAddressSwitcher()
	{
		$showBillingAddress = false;
		$showShippingAddress = false;
		if (isset($this->form->showAddress) && 1 == $this->form->showAddress) {
			$showBillingAddress = true;
		} elseif (isset($this->form->showBillingAddress) && 1 == $this->form->showBillingAddress) {
			$showBillingAddress = true;
		}
		if (isset($this->form->showShippingAddress) && 1 == $this->form->showShippingAddress) {
			$showShippingAddress = true;
		}

		return $showBillingAddress && $showShippingAddress;
	}

	/**
	 * @return bool
	 */
	protected function showAnyAddress()
	{
		$showAnyAddress = false;
		if (isset($this->form->showAddress) && 1 == $this->form->showAddress) {
			$showAnyAddress = true;
		}
		if (
			false === $showAnyAddress
			&& (isset($this->form->showBillingAddress) && 1 == $this->form->showBillingAddress)
		) {
			$showAnyAddress = true;
		}
		if (
			false === $showAnyAddress
			&& (isset($this->form->showShippingAddress) && 1 == $this->form->showShippingAddress)
		) {
			$showAnyAddress = true;
		}

		return $showAnyAddress;
	}

	/**
	 * Note: billing and shipping address fields must be added in child classes manually.
	 *
	 * @return array
	 */
	public static function getFields()
	{
		$fields = array(
			self::FIELD_ACTION => MM_WPFS_ControlUtils::inputHidden(self::FIELD_ACTION),
			self::FIELD_FORM_NAME => MM_WPFS_ControlUtils::inputHidden(self::FIELD_FORM_NAME),
			self::FIELD_CUSTOM_INPUT => MM_WPFS_ControlUtils::customInput(self::FIELD_CUSTOM_INPUT),
			self::FIELD_COUPON => MM_WPFS_ControlUtils::inputGroup(self::FIELD_COUPON),
			self::FIELD_TERMS_OF_USE_ACCEPTED => MM_WPFS_ControlUtils::checkbox(self::FIELD_TERMS_OF_USE_ACCEPTED),
			self::FIELD_GOOGLE_RECAPTCHA_RESPONSE => MM_WPFS_ControlUtils::captcha(self::FIELD_GOOGLE_RECAPTCHA_RESPONSE),
			self::FIELD_NONCE => MM_WPFS_ControlUtils::inputHidden(self::FIELD_NONCE)
		);

		return $fields;
	}

	protected static function getPopupBillingAddressFields()
	{
		$fields = array(
			self::FIELD_BILLING_ADDRESS_LINE1 => MM_WPFS_ControlUtils::inputHidden(self::FIELD_BILLING_ADDRESS_LINE1),
			self::FIELD_BILLING_ADDRESS_LINE2 => MM_WPFS_ControlUtils::inputHidden(self::FIELD_BILLING_ADDRESS_LINE2),
			self::FIELD_BILLING_ADDRESS_ZIP => MM_WPFS_ControlUtils::inputHidden(self::FIELD_BILLING_ADDRESS_ZIP),
			self::FIELD_BILLING_ADDRESS_STATE => MM_WPFS_ControlUtils::inputHidden(self::FIELD_BILLING_ADDRESS_STATE),
			self::FIELD_BILLING_ADDRESS_CITY => MM_WPFS_ControlUtils::inputHidden(self::FIELD_BILLING_ADDRESS_CITY),
			self::FIELD_BILLING_ADDRESS_COUNTRY => MM_WPFS_ControlUtils::inputHidden(self::FIELD_BILLING_ADDRESS_COUNTRY),
		);

		return $fields;
	}

	protected static function getInlineSameBillingAndShippingAddressField()
	{
		return array(
			self::FIELD_SAME_BILLING_AND_SHIPPING_ADDRESS => MM_WPFS_ControlUtils::checkbox(self::FIELD_SAME_BILLING_AND_SHIPPING_ADDRESS)
		);
	}

	protected static function getInlineBillingAddressFields()
	{
		$fields = array(
			self::FIELD_BILLING_NAME => MM_WPFS_ControlUtils::input(self::FIELD_BILLING_NAME),
			self::FIELD_BILLING_ADDRESS_LINE1 => MM_WPFS_ControlUtils::input(self::FIELD_BILLING_ADDRESS_LINE1),
			self::FIELD_BILLING_ADDRESS_LINE2 => MM_WPFS_ControlUtils::input(self::FIELD_BILLING_ADDRESS_LINE2),
			self::FIELD_BILLING_ADDRESS_ZIP => MM_WPFS_ControlUtils::input(self::FIELD_BILLING_ADDRESS_ZIP),
			self::FIELD_BILLING_ADDRESS_STATE => MM_WPFS_ControlUtils::input(self::FIELD_BILLING_ADDRESS_STATE),
			self::FIELD_BILLING_ADDRESS_STATE_SELECT => MM_WPFS_ControlUtils::selectMenu(self::FIELD_BILLING_ADDRESS_STATE_SELECT),
			self::FIELD_BILLING_ADDRESS_CITY => MM_WPFS_ControlUtils::input(self::FIELD_BILLING_ADDRESS_CITY),
			self::FIELD_BILLING_ADDRESS_COUNTRY => MM_WPFS_ControlUtils::selectMenu(self::FIELD_BILLING_ADDRESS_COUNTRY),
		);

		return $fields;
	}

	protected static function getInlineShippingAddressFields()
	{
		$fields = array(
			self::FIELD_SHIPPING_NAME => MM_WPFS_ControlUtils::input(self::FIELD_SHIPPING_NAME),
			self::FIELD_SHIPPING_ADDRESS_LINE1 => MM_WPFS_ControlUtils::input(self::FIELD_SHIPPING_ADDRESS_LINE1),
			self::FIELD_SHIPPING_ADDRESS_LINE2 => MM_WPFS_ControlUtils::input(self::FIELD_SHIPPING_ADDRESS_LINE2),
			self::FIELD_SHIPPING_ADDRESS_ZIP => MM_WPFS_ControlUtils::input(self::FIELD_SHIPPING_ADDRESS_ZIP),
			self::FIELD_SHIPPING_ADDRESS_STATE => MM_WPFS_ControlUtils::input(self::FIELD_SHIPPING_ADDRESS_STATE),
			self::FIELD_SHIPPING_ADDRESS_STATE_SELECT => MM_WPFS_ControlUtils::selectMenu(self::FIELD_SHIPPING_ADDRESS_STATE_SELECT),
			self::FIELD_SHIPPING_ADDRESS_CITY => MM_WPFS_ControlUtils::input(self::FIELD_SHIPPING_ADDRESS_CITY),
			self::FIELD_SHIPPING_ADDRESS_COUNTRY => MM_WPFS_ControlUtils::selectMenu(self::FIELD_SHIPPING_ADDRESS_COUNTRY),
		);

		return $fields;
	}

	public function isCouponFieldVisible()
	{
		$result = isset($this->form->showCouponInput) && 1 == $this->form->showCouponInput && method_exists($this, 'coupon');
		if ($this instanceof MM_WPFS_CheckoutSubscriptionFormView || $this instanceof MM_WPFS_CheckoutPaymentFormView) {
			$result = false;
		}

		return $result;
	}

	/**
	 * @return mixed
	 */
	public function getFormName()
	{
		return (isset($this->form) && isset($this->form->name)) ? $this->form->name : null;
	}

	public function _formName()
	{
		echo ((isset($this->form) && isset($this->form->name)) ? esc_attr($this->form->name) : null);
	}

	/**
	 * @return array
	 */
	public function customInputs()
	{
		return $this->customInputs;
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function coupon()
	{
		return $this->coupon;
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function tOUAccepted()
	{
		return $this->termsOfUseAccepted;
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function submitButton()
	{
		return $this->submitButton;
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function sameBillingAndShippingAddress()
	{
		return $this->sameBillingAndShippingAddress;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function addressSwitcher()
	{
		return $this->addressSwitcher;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function billingAddressRadio()
	{
		return $this->showBillingAddressPanelRadio;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function shippingAddressRadio()
	{
		return $this->showShippingAddressPanelRadio;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function billingAddressPanel()
	{
		return $this->billingAddressPanel;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function billingName()
	{
		return $this->billingName;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function billingAddressLine1()
	{
		return $this->billingAddressLine1;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function billingAddressLine2()
	{
		return $this->billingAddressLine2;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function billingAddressCity()
	{
		return $this->billingAddressCity;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function billingAddressState()
	{
		return $this->billingAddressState;
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function billingAddressStateSelect(): MM_WPFS_Control
	{
		return $this->billingAddressStateSelect;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function billingAddressZip()
	{
		return $this->billingAddressZip;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function billingAddressCountry()
	{
		return $this->billingAddressCountry;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function shippingAddressPanel()
	{
		return $this->shippingAddressPanel;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function shippingName()
	{
		return $this->shippingName;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function shippingAddressLine1()
	{
		return $this->shippingAddressLine1;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function shippingAddressLine2()
	{
		return $this->shippingAddressLine2;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function shippingAddressCity()
	{
		return $this->shippingAddressCity;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function shippingAddressState()
	{
		return $this->shippingAddressState;
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function shippingAddressStateSelect(): MM_WPFS_Control
	{
		return $this->shippingAddressStateSelect;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function shippingAddressZip()
	{
		return $this->shippingAddressZip;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function shippingAddressCountry()
	{
		return $this->shippingAddressCountry;
	}

	public function formAttributes()
	{
		$attributesAsString = MM_WPFS_Control::attributesAsString($this->attributes);

		echo ($attributesAsString);
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function action()
	{
		return $this->action;
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function formName()
	{
		return $this->formName;
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function formGetParameters()
	{
		return $this->formGetParameters;
	}

	/**
	 * @return mixed
	 */
	public function getCurrentVATPercent()
	{
		return $this->__currentVATPercent;
	}

	/**
	 * @return mixed
	 */
	public function getDefaultBillingCountry()
	{
		return $this->defaultBillingCountry;
	}

	/**
	 * @return mixed
	 */
	public function getDefaultShippingCountry()
	{
		return $this->defaultShippingCountry;
	}

	/**
	 * @param $control MM_WPFS_Control
	 * @param $fieldConfig MM_WPFS_FormFieldConfiguration
	 * @return void
	 */
	protected function configureCustomAmountControl($control, $fieldConfig)
	{
		if ($fieldConfig->isConfigurable() && !is_null($fieldConfig->getValue())) {
			$control->setValue(
				MM_WPFS_Currencies::formatByForm($this->form, $this->form->currency, $fieldConfig->getValue(), false, false)
			);
			if ($fieldConfig->isReadonly()) {
				$control->setAttributes(
					array_merge($control->attributes(false), ['readonly' => 'readonly'])
				);
			}
		}
	}

	protected function calculateGrossAmount($displayItems)
	{
		$result = 0;

		foreach ($displayItems as $displayItem) {
			if (
				$displayItem->subType === MM_WPFS::LINE_ITEM_SUBTYPE_TAX &&
				$displayItem->inclusive === true
			) {
				continue;
			}

			$result += $displayItem->amount;
		}

		return $result;
	}

	protected function calculateGrossAmountForComponent($displayItems, $component)
	{
		$result = 0;

		foreach ($displayItems as $displayItem) {
			if ($displayItem->type === $component) {
				if (
					$displayItem->subType === MM_WPFS::LINE_ITEM_SUBTYPE_TAX &&
					$displayItem->inclusive === true
				) {
					continue;
				}

				$result += $displayItem->amount;
			}
		}

		return $result;
	}
}

abstract class MM_WPFS_SubscriptionFormView extends MM_WPFS_FormView implements MM_WPFS_SubscriptionFormViewConstants
{
	use MM_WPFS_FormView_PricingAddOn;
	use MM_WPFS_FormView_CouponAddOn;

	/** @var MM_WPFS_Stripe */
	protected $stripe;

	/** @var array $stripePlans */
	protected $stripePlans;
	/** @var array $selectedStripePlans */
	protected $selectedStripePlans;
	/** @var MM_WPFS_Control */
	protected $plans;
	/** @var MM_WPFS_Control */
	protected $firstPlan;
	/** @var MM_WPFS_Control */
	protected $planQuantity;
	/** @var array */
	protected $productPricing;

	/**
	 * MM_WPFS_SubscriptionFormView constructor.
	 *
	 * @param $form
	 * @param $stripe
	 */
	public function __construct($form, $fieldConfiguration, $stripe, $loggerService)
	{
		parent::__construct($form, $fieldConfiguration, $loggerService);

		$this->stripe = $stripe;

		$this->stripePlans = $this->stripe->getRecurringPrices();
		$this->plans = MM_WPFS_ControlUtils::createControl(
			$this->formHash,
			self::FIELD_PLAN,
			null,
			null,
			/* translators: Form field label for the plan selector */
			__('Choose subscription plan', 'wp-full-stripe'),
			null
		);
		$this->planQuantity = MM_WPFS_ControlUtils::createControl(
			$this->formHash,
			self::FIELD_PLAN_QUANTITY,
			null,
			null,
			/* translators: Form field label for subscription quantity */
			__('Subscription quantity', 'wp-full-stripe'),
			null
		);
		$this->plans->setAttributes(
			array(
				self::ATTR_DATA_WPFS_FORM_ID => $this->form->name
			)
		);

		$this->preparePlans();
		$this->prepareFirstPlan();
		$this->preparePlanQuantity();
		$this->prepareProductPricing();
		$this->formatProductPricing();
	}

	protected function getSelectedPriceId()
	{
		$result = null;

		foreach ($this->plans->options() as $control) {
			/** @var MM_WPFS_Control $control */
			$attributes = $control->attributes(false);

			if (array_key_exists('checked', $attributes) || array_key_exists('selected', $attributes)) {
				$result = $attributes[self::ATTR_DATA_WPFS_VALUE];
				break;
			}
		}

		if (is_null($result)) {
			$control = $this->plans->options()[0];
			$result = $control->attributes(false)[self::ATTR_DATA_WPFS_VALUE];
		}

		return $result;
	}

	protected function formatProductLabel($productName, $priceLabel)
	{
		$taxTicker = $this->getTaxLabel();
		$taxLabel = $this->isCheckoutForm() && !empty($taxTicker) ? (' + ' . $taxTicker) : '';

		return $productName . ' - ' . $priceLabel . $taxLabel;
	}

	protected function formatProductPricing()
	{
		foreach ($this->plans->options() as $control) {
			/** @var MM_WPFS_Control $control */
			$attributes = $control->attributes(false);

			$priceId = $attributes[self::ATTR_DATA_WPFS_VALUE];
			$currency = $attributes[self::ATTR_DATA_WPFS_CURRENCY];
			$interval = $attributes[self::ATTR_DATA_WPFS_INTERVAL];
			$intervalCount = $attributes[self::ATTR_DATA_WPFS_INTERVAL_COUNT];

			$setupFeeInCents = $this->calculateGrossAmountForComponent($this->productPricing[$priceId], MM_WPFS::LINE_ITEM_TYPE_SETUP_FEE);
			$productPriceInCents = $this->calculateGrossAmountForComponent($this->productPricing[$priceId], MM_WPFS::LINE_ITEM_TYPE_PRODUCT);

			$amountLabel = MM_WPFS_Localization::getPriceAndIntervalLabel(
				$interval,
				$intervalCount,
				MM_WPFS_Currencies::formatAndEscapeByForm($this->form, $currency, $productPriceInCents, true, true)
			);
			$productLabel = $this->formatProductLabel($attributes[self::ATTR_DATA_WPFS_PRODUCT_NAME], $amountLabel);

			$control->setLabel($productLabel);
			$attributes[self::ATTR_DATA_WPFS_PLAN_AMOUNT] = MM_WPFS_Currencies::formatAmount($currency, $productPriceInCents, true);
			$attributes[self::ATTR_DATA_WPFS_PLAN_AMOUNT_IN_SMALLEST_COMMON_CURRENCY] = $productPriceInCents;
			$attributes[self::ATTR_DATA_WPFS_PLAN_SETUP_FEE] = MM_WPFS_Currencies::formatAmount($currency, $setupFeeInCents, true);
			$attributes[self::ATTR_DATA_WPFS_PLAN_SETUP_FEE_IN_SMALLEST_COMMON_CURRENCY] = $setupFeeInCents;

			$control->setAttributes($attributes);
		}
	}

	protected function prepareProductPricing()
	{
		$coupon = $this->prepareCouponData();

		if ($this->form->allowMultipleSubscriptions == 1) {
			$defaultQuantity = $this->form->minimumQuantityOfSubscriptions + 0;
			if ($defaultQuantity < 1) {
				$defaultQuantity = 1;
			}
		} else {
			$defaultQuantity = 1;
		}

		$pricingData = new \StdClass;
		$pricingData->formType = MM_WPFS_Utils::getFormType($this->form);
		$pricingData->formId = $this->form->name;
		$pricingData->country = $this->getDefaultCountry();
		$pricingData->state = null;
		$pricingData->zip = null;
		$pricingData->taxIdType = null;
		$pricingData->taxId = null;
		$pricingData->couponCode = is_null($coupon) ? null : $coupon->name;
		$pricingData->couponPercentOff = is_null($coupon) ? true : (!($coupon->amount_off > 0));
		$pricingData->customAmount = null;
		$pricingData->quantity = $defaultQuantity;
		$pricingData->stripeTax = MM_WPFS_Utils::getFormType($this->form) === MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION &&
			$this->form->vatRateType === MM_WPFS::FIELD_VALUE_TAX_RATE_STRIPE_TAX;

		$this->productPricing = MM_WPFS_Pricing::createFormPriceCalculator($pricingData, $this->loggerService)->getProductPrices();
	}

	protected function getOptionSelectedAttribute()
	{
		$result = 'checked';

		if (MM_WPFS::PLAN_SELECTOR_STYLE_DROPDOWN === $this->form->planSelectorStyle) {
			$result = 'selected';
		}

		return $result;
	}


	protected function preparePlans()
	{
		if (!empty($this->stripePlans)) {
			$planOptions = array();
			$this->selectedStripePlans = MM_WPFS_Utils::getSortedFormPlans($this->stripePlans, $this->form->decoratedPlans);

			/** @var MM_WPFS_FormFieldConfiguration $priceFieldConfig */
			$priceFieldConfig = $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_PRICE];

			$isPriceConfigurable = $priceFieldConfig->isConfigurable() && !is_null($priceFieldConfig->getValue());
			$selectedPriceId = $isPriceConfigurable ? $priceFieldConfig->getValue() : null;

			$planIndex = 0;
			$taxLabel = $this->getTaxLabel();
			$selectedAttribute = $this->getOptionSelectedAttribute();
			foreach ($this->selectedStripePlans as $selectedStripePlan) {
				$planId = $selectedStripePlan->stripePlan->id;
				$planCurrency = $selectedStripePlan->stripePlan->currency;
				$planCancellationCount = $selectedStripePlan->properties->cancellationCount;    // todo: for legacy plans, it's in the metadata
				$planAmount = $selectedStripePlan->stripePlan->unit_amount;
				$planSetupFee = $selectedStripePlan->properties->setupFee;             // todo: for legacy plans, it's in the metadata
				$planName = $selectedStripePlan->stripePlan->product->name;
				$planInterval = $selectedStripePlan->stripePlan->recurring->interval;
				$planIntervalCount = $selectedStripePlan->stripePlan->recurring->interval_count;

				$currencyArray = MM_WPFS_Currencies::getCurrencyFor($planCurrency);

				$controlLabel = MM_WPFS_Localization::translateLabel($planName);
				$formattedAmount = MM_WPFS_Currencies::formatAndEscapeByForm($this->form, $planCurrency, $planAmount, true, true);
				$amountLabel = MM_WPFS_Localization::getPriceAndIntervalLabel($planInterval, $planIntervalCount, $formattedAmount) . (!empty($taxLabel) ? (' + ' . $taxLabel) : '');

				$name = null;
				if (MM_WPFS::PLAN_SELECTOR_STYLE_RADIO_BUTTONS === $this->form->planSelectorStyle) {
					$name = self::FIELD_PLAN;
				}
				$plan = MM_WPFS_ControlUtils::createControl($this->formHash, $name, null, null, null, $planIndex);
				$plan->setValue($planId);

				$planAttributes = array(
					self::ATTR_DATA_WPFS_VALUE => $planId,
					self::ATTR_DATA_WPFS_PLAN_AMOUNT => MM_WPFS_Currencies::formatAmount($planCurrency, $planAmount, true),
					self::ATTR_DATA_WPFS_PLAN_AMOUNT_IN_SMALLEST_COMMON_CURRENCY => $planAmount,
					self::ATTR_DATA_WPFS_PLAN_SETUP_FEE => MM_WPFS_Currencies::formatAmount($planCurrency, $planSetupFee, true),
					self::ATTR_DATA_WPFS_PLAN_SETUP_FEE_IN_SMALLEST_COMMON_CURRENCY => $planSetupFee,           // todo: for legacy plans, it's in the metadata
					self::ATTR_DATA_WPFS_PRODUCT_NAME => $controlLabel,
					self::ATTR_DATA_WPFS_INTERVAL => $planInterval,
					self::ATTR_DATA_WPFS_INTERVAL_COUNT => $planIntervalCount,
					self::ATTR_DATA_WPFS_CANCELLATION_COUNT => $planCancellationCount,  // todo: for legacy plans, it's in the metadata
					self::ATTR_DATA_WPFS_CURRENCY => $planCurrency,
					self::ATTR_DATA_WPFS_ZERO_DECIMAL_SUPPORT => $currencyArray['zeroDecimalSupport'] ? 'true' : 'false',
					self::ATTR_DATA_WPFS_CURRENCY_SYMBOL => MM_WPFS_Currencies::getCurrencySymbolFor($planCurrency)
				);
				if ($isPriceConfigurable && $planId == $selectedPriceId) {
					$planAttributes[$selectedAttribute] = $selectedAttribute;
				}

				$plan->setAttributes($planAttributes);

				$plan->setLabel(sprintf('%s - <strong>%s</strong>', $controlLabel, $amountLabel), MM_WPFS_Control::ESCAPE_TYPE_NONE);
				array_push($planOptions, $plan);

				$planIndex++;
			}
			$this->plans->setOptions($planOptions);

			if (count($this->plans->options()) == 0) {
				$this->submitButton->setAttributes(
					array_merge(
						$this->submitButton->attributes(false),
						array('disabled' => true)
					)
				);
			}
		}
	}

	protected function prepareFirstPlan()
	{
		$firstStripePlan = null;
		if (!empty($this->stripePlans)) {
			if (sizeof($this->selectedStripePlans) > 0) {
				$firstStripePlan = $this->selectedStripePlans[0];
			}
		}
		if (!is_null($firstStripePlan)) {
			$planId = $firstStripePlan->stripePlan->id;
			$planCurrency = $firstStripePlan->stripePlan->currency;
			$planCancellationCount = $firstStripePlan->properties->cancellationCount;
			$planAmount = $firstStripePlan->stripePlan->unit_amount;
			$planSetupFee = $firstStripePlan->properties->setupFee;
			$planName = $firstStripePlan->stripePlan->product->name;
			$planInterval = $firstStripePlan->stripePlan->recurring->interval;
			$planIntervalCount = $firstStripePlan->stripePlan->recurring->interval_count;

			$currencyArray = MM_WPFS_Currencies::getCurrencyFor($planCurrency);

			$this->firstPlan = MM_WPFS_ControlUtils::createControl($this->formHash, self::FIELD_PLAN, null, null, null, null);
			$this->firstPlan->setValue($planId);
			$this->firstPlan->setAttributes(
				array(
					'type' => 'hidden',
					self::ATTR_DATA_WPFS_VALUE => $planId,
					self::ATTR_DATA_WPFS_PLAN_AMOUNT => MM_WPFS_Currencies::formatAmount($planCurrency, $planAmount, true),
					self::ATTR_DATA_WPFS_PLAN_AMOUNT_IN_SMALLEST_COMMON_CURRENCY => $planAmount,
					self::ATTR_DATA_WPFS_PLAN_SETUP_FEE => MM_WPFS_Currencies::formatAmount($planCurrency, $planSetupFee, true),
					self::ATTR_DATA_WPFS_PLAN_SETUP_FEE_IN_SMALLEST_COMMON_CURRENCY => $planSetupFee,
					self::ATTR_DATA_WPFS_PRODUCT_NAME => MM_WPFS_Localization::translateLabel($planName),
					self::ATTR_DATA_WPFS_INTERVAL => $planInterval,
					self::ATTR_DATA_WPFS_INTERVAL_COUNT => $planIntervalCount,
					self::ATTR_DATA_WPFS_CANCELLATION_COUNT => $planCancellationCount,
					self::ATTR_DATA_WPFS_CURRENCY => $planCurrency,
					self::ATTR_DATA_WPFS_ZERO_DECIMAL_SUPPORT => $currencyArray['zeroDecimalSupport'] ? 'true' : 'false',
					self::ATTR_DATA_WPFS_CURRENCY_SYMBOL => MM_WPFS_Currencies::getCurrencySymbolFor($planCurrency)
				)
			);
		}
	}

	protected function preparePlanQuantity()
	{
		$attributes = array(
			self::ATTR_DATA_WPFS_STEPPER => self::FIELD_PLAN_QUANTITY,
			self::ATTR_DATA_DEFAULT_VALUE => 1,
			self::ATTR_DATA_MINIMUM_VALUE => 1
		);
		if (isset($this->form->minimumQuantityOfSubscriptions) && $this->form->minimumQuantityOfSubscriptions > 0) {
			$attributes[self::ATTR_DATA_MINIMUM_VALUE] = $this->form->minimumQuantityOfSubscriptions;
			$attributes[self::ATTR_DATA_DEFAULT_VALUE] = $this->form->minimumQuantityOfSubscriptions;
		}
		if (isset($this->form->maximumQuantityOfSubscriptions) && $this->form->maximumQuantityOfSubscriptions > 0) {
			$attributes[self::ATTR_DATA_MAXIMUM_VALUE] = $this->form->maximumQuantityOfSubscriptions;
		}
		$this->planQuantity->setAttributes($attributes);
	}

	/**
	 * @return array
	 */
	public static function getFields()
	{

		$fields = array(
			self::FIELD_PLAN => MM_WPFS_ControlUtils::selectMenu(self::FIELD_PLAN),
			self::FIELD_PLAN_QUANTITY => MM_WPFS_ControlUtils::stepper(self::FIELD_PLAN_QUANTITY)
		);

		return array_merge($fields, parent::getFields());
	}

	/**
	 * @return array
	 */
	public function getSelectedStripePlanIds()
	{
		$planList = array();

		foreach ($this->selectedStripePlans as $plan) {
			$planList[$plan->stripePlan->id] = $plan->stripePlan->id;
		}

		return $planList;
	}

	/**
	 * @return array
	 */
	protected function getFormAttributes()
	{
		$attributes = array();

		$attributes[self::ATTR_DATA_WPFS_TAX_RATE_TYPE] = $this->form->vatRateType;
		$attributes[self::ATTR_DATA_WPFS_SHOW_COUPON_FIELD] = $this->isCouponFieldVisible() ? 'true' : 'false';
		$attributes[self::ATTR_DATA_WPFS_SELECTOR_STYLE] = $this->form->planSelectorStyle;

		return array_merge($attributes, parent::getFormAttributes());
	}


	public function firstPlan()
	{
		return $this->firstPlan;
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function plans()
	{
		return $this->plans;
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function planQuantity()
	{
		return $this->planQuantity;
	}

	/**
	 * @return array
	 */
	public function getProductPricing(): array
	{
		return $this->productPricing;
	}
}

trait MM_WPFS_FormView_PricingAddOn
{
	protected function getDefaultCountry()
	{
		return empty($this->form->defaultBillingCountry) ? MM_WPFS::COUNTRY_CODE_UNITED_STATES : $this->form->defaultBillingCountry;
	}

	/**
	 * @param $taxRates array
	 * @return string
	 */
	private function extractTaxLabel($taxRates)
	{
		$result = '';

		if (count($taxRates) > 0) {
			$result = $taxRates[0]->displayName;

			if (count($taxRates) > 1) {
				foreach ($taxRates as $taxRate) {
					if ($taxRate->displayName !== $result) {
						$result = __('Tax', 'wp-full-stripe');
						break;
					}
				}
			}
		}

		return $result;
	}

	protected function areExclusiveTaxRates($taxRates)
	{
		$result = true;

		if (count($taxRates) > 0) {
			foreach ($taxRates as $taxRate) {
				if ($taxRate->inclusive) {
					$result = false;
					break;
				}
			}
		}

		return $result;
	}

	protected function getTaxLabel()
	{
		$taxRates = json_decode($this->form->vatRates);
		$result = '';

		if (
			$this->form->vatRateType !== null &&
			$this->form->vatRateType !== MM_WPFS::FIELD_VALUE_TAX_RATE_NO_TAX &&
			$this->form->vatRateType !== MM_WPFS::FIELD_VALUE_TAX_RATE_STRIPE_TAX &&
			$this->areExclusiveTaxRates($taxRates)
		) {
			$result = $this->extractTaxLabel($taxRates);
		}

		return $result;
	}
}

interface MM_WPFS_FormView_InlineTaxAddOnConstants
{
	const FIELD_BUYING_AS_BUSINESS = 'wpfs-buying-as-business';
	const FIELD_BUSINESS_NAME = 'wpfs-business-name';
	const FIELD_TAX_ID = 'wpfs-tax-id';
	const FIELD_TAX_ID_TYPE = 'wpfs-tax-id-type';
	const FIELD_TAX_COUNTRY = 'wpfs-tax-country';
	const FIELD_TAX_STATE = 'wpfs-tax-state';
	const FIELD_TAX_ZIP = 'wpfs-tax-zip';
}

trait MM_WPFS_FormView_InlineTax_AddOn
{
	/** @var MM_WPFS_Control */
	protected $buyingAsBusiness;
	/** @var MM_WPFS_Control */
	protected $businessName;
	/** @var MM_WPFS_Control */
	protected $taxId;
	/** @var MM_WPFS_Control */
	protected $taxCountry;
	/** @var MM_WPFS_Control */
	protected $taxState;
	/** @var MM_WPFS_Control */
	protected $taxZip;
	/** @var MM_WPFS_Control */
	protected $taxIdType;

	protected function initInlineTaxFields()
	{
		$this->prepareBuyingAsBusiness();
		$this->prepareTaxCountryFields();
	}

	protected function prepareTaxCountryFields()
	{
		$this->taxCountry = MM_WPFS_ControlUtils::createControl(
			$this->formHash,
			self::FIELD_TAX_COUNTRY,
			null,
			null,
			__('Country', 'wp-full-stripe'),
			null
		);
		$this->taxCountry->setOptions($this->prepareCountryOptions(MM_WPFS_Countries::getAvailableCountries(), $this->getDefaultCountry()));

		$this->taxState = MM_WPFS_ControlUtils::createControl(
			$this->formHash,
			self::FIELD_TAX_STATE,
			null,
			null,
			__('State', 'wp-full-stripe'),
			null
		);
		$this->taxState->setOptions($this->prepareStateOptions(null));

		$this->taxZip = MM_WPFS_ControlUtils::createControl(
			$this->formHash,
			self::FIELD_TAX_ZIP,
			null,
			null,
			__('Postal code', 'wp-full-stripe'),
			null
		);
	}

	protected function prepareBuyingAsBusiness()
	{
		$this->buyingAsBusiness = MM_WPFS_ControlUtils::createControl(
			$this->formHash,
			self::FIELD_BUYING_AS_BUSINESS,
			null,
			null,
			__("I'm buying as a business", 'wp-full-stripe'),
			null
		);

		$this->businessName = MM_WPFS_ControlUtils::createControl(
			$this->formHash,
			self::FIELD_BUSINESS_NAME,
			null,
			null,
			__('Business name', 'wp-full-stripe'),
			null
		);

		$this->taxIdType = MM_WPFS_ControlUtils::createControl(
			$this->formHash,
			self::FIELD_TAX_ID_TYPE,
			null,
			null,
			/* translators: Form field label for the country of the billing address */
			__('Tax ID Type', 'wp-full-stripe'),
			null
		);
		$this->taxIdType->setOptions($this->prepareTaxTypeOptions($this->getDefaultCountry()));

		$this->taxId = MM_WPFS_ControlUtils::createControl(
			$this->formHash,
			self::FIELD_TAX_ID,
			null,
			null,
			__('Tax ID', 'wp-full-stripe'),
			null
		);
	}

	/**
	 * @param $defaultCountryCode string
	 * @return array
	 */
	protected function prepareTaxTypeOptions($defaultCountryCode)
	{
		$result = [];
		$taxIdTypesByCountry = MM_WPFS_CustomerTaxId::getTaxIdTypesByCountry();

		$isTaxIdSelected = false;
		$isCountrySupported = array_key_exists($defaultCountryCode, $taxIdTypesByCountry);
		foreach (MM_WPFS_CustomerTaxId::getUniqueTaxIdTypes() as $taxIdItem) {
			$id = $taxIdItem['id'];

			$option = MM_WPFS_ControlUtils::createControl($this->formHash, null, null, $taxIdItem['description'], null, null);
			$option->setValue($id);

			$attributes = [];
			if (!$isTaxIdSelected && $isCountrySupported) {
				$countryItem = $taxIdTypesByCountry[$defaultCountryCode];

				if (array_key_exists($id, $countryItem)) {
					$attributes['selected'] = 'selected';
					$isTaxIdSelected = true;
				}
			}
			$option->setAttributes($attributes);

			$result[] = $option;
		}

		return $result;
	}

	protected static function getInlineTaxFields()
	{
		return [
			self::FIELD_BUYING_AS_BUSINESS => MM_WPFS_ControlUtils::checkbox(self::FIELD_BUYING_AS_BUSINESS),
			self::FIELD_BUSINESS_NAME => MM_WPFS_ControlUtils::input(self::FIELD_BUSINESS_NAME),
			self::FIELD_TAX_ID_TYPE => MM_WPFS_ControlUtils::input(self::FIELD_TAX_ID_TYPE),
			self::FIELD_TAX_ID => MM_WPFS_ControlUtils::input(self::FIELD_TAX_ID),
			self::FIELD_TAX_COUNTRY => MM_WPFS_ControlUtils::selectMenu(self::FIELD_TAX_COUNTRY),
			self::FIELD_TAX_STATE => MM_WPFS_ControlUtils::selectMenu(self::FIELD_TAX_STATE),
			self::FIELD_TAX_ZIP => MM_WPFS_ControlUtils::input(self::FIELD_TAX_ZIP),
		];
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function buyingAsBusiness(): MM_WPFS_Control
	{
		return $this->buyingAsBusiness;
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function businessName(): MM_WPFS_Control
	{
		return $this->businessName;
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function taxId(): MM_WPFS_Control
	{
		return $this->taxId;
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function taxIdType(): MM_WPFS_Control
	{
		return $this->taxIdType;
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function taxCountry(): MM_WPFS_Control
	{
		return $this->taxCountry;
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function taxState(): MM_WPFS_Control
	{
		return $this->taxState;
	}

	/**
	 * @return MM_WPFS_Control
	 */
	public function taxZip(): MM_WPFS_Control
	{
		return $this->taxZip;
	}
}

trait MM_WPFS_FormView_CouponAddOn
{
	protected $couponData;

	protected function prepareCouponData()
	{
		$result = null;
		$couponFieldConfig = $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_COUPON];

		$this->couponData = null;
		if ($this->isCouponFieldVisible() && $couponFieldConfig->isConfigurable() && !is_null($couponFieldConfig->getValue())) {
			$couponCode = $couponFieldConfig->getValue();

			$coupon = $this->stripe->retrieveCouponByPromotionalCodeOrCouponCode($couponCode);
			if (!is_null($coupon)) {
				$formType = MM_WPFS_Utils::getFormType($this->form);
				$formId = $this->form->name;

				$isApplicable = MM_WPFS::getInstance()->isCouponApplicableToForm(
					$coupon,
					$formType,
					$formId,
					$this->getSelectedPriceId()
				);

				if ($isApplicable->applicableToProduct) {
					$result = $coupon;

					$discountedPriceIds = MM_WPFS::getInstance()->getDiscountedPriceIdsByCouponAndForm($coupon, $formType, $formId);
					$this->couponData = array(
						'id' => $coupon->id,
						'name' => $couponCode,
						'currency' => $coupon->currency,
						'percent_off' => $coupon->percent_off,
						'amount_off' => $coupon->amount_off,
						'discounted_price_ids' => $discountedPriceIds
					);

					$this->logger->debug(__FUNCTION__, "Coupon code {$couponCode} is applicable to form");
				}
			} else {
				$this->logger->debug(__FUNCTION__, "Coupon code {$couponCode} is NOT applicable to form");
			}
		}

		return $result;
	}

	/**
	 * @return array
	 */
	public function getCouponData()
	{
		return $this->couponData;
	}
}

abstract class MM_WPFS_PaymentFormView extends MM_WPFS_FormView implements MM_WPFS_PaymentFormViewConstants
{
	use MM_WPFS_FormView_PricingAddOn;
	use MM_WPFS_FormView_CouponAddOn;

	/** @var MM_WPFS_Stripe */
	protected $stripe;

	protected $currencyCode;
	protected $currencySymbol;
	protected $currencyName;
	protected $currencyZeroDecimalSupport;
	/** @var MM_WPFS_Control */
	protected $customAmount;
	/** @var MM_WPFS_Control */
	protected $customAmountOptions;
	/** @var array */
	protected $productPricing;

	/**
	 * MM_WPFS_PaymentFormView constructor.
	 *
	 * @param $form
	 */
	public function __construct($form, $fieldConfiguration, $stripe, $loggerService)
	{
		parent::__construct($form, $fieldConfiguration, $loggerService);

		$this->stripe = $stripe;

		$this->currencyCode = null;
		$this->currencyName = null;
		$this->currencySymbol = null;
		$this->currencyZeroDecimalSupport = null;
		$this->customAmount = null;
		$this->customAmountOptions = null;

		$this->prepareCurrency();
		$this->prepareCustomAmountAndOptions();
		$this->prepareProductPricing();
		$this->formatProductPricing();

		$this->attributes = $this->getFormAttributes();

	}

	protected function getCustomAmountForPricing()
	{
		$result = null;

		/** @var MM_WPFS_FormFieldConfiguration $amountFieldConfig */
		$amountFieldConfig = $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_AMOUNT];
		$isAmountConfigurable = $amountFieldConfig->isConfigurable() && !is_null($amountFieldConfig->getValue());

		if (
			$isAmountConfigurable && (
				MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT == $this->form->customAmount ||
				(MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS == $this->form->customAmount && 1 == $this->form->allowListOfAmountsCustom))
		) {
			$result = $amountFieldConfig->getValue();
		}

		return $result;
	}

	protected function getSelectedPriceId()
	{
		$result = null;

		if (MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT == $this->form->customAmount) {
			$result = 'customAmount';
		} else if (MM_WPFS::PAYMENT_TYPE_SPECIFIED_AMOUNT == $this->form->customAmount) {
			/** @var MM_WPFS_Control $control */
			$control = $this->customAmountOptions->options()[0];
			$result = $control->attributes(false)[self::ATTR_DATA_WPFS_AMOUNT_PRICE_ID];
		} else if (MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS == $this->form->customAmount) {
			foreach ($this->customAmountOptions->options() as $control) {
				/** @var MM_WPFS_Control $control */
				$attributes = $control->attributes(false);

				if (array_key_exists('checked', $attributes) || array_key_exists('selected', $attributes)) {
					$result = $attributes[self::ATTR_DATA_WPFS_AMOUNT_PRICE_ID];
					break;
				}
			}

			if (is_null($result)) {
				$control = $this->customAmountOptions->options()[0];
				$result = $control->attributes(false)[self::ATTR_DATA_WPFS_AMOUNT_PRICE_ID];
			}
		}

		return $result;
	}

	protected function prepareProductPricing()
	{
		$coupon = $this->prepareCouponData();

		$pricingData = new \StdClass;
		$pricingData->formType = MM_WPFS_Utils::getFormType($this->form);
		$pricingData->formId = $this->form->name;
		$pricingData->country = $this->getDefaultCountry();
		$pricingData->state = null;
		$pricingData->zip = null;
		$pricingData->taxIdType = null;
		$pricingData->taxId = null;
		$pricingData->couponCode = is_null($coupon) ? null : $coupon->name;
		$pricingData->couponPercentOff = is_null($coupon) ? true : (!($coupon->amount_off > 0));
		$pricingData->customAmount = $this->getCustomAmountForPricing();
		$pricingData->quantity = 1;
		$pricingData->stripeTax = MM_WPFS_Utils::getFormType($this->form) === MM_WPFS::FORM_TYPE_INLINE_PAYMENT &&
			$this->form->vatRateType === MM_WPFS::FIELD_VALUE_TAX_RATE_STRIPE_TAX;

		$this->productPricing = MM_WPFS_Pricing::createFormPriceCalculator($pricingData, $this->loggerService)->getProductPrices();
	}

	protected function formatProductLabel($productName, $amountInCents)
	{
		$amountLabel = MM_WPFS_Currencies::formatAndEscapeByForm($this->form, $this->currencyCode, $amountInCents, false);
		$taxTicker = $this->getTaxLabel();
		$taxLabel = $this->isCheckoutForm() && !empty($taxTicker) ? (' + ' . $taxTicker) : '';

		switch ($this->form->amountSelectorStyle) {
			case MM_WPFS::SELECTOR_STYLE_BUTTON_GROUP:
				return $amountLabel . $taxLabel;

			case MM_WPFS::SELECTOR_STYLE_DROPDOWN:
			case MM_WPFS::SELECTOR_STYLE_RADIO_BUTTONS:
			default:
				return MM_WPFS_Localization::translateLabel($productName) . ' - ' . $amountLabel . $taxLabel;
		}
	}

	protected function formatProductPricing()
	{
		if (!empty($this->customAmountOptions)) {
			foreach ($this->customAmountOptions->options() as $control) {
				/** @var MM_WPFS_Control $control */
				$attributes = $control->attributes(false);

				$priceId = $attributes[self::ATTR_DATA_WPFS_AMOUNT_PRICE_ID];
				if ($priceId === MM_WPFS::PRICE_ID_CUSTOM_AMOUNT) {
					continue;
				}

				$amountInCents = $this->calculateGrossAmount($this->productPricing[$priceId]);
				$productLabel = $this->formatProductLabel(
					$attributes[self::ATTR_DATA_WPFS_PRODUCT_NAME],
					$amountInCents
				);

				$control->setLabel($productLabel);
				$attributes[self::ATTR_DATA_WPFS_AMOUNT_DESCRIPTION] = $productLabel;
				$attributes[self::ATTR_DATA_WPFS_AMOUNT_IN_SMALLEST_COMMON_CURRENCY] = $amountInCents;

				$control->setValue(MM_WPFS_Currencies::formatAmount($this->currencyCode, $amountInCents));

				$control->setAttributes($attributes);
			}
		}
	}

	protected function prepareCurrency()
	{
		$currency = MM_WPFS_Currencies::getCurrencyFor($this->form->currency);
		$this->currencyCode = $currency['code'];
		$this->currencyName = $currency['name'];
		$this->currencySymbol = $currency['symbol'];
		$this->currencyZeroDecimalSupport = $currency['zeroDecimalSupport'];
	}

	protected function getAmountButtonTitle()
	{
		$result = $this->form->buttonTitle;
		if ($this instanceof MM_WPFS_CheckoutPaymentFormView || $this instanceof MM_WPFS_CheckoutSaveCardFormView) {
			$result = $this->form->openButtonTitle;
		}

		return $result;
	}

	protected function getOptionSelectedAttribute()
	{
		$result = 'checked';

		if (MM_WPFS::SELECTOR_STYLE_DROPDOWN === $this->form->amountSelectorStyle) {
			$result = 'selected';
		}

		return $result;
	}

	protected function prepareCustomAmountAndOptions()
	{
		/** @var MM_WPFS_FormFieldConfiguration $priceFieldConfig */
		$priceFieldConfig = $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_PRICE];

		$isPriceConfigurable = $priceFieldConfig->isConfigurable() && !is_null($priceFieldConfig->getValue());
		$selectedPriceId = $isPriceConfigurable ? $priceFieldConfig->getValue() : null;

		/** @var MM_WPFS_FormFieldConfiguration $amountFieldConfig */
		$amountFieldConfig = $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_AMOUNT];
		$isAmountConfigurable = $amountFieldConfig->isConfigurable() && !is_null($amountFieldConfig->getValue());
		$isAmountConfigured = false;

		$amountButtonTitle = $this->getAmountButtonTitle();
		$attributes = array(
			self::ATTR_DATA_WPFS_BUTTON_TITLE => MM_WPFS_Localization::translateLabel($amountButtonTitle),
			self::ATTR_DATA_WPFS_CURRENCY => $this->currencyCode,
			self::ATTR_DATA_WPFS_ZERO_DECIMAL_SUPPORT => $this->currencyZeroDecimalSupport ? 'true' : 'false',
			self::ATTR_DATA_WPFS_CURRENCY_SYMBOL => $this->currencySymbol,
			self::ATTR_DATA_WPFS_FORM_ID => $this->formHash,
			self::ATTR_DATA_WPFS_SELECTOR_STYLE => $this->form->amountSelectorStyle,
			self::ATTR_DATA_WPFS_PRODUCT_NAME => MM_WPFS_Utils::getDefaultProductDescription(),

		);
		if (MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT == $this->form->customAmount) {
			$this->customAmount = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				self::FIELD_CUSTOM_AMOUNT_UNIQUE,
				null,
				null,
				/* translators: Form field label for the payment amount that can be entered manually (think paying invoices) */
				__('Amount', 'wp-full-stripe'),
				null
			);
			$customAmountOptionAttributes = array(
				self::ATTR_DATA_WPFS_AMOUNT_PRICE_ID => 'customAmount',
			);
			$this->customAmount->setAttributes(array_merge($attributes, $customAmountOptionAttributes));
			$this->customAmount->setLabelAttributes(
				array(
					'class' => 'wpfs-form-label'
				)
			);
			if ($isAmountConfigurable) {
				$this->configureCustomAmountControl($this->customAmount, $amountFieldConfig);
				$isAmountConfigured = true;
			}

		} elseif (
			MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS == $this->form->customAmount ||
			MM_WPFS::PAYMENT_TYPE_SPECIFIED_AMOUNT == $this->form->customAmount
		) {
			$this->customAmountOptions = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				self::FIELD_CUSTOM_AMOUNT,
				null,
				null,
				/* translators: Form field label for selecting a one-time payment amount */
				__('Product', 'wp-full-stripe'),
				null
			);
			$this->customAmountOptions->setAttributes($attributes);

			$listOfProducts = MM_WPFS_Utils::decodeJsonArray($this->form->decoratedProducts);
			if (count($listOfProducts) == 0 && MM_WPFS::PAYMENT_TYPE_SPECIFIED_AMOUNT == $this->form->customAmount) {
				$this->form->customAmount = MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS;
			} else if (count($listOfProducts) == 1 && $this->form->allowListOfAmountsCustom == 0) {
				$this->form->customAmount = MM_WPFS::PAYMENT_TYPE_SPECIFIED_AMOUNT;
			}

			$customAmountOptions = array();
			$lastIndex = -1;
			$index = 0;
			$selectedAttribute = $this->getOptionSelectedAttribute();
			foreach ($listOfProducts as $product) {
				$lastIndex = $index;
				$amount = $product->price;
				$descriptionLabel = $this->formatProductLabel($product->name, $amount);

				$customAmountOption = MM_WPFS_ControlUtils::createControl(
					$this->formHash,
					self::FIELD_CUSTOM_AMOUNT,
					null,
					null,
					$descriptionLabel,
					$index
				);
				$customAmountOption->setValue(MM_WPFS_Currencies::formatAmount($this->currencyCode, $amount));
				$optionAttributes = array(
					self::ATTR_DATA_WPFS_AMOUNT_IN_SMALLEST_COMMON_CURRENCY => $amount,
					self::ATTR_DATA_WPFS_AMOUNT_INDEX => $index,
					self::ATTR_DATA_WPFS_AMOUNT_DESCRIPTION => $descriptionLabel,
					self::ATTR_DATA_WPFS_AMOUNT_PRICE_ID => $product->stripePriceId,
					self::ATTR_DATA_WPFS_PRODUCT_NAME => MM_WPFS_Localization::translateLabel($product->name),
				);
				if (!$isAmountConfigurable && $isPriceConfigurable && $product->stripePriceId == $selectedPriceId) {
					$optionAttributes[$selectedAttribute] = $selectedAttribute;
				}

				$optionAttributes = array_merge($attributes, $optionAttributes);
				$customAmountOption->setAttributes($optionAttributes);

				array_push($customAmountOptions, $customAmountOption);
				$index++;
			}
			if (1 == $this->form->allowListOfAmountsCustom) {
				$this->customAmount = MM_WPFS_ControlUtils::createControl(
					$this->formHash,
					self::FIELD_CUSTOM_AMOUNT_UNIQUE,
					null,
					null,
					/* translators: Form field label for the payment amount that can be entered manually (think paying invoices) */
					__('Amount', 'wp-full-stripe'),
					null
				);
				$customAmountAttributes = array_merge($attributes, [self::ATTR_DATA_WPFS_AMOUNT_PRICE_ID => 'customAmount']);
				if (count($customAmountOptions) > 0 && $isAmountConfigured) {
					$customAmountAttributes['disabled'] = true;
				}
				$this->customAmount->setAttributes($customAmountAttributes);
				$this->customAmount->setLabelAttributes(
					array(
						'class' => 'wpfs-sr-only'
					)
				);

				$customAmountOption = MM_WPFS_ControlUtils::createControl(
					$this->formHash,
					self::FIELD_CUSTOM_AMOUNT,
					null,
					null,
					/* translators: Button label for entering a payment amount manually (think donation form) */
					__('Other', 'wp-full-stripe'),
					$lastIndex + 1
				);
				$customAmountOption->setValue('other');
				$customAmountOptionAttributes = array_merge($attributes, array(self::ATTR_DATA_WPFS_AMOUNT_PRICE_ID => 'customAmount'));
				$customAmountOption->setAttributes($customAmountOptionAttributes);

				if ($isAmountConfigurable) {
					$this->configureCustomAmountControl($this->customAmount, $amountFieldConfig);

					$customAmountOption->setAttributes(
						array_merge($customAmountOption->attributes(false), [$selectedAttribute => $selectedAttribute])
					);
				}

				array_push($customAmountOptions, $customAmountOption);
			}

			$this->customAmountOptions->setOptions($customAmountOptions);

			if (count($this->customAmountOptions->options()) == 0) {
				$this->submitButton->setAttributes(
					array_merge(
						$this->submitButton->attributes(false),
						array('disabled' => true)
					)
				);
			}
		}
	}

	/**
	 * @return array
	 */
	protected function getFormAttributes()
	{
		$attributes = array();

		$attributes[self::ATTR_DATA_WPFS_AMOUNT_TYPE] = $this->form->customAmount;
		if (MM_WPFS::PAYMENT_TYPE_SPECIFIED_AMOUNT === $this->form->customAmount || MM_WPFS::PAYMENT_TYPE_CARD_CAPTURE === $this->form->customAmount) {
			$attributes[self::ATTR_DATA_WPFS_AMOUNT] = $this->form->amount;
			$attributes[self::ATTR_DATA_WPFS_CURRENCY] = $this->currencyCode;
			$attributes[self::ATTR_DATA_WPFS_ZERO_DECIMAL_SUPPORT] = $this->currencyZeroDecimalSupport ? 'true' : 'false';
			$attributes[self::ATTR_DATA_WPFS_CURRENCY_SYMBOL] = $this->currencySymbol;
		} elseif (MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS === $this->form->customAmount) {
			$attributes[self::ATTR_DATA_WPFS_SELECTOR_STYLE] = $this->form->amountSelectorStyle;
			$attributes[self::ATTR_DATA_WPFS_ALLOW_LIST_OF_AMOUNTS_CUSTOM] = $this->form->allowListOfAmountsCustom;
		}
		$attributes[self::ATTR_DATA_WPFS_TAX_RATE_TYPE] = $this->form->vatRateType;
		$attributes[self::ATTR_DATA_WPFS_SHOW_COUPON_FIELD] = $this->isCouponFieldVisible() ? 'true' : 'false';

		return array_merge($attributes, parent::getFormAttributes());
	}

	/**
	 * @return array
	 */
	public static function getFields()
	{
		$fields = array(
			self::FIELD_CUSTOM_AMOUNT_UNIQUE => MM_WPFS_ControlUtils::inputGroup(self::FIELD_CUSTOM_AMOUNT_UNIQUE),
		);

		return array_merge($fields, parent::getFields());
	}

	/**
	 * @param $form
	 *
	 * @return string
	 */
	protected function getSubmitButtonCaptionForForm($form)
	{
		$submitButtonCaption = $this->getSubmitButtonRawCaptionForForm($form);
		$amountLabel = '';

		if (isset($form->customAmount)) {
			if (
				MM_WPFS::PAYMENT_TYPE_SPECIFIED_AMOUNT == $form->customAmount ||
				MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS == $form->customAmount
			) {
				$listOfProducts = json_decode($form->decoratedProducts);
				if (JSON_ERROR_NONE === json_last_error()) {
					$firstProduct = reset($listOfProducts);
					$amountLabel = MM_WPFS_Currencies::formatAndEscapeByForm($form, $form->currency, $firstProduct->price, false);
				}
			}
		}

		return $this->insertAmountIntoLabel($submitButtonCaption, $amountLabel);
	}

	public function _currencySign()
	{
		if (1 == $this->form->showCurrencySymbolInsteadOfCode) {
			$this->_currencySymbol();
		} else {
			$this->_currencyCode();
		}
	}

	public function _currencySymbol()
	{
		echo (esc_attr($this->currencySymbol));
	}

	public function _currencyCode()
	{
		echo (esc_attr($this->currencyCode));
	}

	public function showCurrencySignAtFirstPosition()
	{
		return 1 == $this->form->showCurrencySignAtFirstPosition;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function customAmount()
	{
		return $this->customAmount;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function customAmountOptions()
	{
		return $this->customAmountOptions;
	}

	/**
	 * @return array
	 */
	public function getProductPricing(): array
	{
		return $this->productPricing;
	}
}


class MM_WPFS_InlinePaymentFormView extends MM_WPFS_PaymentFormView implements MM_WPFS_FormView_InlineTaxAddOnConstants
{
	use MM_WPFS_InlineFormView;
	use MM_WPFS_FormView_InlineTax_AddOn;

	/**
	 * MM_WPFS_InlinePaymentFormView constructor.
	 *
	 * @param $form
	 */
	public function __construct($form, $fieldConfiguration, $stripe, $loggerService)
	{
		parent::__construct($form, $fieldConfiguration, $stripe, $loggerService);

		$this->initInlineFields($this->formHash);
		$this->initInlineTaxFields();

		$this->action->setValue(self::FIELD_ACTION_VALUE_INLINE_PAYMENT_CHARGE);

	}

	/**
	 * @return array
	 */
	public static function getFields()
	{
		$inlineFields = self::getInlineFields();
		$inlineFields = array_merge($inlineFields, self::getInlineSameBillingAndShippingAddressField());
		$inlineFields = array_merge($inlineFields, self::getInlineBillingAddressFields());
		$inlineFields = array_merge($inlineFields, self::getInlineShippingAddressFields());

		return array_merge($inlineFields, parent::getFields(), self::getInlineTaxFields());
	}

	/**
	 * @return array
	 */
	public function getFormAttributes()
	{
		$attributes = array();

		$attributes[self::ATTR_DATA_WPFS_FORM_TYPE] = MM_WPFS::FORM_TYPE_INLINE_PAYMENT;
		$attributes = array_merge($attributes, parent::getFormAttributes());

		$inlineFormAttributes = $this->getInlineFormAttributes($this->form);

		return array_merge($attributes, $inlineFormAttributes);
	}

}

class MM_WPFS_InlineSubscriptionFormView extends MM_WPFS_SubscriptionFormView implements MM_WPFS_FormView_InlineTaxAddOnConstants
{
	use MM_WPFS_InlineFormView;
	use MM_WPFS_FormView_InlineTax_AddOn;

	/**
	 * MM_WPFS_InlineSubscriptionFormView constructor.
	 *
	 * @param $form
	 * @param $stripe
	 */
	public function __construct($form, $fieldConfiguration, $stripe, $loggerService)
	{
		parent::__construct($form, $fieldConfiguration, $stripe, $loggerService);
		$this->initInlineFields($this->formHash);
		$this->initInlineTaxFields();

		$this->action->setValue(self::FIELD_ACTION_VALUE_INLINE_SUBSCRIPTION_CHARGE);

	}

	/**
	 * @return array
	 */
	public static function getFields()
	{
		$inlineFields = self::getInlineFields();
		$inlineFields = array_merge($inlineFields, self::getInlineSameBillingAndShippingAddressField());
		$inlineFields = array_merge($inlineFields, self::getInlineBillingAddressFields());
		$inlineFields = array_merge($inlineFields, self::getInlineShippingAddressFields());

		return array_merge($inlineFields, parent::getFields(), self::getInlineTaxFields());
	}

	/**
	 * @return array
	 */
	public function getFormAttributes()
	{

		$attributes = array();

		$attributes[self::ATTR_DATA_WPFS_FORM_TYPE] = MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION;
		$attributes = array_merge($attributes, parent::getFormAttributes());

		$inlineFormAttributes = $this->getInlineFormAttributes($this->form);

		return array_merge($attributes, $inlineFormAttributes);
	}

}

abstract class MM_WPFS_DonationFormView extends MM_WPFS_FormView implements MM_WPFS_DonationFormViewConstants
{

	protected $currencyCode;
	protected $currencySymbol;
	protected $currencyName;
	protected $currencyZeroDecimalSupport;

	protected $customAmount;
	/** @var MM_WPFS_Control */
	protected $donationAmountOptions;
	/** @var MM_WPFS_Control */
	protected $donationFrequencyOptions;

	/**
	 * MM_WPFS_PaymentFormView constructor.
	 *
	 * @param $form
	 */
	public function __construct($form, $fieldConfiguration, $loggerService)
	{
		parent::__construct($form, $fieldConfiguration, $loggerService);

		$this->currencyCode = null;
		$this->currencyName = null;
		$this->currencySymbol = null;
		$this->currencyZeroDecimalSupport = null;
		$this->customAmount = null;
		$this->donationAmountOptions = null;
		$this->donationFrequencyOptions = null;

		$this->prepareCurrency();
		$this->prepareDonationAmountOptions();
		$this->prepareDonationFrequencyOptions();

		$this->attributes = $this->getFormAttributes();

	}

	protected function prepareCurrency()
	{
		$currency = MM_WPFS_Currencies::getCurrencyFor($this->form->currency);
		$this->currencyCode = $currency['code'];
		$this->currencyName = $currency['name'];
		$this->currencySymbol = $currency['symbol'];
		$this->currencyZeroDecimalSupport = $currency['zeroDecimalSupport'];
	}


	protected function getAmountButtonTitle()
	{
		$amountButtonTitle = $this->form->buttonTitle;
		if ($this instanceof MM_WPFS_CheckoutDonationFormView) {
			$amountButtonTitle = $this->form->openButtonTitle;
		}

		return $amountButtonTitle;
	}

	protected function getSubmitButtonCaptionForForm($form)
	{
		$caption = $this->getAmountButtonTitle();

		if ($this->containsAmountMacro($caption)) {
			$donationAmounts = MM_WPFS_Utils::decodeJsonArray($this->form->donationAmounts);
			if (count($donationAmounts) > 0) {
				$donationAmount = (int) $donationAmounts[0];
				$amountLabel = MM_WPFS_Currencies::formatAndEscapeByForm($this->form, $this->form->currency, $donationAmount, false);

				$caption = $this->insertAmountIntoLabel($caption, $amountLabel);
			}
		}

		return $caption;
	}

	protected function createDonationFrequencyOption($label, $value, $index)
	{
		$frequencyOption = MM_WPFS_ControlUtils::createControl($this->formHash, self::FIELD_DONATION_FREQUENCY, null, null, $label, $index);
		$frequencyOption->setValue($value);
		$frequencyAttributes = array();
		$frequencyOption->setAttributes($frequencyAttributes);
		$frequencyOption->setLabel($label);

		return $frequencyOption;
	}

	/**
	 * @return bool
	 */
	protected function isRecurringDonationForm()
	{
		$isRecurringDonation = $this->form->allowDailyRecurring == 1 ||
			$this->form->allowWeeklyRecurring == 1 ||
			$this->form->allowMonthlyRecurring == 1 ||
			$this->form->allowAnnualRecurring == 1;

		return $isRecurringDonation;
	}

	/**
	 * @return false
	 */
	public function isCustomAmountOnly()
	{
		$res = false;

		if (
			!is_null($this->donationAmountOptions) &&
			!is_null($this->donationAmountOptions->options())
		) {
			if (count($this->donationAmountOptions->options()) == 1) {
				/* @var $onlyOption MM_WPFS_Control */
				$onlyOption = $this->donationAmountOptions->options()[0];

				if (MM_WPFS_DonationFormViewConstants::FIELD_VALUE_CUSTOM_AMOUNT_OTHER === $onlyOption->value(false)) {
					$res = true;
				}
			}
		}

		return $res;
	}

	/**
	 * @return false
	 */
	public function isOneSuggestedAmountOnly()
	{
		$res = false;

		if (
			!is_null($this->donationAmountOptions) &&
			!is_null($this->donationAmountOptions->options())
		) {
			if (count($this->donationAmountOptions->options()) == 1) {
				/* @var $onlyOption MM_WPFS_Control */
				$onlyOption = $this->donationAmountOptions->options()[0];

				if (MM_WPFS_DonationFormViewConstants::FIELD_VALUE_CUSTOM_AMOUNT_OTHER !== $onlyOption->value(false)) {
					$res = true;
				}
			}
		}

		return $res;
	}

	protected function prepareDonationFrequencyOptions()
	{
		$donationFrequencyAttributes = array();

		$this->donationFrequencyOptions = MM_WPFS_ControlUtils::createControl(
			$this->formHash,
			self::FIELD_DONATION_FREQUENCY,
			null,
			null,
			/* translators: Form field label for selecting a one-time donation amount */
			__('Donation frequency', 'wp-full-stripe'),
			null
		);
		$this->donationFrequencyOptions->setAttributes($donationFrequencyAttributes);

		$donationFrequencyOptions = array();
		$frequencyOptionIndex = 0;

		if ($this->form->allowOneTimeDonation == 1) {
			$oneTimeOption = $this->createDonationFrequencyOption(
				__('One-time', 'wp-full-stripe'),
				self::FIELD_VALUE_DONATION_FREQUENCY_ONE_TIME,
				$frequencyOptionIndex
			);
			array_push($donationFrequencyOptions, $oneTimeOption);
			$frequencyOptionIndex += 1;
		}

		if ($this->isRecurringDonationForm()) {
			// Let's add the frequencies selected by the the user
			if ($this->form->allowDailyRecurring == 1) {
				$dailyOption = $this->createDonationFrequencyOption(
					__('Daily', 'wp-full-stripe'),
					self::FIELD_VALUE_DONATION_FREQUENCY_DAILY,
					$frequencyOptionIndex
				);
				array_push($donationFrequencyOptions, $dailyOption);
				$frequencyOptionIndex += 1;
			}
			if ($this->form->allowWeeklyRecurring == 1) {
				$weeklyOption = $this->createDonationFrequencyOption(
					__('Weekly', 'wp-full-stripe'),
					self::FIELD_VALUE_DONATION_FREQUENCY_WEEKLY,
					$frequencyOptionIndex
				);
				array_push($donationFrequencyOptions, $weeklyOption);
				$frequencyOptionIndex += 1;
			}
			if ($this->form->allowMonthlyRecurring == 1) {
				$monthlyOption = $this->createDonationFrequencyOption(
					__('Monthly', 'wp-full-stripe'),
					self::FIELD_VALUE_DONATION_FREQUENCY_MONTHLY,
					$frequencyOptionIndex
				);
				array_push($donationFrequencyOptions, $monthlyOption);
				$frequencyOptionIndex += 1;
			}
			if ($this->form->allowAnnualRecurring == 1) {
				$annualOption = $this->createDonationFrequencyOption(
					__('Annual', 'wp-full-stripe'),
					self::FIELD_VALUE_DONATION_FREQUENCY_ANNUAL,
					$frequencyOptionIndex
				);
				array_push($donationFrequencyOptions, $annualOption);
			}
		}

		$this->donationFrequencyOptions->setOptions($donationFrequencyOptions);
	}

	protected function getOptionSelectedAttribute()
	{
		return 'checked';
	}

	protected function prepareDonationAmountOptions()
	{
		$amountButtonTitle = $this->getAmountButtonTitle();

		/** @var MM_WPFS_FormFieldConfiguration $amountFieldConfig */
		$amountFieldConfig = $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_AMOUNT];
		$isAmountConfigurable = $amountFieldConfig->isConfigurable() && !is_null($amountFieldConfig->getValue());
		$isAmountConfigured = false;

		$attributes = array(
			self::ATTR_DATA_WPFS_BUTTON_TITLE => MM_WPFS_Localization::translateLabel($amountButtonTitle),
			self::ATTR_DATA_WPFS_CURRENCY => $this->currencyCode,
			self::ATTR_DATA_WPFS_ZERO_DECIMAL_SUPPORT => $this->currencyZeroDecimalSupport ? 'true' : 'false',
			self::ATTR_DATA_WPFS_CURRENCY_SYMBOL => $this->currencySymbol,
			self::ATTR_DATA_WPFS_FORM_ID => $this->formHash,
			self::ATTR_DATA_WPFS_SHOW_AMOUNT => $this->containsAmountMacro($amountButtonTitle) ? 1 : 0,

		);

		$this->donationAmountOptions = MM_WPFS_ControlUtils::createControl(
			$this->formHash,
			self::FIELD_CUSTOM_AMOUNT,
			null,
			null,
			/* translators: Form field label for selecting a one-time donation amount */
			__('Donation amount', 'wp-full-stripe'),
			null
		);
		$this->donationAmountOptions->setAttributes($attributes);

		$donationAmountOptions = array();
		$donationAmountsArray = MM_WPFS_Utils::decodeJsonArray($this->form->donationAmounts);
		$selectedAttribute = $this->getOptionSelectedAttribute();
		$lastIndex = -1;
		foreach ($donationAmountsArray as $index => $listElement) {
			$lastIndex = $index;
			$donationAmount = (int) $listElement;

			if ($donationAmount !== 0) {
				$amountLabel = MM_WPFS_Currencies::formatAndEscapeByForm($this->form, $this->currencyCode, $donationAmount, false);
				$customAmountOption = MM_WPFS_ControlUtils::createControl($this->formHash, self::FIELD_CUSTOM_AMOUNT, null, null, $amountLabel, $index);
				$formattedAmount = MM_WPFS_Currencies::formatAmount($this->currencyCode, $donationAmount);
				$customAmountOption->setValue($formattedAmount);
				$optionAttributes = array(
					self::ATTR_DATA_WPFS_AMOUNT_IN_SMALLEST_COMMON_CURRENCY => $donationAmount,
					self::ATTR_DATA_WPFS_AMOUNT_INDEX => $index,
				);

				if ($isAmountConfigurable && $donationAmount == $amountFieldConfig->getValue()) {
					$optionAttributes[$selectedAttribute] = $selectedAttribute;
					$isAmountConfigured = true;
				}

				$optionAttributes = array_merge($attributes, $optionAttributes);
				$customAmountOption->setAttributes($optionAttributes);
				$customAmountOption->setLabel($amountLabel);
				array_push($donationAmountOptions, $customAmountOption);
			}
		}

		if (1 == $this->form->allowCustomDonationAmount) {
			$this->customAmount = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				self::FIELD_CUSTOM_AMOUNT_UNIQUE,
				null,
				null,
				/* translators: Form field label for the donation amount that can be entered manually */
				__('Amount', 'wp-full-stripe'),
				null
			);
			$customAmountAttributes = $attributes;
			if (count($donationAmountOptions) > 0 && $isAmountConfigured) {
				$customAmountAttributes = array_merge($customAmountAttributes, array('disabled' => true));
			}
			$this->customAmount->setAttributes($customAmountAttributes);
			$this->customAmount->setLabelAttributes(
				array(
					'class' => 'wpfs-sr-only'
				)
			);
			$customAmountOption = MM_WPFS_ControlUtils::createControl(
				$this->formHash,
				self::FIELD_CUSTOM_AMOUNT,
				null,
				null,
				/* translators: Button label for entering a payment amount manually (think donation form) */
				__('Other', 'wp-full-stripe'),
				$lastIndex + 1
			);
			$customAmountOption->setValue(MM_WPFS_DonationFormViewConstants::FIELD_VALUE_CUSTOM_AMOUNT_OTHER);
			$customAmountOption->setAttributes($attributes);

			if ($isAmountConfigurable && !$isAmountConfigured) {
				$this->configureCustomAmountControl($this->customAmount, $amountFieldConfig);

				$customAmountOption->setAttributes(
					array_merge($customAmountOption->attributes(false), [$selectedAttribute => $selectedAttribute])
				);
			}

			array_push($donationAmountOptions, $customAmountOption);
		}

		$this->donationAmountOptions->setOptions($donationAmountOptions);
	}

	/**
	 * @return array
	 */
	protected function getFormAttributes()
	{
		$attributes = array();

		$attributes[self::ATTR_DATA_WPFS_CURRENCY] = $this->currencyCode;
		$attributes[self::ATTR_DATA_WPFS_ZERO_DECIMAL_SUPPORT] = $this->currencyZeroDecimalSupport ? 'true' : 'false';
		$attributes[self::ATTR_DATA_WPFS_CURRENCY_SYMBOL] = $this->currencySymbol;
		$attributes[self::ATTR_DATA_WPFS_AMOUNT_TYPE] = $this->isCustomAmountOnly() ? MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT : MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS;
		$attributes[self::ATTR_DATA_WPFS_ALLOW_LIST_OF_AMOUNTS_CUSTOM] = $this->form->allowCustomDonationAmount;
		$attributes[self::ATTR_DATA_WPFS_SELECTOR_STYLE] = MM_WPFS::SELECTOR_STYLE_BUTTON_GROUP;

		return array_merge($attributes, parent::getFormAttributes());
	}

	/**
	 * @return array
	 */
	public static function getFields()
	{
		$fields = array(
			self::FIELD_CUSTOM_AMOUNT_UNIQUE => MM_WPFS_ControlUtils::inputGroup(self::FIELD_CUSTOM_AMOUNT_UNIQUE)
		);

		return array_merge($fields, parent::getFields());
	}

	public function _currencySign()
	{
		if (1 == $this->form->showCurrencySymbolInsteadOfCode) {
			$this->_currencySymbol();
		} else {
			$this->_currencyCode();
		}
	}

	public function _currencySymbol()
	{
		echo (esc_attr($this->currencySymbol));
	}

	public function _currencyCode()
	{
		echo (esc_attr($this->currencyCode));
	}

	public function showCurrencySignAtFirstPosition()
	{
		return 1 == $this->form->showCurrencySignAtFirstPosition;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function customAmount()
	{
		return $this->customAmount;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function donationAmountOptions()
	{
		return $this->donationAmountOptions;
	}

	/**
	 * @return MM_WPFS_Control|null
	 */
	public function donationFrequencyOptions()
	{
		return $this->donationFrequencyOptions;
	}

}

class MM_WPFS_InlineDonationFormView extends MM_WPFS_DonationFormView
{

	use MM_WPFS_InlineFormView;

	/**
	 * MM_WPFS_InlineDonationFormView constructor.
	 *
	 * @param $form
	 */
	public function __construct($form, $fieldConfiguration, $loggerService)
	{
		parent::__construct($form, $fieldConfiguration, $loggerService);

		$this->initInlineFields($this->formHash);

		$this->action->setValue(self::FIELD_ACTION_VALUE_INLINE_DONATION_CHARGE);

	}

	/**
	 * @return array
	 */
	public static function getFields()
	{
		$inlineFields = self::getInlineFields();
		$inlineFields = array_merge($inlineFields, self::getInlineSameBillingAndShippingAddressField());
		$inlineFields = array_merge($inlineFields, self::getInlineBillingAddressFields());
		$inlineFields = array_merge($inlineFields, self::getInlineShippingAddressFields());

		return array_merge($inlineFields, parent::getFields());
	}

	/**
	 * @return array
	 */
	public function getFormAttributes()
	{
		$attributes = array();

		$attributes[self::ATTR_DATA_WPFS_FORM_TYPE] = MM_WPFS::FORM_TYPE_INLINE_DONATION;
		$attributes = array_merge($attributes, parent::getFormAttributes());

		$inlineFormAttributes = $this->getInlineFormAttributes($this->form);

		return array_merge($attributes, $inlineFormAttributes);
	}

}

class MM_WPFS_CheckoutDonationFormView extends MM_WPFS_DonationFormView
{

	use MM_WPFS_CheckoutFormView;

	/**
	 * MM_WPFS_PopupDonationFormView constructor.
	 *
	 * @param $form
	 */
	public function __construct($form, $fieldConfiguration, $loggerService)
	{
		parent::__construct($form, $fieldConfiguration, $loggerService);

		$this->action->setValue(self::FIELD_ACTION_VALUE_POPUP_DONATION_CHARGE);
	}

	public static function getFields()
	{
		$fields = self::getPopupBillingAddressFields();

		return array_merge($fields, parent::getFields());
	}

	protected function getFormAttributes()
	{
		$attributes = array();

		$attributes[self::ATTR_DATA_WPFS_FORM_TYPE] = MM_WPFS::FORM_TYPE_CHECKOUT_DONATION;
		$attributes = array_merge($attributes, parent::getFormAttributes());

		$popupFormAttributes = $this->getCheckoutFormAttributes($this->form);

		return array_merge($attributes, $popupFormAttributes);
	}

}

abstract class MM_WPFS_SaveCardFormView extends MM_WPFS_FormView implements MM_WPFS_SaveCardFormViewConstants
{

	public function __construct($form, $fieldConfiguration, $loggerService)
	{
		parent::__construct($form, $fieldConfiguration, $loggerService);

		$this->attributes = $this->getFormAttributes();
	}

	protected function getFormAttributes()
	{
		$attributes = array();
		return array_merge($attributes, parent::getFormAttributes());
	}

	public static function getFields()
	{
		$fields = array();

		return array_merge($fields, parent::getFields());
	}
}

class MM_WPFS_InlineSaveCardFormView extends MM_WPFS_SaveCardFormView
{

	use MM_WPFS_InlineFormView;

	/**
	 * MM_WPFS_InlineCardCaptureFormView constructor.
	 *
	 * @param $form
	 */
	public function __construct($form, $fieldConfiguration, $loggerService)
	{
		parent::__construct($form, $fieldConfiguration, $loggerService);

		$this->initInlineFields($this->formHash);

		$this->action->setValue(self::FIELD_ACTION_VALUE_INLINE_PAYMENT_CHARGE);
	}

	public static function getFields()
	{
		$inlineFields = self::getInlineFields();
		$inlineFields = array_merge($inlineFields, self::getInlineSameBillingAndShippingAddressField());
		$inlineFields = array_merge($inlineFields, self::getInlineBillingAddressFields());
		$inlineFields = array_merge($inlineFields, self::getInlineShippingAddressFields());

		return array_merge($inlineFields, parent::getFields());
	}

	public function getFormAttributes()
	{
		$attributes = array();

		$attributes[self::ATTR_DATA_WPFS_FORM_TYPE] = MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD;
		$attributes = array_merge($attributes, parent::getFormAttributes());

		$inlineFormAttributes = $this->getInlineFormAttributes($this->form);

		return array_merge($attributes, $inlineFormAttributes);
	}

}

class MM_WPFS_CheckoutPaymentFormView extends MM_WPFS_PaymentFormView
{

	use MM_WPFS_CheckoutFormView;

	/**
	 * MM_WPFS_PopupPaymentFormView constructor.
	 *
	 * @param $form
	 */
	public function __construct($form, $fieldConfiguration, $stripe, $loggerService)
	{
		parent::__construct($form, $fieldConfiguration, $stripe, $loggerService);

		$this->action->setValue(self::FIELD_ACTION_VALUE_POPUP_PAYMENT_CHARGE);
	}

	public static function getFields()
	{
		$fields = self::getPopupBillingAddressFields();

		return array_merge($fields, parent::getFields());
	}

	protected function getFormAttributes()
	{
		$attributes = array();

		$attributes[self::ATTR_DATA_WPFS_FORM_TYPE] = MM_WPFS::FORM_TYPE_CHECKOUT_PAYMENT;
		$attributes = array_merge($attributes, parent::getFormAttributes());

		$popupFormAttributes = $this->getCheckoutFormAttributes($this->form);

		return array_merge($attributes, $popupFormAttributes);
	}

}

class MM_WPFS_CheckoutSubscriptionFormView extends MM_WPFS_SubscriptionFormView
{

	use MM_WPFS_CheckoutFormView;

	/**
	 * MM_WPFS_PopupSubscriptionFormView constructor.
	 *
	 * @param $form
	 * @param $stripe
	 */
	public function __construct($form, $fieldConfiguration, $stripe, $loggerService)
	{
		parent::__construct($form, $fieldConfiguration, $stripe, $loggerService);

		$this->action->setValue(self::FIELD_ACTION_VALUE_POPUP_SUBSCRIPTION_CHARGE);
	}

	public static function getFields()
	{
		$fields = array(
		);
		$fields = array_merge($fields, self::getPopupBillingAddressFields());

		return array_merge($fields, parent::getFields());
	}

	/**
	 * @return array
	 */
	public function getFormAttributes()
	{

		$attributes = array();

		$attributes[self::ATTR_DATA_WPFS_FORM_TYPE] = MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION;
		$attributes[self::ATTR_DATA_WPFS_SIMPLE_BUTTON_LAYOUT] = $this->form->simpleButtonLayout;
		$attributes = array_merge($attributes, parent::getFormAttributes());

		$popupFormAttributes = $this->getCheckoutFormAttributes($this->form);

		if (1 == $this->form->simpleButtonLayout) {
			$attributes[self::ATTR_CLASS] = self::ATTR_CLASS_VALUE_WPFS_FORM_WPFS_FORM_INLINE;
		}

		return array_merge($attributes, $popupFormAttributes);
	}

	public function getFirstPlanName()
	{
		$firstPlanName = null;

		if (isset($this->form) && isset($this->form->plans)) {
			$plans = json_decode($this->form->plans);
			if (JSON_ERROR_NONE === json_last_error()) {
				if (is_array($plans) && count($plans) > 0) {
					$firstPlanName = $plans[0];
				}
			}
		}

		return $firstPlanName;
	}

}

class MM_WPFS_CheckoutSaveCardFormView extends MM_WPFS_SaveCardFormView
{

	use MM_WPFS_CheckoutFormView;

	/**
	 * MM_WPFS_PopupCardCaptureFormView constructor.
	 *
	 * @param $form
	 */
	public function __construct($form, $fieldConfiguration, $loggerService)
	{
		parent::__construct($form, $fieldConfiguration, $loggerService);

		$this->action->setValue(self::FIELD_ACTION_VALUE_POPUP_PAYMENT_CHARGE);
	}

	public static function getFields()
	{
		$fields = self::getPopupBillingAddressFields();

		return array_merge($fields, parent::getFields());
	}

	protected function getFormAttributes()
	{
		$attributes = array();

		$attributes[self::ATTR_DATA_WPFS_FORM_TYPE] = MM_WPFS::FORM_TYPE_CHECKOUT_SAVE_CARD;
		$attributes = array_merge($attributes, parent::getFormAttributes());

		$popupFormAttributes = $this->getCheckoutFormAttributes($this->form);

		return array_merge($attributes, $popupFormAttributes);
	}
}
