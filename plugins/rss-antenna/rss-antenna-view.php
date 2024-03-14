<div class='rss-antenna'>
	<?php foreach($info->items as $item): ?>
	<div class='rss-item'>
	<a href="<?php echo $item->url; ?>" target="_blank">
		<?php if (!empty($item->img_src)): ?>
		<div class='rss-img <?php echo $info->image_position;?>' >
			<img src='<?php echo $item->img_src;?>'  alt=''>
		</div>
		<?php endif; ?>
		<p class='title'>
		<?php echo $item->title; ?></p>
		<p class='siteinfo'>[<?php echo $item->site_name; ?>] <?php echo $item->date; ?></p>
		<?php if( !empty($item->description)): ?>
			<p class='description'>
				<?php echo $item->description; ?>
			</p>
		<?php endif; ?>
	</a>
	</div>
    <hr>
	<?php endforeach; ?>
</div>