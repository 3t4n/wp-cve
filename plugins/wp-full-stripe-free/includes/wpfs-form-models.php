<?php

/**
 * Used by WPFSM, WPFP_Mailchimp
 */
trait MM_WPFS_Model {

	/**
	 * @var MM_WPFS_Validator
	 */
	protected $__validator;

	/**
	 * @param $parameterName
	 * @param null $defaultValue
	 * @param string $sanitationType
	 *
	 * @return string
	 */
	public function getSanitizedPostParam( $parameterName, $defaultValue = null, $sanitationType = MM_WPFS_ModelConstants::SANITATION_TYPE_TEXT_FIELD ) {
		return $this->getSanitizedArrayParam( $_POST, $parameterName, $defaultValue, $sanitationType );
	}

	/**
	 * @param array $dataArray
	 * @param $parameterName
	 * @param null $defaultValue
	 * @param string $sanitationType
	 *
	 * @return array|string
	 */
	public function getSanitizedArrayParam( $dataArray, $parameterName, $defaultValue = null, $sanitationType = MM_WPFS_ModelConstants::SANITATION_TYPE_TEXT_FIELD ) {
		$parameterValue = $this->getArrayParam( $dataArray, $parameterName, $defaultValue );

		return $this->sanitizeValue( $parameterValue, $sanitationType );
	}

	/**
	 * @param array $dataArray
	 * @param $parameterName
	 * @param null $defaultValue
	 *
	 * @return array|string
	 */
	public function getArrayParam( $dataArray, $parameterName, $defaultValue = null ) {
		if ( array_key_exists( $parameterName, $dataArray ) && isset( $dataArray[ $parameterName ] ) ) {
			$value = wp_unslash( $dataArray[ $parameterName ] );
		} else {
			$value = wp_unslash( $defaultValue );
		}

		return $value;
	}

	/**
	 * @param $value
	 * @param $sanitationType
	 *
	 * @return array|string
	 */
	public function sanitizeValue( $value, $sanitationType = MM_WPFS_ModelConstants::SANITATION_TYPE_TEXT_FIELD ) {
		if ( is_array( $value ) ) {
			if ( MM_WPFS_ModelConstants::SANITATION_TYPE_TEXT_FIELD === $sanitationType ) {
				$functionName = 'sanitize_text_field';
			} elseif ( MM_WPFS_ModelConstants::SANITATION_TYPE_EMAIL === $sanitationType ) {
				$functionName = 'sanitize_email';
			} elseif ( MM_WPFS_ModelConstants::SANITATION_TYPE_KEY === $sanitationType ) {
				$functionName = 'sanitize_key';
			} else {
				$functionName = 'sanitize_text_field';
			}

			array_walk_recursive( $value, $functionName );

			return $value;
		} else {
			if ( MM_WPFS_ModelConstants::SANITATION_TYPE_TEXT_FIELD === $sanitationType ) {
				return sanitize_text_field( $value );
			} elseif ( MM_WPFS_ModelConstants::SANITATION_TYPE_EMAIL === $sanitationType ) {
				return sanitize_email( $value );
			} elseif ( MM_WPFS_ModelConstants::SANITATION_TYPE_KEY === $sanitationType ) {
				return sanitize_key( $value );
			} else {
				return sanitize_text_field( $value );
			}
		}
	}

	/**
	 * This function retrieves the value saved on the specific key from the $_POST array.
	 * The function strips slashes from the returned value.
	 *
	 * @param $parameterName
	 * @param null $defaultValue
	 *
	 * @return null
	 * @see wp_unslash()
	 *
	 */
	public function getPostParam( $parameterName, $defaultValue = null ) {
		return $this->getArrayParam( $_POST, $parameterName, $defaultValue );
	}

	/**
	 * @param $parameterName
	 * @param null $defaultValue
	 *
	 * @return null
	 */
	public function getNumericPostParam( $parameterName, $defaultValue = null ) {
		return $this->getNumericArrayParam( $_POST, $parameterName, $defaultValue );
	}

	/**
	 * @param array $dataArray
	 * @param $parameterName
	 * @param null $defaultValue
	 *
	 * @return array|string
	 */
	public function getNumericArrayParam( $dataArray, $parameterName, $defaultValue = null ) {
		if ( isset( $dataArray[ $parameterName ] ) && is_numeric( $dataArray[ $parameterName ] ) ) {
			$value = wp_unslash( $dataArray[ $parameterName ] );
		} else {
			$value = wp_unslash( $defaultValue );
		}

		return $value;
	}

	/**
	 * @param array $dataArray
	 * @param $parameterName
	 * @param null $defaultValue
	 *
	 * @return string
	 */
	public function getStrippedArrayParam( $dataArray, $parameterName, $defaultValue = null ) {
		return stripslashes( $this->getArrayParam( $dataArray, $parameterName, $defaultValue ) );
	}

	/**
	 * @param $parameterName
	 * @param null $defaultValue
	 *
	 * @return string
	 */
	public function getHTMLDecodedPostParam( $parameterName, $defaultValue = null ) {
		return $this->getHTMLDecodedArrayParam( $_POST, $parameterName, $defaultValue );
	}

	/**
	 * @param array $dataArray
	 * @param $parameterName
	 * @param null $defaultValue
	 *
	 * @return string
	 */
	public function getHTMLDecodedArrayParam( $dataArray, $parameterName, $defaultValue = null ) {
		return html_entity_decode( $this->getArrayParam( $dataArray, $parameterName, $defaultValue ) );
	}

	/**
	 * @param $parameterName
	 *
	 * @return array|mixed|object
	 */
	public function getJSONDecodedPostParam( $parameterName ) {
		return $this->getJSONDecodedArrayParam( $_POST, $parameterName );
	}

	/**
	 * @param array $dataArray
	 * @param $parameterName
	 *
	 * @return array|mixed|object
	 */
	public function getJSONDecodedArrayParam( $dataArray, $parameterName ) {
		return json_decode( rawurldecode( stripslashes( $dataArray[ $parameterName ] ) ) );
	}

	/**
	 * @param $parameterName
	 * @param string $sanitationType
	 *
	 * @return string
	 */
	public function getURLDecodedPostParam( $parameterName, $sanitationType = MM_WPFS_ModelConstants::SANITATION_TYPE_TEXT_FIELD ) {
		return $this->getURLDecodedArrayParam( $_POST, $parameterName, $sanitationType );
	}

	/**
	 * @param array $dataArray
	 * @param $parameterName
	 * @param string $sanitationType
	 *
	 * @return array|string
	 */
	public function getURLDecodedArrayParam( $dataArray, $parameterName, $sanitationType = MM_WPFS_ModelConstants::SANITATION_TYPE_TEXT_FIELD ) {
		return $this->sanitizeValue( urldecode( $this->getArrayParam( $dataArray, $parameterName ) ), $sanitationType );
	}

}

interface MM_WPFS_ModelConstants {

	const SANITATION_TYPE_TEXT_FIELD = 'text_field';
	const SANITATION_TYPE_KEY = 'key';
	const SANITATION_TYPE_EMAIL = 'email';

}

/**
 * Interface MM_WPFS_Binder contains the necessary functions for implementation to bind data to properties.
 *
 * Used by WPFSM, WPFP_Mailchimp
 */
interface MM_WPFS_Binder {

	const EMPTY_STR = '';

	/**
	 * Performs property binding by he $_POST superglobal.
	 *
	 * @return MM_WPFS_BindingResult
	 */
	public function bind();

	/**
	 * Performs property binding by the given array.
	 *
	 * @param $postData
	 *
	 * @return MM_WPFS_BindingResult
	 */
	public function bindByArray( $postData );

	/**
	 * This function can be overridden/implemented to call functions for specific operations that need to be run after
	 * bind().
	 *
	 * @return mixed
	 */
	public function afterBind();

	/**
	 * Returns an array with property names as keys to save this instance to database
	 *
	 * @return array
	 */
	public function getData();

	/**
	 * Returns an array with POST parameters as keys to serialize this instance. This array should be used as a
	 * parameter for bindByArray() later.
	 *
	 * @return array
	 * @see MM_WPFS_Binder::bindByArray()
	 */
	public function getPostData();

}

interface MM_WPFS_Public_PopupForm {

}

interface MM_WPFS_Public_InlineForm {

}

/**
 * Used by WPFSM, WPFP_Mailchimp
 */
class MM_WPFS_BindingResult {

	protected $formHash = null;
	protected $globalErrors = array();
	protected $fieldErrors = array();

	/**
	 * MM_WPFS_BindingResult constructor.
	 *
	 * @param $formHash
	 */
	public function __construct( $formHash = null ) {
		$this->formHash = $formHash;
	}

	public function hasErrors() {
		return ! empty( $this->globalErrors ) || ! empty( $this->fieldErrors );
	}

	public function hasFieldErrors( $field = null ) {
		if ( is_null( $field ) ) {
			return ! empty( $this->fieldErrors );
		} else {
			return array_key_exists( $field, $this->fieldErrors );
		}
	}

	public function addFieldError( $fieldName, $fieldId, $error ) {
		if ( is_null( $fieldName ) ) {
			return;
		}
		if ( ! array_key_exists( $fieldName, $this->fieldErrors ) ) {
			$this->fieldErrors[ $fieldName ] = array();
		}
		array_push(
			$this->fieldErrors[ $fieldName ],
			array(
				'id'      => $fieldId,
				'name'    => $fieldName,
				'message' => $error
			)
		);
	}

	public function getFieldErrors( $field = null ) {
		if ( is_null( $field ) ) {
			$fieldErrors = array();
			foreach ( array_values( $this->fieldErrors ) as $errors ) {
				$fieldErrors = array_merge( $fieldErrors, $errors );
			}

			return $fieldErrors;
		}
		if ( array_key_exists( $field, $this->fieldErrors ) ) {
			return $this->fieldErrors[ $field ];
		} else {
			return array();
		}
	}

	public function getGlobalErrors() {
		return $this->globalErrors;
	}

	public function hasGlobalErrors() {
		return ! empty( $this->globalErrors );
	}

