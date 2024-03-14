<?php
namespace brainspace;
use WP_Query;
class ExportPost {

    private $settings = null;

    public function __construct() {

        $this->settings = unserialize(get_option('wpb-field-settings'));
        add_action('admin_menu', array($this, 'eapm_register_page'));
        add_action('template_redirect', array($this, 'eapm_create_post_csv'));
    }

    public function eapm_register_page() {
        add_submenu_page('tools.php', 'Export Posts', 'Export Posts', 'read', 'eapm-export-posts', array($this, 'eapm_render_settings_page'));
    }

    public function eapm_render_settings_page() {

		$headings = array('ID', 'post_author', 'post_date', 'post_date_gmt', 'post_content', 'post_title', 'post_excerpt', 'post_status', 'comment_status', 'ping_status', 'post_password', 'post_name', 'to_ping', 'pinged', 'post_modified', 'post_modified_gmt', 'post_content_filtered', 'post_parent', 'guid', 'menu_order', 'post_type', 'post_mime_type', 'comment_count', 'filter');
		
        echo __('<p>Select which post types to be included in export:</p>');

        $post_types = get_post_types(array(
            'public' => true,
        ));
        $settings = null;

        if (!empty($_POST)) {
			update_option('wpb-field-settings', serialize($_POST));
        }

        $settings = unserialize(get_option('wpb-field-settings'));
		//echo '<pre>'; print_r($settings); echo '</pre>';
		
		if( empty($settings['post_types'])){ $settings['post_types'] = array('post'); }
		if( empty($settings['post_statuses'])){ $settings['post_statuses'] = array('publish'); }
		if( empty($settings['post_keys'])) { $settings['post_keys'] = $headings; }  

        echo '<form action="'.$_SERVER['REQUEST_URI'].'" method="post">';

        echo '<ul>';
        foreach ($post_types as $post_type) {
            echo '<li><label><input type="checkbox"'.($settings && in_array($post_type, $settings['post_types']) ? ' checked="checked"' : '').' name="post_types[]" value="'.$post_type.'"><strong>'.$post_type.'</strong></label></li>';
        }
        echo '</ul>';

        echo __('<p>Select which post status to be included in export:</p>');

        $post_statuses = get_post_statuses();

        echo '<ul>';
        foreach ($post_statuses as $post_status => $post_status_label) {
            echo '<li><label><input type="checkbox"'.($settings && in_array($post_status, $settings['post_statuses']) ? ' checked="checked"' : '').' name="post_statuses[]" value="'.$post_status.'"><strong>'.$post_status_label.'</strong></label></li>';
        }
        echo '</ul>';
        echo __('<p>Which post field and post meta fields do wish you to export? <strong>(Multiple selection):</strong></p>');

		$allposts = $this->eapm_get_posts_by_name($settings['post_types']); // get all post by 
		$meta_keys = array_filter($this->eapm_get_meta_keys_array($allposts));
		$TempArray = array();

		foreach($meta_keys as $value) {
			$TempArray = array_unique(array_merge_recursive($TempArray,$value), SORT_REGULAR);
		}
		$selected_meta_keys = array();
		if(!empty($settings['meta_keys'])){
			if ($settings && $settings['meta_keys']) {
				foreach ($settings['meta_keys'] as $meta_key) {
					$selected_meta_keys[]= $meta_key;
				}
			}
		}
		
		$selected_post_fields = $settings['post_keys'];

		echo '<select multiple="multiple" size="10" name="post_keys[]">';
          foreach ($headings as $field) {
            if (in_array($field,  $selected_post_fields)){
              echo '\n\t<option selected="selected" value="'. $field . '">'.$field.'</option>';
            } else {
              echo '\n\t\<option value="'.$field .'">'.$field.'</option>'; }
            }
		echo '</select>';

          $ccsve_std_fields_num = count($TempArray);
          echo '<select multiple="multiple" size="'.$ccsve_std_fields_num.'" name="meta_keys[]">';
          foreach ($TempArray as $field) {
            if (in_array($field,  $selected_meta_keys)){
              echo '\n\t<option selected="selected" value="'. $field . '">'.$field.'</option>';
            } else {
              echo '\n\t\<option value="'.$field .'">'.$field.'</option>'; }
            }
		  echo '</select>';

        echo '<p><input type="submit" value="Save settings" class="button"><span class="description">  Scroll down to view result and download Post CSV file.</span></p>';
        echo '</form>';
		
		$headings = $settings['post_keys'];
		$array = $this->eapm_get_post_from_settings();

		if (!empty($settings['meta_keys'])) {

			foreach ( $settings['meta_keys'] as $meta_key) {
				$headings[] = $meta_key;
			}
		}
		
				
		echo '<table class="widefat"><thead>';
		echo '<tr>';
		foreach ($headings as $title) {
			echo '<td>'.$title .'</td>';
		}
		
		echo '</tr></thead>';
		echo '<tbody>';
		foreach ($array->posts as $row) 
		{
			echo '<tr>';
			$post_key_counter = 0;
			if(!empty($settings['post_keys']))
			{
				foreach ($settings['post_keys'] as $post_key) {
					echo '<td>'.  wp_trim_words( $row->$post_key, 50 ) .'</td>';
					$post_key_counter++;
				}
			}
				 
			$meta_key_counter = $post_key_counter;
			if(!empty($settings['meta_keys']))
			{
				foreach ($settings['meta_keys'] as $meta_key) {
					$result = maybe_serialize(get_post_meta( $row->ID, $meta_key, true));
					if( is_serialized($result) ){
		
					$column_arr = maybe_unserialize($result);
					$string = implode(' | ', array_filter(array_map(
							function ($v, $k) {
								if(is_array($v)){
									$v = array_filter($v);
									return $k.'[]='.implode(',' , $v);
								}else{
									return $k.'='.$v;
								}
							}, 
							$column_arr, 
							array_keys($column_arr)
						)));
						
						
						echo '<td>'. $string .'</td>';

					}
					else{
						echo '<td>'. $result .'</td>';
					}
					$meta_key_counter++;
				}
			}
				
			echo '</tr>';
		}
		echo '</tbody></table>';
		$paged = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
		$args = array(
			'base'               => '%_%',
			'format'             => '?paged=%#%',
			'total'              => $array->max_num_pages,
			'current'            => max(1, $paged),
			'show_all'           => true,
			'prev_next'          => true,
			'prev_text'          => __('« Previous'),
			'next_text'          => __('Next »'),
			'type'               => 'plain',
			'add_args'           => true,
			'add_fragment'       => '',
			'before_page_number' => '',
			'after_page_number'  => ''
		); 
		echo paginate_links( $args );
		echo '<p><a href="'.home_url ("wp-posts-export.csv").'" class="button">'.__('Generate CSV file').'</a></p>';
    }

