<?php

if ( !defined('CP_CONTACTFORMPP_AUTH_INCLUDE') ) { echo 'Direct access not allowed.';  exit; }

if ( !is_admin() )
{
    echo 'Direct access not allowed.';
    exit;
}
 
$records_per_page = 30; 

if (!empty($_REQUEST['r']))
    $verify_nonce = wp_verify_nonce( $_REQUEST['r'], 'cfwpp_update_actions');
else
    $verify_nonce = false;

if (!defined('CP_CONTACTFORMPP_ID'))
    define ('CP_CONTACTFORMPP_ID',intval($_GET["cal"]));

global $wpdb;

$message = "";

if (isset($_GET['lur']) && $_GET['lur'] != '' && $_GET['refund'] == '1'  && $verify_nonce)
{
    $event = $wpdb->get_results( "SELECT * FROM ".CP_CONTACTFORMPP_POSTS_TABLE_NAME." WHERE id=".intval($_GET['lur']) );
    $params = unserialize($event[0]->posted_data); 
    if ($params["txnid"])
    {
        $myform = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE .' WHERE id='.intval($event[0]->formid));
        if ($myform[0]->pprefunds == 'true')
        {
            if ($myform[0]->paypalexpress_api_username != '' && $myform[0]->paypalexpress_api_password != '' && $myform[0]->paypalexpress_api_signature != '')
            {
                $refund = new CP_PayPalRefund( array( 'username' => $myform[0]->paypalexpress_api_username, 'password' => $myform[0]->paypalexpress_api_password, 'signature' => $myform[0]->paypalexpress_api_signature, 'mode' => ($myform[0]->paypal_mode=='sandbox'?'sandbox':'production') ) );
                $result = $refund->refundAmount(array( 'transactionID' => $params["txnid"]));
                if (@$result["ERROR_MESSAGE"] || @$result["L_LONGMESSAGE0"])        
                    $message = "<span style=\"color:red\">Refund CANNOT be processed. Error:".$result["ERROR_MESSAGE"]." ".$result["L_LONGMESSAGE0"].".</span>";
                else
                {
                    $wpdb->query('UPDATE `'.CP_CONTACTFORMPP_POSTS_TABLE_NAME.'` SET paid=2 WHERE id='.intval($_GET['lur']));           
                    $message = __('Refund processed.','cp-contact-form-with-paypal');        
                }
            }
            else
                  $message = "<span style=\"color:red\">Refund CANNOT be processed. <a href=\"admin.php?page=cp_contact_form_paypal.php&cal=".$event[0]->formid."#enable_paypal\">PayPal NVP API access must be setup in the plugin settings. Click to correct.</a></span>";
        }
        else
            $message = "<span style=\"color:red\">Refund CANNOT be processed. <a href=\"admin.php?page=cp_contact_form_paypal.php&cal=".$event[0]->formid."#enable_paypal\">Refunds are disabled in the plugin settings. Click to correct.</a></span>";     
    }
    else
        $message = "<span style=\"color:red\">Refund CANNOT be processed. This item doesn't have an stored PayPal transaction ID.</span>"; 
}
else if (isset($_GET['delmark']) && $_GET['delmark'] != '' && $verify_nonce)
{
    for ($i=0; $i<=$records_per_page; $i++)
    if (isset($_GET['c'.$i]) && $_GET['c'.$i] != '')   
        $wpdb->query('DELETE FROM `'.CP_CONTACTFORMPP_POSTS_TABLE_NAME.'` WHERE id='.intval($_GET['c'.$i]));       
    $message = __('Marked items deleted','cp-contact-form-with-paypal');
}
else if (isset($_GET['del']) && $_GET['del'] == 'all' && $verify_nonce)
{    
    if (CP_CONTACTFORMPP_ID == '' || CP_CONTACTFORMPP_ID == '0')
        $wpdb->query('DELETE FROM `'.CP_CONTACTFORMPP_POSTS_TABLE_NAME.'`');           
    else
        $wpdb->query('DELETE FROM `'.CP_CONTACTFORMPP_POSTS_TABLE_NAME.'` WHERE formid='.intval(CP_CONTACTFORMPP_ID));           
    $message = __('All items deleted','cp-contact-form-with-paypal');
} 
else if (isset($_GET['lu']) && $_GET['lu'] != '' && $verify_nonce)
{
    $wpdb->query('UPDATE `'.CP_CONTACTFORMPP_POSTS_TABLE_NAME.'` SET paid='.intval($_GET["status"]).' WHERE id='.intval($_GET['lu']));           
    $message = __('Item updated','cp-contact-form-with-paypal');        
}
else if (isset($_GET['ld']) && $_GET['ld'] != '' && $verify_nonce)
{
    $wpdb->query('DELETE FROM `'.CP_CONTACTFORMPP_POSTS_TABLE_NAME.'` WHERE id='.intval($_GET['ld']));       
    $message = __('Item deleted','cp-contact-form-with-paypal');
}

