<?php
/**
* @package Scheduled_Announcements_Widget
* @version 1.0
*/
/*
Plugin Name: Scheduled Announcements Widget
Plugin URI: http://nlb-creations.com/2012/02/01/wordpress-plugin-scheduled-announcement-widget/
Description: This plugin created a set of scheduled announcements that can be displayed as a widget or with shortcode.
Author: Nikki Blight <nblight@nlb-creations.com>
Version: 1.0
Author URI: http://www.nlb-creations.com
*/

// Activation
function saw_plugin_activation(){
    do_action( 'saw_plugin_default_options' );
}
register_activation_hook( __FILE__, 'saw_plugin_activation' );

// Set default settings values
function saw_plugin_default_values(){
    add_option('saw_scroll_options', 'horizontal');
    add_option('saw_speed_options', '4000');
    add_option('saw_trans_options', '800');
    add_option('saw_width_options', '200');
    add_option('saw_height_options', '120');
    add_option('saw_text_color_options', '333333');
    add_option('saw_link_color_options', '0000ff');
    add_option('saw_order_options', 'ASC');
}
add_action( 'saw_plugin_default_options', 'saw_plugin_default_values' );

//add the Settings link to the plugin page
function saw_action_links( $links, $file ) {
    if ( $file != plugin_basename( __FILE__ )) {
    	return $links;
    }

	$settings_link = '<a href="edit.php?post_type=sched_announcement&page=scheduled-announcement-widget">' . __( 'Settings', 'scheduled-announcements-widget' ) . '</a>';
	array_unshift( $links, $settings_link );

	return $links;
}
add_filter( 'plugin_action_links', 'saw_action_links',10,2);

//jQuery MUST be available, so just in case the theme doesn't use it, enqueue it now
function saw_scripts() {
	wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'saw_scripts' );

//add the jQuery needed in the admin section
function saw_admin_scripts() {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_style('jquery-ui');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_register_style('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
}
add_action( 'admin_enqueue_scripts', 'saw_admin_scripts' );

//there are some known conflicts with the Event Planner plugin on the settings page, so let's dump the javascript and styles for that plugin if it's installed.
function saw_remove_conflicts() {
	if(stristr($_SERVER['REQUEST_URI'], "wp-admin/edit.php?post_type=sched_announcement")) {
		wp_dequeue_script('epl-event-manager-js');
		wp_dequeue_script('epl-forms-js');
		wp_dequeue_script('events_planner_js');
		wp_dequeue_style('events-planner-jquery-ui-style');
		wp_dequeue_style('events-planner-stylesheet');
	}
}
add_action('admin_init', 'saw_remove_conflicts');

//create a custom post type to hold custom data
function saw_create_post_types() {
	register_post_type( 'sched_announcement',
	array(
			'labels' => array(
				'name' => __( 'Announcements' ),
				'singular_name' => __( 'Announcement' ),
				'add_new' => __( 'Add Announcement'),
				'add_new_item' => __( 'Add Announcement'),
				'edit_item' => __( 'Edit Announcement' ),
				'new_item' => __( 'New Announcement' ),
				'view_item' => __( 'View Announcement' )
	),
			'show_ui' => true,
			'description' => 'Post type for Announcements',
			'menu_position' => 5,
			'menu_icon' => WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) . '/saw-menu-icon.png',
			'public' => true,
			'taxonomies' => array('saw_categories'),
			'supports' => array('title', 'editor', 'page-attributes'),
			'can_export' => true
	)
	);
}
add_action( 'init', 'saw_create_post_types' );

//create custom taxonomies for the new post types
function saw_create_taxonomy() {
	register_taxonomy('saw_categories', array('sched_announcement'),
	array(
			'hierarchical' => true, 
			'label' => 'Announcement Categories', 
			'singular_label' => 'Announcement Category',
			'public' => true,
			'show_tagcloud' => false,
			'query_var' => true
		)
	);
}
add_action('init', 'saw_create_taxonomy');

//Add boxes to the edit screens for a qrcode post type
function saw_dynamic_add_custom_box() {
	
	add_meta_box(
		'dynamic_saw_dates',
	__( 'Dates (optional)', 'myplugin_textdomain' ),
		'saw_dates_custom_box',
		'sched_announcement',
		'side');
}
add_action( 'add_meta_boxes', 'saw_dynamic_add_custom_box' );

