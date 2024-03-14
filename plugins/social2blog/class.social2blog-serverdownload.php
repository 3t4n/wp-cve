<?php
if( !defined( 'ABSPATH' ) ) exit;

require_once 'commons/helpers/class.social2blog-http.php';
require_once 'commons/helpers/class.social2blog-log.php';


class Social2blog_Serverdownload {
	private $apikey;
	private $error_img = "";

	public function __construct() {
		$this->apikey = $this->get_apikey();
	}
	/**
	 * Recupero ApiKey dul DB
	 */
	public static function get_apikey() {
		return get_option('social2blog_apikey');
	}

	/**
	 * controlla se è registrato in remoto
	 */

	public function verifyRemoteRegister() {
		$_url_pages = SOCIAL2BLOG_SERVER_URL."?api_key=" . $this->apikey . "&act=verifyreg";
		$output = Social2blog_Http::requestHttp ( $_url_pages );


		if ($output === "register") {
			return true;
		} else {
			return false;
		}

	}
	/**
	 * Scarica i post dal server
	 */
	public function get_posts() {

	  $social2blog_state = check_sync();

	  if ($social2blog_state == true) {
	  	return "sync_server_required";

	  }



		global $wpdb;
		$social2blog_lastidpost =$wpdb->get_var("
				SELECT MAX(`meta_value`) FROM `".$wpdb->base_prefix."postmeta`
				WHERE
					`meta_key` LIKE 'social2blog_post_id'
					AND
					post_id IN (
						SELECT ID FROM `".$wpdb->base_prefix."posts`
							WHERE `post_status` = 'publish'
					)
				");

		$social2blog_lastidpost = ($social2blog_lastidpost != null) ? $social2blog_lastidpost : "0";

		$graph_url_pages = SOCIAL2BLOG_SERVER_URL."?api_key=" . $this->apikey . "&act=getposts&social2blog_last_id_post=".$social2blog_lastidpost;

		$output = Social2blog_Http::requestHttp ( $graph_url_pages );
		if ( SOCIAL2BLOG_DEBUG ){
			Social2blog_Log::debug("Risposta del server getPost()");
			Social2blog_Log::debug($output);
		}

		$resp = json_decode ( $output );

		$posts = $resp->body->posts;

		$answer = $resp->state;

		if ( $answer == "success"){
			if( empty($posts)){
				return "ok";
			}
		}else{
			return "error";
		}

		foreach ($posts as $body) {
			if ($body->api_key != $this->apikey){
				throw new Social2blog_Exception( __('api_key ERRATO.', 'social2blog-text') );
			}

			$result = $this->createPost ( $body );
			if ($result != 0) {
				return $result;
			}

		}

		if (!empty($this->error_img)){
			return $this->error_img;
		}
		return "ok";
	}

	public function get_events() {

		$social2blog_state = check_sync();
		 if ($social2blog_state == true) {
	  		return "sync_server_required";
	  	}

		global $wpdb;
		$last_social2blog_id_event =$wpdb->get_var("
				SELECT MAX(`meta_value`) FROM `".$wpdb->base_prefix."postmeta`
				WHERE
					`meta_key` LIKE 'social2blog_event_id'
					AND
					post_id IN (
						SELECT ID FROM `".$wpdb->base_prefix."posts`
							WHERE `post_status` = 'publish'
					)
				");

		$last_social2blog_id_event = ($last_social2blog_id_event != null) ? $last_social2blog_id_event : "0";

		$graph_url_pages = SOCIAL2BLOG_SERVER_URL."?api_key=" . $this->apikey . "&act=getevents&social2blog_last_id_event=".$last_social2blog_id_event;

		$output = Social2blog_Http::requestHttp ( $graph_url_pages );
		if ( SOCIAL2BLOG_DEBUG ){
			Social2blog_Log::debug("Risposta server getEvents()");
			Social2blog_Log::debug($output);
		}

		$resp = json_decode ( $output );

		$events = $resp->body->events;
		$answer = $resp->state;

		if ( $answer == "success"){
			if( empty($events)){
				return "ok";
			}
		}else{
			return "error";
		}

		foreach ($events as $body) {

			if ($body->api_key != $this->apikey){
				throw new Social2blog_Exception( __('api_key ERRATO.', 'social2blog-text') );
			}

			$result = $this->createEvents( $body );
			if ($result != "ok") {
				return $result;
			}

		}
		return "ok";
	}

	/**
	 * Nuovo post
	 */
	public function createPost($post_info) {
		if ( SOCIAL2BLOG_DEBUG ){
			Social2blog_Log::debug("--> Creazione post  <--");
			Social2blog_Log::debug($post_info);
		}
		/*
		stdClass Object
			(
			    [_id] => stdClass Object
			        (
			        )

			    [title] => titolo post
			    [content] => testo del post
			    [category] => category (string = a,b,c)
			    [source] => social (facebook/twitter/instagram)
			    [featured] => imm.evidenza (base64img)
			    [featured_name] => nome file
			    [post_id] => XXXXXXXXXXXXX_XXXXXXXXXXX
			    [api_key] => apikey xkoll
			    [social2blog_post_id] => XXXX (int)
			    [retrieve_date] => data post
			    [updated_time] =>
			    [created_time] => aaaa-mm-ggThh:mm:ss+timezone
			)
		 */
		global $wpdb;

		$querystr = "SELECT `ID` FROM `".$wpdb->base_prefix."posts` WHERE `ID` IN (SELECT `post_id` FROM ".$wpdb->base_prefix."postmeta WHERE `meta_value` = '{$post_info->post_id}')";
		$pageposts = $wpdb->get_results($querystr, OBJECT);
		$post_id = $pageposts[0]->ID;

		if ( empty($post_id) ){
			// Se $query->posts è vuoto il post non è stato ancora inserito
			try {
				if( null == get_page_by_title( $post_info->title ) ) {

					$post_table_name = $wpdb->prefix . 'posts';
					$post_status = "draft";
					$post_author_login = 0;
					if ( $post_info->source == "facebook" ) {
						$post_status = Social2blog_Facebook::retriveStatusPost() == 1 ? "publish" :"draft";
						$post_author_login = Social2blog_Facebook::retriveAuthorPost();
					} elseif ( $post_info->source == "twitter" ) {
						$post_status = get_option('social2blog_tw_postStatus') == 1 ? "publish" :"draft";
						$post_author_login = Social2blog_Twitter::retriveAuthorPost();
					} elseif ( $post_info->source =="instagram" ) {
						$post_status = get_option('social2blog__inst_postStatus') == 1 ? "publish" :"draft";
					}
					$user =  get_user_by( "login", $post_author_login );


					//Controllo se è presente un link
					if (!empty($post_info->link)) {
						$post_info->content = $post_info->content."<br />Link: <a href='".$post_info->link."'>".$post_info->link."</a>";
					}

					$my_post = array(
									'post_author'		=>	$user->ID,
									'post_title'		=>	$post_info->title,
									'post_content'		=>	$post_info->content,
									'post_date'			=>	$post_info->created_time,
									'post_status'		=>	$post_status,
									'post_type'		=>	'post');


					// Insert the post into the database
					$lastid = wp_insert_post( $my_post );

					if ($lastid) {

						$check_category = $this->check_category( $post_info->category,  $post_info->source);

						if (!$check_category){
							wp_delete_post($lastid);
							return "ok";
						}
						$categorie = explode( "," , $post_info->category);

						wp_set_object_terms( $lastid, $categorie, 'category' );

						// Aggiungo informazioni sul post nei meta del post
						// add_post_meta( num_articolo, facebook_post_id , xxxxxxxxx);
						add_post_meta( $lastid, "social2blog_".$post_info->source."_post_id", $post_info->post_id );

						// Aggiungo informazioni sul post nei meta del post
						add_post_meta( $lastid, "social2blog_post_id", $post_info->social2blog_post_id );

						// Se c'è l'immagine in evidenza la aggiungo al post
						if (!empty($post_info->featured_name)) {
							$imm_inser = $this->addImageFeatured($lastid, $post_info);
						}

						//aggiungo i tags
						if (!empty($post_info->other_tags)) {
							wp_set_post_tags( $lastid, $post_info->other_tags, true );
						}
						if ($imm_inser != "ok"){
							return "error";
						}
					} else {
						return $post_info->source." ".$post_info->post_id;
					}

				}
			} catch (Exception $e) {
				return $e->getMessage();
			}
		}
		return "ok";
	}

	/**
	 * Nuovo evento
	 */
	public function createEvents($post_info) {
	/*
	 (
    	[_id] => stdClass Object
        (
        )
    [name] => Titolo Evento
    [event_id] => fb_id_event
    [place_name] => Citta evento
    [city] => Citta evento
    [country] => Nazione evento
    [description] => Contenuto evento
    [start_time] => 2016-05-10T13:00:00+0200
    [end_time] => 2016-05-11T16:00:00+0200
    [image] => base64 img
    [social2blog_event_id] => XXXX (int)
	 */

		global $wpdb;
		$querystr = "SELECT ".$wpdb->base_prefix."posts.ID FROM ".$wpdb->base_prefix."posts INNER JOIN ".$wpdb->base_prefix."postmeta ON ( ".$wpdb->base_prefix."posts.ID = ".$wpdb->base_prefix."postmeta.post_id ) WHERE ".$wpdb->base_prefix."postmeta.meta_key = 'facebook_event_id' AND ".$wpdb->base_prefix."postmeta.meta_value = '{$post_info->event_id}' AND (".$wpdb->base_prefix."posts.post_status = 'publish' OR ".$wpdb->base_prefix."posts.post_status = 'draft' OR ".$wpdb->base_prefix."posts.post_status =  'trash') GROUP BY ".$wpdb->base_prefix."posts.ID ORDER BY ".$wpdb->base_prefix."posts.post_date DESC LIMIT 0, 1";
		$pageposts = $wpdb->get_results($querystr, OBJECT);
		$event_id = $pageposts[0]->ID;

		if ( empty($event_id) ){

			$post_author_login = Social2blog_Facebook::retriveAuthorPost();
			$user =  get_user_by( "login", $post_author_login );

			$post_status = Social2blog_Facebook::retriveStatusEvent() == "1" ? "publish" :"draft";

			$event_title = isset($post_info->name) ? $post_info->name : false;

			if ( $event_title === false) {
				return "ok";
			}

			$event_description = isset($post_info->description) ? $post_info->description : " ";

			$event_info = array(
					'post_title'    => $event_title,
					'post_content'  => $event_description,
					'post_status'   => $post_status,
					'post_author'   => $user->ID,
					'post_category' => array()
			);

			$evento = Tribe__Events__API::createEvent($event_info);

			// Aggiungo event_id di facebook per evitare doppioni
			add_post_meta( $evento, 'social2blog_facebook_event_id', $post_info->event_id);

			$venue = isset($post_info->place_name) ? $post_info->place_name : "";
			$state = isset($post_info->country) ? $post_info->country : "";
			$country = isset($post_info->country) ? $post_info->country : "";
			$city = isset($post_info->city) ? $post_info->city : "";
			$zip = isset($post_info->zip) ? $post_info->zip : "";
			$address = isset($post_info->street) ? $post_info->street : "";

			$key_identifier = $venue."_".$state."_".$country."_".$city."_".$zip;

			global $wpdb;
			$querystr = "SELECT ".$wpdb->base_prefix."posts.ID FROM wp_posts INNER JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id ) WHERE wp_postmeta.meta_key = 'location_event_id' AND wp_postmeta.meta_value = '{$key_identifier}' AND wp_posts.post_status = 'publish' GROUP BY wp_posts.ID ORDER BY wp_posts.post_date DESC LIMIT 0, 1";
			$locationposts = $wpdb->get_results($querystr, OBJECT);

			$location_id =  $locationposts[0]->ID;

			if ( empty($location_id) ){

				$event_location = array(
						'Venue'  		=> $venue,
						'State'			=> $state,
						'Country'		=> $country,
						'City'			=> $city,
						'Zip'			=> $zip,
						'Address'		=> $address
				);

				$location_id = Tribe__Events__API::createVenue($event_location);

				add_post_meta( $location_id, 'location_event_id', $key_identifier);
			}

			$event_meta = array();

			$organizer_id = array(
					'OrganizerID'	=> Social2blog_Facebook::retriveOrganizer()
			);

			$location = array(
					'VenueID'		=> $location_id
			);

			if( !empty($organizer_id) ){
				$event_meta += array(
						'Organizer'			=> array($organizer_id)
				);
				$event_meta += array(
						'EventOrganizerID'	=> Social2blog_Facebook::retriveOrganizer()
				);
			}

			if( !empty($location) ){
				$event_meta += array (
						'Venue'				=> $location,
						'EventShowMapLink'	=> "1",
						'EventShowMap'		=> "1"
				);
			}

			$data_event = $this->parseTimeEvent($post_info->start_time, $post_info->end_time);

			$event_meta = $event_meta + $data_event;


			// Immagine
			if (!empty($post_info->image)) {
				$imm_inser = $this->addImageFeatured($evento, $post_info);
			}

			add_post_meta($location_id, 'social2blog_event_id', $post_info->social2blog_event_id);

			Tribe__Events__API::updateEvent($evento, $event_meta);

			if ( $imm_inser != "ok"){
				return "error";
			}
		}
		return "ok";
	}

	/**
	 * Salva la immagine in wordprss
	 * @param unknown $base64img
	 * @param unknown $image_name
	 * @param string $image_type
	 */
	private function saveImage($base64img, $image_name, $image_type = 'image/jpg') {

		$upload_dir       = wp_upload_dir();

		// @new
		$upload_path      = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;


		$base64img = str_replace('data:'.$image_type.';base64,', '', $base64img);
		$base64img = str_replace(' ', '+', $base64img);

		$decoded          = base64_decode( $base64img ) ;

		$filename         = $image_name;
		// Se l'immagine non ha tipo aggiungo .jpg
		if ( !preg_match("/\.(gif|png|jpg)$/", $filename) ){
			$filename += ".jpg";
		}

		$hashed_filename  = md5( $filename . microtime() ) . '_' . $filename;

		// @new
		$image_upload     = file_put_contents( $upload_path . $hashed_filename, $decoded );

		//return the url
		$conturl = content_url();
		$bruc = substr( strrchr( $conturl, '/' ), 1 );

		$fn = $upload_path . $hashed_filename;
		$fn = explode($bruc, $fn);
		$hg = $fn[1];
		$hg = str_replace("\\", "/", $hg);


		$defi = $conturl.$hg;
		if ($image_upload  > 0) {
			return $defi;
		} else {
			$var_x = error_get_last();
			return null;
		}
	}

	/**
	 * Aggiunge una immagine al post
	 */
	private function addImageFeatured($lastid, $post_info){

		$image_type = 'image/jpg';
		$noim = false;
		$controllo_featured_name = isset($post_info->featured_name);
		if ( $controllo_featured_name ){
			$ext_im = explode(".", $post_info->featured_name);

			if (strcasecmp("png", end($ext_im)) == 0) {
				$image_type = 'image/png';
			} else if (strcasecmp("jpg", end($ext_im)) == 0) {
				$image_type = 'image/jpg';

			} else if (strcasecmp("jpeg", end($ext_im)) == 0) {
				$image_type = 'image/jpg';
			} else {
				$noim = true;
			}
		}

		if ($noim != true) {
			$imm 	= 	isset( $post_info->featured ) ? $post_info->featured : $post_info->image;
			$name_imm =	isset( $post_info->featured_name) ? $post_info->featured_name : "img_event_".$post_info->event_id.".jpg";

			$res_img = $this->saveImage($imm, $name_imm, $image_type);
		}

		if ( !empty($res_img) ) {
			if (trim($res_img) != "") {
				$wp_filetype = wp_check_filetype($res_img, null);
				$attachment = array(
						'guid'           => $res_img,
						'post_mime_type' => $wp_filetype['type'],
						'post_title'     => preg_replace( '/\.[^.]+$/', '', $res_img ),
						'post_content'   => '',
						'post_status'    => 'inherit'
				);

				$upload_dir       = wp_upload_dir();
				$upload_path      = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;
				$path_file = $upload_path . basename($res_img);

				$attach_id = wp_insert_attachment( $attachment, $path_file, $lastid );

				require_once(ABSPATH . 'wp-admin/includes/image.php');
				$attach_data = wp_generate_attachment_metadata( $attach_id, $path_file );
				wp_update_attachment_metadata( $attach_id, $attach_data );

				// add featured image to post
				//add_post_meta($lastid, '_thumbnail_id', $attach_id);
				set_post_thumbnail( $lastid, $attach_id );
			}
		}else{
			if ($noim != true) {
				$this->error_img = "error_no_imm";
				wp_delete_post($lastid);
				return $this->error_img;
				//die();
			}
		}
		return "ok";
	}
	/**
	 *
	 * @param $start	Data di inizio	( es: Y-m-gTH:m:s+TimeZone )
	 * @param $end		Data fine 		( default vuota )
	 * @return Array
	 */
	private function parseTimeEvent($startTime, $endTime = null){

		if ( !isset($startTime) || $startTime == ""){
			return array( null );
		}

		$date = new DateTime($startTime);
// 		$info_data = explode( "T", $startTime);
// 		$day = $info_data[0];
// 		$hour = explode( "+", $info_data[1])[0];
		$st = explode( "+" , $startTime);
		$date_notm_start = $st[0];
		list($giorno_start, $orario_start) = explode( "T" , $date_notm_start);
		list($hour_start, $minute_start, $second_start) = explode(":", $orario_start);

		$timezone = $date->getTimezone();

		if( isset($endTime) && !empty($endTime) ){
			$date = new DateTime($endTime);
			$av = explode( "+" , $endTime);
			$date_notm_end = $av[0];
			list($giorno_end, $orario_end) = explode( "T" , $date_notm_end);
			list($hour_end, $minute_end, $second_end) = explode(":", $orario_end);
		}

		$result = array(
				'EventStartDate'	=>	isset( $giorno_start ) ? $giorno_start : "",
				'EventStartHour'	=>	isset( $hour_start ) ? $hour_start : "",
				'EventStartMinute'	=>	isset( $minute_start ) ? $minute_start : "",
				'EventEndDate'		=>	isset( $giorno_end) ? $giorno_end : "",
				'EventEndHour'		=>	isset( $hour_end ) ? $hour_end : "",
				'EventEndMinute'	=>	isset( $minute_end ) ? $minute_end : "",
				'EventTimezone'		=>	isset( $timezone ) ? $timezone->timezone : ""
		);

		return $result;

	}

	/**
	 * Manda tutte le informazioni del plugin al server
	 */
	public function updateServerInfo(){
		global $wpdb;
		$querystr = "SELECT `option_name`,`option_value` FROM `".$wpdb->base_prefix."options` WHERE `option_name` LIKE 'social2blog%'";
		$social2blog_info_db = $wpdb->get_results($querystr, OBJECT);

		$option_map =  array(
	    	"social2blog_tw_title_count"	=> "tw_title_count",
			"social2blog_tw_user_name"	=> "tw_user_name",
			"social2blog_tw_user_id"		=> "tw_user_id",
			"social2blog_tw_post_foto"	=> "tw_post_foto",
			"social2blog_tw_oauth_token" 	=> "tw_oauth_token",
			"social2blog_tw_oauth_secret"	=> "tw_oauth_secret",
			"social2blog_tw_tags" 		=> "tw_tags",
			"social2blog_fb_id_page" 		=> "id_page",
			"social2blog_fb_tags" 		=> "tags",
			"social2blog_fb_title_count" 	=> "title_count",
			"social2blog_fb_access_token"	=> "page_access_token",
			"social2blog_fb_event" 		=> "fb_events",
			"social2blog_fb_post" 		=> "fb_post"
		);

		$info_data = array();
		for($i = 0; $i < count($social2blog_info_db); $i++){
			if ( array_key_exists($social2blog_info_db[$i]->option_name,$option_map) ){
				$info_data += array(
					$option_map[$social2blog_info_db[$i]->option_name] => $social2blog_info_db[$i]->option_value
				);
			}
		}

		$fcvg = array("tw_tags", "tags");
		for ($i = 0; $i < count($fcvg); $i++) {
			if (!empty($info_data[$fcvg[$i]])) {
				$info_data[$fcvg[$i]] = explode(" ", $info_data[$fcvg[$i]]);
			}
		}

		$dataCard = json_encode($info_data);

		if ( SOCIAL2BLOG_DEBUG ){
			Social2blog_Log::debug("--> XKOLL CARD <--");
			Social2blog_Log::debug($dataCard);
		}

		$apikey = get_option('social2blog_apikey');
		$graph_url_pages = SOCIAL2BLOG_SERVER_URL."?api_key=".$apikey."&act=updatedata&xk_data=".urlencode($dataCard);

		$output = Social2blog_Http::requestHttp($graph_url_pages);

		$stateJ = json_decode($output);

		if ( SOCIAL2BLOG_DEBUG ){
			Social2blog_Log::debug("--> XKOLL SERVER ANSWER <--");
			Social2blog_Log::debug($stateJ);
		}

		$state = $stateJ->state;

		if( $state === "success" and $stateJ->body->api_key === $this->apikey ){
			return "ok";
		} elseif( $state == "fail" ){
			$error = $stateJ -> message;
			if ($error == "api key not found"){
				return "apikey_errata";
			} else {
				return "error";
			}

		}else{
			return "error";
		}
	}

	private function check_category($cat , $social){

		if( empty( $cat ) ){
			return false;
		}

		$categorie = explode( "," , $cat);
		for ($i = 0 ; $i < count($categorie) ; $i++){
			$cat_exist = get_category_by_slug( $categorie[$i]);
			// Controllo se la categoria in ingresso esiste
			if( $cat_exist == false ){
				social2blog_setstate("1");
				return false;
			}
		}

		if ($social === "facebook"){
			$tag = Social2blog_Facebook::getTags();
			$tags = str_replace("#", "", $tag);
			$array_tag = explode(" ", $tags);

			for($i=0; $i < count($categorie); $i++){
				if( ! in_array($categorie[$i], $array_tag)){
					social2blog_setstate("1");
					return false;
				}
			}
			return true;
		}elseif ($social === "twitter"){
			$tag = Social2blog_Twitter::getTags();
			$tags = str_replace("#", "", $tag);
			$array_tag = explode(" ", $tags);

			for($i=0; $i < count($categorie); $i++){
				if( ! in_array($categorie[$i], $array_tag)){
					social2blog_setstate("1");
					return false;
				}
			}
			return true;
		}else {
			social2blog_setstate("1");
			return false;
		}

	}

}
