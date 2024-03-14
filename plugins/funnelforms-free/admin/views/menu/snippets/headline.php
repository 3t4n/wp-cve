<div class="af2_menu_headline">
    <div class="af2_menu_headline_heading">
        <h3 class="af2_menu_headline_heading_text"><?php echo esc_html($heading); ?></h3>
    </div>
    <div class="af2_menu_headline_components">
        <?php if(isset($menu_functions_search)) { ?>
            <div class="af2_menu_headline_search_component">
                <?php
                $filter_columns = null;
                if(is_array($menu_functions_search)) {
                    $filter_columns = '';
                    for($i = 0; $i < sizeof($menu_functions_search); $i++) {
                        if($i != 0) $filter_columns .= ';';
                        $filter_columns .= $menu_functions_search[$i];
                    }
                }
                else {
                    $filter_columns = $menu_functions_search;
                }
                ?>
                <input id="af2_search_filter" data-searchfiltercolumn="<?php echo esc_html($filter_columns); ?>" type="text" placeholder="<?php _e('Search...', 'funnelforms-free'); ?>">
                
                <div class="af2_menu_headline_search_component_icon"><i class="fas fa-search"></i></div>
            </div>
        <?php }; ?>
        
        <?php if(isset($menu_functions_button)) { ?>
            <?php if(isset($menu_functions_button['link'])) { ?>
            <a class="af2_btn_link" href="<?php _e($menu_functions_button['link']); ?>">
                <div id="<?php _e($menu_functions_button['triggerId']); ?>" class="af2_btn af2_btn_primary af2_menu_functions_button"><i class="<?php _e($menu_functions_button['icon']); ?>"></i><?php _e($menu_functions_button['label']) ; ?></div>
            </a>
            <?php } else { 

                ?>
                <div id="<?php _e($menu_functions_button['triggerId']); ?>" class="af2_btn af2_btn_primary af2_menu_functions_button 
                    <?php 
                        if(isset($menu_functions_button['modelTarget'])){
                            _e('af2_modal_btn') ;     
                        }else{
                            _e('') ;     
                        }
                    ?>" 
                <?php if(isset($menu_functions_button['dataAttributes']) ) { foreach($menu_functions_button['dataAttributes'] as $att => $dataAttr){ _e('data-'.$att.'="'.$dataAttr.'"'); } }; ?>><i class="<?php _e($menu_functions_button['icon']); ?>"></i><?php _e($menu_functions_button['label']) ; ?></div>
            <?php }; ?>
        <?php }; ?>

        <?php if(isset($menu_functions_select)) { 
                if($menu_functions_select['id']){
                    $menuSelect = $menu_functions_select['id'];
                }
                if($menu_functions_select['link']){
                    $menuSelectlink = $menu_functions_select['link'];
                }
                if($menu_functions_select['getattribute']){
                    $menuSelecattr = $menu_functions_select['getattribute'];
                } 
            ?>
            
            <div class="af2_menu_headline_select_component">
                <p class="af2_menu_functions_select_label"><?php _e($menu_functions_select['title']); ?></p>
                <select id="<?php _e(esc_html($menuSelect)) ; ?>" class="af2_menu_functions_select" data-link="<?php _e(esc_html($menuSelectlink)); ?>" data-getattribute="<?php _e(esc_html($menuSelecattr)) ; ?>">
                    <option value="all" 
                    <?php
                     if($menu_functions_select['selected'] === 'all'){
                                _e('selected');
                            }else{ _e(''); }
                    ?>><?php _e($menu_functions_select['all_label']); ?></option>
                    <?php foreach($menu_functions_select['options'] as $option) { 

                        if($option['value']){
                            $opvalue = $option['value'];
                        }

                        ?>
                    <option value="<?php _e(esc_html($opvalue)) ; ?>" 
                        <?php 
                        if($menu_functions_select['selected'] === strval($option['value'])){
                                _e('selected') ;
                            }else{ _e(''); }
                    ?>>
                    <?php _e($option['label']); ?></option>
                    <?php }; ?>
                </select>
            </div>
        <?php }; ?>
    </div>
</div>
