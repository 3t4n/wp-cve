<?php
/*
 *Title: WP E-Commerce
 *Origin plugin: wp-e-commerce/wp-shopping-cart.php
*/
?>
<?php
if (!function_exists('add_action') ) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}
set_time_limit(60 * 5); //5 min
ini_set('memory_limit', '256M');


$is_post_request = strtoupper(pelm_read_sanitized_server_parm('REQUEST_METHOD','')) === 'POST';

$variations_skip = array( 'categories','status');

function pelm_array_escape(&$arr)
{
    foreach($arr as $key => $value){
        if(is_string($value)) {
            if(strpos($value, "\n") !== false) {
                $arr[$key] = str_replace(array("\n","\r"), array("\\n","\\r"), $value);
            }
        }
    }
}

if($is_post_request) {
    $pelm_security = pelm_read_sanitized_request_parm("pelm_security");
    if($pelm_security) {
        if (!wp_verify_nonce(pelm_read_sanitized_request_parm("pelm_security"), 'pelm_nonce')) {
            die("<br><br>CSRF: Hmm .. looks like you didn't send any credentials.. No access for you!");
        } else{
            pelm_accept_verified_nonce("pelm_nonce", pelm_read_sanitized_request_parm("pelm_security"));
        }
    }else {
        die("<br><br>CSRF: Hmm .. looks like you didn't send any credentials.. No access for you!");
    }
}

if((pelm_read_sanitized_request_parm("keep_alive"))) {
    if(pelm_read_sanitized_request_parm("keep_alive")) {
        return;
    }
}

global $wpdb;
global $wpsc_fields_visible;
global $custom_fileds, $use_image_picker, $use_content_editior;


$use_image_picker    = false;
$use_content_editior = false;

$wpsc_fields_visible = array();
if(isset($plem_price_settings['wpsc_fileds'])) {
    foreach(explode(",", $plem_price_settings['wpsc_fileds']) as $I => $val){
        if($val) {
            $wpsc_fields_visible[$val] = true;
        }
    }
}

global $impexp_settings, $custom_export_columns;

$impexp_settings       = new stdClass;
$custom_export_columns = array();        

if(isset($plem_price_settings[pelm_read_sanitized_request_parm("elpm_shop_com").'_custom_import_settings'])) {
    $impexp_settings = $plem_price_settings[pelm_read_sanitized_request_parm("elpm_shop_com").'_custom_import_settings'];
    if(!isset($impexp_settings->custom_export_columns)) {
        $impexp_settings->custom_export_columns = "";
    }
    if($impexp_settings) {
        foreach(explode(",", $impexp_settings->custom_export_columns) as $col){
            if($col) {
                $custom_export_columns[] = $col;
            }
        }
    }
}

if(!isset($impexp_settings->delimiter2)) {
    $impexp_settings->delimiter2 = ',';
}

if(!isset($impexp_settings->use_custom_export)) {
    $impexp_settings->use_custom_export = false;
}


function pelm_fn_show_filed($name)
{
    global $wpsc_fields_visible;
    
    if(empty($wpsc_fields_visible)) {
        return $name != "image";
    }
        
    if(isset($wpsc_fields_visible[$name])) {
        return $wpsc_fields_visible[$name];
    }
    
    $wpsc_fields_visible[$name] = false;
    return false;
};

function pelm_fn_correct_type($s)
{
    if(is_numeric(trim($s))) {
        return intval($s);
    } else { 
        return trim($s);
    }  
};

function pelm_array_val($val)
{
    if(is_string($val)) {
        return explode(",", $val);
    }
    return $val;    
};

function pelm_fn_get_meta_by_path($id,$path)
{
    if(strpos($path, '!') !== false || strpos($path, '.') !== false) {
        $object   = null;
        $is_object  = false;
        $meta_key = "";
        $parent_is_object = false;
        
        $ind_key  = strpos($path, '!');
        $ind_prop = strpos($path, '.');
        
        if($ind_key > $ind_prop && $ind_prop !== false) {
            $meta_key = substr($path, 0, $ind_prop);
            $object   = get_post_meta($id, $meta_key, true);
            $path     = substr($path, $ind_prop + 1);
            if(!$object) {
                return null;
            }
            $parent_is_object = !is_array($object);    
        }else{
            $meta_key = substr($path, 0, $ind_key);
            $object   = get_post_meta($id, $meta_key, true);
            $path     = substr($path, $ind_key + 1);
            if(!$object) {
                return null;
            }
            $parent_is_object = !is_array($object);        
        }
        $ptr = &$object;
        do{
            $is_object = false;
            $ind_key  = strpos($path, '!');
            $ind_prop = strpos($path, '.');
              $ind = -1; 
            if($ind_key !== false) {
                if($ind_prop !== false) {
                         $ind = $ind_key > $ind_prop? $ind_prop : $ind_key;
                    if($ind === $ind_prop) {
                        $is_object = true;
                    }
                }else {
                         $ind = $ind_key;
                }
            }elseif($ind_prop !== false) {
                $ind = $ind_prop;
                $is_object = true;
            }    
           
            if($ind != -1) { 
                $key  =  substr($path, 0, $ind);
                if($key === '' || $key === null) {
                    $path = $key;
                    break;
                }
                $path =  substr($path, $ind + 1);
            }else { 
                break;
            }
               
            if($parent_is_object) {
                if(!isset($ptr->{$key})) {
                    return null;
                }
                $ptr = &$ptr->{$key};  
            }else{
                if(!isset($ptr[$key])) {
                    return null;
                }
                $ptr = &$ptr[$key];
            }       
            $parent_is_object = !is_array($ptr);
        }while($ind_key !== false || $ind_prop !== false);
        
        if($is_object) {
            return $ptr->{$path};
        } else {
            return $ptr[$path];
        }
    }else {
        return get_post_meta($id, $path, true);
    }
};


function pelm_fn_convert_unit($value,$from,$to)
{
    if($from == "pound") {
        if($to == "ounce") { $value *= 16;
        } elseif($to == "gram") { $value *= 453.59237;
        } elseif($to == "kilogram") { $value *= 0.45359237;
        }
    }elseif($from == "ounce") {
        if($to == "pound") { $value *= 0.0625; 
        } elseif($to == "gram") { $value *= 28.3495231;
        } elseif($to == "kilogram") { $value *= 0.0283495231;
        }
    }elseif($from == "gram") {
        if($to == "pound") { $value *= 0.00220462262;
        } elseif($to == "ounce") { $value *= 0.0352739619;
        } elseif($to == "kilogram") { $value *= 0.001;
        }
    }elseif($from == "kilogram") {
        if($to == "pound") { $value *= 2.204622;
        } elseif($to == "ounce") { $value *= 35.2739619;
        } elseif($to == "gram") { $value *= 1000;
        }
    }elseif($from == "in") {
        if($to == "cm") { $value *= 2.54;
        } elseif($to == "meter") { $value *= 0.0254;
        } 
    }elseif($from == "cm") { 
        if($to == "in") { $value *= 0.393700787;
        } elseif($to == "meter") { $value *= 0.01;
        }
    }elseif($from == "meter") {
        if($to == "in") { $value *= 39.3700787;
        } elseif($to == "cm") { $value *= 100;
        }
    }  
    return $value;
};

function pelm_get_array_value(&$array,$key,$default)
{
    if(isset($array[$key])) {
        return $array[$key];
    } else {
        return $default;
    }
}; 

$custom_fileds = array();
function pelm_load_custom_fields(&$plem_price_settings,&$custom_fileds)
{
    global $use_image_picker, $use_content_editior;
    
    
    for($I = 0 ; $I < 20 ; $I++){
        $n = $I + 1;
        if(isset($plem_price_settings["wpsccf_enabled".$n])) {
            if($plem_price_settings["wpsccf_enabled".$n]) {
                $cfield = new stdClass();
                
                $cfield->type  = pelm_get_array_value($plem_price_settings, "wpsccf_type".$n, "");
                if(!$cfield->type) {
                    continue;
                }
                  
                $cfield->title = pelm_get_array_value($plem_price_settings, "wpsccf_title".$n, "");
                if(!$cfield->title) {
                    continue;
                }
               
                $cfield->source = pelm_get_array_value($plem_price_settings, "wpsccf_source".$n, "");
                if(!$cfield->source) {
                    continue;
                }  
                  
                $cfield->options = pelm_get_array_value($plem_price_settings, "wpsccf_editoptions".$n, "");
                if($cfield->options) {
                    $cfield->options = json_decode($cfield->options);
                }else{
                    $cfield->options = new stdClass();    
                    $cfield->options->formater = '';
                }
                    
                if($cfield->type == 'term') {
                    $cfield->terms = array();
                    $terms = get_terms($cfield->source, array('hide_empty' => false));
                    foreach($terms as $val){
                        $value            = new stdClass();
                        $value->value     = $val->term_id;
                        //$value->slug      = $val->slug;
                        $value->name      = $val->name;
                        //$value->parent    = $val->parent;
                        $cfield->terms[]  = $value;
                    }
                }else{
                    if($cfield->options->formater == "content") {
                        $use_content_editior = true;
                    } elseif($cfield->options->formater == "image") {
                        $use_image_picker    = true;
                    }
                }    
                    
                $cfield->name = 'cf_'. strtolower($cfield->source);            
                $custom_fileds[$cfield->name] = $cfield;    
            }   
        }
    }
};

pelm_load_custom_fields($plem_price_settings, $custom_fileds);

$productsPerPage = 1000;
if(isset($plem_price_settings["productsPerPage"])) {
    $productsPerPage = intval($plem_price_settings["productsPerPage"]);
}

$limit = $productsPerPage;

if(pelm_read_sanitized_cookie_parm('pelm_txtlimit', 0)) {
    $limit = pelm_read_sanitized_cookie_parm('pelm_txtlimit', $productsPerPage);
}

$page_no  = 1;

$orderby         = "ID";
$orderby_key     = "";

$sort_order  = "DESC";
$sku = '';
$product_name = '';
$product_category = '';
$product_tag      = '';
$product_status   = '';

if((pelm_read_sanitized_request_parm("limit"))) {
    $limit = pelm_read_sanitized_request_parm("limit");
}

if((pelm_read_sanitized_request_parm("page_no"))) {
    $page_no = pelm_read_sanitized_request_parm("page_no");
}

if((pelm_read_sanitized_request_parm("sku"))) {
    $sku = pelm_read_sanitized_request_parm("sku");
}

if((pelm_read_sanitized_request_parm("product_name"))) {
    $product_name = pelm_read_sanitized_request_parm("product_name");
}

if((pelm_read_sanitized_request_parm("product_category"))) {
    $product_category = explode(",", pelm_read_sanitized_request_parm("product_category"));
}

if((pelm_read_sanitized_request_parm("product_tag"))) {
    $product_tag = explode(",", pelm_read_sanitized_request_parm("product_tag"));
}


if((pelm_read_sanitized_request_parm("product_status"))) {
    $product_status = explode(",", pelm_read_sanitized_request_parm("product_status"));
}    

if((pelm_read_sanitized_request_parm("sortColumn"))) {
    $orderby = pelm_read_sanitized_request_parm("sortColumn");
    
    $orderby = "ID";
    $orderby_key = "";
    
    if(isset($custom_fileds[pelm_read_sanitized_request_parm("sortColumn")])) {
     
        $field = $custom_fileds[pelm_read_sanitized_request_parm("sortColumn")];
        
        if($custom_fileds[pelm_read_sanitized_request_parm("sortColumn")]->type == 'post') {
            $orderby = $custom_fileds[pelm_read_sanitized_request_parm("sortColumn")]->source;
            $orderby_key = "";
        }elseif($custom_fileds[pelm_read_sanitized_request_parm("sortColumn")]->type == 'meta') {
            $orderby = "meta_value";
            $orderby_key = $custom_fileds[pelm_read_sanitized_request_parm("sortColumn")]->source;
        }

    }
    elseif($orderby == "id") { $orderby = "ID";
    } elseif($orderby == "sku") {
        $orderby = "meta_value";
        $orderby_key = "_wpsc_sku";
    }
    elseif($orderby == "slug") { $orderby = "name";
    } elseif($orderby == "categories") {
        $orderby = "category_name";
        //???? this is not correct
    }
    elseif($orderby == "name") { $orderby = "title";
    } elseif($orderby == "stock") {
        $orderby = "meta_value_num";
        $orderby_key = "_wpsc_stock";
    }
    elseif($orderby == "price") {
        $orderby = "meta_value_num";
        $orderby_key = "_wpsc_price";
    }
    elseif($orderby == "override_price") {
        $orderby = "meta_value_num";
        $orderby_key = "_wpsc_special_price";
    }
    elseif($orderby == "status") { 
        $orderby = "status";
    }
    elseif($orderby == "tags") { 
        $orderby = "tag";
        //???? this is not correct
    }
}

if((pelm_read_sanitized_request_parm("sortOrder"))) {
    $sort_order = pelm_read_sanitized_request_parm("sortOrder");
}

