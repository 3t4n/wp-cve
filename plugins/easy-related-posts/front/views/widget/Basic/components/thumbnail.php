<?php
?>
<img
	src="<?php echo $v->getThumbnail($optionsObj->getThumbnailHeight(),$optionsObj->getThumbnailWidth(),$optionsObj->getCropThumbnail()); ?>"
	class="erpProThumb <?php echo $thumbClass; ?>"
	data-caption="<?php echo str_replace('"', "'", $v->getTitle());?>">

