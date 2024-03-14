<?php
/** @noinspection PhpMultipleClassesDeclarationsInOneFile */

class MM_WPFS_Admin_CreateFormValidator extends MM_WPFS_Validator {

    /** @var MM_WPFS_Database */
    private $db = null;

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );

        $this->db = new MM_WPFS_Database();
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param MM_WPFS_Admin_CreateFormModel $formModelObject
     */
    public function validate($bindingResult, $formModelObject) {
        if ($formModelObject instanceof MM_WPFS_Admin_CreateFormModel) {
            $displayNameFieldName  = MM_WPFS_Admin_CreateFormViewConstants::FIELD_FORM_DISPLAY_NAME;
            $displayNameFieldId    = MM_WPFS_Utils::generateFormElementId( $displayNameFieldName, MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_CREATE_FORM);

            if ( empty( $formModelObject->getDisplayName() )) {
                $bindingResult->addFieldError( $displayNameFieldName, $displayNameFieldId,
                    /* translators: Create form validation error when no display name specified */
                    __( "Please enter a display name", 'wp-full-stripe-admin' ) );
            } elseif ( ! preg_match('/^[^\s]{1,}.{0,}$/', $formModelObject->getDisplayName() ) ) {
                $bindingResult->addFieldError( $displayNameFieldName, $displayNameFieldId,
                    /* translators: Create form validation error when the display name contains invalid characters */
                    __( 'The display name may contain any characters but it shouldn\'t start with a space.', 'wp-full-stripe-admin' ) );
            }

            $nameFieldName  = MM_WPFS_Admin_CreateFormViewConstants::FIELD_FORM_NAME;
            $nameFieldId    = MM_WPFS_Utils::generateFormElementId( $nameFieldName, MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_CREATE_FORM);

            if ( empty( $formModelObject->getName() )) {
                $bindingResult->addFieldError( $nameFieldName, $nameFieldId,
                    /* translators: Create form validation error when no identifier is specified  */
                    __( "Please enter an identifier", 'wp-full-stripe-admin' ) );
            } elseif ( ! preg_match('/^[\w\-]+$/', $formModelObject->getName() ) ) {
                $bindingResult->addFieldError( $nameFieldName, $nameFieldId,
                    /* translators: Create form validation error when the identifier contains invalid characters */
                    __( 'The identifier may contain only alphanumeric characters, dashes, and underscores.', 'wp-full-stripe-admin' ) );
            } elseif ( $this->isFormNameAlreadyUsed( $formModelObject ) ) {
                $bindingResult->addFieldError( $nameFieldName, $nameFieldId,
                    /* translators: Create form validation error when a form with the specified identifier already exists */
                    __( 'This identifier is already in use, choose a different one.', 'wp-full-stripe-admin' ) );
            }

            $formTypes = array(
                MM_WPFS::FORM_TYPE_PAYMENT,
                MM_WPFS::FORM_TYPE_SUBSCRIPTION,
                MM_WPFS::FORM_TYPE_DONATION,
                MM_WPFS::FORM_TYPE_SAVE_CARD );
            if ( false === array_search( $formModelObject->getType(), $formTypes ) ) {
                $error =
                    /* translators: Create form validation error message when no form type is selected */
                    __( 'Please select a form type', 'wp-full-stripe-admin' );

                $bindingResult->addGlobalError( $error );
            }

            $formLayouts = array(
                MM_WPFS::FORM_LAYOUT_INLINE,
                MM_WPFS::FORM_LAYOUT_CHECKOUT
            );
            if ( false === array_search( $formModelObject->getLayout(), $formLayouts ) ) {
                $error =
                    /* translators: Create form validation error message when no form layout is selected */
                    __( 'Please select a form layout', 'wp-full-stripe-admin' );

                $bindingResult->addGlobalError( $error );
            }
        }
    }

    /**
     * @param $formModelObject MM_WPFS_Admin_CreateFormModel
     *
     * @return boolean
     */
    private function isFormNameAlreadyUsed( $formModelObject ) {
        return count( $this->db->getFormIdsByName( $formModelObject->getName() )) > 0;
    }
}

class MM_WPFS_Admin_StripeAccountValidator extends MM_WPFS_Validator {
    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param MM_WPFS_Admin_StripeAccountModel $formModelObject
     */
    public function validate( $bindingResult, $formModelObject ) {

        if ( !$this->options->get( MM_WPFS_Options::OPTION_USE_WP_TEST_PLATFORM)) {
            $testSecretKeyFieldName  = MM_WPFS_Admin_ConfigureStripeAccountViewConstants::FIELD_API_TEST_SECRET_KEY;
            $testSecretKeyFieldId    = MM_WPFS_Utils::generateFormElementId( $testSecretKeyFieldName, MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_SAVE_STRIPE_ACCOUNT );
            if ( empty( $formModelObject->getTestSecretKey() )) {
                $bindingResult->addFieldError($testSecretKeyFieldName, $testSecretKeyFieldId,
                    /* translators: Save Stripe settings validation error when no test secret key specified */
                    __("Please enter a test secret key", 'wp-full-stripe-admin'));
            }
            $testPublishableKeyFieldName  = MM_WPFS_Admin_ConfigureStripeAccountViewConstants::FIELD_API_TEST_PUBLISHABLE_KEY;
            $testPublishableKeyFieldId    = MM_WPFS_Utils::generateFormElementId( $testPublishableKeyFieldName, MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_SAVE_STRIPE_ACCOUNT );
            if ( empty( $formModelObject->getTestPublishableKey() )) {
                $bindingResult->addFieldError($testPublishableKeyFieldName, $testPublishableKeyFieldId,
                    /* translators: Save Stripe settings validation error when no test publishable key specified */
                    __("Please enter a test publishable key", 'wp-full-stripe-admin'));
            }
        }

        if (!$this->options->get(MM_WPFS_Options::OPTION_USE_WP_LIVE_PLATFORM)) {
            $liveSecretKeyFieldName  = MM_WPFS_Admin_ConfigureStripeAccountViewConstants::FIELD_API_LIVE_SECRET_KEY;
            $liveSecretKeyFieldId    = MM_WPFS_Utils::generateFormElementId( $liveSecretKeyFieldName, MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_SAVE_STRIPE_ACCOUNT );
            if ( empty( $formModelObject->getLiveSecretKey() )) {
                $bindingResult->addFieldError($liveSecretKeyFieldName, $liveSecretKeyFieldId,
                    /* translators: Save Stripe settings validation error when no live secret key specified */
                    __("Please enter a live secret key", 'wp-full-stripe-admin'));
            }
            $livePublishableKeyFieldName  = MM_WPFS_Admin_ConfigureStripeAccountViewConstants::FIELD_API_LIVE_PUBLISHABLE_KEY;
            $livePublishableKeyFieldId    = MM_WPFS_Utils::generateFormElementId( $livePublishableKeyFieldName, MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_SAVE_STRIPE_ACCOUNT );
            if ( empty( $formModelObject->getLivePublishableKey() )) {
                $bindingResult->addFieldError($livePublishableKeyFieldName, $livePublishableKeyFieldId,
                    /* translators: Save Stripe settings validation error when no live publishable key specified */
                    __("Please enter a live publishable key", 'wp-full-stripe-admin'));
            }
        }


        $apiModes = array(
            MM_WPFS::STRIPE_API_MODE_TEST,
            MM_WPFS::STRIPE_API_MODE_LIVE
        );
        if ( false === array_search( $formModelObject->getApiMode(), $apiModes ) ) {
            $error =
                /* translators: Save Stripe settings validation error when no API mode is selected */
                __( 'Please select an API mode', 'wp-full-stripe-admin' );

            $bindingResult->addGlobalError( $error );
        }
    }
}

