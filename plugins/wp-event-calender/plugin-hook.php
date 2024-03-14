<?php
/*
Plugin Name: Wordpress Event Calender 
Plugin URI: http://paisleyfarmersmarket.ca/sohels/
Description: This plugin will adds event calender feature in your wordpress.
Author: sohelwpexpert
Text Domain: event-calender-wordpress
Author URI: http://paisleyfarmersmarket.ca/sohels/
Version: 1.1
*/

/*Some Set-up*/
define('PRO_EVENT_CALENDER_WORDPRESS', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );



function ms_wp_event_calnder_plugin_main_js() {
/**
 * Register global styles & scripts.
 */
wp_register_style('event-calender-wordpress-css', PRO_EVENT_CALENDER_WORDPRESS.'css/style.css');

//wp_register_script('easy-news-js', PRO_EVENT_CALENDER_WORDPRESS.'js/jquery.ticker.min.js', array( 'jquery' ));

load_plugin_textdomain( 'event-calender-wordpress', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

/**
 * Enqueue global styles & scripts.
 */
 
wp_enqueue_style('event-calender-wordpress-css');

wp_enqueue_script('jquery');
}
add_action( 'wp_enqueue_scripts', 'ms_wp_event_calnder_plugin_main_js' );




/* Registering Plugin JS Active in Head*/
add_action( 'wp_head', 'proadd_event_calender_js_after_head' );

function proadd_event_calender_js_after_head () { ?>
<script type="text/javascript">
(function(jQuery){
	jQuery.fn.jflatTimeline = function(options){
	
		/**------------------ SETTING PARAMETERS ------------------**/
		
		var timelinedates = new Array();
		var date_sort_asc = function (date1, date2) {
			// This is a comparison function that will result in dates being sorted in
			// ASCENDING order. As you can see, JavaScript's native comparison operators
			// can be used to compare dates. This was news to me.
			if (date1 > date2) return -1;
			if (date1 < date2) return 1;
			return 0;
		};
		
		var current_year = 0;
		var current_month = 0;
		var scroll_count = 2;
		var scrolled = 0;
		var scroll_time = 500;
		
		var month=new Array();
		month[0]="<?php echo __( 'January', 'event-calender-wordpress' ); ?>";
		month[1]="<?php echo __( 'February', 'event-calender-wordpress' ); ?>";
		month[2]="<?php echo __( 'March', 'event-calender-wordpress' ); ?>";
		month[3]="<?php echo __( 'April', 'event-calender-wordpress' ); ?>";
		month[4]="<?php echo __( 'May', 'event-calender-wordpress' ); ?>";
		month[5]="<?php echo __( 'June', 'event-calender-wordpress' ); ?>";
		month[6]="<?php echo __( 'July', 'event-calender-wordpress' ); ?>";
		month[7]="<?php echo __( 'August', 'event-calender-wordpress' ); ?>";
		month[8]="<?php echo __( 'September', 'event-calender-wordpress' ); ?>";
		month[9]="<?php echo __( 'October', 'event-calender-wordpress' ); ?>";
		month[10]="<?php echo __( 'November', 'event-calender-wordpress' ); ?>";
		month[11]="<?php echo __( 'December', 'event-calender-wordpress' ); ?>";
		
		var config = {};
		if(options){
			jQuery.extend(config, options);
		}
		
		
		/**------------------ BEGIN FUNCTION BODY ------------------**/
		
		return this.each(function(){
			selector = jQuery(this);
			
			if(config.scroll)
				scroll_count = parseInt(config.scroll);
		
			if(config.width)
				selector.css('width', config.width)

			if(config.scrollingTime)
				scroll_time = config.scrollingTime;
				
		/**------------------ INSERT  YEAR MONTH BAR------------------**/
		
			//
			if(!selector.children('.timeline-wrap').children('.event.selected').length)
				selector.children('.timeline-wrap').children('.event:first-child').addClass('selected')
			//This store the selected year to 'current_year'
			
			current_year = (new Date(selector.children('.timeline-wrap').children('.event.selected').attr('data-date'))).getFullYear() 
			//This store the selected year to 'current_month'
			current_month = (new Date(selector.children('.timeline-wrap').children('.event.selected').attr('data-date'))).getMonth()
			
			//This will generate the month-year bar if it doesn't exist + put the current year and month
			if(!selector.children('.month-year-bar').length){
				selector.prepend('<div class = "month-year-bar"></div>')
				selector.children('.month-year-bar').prepend('<div class = "year"><a class = "event_calender_wp_prev"></a><span>' + String(current_year) + '</span><a class = "event_calender_wp_next"></a></div>')
				selector.children('.month-year-bar').prepend('<div class = "month"><a class = "event_calender_wp_prev"></a><span>' + String(month[current_month]) + '</span><a class = "event_calender_wp_next"></a></div>')
			}
			
		/**------------------ STORING DATES INTO ARRAY------------------**/

			var i = 0;
			// Store the dates into timelinedates[]
			selector.children('.timeline-wrap').children('.event').each(function(){
				timelinedates[i] = new Date(jQuery(this).attr('data-date'));
				i++;
			})
			//Sort the dates from small to large
			timelinedates.sort(date_sort_asc)
			
		/**------------------ INSERT DATES BAR------------------**/
			
			//This will insert the month year bar
				
				
			if(!selector.children(".dates-bar").length)
				selector.children(".month-year-bar").after('<div class = "dates-bar"><a class = "event_calender_wp_prev"></a><a class = "noevent">No event found</a><a class = "event_calender_wp_next"></a></div>')
			
			//This for loop will insert all the dates in the bar fetching from timelinedates[]
			for(i=0; i < timelinedates.length; i++){
				dateString = String((timelinedates[i].getMonth() + 1) + "/" + timelinedates[i].getDate() + "/" + timelinedates[i].getFullYear())
				if(selector.children('.dates-bar').children('a[data-date = "'+ dateString +'"]').length)
					continue;
				selector.children('.dates-bar').children('a.event_calender_wp_prev').after('<a data-date = '+ dateString + '><span class = "date">' + String(timelinedates[i].getDate()) + '</span><span class = "month">' + String(month[timelinedates[i].getMonth()]) + '</span></a>')
			}
			
			//This will convert the event data-date attribute from mm/dd/yyyy into m/d/yyyy
			for(i = 0; i < selector.children('.timeline-wrap').children('.event').length; i++){
				var a = new Date(selector.children('.timeline-wrap').children('.event:nth-child(' + String(i+1)+ ')').attr('data-date'))
				dateString = String((a.getMonth() + 1) + "/" + a.getDate() + "/" + a.getFullYear())
				selector.children('.timeline-wrap').children('.event:nth-child(' + String(i+1)+ ')').attr('data-date', dateString)
			}
			
			
			//This will hide the noevent bar
			selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent)').each(function(){
				if((new Date(jQuery(this).attr('data-date'))).getFullYear() != current_year)
					jQuery(this).hide();
			})
			
			//event_calender_wp_prevent from calling twice
			if(selector.hasClass('calledOnce'))
				return 0;
			selector.addClass('calledOnce')
			
			//Add 'selected' class the date
			selector.children('.dates-bar').children('a[data-date ="' + String(selector.children('.timeline-wrap').children('.event.selected').attr('data-date')) + '"]').addClass('selected')
			//Adding Class s_screen
			if(selector.width() < 500)
				selector.addClass('s_screen')
				
			jQuery(window).resize(function(){
				if(selector.width() < 500)
					selector.addClass('s_screen')
				else
					selector.removeClass('s_screen')	
			})
		/**------------------ EVENTS HANDLING------------------**/

		/**------------------ EVENTS FOR CLICKING ON THE DATES ------------------**/
		
			selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent)').click(function(){
				a = String(jQuery(this).attr('data-date'));

				selector.children('.timeline-wrap').children('.event.selected').removeClass('selected');

				selector.children('.timeline-wrap').children('.event[data-date="' + a + '"]').addClass('selected');
				
				selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent)').removeClass('selected');
				jQuery(this).addClass('selected')

			})
			
		/**------------------ EVENTS FOR CLICKING TO THE event_calender_wp_next DATE EVENT ------------------**/
			
			selector.children('.dates-bar').children('a.event_calender_wp_next').click(function(){
				var actual_scroll = scroll_count;
				var c = selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible()').length
				if(scrolled + scroll_count >= c)
					actual_scroll = (c - scrolled)-1
				
				if(parseInt(selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').css('width'))*actual_scroll > selector.children('.dates-bar').width())
					while(parseInt(selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').css('width'))*actual_scroll > selector.children('.dates-bar').width() && actual_scroll > 1)
						actual_scroll -= 1;
				
				var a = (-1)*actual_scroll*parseInt(selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').css('width'));
				selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').animate({marginLeft: '+=' + String(a)+ 'px'}, scroll_time)
				scrolled += actual_scroll;
				
				current_month = new Date(selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(' + String(scrolled) + ')').attr('data-date')).getMonth()
				
				selector.children('.month-year-bar').children('.month').children('span').text(month[current_month])
			})
			
			
		/**------------------ EVENTS FOR CLICKING TO THE event_calender_wp_prevIOUS DATE EVENT ------------------**/
			
			
			selector.children('.dates-bar').children('a.event_calender_wp_prev').click(function(){
				var actual_scroll = scroll_count;
				var c = selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible()').length
				if(scrolled <= scroll_count)
					actual_scroll = scrolled;

				if(parseInt(selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').css('width'))*actual_scroll > selector.children('.dates-bar').width())
					while(parseInt(selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').css('width'))*actual_scroll > selector.children('.dates-bar').width() && actual_scroll > 1)
						actual_scroll -= 1;

					
				var a = actual_scroll*parseInt(selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').css('width'));
				selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').animate({marginLeft: '+=' + String(a)+ 'px'}, scroll_time)
				scrolled -= actual_scroll;
				
				current_month = new Date(selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(' + String(scrolled) + ')').attr('data-date')).getMonth()
				
				selector.children('.month-year-bar').children('.month').children('span').text(month[current_month])
			})
			
			
		/**------------------ EVENTS FOR CLICKING TO THE event_calender_wp_next MONTH ------------------**/
			
			selector.children('.month-year-bar').children('.month').children('.event_calender_wp_next').click(function(){

				if(!(current_month == 11))
					current_month += 1;
				else
					current_month = 0;
					
				var month_found = 0;
				
				selector.children('.month-year-bar').children('.month').children('span').text(month[current_month])
				selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible()').each(function(){
						month_found += 1 ;
					if((new Date(jQuery(this).attr('data-date'))).getMonth() >= current_month){
						return false;
					}
				})
				
				
				var a = (month_found-scrolled-1)*parseInt(selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').css('width'));
				selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').animate({marginLeft: '-=' + String(a)+ 'px'}, scroll_time)
				scrolled = month_found - 1;
				
			})			
			
		/**------------------ EVENTS FOR CLICKING TO THE event_calender_wp_prevIOUS MONTH ------------------**/
			
			
			selector.children('.month-year-bar').children('.month').children('.event_calender_wp_prev').click(function(){
				if(!(current_month == 0))
					current_month -= 1;
				else
					current_month = 11;
					
				var month_found = 0;
				
				selector.children('.month-year-bar').children('.month').children('span').text(month[current_month])
				selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible()').each(function(){
						month_found += 1 ;
					if((new Date(jQuery(this).attr('data-date'))).getMonth() >= current_month){
						return false;
					}
				})
				
				
				var a = (month_found-scrolled-1)*parseInt(selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').css('width'));
				selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').animate({marginLeft: '-=' + String(a)+ 'px'}, scroll_time)
				scrolled = month_found - 1;
				
				
			})
			
			
		/**------------------ EVENTS FOR CLICKING TO THE event_calender_wp_next YEAR ------------------**/
			
			selector.children('.month-year-bar').children('.year').children('.event_calender_wp_next').click(function(){
				current_year += 1;
				selector.children('.month-year-bar').children('.year').children('span').text(String(current_year))
				
				selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent)').each(function(){
				
					if((new Date(jQuery(this).attr('data-date'))).getFullYear() != current_year)
						jQuery(this).hide();
					else
						jQuery(this).show()
				})
				
				if(!selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible').length){
					selector.children('.dates-bar').children('a.noevent').css('display', 'block');
				}else{
					selector.children('.dates-bar').children('a.noevent').css('display', 'none');
					selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent)').css('margin-left', '0');
					scrolled = 0;
					selector.children('.timeline-wrap').children('.event').removeClass('selected');
					selector.children('.timeline-wrap').children('.event').each(function(){
						a = (new Date(jQuery(this).attr('data-date')))
						if(a.getFullYear() == current_year){
							jQuery(this).addClass('selected')
							current_month = a.getMonth();
							selector.children('.month-year-bar').children('.month').children('span').text(month[current_month])
							return false;
						}
					})
				}
				selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent)').removeClass('selected');
				selector.children('.dates-bar').children('a[data-date ="' + String(selector.children('.timeline-wrap').children('.event.selected').attr('data-date')) + '"]').addClass('selected')

			})
			
			
		/**------------------ EVENTS FOR CLICKING TO THE event_calender_wp_prevIOUS YEAR ------------------**/
			
			
			selector.children('.month-year-bar').children('.year').children('.event_calender_wp_prev').click(function(){
				current_year -= 1;
				selector.children('.month-year-bar').children('.year').children('span').text(String(current_year))
				
				selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent)').each(function(){
				
					if((new Date(jQuery(this).attr('data-date'))).getFullYear() != current_year)
						jQuery(this).hide();
					else
						jQuery(this).show()
				})

				if(!selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible').length){
					selector.children('.dates-bar').children('a.noevent').css('display', 'block');
				}else{
					selector.children('.dates-bar').children('a.noevent').css('display', 'none');					
					selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent)').css('margin-left', '0');
					scrolled = 0;
					selector.children('.timeline-wrap').children('.event').removeClass('selected');
					selector.children('.timeline-wrap').children('.event').each(function(){
						a = (new Date(jQuery(this).attr('data-date')))
						if(a.getFullYear() == current_year){
							jQuery(this).addClass('selected')
							current_month = a.getMonth();
							selector.children('.month-year-bar').children('.month').children('span').text(month[current_month])
							return false;
						}
					})
				}
			
				selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent)').removeClass('selected');
							selector.children('.dates-bar').children('a[data-date ="' + String(selector.children('.timeline-wrap').children('.event.selected').attr('data-date')) + '"]').addClass('selected')

			})
			
		})
	}
})(jQuery)		
</script>
<?php }



