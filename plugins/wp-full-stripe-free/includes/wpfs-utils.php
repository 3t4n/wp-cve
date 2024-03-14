<?php

class MM_WPFS_Utils {

    const ADDITIONAL_DATA_KEY_ACTION_NAME = 'action_name';
    const ADDITIONAL_DATA_KEY_CUSTOMER = 'customer';
    const ADDITIONAL_DATA_KEY_MACROS = 'macros';
    const ADDITIONAL_DATA_KEY_MACRO_VALUES = 'macroValues';
    const WPFS_LOG_MESSAGE_PREFIX = "WPFS: ";
    const STRIPE_METADATA_KEY_MAX_LENGTH = 40;
    const STRIPE_METADATA_VALUE_MAX_LENGTH = 500;
    const STRIPE_METADATA_KEY_MAX_COUNT = 20;
    const ELEMENT_PART_SEPARATOR = '--';

    const ESCAPE_TYPE_NONE = 'none';
    const ESCAPE_TYPE_HTML = 'esc_html';
    const ESCAPE_TYPE_ATTR = 'esc_attr';
    const WPFS_ENCRYPT_METHOD_AES_256_CBC = 'AES-256-CBC';

    public static function extractFirstTierPricingFromPlan( $plan ) {
        return $plan->tiers[0]['unit_amount'];
    }

    /**
     * @return bool
     */
    public static function generateCSSFormID( $form_hash ) {
        return MM_WPFS_FormView::ATTR_ID_VALUE_PREFIX . $form_hash;
    }


    /**
     * @return bool
     */
    public static function isDemoMode() {
        return defined( 'WP_FULL_STRIPE_DEMO_MODE' );
    }


    /**
     * @param $form
     *
     * @return null|string
     */
    public static function getFormType( $form ) {
        if ( is_null( $form ) ) {
            return null;
        }
        if ( isset( $form->paymentFormID ) ) {
            if ( MM_WPFS::PAYMENT_TYPE_CARD_CAPTURE === $form->customAmount ) {
                return MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD;
            } else {
                return MM_WPFS::FORM_TYPE_INLINE_PAYMENT;
            }
        }
        if ( isset( $form->subscriptionFormID ) ) {
            return MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION;
        }
        if ( isset( $form->checkoutFormID ) ) {
            if ( MM_WPFS::PAYMENT_TYPE_CARD_CAPTURE === $form->customAmount ) {
                return MM_WPFS::FORM_TYPE_CHECKOUT_SAVE_CARD;
            } else {
                return MM_WPFS::FORM_TYPE_CHECKOUT_PAYMENT;
            }
        }
        if ( isset( $form->checkoutSubscriptionFormID ) ) {
            return MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION;
        }

        if ( isset( $form->donationFormID ) ) {
            return MM_WPFS::FORM_TYPE_INLINE_DONATION;
        }
        if ( isset( $form->checkoutDonationFormID ) ) {
            return MM_WPFS::FORM_TYPE_CHECKOUT_DONATION;
        }

        return null;
    }

    public static function isInlinePaymentFormType( $type ) {
        return $type === MM_WPFS::FORM_TYPE_INLINE_PAYMENT;
    }

    public static function isCheckoutPaymentFormType( $type ) {
        return $type === MM_WPFS::FORM_TYPE_POPUP_PAYMENT || $type === MM_WPFS::FORM_TYPE_CHECKOUT_PAYMENT;
    }

    public static function isInlineSaveCardFormType( $type ) {
        return $type === MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD;
    }

    public static function isCheckoutSaveCardFormType( $type ) {
        return $type === MM_WPFS::FORM_TYPE_POPUP_SAVE_CARD || $type === MM_WPFS::FORM_TYPE_CHECKOUT_SAVE_CARD;
    }

    public static function isInlineSubscriptionFormType( $type ) {
        return $type === MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION;
    }

    public static function isCheckoutSubscriptionFormType( $type ) {
        return $type === MM_WPFS::FORM_TYPE_POPUP_SUBSCRIPTION || $type === MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION;
    }

    public static function isInlineDonationFormType( $type ) {
        return $type === MM_WPFS::FORM_TYPE_INLINE_DONATION;
    }

    public static function isCheckoutDonationFormType( $type ) {
        return $type === MM_WPFS::FORM_TYPE_POPUP_DONATION || $type === MM_WPFS::FORM_TYPE_CHECKOUT_DONATION;
    }

    /**
     * @param $slug
     * @param $id
     * @param $type
     *
     * @return string
     */
    public static function createFormEditUrl( $slug, $id, $type ) {
        $editUrl = add_query_arg(
            array(
                'page' => $slug,
                'form' => $id,
                'type' => $type
            ),
            admin_url( "admin.php" )
        );

        return $editUrl;
    }