	public function addGlobalError( $error ) {
		array_push( $this->globalErrors, $error );
	}

	/**
	 * @return null
	 */
	public function getFormHash() {
		return $this->formHash;
	}

	/**
	 * @param null $formHash
	 */
	public function setFormHash( $formHash ) {
		$this->formHash = $formHash;
	}

}

abstract class MM_WPFS_Public_FormModel implements MM_WPFS_Binder {
    use MM_WPFS_Model;
    use MM_WPFS_StaticContext_AddOn;
    use MM_WPFS_Logger_AddOn;

	const CUSTOM_FIELD_IDENTIFIER_PREFIX = 'CustomField';

	const ARRAY_KEY_ADDRESS_LINE_1 = 'line1';
	const ARRAY_KEY_ADDRESS_LINE_2 = 'line2';
	const ARRAY_KEY_ADDRESS_CITY = 'city';
	const ARRAY_KEY_ADDRESS_STATE = 'state';
	const ARRAY_KEY_ADDRESS_COUNTRY = 'country';
	const ARRAY_KEY_ADDRESS_COUNTRY_CODE = 'country_code';
	const ARRAY_KEY_ADDRESS_ZIP = 'zip';

	const PARAM_WPFS_FORM_NAME = 'wpfs-form-name';
	const PARAM_WPFS_FORM_ACTION = 'action';
	const PARAM_WPFS_FORM_GET_PARAMETERS = 'wpfs-form-get-parameters';
	const PARAM_WPFS_REFERRER = 'wpfs-referrer';
	const PARAM_WPFS_STRIPE_PAYMENT_METHOD_ID = 'wpfs-stripe-payment-method-id';
	const PARAM_WPFS_STRIPE_PAYMENT_INTENT_ID = 'wpfs-stripe-payment-intent-id';
	const PARAM_WPFS_STRIPE_SETUP_INTENT_ID = 'wpfs-stripe-setup-intent-id';
	const PARAM_WPFS_CARD_HOLDER_NAME = 'wpfs-card-holder-name';
	const PARAM_WPFS_CARD_HOLDER_EMAIL = 'wpfs-card-holder-email';
	const PARAM_WPFS_CARD_HOLDER_PHONE = 'wpfs-card-holder-phone';
	const PARAM_WPFS_CUSTOM_INPUT = 'wpfs-custom-input';
	const PARAM_WPFS_SAME_BILLING_AND_SHIPPING_ADDRESS = 'wpfs-same-billing-and-shipping-address';
	const PARAM_WPFS_BILLING_NAME = 'wpfs-billing-name';
	const PARAM_WPFS_BILLING_ADDRESS_LINE_1 = 'wpfs-billing-address-line-1';
	const PARAM_WPFS_BILLING_ADDRESS_LINE_2 = 'wpfs-billing-address-line-2';
	const PARAM_WPFS_BILLING_ADDRESS_CITY = 'wpfs-billing-address-city';
	const PARAM_WPFS_BILLING_ADDRESS_STATE = 'wpfs-billing-address-state';
    const PARAM_WPFS_BILLING_ADDRESS_STATE_SELECT = 'wpfs-billing-address-state-select';
	const PARAM_WPFS_BILLING_ADDRESS_ZIP = 'wpfs-billing-address-zip';
	const PARAM_WPFS_BILLING_ADDRESS_COUNTRY = 'wpfs-billing-address-country';
	const PARAM_WPFS_SHIPPING_NAME = 'wpfs-shipping-name';
	const PARAM_WPFS_SHIPPING_ADDRESS_LINE_1 = 'wpfs-shipping-address-line-1';
	const PARAM_WPFS_SHIPPING_ADDRESS_LINE_2 = 'wpfs-shipping-address-line-2';
	const PARAM_WPFS_SHIPPING_ADDRESS_CITY = 'wpfs-shipping-address-city';
	const PARAM_WPFS_SHIPPING_ADDRESS_STATE = 'wpfs-shipping-address-state';
    const PARAM_WPFS_SHIPPING_ADDRESS_STATE_SELECT = 'wpfs-shipping-address-state-select';
	const PARAM_WPFS_SHIPPING_ADDRESS_ZIP = 'wpfs-shipping-address-zip';
	const PARAM_WPFS_SHIPPING_ADDRESS_COUNTRY = 'wpfs-shipping-address-country';
	const PARAM_WPFS_TERMS_OF_USE_ACCEPTED = 'wpfs-terms-of-use-accepted';
	const PARAM_GOOGLE_RECAPTCHA_RESPONSE = 'g-recaptcha-response';
	const PARAM_WPFS_NONCE = 'wpfs-nonce';
	const PARAM_WPFS_COUPON = 'wpfs-coupon';
    const PARAM_WPFS_IP_ADDRESS = 'wpfs-ip-address';

	protected $action;
	protected $formName;
	protected $formGetParameters;
	protected $referrer;
	protected $stripePaymentMethodId;
	protected $stripePaymentIntentId;
	protected $stripeSetupIntentId;
	protected $cardHolderName;
	protected $cardHolderEmail;
	protected $cardHolderPhone;
	protected $customInputValues;
	protected $sameBillingAndShippingAddress;
	protected $billingName;
	protected $billingAddressLine1;
	protected $billingAddressLine2;
	protected $billingAddressCity;
	protected $billingAddressState;
	protected $billingAddressZip;
	protected $billingAddressCountry;
	protected $shippingName;
	protected $shippingAddressLine1;
	protected $shippingAddressLine2;
	protected $shippingAddressCity;
	protected $shippingAddressState;
	protected $shippingAddressZip;
	protected $shippingAddressCountry;
	protected $termsOfUseAccepted;
	protected $googleReCaptchaResponse;
	protected $transactionId;
	protected $nonce;
	protected $couponCode;
    protected $ipAddress;

	protected $__form;
	protected $__formHash;
	protected $__billingAddressCountryComposite;
	protected $__billingAddressCountryName;
	protected $__billingAddressCountryCode;
	protected $__shippingAddressCountryComposite;
	protected $__shippingAddressCountryName;
	protected $__shippingAddressCountryCode;
	/**
	 * @var \StripeWPFS\Customer
	 */
	protected $__stripeCustomer;
	protected $__productName;
	/**
	 * @var \StripeWPFS\PaymentMethod
	 */
	protected $__stripePaymentMethod;

	/**
	 * @var \StripeWPFS\Coupon|\StripeWPFS\PromotionCode
	 */
	protected $__stripeDiscount;
    /**
     * @var \StripeWPFS\Coupon
     */
    protected $__stripeCoupon;
    protected $__stripeDiscountId;
    protected $__stripeDiscountType;

	/**
	 * @var MM_WPFS_Database
	 */
	protected $__db;
	/**
	 * @var MM_WPFS_Stripe
	 */
	protected $__stripe;
    /**
     * @var MM_WPFS_Options
     */
    protected $options;

    /**
	 * MM_WPFS_Public_FormModel constructor.
	 */
	public function __construct( $loggerService ) {
        $this->initLogger( $loggerService, MM_WPFS_LoggerService::MODULE_RUNTIME );
        $this->options = new MM_WPFS_Options();

        $this->initStaticContext();

		$this->__db    = new MM_WPFS_Database();
		$this->__stripe = new MM_WPFS_Stripe( MM_WPFS_Stripe::getStripeAuthenticationToken($this->staticContext), $this->loggerService );
	}

	public function bind() {
        $this->detectIpAddress();
		return $this->bindByArray( $_POST );
	}

    /**
     * @return string|null
     */
    private function findIpAddress() {
        $httpHeaderNames = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR' );

        foreach ( $httpHeaderNames as $httpHeaderName) {
            if ( array_key_exists( $httpHeaderName, $_SERVER ) === true ) {
                foreach ( explode(',', $_SERVER[ $httpHeaderName ]) as $ip ) {
                    if ( filter_var( $ip, FILTER_VALIDATE_IP ) !== false ) {
                        return $ip;
                    }
                }
            }
        }

        return null;
    }

    private function detectIpAddress() {
        $_POST[ self::PARAM_WPFS_IP_ADDRESS ] = $this->findIpAddress();
    }

