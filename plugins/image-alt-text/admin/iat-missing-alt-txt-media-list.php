<?php
/* get all media post_type is attachment */
$posts_args = array(
    'post_type' => 'attachment',
    'numberposts' => -1
);
$posts = get_posts($posts_args);

$missing_alt_media_list_array = array();
$post_alt = '';

/* get site url */
$site_url = site_url();
$url = '';

if (isset($posts) && !empty($posts)) {
    foreach ($posts as $post) {
        if ($post->ID) {
            $post_id = $post->ID;
            $post_mime_type = sanitize_mime_type($post->post_mime_type);
            $post_title = $post->post_title;
            $url = wp_get_original_image_url($post_id);
            $post_date = date("F j, Y, g:i a", strtotime($post->post_date));
            if (str_contains($post_mime_type, 'image')) {
                $post_alt = get_post_meta($post_id, '_wp_attachment_image_alt', true);
                if ($post_alt == '') {
                    $missing_alt_media_list_array[] = array(
                        'post_id' => $post_id,
                        'post_image' => $url,
                        'post_title' => $post_title,
                        'post_url' => $url,
                        'post_date' => $post_date
                    );
                }
            }
        }
    }
}
?>
<div class="heading">
    <img src="<?php echo IAT_FILE_URL . "assets/images/image-alt-text-logo.png" ?>" alt="Image Alt Text">
</div>
<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <a class="nav-item nav-link active" id="existing-alt-text-media" data-bs-toggle="tab" href="#existing-media" role="tab" aria-controls="existing-media" aria-selected="true"><?php _e('Media with Alt', IMAGE_ALT_TEXT); ?></a>
        <a class="nav-item nav-link" id="missing-alt-text-media" data-bs-toggle="tab" href="#missing-media" role="tab" aria-controls="missing-media" aria-selected="false"><?php _e('Media without Alt', IMAGE_ALT_TEXT); ?></a>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="existing-media" role="tabpanel" aria-labelledby="existing-media">
        <div class="container-fluid">
            <div class="wrap">
                <div class="img-alt-table">
                    <table class="table table-bordered table-sm" id="ex-list-table">
                        <thead>
                            <tr>
                                <th><?php _e('Image', IMAGE_ALT_TEXT); ?></th>
                                <th><?php _e('Name', IMAGE_ALT_TEXT); ?></th>
                                <th><?php _e('URL', IMAGE_ALT_TEXT); ?></th>
                                <th><?php _e('Created date', IMAGE_ALT_TEXT); ?></th>
                                <th><?php _e('Add alt text', IMAGE_ALT_TEXT); ?></th>
                                <th><?php _e('Action', IMAGE_ALT_TEXT); ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="missing-media" role="tabpanel" aria-labelledby="missing-media">
        <div class="container-fluid">
            <div class="wrap">
                <?php if (!empty($missing_alt_media_list_array)) { ?>
                    <div class="copy-form-div">
                        <form id="all-name-copy-text-to-alt-form">
                            <input type="hidden" name="action" value="iat_copy_all_name_to_alt_action">
                            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('iat_copy_all_name_to_alt_nonce'); ?>">
                            <input type="hidden" name="ajax_call" id="ajax_call" value="1" />
                            <button type="submit" id="copy-name-tp-alt-txt-btn" class="btn btn-secondary tooltip-1">
                                <span class="tooltiptext">
                                    <?php _e('This button will copy post name to image alt text for all missing alt text media files.', IMAGE_ALT_TEXT); ?>
                                </span>
                                <i class="loader copy-name-loader" style="display:none;"></i>
                                &nbsp;<?php _e('Bulk Alt Text', IMAGE_ALT_TEXT); ?>
                            </button>
                        </form>
                    </div>
                <?php } ?>
                <table class="table table-bordered table-sm" id="list-table">
                    <thead>
                        <tr>
                            <th><?php _e('Image', IMAGE_ALT_TEXT); ?></th>
                            <th><?php _e('Name', IMAGE_ALT_TEXT); ?></th>
                            <th><?php _e('URL', IMAGE_ALT_TEXT); ?></th>
                            <th><?php _e('Created date', IMAGE_ALT_TEXT); ?></th>
                            <th><?php _e('Add alt text', IMAGE_ALT_TEXT); ?></th>
                            <th><?php _e('Action', IMAGE_ALT_TEXT); ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>