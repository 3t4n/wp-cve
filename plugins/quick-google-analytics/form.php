<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* --------------------------------------------------------------------------------------------------------------------------------------- */
 function adminForm_quickgoogleanalytics() {
	
?>
<div class="wrap">
<h2>Quick Google Analytics</h2>
<p>With this simple WordPress Plugin you can Add your Google Analytics Code (ua-xxxxxxx) into your Header.php File without coding</p>

<?php 
echo '<img src="' . plugin_dir_url(__FILE__) . 'google-ua-msg.jpg" alt="Notification">';
?>

<hr />
	
<?php
  

  
/*------nonce field check start ---- */
if (isset($_REQUEST['submit'])) {

  if ( 
    ! isset( $_POST['nonce_ua'] ) 
    || ! wp_verify_nonce( $_POST['nonce_ua'], 'nonce_ua_field' ) 
		) {

   				//print 'Sorry, your nonce did not verify.';
   				exit;

			} else {
   		saveForm_quickgoogleanalytics();
  			}
			
  }			
/*------nonce field check end ---- */  

/*------nonce field check start ---- */
if (isset($_REQUEST['submit_g'])) {

	if ( 
	  ! isset( $_POST['nonce_g'] ) 
	  || ! wp_verify_nonce( $_POST['nonce_g'], 'nonce_g_field' ) 
		  ) {
  
					 //print 'Sorry, your nonce did not verify.';
					 exit;
  
			  } else {
			 saveForm_g_quickgoogleanalytics();
				}
			  
	}			
  /*------nonce field check end ---- */ 

  /*------nonce field check start ---- */
if (isset($_REQUEST['submit_select'])) {

	if ( 
	  ! isset( $_POST['nonce_select'] ) 
	  || ! wp_verify_nonce( $_POST['nonce_select'], 'nonce_select_field' ) 
		  ) {
  
					 //print 'Sorry, your nonce did not verify.';
					 exit;
  
			  } else {
			 saveForm_select_quickgoogleanalytics();
				}
			  
	}			
  /*------nonce field check end ---- */ 



  
/*------nonce field check start ---- */
	 //status online oder offline 
if (isset($_REQUEST['submit_anonymized_ip'])) {
  if ( 
    ! isset( $_POST['nonce_ip'] ) 
    || ! wp_verify_nonce( $_POST['nonce_ip'], 'nonce_ip_field' ) 
		) {

   				//print 'Sorry, your nonce did not verify.';
   				exit;

			} else {
   		saveForm_quickgoogleanalytics_anonymized_ip();
  			}
}
/*------nonce field check end ---- */ 
	 
  
  
 showForm_quickgoogleanalytics();
 }
/* --------------------------------------------------------------------------------------------------------------------------------------- */ 
 
    
/* --------------------------------------------------------------------------------------------------------------------------------------- */  
 function saveForm_quickgoogleanalytics() {
  if (sanitize_text_field($_POST['quickgoogleanalytics_ua']) ) {

  update_option('quickgoogleanalytics_ua', sanitize_text_field($_POST['quickgoogleanalytics_ua']) );
  }
  
 }
/* --------------------------------------------------------------------------------------------------------------------------------------- */

/* --------------------------------------------------------------------------------------------------------------------------------------- */  
function saveForm_g_quickgoogleanalytics() {
	if (sanitize_text_field($_POST['quickgoogleanalytics_g']) ) {
  
	update_option('quickgoogleanalytics_g', sanitize_text_field($_POST['quickgoogleanalytics_g']) );
	}
	
   }
  /* --------------------------------------------------------------------------------------------------------------------------------------- */
  

  /* --------------------------------------------------------------------------------------------------------------------------------------- */  
function saveForm_select_quickgoogleanalytics() {
	if (sanitize_text_field($_POST['quickgoogleanalytics_select']) ) {
  
	update_option('quickgoogleanalytics_select', sanitize_text_field($_POST['quickgoogleanalytics_select']) );
	}
	
   }
  /* --------------------------------------------------------------------------------------------------------------------------------------- */
  



/* --------------------------------------------------------------------------------------------------------------------------------------- */  
//safe anonymized ip field
function saveForm_quickgoogleanalytics_anonymized_ip() {
  if (sanitize_text_field($_POST['quickgoogleanalytics_ip']) ) {

  update_option('quickgoogleanalytics_ip', sanitize_text_field($_POST['quickgoogleanalytics_ip']) );
  }
  
 }
/* --------------------------------------------------------------------------------------------------------------------------------------- */


