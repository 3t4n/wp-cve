<?php

class MM_WPFS_ConfigurableFormFieldsFactory {
    public static function createConfiguration( $formType ) {
        switch ( $formType ) {
            case MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD:
                return new MM_WPFS_InlineSaveCardConfigurableFormFields();
            case MM_WPFS::FORM_TYPE_INLINE_PAYMENT:
                return new MM_WPFS_InlinePaymentConfigurableFormFields();
            case MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION:
                return new MM_WPFS_InlineSubscriptionConfigurableFormFields();
            case MM_WPFS::FORM_TYPE_INLINE_DONATION:
                return new MM_WPFS_InlineDonationConfigurableFormFields();
            case MM_WPFS::FORM_TYPE_CHECKOUT_SAVE_CARD:
                return new MM_WPFS_CheckoutSaveCardConfigurableFormFields();
            case MM_WPFS::FORM_TYPE_CHECKOUT_PAYMENT:
                return new MM_WPFS_CheckoutPaymentConfigurableFormFields();
            case MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION:
                return new MM_WPFS_CheckoutSubscriptionConfigurableFormFields();
            case MM_WPFS::FORM_TYPE_CHECKOUT_DONATION:
                return new MM_WPFS_CheckoutDonationConfigurableFormFields();
        }
    }
}

class MM_WPFS_FormFieldConfiguration {
    protected $fieldName;
    protected $value;
    protected $isReadonly;
    protected $isConfigurable;

    public function __construct( $fieldName, $isConfigurable, $isReadyOnly, $value ) {
        $this->fieldName = $fieldName;
        $this->isConfigurable = $isConfigurable;
        $this->isReadonly = $isReadyOnly;
        $this->value = $value;
    }

    public function __toString() {
        return 'field: '            . $this->fieldName .
               ' value: '           . $this->value .
               ' isConfigurable: '  . MM_WPFS_Utils::boolToString($this->isConfigurable) .
               ' isReadonly: '      . MM_WPFS_Utils::boolToString($this->isReadonly);
    }

    /**
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function isReadonly() {
        return $this->isReadonly;
    }

    /**
     * @return mixed
     */
    public function isConfigurable() {
        return $this->isConfigurable;
    }

    /**
     * @param mixed $value
     */
    public function setValue( $value ) {
        $this->value = $value;
    }

    /**
     * @param mixed $isReadonly
     */
    public function setIsReadonly($isReadonly ) {
        $this->isReadonly = $isReadonly;
    }

    /**
     * @param mixed $isConfigurable
     */
    public function setIsConfigurable($isConfigurable ) {
        $this->isConfigurable = $isConfigurable;
    }
}

abstract class MM_WPFS_ConfigurableFormFields {
    const FIELD_EMAIL = 'email';
    const FIELD_CUSTOMER_ID = 'customerId';
    const FIELD_CARDHOLDERS_NAME = 'cardholdersName';
    const FIELD_CUSTOM_PREFIX = 'customField';
    const FIELD_BILLING_NAME = 'billingName';
    const FIELD_BILLING_ADDRESS = 'billingAddress';
    const FIELD_BILLING_ADDRESS2 = 'billingAddress2';
    const FIELD_BILLING_CITY = 'billingCity';
    const FIELD_BILLING_ZIP = 'billingZip';
    const FIELD_BILLING_STATE = 'billingState';
    const FIELD_BILLING_COUNTRY_CODE = 'billingCountryCode';
    const FIELD_SHIPPING_NAME = 'shippingName';
    const FIELD_SHIPPING_ADDRESS = 'shippingAddress';
    const FIELD_SHIPPING_ADDRESS2 = 'shippingAddress2';
    const FIELD_SHIPPING_CITY = 'shippingCity';
    const FIELD_SHIPPING_ZIP = 'shippingZip';
    const FIELD_SHIPPING_STATE = 'shippingState';
    const FIELD_SHIPPING_COUNTRY_CODE = 'shippingCountryCode';
    const FIELD_PRICE = 'price';
    const FIELD_COUPON = 'coupon';
    const FIELD_AMOUNT = 'amount';

