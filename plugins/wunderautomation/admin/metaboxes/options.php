<?php

if (!defined('ABSPATH')) {
    exit;
}

global $post, $action;

$postObject = get_post_type_object('automation-workflow');
$sortOrder  = (int)get_post_meta($post->ID, 'sortorder', true);
$sortOrder  = $sortOrder == 0 ? 5 : $sortOrder;
$data       = json_encode(
    (object)[
        'options' => (object)[
            'sortOrder' => $sortOrder,
        ]
    ]
);
?>

<div id="optionsmetabox">
</div>

<template id="options-component">
    <div class="submitbox" id="workflowoptions">
        <?php _e('Workflow order ', 'wunderauto'); ?>
        <input v-model="options.sortOrder" type="number" step="1" min="1" max="99" maxlength="3">
        <br>
        <i>
            <?php
            _e(
                'If two workflows use the same trigger, you can control which workflow runs first using the order ' .
                'option. Default = 5',
                'wunderauto'
            ) ?>
        </i>
    </div>
</template>

<div id="options-data" style="display:none;">
    <?php echo $data !== false ? esc_attr($data) : '' ?>
</div>