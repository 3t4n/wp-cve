<?php 
use Adminz\Admin\Adminz as Adminz;
use Adminz\Admin\ADMINZ_Woocommerce as ADMINZ_Woocommerce;
use Adminz\Admin\ADMINZ_Flatsome as ADMINZ_Flatsome;
use Adminz\Helper\ADMINZ_Helper_Woocommerce_Taxonomy; 
use Adminz\Helper\ADMINZ_Helper_ACF;
use Adminz\Helper\ADMINZ_Helper_ACF_THX; 



add_action('ux_builder_setup', 'adminz_woo_form');
function adminz_woo_form(){        
    
    if(!Adminz::is_woocommerce()) return;    
    $optionattr2 = ADMINZ_Woocommerce::get_arr_attributes();    
    $tax_arr = ADMINZ_Woocommerce::get_arr_tax();    
    $key_arr = ADMINZ_Woocommerce::get_arr_meta_key('product');

    $all = apply_filters('adminz_woo_form_fields',array_merge($tax_arr,$key_arr));
    $all['view_more'] = "View more";
    $all['submit'] = "Submit";    

    $all = array_filter($all);
    

    $settings =  array(
        'name'      => "Search form ".__('Product','administrator-z'),
        'category'  => Adminz::get_adminz_menu_title(),
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'search' . '.svg',
        'info'      => '{{ id }}',
        'options' => array(
            'taxgroup'=>array(
                'type' => 'group',
                'heading'   =>'Fields',
                'options'=>   [
                    'fields'=> [
                        'type' => 'select',
                        'param_name' => 'slug',
                        'default'=> 'title,submit',
                        'heading' => 'Select taxonomies',
                        'config' => array(
                            'multiple' => true,
                            'sortable'    => true,
                            'placeholder' => 'Select..',
                            'options' => $all
                        ),
                    ],
                    'query_type_or'=>array(
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Query type OR',
                        'description'=> 'Only for Product attributes',
                        'config' => array(
                            'multiple' => true,
                            'placeholder' => 'Select..',
                            'options' => $optionattr2
                        ), 
                    ),
                    'fixed_field'=>  [
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Fixed field',
                        'options' => $all
                    ],
                ]        
            ),
            'appearance'=>array(
                'type' => 'group',
                'heading'   =>'Appearance'  ,
                'options'=> [
                    'col_spacing' => array(
                        'type' => 'radio-buttons',
                        'heading' => 'Column Spacing',
                        'full_width' => true,
                        'default' => '',
                        'options' => array(
                            '' => array( 'title' => 'Normal'),
                            'small' => array( 'title' => 'Small' ),
                            'large' => array( 'title' => 'Large' ),
                            'collapse' => array( 'title' => 'Collapse' ),
                        ),
                    ),
                    'style'=>array(
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Use button field',
                        'config' => array(
                            'multiple' => true,
                            'placeholder' => 'Select..',
                            'options' => $all
                        ), 
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
                    /*'global_filter_price'=>[
                        'type'=>'checkbox',
                        'heading'=> 'Filter price step',
                        'description'=> 'Use global steps in Settings in Tool-> administrator Z-> woocommerce',
                        'default'=> ''
                    ],                   
                    'step'=>array(
                        'type' => 'textfield',
                        'heading'   =>'Step',
                        'default'=> '',
                        'conditions' => 'filter_price == ""',
                    ),       */              
                    'selectall'=>array(
                        'type' => 'textfield',
                        'heading'   =>'Select all text',
                        'default'=> __("Select all",'administrator-z')
                    ),
                    'search_placeholder'=>array(
                        'type' => 'textfield',
                        'heading'   =>'Select placeholder',
                        'default'=> __( 'Enter a search term and press enter', 'administrator-z' )
                    ),
                    'selectnone'=>array(
                        'type' => 'textfield',
                        'heading'   =>'Select none text',
                        'default'=> __("Search")
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
                    'field_col_12'=>array(
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Field 12 cols',
                        'description'=> 'Choose field to 12 cols',
                        'config' => array(
                            'multiple' => true,
                            'placeholder' => 'Select..',
                            'options' => $all
                        ),       
                    ),
                    'field_col_6'=>array(
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Field 6 cols',
                        'description'=> 'Choose field to 6 cols',
                        'config' => array(
                            'multiple' => true,
                            'placeholder' => 'Select..',
                            'options' => $all
                        ),       
                    ),
                    'field_col_4'=>array(
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Field 4 cols',
                        'description'=> 'Choose field to 4 cols',
                        'config' => array(
                            'multiple' => true,
                            'placeholder' => 'Select..',
                            'options' => $all
                        ),       
                    ),
                    'field_col_3'=>array(
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Field 3 cols',
                        'description'=> 'Choose field to 3 cols',
                        'config' => array(
                            'multiple' => true,
                            'placeholder' => 'Select..',
                            'options' => $all
                        ),       
                    ),
                    'field_col_2'=>array(
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Field 2 cols',
                        'description'=> 'Choose field to 2 cols',
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
                    'search_text'=>array(
                        'type' => 'textfield',
                        'heading'   =>'Search text',
                        'default'=> __( 'Search', 'administrator-z' )
                    ), 
                    'form_class'=>array(
                        'type' => 'textfield',
                        'heading'   =>'Class',
                        'default'=>''
                    ),                
                ],
            ),
        ),
    );
    $all_arr_fixed_value = [];
    if(!empty($all) and is_array($all)){
        foreach ($all as $key => $value) {
            $terms = get_terms($key);
            $tmp = [];

            if(!is_wp_error($terms) and !empty($terms) and is_array($terms)){
                foreach ($terms as $key2 => $value) {
                    $tmp[$value->slug] = $value->name;
                }
            }
            $all_arr_fixed_value[$key] = $tmp;
        }
    }
    if(!empty($all_arr_fixed_value) and is_array($all_arr_fixed_value)){
        foreach ($all_arr_fixed_value as $key => $value) {
            $settings['options']['taxgroup']['options']['fixed_'.$key] = array(
                        'type' => 'select',
                        'param_name' => 'slug',
                        'heading' => 'Fixed value',
                        'conditions' => 'fixed_field == "'.$key.'"',
                        'options' => $value
                    );
        }
    }
    add_ux_builder_shortcode('adminz_woo_form',$settings);
}
add_shortcode('adminz_woo_form', 'adminz_woo_form_shortcode');
function adminz_woo_form_shortcode($atts, $content = null ) {           
    if(!Adminz::is_woocommerce()) return;  
    global $wpdb;
    $defaultatts = array(
        '_id'=>rand(),
        'item_col_large'=> '3',
        'item_col_small'=> '1',
        'global_filter_price'=>'',
        'step'=>'',     
        'field_before_title'=>'',
        'field_after_title'=>'',
        'style'=>'',
        'selectnone'=> __("Search") ,
        'search_placeholder'=> __( 'Enter a search term and press enter', 'administrator-z' ),
        'selectall'=> __("Select all",'administrator-z'),
        'fields'=> 'title,submit',
        'query_type_or'=>'',
        'fixed_field'=>'',
        // value for fixed field
        'closerow_before'=>'',
        'field_col_12'=>'',   
        'field_col_6'=>'',   
        'field_col_4'=>'',   
        'field_col_3'=>'',   
        'field_col_2'=>'',   
        'field_view_more'=> '', 
        'view_more_text_1'=> 'view more',
        'view_more_text_2'=> 'view less',
        'search_text'=> __( 'Search', 'administrator-z' ),
        'col_spacing'=> '',
        'form_class'=>''
    );
    $tax_arr = ADMINZ_Woocommerce::get_arr_tax();  
    $key_arr = ADMINZ_Woocommerce::get_arr_meta_key('product');

    if(!empty($tax_arr) and is_array($tax_arr)){
        foreach ($tax_arr as $key => $value) {
            $defaultatts['fixed_'.$key] = '';
        }
    }

    extract(shortcode_atts($defaultatts, $atts));     
    $fields = explode(",", $fields);         
    $field_col_12 = explode(',', $field_col_12);
    $field_col_6 = explode(',', $field_col_6);
    $field_col_4 = explode(',', $field_col_4);
    $field_col_3 = explode(',', $field_col_3);
    $field_col_2 = explode(',', $field_col_2);
    $field_view_more = $field_view_more? explode(',',$field_view_more) : [];
    $style = explode(",", $style);
    
    $taxonomy_hien_tai = ADMINZ_Helper_Woocommerce_Taxonomy::lay_toan_bo_taxonomy_hien_tai();    
    ob_start();
    ?>
    <form id="<?php echo esc_attr($_id); ?>" class="<?php echo $form_class ?> adminz_woo_form mb-0" method="get" action="<?php echo wc_get_page_permalink( 'shop' ); ?>">
        <div class="row row-<?php echo $col_spacing ?>">
            <?php 
            if($fields){
                // sap xep lai fields theo view_more de khi click de~ nhin
                // echo '<pre>'; print_r($field_view_more); echo '</pre>';
                // if(!empty($field_view_more) and is_array($field_view_more)){
                //     foreach ($field_view_more as $key => $value) {
                //         if(in_array($value,$fields)){
                //             unset($fields[array_search($value, $fields)]);
                //             $fields[]  = $value;
                //         }
                //     }
                // }
                // luon luon chuyen viewmore và submit xuong duoi cung
                if(in_array('view_more',$fields)){
                    unset($fields[array_search('view_more', $fields)]);
                }
                $fields[] = 'view_more';
                if(in_array('submit',$fields)){
                    unset($fields[array_search('submit', $fields)]);
                }
                $fields[] = 'submit';
                
                foreach ($fields as $taxonomy) {
                    if(in_array($taxonomy, explode(',', $closerow_before))){
                        echo '</div><div class="row">';
                    }

                    $item_col_class = "col small-".(12/$item_col_small). " large-".(12/$item_col_large);                    

                    if(in_array($taxonomy, (array)$field_col_12)){
                        $item_col_class = "col small-12 large-12";
                    }
                    if(in_array($taxonomy, (array)$field_col_6)){
                        $item_col_class = "col small-12 large-6";
                    }
                    if(in_array($taxonomy, (array)$field_col_4)){
                        $item_col_class = "col small-12 large-4";
                    }
                    if(in_array($taxonomy, (array)$field_col_3)){
                        $item_col_class = "col small-12 large-3";
                    }
                    if(in_array($taxonomy, (array)$field_col_2)){
                        $item_col_class = "col small-12 large-2";
                    }
                    if(in_array($taxonomy,$field_view_more)){
                        $item_col_class.= " hidden col_view_more";
                    }
                    
                    switch ($taxonomy) {
                        case 'title':
                            ?>                            
                            <div class="<?php echo esc_attr($item_col_class); ?>">
                                <div class="col-inner">
                                    <div class="field-title <?php if($field_before_title){ echo 'has-left-title';} ?>">
                                        <div class="relative full-width">
                                            <div class="relative">
                                                <input type="text" name="s" class="mb-0" placeholder="<?php echo esc_attr($search_placeholder); ?>" style="padding-left:  40px;" value="<?php 
                                        echo isset($taxonomy_hien_tai['s'])? esc_attr($taxonomy_hien_tai['s']) : "";  ?>" />
                                                <i class="icon-search absolute v-center left op-4" style="margin-left: 15px; color: #232323;" ></i>
                                            </div>
                                            <?php 
                                            if($field_after_title){
                                                $after_class = "";
                                                if($field_after_title == 'submit' or $field_after_title == 'view_more'){
                                                    $after_class = "hide-for-small";
                                                }
                                                echo '<div class="absolute v-center right '.esc_attr($after_class).'">';
                                                echo adminz_woo_form_get_sub_field($field_after_title,$item_col_class,$taxonomy,$style,$global_filter_price,$step, $selectnone,$query_type_or,$view_more_text_1,$view_more_text_2,$field_view_more,$field_after_title,$field_before_title);
                                                echo '</div>';
                                            }
                                            ?>                                        
                                        </div>
                                        <?php 
                                        if($field_before_title){
                                            echo '<div class="left-title">';
                                            echo adminz_woo_form_get_sub_field($field_before_title,$item_col_class,$taxonomy,$style,$global_filter_price,$step, $selectnone,$query_type_or,$view_more_text_1,$view_more_text_2,$field_view_more,$field_after_title,$field_before_title);
                                            echo '</div>';
                                        }                                        
                                        ?>
                                    </div>                                    
                                </div>
                            </div>
                            <?php
                            break;
                        case 'price':
                            echo adminz_woo_form_get_field_price($item_col_class,$global_filter_price,$step,$style,$selectnone);
                            break;
                        case 'submit': 
                            echo adminz_woo_form_get_field_submit($item_col_class,$field_after_title,true,$view_more_text_1, $view_more_text_2, $field_view_more, $field_before_title,$search_text); 
                            break;
                        case 'view_more':                                                          
                            break;
                        default:
                            // for taxonomy
                            if(array_key_exists($taxonomy,$tax_arr)){
                                echo adminz_woo_form_get_field_taxonomy($item_col_class,$taxonomy,$style,$selectnone,$query_type_or);
                            }

                            // for metakey
                            if(array_key_exists($taxonomy,$key_arr)){
                                echo adminz_woo_form_get_field_metakey($item_col_class,$taxonomy,$style,$selectnone,$query_type_or);
                            }

                            // Other
                            do_action('adminz_woo_form_fields_html',$taxonomy,$item_col_class,$selectnone);
                        break;
                    }
                }                    
            }
            ?>
        </div>  
        <?php 
        // for fixed field
        if(isset($fixed_field) and $fixed_field ) {
            if(isset(${'fixed_'.$fixed_field}) and ${'fixed_'.$fixed_field}){
                ?>
                <input type="hidden" name="<?php echo esc_attr($fixed_field);?>" value="<?php echo esc_attr(${'fixed_'.$fixed_field}) ;?>">
                <?php
            }
        }

        ?>
        <input type="hidden" name="post_type" value="product">
    </form>
    
    <?php      
    add_action('wp_footer',function () use($_id){   
        
        ?>
        <script type="text/javascript">
            window.addEventListener('DOMContentLoaded', function() {
                (function($){
                    var adminz_woo_form = $('<?php echo "#".esc_attr($_id); ?>.adminz_woo_form');
                    $(adminz_woo_form).find('.listcheckbox label').each(function(){
                        $(this).click(function(e){
                            e.preventDefault();
                            $(this).toggleClass('active');
                        });                
                    });
                    $(adminz_woo_form).find(".filter_price .listcheckbox label").each(function(){
                        $(this).click(function(e){
                            e.preventDefault();
                            var from = $(this).data("from");
                            var to = $(this).data("to");
                            $(adminz_woo_form).find('input[name="min_price"]').removeAttr("disabled").val(from);
                            $(adminz_woo_form).find('input[name="max_price"]').removeAttr("disabled").val(to);
                        });
                    });
                    $(adminz_woo_form).find(".filter_price select").on('change keyup',function(e){
                        e.preventDefault();
                        var min_price = ($(this).find(':selected').data('min_price'));
                        var max_price = ($(this).find(':selected').data('max_price'));
                        $(adminz_woo_form).find('input[name="min_price"]').removeAttr("disabled").val(min_price);
                        $(adminz_woo_form).find('input[name="max_price"]').removeAttr("disabled").val(max_price);
                    });
                    $(adminz_woo_form).find('.listcheckbox.tax label').each(function(){
                        $(this).click(function(e){
                            e.preventDefault();                    
                            var current_data_tax = $(this).data('tax');                    
                            var tax_value = '';
                            $(this).closest(".listcheckbox.tax").find("label").each(function(){                        
                                if($(this).hasClass('active') & ($(this).data("tax") == current_data_tax)){
                                    tax_value +=$(this).data("value")+",";
                                };
                            });                    
                            tax_value = tax_value.slice(0, -1);
                            $(this).closest(".col").find("input[name='"+current_data_tax+"']").val(tax_value);
                        });
                    });
                    $(adminz_woo_form).find('.viewmore').on('click',function(){
                        $(this).closest(adminz_woo_form).toggleClass("viewmored");
                        $(this).closest(adminz_woo_form).find('.col_view_more').toggleClass('hidden');
                        var current_text = $(this).find('span').text();
                        $(this).find('span').text($(this).data('text'));
                        $(this).data('text',current_text);
                        $(this).toggleClass('toggled');
                    });

                })(jQuery);
            });
        </script>
        <?php
             
    });
    $return = apply_filters('adminz_output_debug',ob_get_clean());
    return $return;
}
function adminz_woo_form_get_sub_field($field,$item_col_class,$taxonomy,$style,$global_filter_price,$step, $selectnone,$query_type_or,$view_more_text_1,$view_more_text_2,$field_view_more,$field_after_title,$field_before_title){   
    echo '<div class="row row-collapse">';
    switch ($field) {
        case 'title':
            echo 'not supportted';
            break;
        case 'price':
            echo adminz_woo_form_get_field_price($item_col_class,$global_filter_price,$step,$style,$selectnone);
            break;
        case 'submit':
            echo adminz_woo_form_get_field_submit($item_col_class,$field_after_title,false,$view_more_text_1, $view_more_text_2, $field_view_more, $field_before_title,$search_text);
            break;
        case 'view_more':
            break;
        default:
            echo adminz_woo_form_get_field_taxonomy($item_col_class,$field,$style,$selectnone,$query_type_or);
            break;
    }
    echo '</div>';
}

function adminz_woo_form_get_field_submit(
        $item_col_class,
        $field_after_title,
        $is_main_field=true,
        $view_more_text_1 =true,//
        $view_more_text_2 =true,
        $field_view_more =true,
        $field_before_title =true,
        $search_text=''
    ){
    ob_start();
    if($field_after_title == 'submit' and $is_main_field){
        $item_col_class .= " show-for-small";
    }
    ?>
    <div class="<?php echo esc_attr($item_col_class); ?> zsubmit">   
        <div class="col-inner">
            <button type="submit" class="<?php echo apply_filters('adminz_woo_form_submit_class','ux-search-submit submit-button secondary button icon ') ?>" aria-label="<?php echo __( 'Submit', 'administrator-z' ); ?>">
                <?php echo esc_attr($search_text); ?>        
            </button>   
            <?php
            if(!empty($field_view_more) and is_array($field_view_more)){
                if($field_after_title == 'view_more' and $is_main_field){
                    $item_col_class .= " show-for-small";
                }
            ?>
                <button type="button" class="<?php echo apply_filters('adminz_woo_form_viewmore_class','button is-outline  is-smaller op-6 viewmore') ?>" data-text="<?php echo esc_attr($view_more_text_2); ?>"><span><?php echo esc_attr($view_more_text_1); ?></span> <i class="icon-angle-up hidden"></i> <i class="icon-angle-down"></i></button>
                <!-- <button type="reset" class="button is-outline is-smaller op-6 reset">
                    Reset
                </button>     -->  
            <?php
            }
            ?>
        </div>
        </div>
    <?php
    return ob_get_clean();
}
function adminz_woo_form_get_field_taxonomy($item_col_class,$taxonomy,$style,$selectnone,$query_type_or){
    if(!is_user_logged_in() and get_transient( __FUNCTION__ .$taxonomy)){
        return get_transient( __FUNCTION__ .$taxonomy);
    }
    
    ob_start();    
    $taxobj = get_taxonomy($taxonomy);    
    $get_terms = get_terms($taxonomy,array('hide_empty' => false));        
    $taxonomy2 = ADMINZ_Helper_Woocommerce_Taxonomy::thay_doi_term_slug_cho_link($taxonomy); 
    $label = ADMINZ_Helper_Woocommerce_Taxonomy::thay_doi_taxonomy_label($taxobj);
    $selectnone .= " ".$label;
    ?>
    <div class="<?php echo esc_attr($item_col_class); ?>">                                
        <?php                                 
        if(in_array($taxonomy, $style)){                                    
            ?>
            <strong><?php echo esc_attr($label); ?></strong> 
            <input type="hidden" class="target_value" name="<?php echo esc_attr($taxonomy2);?>" value="<?php
            echo ADMINZ_Helper_Woocommerce_Taxonomy::lay_gia_tri_taxonomy_hien_tai($taxonomy2);
            ?>">                                    
            
            <div class="listcheckbox tax">
                <?php 
                if(!empty($get_terms) and is_array($get_terms)){
                    foreach ($get_terms as $key => $term) {
                        echo ADMINZ_Helper_Woocommerce_Taxonomy::chuyen_doi_term_sang_button_form($term,$taxonomy2);
                    }
                }
                 ?>
            </div>                                    
            <?php                              
        }else{
            $categoryHierarchy = array();             
            if(is_array($get_terms)){                
                ADMINZ_Helper_Woocommerce_Taxonomy::sap_xep_lai_cha_con($get_terms, $categoryHierarchy);
            }            
            ?>
            <?php

                $attrs = [
                    'class'=>'adminz_woo_form_get_field_taxonomy target_value',
                    'name'=> $taxonomy2.'[]',
                    'multiple'=>'multiple',
                    'data-placeholder'=>$selectnone
                ];
                if(
                    substr($taxonomy2, 0,7) == 'filter_' or
                    in_array($taxonomy2,['product_visibility', 'product_type', 'product_tag', 'product_cat', 'rating_filter'])
                ){
                    $attrs['name'] = str_replace('[]','',$attrs['name']);
                    $attrs['multiple'] = '';
                    $attrs['data-placeholder'] = '';
                }

                

            ?>
            <select <?php echo adminz_render_html_attr($attrs); ?> >
                <option value="" ><?php echo esc_attr($selectnone); ?></option>
                <?php 
                if(!empty($categoryHierarchy) and is_array($categoryHierarchy)){
                    foreach ($categoryHierarchy as $key => $term) {
                        echo ADMINZ_Helper_Woocommerce_Taxonomy::chuyen_doi_term_option_select($term,$taxonomy2,"");
                    }
                }
                ?>
            </select>
            <?php
        }                                
        if(in_array($taxonomy2,explode(",",$query_type_or))){                                    
                echo ADMINZ_Helper_Woocommerce_Taxonomy::lay_input_query_type($taxonomy);
            }
        ?>
    </div>
    <?php
    $return = ob_get_clean();
    set_transient(__FUNCTION__.$taxonomy,$return, DAY_IN_SECONDS ); // Đặt trước đoạn return
    return $return;
}
function adminz_woo_form_get_field_metakey($item_col_class,$metakey,$style,$selectnone,$query_type_or){
    ob_start();

    $metavalues = ADMINZ_Flatsome::adminz_get_all_meta_values_by_key($metakey);
    $metavalues = apply_filters('adminz_woo_form_metavalues_'.$metakey,array_filter($metavalues));
    

    switch ($metakey) {
        case 'huyen':
            $label = ADMINZ_Helper_ACF_THX::get_huyen_label();
            break;
        case 'xa':
            $label = ADMINZ_Helper_ACF_THX::get_xa_label();
            break;
        case 'tinh':
            $label = ADMINZ_Helper_ACF_THX::get_tinh_label();
            break;   
        default:            
            $label = ADMINZ_Helper_ACF::get_field_label($metakey);
            break;
    }
    $selectnone .= " ".$label;
    ?>
    <div class="<?php echo esc_attr($item_col_class); ?>">                                
        <?php


        $current_metavalue = isset($_GET[$metakey])? sanitize_text_field($_GET[$metakey]) : "";
        if(in_array($metakey, $style)){
            ?>
            <strong><?php echo esc_attr($label); ?></strong>             
            <input type="hidden" class="target_value" name="<?php echo esc_attr($metakey);?>" value="<?php echo esc_attr($current_metavalue);?>">                                    
            
            <div class="listcheckbox tax">
                <?php 
                if(!empty($metavalues) and is_array($metavalues)){
                    $current_metavalue = explode(",",$current_metavalue);
                    foreach ($metavalues as $key => $term) {
                        ?>
                        <label data-value="<?php echo esc_attr($term);?>" data-tax="<?php echo esc_attr($metakey); ?>" class="<?php echo in_array($term,$current_metavalue)? "active" : ""; ?>"> 
                            <?php echo esc_attr($term); ?>
                        </label>
                        <?php
                    }
                }
                 ?>
            </div>
            <?php
        }else{
            $attrs = [
                'name'=>$metakey."[]",
                'multiple'=>'multiple',
                'data-placeholder'=>$selectnone,
                'class'=>'target_value'
            ];
            // if(in_array($metakey,['tinh','huyen','xa'])){
                // $attrs = [
                //     'name'=>$metakey,
                //     'class'=>'target_value'
                // ];
                // $attrs['name'] = $metakey;
            // }
            // echo "<pre>";print_r($attrs);echo "</pre>";
            ?>
            <select <?php echo adminz_render_html_attr($attrs); ?> >
                <option value="" ><?php echo esc_attr($selectnone); ?></option>
                <?php 
                if(!empty($metavalues) and is_array($metavalues)){
                    $current_metavalue = explode(",",$current_metavalue);
                    foreach ($metavalues as $key => $term) {
                        $selected = in_array($term,$current_metavalue)? "selected" : "";

                        ?>
                        <option value="<?php echo esc_attr($term) ?>" <?php echo esc_attr($selected); ?> ><?php echo esc_attr($term); ?></option>
                        <?php
                    }
                }
                ?>
            </select>
            <?php
        }  
        ?>
    </div>
    <?php
    return ob_get_clean();
}
function adminz_woo_form_get_field_price($item_col_class='',$global_filter_price=false,$step=false,$style=false,$selectnone=false){
    global $wp;
    ob_start();
    $step = max( apply_filters( 'woocommerce_price_filter_widget_step', 10 ), 1 );
    $prices    = adminz_get_filtered_price();
    $min_price = $prices->min_price;
    $max_price = $prices->max_price;
    // Check to see if we should add taxes to the prices if store are excl tax but display incl.
    $tax_display_mode = get_option( 'woocommerce_tax_display_shop' );

    if ( wc_tax_enabled() && ! wc_prices_include_tax() && 'incl' === $tax_display_mode ) {
        $tax_class = apply_filters( 'woocommerce_price_filter_widget_tax_class', '' ); // Uses standard tax class.
        $tax_rates = WC_Tax::get_rates( $tax_class );

        if ( $tax_rates ) {
            $min_price += WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $min_price, $tax_rates ) );
            $max_price += WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $max_price, $tax_rates ) );
        }
    }

    $min_price = apply_filters( 'woocommerce_price_filter_widget_min_amount', floor( $min_price / $step ) * $step );
    $max_price = apply_filters( 'woocommerce_price_filter_widget_max_amount', ceil( $max_price / $step ) * $step );

    // If both min and max are equal, we don't need a slider.
    if ( $min_price === $max_price ) {
        return ob_get_clean();
    }

    $current_min_price = isset( $_GET['min_price'] ) ? floor( floatval( wp_unslash( $_GET['min_price'] ) ) / $step ) * $step : $min_price; // WPCS: input var ok, CSRF ok.
    $current_max_price = isset( $_GET['max_price'] ) ? ceil( floatval( wp_unslash( $_GET['max_price'] ) ) / $step ) * $step : $max_price; // WPCS: input var ok, CSRF ok.

    // $this->widget_start( $args, $instance );

    $form_action = get_permalink(get_option('woocommerce_shop_page_id'));

    $args = [
        'form_action'       => $form_action,
        'step'              => $step,
        'min_price'         => $min_price,
        'max_price'         => $max_price,
        'current_min_price' => $current_min_price,
        'current_max_price' => $current_max_price,
        'call_hidden_fields'=> false
    ];

    echo '<div class="'.esc_attr($item_col_class).' widget_price_filter adminz_woo_form_field_price_filter">';
    // echo 'adminz filter price';
    extract($args);
    require ADMINZ_DIR . '/shortcodes/inc/template-price-filter.php';
    echo '</div>';

    // $this->widget_end( $args );




    return ob_get_clean();
}

