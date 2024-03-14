<?php

namespace WilokeEmailCreator\Dashboard\Controllers;


use Exception;
use stdClass;
use WilokeEmailCreator\Dashboard\Shared\GeneralHelper;
use WilokeEmailCreator\Dashboard\Shared\TraitBillingCountries;
use WilokeEmailCreator\Dashboard\Shared\TraitGetProductCategories;
use WilokeEmailCreator\DataFactory\Controllers\DataFactoryController;
use WilokeEmailCreator\Illuminate\Message\MessageFactory;
use WilokeEmailCreator\Illuminate\Prefix\AutoPrefix;
use WilokeEmailCreator\Shared\GetFieldPlaceholderSubjectEmail;
use WilokeEmailCreator\Shared\TraitEmailTypes;
use WilokeEmailCreator\Shared\TraitGetCurrency;
use WilokeEmailCreator\Shared\TraitHandleGeneralSettings;
use WilokeEmailCreator\Shared\TraitProductOptions;
use WilokeEmailCreator\Templates\Model\TemplateModel;
use WilokeEmailCreator\Shared\Helper;

class DashboardController
{
	use GeneralHelper, TraitHandleGeneralSettings, TraitEmailTypes, TraitProductOptions, TraitBillingCountries,
		TraitGetProductCategories, TraitGetCurrency;

	const WILMT_GLOBAL            = 'WILMT_GLOBAL';
	const PRODUCT_NAME            = 'emailcreator';
	const WILMT_PURCHASE_CODE_URL = 'https://emailcreator.app/wp-json/ev/v1/verifications';

	public function __construct()
	{
		add_action('admin_menu', [$this, 'registerMenu']);
		add_action('init', [$this, 'handleCheckPlanAccount']);
		add_action('admin_enqueue_scripts', [$this, 'enqueueScriptsToDashboard'], 10);
		//ajax
		add_action('wp_ajax_' . WILOKE_EMAIL_CREATOR_PREFIX . 'getTemplates', [$this, 'getTemplates']);
		add_action('wp_ajax_' . WILOKE_EMAIL_CREATOR_PREFIX . 'getCustomerTemplates',
			[$this, 'getCustomerTemplates']);
		add_action('wp_ajax_' . WILOKE_EMAIL_CREATOR_PREFIX . 'getCategories', [$this, 'getCategories']);
		add_action('wp_ajax_' . WILOKE_EMAIL_CREATOR_PREFIX . 'getTemplateDetail', [$this, 'getTemplateDetail']);
		add_action('wp_ajax_' . WILOKE_EMAIL_CREATOR_PREFIX . 'getSection', [$this, 'getSection']);
		add_action('wp_ajax_' . WILOKE_EMAIL_CREATOR_PREFIX . 'getProducts', [$this, 'getProducts']);
		add_action('wp_ajax_' . WILOKE_EMAIL_CREATOR_PREFIX . 'loadMoreProducts', [$this, 'loadMoreProducts']);
		add_action('wp_ajax_' . WILOKE_EMAIL_CREATOR_PREFIX . 'getPosts', [$this, 'getPosts']);
		add_action('wp_ajax_' . WILOKE_EMAIL_CREATOR_PREFIX . 'loadMorePosts', [$this, 'loadMorePosts']);
		add_action('wp_ajax_' . WILOKE_EMAIL_CREATOR_PREFIX . 'verifyPurchaseCode', [$this, 'verifyPurchaseCode']);
		add_action('wp_ajax_' . WILOKE_EMAIL_CREATOR_PREFIX . 'generalSettings', [$this, 'generalSettings']);
	}

	public function generalSettings()
	{
		$aData = $_POST ?? [];
		if (isset($aData['generalSetting']['automatically'])) {
			$this->handleSaveAutomatically($aData['generalSetting']['automatically']);
		}
		MessageFactory::factory('ajax')->success(esc_html__('Passed', 'emailcreator'));
	}

