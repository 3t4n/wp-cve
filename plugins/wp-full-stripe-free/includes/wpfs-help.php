<?php

class MM_WPFS_HelpService {
    use MM_WPFS_Logger_AddOn;

	/**
	 * @var MM_WPFS_HelpRepository
	 */
	private $repository;

	/**
	 * MM_WPFS_HelpService constructor.
	 */
	public function __construct( $loggerService ) {
        $this->initLogger( $loggerService, MM_WPFS_LoggerService::MODULE_ADMIN );
	}

	private function initRepository() {
		if ( is_null( $this->repository ) ) {
			$this->repository = MM_WPFS_HelpRepositoryFactory::create( $this->loggerService );
		}
	}

	/**
	 * @param array $requestParameters
	 *
	 * @return MM_WPFS_ContextHelp
	 */
	public function getContextSensitiveHelp( $requestParameters = array() ) {

		$this->initRepository();
		
		$page = array_key_exists( 'page', $requestParameters ) ? $requestParameters['page'] : null;
		$tab  = array_key_exists( 'tab', $requestParameters ) ? $requestParameters['tab'] : null;
		$type = array_key_exists( 'type', $requestParameters ) ? $requestParameters['type'] : null;

		if ( ! is_null( $type ) ) {
			return $this->repository->getHelp( $page, $type );
		} else {
			return $this->repository->getHelp( $page, $tab );
		}
	}

}

class MM_WPFS_ContextHelp {

	protected $page;
	protected $section;

	protected $relatedArticles = array();
	protected $globalArticles = array();

	/**
	 * MM_WPFS_ContextHelp constructor.
	 *
	 * @param $page
	 * @param $section
	 */
	public function __construct( $page, $section ) {
		$this->page    = $page;
		$this->section = $section;
	}

	/**
	 * @return mixed
	 */
	public function getPage() {
		return $this->page;
	}

	/**
	 * @return mixed
	 */
	public function getSection() {
		return $this->section;
	}

	/**
	 * @return MM_WPFS_HelpArticle[]
	 */
	public function getRelatedArticles() {
		return $this->relatedArticles;
	}

	/**
	 * @param MM_WPFS_HelpArticle[] $relatedArticles
	 */
	public function setRelatedArticles( $relatedArticles ) {
		$this->relatedArticles = $relatedArticles;
	}

	/**
	 * @return MM_WPFS_HelpArticle[]
	 */
	public function getGlobalArticles() {
		return $this->globalArticles;
	}

	/**
	 * @param MM_WPFS_HelpArticle[] $globalArticles
	 */
	public function setGlobalArticles( array $globalArticles ) {
		$this->globalArticles = $globalArticles;
	}

}

class MM_WPFS_HelpArticle {

	private $caption;
	private $href;
	private $visualType;

	/**
	 * MM_WPFS_HelpArticle constructor.
	 *
	 * @param $caption
	 * @param $href
	 * @param $visualType
	 */
	public function __construct( $caption, $href, $visualType ) {
		$this->caption    = $caption;
		$this->href       = $href;
		$this->visualType = $visualType;
	}

	/**
	 * @return mixed
	 */
	public function getCaption() {
		return $this->caption;
	}

	/**
	 * @param mixed $caption
	 */
	public function setCaption( $caption ) {
		$this->caption = $caption;
	}

	/**
	 * @return mixed
	 */
	public function getHref() {
		return $this->href;
	}

	/**
	 * @param mixed $href
	 */
	public function setHref( $href ) {
		$this->href = $href;
	}

	/**
	 * @return MM_WPFS_HelpArticleVisualType
	 */
	public function getVisualType() {
		return $this->visualType;
	}

	/**
	 * @param MM_WPFS_HelpArticleVisualType $visualType
	 */
	public function setVisualType( $visualType ) {
		$this->visualType = $visualType;
	}

}

class MM_WPFS_HelpArticleVisualType {