	public function bindByArray( $postData ) {
		$bindingResult = new MM_WPFS_BindingResult();

		$this->action                        = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_FORM_ACTION );
		$this->formName                      = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_FORM_NAME );
		$this->formGetParameters             = $this->getURLDecodedArrayParam( $postData, self::PARAM_WPFS_FORM_GET_PARAMETERS );
		$this->referrer                      = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_REFERRER );
		$this->stripePaymentMethodId         = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_STRIPE_PAYMENT_METHOD_ID );
		$this->stripePaymentIntentId         = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_STRIPE_PAYMENT_INTENT_ID );
		$this->stripeSetupIntentId           = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_STRIPE_SETUP_INTENT_ID );
		$this->couponCode                    = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_COUPON );
		$this->cardHolderName                = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_CARD_HOLDER_NAME );
		$this->cardHolderEmail               = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_CARD_HOLDER_EMAIL, null, MM_WPFS_ModelConstants::SANITATION_TYPE_EMAIL );
		$this->cardHolderPhone               = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_CARD_HOLDER_PHONE, null );
		$this->customInputValues             = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_CUSTOM_INPUT );
		$this->sameBillingAndShippingAddress = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_SAME_BILLING_AND_SHIPPING_ADDRESS, 0 );
		$this->billingName                   = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_BILLING_NAME );
        $this->billingAddressCountry         = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_BILLING_ADDRESS_COUNTRY, MM_WPFS_Binder::EMPTY_STR );
        if ( $this->billingAddressCountry === MM_WPFS::COUNTRY_CODE_UNITED_STATES ) {
            $this->billingAddressState = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_BILLING_ADDRESS_STATE_SELECT, MM_WPFS_Binder::EMPTY_STR );
        } else {
            $this->billingAddressState = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_BILLING_ADDRESS_STATE, MM_WPFS_Binder::EMPTY_STR );
        }
		$this->billingAddressLine1           = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_BILLING_ADDRESS_LINE_1, MM_WPFS_Binder::EMPTY_STR );
		$this->billingAddressLine2           = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_BILLING_ADDRESS_LINE_2, MM_WPFS_Binder::EMPTY_STR );
		$this->billingAddressCity            = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_BILLING_ADDRESS_CITY, MM_WPFS_Binder::EMPTY_STR );
		$this->billingAddressZip             = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_BILLING_ADDRESS_ZIP, MM_WPFS_Binder::EMPTY_STR );
		$this->shippingName                  = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_SHIPPING_NAME );
        $this->shippingAddressCountry        = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_SHIPPING_ADDRESS_COUNTRY );
        if ( $this->shippingAddressCountry === MM_WPFS::COUNTRY_CODE_UNITED_STATES ) {
            $this->shippingAddressState = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_SHIPPING_ADDRESS_STATE_SELECT, MM_WPFS_Binder::EMPTY_STR );
        } else {
            $this->shippingAddressState = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_SHIPPING_ADDRESS_STATE, MM_WPFS_Binder::EMPTY_STR );
        }
		$this->shippingAddressLine1          = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_SHIPPING_ADDRESS_LINE_1 );
		$this->shippingAddressLine2          = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_SHIPPING_ADDRESS_LINE_2 );
		$this->shippingAddressCity           = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_SHIPPING_ADDRESS_CITY );
		$this->shippingAddressZip            = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_SHIPPING_ADDRESS_ZIP );
		$this->termsOfUseAccepted            = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_TERMS_OF_USE_ACCEPTED, 0 );
		$this->googleReCaptchaResponse       = $this->getSanitizedArrayParam( $postData, self::PARAM_GOOGLE_RECAPTCHA_RESPONSE );
		$this->nonce                         = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_NONCE );
        $this->ipAddress                     = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_IP_ADDRESS );

		/*
				if ( isset( $this->cardHolderName ) && ! empty( $this->cardHolderName ) ) {
					if ( ! isset( $this->billingName ) || empty( $this->billingName ) ) {
						$this->billingName = $this->cardHolderName;
					}
					if ( ! isset( $this->shippingName ) || empty( $this->shippingName ) ) {
						$this->shippingName = $this->cardHolderName;
					}
				} else {
					if ( isset( $this->billingName ) && ! empty( $this->billingName ) ) {
						$this->cardHolderName = $this->billingName;
					} elseif ( isset( $this->shippingName ) && ! empty( $this->shippingName ) ) {
						$this->cardHolderName = $this->shippingName;
					}
				}
		*/

		$this->initBillingAddressCountryComposite();
		$this->initShippingAddressCountryComposite();

		return $bindingResult;
	}

    protected function extractDiscountData( $discount ) {
        $result = new \StdClass;

        if ( isset($discount) && isset($discount->promotion_code)  ) {
            $result->type = MM_WPFS::DISCOUNT_TYPE_PROMOTION_CODE;
            $result->id = $discount->id;
            $result->coupon = $discount->coupon;
        } else if ( isset($discount->coupon) ) {
            $result->type = MM_WPFS::DISCOUNT_TYPE_COUPON;
            $result->id = $discount->id;
            $result->coupon = $discount;
        } else {
            throw new Exception( __CLASS__ . '.' . __FUNCTION__ . '(): Unsupported discount type received: ' . print_r( $discount, true ) );
        }

        return $result;
    }

    protected function prepareStripeCoupon() {
		if ( isset( $this->couponCode ) && ! empty( $this->couponCode ) ) {
            $discount = $this->retrieveCouponOrPromotionalCode();
            $discountData = $this->extractDiscountData( $discount );

            $this->__stripeDiscount = $discount;
            $this->__stripeCoupon = $discountData->coupon;
            $this->__stripeDiscountId = $discountData->id;
            $this->__stripeDiscountType = $discountData->type;
        }
	}

	protected function initBillingAddressCountryComposite() {
		if ( isset( $this->billingAddressCountry ) ) {
			$this->__billingAddressCountryComposite = MM_WPFS_Countries::getCountryByCode( $this->billingAddressCountry );
			if ( isset( $this->__billingAddressCountryComposite ) ) {
				$this->__billingAddressCountryName = $this->__billingAddressCountryComposite['name'];
				$this->__billingAddressCountryCode = $this->__billingAddressCountryComposite['alpha-2'];
			}
		}
	}

	protected function initShippingAddressCountryComposite() {
		if ( isset( $this->shippingAddressCountry ) ) {
			$this->__shippingAddressCountryComposite = MM_WPFS_Countries::getCountryByCode( $this->shippingAddressCountry );
			if ( isset( $this->__shippingAddressCountryComposite ) ) {
				$this->__shippingAddressCountryName = $this->__shippingAddressCountryComposite['name'];
				$this->__shippingAddressCountryCode = $this->__shippingAddressCountryComposite['alpha-2'];
			}
		}
	}

	/**
	 * @param array $stripeAddressHash
	 */
	public function updateBillingAddressByStripeAddressHash( $stripeAddressHash ) {
		if ( isset( $stripeAddressHash ) ) {
			if ( isset( $stripeAddressHash->line1 ) ) {
				$this->billingAddressLine1 = $stripeAddressHash->line1;
			}
			if ( isset( $stripeAddressHash->line2 ) ) {
				$this->billingAddressLine2 = $stripeAddressHash->line2;
			}
			if ( isset( $stripeAddressHash->city ) ) {
				$this->billingAddressCity = $stripeAddressHash->city;
			}
			if ( isset( $stripeAddressHash->state ) ) {
				$this->billingAddressState = $stripeAddressHash->state;
			}
			if ( isset( $stripeAddressHash->postal_code ) ) {
				$this->billingAddressZip = $stripeAddressHash->postal_code;
			}
			if ( isset( $stripeAddressHash->country ) ) {
				$this->billingAddressCountry = $stripeAddressHash->country;
			}
			$this->initBillingAddressCountryComposite();
		}
	}

	/**
	 * @return MM_WPFS_Database
	 */
	public function getDb() {
		return $this->__db;
	}

	/**
	 * @return MM_WPFS_Stripe
	 */
	public function getStripe() {
		return $this->__stripe;
	}
	
	public function getData() {
		// tnagy unsupported operation
		return array();
	}

	public function getPostData() {

		$array = array(
			self::PARAM_WPFS_FORM_ACTION                => $this->action,
			self::PARAM_WPFS_FORM_NAME                  => $this->formName,
			self::PARAM_WPFS_FORM_GET_PARAMETERS        => $this->formGetParameters,
			self::PARAM_WPFS_REFERRER                   => $this->referrer,
			self::PARAM_WPFS_STRIPE_PAYMENT_METHOD_ID   => $this->stripePaymentMethodId,
			self::PARAM_WPFS_STRIPE_PAYMENT_INTENT_ID   => $this->stripePaymentIntentId,
			self::PARAM_WPFS_STRIPE_SETUP_INTENT_ID     => $this->stripeSetupIntentId,
			self::PARAM_WPFS_CARD_HOLDER_NAME           => $this->cardHolderName,
			self::PARAM_WPFS_CARD_HOLDER_EMAIL          => $this->cardHolderEmail,
			self::PARAM_WPFS_CARD_HOLDER_PHONE          => $this->cardHolderPhone,
			self::PARAM_WPFS_CUSTOM_INPUT               => $this->customInputValues,
			self::PARAM_WPFS_BILLING_NAME               => $this->billingName,
			self::PARAM_WPFS_BILLING_ADDRESS_LINE_1     => $this->billingAddressLine1,
			self::PARAM_WPFS_BILLING_ADDRESS_LINE_2     => $this->billingAddressLine2,
			self::PARAM_WPFS_BILLING_ADDRESS_CITY       => $this->billingAddressCity,
			self::PARAM_WPFS_BILLING_ADDRESS_STATE      => $this->billingAddressState,
			self::PARAM_WPFS_BILLING_ADDRESS_ZIP        => $this->billingAddressZip,
			self::PARAM_WPFS_BILLING_ADDRESS_COUNTRY    => $this->billingAddressCountry,
			self::PARAM_WPFS_SHIPPING_NAME              => $this->shippingName,
			self::PARAM_WPFS_SHIPPING_ADDRESS_LINE_1    => $this->shippingAddressLine1,
			self::PARAM_WPFS_SHIPPING_ADDRESS_LINE_2    => $this->shippingAddressLine2,
			self::PARAM_WPFS_SHIPPING_ADDRESS_CITY      => $this->shippingAddressCity,
			self::PARAM_WPFS_SHIPPING_ADDRESS_STATE     => $this->shippingAddressState,
			self::PARAM_WPFS_SHIPPING_ADDRESS_ZIP       => $this->shippingAddressZip,
			self::PARAM_WPFS_SHIPPING_ADDRESS_COUNTRY   => $this->shippingAddressCountry,
			self::PARAM_WPFS_TERMS_OF_USE_ACCEPTED      => $this->termsOfUseAccepted,
			self::PARAM_GOOGLE_RECAPTCHA_RESPONSE       => $this->googleReCaptchaResponse,
			self::PARAM_WPFS_NONCE                      => $this->nonce,
			self::PARAM_WPFS_COUPON                     => $this->couponCode,
			self::PARAM_WPFS_IP_ADDRESS                 => $this->ipAddress
		);

		return $array;
	}

	/**
	 * @return mixed
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * @return string
	 */
	public function getFormGetParameters() {
		return $this->formGetParameters;
	}

	/**
	 * @return mixed
	 */
	public function getReferrer() {
		return $this->referrer;
	}

	/**
	 * @return mixed
	 */
	public function getStripePaymentMethodId() {
		return $this->stripePaymentMethodId;
	}

	/**
	 * @return mixed
	 */
	public function getStripePaymentIntentId() {
		return $this->stripePaymentIntentId;
	}

	/**
	 * @return mixed
	 */
	public function getStripeSetupIntentId() {
		return $this->stripeSetupIntentId;
	}

	/**
	 * @return mixed
	 */
	public function getCardHolderName() {
		return $this->cardHolderName;
	}

	/**
	 * @param mixed $cardHolderName
	 */
	public function setCardHolderName( $cardHolderName ) {
		$this->cardHolderName = $cardHolderName;
	}

	/**
	 * @return mixed
	 */
	public function getCardHolderEmail() {
		return $this->cardHolderEmail;
	}

	/**
	 * @param mixed $cardHolderEmail
	 */
	public function setCardHolderEmail( $cardHolderEmail ) {
		$this->cardHolderEmail = $cardHolderEmail;
	}

	/**
	 * @return mixed
	 */
	public function getCardHolderPhone() {
		return $this->cardHolderPhone;
	}

	/**
	 * @param mixed $cardHolderPhone
	 */
	public function setCardHolderPhone( $cardHolderPhone ) {
		$this->cardHolderPhone = $cardHolderPhone;
	}

	/**
	 * @return mixed
	 */
	public function getCustomInputvalues() {
		return $this->customInputValues;
	}

	/**
	 * @return mixed
	 */
	public function getBillingAddressLine1() {
		return $this->billingAddressLine1;
	}

	/**
	 * @return mixed
	 */
	public function getBillingAddressLine2() {
		return $this->billingAddressLine2;
	}

	/**
	 * @return mixed
	 */
	public function getBillingAddressCity() {
		return $this->billingAddressCity;
	}

	/**
	 * @return mixed
	 */
	public function getBillingAddressState() {
		return $this->billingAddressState;
	}

	/**
	 * @return mixed
	 */
	public function getBillingAddressZip() {
		return $this->billingAddressZip;
	}

	/**
	 * @return mixed
	 */
	public function getBillingAddressCountry() {
		return $this->billingAddressCountry;
	}

	/**
	 * @return mixed
	 */
	public function getShippingAddressLine1() {
		return $this->shippingAddressLine1;
	}

	/**
	 * @return mixed
	 */
	public function getShippingAddressLine2() {
		return $this->shippingAddressLine2;
	}

	/**
	 * @return mixed
	 */
	public function getShippingAddressCity() {
		return $this->shippingAddressCity;
	}

	/**
	 * @return mixed
	 */
	public function getShippingAddressState() {
		return $this->shippingAddressState;
	}

	/**
	 * @return mixed
	 */
	public function getShippingAddressZip() {
		return $this->shippingAddressZip;
	}

	/**
	 * @return mixed
	 */
	public function getShippingAddressCountry() {
		return $this->shippingAddressCountry;
	}

	/**
	 * @return mixed
	 */
	public function getTermsOfUseAccepted() {
		return $this->termsOfUseAccepted;
	}

	/**
	 * @return mixed
	 */
	public function getGoogleReCaptchaResponse() {
		return $this->googleReCaptchaResponse;
	}

	/**
	 * @return mixed
	 */
	public function getTransactionId() {
		return $this->transactionId;
	}

	/**
	 * @param mixed $transactionId
	 */
	public function setTransactionId( $transactionId ) {
		$this->transactionId = $transactionId;
	}

	/**
	 * @return mixed
	 */
	public function getFormHash() {
		return $this->__formHash;
	}

	/**
	 * @param mixed $formHash
	 */
	public function setFormHash( $formHash ) {
		$this->__formHash = $formHash;
	}

	/**
	 * @return mixed
	 */
	public function getNonce() {
		return $this->nonce;
	}

	/**
	 * @param mixed $nonce
	 */
	public function setNonce( $nonce ) {
		$this->nonce = $nonce;
	}

	/**
	 * @return mixed
	 */
	public function getBillingAddressCountryComposite() {
		return $this->__billingAddressCountryComposite;
	}

	/**
	 * @param mixed $billing_address_country_composite
	 */
	public function setBillingAddressCountryComposite( $billing_address_country_composite ) {
		$this->__billingAddressCountryComposite = $billing_address_country_composite;
	}

	/**
	 * @return mixed
	 */
	public function getBillingAddressCountryName() {
		return $this->__billingAddressCountryName;
	}

	/**
	 * @param mixed $billing_address_country_name
	 */
	public function setBillingAddressCountryName( $billing_address_country_name ) {
		$this->__billingAddressCountryName = $billing_address_country_name;
	}

	/**
	 * @return mixed
	 */
	public function getShippingAddressCountryComposite() {
		return $this->__shippingAddressCountryComposite;
	}

	/**
	 * @return mixed
	 */
	public function getShippingAddressCountryName() {
		return $this->__shippingAddressCountryName;
	}

	/**
	 * @return array
	 */
	public function getMetadata() {
		$metadata = array();

		if ( isset( $this->cardHolderEmail ) ) {
			$metadata['customer_email'] = $this->cardHolderEmail;
		}
		if ( isset( $this->cardHolderName ) ) {
			$metadata['customer_name'] = $this->cardHolderName;
		}
		if ( isset( $this->formName ) ) {
			$metadata['form_name'] = $this->formName;
		}
		if ( isset( $this->__form->allowMultipleSubscriptions ) ) {
			$metadata['allow_multiple_subscriptions'] = $this->__form->allowMultipleSubscriptions;
			if ( isset( $this->__form->allowMultipleSubscriptions ) && 1 == $this->__form->allowMultipleSubscriptions ) {
				$metadata['minimum_quantity_of_subscriptions'] = $this->__form->minimumQuantityOfSubscriptions;
				$metadata['maximum_quantity_of_subscriptions'] = $this->__form->maximumQuantityOfSubscriptions;
			}
		}
		if (
			( isset( $this->__form->showAddress ) && 1 == $this->__form->showAddress )
			|| ( isset( $this->__form->showBillingAddress ) && 1 == $this->__form->showBillingAddress )
		) {
			if ( isset( $this->billingName ) ) {
				$metadata['billing_name'] = $this->billingName;
			}
			if ( isset( $this->billingAddressLine1 ) || isset( $this->billingAddressZip ) || isset( $this->billingAddressCity ) || isset( $this->billingAddressCountry ) ) {
				$metadata['billing_address'] = implode( '|', array(
					$this->billingAddressLine1,
					$this->billingAddressLine2,
					$this->billingAddressZip,
					$this->billingAddressCity,
					$this->billingAddressState,
					$this->__billingAddressCountryName,
					$this->__billingAddressCountryCode
				) );
			}
		}
		if ( isset( $this->__form->showShippingAddress ) && 1 == $this->__form->showShippingAddress ) {
			if ( isset( $this->shippingName ) ) {
				$metadata['shipping_name'] = $this->shippingName;
			}
			if ( isset( $this->shippingAddressLine1 ) || isset( $this->shippingAddressZip ) || isset( $this->shippingAddressCity ) || isset( $this->shippingAddressCountry ) ) {
				$metadata['shipping_address'] = implode( '|', array(
					$this->shippingAddressLine1,
					$this->shippingAddressLine2,
					$this->shippingAddressZip,
					$this->shippingAddressCity,
					$this->shippingAddressState,
					$this->__shippingAddressCountryName,
					$this->__shippingAddressCountryCode
				) );
			}
		}
		if ( is_null( $this->__form->customInputs ) ) {
			$customInputValueString = is_array( $this->customInputValues ) ? implode( ",", $this->customInputValues ) : printf( $this->customInputValues );
			if ( ! empty( $customInputValueString ) ) {
				$metadata['custom_inputs'] = $customInputValueString;
			}
		} else {
			$customInputLabels = $this->getDecodedCustomInputLabels();
			foreach ( $customInputLabels as $i => $label ) {
				$key = $label;
				if ( array_key_exists( $key, $metadata ) ) {
					$key = $label . $i;
				}
				if ( ! empty( $this->customInputValues[ $i ] ) ) {
					$metadata[ $key ] = $this->customInputValues[ $i ];
				}
			}
		}

		// users can add custom metadata via filter
		try {
			$user_meta = apply_filters( MM_WPFS::FILTER_NAME_ADD_TRANSACTION_METADATA, array(), $this->getFormName(), $this->getFormGetParametersAsArray() );
			$metadata  = array_merge( $metadata, $user_meta );
		} catch ( Exception $ex ) {
            $this->logger->error(__FUNCTION__, 'Cannot apply metadata filter', $ex);
		}

		return $metadata;
	}

	/**
	 * @return array
	 */
	public function getDecodedCustomInputLabels() {
		$customInputLabels = array();
		if ( isset( $this->__form->customInputs ) ) {
			$customInputLabels = explode( '{{', $this->__form->customInputs );
		}

		return $customInputLabels;
	}

	/**
	 * @return string
	 */
	public function getCustomFieldsJSON() {
		$customFields = array();
		if ( empty( $this->__form->customInputs ) ) {
			if ( is_array( $this->customInputValues ) ) {
				foreach ( $this->customInputValues as $i => $value ) {
					array_push( $customFields, $this->createCustomFieldObject( self::CUSTOM_FIELD_IDENTIFIER_PREFIX . ( $i + 1 ), self::CUSTOM_FIELD_IDENTIFIER_PREFIX . ( $i + 1 ), 'text', $value ) );
				}
			} else if ( ! empty( $this->customInputValues ) ) {
				array_push( $customFields, $this->createCustomFieldObject( self::CUSTOM_FIELD_IDENTIFIER_PREFIX . 1, self::CUSTOM_FIELD_IDENTIFIER_PREFIX . 1, 'text', printf( $this->customInputValues ) ) );
			}
		} else {
			$customInputLabels = $this->getDecodedCustomInputLabels();
			foreach ( $customInputLabels as $i => $label ) {
				$value = empty( $this->customInputValues[ $i ] ) ? '' : $this->customInputValues[ $i ];
				array_push( $customFields, $this->createCustomFieldObject( self::CUSTOM_FIELD_IDENTIFIER_PREFIX . ( $i + 1 ), $label, 'text', $value ) );
			}
		}

		return json_encode( $customFields );
	}

	/**
	 * @param string $identifier
	 * @param string $label
	 * @param string $type
	 * @param string $value
	 *
	 * @return StdClass
	 */
	public function createCustomFieldObject( $identifier, $label, $type, $value ) {
		$customFieldObject = new \StdClass;

		$customFieldObject->identifier = $identifier;
		$customFieldObject->label      = $label;
		$customFieldObject->type       = $type;
		$customFieldObject->value      = $value;

		return $customFieldObject;
	}

	/**
	 * @return mixed
	 */
	public function getFormName() {
		return $this->formName;
	}

	/**
	 * @return array
	 */
	public function getFormGetParametersAsArray() {
		$res = json_decode( $this->formGetParameters, true );

		return $res ? $res : array();
	}

	/**
	 * @return \StripeWPFS\Customer
	 */
	public function getStripeCustomer() {
		return $this->__stripeCustomer;
	}

	/**
	 * @param \StripeWPFS\Customer $stripeCustomer
	 * @param bool $updatePropertiesByCustomer
	 */
	public function setStripeCustomer( $stripeCustomer, $updatePropertiesByCustomer = false ) {
		$this->__stripeCustomer = $stripeCustomer;
		if ( $updatePropertiesByCustomer && ! is_null( $stripeCustomer ) ) {
			$this->cardHolderEmail = $stripeCustomer->email;
			$this->cardHolderName  = $stripeCustomer->name;
			$this->cardHolderPhone = $stripeCustomer->phone;
			$this->billingName     = ! is_null( $this->cardHolderName ) ? $this->cardHolderName : null;
			if ( isset( $stripeCustomer->address ) ) {
				$this->billingAddressLine1   = $stripeCustomer->address->line1;
				$this->billingAddressLine2   = $stripeCustomer->address->line2;
				$this->billingAddressCity    = $stripeCustomer->address->city;
				$this->billingAddressState   = $stripeCustomer->address->state;
				$this->billingAddressZip     = $stripeCustomer->address->postal_code;
				$this->billingAddressCountry = $stripeCustomer->address->country;
				$this->initBillingAddressCountryComposite();
			}
			if ( isset( $stripeCustomer->shipping ) && isset( $stripeCustomer->shipping->address ) ) {
				$this->shippingName           = $stripeCustomer->shipping->name;
				$this->shippingAddressLine1   = $stripeCustomer->shipping->address->line1;
				$this->shippingAddressLine2   = $stripeCustomer->shipping->address->line2;
				$this->shippingAddressCity    = $stripeCustomer->shipping->address->city;
				$this->shippingAddressState   = $stripeCustomer->shipping->address->state;
				$this->shippingAddressZip     = $stripeCustomer->shipping->address->postal_code;
				$this->shippingAddressCountry = $stripeCustomer->shipping->address->country;
				$this->initShippingAddressCountryComposite();
			}
		}
	}

	/**
	 * @return \StripeWPFS\PaymentMethod
	 */
	public function getStripePaymentMethod() {
		return $this->__stripePaymentMethod;
	}

	/**
	 * @param \StripeWPFS\PaymentMethod $stripePaymentMethod
	 */
	public function setStripePaymentMethod( $stripePaymentMethod ) {
		$this->__stripePaymentMethod = $stripePaymentMethod;
	}

	/**
	 * @return mixed
	 */
	public function getSameBillingAndShippingAddress() {
		return $this->sameBillingAndShippingAddress;
	}

	/**
	 * @return mixed
	 */
	public function getBillingName() {
		return $this->billingName;
	}

	/**
	 * @param mixed $billingName
	 */
	public function setBillingName( $billingName ) {
		$this->billingName = $billingName;
	}

	/**
	 * @return mixed
	 */
	public function getShippingName() {
		return $this->shippingName;
	}

	/**
	 * @param mixed $shippingName
	 */
	public function setShippingName( $shippingName ) {
		$this->shippingName = $shippingName;
	}

	/**
	 * @param bool $mayReturnNull
	 *
	 * @return array
	 */
	public function getBillingAddress( $mayReturnNull = true ) {
		return $this->getAddressArray(
			$mayReturnNull,
			$this->billingAddressLine1,
			$this->billingAddressLine2,
			$this->billingAddressCity,
			$this->billingAddressState,
			$this->__billingAddressCountryName,
			$this->__billingAddressCountryCode,
			$this->billingAddressZip
		);
	}

	/**
	 * @param $mayReturnNull
	 * @param $line1
	 * @param $line2
	 * @param $city
	 * @param $state
	 * @param $countryName
	 * @param $countryCode
	 * @param $zip
	 *
	 * @return array
	 */
	protected function getAddressArray( $mayReturnNull, $line1, $line2, $city, $state, $countryName, $countryCode, $zip ) {
		$addressData = array(
			self::ARRAY_KEY_ADDRESS_LINE_1       => is_null( $line1 ) ? '' : $line1,
			self::ARRAY_KEY_ADDRESS_LINE_2       => is_null( $line2 ) ? '' : $line2,
			self::ARRAY_KEY_ADDRESS_CITY         => is_null( $city ) ? '' : $city,
			self::ARRAY_KEY_ADDRESS_STATE        => is_null( $state ) ? '' : $state,
			self::ARRAY_KEY_ADDRESS_COUNTRY      => is_null( $countryName ) ? '' : $countryName,
			self::ARRAY_KEY_ADDRESS_COUNTRY_CODE => is_null( $countryCode ) ? '' : $countryCode,
			self::ARRAY_KEY_ADDRESS_ZIP          => is_null( $zip ) ? '' : $zip
		);
		if ( $mayReturnNull ) {
			$hasNotEmptyValue = false;
			foreach ( $addressData as $key => $value ) {
				if ( $value !== '' ) {
					$hasNotEmptyValue = true;
				}
			}
			if ( $hasNotEmptyValue ) {
				return $addressData;
			} else {
				return null;
			}
		}

		return $addressData;
	}

	/**
	 * @param bool $mayReturnNull
	 *
	 * @return array
	 */
	public function getShippingAddress( $mayReturnNull = true ) {
		return $this->getAddressArray(
			$mayReturnNull,
			$this->shippingAddressLine1,
			$this->shippingAddressLine2,
			$this->shippingAddressCity,
			$this->shippingAddressState,
			$this->__shippingAddressCountryName,
			$this->__shippingAddressCountryCode,
			$this->shippingAddressZip
		);
	}

	/**
	 * @return mixed
	 */
	public function getProductName() {
		return $this->__productName;
	}

	/**
	 * @param mixed $productName
	 */
	public function setProductName( $productName ) {
		$this->__productName = $productName;
	}

	/**
	 * @param $popupFormSubmit
	 *
	 * @return array|mixed|object
	 */
	public function extractFormModelDataFromPopupFormSubmit( $popupFormSubmit ) {
		$postData = array();
		if ( isset( $popupFormSubmit ) && isset( $popupFormSubmit->postData ) ) {
			$postData = json_decode(
				$popupFormSubmit->postData,
				/* to associative array */
				true
			);
			if ( JSON_ERROR_NONE !== json_last_error() ) {
				$postData = array();
			}
		}

		return $postData;
	}

	/**
	 * @param $checkoutSession
	 *
	 * @return array
	 */
	public function extractFormModelDataFromCheckoutSession( $checkoutSession ) {
		// todo tnagy extract data from setup intent / subscription's setup intent / payment intent
		$result = array();

		if ( isset($checkoutSession) ) {
			if ( isset( $checkoutSession->line_items ) && count($checkoutSession->line_items->data) > 0 ) {
				$lineItem = $checkoutSession->line_items->data[0];
				if ( isset($lineItem) ) {
					if ( isset( $lineItem->discounts ) && count( $lineItem->discounts ) > 0 ) {
						$stripeDiscountObject = $lineItem->discounts[0];
						if ( isset($stripeDiscountObject) && isset( $stripeDiscountObject->discount ) ) {
							$stripeDiscount = $stripeDiscountObject->discount;
							if ( isset($stripeDiscount) ) {
								$result[ MM_WPFS_Public_FormModel::PARAM_WPFS_COUPON ]                     = $stripeDiscount->coupon->id;
							}
						}
					}
				}
			}
			if ( isset( $checkoutSession->payment_intent ) ) {
				if ( $checkoutSession->payment_intent->id ) {
					$paymentIntent = $checkoutSession->payment_intent;
				} else {
					$paymentIntent = $this->__stripe->retrievePaymentIntent( $checkoutSession->payment_intent );
				}
				if ( isset($paymentIntent->id) && isset( $paymentIntent->payment_method ) ) {
					if ( $paymentIntent->payment_method->id ) {
						$paymentMethod = $paymentIntent->payment_method;
					} else {
						$paymentMethod = $this->__stripe->retrievePaymentMethod( $paymentIntent->payment_method );
					}
					if ( isset($paymentMethod) 
					     && isset( $paymentMethod->billing_details )
					     && isset( $paymentMethod->billing_details->address )
					) {
						$result[ MM_WPFS_Public_FormModel::PARAM_WPFS_BILLING_NAME ]            = $paymentMethod->billing_details->name;
						$result[ MM_WPFS_Public_FormModel::PARAM_WPFS_BILLING_ADDRESS_LINE_1 ]  = $paymentMethod->billing_details->address->line1;
						$result[ MM_WPFS_Public_FormModel::PARAM_WPFS_BILLING_ADDRESS_LINE_2 ]  = $paymentMethod->billing_details->address->line2;
						$result[ MM_WPFS_Public_FormModel::PARAM_WPFS_BILLING_ADDRESS_CITY ]    = $paymentMethod->billing_details->address->city;
						$result[ MM_WPFS_Public_FormModel::PARAM_WPFS_BILLING_ADDRESS_STATE ]   = $paymentMethod->billing_details->address->state;
						$result[ MM_WPFS_Public_FormModel::PARAM_WPFS_BILLING_ADDRESS_ZIP ]     = $paymentMethod->billing_details->address->postal_code;
						$result[ MM_WPFS_Public_FormModel::PARAM_WPFS_BILLING_ADDRESS_COUNTRY ] = $paymentMethod->billing_details->address->country;
					}
				}
			}
		}
		$result[ MM_WPFS_Public_FormModel::PARAM_WPFS_SHIPPING_NAME ]            = null;
		$result[ MM_WPFS_Public_FormModel::PARAM_WPFS_SHIPPING_ADDRESS_LINE_1 ]  = null;
		$result[ MM_WPFS_Public_FormModel::PARAM_WPFS_SHIPPING_ADDRESS_LINE_2 ]  = null;
		$result[ MM_WPFS_Public_FormModel::PARAM_WPFS_SHIPPING_ADDRESS_CITY ]    = null;
		$result[ MM_WPFS_Public_FormModel::PARAM_WPFS_SHIPPING_ADDRESS_STATE ]   = null;
		$result[ MM_WPFS_Public_FormModel::PARAM_WPFS_SHIPPING_ADDRESS_COUNTRY ] = null;
		$result[ MM_WPFS_Public_FormModel::PARAM_WPFS_SHIPPING_ADDRESS_ZIP ]     = null;

		return $result;
	}

	public function afterBind() {
		$this->updateShippingAddress();
	}

	private function updateShippingAddress() {
		if ( ! is_null( $this->getForm() ) ) {
			if ( 1 == $this->sameBillingAndShippingAddress && 1 == $this->getForm()->showShippingAddress ) {
				$this->shippingName           = $this->billingName;
				$this->shippingAddressLine1   = $this->billingAddressLine1;
				$this->shippingAddressLine2   = $this->billingAddressLine2;
				$this->shippingAddressCity    = $this->billingAddressCity;
				$this->shippingAddressState   = $this->billingAddressState;
				$this->shippingAddressZip     = $this->billingAddressZip;
				$this->shippingAddressCountry = $this->billingAddressCountry;
				$this->initShippingAddressCountryComposite();
			}
		}
	}

	/**
	 * @return mixed
	 */
	public function getForm() {
		return $this->__form;
	}

	/**
	 * @param mixed $form
	 */
	public function setForm( $form ) {
		$this->__form = $form;
		$this->prepareFormHash();
	}

	protected function prepareFormHash() {
		$formType = MM_WPFS_Utils::getFormType( $this->__form );
		$formId   = MM_WPFS_Utils::getFormId( $this->__form );
		$formName = $this->__form->name;
		$this->setFormHash(
			esc_attr(
				MM_WPFS_Utils::generateFormHash(
					$formType,
					$formId,
					$formName
				)
			)
		);
	}

	/**
	 * @return mixed
	 */
	public function getCouponCode() {
		return $this->couponCode;
	}

	/**
	 * @param $couponCode string
	 */
	public function setCouponCode( $couponCode ) {
		$this->couponCode = $couponCode;
	}

    /**
     * @return mixed
     */
    public function getStripeDiscountId() {
        return $this->__stripeDiscountId;
    }

    /**
     * @param $stripeDiscountId string
     */
    public function setStripeDiscountId( $stripeDiscountId ) {
        $this->__stripeDiscountId = $stripeDiscountId;
    }

    /**
     * @return mixed
     */
    public function getStripeDiscountType() {
        return $this->__stripeDiscountType;
    }

    /**
     * @param $stripeDiscountType string
     */
    public function setStripeDiscountType( $stripeDiscountType ) {
        $this->__stripeDiscountType = $stripeDiscountType;
    }

    /**
	 * @return \StripeWPFS\Coupon
	 */
	public function getStripeCoupon() {
		return $this->__stripeCoupon;
	}

	/**
	 * @param \StripeWPFS\Coupon $stripeCoupon
	 */
	public function setStripeCoupon( $stripeCoupon ) {
		$this->__stripeCoupon = $stripeCoupon;
	}

    /**
     * @return \StripeWPFS\Coupon|\StripeWPFS\PromotionCode
     */
    public function getStripeDiscount() {
        return $this->__stripeDiscount;
    }

    /**
     * @param \StripeWPFS\Coupon|\StripeWPFS\PromotionCode $stripeDiscount
     */
    public function setStripeDiscount($stripeDiscount ) {
        $this->__stripeDiscount = $stripeDiscount;
    }

    /**
	 * @return \StripeWPFS\Coupon
	 * @throws \StripeWPFS\Exception\ApiErrorException
	 */
	protected function retrieveCouponOrPromotionalCode() {
		$result = $this->retrievePromotionalCode();
		if ( ! is_null( $result ) ) {
			if ( ! $result->active) {
				$result = $this->retrieveCoupon();
			}
		} else {
			$result = $this->retrieveCoupon();
		}

		return $result;
	}

	/**
	 * @return \StripeWPFS\PromotionCode|null
	 */
	protected function retrievePromotionalCode() {
		try {
			return $this->__stripe->retrievePromotionalCode( $this->couponCode );
		} catch ( Exception $e ) {
			return null;
		}
	}

	/**
	 * @return \StripeWPFS\Coupon|null
	 */
	protected function retrieveCoupon() {
		try {
			return $this->__stripe->retrieveCoupon( $this->couponCode );
		} catch ( Exception $e ) {
			return null;
		}
	}

    /**
     * @return mixed
     */
    public function getIpAddress() {
        return $this->ipAddress;
    }
}

