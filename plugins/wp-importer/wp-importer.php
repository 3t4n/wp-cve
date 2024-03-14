<?php
/*
Plugin Name: WP Importer
Description: Create unlimited post/pages through the CSV file.
Version: 1.7
Author: WP Experts Team
Author URI: http://www.wp-experts.in
*/
/*  Copyright 2015-2023  wp importer (email : raghunath.0087@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class WpImporter {
	
	 /**
         * Construct the plugin object
         */
        public function __construct()
        {
           add_action( 'admin_bar_menu', array(&$this,'toolbar_link_to_wpc'), 999 );
        } // END public function __construct
	  /**
		 * hook to add link under adminmenu bar
		 */		
		public function toolbar_link_to_wpc( $wp_admin_bar ) {
			$args = array(
				'id'    => 'wi_menu_bar',
				'title' => 'WP Importer',
				'href'  => admin_url('tools.php?page=wp-importer'),
				'meta'  => array( 'class' => 'wi-toolbar-page' )
			);
			$wp_admin_bar->add_node( $args );
			//second lavel
			$wp_admin_bar->add_node( array(
				'id'    => 'wi-second-sub-item',
				'parent' => 'wi_menu_bar',
				'title' => 'Settings',
				'href'  => admin_url('tools.php?page=wp-importer'),
				'meta'  => array(
					'title' => __('Settings'),
					'target' => '_self',
					'class' => 'wi_menu_item_class'
				),
			));
		}
		
    var $log = array();
    /**
     * Determine value of option $name from database, $default value or $params,
     * save it to the db if needed and return it.
     *
     * @param string $name
     * @param mixed  $default
     * @param array  $params
     * @return string
     */
    function process_option($name, $default, $params) {
        if (array_key_exists($name, $params)) {
            $value = stripslashes($params[$name]);
        } elseif (array_key_exists('_'.$name, $params)) {
            // unchecked checkbox value
            $value = stripslashes($params['_'.$name]);
        } else {
            $value = null;
        }
        $stored_value = get_option($name);
        if ($value == null) {
            if ($stored_value === false) {
                if (is_callable($default) &&
                    method_exists($default[0], $default[1])) {
                    $value = call_user_func($default);
                } else {
                    $value = $default;
                }
                add_option($name, $value);
            } else {
                $value = $stored_value;
            }
        } else {
            if ($stored_value === false) {
                add_option($name, $value);
            } elseif ($stored_value != $value) {
                update_option($name, $value);
            }
        }
        return $value;
    }

    /**
     * Plugin's interface
     *
     * @return void
     */

 function wp_importer_form() {
        $opt_draft = $this->process_option('csv_importer_import_as_draft',
            'publish', $_POST);
        $opt_cat = $this->process_option('csv_importer_cat', 0, $_POST);

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $this->post(compact('opt_draft', 'opt_cat'));
        }

        // form HTML {{{
