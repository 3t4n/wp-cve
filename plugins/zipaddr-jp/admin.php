<?php
if( !defined('ABSPATH') ) exit;

function zipaddr_admin_menu(){                    // 設定メニュー下にサブメニューを追加
	add_menu_page( 'Zipaddr-JP', 'Zipaddr-JP', 'activate_plugins', zipaddr_KEYS, 'zipaddr_conf');
	add_submenu_page(zipaddr_KEYS, __('option','zipaddr-jp'),__('option','zipaddr-jp'), 'activate_plugins', zipaddr_KEYS, 'zipaddr_conf');
}
function zipaddr_radio($iname,$selected,$table){
$cdata1=  '<label><input name="{iname}" id="{iname}_{n}" type="radio" value="{key}"{select} />';
$cdata1.= '{data}</label><br />'."\n";
	$ans= "";
	$n= 0;
	foreach($table as $key => $data) {
		$select= ($key==$selected) ?  " checked" : "";
		$n++;
		$dat= str_replace("{iname}", $iname, $cdata1);
		$dat= str_replace("{n}",     $n,     $dat);
		$dat= str_replace("{select}",$select,$dat);
		$dat= str_replace("{key}",   $key,   $dat);
		$dat= str_replace("{data}",  $data,  $dat);
		$ans.= $dat;
	}
	return $ans;
}
function zipaddr_conf(){
	if( !empty($_GET['page']) && $_GET['page'] == zipaddr_KEYS ){
		if( isset($_POST['zipaddr_token']) && !empty($_POST['zipaddr_token']) ){
			check_admin_referer('zipaddrjp', 'zipaddrjp_def');
			$array= array();
			$flds= zipaddrjp_fld();
			foreach($flds as $i => $key){
				$keys= zipaddr_SYS.$key;          // sys_site
				$da= isset($_POST[$keys]) ?  $_POST[$keys] : "";
				if( $key=="plce" && $da=="&nbsp;" ) {;}
				else $da= htmlspecialchars($da,ENT_QUOTES,'UTF-8');
				$array[$keys]= $da;
				$$keys= $da;
			}
			if( $sys_site < "1" || "5" < $sys_site )    $array[zipaddr_SYS.'site']= "4";
			if( $sys_keta < "5" || "7" < $sys_keta )    $array[zipaddr_SYS.'keta']= "7";
//			if( $sys_site == "4" )                      $array[zipaddr_SYS.'keta']= "7";
			if( $sys_site == "2" )                      $array[zipaddr_SYS.'keta']= "5";
			else                                        $array[zipaddr_SYS.'keta']= "7";
			if( $sys_deli=="" || $sys_deli=="-" ){;}
			else                                        $array[zipaddr_SYS.'deli']= "-";
			if( !preg_match("/^[0-9\-]+$/",$sys_tate) ) $array[zipaddr_SYS.'tate']= "";
			if( !preg_match("/^[0-9\-]+$/",$sys_yoko) ) $array[zipaddr_SYS.'yoko']= "";
			if( !preg_match("/^[0-9\-]+$/",$sys_pfon) ) $array[zipaddr_SYS.'pfon']= "";
			if( !preg_match("/^[0-9\-]+$/",$sys_sfon) ) $array[zipaddr_SYS.'sfon']= "";
			if( $sys_dyna=="" || $sys_dyna=="1" ){;}
			else                                        $array[zipaddr_SYS.'dyna']= "";
			$array[zipaddr_SYS.'parm']= str_replace(",", "|", $sys_parm);
			$param= serialize($array);
			update_option(zipaddr_DEFINE,$param); // 定義情報を更新する
			$mesg= "稼働環境を設定しました。";
		}
		else $mesg= "【稼働環境の設定】";

		$flds= zipaddrjp_fld(); foreach($flds as $i => $key){$keys=zipaddr_SYS.$key; $$keys="";}
		$param= unserialize( get_option(zipaddr_DEFINE) ); // get定義情報
		foreach((array)$param as $key => $data){
			$da= ($data=="&nbsp;") ?  $data : htmlspecialchars($data,ENT_QUOTES,'UTF-8');
			$$key= $da;
		}
		if( empty($sys_site) ) $sys_site= "4";
		if( empty($sys_keta) ) $sys_keta= "7";
		if( $sys_site == "1" ) $sys_site= "4";
		if( $sys_keta < 5 ||  7 < $sys_keta ) $sys_keta= "7";
		if( $sys_pfon < 9 || 25 < $sys_pfon ) $sys_pfon= "";
		if( $sys_sfon < 9 || 25 < $sys_sfon ) $sys_sfon= "";
		$sit= array("4" => "商用版サイト（default）","5" => "商用版サイト（zipaddra.js）","2" => "有償版ガイダンス有り","3" => "有償版ガイダンス無し");
		$gid= array(""  => "表示（default）","1" => "非表示");
		$site= zipaddr_radio("sys_site", $sys_site, $sit);
		$gide= zipaddr_radio("sys_gide", $sys_gide, $gid);
		$param= str_replace("|", ",", $sys_parm);
		$token= zipaddrjp_str();                  // token
?>
<h2><?php zipaddr_e($mesg) ?>（zipaddr-jp）V<?php zipaddr_e(zipaddr_VERS) ?></h2>
<form id="zipaddr-conf" method="post" action="">
<table border="1" cellspacing="0" cellpadding="8" summary=" ">
    <tr><td colspan="2" width="90" bgcolor="#f3f3f3">▼郵番DBの稼働環境選択（<span style="color: #ff0000;">※</span>：必須）</td>
    </tr>
    <tr ><td bgcolor="#f3f3f3">利用サイト<span style="color: #ff0000;">※</span></td>
        <td><?php echo($site) ?></td>
    </tr>
    <tr><td bgcolor="#f3f3f3">郵便番号の区切り文字</td>
        <td><input type="text" name="sys_deli" size="5" maxlength="1" value="<?php zipaddr_e($sys_deli) ?>" style="ime-mode:disabled;" />　（default: '-'）</td>
    </tr>
    <tr><td bgcolor="#f3f3f3">ガイダンス位置の補正</td>
        <td>
縦：<input type="text" name="sys_tate" size="5" maxlength="4" value="<?php zipaddr_e($sys_tate) ?>" style="ime-mode:disabled;" />　（default: 18）<br />
横：<input type="text" name="sys_yoko" size="5" maxlength="4" value="<?php zipaddr_e($sys_yoko) ?>" style="ime-mode:disabled;" />　（default: 22）
        </td>
    </tr>
    <tr><td bgcolor="#f3f3f3">ガイダンス画面の文字サイズ</td>
        <td>
PC：<input type="text" name="sys_pfon" size="5" maxlength="4" value="<?php zipaddr_e($sys_pfon) ?>" style="ime-mode:disabled;" />　（default: 12）<br />
SF：<input type="text" name="sys_sfon" size="5" maxlength="4" value="<?php zipaddr_e($sys_sfon) ?>" style="ime-mode:disabled;" />　（default: 20）
        </td>
    </tr>
    <tr ><td bgcolor="#f3f3f3">フッター表示オプション<br />（@ZipAddr_xx部分）</td>
        <td><?php echo($gide) ?></td>
    </tr>
    <tr><td bgcolor="#f3f3f3">placeholder上書（m:住所自動入力）</td>
        <td><input type="text" name="sys_plce" size="25" maxlength="50" value="<?php zipaddr_e($sys_plce) ?>" /></td>
    </tr>
    <tr><td bgcolor="#f3f3f3">選択後にフォーカスするid名</td>
        <td><input type="text" name="sys_focs" size="25" maxlength="50" value="<?php zipaddr_e($sys_focs) ?>" /></td>
    </tr>
    <tr><td bgcolor="#f3f3f3">システム拡張用のAP識別子</td>
        <td><input type="text" name="sys_syid" size="25" maxlength="50" value="<?php zipaddr_e($sys_syid) ?>" /></td>
    </tr>
    <tr><td bgcolor="#f3f3f3">htmlの動的生成（1:有効）</td>
        <td><input type="text" name="sys_dyna" size="25" maxlength="50" value="<?php zipaddr_e($sys_dyna) ?>" /></td>
    </tr>
    <tr><td bgcolor="#f3f3f3">オウンコード設定パラメータ</td>
        <td><input type="text" name="sys_parm" size="25" maxlength="255" value="<?php zipaddr_e($param) ?>" /><br />例：bgc=#3366ff,rtrs=</td>
    </tr>
    <tr><td bgcolor="#f3f3f3">無条件挿入（;区切でurlのkeyword）</td>
        <td><input type="text" name="sys_drct" size="25" maxlength="50" value="<?php zipaddr_e($sys_drct) ?>" placeholder="例：/contact" /></td>
    </tr>
</table>
<br />
▼郵便番号DBの稼働場所は、次の2系統があります。<br />
　商用版サイト： <?php zipaddr_e(zipaddr_COM) ?> 系<br />
　有償版サイト： <?php zipaddr_e(zipaddr2COM) ?> 系<br />
※有償版は利用申請をしないと動きません。<br />
※有償版のご利用には別途、<a href="<?php zipaddr_e(zipaddr2COM) ?>use/" target="_blank">利用申請（有償）</a> が必要となります。<br />
▼[システム拡張AP識別子（'_'区切り）]<br />
▼自動判定：WooCommerce、Trust Form、Ninja Forms、WP-Members、WPForms、Visual Form Builder<br />
　　　　　　Contact Form 7、MW WP Form、Welcart、Mailform Pro<br />
<div class="btn-area">
	<ul><li>
		<input type="hidden" name="zipaddr_token" value="<?php zipaddr_e($token) ?>" />
		<?php wp_nonce_field( 'zipaddrjp', 'zipaddrjp_def' ); ?>
		<input name="submit" id="submit" class="button button-primary" type="submit" value="この内容で登録する" />
	</li></ul>
</div>
</form>
<?php
	}
}

function zipaddr_uninstall() {delete_option(zipaddr_DEFINE);} // uninstall処理
?>
