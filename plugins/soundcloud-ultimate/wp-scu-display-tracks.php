<?php
require_once 'includes/Services/Soundcloud.php';

/*This class takes care of the tracks display */
if(!class_exists('WP_SCU_List_Table')){
	include_once(WPSHQ_SCU_PLUGIN_PATH.'/includes/wpscu-list-table.php');  
}

class SoundCloud_Tracks_Display extends WP_SCU_List_Table {
    
    function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'track',     //singular name of the listed records
            'plural'    => 'tracks',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }

    function column_default($item, $column_name){
    	return $item[$column_name];
    }
        
    function column_title($item){
        
        //Build row actions
        $actions = array(
             'delete'    => sprintf('<a href="admin.php?page=wpshq_scu_plugin_options&tab=scu_display_page&action=delete&id='.$item['id'].'&track=%s" onclick="return confirm(\'Are you sure you want to delete this track from your SoundCloud account?\')">Delete</a>',$item['title']),
        );
        
        //Return the title contents
        return sprintf('%1$s <span style="color:silver"></span>%2$s',
            /*$1%s*/ $item['title'],
            /*$2%s*/ $this->row_actions($actions)
        );
    }

    function column_permalink_url($item){
        //Build row actions
    	if ($item['sharing'] == 'private'){
    		$track_url = $item['secret_uri'];
	        $actions = array(
	             'view' => sprintf('<a id="scu_track_preview" href="%s" target="_blank">Preview</a>',$track_url),
	        );
        }else{
        	$track_url = $item['permalink_url'];
	        $actions = array(
	             'view' => sprintf('<a id="scu_track_preview" href="%s" target="_blank">Preview</a>',$track_url),
	        );
        }
        
        //Return the title contents
        return sprintf('%1$s <span style="color:silver"></span>%2$s',
            /*$1%s*/ $track_url,
            /*$2%s*/ $this->row_actions($actions)
        );
    }
    
//    function column_cb($item){
//        return sprintf(
//            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
//            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label
//            /*$2%s*/ $item['id']                //The value of the checkbox should be the record's id
//       );
//    }
    
    function get_columns(){
        $columns = array(
           // 'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            //'id' => 'ID',
        	'title' => 'Title',
        	'description' => 'Description',
        	'permalink_url' => 'Track URL',
        	'sharing' => 'Sharing',
            'playback_count' => 'Playback Count'
        );
        return $columns;
    }
    
    function get_sortable_columns() {
        $sortable_columns = array(
            //'id' => array('id',false),
        	'title' => array('title',false),
        	'permalink_url' => array('permalink_url',false),
        	'sharing' => array('sharing',false),
        	'playback_count' => array('playback_count',false),
            'description' => array('description',false)
        );
        return $sortable_columns;
    }
    
    function process_delete_action() {
    	$track = isset($_GET['track'])?$_GET['track']:'';
    	$track_id = isset($_GET['id'])?$_GET['id']:'';
		if('delete'===$this->current_action()) {
			//check if the individual delete link was clicked
			if ($_GET['track']!= ''){
			//get soundcloud options
			$sc_options = get_option('soundcloud_settings');
			if ($sc_options) {
				$sc_id = $sc_options['sc_client_id'];
				$sc_secret = $sc_options['sc_client_secret']; 
				$sc_token = $sc_options['sc_client_access_token'];
				$sc_redirect_uri = $sc_options['sc_redirect_uri'];
			}
			
			$soundcloud = new Services_Soundcloud($sc_id, $sc_secret, $sc_redirect_uri);
			if ($sc_token) {
				$soundcloud->setAccessToken($sc_token);
			} else {
				//TODO display an error stating that the user needs to authenticate first
				return 0;
			}
			
			try {
			    $response = $soundcloud->delete('tracks/'.$track_id);
			    return 1;
			} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
				exit($e->getMessage());
			}
	        	echo '<div id="message" class="updated fade"><p>The track "'.$track.'" was deleted from your SoundCloud account.</p></div>';
        	}
        }       
    }
    
    function prepare_items($data) {
        /**
         * First, lets decide how many records per page to show
         */
        $per_page = 20;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);
        
        $delete_action = $this->process_delete_action();
    	if ($delete_action) {
    		//if we performed a delete then we will need to refresh the $data variable to reflect the change
			$data = scu_get_tracks();    		
    	}
		//$screen = get_current_screen();

	/* -- Ordering parameters -- */
	    //Parameters that are going to be used to order the result
	    $orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'id';
	    $order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : 'ASC';

	    //let's first retrieve all the tracks in the soundcloud account
	    //$data = scu_get_tracks();
	    	    
        $current_page = $this->get_pagenum();
        $total_items = count($data);
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        $this->items = $data;
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }
    
}

