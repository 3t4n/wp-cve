<?php

class MM_WPFS_Options {

    const OPTION_KEY_FULLSTRIPE = 'fullstripe_options';

    const OPTION_VERSION = 'fullstripe_version';
    const OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_CANCEL_SUBSCRIPTIONS = 'my_account_subscribers_cancel_subscriptions';
    const OPTION_EMAIL_NOTIFICATION_BCC_ADDRESSES = 'email_notification_bcc_addresses';
    const OPTION_API_TEST_PUBLISHABLE_KEY = 'publishKey_test';
    const OPTION_API_MODE = 'apiMode';
    const OPTION_TEST_STRIPE_PLATFORM_PK = 'pk_test_51Nudb0J5g91RXBK2c1QCKTQDnwUzVX5lM3ICrpbEb7KuTsmYe188Dux2m2lyHA8nTy2LfW9Ui0PFta4v3HL67Y1M00ZETgTIxV';
    const OPTION_LIVE_STRIPE_PLATFORM_PK = 'pk_live_51Nudb0J5g91RXBK2alZ7l9zDkyoqVVa7Wun9Z3TlWCMDTm1pYdCUIPQpE13UvqRidKKkaDoma1a8Rp2YviNeoqwY00pQQNojq3';
    const OPTION_TEST_ACCOUNT_ID = 'test_account_id';
    const OPTION_LIVE_ACCOUNT_ID = 'live_account_id';
    const OPTION_LIVE_ACCOUNT_STATUS = 'live_account_status';
    const OPTION_TEST_ACCOUNT_STATUS = 'test_account_status';
    const OPTION_USE_WP_TEST_PLATFORM = 'use_wp_test_platform';
    const OPTION_USE_WP_LIVE_PLATFORM = 'use_wp_live_platform';
    const OPTION_GOOGLE_CLOUD_FUNCTIONS_URL = 'https://us-central1-wpfp-functions-prod.cloudfunctions.net';
    const OPTION_ACCOUNT_STATUS_REJECTED = 'REJECTED';
    const OPTION_ACCOUNT_STATUS_RESTRICTED = 'RESTRICTED';
    const OPTION_ACCOUNT_STATUS_RESTRICTED_SOON = 'RESTRICTED_SOON';
    const OPTION_ACCOUNT_STATUS_PENDING_ENABLED = 'PENDING_ENABLED';
    const OPTION_ACCOUNT_STATUS_PENDING_DISABLED = 'PENDING_DISABLED';
    const OPTION_ACCOUNT_STATUS_ENABLED = 'ENABLED';
    const OPTION_ACCOUNT_STATUS_COMPLETE = 'COMPLETE';
    const OPTION_ACCOUNT_STATUS_NULL = 'NULL';
    const OPTION_CUSTOMER_PORTAL_SCROLLING_PANE_INTO_VIEW = 'my_account_scrolling_pane_into_view';
    const OPTION_CUSTOMER_PORTAL_SHOW_ALL_INVOICES = 'my_account_show_all_invoices';
    const OPTION_SHOW_CURRENCY_SYMBOL_INSTEAD_OF_CODE = 'show_currency_symbol_instead_of_code';
    const OPTION_CUSTOMER_PORTAL_SHOW_SUBSCRIPTIONS_TO_CUSTOMERS = 'my_account_subscribers_show_subscriptions';
    const OPTION_SHOW_CURRENCY_SIGN_AT_FIRST_POSITION = 'show_currency_sign_first';
    const OPTION_LAST_WEBHOOK_EVENT_TEST = 'last_webhook_event_test';
    const OPTION_CUSTOMER_PORTAL_SHOW_INVOICES_SECTION = 'my_account_show_invoices_section';
    const OPTION_LAST_WEBHOOK_EVENT_LIVE = 'last_webhook_event_live';
    const OPTION_RECEIPT_EMAIL_TYPE = 'receiptEmailType';
    const OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_UPDOWNGRADE_SUBSCRIPTIONS = 'my_account_subscribers_updowngrade_subscriptions';
    const OPTION_API_LIVE_PUBLISHABLE_KEY = 'publishKey_live';
    const OPTION_PUT_WHITESPACE_BETWEEN_CURRENCY_AND_AMOUNT = 'put_whitespace_between_currency_and_amount';
    const OPTION_EMAIL_NOTIFICATION_SENDER_ADDRESS = 'email_receipt_sender_address';
    const OPTION_API_LIVE_SECRET_KEY = 'secretKey_live';
    const OPTION_VALUE_RECEIPT_EMAIL_PLUGIN = 'plugin';
    const OPTION_LOG_LEVEL = 'logLevel';
    const OPTION_FORM_CUSTOM_CSS = 'form_css';
    const OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY = 'google_recaptcha_secret_key';
    const OPTION_SECURE_CUSTOMER_PORTAL_WITH_GOOGLE_RE_CAPTCHA = 'secure_subscription_update_with_google_recaptcha';
    const OPTION_SECURE_CHECKOUT_FORMS_WITH_GOOGLE_RE_CAPTCHA = 'secure_checkout_forms_with_google_recaptcha';
    const OPTION_API_TEST_SECRET_KEY = 'secretKey_test';
    const OPTION_LOG_TO_WEB_SERVER = 'logToWebServer';
    const OPTION_CATCH_UNCAUGHT_ERRORS = 'catchUncaughtErrors';
    const OPTION_CUSTOMER_PORTAL_WHEN_CANCEL_SUBSCRIPTIONS = 'my_account_when_cancel_subscriptions';
    const OPTION_FILL_IN_EMAIL_FOR_LOGGED_IN_USERS = 'lock_email_field_for_logged_in_users';
    const OPTION_DECIMAL_SEPARATOR_SYMBOL = 'decimal_separator_symbol';
    const OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA = 'secure_inline_forms_with_google_recaptcha';
    const OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY = 'google_recaptcha_site_key';
    const OPTION_EMAIL_TEMPLATES = 'email_receipts';
    const OPTION_WEBHOOK_TOKEN = 'webhook_token';
    const OPTION_CUSTOM_INPUT_FIELD_MAX_COUNT = 'custom_input_field_max_count';
    const OPTION_SET_FORM_FIELDS_VIA_URL_PARAMETERS = 'set_form_fields_via_url_parameters';