?>
<div id="wp-settings"> 
<div class="wrap">
    <h1>Wp Importer Settings</h1><hr />
    <form class="add:the-list: validate" method="post" enctype="multipart/form-data">
	<div style="width: 80%; padding: 10px; margin: 10px;"> 
	<div id="wpimporter-tab-menu"><a id="wpimporter-general" class="wpimporter-tab-links active" >General</a> <a  id="wpimporter-support" class="wpimporter-tab-links">Support</a> </div>
	<div class="wpimporter-setting">
	<!-- General Setting -->	
	<div class="first wpimporter-tab" id="div-wpimporter-general">
		
		<table border="5" cellpadding="10">
	<tr>
	<td valign="top">		
	<h2>General Settings</h2>
		   <?php wp_nonce_field( 'wp_import_csv_action', 'wp_csv_nonce_field' ); ?>
		<!-- Parent category -->
        <p><label for="page_type">Choose Post Type:</label> 
        <select name="page_type" id="page_type">
			<option value="">Select post type</option>
			<?php 

			$args = array(
			   'public'   => true,
			   '_builtin' => false
			);

			$output = 'names'; // names or objects, note names is the default
			$operator = 'and'; // 'and' or 'or'

			$post_types = get_post_types( $args, $output, $operator ); 
			array_push($post_types,'post');array_push($post_types,'page');
			foreach ( $post_types  as $post_type ) {

				echo '<option value="'.$post_type.'" >'.$post_type.'</option>';
			}

			?>
	</select>
        <!-- File input --></p>
        <p><label for="csv_import">Upload file:</label><input name="csv_import" id="csv_import" type="file" value="" aria-required="true" /></p>
        <p class="submit"><?php submit_button("Import Now"); ?></p>
        <ul>
        <li><strong>Note 1:</strong> Please don't do any changes in first row (Index Row)<br> of sample CSV file. Start to insert data from second row and add values as per column name of first row.<br>Unique ID column value should be uninue for every post, It's very important during update post data by same CSV file</li>        
        <li><strong>Note 2:</strong> This plugin provided only an option to <br>upload the SEO META values of <a href="https://wordpress.org/plugins/wordpress-seo/" target="_blank">"Yoast SEO"</a> plugin.</li>
        </ul>
        <hr>
        <h3 align="left" style="color:red;"><a href="<?php echo plugins_url( 'sample/sample.csv',__FILE__ ); ?>" target="_blank">Click here</a> to download sample csv file </h3>
        </td>
	<td><h2><a href="http://www.wp-experts.in/products/wordpress-csv-importer-pro" target="_blank">GO PRO</a></h2>
	<h2 style="color:green;text-align:left;"><strong>Pay one time use lifetime.</strong> Hurry up!!</h2>
	<p>We have released an addon of this plugin which not only demonstrates the flexibility of <strong>WP Importer</strong>, <br>but also added some important features given below as well:</p>
	<ol>
		<li>Create/Update unlimited posts</li>
		<li>Create/Update unlimited pages</li>
		<li>Create/Update unlimited custom post type pages</li>
		<li>An option to upload featured image for each post</li>
		<li>An option to define category name for each post</li>
		<li>An option to upload any custom field meta value</li>
		<li>An option to define any custom taxonomy name for each post</li>
		<li>An option to define post menu order</li>
		<li>An option to define post author</li>
		<li>An option to define post status</li>
		<li>An option to define custom post slug name</li>
		<li>Faster support</li>
	</ol>
	  <p> <a href="http://www.wp-experts.in/products/wordpress-csv-importer-pro">Click Here</a> to upgrade to pro version.</p>
	 <p> <a href="mailto:raghunath.0087@gmail.com">Click here </a> to send your query for us </p></td>
	</tr>
	</table>
     
	</div><!-- end wrap -->
	<!-- Support -->
	<div class="last author wpimporter-tab" id="div-wpimporter-support">
	<h2>Plugin Support</h2>
	<table>
	<tr>
	<td width="30%"><p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZEMSYQUZRUK6A" target="_blank" style="font-size: 17px; font-weight: bold;"><img src="<?php echo  plugins_url( 'images/btn_donate_LG.gif' , __FILE__ );?>" title="Donate for this plugin"></a></p>
	
	<p><strong>Plugin Author:</strong><br><a href="http://www.wp-experts.in" target="_blank">WP-Experts.In</a></p>
	<p><a href="mailto:raghunath.0087@gmail.com" target="_blank" class="contact-author">Contact Author</a></p>