	public function handleCheckPlanAccount()
	{
		if (Helper::getPackagePlan() != 'free') {
			$purchaseCode = Helper::getPurchaseCode();
			$aResponse = $this->_handleCheckPurchaseCode($purchaseCode);
			if ($aResponse['status'] == 'error') {
				Helper::updatePackagePlan('free');
				Helper::updatePurchaseCode('');
			}
		}
	}

	public function verifyPurchaseCode()
	{
		try {
			$purchaseCode = sanitize_text_field($_POST['purchaseCode']);
			$aResponse = $this->_handleCheckPurchaseCode($purchaseCode);
			if ($aResponse['status'] == 'error') {
				throw new Exception($aResponse['message'], $aResponse['code']);
			}
			Helper::updateLicenseSourcePlan($aResponse['data']['licenseSource'] ?? 'envato');
			Helper::updatePackagePlan( 'pro');
			Helper::updatePurchaseCode($purchaseCode);
			return MessageFactory::factory('ajax')->success($aResponse['message'], [
				'link' => $this->getLinkReview()
			]);
		}
		catch (Exception $exception) {
			return MessageFactory::factory('ajax')->error($exception->getMessage(), $exception->getCode());
		}
	}

	private function _handleCheckPurchaseCode($purchaseCode)
	{
		try {
			if (empty($purchaseCode)) {
				throw new Exception(esc_html__(
					'Please provide your purchase to the Purchase code field.',
					'emailcreator'
				),
					401
				);
			}
			$aResult = wp_remote_post(self::WILMT_PURCHASE_CODE_URL, [
					'method'      => 'POST',
					'timeout'     => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => [
						'Content-Type: application/json'
					],
					'body'        => [
						'purchaseCode' => sanitize_text_field($purchaseCode),
						'productName'  => self::PRODUCT_NAME,
						'email'        => get_option('admin_email'),
						'clientSite'   => home_url('/')
					]
				]
			);
			if (is_wp_error($aResult)) {
				throw new Exception($aResult->get_error_message(), $aResult->get_error_code());
			}
			$aResponse = json_decode(wp_remote_retrieve_body($aResult), true);
			if ($aResponse['status'] == 'error') {
				throw new Exception($aResponse['message'], $aResponse['code']);
			}
			if ($aResponse['status'] == 'success') {
				return MessageFactory::factory()->success($aResponse['message'], $aResponse['data']);
			}
			throw new Exception($aResponse['message'], 401);
		}
		catch (Exception $exception) {
			return MessageFactory::factory()->error($exception->getMessage(), $exception->getCode());
		}
	}

	/**
	 * @throws Exception
	 */
	public function getTemplates()
	{
		$aData = DataFactoryController::setPlatform()->getTemplates();
		MessageFactory::factory('ajax')->success(esc_html__('found it', 'emailcreator'), $aData);
	}

	/**
	 * @throws Exception
	 */
	public function getCustomerTemplates()
	{
		$aData = apply_filters(WILOKE_EMAIL_CREATOR_HOOK_PREFIX .
			'src/DataFactory/Config/DataImportService/getCustomerTemplates', $aArgs = []);
		MessageFactory::factory('ajax')->success(esc_html__('found it', 'emailcreator'),
			$aData['data']['items']);
	}

	/**
	 * @throws Exception
	 */
	public function getCategories()
	{
		$aData = DataFactoryController::setPlatform()->getCategories();
		MessageFactory::factory('ajax')->success(esc_html__('found it', 'emailcreator'), $aData);
	}

	/**
	 * @throws Exception
	 */
	public function getTemplateDetail()
	{
		$templateID = abs((int)$_POST['template']['BeId'] ?? 0);
		if (TemplateModel::IsTemplateExists($templateID)) {
			$aData = apply_filters(WILOKE_EMAIL_CREATOR_HOOK_PREFIX .
				'src/Dashboard/DashboardControllers/getTemplateDetail', [], $templateID);
		} else {
			$templateID = sanitize_text_field($_POST['template']['id']);
			$aData = DataFactoryController::setPlatform()->getTemplateDetail($templateID);
		}
		MessageFactory::factory('ajax')->success(esc_html__('found it', 'emailcreator'), $aData);
	}

