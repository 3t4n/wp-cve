<?php

declare(strict_types=1);

namespace Siel\Acumulus\Config;

use InvalidArgumentException;
use RuntimeException;
use Siel\Acumulus\Helpers\Translator;
use Siel\Acumulus\Invoice\Source;

use function array_key_exists;
use function in_array;

/**
 * Defines an interface to access the shop specific's capabilities.
 */
abstract class ShopCapabilities
{
    protected Translator $translator;
    protected string $shopName;

    public function __construct(string $shopNamespace, Translator $translator)
    {
        $this->translator = $translator;
        $pos = strrpos($shopNamespace, '\\');
        $this->shopName = $pos !== false ? substr($shopNamespace, $pos + 1) : $shopNamespace;
    }

    /**
     * Helper method to translate strings.
     *
     * @param string $key
     *  The key to get a translation for.
     *
     * @return string
     *   The translation for the given key or the key itself if no translation
     *   could be found.
     */
    protected function t(string $key): string
    {
        return $this->translator->get($key);
    }

    /**
     * Returns an array with shop specific configuration defaults.
     *
     * Any key defined in {@see getKeyInfo()} that can be
     * given a more logical value given that the library is running in a given
     * web shop software, should be returned here.
     *
     * This base method is abstract, because at least these keys that allow
     * tokens (veldverwijzingen) to get customer, invoice and invoice line fields
     * should be returned.
     * - See {@see \Siel\Acumulus\Invoice\Creator::getCustomer()} to get a list
     *   of fields at the customer level that use tokens.
     * - See {@see \Siel\Acumulus\Invoice\Creator::getInvoice()} to get a list
     *   of fields at the invoice level that use tokens.
     * - At the item line level, the fields 'itemnumber', 'product', 'nature',
     *   and 'costprice' may use tokens.
     *
     * See{@see \Siel\Acumulus\Helpers\Token} or the help text under key
     * 'desc_tokens' in siel/acumulus/src/Shop/ConfigFormTranslations.php for
     * more info about the possible options to define combinations or a
     * selection of various tokens.
     */
    abstract public function getDefaultShopConfig(): array;

    /**
     * Returns an array with shop specific property mapping defaults.
     *
     * This base method is abstract, because mappings are almost per definition
     * shop dependent.
     * See the {@see \Siel\Acumulus\Data\_Documentation Data namespace} to get
     * all the possible {@see \Siel\Acumulus\Data\AcumulusObject} types and
     * their {@see \Siel\Acumulus\Data\AcumulusProperty} properties.
     *
     * @return string[][]
     *   A keyed array of keyed arrays. At the 1st level the keys are the
     *   Data\...Type::... constants and the values are the mappings for that
     *   object type. A mapping being a keyed array, with as keys the property
     *   names and as values a so-called field expansion specification, see
     *   {@see \Siel\Acumulus\Helpers\FieldExpander}.
     *
     * @todo: make abstract when implemented for all shops.
     */
    public function getDefaultShopMappings(): array
    {
        return [];
    }