if (CP_CONTACTFORMPP_ID != 0)
    $myform = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE .' WHERE id='.intval(CP_CONTACTFORMPP_ID));


if (!empty($_GET["p"]))
    $current_page = intval($_GET["p"]);
else 
    $current_page = 1;

$cond = '';
if (!empty ($_GET["search"]) && $_GET["search"] != '') $cond .= " AND (data like '%".esc_sql($_GET["search"])."%' OR paypal_post LIKE '%".esc_sql($_GET["search"])."%')";
if (!empty ($_GET["dfrom"]) && $_GET["dfrom"] != '') $cond .= " AND (`time` >= '".esc_sql($_GET["dfrom"])."')";
if (!empty ($_GET["dto"]) && $_GET["dto"] != '') $cond .= " AND (`time` <= '".esc_sql($_GET["dto"])." 23:59:59')";
if (CP_CONTACTFORMPP_ID != 0) $cond .= " AND formid=".CP_CONTACTFORMPP_ID;

$events = $wpdb->get_results( "SELECT * FROM ".CP_CONTACTFORMPP_POSTS_TABLE_NAME." WHERE 1=1 ".$cond." ORDER BY `time` DESC" );
$total_pages = ceil(count($events) / $records_per_page);

$nonce = wp_create_nonce( 'cfwpp_update_actions' );

if ($message) echo "<div id='setting-error-settings_updated' class='updated settings-error'><p><strong>".esc_html($message)."</strong></p></div>";


?>
<script type="text/javascript">
 function cp_updateMessageItem(id,status)
 {    
    document.location = 'admin.php?page=cp_contact_form_paypal.php&cal=<?php echo intval($_GET["cal"]); ?>&list=1&status='+status+'&lu='+id+'&r=<?php echo esc_js($nonce); ?>';   
 } 
 function cp_deleteMessageItem(id)
 {
    if (confirm('<?php _e('Are you sure that you want to delete this item? Note: This action cannot be undone.','cp-contact-form-with-paypal'); ?>'))
    {        
        document.location = 'admin.php?page=cp_contact_form_paypal.php&cal=<?php echo intval($_GET["cal"]); ?>&list=1&ld='+id+'&r=<?php echo esc_js($nonce); ?>';
    }
 }
 function cp_deletemarked()
 {
    if (confirm('<?php _e('Are you sure that you want to delete the marked items?','cp-contact-form-with-paypal'); ?>')) 
        document.dex_table_form.submit();
 }  
 function cp_deleteall()
 {
    if (confirm('<?php _e('Are you sure that you want to delete ALL bookings for this form?','cp-contact-form-with-paypal'); ?>'))
    {        
        document.location = 'admin.php?page=cp_contact_form_paypal.php&cal=<?php echo intval(CP_CONTACTFORMPP_ID); ?>&list=1&del=all&r=<?php echo esc_js($nonce); ?>';
    }    
 }
 function cp_markall()
 {
     var ischecked = document.getElementById("cpcontrolck").checked;
     <?php for ($i=($current_page-1)*$records_per_page; $i<$current_page*$records_per_page; $i++) if (isset($events[$i])) { ?>
     document.forms.dex_table_form.c<?php echo $i-($current_page-1)*$records_per_page; ?>.checked = ischecked;
     <?php } ?>
 }
 function cp_refundItem(id,status)
 {    
    if (confirm('<?php _e('Are you sure you want to refund this item? Note that this action cannot be undone.','cp-contact-form-with-paypal'); ?>'))
        document.location = 'admin.php?page=cp_contact_form_paypal.php&cal=<?php echo intval(CP_CONTACTFORMPP_ID); ?>&list=1&refund=1&lur='+id+'&r=<?php echo esc_js($nonce); ?>';   
 }   
</script>
<div class="wrap">
<h1>PayPal Form - <?php _e('Message List','cp-contact-form-with-paypal'); ?></h1>

<input type="button" name="backbtn" value="<?php _e('Back to items list','cp-contact-form-with-paypal'); ?>..." onclick="document.location='admin.php?page=cp_contact_form_paypal.php';">


<div id="normal-sortables" class="meta-box-sortables">
 <hr />
 <h3><?php _e('This message list is from','cp-contact-form-with-paypal'); ?>: <?php if (CP_CONTACTFORMPP_ID != 0) echo esc_html($myform[0]->form_name); else echo 'All forms'; ?></h3>
