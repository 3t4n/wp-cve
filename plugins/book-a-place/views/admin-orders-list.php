<?php

/*
 * Select Orders
 */

$message = '';
$error = false;

$currency_symbols = $this->get_currency_symbols();

$search_form_style = '';
$toggle_search_form_text = __('Hide search form', $this->plugin_slug);
$toggle_search_form_alt_text = __('Show search form', $this->plugin_slug);


// default field values
$post_fields = array(
    'email' => '',
    'phone' => '',
    'code' => '',
    'status_id' => '',
    'price_from' => '',
    'price_to' => '',
    'date_from' => '',
    'date_to' => '',
    'first_name' => '',
    'last_name' => '',
);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $search_form_style = ' style="display: block;"';
    $txt = $toggle_search_form_alt_text;
    $toggle_search_form_alt_text = $toggle_search_form_text;
    $toggle_search_form_text = $txt;

    $search_error_msg = '';

    // pre form validation
    $coupon_form_config = array(
        'email' => array('text', true, false, false),
        'phone' => array('text', true, false, false),
        'code' => array('text', true, false, false),
        'status_id' => array('select', true, false, false),
        'price_from' => array('text', true, false, false),
        'price_to' => array('text', true, false, false),
        'date_from' => array('text', true, false, false),
        'date_to' => array('text', true, false, false),
        'first_name' => array('text', true, false, false),
        'last_name' => array('text', true, false, false),
    );
    $bap_pre_form_validation = new Bap_PreFormValidation($_POST, $coupon_form_config);
    $valid = $bap_pre_form_validation->validate();
    $post_fields = $bap_pre_form_validation->getFields();

    if ($valid) {
        $orders = $this->search_orders($post_fields);
    } else {
        $error = true;
        $search_error_msg = __('Search Error.', $this->plugin_slug);
    }

} else {
    $orders = $this->get_orders();
}

?>

<?php if ($message || $error) : ?>
    <div class="<?php echo ($error) ? 'error' : 'updated' ?>" id="message">
        <p><?php echo $search_error_msg . $message; ?></p>
    </div>
<?php endif; ?>

<div class="orders-search-actions">
    <a id="orders-search-toggle" href="#" data-alt-text="<?php echo $toggle_search_form_text; ?>"><?php echo $toggle_search_form_alt_text; ?></a>
</div>

<div <?php echo $search_form_style; ?> id="orders-search" class="orders-search">
    <div class="orders-search-form">
        <form id="search-form" action="" method="post">

            <div class="search-row">
                <label for="orders-search-first-name"><?php _e('First Name:', $this->plugin_slug); ?></label>
                <input class="regular-text" id="orders-search-first-name" name="first_name" type="text" value="<?php echo $post_fields['first_name']; ?>"/>
            </div>

            <div class="search-row">
                <label for="orders-search-last-name"><?php _e('Last Name:', $this->plugin_slug); ?></label>
                <input class="regular-text" id="orders-search-last-name" name="last_name" type="text" value="<?php echo $post_fields['last_name']; ?>"/>
            </div>

            <div class="search-row">
                <label for="orders-search-email"><?php _e('Email:', $this->plugin_slug); ?></label>
                <input class="regular-text" id="orders-search-email" name="email" type="text" value="<?php echo $post_fields['email']; ?>"/>
            </div>

            <div class="search-row">
                <label for="orders-search-phone"><?php _e('Phone:', $this->plugin_slug); ?></label>
                <input class="regular-text" id="orders-search-phone" name="phone" type="text" value="<?php echo $post_fields['phone']; ?>"/>
            </div>

            <div class="search-row">
                <label for="orders-search-code"><?php _e('Order Code:', $this->plugin_slug); ?></label>
                <input class="regular-text" id="orders-search-code" name="code" type="text" value="<?php echo $post_fields['code']; ?>"/>
            </div>

            <div class="search-row">
                <label for="orders-search-status"><?php _e('Order Status:', $this->plugin_slug); ?></label>
                <select name="status_id" id="orders-search-status">
                    <?php $order = new stdClass(); $order->status_id = $post_fields['status_id']; ?>
                    <?php echo '<option value="" ' . selected('', $order->status_id, false) . '>' . __('All', $this->plugin_slug) . '</option>'; ?>
                    <?php echo $this->get_order_statuses_options($order); ?>
                </select>
            </div>

            <div class="search-row">
                <label for="orders-search-price-from"><?php _e('Order Price:', $this->plugin_slug); ?></label>
                <?php echo $currency_symbols[$this->options['currency-symbol']]; ?>
                <input class="small-text" id="orders-search-price-from" name="price_from" type="text" value="<?php echo $post_fields['price_from']; ?>"/>
                -
                <?php echo $currency_symbols[$this->options['currency-symbol']]; ?>
                <input class="small-text" id="orders-search-price-to" name="price_to" type="text" value="<?php echo $post_fields['price_to']; ?>"/>
            </div>

            <div class="search-row">
                <label for="orders-search-date-from"><?php _e("Order Date:", $this->plugin_slug); ?></label>
                <input type="text" class="regular-text" id="orders-search-date-from" name="date_from" value="<?php echo $post_fields['date_from']; ?>">
                -
                <input type="text" class="regular-text" id="orders-search-date-to" name="date_to" value="<?php echo $post_fields['date_to']; ?>">
            </div>

            <div style="margin-bottom: 0;" class="search-row">
                <a style="vertical-align: middle;" href="<?php echo $this->page_url; ?>" title="<?php _e("Reset Search", $this->plugin_slug); ?>" class="button"><?php _e("Reset Search", $this->plugin_slug); ?></a>
                <input style="vertical-align: middle;" class="button button-primary" type="submit" value="<?php _e("Search", $this->plugin_slug); ?>"/>
            </div>


        </form>
    </div>