    /**
     * @param $id string
     * @param $type string
     * @param $layout string
     *
     * @return string
     */
    public static function getFormEditUrl( $id, $type, $layout ) {
        $editUrl = "#";

        if ( $type   === MM_WPFS::FORM_TYPE_PAYMENT &&
            $layout === MM_WPFS::FORM_LAYOUT_INLINE ) {
            $editUrl = self::createFormEditUrl( MM_WPFS_Admin_Menu::SLUG_EDIT_FORM, $id, MM_WPFS::FORM_TYPE_INLINE_PAYMENT );
        } elseif ( $type   === MM_WPFS::FORM_TYPE_PAYMENT &&
            $layout === MM_WPFS::FORM_LAYOUT_CHECKOUT ) {
            $editUrl = self::createFormEditUrl( MM_WPFS_Admin_Menu::SLUG_EDIT_FORM, $id, MM_WPFS::FORM_TYPE_CHECKOUT_PAYMENT );
        } elseif (  $type   === MM_WPFS::FORM_TYPE_SUBSCRIPTION &&
            $layout === MM_WPFS::FORM_LAYOUT_INLINE ) {
            $editUrl = self::createFormEditUrl( MM_WPFS_Admin_Menu::SLUG_EDIT_FORM, $id, MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION );
        } elseif (  $type   === MM_WPFS::FORM_TYPE_SUBSCRIPTION &&
            $layout === MM_WPFS::FORM_LAYOUT_CHECKOUT ) {
            $editUrl = self::createFormEditUrl( MM_WPFS_Admin_Menu::SLUG_EDIT_FORM, $id, MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION );
        } elseif (  $type   === MM_WPFS::FORM_TYPE_DONATION &&
            $layout === MM_WPFS::FORM_LAYOUT_INLINE ) {
            $editUrl = self::createFormEditUrl( MM_WPFS_Admin_Menu::SLUG_EDIT_FORM, $id, MM_WPFS::FORM_TYPE_INLINE_DONATION );
        } elseif (  $type   === MM_WPFS::FORM_TYPE_DONATION &&
            $layout === MM_WPFS::FORM_LAYOUT_CHECKOUT ) {
            $editUrl = self::createFormEditUrl( MM_WPFS_Admin_Menu::SLUG_EDIT_FORM, $id, MM_WPFS::FORM_TYPE_CHECKOUT_DONATION );
        } elseif (  $type   === MM_WPFS::FORM_TYPE_SAVE_CARD &&
            $layout === MM_WPFS::FORM_LAYOUT_INLINE ) {
            $editUrl = self::createFormEditUrl( MM_WPFS_Admin_Menu::SLUG_EDIT_FORM, $id, MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD );
        } elseif (  $type   === MM_WPFS::FORM_TYPE_SAVE_CARD &&
            $layout === MM_WPFS::FORM_LAYOUT_CHECKOUT ) {
            $editUrl = self::createFormEditUrl( MM_WPFS_Admin_Menu::SLUG_EDIT_FORM, $id, MM_WPFS::FORM_TYPE_CHECKOUT_SAVE_CARD );
        }

        return $editUrl;
    }


    /**
     * Used by WPFSM, WPFP_Mailchimp
     */
    public static function generateFormElementId($element_id, $form_hash, $index = null ) {
        if ( is_null( $element_id ) ) {
            return null;
        }

        $generated_id = $element_id . MM_WPFS_Utils::ELEMENT_PART_SEPARATOR . $form_hash;
        if ( ! is_null( $index ) ) {
            $generated_id .= MM_WPFS_Utils::ELEMENT_PART_SEPARATOR . $index;
        }

        return esc_attr( $generated_id );
    }

    public static function generateFormHash($form_type, $form_id, $form_name ) {
        $data = $form_type . '|' . $form_id . '|' . $form_name;

        return substr( base64_encode( hash( 'sha256', $data ) ), 0, 7 );
    }

    /**
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
    public static function prepareAddressData($line1, $line2, $city, $state, $countryName, $countryCode, $zip ) {
        $addressData = array(
            'line1'        => is_null( $line1 ) ? '' : $line1,
            'line2'        => is_null( $line2 ) ? '' : $line2,
            'city'         => is_null( $city ) ? '' : $city,
            'state'        => is_null( $state ) ? '' : $state,
            'country'      => is_null( $countryName ) ? '' : $countryName,
            'country_code' => is_null( $countryCode ) ? '' : $countryCode,
            'zip'          => is_null( $zip ) ? '' : $zip
        );

        return $addressData;
    }

    /**
     * This function creates a Stripe shipping address hash
     *
     * @param $shipping_name
     * @param $shipping_phone
     * @param $address_array array previously created with prepare_address_data()
     *
     * @return array
     */
    public static function prepareStripeShippingHashFromArray($shipping_name, $shipping_phone, $address_array ) {
        return self::prepare_stripe_shipping_hash(
            $shipping_name,
            $shipping_phone,
            $address_array['line1'],
            $address_array['line2'],
            $address_array['city'],
            $address_array['state'],
            $address_array['country_code'],
            $address_array['zip']
        );
    }

