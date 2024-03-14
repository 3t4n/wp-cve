
<?php 
$menustab = ''; if($menu_blur_option == true){ $menustab = 'af2_blurred'; }
?>
<div class="af2_menu_sheet <?php _e($menustab); ?>">
    <div class="af2_menu_sheet_content">
        <div class="af2_card">
            <div class="af2_card_block">
                <?php include FNSF_AF2_MENU_HOOKS_SNIPPET; ?>
                <div class="af2_post_table">
                    <div class="af2_post_table_head">
                        <div class="af2_post_table_row">
                            <div class="af2_post_table_content af2_post_table_checkbox" style="width: 60px;"><input type="checkbox" class="af2_choose_all_table_objects"></div>

                            <?php foreach( $table_columns as $table_column ) { 

                                

                                    if(!(isset($table_column['hidden']) && $table_column['hidden'] == true)){
                                        $hide_column =  '' ;
                                    }else{
                                        $hide_column =  'af2_hide' ;
                                    }
                                    ?>
                            <div class="af2_post_table_content <?php _e($hide_column); ?>" style="width: <?php _e($table_column['width']); ?>; flex: <?php _e($table_column['flex']); ?>; min-width: <?php _e($table_column['min-width']); ?>; max-width: <?php _e($table_column['max-width']); ?>">
                                <h5><?php 
                                    
                                 if($table_column['lable'] == 'Form title (backend)'){ _e('Form title (backend)', 'funnelforms-free'); } 
                                 if($table_column['lable'] == 'WordPress shortcode'){ _e('WordPress shortcode', 'funnelforms-free'); } 
                                 if($table_column['lable'] == 'Popup shortcode'){ _e('Popup shortcode', 'funnelforms-free'); } 
                                 if($table_column['lable'] == 'External embed code'){ _e('External embed code', 'funnelforms-free');
                                 } 
                                 if($table_column['lable'] == 'Leads'){  _e('Leads', 'funnelforms-free');
                                 } 
                                 if($table_column['lable'] == 'Question title'){ _e('Question title', 'funnelforms-free');
                                 } 
                                 if($table_column['lable'] == 'Question type'){ _e('Question type', 'funnelforms-free');
                                 } 
                                 if($table_column['lable'] == 'Author'){  _e('Author', 'funnelforms-free');
                                 }
                                 if($table_column['lable'] == 'Date / Time'){ _e('Date / Time', 'funnelforms-free');
                                 } 
                                 if($table_column['lable'] == 'Name'){ _e('Name', 'funnelforms-free');
                                 } 
                                 if($table_column['lable'] == 'E-mail'){ _e('E-mail', 'funnelforms-free');
                                 } 
                                 if($table_column['lable'] == 'Phone'){ _e('Phone', 'funnelforms-free');
                                 } 
                                 if($table_column['lable'] == 'Details'){ _e('Details', 'funnelforms-free');
                                 } 
                                 if($table_column['lable'] == 'post_status'){ _e('post_status', 'funnelforms-free');
                                 } 
                                 if($table_column['lable'] == 'Title'){ _e('Title', 'funnelforms-free');
                                 } 
                                 if($table_column['lable'] == 'Date'){ _e('Date', 'funnelforms-free');
                                 } 
                                 if($table_column['lable'] == 'Time'){ _e('Time', 'funnelforms-free');
                                 } 
                                 if($table_column['lable'] == 'lead_id'){ _e('lead_id', 'funnelforms-free');
                                 }
                                 if($table_column['lable'] == 'Duration'){ _e('Duration', 'funnelforms-free');
                                 } 
                                 if($table_column['lable'] == 'ID'){ _e('ID', 'funnelforms-free');
                                 } 
                                 if($table_column['lable'] == 'Contact form title (backend)'){ _e('Contact form title (backend)', 'funnelforms-free');
                                 } 
                                 if($table_column['lable'] == 'Contact form title (frontend)'){ _e('Contact form title (frontend)', 'funnelforms-free');
                                 }
                                 if($table_column['lable'] == 'Category'){ _e('Category', 'funnelforms-free');
                                 } 

                                ?></h5>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="af2_post_table_body">
                        <?php foreach( $posts as $post ) { ?>
                        <?php 
                            $uid_label = null;
                            foreach($table_columns as $table_column) {
                                if($table_column['uid'] == true) $uid_label = $table_column['lable'];
                            }
                            $posttabrow = '' ; if(isset($post['error']) && $post['error'] == 'true'){ $posttabrow = 'af2_error_row'; }
                         ?>
                        <div id="<?php  _e($post[$uid_label]); ?>" class="af2_post_table_row <?php _e($posttabrow); ?>">
                            <div class="af2_post_table_content af2_post_table_checkbox" style="width: 60px;"><input type="checkbox" id="<?php _e($post[$uid_label]); ?>" class="af2_choose_table_object"></div>
                            <?php foreach( $table_columns as $table_column ) { 


                                if(!(isset($table_column['hidden']) && $table_column['hidden'] == true)){
                                        $hide_column =  '' ;
                                    }else{
                                         $hide_column =  'af2_hide' ;
                                    }
                                    $cltranslate = '';
                                    if($table_column['translate']){
                                        $cltranslate = $post[$table_column['lable']];
                                    }else{
                                        $cltranslate = $post[$table_column['lable']];
                                    }
                                ?>  
                            <div class="af2_post_table_content <?php _e($hide_column) ; ?>" style="width: <?php _e($table_column['width']) ; ?>; flex: <?php _e($table_column['flex']); ?>" data-searchfilter="<?php _e($table_column['lable']); ?>"
                            data-searchvalue="<?php _e($cltranslate); ?>">
                                <?php if(isset($table_column['select'])) { ?>
                                    <select style="padding: 0px 25px 0 10px !important;" class="<?php _e($table_column['select']['select_class']); ?>" data-elementid="<?php _e($post[$uid_label]); ?>">
                                        <option value="empty"><?php _e($table_column['select']['empty_value']); ?></option>
                                        <?php foreach($table_column['select']['selection_values'] as $option) {

                                                $cllable = '';
                                                if($post[$table_column['lable']] === $option['value']){
                                                    $cllable = 'selected';
                                                }else{
                                                    $cllable = '';
                                                }

                                         ?>
                                            <option value="<?php _e($option['value']); ?>" <?php _e($cllable)  ; ?>><?php _e($option['label']); ?></option>
                                        <?php } ?>
                                    </select>
                                <?php } else { ?>
                                <?php if(!$table_column['button']) {
                                      $tabcon = ''; if($table_column['highlight']){ $tabcon = 'af2_highlight'; }  
                                 ?>
                                <p class="table_content <?php _e($tabcon); ?>">
                                    <?php if(isset($table_column['url']) && $table_column['url'] && isset($table_builder_load_url) && isset($table_builder_load_url_id)) { ?>
                                        <?php 
                                            $actual_href = $table_builder_load_url.$post[$table_builder_load_url_id];
                                            if(isset($table_column['urlnum']) && $table_column['urlnum'] > -1) {
                                                $val = $table_builder_load_url_ids_array[$table_column['urlnum']];
                                                $actual_href = $val['page'].$post[$val['id']];
                                            }
                                       ; ?>
                                        <a class="af2_btn_link" href="<?php _e(sanitize_url($actual_href)); ?>">
                                        <?php

                                                $cl2translate = '';
                                                if($table_column['translate']){
                                                    $cl2translate = $post[$table_column['lable']];
                                                }else{
                                                    $cl2translate = $post[$table_column['lable']];
                                                }
                                         ?>
                                            <?php  _e($cl2translate) ; ?>
                                        </a>
                                        <?php } else { 
                                                    $cl3translate = '';
                                                if($table_column['translate']){
                                                    $cl3translate = $post[$table_column['lable']];
                                                }else{
                                                    $cl3translate = $post[$table_column['lable']];
                                                }
                                            ?>
                                        <?php _e($cl3translate); ?>
                                        <?php } ?>
                                </p>
                                <?php } else { ?>
                                <div class="table_content">
                                    <?php if(isset($table_column['url']) && $table_column['url'] && isset($table_builder_load_url) && isset($table_builder_load_url_id)) { ?>
                                        <?php 
                                            $actual_href = $table_builder_load_url.$post[$table_builder_load_url_id];
                                            if(isset($table_column['urlnum']) && $table_column['urlnum'] > -1) {
                                                $val = $table_builder_load_url_ids_array[$table_column['urlnum']];
                                                $actual_href = $val['page'].$post[$val['id']];
                                            }
                                        ?>
                                        <a class="af2_btn_link" href="<?php _e($actual_href); ?>">
                                            <div class="af2_btn af2_btn_<?php _e($table_column['buttonclass']); ?>">
                                                <?php
                                                         $cl4translate = '';
                                                        if($table_column['translate']){
                                                            $cl4translate = $post[$table_column['lable']];
                                                        }else{
                                                            $cl4translate = $post[$table_column['lable']];
                                                        }


                                                 ?>
                                                <?php _e($cl4translate); ?>
                                            </div>
                                        </a>
                                        <?php } else { 

                                                        $cl5btn = '';
                                                        if(isset($table_column['btn_disabled']) && $table_column['btn_disabled'] == true){
                                                            $cl5btn = 'af2_btn_disabled';
                                                        }else{
                                                            $cl5btn = '';
                                                        }
                                                        $cl6btn = '';
                                                        if(isset($table_column['buttonid']) && $table_column['buttonid']){
                                                            $cl6btn = 'data-objectid="'.$post[$uid_label].'"';
                                                        }else{
                                                            $cl6btn = '';
                                                        }


                                            ?>
                                            <div class="af2_btn af2_btn_<?php _e($table_column['buttonclass']); ?> <?php _e($cl5btn) ; ?>" <?php _e($cl6btn) ; ?>>
                                            <?php if(isset($table_column['btn_disabled']) && $table_column['btn_disabled'] == true) { ?>
                                                <div class="af2_pro_sign"><i class="fas fa-star"></i><?php _e('PRO', 'funnelforms-free'); ?></div>
                                            <?php } 

                                                        $cl5translate = '';
                                                        if($table_column['translate']){
                                                            $cl5translate = $post[$table_column['lable']];
                                                        }else{
                                                            $cl5translate = $post[$table_column['lable']];
                                                        }

                                            ?>
                                            <?php _e($cl5translate) ; ?>
                                            </div>
                                        <?php } ?>
                                </div>
                                <?php } ?>
                                <?php } ?>
                            </div>
                            <?php } ?>
                        </div>
                        <?php } ?>
                    </div>


                    <?php
                    if($this->pagination) {
                        $pageOffset = isset($_GET['page_offset']) ? intval(sanitize_text_field($_GET['page_offset'])) : 0;
                        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
                        $full_url = $protocol."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        ?>
                        <div class="af2_pagination">
                            <?php
                            $url = addToURL('page_offset', max(($pageOffset-1),0), $full_url);
                            ?>
                            <a href="<?php echo esc_url($url); ?>">
                                <div class="af2_pagination-btn af2_btn af2_btn_primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                                    </svg>
                                </div>
                            </a>

                            <div class="af2_page_indices" style="display: flex; color: #ffffff;">
                                <?php
                                for($i=0; $i<$this->numPages; $i++) {
                                    $url = addToURL('page_offset', $i, $full_url);
                                    echo "<a href='".esc_url($url)."'>";
                                    if($pageOffset == $i) {
                                        echo "<div class='af2_page_index af2_page_index_current'>" . esc_html(($i + 1)) . "</div>";
                                    } else {
                                        echo "<div class='af2_page_index'>" . esc_html(($i + 1)) . "</div>";
                                    }
                                    echo "</a>";
                                }
                                ?>
                            </div>
                            <?php
                            $url = addToURL('page_offset', min(($pageOffset+1),($this->numPages-1)), $full_url);
                            ?>
                            <a href="<?php echo $url; ?>">
                                <div class="af2_pagination-btn af2_btn af2_btn_primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                </div>
                            </a>
                        </div>
                        <?php
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
    <?php include FNSF_AF2_MENU_SIDEBAR; ?>

    <?php if( $custom_template != null ) include $custom_template; ?>
</div>


<?php if($menu_blur_option == true) { ?>
    <div class="af2_decide_pro">
        <div class="af2_decide_pro_div">
            <h1><?php _e('This function is only included in Funnelforms Pro!', 'funnelforms-free'); ?></h1>
            <h1 style="display: none;"><?php _e('Choose Pro', 'funnelforms-free'); ?></h1>
            <h5 style="
    text-align: center;
    margin-top: 10px;
    margin-bottom: 50px;
    white-space: break-spaces;
    max-width: 60%;
"><?php _e('Note: The Funnelforms Free and Pro version are two different plugins. You will receive the download link after purchasing the Pro version and then you can upload the plugin to your WordPress website.', 'funnelforms-free') ?></h5>
            <a class="af2_btn_link" target="_blank" href="https://www.funnelforms.io/gopro">
                <div class="af2_btn af2_btn_primary"><?php _e('Upgrade to Pro Version', 'funnelforms-free'); ?></div>
            </a>
        </div>
    </div>
<?php }; ?>
