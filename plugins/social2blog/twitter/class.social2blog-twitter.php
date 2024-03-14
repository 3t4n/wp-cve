<?php
if( !defined( 'ABSPATH' ) ) exit;

class Social2blog_Twitter {
	private $title_count, $post_foto, $author, $api_key, $user_name, $user_id;
	function __construct() {
		$apikey = $this->get_apikey ();
		$this->setApikey ( $apikey );
		
		$user_name = $this->retriveUser_name ();
		$this->setUser_name ( $user_name );

		$user_id = $this->retriveUser_id ();
		$this->setUser_id ( $user_id );
		
		$author = $this->retriveAuthorPost();
		$this->setAuthorPost($author);
	}
	public function getTitle_count() {
		return $this->title_count;
	}
	public function setTitle_count($num) {
		$this->title_count = $num;
	}
	public function getPost_foto() {
		return $this->post_foto;
	}
	public function setPost_foto($value) {
		$this->post_foto = $value;
	}

	/**
	 * Recupero ApiKey dul DB
	 */
	public function get_apikey() {
		return get_option ( 'social2blog_apikey' );
	}
	public function setApikey($setApiKey) {
		$this->api_key = $setApiKey;
	}

	/**
	 * Save OAuth Token
	 */
	public function saveOAuthToken($oauth_token) {
		update_option ( 'social2blog_tw_oauth_token', $oauth_token );
	}

	/**
	 * Save OAuth Token Secret
	 */
	public function saveOAuthSecret($oauth_token_secret) {
		update_option ( 'social2blog_tw_oauth_secret', $oauth_token_secret );
	}

	/**
	 * Save $user_name
	 */
	public function saveUser_name($user_name) {
		update_option ( 'social2blog_tw_user_name', $user_name );
	}

	/**
	 * Save $user_id
	 */
	public function saveUser_id($user_id) {
		update_option ( 'social2blog_tw_user_id', $user_id );
	}

	/**
	 * Remove OAuth Token
	 */
	public function removeOAuthToken() {
		$del = delete_option ( 'social2blog_tw_oauth_token' );
	}

	/**
	 * Remove OAuth Token Secret
	 */
	public function removeOAuthTokenSecret() {
		$del = delete_option ( 'social2blog_tw_oauth_secret' );
	}

	/**
	 * Cancella user_name
	 */
	public function removeUser_name() {
		$del = delete_option ( 'social2blog_tw_user_name' );
	}

	/**
	 * Cancella User_id
	 */
	public function removeUser_id() {
		$del = delete_option ( 'social2blog_tw_user_id' );
	}
	
	public function setAuthorPost($author){
		$this->author = $author;
	}

	/**
	 * Cancella tutti i record dal DB
	 */
	public function removeInfoDB() {
		$this->removeOAuthToken ();
		$this->removeOAuthTokenSecret ();
		$this->removeUser_id ();
		$this->removeUser_name ();

		/*
		 * $user_name = $this->retriveUser_name();
		 * $this->setUser_name($user_name);
		 *
		 * $user_id = $this->retriveUser_id();
		 * $this->setUser_id($user_id);
		 */
		echo "Informazioni Twitter rimosse";
	}

	/**
	 * Ottiene OAuth Token dal db
	 */
	public function retrieveOAuthToken() {
		return get_option ( 'social2blog_tw_oauth_token' );
	}

	/**
	 * Ottiene OAuth Token Secret dal db
	 */
	public function retrieveOAuthSecret() {
		return get_option ( 'social2blog_tw_oauth_secret' );
	}

	/**
	 * Ottiene user_name dal db
	 */
	public function retriveUser_name() {
		return get_option ( 'social2blog_tw_user_name', $this->user_name );
	}

	/**
	 * Salva user_name
	 */
	public function setUser_name($user_name) {
		$this->user_name = $user_name;
	}

