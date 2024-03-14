<?php
/*
 * Title: WooCommerce
 * Origin plugin: woocommerce/woocommerce.php,envato-wordpress-toolkit/woocommerce.php
*/
?>
<?php
if (!function_exists('add_action') ) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if(ini_get('max_execution_time') < 300) {
    set_time_limit(300); //5 min
}

$is_post_request = strtoupper(pelm_read_sanitized_server_parm('REQUEST_METHOD','')) === 'POST';

global $start_time, $max_time, $mem_limit, $res_limit_interupted, $resume_skip;
$resume_skip          = 0;  
$res_limit_interupted = 0;

$start_time     = time();
$max_time       = ini_get('max_execution_time') / 2;
if(!$max_time) {
    $max_time    = 30;
}

global $wpdb;
global $wooc_fields_visible, $variations_fields;
global $custom_fileds, $use_image_picker, $use_content_editior;

$use_image_picker    = false;
$use_content_editior = false;

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

if(pelm_read_sanitized_request_parm('keep_alive', null)) {
    return;
}

if(pelm_read_sanitized_request_parm("resume_skip")) {
    $resume_skip = intval(pelm_read_sanitized_request_parm("resume_skip"));
}

if(pelm_read_sanitized_request_parm("set_mem_limit")) {
    if(!current_user_can('administrator')) {
        die("<br><br>You are not allowed to execute this operation!");
    }
    
    if(pelm_read_sanitized_request_parm("pelm_security", null)) {
        if (!wp_verify_nonce(pelm_read_sanitized_request_parm("pelm_security"), 'pelm_nonce')) {
            die("<br><br>CSRF: Hmm .. looks like you didn't send any credentials.. No access for you!");
        }
    }else {
        die("<br><br>CSRF: Hmm .. looks like you didn't send any credentials.. No access for you!");
    }
    
    update_option("plem_price_mem_limit" . ini_get('memory_limit'), pelm_read_sanitized_request_parm("set_mem_limit"), false);
    
    ?>
        <div class="scope_body">
        
        <style type="text/css">
            html, body{
                background:#505050;
                color:white;
                font-family:sans-serif;    
            }
        </style>
        
        <div class="scope_body_content">
          <script type="text/javascript">
                window.location = window.location.href.split("&set_mem_limit")[0] + "&pelm_security=<?php echo esc_attr(pelm_get_nonce("pelm_nonce")); ?>";
          </script>
        </div>
        </div>
    <?php
    die;
    return;
}

$mem_limit = get_option("plem_price_mem_limit" . ini_get('memory_limit'), 0); 
global $start_mem;
$start_mem = memory_get_usage();

function pelm_get_mem_allocated()
{
    global $start_mem;
    return memory_get_usage() - $start_mem;
} 

if(!$mem_limit && (pelm_read_sanitized_request_parm("DO_UPDATE", null) || pelm_read_sanitized_request_parm("DO_EXPORT", null) || pelm_read_sanitized_request_parm("DO_IMPORT", null))) {
    $mem_limit = 128000000;
}

if(pelm_read_sanitized_request_parm("memtest", null)) {
    
    header('Content-Type: text/text; charset=' . get_option('blog_charset'), true);
    $x = array_fill(0, intval(pelm_read_sanitized_request_parm("memtest")), false);
    wp_die(esc_html("OK ". count($x)));
    return;//
    
}elseif($mem_limit == 0) {
    
    wp_enqueue_script('jquery');
    
    ?>
    
    <div class="scope_body">
        
        <style type="text/css">
            html, body{
                background:#505050;
                color:white;
                font-family:sans-serif;    
            }
        </style>
    <?php wp_print_scripts(); ?>
        
        <div>
            <h3>Inspecting environment...</h3>
            <p>Please wait a moment!</p>
            <script type="text/javascript">
            var curr_memtest = 100000;
            var test_jump    = curr_memtest;
            
            function pelm_checkmem(){
                jQuery.ajax({
                    url: window.location.href + "&memtest=" + (curr_memtest + test_jump) + "&pelm_security=<?php echo esc_attr(pelm_get_nonce("pelm_nonce")); ?>",
                    type: "GET",
                    success: function (data) {
                        if(curr_memtest > 3200000){
                            window.location = window.location.href + "&set_mem_limit=" + (curr_memtest * 80) + "&pelm_security=<?php echo esc_attr(pelm_get_nonce("pelm_nonce")); ?>";
                            return;
                        }
                        
                        if(data){
                            
                            if(!jQuery("span.memtest-info")[0]){
                                jQuery("BODY").append(jQuery("<span class='memtest-info' ></span>"));
                            }
                            
                            jQuery("span.memtest-info").html("Meme test passed: " + curr_memtest );
                            
                            curr_memtest += test_jump;
                            pelm_checkmem();
                        }else{
                            if(test_jump == 25000){
                                window.location = window.location.href + "&set_mem_limit=" + (curr_memtest * 80) + "&pelm_security=<?php echo esc_attr(pelm_get_nonce("pelm_nonce")); ?>"; 
                            }else{
                                test_jump = test_jump / 2;
                                pelm_checkmem();
                            }
                        }
                    },
                    error:function (a,b,c) {
                        
                        if(test_jump == 25000){
                                window.location = window.location.href + "&set_mem_limit=" + (curr_memtest * 80) + "&pelm_security=<?php echo esc_attr(pelm_get_nonce("pelm_nonce")); ?>"; 
                        }else{
                            
                            curr_memtest -= test_jump;
                            test_jump = test_jump / 2;
                            curr_memtest += test_jump;
                            
                            pelm_checkmem();
                        }
                    }
                });
            };
            pelm_checkmem();
            </script>
        </div>
    </div>
    <?php
    die();
    return;
}

//POSIBLE REQUESTS do_import, do_export
$wooc_fields_visible = array();
if(isset($plem_price_settings['wooc_fileds'])) {
    foreach(explode(",", $plem_price_settings['wooc_fileds']) as $I => $val){
        if($val) {
            $wooc_fields_visible[$val] = true;
        }
    }
}

global $impexp_settings, $custom_export_columns;

$impexp_settings       = new stdClass;
$custom_export_columns = array();        



if(isset($plem_price_settings[pelm_read_sanitized_request_parm("elpm_shop_com", "").'_custom_import_settings'])) {
    $impexp_settings = $plem_price_settings[pelm_read_sanitized_request_parm("elpm_shop_com", "").'_custom_import_settings'];
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
    global $wooc_fields_visible, $custom_export_columns, $impexp_settings;
    
    if(empty($wooc_fields_visible)) {
        return true;
    }
    
    if($impexp_settings->use_custom_export && pelm_read_sanitized_request_parm("do_export", null)) {
        if(in_array($name, $custom_export_columns)) {
            return true;
        } else {
            return false;
        }
    }else if($name=="categories_paths" && $impexp_settings->use_custom_export && pelm_read_sanitized_request_parm("do_export", null)) {
        return true;
    }
        
    if(isset($wooc_fields_visible[$name])) {
        return $wooc_fields_visible[$name];
    }
    
    $wooc_fields_visible[$name] = false;
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

global $sitepress;
if(isset($sitepress)) {
    $sitepress->switch_lang($sitepress->get_default_language(), true);
}

function pelm_on_product_update($id, $post = null)
{
    global $woocommerce_wpml;
    if(isset($woocommerce_wpml)) {
        if(isset($woocommerce_wpml->products)) {
            global $pagenow;
            $pagenow = 'post.php';
            if(method_exists($woocommerce_wpml->products, "sync_post_action")) {
                $woocommerce_wpml->products->sync_post_action($id, $post === null ? get_post($id) : $post);
            }
        }
    }
}

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

$variations_fields = array(
    'stock',
    'slug',
    'sku',
    'shipping_class',
    "weight",
    "length",
    "width",
    "height",
    "price",
    "override_price",
    "stock_status",
    "backorders",
    "virtual",
    "downloadable",
    "status"
); 

$attributes      = array();
$attributes_asoc = array();
function pelm_load_attributes(&$attributes, &$attributes_asoc)
{
    global $wpdb, $variations_fields;
    $woo_attrs = $wpdb->get_results("select * from " . $wpdb->prefix . "woocommerce_attribute_taxonomies", ARRAY_A);
    
    foreach($woo_attrs as $attr){
        $att         = new stdClass();
        $att->id     = $attr['attribute_id'];
        $att->name   = $attr['attribute_name'];  
        $att->label  = $attr['attribute_label']; 
        if(!$att->label) {
            $att->label = ucfirst($att->name);
        }
        $att->type   = $attr['attribute_type'];

      
        $att->values = array();
        $values     = get_terms('pa_' . $att->name, array('hide_empty' => false));
        foreach($values as $val){
            $value          = new stdClass();
            $value->id      = $val->term_id;
            $value->slug    = $val->slug;
            $value->name    = $val->name;
            $value->parent  = $val->parent;
            $att->values[]  = $value;
        }
     
        $attributes[]                = $att;
        $attributes_asoc[$att->name] = $att;
        $variations_fields[] = 'pattribute_'.$att->id;
    }
};

pelm_load_attributes($attributes, $attributes_asoc);

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
    return null;
};

function pelm_update_parent_price_data($parent)
{
    global $wpdb;
    $wpdb->flush();
    
    $var_ids = $wpdb->get_col(
        $wpdb->prepare( 
            "SELECT      p.ID
			FROM        $wpdb->posts p
		 WHERE       p.post_type = 'product_variation'
						AND 
					 p.post_parent = %d
		 ORDER BY    p.ID
		",
            $parent
        )
    );
    
    $_min_variation_price            = '';
    $_max_variation_price            = '';
    $_min_price_variation_id         = '';
    $_max_price_variation_id         = ''; 
    
    $_min_variation_regular_price    = '';
    $_max_variation_regular_price    = '';
    $_min_regular_price_variation_id = '';
    $_max_regular_price_variation_id = '';
    
    $_min_variation_sale_price       = '';
    $_max_variation_sale_price       = '';
    $_min_sale_price_variation_id    = '';
    $_max_sale_price_variation_id    = '';
    
    foreach($var_ids as $vid){
        $_regular_price = get_post_meta($vid, '_regular_price', true);
        $_sale_price = get_post_meta($vid, '_sale_price', true);
        $_price  = get_post_meta($vid, '_price', true);
        
        if(!$_price) {
            $_price = $_sale_price ? $_sale_price : $_regular_price;
        }
        
        if($_price) {
            if(!$_min_variation_price) {
                $_min_variation_price    = $_price;
                $_min_price_variation_id = $vid;
            }
            if(!$_max_variation_price) {
                $_max_variation_price    = $_price;
                $_max_price_variation_id = $vid;
            }
            
            if(floatval($_min_variation_price) > floatval($_price)) {
                $_min_variation_price    = $_price;
                $_min_price_variation_id = $vid;
            }
            
            if(floatval($_max_variation_price) < floatval($_price)) {
                $_max_variation_price    = $_price;
                $_max_price_variation_id = $vid;
            }
        }
        
        if($_regular_price) {
            if(!$_min_variation_regular_price) {
                $_min_variation_regular_price    = $_regular_price;
                $_min_regular_price_variation_id = $vid;
            }
            
            if(!$_max_variation_regular_price) {
                $_max_variation_regular_price    = $_regular_price;
                $_max_regular_price_variation_id = $vid;
            }
            
            if(floatval($_min_variation_regular_price) > floatval($_regular_price)) {
                $_min_variation_regular_price    = $_regular_price;
                $_min_regular_price_variation_id = $vid;
            }
            
            if(floatval($_max_variation_regular_price) < floatval($_regular_price)) {
                $_max_variation_regular_price    = $_regular_price;
                $_max_regular_price_variation_id = $vid;
            }
        }
        
        if($_sale_price) {
            if(!$_min_variation_sale_price) {
                $_min_variation_sale_price    = $_sale_price;
                $_min_sale_price_variation_id = $vid;
            }
            
            if(!$_max_variation_sale_price) {
                $_max_variation_sale_price    = $_sale_price;
                $_max_sale_price_variation_id = $vid;
            }
            
            if(floatval($_min_variation_sale_price) > floatval($_sale_price)) {
                $_min_variation_sale_price    = $_sale_price;
                $_min_sale_price_variation_id = $vid;
            }
            
            if(floatval($_max_variation_sale_price) < floatval($_sale_price)) {
                $_max_variation_sale_price    = $_sale_price;
                $_max_sale_price_variation_id = $vid;
            }
        }
    }
    
    update_post_meta($parent, '_min_variation_price', $_min_variation_price);
    update_post_meta($parent, '_max_variation_price', $_max_variation_price);
    update_post_meta($parent, '_min_price_variation_id', $_min_price_variation_id);
    update_post_meta($parent, '_max_price_variation_id', $_max_price_variation_id);
    update_post_meta($parent, '_min_variation_regular_price', $_min_variation_regular_price);
    update_post_meta($parent, '_max_variation_regular_price', $_max_variation_regular_price);
    update_post_meta($parent, '_min_regular_price_variation_id', $_min_regular_price_variation_id);
    update_post_meta($parent, '_max_regular_price_variation_id', $_max_regular_price_variation_id);
    update_post_meta($parent, '_min_variation_sale_price', $_min_variation_sale_price);
    update_post_meta($parent, '_max_variation_sale_price', $_max_variation_sale_price);
    update_post_meta($parent, '_min_sale_price_variation_id', $_min_sale_price_variation_id);
    update_post_meta($parent, '_max_sale_price_variation_id', $_max_sale_price_variation_id);
    update_post_meta($parent, '_price', $_min_variation_price);
    if(function_exists("wc_delete_product_transients")) {
        wc_delete_product_transients($parent);
    }
}

function pelm_update_parent_variation_data($parent, &$res_obj, &$aasoc,$attributes_set)
{
    global $wpdb;
    $defaultAtt = get_post_meta($parent, '_product_attributes', true);
    $wpdb->flush();
    
    $var_ids = $wpdb->get_col(
        $wpdb->prepare( 
            "SELECT      p.ID
			FROM        $wpdb->posts p
		 WHERE       p.post_type   = 'product_variation'
						AND 
					 p.post_parent = %d
		 ORDER BY    p.ID
		",
            $parent
        )
    );

    $attrs = array();
    if(!empty($var_ids)) {
        $wpdb->flush();
        $attrs = $wpdb->get_col(
            "SELECT DISTINCT pm.meta_key
						FROM        $wpdb->postmeta pm
					 WHERE       pm.post_id IN (".implode(",", $var_ids).")
								 AND
								 pm.meta_key LIKE 'attribute_pa_%'"
        );
    }
    
    $curr_attrs = array_keys($defaultAtt);
    $dirty = false;    
    foreach($curr_attrs as $attr_name){
        $a = "attribute_" . $attr_name;
        if($defaultAtt[$attr_name]["is_variation"]) {
            if(!in_array($a, $attrs)) {
                $defaultAtt[$attr_name]['is_variation'] = 0; 
                $dirty = true;
                wp_set_object_terms($parent, null, $attr_name);
            }
        }
    }
    
    if(!empty($attributes_set)) {
        foreach($attrs as $ind => $att){
            $a = substr($att, 10);
            if(in_array(substr($a, 3), $attributes_set)) {
                if(!in_array($a, $attrs)) {
                    $defaultAtt[$a] = array (
                    'name'         => $a,
                    'value'        => '',
                    'position'     => count($defaultAtt),
                    'is_visible'   => 1,
                    'is_variation' => 1,
                    'is_taxonomy'  => 1
                    );
                    $dirty = true;                    
                }else{
                    if(!$defaultAtt[$a]["is_variation"]) {
                        $defaultAtt[$a]["is_variation"] = 1;
                        $dirty = true;                    
                    }
                }
            }
        }
    }
    
    
    if($dirty) {
        
        update_post_meta($parent, '_product_attributes', $defaultAtt);
        pelm_update_parent_price_data($parent);
        
        foreach($defaultAtt as $key=> $val){
            if($defaultAtt[$key]["is_variation"]) {
                $aterm = substr($key, 3);
                $val = array();
                foreach($var_ids as $v_id){
                    $v = get_post_meta($v_id, 'attribute_'. $key, true);
                    if($v) {
                        $val[] = $v."";
                    }
                }
                wp_set_object_terms($parent, $val, $key);
            }
        }
        
        if($res_obj) {
            $ainf = array();
            
            if(!isset($res_obj->dependant_updates)) {
                $res_obj->dependant_updates = array();
            }
            
            if(!isset($res_obj->dependant_updates[$parent])) {
                $res_obj->dependant_updates[$parent] = new stdClass;
            }
                    
            foreach($aasoc as $key=> $val){
                $a_id = $val->id;
                $ainf[$a_id]    = new stdClass;
                if(isset($defaultAtt["pa_".$key])) {
                    $ainf[$a_id]->v = $defaultAtt["pa_".$key]["is_variation"];
                    $ainf[$a_id]->s = $defaultAtt["pa_".$key]["is_visible"] ? true : false;
                }else{
                    $ainf[$a_id]->v = 0;
                    $ainf[$a_id]->s = true;
                }
                
                $res_obj->dependant_updates[$parent]->{"pattribute_" . $val->id } = wp_get_object_terms($parent, "pa_".$key, array('fields' => 'ids'));
            }
            
            if(!isset($res_obj->dependant_updates[$parent])) {
                $res_obj->dependant_updates[$parent] = new stdClass;
            }
            
            $res_obj->dependant_updates[$parent]->att_info = $ainf;
        } 
    }
}

