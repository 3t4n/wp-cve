<?php

class WPFS_InvalidTaxIdException extends Exception
{
    // We just use the type information, no customized behavior
}

class MM_WPFS_Pricing
{
    public function __construct()
    {
    }

    public static function getCountryCodesRequiringPostalCodeForTax()
    {
        return [
            'CA',
            'US'
        ];
    }

    public static function createFormPriceCalculator($pricingData, $loggerService)
    {
        switch ($pricingData->formType) {
            case MM_WPFS::FORM_TYPE_INLINE_PAYMENT:
                return new MM_WPFS_InlinePaymentPriceCalculator($pricingData, $loggerService);

            case MM_WPFS::FORM_TYPE_CHECKOUT_PAYMENT:
                return new MM_WPFS_CheckoutPaymentPriceCalculator($pricingData, $loggerService);

            case MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION:
                return new MM_WPFS_InlineSubscriptionPriceCalculator($pricingData, $loggerService);

            case MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION:
                return new MM_WPFS_CheckoutSubscriptionPriceCalculator($pricingData, $loggerService);

            default:
                MM_WPFS_Utils::log(__CLASS__ . '.' . __FUNCTION__ . ": unknown form type '{$pricingData->formType}'!");
                throw new Exception("Unknown form type '{$pricingData->formType}'!");
        }
    }

    public static function extractSimplifiedPricingFromInvoiceLineItems($lineItems)
    {
        $result = new \StdClass;
        $amountTotal = 0;
        $taxExclusiveTotal = 0;
        $taxInclusiveTotal = 0;
        $discountTotal = 0;

        foreach ($lineItems as $lineItem) {
            $amountTotal += $lineItem->amount;

            foreach ($lineItem->discount_amounts as $discountAmount) {
                $discountTotal += $discountAmount->amount;
            }

            foreach ($lineItem->tax_amounts as $taxAmount) {
                if ($taxAmount->inclusive) {
                    $taxInclusiveTotal += $taxAmount->amount;
                } else {
                    $taxExclusiveTotal += $taxAmount->amount;
                }
            }
        }

        $result->totalAmount = $amountTotal;
        $result->taxAmountExclusive = $taxExclusiveTotal;
        $result->taxAmountInclusive = $taxInclusiveTotal;
        $result->discountAmount = $discountTotal;

        return $result;
    }

    public static function extractSubscriptionPricingFromInvoiceLineItems($lineItems)
    {
        $result = new \StdClass;
        $result->product = null;
        $result->setupFee = null;

        foreach ($lineItems as $lineItem) {
            $statItem = new \StdClass;
            $statItem->quantity = $lineItem->quantity;
            $statItem->amount = 0;
            $statItem->taxExclusive = 0;
            $statItem->taxInclusive = 0;
            $statItem->discount = 0;

            $statItem->amount += $lineItem->amount;

            foreach ($lineItem->discount_amounts as $discountAmount) {
                $statItem->discount += $discountAmount->amount;
            }

            foreach ($lineItem->tax_amounts as $taxAmount) {
                if ($taxAmount->inclusive) {
                    $statItem->taxInclusive += $taxAmount->amount;
                } else {
                    $statItem->taxExclusive += $taxAmount->amount;
                }

            }

            $metaData = $lineItem->metadata;
            if ($metaData !== null && isset($metaData->type) && $metaData->type === 'setupFee') {
                $result->setupFee = $statItem;
            } else {
                $result->product = $statItem;
            }
        }

        return $result;
    }

    public static function extractTaxRateIdsStatic($taxRates)
    {
        $taxRateIds = [];

        foreach ($taxRates as $taxRate) {
            array_push($taxRateIds, $taxRate->taxRateId);
        }

        return $taxRateIds;
    }

    /**
     * @param $savedProducts array
     * @return array
     */
    public static function extractPriceIdsFromProductsStatic($savedProducts)
    {
        $priceIds = array();

        foreach ($savedProducts as $savedProduct) {
            array_push($priceIds, $savedProduct->stripePriceId);
        }

        return $priceIds;
    }
}

abstract class MM_WPFS_PriceCalculator
{
    use MM_WPFS_Logger_AddOn;
    use MM_WPFS_StaticContext_AddOn;

    /** @var $stripe MM_WPFS_Stripe */
    protected $stripe;
    /** @var $db MM_WPFS_Database */
    protected $db = null;
    /** @var $options MM_WPFS_Options */
    protected $options = null;
    protected $pricingData;

    public function __construct($pricingData, $loggerService)
    {
        $this->initLogger($loggerService, MM_WPFS_LoggerService::MODULE_RUNTIME);
        $this->options = new MM_WPFS_Options();

        $this->initStaticContext();

        $this->pricingData = $pricingData;
        $this->stripe = new MM_WPFS_Stripe(MM_WPFS_Stripe::getStripeAuthenticationToken($this->staticContext), $loggerService);
        $this->db = new MM_WPFS_Database();
    }