    /**
     * Returns a list with the shop specific token info.
     *
     * Many fields of an Acumulus invoice can be filled with user configured and
     * dynamically looked up values of properties or method return values from
     * web shop objects that are made available when creating the invoice. The
     * advanced settings form gives the user an overview of what objects,
     * properties and methods are available. This overview is based on the info
     * that this method returns.
     *
     * This base implementation returns the info that is available in all web
     * shops. Overriding methods should add the web shop specific info, which
     * must at least include the "property source" 'source', being the web
     * shop's order or refund object or array.
     *
     * This method returns an array of token infos keyed by the "property
     * source" name. A token info is an array that can have the following keys:
     * - more-info (string, optional): free text to tell the use where to look
     *   for more info.
     * - class (string|string[], optional): class or array of class names where
     *   the properties come from.
     * - file (string|string[], optional): file or array of file names where
     *   the properties come from.
     * - table (string|string[], optional): database table or array of table
     *   names where the properties come from.
     * - additional-info (string, optional): free text to give the user
     *   additional info.
     * - properties (string[], required): array of property and method names
     *   that can be used as token.
     * - properties-more: bool indicating if not all properties were listed and
     *   a message indicating where to look for more properties should be shown.
     *
     * It is expected that 1 of the keys 'class', 'file', or 'table' is defined.
     * If 'class' is defined, 'file' may also be defined.
     *
     * @return array[]
     *   A multi-level array of token infos keyed by the "property source" name.
     *
     * @legacy: old way of showing field references.
     */
    public function getTokenInfo(): array
    {
        $result = [];
        $result['invoiceSource'] = [
            'more-info' => ucfirst($this->t('invoice_source')),
            'class' => Source::class,
            'properties' => [
                'type (' . $this->t(Source::Order) . ' ' . $this->t('or') . ' ' . $this->t(Source::CreditNote) . ', ' .
                    $this->t('internal_not_label') . ')',
                'id (' . $this->t('internal_id') . ')',
                'reference (' . $this->t('external_id') . ')',
                'date',
                'status (' . $this->t('internal_not_label') . ')',
                'paymentMethod (' . $this->t('internal_not_label') . ')',
                'paymentStatus (1: ' . $this->t('payment_status_1') . '; 2: ' . $this->t('payment_status_2') . ')',
                'paymentDate',
                'countryCode',
                'currency',
                'invoiceReference (' . $this->t('external_id') . ')',
                'invoiceDate',
            ],
            'properties-more' => false,
        ];
        $result['invoiceSourceType'] = [
            'properties' => [
                'label (' . sprintf($this->t('label'), Source::Order, Source::CreditNote) . ')',
            ],
            'properties-more' => false,
        ];
        if (array_key_exists(Source::CreditNote, $this->getSupportedInvoiceSourceTypes())) {
            $result['originalInvoiceSource'] = [
                'more-info' => ucfirst($this->t('original_invoice_source')),
                'properties' => [$this->t('see_invoice_source_above')],
                'properties-more' => false,
            ];
        }
        $result['originalInvoiceSourceType'] = [
            'properties' => [
                'label (' . sprintf($this->t('label'), Source::Order, Source::CreditNote) . ')',
            ],
            'properties-more' => false,
        ];
        $result['source'] = array_merge(['more-info' => ucfirst($this->t('order_or_refund'))], $this->getTokenInfoSource());
        if (array_key_exists(Source::CreditNote, $this->getSupportedInvoiceSourceTypes())) {
            $result['refund'] = array_merge(['more-info' => ucfirst($this->t('refund_only'))], $this->getTokenInfoRefund());
            $result['order'] = array_merge(['more-info' => ucfirst($this->t('original_order_for_refund'))], $this->getTokenInfoOrder());
            $result['refundedInvoiceSource'] = [
                'more-info' => ucfirst($this->t('original_invoice_source') . ' ' . ucfirst($this->t('refund_only'))),
                'properties' => [$this->t('see_invoice_source_above')],
                'properties-more' => false,
            ];
            $result['refundedInvoiceSourceType'] = [
                'properties' => [
                    'label (' . sprintf($this->t('label'), Source::Order, Source::CreditNote) . ')',
                ],
                'properties-more' => false,
            ];
            $result['refundedOrder'] = [
                'more-info' => ucfirst($this->t('original_order_for_refund') . ' ' . ucfirst($this->t('refund_only'))),
                'properties' => [$this->t('see_order_above')],
                'properties-more' => false,
            ];
        }
        $result += $this->getTokenInfoShopProperties();

        return $result;
    }

    /**
     * Returns shop specific token info for the 'source' property.
     *
     * @legacy: old way of showing field references.
     */
    abstract protected function getTokenInfoSource(): array;

    /**
     * Returns shop specific token info for the 'refund' property.
     *
     * Override if your shop supports refunds.
     *
     * @legacy: old way of showing field references.
     */
    protected function getTokenInfoRefund(): array
    {
        return [];
    }

