<?php 
/* 
Plugin Name: DoFollow Case by Case
Plugin URI: https://apasionados.es/#utm_source=wpadmin&utm_medium=plugin&utm_campaign=wpdofollowplugin 
Description: DoFollow Case by Case allows you to selectively apply dofollow to comments and make links in pages or posts "nofollow".
Version: 3.5.1
Author: Apasionados, Apasionados del Marketing, NetConsulting
Author URI: https://apasionados.es 
Text Domain: dofollow-case-by-case
Domain Path: /i18n/
*/


// --- Table NODOFOLLOW
function install_table(){	
    global $wpdb;
	$table_name = $wpdb->prefix."nodofollow";	
	$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			id_comment bigint(20),
			active_dofollow bigint(20),
			user_email varchar(100),
			active_dofollow_url_author bigint(20),
			url  varchar(100),			
			opc  varchar(100),
			UNIQUE KEY id (id)
		);";
	$wpdb -> query($sql);
}

// -- upload css
//add_action('wp_print_styles', 'carga_estilos_theme');
add_action('admin_init', 'upload_css');
function upload_css(){	
	//plugin_basename( dirname(__FILE__).'/dofollow-case-by-case.php' )
  wp_register_style('NDF_style', plugins_url('css/style.css', __FILE__), array(), '1.1', 'all');
  wp_enqueue_style('NDF_style');
}

// -- Languages
add_action('plugins_loaded', 'language_NDF');
function language_NDF() {
	load_plugin_textdomain('dofollow-case-by-case', false, dirname(plugin_basename( __FILE__ )).'/i18n/'); 
}

// -- Acess plugins settings from PLUGINS / INSTALLED PLUGINS
function ndf_plugin_action_links( $links, $file ) {
	if ( $file == plugin_basename( dirname(__FILE__).'/dofollow-case-by-case.php' ) ) {
		$links[] = '<a href="' . admin_url( 'admin.php?page=cont_config_NDF' ) . '">'.__( 'Settings' ).'</a>';
	}
	return $links;
}
add_filter( 'plugin_action_links', 'ndf_plugin_action_links', 10, 2);

// --- Config MENU
add_action('admin_menu','menu_config_NDF');
function menu_config_NDF(){
	if (current_user_can('manage_options')) {
	//menu principal
	add_menu_page("DoFollow", "DoFollow", 'activate_plugins', "cont_config_NDF", "cont_config_NDF",  plugins_url('images/icon.png', __FILE__));
	}
}

// --- Config SUB-MENU-EMAIL
add_action('admin_menu','sub_menu_config_NDF_email');
function sub_menu_config_NDF_email(){
	//sub menu principal
	$textmenu = __( 'Email White List', 'dofollow-case-by-case');
	add_submenu_page("cont_config_NDF", $textmenu , $textmenu, 'activate_plugins', "cont_config_sub_NDF_email", "cont_config_sub_NDF_email");
}

// --- Config SUB-MENU-URL
add_action('admin_menu','sub_sub_menu_config_NDF_url');
function sub_sub_menu_config_NDF_url(){
	//sub menu principal
	$textmenu = __( 'URL White List', 'dofollow-case-by-case');
	add_submenu_page("cont_config_NDF", $textmenu, $textmenu , 'activate_plugins', "cont_config_sub_NDF_url", "cont_config_sub_NDF_url");
}

// --- Msj ERROR 
function show_message_($message, $errormsg = false){
	if ($errormsg) {
		echo '<div id="message" class="error">';
	}
	else {
		echo '<div id="message" class="updated fade">';
	}
	echo "<p><strong>$message</strong></p></div>";
} 

// --- Call Msj
function show_admin_messages($mensaje, $bool_error){
    show_message_($mensaje, $bool_error);
}

// -- pagination_limit
function pagination_limit($page, $opc){
	global $wpdb;           
	$num_rows = $wpdb->query($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."nodofollow WHERE opc = %s",$opc));	
	//reg per page
	$rows_per_page = 10;	
	//total pages
	$lastpage= ceil($num_rows / $rows_per_page);	
	//value page and page finish
	$page=(int)$page;
	if($page > $lastpage)$page= $lastpage;
	if($page < 1)$page=1;
	//create limit
	$limit= 'LIMIT '. ($page - 1) * $rows_per_page . ', ' .$rows_per_page;
    //delete cache
	$wpdb->flush();
	return $limit;	
}

