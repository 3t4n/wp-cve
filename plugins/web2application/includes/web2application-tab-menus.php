<?php
/* if ( ! defined( 'ABSPATH' ) ) exit;
define('WP_DEBUG', false); */
// Init Options Global
global $w2a_options;



add_thickbox();


function w2a_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
}
add_action( 'admin_enqueue_scripts', 'w2a_color_picker' );




function w2a_enqueue_media_lib_uploader() {
    //Core media script
    wp_enqueue_media();

    // Your custom js file
    wp_register_script( 'media-lib-uploader-js', plugins_url( 'media-lib-uploader.js' , __FILE__ ), array('jquery') );
    wp_enqueue_script( 'media-lib-uploader-js' );
}
add_action('admin_enqueue_scripts', 'w2a_enqueue_media_lib_uploader');

// the media uploader will not open without it
wp_enqueue_media();	


	
// UPDATE MENU SETTINGS
if (isset($_POST['submit'])) {
	if(wp_verify_nonce($_REQUEST['w2a_tab_menu_submit_post'], 'w2a_tab_menu')){
		
		//sanitize input fields
		$postData = $_POST['data']; //sanitize_text_field($_POST['data']);
		
		// send the data to save
		$url = 'https://www.web2application.com/w2a/api-process/save_app_menu_settings_from_plugin.php';
		$data = array('api_domain' => $_SERVER['SERVER_NAME'], 'api_key' => trim($w2a_options['w2a_api_key']), 'data' => $postData);
		$json = json_encode($data);
		
		/*$options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query($data),
                    ),
                );
        $context  = stream_context_create($options);
        $html = file_get_contents($url, false, $context);*/

		// init header
		$headers = array("Content-type: application/json");

		// init curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		$response = curl_exec($ch);
		curl_close($ch);
		
		// check if response is not empty
        if ($response != ""){
            echo '<div id="web2app-error-mesage">';
            echo $response;
            echo '</div>';
        }

	} else {
		// display error
		echo '<div id="web2app-error-mesage">';
		echo _e('oops... some thing wrong. Please reload the page and try again', 'web2application');
		echo '</div>';
	}
}

// ADD MENU LINK
if(isset($_POST['submitNewMenu'])) {
	if(wp_verify_nonce($_REQUEST['w2a_add_menu_link_submit_post'], 'w2a_add_menu_link')){
		
		// get selected icon option
		$option     = sanitize_text_field($_POST['w2a_icon_option']);
		$icon       = "";

		if ($option == 1) {
		  	$icon	= sanitize_text_field($_POST['link_icon_fa']);
		} else {
			$icon	= sanitize_text_field($_POST['link_icon_url']);
		}
		
		//sanitize input fields
		$postData 					= $_POST['menu'];
		$postData['link_icon'] 		= $icon;
		
		// send the data to save
		$url = 'https://www.web2application.com/w2a/api-process/save_new_app_menu_from_plugin.php';
		$data = array('api_domain' => $_SERVER['SERVER_NAME'], 'api_key' => trim($w2a_options['w2a_api_key']), 'data' => $postData);
		$json = json_encode($data);
		
		/*$options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query($data),
                    ),
                );
        $context  = stream_context_create($options);
        $html = file_get_contents($url, false, $context);*/

		// init header
		$headers = array("Content-type: application/json");

		// init curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		$response = curl_exec($ch);
		curl_close($ch);
		
		// check if response is not empty
        if ($response != ""){
            echo '<div id="web2app-error-mesage">';
            echo $response;
            echo '</div>';
        }

	} else {
		// display error
		echo '<div id="web2app-error-mesage">';
		echo _e('oops... some thing wrong. Please reload the page and try again', 'web2application');
		echo '</div>';
	}
}