	const TYPE_BOOKMARD = 'bookmark';
	const TYPE_GETTING_STARTED = 'getting-started';
	const TYPE_KNOWLEDGE_BASE = 'knowledge-base';
	const TYPE_FEEDBACK = 'feedback';
	const ICON_BOOKMARK = 'wpfs-icon-book-open-bookmark';
	const ICON_GETTING_STARTED = 'wpfs-icon-space-rocket-flying';
	const ICON_KNOWLEDGE_BASE = 'wpfs-icon-e-learning-monitor';
	const ICON_FEEDBACK = 'wpfs-icon-messages-bubble-square-text';

	private $name;
	private $cssClass;

	/**
	 * MM_WPFS_HelpArticalVisualType constructor.
	 *
	 * @param $name
	 * @param $cssClass
	 */
	public function __construct( $name, $cssClass ) {
		$this->name     = $name;
		$this->cssClass = $cssClass;
	}

	/**
	 * @return MM_WPFS_HelpArticleVisualType
	 */
	public static function bookmark() {
		return new MM_WPFS_HelpArticleVisualType( self::TYPE_BOOKMARD, self::ICON_BOOKMARK );
	}

	/**
	 * @return MM_WPFS_HelpArticleVisualType
	 */
	public static function gettingStarted() {
		return new MM_WPFS_HelpArticleVisualType( self::TYPE_GETTING_STARTED, self::ICON_GETTING_STARTED );
	}

	/**
	 * @return MM_WPFS_HelpArticleVisualType
	 */
	public static function knowledgeBase() {
		return new MM_WPFS_HelpArticleVisualType( self::TYPE_KNOWLEDGE_BASE, self::ICON_KNOWLEDGE_BASE );
	}

	/**
	 * @return MM_WPFS_HelpArticleVisualType
	 */
	public static function feedback() {
		return new MM_WPFS_HelpArticleVisualType( self::TYPE_FEEDBACK, self::ICON_FEEDBACK );
	}

	/**
	 * @return mixed
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return mixed
	 */
	public function getCssClass() {
		return $this->cssClass;
	}

}

/**
 * Class MM_WPFS_HelpRepository
 *
 * In-memory data structure for context-sensitive help.
 */
class MM_WPFS_HelpRepository {
    use MM_WPFS_Logger_AddOn;

	private $data = array();

    public function __construct( $loggerService ) {
        $this->initLogger( $loggerService, MM_WPFS_LoggerService::MODULE_ADMIN );
    }

    /**
	 * @param MM_WPFS_ContextHelp $contextHelp
	 * @param $page
	 * @param $section
	 */
	public function addHelp( MM_WPFS_Contexthelp $contextHelp, $page, $section ) {
        $this->logger->debug(__FUNCTION__, 'CALLED, page=' . $page . ', section=' . $section);

		$this->data[ $page ][ $section ] = $contextHelp;
	}

	/**
	 * @param $page
	 * @param $section
	 *
	 * @return MM_WPFS_ContextHelp
	 */
	public function getHelp( $page, $section ) {
        $this->logger->debug(__FUNCTION__, 'CALLED, page=' . $page . ', section=' . $section);

		$contextHelp = null;
		if ( array_key_exists( $page, $this->data ) ) {
            $this->logger->debug(__FUNCTION__, 'Page found');

			if ( array_key_exists( $section, $this->data[ $page ] ) ) {
				$contextHelp = $this->data[ $page ][ $section ];

                $this->logger->debug(__FUNCTION__, 'Section found');
			} else {
				$contextHelp = $this->data[ $page ][''];

                $this->logger->debug(__FUNCTION__, 'Section not found');
			}
		} else {
            $this->logger->debug(__FUNCTION__, 'Page not found');
		}

		// tnagy retrieve default help entry
		if ( is_null( $contextHelp ) ) {
			$contextHelp = $this->data[''][''];

            $this->logger->debug(__FUNCTION__, 'Default help entry retrieved');
		}

		return $contextHelp;
	}

}

