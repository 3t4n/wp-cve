<?php

// File generated from our OpenAPI spec

namespace StripeWPFS;

/**
 * Client used to send requests to Stripe's API.
 *
 * @property \StripeWPFS\Service\AccountLinkService $accountLinks
 * @property \StripeWPFS\Service\AccountService $accounts
 * @property \StripeWPFS\Service\ApplePayDomainService $applePayDomains
 * @property \StripeWPFS\Service\ApplicationFeeService $applicationFees
 * @property \StripeWPFS\Service\BalanceService $balance
 * @property \StripeWPFS\Service\BalanceTransactionService $balanceTransactions
 * @property \StripeWPFS\Service\BillingPortal\BillingPortalServiceFactory $billingPortal
 * @property \StripeWPFS\Service\ChargeService $charges
 * @property \StripeWPFS\Service\Checkout\CheckoutServiceFactory $checkout
 * @property \StripeWPFS\Service\CountrySpecService $countrySpecs
 * @property \StripeWPFS\Service\CouponService $coupons
 * @property \StripeWPFS\Service\CreditNoteService $creditNotes
 * @property \StripeWPFS\Service\CustomerService $customers
 * @property \StripeWPFS\Service\DisputeService $disputes
 * @property \StripeWPFS\Service\EphemeralKeyService $ephemeralKeys
 * @property \StripeWPFS\Service\EventService $events
 * @property \StripeWPFS\Service\ExchangeRateService $exchangeRates
 * @property \StripeWPFS\Service\FileLinkService $fileLinks
 * @property \StripeWPFS\Service\FileService $files
 * @property \StripeWPFS\Service\Identity\IdentityServiceFactory $identity
 * @property \StripeWPFS\Service\InvoiceItemService $invoiceItems
 * @property \StripeWPFS\Service\InvoiceService $invoices
 * @property \StripeWPFS\Service\Issuing\IssuingServiceFactory $issuing
 * @property \StripeWPFS\Service\MandateService $mandates
 * @property \StripeWPFS\Service\OAuthService $oauth
 * @property \StripeWPFS\Service\OrderReturnService $orderReturns
 * @property \StripeWPFS\Service\OrderService $orders
 * @property \StripeWPFS\Service\PaymentIntentService $paymentIntents
 * @property \StripeWPFS\Service\PaymentLinkService $paymentLinks
 * @property \StripeWPFS\Service\PaymentMethodService $paymentMethods
 * @property \StripeWPFS\Service\PayoutService $payouts
 * @property \StripeWPFS\Service\PlanService $plans
 * @property \StripeWPFS\Service\PriceService $prices
 * @property \StripeWPFS\Service\ProductService $products
 * @property \StripeWPFS\Service\PromotionCodeService $promotionCodes
 * @property \StripeWPFS\Service\QuoteService $quotes
 * @property \StripeWPFS\Service\Radar\RadarServiceFactory $radar
 * @property \StripeWPFS\Service\RefundService $refunds
 * @property \StripeWPFS\Service\Reporting\ReportingServiceFactory $reporting
 * @property \StripeWPFS\Service\ReviewService $reviews
 * @property \StripeWPFS\Service\SetupAttemptService $setupAttempts
 * @property \StripeWPFS\Service\SetupIntentService $setupIntents
 * @property \StripeWPFS\Service\ShippingRateService $shippingRates
 * @property \StripeWPFS\Service\Sigma\SigmaServiceFactory $sigma
 * @property \StripeWPFS\Service\SkuService $skus
 * @property \StripeWPFS\Service\SourceService $sources
 * @property \StripeWPFS\Service\SubscriptionItemService $subscriptionItems
 * @property \StripeWPFS\Service\SubscriptionScheduleService $subscriptionSchedules
 * @property \StripeWPFS\Service\SubscriptionService $subscriptions
 * @property \StripeWPFS\Service\TaxCodeService $taxCodes
 * @property \StripeWPFS\Service\TaxRateService $taxRates
 * @property \StripeWPFS\Service\Terminal\TerminalServiceFactory $terminal
 * @property \StripeWPFS\Service\TokenService $tokens
 * @property \StripeWPFS\Service\TopupService $topups
 * @property \StripeWPFS\Service\TransferService $transfers
 * @property \StripeWPFS\Service\WebhookEndpointService $webhookEndpoints
 */
class StripeClient extends BaseStripeClient
{
    /**
     * @var \StripeWPFS\Service\CoreServiceFactory
     */
    private $coreServiceFactory;

    public function __get($name)
    {
        if (null === $this->coreServiceFactory) {
            $this->coreServiceFactory = new \StripeWPFS\Service\CoreServiceFactory($this);
        }

        return $this->coreServiceFactory->__get($name);
    }
}