// --- pagination_href
function pagination_href($paged, $opc){
	global $wpdb;			
	$num_rows = $wpdb->query($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'nodofollow WHERE opc = %s',$opc));
	//total pages
    $lastpage= ceil($num_rows / 10);
	
	$result = '<ul>';
	if($lastpage != 1)	
	$result .= "<li><a href='".get_pagenum_link($paged - 1)."'>&laquo;</a></li>";
	for ($j=1; $j <= $lastpage; $j++){		
		if($paged == $j)					
			$result .= "<li><a class='visited'>".$j."</a></li>";				
		else
			$result .= "<li><a href='".get_pagenum_link($j)."'>".$j."</a></li>";
	 }
	if($lastpage != $paged)
		$result .= "<li><a href='".get_pagenum_link($paged + 1)."'>&raquo;</a></li>";
		
    $result .= '</ul>';			           
	//delete cache
	$wpdb->flush();
	return $result;
}

// --- Create whitelist
function listWhiteDofollow($opc){	
	global $wpdb;
	$url = plugins_url('/images/', __FILE__);			
	//get Var and call of pagination_limit
	if(isset($_REQUEST['paged'])){ $page= $_REQUEST['paged']; $limit = pagination_limit($page, $opc);}
	else{ $page=1; $limit = pagination_limit($page, $opc);}			
	
	$aNDF = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."nodofollow WHERE opc ='%s' $limit", $opc));	
	
	$table_user = $table_user ?? null;
	switch($opc){
		case "email":										
			if($aNDF){
				$table_user .= '<table style="margin-top:20px; margin-left:20px; margin-right:20px; border: 1px solid #c3c3c3"><tr style="background-color:#F1F1F1; font-size:12px"><td style="padding:15px"</td><td style="padding:15px"><strong>Correo Electronico</strong></td><td style="padding:15px; text-align:center"><strong>Url Author Dofollow</strong></td></tr>';	
					foreach($aNDF as $aUsuario){
						$table_user .= '<tr style="font-size:12px">';
						$table_user .= '<td width="1%" style="padding: 10px"><input type="checkbox" name="'.$aUsuario->id.'_action" value="1"/></td>';
						$table_user .= '<td width="40%" style="padding: 10px">'.$aUsuario->user_email.'</td>';					
						if($aUsuario->active_dofollow_url_author == 1)
							$table_user .= '<th width="30%" style="padding: 10px"><img src="'.$url.'ok.png" width="20px" /></th>';						
						else
							$table_user .= '<th width="30%" style="padding: 10px"><img src="'.$url.'ko.png" width="20px" /></th>';									
						$table_user .='</tr>';
					}				
				$table_user .= '</table><p></p>';				
				$table_user .= '<div class="pagination">'.pagination_href($page, $opc).'</div>';	
				
			}		
			break;		
		case "url":						
			if($aNDF){
				$table_user = '<table style="margin-top:20px; margin-left:20px; margin-right:20px; border: 1px solid #c3c3c3"><tr style="background-color:#F1F1F1; font-size:12px"><td></td><td style="padding:15px"><strong>URL</strong></td></tr>';	
					foreach($aNDF as $aUrl){				
						$table_user .= '<tr style="font-size:12px">';
						$table_user .= '<td width="1%" style="padding: 10px"><input type="checkbox" name="'.$aUrl->id.'_action" value="1"/></td>';
						$table_user .= '<td width="60%" style="padding: 10px">'.$aUrl->url.'</td></tr>';						
					}
				$table_user .= '</table><p></p>';
				$table_user .= '<div class="pagination">'.pagination_href($page, $opc).'</div>';	
			}
			break;
		default:
			break;
	}	
	//delete cache
	$wpdb->flush();
	return $table_user;
}