add_action( 'init', 'create_post_type_pro_event_calender' );
function create_post_type_pro_event_calender() {


	register_post_type( 'event-calender',
		array(
			'labels' => array(
					'name' => __( 'Events', 'event-calender-wordpress' ),
					'singular_name' => __( 'Event', 'event-calender-wordpress' ),
					'add_new' => __( 'Add New', 'event-calender-wordpress' ),
					'add_new_item' => __( 'Add New Event', 'event-calender-wordpress' ),
					'edit_item' => __( 'Edit Event', 'event-calender-wordpress' ),
					'new_item' => __( 'New Event', 'event-calender-wordpress' ),
					'view_item' => __( 'View Event', 'event-calender-wordpress' ),
					'not_found' => __( 'Sorry, we couldn\'t find the Event you are looking for.', 'event-calender-wordpress' )
			),
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => true,
		'menu_position' => 14,
		'has_archive' => true,
		'hierarchical' => true,
		'capability_type' => 'post',
		'rewrite' => array( 'slug' => 'event-calender-item' ),
		'supports' => array( 'title', 'editor', 'custom-fields')
		)
	);	

		
}	
	

function pro_events_item_taxonomy() {
	register_taxonomy(
		'events_cat',  
		'event-calender',  
		array(
			'hierarchical'          => true,
			'label'                 => __( 'Events Category', 'event-calender-wordpress' ),
			'query_var'             => true,
			'show_admin_column' => true,
			'rewrite'               => array(
				'slug'              => 'events-category',
				'with_front'    	=> false
				)
			)
	);
}
add_action( 'init', 'pro_events_item_taxonomy');  	


	
function pro_event_item_shortcode($atts){
	extract( shortcode_atts( array(
		'id' => 'event',	
		'category' => '',	
		'color' => '#CC4D4D',	
	), $atts, 'events' ) );
	
    $q = new WP_Query(
        array( 'events_cat' => $category, 'posts_per_page' => '-1', 'post_type' => 'event-calender')
        );
	$list = '
	
	<script type= "text/javascript">
	jQuery(document).ready(function(){
		jQuery("div#event_calender_wp'.$id.'").jflatTimeline({
			scroll : "1",   
			width : "100%", 
			scrollingTime : "300"
		});
	})
	</script>
	
	<style type="text/css">
		div#event_calender_wp'.$id.' .month-year-bar{background:'.$color.';}
		div.pro_event_calender_wp{display:none;}
	</style>
	<div class="pro_event_calender_wp">
	<div id="event_calender_wp'.$id.'" class = "jflatTimeline"><div class = "timeline-wrap">
	';

	while($q->have_posts()) : $q->the_post();
		//get the ID of your post in the loop
		$idd = get_the_ID();
		$content_main = do_shortcode(get_the_content());
		$content_autop = wpautop(trim($content_main));
		$event_date = get_post_meta($idd, 'event_date', true); 
		$event_selected = get_post_meta($idd, 'event_selected', true); 
		
		$list .= '
			
			<div class = "event '.$event_selected.'" data-date = "'.$event_date.'">
				<div class = "layout1"> 
					<h3>' .do_shortcode( get_the_title() ). '</h3>
					'.$content_autop.'
				</div>
				<span class = "date">'.$event_date.'</span>
			</div>	
			

		';        
	endwhile;
	$list.= '</div></div></div>';
	wp_reset_query();
	return $list;
}
add_shortcode('events', 'pro_event_item_shortcode');	





