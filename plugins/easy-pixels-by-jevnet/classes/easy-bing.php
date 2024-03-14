<?php
class jn_easyBingAds extends Easypixels_network
{
	public $code='';

	function __construct(){	parent::__construct();}

	function putAdminOptions()
	{
		echo '
          <table class="form-table">
          <tr>
               <th>'.__('Enable Bing Ads tracking','easy-pixels-by-jevnet').'</th><td style="width:3em"><input type="checkbox" id="jn_EPBingAds_enable" name="jn_EPBingAds_enable" '.$this->checkboxSetting().'></td>
               <td><input value="'.$this->code.'" type="text" id="jn_EPBingAds_cid" name="jn_EPBingAds_cid" placeholder="01234567"></td>
          </tr>
          </table>';
	}

	public function putTrackingCode()
	{
		if(($this->enabled)&&($this->code!=''))
		{
			echo '<script>(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:"'.$this->code.'"};o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,"script","//bat.bing.com/bat.js","uetq");</script><noscript><img src="//bat.bing.com/action/0?ti='.$this->code.'&Ver=2" height="0" width="0" style="display:none; visibility: hidden;" /></noscript>';
		}else{return false;}
	}

	static function sanitizeCode($code='')
	{
		$code=sanitize_text_field($code);
		$code=(preg_match('/^[0-9]*$/', $code))?$code:'';
		return $code;
	}
}