/* --------------------------------------------------------------------------------------------------------------------------------------- */
function showForm_quickgoogleanalytics() {

  
  
   $quickgoogleanalytics_ua_show = get_option('quickgoogleanalytics_ua');
   $quickgoogleanalytics_g_show = get_option('quickgoogleanalytics_g');

  //tel 1. Google Analytics Code
  echo '<h2 id="info">Quick Google Analytics Code</h2>';
  echo '<div id="info">these fields will soon be deactivated.</div>';
  echo '<form method="post">';
  echo '<label for="quickgoogleanalytics_ua"><strong>Add Google Analytics Code (Exampl.: UA-12345678-9) </strong><br />';
  echo '<input type="text"  name="quickgoogleanalytics_ua" size="50" maxlength="50" value="' . $quickgoogleanalytics_ua_show . '">';
  echo '</label><br /><p></p>';
  echo '<input type="submit" style="height: 25px; width: 250px" name="submit" value="Sichern / Save">';
  wp_nonce_field( 'nonce_ua_field', 'nonce_ua' );
echo '</form><br/>';

 //tel 2. Google Analytics Code 4
 echo '<h2>Quick Google Analytics 4 Code</h2>';
 echo '<form method="post">';
 echo '<label for="quickgoogleanalytics_ua"><strong>Add Google Analytics Code (Exampl.: G-ABCDEFGHIJ) </strong><br />';
 echo '<input type="text"  name="quickgoogleanalytics_g" size="50" maxlength="50" value="' . $quickgoogleanalytics_g_show . '">';
 echo '</label><br /><p></p>';
 echo '<input type="submit" style="height: 25px; width: 250px" name="submit_g" value="Sichern / Save">';
 wp_nonce_field( 'nonce_g_field', 'nonce_g' );
echo '</form><br/>';

/* ################### Checkfield ################ */
$quickgoogleanalytics_select_show = get_option('quickgoogleanalytics_select');

//Style Auswahl
echo '<h2 id="auswahlfeld">Select your active Code</h2>';
echo '<div id="info">these fields will soon be deactivated.</div>';
echo '<p>Which code should be displayed. Only the old ua-Code or only the new g-code or both old and new at the same time? new</p>';
echo '<form method="post">';

ECHO '<select name="quickgoogleanalytics_select">';

if ($quickgoogleanalytics_select_show == '' or $quickgoogleanalytics_select_show == '1')
{ echo '<option selected value="1" >Old Google Analytics Code (will soon be deactivated)</option>';}
else
{ echo '<option value="1" >Old Google Analytics Code (will soon be deactivated)</option>';}
	
if ($quickgoogleanalytics_select_show == '2')
{ echo '<option selected value="2" >Old UA-Code & New G-Code (will soon be deactivated)</option>';}
else
{ echo '<option value="2" >Old UA-Code & New G-Code (will soon be deactivated)</option>';}

if ($quickgoogleanalytics_select_show == '3')
{ echo '<option selected value="3" >Google Analytics 4 (G-ABCDEFGHIJ)</option>';}
else
{ echo '<option value="3" >Google Analytics 4 (G-ABCDEFGHIJ)</option>';}
	
if ($quickgoogleanalytics_select_show == '4')
{ echo '<option selected value="4" >All Google Analytics Code deactivated</option>';}
else
{ echo '<option value="4" >All Google Analytics Code deactivated</option>';}



echo '</select>';


echo '<br />';
echo '<input type="submit" style="height: 25px; width: 250px" name="submit_select" value="Sichern / Save">';
  wp_nonce_field( 'nonce_select_field', 'nonce_select' );
  echo '</form>';
  echo '<br /><br />';	



/* #################### IP ######################### */

// IP Anonymized
	
	$quickgoogleanalytics_ip_show = get_option('quickgoogleanalytics_ip');
	
	if ($quickgoogleanalytics_ip_show == 'an')
		{
		$quickgoogleanalytics_ip_an = "<input name='quickgoogleanalytics_ip' type='radio' value='an' checked>";
		$quickgoogleanalytics_ip_aus = "<input name='quickgoogleanalytics_ip' type='radio' value='aus'>";
		}
	else
		{
		$quickgoogleanalytics_ip_an = "<input name='quickgoogleanalytics_ip' type='radio' value='an'>";
		$quickgoogleanalytics_ip_aus = "<input name='quickgoogleanalytics_ip' type='radio' value='aus' checked>";
		}
	
	
	echo "<h2>Anonymize IP</h2>";
	echo '<p>Anonymize IP adress?</p>';
	echo "<form method='post'>";
	echo "<table width='200' border='0'>";
	echo "<tr>";
	echo "<td width='20'>$quickgoogleanalytics_ip_aus</td>";
	echo "<td width='180'>No</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>$quickgoogleanalytics_ip_an</td>";
	echo "<td>Yes</td>";
	echo "</tr>";
	echo "</table>";
	echo "<input type='submit' style='height: 25px; width: 250px' name='submit_anonymized_ip' value='Sichern / Save'>";
	wp_nonce_field( 'nonce_ip_field', 'nonce_ip' );
	echo "</form><br />";
/* ########################################################## */
  
  ?>
  </div>
  <hr />
 
  <div class="wrap">
 
  <h2>Infos</h2>
  <p>Dies ist das Quick Google Analytics Plugin - programmiert von Eric-Oliver M&auml;chler von <a href="http://www.chefblogger.me" target="_blank">www.chefblogger.me</a>. Mehr von meinen WordPress Plugins findet man übrigens unter <a href="https://www.ericmaechler.com/produkt-kategorie/wordpress-plugins/" target="_blank">hier</a> </p>

  
  </div>
  <?php
 }
 /* --------------------------------------------------------------------------------------------------------------------------------------- */
?>