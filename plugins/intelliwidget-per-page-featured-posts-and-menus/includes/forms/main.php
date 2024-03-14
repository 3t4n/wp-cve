<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * main.php - Outputs widget form
 *
 * @package IntelliWidget
 * @subpackage includes
 * @author Jason C Fleming
 * @copyright 2014-2015 Lilaea Media LLC
 * @access public
 */
?>
<?php if ( $is_widget ): ?><div class="intelliwidget-form-container"><?php endif; ?>
<input type="hidden" id="<?php echo $widgetobj->get_field_id( 'category' ); ?>" name="<?php echo $widgetobj->get_field_name( 'category' ); ?>" value="" />
<?php if ( !$is_widget && 'preview' != $adminobj->objecttype ): ?>
<p><?php echo apply_filters( 'intelliwidget_nocopy_setting', '
  <label title="' . $this->get_tip( 'nocopy' ) . '">
    <input id="' . $widgetobj->get_field_id( 'nocopy' ). '" name="' . $widgetobj->get_field_name( 'nocopy' ) . '" type="checkbox" ' . checked( $instance[ 'nocopy' ], 1, FALSE ) . ' value="1"/> ' . $this->get_label( 'nocopy' ) . '
  </label>
' ); ?>
</p><?php endif; ?>
<p> <?php if ( $is_widget ) include( INTELLIWIDGET_DIR . '/includes/forms/docslink.php' ); ?>
<?php if ( $is_widget ): ?>
  <label title="<?php echo $this->get_tip( 'hide_if_empty' ); ?>">
    <input class="iw-widget-control" name="<?php echo $widgetobj->get_field_name( 'hide_if_empty' ); ?>" id="<?php echo $widgetobj->get_field_id( 'hide_if_empty' ); ?>" type="checkbox" <?php checked( $instance[ 'hide_if_empty' ], 1 ); ?> value="1"/><?php echo $this->get_label( 'hide_if_empty' ); ?>  </label>
<?php elseif ( 'preview' != $adminobj->objecttype ): ?>    
      <input type="hidden" id="<?php echo $widgetobj->get_field_id( 'box_id' ); ?>" name="<?php echo $widgetobj->get_field_name( 'box_id' ); ?>" value="<?php echo $widgetobj->box_id; ?>" />
  <label title="<?php echo $this->get_tip( 'replace_widget' ); ?>" for="<?php echo $widgetobj->get_field_id( 'box_id' ); ?>">
    <?php echo $this->get_label( 'replace_widget' ); ?>: </label>
  <select name="<?php echo $widgetobj->get_field_name( 'replace_widget' ); ?>" id="<?php echo $widgetobj->get_field_id( 'replace_widget' ); ?>">
    <?php foreach ( $adminobj->intelliwidgets as $value => $label ): ?>
    <option value="<?php echo $value; ?>" <?php selected( $instance[ 'replace_widget' ], $value ); ?>><?php echo $label; ?></option>
    <?php endforeach; ?>
  </select>
<?php endif; ?>
</p>
<?php // execute custom action hook for content value if it exists
        if ( empty( $instance[ 'hide_if_empty' ] ) ):
            do_action( 'intelliwidget_form_all_before', $adminobj, $widgetobj, $instance, $is_widget );
            do_action( 'intelliwidget_form_' . $instance[ 'content' ], $adminobj, $widgetobj, $instance, $is_widget );
            do_action( 'intelliwidget_form_all_after', $adminobj, $widgetobj, $instance, $is_widget );
        endif;
        if ( !$is_widget ): if ( 'preview' != $adminobj->objecttype ): ?>
<span class="submitbox" style="float:left;"><a href="<?php echo $adminobj->get_nonce_url( $widgetobj->post_id, 'delete', $widgetobj->box_id ); ?>" id="iw_delete_<?php echo $widgetobj->post_id . '_' . $widgetobj->box_id; ?>" class="iw-delete submitdelete">
<?php _e( 'Delete', 'intelliwidget' ); ?>
</a></span><?php endif; ?><div class="iw-save-container" style="float:right"><input name="save" class="button button-large iw-save" id="<?php echo $widgetobj->get_field_id( 'save' ); ?>" value="<?php _e( 'Save Settings', 'intelliwidget' ); ?>" type="button" autocomplete="off" /></div>
  <span class="spinner <?php echo $widgetobj->get_field_id( 'spinner' ); ?>"></span>

<div style="clear:both"></div><?php 
        endif;
    if ( $is_widget ): ?></div><?php endif;