//print the custom meta box
function saw_dates_custom_box() {
	global $post;
    // Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'dynamicMetaDate_noncename' );
    
    echo '<div id="meta_inner">';

    //get the saved metadata
    $start = get_post_meta($post->ID,'saw_start_date',true);
    $end = get_post_meta($post->ID,'saw_end_date',true);
    ?>
    <em>Both dates must be set to use scheduling feature.</em><br /><br />
	
	<script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#datepicker-start').datepicker({
            	dateFormat : 'yy-mm-dd'
            });
            
            $('#datepicker-end').datepicker({
            	dateFormat : 'yy-mm-dd'
            });
        });
    </script>
	
	<strong>Start Date: </strong><br />
	<input type="text" id="datepicker-start" name="saw_start_date" <?php if($start != '') { echo 'value="'.$start.'"'; } ?>>
	<br /><br />
	<strong>End Date: </strong><br />
	<input type="text" id="datepicker-end" name="saw_end_date" <?php if($end != '') { echo 'value="'.$end.'"'; } ?>>
	
    <?php 
	echo '</div>';
}

//when the post is saved, save our custom postmeta too
function saw_dynamic_save_postdata( $post_id ) {
	//if our form has not been submitted, we dont want to do anything
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// verify this came from the our screen and with proper authorization
	if (isset($_POST['dynamicMetaDate_noncename'])){
		if ( !wp_verify_nonce( $_POST['dynamicMetaDate_noncename'], plugin_basename( __FILE__ ) ) )
		return;
	}
	else {
		return;
	}
	//save the data
	$start = $_POST['saw_start_date'];
	$end = $_POST['saw_end_date'];

	update_post_meta($post_id,'saw_start_date',$start);
	update_post_meta($post_id,'saw_end_date',$end);
}
add_action( 'save_post', 'saw_dynamic_save_postdata' );

//get the info for the announcement set
function saw_get_current($order = 'ASC', $tax = '') {

	//get the current date
	$now = date('Y-m-d', strtotime(current_time("mysql")));

	//set the arguements for get_posts
	$args = array(
	    'post_type' => 'sched_announcement',
	    'post_status' => 'publish',
	    'order_by' => 'menu_order',
	    'order' => $order,
	    'meta_query' => array(
	        array(
	            'key' => 'saw_start_date',
	            'value' => $now,
	            'type' => 'DATE',
                'compare' => '<='
	        ),
	        array(
	            'key' => 'saw_end_date',
	            'value' => $now,
	            'type' => 'DATE',
                'compare' => '>='
	        )
	    )
	);
	
	//if a taxonomy has been specified, add that in to the arguements, too
	if($tax != '' && $tax != "0") {
	    $args['tax_query'] = array(
	        //'relation' => 'AND',
	        array(
	            'taxonomy' => 'saw_categories',
	            'field' => is_numeric($tax) ? 'term_id' : 'slug',
	            'terms' => $tax
	        )
	    );
	}
	
	$announcements = get_posts($args);
	return $announcements;
}

