<?php
class jn_Analytics_v1_90
{
	private $code='';
	private $enabled=false;
	private $wp_user_id=false;

	function is_enabled(){return $this->enabled;}
	function getCode(){return $this->code;}

	public function __construct()
	{
		$settings=get_option('jn_EPGA');
		if($settings!=false)
		{
			$this->enabled=$settings["enabled"];
			$this->code=$settings["code"];
			$this->wp_user_id=(isset($settings["wp_user_id"]))?$settings["wp_user_id"]:false;
		}
		else
		{
			$this->enabled=get_option('jn_EPGA_enable',false);
			$this->code=get_option('jn_EPGA_code','');
		}
	}
}

class jn_easyBingAds_v1_90
{
	private $code='';
	private $enabled='off';

	function is_enabled(){return $this->enabled;}
	function getCode(){return $this->code;}

	public function __construct()
	{
		$settings=get_option('jn_BingAds','');
		if($settings)
		{
			$this->code=$settings["cid"];
			$this->enabled=$settings["enabled"];
		}
	}

}

class jn_Facebook_v1_90
{
	private $code='';
	private $enabled=false;
	private $isSet=false;

	function is_enabled(){return $this->enabled;}
	function getCode(){return $this->code;}

	public function __construct()
	{

		$socialSettings=get_option('jn_EP_Social');
		if($socialSettings)
		{
			$this->code=$socialSettings["fb_code"];
			$this->enabled=$socialSettings["fb_enabled"];
		}

	}
}

class jn_easyGAds_v1_90
{
	private $code='';
	private $enabled=false;
	private $phoneNumber='';
	private $GFC_conversionLabel='';
	private $remarketing=true;
	private $WCLabel='';

	function is_enabled(){return $this->enabled;}
	function getCode(){return $this->code;}
	function getGFC_conversionLabel(){return $this->GFC_conversionLabel;}
	function getRemarketing(){return $this->remarketing;}
	function getWCLabel(){return $this->WCLabel;}
	function getPhoneNumber(){return $this->phoneNumber;}

	public function __construct()
	{
		$settings=get_option('jn_EPADW','');
		if($settings)
		{
			$this->code=isset($settings["code"])?$settings["code"]:'';
			$this->enabled=isset($settings["enabled"])?$settings["enabled"]:false;
			$this->phoneNumber=isset($settings["phoneNumber"])?$settings["phoneNumber"]:'';
			$this->GFC_conversionLabel=isset($settings["GFC_conversionLabel"])?$settings["GFC_conversionLabel"]:'';
			$this->remarketing=isset($settings["remarketing"])?$settings["remarketing"]:true;
			$this->WCLabel=isset($settings["WCLabel"])?$settings["WCLabel"]:'';
		}
	}
}

class jn_easyGTagManager_v1_90
{
	private $code='';
	private $enabled=false;

	function is_enabled(){return ($this->enabled);}
	function getCode(){return $this->code;}

	public function __construct()
	{
		$settings=get_option('jnep_GoogleEPconfig','');
		if($settings)
		{
			$this->enabled=isset($settings["googleGTMenabled"])?$settings["googleGTMenabled"]:false;
			$this->code=isset($settings["googleGTMcode"])?$settings["googleGTMcode"]:'';
		}
	}
}

class jn_easypixels_LinkedIn_v1_90
{
	private $code='';
	private $enabled=false;

	function is_enabled(){return $this->enabled;}
	function getCode(){return $this->code;}

	public function __construct()
	{
		$settings=get_option('jn_EPLinkedIn','');
		if($settings)
		{
			$this->code=$settings["code"];
			$this->enabled=$settings["enabled"];
		}
	}
}

class jn_easypixels_Twitter_v1_90
{
	private $code='';
	private $enabled=false;

	function is_enabled(){return $this->enabled;}
	function getCode(){return $this->code;}

	public function __construct()
	{
		$settings=get_option('jn_EPTW','');
		if($settings)
		{
			$this->code=$settings["code"];
			$this->enabled=$settings["enabled"];
		}
	}
}

class jn_easypixels_Yandex_v1_90
{
	private $code='';
	private $enabled=false;
	
	function is_enabled(){return $this->enabled;}
	function getCode(){return $this->code;}

	public function __construct()
	{
		$settings=get_option('jn_EPYandex','');
		if($settings)
		{
			$this->code=$settings["code"];
			$this->enabled=$settings["enabled"];
		}
	}
}