<?php 

if ( !is_admin() || !current_user_can('manage_options') ) {echo 'Direct access not allowed.';exit;} 

$nonce = wp_create_nonce( 'bccf_update_actions_pwizard' );

?>


<h1>Publish Booking Calendar Contact Form</h1>

<style type="text/css">

.ahb-buttons-container{margin:1em 1em 1em 0;}
.ahb-return-link{float:right;}
.ahb-mssg{margin-left:0 !important; }
.ahb-section-container {
	border: 1px solid #e6e6e6;
	padding:0px;
	border-radius: 3px;
	-webkit-box-flex: 1;
	flex: 1;
	margin: 1em 1em 1em 0;
	min-width: 200px;
	background: #ffffff;
	position:relative;
}
.ahb-section{padding:20px;display:none;}
.ahb-section label{font-weight:600;}
.ahb-section-active{display:block;}

.ahb-row{display:none;}
.ahb-section table td,
.ahb-section table th{padding-left:0;padding-right:0;}
.ahb-section select,
.ahb-section input[type="text"]{width:100%;}

.cpmvcontainer { font-size:16px !important; }
</style>

<div class="ahb-buttons-container">
	<a href="javascript:document.location='admin.php?page=dex_bccf.php';" class="ahb-return-link">&larr;Return to the calendars list</a>
	<div class="clear"></div>
</div>

<form method="post" action="?page=dex_bccf.php&pwizard=1" name="regForm" id="regForm">          
 <input name="dex_bccf_do_action_loaded" type="hidden" value="wizard" />
 <input name="nonce" type="hidden" value="<?php echo esc_attr($nonce); ?>" />

<?php 

if (isset($_POST['dex_bccf_do_action_loaded']) && @$_POST['dex_bccf_do_action_loaded'] == 'wizard') {
    global $bccf_postURL;
?>
<div class="ahb-section-container">
	<div class="ahb-section ahb-section-active" data-step="1">
        <h1>Great! Form successfully published</h1>
        <p class="cpmvcontainer">The booking form was placed into the page <a href="<?php echo esc_attr($bccf_postURL); ?>"><?php echo esc_html($bccf_postURL); ?></a>.</p>
        <p class="cpmvcontainer">Now you can:</p>
        <div style="clear:both"></div>
        <button class="button button-primary cpmvcontainer" type="button" id="nextBtn" onclick="window.open('<?php echo esc_attr($bccf_postURL); ?>');">View the Published Form</button>
        <div style="clear:both"></div>
        <p class="cpmvcontainer">* Note: If the form was published in a new page or post it will be a 'draft', you have to publish the page/post in the future if needed.</p>
        <div style="clear:both"></div>
        <button class="button button-primary cpmvcontainer" type="button" id="nextBtn" onclick="window.open('?page=dex_bccf.php&cal=<?php echo intval($_POST["dex_bccf_id"]); ?>');">Edit the booking form settings</button>
        <div style="clear:both"></div>
    </div>
</div>
<div style="clear:both"></div>
<?php
} else {     
?>

<div class="ahb-section-container">
	<div class="ahb-section ahb-section-active" data-step="1">
		<table class="form-table">
            <tbody>
				<tr valign="top">
					<th><label>Select booking form</label></th>
					<td>
                    <select id="dex_bccf_id" name="dex_bccf_id" onchange="reloadappbk(this);">
<?php
  $myrows = $wpdb->get_results( "SELECT * FROM ". $wpdb->prefix."bccf_reservation_calendars");
  foreach ($myrows as $item)            
      echo '<option value="'.intval($item->id).'"'.(!empty($_GET["cal"]) && $item->id==$_GET["cal"]?' selected':'').'>'.esc_html($item->uname).'</option>';
?>                
            </select>
                    </td>    
                </tr>   
                <tr valign="top">
                    <th><label>Where to publish it?</label></th>
					<td> 
                        <select name="whereto" onchange="mvpublish_displayoption(this);">
                          <option value="0">Into a new page</option>
                          <option value="1">Into a new post</option>
                          <option value="2">Into an existent page</option>
                          <option value="3">Into an existent post</option>
                          <option value="4" style="color:#bbbbbb">Widget in a sidebar, header or footer - upgrade required for this option -</option>
                        </select>                    
                    </td>    
                </tr> 
                <tr valign="top" id="posttitle">
                    <th><label>Page/Post Title</label></th>
					<td> 
                        <input type="text" name="posttitle" value="Contact Form" />
                    </td>    
                </tr>                  
                <tr valign="top"  id="ppage" style="display:none">
                    <th><label>Select page</label></th>
					<td>
                        <select name="publishpage">
                         <?php 
                             $pages = get_pages();
                             foreach ( $pages as $page ) {
                               echo '<option value="' .  intval($page->ID)  . '">';
                               echo esc_html($page->post_title);
                               echo '</option>';
                             }
                         ?>
                        </select>
                    </td>    
                </tr> 
                <tr valign="top" id="ppost" style="display:none">
                    <th><label>Select post</label></th>
					<td> 
                        <select name="publishpost">
                         <?php 
                             $pages = get_posts();
                             foreach ( $pages as $page ) {
                               echo '<option value="' .  intval($page->ID)  . '">';
                               echo esc_html($page->post_title);
                               echo '</option>';
                             }
                         ?>
                        </select>                    
                    </td>    
                </tr>                    
            <tbody>                
       </table>
       <hr size="1" />
       <div class="ahb-buttons-container">
			<input type="submit" value="Publish Booking Form" class="button button-primary" style="float:right;margin-right:10px"  />
			<div class="clear"></div>
		</div>
</form>
</div>
</div>
<?php } ?>


<script type="text/javascript">

function reloadappbk(item) {
    document.location = '?page=dex_bccf.php&pwizard=1&cal='+item.options[item.options.selectedIndex].value;
}


function mvpublish_displayoption(sel) {
    document.getElementById("ppost").style.display = 'none';
    document.getElementById("ppage").style.display = 'none';
    document.getElementById("posttitle").style.display = 'none';    
    if (sel.selectedIndex == 4)
    {
        alert('Widget option available only in commercial versions. Upgrade required for this option.');
        sel.selectedIndex = 0;        
    }
    else if (sel.selectedIndex == 2)
        document.getElementById("ppage").style.display = '';
    else if (sel.selectedIndex == 3)
        document.getElementById("ppost").style.display = '';
    else if (sel.selectedIndex == 1 || sel.selectedIndex == 0)
        document.getElementById("posttitle").style.display = '';
}


</script>   

<div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e('Note','booking-calendar-contact-form'); ?></span></h3>
  <div class="inside">
   <?php _e('You can also publish the form in a post/page, use the dedicated icon','booking-calendar-contact-form'); ?> <?php echo '<img hspace="5" src="'.plugins_url('/images/dex_apps.gif', __FILE__).'" alt="'.__('Insert Booking Calendar Contact Form','booking-calendar-contact-form').'" /></a>';     ?>
   <?php _e('which has been added to your Upload/Insert Menu, just below the title of your Post/Page or under the "+" icon if using the Gutemberg editor.','booking-calendar-contact-form'); ?>
   <br /><br />
  </div>
</div>
