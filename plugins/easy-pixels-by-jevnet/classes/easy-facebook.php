<?php
class jn_Facebook extends Easypixels_network
{
	private $isSet=false;

	public function __construct(){parent::__construct();}

	public function putAdminOptions()
	{
//		var_dump($this);
//		exit;
		echo '
          <table class="form-table">
          <tr>
				<th><img src="'.JN_EasyPixels_URL.'/img/fb.png" alt="Facebook" width="20px" style="float:left"><span style="margin-left:.5em"> Facebook</span></th><td style="width:2em"><input type="checkbox" id="jn_EPFBtrack_enable" name="jn_EPFBtrack_enable" '.$this->checkboxSetting().'></td>
				<td><input value="'.$this->code.'" type="text" id="jn_EPFBtrack_code" name="jn_EPFBtrack_code"></td>
          </tr>
          </table>';
	}

	public function putTrackingCode()
	{
		if(($this->enabled)&&($this->code!='')&&(!$this->isSet))
		{
			echo '<script type="text/javascript">!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version=\'2.0\';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,\'script\',\'https://connect.facebook.net/en_US/fbevents.js\'); fbq(\'init\', \''.$this->code.'\');fbq(\'track\', \'PageView\');</script><noscript><img height="1" width="1" src="https://www.facebook.com/tr?id='.$this->code.'&ev=PageView&noscript=1"/></noscript>';
			$this->isSet=true;
		}else{return false;}
	}
/*
	static public function save($WP_settings_group='jnEasyPixelsSettings-group')
	{
		if(isset($_POST["jn_EPFBtrack_code"]))
		{
			$socialSettings=get_option('jn_EP_Social');

			$socialSettings["fb_enabled"]=((isset($_POST["jn_EPFBtrack_enable"]))&&($_POST["jn_EPFBtrack_enable"]=='on'));
			$socialSettings["fb_code"]=self::sanitizeCode($_POST["jn_EPFBtrack_code"]);
			update_option('jn_EP_Social', $socialSettings);
		}
	}
*/
	static function sanitizeCode($theLabel='')
	{
		$theLabel=sanitize_text_field($theLabel);
		$theLabel=(preg_match('/^[0-9]*$/', $theLabel))?$theLabel:'';
		return $theLabel;
	}
}