if((pelm_read_sanitized_request_parm("DO_UPDATE"))) {
    if(pelm_read_sanitized_request_parm("DO_UPDATE") == '1' && $is_post_request) {
    
        if(!( current_user_can('editor') || current_user_can('administrator'))) {
            wp_die("You are not allowed to edit products!");
            return;
        }
    
        $timestamp = time();
        $json = file_get_contents('php://input');
        $tasks = json_decode($json);
        $surogates = get_option("plem_price_wpsc_surogates", array());
        $surogates_dirty = false;
        if(!empty($surogates)) {
            foreach($surogates as $s_key => $s){
                if($s["created"] < $timestamp - 1800) {
                       unset($surogates[$s_key]);
                       $surogates_dirty = true;
                }        
            }
        }
    
        $res = array();
        $temp = '';
    
        foreach($tasks as $key => $task){
               $return_added = false;
               $res_item = new stdClass();
               $res_item->id = $key;
       
               $sKEY = "".$key;
            if($sKEY[0] == 's') {
                continue;
            }
       
            if(isset($task->DO_DELETE)) {
                if($task->DO_DELETE === 'delete') {
               
                    wp_delete_post($key, true);
                    $res[] = $res_item;
                    continue;
                }
            }
       
            $upd_prop = array();
      
            $post_update = array( 'ID' => $key );
      
       
       
            if(isset($task->price)) { 
                update_post_meta($key, '_wpsc_price', $task->price);
            }
       
            if(isset($task->override_price)) {
                update_post_meta($key, '_wpsc_special_price', $task->override_price);
            }
       
            if($return_added) {
                $res_item->surogate = $sKEY;
                $res_item->full     = pelm_product_render($key, "data");
            }
       
            $res_item->success = true;
            $res[] = $res_item;
        }
    
        if($surogates_dirty) {
            update_option("plem_price_wpsc_surogates", (array)$surogates);
        }
    
        echo json_encode($res);
        exit; 
        return;
    }
}

function pelm_get_float($str)
{ 
    global $impexp_settings;

    if(!isset($impexp_settings->german_numbers)) {
        $impexp_settings->german_numbers = 0;
    } 
    
    if($impexp_settings->german_numbers) {
        $str = str_replace(".", "", $str);
        $str = str_replace(",", ".", $str);
    }else{
        if(strstr($str, ",")) { 
            $str = str_replace(",", "", $str); // replace ',' with '.' 
        }
    }
    return $str;
}; 

$import_count = 0;
if((pelm_read_sanitized_request_parm("do_import"))) {
    if(pelm_read_sanitized_request_parm("do_import") == "1") {
        
        if(!( current_user_can('editor') || current_user_can('administrator'))) {
            wp_die("You are not allowed to edit products!");
            return;
        }
        
        $n = 0;
        if (($handle = fopen($_FILES['file']['tmp_name'], "r")) !== false) {
            $id_index                = -1;
            $price_index             = -1;
            $price_o_index           = -1;
            $stock_index             = -1;
            $sku_index               = -1;
            $name_index              = -1;
            $slug_index              = -1;
            $status_index            = -1;
            $categories_names_index  = -1;
            $tags_names_index        = -1;
            $weight_index            = -1;
            $height_index            = -1;
            $width_index             = -1;
            $length_index            = -1;
            $taxable_index           = -1;
            $loc_shipping_index      = -1;
            $int_shipping_index      = -1;
            
            $cf_indexes              = array();
            $col_count                  = 0;
            
            $skip_first    = false;
            $custom_import = false;
            
            $imported_ids  = array();
        
            if($impexp_settings) {
                
                
                $cic = array();
                foreach(explode(",", $impexp_settings->custom_import_columns) as $col){
                    if($col) {
                        $cic[] = $col;
                    }
                }
                
                if($impexp_settings->use_custom_import) {
                    $custom_import = true;
                    if($impexp_settings->first_row_header) {
                        $skip_first = true;
                    }
                        
                    $col_count = count($cic);
                    $data      = $cic;
                    for($i = 0 ; $i < $col_count; $i++){
                        if($data[$i]     == "id") { $id_index = $i;
                        } elseif($data[$i] == "price") { $price_index = $i;
                        } elseif($data[$i] == "override_price") { $price_o_index = $i;
                        } elseif($data[$i] == "sku") { $sku_index   = $i;
                        } elseif($data[$i] == 'stock') { $stock_index = $i;
                        } elseif($data[$i] == 'name') { $name_index = $i;
                        } elseif($data[$i] == 'slug') { $slug_index = $i;
                        } elseif($data[$i] == 'status') { $status_index = $i;
                        } elseif($data[$i] == 'categories_names') { $categories_names_index = $i;
                        } elseif($data[$i] == 'tags_names') { $tags_names_index = $i;
                        } elseif($data[$i] == 'weight') { $weight_index            = $i;
                        } elseif($data[$i] == 'height') { $height_index            = $i;
                        } elseif($data[$i] == 'width') {  $width_index             = $i;
                        } elseif($data[$i] == 'length') { $length_index            = $i;
                        } elseif($data[$i] == 'taxable') { $taxable_index           = $i;
                        } elseif($data[$i] == 'loc_shipping') { $loc_shipping_index      = $i;
                        } elseif($data[$i] == 'int_shipping') { $int_shipping_index      = $i;
                        }
                        
                        foreach($custom_fileds as $cfname => $cfield){
                            if($cfname == $data[$i]) {
                                $cf_indexes[$cfname] = $i;
                                break;
                            }
                        }

                    }
                    
                }
            }
            
            $csv_row_processed_count = 0;
            if(!isset($impexp_settings->delimiter)) {
                $impexp_settings->delimiter = ",";
            }
            while (($data = fgetcsv($handle, 32768, $impexp_settings->delimiter)) !== false) {
                if($n == 0 && $custom_import && $skip_first) {
                    //NOTHING 
                }elseif($n == 0 && !$custom_import) {
                
                    //$id_index    = 0;
                    $col_count = count($data);
                    for($i = 0 ; $i < $col_count; $i++){
                        if($data[$i]     == "id") { $id_index = $i;
                        } elseif($data[$i] == "price") { $price_index = $i;
                        } elseif($data[$i] == "override_price") { $price_o_index = $i;
                        } elseif($data[$i] == "sku") { $sku_index   = $i;
                        } elseif($data[$i] == 'stock') { $stock_index = $i;
                        } elseif($data[$i] == 'name') { $name_index = $i;
                        } elseif($data[$i] == 'slug') { $slug_index = $i;
                        } elseif($data[$i] == 'status') { $status_index = $i;
                        } elseif($data[$i] == 'categories_names') { $categories_names_index = $i;
                        } elseif($data[$i] == 'tags_names') { $tags_names_index = $i;
                        } elseif($data[$i] == 'weight') { $weight_index            = $i;
                        } elseif($data[$i] == 'height') { $height_index            = $i;
                        } elseif($data[$i] == 'width') {  $width_index             = $i;
                        } elseif($data[$i] == 'length') { $length_index            = $i;
                        } elseif($data[$i] == 'taxable') { $taxable_index           = $i;
                        } elseif($data[$i] == 'loc_shipping') { $loc_shipping_index      = $i;
                        } elseif($data[$i] == 'int_shipping') { $int_shipping_index      = $i;
                        }
                        
                        foreach($custom_fileds as $cfname => $cfield){
                            if($cfname == $data[$i]) {
                                $cf_indexes[$cfname] = $i;
                                break;
                            }
                        }

                    }
                }else{
                    $csv_row_processed_count++;
                
                    $id = null;
                    if($id_index >= 0) {    
                        $id = intval($data[$id_index]);
                    }
                
                    if(!$id && $sku_index != -1) {
                        if($data[$sku_index]) {
                            $res = $wpdb->get_col("select post_id from $wpdb->postmeta where meta_key like '_wpsc_sku' and meta_value like '".$data[$sku_index]."'");
                            if(!empty($res)) {
                                $id = $res[0];
                            }
                        }
                    }
                   
                    if(!$id && $name_index != -1) {
                        if($data[$name_index]) {
                            $res = $wpdb->get_col("select ID from $wpdb->posts where cast(post_title as char(255)) like '" . $data[$name_index] . "' and post_type like 'wpsc-product' ");
                            if(!empty($res)) {
                                $id = $res[0];
                            }
                        }
                    }
                   
                    if(!$id) {
                        continue;
                    }
                   
                    if(false === get_post_status($id)) {
                        continue;
                    }
                    
                    $imported_ids[] = $id;        
                
                    while(count($data) < $col_count) {
                        $data[] = null;
                    }
                      
                    $post_update = array( 'ID' => $id );
      
                    if($price_index > -1) { 
                        if($data[$price_index]) {
                            $data[$price_index] = pelm_get_float($data[$price_index]);
                        }
                    
                        update_post_meta($id, '_wpsc_price', $data[$price_index]);
                    }
                   
                    if($price_o_index > -1) {
                        if($data[$price_o_index]) {
                            $data[$price_o_index]= pelm_get_float($data[$price_o_index]);
                        }
                        
                        update_post_meta($id, '_wpsc_special_price', $data[$price_o_index]);
                    }
                   
                    $import_count ++;
                }
                $n++;            
            }
            fclose($handle);
            
            if($csv_row_processed_count > 0) {
                if(isset($impexp_settings->notfound_setpending)) {
                    if($impexp_settings->notfound_setpending) {
                        if(!empty($imported_ids)) {
                            $wpdb->query( 
                                $wpdb->prepare("UPDATE $wpdb->posts SET post_status = 'pending' WHERE (post_type LIKE 'wpsc-product' or post_type LIKE 'wpsc-variation') AND NOT ID IN (". implode(",", $imported_ids) .")")
                            ); 
                        }else{
                            $wpdb->query( 
                                $wpdb->prepare("UPDATE $wpdb->posts SET post_status = 'pending' WHERE (post_type LIKE 'wpsc-product' or post_type LIKE 'wpsc-variation')")
                            );
                        }
                    }
                }
            }
        }
        
        $custom_fileds   = array();
        pelm_load_custom_fields($plem_price_settings, $custom_fileds);
    }
}



global $categories, $cat_asoc;
$categories = array();
$cat_asoc   = array();

function pelm_list_categories_callback($category, $level, $parameters)
{
    global $categories, $cat_asoc;
    $cat = new stdClass();
    $cat->category_id     = $category->term_id;
    $cat->category_name   = $category->name;
    $cat->category_slug   = urldecode($category->slug);
    $cat->category_parent = $category->parent;
    $categories[] = $cat;   
    $cat_asoc[$cat->category_id] = $cat;
};

$res = wpsc_list_categories('pelm_list_categories_callback');


$_num_sample = (1/2).'';
$args = array(
     'post_type' => array('wpsc-product','wpsc-variation')
    ,'posts_per_page' => -1
    ,'ignore_sticky_posts' => false
    ,'post_status' => 'any'
    ,'orderby' => $orderby 
    ,'order' => $sort_order
    ,'fields' => 'ids'
);

if($product_status) {
    $args['post_status'] = $product_status;
}
//else
//    $args['post_status'] = 'any';

if($orderby_key) {
    $args['meta_key'] = $orderby_key;
}

$meta_query = array();

if(isset($product_name) && $product_name) {
    $name_postids = $wpdb->get_col("select ID from $wpdb->posts where post_title like '%$product_name%' ");
    $args['post__in'] = empty($name_postids) ? array(-9999) : $name_postids;
}

$tax_query = array();

if($product_category) {
    $tax_query[] =  array(
                        'taxonomy' => 'wpsc_product_category',
                        'field' => 'id',
                        'terms' => $product_category
                    );
}



if($product_tag) {
    $tax_query[] =  array(
                        'taxonomy' => 'product_tag',
                        'field' => 'id',
                        'terms' => $product_tag
                    );
}

if($sku) {
    $meta_query[] =    array(
                        'key' => '_wpsc_sku',
                        'value' => $sku,
                        'compare' => 'LIKE'
                    );
}

if(!empty($tax_query)) {
    $args['tax_query']  = $tax_query;
}

if(!empty($meta_query)) {
    $args['meta_query'] = $meta_query;
}


$tags           = array();
foreach((array)get_terms('product_tag', array('hide_empty' => false )) as $pt){
    $t = new stdClass();
    $t->id   = $pt->term_id;
    $t->slug = urldecode($pt->slug);
    $t->name = $pt->name;
    $tags[]     = $t;
}

$count = 0;

$mu_res = 0;
if((pelm_read_sanitized_request_parm("mass_update_val"))) {

    $products_query = new WP_Query($args);
    $count          = $products_query->found_posts;
    $IDS            = $products_query->get_posts();  
 
    foreach ($IDS as $id) {
      
        if(pelm_read_sanitized_request_parm("mass_update_override")) {
            $override_price     = get_post_meta($id, '_wpsc_special_price', true);
            if(is_numeric($override_price)) {
                 $override_price = floatval($override_price);
                if(pelm_read_sanitized_request_parm("mass_update_percentage")) {
                    update_post_meta($id, '_wpsc_special_price', $override_price * (1 + floatval(pelm_read_sanitized_request_parm("mass_update_val")) / 100));
                }else{
                    update_post_meta($id, '_wpsc_special_price', $override_price + floatval(pelm_read_sanitized_request_parm("mass_update_val")));
                }
            }
        }else{
            $price              = get_post_meta($id, '_wpsc_price', true);
            if(is_numeric($price)) {
                $price = floatval($price);
                if(pelm_read_sanitized_request_parm("mass_update_percentage")) {
                    update_post_meta($id, '_wpsc_price', $price * (1 + floatval(pelm_read_sanitized_request_parm("mass_update_val")) / 100));
                }else{
                    update_post_meta($id, '_wpsc_price', $price + floatval(pelm_read_sanitized_request_parm("mass_update_val")));
                }
            }
        }
        $mu_res++;
    }
    wp_reset_postdata();
}

//$products       = array();
$post_statuses = get_post_stati();
$pos_stat = get_post_statuses();        
foreach($post_statuses as $name => $title){
    if(isset($pos_stat[$name])) {
        $post_statuses[$name] = $pos_stat[$name];
    }        
}
$args['posts_per_page'] = $limit; 
$args['paged'] = $page_no;

$products_query = new WP_Query($args);
$count          = $products_query->found_posts;    
$IDS            = $products_query->get_posts();    