class MM_WPFS_HelpRepositoryFactory {

	const HELP_ARTICLE_BASE_URL = 'https://support.paymentsplugin.com';

	/**
	 * @return MM_WPFS_HelpRepository
	 */
	public static function create( $loggerService ) {
		$helpRepository = new MM_WPFS_HelpRepository( $loggerService );

		// tnagy default
		self::addEntry( $helpRepository, self::createDefaultPageHelp() );

		// tnagy forms
		self::addEntry( $helpRepository, self::createFormsPageHelp() );
		// tnagy create form
		self::addEntry( $helpRepository, self::createCreateFormPageHelp() );
		// tnagy edit forms by type
		self::addEntry( $helpRepository, self::createEditPaymentFormPageHelp() );
		self::addEntry( $helpRepository, self::createEditCheckoutFormPageHelp() );
		self::addEntry( $helpRepository, self::createEditSubscriptionFormPageHelp() );
		self::addEntry( $helpRepository, self::createEditCheckoutSubscriptionFormPageHelp() );
		self::addEntry( $helpRepository, self::createEditInlineDonationFormPageHelp() );
		self::addEntry( $helpRepository, self::createEditCheckoutDonationFormPageHelp() );
		self::addEntry( $helpRepository, self::createEditInlineSaveCardFormPageHelp() );
		self::addEntry( $helpRepository, self::createEditCheckoutSaveCardFormPageHelp() );
		// tnagy transactions
		self::addEntry( $helpRepository, self::createTransactionsPageHelp() );
		self::addEntry( $helpRepository, self::createOneTimePaymentsPageHelp() );
		self::addEntry( $helpRepository, self::createSubscriptionsPageHelp() );
		self::addEntry( $helpRepository, self::createDonationsPageHelp() );
		self::addEntry( $helpRepository, self::createSavedCardsPageHelp() );
		// tnagy settings
		self::addEntry( $helpRepository, self::createSettingsPageHelp() );
		self::addEntry( $helpRepository, self::createStripeSettingsPageHelp() );
		self::addEntry( $helpRepository, self::createFormsSettingsPageHelp() );
		self::addEntry( $helpRepository, self::createEmailSettingsOptionsPageHelp() );
        self::addEntry( $helpRepository, self::createEmailSettingsTemplatesPageHelp() );
		self::addEntry( $helpRepository, self::createSecuritySettingsPageHelp() );
		self::addEntry( $helpRepository, self::createCustomerPortalSettingsPageHelp() );
		self::addEntry( $helpRepository, self::createWordpressDashboardSettingsPageHelp() );

		return $helpRepository;
	}

	/**
	 * @param MM_WPFS_HelpRepository $helpRepository
	 * @param MM_WPFS_ContextHelp $contextHelp
	 */
	protected static function addEntry( $helpRepository, $contextHelp ) {
		$helpRepository->addHelp( $contextHelp, $contextHelp->getPage(), $contextHelp->getSection() );
	}

	/**
	 * @return MM_WPFS_ContextHelp
	 */
	protected static function createDefaultPageHelp() {
		$page    = '';
		$section = '';

		$contextHelp = new MM_WPFS_ContextHelp( $page, $section );

		$relatedArticles = self::getDefaultRelatedArticlesArray( $page );
		$globalArticles  = self::getDefaultGlobalArticlesArray( $page );

		$contextHelp->setRelatedArticles( $relatedArticles );
		$contextHelp->setGlobalArticles( $globalArticles );

		return $contextHelp;
	}

	/**
	 * @param $page
	 *
	 * @return MM_WPFS_HelpArticle[]
	 */
	protected static function getDefaultRelatedArticlesArray( $page ) {
		return array(
			self::createArticle(
				__( 'Set up Stripe account', 'wp-full-stripe-admin' ),
				'/article/16-configuring-the-stripe-api-keys',
				MM_WPFS_HelpArticleVisualType::bookmark(),
				$page
			),
			self::createArticle(
				__( 'Set up Stripe webhook', 'wp-full-stripe-admin' ),
				'/article/17-setting-up-webhooks',
				MM_WPFS_HelpArticleVisualType::bookmark(),
				$page
			)
		);
	}

