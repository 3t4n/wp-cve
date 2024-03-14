<?php

if ( !is_admin() )
{
    echo 'Direct access not allowed.';
    exit;
}

if (!defined('CP_BCCF_CALENDAR_ID'))
    define ('CP_BCCF_CALENDAR_ID',intval($_GET["cal"]));

global $wpdb;
$mycalendarrows = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM '.DEX_BCCF_CONFIG_TABLE_NAME .' WHERE `'.TDE_BCCFCONFIG_ID.'`=%d', CP_BCCF_CALENDAR_ID ) );

$message = "";

$records_per_page = 25; 

if (!empty($_REQUEST['_wpnonce']) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'uname_bccf' ) && isset($_GET['delmark']) && $_GET['delmark'] != '')
{
    for ($i=0; $i<=$records_per_page; $i++)
    if (isset($_GET['c'.$i]) && $_GET['c'.$i] != '')   
        $wpdb->query('DELETE FROM `'.DEX_BCCF_CALENDARS_TABLE_NAME.'` WHERE id='.intval($_GET['c'.$i]));       
    $message = "Marked items deleted";
}
else if (!empty($_REQUEST['_wpnonce']) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'uname_bccf' ) && isset($_GET['del']) && $_GET['del'] == 'all')
{    
    if (CP_BCCF_CALENDAR_ID == '' || CP_BCCF_CALENDAR_ID == '0')
        $wpdb->query('DELETE FROM `'.DEX_BCCF_CALENDARS_TABLE_NAME.'`');           
    else
        $wpdb->query('DELETE FROM `'.DEX_BCCF_CALENDARS_TABLE_NAME.'` WHERE reservation_calendar_id='.intval(CP_BCCF_CALENDAR_ID));           
    $message = "All items deleted";
} 
else if (!empty($_REQUEST['_wpnonce']) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'uname_bccf' ) && isset($_GET['ld']) && $_GET['ld'] != '')
{
    $wpdb->query('DELETE FROM `'.DEX_BCCF_CALENDARS_TABLE_NAME.'` WHERE id='.intval($_GET['ld']));       
    $message = "Item deleted";
}

if ($message) echo "<div id='setting-error-settings_updated' class='updated settings-error'> <p><strong>".esc_html($message)."</strong></p></div>";

$current_user = wp_get_current_user();

