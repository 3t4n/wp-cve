<?php
	
	
	
	$src = $_POST["src"];


	//---------------------------------------------------------------------------
	//タグ除去
	//---------------------------------------------------------------------------
	$remove_tag = $src;

	//改行コード
	$n_tag = get_crlf_tag("n", $remove_tag);
	$r_tag = get_crlf_tag("r", $remove_tag);

	//改行コード置換
	$remove_tag = preg_replace("/\n/iu", $n_tag, $remove_tag);
	$remove_tag = preg_replace("/\r/iu", $r_tag, $remove_tag);

	//装飾系タグリスト
	$tag_list = array("strong","span","font","del","b","i","u");
	
	//装飾系タグ除去
	for($i=0; $i<count($tag_list); $i++) {
		$remove_tag = removeTag($tag_list[$i], $remove_tag);
	}

	//改行コードを元に戻す
	$remove_tag = preg_replace("/" .$n_tag. "/","\n", $remove_tag);
	$remove_tag = preg_replace("/" .$r_tag. "/","\r", $remove_tag);

	//タグを改行に変換
	$remove_tag = preg_replace("/<\/?[^>]+>/","\n", $remove_tag);

	//エスケープ文字リスト
	$escape_list = array("&nbsp;","&#160;","&lt;","&#60;","&gt;","&#62;","&laquo;","&#171;","&raquo;","&#187;","&quot;","&#34;","&apos;","&#39;","&copy;","&#169;","&reg;","&#174;","&minus;","&#8722;"
,"&ndash;","&#8211;","&mdash;","&#8212;","&#45;","&amp;","&#38;");
	//エスケープ文字を変換
	for($i=0; $i<count($escape_list); $i++) {
		$remove_tag = removeEscape($escape_list[$i], $remove_tag);
	}

	//2つ以上連続する改行をまとめる
	$remove_tag=preg_replace("/(\r\n){2,}|\r{2,}|\n{2,}/","\n",$remove_tag);

	$src = $remove_tag;

	//---------------------------------
	//装飾タグ削除
	function removeTag($tag, $str) {
		$ptn = '/<' . $tag . '(.*?)>(.*?)<\/' . $tag . '>/iu';
		$tmpStr = $str;

		//タグ削除（見つからなくなるまでループ）
		while (preg_match($ptn,$tmpStr)===1) {
			$tmpStr = preg_replace($ptn, "$2", $tmpStr);
		}
		$removeStr = $tmpStr;

		return $removeStr;
	}

	//改行置換用文字列
	function get_crlf_tag($ptn, $str) {
		$tmpStr = $str;
		$tmpPtn = $ptn;
		//置換文字列の存在チェック
		while (preg_match("/<><>" .$tmpPtn. "<><>/",$tmpStr)===1) {
			$tmpPtn .= $ptn;
		}
		return "<><>" .$tmpPtn. "<><>";
	}

	//エスケープ文字置換（改行に）
	function removeEscape($chr, $str){
		$ptn = '/' . $chr . '/iu';
		$tmpStr = $str;

		//タグ削除（見つからなくなるまでループ）
		while (preg_match($ptn,$tmpStr)===1) {
			$tmpStr = preg_replace($ptn, "\n", $tmpStr);
		}
		$removeStr = $tmpStr;

		return $removeStr;
	}

	//---------------------------------------------------------------------------



	
	$data = array(
		"src"=>$src
	);
	
	$data = http_build_query($data, "", "&");
	
	//header
	$header = array(
		"Content-Type: application/x-www-form-urlencoded",
		"Content-Length: " . strlen($data)
	);
	
	$context = array(
		"http" => array(
			"method"  => "POST",
			"header"  => implode("\r\n", $header),
			"content" => $data
		)
	);

	$url = "http://api.seo-composer.com/hpb_get_words/";
	echo file_get_contents($url, false, stream_context_create($context));
	
	
	
	
	
	
	
	//echo "POST<PRE>";
	//var_dump($_POST);
	//echo "</PRE>";
    //
	//echo "GET<PRE>";
	//var_dump($_GET);
	//echo "</PRE>";
?>
