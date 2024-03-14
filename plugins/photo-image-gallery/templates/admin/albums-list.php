<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $wpdb;
$uxgallery_nonce_remove_album = wp_create_nonce('uxgallery_nonce_remove_album');
$gallery_wp_nonce_add_album = wp_create_nonce('gallery_wp_nonce_add_album');

?>

<div class="wrap uxgallery_wrap">
    <?php $path_site = plugins_url("../images", __FILE__); ?>
    <div style="clear: both;"></div>
    <div id="poststuff">
        <div id="gallerys-list-page" class="image_gallery_page_heading">
            <h1> <?php echo __('UX Albums', 'photo-gallery-wp'); ?>
                <a
                   class="free_notice_button add-new-h2"><?php echo __('Add New Album', 'photo-gallery-wp'); ?></a>
            </h1>

            <table class="wp-list-table widefat fixed pages" style="width:95%">
                <thead>
                <tr>
                    <th scope="col" id="id" style="width:30px">
                        <span><?php echo __('ID', 'photo-gallery-wp'); ?></span><span
                                class="sorting-indicator"></span></th>
                    <th scope="col" id="name" style="width:85px">
                        <span><?php echo __('Name', 'photo-gallery-wp'); ?></span><span
                                class="sorting-indicator"></span></th>
                    <th scope="col" id="name" style="width:85px">
                        <span><?php echo __('Shortcode', 'photo-gallery-wp'); ?></span><span
                                class="sorting-indicator"></span></th>
                    <th scope="col" id="prod_count" style="width:40px;">
                        <span><?php echo __('Galleries', 'photo-gallery-wp'); ?></span><span
                                class="sorting-indicator"></span></th>
                </tr>
                </thead>
                <tbody>

                <?php
                foreach ($albums as $k => $item):
                    $item_id = intval($item->id);
                    $uxgallery_nonce_remove_album = wp_create_nonce('uxgallery_nonce_remove_album' . $item_id);
                    ?>
                    <tr <?php if ($k % 2 == 0) {
                        echo "class='has-background'";
                    } ?>>
                        <td><?= $k + 1 ?></td>
                        <td>
                            <h3><a href="admin.php?page=galleries_ux_albums&task=edit_cat&id=<?= $item_id ?>"><?= esc_html(stripslashes($item->name)); ?></a></h3>
                            <div class="row-actions">
                                <span class="edit"><a href="admin.php?page=galleries_ux_albums&task=edit_cat&id=<?= $item_id ?>">Edit</a> | </span>
                                <span class="trash"><a href="admin.php?page=galleries_ux_albums&task=remove_album&id=<?= $item_id ?>&uxgallery_nonce_remove_album=<?php echo $uxgallery_nonce_remove_album; ?>" class="submitdelete">Delete</a></span>
                            </div>
                        </td>
                        <td>
                            <div class="shortcode_copy_block album_shortcode" onclick="select()" readonly="readonly"><input value='[uxgallery_album id="<?php echo $item_id; ?>"]'><a href="#"></a> <p class="elemcop">Shortcode Copied</p></div>
                        </td>
                        <td> <div class="gal_num">(<?= $item->galleries_count ?>)</div></td>

                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
