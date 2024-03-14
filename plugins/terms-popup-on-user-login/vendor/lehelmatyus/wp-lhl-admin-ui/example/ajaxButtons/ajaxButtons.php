<?php

$reset_disabled = false;
if (!$this->license_is_active) {
    $reset_disabled = true;
}

$reset_link_text = __("Ajax Button Example", 'terms-popup-on-user-login');
$onclick_event_name = "ajaxButtonExample";

AdminForm::button__active_key_required(
    $this->license_is_active,
    [],
    $reset_link_text,
    "button_id_EXAMPLE",
    $reset_disabled,
    $onclick_event_name,
    [
        'data-user-id = ' . $user->ID
    ]
);



/**
 * Example
 */



$btn = [
    'id' => 'bbrc_report',
    'classes_string' => implode(" ", ['bbrc_report_btn', 'lhl-ui-btn']),
    'attr_string' => implode(" ", ['enabled=true']),
    'onclick_attr' => " onclick=bbrc_report_click(event)",
    'data' => json_encode(
        [
            "reply_id" => $reply_id,
        ]
    ),
    'title' => __('Report', 'bb_report_content'),
    'msg' => [
        'classes_string' => implode(" ", ['lhl-ui-msg', 'hidden']),
        'wait' => __('reporting..', 'bb_report_content'),
        'success' => __('reported!', 'bb_report_content'),
        'error' => __('error', 'bb_report_content'),
    ]
];

$link_ =  sprintf(
    '<a id="%s" class="%s" %s %s data-vals="%s">%s</a>',
    esc_attr($btn['id']),
    esc_attr($btn['classes_string']),
    esc_attr($btn['attr_string']),
    esc_attr($btn['onclick_attr']),
    esc_attr($btn['data']),
    esc_attr($btn['title'])
);
$link_ .=  sprintf(
    " <span class='%s__msg %s' data-wait-msg='%s' data-success-msg='%s' data-error-msg='%s'></span>",
    esc_attr($btn['id']),
    esc_html($btn['msg']['classes_string']),
    esc_html($btn['msg']['wait']),
    esc_html($btn['msg']['success']),
    esc_html($btn['msg']['error']),
);
