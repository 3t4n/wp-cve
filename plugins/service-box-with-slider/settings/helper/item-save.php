<?php
if (!empty($_POST['rearrange-list-save']) && $_POST['rearrange-list-save'] == 'Save') {
    $nonce = $_REQUEST['_wpnonce'];
    if (!wp_verify_nonce($nonce, 'sbs-6310-nonce-rearrange-list')) {
        die('You do not have sufficient permissions to access this page.');
    } else {
        $itemIds = sanitize_text_field($_POST['rearrange_items_list']) . "||##||" . sanitize_text_field($_POST['order_type']);
        $wpdb->query($wpdb->prepare("UPDATE $style_table SET itemids = %s WHERE id = %d", $itemIds, sanitize_text_field($_POST['rearrange_id'])));
    }
}


if (!empty($_POST['add-edit-item-save']) && $_POST['add-edit-item-save'] == 'Save' && $_POST['styleid'] != '') {
    $nonce = $_REQUEST['_wpnonce'];
    if (!wp_verify_nonce($nonce, 'sbs-6310-nonce-add-edit-item')) {
    die('You do not have sufficient permissions to access this page.');
    } else {
        $id = sanitize_text_field($_POST['styleid']);
        $memberids = array_map('sanitize_text_field', $_POST['item_id']);
        $temp = "";
        if($memberids){
            foreach ($memberids as $memberid){
                if($temp){
                    $temp .= ",";
                }
                $temp .= $memberid;
            }
        }
        $wpdb->query($wpdb->prepare("UPDATE $style_table SET itemids = %s WHERE id = %d", $temp, $id));
    }
}

if (!empty($_POST['desktop_item_per_row_sub']) && $_POST['desktop_item_per_row_sub'] == 'Update') {
    $nonce = $_REQUEST['_wpnonce'];
    if (!wp_verify_nonce($nonce, 'sbs_6310_nonce_field_form')) {
        die('You do not have sufficient permissions to access this page.');
    } else {
        $styledata = $wpdb->get_row($wpdb->prepare("SELECT * FROM $style_table WHERE id = %d ", $styleId), ARRAY_A);
        $item_per_row_data = sanitize_text_field($_POST['desktop_item_per_row_data']);
        $styleid = sanitize_text_field($_POST['id']);        
        $css = $item_per_row_data . "|" . substr($styledata['css'], 2);
        
        $wpdb->update("$style_table", array(
            "css" => $css
                ), array('id' => $styleid), array('%s'), array('%d')
        );

    }
}