	/**
	 * @param $caption
	 * @param $path
	 * @param $visualType
	 * @param $page
	 *
	 * @return MM_WPFS_HelpArticle
	 */
	protected static function createArticle( $caption, $path, $visualType, $page ) {
		$articleUrl = self::buildArticleURL( $path, $page );

		return new MM_WPFS_HelpArticle( $caption, $articleUrl, $visualType );
	}

	/**
	 * @param $path
	 * @param $utmContent
	 *
	 * @return string
	 */
	protected static function buildArticleURL( $path, $utmContent ) {
		$articleUrl = add_query_arg(
			array(
				'utm_source'   => 'plugin-wpfs',
				'utm_medium'   => 'help',
				'utm_campaign' => 'v' . MM_WPFS::VERSION,
				'utm_content'  => $utmContent
			),
			self::HELP_ARTICLE_BASE_URL . $path
		);

		return $articleUrl;
	}

	/**
	 * @param $page
	 *
	 * @return MM_WPFS_HelpArticle[]
	 */
	protected static function getDefaultGlobalArticlesArray( $page ) {
		return array(
			self::createArticle(
				__( 'Getting started guide', 'wp-full-stripe-admin' ),
				'/article/69-getting-started-with-wp-full-pay',
				MM_WPFS_HelpArticleVisualType::gettingStarted(),
				$page
			),
			self::createArticle(
				__( 'Search the documentation', 'wp-full-stripe-admin' ),
				'/',
				MM_WPFS_HelpArticleVisualType::knowledgeBase(),
				$page
			),
			self::createArticle(
				__( 'Leave feedback', 'wp-full-stripe-admin' ),
				'/#contact',
				MM_WPFS_HelpArticleVisualType::feedback(),
				$page
			)
		);
	}

	/**
	 * @return MM_WPFS_ContextHelp
	 */
	protected static function createFormsPageHelp() {
		$page    = MM_WPFS_Admin_Menu::SLUG_FORMS;
		$section = '';

		$contextHelp = new MM_WPFS_ContextHelp( $page, $section );

		$relatedArticles = array(
            self::createArticle(
                __( 'How to use shortcodes', 'wp-full-stripe-admin' ),
                '/article/27-how-to-use-form-shortcodes',
                MM_WPFS_HelpArticleVisualType::bookmark(),
                $page
            ),
            self::createArticle(
                __( 'Introducing form types', 'wp-full-stripe-admin' ),
                '/article/21-introducing-form-types',
                MM_WPFS_HelpArticleVisualType::bookmark(),
                $page
            ),
        );
		$globalArticles  = self::getDefaultGlobalArticlesArray( $page );

		$contextHelp->setRelatedArticles( $relatedArticles );
		$contextHelp->setGlobalArticles( $globalArticles );

		return $contextHelp;
	}

	/**
	 * @return MM_WPFS_ContextHelp
	 */
	protected static function createCreateFormPageHelp() {
		$page    = MM_WPFS_Admin_Menu::SLUG_CREATE_FORM;
		$section = '';

		$contextHelp = new MM_WPFS_ContextHelp( $page, $section );

        $relatedArticles = array(
            self::createArticle(
                __( 'Introducing form types', 'wp-full-stripe-admin' ),
                '/article/21-introducing-form-types',
                MM_WPFS_HelpArticleVisualType::bookmark(),
                $page
            ),
        );
		$globalArticles = self::getDefaultGlobalArticlesArray( $page );

		$contextHelp->setRelatedArticles( $relatedArticles );
		$contextHelp->setGlobalArticles( $globalArticles );

		return $contextHelp;
	}