trait MM_WPFS_Public_FormModel_InlineTaxAddOn {
    protected $buyingAsBusiness;
    protected $businessName;
    protected $taxIdType;
    protected $taxId;
    protected $taxCountry;
    protected $taxState;
    protected $taxZip;

    protected function bindTaxDataByArray( $postData ) {
        $this->buyingAsBusiness        = $this->getSanitizedArrayParam( $postData, MM_WPFS_FormView_InlineTaxAddOnConstants::FIELD_BUYING_AS_BUSINESS, 0 );
        $this->businessName            = $this->getSanitizedArrayParam( $postData, MM_WPFS_FormView_InlineTaxAddOnConstants::FIELD_BUSINESS_NAME );
        $this->taxIdType               = $this->getSanitizedArrayParam( $postData, MM_WPFS_FormView_InlineTaxAddOnConstants::FIELD_TAX_ID_TYPE );
        $this->taxId                   = $this->getSanitizedArrayParam( $postData, MM_WPFS_FormView_InlineTaxAddOnConstants::FIELD_TAX_ID );
        $this->taxZip                  = $this->getSanitizedArrayParam( $postData, MM_WPFS_FormView_InlineTaxAddOnConstants::FIELD_TAX_ZIP );

        $this->taxCountry = $this->getSanitizedArrayParam( $postData, MM_WPFS_FormView_InlineTaxAddOnConstants::FIELD_TAX_COUNTRY );
        if ( empty( $this->taxCountry ) ) {
            $this->taxCountry   = $this->getBillingAddressCountry();
            $this->taxState     = $this->getBillingAddressState();
        }  else {
            $this->taxState     = $this->getSanitizedArrayParam( $postData, MM_WPFS_FormView_InlineTaxAddOnConstants::FIELD_TAX_STATE );
        }
    }

