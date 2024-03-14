<?php
/**
 * @noinspection PhpMissingParentCallCommonInspection  many parent methods are
 *   no-ops or call {@see Source::callTypeSpecificMethod()}.
 * @noinspection PhpMultipleClassDeclarationsInspection OC3 has many double class definitions
 * @noinspection PhpUndefinedClassInspection Mix of OC4 and OC3 classes
 * @noinspection PhpUndefinedNamespaceInspection Mix of OC4 and OC3 classes
 */

declare(strict_types=1);

namespace Siel\Acumulus\OpenCart\Invoice;

use Siel\Acumulus\Api;
use Siel\Acumulus\Invoice\Currency;
use Siel\Acumulus\Invoice\Source as BaseSource;
use Siel\Acumulus\Invoice\Totals;
use Siel\Acumulus\Meta;
use Siel\Acumulus\OpenCart\Helpers\Registry;

use function in_array;
use function strlen;

/**
 * Wraps an OpenCart order in an invoice source object.
 *
 * @property array $shopSource
 */
abstract class Source extends BaseSource
{
    /**
     * @var array[]
     *   List of OpenCart order total records.
     */
    protected array $orderTotalLines;

    protected function setSource(): void
    {
        // @error: throw exception when not found.
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->shopSource = $this->getRegistry()->getOrder($this->getId());
    }

    /**
     * Sets the id based on the loaded Order.
     */
    protected function setId(): void
    {
        $this->id = $this->shopSource['order_id'];
    }

    public function getDate(): string
    {
        return substr($this->shopSource['date_added'], 0, strlen('2000-01-01'));
    }

    /**
     * {@inheritdoc}
     *
     * @return int
     */
    public function getStatus(): int
    {
        return (int) $this->shopSource['order_status_id'];
    }

    public function getCountryCode(): string
    {
        if (!empty($this->shopSource['payment_iso_code_2'])) {
            return $this->shopSource['payment_iso_code_2'];
        } elseif (!empty($this->shopSource['shipping_iso_code_2'])) {
            return $this->shopSource['shipping_iso_code_2'];
        } else {
            return '';
        }
    }

    /**
     * {@inheritdoc}
     *
     * This override returns the code of the selected payment method.
     */
    public function getPaymentMethod(): ?string
    {
        return $this->shopSource['payment_code'] ?? parent::getPaymentMethod();
    }

    public function getPaymentStatus(): int
    {
        // The 'config_complete_status' setting contains a set of statuses that,
        //  according to the help on the settings form:
        // "The order status the customer's order must reach before they are
        //  allowed to access their downloadable products and gift vouchers."
        // This seems like the set of statuses where payment has been
        // completed...
        $orderStatuses = (array) $this->getRegistry()->config->get('config_complete_status');

        return (empty($orderStatuses) || in_array($this->shopSource['order_status_id'], $orderStatuses, true))
            ? Api::PaymentStatus_Paid
            : Api::PaymentStatus_Due;
    }

    public function getPaymentDate(): ?string
    {
        // @todo Can we determine this based on history (and optionally
        //   payment_code)?
        // Will default to the issue date.
        return null;
    }

    /**
     * {@inheritdoc}
     *
     * In OpenCart the amounts are in the shop's default currency, even if
     * another currency was presented to the customer, so we will not have to
     * convert the amounts and this meta info is thus purely informative.
     */
    public function getCurrency(): Currency
    {
        return new Currency($this->shopSource['currency_code'], (float) $this->shopSource['currency_value']);
    }

    /**
     * {@inheritdoc}
     *
     * This override provides the values meta-invoice-amountinc,
     * meta-invoice-vatamount and a vat breakdown in meta-invoice-vat.
     */
    public function getTotals(): Totals
    {
        $vatAmount = 0.0;
        $vatBreakdown = [];
        $orderTotals = $this->getOrderTotalLines();
        foreach ($orderTotals as $totalLine) {
            if ($totalLine['code'] === 'tax') {
                $vatAmount += $totalLine['value'];
                $vatBreakdown[$totalLine['title']] = $totalLine['value'];
            }
        }
        return new Totals((float) $this->shopSource['total'], $vatAmount, null, $vatBreakdown);
    }

    /**
     * Returns a list of OpenCart order total records.
     *
     * These are shipment, other fee, tax, and discount lines.
     *
     * @return array[]
     *   The set of order total lines for this order. This set is ordered by
     *   sort_order, meaning that lines before the tax line are amounts ex vat
     *   and lines after are inc vat.
     */
    abstract public function getOrderTotalLines(): array;

    public function getInvoiceReference()
    {
        $result = null;
        if (!empty($this->shopSource['invoice_no'])) {
            $result = $this->shopSource['invoice_prefix'] . $this->shopSource['invoice_no'];
        }
        return $result;
    }

    /**
     * @return \Opencart\Catalog\Model\Checkout\Order|\Opencart\Admin\Model\Sale\Order|\ModelCheckoutOrder|\ModelSaleOrder
     */
    protected function getOrderModel()
    {
        return $this->getRegistry()->getOrderModel();
    }

    /**
     * Wrapper method that returns the OpenCart registry class.
     */
    protected function getRegistry(): Registry
    {
        return Registry::getInstance();
    }
}