if($count == 0) {
    $IDS = array();
    unset($args['fields']);
    $products_query = new WP_Query($args);
    $count          = $products_query->found_posts;    
    while($products_query->have_posts()){
        $products_query->next_post();
        $IDS[] = $products_query->post->ID; 
    }     
    wp_reset_postdata();
}

$IDS = array_unique($IDS);

function pelm_product_render(&$IDS, $op,&$df = null)
{
    global $wpdb, $custom_fileds, $impexp_settings, $custom_export_columns, $tbl_post_fields;

    $mvd =  ",";
    
    $p_ids = is_array($IDS) ? $IDS : array($IDS);
    
    $fcols = array();    
    foreach($custom_fileds as $cfname => $cfield){
        if($cfield->type == "post") {
            if(isset($tbl_post_fields[$cfield->source])) {
                $fcols[] = $cfield->source;
            }
        }
    }
    $id_list = implode(",", $p_ids);
    if(!$id_list) {
        $id_list = 9999999;
    }
    $raw_data = $wpdb->get_results("select ID, post_name ". (!empty($fcols) ? "," . implode(",", $fcols) : "") ." from $wpdb->posts where ID in (". $id_list .")", OBJECT_K); 
 
    
    $p_n = 0;
    foreach($p_ids as $id) {
      
        $prod = new stdClass();
        $prod->id         = $id;
      
        if(!(pelm_read_sanitized_request_parm("do_export"))) {
            $prod->type           = get_post_type($id);
            $prod->parent         = get_ancestors($id, 'wpsc-product');
            if(!empty($prod->parent)) {
                 $prod->parent = $prod->parent[0];
            } else {
                  $prod->parent = null;
            }    
        }
      
        if(pelm_fn_show_filed('sku')) {
            $prod->sku        = get_post_meta($id, '_wpsc_sku', true);
        }
      
        if(pelm_fn_show_filed('slug')) {
            $prod->slug           = pelm_to_utf8(urldecode($raw_data[$id]->post_name));
        }
    
        if(pelm_fn_show_filed('categories')) {    
            $prod->categories = wp_get_object_terms($id, 'wpsc_product_category', array('fields' => 'ids'));
        }
      
        if(!(pelm_read_sanitized_request_parm("do_export")) && $prod->parent) {
            if(pelm_fn_show_filed('categories')) {
                $prod->categories = wp_get_object_terms($prod->parent, 'wpsc_product_category', array('fields' => 'ids'));
            }
        }
      
        if((pelm_read_sanitized_request_parm("do_export"))) {
            if(pelm_fn_show_filed('categories')) {    
                $prod->categories_names     = implode("$mvd ", wp_get_object_terms($id, 'wpsc_product_category', array('fields' => 'names')));
                unset($prod->categories);
            }
        }
      
      
      
      
        if(pelm_fn_show_filed('name')) {    
            $prod->name               = get_the_title($id);
        }
      
        if(pelm_fn_show_filed('stock')) {    
            $prod->stock              = get_post_meta($id, '_wpsc_stock', true);
            if(!$prod->stock) {
                $prod->stock = '';
            }
        }

        if(pelm_fn_show_filed('price')) {    
            $prod->price              = get_post_meta($id, '_wpsc_price', true);
          
        }

        if(pelm_fn_show_filed('override_price')) {    
            $prod->override_price     = get_post_meta($id, '_wpsc_special_price', true);
          
        }    
     
     
     
        foreach($custom_fileds as $cfname => $cfield){ 
            if($cfield->type == "term") {
                if((pelm_read_sanitized_request_parm("do_export"))) {
                    $prod->{$cfname} = implode("$mvd ", wp_get_object_terms($id, $cfield->source, array('fields' => 'names')));
                } else{
                    if($prod->parent) {
                        $prod->{$cfname} = wp_get_object_terms($prod->parent, $cfield->source, array('fields' => 'ids'));
                    } else {
                        $prod->{$cfname} = wp_get_object_terms($id, $cfield->source,  array('fields' => 'ids'));
                    }
                }    
            }elseif($cfield->type == "meta") {
           
                $decoder = "";
                if(isset($cfield->options)) {
                    if(isset($cfield->options->serialization)) {
                        $decoder = $cfield->options->serialization;
                    }    
                }
            
                $prod->{$cfname} = pelm_fn_get_meta_by_path($id, $cfield->source, $decoder);
            
                if(isset($cfield->options)) {
                    if(isset($cfield->options->format)) {
                        if($cfield->options->format == "json_array") {    
                            if((pelm_read_sanitized_request_parm("do_export"))) {
                                 $prod->{$cfname} = implode($mvd, json_decode($prod->{$cfname}));
                            } else {
                                $prod->{$cfname} = implode(",", json_decode($prod->{$cfname}));
                            }
                        }else if((pelm_read_sanitized_request_parm("do_export"))) {
                            if(strpos($cfield->options->format, '_array') !== false) {
                                if(is_array($prod->{$cfname})) {
                                                       $prod->{$cfname} = implode("$mvd", $prod->{$cfname});
                                }
                            }
                        }
                    }    
                }
            
            }elseif($cfield->type == "post") {
                $prod->{$cfname} = $raw_data[$id]->{$cfield->source};
            }
       
            if($cfield->options->formater == "checkbox") {
                if($prod->{$cfname} !== null) {
                    $prod->{$cfname} = $prod->{$cfname} . "";
                } else if(isset($cfield->options->null_value)) {
                    if($cfield->options->null_value) {
                        $prod->{$cfname} = $prod->{$cfname} = $cfield->options->null_value."";
                    }
                }
            }
        }

     
     
        if(pelm_fn_show_filed('status')) {
            $prod->status       = get_post_status($id);
        }
      
        $ptrems = get_the_terms($id, 'product_tag');
      
        if(pelm_fn_show_filed('tags')) {
            if((pelm_read_sanitized_request_parm("do_export"))) {
                $prod->tags_names         = null;
                if($ptrems) {
                    foreach((array)$ptrems as $pt){
                        if(!isset($prod->tags_names)) { 
                            $prod->tags_names = array();
                        }
                        
                        $prod->tags_names[] = $pt->name;
                    }
                    $prod->tags_names = implode("$mvd ", $prod->tags_names);
                }
            }else{
                $prod->tags               = null;
                if($ptrems) {
                    foreach((array)$ptrems as $pt){
                        if(!isset($prod->tags)) { 
                            $prod->tags = array();
                        }
                        
                        $prod->tags[] = $pt->term_id;
                    }
                }
            }
        }
      
        $pr_meta = get_post_meta($id, '_wpsc_product_metadata', true);
      
        if(is_string($pr_meta)) {
            $pr_meta = unserialize($pr_meta);
        }
      
        if(!is_array($pr_meta) || !$pr_meta) {
             $pr_meta = array();
        }
       
      
      
        if(!isset($pr_meta['dimensions'])) {
            $pr_meta['dimensions'] = array(
            'length' => 0,
                 'width'  => 0,
                 'height' => 0
            );
        }    
      
        if(!isset($pr_meta['dimensions_unit'])) {
            $pr_meta['dimensions_unit'] = "in";
        }    
      
        if(!isset($pr_meta['weight_unit'])) {
            $pr_meta['weight_unit'] = "pound";
        }    
      
        $dimensions = $pr_meta['dimensions'];
      
        if(pelm_fn_show_filed('weight')) {
            $prod->weight       = isset($pr_meta['weight']) ? round(pelm_fn_convert_unit($pr_meta['weight'], 'pound', $pr_meta['weight_unit']), 2) .' '.  $pr_meta['weight_unit'] : "";
        }
        if(pelm_fn_show_filed('height')) {
            $prod->height       = isset($dimensions['height']) ? $dimensions['height'] .' '.$pr_meta['dimensions_unit'] : "";
        }
        if(pelm_fn_show_filed('width')) {
            $prod->width        = isset($dimensions['width']) ? $dimensions['width']  .' '. $pr_meta['dimensions_unit'] : "";
        }
        if(pelm_fn_show_filed('length')) {
            $prod->length       = isset($dimensions['length']) ? $dimensions['length'] .' '. $pr_meta['dimensions_unit'] : "";
        }
        if(pelm_fn_show_filed('taxable')) {
            $prod->taxable      = isset($pr_meta['wpec_taxes_taxable_amount']) ? $pr_meta['wpec_taxes_taxable_amount'] : "";
        }
        if(pelm_fn_show_filed('loc_shipping')) {
            $prod->loc_shipping = isset($pr_meta['shipping']) ? $pr_meta['shipping']['local'] : "";
        }
        if(pelm_fn_show_filed('int_shipping')) {
            $prod->int_shipping = isset($pr_meta['shipping']) ? $pr_meta['shipping']['international'] : "";
        }
        
        if(pelm_fn_show_filed('image')) {    
            $prod->image = null;
          
            if(has_post_thumbnail($id)) {
                $thumb_id    = get_post_thumbnail_id($id);
            
                $prod->image = new stdClass;
                $prod->image->id    = $thumb_id;
            
                $prod->image->src   = wp_get_attachment_image_src($thumb_id, 'full');
                if(is_array($prod->image->src)) {
                        $prod->image->src = $prod->image->src[0];
                }
            
                $prod->image->thumb = wp_get_attachment_image_src($thumb_id, 'thumbnail');
                if(is_array($prod->image->thumb)) {
                    $prod->image->thumb = $prod->image->thumb[0];
                }
            
                if(!$prod->image->src) {
                    $prod->image = null;
                }
            
                if((pelm_read_sanitized_request_parm("do_export"))) {
                    if($prod->image) {
                        $prod->image = $prod->image->src;
                    }else{
                        $prod->image = "";
                    }
                }
            
            }
        }
     
        if(pelm_fn_show_filed('gallery')) {    
            $prod->gallery = null;
            $gallery = get_post_meta($id, "_wpsc_product_gallery", true);
          
            if(is_string($gallery)) {
                $gallery = explode(",", $gallery);
            }
          
            if(!is_array($gallery)) {
                $gallery = array();
            }
          
            if($gallery) {
                $prod->gallery = array();
                foreach($gallery as $ind => $img_id){
                    if(pelm_read_sanitized_request_parm("do_export")) {
                        $img = wp_get_attachment_image_src($img_id, 'full');
                        if(is_array($img)) {
                            $img = $img[0];
                        }
                        $prod->gallery[] = $img;
                    }else{
                        $gimg = new stdClass;
                        $gimg->id    = $img_id; 
                        $gimg->src   = wp_get_attachment_image_src($img_id, 'full');
                        if(is_array($gimg->src)) {
                            $gimg->src = $gimg->src[0];
                        }
                      
                        $gimg->thumb = wp_get_attachment_image_src($img_id, 'thumbnail'); 
                        if(is_array($gimg->thumb)) {
                            $gimg->thumb =$gimg->thumb[0];
                        }
                      
                        if($gimg->src) {
                            $prod->gallery[] = $gimg;
                        } 
                    }
                }
              
                if(pelm_read_sanitized_request_parm("do_export")) {
                    $prod->gallery = implode($mvd, $prod->gallery);
                }
            }
        }
      
 
  
        if($op == "json") {
            if($p_n > 0) echo ",";
            echo json_encode($prod,0,5);       
        }elseif($op == "export") {
            if($p_n == 0) {    
                if($impexp_settings->use_custom_export) {
                    fputcsv($df, $custom_export_columns, $impexp_settings->delimiter2);
                }else{         
                      $pprops =  (array)$prod;
                      $props = array();
                    foreach( $pprops as $key => $pprop){
                        $props[] = $key;
                    }
                    fputcsv($df, $props);
                }
            }
         
            if($impexp_settings->use_custom_export) {
                $eprod = array();
                foreach($custom_export_columns as $prop){
                      $eprod[] = $prod->$prop;
                }
                pelm_array_escape($eprod);
                fputcsv($df, $eprod, $impexp_settings->delimiter2);
            }else{
                $aprod = (array)$prod;
                pelm_array_escape($aprod);
                fputcsv($df, $aprod, $impexp_settings->delimiter2);
            }
        
        
        }elseif($op == "data") {
            return $prod;
        }
        $p_n++;
        unset($prod);
      
    }
};

if((pelm_read_sanitized_request_parm("do_export"))) {
    if(pelm_read_sanitized_request_parm("do_export") == "1") {
    
        $filename = "data_export_" . date("Y-m-d") . ".csv";
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download  
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-type:application/csv;charset=UTF-8");
        header("Content-Transfer-Encoding: binary");
        echo "\xEF\xBB\xBF"; // UTF-8 BOM
        
        $df = fopen("php://output", 'w');
       
        ///////////////////////////////////////////////////
        pelm_product_render($IDS, "export", $df);
        ///////////////////////////////////////////////////
        
        fclose($df);
        
        die();
        exit;  
        return;
    }
}


?>

<div class="scope_body">
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo esc_attr(get_option('blog_charset')); ?>" />
<script type="text/javascript">
function pelm_get_request_url(){
    if(window.pelm_ajaxurl)
        return pelm_ajaxurl + "?action=pelm_price_frame_display&elpm_shop_com=<?php echo esc_attr($elpm_shop_com); ?>";
    else{
        return window.location.href.split("?")[0] + + "?action=pelm_price_frame_display&elpm_shop_com=<?php echo esc_attr($elpm_shop_com); ?>";
    }
}

var _wpColorScheme = {"icons":{"base":"#999","focus":"#2ea2cc","current":"#fff"}};

var pelm_ajaxurl = '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';
var pelm_plugin_version = '<?php echo esc_attr($plem_price_settings['plugin_version']); ?>';

var pelm_localStorage_clear_flag = false;
function pelm_cleanLayout(){
    localStorage.clear();
    pelm_localStorage_clear_flag = true;
    doLoad();
    return false;
}

</script>
<?php

