<?php
/**
 * Text
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if( !empty($field['conditional']) ){
    $compare_results = tpmeta_is_condition_matched($field['conditional'], $fields);
}else{
    $compare_results = true;
}

$format = get_post_format() ? : 'standard';
$field['field_type'] = isset($field_type)? $field_type : '';
$field['post'] = $post;
?>

<?php if(isset($field['post_format']) && $field['post_format'] != ""): ?>
<div class="tm-field-row <?php echo esc_attr($field['type']); ?> <?php echo esc_attr(esc_html($field['id'])); ?>" style="display:<?php echo !$compare_results || ($format != $field['post_format'])? 'none' : 'block'; ?>">
    <label class="label-<?php echo esc_attr($field['type']); ?>"><?php echo esc_html($field['label']); ?></label>
    <?php tpmeta_load_template('metaboxes/fields/'.$field['type'].'.php', $field); ?>
</div>
<?php else: ?>
    <?php if($field['type'] == 'tabs'): ?>
    <div class="tm-field-row <?php echo esc_attr($field['type']); ?> <?php echo esc_attr(esc_html($field['id'])); ?>" style="display:<?php echo !$compare_results? 'none' : 'block'; ?>">
        <label class="label-<?php echo esc_attr($field['type']); ?>"><?php echo esc_html($field['label']); ?></label>
        <?php tpmeta_load_template('metaboxes/fields/'.$field['type'].'.php', $field); ?>
        <?php
            $tabs_childs = array_filter($fields, function($item) use ($field){
                if(isset($item['parent'])){
                    return $field['id'] == $item['parent'];
                }
            });
        ?>
        <?php foreach($tabs_childs as $tab_child): 
        $hide_tab_content = tpmeta_is_condition_matched($tab_child['conditional'], $tabs_childs);    
        ?>
        <div class="tm-field-row <?php echo esc_attr($tab_child['type']); ?> <?php echo esc_attr(esc_html($tab_child['id'])); ?>" style="display:<?php echo !$hide_tab_content? 'none' : 'block'; ?>">
            <label class="label-<?php echo esc_attr($tab_child['type']); ?>"><?php echo esc_html($tab_child['label']); ?></label>
            <?php tpmeta_load_template('metaboxes/fields/'.$tab_child['type'].'.php', $tab_child); ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
        <?php if(!isset($field['parent'])): ?>
        <div class="tm-field-row <?php echo esc_attr($field['type']); ?> <?php echo esc_attr(esc_html($field['id'])); ?>" style="display:<?php echo !$compare_results? 'none' : 'block'; ?>">
            <label class="label-<?php echo esc_attr($field['type']); ?>"><?php echo esc_html($field['label']); ?></label>
            <?php tpmeta_load_template('metaboxes/fields/'.$field['type'].'.php', $field); ?>
        </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>
<?php
if(!empty($field['conditional'])): 
    $metabox_types = array_column($fields, 'type', 'id');
    $metafield_type = isset($metabox_types[$field['conditional'][0]])? $metabox_types[$field['conditional'][0]] : '';
?>
<script type="text/javascript">
(function( $ ){
"use scrict";
    if('<?php echo esc_html($metafield_type); ?>' == 'switch'){
        $('#'+'<?php echo esc_html($field['conditional'][0]); ?>').on('change', function(){
            if($(this).is(':checked')){
                $(this).val('on')
                let evaluate = eval("$(this).val()<?php echo esc_html($field['conditional'][1]); ?>'<?php echo esc_html($field['conditional'][2]); ?>'")
                if(evaluate){
                    $('.'+'<?php echo esc_html($field['id']); ?>').show()
                }
            }else{
                $(this).val('off')
                let evaluate = eval("$(this).val()<?php echo esc_html($field['conditional'][1]); ?>'<?php echo esc_html($field['conditional'][2]); ?>'")
                
                if(!evaluate){
                    $('.'+'<?php echo esc_html($field['id']); ?>').hide()
                }
            }
        })
    }else if('<?php echo esc_html($metafield_type);  ?>' == 'tabs'){
        $('.'+'<?php echo esc_html($field['conditional'][0]); ?>-tab').on('change', function(e){
            
            let evaluate = eval("$(this).val()<?php echo esc_html($field['conditional'][1]); ?>'<?php echo esc_html($field['conditional'][2]); ?>'")
            
            if(evaluate){
                $('.'+'<?php echo esc_html($field['id']); ?>').show()
            }else{
                $('.'+'<?php echo esc_html($field['id']); ?>').hide()
            }
            
        })
    }else if('<?php echo esc_html($metafield_type);  ?>' == 'select'){
        $('#'+'<?php echo esc_html($field['conditional'][0]); ?>-select').on('change', function(e){
            
            let evaluate = eval("$(this).val()<?php echo esc_html($field['conditional'][1]); ?>'<?php echo esc_html($field['conditional'][2]); ?>'")
            
            if(evaluate){
                $('.'+'<?php echo esc_html($field['id']); ?>').show()
            }else{
                $('.'+'<?php echo esc_html($field['id']); ?>').hide()
            }
            
        })
    }else{
        console.log('No Conditional Element Found')
    }
})( jQuery )
</script>
<?php endif; ?>