	/**
	 * Salva user_id
	 */
	public function setUser_id($user_id) {
		$this->user_id = $user_id;
	}

	/**
	 * Ottiene name_id dal db
	 */
	public function retriveUser_id() {
		return get_option ( 'social2blog_tw_user_id', $this->user_id );
	}

	/**
	 * Save il numero di parole del titolo dei post
	 */
	public function saveTitleCount($num) {
		update_option ( 'social2blog_tw_title_count', $num );
		$this->title_count = $num;
	}

	/**
	 * Remove il numero di parole del titolo dei post
	 */
	public function removeTitleCount() {
		$del = delete_option ( 'social2blog_tw_title_count' );
		$this->setTitle_count ( null );
	}
	/**
	 * Restituisce il numero di parole del titolo dei post
	 * 0 = Prima frase del post
	 * n = numero delle prime parole da estrarre dal post
	 */
	public function retriveTitleCount() {
		return get_option ( 'social2blog_tw_title_count' );
	}

	/**
	 * Save il controllo sul post
	 */
	public function savePostFoto($value) {
		update_option ( 'social2blog_tw_post_foto', $value );
	}
	/**
	 * Remove il controllo sul post
	 */
	public function removePostFoto() {
		$del = delete_option ( 'social2blog_tw_post_foto' );
		$this->setPost_foto ( null );
	}
	/**
	 * Restituisce il controllo sul post
	 */
	public function retrivePostFoto() {
		return get_option ( 'social2blog_tw_post_foto' );
	}
	/**
	 * Prende i tag dal DB
	 */
	public static function getTags() {
		$tags = get_option ( 'social2blog_tw_tags' );
		if (!empty($tags)) {
			$tags = strtolower($tags);
		}
		return $tags;
	}

	/**
	 * Prende in ingresso una stringa di tag e la salva sul db
	 * es $tags = "tag1,tag2,tag3"
	 */
	public static function saveTags($tags) {
		if (!empty($tags)) {
			$tags = strtolower($tags);
		}
		update_option ( 'social2blog_tw_tags', $tags );
	}

	/**
	 * Remove tags salvati nel db
	 */
	public function removeTags() {
		delete_option ( 'social2blog_tw_tags' );
	}

	/**
	 * Salva l'autore del Post
	 */
	public function saveAuthorPost($aut) {
		update_option ( 'social2blog_tw_postAuthor', $aut );
	}

