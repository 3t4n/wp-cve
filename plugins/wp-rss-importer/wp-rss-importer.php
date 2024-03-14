<?php
/*
Plugin Name: Wp RSS Importer 
Plugin URI: http://logicsart.com/wordpress-plugins/wp-rss-importer
Description: An easy to use RSS management system for wordpress that save posts from RSS url. This plugin works for any post or custom post type.
Author: Dileep Awasthi
Author URI: http://logicsart.com/
Version: 2.0.2
License: GPLv3

Wp RSS Importer */

if ( !class_exists( 'wp_rss_importer' ) ) {
	class wp_rss_importer { 
		function __construct() {
			$this->define_constants();
			$this->define_tables();
			if ( is_admin() ) {
                add_action('admin_head', array($this,'logics_get_tax_js'));
				add_action('wp_ajax_logics_taxget', array($this,'logics_taxget_action_callback'));
				add_action('wp_ajax_logics_termget', array($this,'logics_termget_action_callback'));
				
				require_once( LOGICS_PLUGIN_DIR . 'admin/class-admin.php' );
            }
			add_action('wp_ajax_runcron', array($this,'runcron_callback'));
			add_action('wp_ajax_nopriv_runcron', array($this,'runcron_callback'));
		}
		function logics_taxget_action_callback() {
			if(isset($_REQUEST['parentID']) && $_REQUEST['parentID'] != '') {
				$taxonomy_names = get_object_taxonomies( $_REQUEST['parentID'] );
				foreach($taxonomy_names as $tx) {
					echo '<option value="'.$tx.'">'.$tx.'</option>';
				}
				echo '+++++';
				$terms = get_terms(array($taxonomy_names[0]),array('hide_empty' => false));
				foreach($terms as $tm) {
					echo '<input type="checkbox" name="logics[taxitem][]" id="logics-rss-taxitem-'.$tm->term_id.'" value="'.$tm->term_id.'" >'.$tm->name.' ';
				}
				die;
			}
		}
		function logics_termget_action_callback() {
			if(isset($_REQUEST['parentID']) && $_REQUEST['parentID'] != '') {
				$terms = get_terms(array($_REQUEST['parentID']),array('hide_empty' => false));
				foreach($terms as $tm) {
					echo '<input type="checkbox" name="logics[taxitem][]" id="logics-rss-taxitem-'.$tm->term_id.'" value="'.$tm->term_id.'" >'.$tm->name.' ';
				}
				die;
			}
		}
		function logics_get_tax_js() { 
		?>
		<style>.logics-add-rss label { float:left; width:200px; margin:2px; } .logics-add-rss input[type=text],select { width:32%; padding:5px; }</style>
		<script type="text/javascript" >
		 
		function populate_Select(parentID,postID){
		jQuery(document).ready(function($) {
		 
				 //Empty secondary categories
				 $('#logics-rss-taxid').empty();
				 $('#logics-rss-taxitem').empty();
			   
				var data = {
						action: 'logics_taxget',
						parentID: parentID,
						postID: postID
				};
		 
				// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
				jQuery.post(ajaxurl, data, function(response)
				{
						var res = response.split("+++++");
						$('#logics-rss-taxid').removeAttr("disabled").append(res[0]);
						$('#logics-rss-taxitem').html(res[1]);
				});
		});
		};
		function populate_Terms(parentID,postID){
		jQuery(document).ready(function($) {
		 
				 //Empty secondary categories
				 $('#logics-rss-taxitem').empty();
			   
				var data = {
						action: 'logics_termget',
						parentID: parentID,
						postID: postID
				};
		 
				// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
				jQuery.post(ajaxurl, data, function(response)
				{
						$('#logics-rss-taxitem').html(response);
				});
		});
		};
		</script>
		<?php
		}
		/**
         * Setup plugin constants
         *
         * @since 1.0
         * @return void
         */
        public function define_constants() {
            
            if ( !defined( 'LOGICS_VERSION_NUM' ) )
                define( 'LOGICS_VERSION_NUM', '1.2.23' );

            if ( !defined( 'LOGICS_URL' ) )
                define( 'LOGICS_URL', plugin_dir_url( __FILE__ ) );

            if ( !defined( 'LOGICS_BASENAME' ) )
                define( 'LOGICS_BASENAME', plugin_basename( __FILE__ ) );

            if ( ! defined( 'LOGICS_PLUGIN_DIR' ) )
                define( 'LOGICS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
        }
		
		private function define_tables() {
            
			global $wpdb;
		
			$wpdb->rss_settings = $wpdb->prefix . 'rss_settings';
			$wpdb->posts = $wpdb->prefix . 'posts';
		}
		
		public function wp_exist_page_by_title($title_str) {
			global $wpdb;
			return $wpdb->get_row("SELECT ID FROM $wpdb->posts WHERE post_title = '" . $title_str . "' && post_status = 'publish'", 'ARRAY_N');
		}
		public function runcron_callback() {
		global $wpdb;
		$rdata = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->rss_settings WHERE id = %d", $_REQUEST[id] ), ARRAY_A );
				$content = file_get_contents($rdata['url']);
				$termArray = array();
				foreach(explode(',',$rdata['taxitem']) as $r) {
					$term = get_term( $r, $rdata['taxid'] );
					$termArray[] = $term->name;
				}
				if(get_option('logics_sourcelink_enable') == 1) {
					$slink = 1;
				} else {
					$slink = 0;
				}
				$x = new SimpleXmlElement($content);			
				$cnt = 1;
				foreach($x->channel->item as $key=>$entry) 
				{
					$xmltoarray = array( (string) $entry->link );
					$pt = $this->wp_exist_page_by_title($entry->title);
					if($pt == '') {
					// Create post object
					$desc = $entry->description;
					if($slink == 1) {
						$desc .= $desc.' <br />';
						if(get_option('logics_sourcelink')) {
							$desc .= '<a href="'.$xmltoarray[0].'" target="_blank">'.get_option('logics_sourcelink').'</a>';
						}
					}
					$my_post = array(
						'post_type'     => $rdata['pid'],
						'post_title'    => $entry->title,
						'post_content'  => $desc,
						'post_status'   => 'publish',
						'post_author'   => 1
					);
					
					// Insert the post into the database
					$post_id = wp_insert_post( $my_post, false);
					if(get_option(logics_source_url_meta) == 1) {
						add_post_meta( $post_id, 'wpri_sourcelink', $xmltoarray[0] );
					} 
					/* Modification  suggested by Elliot */
					if(get_option(logics_feed_id_meta) == 1) {
						add_post_meta( $post_id, 'wpri_feedid', $_REQUEST[id] );
					}
					/*End of mod*/
					wp_set_object_terms( $post_id, $termArray, $rdata['taxid'] );
					global $wpdb;
					$result = $wpdb->query( 
								$wpdb->prepare( 
										"
										UPDATE $wpdb->rss_settings
										SET isrun = %d 
										WHERE id = %d",
										'1',
										$rdata['id']
									  )
								);	
					}
					$cnt++;
				}
				echo '<p style="font-size: 20px; text-align: center;">RSS has been updated <br /> <a href="admin.php?page=logics_managerss">Go to manage screen</a></p>';			

	}
	}
	$rssImporter = new wp_rss_importer();
}

/**
* Setup Database Table
*
* @since 1.0
*/
global $logics_db_version;
$logics_db_version = '1.0';

function logics_db_install() {
	global $wpdb;
	global $logics_db_version;

	$table_name = $wpdb->prefix . 'rss_settings';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
		title varchar(255) DEFAULT '' NOT NULL,
		`pid` varchar(100) DEFAULT '' NOT NULL,
		`taxid` varchar(100) DEFAULT '' NOT NULL,
		`taxitem` varchar(300) DEFAULT '' NOT NULL,
		`url` varchar(255) DEFAULT '' NOT NULL,
		`isrun` int(11) NOT NULL,
		PRIMARY KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'logics_db_version', $logics_db_version );
	add_option( 'logics_sourcelink', 'Source from..', '', 'yes' );
}
register_activation_hook( __FILE__, 'logics_db_install' );
?>