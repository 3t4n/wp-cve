<?php
class jn_easypixels
{
	private $dontTrackAdminUsers='';
	private $version='212';
	public $trackingOptions;

	
	public function __construct()
	{
		$settings=get_option('jn_easypixels');
		if($settings!=false)
		{
			$this->dontTrackAdminUsers=(isset($settings["dontTrackAdminUsers"]))?$settings["dontTrackAdminUsers"]:false;

			// Not defined. Update before v.2.00
			if(!isset($settings["version"]))
			{
				if(!function_exists('update_to_v200'))
				{
					include(JN_EasyPixels_PATH."/utils/update.php");
					update_to_v200();
					$settings=get_option('jn_easypixels');
				}
			}
			$this->version=(isset($settings["version"]))?$settings["version"]:'';
			$this->trackingOptions=$this->getSettings($settings,$this->getLanguageCode());
		}
		else{
			// Not defined. Update before v.2.00
			if(!function_exists('update_to_v200'))
			{
				include(JN_EasyPixels_PATH."/utils/update.php");
				update_to_v200();
			}
			$this->trackingOptions=new jnep_trackingCodes();
		}

	}


	private function getSettings($settings=null,$lang='')
	{
		if((isset($settings[get_option( 'siteurl' )])) && (isset($settings[get_option( 'siteurl' )][$lang])))
		{
			return $this->objectToObject($settings[get_option( 'siteurl' )][$lang]);
		}
		else
		{
			return new jnep_trackingCodes();
		}
	}

	private function getLanguageCode()
	{
		if(defined('ICL_LANGUAGE_CODE')){return ICL_LANGUAGE_CODE;}
		else {return get_locale();}

	}

	private function objectToObject($instance, $className='jnep_trackingCodes') {
	    return unserialize(sprintf('O:%d:"%s"%s',strlen($className),$className,strstr(strstr(serialize($instance), '"'), ':')  ));
	}


	public function getVersion(){return $this->version;}
	public function trackAdminUsers(){return (!$this->dontTrackAdminUsers);}

	private function checkboxSetting($property){return ($this->$property)?' checked="checked"':'';}

	public function putAdminOptions()
	{
		echo '<table style="width:100%;margin: 2% 0;border:1px solid #aaa;background:#fefefe;"><tr><td style="padding:1em;font-size:12px"><input type="hidden" value="" name="epform"><input type="checkbox" id="jn_EP_enableSpecialTracking" name="jn_EP_enableSpecialTracking"'.$this->checkboxSetting('dontTrackAdminUsers').'>'.__("Don't track admin and editor users",'easy-pixels-by-jevnet').'</td></tr></table>';
	}


	static public function get_plugin_version()
	{
		if(is_admin())
		{
			$plugin_data = get_plugin_data( __FILE__ );
			$plugin_version = $plugin_data['Version'];
			return $plugin_version;
		}
	}

