<div class="wrap furgonetka__wrap">
    <?php require __DIR__ . '/../../admin/partials/furgonetka-admin-messages.php'; ?>

    <h1 class="screen-reader-text"><?php
        esc_attr_e('Attach map to delivery option', 'furgonetka'); ?></h1>
    <h2 class="furgonetka__header-secondary"><?php
        esc_attr_e('Attach map to delivery option', 'furgonetka'); ?></h2>
    <p class="furgonetka__info">
        <?php
        esc_attr_e(
            'Please remember! You can only attach map to flat rates delivery option. Every delivery option can have one map attached',
            'furgonetka'
        ); ?>
    </p>

    <form method="post" action="">
        <?php
        $delivery_to_type = get_option(FURGONETKA_PLUGIN_NAME . '_deliveryToType'); ?>
        <?php
        wp_nonce_field(); ?>
        <input type="hidden" name="furgonetkaAction" value="<?= Furgonetka_Admin::ACTION_SAVE_DELIVERY ?>"/>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="inpost" class="furgonetka__label">
                        <?php
                        esc_attr_e('Add InPost map to:', 'furgonetka'); ?>
                    </label>
                </th>
                <td>
                    <?php
                    Furgonetka_Admin::map_attach_to('inpost', $delivery_to_type); ?>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="poczta" class="furgonetka__label">
                        <?php
                        esc_attr_e('Add Poczta Polska map to:', 'furgonetka'); ?>
                    </label>
                </th>
                <td>
                    <?php
                    Furgonetka_Admin::map_attach_to('poczta', $delivery_to_type); ?>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="kiosk" class="furgonetka__label">
                        <?php
                        esc_attr_e('Add ORLEN Paczka map to:', 'furgonetka'); ?>
                    </label>
                </th>
                <td>
                    <?php
                    Furgonetka_Admin::map_attach_to('kiosk', $delivery_to_type); ?>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="uap" class="furgonetka__label">
                        <?php
                        esc_attr_e('Add UPS Access Point map to:', 'furgonetka'); ?>
                    </label>
                </th>
                <td>
                    <?php
                    Furgonetka_Admin::map_attach_to('uap', $delivery_to_type); ?>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="dpd" class="furgonetka__label">
                        <?php
                        esc_attr_e('Add DPD Pickup map to:', 'furgonetka'); ?>
                    </label>
                </th>
                <td>
                    <?php
                    Furgonetka_Admin::map_attach_to('dpd', $delivery_to_type); ?>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="dhl" class="furgonetka__label">
                        <?php
                        esc_attr_e('Add DHL Parcel map to:', 'furgonetka'); ?>
                    </label>
                </th>
                <td>
                    <?php
                    Furgonetka_Admin::map_attach_to('dhl', $delivery_to_type); ?>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="fedex" class="furgonetka__label">
                        <?php
                        esc_attr_e('Add FedEx Point map to:', 'furgonetka'); ?>
                    </label>
                </th>
                <td>
                    <?php
                    Furgonetka_Admin::map_attach_to('fedex', $delivery_to_type); ?>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="gls" class="furgonetka__label">
                        <?php
                        esc_attr_e('Add GLS Szybka Paczka map to*:', 'furgonetka'); ?>
                    </label>
                </th>
                <td>
                    <?php
                    Furgonetka_Admin::map_attach_to('gls', $delivery_to_type); ?>
                    <p class="furgonetka__input-info">
                        <?php
                        esc_attr_e('* Applies to own contracts in the ShopDeliveryService service', 'furgonetka'); ?>
                    </p>
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit">
            <input
                    type="submit"
                    name="submit"
                    class="button button-primary furgonetka__button-primary"
                    value="<?php
                    esc_attr_e('Save', 'furgonetka'); ?>"
            >
        </p>
    </form>
</div>