	/**
	 * @throws Exception
	 */
	public function getSection()
	{
		$categoryId = sanitize_text_field($_POST['categoryId'] ?? 0);
		$aData = DataFactoryController::setPlatform()->getSection($categoryId);
		MessageFactory::factory('ajax')->success(esc_html__('found it', 'emailcreator'), $aData);
	}

	/**
	 * @throws Exception
	 */
	public function getProducts()
	{
		$search = sanitize_text_field($_POST['search'] ?? '');
		$aData = DataFactoryController::setPlatform()->getProducts([
			's'     => $search,
			'limit' => 50,
			'page'  => 1
		]);
		MessageFactory::factory('ajax')->success(esc_html__('found it', 'emailcreator'),
			$aData['data'] ?? []);
	}

	/**
	 * @throws Exception
	 */
	public function loadMoreProducts()
	{
		$search = sanitize_text_field($_POST['search'] ?? '');
		$page = abs($_POST['page'] ?? 1);
		$aData = DataFactoryController::setPlatform()->getProducts([
			's'     => $search,
			'limit' => 50,
			'page'  => $page
		]);
		MessageFactory::factory('ajax')->success(esc_html__('found it', 'emailcreator'),
			$aData['data'] ?? []);
	}

	/**
	 * @throws Exception
	 */
	public function loadMorePosts()
	{
		$search = sanitize_text_field($_POST['search'] ?? '');
		$page = abs($_POST['page'] ?? 1);
		$aData = DataFactoryController::setPlatform()->getPosts([
			's'     => $search,
			'limit' => 50,
			'page'  => $page
		]);
		MessageFactory::factory('ajax')->success(esc_html__('found it', 'emailcreator'),
			$aData['data'] ?? []);
	}

	/**
	 * @throws Exception
	 */
	public function getPosts()
	{
		$search = sanitize_text_field($_POST['search'] ?? '');
		$aData = DataFactoryController::setPlatform()->getPosts([
			's'     => $search,
			'limit' => 50,
			'page'  => 1
		]);
		MessageFactory::factory('ajax')->success('found it', $aData['data'] ?? []);
	}

	public function getLinkReview(): string
	{
		return Helper::isLicenseSourceEnvato() ?
			'https://wordpress.org/support/plugin/email-creatior/reviews/#new-post' :
			'https://codecanyon.net/item/email-creator/39031325?s_rank=1';
	}

	public function getLinkRequestFeature(): string
	{
		return 'https://emailcreator.app/request-a-feature/';
	}