//shortcode function for use in theme
function saw_show_widget($atts) {
	extract( shortcode_atts( array(
		'title' => '',	
		'show_titles' => 1,
		'scroll' => '',
		'speed' => '',
		'transition' => '',
		'width' => '',
		'height' => '',
		'link' => '',
		'text' => '',
		'order' => '',
		'saw_id' => 'saw_ticker_shortcode',
		'tax' => ''
	), $atts ) );
	
	//if settings are not specified in the short code, use the option settings.  Sanitize everything while we're at it.
	$saw_id = sanitize_text_field(esc_html(str_replace("-", "", $saw_id))); //Hypens screw up the javascript. Remove them.
	$title = sanitize_text_field(esc_html($title));
	$show_titles = sanitize_text_field(esc_html($show_titles));
	$scroll_settings = !$scroll ? get_option('saw_scroll_options') : sanitize_text_field(esc_html($scroll));
	$speed_settings = !$speed ? get_option('saw_speed_options') :  sanitize_text_field(esc_html($speed));
	$trans_settings = !$transition ? get_option('saw_trans_options') : sanitize_text_field(esc_html($transition));
	$width_settings = !$width ? get_option('saw_width_options') : sanitize_text_field(esc_html($width));
	$height_settings = !$height ? get_option('saw_height_options') : sanitize_text_field(esc_html($height));
	$text_settings = !$text ? get_option('saw_text_color_options') : sanitize_hex_color_no_hash(esc_html($text));
	$link_settings = !$link ? get_option('saw_link_color_options') : sanitize_hex_color_no_hash(esc_html($link));	
	$order_settings = !$order ? get_option('saw_order_options') : sanitize_text_field(esc_html($order));
	
	//fetch the current announcememts
	$announcements = saw_get_current($order_settings, $tax);
	
	$output = '';
	if(count($announcements) > 0) {
		$output .= '<style type="text/css">';
		$output .= 'div.saw_announcements_'.$saw_id.' {';
		$output .= '	width: '.$width_settings.'px;';
		$output .= '	overflow: hidden;';
		$output .= '}';
					
		$output .= 'div.saw_announcements_'.$saw_id.'.horizontal ul#'.$saw_id.' {';
		$output .= '    width: '.($width_settings*2).'px;';
		$output .= '    height: '.$height_settings.'px;';
		$output .= '    overflow: hidden;';
		$output .= '	margin: 0px;';
		$output .= '}';
					
		$output .= 'div.saw_announcements_'.$saw_id.'.vertical ul#'.$saw_id.' {';
		$output .= '	width: '.$width_settings.'px;';
		$output .= '	height: '.$height_settings.'px;';
		$output .= '	overflow: hidden;';
		$output .= '	margin: 0px;';
		$output .= '}';
					 
		$output .= 'ul#'.$saw_id.' li {';
		$output .= '	width: '.$width_settings.'px;';
		$output .= '	height: '.$height_settings.'px;';
		$output .= '	padding: 0px;';
		$output .= '	display: block;';
		$output .= '	float: left;';
		$output .= '}';
					 
		$output .= 'ul#'.$saw_id.' li a {';
		$output .= '	color: #'.$link_settings.';';
		$output .= '}';
					
		$output .= 'ul#'.$saw_id.' li span {';
		$output .= '	display: inline;';
		$output .= '	color: #'.$text_settings.';';
		$output .= '}';
					
		$output .= '.saw_title {';
		$output .= '	font-weight: bold;';
		$output .= '}';
		$output .= '</style>';
		
		
		$output .= '<div class="saw_announcements_'.$saw_id.' '.$scroll_settings.'">';
		$rand = rand();
		if(count($announcements) > 1) {
			
			$output .= '<script type="text/javascript">';
			$output .= 'jQuery(function()';
			$output .= '{';
			$output .= '	var ticker = function()';
			$output .= '	{';
			$output .= "		jQuery('#".$saw_id." li:first').animate( {";
			
			if($scroll_settings == 'horizontal') {
				$output .= "marginLeft: '-".$width_settings."px'";
			}
			if($scroll_settings == 'vertical') {
				$output .= "marginTop: '-".$height_settings."px'";
			}
			
			$output .= '	}, '.$trans_settings.', function()';
			$output .= '			{';
			$output .= "				jQuery(this).detach().appendTo('ul#".$saw_id."').removeAttr('style');";
			$output .= '			});';
			$output .= '	interval;';
			$output .= '	};';
			$output .= '	var interval = setInterval(ticker, '.$speed_settings.');';
			$output .= "	jQuery('#".$saw_id." li:first').hover(function() {";
			$output .= '		clearInterval(interval);';
			$output .= '	}, function() {';
			$output .= "		interval = setInterval(ticker, ".$speed_settings.");";
			$output .= '		});';
			$output .= '	interval;';
			$output .= '});';
			$output .= '</script>';
		}
		
		if($title != '') {
			$output .= '<h2 class="saw_list_title">'.$title.'</h2>';
		}
		$output .= '	<ul id="'.$saw_id.'">';
	 
		foreach($announcements as $msg) {
			$output .= '<li class="saw_msg">';
			if($show_titles) {
				$output .= '<span class="saw_title">'.$msg->post_title.'</span><span class="saw_separator"></span> ';
			}
			$output .= '<span class="saw_content">'.apply_filters('the_content', $msg->post_content).'</span>';
			$output .= '</li>';
		}
		
		$output .= '	</ul>';
		$output .= '</div>';
	}
	return $output;
}
add_shortcode( 'announcements', 'saw_show_widget');

