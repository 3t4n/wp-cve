<?php
class jn_easyGAds extends Easypixels_network
{
	private $phoneNumber='';
	private $GFC_conversionLabel='';
	private $remarketing=true;
	private $WCLabel='';

	function setRemarketing($enabled=false){if(is_bool($enabled)){$this->remarketing=$enabled;}}
	function setPhoneNumber($number){$this->phoneNumber=sanitize_text_field($number);}
	function setGFC_conversionLabel($code){$this->GFC_conversionLabel=self::sanitizeGAlabel($code);}
	function getLabel(){return $this->WCLabel;}
	function setLabel($label){$this->WCLabel=sanitize_text_field($label);}

	public function checkboxSetting($setting=false){return ($setting)?' checked="checked"':'';}

	public function putAdminOptions()
	{
		echo '
		<table class="form-table">
			<tr>
				<th>'.__('Enable Google Ads tracking','easy-pixels-by-jevnet').'</th>
				<td style="width:3em"><input type="checkbox" onclick="jn_EPADW_ADWoptionsToggle();" id="jn_EPGADW_CF7_enable" name="jn_EPGADW_CF7_enable" '.$this->checkboxSetting($this->enabled).'></td>
				<td></td>
			</tr>
		</table>';
		echo '
		<div id="ADWoptionsDropdown" style="margin-left:1em;background:#fefefe;padding:1em;box-shadow:0 0 2px #666">
		<h3>Google Ads Options</h3>
		<table class="form-table">
			<th>'.__('Google Ads tracking ID','easy-pixels-by-jevnet').'</th>
			<td><input value="'.$this->code.'" type="text" id="jn_EPGADW_cid" name="jn_EPGADW_cid" placeholder="AW-012345678" pattern="AW-[0-9]+"></td>
		</table>
		<table class="form-table">
			<th>'.__('Enable Remarketing','easy-pixels-by-jevnet').'</th>
			<td><input type="checkbox" id="jn_EPGADW_remarketing" name="jn_EPGADW_remarketing" '.$this->checkboxSetting($this->remarketing).'></td>
		</table>
		<h4>'.__('Google Forwarding Call','easy-pixels-by-jevnet').'</h4>
		<table class="form-table">
			<tr>
				<th>'.__('Phone Number','easy-pixels-by-jevnet').'</th>
				<td><input value="'.$this->phoneNumber.'" type="text" id="jn_EPGADW_phoneNumber" name="jn_EPGADW_phoneNumber" placeholder="555 555 555"></td>
				<th>'.__('Conversion label','easy-pixels-by-jevnet').'</th>
				<td><input value="'.$this->GFC_conversionLabel.'" type="text" id="jn_EPGADW_GFC_conversionLabel" name="jn_EPGADW_GFC_conversionLabel" placeholder="XXXXXXXXXXXX"></td>';
		if(($this->code!='')&&($this->GFC_conversionLabel!='')&&($this->phoneNumber!=''))
		{
			echo '<td><a href="'.get_site_url().'#google-wcc-debug" target="_blank">'.__("Test Forwarding Call",'easy-pixels-by-jevnet').'</a></td>';
		}
			echo '</tr>
		</table></div>';
		echo '<script>function jn_EPADW_ADWoptionsToggle(){document.getElementById("ADWoptionsDropdown").style.display=(document.getElementById("jn_EPGADW_CF7_enable").checked)?"block":"none"}jn_EPADW_ADWoptionsToggle();</script>';
	}

	public function putTrackingCode()
	{
		if(($this->enabled)&&($this->code!=''))
		{
			if(!$this->remarketing)
			{
				echo "gtag('set', 'allow_ad_personalization_signals', false);";
			}
			echo "gtag('config', '".$this->code."');";

		}else{return false;}
	}

	public function putForwardingCall()
	{
		if(($this->enabled)&&($this->code!='')&&($this->GFC_conversionLabel!='')&&($this->phoneNumber!=''))
		{
			echo "gtag('config', '".$this->code."/".$this->GFC_conversionLabel."', {'phone_conversion_number': '".$this->phoneNumber."'});";
		}else{return false;}
	}
	


	static function sanitizeCode($theCode='')
	{
		if(is_numeric($theCode)){$theCode='AW-'.$theCode;}
		$theCode=sanitize_text_field(strtoupper($theCode));
		$theCode=(preg_match('/AW\-[0-9]*/', $theCode))?$theCode:'';
		return $theCode;
	}

	static function sanitizeGAlabel($theLabel='')
	{
		$theLabel=(isset($theLabel))?sanitize_text_field($theLabel):sanitize_text_field($_POST["jn_EPGADW_GFC_conversionLabel"]);
		if((strpos($theLabel,'/')>0)&&(strpos(strtoupper($theLabel),'AW-')==0)){$theLabel=substr($theLabel, strpos($theLabel,'/')+1);}
		return preg_replace('/[^\w]/', '', $theLabel);
	}
}