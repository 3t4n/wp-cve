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
// convert legacy category to taxonomies
//echo 'widget id: ' . $_POST[ 'widget-id' ] . "\n";
//global $wp_registered_widgets;

if ( empty( $instance[ 'terms' ] ) && isset( $instance[ 'category' ] ) && '-1' != $instance[ 'category' ] )
    $instance[ 'terms' ] = $adminobj->map_category_to_tax( $instance[ 'category' ] );
       
?>
<input type="hidden" name="<?php echo $widgetobj->get_field_name( 'page_multi' ); ?>" id="<?php echo $widgetobj->get_field_id( 'page_multi' ); ?>" value="1" />
<input type="hidden" name="<?php echo $widgetobj->get_field_name( 'terms_multi' ); ?>" id="<?php echo $widgetobj->get_field_id( 'terms_multi' ); ?>" value="1" />
    <p>
      <label title="<?php echo $this->get_tip( 'page' );?>"><?php echo $this->get_label( 'page' );?>:</label><br/>
      <input id="<?php echo $widgetobj->get_field_id( 'pagesearch' ); ?>" name="<?php echo $widgetobj->get_field_name( 'pagesearch' ); ?>" type="text" value="" placeholder="<?php _e( 'Refine List', 'intelliwidget' ); ?>" class="iw-menusearch" autocomplete="off" /><br/>
      <select  class="widefat intelliwidget-multiselect" name="<?php echo $widgetobj->get_field_name( 'page' ); ?>[]"  multiple="multiple" id="<?php echo $widgetobj->get_field_id( 'page' ); ?>">
        <?php echo $adminobj->get_posts_list( $instance ); ?>
      </select>
    </p> 
    <p>
      <label title="<?php echo $this->get_tip( 'terms' );?>">
        <?php echo $this->get_label( 'terms' );?>
      </label>&nbsp;<select name="<?php echo $widgetobj->get_field_name( 'allterms' ); ?>" id="<?php echo $widgetobj->get_field_id( 'allterms' ); ?>" style="width:4em;min-width:4em">
        <option value="0"<?php if ( isset( $instance[ 'allterms' ] ) ) selected( $instance[ 'allterms' ], 0 ); ?>>
        <?php _e( 'any', 'intelliwidget' ); ?>
        </option>
        <option value="1"<?php if ( isset( $instance[ 'allterms' ] ) ) selected( $instance[ 'allterms' ], 1 ); ?>>
        <?php _e( 'all', 'intelliwidget' ); ?>
        </option>
      </select>&nbsp;<label title="<?php echo $this->get_tip( 'allterms' ); ?>"><?php echo $this->get_label( 'allterms' ); ?>:</label><br/>
      <input id="<?php echo $widgetobj->get_field_id( 'termssearch' ); ?>" name="<?php echo $widgetobj->get_field_name( 'termssearch' ); ?>" type="text" value="" placeholder="<?php _e( 'Refine List', 'intelliwidget' ); ?>" class="iw-menusearch" autocomplete="off" /><br/>
      <select class="widefat intelliwidget-multiselect" name="<?php echo $widgetobj->get_field_name( 'terms' ); ?>[]" size="1" multiple="multiple" id="<?php echo $widgetobj->get_field_id( 'terms' ); ?>">
        <?php echo $adminobj->get_terms_list( $instance ); ?>
      </select>
    </p>