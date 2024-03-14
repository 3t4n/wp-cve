<?php

/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2018.06.15.
 * Time: 13:25
 */
class MM_WPFS_EventHandler
{
	use MM_WPFS_Logger_AddOn;
	use MM_WPFS_StaticContext_AddOn;

	const WPFS_PLUGIN_SLUG = 'wp-full-stripe';
	const WPFS_REST_API_VERSION = 'v1';
	const WPFS_STRIPE_HOOKS_ENDPOINT = 'stripe-hooks';

	/** @var $db MM_WPFS_Database */
	protected $db = null;

	/** @var $mailer MM_WPFS_Mailer */
	protected $mailer = null;

	/** @var $mailer MM_WPFS_Options */
	protected $options = null;

	/** @var array */
	protected $eventProcessors = array();

	/**
	 * MM_WPFS_WebHookEventHandler constructor.
	 *
	 * @param MM_WPFS_Database $db
	 * @param MM_WPFS_Mailer $mailer
	 * @param MM_WPFS_LoggerService $loggerService
	 */
	public function __construct(MM_WPFS_Database $db, MM_WPFS_Mailer $mailer, MM_WPFS_LoggerService $loggerService)
	{
		$this->initLogger($loggerService, MM_WPFS_LoggerService::MODULE_WEBHOOK_EVENT_HANDLER);
		$this->options = new MM_WPFS_Options();

		$this->initStaticContext();

		$this->db = $db;
		$this->mailer = $mailer;

		$this->initProcessors();
		$this->hooks();
	}

	protected function hooks()
	{
		// tnagy WPFS-554: register REST API Endpoint for Stripe Webhooks
		add_action('rest_api_init', array($this, 'registerRESTAPIRoutes'));
	}

	/**
	 * @return string
	 */
	public static function getRESTNamespace(): string
	{
		return self::WPFS_PLUGIN_SLUG . '/' . self::WPFS_REST_API_VERSION;
	}

	/**
	 * Registers a REST endpoint for Stripe Webhooks handling
	 */
	public function registerRESTAPIRoutes()
	{
		register_rest_route(self::getRESTNamespace(), $this->getRESTRoute(), array(
			'methods' => WP_REST_Server::CREATABLE,
			'callback' => array($this, 'handleRESTRequest'),
			'args' => array(),
			'permission_callback' => '__return_true'
		));
	}

	/**
	 * @return string the REST API endpoint path for Stripe Webhooks
	 */
	public static function getWebhookPath()
	{
		return self::getRESTNamespace() . self::getRESTRoute();
	}

	/**
	 * @return string
	 */
	public static function getWebhookEndpointURL($context)
	{
		return add_query_arg(
			array(
				'auth_token' => MM_WPFS_EventHandler::getWebhookToken($context)
			),
			get_rest_url(null, MM_WPFS_EventHandler::getWebhookPath())
		);
	}

	/**
	 * @return string
	 */
	public static function getDemoWebhookURL()
	{
		$webhookUrl = 'https://demo.example.com/wp-admin/admin-post.php?action=handle_wpfs_event&auth_token=mfdg78er7rnvc74tnv7werndsfjkfds';

		return $webhookUrl;
	}

	/**
	 * @return string
	 */
	public static function getLegacyWebhookEndpointURL($context)
	{
		return add_query_arg(
			array(
				'action' => 'handle_wpfs_event',
				'auth_token' => MM_WPFS_EventHandler::getWebhookToken($context)
			),
			admin_url('admin-post.php')
		);
	}

	protected function initProcessors()
	{
		$processors = array(
			new MM_WPFS_CustomerSubscriptionDeleted($this->db, $this->mailer, $this->loggerService),
			new MM_WPFS_InvoiceCreated($this->db, $this->mailer, $this->loggerService),
			new MM_WPFS_InvoicePaymentSucceeded($this->db, $this->mailer, $this->loggerService),
			new MM_WPFS_ChargeCaptured($this->db, $this->mailer, $this->loggerService),
			new MM_WPFS_ChargeExpired($this->db, $this->mailer, $this->loggerService),
			new MM_WPFS_ChargeFailed($this->db, $this->mailer, $this->loggerService),
			new MM_WPFS_ChargePending($this->db, $this->mailer, $this->loggerService),
			new MM_WPFS_ChargeRefunded($this->db, $this->mailer, $this->loggerService),
			new MM_WPFS_ChargeSucceeded($this->db, $this->mailer, $this->loggerService),
			new MM_WPFS_ChargeUpdated($this->db, $this->mailer, $this->loggerService),
			new MM_WPFS_CustomerSubscriptionUpdated($this->db, $this->mailer, $this->loggerService)
		);
		foreach ($processors as $processor) {
			$this->eventProcessors[$processor->getType()] = $processor;
		}
	}

	/**
	 * @param bool $isLiveMode
	 * @param int $time
	 */
	protected function updateLastEventTimestamp(bool $isLiveMode, int $time)
	{
		if ($isLiveMode) {
			$this->options->set(MM_WPFS_Options::OPTION_LAST_WEBHOOK_EVENT_LIVE, $time);
		} else {
			$this->options->set(MM_WPFS_Options::OPTION_LAST_WEBHOOK_EVENT_TEST, $time);
		}
	}

	/**
	 * @param $event
	 */
	protected function logEventTimeStamp($event)
	{
		if (isset($event) && property_exists($event, 'livemode')) {
			$this->updateLastEventTimestamp($event->livemode, time());
		}
	}

	public function handle($event)
	{
		try {

			$this->logEventTimestamp($event);

			$eventProcessed = false;
			if (isset($event) && isset($event->type)) {
				$eventProcessor = null;
				if (array_key_exists($event->type, $this->eventProcessors)) {
					$eventProcessor = $this->eventProcessors[$event->type];
				}
				if ($eventProcessor instanceof MM_WPFS_EventProcessor) {
					$eventProcessor->process($event, $this->createContextForEvent($event));
					$eventProcessed = true;
				} else {
					$this->logger->debug(__FUNCTION__, 'Event processor not found');
				}
			} else {
				$this->logger->debug(__FUNCTION__, 'Event not recognized or event has no type property');
			}

			return $eventProcessed;
		} catch (Exception $ex) {
			$this->logger->error(__FUNCTION__, 'Error while handling webhook event', $ex);
			throw $ex;
		}
	}