// CHANGE LINK ICON
if(isset($_POST['submitIconChanges'])) {
	if(wp_verify_nonce($_REQUEST['w2a_change_link_icon_submit_post'], 'w2a_change_link_icon')){
		
		// get selected icon option
		$option     = sanitize_text_field($_POST['w2a_icon_option2']);
		$icon       = "";

		if ($option == 1) {
		  	$icon	= sanitize_text_field($_POST['link_icon_fa2']);
		} else {
			$icon	= sanitize_text_field($_POST['link_icon_url2']);
		}
		
		//sanitize input fields
		$postData['link_id'] 	= sanitize_text_field($_POST['link_id']);
		$postData['link_icon'] 	= $icon;
		
		// send the data to save
		$url = 'https://www.web2application.com/w2a/api-process/save_app_menu_icon_from_plugin.php';
		$data = array('api_domain' => $_SERVER['SERVER_NAME'], 'api_key' => trim($w2a_options['w2a_api_key']), 'data' => $postData);
		$json = json_encode($data);
		
		/*$options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query($data),
                    ),
                );
        $context  = stream_context_create($options);
        $html = file_get_contents($url, false, $context);*/

		// init header
		$headers = array("Content-type: application/json");

		// init curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		$response = curl_exec($ch);
		curl_close($ch);
		
		// check if response is not empty
        if ($response != ""){
            echo '<div id="web2app-error-mesage">';
            echo $response;
            echo '</div>';
        }

	} else {
		// display error
		echo '<div id="web2app-error-mesage">';
		echo _e('oops... some thing wrong. Please reload the page and try again', 'web2application');
		echo '</div>';
	}
}

// UPDATE MENU LINK
if(isset($_POST['task3'])) {
	if(wp_verify_nonce($_REQUEST['w2a_update_menu_link_submit_post'], 'w2a_update_menu_link')){
		
		//sanitize input fields
		$postData = $_POST['data'];
		
		// send the data to save
		$url = 'https://www.web2application.com/w2a/api-process/save_app_menu_changes_from_plugin.php';
		$data = array('api_domain' => $_SERVER['SERVER_NAME'], 'api_key' => trim($w2a_options['w2a_api_key']), 'data' => $postData);
		$json = json_encode($data);
		
		/*$options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query($data),
                    ),
                );
        $context  = stream_context_create($options);
        $html = file_get_contents($url, false, $context);*/

		// init header
		$headers = array("Content-type: application/json");

		// init curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		$response = curl_exec($ch);
		curl_close($ch);
		
		// check if response is not empty
        if ($response != ""){
            echo '<div id="web2app-error-mesage">';
            echo $response;
            echo '</div>';
        }
	}
}

// DELETE MENU LINK
if(isset($_POST['task4'])) {
	if(wp_verify_nonce($_REQUEST['w2a_delete_menu_link_submit_post'], 'w2a_delete_menu_link')){
		
		//sanitize input fields
		$postData = sanitize_text_field($_POST['link_id']);
		
		// send the data to save
		$url = 'https://www.web2application.com/w2a/api-process/delete_app_menu_from_plugin.php';
		$data = array('api_domain' => $_SERVER['SERVER_NAME'], 'api_key' => trim($w2a_options['w2a_api_key']), 'link_id' => $postData);
		$json = json_encode($data);
		
		/*$options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query($data),
                    ),
                );
        $context  = stream_context_create($options);
        $html = file_get_contents($url, false, $context);*/

		// init header
		$headers = array("Content-type: application/json");

		// init curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		$response = curl_exec($ch);
		curl_close($ch);
		
		// check if response is not empty
        if ($response != ""){
            echo '<div id="web2app-error-mesage">';
            echo $response;
            echo '</div>';
        }
	}
}

function startsWith ($string, $startString) { 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
} 

// get appId to check api key validity
$url = 'https://www.web2application.com/w2a/api-process/get_app_id.php?api_domain='.$_SERVER['SERVER_NAME'].'&api_key='.trim($w2a_options['w2a_api_key'].'&version=new');
$appId = file_get_contents($url);

// check
if ($appId == "") {
	// init curl
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$appId = curl_exec($ch);
	curl_close($ch);
}


// check
$disabled = ($appId == 'Wrong API. Please Check Your API Key' || trim($w2a_options['w2a_api_key']) == "") ? true : false;

// check
if ($appId != 'Wrong API. Please Check Your API Key' && is_numeric($appId)) {
	// get app premium settings
	$url 		= 'https://www.web2application.com/w2a/api-process/get_app_menus.php?api_domain='.$_SERVER['SERVER_NAME'].'&api_key='.trim($w2a_options['w2a_api_key'].'&version=new');
	$settings 	= file_get_contents($url);
	
	// check
	if ($settings == "") {
		// init curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$settings = curl_exec($ch);
		curl_close($ch);
	}
	
	// decode
	$row 		= json_decode($settings);
	$base_url 	= $row->base_url;
	$app_paied 	= $row->app_paied;
	$menu_count	= count($row->menu);
	$base_url   = ($app_paied != "no" && $menu_count > 0) ?  $row->menu[0]->link_url : $base_url;
	$app_menu_background   = $row->app_menu_background;
	$app_menu_link_color   = $row->app_menu_link_color;
}
?>

