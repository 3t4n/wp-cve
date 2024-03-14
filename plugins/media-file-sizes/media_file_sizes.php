<?php
/*
Plugin Name: Media File Sizes
Plugin URI: http://wordpress.org/extend/plugins/media-file-sizes/
Description: Displays file sizes next to media items in library.  (For image files, also displays total size of all generated sizes.)
Author: Jason Lemahieu
Version: 1.8
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if (is_admin()) {

	/**
	 * Establish new column on media library page
	 */
	function mediafilesizes_library_column_header( $cols ) {
		$cols['media_filesizes'] = __('Space Used');
		return $cols;
	}
	add_filter( 'manage_media_columns', 'mediafilesizes_library_column_header' );

	/**
	 * Output the actual cell in the media file sizes row on the media library
	 */
	function mediafilesizes_library_column_row( $column_name, $id ) {
				
		if ($column_name == 'media_filesizes') {
			
			
			$space = mediafilesizes_get_size($id);
			
			$totalspace = $space['total'];
			$originalspace = $space['original'];
			
			$class = '';  //for caution, warning, alert - based on percentage of quota used
			
			$original_kbs = round($originalspace / 1024, 0);
			$displayoriginalspace = $original_kbs . " KB";

			$original_mbs = "";
			if ($original_kbs > 1023) {
				$original_mbs = round($original_kbs / 1024, 1);
				$displayoriginalspace .= " ({$original_mbs} MB)";
			}


			$quotaspace = 0;
			if ($totalspace) {
				//$original is different from total - display original as well
				
				$totalspace_kbs = round($totalspace / 1024, 0);
				$displaytotalspace = $totalspace_kbs . " KB";
				
				if ($totalspace_kbs > 1023) {
					$totalspace_mbs = round($totalspace_kbs / 1024, 1);
					$displaytotalspace .= " ({$totalspace_mbs} MB)";
				}


				echo _('All Sizes') . ": " . $displaytotalspace . "<br/>";
				echo _('Original') . ": " . $displayoriginalspace;
				$quotaspace = $totalspace;
			} else {
				// just 1
				echo $displayoriginalspace;
				$quotaspace = $originalspace;
			} 
			
			
			// determine percentage of space used. Displayed only if >= 1
			
			if (function_exists('get_space_allowed')) {
				$quota = get_space_allowed();
				$quota = $quota * 1048576;
				$percentage = round($quotaspace/$quota*100, 1);
				
				if ($percentage >= 1) {
					
					$class = "media-file-sizes-caution";
					if ($percentage >= 3) {
						$class = "media-file-sizes-warning";
						if ($percentage >= 10) {
							$class = "media-file-sizes-alert";
						}
					}	
					$quota_info = "<br /><span class='" . $class . "'>".$percentage."% " . _('of allowed space') . "</span>";
					echo $quota_info;

					do_action('media_file_sizes_percent_after');
				
				}
			}
			
		} //if column_name == media_filesizes
	}
	add_action( 'manage_media_custom_column', 'mediafilesizes_library_column_row', 10, 2 );

	/**
	 * Calculate (if necessary), cache (if necessary), and return the size of this media item
	 */
	function mediafilesizes_get_size($id) {
		
		$return_array = array(
				"original"=> "",
				"total" => "",
		);
			
		$upload_dir = wp_upload_dir();
		$upload_base_dir = $upload_dir['basedir'];

		
		$space = 0; //totalspace
		$metadata = wp_get_attachment_metadata($id);
		
		if ($metadata) {
			
			// see if image or audio/video
			if (isset($metadata['filesize']) && $metadata['filesize']) {
				$return_array['original'] = $metadata['filesize'];
				update_post_meta($id, 'mediafilesize', $return_array['original']);
			} else {
				//this is an image with possible multiple sizes
				$original_sub_path = $metadata['file'];
				
				$orig_full_path = $upload_base_dir . "/" . $original_sub_path;
				$originalsize = filesize($orig_full_path);
				
				$return_array['original'] = $originalsize;
				
				//extract upload path
				$orig_parts = explode("/", $orig_full_path);
				array_pop($orig_parts);
				$str_path = implode("/", $orig_parts);
				
				//total space used is original size + other sizes
				$space = $originalsize;		
				if (isset($metadata['sizes']) && $metadata['sizes']) {
					foreach ($metadata['sizes'] as $size) {					
									
						$sizepath = $str_path . "/" . $size['file'];				
						$this_size = filesize($sizepath);
						$space = $space + $this_size;
					}
				}
				$return_array['total'] = $space;
				update_post_meta($id, 'mediafilesize', $return_array['total']);
			}
		
			
			
			
				
		}  else {
			//single file - not a set of images
			$path = get_post_meta($id, '_wp_attached_file', true);
			
			$upload_dir = wp_upload_dir();
			$upload_base_dir = $upload_dir['basedir'];
			
			$space = filesize($upload_base_dir . "/" . $path);
			
			$return_array['original'] = $space;
			update_post_meta($id, 'mediafilesize', $return_array['original']);
			
		}
		
		return $return_array;
	}

	/**
	 * cache media file size as attachment data for sorting
	 */
	function mediafilesizes_run_metadata() {
		$args = array( 'post_type' => 'attachment', 'numberposts' => -1, 'post_status' => null, 'post_parent' => null ); 
		$attachments = get_posts( $args );
		
		if ($attachments) {
			foreach ($attachments as $post) {
				setup_postdata($post);
				$sizearray = mediafilesizes_get_size($post->ID);
				
				if (isset($sizearray['total']) && $sizearray['total'] != '') {
					$size = $sizearray['total'];
				} else {
					$size = $sizearray['original'];
				}
				update_post_meta($post->ID, 'mediafilesize', $size);
			}
		}
	}

	/** 
	 * remove stored metadata with each attachment (on deactivation)
	 */
	function mediafilesizes_clear_metadata() {
		$args = array( 'post_type' => 'attachment', 'numberposts' => -1, 'post_status' => null, 'post_parent' => null ); 
		$attachments = get_posts( $args );
		
		if ($attachments) {
			foreach ($attachments as $post) {
				setup_postdata($post);
				delete_post_meta($post->ID, 'mediafilesize');
			}
		}
	}

	/**
	 * check for a plugin upgrade and do any necessary actions.
	 */
	function mediafilesizes_check_upgrade() {

		$version = get_option('mediafilesize_version', '0');
		$version = (float) $version;
		if ($version < 1.7) {
			mediafilesizes_clear_metadata();
			mediafilesizes_run_metadata();
			update_option('mediafilesize_version', '1.7');
		}	
	}


	/**
	 * register the media file size column as sortable (3.1+)
	 */
	function mediafilesizes_library_register_sortable($columns) {
		$columns['media_filesizes'] = 'mediafilesize';
		return $columns;	
	}
	add_filter( 'manage_upload_sortable_columns', 'mediafilesizes_library_register_sortable');

	/**
	 * Define what it means to sort by 'mediafilesizes'
	 */
	function mediafilesizes_column_orderby( $vars ) {
			
		if ( isset( $vars['orderby'] ) && 'mediafilesize' == $vars['orderby'] ) {
				
			$vars = array_merge( $vars, array(
				'meta_key' => 'mediafilesize',
				'orderby' => 'meta_value_num' 
			) );
		} 
		return $vars;
	}
	add_filter('request', 'mediafilesizes_column_orderby');


	/**
	 * Add CSS to wp_head (for percent coloring)
	 */
	function mediafilesizes_css_admin() {
		
		// CSS and Javascript for admin head
		?>
		<!-- Media File Sizes Admin CSS -->
		<style>
		.media-file-sizes-caution {color: #CC6600;}
		.media-file-sizes-warning {color: #F00;}
		.media-file-sizes-alert {color: #F00; font-weight: bold;}
		</style>
		<?php 
	}
	//only embed these on the media page
	if (strpos($_SERVER['REQUEST_URI'], 'upload.php')) {
		add_action('admin_head', 'mediafilesizes_css_admin');	
		add_action('admin_head', 'mediafilesizes_check_upgrade');
	}

} //is_admin

/**
 * On plugin activation, cache metadata for performance and sorting (3.1+)
 */
function mediafilesizes_activate() {
	mediafilesizes_run_metadata();
}
register_activation_hook(__FILE__, 'mediafilesizes_activate');

/**
 * On plugin deactivation, clear cahced metadata
 */
function mediafilesizes_deactivate() {
	mediafilesizes_clear_metadata();
	delete_option('mediafilesize_version');
}
register_deactivation_hook(__FILE__, 'mediafilesizes_deactivate');