function adminz_get_filtered_price() {
    global $wpdb;    
    $args       = (WC()->query->get_main_query()->query_vars)?? [];
    $tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
    $meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

    if ( ! is_post_type_archive( 'product' ) && ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
        $tax_query[] = WC()->query->get_main_tax_query();
    }

    foreach ( $meta_query + $tax_query as $key => $query ) {
        if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
            unset( $meta_query[ $key ] );
        }
    }


    $meta_query = new WP_Meta_Query( $meta_query );
    $tax_query  = new WP_Tax_Query( $tax_query );
    // $search     = WC_Query::get_main_search_query_sql();
    $search = '';

    $meta_query_sql   = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
    $tax_query_sql    = $tax_query->get_sql( $wpdb->posts, 'ID' );
    $search_query_sql = $search ? ' AND ' . $search : '';

    $sql = "
        SELECT min( min_price ) as min_price, MAX( max_price ) as max_price
        FROM {$wpdb->wc_product_meta_lookup}
        WHERE product_id IN (
            SELECT ID FROM {$wpdb->posts}
            " . $tax_query_sql['join'] . $meta_query_sql['join'] . "
            WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product' ) ) ) ) . "')
            AND {$wpdb->posts}.post_status = 'publish'
            " . $tax_query_sql['where'] . $meta_query_sql['where'] . $search_query_sql . '
        )';

    $sql = apply_filters( 'woocommerce_price_filter_sql', $sql, $meta_query_sql, $tax_query_sql );

    return $wpdb->get_row( $sql ); // WPCS: unprepared SQL ok.
}