    protected abstract function getFormFromDatabase();
    protected abstract function getApplicableTaxRates($form);
    protected abstract function getProductBuckets($form);
    protected function prepareStripeInvoiceParams($pricingData, $products, $taxRateIds)
    {
        $result = [];

        $address = [
            'country' => $pricingData->country
        ];
        if ($pricingData->stripeTax) {
            $result['automatic_tax'] = [
                'enabled' => true
            ];

            if (!empty($pricingData->zip)) {
                $address['postal_code'] = $pricingData->zip;
            }
        } else {
            if (!empty($pricingData->state)) {
                $address['state'] = $pricingData->state;
            }
        }
        $result['customer_details'] = [
            'address' => $address
        ];

        if ($pricingData->stripeTax && $pricingData->taxIdType && $pricingData->taxId) {
            $result['customer_details']['tax_ids'] = [
                [
                    'type' => $pricingData->taxIdType,
                    'value' => $pricingData->taxId,
                ]
            ];
        }

        return $result;
    }
    protected abstract function extractPaymentDetailsFromInvoiceLineItems($lineItems, $taxRates, $products);

    /**
     * @param $form
     * @param $pricingData
     * @return array|mixed
     */
    public static function getApplicableInlineTaxRatesStatic($form, $pricingData)
    {
        $taxRates = json_decode($form->vatRates);

        $applicableTaxRates = [];
        if ($form->vatRateType === MM_WPFS::FIELD_VALUE_TAX_RATE_FIXED) {
            $applicableTaxRates = $taxRates;
        } else if ($form->vatRateType === MM_WPFS::FIELD_VALUE_TAX_RATE_DYNAMIC) {
            $applicableTaxRates = MM_WPFS_PriceCalculator::filterApplicableTaxRatesStatic(
                $pricingData->country,
                $pricingData->state,
                $taxRates,
                $pricingData->taxId
            );
        }

        return $applicableTaxRates;
    }

    /**
     * @param $country
     * @param $state
     * @param $taxRates
     * @param $taxId
     *
     * @return array
     */
    public static function filterApplicableTaxRatesStatic($country, $state, $taxRates, $taxId)
    {
        $applicableTaxRates = [];
        if (!empty($country)) {
            if ($country === MM_WPFS::COUNTRY_CODE_UNITED_STATES && !empty($state)) {
                foreach ($taxRates as $taxRate) {
                    if (
                        $taxRate->country === $country &&
                        $taxRate->state === $state
                    ) {
                        array_push($applicableTaxRates, $taxRate);
                    }
                }
            } else if ($country !== MM_WPFS::COUNTRY_CODE_UNITED_STATES) {
                foreach ($taxRates as $taxRate) {
                    if ($taxRate->country === $country) {
                        array_push($applicableTaxRates, $taxRate);
                    }
                }
            }
        }

        $params = [
            'country' => $country,
            'state' => $state,
            'taxId' => $taxId
        ];
        $filteredTaxRates = apply_filters('fullstripe_determine_tax_rates', $applicableTaxRates, $params);

        return $filteredTaxRates;
    }

    /**
     * @param $country
     * @param $state
     * @param $taxRates
     * @param $taxId
     *
     * @return array
     */
    protected function selectApplicableTaxRates($country, $state, $taxRates, $taxId)
    {
        return self::filterApplicableTaxRatesStatic($country, $state, $taxRates, $taxId);
    }

    protected function extractCurrencyFromProducts($products)
    {
        $result = MM_WPFS::CURRENCY_USD;

        if (count($products) > 0) {
            $result = ($products[0])->currency;
        }

        return $result;
    }

    /**
     * @param $form \StdClass
     * @return mixed
     */
    protected function extractCurrencyFromForm($form)
    {
        return $form->currency;
    }

    protected function generateTaxIdStripeErrorMessage()
    {
        $taxIdType = $this->pricingData->taxIdType ?? '';

        return "Invalid value for {$taxIdType}.";
    }

    protected function getProductPricesFromStripe($form, $taxRates)
    {
        $productPricing = [];

        $productBuckets = $this->getProductBuckets($form);

        foreach ($productBuckets as $productBucket) {
            $lines = null;
            try {
                $invoiceParams = $this->prepareStripeInvoiceParams(
                    $this->pricingData,
                    $productBucket,
                    MM_WPFS_Pricing::extractTaxRateIdsStatic($taxRates)
                );

                // We use this to get the actual prices for each of the prodcucts
                // including tax etc. An upcoming invoice is a "test" invoice and won't get saved
                // but we get all the information we need to display the price details
                // It wouldn't be enough to get get the price objects as they wouldn't have the tax
                // calculation
                $invoice = $this->stripe->getUpcomingInvoice($invoiceParams);
                if ($invoice->lines->has_more) {
                    // has_more means there are more prices than returned
                    // limit is usually 10
                    // Stripe returns a URL where we can get all the lines
                    // we just need the params though as we use the Stripe client
                    $next_url = $invoice->lines->url;
                    // split parameters from url and urldecode
                    $next_url = explode('?', $next_url)[1];
                    $next_url = urldecode($next_url);
                    // convert the string to an array
                    $next_url = wp_parse_args($next_url);
                    $next_url['limit'] = 100;
                    $invoice_line_items = $this->stripe->getUpcomingInvoiceItems($next_url);
                    $lines = $invoice_line_items->data;
                } else {
                    $lines = $invoice->lines->data;
                }
            } catch (Exception $ex) {
                if ($ex->getMessage() === $this->generateTaxIdStripeErrorMessage()) {
                    throw new WPFS_InvalidTaxIdException($ex->getMessage());
                } else {
                    throw $ex;
                }
            }

            if ($lines !== null) {
                $productPricing = array_merge($productPricing, $this->extractPaymentDetailsFromInvoiceLineItems($lines, $taxRates, $productBucket));
            }
        }

        return $productPricing;
    }