    /**
     * This function creates a Stripe shipping address hash
     *
     * @param $shipping_name string Customer name
     * @param $shipping_phone string Customer phone (including extension)
     * @param $line1 string Address line 1 (Street address/PO Box/Company name)
     * @param $line2 string Address line 2 (Apartment/Suite/Unit/Building)
     * @param $city string City/District/Suburb/Town/Village
     * @param $state string State/County/Province/Region
     * @param $country_code string 2-letter country code
     * @param $postal_code string ZIP or postal code
     *
     * @return array
     */
    public static function prepare_stripe_shipping_hash( $shipping_name, $shipping_phone, $line1, $line2, $city, $state, $country_code, $postal_code ) {
        $shipping_hash = array();

        //-- The 'name' property is required. It must contain a non-empty value or be null
        $shipping_hash['name'] = ! empty( $shipping_name ) ? $shipping_name : null;

        if ( ! empty( $shipping_phone ) ) {
            $shipping_hash['phone'] = $shipping_phone;
        }
        $address_hash             = self::prepare_stripe_address_hash( $line1, $line2, $city, $state, $country_code, $postal_code );
        $shipping_hash['address'] = $address_hash;

        return $shipping_hash;
    }

    /**
     * This function creates a Stripe address hash
     *
     * @param $line1 string Address line 1 (Street address/PO Box/Company name)
     * @param $line2 string Address line 2 (Apartment/Suite/Unit/Building)
     * @param $city string City/District/Suburb/Town/Village
     * @param $state string State/County/Province/Region
     * @param $country_code string 2-letter country code
     * @param $postal_code string ZIP or postal code
     *
     * @return array
     */
    public static function prepare_stripe_address_hash( $line1, $line2, $city, $state, $country_code, $postal_code ) {
        $address_hash = array();

        //-- The 'line1' property is required
        if ( empty( $line1 ) ) {
            throw new InvalidArgumentException( __FUNCTION__ . '(): address line1 is required.' );
        } else {
            $address_hash['line1'] = $line1;
        }
        if ( ! empty( $line2 ) ) {
            $address_hash['line2'] = $line2;
        }
        if ( ! empty( $city ) ) {
            $address_hash['city'] = $city;
        }
        if ( ! empty( $state ) ) {
            $address_hash['state'] = $state;
        }
        if ( ! empty( $country_code ) ) {
            $address_hash['country'] = $country_code;
        }
        if ( ! empty( $postal_code ) ) {
            $address_hash['postal_code'] = $postal_code;
        }

        return $address_hash;
    }

    /**
     * This function creates a Stripe address hash from an array created previously created with prepare_address_data()
     *
     * @param array $address_array
     *
     * @return array
     */
    public static function prepareStripeBillingAddressHashFromArray($address_array ) {
        return self::prepare_stripe_address_hash(
            $address_array['line1'],
            $address_array['line2'],
            $address_array['city'],
            $address_array['state'],
            $address_array['country_code'],
            $address_array['zip']
        );
    }

    /**
     * @param $value
     * @param $escapeType
     *
     * @return string|void
     */
    public static function escape( $value, $escapeType ) {
        if ( is_null( $value ) ) {
            return $value;
        }
        if ( self::ESCAPE_TYPE_HTML === $escapeType ) {
            return esc_html( $value );
        } elseif ( self::ESCAPE_TYPE_ATTR === $escapeType ) {
            return esc_attr( $value );
        } else {
            return $value;
        }
    }

    /**
     * todo: remove this function
     * @param $netValue
     * @param $taxPercent
     *
     * @return mixed
     */
    public static function calculateGrossFromNet( $netValue, $taxPercent ) {
        if ( ! is_numeric( $netValue ) ) {
            throw new InvalidArgumentException( sprintf( 'Parameter %s=%s is not numeric.', 'netValue', $netValue ) );
        }
        if ( ! is_numeric( $taxPercent ) ) {
            throw new InvalidArgumentException( sprintf( 'Parameter %s=%s is not numeric.', 'taxPercent', $taxPercent ) );
        }

        if ( $taxPercent == 0.0 ) {
            $grossValue = $netValue;
            $taxValue   = 0;
        } else {
            $grossValue = round( $netValue * ( 1.0 + round( $taxPercent, 4 ) / 100.0 ) );
            $taxValue   = $grossValue - $netValue;
        }

        $result = array(
            'net'        => $netValue,
            'taxPercent' => $taxPercent,
            'taxValue'   => $taxValue,
            'gross'      => $grossValue
        );

        return $result;
    }

    /**
     * @param $stripePlans
     *
     * @return array
     */
    public static function getStripePlanLookup($stripePlans ) {
        $planIds = array();

        foreach ( $stripePlans as $stripePlan) {
            $planIds[ $stripePlan->id ] = $stripePlan;
        }

        return $planIds;
    }

