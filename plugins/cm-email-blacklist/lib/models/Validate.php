<?php
class CMEB_Validate {

    protected static $_availableValidators = array( 'FreeDomains', 'WhiteList', );

    const OPTION_ENABLED_VALIDATORS = 'cmeb_enabled_validators';
    const ERROR_MESSAGE_OPTION      = 'cmeb_error_message';

    protected static $_enabledValidators = array();
    public static $_whiteListed          = false;
    public static $_freeDomainsListed    = false;
    public static $_emailBlacklisted     = false;

    public static function init() {
        self::$_enabledValidators = get_option( self::OPTION_ENABLED_VALIDATORS, self::$_availableValidators );
        add_filter( 'cmeb_admin_settings', array( get_class(), 'handleAdminSettings' ) );
        add_filter( 'registration_errors', array( get_class(), 'addValidationErrors' ), 100, 3 );
        add_filter( 'submit_wpum_form_validate_fields', array( get_class(),'wpumValidate' ), 100, 4 );
        add_action( 'register_form', array( get_class(), 'addRegisterFormField' ) );
        add_action( 'wpmu_validate_user_signup', array( get_class(), 'muVerifyDomain' ), 100 );
		add_action( 'cm_register_post', array( get_class(), 'cm_register_post' ), 1000, 3 );
    }

    public function isValid( $domain ) {
        $domain = esc_attr( strtolower( trim( $domain ) ) );

        $checkWhitelist   = CMEB_Settings::getOption( CMEB_Settings::OPTION_WHITE_LIST );
        $checkFreeDomains = CMEB_Settings::getOption( CMEB_Settings::OPTION_FREE_DOMAINS );

        if ( 1 == $checkWhitelist ) {
            include_once CMEB_PATH . '/lib/models/WhiteList.php';
            $isValid            = self::$_whiteListed = CMEB_WhiteList::isValid( $domain );
            /*
             * Domain whitelisted no point in further checking
             */
            if ( true == self::$_whiteListed ) {
                return self::$_whiteListed;
            }
        } else {
            /*
             * If we will NOT check agains the whitelist, we need to assume that the e-mail is valid.
             */
            $isValid = 1;
        }

        if ( $isValid && 1 == $checkFreeDomains ) {
            include_once CMEB_PATH . '/lib/models/FreeDomains.php';
            $isNotOnFreeDomainsList   = CMEB_FreeDomains::isValid( $domain );
            /*
             * Valid if not on free domains list
             */
            $isValid                  = $isNotOnFreeDomainsList;
            self::$_freeDomainsListed = !$isValid;
        }
        return $isValid;
    }

    public static function cm_register_post( $sanitized_user_login, $email, $errors ) {
		$emailValidationResult = self::validateEmail( $email );

        if ( !empty( $emailValidationResult ) ) {
            $loginFaild = CMEB_Settings::getOption( CMEB_Settings::OPTION_LOGIN_ERROR );
            add_action( 'login_head', 'wp_shake_js', 12 );
			//return new WP_Error( 'authentication_failed',  '<strong>' . $loginFaild . '</strong> </br>' . $emailValidationResult . '</br>' );
            $errors->add( 'authentication_failed', $loginFaild . ' ' . $emailValidationResult );
        } else {
            $emailParts = explode( '@', $email );
            if ( isset( $emailParts[ 1 ] ) ) {
                $emailDomain = $emailParts[ 1 ];
                $validator   = new self;
                if ( !$validator->isValid( $emailDomain ) ) {
					$reason = '';
                    if ( self::$_whiteListed == true ) {
						// Domain is whitelisted
                        $reason = CMEB_Settings::getOption( CMEB_Settings::OPTION_BECAUSE_WHITE );
                    } elseif ( self::$_freeDomainsListed ) {
						// Domain is in the Free domains list
                        $reason = CMEB_Settings::getOption( CMEB_Settings::OPTION_BECAUSE_WHITE );
                    } else {
						// Domain is neither blacklisted nor whitelisted
                        $reason = CMEB_Settings::getOption( CMEB_Settings::OPTION_BECAUSE_WHITE );
                    }
                    add_action( 'login_head', 'wp_shake_js', 12 );
                    $loginFaild = CMEB_Settings::getOption( CMEB_Settings::OPTION_LOGIN_ERROR );
					//return new WP_Error( 'authentication_failed', '<strong>' . $loginFaild . '</strong> </br>' . $reason . '</br>' );
                    $errors->add( 'authentication_failed', $loginFaild . ' ' . $reason );
                }
            } else {
                add_action( 'login_head', 'wp_shake_js', 12 );
                $errors->add( 'authentication_failed', 'ERROR: E-mail format is not valid.' );
				//return new WP_Error( 'authentication_failed', '<strong>ERROR</strong>: E-mail format is not valid.' );
            }
        }
		//return $errors;
	}

