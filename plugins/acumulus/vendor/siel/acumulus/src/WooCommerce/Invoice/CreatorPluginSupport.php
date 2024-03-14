<?php
/**
 * @noinspection DuplicatedCode  We have a copy of this class in Completors\Legacy.
 */

declare(strict_types=1);

namespace Siel\Acumulus\WooCommerce\Invoice;

use Siel\Acumulus\Invoice\InvoiceAddResult;
use Siel\Acumulus\Invoice\Source as BaseSource;
use Siel\Acumulus\Meta;
use Siel\Acumulus\Tag;
use WC_Booking;
use WC_Booking_Data_Store;
use WC_Order_Item_Product;
use WC_Product;

use function function_exists;
use function is_string;

/**
 * CreatorSupportForOtherPlugins contains support for other plugins.
 *
 * The WooCommerce market contains many additional plugins that add features
 * to standard WooCommerce. Supporting all these plugins is difficult and
 * results in hard to read and maintain code. Therefore, we try to split support
 * for these other plugins off into its own containers that react to the
 * Acumulus filters and actions.
 */
class CreatorPluginSupport
{
    /**
     * Called at the beginning of Creator::getItemLine().
     *
     * @param WC_Order_Item_Product $item
     *   An array representing an order item line, meta values are already
     *   available under their own names and as an array under key 'item_meta'.
     * @param WC_Product|bool|null $product
     *   The product that was sold on this line, may also be a bool according to
     *   the WC3 php documentation. I guess it will be false if the product has
     *   been deleted since.
     *
     * @noinspection PhpUnused
     */
    public function getItemLineBefore(Creator $creator, WC_Order_Item_Product $item, $product): void
    {
        $this->getItemLineBeforeBookings($creator, $item, $product);
    }

    /**
     * Called at the end of Creator::getItemLine().
     *
     * @param WC_Order_Item_Product $item
     *   An array representing an order item line, meta values are already
     *   available under their own names and as an array under key 'item_meta'.
     * @param WC_Product|bool|null $product
     *   The product that was sold on this line, may also be a bool according to
     *   the WC3 php documentation. I guess it will be false if the product has
     *   been deleted since.
     *
     * @noinspection PhpUnused
     */
    public function getItemLineAfter(
        Creator $creator,
        /** @noinspection PhpUnusedParameterInspection */ WC_Order_Item_Product $item,
        /** @noinspection PhpUnusedParameterInspection */$product
    ): void {
        $this->getItemLineAfterBookings($creator);
    }

    /**
     * Support for the "WooCommerce Bookings" plugin.
     *
     * Bookings are stored in a separate entity, we add that as a separate
     * property source, so its properties can be used.
     *
     * @param WC_Product|bool|null $product
     */
    public function getItemLineBeforeBookings(Creator $creator, WC_Order_Item_Product $item, $product): void
    {
        if (($product instanceof WC_Product)
            && function_exists('is_wc_booking_product')
            && is_wc_booking_product($product)
        ) {
            $booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_item_id($item->get_id());
            if ($booking_ids) {
                // I cannot imagine multiple bookings belonging to the same
                // order line, but if that occurs, only the 1st booking will
                // be added as a property source.
                $booking = new WC_Booking(reset($booking_ids));
                $creator->addPropertySource('booking', $booking);
                $resource = $booking->get_resource();
                if ($resource) {
                    $creator->addPropertySource('resource', $resource);
                }
            }
        }
    }

    /**
     * Supports the "WooCommerce Bookings" plugin.
     *
     * Removes the property source.
     */
    public function getItemLineAfterBookings(Creator $creator): void
    {
        $creator->removePropertySource('resource');
        $creator->removePropertySource('booking');
    }

    /**
     * Filter that reacts to the acumulus_invoice_created event.
     */
    public function acumulusInvoiceCreated(?array $invoice, BaseSource $invoiceSource, InvoiceAddResult $localResult): ?array
    {
        $invoice = $this->supportBundleProducts($invoice, $invoiceSource, $localResult);
        /** @noinspection PhpUnnecessaryLocalVariableInspection */
        $invoice = $this->supportTMExtraProductOptions($invoice, $invoiceSource, $localResult);
        return $invoice;
    }

