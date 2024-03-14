<?php 
/* 
Plugin Name: WP Database Optimizer Tools
Plugin URI: http://xtremenews.info/wordpress-plugins/wp-database-optimizer-tools/ 
Description: This plugin will help you to optimize your wordpress database. 
Version: 0.2
Author: Moyo
Author URI: http://xtremenews.info


Copyright 2011  Moyo  

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110, USA


*/

if ('wp-database-optimizer-tools.php' == basename($_SERVER['SCRIPT_FILENAME'])){
	die ('Please do not access this file directly. Thanks!');
}



define( 'HMBKP_REQUIRED_WP_VERSION', '3.1' );
define( 'HMBKP_REQUIRED_WP_VERSION', 'wp-database-optimizer-tools');

// Don't activate on old versions of WordPress
if ( version_compare( get_bloginfo('version'), HMBKP_REQUIRED_WP_VERSION, '<' ) ) {

	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	deactivate_plugins( ABSPATH . 'wp-content/plugins/' . HMBKP_PLUGIN_SLUG . '/index.php' );

	if ( isset( $_GET['action'] ) && ( $_GET['action'] == 'activate' || $_GET['action'] == 'error_scrape' ) )
		die( sprintf( __( 'BackUpWordPress requires WordPress version %s.', 'hmbkp' ), HMBKP_REQUIRED_WP_VERSION ) );

}


// Hook for adding admin menus
add_action('admin_menu', 'mt_add_pages');


// action function for above hook
function mt_add_pages() {
    // Add a new top-level menu 
   add_menu_page('DB Database Optimizer Tools Options', 'DB Optimizer', 'manage_options', 'wp-database-optimizer-tools', 
                  'db_optimizer_options', get_option('siteurl').'/wp-content/plugins/wp-database-optimizer-tools/images/database.png '  );
                  
                  
   // Add a submenu to the custom top-level menu:
    add_submenu_page('wp-database-optimizer-tools', __('DB Backup','db-backup'), __('DB Backup','db-backup'), 'manage_options', 'db-backup', 'dbBackup');
    
    
    //adding extra menu repaid DB
// Add a submenu to the custom top-level menu:
    add_submenu_page('wp-database-optimizer-tools', __('DB Repair','db-repair'), __('DB Repair','db-repair'), 'manage_options', 'db-repair', 'dbRepair');
                  
}


function dbBackup() {
     require_once('databasebackup/backup.php');  
}



function dbRepair() {
     require_once('repairDB.php');  
}


////////////////////////////////////
// Functions
////////////////////////////////////

function cleanDB($type){
    global $wpdb;
    $msg = "" ;

    if($type == 'revision' ){
      $query = "DELETE FROM $wpdb->posts WHERE post_type = 'revision'";
      $revisions = $wpdb->query( $query );
      $msg .= '<div id="success" ><strong> ' .$revisions. ' revisions deleted</strong></div>' ;
    }
    
    if($type == 'trash' ){
      $query = "DELETE FROM $wpdb->posts WHERE post_status = 'trash'";
      $trash = $wpdb->query( $query );
      $msg .= '<div id="success" ><strong> ' .$trash. ' post(s) in the trash deleted</strong></div>' ;
    }
    
    if($type == 'autodraft'){
      $query = "DELETE FROM $wpdb->posts WHERE post_status = 'auto-draft'";
      $autodraft = $wpdb->query( $query );
      $msg .= '<div id="success" ><strong>'. $autodraft. ' autodrafts deleted</strong></div>'; 
    }
    
    
    if($type == 'spam'){
      $query = "DELETE FROM $wpdb->comments WHERE comment_approved = 'spam';";
      $comments = $wpdb->query( $query );
      $msg .= '<div id="success" ><strong>'.$comments. ' spam deleted</strong></div>';
     }   

    if($type == 'unapproved') {
      $query = "DELETE FROM $wpdb->comments WHERE comment_approved = '0';";
      $comments = $wpdb->query( $query );
      $msg .= '<div id="success" ><strong>'.$comments. ' unapproved comments deleted</strong></div>';
    } 
    
    if($type == ""){
      $msg = '<div id="warning" ><strong>You did not select an action to do</strong></div>' ;
    }     


   return  $msg;

}

