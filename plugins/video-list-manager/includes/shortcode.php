<?php 
/**
 * Register a shortcode
 */
add_shortcode('tnt_video_list', 'tntSCVideoList');
add_shortcode('tnt_video', 'tntSCVideo');

/**
 * YOUTUBE
 */

/**
 * Function get embed code from youtube link
 */
function tntGetYoutubeEmbedLink($link)
{
	$youtubeEmbedLink = 'http://www.youtube.com/embed/';
	$l = explode('?v=', $link);
	$embedCode = $l[1];
	$youtubeEmbedLink .= $embedCode;
	return $youtubeEmbedLink;
}

/**
 * Function get thumb link from youtube link
 */
function tntGetYoutubeThumbLink($link)
{
	$l = explode('?v=', $link);
	$embedCode = $l[1];
	$youtubeThumbLink = 'http://img.youtube.com/vi/'.$embedCode.'/mqdefault.jpg';
	return $youtubeThumbLink;
}

/**
 * VIMEO
 */

/**
 * Function get embed code from vimeo link
 */
function tntGetVimeoEmbedLink($link)
{
	$vimeoEmbedLink = 'http://player.vimeo.com/video/';
	$l = explode('vimeo.com/', $link);
	$embedCode = $l[1];
	$vimeoEmbedLink .= $embedCode;
	return $vimeoEmbedLink;
}

/**
 * Function get thumb link from vimeo link
 */
function tntGetVimeoThumbLink($link)
{
	$l = explode('vimeo.com/', $link);
	$embedCode = $l[1];
	$xmlVimeo = simplexml_load_file("http://vimeo.com/api/v2/video/$embedCode.xml");
    $thumbnail = $xmlVimeo->video->thumbnail_large;
	return $thumbnail;
}

/**
 * DAILYMOTION
 */

/**
 * Function get embed code from dailymotion link
 */
function tntGetDailymotionEmbedLink($link)
{
	$dmEmbedLink = 'http://www.dailymotion.com/embed/video/';
	$l = explode('video/', $link);
	$l1 = explode('_', $l[1]);
	$embedCode = $l1[0];
	$dmEmbedLink .= $embedCode;
	return $dmEmbedLink;
}

/**
 * Function get thumb link from dailymotion link
 */
function tntGetDailymotionThumbLink($link)
{
	$l = explode('video/', $link);
	$dmThumbLink = $l[0].'thumbnail/video/'.$l[1];
	return $dmThumbLink;
}

/**
 * Callback function for shortcode [tnt_video_list]
 */