$prefix = 'event_';

$meta_box = array(
	'id' => 'event-calender_metabox',
	'title' => 'Event Information',
	'page' => 'event-calender',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => 'Event Date',
			'desc' => 'Enter event date here. Date needs to be in MM/DD/YYYY format.',
			'id' => $prefix . 'date',
			'type' => 'text',
			'std' => '11/17/2016'
		),
		array(
			'name' => 'Sticky Event?',
			'id' => $prefix . 'selected',
			'type' => 'radio',
			'options' => array(
				array('name' => 'Yes&nbsp;&nbsp;', 'value' => 'selected'),
				array('name' => 'No', 'value' => 'not_selected')
			)
		)
	)
);

add_action('admin_menu', 'pro_eventcalender_add_box');

// Add meta box
function pro_eventcalender_add_box() {
	global $meta_box;
	
	add_meta_box($meta_box['id'], $meta_box['title'], 'pro_eventcalender_show_box', $meta_box['page'], $meta_box['context'], $meta_box['priority']);
}

// Callback function to show fields in meta box
function pro_eventcalender_show_box() {
	global $meta_box, $post;
	
	// Use nonce for verification
	echo '<input type="hidden" name="pro_eventcalender_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<table class="form-table">';

	foreach ($meta_box['fields'] as $field) {
		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);
		
		echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td>';
		switch ($field['type']) {
			case 'text':
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />',
					'<br />', $field['desc'];
				break;
			case 'radio':
				foreach ($field['options'] as $option) {
					echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' /> ', $option['name']   ;
				}
				break;
		}
		echo 	'<td>',
			'</tr>';
	}
	
	echo '</table>';
}

add_action('save_post', 'pro_eventcalender_save_data');

// Save data from meta box
function pro_eventcalender_save_data($post_id) {
	global $meta_box;
	
	// verify nonce
	if (!wp_verify_nonce($_POST['pro_eventcalender_meta_box_nonce'], basename(__FILE__))) {
		return $post_id;
	}

	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}

	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
	
	foreach ($meta_box['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}


?>