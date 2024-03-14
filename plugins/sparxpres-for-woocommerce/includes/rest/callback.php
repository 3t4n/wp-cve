<?php
defined('ABSPATH') || exit;

/**
 * Add sparxpres callback rest endpoint
 */
add_action('rest_api_init', function () {
    register_rest_route('sparxpres/v1', 'callback', array(
        'methods' => 'POST, PUT, PATCH',
        'callback' => 'sparxpres_callback',
        'permission_callback' => '__return_true'
    ));
});


/**
 * Handle callback
 * @param $request
 * @return WP_Error|WP_REST_Response
 */
function sparxpres_callback($request)
{
    $body = $request->get_body();
    if (empty($body)) {
        return new WP_Error('error', 'No body');
    }

    $params = json_decode($body);
    if (empty($params)) {
        return new WP_Error('error', 'Could not process body json');
    }

    $key = $params->key ?? "";
    $status = $params->status ?? "";
    $transactionId = $params->transactionId ?? "";
    $cbAmount = ceil($params->amount ?? 0);
    $cbAmountCents = ceil($params->amountCents ?? 0);
    if (empty($key) || empty($status) || empty($transactionId)) {
        return new WP_Error('error', 'Invalid json content');
    }

    if ($key !== SparxpresUtils::get_callback_identifier()) {
        return new WP_Error('error', 'Invalid key');
    }

    $order = wc_get_order($transactionId);
    if (empty($order)) {
        return new WP_Error('error', 'Invalid order');
    }

    if ($status === 'NEW'
        || $status === 'WAITING_FOR_SIGNATURE'
        || $status === 'RESERVED'
        || $status === 'CAPTURED'
    ) {
        if ($cbAmountCents > 0) {
            $orderAmtCents = ceil($order->get_total() * 100);
            if ($orderAmtCents < $cbAmountCents - 10 || $orderAmtCents > $cbAmountCents + 10) {
                // more than +/- 10 cents diff
                return new WP_Error('error', 'Invalid amount (expected: ' . $cbAmountCents . ', was: ' . $orderAmtCents . ')');
            }
        } else {
            $orderAmount = ceil($order->get_total());
            if ($orderAmount < $cbAmount - 1 || $orderAmount > $cbAmount + 1) {
                // more thant +/- 1 kr diff
                return new WP_Error('error', 'Invalid amount (expected: ' . $cbAmount . ', was: ' . $orderAmount . ')');
            }
        }
    }

    $originalStatusCode = $order->get_status();
    if ($originalStatusCode === 'cancelled'
        || $originalStatusCode === 'refunded'
        || $originalStatusCode === 'failed'
        || $originalStatusCode === 'completed'
        || ($originalStatusCode === 'processing' && $status !== 'CAPTURED')
    ) {
        $order->add_order_note(
            sprintf('Sparxpres sendte callback (%s), men ordrens status var %s, og er derfor IKKE opdateret.',
                $status,
                $originalStatusCode
            )
        );

        $response = new WP_REST_Response(array(
            "code" => "success",
            "message" => "Status NOT updated, because original status is: " . $originalStatusCode
        ));
        $response->set_status(200);
        return $response;
    }

    switch ($status) {
        case "NEW":
            if ($order->get_status() === 'pending') {
                // Awaiting payment – stock is reduced
                $order->update_status('on-hold', 'Sparxpres har modtaget anmodningen.');
            } else {
                $order->add_order_note('Sparxpres har modtaget anmodningen.');
            }
            break;
        case "WAITING_FOR_SIGNATURE":
            $order->add_order_note('Sparxpres afventer kundens underskrift.');
            break;
        case "REGRETTED":
        case "CANCELED":
        case "CANCELLED":
            $order->update_status('cancelled', 'Lånet/købet er annulleret.');
            break;
        case "DECLINE":
            $order->update_status('failed', 'Der er givet afslag på ansøgningen.');
            break;
        case "RESERVED":
            if (!$order->is_paid()) {
                $order->payment_complete();
            }
            $order->add_order_note('Betalingen er klar til frigivelse hos Sparxpres.');
            break;
        case "CAPTURED":
            if (!$order->is_paid()) {
                $order->payment_complete();
                $order->add_order_note('Betalingen er sat til udbetaling hos Sparxpres.');
            } else {
                $order->add_order_note('Betalingen er frigivet (sat til udbetaling) hos Sparxpres.');
            }
            break;
        default:
            return new WP_Error('error', 'Status not valid');
    }

    $response = new WP_REST_Response(array("code" => "success"));
    $response->set_status(200);
    return $response;
}
