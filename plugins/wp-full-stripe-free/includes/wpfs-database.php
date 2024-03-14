<?php

class MM_WPFS_Database {
    const DATE_FORMAT_DATABASE = 'Y-m-d H:i:s';

	/**
	 *
	 * @return bool
	 * @throws Exception
	 */
	public static function fullstripe_setup_db() {
		// require for dbDelta()
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$table = $wpdb->prefix . 'fullstripe_payments';

		$sql = "CREATE TABLE " . $table . " (
        paymentID INT NOT NULL AUTO_INCREMENT,
        eventID VARCHAR(100) NOT NULL,
        description VARCHAR(255) NOT NULL,
        payment_method VARCHAR(100),
        paid TINYINT(1),
        captured TINYINT(1),
        refunded TINYINT(1),
        expired TINYINT(1),
        failure_code VARCHAR(100),
        failure_message VARCHAR(512),
        livemode TINYINT(1),
        last_charge_status VARCHAR(100),
        currency VARCHAR(3) NOT NULL,
        amount INT NOT NULL,
        fee INT NOT NULL,
        priceId VARCHAR(100),
        coupon VARCHAR(100),
        billingName VARCHAR(100),
        addressLine1 VARCHAR(500) NOT NULL,
        addressLine2 VARCHAR(500) NOT NULL,
        addressCity VARCHAR(500) NOT NULL,
        addressState VARCHAR(255) NOT NULL,
        addressZip VARCHAR(100) NOT NULL,
        addressCountry VARCHAR(100) NOT NULL,
        addressCountryCode VARCHAR(2) NOT NULL,
        shippingName VARCHAR(100),
        shippingAddressLine1 VARCHAR(500) NOT NULL,
        shippingAddressLine2 VARCHAR(500) NOT NULL,
        shippingAddressCity VARCHAR(500) NOT NULL,
        shippingAddressState VARCHAR(255) NOT NULL,
        shippingAddressZip VARCHAR(100) NOT NULL,
        shippingAddressCountry VARCHAR(100) NOT NULL,
        shippingAddressCountryCode VARCHAR(2) NOT NULL,
        created DATETIME NOT NULL,
        stripeCustomerID VARCHAR(100),
        name VARCHAR(100),
        email VARCHAR(255) NOT NULL,
        formId INT,
        formType VARCHAR(30),
        formName VARCHAR(100),
        ipAddressSubmit VARCHAR(64),
        customFields TEXT,
        phoneNumber VARCHAR(64),
        PRIMARY KEY (paymentID)
        ) $charset_collate;";

		// database write/update
		dbDelta( $sql );

		$table = $wpdb->prefix . 'fullstripe_payment_forms';