wp_register_script('fictive-script', "www.fictive-script.com/fictive-script.js");
wp_enqueue_script('fictive-script');

wp_register_style('fictive-style', "www.fictive-script.com/fictive-script.css");
wp_enqueue_style('fictive-style');

global $wp_scripts;
foreach($wp_scripts as $skey => $scripts){
    if(is_array($scripts)) {
        if(!empty($scripts)) {
            if(in_array("fictive-script", $scripts)) {
                foreach($scripts as $script){
                    wp_dequeue_script($script);
                }
            }
        }
    }
}

global $wp_styles;
foreach($wp_styles as $skey => $styles){
    if(is_array($styles)) {
        if(!empty($styles)) {
            if(in_array("fictive-style", $styles)) {
                foreach($styles as $style){
                    wp_dequeue_style($style);
                }
            }
        }
    }
}

wp_enqueue_script('moment');    
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-dialog');
wp_enqueue_script('jquery-ui-sortable');
wp_enqueue_script('jquery-ui-resizable');
wp_enqueue_script('jquery-ui-draggable');

if($use_content_editior) {
    wp_enqueue_script('word-count');
    wp_enqueue_script('editor');
    wp_enqueue_script('quicktags');
    wp_enqueue_script('wplink');
    wp_enqueue_script('wpdialogs-popup');
    wp_print_styles('wp-jquery-ui-dialog');
}else{
    wp_print_styles('wp-jquery-ui-dialog');
}


wp_enqueue_script('moment', $excellikepricechangeforwoocommerceandwpecommercelight_baseurl. 'core/m/moment.js', array('jquery'));
wp_enqueue_script('pikaday', $excellikepricechangeforwoocommerceandwpecommercelight_baseurl. 'core/pday/pikaday.js', array('jquery'));
wp_enqueue_script('zeroclipboard-hst', $excellikepricechangeforwoocommerceandwpecommercelight_baseurl. 'core/zc/ZeroClipboard.js', array('jquery'));
wp_enqueue_script('handsontable', $excellikepricechangeforwoocommerceandwpecommercelight_baseurl. 'core/jquery.handsontable.js', array('jquery'));


wp_enqueue_style('pikaday', $excellikepricechangeforwoocommerceandwpecommercelight_baseurl. 'core/pday/pikaday.css', array());
wp_enqueue_style('handsontable', $excellikepricechangeforwoocommerceandwpecommercelight_baseurl. 'core/jquery.handsontable.css', array());

if($use_image_picker || $use_content_editior || pelm_fn_show_filed('image')  || pelm_fn_show_filed('gallery')) {
    wp_enqueue_media();    
}

wp_print_scripts();

wp_enqueue_style('removerow', $excellikepricechangeforwoocommerceandwpecommercelight_baseurl. 'core/removeRow.css', array());
wp_enqueue_script('removerow', $excellikepricechangeforwoocommerceandwpecommercelight_baseurl. 'core/removeRow.js', array('jquery'));

wp_enqueue_style('chosen', $excellikepricechangeforwoocommerceandwpecommercelight_baseurl. 'lib/chosen.min.css', array());
wp_enqueue_script('chosen', $excellikepricechangeforwoocommerceandwpecommercelight_baseurl. 'lib/chosen.jquery.min.js', array('jquery'));

wp_enqueue_style('pelmprice', $excellikepricechangeforwoocommerceandwpecommercelight_baseurl. 'assets/style.css', array());

wp_print_styles('pikaday');
wp_print_styles('handsontable');

    

if(isset($plem_price_settings['enable_delete'])) { 
    if($plem_price_settings['enable_delete']) {
        wp_print_styles('removerow');
        wp_print_scripts('removerow');
    } 
}

wp_print_styles('chosen');
wp_print_scripts('chosen');
wp_print_styles('pelmprice');

?>

