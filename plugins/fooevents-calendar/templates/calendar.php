<?php
/**
 * Template for FooEvents Calendar
 *
 * @file    FooEvents Calendar calendar output
 * @link    https://www.fooevents.com
 * @package fooevents-calendar
 */

?>
<div id='<?php echo esc_attr( $calendar_id ); ?>' class="fooevents_calendar" style="clear:both"></div>
<script>
(function($) {
	var localObj = '<?php echo $local_args['json_events']; ?>';
	var settings = JSON.parse(localObj);    
	if( $('#'+settings.id).length ) {
		jQuery('#'+settings.id).fullCalendar(settings);
	} 
})(jQuery);
</script>