// --- Insert Users(email)
function getEmail($act){
	if (isset($_POST['apa_dofollow_case_by_case_nonce'])) {
		if (wp_verify_nonce($_POST['apa_dofollow_case_by_case_nonce'], 'apa_dofollow_case_by_case_action')) {
			global $wpdb;		
			$aUserNDF = $wpdb->query($wpdb->prepare('SELECT user_email FROM '.$wpdb->prefix.'nodofollow WHERE user_email = %s',$_REQUEST['ndf_email']));		
			//check to see if this is inserted in the database
			if($aUserNDF){					
				show_admin_messages(__( 'This email is already contained in the White List. Please go to Email White List to edit it.', 'dofollow-case-by-case'), true);
			}else{
				$data = array('active_dofollow' => 1, 'user_email' => $_REQUEST['ndf_email'], 'opc' => 'email','active_dofollow_url_author' => $act);
				$wpdb->insert($wpdb->prefix."nodofollow", $data);
				show_admin_messages(__( 'Email added correctly to the White List', 'dofollow-case-by-case'), false);
			}
			//delete cache
			$wpdb->flush();
		}
	} else {
		// Invalid nonce.
		die('Security check failed');
	}	
}

// --- Insert URL
function getUrl(){				
	if (isset($_POST['apa_dofollow_case_by_case_nonce'])) {
		if (wp_verify_nonce($_POST['apa_dofollow_case_by_case_nonce'], 'apa_dofollow_case_by_case_action')) {
			global $wpdb;	
			$aUrlNDF = $wpdb->get_row("SELECT url FROM ".$wpdb->prefix."nodofollow where url ='".$_REQUEST['ndf_url']."'", ARRAY_A);		
			//check to see if this is inserted in the database
			if($aUrlNDF){					
				show_admin_messages(__('This URL is already contained in the White List. Please go to Email White List to edit it.', 'dofollow-case-by-case'), true);
			}else{					
				$data = array('active_dofollow' => 1, 'url' => $_REQUEST['ndf_url'], 'opc' => 'url');
				$wpdb->insert($wpdb->prefix."nodofollow", $data);
				show_admin_messages(__( 'URL added correctly to the URL White List','dofollow-case-by-case'), false);			
			}
			//delete cache
			$wpdb->flush();
		}
	} else {
		// Invalid nonce.
		die('Security check failed');
	}	
}

// --- Delete the selected data of the listwhite
function get_delete_list($aNDFs){	
	global $wpdb;
	$message = '';
	foreach($aNDFs as $aNDF){
		if(isset($_REQUEST[$aNDF->id.'_action']))
			$wpdb->delete($wpdb->prefix.'nodofollow', array('id'=> $aNDF->id));					
			$message = __( 'Entry removed correctly', 'dofollow-case-by-case');
	}
	return $message;
}

// --- Update email URL Author dofollow of the whitelist
function get_update_list($aNDFs, $active){	
	global $wpdb;
	$message = '';
	foreach($aNDFs as $aNDF){
		//if active 1 activate
		if($active == 1){	
			if(isset($_REQUEST[$aNDF->id.'_action'])){				
				$data = array('active_dofollow_url_author' => 1);
				$where = array('id' => $aNDF->id);
				$wpdb->update($wpdb->prefix.'nodofollow', $data, $where);					
				$message = __('Entry updated correctly', 'dofollow-case-by-case');
			}
		}		
		//if active 2 deactivate
		if($active == 0){		
			if(isset($_REQUEST[$aNDF->id.'_action'])){			
				$data = array('active_dofollow_url_author' => 0);
				$where = array('id' => $aNDF->id);
				$wpdb->update($wpdb->prefix.'nodofollow', $data, $where);					
				$message = __('Entry updated correctly', 'dofollow-case-by-case');
			}
		}
	}
	return $message;
}

// --- Comment Clean all words and only I keep the URL
function clearComment( $comment ) {
	preg_match( '%(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?%iu', $comment, $matches );
	return isset( $matches[0] ) ? trim( $matches[0], '"') : NULL;
}


// --- main panel - config
function cont_config_NDF(){	
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }
	global $wpdb;
	add_action('admin_notices', 'show_admin_messages');
	//submit ok
	if(isset($_REQUEST['ndf_submit'])){
		//check email
		if(isset($_REQUEST['ndf_email']) & $_REQUEST['ndf_email'] != ''){					
			if(isset($_REQUEST['url_author_ndf'])) $act = 1; else $act = 0;
			getEmail($act);
		}
		//check URL		
		if(isset($_REQUEST['ndf_url']) & $_REQUEST['ndf_url'] != 'https://'){			
			getUrl();
		}
	}