    /**
     * Returns shop specific token info for the 'refundedOrder' property.
     *
     * Override if your shop supports refunds.
     *
     * @legacy: old way of showing field references.
     */
    protected function getTokenInfoOrder(): array
    {
        return [];
    }

    /**
     * Returns token info for any additional properties.
     *
     * Think of properties like:
     * - Billing or sending address
     * - Customer
     * - Order item or line
     * - Product
     *
     * @legacy: old way of showing field references.
     */
    abstract protected function getTokenInfoShopProperties(): array;

    /**
     * Returns an option list of all shop order statuses.
     *
     * Note that the IDs are the values that are stored in the config and are
     * later on compared with the order status when a web shop event occurs
     * that may lead to sending the invoice to Acumulus.
     *
     * @return string[]
     *   An array of all shop order statuses, with the key being the ID for
     *   the dropdown item and the value being the label for the dropdown item.
     */
    abstract public function getShopOrderStatuses(): array;

    /**
     * Returns a list of invoice source types supported by this shop.
     *
     * The default implementation returns order and credit note. Override if the
     * specific shop does not support credit notes (or supports other types).
     *
     * @return string[]
     *   The list of supported invoice source types. The keys are the internal
     *   {@see \Siel\Acumulus\Invoice\Source} constants, the values are
     *   translated labels.
     */
    public function getSupportedInvoiceSourceTypes(): array
    {
        return [
            Source::Order => ucfirst($this->t(Source::Order)),
            Source::CreditNote => ucfirst($this->t(Source::CreditNote)),
        ];
    }

    /**
     * Returns an option list of all shop invoice related events.
     *
     * This list represents the shop initiated events that may trigger the
     * sending of the invoice to Acumulus.
     *
     * @return string[]
     *   An array of all shop invoice related events, with the key being the ID
     *   for the dropdown item, 1 of the
     *  {@see \Siel\Acumulus\Config}::TriggerInvoiceEvent_... constants,
     *  and the value being the label for the dropdown item.
     */
    public function getTriggerInvoiceEventOptions(): array
    {
        return [
            Config::TriggerInvoiceEvent_None => $this->t('option_triggerInvoiceEvent_0'),
        ];
    }

    /**
     * Returns an option list of credit note related events.
     *
     * This list represents the shop initiated events that may trigger the
     * sending of a credit invoice to Acumulus.
     *
     * This default implementation returns
     * - PluginConfig::TriggerCreditNoteEvent_None for all shops, and as only
     *   value for shops that do not support credit notes
     * - PluginConfig::TriggerCreditNoteEvent_Create for shops that do support
     *   credit notes (based on {@see getSupportedInvoiceSourceTypes()}).
     *
     * @return string[]
     *   An array of all credit note related events, with the key being the ID
     *   for the dropdown item, 1 of the {@see \Siel\Acumulus\Config}
     *   TriggerCreditNoteEvent_... constants, and the value being the label for
     *   the dropdown item.
     *
     * @noinspection PhpUnused Called via method name construction in
     *   BaseConfigForm::getOptionsOrHiddenField().
     */
    public function getTriggerCreditNoteEventOptions(): array
    {
        $result = [
            Config::TriggerCreditNoteEvent_None => $this->t('option_triggerCreditNoteEvent_0'),
        ];

        if (in_array(Source::CreditNote, $this->getSupportedInvoiceSourceTypes())) {
            $result[Config::TriggerCreditNoteEvent_Create] = $this->t('option_triggerCreditNoteEvent_1');
        }

        return $result;
    }

    /**
     * Returns a list of valid sources that can be used as invoice number.
     *
     * This may differ per shop as not all shops support invoices as a separate
     * entity.
     *
     * Overrides should typically return a subset of the constants defined in
     * this base implementation, but including at least
     * {@see Config::InvoiceNrSource_Acumulus}.
     *
     * @return string[]
     *   An array keyed by the option values and having translated descriptions
     *   as values.
     */
    public function getInvoiceNrSourceOptions(): array
    {
        return [
            Config::InvoiceNrSource_ShopInvoice => $this->t('option_invoiceNrSource_1'),
            Config::InvoiceNrSource_ShopOrder => $this->t('option_invoiceNrSource_2'),
            Config::InvoiceNrSource_Acumulus => $this->t('option_invoiceNrSource_3'),
        ];
    }