<link href="//web2application.com/w2a/user/lib/colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet">
<link href="//web2application.com/w2a/user/lib/fontawesome-picker/css/fontawesome-iconpicker.min.css" rel="stylesheet">
<link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet">
<script src="//web2application.com/w2a/user/lib/fontawesome-picker/js/fontawesome-iconpicker.js"></script>
<script src="//web2application.com/w2a/user/lib/colorpicker/js/bootstrap-colorpicker.js"></script>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<style type="text/css">
div#app-demo-sec {
    margin: 0 auto;
    width: 320px;
    height: 650px;
    background: url(https://web2application.com/w2a/images/mobile-blank-iphone8-plus.png);
    background-size: 100%;
    background-repeat: no-repeat;
}
div#iframe_app_test {
    margin-top: 23%;
    margin-right: 6%;
    margin-left: 6%;
    height: 495px;
	overflow: hidden;
}
#app-demo-sec iframe{
    width: 100%;
    height: 100%;
    border: none;
}
div.scrollmenu {
	margin-right: 6%; 
	margin-left: 6%;
	background-color: <?php echo $app_menu_background; ?>;
	overflow: auto;
	white-space: nowrap;
}
div.scrollmenu a {
	display: inline-block;
	color: <?php echo $app_menu_link_color; ?>;
	text-align: center;
	padding: 5px 14px;
	text-decoration: none;
  font-size: 13px;
}
div.scrollmenu a:hover {
	background-color: #777;
}

.my-section {
    background: #ffffff;
    padding: 10px;
}
.form-control {
    width: 200px;
}
.form-control2 {
    width: 300px;
}
</style>

