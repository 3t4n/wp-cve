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
?>
<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
if ( !$is_widget ): // only show menu locations on post profiles
    if ( !defined( 'INTELLIWIDGET_PRO_VERSION' ) || INTELLIWIDGET_PRO_VERSION >= '2.0.0' ):
?><p>
  <label title="<?php echo $this->get_tip( 'menu_location' );?>" for="<?php echo $widgetobj->get_field_id( 'menu_location' ); ?>">
    <?php echo $this->get_label( 'menu_location' ); ?>
    : </label>
  <select id="<?php echo $widgetobj->get_field_id( 'menu_location' ); ?>" name="<?php echo $widgetobj->get_field_name( 'menu_location' ); ?>">
        <option value=""><?php _e( 'Use Widget Location', 'chld_thm_cfg_plugins' ); ?></option>
            <?php
            
            // Get menu locations
            foreach ( get_registered_nav_menus() as $value => $label ): ?>
        <option value="<?php echo $value; ?>" <?php selected( $instance[ 'menu_location' ], $value ); ?>><?php echo $label; ?></option>
        <?php endforeach;?>
  </select>
</p><?php endif; endif; ?>
<p>
  <label title="<?php echo $this->get_tip( 'nav_menu' );?>" for="<?php echo $widgetobj->get_field_id( 'nav_menu' ); ?>">
    <?php echo $this->get_label( 'nav_menu' ); ?>: </label>
  <select id="<?php echo $widgetobj->get_field_id( 'nav_menu' ); ?>" name="<?php echo $widgetobj->get_field_name( 'nav_menu' ); ?>">
            <?php
            // Get menus
            foreach ( $adminobj->get_nav_menu() as $value => $label ): ?>
        <option value="<?php echo $value; ?>" <?php selected( $instance[ 'nav_menu' ], $value ); ?>><?php echo $label; ?></option>
        <?php endforeach;?>
  </select>
</p>