    public static function generateFieldConfiguration( $configurableFields ) {
        $result = [];

        foreach ( $configurableFields as $fieldName => $isConfigurable ) {
            $fieldConfig = new MM_WPFS_FormFieldConfiguration( $fieldName, $isConfigurable, false, null );
            $result[ $fieldName ] = $fieldConfig;
        }

        return $result;
    }

    public static function addUrlParamsToConfiguration( $fieldConfiguration, $urlParams ) {
        $result = $fieldConfiguration;

        foreach ( $fieldConfiguration as $fieldName => $config ) {
            /** @var $config MM_WPFS_FormFieldConfiguration */
            if ( array_key_exists( $fieldName, $urlParams ) ) {
                $config->setValue( $urlParams[ $fieldName ] );
                $result[ $fieldName ] = $config;
            }
        }

        return $result;
    }

    protected function getCommonFields() : array {
        $result = [];

        for ( $idx = 0; $idx < MM_WPFS::DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT; $idx++ ) {
            $result[] = self::FIELD_CUSTOM_PREFIX . ($idx + 1);
        }

        $result = array_merge( $result, [
            MM_WPFS_ConfigurableFormFields::FIELD_EMAIL,

            MM_WPFS_ConfigurableFormFields::FIELD_BILLING_NAME,
            MM_WPFS_ConfigurableFormFields::FIELD_BILLING_ADDRESS,
            MM_WPFS_ConfigurableFormFields::FIELD_BILLING_ADDRESS2,
            MM_WPFS_ConfigurableFormFields::FIELD_BILLING_ZIP,
            MM_WPFS_ConfigurableFormFields::FIELD_BILLING_CITY,
            MM_WPFS_ConfigurableFormFields::FIELD_BILLING_STATE,
            MM_WPFS_ConfigurableFormFields::FIELD_BILLING_COUNTRY_CODE,

            MM_WPFS_ConfigurableFormFields::FIELD_SHIPPING_NAME,
            MM_WPFS_ConfigurableFormFields::FIELD_SHIPPING_ADDRESS,
            MM_WPFS_ConfigurableFormFields::FIELD_SHIPPING_ADDRESS2,
            MM_WPFS_ConfigurableFormFields::FIELD_SHIPPING_ZIP,
            MM_WPFS_ConfigurableFormFields::FIELD_SHIPPING_CITY,
            MM_WPFS_ConfigurableFormFields::FIELD_SHIPPING_STATE,
            MM_WPFS_ConfigurableFormFields::FIELD_SHIPPING_COUNTRY_CODE,
        ]);

        return $result;
    }

    /**
     * @param $fields
     * @return array
     */
    protected function generateFalseArray( $fields ) : array {
        $result = [];

        foreach( $fields as $field ) {
            $result[ $field ] = false;
        }

        return $result;
    }
}

trait MM_WPFS_InlineConfigurableFormFields_AddOn {
    protected function getInlineFields() : array {
        return [
            MM_WPFS_ConfigurableFormFields::FIELD_CARDHOLDERS_NAME,
        ];
    }
}

class MM_WPFS_InlineSaveCardConfigurableFormFields extends  MM_WPFS_ConfigurableFormFields {
    use MM_WPFS_InlineConfigurableFormFields_AddOn;

    public function getFields() : array {
        return $this->generateFalseArray( array_merge(
            $this->getCommonFields(),
            $this->getInlineFields()
        ));
    }
}

trait MM_WPFS_ProductConfigurableFormFields_AddOn {
    protected function getProductFields(): array {
        return [
            MM_WPFS_ConfigurableFormFields::FIELD_PRICE
        ];
    }
}

trait MM_WPFS_CouponConfigurableFormFields_AddOn {
    protected function getCouponFields(): array {
        return [
            MM_WPFS_ConfigurableFormFields::FIELD_COUPON
        ];
    }
}

trait MM_WPFS_CustomAmountConfigurableFormFields_AddOn {
    protected function getCustomAmountFields(): array {
        return [
            MM_WPFS_ConfigurableFormFields::FIELD_AMOUNT,
        ];
    }
}