    /**
     * @return mixed
     */
    public function getBuyingAsBusiness() {
        return $this->buyingAsBusiness;
    }

    /**
     * @param mixed $buyingAsBusiness
     */
    public function setBuyingAsBusiness($buyingAsBusiness) {
        $this->buyingAsBusiness = $buyingAsBusiness;
    }

    /**
     * @return mixed
     */
    public function getBusinessName() {
        return $this->businessName;
    }

    /**
     * @param mixed $businessName
     */
    public function setBusinessName($businessName) {
        $this->businessName = $businessName;
    }

    /**
     * @return mixed
     */
    public function getTaxIdType() {
        return $this->taxIdType;
    }

    /**
     * @param mixed $taxId
     */
    public function setTaxIdType( $taxIdType ) {
        $this->taxIdType = $taxIdType;
    }

    /**
     * @return mixed
     */
    public function getTaxId() {
        return $this->taxId;
    }

    /**
     * @param mixed $taxId
     */
    public function setTaxId($taxId) {
        $this->taxId = $taxId;
    }

    /**
     * @return mixed
     */
    public function getTaxCountry() {
        return $this->taxCountry;
    }

    /**
     * @param mixed $taxCountry
     */
    public function setTaxCountry($taxCountry) {
        $this->taxCountry = $taxCountry;
    }

