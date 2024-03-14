<div class='whatsnew'>
	<?php if ( $info->title ): ?>
		<?php echo '<'.$info->title_tag.'>'.$info->title.'</'.$info->title_tag.'>'; ?>
	<?php endif; ?>

	<hr/>
	<?php foreach($info->items as $item): ?>
	<dl>
		<a href="<?php echo $item->url; ?>">
		<dt>
			<?php echo $item->date; ?>
		</dt>
		<dd>
			<?php if ( $item->newmark ): ?>
			<span class='newmark'>NEW!</span>
			<?php endif; ?>
			<?php echo $item->title; ?>
		</dd>
		</a>
	</dl>
	<hr/>
	<?php endforeach; ?>
</div>