function getDBInfo($type){
    global $wpdb;
    $msg = "" ;
    
    if($type == "revisions") {
    
            $sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'revision'";
            $revisions = $wpdb->get_var( $sql );

            //var_dump(!$revisions ==);
            if(!$revisions == 0 || !$revisions == NULL){
              $msg .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$revisions.__(' revisions in your database');
            }
            else $msg .='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp No post revisions found';
           

     }
     
     
     if($type == "trash") {
    
            $sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'trash' ";
            $trash = $wpdb->get_var( $sql );

            
            if(!$trash == 0 || !$trash == NULL){
              $msg .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. $trash.__(' post(s) marked as trash in your database');
            }
            else $msg .='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp You do not have post in the trash ';
           

     }   
     
     if($type == "autodraft" ){
            $sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'auto-draft'";
            $autodraft = $wpdb->get_var( $sql );

            if(!$autodraft == 0 || !$autodraft == NULL){
              $msg .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$autodraft.__(' autodraft post(s) in your database');
            }
            else $msg .='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; No auto draft posts found';
           
			
			}
        
      if($type =="spam"){
            $sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = 'spam';";
            $comments = $wpdb->get_var( $sql );
            if(!$comments == NULL || !$comments == 0){
              $msg .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$comments.__(' spam comments found').' | <a href="edit-comments.php?comment_status=spam">'.__(' Review Spams</a>');
            } else
              $msg .='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; No spam comments found';          
      }
      
      if($type =="unapproved"){
            $sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '0';";
            $comments = $wpdb->get_var( $sql );
            if(!$comments == NULL || !$comments == 0){
              $msg .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$comments.__(' unapproved comments found').' | <a href="edit-comments.php?comment_status=moderated">'.__(' Review Unapproved Comments</a>');;
            } else
              $msg .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; No unapproved comments found';
      }

      if($type == "" ){
            $msg .= '<div id="warning" ><strong>You did not select an action</strong></div>';
           
      } // end of switch


      return $msg;
   


} 

