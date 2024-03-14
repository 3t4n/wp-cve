<?php
/**
 * Functions
 *
 * @package     MailerLiteFIRSS\Functions
 * @since       1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get options
 *
 * return array options or empty when not available
 */
function mailerlite_firss_get_options()
{
    return get_option('mailerlite_firss', array());
}

/**
 * Get option default value
 *
 * @param $key
 *
 * @return null|string
 */
function mailerlite_firss_get_option_default_value($key)
{

    switch ($key) {
        case 'image_size':
            $value = 'large';
            break;
        case 'image_alignment':
            $value = 'left-above';
            break;
        default:
            $value = null;
            break;
    }

    return $value;
}

/**
 * Add featured image to content
 *
 * @param $content
 *
 * @return string
 */
function mailerlite_firss_add_image_to_content($content)
{

    global $post;

    if (isset($post->ID) && has_post_thumbnail($post->ID)) {

        $options = mailerlite_firss_get_options();

        $image_size = (!empty($options['image_size'])) ? $options['image_size'] : mailerlite_firss_get_option_default_value('image_size');
        $image_alignment = (!empty($options['image_alignment'])) ? $options['image_alignment'] : mailerlite_firss_get_option_default_value('image_alignment');

        $image_inline_styles = mailerlite_firss_get_image_inline_styles($image_alignment);

        $image = get_the_post_thumbnail($post->ID, $image_size, array(
            'style' => $image_inline_styles,
            'class' => 'webfeedsFeaturedVisual',
        ));

        if (!empty($image) && strpos($content, $image) === false) {

            $content = $image . $content;
        }
    }

    return $content;
}

/**
 * Get image inline styles
 *
 * @param $image_alignment
 *
 * @return string
 */
function mailerlite_firss_get_image_inline_styles($image_alignment)
{

    switch ($image_alignment) {

        case 'left-above':
            $styles = 'display: block; margin-bottom: 10px; clear: both; max-width: 100%;';
            break;
        case 'centered-above':
            $styles = 'display: block; margin: auto; margin-bottom: 10px; max-width: 100%;';
            break;
        case 'left-wrap':
            $styles = 'float: left; margin-right: 10px;';
            break;
        case 'right-wrap':
            $styles = 'float: right; margin-left: 10px;';
            break;
        default:
            $styles = 'display: block; margin-bottom: 10px; clear: both; max-width: 100%;';
            break;
    }

    return $styles;
}