?>	
		<div id="dofollow-case-by-case" class="wrap">
			<div id="poststuff">
				<div id="dofollow-header">
					<h2><?php _e( 'DoFollow Configuration', 'dofollow-case-by-case'); ?></h2>
				</div>
				<div id="left">
					<form action="" method="POST">
						<?php wp_nonce_field('apa_dofollow_case_by_case_action', 'apa_dofollow_case_by_case_nonce'); ?>
						<!-- <h2>Configuraci&oacute;n dofollow</h2>-->
						<br/>
						<p><?php _e( 'This plugin allows you to set links in comments to be dofollow instead of nofollow. When editing a comment, now you have the option to remove the rel=&quot;nofollow&quot; attributes from the links contained in them.', 'dofollow-case-by-case'); ?></p>
						<p><?php _e( 'To make it easier, you can also setup commenters emails whose links in comments should always be dofollow and you can even set their Author URL when commenting to be dofollow.', 'dofollow-case-by-case'); ?></p>
						<p><?php _e( 'On the other side you can also define URLs that when contained in a comment are always dofollow, so that you can setup links to your own sites to be always dofollow.', 'dofollow-case-by-case'); ?></p>
					
						<div class="postbox">
							<h3 class="hndle"><span><?php _e( 'Email', 'dofollow-case-by-case'); ?></span></h3>												
							<div class="inside">
                                <div class="postboxp">
								<p><?php _e( 'Removes the &quot;nofollow&quot; attribute from the comments made by a certain commenter, identified by his email address. You can also setup that his Author URL when commenting should be dofollow. Adding an email address here adds it to the Email White List. Please edit it there.', 'dofollow-case-by-case'); ?></p>
                                <p><?php _e( 'Email', 'dofollow-case-by-case'); ?>: <input type="text" name="ndf_email" id="ndf_email" style="width:300px" value=""/></p>							
                                <p><i><?php _e( 'DoFollow Author URL from commenter', 'dofollow-case-by-case'); ?> <strong>dofollow</strong></i></p>
                                <p><?php _e( 'When checked: Enable', 'dofollow-case-by-case'); ?> : <input type="checkbox" name="url_author_ndf" id="url_author_ndf" value="0"/></p>
                                <p><input type="submit" class="button-primary" id="ndf	_submit" name="ndf_submit" value="<?php _e( 'Save', 'dofollow-case-by-case'); ?>"/></p>
                                </div>
                            </div>
						</div>						
					
						<div class="postbox">
							<h3 class="hndle"><span>Url</span></h3>						
							<div class="inside">
                                <div class="postboxp">
                                <p><strong><?php _e( 'Removes the &quot;nofollow&quot; attribute from the URLs you setup. Adding an email address here adds it to the URL White List. Please edit it there.', 'dofollow-case-by-case'); ?></strong></p>							
                                <p><i><?php _e( 'You can add links to your own site and also to external sites.', 'dofollow-case-by-case'); ?></i></p>
                                <p>URL:<input type="text" name="ndf_url" id="ndf_url" style="width:300px" value="https://"/></p>
                                <p><input type="submit" class="button-primary" id="ndf_submit" name="ndf_submit" value="<?php _e( 'Save', 'dofollow-case-by-case'); ?>"/></p>
                                </div>
                            </div>
						</div>	
					</form>
				</div>
				<div id="right">
				</div>
			</div>			
		</div>
<?php
}

// --- Main Panel secondary - whitelist EMAIL
function cont_config_sub_NDF_email(){
	global $wpdb;
	add_action('admin_notices', 'show_admin_messages');
	if(isset($_REQUEST['ndf_action_submit'])){		
		//check action selected 
		$aNDFs = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."nodofollow WHERE active_dofollow = %d", 1));	
		switch($_REQUEST['acciones']){
			case 0:		
				$message =  get_update_list($aNDFs, $_REQUEST['acciones']);
				break;			
			case 1:
				$message =  get_update_list($aNDFs, $_REQUEST['acciones']);
				break;
			case 2:
				$message = get_delete_list($aNDFs);					
				break;
			default:
				break;
		}
		show_admin_messages($message, false);		
		//delete cache
		$wpdb->flush();
	}