pelm_load_custom_fields($plem_price_settings, $custom_fileds);

$tax_classes  = array();
foreach(explode("\n", get_option('woocommerce_tax_classes')) as $tc){
    $tax_classes[ str_replace(" ", "-", strtolower($tc))] = $tc;
}

$tax_statuses = array();
$tax_statuses['taxable'] = 'Taxable';
$tax_statuses['shipping'] = 'Shipp. only';
$tax_statuses['none'] = 'None';

$productsPerPage = 500;
if(isset($plem_price_settings["productsPerPage"])) {
    $productsPerPage = intval($plem_price_settings["productsPerPage"]);
}

$limit = $productsPerPage;

if(pelm_read_sanitized_cookie_parm('pelm_txtlimit', 0)) {
    $limit = pelm_read_sanitized_cookie_parm('pelm_txtlimit', $productsPerPage);
}
    
$page_no  = 1;

$orderby          = "ID";
$orderby_key      = "";

$sort_order        = "DESC";
$sku               = '';
$product_name       = '';
$product_category = '';
$product_tag      = '';
$product_shipingclass = '';
$product_status   = '';



if(pelm_read_sanitized_request_parm('limit')) {
    $limit = pelm_read_sanitized_request_parm('limit');
    setcookie('pelm_txtlimit', $limit, time() + 3600 * 24 * 30);
}

if(pelm_read_sanitized_request_parm('page_no')) {
    $page_no = pelm_read_sanitized_request_parm('page_no');
}

if(pelm_read_sanitized_request_parm('sku')) {
    $sku = pelm_read_sanitized_request_parm('sku');
}

if(pelm_read_sanitized_request_parm('product_name')) {
    $product_name = pelm_read_sanitized_request_parm('product_name');
}

if(pelm_read_sanitized_request_parm('product_tag')) {
    $product_tag = explode(",", pelm_read_sanitized_request_parm('product_tag'));
}

if(pelm_read_sanitized_request_parm('product_category')) {
    $product_category = explode(",", pelm_read_sanitized_request_parm('product_category'));
}

if(pelm_read_sanitized_request_parm('product_shipingclass')) {
    $product_shipingclass = explode(",", pelm_read_sanitized_request_parm('product_shipingclass'));
}

if(pelm_read_sanitized_request_parm('product_status')) {
    $product_status = explode(",", pelm_read_sanitized_request_parm('product_status'));
}

$filter_attributes = array();
foreach($attributes as $attr){
    if(pelm_read_sanitized_request_parm('pattribute_'.$attr->id)) {
        $filter_attributes[$attr->name] = explode(",", pelm_read_sanitized_request_parm('pattribute_'.$attr->id));
    }
}

if(pelm_read_sanitized_request_parm('sortColumn')) {

    $orderby = pelm_read_sanitized_request_parm('sortColumn');
    $orderby_key = "";

    if(isset($custom_fileds[$orderby])) {
        $field = $custom_fileds[$orderby];
        if($custom_fileds[$orderby]->type == 'post') {
            $orderby = $custom_fileds[$orderby]->source;
            $orderby_key = "";
        }elseif($custom_fileds[$orderby]->type == 'meta') {
            $orderby = "meta_value";
            $orderby_key = $custom_fileds[$orderby]->source;
        }
    }elseif($orderby == "sku") {
        $orderby = "meta_value";
        $orderby_key = "_sku";
    }elseif($orderby == "slug") { $orderby = "name";
    } elseif($orderby == "name") { $orderby = "title";
    } elseif($orderby == "stock") {
        $orderby = "meta_value_num";
        $orderby_key = "_stock";
    }elseif($orderby == "stock_status") {
        $orderby = "meta_value";
        $orderby_key = "_stock_status";
    }elseif($orderby == "price") {
        $orderby = "meta_value_num";
        $orderby_key = "_regular_price";
    }elseif($orderby == "override_price") {
        $orderby = "meta_value_num";
        $orderby_key = "_sale_price";
    }elseif($orderby == "status") { 
        $orderby = "status";
    }elseif($orderby == "weight") { 
        $orderby = "meta_value_num";
        $orderby_key = "_weight";
    }elseif($orderby == "length") { 
        $orderby = "meta_value_num";
        $orderby_key = "_length";
    }elseif($orderby == "width") { 
        $orderby = "meta_value_num";
        $orderby_key = "_width";
    }elseif($orderby == "height") { 
        $orderby = "meta_value_num";
        $orderby_key = "_height";
    }elseif($orderby == "featured") { 
        $orderby = "meta_value";
        $orderby_key = "_featured";
    }elseif($orderby == "virtual") { 
        $orderby = "meta_value";
        $orderby_key = "_virtual";
    }elseif($orderby == "downloadable") { 
        $orderby = "meta_value";
        $orderby_key = "_downloadable";
    }elseif($orderby == "tax_status") { 
        $orderby = "meta_value";
        $orderby_key = "_tax_status";
    }elseif($orderby == "tax_class") { 
        $orderby = "meta_value";
        $orderby_key = "_tax_class";
    }elseif($orderby == "backorders") { 
        $orderby = "meta_value";
        $orderby_key = "_backorders";
    }elseif($orderby == "tags") { 
        $orderby = "ID";
    }else {
        $orderby = "ID";
    }
    
    if(!$orderby) {
        $orderby = "ID";
    }
}

if(pelm_read_sanitized_request_parm('sortOrder')) {
    $sort_order = pelm_read_sanitized_request_parm('sortOrder');
}


if(pelm_read_sanitized_request_parm('DO_UPDATE')) {
    
    
    if(!( current_user_can('editor') || current_user_can('administrator'))) {
        wp_die("You are not allowed to edit products!");
        return;
    }

    if(pelm_read_sanitized_request_parm('DO_UPDATE') == '1' && $is_post_request) {
        
        $timestamp          = time();
        $json              = file_get_contents('php://input');
        $tasks              = json_decode($json);
        $surogates          = get_option("plem_price_wooc_surogates", array());
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
            }else if(!is_numeric($key)) {
                continue;
            }
           
           
            $parent = get_ancestors($key, 'product');
            if(!empty($parent)) {
                $parent = $parent[0];
            } else {        
                $parent = 0;
            }
           
            $upd_prop = array();
            $post_update = array( 'ID' => $key );
          
            $any_price_set = false;
            if(isset($task->price)) { 
                update_post_meta($key, '_regular_price', sanitize_text_field($task->price));
                $any_price_set = true;
            }
           
            if(isset($task->override_price)) {
                update_post_meta($key, '_sale_price', sanitize_text_field($task->override_price));
                $any_price_set = true;
            }
           
            if(isset($task->muk_price)) {
                $res_item->results = $wpdb->get_results(str_ireplace("#__", $wpdb->prefix,  sanitize_text_field($task->muk_price)), ARRAY_A);
            }
           
            if($any_price_set) {
                $s_price = get_post_meta($key, '_sale_price', true);
                $r_price = get_post_meta($key, '_regular_price', true);
                $_price = $s_price ? $s_price :  $r_price;
                update_post_meta($key, '_price', $_price);
                
                if($parent) {
                    pelm_update_parent_price_data($parent);
                }
            }
           
            if(function_exists("wc_delete_product_transients")) {
                wc_delete_product_transients($key);
            }
           
            if($return_added) {
                $res_item->surogate = $sKEY;
                $res_item->full     = pelm_product_render($key, $attributes, "data");
            }
           
            $res_item->success = true;
            $res[] = $res_item;
           
                        
            pelm_on_product_update($parent ? $parent : $key);
           
        }
        
        if($surogates_dirty) {
            update_option("plem_price_wooc_surogates", (array)$surogates);
        }
        
        echo (json_encode($res));
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


function &pelm_get_category_path($pcat_id)
{
    global $cat_asoc,$catpath_asoc;
    
    if(!isset($cat_asoc[$pcat_id]->category_path)) {
        $cname = "";
        $tname = "";
        $tmp   = $cat_asoc[$pcat_id];
        do{
            if(!$cname) {
                $tname = $cname = $tmp->category_name;
            }else{
                $cname = $tmp->category_name . ('/' . $cname);
                $tname = '-' . $tname;
            }
            if(isset($cat_asoc[$tmp->category_parent])) {
                $tmp = $cat_asoc[$tmp->category_parent];
            } else {
                $tmp = false;
            }
        }while($tmp);
        
        $cat_asoc[$pcat_id]->category_path = $cname;
        $cat_asoc[$pcat_id]->treename      = $tname;
        if($catpath_asoc !== null) {
            $catpath_asoc[strtolower($cname)] = $pcat_id;
        }
    }
    return $cat_asoc[$pcat_id]->category_path;
}

function pelm_get_product_categories_paths($id)
{
    global $cat_asoc;
    $pcategories = wp_get_object_terms($id, 'product_cat', array('fields' => 'ids'));
    $cpaths = array();
    foreach($pcategories as $pcat_id){
        $cpaths[] = pelm_get_category_path($pcat_id);
    }
    return implode(",", $cpaths);
}


//CSV IMPORT FNs ///////////////////////////////////////////////////////////////////////////////////////////
function pelm_get_csv_order()
{
    global $wpdb;
    
    $value  =$wpdb->get_col("SELECT option_value FROM $wpdb->options WHERE option_name LIKE '%pelm_order_csv%'");
    
    $csv_order=array();
    $csv_order=explode(",", $value[0]);

    return $csv_order;
    
}

function pelm_remember_csv_order($order)
{
    $order_string = implode(",", $order);
    update_option("pelm_order_csv", $order_string, "no");    
}

function pelm_save_csv_for_cross_req_import($data_csv,$import_uid)
{
    global $impexp_settings;
    if ($data_csv) {
        $n = 0;
        while (($data = fgetcsv($data_csv, 32768 * 4, $impexp_settings->delimiter)) !== false) {
            if($n == 0) {//REMOVE UTF8 BOM IF THERE
                 $bom     = pack('H*', 'EFBBBF');
                 $data[0] = preg_replace("/^$bom/", '', $data[0]);
            }
            update_option($import_uid."_". $n, $data, "no");
            $n++;    
        }
    }
}

function pelm_insert_image_media($image)
{
    global $wpdb, $image_import_path_cache;
    $attach_id = false;
    $upload_dir = wp_upload_dir();
    $ok=false;

    $filename =  sanitize_file_name(basename(urldecode($image)));
    $file = $upload_dir['url'] . '/' . $filename;
    if (wp_mkdir_p($upload_dir['path'])) {
        $file = $upload_dir['path'].DIRECTORY_SEPARATOR.$filename;
    } 
    else{
        $file = $upload_dir['basedir'].DIRECTORY_SEPARATOR.$filename;
    }
    
    list($directory, , $extension, $filename) = array_values(pathinfo($file));
    $new_file_name = $filename.".".$extension;

    $wp_filetype = wp_check_filetype($file, null);

    if (!$wp_filetype['type'] && !empty($mime_type)) {
        $allowed_content_types = wp_get_mime_types();
        
        if (in_array($mime_type, $allowed_content_types)) {
            $wp_filetype['type'] = $mime_type;
        }
    }

    $uploads = stristr($image, 'uploads');
    $path_add = substr($uploads, 7);
    $path = $upload_dir["basedir"].$path_add;

    if(isset($image_import_path_cache[$image])) {
        //postoji vec, samo vracamo id
        $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $upload_dir['url'] . '/' .$new_file_name)); 
        return $attachment[0];
    
    }
    elseif(file_exists($path) && strpos($path, $extension)!=false) {
        //postoji na disku ali ne i informacija da je u impc vraticemo id 
        $image_import_path_cache[$image]=$path;
        $absolute_path = $upload_dir['baseurl'].$path_add;
        $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $absolute_path)); 
        return $attachment[0];
    }
    else{
        if(file_exists($file)) {
            $i= 1;
            //loop until it works
            while (file_exists($file))
            {
                //create a new filename
                $file = $directory . '/' . $filename . '-' . $i . '.' . $extension;
                // ! DEV fix file url in image
                $new_file_name = $filename . '-' . $i . '.' . $extension;
                $i++;
            }
        }
        
        $attachment = array(
        'guid'           => $upload_dir['url'] . '/' .$new_file_name, 
        'post_mime_type' => $wp_filetype['type'],
        'post_title'     => preg_replace('/\.[^.]+$/', '', $new_file_name),
        'post_content'   => '',
        'post_status'    => 'inherit'
        );
        
        
        $upload_dir['subdir'] = substr($upload_dir['subdir'], 1);
        $image_data = file_get_contents($image);
        file_put_contents($file, $image_data);    
        
        //$attach_id = wp_insert_attachment( $attachment, $upload_dir['subdir'] . '/' .$new_file_name);
        $attach_id = wp_insert_attachment($attachment, $upload_dir['subdir'] . '/' .$new_file_name);
        
        include_once ABSPATH . 'wp-admin/includes/image.php';

        $attach_data = wp_generate_attachment_metadata($attach_id, $file);

        wp_update_attachment_metadata($attach_id, $attach_data);
        $image_import_path_cache[$image]=$file;
    
        return $attach_id;
    }
}

global $cat_asoc,$categories, $catpath_asoc;
$catpath_asoc = null;



if(pelm_read_sanitized_request_parm("do_import")) {
    if(pelm_read_sanitized_request_parm("do_import") == "1") {
        $catpath_asoc = array();
    }
}
    
$cat_asoc   = array();
$categories = array();

$shipping_classes   = array();
$shippclass_asoc    = array();

$product_types      = array();
//$product_types_asoc = array();

$args = array(
    'number'     => 99999,
    'orderby'    => 'slug',
    'order'      => 'ASC',
    'hide_empty' => false,
    'include'    => ''
);

function pelm_catsort($a, $b)
{
    return strcmp($a->category_path, $b->category_path);
}

$woo_categories = get_terms('product_cat', $args);

foreach($woo_categories as $category){
    $cat = new stdClass();
    $cat->category_id     = $category->term_id;
    $cat->category_name   = $category->name;
    $cat->category_slug   = urldecode($category->slug);
    $cat->category_parent = $category->parent;
    $cat_asoc[$cat->category_id] = $cat;
    $categories[]         = $cat_asoc[$cat->category_id];
};

foreach($cat_asoc as $cid => $cat){
    pelm_get_category_path($cid);    
}

usort($categories, "pelm_catsort");

$woo_shipping_classes = get_terms('product_shipping_class', $args);
foreach($woo_shipping_classes as $shipping_class){
    $sc = new stdClass();
    $sc->id     = $shipping_class->term_id;
    $sc->name   = $shipping_class->name;
    $sc->slug   = urldecode($shipping_class->slug);
    $sc->parent = $shipping_class->parent;
    $shipping_classes[] = $sc;   
    $shippclass_asoc[$sc->id] = $sc;
}

