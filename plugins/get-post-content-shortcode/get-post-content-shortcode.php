<?php
/*
Plugin Name: Get Post Content Shortcode
Plugin Group: Shortcodes
Plugin URI: http://phplug.in/
Description: This plugin provides a shortcode to get the content of a post based on ID number.
Version: 0.4.0
Author: Eric King
Author URI: http://webdeveric.com/
*/

if (! function_exists('is_yes')) {
    function is_yes($arg)
    {
        if (is_string($arg)) {
            $arg = strtolower($arg);
        }
        return in_array($arg, array( true, 'true', 'yes', 'y', '1', 1 ), true);
    }
}

if (! function_exists('split_comma')) {
    function split_comma($csv)
    {
        return array_map('trim', explode(',', $csv));
    }
}

function wde_post_content_status($status = '', $default_status = 'publish')
{
    $valid_fields = array_intersect(split_comma($status), get_post_stati());

    if (empty($valid_fields)) {
        $valid_fields[] = $default_status;
    }

    return $valid_fields;
}

function wde_post_content_field($field)
{
    $allowed_fields = apply_filters(
        'post-content-allowed-fields',
        array(
            'post_author',
            'post_date',
            'post_date_gmt',
            'post_content',
            'post_title',
            'post_excerpt',
            'post_status',
            'comment_status',
            'ping_status',
            'post_name',
            'to_ping',
            'pinged',
            'post_modified',
            'post_modified_gmt',
            'post_content_filtered',
            'post_parent',
            'guid',
            'menu_order',
            'post_type',
            'post_mime_type',
            'comment_count'
        )
    );

    foreach (array( $field, 'post_' . $field ) as $field_name) {
        if (in_array($field_name, $allowed_fields)) {
            return $field_name;
        }
    }

    return false;
}

function wde_get_post_content_shortcode_attributes($attributes = array())
{
    $default_attributes = array(
        'id'        => 0,
        'autop'     => true,
        'shortcode' => true,
        'field'     => 'post_content',
        'status'    => 'publish'
    );

    $attributes = shortcode_atts(
        array_merge(
            $default_attributes,
            apply_filters('post-content-default-attributes', $default_attributes)
        ),
        $attributes,
        'post-content'
    );

    return array(
        'id'        => (int)$attributes['id'],
        'autop'     => is_yes($attributes['autop']),
        'shortcode' => is_yes($attributes['shortcode']),
        'field'     => wde_post_content_field($attributes['field']),
        'status'    => wde_post_content_status($attributes['status'])
    );
}

function wde_get_post_content_shortcode($attributes, $shortcode_content = null, $code = '')
{
    global $post;

    $content = '';
    $attributes = wde_get_post_content_shortcode_attributes($attributes);

    if ($attributes['field'] && get_the_ID() !== $attributes['id'] && in_array(get_post_status($attributes['id']), $attributes['status'])) {
        $original_post = $post;

        $post = get_post($attributes['id']);

        if (is_a($post, 'WP_Post')) {
            $content = get_post_field($attributes['field'], $post->ID);

            if (! empty($content)) {
                if ($attributes['shortcode']) {
                    $content = do_shortcode($content);
                }

                if ($attributes['autop']) {
                    $content = wpautop($content);
                }
            }
        }

        $post = $original_post;
    }

    return $content;
}

add_shortcode('post-content', 'wde_get_post_content_shortcode');