    /**
     * @param $stripePlans
     * @param $formPlans
     *
     * @return array
     */
    public static function getSortedFormPlans( $stripePlans, $formPlans ) {
        $plans = array();
        $formPlanProperties = !is_null( $formPlans ) ? json_decode( $formPlans ) : array();
        $stripePlanLookup = self::getStripePlanLookup( $stripePlans );

        foreach ( $formPlanProperties as $formPlanProperty) {
            if ( array_key_exists( $formPlanProperty->stripePriceId, $stripePlanLookup ) ) {
                $plan = new \StdClass;
                $plan->properties = $formPlanProperty;
                $plan->stripePlan = $stripePlanLookup[ $formPlanProperty->stripePriceId ];

                array_push( $plans, $plan );
            }
        }

        return $plans;
    }

    /**
     * Parse amount as smallest common currency unit with the given currency if the amount is a number.
     *
     * @param $currency
     * @param $amount
     *
     * @return int|string the parsed value if the amount is a valid number, the amount itself otherwise
     */
    public static function parse_amount( $currency, $amount ) {
        if ( ! is_numeric( $amount ) ) {
            return $amount;
        }
        $currencyArray = MM_WPFS_Currencies::getCurrencyFor( $currency );
        if ( is_array( $currencyArray ) ) {
            if ( $currencyArray['zeroDecimalSupport'] == true ) {
                $theAmount = $amount;
            } else {
                $theAmount = $amount * 100.0;
            }

            return $theAmount;
        }

        return $amount;
    }

    /**
     * @param $encodedCustomInputs
     *
     * @return array
     */
    public static function decodeCustomFieldLabels($encodedCustomInputs ) {
        $customInputLabels = array();
        if ( ! is_null( $encodedCustomInputs ) && !empty( $encodedCustomInputs ) ) {
            $customInputLabels = explode( '{{', $encodedCustomInputs );
        }

        return $customInputLabels;
    }

    /**
     * Used by WPFSM
     */
    public static function log( $message ) {
        error_log( self::WPFS_LOG_MESSAGE_PREFIX . $message );
    }

    /**
     * Used by WPFSM
     */
    public static function logException( Exception $e, $object = null ) {
        if ( isset( $e ) ) {
            if ( is_null( $object ) ) {
                $message = sprintf( 'Message=%s, Stack=%s ', $e->getMessage(), $e->getTraceAsString() );
            } else {
                $message = sprintf( 'Class=%s, Message=%s, Stack=%s ', get_class( $object ), $e->getMessage(), $e->getTraceAsString() );
            }
            MM_WPFS_Utils::log( $message );
        }
    }

    /**
     * @param $context MM_WPFS_StaticContext
     * @param $paymentFormModel
     * @param $transactionData
     * @return array|string|string[]
     */
    public static function prepareStripeChargeDescription( $context, $paymentFormModel, $transactionData ) {
        $stripeChargeDescription = '';
        if ( isset( $paymentFormModel->getForm()->stripeDescription ) && ! empty( $paymentFormModel->getForm()->stripeDescription ) ) {
            $formStripeDescription   = MM_WPFS_Localization::translateLabel( $paymentFormModel->getForm()->stripeDescription );

            $replacer = new MM_WPFS_OneTimePaymentMacroReplacer( $paymentFormModel->getForm(), $transactionData, $context->getLoggerService() );
            $stripeChargeDescription = $replacer->replaceMacrosWithHtmlEscape( $formStripeDescription );
        }

        return $stripeChargeDescription;
    }

    /**
     * @param MM_WPFS_StaticContext $context
     * @param MM_WPFS_Public_DonationFormModel $donationFormModel
     * @param MM_WPFS_DonationTransactionData $transactionData
     *
     * @return mixed
     */
    public static function prepareStripeDonationDescription( $context, $donationFormModel, $transactionData ) {
        $stripeChargeDescription = '';
        if ( isset( $donationFormModel->getForm()->stripeDescription ) && ! empty( $donationFormModel->getForm()->stripeDescription ) ) {
            $formStripeDescription   = MM_WPFS_Localization::translateLabel( $donationFormModel->getForm()->stripeDescription );

            $replacer = new MM_WPFS_DonationMacroReplacer( $donationFormModel->getForm(), $transactionData, $context->getLoggerService() );
            $stripeChargeDescription = $replacer->replaceMacrosWithHtmlEscape( $formStripeDescription );
        }

        return $stripeChargeDescription;
    }

    /**
     * @param MM_WPFS_StaticContext $context
     * @param MM_WPFS_Public_PaymentFormModel $saveCardFormModel
     * @param MM_WPFS_SaveCardTransactionData $transactionData
     *
     * @return mixed
     */
    public static function prepareStripeCardSavedDescription( $context, $saveCardFormModel, $transactionData ) {
        $stripeCustomerDescription = '';
        if ( isset( $saveCardFormModel->getForm()->stripeDescription ) && ! empty( $saveCardFormModel->getForm()->stripeDescription ) ) {
            $formStripeDescription     = MM_WPFS_Localization::translateLabel( $saveCardFormModel->getForm()->stripeDescription );

            $replacer = new MM_WPFS_SaveCardMacroReplacer( $saveCardFormModel->getForm(), $transactionData, $context->getLoggerService() );
            $stripeCustomerDescription = $replacer->replaceMacrosWithHtmlEscape( $formStripeDescription );
        }

        return $stripeCustomerDescription;
    }