    /**
     * Returns a list of valid date sources that can be used as invoice date.
     *
     * This may differ per shop as not all shops support invoices as a separate
     * entity.
     *
     * Overrides should typically return a subset of the constants defined in
     * this base implementation, but including at least
     * {@see Config::IssueDateSource_Transfer}.
     *
     * @return string[]
     *   An array keyed by the option values and having translated descriptions
     *   as values.
     */
    public function getDateToUseOptions(): array
    {
        return [
            Config::IssueDateSource_InvoiceCreate => $this->t('option_dateToUse_1'),
            Config::IssueDateSource_OrderCreate => $this->t('option_dateToUse_2'),
            Config::IssueDateSource_Transfer => $this->t('option_dateToUse_3'),
        ];
    }

    /**
     * Returns an option list of active payment methods.
     *
     * The ids returned are later on used to compare with an order's payment
     * method, so the appropriate template and account can be chosen.
     *
     * @return string[]
     *   An array of active payment methods, with the key being the id (internal
     *   name) for the dropdown item and the value being the label for the
     *   dropdown item.
     */
    abstract public function getPaymentMethods(): array;

    /**
     * Returns an option list of tax classes.
     *
     * @return string[]
     *   An array of tax classes, with the key being the tax class id, to be
     *   used as id for the dropdown item and the value being the tax class
     *   name, to be used as the label for the dropdown item.
     */
    abstract public function getVatClasses(): array;

    /**
     * Returns a link to a form page or an image.
     *
     * If the web shop adds a session token or something like that to
     * administrative links, the returned link should contain so as well.
     *
     * @param string $linkType
     *   The form or resource to get the link to: 'settings', 'mappings', 'config',
     *   'advanced', 'batch', 'activate', 'register', 'logo', 'pro-support-link',
     *   'pro-support-img'.
     *
     * @throws \InvalidArgumentException
     *   Unknown link type.
     */
    public function getLink(string $linkType): string
    {
        if ($linkType === 'fiscal-address-setting') {
            return '#';
        }
        throw new InvalidArgumentException(__METHOD__ . "('$linkType'): unknown link type");
    }

    /**
     * Returns whether our module for this shop (already) implements the
     * InvoiceStatus screen.
     *
     * At this moment all shops implements this screen, so this method returns
     * true and is not overridden.
     *
     * @return true
     */
    public function hasInvoiceStatusScreen(): bool
    {
        return true;
    }

    /**
     * Returns whether our module for this shop (already) implements the
     * features on the Order list screen.
     */
    public function hasOrderList(): bool
    {
        return false;
    }

    /**
     * Returns the name of the setting or the address type that the shop uses for fiscal
     * calculations.
     *
     * @return string
     *   One of the {@see \Siel\Acumulus\Data\AddressType} constants if the shop does not
     *   allow to choose the address, the name of the setting that determines which
     *   address is used otherwise.
     */
    public function getFiscalAddressSetting(): string
    {
        throw new RuntimeException(__METHOD__ . ' is not implemented');
    }

    /**
     * Returns whether the new code is used.
     *
     * The default return here is that the old code is used, so override for webshops that
     * do use the new code (the override gives an emergency switch back to the old code).
     *
     * @return bool
     *   True if the new {@see \Siel\Acumulus\Data\_Documentation data objects},
     *   its {@see \Siel\Acumulus\Collectors\_Documentation collectors},
     *   {@see \Siel\Acumulus\Config\Mappings},
     *   and {@see \Siel\Acumulus\Completors\_Documentation completors} are used,
     *   false otherwise.
     */
    public function usesNewCode(): bool
    {
        return false;
    }
}
