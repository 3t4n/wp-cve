<div class="wrap furgonetka__wrap">
    <?php require __DIR__ . '/../../admin/partials/furgonetka-admin-messages.php'; ?>

    <h1 class="screen-reader-text furgonetka__header-primary"><?php
        esc_attr_e('Advanced settings', 'furgonetka'); ?></h1>
    <h1 class="furgonetka__header-primary"><?php
        esc_attr_e('Advanced settings', 'furgonetka'); ?></h1>
    <p class="furgonetka__info">
        <?php
        esc_attr_e(
            'Settings below are for advanced users with at least basic programming/web development knowledge.',
            'furgonetka'
        ); ?><br>

    </p>
    <h2 class="furgonetka__header-secondary"><?php
        esc_attr_e('Portmonetka', 'furgonetka'); ?></h2>
    <form method="post" action="">
        <input type="hidden" name="furgonetkaAction" value="<?= Furgonetka_Admin::ACTION_SAVE_ADVANCED ?>"/>
        <?php wp_nonce_field(); ?>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="productSelector" class="furgonetka__label">
                        <?php
                        esc_attr_e('Portmonetka product button parent html selector', 'furgonetka'); ?>
                    </label>
                </th>
                <td>
                    <input
                            name="portmonetka_product_selector"
                            type="text"
                            id="productSelector"
                            value="<?php
                            echo esc_html(Furgonetka_Admin::get_portmonetka_product_selector()); ?>"
                            class="regular-text furgonetka__input"
                    >
                    <p class="furgonetka__input-info">
                        <?php
                        esc_attr_e(
                            'Use only when default button placement is wrong, will display button within woocommerce single product page.',
                            'furgonetka'
                        ); ?><br>
                        <?php
                        esc_attr_e('Element must be inside woocommerce add to cart form', 'furgonetka'); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="cartSelector" class="furgonetka__label">
                        <?php
                        esc_attr_e('Portmonetka cart button parent html selector', 'furgonetka'); ?>
                    </label>
                </th>
                <td>
                    <input
                            name="portmonetka_cart_selector"
                            type="text"
                            id="cartSelector"
                            value="<?php
                            echo esc_html(Furgonetka_Admin::get_portmonetka_cart_selector()); ?>"
                            class="regular-text furgonetka__input"
                    >
                    <p class="furgonetka__input-info">
                        <?php
                        esc_attr_e(
                            'Use only when default button placement is wrong, will display button within woocommerce cart page.',
                            'furgonetka'
                        ); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="miniCartSelector" class="furgonetka__label">
                        <?php
                        esc_attr_e('Portmonetka mini cart button parent html selector', 'furgonetka'); ?>
                    </label>
                </th>
                <td>
                    <input
                            name="portmonetka_minicart_selector"
                            type="text"
                            id="miniCartSelector"
                            value="<?php
                            echo esc_html(Furgonetka_Admin::get_portmonetka_minicart_selector()); ?>"
                            class="regular-text furgonetka__input"
                    >
                    <p class="furgonetka__input-info">
                        <?php
                        esc_attr_e(
                            'Use only when default button placement is wrong, will display button within woocommerce mini cart widget.',
                            'furgonetka'
                        ); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="cartButtonPosition" class="furgonetka__label">
                        <?php
                        esc_attr_e('Portmonetka cart button position', 'furgonetka'); ?>
                    </label>
                </th>
                <td>
                    <select
                            name="portmonetka_cart_button_position"
                            id="cartButtonPosition"
                            class="regular-text furgonetka__select"
                    >
                        <?php
                        $cart_button_position_value = Furgonetka_Admin::get_portmonetka_cart_button_position();
                        foreach ($additional_data['position_options'] as $label => $value) { ?>
                            <option value="<?php
                            echo $value; ?>"<?php
                            echo ($cart_button_position_value === $value) ? 'selected' : '' ?>>
                                <?php
                                esc_attr_e($label, 'furgonetka'); ?>
                            </option>
                        <?php
                        } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="cartButtonWidth" class="furgonetka__label">
                        <?php
                        esc_attr_e('Portmonetka cart button width', 'furgonetka'); ?>
                    </label>
                </th>
                <td>
                    <select
                            name="portmonetka_cart_button_width"
                            id="cartButtonWidth"
                            class="regular-text furgonetka__select"
                    >
                        <?php
                        $cart_button_width_value = Furgonetka_Admin::get_portmonetka_cart_button_width();

                        foreach ($additional_data['width_options'] as $label => $value) { ?>
                            <option value="<?php
                            echo $value; ?>"<?php
                            echo ($cart_button_width_value === $value) ? 'selected' : '' ?>>
                                <?php
                                esc_attr_e($label, 'furgonetka'); ?>
                            </option>
                        <?php
                        } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="cartButtonCSS" class="furgonetka__label">
                        <?php
                        esc_attr_e('Portmonetka cart button custom CSS', 'furgonetka'); ?>
                    </label>
                </th>
                <td>
                            <textarea
                                    name="portmonetka_cart_button_css"
                                    type="text"
                                    id="cartButtonCSS"
                                    placeholder=".selector { width: 100%; }"
                                    class="regular-text furgonetka__input"
                            ><?php
                                echo esc_html(Furgonetka_Admin::get_portmonetka_cart_button_css()); ?></textarea>
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

    <h2 class="furgonetka__header-secondary furgonetka__header-secondary--margin-top-80"><?php
        esc_attr_e('Furgonetka - API account', 'furgonetka'); ?></h2>
    <form method="post" action="">
        <?php wp_nonce_field(); ?>
        <input type="hidden" name="furgonetkaAction" value="<?= Furgonetka_Admin::ACTION_RESET_CREDENTIALS ?>"/>
        <p class="furgonetka__info">
            <?php esc_attr_e( 'You can reset current account by clicking Reset button', 'furgonetka' ); ?>
        </p>
        <table class="form-table">
            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-secondary-red furgonetka__button-secondary-red" value="<?php esc_attr_e( 'Reset', 'furgonetka' ); ?>">
            </p>
        </table>
    </form>
</div>