	/**
	 * @param $context MM_WPFS_StaticContext
	 * @return mixed
	 */
	public static function getWebhookToken($context)
	{
		return $context->getOptions()->get(MM_WPFS_Options::OPTION_WEBHOOK_TOKEN);
	}

	public function handleRESTRequest()
	{
		$this->logger->debug(__FUNCTION__, 'CALLED');

		$auth_token = empty($_REQUEST['auth_token']) ? '' : $_REQUEST['auth_token'];
		$web_hook_token = self::getWebhookToken($this->staticContext);

		if ($web_hook_token !== $auth_token) {
			$this->logger->debug(__FUNCTION__, 'Authentication failed, abort.');

			// return HTTP Unauthorized
			status_header(401);
			header('Content-Type: application/json');
			exit;
		}

		try {

			// tnagy retrieve the request's body and parse it as JSON
			$input = @file_get_contents("php://input");

			$event = json_decode($input);
			$eventProcessed = $this->handle($event);

			$this->logger->debug(__FUNCTION__, 'Event processed? ' . ($eventProcessed === true ? 'true' : 'false'));

			// return HTTP OK
			status_header(200);
		} catch (Exception $ex) {
			$this->logger->error(__FUNCTION__, 'Error while handling REST request', $ex);

			// return HTTP Internal Server Error
			status_header(500);
		}

		header("Content-Type: application/json");
		exit;
	}

	/**
	 * @return string
	 */
	public static function getRESTRoute()
	{
		return '/' . self::WPFS_STRIPE_HOOKS_ENDPOINT;
	}

	/**
	 * @param \StripeWPFS\Event $event
	 *
	 * @return MM_WPFS_LiveModeAwareEventProcessorContext
	 * @throws Exception
	 */
	private function createContextForEvent($event)
	{
		$context = new MM_WPFS_LiveModeAwareEventProcessorContext($this->loggerService, $this->db, $this->mailer);
		// tnagy update context's liveMode property and Stripe instance to Live/Test mode depending on event's livemode attribute
		if (property_exists($event, 'livemode')) {
			if (true === $event->livemode) {
				$context->setLiveMode(true);
				$context->setStripe(new MM_WPFS_Stripe(MM_WPFS_Stripe::getStripeLiveAuthenticationToken($this->staticContext), $this->loggerService));
			} else {
				$context->setLiveMode(false);
				$context->setStripe(new MM_WPFS_Stripe(MM_WPFS_Stripe::getStripeTestAuthenticationToken($this->staticContext), $this->loggerService));
			}
		} else {
			// tnagy use default settings
			$context->setLiveMode(MM_WPFS_Stripe::isStripeApiInLiveMode($this->staticContext));
			$context->setStripe(new MM_WPFS_Stripe(MM_WPFS_Stripe::getStripeAuthenticationToken($this->staticContext), $this->loggerService));
		}

		return $context;
	}

}

class MM_WPFS_LiveModeAwareEventProcessorContext
{

	/**
	 * @var bool
	 */
	protected $liveMode;
	/**
	 * @var MM_WPFS_LoggerService
	 */
	protected $loggerService;
	/**
	 * @var MM_WPFS_Database
	 */
	protected $db = null;
	/**
	 * @var MM_WPFS_Mailer
	 */
	protected $mailer = null;
	/**
	 * This property is instantiated per request due to Stripe API mode to use the correct API key
	 * @var MM_WPFS_Stripe
	 */
	protected $stripe = null;

	/**
	 * MM_WPFS_EventProcessorContext constructor.
	 *
	 * @param MM_WPFS_LoggerService $loggerService
	 * @param MM_WPFS_Database $db
	 * @param MM_WPFS_Mailer $mailer
	 */
	public function __construct(MM_WPFS_LoggerService $loggerService, MM_WPFS_Database $db, MM_WPFS_Mailer $mailer)
	{
		$this->loggerService = $loggerService;
		$this->db = $db;
		$this->mailer = $mailer;
	}

	/**
	 * @return bool
	 */
	public function isLiveMode(): bool
	{
		return $this->liveMode;
	}

	/**
	 * @param bool $liveMode
	 */
	public function setLiveMode(bool $liveMode)
	{
		$this->liveMode = $liveMode;
	}

	/**
	 * @return MM_WPFS_LoggerService
	 */
	public function getLoggerService()
	{
		return $this->loggerService;
	}

	/**
	 * @return MM_WPFS_Database
	 */
	public function getDb()
	{
		return $this->db;
	}

	/**
	 * @return MM_WPFS_Mailer
	 */
	public function getMailer()
	{
		return $this->mailer;
	}

	/**
	 * @return MM_WPFS_Stripe
	 */
	public function getStripe()
	{
		return $this->stripe;
	}

	/**
	 * @param MM_WPFS_Stripe $stripe
	 */
	public function setStripe(MM_WPFS_Stripe $stripe)
	{
		$this->stripe = $stripe;
	}

}

abstract class MM_WPFS_EventProcessor
{
	use MM_WPFS_Logger_AddOn;

	const STRIPE_API_VERSION_2018_02_28 = '2018-02-28';
	const STRIPE_API_VERSION_2018_05_21 = '2018-05-21';

	/* @var $db MM_WPFS_Database */
	protected $db = null;
	/* @var $mailer MM_WPFS_Mailer */
	protected $mailer = null;

	/**
	 * MM_WPFS_WebHookEventProcessor constructor.
	 *
	 * @param MM_WPFS_Database $db
	 * @param MM_WPFS_Mailer $mailer
	 * @param MM_WPFS_LoggerService $loggerService
	 */
	public function __construct(MM_WPFS_Database $db, MM_WPFS_Mailer $mailer, MM_WPFS_LoggerService $loggerService)
	{
		$this->initLogger($loggerService, MM_WPFS_LoggerService::MODULE_WEBHOOK_EVENT_HANDLER);

		$this->db = $db;
		$this->mailer = $mailer;
	}