    public function getProductPrices()
    {
        $form = $this->getFormFromDatabase();
        $taxRates = $this->getApplicableTaxRates($form);

        return $this->getProductPricesFromStripe($form, $taxRates);
    }

    /**
     * @param $taxItem
     * @return string|null
     */
    protected function determineTaxLabel($taxItem)
    {
        $params = [
            'taxableAmount' => $taxItem->taxable_amount,
            'amount' => $taxItem->amount,
            'inclusive' => $taxItem->inclusive,
            'taxabilityReason' => $taxItem->taxability_reason,
            'country' => $this->pricingData->country,
            'state' => $this->pricingData->state,
            'postalCode' => $this->pricingData->zip,
            'taxIdType' => $this->pricingData->taxIdType,
            'taxId' => $this->pricingData->taxId,
        ];

        $result = __('Tax', 'wp-full-stripe');
        try {
            $result = apply_filters(MM_WPFS::FILTER_NAME_DETERMINE_TAX_LABEL, $result, $params);
        } catch (Exception $ex) {
            $this->logger->error(__FUNCTION__, 'Cannot determine tax label', $ex);
        }

        return $result;
    }
}

abstract class MM_WPFS_PaymentPriceCalculator extends MM_WPFS_PriceCalculator
{

    protected function getProductBuckets($form)
    {
        $decoratedProducts = [];
        if (!is_null($this->pricingData->customAmount)) {
            $customAmountProduct = new \StdClass;
            $customAmountProduct->isCustomAmount = true;

            $decoratedProducts[] = $customAmountProduct;
        }
        if ($form->customAmount !== MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT) {
            $decoratedProducts = array_merge($decoratedProducts, MM_WPFS_Utils::decodeJsonArray($form->decoratedProducts));
        }

        if (count($decoratedProducts) === 0) {
            return [];
        }
        if ($this->pricingData->couponPercentOff) {
            return [$decoratedProducts];
        }

        $result = [];
        foreach ($decoratedProducts as $decoratedProduct) {
            array_push($result, [$decoratedProduct]);
        }

        return $result;
    }

    protected function prepareStripeInvoiceParams($pricingData, $products, $taxRateIds)
    {
        $result = parent::prepareStripeInvoiceParams($pricingData, $products, $taxRateIds);
        $form = $this->getFormFromDatabase();

        if (!empty($pricingData->couponCode)) {
            $result['discounts'] = [
                [
                    'coupon' => $pricingData->couponCode
                ]
            ];
        }

        $invoiceItems = [];
        foreach ($products as $product) {
            $invoiceItem = null;

            if (isset($product->isCustomAmount)) {
                $invoiceItem = [
                    'amount' => $pricingData->customAmount,
                    'currency' => $this->extractCurrencyFromForm($form),
                    'description' => __('Other amount', 'wp-full-stripe'),
                    'metadata' => [
                        'type' => 'customAmount'
                    ]
                ];
            } else {
                $invoiceItem = [
                    'price' => $product->stripePriceId
                ];
            }
            if (!$pricingData->stripeTax) {
                $invoiceItem['tax_rates'] = $taxRateIds;
            }

            array_push($invoiceItems, $invoiceItem);
        }
        $result['invoice_items'] = $invoiceItems;

        return $result;
    }

