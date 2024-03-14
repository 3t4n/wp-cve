<div class="af2_builder">
    <div class="af2_builder_wrapper">
        <div class="af2_toast_wrapper"></div>

        <?php if(isset($builder_sidebar_data)) { 
                $buildToast = '';
                if($builder_sidebar_select_filter != null){ $buildToast = 'af2_select_filter'; }

            ?>
        <div class="af2_builder_sidebar colorOne leftSidebar <?php _e($buildToast) ;?>">
            <div>
                <div class="af2_builder_overflow-scroll">
                    <div class="af2_builder_sidebar_header af2_icon_text af2_builder_element">
                        <div class="af2_icon_wrapper colorPrimary"><i class="<?php _e($builder_sidebar_data['icon']); ?>"></i></div>
                        <h4><?php _e($builder_sidebar_data['label'], 'funnelforms-free'); ?></h4>
                    </div>
                    <?php if($builder_sidebar_select_filter != null) { ?>
                    <div class="af2_builder_sidebar_select_filter">
                        <select style="padding: 0px 25px 0 10px !important;" class="<?php  _e($builder_sidebar_select_filter['select_class']); ?>">
                            <option value="empty"><?php _e($builder_sidebar_select_filter['empty_value'], 'funnelforms-free'); ?></option>
                            <?php foreach($builder_sidebar_select_filter['selection_values'] as $option) { ?>
                                <option value="<?php _e($option['value']); ?>"><?php _e($option['label']); ?></option>
                            <?php }; ?>
                        </select>
                    </div>
                    <?php }; ?>
                    <div class="af2_builder_sidebar_content_wrapper af2_builder_element af2_no_padding">
                        <?php foreach($builder_sidebar_content_elements as $builder_sidebar_content_element) {  


                            if(isset($builder_sidebar_content_element['disabled']) && $builder_sidebar_content_element['disabled'] == true){
                                $funnelBulder_first =  'af2_disabled_sidebar_element' ;
                            }else{
                                $funnelBulder_first =  '' ;
                            }


                            if(isset($builder_sidebar_content_element['icon'])){
                                $funnelBulder_Secound = 'af2_flex_sidebar_heading' ;
                            }else{
                                $funnelBulder_Secound =  '' ;
                            }

                            if(isset($builder_sidebar_content_element['select_value'])){
                                $funnelBulder_third = $builder_sidebar_content_element['select_value'] ;
                            }else{
                                $funnelBulder_third =  null ;
                            }
 


                            ?>

                            <div class="af2_builder_sidebar_content af2_builder_sidebar_element <?php _e($funnelBulder_first)  ; ?>  <?php _e($builder_sidebar_content_element_class) ; ?> <?php _e($funnelBulder_Secound)  ;  ?>"
                            data-elementid="<?php _e($builder_sidebar_content_element['elementid']) ; ?>" data-selectvalue="<?php _e($funnelBulder_third)  ;  ?>">
                            <?php if(isset($builder_sidebar_content_element['disabled']) && $builder_sidebar_content_element['disabled'] == true) { ?>
                                <div class="af2_pro_sign"><i class="fas fa-star"></i><?php _e('PRO', 'funnelforms-free'); ?></div>
                            <?php }; ?>
                            <?php if(isset($builder_sidebar_content_element['icon'])) { ?>
                                <i class="<?php _e($builder_sidebar_content_element['icon']) ; ?>"></i>
                            <?php }; ?>
                            <h5 class="af2_builder_sidebar_content_heading"><?php _e($builder_sidebar_content_element['label'], 'funnelforms-free'); ?></h5>
                            <?php if(isset($builder_sidebar_content_element['image'])) { ?>
                            <div class="af2_builder_sidebar_image">
                                <img src="<?php _e(plugins_url($builder_sidebar_content_element['image'], AF2F_PLUGIN)) ; ?>">
                            </div>
                            <?php }; ?>
                        </div>
                        <?php }; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php }; ?>
        <div class="af2_builder_content_wrapper">
            <div class="af2_builder_header af2_builder_element">
                <?php if($builder_pre_heading_buttons != null) { ?>
                    <div class="af2_builder_pre_heading_buttons">
                    <?php foreach($builder_pre_heading_buttons as $builder_pre_heading_button) { ?>
                        <div id="<?php _e($builder_pre_heading_button['id']) ; ?>" class="af2_btn af2_btn_primary"><i class="<?php _e($builder_pre_heading_button['icon']) ; ?>"></i></div>
                    <?php }; ?>
                    </div>
                <?php }; ?>
                <div class="af2_builder_header_heading af2_icon_text">
                    <div class="af2_icon_wrapper colorBlue"><i class="<?php _e($builder_heading['icon']) ; ?>"></i></div>
                    <h4><?php _e($builder_heading['label'], 'funnelforms-free'); ?></h4>
                </div>
                <div class="af2_builder_header_components">

                    <?php if(isset($menu_builder_pre_control_buttons)) { ?>
                        <?php foreach($menu_builder_pre_control_buttons as $button)  { ?>
                        <div id="<?php _e($button['id']) ; ?>" class="af2_btn af2_btn_primary">
                            <i class="<?php _e($button['icon']) ; ?>"></i>
                            <?php _e($button['label'], 'funnelforms-free'); ?></div>
                        <?php }; ?>
                    <?php }; ?>

                    <?php
                            $buttontext = __('Exit editor', 'funnelforms-free');
                            $close_url = $close_editor_url;

                            if(isset($_GET['navigateBackBuilder']) && isset($_GET['navigateBackID'])) {
                                $BackBuilder = sanitize_text_field($_GET['navigateBackBuilder']);
                                $navigateBackID = sanitize_text_field($_GET['navigateBackID']) ;
                                $buttontext = __('Back to Form-Editor', 'funnelforms-free');
                                $close_url = admin_url('/admin.php?page='.$BackBuilder.'&id='.$navigateBackID);
                            }
                   ; ?>
                    <a class="af2_btn_link" href="<?php _e($close_url); ?>">
                        <div id="af2_close_editor" class="af2_btn af2_btn_primary"><i class="fas fa-times-circle"></i><?php _e($buttontext) ; ?></div>
                    </a>
                    <?php if(isset($menu_builder_control_buttons)) { ?>
                        <?php foreach($menu_builder_control_buttons as $menu_builder_control_button)  {

                            if(isset($builder_sidebar_data)){
                                $funnelBulder_fourth = 'margin-lr' ;
                            }else{
                                $funnelBulder_fourth = '' ;   
                            }

                                ?>
                        <div id="<?php _e($menu_builder_control_button['id']) ; ?>" class="af2_btn af2_btn_primary">
                            <i class="<?php _e($menu_builder_control_button['icon']) ; ?>"></i>
                            <?php _e($menu_builder_control_button['label'])  ; ?></div>
                        <?php }; ?>
                    <?php }; ?>
                    <div id="<?php _e($af2_own_save_button_id) ; ?>" class="af2_btn af2_btn_primary"><i class="fas fa-save"></i><?php _e('Save', 'funnelforms-free'); ?></div>
                </div>
            </div>

            <div class="dragscroll af2_builder_content af2_card <?php _e( $funnelBulder_fourth)  ; ?>">
                <div class="af2_card_block af2_builder_workspace">
                    <?php include $builder_template; ?>
                </div>
            </div>
        </div>

        <?php if(isset($builder_sidebar_edit)) { ?>
        <div class="af2_builder_sidebar colorOne rightSidebar editSidebar hide">
            <div>
                <div class="af2_builder_overflow-scroll">
                    <div class="af2_builder_sidebar_header af2_icon_text af2_builder_element">
                        <div class="af2_btn af2_btn_primary af2_control_button unsummonEditSidebar"><i class="fas fa-times"></i></div>
                        <h4><?php _e($builder_sidebar_edit['label'])  ; ?></h4>
                    </div>
                    <div class="af2_builder_sidebar_content_wrapper af2_builder_element af2_no_padding">
                    </div>
                </div>
            </div>
        </div>
        <?php }; ?>
    </div>
</div>

<div id="af2_save_modal" class="af2_modal"
    data-class="af2_save_modal"
    data-target="af2_save_modal"
    data-sizeclass="moderate_size"
    data-bottombar="false"
    data-heading="<?php _e('Error log', 'funnelforms-free'); ?>"
    data-close="<?php _e('Close', 'funnelforms-free'); ?>">

  <!-- Modal content -->
  <div class="af2_modal_content">
    
  </div>
</div>