	public function save($WP_settings_group='jnEasyPixelsSettings-group',$update=false,$lang='')
	{
		if(isset($_POST["epform"]))
		{
			$settings=get_option('jn_easypixels');
			if($settings==false){$settings=[];}
			$settings["dontTrackAdminUsers"]=(isset($_POST["jn_EP_enableSpecialTracking"]));
			$settings["version"]=$this->version;

			$this->trackingOptions->analytics->setCode($_POST["jn_EPGA_code"]);
			$this->trackingOptions->analytics->enable(isset($_POST["jn_EPGA_enable"]));

			$this->trackingOptions->bing->setCode($_POST["jn_EPBingAds_cid"]);
			$this->trackingOptions->bing->enable(((isset($_POST["jn_EPBingAds_enable"]))&&($_POST["jn_EPBingAds_enable"]=='on')));

			$this->trackingOptions->facebook->setCode($_POST["jn_EPFBtrack_code"]);
			$this->trackingOptions->facebook->enable(((isset($_POST["jn_EPFBtrack_enable"]))&&($_POST["jn_EPFBtrack_enable"]=='on')));

			$this->trackingOptions->gads->setCode($_POST["jn_EPGADW_cid"]);
			$this->trackingOptions->gads->enable((isset($_POST["jn_EPGADW_CF7_enable"])));
			$this->trackingOptions->gads->setRemarketing(isset($_POST["jn_EPGADW_remarketing"]));
			$this->trackingOptions->gads->setPhoneNumber($_POST["jn_EPGADW_phoneNumber"]);
			$this->trackingOptions->gads->setGFC_conversionLabel($_POST["jn_EPGADW_GFC_conversionLabel"]);

			$this->trackingOptions->gtm->setCode($_POST["jn_EPGTM_code"]);
			$this->trackingOptions->gtm->enable((isset($_POST["jn_EPGTM_enable"])));

			$this->trackingOptions->linkedin->setCode($_POST["jn_EPLinkedIn_Track_code"]);
			$this->trackingOptions->linkedin->enable(isset($_POST["jn_EPLinkedIn_Track_enable"]));

			$this->trackingOptions->twitter->setCode($_POST["jn_EPTwTrack_code"]);
			$this->trackingOptions->twitter->enable(isset($_POST["jn_EPTwTrack_enable"]));

			$this->trackingOptions->yandex->setCode($_POST["jn_EPYandex_code"]);
			$this->trackingOptions->yandex->enable(isset($_POST["jn_EPYandex_enable"]));

			if(!isset($settings[get_option( 'siteurl' )])){$settings[get_option( 'siteurl' )]=[];}
			$settings[get_option( 'siteurl' )][$this->getLanguageCode()]=$this->trackingOptions;
			update_option('jn_easypixels', $settings);
		}
		else
		{
			if(isset($_POST["epWCform"]))
			{
				$settings=get_option('jn_easypixels');
				if($settings==false){$settings=[];}
				$settings["version"]=$this->version;
				$this->trackingOptions->gads->setLabel($_POST["jn_GADW_WCLabel"]);
				if(!isset($settings[get_option( 'siteurl' )])){$settings[get_option( 'siteurl' )]=[];}
				$settings[get_option( 'siteurl' )][$this->getLanguageCode()]=$this->trackingOptions;
				update_option('jn_easypixels', $settings);
			}
			if($update)
			{
				$settings=get_option('jn_easypixels');
				if($settings==false){$settings=[];}
				if(!isset($settings[get_option( 'siteurl' )])){$settings[get_option( 'siteurl' )]=[];}
				if($lang!=''){$settings[get_option( 'siteurl' )][$lang]=$this->trackingOptions;}
				else{$settings[get_option( 'siteurl' )][$this->getLanguageCode()]=$this->trackingOptions;}
				update_option('jn_easypixels', $settings);
			}
		}
	}


}

class Easypixels_network
{
	protected $code='';
	protected $enabled=false;
	private $wp_user_id=false;

	function __construct(){}

	function setCode($code=''){$this->code=self::sanitizeCode($code);}
	function enable($status=false){$this->enabled=(is_bool($status))?$status:$this->enabled;}

	public function is_enabled(){return $this->enabled;}
	public function getCode(){return $this->code;}

	protected function checkboxSetting(){return ($this->is_enabled())?' checked="checked"':'';}

	static function sanitizeCode($code=''){return sanitize_text_field(strtoupper($code));}

	public function __call($method, $args)
    {
        if (isset($this->$method)) {
//            $func = $this->$method;
            return call_user_func_array($this->{$method}->bindTo($this),$args);
//            return call_user_func_array($func, $args);
        }
    }
}

class jnep_trackingCodes
{
	public $analytics;
	public $gads;
	public $facebook;
	public $gtm;
	public $twitter;
	public $bing;
	public $yandex;
	public $linkedin;

	function __construct()
	{
		$this->analytics=new jn_Analytics();
		$this->gads=new jn_easyGAds();
		$this->facebook=new jn_Facebook();
		$this->gtm=new jn_easyGTagManager();
		$this->twitter=new jn_easypixels_Twitter();
		$this->bing=new jn_easyBingAds();
		$this->yandex=new jn_easypixels_Yandex();
		$this->linkedin=new jn_easypixels_LinkedIn();
	}

	public function setAnalyticsConfig($config=null)
	{
		if((!is_null($config))&&(get_class($config)=='jn_Analytics')){$this->analytics=$config;}
	}
	
	public function setFacebookConfig($config=null)
	{
		if((!is_null($config))&&(get_class($config)=='jn_Facebook')){$this->facebook=$config;}
	}

	public function setGTMConfig($config=null)
	{
		if((!is_null($config))&&(get_class($config)=='jn_easyGTagManager')){$this->gtm=$config;}
	}

	public function setTwitterConfig($config=null)
	{
		if((!is_null($config))&&(get_class($config)=='jn_easypixels_Twitter')){$this->twitter=$config;}
	}

	public function setBingConfig($config=null)
	{
		if((!is_null($config))&&(get_class($config)=='jn_easyBingAds')){$this->bing=$config;}
	}

	public function setYandexConfig($config=null)
	{
		if((!is_null($config))&&(get_class($config)=='jn_easypixels_Yandex')){$this->yandex=$config;}
	}

	public function setLinkedinConfig($config=null)
	{
		if((!is_null($config))&&(get_class($config)=='jn_easypixels_LinkedIn')){$this->linkedin=$config;}
	}
}