?>
		<div id="dofollow-case-by-case" class="wrap">
			<div id="poststuff">
				<div id="dofollow-header">
					<h2><?php _e( 'Setup EMAIL White List', 'dofollow-case-by-case');?></h2>			
				</div>
				<div id="left">
					<form action="" method="POST">
					<br/>
					<p><?php _e( 'The Email White List contains a list of emails of commenters, whose links in comments are allways dofollow.', 'dofollow-case-by-case');?></p>		
					<p><?php _e(  'And you can also choose to make the Author URL dofollow. By default the Author URL is not followed.', 'dofollow-case-by-case');?></p>
					<p><?php _e( 'Here you can add for example the email addresses of your staff and collaborators.', 'dofollow-case-by-case');?></p>		
					
						<p> 
							<select name="acciones" size="1"> 
								<option value=""><?php _e( 'Bulk actions', 'dofollow-case-by-case'); ?></option> 							
								<option value="0"><?php _e( 'Disable DoFollow Author URLs', 'dofollow-case-by-case'); ?></option> 
								<option value="1"><?php _e( 'Enable DoFollow Author URLs', 'dofollow-case-by-case'); ?></option> 							
								<option value="2"><?php _e( 'Remove', 'dofollow-case-by-case'); ?></option> 
							</select>
							<input type="submit" id="ndf_action_submit" name="ndf_action_submit" value="<?php _e( 'Apply', 'dofollow-case-by-case'); ?>" class="button action" style="margin-left:5px"/>
						</p>						
						
						<!-- Tabla de Emails --->
						<div class="postbox">
							<div class="postboxp">
                                <div style="padding-bottom:20px;">
                                    <h3 class="hndle"><?php _e( 'Email', 'dofollow-case-by-case'); ?></h3>
                                        <?php echo listWhiteDofollow('email');?>
                                </div>
							</div>
						</div>								
						
					</form>
				</div>
				<div id="right">
				</div>
			</div>
		</div>
<?php
}

// --- Main Panel secondary - whitelist URL
function cont_config_sub_NDF_url(){
	global $wpdb;
	add_action('admin_notices', 'show_admin_messages');
	if(isset($_REQUEST['ndf_action_submit'])){
		//check action selected
		$aNDFs = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."nodofollow WHERE active_dofollow = %d", 1));	
		switch($_REQUEST['acciones']){			
			case 3:
				$message = get_delete_list($aNDFs);					
				break;
			default:
				break;
		}
		show_admin_messages($message, false);
		//delete cache
		$wpdb->flush();
	}
?>
		<div id="dofollow-case-by-case" class="wrap">
			<div id="poststuff">
				<div id="dofollow-header">
					<h2><?php _e( 'Setup URL White List', 'dofollow-case-by-case');?></h2>									
				</div>
				<div id="left">
					<form action="" method="POST">
					<br/>
					<p><?php _e( 'The URL White List contains a list of URLs that when linked to in a comment, are always dofollow, nevertheless who links to 	them.', 'dofollow-case-by-case');?></p>			
					<p><?php _e( 'Here you can setup for example links from your sites or from other sites.', 'dofollow-case-by-case');?></p>			
						<p> 
							<select name="acciones" size="1"> 
								<option value="0"><?php _e( 'Bulk actions', 'dofollow-case-by-case'); ?></option> 											
								<option value="3"><?php _e( 'Remove', 'dofollow-case-by-case'); ?></option> 
							</select>
							<input type="submit" id="ndf_action_submit" name="ndf_action_submit" value="<?php _e( 'Apply', 'dofollow-case-by-case'); ?>" class="button action" style="margin-left:5px"/>
						</p>
						<!--- Tabla de Url --->
						<div class="postbox">
							<div class="postboxp">
                                <div style="padding-bottom:20px;">
                                    <h3 class="hndle">Url</h3>
                                    <?php echo listWhiteDofollow('url'); ?>			
                                </div>
							</div>
						</div>					
					</form>
				</div>
				<div id="right">
				</div>
			</div>
		</div>
<?php
}

// --- Create box in edit commment
add_action('add_meta_boxes', 'create_box');
function create_box() {
    $screens = array('comment');
    foreach ($screens as $screen) {
        add_meta_box('box_ext_id', __( 'DoFollow Case by Case Options', 'dofollow-case-by-case' ), 'box_inner_custom_box', $screen, 'normal');
    }
}

