<?php
/**
 * Plugin Name: PDF Thumbnails
 * Description: Generates thumbnail for PDF-files when they are added.
 * Version: 2.2.0
 * Author: Stian Liknes
 * License: GPLv3
 */

require_once dirname(__FILE__). '/PdfThumbnailsPlugin.php';

add_action('admin_init', 'pdf_thumbnails_admin_int');
add_action('init', 'pdf_thumbnails_init');

function pdf_thumbnails_init()
{
    add_shortcode('pdf_thumbnails_link', 'pdf_thumbnails_link_shortcode');    
}

function pdf_thumbnails_admin_int()
{
    if (!extension_loaded('imagick')) {
        add_action('admin_notices', 'pdf_thumbnails_missing_imagick');
        return;
    }
    add_filter('wp_generate_attachment_metadata', 'pdf_thumbnails_generate_attachment_metadata', 10, 2);
    add_action('deleted_post', 'pdf_thumbnails_deleted_post');
}

   
function pdf_thumbnails_generate_attachment_metadata($metadata, $attachment_id)
{
    if (get_post_mime_type($attachment_id) === 'application/pdf') {
        PdfThumbnailsPlugin::instance()->regenerateThumbnail($attachment_id);
    }
    return $metadata;
}

function pdf_thumbnails_deleted_post($post_id)
{
    if (!$post_id) {
        return;
    }
    
    $attachments = get_posts(array(
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'post_parent' => (int) $post_id,
        'meta_key' => PdfThumbnailsPlugin::META_KEY
    ));
    
    foreach ($attachments as $attachment) {
        wp_delete_post($attachment->ID);
    }
}

function pdf_thumbnails_missing_imagick()
{
    $message = sprintf(
        __('The <a href="%s">ImageMagick</a> extension must be loaded to generate PDF thumbnails.'),
        esc_url('http://php.net/manual/book.imagick.php')
    );
    ?>
    <div class="error">
        <p><?php echo $message; ?></p>
    </div>
    <?php
}

function pdf_thumbnails_link_shortcode($atts, $content = null)
{
    $params = shortcode_atts(array(
        'id' => 0,
        'size' => 'post-thumbnail',
        'target' => null,
        'title' => null,
        'download' => false
    ), $atts);
    $attachmentId = (int) $params['id'];
    $size = sanitize_key($params['size']);
    $image = get_the_post_thumbnail($attachmentId, $size);
    $htmlAttributes = pdf_thumbnails_html_attributes_to_string(array(
        'href' => wp_get_attachment_url($attachmentId),
        'target' => $params['target'],
        'title' => $params['title'],
        'download' => $params['download']
    ));
    return apply_filters(
        'pdf_thumbnails_link_shortcode',
        "<a $htmlAttributes>$image</a>",
        $attachmentId,
        $size,
        $atts,
        $content
    );
}

function pdf_thumbnails_html_attributes_to_string($htmlAttributesArr) {
    $attributes = array();
    foreach ($htmlAttributesArr as $key => $value) {
        if ($value) {
            $attributes[] = "$key=\"" . esc_attr($value) . '"';
        }
    }
    return implode(' ', $attributes);
}