	public final function process($eventObject, MM_WPFS_LiveModeAwareEventProcessorContext $context)
	{
		if ($this->getType() === $eventObject->type) {
			$event = null;
			try {
				$event = $context->getStripe()->retrieveEvent($eventObject->id);
			} catch (Exception $ex) {
				// We want to make sure that the event exists
				$this->logger->error(__FUNCTION__, 'Cannot retrieve event', $ex);
			}

			if (!is_null($event)) {
				$event = \StripeWPFS\Event::constructFrom(json_decode(json_encode($event), true));
				$this->processEvent($event, $context);
			}
		}
	}

	public abstract function getType();

	/**
	 * @param $event
	 * @param MM_WPFS_LiveModeAwareEventProcessorContext $context
	 */
	protected function processEvent($event, MM_WPFS_LiveModeAwareEventProcessorContext $context)
	{
		// tnagy default implementation, override in subclasses
	}

	/**
	 * @param $event
	 *
	 * @return null|\StripeWPFS\ApiResource
	 */
	protected function getDataObject($event)
	{
		$object = null;
		if (isset($event) && isset($event->data) && isset($event->data->object)) {
			$object = $event->data->object;
		}

		return $object;
	}

	/**
	 * Adds an event ID to a JSON encoded array if the ID is not in the array
	 *
	 * @param string $encodedStripeEventIDs JSON encoded event ID array
	 * @param \StripeWPFS\Event $stripeEvent
	 * @param bool $success output variable to determine whether the event ID has been added to the array
	 *
	 * @return string the new JSON encoded array
	 */
	protected function insertIfNotExists($encodedStripeEventIDs, $stripeEvent, &$success)
	{
		$decodedStripeEventIDs = array();
		if (isset($encodedStripeEventIDs)) {
			$decodedStripeEventIDs = json_decode($encodedStripeEventIDs);
			if (json_last_error() !== JSON_ERROR_NONE) {
				$decodedStripeEventIDs = array();
			}
			if (!is_array($decodedStripeEventIDs)) {
				$decodedStripeEventIDs = array();
			}
		}
		if (isset($stripeEvent) && isset($stripeEvent->id)) {
			if (in_array($stripeEvent->id, $decodedStripeEventIDs)) {
				$data = $encodedStripeEventIDs;
				$success = false;
			} else {
				array_push($decodedStripeEventIDs, $stripeEvent->id);
				$data = json_encode($decodedStripeEventIDs);
				if (json_last_error() === JSON_ERROR_NONE) {
					$success = true;
				} else {
					$success = false;
				}
			}
		} else {
			$data = $encodedStripeEventIDs;
			$success = false;
		}

		return $data;
	}

	/**
	 * @param $event
	 *
	 * @return null|array
	 */
	protected function getDataPreviousAttributes($event)
	{
		$previous_attributes = null;
		if (isset($event) && isset($event->data) && isset($event->data->previous_attributes)) {
			$previous_attributes = $event->data->previous_attributes;
		}

		return $previous_attributes;
	}

	/**
	 * @param $wpfsSubscriber
	 *
	 * @return array|object|void|null
	 */
	protected function getSubscriptionFormBySubscriber($wpfsSubscriber)
	{
		$form = $this->db->getInlineSubscriptionFormById($wpfsSubscriber->formId);
		if ($form->name === $wpfsSubscriber->formName) {
			return $form;
		}
		$form = $this->db->getCheckoutSubscriptionFormById($wpfsSubscriber->formId);
		if ($form->name === $wpfsSubscriber->formName) {
			return $form;
		}

		return null;
	}

	/**
	 * @param $wpfsSubscriber
	 * @param MM_WPFS_LiveModeAwareEventProcessorContext $context
	 */
	protected function sendSubscriptionEndedReceipt($wpfsSubscriber, MM_WPFS_LiveModeAwareEventProcessorContext $context)
	{
		$form = $this->getSubscriptionFormBySubscriber($wpfsSubscriber);

		if (MM_WPFS_Mailer::canSendSubscriptionEndedPluginNotification($form)) {
			$transactionData = $this->createSubscriptionDataBySubscriber($form, $wpfsSubscriber, $context);
			$this->mailer->sendSubscriptionFinishedEmailReceipt($form, $transactionData);
		}
	}

	protected function getSetupFeeFromSubscriptionForm($form, $planId)
	{
		$result = 0;

		$decoratedPlans = MM_WPFS_Utils::decodeJsonArray($form->decoratedPlans);
		foreach ($decoratedPlans as $decoratedPlan) {
			if ($decoratedPlan->stripePriceId === $planId) {
				if (property_exists($decoratedPlan, 'setupFee') && !empty($decoratedPlan->setupFee)) {
					$result = $decoratedPlan->setupFee;
				}
				break;
			}
		}

		return $result;
	}