	public function enqueueScriptsToDashboard($hook): bool
	{
		$aCountries = [];
		foreach ($this->getBillingCountries() as $key => $item) {
			$aCountries[] = [
				'label' => $item,
				'value' => $key,
			];
		}
		$currency = $this->getCurrency();
		$currencyPosition = $this->getCurrencyPosition();
		$symbol = $this->getCurrencySymbol();
		$aVariables = GetFieldPlaceholderSubjectEmail::getFieldPlaceholder([]);
		wp_localize_script('jquery', self::WILMT_GLOBAL, [
			'url'                          => admin_url('admin-ajax.php'),
			'logo'                         => esc_url(wp_get_attachment_url(get_theme_mod('custom_logo'))),
			'restBase'                     => trailingslashit(rest_url(WILOKE_EMAIL_CREATOR_REST)),
			'email'                        => get_option('admin_email'),
			'rules'                        => esc_html__('Rules', 'emailcreator'),
			'clientSite'                   => home_url('/'),
			'titleUpload'                  => esc_html__('Select or upload image', 'emailcreator'),
			'countries'                    => $aCountries,
			'labelCountries'               => esc_html__('Apply to billing countries', "emailcreator"),
			'categories'                   => $this->getProductCategories(),
			'labelCategories'              => esc_html__('Apply to categories', "emailcreator"),
			'labelMinOrder'                => esc_html__('Apply to min order (Subtotal $)', "emailcreator"),
			'labelMaxOrder'                => esc_html__('Apply to max order (Subtotal $)', "emailcreator"),
			'upgradePlanModal'             => esc_html__('Upgrade Plan Modal', "emailcreator"),
			'labelAddedToCartXMinutes'     => esc_html__('After Added To Cart X minutes', "emailcreator"),
			'labelAfterOrderStatusPending' => esc_html__('After Order Status: pending', "emailcreator"),
			'labelAfterOrderStatusFailed'  => esc_html__('After Order Status: failed', "emailcreator"),
			'enterPurchaseCode'            => esc_html__('Your Purchase', "emailcreator"),
			'comingSoonModal'              => esc_html__('Coming Soon', "emailcreator"),
			'textReview'                   => esc_html__('Let\' better Email Creator together. Share your thoughts!',
				"emailcreator"),
			'buttonFeatures'               => esc_html__('Request Features', "emailcreator"),
			'textPCMReview'                => esc_html__('Congratulations, you are now a Power User!', "emailcreator"),
			'descPCMReview'                => esc_html__('This awesome feature has just been activated for you. If you love it, please consider giving us a high-five back by leaving a nice review.',
				"emailcreator"),
			'linkFeatures'                 => $this->getLinkRequestFeature(),
			'buttonReview'                 => esc_html__('Great i\'ll leave a good review', "emailcreator"),
			'linkReview'                   => $this->getLinkReview(),
			'generalSettings'              => esc_html__('General Settings', "emailcreator"),
			'AutomaticallyLabel'           => esc_html__('Automatically Complete Orders', "emailcreator"),
			'intro'                        => esc_html__('You spend hours after hours designing emails, tweaking elements & sending test, etc. Not anymore. Get your custom & unique email templates in minutes!',
				"emailcreator"),
			'pluginName'                   => esc_html__('Wiloke Email Creator', "emailcreator"),
			'introMyTemplate'              => esc_html__('Custom & Manage Your Own Emails In One Place',
				"emailcreator"),
			'automatically'                => $this->getAutomatically(),
			'notionComingSoonModal'        => esc_html__('Feature coming soon.', "emailcreator"),
			'purchaseCodeLabel'            => esc_html__('Enter your purchase code here and click submit button.',
				"emailcreator"),
			'notionUpgradePlanModal'       => esc_html__('Please upgrade your plan to use this feature.',
				"emailcreator"),
			'purchaseCode'                 => Helper::getPurchaseCode(),
			'productOptions'               => $this->getProductOptions(),
			'emailTypes'                   => $this->getEmailTypes(),
			'currency'                     => [
				'active'   => $currency,
				'position' => $currencyPosition,
				'symbol'   => html_entity_decode($symbol)
			],
			'variables'                    => [
				'orders'  => $aVariables['order'],
				'account' => array_unique(array_merge($aVariables['account'],$aVariables['order'])),
			],
			'package'                      => [
				'type'  => Helper::getPackagePlan(),
				'link'  => 'https://codecanyon.net/item/email-creator/39031325?s_rank=1',
				'label' => esc_html__('upgrade to pro', "emailcreator"),
			],
			'headerTextEmailTestingModal'  => esc_html__('Testing send email', "emailcreator"),
			'labelTestingModal'            => esc_html__('Your email', "emailcreator"),
			'headerTextModal'              => esc_html__('Notion', "emailcreator"),
			'headerTextRuleModal'          => esc_html__('Rule Modal', "emailcreator"),
			'headerTextPurchase'           => esc_html__('Upgrade To Pro Plan', "emailcreator"),
			'headerTextPurchaseCodeModal'  => esc_html__('Purchase Code Modal', "emailcreator"),
			'brandContentSection'          => $this->getBrandContentSection(),
			'linkDocSMTP'                  => 'https://emailcreator.app/docs/set-up-smtp/',
			'docPlugin'                    => 'https://emailcreator.app/docs/how-to-custom-your-own-email-with-email-creatior/',
			'messageErrorMail'             => esc_html__('Error: It seems your server does not allow to use PHP mailer function. Please setup ',
				"emailcreator")
		]);


		if ((strpos($hook, $this->getDashboardSlug()) !== false)) {

			// enqueue script
			wp_enqueue_script(
				AutoPrefix::namePrefix('twig.min.js'),
				'https://cdnjs.cloudflare.com/ajax/libs/twig.js/1.15.0/twig.min.js',
				[],
				WILOKE_EMAIL_CREATOR_VERSION,
				true
			);
			wp_enqueue_script(
				AutoPrefix::namePrefix('main'),
				'https://email-builder-core.netlify.app/static/js/main.js',
				[],
				WILOKE_EMAIL_CREATOR_VERSION,
				true
			);

			wp_enqueue_script(
				AutoPrefix::namePrefix('dashboard-script'),
				plugin_dir_url(__FILE__) . '../Assets/Js/Script.js',
				[AutoPrefix::namePrefix('main')],
				WILOKE_EMAIL_CREATOR_VERSION,
				true
			);
			wp_enqueue_media();
			//enqueue style

			wp_enqueue_style(
				uniqid(AutoPrefix::namePrefix('fonts.googleapis')),
				'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Roboto:wght@300;400;500&display=swap',
				[],
				WILOKE_EMAIL_CREATOR_VERSION
			);

			wp_enqueue_style(
				'fontawesome-5',
				'https://kit-pro.fontawesome.com/releases/v5.15.4/css/pro.min.css',
				[],
				'5.15.4'
			);

			wp_enqueue_style(
				AutoPrefix::namePrefix('main.css'),
				'https://email-builder-core.netlify.app/static/css/main.css',
				[],
				WILOKE_EMAIL_CREATOR_VERSION
			);

			wp_enqueue_style(
				uniqid(AutoPrefix::namePrefix('Style.css')),
				plugin_dir_url(__FILE__) . '../Assets/Css/Style.css',
				[],
				WILOKE_EMAIL_CREATOR_VERSION
			);
		}
		return false;
	}

