<?php
/*
 * Plugin Name: LSD Custom taxonomy and category meta
 * Description: Add the ability to attach meta to the Wordpress categories
 * Version: 1.3.2
 * Author: Bas Matthee
 * Author URI: http://www.twitter.com/BasMatthee
 *
 * This plugin has been developped and tested with Wordpress Version 3.9.1
 *
 * Copyright 2014  Bas Matthee (@BasMatthee)
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 *
 */

if (!defined('WP_CONTENT_DIR')) {
    
    define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
    
}

if (!defined('DIRECTORY_SEPARATOR')) {
    
    if (strpos(php_uname('s'), 'Win') !== false) {
        
        define('DIRECTORY_SEPARATOR', '\\');
        
    } else {
        
        define('DIRECTORY_SEPARATOR', '/');
        
    }
}

$pluginPath = ABSPATH.PLUGINDIR.DIRECTORY_SEPARATOR."custom-taxonomy-category-and-term-fields";
define('WPTM_PATH', $pluginPath);
$filePath = $pluginPath.DIRECTORY_SEPARATOR.basename(__FILE__);
$asolutePath = dirname(__FILE__).DIRECTORY_SEPARATOR;
define('WPTM_ABSPATH', $asolutePath);

// Initialization and Hooks
global $wpdb;
global $wp_version;
global $wptm_version;
global $wptm_db_version;
global $wptm_table_name;
global $wp_version;

$wptm_version       = '1.3.1';
$wptm_db_version    = '0.0.1';
$wptm_table_name    = $wpdb->prefix.'termsmeta';

register_activation_hook($filePath,'wptm_install');

if ($wp_version >= '2.7') {
    
    register_uninstall_hook($filePath,'wptm_uninstall');
    
} else {
    
    register_deactivation_hook($filePath,'wptm_uninstall');
    
}

// Actions & Filters
add_action('admin_init', 'wptm_init');
add_filter('admin_enqueue_scripts','wptm_admin_enqueue_scripts');

if (is_admin()) {
    
    include WPTM_ABSPATH.'views'.DIRECTORY_SEPARATOR.'options.php';
    
    $WPTMAdmin = new wptm_admin();
    
}

/**
 * Function called when installing or updgrading the plugin.
 * @return void.
 */
function wptm_install() {
    
    global $wpdb;
    global $wptm_table_name;
    global $wptm_db_version;

    // create table on first install
    if ($wpdb->get_var("show tables like '$wptm_table_name'") != $wptm_table_name) {

        wptm_createTable($wpdb, $wptm_table_name);
        add_option("wptm_db_version", $wptm_db_version);
        add_option("wptm_configuration", '');
        
    }

    // On plugin update only the version nulmber is updated.
    $installed_ver = get_option( "wptm_db_version" );
    
    if ($installed_ver != $wptm_db_version) {

        update_option( "wptm_db_version", $wptm_db_version );
        
    }

}

/**
 * Function called when un-installing the plugin.
 * @return void.
 */
function wptm_uninstall() {
    
    global $wpdb;
    global $wptm_table_name;

    // delete table
    if($wpdb->get_var("show tables like '$wptm_table_name'") == $wptm_table_name) {

        wptm_dropTable($wpdb, $wptm_table_name);
    }
    
    delete_option("wptm_db_version");
    delete_option("wptm_configuration");
    
}

/**
 * Function that creates the wptm table.
 *
 * @param $wpdb : database manipulation object.
 * @param $table_name : name of the table to create.
 * @return void.
 */