	/**
	 * @param $wpfsSubscriber
	 * @param MM_WPFS_LiveModeAwareEventProcessorContext $context
	 *
	 * @return MM_WPFS_SubscriptionTransactionData|null
	 */
	protected function createSubscriptionDataBySubscriber($form, $wpfsSubscriber, MM_WPFS_LiveModeAwareEventProcessorContext $context)
	{
		$transactionData = new MM_WPFS_SubscriptionTransactionData();

		$billingCountryComposite = MM_WPFS_Countries::getCountryByName($wpfsSubscriber->addressCountry);
		$billingAddress = MM_WPFS_Utils::prepareAddressData(
			$wpfsSubscriber->addressLine1,
			$wpfsSubscriber->addressLine2,
			$wpfsSubscriber->addressCity,
			$wpfsSubscriber->addressState,
			$wpfsSubscriber->addressCountry,
			is_null($billingCountryComposite) ? '' : $billingCountryComposite['alpha-2'],
			$wpfsSubscriber->addressZip
		);

		$shippingCountryComposite = MM_WPFS_Countries::getCountryByName($wpfsSubscriber->shippingAddressCountry);
		$shippingAddress = MM_WPFS_Utils::prepareAddressData(
			$wpfsSubscriber->shippingAddressLine1,
			$wpfsSubscriber->shippingAddressLine2,
			$wpfsSubscriber->shippingAddressCity,
			$wpfsSubscriber->shippingAddressState,
			$wpfsSubscriber->shippingAddressCountry,
			is_null($shippingCountryComposite) ? '' : $shippingCountryComposite['alpha-2'],
			$wpfsSubscriber->shippingAddressZip
		);


		$transactionData->setFormName($wpfsSubscriber->formName);
		$transactionData->setStripeCustomerId($wpfsSubscriber->stripeCustomerID);
		$transactionData->setCustomerName($wpfsSubscriber->name);
		$transactionData->setCustomerEmail($wpfsSubscriber->email);
		$transactionData->setPlanId($wpfsSubscriber->planID);
		$transactionData->setBillingName($wpfsSubscriber->billingName);
		$transactionData->setBillingAddress($billingAddress);
		$transactionData->setShippingName($wpfsSubscriber->shippingName);
		$transactionData->setShippingAddress($shippingAddress);
		$transactionData->setTransactionId($wpfsSubscriber->stripeSubscriptionID);
		$transactionData->setCouponCode($wpfsSubscriber->stripeSubscriptionID);

		$stripePlan = $context->getStripe()->retrievePlan($wpfsSubscriber->planID);
		if (isset($stripePlan)) {
			$transactionData->setPlanName($stripePlan->product->name);
			$transactionData->setPlanCurrency($stripePlan->currency);
			$transactionData->setProductName($stripePlan->product->name);

			$stripePlanSetupFee = $this->getSetupFeeFromSubscriptionForm($form, $wpfsSubscriber->planID);

			$planAmountGrossComposite = MM_WPFS_Utils::calculateGrossFromNet($stripePlan->unit_amount, 0);
			$planSetupFeeGrossComposite = MM_WPFS_Utils::calculateGrossFromNet($stripePlanSetupFee, 0);
			$planAmountGrossTotalComposite = MM_WPFS_Utils::calculateGrossFromNet($wpfsSubscriber->quantity * $stripePlan->unit_amount, 0);
			$planSetupFeeGrossTotalComposite = MM_WPFS_Utils::calculateGrossFromNet($wpfsSubscriber->quantity * $stripePlanSetupFee, 0);

			$transactionData->setSetupFeeNetAmount($stripePlanSetupFee);
			$transactionData->setSetupFeeGrossAmount($planSetupFeeGrossComposite['gross']);
			$transactionData->setSetupFeeTaxAmount($transactionData->getSetupFeeGrossAmount() - $transactionData->getSetupFeeNetAmount());
			$transactionData->setSetupFeeNetAmountTotal($planSetupFeeGrossTotalComposite['net']);
			$transactionData->setSetupFeeGrossAmountTotal($planSetupFeeGrossTotalComposite['gross']);
			$transactionData->setSetupFeeTaxAmountTotal($planSetupFeeGrossTotalComposite['taxValue']);

			$transactionData->setPlanNetAmount($stripePlan->amount);
			$transactionData->setPlanGrossAmount($planAmountGrossComposite['gross']);
			$transactionData->setPlanTaxAmount($transactionData->getPlanGrossAmount() - $transactionData->getPlanNetAmount());
			$transactionData->setPlanQuantity($wpfsSubscriber->quantity);
			$transactionData->setPlanNetAmountTotal($planAmountGrossTotalComposite['net']);
			$transactionData->setPlanGrossAmountTotal($planAmountGrossTotalComposite['gross']);
			$transactionData->setPlanTaxAmountTotal($planAmountGrossTotalComposite['taxValue']);
		}

		/*
		 *  todo: The following TransactionData attributes are not set at the moment:
		 * - companyName
		 * - taxCountry
		 * - taxId
		 * - Metadata
		 * - Custom input values
		 * - Billing cycle anchor day
		 * - Prorate until anchor day
		 */

		return $transactionData;
	}

	/**
	 * @param $stripeSubscriptionId
	 *
	 * @return array|null|object|void
	 */
	protected function findSubscriberByStripeSubscriptionId($stripeSubscriptionId)
	{
		return $this->db->getSubscriptionByStripeSubscriptionId($stripeSubscriptionId);
	}

}

abstract class MM_WPFS_InvoiceEventProcessor extends MM_WPFS_EventProcessor
{

	const INVOICE_ITEM_TYPE_SUBSCRIPTION = 'subscription';

	protected function findSubscriptionIdInLine($event, $line)
	{
		$stripeSubscriptionId = null;
		$stripeSubscriptionIdSource = null;
		if (strtotime(self::STRIPE_API_VERSION_2018_05_21) <= strtotime($event->api_version)) {
			if (self::INVOICE_ITEM_TYPE_SUBSCRIPTION === $line->type) {
				$stripeSubscriptionId = $line->subscription;
				$stripeSubscriptionIdSource = 'subscription';
			}
		} else {
			$stripeSubscriptionId = $line->id;
			$stripeSubscriptionIdSource = 'id';
		}

		$this->getLogger()->debug(__FUNCTION__, "api_version={$event->api_version}, stripe_subscription_id={$stripeSubscriptionId}, stripe_subscription_id_source={$stripeSubscriptionIdSource}");

		return $stripeSubscriptionId;
	}

	/**
	 * @return MM_WPFS_Logger
	 */
	protected abstract function getLogger();

