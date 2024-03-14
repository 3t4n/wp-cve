<?php
global $YRM_TYPES_INFO;
$tutorialsTitles = $YRM_TYPES_INFO['youtubeUrls'];
$unlockURL = '';
$allowedTags = ReadMoreAdminHelper::getAllowedTags();
if(YRM_PKG == YRM_FREE_PKG) {
	$unlockURL = '<a href="'.YRM_PRO_URL.'" target="_blank">Unlock</a>';
}

foreach ($tutorialsTitles as $videoKey => $url) {
	$title = 'Video Tutorial';
	
	if (!empty(ReadMoreAdminHelper::getTitleFromType($videoKey))) {
		$title = ReadMoreAdminHelper::getTitleFromType($videoKey);
	}
	$embedUrl = ReadMoreAdminHelper::getYoutubeEmbedUrl($url);
	?>
	<div class="current-video-section">
		<h3 style=" margin: 40px 0px 20px;"><?php echo wp_kses($title, $allowedTags); ?> <?php echo wp_kses($unlockURL, $allowedTags); ?></h3>
		<iframe class="current-iframe" src="<?php echo esc_url($embedUrl);?>" style="width: 80%; height: 300px;"></iframe>
	</div>
	<?php
}