// --- Create checkbox option in edit comment
function box_inner_custom_box(){		
	global $wpdb, $comment;				
	$url = plugins_url('/images/', __FILE__);	
	//ID comment in Array 
	$comment_array = get_comment($comment->comment_ID, ARRAY_A);		
	$aCommentNDF = $wpdb->get_row($wpdb->prepare('SELECT active_dofollow FROM '.$wpdb->prefix.'nodofollow WHERE id_comment = %d', $comment_array['comment_ID']), ARRAY_A);			
	echo '<br /><label><strong>'.__( 'Change all comment links to DoFollow', 'dofollow-case-by-case').'</strong></label> <br /><br />';	 	
	echo '<input type="checkbox" name="nofollow_text" value="0" style="margin-left:20px"';
	
	if ($aCommentNDF['active_dofollow'] == 1){
		echo 'checked="checked"';
	}	
	echo '/><span style="padding-left: 10px">dofollow</span><br /><br />';			
	
	//check email user in whitelist
	$aAuthorNDF = $wpdb->get_row($wpdb->prepare('SELECT user_email, active_dofollow_url_author FROM '.$wpdb->prefix.'nodofollow WHERE user_email = %s',$comment_array['comment_author_email']), ARRAY_A);			
	
	echo '<p><strong>'.__( 'User contained in the DoFollow White List', 'dofollow-case-by-case').'</strong></p>';
	echo '<table width="50%" style="border: 1px solid #c3c3c3">';
	echo '<tr style="background-color:#F1F1F1; font-size:12px"><td style="padding: 10px" >'.__( 'Email', 'dofollow-case-by-case').'</td><td style="padding: 10px">Url Author Dofollow</td></tr>';
	//si exist in whitelist
	if ($aAuthorNDF['user_email']){
		echo '<tr><td style="padding: 10px">'.$aAuthorNDF['user_email'].'</td>';
		if ($aAuthorNDF['active_dofollow_url_author'] == 1)
			echo '<td style="padding: 10px; text-align:center"><img src="'.$url.'ok.png" width="20px" /></td>';
		else
			echo '<td style="padding: 10px; text-align:center"><img src="'.$url.'ko.png" width="20px" /></td>';
	}else{
		echo '<td coldspan="2" style="padding: 10px; text-align:center">El usuario no se encuentra a&ntilde;adido en la lista blanca.</td>';
	}
	echo '</tr></table>';
	//delete cache
	$wpdb->flush();
}

// --- Update options in edit comment
add_filter('edit_comment', 'update_comment', 17);
function update_comment($comment_content){
	global $wpdb, $comment;
	if(isset($_POST['nofollow_text'])){
		//ID comment and check if is selected
		$aCommentNDF = $wpdb->get_row($wpdb->prepare('SELECT id_comment FROM '.$wpdb->prefix.'nodofollow WHERE id_comment = %d', $_REQUEST['c']), ARRAY_A);	
		if($aCommentNDF != NULL){
				$active_dofollow = 1;		
				$data = array('id_comment' => $_REQUEST['c'], 'active_dofollow' => $active_dofollow);
				$where = array('id_comment' => $_REQUEST['c']);
				$wpdb->update($wpdb->prefix."nodofollow", $data, $where);
			}else{
			$active_dofollow = 1;		
			$data = array('id_comment' => $_REQUEST['c'], 'active_dofollow' => $active_dofollow);
			$wpdb->insert($wpdb->prefix."nodofollow", $data);
		}
	}
	else{
		//check if exist
		$aCommentNDF = $wpdb->get_row($wpdb->prepare('SELECT id_comment FROM '.$wpdb->prefix.'nodofollow WHERE id_comment = %d', $_REQUEST['c']), ARRAY_A);	
		if($aCommentNDF != NULL){
			$active_dofollow = 0;		
			$data = array('id_comment' => $_REQUEST['c'], 'active_dofollow' => $active_dofollow);
			$where = array('id_comment' => $_REQUEST['c']);
			$wpdb->update($wpdb->prefix."nodofollow", $data, $where);
		}
	}
	//delete cache
	$wpdb->flush();
}