/***************************** RENDER ADMIN PAGE ********************************
 * The following function renders the receipts table page
 ********************************************************************************/
function renderTracksList(){
//	   	if(isset($_REQUEST['deleted'])){
//	    	echo '<div id="message" class="updated fade"><p><strong>';
//		    echo stripslashes($_REQUEST['msg']);
//		    echo '</strong></p></div>';
//   	}
	$track_url = '';
	$sc_track_title = '';
	$sc_track_desc = '';
	$sc_genre = '';
	$sc_visibility = '';
	$sc_comments = '';
	
		if (isset($_POST['upload_to_soundcloud'])){
			$errors = '';
			$att_id = '';
			if ( isset( $_POST[ 'track-url' ]) && !empty( $_POST[ 'track-url' ]) ) {
				$file_type = wp_check_filetype($_POST['track-url']);
				if ($file_type['type'] == 'audio/mpeg') {
					$track_url = $_POST[ 'track-url' ];
					$att_id = wp_scu_get_file_id($_POST['track-url']);
				} else {
					$errors .= 'The file you are attempting to upload to SoundCloud is not an audio file. Please select an audio file and try again.<br />';
				}
			}
			
			if ($_POST['sc-track-title'] != "") {
				$sc_track_title = filter_var($_POST['sc-track-title'], FILTER_SANITIZE_STRING);
				if ($_POST['sc-track-title'] == "") {
					$errors .= 'Please enter the Track Title.<br/>';
				}
			} else {
				$errors .= 'Please enter the Track Title.<br/>';
			}
			
			if ($_POST['sc-track-desc'] != "") {
				$sc_track_desc = filter_var($_POST['sc-track-desc'], FILTER_SANITIZE_STRING);
				if ($_POST['sc-track-desc'] == "") {
					$errors .= 'Please enter the Track Description.<br/>';
				}
			} else {
				$errors .= 'Please enter the Track Description.<br/>';
			}
			
			if ($_POST['sc-genre'] != "") {
				$sc_genre = filter_var($_POST['sc-genre'], FILTER_SANITIZE_STRING);
				if ($_POST['sc-genre'] == "") {
					$errors .= 'Please enter the Track Genre.<br/>';
				}
			} else {
				$errors .= 'Please enter the Track Genre.<br/>';
			}
			
			if ($_POST['sc-visibility'] != "") {
				$sc_visibility = filter_var($_POST['sc-visibility'], FILTER_SANITIZE_STRING);
			}
			
			if ($_POST['sc-comments'] != "") {
				$sc_comments = filter_var($_POST['sc-comments'], FILTER_SANITIZE_STRING);
			}
	
			if (!$errors)
			{
				$attached_file = get_attached_file( $att_id );
				$track_info = array('title' => $sc_track_title,
									'desc' => $sc_track_desc,
									'genre' => $sc_genre,
									'visibility' => $sc_visibility,
									'commentable' => $sc_comments);
				
				//now perform the SoundCloud API upload of track
				if (wp_scu_upload_track($attached_file, $track_info)) {
					echo '<div id="message" class="updated fade">';
					echo '<p>Your track is now uploading to SoundCloud. This may take up to a minute or more to complete.</p>';
					echo '</div>';
				} else {
					echo '<div class="error fade">';
					echo '<p>It appears that you are not connected to SoundCloud. <br />Please enter and save the appropriate configuration in the "Settings" tab and then click the "Connect To SoundCloud" link.</p>';
					echo '</div>';
				}
				//clear form values after upload
				$track_url = '';
				$sc_track_title = '';
				$sc_track_desc = '';
				$sc_genre = '';
				$sc_visibility = 'public';
				$sc_comments = 'true';
			}
			else
			{
				echo '<div class="error fade"><br />' . $errors . '<br/></div>';
			}
		}
?>
<h2>View, Preview, Add and Delete Tracks From You SoundCloud Account</h2>
<div style="border-bottom:1px solid #dedede;height:10px"></div>
<div id="poststuff"><div id="post-body">
<div class="postbox">
<h3><label for="title">Upload Tracks To Your SoundCloud Account</label></h3>
<div class="inside">
<form action="admin.php?page=wpshq_scu_plugin_options&tab=scu_display_page" method="post" enctype="multipart/form-data">
<table class="form-table">
	<tr valign="top">
		<th scope="row"><label for="SCUTrackURL">Select SoundCloud upload file</label>
		</th>
		<td>
		<input type="text" id="upload_track" size="50" name="track-url" value="<?php echo $track_url; ?>" />
		<input id="upload_track_button" type="button" value="Select A Track" class="button-secondary" /><br />
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="SCTitle"> Enter Track Title:</label>
		</th>
		<td><input type="text" size="40" name="sc-track-title" value="<?php echo $sc_track_title; ?>" /></td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="SCDesc"> Enter Track Description:</label>
		</th>
		<td><input type="text" size="40" name="sc-track-desc" value="<?php echo $sc_track_desc; ?>" /></td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="SCGenre"> Enter Genre:</label>
		</th>
		<td><input type="text" size="40" name="sc-genre" value="<?php echo $sc_genre; ?>" /></td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="SCVisibility"> Track Visibility:</label></th>
		<td>
		<select name="sc-visibility">
		<option value="public" <?php if ($sc_visibility == 'public') echo 'selected="selected"'; ?>> public </option>
		<option value="private" <?php if ($sc_visibility == 'private') echo 'selected="selected"'; ?>> private </option>
		</select>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="SCComments"> Allow Comments:</label></th>
		<td>
		<select name="sc-comments">
		<option value="true" <?php if ($sc_comments == 'true') echo 'selected="selected"'; ?>> true </option>
		<option value="false" <?php if ($sc_comments == 'false') echo 'selected="selected"'; ?>> false </option>
		</select>
		</td>
	</tr>
</table>
<br />
<input type="submit" class="button-primary" name="upload_to_soundcloud" value="Upload To SoundCloud"/>
</form>
</div></div>
</div></div>
<h2>Your SoundCloud Tracks</h2>
<?php
	$track_action = isset($_GET['action'])?$_GET['action']:'';
	$track_data = get_option('soundcloud_track_data');
	if ($track_data && ($track_action != 'delete')) {
		$playlists = unserialize($track_data['playlists']);
		$tracks = unserialize($track_data['tracks']);		
	} else{
		$playlists = scu_get_playlists();
		$tracks = scu_get_tracks();
		//now let's store tracks and playlist data
		$options = array(
					'tracks' => serialize($tracks),
					'playlists' => serialize($playlists)
				);
		update_option('soundcloud_track_data', $options); //store the results in WP options table
	}
	$track_filter = isset($_POST['sc-track-filter'])?$_POST['sc-track-filter']:'';
	
?>	
	<form action="admin.php?page=wpshq_scu_plugin_options&tab=scu_display_page" method="post">
	<label>Filter Your Tracks: </label><select name="sc-track-filter">
	<option value="all_tracks" <?php if ($track_filter == 'all_tracks') echo 'selected="selected"'; ?>> All Tracks</option>
<?php
	if (!empty($playlists)){
		foreach ($playlists as $set){
			$drop_down_option = '<option value="'.$set['permalink'].'"';
			if ($track_filter == $set['permalink']) {
				$drop_down_option .= 'selected="selected">Playlist: '.$set['permalink'].'</option>';
			} else {
				$drop_down_option .= '>Playlist: '.$set['permalink'].'</option>';				
			}
			echo $drop_down_option;
		}
	}
?>
	</select>
	<span>&nbsp;<input name="soundcloud_refresh_table" type="submit" value="Refresh Data" class="button-primary" /></span>
	</form>
<?php
	//Create an instance of our package class...
    $tracksTableList = new SoundCloud_Tracks_Display();

    if (isset($_POST['soundcloud_refresh_table'])) {
    	//refresh the data
		$playlists = scu_get_playlists();
		$tracks = scu_get_tracks();
		//now let's store tracks and playlist data
		$options = array(
					'tracks' => serialize($tracks),
					'playlists' => serialize($playlists)
				);
		update_option('soundcloud_track_data', $options); //store the results in WP options table
		$track_data = get_option('soundcloud_track_data');
    	if (isset($_POST['sc-track-filter']) && $_POST['sc-track-filter'] == "all_tracks") {
    		$display_table_msg = '<br /><div class="scu_custom_blue_box">Displaying Results For All SoundCloud Tracks In Your Account</div>';
    		echo $display_table_msg;
    		//Fetch, prepare, sort, and filter our data...
		    $tracksTableList->prepare_items($tracks);
		} else if ($_POST['sc-track-filter'] != "") {
			//this means we have selected a playlist
			$selected_playlist_name = $_POST['sc-track-filter'];
			$selected_playlist_data = array();
			foreach ($playlists as $key => $val) {
		       if ($val['permalink'] === $selected_playlist_name) {
		       		$selected_playlist_data = $playlists[$key];
		       		break;
		       }
	   		}
	   		
			if (empty($selected_playlist_data)){
				$tracksTableList->prepare_items($selected_playlist_data); //display nothing
			}else {
				//Fetch, prepare, sort, and filter our data...
			    $tracksTableList->prepare_items($selected_playlist_data['tracks']); //display the tracks for this playlist
			}
		    if (isset($_POST['sc-track-filter']) && $_POST['sc-track-filter'] != 'all_tracks') {
				$display_table_msg = '<br /><div class="scu_custom_blue_box">Displaying Results For Playlist:<br /><br /><strong>'.$selected_playlist_data['permalink_url'].'</strong></div>';
				echo $display_table_msg;
			}
		}
    } else {
			$display_table_msg = '<br /><div class="scu_custom_blue_box">Displaying Results For All SoundCloud Tracks In Your Account</div>';
			echo $display_table_msg;
			//Fetch, prepare, sort, and filter our data...
		    $tracksTableList->prepare_items($tracks);
    }
?>        		
        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
	<form id="tables-filter" method="get" onSubmit="return confirm('Are you sure you want to perform this bulk operation on the selected entries?');">
		<!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
        <input type="hidden" name="tab" value="<?php echo $_REQUEST['tab']; ?>" />
        <!-- Now we can render the completed list table -->
       	<?php $tracksTableList->display(); ?>
    </form>
<?php
}

