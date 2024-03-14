<?php
class jn_Analytics extends Easypixels_network
{
	private $wp_user_id=false;

	public function putAdminOptions()
	{
		echo '
          <table class="form-table">
          <tr>
            	<th>'.__('Enable Analytics','easy-pixels-by-jevnet').'</th>
            	<td style="width:3em"><input type="checkbox" id="jn_EPGA_enable" name="jn_EPGA_enable"'.$this->checkboxSetting('enabled').'></td>
				<td><input value="'.$this->code.'" type="text" id="jn_EPGA_code" name="jn_EPGA_code" placeholder="UA-XXXXXXXX-X / G-XXXXXXXXX" pattern="((UA-[0-9]+-[0-9]+)|(G-[A-Za-z0-9]*))"><!-- <input type="checkbox" id="jn_EPGA_overwriteUserId" name="jn_EPGA_overwriteUserId"'.$this->checkboxSetting('wp_user_id').'>'.__('If user is logged in overwrite session ID','easy-pixels-by-jevnet').' --></td>
				<th></th>
          </tr>
     </table>';
	}

	public function putTrackingCode()
	{
		if($this->code!='')
		{
			if(($this->wp_user_id)&&(get_current_user_id()!==0))
			{
				$userid="{'user_id': '".get_current_user_id()."'}";
				echo "gtag('config', '".$this->code."',".$userid.");";
/*				echo "gtag('set', 'dimension1','USER_ID');"; */
			}
			else
			{
				echo "gtag('config', '".$this->code."');";
			}
		}
	}

	static function sanitizeCode($code='')
	{
		$code=sanitize_text_field(strtoupper($code));
		$code=(preg_match('/((UA\-[0-9]+\-[0-9]+)|(G\-[A-Za-z0-9]*))/', $code))?$code:'';
		return $code;
	}
}
?>