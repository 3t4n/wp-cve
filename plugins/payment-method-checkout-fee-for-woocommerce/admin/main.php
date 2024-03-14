<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="feesettingscontainer">
    <h1>Payment Method Checkout Fee</h1>
    <p>You can easily add a percentage fee to your payment methods.</p>

    <form id="register_form" method="post" action="options.php">
        <?php
        settings_fields('checkoutfee_options_group');
        ?>

        <?php
        $gateways = WC()->payment_gateways->get_available_payment_gateways();
        $enabled_gateways = [];

        foreach ($gateways as $gateway) {

            if ($gateway->enabled == 'yes') {

                $enabled_gateways[] = $gateway;

                $methodtitle = $gateway->title;
                $methodslug = $gateway->id;
                ?>

                <div class="singletab entry-<?php echo $methodslug; ?>">
                    <h3><?php echo $methodtitle; ?></h3>
                    <p><?php echo $methodslug; ?></p>
                    <table>
                        <tr>
                            <td>
                                <label>
                                    <input type="checkbox" name="<?php echo $methodslug; ?>_name_enabled" value="1" <?php checked('1', get_option($methodslug . "_name_enabled")); ?>>
                                    Enabled
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="<?php echo $methodslug; ?>_name_label">Fee Label</label></th>
                            <td><input maxlength="50" type="text" id="<?php echo $methodslug; ?>_name_label" name="<?php echo $methodslug; ?>_name_label" value="<?php echo esc_attr(get_option($methodslug . "_name_label")); ?>" /></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="<?php echo $methodslug; ?>_name_percent">Percent</label></th>
                            <td><input type="number" step=".01" max="100" id="<?php echo $methodslug; ?>_name_percent" name="<?php echo $methodslug; ?>_name_percent" value="<?php echo esc_attr(get_option($methodslug . "_name_percent")); ?>" /></td>
                        </tr>
                    </table>
                </div>

                <?php
            }
        }
        ?>

        <?php submit_button(); ?>
        <p id="errors"></p>
    </form>

    <br /><br />
    <p style="font-size: 10px;">Powered by <a href="https://vipestudio.com" target="_blank">Vipe Studio</a></p>
</div>

<div class="wapuu">
    <a href="https://vipestudio.com/en/" target="_blank"><img src="<?php echo plugin_dir_url(__FILE__) . 'img/Wapuu-Vipe-Studio-Ltd.png'; ?>"></a>
</div>