    /**
     * @param $context MM_WPFS_StaticContext
     * @return bool
     */
    public static function getCancelSubscriptionsAtPeriodEnd( $context ) {
        return $context->getOptions()->get( MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_WHEN_CANCEL_SUBSCRIPTIONS ) === MM_WPFS::CANCEL_SUBSCRIPTION_AT_PERIOD_END;
    }

    public static function getDefaultPaymentStripeDescription() {
        return
            /* translators: Default transaction description for one-time payments */
            __( 'Payment on form %FORM_NAME%', 'wp-full-stripe' );
    }

    public static function getDefaultSaveCardDescription() {
        return
            /* translators: Default transaction description for saved cards */
            __( 'Card saved on form %FORM_NAME%', 'wp-full-stripe' );
    }

    public static function getDefaultDonationDescription() {
        return
            /* translators: Default transaction description for donations */
            __( 'Donation on form %FORM_NAME%', 'wp-full-stripe' );
    }

    public static function getDefaultTermsOfUseLabel() {
        $defaultTermsOfUseURL = home_url( '/terms-of-use' );

        return sprintf(
        /* translators: Default label for the Terms of Use checkbox */
            __( "I accept the <a href='%s' target='_blank'>Terms of Use</a>" ), $defaultTermsOfUseURL );
    }

    public static function getDefaultTermsOfUseNotCheckedErrorMessage() {
        return
            /* translators: Field validation error message when the Terms of use checkbox is not checked */
            __( 'Please accept the Terms of Use', 'wp-full-stripe' );
    }

    public static function getDefaultCouponInvalidErrorMessage() {
        return
            /* translators: Banner message of expired coupon */
            __( 'This coupon has expired.', 'wp-full-stripe' );
    }

    public static function getDefaultCouponDoesNotApplyToErrorMessage() {
        return
            /* translators: Banner message of applied coupon */
            __( 'The coupon doesn\'t apply to this service or product', 'wp-full-stripe' );
    }

    public static function getDefaultInvalidCouponCurrencyErrorMessage() {
        return
            /* translators: Banner message of expired coupon */
            __( 'This coupon has an invalid currency.', 'wp-full-stripe' );
    }

    public static function getDefaultPaymentButtonTitle() {
        return
            /* translators: Default payment button label on inline one-time payment forms */
            __( 'Make payment', 'wp-full-stripe' );
    }

    public static function getDefaultSaveCardButtonTitle() {
        return
            /* translators: Default payment button label on inline save card forms */
            __( 'Save card', 'wp-full-stripe' );
    }

    public static function getDefaultPaymentOpenButtonTitle() {
        return
            /* translators: Default payment button label on checkout one-time payment forms */
            __( 'Make payment', 'wp-full-stripe' );
    }

    public static function getDefaultSubscriptionButtonTitle() {
        return
            /* translators: Default subscription button label on inline subscription forms */
            __( 'Subscribe', 'wp-full-stripe' );
    }

    public static function getDefaultSubscriptionOpenButtonTitle() {
        return
            /* translators: Default subscription button label on checkout subscription forms */
            __( 'Subscribe', 'wp-full-stripe' );
    }

    public static function getDefaultDonationButtonTitle() {
        return
            /* translators: Default donation button label on inline donation forms */
            __( 'Donate', 'wp-full-stripe' );
    }

    public static function getDefaultDonationOpenButtonTitle() {
        return
            /* translators: Default donation button label on inline donation forms */
            __( 'Donate', 'wp-full-stripe' );
    }

    public static function getDefaultProductDescription() {
        /* translators: Placeholder product name for newly created one-time payment forms */
        return __('My Product', 'wp-full-stripe');
    }

    public static function getDefaultDonationProductDescription() {
        /* translators: Placeholder product name for newly created donation forms */
        return __('My Donation', 'wp-full-stripe');
    }

    public static function getPaymentStatuses() {
        return array(
            MM_WPFS::PAYMENT_STATUS_FAILED,
            MM_WPFS::PAYMENT_STATUS_RELEASED,
            MM_WPFS::PAYMENT_STATUS_REFUNDED,
            MM_WPFS::PAYMENT_STATUS_EXPIRED,
            MM_WPFS::PAYMENT_STATUS_PAID,
            MM_WPFS::PAYMENT_STATUS_AUTHORIZED,
            MM_WPFS::PAYMENT_STATUS_PENDING
        );
    }

    public static function getSubscriptionStatuses() {
        return array(
            MM_WPFS::SUBSCRIBER_STATUS_INCOMPLETE,
            MM_WPFS::SUBSCRIBER_STATUS_RUNNING,
            MM_WPFS::SUBSCRIBER_STATUS_ENDED,
            MM_WPFS::SUBSCRIBER_STATUS_CANCELLED
        );
    }

