<?php

declare(strict_types=1);

namespace Siel\Acumulus\WooCommerce\Invoice;

use RuntimeException;
use Siel\Acumulus\Api;
use Siel\Acumulus\Invoice\Source as BaseSource;
use Siel\Acumulus\Invoice\Totals;
use WC_Abstract_Order;
use WC_Order;
use WC_Order_Refund;

use function get_class;
use function gettype;
use function is_object;
use function strlen;

/**
 * Wraps a WooCommerce order in an invoice source object.
 *
 * Since WC 2.2.0 multiple order types can be defined, @see
 * wc_register_order_type() and wc_get_order_types(). WooCommerce itself defines
 * 'shop_order' and 'shop_order_refund'. The base class for all these types of
 * orders is WC_Abstract_Order
 *
 * @method WC_Order|WC_Order_Refund|WC_Abstract_Order getSource()
 */
class Source extends BaseSource
{
    /**
     * Loads an Order or refund source for the set id.
     *
     * @throws  \RuntimeException
     *   If $idOrSource is empty or not a valid source.
     */
    protected function setSource(): void
    {
        $this->shopSource = wc_get_order($this->getId());
        if (!is_object($this->shopSource)) {
            throw new RuntimeException(sprintf('Not a valid source id (%s %d)', $this->type, $this->id));
        }
    }

    /**
     * Sets the id based on the loaded Order or Order refund.
     *
     * @throws \RuntimeException
     *   If $idOrSource is empty or not a valid source.
     */
    protected function setId(): void
    {
        if (!$this->shopSource instanceof WC_Abstract_Order) {
            // @todo: PHP8.0: get_debug_type().
            $type = gettype($this->shopSource);
            if ($type === 'object') {
                $type = get_class($this->shopSource);
            }
            throw new RuntimeException("$type is not a WC_Abstract_Order");
        }
        /** @noinspection PhpUndefinedMethodInspection */
        $this->id = $this->getSource()->get_id();
    }

    /**
     * Returns the user facing reference for the web shop's invoice source.
     *
     * Method get_order_number() is used for when other plugins are installed that add an
     * order number that differs from the ID. Known plugins that do so:
     * - woocommerce-sequential-order-numbers(-pro)
     * - wc-sequential-order-numbers
     * - custom-order-numbers-for-woocommerce(-pro)
     *
     * @return string|int
     *   The user facing id for the web shop's invoice source. This is not
     *   necessarily the internal id.
     */
    public function getReference()
    {
        if ($this->getType() === Source::Order) {
            /** @var \WC_Order $order */
            $order = $this->shopSource;
            return $order->get_order_number();
        }
        return parent::getReference();
    }

    /**
     * @inheritDoc
     */
    public function getDate(): string
    {
        // get_date_created() returns a WC_DateTime which has a _toString() method.
        return substr((string) $this->getSource()->get_date_created(), 0, strlen('2000-01-01'));
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): string
    {
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        return $this->getSource()->get_status();
    }

    /**
     * {@inheritdoc}
     *
     * This override returns the id of a WC_Payment_Gateway.
     */
    public function getPaymentMethod(): ?string
    {
        // Payment method is not stored for credit notes, so it is expected to
        // be the same as for its order.
        /** @var \WC_Order $order */
        $order = $this->getOrder()->shopSource;
        return $order->get_payment_method();
    }

    /**
     * Returns whether the order has been paid or not.
     *
     * @return int
     *   \Siel\Acumulus\Api::PaymentStatus_Paid or
     *   \Siel\Acumulus\Api::PaymentStatus_Due
     *
     * @noinspection PhpUnused : called via getPaymentStatus().
     */
    protected function getPaymentStatusOrder(): int
    {
        return $this->getSource()->is_paid() ? Api::PaymentStatus_Paid : Api::PaymentStatus_Due;
    }

    /**
     * Returns whether the order refund has been paid or not.
     *
     * For now, we assume that a refund is paid back on creation.
     *
     * @return int
     *   \Siel\Acumulus\Api::PaymentStatus_Paid or
     *   \Siel\Acumulus\Api::PaymentStatus_Due
     *
     * @noinspection PhpUnused Called via callTypeSpecificMethod().
     */
    protected function getPaymentStatusCreditNote(): int
    {
        return Api::PaymentStatus_Paid;
    }

    /**
     * Returns the payment date of the order.
     *
     * @return string
     *   The payment date of the order (yyyy-mm-dd).
     *
     * @noinspection PhpUnused : called via getPaymentDate().
     */
    protected function getPaymentDateOrder(): string
    {
        // get_date_paid() returns a WC_DateTime which has a _toString() method.
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        return substr((string) $this->getSource()->get_date_paid(), 0, strlen('2000-01-01'));
    }

    /**
     * Returns the payment date of the order refund.
     * We take the last modified date as pay date.
     *
     * @return string
     *   The payment date of the order refund (yyyy-mm-dd).
     *
     * @noinspection PhpUnused : called via getPaymentDate().
     */
    protected function getPaymentDateCreditNote(): string
    {
        // get_date_modified() returns a WC_DateTime which has a _toString() method.
        return substr((string) $this->getSource()->get_date_modified(), 0, strlen('2000-01-01'));
    }

    public function getCountryCode(): string
    {
        // Billing information is not stored for credit notes, so it is expected
        // to be the same as for its order.
        /** @var \WC_Order $order */
        $order = $this->getOrder()->shopSource;
        $tax_based_on = get_option('woocommerce_tax_based_on');
        $result = '';
        if ($tax_based_on === 'shipping') {
            $result = $order->get_shipping_country();
        }
        if (empty($result)) {
            $result = $order->get_billing_country();
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * This override provides the values meta-invoice-amountinc and
     * meta-invoice-vatamount.
     *
     * @noinspection PhpCastIsUnnecessaryInspection
     *   WooCommerce is not so strict when it comes to documenting its "@return"
     *   types. So many return values advertised as float, will be strings
     *   representing a float.
     */
    public function getTotals(): Totals
    {
        return new Totals((float) $this->getSource()->get_total(), (float) $this->getSource()->get_total_tax());
    }

    /**
     * {@inheritdoc}
     *
     * @return int
     */
    protected function getShopOrderOrId(): int
    {
        /** @var \WC_Order_Refund $refund */
        $refund = $this->shopSource;
        return $refund->get_parent_id();
    }

    /**
     * {@inheritdoc}
     *
     * @return \WC_Order_Refund[]
     */
    protected function getShopCreditNotesOrIds(): array
    {
        /** @var \WC_Order $order */
        $order = $this->shopSource;
        return $order->get_refunds();
    }
}