		$sql = "CREATE TABLE " . $table . " (
        paymentFormID INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        displayName VARCHAR(100),        
        formTitle VARCHAR(100) NOT NULL,
        chargeType VARCHAR(100) NOT NULL,
        amount INT NOT NULL,
        currency VARCHAR(3) NOT NULL,
        customAmount VARCHAR(32) NOT NULL,
        listOfAmounts VARCHAR(1024) DEFAULT NULL,
        decoratedProducts TEXT,
        minimumPaymentAmount INT default 0,
        allowListOfAmountsCustom TINYINT(1) DEFAULT '0',
        vatRateType VARCHAR(32),
        vatRates TEXT,
        collectCustomerTaxId TINYINT(1) DEFAULT '0',
        generateInvoice TINYINT(1) DEFAULT '0',
        amountSelectorStyle VARCHAR(100) NOT NULL,
        buttonTitle VARCHAR(100) NOT NULL DEFAULT 'Make Payment',
        showButtonAmount TINYINT(1) DEFAULT '1',
        showEmailInput TINYINT(1) DEFAULT '1',
		showCouponInput TINYINT(1) DEFAULT '0',
        showCustomInput TINYINT(1) DEFAULT '0',
        customInputRequired TINYINT(1) DEFAULT '0',
        customInputTitle VARCHAR(100) NOT NULL DEFAULT 'Extra Information',
        customInputs TEXT,
        redirectOnSuccess TINYINT(1) DEFAULT '0',
        redirectPostID INT(5) DEFAULT 0,
        redirectUrl VARCHAR(1024) DEFAULT NULL,
        redirectToPageOrPost TINYINT(1) DEFAULT '1',
        showDetailedSuccessPage TINYINT(1) DEFAULT '0',
        showAddress TINYINT(1) DEFAULT '0',
		defaultBillingCountry VARCHAR(100),
        showShippingAddress TINYINT(1) DEFAULT '0',
        sendEmailReceipt TINYINT(1) DEFAULT '0',
        formStyle INT(5) DEFAULT 0,
        stripeDescription VARCHAR(1024) DEFAULT NULL,
        showTermsOfUse TINYINT(1) DEFAULT '0',
        termsOfUseLabel VARCHAR(1024) DEFAULT NULL,
        termsOfUseNotCheckedErrorMessage VARCHAR(256) DEFAULT NULL,
        preferredLanguage VARCHAR(16),
        decimalSeparator VARCHAR(32) NOT NULL,
        showCurrencySymbolInsteadOfCode TINYINT(1) DEFAULT '1',
        showCurrencySignAtFirstPosition TINYINT(1) DEFAULT '1',
        putWhitespaceBetweenCurrencyAndAmount TINYINT(1) DEFAULT '0',
        emailTemplates TEXT,
        stripeElementsTheme VARCHAR(32) NOT NULL DEFAULT 'stripe',
        stripeElementsFont VARCHAR(32),
        PRIMARY KEY (paymentFormID)
        ) $charset_collate;";

		// database write/update
		dbDelta( $sql );

		$paymentType = MM_WPFS::PAYMENT_TYPE_SPECIFIED_AMOUNT;
		// tnagy migrate old values
		$queryResult = $wpdb->query( $wpdb->prepare( "UPDATE $table SET customAmount = %s WHERE customAmount = %s", $paymentType, '0' ) );
		self::handleDbError( $queryResult, 'Migration of fullstripe_payment_forms/customAmount failed!' );

		$paymentType = MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT;
		$queryResult = $wpdb->query( $wpdb->prepare( "UPDATE $table SET customAmount = %s WHERE customAmount = %s", $paymentType, '1' ) );
		self::handleDbError( $queryResult, 'Migration of fullstripe_payment_forms/customAmount failed!' );

		$table = $wpdb->prefix . 'fullstripe_subscription_forms';

		$sql = "CREATE TABLE " . $table . " (
        subscriptionFormID INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        displayName VARCHAR(100),
        formTitle VARCHAR(100) NOT NULL,
        plans VARCHAR(2048) NOT NULL,
        decoratedPlans TEXT,
        showCouponInput TINYINT(1) DEFAULT '0',
        showCustomInput TINYINT(1) DEFAULT '0',
        customInputRequired TINYINT(1) DEFAULT '0',
        customInputTitle VARCHAR(100) NOT NULL DEFAULT 'Extra Information',
        customInputs TEXT,
        redirectOnSuccess TINYINT(1) DEFAULT '0',
        redirectPostID INT(5) DEFAULT 0,
        redirectUrl VARCHAR(1024) DEFAULT NULL,
        redirectToPageOrPost TINYINT(1) DEFAULT '1',
        showDetailedSuccessPage TINYINT(1) DEFAULT '0',
        showAddress TINYINT(1) DEFAULT '0',
        defaultBillingCountry VARCHAR(100),
        showShippingAddress TINYINT(1) DEFAULT '0',
        sendEmailReceipt TINYINT(1) DEFAULT '0',
        formStyle INT(5) DEFAULT 0,
        buttonTitle VARCHAR(100) NOT NULL DEFAULT 'Subscribe',
        setupFee INT NOT NULL DEFAULT '0',
        vatRateType VARCHAR(32),
        vatRates TEXT,
        collectCustomerTaxId TINYINT(1) DEFAULT '0',
        vatPercent DECIMAL(7,4) DEFAULT 0.0,
        stripeDescription VARCHAR(1024) DEFAULT NULL,
        showTermsOfUse TINYINT(1) DEFAULT '0',
        termsOfUseLabel VARCHAR(1024) DEFAULT NULL,
        termsOfUseNotCheckedErrorMessage VARCHAR(256) DEFAULT NULL,
        planSelectorStyle VARCHAR(32) NOT NULL,
        allowMultipleSubscriptions TINYINT(1) DEFAULT '0',
        minimumQuantityOfSubscriptions INT(5) DEFAULT 0,
        maximumQuantityOfSubscriptions INT(5) DEFAULT 0,
        anchorBillingCycle TINYINT(1) DEFAULT '0',
        billingCycleAnchorDay TINYINT(2) DEFAULT '0',
        prorateUntilAnchorDay TINYINT(1) DEFAULT '1',
        preferredLanguage VARCHAR(16),
        decimalSeparator VARCHAR(32) NOT NULL,
        showCurrencySymbolInsteadOfCode TINYINT(1) DEFAULT '1',
        showCurrencySignAtFirstPosition TINYINT(1) DEFAULT '1',
        putWhitespaceBetweenCurrencyAndAmount TINYINT(1) DEFAULT '0',
        emailTemplates TEXT,
        stripeElementsTheme VARCHAR(32) NOT NULL DEFAULT 'stripe',
        stripeElementsFont VARCHAR(32),
        PRIMARY KEY (subscriptionFormID)
        ) $charset_collate;";

		// database write/update
		dbDelta( $sql );

		$table = $wpdb->prefix . 'fullstripe_subscribers';

		$sql = "CREATE TABLE " . $table . " (
        subscriberID INT NOT NULL AUTO_INCREMENT,
        stripeCustomerID VARCHAR(100) NOT NULL,
        stripeSubscriptionID VARCHAR(100) NOT NULL,
        stripePaymentIntentID VARCHAR(100),
        stripeSetupIntentID VARCHAR(100),
		chargeMaximumCount INT(5) NOT NULL,
		chargeCurrentCount INT(5) NOT NULL,
		invoiceCreatedCount INT(5),		
		status VARCHAR(32) NOT NULL,
		cancelled DATETIME DEFAULT NULL,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL,
        planID VARCHAR(100) NOT NULL,
        quantity INT(5) DEFAULT 1,
        coupon VARCHAR(100),
        billingName VARCHAR(100),
        addressLine1 VARCHAR(500) NOT NULL,
        addressLine2 VARCHAR(500) NOT NULL,
        addressCity VARCHAR(500) NOT NULL,
        addressState VARCHAR(255) NOT NULL,
        addressZip VARCHAR(100) NOT NULL,
        addressCountry VARCHAR(100) NOT NULL,
        addressCountryCode VARCHAR(2) NOT NULL,
        shippingName VARCHAR(100),
        shippingAddressLine1 VARCHAR(500),
        shippingAddressLine2 VARCHAR(500),
        shippingAddressCity VARCHAR(500),
        shippingAddressState VARCHAR(255),
        shippingAddressZip VARCHAR(100),
        shippingAddressCountry VARCHAR(100),
        shippingAddressCountryCode VARCHAR(2),
        created DATETIME NOT NULL,
        livemode TINYINT(1),
        formId INT,
        formName VARCHAR(100),
        vatPercent DECIMAL(7,4) DEFAULT 0.0,
        processedStripeEventIDs TEXT,
        ipAddressSubmit VARCHAR(64),
        customFields TEXT,
        phoneNumber VARCHAR(64),
        PRIMARY KEY (subscriberID),
		KEY stripeSubscriptionID (stripeSubscriptionID),
		KEY stripePaymentIntentID (stripePaymentIntentID),
		KEY stripeSetupIntentID (stripeSetupIntentID)
        ) $charset_collate;";

		// database write/update
		dbDelta( $sql );

		$table = $wpdb->prefix . 'fullstripe_checkout_forms';

		$sql = "CREATE TABLE " . $table . " (
        checkoutFormID INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        displayName VARCHAR(100),
        companyName VARCHAR(100) NOT NULL,
        productDesc VARCHAR(100) NOT NULL,
        chargeType VARCHAR(100) NOT NULL,
        amount INT NOT NULL,
        currency VARCHAR(3) NOT NULL,
        customAmount VARCHAR(32) NOT NULL,
        listOfAmounts VARCHAR(1024) DEFAULT NULL,
        decoratedProducts TEXT,
        minimumPaymentAmount INT default 0,
        vatRateType VARCHAR(32),
        vatRates TEXT,
        collectCustomerTaxId TINYINT(1) DEFAULT '0',
        generateInvoice TINYINT(1) DEFAULT '0',
        allowListOfAmountsCustom TINYINT(1) DEFAULT '0',
        amountSelectorStyle VARCHAR(100) NOT NULL,
        openButtonTitle VARCHAR(100) NOT NULL DEFAULT 'Pay With Card',
        buttonTitle VARCHAR(100) NOT NULL DEFAULT 'Pay {{amount}}',
        showButtonAmount TINYINT(1) DEFAULT '1',
        showBillingAddress TINYINT(1) DEFAULT '0',
        defaultBillingCountry VARCHAR(100),
        showShippingAddress TINYINT(1) DEFAULT '0',
		showCouponInput TINYINT(1) DEFAULT '0',
        showCustomInput TINYINT(1) DEFAULT '0',
        customInputRequired TINYINT(1) DEFAULT '0',
        customInputTitle VARCHAR(100) NOT NULL DEFAULT 'Extra Information',
        customInputs TEXT,
        sendEmailReceipt TINYINT(1) DEFAULT '0',
        showRememberMe TINYINT(1) DEFAULT '0',
        image VARCHAR(500) NOT NULL,
        redirectOnSuccess TINYINT(1) DEFAULT '0',
        redirectPostID INT(5) DEFAULT 0,
        redirectUrl VARCHAR(1024) DEFAULT NULL,
        redirectToPageOrPost TINYINT(1) DEFAULT '1',
        showDetailedSuccessPage TINYINT(1) DEFAULT '0',
        disableStyling TINYINT(1) DEFAULT 0,
        useBitcoin TINYINT(1) DEFAULT '0',
        useAlipay TINYINT(1) DEFAULT '0',
        preferredLanguage VARCHAR(16),
        stripeDescription VARCHAR(1024) DEFAULT NULL,
        showTermsOfUse TINYINT(1) DEFAULT '0',
        termsOfUseLabel VARCHAR(1024) DEFAULT NULL,
        termsOfUseNotCheckedErrorMessage VARCHAR(256) DEFAULT NULL,
        decimalSeparator VARCHAR(32) NOT NULL,
        showCurrencySymbolInsteadOfCode TINYINT(1) DEFAULT '1',
        showCurrencySignAtFirstPosition TINYINT(1) DEFAULT '1',
        putWhitespaceBetweenCurrencyAndAmount TINYINT(1) DEFAULT '0',
        emailTemplates TEXT,
        collectPhoneNumber TINYINT(1) DEFAULT '0',
        PRIMARY KEY (checkoutFormID)
        ) $charset_collate;";

		// database write/update
		dbDelta( $sql );

		$sql = "CREATE TABLE {$wpdb->prefix}fullstripe_patch_info (
		id INT NOT NULL AUTO_INCREMENT,
		patch_id VARCHAR(191) NOT NULL,
		plugin_version VARCHAR(255) NOT NULL,
		applied_at DATETIME NOT NULL,
		description VARCHAR(500),
		PRIMARY KEY (id),
		KEY patch_id (patch_id)
		) $charset_collate;";

		dbDelta( $sql );

		$table = $wpdb->prefix . 'fullstripe_checkout_subscription_forms';

		$sql = "CREATE TABLE " . $table . " (
		checkoutSubscriptionFormID INT NOT NULL AUTO_INCREMENT,
		name VARCHAR(100) NOT NULL,
		displayName VARCHAR(100),
		companyName VARCHAR(100) NOT NULL,
		productDesc VARCHAR(100) NOT NULL,
		image VARCHAR(500) NOT NULL,
		plans VARCHAR(2048) NOT NULL,
        decoratedPlans TEXT,
		showCouponInput TINYINT(1) DEFAULT '0',
		showCustomInput TINYINT(1) DEFAULT '0',
		customInputRequired TINYINT(1) DEFAULT '0',
		customInputTitle VARCHAR(100) NOT NULL DEFAULT 'Extra Information',
		customInputs TEXT,
		redirectOnSuccess TINYINT(1) DEFAULT '0',
		redirectPostID INT(5) DEFAULT 0,
		redirectUrl VARCHAR(1024) DEFAULT NULL,
		redirectToPageOrPost TINYINT(1) DEFAULT '1',
		showDetailedSuccessPage TINYINT(1) DEFAULT '0',
		showBillingAddress TINYINT(1) DEFAULT '0',
		showShippingAddress TINYINT(1) DEFAULT '0',
		sendEmailReceipt TINYINT(1) DEFAULT '0',
		disableStyling TINYINT(1) DEFAULT 0,
        openButtonTitle VARCHAR(100) NOT NULL DEFAULT 'Pay With Card',
		buttonTitle VARCHAR(100) NOT NULL DEFAULT 'Subscribe',
		showRememberMe TINYINT(1) DEFAULT '0',
        vatRateType VARCHAR(32),
        vatRates TEXT,
        collectCustomerTaxId TINYINT(1) DEFAULT '0',
        vatPercent DECIMAL(7,4) DEFAULT 0.0,
        defaultBillingCountry VARCHAR(100),
        simpleButtonLayout TINYINT(1) DEFAULT '0',
        preferredLanguage VARCHAR(16),
        stripeDescription VARCHAR(1024) DEFAULT NULL,
        showTermsOfUse TINYINT(1) DEFAULT '0',
        termsOfUseLabel VARCHAR(1024) DEFAULT NULL,
        termsOfUseNotCheckedErrorMessage VARCHAR(256) DEFAULT NULL,
        planSelectorStyle VARCHAR(32) NOT NULL,
        allowMultipleSubscriptions TINYINT(1) DEFAULT '0',
        minimumQuantityOfSubscriptions INT(5) DEFAULT 0,
        maximumQuantityOfSubscriptions INT(5) DEFAULT 0,
        decimalSeparator VARCHAR(32) NOT NULL,
        showCurrencySymbolInsteadOfCode TINYINT(1) DEFAULT '1',
        showCurrencySignAtFirstPosition TINYINT(1) DEFAULT '1',
        putWhitespaceBetweenCurrencyAndAmount TINYINT(1) DEFAULT '0',
        emailTemplates TEXT,
        collectPhoneNumber TINYINT(1) DEFAULT '0',
        PRIMARY KEY (checkoutSubscriptionFormID)
        ) $charset_collate;";

		// database write/update
		dbDelta( $sql );

		$table = $wpdb->prefix . 'fullstripe_card_captures';

		$sql = "CREATE TABLE $table (
        captureID INT NOT NULL AUTO_INCREMENT,
        livemode TINYINT(1),
        billingName VARCHAR(100),
        addressLine1 VARCHAR(500) NOT NULL,
        addressLine2 VARCHAR(500) NOT NULL,
        addressCity VARCHAR(500) NOT NULL,
        addressState VARCHAR(255) NOT NULL,
        addressZip VARCHAR(100) NOT NULL,
        addressCountry VARCHAR(100) NOT NULL,
        addressCountryCode VARCHAR(2) NOT NULL,
        shippingName VARCHAR(100),
        shippingAddressLine1 VARCHAR(500) NOT NULL,
        shippingAddressLine2 VARCHAR(500) NOT NULL,
        shippingAddressCity VARCHAR(500) NOT NULL,
        shippingAddressState VARCHAR(255) NOT NULL,
        shippingAddressZip VARCHAR(100) NOT NULL,
        shippingAddressCountry VARCHAR(100) NOT NULL,
        shippingAddressCountryCode VARCHAR(2) NOT NULL,
        created DATETIME NOT NULL,
        stripeCustomerID VARCHAR(100),
        name VARCHAR(100),
        email VARCHAR(255) NOT NULL,
        formId INT,
        formType VARCHAR(30),
        formName VARCHAR(100),
        ipAddressSubmit VARCHAR(64),
        customFields TEXT,
        PRIMARY KEY (captureID)
        ) $charset_collate;";

		// database write/update
		dbDelta( $sql );

		$sql = "CREATE TABLE {$wpdb->prefix}fullstripe_card_update_session (
		id INT NOT NULL AUTO_INCREMENT,
		hash VARCHAR(191) NOT NULL,
		email VARCHAR(191) NOT NULL,
		liveMode TINYINT(1),
		stripeCustomerId VARCHAR(100) NOT NULL,
		securityCodeRequest INT DEFAULT 0,
		securityCodeInput INT DEFAULT 0,
		created DATETIME NOT NULL,
		status VARCHAR(32) NOT NULL,
		PRIMARY KEY (id),
		KEY hash (hash),
		KEY email (email),
		KEY stripeCustomerId (stripeCustomerId),
		KEY status (status),
		KEY created (created)
		) $charset_collate;";

		// database write/update
		dbDelta( $sql );

		$sql = "CREATE TABLE {$wpdb->prefix}fullstripe_security_code (
		id INT NOT NULL AUTO_INCREMENT,
		sessionId INT NOT NULL,
		securityCode VARCHAR(191) NOT NULL,
		created DATETIME NOT NULL,
		sent DATETIME,
		consumed DATETIME,
		status VARCHAR(32) NOT NULL,
		PRIMARY KEY (id),
		KEY sessionId (sessionId),
		KEY securityCode (securityCode),
		KEY status (status)
		) $charset_collate;";

		// database write/update
		dbDelta( $sql );

		$sql = "CREATE TABLE {$wpdb->prefix}fullstripe_checkout_form_submit (
		id INT NOT NULL AUTO_INCREMENT,
		hash VARCHAR(191) NOT NULL,
		formHash VARCHAR(64) NOT NULL,
		formType VARCHAR(30),
		referrer VARCHAR(1024) NOT NULL,
		postData TEXT NOT NULL,
		checkoutSessionId VARCHAR(191),
		liveMode TINYINT(1),
		created DATETIME NOT NULL,
		status VARCHAR(32) NOT NULL,
		lastMessageTitle VARCHAR(256),
		lastMessage VARCHAR(1024),
		processedWithError INT DEFAULT 0,
		errorMessage VARCHAR(180),
		relatedStripeEventIDs TEXT,
		PRIMARY KEY (id),
		KEY hash (hash),
		KEY checkoutSessionId (checkoutSessionId),
		KEY status (status),
		KEY liveMode (liveMode),
		KEY liveModeStatus (liveMode, status)
		) $charset_collate;";

		// database write/update
		dbDelta( $sql );

		$sql = "CREATE TABLE {$wpdb->prefix}fullstripe_log (
		id INT NOT NULL AUTO_INCREMENT,
		created DATETIME NOT NULL,
		`module` VARCHAR(64) NOT NULL,
		`class` VARCHAR(128) NOT NULL,
		`function` VARCHAR(128) NOT NULL,
		`level` VARCHAR(16) NOT NULL,
		`message` VARCHAR(512) NOT NULL,
		`exception` TEXT NOT NULL,
		PRIMARY KEY (id),
		KEY created (created),
		KEY `module` (`module`),
		KEY `class` (`class`),
		KEY `function` (`function`),
		KEY `level` (`level`)
		) $charset_collate;";

		// database write/update
		dbDelta( $sql );

        $table = $wpdb->prefix . 'fullstripe_donations';

        $sql = "CREATE TABLE " . $table . " (
        donationID INT NOT NULL AUTO_INCREMENT,
        stripeCustomerID VARCHAR(100) NOT NULL,
        stripePaymentIntentID VARCHAR(100) NOT NULL,
        stripeSubscriptionID VARCHAR(100),
        stripePlanID VARCHAR(100),
        stripeSetupIntentID VARCHAR(100),
        description VARCHAR(255),
        paymentMethod VARCHAR(100),
        paid TINYINT(1),
        captured TINYINT(1),
        refunded TINYINT(1),
        expired TINYINT(1),
        failureCode VARCHAR(100),
        failureMessage VARCHAR(512),
        lastChargeStatus VARCHAR(100),
        currency VARCHAR(3) NOT NULL,
        amount INT NOT NULL,
        donationFrequency VARCHAR(32),
		subscriptionStatus VARCHAR(32),
		cancelled DATETIME DEFAULT NULL,
        name VARCHAR(100),
        email VARCHAR(255),
        billingName VARCHAR(100),
        addressLine1 VARCHAR(500),
        addressLine2 VARCHAR(500),
        addressCity VARCHAR(500),
        addressState VARCHAR(255),
        addressZip VARCHAR(100),
        addressCountry VARCHAR(100),
        addressCountryCode VARCHAR(2),
        shippingName VARCHAR(100),
        shippingAddressLine1 VARCHAR(500),
        shippingAddressLine2 VARCHAR(500),
        shippingAddressCity VARCHAR(500),
        shippingAddressState VARCHAR(255),
        shippingAddressZip VARCHAR(100),
        shippingAddressCountry VARCHAR(100),
        shippingAddressCountryCode VARCHAR(2),
        created DATETIME NOT NULL,
        livemode TINYINT(1),
        formId INT,
        formType VARCHAR(30),
        formName VARCHAR(100),
        vatPercent DECIMAL(7,4) DEFAULT 0.0,
        processedStripeEventIDs TEXT,
        ipAddressSubmit VARCHAR(64),
        customFields TEXT,
        phoneNumber VARCHAR(64),
        PRIMARY KEY (donationID),
		KEY stripeSubscriptionID (stripeSubscriptionID),
		KEY stripePaymentIntentID (stripePaymentIntentID),
		KEY stripeSetupIntentID (stripeSetupIntentID)
        ) $charset_collate;";

        // database write/update
        dbDelta( $sql );


        $table = $wpdb->prefix . 'fullstripe_donation_forms';

        $sql = "CREATE TABLE " . $table . " (
        donationFormID INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(100),
        displayName VARCHAR(100),
        currency VARCHAR(3),
        minimumDonationAmount INT default 0,
        donationAmounts VARCHAR(1024) DEFAULT NULL,
        allowOneTimeDonation TINYINT(1) DEFAULT '0',
        allowCustomDonationAmount TINYINT(1) DEFAULT '0',
        allowDailyRecurring TINYINT(1) DEFAULT '0',
        allowWeeklyRecurring TINYINT(1) DEFAULT '0',
        allowMonthlyRecurring TINYINT(1) DEFAULT '0',
        allowAnnualRecurring TINYINT(1) DEFAULT '0',
        stripeDescription VARCHAR(1024) DEFAULT NULL,
        productDesc VARCHAR(100),
        generateInvoice TINYINT(1) DEFAULT '0',
        buttonTitle VARCHAR(100) DEFAULT 'Donate',
        showAddress TINYINT(1) DEFAULT '0',
		defaultBillingCountry VARCHAR(100),
        showShippingAddress TINYINT(1) DEFAULT '0',
        preferredLanguage VARCHAR(16),
        decimalSeparator VARCHAR(32),
        showCurrencySymbolInsteadOfCode TINYINT(1) DEFAULT '1',
        showCurrencySignAtFirstPosition TINYINT(1) DEFAULT '1',
        putWhitespaceBetweenCurrencyAndAmount TINYINT(1) DEFAULT '0',
        showTermsOfUse TINYINT(1) DEFAULT '0',
        termsOfUseLabel VARCHAR(1024) DEFAULT NULL,
        termsOfUseNotCheckedErrorMessage VARCHAR(256) DEFAULT NULL,
        showCustomInput TINYINT(1) DEFAULT '0',
        customInputRequired TINYINT(1) DEFAULT '0',
        customInputTitle VARCHAR(100) DEFAULT 'Extra Information',
        customInputs TEXT,
        sendEmailReceipt TINYINT(1) DEFAULT '0',
        redirectOnSuccess TINYINT(1) DEFAULT '0',
        redirectPostID INT(5) DEFAULT 0,
        redirectUrl VARCHAR(1024) DEFAULT NULL,
        redirectToPageOrPost TINYINT(1) DEFAULT '1',
        showDetailedSuccessPage TINYINT(1) DEFAULT '0',
        emailTemplates TEXT,
        stripeElementsTheme VARCHAR(32) NOT NULL DEFAULT 'stripe',
        stripeElementsFont VARCHAR(32),
        PRIMARY KEY (donationFormID)
        ) $charset_collate;";

        // database write/update
        dbDelta( $sql );

        $table = $wpdb->prefix . 'fullstripe_checkout_donation_forms';

        $sql = "CREATE TABLE " . $table . " (
        checkoutDonationFormID INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(100),
        displayName VARCHAR(100),
        currency VARCHAR(3),
        minimumDonationAmount INT default 0,
        donationAmounts VARCHAR(1024) DEFAULT NULL,
        allowCustomDonationAmount TINYINT(1) DEFAULT '0',
        allowOneTimeDonation TINYINT(1) DEFAULT '0',
        allowDailyRecurring TINYINT(1) DEFAULT '0',
        allowWeeklyRecurring TINYINT(1) DEFAULT '0',
        allowMonthlyRecurring TINYINT(1) DEFAULT '0',
        allowAnnualRecurring TINYINT(1) DEFAULT '0',
        stripeDescription VARCHAR(1024) DEFAULT NULL,
        generateInvoice TINYINT(1) DEFAULT '0',
        companyName VARCHAR(100),
        productDesc VARCHAR(100),
        image VARCHAR(500),
        openButtonTitle VARCHAR(100) DEFAULT 'Donate',
        buttonTitle VARCHAR(100) DEFAULT 'Donate',
        showBillingAddress TINYINT(1) DEFAULT '0',
		defaultBillingCountry VARCHAR(100),
        showShippingAddress TINYINT(1) DEFAULT '0',
        preferredLanguage VARCHAR(16),
        decimalSeparator VARCHAR(32),
        showCurrencySymbolInsteadOfCode TINYINT(1) DEFAULT '1',
        showCurrencySignAtFirstPosition TINYINT(1) DEFAULT '1',
        putWhitespaceBetweenCurrencyAndAmount TINYINT(1) DEFAULT '0',
        showTermsOfUse TINYINT(1) DEFAULT '0',
        termsOfUseLabel VARCHAR(1024) DEFAULT NULL,
        termsOfUseNotCheckedErrorMessage VARCHAR(256) DEFAULT NULL,
        showCustomInput TINYINT(1) DEFAULT '0',
        customInputRequired TINYINT(1) DEFAULT '0',
        customInputTitle VARCHAR(100) DEFAULT 'Extra Information',
        customInputs TEXT,
        sendEmailReceipt TINYINT(1) DEFAULT '0',
        redirectOnSuccess TINYINT(1) DEFAULT '0',
        redirectPostID INT(5) DEFAULT 0,
        redirectUrl VARCHAR(1024) DEFAULT NULL,
        redirectToPageOrPost TINYINT(1) DEFAULT '1',
        showDetailedSuccessPage TINYINT(1) DEFAULT '0',
        emailTemplates TEXT,
        collectPhoneNumber TINYINT(1) DEFAULT '0',
        PRIMARY KEY (checkoutDonationFormID)
        ) $charset_collate;";

        // database write/update
        dbDelta( $sql );

        do_action( 'fullstripe_setup_db' );

		return true;
	}

	/**
	 *
	 * @param $result
	 *
	 * @param $message
	 *
	 * @throws Exception
	 */
	public static function handleDbError( $result, $message ) {
		if ( $result === false ) {
			global $wpdb;
			MM_WPFS_Utils::log( sprintf( "%s: Raised exception with message=%s", 'WP Full Pay/Database', $message ) );
            MM_WPFS_Utils::log( sprintf( "%s: SQL last error=%s", 'WP Full Pay/Database', $wpdb->last_error ) );
			throw new Exception( $message );
		}
	}

	/**
	 * @return array|null|object|void
	 */
	public static function get_site_ids() {
		global $wpdb;

		return $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs} WHERE site_id = {$wpdb->siteid};" );
	}

    /**
     * @param MM_WPFS_Public_DonationFormModel $donationFormModel
     * @param \StripeWPFS\PaymentIntent $paymentIntent
     * @param \StripeWPFS\Subscription $subscription
     *
     * @return mixed
     * @throws Exception
     */
	public function insertInlineDonation( $donationFormModel, $paymentIntent, $subscription, $lastCharge ) {
        global $wpdb;

        $stripeCustomerID       = $donationFormModel->getStripeCustomer()->id;
        $stripeSubscriptionID   = $donationFormModel->isRecurringDonation() ? $subscription->id : null;
        $billingAddress         = $donationFormModel->getBillingAddress();
        $shippingAddress        = $donationFormModel->getShippingAddress();

        $description = $this->getTruncatedDescriptionFromPaymentIntent( $paymentIntent );
        $data        = array(
            'stripeCustomerID'           => $stripeCustomerID,
            'stripeSubscriptionID'       => $stripeSubscriptionID,
            'stripePaymentIntentID'      => $paymentIntent->id,
            'stripeSetupIntentID'        => $donationFormModel->getStripeSetupIntentId(),
            'stripePlanID'               => $donationFormModel->isRecurringDonation() ? $subscription->plan->id : null,
            'description'                => $description,
            'paymentMethod'              => 'card',
            'paid'                       => $lastCharge->paid,
            'captured'                   => $lastCharge->captured,
            'refunded'                   => $lastCharge->refunded,
            'expired'                    => false,
            'failureCode'                => $lastCharge->failure_code,
            'failureMessage'             => $lastCharge->failure_message,
            'lastChargeStatus'           => $lastCharge->status,
            'currency'                   => $paymentIntent->currency,
            'amount'                     => $paymentIntent->amount,
            'donationFrequency'          => $donationFormModel->getDonationFrequency(),
            'subscriptionStatus'         => $donationFormModel->isRecurringDonation() ? $subscription->status : null,
            'name'                       => $donationFormModel->getCardHolderName(),
            'email'                      => $donationFormModel->getCardHolderEmail(),
            'billingName'                => $donationFormModel->getBillingName(),
            'addressLine1'               => is_null( $billingAddress ) ? null : $billingAddress['line1'],
            'addressLine2'               => is_null( $billingAddress ) ? null : $billingAddress['line2'],
            'addressCity'                => is_null( $billingAddress ) ? null : $billingAddress['city'],
            'addressState'               => is_null( $billingAddress ) ? null : $billingAddress['state'],
            'addressCountry'             => is_null( $billingAddress ) ? null : $billingAddress['country'],
            'addressCountryCode'         => is_null( $billingAddress ) ? null : $billingAddress['country_code'],
            'addressZip'                 => is_null( $billingAddress ) ? null : $billingAddress['zip'],
            'shippingName'               => $donationFormModel->getShippingName(),
            'shippingAddressLine1'       => is_null( $shippingAddress ) ? null : $shippingAddress['line1'],
            'shippingAddressLine2'       => is_null( $shippingAddress ) ? null : $shippingAddress['line2'],
            'shippingAddressCity'        => is_null( $shippingAddress ) ? null : $shippingAddress['city'],
            'shippingAddressState'       => is_null( $shippingAddress ) ? null : $shippingAddress['state'],
            'shippingAddressCountry'     => is_null( $shippingAddress ) ? null : $shippingAddress['country'],
            'shippingAddressCountryCode' => is_null( $shippingAddress ) ? null : $shippingAddress['country_code'],
            'shippingAddressZip'         => is_null( $shippingAddress ) ? null : $shippingAddress['zip'],
            'created'                    => date( self::DATE_FORMAT_DATABASE, $paymentIntent->created ),
            'livemode'                   => $paymentIntent->livemode,
            'formId'                     => $donationFormModel->getForm()->donationFormID,
            'formType'                   => MM_WPFS::FORM_TYPE_INLINE_DONATION,
            'formName'                   => $donationFormModel->getForm()->name,
            'ipAddressSubmit'            => $donationFormModel->getIpAddress(),
	        'customFields'               => $donationFormModel->getCustomFieldsJSON(),
            'phoneNumber'                => empty( $donationFormModel->getCardHolderPhone() ) ? null : $donationFormModel->getCardHolderPhone()
        );

        $insertResult = $wpdb->insert( $wpdb->prefix . 'fullstripe_donations', apply_filters( 'fullstripe_insert_donation_data', $data ) );
        self::handleDbError( $insertResult, __FUNCTION__ . '(): an error occurred during insert!' );

        return $insertResult;
    }

    /**
     * @param MM_WPFS_Public_DonationFormModel $donationFormModel
     * @param \StripeWPFS\PaymentIntent $paymentIntent
     * @param \StripeWPFS\Subscription $subscription
     *
     * @return mixed
     * @throws Exception
     */
    public function insertCheckoutDonation( $donationFormModel, $paymentIntent, $subscription, $lastCharge ) {
        global $wpdb;

        $stripeCustomerID       = $donationFormModel->getStripeCustomer()->id;
        $stripeSubscriptionID   = $donationFormModel->isRecurringDonation() ? $subscription->id : null;
        $subscriptionStatus     = $donationFormModel->isRecurringDonation() ? $subscription->status : null;
        $billingAddress         = $donationFormModel->getBillingAddress();
        $shippingAddress        = $donationFormModel->getShippingAddress();

        $description = $this->getTruncatedDescriptionFromPaymentIntent( $paymentIntent );
        $data        = array(
            'stripeCustomerID'           => $stripeCustomerID,
            'stripeSubscriptionID'       => $stripeSubscriptionID,
            'stripePaymentIntentID'      => $paymentIntent->id,
            'stripeSetupIntentID'        => $donationFormModel->getStripeSetupIntentId(),
            'stripePlanID'               => $donationFormModel->isRecurringDonation() ? $subscription->plan->id : null,
            'description'                => $description,
            'paymentMethod'              => 'card',
            'paid'                       => $lastCharge->paid,
            'captured'                   => $lastCharge->captured,
            'refunded'                   => $lastCharge->refunded,
            'expired'                    => false,
            'failureCode'                => $lastCharge->failure_code,
            'failureMessage'             => $lastCharge->failure_message,
            'lastChargeStatus'           => $lastCharge->status,
            'currency'                   => $paymentIntent->currency,
            'amount'                     => $paymentIntent->amount,
            'donationFrequency'          => $donationFormModel->getDonationFrequency(),
            'subscriptionStatus'         => $subscriptionStatus,
            'name'                       => $donationFormModel->getCardHolderName(),
            'email'                      => $donationFormModel->getCardHolderEmail(),
            'billingName'                => $donationFormModel->getBillingName(),
            'addressLine1'               => is_null( $billingAddress ) ? null : $billingAddress['line1'],
            'addressLine2'               => is_null( $billingAddress ) ? null : $billingAddress['line2'],
            'addressCity'                => is_null( $billingAddress ) ? null : $billingAddress['city'],
            'addressState'               => is_null( $billingAddress ) ? null : $billingAddress['state'],
            'addressCountry'             => is_null( $billingAddress ) ? null : $billingAddress['country'],
            'addressCountryCode'         => is_null( $billingAddress ) ? null : $billingAddress['country_code'],
            'addressZip'                 => is_null( $billingAddress ) ? null : $billingAddress['zip'],
            'shippingName'               => $donationFormModel->getShippingName(),
            'shippingAddressLine1'       => is_null( $shippingAddress ) ? null : $shippingAddress['line1'],
            'shippingAddressLine2'       => is_null( $shippingAddress ) ? null : $shippingAddress['line2'],
            'shippingAddressCity'        => is_null( $shippingAddress ) ? null : $shippingAddress['city'],
            'shippingAddressState'       => is_null( $shippingAddress ) ? null : $shippingAddress['state'],
            'shippingAddressCountry'     => is_null( $shippingAddress ) ? null : $shippingAddress['country'],
            'shippingAddressCountryCode' => is_null( $shippingAddress ) ? null : $shippingAddress['country_code'],
            'shippingAddressZip'         => is_null( $shippingAddress ) ? null : $shippingAddress['zip'],
            'created'                    => date( self::DATE_FORMAT_DATABASE, $paymentIntent->created ),
            'livemode'                   => $paymentIntent->livemode,
            'formId'                     => $donationFormModel->getForm()->checkoutDonationFormID,
            'formType'                   => MM_WPFS::FORM_TYPE_CHECKOUT_DONATION,
            'formName'                   => $donationFormModel->getForm()->name,
            'ipAddressSubmit'            => $donationFormModel->getIpAddress(),
	        'customFields'               => $donationFormModel->getCustomFieldsJSON(),
            'phoneNumber'                => empty( $donationFormModel->getCardHolderPhone() ) ? null : $donationFormModel->getCardHolderPhone()
        );

        $insertResult = $wpdb->insert( $wpdb->prefix . 'fullstripe_donations', apply_filters( 'fullstripe_insert_checkout_donation_data', $data ) );
        self::handleDbError( $insertResult, __FUNCTION__ . '(): an error occurred during insert!' );

        return $insertResult;
    }

    protected function getTruncatedDescriptionFromPaymentIntent( $paymentIntent ) {
        $description = isset( $paymentIntent->description ) && ! empty( $paymentIntent->description ) ? $paymentIntent->description : '';

        return MM_WPFS_Utils::truncateString( $description, 255 );
    }

    /**
     * @param $paymentFormModel MM_WPFS_Public_PaymentFormModel
     * @param $transactionData MM_WPFS_PaymentTransactionData
     * @return bool|int
     * @throws Exception
     */
	public function insertPayment( $paymentFormModel, $transactionData, $lastCharge ) {
		global $wpdb;

        /** @var \StripeWPFS\PaymentIntent */
		$paymentIntent = $paymentFormModel->getStripePaymentIntent();

        $billingAddress     = $paymentFormModel->getBillingAddress( false );
        $shippingAddress    = $paymentFormModel->getShippingAddress( false );
		$description        = $this->getTruncatedDescriptionFromPaymentIntent( $paymentIntent );

		$data = array(
			'eventID'                    => $paymentIntent->id,
			'description'                => $description,
			'payment_method'             => 'card',
			'paid'                       => $lastCharge->paid,
			'captured'                   => $lastCharge->captured,
			'refunded'                   => $lastCharge->refunded,
			'expired'                    => false,
			'failure_code'               => $lastCharge->failure_code,
			'failure_message'            => $lastCharge->failure_message,
			'livemode'                   => $paymentIntent->livemode,
			'last_charge_status'         => $lastCharge->status,
			'currency'                   => $paymentIntent->currency,
			'amount'                     => $paymentIntent->amount,
			'fee'                        => ( isset( $paymentIntent->fee ) && ! is_null( $paymentIntent->fee ) ) ? $paymentIntent->fee : 0,
			'priceId'                    => $paymentFormModel->getPriceId(),
			'coupon'                     => empty( $transactionData->getCouponCode() ) ? null : $transactionData->getCouponCode(),
			'billingName'                => $paymentFormModel->getBillingName(),
			'addressLine1'               => $billingAddress['line1'],
			'addressLine2'               => $billingAddress['line2'],
			'addressCity'                => $billingAddress['city'],
			'addressState'               => $billingAddress['state'],
			'addressCountry'             => $billingAddress['country'],
			'addressCountryCode'         => $billingAddress['country_code'],
			'addressZip'                 => $billingAddress['zip'],
			'shippingName'               => $paymentFormModel->getShippingName(),
			'shippingAddressLine1'       => $shippingAddress['line1'],
			'shippingAddressLine2'       => $shippingAddress['line2'],
			'shippingAddressCity'        => $shippingAddress['city'],
			'shippingAddressState'       => $shippingAddress['state'],
			'shippingAddressCountry'     => $shippingAddress['country'],
			'shippingAddressCountryCode' => $shippingAddress['country_code'],
			'shippingAddressZip'         => $shippingAddress['zip'],
			'created'                    => date( self::DATE_FORMAT_DATABASE, $paymentIntent->created ),
			'stripeCustomerID'           => $paymentFormModel->getStripeCustomer()->id,
			'name'                       => $paymentFormModel->getCardHolderName(),
			'email'                      => $paymentFormModel->getCardHolderEmail(),
			'formId'                     => MM_WPFS_Utils::getFormId( $paymentFormModel->getForm() ),
			'formType'                   => MM_WPFS::FORM_TYPE_PAYMENT,
			'formName'                   => $paymentFormModel->getFormName(),
            'ipAddressSubmit'            => $paymentFormModel->getIpAddress(),
			'customFields'               => $paymentFormModel->getCustomFieldsJSON(),
            'phoneNumber'                => empty( $paymentFormModel->getCardHolderPhone() ) ? null : $paymentFormModel->getCardHolderPhone()
		);

		$insertResult = $wpdb->insert( $wpdb->prefix . 'fullstripe_payments', apply_filters( 'fullstripe_insert_payment_data', $data ) );
		self::handleDbError( $insertResult, __FUNCTION__ . '(): an error occurred during insert!' );

		return $insertResult;
	}

    /**
     * @param $subscriptionFormModel MM_WPFS_Public_SubscriptionFormModel
     * @param $transactionData MM_WPFS_SubscriptionTransactionData
     * @return bool|int
     *
     * @throws Exception
     */
	public function insertSubscriber( $subscriptionFormModel, $transactionData ) {
        $stripeCustomer         = $subscriptionFormModel->getStripeCustomer();
        $stripeSubscription     = $subscriptionFormModel->getStripeSubscription();
        $stripePaymentIntent    = $subscriptionFormModel->getStripePaymentIntent();
        $stripeSetupIntent      = $subscriptionFormModel->getStripeSetupIntent();

        $billingAddress         = $subscriptionFormModel->getBillingAddress( false );
        $shippingAddress        = $subscriptionFormModel->getShippingAddress( false );

		$data = array(
			'stripeCustomerID'           => $stripeCustomer->id,
			'stripeSubscriptionID'       => $transactionData->getTransactionId(),
			'stripePaymentIntentID'      => isset( $stripePaymentIntent ) ? $stripePaymentIntent->id : null,
			'stripeSetupIntentID'        => isset( $stripeSetupIntent ) ? $stripeSetupIntent->id : null,
			'chargeMaximumCount'         => $subscriptionFormModel->getCancellationCount(),
			'chargeCurrentCount'         => 0,
            'invoiceCreatedCount'        => 0,
			'status'                     => MM_WPFS::SUBSCRIBER_STATUS_INCOMPLETE,
			'name'                       => $subscriptionFormModel->getCardHolderName(),
			'email'                      => $stripeCustomer->email,
			'planID'                     => $stripeSubscription->plan->id,
			'quantity'                   => $stripeSubscription->quantity,
			'coupon'                     => empty( $transactionData->getCouponCode() ) ? null : $transactionData->getCouponCode(),
			'billingName'                => $subscriptionFormModel->getBillingName(),
			'addressLine1'               => $billingAddress['line1'],
			'addressLine2'               => $billingAddress['line2'],
			'addressCity'                => $billingAddress['city'],
			'addressState'               => $billingAddress['state'],
			'addressCountry'             => $billingAddress['country'],
			'addressCountryCode'         => $billingAddress['country_code'],
			'addressZip'                 => $billingAddress['zip'],
			'shippingName'               => $subscriptionFormModel->getShippingName(),
			'shippingAddressLine1'       => $shippingAddress['line1'],
			'shippingAddressLine2'       => $shippingAddress['line2'],
			'shippingAddressCity'        => $shippingAddress['city'],
			'shippingAddressState'       => $shippingAddress['state'],
			'shippingAddressCountry'     => $shippingAddress['country'],
			'shippingAddressCountryCode' => $shippingAddress['country_code'],
			'shippingAddressZip'         => $shippingAddress['zip'],
			'created'                    => date(self::DATE_FORMAT_DATABASE, $stripeSubscription->created ),
			'livemode'                   => $stripeCustomer->livemode,
			'formId'                     => MM_WPFS_Utils::getFormId( $subscriptionFormModel->getForm() ),
			'formName'                   => $subscriptionFormModel->getForm()->name,
            'ipAddressSubmit'            => $subscriptionFormModel->getIpAddress(),
			'customFields'               => $subscriptionFormModel->getCustomFieldsJSON(),
            'phoneNumber'                => empty( $subscriptionFormModel->getCardHolderPhone() ) ? null : $subscriptionFormModel->getCardHolderPhone()
        );

		global $wpdb;
		$insertResult = $wpdb->insert( $wpdb->prefix . 'fullstripe_subscribers', apply_filters( 'fullstripe_insert_subscriber_data', $data ) );
		self::handleDbError( $insertResult, __FUNCTION__ . '(): an error occurred during insert!' );

		return $insertResult;
	}

	/**
	 * @param $stripePaymentIntentId
	 *
	 * @return bool|int
	 * @throws Exception
	 */
	public function updateSubscriptionByPaymentIntentToRunning( $stripePaymentIntentId ) {
		global $wpdb;
		$queryResult = $wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->prefix}fullstripe_subscribers SET status=%s WHERE stripePaymentIntentID=%s",
				MM_WPFS::SUBSCRIBER_STATUS_RUNNING,
				$stripePaymentIntentId
			)
		);
		self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $queryResult;
	}

	/**
	 * @param $stripeSetupIntentId
	 *
	 * @return bool|int
	 * @throws Exception
	 */
	public function updateSubscriptionBySetupIntentToRunning( $stripeSetupIntentId ) {
		global $wpdb;
		$queryResult = $wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->prefix}fullstripe_subscribers SET status=%s WHERE stripeSetupIntentID=%s",
				MM_WPFS::SUBSCRIBER_STATUS_RUNNING,
				$stripeSetupIntentId
			)
		);
		self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $queryResult;
	}

	/**
	 * @param $id
	 * @param $subscriber
	 *
	 * @return mixed
	 * @throws Exception
	 */
	function updateSubscriber( $id, $subscriber ) {
		global $wpdb;
		$updateResult = $wpdb->update( $wpdb->prefix . 'fullstripe_subscribers', $subscriber, array( 'subscriberID' => $id ) );
		self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $updateResult;
	}

	/**
	 *
	 * @param $form
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function insertInlineSubscriptionForm($form ) {
		global $wpdb;
		$insertResult = $wpdb->insert( $wpdb->prefix . 'fullstripe_subscription_forms', $form );
		self::handleDbError( $insertResult, __FUNCTION__ . '(): an error occurred during insert!' );

		return $insertResult;
	}

	/**
	 *
	 * @param $id
	 * @param $form
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function updateInlineSubscriptionForm($id, $form ) {
		global $wpdb;
		$updateResult = $wpdb->update( $wpdb->prefix . 'fullstripe_subscription_forms', $form, array( 'subscriptionFormID' => $id ) );
		self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $updateResult;
	}

	/**
	 * @param $form
	 *
	 * @return false|int
	 * @throws Exception
	 */
	public function insertCheckoutSubscriptionForm($form ) {
		global $wpdb;
		$insertResult = $wpdb->insert( $wpdb->prefix . 'fullstripe_checkout_subscription_forms', $form );
		self::handleDbError( $insertResult, __FUNCTION__ . '()): an error occurred during insert!' );

		return $insertResult;
	}

    /**
     * @param $id
     * @param $form
     * @return bool|int
     *
     * @throws Exception
     */
	public function updateCheckoutSubscriptionForm($id, $form ) {
		global $wpdb;
        unset($form['stripeElementsTheme']);
        unset($form['stripeElementsFont']);
		$updateResult = $wpdb->update( $wpdb->prefix . 'fullstripe_checkout_subscription_forms', $form, array( 'checkoutSubscriptionFormID' => $id ) );
		self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $updateResult;
	}

	/**
	 *
	 * @param $form
	 *
	 * @return mixed
	 * @throws Exception
	 */
	function insertInlinePaymentForm($form ) {
		global $wpdb;
		$insertResult = $wpdb->insert( $wpdb->prefix . 'fullstripe_payment_forms', $form );
		self::handleDbError( $insertResult, __FUNCTION__ . '(): an error occurred during insert.' );

		return $insertResult;
	}

    /**
     *
     * @param $form
     *
     * @return mixed
     * @throws Exception
     */
    function insertCheckoutPaymentForm( $form ) {
        global $wpdb;
        $insertResult = $wpdb->insert( $wpdb->prefix . 'fullstripe_checkout_forms', $form );
        self::handleDbError( $insertResult, __FUNCTION__ . '(): an error occurred during insert.' );

        return $insertResult;
    }

    /**
     *
     * @param $form
     *
     * @return mixed
     * @throws Exception
     */
    function insertInlineDonationForm( $form ) {
        global $wpdb;

        $insertResult = $wpdb->insert( $wpdb->prefix . 'fullstripe_donation_forms', $form );
        self::handleDbError( $insertResult, __FUNCTION__ . '(): an error occurred during insert.' );

        return $insertResult;
    }

    /**
     *
     * @param $form
     *
     * @return mixed
     * @throws Exception
     */
    function insertCheckoutDonationForm( $form ) {
        global $wpdb;

        $insertResult = $wpdb->insert( $wpdb->prefix . 'fullstripe_checkout_donation_forms', $form );
        self::handleDbError( $insertResult, __FUNCTION__ . '(): an error occurred during insert.' );

        return $insertResult;
    }

    /**
     *
     * @param $id
     * @param $form
     *
     * @return mixed
     * @throws Exception
     */
    function updateInlineDonationForm($id, $form ) {
        global $wpdb;

        $updateResult = $wpdb->update( $wpdb->prefix . 'fullstripe_donation_forms', $form, array( 'donationFormID' => $id ) );
        self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during update!' );

        return $updateResult;
    }

    /**
     *
     * @param $id
     * @param $form
     *
     * @return mixed
     * @throws Exception
     */
    function updateCheckoutDonationForm($id, $form ) {
        global $wpdb;
        unset($form['stripeElementsTheme']);
        unset($form['stripeElementsFont']);

        $updateResult = $wpdb->update( $wpdb->prefix . 'fullstripe_checkout_donation_forms', $form, array( 'checkoutDonationFormID' => $id ) );
        self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during update!' );

        return $updateResult;
    }


    /**
	 *
	 * @param $id
	 * @param $form
	 *
	 * @return mixed
	 * @throws Exception
	 */
	function updateInlinePaymentForm( $id, $form ) {
		global $wpdb;

        $updateResult = $wpdb->update( $wpdb->prefix . 'fullstripe_payment_forms', $form, array( 'paymentFormID' => $id ) );
		self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $updateResult;
	}

	/**
	 *
	 * @param $form
	 *
	 * @return mixed
	 * @throws Exception
	 */
	function insert_checkout_form( $form ) {
		global $wpdb;

		$insertResult = $wpdb->insert( $wpdb->prefix . 'fullstripe_checkout_forms', $form );
		self::handleDbError( $insertResult, __FUNCTION__ . '(): an error occurred during insert!' );

		return $insertResult;
	}

	/**
	 *
	 * @param $id
	 * @param $form
	 *
	 * @return mixed
	 * @throws Exception
	 */
	function updateCheckoutPaymentForm($id, $form ) {
		global $wpdb;
        unset($form['stripeElementsTheme']);
        unset($form['stripeElementsFont']);
        file_put_contents('checout.json', json_encode($form, JSON_PRETTY_PRINT));

		$updateResult = $wpdb->update( $wpdb->prefix . 'fullstripe_checkout_forms', $form, array( 'checkoutFormID' => $id ) );
		self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $updateResult;
	}

	/**
	 *
	 * @param $id
	 *
	 * @return mixed
	 * @throws Exception
	 */
	function deleteInlinePaymentForm($id ) {
		global $wpdb;
		$queryResult = $wpdb->query( 'DELETE FROM ' . $wpdb->prefix . 'fullstripe_payment_forms' . " WHERE paymentFormID='" . $id . "';" );
		self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during delete!' );

		return $queryResult;
	}

    /**
     *
     * @param $id
     *
     * @return mixed
     * @throws Exception
     */
    function deleteInlineDonationForm( $id ) {
        global $wpdb;
        $queryResult = $wpdb->query( 'DELETE FROM ' . $wpdb->prefix . 'fullstripe_donation_forms' . " WHERE donationFormID='" . $id . "';" );
        self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during delete!' );

        return $queryResult;
    }

    /**
     *
     * @param $id
     *
     * @return mixed
     * @throws Exception
     */
    function deleteCheckoutDonationForm( $id ) {
        global $wpdb;
        $queryResult = $wpdb->query( 'DELETE FROM ' . $wpdb->prefix . 'fullstripe_checkout_donation_forms' . " WHERE checkoutDonationFormID='" . $id . "';" );
        self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during delete!' );

        return $queryResult;
    }

    /**
	 *
	 * @param $id
	 *
	 * @return mixed
	 * @throws Exception
	 */
	function deleteInlineSubscriptionForm($id ) {
		global $wpdb;
		$queryResult = $wpdb->query( 'DELETE FROM ' . $wpdb->prefix . 'fullstripe_subscription_forms' . " WHERE subscriptionFormID='" . $id . "';" );
		self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during delete!' );

		return $queryResult;
	}

	/**
	 *
	 * @param $id
	 *
	 * @return mixed
	 * @throws Exception
	 */
	function deleteCheckoutPaymentForm($id ) {
		global $wpdb;
		$queryResult = $wpdb->query( 'DELETE FROM ' . $wpdb->prefix . 'fullstripe_checkout_forms' . " WHERE checkoutFormID='" . $id . "';" );
		self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during delete!' );

		return $queryResult;
	}

	/**
	 * @param $id
	 *
	 * @return false|int
	 * @throws Exception
	 */
	function deleteCheckoutSubscriptionForm($id ) {
		global $wpdb;
		$queryResult = $wpdb->query( 'DELETE FROM ' . $wpdb->prefix . 'fullstripe_checkout_subscription_forms' . " WHERE checkoutSubscriptionFormID='" . $id . "';" );
		self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during delete!' );

		return $queryResult;
	}

	/**
	 * @param $id
	 *
	 * @return false|int
	 * @throws Exception
	 */
	function cancelSubscription( $id ) {
		global $wpdb;
		$queryResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_subscribers SET status=%s WHERE subscriberID=%d", MM_WPFS::SUBSCRIBER_STATUS_CANCELLED, $id ) );
		self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $queryResult;
	}

    /**
     * @param $id
     *
     * @return false|int
     * @throws Exception
     */
    function cancelDonationByDonationId( $id ) {
        global $wpdb;
        $queryResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_donations SET subscriptionStatus=%s WHERE donationID=%d", MM_WPFS::SUBSCRIBER_STATUS_CANCELLED, $id ) );
        self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during update!' );

        return $queryResult;
    }

    /**
	 * @param $id
	 *
	 * @return false|int
	 * @throws Exception
	 */
	function deleteSubscriptionById($id ) {
		global $wpdb;
		$queryResult = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}fullstripe_subscribers WHERE subscriberID=%d", $id ) );
		self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during delete!' );

		return $queryResult;
	}

	/**
	 * @param $stripeSubscriptionID
	 *
	 * @return false|int
	 * @throws Exception
	 */
	public function cancelSubscriptionByStripeSubscriptionId($stripeSubscriptionID ) {
		global $wpdb;
		$queryResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_subscribers SET status=%s,cancelled=NOW() WHERE stripeSubscriptionID=%s", MM_WPFS::SUBSCRIBER_STATUS_CANCELLED, $stripeSubscriptionID ) );
		self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $queryResult;
	}

    /**
     * @param $stripeSubscriptionID
     *
     * @return false|int
     * @throws Exception
     */
    public function cancelDonationByStripeSubscriptionId( $stripeSubscriptionID ) {
        global $wpdb;
        $queryResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_donations SET subscriptionStatus=%s,cancelled=NOW() WHERE stripeSubscriptionID=%s", MM_WPFS::SUBSCRIBER_STATUS_CANCELLED, $stripeSubscriptionID ) );
        self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during update!' );

        return $queryResult;
    }

    /**
     * @param $stripeSubscriptionID
     *
     * @param $newQuantity
     *
     * @return false|int
     * @throws Exception
     */
    public function updateSubscriptionPlanByStripeSubscriptionId($stripeSubscriptionID, $newPlanId ) {
        global $wpdb;
        $queryResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_subscribers SET planID=%s WHERE stripeSubscriptionID=%s", $newPlanId, $stripeSubscriptionID ) );
        self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during update!' );

        return $queryResult;
    }

    /**
	 * @param $stripeSubscriptionID
	 *
	 * @param $newQuantity
	 *
	 * @return false|int
	 * @throws Exception
	 */
	public function updateSubscriptionPlanAndQuantityByStripeSubscriptionId($stripeSubscriptionID, $newPlanId, $newQuantity ) {
		global $wpdb;
		$queryResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_subscribers SET planID=%s, quantity=%d WHERE stripeSubscriptionID=%s", $newPlanId, $newQuantity, $stripeSubscriptionID ) );
		self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $queryResult;
	}

	/**
	 * @param $stripeSubscriptionID
	 *
	 * @return false|int
	 * @throws Exception
	 */
	public function endSubscription( $stripeSubscriptionID ) {
		global $wpdb;
		$queryResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_subscribers SET status=%s,cancelled=NOW() WHERE stripeSubscriptionID=%s", MM_WPFS::SUBSCRIBER_STATUS_ENDED, $stripeSubscriptionID ) );
		self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $queryResult;
	}

	/**
	 * @param $stripeSubscriptionID
	 *
	 * @return false|int
	 * @throws Exception
	 */
	public function updateSubscriptionWithInvoice( $stripeSubscriptionID ) {
		global $wpdb;
		$queryResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_subscribers SET invoiceCreatedCount=invoiceCreatedCount + 1 WHERE stripeSubscriptionID=%s", $stripeSubscriptionID ) );
		self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $queryResult;
	}

	/**
	 * @param $stripeSubscriptionID
	 *
	 * @return false|int
	 * @throws Exception
	 */
	public function updateSubscriberWithInvoiceAndEvent( $stripeSubscriptionID, $processedStripeEventIDs ) {
		global $wpdb;
		$queryResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_subscribers SET invoiceCreatedCount=invoiceCreatedCount + 1, processedStripeEventIDs=%s WHERE stripeSubscriptionID=%s", $processedStripeEventIDs, $stripeSubscriptionID ) );
		self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $queryResult;
	}


	/**
	 * @param $stripeSubscriptionID
	 * @param $processedStripeEventIDs
	 *
	 * @return bool|int
	 * @throws Exception
	 */
	public function updateSubscriberWithPaymentAndEvent( $stripeSubscriptionID, $processedStripeEventIDs ) {
		global $wpdb;
		$queryResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_subscribers SET chargeCurrentCount=chargeCurrentCount+1, processedStripeEventIDs=%s WHERE stripeSubscriptionID=%s", $processedStripeEventIDs, $stripeSubscriptionID ) );
		self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $queryResult;
	}

	/**
	 * @param $stripeSubscriptionID
	 * @param $processedStripeEventIDs
	 *
	 * @return bool|int
	 * @throws Exception
	 */
	public function updateSubscriberWithEvent( $stripeSubscriptionID, $processedStripeEventIDs ) {
		global $wpdb;
		$queryResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_subscribers SET processedStripeEventIDs=%s WHERE stripeSubscriptionID=%s", $processedStripeEventIDs, $stripeSubscriptionID ) );
		self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $queryResult;
	}

	/**
	 * @param $submitHash
	 * @param $relatedStripeEventIDs
	 *
	 * @return bool|int
	 * @throws Exception
	 */
	public function updatePopupFormSubmitWithEvent( $submitHash, $relatedStripeEventIDs ) {
		global $wpdb;
		$queryResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_checkout_form_submit SET relatedStripeEventIDs=%s WHERE hash=%s", $relatedStripeEventIDs, $submitHash ) );
		self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $queryResult;
	}

	/**
	 * @param $subscriptionId
	 * @param $planId
	 * @param $chargeMaxCount
	 *
	 * @return bool|int
	 * @throws Exception
	 */
	public function updateSubscriptionPlanAndCounters( $subscriptionId, $planId, $chargeMaxCount ) {
		global $wpdb;
		$queryResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_subscribers SET planID=%s, chargeMaximumCount=%s, chargeCurrentCount=0, invoiceCreatedCount=0 WHERE stripeSubscriptionID=%s", $planId, $chargeMaxCount, $subscriptionId ) );
		self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $queryResult;
	}


	/**
	 *
	 * @param $id
	 *
	 * @return mixed
	 * @throws Exception
	 */
	function deletePayment($id ) {
		global $wpdb;
		$queryResult = $wpdb->query( 'DELETE FROM ' . $wpdb->prefix . 'fullstripe_payments' . " WHERE paymentID='" . $id . "';" );
		self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during delete!' );

		return $queryResult;
	}

    /**
     *
     * @param $id
     *
     * @return mixed
     * @throws Exception
     */
    function deleteDonation( $id ) {
        global $wpdb;
        $queryResult = $wpdb->query( 'DELETE FROM ' . $wpdb->prefix . 'fullstripe_donations' . " WHERE donationID='" . $id . "';" );
        self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during delete!' );

        return $queryResult;
    }

    ////////////////////////////////////////////////////////////////////////////////////////////

	function deleteSavedCard($id ) {
		global $wpdb;
		$queryResult = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}fullstripe_card_captures WHERE captureID=%d", $id ) );
		self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during delete!' );

		return $queryResult;
	}

	function getInlineSaveCardFormsAsArray() {
        global $wpdb;

        $queryResult = $wpdb->get_results( "SELECT * from {$wpdb->prefix}fullstripe_payment_forms where customAmount='card_capture';", ARRAY_A );
		self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during querying inline save card forms!' );

		return $queryResult;
    }

    /**
     *
     * @param $id
     *
     * @return mixed
     */
    function getInlinePaymentFormAsArrayById( $id ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_payment_forms WHERE paymentFormID=%s", $id), ARRAY_A );
    }

    /**
     *
     * @param $id
     *
     * @return mixed
     */
    function getInlinePaymentFormById( $id ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_payment_forms WHERE paymentFormID=%s", $id), OBJECT );
    }

    /**
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
	function getInlinePaymentFormByName($name ) {
		global $wpdb;

		return $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "fullstripe_payment_forms" . " WHERE name='" . $name . "';" );
	}

    /**
     * @return array|object|null
     *
     * @throws Exception
     */
    function getInlinePaymentFormsAsArray() {
        global $wpdb;

        $queryResult = $wpdb->get_results( "SELECT * from {$wpdb->prefix}fullstripe_payment_forms where customAmount != 'card_capture';", ARRAY_A );
        self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during querying inline payment forms!' );

        return $queryResult;
    }

    /**
     *
     * @param $id
     *
     * @return mixed
     */
    function getInlineSubscriptionFormById( $id ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_subscription_forms WHERE subscriptionFormID=%s", $id), OBJECT );
    }

    /**
     *
     * @param $id
     *
     * @return mixed
     */
    function getInlineSubscriptionFormAsArrayById( $id ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_subscription_forms WHERE subscriptionFormID=%s", $id), ARRAY_A );
    }

    /**
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
	function getInlineSubscriptionFormByName($name ) {
		global $wpdb;

		return $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "fullstripe_subscription_forms" . " WHERE name='" . $name . "';" );
	}

    /**
     * @return array|object|null
     *
     * @throws Exception
     */
    function getInlineSubscriptionFormsAsArray() {
        global $wpdb;

        $queryResult = $wpdb->get_results( "SELECT * from {$wpdb->prefix}fullstripe_subscription_forms;", ARRAY_A );
        self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during querying inline subscription forms!' );

        return $queryResult;
    }

    /**
	 * @param $formId
	 *
	 * @return array|null|object|void
	 */
	public function getCheckoutSubscriptionFormById($formId ) {
		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_checkout_subscription_forms WHERE checkoutSubscriptionFormID=%d", $formId ) );
	}

    /**
     *
     * @param $id
     *
     * @return mixed
     */
    function getCheckoutSubscriptionFormAsArrayById( $id ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_checkout_subscription_forms WHERE checkoutSubscriptionFormID=%s", $id), ARRAY_A );
    }

    /**
	 * @param $formName
	 *
	 * @return mixed
	 */
	public function getCheckoutPaymentFormByName($formName ) {
		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_checkout_forms WHERE name=%s", $formName ) );
	}

    /**
     * @param $id
     *
     * @return array|null
     */
    public function getCheckoutPaymentFormAsArrayById( $id ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_checkout_forms WHERE checkoutFormID=%s", $id), ARRAY_A );
    }

    /**
     * @param $id
     * @return object|null
     */
    public function getCheckoutPaymentFormById( $id ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_checkout_forms WHERE checkoutFormID=%s", $id), OBJECT );
    }

    /**
     * @return array|object|null
     *
     * @throws Exception
     */
    function getCheckoutPaymentFormsAsArray() {
        global $wpdb;

        $queryResult = $wpdb->get_results( "SELECT * from {$wpdb->prefix}fullstripe_checkout_forms where customAmount != 'card_capture';", ARRAY_A );
        self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during querying checkout payment forms!' );

        return $queryResult;
    }

    /**
     * @return array|object|null
     *
     * @throws Exception
     */
    function getCheckoutSaveCardFormsAsArray() {
        global $wpdb;

        $queryResult = $wpdb->get_results( "SELECT * from {$wpdb->prefix}fullstripe_checkout_forms where customAmount='card_capture';", ARRAY_A );
        self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during querying checkout save card forms!' );

        return $queryResult;
    }

    /**
	 * @param $formName
	 *
	 * @return array|null|object|void
	 */
	public function getCheckoutSubscriptionFormByName($formName ) {
		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_checkout_subscription_forms WHERE name=%s", $formName ) );
	}

    /**
     * @return array|object|null
     *
     * @throws Exception
     */
    function getCheckoutSubscriptionFormsAsArray() {
        global $wpdb;

        $queryResult = $wpdb->get_results( "SELECT * from {$wpdb->prefix}fullstripe_checkout_subscription_forms;", ARRAY_A );
        self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during querying checkout subscription forms!' );

        return $queryResult;
    }

    /**
     *
     * @param $id
     *
     * @return mixed
     */
    function getInlineDonationFormAsArrayById( $id ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_donation_forms WHERE donationFormID=%s", $id), ARRAY_A );
    }

    /**
     * @return array|object|null
     *
     * @throws Exception
     */
    function getInlineDonationFormsAsArray() {
        global $wpdb;

        $queryResult = $wpdb->get_results( "SELECT * from {$wpdb->prefix}fullstripe_donation_forms;", ARRAY_A );
        self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during querying inline donation forms!' );

        return $queryResult;
    }

    /**
     * @return array|object|null
     *
     * @throws Exception
     */
    function getCheckoutDonationFormsAsArray() {
        global $wpdb;

        $queryResult = $wpdb->get_results( "SELECT * from {$wpdb->prefix}fullstripe_checkout_donation_forms;", ARRAY_A );
        self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during querying checkout donation forms!' );

        return $queryResult;
    }

    /**
     *
     * @param $id
     *
     * @return mixed
     */
    function getInlineDonationFormById( $id ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_donation_forms WHERE donationFormID=%s", $id), OBJECT );
    }

    /**
     * @param $formName
     *
     * @return array|null|object|void
     */
    public function getInlineDonationFormByName($formName ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_donation_forms WHERE name=%s", $formName ) );
    }

    /**
     *
     * @param $id
     *
     * @return mixed
     */
    function getCheckoutDonationFormAsArrayById( $id ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_checkout_donation_forms WHERE checkoutDonationFormID=%s", $id), ARRAY_A );
    }

    /**
     * @param $formName
     *
     * @return array|null|object|void
     */
    public function getCheckoutDonationFormByName($formName ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_checkout_donation_forms WHERE name=%s", $formName ) );
    }

    /**
     * @param $id
     *
     * @return array|object|void|null
     */
    public function getCheckoutDonationFormById($id ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_checkout_donation_forms WHERE checkoutDonationFormID=%s", $id ) );
    }

    /**
	 *
	 * @param $email
	 * @param $livemode
	 *
	 * @return null
	 */
	public function get_customer_id_from_payments( $email, $livemode ) {
		global $wpdb;
		$id      = null;
		$payment = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "fullstripe_payments" . " WHERE email='" . $email . "' AND livemode=" . ( $livemode ? '1' : '0' ) . ";" );
		if ( $payment ) {
			// if no ID set, will be set to null.
			$id = $payment->stripeCustomerID;
		}

		return $id;
	}

	/**
	 *
	 * search payments and subscribers table for existing customer
	 *
	 * @param $email
	 * @param $livemode
	 *
	 * @return null
	 */
	public function find_existing_stripe_customer_by_email( $email, $livemode ) {
		global $wpdb;
		$subscriber = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "fullstripe_subscribers" . " WHERE email='" . $email . "' AND livemode=" . ( $livemode ? '1' : '0' ) . ";", ARRAY_A );
		if ( $subscriber ) {
			$subscriber['is_subscriber'] = true;

			return $subscriber;
		} else {
			$payment = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "fullstripe_payments" . " WHERE email='" . $email . "' AND livemode=" . ( $livemode ? '1' : '0' ) . ";", ARRAY_A );
			if ( $payment ) {
				$subscriber['is_subscriber'] = false;

				return $payment;
			}
		}

		return null;
	}

	/**
	 *
	 * return customers from the payment and subscriber tables where the email address and the mode match
	 *
	 * @param $email
	 * @param $livemode
	 *
	 * @return null
	 */
	public function getExistingStripeCustomersByEmail($email, $livemode ) {
		global $wpdb;

		$subscribers = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_subscribers WHERE UPPER(email)=%s AND livemode=%s GROUP BY StripeCustomerID;", strtoupper($email), $livemode ? '1' : '0' ), ARRAY_A );
        $donors      = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_donations WHERE UPPER(email)=%s AND livemode=%s GROUP BY StripeCustomerID;", strtoupper($email), $livemode ? '1' : '0' ), ARRAY_A );
		$payees      = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_payments WHERE UPPER(email)=%s AND livemode=%s GROUP BY StripeCustomerID;", strtoupper($email), $livemode ? '1' : '0' ), ARRAY_A );
		$cards       = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_card_captures WHERE UPPER(email)=%s AND livemode=%s GROUP BY StripeCustomerID;", strtoupper($email), $livemode ? '1' : '0' ), ARRAY_A );

		$result = array_merge( $subscribers, $donors, $payees, $cards );

		return $result;
	}

	/**
	 * @param $id
	 *
	 * @return array|null|object|void
	 */
	public function findSubscriberById($id ) {
		global $wpdb;
		$subscription = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_subscribers WHERE subscriberID=%d", $id ) );

		return $subscription;
	}

	/**
	 * @param $stripePaymentIntentId
	 *
	 * @return array|null|object|void
	 */
	public function findSubscriberByPaymentIntentId($stripePaymentIntentId ) {
		global $wpdb;
		$subscription = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}fullstripe_subscribers WHERE stripePaymentIntentId=%s",
				$stripePaymentIntentId
			)
		);

		return $subscription;
	}

	/**
	 * @param $stripeSetupIntentId
	 *
	 * @return array|null|object|void
	 */
	public function findSubscriberBySetupIntentId($stripeSetupIntentId ) {
		global $wpdb;
		$subscription = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}fullstripe_subscribers WHERE stripeSetupIntentId=%s",
				$stripeSetupIntentId
			)
		);

		return $subscription;
	}

	/**
	 * @param $stripeSubscriptionId
	 *
	 * @return array|null|object|void
	 */
	public function getSubscriptionByStripeSubscriptionId($stripeSubscriptionId ) {
		global $wpdb;
		$subscription = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_subscribers WHERE stripeSubscriptionID=%s", $stripeSubscriptionId ) );

		return $subscription;
	}

    /**
     * @param $paymentFormModel MM_WPFS_Public_PaymentFormModel
     * @param $transactionData MM_WPFS_SaveCardTransactionData
     *
     * @return bool|int
     * @throws Exception
     */
	public function insertSavedCard($paymentFormModel, $transactionData ) {
		global $wpdb;

		/** @var $stripeCustomer \StripeWPFS\Customer */
		$stripeCustomer = $paymentFormModel->getStripeCustomer();

		$billingAddress  = $paymentFormModel->getBillingAddress( false );
        $shippingAddress = $paymentFormModel->getShippingAddress( false );

		$data = array(
			'livemode'                   => $stripeCustomer->livemode,
			'billingName'                => $paymentFormModel->getBillingName(),
			'addressLine1'               => $billingAddress['line1'],
			'addressLine2'               => $billingAddress['line2'],
			'addressCity'                => $billingAddress['city'],
			'addressState'               => $billingAddress['state'],
			'addressCountry'             => $billingAddress['country'],
			'addressCountryCode'         => $billingAddress['country_code'],
			'addressZip'                 => $billingAddress['zip'],
			'shippingName'               => $paymentFormModel->getShippingName(),
			'shippingAddressLine1'       => $shippingAddress['line1'],
			'shippingAddressLine2'       => $shippingAddress['line2'],
			'shippingAddressCity'        => $shippingAddress['city'],
			'shippingAddressState'       => $shippingAddress['state'],
			'shippingAddressCountry'     => $shippingAddress['country'],
			'shippingAddressCountryCode' => $shippingAddress['country_code'],
			'shippingAddressZip'         => $shippingAddress['zip'],
			'created'                    => date( self::DATE_FORMAT_DATABASE, $stripeCustomer->created ),
			'stripeCustomerID'           => $stripeCustomer->id,
			'name'                       => $paymentFormModel->getCardHolderName(),
			'email'                      => $stripeCustomer->email,
			'formId'                     => MM_WPFS_Utils::getFormId( $paymentFormModel->getForm() ),
			'formType'                   => MM_WPFS::FORM_TYPE_PAYMENT,
			'formName'                   => $paymentFormModel->getFormName(),
            'ipAddressSubmit'            => $paymentFormModel->getIpAddress(),
			'customFields'               => $paymentFormModel->getCustomFieldsJSON()
        );

		$insertResult = $wpdb->insert( $wpdb->prefix . 'fullstripe_card_captures', apply_filters( 'fullstripe_insert_card_data', $data ) );
		self::handleDbError( $insertResult, __FUNCTION__ . '(): an error occurred during insert!' );

		return $insertResult;
	}

	/**
	 * Insert card update session
	 *
	 * @param $email
	 * @param $liveMode
	 * @param $stripeCustomerId
	 * @param $cardUpdateSessionHash
	 *
	 * @return int -1 when insert failed, the inserted record id otherwise
	 * @throws Exception
	 */
	public function insertCustomerPortalSession($email, $liveMode, $stripeCustomerId, $cardUpdateSessionHash ) {
		global $wpdb;

		$insertResult = $wpdb->insert( "{$wpdb->prefix}fullstripe_card_update_session", array(
			'hash'             => $cardUpdateSessionHash,
			'email'            => $email,
			'liveMode'         => $liveMode,
			'stripeCustomerId' => $stripeCustomerId,
			'created'          => current_time( 'mysql' ),
			'status'           => MM_WPFS_CustomerPortalService::SESSION_STATUS_WAITING_FOR_CONFIRMATION
		) );

		self::handleDbError( $insertResult, __FUNCTION__ . '(): an error occurred during insert!' );

		if ( $insertResult === false ) {
			return - 1;
		}

		return $wpdb->insert_id;
	}

	/**
	 * @param string $hash
	 * @param string $formHash
	 * @param string $formType
	 * @param string $referrer
	 * @param string $postData JSON
	 * @param boolean $liveMode
	 *
	 * @return int
	 * @throws Exception
	 */
	public function insertCheckoutFormSubmit($hash, $formHash, $formType, $referrer, $postData, $liveMode ) {
		global $wpdb;

		$insertResult = $wpdb->insert( "{$wpdb->prefix}fullstripe_checkout_form_submit", array(
				'hash'     => $hash,
				'formHash' => $formHash,
				'formType' => $formType,
				'referrer' => $referrer,
				'postData' => $postData,
				'liveMode' => $liveMode,
				'created'  => current_time( 'mysql' ),
				'status'   => MM_WPFS_CheckoutSubmissionService::POPUP_FORM_SUBMIT_STATUS_CREATED
			)
		);

		self::handleDbError( $insertResult, __FUNCTION__ . '(): an error occurred during insert!' );

		if ( $insertResult === false ) {
			return - 1;
		}

		return $wpdb->insert_id;
	}

	/**
	 * @param $hash
	 *
	 * @return array|null|object|void
	 */
	public function findPopupFormSubmitByHash($hash ) {
		global $wpdb;

		$popupFormSubmit = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_checkout_form_submit WHERE hash=%s", $hash ) );

		return $popupFormSubmit;
	}

	/**
	 * @param boolean $liveMode
	 * @param null $limit
	 *
	 * @return array|null|object
	 */
	public function find_popup_form_submits( $liveMode, $limit = null ) {
		global $wpdb;

		if ( is_null( $limit ) ) {
			$preparedQuery = $wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}fullstripe_checkout_form_submit WHERE liveMode=%d AND status<>%s",
				$liveMode,
				MM_WPFS_CheckoutSubmissionService::POPUP_FORM_SUBMIT_STATUS_INTERNAL_ERROR
			);
		} else {
			$preparedQuery = $wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}fullstripe_checkout_form_submit WHERE liveMode=%d AND status<>%s LIMIT %d",
				$liveMode,
				MM_WPFS_CheckoutSubmissionService::POPUP_FORM_SUBMIT_STATUS_INTERNAL_ERROR,
				$limit
			);
		}
		$popupFormSubmits = $wpdb->get_results( $preparedQuery );

		return $popupFormSubmits;
	}

	/**
	 * @param array $idsToDelete
	 *
	 * @return int
	 */
	public function delete_popup_form_submits_by_id( $idsToDelete ) {
		global $wpdb;

		$whereStatement = ' WHERE id IN (' . implode( ', ', array_fill( 0, sizeof( $idsToDelete ), '%s' ) ) . ')';

		$updateResult = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}fullstripe_checkout_form_submit" . $whereStatement, $idsToDelete ) );

		self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during delete!' );

		return $updateResult;
	}

	/**
	 * @param $status
	 * @param array $idsToUpdate
	 *
	 * @return int
	 * @throws Exception
	 */
	public function update_popup_form_submits_with_status_by_id( $status, $idsToUpdate ) {
		global $wpdb;

		$whereStatement = ' WHERE id IN (' . implode( ', ', array_fill( 0, sizeof( $idsToUpdate ), '%s' ) ) . ')';
		$preparedQuery  = $wpdb->prepare(
			"UPDATE {$wpdb->prefix}fullstripe_checkout_form_submit SET status=%s" . $whereStatement,
			array_merge( array( $status ), $idsToUpdate )
		);
		$updateResult   = $wpdb->query( $preparedQuery );

		self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $updateResult;
	}

	/**
	 * @param $popupFormSubmitHash
	 * @param $data
	 *
	 * @return false|int
	 * @throws Exception
	 */
	public function update_popup_form_submit_by_hash( $popupFormSubmitHash, $data ) {
		global $wpdb;

		$updateResult = $wpdb->update( "{$wpdb->prefix}fullstripe_checkout_form_submit", $data, array( 'hash' => $popupFormSubmitHash ) );

		self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $updateResult;
	}

	/**
	 * @param $cardUpdateSessionId
	 * @param $data
	 *
	 * @return false|int
	 * @throws Exception
	 */
	public function updateCustomerPortalSession($cardUpdateSessionId, $data ) {

		global $wpdb;

		$updateResult = $wpdb->update( "{$wpdb->prefix}fullstripe_card_update_session", $data, array( 'id' => $cardUpdateSessionId ) );

		self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $updateResult;
	}

	public function findCustomerPortalSessionByHash($cardUpdateSessionHash ) {
		global $wpdb;

		$cardUpdateSession = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_card_update_session WHERE hash=%s", $cardUpdateSessionHash ) );

		return $cardUpdateSession;
	}

	public function findCustomerPortalSessionsByEmailAndCustomer($email, $stripeCustomerId ) {
		global $wpdb;

		$cardUpdateSession = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}fullstripe_card_update_session WHERE email=%s AND stripeCustomerId=%s",
				$email,
				$stripeCustomerId
			)
		);

		return $cardUpdateSession;
	}

	public function findCustomerPortalSessionsById($cardUpdateSessionId ) {
		global $wpdb;

		$cardUpdateSession = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_card_update_session WHERE id=%s", $cardUpdateSessionId ) );

		return $cardUpdateSession;
	}

	public function insert_security_code( $cardUpdateSessionId, $securityCode ) {
		global $wpdb;

		$insertResult = $wpdb->insert( "{$wpdb->prefix}fullstripe_security_code", array(
			'sessionId'    => $cardUpdateSessionId,
			'securityCode' => $securityCode,
			'created'      => current_time( 'mysql' ),
			'status'       => MM_WPFS_CustomerPortalService::SECURITY_CODE_STATUS_PENDING
		) );

		self::handleDbError( $insertResult, __FUNCTION__ . '(): an error occurred during insert!' );

		if ( $insertResult === false ) {
			return - 1;
		}

		return $wpdb->insert_id;

	}

	public function find_security_codes_by_session( $sessionId ) {
		global $wpdb;

		return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_security_code WHERE sessionId=%d", $sessionId ) );

	}

	public function find_security_code_by_session_and_code( $sessionId, $securityCode ) {
		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_security_code WHERE sessionId=%d AND securityCode=%s", $sessionId, $securityCode ) );

	}

	public function updateSecurityCode($securityCodeId, $data ) {

		global $wpdb;

		$updateResult = $wpdb->update( "{$wpdb->prefix}fullstripe_security_code", $data, array( 'id' => $securityCodeId ) );

		self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $updateResult;
	}

	public function incrementSecurityCodeInput($cardUpdateSessionId ) {
		global $wpdb;

		$updateResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_card_update_session SET securityCodeInput=securityCodeInput+1 WHERE id=%d", $cardUpdateSessionId ) );

		self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $updateResult;
	}

	public function increment_security_code_request( $cardUpdateSessionId ) {
		global $wpdb;

		$updateResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_card_update_session SET securityCodeRequest=securityCodeRequest+1 WHERE id=%d", $cardUpdateSessionId ) );

		self::handleDbError( $updateResult, __FUNCTION__ . '%s: an error occurred during update!' );

		return $updateResult;
	}

	public function invalidateExpiredCustomerPortalSessions($validUntilHour ) {
		global $wpdb;

		$updateResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_card_update_session SET status=%s WHERE created < DATE_SUB(NOW(), INTERVAL %d HOUR)", MM_WPFS_CustomerPortalService::SESSION_STATUS_INVALIDATED, $validUntilHour ) );

		self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $updateResult;
	}

	public function invalidateCustomerPortalSessionsBySecurityCodeRequestLimit($securityCodeRequestLimit ) {
		global $wpdb;

		$updateResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_card_update_session SET status=%s WHERE securityCodeRequest >= %d", MM_WPFS_CustomerPortalService::SESSION_STATUS_INVALIDATED, $securityCodeRequestLimit ) );

		self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $updateResult;
	}

	public function invalidateCustomerPortalSessionsBySecurityCodeInputLimit($securityCodeInputLimit ) {
		global $wpdb;

		$updateResult = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}fullstripe_card_update_session SET status=%s WHERE securityCodeInput >= %d", MM_WPFS_CustomerPortalService::SESSION_STATUS_INVALIDATED, $securityCodeInputLimit ) );

		self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $updateResult;
	}

	public function findInvalidatedCustomerPortalSessionIds() {
		global $wpdb;

		$cardUpdateSessionIds = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}fullstripe_card_update_session WHERE status=%s", MM_WPFS_CustomerPortalService::SESSION_STATUS_INVALIDATED ) );

		return $cardUpdateSessionIds;
	}

	public function deleteSecurityCodesBySessions($invalidatedSessionIds ) {
		global $wpdb;

		$whereStatement = ' WHERE sessionId IN (' . implode( ', ', array_fill( 0, sizeof( $invalidatedSessionIds ), '%s' ) ) . ')';

		$updateResult = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}fullstripe_security_code" . $whereStatement, $invalidatedSessionIds ) );

		self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during delete!' );

		return $updateResult;
	}

	public function deleteInvalidatedCustomerPortalSessions($invalidatedSessionIds ) {
		global $wpdb;

		$whereStatement = ' WHERE id IN (' . implode( ', ', array_fill( 0, sizeof( $invalidatedSessionIds ), '%s' ) ) . ')';

		$updateResult = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}fullstripe_card_update_session" . $whereStatement, $invalidatedSessionIds ) );

		self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during delete!' );

		return $updateResult;
	}

	public function getPayment($id ) {
		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_payments WHERE paymentID=%d", $id ) );
	}

    public function getSavedCard( $id ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_card_captures WHERE captureID=%d", $id ) );
    }

    public function getPaymentByEventId($eventId ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_payments WHERE eventID=%s", $eventId ) );
    }

    public function updatePaymentByEventId( $event_id, $data ) {
		global $wpdb;

		$update_result = $wpdb->update( "{$wpdb->prefix}fullstripe_payments", $data, array( 'eventID' => $event_id ) );

		self::handleDbError( $update_result, __FUNCTION__ . '(): an error occurred during update!' );

		return $update_result;
	}

    public function getDonation( $id ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_donations WHERE donationID=%d", $id ) );
    }

    public function getDonationByStripeSubscriptionId( $stripeSubscriptionId ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_donations WHERE stripeSubscriptionID=%s", $stripeSubscriptionId ) );
    }

    public function getDonationByPaymentIntentId($paymentIntentId ) {
        global $wpdb;

        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fullstripe_donations WHERE stripePaymentIntentID=%s", $paymentIntentId ) );
    }

    public function updateDonationByPaymentIntentId( $paymentIntentId, $data ) {
        global $wpdb;

        $update_result = $wpdb->update( "{$wpdb->prefix}fullstripe_donations", $data, array( 'stripePaymentIntentID' => $paymentIntentId ) );

        self::handleDbError( $update_result, __FUNCTION__ . '(): an error occurred during update!' );

        return $update_result;
    }

    /**
	 * @param string $stripeSubscriptionId
	 *
	 * @return bool|int
	 * @throws Exception
	 */
	public function updateSubscriptionToRunning( $stripeSubscriptionId ) {
		global $wpdb;
		$queryResult = $wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->prefix}fullstripe_subscribers SET status=%s WHERE stripeSubscriptionID=%s",
				MM_WPFS::SUBSCRIBER_STATUS_RUNNING,
				$stripeSubscriptionId
			)
		);
		self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during update!' );

		return $queryResult;
	}


    /**
     * @return array|null
     */
	public function getAllForms() {
        global $wpdb;

        $result = array_merge(
            $this->getInlinePaymentFormsForList(),
            $this->getCheckoutPaymentFormsForList(),
            $this->getInlineSubscriptionFormsForList(),
            $this->getCheckoutSubscriptionFormsForList(),
            $this->getInlineDonationFormsForList(),
            $this->getCheckoutDonationFormsForList(),
            $this->getInlineSaveCardFormsForList(),
            $this->getCheckoutSaveCardFormsForList()
        );

        return $result;
    }

    private function getInlinePaymentFormsForList() {
        global $wpdb;

        $result = $wpdb->get_results( "
            SELECT
                form.paymentFormID as id,
                'payment' as type,
                'inline' as layout,
                form.name as name,
                form.displayName as displayName,
                payment.created as created
            FROM
                {$wpdb->prefix}fullstripe_payment_forms form
            LEFT JOIN 
                {$wpdb->prefix}fullstripe_payments payment
            ON (
                form.paymentFormID = payment.formId AND
                form.name = payment.formName AND
                NOT EXISTS (
                    SELECT
                        1
                    FROM
                        {$wpdb->prefix}fullstripe_payments payment1
                    WHERE
                        form.paymentFormID = payment1.formId AND
                        form.name = payment1.formName AND
                        payment1.created > payment.created
                )
            )
            WHERE
                form.customAmount != 'card_capture';
        ", OBJECT );
        self::handleDbError( $result, __FUNCTION__ . '(): an error occurred during select!' );

        return $result;
    }

    private function getCheckoutPaymentFormsForList() {
        global $wpdb;

        $result = $wpdb->get_results( "
            SELECT
                form.checkoutFormID as id,
                'payment' as type,
                'checkout' as layout,
                form.name as name,
                form.displayName as displayName,
                payment.created as created
            FROM
                {$wpdb->prefix}fullstripe_checkout_forms form
            LEFT JOIN 
                {$wpdb->prefix}fullstripe_payments payment
            ON (
                form.checkoutFormID = payment.formId AND
                form.name = payment.formName AND
                NOT EXISTS (
                    SELECT
                        1
                    FROM
                        {$wpdb->prefix}fullstripe_payments payment1
                    WHERE
                        form.checkoutFormID = payment1.formId AND
                        form.name = payment1.formName AND
                        payment1.created > payment.created
                )
            )
            WHERE
                form.customAmount != 'card_capture'
        ", OBJECT );
        self::handleDbError( $result, __FUNCTION__ . '(): an error occurred during select!' );

        return $result;
    }

    private function getInlineSubscriptionFormsForList() {
        global $wpdb;

        $result = $wpdb->get_results( "
            SELECT
                form.subscriptionFormID as id,
                'subscription' as type,
                'inline' as layout,
                form.name as name,
                form.displayName as displayName,
                sub.created as created
            FROM
                {$wpdb->prefix}fullstripe_subscription_forms form
            LEFT JOIN 
                {$wpdb->prefix}fullstripe_subscribers sub 
            ON (
                form.subscriptionFormID = sub.formId AND
                form.name = sub.formName AND
                NOT EXISTS (
                    SELECT
                        1
                    FROM
                        {$wpdb->prefix}fullstripe_subscribers sub1
                    WHERE
                        form.subscriptionFormID = sub1.formId AND
                        form.name = sub1.formName AND
                        sub1.created > sub.created
                )
            )
        ", OBJECT );
        self::handleDbError( $result, __FUNCTION__ . '(): an error occurred during select!' );

        return $result;
    }

    private function getCheckoutSubscriptionFormsForList() {
        global $wpdb;

        $result = $wpdb->get_results( "
            SELECT
                form.checkoutSubscriptionFormID as id,
                'subscription' as type,
                'checkout' as layout,
                form.name as name,
                form.displayName as displayName,
                sub.created as created
            FROM
                {$wpdb->prefix}fullstripe_checkout_subscription_forms form
            LEFT JOIN 
                {$wpdb->prefix}fullstripe_subscribers sub 
            ON (
                form.checkoutSubscriptionFormID = sub.formId AND
                form.name = sub.formName AND
                NOT EXISTS (
                    SELECT
                        1
                    FROM
                        {$wpdb->prefix}fullstripe_subscribers sub1
                    WHERE
                        form.checkoutSubscriptionFormID = sub1.formId AND
                        form.name = sub1.formName AND
                        sub1.created > sub.created
                )
            )
        ", OBJECT );
        self::handleDbError( $result, __FUNCTION__ . '(): an error occurred during select!' );

        return $result;
    }

    private function getInlineDonationFormsForList() {
        global $wpdb;

        $result = $wpdb->get_results( "
            SELECT 
                donationFormID as id,
                'donation' as type,
                'inline' as layout,
                form.name as name,
                form.displayName as displayName,
                don.created as created
            FROM
                {$wpdb->prefix}fullstripe_donation_forms form
            LEFT JOIN 
                {$wpdb->prefix}fullstripe_donations don 
            ON (
                form.donationFormID = don.formId AND
                form.name = don.formName AND
                NOT EXISTS (
                    SELECT
                        1
                    FROM
                        {$wpdb->prefix}fullstripe_donations don1
                    WHERE
                        form.donationFormID = don1.formId AND
                        form.name = don1.formName AND
                        don1.created > don.created
                )
            )
        ", OBJECT );
        self::handleDbError( $result, __FUNCTION__ . '(): an error occurred during select!' );

        return $result;
    }

    private function getCheckoutDonationFormsForList() {
        global $wpdb;

        $result = $wpdb->get_results( "
            SELECT 
                checkoutDonationFormID as id,
                'donation' as type,
                'checkout' as layout,
                form.name as name,
                form.displayName as displayName,
                don.created as created
            FROM
                {$wpdb->prefix}fullstripe_checkout_donation_forms form
            LEFT JOIN 
                {$wpdb->prefix}fullstripe_donations don 
            ON (
                form.checkoutDonationFormID = don.formId AND
                form.name = don.formName AND
                NOT EXISTS (
                    SELECT
                        1
                    FROM
                        {$wpdb->prefix}fullstripe_donations don1
                    WHERE
                        form.checkoutDonationFormID = don1.formId AND
                        form.name = don1.formName AND
                        don1.created > don.created
                )
            )
        ", OBJECT );
        self::handleDbError( $result, __FUNCTION__ . '(): an error occurred during select!' );

        return $result;
    }

    private function getInlineSaveCardFormsForList() {
        global $wpdb;

        $result = $wpdb->get_results( "
            SELECT
                form.paymentFormID as id,
                'save_card' as type,
                'inline' as layout,
                form.name as name,
                form.displayName as displayName,
                save.created as created
            FROM
                {$wpdb->prefix}fullstripe_payment_forms form
            LEFT JOIN 
                {$wpdb->prefix}fullstripe_card_captures save
            ON (
                form.paymentFormID = save.formId AND
                form.name = save.formName AND
                NOT EXISTS (
                    SELECT
                        1
                    FROM
                        {$wpdb->prefix}fullstripe_card_captures save1
                    WHERE
                        form.paymentFormID = save1.formId AND
                        form.name = save1.formName AND
                        save1.created > save.created
                )
            )
            WHERE
                form.customAmount = 'card_capture'
        ", OBJECT );
        self::handleDbError( $result, __FUNCTION__ . '(): an error occurred during select!' );

        return $result;
    }

    private function getCheckoutSaveCardFormsForList() {
        global $wpdb;

        $result = $wpdb->get_results( "
            SELECT
                form.checkoutFormID as id,
                'save_card' as type,
                'checkout' as layout,
                form.name as name,
                form.displayName as displayName,
                save.created as created
            FROM
                {$wpdb->prefix}fullstripe_checkout_forms form
            LEFT JOIN 
                {$wpdb->prefix}fullstripe_card_captures save
            ON (
                form.checkoutFormID = save.formId AND
                form.name = save.formName AND
                NOT EXISTS (
                    SELECT
                        1
                    FROM
                        {$wpdb->prefix}fullstripe_card_captures save1
                    WHERE
                        form.checkoutFormID = save1.formId AND
                        form.name = save1.formName AND
                        save1.created > save.created
                )
            )
            WHERE
                form.customAmount = 'card_capture'
        ", OBJECT );
        self::handleDbError( $result, __FUNCTION__ . '(): an error occurred during select!' );

        return $result;
    }

    /**
	 * @param $module
	 * @param $class
	 * @param $function
	 * @param $level
	 * @param $message
	 * @param $exceptionStackTrace
	 *
	 * @return false|int
	 * @throws Exception
	 */
	public function insertLog( $module, $class, $function, $level, $message, $exceptionStackTrace ) {
		global $wpdb;
		$insertResult = $wpdb->insert(
			"{$wpdb->prefix}fullstripe_log",
			array(
				'created'   => current_time( 'mysql' ),
				'module'    => $module,
				'class'     => $class,
				'function'  => $function,
				'level'     => $level,
				'message'   => substr( $message, 0, 512 ),
				'exception' => $exceptionStackTrace
			)
		);

		self::handleDbError( $insertResult, __FUNCTION__ . '(): an error occurred during insert!' );

		return $insertResult;
	}

	public function findLogs() {

	}

	public function getFormIdsByName( $name ) {
        global $wpdb;

        $inlinePaymentForms         = $wpdb->get_results( $wpdb->prepare( "SELECT paymentFormID as id FROM {$wpdb->prefix}fullstripe_payment_forms WHERE name=%s;", $name ), ARRAY_A );
        $checkoutPaymentForms       = $wpdb->get_results( $wpdb->prepare( "SELECT checkoutFormID FROM {$wpdb->prefix}fullstripe_checkout_forms WHERE name=%s;", $name ), ARRAY_A );
        $inlineSubscriptionForms    = $wpdb->get_results( $wpdb->prepare( "SELECT subscriptionFormID FROM {$wpdb->prefix}fullstripe_subscription_forms WHERE name=%s;", $name ), ARRAY_A );
        $checkoutSubscriptionForms  = $wpdb->get_results( $wpdb->prepare( "SELECT checkoutSubscriptionFormID FROM {$wpdb->prefix}fullstripe_checkout_subscription_forms WHERE name=%s;", $name ), ARRAY_A );
        $inlineDonationForms        = $wpdb->get_results( $wpdb->prepare( "SELECT donationFormID FROM {$wpdb->prefix}fullstripe_donation_forms WHERE name=%s;", $name ), ARRAY_A );
        $checkoutDonationForms      = $wpdb->get_results( $wpdb->prepare( "SELECT checkoutDonationFormID FROM {$wpdb->prefix}fullstripe_checkout_donation_forms WHERE name=%s;", $name ), ARRAY_A );

        $result = array_merge( $inlinePaymentForms, $checkoutPaymentForms,
                                $inlineSubscriptionForms, $checkoutSubscriptionForms,
                                $inlineDonationForms, $checkoutDonationForms );
        self::handleDbError( $result, __FUNCTION__ . '(): an error occurred during select!' );

        return $result;
    }

    /**
     * @return array|object|null
     * @throws Exception
     */
    public function getNumberOfOneTimePayments() {
        global $wpdb;

        $result = $wpdb->get_row( "SELECT count(*) as paymentCount FROM {$wpdb->prefix}fullstripe_payments;", OBJECT );
        self::handleDbError( $result, __FUNCTION__ . '(): an error occurred during select!' );

        return $result;
    }

    /**
     * @return array|object|null
     * @throws Exception
     */
    public function getNumberOfOneTimePaymentForms() {
        global $wpdb;

        $result = $wpdb->get_row( "
            SELECT SUM(formCount) as formCount FROM (
	            SELECT count(*) as formCount FROM {$wpdb->prefix}fullstripe_payment_forms WHERE customAmount != 'card_capture'
	            union all
	            SELECT count(*) as formCount FROM {$wpdb->prefix}fullstripe_checkout_forms WHERE customAmount != 'card_capture'
            ) as x;
        ", OBJECT );
        self::handleDbError( $result, __FUNCTION__ . '(): an error occurred during select!' );

        return $result;
    }

    /**
     * @return array|object|null
     * @throws Exception
     */
    public function getNumberOfSubscriptions() {
        global $wpdb;

        $result = $wpdb->get_row( "SELECT count(*) as subscriptionCount FROM {$wpdb->prefix}fullstripe_subscribers;", OBJECT );
        self::handleDbError( $result, __FUNCTION__ . '(): an error occurred during select!' );

        return $result;
    }

    /**
     * @return array|object|null
     * @throws Exception
     */
    public function getNumberOfSubscriptionForms() {
        global $wpdb;

        $result = $wpdb->get_row( "
            SELECT SUM(formCount) as formCount FROM (
                SELECT count(*) as formCount FROM {$wpdb->prefix}fullstripe_subscription_forms
                union all
                SELECT count(*) as formCount FROM {$wpdb->prefix}fullstripe_checkout_subscription_forms
            ) as x;
        ", OBJECT );
        self::handleDbError( $result, __FUNCTION__ . '(): an error occurred during select!' );

        return $result;
    }

    /**
     * @return array|object|null
     * @throws Exception
     */
    public function getNumberOfDonations() {
        global $wpdb;

        $result = $wpdb->get_row( "SELECT count(*) as donationCount FROM {$wpdb->prefix}fullstripe_donations;", OBJECT );
        self::handleDbError( $result, __FUNCTION__ . '(): an error occurred during select!' );

        return $result;
    }

    /**
     * @return array|object|null
     * @throws Exception
     */
    public function getNumberOfDonationForms() {
        global $wpdb;

        $result = $wpdb->get_row( "
            SELECT SUM(formCount) as formCount FROM (
                SELECT count(*) as formCount FROM {$wpdb->prefix}fullstripe_donation_forms
                union all
                SELECT count(*) as formCount FROM {$wpdb->prefix}fullstripe_checkout_donation_forms
            ) as x;
        ", OBJECT );
        self::handleDbError( $result, __FUNCTION__ . '(): an error occurred during select!' );

        return $result;
    }

    /**
     * @return array|object|null
     * @throws Exception
     */
    public function getNumberOfSavedCards() {
        global $wpdb;

        $result = $wpdb->get_row( "SELECT count(*) as savedCardCount FROM {$wpdb->prefix}fullstripe_card_captures;", OBJECT );
        self::handleDbError( $result, __FUNCTION__ . '(): an error occurred during select!' );

        return $result;
    }

    /**
     * @return array|object|null
     * @throws Exception
     */
    public function getNumberOfSaveCardForms() {
        global $wpdb;

        $result = $wpdb->get_row( "
            SELECT SUM(formCount) as formCount FROM (
	            SELECT count(*) as formCount FROM {$wpdb->prefix}fullstripe_payment_forms WHERE customAmount = 'card_capture'
	            union all
	            SELECT count(*) as formCount FROM {$wpdb->prefix}fullstripe_checkout_forms WHERE customAmount = 'card_capture'
            ) as x;
        ", OBJECT );
        self::handleDbError( $result, __FUNCTION__ . '(): an error occurred during select!' );

        return $result;
    }

    /**
     * @param $query
     *
     * @return array|object|null
     * @throws Exception
     */
    public function getResults( $query ) {
        global $wpdb;

        $result = $wpdb->get_results( $query, OBJECT );
        self::handleDbError( $result, __FUNCTION__ . '(): an error occurred during select!' );

        return $result;
    }

    /**
     * @param $query
     *
     * @return bool|int
     * @throws Exception
     */
    public function runQuery( $query ) {
        global $wpdb;

        $result = $wpdb->query( $query );
        self::handleDbError( $result, __FUNCTION__ . '(): an error occurred during select!' );

        return $result;
    }

    /**
     * @return array
     */
    public function getOneTimePaymentFormNames() : array {
        global $wpdb;

        $inlineForms   = $wpdb->get_results( "SELECT name, displayName FROM {$wpdb->prefix}fullstripe_payment_forms WHERE customAmount != 'card_capture';", OBJECT );
        $checkoutForms = $wpdb->get_results( "SELECT name, displayName FROM {$wpdb->prefix}fullstripe_checkout_forms WHERE customAmount != 'card_capture';", OBJECT );

        $result = array_merge( $inlineForms, $checkoutForms );

        return $result;
    }

    /**
     * @return array
     */
    public function getSaveCardFormNames() : array {
        global $wpdb;

        $inlineForms   = $wpdb->get_results( "SELECT name, displayName FROM {$wpdb->prefix}fullstripe_payment_forms WHERE customAmount = 'card_capture';", OBJECT );
        $checkoutForms = $wpdb->get_results( "SELECT name, displayName FROM {$wpdb->prefix}fullstripe_checkout_forms WHERE customAmount = 'card_capture';", OBJECT );

        $result = array_merge( $inlineForms, $checkoutForms );

        return $result;
    }

    /**
     * @return array
     */
    public function getSubscriptionFormNames() : array {
        global $wpdb;

        $inlineForms   = $wpdb->get_results( "SELECT name, displayName FROM {$wpdb->prefix}fullstripe_subscription_forms;", OBJECT );
        $checkoutForms = $wpdb->get_results( "SELECT name, displayName FROM {$wpdb->prefix}fullstripe_checkout_subscription_forms;", OBJECT );

        $result = array_merge( $inlineForms, $checkoutForms );

        return $result;
    }

    /**
     * @return array
     */
    public function getDonationFormNames() : array {
        global $wpdb;

        $inlineForms   = $wpdb->get_results( "SELECT name, displayName FROM {$wpdb->prefix}fullstripe_donation_forms;", OBJECT );
        $checkoutForms = $wpdb->get_results( "SELECT name, displayName FROM {$wpdb->prefix}fullstripe_checkout_donation_forms;", OBJECT );

        $result = array_merge( $inlineForms, $checkoutForms );

        return $result;
    }

    /**
     * @return string
     */
    public function getDatabasePrefix() {
        global $wpdb;

        return $wpdb->prefix;
    }

    /**
     * @return bool|int
     *
     * @throws Exception
     */
    public function updateInlineSubscriptionFormSelectorListStyle() {
        global $wpdb;

        $updateResult = $wpdb->update(
            "{$wpdb->prefix}fullstripe_subscription_forms",
            array(
                'planSelectorStyle' => 'radio-buttons'
            ),
            array(
                'planSelectorStyle' => 'list'
            )
        );
        self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during update!' );

        return $updateResult;
    }

    /**
     * @return bool|int
     *
     * @throws Exception
     */
    public function updateCheckoutSubscriptionFormSelectorListStyle() {
        global $wpdb;

        $updateResult = $wpdb->update(
            "{$wpdb->prefix}fullstripe_checkout_subscription_forms",
            array(
                'planSelectorStyle' => 'radio-buttons'
            ),
            array(
                'planSelectorStyle' => 'list'
            )
        );
        self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during update!' );

        return $updateResult;
    }


    /**
     * @return bool|int
     *
     * @throws Exception
     */
    public function updateInlinePaymentFormTaxDefaultSettings( $taxTypeDefault, $taxRateDefault ) {
        global $wpdb;

        $updateResult = $wpdb->update(
            "{$wpdb->prefix}fullstripe_payment_forms",
            array(
                'vatRateType' => $taxTypeDefault,
                'vatRates'    => $taxRateDefault
            ),
            array(
                'vatRateType' => NULL
            )
        );
        self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during update!' );

        return $updateResult;
    }

    /**
     * @return bool|int
     *
     * @throws Exception
     */
    public function updateCheckoutPaymentFormTaxDefaultSettings( $taxTypeDefault, $taxRateDefault ) {
        global $wpdb;

        $updateResult = $wpdb->update(
            "{$wpdb->prefix}fullstripe_checkout_forms",
            array(
                'vatRateType' => $taxTypeDefault,
                'vatRates'    => $taxRateDefault
            ),
            array(
                'vatRateType' => NULL
            )
        );
        self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during update!' );

        return $updateResult;
    }

    /**
     * @return bool|int
     *
     * @throws Exception
     */
    public function updateInlineSubscriptionFormTaxDefaultSettings( $taxTypeDefault, $taxRateDefault ) {
        global $wpdb;

        $updateResult = $wpdb->query( $wpdb->prepare(
            "UPDATE {$wpdb->prefix}fullstripe_subscription_forms SET vatRateType=%s, vatRates=%s WHERE vatRateType in (%s, %s, %s)",
            $taxTypeDefault,
            $taxRateDefault,
            MM_WPFS::VAT_RATE_TYPE_NO_VAT,
            MM_WPFS::VAT_RATE_TYPE_FIXED_VAT,
            MM_WPFS::VAT_RATE_TYPE_CUSTOM_VAT ) );
        self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during update!' );

        return $updateResult;
    }

    /**
     * @return bool|int
     *
     * @throws Exception
     */
    public function updateCheckoutSubscriptionFormTaxDefaultSettings( $taxTypeDefault, $taxRateDefault ) {
        global $wpdb;

        $updateResult = $wpdb->query( $wpdb->prepare(
            "UPDATE {$wpdb->prefix}fullstripe_checkout_subscription_forms SET vatRateType=%s, vatRates=%s WHERE vatRateType in (%s, %s, %s)",
            $taxTypeDefault,
            $taxRateDefault,
            MM_WPFS::VAT_RATE_TYPE_NO_VAT,
            MM_WPFS::VAT_RATE_TYPE_FIXED_VAT,
            MM_WPFS::VAT_RATE_TYPE_CUSTOM_VAT ) );
        self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during update!' );

        return $updateResult;
    }

    /**
     * @return bool|int
     *
     * @throws Exception
     */
    public function updateInlineDonationFormDefaultProduct( ) {
        global $wpdb;

        $updateResult = $wpdb->update(
            "{$wpdb->prefix}fullstripe_donation_forms",
            array(
                'productDesc' => 'Donation'
            ),
            array(
                'productDesc' => NULL
            )
        );
        self::handleDbError( $updateResult, __FUNCTION__ . '(): an error occurred during update!' );

        return $updateResult;
    }

    /**
     * @return array|object|null
     * @throws Exception
     */
    public function getNumberOfLogEntries() {
        global $wpdb;

        $result = $wpdb->get_row( "SELECT count(*) as logCount FROM {$wpdb->prefix}fullstripe_log;", OBJECT );
        self::handleDbError( $result, __FUNCTION__ . '(): an error occurred during select!' );

        return $result;
    }

    public function getLogEntries( $offset, $rowCount ) {
        global $wpdb;

        $result = $wpdb->get_results( sprintf( "SELECT * FROM {$wpdb->prefix}fullstripe_log LIMIT %d, %d", $offset, $rowCount), OBJECT );
        self::handleDbError( $result, __FUNCTION__ . '(): an error occurred during select!' );

        return $result;
    }

    /**
     * @throws Exception
     */
    function deleteLogs() {
        global $wpdb;
        $queryResult = $wpdb->query( 'DELETE FROM ' . $wpdb->prefix . 'fullstripe_log;' );
        self::handleDbError( $queryResult, __FUNCTION__ . '(): an error occurred during delete!' );
    }

}
