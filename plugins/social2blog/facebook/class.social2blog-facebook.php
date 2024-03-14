<?php
if( !defined( 'ABSPATH' ) ) { exit; };

class Social2blog_Facebook {

	private $access_token;
	private $access_token_expire;
	private $page_id;
	private $page_name;
	private $status_post;
	private $apikey;
	private $title_count;
	private $author;
	private $posts_fb;
	private $events_fb;
	private $status_event;
	private $organiz_event;


	/**
	 * @return mixed
	 */
	public function getAccess_token_expire() {
		return $this->access_token_expire;
	}

	/**
	 * @param mixed $access_token_expire
	 */
	public function setAccess_token_expire( $access_token_expire ) {
		$this->access_token_expire = $access_token_expire;
	}

	public function getAccess_token(){
		return $this->access_token;
	}

	public function setAccess_token($access_token){
		$this->access_token = $access_token;
	}

	public function getPage_id(){
		return $this->page_id;
	}

	public function setPage_id($page_id){
		$this->page_id = $page_id;
	}

	public function getPage_name(){
		return $this->page_name;
	}

	public function setPage_name($page_name){
		$this->page_name = $page_name;
	}

	/**
	 * Status Post
	 * 0 = Bozza [default]
	 * 1 = Pubblica
	 */

	public function getStatus_post(){
		return $this->status_post;
	}

	public function setStatus_post($status_post){
		$this->status_post = $status_post;
	}

	public function getApikey(){
		return $this->apikey;
	}

	public function setApikey($apikey){
		$this->apikey = $apikey;
	}

	public function getTitle_count(){
		return $this->title_count;
	}

	public function setTitle_count($title_count){
		$this->title_count = $title_count;
	}

	public function getAuthor(){
		return $this->author;
	}

	public function setAuthor($author){
		$this->author = $author;
	}

	public function getStatus_event(){
		if ($this->status_event == 1) {
			$this->status_event = "on";
		}
		return $this->status_event;
	}

	public function setStatus_event($status_event){
		$this->status_event = $status_event;
	}

	public function getOrganiz_event(){
		return $this->organiz_event;
	}

	public function setOrganiz_event($organiz_event){
		$this->organiz_event = $organiz_event;
	}

	/**
	 *
	 * @param $access_token 
	 *        	facebook
	 */
	public function __construct() {
		/*
		 * Costruttore oggetto loginFB
		 */

		$access_token = $this->retrieveAccessToken();
		$this->setAccess_token($access_token);
		
		$access_token_expire = $this->retrieveAccessTokenExpire();
		$this->setAccess_token_expire($access_token_expire);

		$page_id = $this->retrieveIdPage();
		$this->setPage_id($page_id);

		$apikey = $this->get_apikey();
		$this->setApikey($apikey);

		$status_post = $this->retriveStatusPost();
		$this->setStatus_post($status_post);

		$title_count = $this->retriveTitleCount();
		$this->setTitle_count($title_count);

		$author = $this->retriveAuthorPost();
		$this->setAuthor($author);


		$status_event = $this->retriveStatusEvent();
		$this->setStatus_event($status_event);

		$_event = $this->retriveEvent();
		$this->setEvent($_event);


		$organiz_event = $this->retriveOrganizer();
		$this->setOrganiz_event($organiz_event);

		$thepost = Social2blog_Facebook::retrivePost();
		$this->setPost($thepost);

	}

	/**
	 * Recupero ApiKey dul DB
	 */
	public function get_apikey(){
		return get_option('social2blog_apikey');
	}


	/**
	 * Prende i tag dal DB
	 */
	public static function getTags() {
		$tags = get_option('social2blog_fb_tags');
		if (!empty($tags)) {
			return strtolower($tags);
		}
		return $tags;
	}

	/**
	 * Prende gli organizzatori
	 */
	public function getOrganizersEvents(){
		$args = array(
   		'post_type' => 'tribe_organizer'
		);

		return get_posts($args);
	}

	/**
	 * Prende in ingresso una stringa di tag e la salva sul db
	 * es $tags = "tag1,tag2,tag3"
	 */
	public static function saveTags($tags) {
		if (!empty($tags)) {
			update_option('social2blog_fb_tags', strtolower($tags));
		}

	}