function wptm_createTable($wpdb, $table_name) {
    
    $sql = "CREATE TABLE  ".$table_name." (
          meta_id bigint(20) NOT NULL auto_increment,
          terms_id bigint(20) NOT NULL default '0',
          meta_key varchar(255) default NULL,
          meta_value longtext,
          PRIMARY KEY  (`meta_id`),
          KEY `terms_id` (`terms_id`),
          KEY `meta_key` (`meta_key`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
    
    $results = $wpdb->query($sql);
    
}

/**
 * Function that drops the plugin table.
 *
 * @param $wpdb : database manipulation object.
 * @param $table_name : name of the table to create.
 * @return void.
 */
function wptm_dropTable($wpdb, $table_name) {
    
    $sql = "DROP TABLE  ".$table_name." ;";

    $results = $wpdb->query($sql);
    
}

/**
 * Function that initialise the plugin.
 * It loads the translation files.
 *
 * @return void.
 */
function wptm_init() {
    
    global $wp_version;
    
    if (function_exists('load_plugin_textdomain')) {
        
    	load_plugin_textdomain('wp-category-meta', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/');
               
    } else {
        
        // Load language file
        $locale = get_locale();
        
        if ( !empty($locale) ) {
            
            load_textdomain('wp-category-meta', WPTM_ABSPATH.'lang'.DIRECTORY_SEPARATOR.'custom-taxonomy-category-and-term-fields-'.$locale.'.mo');
            
        }
        
    }
    
    if ($wp_version >= '3.0') {
        
        add_action('created_term', 'wptm_save_meta_tags');
        add_action('edit_term', 'wptm_save_meta_tags');
        add_action('delete_term', 'wptm_delete_meta_tags');
        
        $wptm_taxonomies = get_taxonomies('','names');
        
        if (is_array($wptm_taxonomies) ) {
            
            foreach ($wptm_taxonomies as $wptm_taxonomy ) {
                
                add_action($wptm_taxonomy . '_add_form_fields', 'wptm_add_meta_textinput');
                add_action($wptm_taxonomy . '_edit_form', 'wptm_add_meta_textinput');
                
            }
            
        }
        
    } else {
        
        add_action('create_category', 'wptm_save_meta_tags');
        add_action('edit_category', 'wptm_save_meta_tags');
        add_action('delete_category', 'wptm_delete_meta_tags');
        add_action('edit_category_form', 'wptm_add_meta_textinput');
        
    }
    
}

/**
 * Add the loading of needed javascripts for admin part.
 *
 */
function wptm_admin_enqueue_scripts() {
    
    if (is_admin() && isset($_REQUEST["taxonomy"])) {
        
        wp_register_style('thickbox-css', '/wp-includes/js/thickbox/thickbox.css');
        wp_enqueue_style('thickbox-css');
        
        wp_enqueue_script('thickbox');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('quicktags');
        wp_enqueue_script('wp-category-meta-scripts','/wp-content/plugins/custom-taxonomy-category-and-term-fields/js/wp-category-meta-scripts.js');
        
    }
    
}

/**
 * add_terms_meta() - adds metadata for terms
 *
 *
 * @param int $terms_id terms (category/tag...) ID
 * @param string $key The meta key to add
 * @param mixed $value The meta value to add
 * @param bool $unique whether to check for a value with the same key
 * @return bool
 */
function add_terms_meta($terms_id, $meta_key, $meta_value, $unique = false) {

    global $wpdb;
    global $wptm_table_name;

    // expected_slashed ($meta_key)
    $meta_key   = stripslashes($meta_key);
    $meta_value = stripslashes($meta_value);
    
    if ($unique && $wpdb->get_var($wpdb->prepare("SELECT meta_key FROM $wptm_table_name WHERE meta_key = %s AND terms_id = %d", $meta_key, $terms_id ))) {
        
        return false;
        
    }

    $meta_value = maybe_serialize($meta_value);
    
    $wpdb->insert($wptm_table_name,compact('terms_id', 'meta_key', 'meta_value') );

    wp_cache_delete($terms_id, 'terms_meta');

    return true;
    
}

/**
 * delete_terms_meta() - delete terms metadata
 *
 *
 * @param int $terms_id terms (category/tag...) ID
 * @param string $key The meta key to delete
 * @param mixed $value
 * @return bool
 */
function delete_terms_meta($terms_id, $key, $value = '') {

    global $wpdb;
    global $wptm_table_name;

    // expected_slashed ($key, $value)
    $key    = stripslashes($key);
    $value  = stripslashes($value);

    if (empty($value)) {
        
        $sql = $wpdb->prepare("SELECT meta_id FROM $wptm_table_name WHERE terms_id = %d AND meta_key = %s", $terms_id, $key );
        $meta_id = $wpdb->get_var($sql);
        
    } else {
        
        $sql = $wpdb->prepare("SELECT meta_id FROM $wptm_table_name WHERE terms_id = %d AND meta_key = %s AND meta_value = %s", $terms_id, $key, $value );
        $meta_id = $wpdb->get_var($sql);
        
    }

    if (!$meta_id) {
        
        return false;
        
    }

    if (empty($value)) {
        
        $wpdb->query($wpdb->prepare("DELETE FROM $wptm_table_name WHERE terms_id = %d AND meta_key = %s", $terms_id, $key));
        
    } else {
        
        $wpdb->query($wpdb->prepare("DELETE FROM $wptm_table_name WHERE terms_id = %d AND meta_key = %s AND meta_value = %s", $terms_id, $key, $value));
        
    }

    wp_cache_delete($terms_id, 'terms_meta');

    return true;
    
}

/**
 * get_terms_meta() - Get a terms meta field
 *
 *
 * @param int $terms_id terms (category/tag...) ID
 * @param string $key The meta key to retrieve
 * @param bool $single Whether to return a single value
 * @return mixed The meta value or meta value list
 */
function get_terms_meta($terms_id, $key, $single = false) {

    $terms_id = (int) $terms_id;

    $meta_cache = wp_cache_get($terms_id, 'terms_meta');

    if ( !$meta_cache ) {
        
        update_termsmeta_cache($terms_id);
        $meta_cache = wp_cache_get($terms_id, 'terms_meta');
        
    }

    if ( isset($meta_cache[$key]) ) {
        
        if ( $single ) {
            
            return maybe_unserialize($meta_cache[$key][0]);
            
        } else {
            
            return array_map('maybe_unserialize', $meta_cache[$key]);
            
        }
        
    }
    
    return '';
    
}

/**
 * get_all_terms_meta() - Get all meta fields for a terms (category/tag...)
 *
 *
 * @param int $terms_id terms (category/tag...) ID
 * @return array The meta (key => value) list
 */
function get_all_terms_meta($terms_id) {

    $terms_id = (int) $terms_id;

    $meta_cache = wp_cache_get($terms_id, 'terms_meta');

    if ( !$meta_cache ) {
        
        update_termsmeta_cache($terms_id);
        $meta_cache = wp_cache_get($terms_id, 'terms_meta');
        
    }

    return maybe_unserialize($meta_cache);

}

/**
 * update_termsmeta_cache()
 *
 *
 * @uses $wpdb
 *
 * @param array $category_ids
 * @return bool|array Returns false if there is nothing to update or an array of metadata
 */
function update_termsmeta_cache($terms_ids) {

    global $wpdb;
    global $wptm_table_name;

    if (empty($terms_ids)) {
        
        return false;
        
    }

    if (!is_array($terms_ids)) {
        
        $terms_ids = preg_replace('|[^0-9,]|', '', $terms_ids);
        $terms_ids = explode(',', $terms_ids);
        
    }

    $terms_ids = array_map('intval', $terms_ids);

    $ids = array();
    
    foreach ((array) $terms_ids as $id) {
        
        if ( false === wp_cache_get($id, 'terms_meta') ) {
            
            $ids[] = $id;
            
        }
        
    }

    if (empty($ids)) {
        
        return false;
        
    }

    // Get terms-meta info
    $id_list = join(',', $ids);
    $cache = array();
    
    if ($meta_list = $wpdb->get_results("SELECT terms_id, meta_key, meta_value FROM $wptm_table_name WHERE terms_id IN ($id_list) ORDER BY terms_id, meta_key", ARRAY_A)) {
        
        foreach ((array) $meta_list as $metarow) {
            
            $mpid = (int) $metarow['terms_id'];
            $mkey = $metarow['meta_key'];
            $mval = $metarow['meta_value'];

            // Force subkeys to be array type:
            if (!isset($cache[$mpid]) || !is_array($cache[$mpid])) {
                
                $cache[$mpid] = array();
                
            }
            
            if (!isset($cache[$mpid][$mkey]) || !is_array($cache[$mpid][$mkey])) {
                
                $cache[$mpid][$mkey] = array();
                
            }

            // Add a value to the current pid/key:
            $cache[$mpid][$mkey][] = $mval;
            
        }
        
    }

    foreach ( (array) $ids as $id ) {
        
        if (!isset($cache[$id])) {
            
            $cache[$id] = array();
            
        }
        
    }

    foreach ( array_keys($cache) as $terms) {
        
        wp_cache_set($terms, $cache[$terms], 'terms_meta');
        
    }

    return $cache;
    
}

/**
 * Function that saves the meta from form.
 *
 * @param $id : terms (category) ID
 * @return void;
 */
function wptm_save_meta_tags($id) {

    $metaList = get_option("wptm_configuration");
    
    // Check that the meta form is posted
    $wptm_edit = $_POST["wptm_edit"];
    
    if (isset($wptm_edit) && !empty($wptm_edit)) {
        
        foreach ($metaList as $inputName => $inputType) {
        
            if ($inputType['taxonomy'] == $_POST['taxonomy']) {
                
                // Replace spaces with underscores for nomn-sanitized input names
                $inputValue = $_POST['wptm_'.str_replace(' ','_',$inputName)];
                
                delete_terms_meta($id, $inputName);
                
                if (isset($inputValue) && !empty($inputValue)) {
                    
                    add_terms_meta($id, $inputName, $inputValue);
                    
                }
                
            }
        
        }
        
    }
}

/**
 * Function that deletes the meta for a terms (category/..)
 *
 * @param $id : terms (category) ID
 * @return void
 */
function wptm_delete_meta_tags($id) {
    
    $metaList = get_option("wptm_configuration");
    
    foreach($metaList as $inputName => $inputType) {
        
        delete_terms_meta($id, $inputName);
        
    }
    
}

/**
 * Function that display the meta text input.
 *
 * @return void.
 */
function wptm_add_meta_textinput($tag) {
    
    global $category, $wp_version, $taxonomy;
    
    $category_id = '';
    
    if ($wp_version >= '3.0') {
        
        $category_id = (is_object($tag))?$tag->term_id:null;
        
    } else {
        
        $category_id = $category;
        
    }
    
    $metaList = get_option("wptm_configuration");
    
    if (is_object($category_id)) {
        
        $category_id = $category_id->term_id;
        
    }
    
    if (!is_null($metaList) && count($metaList) > 0 && $metaList != '' && isset($_GET['tag_ID'])) { ?>
        
        <h3 class='hndle'><span><?php _e('Term meta', 'wp-category-meta');?></span></h3>
        
        <div class="inside">
            
            <input value="wptm_edit" type="hidden" name="wptm_edit" /> 
            <input type="hidden" name="image_field" id="image_field" value="" />
            <table class="form-table">
            
            <?php
            
            foreach ($metaList as $inputName => $inputData) {
                
                $inputType = '';
                $inputTaxonomy = 'category';
                
                if (is_array($inputData)) {
                    
                    $inputType = $inputData['type'];
                    $inputTaxonomy = $inputData['taxonomy'];
                    
                } else {
                    
                    $inputType = $inputData;
                    
                }
                
                // display the input field in 2 cases
                // WP version if < 3.0
                // or WP version > 3.0 and $inputTaxonomy == current taxonomy
                if ($wp_version < '3.0' || $inputTaxonomy == $taxonomy) {
                    
                    $inputValue = htmlspecialchars(stripcslashes(get_terms_meta($category_id, $inputName, true)));
                    
                    if ($inputType == 'text') { ?>
                        
                    	<tr class="form-field">
                    		<th scope="row" valign="top">
                                <label for="category_nicename"><?php echo $inputName;?></label>
                            </th>
                    		<td>
                                <input value="<?php echo $inputValue ?>" type="text" size="40" name="<?php echo 'wptm_'.$inputName;?>" /><br />
                    			<?php _e('This additionnal data is attached to the current term', 'custom-taxonomy-category-and-term-fields');?>
                            </td>
                    	</tr>
                        
                	<?php } elseif ($inputType == 'textarea') { ?>
                        
                    	<tr class="form-field">
                    		<th scope="row" valign="top">
                                <label for="category_nicename"><?php echo $inputName;?></label>
                            </th>
                    		<td>
                                <textarea name="<?php echo "wptm_".$inputName?>" rows="5" cols="50" class="large-text"><?php echo $inputValue ?></textarea><br />
                                <?php _e('This additionnal data is attached to the current term', 'custom-taxonomy-category-and-term-fields');?>
                            </td>
                    	</tr>
                    
                	<?php } elseif ($inputType == 'editor') { ?>
                        
                        <? $inputValue = get_terms_meta($category_id, $inputName, true); ?>
                        
                    	<tr>
                    		<th scope="row" valign="top">
                                <label for="category_nicename"><?php echo $inputName;?></label>
                            </th>
                    		<td>
                                <?php wp_editor($inputValue,"wptm_".str_replace(' ','_',$inputName),array('textarea_name'=>"wptm_".str_replace(' ','_',$inputName))); ?>
                                <?php _e('This additionnal data is attached to the current term', 'custom-taxonomy-category-and-term-fields');?>
                            </td>
                    	</tr>
                    
                	<?php } elseif ($inputType == 'image') { ?>
                        
                        <?php $current_image_url = get_terms_meta($category_id, $inputName, true); ?>
                        
                    	<tr class="form-field">
                    		<th scope="row" valign="top">
                                <label for="<?php echo "wptm_".str_replace(' ','_',$inputName);?>" class="wptm_meta_name_label"><?php echo $inputName;?></label>
                            </th>
                    		<td>
                                <div id="<?php echo "wptm_".str_replace(' ','_',$inputName);?>_selected_image" class="wptm_selected_image">
                                    <?php if ($current_image_url != '') echo '<img src="'.$current_image_url.'" style="max-width:100%;"/>';?>
                                </div>
                                <input type="text" name="<?php echo "wptm_".str_replace(' ','_',$inputName);?>" id="<?php echo "wptm_".str_replace(' ','_',$inputName);?>" value="<?php echo $current_image_url;?>" /><br />
                                <br />
                        		<img src="images/media-button-image.gif" alt="Add photos from your media" /> 
                                <a href="media-upload.php?type=image&#038;wptm_send_label=<?php echo str_replace(' ','_',$inputName); ?>&#038;TB_iframe=1&#038;tab=library&#038;height=500&#038;width=640" onclick="image_photo_url_add('<?php echo "wptm_".str_replace(' ','_',$inputName);?>')" class="thickbox" title="Add an Image"> 
                                    <strong>
                                        <?php echo _e('Click here to add/change your image', 'custom-taxonomy-category-and-term-fields');?>
                                    </strong>
                        		</a><br />
                        		<small>
                                    <?php echo _e('Note: To choose image click the "insert into post" button in the media uploader', 'custom-taxonomy-category-and-term-fields');?>
                        		</small><br />
                        		<img src="images/media-button-image.gif" alt="Remove existing image" />
                        		<a href="#" onclick="remove_image_url('<?php echo "wptm_".str_replace(' ','_',$inputName);?>','<?php _e('No image selected', 'custom-taxonomy-category-and-term-fields');?>');return false;">
                                    <strong>
                                        <?php _e('Click here to remove the existing image', 'custom-taxonomy-category-and-term-fields');?>
                                    </strong>
                        		</a><br />
                            </td>
                        </tr>
                    
                	<?php } elseif ($inputType == 'checkbox') { ?>
                    
                        <tr class="form-field">
                            <th scope="row" valign="top">
                                <label for="category_nicename"><?php echo $inputName;?></label>
                            </th>
                            <td>
                                <input value="checked" type="checkbox" <?php echo $inputValue ? 'checked="checked" ' : ''; ?> name="<?php echo 'wptm_'.$inputName;?>" /><br />
                                <?php _e('This additionnal data is attached to the current term', 'wp-category-meta');?>
                            </td>
                        </tr>
                        
                	<?php } // end ELSEIF
                    
                }//end FOREACH
                
            }//end IF ?>
                
                
            </table>
            <textarea id="content_temp" name="content_temp" rows="100" cols="10" tabindex="2" onfocus="image_url_add()" style="width: 1px; height: 1px; padding: 0px; border: none;display :   none;"></textarea>
            <script type="text/javascript">edCanvas_temp = document.getElementById('content_temp');enable=false;</script>
        
        </div>
        
    <?php }// end IF ?>
    
<?php
}

?>