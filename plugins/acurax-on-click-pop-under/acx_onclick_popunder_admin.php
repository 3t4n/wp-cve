<?php
$acurax_popunder_installed_date = get_option('acurax_popunder_installed_date');
if ($acurax_popunder_installed_date=="")
{ 	$acurax_popunder_installed_date = time();
	update_option('acurax_popunder_installed_date', $acurax_popunder_installed_date);
}
$acurax_popunder_array=get_option('acurax_popunder_array');
$acurax_time_out=get_option('acurax_popunder_timeout');
if($acurax_time_out == "" || !is_numeric($acurax_time_out))
{
	$acurax_time_out = 60;
}
update_option('acurax_popunder_timeout',$acurax_time_out);
$acx_popunder_message = "";
	
if($acurax_popunder_array == "")
{
	$acurax_popunder_array = array();
}
else
{	 
	if(is_serialized($acurax_popunder_array))
	{
		$acurax_popunder_array = unserialize($acurax_popunder_array); 
	}	 
	if(!is_array($acurax_popunder_array))
	{
		$acurax_popunder_array = array();
	}
}
if(is_serialized($acurax_popunder_array ))
{
	$acurax_popunder_array = unserialize($acurax_popunder_array); 
}
if(ISSET($_GET['action']))
{
	$acx_get_action=$_GET['action'];
}
else
{
	$acx_get_action='';
}
if(ISSET($_GET['ID']))
{
	$acx_get_id=$_GET['ID'];
}
else
{
	$acx_get_id='';
}
if($acx_get_action=="delete" && $acx_get_id != "")
{
	$to_del_id = $_GET['ID']-1;
	unset($acurax_popunder_array[$to_del_id]);
	$acurax_popunder_array = array_values($acurax_popunder_array);
	if(!is_serialized($acurax_popunder_array ))
	{
		$acurax_popunder_array = serialize($acurax_popunder_array); 
	}
	update_option('acurax_popunder_array', $acurax_popunder_array);
	if(is_serialized($acurax_popunder_array ))
	{
		$acurax_popunder_array = unserialize($acurax_popunder_array); 
	}
	$acx_popunder_message = "The URL Deleted Successfully!.";
}
if(ISSET($_POST['acurax_popunder_hidden']))
{
	$acurax_popunder_hidden=$_POST['acurax_popunder_hidden'];
}
else
{
	$acurax_popunder_hidden="";
}
if($acurax_popunder_hidden=='Y')
{
	if (!isset($_POST['acurax_popunder_save_config'])) die("<br><br>Unknown Error Occurred, Try Again... <a href=''>Click Here</a>");
	if (!wp_verify_nonce($_POST['acurax_popunder_save_config'],'acurax_popunder_save_config')) die("<br><br>Unknown Error Occurred, Try Again... <a href=''>Click Here</a>");
	if(!current_user_can('manage_options')) die("<br><br>Sorry, You have no permission to do this action...</a>");

	//Form data sent
	$acurax_popunder_url_formdata = esc_url_raw($_POST['acurax_popunder_url']);
	if($acurax_popunder_url_formdata !="" )
	{
		$acurax_popunder_array[]=$acurax_popunder_url_formdata;
	}
	if(!is_serialized($acurax_popunder_array))
	{
		$acurax_popunder_array = serialize($acurax_popunder_array); 
	}
	update_option('acurax_popunder_array', $acurax_popunder_array);
	$acurax_time_out = sanitize_text_field($_POST['acurax_popunder_timeout']);
	
	update_option('acurax_popunder_timeout', $acurax_time_out);
	$acx_popunder_message = "Acurax Popunder Settings Saved!";
}
if($acx_popunder_message != "")
{
	echo "<div class='updated'><p><strong>".$acx_popunder_message."</strong></p></div>";
}
// Our class extends the WP_List_Table class, so we need to make sure that it's there
if( ! class_exists( 'WP_List_Table' ) ) 
{
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class  Acx_Onclick_Popunder_My_List_Table extends WP_List_Table 
{
	function __construct()
	{
		
		global $status, $page;
		parent::__construct( array(
				'singular'  => __( 'url', 'mylisttable' ),     //singular name of the listed records
				'plural'    => __( 'urls', 'mylisttable' ),   //plural name of the listed records
				'ajax'      => false        //does this table support ajax?
								) );
	}
	 // here for compatibility with 4.3
    function get_columns()
    {
        // Get options
        return $this->acx_onclick_popunder_get_columns();
    }
	
	function acx_onclick_popunder_data()
	{
		$acurax_popunder_array=get_option('acurax_popunder_array');

		if($acurax_popunder_array == "")
		{
			$acurax_popunder_array = array();
		} 
		else
		{	 
			if(is_serialized($acurax_popunder_array ))
			{
				$acurax_popunder_array = unserialize($acurax_popunder_array); 
			}	 
			if(!is_array($acurax_popunder_array))
			{
				$acurax_popunder_array = array($acurax_popunder_url);
			}
		}

		if(is_serialized($acurax_popunder_array ))
		{
			$acurax_popunder_array = unserialize($acurax_popunder_array); 
		}		 

		$acurax_popunder_url_new = array();

		foreach($acurax_popunder_array as $key => $value)
		{
			$acurax_popunder_url_new[]=array(
			'ID'=>$key+1,
			'URL'=>esc_url($value)
			);
		}
		return $acurax_popunder_url_new;
	}
	function acx_onclick_popunder_get_columns()
	{
		$columns = array(
			'ID' => 'Sl No',
			'URL'    => 'URL',
						);
		return $columns;
	}
	function acx_onclick_popunder_prepare_items()
	{
		$columns = $this->acx_onclick_popunder_get_columns();
		$hidden = array();
		$sortable = $this->acx_onclick_popunder_get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
		$this->items = $this->acx_onclick_popunder_data();
		usort( $this->items,array( &$this, 'acx_onclick_popunder_usort_reorder' ) );
	}
	function column_URL($item)
	{
		$actions = array(
				'delete'    => sprintf('<a href="?page=%s&action=%s&ID=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
						);
		return sprintf('%1$s %2$s', $item['URL'], $this->row_actions($actions) );
	}
	function acx_onclick_popunder_get_sortable_columns()
	{
		$sortable_columns = array(
	  		'ID'  => array('ID',false),
			'URL' => array('URL',false),
	  							);
	  return $sortable_columns;
	}
	function acx_onclick_popunder_usort_reorder( $a, $b ) 
	{
		// If no sort, default to title
		$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'ID';
		// If no order, default to asc
		$order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
		// Determine sort order
		$result = strnatcmp( $a[$orderby], $b[$orderby] );
		// Send final sort direction to usort
		return ( $order === 'asc' ) ? $result : -$result;
	}
	function no_items() 
	{
		_e( 'No URLs found !!!!' );
	}
	function column_default( $item, $column_name ) 
	{
		switch( $column_name )
		{ 
			case 'ID':
			case 'URL':
			return $item[ $column_name ];
			default:
			return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}
	}
}
function acx_onclick_popunder_render_list_page()
{
   $myListTable = new Acx_Onclick_Popunder_My_List_Table();
   echo '<div class="wrap">'; 
   $myListTable->acx_onclick_popunder_prepare_items(); 
   $myListTable->display(); 
   echo '</div>'; 
}
?>
<style>
.top
{
display:none;
}
</style>
<div class="wrap">

<div style='background: none repeat scroll 0% 0% white; height: 100%; display: inline-block; padding: 8px; margin-top: 5px; border-radius: 15px; min-height: 450px; width: 100%;'>
<?php
$acx_onclick_popunder_service_banners = get_option('acurax_popunder_service_banners');
$acx_onclick_popunder_premium_ad = get_option('acurax_popunder_premium_ad');
if ($acx_onclick_popunder_service_banners != "no") { ?>
<div id="acx_ad_banners_onclick_popunder">
<a href="http://www.acurax.com/services/wordpress-designing-experts.php?utm_source=plugin-page&utm_medium=banner&utm_campaign=ocpu" target="_blank" class="acx_ad_onclick_popunder_1">
<div class="acx_ad_onclick_popunder_title">Need Help on Wordpress?</div> <!-- acx_ad_onclick_popunder_title -->
<div class="acx_ad_onclick_popunder_desc">Expert Support at Your Fingertip</div> <!-- acx_ad_onclick_popunder_desc -->
</a> <!--  acx_ad_onclick_popunder_1 -->

<a href="http://www.acurax.com/services/website-redesign.php?utm_source=plugin-page&utm_medium=banner&utm_campaign=ocpu" target="_blank" class="acx_ad_onclick_popunder_1">
<div class="acx_ad_onclick_popunder_title">Needs a Better Designed Website?</div> <!-- acx_ad_onclick_popunder_title -->
<div class="acx_ad_onclick_popunder_desc acx_ad_onclick_popunder_desc2" style="padding-top: 4px; height: 41px; font-size: 13px; text-align: center;">Get High Converting Website - 100% Satisfaction Guaranteed</div> <!-- acx_ad_onclick_popunder_desc -->
</a> <!--  acx_ad_onclick_popunder_1 -->

<a href="http://www.acurax.com/services/website-redesign.php?utm_source=plugin-page&utm_medium=banner&utm_campaign=ocpu" target="_blank" class="acx_ad_onclick_popunder_1">
<div class="acx_ad_onclick_popunder_title">Need More Business?</div> <!-- acx_ad_onclick_popunder_title -->
<div class="acx_ad_onclick_popunder_desc acx_ad_onclick_popunder_desc3" style="padding-top: 13px; height: 32px; font-size: 13px; text-align: center;">Get Your Website Optimized</div> <!-- acx_ad_onclick_popunder_desc -->
</a> <!--  acx_ad_onclick_popunder_1 -->

<a href="http://www.acurax.com/services/wordpress-designing-experts.php?utm_source=plugin-page&utm_medium=banner&utm_campaign=ocpu" target="_blank" class="acx_ad_onclick_popunder_1">
<div class="acx_ad_onclick_popunder_title">Quick Support</div> <!-- acx_ad_onclick_popunder_title -->
<div class="acx_ad_onclick_popunder_desc acx_ad_onclick_popunder_desc4" style="padding-top: 4px; height: 41px; font-size: 13px; text-align: center;">Get Explanation & Fix on Website Issues Instantly</div> <!-- acx_ad_onclick_popunder_desc -->
</a> <!--  acx_ad_onclick_popunder_1 -->
</div> <!--  acx_ad_banners_onclick_popunder -->
<?php } 
else { ?>
<p class="widefat" style="padding:8px;width:99%;">
<b>Acurax Services >> </b>
<a href="http://www.acurax.com/services/wordpress-designing-experts.php?utm_source=plugin-page&utm_medium=banner_link&utm_campaign=ocpu" target="_blank">Need Help on Wordpress?</a> | 
<a href="http://www.acurax.com/services/website-redesign.php?utm_source=plugin-page&utm_medium=banner_link&utm_campaign=ocpu" target="_blank">Needs a Better Designed Website?</a> | 
<a href="http://www.acurax.com/services/website-redesign.php?utm_source=plugin-page&utm_medium=banner_link&utm_campaign=ocpu" target="_blank">Need More Business?</a> | 
<a href="http://www.acurax.com/services/wordpress-designing-experts.php?utm_source=plugin-page&utm_medium=banner_link&utm_campaign=ocpu" target="_blank">Quick Support</a>
</p>
<?php } 
if($acx_onclick_popunder_premium_ad != "no")
{?>
<div id="acx_onclick_popunder_premium">
<a style="margin: 10px 0px 0px 10px; font-weight: bold; font-size: 14px; display: block;" href="#compare">Fully Featured - Premium Onclick Popunder is Available With Tons of Extra Features! - Click Here</a>
</div> <!-- acx_fsmi_premium -->
<?php
}
 echo "<h2>" . __( 'Acurax Popunder Options', 'acx_popunder_config' ) . "</h2>";
$acx_popunder_ru = esc_url(str_replace( '%7E', '~', $_SERVER['REQUEST_URI']));
if($acx_popunder_ru != "")
{
	$acx_popunder_ru = str_replace("action=delete&ID","acurax",$acx_popunder_ru);
}?>
	<form name="acurax_popunder_form" method="post" action="<?php echo $acx_popunder_ru; ?>">
		<table>
		<tr><td>
			<input type="hidden" name="acurax_popunder_hidden" value="Y"></td></tr>
			<tr><td>
			<?php    echo "<h4>" . __( 'Add New Url', 'acx_popunder_config' ) . "</h4>"; ?></td></tr>
			<hr />
			<tr><td><p><?php _e("New Popunder URL: " );?></td><td> <input type="text" name="acurax_popunder_url" id="acurax_popunder_url" value="" size="20">
			</td>
			<td><?php _e(" ex: <a href='http://www.acurax.com' target='_blank'>http://www.acurax.com</a>" );?></td></tr>
			<tr>
			<td colspan="3">
			<input type="submit" name="Submit" value="<?php _e('Add', 'acx_popunder_config');?>" onclick="javascript:return acurax_popunder_validate();">
			</p></td></tr>
		</table>
		<table>
			<hr />
			<tr><td>
			<?php    echo "<h4>" . __( 'General Settings', 'acx_popunder_config' ) . "</h4>"; ?></td></tr>
			<tr><td><p><?php _e("Popunder Cookie Expire Timeout: " ); ?></td><td><input type="text" name="acurax_popunder_timeout" id="acurax_popunder_timeout"  value="<?php echo $acurax_time_out;?>" size="20" /></td><td><?php _e("<b>Minutes</b>. Needs to Define in Minutes, For Eg: '60' for 1 Hour" );?></p>
			</td></tr>
			<tr><td colspan="3">
			<p class="submit">
			<input type="submit" name="Submit" value="<?php _e('Update', 'acx_popunder_config');?>" onclick="javascript:return acurax_popunder_validate();" >
			</p>
			<input name="acurax_popunder_save_config" type="hidden" value="<?php echo wp_create_nonce('acurax_popunder_save_config'); ?>" />
			</td></tr>
		</table>
			<hr />
			<p>
			<?php _e("<h4> Current URLs</h4>" );
			?> 
<?php acx_onclick_popunder_render_list_page();?>
			</p>
    </form>	
	<hr />
<?php
if(ISSET($_GET['status']))
{
$acx_get_status_update = $_GET['status'];
}
else
{
$acx_get_status_update = '';
}

if($acx_get_status_update =="updated")
{
update_option('acurax_popunder_version_p',ACURAX_POPUNDER_VERSION_P);
	?>
	<div id="acx_popunder_updation_notice" name="acx_popunder_updation_notice">
	<?php _e('You have successfully completed the updating process','acx_popunder_config');?>
	<a name="updated"></a>
	</div>
	<?php
}
$acx_onclick_popunder_premium_ad = get_option('acurax_popunder_premium_ad');
if ($acx_onclick_popunder_premium_ad != "no") { 
acx_onclick_popunder_comparison(1);
}?>
<br>
<p class="widefat" style="padding:8px;width:99%;">
Something Not Working Well? Have a Doubt? Have a Suggestion? - <a href="http://www.acurax.com/contact.php" target="_blank">Contact us now</a> | Need a Custom Designed Theme For your Blog or Website? Need a Custom Header Image? - <a href="http://www.acurax.com/contact.php" target="_blank">Contact us now</a>
</p>
</div>
</div>
<script type="text/javascript">
function acurax_popunder_validate()
{
	var acurax_popunder_url=jQuery("#acurax_popunder_url").val();
	var acurax_popunder_timeout=jQuery("#acurax_popunder_timeout").val();
	var acuraxPopunderUrlValid = true;
	if ( acurax_popunder_timeout == "" || !(/^[0-9]+$/.test(acurax_popunder_timeout)) ) 
	{ 
		alert ( "Enter a valid Timeout" ); 
		acuraxPopunderUrlValid = false;
    } 
    if (acurax_popunder_url == "") 
	{ 
		alert ( "Enter Url" ); 
		acuraxPopunderUrlValid = false;
    } 
	else if(!/^(http)s?:\/\//.test(acurax_popunder_url))
	{
		alert( "Enter a valid Url " );
		acuraxPopunderUrlValid = false;
	}
	else if( /^(http)s?:\/\//.test(acurax_popunder_url) ) 
	{ 
		var acx_onclick_popunder_url_temp = acurax_popunder_url;
		var acx_onclick_popunder_url_newstr = acx_onclick_popunder_url_temp.replace(/[(http)s?:\/\/)]/g,"");
		if(acx_onclick_popunder_url_newstr == "")
		{
			alert( "Enter a valid Url " );
			acuraxPopunderUrlValid = false;
		} 
	}
    return acuraxPopunderUrlValid;
}
</script>