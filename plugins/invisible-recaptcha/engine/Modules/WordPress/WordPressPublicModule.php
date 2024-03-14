<?php

namespace InvisibleReCaptcha\Modules\WordPress;

use InvisibleReCaptcha\MchLib\Utils\MchWpUtils;
use InvisibleReCaptcha\Modules\BasePublicModule;
use InvisibleReCaptcha\RequestHandler;

class WordPressPublicModule extends BasePublicModule
{
	private $commentValidationHookIndex = null;
	private $arrRegistrationHooksIndex = array();


	public function __construct()
	{
		parent::__construct();

		if($this->getOption(WordPressAdminModule::OPTION_COMMENTS_FORM_PROTECTION_ENABLED))
		{

			$this->addActionHook( 'comment_form', array( $this, 'renderReCaptchaHolderHtmlCode' ) );
			$this->commentValidationHookIndex = $this->addFilterHook('preprocess_comment', array($this, 'validateCommentsRequest'), -10);

		}


		if($this->getOption(WordPressAdminModule::OPTION_LOGIN_FORM_PROTECTION_ENABLED))
		{

			$this->loginFormHookIndex = $this->addActionHook('login_form', array($this, 'renderReCaptchaHolderHtmlCode'));

			$this->addFilterHook('wp_authenticate_user',  array($this, 'validateLoginUserAuthentication'), 25, 2);

			$this->loginAuthenticateFilterHookIndex = $this->addFilterHook('authenticate',  array($this, 'validateLoginAuthentication'), 95, 3);

		}

		if($this->getOption(WordPressAdminModule::OPTION_REGISTRATION_FORM_PROTECTION_ENABLED))
		{
			$this->arrRegistrationHooksIndex[] = $this->addActionHook('register_form',             array($this, 'renderReCaptchaHolderHtmlCode'));
			$this->arrRegistrationHooksIndex[] = $this->addActionHook('signup_extra_fields',       array($this, 'renderReCaptchaHolderHtmlCode'));

			$this->arrRegistrationHooksIndex[] =  $this->addFilterHook('registration_errors',       array($this, 'validateRegisterSingleSiteRequest'), 10, 3 );
			$this->arrRegistrationHooksIndex[] = $this->addFilterHook('wpmu_validate_user_signup', array($this, 'validateRegisterMultiSiteRequest'), 10, 1);

		}

		if($this->getOption(WordPressAdminModule::OPTION_FORGOT_PASSWD_FORM_PROTECTION_ENABLED))
		{
			$this->addActionHook('lostpassword_form', array($this, 'renderReCaptchaHolderHtmlCode'), 10);
			$this->addActionHook('lostpassword_post', array($this, 'validateLostPasswordRequest'), 10);

		}


	}


	public function getCommentValidationHookIndex()
	{
		return $this->commentValidationHookIndex;
	}

	public function removeRegistrationHooks()
	{
		foreach( (array)$this->arrRegistrationHooksIndex as $registrationHook){
			$this->removeHookByIndex($registrationHook);
		}
	}


	public function validateLostPasswordRequest()
	{
		if ( BasePublicModule::isRecaptchaValid())
			return;

		wp_safe_redirect(wp_login_url());

		exit;
	}


	public function validateRegisterMultiSiteRequest($results)
	{

		if (isset($_POST['stage']) && 'validate-blog-signup' == $_POST['stage'])
			return $results;

		if(BasePublicModule::isRecaptchaValid())
			return $results;

		empty($results['errors']) || !is_wp_error($results['errors']) ? $results['errors'] = new \WP_Error() : null;

		$results['errors']->add('invalid-token', __('Registration Error!', \InvisibleReCaptcha::PLUGIN_SLUG));

		return $results;
	}

	public function validateRegisterSingleSiteRequest($wpError, $sanitizedUserName, $userEmail)
	{
		if(BasePublicModule::isRecaptchaValid())
			return $wpError;

		!is_wp_error($wpError) ? $wpError = new \WP_Error() : null;

		$wpError->add('gdbc-invalid-token', __('Registration Error!', \InvisibleReCaptcha::PLUGIN_SLUG));

		return $wpError;
	}

	public function preventBruteForceAuthentication($userName, $password)
	{
		if(empty($userName))
			return;

		$validateResponse = $this->validateLoginAuthentication(new \WP_Error(), $userName, $password);
		if( ! is_wp_error($validateResponse) )
			return;

		if($validateResponse->get_error_code() !== \InvisibleReCaptcha::PLUGIN_SLUG)
			return;

	}

	public function validateLoginUserAuthentication($wpUser, $password)
	{
		$userName = isset($wpUser->data->user_login) ? $wpUser->data->user_login : '';

		return $this->validateLoginAuthentication($wpUser, $userName, $password);
	}

	public function validateLoginAuthentication($wpUser, $userName, $password)
	{

		if (is_wp_error($wpUser) && in_array($wpUser->get_error_code(), array('empty_username', 'empty_password')) ) {
			return $wpUser;
		}

		if(!function_exists('login_header')) // not wp-login page
			return $wpUser;

		return BasePublicModule::isRecaptchaValid()
				? $wpUser
				: new \WP_Error(\InvisibleReCaptcha::PLUGIN_SLUG,  __('Invalid username or incorrect password!', \InvisibleReCaptcha::PLUGIN_SLUG));

	}



	public function filterCommentsSubmit($submitButtonCode)
	{
		return $this->getReCaptchaHolderHtmlCode() . $submitButtonCode;
	}

	public function validateCommentsRequest($arrComment)
	{
		if(is_admin() && is_user_logged_in())
			return $arrComment;

		$arrWordPressCommentsType = array('pingback' => 1, 'trackback' => 1);

		if( (!empty($arrComment['comment_type']) && isset($arrWordPressCommentsType[strtolower($arrComment['comment_type'])]) ) ) {
			wp_die( '<p>' . __( 'Link Notifications are disabled!', 'invisible-recaptcha' ) . '</p>', __( 'Comment Submission Failure' ), array( 'response' => 200 ) );
		}

		$arrComment['comment_post_ID'] = (!empty($arrComment['comment_post_ID']) && is_numeric($arrComment['comment_post_ID'])) ? (int)$arrComment['comment_post_ID'] : 0;


		if(BasePublicModule::isRecaptchaValid())
			return $arrComment;


		$postPermaLink = get_permalink($arrComment['comment_post_ID']);

		empty($postPermaLink) ? wp_safe_redirect(home_url('/')) : wp_safe_redirect($postPermaLink);

		exit;

	}

}