<?php
/*
Plugin Name: MindValley Include Content
Plugin URI: http://mindvalley.com
Description: Creates shortcode [mv_include] to include content from another post/page.
Author: MindValley
Version: 1.3.2
*/

/**
 *  Usage:
 *  [mv_include id='4'] (best for performance)
 *  [mv_include slug='the-post-slug']
 *  [mv_include path='http://www.example.com/parent-page/sub-page/']
 *  [mv_include path='parent-page/sub-page']
 */ 

class mvIncludeContent {
	function __construct(){
		add_action('init', array(&$this, 'add_shortcode'));
		add_action( 'init', array(&$this, 'create_post_type'));
		add_action('admin_init', array(&$this, 'add_custom_metabox'));
		add_action('admin_head', array(&$this, 'column_css'));
		
		add_action( 'admin_bar_menu', array(&$this, 'wp_admin_bar'), 100 );
		add_action( 'wp_after_admin_bar_render', array(&$this, 'wp_after_admin_bar_render'));
		if( is_admin() )
			$this->enqueue_scripts_styles();
			
		add_filter( 'manage_edit-include_columns', array(&$this, 'column_title'), 100 );
		add_filter( 'manage_include_posts_custom_column', array(&$this, 'column_data'), 100 , 2);
	}
	
	function column_css(){
		echo '<style>
			.widefat .column-id {
				width: 4em;
			}
		</style>';
	}
	
	function column_title($sortables){
		$sortables = array_merge(array_slice($sortables,0,1), array( 'id' => 'ID'), array_slice($sortables,1));
		return $sortables;
	}
	
	function column_data($column_name, $id){
		global $wpdb;
		switch ($column_name) {
			case 'id':
				echo $id;
		        break;

			default:
				break;
		} // end switch
	}
	
	function create_post_type() {
		$labels = array(
		    'name' => _x('Includes', 'post type general name'),
		    'singular_name' => _x('Include', 'post type singular name'),
		    'add_new' => _x('Add New', 'include'),
		    'add_new_item' => __('Add New Include'),
		    'edit_item' => __('Edit Include'),
		    'new_item' => __('New Include'),
		    'all_items' => __('All Includes'),
		    'view_item' => __('View Include'),
		    'search_items' => __('Search Includes'),
		    'not_found' =>  __('No includes found'),
		    'not_found_in_trash' => __('No includes found in Trash'), 
		    'parent_item_colon' => '',
		    'menu_name' => 'Includes'

		  );
		  $args = array(
		    'labels' => $labels,
		    'public' => false,
		    'publicly_queryable' => false,
		    'show_ui' => true, 
		    'show_in_menu' => true,
		    'query_var' => true,
		    'rewrite' => true,
		    'capability_type' => 'page',
		    'has_archive' => true,
		    'hierarchical' => true,
		    'menu_position' => null,
		    'supports' => array('title','editor','custom-fields')
		  ); 
		  register_post_type('include',$args);
	}
	
	function wp_admin_bar(){
		global $wp_admin_bar;
		$wp_admin_bar->add_menu( array( 'id' => 'mv_include_toggle', 'title' => 'Toggle Include Layer', 'href' => '#' ) );
	}
	
	function wp_after_admin_bar_render(){
		?>
		<script>
			jQuery('#wp-admin-bar-mv_include_toggle a').click(function(){
				jQuery('div.mv_include').toggleClass('show');
				jQuery('div.mv_include').each(function(){
					var thediv = jQuery(this);
					if(thediv.hasClass('show')){
						jQuery(this).children('div').each(function(){
							if(jQuery(this).css('float') != 'none'){
								thediv.css('float',jQuery(this).css('float'));
							}
						});
					}
				});
				return false;
			});
		</script>
		<style type="text/css">
		
			div.mv_include.show .edit,
			div.mv_include.show .info {
				display:inline !important;
				position:absolute;
				background:#ff0000;
				padding: 0 15px;
				color:#ffffff;
				top:0;
			}
			
			div.mv_include.show .edit {
				right:0;
			}
			
			div.mv_include.show .info {
				left:0;
			}
			
			
			div.mv_include.show .edit a {
				color:#ffffff;
				text-decoration:none;
			}
			
			div.mv_include.show {
				position:relative;
				border:2px solid red;
				padding:5px;
				margin:-7px 0 10px -7px ;
			}
		</style>
		<?php
	}
	
	function enqueue_scripts_styles(){
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-tooltip', plugins_url('/jquery.tooltip.min.js', __FILE__), array('jquery'));
		wp_register_style('jquery-tooltip', plugins_url('/jquery.tooltip.css', __FILE__));
        wp_enqueue_style('jquery-tooltip');
	}
	