    /**
     * Supports the "WooCommerce Bundle Products" plugin.
     * This method supports the woocommerce-product-bundles extension that
     * stores the bundle products as separate item lines below the bundle line
     * and uses the metadata described below to link them to each other.
     * This method hierarchically groups bundled products into the bundle
     * product and can do so multi-level.
     * Metadata on bundle lines:
     * - bundle_cart_key (hash) unique identifier.
     * - bundled_items (hash[]) refers to the bundle_cart_key of the bundled
     *     products.
     * Metadata on bundled items:
     * - bundled_by (hash) refers to bundle_cart_key of the bundle line.
     * - bundle_cart_key (hash) unique identifier.
     * - bundled_item_hidden: 'yes'|'no' or absent (= 'no').
     * 1) In a 1st pass, we first add bundle metadata to each invoice line that
     *    represents a bundle or bundled item.
     * 2) In a 2nd pass, we group the bundled items as children into the parent
     *    line.
     */
    protected function supportBundleProducts(?array $invoice, BaseSource $invoiceSource, /** @noinspection PhpUnusedParameterInspection */ InvoiceAddResult $localResult): ?array
    {
        /** @var \WC_Abstract_Order $shopSource */
        $shopSource = $invoiceSource->getSource();
        /** @var WC_Order_Item_Product[] $items */
        $items = $shopSource->get_items(apply_filters('woocommerce_admin_order_item_types', 'line_item'));
        foreach ($items as $item) {
            $bundleId = $item->get_meta('_bundle_cart_key');
            $bundledBy = $item->get_meta('_bundled_by');
            if (!empty($bundleId) || !empty($bundledBy)) {
                $line = &$this->getLineByMetaId($invoice[Tag::Customer][Tag::Invoice][Tag::Line], $item->get_id());
                if ($line !== null) {
                    // Add bundle meta data.
                    if ( !empty($bundleId)) {
                        // Bundle or bundled product.
                        $line[Meta::BundleId] = $bundleId;
                    }
                    if ( !empty($bundledBy)) {
                        // Bundled products only.
                        $line[Meta::BundleParentId] = $bundledBy;
                        $line[Meta::BundleVisible] = $item->get_meta('bundled_item_hidden') !== 'yes';
                    }
                }
            }
        }

        $invoice[Tag::Customer][Tag::Invoice][Tag::Line] = $this->groupBundles($invoice[Tag::Customer][Tag::Invoice][Tag::Line]);

        return $invoice;
    }

    protected function &getLineByMetaId(array &$lines, int $id): ?array
    {
        $result = null;
        foreach ($lines as &$line) {
            if ($line[Meta::Id] === $id) {
                $result = &$line;
            }
        }
        // Not found: occurs with refunds and a quantity of 0.
        return $result;
    }

    /**
     * Groups bundled products into the bundle product.
     *
     * @param array $itemLines
     *   The set of invoice lines that may contain bundle lines and bundled
     *   lines.
     *
     * @return array
     *   The set of item lines but with the lines of bundled items
     *   hierarchically placed in their bundle line.
     */
    protected function groupBundles(array $itemLines): array
    {
        $result = [];
        foreach ($itemLines as &$itemLine) {
            if (!empty($itemLine[Meta::BundleParentId])) {
                // Find the parent, note that we expect bundle products to
                // appear before their bundled products, so we can search in
                // $result and have a reference to a line in $result returned!
                $parent = &$this->getParentBundle($result, $itemLine[Meta::BundleParentId]);
                if ($parent !== null) {
                    // Add the bundled product as a child to the bundle.
                    if (!isset($parent[Meta::ChildrenLines])) {
                        $parent[Meta::ChildrenLines] = [];
                    }
                    $parent[Meta::ChildrenLines][] = $itemLine;
                } else {
                    // Oops: not found. Store a message in the line metadata
                    // and keep it as a separate line.
                    $itemLine[Meta::BundleParentId] .= ': not found';
                    $result[] = $itemLine;
                }
            } else {
                // Not a bundled product: just add it to the result.
                $result[] = $itemLine;
            }
        }
        return $result;
    }