</div>


<form action="admin.php" method="get">
 <input type="hidden" name="page" value="cp_contact_form_paypal.php" />
 <input type="hidden" name="cal" value="<?php echo intval(CP_CONTACTFORMPP_ID); ?>" />
 <input type="hidden" name="list" value="1" />
 <nobr><?php _e('Search for','cp-contact-form-with-paypal'); ?>: <input type="text" name="search" value="<?php if (!empty ($_GET["search"])) echo esc_attr($_GET["search"]); ?>" /> &nbsp; &nbsp; &nbsp;</nobr> 
 <nobr><?php _e('From','cp-contact-form-with-paypal'); ?>: <input autocomplete="off" type="text" id="dfrom" name="dfrom" value="<?php if (!empty ($_GET["dfrom"])) echo esc_attr($_GET["dfrom"]); ?>" /> &nbsp; &nbsp; &nbsp; </nobr>
 <nobr><?php _e('To','cp-contact-form-with-paypal'); ?>: <input autocomplete="off" type="text" id="dto" name="dto" value="<?php if (!empty ($_GET["dto"])) echo esc_attr($_GET["dto"]); ?>" /> &nbsp; &nbsp; &nbsp; </nobr>
 <nobr><?php _e('Item','cp-contact-form-with-paypal'); ?>: <select id="cal" name="cal" style="vertical-align:baseline;">
          <option value="0">[<?php _e('All Items','cp-contact-form-with-paypal'); ?>]</option>
   <?php
    $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE );                                                                     
    foreach ($myrows as $item)  
         echo '<option value="'.$item->id.'"'.(intval($item->id)==intval(CP_CONTACTFORMPP_ID)?" selected":"").'>'.esc_html($item->form_name).'</option>'; 
   ?>
    </select></nobr>
 <nobr><span class="submit"><input type="submit" name="ds" value="Filter" /></span> &nbsp; &nbsp; &nbsp; 
 <span class="submit"><input type="submit" name="cp_contactformpp_csv" value="Export to CSV" /></span></nobr>
</form>

<br />
                             
<?php


echo paginate_links(  array(
    'base'         => 'admin.php?page=cp_contact_form_paypal.php&cal='.CP_CONTACTFORMPP_ID.'&list=1%_%&dfrom='.urlencode((!empty($_GET["dfrom"])?$_GET["dfrom"]:'')).'&dto='.urlencode((!empty($_GET["dto"])?$_GET["dto"]:'')).'&search='.urlencode((!empty($_GET["search"])?$_GET["search"]:'')),
    'format'       => '&p=%#%',
    'total'        => $total_pages,
    'current'      => $current_page,
    'show_all'     => false,
    'end_size'     => 1,
    'mid_size'     => 2,
    'prev_next'    => true,
    'prev_text'    => __('&laquo; Previous'),
    'next_text'    => __('Next &raquo;'),
    'type'         => 'plain',
    'add_args'     => false
    ) );

?>

<div id="dex_printable_contents">
<form name="dex_table_form" id="dex_table_form" action="admin.php" method="get">
 <input type="hidden" name="page" value="cp_contact_form_paypal.php" />
 <input type="hidden" name="r" value="<?php echo esc_attr($nonce); ?>" />
 <input type="hidden" name="cal" value="<?php echo intval(CP_CONTACTFORMPP_ID); ?>" />
 <input type="hidden" name="list" value="1" />
 <input type="hidden" name="delmark" value="1" />