	/**
	 * Restituisce l'autore del Post
	 */
	public static function retriveAuthorPost() {
		return get_option ( 'social2blog_tw_postAuthor' );
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
	public function removeAuthorPost() {
		delete_option ( 'social2blog_tw_postAuthor' );
	}

	/**
	 * Save twitter card.
	 * Passa la request
	 */
	public function saveTwitterCard($req) {

		
		$hiddenTags = $req["hidden-tags"];
		//trasforma i tag in lower case
		if (!empty($hiddenTags )) {
			$hiddenTags = strtolower($hiddenTags);
		}
		
		$tags = explode ( " ", $hiddenTags );
		$stPost = isset($req["statusPost"]) ? $req["statusPost"] : "";
		$autore = isset($req["autor_post"]) ?$req["autor_post"] : "";

		$title_type = $req ["titolo_type"];
		if (isset ( $req ["post_foto"] )) {
			if ($req ["post_foto"] == "on") {
				$this->setPost_foto ( $req ["post_foto"] );
			}
		} else {
			$this->setPost_foto ( "off" );
		}

		if ($title_type == 0) {
			$this->setTitle_count ( $req ["titolo_type"] );
		} else {
			$this->setTitle_count ( $req ["titolo_count"] );
		}

		// Controllo gli hashtag
		$regex = '/^(?=.{2,140}$)(#|\x{ff03}){1}([0-9_\p{L}]*[_\p{L}][0-9_\p{L}]*)$/u';
		for($i = 0; $i < count ( $tags ); $i ++) {
			
			
			$upp = trim($tags[$i]);
			if (empty($upp)) {
				continue;
			}
			
			$test = preg_match( $regex, $upp);
			
			if(!$test) {
					throw new Social2blog_Exception(__( 'Salvataggio fallito, hastag non valido riprovare.', 'social2blog-text' ). " (".$upp.")");
			}
			// $tags[$i] = str_replace("#", "", $tags[$i]);
		}
		
		$oauth_token = $this->retrieveOAuthToken();
		$oauth_secret = $this->retrieveOAuthSecret();
		$data = array (
				'tw_oauth_token' => $oauth_token,
				'tw_oauth_secret' => $oauth_secret,
				'tw_tags' => $tags,
				'tw_title_count' => $this->title_count,
				'tw_post_foto' => $this->post_foto,
				'tw_user_name' => $this->user_name,
				'tw_user_id' => $this->user_id
		);
		$twCard = json_encode ( $data );
		if( SOCIAL2BLOG_DEBUG ){
			Social2blog_Log::debug("--> TW CARD <--");
			Social2blog_Log::debug($twCard);
		}
	

		$graph_url_pages = SOCIAL2BLOG_SERVER_URL . "?api_key=" . $this->api_key . "&act=addtwcard&xk_data=" . urlencode($twCard);

		$output = Social2blog_Http::requestHttp ( $graph_url_pages );

		$stateJ = json_decode ( $output );

		$this->saveTags ( $hiddenTags );
		$this->saveStatusPost ( $stPost );
		$this->savePostFoto($this->post_foto);
		$this->saveTitleCount ( $this->title_count );
		$this->saveAuthorPost ( $autore );
		$this->setAuthorPost ( $autore );
		$tags = explode ( ' ', $hiddenTags );
		for($i = 0; $i < count ( $tags ); $i ++) {
			// Creo nuova categoria per ogni tag
			wp_create_category ( str_replace ( "#", "", $tags [$i] ) );
		}

		$state = $stateJ->state;
		
		if ($state === "success" && $stateJ->body->api_key === $this->api_key) {
			return "ok";
		} elseif ($state == "fail") {
			social2blog_setstate ( "1" );
			$error = $stateJ->message;
			if ($error == "api key not found") {
				return "apikey_errata";
			}
		} else {
			social2blog_setstate ( "1" );
			return "error";
		}
	}

	/**
	 * Rimuove la twitter card
	 */
	public function removeTwitterCard() {
		$this->removeTags ();
		$this->removeAuthorPost();
		$this->removePostFoto();
		$this->removeStatusPost();
		$this->removeTitleCount();
		
		
		$oauth_token = $this->retrieveOAuthToken();
		$oauth_secret = $this->retrieveOAuthSecret();
		$data = array (
				'tw_oauth_token' => $oauth_token,
				'tw_oauth_secret' => $oauth_secret,
				'tw_tags' => "@notag",
				'tw_title_count' => " ",
				'tw_post_foto' => " ",
				'tw_user_name' => " ",
				'tw_user_id' => " "
		);
		$twCard = json_encode ( $data );
		if( SOCIAL2BLOG_DEBUG ){
			Social2blog_Log::debug("--> TW CARD <--");
			Social2blog_Log::debug($twCard);
		}
		
		error_log($twCard);
		$graph_url_pages = SOCIAL2BLOG_SERVER_URL . "?api_key=" . $this->api_key . "&act=addtwcard&xk_data=" . urlencode($twCard);
		
		$output = Social2blog_Http::requestHttp ( $graph_url_pages );
		
		$stateJ = json_decode ( $output );
		
		
		$state = $stateJ->state;
		
		if ($state === "success" && $stateJ->body->api_key === $this->api_key) {
			return "ok";
		} elseif ($state == "fail") {
			social2blog_setstate ( "1" );
			$error = $stateJ->message;
			if ($error == "api key not found") {
				return "apikey_errata";
			}
		} else {
			social2blog_setstate ( "1" );
			return "error";
		}
	}

	/**
	 * Status Post
	 * 0 = Bozza [default]
	 * 1 = Pubblica
	 */
	public function saveStatusPost($stPost) {
		update_option ( 'social2blog_tw_postStatus', $stPost );
	}

	/**
	 * Status Post
	 * 0 = Bozza [default]
	 * 1 = Pubblica
	 */
	public function retriveStatusPost() {
		return get_option ( 'social2blog_tw_postStatus' );
	}

	/**
	 * Status Post
	 * 0 = Bozza [default]
	 * 1 = Pubblica
	 */
	public function getStatusPost() {
		return $this->status_post;
	}

	/**
	 * Rimuove lo StatusPost
	 */
	public function removeStatusPost() {
		delete_option ( 'social2blog_tw_postStatus' );
	}

	/**
	 * Restituisce gli ultimi $num tweet
	 */

	/**
	 * Se OAuth_verifier è stato usato si è loggati
	 */
	public function isTWConnected() {
		// $OauthVer = $this->getOauthVerifier();
		if ($this->user_name != null) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Crea il link per loggarsi su Twitter
	 */
	public function linkTWButton() {
		$connected = $this->isTWConnected ();
		if (! $connected) {
			// $this->getOAuthCookie();
			$apik = $this->get_apikey ();
			$url = $this->createLoginUrl ( $apik );
			return $url;
		} else {
			// $url = admin_url();
			$str_uri = SOCIAL2BLOG_LOCALURL;
			return $str_uri;
		}
	}

	/**
	 * Classe per lo stile del bottone
	 */
	public function classTWButton() {
		$connected = $this->isTWConnected ();
		if (! $connected) {
			return "button-primary connect-tw";
		} else {
			return "button-secondary remove-tw";
		}
	}

	/**
	 * Testo nel bottone
	 */
	public function textButton() {
		$connected = $this->isTWConnected ();
		if (! $connected) {
			return __ ( 'Collega', 'social2blog-text' );
		} else {
			return __ ( 'Scollega', 'social2blog-text' );
		}
	}

	/**
	 * Restituisce il link per generare l'oauth token da verificare
	 */
	public function createLoginUrl($apik) {
		return SOCIAL2BLOG_TWITTERCALLBACKURL . "&refap_twitter=" . urlencode ( SOCIAL2BLOG_TWITTER_FL_LOCAL_URL ) . "&final_redirect_uri=" . urlencode ( SOCIAL2BLOG_LOCALURL ) . "&api_key=" . $apik;
	}

	/**
	 * Action per il bottone del collegamento pagina e tag
	 */
	public function buttonActionPage() {
		$tags = $this->getTags ();
		if ($tags == null) {
			echo "save-twitter-card";
		} else {
			echo "remove-twitter-card";
		}
	}

	/**
	 * Classe per il bottone del collegamento pagina e tag
	 */
	public function classTWButtonPage() {
		$tags = $this->getTags ();
		if ($tags == null) {
			return "button-primary connect-tw";
		} else {
			return "button-secondary remove-tw";
		}
	}

	/**
	 * Ottiene i bottoni
	 */
	public function getFormButton() {
		$tags = $this->getTags ();
		if ($tags == null) {
			?>
<input type='submit' class='button-primary connect-tw' name='submit'
	value='<?php echo __( 'Salva', 'social2blog-text' ); ?>' />
<?php } else { ?>
<input type='submit' class='button-primary connect-tw' name='submit'
	value='<?php echo __( 'Modifica', 'social2blog-text' ); ?>' />
<input type='button' class='button-secondary remove-tw' name='cancella'
	id='cancellaTwitter'
	value='<?php echo __( 'Cancella', 'social2blog-text' ); ?>' />
<?php

}
	}
}