    public static function addValidationErrors( $errors, $sanitized_user_login, $user_email ) {
        self::verifyDomain($errors, $sanitized_user_login, $user_email);
        return $errors;
    }

    /**
     * Verify if user email domain is valid
     * @param array $errors
     * @param string $sanitized_user_login
     * @param string $user_email
     * @return WP_Error
     */
    public static function verifyDomain( $errors, $sanitized_user_login, $user_email ) {
        $emailValidationResult = self::validateEmail( $user_email );

        if ( !empty( $emailValidationResult ) ) {
            $loginFaild = CMEB_Settings::getOption( CMEB_Settings::OPTION_LOGIN_ERROR );
            add_action( 'login_head', 'wp_shake_js', 12 );
			//return new WP_Error( 'authentication_failed',  '<strong>' . $loginFaild . '</strong> </br>' . $emailValidationResult . '</br>' );
            $errors->add( 'authentication_failed', $loginFaild . ' ' . $emailValidationResult );
        } else {
            $emailParts = explode( '@', $user_email );
            if ( isset( $emailParts[ 1 ] ) ) {
                $emailDomain = $emailParts[ 1 ];
                $validator   = new self;
                if ( !$validator->isValid( $emailDomain ) ) {
					$reason = '';
                    if ( self::$_whiteListed == true ) {
						// Domain is whitelisted
                        $reason = CMEB_Settings::getOption( CMEB_Settings::OPTION_BECAUSE_WHITE );
                    } elseif ( self::$_freeDomainsListed ) {
						// Domain is in the Free domains list
                        $reason = CMEB_Settings::getOption( CMEB_Settings::OPTION_BECAUSE_WHITE );
                    } else {
						// Domain is neither blacklisted nor whitelisted
                        $reason = CMEB_Settings::getOption( CMEB_Settings::OPTION_BECAUSE_WHITE );
                    }
                    add_action( 'login_head', 'wp_shake_js', 12 );
                    $loginFaild = CMEB_Settings::getOption( CMEB_Settings::OPTION_LOGIN_ERROR );
					//return new WP_Error( 'authentication_failed', '<strong>' . $loginFaild . '</strong> </br>' . $reason . '</br>' );
                    $errors->add( 'authentication_failed', $loginFaild . ' ' . $reason );
                }
            } else {
                add_action( 'login_head', 'wp_shake_js', 12 );
                $errors->add( 'authentication_failed', 'ERROR: E-mail format is not valid.' );
				//return new WP_Error( 'authentication_failed', '<strong>ERROR</strong>: E-mail format is not valid.' );
            }
        }
		//return $errors;
    }

    public static function validateEmail( $email ) {
        $reason    = null;
        $validator = new self;
        $result    = $validator->isEmailValid( $email );

        if ( !$result ) {
             if ( self::$_emailBlacklisted == true ) {
                $reason = CMEB_Settings::getOption( CMEB_Settings::OPTION_EMAIL_BECAUSE_BLACK );
             }
        }
        return $reason;
    }

    public function isEmailValid( $email ) {
        $isValid        = true;
        $email          = strtolower( $email );
        $checkBlacklist = CMEB_Settings::getOption( CMEB_Settings::OPTION_EMAIL_BLACK_LIST );

        if ( 1 == $checkBlacklist ) {
            include_once CMEB_PATH . '/lib/models/EmailBlacklist.php';
            $isBlacklisted = CMEB_EmailBlacklist::isValid( $email );

            $isValid                 = $isBlacklisted;
            self::$_emailBlacklisted = !$isValid;
        }

        return $isValid;
    }