function scu_set_access_token($sc_options){
	//$params = array('client_id' => $sc_id);
	$soundcloud = new Services_Soundcloud($sc_options['sc_client_id'], $sc_options['sc_client_secret'], $sc_options['sc_redirect_uri']);					
	$soundcloud->setAccessToken($sc_options['sc_client_access_token']);
	return $soundcloud;
}

function scu_get_tracks() {
	//get soundcloud options
	$sc_options = get_option('soundcloud_settings');
	if ($sc_options) {
		$sc_id = $sc_options['sc_client_id'];
		$sc_secret = $sc_options['sc_client_secret']; 
		$sc_token = $sc_options['sc_client_access_token'];
		$sc_redirect_uri = $sc_options['sc_redirect_uri'];
	}
	$data = array();
	
	//let's see if the token is set - if not return empty array
	if (!$sc_token) {
		return $data;
	}
//	$params = array('client_id' => $sc_id);
//	$soundcloud = new Services_Soundcloud($sc_id, $sc_secret, $sc_redirect_uri);					
//	$soundcloud->setAccessToken($sc_token);
	$soundcloud = scu_set_access_token($sc_options);
	try {
		$tracks = $soundcloud->get('me/tracks'); //get all tracks uploaded by me
	} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
		//TODO: fix this up to check what kind of error and display message
		$error_code = $e->getHttpCode();
		return $data; //for now if there is a problem return empty array
		//exit($e->getMessage());
	}
	
	$data = json_decode($tracks, true); //decode json data into array
	return $data;
}