	/**
	 * @param \StripeWPFS\Event $event
	 * @param \StripeWPFS\InvoiceLineItem $line
	 *
	 * @return null
	 */
	protected function findSubmitHashInLine($event, $line)
	{
		$submitHash = null;
		if (strtotime(self::STRIPE_API_VERSION_2018_05_21) <= strtotime($event->api_version)) {
			if (self::INVOICE_ITEM_TYPE_SUBSCRIPTION === $line->type) {
				if (isset($line->metadata) && isset($line->metadata->client_reference_id)) {
					$submitHash = $line->metadata->client_reference_id;
				}
			}
		}

		return $submitHash;
	}

	/**
	 * @param $submitHash
	 *
	 * @return array|null|object|void
	 */
	protected function findPopupFormSubmitByHash($submitHash)
	{
		return $this->db->findPopupFormSubmitByHash($submitHash);
	}

	/**
	 * @param $popupFormSubmit
	 * @param \StripeWPFS\Event $stripeEvent
	 *
	 * @return bool|int
	 */
	protected function updatePopupFormSubmitWithEvent($popupFormSubmit, \StripeWPFS\Event $stripeEvent)
	{
		if (isset($popupFormSubmit->relatedStripeEventIDs)) {
			$encodedStripeEventIDs = $popupFormSubmit->relatedStripeEventIDs;
		} else {
			$encodedStripeEventIDs = null;
		}
		$inserted = false;
		$relatedStripeEventIDs = $this->insertIfNotExists($encodedStripeEventIDs, $stripeEvent, $inserted);
		if ($inserted) {
			$this->logger->debug(__FUNCTION__, 'MM_WPFS_InvoicePaymentSucceeded::updatePopupFormSubmitWithEvent(): ' . sprintf('Updating PopupFormSubmit \'%s\' with event ID \'%s\'', $popupFormSubmit->hash, $stripeEvent->id));

			return $this->db->updatePopupFormSubmitWithEvent($popupFormSubmit->hash, $relatedStripeEventIDs);
		}

		return false;
	}

	/**
	 * @param $wpfsSubscriber
	 * @param MM_WPFS_LiveModeAwareEventProcessorContext $context
	 *
	 * @throws Exception
	 */
	protected function endSubscription($wpfsSubscriber, MM_WPFS_LiveModeAwareEventProcessorContext $context)
	{
		$this->db->endSubscription($wpfsSubscriber->stripeSubscriptionID);
		$success = $context->getStripe()->cancelSubscription($wpfsSubscriber->stripeCustomerID, $wpfsSubscriber->stripeSubscriptionID);
		if ($success) {
			$this->sendSubscriptionEndedReceipt($wpfsSubscriber, $context);
		}
	}

	/**
	 * @param $wpfsSubscriber
	 * @param \StripeWPFS\Event $stripeEvent
	 *
	 * @return bool|int
	 */
	protected function updateSubscriberWithPaymentAndEvent($wpfsSubscriber, \StripeWPFS\Event $stripeEvent)
	{
		if (isset($wpfsSubscriber->processedEventIDs)) {
			$encodedStripeEventIDs = $wpfsSubscriber->processedEventIDs;
		} else {
			$encodedStripeEventIDs = null;
		}
		$inserted = false;
		$processedStripeEventIDs = $this->insertIfNotExists($encodedStripeEventIDs, $stripeEvent, $inserted);
		if ($inserted) {
			return $this->db->updateSubscriberWithPaymentAndEvent($wpfsSubscriber->stripeSubscriptionID, $processedStripeEventIDs);
		}

		return false;
	}

	/**
	 * @param $wpfsSubscriber
	 * @param \StripeWPFS\Event $stripeEvent
	 *
	 * @return bool|int
	 */
	protected function updateSubscriberWithInvoiceAndEvent($wpfsSubscriber, \StripeWPFS\Event $stripeEvent)
	{
		if (isset($wpfsSubscriber->processedEventIDs)) {
			$encodedStripeEventIDs = $wpfsSubscriber->processedEventIDs;
		} else {
			$encodedStripeEventIDs = null;
		}
		$inserted = false;
		$processedStripeEventIDs = $this->insertIfNotExists($encodedStripeEventIDs, $stripeEvent, $inserted);
		if ($inserted) {
			return $this->db->updateSubscriberWithInvoiceAndEvent($wpfsSubscriber->stripeSubscriptionID, $processedStripeEventIDs);
		}

		return false;
	}

	/**
	 * @param $wpfsSubscriber
	 * @param \StripeWPFS\Event $stripeEvent
	 * @param bool $inserted
	 * @return string
	 */
	protected function addProcessedWebhookEvent($wpfsSubscriber, \StripeWPFS\Event $stripeEvent, &$inserted)
	{
		if (isset($wpfsSubscriber->processedStripeEventIDs)) {
			$encodedStripeEventIDs = $wpfsSubscriber->processedStripeEventIDs;
		} else {
			$encodedStripeEventIDs = null;
		}
		$processedStripeEventIDs = $this->insertIfNotExists($encodedStripeEventIDs, $stripeEvent, $inserted);

		return $processedStripeEventIDs;
	}

}

class MM_WPFS_CustomerSubscriptionDeleted extends MM_WPFS_EventProcessor
{

	public function __construct(MM_WPFS_Database $db, MM_WPFS_Mailer $mailer, MM_WPFS_LoggerService $loggerService)
	{
		parent::__construct($db, $mailer, $loggerService);
	}

	public function getType()
	{
		return \StripeWPFS\Event::CUSTOMER_SUBSCRIPTION_DELETED;
	}

	protected function isDonationSubscription($subscriptionId)
	{
		$isDonation = false;

		$donationRecord = $this->db->getDonationByStripeSubscriptionId($subscriptionId);
		if (!is_null($donationRecord)) {
			$isDonation = true;
		}

		if (!$isDonation) {
			$subscriptionRecord = $this->db->getSubscriptionByStripeSubscriptionId($subscriptionId);
			if (is_null($subscriptionRecord)) {
				$this->logger->error(__FUNCTION__, "Cannot find subscription record with id '{$subscriptionId}'.");
			}
		}

		return $isDonation;
	}

