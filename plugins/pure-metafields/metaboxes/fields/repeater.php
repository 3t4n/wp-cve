<?php
/**
 * Repeater Fields
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$args = array(
    'field_type'    => 'repeater',
    'repeater_id'   => $id,
);
if(isset($fields) && !empty($fields) && $type == 'repeater'){
    
$_get_db_values = tpmeta_field($id);
?>
<?php if( !empty($_get_db_values) ): ?>
<div class="tp-repeater">
    
    <div class="tp-metabox-repeater connected-sortable">
        <?php 
            $count = 0;
            foreach($_get_db_values as $key => $row):    
            $count++;
        ?>
        <div class="tp-metabox-repeater-row">
            <button class="tp-metabox-repeater-collapse">  
                <span>
                    <svg width="16" height="9" viewBox="0 0 16 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 1L8 8L1 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <span data-count="<?php echo esc_attr($count); ?>" class="tp-metabox-repeater-collapse-text"><?php echo esc_html__('Item', 'pure-metafields'); ?> <?php echo esc_html($count); ?></span>
            </button>
            <input type="hidden" name="<?php echo esc_attr($id); ?>[]" class="<?php echo esc_attr($id); ?>" value="<?php echo esc_html($id); ?>">

            <div class="tp-metabox-repeater-item-wrapper">
                <?php
                    foreach($fields as $field){
                        $new_args = wp_parse_args($args, array(
                            'row_db_value'  => isset($row[$field['id']])? $row[$field['id']] : '', 
                            'field'         => $field, 
                            'row'           => $row,
                            'index'         => $count
                        ));
                        
                        echo '<div class="repeater-field">';
                            tpmeta_load_template( 'metaboxes/fields/repeater-group.php', $new_args);
                        echo '</div>';
                    }
                ?>
            </div>
            <button class="tp-repeater-row-delete-btn tp-delete-row" type="button">
                <svg width="16" height="16" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 5H3H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M17 5V19C17 19.5304 16.7893 20.0391 16.4142 20.4142C16.0391 20.7893 15.5304 21 15 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5M6 5V3C6 2.46957 6.21071 1.96086 6.58579 1.58579C6.96086 1.21071 7.46957 1 8 1H12C12.5304 1 13.0391 1.21071 13.4142 1.58579C13.7893 1.96086 14 2.46957 14 3V5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8 10V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 10V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="tp-repeater-top">
        <button class="tp-repeater-row-add-btn tp-add-row" type="button"><?php echo esc_html__('Add Row', 'pure-metafields'); ?></button>
    </div>
</div>
<?php else: ?>
<div class="tp-repeater">
    <div class="tp-metabox-repeater connected-sortable">
        <div class="tp-metabox-repeater-row">
            <button class="tp-metabox-repeater-collapse">  
                <span>
                    <svg width="16" height="9" viewBox="0 0 16 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 1L8 8L1 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <span data-count="1" class="tp-metabox-repeater-collapse-text"><?php echo esc_html__('Item', 'pure-metafields'); ?> 1</span>
            </button>

            <input type="hidden" name="<?php echo esc_attr($id); ?>[]" class="<?php echo esc_attr($id); ?>" value="<?php echo esc_html($id); ?>">
            <div class="tp-metabox-repeater-item-wrapper">
                <?php
                    foreach($fields as $field){
                        $new_args = wp_parse_args($args, array('field' => $field, 'fields' => $fields));
                            echo '<div class="repeater-field">';
                            tpmeta_load_template( 'metaboxes/fields/repeater-group.php', $new_args);
                            echo '</div>';
                    }
                ?>
            </div>
            <button class="tp-repeater-row-delete-btn tp-delete-row" type="button">
                <svg width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 5H3H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M17 5V19C17 19.5304 16.7893 20.0391 16.4142 20.4142C16.0391 20.7893 15.5304 21 15 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5M6 5V3C6 2.46957 6.21071 1.96086 6.58579 1.58579C6.96086 1.21071 7.46957 1 8 1H12C12.5304 1 13.0391 1.21071 13.4142 1.58579C13.7893 1.96086 14 2.46957 14 3V5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8 10V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 10V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>
    <div class="tp-repeater-top">
        <button class="tp-repeater-row-add-btn tp-add-row" type="button"><?php echo esc_html__('Add Row', 'pure-metafields'); ?></button>
    </div>
</div>
<?php endif; ?>
<?php
}