    /**
     * Searches for, and returns by reference, the parent bundle line.
     *
     * @param array $lines
     *   The lines to search for the parent bundle line.
     * @param $parentId
     *   The meta-bundle-id value to search for.
     *
     * @return array|null
     *   The parent bundle line or null if not found.
     */
    protected function &getParentBundle(array &$lines, $parentId): ?array
    {
        foreach ($lines as &$line) {
            if (!empty($line[Meta::BundleId]) && $line[Meta::BundleId] === $parentId) {
                return $line;
            } elseif (!empty($line[Meta::ChildrenLines])) {
                // Recursively search for the parent bundle.
                $parent = &$this->getParentBundle($line[Meta::ChildrenLines], $parentId);
                if ($parent !== null) {
                    return $parent;
                }
            }
        }
        // Not found. We return a reference, so we cannot directly return null.
        $result = null;
        return $result;
    }

    /**
     * Supports the "WooCommerce TM Extra Product Options" plugin.
     * This method supports the tm-woo-extra-product-options extension that
     * places its data in the metadata under keys that start wth tm_epo or
     * tmcartepo. We need the tncartepo_data value as that contains the
     * options.
     * This method adds the option data as children to the invoice line.
     */
    protected function supportTMExtraProductOptions(
        ?array $invoice,
        BaseSource $invoiceSource,
        /** @noinspection PhpUnusedParameterInspection */
        InvoiceAddResult $localResult
    ): ?array {
        /** @var \WC_Abstract_Order $shopSource */
        $shopSource = $invoiceSource->getSource();
        /** @var WC_Order_Item_Product[] $items */
        $items = $shopSource->get_items(apply_filters('woocommerce_admin_order_item_types', 'line_item'));
        foreach ($items as $item) {
            // If the plugin is no longer used, we may still have an order with
            // products where the plugin was used. Moreover, we don't use any
            // function or method from the plugin, only its stored data. So we
            // do not have to check for the plugin being active, just for the
            // data being there.
            if (!empty($item['tmcartepo_data'])) {
                $line = &$this->getLineByMetaId($invoice[Tag::Customer][Tag::Invoice][Tag::Line], $item->get_id());
                if ($line !== null) {
                    $commonTags = [
                        Tag::Quantity => $line[Tag::Quantity],
                        Meta::VatRateSource => Creator::VatRateSource_Parent,
                    ];
                    if (!isset($line[Meta::ChildrenLines])) {
                        $line[Meta::ChildrenLines] = [];
                    }
                    $line[Meta::ChildrenLines] = array_merge($line[Meta::ChildrenLines], $this->getExtraProductOptionsLines($item, $commonTags));
                }
            }
        }

        return $invoice;
    }

    /**
     * Returns an array of lines that describes this variant.
     *
     * @param array|\ArrayAccess $item
     *   The item line
     * @param array $commonTags
     *   An array of tags from the parent product to add to the child lines.
     *
     * @return array[]
     *   An array of lines that describes this variant.
     */
    protected function getExtraProductOptionsLines($item, array $commonTags): array
    {
        $result = [];

        // It is a bit unclear what format this metadata should have. In old
        // versions I had an unconditional unserialize(), but now I get an array
        // of options (being arrays themselves) and code of the plugin itself
        // expects an array that may contain serialized values, i.e. it uses
        // maybe_unserialize() on the elements, not on the complete value.
        $options = $item['tmcartepo_data'];
        if (is_string($options)) {
            $options = (array) maybe_unserialize($options);
        } else {
            array_walk($options, static function(&$value) {
                if (is_string($value)) {
                    $value = maybe_unserialize($value);
                }
            });
        }

        foreach ($options as $option) {
            // Get option name and choice.
            $label = $option['name'];
            $choice = $option['value'];
            $result[] = [
                            Tag::Product => $label . ': ' . $choice,
                            Tag::UnitPrice => 0,
                        ] + $commonTags;
        }

        return $result;
    }
}