/* Sidebar widget functions */
class SAW_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            
            // Base ID of the widget
            'SAW_Widget',
            
            // Widget name that will appear in UI
            __('Scehduled Announcement Widget', 'saw_widget_domain'),
            
            // Widget description
            array( 'description' => __( 'DCurrently scheduled announements', 'saw_widget_domain' ), )
            );
    }

	function form($instance) {
		$instance = wp_parse_args((array) $instance, array( 'title' => '', 'show_tax' => '', 'show_titles' => '', 'ticker_id' => '' ));
		$title = $instance['title'];
		$show_titles = $instance['show_titles'];
		$show_tax = $instance['show_tax'];
		$ticker_id = $instance['ticker_id'];

		?>
			<p>
		  		<label for="<?php echo $this->get_field_id('title'); ?>">Title: 
		  		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		  		</label>
		  	</p>
		  	
		  	<p>
		  		<label for="<?php echo $this->get_field_id('show_titles'); ?>"> 
		  		<input id="<?php echo $this->get_field_id('show_titles'); ?>" name="<?php echo $this->get_field_name('show_titles'); ?>" type="checkbox" <?php if($show_titles) { echo 'checked="checked"'; } ?> /> 
		  		Show Announcment Titles in Widget
		  		</label>
		  	</p>
		  	
		  	<p>
		  		<label>Show announcements only in this category:</label><br />
		  		<small><em>If list is empty, you have not created any announcement categories.</em></small><br />
		  		<?php
		  			$args = array('name' => $this->get_field_name('show_tax'),
									'id' => $this->get_field_id('show_tax'),
    								'taxonomy' => 'saw_categories',
		  							'show_option_all' => 'All categories',
		  							'selected' => esc_attr($show_tax)); 
		  			wp_dropdown_categories( $args );
		  		?>
		  		
			</p>
			
			<p>
		  		<label for="<?php echo $this->get_field_id('ticker_id'); ?>">Ticker ID (optional): <br />
		  		<small><em>Unique CSS ID.  Use this field if you need multiple announcement tickers on a page.</em></small>
		  		<input class="widefat" id="<?php echo $this->get_field_id('ticker_id'); ?>" name="<?php echo $this->get_field_name('ticker_id'); ?>" type="text" value="<?php echo esc_attr($ticker_id); ?>" />
		  		</label>
		  	</p>
		<?php
	}
 
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['show_titles'] = ( isset( $new_instance['show_titles'] ) ? 1 : 0 );
		$instance['show_tax'] = $new_instance['show_tax'];
		$instance['ticker_id'] = str_replace("-", "_", $new_instance['ticker_id']); //Hyphens in the id screw up the javascript
		return $instance;
	}
 
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
 
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$show_titles = $instance['show_titles'];
		$show_tax = $instance['show_tax'];
		$ticker_id = $instance['ticker_id'];
		
		//get widget options
		$scroll_settings = get_option('saw_scroll_options');		
		$speed_settings = get_option('saw_speed_options');
		$trans_settings = get_option('saw_trans_options');
		$width = get_option('saw_width_options');
		$height = get_option('saw_height_options');
		$text = get_option('saw_text_color_options');
		$link = get_option('saw_link_color_options');
		$order = get_option('saw_order_options');
		
 		//fetch the current announcements and assign a random id number
		$announcements = saw_get_current($order, $show_tax);
		
		if($ticker_id != '') {
			$widget_id = $ticker_id;	
		}
		else {
			$widget_id = rand(0,9999999);
		}

		if(count($announcements) > 0) {
			echo $before_widget;
			?>
			<style type="text/css">
				div.saw_announcements {
					width: <?php echo $width; ?>px;
					overflow: hidden;
				}
				
				div.saw_announcements.horizontal ul#saw_ticker-<?php echo $widget_id; ?> {
				    width: <?php echo ($width)*2; ?>px;
				    height: <?php echo $height; ?>px;
				    overflow: hidden;
				    margin: 0px;
				}
				
				div.saw_announcements.vertical ul#saw_ticker-<?php echo $widget_id; ?> {
				    width: <?php echo $width; ?>px;
				    height: <?php echo $height; ?>px;
				    overflow: hidden;
				    margin: 0px;
				}
				 
				ul#saw_ticker-<?php echo $widget_id; ?>  li {
				    width: <?php echo $width; ?>px;
				    height: <?php echo $height; ?>px;
				    padding: 0px;
				    display: block;
				    float: left;
				}
				 
				ul#saw_ticker-<?php echo $widget_id; ?> li a {
				    color: #<?php echo $link; ?>;
				}
				
				ul#saw_ticker-<?php echo $widget_id; ?> li span {
				    display: inline;
				    color: #<?php echo $text; ?>;
				}
				
				.saw_title {
					font-weight: bold;
				}
			</style>
			
			<div class="widget saw_announcements <?php echo $scroll_settings; ?>">
				<?php if(count($announcements) > 1): ?>
				<script type="text/javascript">
					jQuery(function()
						{
							//animate the ticker
						    var ticker<?php echo $widget_id; ?> = function()
						    {
						        jQuery('#saw_ticker-<?php echo $widget_id; ?> li:first').animate( {<?php if($scroll_settings == 'horizontal') { echo "marginLeft: '-".$width."px'"; } if($scroll_settings == 'vertical') { echo "marginTop: '-".$height."px'"; } ?>}, <?php echo $trans_settings; ?>, function()
								{
									jQuery(this).detach().appendTo('ul#saw_ticker-<?php echo $widget_id; ?>').removeAttr('style');
								});
	
								//after the animation completes, set the interval again
								interval;
							};
	
							//set the interval for the animation
							var interval = setInterval(ticker<?php echo $widget_id; ?>, <?php echo $speed_settings; ?>);
	
							//on hover, pause the ticker, and restart it on mouseout
							jQuery('#saw_ticker-<?php echo $widget_id; ?> li:first').hover(function() {
								clearInterval(interval);
							}, function() {
								interval = setInterval(ticker<?php echo $widget_id; ?>, <?php echo $speed_settings; ?>);
							});
	
							//start the interval for the first animation
							interval;					    
						});
				</script>
				<?php endif; ?>
				<?php 
					if (!empty($title)) {
						echo $before_title . $title . $after_title;
					}
					else {
						echo $before_title.$after_title;
					}
				?>
				
				<ul id="saw_ticker-<?php echo $widget_id; ?>">
					<?php 
					
					foreach($announcements as $msg) {
						echo '<li class="saw_msg">';
						if($show_titles) {
							echo '<span class="saw_title">'.$msg->post_title.'</span><span class="saw_separator"></span> ';
						}
						
						echo '<span class="saw_content">'.apply_filters('the_content', $msg->post_content).'</span>';
						echo '</li>';
					}
					?>
				</ul>
			</div>
			<?php
 
			echo $after_widget;
		}
	}
}