class MM_WPFS_InlinePaymentConfigurableFormFields extends MM_WPFS_ConfigurableFormFields {
    use MM_WPFS_InlineConfigurableFormFields_AddOn;
    use MM_WPFS_ProductConfigurableFormFields_AddOn;
    use MM_WPFS_CouponConfigurableFormFields_AddOn;
    use MM_WPFS_CustomAmountConfigurableFormFields_AddOn;

    public function getFields() : array {
        return $this->generateFalseArray( array_merge(
            $this->getCommonFields(),
            $this->getInlineFields(),
            $this->getProductFields(),
            $this->getCouponFields(),
            $this->getCustomAmountFields()
        ));
    }
}

class MM_WPFS_InlineSubscriptionConfigurableFormFields extends MM_WPFS_ConfigurableFormFields {
    use MM_WPFS_InlineConfigurableFormFields_AddOn;
    use MM_WPFS_ProductConfigurableFormFields_AddOn;
    use MM_WPFS_CouponConfigurableFormFields_AddOn;

    public function getFields() : array {
        return $this->generateFalseArray( array_merge(
            $this->getCommonFields(),
            $this->getInlineFields(),
            $this->getProductFields(),
            $this->getCouponFields()
        ));
    }
}

class MM_WPFS_InlineDonationConfigurableFormFields extends MM_WPFS_ConfigurableFormFields {
    use MM_WPFS_InlineConfigurableFormFields_AddOn;
    use MM_WPFS_CustomAmountConfigurableFormFields_AddOn;

    public function getFields() : array {
        return $this->generateFalseArray( array_merge(
            $this->getCommonFields(),
            $this->getInlineFields(),
            $this->getCustomAmountFields()
        ));
    }
}

trait MM_WPFS_CheckoutConfigurableFormFields_AddOn {
    protected function getCheckoutFields(): array {
        return [
            MM_WPFS_ConfigurableFormFields::FIELD_CUSTOMER_ID
        ];
    }
}

class MM_WPFS_CheckoutSaveCardConfigurableFormFields extends MM_WPFS_ConfigurableFormFields {
    use MM_WPFS_CheckoutConfigurableFormFields_AddOn;
    public function getFields() : array {
        return $this->generateFalseArray( array_merge(
            $this->getCommonFields(),
            $this->getCheckoutFields()
        ));
    }
}
class MM_WPFS_CheckoutPaymentConfigurableFormFields extends MM_WPFS_ConfigurableFormFields {
    use MM_WPFS_CheckoutConfigurableFormFields_AddOn;
    use MM_WPFS_CustomAmountConfigurableFormFields_AddOn;
    use MM_WPFS_ProductConfigurableFormFields_AddOn;
    use MM_WPFS_CouponConfigurableFormFields_AddOn;
    public function getFields() : array {
        return $this->generateFalseArray( array_merge(
            $this->getCommonFields(),
            $this->getCheckoutFields(),
            $this->getProductFields(),
            $this->getCouponFields(),
            $this->getCustomAmountFields()
        ));
    }
}

class MM_WPFS_CheckoutSubscriptionConfigurableFormFields extends MM_WPFS_ConfigurableFormFields {
    use MM_WPFS_CheckoutConfigurableFormFields_AddOn;
    use MM_WPFS_ProductConfigurableFormFields_AddOn;
    use MM_WPFS_CouponConfigurableFormFields_AddOn;
    public function getFields() : array {
        return $this->generateFalseArray( array_merge(
            $this->getCommonFields(),
            $this->getCheckoutFields(),
            $this->getProductFields(),
            $this->getCouponFields()
        ));
    }
}

class MM_WPFS_CheckoutDonationConfigurableFormFields extends MM_WPFS_ConfigurableFormFields {
    use MM_WPFS_CheckoutConfigurableFormFields_AddOn;
    use MM_WPFS_CustomAmountConfigurableFormFields_AddOn;

    public function getFields() : array {
        return $this->generateFalseArray( array_merge(
            $this->getCommonFields(),
            $this->getCheckoutFields(),
            $this->getCustomAmountFields()
        ));
    }
}