	/**
	 * @return MM_WPFS_ContextHelp
	 */
	protected static function createEditPaymentFormPageHelp() {
		$page    = MM_WPFS_Admin_Menu::SLUG_EDIT_FORM;
		$section = MM_WPFS::FORM_TYPE_INLINE_PAYMENT;

		$contextHelp = new MM_WPFS_ContextHelp( $page, $section );

		$relatedArticles = self::getDefaultRelatedArticlesArray( $page );
		$globalArticles  = self::getDefaultGlobalArticlesArray( $page );

		$contextHelp->setRelatedArticles( $relatedArticles );
		$contextHelp->setGlobalArticles( $globalArticles );

		return $contextHelp;
	}

	/**
	 * @return MM_WPFS_ContextHelp
	 */
	protected static function createEditCheckoutFormPageHelp() {
		$page    = MM_WPFS_Admin_Menu::SLUG_EDIT_FORM;
		$section = MM_WPFS::FORM_TYPE_CHECKOUT_PAYMENT;

		$contextHelp = new MM_WPFS_ContextHelp( $page, $section );

		$relatedArticles = self::getDefaultRelatedArticlesArray( $page );
		$globalArticles  = self::getDefaultGlobalArticlesArray( $page );

		$contextHelp->setRelatedArticles( $relatedArticles );
		$contextHelp->setGlobalArticles( $globalArticles );

		return $contextHelp;
	}

	/**
	 * @return MM_WPFS_ContextHelp
	 */
	protected static function createEditSubscriptionFormPageHelp() {
		$page    = MM_WPFS_Admin_Menu::SLUG_EDIT_FORM;
		$section = MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION;

		$contextHelp = new MM_WPFS_ContextHelp( $page, $section );

		$relatedArticles = self::getDefaultRelatedArticlesArray( $page );
		$globalArticles  = self::getDefaultGlobalArticlesArray( $page );

		$contextHelp->setRelatedArticles( $relatedArticles );
		$contextHelp->setGlobalArticles( $globalArticles );

		return $contextHelp;
	}

	/**
	 * @return MM_WPFS_ContextHelp
	 */
	protected static function createEditCheckoutSubscriptionFormPageHelp() {
		$page    = MM_WPFS_Admin_Menu::SLUG_EDIT_FORM;
		$section = MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION;

		$contextHelp = new MM_WPFS_ContextHelp( $page, $section );

		$relatedArticles = self::getDefaultRelatedArticlesArray( $page );
		$globalArticles  = self::getDefaultGlobalArticlesArray( $page );

		$contextHelp->setRelatedArticles( $relatedArticles );
		$contextHelp->setGlobalArticles( $globalArticles );

		return $contextHelp;
	}

	/**
	 * @return MM_WPFS_ContextHelp
	 */
	protected static function createEditInlineDonationFormPageHelp() {
		$page    = MM_WPFS_Admin_Menu::SLUG_EDIT_FORM;
		$section = MM_WPFS::FORM_TYPE_INLINE_DONATION;

		$contextHelp = new MM_WPFS_ContextHelp( $page, $section );

		$relatedArticles = self::getDefaultRelatedArticlesArray( $page );
		$globalArticles  = self::getDefaultGlobalArticlesArray( $page );

		$contextHelp->setRelatedArticles( $relatedArticles );
		$contextHelp->setGlobalArticles( $globalArticles );

		return $contextHelp;
	}

	/**
	 * @return MM_WPFS_ContextHelp
	 */
	protected static function createEditCheckoutDonationFormPageHelp() {
		$page    = MM_WPFS_Admin_Menu::SLUG_EDIT_FORM;
		$section = MM_WPFS::FORM_TYPE_CHECKOUT_DONATION;

		$contextHelp = new MM_WPFS_ContextHelp( $page, $section );

		$relatedArticles = self::getDefaultRelatedArticlesArray( $page );
		$globalArticles  = self::getDefaultGlobalArticlesArray( $page );

		$contextHelp->setRelatedArticles( $relatedArticles );
		$contextHelp->setGlobalArticles( $globalArticles );

		return $contextHelp;
	}