if (cp_bccf_is_administrator() || $mycalendarrows[0]->conwer == $current_user->ID) {

if (!empty($_GET["p"]))
    $current_page = intval($_GET["p"]);
else
    $current_page = 1;                                                                                 

$cond = '';
if (!empty($_GET["search"]))
{
    if (is_numeric($_GET["search"]))
        $cond .= " AND (title like '%".esc_sql($_GET["search"])."%' OR description LIKE '%".esc_sql($_GET["search"])."%' OR id=".intval($_GET["search"]).")";
    else
        $cond .= " AND (title like '%".esc_sql($_GET["search"])."%' OR description LIKE '%".esc_sql($_GET["search"])."%')";
}    
if (!empty($_GET["dfrom"]) && $_GET["dfrom"] != '') $cond .= " AND (datatime_s >= '".esc_sql($_GET["dfrom"])."')";
if (!empty($_GET["dto"]) && $_GET["dto"] != '') $cond .= " AND (datatime_s <= '".esc_sql($_GET["dto"])." 23:59:59')";

$events = $wpdb->get_results( "SELECT * FROM ".DEX_BCCF_CALENDARS_TABLE_NAME." WHERE reservation_calendar_id=".intval(CP_BCCF_CALENDAR_ID).$cond." ORDER BY datatime_s DESC" );

$total_pages = ceil(count($events) / $records_per_page);

$option_calendar_enabled = dex_bccf_get_option('calendar_enabled', DEX_BCCF_DEFAULT_CALENDAR_ENABLED);
 
$nonce_un = wp_create_nonce( 'uname_bccf' );
 
?>
<script type="text/javascript">
 function cp_deleteMessageItem(id)
 {
    if (confirm('Are you sure that you want to delete this item? Note: This cantion cannot be undone.'))
    {        
        document.location = 'admin.php?page=dex_bccf.php&cal=<?php echo intval($_GET["cal"]); ?>&list=1&_wpnonce=<?php echo esc_js($nonce_un); ?>&ld='+id+'&r='+Math.random();
    }
 }
 function cp_deletemarked()
 {
    if (confirm('Are you sure that you want to delete the marked items?')) 
        document.dex_table_form.submit();
 }  
 function cp_deleteall()
 {
    if (confirm('Are you sure that you want to delete ALL bookings for this form?'))
    {        
        document.location = 'admin.php?page=dex_bccf.php&cal=<?php echo intval(CP_BCCF_CALENDAR_ID); ?>&list=1&del=all&_wpnonce=<?php echo esc_js($nonce_un); ?>';
    }    
 }
 function cp_markall()
 {
     var ischecked = document.getElementById("cpcontrolck").checked;
     <?php for ($i=($current_page-1)*$records_per_page; $i<$current_page*$records_per_page; $i++) if (isset($events[$i])) { ?>
     document.forms.dex_table_form.c<?php echo $i-($current_page-1)*$records_per_page; ?>.checked = ischecked;
     <?php } ?>
 }   
</script>
<div class="wrap">
<h1>Booking Calendar Contact Form - Bookings List</h1>

<input type="button" name="backbtn" value="Back to items list..." onclick="document.location='admin.php?page=dex_bccf.php';">


<div id="normal-sortables" class="meta-box-sortables">
 <hr />
 <h3>This booking list applies only to: <?php echo esc_html($mycalendarrows[0]->uname); ?></h3>
</div>


<form action="admin.php" method="get">
 <input type="hidden" name="page" value="dex_bccf.php" />
 <input type="hidden" name="cal" value="<?php echo esc_attr(CP_BCCF_CALENDAR_ID); ?>" />
 <input type="hidden" name="list" value="1" />
 Search for: <input type="text" name="search" value="<?php if (!empty($_GET["search"])) echo esc_attr(sanitize_text_field($_GET["search"])); ?>" /> &nbsp; &nbsp; &nbsp; 
 From: <input autocomplete="off" type="text" id="dfrom" name="dfrom" value="<?php if (!empty($_GET["dfrom"])) echo esc_attr(sanitize_text_field($_GET["dfrom"])); ?>" /> &nbsp; &nbsp; &nbsp; 
 To: <input autocomplete="off" type="text" id="dto" name="dto" value="<?php if (!empty($_GET["dto"])) echo esc_attr(sanitize_text_field($_GET["dto"])); ?>" /> &nbsp; &nbsp; &nbsp; 
 <span class="submit"><input type="submit" name="ds" value="Filter" /></span>
</form>

<br />
                             
<?php


echo paginate_links(  array(
    'base'         => 'admin.php?page=dex_bccf.php&cal='.CP_BCCF_CALENDAR_ID.'&list=1%_%&dfrom='.(!empty($_GET["dfrom"])?urlencode(sanitize_text_field($_GET["dfrom"])): '').'&dto='.(!empty($_GET["dfrom"])?urlencode(sanitize_text_field($_GET["dto"])): '').'&search='.(!empty($_GET["dfrom"])?urlencode(sanitize_text_field($_GET["search"])): ''),
    'format'       => '&p=%#%',
    'total'        => $total_pages,
    'current'      => $current_page,
    'show_all'     => False,
    'end_size'     => 1,
    'mid_size'     => 2,
    'prev_next'    => True,
    'prev_text'    => '&laquo; '.__('Previous','booking-calendar-contact-form'),
    'next_text'    => __('Next','booking-calendar-contact-form').' &raquo;',
    'type'         => 'plain',
    'add_args'     => False
    ) );

?>

<div id="dex_printable_contents">
<form name="dex_table_form" id="dex_table_form" action="admin.php" method="get">
 <input type="hidden" name="page" value="dex_bccf.php" />
 <input type="hidden" name="_wpnonce" value="<?php echo esc_attr($nonce_un); ?>" />
 <input type="hidden" name="cal" value="<?php echo intval(CP_BCCF_CALENDAR_ID); ?>" />
 <input type="hidden" name="list" value="1" />
 <input type="hidden" name="delmark" value="1" />
<table class="wp-list-table widefat fixed pages" cellspacing="0">
	<thead>
	<tr>
      <th width="30" class="cpnopr"><input type="checkbox" name="cpcontrolck" id="cpcontrolck" value="" onclick="cp_markall();"></th>
	  <th style="padding-left:7px;font-weight:bold;width:70px;">ID</th>
	  <th style="padding-left:7px;font-weight:bold;">Date</th>
	  <th style="padding-left:7px;font-weight:bold;">Title</th>
	  <th style="padding-left:7px;font-weight:bold;">Description</th>
	  <th style="padding-left:7px;font-weight:bold;"  class="delbtn">Options</th>
	</tr>
	</thead>
	<tbody id="the-list">
	 <?php for ($i=($current_page-1)*$records_per_page; $i<$current_page*$records_per_page; $i++) if (isset($events[$i])) { ?>
	  <tr class='<?php if (!($i%2)) { ?>alternate <?php } ?>author-self status-draft format-default iedit' valign="top">
	    <td width="1%"  class="cpnopr"><input type="checkbox" name="c<?php echo $i-($current_page-1)*$records_per_page; ?>" value="<?php echo $events[$i]->id; ?>" /></td>      
		<td width="1%"><?php echo $events[$i]->id; ?></td>
		<td><?php echo substr($events[$i]->datatime_s,0,10); ?><?php if ($option_calendar_enabled != 'false') { ?> - <?php echo substr($events[$i]->datatime_e,0,10); ?><?php } ?></td>
		<td><?php echo str_replace('<','&lt;',$events[$i]->title); ?></td>
		<td><?php echo str_replace("&lt;br />&lt;br />","<br />", str_replace('<','&lt;',$events[$i]->description) ); ?></td>
		<td  class="delbtn">		  
		  <input type="button" name="caldelete_<?php echo $events[$i]->id; ?>" value="Delete" onclick="cp_deleteMessageItem(<?php echo $events[$i]->id; ?>);" />                             
		</td>		
      </tr>
     <?php } ?>
	</tbody>
</table>
</form>
</div>

<p class="submit"><input type="button" name="pbutton" value="Print" onclick="do_dexapp_print();" /></p>
<div style="clear:both"></div>
<p class="submit" style="float:left;"><input type="button" name="pbutton" value="Delete marked items" onclick="cp_deletemarked();" /> &nbsp; &nbsp; &nbsp; </p>
<p class="submit" style="float:left;"><input type="button" name="pbutton" value="Delete All Bookings" onclick="cp_deleteall();" /></p>
<div style="clear:both"></div>

</div>


<script type="text/javascript">
 function do_dexapp_print()
 {
      w=window.open();
      w.document.write("<style>.delbtn{display:none}table{border:2px solid black;width:100%;}th{border-bottom:2px solid black;text-align:left}td{padding-left:10px;border-bottom:1px solid black;}</style>"+document.getElementById('dex_printable_contents').innerHTML);
      w.print();
      w.close();    
 }
 
 var $j = jQuery.noConflict();
 $j(function() {
 	$j("#dfrom").datepicker({     	                
                    dateFormat: 'yy-mm-dd'
                 });
 	$j("#dto").datepicker({     	                
                    dateFormat: 'yy-mm-dd'
                 });
 });
 
</script>




<?php } else { ?>
  <br />
  The current user logged in doesn't have enough permissions to edit this calendar. This user can edit only his/her own calendars. Please log in as administrator to get access to all calendars.

<?php } ?>