/* Admin Functions */

//create a menu item for the options page
function saw_admin_menu() {
	if (function_exists('add_options_page')) {
		//add to Announcements tab
		add_submenu_page( 'edit.php?post_type=sched_announcement', 'Announcement Settings', 'Settings', 'manage_options', 'scheduled-announcement-widget', 'saw_admin_options' );
	}
}
add_action( 'admin_menu', 'saw_admin_menu' );

//output the options page
function saw_admin_options() {

  	//watch for form submission
	if (!empty($_POST['saw_scroll_options'])) {
    	//validate the referrer field
		check_admin_referer('saw_options_valid');
 
		//update options settings
		update_option('saw_scroll_options', $_POST['saw_scroll_options']);
		update_option('saw_speed_options', $_POST['saw_speed_options']);
		update_option('saw_trans_options', $_POST['saw_trans_options']);
		update_option('saw_width_options', $_POST['saw_width_options']);
		update_option('saw_height_options', $_POST['saw_height_options']);
		update_option('saw_text_color_options', $_POST['saw_text_color_options']);
		update_option('saw_link_color_options', $_POST['saw_link_color_options']);
		update_option('saw_order_options', $_POST['saw_order_options']);
		
		//show success
		echo '<div id="message" class="updated fade"><p><strong>' . __('Your configuration settings have been saved.') . '</strong></p></div>';
	}
 
	//display the admin options page
	$scroll_settings = get_option('saw_scroll_options');
?>
<style type="text/css">
     .form-table {
         table-layout: auto !important;
         width: auto !important;
     }
     
     .form-table tr td {
        vertical-align: top;
        width: auto !important;
     }
     
     .form-table tr th {
        padding-left: 5px;
        vertical-align: top;
        width: auto !important;
     }
     
    .form-table tr:nth-child(even) {
      background-color: #dfdddd;
    }
    
    .saw-settings {
        padding: 10px;
        width: auto !important;
    }
</style>

<div class="saw-settings">
	<h2><?php _e('Scheduled Announcement Options'); ?></h2>
	<form action="" method="post" id="me_likey_form" accept-charset="utf-8" style="position:relative">
		
		<?php wp_nonce_field('saw_options_valid'); ?>
		
		<input type="hidden" name="action" value="update" />
		<table class="form-table">
			
			<tr>
				<th scope="row">Order</th>
				<td>
					<?php $order_settings = get_option('saw_order_options'); ?>
					<select name="saw_order_options" id="saw_order_options">
						<option value="ASC" <?php if($order_settings == 'ASC') { echo 'selected="selected"'; } ?>>Ascending</option>
						<option value="DESC" <?php if($order_settings == 'DESC') { echo 'selected="selected"'; } ?>>Descending</option>
					</select>
				</td>
				<td>The order in which you would like announcments to scroll, based on the menu order field.</td>
			</tr>
			
			<tr>
				<th scope="row">Scroller Options</th>
				<td>
					<?php $scroll_settings = get_option('saw_scroll_options'); ?>
					<select name="saw_scroll_options" id="saw_scroll_options">
						<option value="vertical" <?php if($scroll_settings == 'vertical') { echo 'selected="selected"'; } ?>>Vertical</option>
						<option value="horizontal" <?php if($scroll_settings == 'horizontal') { echo 'selected="selected"'; } ?>>Horizontal</option>
					</select>
				</td>
				<td>The direction you want your announcements to scroll.</td>
			</tr>
			
			<tr>
				<th scope="row">Scroller Speed</th>
				<td>
					<input type="text" name="saw_speed_options" value="<?php echo get_option('saw_speed_options'); ?>" />
				</td>
				<td>The length of time (in milliseconds) an announcement should remain visible before scrolling to the next.</td>
			</tr>
			
			<tr>
				<th scope="row">Transition Speed</th>
				<td>
					<input type="text" name="saw_trans_options" value="<?php echo get_option('saw_trans_options'); ?>" />
				</td>
				<td>The length of time (in milliseconds) for the scrolling animation to complete.</td>
			</tr>
			
			<tr>
				<th scope="row">Widget width</th>
				<td>
					<input type="text" name="saw_width_options" value="<?php echo get_option('saw_width_options'); ?>" />
				</td>
				<td>The width of the scroller in pixels.</td>
			</tr>
			
			<tr>
				<th scope="row">Widget height</th>
				<td>
					<input type="text" name="saw_height_options" value="<?php echo get_option('saw_height_options'); ?>" />
				</td>
				<td>The height of the scroller in pixels.</td>
			</tr>
			
			<tr>
				<th scope="row">Text color</th>
				<td>
					<input type="text" id="colorField1" name="saw_text_color_options" value="<?php echo get_option('saw_text_color_options'); ?>" />
				</td>
				<td>Choose a color for text in the widget.</td>
			</tr>
			
			<tr>
				<th scope="row">Link color</th>
				<td>
					<input type="text" id="colorField2" name="saw_link_color_options" value="<?php echo get_option('saw_link_color_options'); ?>" />
				</td>
				<td>Choose a color for any links in the widget.</td>
			</tr>
			
			<tr>
				<td colspan="3">
					<input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes') ?>"/>
				</td>
			</tr>
		</table>
	</form>
</div>

<?php $dir = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); ?>
<script type="text/javascript" src="<?php echo $dir; ?>/js/jscolor/jscolor.js"></script>
<script type="text/javascript">
	var myPicker1 = new jscolor.color(document.getElementById('colorField1'), {});
	var myPicker2 = new jscolor.color(document.getElementById('colorField2'), {});
	myPicker1.fromString('<?php echo get_option('saw_text_color_options'); ?>');
	myPicker2.fromString('<?php echo get_option('saw_link_color_options'); ?>');
</script>
<?php
}

function saw_load_widget() {
    register_widget( 'SAW_Widget' );
}
add_action( 'widgets_init', 'saw_load_widget' );

?>