<div class="wrap">

    <h2><?php _e('Web2Application Tab Menus Link Page', 'web2application'); ?></h2>

    <table>
        <tr>
            <td valign="top">
				<div class="my-section">
    				<h3><?php _e('Menu Managment', 'web2application'); ?></h5>
					<form method="post">
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row"><label><?php _e('App Menu Background','web2application'); ?></label></th>
									<td><input name="data[app_menu_background]" type="text" value="<?php echo ($row->app_menu_background); ?>" class="form-control color-picker" data-default-color="#000000" <?php if ($disabled) { echo "disabled"; } ?> />
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('App Menu Link Color','web2application'); ?></label></th>
									<td><input name="data[app_menu_link_color]" type="text" value="<?php echo ($row->app_menu_link_color); ?>" class="form-control color-picker" data-default-color="#ffffff" <?php if ($disabled) { echo "disabled"; } ?> />
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('App Menu Unselected Link Color','web2application'); ?></label></th>
									<td><input name="data[app_menu_unselected_link_color]" type="text" value="<?php echo ($row->app_menu_unselected_link_color); ?>" class="form-control color-picker" data-default-color="#ff0000" <?php if ($disabled) { echo "disabled"; } ?> />
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('App Menu Direction','web2application'); ?></label></th>
									<td>
										<table>
											<tr>
												<td><label for="w2a_app_menu_is_rtl1"><input type="radio" id="w2a_app_menu_is_rtl1" name="data[app_menu_is_rtl]" value="1" <?php echo ($row->app_menu_is_rtl == 1) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /> <?php _e('Right to Left', 'web2application'); ?></label></td>
												<td><label for="w2a_app_menu_is_rtl0"><input type="radio" id="w2a_app_menu_is_rtl0" name="data[app_menu_is_rtl]" value="0" <?php echo ($row->app_menu_is_rtl == 0) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /> <?php _e('Left to Right', 'web2application'); ?></label></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Enable Push History Tab','web2application'); ?></label></th>
									<td>
										<table>
											<tr>
												<td><label class="checkbox" for="w2a_push_history_tab1"><input type="radio" id="w2a_push_history_tab1" name="data[push_history_tab]" value="1" <?php echo ($row->push_history_tab == 1) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> /> Yes</label></td>
												<td><label class="checkbox" for="w2a_push_history_tab0"><input type="radio" id="w2a_push_history_tab0" name="data[push_history_tab]" value="0" <?php echo ($row->push_history_tab == 0) ? "checked" : ""; ?> <?php if ($disabled) { echo "disabled"; } ?> />No</label></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Push History Title','web2application'); ?></label></th>
									<td><input name="data[push_history_title]" type="text" value="<?php echo ($row->push_history_title); ?>" class="form-control" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
							</tbody>
						</table>
						<?php wp_nonce_field('w2a_tab_menu', 'w2a_tab_menu_submit_post'); ?>
						<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'web2application'); ?>" <?php if ($disabled) { echo "disabled"; } ?> /></p>
					</form>
				</div><br><br>
				<div class="my-section">
    				<h3><?php _e('Add New Menu Link', 'web2application'); ?></h5>
					<form method="post">
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row"><label><?php _e('Link Text','web2application'); ?></label></th>
									<td>
										<input name="menu[link_text]" type="text" class="form-control2" <?php if ($disabled) { echo "disabled"; } ?> />
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Link URL','web2application'); ?></label></th>
									<td>
										<input name="menu[link_url]" type="text" class="form-control2" <?php if ($disabled) { echo "disabled"; } ?> /></label>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Link Icon','web2application'); ?></label></th>
									<td>
										<table>
											<tr>
												<td><label for="w2a_icon_option1"><input type="radio" id="w2a_icon_option1" name="w2a_icon_option" value="1" checked <?php if ($disabled) { echo "disabled"; } ?> onclick="javascript:selectIcon('link_icon_fa');" /> Use fontawesome icon</label></td>
												<td><label for="w2a_icon_option2"><input type="radio" id="w2a_icon_option2" name="w2a_icon_option" value="2" <?php if ($disabled) { echo "disabled"; } ?> onclick="javascript:selectIcon('link_icon_url');" /> Upload custom icon</label></td>
											</tr>
										</table>
										<table>
											<tr>
												<td id="link_icon_fa">
													<input id="link_icon" type="text" class="form-control2" name="link_icon_fa" /><br>
													<label data-title="Select an icon..." data-placement="inline" class="icp icp-auto" data-selected="fa-align-justify">
												</td>
											</tr>
											<tr>
												<td id="link_icon_url" style="display:none;">
													<input id="image-url" type="text" class="form-control2" name="link_icon_url" />
													<input id="w2a-upload-button" type="button" class="button" value="Upload Or Select Image"  />
													<p class="description"><?php _e('Please Select Image Or Paste Full Image Url. example : ', 'web2application'); ?>https://domain.com/image.jpg</p>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Link Order','web2application'); ?></label></th>
									<td><input name="menu[link_order]" type="text" class="form-control2" <?php if ($disabled) { echo "disabled"; } ?> /></td>
								</tr>
								<tr>
									<th scope="row"><label><?php _e('Is Active?','web2application'); ?></label></th>
									<td>
										<select name="menu[link_active]" class="form-control2">
											<option>yes</option>
											<option>no</option>
										</select>
									</td>
								</tr>
							</tbody>
						</table>
						<?php wp_nonce_field('w2a_add_menu_link', 'w2a_add_menu_link_submit_post'); ?>
						<p class="submit">
							<input type="submit" name="submitNewMenu" class="button button-primary" value="<?php _e('Save This New Menu', 'web2application'); ?>" <?php if ($disabled) { echo "disabled"; } ?> />
						</p>
					</form>
				</div>
            </td>
            <td width="35%" valign="top">
                <!-- BEGIN - app demo section -->
                <div id="app-details-sec" style="margin-top: 0; margin-left: 20px; padding: 10px; background: #ffffff;">
                  <div id="demo_screen_text">
                    <p class="description"><?php _e('Demo view only. If you like APK for testing on your device please contact us.', 'web2application'); ?></p>
                  </div>
                  <div id="app-demo-sec" style="border:1px solid transparent;">
                    <div id="iframe_app_test" style="height: <?php echo ($app_paied != "no" && $menu_count > 0) ? "454px" : "498px"; ?>;">
                      <iframe id="preview" src="<?php echo $base_url; ?>" scrolling="yes"></iframe>
                    </div>
                    <?php if ($app_paied != "no" && $menu_count > 0) { ?>
                    <div class="scrollmenu">
                      <?php 
                          // iterate
                          foreach ($row->menu as $item) { 
                              if ($item->link_active == 'yes') {
                      ?>

                        <a href="javascript:changeURL('<?php echo $item->link_url; ?>');">
                          <?php if(startsWith($item->link_icon, "fa")) { ?>
                          <i class="<?php echo $item->link_icon; ?>"> </i>
                          <?php } else { ?>
                          <img src="<?php echo $item->link_icon; ?>" width="16"/>
                          <?php } ?>
                          <br><?php echo strtoupper($item->link_text); ?>
                        </a>

                      <?php }} ?>

                      <!-- add menu for notification -->
                      <?php if ($row->push_history_tab == 1) { ?>
                        <a href="#">
                          <i class="fas fa-history"> </i><br>
                          <?php echo strtoupper($row->push_history_title); ?>
                        </a>
                      <?php } ?>

                    </div>
                    <?php } ?>
                  </div>  
                </div>  
                <!-- END - app demo section -->
            </td>
        </tr>
    </table>
	<br><br>

    <div class="my-section">
    	<h3><?php _e('Tab Menus', 'web2application'); ?></h3>
		<table class="form-table stripe" id="menu-table">
			<thead class="thead-dark">
				<tr>
					<th scope="col" width="150"><?php _e('Link Text', 'web2application'); ?></th>
					<th scope="col" width="200"><?php _e('Link URL', 'web2application'); ?></th>
					<th scope="col"><?php _e('Link Icon', 'web2application'); ?></th>
					<th scope="col" width="150"><?php _e('Link Order', 'web2application'); ?></th>
					<th scope="col" width="100"><?php _e('Status', 'web2application'); ?></th>
					<th scope="col" class="text-center" width="150"><?php _e('Action', 'web2application'); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			// check
			if ($appId != 'Wrong API. Please Check Your API Key' && is_numeric($appId)) {
				// iterate
				foreach ($row->menu as $row) {
					$id = $row->link_id;
			?>

				<tr>
				  <td><span class="<?php echo "text_".$id; ?>"><?php _e($row->link_text, 'web2application'); ?></span>
					<input type="text" class="form-control <?php echo "form_".$id; ?>" id="<?php echo "link_text_".$id; ?>" value="<?php echo $row->link_text; ?>" style="display: none;" />
				  </td>
				  <td><span class="<?php echo "text_".$id; ?>"><?php _e($row->link_url, 'web2application'); ?></span>
					<input type="text" class="form-control <?php echo "form_".$id; ?>" id="<?php echo "link_url_".$id; ?>" value="<?php echo $row->link_url; ?>" style="display: none;  width: 500px;" />
				  </td>
				  <td><span><?php echo $row->link_icon; ?></td>
				  <td>
					<span class="<?php echo "text_".$id; ?>"><?php _e($row->link_order, 'web2application'); ?></span>
					<input type="text" class="form-control <?php echo "form_".$id; ?>" id="<?php echo "link_order_".$id; ?>" value="<?php echo $row->link_order; ?>" style="display: none; width: 100px;" />
				  </td>
				  <td>
					<span class="<?php echo "text_".$id; ?>"><?php _e($row->link_active, 'web2application'); ?></span>
					<select class="form-control select2 <?php echo "form_".$id; ?>" id="<?php echo "link_active_".$id; ?>" style="display: none; width: 100px;">
					  <option value="yes" <?php echo ($row->link_active == "yes") ? "selected" : ""; ?>><?php _e('yes', 'web2application'); ?></option>
					  <option value="no" <?php echo ($row->link_active == "no") ? "selected" : ""; ?>><?php _e('no', 'web2application'); ?></option>
					</select>
				  </td>
				  <td class="text-center">
					<div id="<?php echo "btn_group_1_".$id; ?>">
						<a href="#TB_inline?width=500&height=340&inlineId=myModal2" onClick="javascript:changeIcon('<?php echo $id; ?>');" class="thickbox" title="Change Link Icon"><?php _e('Change', 'web2application'); ?></a> | 
						<a href="javascript:goto('<?php echo $id; ?>', 'Edit');" class="btn btn-sm btn-primary"><?php _e('Edit', 'web2application'); ?></a> | 
						<a href="javascript:goto('<?php echo $id; ?>', 'Delete');" class="btn btn-sm btn-danger"><?php _e('Delete', 'web2application'); ?></a>
					</div>
					<div id="<?php echo "btn_group_2_".$id; ?>" style="display: none;">
						<a href="javascript:goto('<?php echo $id; ?>', 'Save');" class="btn btn-sm btn-success"><?php _e('Save', 'web2application'); ?></a> | 
						<a href="javascript:goto('<?php echo $id; ?>', 'Cancel');" class="btn btn-sm btn-danger"><?php _e('Cancel', 'web2application'); ?></a>
					</div>
				  </td>
				</tr>

			<?php }} ?>
			</tbody>
		</table>
    </div>

	<form method="post" id="updateForm">
		<!-- hidden inputs for update menu -->
		<input type="hidden" name="data[id]" id="data_id" />
		<input type="hidden" name="data[link_text]" id="data_link_text" />
		<input type="hidden" name="data[link_url]" id="data_link_url" />
		<input type="hidden" name="data[link_order]" id="data_link_order" />
		<input type="hidden" name="data[link_active]" id="data_link_active" />
		<input type="hidden" name="task3" value="update_menu_link" />
		<?php wp_nonce_field('w2a_update_menu_link', 'w2a_update_menu_link_submit_post'); ?>
	</form>
	
	<form method="post" id="deleteForm">
		<!-- hidden inputs for update menu -->
		<input type="hidden" name="link_id" id="data_link_id" />
		<input type="hidden" name="task4" value="delete_menu_link" />
		<?php wp_nonce_field('w2a_delete_menu_link', 'w2a_delete_menu_link_submit_post'); ?>
	</form>
