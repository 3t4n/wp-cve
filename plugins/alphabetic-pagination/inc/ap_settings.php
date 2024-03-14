<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $ap_implementation, $ap_premium_link, $ap_datap, $ap_customp, $css_arr, $ap_group, $wpdb, $ap_allowed_pages, $ap_query_number, $ap_post_types, $ap_all_plugins, $ap_plugins_activated, $ap_dir, $ap_url, $ap_wc_shortcodes, $ap_android_settings, $ap_settings_saved, $ap_reset_theme;

	//pree($ap_all_plugins);

$mquery = "SELECT $wpdb->postmeta.meta_key FROM $wpdb->postmeta WHERE $wpdb->postmeta.meta_key NOT LIKE '\_%'  AND $wpdb->postmeta.meta_value NOT LIKE '%{%' AND $wpdb->postmeta.meta_value!=''  GROUP BY $wpdb->postmeta.meta_key ORDER BY $wpdb->postmeta.meta_key ASC";
$args = array(
	'posts_per_page'   => -1,
	'offset'           => 0,
	'category'         => '',
	'category_name'    => '',
	'orderby'          => 'title',
	'order'            => 'ASC',
	'include'          => '',
	'exclude'          => '',
	'meta_key'         => '',
	'meta_value'       => '',
	'post_type'        => 'page',
	'post_mime_type'   => '',
	'post_parent'      => '',
	'author'	   => '',
	'post_status'      => 'publish',
	'suppress_filters' => true 
);
$allowed_pages = get_posts($args);

$ap_implementation = ap_get_option('ap_implementation');
require_once('languages.php');
$dom_selectors = array(
'#main'=>'#main',
'#primary'=>'#primary',
'#content'=>'#content',
'#site-content'=>'#site-content',
'body.post-type-archive-product .woocommerce-products-header__title.page-title' => __('WooCommerce Shop Page > Above Page Title','alphabetic-pagination'),
'body.post-type-archive-product div.woocommerce-notices-wrapper' => __('WooCommerce Shop Page > Below Page Title','alphabetic-pagination'),
'body.tax-product_cat .woocommerce-products-header__title.page-title' => __('WooCommerce Product Category Page > Above Category Name','alphabetic-pagination'),
'body.tax-product_cat div.woocommerce-notices-wrapper' => __('WooCommerce Product Category Page > Below Category Name','alphabetic-pagination'),
'body.tax-product_cat .woocommerce-products-header__title.page-title, body.post-type-archive-product .woocommerce-products-header__title.page-title' => __('WooCommerce Shop + Category Pages > Above Heading','alphabetic-pagination'),
'body.tax-product_cat div.woocommerce-notices-wrapper, body.post-type-archive-product div.woocommerce-notices-wrapper' => __('WooCommerce Shop + Category Pages> Below Heading','alphabetic-pagination'),
);
$ap_styles = array(
'ap_gogowords'=>'Gogo Words',
'ap_chess'=>'AP Chess',
'ap_classic'=>'AP Classic',
'ap_mahjong'=>'AP Mahjong'
);
if($ap_customp){
	$ap_styles['ap_miami'] = 'AP Miami';
}

ksort($ap_styles);
$ap_classes = implode(' ', array_keys($ap_styles));

$ap_taxonomies = get_taxonomies();
$stored_tax = ap_get_option('ap_tax', array());
$stored_tax = (is_array($stored_tax)?$stored_tax:array());
$stored_langs = ap_get_option('ap_lang');

$get_post_types = get_post_types();
$ap_where_meta = ap_get_option('ap_where_meta');


if(empty($ap_taxonomies))
$ap_taxonomies = array();

if(empty($stored_tax))
$stored_tax = array();

if(empty($stored_langs) || !is_array($stored_langs))
$stored_langs = array();



$dom_default = false;
$dom_selected = (ap_get_option('ap_dom')==''?false:true);

$ap_all = (ap_get_option('ap_all')==1?true:false);

$ap_numeric_sign = (ap_get_option('ap_numeric_sign')==0?false:true);
$ap_reset_sign = (ap_get_option('ap_reset_sign')==0?false:true);

$wpurl = get_bloginfo('wpurl');

$theme_name = str_replace(array(' ', '-'), '', strtolower(wp_get_theme()));

?>

<div class="wrap ap_settings_div">

        

<div class="icon32" id="icon-options-general"><br></div><h2><?php echo esc_html($ap_datap['Name']); ?> <?php echo esc_html('('.$ap_datap['Version'].($ap_customp?') Pro':')')); ?> - <?php _e('Settings','alphabetic-pagination'); ?> </h2> 
<?php if(!$ap_customp): ?>
<a title="<?php _e('Click here to download pro version','alphabetic-pagination'); ?>" style="background-color: #25bcf0;    color: #fff !important;    padding: 2px 30px;    cursor: pointer;    text-decoration: none;    font-weight: bold;    right: 0;    position: absolute;    top: 0;    box-shadow: 1px 1px #ddd;" href="https://shop.androidbubbles.com/download/" target="_blank"><?php _e('Already a Pro Member?','alphabetic-pagination'); ?></a>
<?php endif; ?>
    <?php if(class_exists('QR_Code_Settings_AP')){ $ap_android_settings->ab_io_display($ap_url); } ?>

<ul class="nav-tab-wrapper">
    <li><a class="nav-tab nav-tab-active" data-tab="general" data-type="free"><i class="fas fa-signature"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e("General", 'alphabetic-pagination'); ?></a></li>
    <li><a class="nav-tab" data-tab="styling" data-type="free"><i class="fas fa-palette"></i>&nbsp;&nbsp;&nbsp;&nbsp;<?php _e("Styling", 'alphabetic-pagination'); ?></a></li>
    <li><a class="nav-tab" data-tab="shortcodes" data-type="pro"><i class="fas fa-code"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php _e("Shortcodes", 'alphabetic-pagination'); ?></a></li>
    <li><a class="nav-tab" data-tab="permissions" data-type="pro"><i class="fas fa-key"></i>&nbsp;&nbsp;&nbsp;&nbsp;<?php _e("Permissions", 'alphabetic-pagination'); ?></a></li>
    <li><a class="nav-tab" data-tab="logs"><i class="fas fa-route"></i>&nbsp;&nbsp;&nbsp;&nbsp;<?php _e("Logs",'alphabetic-pagination'); ?></a></li>
    <li style="float:right"><a class="nav-tab" data-tab="help" data-type="free"><i class="far fa-question-circle"></i>&nbsp;<?php _e("Help", 'alphabetic-pagination'); ?></a></li>
