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
 */
global $_wp_additional_image_sizes;
$this->section_header( $adminobj, $widgetobj, 'appearance', $is_widget );
?>
    <p>
      <label title="<?php echo $this->get_tip( 'template' );?>" for="<?php echo $widgetobj->get_field_id( 'template' ); ?>" class="aligned">
        <?php echo $this->get_label( 'template' ); ?>:</label>
      <select name="<?php echo $widgetobj->get_field_name( 'template' ); ?>" id="<?php echo $widgetobj->get_field_id( 'template' ); ?>">
        <?php foreach ( $adminobj->templates as $template => $name ) : ?>
        <option value="<?php echo $template; ?>" <?php selected( $instance[ 'template' ], $template ); ?>><?php echo $name; ?></option>
        <?php endforeach; ?>
      </select>
    </p>
    <p>
      <label title="<?php echo $this->get_tip( 'sortby' );?>" for="<?php echo $widgetobj->get_field_id( 'sortby' ); ?>" class="aligned">
        <?php echo $this->get_label( 'sortby' ); ?>: </label>
      <select name="<?php echo $widgetobj->get_field_name( 'sortby' ); ?>" id="<?php echo $widgetobj->get_field_id( 'sortby' ); ?>">
        <?php foreach ( IntelliWidgetStrings::get_menu( 'sortby' ) as $value => $label ): ?>
        <option value="<?php echo $value; ?>" <?php selected( $instance[ 'sortby' ], $value ); ?>><?php echo $label; ?></option>
        <?php endforeach; ?>
      </select><label class="aligned">&nbsp;</label>
      <select name="<?php echo $widgetobj->get_field_name( 'sortorder' ); ?>" id="<?php echo $widgetobj->get_field_id( 'sortorder' ); ?>">
        <option value="ASC"<?php selected( $instance[ 'sortorder' ], 'ASC' ); ?>>
        <?php _e( 'ASC', 'intelliwidget' ); ?>
        </option>
        <option value="DESC"<?php selected( $instance[ 'sortorder' ], 'DESC' ); ?>>
        <?php _e( 'DESC', 'intelliwidget' ); ?>
        </option>
      </select>
    </p>
    <p>
      <label title="<?php echo $this->get_tip( 'items' );?>" for="<?php echo $widgetobj->get_field_id( 'items' ); ?>" class="aligned">
        <?php echo $this->get_label( 'items' ); ?>: </label>
      <input id="<?php echo $widgetobj->get_field_id( 'items' ); ?>" name="<?php echo $widgetobj->get_field_name( 'items' ); ?>" size="3" type="text" value="<?php echo esc_attr( $instance[ 'items' ] ); ?>" />
    </p>
    <p>
      <label title="<?php echo $this->get_tip( 'length' );?>" for="<?php echo $widgetobj->get_field_id( 'length' ); ?>" class="aligned">
        <?php echo $this->get_label( 'length' ); ?>: </label>
      <input id="<?php echo $widgetobj->get_field_id( 'length' ); ?>" name="<?php echo $widgetobj->get_field_name( 'length' ); ?>" size="3" type="text" value="<?php echo esc_attr( $instance[ 'length' ] ); ?>" />
    </p>
    <p>
      <label title="<?php echo $this->get_tip( 'allowed_tags' );?>" for="<?php echo $widgetobj->get_field_id( 'allowed_tags' ); ?>" class="aligned">
        <?php echo $this->get_label( 'allowed_tags' ); ?>: </label>
      <input name="<?php echo $widgetobj->get_field_name( 'allowed_tags' ); ?>" id="<?php echo $widgetobj->get_field_id( 'allowed_tags' ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'allowed_tags' ] ); ?>" />
    </p>
    <p>
      <label title="<?php echo $this->get_tip( 'link_text' );?>" for="<?php echo $widgetobj->get_field_id( 'link_text' ); ?>" class="aligned">
        <?php echo $this->get_label( 'link_text' ); ?>: </label>
      <input name="<?php echo $widgetobj->get_field_name( 'link_text' ); ?>" id="<?php echo $widgetobj->get_field_id( 'link_text' ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'link_text' ] ); ?>" />
    </p>
    <p>
      <label title="<?php echo $this->get_tip( 'imagealign' );?>" for="<?php print $widgetobj->get_field_id( 'imagealign' ); ?>" class="aligned">
        <?php echo $this->get_label( 'imagealign' ); ?>: </label>
      <select name="<?php print $widgetobj->get_field_name( 'imagealign' ); ?>" id="<?php print $widgetobj->get_field_id( 'imagealign' ); ?>">
        <?php foreach ( IntelliWidgetStrings::get_menu( 'imagealign' ) as $value => $label ): ?>
        <option value="<?php echo $value; ?>" <?php selected( $instance[ 'imagealign' ], $value ); ?>><?php echo $label; ?></option>
        <?php endforeach; ?>
      </select>
    </p>
    <p>
      <label title="<?php echo $this->get_tip( 'image_size' );?>" for="<?php print $widgetobj->get_field_id( 'image_size' ); ?>" class="aligned">
        <?php echo $this->get_label( 'image_size' ); ?>: </label>
      <select id="<?php echo $widgetobj->get_field_id( 'image_size' ); ?>" name="<?php echo $widgetobj->get_field_name( 'image_size' ); ?>">
        <?php foreach ( IntelliWidgetStrings::get_menu( 'image_size' ) as $value => $label ): ?>
        <option value="<?php echo $value; ?>" <?php selected( $instance[ 'image_size' ], $value ); ?>><?php echo $label; ?></option>
        <?php endforeach; ?>
        <?php if ( is_array( $_wp_additional_image_sizes ) ): foreach ( $_wp_additional_image_sizes as $name => $size ) : ?>
        <option value="<?php echo $name; ?>" <?php selected( $instance[ 'image_size' ], $name ); ?> ><?php echo $name; ?> ( <?php echo $size[ 'width' ]; ?>x<?php echo $size[ 'height' ]; ?>px )</option>
        <?php endforeach; endif;?>
      </select>
    </p>
    <p>
      <label title="<?php echo $this->get_tip( 'no_img_links' );?>">
        <input name="<?php echo $widgetobj->get_field_name( 'no_img_links' ); ?>" id="<?php echo $widgetobj->get_field_id( 'no_img_links' ); ?>" type="checkbox" <?php checked( $instance[ 'no_img_links' ], 1 ); ?> value="1" /><?php echo $this->get_label( 'no_img_links' ); ?>
      </label>
    </p>
    <p>
      <label title="<?php echo $this->get_tip( 'keep_title' );?>">
        <input name="<?php echo $widgetobj->get_field_name( 'keep_title' ); ?>" id="<?php echo $widgetobj->get_field_id( 'keep_title' ); ?>" type="checkbox" <?php checked( $instance[ 'keep_title' ], 1 ); ?> value="1" /><?php echo $this->get_label( 'keep_title' ); ?>
      </label>
    </p>
  </div>
</div>