function scu_get_playlists() {
	//get soundcloud options
	$sc_options = get_option('soundcloud_settings');
	if ($sc_options) {
		$sc_id = $sc_options['sc_client_id'];
		$sc_secret = $sc_options['sc_client_secret']; 
		$sc_token = $sc_options['sc_client_access_token'];
		$sc_redirect_uri = $sc_options['sc_redirect_uri'];
	}
	$data = array();
	
	//let's see if the token is set - if not return empty
	if (!$sc_token) {
		return $data;
	}
	
	$soundcloud = scu_set_access_token($sc_options);
	try {
		$playlists = $soundcloud->get('me/playlists'); //get all sets owned by me
	} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
		//TODO: fix this up to check what kind of error and display message
		$error_code = $e->getHttpCode();
		return $data; //for now if there is a problem return empty array
		//exit($e->getMessage());
	}
	
	//$tracks = $soundcloud->get('users/66660666/tracks'); //if you know userid you can get tracks
	$data = json_decode($playlists, true); //decode json data into array
	return $data;
}

function wp_scu_get_file_id($file_url) {
// retrieves the attachment ID from the file URL
	global $wpdb;
	$prefix = $wpdb->prefix;
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM " . $prefix . "posts" . " WHERE guid='%s';", $file_url )); 
	return $attachment[0]; 
}

