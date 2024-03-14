<div id="modal-categories" class="af2_modal"
data-class="modal-categories"
data-target="modal-categories"
data-sizeclass="moderate_big_size"
data-bottombar="false"
data-urlopen="show_category_modal"
data-heading="<?php _e('Edit categories', 'funnelforms-free'); ?>"
data-close="<?php _e('Close', 'funnelforms-free'); ?>">

    <!-- Modal content -->
    <div class="af2_modal_content">
        <p style="margin-bottom: 20px;"><?php _e('Categories always have to be unique and can not have the same title. If a category gets deleted the question will stay but they change their state to not assigned.', 'funnelforms-free'); ?></p>
        <div class="af2_add_category">
            <input class="af2_add_category_input" placeholder="<?php _e('Add category', 'funnelforms-free'); ?>" type="text"/>
            <button class="add_category af2_btn af2_btn_primary"><?php _e('Add', 'funnelforms-free'); ?></button>
        </div>
        <div class="af2_category_list">
        <?php 
        foreach($menu_functions_select['options'] as $option) { 
            
            $opnlable ='' ;
           if($option['label']){$opnlable = $option['label']; }    
            $opnvalue ='' ;
           if($option['value']){$opnvalue = $option['value']; }
           ?>       
            <div class="af2_category" id="<?php _e(esc_html($opnvalue)); ?>">
                <div class="af2_category_title"><h5><?php _e(esc_html($opnlable)) ; ?></h5></div>
                <button class="af2_category_delete af2_btn af2_btn_primary"><?php _e('Delete', 'funnelforms-free'); ?></button>
            </div>            
            <?php 
        }
       ; ?>   
        </div>     
    </div>
</div>