</div>

<div class="orders-actions">
    <a href="<?php echo $this->page_url; ?>&action=export" title="<?php _e("Export to csv", $this->plugin_slug); ?>" class="button export-to-pdf"><?php _e("Export to csv", $this->plugin_slug); ?></a>
    <a href="<?php echo $this->page_url; ?>&action=clear" title="<?php _e("Clear table", $this->plugin_slug); ?>" class="button orders-clear-submit"><?php _e("Clear", $this->plugin_slug); ?></a>
</div>

<!--    Order List-->
<form method="get" action="" id="posts-filter">

    <table cellspacing="0" class="wp-list-table widefat fixed posts">

        <thead>
        <tr>

            <th style="" class="" scope="col">
                <?php _e("Code", $this->plugin_slug); ?>
            </th>
            <th style="" class="" scope="col">
                <?php _e("Event Name", $this->plugin_slug); ?>
            </th>
            <th style="" class="" scope="col">
                <?php _e("Scheme Name", $this->plugin_slug); ?>
            </th>
            <th style="" class="" scope="col">
                <?php _e("Places", $this->plugin_slug); ?> <span title="<?php _e("NAME", $this->plugin_slug); ?>" style="display: inline-block; vertical-align: middle;" class="ui-icon ui-icon-info places-info-tooltip"></span>
            </th>
            <th style="" class="" scope="col">
                <?php _e("First Name", $this->plugin_slug); ?>
            </th>
            <th style="" class="" scope="col">
                <?php _e("Last Name", $this->plugin_slug); ?>
            </th>
            <th style="" class="" scope="col">
                <?php _e("Email", $this->plugin_slug); ?>
            </th>
            <th style="" class="" scope="col">
                <?php _e("Phone", $this->plugin_slug); ?>
            </th>
            <th style="" class="" scope="col">
                <?php _e("Date", $this->plugin_slug); ?>
            </th>
            <th style="" class="" scope="col">
                <?php _e("Total price", $this->plugin_slug); ?>
            </th>
            <th style="" class="" scope="col">
                <?php _e("Status", $this->plugin_slug); ?>
            </th>

        </tr>
        </thead>

        <tfoot>
        <tr>

            <th style="" class="" scope="col">
                <?php _e("Code", $this->plugin_slug); ?>
            </th>
            <th style="" class="" scope="col">
                <?php _e("Event Name", $this->plugin_slug); ?>
            </th>
            <th style="" class="" scope="col">
                <?php _e("Scheme Name", $this->plugin_slug); ?>
            </th>
            <th style="" class="" scope="col">
                <?php _e("Places", $this->plugin_slug); ?> <span title="<?php _e("NAME", $this->plugin_slug); ?>" style="display: inline-block; vertical-align: middle;" class="ui-icon ui-icon-info places-info-tooltip"></span>
            </th>
            <th style="" class="" scope="col">
                <?php _e("First Name", $this->plugin_slug); ?>
            </th>
            <th style="" class="" scope="col">
                <?php _e("Last Name", $this->plugin_slug); ?>
            </th>
            <th style="" class="" scope="col">
                <?php _e("Email", $this->plugin_slug); ?>
            </th>
            <th style="" class="" scope="col">
                <?php _e("Phone", $this->plugin_slug); ?>
            </th>
            <th style="" class="" scope="col">
                <?php _e("Date", $this->plugin_slug); ?>
            </th>
            <th style="" class="" scope="col">
                <?php _e("Total price", $this->plugin_slug); ?>
            </th>
            <th style="" class="" scope="col">
                <?php _e("Status", $this->plugin_slug); ?>
            </th>

        </tr>
        </tfoot>

        <tbody id="the-list">

        <?php if ($orders && is_array($orders)): ?>
            <?php foreach ($orders as $order) : ?>

                <?php
                $places = unserialize($order->places);
                $scheme = $this->get_scheme_by_place_id(key($places));
                ?>

                <tr valign="top" class="post-1 type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self" id="post-1">

                    <td class="post-title page-title column-title">
                        <strong><?php echo $order->code; ?></strong>

                        <div class="row-actions">
                            <span class="view"><a rel="permalink" title="<?php _e("View this item", $this->plugin_slug); ?>" href="<?php echo $this->page_url; ?>&order=<?php echo $order->order_id; ?>&action=view"><?php _e("View", $this->plugin_slug); ?></a>  </span> |
                            <span class="trash submitdelete"><a href="<?php echo $this->page_url; ?>&order=<?php echo $order->order_id; ?>&action=delete" title="<?php _e("Delete this item", $this->plugin_slug); ?>"><?php _e("Delete", $this->plugin_slug); ?></a></span>
                        </div>
                    </td>
                    <td class="author column-author">
                        <?php echo $order->event_name; ?>
                    </td>
                    <td class="author column-author">
                        <?php echo $scheme->name; ?>
                    </td>
                    <td class="author column-author">
                        <?php if ($places && is_array($places)): ?>
                            <?php foreach ($places as $place_id => $place) : ?>
                                -&nbsp;<?php echo $place['place_name'] ? $place['place_name'] : __("Unnamed", $this->plugin_slug); ?><br/>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </td>
                    <td class="author column-author">
                        <?php echo $order->first_name; ?>
                    </td>
                    <td class="categories column-categories">
                        <?php echo $order->last_name; ?>
                    </td>
                    <td class="tags column-tags">
                        <?php echo $order->email; ?>
                    </td>
                    <td class="tags column-tags">
                        <?php echo $order->phone; ?>
                    </td>
                    <td class="tags column-tags">
                        <?php echo $order->date; ?>
                    </td>
                    <td class="tags column-tags">
                        <?php echo $this->places_money_format($order->total_price); ?>
                    </td>
                    <td class="tags column-tags">
                        <?php echo $this->order_statuses[$order->status_id]; ?>
                    </td>

                </tr>


            <?php endforeach; ?>

        <?php else : ?>

        <tr valign="top" class="post-1 type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self" id="post-1">
            <td colspan="10">
                <?php _e("There are no orders yet.", $this->plugin_slug); ?>
            </td>
        </tr>

        <?php endif; ?>


        </tbody>

    </table>

</form>
<!--    End Order List-->

<div style="margin-top: 10px;" class="orders-actions">
    <a href="<?php echo $this->page_url; ?>&action=export" title="<?php _e("Export to csv", $this->plugin_slug); ?>" class="button export-to-pdf"><?php _e("Export to csv", $this->plugin_slug); ?></a>
    <a href="<?php echo $this->page_url; ?>&action=clear" title="<?php _e("Clear table", $this->plugin_slug); ?>" class="button orders-clear-submit"><?php _e("Clear", $this->plugin_slug); ?></a>
</div>

<script type="text/javascript">
    jQuery('.submitdelete').click(function() {
        if (!confirm('<?php _e("Are you sure you want to delete this item?", $this->plugin_slug); ?>')) {
            return false;
        }
    });

    jQuery('.orders-clear-submit').click(function() {
        if (!confirm('<?php _e("Are you sure you want to clear this list?", $this->plugin_slug); ?>')) {
            return false;
        }
    });

    jQuery(function() {
        jQuery('.places-info-tooltip').tooltip();
    });
</script>