	protected function updateSubscriptionToCancelled($event, $stripeSubscription, MM_WPFS_LiveModeAwareEventProcessorContext $context)
	{
		$wpfsSubscriber = $this->findSubscriberByStripeSubscriptionId($stripeSubscription->id);

		if (isset($wpfsSubscriber)) {
			if (
				MM_WPFS::SUBSCRIPTION_STATUS_ENDED !== $wpfsSubscriber->status &&
				MM_WPFS::SUBSCRIPTION_STATUS_CANCELLED !== $wpfsSubscriber->status
			) {
				$this->updateSubscriberWithEvent($wpfsSubscriber, $event);
				$wpfsSubscriber = $this->findSubscriberByStripeSubscriptionId($stripeSubscription->id);

				if ($wpfsSubscriber->chargeMaximumCount > 0) {
					if ($wpfsSubscriber->chargeCurrentCount >= $wpfsSubscriber->chargeMaximumCount) {
						$this->manageEndedSubscription($wpfsSubscriber, $stripeSubscription->id, $context);
					} else {
						$this->db->cancelSubscriptionByStripeSubscriptionId($stripeSubscription->id);
					}
				} else {
					$this->db->cancelSubscriptionByStripeSubscriptionId($stripeSubscription->id);
				}

				do_action(MM_WPFS::ACTION_NAME_AFTER_SUBSCRIPTION_CANCELLATION, $stripeSubscription->id);
			}
		}
	}

	protected function updateDonationToCancelled($stripeSubscription)
	{
		$wpfsDonation = $this->db->getDonationByStripeSubscriptionId($stripeSubscription->id);

		if (isset($wpfsDonation)) {
			if (MM_WPFS::SUBSCRIPTION_STATUS_CANCELLED !== $wpfsDonation->subscriptionStatus) {
				$this->db->cancelDonationByStripeSubscriptionId($stripeSubscription->id);
				do_action(MM_WPFS::ACTION_NAME_AFTER_SUBSCRIPTION_CANCELLATION, $stripeSubscription->id);
			}
		}
	}

	protected function processEvent($event, MM_WPFS_LiveModeAwareEventProcessorContext $context)
	{
		$stripeSubscription = $this->getDataObject($event);

		if (!is_null($stripeSubscription)) {
			if ($this->isDonationSubscription($stripeSubscription->id)) {
				$this->updateDonationToCancelled($stripeSubscription);
			} else {
				$this->updateSubscriptionToCancelled($event, $stripeSubscription, $context);
			}
		}
	}

	private function updateSubscriberWithEvent($wpfsSubscriber, \StripeWPFS\Event $stripeEvent)
	{
		if (isset($wpfsSubscriber->processedEventIDs)) {
			$encodedStripeEventIDs = $wpfsSubscriber->processedEventIDs;
		} else {
			$encodedStripeEventIDs = null;
		}
		$inserted = false;
		$processedStripeEventIDs = $this->insertIfNotExists($encodedStripeEventIDs, $stripeEvent, $inserted);
		if ($inserted) {
			return $this->db->updateSubscriberWithEvent($wpfsSubscriber->stripeSubscriptionID, $processedStripeEventIDs);
		}

		return false;
	}

	/**
	 * @param $wpfsSubscriber
	 */
	protected function manageEndedSubscription($wpfsSubscriber, $stripeSubscriptionId, MM_WPFS_LiveModeAwareEventProcessorContext $context)
	{
		$this->db->endSubscription($stripeSubscriptionId);
		$this->sendSubscriptionEndedReceipt($wpfsSubscriber, $context);
	}

}

class MM_WPFS_InvoicePaymentSucceeded extends MM_WPFS_InvoiceEventProcessor
{

	public function __construct(MM_WPFS_Database $db, MM_WPFS_Mailer $mailer, MM_WPFS_LoggerService $loggerService)
	{
		parent::__construct($db, $mailer, $loggerService);
	}

	public function getType()
	{
		return \StripeWPFS\Event::INVOICE_PAYMENT_SUCCEEDED;
	}

	protected function getLogger()
	{
		return $this->logger;
	}

	protected function processEvent($event, MM_WPFS_LiveModeAwareEventProcessorContext $context)
	{
		foreach ($event->data->object->lines->data as $line) {
			$wpfsSubscriber = null;
			$stripeSubscriptionId = $this->findSubscriptionIdInLine($event, $line);

			$this->logger->debug(__FUNCTION__, "stripe_subscription_id=$stripeSubscriptionId");

			if (!is_null($stripeSubscriptionId)) {
				$wpfsSubscriber = $this->findSubscriberByStripeSubscriptionId($stripeSubscriptionId);
				if (isset($wpfsSubscriber)) {
					if (
						MM_WPFS::SUBSCRIBER_STATUS_ENDED !== $wpfsSubscriber->status &&
						MM_WPFS::SUBSCRIBER_STATUS_CANCELLED !== $wpfsSubscriber->status
					) {
						$this->updateSubscriberWithPaymentAndEvent($wpfsSubscriber, $event);
						$wpfsSubscriber = $this->findSubscriberByStripeSubscriptionId($stripeSubscriptionId);

						if ($wpfsSubscriber->chargeMaximumCount > 0) {
							if ($wpfsSubscriber->chargeCurrentCount > $wpfsSubscriber->chargeMaximumCount) {
								$this->endSubscription($wpfsSubscriber, $context);
							} else {
								$this->logger->debug(__FUNCTION__, 'subscription charged until maximum charge reached');
							}
						} else {
							$this->logger->debug(__FUNCTION__, "subscription->chargeMaximumCount is zero");
						}
					} else {
						$this->logger->debug(__FUNCTION__, "subscription status is 'ended' or 'cancelled', skip");
					}
				} else {
					$this->logger->debug(__FUNCTION__, 'subscription not found, try to find PopupFormSubmit entry...');

					$submitHash = $this->findSubmitHashInLine($event, $line);

					$this->logger->debug(__FUNCTION__, 'submitHash=' . "$submitHash");

					if (!is_null($submitHash)) {
						$popupFormSubmit = $this->findPopupFormSubmitByHash($submitHash);
						$this->logger->debug(__FUNCTION__, 'popupFormSubmit=' . print_r($popupFormSubmit, true));

						if (!is_null($popupFormSubmit)) {
							$this->updatePopupFormSubmitWithEvent($popupFormSubmit, $event);
							$this->logger->debug(__FUNCTION__, 'popupFormSubmit updated with event ID');
						}
					}
				}
			}
		}
	}
}

