<?php

namespace WunderAuto;

/**
 * Register all actions and filters for the plugin
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 */
class Loader
{
    /**
     * Add a new action to the collection to be registered with WordPress.
     *
     * @param string $hook         The name of the WordPress action that is being registered.
     * @param object $component    A reference to the instance of the object on which the action is defined.
     * @param string $callback     The name of the function definition on the $component.
     * @param int    $priority     Optional. The priority at which the function should be fired. Default is 10.
     * @param int    $acceptedArgs Optional. The number of arguments that should be passed to the $callback.
     *
     * @return void
     */
    public function addAction($hook, $component, $callback, $priority = 10, $acceptedArgs = 1)
    {
        add_action(
            $hook,
            $this->makeCallable($component, $callback),
            $priority,
            $acceptedArgs
        );
    }

    /**
     * Add a new filter to the collection to be registered with WordPress.
     *
     * @param string $hook         The name of the WordPress filter that is being registered.
     * @param object $component    A reference to the instance of the object on which the filter is defined.
     * @param string $callback     The name of the function definition on the $component.
     * @param int    $priority     Optional. The priority at which the function should be fired. Default is 10.
     * @param int    $acceptedArgs Optional. The number of arguments that should be passed to the $callback.
     *
     * @return void
     */
    public function addFilter($hook, $component, $callback, $priority = 10, $acceptedArgs = 1)
    {
        add_filter(
            $hook,
            $this->makeCallable($component, $callback),
            $priority,
            $acceptedArgs
        );
    }

    /**
     * Wrapper to return a (correctly typed) callable
     *
     * @param object $object
     * @param string $method
     *
     * @return callable
     */
    private function makeCallable($object, $method)
    {
        return [$object, $method]; // @phpstan-ignore-line
    }

    /**
     * Add all native WunderAutomation types
     *
     * @return void
     */
    public function addWunderAutoObjects()
    {
        // Add our native triggers, filters etc
        $options = get_option('wunderauto-general');

        wa_add_settings($this->getClasses('settings'));

        // Core
        wa_add_trigger($this->getClasses('core-triggers'));
        wa_add_filter($this->getClasses('core-filters'));
        wa_add_action($this->getClasses('core-actions'));
        wa_add_parameter($this->getClasses('core-parameters'));

        // Webhook
        if (isset($options['enable_webhook_trigger']) && $options['enable_webhook_trigger']) {
            wa_add_trigger(['\\WunderAuto\\Types\Triggers\\Webhook\\Webhook']);
            wa_add_filter(['\\WunderAuto\\Types\\Filters\\WebhookParameter']);
            wa_add_parameter(['\\WunderAuto\\Types\\Parameters\\Webhook\\Data']);
        }

        // Woocommerce
        if (class_exists('WooCommerce')) {
            wa_add_trigger($this->getClasses('woocommerce-triggers'));
            wa_add_filter($this->getClasses('woocommerce-filters'));
            wa_add_action($this->getClasses('woocommerce-actions'));
            wa_add_parameter($this->getClasses('woocommerce-parameters'));

            // Both WooCommerce and ACF
            if (class_exists('ACF')) {
                wa_add_filter($this->getClasses('woocommerce-acf-filters'));
                wa_add_parameter($this->getClasses('woocommerce-acf-parameters'));
            }
        }

        // Advanced custom fields
        if (class_exists('ACF')) {
            wa_add_filter($this->getClasses('acf-filters'));
            wa_add_parameter($this->getClasses('acf-parameters'));
        }

        // WooCommerce PDF Invoices and Packing Slips
        if (class_exists('WPO_WCPDF')) {
            wa_add_filter($this->getClasses('wcpdf-filters'));
            wa_add_parameter($this->getClasses('wcpdf-parameters'));
        }

        // Native but Pro
        wa_add_parameter($this->getClasses('pro-parameters'));
    }