</div>

<!-- BEGIN MODAL -->
<div id="myModal2" style="display:none;">
	<form method="post">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label><?php _e('Link Icon','web2application'); ?></label></th>
                    <td>
                        <table>
                            <tr>
                                <td><label for="w2a_icon_option2a"><input type="radio" id="w2a_icon_option2a" name="w2a_icon_option2" value="1" checked <?php if ($disabled) { echo "disabled"; } ?> onclick="javascript:selectIcon2('link_icon_fa2');" /> <?php _e('Use fontawesome icon', 'web2application'); ?></label></td>
                                <td><label for="w2a_icon_option2b"><input type="radio" id="w2a_icon_option2b" name="w2a_icon_option2" value="2" <?php if ($disabled) { echo "disabled"; } ?> onclick="javascript:selectIcon2('link_icon_url2');" /> <?php _e('Upload custom icon', 'web2application'); ?></label></td>
                            </tr>
						</table>
                        <table>
							<tr>
								<td id="link_icon_fa2">
									<input id="link_icon2" type="text" class="form-control2" name="link_icon_fa2" /><br>
									<label data-title="Select an icon..." data-placement="inline" class="icp icp-auto" data-selected="fa-align-justify">
								</td>
							</tr>
							<tr>
								<td id="link_icon_url2" style="display:none;">
									<input id="image-url2" type="text" class="form-control2" name="link_icon_url2" />
									<input id="w2a-upload-button2" type="button" class="button" value="Upload Or Select Image"  />
									<p class="description"><?php _e('Please Select Image Or Paste Full Image Url. example : ', 'web2application'); ?>https://domain.com/image.jpg</p>
								</td>
							</tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php wp_nonce_field('w2a_change_link_icon', 'w2a_change_link_icon_submit_post'); ?>
		<hr>
		<input name="link_id" id="link_id" type="hidden" value="" />
        <p class="submit">
			<input type="submit" name="submitIconChanges" class="button button-primary" value="<?php _e('Save Changes', 'web2application'); ?>" <?php if ($disabled) { echo "disabled"; } ?> />
			<input type="reset" class="button" value="<?php _e('Cancel', 'web2application'); ?>" <?php if ($disabled) { echo "disabled"; } ?> />
		</p>
    </form>
