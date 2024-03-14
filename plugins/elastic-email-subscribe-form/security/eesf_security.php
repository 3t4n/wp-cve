<?php

if (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] === 'elasticEmailContactAdd') {

        add_action('wp_ajax_elasticEmailContactAdd', 'elasticEmailContactAdd');
        add_action('wp_ajax_nopriv_elasticEmailContactAdd', 'elasticEmailContactAdd');

        include_once 'config/eesf_config.php';

        $request = EESF_REQUEST . '?secret=' . EESF_SECRET_KEY . '&response=' . filter_input(INPUT_POST, "token", FILTER_SANITIZE_STRING);

        $response = file_get_contents($request);
        $resp = json_decode($response);

        do_action('wp_ajax_elasticEmailContactAdd', 'elasticEmailContactAdd');
        do_action('wp_ajax_nopriv_elasticEmailContactAdd', 'elasticEmailContactAdd');

        if ($resp->success === true) {
            do_action('wp_ajax_elasticEmailContactAdd', 'elasticEmailContactAdd');
            do_action('wp_ajax_nopriv_elasticEmailContactAdd', 'elasticEmailContactAdd');
            wp_send_json($resp);
        } else {
            wp_send_json_error(['success' => false, 'message' => 'Oops! It looks like you are trying to abuse our form. Please remember that we take spam seriously.']);
        }

    }
}

function elasticEmailContactAdd()
{

    $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
    $isNameDisabled = filter_input(INPUT_POST, "isNameDisabled", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $lists = filter_input(INPUT_POST, "lists", FILTER_SANITIZE_STRING);
    $activation_template = filter_input(INPUT_POST, "activationTemplate", FILTER_SANITIZE_STRING);

    if ($isNameDisabled === "true") {
        $data = array(
            'publicAccountID' => EESF_PUBLIC_ACID,
            'email' => $email,
            'listName' => $lists,
            'activationTemplate' => $activation_template,
        );
    } elseif ($isNameDisabled === "false") {
        $data = array(
            'publicAccountID' => EESF_PUBLIC_ACID,
            'email' => $email,
            'name' => $name,
            'listName' => $lists,
            'activationTemplate' => $activation_template,
        );
    } else {
        wp_send_json_error('IsNameDisabled is not set.');
    }

    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );

    $context = stream_context_create($options);
    $result = file_get_contents(EESF_REQUEST_CONTACT_ADD, false, $context);

    if ($result === false) {
        wp_send_json_error('Failed to add contact, please try again later.');
    } else {
        wp_send_json_success('The contact has been added to the list.');
    }

}