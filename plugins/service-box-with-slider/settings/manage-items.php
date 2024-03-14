<?php
if (!defined('ABSPATH'))
  exit;
?>
<div class="sbs-6310">
  <h1>Service Box Items <button class="sbs-6310-btn-success" id="add-accordion">Add New</button></h1>
  <?php
  $item_table = $wpdb->prefix . 'sbs_6310_item';
  wp_enqueue_media();

 

  if (!empty($_POST['delete']) && isset($_POST['id']) && is_numeric($_POST['id'])) {
    $nonce = $_REQUEST['_wpnonce'];
    if (!wp_verify_nonce($nonce, 'sbs-6310-nonce-field-delete')) {
      die('You do not have sufficient permissions to access this page.');
    } else {
      $id = (int) $_POST['id'];
      $wpdb->query($wpdb->prepare("DELETE FROM {$item_table} WHERE id = %d", $id));
    }
  } else if (!empty($_POST['save']) && $_POST['save'] == 'Save') {
    $nonce = $_REQUEST['_wpnonce'];
    if (!wp_verify_nonce($nonce, 'sbs-6310-nonce-add')) {
      die('You do not have sufficient permissions to access this page.');
    } else {
      $myData = array();
      $myData[] = sanitize_text_field($_POST['title']);
      $myData[] = sanitize_textarea_field($_POST['description']);
      $myData[] = sanitize_text_field($_POST['icontype']);
      $myData[] = sanitize_text_field($_POST['icons']);
      $myData[] = sanitize_text_field($_POST['hovericons']);
      $myData[] = sanitize_text_field($_POST['image']);
      $myData[] = sanitize_text_field($_POST['hoverimage']);
      $myData[] = isset($_POST['target_type']) ? sanitize_text_field($_POST['target_type']) : 0;
      $myData[] = sanitize_text_field($_POST['detailstext']);
      $myData[] = sanitize_text_field($_POST['detailsurl']);

      $wpdb->query($wpdb->prepare(
        "INSERT INTO {$item_table} set
                     title = %s,
                     description = %s,
                     icontype = %d,
                     icons = %s,
                     hovericons = %s,
                     image = %s, 
                     hoverimage = %s,
                     targertype = %d,
                     detailstext = %s,
                     detailsurl = %s",
        $myData
      ));
    }
  } else if (!empty($_POST['duplicate']) && isset($_POST['id']) && is_numeric($_POST['id'])) {
    $nonce = $_REQUEST['_wpnonce'];
    if (!wp_verify_nonce($nonce, 'sbs_6310_nonce_field_duplicate')) {
      die('You do not have sufficient permissions to access this page.');
    } else {
      $id = (int) $_POST['id'];
      $selectedData = $wpdb->get_row($wpdb->prepare("SELECT * FROM $item_table WHERE id = %d ", $id), ARRAY_A);
      $dupList = array(
              $selectedData['title'], 
              $selectedData['description'], 
              $selectedData['icontype'], 
              $selectedData['icons'], 
              $selectedData['hovericons'], 
              $selectedData['image'], 
              $selectedData['hoverimage'], 
              $selectedData['targertype'], 
              $selectedData['detailstext'],          
              $selectedData['detailsurl']);
      $wpdb->query($wpdb->prepare("INSERT INTO {$item_table}  (title, description, icontype, icons, hovericons, image, hoverimage, targertype, detailstext, detailsurl) VALUES (%s, %s, %d, %s, %s, %s, %s, %d, %s, %s)", $dupList));
    }
  } else if (!empty($_POST['update']) && $_POST['update'] == 'Update') {
    $nonce = $_REQUEST['_wpnonce'];
    if (!wp_verify_nonce($nonce, 'sbs-6310-nonce-item-update')) {
      die('You do not have sufficient permissions to access this page.dddd');
    } else {
      $id = (int) sanitize_text_field($_POST['eid']);
      $myData = array();
      $myData[] = sanitize_text_field($_POST['title']);
      $myData[] = sanitize_textarea_field($_POST['description']);
      $myData[] = sanitize_text_field($_POST['icontype']);
      $myData[] = sanitize_text_field($_POST['icons']);
      $myData[] = sanitize_text_field($_POST['hovericons']);
      $myData[] = sanitize_text_field($_POST['image']);
      $myData[] = sanitize_text_field($_POST['hoverimage']);
      $myData[] = isset($_POST['target_type']) ? sanitize_text_field($_POST['target_type']) : 0;
      $myData[] = sanitize_text_field($_POST['detailstext']);
      $myData[] = sanitize_text_field($_POST['detailsurl']);
      $myData[] = $id;

      $wpdb->query($wpdb->prepare("UPDATE {$item_table} set
                     title = %s,
                     description = %s,
                     icontype = %d,
                     icons = %s,
                     hovericons = %s,
                     image = %s, 
                     hoverimage = %s,
                     targertype = %d,
                     detailstext = %s,
                     detailsurl = %s
                     where id = %d", $myData));
    }
  } else if (!empty($_POST['edit']) && $_POST['edit'] == 'Edit') {
    $nonce = $_REQUEST['_wpnonce'];
    if (!wp_verify_nonce($nonce, 'sbs-6310-nonce-field-edit')) {
      die('You do not have sufficient permissions to access this page.');
    } else {
      $id = (int) $_POST['id'];
      $itemList = $wpdb->get_row($wpdb->prepare("SELECT * FROM $item_table WHERE id = %d ", $id), ARRAY_A);
  ?>
      <div id="sbs-6310-modal-edit-item" class="sbs-6310-modal sbs-6310-display-none">
        <div class="sbs-6310-modal-content sbs-6310-modal-md">
          <form action="" method="post">
            <?php wp_nonce_field("sbs-6310-nonce-item-update") ?>
            <input type="hidden" name="eid" value="<?php echo esc_attr($id); ?>" />
            <div class="sbs-6310-modal-header">
              Edit Items
              <span class="sbs-6310-close">&times;</span>
            </div>
            <div class="sbs-6310-modal-body-form">
              <table border="0" width="100%" cellpadding="10" cellspacing="0">
                <tr>
                  <td class='sbs-6310-width-150'><label class="sbs-6310-form-label" for="title">Title:</label></td>
                  <td><input type="text" name="title" id="title" value="<?php echo sbs_6310_replace(esc_attr($itemList['title'])) ?>" class="sbs-6310-form-input lg" placeholder="Item Title" /></td>
                </tr>
                <tr>
                  <td><label class="sbs-6310-form-label" for="description">Description:</label></td>
                  <td>
                    <textarea name="description" id="description" class="sbs-6310-form-input lg" placeholder="Item Description"><?php echo sbs_6310_replace(esc_attr($itemList['description'])); ?></textarea>
                  </td>
                </tr>
                <tr>
                  <td class='sbs-6310-width-150'><label class="sbs-6310-form-label" for="icons">Icons Type:</label></td>
                  <td>
                    <input type="radio" name='icontype' class='icontype_new' value='1' <?php echo ($itemList['icontype'] == 1) ? 'checked':'' ?> /> Font Awesome Icon
                    <input type="radio" name='icontype' class="icontype_new" value='0' <?php echo ($itemList['icontype'] == 0) ? 'checked':'' ?> /> Custom Icon <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
                  </td>
                </tr>
                <tr height="40px" class="sbs-6310-form-icon font-awesome-icon-new">
                  <td class='sbs-6310-width-150'><label class="sbs-6310-form-label" for="icons">Icons:</label></td>
                  <td>
                    <input type="text" name="icons" id="icons-edit-1" class="sbs-6310-form-input lg" placeholder="fas fa-globe" readonly="" value="<?php echo esc_attr($itemList['icons']) ?>">
                    <div class="sbs-6310-plus-icons" data-id='icons-edit-1'><i class="fas fa-plus-circle"></i></div>
                  </td>
                </tr>
                <tr height="40px" class="sbs-6310-form-icon font-awesome-icon-new">
                  <td class='sbs-6310-width-150'><label class="sbs-6310-form-label" for="icons">Hover Icons:</label></td>
                  <td>
                    <input type="text" name="hovericons" id="icons-edit-2" class="sbs-6310-form-input lg" placeholder="fas fa-globe" readonly="" value="<?php echo esc_attr($itemList['hovericons']) ?>">
                    <div class="sbs-6310-plus-icons" data-id='icons-edit-2'><i class="fas fa-plus-circle"></i></div>
                  </td>
                </tr>
                <tr class="custom-icon-new">
                  <td class='sbs-6310-width-150'><label class="sbs-6310-form-label" for="picture">Custom Icon/Image:</label></td>
                  <td>
                    <input type="text" name="image" id="image-edit-1" class="sbs-6310-form-input" value="<?php echo esc_url($itemList['image']) ?>">
                    <input type="button" value="Upload Custom Icon/Image" class="sbs-6310-btn-default sbs-6310-icon-upload custom-icon-image" data-id="image-edit-1">
                  </td>
                </tr>
                <tr class="custom-icon-new">
                  <td class='sbs-6310-width-150'><label class="sbs-6310-form-label" for="picture">Custom Hover Icon/Image:</label></td>
                  <td>
                    <input type="text" name="hoverimage" id="image-edit-2" class="sbs-6310-form-input" value="<?php echo esc_url($itemList['hoverimage']) ?>">
                    <input type="button" value="Upload Custom Hover Icon/Image" class="sbs-6310-btn-default sbs-6310-icon-upload custom-icon-image" data-id="image-edit-2">
                  </td>
                </tr>
                <tr>
                  <td class='sbs-6310-width-150'><label class="sbs-6310-form-label" for="readmore">Open in new tab:</label></td>
                  <td>
                    <label class="switch">
                      <input type="checkbox" name="target_type" value="1" class="sbs-6310-form-input" <?php echo isset($itemList['targertype']) ? 'checked' : ''; ?>>
                      <span class="slider round button_icon_show_hide"></span>
                    </label>
                    <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
                  </td>
                </tr>
                <tr>
                  <td class='sbs-6310-width-150'><label class="sbs-6310-form-label" for="readmore">Button Text:</label>
                    <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
                  </td>
                  <td><input type="text" name="detailstext" id="readmore" class="sbs-6310-form-input lg" placeholder="Read More" value="<?php echo sbs_6310_replace(esc_attr($itemList['detailstext'])) ?>"></td>
                </tr>
                <tr>
                  <td class='sbs-6310-width-150'><label class="sbs-6310-form-label" for="readmoreurl">Button URL:</label>
                  <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
                </td>
                  <td><input type="text" name="detailsurl" id="readmoreurl" class="sbs-6310-form-input lg" placeholder="https://www.wpmart.org" value="<?php echo esc_url($itemList['detailsurl']) ?>"></td>
                </tr>
              </table>
            </div>

            <div class="sbs-6310-modal-form-footer">
              <button type="button" name="close" id="sbs-6310-from-close-edit" class="sbs-6310-btn-danger sbs-6310-pull-right">Close</button>
              <input type="submit" name="update" class="sbs-6310-btn-primary sbs-6310-pull-right sbs-6310-margin-right-10" value="Update" />
            </div>
            <br class="sbs-6310-clear" />
          </form>
        </div>
        <br class="sbs-6310-clear" />
      </div>
      <?php
        //Open modal script
        $editScript = "
          jQuery(document).ready(function () { 
            jQuery('#sbs-6310-modal-edit-item').fadeIn(500);
            jQuery('body').css({
              'overflow': 'hidden'
            });
            if(parseInt(jQuery('.icontype_new:checked').val()) == 0){
              jQuery('#sbs-6310-modal-edit-item .custom-icon-new').show();
              jQuery('#sbs-6310-modal-edit-item .font-awesome-icon-new').hide();
            }
          });
        ";
        wp_register_script( "sbs-6310-template-edit-item", "" );
        wp_enqueue_script( "sbs-6310-template-edit-item" );
        wp_add_inline_script( "sbs-6310-template-edit-item", $editScript );
      ?>
    <?php
    }
  } 
  ?>

  <table class="sbs-6310-item-table">
    <tr>
      <th class="sbs-6310-width-125">Title</th>
      <th>Description</th>
      <th class="sbs-6310-width-80">Icon</th>
      <th class="sbs-6310-width-80">Hover Icon</th>
      <th class="sbs-6310-width-100">Button Text</th>
      <th class="sbs-6310-width-100">Edit Delete</th>
    </tr>
    <?php
    $data = $wpdb->get_results('SELECT * FROM ' . $item_table . ' ORDER BY id DESC', ARRAY_A);
    foreach ($data as $value) {
      echo '<tr>';
      echo '<td>' . sbs_6310_replace($value['title']) . '</td>';
      echo '<td>' . sbs_6310_replace($value['description']) . '</td>';
      if($value['icontype']) {
        echo '<td align="center">' . (($value['icons']) ? "<i class='{$value['icons']} sbs-6310-font-size-20'></i>" : '---') . '</td>';
        echo '<td align="center">' . (($value['hovericons']) ? "<i class='{$value['hovericons']} sbs-6310-font-size-20'></i>" : '---') . '</td>';
      } else{
        echo '<td align="center">' . (($value['image']) ? "<img src='{$value['image']}' class='sbs-6310-max-width-30' />" : '---') . '</td>';
        echo '<td align="center">' . (($value['hoverimage']) ? "<img src='{$value['hoverimage']}' class='sbs-6310-max-width-30' />" : '---') . '</td>';
      }
      
      echo '<td align="center">' . sbs_6310_replace($value['detailstext']) . '</td>';
      echo '<td>
                 <form method="post">
                   ' . wp_nonce_field("sbs-6310-nonce-field-edit") . '
                          <input type="hidden" name="id" value="' . $value['id'] . '">
                          <button class="sbs-6310-btn-success sbs-6310-action-button" title="Edit"  type="submit" value="Edit" name="edit"><i class="fas fa-edit" aria-hidden="true"></i></button>
                  </form>
                  <form method="post">
                  '.wp_nonce_field('sbs_6310_nonce_field_duplicate').'
                        <input type="hidden" name="id" value="'.$value['id'].'">
                        <button class="sbs-6310-btn-primary sbs-6310-second"  title="Duplicate"  type="submit" value="duplicate" name="duplicate"onclick="return confirm(\'Do you want to duplicate it?\');"><i class="fas fa-clone" aria-hidden="true"></i></button>
               </form>
                  <form method="post">
                   ' . wp_nonce_field("sbs-6310-nonce-field-delete") . '
                          <input type="hidden" name="id" value="' . $value['id'] . '">
                          <button class="sbs-6310-btn-danger sbs-6310-action-button sbs-6310-no-margin" title="Delete"  type="submit" value="delete" name="delete" onclick="return confirm(\'Do you want to delete?\');"><i class="far fa-times-circle" aria-hidden="true"></i></button>
                  </form>
            </td>';
    }
    ?>
  </table>
  <div id="sbs-6310-modal-add-item" class="sbs-6310-modal sbs-6310-display-none">
    <div class="sbs-6310-modal-content sbs-6310-modal-md">
      <form action="" method="post">
        <div class="sbs-6310-modal-header">
          Add Items
          <span class="sbs-6310-close">&times;</span>
        </div>
        <div class="sbs-6310-modal-body-form">
          <?php wp_nonce_field("sbs-6310-nonce-add") ?>
          <table border="0" width="100%" cellpadding="10" cellspacing="0">
            <tr>
              <td class='sbs-6310-width-150'><label class="sbs-6310-form-label" for="title">Title:</label></td>
              <td><input type="text" name="title" id="title" value="" class="sbs-6310-form-input lg" placeholder="Item Title" /></td>
            </tr>
            <tr>
              <td><label class="sbs-6310-form-label" for="description">Description:</label></td>
              <td>
                <textarea name="description" id="description" class="sbs-6310-form-input lg" placeholder="Item Description"></textarea>
              </td>
            </tr>
            <tr>
              <td class='sbs-6310-width-150'><label class="sbs-6310-form-label" for="icons">Icons Type:</label></td>
              <td>
                <input type="radio" name='icontype' class='icontype_new' value='1' checked /> Font Awesome Icon
                <input type="radio" name='icontype' class="icontype_new" value='0' /> Custom Icon <span class="sbs-6310-pro">(Pro)<div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
              </td>
            </tr>
            <tr height="40px" class="sbs-6310-form-icon font-awesome-icon-new">
              <td class='sbs-6310-width-150'><label class="sbs-6310-form-label" for="icons">Icons:</label></td>
              <td>
                <input type="text" name="icons" id="icons-1" class="sbs-6310-form-input lg" placeholder="fas fa-globe" readonly="">
                <div class="sbs-6310-plus-icons" data-id='icons-1'><i class="fas fa-plus-circle"></i></div>
              </td>
            </tr>
            <tr height="40px" class="sbs-6310-form-icon font-awesome-icon-new">
              <td class='sbs-6310-width-150'><label class="sbs-6310-form-label" for="icons">Hover Icons:</label></td>
              <td>
                <input type="text" name="hovericons" id="icons-2" class="sbs-6310-form-input lg" placeholder="fas fa-globe" readonly="">
                <div class="sbs-6310-plus-icons" data-id='icons-2'><i class="fas fa-plus-circle"></i></div>
              </td>
            </tr>
            <tr class="custom-icon-new">
              <td class='sbs-6310-width-150'><label class="sbs-6310-form-label" for="picture">Custom Icon/Image:</label></td>
              <td>
                <input type="text" name="image" id="image-1" class="sbs-6310-form-input">
                <input type="button" value="Upload Custom Icon/Image" class="sbs-6310-btn-default sbs-6310-icon-upload custom-icon-image" data-id="image-1">
              </td>
            </tr>
            <tr class="custom-icon-new">
              <td class='sbs-6310-width-150'><label class="sbs-6310-form-label" for="picture">Custom Hover Icon/Image:</label></td>
              <td>
                <input type="text" name="hoverimage" id="image-2" class="sbs-6310-form-input">
                <input type="button" value="Upload Custom Hover Icon/Image" class="sbs-6310-btn-default sbs-6310-icon-upload custom-icon-image" data-id="image-2">
              </td>
            </tr>
            <tr>
              <td class='sbs-6310-width-150'>
                <label class="sbs-6310-form-label" for="readmore">Open in new tab:</label>                
              </td>
              <td>
                <label class="switch">
                  <input type="checkbox" name="target_type" value="1" class="sbs-6310-form-input">
                  <span class="slider round button_icon_show_hide"></span>
                </label>
                <span class="sbs-6310-pro">(Pro)<div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
              </td>
            </tr>
            <tr>
              <td class='sbs-6310-width-150'><label class="sbs-6310-form-label" for="readmore">Button Text:</label><span class="sbs-6310-pro">(Pro)<div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span></td>
              <td><input type="text" name="detailstext" id="readmore" class="sbs-6310-form-input lg" placeholder="Read More"></td>
            </tr>
            <tr>
              <td class='sbs-6310-width-150'><label class="sbs-6310-form-label" for="readmoreurl">Button URL:</label><span class="sbs-6310-pro">(Pro)<div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span></td>
              <td><input type="text" name="detailsurl" id="readmoreurl" class="sbs-6310-form-input lg" placeholder="https://www.wpmart.org"></td>
            </tr>
          </table>
        </div>
        <div class="sbs-6310-modal-form-footer">
          <button type="button" name="close" id="sbs-6310-from-close" class="sbs-6310-btn-danger sbs-6310-pull-right">Close</button>
          <input type="submit" name="save" class="sbs-6310-btn-primary sbs-6310-pull-right sbs-6310-margin-right-10" value="Save" />
        </div>
        <br class="sbs-6310-clear" />
      </form>
    </div>
    <br class="sbs-6310-clear" />
  </div>
</div>

<!-- Icon Modal Start -->
<div id="sbs_6310_social_icon" class="sbs-6310-modal sbs-6310-display-none">
  <div class="sbs-6310-modal-content sbs-6310-modal-xl">
    <form action="" method="post">
      <?php wp_nonce_field("sbs-6310-nonce-update-icon-order") ?>
      <input type="hidden" name="rearrange_list" id="rearrange_list" value="" />
      <div class="sbs-6310-modal-header">
        <span class="sbs-6310-action-button">Choose your Icon</span>
        <input type="text" id="icon-filter" class="sbs-6310-form-input" placeholder="Search Icon" />
        <span class="sbs-6310-font-awesome-close">&times;</span>
      </div>
      <div class="sbs-6310-modal-body-form">
        <ul class="sbs-6310-choose-icon">
          <?php echo sbs_6310_fa_icon_list('li', '</li>'); ?>
        </ul>
      </div>
      <div class="sbs-6310-modal-form-footer">
        <button type="button" name="close" id="sbs-6310-font-icon-close" class="sbs-6310-btn-danger sbs-6310-pull-right">Close</button>
      </div>
    </form>
    <br class="sbs-6310-clear" />
  </div>
</div>