	/**
	 * Rimuove i tag salvati sul db
	 */
	public function removeTags(){
		delete_option('social2blog_fb_tags');
	}

	/**
	 * Status Post
	 * 0 = Bozza [default]
	 * 1 = Pubblica
	 */
	public function saveStatusPost($stPost) {
		update_option('social2blog_fb_postStatus', $stPost);
	}

	/**
	 * Status Post
	 * 0 = Bozza [default]
	 * 1 = Pubblica
	 */
	public static function retriveStatusPost() {
		return get_option('social2blog_fb_postStatus');

	}



	/**
	 * Rimuove lo StatusPost
	 */
	public function removeStatusPost(){
		delete_option('social2blog_fb_postStatus');
	}

	/**
	 * Post
	 * on = si
	 * off = no [default]
	 */
	public function savePost($stPost) {
		update_option('social2blog_fb_post', $stPost);
	}

	/**
	 * rimuove il save Post
	 */
	public function removeSavePost() {
		delete_option('social2blog_fb_post');
	}

	/**
	 * Event
	 * on = si
	 * off = no [default]
	 */
	public function saveEvent($stEvent) {
		update_option('social2blog_fb_event', $stEvent);
	}

	/**
	 * Status Post
	 * on = si
	 * off = no [default]
	 */
	public static function retrivePost() {
		return  get_option('social2blog_fb_post');

	}

	/**
	 * Status Event
	 * on = si
	 * off = no [default]
	 */
	public static function retriveEvent() {
		return get_option('social2blog_fb_event');
	}

	/**
	 * Ottiene il post
	 */
	public function getPost() {
		return $this->posts_fb;
	}

	public function setPost($post) {
		$this->posts_fb = $post;
	}

	/**
	 * Ottiene l'evento
	 */
	public function getEvent() {
		return $this->events_fb;
	}

	public function setEvent($events) {
		$this->events_fb = $events;
	}

	/**
	 * Rimuove l'Event
	 */
	public function removeEvent(){
		delete_option('social2blog_fb_event');
	}

	/**
	 * Status event
	 * 0 = Bozza [default]
	 * 1 = Pubblica
	 */
	public function saveStatusEvent($stEvent) {
		update_option('social2blog_fb_EventStatus', $stEvent);
	}

	/**
	 * Status Event
	 * 0 = Bozza [default]
	 * 1 = Pubblica
	 */
	public static function retriveStatusEvent() {
		return get_option('social2blog_fb_EventStatus');

	}

	/**
	 * Status Event
	 * 0 = Bozza [default]
	 * 1 = Pubblica
	 */
	public function getStatusEvent() {
		return $this->status_event;
	}


	/**
	 * Rimuove lo StatusEvent
	 */
	public function removeStatusEvent(){
		delete_option('social2blog_fb_eventStatus');
	}

	/**
	 * Recupera l'organizzatore eventi
	 */
	public function getOrganizer(){
		return $this->organiz_event;
	}

	/**
	 * Recupera l'organizzatore eventi
	 */
	public static function retriveOrganizer(){
		return get_option('social2blog_fb_organizerEvent');
	}

	/**
	 * Salva l'organizzatore eventi
	 */
	public function saveOrganizer($organizer_event){
		update_option('social2blog_fb_organizerEvent', $organizer_event);
	}

	/**
	 * Elimina l'organizzatore eventi
	 */
	public function removeOrganizer(){
		delete_option('social2blog_fb_organizerEvent');
	}

	/**
	 * Salva l'autore del Post
	 */
	public function saveAuthorPost($aut) {
		update_option('social2blog_fb_postAuthor', $aut);
	}

	/**
	 * Restituisce l'autore del Post
	 */
	public static function retriveAuthorPost() {
		return get_option('social2blog_fb_postAuthor');

	}

	/**
	 * Autore del Post
	 */
	public function getAuthorPost() {
		return $this->author;
	}

	/**
	 * Rimuove l'autore del Post
	 */
	public function removeAuthorPost(){
		delete_option('social2blog_fb_postAuthor');
	}