    /**
     * @return mixed
     */
    public function getTaxState() {
        return $this->taxState;
    }

    /**
     * @param mixed $taxState
     */
    public function setTaxState($taxState) {
        $this->taxState = $taxState;
    }

    /**
     * @return mixed
     */
    public function getTaxZip() {
        return $this->taxZip;
    }

    /**
     * @param mixed $taxZip
     */
    public function setTaxZip( $taxZip ) {
        $this->taxZip = $taxZip;
    }
}

abstract class MM_WPFS_Public_PaymentFormModel extends MM_WPFS_Public_FormModel {
    use MM_WPFS_Public_FormModel_InlineTaxAddOn;

	const PARAM_WPFS_CUSTOM_AMOUNT_INDEX = 'wpfs-custom-amount-index';
	const PARAM_WPFS_CUSTOM_AMOUNT = 'wpfs-custom-amount';
	const PARAM_WPFS_CUSTOM_AMOUNT_UNIQUE = 'wpfs-custom-amount-unique';
	const INITIAL_CUSTOM_AMOUNT_INDEX = - 1;
	protected $customAmountIndex;
	protected $customAmountValue;
	protected $customAmountUniqueValue;

	protected $__amount;
    protected $__priceId;
    protected $__price;
    protected $__stripePaymentIntent;

	/**
	 * MM_WPFS_Public_PaymentFormModel constructor.
	 */
	public function __construct( $loggerService ) {
		parent::__construct( $loggerService );
	}