</td>
	<td>
	<p><strong>Our Other Plugins:</strong></p>
			<ol>
					<li><a href="https://wordpress.org/plugins/custom-share-buttons-with-floating-sidebar" target="_blank">Custom Share Buttons With Floating Sidebar</a></li>
					<li><a href="https://wordpress.org/plugins/seo-manager/" target="_blank">SEO Manager</a></li>
							<li><a href="https://wordpress.org/plugins/protect-wp-admin/" target="_blank">Protect WP-Admin</a></li>
							<li><a href="https://wordpress.org/plugins/wp-sales-notifier/" target="_blank">WP Sales Notifier</a></li>
							<li><a href="https://wordpress.org/plugins/wp-tracking-manager/" target="_blank">WP Tracking Manager</a></li>
							<li><a href="https://wordpress.org/plugins/wp-categories-widget/" target="_blank">WP Categories Widget</a></li>
							<li><a href="https://wordpress.org/plugins/wp-protect-content/" target="_blank">WP Protect Content</a></li>
							<li><a href="https://wordpress.org/plugins/wp-version-remover/" target="_blank">WP Version Remover</a></li>
							<li><a href="https://wordpress.org/plugins/wp-posts-widget/" target="_blank">WP Post Widget</a></li>
							<li><a href="https://wordpress.org/plugins/wp-importer" target="_blank">WP Importer</a></li>
							<li><a href="https://wordpress.org/plugins/wp-csv-importer/" target="_blank">WP CSV Importer</a></li>
							<li><a href="https://wordpress.org/plugins/wp-testimonial/" target="_blank">WP Testimonial</a></li>
							<li><a href="https://wordpress.org/plugins/wc-sales-count-manager/" target="_blank">WooCommerce Sales Count Manager</a></li>
							<li><a href="https://wordpress.org/plugins/wp-social-buttons/" target="_blank">WP Social Buttons</a></li>
							<li><a href="https://wordpress.org/plugins/wp-youtube-gallery/" target="_blank">WP Youtube Gallery</a></li>
							<li><a href="https://wordpress.org/plugins/tweets-slider/" target="_blank">Tweets Slider</a></li>
							<li><a href="https://wordpress.org/plugins/rg-responsive-gallery/" target="_blank">RG Responsive Slider</a></li>
							<li><a href="https://wordpress.org/plugins/cf7-advance-security" target="_blank">Contact Form 7 Advance Security WP-Admin</a></li>
							<li><a href="https://wordpress.org/plugins/wp-easy-recipe/" target="_blank">WP Easy Recipe</a></li>
					</ol></td>
	</tr>
	</table>

	</div>
  </div>
  </form>
</div>
<!-- End Genral settings -->
<?php
        // end form HTML }}}

    }
