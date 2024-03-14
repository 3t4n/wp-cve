<?php



	/* New Code */
	

if(!class_exists('qrstr')){
	include('phpqrcode.php');
}

if(!class_exists("SingleSettingsWpdp")){

    class SingleSettingsWpdp{

        public $name;
        public $type;
        public $label;
        public $value;
        public $choices;
        public $premium;

	    /**
	     * SingleSettingsWpdp constructor.
	     *
	     * @param $name
	     * @param $type
	     * @param $label
	     * @param $value
	     * @param $choices
	     */
	    public function __construct( string $name, string $type, string $label, string $value, Array $choices = array() ) {

		    $this->name    = $name;
		    $this->type    = $type;
		    $this->label   = $label;
		    $this->value   = $value;
		    $this->choices = $choices;
		    $this->premium = "no";
	    }

	    /**
	     * @param string $premium
	     */
	    public function setPremium( string $premium ) {
		    $this->premium = $premium;
	    }




    }
}

if(!class_exists("ChoiceWpdp")){

	class ChoiceWpdp{

		public $name;
		public $value;

		/**
		 * SingleSettingsWpdp constructor.
		 *
		 * @param $name
		 * @param $value
		 */
		public function __construct( $name, $value ) {
			$this->name  = $name;
			$this->value = $value;
		}

	}
}

if(!class_exists("WPDP_ProSettings")){

	class WPDP_ProSettings{

        public $isPro;
        public $singleProList;
        public $login_key;

		/**
		 * @return mixed
		 */
		public function getIsPro() {
			return $this->isPro;
		}

		/**
		 * @param mixed $isPro
		 */
		public function setIsPro( $isPro ) {
			$this->isPro = $isPro;
		}

		/**
		 * @return mixed
		 */
		public function getSingleProList() {
			return $this->singleProList;
		}

		/**
		 * @param mixed $singleProList
		 */
		public function setSingleProList( $singleProList ) {
			$this->singleProList = $singleProList;
		}

		/**
		 * @return mixed
		 */
		public function getLoginKey() {
			return $this->login_key;
		}

		/**
		 * @param mixed $login_key
		 */
		public function setLoginKey( $login_key ) {
			$this->login_key = $login_key;
		}





	}
}

if(!class_exists("WPDP_SinglePro")){
    class WPDP_SinglePro{

        public $option_id, $option_name, $wp_datepicker, $image_url, $singleSettingList;

	    /**
	     * @return mixed
	     */
	    public function getOptionId() {
		    return $this->option_id;
	    }

	    /**
	     * @param mixed $option_id
	     */
	    public function setOptionId( $option_id ) {
		    $this->option_id = $option_id;
	    }

	    /**
	     * @return mixed
	     */
	    public function getOptionName() {
		    return $this->option_name;
	    }

	    /**
	     * @param mixed $option_name
	     */
	    public function setOptionName( $option_name ) {
		    $this->option_name = $option_name;
	    }

	    /**
	     * @return mixed
	     */
	    public function getWpDatepicker() {
		    return $this->wp_datepicker;
	    }

	    /**
	     * @param mixed $wp_datepicker
	     */
	    public function setWpDatepicker( $wp_datepicker ) {
		    $this->wp_datepicker = $wp_datepicker;
	    }

	    /**
	     * @return mixed
	     */
	    public function getImageUrl() {
		    return $this->image_url;
	    }

	    /**
	     * @param mixed $image_url
	     */
	    public function setImageUrl( $image_url ) {
		    $this->image_url = $image_url;
	    }

	    /**
	     * @return mixed
	     */
	    public function getSingleSettingList() {
		    return $this->singleSettingList;
	    }

	    /**
	     * @param mixed $singleSettingList
	     */
	    public function setSingleSettingList( $singleSettingList ) {
		    $this->singleSettingList = $singleSettingList;
	    }



    }
}