	/**
	 * @return MM_WPFS_ContextHelp
	 */
	protected static function createEditInlineSaveCardFormPageHelp() {
		$page    = MM_WPFS_Admin_Menu::SLUG_EDIT_FORM;
		$section = MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD;

		$contextHelp = new MM_WPFS_ContextHelp( $page, $section );

		$relatedArticles = self::getDefaultRelatedArticlesArray( $page );
		$globalArticles  = self::getDefaultGlobalArticlesArray( $page );

		$contextHelp->setRelatedArticles( $relatedArticles );
		$contextHelp->setGlobalArticles( $globalArticles );

		return $contextHelp;
	}

	/**
	 * @return MM_WPFS_ContextHelp
	 */
	protected static function createEditCheckoutSaveCardFormPageHelp() {
		$page    = MM_WPFS_Admin_Menu::SLUG_EDIT_FORM;
        $section = MM_WPFS::FORM_TYPE_CHECKOUT_SAVE_CARD;

		$contextHelp = new MM_WPFS_ContextHelp( $page, $section );

		$relatedArticles = self::getDefaultRelatedArticlesArray( $page );
		$globalArticles  = self::getDefaultGlobalArticlesArray( $page );

		$contextHelp->setRelatedArticles( $relatedArticles );
		$contextHelp->setGlobalArticles( $globalArticles );

		return $contextHelp;
	}

	/**
	 * @return MM_WPFS_ContextHelp
	 */
	protected static function createTransactionsPageHelp() {
		$page    = MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS;
		$section = '';

		$contextHelp = new MM_WPFS_ContextHelp( $page, $section );

		$relatedArticles = self::getDefaultRelatedArticlesArray( $page );
		$globalArticles  = self::getDefaultGlobalArticlesArray( $page );

		$contextHelp->setRelatedArticles( $relatedArticles );
		$contextHelp->setGlobalArticles( $globalArticles );

		return $contextHelp;
	}

	/**
	 * @return MM_WPFS_ContextHelp
	 */
	protected static function createOneTimePaymentsPageHelp() {
		$page    = MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS;
		$section = MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_PAYMENTS;

		$contextHelp = new MM_WPFS_ContextHelp( $page, $section );

		$relatedArticles = self::getDefaultRelatedArticlesArray( $page );
		$globalArticles  = self::getDefaultGlobalArticlesArray( $page );

		$contextHelp->setRelatedArticles( $relatedArticles );
		$contextHelp->setGlobalArticles( $globalArticles );

		return $contextHelp;
	}

	/**
	 * @return MM_WPFS_ContextHelp
	 */
	protected static function createSubscriptionsPageHelp() {
		$page    = MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS;
		$section = MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_SUBSCRIPTIONS;

		$contextHelp = new MM_WPFS_ContextHelp( $page, $section );

		$relatedArticles = self::getDefaultRelatedArticlesArray( $page );
		$globalArticles  = self::getDefaultGlobalArticlesArray( $page );

		$contextHelp->setRelatedArticles( $relatedArticles );
		$contextHelp->setGlobalArticles( $globalArticles );

		return $contextHelp;
	}

	/**
	 * @return MM_WPFS_ContextHelp
	 */
	protected static function createDonationsPageHelp() {
		$page    = MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS;
		$section = MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_DONATIONS;

		$contextHelp = new MM_WPFS_ContextHelp( $page, $section );

		$relatedArticles = self::getDefaultRelatedArticlesArray( $page );
		$globalArticles  = self::getDefaultGlobalArticlesArray( $page );

		$contextHelp->setRelatedArticles( $relatedArticles );
		$contextHelp->setGlobalArticles( $globalArticles );

		return $contextHelp;
	}

