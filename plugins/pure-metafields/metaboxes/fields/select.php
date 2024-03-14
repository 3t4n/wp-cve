<?php
/**
 * Select
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php if(!isset($row_db_value)): ?>
<select 
    <?php echo (isset($multiple) && $multiple == true)? "name=".esc_attr($id)."[]" : "name=".esc_attr($id); ?>
    id="<?php echo esc_attr($id); ?>-select" 
    class="<?php echo esc_attr($id); ?> tm-select-field <?php echo isset($context)? esc_attr($context) : ''; ?>"
    <?php echo (isset($multiple) && $multiple == true)? 'multiple' : ''; ?>>
    <?php if(!isset($multiple) && $multiple == true): ?>
    <option value="<?php echo esc_html($default); ?>"><?php echo esc_html($placeholder)?? esc_html('Select...'); ?></option>
    <?php endif; ?>
    <?php foreach($options as $key => $val): ?>
        <option 
            value="<?php echo esc_html($key); ?>" 
            <?php selected((is_array(tpmeta_field($id))? array_key_exists($key, tpmeta_field($id)) : tpmeta_field($id) == $key), 1); ?>><?php echo esc_html($val); ?>
        </option>
    <?php endforeach; ?>
</select>
<?php else:
global $post;
$bind_keys = isset($bind)? $bind : '';    
$json_value = '';
if(isset($multiple) && $multiple == true){
    if(metadata_exists('post', $post->ID, $repeater_id)){
        $array_object = [];
        if(!empty($row_db_value)){
            foreach($options as $key => $val){
                if(array_key_exists($key, $row_db_value)){
                    array_push($array_object, $key);
                }
            }
            $json_value = json_encode($array_object, true);
        }
        
    }else{
        $array_object = [];
        foreach($options as $key => $val){
            if(in_array($key, $default)){
                $array_object[$key] = $val;
            }
        }
        $json_value = json_encode($array_object, true);
    }
}

?>
<?php if(isset($multiple) && $multiple == true): ?>
    <input type="hidden" name="<?php echo esc_attr($id); ?>[]" value="<?php echo esc_attr($json_value); ?>">
<?php endif; ?>
<select 
    <?php echo (isset($multiple) && $multiple == true)? "name=".esc_attr($id)."_select[]" : "name=".esc_attr($id).'[]'; ?>
    data-key="<?php echo esc_attr($bind_keys); ?>"
    class="<?php echo esc_attr($id); ?> tm-repeater-select-field tm-repeater-conditional <?php echo isset($context)? esc_attr($context) : ''; ?>"
    <?php echo (isset($multiple) && $multiple == true)? 'multiple' : ''; ?>>
    <?php if(isset($multiple) && $multiple == true): ?>
    <option value="<?php echo esc_html($default?? ''); ?>"><?php echo esc_html($placeholder)?? esc_html('Select...'); ?></option>
    <?php endif; ?>
    <?php foreach($options as $key => $val): ?>
        <?php if(isset($multiple) && $multiple == true): ?>
        <option 
            value="<?php echo esc_html($key); ?>" 
            <?php selected((!metadata_exists('post', $post->ID, $repeater_id)? in_array($key, $default?? array()) : ($default?? '' == $key) ), 1); ?>><?php echo esc_html($val); ?>
        </option>
        <?php else: ?>
        <option 
            value="<?php echo esc_html($key); ?>" 
            <?php selected(array_key_exists($key, (array) $row_db_value), 1); ?>><?php echo esc_html($val); ?>
        </option>
        <?php endif; ?>
    <?php endforeach; ?>
</select>
<?php endif; ?>