</ul>
    <?php if($ap_settings_saved): ?>
    <div class="row mt-3 ap_alert_show">
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><?php _e('Successfully updated.', 'alphabetic-pagination') ?></strong>                <button type="button" class="close" data-dismiss="alert" aria-label="<?php _e('Close', 'alphabetic-pagination') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>
        
        <div class="alert alert-warning alert-dismissible premium-alert mt-4">
         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true" style="font-size:20px">×</span>
          </button>    <strong><?php _e('Warning', 'alphabetic-pagination'); ?>!</strong> <?php _e('This is a premium feature.', 'alphabetic-pagination'); ?> <a target="_blank" href="<?php echo esc_url($ap_premium_link); ?>" class="btn btn-sm btn-danger"><?php _e('Go Premium', 'alphabetic-pagination'); ?></a>
        </div>
        
    <div class="nav-tab-content container-fluid" data-content="general">

        <?php if(function_exists('ap_start_settings_form')){ap_start_settings_form();} ?>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="ap_notes">
                    <?php echo __('By default this plugin enables pagination on the default posts page','alphabetic-pagination').' ('.__('Settings','alphabetic-pagination').' > '.__('Reading','alphabetic-pagination').').<br />'.__('The following option enables Alphabetical Pagination on all other templates','alphabetic-pagination'); ?>.
                </div>
            </div>
        </div>


        <div class="row">

                <div class="col-md-12">

                    <div class="row alphabets_section">
                        <div class="col-md-6 ap_side_label pt-3">
                            <span>

                                <?php _e('Implementation','alphabetic-pagination'); ?>:

                            </span>
                        </div>
                        <div class="col-md-6 pt-3">

                            
                            
<div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons">
    <label class="btn btn-warning" for="ap_implementation_auto">
        <input type="radio" <?php echo ($ap_implementation!=AP_CUSTOM?'checked="checked"':''); ?> class="ap_imp" id="ap_implementation_auto" value="<?php _e('auto','alphabetic-pagination'); ?>" name="ap_implementation" autocomplete="off"> <?php _e('Auto','alphabetic-pagination'); ?>
    </label>
    <label class="btn btn-warning" for="ap_implementation_custom">
        <input type="radio" <?php echo ($ap_implementation!=AP_CUSTOM?'':'checked="checked"'); ?> class="ap_imp" id="ap_implementation_custom" value="<?php echo AP_CUSTOM; ?>" name="ap_implementation" autocomplete="off"> <?php echo ucwords(AP_CUSTOM); ?>
    </label>
</div>                            

                        </div>
                    </div>

                        <?php if(!empty($allowed_pages)): ?>
                        <?php endif; ?>

                    <div class="row alphabets_section">

                        <div class="col-md-6 ap_side_label">
                            <span>

                                <?php _e('Display everywhere?','alphabetic-pagination'); ?> <small>(<?php _e('Archives, Categories, All Post Types etc.','alphabetic-pagination'); ?>)</small>

                            </span>
                        </div>
                        <div class="col-md-6">




<div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons">
    <label class="btn btn-warning" for="ap_all_yes">
        <input type="radio" <?php echo ($ap_all?'checked="checked"':''); ?> class="tog" id="ap_all_yes" value="1" name="ap_all" autocomplete="off"> <?php _e('Yes','alphabetic-pagination'); ?>
    </label>
    <label class="btn btn-warning active" for="ap_all_no">
        <input type="radio" <?php echo ($ap_all?'':'checked="checked"'); ?> class="tog" id="ap_all_no" value="0" name="ap_all" autocomplete="off"> <?php _e('No','alphabetic-pagination'); ?>
    </label>
