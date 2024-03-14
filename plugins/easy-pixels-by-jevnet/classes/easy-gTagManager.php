<?php
class jn_easyGTagManager extends Easypixels_network
{

	function __construct(){parent::__construct();}


	public function checkboxSetting($setting=false)
	{
		return ($setting)?' checked="checked"':'';
	}

	public function putAdminOptions()
	{
		echo '
          <table class="form-table">
          <tr>
               <th>'.__('Enable Google Tag Manager','easy-pixels-by-jevnet').'</th>
               <td style="width:3em"><input type="checkbox" id="jn_EPGTM_enable" name="jn_EPGTM_enable"'.$this->checkboxSetting($this->enabled).'></td>
				<td><input value="'.$this->code.'" type="text" id="jn_EPGTM_code" name="jn_EPGTM_code" placeholder="GTM-XXXXXXX"></td>
          </tr>
     </table>';
	}

	public function putTrackingCode()
	{
		if(($this->enabled)&&($this->code!=''))
		{
			echo "<!-- Easy Pixels: Google Tag Manager --><script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','".$this->code."');</script><!-- Easy Pixels: End Google Tag Manager -->";
		}else{return false;}
	}

	static function sanitizeCode($code='')
	{
		if(strpos($code,'GTM-')!==0){$code='GTM-'.$code;}
		$code=sanitize_text_field(strtoupper($code));
		$code=(preg_match('/GTM\-[0-9]*/', $code))?$code:'';
		return $code;
	}
}