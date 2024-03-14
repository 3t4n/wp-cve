<?php
if (!$showThumbCaptions) {
	?>
	<h4 class="<?php echo $titleClass; ?>"><strong><?php echo $v->getTitle();?></strong></h4>
	<?php
} else {
	?>
	<h4 class="<?php echo $titleClass; ?>"><strong></strong></h4>
        <noscript><h4 class="<?php echo $titleClass; ?>"><strong><?php echo $v->getTitle();?></strong></h4></noscript>
	<?php
}