	/**
	 * @return MM_WPFS_ContextHelp
	 */
	protected static function createSavedCardsPageHelp() {
		$page    = MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS;
		$section = MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_SAVED_CARDS;

		$contextHelp = new MM_WPFS_ContextHelp( $page, $section );

		$relatedArticles = self::getDefaultRelatedArticlesArray( $page );
		$globalArticles  = self::getDefaultGlobalArticlesArray( $page );

		$contextHelp->setRelatedArticles( $relatedArticles );
		$contextHelp->setGlobalArticles( $globalArticles );

		return $contextHelp;
	}

	/**
	 * @return MM_WPFS_ContextHelp
	 */
	protected static function createSettingsPageHelp() {
		$page    = MM_WPFS_Admin_Menu::SLUG_SETTINGS;
		$section = '';

		$contextHelp = new MM_WPFS_ContextHelp( $page, $section );

		$relatedArticles = self::getDefaultRelatedArticlesArray( $page );
		$globalArticles  = self::getDefaultGlobalArticlesArray( $page );

		$contextHelp->setRelatedArticles( $relatedArticles );
		$contextHelp->setGlobalArticles( $globalArticles );

		return $contextHelp;
	}

	/**
	 * @return MM_WPFS_ContextHelp
	 */
	protected static function createStripeSettingsPageHelp() {
		$page    = MM_WPFS_Admin_Menu::SLUG_SETTINGS_STRIPE;
		$section = '';

		$contextHelp = new MM_WPFS_ContextHelp( $page, $section );

		$relatedArticles = array(
            self::createArticle(
                __( 'Set up Stripe account', 'wp-full-stripe-admin' ),
                '/article/16-configuring-the-stripe-api-keys',
                MM_WPFS_HelpArticleVisualType::bookmark(),
                $page
            ),
            self::createArticle(
                __( 'Set up Stripe webhook', 'wp-full-stripe-admin' ),
                '/article/17-setting-up-webhooks',
                MM_WPFS_HelpArticleVisualType::bookmark(),
                $page
            ),
            self::createArticle(
                __( 'Why plans disappear when changing API mode?', 'wp-full-stripe-admin' ),
                '/article/52-all-subscription-plans-disappear-when-going-live',
                MM_WPFS_HelpArticleVisualType::bookmark(),
                $page
            ),
        );
		$globalArticles  = self::getDefaultGlobalArticlesArray( $page );

		$contextHelp->setRelatedArticles( $relatedArticles );
		$contextHelp->setGlobalArticles( $globalArticles );

		return $contextHelp;
	}

	/**
	 * @return MM_WPFS_ContextHelp
	 */
	protected static function createFormsSettingsPageHelp() {
		$page    = MM_WPFS_Admin_Menu::SLUG_SETTINGS_FORMS;
		$section = '';

		$contextHelp = new MM_WPFS_ContextHelp( $page, $section );

		$relatedArticles = array(
            self::createArticle(
                __( 'Customizing form styles', 'wp-full-stripe-admin' ),
                '/article/45-customizing-forms-with-css',
                MM_WPFS_HelpArticleVisualType::bookmark(),
                $page
            ),
            self::createArticle(
                __( 'How to translate labels', 'wp-full-stripe-admin' ),
                '/article/44-translating-the-plugin-to-other-languages',
                MM_WPFS_HelpArticleVisualType::bookmark(),
                $page
            ),
        );
		$globalArticles  = self::getDefaultGlobalArticlesArray( $page );

		$contextHelp->setRelatedArticles( $relatedArticles );
		$contextHelp->setGlobalArticles( $globalArticles );

		return $contextHelp;
	}

	/**
	 * @return MM_WPFS_ContextHelp
	 */
	protected static function createEmailSettingsOptionsPageHelp() {
		$page    = MM_WPFS_Admin_Menu::SLUG_SETTINGS_EMAIL_NOTIFICATIONS;
		$section = 'options';

		$contextHelp = new MM_WPFS_ContextHelp( $page, $section );

		$relatedArticles = array(
            self::createArticle(
                __( 'Configuring email notifications', 'wp-full-stripe-admin' ),
                '/article/28-configuring-email-notifications',
                MM_WPFS_HelpArticleVisualType::bookmark(),
                $page
            ),
        );
		$globalArticles  = self::getDefaultGlobalArticlesArray( $page );

		$contextHelp->setRelatedArticles( $relatedArticles );
		$contextHelp->setGlobalArticles( $globalArticles );

		return $contextHelp;
	}