</div>     

                            <div class="ap_tax_div mb-3 <?php echo ($ap_all?'hide':''); ?>">

                                <fieldset>

                                    <div>
                                        <select class="ap_taxes" name="ap_tax[]" id="tax_selector" multiple="multiple">
                                            <option value=""><?php _e('Select','alphabetic-pagination'); ?></option>
                                            <?php foreach($ap_taxonomies as $tax): ?>
                                                <option value="<?php echo esc_attr($tax); ?>" <?php echo in_array($tax, $stored_tax)?'selected="selected"':''; ?>><?php echo esc_html($tax); ?></option>
                                            <?php endforeach; ?>
                                        </select>

                                    </div>
                                    <small><?php _e('Note: Multiple taxonomies can be selected.','alphabetic-pagination'); ?></small>
                                </fieldset>

                            </div>

                            <div class="ap_tax_types mb-3 hide">

                                <fieldset class="mb-3">

                                    <div class="">
                                        <select style="background-color:#25bcf0; color:#fff;" class="ap_taxes_types mr-1" name="ap_tax_types[]" id="tax_types_selector" multiple="multiple">
                                            <option value=""><?php _e('Select to Include','alphabetic-pagination'); ?></option>
                                        </select>
                                        <select style="background-color:#fc5151; color:#fff;" class="ap_taxes_types_x" name="ap_tax_types_x[]" id="tax_types_selector_x" multiple="multiple">
                                            <option value=""><?php _e('Select to Exclude','alphabetic-pagination'); ?></option>
                                        </select>
                                    </div>
                                    <small><?php echo __('Note: Multiple items can be selected.','alphabetic-pagination').' '.__('Exclude will overwrite include.','alphabetic-pagination'); ?></small>
                                </fieldset>


                                <?php
                                $meta_values = $wpdb->get_results($mquery);

                                if(!empty($meta_values)){
                                    //pree($meta_values);
                                    ?>

                                    <fieldset>

                                        <div>
                                            <select class="ap_taxes_types" name="ap_where_meta" id="where_meta">


                                                <option value=""><?php _e('Choose','alphabetic-pagination'); ?> meta_key <?php _e('for filtering','alphabetic-pagination'); ?></option>
                                                <?php
                                                foreach($meta_values as $mvalues){
                                                    ?>
                                                    <option <?php echo ($mvalues->meta_key==$ap_where_meta)?'selected="selected"':''; ?> value="<?php echo esc_attr($mvalues->meta_key); ?>"><?php echo esc_html($mvalues->meta_key); ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <small><?php echo __('Default','alphabetic-pagination').': post_title '.__('is default column for filtering','alphabetic-pagination'); ?></small>
                                    </fieldset>
                                    <?php
                                }

                                ?>

                            </div>




                        </div>
                    </div>

                    <div class="row alphabets_section">

                        <div class="col-md-6 ap_side_label">
                            <span><?php _e('Post Type','alphabetic-pagination'); ?>: <small><?php _e('Post, Page, Product, Order or Custom Post Type etc.','alphabetic-pagination'); ?></small></span>
                        </div>
                        <div class="col-md-6">

                            <div class="ap_auto_more mb-3">
                                <?php
                                $ap_auto_post_types = ap_get_option('ap_auto_post_types', array());
                                $ap_auto_post_statuses = ap_get_option('ap_auto_post_statuses', array());

                                $post_types = get_post_types();
                                $post_statuses = get_post_statuses();


                                ?>

                                <div style="clear:both">
                                    <select class="ap_auto_post_types" name="ap_auto_post_types[]" id="ap_auto_post_types" multiple="multiple">
                                        <option value=""><?php _e('Select','alphabetic-pagination'); ?></option>
                                        <?php foreach($post_types as $post_type_key=>$post_type_value): ?>
                                            <option value="<?php echo esc_attr($post_type_key); ?>" <?php echo in_array($post_type_key, $ap_auto_post_types)?'selected="selected"':''; ?>><?php echo esc_html($post_type_value); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="row alphabets_section">

                        <div class="col-md-6 ap_side_label">
                            <span><?php _e('Post Status','alphabetic-pagination'); ?>:</span>
                        </div>

                        <div class="col-md-6">

                            <div class="mb-3" style="clear:both">
                                <select class="ap_auto_post_statuses" name="ap_auto_post_statuses[]" id="ap_auto_post_statuses" multiple="multiple">
                                    <option value=""><?php _e('Select','alphabetic-pagination'); ?></option>
                                    <?php foreach($post_statuses as $post_status_key=>$post_status_value): ?>
                                        <option value="<?php echo esc_attr($post_status_key); ?>" <?php echo in_array($post_status_key, $ap_auto_post_statuses)?'selected="selected"':''; ?>><?php echo esc_html($post_status_value); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>

                    </div>

                    <div class="row alphabets_section">

                        <div class="col-md-6 ap_side_label">

                                <span><?php _e('DOM Position?','alphabetic-pagination'); ?></span>

                        </div>

                        <div class="col-md-6 mb-3">

                            <fieldset class="doms">


                                <div class="dom_options <?php echo ($dom_selected?'hide':''); ?>">
                                    <a id="dom_default" class="btn btn-sm btn-primary"><?php _e('Default','alphabetic-pagination'); ?></a>
                                    <a id="dom_custom" class="btn btn-sm btn-danger"><?php _e('Custom','alphabetic-pagination'); ?></a>
                                </div>


                                <?php if(in_array(ap_get_option('ap_dom'), $dom_selectors)): ?>

                                    <?php $dom_default = true; ?>

                                <?php endif; ?>


                                <div>

                                    <select class="<?php echo ($dom_default?'':'hide'); ?> dom_opt" name="ap_dom" id="dom_selector">
                                        <option value=""><?php _e('Select','alphabetic-pagination'); ?></option>
                                        <?php foreach($dom_selectors as $dom=>$dom_text): ?>
                                            <option value="<?php echo esc_attr($dom); ?>" <?php selected( $dom, ap_get_option('ap_dom') ); ?>><?php echo esc_html($dom_text); ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <?php echo ($dom_default?'':'<input type="text" name="ap_dom" value="'.esc_attr(ap_get_option('ap_dom')).'" />'); ?>
                                    <a id="dom_reset" class="<?php echo ($dom_selected?'':'hide'); ?>"><?php _e('Reset','alphabetic-pagination'); ?></a>
                                    <div class="ap_caption">
                                        <?php _e('This is the HTML element where the Alphabetical Pagination will be placed into.','alphabetic-pagination'); ?>
                                    </div>

                                </div>









                            </fieldset>

                        </div>

                    </div>

                </div>

            </div>

        <?php if(function_exists('ap_end_settings_form')){ap_end_settings_form();} ?>

        <?php if(!$ap_customp): ?>
            <a target="_blank" href="<?php echo esc_url($ap_premium_link); ?>">
                <img style="width:100%" src="<?php echo esc_url($ap_url); ?>images/ap-banner.png" />
            </a>
        <?php endif; ?>

    </div>


    <div class="nav-tab-content container-fluid hide styling" data-content="styling">

        <?php if(function_exists('ap_start_settings_form')){ap_start_settings_form();} ?>

            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="alphabets_section row">

                        
                            
                                <div class="alphabets_label"><?php _e('Styles','alphabetic-pagination'); ?>:</div>
                                <div class="alphabets_settings row">
                                    <?php
                                    //pree($ap_all_plugins);

                                    if(!array_key_exists('chameleon/index.php', $ap_all_plugins)){
                                        ?>
                                        <a style="line-height:26px; width:230px;" href="plugin-install.php?s=chameleon&tab=search&type=term" class="btn btn-sm btn-danger" target="_blank"><?php _e('Install Chameleon for Styles','alphabetic-pagination'); ?></a>
                                        <?php
                                    }elseif(!in_array('chameleon/index.php', $ap_plugins_activated)){
                                        ?>
                                        <a style="line-height:26px; width:230px;" href="plugins.php?plugin_status=inactive&s=chameleon" class="btn btn-sm btn-danger" target="_blank"><?php _e('Activate Chameleon for Styles','alphabetic-pagination'); ?></a>
                                        <?php
                                    }else{
                                    global $wpc_assets_loaded, $wpc_dir, $wpc_url, $wpc_supported;
                                    //pre($wpc_assets_loaded);
                                    $wp_chameleon = get_option( 'wp_chameleon');
                                    $short = 'ap';
                                    //pree($wpc_assets_loaded['ap']);
                                    //pree(ap_get_option('ap_style'));
                                    //pree($wp_chameleon['ap']);
                                    if(isset($wpc_assets_loaded[$short]) && !empty($wpc_assets_loaded[$short])){
                                        ksort($wpc_assets_loaded[$short]);
                                        //pree($wp_chameleon);
                                        ?>
                                        <select name="ap_style" id="ap_styles" class="apc_style">
                                            <option value=""><?php _e('Select','alphabetic-pagination'); ?></option>
                                            <?php
                                            foreach($wpc_assets_loaded[$short] as $style_name=>$style_data){
												
												//pree($style_data);

                                                if(function_exists('wpc_previews'))
                                                $wpc_previews = wpc_previews($wpc_supported[$short]['slug'], $style_name, $style_data, $short);
                                                //pree($wpc_supported[$short]['slug']. ' > ' .$style_name. ' > ' .$style_data. ' > ' .$short);

                                                $selected = ((isset($wp_chameleon[$short][$style_name]) && !empty($wp_chameleon[$short][$style_name]) && current($wp_chameleon[$short][$style_name])=='enabled')?$style_name:'');
												
												$style_data['images']['screenshot'] = isset($style_data['images']['screenshot'])?$style_data['images']['screenshot']:$style_data['images']['thumb'];

                                                ?>
                                                <option data-preview="<?php echo isset($style_data['images']['screenshot'])?str_replace($wpc_dir, $wpc_url, $style_data['images']['screenshot']):''; ?>" value="<?php echo esc_attr($style_name); ?>" <?php selected( $style_name, $selected ); ?>><?php echo ucwords(str_replace(array('_', '-'), ' ', $style_name)); ?></option>
                                                <?php
                                            }



                                            ?>




                                        </select>
                                        <small><a style="float:right" href="https://www.youtube.com/embed/I8IAnf8wFpw" target="_blank" class="btn btn-sm btn-info chameleon_links"><?php _e('Video Tutorial','alphabetic-pagination'); ?></a></small>

                                        <div class="ap_preview">
                                            <a href="" target="_blank">
                                                <img src="" />
                                            </a>
                                        </div>
                                        <?php
                                    }

                                    ?>
                                </div>

						
					</div>                        
				</div>                    
			</div>                
             <div class="row">
                <div class="col-md-12">
                    <div class="alphabets_section row">           
            
            
                                <div class="alphabets_label"><?php _e('Templates','alphabetic-pagination'); ?>:</div>
                                <div class="alphabets_settings row">
                                    <?php
                                    if(isset($wpc_assets_loaded['apt']) && !empty($wpc_assets_loaded['apt'])){
                                        //pree($wp_chameleon);
                                        ?>

                                        <select name="ap_template" id="ap_templates" class="apc_template">
                                            <option value=""><?php _e('Select','alphabetic-pagination'); ?></option>
                                            <?php
                                            foreach($wpc_assets_loaded['apt'] as $style_name=>$style_data){

                                                $selected = ((isset($wp_chameleon['apt'][$style_name]) && !empty($wp_chameleon['apt'][$style_name]) && current($wp_chameleon['apt'][$style_name])=='enabled')?$style_name:'');

                                                ?>
                                                <option data-preview="<?php echo str_replace($wpc_dir, $wpc_url, $style_data['images']['thumb']); ?>" value="<?php echo esc_attr($style_name); ?>" <?php selected( $style_name, $selected ); ?>><?php echo ucwords(str_replace(array('_', '-'), ' ', $style_name)); ?></option>
                                                <?php
                                            }



                                            ?>




                                        </select>


                                        <div class="apt_preview">
                                            <a href="" target="_blank">
                                                <img src="" />
                                            </a>
                                        </div>
                                        <?php
                                    }
                                    }
                                  
                                    ?>
                                </div>

                        </div>
                        
                </div>
            </div>                        
             <div class="row">
                <div class="col-md-12">
                    <div class="alphabets_section row">   
                    


                                <div class="alphabets_label"><?php _e('Alphabets in?','alphabetic-pagination'); ?></div>
                                <div class="alphabets_settings row">
                                    <fieldset>

<div class="btn-group  btn-group-sm btn-group-toggle" data-toggle="buttons">
    <label class="btn btn-warning" for="case_U">
        <input <?php echo (ap_get_option('ap_case')=='U'?'checked="checked"':''); ?> class="tog" id="case_U" value="U" type="radio" name="ap_case" autocomplete="off"> <?php _e('Uppercase','alphabetic-pagination'); ?>
    </label>
    <label class="btn btn-warning" for="case_L">
        <input <?php echo (ap_get_option('ap_case')=='L'?'checked="checked"':''); ?> class="tog" id="case_L" value="L" type="radio" name="ap_case" autocomplete="off" > <?php _e('Lowercase','alphabetic-pagination'); ?>
    </label>
</div>




<?php echo (ap_get_option('ap_case')=='L'?'':'<i class="fas fa-font"></i>'); ?>



                                    </fieldset>
                                </div>
		</div>
	</div>
</div>
             <div class="row">
                <div class="col-md-12">
                    <div class="alphabets_section row">  
                    
                                <div class="alphabets_label"><?php _e('Layout?','alphabetic-pagination'); ?></div>
                                <div class="alphabets_settings row">


                                    <fieldset>



<div class="btn-group  btn-group-sm btn-group-toggle" data-toggle="buttons">
    <label class="btn btn-warning" for="layout_H">
        <input type="radio" <?php echo (ap_get_option('ap_layout')=='H'?'checked="checked"':''); ?> class="tog" id="layout_H" value="H" name="ap_layout" autocomplete="off" /> <?php _e('Horizontal','alphabetic-pagination'); ?>
    </label>
    <label class="btn btn-warning" for="layout_V">
        <input type="radio" <?php echo (ap_get_option('ap_layout')=='V'?'checked="checked"':''); ?> class="tog" id="layout_V" value="V" name="ap_layout" autocomplete="off" /> <?php _e('Vertical','alphabetic-pagination'); ?>
    </label>
</div>

<?php echo (ap_get_option('ap_layout')=='V'?'<i class="fas fa-arrows-alt-v"></i>':'<i class="fas fa-arrows-alt-h"></i>'); ?>                                   



                                    </fieldset>
                                </div>




                    </div>
                </div>
            </div>

<div class="row">
                <div class="col-md-12">
                    <?php
                    echo alphabets_bar();
                    //ap_ready();
                    ?>
                </div>
            </div>
            <div class="row numeric_reset">
                <div class="col-md-6 alphabets_section">


                            <div class="ap_numeric_label"><b><?php echo __('Numeric sign','alphabetic-pagination').' "#" '.__('visibility in pagination','alphabetic-pagination'); ?>?</b></div>

                            <fieldset>
                      
                                
<div class="btn-group  btn-group-sm btn-group-toggle" data-toggle="buttons">
    <label class="btn btn-warning" for="ap_numeric_sign_yes">
        <input type="radio" <?php echo ($ap_numeric_sign?'checked="checked"':''); ?> class="tog" id="ap_numeric_sign_yes" value="1" name="ap_numeric_sign" autocomplete="off"> <?php _e('Yes','alphabetic-pagination'); ?>
    </label>
    <label class="btn btn-warning" for="ap_numeric_sign_no">
        <input type="radio" <?php echo ($ap_numeric_sign?'':'checked="checked"'); ?> class="tog" id="ap_numeric_sign_no" value="0" name="ap_numeric_sign"> <?php _e('No','alphabetic-pagination'); ?>
    </label>
</div>

<?php echo ($ap_numeric_sign?'<i class="fas fa-sort-numeric-up-alt"></i>':''); ?>
                            </fieldset>


                </div>

                <div class="col-md-6 alphabets_section">

                  
                        <div class="ap_reset_sign_label"><b><?php _e('View All','alphabetic-pagination'); ?>/<?php _e('Refresh','alphabetic-pagination'); ?> "<img src="<?php echo plugin_dir_url( dirname(__FILE__) ); ?>images/reset-<?php echo (in_array($ap_reset_theme, array('dark', 'light'))?$ap_reset_theme:'dark'); ?>.png" data-light="<?php echo plugin_dir_url( dirname(__FILE__) ); ?>images/reset-light.png" data-dark="<?php echo plugin_dir_url( dirname(__FILE__) ); ?>images/reset-dark.png"  />" <?php _e('icon visibility','alphabetic-pagination'); ?>?</b></div>

                        <fieldset>
                        
<div class="btn-group  btn-group-sm btn-group-toggle" data-toggle="buttons">
    <label class="btn btn-warning" for="ap_reset_sign_yes">
        <input type="radio" <?php echo ($ap_reset_sign?'checked="checked"':''); ?> class="tog" id="ap_reset_sign_yes" value="1" name="ap_reset_sign" autocomplete="off"> <?php _e('Yes','alphabetic-pagination'); ?>
    </label>
    <label class="btn btn-warning" for="ap_reset_sign_no">
        <input type="radio" <?php echo ($ap_reset_sign?'':'checked="checked"'); ?> class="tog" id="ap_reset_sign_no" value="0" name="ap_reset_sign"> <?php _e('No','alphabetic-pagination'); ?>
    </label>
</div>                        
<?php echo ($ap_reset_sign?'<i class="fas fa-redo-alt"></i>':'<i class="fas fa-minus"></i>'); ?>  

<div style="clear:both; margin:30px 0 0 0">
<div class="btn-group  btn-group-sm btn-group-toggle" data-toggle="buttons">
    <label class="btn btn-warning" for="ap_reset_theme_dark">
        <input type="radio" <?php checked($ap_reset_theme=='dark'); ?> class="tog" id="ap_reset_theme_dark" value="dark" name="ap_reset_theme" autocomplete="off"> <?php _e('Dark','alphabetic-pagination'); ?>
    </label>
    <label class="btn btn-warning" for="ap_reset_theme_light">
        <input type="radio" <?php checked($ap_reset_theme=='light'); ?> class="tog" id="ap_reset_theme_light" value="light" name="ap_reset_theme"> <?php _e('Light','alphabetic-pagination'); ?>
    </label>
</div>                   
</div>     



                        </fieldset>


                </div>
            </div>

            

            <div class="row ap_vertical_adj">
                <div class="col-md-6 alphabets_section">


                        <div class="ap_disable_label"><b><?php _e('Disable Empty Alphabets?','alphabetic-pagination'); ?></b></div>

                        <fieldset>
                        
                            
<div class="btn-group  btn-group-sm btn-group-toggle" data-toggle="buttons">
    <label class="btn btn-warning" for="ap_disable_yes">
        <input type="radio" <?php echo ($ap_disable==1?'checked="checked"':''); ?> class="tog" id="ap_disable_yes" value="1" name="ap_disable" autocomplete="off"> <?php _e('Yes','alphabetic-pagination'); ?>
    </label>
    <label class="btn btn-warning" for="ap_disable_no">
        <input type="radio" <?php echo ($ap_disable==1?'':'checked="checked"'); ?> class="tog" id="ap_disable_no" value="0" name="ap_disable" autocomplete="off" > <?php _e('No','alphabetic-pagination'); ?>
    </label>
</div>

<?php echo ($ap_disable==1?'<i class="fab fa-buromobelexperte"></i>':'<i class="fas fa-th"></i>'); ?>    
                            
                        </fieldset>



                </div>
				<div class="col-md-6 alphabets_section">


                        <div class="ap_grouping_label"><b><?php _e('Alphabets Grouping?','alphabetic-pagination'); ?></b></div>

                        <fieldset>
                                                        
<div class="btn-group  btn-group-sm btn-group-toggle" data-toggle="buttons">
    <label class="btn btn-warning" for="ap_group_yes">
        <input type="radio" <?php echo ($ap_group==1?'checked="checked"':''); ?> class="tog" id="ap_group_yes" value="1" name="ap_grouping" autocomplete="off"> <?php _e('Yes','alphabetic-pagination'); ?>
    </label>
    <label class="btn btn-warning" for="ap_group_no">
        <input type="radio" <?php echo ($ap_group==1?'':'checked="checked"'); ?> class="tog" id="ap_group_no" value="0" name="ap_grouping" autocomplete="off" > <?php _e('No','alphabetic-pagination'); ?>
    </label>
</div>
<?php echo ($ap_group?'<i class="fas fa-ellipsis-h"></i>':'<i class="fas fa-ellipsis-h faded"></i>'); ?>
                                      
                        </fieldset>

               </div>

            
                
            </div>

            <div class="row ap_vertical_adj">
                <div class="col-md-6 alphabets_section">

                        <div class="ap_grouping_label">
                            <b>
                                <?php _e('Hide/Show pagination if only one post available?','alphabetic-pagination'); ?>
                            </b>
                        </div>

                        <div class="" id="front-static-pages">

                            <fieldset>

                                
<div class="btn-group  btn-group-sm btn-group-toggle" data-toggle="buttons">
    <label class="btn btn-warning" for="signle_hide">
        <input type="radio" <?php echo (ap_get_option('ap_single')==0?'checked="checked"':''); ?> id="signle_hide" value="0" name="ap_single" autocomplete="off"> <?php _e('Hide','alphabetic-pagination'); ?>
    </label>
    <label class="btn btn-warning" for="signle_show">
        <input type="radio" <?php echo (ap_get_option('ap_single')==1?'checked="checked"':''); ?> id="signle_show" value="1" name="ap_single" autocomplete="off" > <?php _e('Show','alphabetic-pagination'); ?>
    </label>
</div>
                                          

                            </fieldset>

                        </div>

                        

                </div>
                
				<div class="col-md-6 alphabets_section">


                        

                        <div class="ap_grouping_label">
                            <b>
                                <?php _e('Language selection?','alphabetic-pagination'); ?>
                            </b>
                        </div>


                        <?php //pree($ap_langs);
                        if(!empty($ap_langs)): ksort($ap_langs);  ?>

                            <fieldset>

                                <div>
                                    <select class="ap_langs" name="ap_lang[]" id="ap_lang_selector">

                                        <?php foreach($ap_langs as $titles=>$letters):
                                            $lang = ucwords($titles);

                                            ?>
                                            <option value="<?php echo esc_attr($lang); ?>" <?php echo (($lang && in_array($lang, $stored_langs)) || (empty($stored_langs) && $lang=='English'))?'selected="selected"':''; ?>><?php echo esc_html($lang); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <i class="fas fa-language"></i>

                            </fieldset>
                            
                            

                        <?php endif; ?>

                  

                </div>                
            </div>
            


        <?php if(function_exists('ap_end_settings_form')){ap_end_settings_form('ap_vertical_adj');} ?>


    </div>

    <div class="nav-tab-content container-fluid hide shortcodes" data-content="shortcodes">
        <?php if(function_exists('ap_start_settings_form')){ap_start_settings_form();} ?>
        <div class="row mt-5">
            <div class="col-md-12 alphabets_section">
                <div class="ap_shortcode">
                    <h4><?php _e('Shortcodes','alphabetic-pagination'); ?>:</h4>

                    <code>
                        [ap_pagination]
                    </code>
                    <div>
                        or
                    </div>
                    <code>
                        &lt;?php echo do_shortcode('[ap_pagination]'); ?&gt;
                    </code>
                    <div>&nbsp;</div>



                    <h4><?php _e('Additional Shortcodes','alphabetic-pagination'); ?>:</h4>

                    <code>[ap_results class=&quot;ap_results&quot; type=&quot;users_list&quot;]</code><div>&nbsp;</div>

                    <code>[ap_results class=&quot;ap_results&quot; type=&quot;content_list&quot; thumb=&quot;false&quot; post_type=&quot;page&quot; post_parent=&quot;0&quot; get_children=&quot;false&quot;]</code><div>&nbsp;</div>

                    <code>[ap_pagination type="jquery" wrapper="#primary #content article" item="header &gt; h1 &gt; a"] <a class="video-tutorial" href="https://www.youtube.com/embed/23DPJOrY2zY" target="_blank"><i class="fab fa-youtube"></i></a></code><div>&nbsp;</div>

                    <code>[ap_pagination type=&quot;jquery&quot; wrapper=&quot;#content .ap_results .ap-citem&quot; item=&quot;a strong&quot;]</code><div>&nbsp;</div>
                    
                    <code>[ap_pagination type=&quot;jquery&quot; wrapper=&quot;#content .ap_results .ap-citem&quot; item=&quot;a strong&quot; filter=&quot;yes&quot; separator=&quot;yes&quot; itemseparator=&quot;.acadp-divider&quot;]</code><div>&nbsp;</div>

                    <code>[ap_results class=&quot;ap_results&quot; type=&quot;content_list&quot; custom-link=&quot;post_meta_key&quot; get_children=&quot;false&quot;]</code><div>&nbsp;</div>

                    <code>[ap_results class=&quot;ap_results&quot; type=&quot;content_list&quot; category_ids=&quot;20,21,22&quot;]</code>
					<div>&nbsp;</div>
                    
                    <h4><?php _e('Taxonomy Related Shortcodes','alphabetic-pagination'); ?>:</h4>
                    
                    <code>[ap_pagination type=&quot;jquery&quot; wrapper=&quot;.ap_categories div.ap-items-group&quot; item=&quot;.ap-label&quot;]<br />
					[ap_results class=&quot;ap_categories&quot; type=&quot;category_group&quot; exclude=&quot;&quot; include=&quot;&quot; thumb=&quot;true&quot; taxonomy=&quot;product_cat&quot; thumb_field=&quot;thumbnail_id&quot; thumb_list=&quot;false&quot; thumb_strip=&quot;false&quot;]</code><div>&nbsp;</div>

                    <code>[ap_pagination type=&quot;jquery&quot; wrapper=&quot;.ap_categories ul li.ap-citem&quot; item=&quot;strong&quot;]<br />
					[ap_results class=&quot;ap_categories&quot; type=&quot;category_list&quot; exclude=&quot;&quot; include=&quot;&quot; thumb=&quot;true&quot; taxonomy=&quot;product_cat&quot; thumb_field=&quot;thumbnail_id&quot; thumb_list=&quot;false&quot; thumb_strip=&quot;false&quot;]</code><div>&nbsp;</div>


				  <h4><?php _e('Action/Filter Hooks','alphabetic-pagination'); ?>:</h4>
                                        <code style="text-align:left;">add_action('ap_reset_items_list_javascript', '<?php echo esc_html($theme_name); ?>_reset_items_list_javascript_callback', 10);<br /><br />
if(!function_exists('<?php echo esc_html($theme_name); ?>_reset_items_list_javascript_callback')){<br />
		&emsp;&emsp;function <?php echo esc_html($theme_name); ?>_reset_items_list_javascript_callback(){<br />
			&emsp;&emsp;&emsp;echo '<?php echo esc_html($theme_name); ?>_reset_items_list_javascript_func();';<br />
		&emsp;&emsp;}<br />
	}<br />
	                    
              </code>

                    <code style="text-align:left;">add_action('ap_item_selected_javascript', '<?php echo esc_html($theme_name); ?>_item_selected_javascript_callback', 10);<br /><br />
	if(!function_exists('<?php echo esc_html($theme_name); ?>_item_selected_javascript_callback')){<br />
		&emsp;&emsp;function <?php echo esc_html($theme_name); ?>_item_selected_javascript_callback(){<br />
			&emsp;&emsp;&emsp;echo '<?php echo esc_html($theme_name); ?>_item_selected_javascript_func();';<br />
		&emsp;&emsp;}<br />
	}	
                    </code>

                    <code style="text-align:left;">add_action('init', '<?php echo esc_html($theme_name); ?>_add_page_cats', 10);<br /><br />
	if(!function_exists('<?php echo esc_html($theme_name); ?>_add_page_cats')){<br />
		&emsp;&emsp;function <?php echo esc_html($theme_name); ?>_add_page_cats(){<br />
			&emsp;&emsp;&emsp;&emsp;register_taxonomy_for_object_type('post_tag', 'page');<br />
			&emsp;&emsp;&emsp;&emsp;register_taxonomy_for_object_type('category', 'page'); <br />
		&emsp;&emsp;}<br />
	}	<br /><br />
    
    function <?php echo esc_html($theme_name); ?>_custom_query( $query ) {<br />
		&emsp;&emsp;if (!is_admin() && $query->is_archive() && $query->is_main_query() ) {<br />
			&emsp;&emsp;&emsp;&emsp;$query->set( 'post_type', array('post', 'page') );<br />
			&emsp;&emsp;&emsp;&emsp;$query->set( 'orderby', 'title' );<br />
			&emsp;&emsp;&emsp;&emsp;$query->set( 'order', 'ASC' );<br />
		&emsp;&emsp;}<br />
    }<br />
    add_filter( 'pre_get_posts', '<?php echo esc_attr($theme_name); ?>_custom_query' );  <br />
    
    
	<br /><br />
    
    function <?php echo esc_html($theme_name); ?>ap_results_content_filter_callback($content, $link, $thumb, $post){<br />
    <br />
&emsp;&emsp;&emsp;&emsp;$content = ($content?$content:$post-&gt;post_content);<br />
&emsp;&emsp;&emsp;&emsp;$content = wp_trim_words( $content, 40, '...' );<br />
<br />
return $content;<br />
}
    add_filter('ap-results-content-filter', '<?php echo esc_attr($theme_name); ?>_ap_results_content_filter_callback', 10, 4);<br /><br />

    add_filter('ap-results-title-filter', '<?php echo esc_attr($theme_name); ?>_ap_results_title_filter_callback', 10, 4);<br /><br />
                    </code>
                    
                    

                  

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 p-0 mb-4">
                <?php if($ap_customp && function_exists ( "is_woocommerce" )): ?>
                    <div class="ap_wc_div">
                        <div class="ap_wc_label"><b><?php _e('WooCommerce Shortcodes?','alphabetic-pagination'); ?></b></div>
                        <small><?php echo __('If your theme is using WooCommerce Shortcodes so default filters might will not work.','alphabetic-pagination').' '.__('Please select Yes to make it work with shortcodes.','alphabetic-pagination'); ?></small>
                        <fieldset>
          

<div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons">
    <label class="btn btn-danger" for="ap_wc_yes">
        <input type="radio" <?php echo ($ap_wc_shortcodes?'checked="checked"':''); ?> class="tog" id="ap_wc_yes" value="1" name="ap_wc_shortcodes" autocomplete="off"> <?php _e('Yes','alphabetic-pagination'); ?>
    </label>
    <label class="btn btn-danger" for="ap_wc_no">
        <input type="radio" <?php echo ($ap_wc_shortcodes?'':'checked="checked"'); ?> class="tog" id="ap_wc_no" value="0" name="ap_wc_shortcodes" autocomplete="off" > <?php _e('No','alphabetic-pagination'); ?>
    </label>
</div>                            
                        </fieldset>

                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if(function_exists('ap_end_settings_form')){ap_end_settings_form();} ?>

    </div>


    <div class="nav-tab-content container-fluid hide permissions" data-content="permissions">

        <?php if(function_exists('ap_start_settings_form')){ap_start_settings_form();} ?>

            <div class="row mt-5">
                <div class="col-md-12 alphabets_section">

                    <div class="row">
                        <div class="col-md-6 ap_side_label py-3">

 							<div class="">
                                <iframe class="mt-3 w-100" height="300" src="https://www.youtube.com/embed/q6mUKDinrW8" frameborder="0" allowfullscreen></iframe>
                            </div>                           

                        </div>
                        
                        <div class="col-md-6">
							<div class="">
                                <?php _e('Default Query Number','alphabetic-pagination'); ?>
                               

                            </div>
                            <div class="">

                                <fieldset>
						
                        <input placeholder="Query Number" class="query_number" type="number" name="ap_query[default]" value="<?php echo (array_key_exists('default', $ap_query_number)?$ap_query_number['default']:''); ?>" />
                        <a class="float-right" href="https://developer.wordpress.org/reference/functions/is_main_query/" target="_blank"><i class="fas fa-database"></i></a>
                        
		                        </fieldset>
							</div>                                
                            
							<div class="">
                                <?php _e('Allowed Pages?','alphabetic-pagination'); ?>
                               

                            </div>
                            <div class="">

                                <fieldset>


                                        <select class="ap_allowed_pages" name="ap_allowed_pages[]" id="ap_allowed_pages" multiple="multiple">


                                            <option value=""><?php _e('Default','alphabetic-pagination'); ?></option>
                                            <?php
                                            foreach($allowed_pages as $apages){
                                                ?>
                                                <option <?php echo (is_array($ap_allowed_pages) && in_array($apages->ID, $ap_allowed_pages))?'selected="selected"':''; ?> value="<?php echo esc_attr($apages->ID); ?>"><?php echo esc_attr($apages->post_title); ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <small class="my-2 d-block"><?php _e('Note: Auto works with only archives, custom can work with any page including archives.','alphabetic-pagination'); ?></small><br />
</fieldset>

<fieldset>

                                    <?php
                                    foreach($allowed_pages as $apages){
                                        ?>



                                            <input placeholder="Query Number" class="query_number hide" type="number" name="ap_query[<?php echo esc_attr($apages->ID); ?>]" value="<?php echo (array_key_exists($apages->ID, $ap_query_number)?$ap_query_number[$apages->ID]:''); ?>" />
											

                                        <?php if(!empty($get_post_types) && is_array($ap_post_types)): ?>
                                            <select id="ap_post_types_<?php echo esc_attr($apages->ID); ?>" name="ap_post_types[<?php echo esc_attr($apages->ID); ?>][]" class="ap_post_types hide" multiple="multiple">
                                                <?php foreach($get_post_types as $key=>$val): ?>
                                                    <option value="<?php echo esc_attr($key); ?>" <?php echo (is_array($ap_post_types) && isset($ap_post_types[$apages->ID]) && is_array($ap_post_types[$apages->ID]) && array_key_exists($apages->ID, $ap_post_types) && in_array($key, $ap_post_types[$apages->ID]))?'selected="selected"':''; ?>><?php echo ucwords(str_replace(array('_'), ' ', $val)); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php endif; ?>

                                        <?php
                                    }
                                    ?>
                                    

                                    <br>

                                    <small>
                                        <?php echo __('Note: For pages, you should not use the main query.','alphabetic-pagination').' '.__('Try numbers from','alphabetic-pagination').' 2 '.__('to','alphabetic-pagination').' 6'; ?>. <?php echo __('Recommended','alphabetic-pagination').': 3'; ?>
                                    </small>
<a class="float-right" href="https://developer.wordpress.org/reference/functions/is_main_query/" target="_blank"><i class="fas fa-database"></i></a>
                                </fieldset>

                            </div>


                        </div>
                    </div>

                </div>
            </div>

        <?php if(function_exists('ap_end_settings_form')){ap_end_settings_form();} ?>

    </div>
	
    <div class="nav-tab-content container-fluid hide" data-content="logs">

        <div class="row mt-3 logs_section">
        
        	<?php if(function_exists('ap_debug_logger_display')?ap_debug_logger_display():''); ?>
        	                
        </div>

    </div>

    <div class="nav-tab-content container-fluid hide" data-content="help">

        <div class="row mt-3 alphabets_section">
        
        	<ul class="position-relative">
            	<li><a class="btn btn-sm btn-info" href="https://wordpress.org/support/plugin/alphabetic-pagination/" target="_blank"><?php _e('Open a Ticket on Support Forums', 'alphabetic-pagination'); ?> &nbsp;<i class="fas fa-tag"></i></a></li>
                <li><a class="btn btn-sm btn-warning" href="http://demo.androidbubble.com/contact/" target="_blank"><?php _e('Contact Developer', 'alphabetic-pagination'); ?> &nbsp;<i class="fas fa-headset"></i></a></li>
                <li><a class="btn btn-sm btn-secondary" href="<?php echo esc_url($ap_premium_link); ?>/?help" target="_blank"><?php _e('Need Urgent Help?', 'alphabetic-pagination'); ?> &nbsp;<i class="fas fa-phone"></i></i></a></li>
                <li><iframe width="560" height="315" src="https://www.youtube.com/embed/N-ewX28pLXs" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></li>
			</ul>                
        </div>

    </div>



</div>



<script type="text/javascript" language="javascript">
jQuery(document).ready(function($) {


    <?php if (isset($_GET['t']) || isset($_POST['ap_tn'])):


    $ap_tn = isset($_POST['ap_tn']) ? sanitize_ap_data($_POST['ap_tn']) : sanitize_ap_data($_GET['t']);

    ?>
    $('.nav-tab-wrapper .nav-tab[data-tab="<?php echo  $ap_tn;?>"]').click();

    <?php endif; ?>

	setInterval(function(){ jQuery('.useful_link').fadeTo('slow', 0).fadeTo('slow', 1.0);



	}, 1000*60);
	
	jQuery('#dom_selector').click(function(){
		if(jQuery(this).val()=='Custom'){

			if($(this).parent().find('input[name="ap_dom"]').length==0){	
				$(this).parent().append('<input type="text" name="ap_dom" value="<?php echo ap_get_option('ap_dom'); ?>" />');
			}
			
			$(this).remove();
		}
	});
	
	if($('#adminmenu li.current a.current').length>0){
		var title = $('#adminmenu li.current a.current').html();
		title = title.split(' ');
		var updated_title = title[0]+' <span>'+title[1]+'</span>';
		$('#adminmenu li.current a.current').html(updated_title);
	}
	
	

	$('#dom_custom').click(function(){
		
		
		$('#dom_selector').hide();
		if($(this).parents().eq(1).find('input[name="ap_dom"]').length==0){
			$('#dom_selector').parent().find('#dom_reset').before('<input type="text" name="ap_dom" value="<?php echo ap_get_option('ap_dom'); ?>" />');	
			
		}
		$('.dom_options').hide();
		$('#dom_reset').show();
		
	});	
	
	jQuery('#dom_default').click(function(){
		
		jQuery(this).parent().hide();
		jQuery('#dom_selector').show();
		jQuery('#dom_reset').show();
		
	});
	
	jQuery('#dom_reset').click(function(){
		jQuery(this).hide();
		jQuery('.dom_opt').hide();
		jQuery('.dom_options').show();
		jQuery('#dom_selector').parent().find('input[name="ap_dom"]').remove();
		
	});
	
	jQuery('#ap_all_no').click(function(){
		jQuery('.ap_tax_div').slideDown();
	});
	jQuery('#ap_all_yes').click(function(){
		jQuery('.ap_tax_div').slideUp();
	});

	$('input.ap_imp').click(function(){
		switch($(this).val()){
			case '<?php echo AP_CUSTOM; ?>':
				// jQuery('div.ap_shortcode, div.ap_allowed_pages_div').slideDown('slow');
				//$('.ap_auto_more').hide();
				ap_premium_alert(true);
			break;
			default:
				// jQuery('div.ap_shortcode, div.ap_allowed_pages_div').slideUp('slow');
				//$('.ap_auto_more').show();
				ap_premium_alert(false);
			break;
		}
	
	});
	

	

    $('input[name="ap_layout"]').click(function(){
		$(this).parents().eq(2).find('i').remove();
		switch($(this).attr('id')){
			case 'layout_H':
				$(this).parents().eq(2).append('<i class="fas fa-arrows-alt-h"></i>');
			break;
			case 'layout_V':
				$(this).parents().eq(2).append('<i class="fas fa-arrows-alt-v"></i>');
			break;
		}
	});
	
    $('input[name="ap_grouping"]').click(function(){
		$(this).parents().eq(2).find('i').remove();
		switch($(this).attr('id')){
			case 'ap_group_yes':
				$(this).parents().eq(2).append('<i class="fas fa-ellipsis-h"></i>');
			break;
			case 'ap_group_no':
				$(this).parents().eq(2).append('<i class="fas fa-ellipsis-h faded"></i>');
			break;
		}
	});	
	
	
    $('input[name="ap_numeric_sign"]').click(function(){
		$(this).parents().eq(2).find('i').remove();
		switch($(this).attr('id')){
			case 'ap_numeric_sign_yes':
				$('li.ap_numeric').show();
				$(this).parents().eq(2).append('<i class="fas fa-sort-numeric-up-alt"></i>');
			break;
			case 'ap_numeric_sign_no':
				$('li.ap_numeric').hide();
			break;
		}
	});	
		
    $('input[name="ap_reset_sign"]').click(function(){
		$(this).parents().eq(2).find('i').remove();
		switch($(this).attr('id')){
			case 'ap_reset_sign_yes':
				$(this).parents().eq(2).append('<i class="fas fa-redo-alt"></i>');
			break;
			case 'ap_reset_sign_no':
				$(this).parents().eq(2).append('<i class="fas fa-minus"></i>');
			break;
		}
	});	
	
    $('input[name="ap_disable"]').click(function(){
		$(this).parents().eq(2).find('i').remove();
		switch($(this).attr('id')){
			case 'ap_disable_yes':
				$(this).parents().eq(2).append('<i class="fab fa-buromobelexperte"></i>');
			break;
			case 'ap_disable_no':
				$(this).parents().eq(2).append('<i class="fas fa-th"></i>');
			break;
		}
	});	
	

    jQuery('input[name="ap_layout"]:checked').change();
        
    jQuery('input[name="ap_case"]').click(function(){
		$('.ap_pagination').removeClass('case_U');
		$('.ap_pagination').removeClass('case_L');
		$('.ap_pagination').addClass($(this).attr('id'));
		$(this).parents().eq(2).find('i').remove();
		switch($(this).attr('id')){
			case 'case_U':
				$(this).parents().eq(2).append('<i class="fas fa-font"></i>');
			break;
			case 'case_L':
				
			break;
		}		
	});
	
	
	
	jQuery('select[name="ap_style"]').change(function(){
                               
		$('.ap_pagination').removeClass('<?php echo esc_attr($ap_classes); ?>').addClass(jQuery(this).val());
     });
		
	jQuery('input.ap_imp:checked').click();
	
	
	$('select.apc_style').on('change keyup', function(){
		
		var preview = $(this).find('option:selected').data('preview');
		$('.ap_preview a').attr('href', preview);
		$('.ap_preview img').attr('src', preview);
		if($(this).val()==''){
			$('.ap_preview').hide();
		}else{
			$('.ap_preview').show();
		}
		
	});	
	
	
	$('select.apc_template').on('change keyup', function(){

		var preview = $(this).find('option:selected').data('preview');
		$('.apt_preview a').attr('href', preview);
		$('.apt_preview img').attr('src', preview);
		if($(this).val()==''){
			$('.apt_preview').hide();
		}else{
			$('.apt_preview').show();
		}
		
	});		
	
	
	
	
	setTimeout(function(){
		if($('select.apc_style').length>0){
			$('select.apc_style').change();
		}
		
		if($('select.apc_template').length>0){
			$('select.apc_template').change();
		}		
	}, 1000);
	
});	
</script>

<style type="text/css">
<?php echo wp_kses_post(implode('', $css_arr)); ?>
	#wpfooter{
		display:none;
	}
<?php if(!$ap_customp): ?>

	#adminmenu li.current a.current {
		font-size: 12px !important;
		font-weight: bold !important;
		padding: 6px 0px 6px 12px !important;
	}
	#adminmenu li.current a.current,
	#adminmenu li.current a.current span:hover{
		color:#25bcf0;
	}
	#adminmenu li.current a.current:hover,
	#adminmenu li.current a.current span{
		color:#fc5151;
	}	
<?php endif; ?>
	.woocommerce-message, .update-nag, #message, .notice.notice-error, .error.notice, div.notice, div.fs-notice, div.wrap > div.updated{ display:none !important; }
</style>