	public function bindByArray( $postData ) {
		$bindingResult                 = parent::bindByArray( $postData );
		$this->customAmountIndex       = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_CUSTOM_AMOUNT_INDEX, self::INITIAL_CUSTOM_AMOUNT_INDEX );
		$this->customAmountValue       = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_CUSTOM_AMOUNT );
		$this->customAmountUniqueValue = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_CUSTOM_AMOUNT_UNIQUE );

		$this->bindTaxDataByArray($postData);

        $this->prepareStripeCouponAndProduct();

        if ( isset( $this->__validator ) ) {
            $this->__validator->validate( $bindingResult, $this );
        }

        $this->afterBind();

        return $bindingResult;
	}

	protected function prepareStripeCouponAndProduct() {
		$this->prepareStripeCoupon();
	}

	public function getPostData() {
		$parentPostData = parent::getPostData();

		$postData = array(
			self::PARAM_WPFS_CUSTOM_AMOUNT_INDEX  => $this->customAmountIndex,
			self::PARAM_WPFS_CUSTOM_AMOUNT        => $this->customAmountValue,
			self::PARAM_WPFS_CUSTOM_AMOUNT_UNIQUE => $this->customAmountUniqueValue
		);

		return array_merge( $postData, $parentPostData );
	}

	/**
	 * @return mixed
	 */
	public function getCustomAmountIndex() {
		return $this->customAmountIndex;
	}

	/**
	 * @param mixed $customAmountIndex
	 */
	public function setCustomAmountIndex( $customAmountIndex ) {
		$this->customAmountIndex = $customAmountIndex;
	}

	/**
	 * @return mixed
	 */
	public function getCustomAmountValue() {
		return $this->customAmountValue;
	}

	/**
	 * @param mixed $customAmountValue
	 */
	public function setCustomAmountValue( $customAmountValue ) {
		$this->customAmountValue = $customAmountValue;
	}

	/**
	 * @return mixed
	 */
	public function getCustomAmountUniqueValue() {
		return $this->customAmountUniqueValue;
	}

	/**
	 * @param mixed $customAmountUniqueValue
	 */
	public function setCustomAmountUniqueValue( $customAmountUniqueValue ) {
		$this->customAmountUniqueValue = $customAmountUniqueValue;
	}

	/**
	 * @return mixed
	 */
	public function getAmount() {
		return $this->__amount;
	}

	/**
	 * @param mixed $amount
	 */
	public function setAmount( $amount ) {
		$this->__amount = $amount;
	}

    /**
     * @return mixed
     */
    public function getPriceId() {
        return $this->__priceId;
    }

    /**
     * @return mixed
     */
    public function getPrice() {
        return $this->__price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price) {
        $this->__price = $price;
    }

    /**
     * @param mixed $priceId
     */
    public function setPriceId( $priceId ) {
        $this->__priceId = $priceId;
    }

	public function setForm( $form ) {
		parent::setForm( $form );
		$this->prepareAmountAndProductName();
	}

    /**
     * @return mixed
     */
    public function getStripePaymentIntent() {
        return $this->__stripePaymentIntent;
    }

    /**
     * @param mixed $stripePaymentIntent
     */
    public function setStripePaymentIntent( $stripePaymentIntent ) {
        $this->__stripePaymentIntent = $stripePaymentIntent;
    }

    /**
     * @throws WPFS_UserFriendlyException
     */
	protected function prepareAmountAndProductName() {
		$this->__amount = null;
        $this->__productName = MM_WPFS_Utils::getDefaultProductDescription();

        $customAmount =
            MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT === $this->__form->customAmount ||
            ( MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS === $this->__form->customAmount
                && 1 == $this->__form->allowListOfAmountsCustom
                && 'other' === $this->customAmountValue );

        if ( !$customAmount && MM_WPFS::PAYMENT_TYPE_CARD_CAPTURE !== $this->__form->customAmount ) {
            $products = MM_WPFS_Utils::decodeJsonArray( $this->__form->decoratedProducts );

            if ( count( $products ) === 0 ) {
                $ex = new WPFS_UserFriendlyException( __( 'Please add products to the form.', 'wp-full-stripe' ));
                $ex->setTitle( __( 'Form configuration error', 'wp-full-stripe' ));
                throw $ex;
            } else if ( count( $products ) === 1 ) {
                $product = $products[0];
            } elseif ( isset( $this->customAmountIndex ) && $this->customAmountIndex > self::INITIAL_CUSTOM_AMOUNT_INDEX && count( $products ) > $this->customAmountIndex ) {
                $product = $products[ $this->customAmountIndex ];
            } else {
                throw new WPFS_UserFriendlyException( __CLASS__ . '.' . __FUNCTION__ . ": amountIndex not found." );
            }

            $this->__amount      = $product->price;
            $this->__productName = $product->name;
            $this->__priceId     = $product->stripePriceId;
            $this->__price       = $product;
        } elseif ( $customAmount ) {
            $parsedAmount    = MM_WPFS_Currencies::parseByForm( $this->__form, $this->__form->currency, $this->customAmountUniqueValue );
            $this->__amount  = MM_WPFS_Utils::parse_amount( $this->__form->currency, $parsedAmount );
            $this->__productName = isset( $this->__form->productDesc ) ? $this->__form->productDesc : MM_WPFS_Utils::getDefaultProductDescription();
            $this->__priceId = null;
            $this->__price   = null;
        }
	}
}

abstract class MM_WPFS_Public_DonationFormModel extends MM_WPFS_Public_FormModel {

	const PARAM_WPFS_CUSTOM_AMOUNT_INDEX = 'wpfs-custom-amount-index';
	const PARAM_WPFS_CUSTOM_AMOUNT = 'wpfs-custom-amount';
	const PARAM_WPFS_CUSTOM_AMOUNT_UNIQUE = 'wpfs-custom-amount-unique';
	const PARAM_WPFS_DONATION_FREQUENCY = 'wpfs-donation-frequency';
	const INITIAL_CUSTOM_AMOUNT_INDEX = - 1;
	protected $customAmountIndex;
	protected $customAmountValue;
	protected $customAmountUniqueValue;
	protected $donationFrequency;
	protected $__amount;
	protected $__stripeSubscription;

	/**
	 * MM_WPFS_Public_DonationFormModel constructor.
	 */
	public function __construct( $loggerService ) {
		parent::__construct( $loggerService );
	}

	public function bindByArray( $postData ) {
		$bindingResult                 = parent::bindByArray( $postData );
		$this->customAmountIndex       = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_CUSTOM_AMOUNT_INDEX, self::INITIAL_CUSTOM_AMOUNT_INDEX );
		$this->customAmountValue       = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_CUSTOM_AMOUNT );
		$this->customAmountUniqueValue = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_CUSTOM_AMOUNT_UNIQUE );
		$this->donationFrequency       = $this->getSanitizedArrayParam( $postData, self::PARAM_WPFS_DONATION_FREQUENCY );

		if ( isset( $this->__validator ) ) {
			$this->__validator->validate( $bindingResult, $this );
		}

		$this->afterBind();

		return $bindingResult;
	}

	public function getPostData() {
		$parentPostData = parent::getPostData();

		$postData = array(
			self::PARAM_WPFS_CUSTOM_AMOUNT_INDEX  => $this->customAmountIndex,
			self::PARAM_WPFS_CUSTOM_AMOUNT        => $this->customAmountValue,
			self::PARAM_WPFS_CUSTOM_AMOUNT_UNIQUE => $this->customAmountUniqueValue,
			self::PARAM_WPFS_DONATION_FREQUENCY   => $this->donationFrequency
		);

		return array_merge( $postData, $parentPostData );
	}

	/**
	 * @return mixed
	 */
	public function getCustomAmountIndex() {
		return $this->customAmountIndex;
	}

	/**
	 * @param mixed $customAmountIndex
	 */
	public function setCustomAmountIndex( $customAmountIndex ) {
		$this->customAmountIndex = $customAmountIndex;
	}

	/**
	 * @return mixed
	 */
	public function getCustomAmountValue() {
		return $this->customAmountValue;
	}

	/**
	 * @param mixed $customAmountValue
	 */
	public function setCustomAmountValue( $customAmountValue ) {
		$this->customAmountValue = $customAmountValue;
	}

	/**
	 * @return mixed
	 */
	public function getCustomAmountUniqueValue() {
		return $this->customAmountUniqueValue;
	}

	/**
	 * @param mixed $customAmountUniqueValue
	 */
	public function setCustomAmountUniqueValue( $customAmountUniqueValue ) {
		$this->customAmountUniqueValue = $customAmountUniqueValue;
	}

	/**
	 * @return mixed
	 */
	public function getAmount() {
		return $this->__amount;
	}

	/**
	 * @param mixed $amount
	 */
	public function setAmount( $amount ) {
		$this->__amount = $amount;
	}

	public function setForm( $form ) {
		parent::setForm( $form );
		$this->prepareAmountAndProductName();
	}

	protected function prepareAmountAndProductName() {
		$this->__amount = null;
		if ( isset( $this->__form->productDesc ) ) {
			$this->__productName = esc_attr( $this->__form->productDesc );
		} else {
			$this->__productName = __( "Donation", 'wp-full-stripe' );
		}

		if ( 1 == $this->__form->allowCustomDonationAmount && 'other' === $this->customAmountValue ) {
			$parsedAmount   = MM_WPFS_Currencies::parseByForm( $this->__form, $this->__form->currency, $this->customAmountUniqueValue );
			$this->__amount = MM_WPFS_Utils::parse_amount( $this->__form->currency, $parsedAmount );
		} else {
			$donationAmounts = MM_WPFS_Utils::decodeJsonArray( $this->__form->donationAmounts );
			if ( isset( $this->customAmountIndex ) && $this->customAmountIndex > self::INITIAL_CUSTOM_AMOUNT_INDEX && count( $donationAmounts ) > $this->customAmountIndex ) {
				$this->__amount = $donationAmounts[ $this->customAmountIndex ];
			}
		}
	}

	public function getDonationFrequency() {
		return $this->donationFrequency;
	}

	public function isRecurringDonation() {
		return $this->donationFrequency !== MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_ONE_TIME ? true : false;
	}

    /**
     * @return mixed
     */
    public function getStripeSubscription() {
        return $this->__stripeSubscription;
    }

    /**
     * @param mixed $stripeSubscription
     */
    public function setStripeSubscription( $stripeSubscription) {
        $this->__stripeSubscription = $stripeSubscription;
    }

}

class MM_WPFS_Public_InlineDonationFormModel extends MM_WPFS_Public_DonationFormModel implements MM_WPFS_Public_InlineForm {