	/**
	 * Save access token
	 */
	public function saveAccessToken($access_token, $api_key_xkoll) {
		//preparare la URL:

		$actual_link = SOCIAL2BLOG_LOCALURL."-facebook";

		$tz_object = new DateTimeZone('Europe/Rome');
		$datetime = new DateTime();
		$datetime->setTimezone($tz_object);
		$ora = $datetime->format('Y\-m\-d\ h:i');


		$icode = md5($actual_link.$ora);

		$graph_url_pages = "https://www.social2blog.com/api/access_token/get_access_token.php?idcode=".$icode."&api_key=".$api_key_xkoll."&fb_exchange_token=".$access_token;


		
		$output = Social2blog_Http::requestHttp($graph_url_pages);
		$_ssa = json_decode($output); 
		$expires_in = $_ssa->expires_in;

		update_option('social2blog_fb_access_token', $_ssa->access_token);
		$this->setAccess_token($_ssa->access_token);
		
		if (empty($expires_in)) {
			update_option('social2blog_fb_access_token_expire', "∞");
			$this->setAccess_token_expire("∞");
		} else  {
			$date = new DateTime();
			$date->add(new DateInterval('PT'.$expires_in."S")); //
			update_option('social2blog_fb_access_token_expire', $date->getTimestamp());
			$this->setAccess_token_expire($date->getTimestamp());
		}

		return;
	}

	/**
	 * Remove access token
	 */
	public function removeAccessToken(){
		delete_option('social2blog_fb_access_token');
		delete_option('social2blog_fb_access_token_expire');
		$this->setAccess_token(null);
		$this->setAccess_token_expire(null);
		echo "Rimosso access_token";

	}

	/**
	 * Ottiene access token
	 */
	public function retrieveAccessToken() {
		return get_option('social2blog_fb_access_token');
	}
	
	/**
	 * Ottiene access token
	 */
	public function retrieveAccessTokenExpire() {
		return get_option('social2blog_fb_access_token_expire');
	}

	/**
	 * Save id page
	 */
	public function saveIdPage($id_page) {
		update_option('social2blog_fb_id_page', $id_page);
		$this->page_id = $id_page;
	}

	/**
	 * Set id page
	 */
	public function setIdPage($id_page) {
		$this->page_id = $id_page;
	}

	/**
	 * Remove id_page
	 */
	public function removeIdPage(){
		$del = delete_option('social2blog_fb_id_page');
		$this->setPage_id(null);
	}

	/**
	 * Ottiene id_page
	 */
	public function retrieveIdPage() {
		return  get_option('social2blog_fb_id_page');
	}

	/**
	 * Save il numero di parole del titolo dei post
	 */
	public function saveTitleCount($num) {
		update_option('social2blog_fb_title_count', $num);
		$this->title_count = $num;
	}

	/**
	 * Remove il numero di parole del titolo dei post
	 */
	public function removeTitleCount(){
		$del = delete_option('social2blog_fb_title_count');
		$this->setTitle_count(null);
	}
	/**
	 * Restituisce il numero di parole del titolo dei post
	 * 0 = Prima frase del post
	 * n = numero delle prime parole da estrarre dal post
	 */
	public function retriveTitleCount(){
		return get_option('social2blog_fb_title_count');
	}

	/**
	 * Scarica le informazioni delle pagine gestite (id e nome pagina) e li restituisce
	 *
	 * @return array
	 */
	public function capturePageAdmin() {
		$graph_url_pages = "https://graph.facebook.com/v3.3/me/accounts?access_token=" . $this->access_token;
		$pages = Social2blog_Http::requestHttp($graph_url_pages);

		

		$xpage = json_decode ( $pages, true );
		$facebookPages =  $xpage['data'];
		$pagine = array ();
		for($i = 0; $i < count ( $facebookPages ); $i ++) {
			$pageAdminName = $facebookPages [$i] ['name'];
			// error_log ("PAGINA: ".$pageAdminName);
			$page_id = $facebookPages [$i] ['id'];
			$temp = array (
					$pageAdminName,
					$page_id
			);
			array_push ( $pagine, $temp );
		}
		return $pagine;
	}

	/**
	 * Prende l'access token della pagina
	 */
	public function  getPageToken($id_page){
		if (empty($id_page)) {
			$id_page = trim($id_page);
			$id_page = filter_var($id_page, FILTER_SANITIZE_NUMBER_INT);
		}

		$graph_url_pages = "https://graph.facebook.com/v3.3/$id_page?fields=access_token&access_token=" . $this->access_token;


		$output = Social2blog_Http::requestHttp($graph_url_pages);
		
		$temp = explode(",", $output);
		$pageToken = explode(":", $temp[0]);
		return $pageToken[1];
	}