$woo_ptypes = get_terms('product_type', $args);
foreach($woo_ptypes as $T){
    $PT = new stdClass();
    $PT->id     = $T->term_id;
    $PT->name   = $T->name;
    $PT->slug   = urldecode($T->slug);
    $PT->parent = $T->parent;
    $product_types[] = $PT;   
    //$product_types_asoc[$sc->id] = $PT;
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////

$import_count = 0;
if(pelm_read_sanitized_request_parm("do_import")) {
    if(pelm_read_sanitized_request_parm("do_import") == "1") {
        
        if(!( current_user_can('editor') || current_user_can('administrator'))) {
            wp_die("You are not allowed to edit products!");
            return;
        }
        
        $import_uid = pelm_read_sanitized_request_parm("import_uid", uniqid("plem_price_import_"));
        
        $n = 0;
        if (($handle = fopen($_FILES['file']['tmp_name'], "r")) !== false  || pelm_read_sanitized_request_parm("continueFrom")) {
            
            

            if(!pelm_read_sanitized_request_parm("continueFrom")) {
                pelm_save_csv_for_cross_req_import($handle, $import_uid);
            }

            $id_index                  = -1;
            $price_index               = -1;
            $price_o_index             = -1;
            $stock_index               = -1;
            $sku_index                 = -1;
            $name_index                = -1;
            $slug_index                = -1;
            $status_index              = -1;
            $categories_names_index    = -1;
            $categories_paths_index    = -1;
            $shipping_class_name_index = -1;
            $weight_index              = -1;
            $length_index              = -1;
            $width_index               = -1;
            $height_index              = -1;
            $featured_index            = -1;
            $virtual_index             = -1;
            $downloadable_index        = -1;
            $tax_status_index          = -1;
            $tax_class_index           = -1;
            $backorders_index          = -1;
            $tags_names_index          = -1;
            $product_type_index        = -1;
            $parent_index              = -1;
            $featured_image_index       = -1; 
            $product_gallery_index     = -1; 

            $attribute_indexes         = array();
            $attribute_visibility_indexes = array();
            $cf_indexes                = array();
            $col_count = 0;
            $skip_first                = false;
            $custom_import             = false;
            $imported_ids              = array();
            $data_column_order         = array();
            $load_from_cross_state     = false;      
            
            
            if($impexp_settings) {
                if($impexp_settings->use_custom_import) {
                    $cic = array();
                    foreach(explode(",", $impexp_settings->custom_import_columns) as $col){
                        if($col) {
                            $cic[] = $col;
                        }
                    }
                    if($impexp_settings->first_row_header) {
                        $skip_first = true;
                    }
                        
                    $col_count         = count($cic);
                    $data_column_order = $cic;
                    $custom_import = true;
                }
            }
            
            $csv_row_processed_count = 0;
            global $image_import_path_cache;
            $image_import_path_cache = get_option("pelm_impc", array());
            
            if(!isset($impexp_settings->delimiter)) {
                $impexp_settings->delimiter = ",";
            }
            
            $import_count = intval(pelm_read_sanitized_request_parm("import_count", 0));
            
            if(!$custom_import) {
                if(pelm_read_sanitized_request_parm("continueFrom")) {
                    $data_column_order = pelm_get_csv_order();    
                }else{
                    $data_column_order = get_option($import_uid."_0", null);
                    $n = 1;
                }
            }else if($skip_first) {
                $n = 1;
            }
                        
            if(pelm_read_sanitized_request_parm("continueFrom")) {
                $n = intval(pelm_read_sanitized_request_parm("continueFrom"));
            }
            
            $data = get_option($import_uid."_".$n, null);
            
            if(!$data_column_order) {
                die("Missing column config!");
                return;
            }
            
            $col_count = count($data_column_order);
            for($i = 0 ; $i < $col_count; $i++){
                $data_column_order[$i] = trim($data_column_order[$i]);
                if($data_column_order[$i]     == "id") { $id_index = $i;
                } elseif($data_column_order[$i] == "price") { $price_index = $i;
                } elseif($data_column_order[$i] == "override_price") { $price_o_index = $i;
                } elseif($data_column_order[$i] == "sku") { $sku_index   = $i;
                } elseif($data_column_order[$i] == 'stock') { $stock_index = $i;
                } elseif($data_column_order[$i] == 'name') { $name_index = $i;
                } elseif($data_column_order[$i] == 'slug') { $slug_index = $i;
                } elseif($data_column_order[$i] == 'status') { $status_index = $i;
                } elseif($data_column_order[$i] == 'categories_names') { $categories_names_index = $i;
                } elseif($data_column_order[$i] == 'shipping_class_name') { $shipping_class_name_index = $i;
                } elseif($data_column_order[$i] == 'tags_names') { $tags_names_index = $i;
                } elseif($data_column_order[$i] == 'weight') { $weight_index = $i;
                } elseif($data_column_order[$i] == 'length') { $length_index = $i;
                } elseif($data_column_order[$i] == 'width') { $width_index = $i;
                } elseif($data_column_order[$i] == 'height') { $height_index = $i;
                } elseif($data_column_order[$i] == 'featured') { $featured_index = $i;
                } elseif($data_column_order[$i] == 'virtual') { $virtual_index = $i;
                } elseif($data_column_order[$i] == 'downloadable') { $downloadable_index = $i;
                } elseif($data_column_order[$i] == 'tax_status') { $tax_status_index = $i;
                } elseif($data_column_order[$i] == 'tax_class') { $tax_class_index = $i;
                } elseif($data_column_order[$i] == 'backorders') { $backorders_index = $i;
                } elseif($data_column_order[$i] == 'product_type') { $product_type_index = $i;
                } elseif($data_column_order[$i] == 'parent') { $parent_index = $i;
                } elseif($data_column_order[$i] == 'image') { $featured_image_index = $i;
                } elseif($data_column_order[$i] == 'gallery') { $product_gallery_index = $i;
                } elseif($data_column_order[$i] == 'categories_paths') { $categories_paths_index = $i;
                }
                
                foreach($attributes as $att){
                    if('pattribute_'.$att->id == $data_column_order[$i]) {
                        $attribute_indexes[$att->name] = $i;
                        break;
                    }
                    
                    if('pattribute_'.$att->id.'_visible' == $data_column_order[$i]) {
                        $attribute_visibility_indexes[$att->name] = $i;
                        break;
                    }
                }
                
                foreach($custom_fileds as $cfname => $cfield){
                    if($cfname == $data_column_order[$i]) {
                        $cf_indexes[$cfname] = $i;
                        break;
                    }
                }
            }
            
            pelm_remember_csv_order($data_column_order);
            
            while ($data) {
                
                $data = array_map("pelm_to_utf8", $data);
                
                if($csv_row_processed_count > 0 && ($csv_row_processed_count >= 300 || $start_time + $max_time  < time() || pelm_get_mem_allocated() + 1048576 > $mem_limit)) {
                    //////////////////////BRAK EXEC AND DO ANOTHER REQUEST/////////////////////////
                    update_option("pelm_impc", $image_import_path_cache, "no");    
                    if($handle) {
                           fclose($handle);
                    } 
                    ?>
                        
                        <div class="scope_body">
                            
                                <style type="text/css">
                                    html, body{
                                        background:#505050;
                                        color:white;
                                        font-family:sans-serif;    
                                    }
                                </style>
                            
                            <div class="scope_body_content">
                                <form method="POST" id="continueImportForm">
                                    <input name="pelm_security" type="hidden" value="<?php echo esc_attr(pelm_get_nonce("pelm_nonce")); ?>" />
                                
                                    <h2><?php echo esc_html__("Importing...", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></h2>
                                    <p><?php echo esc_attr($import_count); ?> <?php echo esc_html__("products/product variations entries processed from ", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?> <?php echo esc_attr($n)." CSV ".__("rows.", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');  ?> </p>
                                    <hr/>
                                    
                                    <input type="hidden" name="import_uid" value="<?php echo esc_attr($import_uid);?>">
                                    <input type="hidden" name="continueFrom" value="<?php echo esc_attr($n);?>">
                                    <input type="hidden" name="do_import" value="1">
                    <?php if(pelm_read_sanitized_request_parm("sortOrder")) { ?>
                                        <input type="hidden" name="sortOrder" value="<?php echo esc_attr($orderby);?>">
                    <?php } 
                    if(pelm_read_sanitized_request_parm("sortColumn")) { ?>
                                        <input type="hidden" name="sortColumn" value="<?php echo esc_attr($sort_order);?>">
                    <?php } 
                    if(pelm_read_sanitized_request_parm("page_no")) {?>
                                        <input type="hidden" name="page_no" value="<?php echo pelm_esc_sanitized_request_parm("page_no");?>">
                    <?php }
                    if(pelm_read_sanitized_request_parm("limit")) {?>
                                        <input type="hidden" name="limit" value="<?php echo pelm_esc_sanitized_request_parm("limit");?>">
                    <?php } 
                    if(pelm_read_sanitized_request_parm("sku")) {?>
                                        <input type="hidden" name="sku" value="<?php echo pelm_esc_sanitized_request_parm("sku");?>">
                    <?php } 
                    if(pelm_read_sanitized_request_parm("product_name")) {?>
                                        <input type="hidden" name="product_name" value="<?php echo pelm_esc_sanitized_request_parm("product_name");?>">
                    <?php }
                    if(pelm_read_sanitized_request_parm("product_category")) {?>
                                        <input type="hidden" name="product_category" value="<?php echo pelm_esc_sanitized_request_parm("product_category");?>">
                    <?php }                                    
                    if(pelm_read_sanitized_request_parm("product_shipingclass")) {?>
                                        <input type="hidden" name="product_shipingclass" value="<?php echo pelm_esc_sanitized_request_parm("product_shipingclass");?>">
                    <?php } 
                    if(pelm_read_sanitized_request_parm("product_tag")) {?>
                                        <input type="hidden" name="product_tag" value="<?php echo pelm_esc_sanitized_request_parm("product_tag");?>">
                    <?php } 
                    if(pelm_read_sanitized_request_parm("product_status")) {?>
                                        <input type="hidden" name="product_status" value="<?php echo pelm_esc_sanitized_request_parm("product_status");?>">
                    <?php } ?>
                                    
                    <?php foreach($attributes as $attr){ 
                                            
                        if(pelm_read_sanitized_request_parm("pattribute_" . $attr->id)) {
                            ?>
                                        <input type="hidden" name="pattribute_<?php echo esc_attr($attr->id);?>" value="<?php echo pelm_esc_sanitized_request_parm("pattribute_" . $attr->id);?>">
                            <?php	
                        }
                                            
                    } ?>
                                    <input type="hidden" name="import_count" value="<?php echo esc_attr($import_count) ;?>" />
                                </form>
                                <script type="text/javascript">
                                        document.getElementById("continueImportForm").submit();
                                </script>
                            </div>
                        </div>
                    <?php
                    
                    if(isset($impexp_settings->notfound_setpending)) {
                        if($impexp_settings->notfound_setpending) {
                            if(!empty($imported_ids)) {
                                $wpdb->query( 
                                    $wpdb->prepare("UPDATE $wpdb->posts SET post_status = 'pending' WHERE (post_type LIKE 'product_variation' or post_type LIKE 'product') AND NOT ID IN (". implode(",", $imported_ids) .")")
                                ); 
                            }else{
                                $wpdb->query( 
                                    $wpdb->prepare("UPDATE $wpdb->posts SET post_status = 'pending' WHERE (post_type LIKE 'product_variation' or post_type LIKE 'product')")
                                );
                            }
                        }
                    }
                    
                    die;
                    return;
                    exit;
                   
                    ///////////////////////////////////////////////////////////////////////////////
                    break;
                }
                //UPD ROUTINE/////////////////////////////////////////////////////////////////////////
                $csv_row_processed_count++;
                $id = null;
                if($id_index >= 0) {    
                    $id = intval($data[$id_index]);
                }

                if($sku_index != -1) {
                    $data[$sku_index] = trim($data[$sku_index]);
                }

                if(!$id && $sku_index != -1) {
                    if($data[$sku_index]) {
                        $res = $wpdb->get_col("select post_id from $wpdb->postmeta where meta_key like '_sku' and meta_value like '".$data[$sku_index]."'");
                        if(!empty($res)) {
                            $id = $res[0];
                        }
                    }
                }    

                if(!$id && $name_index != -1) {
                    if($data[$name_index]) {
                        $res = $wpdb->get_col("select ID from $wpdb->posts where cast(post_title as char(255)) like '" . $data[$name_index] . "' and (post_type like 'product' OR post_type like 'product_variation') ");
                        if(!empty($res)) {
                            $id = $res[0];
                        }
                    }
                }

                $continue = false;
                
                if($id) {
                    if(false === get_post_status($id)) {
                        $continue = true;
                    }
                }
                
                if($continue) {
                    $n++;
                    $data = get_option($import_uid."_".$n, null);
                    continue;
                }
                
                $parent = get_ancestors($id, 'product');
                if(!empty($parent)) {
                    $parent = $parent[0];
                }else {
                    $parent = 0;
                }
                
                $imported_ids[] = $id;    
                                
                while(count($data) < $col_count) {
                    $data[] = null;
                }    
                    
                $post_update = array( 'ID' => $id );

                

                $any_price_set = false;
                if($price_index > -1) { 
                    if($data[$price_index]) {
                        $data[$price_index] = pelm_get_float($data[$price_index]);
                    }

                    update_post_meta($id, '_regular_price', $data[$price_index]);
                    $any_price_set = true;
                }

                if($price_o_index > -1) {
                    if($data[$price_o_index]) {
                        $data[$price_o_index]= pelm_get_float($data[$price_o_index]);
                    }
                    
                    update_post_meta($id, '_sale_price', $data[$price_o_index]);
                    $any_price_set = true;
                }

                if($any_price_set) {
                    $s_price = get_post_meta($id, '_sale_price', true);
                    $r_price = get_post_meta($id, '_regular_price', true);
                    $_price = $s_price ? $s_price :  $r_price;
                    update_post_meta($id, '_price', $_price);
                    
                    if($parent) {
                        pelm_update_parent_price_data($parent);
                    }
                }

                if(function_exists("wc_delete_product_transients")) {
                    wc_delete_product_transients($id);
                }
                
                pelm_on_product_update($parent ? $parent : $id);
                //////////////////////////////////////////////////////////////////////////////////////
                $import_count++;
                $n++;
                $data = get_option($import_uid."_".$n, null);
            }
            
            if($handle) {
                fclose($handle);
            }
            
            if($csv_row_processed_count > 0) {
                if(isset($impexp_settings->notfound_setpending)) {
                    if($impexp_settings->notfound_setpending) {
                        if(!empty($imported_ids)) {
                            $wpdb->query( 
                                $wpdb->prepare("UPDATE $wpdb->posts SET post_status = 'pending' WHERE (post_type LIKE 'product_variation' or post_type LIKE 'product') AND NOT ID IN (". implode(",", $imported_ids) .")")
                            ); 
                        }else{
                            $wpdb->query( 
                                $wpdb->prepare("UPDATE $wpdb->posts SET post_status = 'pending' WHERE (post_type LIKE 'product_variation' or post_type LIKE 'product')")
                            );
                        }
                    }
                }
            }
        
            
        }
        //WE NEED TO RELOAD ATTRIBUTES BECUSE WE MIGHT CREATED SOME NEW ONES
        $attributes      = array();
        $attributes_asoc = array();
        pelm_load_attributes($attributes, $attributes_asoc);
        $custom_fileds   = array();
        pelm_load_custom_fields($plem_price_settings, $custom_fileds);
        ////////////////////////////////////////////////////////////////////
        
        
        //WHEN WE REACH THIS POINT IMPORT IS DONE///////
        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '$import_uid%'");
        delete_option("pelm_order_csv");
        delete_option("pelm_impc");
        ////////////////////////////////////////////////
    }
}


$_num_sample = (1/2).'';
$args = array(
     'post_type' => array('product','product_variation')
    ,'posts_per_page' => -1
    ,'ignore_sticky_posts' => true
    ,'orderby' => $orderby 
    ,'order' => $sort_order
    ,'fields' => 'ids'
);

if($product_status) {
    $args['post_status'] = $product_status;
}

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
                        'taxonomy' => 'product_cat',
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

if($product_shipingclass) {
    $tax_query[] =  array(
                        'taxonomy' => 'product_shipping_class',
                        'field' => 'id',
                        'terms' => $product_shipingclass
                    );
}

foreach($filter_attributes as $fattr => $vals){
    $ids   = array();
    $names = array();
    foreach($vals as $val){
        if(is_numeric($val)) {
            $ids[] = intval($val);
        } else {
            $names[] = $val;
        }
    }
    if(!empty($ids)) {
        $tax_query[] =  array(
          'taxonomy' => 'pa_' . $fattr,
          'field' => 'id',
          'terms' => $ids
                        );
    }
    
    if(!empty($names)) {
        $tax_query[] =  array(
          'taxonomy' => 'pa_' . $fattr,
          'field' => 'name',
          'terms' => $names
                        );
    }                
}

if($sku) {
    $meta_query[] = array(
                        'key'     => '_sku',
                        'value'   => $sku,
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
$post_statuses = get_post_stati();
$pos_stat = get_post_statuses();        
foreach($post_statuses as $name => $title){
    if(isset($pos_stat[$name])) {
        $post_statuses[$name] = $pos_stat[$name];
    }        
}

if(!pelm_read_sanitized_request_parm("mass_update_val")) {
    $args['posts_per_page'] = $limit; 
    $args['paged']          = $page_no;
}

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
        $pid = $products_query->post->ID; 
        $IDS[] = $pid;
    }
    
    wp_reset_postdata();
}

$ID_TMP = array();
foreach($IDS as $p_id){
    $ID_TMP[] = $p_id;
     
    $wpdb->flush();
    $variat_ids = $wpdb->get_col(
        $wpdb->prepare( 
            "SELECT 
				p.ID
			 FROM  
				$wpdb->posts p
			 WHERE  
				(p.post_type = 'product_variation' AND  p.post_parent = %d)
			 ORDER BY    p.ID
		",
         $p_id
		 ,
         $p_id
        )
    );
            
    foreach($variat_ids as $vid) {
        $ID_TMP[] = $vid;
    }
}

unset($IDS);
$IDS = $ID_TMP;

$IDS = array_unique($IDS);

$mu_res = pelm_read_sanitized_request_parm("mu_res", 0);

if(pelm_read_sanitized_request_parm("mass_update_val")) {
    
    $ucol  = "";
    $uprop = "pr_p.product_price";
  
    $mu_proccessed  = pelm_read_sanitized_request_parm("mu_proccessed", 0);
  
    $mu_updated     = 0;
    $interupted     = false;
  
    $mass_update_val = floatval(pelm_read_sanitized_request_parm("mass_update_val", 0));
  
    for ($i = $mu_proccessed; $i < count($IDS); $i++) {
        $id = $IDS[$i];
      
        if($mu_updated == 300 || $mu_updated > 0 && $start_time + $max_time  < time() || pelm_get_mem_allocated() + 1048576 > $mem_limit) {
            $interupted  = true;
            break;
        }else{
          
          
      
            if(pelm_read_sanitized_request_parm("mass_update_override")) {
                $override_price     = get_post_meta($id, '_sale_price', true);
                if(is_numeric($override_price)) {
                    $override_price = floatval($override_price);
                    if(pelm_read_sanitized_request_parm("mass_update_percentage")) {
                        update_post_meta($id, '_sale_price', $override_price * (1 + $mass_update_val / 100));
                        $mu_res++;
                    }else{
                        update_post_meta($id, '_sale_price', $override_price + $mass_update_val);
                        $mu_res++;
                    }
                }
            }else{
                $price              = get_post_meta($id, '_regular_price', true);
                if(is_numeric($price)) {
                       $price = floatval($price);
                    if(pelm_read_sanitized_request_parm("mass_update_percentage")) {
                        update_post_meta($id, '_regular_price', $price * (1 + $mass_update_val / 100));
                        $mu_res++;
                    }else{
                        update_post_meta($id, '_regular_price', $price + $mass_update_val);
                        $mu_res++;
                    }
                }
            }
          
            $_price = get_post_meta($id, '_sale_price', true) ? get_post_meta($id, '_sale_price', true) :  get_post_meta($id, '_regular_price', true);
            update_post_meta($id, '_price', $_price);
          
            $_price = floatval($_price);
            $parent = get_ancestors($id, 'product');
            if(!empty($parent)) {
                $parent = $parent[0];
                pelm_update_parent_price_data($parent);
            }
          
            pelm_on_product_update($parent ? $parent : $id);
          
            $mu_updated++;
        }
        $mu_proccessed++;
    }
  
    ?>
    
    <div class="scope_body">
        
        <style type="text/css">
            html, body{
                background:#505050;
                color:white;
                font-family:sans-serif;    
            }
        </style>
        
        <div class="scope_body_content">
            <form method="POST" id="continueMUfrom">
                <input name="pelm_security" type="hidden" value="<?php echo esc_attr(pelm_get_nonce("pelm_nonce")); ?>" />
                <h2><?php echo esc_html__("Updating prices...", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></h2>
                <h3><?php echo (pelm_esc_sanitized_request_parm("mass_update_percentage") ? "%" : "") . (floatval(pelm_esc_sanitized_request_parm("mass_update_val")) > 0 ? "+" : "-") . pelm_esc_sanitized_request_parm("mass_update_val");?></h3>
                <p>(<?php echo esc_attr($mu_res);?>) <?php echo esc_html__("products/product price updated of total ", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); 
					echo esc_attr($mu_proccessed);?><?php echo esc_html__(" processed.", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></p>
                <hr/>
                
                <?php if($interupted) { ?>
                <input type="hidden" name="mu_res" value="<?php echo esc_attr($mu_res);?>">
                <input type="hidden" name="mu_proccessed" value="<?php echo esc_attr($mu_proccessed);?>">
                <input type="hidden" name="mass_update_val" value="<?php echo pelm_esc_sanitized_request_parm("mass_update_val");?>">
                <input type="hidden" name="mass_update_override" value="<?php echo pelm_esc_sanitized_request_parm("mass_update_override");?>">
                <input type="hidden" name="mass_update_percentage" value="<?php echo pelm_esc_sanitized_request_parm("mass_update_percentage");?>">
                <?php } ?>
                
                <?php if((pelm_read_sanitized_request_parm("sortOrder"))) {?>
                    <input type="hidden" name="sortOrder" value="<?php echo esc_attr($orderby);?>">
                <?php } 
                if((pelm_read_sanitized_request_parm("sortColumn"))) {?>
                    <input type="hidden" name="sortColumn" value="<?php echo esc_attr($sort_order);?>">
                <?php } 
                if((pelm_read_sanitized_request_parm("page_no"))) {?>
                    <input type="hidden" name="page_no" value="<?php echo pelm_esc_sanitized_request_parm("page_no");?>">
                <?php }
                if((pelm_read_sanitized_request_parm("limit"))) {?>
                    <input type="hidden" name="limit" value="<?php echo pelm_esc_sanitized_request_parm("limit");?>">
                <?php } 
                if((pelm_read_sanitized_request_parm("sku"))) {?>
                    <input type="hidden" name="sku" value="<?php echo pelm_esc_sanitized_request_parm("sku");?>">
                <?php } 
                if((pelm_read_sanitized_request_parm("product_name"))) {?>
                    <input type="hidden" name="product_name" value="<?php echo pelm_esc_sanitized_request_parm("product_name");?>">
                <?php } 
                if((pelm_read_sanitized_request_parm("product_category"))) {?>
                    <input type="hidden" name="product_category" value="<?php echo pelm_esc_sanitized_request_parm("product_category");?>">
                <?php }
                if((pelm_read_sanitized_request_parm("product_shipingclass"))) {?>
                    <input type="hidden" name="product_shipingclass" value="<?php echo pelm_esc_sanitized_request_parm("product_shipingclass");?>">
                <?php } 
                if((pelm_read_sanitized_request_parm("product_tag"))) {?>
                    <input type="hidden" name="product_tag" value="<?php echo pelm_esc_sanitized_request_parm("product_tag");?>">
                <?php } 
                if((pelm_read_sanitized_request_parm("product_status"))) {?>
                    <input type="hidden" name="product_status" value="<?php echo pelm_esc_sanitized_request_parm("product_status");?>">
                <?php } ?>
                
                <?php foreach($attributes as $attr){ 
                    if(pelm_read_sanitized_request_parm("pattribute_" . $attr->id)) {
                            
                        ?>
                            <input type="hidden" name="pattribute_<?php echo esc_attr($attr->id);?>" value="<?php echo pelm_esc_sanitized_request_parm("pattribute_" . $attr->id);?>">
                        <?php	
                            
                    }
                } ?>
            </form>
            <script type="text/javascript">
                <?php if(!$interupted) { ?>
                setTimeout(function(){
                <?php } ?>
                    document.getElementById("continueMUfrom").submit();
                <?php if(!$interupted) { ?>
                    },2000);
                <?php } ?>    
            </script>
        </div>
    </div>
    
    <?php
    die();
    return;
    wp_reset_postdata();
}

//$count = count($ID_TMP);

function pelm_product_render(&$IDS,&$attributes,$op,&$df = null)
{
    global $wpdb, $custom_fileds, $impexp_settings, $custom_export_columns,$resume_skip;
    
    $is_for_export = intval(pelm_read_sanitized_request_parm("do_export", false));
    
    $p_ids = is_array($IDS) ? $IDS : array($IDS);
    
    if($resume_skip > 0) {
        $p_ids = array_slice($p_ids, $resume_skip);
    }
    
    $fcols = array();    
    foreach($custom_fileds as $cfname => $cfield){
        if($cfield->type == "post") {
            $fcols[] = $cfield->source;
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
        $prod->id             = $id;
      
        if(!$is_for_export) {
            $prod->type           = get_post_type($id);
        }
      
        $prod->parent  = get_ancestors($id, 'product');
        if(!empty($prod->parent)) {
            $prod->parent = $prod->parent[0];
        } else {
            $prod->parent = null;
        }
    
        if(pelm_fn_show_filed('sku')) {
            $prod->sku            = get_post_meta($id, '_sku', true);
        }
      
        if(pelm_fn_show_filed('slug')) {
            $prod->slug           = urldecode($raw_data[$id]->post_name);
        }
        
        if(pelm_fn_show_filed('stock')) {
            $prod->stock              = get_post_meta($id, '_stock', true);//_manage_stock - null if not
        }
      
        if(pelm_fn_show_filed('stock_status')) {
            if($is_for_export) {
                $prod->stock_status       = get_post_meta($id, '_stock_status', true);
            } else {
                $prod->stock_status       = get_post_meta($id, '_stock_status', true) == "instock" ? true : false;
            }
        }
      
        if(pelm_fn_show_filed('categories')) {
            $prod->categories     = wp_get_object_terms($id, 'product_cat', array('fields' => 'ids'));
        }
    
        if(pelm_fn_show_filed('shipping_class')) {
            $prod->shipping_class = wp_get_object_terms($id, 'product_shipping_class', array('fields' => 'ids'));
        }
    
    
        if(pelm_fn_show_filed('product_type') || !$is_for_export) {
            if($is_for_export) {
                $prod->product_type = implode(",", wp_get_object_terms($id, 'product_type', array('fields' => 'names')));
            } else {      
                $prod->product_type = wp_get_object_terms($id, 'product_type', array('fields' => 'ids'));
            }
        }
      
        if(pelm_fn_show_filed('virtual') || pelm_fn_show_filed('downloadable')) {
            $ptype = wp_get_object_terms($id, 'product_type', array('fields' => 'names'));
            if(is_array($ptype)) {
                if(!empty($ptype)) {
                    $ptype = $ptype[0];
                } else {
                    $ptype = null;
                }
            }
            
        
            if(pelm_fn_show_filed('virtual')) {
                if($is_for_export) {
                    $prod->virtual     = get_post_meta($id, '_virtual', true);
                } else{
                    $prod->virtual     = get_post_meta($id, '_virtual', true) == "yes" ? true : false;
                }
            }
        
            if(pelm_fn_show_filed('downloadable')) {
                if($is_for_export) {
                    $prod->downloadable     = get_post_meta($id, '_downloadable', true);
                } else{
                    $prod->downloadable     = get_post_meta($id, '_downloadable', true) == "yes" ? true : false;
                }
            }
        }
     
        if(!$is_for_export && $prod->parent) {
            if(pelm_fn_show_filed('categories')) {
                $prod->categories     = wp_get_object_terms($prod->parent, 'product_cat', array('fields' => 'ids'));
            }
        
            if(pelm_fn_show_filed('shipping_class')) {
                if($prod->shipping_class == -1) {
                    $prod->shipping_class = wp_get_object_terms($prod->parent, 'product_shipping_class', array('fields' => 'ids'));
                }
            }    
        }
      
        if($is_for_export) {
        
            if(pelm_fn_show_filed("categories_paths")) {
                $prod->categories_paths     = pelm_get_product_categories_paths($id);
            }elseif(pelm_fn_show_filed('categories')) {
                $prod->categories_names     = implode(", ", wp_get_object_terms($id, 'product_cat', array('fields' => 'names')));
            }
        
            unset($prod->categories);
        
            if(pelm_fn_show_filed('shipping_class')) {
                $prod->shipping_class_name  = implode(", ", wp_get_object_terms($id, 'product_shipping_class', array('fields' => 'names')));
                unset($prod->shipping_class);
            }
        
            if(pelm_fn_show_filed('stock_status')) {
                unset($prod->stock_status);
            }
        }
      
        if(pelm_fn_show_filed('price')) {
            $prod->price              = get_post_meta($id, '_regular_price', true);
        }
      
        if(pelm_fn_show_filed('override_price')) {
            $prod->override_price     = get_post_meta($id, '_sale_price', true);
        }
      
        $name_suffix = '';    
        if(pelm_fn_show_filed('name')) {
            if($prod->parent) {
                $prod->name         = get_the_title($prod->parent)." (";
                $name_suffix        = " variation #". intval($id) . ')';
            }else {
                $prod->name         = get_the_title($id);
            }
        } 
      
      
        $pa = get_post_meta($prod->parent ? $prod->parent : $id, '_product_attributes', true);
        $att_info = array();
      
        foreach($attributes as $att){
            if(!pelm_fn_show_filed('pattribute_' . $att->id)) {
                continue;
            }
          
            $inf    = new stdClass();
            $inf->v = 0;
            $inf->s = true;
            if(isset($pa['pa_'. $att->name])) {
                $inf->v = $pa['pa_'. $att->name]["is_variation"];
                $inf->s = $pa['pa_'. $att->name]["is_visible"] ? true : false;
            }
            $att_info[$att->id] = $inf;
        
            if($prod->parent) {
                if($inf->v) {
                    $att_value = get_post_meta($id, 'attribute_pa_'. $att->name, true);
                    $att_value = explode(",", $att_value);
                    $tnames    = array();
                    $tids      = array();
                    foreach($att_value as $tslug){
                             $term = null;
                        foreach($att->values as $trm){
                            if($trm->slug == $tslug) {
                                       $term = $trm;
                                       break;
                            }
                        }
                    
                        if($term) {
                            $tnames[] = $term->name;
                            $tids[]   = $term->id; 
                        }
                    
                        if(!empty($tids)) { //VARIANT Can have only one 
                            break;
                        }
                    }
                
                    if(pelm_fn_show_filed('name') && !empty($tnames)) {
                           $prod->name .= (" ". implode(",", $tnames));
                    }
                    if($is_for_export) {
                        $prod->{'pattribute_'.$att->id} =  implode(",", $tnames);
                    } else {    
                        $prod->{'pattribute_'.$att->id} = $tids;
                    }
                
                }else{
                    if($is_for_export) {
                        $prod->{'pattribute_'.$att->id} = null;//NO APPLACABLE
                    }else{
                        $prod->{'pattribute_'.$att->id} = wp_get_object_terms($prod->parent, 'pa_'. $att->name, array('fields' => 'ids'));//INHERITED
                    }
                }
                if(pelm_fn_show_filed("attribute_show")) {
                    $prod->{'pattribute_'.$att->id."_visible"} = null;
                }
            }else{
                if($is_for_export) {
                    if($inf->v) {
                             $prod->{'pattribute_'.$att->id} = null;
                    }else{
                           $prod->{'pattribute_'.$att->id} = implode(", ", wp_get_object_terms($id, 'pa_'. $att->name, array('fields' => 'names')));
                    }
                    if(pelm_fn_show_filed("attribute_show")) {
                        $prod->{'pattribute_'.$att->id."_visible"} = $inf->s ? "yes" : "no";
                    }
                }else{
                    $prod->{'pattribute_'.$att->id} = wp_get_object_terms($id, 'pa_'. $att->name, array('fields' => 'ids'));
                    if(pelm_fn_show_filed("attribute_show")) {
                        $prod->{'pattribute_'.$att->id."_visible"} = $inf->s;
                    }
                }
            }
        }
      
        if(!$is_for_export) {
            $prod->att_info = $att_info;
        }
      
        if(pelm_fn_show_filed('name')) {
            $prod->name .= $name_suffix;
        }
     
        foreach($custom_fileds as $cfname => $cfield){ 
            if($cfield->type == "term") {
                if($is_for_export) {
                    $prod->{$cfname} = implode(", ", wp_get_object_terms($id, $cfield->source, array('fields' => 'names')));
                } else{
                    if($prod->parent) {
                        $prod->{$cfname} = wp_get_object_terms($prod->parent, $cfield->source, array('fields' => 'ids'));
                    } else {
                        $prod->{$cfname} = wp_get_object_terms($id, $cfield->source, array('fields' => 'ids'));
                    }
                }    
                
            }elseif($cfield->type == "meta") {
                $prod->{$cfname} = pelm_fn_get_meta_by_path($id, $cfield->source);
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
            if($is_for_export) {
                $prod->tags_names         = null;
                if($ptrems) {
                    foreach((array)$ptrems as $pt){
                        if(!isset($prod->tags_names)) { 
                            $prod->tags_names = array();
                        }
                        
                        $prod->tags_names[] = $pt->name;
                    }
                    $prod->tags_names = implode(", ", $prod->tags_names);
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
      
        if(pelm_fn_show_filed('weight')) {
            $prod->weight       = get_post_meta($id, '_weight', true);
        }
        if(pelm_fn_show_filed('length')) {
            $prod->length       = get_post_meta($id, '_length', true);
        }
        if(pelm_fn_show_filed('width')) {
            $prod->width        = get_post_meta($id, '_width', true);
        }
        if(pelm_fn_show_filed('height')) {
            $prod->height       = get_post_meta($id, '_height', true);
        }
      
        if(pelm_fn_show_filed('featured')) {
            if($is_for_export) {
                $prod->featured     = get_post_meta($id, '_featured', true);
            } else{
                $prod->featured     = get_post_meta($prod->parent ? $prod->parent : $id, '_featured', true) == "yes" ? true : false;
            }
        }
      
        if(pelm_fn_show_filed('tax_status')) {
            $prod->tax_status   = get_post_meta(($prod->parent && !$is_for_export) ? $prod->parent : $id, '_tax_status', true);
        }
        if(pelm_fn_show_filed('tax_class')) {
            $prod->tax_class    = get_post_meta(($prod->parent && !$is_for_export) ? $prod->parent : $id, '_tax_class', true);
        }
        if(pelm_fn_show_filed('backorders')) {
            $prod->backorders   = get_post_meta(($prod->parent && !$is_for_export) ? $prod->parent : $id, '_backorders', true);
        }
    
        if(pelm_fn_show_filed('image')) {    
            $prod->image = null;
          
            if(has_post_thumbnail($id)) {
                $thumb_id    = get_post_thumbnail_id($id);
                if($is_for_export) {
                    if(!$prod->parent) {
                        $prod->image = wp_get_attachment_image_src($thumb_id, 'full');
                        if(is_array($prod->image)) {
                                $prod->image = $prod->image[0];
                        }
                    }
                }else{
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
                }
            }
        }
      
        if(pelm_fn_show_filed('gallery')) {    
            $prod->gallery = null;
          
            if(!($is_for_export && $prod->parent)) {
                $gallery = get_post_meta($prod->parent ? $prod->parent : $id, "_product_image_gallery", true);
                if($gallery) {
                    $prod->gallery = array();
                    foreach(explode(",", $gallery) as $ind => $img_id){
                        if($is_for_export) {
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
                  
                    if($is_for_export) {
                        $prod->gallery = implode(",", $prod->gallery);
                    }
                }
            }
        }
      
        if(!pelm_fn_show_filed('parent') && $is_for_export) {
            unset($prod->parent);
        }
      
        if($op == "json") {
            if($p_n > 0) echo ",";
            echo json_encode($prod,0,5);
			
            global $start_time,$max_time,$mem_limit,$res_limit_interupted;
        
            if($p_n > 0 && $start_time + $max_time  < time() || pelm_get_mem_allocated() + 1048576 > $mem_limit) {
                $res_limit_interupted = $p_n + 1;
                break;    
            }
        }elseif($op == "export") {
         
            if($p_n == 0 && $resume_skip == 0) {    
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
                      $eprod[] = &$prod->$prop;
                }
                fputcsv($df, $eprod, $impexp_settings->delimiter2);
            }else {
                fputcsv($df, (array)$prod, $impexp_settings->delimiter2);
            }
        
            global $start_time,$max_time,$mem_limit,$res_limit_interupted, $in_export_chunk;
         
            if(!isset($in_export_chunk)) {
                $in_export_chunk = 1;
            } else {
                $in_export_chunk++;
            }
         
            if($in_export_chunk == 300 || $p_n > $resume_skip && $start_time + $max_time  < time() || pelm_get_mem_allocated() + 1048576 > $mem_limit) {
                $res_limit_interupted = $p_n + 1;
                break;    
            }
        
        }elseif($op == "data") {
            return $prod;
        }
        $p_n++;
      
        unset($prod);
      
    }
};


$is_for_export = intval(pelm_read_sanitized_request_parm("do_export", false));


if($is_for_export) {
    
    $export_uid = pelm_read_sanitized_request_parm("export_uid", uniqid("plem_price_export_"));
    $chunk_n    = get_option($export_uid ."_chunk", 0);
    $chunk_n++;
    
    $df = fopen("php://temp", 'w+');
    ///////////////////////////////////////////////////
    pelm_product_render($IDS, $attributes, "export", $df);
    ///////////////////////////////////////////////////
    rewind($df);
    $contents = '';
    while (!feof($df)) {
        $contents .= fread($df, 8192 * 4);
    }
    
    update_option($export_uid ."_chunk", $chunk_n, false);
    update_option($export_uid . "_" . $chunk_n, $contents, false);
    
    fclose($df);
    
    if($res_limit_interupted == 0) {
        
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
        $csv_df = fopen("php://output", 'w');
        $chunk_n = 1;
        $contents = get_option($export_uid . "_" . $chunk_n, false);
        while($contents !== false){
            fwrite($csv_df, $contents);
            $chunk_n++;
            $contents = get_option($export_uid . "_" . $chunk_n, false);
        }
        fclose($csv_df);
        
        delete_option($export_uid ."_chunk");
        $chunk_n = 1;
        while(delete_option($export_uid . "_" . $chunk_n)) {
            $chunk_n++;
        }
        
    }else{
        $resume_skip += $res_limit_interupted;
        ?>
            
            <div class="scope_body">
                
                <style type="text/css">
                    html, body{
                        background:#505050;
                        color:white;
                        font-family:sans-serif;    
                    }
                </style>
                
                <div class="scope_body_content">
                    <form method="POST" id="continueExportForm">
                        <input name="pelm_security" type="hidden" value="<?php echo esc_attr(pelm_get_nonce("pelm_nonce")); ?>" />
                        <h2><?php echo esc_html__("Preparing export...", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></h2>
                        <p>(<?php echo esc_attr($resume_skip);?>) <?php echo esc_html__("products/product variations entries processed", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></p>
                        <hr/>
                        <p><?php echo esc_html__("You can close this browser tab once you receive CSV file", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></p>
                        <input type="hidden" name="resume_skip" value="<?php echo esc_attr($resume_skip);?>">
                        <input type="hidden" name="export_uid" value="<?php echo esc_attr($export_uid);?>">
                        <input type="hidden" name="do_export" value="1">
        <?php if((pelm_read_sanitized_request_parm("sortOrder"))) {?>
                            <input type="hidden" name="sortOrder" value="<?php echo esc_attr($orderby);?>">
        <?php } 
        if((pelm_read_sanitized_request_parm("sortColumn"))) {?>
                            <input type="hidden" name="sortColumn" value="<?php echo esc_attr($sort_order);?>">
        <?php } 
        if((pelm_read_sanitized_request_parm("page_no"))) {?>
                            <input type="hidden" name="page_no" value="<?php echo pelm_esc_sanitized_request_parm("page_no");?>">
        <?php }
        if((pelm_read_sanitized_request_parm("limit"))) {?>
                            <input type="hidden" name="limit" value="<?php echo pelm_esc_sanitized_request_parm("limit");?>">
        <?php } 
        if((pelm_read_sanitized_request_parm("sku"))) {?>
                            <input type="hidden" name="sku" value="<?php echo pelm_esc_sanitized_request_parm("sku");?>">
        <?php } 
        if((pelm_read_sanitized_request_parm("product_name"))) {?>
                            <input type="hidden" name="product_name" value="<?php echo pelm_esc_sanitized_request_parm("product_name");?>">
        <?php }
        if((pelm_read_sanitized_request_parm("product_category"))) {?>
                            <input type="hidden" name="product_category" value="<?php echo pelm_esc_sanitized_request_parm("product_category");?>">
        <?php }                            
        if((pelm_read_sanitized_request_parm("product_shipingclass"))) {?>
                            <input type="hidden" name="product_shipingclass" value="<?php echo pelm_esc_sanitized_request_parm("product_shipingclass");?>">
        <?php } 
        if((pelm_read_sanitized_request_parm("product_tag"))) {?>
                            <input type="hidden" name="product_tag" value="<?php echo pelm_esc_sanitized_request_parm("product_tag");?>">
        <?php } 
        if((pelm_read_sanitized_request_parm("product_status"))) {?>
                            <input type="hidden" name="product_status" value="<?php echo pelm_esc_sanitized_request_parm("product_status");?>">
        <?php } ?>
                        
         <?php foreach($attributes as $attr){ 
                if(pelm_read_sanitized_request_parm("pattribute_" . $attr->id, null)) {
                    ?>
                                    <input type="hidden" name="pattribute_<?php echo esc_attr($attr->id);?>" value="<?php echo pelm_esc_sanitized_request_parm("pattribute_" . $attr->id, null);?>">
                    <?php	
                }
                                
         } ?>
                    </form>
                    <script type="text/javascript">
                            document.getElementById("continueExportForm").submit();
                    </script>
                </div>
            </div>
        <?php
    }
    die();
    exit;  
    return;
}

?>

<div class="scope_body">

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo esc_attr(get_option('blog_charset')); ?>" />
<script type="text/javascript">

var _wpColorScheme = {"icons":{"base":"#999","focus":"#2ea2cc","current":"#fff"}};
var pelm_ajaxurl = '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';
var pelm_plugin_version = '<?php echo esc_attr($plem_price_settings['plugin_version']); ?>';
var pelm_localStorage_clear_flag = false;

function pelm_clean_layout(){
    localStorage.clear();
    pelm_localStorage_clear_flag = true;
    pelm_do_load();
    return false;
}

if(<?php if( pelm_fn_show_filed("attribute_show") ) echo "'1'" ; else echo "'0'"; ?> != localStorage["dg_wooc_attribute_show_visible"]){
    localStorage.clear();
}

localStorage["dg_wooc_attribute_show_visible"] = <?php if( pelm_fn_show_filed("attribute_show")) echo "'1'"; else echo "'0'"; ?>;

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
<script type='text/javascript'>
function pelm_get_request_url(){
    if(window.pelm_ajaxurl)
        return pelm_ajaxurl + "?action=pelm_price_frame_display&elpm_shop_com=<?php echo esc_attr($elpm_shop_com); ?>";
    else{
        return window.location.href.split("?")[0] + + "?action=pelm_price_frame_display&elpm_shop_com=<?php echo esc_attr($elpm_shop_com); ?>";
    }
}

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

</script>

<div class="scope_body_content pelm_body">
<a class="pro-upgrade blink" target="_blank" style="position: absolute;right: 10px;top: 5px;color: cyan;font-size: 12px;" href="https://holest.com/bulk-product-manager-for-woo-commerce"><?php echo esc_html__("To edit/import ALL get PRO version &gt; &gt;", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?> </a>
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
  <li><span class="copy"><button id="cmdCopy" onclick="pelm_copy();" ><?php echo esc_html__("Clone", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></button></span></li>
  <li><span class="delete"><button id="cmdDelete" onclick="pelm_deleteproducts();" ><?php echo esc_html__("Delete", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></button></span></li>
  <li>
   <span><span> <?php echo esc_html__("Export/Import", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?> &#9655;</span></span>
   <ul>
     <li><span><button onclick="pelm_do_export();return false;" ><?php echo esc_html__("Export CSV", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></button></span></li>
     <li><span><button onclick="pelm_do_import();return false;" ><?php echo esc_html__("Update from CSV", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></button></span></li>
     <li><span><button onclick="pelm_showSettings();return false;" ><?php echo esc_html__("Custom import/export settings", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></button></span></li>
   </ul>
  </li>
  <li>
   <span><span> <?php echo esc_html__("Options", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?> &#9655;</span></span>
   <ul>
   
     <li><span><button onclick="if(window.self !== window.top) window.parent.location = 'admin.php?page=excellikepricechangeforwoocommerceandwpecommercelight-settings';  else window.location = 'admin.php?page=excellikepricechangeforwoocommerceandwpecommercelight-settings';" > <?php echo esc_html__("Settings", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?> </button></span></li>
     <li><span><button onclick="pelm_clean_layout();return false;" ><?php echo esc_html__("Clean layout cache...", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></button></span></li>
     <li><span><a target="_blank" href="<?php echo "http://www.holest.com/excel-like-product-manager-woocommerce-documentation"; ?>" > <?php echo esc_html__("Help", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?> </a></span></li>
   </ul>
  </li>
  
  <!--
  <li style="font-weight: bold;">
   <span><a style="color: cyan;font-size: 16px;" href="http://holest.com/index.php/holest-outsourcing/joomla-wordpress/virtuemart-excel-like-product-manager.html">Buy this component!</a></span> 
  </li>
  -->
  
  <li style="width:200px;">
  <input style="width:130px;display:inline-block;" type="text" id="activeFind" placeholder="<?php echo esc_html__("active data search...", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?>" />
  <span style="display:inline-block;" id="search_matches"></span>
  <button id="cmdActiveFind" >&#9655;&#9655;</button> 
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
    
     <input id="cmdRefresh" type="submit" class="cmd" value="<?php echo esc_html__("Refresh", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?>" onclick="pelm_do_load();" />
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
            echo '<option value="'.esc_attr($category->category_id).'" >'. esc_attr($category->treename).'</option>';
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
     <label><?php echo esc_html__("Shipping class", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?></label>
     <select data-placeholder="<?php echo esc_html__("Chose shipping classes...", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?>" class="inputbox" multiple name="product_shipingclass" >
        <option value=""></option>
        <?php
        foreach($shipping_classes as $shipping_class){
            $par_ind = '';
            if($shipping_class->parent) {
                $par = $shippclass_asoc[$shipping_class->parent];
                while($par){
                    $par_ind.= ' - ';
                    $par = $shippclass_asoc[$par->parent];
                }
            }
            echo '<option value="'.esc_attr($shipping_class->id).'" >'.esc_attr($par_ind.$shipping_class->name).'</option>';
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
                <option value="<?php echo esc_attr($val); ?>"><?php echo esc_html__($title, 'woocommerce');?></option>
            <?php
        }

        ?>
     </select>
  </div>
  
  <?php
    foreach($attributes as $att){
        ?>    
   <div class="filter_option">
     <label><?php echo esc_html__($att->label);?></label>
     <select data-placeholder="<?php echo esc_html__("Chose values...", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?>" class="inputbox attribute-filter" multiple name="pattribute_<?php echo esc_attr($att->id); ?>" >
        <option value=""></option>
        <?php
        foreach($att->values as $val){
            echo '<option value="'.esc_attr($val->id).'" >'.esc_attr($val->name).'</option>';
        }
        ?>
     </select>
    </div>    
    
    
        <?php     
    }
    ?>
  
  


  <div class="filter_option mass-update">
      <label><?php echo esc_html__("Mass update by filter criteria: ", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></label> 
      <input style="width:140px;float:left;" placeholder="<?php echo sprintf(__("[+/-]X%s or [+/-]X", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'), '%'); ?>" type="text" id="txtMassUpdate" value="" /> 
      <button id="cmdMassUpdate" class="cmd" onclick="pelm_mass_update(false);return false;" style="float:right;"><?php echo esc_html__("Mass update price", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></button>
      <button id="cmdMassUpdateOverride" class="cmd" onclick="pelm_mass_update(true);return false;" style="float:right;"><?php echo esc_html__("Mass update sales price", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></button>
      
  </div>
  
  <div style="clear:both;" class="filter-panel-spacer-bottom" ></div>
  
</div>
</div>

<div id="dg_wooc" class="hst_dg_view fixed-<?php echo esc_attr($plem_price_settings['fixedColumns']); ?>" style="margin-left:-1px;margin-top:0px;overflow: scroll;background:#FBFBFB;">
</div>

</div>
<div class="footer">
 <div class="pagination">
   <label for="txtLimit" ><?php echo esc_html__("Limit:", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?></label><input id="txtlimit" class="save-state" style="width:40px;text-align:center;" value="<?php echo esc_attr($limit);?>" plem="<?php 
	$arr = array_keys($plem_price_settings);
	sort($arr);
	echo esc_attr($plem_price_settings[reset($arr)]); 
   ?>" />
   <?php
    if($limit && ceil($count / $limit) > 1) {
        ?>
           <input type="hidden" id="paging_page" value="<?php echo esc_attr($page_no); ?>" />    
           
        <?php
        if($page_no > 1) {
            ?>
           <span class="page_number" onclick="pelm_set_page(this,1);return false;" ><<</span>
           <span class="page_number" onclick="pelm_set_page(this,'<?php echo esc_attr($page_no - 1); ?>');return false;" ><</span>
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
              <span class="page_number <?php echo esc_attr(($i + 1) == $page_no ? " active " : "");  ?>" onclick="pelm_set_page(this,'<?php echo esc_attr($i + 1); ?>');return false;" ><?php echo esc_attr($i + 1); ?></span>
            <?php	        
        }
          
        if($page_no < ceil($count / $limit)) {
            ?>
           <span class="page_number" onclick="pelm_set_page(this,'<?php echo esc_attr($page_no + 1); ?>');return false;" >></span>
           <span class="page_number" onclick="pelm_set_page(this,'<?php echo esc_attr(ceil($count / $limit)); ?>');return false;" >>></span>
            <?php
        }
          
    }
    ?>
   <span class="pageination_info"><?php echo sprintf(__("Page %s of %s, total %s products by filter criteria", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'), esc_attr($page_no), esc_attr(ceil($count / $limit)), "<span id='rcount'>" . esc_attr($count) . '</span>'); ?> </span>
   
 </div>
 
 <span class="note" style="float:right;"><?php echo esc_html__("*All changes are instantly autosaved", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?></span>
 <span class="wait save_in_progress" ></span>
 
</div>


<form id="operationFRM" method="POST" >
<input name="pelm_security" type="hidden" value="<?php echo esc_attr(pelm_get_nonce("pelm_nonce")); ?>" />
<div>
</div>
</form>

<script type="text/javascript">
var imagePicker = null;
var galleryPicker = null;
var upload_dir_data = <?php echo json_encode(wp_upload_dir()) ?>;

var DG                = null;
var tasks             = {};
var variations_fields = <?php echo (json_encode($variations_fields)); ?>;
var categories           = <?php echo (json_encode($categories));?>;
var tags                 = <?php echo (json_encode($tags));?>;
var shipping_calsses  = <?php echo (json_encode($shipping_classes));?>;

var asoc_cats = {};
var asoc_tags = {};
var asoc_shipping_calsses = {};

var tax_classes   = <?php 
    $tca = array(); 
	foreach($tax_classes as $tc => $label){
		$x = new stdClass(); 
		$x->value = esc_attr($tc); 
		$x->name  = esc_attr($label); 
		$tca[] = $x;
	}; 
    echo json_encode($tca);
	?>;

var tax_statuses  = <?php 
    $tsa = array(); 
	foreach($tax_statuses as $ts => $label){
		$x = new stdClass(); 
		$x->value = esc_attr($ts); 
		$x->name  = esc_attr($label); 
		$tsa[]    = $x;
	}; 
    echo json_encode($tsa);
?>;

var product_types = <?php echo (json_encode($product_types));?>;

var asoc_tax_classes   = {};
var asoc_tax_statuses  = {};
var asoc_product_types = {};

var ContentEditorCurrentlyEditing = {};
var ImageEditorCurrentlyEditing   = {};

var ProductPreviewBox         = jQuery("#product-preview");
var ProductPreviewBox_title = jQuery("#product-preview p");

var SUROGATES  = {};
var multidel   = false;

var sortedBy     = 0;
var sortedOrd    = true;
var explicitSort = false;
var jumpIndex    = 0;
var page_col_w   = {};

if(localStorage['dg_wooc_page_col_w'])
    page_col_w = JSON.parse(localStorage['dg_wooc_page_col_w']);
 

<?php
foreach($attributes as $att){
    $values      = array();
    $values_asoc = array();
    foreach($att->values as $val){
        $v = new stdClass();
        $v->value = $val->id;
        $v->name  = $val->name;
        $values[]   = $v;
        $values_asoc[$val->id] = $val->name;
    }

    ?>
var attribute_<?php echo esc_attr($att->id); ?> = <?php echo (json_encode($values));?>;
var asoc_attribute_<?php echo esc_attr($att->id); ?> = <?php echo (json_encode($values_asoc));?>;
    <?php
}
?>
window.onbeforeunload = function() {
    try{
        localStorage['dg_wooc_page_col_w'] = JSON.stringify(page_col_w);
        pelmStoreState();
    }catch(e){}
    
    var n = 0;
    for(var key in tasks)
        n++;
     
    if(n > 0){
      pelm_do_save();
      return "<?php echo esc_html__("Transactions ongoing. Please wait a bit more for them to complete!", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?>";
    }else
      return;       
}

for(var c in categories){
  asoc_cats[categories[c].category_id] = categories[c].category_name;
}

for(var t in tags){
  asoc_tags[tags[t].id] = tags[t].name;
}

for(var s in shipping_calsses){
  asoc_shipping_calsses[shipping_calsses[s].id] = shipping_calsses[s].name;
}

for(var i in tax_classes){
  asoc_tax_classes[tax_classes[i].value] = tax_classes[i].name;
}

for(var i in tax_statuses){
  asoc_tax_statuses[tax_statuses[i].value] = tax_statuses[i].name;
}

for(var i in product_types){
  asoc_product_types[product_types[i].id] = product_types[i].name;
}

$ = jQuery;
var keepAliveTimeoutHande = null;
var resizeTimeout
  , availableWidth
  , availableHeight
  , $window = jQuery(window)
  , $dg     = jQuery('#dg_wooc');

var pelm_calculate_size = function () {
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

$window.on('resize', pelm_calculate_size);

pelm_calculate_size();

jQuery(document).ready(function(){pelm_calculate_size();});
jQuery(window).load(function(){pelm_calculate_size();});  

function pelm_set_page(sender,page){
    jQuery('#paging_page').val(page);
    jQuery('.page_number').removeClass('active');
    jQuery(sender).addClass('active');
    pelm_do_load();
    return false;
}

var pending_load = 0;

function pelm_get_sort_property(){
    if(!DG)    
        DG = jQuery('#dg_wooc').data('handsontable');
    
    if(!DG)
        return "id";
    
    return DG.colToProp( DG.sortColumn);
}

function pelm_do_load(withImportSettingsSave){
    pending_load++;
    if(pending_load < 6){
        var n = 0;
        for(var key in tasks)
            n++;
            
        if(n > 0) {
          setTimeout(function(){
            pelm_do_load();
          },2000);
          return;
        }
    }

    var POST_DATA = {};
    
    POST_DATA.sortOrder            = DG.sortOrder ? "ASC" : "DESC";
    POST_DATA.sortColumn           = pelm_get_sort_property();
    POST_DATA.limit                = jQuery('#txtlimit').val();
    POST_DATA.page_no              = jQuery('#paging_page').val();
    
     POST_DATA.sku                  = jQuery('.filter_option *[name="sku"]').val();
    POST_DATA.product_name         = jQuery('.filter_option *[name="product_name"]').val();
    POST_DATA.product_shipingclass = jQuery('.filter_option *[name="product_shipingclass"]').val();
    POST_DATA.product_category     = jQuery('.filter_option *[name="product_category"]').val();
    POST_DATA.product_tag          = jQuery('.filter_option *[name="product_tag"]').val();
    POST_DATA.product_status       = jQuery('.filter_option *[name="product_status"]').val();
<?php foreach($attributes as $attr){ ?>
    POST_DATA.pattribute_<?php echo esc_attr($attr->id);?> = jQuery('.filter_option *[name="pattribute_<?php echo esc_attr($attr->id);?>"]').val();
<?php } ?>    
    
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

function pelm_mass_update(update_override){
    if(!jQuery.trim(jQuery('#txtMassUpdate').val())){
      alert("<?php echo esc_html__("Enter value first!", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?>");
      return;
    } 

    if(confirm("<?php echo esc_html__("Update product price for all products matched by filter criteria (this operation can not be undone)?", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?>")){
        var POST_DATA = {};
        
        POST_DATA.mass_update_val        = parseFloat(jQuery('#txtMassUpdate').val()); 
        POST_DATA.mass_update_percentage = (jQuery('#txtMassUpdate').val().indexOf("%") >= 0) ? 1 : 0;
        POST_DATA.mass_update_override   = update_override ? '1' : '0';
        
        POST_DATA.sortOrder            = DG.sortOrder ? "ASC" : "DESC";
        POST_DATA.sortColumn           = pelm_get_sort_property();
        POST_DATA.limit                = jQuery('#txtlimit').val();
        POST_DATA.page_no               = jQuery('#paging_page').val();
        
        POST_DATA.sku                  = jQuery('.filter_option *[name="sku"]').val();
        POST_DATA.product_name         = jQuery('.filter_option *[name="product_name"]').val();
        POST_DATA.product_shipingclass = jQuery('.filter_option *[name="product_shipingclass"]').val();
        POST_DATA.product_category     = jQuery('.filter_option *[name="product_category"]').val();
        POST_DATA.product_tag          = jQuery('.filter_option *[name="product_tag"]').val();
        POST_DATA.product_status       = jQuery('.filter_option *[name="product_status"]').val();
<?php foreach($attributes as $attr){ ?>
        POST_DATA.pattribute_<?php echo esc_attr($attr->id);?> = jQuery('.filter_option *[name="pattribute_<?php echo esc_attr($attr->id);?>"]').val();
<?php } ?>    
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

function pelm_build_id_index_directory(rebuild){
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

function pelm_do_save(callback, error_callback){
    var update_data = JSON.stringify(tasks);        
    save_in_progress = true;
    jQuery(".save_in_progress").show();

    jQuery.ajax({
    url: pelm_get_request_url() + "&DO_UPDATE=1&diff=" + Math.random() + "&pelm_security=<?php echo esc_attr(pelm_get_nonce("pelm_nonce")); ?>",
    type: "POST",
    dataType: "json",
    data: update_data,
    success: function (data) {

        pelm_build_id_index_directory();
        var rebuild_indexes = false;
        var re_sort         = false;
        
        var updated = JSON.parse(update_data);
        
        if(data){
            for(var j = 0; j < data.length ; j++){
                
                
                
                if(data[j].dependant_updates){
                    for(var p_id in data[j].dependant_updates){
                        try{
                            if (data[j].dependant_updates.hasOwnProperty(p_id)) {
                                var row_ind = id_index[p_id].ind;
                                for( var prop in data[j].dependant_updates[p_id]){
                                    try{
                                        if (data[j].dependant_updates[p_id].hasOwnProperty(prop)) {
                                            DG.getData()[row_ind][prop] = data[j].dependant_updates[p_id][prop];
                                            if(prop == "att_info"){
                                                if(!DG.getData()[row_ind]["parent"]){
                                                    tasks[key].att_info   = data[j].dependant_updates[p_id][prop];
                                                    updated[key].att_info = data[j].dependant_updates[p_id][prop];
                                                }
                                            }
                                        }
                                    }catch(ex1){}
                                }
                            }
                        }catch(ex1){}
                    }
                }
                
                if(data[j].pnew || data[j].clones){
                    var ind = data[j].pnew ? (parseInt(variant_dialog.attr("ref_dg_index")) + 1) : ( DG.countRows() - (DG.getSettings().minSpareRows || 0) );
                    if(data[j].pnew){
                        while(DG.getDataAtRowProp(ind,'parent'))
                            ind++;
                    }
                    var insert_data = data[j].pnew ? data[j].pnew : data[j].clones;
                    for(var p_id in insert_data){
                        try{
                            DG.alter("insert_row",ind);
                            if (insert_data.hasOwnProperty(p_id)) {
                                
                                for( var prop in insert_data[p_id]){
                                    try{
                                        if (insert_data[p_id].hasOwnProperty(prop)) {
                                            DG.getSourceDataAtRow(ind)[prop] = insert_data[p_id][prop];
                                        }
                                    }catch(ex1){}
                                }
                            }
                        }catch(ex1){}
                        ind++;
                        rebuild_indexes = true;
                    }
                    
                    if(data[j].clones)
                        re_sort = true;
                }
                
                if(data[j].surogate){
                    var row_ind = SUROGATES[data[j].surogate];
                    for(var prop in data[j].full){
                        try{
                            if (data[j].full.hasOwnProperty(prop)) {
                                DG.getSourceDataAtRow(row_ind)[prop] = data[j].full[prop];
                            }
                        }catch(e){}
                    }
                    
                    if(data[j].full.id){
                        if(id_index[data[j].full.id])
                            id_index[data[j].full.id].ind = row_ind;
                        else
                            id_index[data[j].full.id] = {ind:row_ind,ch:[]}; 
                    }
                    
                }else if(data[j].full){
                    var row_ind = id_index[data[j].id].ind;
                    for(var prop in data[j].full){
                        try{
                            if (data[j].full.hasOwnProperty(prop)) {
                                DG.getData()[row_ind][prop] = data[j].full[prop];
                            }
                        }catch(e){}
                    }
                }
            }
        }
        
        if(rebuild_indexes)
            pelm_build_id_index_directory(true);

        if(re_sort){
            explicitSort = true;
            DG.sort( DG.sortColumn , DG.sortOrder);
            explicitSort = false;
        }
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
                                if(prop == "DO_CLONE" || prop == "DO_DELETE" || prop == "dg_index" || prop == "surogate" || prop == "variate_by"  || prop == "variate_count")
                                    continue;
                                try{
                                    if(tasks[key].hasOwnProperty(prop)){
                                        if(jQuery.inArray(prop, variations_fields) == -1 || prop.indexOf("pattribute_") == 0 || prop.indexOf("att_info") == 0){
                                           for(ch in inf.ch){
                                              if(prop == 'name'){
                                                var old = DG.getData()[inf.ch[ch]][prop];
                                                DG.getData()[inf.ch[ch]][prop] = tasks[key][prop] + ' ' + old.substr(old.indexOf('('));
                                              }else if(prop.indexOf("pattribute_") == 0){
                                                var is_var = false;
                                                try{
                                                    is_var = DG.getData()[inf.ch[ch]]["att_info"][prop.substr(11)].v;
                                                }catch(vex){}    
                                                if(!is_var){
                                                    DG.getData()[inf.ch[ch]][prop] = tasks[key][prop];
                                                } 
                                              }else
                                                DG.getData()[inf.ch[ch]][prop] = tasks[key][prop];
                                           }
                                        }
                                    }
                                }catch(exi){}
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

        if(callback){
            try{
                callback(data);
            }catch(ex){}
        }
        DG.render();
        
        jQuery("#rcount").html(DG.countRows() - 1);
            
    },
    error: function(a,b,c){

        save_in_progress = false;
        jQuery(".save_in_progress").hide();
        
        if(error_callback){
            try{
                tasks = {};
                error_callback();
            }catch(ex){}
        }else
            pelm_call_save();
        
    }
    });
}

function pelm_call_save(){
    
    var has_tasks = false;
    for (var property in tasks) {
        if (tasks.hasOwnProperty(property)) {
            has_tasks = true;    
            break;
        }
    }
    
    if(!has_tasks)
        return;
    
    if(saveHandle){
       clearTimeout(saveHandle);
       saveHandle = null;
    }
    
    saveHandle = setTimeout(function(){
       saveHandle = null;
       
       if(save_in_progress){
           setTimeout(function(){
            pelm_call_save();
           },2000);
           return;
       }
       pelm_do_save();
    },2000);
}

function pelm_undo(){
    if(DG)
        DG.undo();
}

function pelm_redo(){
    if(DG)
        DG.redo();
}

function pelm_get_pro(){
    var url = '//holest.com/bulk-product-manager-for-woo-commerce';
    
    if(window.self !== window.top) 
        window.parent.location = url;  
    else 
        window.location = url;
    
    return false;
}

function pelm_copy(){
    return pelm_get_pro();
}

function pelm_deleteproducts(){
    return pelm_get_pro();
}

var strip_helper = document.createElement("DIV");
function pelm_strip(html){
   strip_helper.innerHTML = html;
   return strip_helper.textContent || strip_helper.innerText || "";
}

jQuery(document).ready(function(){

    let CustomSelectEditor = Handsontable.editors.BaseEditor.prototype.extend();
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
        
        let options = this.cellProperties.selectOptions || [];

        let optionElements = options.map(function(option){
            let optionElement = jQuery('<option />');
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
        
        
        let widg = this.select.next();
        let self = this;
        
        let create = false;
        
        let multiple = this.cellProperties.select_multiple;
        if(typeof multiple === "function"){
            multiple = !!multiple(this.instance,this.row, this.prop);
        }else if(!multiple)
            multiple = false;
        
        let create_option = this.cellProperties.allow_random_input;
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
                    jQuery('#dg_wooc').handsontable("selectCell", self.row , self.col);                    
               });
            }else if(!this.select.attr("multiple")){
                this.select.attr('multiple','multiple');
            }
            let chos;
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
                  let src_inp = jQuery(this).find('LI.search-field > INPUT[type="text"]:first');
                  if(src_inp[0])
                    if(src_inp.val() == ''){
                       //event.stopImmediatePropagation();
                       //event.preventDefault();
                       self.discardEditor();
                       self.finishEditing();
                       //self.focus();
                       //self.close();
                       jQuery('#dg_wooc').handsontable("selectCell", self.row + 1, self.col);
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
        
        let widg = this.select.next();
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
    
    let clonableARROW = document.createElement('DIV');
    clonableARROW.className = 'htAutocompleteArrow';
    clonableARROW.appendChild(document.createTextNode('\u25BC'));
    
    let clonableEDIT = document.createElement('DIV');
    clonableEDIT.className = 'htAutocompleteArrow';
    clonableEDIT.appendChild(document.createTextNode('\u270E'));
    
    let clonableIMAGE = document.createElement('DIV');
    clonableIMAGE.className = 'htAutocompleteArrow';
    clonableIMAGE.appendChild(document.createTextNode('\u27A8'));
        
    let CustomSelectRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        try{
          
           // var WRAPPER = clonableWRAPPER.cloneNode(true); //this is faster than createElement
            let ARROW = clonableARROW.cloneNode(true); //this is faster than createElement

            Handsontable.renderers.TextRenderer(instance, td, row, col, prop, value, cellProperties);
            
            let fc = td.firstChild;
            while(fc) {
                td.removeChild( fc );
                fc = td.firstChild;
            }
            
            td.appendChild(ARROW); 
            
            if(value){
                
                if(cellProperties.select_multiple){ 
                    let rval = value;
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
              let eventManager = Handsontable.eventManager(instance);

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
              let eventManager = Handsontable.eventManager(instance);    
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
    /////////////////////////////////////////////////////////////////////////////////////////
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
    
    let customImageEditor = Handsontable.editors.BaseEditor.prototype.extend();
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
                    let selection = galleryPicker.state().get('selection');
                    
                    let gval = new Array();
                    
                    selection.each(function(attachment) {
                        
                        let val = {};
                        val.id    = attachment.attributes.id;
                        val.src   = attachment.attributes.url;
                        val.thumb = attachment.attributes.sizes.thumbnail.url;
                        
                        gval.push(val);
                        
                    });
                    
                    DG.setDataAtRowProp(ImageEditorCurrentlyEditing.row, ImageEditorCurrentlyEditing.prop, gval, "" );
                    DG.selectCell(ImageEditorCurrentlyEditing.row,ImageEditorCurrentlyEditing.col);
                });
                
                galleryPicker.on('open',function() {
                    let selection = galleryPicker.state().get('selection');

                    //remove all the selection first
                    selection.each(function(image) {
                        let attachment = wp.media.attachment( image.attributes.id );
                        attachment.fetch();
                        selection.remove( attachment ? [ attachment ] : [] );
                    });

                    if(galleryPicker.current_value){
                        for(let i = 0; i < galleryPicker.current_value.length; i++){
                            if(galleryPicker.current_value[i].id){
                                let att = wp.media.attachment( galleryPicker.current_value[i].id );
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
                
                let newTitle = jQuery("<h1>" + 'Product Images (#' + DG.getDataAtRowProp(this.row,'sku') + ' ' + DG.getDataAtRowProp(this.row,'name') + ')' + "</h1>");
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
                    let selection = imagePicker.state().get('selection');
                    selection.each(function(attachment) {
                        //console.log(attachment);
                        
                        let val = ImageEditorCurrentlyEditing.value;
                        if(!val) val = {};
                        val.id    = attachment.attributes.id;
                        val.src   = attachment.attributes.url;
                        val.thumb = attachment.attributes.sizes.thumbnail.url;
                        DG.setDataAtRowProp(ImageEditorCurrentlyEditing.row, ImageEditorCurrentlyEditing.prop, val, "" );
                        DG.selectCell(ImageEditorCurrentlyEditing.row,ImageEditorCurrentlyEditing.col);
                    });
                });
                
                imagePicker.on('open',function() {
                    let selection = imagePicker.state().get('selection');

                    //remove all the selection first
                    selection.each(function(image) {
                        let attachment = wp.media.attachment( image.attributes.id );
                        attachment.fetch();
                        selection.remove( attachment ? [ attachment ] : [] );
                    });

                    if(imagePicker.current_value){
                        if(imagePicker.current_value.id){
                            let att = wp.media.attachment( imagePicker.current_value.id );
                            att.fetch();
                            selection.add( att ? [ att ] : [] );
                        }
                    }
                });
                
                imagePicker.on('close',function() {
                    DG.selectCell(ImageEditorCurrentlyEditing.row,ImageEditorCurrentlyEditing.col);
                });
                
            }else{
                let newTitle = jQuery("<h1>" + 'Featured image(#' + DG.getDataAtRowProp(this.row,'sku') + ' ' + DG.getDataAtRowProp(this.row,'name') + ')' + "</h1>");
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
    
    ///////////////////////////////////////////////////////////////////////////////////////////
    
    let pelm_centerCheckboxRenderer = function (instance, td, row, col, prop, value, cellProperties) {
      Handsontable.renderers.CheckboxRenderer.apply(this, arguments);
      td.style.textAlign = 'center';
      td.style.verticalAlign = 'center';
    };
    
    let pelm_centerCheckboxRendererROHide = function (instance, td, row, col, prop, value, cellProperties) {
      Handsontable.renderers.CheckboxRenderer.apply(this, arguments);
      if(cellProperties.readOnly){
          var fc = td.firstChild;
          while(fc) {
                td.removeChild( fc );
                fc = td.firstChild;
          }
      }else{
          td.style.textAlign = 'center';
          td.style.verticalAlign = 'center';
      }
    };

    let pelm_centerTextRenderer = function (instance, td, row, col, prop, value, cellProperties) {
      Handsontable.renderers.TextRenderer.apply(this, arguments);
      td.style.textAlign = 'center';
      td.style.verticalAlign = 'center';
    };
    
    let pelm_TextRenderer = function (instance, td, row, col, prop, value, cellProperties) {
      Handsontable.renderers.TextRenderer.apply(this, arguments);
      td.style.textAlign = 'left';
      td.style.verticalAlign = 'center';
    };
    
    let pelm_customContentRenderer = function (instance, td, row, col, prop, value, cellProperties) {
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
              let eventManager = Handsontable.eventManager(instance);

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
              let eventManager = Handsontable.eventManager(instance);    
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
    
    let pelm_customImageRenderer = function (instance, td, row, col, prop, value, cellProperties) {
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
            }else{
                value = null;
            }
            Handsontable.renderers.TextRenderer.apply(this, arguments);
            Handsontable.Dom.addClass(td, 'htImage');
            td.insertBefore(clonableIMAGE.cloneNode(true), td.firstChild);
            if (!td.firstChild) { //http://jsperf.com/empty-node-if-needed
              td.appendChild(document.createTextNode('\u00A0')); //\u00A0 equals &nbsp; for a text node
            }

            if (!instance.acArrowListener) {
              instance.acArrowHookedToDouble = true;    
              let eventManager = Handsontable.eventManager(instance);

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
              let eventManager = Handsontable.eventManager(instance);    
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
    
    let VariationEditorInvoker = function (instance, td, row, col, prop, value, cellProperties) {
      Handsontable.renderers.HtmlRenderer.apply(this, arguments);    
           td.innerHTML  = "";
           td.className += " add-var-cell";
           if(!instance.getDataAtRowProp(row,'parent')){
               let ptype = instance.getDataAtRowProp(row,'product_type');

               if(ptype)
                   if(ptype[0])
                       ptype = ptype[0];
               
               if(ptype){
                 if(asoc_product_types[ptype] == 'variable'){
                   let a = document.createElement("a");
                   a.className  = "add-var";
                   //a.target = "_blank";
                   a.href   = "?v="  + instance.getDataAtRowProp(row,'id');
                   a.rel    = instance.getDataAtRowProp(row,'id');
                   a.innerHTML = "Variations..."; 
                   td.appendChild(a);     
                 }
               }
           }
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
    
    sortedBy  = null;
    sortedOrd = null;
    
    jQuery('#dg_wooc').handsontable({
      data: [<?php pelm_product_render($IDS, $attributes, "json");?>],
      minSpareRows: <?php if(isset($plem_price_settings['enable_add'])) { if($plem_price_settings['enable_add']) { echo "1"; 
} else { echo "0";
                    } 
} else { echo "0";
                    }  ?>,
      colHeaders: true,
      rowHeaders:true,
      contextMenu: false,
      manualColumnResize: true,
      manualColumnMove: true,
      //debug:true,
      columnSorting: true,
      persistentState: true,
      variableRowHeights: false,
      fillHandle: 'vertical',
      currentRowClassName: 'currentRow',
      currentColClassName: 'currentCol',
      fixedColumnsLeft: <?php echo intval(esc_attr($plem_price_settings['fixedColumns'])); ?>,
      search: true,
      colWidths:function(cindex){
          let prop = DG.colToProp(cindex);
          if(!prop)
              return 80;
          prop = String(prop);
          if(prop.indexOf("_visible") > 0)
              return 20;
          if(page_col_w[prop]){
              return page_col_w[prop];
          }else
            return cw[cindex];
      },
      width: function () {
        if (availableWidth === void 0) {
          pelm_calculate_size();
        }
        return availableWidth ;
      },
      
      height: function () {
        if (availableHeight === void 0) {
          pelm_calculate_size();
        }
        return availableHeight;
      }
      ,colHeaders:[
        "ID"
        <?php if(pelm_fn_show_filed('sku')) { echo ',"'.__("SKU", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('name')) { echo ',"'.__("Name", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('slug')) { echo ',"'.__("Slug", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('product_type')) { echo ',"'.__("P. Type", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('parent')) { echo ',"'.__("Variations", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('categories')) { echo ',"'.__("Category", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('featured')) { echo ',"'.__("Featured", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('virtual')) { echo ',"'.__("Virtual", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('downloadable')) { echo ',"'.__("Downloadable", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('stock_status')) { echo ',"'.__("In stock?", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('stock')) { echo ',"'.__("Stock", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('price')) { echo ',"'.__("Price", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('override_price')) { echo ',"'.__("Sales price", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('tags')) { echo  ',"'. esc_html__("Tags", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php
         
        foreach($attributes as $att){
            if(pelm_fn_show_filed('pattribute_' . $att->id)) {     
                echo ",".'"'.esc_attr(addslashes(esc_attr($att->label))).'"'; 
                if(pelm_fn_show_filed("attribute_show")) {
                      echo ",".'"<span class=\'attr_visibility\'></span>"';
                } 
            }
        }
         
        ?>
        
        <?php if(pelm_fn_show_filed('status')) { echo ',"'.__("Status", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('weight')) { echo ',"'.__("Weight", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('height')) { echo ',"'.__("Height", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('width')) { echo ',"'.__("Width", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('length')) { echo ',"'.__("Length", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('image')) { echo ',"'.__("Image", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('gallery')) { echo ',"'.__("Gallery", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('backorders')) { echo ',"'.__("Backorders", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('shipping_class')) { echo ',"'.__("Shipp. class", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('tax_status')) { echo ',"'.__("Tax status", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        <?php if(pelm_fn_show_filed('tax_class')) { echo ',"'.__("Tax class", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light').'"';
        }?>
        
        <?php
        foreach($custom_fileds as $cfname => $cfield){ 
            echo ',"'.addslashes(__(esc_attr($cfield->title), 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light')).'"';
        }
        ?>        
        
      ],
      columns: [
       { data: "id", readOnly: true, type: 'numeric' }
      <?php if(pelm_fn_show_filed('sku')) { ?>,{ data: "sku", type: 'text' }<?php 
      } ?>
      <?php if(pelm_fn_show_filed('name')) { ?>,{ data: "name", type: 'text'  }<?php 
      } ?> 
      <?php if(pelm_fn_show_filed('slug')) { ?>,{ data: "slug", type: 'text'  }<?php 
      } ?>
      
      <?php if(pelm_fn_show_filed('product_type')) { ?>,{
        data: "product_type",
        editor: CustomSelectEditor.prototype.extend(),
        renderer: CustomSelectRenderer,
        select_multiple: false,
        dictionary: asoc_product_types,
        selectOptions: (!product_types) ? [] : product_types.map(function(source){
                           return {
                             "name": source.name , 
                             "value": source.id
                           }
                        })
       }<?php } ?>
      <?php if(pelm_fn_show_filed('parent')) { ?>,{ data: "parent", renderer: VariationEditorInvoker  }<?php 
      } ?>       
      <?php if(pelm_fn_show_filed('categories')) { ?>,{
        data: "categories",
        editor: CustomSelectEditor.prototype.extend(),
        renderer: CustomSelectRenderer,
        select_multiple: true,
        dictionary: asoc_cats,
        selectOptions: (!categories) ? [] : categories.map(function(source){
                           return {
                             "name": source.treename , 
                             "value": source.category_id
                           }
                        })
       }<?php } ?>
      <?php if(pelm_fn_show_filed('featured')) { ?>,{ data: "featured" , type: "checkbox", renderer: pelm_centerCheckboxRenderer }<?php 
      } ?>
      <?php if(pelm_fn_show_filed('virtual')) { ?>,{ data: "virtual" , type: "checkbox", renderer: pelm_centerCheckboxRenderer }<?php 
      } ?>
      <?php if(pelm_fn_show_filed('downloadable')) { ?>,{ data: "downloadable" , type: "checkbox", renderer: pelm_centerCheckboxRenderer }<?php 
      } ?>
      <?php if(pelm_fn_show_filed('stock_status')) { ?>,{ data: "stock_status" , type: "checkbox", renderer: pelm_centerCheckboxRenderer }<?php 
      } ?>
      <?php if(pelm_fn_show_filed('stock')) { ?>,{ data: "stock" ,type: 'numeric', renderer: pelm_centerTextRenderer }<?php 
      } ?>
      <?php if(pelm_fn_show_filed('price')) { ?>,{ data: "price"  ,type: 'numeric',format: '0<?php echo esc_attr(substr($_num_sample, 1, 1));?>00'}<?php 
      } ?>
      <?php if(pelm_fn_show_filed('override_price')) { ?>,{ data: "override_price"  ,type: 'numeric',format: '0<?php echo esc_attr(substr($_num_sample, 1, 1));?>00'} <?php 
      } ?> 
      <?php if(pelm_fn_show_filed('tags')) { ?>,{
        data: "tags",
        editor: CustomSelectEditor.prototype.extend(),
        renderer: CustomSelectRenderer,
        select_multiple: true,
        dictionary: asoc_tags,
        allow_random_input: true,
        selectOptions: (!tags) ? [] : tags.map(function(source){
                           return {
                             "name": source.name , 
                             "value": source.id
                           }
                        })
       }<?php } ?>
      
 <?php
    
    foreach($attributes as $att){
        if(pelm_fn_show_filed('pattribute_' . $att->id)) {
            echo ',{';
            echo '  data: "pattribute_'.esc_attr($att->id).'" ';
            echo ' ,editor: CustomSelectEditor.prototype.extend() ';
            echo ' ,renderer: CustomSelectRenderer ';
            ?>
                ,select_multiple:function(dg,row, prop){
                    return !dg.getDataAtRowProp(row,'parent');    
                }
                ,allow_random_input: function(dg,row, prop){
                    return !dg.getDataAtRowProp(row,'parent');    
                }
            <?php
            echo ' ,dictionary: asoc_attribute_'.esc_attr($att->id) . ' ';
            echo ' ,selectOptions: attribute_'.esc_attr($att->id) . ' ';
            echo '}';
            if(pelm_fn_show_filed("attribute_show")) {
                echo ',{ data: "pattribute_'.esc_attr($att->id).'_visible" , type: "checkbox", renderer: pelm_centerCheckboxRendererROHide }';
            }
        }
    }
    
    ?>
      <?php if(pelm_fn_show_filed('status')) { ?>,{ 
         data: "status", 
         editor: CustomSelectEditor.prototype.extend(),
         renderer: CustomSelectRenderer,
         select_multiple: false,
         dictionary: pelm_array_to_dictionary(postStatuses),
         selectOptions:postStatuses
       }<?php } ?>
      <?php if(pelm_fn_show_filed('weight')) { ?>,{ data: "weight", type: 'text' }<?php 
      } ?>
      <?php if(pelm_fn_show_filed('height')) { ?>,{ data: "height", type: 'text' }<?php 
      } ?>
      <?php if(pelm_fn_show_filed('width')) { ?>,{ data: "width", type: 'text' }<?php 
      } ?>
      <?php if(pelm_fn_show_filed('length')) { ?>,{ data: "length", type: 'text' }<?php 
      } ?>
      <?php if(pelm_fn_show_filed('image')) { ?>,{ 
        data: "image", 
        editor: customImageEditor.prototype.extend(),
        renderer: pelm_customImageRenderer,
        select_multiple: false
}<?php } ?>
      <?php if(pelm_fn_show_filed('gallery')) { ?>,{ 
        data: "gallery", 
        editor: customImageEditor.prototype.extend(),
        renderer: pelm_customImageRenderer,
        select_multiple: true
}<?php } ?>
      <?php if(pelm_fn_show_filed('backorders')) { ?>,{ 
        data: "backorders" ,
        editor: CustomSelectEditor.prototype.extend(),
        renderer: CustomSelectRenderer,
        select_multiple: false,
        dictionary:pelm_array_to_dictionary(["yes","notify","no"]),
        selectOptions: ["yes","notify","no"]
        }
      <?php } ?>
      <?php if(pelm_fn_show_filed('shipping_class')) { ?>,{
        data: "shipping_class",
        editor: CustomSelectEditor.prototype.extend(),
        renderer: CustomSelectRenderer,
        select_multiple: false,
        dictionary: asoc_shipping_calsses,
        selectOptions: (!shipping_calsses) ? [] : shipping_calsses.map(function(source){
                           return {
                             "name": source.name , 
                             "value": source.id
                           }
                        })
       }<?php } ?>
      <?php if(pelm_fn_show_filed('tax_status')) { ?>,{
        data: "tax_status",
        editor: CustomSelectEditor.prototype.extend(),
        renderer: CustomSelectRenderer,
        select_multiple: false,
        dictionary: asoc_tax_statuses,
        selectOptions: tax_statuses
       }<?php } ?>
      <?php if(pelm_fn_show_filed('tax_class')) { ?>,{
        data: "tax_class",
        editor: CustomSelectEditor.prototype.extend(),
        renderer: CustomSelectRenderer,
        select_multiple: false,
        dictionary: asoc_tax_classes,
        selectOptions: tax_classes
       }<?php } ?>
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
                    $asoc_trm = new stdClass;
                    foreach($cfield->terms as $t){
                        $asoc_trm->{$t->value} = $t->name;
                    } 
                    echo json_encode($asoc_trm);
                    ?>
                 }
            <?php }else{?>
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
                    if($cfield->options->unchecked_value || $cfield->options->unchecked_value === "0") { echo ',uncheckedTemplate: "'. esc_attr($cfield->options->unchecked_value).'"';
                    } 
                }elseif($cfield->options->formater == "dropdown") {
                    echo ',type: "autocomplete", strict: ' . ($cfield->options->strict ? "true" : "false");
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
                    echo ',dateFormat: "'.($cfield->options->format ? $cfield->options->format : "YYYY-MM-DD HH:mm:ss").'"';
                    echo ',correctFormat: true';
                    echo ',defaultDate: "'.($cfield->options->default ? $cfield->options->default : "0000-00-00 00:00:00").'"';
                    echo ',renderer: pelm_TextRenderer';
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
             if(multidel)
                 return true;
             if(!DG.getDataAtRowProp(index,"id"))
                 return false;
             
             if(confirm("<?php echo esc_html__("Remove product", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?> <?php echo esc_html__("SKU", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?>:" + DG.getDataAtRowProp(index,"sku") + ", <?php echo esc_html__("Name", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?>: '" + DG.getDataAtRowProp(index,"name") + "', ID:" +  DG.getDataAtRowProp(index,"id") + "?")){
                
                let id = DG.getDataAtRowProp(index,"id");
                
                if(!tasks[id])
                    tasks[id] = {};
                
                tasks[id]["DO_DELETE"] = 'delete';
                id_index = null;
                pelm_call_save();
                
                return true;         
             }else
                return false;
        
        }        <?php
            } 
        }

        ?>
      
      ,afterChange: function (change, source) {
        if(!change)   
            return;
        if(!DG)
            DG = jQuery('#dg_wooc').data('handsontable');
        
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
          
            let id = DG.getDataAtRowProp (data[0],'id');
            if(!id){
                if(!data[3])
                    return;
                let surogat = "s" + parseInt( Math.random() * 10000000); 
                DG.getSourceDataAtRow(data[0])['id'] = surogat;
                id = surogat;
                SUROGATES[surogat] = data[0];
            }
            
            let prop = data[1];
            let val  = data[3];
            if(!tasks[id])
                tasks[id] = {};
            tasks[id][prop] = val;
            tasks[id]["dg_index"] = data[0];
        });
        
        pelm_call_save();
        
      }
      ,afterColumnResize: function(col, newSize){
        if(DG.c_resize_monior_disable)
            return;
          if(DG){
            if(DG.colToProp(col).indexOf("_visible") > 0){
                if(newSize > 22){
                    let c_w = [];
                    for(let i = 0 ; i < DG.countCols(); i++){
                        if(i == col){
                            c_w.push(20);    
                        }else
                            c_w.push(DG.getColWidth(i));    
                    }
                    DG.c_resize_monior_disable =  true;
                    DG.updateSettings({colWidths: c_w});
                    DG.c_resize_monior_disable = false;                    
                }
            }
            page_col_w = {};
            for(let i = 0 ; i < DG.countCols(); i++){
                page_col_w[DG.getCellMeta(0,i).prop] = DG.getColWidth(i);
            }
        }
      },afterColumnMove: function(oldIndex, newIndex){
            let c_w = [];
            for(let i = 0 ; i < DG.countCols(); i++){
                c_w.push(DG.getColWidth(i));    
            }
            
          
            if(DG.colToProp(newIndex).indexOf("pattribute_") == 0){
                let prop = DG.colToProp(newIndex);
                if(prop.indexOf("_visible") > 0){
                    let c = DG.propToCol(prop.replace("_visible",""));
                    if(jQuery.isNumeric(c)){
                        let a_i   = false;
                        let a_i_v = false;
                        
                        for(let i  = 0; i< DG.countCols() ; i++){
                            if(DG.getCellMeta(0,i).prop == prop.replace("_visible","")){
                                a_i = i;    
                            }else if(DG.getCellMeta(0,i).prop == prop){
                                a_i_v = i;    
                            }
                            if(a_i !== false && a_i_v !== false){
                                DG.manualColumnPositions.splice(a_i_v + ( newIndex < oldIndex ? 0 : -1), 0, DG.manualColumnPositions.splice(a_i, 1)[0]);
                                break;
                            }
                        } 
                    }else
                        return;
                    
                }else{
                    let c = DG.propToCol(prop + "_visible");
                    if(jQuery.isNumeric(c)){
                        let a_i   = false;
                        let a_i_v = false;
                        
                        for(let i  = 0; i< DG.countCols() ; i++){
                            if(DG.getCellMeta(0,i).prop == prop){
                                a_i = i;    
                            }else if(DG.getCellMeta(0,i).prop == prop + "_visible"){
                                a_i_v = i;    
                            }
                            if(a_i !== false && a_i_v !== false){
                                DG.manualColumnPositions.splice(a_i + ( newIndex < oldIndex ? 1 : 0), 0, DG.manualColumnPositions.splice(a_i_v, 1)[0]);
                                break;
                            }
                        } 
                    }else
                        return;
                }
                DG.forceFullRender = true;
                DG.view.render()
                Handsontable.hooks.run(DG, 'persistentStateSave', 'manualColumnPositions', DG.manualColumnPositions);
            }
            
            if(page_col_w){
                let c_w = [];
                for(let i  = 0; i< DG.countCols() ; i++){
                    let prop = DG.getCellMeta(0,i).prop;
                    if(page_col_w[prop]){
                        c_w.push(page_col_w[prop]);
                    }else{
                        if(prop.indexOf("_visible") > 0)
                            c_w.push(20);
                        else
                            c_w.push(80);
                    }
                }
            }
            
            DG.c_resize_monior_disable =  true;
            DG.updateSettings({colWidths: c_w});
            DG.c_resize_monior_disable = false;    
            
      },beforeColumnSort: function (column, order){
          
          if(explicitSort)
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
            DG = jQuery('#dg_wooc').data('handsontable');
            
        if(!DG)
            return;
        
        this.readOnly = false;
                
        let row_data = DG.getData()[row]; 
        if(!row_data)
            return;
        
        if(prop == "id"){
            this.readOnly = true;
            return;
        }
        
        if(row_data.parent){
            if(jQuery.inArray(prop, variations_fields) < 0){
                this.readOnly = true;
            }
        }
        
        try{
            if(prop.indexOf('pattribute_') == 0){
                let attgid = prop.substr(11);
                if(row_data.att_info[attgid]){
                    if(row_data.parent && !this.readOnly){
                        this.readOnly = !row_data.att_info[attgid].v;
                    }else if(!this.readOnly){
                        this.readOnly = row_data.att_info[attgid].v;
                    }
                }else if(row_data.parent)
                    this.readOnly = true;
            }
        }catch(ex){}
        
        if(!(prop == 'price' || prop == 'override_price' || prop == 'gallery' || prop == 'image' ))
            this.readOnly = true;
        
      },afterSelection:function(r, c, r_end, c_end){
            let img = DG.getDataAtRowProp(r,'image');
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
    
    if(!DG)
        DG = jQuery('#dg_wooc').data('handsontable');
    
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

    if('<?php echo esc_attr($product_category);?>') jQuery('.filter_option *[name="product_category"]').val("<?php if($product_category) { echo esc_attr(implode(",", $product_category));
}?>".split(','));
    if('<?php echo esc_attr($product_tag);?>') jQuery('.filter_option *[name="product_tag"]').val("<?php if($product_tag) { echo esc_attr(implode(",", $product_tag));
}?>".split(','));
    if('<?php echo esc_attr($product_shipingclass);?>') jQuery('.filter_option *[name="product_shipingclass"]').val("<?php if($product_shipingclass) { echo esc_attr(implode(",", $product_shipingclass));
}?>".split(','));
    if('<?php echo esc_attr($product_status);?>') jQuery('.filter_option *[name="product_status"]').val("<?php if($product_status) { echo esc_attr(implode(",", $product_status));
}?>".split(','));
    
    
    <?php 
    foreach($attributes as $attr){
        if(isset($filter_attributes[$attr->name])) {
            ?>
    jQuery('.filter_option *[name="pattribute_<?php echo esc_attr($attr->id); ?>"]').val("<?php if($filter_attributes[$attr->name]) { echo esc_attr(implode(",", $filter_attributes[$attr->name]));
}?>".split(','));
            <?php 
        }    
    } ?>
    
    

    jQuery('SELECT[name="product_category"]').chosen();
    jQuery('SELECT[name="product_status"]').chosen();
    jQuery('SELECT[name="product_tag"]').chosen();
    jQuery('SELECT[name="product_shipingclass"]').chosen();

    jQuery('SELECT.attribute-filter').chosen({
                    create_option:            true,
                    create_option_text:      'value',
                    persistent_create_option: true,
                    skip_no_results:          true
                });
                
    jQuery("<div class='grid-bottom-spacer' style='min-height:120px;'></div>").insertAfter( jQuery("table.htCore"));        
    
    
    function pelm_screen_search(select){
        if(DG){
            let self        = document.getElementById('activeFind');
            let queryResult = DG.search.query(self.value);
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
       $upd_val = pelm_read_sanitized_request_parm("mass_update_val", 0) .( pelm_read_sanitized_request_parm("mass_update_percentage") ? "%" : "" );
    ?>
       jQuery(window).load(function(){
       alert('<?php echo esc_attr(sprintf(__("Product price for all products matched by filter criteria is changed by %s", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'), $upd_val)); ?>');
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
    let link = pelm_get_request_url() + "&do_export=1" ;
   
    let QUERY_DATA = {};
    QUERY_DATA.sortOrder            = DG.sortOrder ? "ASC" : "DESC";
    QUERY_DATA.sortColumn           = pelm_get_sort_property();
    
    QUERY_DATA.limit                = "9999999999";
    QUERY_DATA.page_no              = "1";
    
    QUERY_DATA.sku                  = jQuery('.filter_option *[name="sku"]').val();
    QUERY_DATA.product_name         = jQuery('.filter_option *[name="product_name"]').val();
    QUERY_DATA.product_shipingclass = jQuery('.filter_option *[name="product_shipingclass"]').val();
    QUERY_DATA.product_category     = jQuery('.filter_option *[name="product_category"]').val();
    QUERY_DATA.product_tag          = jQuery('.filter_option *[name="product_tag"]').val();
    QUERY_DATA.product_status       = jQuery('.filter_option *[name="product_status"]').val();
<?php foreach($attributes as $attr){ ?>
    QUERY_DATA.pattribute_<?php echo esc_attr($attr->id);?> = jQuery('.filter_option *[name="pattribute_<?php echo esc_attr($attr->id);?>"]').val();
<?php } ?>    
    
    
    for(let key in QUERY_DATA){
        if(QUERY_DATA[key])
            link += ("&" + key + "=" + QUERY_DATA[key]);
    }
    
    window.open(link, '_blank');
    
    
    return false;
}

function pelm_do_import(){
    let import_panel = jQuery("<div class='import_form'><form method='POST' enctype='multipart/form-data'>"
    + ('<input name="pelm_security" type="hidden" value="<?php echo esc_attr(pelm_get_nonce("pelm_nonce")); ?>" />') 
    + "<span><?php echo esc_html__("Select .CSV file to update prices/stock from.<br>(To void price, stock or any available field update remove coresponding column from CSV file)", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></span><br/><label for='file'><?php echo esc_html__("File:", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></label><input type='file' name='file' id='file' /><br/><br/><button class='cmdImport' ><?php echo esc_html__("Import", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></button><button class='cancelImport'><?php echo esc_html__("Cancel", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></button></form><br/><p>*If you edit from MS Excel you must save using 'Save As', for 'Sava As Type' choose 'CSV Comma Delimited (*.csv)'. Otherwise MS Excel fill save in incorrect format!</p></div>"); 
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
        let frm = import_panel.find('FORM');
        let POST_DATA = {};
        
        POST_DATA.do_import            = "1";
        POST_DATA.sortOrder            = DG.sortOrder ? "ASC" : "DESC";
        POST_DATA.sortColumn           = pelm_get_sort_property();
        POST_DATA.limit                = jQuery('#txtlimit').val();
        POST_DATA.page_no              = jQuery('#paging_page').val();
        
        POST_DATA.sku                  = jQuery('.filter_option *[name="sku"]').val();
        POST_DATA.product_name         = jQuery('.filter_option *[name="product_name"]').val();
        POST_DATA.product_shipingclass = jQuery('.filter_option *[name="product_shipingclass"]').val();
        POST_DATA.product_category     = jQuery('.filter_option *[name="product_category"]').val();
        POST_DATA.product_tag          = jQuery('.filter_option *[name="product_tag"]').val();
        POST_DATA.product_status       = jQuery('.filter_option *[name="product_status"]').val();
<?php foreach($attributes as $attr){ ?>
        POST_DATA.pattribute_<?php echo esc_attr($attr->id);?> = jQuery('.filter_option *[name="pattribute_<?php echo esc_attr($attr->id);?>"]').val();
<?php } ?>        
        
        for(let key in POST_DATA){
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

if($use_image_picker || $use_content_editior || pelm_fn_show_filed('image')  || pelm_fn_show_filed('gallery')) {
       wp_print_styles('media-views');
       wp_print_styles('imgareaselect');
       wp_print_media_templates();
}

?>
<div style="display:none">

 <div id="variant_dialog">
   <p style="color:red" >
   Not available with this version!
   </p>
   <h3><?php echo esc_html__("Configure Variations:", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?></h3>
   
   <?php
    foreach($attributes as $att){
        ?>    
   <div class="att-row">
     <label><?php echo esc_attr($att->label);?></label>
     <input disabled type="checkbox" att_id="<?php echo esc_attr($att->id); ?>" name="<?php echo esc_attr($att->name); ?>" value="1" />
    </div>    
        <?php     
    }
    ?>
  <hr/>
  <br/>
  <h3><?php echo esc_html__("Create variations:", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?></h3>
  <div class="att-row">
   <p><?php echo esc_html__("Create", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?> <input disabled style="width:35px;text-align:center;" id="createVarCount" value="0" /> <?php echo esc_html__("new variations", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></p>  
  </div>
 </div>
</div>
<script type="text/javascript">
var attributes = <?php echo json_encode($attributes); ?>;
var variant_dialog = null;

jQuery(document).ready(function(){
    jQuery("#variant_dialog").dialog({
        autoOpen: false,
        modal: true,
        maxWidth: '90%',
        width:280,
        buttons: {
            "<?php echo esc_html__("Cancel", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');?>": function() {
              jQuery( this ).dialog( "close" );
            }
        }
    }).on( "dialogopen", function( event, ui ) {
        jQuery("#variant_dialog SELECT:not(.chos-done)").addClass("chos-done").chosen({
            create_option: true,
            create_option_text: 'value',
            persistent_create_option: true,
            skip_no_results: true
        });
    });
    variant_dialog = jQuery("#variant_dialog");
});

jQuery(document).on("click","a.add-var",function(e){
    e.preventDefault();
    var id = DG.getDataAtRowProp(DG.getSelected()[0],'id');
    
    jQuery("#variant_dialog INPUT[type='checkbox']").prop('checked',false);
    var att_info = DG.getDataAtRowProp(DG.getSelected()[0],'att_info');
    
    for(var aid in att_info){
        try{
            if (att_info.hasOwnProperty(aid)) {
                if(att_info[aid].v)
                    jQuery("#variant_dialog INPUT[type='checkbox'][att_id='" + aid + "']").prop('checked',true);
            }
        }catch(e){
            //    
        }
    }
    jQuery("#createVarCount").val(0);
    jQuery("#variant_dialog").attr("ref_dg_index",DG.getSelected()[0]).attr("ref_id",id).dialog('option', 'title', '(' + id + ') ' + DG.getDataAtRowProp(DG.getSelected()[0],'name')).dialog('open');
});

</script>

<?php
if($res_limit_interupted > 0) {
    ?>
<script type="text/javascript">
    jQuery(window).load(function(){
        alert("WARNING!\nProduct output interrupted after <?php echo esc_attr($res_limit_interupted); ?> due memory and execution time limits!\nYou should decrease product per page setting.");    
    });
</script>    
    <?php
}    
?>

</div>
</div>
<?php

exit;
?>