function adminz_render_html_attr($attrs = []){
    if(!empty($attrs) and is_array($attrs)){
        ob_start();

        $enable_select2_multiple = false; // bật select 2 multiple
        $enable_select2 = false; // bật select 2

        // nếu bật option         
        if((isset(ADMINZ_Woocommerce::$options['enable_select2_multiple']) and ADMINZ_Woocommerce::$options['enable_select2_multiple'] == 'on') ){
            $enable_select2_multiple = true;
        }

        // nếu bật option
        if((isset(ADMINZ_Woocommerce::$options['enable_select2']) and ADMINZ_Woocommerce::$options['enable_select2'] == 'on') ){
            $enable_select2 = true;
        }


        // nếu tắt select thì ko multiple
        if(!$enable_select2){
            $enable_select2_multiple = false;
        }

        // filter hooks
        $enable_select2_multiple = apply_filters('adminz_enable_select2_multiple',$enable_select2_multiple,$attrs);
        $enable_select2 = apply_filters('adminz_enable_select2',$enable_select2,$attrs);


        // nếu ko bật select 2 thì unset một số attr
        if(!$enable_select2_multiple){
            if(isset($attrs['name'])){
                $attrs['name'] = str_replace('[]','',$attrs['name']);
            }
            if(isset($attrs['multiple'])){
                $attrs['multiple'] = '';
            }
            if(isset($attrs['data-placeholder'])){
                $attrs['data-placeholder'] = '';
            }
            if(isset($attrs['class'])){
                $attrs['class'] = str_replace('hidden','',$attrs['class']);
            }
        }

        $attrs = apply_filters('adminz_render_html_attrs',$attrs);

        // echo "<pre>";print_r($attrs);echo "</pre>";
        foreach ($attrs as $key => $value) {
            if($value){
                echo $key . "=" . '"'.$value.'" ';
            }
        }

        return ob_get_clean();
    }
}