	/**
	 * Se Ã¨ memorizzato l'access_token di facebook restituisce vero
	 */
	public function isFBConnected() {

		if (empty($this->access_token)) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Stampa lo stile del bottone
	 *
	 * @return html
	 */
	public function classFBButton() {
		if (empty($this->access_token)) {
			return "button-primary connect-fb";
		} else {
			return "button-secondary remove-fb";
		}
	}

	/**
	 * Testo nel bottone
	 */
	public function textButton() {
		if (empty($this->access_token)) {
			return __( 'Collega', 'social2blog-text' );
		} else {
			return __( 'Scollega', 'social2blog-text' );
		}
	}


	/**
	 * Crea il link per loggarsi su Facebook
	 */
	public function linkFBButton() {
		if ( !$this->isFBConnected() ) {
			$url = $this->createLoginUrl ();
			return $url;
		}
		else {

			$actual_link = admin_url().$_SERVER["REQUEST_URI"];
			return $actual_link."&access_token=remove";
		}

	}

	/**
	 * Crea l'url per il login su Facebook
	 */
	public function createLoginUrl() {

		//
		//$url = "https://graph.facebook.com/oauth/authorize?type=user_agent&client_id=$appID&redirect_uri=".$this->getRedirectURI()."&scope=manage_pages";

		//SOCIAL2BLOG_ACCESSURL
		$url = SOCIAL2BLOG_ACCESSURL."?refap=".urlencode(SOCIAL2BLOG_LOCALURL)."&api_key=".$this->apikey;
		return $url;
	}

	/**
	 * Action per il bottone del collegamento pagina e tag
	 */
	public function buttonActionPage(){
		if($this->getPage_id() == null){
			echo "save-facebook-card";
		}
		else{
			echo "remove-facebook-card";
		}
	}

	/**
	 * Classe per il bottone del collegamento pagina e tag
	 */
	public function classFBButtonPage(){
		if($this->getPage_id() == null ){
			return "button-primary connect-fb";
		}
		else{
			return "button-secondary remove-fb";
		}
	}

	/**
	 * Ottiene i bottoni
	 */
	public function getFormButton() {
		if($this->getPage_id() == null ){ ?>
			<input type='submit' class='button-primary connect-fb' name='submit' value='<?php echo __( 'Salva', 'social2blog-text' ); ?>'/>
		<?php } else { ?>
			<input type='submit' class='button-primary connect-fb' name='submit' value='<?php echo __( 'Modifica', 'social2blog-text' ); ?>'/>
			<input type='button' class='button-secondary remove-fb' name='cancella' id='cancellaFacebook' value='<?php echo __( 'Cancella', 'social2blog-text' ); ?>'/>
		<?php }
	}

	/**
	 * Salva la facebook card
	 */
	public function saveFacebookCard($req) {

		$id_page = $req["idPage"];
		$page_access_token = $this->getPageToken($id_page);

		$fb_post = isset($req["fb-post"]) ? $req["fb-post"] : "off";
		$stPost = isset($req["statusPost"]) ? $req["statusPost"] : "";
		$title_type =  isset($req["titolo_type"]) ? $req["titolo_type"] : "";
		$autore =  isset($req["autor_post"]) ?$req["autor_post"] : "";

		$fb_events = null;
		if(isset($req["fb-event"])) {
			$fb_events = $req["fb-event"];
		}


		if ($title_type == 0){
			$this->setTitle_count($req["titolo_type"]);
		}else{
			$this->setTitle_count($req["titolo_count"]);
		}

		if (empty($id_page)) {
			throw new Social2blog_Exception( __('Salvataggio fallito, pagina facebook non valida riprovare.', 'social2blog-text') );
		}

		$hiddenTags = $req["hidden-tags"];
		//trasforma i tag in lower case
		if (!empty($hiddenTags )) {
			$hiddenTags = strtolower($hiddenTags);
		}
		$tags = explode(" ", $hiddenTags);


		//Controllo gli hashtag
		if ($fb_post == "on"){
			$regex = '/^(?=.{2,140}$)(#|\x{ff03}){1}([0-9_\p{L}]*[_\p{L}][0-9_\p{L}]*)$/u';

			for($i=0; $i< count($tags); $i++){

				$upp = trim($tags[$i]);
				if (empty($upp)) {
					continue;
 				}
				$test = preg_match( $regex, $upp);

				if(!$test){
					throw new Social2blog_Exception(__( 'Salvataggio fallito, hastag non valido riprovare.', 'social2blog-text' ). " (".$upp.")");
				}
				//$tags[$i] = str_replace("#", "", $tags[$i]);
			}
		}
		$data = array(
				'id_page' => $id_page,
				'page_access_token' => $page_access_token,
				'fb_post'=> $fb_post,
				'tags' => $tags,
				'title_count' => $this->title_count,
				'fb_events' => $fb_events
			);

		$fbCard = json_encode($data);
		if( SOCIAL2BLOG_DEBUG ){
			Social2blog_Log::debug("--> FB CARD <--");
			Social2blog_Log::debug($fbCard);
		}

		$graph_url_pages = SOCIAL2BLOG_SERVER_URL."?api_key=".$this->apikey."&act=addfbcard&xk_data=".urlencode($fbCard);

		$output = Social2blog_Http::requestHttp($graph_url_pages);

		$stateJ = json_decode($output);

		$state = $stateJ->state;

		$this->saveIdPage($id_page);
		$this->savePost($fb_post);
		$this->setPost($fb_post);
		$this->saveTags($hiddenTags);
		$this->saveTitleCount($this->title_count);
		$this->saveAuthorPost($autore);
		$this->saveStatusPost($stPost);
		$tags = explode(' ', $hiddenTags);
		for($i=0 ; $i < count($tags); $i++){
			// Creo nuova categoria per orgni tag
			wp_create_category(str_replace("#","", $tags[$i]));
		}


		if( isset($req["fb-event"]) && $req["fb-event"] == "on" ){
			$this->saveEvent($req["fb-event"]);
			$this->saveStatusEvent($req["statusEvents"]);
			$this->saveOrganizer($req["organizer_events"]);
		}else{
			$this->saveEvent("off");
		}

		if( $state === "success" and $stateJ->body->api_key === $this->apikey ){
			return "ok";
		}
		elseif( $state == "fail" ){
			$error = $stateJ->message;

			if ($error == "api key not found" || $error == "page access token not found"){
			?>
   				<div class="notice error my-acf-notice is-dismissible" >
   	 			<p><?php _e( 'Salvataggio fallito, riprovare.', 'social2blog-text' ); ?></p>
		 		</div>
   			<?php
   			social2blog_setstate("1");
   			return "apikey_errata";
			}
		}else{
			social2blog_setstate("1");
			return "error";
		}
	}

	/**
	 * Rimuove la facebook card
	 */
	public function removeFacebookCard(){
		$this->removeTags();
		$this->removeIdPage();
		$this->removeSavePost();
		$this->saveEvent("off");
		//-->

		$data = array(
				'id_page' => " ",
				'page_access_token' => " ",
				'fb_post'=> "off",
				'tags' => " ",
				'title_count' => " ",
				'fb_events' => "off"
		);

		$fbCard = json_encode($data);
		if( SOCIAL2BLOG_DEBUG ){
			Social2blog_Log::debug("--> FB CARD <--");
			Social2blog_Log::debug($fbCard);
		}

		$graph_url_pages = SOCIAL2BLOG_SERVER_URL."?api_key=".$this->apikey."&act=addfbcard&xk_data=".urlencode($fbCard);

		//error_log($graph_url_pages);
		$output = Social2blog_Http::requestHttp($graph_url_pages);

		$stateJ = json_decode($output);

		$state = $stateJ->state;


		if( $state === "success" and $stateJ->body->api_key === $this->apikey ){
			return "ok";
		}
		elseif( $state == "fail" ){
			$error = $stateJ->message;

			if ($error == "api key not found" || $error == "page access token not found"){
				?>
		   				<div class="notice error my-acf-notice is-dismissible" >
		   	 			<p><?php _e( 'Salvataggio fallito, riprovare.', 'social2blog-text' ); ?></p>
				 		</div>
		   	 <?php
		   			social2blog_setstate("1");
		   			return "apikey_errata";
			}
		}else{
					social2blog_setstate("1");
					return "error";
		}


		//-->


	}


}
