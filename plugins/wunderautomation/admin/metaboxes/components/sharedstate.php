<?php

use WunderAuto\Types\Internal\WorkflowState;

if (!defined('ABSPATH')) {
    exit;
}

$wunderAuto = wa_wa();
$data       = (object)[];

/*
 * Pro status
 */
$data->isPro = $wunderAuto->isPro();

/*
 * Our own object types
 */
$data->objectTypes = $wunderAuto->getObjectTypes();

/*
 * Post types
 */
$data->postTypes = [];
$types           = get_post_types([], 'objects');
foreach ($types as $key => $type) {
    if (!($type instanceof WP_Post_Type)) {
        continue;
    }
    $data->postTypes[] = [
        'value' => $key,
        'label' => $type->label . " ({$type->name})",
    ];
}

/*
 * Post statuses
 */
$wp_post_statuses = wa_get_wp_post_statuses();

$data->postStatuses = [];
foreach ((array)$wp_post_statuses as $key => $status) {
    $data->postStatuses[] = [
        'value' => $key,
        'label' => $status->label . " ($key)",
    ];
}

/**
 * User roles
 */
$wp_roles        = wp_roles();
$data->userRoles = [];
foreach ((array)$wp_roles->roles as $key => $role) {
    $data->userRoles[] = [
        'value' => $key,
        'label' => $role['name'],
    ];
}

/**
 * Taxonomies
 */
$wp_taxonomies    = wa_get_taxonomies();
$data->taxonomies = [];
foreach ((array)$wp_taxonomies as $taxonomy) {
    $data->taxonomies[] = [
        'value' => $taxonomy->name,
        'label' => $taxonomy->label,
        'type'  => $taxonomy->object_type,
    ];
}

if (class_exists('WooCommerce')) {
    /*
     * Existing coupons
     */
    $data->shopCoupons = [];

    $args    = [
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'asc',
        'post_type'      => 'shop_coupon',
        'post_status'    => 'publish',
        'meta_query'     => [
            [
                'key'     => '_wa_generated',
                'compare' => 'NOT EXISTS',
            ],
        ],
    ];
    $coupons = get_posts($args);
    foreach ($coupons as $coupon) {
        if (!($coupon instanceof WP_Post)) {
            continue;
        }
        $data->shopCoupons[] = [
            'value' => $coupon->ID,
            'label' => $coupon->post_title,
        ];
    }
}

/*
 * ACF fields
 */
if (class_exists('ACF')) {
    $data->acfFields = [];
    $groups          = acf_get_field_groups();
    foreach ($groups as $group) {
        $title                   = $group['title'];
        $data->acfFields[$title] = [];
        $fields                  = acf_get_fields($group['key']);
        foreach ($fields as $field) {
            $data->acfFields[$title][] = [
                'value' => $field['key'],
                'label' => $field['label']
            ];
        }
    }
}

/*
 * Workflows
 */
$data->workflows = [];
$workflows       = $wunderAuto->getWorkflows();
foreach ($workflows as $workflow) {
    $id = $workflow->getPostId();

    /** @var WorkflowState $state */  // phpcs:ignore
    $state             = $workflow->getState();
    $data->workflows[] = [
        'value'   => $id,
        'label'   => $state->name . "(#$id)",
        'trigger' => $state->trigger->trigger,
    ];
}

$data = json_encode($data);
?>

<div id="shared-data" style="display:none;">
    <?php echo $data !== false ? esc_attr($data) : '' ?>
</div>