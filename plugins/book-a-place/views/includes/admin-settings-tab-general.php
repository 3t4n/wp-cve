<form action="" method="post">
    <input type="hidden" name="current-tab" value="1"/>
    <table class="form-table">
        <tbody>

        <tr valign="top">
            <th scope="row"><label for="email"><?php _e("E-mail Address", $this->plugin_slug); ?></label></th>
            <td>
                <input type="text" class="regular-text" value="<?php echo isset($options['email']) ? esc_attr($options['email']) : ''; ?>" id="email" name="email">

                <p class="description"><?php _e("This address is used for admin purposes, like new order notification.", $this->plugin_slug); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="cart-expiration-time"><?php _e("Cart Expiration Time", $this->plugin_slug); ?></label></th>
            <td>
                <input type="text" class="small-text" value="<?php echo isset($options['cart-expiration-time']) ? esc_attr($options['cart-expiration-time']) : ''; ?>" id="cart-expiration-time" name="cart-expiration-time"> <?php _e("minutes", $this->plugin_slug); ?>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="currency-symbol"><?php _e("Currency Symbol", $this->plugin_slug); ?></label></th>
            <td>
                <select name="currency-symbol" id="currency-symbol">
                    <?php $currency_symbols = $this->get_currency_symbols(); ?>
                    <?php foreach ($currency_symbols as $key => $currency_symbol) : ?>
                        <option <?php selected($key, $options['currency-symbol']); ?> value="<?php echo $key; ?>"><?php echo $currency_symbol; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>

        </tbody>
    </table>


    <p class="submit">
        <input type="submit" value="<?php _e("Save Changes", $this->plugin_slug); ?>" class="button button-primary" id="submit" name="submit">
    </p>
</form>