</head>
<div class="scope_body_content pelm_body">
<?php if($use_content_editior) { ?>
<div id="content-editor" >
    <div>
    <?php
    $args = array(
    'textarea_rows' => 20,
    'teeny' => true,
    'quicktags' => true,
    'media_buttons' => true
    );
         
    wp_editor('', 'editor', $args);
    _WP_Editors::editor_js();
    ?>
    <div class="cmds-editor">
       <a class="metro-button" id="cmdContentSave" ><?php echo esc_html__("Save", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></a>
       <a class="metro-button" id="cmdContentCancel" ><?php echo esc_html__("Cancel", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></a>   
       <div style="clear:both;" ></div>
    </div>
    </div>
</div>
<?php } ?>

<div class="header pelm_menu_wrapper">
<ul class="menu pelm_menu">
  <?php if(pelm_read_sanitized_request_parm('action', "") == 'pelm_price_frame_display') { ?>
  <li>
   <a class="cmdBackToWP" href="<?php echo "admin.php?page=excellikepricechangeforwoocommerceandwpecommercelight-wooc"; ?>" > <?php echo esc_html__("Dock in admin", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?> </a>
  </li>
  <?php }else{ ?>
  <li>
   <a class="cmdFullScreen" href="#" > <?php echo esc_html__("Full screen", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?> </a>
   <script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery("a.cmdFullScreen").attr("href",pelm_get_request_url());
    });
   </script>
  </li>
  <?php } ?>
  
  <li><span class="undo"><button id="cmdUndo" onclick="pelm_undo();" ><?php echo esc_html__("Undo", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></button></span></li>
  <li><span class="redo"><button id="cmdRedo" onclick="pelm_redo();" ><?php echo esc_html__("Redo", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></button></span></li>
  <li>
   <span><span> <?php echo esc_html__("Export/Import", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?> &#9655;</span></span>
   <ul>
     <li><span><button onclick="pelm_do_export();return false;" ><?php echo esc_html__("Export CSV", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></button></span></li>
     <li><span><button onclick="pelm_do_import();return false;" ><?php echo esc_html__("Update from CSV", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></button></span></li>
     <li><span><button onclick="pelm_showSettings();return false;" ><?php echo esc_html__("Custom import settings", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></button></span></li>
   </ul>
  </li>
  <li>
   <span><span> <?php echo esc_html__("Options", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?> &#9655;</span></span>
   <ul>
     <li><span><button onclick="if(window.self !== window.top) window.parent.location = 'admin.php?page=excellikepricechangeforwoocommerceandwpecommercelight-settings';  else window.location = 'admin.php?page=excellikepricechangeforwoocommerceandwpecommercelight-settings';" > <?php echo esc_html__("Settings", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?> </button></span></li>
     <li><span><button onclick="pelm_cleanLayout();return false;" ><?php echo esc_html__("Clean layout cache...", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></button></span></li>
     <li><span><a target="_blank" href="<?php echo "http://www.holest.com/excel-like-product-manager-wpecommerce-documentation"; ?>" > <?php echo esc_html__("Help", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?> </a></span></li>
   </ul>
  </li>
  
  <li style="width:200px;">
  <input style="width:130px;display:inline-block;" type="text" id="activeFind" placeholder="<?php echo esc_html__("active data search...", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?>" />
  <span style="display:inline-block;" id="search_matches"></span>
  <button id="cmdActiveFind" >&#9655;&#9655;</button> 
  </li>

  
  <li style="float:right;" >
   <table>
     <tr><td rowspan="2" ><?php echo esc_html__("Input units", 'productexcellikemanager');?>:&nbsp;&nbsp;</td><td><?php echo esc_html__("Weight", 'productexcellikemanager');?></td><td><?php echo esc_html__("Height", 'productexcellikemanager');?>/<?php echo esc_html__("Width", 'productexcellikemanager');?>/<?php echo esc_html__("Length", 'productexcellikemanager');?></td></tr> 
     <tr>
         
         <td>
            <select class="save-state" id="weight_unit">
                <option value="pound" selected="selected">pounds</option>
                <option value="ounce">ounces</option>
                <option value="gram">grams</option>
                <option value="kilogram">kilograms</option>
            </select>
         </td>
         <td>
            <select class="save-state" id="dimensions_unit">
                <option value="in" selected="selected">inches</option>
                <option value="cm">cm</option>
                <option value="meter">meters</option>
            </select>
         </td>
     </tr> 
   </table>
  </li>
</ul>

</div>
<div class="content">
<div class="filter_panel opened">
<span class="filters_label" ><span class="toggler"><span><?php echo esc_html__("Filters", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?></span></span></span>
<div class="filter_holder<?php if(pelm_fn_show_filed('image')) { echo " with-image";
} ?>">
  
  <div class="filter_option" id="refresh-button-holder" >
     
     <div id="product-preview" >
        <p></p>
     </div>
    
     <input id="cmdRefresh" type="submit" class="cmd" value="<?php echo esc_html__("Refresh", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?>" onclick="doLoad();" />
  </div>
  <div class="refresh-button-spacer"  >
  </div>

  
  
  <div class="filter_option">
     <label><?php echo esc_html__("SKU", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?></label>
     <input placeholder="<?php echo esc_html__("Enter part of SKU...", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?>" type="text" name="sku" value="<?php echo esc_attr($sku);?>"/>
  </div>
  
  <div class="filter_option">
     <label><?php echo esc_html__("Product Name", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?></label>
     <input placeholder="<?php echo esc_html__("Enter part of name...", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?>" type="text" name="product_name" value="<?php echo esc_attr($product_name);?>"/>
  </div>
  
  <div class="filter_option">
     <label><?php echo esc_html__("Category", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?></label>
     <select data-placeholder="<?php echo esc_html__("Chose categories...", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?>" class="inputbox" multiple name="product_category" >
        <option value=""></option>
        <?php
        foreach($categories as $category){
            $par_ind = '';
            if($category->category_parent) {
                $par = $cat_asoc[$category->category_parent];
                while($par){
                    $par_ind.= ' - ';
                    $par = $cat_asoc[$par->category_parent];
                }
            }
            echo '<option value="'. esc_attr($category->category_id).'" >'.esc_attr($par_ind.$category->category_name).'</option>';
        }
        
        ?>
     </select>
  </div>
  
 <div class="filter_option">
     <label><?php echo esc_html__("Tags", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?></label>
     <select data-placeholder="<?php echo esc_html__("Chose tags...", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?>" class="inputbox" multiple name="product_tag" >
        <option value=""></option>
        <?php
        foreach($tags as $tag){
            echo '<option value="'.esc_attr($tag->id).'" >'.esc_attr($tag->name).'</option>';
        }
        
        ?>
     </select>
  </div>
  
  <div class="filter_option">
     <label><?php echo esc_html__("Product Status", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?></label>
     <select data-placeholder="<?php echo esc_html__("Chose status...", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?>"  class="inputbox" name="product_status" multiple >
        <option value="" ></option>
        <?php
        foreach($post_statuses as $val => $title){
            ?>
                <option value="<?php echo esc_attr($val); ?>"><?php echo esc_html__($title, 'wp-e-commerce');?></option>
            <?php
        }

        ?>
     </select>
  </div>
  

  <br/>
  <hr/>
  
  <div class="filter_option mass-update">
      <label><?php echo esc_html__("Mass update by filter criteria: ", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></label> 
      <input style="width:140px;float:left;" placeholder="<?php echo sprintf(__("[+/-]X%s or [+/-]X", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'), '%'); ?>" type="text" id="txtMassUpdate" value="" /> 
      <button id="cmdMassUpdate" class="cmd" onclick="massUpdate(false);return false;" style="float:right;"><?php echo esc_html__("Mass update price", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></button>
      <button id="cmdMassUpdateOverride" class="cmd" onclick="massUpdate(true);return false;" style="float:right;"><?php echo esc_html__("Mass update sales price", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></button>
      
  </div>
  <div style="clear:both;" class="filter-panel-spacer-bottom" ></div>
  <a class="pro-upgrade blink" target="_blank" style="color: cyan;font-size: 12px;" href="https://holest.com/bulk-product-manager-for-woo-commerce"><?php echo esc_html__("To edit/import ALL get PRO version &gt;&gt;", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?> </a>
</div>
</div>

<div id="dg_wpsc" class="hst_dg_view fixed-<?php echo esc_attr($plem_price_settings['fixedColumns']); ?>" style="margin-left:-1px;margin-top:0px;overflow: scroll;background:#FBFBFB;">
</div>

</div>
<div class="footer">
 <div class="pagination">
   <label for="txtLimit" ><?php echo esc_html__("Limit:", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?></label><input id="txtlimit" class="save-state" style="width:40px;text-align:center;" value="<?php echo esc_attr($limit);?>" plem="<?php 
	$arr =array_keys($plem_price_settings);
	sort($arr);
	echo esc_attr($plem_price_settings[reset($arr)]); 
	?>"  />
   <?php
    if($limit && ceil($count / $limit) > 1) {
        ?>
           <input type="hidden" id="paging_page" value="<?php echo esc_attr($page_no) ?>" />    
           
        <?php
        if($page_no > 1) {
            ?>
           <span class="page_number" onclick="setPage(this,1);return false;" ><<</span>
           <span class="page_number" onclick="setPage(this,'<?php esc_attr($page_no - 1); ?>');return false;" ><</span>
            <?php
        }
          
        for($i = 0; $i < ceil($count / $limit); $i++ ){
            if(($i + 1) < $page_no - 2 ) { continue;
            }
            if(($i + 1) > $page_no + 2) {
				 echo "<label>...</label>";              
				 break;
            }
            ?>
              <span class="page_number <?php echo esc_attr(($i + 1) == $page_no ? " active " : "");  ?>" onclick="setPage(this,'<?php echo esc_attr($i + 1); ?>');return false;" ><?php echo esc_attr($i + 1); ?></span>
            <?php	        
        }
          
        if($page_no < ceil($count / $limit)) {
            ?>
           <span class="page_number" onclick="setPage(this,'<?php echo esc_attr($page_no + 1); ?>');return false;" >></span>
           <span class="page_number" onclick="setPage(this,'<?php echo esc_attr(ceil($count / $limit)); ?>');return false;" >>></span>
            <?php
        }
          
    }
    ?>
   <span class="pageination_info"><?php echo sprintf(__("Page %s of %s, total %s products by filter criteria", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'), esc_attr($page_no), esc_attr(ceil($count / $limit)), esc_attr($count)); ?></span>
   
 </div>
 
 <span class="note" style="float:right;"><?php echo esc_html__("*All changes are instantly autosaved", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?></span>
 <span class="wait save_in_progress" ></span>
 
</div>


<form id="operationFRM" method="POST" >
    <input name="pelm_security" type="hidden" value="<?php echo esc_attr(pelm_get_nonce("pelm_nonce")); ?>" />
    <div></div>
</form>

<script type="text/javascript">

try{
    if(!window.wp)
        window.wp = {};    
    if(!wp.media)
        wp.media = {editor: { send:{attachment: null} }};
    if(!jQuery.fn.accordion)
        jQuery.fn.accordion = function(){};
    if(!jQuery.fn.datepicker)
        jQuery.fn.datepicker = function(){}
}catch(ex){
//
}


var imagePicker   = null;
var galleryPicker = null;
var upload_dir_data = <?php echo json_encode(wp_upload_dir()) ?>;

var DG          = null;
var tasks      = {};
var variations_skip = <?php echo json_encode($variations_skip); ?>;
var categories = <?php echo json_encode($categories);?>;
var tags       = <?php echo json_encode($tags);?>;
var asoc_cats = {};
var asoc_tags = {};

var ContentEditorCurrentlyEditing = {};
var ImageEditorCurrentlyEditing = {};

var ProductPreviewBox = jQuery("#product-preview");
var ProductPreviewBox_title = jQuery("#product-preview p");

var SUROGATES  = {};

var sortedBy     = 0;
var sortedOrd    = true;

window.onbeforeunload = function() {
    try{
        pelmStoreState();
    }catch(e){}
    
    var n = 0;
    for(var key in tasks)
        n++;
     
    if(n > 0){
      pelm_do_save();
      return "<?php echo esc_html__("Transactions ongoing. Plese wait a bit more for them to complete!", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?>";
    }else
      return;       
}

for(var c in categories){
  asoc_cats[categories[c].category_id] = categories[c].category_name;
}

for(var t in tags){
  asoc_tags[tags[t].id] = tags[t].name;
}

var keepAliveTimeoutHande = null;
var resizeTimeout
  , availableWidth
  , availableHeight
  , $window = jQuery(window)
  , $dg     = jQuery('#dg_wpsc');
  
$ = jQuery;  

var calculateSize = function () {
  var offset = $dg.offset();
  
  jQuery('div.content').outerHeight(jQuery(window).height() - (jQuery('#wpadminbar').outerHeight() || 0) - (jQuery(".pelm_menu_wrapper").outerHeight() || 0));

  
  availableWidth = (jQuery('div#wpbody-content').innerWidth() || jQuery(".pelm_body").innerWidth()) - jQuery(".filter_panel").innerWidth();
  
  if(jQuery(".filter_panel").is(".closed")){
      availableWidth -= parseInt(jQuery(".filter_panel").css("right"));
  }
  
  availableHeight = jQuery('.pelm_body div.content').innerHeight() - jQuery(".pelm_body .footer").innerHeight()
  
  
  jQuery('.filter_panel').css('height',(availableHeight ) + 'px');
  
  if(DG)
    DG.updateSettings({ width: availableWidth, height: availableHeight });
  jQuery('.filters_label .toggler').outerHeight(jQuery('.filter_holder').innerHeight() + 4);
  
  jQuery("#wpbody-content").css("padding-bottom",0);
};

$window.on('resize', calculateSize);

calculateSize();

jQuery(document).ready(function(){calculateSize();});
jQuery(window).load(function(){calculateSize();});  



function setPage(sender,page){
    jQuery('#paging_page').val(page);
    jQuery('.page_number').removeClass('active');
    jQuery(sender).addClass('active');
    doLoad();
    return false;
}

var pending_load = 0;

function getSortProperty(){
    if(!DG)    
        DG = jQuery('#dg_wpsc').data('handsontable');
    
    if(!DG)
        return "id";
    
    return DG.colToProp( DG.sortColumn);
}

function doLoad(withImportSettingsSave){
    pending_load++;
    if(pending_load < 6){
        var n = 0;
        for(var key in tasks)
            n++;
            
        if(n > 0) {
          setTimeout(function(){
            doLoad();
          },2000);
          return;
        }
    }

    var POST_DATA = {};
    
    POST_DATA.sortOrder            = DG.sortOrder ? "ASC" : "DESC";
    POST_DATA.sortColumn           = getSortProperty();
    POST_DATA.limit                = jQuery('#txtlimit').val();
    POST_DATA.page_no              = jQuery('#paging_page').val();
    
     POST_DATA.sku                  = jQuery('.filter_option *[name="sku"]').val();
    POST_DATA.product_name         = jQuery('.filter_option *[name="product_name"]').val();
    POST_DATA.product_tag          = jQuery('.filter_option *[name="product_tag"]').val();
    POST_DATA.product_category     = jQuery('.filter_option *[name="product_category"]').val();
    POST_DATA.product_status       = jQuery('.filter_option *[name="product_status"]').val();
    
    if(withImportSettingsSave){
      var settings = {};
      jQuery('#settings-panel INPUT[name],#settings-panel TEXTAREA[name],#settings-panel SELECT[name]').each(function(i){
        if(jQuery(this).attr('type') == "checkbox")
            POST_DATA[jQuery(this).attr('name')] = jQuery(this)[0].checked ? 1 : 0;
        else
            POST_DATA[jQuery(this).attr('name')] = jQuery(this).val() instanceof Array ? jQuery(this).val().join(",") : jQuery(this).val(); 
      });
      
      POST_DATA.save_import_settings = 1;
    }
    
    jQuery('#operationFRM > div').empty();
    
    for(var key in POST_DATA){
        if(POST_DATA[key])
            jQuery('#operationFRM > div').append("<INPUT type='hidden' name='" + key + "' value='" + POST_DATA[key] + "' />");
    }
    
    jQuery('#operationFRM').submit();
}

function massUpdate(update_override){
    if(!jQuery.trim(jQuery('#txtMassUpdate').val())){
      alert("<?php echo esc_html__("Enter value first!", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?>");
      return;
    } 

    if(confirm("<?php echo esc_html__("Update proiduct price for all products matched by filter criteria (this operation can not be undone)?", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?>")){
        var POST_DATA = {};
        
        POST_DATA.mass_update_val        = parseFloat(jQuery('#txtMassUpdate').val()); 
        POST_DATA.mass_update_percentage = (jQuery('#txtMassUpdate').val().indexOf("%") >= 0) ? 1 : 0;
        POST_DATA.mass_update_override   = update_override ? '1' : '0';
        
        POST_DATA.sortOrder            = DG.sortOrder ? "ASC" : "DESC";
        POST_DATA.sortColumn           = getSortProperty();
        POST_DATA.limit                = jQuery('#txtlimit').val();
        POST_DATA.page_no               = jQuery('#paging_page').val();
        
        POST_DATA.sku                  = jQuery('.filter_option *[name="sku"]').val();
        POST_DATA.product_name         = jQuery('.filter_option *[name="product_name"]').val();
        POST_DATA.product_tag          = jQuery('.filter_option *[name="product_tag"]').val();
        POST_DATA.product_category     = jQuery('.filter_option *[name="product_category"]').val();
        POST_DATA.product_status       = jQuery('.filter_option *[name="product_status"]').val();
        
        
        jQuery('#operationFRM > div').empty();
        
        for(var key in POST_DATA){
            if(POST_DATA[key])
                jQuery('#operationFRM > div').append("<INPUT type='hidden' name='" + key + "' value='" + POST_DATA[key] + "' />");
        }
        jQuery('#operationFRM').submit();
    }
}

var saveHandle = null;
var save_in_progress = false;
var id_index = null;

function build_id_index_directory(rebuild){
    if(rebuild)
        id_index = null;
    
    if(!id_index){
        id_index = [];
        var n = 0;
        DG.getData().map(function(s){
          if(id_index[s.id])
            id_index[s.id].ind = n;
          else
            id_index[s.id] = {ind:n,ch:[]}; 
          
          if(s.parent){
              if(id_index[s.parent])
                id_index[s.parent].ch.push(n);
              else
                id_index[s.parent] = {ind:-1,ch:[n]}; 
          }                
          n++;
        });
    }    
}

function pelm_do_save(){
    var update_data = JSON.stringify(tasks);        
    save_in_progress = true;
    jQuery(".save_in_progress").show();

    jQuery.ajax({
    url: pelm_get_request_url() + "&DO_UPDATE=1&diff=" + Math.random() + "&pelm_security=<?php echo esc_attr(pelm_get_nonce("pelm_nonce")); ?>",
    type: "POST",
    dataType: "json",
    data: update_data,
    success: function (data) {
        build_id_index_directory();
        
        //date.id
        if(data){
            for(var j = 0; j < data.length ; j++){
                    if(data[j].surogate){
                        var row_ind = SUROGATES[data[j].surogate];
                        for(var prop in data[j].full){
                            try{
                                if (data[j].full.hasOwnProperty(prop)) {
                                    DG.getSourceDataAtRow(row_ind)[prop] = data[j].full[prop];
                                }
                            }catch(e){}
                        }
                    }else if(data[j].full){
                        var row_ind = id_index[data[j].id];
                        for(var prop in data[j].full){
                            try{
                                if (data[j].full.hasOwnProperty(prop)) {
                                    DG.getSourceDataAtRow(row_ind)[prop] = data[j].full[prop];
                                }
                            }catch(e){}
                        }
                    }
            }
        }
            
        var updated = JSON.parse(update_data);
        for(key in updated){
         if(tasks[key]){
             
            var data_ind = - 1;
            if(data){
                for(var j = 0; j < data.length ; j++){
                    if(data[j].id == key){
                        data_ind = j;
                        break;
                    }
                }
            }
            
            //Update inherited values
            try{
                if(data_ind >= 0){
                    if(data[data_ind].id && data[data_ind].success){
                        var inf = id_index[data[data_ind].id];
                        if(inf.ind >= 0 && inf.ch.length > 0){
                            for(prop in tasks[key]){
                                if(jQuery.inArray(prop, variations_skip) >= 0){
                                   for(ch in inf.ch){
                                      DG.getData()[inf.ch[ch]][prop] = tasks[key][prop];
                                   }
                                }
                            }    
                        }
                    }
                }
            }catch(e){} 
         
            if(JSON.stringify(tasks[key]) == JSON.stringify(updated[key]))
                delete tasks[key];
         }
        }

        save_in_progress = false;
        jQuery(".save_in_progress").hide();
        
        DG.render();
        jQuery("#rcount").html(DG.countRows() - 1);

    },
    error: function(a,b,c){

        save_in_progress = false;
        jQuery(".save_in_progress").hide();
        pelm_call_save();
        
    }
    });
}

function pelm_call_save(){
    if(saveHandle){
       clearTimeout(saveHandle);
       saveHandle = null;
    }
    
    saveHandle = setTimeout(function(){
       saveHandle = null;
       
       if(save_in_progress){
           setTimeout(function(){
            pelm_call_save();
           },3000);
           return;
       }
       pelm_do_save();
    },3000);
}

function pelm_undo(){
    DG.undo();
}

function pelm_redo(){
    DG.redo();
}

var strip_helper = document.createElement("DIV");
function pelm_strip(html){
   strip_helper.innerHTML = html;
   return strip_helper.textContent || strip_helper.innerText || "";
}

jQuery(document).ready(function(){

    var CustomSelectEditor = Handsontable.editors.BaseEditor.prototype.extend();
    CustomSelectEditor.prototype.init = function(){
       // Create detached node, add CSS class and make sure its not visible
       this.select = jQuery('<select multiple="1" ></select>')
         .addClass('htCustomSelectEditor')
         .hide();
         
       // Attach node to DOM, by appending it to the container holding the table
       jQuery(this.instance.rootElement).append(this.select);
    };
    
    // Create options in prepare() method
    CustomSelectEditor.prototype.prepare = function(){
       
        //Remember to invoke parent's method
        Handsontable.editors.BaseEditor.prototype.prepare.apply(this, arguments);
        
        var options = this.cellProperties.selectOptions || [];

        var optionElements = options.map(function(option){
            var optionElement = jQuery('<option />');
            if(typeof option === typeof {}){
              optionElement.val(option.value);
              optionElement.html(option.name);
            }else{
              optionElement.val(option);
              optionElement.html(option);
            }

            return optionElement
        });

        this.select.empty();
        this.select.append(optionElements);
        
        
        var widg = this.select.next();
        var self = this;
        
        var create = false;
        
        var multiple = this.cellProperties.select_multiple;
        if(typeof multiple === "function"){
            multiple = !!multiple(this.instance,this.row, this.prop);
        }else if(!multiple)
            multiple = false;
        
        var create_option = this.cellProperties.allow_random_input;
        if(typeof create_option === "function"){
            create_option = !!create_option(this.instance,this.row, this.prop);
        }else if(!create_option)
            create_option = false;
        
        if(widg.is('.chosen-container')){
            if(
                !!this.select.data('chosen').is_multiple != multiple
                ||
                !!this.select.data('chosen').create_option != create_option
               ){
                    this.select.chosen('destroy');    
                    create = true;
                }
        }else
            create = true;
        
        if(create){
            if(!multiple){
               this.select.removeAttr('multiple');
               this.select.change(function(){
                    self.finishEditing()
                    jQuery('#dg_wpsc').handsontable("selectCell", self.row , self.col);                    
               });
            }else if(!this.select.attr("multiple")){
                this.select.attr('multiple','multiple');
            }
            var chos;
            if(create_option)
                chos = this.select.chosen({
                    create_option: true,
                    create_option_text: 'value',
                    persistent_create_option: true,
                    skip_no_results: true
                }).data('chosen');
            else
                chos = this.select.chosen().data('chosen');

            chos.container.bind('keyup', function (event) {
               if(event.keyCode == 27){
                    self.cancelUpdate = true;
                    self.discardEditor();
                    self.finishEditing();
                    
               }else if(event.keyCode == 13){
                  var src_inp = jQuery(this).find('LI.search-field > INPUT[type="text"]:first');
                  if(src_inp[0])
                    if(src_inp.val() == ''){
                       //event.stopImmediatePropagation();
                       //event.preventDefault();
                       self.discardEditor();
                       self.finishEditing();
                       //self.focus();
                       //self.close();
                       jQuery('#dg_wpsc').handsontable("selectCell", self.row + 1, self.col);
                    }
               }
            });
        }
    };
    
    
    CustomSelectEditor.prototype.getValue = function () {
       return this.select.val() || [];
    };

    CustomSelectEditor.prototype.setValue = function (value) {
       if(!(value instanceof Array))
        value = value.split(',');
       this.select.val(value);
       this.select.trigger("chosen:updated");
    };
    
    CustomSelectEditor.prototype.open = function () {
        //sets <select> dimensions to match cell size
        
        this.cancelUpdate = false;
        
        var widg = this.select.next();
        widg.css({
           height: jQuery(this.TD).height(),
           'min-width' : jQuery(this.TD).outerWidth() > 250 ? jQuery(this.TD).outerWidth() : 250
        });
        
        widg.find('LI.search-field > INPUT').css({
           'min-width' : jQuery(this.TD).outerWidth() > 250 ? jQuery(this.TD).outerWidth() : 250
        });

        //display the list
        widg.show();

        //make sure that list positions matches cell position
        widg.offset(jQuery(this.TD).offset());
    };
    
    CustomSelectEditor.prototype.focus = function () {
         this.instance.listen();
    };

    CustomSelectEditor.prototype.close = function () {
         if(!this.cancelUpdate)
            this.instance.setDataAtCell(this.row,this.col,this.select.val(),'edit')
         
         this.select.next().hide();
    };
    
    var clonableARROW = document.createElement('DIV');
    clonableARROW.className = 'htAutocompleteArrow';
    clonableARROW.appendChild(document.createTextNode('\u25BC'));
    
    var clonableEDIT = document.createElement('DIV');
    clonableEDIT.className = 'htAutocompleteArrow';
    clonableEDIT.appendChild(document.createTextNode('\u270E'));
    
    var clonableIMAGE = document.createElement('DIV');
    clonableIMAGE.className = 'htAutocompleteArrow';
    clonableIMAGE.appendChild(document.createTextNode('\u27A8'));
        
    var CustomSelectRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        try{
          
           // var WRAPPER = clonableWRAPPER.cloneNode(true); //this is faster than createElement
            var ARROW = clonableARROW.cloneNode(true); //this is faster than createElement

            Handsontable.renderers.TextRenderer(instance, td, row, col, prop, value, cellProperties);
            
            var fc = td.firstChild;
            while(fc) {
                td.removeChild( fc );
                fc = td.firstChild;
            }
            
            td.appendChild(ARROW); 
            
            if(value){
                
                if(cellProperties.select_multiple){ 
                    var rval = value;
                    if(!(rval instanceof Array))
                        rval = rval.split(',');
                    
                    td.appendChild(document.createTextNode(rval.map(function(s){ 
                            if(cellProperties.dictionary[s])
                                return cellProperties.dictionary[s];
                            else
                                return s;
                        }).join(', ')
                    ));
                }else{
                    td.appendChild(document.createTextNode(cellProperties.dictionary[value] || value));
                }
                
            }else{
                //jQuery(td).html('');
            }
            
            Handsontable.Dom.addClass(td, 'htAutocomplete');

            if (!td.firstChild) {
              td.appendChild(document.createTextNode('\u00A0')); //\u00A0 equals &nbsp; for a text node
            }

            if (!instance.acArrowListener) {
              instance.acArrowHookedToDouble = true;    
              var eventManager = Handsontable.eventManager(instance);

              //not very elegant but easy and fast
              instance.acArrowListener = function (event) {
                if (Handsontable.Dom.hasClass(event.target,'htAutocompleteArrow')) {
                  instance.view.wt.getSetting('onCellDblClick', null, new WalkontableCellCoords(row, col), td);
                }
              };

              jQuery(instance.rootElement).on("mousedown.htAutocompleteArrow",".htAutocompleteArrow",instance.acArrowListener);

              //We need to unbind the listener after the table has been destroyed
              instance.addHookOnce('afterDestroy', function () {
                eventManager.clear();
              });

            }else if(!instance.acArrowHookedToDouble){
              instance.acArrowHookedToDouble = true;    
              var eventManager = Handsontable.eventManager(instance);    
              jQuery(instance.rootElement).on("mousedown.htAutocompleteArrow",".htAutocompleteArrow",instance.acArrowListener);
              //We need to unbind the listener after the table has been destroyed
              instance.addHookOnce('afterDestroy', function () {
                eventManager.clear();
              });    
                
            }
        }catch(e){
            jQuery(td).html('');
        }
    };
    ///////////////////////////////////////////////////////////////////////////////////////
    jQuery('#content-editor #cmdContentSave').click(function(){
       DG.setDataAtRowProp( ContentEditorCurrentlyEditing.row, 
                            ContentEditorCurrentlyEditing.prop, 
                            jQuery('#content-editor textarea.wp-editor-area:visible')[0] ? (jQuery('#content-editor textarea.wp-editor-area:visible').val() || '') : (jQuery('#content-editor #editor_ifr').contents().find('BODY').html() || ''),
                            ''
                          );
                            
       jQuery('#content-editor').css('top','110%');
    });
    
    jQuery('#content-editor #cmdContentCancel').click(function(){
       jQuery('#content-editor').css('top','110%');
    });
    
    let customContentEditor = Handsontable.editors.BaseEditor.prototype.extend();
    customContentEditor.prototype.open = function () {
        ContentEditorCurrentlyEditing.row  = this.row; 
        ContentEditorCurrentlyEditing.col  = this.col; 
        ContentEditorCurrentlyEditing.prop = this.prop; 
        jQuery('#content-editor').css('top','0%');
        
        DG.selectCell(ContentEditorCurrentlyEditing.row,ContentEditorCurrentlyEditing.col);
    };
    
    customContentEditor.prototype.getValue = function () {
       if(jQuery('#content-editor textarea.wp-editor-area:visible')[0])
          return jQuery('#content-editor textarea.wp-editor-area:visible').val() || '';
       else
          return jQuery('#content-editor #editor_ifr').contents().find('BODY').html() || '';       
    };

    customContentEditor.prototype.setValue = function (value) {
        jQuery('#content-editor textarea.wp-editor-area').val(value || "");
        jQuery('#content-editor #editor_ifr').contents().find('BODY').html(value || "");
        this.finishEditing();
    };
    
    customContentEditor.prototype.focus = function () { this.instance.listen();};
    customContentEditor.prototype.close = function () {};
    ///////////////////////////////////////////////////////////////////////////////////////////
    
    var customImageEditor = Handsontable.editors.BaseEditor.prototype.extend();
    customImageEditor.prototype.open = function () {
        ImageEditorCurrentlyEditing.row   = this.row; 
        ImageEditorCurrentlyEditing.col   = this.col; 
        ImageEditorCurrentlyEditing.prop  = this.prop; 
        ImageEditorCurrentlyEditing.value = this.originalValue;
        var SELF = this;
        
        if(this.instance.getSettings().columns[this.col].select_multiple){
            if(!galleryPicker){
                galleryPicker  = wp.media({
                    title: 'Product Images (#' + DG.getDataAtRowProp(this.row,'sku') + ' ' + DG.getDataAtRowProp(this.row,'name') + ')',
                    multiple: true,
                    library: {
                        type: 'image'
                    },
                    button: {
                        text: 'Set product images'
                    }
                });
                
                galleryPicker.on( 'select', function() {
                    var selection = galleryPicker.state().get('selection');
                    
                    var gval = new Array();
                    
                    selection.each(function(attachment) {
                        
                        var val = {};
                        val.id    = attachment.attributes.id;
                        val.src   = attachment.attributes.url;
                        val.thumb = attachment.attributes.sizes.thumbnail.url;
                        
                        gval.push(val);
                        
                    });
                    
                    DG.setDataAtRowProp(ImageEditorCurrentlyEditing.row, ImageEditorCurrentlyEditing.prop, gval, "" );
                    DG.selectCell(ImageEditorCurrentlyEditing.row,ImageEditorCurrentlyEditing.col);
                });
                
                galleryPicker.on('open',function() {
                    var selection = galleryPicker.state().get('selection');

                    //remove all the selection first
                    selection.each(function(image) {
                        var attachment = wp.media.attachment( image.attributes.id );
                        attachment.fetch();
                        selection.remove( attachment ? [ attachment ] : [] );
                    });

                    if(galleryPicker.current_value){
                        for(var i = 0; i < galleryPicker.current_value.length; i++){
                            if(galleryPicker.current_value[i].id){
                                var att = wp.media.attachment( galleryPicker.current_value[i].id );
                                att.fetch();
                                selection.add( att ? [ att ] : [] );
                            }
                        }
                    }
                });
                
                galleryPicker.on('close',function() {
                    DG.selectCell(ImageEditorCurrentlyEditing.row,ImageEditorCurrentlyEditing.col);
                });
                
                
            }else{
                
                var newTitle = jQuery("<h1>" + 'Product Images (#' + DG.getDataAtRowProp(this.row,'sku') + ' ' + DG.getDataAtRowProp(this.row,'name') + ')' + "</h1>");
                jQuery(galleryPicker.el).find('.media-frame-title h1 *').appendTo(newTitle);
                jQuery(galleryPicker.el).find(".media-frame-title > *").remove();
                jQuery(galleryPicker.el).find(".media-frame-title").append(newTitle);
                
            }
            galleryPicker.current_value = this.originalValue;
            galleryPicker.open();
            
        }else{
        
            if(!imagePicker){
                
                imagePicker = wp.media({
                    title: 'Featured image(#' + DG.getDataAtRowProp(this.row,'sku') + ' ' + DG.getDataAtRowProp(this.row,'name') + ')',
                    multiple: false,
                    library: {
                        type: 'image'
                    },
                    button: {
                        text: 'Set as featured image'
                    }
                });
                
                imagePicker.on( 'select', function() {
                    var selection = imagePicker.state().get('selection');
                    selection.each(function(attachment) {
                        //console.log(attachment);
                        
                        var val = ImageEditorCurrentlyEditing.value;
                        if(!val) val = {};
                        val.id    = attachment.attributes.id;
                        val.src   = attachment.attributes.url;
                        val.thumb = attachment.attributes.sizes.thumbnail.url;
                        DG.setDataAtRowProp(ImageEditorCurrentlyEditing.row, ImageEditorCurrentlyEditing.prop, val, "" );
                        DG.selectCell(ImageEditorCurrentlyEditing.row,ImageEditorCurrentlyEditing.col);
                    });
                });
                
                imagePicker.on('open',function() {
                    var selection = imagePicker.state().get('selection');

                    //remove all the selection first
                    selection.each(function(image) {
                        var attachment = wp.media.attachment( image.attributes.id );
                        attachment.fetch();
                        selection.remove( attachment ? [ attachment ] : [] );
                    });

                    if(imagePicker.current_value){
                        if(imagePicker.current_value.id){
                            var att = wp.media.attachment( imagePicker.current_value.id );
                            att.fetch();
                            selection.add( att ? [ att ] : [] );
                        }
                    }
                });
                
                imagePicker.on('close',function() {
                    DG.selectCell(ImageEditorCurrentlyEditing.row,ImageEditorCurrentlyEditing.col);
                });
                
            }else{
                var newTitle = jQuery("<h1>" + 'Featured image(#' + DG.getDataAtRowProp(this.row,'sku') + ' ' + DG.getDataAtRowProp(this.row,'name') + ')' + "</h1>");
                jQuery(imagePicker.el).find('.media-frame-title h1 *').appendTo(newTitle);
                jQuery(imagePicker.el).find(".media-frame-title > *").remove();
                jQuery(imagePicker.el).find(".media-frame-title").append(newTitle);
            }
            
            imagePicker.current_value = this.originalValue;
            imagePicker.open();
        }
    };
    
    customImageEditor.prototype.getValue = function () {
        return ImageEditorCurrentlyEditing.value;
    };
    
    customImageEditor.prototype.setValue = function ( value ) {
        ImageEditorCurrentlyEditing.value = value instanceof Object || value instanceof Array ? value : this.originalValue; 
        this.finishEditing(); 
    };
    
    customImageEditor.prototype.focus = function () { this.instance.listen();};
    customImageEditor.prototype.close = function () {};
    
    /////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    
    var pelm_customContentRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        try{
            
            arguments[5] = pelm_strip(value); 
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            Handsontable.Dom.addClass(td, 'htContent');
            td.insertBefore(clonableEDIT.cloneNode(true), td.firstChild);
            if (!td.firstChild) { //http://jsperf.com/empty-node-if-needed
              td.appendChild(document.createTextNode('\u00A0')); //\u00A0 equals &nbsp; for a text node
            }

            if (!instance.acArrowListener) {
              instance.acArrowHookedToDouble = true;    
              var eventManager = Handsontable.eventManager(instance);

              //not very elegant but easy and fast
              instance.acArrowListener = function (event) {
                if (Handsontable.Dom.hasClass(event.target,'htAutocompleteArrow')) {
                  instance.view.wt.getSetting('onCellDblClick', null, new WalkontableCellCoords(row, col), td);
                }
              };

              jQuery(instance.rootElement).on("mousedown.htAutocompleteArrow",".htAutocompleteArrow",instance.acArrowListener);

              //We need to unbind the listener after the table has been destroyed
              instance.addHookOnce('afterDestroy', function () {
                eventManager.clear();
              });

            }else if(!instance.acArrowHookedToDouble){
              instance.acArrowHookedToDouble = true;    
              var eventManager = Handsontable.eventManager(instance);    
              jQuery(instance.rootElement).on("mousedown.htAutocompleteArrow",".htAutocompleteArrow",instance.acArrowListener);
              
              //We need to unbind the listener after the table has been destroyed
              instance.addHookOnce('afterDestroy', function () {
                eventManager.clear();
              });    
                
            }
        }catch(e){
            jQuery(td).html('');
        }
    };
    
    var customImageRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        try{
            
            if(DG.getDataAtRowProp(row,'id')){
                if(!value)
                    value = DG.getDataAtRowProp(row,prop);
                
                if(!upload_dir_data.npbaseurl){
                    upload_dir_data.npbaseurl = upload_dir_data.npbaseurl.replace("http://","")
                                                                         .replace("https://","")
                                                                         .replace("www.","");
                }
                
                if(value){
                    if(value instanceof Array){
                            value = value.map(function(v){
                                try{
                                    v = v.src.split(upload_dir_data.npbaseurl);
                                    v = v[v.length -1];
                                }catch(espl){
                                    return "";
                                }
                                return v; 
                            });
                            value = value.join(",");
                    }else{
                        try{
                            value = value.src.split(upload_dir_data.npbaseurl);
                            value = value[value.length -1];
                        }catch(espl){}
                    }
                }
            }else
                value = null;
            
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            Handsontable.Dom.addClass(td, 'htImage');
            td.insertBefore(clonableIMAGE.cloneNode(true), td.firstChild);
            if (!td.firstChild) { //http://jsperf.com/empty-node-if-needed
              td.appendChild(document.createTextNode('\u00A0')); //\u00A0 equals &nbsp; for a text node
            }

            if (!instance.acArrowListener) {
              instance.acArrowHookedToDouble = true;    
              var eventManager = Handsontable.eventManager(instance);

              //not very elegant but easy and fast
              instance.acArrowListener = function (event) {
                if (Handsontable.Dom.hasClass(event.target,'htAutocompleteArrow')) {
                  instance.view.wt.getSetting('onCellDblClick', null, new WalkontableCellCoords(row, col), td);
                }
              };

              jQuery(instance.rootElement).on("mousedown.htAutocompleteArrow",".htAutocompleteArrow",instance.acArrowListener);

              //We need to unbind the listener after the table has been destroyed
              instance.addHookOnce('afterDestroy', function () {
                eventManager.clear();
              });

            }else if(!instance.acArrowHookedToDouble){
              instance.acArrowHookedToDouble = true;    
              var eventManager = Handsontable.eventManager(instance);    
              jQuery(instance.rootElement).on("mousedown.htAutocompleteArrow",".htAutocompleteArrow",instance.acArrowListener);
              
              //We need to unbind the listener after the table has been destroyed
              instance.addHookOnce('afterDestroy', function () {
                eventManager.clear();
              });    
                
            }
            
        }catch(e){
            jQuery(td).html('');
        }
    };


    
    
    var unitEditor = Handsontable.editors.TextEditor.prototype.extend();
    unitEditor.prototype.getValue = function () {
        if(!this.INPUT)
            this.INPUT = jQuery(this.TEXTAREA); 
            
        if(!this.INPUT.val())
            return '';
        else
            var value = this.INPUT.val().replace(' ',''); 
            if(String(parseFloat(value)) == value)
                return this.INPUT.val() + ' ' + this.INPUT.attr("unit");
            else{
                var val   = parseFloat(value);
                
                var unit  = value.replace(val,'').replace(' ','');
                var units = [];
                if(typeof this.cellProperties.unit == typeof {} || this.cellProperties.unit.indexOf('.') >= 0 || this.cellProperties.unit.indexOf('#') >= 0 )
                  units    = jQuery(this.cellProperties.unit + ', ' + this.cellProperties.unit + ' *').toArray().map(function(o){
                     var o = jQuery(o);
                     if(!o.attr('value'))
                       return null;
                     return o.attr('value');
                  });
                else
                  units[0] = this.cellProperties.unit;
                
                var nunit = '';                
                for(var ind in units){
                  if(units[ind])
                      if(unit.toLowerCase() == units[ind].toLowerCase()){
                        nunit = units[ind];
                        break;
                      }
                }
            
                if(!nunit)
                    nunit = this.INPUT.attr("unit");
                
                return val + ' ' + nunit;
            }
                            
    };
    
    unitEditor.prototype.setValue = function (value) {
        if(!this.INPUT)
            this.INPUT = jQuery(this.TEXTAREA);
            
        this.INPUT.val('');//clean;
        
        var val  = '';
        var unit = '';
        
        if(!value || String(parseFloat(value)) == value){
            val   = parseFloat(value);
            if(typeof this.cellProperties.unit == typeof {} || this.cellProperties.unit.indexOf('.') >= 0 || this.cellProperties.unit.indexOf('#') >= 0 )
              unit  = jQuery(this.cellProperties.unit).val();
            else
              unit  = this.cellProperties.unit;
        }else{
            val   = parseFloat(value); 
            unit  = value.replace(val,'').replace(' ','');
        }
        
        this.INPUT.attr("unit",unit);
        if(!isNaN(val))
            this.INPUT.val(val);
    };
    
    var pelm_centerCheckboxRenderer = function (instance, td, row, col, prop, value, cellProperties) {
      Handsontable.renderers.CheckboxRenderer.apply(this, arguments);
      jQuery(td).css({
        'text-align': 'center',
        'vertical-align': 'middle'
      });
    };

    var pelm_centerTextRenderer = function (instance, td, row, col, prop, value, cellProperties) {
      Handsontable.renderers.TextRenderer.apply(this, arguments);
      jQuery(td).css({
        'text-align': 'center',
        'vertical-align': 'middle'
      });
    };
    
    var postStatuses = <?php echo json_encode(array_keys($post_statuses)); ?>;
    
    function pelm_array_to_dictionary(arr){
        var dict = {};
        for(var i = 0; i< arr.length; i++){
            dict[(arr[i] + "")] = arr[i];
        }
        return dict;    
    }
    
    var cw = [40,60,160,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80];
    if(localStorage['dg_wpsc_manualColumnWidths']){
        var LS_W = JSON.parse(localStorage['dg_wpsc_manualColumnWidths']);
        for(var i = 0; i< LS_W.length; i++){
            if(LS_W[i])
                cw[i] = LS_W[i] || 80;
        }
    }
    
    sortedBy  = null;
    sortedOrd = null;
    
    jQuery('#dg_wpsc').handsontable({
      data: [<?php pelm_product_render($IDS, "json");?>],
      minSpareRows: <?php if(isset($plem_price_settings['enable_add'])) { if($plem_price_settings['enable_add']) { echo "1"; 
} else { echo "0";
                    } 
} else { echo "0";
                    }  ?>,
      colHeaders: true,
      rowHeaders: true,
      contextMenu: false,
      manualColumnResize: true,
      manualColumnMove: true,
      columnSorting: true,
      persistentState: true,
      variableRowHeights: false,
      search:true,
      fillHandle: 'vertical',
      currentRowClassName: 'currentRow',
      currentColClassName: 'currentCol',
      fixedColumnsLeft: <?php echo esc_attr($plem_price_settings['fixedColumns']); ?>,
      //stretchH: 'all',
      colWidths:cw,
      width: function () {
        if (availableWidth === void 0) {
          calculateSize();
        }
        return availableWidth ;
      },
      height: function () {
        if (availableHeight === void 0) {
          calculateSize();
        }
        return availableHeight;
      }
      ,colHeaders:[
        "ID"
        <?php if(pelm_fn_show_filed('sku')) { echo  ',"'. esc_html__("SKU", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('name')) { echo  ',"'.__("Product Name", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('slug')) { echo  ',"'. esc_html__("Slug", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('categories')) { echo  ',"'. esc_html__("Category", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('stock')) { echo  ',"'. esc_html__("Stock", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('price')) { echo  ',"'. esc_html__("Price", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('override_price')) { echo  ',"'. esc_html__("Sales price", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('tags')) { echo  ',"'. esc_html__("Tags", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('status')) { echo  ',"'. esc_html__("Status", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('weight')) { echo  ',"'. esc_html__("Weight", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('height')) { echo  ',"'. esc_html__("Height", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('width')) { echo  ',"'. esc_html__("Width", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('length')) { echo  ',"'. esc_html__("Length", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('image')) { echo ',"'.__("Image", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('taxable')) { echo  ',"'. esc_html__("Taxable", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('loc_shipping')) { echo  ',"'. esc_html__("Local ship.", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('int_shipping')) { echo  ',"'. esc_html__("Int. ship.", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php
        foreach($custom_fileds as $cfname => $cfield){ 
            echo ',"'.addslashes(__(esc_attr($cfield->title), 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light')).'"';
        }
        ?>        
      ],
      columns: [
       { data: "id", readOnly: true, type: 'numeric' }
      <?php if(pelm_fn_show_filed('sku')) { ?>,{ data: "sku" }<?php 
      } ?>
      <?php if(pelm_fn_show_filed('name')) { ?>,{ data: "name"  }<?php 
      } ?>
      <?php if(pelm_fn_show_filed('slug')) { ?>,{ data: "slug", type: 'text'  }<?php 
      } ?>
      <?php if(pelm_fn_show_filed('categories')) { ?>,{
        data: "categories",
        editor: CustomSelectEditor.prototype.extend(),
        renderer: CustomSelectRenderer,
        select_multiple: true,
        dictionary: asoc_cats,
        selectOptions: (!categories) ? [] : categories.map(function(source){
                           return {
                             "name": source.category_name , 
                             "value": source.category_id
                           }
                        })
       }<?php } ?>
      <?php if(pelm_fn_show_filed('stock')) { ?>,{ data: "stock" ,type: 'numeric',format: '0', renderer: pelm_centerTextRenderer }<?php 
      } ?>
      <?php if(pelm_fn_show_filed('price')) { ?>,{ data: "price"  ,type: 'numeric',format: '0<?php echo esc_attr(substr($_num_sample, 1, 1));?>00'}<?php 
      } ?>
      <?php if(pelm_fn_show_filed('override_price')) { ?>,{ data: "override_price"  ,type: 'numeric',format: '0<?php echo esc_attr(substr($_num_sample, 1, 1));?>00'}<?php 
      } ?>       
      <?php if(pelm_fn_show_filed('tags')) { ?>,{
        data: "tags",
        editor: CustomSelectEditor.prototype.extend(),
        renderer: CustomSelectRenderer,
        select_multiple: true,
        dictionary: asoc_tags,
        selectOptions: (!tags) ? [] : tags.map(function(source){
                           return {
                             "name": source.name , 
                             "value": source.id
                           }
                        })
       }<?php } ?>
      <?php if(pelm_fn_show_filed('status')) { ?>,{ 
         data: "status",
         editor: CustomSelectEditor.prototype.extend(),
         renderer: CustomSelectRenderer,
         select_multiple: false,
         dictionary: pelm_array_to_dictionary(postStatuses),
         selectOptions:postStatuses
       }<?php } ?>
           <?php if(pelm_fn_show_filed('weight')) { ?>,{ data: "weight", editor: unitEditor, unit: '#weight_unit' }<?php 
           } ?>
      <?php if(pelm_fn_show_filed('height')) { ?>,{ data: "height", editor: unitEditor, unit: '#dimensions_unit' }<?php 
      } ?>
      <?php if(pelm_fn_show_filed('width')) { ?>,{ data: "width", editor: unitEditor, unit: '#dimensions_unit' }<?php 
      } ?>
      <?php if(pelm_fn_show_filed('length')) { ?>,{ data: "length", editor: unitEditor, unit: '#dimensions_unit' }<?php 
      } ?>
      
      <?php if(pelm_fn_show_filed('image')) { ?>,{ 
        data: "image", 
        editor: customImageEditor.prototype.extend(),
        renderer: customImageRenderer
}<?php } ?>

      <?php if(pelm_fn_show_filed('taxable')) { ?>,{ data: "taxable", type: 'numeric',format: '0<?php echo esc_attr(substr($_num_sample, 1, 1));?>00' }<?php 
      } ?>
      <?php if(pelm_fn_show_filed('loc_shipping')) { ?>,{ data: "loc_shipping", type: 'numeric',format: '0<?php echo esc_attr(substr($_num_sample, 1, 1));?>00' }<?php 
      } ?>
      <?php if(pelm_fn_show_filed('int_shipping')) { ?>,{ data: "int_shipping", type: 'numeric',format: '0<?php echo esc_attr(substr($_num_sample, 1, 1));?>00' }<?php 
      } ?>
      
  <?php foreach($custom_fileds as $cfname => $cfield){ 
        if($cfield->type == "term") {?>
            ,{ 
               data: "<?php echo esc_attr($cfield->name);?>",
               editor: CustomSelectEditor.prototype.extend(),
               renderer: CustomSelectRenderer,
               select_multiple: <?php echo esc_attr($cfield->options->multiple ? "true" : "false"); ?>,
               allow_random_input: <?php echo esc_attr($cfield->options->allownew ? "true" : "false"); ?>,
               selectOptions: <?php echo json_encode($cfield->terms);?>,
                   dictionary: <?php
                      $asoc_trm = new stdClass();
                    foreach($cfield->terms as $t){
                        $asoc_trm->{$t->value} = $t->name;
                    } 
                      echo json_encode($asoc_trm);
                    ?>
             }
        <?php }else{ ?>
            ,{ 
               data: "<?php echo esc_attr($cfield->name);?>"
            <?php
            if($cfield->options->formater == "content") {?>
                , editor: customContentEditor.prototype.extend()
                , renderer: pelm_customContentRenderer
                <?php
            }elseif($cfield->options->formater == "checkbox") {
                echo ',type: "checkbox"'; 
                echo ',renderer: pelm_centerCheckboxRenderer';
                if($cfield->options->checked_value || $cfield->options->checked_value === "0") { echo ',checkedTemplate: "'. esc_attr($cfield->options->checked_value).'"';
                } 
                if($cfield->options->unchecked_value || $cfield->options->unchecked_value === "0") { echo ',uncheckedTemplate: "'.esc_attr($cfield->options->unchecked_value).'"';
                }
            }elseif($cfield->options->formater == "dropdown") {
                echo ',type: "autocomplete", strict: ' . esc_attr($cfield->options->strict ? "true" : "false");
                echo ',source:' ;
                $vals = str_replace(", ", ",", $cfield->options->values);
                $vals = str_replace(", ", ",", $vals);
                $vals = str_replace(" ,", ",", $vals);
                $vals = str_replace(", ", ",", $vals);
                $vals = str_replace(" ,", ",", $vals);
                $vals = explode(",", $vals);
                echo json_encode($vals);
            }elseif($cfield->options->formater == "date") {
                echo ',type: "date"';
                echo ',dateFormat: "'.esc_attr($cfield->options->format).'"';
                echo ',correctFormat: true';
                echo ',defaultDate: "'.esc_attr($cfield->options->default).'"';
            }else{
                if($cfield->options->format == "integer") { echo  ',type: "numeric"';
                } elseif($cfield->options->format == "decimal") { echo  ',type: "numeric", format: "0'. esc_attr(substr($_num_sample, 1, 1)).'00"';
                }
            }
            ?>                   
             }
        <?php }
  } ?>

      
      ]
      ,outsideClickDeselects: false
      <?php

        if(isset($plem_price_settings['enable_delete'])) { 
            if($plem_price_settings['enable_delete']) {
                ?>
        
        ,removeRowPlugin: true
        ,beforeRemoveRow: function (index, amount){
             if(!DG.getDataAtRowProp(index,"id"))
                 return false;
             if(confirm("<?php echo esc_html__("Remove product", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?> <?php echo esc_html__("SKU", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?>:" + DG.getDataAtRowProp(index,"sku") + ", <?php echo esc_html__("Name", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?>: '" + DG.getDataAtRowProp(index,"name") + "', ID:" +  DG.getDataAtRowProp(index,"id") + "?")){
                
                var id = DG.getDataAtRowProp(index,"id");
                
                if(!tasks[id])
                    tasks[id] = {};
                
                tasks[id]["DO_DELETE"] = 'delete';
                
                pelm_call_save();
                
                return true;         
             }else
                return false;
        
        }
                <?php
            } 
        }

        ?>
      ,afterChange: function (change, source) {
        if(!change)   
            return;
        if(!DG)
            DG = jQuery('#dg_wpsc').data('handsontable');
        
        if (source === 'loadData') return;
        if (source === 'skip') return;
        if(!change[0])
            return;
            
        if(!jQuery.isArray(change[0]))
            change = [change];
        
        
        change.map(function(data){
            if(!data)
              return;
          
            if ([data[2]].join("") == [data[3]].join(""))
                return;
            
            var id = DG.getDataAtRowProp (data[0],'id');
            if(!id){
                if(!data[3])
                    return;
                var surogat = "s" + parseInt( Math.random() * 10000000); 
                DG.getSourceDataAtRow(data[0])['id'] = surogat;
                id = surogat;
                SUROGATES[surogat] = data[0];
            }
            
            var prop = data[1];
            var val  = data[3];
            if(!tasks[id])
                tasks[id] = {};
            tasks[id][prop] = val;
            tasks[id]["dg_index"] = data[0];
        });
        
        pelm_call_save();
      }
      ,afterColumnResize: function(currentCol, newSize){
        
      }
      ,beforeColumnSort: function (column, order){
          
          if(window.explicitSort)
              return;
          
          if(DG){
            if(DG.getSelected()){
                DG.sortColumn = DG.getSelected()[1];
                
                if(sortedBy == DG.sortColumn)
                    DG.sortOrder = !sortedOrd;
                else
                    DG.sortOrder = true;
                
                sortedBy  = DG.sortColumn;
                sortedOrd = DG.sortOrder;
                
            }
          }
        
      }
      ,cells: function (row, col, prop) {
        if(!DG)
            DG = jQuery('#dg_wpsc').data('handsontable');
            
        if(!DG)
            return;
        
        this.readOnly = false;
            
        var row_data = DG.getData()[row]; 
        if(!row_data)
            return;
        
        if(prop == "id"){
            this.readOnly = true;
            return;
        }
        
        
        try{    
            if(row_data.parent){
                if(jQuery.inArray(prop, variations_skip) >= 0){
                    this.readOnly = true;
                }
            }
        }catch(ex){}
        
        if(!(prop == 'price' || prop == 'override_price' || prop == 'gallery' || prop == 'image' ))
            this.readOnly = true;
        
      },afterSelection:function(r, c, r_end, c_end){
            var img = DG.getDataAtRowProp(r,'image');
            if(img){
                if(img.src){
                    ProductPreviewBox.css("background-image","url(" + img.src + ")");    
                }else
                    ProductPreviewBox.css("background-image","");                    
            }else
                ProductPreviewBox.css("background-image","");    

            ProductPreviewBox.attr('row', r);
            
            ProductPreviewBox_title.text("#" + (DG.getDataAtRowProp(r,'sku') || "") + "" + (DG.getDataAtRowProp(r,'name') || ""));    
     }


      
    });
    
    jQuery(document).on("click","#product-preview",function(e){
        try{
            if(DG.propToCol("image") > -1 && jQuery(this).attr("row")){
                DG.selectCell(parseInt(jQuery(this).attr("row")), DG.propToCol("image"));
                DG.getActiveEditor().beginEditing();
            }
        }catch(e){
        //    
        }
    });

    
    if(!DG)
        DG = jQuery('#dg_wpsc').data('handsontable');
    
    sortedBy  = DG.sortColumn;
    sortedOrd = DG.sortOrder;
    

    
    jQuery('.filters_label').click(function(){
        if( jQuery(this).parent().is('.opened')){
            jQuery(this).parent().removeClass('opened').addClass('closed');
        }else{
            jQuery(this).parent().removeClass('closed').addClass('opened');
        }
        jQuery(window).trigger('resize');
    });
    
    jQuery(window).load(function(){
        jQuery(window).trigger('resize');
    });
    
    
    if('<?php echo esc_attr($product_category);?>') jQuery('.filter_option *[name="product_category"]').val("<?php if($product_category) { echo esc_attr(implode(",", $product_category));
}?>".split(','));
    if('<?php echo esc_attr($product_tag);?>') jQuery('.filter_option *[name="product_tag"]').val("<?php if($product_tag) { echo esc_attr(implode(",", $product_tag));
}?>".split(','));
    if('<?php echo esc_attr($product_status);?>') jQuery('.filter_option *[name="product_status"]').val("<?php if($product_status) { echo esc_attr(implode(",", $product_status));
}?>".split(','));
    
    jQuery('SELECT[name="product_category"]').chosen();
    jQuery('SELECT[name="product_tag"]').chosen();
    jQuery('SELECT[name="product_status"]').chosen();
    
    jQuery("<div class='grid-bottom-spacer' style='min-height:120px;'></div>").insertAfter( jQuery("table.htCore"));        
    
    
    function pelm_screen_search(select){
        if(DG){
            var self = document.getElementById('activeFind');
            var queryResult = DG.search.query(self.value);
            if(select){
                if(!queryResult.length){
                    jumpIndex = 0;
                    return;
                }
                if(jumpIndex > queryResult.length - 1)
                    jumpIndex = 0;
                DG.selectCell(queryResult[jumpIndex].row,queryResult[jumpIndex].col,queryResult[jumpIndex].row,queryResult[jumpIndex].col,true);
                jQuery("#search_matches").html(("" + (jumpIndex + 1) + "/" + queryResult.length) || "");
                jumpIndex ++;
            }else{
                jQuery("#search_matches").html(queryResult.length || "");
                DG.render();
                jumpIndex = 0;
            }
        }
    }
    
    Handsontable.Dom.addEvent(document.getElementById('activeFind') , 'keyup', function (event) {
        if(event.keyCode == 13){
            pelm_screen_search(true);
        }else{
            pelm_screen_search(false);
        }
    });
    
    jQuery("#cmdActiveFind").click(function(){
        pelm_screen_search(true);
    });
    
    
});



  <?php
    if($mu_res) {
        $upd_val = pelm_read_sanitized_request_parm("mass_update_val").(  pelm_read_sanitized_request_parm("mass_update_percentage") ? "%" : "" );
        ?>
       jQuery(window).load(function(){
       alert('<?php echo esc_attr(sprintf(__("Proiduct price for all products matched by filter criteria is changed by %s", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'), $upd_val)); ?>');
       });
        <?php
    }
    
    if($import_count) {
        ?>
       jQuery(window).load(function(){
       alert('<?php echo esc_attr(sprintf(__("%s products updated prices form imported file!", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'), $import_count)); ?>');
       });
        <?php
    }
    
    ?>


function pelm_do_export(){
    var link = pelm_get_request_url() + "&do_export=1" ;
   
    var QUERY_DATA = {};
    QUERY_DATA.sortOrder            = DG.sortOrder ? "ASC" : "DESC";
    QUERY_DATA.sortColumn           = getSortProperty();
    
    QUERY_DATA.limit                = "9999999999";
    QUERY_DATA.page_no              = "1";
    
    QUERY_DATA.sku                  = jQuery('.filter_option *[name="sku"]').val();
    QUERY_DATA.product_name         = jQuery('.filter_option *[name="product_name"]').val();
    QUERY_DATA.product_tag          = jQuery('.filter_option *[name="product_tag"]').val();
    QUERY_DATA.product_category     = jQuery('.filter_option *[name="product_category"]').val();
    QUERY_DATA.product_status       = jQuery('.filter_option *[name="product_status"]').val();
    
    for(var key in QUERY_DATA){
        if(QUERY_DATA[key])
            link += ("&" + key + "=" + QUERY_DATA[key]);
    }
    
    window.location =  link;
    return false;
}

function pelm_do_import(){
    var import_panel = jQuery("<div class='import_form'><form method='POST' enctype='multipart/form-data'>" +
    '<input name="pelm_security" type="hidden" value="<?php echo esc_attr(pelm_get_nonce("pelm_nonce")); ?>" />'
    +
    "<span><?php echo esc_html__("Select .CSV file to update prices/stock from.<br>(To void price, stock or any available field update remove coresponding column from CSV file)", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></span><br/><label for='file'><?php echo esc_html__("File:", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></label><input type='file' name='file' id='file' /><br/><br/><button class='cmdImport' ><?php echo esc_html__("Import", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></button><button class='cancelImport'><?php echo esc_html__("Cancel", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></button></form><br/><p>*If you edit from MS Excel you must save using 'Save As', for 'Sava As Type' choose 'CSV Comma Delimited (*.csv)'. Otherwise MS Excel fill save in incorrect format!</p></div>"); 
    import_panel.appendTo(jQuery("BODY"));
    
    import_panel.find('.cancelImport').click(function(){
        import_panel.remove();
        return false;
    });
    
    import_panel.find('.cmdImport').click(function(){
        if(!jQuery("#file").val()){
          alert('<?php echo esc_html__("Enter value first!", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?>');
          return false;
        }
        var frm = import_panel.find('FORM');
        var POST_DATA = {};
        
        POST_DATA.do_import            = "1";
        POST_DATA.sortOrder            = DG.sortOrder ? "ASC" : "DESC";
        POST_DATA.sortColumn           = getSortProperty();
        POST_DATA.limit                = jQuery('#txtlimit').val();
        POST_DATA.page_no               = jQuery('#paging_page').val();
        
        POST_DATA.sku                  = jQuery('.filter_option *[name="sku"]').val();
        POST_DATA.product_name         = jQuery('.filter_option *[name="product_name"]').val();
        POST_DATA.product_tag          = jQuery('.filter_option *[name="product_tag"]').val();
        POST_DATA.product_category     = jQuery('.filter_option *[name="product_category"]').val();
        POST_DATA.product_status       = jQuery('.filter_option *[name="product_status"]').val();
        
        for(var key in POST_DATA){
            if(POST_DATA[key])
                frm.append("<INPUT type='hidden' name='" + key + "' value='" + POST_DATA[key] + "' />");
        }
        frm.attr("action",pelm_get_request_url());        
        frm.submit();
        return false;
    });
}

</script>

<?php
    wp_enqueue_script('pelm_price_script', $excellikepricechangeforwoocommerceandwpecommercelight_baseurl. 'lib/script.js', array('jquery'));
    wp_print_scripts('pelm_price_script');
    
    $settp_path = realpath(dirname(__FILE__). DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . 'settings_panel.php');
    require $settp_path;
if($use_image_picker || $use_content_editior || pelm_fn_show_filed('image')) {
       wp_print_styles('media-views');
       wp_print_styles('imgareaselect');
       wp_print_media_templates();
}
?>
</div>
</div>
<?php
exit;
?>
