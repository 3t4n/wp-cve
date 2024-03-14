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
$this->section_header( $adminobj, $widgetobj, 'addltext', $is_widget ); 
?>
    <p>
      <label title="<?php echo $this->get_tip( 'text_position' );?>" for="<?php echo $widgetobj->get_field_id( 'text_position' ); ?>">
        <?php echo $this->get_label( 'text_position' ); ?>: </label>
      <select name="<?php echo $widgetobj->get_field_name( 'text_position' ); ?>" id="<?php echo $widgetobj->get_field_id( 'text_position' ); ?>">
        <?php foreach ( IntelliWidgetStrings::get_menu( 'text_position' ) as $value => $label ): ?>
        <option value="<?php echo $value; ?>" <?php selected( $instance[ 'text_position' ], $value ); ?>><?php echo $label; ?></option>
        <?php endforeach; ?>
      </select>
    </p>
    <p>
      <textarea class="widefat" rows="3" cols="20" id="<?php echo $widgetobj->get_field_id( 'custom_text' ); ?>" 
name="<?php echo $widgetobj->get_field_name( 'custom_text' ); ?>"><?php echo esc_textarea( $instance[ 'custom_text' ] ); ?></textarea>
    </p>
    <p>
      <label title="<?php echo $this->get_tip( 'filter' );?>">
        <input id="<?php echo $widgetobj->get_field_id( 'filter' ); ?>" name="<?php echo $widgetobj->get_field_name( 'filter' ); ?>" type="checkbox" <?php checked( $instance[ 'filter' ], 1 ); ?> value="1" />
        &nbsp;
        <?php echo $this->get_label( 'filter' ); ?>
      </label>
    </p>
<?php $this->section_footer();