if(!class_exists('QR_Code_Settings_WPDP')){
	class QR_Code_Settings_WPDP
	{
		private $rest_api_url;
		private $plugin_url;
		private $plugin_dir;
		public $app_slug;
		public $prefix;
		public $css_prefix;
        private $default_array = array(

			"wp_datepicker" => "",
			"wp_datepicker_language" => "Select Language",
			"wp_datepicker_wpadmin" => "0",
			"wp_datepicker_readonly" => "1",
			"wp_datepicker_weekends" => "0",
			"wp_datepicker_beforeShowDay" => "",
			"wp_datepicker_months" => "0",
			"wpdp_fonts" => "",

        );

        private  $options_array = array(

			"wpdp_inline" => "",
			"use_custom_style1" => "",
			"color1" => "",
			"color2" => "",
			"color3" => "",
			"color4" => "",
			"color5" => "",
			"dateFormat" => "",
			"defaultDate" => "",
			"changeMonth" => "",
			"changeYear" => "",
			"firstDay" => "",
			"closeText" => "",
			"currentText" => "",
			"minDate" => "",
			"maxDate" => "",
			"yearRange" => "",
			"showButtonPanel" => "",

        );
		
	
		function __construct($plugin_dir, $plugin_url, $rest_api_url)
		{
			$this->rest_api_url = $rest_api_url;
			$this->plugin_url = $plugin_url;
			$this->plugin_dir = $plugin_dir;		
			$this->app_slug = 'wp.datepicker';
			$this->prefix = 'wp_datepicker_';
			$this->css_prefix = str_replace('_', '-', $this->prefix);
			$this->execute_settings_api();

		}

	
		private function get_login_key_option_name(){
			$login_key_option_name = str_replace(" ", "_", get_bloginfo());
			$login_key_option_name = $login_key_option_name."_login_key";
			return $login_key_option_name;
		}
	
		private function generate_random_login_key(){
	
			$login_key_option_name = $this->get_login_key_option_name();		
			$login_key_array = get_option($login_key_option_name);
			$rand_login_key = md5(rand());
			
	
			if(empty($login_key_array)){
				$rand_key_update_status =	update_option($login_key_option_name, array($rand_login_key));
			}else{
				$login_key_array[] = $rand_login_key;
				$rand_key_update_status =	update_option($login_key_option_name, $login_key_array);
			}
	
			if($rand_key_update_status == true){
				return	$rand_login_key;
			}else{
				return false;
			}
		}	
	
		private function validate_login_key($login_key){
	
			$login_key_option_name = $this->get_login_key_option_name();
			$login_key_array = get_option($login_key_option_name, array());
			// $login_key_array = array("a7749324bf84d8e7ae248562832d24d5");
			$login_key_match_result = array_search($login_key, $login_key_array);
			if($login_key_match_result >= 0){
				return true;
			}else{
				return false;
			}
		}
		
		function qrhash_authentication_settings($param){
	
			
	
			$result_array = array(
	
				"request_status" => "rejected",
	
				"login_key" => "null",
	
				"settings_name" => "null"
	
			);
	
	
	
			if(isset($param['qr_hash'])){				
	
				
	
				$qr_hash_call = $param['qr_hash'];
	
				$qr_hash_option = get_option($this->prefix.'qrcode_hash');				
	
				$epn_qrcode_hash = $qr_hash_option[$this->prefix.'qrcode_hash'];
	
				// $epn_qrcode_hash = "a";
	
				$epn_qrcode_hash_time = $qr_hash_option[$this->prefix.'qrcode_hash_time'];
	
				
	
				if($epn_qrcode_hash == $qr_hash_call){
	
					$rand_login_key = $this->generate_random_login_key();				
	
					if($rand_login_key != false){
						$result_array["request_status"] = "active";
						$result_array["login_key"] = $rand_login_key;
						$result_array["settings_name"] = get_bloginfo();
					}
	
				}			
	
			
	
			}
	
	
	
			$res = new WP_REST_Response($result_array);			
	
			return $res;
	
		}
	
		function register_qrhash_authentication_settings() {		
	
			
	
			register_rest_route( $this->rest_api_url, '/'.$this->prefix.'authentication', array(
	
			  'methods' => 'POST',
	
			  'callback' => array($this,'qrhash_authentication_settings'),
			  
			  'permission_callback' => '__return_true',
	
			));
		}	
	
		function generate_qrcode_ajax() { ?>
	
			<script type="text/javascript" language="javascript">
	
	
	
				jQuery(document).ready(function($) {
	
	
	
					var qrSample = $(".<?php echo $this->css_prefix; ?>qrcode-body .<?php echo $this->css_prefix; ?>qrcode-view .qr-sample");
	
					var modal = $(".<?php echo $this->css_prefix; ?>qrcode-body .qr-modal");
	
					var qrcode_img = $('.<?php echo $this->css_prefix; ?>qrcode-body .<?php echo $this->css_prefix; ?>qrcode-img');
	
					var modal_close = $('.<?php echo $this->css_prefix; ?>qrcode-body .qr-modal .qr-modal-close');
	
					var interval = null;
	
	
	
					var data = {
	
						'action': '<?php echo $this->prefix; ?>generate_qrcode'
	
					};
	
	
	
					var get_qrcode = function (){
	
	
	
						jQuery.post(ajaxurl, data, function(response, status) {							
	
							
	
							if(status == 'success'){
	
								qrcode_img.html(response);
	
							}
	
	
	
						});
	
					}
	
					
	
					var clear_interval = function (){
	
						clearInterval(interval);
	
						qrcode_img.html('<span class="qr-loading"><?php _e("Loading", "wp-datepicker"); ?>...</span>');
	
					}
	
					
	
					qrSample.on("click", function(){
	
						modal.css("display","block");
	
						$(get_qrcode);
	
						interval = setInterval(get_qrcode, 1000*60);
	
					})
	
	
	
					modal.on("click", function(e){
	
						
	
						if(e.target == modal[0]){
	
							modal.css("display", "none");
	
							$(clear_interval);
	
						}
	
							
	
						
	
					})
	
	
	
					modal_close.on("click", function(){
	
						modal.click();
	
					});
	
	
	
					$(document).keyup(function(e) {
	
						
	
						if (e.keyCode === 27){
	
								modal.click();
	
								$(clear_interval);
	
						}
	
					});
	
					
	
					
	
					
	
					
	
				});
	
	
	
			</script> 
	
			<?php
	
		}
	
		function generate_qrcode() {
	

	
			$tempDir = $this->plugin_dir."io/";
			if(!file_exists($tempDir)){
				mkdir($tempDir);
			}
			$url = $this->plugin_url."io/";

			/*
			$files = glob($tempDir.'*'); // get all file names		
	
			if(!empty($files)){
	
				foreach($files as $file){ // iterate files
	
					//if(is_file($file))	
					//unlink($file); // delete file
	
				}
	
			}
			*/
			
	
			$epn_qrcode_hash_array = array();
	
			$codeContents = array();
	
			$rand_no = rand();
	
			$rand_no_qr = md5($rand_no);
	
			$codeContents['url'] = get_home_url()."/wp-json/".$this->rest_api_url."/";
			// $codeContents['url'] = "http://192.168.43.248:82/wp-json/".$this->rest_api_url."/";
	
			$codeContents['qr_hash'] = $rand_no_qr;
	
			$epn_qrcode_hash_array[$this->prefix.'qrcode_hash_time'] = time()+30;
	
			$epn_qrcode_hash_array[$this->prefix.'qrcode_hash'] = $codeContents['qr_hash'];
	
			update_option($this->prefix.'qrcode_hash', $epn_qrcode_hash_array);
	
	
	
			$qr_content = json_encode($codeContents);
	
			$fileName = 'sample.png';
	
			$pngAbsoluteFilePath = $tempDir.$fileName;		
	
			QRcode::png($qr_content, $pngAbsoluteFilePath,QR_ECLEVEL_L,10);
			
			echo '<img src="'.$url.$fileName.'?t='.time().'" />';
	
	
	
			wp_die();
	
	
	
		}
	
	
		private function execute_settings_api(){
	
			add_action( 'rest_api_init', array($this, 'register_api_read_settings'));
			add_action( 'rest_api_init', array($this, 'register_api_update_settings'));
			add_action( 'rest_api_init', array($this, 'register_qrhash_authentication_settings'));
			add_action( 'wp_ajax_'.$this->prefix.'generate_qrcode', array($this,'generate_qrcode') );
			add_action( 'admin_footer', array($this, 'generate_qrcode_ajax') );
		}
	
		public function ab_io_display($plugin_url){
			

			?>
			<style type="text/css">
				.<?php echo $this->css_prefix; ?>qrcode-body .<?php echo $this->css_prefix; ?>qrcode-view {
					float: left;
					width: 172px;
					height: 100px;
					clear: both;
					cursor: pointer;
					position: relative;
				}
				
				.<?php echo $this->css_prefix; ?>qrcode-body .<?php echo $this->css_prefix; ?>qrcode-view .qr-sample {
					width: auto;
					height: 38px;
					position: absolute;
					right: 0;
				}
	
				.<?php echo $this->css_prefix; ?>qrcode-body .<?php echo $this->css_prefix; ?>qrcode-view .google-badge-img {
					width: auto;
					height: 38px;
					top: 0;
					position:absolute;
	
				}
	
				.<?php echo $this->css_prefix; ?>qrcode-body .qr-modal{
					display: none;
					position:fixed;
					z-index: 50000;
					top:0;
					left:0;
					width: 100%;
					height:100%;
					overflow: auto;
					background-color: rgb(0,0,0);
					background-color: rgba(0,0,0,0.6);
				}
	
	
				.<?php echo $this->css_prefix; ?>qrcode-body .qr-modal .modal-content {
	
					margin: auto;	
					width: 40%;
					text-align: center;
					padding-top: 50px;
					padding-bottom: 20px;
	
				}
	
				.<?php echo $this->css_prefix; ?>qrcode-body .qr-modal .modal-content .qr-loading {
	
					font-size: 2rem;
					color: white;
				} 
	
	
				.<?php echo $this->css_prefix; ?>qrcode-body .<?php echo $this->css_prefix; ?>qrcode-img img {
					widows: 100%;
					height: auto;
				}
	
				.<?php echo $this->css_prefix; ?>qrcode-body .qr-modal .qr-modal-close {
					color: tomato;
					float: right;
					font-size: 50px;
					/* font-weight: bold; */
					margin-top: 50px;
					margin-right: 50px;
	
				}
	
				.<?php echo $this->css_prefix; ?>qrcode-body .qr-modal .qr-modal-close:hover,
				.<?php echo $this->css_prefix; ?>qrcode-body .qr-modal .qr-modal-close:focus {
					color: #000;
					text-decoration: none;
					cursor: pointer;
				}
	
			</style>
			<div class="<?php echo $this->css_prefix; ?>qrcode-body">
	
	
	
				<div class="<?php echo $this->css_prefix; ?>qrcode-view">
	
	
	
					<img class="qr-sample" title="<?php _e("Click here to Scan QR Code", "wp-datepicker"); ?>" src="<?php echo $plugin_url.'io/sample.png' ?>">
	
					<div class="google-badge">
	
	
	
						<a target="_blank" href="https://play.google.com/store/apps/details?id=<?php echo $this->app_slug; ?>" title="<?php _e("Click here for Android Application", "wp-datepicker"); ?>">
	
							<img class="google-badge-img" alt="<?php _e("Get it on", "wp-datepicker"); ?> Google Play" src="<?php echo $plugin_url.'img/'; ?>googplay.png" />
	
						</a>
	
	
	
					</div>
	
				</div>
	
				
	
	
	
				<div class="qr-modal">
	
					<span class="qr-modal-close">&times;</span>
	
					<!-- Modal content -->
	
					<div class="modal-content">
	
						
	
						<div class="<?php echo $this->css_prefix; ?>qrcode-img">
	
							<span class="qr-loading"><?php _e("Loading", "wp-datepicker"); ?>...</span>
	
						</div>
	
					</div>
	
	
	
				</div>
	
	
	
			</div>
	
			<?php		
	
		}

		function register_api_read_settings(){

			register_rest_route( $this->rest_api_url, '/'.$this->prefix.'read_settings', array(

				'methods' => 'POST',

				'callback' => array($this, 'api_read_settings'),
				
				'permission_callback' => '__return_true',

			));

			register_rest_route( $this->rest_api_url, '/'.$this->prefix.'read_pro_settings', array(

				'methods' => 'POST',

				'callback' => array($this, 'api_read_pro_settings'),
				
				'permission_callback' => '__return_true',

			));
		}

		function register_api_update_settings() {

			register_rest_route( $this->rest_api_url, '/'.$this->prefix.'update_settings', array(

				'methods' => 'POST',

				'callback' => array($this,'api_update_settings'),
				
				'permission_callback' => '__return_true',

			));
		}

		function api_read_settings($param){

		    global $wpdp_pro;

			$login_key = $param['login_key'];
			$option_name = $param['option_name'];

			$settingsList = array();

			$settings_array = array(
				"login_key" => "invalid",
                "qrStatus" => "ok",
				"isPro" => $wpdp_pro ? "yes" : "no",
				"singleSettingList" => $settingsList,

			);

			if($login_key == $this->app_slug){

				$login_key_status = $this->validate_login_key($login_key);
				$settingsList = $this->api_get_single_setting_list($option_name);

				$settings_array['singleSettingList'] = $settingsList;
				$settings_array['login_key'] = "valid";

            }

            $res = new WP_REST_Response($settings_array);
            return $res;


		}

		function api_get_single_setting_list($option_name){

			$wpdp_options_default = get_option($option_name, array());
			$wpdp_options = isset($wpdp_options_default['wpdp_options']) && is_array($wpdp_options_default['wpdp_options']) ? $wpdp_options_default['wpdp_options'] : array();

//            echo json_encode($wpdp_options_default);exit;

			$settingsList = array();

			$common_choices = array(new ChoiceWpdp('Enable', '1'), new ChoiceWpdp('Disable', '0'));
			$common_choices_reverse = array(new ChoiceWpdp('Enable', '0'), new ChoiceWpdp('Disable', '1'));
			$read0nly_choices = array(new ChoiceWpdp('Read-only', '1'), new ChoiceWpdp('Editable', '0'));
			$month_choices = array(new ChoiceWpdp('Short', '1'), new ChoiceWpdp('Full', '0'));


			$wp_datepicker = new SingleSettingsWpdp("wp_datepicker", "text", "Selector for datepicker as CSV (Comma Separated Values)", "");//free
			$wp_datepicker_wpadmin = new SingleSettingsWpdp("wp_datepicker_wpadmin", "radio", "Enable for backend (wp-admin)?", "", $common_choices);//free
			$wp_datepicker_readonly = new SingleSettingsWpdp("wp_datepicker_readonly", "radio", "Make datepicker field editable or readonly?", "", $read0nly_choices);//free
			$wp_datepicker_weekends = new SingleSettingsWpdp("wp_datepicker_weekends", "radio", "Weekends?", "", $common_choices_reverse);//free
			$wp_datepicker_months = new SingleSettingsWpdp("wp_datepicker_months", "radio", "Need months in full or short?", "", $month_choices);//free

//			$use_custom_style1 = new SingleSettingsWpdp("use_custom_style1", "checkbox", "File Upload Front-end (Premium)", "file_upload");
//			$use_custom_colors = new SingleSettingsWpdp("use_custom_colors", "checkbox", "Ajax Based Directory Navigation (Premium)", "ajax");
//			$color1 = new SingleSettingsWpdp("color1", "checkbox", "Update URI with Directory ID (Premium)", "ajax_url");
//			$color2 = new SingleSettingsWpdp("color2", "checkbox", "Update URI with Directory ID (Premium)", "ajax_url");
//			$color3 = new SingleSettingsWpdp("color3", "checkbox", "Update URI with Directory ID (Premium)", "ajax_url");
//			$color4 = new SingleSettingsWpdp("color4", "checkbox", "Update URI with Directory ID (Premium)", "ajax_url");
//			$color5 = new SingleSettingsWpdp("color5", "checkbox", "Update URI with Directory ID (Premium)", "ajax_url");

			$dateFormat = new SingleSettingsWpdp("dateFormat", "text", "Date Format:", "");//free
			$defaultDate = new SingleSettingsWpdp("defaultDate", "text", "Default Date:", "");//free

			$wpdp_inline = new SingleSettingsWpdp("wpdp_inline", "checkbox", "Sort By Filename or File Title? (Default: Filename)", "filename");//free
			$changeMonth = new SingleSettingsWpdp("changeMonth", "checkbox", "Change Month", "");//pro
			$changeYear = new SingleSettingsWpdp("changeYear", "checkbox", "Change Year", "");//pro
			$firstDay = new SingleSettingsWpdp("firstDay", "checkbox", "Update URI with Directory ID (Premium)", "ajax_url");//pro
			$closeText = new SingleSettingsWpdp("closeText", "checkbox", "Update URI with Directory ID (Premium)", "ajax_url");//pro
			$currentText = new SingleSettingsWpdp("currentText", "checkbox", "Update URI with Directory ID (Premium)", "ajax_url");//pro
			$minDate = new SingleSettingsWpdp("minDate", "checkbox", "Update URI with Directory ID (Premium)", "ajax_url");//pro
			$maxDate = new SingleSettingsWpdp("maxDate", "checkbox", "Update URI with Directory ID (Premium)", "ajax_url");//pro
			$yearRange = new SingleSettingsWpdp("yearRange", "checkbox", "Update URI with Directory ID (Premium)", "ajax_url");//pro
			$showButtonPanel = new SingleSettingsWpdp("showButtonPanel", "checkbox", "Show Button Panel:", "");//pro

			$premium_settings = array(

				"wpdp_inline",
				"changeMonth",
				"changeYear",
				"firstDay",
				"closeText",
				"currentText",
				"minDate",
				"maxDate",
				"yearRange",
				"showButtonPanel",
			);


			$settingsList[] = $wp_datepicker;
			$settingsList[] = $wp_datepicker_wpadmin;
			$settingsList[] = $wp_datepicker_readonly;
			$settingsList[] = $wp_datepicker_weekends;
			$settingsList[] = $wp_datepicker_months;
			$settingsList[] = $dateFormat;
			$settingsList[] = $defaultDate;

//			$settingsList[] = $wpdp_inline;
			$settingsList[] = $changeMonth;
			$settingsList[] = $changeYear;
//			$settingsList[] = $firstDay;
//			$settingsList[] = $closeText;
//			$settingsList[] = $currentText;
//			$settingsList[] = $minDate;
//			$settingsList[] = $maxDate;
//			$settingsList[] = $yearRange;
			$settingsList[] = $showButtonPanel;


			if(!empty($settingsList)){

				foreach ($settingsList as $index => $single_settings_AP){

                    $type = $single_settings_AP->type;


					if(array_key_exists($single_settings_AP->name, $this->default_array) && array_key_exists($single_settings_AP->name, $wpdp_options_default)){

						$single_settings_AP->value = $wpdp_options_default[$single_settings_AP->name];

						if($type == "checkbox"){

							$single_settings_AP->value = array_key_exists($single_settings_AP->name, $wpdp_options_default) ? "on" : "off";

						}

					}

					if (array_key_exists($single_settings_AP->name, $this->options_array) && array_key_exists($single_settings_AP->name, $wpdp_options)){

						$single_settings_AP->value = $wpdp_options[$single_settings_AP->name];

						if($type == "checkbox"){

							$single_settings_AP->value = array_key_exists($single_settings_AP->name, $wpdp_options) ? "on" : "off";

						}

					}




					if(in_array($single_settings_AP->name, $premium_settings)){
						$single_settings_AP->setPremium("yes");
					}

					$settingsList[$index] = $single_settings_AP;
				}

			}

			return $settingsList;


		}

		function api_read_pro_settings($param){

			global $wpdp_pro, $wpdp_url;


			$pro_settings = new WPDP_ProSettings();
			$pro_settings->setLoginKey("invalid");

			$single_settings_list = array();

			$login_key = $param['login_key'];

			if($login_key == $this->app_slug){

				$wpdp_get_datepicker_list = wpdp_get_datepicker_list();
				if(!empty($wpdp_get_datepicker_list)){

					foreach ($wpdp_get_datepicker_list as $index => $single_datepicker){

						if(!$wpdp_pro && $index > 0) break;

						$option = get_option($single_datepicker->option_name);

						$wp_datepicker = isset($option['wp_datepicker']) && $option['wp_datepicker'] ? $option['wp_datepicker'] : $single_datepicker->option_name;
						$img_name = isset($option['wpdp_options']['use_custom_style1']) && $option['wpdp_options']['use_custom_style1'] ? $option['wpdp_options']['use_custom_style1'] : 'default';
						$img_url = $wpdp_url.'pro/img/'.$img_name.'.jpg';

						$single_pro = new WPDP_SinglePro();
						$single_pro->setOptionId($single_datepicker->option_id);
						$single_pro->setOptionName($single_datepicker->option_name);
						$single_pro->setWpDatepicker($wp_datepicker);
						$single_pro->setImageUrl($img_url);

						if(!$wpdp_pro && $index == 0){

							$single_pro->setSingleSettingList($this->api_get_single_setting_list($single_datepicker->option_name));


						}else{

							$single_pro->setSingleSettingList(array());

						}


						$single_settings_list[] = $single_pro;


					}
				}
				$pro_settings->setLoginKey("valid");
            }




			$pro_settings->setIsPro($wpdp_pro ? "yes" : "no");
			$pro_settings->setSingleProList($single_settings_list);

			$res = new WP_REST_Response($pro_settings);
			return $res;
		}

		function api_update_settings($param){
            global $wpdp_gen_file;
			$login_key = $param['login_key'];
			$login_key_status = $this->validate_login_key($login_key);
			$update_epn_settings = array(

				'status' => 'not_ok',
                'login_key' => 'valid'

			);
			// if($login_key == base64_decode('MTIz')){
			if($login_key == $this->app_slug){


				$jsonSettings = $param['jsonSettings'];
				$optionName = $param['optionName'];
				$jsonSettings = json_decode($jsonSettings, true);

				$wpdp_options_default = get_option($optionName, array());
				if(empty($wpdp_options_default)){
					$wpdp_options_default = $this->default_array;
					$wpdp_options_default['wpdp_options'] = $this->options_array;
				}

				$wpdp_options = isset($wpdp_options_default['wpdp_options']) && is_array($wpdp_options_default['wpdp_options']) ? $wpdp_options_default['wpdp_options'] : array();


				if(!empty($jsonSettings)){

				    foreach ($jsonSettings as $index => $settings){

				        $setting_name =  $settings['name'];
				        $setting_value =  $settings['value'];
				        $type =  $settings['type'];







					    if($type == "checkbox"){


						    if($setting_value == "on" && !array_key_exists($setting_name, $wpdp_options)){
							    $wpdp_options[$setting_name] = "1";
						    }

						    if($setting_value == "off" && array_key_exists($setting_name, $wpdp_options)){
							    unset($wpdp_options[$setting_name]);
						    }

                        }else{

						    if(array_key_exists($setting_name, $wpdp_options_default)){

							    $wpdp_options_default[$setting_name] = $setting_value;

						    }elseif(array_key_exists($setting_name, $wpdp_options)){

							    $wpdp_options[$setting_name] = $setting_value;

						    }
					    }



                    }

					$wpdp_options_default['wpdp_options'] = $wpdp_options;
					$update = update_option($optionName, $wpdp_options_default);

					if($update){

                        if(function_exists('wpdp_generate_js_file')){

                            wpdp_generate_js_file();

                        }

                        if(function_exists('wpdp_generate_css_file')){

                            wpdp_generate_css_file();

                        }

						$update_epn_settings['status']  = 'OK';
					}

				}


			}else{

				$update_epn_settings['login_key'] = 'invalid';
			}

			$res = new WP_REST_Response($update_epn_settings);
			return $res;

		}

	
	
	}	


}