function print_messages() {
        if (!empty($this->log)) {

        // messages HTML {{{
?>
<div class="wrap">
    <?php if (!empty($this->log['error'])): ?>
    <div class="error">
        <?php foreach ($this->log['error'] as $error): ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php if (!empty($this->log['notice'])): ?>
    <div class="updated fade">
        <?php foreach ($this->log['notice'] as $notice): ?>
            <p><?php echo $notice; ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php if (!empty($this->log['success'])): ?>
    <div class="updated fade">
        <?php foreach ($this->log['success'] as $success): ?>
            <p><?php echo $success; ?></p>
        <?php endforeach; ?>
    </div>

    <?php endif; ?>
</div><!-- end wrap -->
<?php
        // end messages HTML }}}

            $this->log = array();
        }
    }
    /**
     * Handle POST submission
     *
     * @param array $options
     * @return void
     */
    function post($options) {
       
        if ( ! isset( $_POST['wp_csv_nonce_field'] ) || ! wp_verify_nonce( $_POST['wp_csv_nonce_field'], 'wp_import_csv_action' ) ) {
				 $this->log['error'][] = 'Invalid attempt';
                 $this->print_messages();
				return;
			}
			
		if (!current_user_can('import')) {
				 $this->log['error'][] = 'You are not permitted for import data';
                 $this->print_messages();
				return;
			}
			
        if (empty($_POST['page_type'])) {
            $this->log['error'][] = 'Please select post type';
            $this->print_messages();
            return;
        }
        
        if (empty($_FILES['csv_import']['tmp_name'])) {
            $this->log['error'][] = 'No file uploaded, aborting.';
            $this->print_messages();
            return;
        }

        if (!current_user_can('publish_pages') || !current_user_can('publish_posts')) {
            $this->log['error'][] = 'You don\'t have the permissions to publish posts and pages. Please contact the blog\'s administrator.';
            $this->print_messages();
            return;
        }
        
        $csv_file = $_FILES['csv_import']['tmp_name']; 
		//echo $csv_file = sanitize_file_name($csv_file); 
		$filename = sanitize_file_name($_FILES['csv_import']['name']); 
		$type = strtolower(substr($filename,-3));
		//$type = sanitize_file_name($type); 
        if ($type!='csv') {
            $this->log['error'][] = 'File format is wrong.';
            $this->print_messages();
            return;
        }
        
     if (! is_file( $csv_file )) {
            $this->log['error'][] = 'Failed to load file';
            $this->print_messages();
            return;
        }

$pageType=$_POST['page_type'];
/** 
 *  post type 
 * */
if($pageType!=''){    
/** Store .csv file value into a array */
$fldAry=array("custom_id",
			  "post_title",
			  "post_slug",
			  "menu_order",
			  "post_status",
			  "post_content",
			  "author_id",
			  'meta_title',
			  'meta_desc',
			  'meta_key'
			  );
	$arry=$this->csvIndexArray($csv_file, ",", $fldAry, 0);
	$skipped = 0;
	$imported = 0;
	$time_start = microtime(true);
	$upload_dir = wp_upload_dir();
	$upload_path=$upload_dir['baseurl'];

	global $post,$wpdb;
	if(count($arry) > 0):
	foreach ($arry as $data) {
		$data = wp_slash($data);
		wp_reset_postdata();
		$user_id =get_current_user_id();
			if(isset($data['author_id']) && $data['author_id']!='')
			{
				$user_id=$data['author_id'];
			}
			$post_title=$data['post_title'];
			
			/* check post exist or not */
			if(isset($data['custom_id']) && $data['custom_id']!='')
			{
			$customId=trim($data['custom_id']);
			$mainquery="SELECT p.ID FROM ".$wpdb->prefix."posts p, ".$wpdb->prefix."postmeta meta WHERE 
				p.ID = meta.post_id 
				AND ( (meta.meta_key = '_wp_importer_unique_id' 
				AND meta.meta_value = '$customId') || (meta.meta_key = 'custom_id' 
				AND meta.meta_value = '$customId') )
				AND p.post_type = '$pageType'
				limit 0,1";			
            $csvpage = $wpdb->get_results($mainquery, OBJECT);
			}
			else
			{
				$csvpage=true;
				return;
				// If no customID is passed, do not add/edit the record
			} 
           $checkpoststatus='0';
			if (!$csvpage){
			/* create new post */	
			$new_post = array(
						'post_title'   => convert_chars($data['post_title']),
						'menu_order'   => $data['menu_order'],
						'post_name'   => trim($data['post_slug']),
						'post_status'  => $data['post_status'],
						'post_content' => wpautop(convert_chars($data['post_content'])),
						'post_type'    => $pageType,
						'post_author'  => $user_id,
					);
			// Insert the post into the database
			$existpost_id = wp_insert_post($new_post);

			}else
			{
				$existpost_id =$csvpage[0]->ID;
				$update_post = array(
						'ID' 		   =>$existpost_id,
						'post_title'   => convert_chars($data['post_title']),
						'menu_order'   => convert_chars($data['menu_order']),
						'post_status'  => $data['post_status'],
						'post_modified'  => date('Y-m-d h:m:s'),
						'post_author'  => $user_id,
					);
				wp_update_post($update_post);
			$checkpoststatus='1';
			}
			/* Start custom meta fields */
			$videoId='';
			if($existpost_id):	
				if(isset($data['custom_id']) && $data['custom_id']!='')
				 {
					$custom_id=$data['custom_id'];
					if(!add_post_meta($existpost_id, '_wp_importer_unique_id',$custom_id, true))
					{
						update_post_meta($existpost_id, '_wp_importer_unique_id',$custom_id);
						
						}else
						{
							add_post_meta($existpost_id, '_wp_importer_unique_id',$custom_id, true);
							
							}
					}	
				/* Start SEO meta content */
				
				if(isset($data['meta_title']) && $data['meta_title']!='')
				{  
				   $metaTitle=$data['meta_title'];
				   if(!add_post_meta($existpost_id, '_yoast_wpseo_title',$metaTitle, true))
					{
						update_post_meta($existpost_id, '_yoast_wpseo_title',$metaTitle);
						
					}else
					{
						add_post_meta($existpost_id, '_yoast_wpseo_title',$metaTitle, true);
							
					}
				
				}// update meta title
				
				if(isset($data['meta_desc']) && $data['meta_desc']!='')
				{
					$metaDesc=$data['meta_desc'];
					if(!add_post_meta($existpost_id, '_yoast_wpseo_metadesc',$metaDesc, true))
					{
						update_post_meta($existpost_id, '_yoast_wpseo_metadesc',$metaDesc);
						
						}else
						{
							add_post_meta($existpost_id, '_yoast_wpseo_metadesc',$metaDesc, true);
							
							}
			
				} // update meta description
				
				if(isset($data['meta_key']) && $data['meta_key']!='')
				{
				  $metaKeys=$data['meta_key'];
				  if(!add_post_meta($existpost_id, '_yoast_wpseo_metakeywords',$metaKeys, true))
					{
						update_post_meta($existpost_id, '_yoast_wpseo_metakeywords',$metaKeys);
						
						}else
						{
							add_post_meta($existpost_id, '_yoast_wpseo_metakeywords',$metaKeys, true);
							
							}
				
				}// update meta keyuwords'
			 
				/* End SEO meta content */

			$imported++;
			else:
			$skipped++;
			endif;

			if($checkpoststatus=='1'){$msg='Updated';}else{$msg='Created';}	
			$this->log['success'][] = '#'.$existpost_id.'. '.$data['post_title'].' page is <b>'.$msg.'</b>';
			$this->print_messages();
			} 
	endif;
}
        if (file_exists($csv_file)) {
            @unlink($csv_file);
        }

        $exec_time = microtime(true) - $time_start;

        if ($skipped) {
            $this->log['notice'][] = "<b>Skipped {$skipped} posts (most likely due to empty title, body and excerpt).</b>";
        }
        $this->log['notice'][] = sprintf("<b>Imported {$imported} pages in %.2f seconds.</b>", $exec_time);
        $this->print_messages();
    }