class MM_WPFS_Admin_MyAccountValidator extends MM_WPFS_Validator {
    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param MM_WPFS_Admin_MyAccountModel $formModelObject
     */
    public function validate( $bindingResult, $formModelObject ) {
        $yesNoValues     = array( '0', '1' );
        $whenCancelValues = array(
            MM_WPFS::CANCEL_SUBSCRIPTION_IMMEDIATELY,
            MM_WPFS::CANCEL_SUBSCRIPTION_AT_PERIOD_END
        );

        if ( false === array_search( $formModelObject->getShowSubscriptions(), $yesNoValues ) ) {
            $error =
                /* translators: Validation error message displayed if it's not selected whether subscriptions should be shown to customers */
                __( 'Please select if customers can see their subscriptions', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }

        if ( false === array_search( $formModelObject->getCancelSubscriptions(), $yesNoValues ) ) {
            $error =
                /* translators: Validation error message displayed if it's not selected whether customers can cancel subscriptions */
                __( 'Please select if customers can cancel subscriptions', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }

        if ( false === array_search( $formModelObject->getWhenCancelSubscriptions(), $whenCancelValues ) ) {
            $error =
                /* translators: Validation error message displayed if it's not selected when subscriptions are canceled (immediately, or at the end of the term) */
                __( 'Please select when to cancel subscriptions', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }

        if ( false === array_search( $formModelObject->getUpdowngradeSubscriptions(), $yesNoValues ) ) {
            $error =
                /* translators: Validation error message displayed if it's not selected whether customers can upgrade/downgrade subscriptions */
                __( 'Please select if customers can upgrade/downgrade subscriptions', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }

        if ( false === array_search( $formModelObject->getShowInvoices(), $yesNoValues ) ) {
            $error =
                /* translators: Validation error message displayed if it's not selected whether customers can view invoices */
                __( 'Please select if customers can view invoices', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }

        if ( false === array_search( $formModelObject->getScrollingPaneIntoView(), $yesNoValues ) ) {
            $error =
                /* translators: Validation error message displayed if it's not selected whether scrolling pane into view is enabled or disabled */
                __( 'Please select if pane should scroll into view', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }
    }
}

class MM_WPFS_Admin_SecurityValidator extends MM_WPFS_Validator {
    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param MM_WPFS_Admin_SecurityModel $formModelObject
     */
    public function validate( $bindingResult, $formModelObject ) {
        $yesNoValues     = array( '0', '1' );

        if ( false === array_search( $formModelObject->getSecureInlineForms(), $yesNoValues ) ) {
            $error =
                /* translators: Validation error message displayed if it's not selected whether inline forms are secured with Google reCaptcha */
                __( 'Please select whether inline forms should be secured with Google reCAPTCHA', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }

        if ( false === array_search( $formModelObject->getSecureCheckoutForms(), $yesNoValues ) ) {
            $error =
                /* translators: Validation error message displayed if it's not selected whether checkout forms are secured with Google reCaptcha */
                __( 'Please select whether checkout forms should be secured with Google reCAPTCHA', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }

        if ( false === array_search( $formModelObject->getSecureCustomerPortal(), $yesNoValues ) ) {
            $error =
                /* translators: Validation error message displayed if it's not selected whether the Customer Portal is secured with Google reCaptcha */
                __( 'Please select whether the Customer Portal should be secured with Google reCAPTCHA', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }

        $validateRecaptchaKeys = $formModelObject->getSecureInlineForms() == '1' ||
            $formModelObject->getSecureCheckoutForms() == '1' ||
            $formModelObject->getSecureCustomerPortal() == '1';

        if ( $validateRecaptchaKeys ) {
            $reCaptchaSecretKeyFieldName  = MM_WPFS_Admin_SecurityViewConstants::FIELD_SECURITY_RECAPTCHA_SECRET_KEY;
            $reCaptchaSecretKeyFieldId    = MM_WPFS_Utils::generateFormElementId( $reCaptchaSecretKeyFieldName, MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_SECURITY );
            if ( empty( $formModelObject->getReCaptchaSecretKey() )) {
                $bindingResult->addFieldError( $reCaptchaSecretKeyFieldName, $reCaptchaSecretKeyFieldId,
                    /* translators: Save security settings validation error when no Google reCaptcha secret key specified */
                    "Please enter a secret key" );
            }

            $reCaptchaSiteKeyFieldName  = MM_WPFS_Admin_SecurityViewConstants::FIELD_SECURITY_RECAPTCHA_SITE_KEY;
            $reCaptchaSiteKeyFieldId    = MM_WPFS_Utils::generateFormElementId( $reCaptchaSiteKeyFieldName, MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_SECURITY );
            if ( empty( $formModelObject->getReCaptchaSiteKey() )) {
                $bindingResult->addFieldError( $reCaptchaSiteKeyFieldName, $reCaptchaSiteKeyFieldId,
                    /* translators: Save security settings validation error when no Google reCaptcha site key specified */
                    "Please enter a site key" );
            }
        }
    }
}

class MM_WPFS_Admin_EmailOptionsValidator extends MM_WPFS_Validator {
    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param MM_WPFS_Admin_EmailOptionsModel $formModelObject
     */
    public function validate( $bindingResult, $formModelObject ) {
        $fromAddressValues = array(
            MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_VALUE_FROM_ADDRESS_ADMIN,
            MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_VALUE_FROM_ADDRESS_CUSTOM
        );

        if ( false === array_search( $formModelObject->getFromAddress(), $fromAddressValues ) ) {
            $error =
                /* translators: Validation error message displayed if it's not selected whether the "From" address of emails sent by the plugin should be the site admin or somebody else */
                __( 'Please select whether the From address should be the site admin or somebody else.', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }

        if ( $formModelObject->getFromAddress() === MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_VALUE_FROM_ADDRESS_CUSTOM ) {
            $fromAddressCustomFieldName  = MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_EMAIL_OPTIONS_FROM_ADDRESS_CUSTOM;
            $fromAddressCustomFieldId    = MM_WPFS_Utils::generateFormElementId( $fromAddressCustomFieldName, MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_EMAIL_OPTIONS );

            if ( empty( $formModelObject->getFromAddressCustom() )) {
                $bindingResult->addFieldError( $fromAddressCustomFieldName, $fromAddressCustomFieldId,
                    /* translators: Email options error when no custom email sender address is provided */
                    __( 'Please enter a sender address', 'wp-full-stripe-admin' ) );
            } elseif ( !filter_var( $formModelObject->getFromAddressCustom(), FILTER_VALIDATE_EMAIL) ) {
                $bindingResult->addFieldError( $fromAddressCustomFieldName, $fromAddressCustomFieldId,
                    /* translators: Email options error when no custom email sender address is provided */
                    __( 'Please enter a valid sender address', 'wp-full-stripe-admin' ) );
            }
        }

        if ( !empty( $formModelObject->getSendCopyToAdmin() ) &&
            !filter_var( $formModelObject->getSendCopyToAdmin(), FILTER_VALIDATE_EMAIL ) ) {
            $copyToAdminFieldName  = MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_EMAIL_OPTIONS_SEND_COPY_TO_ADMIN;
            $copyToAdminFieldId    = MM_WPFS_Utils::generateFormElementId( $copyToAdminFieldName, MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_EMAIL_OPTIONS );

            $bindingResult->addFieldError( $copyToAdminFieldName, $copyToAdminFieldId,
                /* translators: Email options error when no custom email sender address is provided
                 * p1: The invalid email address
                 */
                sprintf( __( "The email address '%s' is invalid", 'wp-full-stripe-admin' ), $formModelObject->getSendCopyToAdmin() ) );
        }

        foreach( $formModelObject->getSendCopyToList() as $email ) {
            if ( !filter_var( $email, FILTER_VALIDATE_EMAIL) ) {
                $copyToListFieldName  = MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_EMAIL_OPTIONS_SEND_COPY_TO_LIST;
                $copyToListFieldId    = MM_WPFS_Utils::generateFormElementId( $copyToListFieldName, MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_EMAIL_OPTIONS );

                $bindingResult->addFieldError( $copyToListFieldName, $copyToListFieldId,
                    sprintf(
                    /* translators: Email options error when an invalid email sender address is provided */
                        __( "The email address '%s' is invalid", 'wp-full-stripe-admin' ), $email ) );

                break;
            }
        }
    }
}

class MM_WPFS_Admin_FormsOptionsValidator extends MM_WPFS_Validator {
    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param MM_WPFS_Admin_FormsOptionsModel $formModelObject
     */
    public function validate( $bindingResult, $formModelObject ) {
        $yesNoValues = array( '0', '1' );

        if ( false === array_search( $formModelObject->getFillInEmail(), $yesNoValues ) ) {
            $error =
                /* translators: Validation error message when it's not specified whether the email address field on forms should be filled in for logged in users */
                __( 'Please select whether the email address should be filled in for logged in user', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }

        if ( false === array_search( $formModelObject->getSetFormFieldsViaUrlParameters(), $yesNoValues ) ) {
            $error =
                /* translators: Validation error message when it's not specified whether fields forms can be set via URL parameters */
                __( 'Please select whether form fields can be set via URL parameters', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }
    }
}

class MM_WPFS_Admin_FormsAppearanceValidator extends MM_WPFS_Validator {
    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param MM_WPFS_Admin_FormsAppearanceModel $formModelObject
     */
    public function validate( $bindingResult, $formModelObject ) {
    }
}

class MM_WPFS_Admin_WordpressDashboardValidator extends MM_WPFS_Validator {
    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param MM_WPFS_Admin_WordpressDashboardModel $formModelObject
     */
    public function validate( $bindingResult, $formModelObject ) {
        $yesNoValues            = array( '0', '1' );
        $decimalSeparatorValues = array(
            MM_WPFS::DECIMAL_SEPARATOR_SYMBOL_DOT,
            MM_WPFS::DECIMAL_SEPARATOR_SYMBOL_COMMA
        );

        if ( false === array_search( $formModelObject->getDecimalSeparator(), $decimalSeparatorValues ) ) {
            $error =
                /* translators: Validation error message when the decimal separator character of numbers is not selected */
                __( 'Please select the decimal separator', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }

        if ( false === array_search( $formModelObject->getUseSymbolNotCode(), $yesNoValues ) ) {
            $error =
                /* translators: Validation error message when it is not selected whether currency symbols or currecny codes should be displayed */
                __( 'Please select whether currency symbols or codes should be displayed', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }

        if ( false === array_search( $formModelObject->getCurrencySymbolAtFirstPosition(), $yesNoValues ) ) {
            $error =
                /* translators: Validation error message when it is not selected whether currency symbols should be displayed on the left or right */
                __( 'Please select whether the currency symbol should be displayed on the left or right ', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }

        if ( false === array_search( $formModelObject->getPutSpaceBetweenSymbolAndAmount(), $yesNoValues ) ) {
            $error =
                __( 'Please select whether space should be inserted between the currency symbol and amount', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }
    }
}

class MM_WPFS_Admin_LogsValidator extends MM_WPFS_Validator {
    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param MM_WPFS_Admin_LogsModel $formModelObject
     */
    public function validate( $bindingResult, $formModelObject ) {
        $yesNoValues            = array( '0', '1' );
        $logLevelValues = array(
            MM_WPFS_LoggerService::LEVEL_ERROR,
            MM_WPFS_LoggerService::LEVEL_WARNING,
            MM_WPFS_LoggerService::LEVEL_INFO,
            MM_WPFS_LoggerService::LEVEL_DEBUG
        );

        if ( false === array_search( $formModelObject->getLogLevel(), $logLevelValues ) ) {
            $error =
                /* translators: Validation error message when the log level is not selected */
                __( 'Please select the logging level', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }

        if ( false === array_search( $formModelObject->getLogToWebServer(), $yesNoValues ) ) {
            $error =
                /* translators: Validation error message when it is not selected whether the plugin should log to the webserver error log */
                __( 'Please select whether the plugin should log to the webserver error log', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }

        if ( false === array_search( $formModelObject->getCatchUncaughtErrors(), $yesNoValues ) ) {
            $error =
                /* translators: Validation error message when it is not selected whether the plugin should catch all uncaught errors */
                __( 'Please select whether the plugin should catch all uncaught errors', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }
    }
}


abstract class MM_WPFS_Admin_FormValidator extends MM_WPFS_Validator {

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_FormModel
     */
    protected function validateTermsOfUse($bindingResult, $formModel ) {
        if ( $formModel->getShowTermsOfUse() == 1 ) {
            $labelFieldName = MM_WPFS_Admin_FormView::FIELD_FORM_TERMS_OF_SERVICE_LABEL;
            $labelFieldId   = MM_WPFS_Utils::generateFormElementId( $labelFieldName, $formModel->getFormHash() );
            if ( empty( $formModel->getTermsOfUseLabel() )) {
                $error     = __( 'Please enter a label for the terms of service input field', 'wp-full-stripe-admin' );
                $bindingResult->addFieldError( $labelFieldName, $labelFieldId, $error );
            }

            $errorFieldName = MM_WPFS_Admin_FormView::FIELD_FORM_TERMS_OF_SERVICE_ERROR;
            $errorFieldId   = MM_WPFS_Utils::generateFormElementId( $errorFieldName, $formModel->getFormHash() );
            if ( empty( $formModel->getTermsOfUseErrorMessage() )) {
                $error     = __( "Please enter an error message that's displayed when the terms of service field is empty", 'wp-full-stripe-admin' );
                $bindingResult->addFieldError( $errorFieldName, $errorFieldId, $error );
            }
        }
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_FormModel
     */
    protected function validateLocaleSettings($bindingResult, $formModel ) {
        $yesNoValues            = array( '0', '1' );
        $decimalSeparatorValues = array(
            MM_WPFS::DECIMAL_SEPARATOR_SYMBOL_DOT,
            MM_WPFS::DECIMAL_SEPARATOR_SYMBOL_COMMA
        );

        if ( false === array_search( $formModel->getLocaleDecimalSeparator(), $decimalSeparatorValues ) ) {
            $error =
                __( 'Please select the decimal separator (General tab)', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }

        if ( false === array_search( $formModel->getLocaleUseSymbolNotCode(), $yesNoValues ) ) {
            $error =
                __( 'Please select whether currency symbols or codes should be displayed (General tab)', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }

        if ( false === array_search( $formModel->getLocaleCurrencySymbolAtFirstPosition(), $yesNoValues ) ) {
            $error =
                __( 'Please select whether the currency symbol should be displayed on the left or right (General tab)', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }

        if ( false === array_search( $formModel->getLocalePutSpaceBetweenSymbolAndAmount(), $yesNoValues ) ) {
            $error =
                __( 'Please select whether space should be inserted between the currency symbol and amount (General tab)', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_FormModel
     */
    protected function validateButtonLabel( $bindingResult, $formModel ) {
        $fieldName = MM_WPFS_Admin_FormView::FIELD_FORM_BUTTON_LABEL;
        $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModel->getFormHash() );

        if ( empty( $formModel->getButtonLabel() )) {
            /* translators: Validation error message when no form submit button label is entered */
            $error     = __( 'Please enter a button label', 'wp-full-stripe-admin' );
            $bindingResult->addFieldError( $fieldName, $fieldId, $error );
        } elseif ( empty( preg_replace('/\s+/', '', $formModel->getButtonLabel())) ) {
            $bindingResult->addFieldError( $fieldName, $fieldId,
                /* translators: Validation error message when the submit button label doesn't contain at least one non-whitespace character */
                __( 'The button label should contain at least one character other than whitespace', 'wp-full-stripe-admin' ));
        }
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_FormModel
     */
    protected function validateNameAndDisplayName( $bindingResult, $formModel ) {
        $displayNameFieldName = MM_WPFS_Admin_FormView::FIELD_FORM_DISPLAY_NAME;
        $displayNameFieldId   = MM_WPFS_Utils::generateFormElementId( $displayNameFieldName, $formModel->getFormHash() );
        if ( empty( $formModel->getDisplayName() )) {
            /* translators: Validation error message when the display name of the form is empty */
            $error     = __( 'Please enter a display name', 'wp-full-stripe-admin' );
            $bindingResult->addFieldError( $displayNameFieldName, $displayNameFieldId, $error );
        } elseif ( ! preg_match('/^[^\s]{1,}.{0,}$/', $formModel->getDisplayName() ) ) {
            $bindingResult->addFieldError( $displayNameFieldName, $displayNameFieldId,
                /* translators: Validation error message when the display name starts with a space */
                __( 'The display name may contain any characters but it shouldn\'t start with a space.', 'wp-full-stripe-admin' ) );
        }

        $nameFieldName = MM_WPFS_Admin_FormView::FIELD_FORM_NAME;
        $nameFieldId   = MM_WPFS_Utils::generateFormElementId( $nameFieldName, $formModel->getFormHash() );
        if ( empty( $formModel->getName() )) {
            /* translators: Validation error message when the identifier of the form is empty */
            $error     = __( 'Please enter the form identifier', 'wp-full-stripe-admin' );
            $bindingResult->addFieldError( $nameFieldName, $nameFieldId, $error );
        } elseif ( ! preg_match('/^[\w\-]+$/', $formModel->getName() ) ) {
            $bindingResult->addFieldError( $nameFieldName, $nameFieldId,
                /* translators: Validation error message when the identifier of the form contains invalid characters */
                __( 'The identifier may contain only alphanumeric characters, dashes, and underscores.', 'wp-full-stripe-admin' ) );
        }
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_FormModel
     */
    protected function validateRedirect($bindingResult, $formModel ) {
        $redirectValues = array(
            MM_WPFS::REDIRECT_TYPE_SHOW_CONFIRMATION_MESSAGE,
            MM_WPFS::REDIRECT_TYPE_TO_PAGE_OR_POST,
            MM_WPFS::REDIRECT_TYPE_TO_CUSTOM_URL,
        );
        if ( false === array_search( $formModel->getRedirectType(), $redirectValues ) ) {
            $error =
                __( 'Please decide what should happen after a successful payment (General tab)', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }

        if ( $formModel->getRedirectType() === MM_WPFS::REDIRECT_TYPE_TO_CUSTOM_URL ) {
            $redirectFieldName = MM_WPFS_Admin_FormView::FIELD_FORM_REDIRECT_CUSTOM_URL;
            $redirectFieldId   = MM_WPFS_Utils::generateFormElementId( $redirectFieldName, $formModel->getFormHash() );

            if ( empty( $formModel->getRedirectURl() )) {
                $error     = __( 'Please enter an URL', 'wp-full-stripe-admin' );
                $bindingResult->addFieldError( $redirectFieldName, $redirectFieldId, $error );
            } elseif ( filter_var( $formModel->getRedirectURl(), FILTER_VALIDATE_URL) === false) {
                $bindingResult->addFieldError( $redirectFieldName, $redirectFieldId,
                    __( 'Please enter a valid URL', 'wp-full-stripe-admin' ));
            }
        }
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param MM_WPFS_Binder $formModel
     */
    public function validate( $bindingResult, $formModel ) {
        $this->validateNameAndDisplayName( $bindingResult, $formModel );
        $this->validateRedirect( $bindingResult, $formModel );
        $this->validateButtonLabel( $bindingResult, $formModel );
        $this->validateLocaleSettings( $bindingResult, $formModel );
        $this->validateTermsOfUse( $bindingResult, $formModel );
    }
}

trait MM_WPFS_Admin_InlineFormValidator {

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_InlineFormModel
     */
    protected function validateCardInputFieldLanguage( $bindingResult, $formModel ) {
        if ( $formModel->getCardInputFieldLanguage() !== MM_WPFS::PREFERRED_LANGUAGE_AUTO ) {
            if ( false === array_search( $formModel->getCardInputFieldLanguage(), MM_WPFS_Languages::getStripeElementsLanguageCodes()) ) {
                $error =
                    __( 'Please select the card input field language (Appearance tab)', 'wp-full-stripe-admin' );
                $bindingResult->addGlobalError( $error );
            }
        }
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param MM_WPFS_Binder $formModel
     */
    protected function validateInlineFields( $bindingResult, $formModel ) {
        $this->validateCardInputFieldLanguage( $bindingResult, $formModel );
    }
}


trait MM_WPFS_Admin_CheckoutFormValidator {

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_CheckoutFormModel
     */
    protected function validateOpenButtonLabel( $bindingResult, $formModel ) {
        $fieldName = MM_WPFS_Admin_CheckoutFormViewConstants::FIELD_FORM_OPEN_BUTTON_LABEL;
        $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModel->getFormHash() );

        if ( empty( $formModel->getOpenButtonLabel() )) {
            $error     =
                /* translators: Validation error message when the form open button label is empty */
                __( 'Please enter a button label', 'wp-full-stripe-admin' );
            $bindingResult->addFieldError( $fieldName, $fieldId, $error );
        } elseif ( empty( preg_replace('/\s+/', '', $formModel->getOpenButtonLabel())) ) {
            $error  =
                /* translators: Validation error message when the form open button label doesn't contain at least one character other than whitespace */
                __( 'The button label should contain at least one character other than whitespace', 'wp-full-stripe-admin' );
            $bindingResult->addFieldError( $fieldName, $fieldId, $error );
        }
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_CheckoutFormModel
     */
    protected function validateCheckoutLanguage( $bindingResult, $formModel ) {
        if ( $formModel->getCheckoutFormLanguage() !== MM_WPFS::PREFERRED_LANGUAGE_AUTO ) {
            if ( false === array_search( $formModel->getCheckoutFormLanguage(), MM_WPFS_Languages::getCheckoutLanguageCodes()) ) {
                $error =
                    __( 'Please select the checkout form language (Appearance tab)', 'wp-full-stripe-admin' );
                $bindingResult->addGlobalError( $error );
            }
        }
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param MM_WPFS_Binder $formModel
     */
    protected function validateCheckoutFields( $bindingResult, $formModel ) {
        $this->validateOpenButtonLabel( $bindingResult, $formModel );
        $this->validateCheckoutLanguage( $bindingResult, $formModel );
    }
}

abstract class MM_WPFS_Admin_SaveCardFormValidator extends MM_WPFS_Admin_FormValidator {

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    public function validate( $bindingResult, $formModel ) {
        parent::validate( $bindingResult, $formModel );
    }
}

class MM_WPFS_Admin_InlineSaveCardFormValidator extends MM_WPFS_Admin_SaveCardFormValidator {
    use MM_WPFS_Admin_InlineFormValidator;

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    public function validate($bindingResult, $formModel) {
        parent::validate( $bindingResult, $formModel );
        $this->validateInlineFields( $bindingResult, $formModel );
    }
}

class MM_WPFS_Admin_CheckoutSaveCardFormValidator extends MM_WPFS_Admin_SaveCardFormValidator {
    use MM_WPFS_Admin_CheckoutFormValidator;

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    public function validate($bindingResult, $formModel) {
        parent::validate( $bindingResult, $formModel );
        $this->validateCheckoutFields( $bindingResult, $formModel );
    }
}

abstract class MM_WPFS_Admin_DonationFormValidator extends MM_WPFS_Admin_FormValidator {

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_DonationFormModel
     */
    protected function validateDonationAmounts( $bindingResult, $formModel ) {
        if ( count( $formModel->getDonationAmounts() ) === 0 &&
            $formModel->getAllowCustomDonationAmount() == 0 ) {
            $fieldName = MM_WPFS_Admin_DonationFormViewConstants::FIELD_FORM_ALLOW_CUSTOM_DONATION_AMOUNT;
            $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModel->getFormHash() );
            /* translators: Donations forms can have a custom amount field and/or suggested amount buttons. Having none of them is an issue */
            $error     = __( 'Please add suggested donation amounts or allow entering a custom donation amount', 'wp-full-stripe-admin' );

            $bindingResult->addFieldError( $fieldName, $fieldId, $error );
        }
    }

    /**
     * @param $formModel MM_WPFS_Admin_DonationFormModel
     * @return bool
     */
    protected function isMinimumAmountValidationRequired( $formModel ) {
        return $formModel->getMinimumDonationAmount() != 0 &&
            $formModel->getAllowCustomDonationAmount() == 1;
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_DonationFormModel
     */
    protected function validateMinimumDonationAmount( $bindingResult, $formModel ) {
        if ( ! $this->isMinimumAmountValidationRequired( $formModel )) {
            return;
        }

        if ( count( $formModel->getDonationAmounts() ) > 0 &&
            min( $formModel->getDonationAmounts() ) < $formModel->getMinimumDonationAmount() ) {
            $fieldName = MM_WPFS_Admin_DonationFormViewConstants::FIELD_FORM_MINIMUM_DONATION_AMOUNT;
            $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModel->getFormHash() );

            $suggestedAmount = MM_WPFS_Currencies::formatAndEscapeByAdmin( $this->staticContext, $formModel->getCurrency(), min( $formModel->getDonationAmounts() ), false, true );
            $minimumAmount = MM_WPFS_Currencies::formatAndEscapeByAdmin( $this->staticContext, $formModel->getCurrency(), $formModel->getMinimumDonationAmount(), false, true );
            $error = sprintf(
            /* translators: This error message is displayed when the donation form's minimum donation amount is larger than the lowest suggested donation amount */
                __( 'The suggested donation amount of %1$s is lower than the minimum donation amount of %2$s.', 'wp-full-stripe-admin' ), $suggestedAmount, $minimumAmount  );
            $bindingResult->addFieldError( $fieldName, $fieldId, $error );
        }
    }

    /**
     * @param $formModel MM_WPFS_Admin_DonationFormModel
     *
     * @returns boolean
     */
    protected function isDonationFrequencySelected( $formModel ) {
        return  1 == $formModel->getDonationFrequencyOnetime() ||
            1 == $formModel->getDonationFrequencyDaily() ||
            1 == $formModel->getDonationFrequencyWeekly() ||
            1 == $formModel->getDonationFrequencyMonthly() ||
            1 == $formModel->getDonationFrequencyAnnual();
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_DonationFormModel
     */
    protected function validateDonationFrequencies( $bindingResult, $formModel ) {
        if ( ! $this->isDonationFrequencySelected( $formModel ) ) {
            $fieldName = MM_WPFS_Admin_DonationFormViewConstants::FIELD_FORM_DONATION_FREQUENCIES;
            $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModel->getFormHash() );
            /* translators: Validation error message when no donation frequency selected such as one-time, daily, weekly, etc */
            $error     = __( 'Please select at least one donation frequency', 'wp-full-stripe-admin' );

            $bindingResult->addFieldError( $fieldName, $fieldId, $error );
        }
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_PaymentFormModel
     */
    protected function validateCurrency( $bindingResult, $formModel ) {
        $currencies = MM_WPFS_Currencies::getAvailableCurrencies();

        if ( false === array_search( $formModel->getCurrency(), array_keys( $currencies ) )) {
            $error =
                __( 'Please select the payment currency (Payment tab)', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }
    }

    public function validate( $bindingResult, $formModel ) {
        parent::validate( $bindingResult, $formModel );

        $this->validateCurrency( $bindingResult, $formModel );
        $this->validateDonationAmounts( $bindingResult, $formModel );
        $this->validateMinimumDonationAmount( $bindingResult, $formModel );
        $this->validateDonationFrequencies( $bindingResult, $formModel );
    }
}

class MM_WPFS_Admin_InlineDonationFormValidator extends MM_WPFS_Admin_DonationFormValidator {
    use MM_WPFS_Admin_InlineFormValidator;

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Admin_InlineDonationFormModel
     */
    protected function validateDefaultProductName( $bindingResult, $formModel ) {
        if ( empty( $formModel->getDefaultProductName() )) {
            $fieldName = MM_WPFS_Admin_InlineDonationFormViewConstants::FIELD_FORM_DEFAULT_PRODUCT_NAME;
            $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModel->getFormHash() );
            /* translators: Validation error message when no default donation product name is entered. */
            $error     = __( 'Please enter a donation product name', 'wp-full-stripe-admin' );
            $bindingResult->addFieldError( $fieldName, $fieldId, $error );
        }
    }

    protected function validateInlineDonationFields( $bindingResult, $formModel ) {
        $this->validateDefaultProductName( $bindingResult, $formModel );
    }

    public function validate($bindingResult, $formModel) {
        parent::validate( $bindingResult, $formModel );
        $this->validateInlineFields( $bindingResult, $formModel );
        $this->validateInlineDonationFields( $bindingResult, $formModel );
    }
}

trait MM_WPFS_Admin_CheckoutPhoneNumberValidator_AddOn {
    /**
     * @param $bindingResult
     * @param $formModelObject MM_WPFS_Binder|MM_WPFS_Admin_CheckoutPaymentFormModel|MM_WPFS_Admin_CheckoutSubscriptionFormModel|MM_WPFS_Admin_CheckoutDonationFormModel
     * @return void
     */
    protected function validateCheckoutCollectPhoneNumber( $bindingResult, $formModelObject ) {
        $yesNoValues     = array( '0', '1' );

        if ( false === array_search( $formModelObject->getCollectPhoneNumber(), $yesNoValues ) ) {
            $error =
                /* translators: Validation error message displayed if it's not selected whether the customer's phone number should be collected  */
                __( 'Please select if the customer\'s phone number should be collected', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }
    }
}

class MM_WPFS_Admin_CheckoutDonationFormValidator extends MM_WPFS_Admin_DonationFormValidator {
    use MM_WPFS_Admin_CheckoutFormValidator;
    use MM_WPFS_Admin_CheckoutPhoneNumberValidator_AddOn;

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    public function validate($bindingResult, $formModel) {
        parent::validate( $bindingResult, $formModel );
        $this->validateCheckoutFields( $bindingResult, $formModel );

        $this->validateCheckoutProductName( $bindingResult, $formModel );
        $this->validateCheckoutCollectPhoneNumber( $bindingResult, $formModel );
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_CheckoutDonationFormModel
     */
    protected function validateCheckoutProductName( $bindingResult, $formModel ) {
        if ( empty( $formModel->getCheckoutProductName() )) {
            $fieldName = MM_WPFS_Admin_CheckoutDonationFormView::FIELD_FORM_CHECKOUT_PRODUCT_NAME;
            $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModel->getFormHash() );
            /* translators: Validation error message when no donation product name is entered. The product name is displayed as line item of the donation form. */
            $error     = __( 'Please enter a donation product name', 'wp-full-stripe-admin' );
            $bindingResult->addFieldError( $fieldName, $fieldId, $error );
        }
    }
}

trait MM_WPFS_Admin_TaxRateValidatorTools {
    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_PaymentFormModel|MM_WPFS_Admin_SubscriptionFormModel
     */
    protected function isFormWithTaxRates( $formModel ) {
        return MM_WPFS::FIELD_VALUE_TAX_RATE_FIXED === $formModel->getTaxType() ||
            MM_WPFS::FIELD_VALUE_TAX_RATE_DYNAMIC === $formModel->getTaxType();
    }

    protected function validateTaxRates( $bindingResult, $formModel ) {
        $taxRateTypes = array(
            MM_WPFS::FIELD_VALUE_TAX_RATE_NO_TAX,
            MM_WPFS::FIELD_VALUE_TAX_RATE_STRIPE_TAX,
            MM_WPFS::FIELD_VALUE_TAX_RATE_FIXED,
            MM_WPFS::FIELD_VALUE_TAX_RATE_DYNAMIC,
        );

        if ( false === array_search( $formModel->getTaxType(), $taxRateTypes )) {
            $error =
                __( 'Please select a tax type (Tax tab)', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }

        if ( MM_WPFS::FIELD_VALUE_TAX_RATE_FIXED === $formModel->getTaxType() ) {
            if ( count( $formModel->getTaxRates() ) > 2 ) {
                $fieldName = MM_WPFS_Admin_View_TaxConstants::FIELD_FORM_TAX_RATES_ERROR;
                $fieldId   = MM_WPFS_Admin_View_TaxConstants::FIELD_FORM_TAX_RATES_ERROR;
                $error     = __( 'Please select at most two tax rates for the fixed rate calculation', 'wp-full-stripe-admin' );

                $bindingResult->addFieldError( $fieldName, $fieldId, $error );
            }
        }

        if ( $this->isFormWithTaxRates( $formModel ) && count( $formModel->getTaxRates() ) > 0 ) {
            $containsInclusive = false;
            $containsExclusive = false;

            foreach( $formModel->getTaxRates() as $taxRate ) {
                if ( $taxRate['inclusive'] ) {
                    $containsInclusive = true;
                } else {
                    $containsExclusive = true;
                }
            }

            if ( $containsInclusive && $containsExclusive ) {
                $fieldName = MM_WPFS_Admin_View_TaxConstants::FIELD_FORM_TAX_RATES_ERROR;
                $fieldId   = MM_WPFS_Admin_View_TaxConstants::FIELD_FORM_TAX_RATES_ERROR;
                $error     = __( 'Please don\'t mix inclusive and exclusive tax rates' , 'wp-full-stripe-admin' );

                $bindingResult->addFieldError( $fieldName, $fieldId, $error );
            }
        }
    }
}


abstract class MM_WPFS_Admin_PaymentFormValidator extends MM_WPFS_Admin_FormValidator {
    use MM_WPFS_Admin_TaxRateValidatorTools;

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_PaymentFormModel
     */
    protected function validateCurrency( $bindingResult, $formModel ) {
        $currencies = MM_WPFS_Currencies::getAvailableCurrencies();

        if ( false === array_search( $formModel->getCurrency(), array_keys( $currencies ) )) {
            $error =
                __( 'Please select the payment currency (Payment tab)', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_PaymentFormModel
     */
    protected function validatePaymentType( $bindingResult, $formModel ) {
        $paymentTypes = array(
            MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS,
            MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT
        );

        if ( false === array_search( $formModel->getPaymentType(), $paymentTypes )) {
            $error =
                __( 'Please select a payment type (Payment tab)', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }
    }

    /**
     * @param $formModel MM_WPFS_Admin_PaymentFormModel
     * @return int
     */
    protected function getMinimumProductAmount( $formModel ) {
        $result = PHP_INT_MAX;

        foreach( $formModel->getOnetimeProducts() as $product ) {
            if ( $product['price'] < $result ) {
                $result = $product['price'];
            }
        }

        return $result;
    }

    /**
     * @param $formModel MM_WPFS_Admin_PaymentFormModel
     * @return bool
     */
    protected function isMinimumAmountValidationRequired( $formModel ) {
        return   $formModel->getMinimumPaymentAmount() != 0 ||
            $formModel->getPaymentType() === MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT ||
            ( $formModel->getPaymentType() === MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS && $formModel->getAllowCustomPaymentAmount() == 1 );
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_PaymentFormModel
     */
    protected function validateMinimumPaymentAmount( $bindingResult, $formModel ) {
        if ( ! $this->isMinimumAmountValidationRequired( $formModel  )) {
            return;
        }

        if ( $formModel->getPaymentType() === MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS && $formModel->getAllowCustomPaymentAmount() == 1 ) {
            $minimumProductAmount = $this->getMinimumProductAmount( $formModel );

            if ( count( $formModel->getOnetimeProducts() ) > 0 && $minimumProductAmount <= $formModel->getMinimumPaymentAmount() ) {
                $fieldName = MM_WPFS_Admin_PaymentFormViewConstants::FIELD_FORM_MINIMUM_PAYMENT_AMOUNT;
                $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModel->getFormHash() );

                $productAmount = MM_WPFS_Currencies::formatAndEscapeByAdmin( $this->staticContext, $formModel->getCurrency(), $minimumProductAmount, false, true );
                $minimumAmount = MM_WPFS_Currencies::formatAndEscapeByAdmin( $this->staticContext, $formModel->getCurrency(), $formModel->getMinimumPaymentAmount(), false, true );
                $error = sprintf(
                /* translators: This error message is displayed when the payment form's minimum payment amount is larger than the lowest product price */
                    __( 'The product price of %1$s is lower than the minimum payment amount of %2$s.', 'wp-full-stripe-admin' ), $productAmount, $minimumAmount  );
                $bindingResult->addFieldError( $fieldName, $fieldId, $error );
            }
        }
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_PaymentFormModel
     */
    protected function validateChargeType( $bindingResult, $formModel ) {
        $chargeTypes = array(
            MM_WPFS::CHARGE_TYPE_IMMEDIATE,
            MM_WPFS::CHARGE_TYPE_AUTHORIZE_AND_CAPTURE
        );

        if ( false === array_search( $formModel->getChargeType(), $chargeTypes )) {
            $error =
                __( 'Please select a payment type (Payment tab)', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_PaymentFormModel
     */
    protected function validateProducts( $bindingResult, $formModel ) {
        if ( $formModel->getPaymentType() === MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS ) {
            if ( count( $formModel->getOnetimeProducts() ) === 0 ) {
                $fieldName = MM_WPFS_Admin_PaymentFormViewConstants::FIELD_FORM_ONETIME_PRODUCTS_ERROR;
                $fieldId   = MM_WPFS_Admin_PaymentFormViewConstants::FIELD_FORM_ONETIME_PRODUCTS_ERROR;
                $error     = __( 'Please select at least one product', 'wp-full-stripe-admin' );

                $bindingResult->addFieldError( $fieldName, $fieldId, $error );
            }
        }
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_PaymentFormModel
     */
    protected function validateProductSelectorStyle( $bindingResult, $formModel ) {
        $productSelectorStyles = array(
            MM_WPFS::SELECTOR_STYLE_RADIO_BUTTONS,
            MM_WPFS::SELECTOR_STYLE_DROPDOWN,
            MM_WPFS::SELECTOR_STYLE_BUTTON_GROUP,
        );

        if ( false === array_search( $formModel->getProductSelectorStyle(), $productSelectorStyles )) {
            $error =
                /* translators: Validation error message when no product selector style selected, such as radio button list, or dropdown */
                __( 'Please select a product selector style (Appearance tab)', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_PaymentFormModel
     */
    protected function validateShowCouponField( $bindingResult, $formModel ) {
        $yesNoValues = array( '0', '1' );

        if ( false === array_search( $formModel->getShowCouponField(), $yesNoValues ) ) {
            $error =
                __( 'Please select whether the coupon field should be displayed (Layout tab)', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_PaymentFormModel
     */
    protected function validateGenerateInvoice( $bindingResult, $formModel ) {
        $yesNoValues = array( '0', '1' );

        if ( false === array_search( $formModel->getGenerateInvoice(), $yesNoValues ) ) {
            $error =
                __( 'Please select whether invoice should be generated (Payment tab)', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }
    }

    public function validate( $bindingResult, $formModel ) {
        parent::validate( $bindingResult, $formModel );

        $this->validateCurrency( $bindingResult, $formModel );
        $this->validatePaymentType( $bindingResult, $formModel );
        $this->validateChargeType( $bindingResult, $formModel );
        $this->validateMinimumPaymentAmount( $bindingResult, $formModel );
        $this->validateGenerateInvoice( $bindingResult, $formModel );
        $this->validateProducts( $bindingResult, $formModel );
        $this->validateTaxRates( $bindingResult, $formModel );
        $this->validateProductSelectorStyle( $bindingResult, $formModel );
        $this->validateShowCouponField( $bindingResult, $formModel );
    }
}

class MM_WPFS_Admin_InlinePaymentFormValidator extends MM_WPFS_Admin_PaymentFormValidator {
    use MM_WPFS_Admin_InlineFormValidator;

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    public function validate($bindingResult, $formModel) {
        parent::validate( $bindingResult, $formModel );
        $this->validateInlineFields( $bindingResult, $formModel );
    }
}

class MM_WPFS_Admin_CheckoutPaymentFormValidator extends MM_WPFS_Admin_PaymentFormValidator {
    use MM_WPFS_Admin_CheckoutFormValidator;
    use MM_WPFS_Admin_CheckoutPhoneNumberValidator_AddOn;

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    public function validate($bindingResult, $formModel) {
        parent::validate( $bindingResult, $formModel );
        $this->validateCheckoutFields( $bindingResult, $formModel );

        $this->validateCheckoutPaymentFields( $bindingResult, $formModel );
        $this->validateCheckoutCollectPhoneNumber( $bindingResult, $formModel );
    }

    protected function validateCheckoutPaymentFields( $bindingResult, $formModel ) {
        $this->validateCheckoutProductName( $bindingResult, $formModel );
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_CheckoutPaymentFormModel
     */
    protected function validateCheckoutProductName( $bindingResult, $formModel ) {
        $isCustomAllowed = $formModel->getPaymentType() === MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT ||
            ( $formModel->getPaymentType() === MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS && $formModel->getAllowCustomPaymentAmount() == 1 );

        if ( $isCustomAllowed === true && empty( $formModel->getCheckoutProductName() )) {
            $fieldName = MM_WPFS_Admin_CheckoutPaymentFormViewConstants::FIELD_FORM_CHECKOUT_PRODUCT_NAME;
            $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModel->getFormHash() );
            /* translators: Validation error message when no name specified for the custom product. When a custom amount is entered on the form, the custom product name will be the name of the line item */
            $error     = __( 'Please enter a custom product name', 'wp-full-stripe-admin' );
            $bindingResult->addFieldError( $fieldName, $fieldId, $error );
        }
    }
}

abstract class MM_WPFS_Admin_SubscriptionFormValidator extends MM_WPFS_Admin_FormValidator {
    use MM_WPFS_Admin_TaxRateValidatorTools;

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    public function validate( $bindingResult, $formModel ) {
        parent::validate( $bindingResult, $formModel );

        $this->validateSubscriptionFields( $bindingResult, $formModel );
    }

    protected function validateSubscriptionFields( $bindingResult, $formModel ) {
        $this->validatePlans( $bindingResult, $formModel );
        $this->validateTaxRates( $bindingResult, $formModel );
        $this->validateMinMaxSubscriptionQuantity( $bindingResult, $formModel );
        $this->validatePlanSelectorStyle( $bindingResult, $formModel );
        $this->validateShowCouponField( $bindingResult, $formModel );
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_SubscriptionFormModel
     */
    protected function validatePlans( $bindingResult, $formModel ) {
        if ( count( $formModel->getRecurringProducts() ) === 0 ) {
            $fieldName = MM_WPFS_Admin_SubscriptionFormViewConstants::FIELD_FORM_RECURRING_PRODUCTS_ERROR;
            $fieldId   = MM_WPFS_Admin_SubscriptionFormViewConstants::FIELD_FORM_RECURRING_PRODUCTS_ERROR;
            /* translators: Validation error message when no subscription plan is selected on a subscription form */
            $error     = __( 'Please select at least one plan', 'wp-full-stripe-admin' );

            $bindingResult->addFieldError( $fieldName, $fieldId, $error );
        }
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_SubscriptionFormModel
     */
    protected function validateMinMaxSubscriptionQuantity($bindingResult, $formModel ) {
        if ( $formModel->getAllowSubscriptionQuantity() == 1 ) {
            $filterOptions = array(
                'options' => array(
                    'min_range' => 0
                )
            );
            if ( filter_var( $formModel->getSubscriptionMinimumQuantity(), FILTER_VALIDATE_INT, $filterOptions ) === false ) {
                $fieldName = MM_WPFS_Admin_SubscriptionFormViewConstants::FIELD_FORM_SUBSCRIPTION_MINIMUM_QUANTITY;
                $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModel->getFormHash() );
                /* translators: Validation error message when subscriptions can be purchased in bulk but no minimum quantity is entered */
                $error     = __( 'Please enter the minimum subscription quantity or zero', 'wp-full-stripe' );
                $bindingResult->addFieldError( $fieldName, $fieldId, $error );
            }
            $filterOptions = array(
                'options' => array(
                    'min_range' => 0
                )
            );
            if ( filter_var( $formModel->getSubscriptionMaximumQuantity(), FILTER_VALIDATE_INT, $filterOptions ) === false ) {
                $fieldName = MM_WPFS_Admin_SubscriptionFormViewConstants::FIELD_FORM_SUBSCRIPTION_MAXIMUM_QUANTITY;
                $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModel->getFormHash() );
                /* translators: Validation error message when subscriptions can be purchased in bulk but no maximum quantity is entered */
                $error     = __( 'Please enter the maximum subscription quantity or zero', 'wp-full-stripe-admin' );
                $bindingResult->addFieldError( $fieldName, $fieldId, $error );
            }
            if ( ! $bindingResult->hasErrors() ) {
                if ( $formModel->getSubscriptionMinimumQuantity() > 0 && $formModel->getSubscriptionMaximumQuantity() > 0 ) {
                    if ($formModel->getSubscriptionMinimumQuantity() > $formModel->getSubscriptionMaximumQuantity()) {
                        $fieldName = MM_WPFS_Admin_SubscriptionFormViewConstants::FIELD_FORM_SUBSCRIPTION_MINIMUM_QUANTITY;
                        $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModel->getFormHash() );
                        /* translators: Validation error message when subscriptions can be purchased in bulk but maximum quantity is less than maximum quantity */
                        $error     = __( 'Please enter a lower value for minimum subscription quantity', 'wp-full-stripe-admin' );
                        $bindingResult->addFieldError( $fieldName, $fieldId, $error );
                    }
                }
            }
        }
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_SubscriptionFormModel
     */
    protected function validatePlanSelectorStyle( $bindingResult, $formModel ) {
        $productSelectorStyles = array(
            MM_WPFS::SELECTOR_STYLE_RADIO_BUTTONS,
            MM_WPFS::SELECTOR_STYLE_DROPDOWN,
        );

        if ( false === array_search( $formModel->getProductSelectorStyle(), $productSelectorStyles )) {
            $error =
                /* translators: Validation error message when no plan selector style selected, such as radio button list, dropdown, etc */
                __( 'Please select a plan selector style (Appearance tab)', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }
    }

    /**
     * @param MM_WPFS_BindingResult $bindingResult
     * @param $formModel MM_WPFS_Binder|MM_WPFS_Admin_SubscriptionFormModel
     */
    protected function validateShowCouponField( $bindingResult, $formModel ) {
        $yesNoValues = array( '0', '1' );

        if ( false === array_search( $formModel->getShowCouponField(), $yesNoValues ) ) {
            $error =
                __( 'Please select whether the coupon field should be displayed (Layout tab)', 'wp-full-stripe-admin' );
            $bindingResult->addGlobalError( $error );
        }
    }
}

class MM_WPFS_Admin_InlineSubscriptionFormValidator extends MM_WPFS_Admin_SubscriptionFormValidator {
    use MM_WPFS_Admin_InlineFormValidator;

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    public function validate($bindingResult, $formModel) {
        parent::validate( $bindingResult, $formModel );
        $this->validateInlineFields( $bindingResult, $formModel );
    }
}

class MM_WPFS_Admin_CheckoutSubscriptionFormValidator extends MM_WPFS_Admin_SubscriptionFormValidator {
    use MM_WPFS_Admin_CheckoutFormValidator;
    use MM_WPFS_Admin_CheckoutPhoneNumberValidator_AddOn;


    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    public function validate($bindingResult, $formModel) {
        parent::validate( $bindingResult, $formModel );
        $this->validateCheckoutFields( $bindingResult, $formModel );

        $this->validateCheckoutCollectPhoneNumber( $bindingResult, $formModel );
    }
}