    protected function extractPaymentDetailsFromInvoiceLineItems($lineItems, $taxRates, $products)
    {
        $productPricing = [];

        foreach ($lineItems as $lineItem) {
            $dislayItems = [];

            $priceId = $lineItem->price->id;
            $metaData = $lineItem->metadata;
            if (
                $metaData !== null && isset($metaData->type) &&
                $metaData->type === MM_WPFS::LINE_ITEM_TYPE_CUSTOM_AMOUNT
            ) {
                $priceId = MM_WPFS::PRICE_ID_CUSTOM_AMOUNT;
            }
            $currency = $lineItem->currency;
            $displayName = $lineItem->description;
            $amount = $lineItem->amount;

            $displayItem = new \StdClass;
            $displayItem->type = MM_WPFS::LINE_ITEM_TYPE_PRODUCT;
            $displayItem->subType = MM_WPFS::LINE_ITEM_TYPE_PRODUCT;
            $displayItem->id = $priceId;
            $displayItem->displayName = $displayName;
            $displayItem->currency = $currency;
            $displayItem->amount = $amount;
            array_push($dislayItems, $displayItem);

            $discountTotal = 0;
            foreach ($lineItem->discount_amounts as $discountAmount) {
                $discountTotal += $discountAmount->amount;
            }
            if ($discountTotal > 0) {
                $displayItem = new \StdClass;
                $displayItem->type = MM_WPFS::LINE_ITEM_TYPE_PRODUCT;
                $displayItem->subType = MM_WPFS::LINE_ITEM_SUBTYPE_DISCOUNT;
                $displayItem->id = null;
                $displayItem->displayName = __('Discount', 'wp-full-stripe');
                $displayItem->currency = $currency;
                $displayItem->amount = -$discountTotal;
                array_push($dislayItems, $displayItem);
            }

            $taxItemLookup = [];
            foreach ($lineItem->tax_amounts as $taxItem) {
                $taxItemLookup[$taxItem->tax_rate] = $taxItem;
            }
            if ($this->pricingData->stripeTax) {
                foreach ($taxItemLookup as $taxRateId => $taxItem) {
                    if ($taxItem->amount > 0) {
                        $displayItem = new \StdClass;
                        $displayItem->type = MM_WPFS::LINE_ITEM_TYPE_PRODUCT;
                        $displayItem->subType = MM_WPFS::LINE_ITEM_SUBTYPE_TAX;
                        $displayItem->id = $taxRateId;
                        $displayItem->displayName = $this->determineTaxLabel($taxItem);
                        $displayItem->currency = $currency;
                        $displayItem->amount = $taxItem->amount;
                        $displayItem->percentage = null;
                        $displayItem->inclusive = $taxItem->inclusive;
                        array_push($dislayItems, $displayItem);
                    }
                }
            } else {
                foreach ($taxRates as $taxRate) {
                    $taxAmount = array_key_exists($taxRate->taxRateId, $taxItemLookup) ? $taxItemLookup[$taxRate->taxRateId]->amount : 0;

                    if ($taxAmount > 0) {
                        $displayItem = new \StdClass;
                        $displayItem->type = MM_WPFS::LINE_ITEM_TYPE_PRODUCT;
                        $displayItem->subType = MM_WPFS::LINE_ITEM_SUBTYPE_TAX;
                        $displayItem->id = $taxRate->taxRateId;
                        $displayItem->displayName = $taxRate->displayName;
                        $displayItem->currency = $currency;
                        $displayItem->amount = $taxAmount;
                        $displayItem->percentage = $taxRate->percentage;
                        $displayItem->inclusive = $taxRate->inclusive;
                        array_push($dislayItems, $displayItem);
                    }
                }
            }

            $productPricing[$priceId] = $dislayItems;
        }

        return $productPricing;
    }
}

trait MM_WPFS_InlineTaxCalculator
{

    protected function getApplicableTaxRates($form)
    {
        return MM_WPFS_PriceCalculator::getApplicableInlineTaxRatesStatic($form, $this->pricingData);
    }
}

trait MM_WPFS_CheckoutTaxCalculator
{
    protected function getApplicableTaxRates($form)
    {
        $taxRates = json_decode($form->vatRates);

        $applicableTaxRates = [];
        if ($form->vatRateType === MM_WPFS::FIELD_VALUE_TAX_RATE_FIXED) {
            $applicableTaxRates = $taxRates;
        } else if ($form->vatRateType === MM_WPFS::FIELD_VALUE_TAX_RATE_DYNAMIC) {
            $applicableTaxRates = [];
        }

        return $applicableTaxRates;
    }
}

class MM_WPFS_InlinePaymentPriceCalculator extends MM_WPFS_PaymentPriceCalculator
{
    use MM_WPFS_InlineTaxCalculator;

    protected function getFormFromDatabase()
    {
        return $this->db->getInlinePaymentFormByName($this->pricingData->formId);
    }
}

class MM_WPFS_CheckoutPaymentPriceCalculator extends MM_WPFS_PaymentPriceCalculator
{
    use MM_WPFS_CheckoutTaxCalculator;

    protected function getFormFromDatabase()
    {
        return $this->db->getCheckoutPaymentFormByName($this->pricingData->formId);
    }
}

abstract class MM_WPFS_SubscriptionPriceCalculator extends MM_WPFS_PriceCalculator
{
    protected function getProductBuckets($form)
    {
        $result = [];
        $decoratedPlans = MM_WPFS_Utils::decodeJsonArray($form->decoratedPlans);

        if ($this->pricingData->couponPercentOff) {
            $hashedBuckets = [];
            foreach ($decoratedPlans as $decoratedPlan) {
                $key = $decoratedPlan->currency . $decoratedPlan->interval . $decoratedPlan->intervalCount;

                if (array_key_exists($key, $hashedBuckets)) {
                    $hashedBucket = $hashedBuckets[$key];
                    array_push($hashedBucket, $decoratedPlan);
                    $hashedBuckets[$key] = $hashedBucket;
                } else {
                    $hashedBuckets[$key] = [$decoratedPlan];
                }
            }

            foreach ($hashedBuckets as $hashedBucket) {
                array_push($result, $hashedBucket);
            }
        } else {
            foreach ($decoratedPlans as $decoratedPlan) {
                array_push($result, [$decoratedPlan]);
            }
        }

        return $result;
    }

