<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * class-intelliwidget-form.php - Outputs widget form
 *
 * @package IntelliWidget
 * @subpackage includes
 * @author Jason C Fleming
 * @copyright 2014-2015 Lilaea Media LLC
 * @access public
 */
//echo 'widget id: ' . $_POST[ 'widget-id' ] . "\n";
//global $wp_registered_widgets;


$this->section_header( $adminobj, $widgetobj, 'selection', $is_widget );
?><span class="spinner <?php echo $widgetobj->get_field_id( 'selectionspinner' ); ?>"></span>
    <p>
      <label title="<?php echo $this->get_tip( 'post_types' );?>" style="display:block">
        <?php echo $this->get_label( 'post_types' ); ?>:</label>
      <?php foreach ( $adminobj->post_types as $type ) : ?>
      <label style="white-space:nowrap;margin-right:10px" for="<?php echo $widgetobj->get_field_id( 'post_types_' . $type ); ?>">
        <input class="iw<?php echo $is_widget? '-widget' : ''; ?>-control"  type="checkbox" id="<?php echo $widgetobj->get_field_id( 'post_types_' . $type ); ?>" name="<?php echo $widgetobj->get_field_name( 'post_types' ); ?>[]" value="<?php echo $type; ?>" <?php checked( in_array( $type, $instance[ 'post_types' ] ), 1 ); ?> />
        <?php echo ucfirst( $type ); ?></label>
      <?php endforeach; ?>
    </p>
    <div id="<?php echo $widgetobj->get_field_id( 'menus' ); ?>">
<?php do_action( 'intelliwidget_post_selection_menus', $adminobj, $widgetobj, $instance ); ?>
    </div>
    <div>
    <div style="float:left;width:47%">
    <p>
      <label title="<?php echo $this->get_tip( 'hide_no_posts' );?>">
        <input name="<?php echo $widgetobj->get_field_name( 'hide_no_posts' ); ?>" id="<?php echo $widgetobj->get_field_id( 'hide_no_posts' ); ?>" type="checkbox" <?php checked( $instance[ 'hide_no_posts' ], 1 ); ?> value="1" /><?php echo $this->get_label( 'hide_no_posts' ); ?>
      </label>
    </p>
    <p>
      <label title="<?php echo $this->get_tip( 'same_term' );?>">
        <input name="<?php echo $widgetobj->get_field_name( 'same_term' ); ?>" id="<?php echo $widgetobj->get_field_id( 'same_term' ); ?>" type="checkbox" <?php checked( $instance[ 'same_term' ], 1 ); ?> value="1" /><?php echo $this->get_label( 'same_term' ); ?>
      </label>
    </p>
<?php if ( current_user_can( 'read_private_posts' ) ): ?>
    <p>
      <label title="<?php echo $this->get_tip( 'include_private' );?>">
        <input name="<?php echo $widgetobj->get_field_name( 'include_private' ); ?>" id="<?php echo $widgetobj->get_field_id( 'include_private' ); ?>" type="checkbox" <?php checked( $instance[ 'include_private' ], 1 ); ?> value="1" /><?php echo $this->get_label( 'include_private' ); ?>
      </label>
    </p>   
<?php endif; ?></div><div style="float:right;width:47%">
    <p>
      <label title="<?php echo $this->get_tip( 'future_only' );?>">
        <input name="<?php echo $widgetobj->get_field_name( 'future_only' ); ?>" id="<?php echo $widgetobj->get_field_id( 'future_only' ); ?>" type="checkbox" <?php checked( $instance[ 'future_only' ], 1 ); ?> value="1" /><?php echo $this->get_label( 'future_only' ); ?>
      </label>
    </p>
    <p>
      <label title="<?php echo $this->get_tip( 'active_only' );?>">
        <input name="<?php echo $widgetobj->get_field_name( 'active_only' ); ?>" id="<?php echo $widgetobj->get_field_id( 'active_only' ); ?>" type="checkbox" <?php checked( $instance[ 'active_only' ], 1 ); ?> value="1" /><?php echo $this->get_label( 'active_only' ); ?>
      </label>
    </p>
    <p>
      <label title="<?php echo $this->get_tip( 'skip_expired' );?>">
        <input name="<?php echo $widgetobj->get_field_name( 'skip_expired' ); ?>" id="<?php echo $widgetobj->get_field_id( 'skip_expired' ); ?>" type="checkbox" <?php checked( $instance[ 'skip_expired' ], 1 ); ?> value="1" /><?php echo $this->get_label( 'skip_expired' ); ?>
      </label>
    </p>
    <p>
      <label title="<?php echo $this->get_tip( 'skip_post' );?>">
        <input name="<?php echo $widgetobj->get_field_name( 'skip_post' ); ?>" id="<?php echo $widgetobj->get_field_id( 'skip_post' ); ?>" type="checkbox" <?php checked( $instance[ 'skip_post' ], 1 ); ?> value="1" /><?php echo $this->get_label( 'skip_post' ); ?>
      </label>
    </p>
<?php // hidden input field with timestamp forces customizer to update widget form 
    if ( $is_widget ):
        $time = 'iw' . time(); ?><input type="hidden" name="<?php echo $widgetobj->get_field_name( $time ); ?>" value="" id="<? echo $widgetobj->get_field_id( $time ); ?>" /><?php
    endif; ?>
</div><div style="clear:both"></div></div><?php
    $this->section_footer();