    public function eapm_render_export_page() {

        if ($this->settings) {

            echo __('<p>This plugin will export all selected posts with the following post meta fields (with current value if available):</p>');

            echo '<ul>';
			
			if(!empty($this->settings['meta_keys']))
			{
				foreach ($this->settings['meta_keys'] as $meta_key) {
					echo "<li><strong>$meta_key</strong></li>";
				}
			}

            echo '</ul>';

            echo '<p><a href="'.home_url ("wp-posts-export.csv").'" class="button">'.__('Generate CSV file').'</a></p>';

        } else {
            echo __('<p>You need configure the settings for this plugin. See Batch settings under the Tools menu.</p>');
        }
    }

    public function eapm_create_post_csv() {
		$path = $_SERVER['REQUEST_URI'];
        if (is_user_logged_in() && (stripos(strtolower($path), 'wp-posts-export.csv') == true)) {

			header("Content-type: text/csv; charset=utf-8",true,200);
            header("Content-Disposition: attachment; filename=wp-posts-export.csv");
            header("Pragma: no-cache");
            header("Expires: 0"); 
			
			$posts = new WP_Query(array(
                'post_status' => $this->settings['post_statuses'],
                'post_type' => $this->settings['post_types']
            ));
			
			$headings = $this->settings['post_keys'];
			
            if (!empty($this->settings['meta_keys'])) {

                foreach ($this->settings['meta_keys'] as $meta_key) {
                    $headings[] = $meta_key;
                }
            }
            $fh = fopen('php://output', 'w');

            ob_start();

            fputcsv($fh, $headings);
           
			
            foreach ($posts->posts as $row) 
			{
				$cloumn = [];
				$post_key_counter = 0;
				if(!empty($this->settings['post_keys']))
				{
					foreach ($this->settings['post_keys'] as $post_key) {
						$cloumn[] = $row->$post_key;
						$post_key_counter++;
					}
				}
				
				$meta_key_counter = $post_key_counter;
				if(!empty($this->settings['meta_keys']))
				{
					foreach ($this->settings['meta_keys'] as $meta_key) {
						$result = maybe_serialize(get_post_meta( $row->ID, $meta_key, true));
						if( is_serialized($result) ){
			
						$column_arr = maybe_unserialize($result);
						$string = implode(' | ', array_filter(array_map(
								function ($v, $k) {
									if(is_array($v)){
										$v = array_filter($v);
										return $k.'[]='.implode(',' , $v);
									}else{
										return $k.'='.$v;
									}
								}, 
								$column_arr, 
								array_keys($column_arr)
							)));
							
							
							$cloumn[] = $string;

						}
						else{
							$cloumn[] = $result;
						}
						
						$meta_key_counter++;
					}
				}
                                fputcsv($fh, $cloumn);
			}
			$string = ob_get_clean();
            fclose($fh);
            exit($string);
		}
	}

	public function eapm_get_posts_by_name($post_type = 'post')
	{
		$post_ids = array();
		if(empty($post_type)){
			return;
		}

		$args = array (
			'post_type'              => $post_type,
			'post_status'            => array( 'publish' ),
		);

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_ids[] = get_the_ID();
			}
		} 
		// Restore original Post Data
		wp_reset_postdata();
		return $post_ids;
	}
	
	public function eapm_get_meta_keys_array($post_ids){
		$custom_field_keys = array();
		if(empty($post_ids)){
			return;
		}
		foreach($post_ids as $id){
			$custom_field_keys[] = get_post_custom_keys($id);
		}
		return $custom_field_keys;
	}
	
	public function eapm_get_post_from_settings(){
	    
			$paged = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
			$posts = new WP_Query(array(
                'posts_per_page' => 5,
				'paged' => $paged,
                'post_status' => $this->settings['post_statuses'],
                'post_type' => $this->settings['post_types']
            ));
			return $posts;
	}
	
}