    /**
     * @param $payment
     *
     * @return string
     */
    public static function getPaymentStatus($payment ) {
        if ( is_null( $payment ) ) {
            $payment_status = MM_WPFS::PAYMENT_STATUS_UNKNOWN;
        } elseif ( MM_WPFS::STRIPE_CHARGE_STATUS_FAILED === $payment->last_charge_status ) {
            $payment_status = MM_WPFS::PAYMENT_STATUS_FAILED;
        } elseif ( MM_WPFS::STRIPE_CHARGE_STATUS_PENDING === $payment->last_charge_status ) {
            $payment_status = MM_WPFS::PAYMENT_STATUS_PENDING;
        } elseif ( 1 == $payment->expired ) {
            $payment_status = MM_WPFS::PAYMENT_STATUS_EXPIRED;
        } elseif ( 1 == $payment->refunded ) {
            if ( 1 == $payment->captured ) {
                $payment_status = MM_WPFS::PAYMENT_STATUS_REFUNDED;
            } else {
                $payment_status = MM_WPFS::PAYMENT_STATUS_RELEASED;
            }
        } elseif ( MM_WPFS::STRIPE_CHARGE_STATUS_SUCCEEDED === $payment->last_charge_status && 1 == $payment->paid && 1 == $payment->captured ) {
            $payment_status = MM_WPFS::PAYMENT_STATUS_PAID;
        } elseif ( MM_WPFS::STRIPE_CHARGE_STATUS_SUCCEEDED === $payment->last_charge_status && 1 == $payment->paid && 0 == $payment->captured ) {
            $payment_status = MM_WPFS::PAYMENT_STATUS_AUTHORIZED;
        } else {
            $payment_status = MM_WPFS::PAYMENT_STATUS_UNKNOWN;
        }

        return $payment_status;
    }

    /**
     * @param $donation
     *
     * @return string
     */
    public static function getDonationPaymentStatus($donation ) {
        if ( is_null( $donation ) ) {
            $payment_status = MM_WPFS::PAYMENT_STATUS_UNKNOWN;
        } elseif ( MM_WPFS::STRIPE_CHARGE_STATUS_FAILED === $donation->lastChargeStatus ) {
            $payment_status = MM_WPFS::PAYMENT_STATUS_FAILED;
        } elseif ( MM_WPFS::STRIPE_CHARGE_STATUS_PENDING === $donation->lastChargeStatus ) {
            $payment_status = MM_WPFS::PAYMENT_STATUS_PENDING;
        } elseif ( 1 == $donation->expired ) {
            $payment_status = MM_WPFS::PAYMENT_STATUS_EXPIRED;
        } elseif ( 1 == $donation->refunded ) {
            if ( 1 == $donation->captured ) {
                $payment_status = MM_WPFS::PAYMENT_STATUS_REFUNDED;
            } else {
                $payment_status = MM_WPFS::PAYMENT_STATUS_RELEASED;
            }
        } elseif ( MM_WPFS::STRIPE_CHARGE_STATUS_SUCCEEDED === $donation->lastChargeStatus && 1 == $donation->paid && 1 == $donation->captured ) {
            $payment_status = MM_WPFS::PAYMENT_STATUS_PAID;
        } elseif ( MM_WPFS::STRIPE_CHARGE_STATUS_SUCCEEDED === $donation->lastChargeStatus && 1 == $donation->paid && 0 == $donation->captured ) {
            $payment_status = MM_WPFS::PAYMENT_STATUS_AUTHORIZED;
        } else {
            $payment_status = MM_WPFS::PAYMENT_STATUS_UNKNOWN;
        }

        return $payment_status;
    }

    /**
     * @param $donation
     *
     * @return string
     */
    public static function getDonationStatus( $donation ) : string {
        $status         = MM_WPFS::DONATION_STATUS_UNKNOWN;
        $oneTimeStatus  = MM_WPFS_Utils::getDonationPaymentStatus( $donation );

        if ( MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_ONE_TIME === $donation->donationFrequency ) {
            if ( MM_WPFS::PAYMENT_STATUS_PAID === $oneTimeStatus ) {
                $status = MM_WPFS::DONATION_STATUS_PAID;
            } else if ( MM_WPFS::PAYMENT_STATUS_REFUNDED === $oneTimeStatus  ) {
                $status = MM_WPFS::DONATION_STATUS_REFUNDED;
            }
        } else {
            if ( $donation->subscriptionStatus === \StripeWPFS\Subscription::STATUS_ACTIVE ) {
                $status = MM_WPFS::DONATION_STATUS_RUNNING;
            } else if ( $donation->subscriptionStatus === MM_WPFS::SUBSCRIBER_STATUS_CANCELLED &&
                MM_WPFS::PAYMENT_STATUS_PAID === $oneTimeStatus ) {
                $status = MM_WPFS::DONATION_STATUS_PAID;
            } else if ( $donation->subscriptionStatus === MM_WPFS::SUBSCRIBER_STATUS_CANCELLED &&
                MM_WPFS::PAYMENT_STATUS_REFUNDED === $oneTimeStatus ) {
                $status = MM_WPFS::DONATION_STATUS_REFUNDED;
            }
        }

        return $status;
    }