function tntSCVideoList($attr){
	$tntOptions = get_option('tntVideoManageOptions');

	//Get cat ID
	$catID = $attr['id'];
	
	//Get column
	$columnOption = $tntOptions['columnPerRow'];
	$columns = (isset($attr['col'])) ? $attr['col'] : $columnOption;

	//Get video width
	$videoWidthOption = $tntOptions['videoWidth'];
	$vidWidth 	= (isset($attr['width'])) ? $attr['width'] : $videoWidthOption;

	//Get video height
	$videoHeightOption = $tntOptions['videoHeight'];
	$vidHeight 	 = (isset($attr['height'])) ? $attr['height'] : $videoHeightOption;	 

	//Get Limit
	$limitOption = $tntOptions['limitPerPage'];
	$numLimit    = (isset($attr['limit'])) ? $attr['limit'] : $limitOption;

	//Get Order and Order by
	$arrVideoOrder 		= array('videoid','addingdate', 'editingdate', 'alphabet', 'ordernumber');
	$arrVideoOrderBy 	= array('asc', 'desc');
	$orderOption 		= $tntOptions['videoOrder'];
	$orderbyOption 		= $tntOptions['videoOrderBy'];
	$vidOrder 	 		= (isset($attr['order']) && in_array($attr['order'], $arrVideoOrder)) ? $attr['order'] : $orderOption;
	$vidOrderBy 		= (isset($attr['orderby']) && in_array($attr['orderby'], $arrVideoOrderBy)) ? $attr['orderby'] : $orderbyOption;

	//Get videos by catID
	$args = array('catID' => $catID, 'isPublish' => 1);
	$videoList = TNT_Video::tntGetVideos($args); 

	//Get all information for pagination
	$items = count($videoList);
	if($items > 0) 
	{
        $p = new TNT_Pagination();
        $p->items($items);
        $p->limit($numLimit); // Limit entries per page
        $p->target($_SERVER["REQUEST_URI"]); 
        $p->calculate(); // Calculates what to show
        $p->parameterName('paged');
        $p->adjacents(1); //No. of page away from the current page
        
        $pageMix = explode('/page/', $_SERVER["REQUEST_URI"]);
        $page = '';
        if(isset($pageMix[1]))
        {
        	$page = (int)substr($pageMix[1], 0, 5);
        }
        else
        {
        	if($_GET['paged'] != '')
        	{
        		$page = $_GET['paged'];
        	} 	
        	else
        	{
        		$page = 1;
        	}
        }
        
        $p->page = ($page != null) ? $page : 1;
        
        //Query for limit paging
        $limit = "LIMIT " . ($p->page - 1) * $p->limit  . ", " . $p->limit;
	         
	} else {
	    echo "No Record Found! Category ID was not existed!"; exit();
	}

	//Get videos by options
	switch ($vidOrder) {
		case 'addingdate':
			$vidOrder = 'date_created';
			break;
		case 'editingdate':
			$vidOrder = 'date_modified';
			break;
		case 'alphabet':
			$vidOrder = 'video_title';
			break;
		case 'ordernumber':
			$vidOrder = 'video_order';
			break;
		default:
			$vidOrder = 'video_id';
			break;
	}
	$args = array('catID' => $catID, 'isPublish' => 1, 'limitText' => $limit, 'orderBy' => $vidOrder, 'order' => $vidOrderBy);
	$videoListLimit = TNT_Video::tntGetVideos($args);

	//Show template
	$vListToShow = array();
	foreach ($videoListLimit as $video)
	{
		$v = array();
		$videoTypeTitle = $video->video_type_title;
		$linkEmbed = "";
		$thumbImg = "";
		switch($videoTypeTitle)
		{
			case "Youtube" :
				$linkEmbed = tntGetYoutubeEmbedLink($video->video_link); 
				$thumbImg = tntGetYoutubeThumbLink($video->video_link);
				break;
			case "Vimeo" :
				$linkEmbed = tntGetVimeoEmbedLink($video->video_link);
				$thumbImg = tntGetVimeoThumbLink($video->video_link);
				break;
			case "DailyMotion" :
				$linkEmbed = tntGetDailymotionEmbedLink($video->video_link); 
				$thumbImg = tntGetDailymotionThumbLink($video->video_link);
				break;			
			default:
				break;
		}
		$v['videoTitle'] 	= $video->video_title;
		$v['videoThumb'] 	= $thumbImg;
		$v['videoUrl'] 		= $video->video_link;			
		$v['videoEmbed'] 	= $linkEmbed;
		$v['videoWidth'] 	= $vidWidth;
		$v['videoHeight'] 	= $vidHeight;
		$vListToShow[] = $v;
	}	

	$tntPagi = $p->getOutput();

	$view = tntTemplateVideoList($vListToShow, $tntPagi, $columns);
	return $view;
}

/**
 * Callback function for shortcode [tnt_video]
 */
function tntSCVideo($attr){
	$tntOptions = get_option('tntVideoManageOptions');

	//Get video ID
	$videoID = (int)$attr['id'];

	//Get width
	$videoWidthOption = $tntOptions['videoWidth'];
	$videoWidth = (isset($attr['width'])) ? $attr['width'] : $videoWidthOption; 

	//Get height
	$videoHeightOption = $tntOptions['videoHeight'];
	$videoHeight = (isset($attr['height'])) ? $attr['height'] : $videoHeightOption; 

	//Get video by videoID
	$args = array('videoID' => $videoID, 'isPublish' => 1);
	$video = TNT_Video::tntGetVideos($args);
	if($video)
	{
		//Show template
		$vShow = "";
		foreach ($video as $vid)
		{
			$vShow['videoTitle'] = $vid->video_title;
			switch ($vid->video_link_type)
			{
				case "1":
					$linkEmbed = tntGetYoutubeEmbedLink($vid->video_link);
					$vShow['videoFrame'] = '<iframe width="'.$videoWidth.'" height="'.$videoHeight.'" src="'.$linkEmbed.'" frameborder="0" allowfullscreen></iframe>';
					break;
				case "2":
					$linkEmbed = tntGetVimeoEmbedLink($vid->video_link);
					$vShow['videoFrame'] = '<iframe width="'.$videoWidth.'" height="'.$videoHeight.'" src="'.$linkEmbed.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
					break;
			}
			
		}

		$view = tntTemplateVideoItem($vShow);	
	}
	else
	{
		$view = "No Video ID found";
	}
	
	return $view;
}

 ?>