    /**
     * Default class arrays for native WunderAutomation triggers, actions, filters and parameters
     *
     * @param string $set
     *
     * @return array<int, string>
     */
    protected function getClasses($set)
    {
        switch ($set) {
            case 'settings':
                return [
                    '\\WunderAuto\\Settings\\GeneralSettings',
                    '\\WunderAuto\\Settings\\Tools',
                    '\\WunderAuto\\Settings\\Support',
                ];
            case 'core-triggers':
                return [
                    '\\WunderAuto\\Types\Triggers\\Post\\Created',
                    '\\WunderAuto\\Types\Triggers\\Post\\Pending',
                    '\\WunderAuto\\Types\Triggers\\Post\\Published',
                    '\\WunderAuto\\Types\Triggers\\Post\\Privatized',
                    '\\WunderAuto\\Types\Triggers\\Post\\Trashed',
                    '\\WunderAuto\\Types\Triggers\\Post\\StatusChanged',
                    '\\WunderAuto\\Types\Triggers\\Post\\Saved',

                    '\\WunderAuto\\Types\Triggers\\User\\Created',
                    '\\WunderAuto\\Types\Triggers\\User\\Login',
                    '\\WunderAuto\\Types\Triggers\\User\\ProfileUpdated',
                    '\\WunderAuto\\Types\Triggers\\User\\RoleChanged',

                    '\\WunderAuto\\Types\Triggers\\Comment\\Submitted',
                    '\\WunderAuto\\Types\Triggers\\Comment\\Approved',
                    '\\WunderAuto\\Types\Triggers\\Comment\\StatusChanged',

                    '\\WunderAuto\\Types\Triggers\\ConfirmationLink\\Post',
                    '\\WunderAuto\\Types\Triggers\\ConfirmationLink\\Order',
                ];
            case 'core-filters':
                return [
                    '\\WunderAuto\\Types\\Filters\\Post\\Title',
                    '\\WunderAuto\\Types\\Filters\\Post\\Content',
                    '\\WunderAuto\\Types\\Filters\\Post\\Type',
                    '\\WunderAuto\\Types\\Filters\\Post\\Status',
                    '\\WunderAuto\\Types\\Filters\\Post\\CreationDate',
                    '\\WunderAuto\\Types\\Filters\\Post\\ModifiedDate',
                    '\\WunderAuto\\Types\\Filters\\Post\\Owner',
                    '\\WunderAuto\\Types\\Filters\\Post\\Tags',
                    '\\WunderAuto\\Types\\Filters\\Post\\Categories',
                    '\\WunderAuto\\Types\\Filters\\Post\\CustomField',

                    '\\WunderAuto\\Types\\Filters\\User\\Email',
                    '\\WunderAuto\\Types\\Filters\\User\\Role',
                    '\\WunderAuto\\Types\\Filters\\User\\CreationDate',
                    '\\WunderAuto\\Types\\Filters\\User\\LastLogin',
                    '\\WunderAuto\\Types\\Filters\\User\\LoginCount',
                    '\\WunderAuto\\Types\\Filters\\User\\CustomField',

                    '\\WunderAuto\\Types\\Filters\\Comment\\Type',
                    '\\WunderAuto\\Types\\Filters\\Comment\\Status',
                    '\\WunderAuto\\Types\\Filters\\Comment\\CreationDate',
                    '\\WunderAuto\\Types\\Filters\\Comment\\AuthorName',
                    '\\WunderAuto\\Types\\Filters\\Comment\\AuthorEmail',
                    '\\WunderAuto\\Types\\Filters\\Comment\\Content',
                    '\\WunderAuto\\Types\\Filters\\Comment\\CustomField',

                    '\\WunderAuto\\Types\\Filters\\ConfirmationLink\\Name',
                    '\\WunderAuto\\Types\\Filters\\ConfirmationLink\\Clicks',

                    '\\WunderAuto\\Types\\Filters\\Initiator',
                    '\\WunderAuto\\Types\\Filters\\IP',
                    '\\WunderAuto\\Types\\Filters\\RefererUrl',
                    '\\WunderAuto\\Types\\Filters\\RefererPost',
                    '\\WunderAuto\\Types\\Filters\\Date',
                    '\\WunderAuto\\Types\\Filters\\DateBetween',
                    '\\WunderAuto\\Types\\Filters\\Weekday',
                    '\\WunderAuto\\Types\\Filters\\TimeOfDay',
                    '\\WunderAuto\\Types\\Filters\\Option',
                ];
            case 'core-actions':
                return [
                    '\\WunderAuto\\Types\\Actions\\Email',
                    '\\WunderAuto\\Types\\Actions\\HTMLEmail',
                    '\\WunderAuto\\Types\\Actions\\ChangeRole',
                    '\\WunderAuto\\Types\\Actions\\RestApiCall',
                    '\\WunderAuto\\Types\\Actions\\Webhook',
                    '\\WunderAuto\\Types\\Actions\\CreatePost',
                    '\\WunderAuto\\Types\\Actions\\CreateUser',
                    '\\WunderAuto\\Types\\Actions\\ChangeStatus',
                    '\\WunderAuto\\Types\\Actions\\ChangeCustomField',
                    '\\WunderAuto\\Types\\Actions\\TaxonomyTerm',
                    '\\WunderAuto\\Types\\Actions\\TaxonomyTerm',
                    '\\WunderAuto\\Types\\Actions\\CancelDelayedWorkflows',
                    '\\WunderAuto\\Types\\Actions\\AddObjects',
                    '\\WunderAuto\\Types\\Actions\\ErrorLog',
                    '\\WunderAuto\\Types\\Actions\\Log',
                    //'\\WunderAuto\\Types\\Actions\\RunWorkflow',
                ];
            case 'core-parameters':
                return [
                    '\\WunderAuto\\Types\\Parameters\\Post\\Id',
                    '\\WunderAuto\\Types\\Parameters\\Post\\Title',
                    '\\WunderAuto\\Types\\Parameters\\Post\\Content',
                    '\\WunderAuto\\Types\\Parameters\\Post\\Status',
                    '\\WunderAuto\\Types\\Parameters\\Post\\Slug',
                    '\\WunderAuto\\Types\\Parameters\\Post\\Type',
                    '\\WunderAuto\\Types\\Parameters\\Post\\CommentCount',
                    '\\WunderAuto\\Types\\Parameters\\Post\\Date',
                    '\\WunderAuto\\Types\\Parameters\\Post\\Modified',
                    '\\WunderAuto\\Types\\Parameters\\Post\\Url',
                    '\\WunderAuto\\Types\\Parameters\\Post\\AdminUrl',
                    '\\WunderAuto\\Types\\Parameters\\Post\\CustomField',
                    '\\WunderAuto\\Types\\Parameters\\Post\\Term',

                    '\\WunderAuto\\Types\\Parameters\\User\\Id',
                    '\\WunderAuto\\Types\\Parameters\\User\\Email',
                    '\\WunderAuto\\Types\\Parameters\\User\\Role',
                    '\\WunderAuto\\Types\\Parameters\\User\\Login',
                    '\\WunderAuto\\Types\\Parameters\\User\\FirstName',
                    '\\WunderAuto\\Types\\Parameters\\User\\LastName',
                    '\\WunderAuto\\Types\\Parameters\\User\\NickName',
                    '\\WunderAuto\\Types\\Parameters\\User\\Date',
                    '\\WunderAuto\\Types\\Parameters\\User\\LastLogin',
                    '\\WunderAuto\\Types\\Parameters\\User\\LoginCount',
                    '\\WunderAuto\\Types\\Parameters\\User\\NickName',
                    '\\WunderAuto\\Types\\Parameters\\User\\CustomField',

                    '\\WunderAuto\\Types\\Parameters\\Comment\\Id',
                    '\\WunderAuto\\Types\\Parameters\\Comment\\AuthorName',
                    '\\WunderAuto\\Types\\Parameters\\Comment\\AuthorEmail',
                    '\\WunderAuto\\Types\\Parameters\\Comment\\Content',
                    '\\WunderAuto\\Types\\Parameters\\Comment\\Status',
                    '\\WunderAuto\\Types\\Parameters\\Comment\\Type',
                    '\\WunderAuto\\Types\\Parameters\\Comment\\CustomField',

                    '\\WunderAuto\\Types\\Parameters\\SiteName',
                    '\\WunderAuto\\Types\\Parameters\\SiteUrl',
                    '\\WunderAuto\\Types\\Parameters\\IP',
                    '\\WunderAuto\\Types\\Parameters\\RefererUrl',
                    '\\WunderAuto\\Types\\Parameters\\RefererPost',
                    '\\WunderAuto\\Types\\Parameters\\DateTime',
                    '\\WunderAuto\\Types\\Parameters\\Option',
                    '\\WunderAuto\\Types\\Parameters\\ConfirmationLink',
                ];
            case 'woocommerce-triggers':
                return [
                    '\\WunderAuto\\Types\Triggers\\Order\\Created',
                    '\\WunderAuto\\Types\Triggers\\Order\\OnHold',
                    '\\WunderAuto\\Types\Triggers\\Order\\Pending',
                    '\\WunderAuto\\Types\Triggers\\Order\\Processing',
                    '\\WunderAuto\\Types\Triggers\\Order\\Paid',
                    '\\WunderAuto\\Types\Triggers\\Order\\Completed',
                    '\\WunderAuto\\Types\Triggers\\Order\\Cancelled',
                    '\\WunderAuto\\Types\Triggers\\Order\\Failed',
                    '\\WunderAuto\\Types\Triggers\\Order\\Refunded',
                    '\\WunderAuto\\Types\Triggers\\Order\\Saved',

                    '\\WunderAuto\\Types\Triggers\\Comment\\OrderNoteSubmitted',
                ];
            case 'woocommerce-filters':
                return [
                    '\\WunderAuto\\Types\\Filters\\Order\\Number',
                    '\\WunderAuto\\Types\\Filters\\Order\\Status',
                    '\\WunderAuto\\Types\\Filters\\Order\\CreationDate',
                    '\\WunderAuto\\Types\\Filters\\Order\\PaidDate',
                    '\\WunderAuto\\Types\\Filters\\Order\\CompletedDate',
                    '\\WunderAuto\\Types\\Filters\\Order\\Products',
                    '\\WunderAuto\\Types\\Filters\\Order\\ProductTypes',
                    '\\WunderAuto\\Types\\Filters\\Order\\ProductTags',
                    '\\WunderAuto\\Types\\Filters\\Order\\ProductCategories',
                    '\\WunderAuto\\Types\\Filters\\Order\\VirtualProduct',
                    '\\WunderAuto\\Types\\Filters\\Order\\DownloadableProduct',
                    '\\WunderAuto\\Types\\Filters\\Order\\Total',
                    '\\WunderAuto\\Types\\Filters\\Order\\ShippingMethod',
                    '\\WunderAuto\\Types\\Filters\\Order\\ShippingZone',
                    '\\WunderAuto\\Types\\Filters\\Order\\CreatedVia',
                    '\\WunderAuto\\Types\\Filters\\Order\\IsGuest',
                    '\\WunderAuto\\Types\\Filters\\Order\\CustomField',
                    '\\WunderAuto\\Types\\Filters\\Order\\AdvancedCustomField',
                    '\\WunderAuto\\Types\\Filters\\Order\\CustomerNote',

                    '\\WunderAuto\\Types\\Filters\\Order\\BillingEmail',
                    '\\WunderAuto\\Types\\Filters\\Order\\BillingCountry',
                    '\\WunderAuto\\Types\\Filters\\Order\\BillingState',
                    '\\WunderAuto\\Types\\Filters\\Order\\BillingCity',
                    '\\WunderAuto\\Types\\Filters\\Order\\BillingCompany',
                    '\\WunderAuto\\Types\\Filters\\Order\\PaymentMethod',
                    '\\WunderAuto\\Types\\Filters\\Order\\ShippingCountry',
                    '\\WunderAuto\\Types\\Filters\\Order\\ShippingState',
                    '\\WunderAuto\\Types\\Filters\\Order\\ShippingCity',
                    '\\WunderAuto\\Types\\Filters\\Order\\ShippingCompany',
                    '\\WunderAuto\\Types\\Filters\\Order\\ShippingCompany',

                    '\\WunderAuto\\Types\\Filters\\Customer\\TotalOrderCount',
                    '\\WunderAuto\\Types\\Filters\\Customer\\CompletedOrderCount',
                    '\\WunderAuto\\Types\\Filters\\Customer\\PaidOrderCount',
                    '\\WunderAuto\\Types\\Filters\\Customer\\CompletedOrderTotal',
                    '\\WunderAuto\\Types\\Filters\\Customer\\IsLastOrder',

                    '\\WunderAuto\\Types\\Filters\\Coupon\\Name',

                    '\\WunderAuto\\Types\\Filters\\Comment\\IsCustomerNote',
                    '\\WunderAuto\\Types\\Filters\\Comment\\IsOrderStatusNote',
                    '\\WunderAuto\\Types\\Filters\\Comment\\IsWooCommerceSystemNote',
                ];
            case 'woocommerce-actions':
                return [
                    '\\WunderAuto\\Types\\Actions\\OrderNote',
                    '\\WunderAuto\\Types\\Actions\\WooCommerceEmail',
                ];
            case 'woocommerce-parameters':
                return [
                    '\\WunderAuto\\Types\\Parameters\\Order\\Id',
                    '\\WunderAuto\\Types\\Parameters\\Order\\Number',
                    '\\WunderAuto\\Types\\Parameters\\Order\\Status',
                    '\\WunderAuto\\Types\\Parameters\\Order\\Email',
                    '\\WunderAuto\\Types\\Parameters\\Order\\CustomerNote',
                    '\\WunderAuto\\Types\\Parameters\\Order\\SubTotal',
                    '\\WunderAuto\\Types\\Parameters\\Order\\CartTotal',
                    '\\WunderAuto\\Types\\Parameters\\Order\\CartTax',
                    '\\WunderAuto\\Types\\Parameters\\Order\\DiscountTotal',
                    '\\WunderAuto\\Types\\Parameters\\Order\\DiscountTax',
                    '\\WunderAuto\\Types\\Parameters\\Order\\ShippingTotal',
                    '\\WunderAuto\\Types\\Parameters\\Order\\ShippingTax',
                    '\\WunderAuto\\Types\\Parameters\\Order\\Total',
                    '\\WunderAuto\\Types\\Parameters\\Order\\TotalTax',
                    '\\WunderAuto\\Types\\Parameters\\Order\\TotalExclTax',
                    '\\WunderAuto\\Types\\Parameters\\Order\\Fees',
                    '\\WunderAuto\\Types\\Parameters\\Order\\FeesTax',
                    '\\WunderAuto\\Types\\Parameters\\Order\\PaymentMethod',
                    '\\WunderAuto\\Types\\Parameters\\Order\\OrderItemsTotal',
                    '\\WunderAuto\\Types\\Parameters\\Order\\OrderItemsTax',
                    '\\WunderAuto\\Types\\Parameters\\Order\\StripeFee',
                    '\\WunderAuto\\Types\\Parameters\\Order\\PaypalFee',
                    '\\WunderAuto\\Types\\Parameters\\Order\\Date',
                    '\\WunderAuto\\Types\\Parameters\\Order\\PaidDate',
                    '\\WunderAuto\\Types\\Parameters\\Order\\CompletedDate',
                    '\\WunderAuto\\Types\\Parameters\\Order\\ShippingZoneName',
                    '\\WunderAuto\\Types\\Parameters\\Order\\ShippingMethodName',
                    '\\WunderAuto\\Types\\Parameters\\Order\\CreatedVia',
                    '\\WunderAuto\\Types\\Parameters\\Order\\OrderKey',
                    '\\WunderAuto\\Types\\Parameters\\Order\\Details',
                    '\\WunderAuto\\Types\\Parameters\\Order\\Meta',
                    '\\WunderAuto\\Types\\Parameters\\Order\\CustomerDetails',
                    '\\WunderAuto\\Types\\Parameters\\Order\\CustomField',

                    '\\WunderAuto\\Types\\Parameters\\Billing\\Email',
                    '\\WunderAuto\\Types\\Parameters\\Billing\\Phone',
                    '\\WunderAuto\\Types\\Parameters\\Billing\\FirstName',
                    '\\WunderAuto\\Types\\Parameters\\Billing\\LastName',
                    '\\WunderAuto\\Types\\Parameters\\Billing\\FullName',
                    '\\WunderAuto\\Types\\Parameters\\Billing\\Company',
                    '\\WunderAuto\\Types\\Parameters\\Billing\\Address1',
                    '\\WunderAuto\\Types\\Parameters\\Billing\\Address2',
                    '\\WunderAuto\\Types\\Parameters\\Billing\\City',
                    '\\WunderAuto\\Types\\Parameters\\Billing\\Postcode',
                    '\\WunderAuto\\Types\\Parameters\\Billing\\Country',
                    '\\WunderAuto\\Types\\Parameters\\Billing\\State',
                    '\\WunderAuto\\Types\\Parameters\\Shipping\\Phone',
                    '\\WunderAuto\\Types\\Parameters\\Shipping\\FirstName',
                    '\\WunderAuto\\Types\\Parameters\\Shipping\\LastName',
                    '\\WunderAuto\\Types\\Parameters\\Shipping\\FullName',
                    '\\WunderAuto\\Types\\Parameters\\Shipping\\Company',
                    '\\WunderAuto\\Types\\Parameters\\Shipping\\Address1',
                    '\\WunderAuto\\Types\\Parameters\\Shipping\\Address2',
                    '\\WunderAuto\\Types\\Parameters\\Shipping\\City',
                    '\\WunderAuto\\Types\\Parameters\\Shipping\\Postcode',
                    '\\WunderAuto\\Types\\Parameters\\Shipping\\Country',
                    '\\WunderAuto\\Types\\Parameters\\Shipping\\State',

                    '\\WunderAuto\\Types\\Parameters\\Customer\\TotalOrderCount',
                    '\\WunderAuto\\Types\\Parameters\\Customer\\CompletedOrderCount',
                    '\\WunderAuto\\Types\\Parameters\\Customer\\PaidOrderCount',
                    '\\WunderAuto\\Types\\Parameters\\Customer\\CompletedOrderTotal',

                    // Order related User parameters
                    //'\\WunderAuto\\Types\\Parameters\\User\\Order\\Order',

                    '\\WunderAuto\\Types\\Parameters\\GenerateCoupon',
                ];
            case 'woocommerce-actions':
                return [
                    '\\WunderAuto\\Types\\Actions\\OrderNote',
                    '\\WunderAuto\\Types\\Actions\\WooCommerceEmail',
                ];
            case 'woocoomerce-acf-filters':
                return [
                    '\\WunderAuto\\Types\\Filters\\Order\\AdvancedCustomField',
                ];
            case 'woocommerce-acf-parameters':
                return [
                    '\\WunderAuto\\Types\\Parameters\\Order\\AdvancedCustomField',
                ];
            case 'acf-filters':
                return [
                    '\\WunderAuto\\Types\\Filters\\Post\\AdvancedCustomField',
                    '\\WunderAuto\\Types\\Filters\\User\\AdvancedCustomField',
                    '\\WunderAuto\\Types\\Filters\\Comment\\AdvancedCustomField',
                ];
            case 'acf-parameters':
                return [
                    '\\WunderAuto\\Types\\Parameters\\Post\\AdvancedCustomField',
                    '\\WunderAuto\\Types\\Parameters\\User\\AdvancedCustomField',
                    '\\WunderAuto\\Types\\Parameters\\Comment\\AdvancedCustomField',
                ];
            case 'wcpdf-filters':
                return [
                        '\\WunderAuto\\Types\\Filters\\Order\\WCPDFInvoiceNumber',
                        '\\WunderAuto\\Types\\Filters\\Order\\WCPDFInvoiceDate',
                        '\\WunderAuto\\Types\\Filters\\Order\\WCPDFInvoiceNotes',
                    ];
            case 'wcpdf-parameters':
                return [
                    '\\WunderAuto\\Types\\Parameters\\Order\\WCPDFInvoiceNumber',
                    '\\WunderAuto\\Types\\Parameters\\Order\\WCPDFInvoiceDate',
                    '\\WunderAuto\\Types\\Parameters\\Order\\WCPDFInvoiceNotes',
                ];
            case 'pro-parameters':
                return [
                    '\\WunderAuto\\Types\\Parameters\\Order\\ReorderUrl',
                ];
        }

        return [];
    }
}
