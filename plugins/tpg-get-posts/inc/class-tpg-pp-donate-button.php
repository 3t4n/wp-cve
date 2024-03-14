<?php
/*
 *	donation for TPG
 *
 *  generates the html to place a donate button on a webpage
 *  
 * Usage:
 *	$ppb = new paypal_donate_button;
 *	$ppb->set_var("for_text","wordpress plugin");
 *	$ppg->set_var("desc","descript of ask");
 *	$pp_btn = $ppg->gen_donate_button();
 * Styling:
 *	#donate-button-div {
 *		margin: 0 .5em 0 1em;
 *		width: 40%;
 *		border:#FFCC00 2px;
 * 		float: right;
 *	}
 *	#donate-desc {
 *		font-size:small;
 *		border:thin;
 *		padding: .5em .5em .5em .5em;
 *	}
 *	#donate-button {
 *		text-align:center;
 *	}
 *
 */
class tpg_pp_donate_button	{
	
	private $acct_id ="-----BEGIN PKCS7-----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-----END PKCS7-----
";
	private $for_text="service name";               //product or service description
	private $desc="the ask";						//test above button
	
	public function __construct() {
		//initialize flds
		$this->for_text = "";
		$this->desc = "";
	}
	
	/*
	 *	set variable
	 *
	 * @package WordPress
	 * @subpackage tpg-get-posts
	 * @since 1.3
	 *
	 * set a value in a variable
	 * 
	 * @param	string	variable name
	 * @param 	string	value
	 * @return	bool
	 *
	 */
	public function set_var($_var,$_val) {
		// var must exist; dynamic var not allowed
		if (array_key_exists($_var, get_class_vars(__CLASS__))) {
			$this->$_var = $_val;
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 *	generate a donate button 
	 *
	 * @package WordPress
	 * @subpackage tpg-get-posts
	 * @since 1.3
	 *
	 * generate a donate buton
	 * 
	 * @param	array	$links
	 * @param 	 		$file
	 * @return	array	$links
	 *
	 */
	public function gen_donate_button() {
		$button_code  = '<div id="donate-button-div">';
		if ($this->desc != '') {
			$button_code .= '<div id="donate-desc">'.$this->desc.'</div>';
		}
		$button_code .= '<div id="donate-button"><form action="https://www.paypal.com/cgi-bin/webscr" method=post>
	<input type=hidden name=cmd value=_s-xclick>
	<input type=hidden name=encrypted value="'.$this->acct_id.'">';
		if ($this->for_text != '') {
			$button_code .= '<input type=hidden name="item_name" value="'.$this->for_text.'">';
		}
		$button_code .= '<input type=image src="https://www.paypalobjects.com/WEBSCR-640-20110306-1/en_US/i/btn/btn_donateCC_LG.gif" border=0 name=submit alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border=0 src="https://www.paypalobjects.com/WEBSCR-640-20110306-1/en_US/i/scr/pixel.gif" width=1 height=1>
	</form></div>	</div>';
	return $button_code;
	}
}
?>