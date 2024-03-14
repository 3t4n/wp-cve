<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<a href="<?php _e(PDCKL_ADMIN_LINK); ?>&amp;s=orders&amp;a=add" style="margin-top:20px; display:inline;"><input type="button" class="button-primary" value="<?php echo $pdckl_lang['btn_add_link']; ?>"></a>

<?php if(($_GET['a'] ?? '') == 'add') { ?>
  <form name="pdckl_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <table class="wp-list-table widefat" style="margin-top:10px;">
      <thead>
        <tr>
          <td colspan="3"><h3><?php echo $pdckl_lang['orders_add_title']; ?></h3></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo $pdckl_lang['orders_add_idp']; ?></td>
          <td><input type="text" name="pdckl_link_pid" class="regular-text"> <?php echo pdckl_show_help('h_add_link_clid'); ?></td>
        </tr>
        <tr>
          <td><?php echo $pdckl_lang['orders_add_link']; ?></td>
          <td><input type="text" name="pdckl_link" class="regular-text"> <?php echo pdckl_show_help('h_add_link_link'); ?></td>
        </tr>
        <tr>
          <td><?php echo $pdckl_lang['orders_add_name']; ?></td>
          <td><input type="text" name="pdckl_link_name" class="regular-text"> <?php echo pdckl_show_help('h_add_link_name'); ?></td>
        </tr>
        <tr>
          <td><?php echo $pdckl_lang['orders_add_desc']; ?></td>
          <td><input type="text" name="pdckl_link_description" class="regular-text"> <?php echo pdckl_show_help('h_add_link_desc'); ?></td>
        </tr>
        <tr>
          <td><?php echo $pdckl_lang['settings_type']; ?></td>
          <td>
            <select name="pdckl_type">
              <option value="follow">follow</option>
              <option value="nofollow">nofollow</option>
            </select>
          </td>
        </tr>
        <tr>
          <input type="hidden" name="pdckl_hidden" value="order_add">
          <?php echo wp_nonce_field( 'save-orders' ); ?>
          <td colspan="2"><input type="submit" class="button-primary" value="<?php echo $pdckl_lang['btn_save']; ?>"></td>
          <td style="text-align:right;"><a href="<?php _e(PDCKL_ADMIN_LINK); ?>&amp;s=orders"><input type="button" class="button action" value="<?php echo $pdckl_lang['btn_close']; ?>"></a></td>
        </tr>
      </tbody>
    </table>
  </form>
<?php
}

if(($_GET['a'] ?? '') == 'edit') {
  $id_order = $_GET['order'];
  $id_post = $_GET['post'];
  $order = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "pdckl_links WHERE id = $id_order", ARRAY_A);
  $link = $order['link']; ?>

  <form name="pdckl_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <table class="wp-list-table widefat" style="margin-top:10px;">
      <thead>
        <tr>
          <td colspan="3">
            <h3>
              <?php echo $pdckl_lang['orders_edit_title']; ?>
              <a href="<?php _e(PDCKL_ADMIN_LINK); ?>&amp;s=orders"><input type="button" class="button action" style="float: right;" name="Back" value="<?php echo $pdckl_lang['btn_close']; ?>"></a>
            </h3>
          </td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th><?php echo $pdckl_lang['orders_edit_ido']; ?></th>
          <td><?php _e($id_order); ?></td>
        </tr>
        <tr>
          <th><?php echo $pdckl_lang['orders_edit_idp']; ?></th>
          <td><?php _e($id_post); ?></td>
        </tr>
        <tr>
          <th><?php echo $pdckl_lang['orders_edit_purchased']; ?></th>
          <td><?php _e(Date("d.m.Y - H:i", $order['time'])); ?></td>
        <tr>
          <th><label for="pdckl_link"><?php echo $pdckl_lang['orders_edit_link']; ?></label></th>
          <td><input type="text" name="pdckl_link" value='<?php _e($link); ?>' class="regular-text"></td>
        </tr>
        <tr>
          <input type="hidden" name="pdckl_id_order" value="<?php echo $id_order; ?>">
          <input type="hidden" name="pdckl_hidden" value="order_edit">
          <td></td>
          <td><input type="submit" name="Submit" value="<?php echo $pdckl_lang['btn_save']; ?>" class="button-primary" /></td>
        </tr>
      </tbody>
    </table>
  </form>
<?php } ?>

<table class="wp-list-table widefat" style="margin-top:10px;">
    <thead style="font-weight:bold;">
        <tr>
            <td width="20"><?php echo $pdckl_lang['orders_table_ido']; ?></td>
            <td><?php echo $pdckl_lang['orders_table_idp']; ?></td>
            <td><?php echo $pdckl_lang['orders_table_link']; ?></td>
            <td width="120"><?php echo $pdckl_lang['orders_table_date']; ?></td>
            <td width="150"><?php echo $pdckl_lang['orders_table_tools']; ?></td>
        </tr>
    </thead>
    <tbody>
        <?php
        $orders = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "pdckl_links ORDER BY id DESC", ARRAY_A);
        if($orders == 0)
        {
        ?>
        <tr>
            <td colspan="5" style="color:red;"><?php echo $pdckl_lang['orders_empty']; ?></td>
        </tr>
        <?php
        }
        else
        {
        $orders = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "pdckl_links ORDER BY id DESC", ARRAY_A);
        foreach($orders as $order):
        ?>
        <tr>
            <td><?php _e($order["id"]); ?></td>
            <td><a href="../?p=<?php _e($order["id_post"]); ?>" target="_blank"><?php
              $post = get_post($order["id_post"]);
              _e($post->post_title);
            ?></a></td>
            <td><?php _e($order["link"]); ?></td>
            <td><?php _e(Date("d.m.Y - H:i", $order['time'])); ?></td>
            <td>
                <?php
                $active = $wpdb->get_row("SELECT active FROM " . $wpdb->prefix . "pdckl_links WHERE id = " . $order["id"], ARRAY_A);
                if($active['active'] == 1)
                {
                ?>
                    <a href="<?php _e(PDCKL_ADMIN_LINK); ?>&amp;s=orders&amp;order=<?php _e($order["id"]); ?>&amp;a=hide"><?php echo $pdckl_lang['orders_tools_hide']; ?></a>
                <?php
                }
                else
                {
                ?>
                    <a href="<?php _e(PDCKL_ADMIN_LINK); ?>&amp;s=orders&amp;order=<?php _e($order["id"]); ?>&amp;a=show"><?php echo $pdckl_lang['orders_tools_show']; ?></a>
                <?php } ?>

                |
                <a href="<?php _e(PDCKL_ADMIN_LINK); ?>&amp;s=orders&amp;order=<?php _e($order["id"]); ?>&amp;post=<?php _e($order['id_post']); ?>&amp;a=edit"><?php echo $pdckl_lang['orders_tools_edit']; ?></a> |
                <a href="<?php _e(PDCKL_ADMIN_LINK); ?>&amp;s=orders&amp;order=<?php _e($order["id"]); ?>&amp;a=delete"><?php echo $pdckl_lang['orders_tools_delete']; ?></a>
            </td>
        </tr>
        <?php endforeach;
        } ?>
    </tbody>
</table>
<p><?php echo $pdckl_lang['orders_backup_tip'] ?></p>
