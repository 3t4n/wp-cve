<?php

use WPO\WC\MyParcelBE\Compatibility\Order as WCX_Order;
use WPO\WC\MyParcelBE\Compatibility\WC_Core as WCX;

defined('ABSPATH') or die();

include('html-start.php');

/**
 * @var array $order_ids
 */

$add_return = WCMPBE_Export::ADD_RETURN;
$export     = WCMPBE_Export::EXPORT;

$order_ids_string = implode(';', $order_ids);

$target_url = wp_nonce_url(
    admin_url("admin-ajax.php?action=$export&request=$add_return&modal=true&order_ids=$order_ids_string"),
    WCMYPABE::NONCE_ACTION
);

?>
    <form
        method="post" class="page-form wcmpbe__bulk-options wcmpbe__return-dialog" action="<?php echo $target_url; ?>">
        <table style="width: 100%">
            <tbody>
            <?php
            $c = true;
            foreach ($order_ids as $order_id) :
                $order         = WCX::get_order($order_id);
                $orderSettings = new OrderSettings($order);

                // skip non-myparcel destinations
                $shipping_country = WCX_Order::get_prop($order, 'shipping_country');
                if (! WCMPBE_Country_Codes::isAllowedDestination($shipping_country)) {
                    continue;
                }

                $package_types = WCMPBE_Data::getPackageTypes();
                ?>
                <tr
                    class="order-row <?php echo(($c = ! $c)
                        ? 'alternate'
                        : ''); ?>">
                    <td>
                        <table style="width: 100%">
                            <tr>
                                <td colspan="2">
                                    <strong>
                                        <?php echo sprintf(
                                            "%s %s",
                                            __("Order", "woocommerce-myparcelbe"),
                                            $order->get_order_number()
                                        ); ?>
                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <td class="ordercell">
                                    <table class="widefat">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php _e("Product name", "woocommerce-myparcelbe"); ?></th>
                                            <th class="wcmpbe__text--right"><?php _e("weight", "woocommerce-myparcelbe"); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($order->get_items() as $item_id => $item) : ?>
                                            <tr>
                                                <td><?php echo $item['qty'] . 'x'; ?></td>
                                                <td><?php echo WCMPBE_Export::get_item_display_name($item, $order) ?></td>
                                                <td class="wcmpbe__text--right">
                                                    <?php

                                                    $weight = $item->get_product()->weight;

                                                    if ($weight) {
                                                        echo wc_format_weight($weight * $item['qty']);
                                                    } else {
                                                        echo esc_html__('N/A', 'woocommerce');
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th><?php _e("Total weight", "woocommerce-myparcelbe"); ?></th>
                                            <th class="wcmpbe__text--right">
                                                <?php
                                                $weight = $orderSettings->getWeight();

                                                if ($weight) {
                                                    echo wc_format_weight($weight);
                                                } else {
                                                    echo esc_html__('N/A', 'woocommerce');
                                                }
                                                ?>
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </td>
                            </tr> <!-- last row -->
                            <?php
                            echo '<p>' . $order->get_formatted_shipping_address() . '<br/>' . WCX_Order::get_prop(
                                    $order,
                                    'billing_phone'
                                ) . '<br/>' . WCX_Order::get_prop($order, 'billing_email') . '</p>';
                            ?>
                            </td></tr>
                            <tr>
                                <td
                                    colspan="2" class="wcmpbe__shipment-options">
                                    <?php
                                    $skip_save = true; // dont show save button for each order
                                    if (isset($dialog) && $dialog === 'shipment') {
                                        include('html-order-shipment-options.php');
                                    } else {
                                        include('html-order-return-shipment-options.php');
                                    }
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div>
            <?php
            if (isset($dialog) && $dialog === 'shipment') {
                $button_text = __("Export to MyParcel BE", "woocommerce-myparcelbe");
            } else {
                $button_text = __("Send email", "woocommerce-myparcelbe");
            }
            ?>
            <div class="wcmpbe__d--flex">
                <input type="submit" value="<?php echo $button_text; ?>" class="button wcmpbe__return-dialog__save">
                <?php WCMYPABE_Admin::renderSpinner() ?>
            </div>
        </div>
    </form>

<?php

include('html-end.php');