function wp_scu_upload_track($file_url, $track_info) {
	//get soundcloud options
	$sc_options = get_option('soundcloud_settings');
	if(!$sc_options || $sc_options['sc_client_id'] == '' || $sc_options['sc_client_secret'] == '' || $sc_options['sc_client_access_token'] == ''){
		//no settings config has been saved so lets exit
		return false;
	}else if ($sc_options) {
		$sc_id = $sc_options['sc_client_id'];
		$sc_secret = $sc_options['sc_client_secret']; 
		$sc_token = $sc_options['sc_client_access_token'];
		$sc_redirect_uri = $sc_options['sc_redirect_uri'];
	} 
	
	$soundcloud = new Services_Soundcloud($sc_id, $sc_secret, $sc_redirect_uri);
	$soundcloud->setAccessToken($sc_token);
	
	//$attached_file = get_option('soundcloud_file_path');
	//curl_setopt($ch, CURLOPT_CAINFO, 'C:/absolute/path/to/cacert.pem');
	$track = array(
	    'track[title]' => $track_info['title'],
	    'track[description]' => $track_info['desc'],
		'track[genre]' => $track_info['genre'],
		'track[visibility]' => $track_info['visibility'],
		'track[commentable]' => $track_info['commentable'],
	    'track[asset_data]' => "@".$file_url
	);
	
	try {
	    $response = $soundcloud->post('tracks', $track);
	    //now delete the file from the WP system
	    //wp_delete_attachment( $attached_file );
	} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
		exit($e->getMessage());
	}
	return true;	
}
?>