    public static function handleAdminSettings( $params ) {
        if ( !empty( $_POST[ '_wpnonce' ] ) && wp_verify_nonce( $_POST[ '_wpnonce' ], 'cmeb_settings' ) ) {
            $params                   = CMEB_Settings::processPostRequest();
            update_option( self::OPTION_ENABLED_VALIDATORS, !empty( $_POST[ 'enabledValidators' ] ) ? sanitize_text_field($_POST[ 'enabledValidators' ]) : array()  );
            if ( !empty( $_POST[ 'errorMessage' ] ) )
                update_option( self::ERROR_MESSAGE_OPTION, sanitize_text_field($_POST[ 'errorMessage' ]) );
            self::$_enabledValidators = get_option( self::OPTION_ENABLED_VALIDATORS, array() );
            $params[ 'messages' ][]   = 'Options updated';
        }
        $params[ 'availableValidators' ] = self::$_availableValidators;
        $params[ 'enabledValidators' ]   = self::$_enabledValidators;
        $params[ 'validatorNames' ]      = array(
            'FreeDomains' => 'Use predefined list of free e-mail domains (downloaded from SpamAssassin - http://svn.apache.org/repos/asf/spamassassin/trunk/rules/20_freemail_domains.cf)'
        );
        return $params;
    }

    public static function install() {
        foreach ( self::$_availableValidators as $validatorName ) {
            $className = 'CMEB_' . $validatorName;
            include_once CMEB_PATH . '/lib/models/' . $validatorName . '.php';
            $className::install();
        }
    }

    public static function uninstall() {
        foreach ( self::$_availableValidators as $validatorName ) {
            $className = 'CMEB_' . $validatorName;
            include_once CMEB_PATH . '/lib/models/' . $validatorName . '.php';
            $className::install();
        }
    }

    public function isWhiteListed() {
        return self::$_whiteListed;
    }

    public function isFreeDomainListed() {
        return self::$_freeDomainsListed;
    }

    public static function addRegisterFormField() {
        global $cmindsPluginPackage;
        $packageObj = isset($cmindsPluginPackage[ 'cmeb' ]) ? $cmindsPluginPackage[ 'cmeb' ] : null;

        if(!empty($packageObj) && !$packageObj->isPoweredByEnabled()){
            return;
        }

        ob_start();
        ?>
        <style>
            #user_email {margin-bottom:0px;}
            .cmeb_poweredby {clear:both;float:none;font-size:8px;line-height:1.5;margin-bottom:16px;display: inline-block;color:#bbb;text-decoration:none;font-weight:bold}
            .cmeb_poweredby:before {content:'Powered by ';}
        </style>
        <!--// By leaving following snippet in the code, you're expressing your gratitude to creators of this plugin. Thank You! //-->
        <span class="cmeb_poweredby"><a href="http://www.cminds.com/" target="_new">CreativeMinds WordPress Plugins</a> <a href="https://www.cminds.com/wordpress-plugins-library/email-registration-blacklist-plugin-for-wordpress/" target="_new">E-Mail Blacklist</a></span>
        <?php

        ob_end_flush();
    }

    /**
     * Make sure the chosen email is not in the blacklist.
     *
     * @param boolean $pass
     * @param array $fields
     * @param array $values
     * @param string $form
     * @return mixed
     */
    public static function wpumValidate( $pass, $fields, $values, $form ) {

        if ( $form == 'registration' && isset( $values['register']['user_email'] ) ) {
            $errorsObj = new WP_Error;
            self::verifyDomain( $errorsObj, '', $values['register']['user_email']);

            if( !empty( $errorsObj->get_error_message('authentication_failed'))) return $errorsObj;
        }
        return $pass;
    }

    public static function muVerifyDomain( $result ) {
        $email     = isset( $result[ 'user_email' ] ) ? $result[ 'user_email' ] : '';
        $errorsObj = $result[ 'errors' ];
        if ( !empty( $email ) && !empty( $errorsObj ) ) {
            self::verifyDomain( $errorsObj, '', $email );

            if ( $errorsObj->get_error_messages( 'authentication_failed' ) ) {
                $errors = $errorsObj->get_error_messages( 'authentication_failed' );
                foreach ( $errors as $value ) {
                    $errorsObj->add( 'user_email', $value );
                }
            }
        }
        return $result;
    }

}