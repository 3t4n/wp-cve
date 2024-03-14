<?php

$message = '';
$error = '';
if (isset($_POST['submit'])) {
    if ($this->update_order($_GET['order']) === false) {
        $error = __('Error.', $this->plugin_slug);
    } else {
        $message = __('Order has been successfully updated.', $this->plugin_slug);
    }
}

$order = $this->get_order_by_id($_GET['order']);
$places = unserialize($order->places);

// get scheme
$place_ids = array_keys($places);
$scheme = $this->get_scheme_by_place_id($place_ids[0]);

?>

<?php if ($message || $error) : ?>
    <div class="<?php echo ($error) ? 'error' : 'updated' ?>" id="message">
        <p><?php echo $error . $message; ?></p>
    </div>
<?php endif; ?>

<h3 class="title"><?php _e("Order details", $this->plugin_slug); ?></h3>

<form action="" method="post">
    <table class="form-table">
        <tbody>

        <tr valign="top">
            <th scope="row"><?php _e("Code", $this->plugin_slug); ?></th>
            <td><?php echo $order->code; ?></td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e("Date", $this->plugin_slug); ?></th>
            <td><?php echo $order->date; ?></td>
        </tr>

        <?php if ($order->event_name) : ?>
            <tr valign="top">
                <th scope="row"><?php _e("Event Name", $this->plugin_slug); ?></th>
                <td><?php echo $order->event_name; ?></td>
            </tr>
        <?php else : ?>
            <tr valign="top">
                <th scope="row"><?php _e("Scheme Name", $this->plugin_slug); ?></th>
                <td><?php echo ($scheme) ? $scheme->name : __('Scheme not found', $this->plugin_slug); ?></td>
            </tr>
        <?php endif; ?>

        <tr valign="top">
            <th scope="row"><?php _e("First Name", $this->plugin_slug); ?></th>
            <td><?php echo $order->first_name; ?></td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e("Last Name", $this->plugin_slug); ?></th>
            <td><?php echo $order->last_name; ?></td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e("Email", $this->plugin_slug); ?></th>
            <td><?php echo $order->email; ?></td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e("Phone", $this->plugin_slug); ?></th>
            <td><?php echo $order->phone; ?></td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e("Notes", $this->plugin_slug); ?></th>
            <td><?php echo $order->notes; ?></td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e("Database ID", $this->plugin_slug); ?></th>
            <td><?php echo $order->order_id; ?></td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="order-status"><?php _e("Status", $this->plugin_slug); ?></label></th>
            <td>
                <select name="order-status" id="order-status">

                    <?php echo $this->get_order_statuses_options($order); ?>

                </select>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><?php _e("Total Price", $this->plugin_slug); ?></th>
            <td><?php echo $this->places_money_format($order->total_price); ?></td>
        </tr>

        <tr valign="top">
            <th scope="row">
                <?php _e("Places", $this->plugin_slug); ?> <br/>
                <span style="font-weight: normal; font-size: 10px;"><?php _e("NAME: PRICE", $this->plugin_slug); ?></span>
            </th>
            <td>
                <?php foreach ($places as $place_id => $place): ?>
                    <p><?php echo $place['place_name'] ? $place['place_name'] : __("Unnamed", $this->plugin_slug); ?>: <?php echo $this->places_money_format($place['place_price']); ?></p>
                <?php endforeach; ?>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="order-admin-notes"><?php _e("Admin notes", $this->plugin_slug); ?></label></th>
            <td>
                <textarea name="order-admin-notes" id="order-admin-notes" cols="50" rows="5"><?php echo esc_textarea($order->admin_notes); ?></textarea>
            </td>
        </tr>

        </tbody>
    </table>


    <p class="submit">
        <input type="submit" value="<?php _e("Save Changes", $this->plugin_slug); ?>" class="button button-primary" id="submit" name="submit">
        <a class="button" href="?page=<?php echo $this->get_proper_page_slug(); ?>"><?php _e("Cancel", $this->plugin_slug); ?></a>
    </p>
</form>