class MM_WPFS_InvoiceCreated extends MM_WPFS_InvoiceEventProcessor
{

	public function __construct(MM_WPFS_Database $db, MM_WPFS_Mailer $mailer, MM_WPFS_LoggerService $loggerService)
	{
		parent::__construct($db, $mailer, $loggerService);
	}

	public function getType()
	{
		return \StripeWPFS\Event::INVOICE_CREATED;
	}

	protected function getLogger()
	{
		return $this->logger;
	}

	protected function processEvent($event, MM_WPFS_LiveModeAwareEventProcessorContext $context)
	{
		foreach ($event->data->object->lines->data as $line) {
			$wpfsSubscriber = null;
			$stripeSubscriptionId = $this->findSubscriptionIdInLine($event, $line);

			$this->logger->debug(__FUNCTION__, "stripeSubscriptionId=$stripeSubscriptionId");

			if (!is_null($stripeSubscriptionId)) {
				$wpfsSubscriber = $this->findSubscriberByStripeSubscriptionId($stripeSubscriptionId);
				if (isset($wpfsSubscriber)) {
					if (
						MM_WPFS::SUBSCRIBER_STATUS_ENDED !== $wpfsSubscriber->status
						&& MM_WPFS::SUBSCRIBER_STATUS_CANCELLED !== $wpfsSubscriber->status
					) {
						$this->updateSubscriberWithInvoiceAndEvent($wpfsSubscriber, $event);
						$wpfsSubscriber = $this->findSubscriberByStripeSubscriptionId($stripeSubscriptionId);

						if ($wpfsSubscriber->chargeMaximumCount > 0) {
							if (
								$wpfsSubscriber->chargeCurrentCount >= $wpfsSubscriber->chargeMaximumCount &&
								$wpfsSubscriber->invoiceCreatedCount > $wpfsSubscriber->chargeMaximumCount
							) {
								$this->endSubscription($wpfsSubscriber, $context);
							} else {
								$this->logger->debug(__FUNCTION__, 'subscription charged until maximum charge reached');
							}
						} else {
							$this->logger->debug(__FUNCTION__, "subscription->chargeMaximumCount is zero");
						}
					} else {
						$this->logger->debug(__FUNCTION__, "subscription status is 'ended' or 'cancelled', skip");
					}
				} else {
					$this->logger->debug(__FUNCTION__, 'subscription not found, try to find PopupFormSubmit entry...');

					$submitHash = $this->findSubmitHashInLine($event, $line);

					$this->logger->debug(__FUNCTION__, 'submitHash=' . "$submitHash");

					if (!is_null($submitHash)) {
						$popupFormSubmit = $this->findPopupFormSubmitByHash($submitHash);

						if (!is_null($popupFormSubmit)) {
							$this->updatePopupFormSubmitWithEvent($popupFormSubmit, $event);

							$this->logger->debug(__FUNCTION__, 'popupFormSubmit updated with event ID');
						}
					}
				}
			}
		}
	}
}

trait MM_WPFS_ChargeEventUtils
{
	protected function isDonationPayment($paymentIntentId)
	{
		$isDonation = false;

		$donationRecord = $this->db->getDonationByPaymentIntentId($paymentIntentId);
		if (!is_null($donationRecord)) {
			$isDonation = true;
		}

		return $isDonation;
	}

	/**
	 * @param $charge \StripeWPFS\ApiResource
	 */
	protected function updatePaymentStatus($charge)
	{
		if ($this->isDonationPayment($charge->payment_intent)) {
			$this->db->updateDonationByPaymentIntentId(
				$charge->payment_intent,
				array(
					'paid' => $charge->paid,
					'captured' => $charge->captured,
					'refunded' => $charge->refunded,
					'lastChargeStatus' => $charge->status
				)
			);
		} else {
			$this->db->updatePaymentByEventId(
				$charge->payment_intent,
				array(
					'paid' => $charge->paid,
					'captured' => $charge->captured,
					'refunded' => $charge->refunded,
					'last_charge_status' => $charge->status
				)
			);
		}
	}

	/**
	 * @param $charge \StripeWPFS\ApiResource
	 */
	protected function updatePaymentStatusAndExpiry($charge)
	{
		if ($this->isDonationPayment($charge->payment_intent)) {
			$this->db->updateDonationByPaymentIntentId(
				$charge->payment_intent,
				array(
					'paid' => $charge->paid,
					'captured' => $charge->captured,
					'refunded' => $charge->refunded,
					'lastChargeStatus' => $charge->status,
					'expired' => true
				)
			);
		} else {
			$this->db->updatePaymentByEventId(
				$charge->payment_intent,
				array(
					'paid' => $charge->paid,
					'captured' => $charge->captured,
					'refunded' => $charge->refunded,
					'last_charge_status' => $charge->status,
					'expired' => true
				)
			);
		}
	}

	/**
	 * @param $charge \StripeWPFS\ApiResource
	 */
	protected function updatePaymentStatusAndFailureCodes($charge)
	{
		if ($this->isDonationPayment($charge->payment_intent)) {
			$this->db->updateDonationByPaymentIntentId(
				$charge->payment_intent,
				array(
					'paid' => $charge->paid,
					'captured' => $charge->captured,
					'refunded' => $charge->refunded,
					'lastChargeStatus' => $charge->status,
					'failureCode' => $charge->failure_code,
					'failureMessage' => $charge->failure_message
				)
			);
		} else {
			$this->db->updatePaymentByEventId(
				$charge->payment_intent,
				array(
					'paid' => $charge->paid,
					'captured' => $charge->captured,
					'refunded' => $charge->refunded,
					'last_charge_status' => $charge->status,
					'failure_code' => $charge->failure_code,
					'failure_message' => $charge->failure_message
				)
			);
		}
	}
}