    /**
     * @param $form
     *
     * @return null|string
     */
    public static function getFormId( $form ) {
        if ( is_null( $form ) ) {
            return null;
        }
        if ( isset( $form->paymentFormID ) ) {
            return $form->paymentFormID;
        }
        if ( isset( $form->subscriptionFormID ) ) {
            return $form->subscriptionFormID;
        }
        if ( isset( $form->checkoutFormID ) ) {
            return $form->checkoutFormID;
        }
        if ( isset( $form->checkoutSubscriptionFormID ) ) {
            return $form->checkoutSubscriptionFormID;
        }
        if ( isset( $form->donationFormID ) ) {
            return $form->donationFormID;
        }
        if ( isset( $form->checkoutDonationFormID ) ) {
            return $form->checkoutDonationFormID;
        }

        return null;
    }

    /**
     * @param $payment
     *
     * @return string
     */
    public static function getPaymentObjectType($payment ) {
        if ( isset( $payment ) && isset( $payment->eventID ) ) {
            if ( strlen( $payment->eventID ) > 3 ) {
                if ( MM_WPFS::STRIPE_OBJECT_ID_PREFIX_PAYMENT_INTENT === substr( $payment->eventID, 0, 3 ) ) {
                    return MM_WPFS::PAYMENT_OBJECT_TYPE_STRIPE_PAYMENT_INTENT;
                } elseif ( MM_WPFS::STRIPE_OBJECT_ID_PREFIX_CHARGE === substr( $payment->eventID, 0, 3 ) ) {
                    return MM_WPFS::PAYMENT_OBJECT_TYPE_STRIPE_CHARGE;
                }
            }
        }

        return MM_WPFS::PAYMENT_OBJECT_TYPE_UNKNOWN;
    }

    /**
     * @param MM_WPFS_Public_PaymentFormModel $paymentFormModel
     *
     * @return bool
     */
    public static function hasToCapturePaymentIntentByFormModel($paymentFormModel ) {
        if ( MM_WPFS::CHARGE_TYPE_IMMEDIATE === $paymentFormModel->getForm()->chargeType ) {
            $capture = true;
        } elseif ( MM_WPFS::CHARGE_TYPE_AUTHORIZE_AND_CAPTURE === $paymentFormModel->getForm()->chargeType ) {
            $capture = false;
        } else {
            $capture = true;
        }

        return $capture;
    }

    /**
     * @param MM_WPFS_Public_FormModel $formModelObject
     *
     * @return mixed|string|void
     */
    public static function generateFormNonce( $formModelObject ) {
        $nonceObject            = new stdClass();
        $nonceObject->created   = time();
        $nonceObject->formHash  = $formModelObject->getFormHash();
        $nonceObject->fieldHash = md5( json_encode( $formModelObject ) );
        $nonceObject->salt      = wp_generate_password( 16, false );

        return json_encode( $nonceObject );
    }

    public static function decodeFormNonce( $text ) {
        $decodedObject = json_decode( $text );

        if ( null === $decodedObject || false === $decodedObject || JSON_ERROR_NONE !== json_last_error() ) {
            return false;
        }

        return $decodedObject;
    }

    public static function encrypt( $message ) {
        $nonce = \Sodium\randombytes_buf( \Sodium\CRYPTO_SECRETBOX_NONCEBYTES );

        $encodedMessage = base64_encode(
            $nonce . \Sodium\crypto_secretbox(
                $message,
                $nonce,
                self::getEncryptionKey()
            )
        );

        return $encodedMessage;
    }

    /**
     * @return string
     * @throws Exception
     */
    private static function getEncryptionKey() {
        $desiredKeyLength = 32;
        if ( strlen( NONCE_KEY ) == $desiredKeyLength ) {
            return NONCE_KEY;
        } elseif ( strlen( NONCE_KEY ) > $desiredKeyLength ) {
            return substr( NONCE_KEY, 0, 32 );
        } else {
            throw new Exception( 'WordPress Constant NONCE_KEY is too short' );
        }
    }