</div>
<!-- END MODAL -->



<script>
	jQuery(document).ready(function($){
		// color picker
		//makes problems
		//$('.color-picker').wpColorPicker();
		
		// datatables
		$('#menu-table').DataTable();
		
		// icon picker
        $('.icp-auto').iconpicker();
        $('.icp').on('iconpickerSelected', function (e) {
            $('#link_icon').val(e.iconpickerValue);
            $('#link_icon2').val(e.iconpickerValue);
        });
		
		// media uploader for add menu
		var w2aMediaUploader;
        $('#w2a-upload-button').click(function(e) {
            e.preventDefault();

            // If the uploader object has already been created, reopen the dialog
            if (w2aMediaUploader) {
                w2aMediaUploader.open();
                return;
            }
            // Extend the wp.media object
            w2aMediaUploader = wp.media.frames.file_frame = wp.media({
              title: 'Choose Image',
              button: {
              text: 'Choose Image'
            }, multiple: false });

            // When a file is selected, grab the URL and set it as the text field's value
            w2aMediaUploader.on('select', function() {
                attachment = w2aMediaUploader.state().get('selection').first().toJSON();
                $('#image-url').val(attachment.url);
            });
            // Open the uploader dialog
            w2aMediaUploader.open();
        });

		// media uploader for change icon
        $('#w2a-upload-button2').click(function(e) {
            e.preventDefault();

            // If the uploader object has already been created, reopen the dialog
            if (w2aMediaUploader) {
                w2aMediaUploader.open();
                return;
            }
            // Extend the wp.media object
            w2aMediaUploader = wp.media.frames.file_frame = wp.media({
              title: 'Choose Image',
              button: {
              text: 'Choose Image'
            }, multiple: false });

            // When a file is selected, grab the URL and set it as the text field's value
            w2aMediaUploader.on('select', function() {
                attachment = w2aMediaUploader.state().get('selection').first().toJSON();
                $('#image-url2').val(attachment.url);
            });
            // Open the uploader dialog
            w2aMediaUploader.open();
        });
	});
	  
	  
	function changeURL(url) {
		$('#preview').attr('src', url);
	}
	
	
	function selectIcon(view) {
		// hide all by default
		document.getElementById('link_icon_fa').style.display = "none";
		document.getElementById('link_icon_url').style.display = "none";

		// show selected view
		document.getElementById(view).style.display = "block";
	}
	function selectIcon2(view) {
		// hide all by default
		document.getElementById('link_icon_fa2').style.display = "none";
		document.getElementById('link_icon_url2').style.display = "none";

		// show selected view
		document.getElementById(view).style.display = "block";
	}

    function changeIcon(id) {
		document.getElementById('link_id').value = id;
    }


    function goto(id, val) {

        if(val == 'Edit') {
			[].forEach.call(document.querySelectorAll('.text_'+id), function (el) {
			  	el.style.display = "none";
			});
			
			[].forEach.call(document.querySelectorAll('.form_'+id), function (el) {
			  	el.style.display = "block";
			});
			
			document.getElementById('btn_group_1_'+id).style.display = "none";
			document.getElementById('btn_group_2_'+id).style.display = "block";

        } else if(val == 'Cancel') {
			[].forEach.call(document.querySelectorAll('.text_'+id), function (el) {
			  	el.style.display = "block";
			});
			
			[].forEach.call(document.querySelectorAll('.form_'+id), function (el) {
			  	el.style.display = "none";
			});
			
			document.getElementById('btn_group_1_'+id).style.display = "block";
			document.getElementById('btn_group_2_'+id).style.display = "none";

        } else if(val == 'Save') {
			
			var e = document.getElementById('link_active_'+id);

			// get values
            var link_text     = document.getElementById('link_text_'+id).value;
            var link_url      = document.getElementById('link_url_'+id).value;
            var link_order    = document.getElementById('link_order_'+id).value;
            var link_active   = e.options[e.selectedIndex].value;

            // add values to form
			document.getElementById('data_id').value = id;
			document.getElementById('data_link_text').value = link_text;
			document.getElementById('data_link_url').value = link_url;
			document.getElementById('data_link_order').value = link_order;
			document.getElementById('data_link_active').value = link_active;

            // submit edit form
            document.getElementById('updateForm').submit();

        } else if(val == 'Delete') {
            var res = confirm("Are you sure you want to delete this menu item?");
            if (res == true) {
                // add values to form
				document.getElementById('data_link_id').value = id;
                // submit delete form
				document.getElementById('deleteForm').submit();
            }
        }
    }

  </script>