    /**
     * @return MM_WPFS_ContextHelp
     */
    protected static function createEmailSettingsTemplatesPageHelp() {
        $page    = MM_WPFS_Admin_Menu::SLUG_SETTINGS_EMAIL_NOTIFICATIONS;
        $section = 'templates';

        $contextHelp = new MM_WPFS_ContextHelp( $page, $section );

        $relatedArticles = array(
            self::createArticle(
                __( 'Using placeholder tokens', 'wp-full-stripe-admin' ),
                '/article/29-using-placeholder-tokens',
                MM_WPFS_HelpArticleVisualType::bookmark(),
                $page
            ),
            self::createArticle(
                __( 'Configuring email notifications', 'wp-full-stripe-admin' ),
                '/article/28-configuring-email-notifications',
                MM_WPFS_HelpArticleVisualType::bookmark(),
                $page
            ),
        );
        $globalArticles  = self::getDefaultGlobalArticlesArray( $page );

        $contextHelp->setRelatedArticles( $relatedArticles );
        $contextHelp->setGlobalArticles( $globalArticles );

        return $contextHelp;
    }

    /**
	 * @return MM_WPFS_ContextHelp
	 */
	protected static function createSecuritySettingsPageHelp() {
		$page    = MM_WPFS_Admin_Menu::SLUG_SETTINGS_SECURITY;
		$section = '';

		$contextHelp = new MM_WPFS_ContextHelp( $page, $section );

		$relatedArticles = array(
            self::createArticle(
                __( 'Using Google reCaptcha', 'wp-full-stripe-admin' ),
                '/article/18-registering-your-website-for-google-recaptcha',
                MM_WPFS_HelpArticleVisualType::bookmark(),
                $page
            ),
        );
		$globalArticles  = self::getDefaultGlobalArticlesArray( $page );

		$contextHelp->setRelatedArticles( $relatedArticles );
		$contextHelp->setGlobalArticles( $globalArticles );

		return $contextHelp;
	}

	/**
	 * @return MM_WPFS_ContextHelp
	 */
	protected static function createCustomerPortalSettingsPageHelp() {
		$page    = MM_WPFS_Admin_Menu::SLUG_SETTINGS_CUSTOMER_PORTAL;
		$section = '';

		$contextHelp = new MM_WPFS_ContextHelp( $page, $section );

		$relatedArticles = array(
            self::createArticle(
                __( 'Setting up Customer Portal', 'wp-full-stripe-admin' ),
                '/article/49-subscribers-cannot-log-in-to-the-manage-subscriptions-page',
                MM_WPFS_HelpArticleVisualType::bookmark(),
                $page
            ),
        );
		$globalArticles  = self::getDefaultGlobalArticlesArray( $page );

		$contextHelp->setRelatedArticles( $relatedArticles );
		$contextHelp->setGlobalArticles( $globalArticles );

		return $contextHelp;
	}

	/**
	 * @return MM_WPFS_ContextHelp
	 */
	protected static function createWordpressDashboardSettingsPageHelp() {
		$page    = MM_WPFS_Admin_Menu::SLUG_SETTINGS_WORDPRESS_DASHBOARD;
		$section = '';

		$contextHelp = new MM_WPFS_ContextHelp( $page, $section );

		$relatedArticles = self::getDefaultRelatedArticlesArray( $page );
		$globalArticles  = self::getDefaultGlobalArticlesArray( $page );

		$contextHelp->setRelatedArticles( $relatedArticles );
		$contextHelp->setGlobalArticles( $globalArticles );

		return $contextHelp;
	}

}