<table class="wp-list-table widefat fixed pages" cellspacing="0">
	<thead>
	<tr>
      <th width="30" class="cpnopr"><input type="checkbox" name="cpcontrolck" id="cpcontrolck" value="" onclick="cp_markall();"></th>
      <th style="padding-left:7px;font-weight:bold;" width="50" nowrap>ID</th>
	  <th style="padding-left:7px;font-weight:bold;" width="125"><?php _e('Date','cp-contact-form-with-paypal'); ?></th>
	  <th style="padding-left:7px;font-weight:bold;"><?php _e('Email','cp-contact-form-with-paypal'); ?></th>
	  <th style="padding-left:7px;font-weight:bold;"><?php _e('Message','cp-contact-form-with-paypal'); ?></th>
	  <th style="padding-left:7px;font-weight:bold;"><?php _e('Payment Info','cp-contact-form-with-paypal'); ?></th>	  
	  <th style="padding-left:7px;font-weight:bold;"  class="noprint"><?php _e('Options','cp-contact-form-with-paypal'); ?></th>	
	</tr>
	</thead>
	<tbody id="the-list">
	 <?php for ($i=($current_page-1)*$records_per_page; $i<$current_page*$records_per_page; $i++) if (isset($events[$i])) { ?>
	  <tr class='<?php if (!($i%2)) { ?>alternate <?php } ?>author-self status-draft format-default iedit' valign="top">
        <td width="1%"  class="cpnopr"><input type="checkbox" name="c<?php echo intval($i-($current_page-1)*$records_per_page); ?>" value="<?php echo intval($events[$i]->id); ?>" /></td>      
		<td><?php echo intval($events[$i]->id); ?></td>
		<td><?php echo substr($events[$i]->time,0,16); ?></td>
		<td><?php echo esc_html(sanitize_email($events[$i]->notifyto)); ?></td>
		<td><?php 
		     $data = $events[$i]->data;
		     $posted_data = unserialize($events[$i]->posted_data);		     
		     foreach ($posted_data as $item => $value)
		         if (strpos($item,"_url") && $value != '')		         
		             $data = str_replace ($posted_data[str_replace("_url","",$item)],'<a href="'.$value.'" target="_blank">'.$posted_data[str_replace("_url","",$item)].'</a><br />',$data);  
		     echo str_replace("\n","<br />",str_replace('<','&lt;',esc_html($data)));  
		     
		?></td>
		<td>
		    <?php 
		          if ($events[$i]->paid == 1) {
		              echo '<span style="color:#00aa00;font-weight:bold">'.__("Paid").'</span><hr />';
		              if (substr($events[$i]->paypal_post,0,2) != 'a:') echo esc_html(str_replace("\n","<br />",$events[$i]->paypal_post)); 
		          }    
		          else 
                      if ($events[$i]->paid == 2) 
                          echo '<span style="color:#999999;font-weight:bold">'.__("Refunded").'</span>'; 
                      else
		                  echo '<span style="color:#ff0000;font-weight:bold">'.__("Not Paid").'</span>'; 
		    ?>
		    
		</td>
		<td class="noprint">
		  <?php if ($events[$i]->paid == 1) { ?>
   	        <input type="button" name="calmanage_<?php echo esc_attr($events[$i]->id); ?>" value="<?php _e('Change status to NOT PAI','cp-contact-form-with-paypal'); ?>D" onclick="cp_updateMessageItem(<?php echo esc_attr($events[$i]->id); ?>,0);" /><br />      
            <input type="button" name="calrefund_<?php echo esc_attr($events[$i]->id); ?>" value="<?php _e('Refund','cp-contact-form-with-paypal'); ?>" onclick="cp_refundItem(<?php echo esc_attr($events[$i]->id); ?>,1);" /><br /> 
 		  <?php } else { ?>
 		    <input type="button" name="calmanage_<?php echo esc_attr($events[$i]->id); ?>" value="<?php _e('Change status to PAID','cp-contact-form-with-paypal'); ?>" onclick="cp_updateMessageItem(<?php echo esc_attr($events[$i]->id); ?>,1);" /><br />                             
 		  <?php } ?>          
		  
		  <input type="button" name="caldelete_<?php echo esc_attr($events[$i]->id); ?>" value="<?php _e('Delete','cp-contact-form-with-paypal'); ?>" onclick="cp_deleteMessageItem(<?php echo esc_attr($events[$i]->id); ?>);" />                             
		</td>
      </tr>
     <?php } ?>
	</tbody>
</table>
</form>
</div>

<p class="submit"><input type="button" name="pbutton" value="Print" onclick="do_dexapp_print();" /></p>
<div style="clear:both"></div>
<p class="submit" style="float:left;"><input type="button" name="pbutton" value="<?php _e('Delete marked items','cp-contact-form-with-paypal'); ?>" onclick="cp_deletemarked();" /> &nbsp; &nbsp; &nbsp; </p>
<p class="submit" style="float:left;"><input type="button" name="pbutton" value="<?php _e('Delete All Bookings','cp-contact-form-with-paypal'); ?>" onclick="cp_deleteall();" /></p>
<div style="clear:both"></div>

</div>


<script type="text/javascript">
 function do_dexapp_print()
 {
      w=window.open();
      w.document.write("<style>.noprint{display:none}table{border:2px solid black;width:100%;}th{border-bottom:2px solid black;text-align:left}td{padding-left:10px;border-bottom:1px solid black;}</style>"+document.getElementById('dex_printable_contents').innerHTML);
      w.print();
      w.close();    
 }
 
 var $j = jQuery;
 $j(function() {
 	$j("#dfrom").datepicker({     	                
                    dateFormat: 'yy-mm-dd'
                 });
 	$j("#dto").datepicker({     	                
                    dateFormat: 'yy-mm-dd'
                 });
 });
 
</script>