	function add_custom_metabox(){
		add_meta_box( 'mv_showincluded', __( 'Included Content', 'mindvalley' ), 
                array(&$this, 'showincluded_metabox'), 'post', 'side', 'high' );
		add_meta_box( 'mv_showincluded', __( 'Included Content', 'mindvalley' ), 
				array(&$this, 'showincluded_metabox'), 'page', 'side', 'high' );
	}
	
	function add_shortcode(){
		add_shortcode("mv_include", array(&$this, 'include_content'));
	}
	
	function showincluded_metabox(){
		global $post;
		$post_content = $post->post_content;
		if ( !user_can_richedit() ) {
			$post_content = htmlspecialchars_decode( $post_content, ENT_QUOTES );
		}

		preg_match_all('/(.?)\[(mv_include)\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?(.?)/s', $post_content, $m);
		
		$included_post = array();

		for($i=0;$i<count($m[0]);$i++){
			// allow [[foo]] syntax for escaping a tag
			if ( $m[1][$i] != '[' || $m[6][$i] != ']' ) {
				$tag = $m[2][$i];
				$atts = shortcode_parse_atts( $m[3][$i] );
				
				$thepostid = (int) $atts['id'];
				$thepostslug = $atts['slug'];
				$thepagepath = str_replace(get_bloginfo('url'),'',$atts['path']);

				if(!empty($thepostid)){
					$find = get_post( $thepostid );
				}elseif(!empty($thepostslug)){
					$find = $this->get_post_by_slug($thepostslug);
				}elseif(!empty($thepagepath)){
					$find = get_page_by_path( $thepagepath );
				}else{
					$find = false;
				}
				
				if ( $find ){
					$included_post[$find->ID] = $find;
				}
					
			}
		}
		echo '<ol>';
		if(!empty($included_post)){
			foreach($included_post as $p){
				$edit_link = get_edit_post_link($p->ID);
				?>
				<li>
			    	<a class="title" href="<?php echo get_permalink( $p->ID )?>" target="_blank" rev="#mv_ic_fn<?php echo $p->ID?>"><?php echo (strlen($p->post_title) > 25) ? substr($p->post_title,0,25) . " ..." : $p->post_title;?></a>
				<?php 
					if(!empty($edit_link)){
						?>
			            	<a href="<?php echo $edit_link?>" target="_blank" style="float:right;">[EDIT]</a>
			            <?php				
					}
				?>
			    <div id="mv_ic_fn<?php echo $p->ID?>" style="display:none">
					<h3><?php echo $p->post_title;?></h3>
			        <?php
						$output = '';
						$findparent = $p;
						$i = 0;
						while ($findparent->post_parent)	{
							$findparent = get_post($findparent->post_parent);

							if(empty($output))
								$output = '<strong>' . $findparent->post_title . '</strong>';
							else
								$output = '<strong>' . $findparent->post_title . '</strong>' . ' > ' . $output;

						} 
						if(!empty($output))
							echo "<strong>Parents:</strong> " . $output;

					?>
			        <br />[ID: <?php echo $p->ID?>]
			        <br /><br />
			        <?php 
						if(has_excerpt($p->ID))
							echo htmlspecialchars($p->post_excerpt);
						else
							echo htmlspecialchars($p->post_content);
					?>
			    </div>
			    </li>
				<?php
			}
		}
		echo '</ol>';
		?>
        <script language="javascript">
			jQuery('#mv_showincluded a.title').tooltip({ 
				bodyHandler: function() { 
					return jQuery(jQuery(this).attr("rev")).html(); 
				},
				track:true, 
				showURL: false 
			});
		</script>
        <?php
	}
	
	
	function include_content( $atts ){
		
		$thepostid = (int) $atts['id'];
		$thepostslug = $atts['slug'];
		$thepagepath = str_replace(get_bloginfo('url'),'',$atts['path']);
		if(!empty($thepostid)){
			$post = get_post( $thepostid );
		}elseif(!empty($thepostslug)){
			$post = $this->get_post_by_slug($thepostslug);
		}elseif(!empty($thepagepath)){
			$post = get_page_by_path( $thepagepath, OBJECT, 'page' );
		}else{
			$post = false;
		}
		if ( !$post )
		    return '';
		
		$content = apply_filters( 'the_content', $post->post_content );

		if( current_user_can('edit_posts')){
			$edit_link = get_edit_post_link($post->ID);
			$atts_string = '';
			foreach($atts as $key => $value){
				$atts_string .= ' ' . $key . '="' . $value . '"';
			}
			$content = '<div class="mv_include"><div class="info" style="display:none">[mv_include '.$atts_string.']</div><div class="edit" style="display:none"><a href="'.$edit_link.'" target="_blank">Edit</a></div>' . $content . '</div>';
		}
		

		return $content;
	}

	function get_post_by_slug($post_name, $output = OBJECT){
	    global $wpdb;
	    $post = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND (post_type = 'post' OR post_type='page')", $post_name ));
	    if ( $post )
	        return get_post($post, $output);

	    return null;
	}

}

new mvIncludeContent();