    public function __construct() {
    }

    protected function getOptionsSafe( $key ) {
        return get_option( $key, [] );
    }

    public function get( $key ) {
        $options = $this->getOptionsSafe( self::OPTION_KEY_FULLSTRIPE );

        return $options[ $key ];
    }

    public function getSeveral( array $keys ) {
        $result = [];
        $options = $this->getOptionsSafe( self::OPTION_KEY_FULLSTRIPE );

        foreach ( $keys as $key ) {
            $result[ $key ] = $options[ $key ];
        }

        return $result;
    }

    public function set( $key, $value ) {
        $options = $this->getOptionsSafe( self::OPTION_KEY_FULLSTRIPE );

        $options[ $key ] = $value;

        update_option( self::OPTION_KEY_FULLSTRIPE, $options );
    }

    public function setSeveral( $keyValues ) {
        $options = $this->getOptionsSafe( self::OPTION_KEY_FULLSTRIPE );

        foreach ( $keyValues as $key => $value ) {
            $options[ $key ] = $value;
        }

        update_option( self::OPTION_KEY_FULLSTRIPE, $options );
    }

    public function setNonExistentSeveral( $keyValues ) {
        $options = $this->getOptionsSafe( self::OPTION_KEY_FULLSTRIPE );

        foreach ( $keyValues as $key => $value ) {
            if ( ! array_key_exists( $key, $options ) ) {
                $options[ $key ] = $value;
            }
        }

        update_option( self::OPTION_KEY_FULLSTRIPE, $options );
    }

    public function getVersion() {
        return $this->get( self::OPTION_VERSION );
    }
}
