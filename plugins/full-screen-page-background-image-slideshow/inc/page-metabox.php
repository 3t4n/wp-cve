<?php

/*
 * Created on Feb 10, 2013
 * Author: Mohsin Rasool
 * 
 * Copyright 2013 NeuMarkets. All rights reserved.
 * COMPANY PROPRIETARY/CONFIDENTIAL. Use is subject to license terms.
 */

define('WPDS_NUM_ADD_IMAGES',6);
function wpds_media_meta_box() {
    add_meta_box('fsi_bg_images', 'Full Screen Backgroud Images', 'fsi_bg_images_cb', 'page', 'normal');
}
add_action('add_meta_boxes', 'wpds_media_meta_box');


function fsi_bg_images_cb($post) {
    
    echo '<table>
                <tr><td colspan="2"><label><input id="fsi_fs_images" name="fsi_fs_images" type="checkbox" value="1" '.checked(get_post_meta($post->ID,'fsi_fs_images',true),'1',false).' /> Enable custom background image for this page</label></td>
                    <td colspan="2"><label><input id="fsi_fs_images_slideshow" name="fsi_fs_images_slideshow"  type="checkbox" value="1" '.checked(get_post_meta($post->ID,'fsi_fs_images_slideshow',true),'1',false).' /> Enable slideshow</label></td>
                </tr>
        ';
    for($i=0; $i<WPDS_NUM_ADD_IMAGES; $i++){
        echo ($i%2==0) ? '<tr valign="top">':'';
        echo '<td valign=top width="40%"><input id="fsi_fs_images'.$i.'_field" type="text" size="36" name="fsi_fs_images'.$i.'" value="'.get_post_meta($post->ID,'fsi_fs_images'.$i,true).'" />
        <input id="fsi_fs_images'.$i.'" class="upload_buttons" type="button" value="Upload/Select" /></td><td width="10%">';
        if(has_fsi_bg_image($post->ID,$i) )
                echo '<a href="'.the_fsi_bg_image_url($post->ID,$i).' target="_blank"><img src="'.the_fsi_bg_image_url($post->ID,$i,array(30,30)).'" height=30 /></a>';
        echo '</td>';
        echo (($i+1)%2==0) ? '</tr>':'';
    }
    echo '
        <tr><td colspan="4">You can specify an image URL or an attachment ID through Upload/Select button. </td></tr>
    </table>';
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            var formfieldID = '';
            var wpds_orig_send_to_editor = window.send_to_editor;
            jQuery('.upload_buttons').click(function() {
                formfieldID = jQuery(this).attr('id')+'_field';
                tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
                
                window.send_to_editor = function(html) {
                    attachmentID = html.match(/wp-image-([0-9]+)/);
                    if(attachmentID)
                        pasteValue = attachmentID[1];
                    else
                        pasteValue = jQuery(html).filter('img').attr('src');

                    jQuery('#'+formfieldID).val(pasteValue);
                    tb_remove();
                    window.send_to_editor = wpds_orig_send_to_editor;
                }
                return false;
            });

        });
    </script>
    <?php
}

// end post_media

function wpds_save_media_mb($post_id) {

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    } // end if

    if (!current_user_can('edit_post', $post_id)) {
        return;
    } // end if

    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
        return;
    }

    $post_id = $_POST['ID'];
    for($i=0; $i<WPDS_NUM_ADD_IMAGES; $i++){
        if(isset($_POST['fsi_fs_images'.$i]))
            update_post_meta($post_id, 'fsi_fs_images'.$i, $_POST['fsi_fs_images'.$i]);
    }

    update_post_meta($post_id, 'fsi_fs_images', $_POST['fsi_fs_images']);
    update_post_meta($post_id, 'fsi_fs_images_slideshow', $_POST['fsi_fs_images_slideshow']);
}
add_action('save_post','wpds_save_media_mb');

function has_fsi_bg_image($post_id, $id=1) {
   
    $meta = get_post_meta($post_id,'fsi_fs_images'.$id, true);
    if(empty($meta))    return false;

    return true;
}

function the_fsi_bg_image_url($post_id, $id=1, $size='full') {
    $meta = get_post_meta($post_id,'fsi_fs_images'.$id, true);
    if(empty($meta))    return false;
    if(is_numeric($meta)){
        $image = wp_get_attachment_image_src($meta, $size);
        if(!empty($image))
            return $image[0];
        return false;
    }
    else{
        return $meta;
    }
}

?>