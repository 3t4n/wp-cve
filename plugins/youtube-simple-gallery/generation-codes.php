<?php 
	/** Codigo base utilizado => http://webdeveloperswall.com/php/generate-youtube-embed-code-from-url **/
	function ysg_extractUTubeVidId($url){
		$vid_id = "";
		$flag = false;
		if(isset($url) && !empty($url)){
			$parts = explode("?", $url);
			if(isset($parts) && !empty($parts) && is_array($parts) && count($parts)>1){
				$params = explode("&", $parts[1]);
				if(isset($params) && !empty($params) && is_array($params)){
					foreach($params as $param){
						$kv = explode("=", $param);
						if(isset($kv) && !empty($kv) && is_array($kv) && count($kv)>1){
							if($kv[0]=='v'){
								$vid_id = $kv[1];
								$flag = true;
								break;
							}
						}
					}
				}
			}
			if(!$flag){
				$needle = "youtu.be/";
				$pos = null;
				$pos = strpos($url, $needle);
				if ($pos !== false) {
					$start = $pos + strlen($needle);
					$vid_id = substr($url, $start, 11);
					$flag = true;
				}
			}
		}
		return $vid_id;
	}
	
	function ysg_youtubeEmbedFromUrl($youtube_url){
		$vid_id = ysg_extractUTubeVidId($youtube_url);
		return ysg_generateYoutubeEmbedCode($vid_id);
	}
	
	function ysg_generateYoutubeEmbedCode($vid_id){
		$html = $vid_id;
		return $html;
	}
	
	/*
	Code Original => webdeveloperswall.com
	function youtubeEmbedFromUrl($youtube_url, $width, $height){
		$vid_id = extractUTubeVidId($youtube_url);
		return generateYoutubeEmbedCode($vid_id, $width, $height);
	}
	function generateYoutubeEmbedCode($vid_id, $width, $height){
		$w = $width;
		$h = $height;
		$html = '<iframe width="'.$w.'" height="'.$h.'" src="http://www.youtube.com/embed/'.$vid_id.'?rel=0" frameborder="0" allowfullscreen></iframe>';
		return $html;
	}
	Exemple :
	$embed_code = youtubeEmbedFromUrl("http://www.youtube.com/watch?v=UzifCbU_gJU");
	<iframe width="400" height="300" src="http://www.youtube.com/embed/'.$embed_code.'?rel=0" frameborder="0" allowfullscreen></iframe>
	<img src="http://img.youtube.com/vi/'.$embed_code.'/mqdefault.jpg" alt="" title="" />
	*/