    protected function prepareStripeInvoiceParams($pricingData, $products, $taxRateIds)
    {
        $result = parent::prepareStripeInvoiceParams($pricingData, $products, $taxRateIds);

        if (!empty($pricingData->couponCode)) {
            $result['coupon'] = $pricingData->couponCode;
        }

        $invoiceItems = [];
        $subscriptionItems = [];
        foreach ($products as $product) {
            $subscriptionItem = [
                'price' => $product->stripePriceId,
                'quantity' => $pricingData->quantity,
            ];
            if (!$pricingData->stripeTax) {
                $subscriptionItem['tax_rates'] = $taxRateIds;
            }
            array_push($subscriptionItems, $subscriptionItem);

            if ($product->setupFee > 0) {
                $invoiceItem = [
                    'amount' => $product->setupFee * $pricingData->quantity,
                    'currency' => $product->currency,
                    'description' => __('Setup fee', 'wp-full-stripe'),
                    'metadata' => [
                        'type' => 'setupFee',
                        'priceId' => $product->stripePriceId
                    ],
                ];
                if (!$pricingData->stripeTax) {
                    $invoiceItem['tax_rates'] = $taxRateIds;
                }
                array_push($invoiceItems, $invoiceItem);
            }
        }

        $result['invoice_items'] = $invoiceItems;
        $result['subscription_items'] = $subscriptionItems;

        return $result;
    }

    /**
     * @param $lineItems
     * @return mixed
     */
    protected function extractCurrencyFromLineItems($lineItems)
    {
        return $lineItems[0]->currency;
    }

    /**
     * @param $label
     * @param $quantity
     * @return string
     */
    protected function createLineItemDisplayName($label, $quantity)
    {
        return $quantity === 1 ? $label : "{$quantity}x {$label}";
    }

