<?php
/**
 * 後台 - 設定頁運送項目區塊
 */

defined('ECPAY_PLUGIN_PATH') || exit;

// 驗證
if (!is_array($ecpayLogisticsB2C)) {
    return;
}
if (!is_array($shipping_options)) {
    return;
}

?>

<!-- template -->
<tr valign="top">
    <th scope="row" class="titledesc">運送項目:</th>
    <td class="forminp" id="<?php echo $id; ?>_options">
    <table class="shippingrows widefat" cellspacing="0">
        <tbody>
        <?php
            foreach ($ecpayLogisticsB2C as $key => $value) {
        ?>
            <tr class="option-tr">
                <td><input type="checkbox" name="<?php echo esc_html($key);?>" value="<?php echo esc_html($key); ?>"
                <?php if (in_array($key, $shipping_options)) echo 'checked';?>> <?php echo esc_html($value); ?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    </td>
</tr>