	/**
	 * MM_WPFS_Public_InlineDonationFormModel constructor.
	 */
	public function __construct( $loggerService ) {
		parent::__construct( $loggerService );

		$this->__validator = new MM_WPFS_InlineDonationFormValidator( $loggerService );
	}

}

class MM_WPFS_Public_CheckoutDonationFormModel extends MM_WPFS_Public_DonationFormModel implements MM_WPFS_Public_PopupForm {
	/**
	 * MM_WPFS_Public_PopupDonationFormModel constructor.
	 */
	public function __construct( $loggerService ) {
		parent::__construct( $loggerService );

		$this->__validator = new MM_WPFS_PopupDonationFormValidator( $loggerService );
	}

}

abstract class MM_WPFS_Public_SubscriptionFormModel extends MM_WPFS_Public_FormModel {
    use MM_WPFS_Public_FormModel_InlineTaxAddOn;

	const PARAM_WPFS_STRIPE_PLAN = 'wpfs-plan';
	const PARAM_WPFS_STRIPE_PLAN_QUANTITY = 'wpfs-plan-quantity';

	protected $stripePlanId;
	protected $stripePlanQuantity;
	/**
	 * @var \StripeWPFS\Plan
	 */
	protected $__stripePlan;
	protected $__stripePlanAmount;
    protected $__stripePlanSetupFee;
    protected $__stripePlanBillingAnchorDay;
    protected $__stripePlanProrateUntilAnchorDay;
    protected $__stripePlanTrialPeriodDays;
    protected $__stripePlanCancellationCount;

    protected $__stripePlanProperties;

    protected $__stripeSubscription;
    protected $__stripePaymentIntent;
    protected $__stripeSetupIntent;

	/**
	 * MM_WPFS_Public_SubscriptionFormModel constructor.
	 */
	public function __construct( $loggerService ) {
		parent::__construct( $loggerService );
	}

	public function bindByArray( $postData ) {
		$bindingResult                  = parent::bindByArray( $postData );
		$this->stripePlanId             = $this->getHTMLDecodedArrayParam( $postData, self::PARAM_WPFS_STRIPE_PLAN );
		$stripePlanQuantityDefaultValue = 1;
		$this->stripePlanQuantity       = $this->getNumericArrayParam( $postData, self::PARAM_WPFS_STRIPE_PLAN_QUANTITY, $stripePlanQuantityDefaultValue );

        $this->bindTaxDataByArray( $postData );

        $this->__productName      = '';

		$form = $this->getFormFromDatabase();
        $this->setForm( $form );

		$this->prepareStripePlan();
		$this->prepareStripeCouponAndProduct();

        if ( isset( $this->__validator ) ) {
            $this->__validator->validate( $bindingResult, $this );
        }

        $this->afterBind();

        return $bindingResult;
	}

	abstract protected function getFormFromDatabase();

    protected function getPlanFromStripe() {
        $this->__stripePlan = $this->__stripe->retrievePlan( $this->stripePlanId );
    }

    protected function getPlanPropertiesFromForm() {
        $stripePriceId  = $this->__stripePlan->id;
        $planProperties = json_decode( $this->__form->decoratedPlans );

        foreach ( $planProperties as $planProperty ) {
            if ( $planProperty->stripePriceId === $stripePriceId ) {
                $this->__stripePlanProperties = $planProperty;
                break;
            }
        }

        if ( is_null( $this->__stripePlanProperties ) ) {
            $prop = new \StdClass;
            $prop->stripePriceId        = $this->__stripePlan->id;
            $prop->name                 = $this->__stripePlan->product->name;
            $prop->currency             = $this->__stripePlan->currency;
            $prop->interval             = $this->__stripePlan->recurring->interval;
            $prop->intervalCount        = $this->__stripePlan->recurring->interval_count;
            $prop->price                = $this->__stripePlan->unit_amount;
            $prop->setupFee             = 0;
            $prop->trialDays            = 0;
            $prop->cancellationCount    = 0;
            $prop->billingAnchorDay     = 0;
            $prop->prorateUntilBillingAnchorDay = false;

            $this->__stripePlanProperties = $prop;
        }
    }

    protected function extractPlanPropertiesData() {
        $this->__productName                       = $this->__stripePlan->product->name;
        $this->__stripePlanBillingAnchorDay        = $this->__stripePlanProperties->billingAnchorDay;
        $this->__stripePlanProrateUntilAnchorDay   = $this->__stripePlanProperties->prorateUntilBillingAnchorDay ? 1 : 0;
        $this->__stripePlanTrialPeriodDays         = $this->__stripePlanProperties->trialDays;
        $this->__stripePlanCancellationCount       = $this->__stripePlanProperties->cancellationCount;
    }

    protected function prepareStripePlan() {
	    $this->getPlanFromStripe();
	    $this->getPlanPropertiesFromForm();
        $this->extractPlanPropertiesData();
    }

	protected function prepareStripeCouponAndProduct() {
        parent::prepareStripeCoupon();

		if ( isset( $this->stripePlanId ) ) {
			$this->__stripePlanSetupFee = $this->__stripePlanProperties->setupFee;
			$this->__stripePlanAmount = $this->__stripePlan->unit_amount;
		}
	}

	public function getPostData() {
		$parentPostData = parent::getPostData();

		$postData = array(
			self::PARAM_WPFS_STRIPE_PLAN          => $this->stripePlanId,
			self::PARAM_WPFS_STRIPE_PLAN_QUANTITY => $this->stripePlanQuantity
		);

		return array_merge( $postData, $parentPostData );
	}

	/**
	 * @return mixed
	 */
	public function getStripePlanId() {
		return $this->stripePlanId;
	}

	/**
	 * @return mixed
	 */
	public function getStripePlanQuantity() {
		return $this->stripePlanQuantity;
	}

	/**
	 * @return mixed
	 */
	public function getStripePlan() {
		return $this->__stripePlan;
	}

	/**
	 * @return mixed
	 */
	public function getSetupFee() {
		return $this->__stripePlanSetupFee;
	}

    /**
     * @return mixed
     */
    public function getStripePlanProperties() {
        return $this->__stripePlanProperties;
    }

	/**
	 * @return mixed
	 */
	public function getPlanAmount() {
		return $this->__stripePlanAmount;
	}

    /**
     * @return mixed
     */
    public function getBillingAnchorDay() {
        return $this->__stripePlanBillingAnchorDay;
    }

    /**
     * @return mixed
     */
    public function getProrateUntilAnchorDay() {
        return $this->__stripePlanProrateUntilAnchorDay;
    }

    /**
     * @return mixed
     */
    public function getTrialPeriodDays() {
        return $this->__stripePlanTrialPeriodDays;
    }

    /**
     * @return mixed
     */
    public function getCancellationCount() {
        return $this->__stripePlanCancellationCount;
    }

    /**
     * @return mixed
     */
    public function getStripePaymentIntent() {
        return $this->__stripePaymentIntent;
    }

    /**
     * @param mixed $stripePaymentIntent
     */
    public function setStripePaymentIntent($stripePaymentIntent ) {
        $this->__stripePaymentIntent = $stripePaymentIntent;
    }

    /**
     * @return mixed
     */
    public function getStripeSetupIntent() {
        return $this->__stripeSetupIntent;
    }

    /**
     * @param mixed $stripeSetupIntent
     */
    public function setStripeSetupIntent($stripeSetupIntent ) {
        $this->__stripeSetupIntent = $stripeSetupIntent;
    }

    /**
     * @return mixed
     */
    public function getStripeSubscription() {
        return $this->__stripeSubscription;
    }

    /**
     * @param mixed $stripeSubscription
     */
    public function setStripeSubscription( $stripeSubscription ) {
        $this->__stripeSubscription = $stripeSubscription;
    }
}

class MM_WPFS_Public_InlinePaymentFormModel extends MM_WPFS_Public_PaymentFormModel implements MM_WPFS_Public_InlineForm {
    /**
     * MM_WPFS_Public_InlinePaymentFormModel constructor.
     */
    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );

        $this->__validator = new MM_WPFS_InlinePaymentFormValidator( $loggerService );
    }
}

class MM_WPFS_Public_InlineSubscriptionFormModel extends MM_WPFS_Public_SubscriptionFormModel implements MM_WPFS_Public_InlineForm {
	/**
	 * MM_WPFS_Public_InlineSubscriptionFormModel constructor.
	 */
	public function __construct( $loggerService ) {
		parent::__construct( $loggerService );

		$this->__validator = new MM_WPFS_InlineSubscriptionFormValidator( $loggerService );
	}

    protected function getFormFromDatabase() {
        return $this->__db->getInlineSubscriptionFormByName( $this->getFormName() );
    }
}

class MM_WPFS_Public_CheckoutPaymentFormModel extends MM_WPFS_Public_PaymentFormModel implements MM_WPFS_Public_PopupForm {

	/**
	 * MM_WPFS_Public_PopupPaymentFormModel constructor.
	 */
	public function __construct( $loggerService ) {
		parent::__construct( $loggerService );

		$this->__validator = new MM_WPFS_PopupPaymentFormValidator( $loggerService );
	}

    public function bindByArray($postData) {
        $bindingResult = parent::bindByArray($postData);

        if (isset($this->__validator)) {
            $this->__validator->validate($bindingResult, $this);
        }

        $this->afterBind();

        return $bindingResult;
    }
}

class MM_WPFS_Public_CheckoutSubscriptionFormModel extends MM_WPFS_Public_SubscriptionFormModel implements MM_WPFS_Public_PopupForm {

	/**
	 * MM_WPFS_Public_PopupSubscriptionFormModel constructor.
	 */
	public function __construct( $loggerService ) {
		parent::__construct( $loggerService );

		$this->__validator = new MM_WPFS_PopupSubscriptionFormValidator( $loggerService );
	}

    protected function getFormFromDatabase() {
        return $this->__db->getCheckoutSubscriptionFormByName( $this->getFormName() );
    }

    public function bindByArray( $postData ) {
        $bindingResult = parent::bindByArray( $postData );

        if ( isset( $this->__validator ) ) {
            $this->__validator->validate( $bindingResult, $this );
        }

        $this->afterBind();

        return $bindingResult;
    }
}