    protected function getTaxDisplayName($taxRates)
    {
        $result = __('Tax', 'wp-full-stripe');

        if (count($taxRates) > 0) {
            $result = $taxRates[0]->displayName;

            for ($idx = 1; $idx < count($taxRates); $idx++) {
                $taxRate = $taxRates[$idx];

                if ($result !== $taxRate->displayName) {
                    $result = __('Tax', 'wp-full-stripe');
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * @param $lineItems
     * @param $taxRates
     * @param $products
     * @return array
     */
    protected function extractPaymentDetailsFromInvoiceLineItems($lineItems, $taxRates, $products)
    {
        $productPricing = [];

        $productLookup = [];
        foreach ($products as $product) {
            $productLookup[$product->stripePriceId] = $product;
        }

        $priceGroupLookup = [];
        foreach ($lineItems as $lineItem) {
            $priceId = $lineItem->price->id;
            $isSetupFee = false;

            $metaData = $lineItem->metadata;
            // $this->logger->info(__FUNCTION__, 'metaData: ' . print_r( $lineItem, true ));
            if ($metaData !== null && !empty((array) $metaData) && $metaData->type === MM_WPFS::LINE_ITEM_TYPE_SETUP_FEE) {
                $isSetupFee = true;
                $priceId = $metaData->priceId;
            }

            if (!array_key_exists($priceId, $priceGroupLookup)) {
                $priceGroupLookup[$priceId] = [];
            }

            $priceItems = $priceGroupLookup[$priceId];
            if (count($priceItems) == 0 || count($priceItems) > 1) {
                array_push($priceItems, $lineItem);
            } else if (count($priceItems) === 1) {
                if ($isSetupFee) {
                    array_unshift($priceItems, $lineItem);
                } else {
                    array_push($priceItems, $lineItem);
                }
            }
            $priceGroupLookup[$priceId] = $priceItems;
        }

        foreach ($priceGroupLookup as $priceId => $lineItems) {
            $dislayItems = [];

            foreach ($lineItems as $lineItem) {
                $priceId = $lineItem->price->id;
                $type = MM_WPFS::LINE_ITEM_TYPE_PRODUCT;
                $discountTotal = 0;
                $taxAmountLookup = [];

                $metaData = $lineItem->metadata;
                if ($metaData !== null && !empty((array) $metaData) && $metaData->type === MM_WPFS::LINE_ITEM_TYPE_SETUP_FEE) {
                    $priceId = $metaData->priceId;
                    $type = MM_WPFS::LINE_ITEM_TYPE_SETUP_FEE;
                    $displayName = $this->createLineItemDisplayName(__('Setup fee', 'wp-full-stripe'), $lineItem->quantity);
                } else {
                    $displayName = $this->createLineItemDisplayName($productLookup[$priceId]->name, $lineItem->quantity);
                }

                $displayItem = new \StdClass;
                $displayItem->type = $type;
                $displayItem->subType = $type;
                $displayItem->id = $priceId;
                $displayItem->displayName = $displayName;
                $displayItem->currency = $lineItem->currency;
                $displayItem->amount = $lineItem->amount;
                array_push($dislayItems, $displayItem);

                foreach ($lineItem->discount_amounts as $discountAmount) {
                    $discountTotal += $discountAmount->amount;
                }

                if ($discountTotal > 0) {
                    $displayItem = new \StdClass;
                    $displayItem->type = $type;
                    $displayItem->subType = MM_WPFS::LINE_ITEM_SUBTYPE_DISCOUNT;
                    $displayItem->id = $priceId;
                    $displayItem->displayName = __('Discount', 'wp-full-stripe');
                    $displayItem->currency = $this->extractCurrencyFromLineItems($lineItems);
                    $displayItem->amount = -$discountTotal;
                    array_push($dislayItems, $displayItem);
                }

                $taxItemLookup = [];
                foreach ($lineItem->tax_amounts as $taxItem) {
                    $taxItemLookup[$taxItem->tax_rate] = $taxItem;
                }

                if ($this->pricingData->stripeTax) {
                    foreach ($taxItemLookup as $taxRateId => $taxItem) {
                        if ($taxItem->amount > 0) {
                            $displayItem = new \StdClass;
                            $displayItem->type = $type;
                            $displayItem->subType = MM_WPFS::LINE_ITEM_SUBTYPE_TAX;
                            $displayItem->id = $taxRateId;
                            $displayItem->displayName = $this->determineTaxLabel($taxItem);
                            $displayItem->currency = $this->extractCurrencyFromLineItems($lineItems);
                            $displayItem->amount = $taxItem->amount;
                            $displayItem->percentage = null;
                            $displayItem->inclusive = $taxItem->inclusive;
                            array_push($dislayItems, $displayItem);
                        }
                    }
                } else {
                    foreach ($taxRates as $taxRate) {
                        $taxAmount = array_key_exists($taxRate->taxRateId, $taxItemLookup) ? $taxItemLookup[$taxRate->taxRateId]->amount : 0;

                        if ($taxAmount > 0) {
                            $displayItem = new \StdClass;
                            $displayItem->type = $type;
                            $displayItem->subType = MM_WPFS::LINE_ITEM_SUBTYPE_TAX;
                            $displayItem->id = $taxRate->taxRateId;
                            $displayItem->displayName = $taxRate->displayName;
                            $displayItem->currency = $this->extractCurrencyFromLineItems($lineItems);
                            $displayItem->amount = $taxAmount;
                            $displayItem->percentage = $taxRate->percentage;
                            $displayItem->inclusive = $taxRate->inclusive;
                            array_push($dislayItems, $displayItem);
                        }
                    }
                }
            }

            $productPricing[$priceId] = $dislayItems;
        }

        return $productPricing;
    }
}

class MM_WPFS_InlineSubscriptionPriceCalculator extends MM_WPFS_SubscriptionPriceCalculator
{
    use MM_WPFS_InlineTaxCalculator;

    protected function getFormFromDatabase()
    {
        return $this->db->getInlineSubscriptionFormByName($this->pricingData->formId);
    }
}

class MM_WPFS_CheckoutSubscriptionPriceCalculator extends MM_WPFS_SubscriptionPriceCalculator
{
    use MM_WPFS_CheckoutTaxCalculator;

    protected function getFormFromDatabase()
    {
        return $this->db->getCheckoutSubscriptionFormByName($this->pricingData->formId);
    }
}

class MM_WPFS_CustomerTaxId
{

    /**
     * @return array
     */
    static function getTaxIdTypes()
    {
        return [
            [
                'id' => 'au_abn',
                'description' => __('Australian Business Number (AU ABN)', 'wp-full-stripe'),
                'countryCode' => 'AU',
                'example' => '12345678912'
            ],
            [
                'id' => 'au_arn',
                'description' => __('Australian Taxation Office Reference Number', 'wp-full-stripe'),
                'countryCode' => 'AU',
                'example' => '123456789123'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'AT',
                'example' => 'ATU12345678'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'BE',
                'example' => 'BE0123456789'
            ],
            [
                'id' => 'br_cnpj',
                'description' => __('Brazilian CNPJ number', 'wp-full-stripe'),
                'countryCode' => 'BR',
                'example' => '01.234.456/5432-10'
            ],
            [
                'id' => 'br_cpf',
                'description' => __('Brazilian CPF number', 'wp-full-stripe'),
                'countryCode' => 'BR',
                'example' => '123.456.789-87'
            ],
            [
                'id' => 'bg_uic',
                'description' => __('Bulgaria Unified Identification Code', 'wp-full-stripe'),
                'countryCode' => 'BG',
                'example' => '123456789'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'BG',
                'example' => 'BG0123456789'
            ],
            [
                'id' => 'ca_bn',
                'description' => __('Canadian BN', 'wp-full-stripe'),
                'countryCode' => 'CA',
                'example' => '123456789'
            ],
            [
                'id' => 'ca_gst_hst',
                'description' => __('Canadian GST/HST number', 'wp-full-stripe'),
                'countryCode' => 'CA',
                'example' => '123456789RT0002'
            ],
            [
                'id' => 'ca_pst_bc',
                'description' => __('Canadian PST number (British Columbia)', 'wp-full-stripe'),
                'countryCode' => 'CA',
                'example' => 'PST-1234-5678'
            ],
            [
                'id' => 'ca_pst_mb',
                'description' => __('Canadian PST number (Manitoba)', 'wp-full-stripe'),
                'countryCode' => 'CA',
                'example' => '123456-7'
            ],
            [
                'id' => 'ca_pst_sk',
                'description' => __('Canadian PST number (Saskatchewan)', 'wp-full-stripe'),
                'countryCode' => 'CA',
                'example' => '1234567'
            ],
            [
                'id' => 'ca_qst',
                'description' => __('Canadian QST number (Québec)', 'wp-full-stripe'),
                'countryCode' => 'CA',
                'example' => '1234567890TQ1234'
            ],
            [
                'id' => 'cl_tin',
                'description' => __('Chilean TIN', 'wp-full-stripe'),
                'countryCode' => 'CL',
                'example' => 'Chilean TIN'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'HR',
                'example' => 'HR12345678912'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'CY',
                'example' => 'CY12345678Z'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'CZ',
                'example' => 'CZ1234567890'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'DK',
                'example' => 'DK12345678'
            ],
            [
                'id' => 'eg_tin',
                'description' => __('Egyptian Tax Identification Number', 'wp-full-stripe'),
                'countryCode' => 'EG',
                'example' => '123456789'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'EE',
                'example' => 'EE123456789'
            ],
            [
                'id' => 'eu_oss_vat',
                'description' => __('European One Stop Shop VAT number for non-Union scheme', 'wp-full-stripe'),
                'countryCode' => null,
                'example' => 'EU123456789'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'FI',
                'example' => 'FI12345678'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'FR',
                'example' => 'FRAB123456789'
            ],
            [
                'id' => 'ge_vat',
                'description' => __('Georgian VAT', 'wp-full-stripe'),
                'countryCode' => 'GE',
                'example' => '123456789'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'DE',
                'example' => 'DE123456789'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'GR',
                'example' => 'EL123456789'
            ],
            [
                'id' => 'hk_br',
                'description' => __('Hong Kong BR number', 'wp-full-stripe'),
                'countryCode' => 'HK',
                'example' => '12345678'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'HU',
                'example' => 'HU12345678912'
            ],
            [
                'id' => 'hu_tin',
                'description' => __('Hungary tax number (adószám)', 'wp-full-stripe'),
                'countryCode' => 'HU',
                'example' => '12345678-1-23'
            ],
            [
                'id' => 'is_vat',
                'description' => __('Icelandic VAT', 'wp-full-stripe'),
                'countryCode' => 'IS',
                'example' => '123456'
            ],
            [
                'id' => 'in_gst',
                'description' => __('Indian GST number', 'wp-full-stripe'),
                'countryCode' => 'IN',
                'example' => '12ABCDE3456FGZH'
            ],
            [
                'id' => 'id_npwp',
                'description' => __('Indonesian NPWP number', 'wp-full-stripe'),
                'countryCode' => 'ID',
                'example' => '12.345.678.9-012.345'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'IE',
                'example' => 'IE1234567AB'
            ],
            [
                'id' => 'il_vat',
                'description' => __('Israel VAT', 'wp-full-stripe'),
                'countryCode' => 'IL',
                'example' => '000012345'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'IT',
                'example' => 'IT12345678912'
            ],
            [
                'id' => 'jp_cn',
                'description' => __('Japanese Corporate Number (*Hōjin Bangō*)', 'wp-full-stripe'),
                'countryCode' => 'JP',
                'example' => '1234567891234'
            ],
            [
                'id' => 'jp_rn',
                'description' => __('Japanese Registered Foreign Businesses\' Registration Number (*Tōroku Kokugai Jigyōsha no Tōroku Bangō*)', 'wp-full-stripe'),
                'countryCode' => 'JP',
                'example' => '12345'
            ],
            [
                'id' => 'jp_trn',
                'description' => __('Japanese Tax Registration Number (*Tōroku Bangō*)', 'wp-full-stripe'),
                'countryCode' => 'JP',
                'example' => 'T1234567891234'
            ],
            [
                'id' => 'ke_pin',
                'description' => __('Kenya Revenue Authority Personal Identification Number', 'wp-full-stripe'),
                'countryCode' => 'KE',
                'example' => 'P000111111A'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'LV',
                'example' => 'LV12345678912'
            ],
            [
                'id' => 'li_uid',
                'description' => __('Liechtensteinian UID number', 'wp-full-stripe'),
                'countryCode' => 'LI',
                'example' => 'CHE123456789'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'LT',
                'example' => 'LT123456789123'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'LU',
                'example' => 'LU12345678'
            ],
            [
                'id' => 'my_frp',
                'description' => __('Malaysian FRP number', 'wp-full-stripe'),
                'countryCode' => 'MY',
                'example' => '12345678'
            ],
            [
                'id' => 'my_itn',
                'description' => __('Malaysian ITN', 'wp-full-stripe'),
                'countryCode' => 'MY',
                'example' => 'MT12345678'
            ],
            [
                'id' => 'my_sst',
                'description' => __('Malaysian SST number', 'wp-full-stripe'),
                'countryCode' => 'MY',
                'example' => 'A12-3456-78912345'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'MT',
                'example' => 'MT12345678'
            ],
            [
                'id' => 'mx_rfc',
                'description' => __('Mexican RFC number', 'wp-full-stripe'),
                'countryCode' => 'MX',
                'example' => 'ABC010203AB9'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'NL',
                'example' => 'NL123456789B12'
            ],
            [
                'id' => 'nz_gst',
                'description' => __('New Zealand GST number', 'wp-full-stripe'),
                'countryCode' => 'NZ',
                'example' => '123456789'
            ],
            [
                'id' => 'no_vat',
                'description' => __('Norwegian VAT number', 'wp-full-stripe'),
                'countryCode' => 'NO',
                'example' => '123456789MVA'
            ],
            [
                'id' => 'ph_tin',
                'description' => __('Philippines Tax Identification Number', 'wp-full-stripe'),
                'countryCode' => 'PH',
                'example' => '123456789012'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'PL',
                'example' => 'PL1234567890'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'PT',
                'example' => 'PT123456789'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'RO',
                'example' => 'RO1234567891'
            ],
            [
                'id' => 'ru_inn',
                'description' => __('Russian INN', 'wp-full-stripe'),
                'countryCode' => 'RU',
                'example' => '1234567891'
            ],
            [
                'id' => 'ru_kpp',
                'description' => __('Russian KPP', 'wp-full-stripe'),
                'countryCode' => 'RU',
                'example' => '123456789'
            ],
            [
                'id' => 'sa_vat',
                'description' => __('Saudi Arabia VAT', 'wp-full-stripe'),
                'countryCode' => 'SA',
                'example' => '123456789012345'
            ],
            [
                'id' => 'sg_gst',
                'description' => __('Singaporean GST', 'wp-full-stripe'),
                'countryCode' => 'SG',
                'example' => 'M12345678X'
            ],
            [
                'id' => 'sg_uen',
                'description' => __('Singaporean UEN', 'wp-full-stripe'),
                'countryCode' => 'SG',
                'example' => '123456789F'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'SK',
                'example' => 'SK1234567891'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'SI',
                'example' => 'SI12345678'
            ],
            [
                'id' => 'si_tin',
                'description' => __('Slovenia tax number (davčna številka)', 'wp-full-stripe'),
                'countryCode' => 'SI',
                'example' => '12345678'
            ],
            [
                'id' => 'za_vat',
                'description' => __('South African VAT number', 'wp-full-stripe'),
                'countryCode' => 'ZA',
                'example' => '4123456789'
            ],
            [
                'id' => 'kr_brn',
                'description' => __('Korean BRN', 'wp-full-stripe'),
                'countryCode' => 'KR',
                'example' => '123-45-67890'
            ],
            [
                'id' => 'es_cif',
                'description' => __('Spanish NIF number (previously Spanish CIF number)', 'wp-full-stripe'),
                'countryCode' => 'ES',
                'example' => 'A12345678'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'ES',
                'example' => 'ESA1234567Z'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('European VAT number', 'wp-full-stripe'),
                'countryCode' => 'SE',
                'example' => 'SE123456789123'
            ],
            [
                'id' => 'ch_vat',
                'description' => __('Switzerland VAT number', 'wp-full-stripe'),
                'countryCode' => 'CH',
                'example' => 'CHE-123.456.789 MWST'
            ],
            [
                'id' => 'tw_vat',
                'description' => __('Taiwanese VAT', 'wp-full-stripe'),
                'countryCode' => 'TW',
                'example' => '12345678'
            ],
            [
                'id' => 'th_vat',
                'description' => __('Thai VAT', 'wp-full-stripe'),
                'countryCode' => 'TH',
                'example' => '1234567891234'
            ],
            [
                'id' => 'tr_tin',
                'description' => __('Turkish Tax Identification Number', 'wp-full-stripe'),
                'countryCode' => 'TR',
                'example' => '0123456789'
            ],
            [
                'id' => 'ua_vat',
                'description' => __('Ukrainian VAT', 'wp-full-stripe'),
                'countryCode' => 'UA',
                'example' => '123456789'
            ],
            [
                'id' => 'ae_trn',
                'description' => __('United Arab Emirates TRN', 'wp-full-stripe'),
                'countryCode' => 'AE',
                'example' => '123456789012345'
            ],
            [
                'id' => 'eu_vat',
                'description' => __('Northern Ireland VAT number', 'wp-full-stripe'),
                'countryCode' => 'GB',
                'example' => 'XI123456789'
            ],
            [
                'id' => 'gb_vat',
                'description' => __('United Kingdom VAT number', 'wp-full-stripe'),
                'countryCode' => 'GB',
                'example' => 'GB123456789'
            ],
            [
                'id' => 'us_ein',
                'description' => __('United States EIN', 'wp-full-stripe'),
                'countryCode' => 'US',
                'example' => '12-3456789'
            ],
        ];
    }

    static function getUniqueTaxIdTypes()
    {
        $result = [];

        foreach (self::getTaxIdTypes() as $taxIdItem) {
            $taxId = $taxIdItem['id'];

            if (!array_key_exists($taxId, $result)) {
                $result[$taxId] = [
                    'id' => $taxId,
                    'description' => $taxIdItem['description']
                ];
            }
        }

        return array_values($result);
    }

    static function getTaxIdTypesByCountry()
    {
        $result = [];

        foreach (self::getTaxIdTypes() as $taxIdTypeItem) {
            $countryCode = $taxIdTypeItem['countryCode'];
            $id = $taxIdTypeItem['id'];

            $countryItem = $countryCode !== null && array_key_exists($countryCode, $result) ? $result[$countryCode] : [];
            if (!array_key_exists($id, $countryItem)) {
                $countryItem[$id] = [
                    'id' => $id,
                    'example' => $taxIdTypeItem['example']
                ];
            }
            $result[$countryCode] = $countryItem;
        }

        return $result;
    }
}