    public static function decrypt( $secretMessage ) {
        $decodedMessage   = base64_decode( $secretMessage );
        $nonce            = mb_substr( $decodedMessage, 0, \Sodium\CRYPTO_SECRETBOX_NONCEBYTES, '8bit' );
        $encryptedMessage = mb_substr( $decodedMessage, \Sodium\CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit' );
        $decryptedMessage = \Sodium\crypto_secretbox_open( $encryptedMessage, $nonce, self::getEncryptionKey() );

        return $decryptedMessage;
    }

    /**
     * This function is the exact copy of wp_timezone_string() of WordPress.
     * We had to copy it here because it's available only since v5.3.0 .
     *
     * @return string
     */
    public static function getWordpressTimezone() {
        $tz_string = get_option('timezone_string');
        $tz_offset = get_option('gmt_offset', 0);

        if (!empty($tz_string)) {
            $timezone = $tz_string;
        } elseif ($tz_offset == 0) {
            $timezone = 'UTC';
        } else {
            $timezone = $tz_offset;

            if(substr($tz_offset, 0, 1) != "-" && substr($tz_offset, 0, 1) != "+" && substr($tz_offset, 0, 1) != "U") {
                $timezone = "+" . $tz_offset;
            }
        }

        return $timezone;
    }


    public static function calculateTrialEndFromNow($trialDays ) {
        $currentTimestamp     = time();
        $oneDayInSeconds      = 24 * 60 * 60;

        return $currentTimestamp + $trialDays * $oneDayInSeconds;
    }

    public static function calculateBillingCycleAnchorFromNow( $billingCycleAnchorDay ) {
        return self::calculateBillingCycleAnchorFromTimestamp( $billingCycleAnchorDay, time() );
    }

    public static function calculateBillingCycleAnchorFromTimestamp( $billingCycleAnchorDay, $startingTimestamp ) {
        $oneDayInSeconds      = 24 * 60 * 60;

        $datetime = new DateTime();
        $datetime->setTimestamp($startingTimestamp);
        $datetime->setTimezone(new DateTimeZone( self::getWordpressTimezone() ));

        $currentDayOfMonth    = $datetime->format("d" );
        $numDaysInMonth       = $datetime->format("t" );

        $billingAnchorTimestamp = null;
        if ( $billingCycleAnchorDay >= $currentDayOfMonth ) {
            $billingAnchorTimestamp = $startingTimestamp + ( $billingCycleAnchorDay - $currentDayOfMonth ) * $oneDayInSeconds;
        } else {
            $billingAnchorTimestamp = $startingTimestamp + ( $numDaysInMonth - $currentDayOfMonth + $billingCycleAnchorDay ) * $oneDayInSeconds;
        }

        return $billingAnchorTimestamp;
    }

    public static function decodeJsonArray( $arr ) {
        $res = json_decode( $arr );
        if ( json_last_error() != JSON_ERROR_NONE ) {
            // todo: Log the json decode error
            $res = array();
        }

        return $res;
    }

    /**
     * @param $bindingResult MM_WPFS_BindingResult
     *
     * @return array
     */
    public static function generateReturnValueFromBindings( $bindingResult ) {
        return array(
            'success' => false,
            'bindingResult' => array(
                'fieldErrors' => array(
                    'title' =>
                    /* translators: Banner title of a hidden field's validation error */
                        __('Field validation error', 'wp-full-stripe'),
                    'errors' => $bindingResult->getFieldErrors()
                ),
                'globalErrors' => array(
                    'title' =>
                    /* translators: Banner title of a validation error which is not field specific */
                        __('Form error', 'wp-full-stripe'),
                    'errors' => $bindingResult->getGlobalErrors()
                )
            )
        );
    }

    public static function determineCustomerName($cardHolderName, $businessName, $billingName) {
        $result = null;

        if (is_null($result)) {
            $result = $cardHolderName;
        }

        if (is_null($result)) {
            $result = $billingName;
        }

        if (is_null($result) && !empty($businessName)) {
            $result = $businessName;
        }

        return $result;
    }

    public static function formatTimeStamp( $timestamp, $format ) {
        $datetime = new DateTime();
        $datetime->setTimestamp($timestamp);
        $datetime->setTimezone(new DateTimeZone( self::getWordpressTimezone() ));

        return $datetime->format($format);
    }

    /**
     * @param $timestamp
     * @return false|string
     */
    public static function formatTimestampWithWordpressDateFormat($timestamp ) {
        return self::formatTimeStamp(
            $timestamp,
            get_option( 'date_format' )
        );
    }

    /**
     * @param $timestamp
     * @return false|string
     */
    public static function formatTimestampWithWordpressDateTimeFormat($timestamp ) {
        return self::formatTimeStamp(
            $timestamp,
            get_option( 'date_format' ) . ' ' . get_option( 'time_format' )
        );
    }

    /**
     * @return bool
     */
    public static function isFreemiusLicensing() {
        global $wpfs_fs;
        return isset( $wpfs_fs );
    }

    public static function getMinimumPlanQuantityOfSubscription( $subscription ) {
        $minimumPlanQuantity = 0;
        if (isset($subscription->metadata) && isset($subscription->metadata->minimum_quantity_of_subscriptions)) {
            $minimumPlanQuantity = intval($subscription->metadata->minimum_quantity_of_subscriptions);
        }

        return $minimumPlanQuantity;
    }

    public static function getMaximumPlanQuantityOfSubscription( $subscription ) {
        $maximumPlanQuantity = 0;
        if (isset($subscription->metadata) && isset($subscription->metadata->maximum_quantity_of_subscriptions)) {
            $maximumPlanQuantity = intval($subscription->metadata->maximum_quantity_of_subscriptions);
        }

        return $maximumPlanQuantity;
    }

    public static function truncateString($description, int $length) {
        return substr( $description,0, $length );
    }

    public static function boolToString($boolVal): string {
        return $boolVal ? 'true' : 'false';
    }
}
