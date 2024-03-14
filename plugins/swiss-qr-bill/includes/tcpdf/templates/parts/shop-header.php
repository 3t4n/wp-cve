<?php
if ( !defined('ABSPATH') ) {
    exit();
}

?>
<style>
    td.f-small {
        font-size: 10px;
    }
</style>
<table border="0">
    <?php if ( $gateway_options['shop_logo'] ): ?>
        <tr>
            <td style="padding: 0"><img
                        src="<?php echo get_attached_file($gateway_options['shop_logo'], true); ?>"
                        width="auto" height="80">
            </td>
        </tr>
    <?php endif; ?>
    <tr>
        <td class="f-small"><br><b><?php echo $gateway_options['shop_name']; ?></b>
            <?php echo $gateway_options['shop_street_address_1'] ? '<br>' . $gateway_options['shop_street_address_1'] : ''; ?>
            <?php echo $gateway_options['shop_address_2'] != '' ? '<br>' . $gateway_options['shop_address_2'] : ''; ?>
            <?php echo $gateway_options['shop_zipcode'] || $gateway_options['shop_city'] ? '<br>' . $gateway_options['shop_zipcode'] . ' ' . $gateway_options['shop_city'] : '' ?>
            <br>

            <?php echo $gateway_options['shop_telephone'] ? '<br>' . __('Telephone', 'swiss-qr-bill') . ': ' . $gateway_options['shop_telephone'] : '' ?>
            <?php echo $gateway_options['shop_email'] ? '<br>' . __('Email', 'swiss-qr-bill') . ': ' . $gateway_options['shop_email'] : '' ?>
            <?php echo $gateway_options['shop_vat_number'] ? '<br><br>' . __('VAT Number', 'swiss-qr-bill') . ': ' . $gateway_options['shop_vat_number'] : '' ?>
        </td>
    </tr>
</table>