// --- Update rel url nofollow - dofollow in execution time of URL Author
add_filter('get_comment_author_link', 'remove_DoFollowAuthor', 11);
function remove_DofollowAuthor($commentAuthor){
	global $comment, $wpdb;
	$comment_array = get_comment($comment->comment_ID, ARRAY_A ); 	
	
    $url = get_comment_author_url();
    $author = get_comment_author();
	
	$aAuthorEmailNDF = $wpdb->get_row($wpdb->prepare('SELECT id_comment, active_dofollow, active_dofollow_url_author FROM '.$wpdb->prefix.'nodofollow WHERE user_email = %s AND active_dofollow_url_author = 1', $comment_array['comment_author_email']), ARRAY_A);	

	//if($aAuthorEmailNDF['active_dofollow'] == 1)
	if (!is_null($aAuthorEmailNDF) && $aAuthorEmailNDF['active_dofollow'] == 1)
		if ( empty( $url ) || 'https://' == $url )
			$contentAuthor = $author;
		else
			$contentAuthor = "<a href='".$url."' rel='external' target='_blank'>".$author."</a>";
	else	
		if ( empty( $url ) || 'https://' == $url )
			$contentAuthor = $author;
		else
	        $contentAuthor = "<a href='".$url."' rel='external nofollow' target='_blank'>".$author."</a>";    
	
	//delete cache
	$wpdb->flush();
	return $contentAuthor;
}

// --- Update rel url nofollow - dofollow in execution time of comments
add_filter('get_comment_text', 'remove_DoFollowComment');
// add_filter('comment_text', 'remove_DoFollowComment');
function remove_DoFollowComment($c){
    global $comment, $wpdb;	
	//Array comment
	$comment_array = get_comment($comment->comment_ID, ARRAY_A ); 			
	//ID user desactivate of url dofollow of comments, and udate dofollow of the URL Author
	$aAuthorEmailNDF = $wpdb->get_row($wpdb->prepare('SELECT id_comment, active_dofollow FROM '.$wpdb->prefix.'nodofollow WHERE user_email = %s AND active_dofollow = 1',$comment_array['comment_author_email']), ARRAY_A);	
	//ID comment if is activate of delete url dofollow	
	$aCommentNDF = $wpdb->get_row($wpdb->prepare('SELECT id_comment, active_dofollow FROM '.$wpdb->prefix.'nodofollow WHERE id_comment = %d', $comment_array['comment_ID']), ARRAY_A);				
	//delete content and recovery URL
	$url = clearComment($c);		
	//URL of comment check in BBDD
	$aUrlNDF = $wpdb->get_row($wpdb->prepare('SELECT id_comment, active_dofollow FROM '.$wpdb->prefix.'nodofollow WHERE url = %s ', $url), ARRAY_A);	

	//if($aAuthorEmailNDF['active_dofollow'] == 1){
	if (!is_null($aAuthorEmailNDF) && $aAuthorEmailNDF['active_dofollow'] == 1){
			$c = str_replace('nofollow', 'external', $c);			
	}else{
			//if($aUrlNDF['active_dofollow'] == 1)
			if (!is_null($aUrlNDF) && $aUrlNDF['active_dofollow'] == 1)
				$c = str_replace('nofollow', 'external', $c);			
			else{
				//if($aCommentNDF['id_comment'] == $comment_array['comment_ID']){
				if (!is_null($aCommentNDF) && $aCommentNDF['id_comment'] == $comment_array['comment_ID']) {
					//if($aCommentNDF['active_dofollow'] == 1)
					if (!is_null($aCommentNDF) && $aCommentNDF['active_dofollow'] == 1)
						$c = str_replace('nofollow', 'external', $c);			
				}else{
					$c;
				}
			}
				
	}
	//update URL target _blank
	$c = str_replace('<a', '<a target="_blank"', $c);
	//delete cache
	$wpdb->flush();
	return $c;
}

// Activate Plugin
register_activation_hook(__FILE__, 'NDF_activation');
function NDF_activation() {  	
	install_table();
}

// Deactivate plugin
register_deactivation_hook(__FILE__, 'NDF_deactivation'); 
function NDF_deactivation() {	
}

//delete plugin and delete table nodofollow
register_uninstall_hook(__FILE__, 'NDF_deleteplugin');
function NDF_deleteplugin(){
	Global $wpdb;	
	 $sql = "DROP TABLE ". $wpdb->prefix."nodofollow";
	 $wpdb->query($wpdb->prepare($sql));
	 $wpdb->flush();	
}
?>