/** Reterive data from csv file to array format */
function csvIndexArray($filePath='', $delimiter='|', $header = null, $skipLines = -1) {
         $lineNumber = 0;
         $dataList = array();
         //$headerItems = array();
        if (($handle = fopen($filePath, 'r')) != FALSE) {
			
		   while (($items = fgetcsv($handle, 1000, ",")) !== FALSE) 
		   {
			    if($lineNumber == 0)
			    { 
					//$header = $items; 
					$lineNumber++; continue; 
				}
				
				$record = array();
				for($index = 0, $m = count($header); $index < $m; $index++){
					//If column exist then and then added in data with header name
					if(isset($items[$index])) {
				   $itmcont = trim(mb_convert_encoding(str_replace('"','',$items[$index]), "utf-8", "HTML-ENTITIES" ));
				   $record[$header[$index]] = str_replace('#',',',$itmcont);
					}
				}
				$dataList[] = $record; 				
				 
				
			}			
           fclose($handle);
        }
        return $dataList;
    }
}
// Add settings link to plugin list page in admin
if(!function_exists('wp_importer_add_settings_link')):
function wp_importer_add_settings_link( $links ) {
  $settings_link = '<a href="tools.php?page=wp-importer">' . __( 'Settings', 'wp-importer' ) . '</a>';
    $settings_link .= ' | <a href="mailto:raghunath.0087@gmail">' . __( 'Contact to Author', 'wp-importer' ) . '</a>';
   array_unshift( $links, $settings_link );
  return $links;
}
endif;
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'wp_importer_add_settings_link' );
function wpimport_admin_menu() {
    require_once ABSPATH . '/wp-admin/admin.php';
    $plugin = new WpImporter;
    add_submenu_page('tools.php','Wp Importer', 'Wp Importer', 'manage_options','wp-importer',
        array($plugin, 'wp_importer_form'));
}
add_action('admin_menu', 'wpimport_admin_menu');
if (isset($_GET['page']) && $_GET['page'] == 'wp-importer') {
   add_action('admin_enqueue_scripts','init_wp_importer_admin_scripts');
}
if(!function_exists('init_wp_importer_admin_scripts')):
function init_wp_importer_admin_scripts()
{
wp_register_style( 'wp_importer_admin_style', plugins_url( 'css/admin-min.css',__FILE__ ) );
wp_enqueue_style( 'wp_importer_admin_style' );
wp_enqueue_script('jquery');

$admin_script=' jQuery(document).ready(function(){
		jQuery(".wpimporter-tab").hide();
		jQuery("#div-wpimporter-general").show();
	    jQuery(".wpimporter-tab-links").click(function(){
		var divid=jQuery(this).attr("id");
		jQuery(".wpimporter-tab-links").removeClass("active");
		jQuery(".wpimporter-tab").hide();
		jQuery("#"+divid).addClass("active");
		jQuery("#div-"+divid).fadeIn();
		});
	jQuery(".button-primary").click(function(){
	 if(confirm("Click OK to continue?")){
      }
	})
	}); '  ;
wp_add_inline_script( 'jquery-core', $admin_script );
}
endif;