	public function registerMenu()
	{
		$domain = str_replace(['https://', 'http://'], ['', ''], home_url());
		$domain = trim($domain, "/");
		$capability = $domain == "demo.emailcreator.app" ? "publish_posts" : "administrator";

		add_menu_page(
			esc_html__('Wiloke Email Creator', "emailcreator"),
			esc_html__('Wiloke Email Creator', "emailcreator"),
			$capability,
			$this->getDashboardSlug(),
			[$this, 'renderSettings'],
			plugin_dir_url(__FILE__) . '../Assets/logo.png'
		);
	}

	public function renderSettings()
	{
		$isReview = get_option(AutoPrefix::namePrefix('review'));
		?>
        <section class="wiloke-email-builder">
            <div class="wiloke-email-builder__container">
                <div id="customer-templates-root">
                    <div class="wil-p">
						<?php
						if (empty($isReview)) {
							if (defined('WILOKE_EMAIL_CREATOR_WP_ORG') && WILOKE_EMAIL_CREATOR_WP_ORG &&
								!Helper::isPro()) {
								?>
                                <p class="color-red">
                                    <a href="https://chatting.page/bdzedo8yftsclnwmwmbcqcsyscbk4rtl"
                                       target="_blank">
										<?php echo sprintf(__("<span class='hotpink'>Upgrade to pro version</span>", "emailcreator")); ?>
                                    </a>
                                </p>
								<?php
							} else {
								?>
                                <p>
									<?php esc_html_e("Let's better Email Creator together. Share your ",
										"emailcreator"); ?>
                                    <a class="hotpink"
                                       target="_blank"
                                       href="<?php echo esc_url($this->getLinkRequestFeature()); ?>">
										<?php esc_html_e('Request Features', 'emailcreator'); ?>
                                    </a> and
                                    <a class="hotpink" target="_blank"
                                       href="<?php echo esc_url($this->getLinkReview()); ?>">
										<?php esc_html_e('Leave Reviews', "emailcreator"); ?>
                                    </a>
                                </p>
								<?php
							}
						}
						?>
                    </div>
                </div>
            </div>
        </section>
		<?php
	}
}