class MM_WPFS_ChargeCaptured extends MM_WPFS_EventProcessor
{
	use MM_WPFS_ChargeEventUtils;

	public function __construct(MM_WPFS_Database $db, MM_WPFS_Mailer $mailer, MM_WPFS_LoggerService $loggerService)
	{
		parent::__construct($db, $mailer, $loggerService);
	}

	public function getType()
	{
		return \StripeWPFS\Event::CHARGE_CAPTURED;
	}

	protected function processEvent($event, MM_WPFS_LiveModeAwareEventProcessorContext $context)
	{
		$charge = $this->getDataObject($event);
		if (!is_null($charge)) {
			$this->updatePaymentStatus($charge);
		}
	}
}

class MM_WPFS_ChargeExpired extends MM_WPFS_EventProcessor
{
	use MM_WPFS_ChargeEventUtils;

	public function __construct(MM_WPFS_Database $db, MM_WPFS_Mailer $mailer, MM_WPFS_LoggerService $loggerService)
	{
		parent::__construct($db, $mailer, $loggerService);
	}

	public function getType()
	{
		return \StripeWPFS\Event::CHARGE_EXPIRED;
	}

	protected function processEvent($event, MM_WPFS_LiveModeAwareEventProcessorContext $context)
	{
		$charge = $this->getDataObject($event);
		if (!is_null($charge)) {
			$this->updatePaymentStatusAndExpiry($charge);
		}
	}
}

class MM_WPFS_ChargeFailed extends MM_WPFS_EventProcessor
{
	use MM_WPFS_ChargeEventUtils;

	public function __construct(MM_WPFS_Database $db, MM_WPFS_Mailer $mailer, MM_WPFS_LoggerService $loggerService)
	{
		parent::__construct($db, $mailer, $loggerService);
	}

	public function getType()
	{
		return \StripeWPFS\Event::CHARGE_FAILED;
	}

	protected function processEvent($event, MM_WPFS_LiveModeAwareEventProcessorContext $context)
	{
		$charge = $this->getDataObject($event);
		if (!is_null($charge)) {
			$this->updatePaymentStatusAndFailureCodes($charge);
		}
	}
}

class MM_WPFS_ChargePending extends MM_WPFS_EventProcessor
{
	use MM_WPFS_ChargeEventUtils;

	public function __construct(MM_WPFS_Database $db, MM_WPFS_Mailer $mailer, MM_WPFS_LoggerService $loggerService)
	{
		parent::__construct($db, $mailer, $loggerService);
	}

	public function getType()
	{
		return \StripeWPFS\Event::CHARGE_PENDING;
	}

	protected function processEvent($event, MM_WPFS_LiveModeAwareEventProcessorContext $context)
	{
		$charge = $this->getDataObject($event);
		if (!is_null($charge)) {
			$this->updatePaymentStatus($charge);
		}
	}
}

class MM_WPFS_ChargeRefunded extends MM_WPFS_EventProcessor
{
	use MM_WPFS_ChargeEventUtils;

	public function __construct(MM_WPFS_Database $db, MM_WPFS_Mailer $mailer, MM_WPFS_LoggerService $loggerService)
	{
		parent::__construct($db, $mailer, $loggerService);
	}

	public function getType()
	{
		return \StripeWPFS\Event::CHARGE_REFUNDED;
	}

	protected function processEvent($event, MM_WPFS_LiveModeAwareEventProcessorContext $context)
	{
		$charge = $this->getDataObject($event);
		if (!is_null($charge)) {
			$this->updatePaymentStatus($charge);
		}
	}
}

class MM_WPFS_ChargeSucceeded extends MM_WPFS_EventProcessor
{
	use MM_WPFS_ChargeEventUtils;

	public function __construct(MM_WPFS_Database $db, MM_WPFS_Mailer $mailer, MM_WPFS_LoggerService $loggerService)
	{
		parent::__construct($db, $mailer, $loggerService);
	}

	public function getType()
	{
		return \StripeWPFS\Event::CHARGE_SUCCEEDED;
	}

	protected function processEvent($event, MM_WPFS_LiveModeAwareEventProcessorContext $context)
	{
		$charge = $this->getDataObject($event);
		if (!is_null($charge)) {
			$this->updatePaymentStatus($charge);
		}
	}
}

class MM_WPFS_ChargeUpdated extends MM_WPFS_EventProcessor
{

	public function __construct(MM_WPFS_Database $db, MM_WPFS_Mailer $mailer, MM_WPFS_LoggerService $loggerService)
	{
		parent::__construct($db, $mailer, $loggerService);
	}

	public function getType()
	{
		return \StripeWPFS\Event::CHARGE_UPDATED;
	}

	protected function processEvent($event, MM_WPFS_LiveModeAwareEventProcessorContext $context)
	{
		// tnagy charge description or metadata updated, nothing to do here
	}
}

class MM_WPFS_CustomerSubscriptionUpdated extends MM_WPFS_EventProcessor
{

	public function getType()
	{
		return \StripeWPFS\Event::CUSTOMER_SUBSCRIPTION_UPDATED;
	}

	protected function processEvent($event, MM_WPFS_LiveModeAwareEventProcessorContext $context)
	{
		$previousAttributes = $this->getDataPreviousAttributes($event);
		if (!is_null($previousAttributes)) {
			/** @var \StripeWPFS\Subscription $stripeSubscription */
			$stripeSubscription = $this->getDataObject($event);
			if (!is_null($stripeSubscription)) {
				$wpfsSubscriber = $this->findSubscriberByStripeSubscriptionId($stripeSubscription->id);
				if (isset($wpfsSubscriber)) {
					if ($previousAttributes->offsetExists('quantity')) {
						$this->db->updateSubscriber(
							$wpfsSubscriber->subscriberID,
							array('quantity' => $stripeSubscription->quantity)
						);
					}
				}
			}
		}
	}

}