function db_optimizer_options() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
		
  }else{
  
  ?>
  
  <html>

	<head>
	<style>
	<?php require_once("css/style.php") ; ?> 
	</style>
	</head>
	<body>
  
  <?php
    echo '
    
    
    
    <div class="wrap">';
 
  if(isset($_POST["wp-optimize"])){

  $result;

    if (isset($_POST["revisions"])) {
       $result = cleanDB('revision');
       
       echo $result;

    }
    	
    if (isset($_POST["autodraft"])) {
        $result = cleanDB('autodraft');
         echo $result;
    }	
    
    if (isset($_POST["spam"])) {
        $result = cleanDB('spam');
        echo $result;
    }
    
    if (isset($_POST["unapproved"])) {
         $result = cleanDB('unapproved');
         echo $result;
    }
    
    if (isset($_POST["trash"])) {
         $result = cleanDB('trash');
         echo $result;
    }
    
    
    if (isset($_POST["optimize-db"])) {
        $result .= '<div id="success" ><strong>'. DB_NAME.__(" Database Optimized!</strong></div>");
        echo $result;
      
        
        }
        
        
  if ($result == ''){
    echo '<div id="warning" >';
    echo '<strong>You did not select any action to perform</strong></div>';
  }
}

?>

<h2><?php _e('Database Optimization Options'); ?></h2>
	
<div id="tableForm" >	  
	
<table border="0" cellspacing="0" cellpadding="0">
<form action="#" method="post" name="optimize_form" id="optimize_form">
  <tr>
    <td></td>
  </tr>

  <tr>
    
    <td ><input name="revisions" id="revisions" type="checkbox" value="" />
	 <?php _e('Remove all Post revisions'); ?><br />
   <small><?php _e(getDBInfo('revisions')); ?></small></td>
  </tr>
  <tr>
    
    <td>&nbsp;</td>
  </tr>
  <tr>
  
  <tr>
    
    <td ><input name="trash" id="trash" type="checkbox" value="" />
	 <?php _e('Remove all Post in the Trash'); ?><br />
   <small><?php _e(getDBInfo('trash')); ?></small></td>
  </tr>
  
  
  <tr>
    <td>&nbsp;</td>
  </tr>
   
    <td><input name="autodraft" id="autodraft" type="checkbox" value="" />
	 <?php _e('Remove all auto draft posts'); ?><br />
   <small><?php _e(getDBInfo('autodraft')); ?></small></td>
  </tr>
  <tr>
    
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    
    <td><input name="spam" type="checkbox" value="" />
	 <?php _e('Clean marked Spam comments'); ?><br />
   <small><?php _e(getDBInfo('spam') ); ?></small></td>
  </tr>
  <tr>
    
    <td>&nbsp;</td>
  </tr>
  <tr>
    
    <td><input name="unapproved" type="checkbox" value="" />
	 <?php _e('Clean Unapproved comments'); ?><br />
   <small><?php _e(getDBInfo('unapproved' )); ?></small></td>
  </tr>
  <tr>
    
    <td>&nbsp;</td>
  </tr>
  <tr>
    
    <td><input name="optimize-db" type="checkbox" value="" />
	 <?php _e('Optimize database tables'); ?></td>
  </tr>
  <tr>
    
    <td>&nbsp;</td>
  </tr>
    
    <td><input class="button-primary" type="submit" name="wp-optimize" value="<?php _e('PROCESS'); ?>" /></td>
  </tr>
  <tr>
    
    <td>&nbsp;</td>
  </tr>
  
  </form>
</table>


</div> <!-- End table checkboxes -->

<div id="infoHelp" >

<p><?php _e('Plugin Homepage'); ?> :&nbsp; <a href="http://xtremenews.info/wordpress-plugins/wp-database-optimizer-tools/" target="_blank">WP Database Optimizer Tools</a></p>


<p><?php _e('Buy me a Beer '); ?> : <center> <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="5N6K3MF8HSTKL">
		<input type="image" src="https://www.paypalobjects.com/WEBSCR-640-20110429-1/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypalobjects.com/WEBSCR-640-20110429-1/en_US/i/scr/pixel.gif" width="1" height="1">
	</form> </center></p>
    


</div> <!-- End info help -->





<div id="optimizeTable" >

<h3><?php _e('Database Tables Report'); ?></h3>
<h5><?php _e('Database Name:'); ?> '<?php _e(DB_NAME);?>'</h5>

<p><?php _e('Optimize all the tables found in the database.')?></p>


<a name="report">&nbsp;</a>

<table class="widefat fixed" cellspacing="0">
<thead>
	<tr>
	<th scope="col"><?php _e('Table'); ?></th>
	<th scope="col"><?php _e('Size') ;?></th>
	<th scope="col"><?php _e('Status'); ?></th>
	<th scope="col"><?php _e('Space Save'); ?></th>
	</tr>
</thead>
<tfoot>
	<tr>
	<th scope="col"><?php _e('Table'); ?></th>
	<th scope="col"><?php _e('Size')?></th>
	<th scope="col"><?php _e('Status'); ?></th>
	<th scope="col"><?php _e('Space Save'); ?></th>
	</tr>
</tfoot>
<tbody id="the-list">
<?php
$alternate = ' class="alternate"';
	$db_clean = DB_NAME;
	$tot_data = 0;
	$tot_idx = 0;
	$tot_all = 0;
	$local_query = 'SHOW TABLE STATUS FROM `'. DB_NAME.'`';
	$result = mysql_query($local_query);
	if (mysql_num_rows($result)){
		while ($row = mysql_fetch_array($result))
		{
			$tot_data = $row['Data_length'];
			$tot_idx  = $row['Index_length'];
			$total = $tot_data + $tot_idx;
			$total = $total / 1024 ;
			$total = round ($total,3);
			$gain= $row['Data_free'];
			$gain = $gain / 1024 ;
			$total_gain += $gain;
			$gain = round ($gain,3);
			if (isset($_POST["optimize-db"])) {
        $local_query = 'OPTIMIZE TABLE '.$row[0];
			  $resultat  = mysql_query($local_query);
        //echo "optimization";
            }

      if ($gain == 0){
				echo "<tr". $alternate .">
					<td class='column-name'>". $row[0] ."</td>
					<td class='column-name'>". $total ." Kb"."</td>
					<td class='column-name'>" .  __('Already Optimized', 'wp-optimize') . "</td>
					<td class='column-name'>0 Kb</td>
					</tr>\n";
			} else
			{
      if (isset($_POST["optimize-db"])) {
        echo "<tr". $alternate .">
					<td class='column-name'>". $row[0] ."</td>
					<td class='column-name'>". $total ." Kb"."</td>
          <td class='column-name' style=\"color: #0000FF;\">" .  __('Optimized', 'wp-optimize') . "</td>
					<td class='column-name'>". $gain ." Kb</td>
					</tr>\n";
        }
        else {
        echo "<tr". $alternate .">
					<td class='column-name'>". $row[0] ."</td>
					<td class='column-name'>". $total ." Kb"."</td>
          <td class='column-name' style=\"color: #FF0000;\">" .  __('Need to Optimize', 'wp-optimize') . "</td>
					<td class='column-name'>". $gain ." Kb</td>
					</tr>\n";
        }
			}
			$alternate = ( empty( $alternate ) ) ? ' class="alternate"' : '';
		}
	}
	

?>
</tbody>
</table>

</div> <!-- optimize table -->


</div> <!-- end wrapper -->

</body>
</html>

<?php
	
  }
	
}



?>