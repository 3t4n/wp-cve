<?php
if (! defined('ABSPATH')) {
    exit;
}
if (! current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}

if (! empty($_POST['delete']) && isset($_POST['id']) && is_numeric($_POST['id'])) {
    $nonce = $_REQUEST['_wpnonce'];
    if (! wp_verify_nonce($nonce, 'tss_nonce_field_delete')) {
        die('You do not have sufficient permissions to access this page.');
    } else {
        $id = (int) sanitize_text_field($_POST['id']);
        $wpdb->query($wpdb->prepare("DELETE FROM {$style_table} WHERE id = %d", $id));
    }
}
if (!empty($_POST['duplicate']) && isset($_POST['id']) && is_numeric($_POST['id'])) {
    $nonce = $_REQUEST['_wpnonce'];
    if (!wp_verify_nonce($nonce, 'sbs_6310_nonce_field_duplicate')) {
      die('You do not have sufficient permissions to access this page.');
    } else {
      $id = (int) $_POST['id'];
      $selectedData = $wpdb->get_row($wpdb->prepare("SELECT * FROM $style_table WHERE id = %d ", $id), ARRAY_A);
      $dupList = array(
              $selectedData['name'], 
              $selectedData['style_name'], 
              $selectedData['css'],           
              $selectedData['itemids']);
      $wpdb->query($wpdb->prepare("INSERT INTO {$style_table} (name, style_name, css,itemids) VALUES ( %s, %s, %s, %s )", $dupList));
    }
  }

?>

<h3> Service Box</h3>
<table class="sbs-6310-table">
   <tr  style="background-color: #f5f5f5">
      <td style="width: 130px">Service Name</td>
      <td style="width: 140px">Template</td>
      <td>Shortcode</td>
      <td style="width: 130px">Manage</td>
   </tr>
   <?php
   $data = $wpdb->get_results('SELECT * FROM '.$style_table.' ORDER BY id DESC', ARRAY_A);
   foreach ($data as $value) {
       $id = $value['id'];
       $temp = substr($value['style_name'], -2);
       if ($temp <= 10) {
           $temp = '01-10';
       } elseif ($temp <= 20) {
           $temp = '11-20';
       } elseif ($temp <= 30) {
           $temp = '21-30';
       } elseif ($temp <= 40) {
           $temp = '31-40';
       } elseif ($temp <= 50) {
           $temp = '41-50';
       } elseif ($temp <= 60) {
           $temp = '51-60';
       }
       $style_name = explode('-', $value['style_name']);

       echo '<tr class="sbs-6310-row-select">';
       echo '<td>'.esc_attr($value['name']).'</td>';
       echo '<td>'.ucfirst(esc_attr($style_name[0])).' '.(int) esc_attr($style_name[1]).'</td>';
       echo '<td><span>Shortcode <input type="text" class="sbs-6310-6330-shortcode" onclick="this.setSelectionRange(0, this.value.length)" value="[sbs_6310_service_box id=&quot;'.esc_attr($id).'&quot;]"></span>';
       echo '<td>
            <a href="'.admin_url("admin.php?page=sbs-6310-template-".esc_attr($temp)."&styleid=".esc_attr($id)).'" title="Edit"  class="sbs-6310-btn-success sbs-6310-margin-right-10 sbs-6310-first"><i class="fas fa-edit" aria-hidden="true"></i></a>
            <form method="post">
            '.wp_nonce_field('sbs_6310_nonce_field_duplicate').'
                  <input type="hidden" name="id" value="'.esc_attr($id).'">
                  <button class="sbs-6310-btn-primary sbs-6310-second"  title="Duplicate"  type="submit" value="duplicate" name="duplicate"onclick="return confirm(\'Do you want to duplicate it?\');"><i class="fas fa-clone" aria-hidden="true"></i></button>
         </form>
            <form method="post">
               '.wp_nonce_field('tss_nonce_field_delete').'
                     <input type="hidden" name="id" value="'.esc_attr($id).'">
                     <button class="sbs-6310-btn-danger sbs-6310-third"  title="Delete"  type="submit" value="delete" name="delete" onclick="return confirm(\'Do you want to delete?\');"><i class="far fa-times-circle" aria-hidden="true"></i></button>
            </form>
            
         </td>';
       echo ' </tr>';
   }
   ?>
</table>