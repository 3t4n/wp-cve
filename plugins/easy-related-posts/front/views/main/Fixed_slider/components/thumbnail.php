<?php
?>
<img
	src="<?php echo $v->getThumbnail($optionsObj->getThumbnailHeight(),$optionsObj->getThumbnailWidth(),$optionsObj->getCropThumbnail()); ?>"
	class="erpProThumb <?php echo $thumbClass; ?>"
        alt="<?php echo $v->getPostTitleEscaped(true); ?>"
	<?php 
	if ($showThumbCaptions){
	?>
		data-caption="<?php echo $v->getPostTitleEscaped(); ?>"
	<?php 
	}
	?>
	>