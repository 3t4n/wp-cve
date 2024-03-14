<?php 
use Adminz\Admin\Adminz as Adminz;
use Adminz\Helper\ADMINZ_Helper_Flatsome_Portfolio as ADMINZ_Helper_Flatsome_Portfolio;
use Adminz\Admin\ADMINZ_Flatsome as ADMINZ_Flatsome;

add_action('ux_builder_setup', 'adminz_flatsome_portfolios_form_builder');
function adminz_flatsome_portfolios_form_builder(){        

    $tax_arr = ADMINZ_Flatsome::get_arr_tax();    
    $key_arr = ADMINZ_Flatsome::get_arr_meta_key();    
    
    $all = array_merge(
        ['search'=>'Type to search'], 
        $tax_arr,
        ['view_more'=>"View more"],
        ['submit'=> 'Submit'],
        $key_arr
    );
    $all = array_filter($all);

    add_ux_builder_shortcode('adminz_flatsome_portfolios_form', array(
        'name'      => "Search form ". ADMINZ_Helper_Flatsome_Portfolio::$customname,
        'category'  => Adminz::get_adminz_menu_title(),
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'search' . '.svg',
        'info'      => '{{ id }}',
        'options' => array(
            'taxgroup'=>array(
                'type' => 'group',
                'heading'   =>'Fields',
                'options'=>   [
                    'tax'=> [
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Select fields',        
                        'default'=> 'search,submit',
                        'config' => array(
                            'multiple' => true,
                            'sortable'    => true,
                            'placeholder' => 'Select..',
                            'options' => $all
                        ),
                    ],
                    /*'metakey'=> [
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Select Metakey',                        
                        'config' => array(
                            'multiple' => true,
                            'placeholder' => 'Select..',
                            'options' => $key_arr
                        ),
                    ],*/
                ]        
            ),
            'target'=>array(
                'type'=>'group',
                'heading'=>'Target',
                'options'=>array(
                    'target'  => array(
                    'type'    => 'select',
                    'heading' => 'Page target',
                    'default' => '',
                    'options' => ux_builder_get_page_parents(),
                ),
                )
            ),
            'appearance'=>array(
                'type' => 'group',
                'heading'   =>'Appearance'  ,
                'options'=> [
                    'metakey_relationship'=>array(
                        'type'=> 'checkbox',
                        'default'=> 'true',
                        'heading'=> 'Relationship of meta key fields'
                    ),
                    'field_before_title'=>array(
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Field before title',
                        'options' => $all
                    ), 
                    'field_after_title'=>array(
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Field after title',
                        'options' => $all
                    ), 
                    'selectnone'=>array(
                        'type' => 'textfield',
                        'heading'   =>'Select none text',
                        'default'=> "",                        
                    ),
                    'search_placeholder'=>array(
                        'type' => 'textfield',
                        'heading'   =>'Search placeholder',
                        'default'=> __( 'Enter a search term and press enter', 'administrator-z' ),                        
                    ),
                    'item_col_large' => array(
                        'type'       => 'slider',
                        'heading'    => 'Items per row',
                        'description' => 'On Large screen',
                        'default'    => '3',
                        'min'   => 1,
                        'max'   => 12
                    ),
                    'item_col_small' => array(
                        'type'       => 'slider',
                        'heading'    => 'Items per row',
                        'description' => 'On Small screen',
                        'default'    => '1',
                        'min'   => 1,
                        'max'   => 12
                    ),
                    'fields_12_col'=>array(
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Field col 12 cols',
                        'description'=> 'Choose field to 12 cols',
                        'config' => array(
                            'multiple' => true,
                            'placeholder' => 'Select..',
                            'options' => $all
                        ),       
                    ),
                    'field_view_more'=>array(
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Field view more',
                        'description'=> 'Showing when click toggle button',
                        'config' => array(
                            'multiple' => true,
                            'placeholder' => 'Select..',
                            'options' => $all
                        ),       
                    ),
                    'view_more_text_1'=>array(
                        'type' => 'textfield',
                        'heading'   =>'View more text 1',
                        'default'=> 'view more'
                    ),
                    'view_more_text_2'=>array(
                        'type' => 'textfield',
                        'heading'   =>'View more text 2',
                        'default'=> 'view less'
                    ),
                    'closerow_before'=>array(
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Close row before',
                        'description'=> 'Choose field to close row',
                        'config' => array(
                            'multiple' => true,
                            'placeholder' => 'Select..',
                            'options' => $all
                        ),       
                    ),                    
                ],
            ),
        ),
    ));
}
add_shortcode('adminz_flatsome_portfolios_form', 'adminz_flatsome_portfolios_form_function');
function adminz_flatsome_portfolios_form_function($atts, $content = null ){        
    extract(shortcode_atts(array(
        '_id'=>rand(),   
        'tax'=> 'submit,search',
        //'metakey'=> '',
        'target' => '',
        'selectnone' => '',
        'search_placeholder'=> __( 'Enter a search term and press enter', 'administrator-z' ),
        'item_col_large' => '3',
        'item_col_small' => '1',
        'fields_12_col' => '',
        'closerow_before'=> '',
        'field_view_more'=> '', 
        'view_more_text_1'=> 'view more',
        'view_more_text_2'=> 'view less',
        'metakey_relationship'=> true,
        'field_before_title'=>'',
        'field_after_title'=>''
    ), $atts));
    ob_start();
    
    ?>
    <style type="text/css">.adminz_portfolio_form input, .adminz_portfolio_form select, .adminz_portfolio_form textarea{margin-bottom: 0px;}.adminz_portfolio_form .viewmore .icon-angle-up{display: none;}.adminz_portfolio_form .viewmore.toggled .icon-angle-up{display: inline-block;} .adminz_portfolio_form .viewmore.toggled .icon-angle-down{display: none;}@media (min-width:  550px){.adminz_portfolio_form .field-title{display: flex; flex-direction: row-reverse; } .adminz_portfolio_form .field-title.has-left-title .relative .relative>*{margin-left:  -1px;} }@media (max-width:  549px){.adminz_portfolio_form .absolute.v-center.right{position: static !important; transform:  unset !important; } }</style>
    <form id="<?php echo esc_attr($_id); ?>" class="adminz_portfolio_form mb-0" method="get" action="<?php echo get_permalink($target);?>">        
        <div class="row">
        <?php  
            $tax = array_filter(explode(",", $tax));   
            
            //$metakey = array_filter(explode(",", $metakey));
            $key_arr = ADMINZ_Woocommerce::get_arr_meta_key();    
            $metakey = [];
            if(!empty($tax) and is_array($tax)){
                foreach ($tax as $key => $value) {
                    if(array_key_exists($value,$key_arr)){
                        $metakey[] = $value;
                    }
                }
            }
            $sqlresult = ADMINZ_Helper_Flatsome_Portfolio::adminz_get_all_meta_key_value('featured_item',$metakey);

            $fields_12_col = array_filter(explode(',', $fields_12_col));
            $closerow_before = array_filter(explode(',', $closerow_before));
            $item_col_class = "col small-".(12/$item_col_small). " large-".(12/$item_col_large);
            $field_view_more = $field_view_more? explode(',',$field_view_more) : [];

            $fields = array_values($tax);            


            // sap xep lai fields theo view_more de khi click de~ nhin
            if(!empty($field_view_more) and is_array($field_view_more)){
                foreach ($field_view_more as $key => $value) {
                    if(in_array($value,$fields)){
                        unset($fields[array_search($value, $fields)]);
                        $fields[]  = $value;
                    }
                }
            }

            // luon luon chuyen viewmore và submit xuong duoi cung
            if(in_array('view_more',$fields)){
                unset($fields[array_search('view_more', $fields)]);
            }
            $fields[] = 'view_more';
            if(in_array('submit',$fields)){
                unset($fields[array_search('submit', $fields)]);
            }
            $fields[] = 'submit';            

            if(!empty($fields) and is_array($fields)){                
                foreach ($fields as $value) {
                    $col = $item_col_class;
                    if(in_array($value,$fields_12_col)){
                        $col = "col small-".(12/$item_col_small). " large-12";
                    }
                    if(in_array($value,$closerow_before)){
                        echo "</div><div class='row'>";
                    } 
                    if(in_array($value,$field_view_more)){
                        $col.= " hidden col_view_more";
                    }
                    // search
                    if($value  == 'search'){                        
                        ?>
                        <div class="<?php echo esc_attr($col);?>">
                            <div class="col-inner">
                                <div class="field-title <?php if($field_before_title){ echo 'has-left-title';} ?>">
                                    <div class="relative full-width">
                                        <div class="relative">
                                            <input type="text" name="search" placeholder="<?php echo esc_attr($search_placeholder); ?>" style="padding-left:  40px; " value="<?php if(isset($_GET['search'])) echo esc_attr($_GET['search']); ?>">
                                            <i class="icon-search absolute v-center left op-6" style="margin-left: 15px;"></i>
                                        </div>
                                        <?php 
                                        if($field_after_title){
                                            $after_class = "";
                                            if($field_after_title == 'submit' or $field_after_title == 'view_more'){
                                                $after_class = "hide-for-small";
                                            }
                                            echo '<div class="absolute v-center right '.esc_attr($after_class).'">'; 
                                            echo admz_portfolio_form_get_sub_field($field_after_title,$tax,$metakey,"col small-12 large-12",$selectnone,$_GET,$field_view_more,"col small-12 large-12",$view_more_text_1,$view_more_text_2,$field_after_title,$field_before_title);
                                            echo '</div>';
                                        }
                                        ?> 
                                    </div>
                                    <?php 
                                    if($field_before_title){
                                        echo '<div class="left-title">';                                                    
                                        echo admz_portfolio_form_get_sub_field($field_before_title,$tax,$metakey,'col small-12 large-12',$selectnone,$_GET,$field_view_more,$item_col_class,$view_more_text_1,$view_more_text_2,$field_after_title,$field_before_title);
                                        echo '</div>';
                                    }                                        
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    } 
                    if(array_key_exists($value,ADMINZ_Flatsome::get_arr_tax())){
                        echo admz_portfolio_form_field_tax($value, $col, $selectnone, $_GET);
                    }                    
                    
                    // metakeys                    
                    if(array_key_exists($value,ADMINZ_Woocommerce::get_arr_meta_key())){   
                        echo admz_portfolio_form_field_metakey($value, $col, $selectnone, $_GET,$sqlresult);
                    }

                    // view_more
                    if($value == 'view_more'){                        
                        echo admz_portfolio_form_field_view_more($field_view_more,$col, $view_more_text_1,$view_more_text_2,$field_after_title,$field_before_title,true);                        
                    }
                    // submit
                    if($value == 'submit'){
                        echo admz_portfolio_form_field_submit($col,$field_after_title,true);
                    }                                  
                }
            }            
                      
            if($metakey_relationship){
                $relationship_metakey_value = ['keys'=>[] , 'values'=> []];
                if(!empty($sqlresult) and is_array($sqlresult)){
                    foreach ($sqlresult as $key => $value) {
                        if(!in_array($value->meta_key,$relationship_metakey_value['keys'])){
                            $relationship_metakey_value['keys'][] = trim($value->meta_key); 
                        }
                        if(!in_array($value->ID,$relationship_metakey_value['values'])){
                            $relationship_metakey_value['values'][$value->ID][] = trim($value->meta_value); 
                        }
                    }
                }    
                $relationship_metakey_value['values'] = array_values($relationship_metakey_value['values']);
                echo '<div class="relationship_metakey_value" style="display: none">'.json_encode($relationship_metakey_value).'</div>';
            }
            
        ?>
        </div>
    </form>
    <script type="text/javascript"> 
    window.addEventListener('DOMContentLoaded', function() {
        (function($){
            var adminz_portfolio_form = $('.adminz_portfolio_form'); 
            $(adminz_portfolio_form).submit(function() {
                $(this).find("input,select,textarea").each(function(){
                    if($(this).val() == ""){$(this).prop('disabled', true); };
                     }); 
            });
            $(adminz_portfolio_form).find('.viewmore').on('click',function(){
                $(this).closest(adminz_portfolio_form).find('.col_view_more').toggleClass('hidden');
                var current_text = $(this).find('span').text();
                $(this).find('span').text($(this).data('text'));
                $(this).data('text',current_text);
                $(this).toggleClass('toggled');
            });
            $(adminz_portfolio_form).find('button[type="reset"]').on("click",function(e){
                $(this).closest(adminz_portfolio_form).find('input[type="hidden"]').val("");                
            });

            <?php if($metakey_relationship){ ?>
            $('.adminz_portfolio_form').each(function(){
                var relationship_data = JSON.parse($(this).find(".relationship_metakey_value").text());
                $(this).find("select").change(function(){
                    var meta_key = $(this).attr('name');                    
                    var key = relationship_data.keys.indexOf(meta_key);
                    if(key == -1) {return;}
                    var keytarget = key + 1;
                    if(keytarget< relationship_data.keys.length){
                        var current_value = $(this).val();
                        var meta_key_target = relationship_data.keys[keytarget];
                        if($('select[name="'+meta_key_target+'"]').length){
                            $('select[name="'+meta_key_target+'"] option').hide();
                            $('select[name="'+meta_key_target+'"] option:first-child').show();
                            $('select[name="'+meta_key_target+'"]').val("").change();
                            for (var i = 0; i < relationship_data.values.length; i++) {                            
                                if(relationship_data.values[i][key] == current_value){
                                    var target_value = relationship_data.values[i][keytarget];
                                    $('select[name="'+meta_key_target+'"] option[value="'+target_value+'"]').show();
                                }                            
                            }
                        }                        
                    }
                });                
            });
            <?php } ?>
        })(jQuery); }); 
    </script>
    <?php
    $return = apply_filters('adminz_output_debug',ob_get_clean());    
    return $return;
}
function admz_portfolio_form_get_sub_field($value,$tax,$metakey,$col,$selectnone,$get,$field_view_more,$item_col_class,$view_more_text_1,$view_more_text_2,$field_after_title,$field_before_title){
    echo '<div class="row row-collapse">';
    // taxonomy    
    if(array_key_exists($value,ADMINZ_Flatsome::get_arr_tax())){
        echo admz_portfolio_form_field_tax($value, $col, $selectnone, $get);
    }                    
    
    // metakeys                    
    if(array_key_exists($value,ADMINZ_Woocommerce::get_arr_meta_key())){   
        echo admz_portfolio_form_field_metakey($value, $col, $selectnone, $get,$sqlresult);
    }

    // view_more
    if($value == 'view_more'){
        echo admz_portfolio_form_field_view_more($field_view_more,$item_col_class, $view_more_text_1,$view_more_text_2,$field_after_title,$field_before_title,false);
    }
    // submit
    if($value == 'submit'){
        echo admz_portfolio_form_field_submit($col,$field_after_title,false);
    }       
    echo '</div>';
}
function admz_portfolio_form_field_submit($item_col_class,$field_after_title,$is_main_field = true){
    ob_start();
    if($field_after_title == 'submit' and $is_main_field){
        $item_col_class .= " show-for-small";
    }
    ?>
    <div class="<?php echo esc_attr($item_col_class);?>">
        <button type="submit" class="<?php echo apply_filters('adminz_woo_form_submit_class','ux-search-submit submit-button secondary button icon ') ?>" aria-label="<?php echo __( 'Submit', 'administrator-z' ); ?>">
            <?php echo __( 'Search', 'administrator-z' ); ?>                     
        </button>
    </div>
    <?php
    return ob_get_clean();
}
function admz_portfolio_form_field_view_more($field_view_more,$item_col_class,$view_more_text_1,$view_more_text_2,$field_after_title,$field_before_title,$is_main_field = true){
    ob_start();
    if(!empty($field_view_more) and is_array($field_view_more)){
        if($field_after_title == 'view_more' and $is_main_field){
            $item_col_class .= " show-for-small";
        }
        ?>
        <div class="<?php echo esc_attr($item_col_class); ?>">
            <p>
            <button type="button" class="button is-link  is-smaller op-6 viewmore" data-text="<?php echo esc_attr($view_more_text_2); ?>"><span><?php echo esc_attr($view_more_text_1); ?></span> <i class="icon-angle-up"></i> <i class="icon-angle-down"></i></button>
            <button type="reset" class="button is-link  is-smaller op-6 reset">
                    Reset
                </button>
            </p>
        </div>
        <?php
    }
    return ob_get_clean();
}
function admz_portfolio_form_field_metakey($value, $col, $selectnone, $get ,$sqlresult){
    ob_start();
    $list_all_meta_value = [];
    if(!empty($sqlresult) and is_array($sqlresult)){
        foreach ($sqlresult as $result) {
            if($result->meta_key == $value){
                if(!in_array(trim($result->meta_value),$list_all_meta_value)){                                
                    if($result->meta_value){
                        $list_all_meta_value[] = trim($result->meta_value);
                    }
                }
            }
        }
    }
    $metalabel = ADMINZ_Helper_Flatsome_Portfolio::get_meta_key_label($value);
    if($metalabel){
    ?>
    <div class="<?php echo esc_attr($col);?>">
        <select name="<?php echo esc_attr($value); ?>">
            <option value=""><?php echo esc_attr($selectnone)." ".esc_attr($metalabel) ;?> </option>
            <?php 
            if(!empty($list_all_meta_value) and is_array($list_all_meta_value)){
                foreach ($list_all_meta_value as $meta_value) {            
                    if(isset($_GET[$value]) and (sanitize_text_field($_GET[$value]) == $meta_value)){
                        $selected = "selected";
                    }else{
                        $selected = "";
                    }                    
                    echo '<option '.esc_attr($selected).' value="'.esc_attr($meta_value).'">'.esc_attr($meta_value).'</option>';
                }
            }
            ?>
        </select>
    </div>
    <?php
    }
    return ob_get_clean();
}
function admz_portfolio_form_field_tax($value,$col,$selectnone,$get){
    ob_start();
    $taxobj = get_taxonomy($value);
    if($taxobj and $taxobj->label){
    ?>
    <div class="<?php echo esc_attr($col);?>">
        <?php  
        $get_terms = get_terms($value,array('hide_empty' => false));    
        ?>
        <select name="<?php echo esc_attr($value); ?>">
            <option value=""><?php echo esc_attr($selectnone)." ".esc_attr($taxobj->label) ;?> </option>
            <?php 
            if(!empty($get_terms) and is_array($get_terms)){                            
                foreach ($get_terms as $key => $term) {                                
                    if(isset($get[$value]) and ($get[$value] == $term->slug)){
                        $selected = "selected";
                    }else{
                        $selected = "";
                    }
                    echo '<option '.esc_attr($selected).' value="'.esc_attr($term->slug).'">'.esc_attr($term->name).'</option>';
                }
            }
            ?>
        </select>
    </div>
    <?php
    }
    return ob_get_clean();
}