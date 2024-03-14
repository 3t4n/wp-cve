<?php 
if ( ! empty( $title ) )
	echo $before_title . $title . $after_title;

if(!empty($instance['imgs'])) :

	$imgs = $instance['imgs'];

	if($instance['img_open_in'] == 'new' ) {
		$img_open_in = 'target="_blank"';
	} else {
		$img_open_in = '';
	}

	if($instance['rel'] == 'nofollow' ) {
		$rel = 'rel="nofollow"';
	} else {
		$rel = '';
	}

	if($instance['size'] == 'custom'){
		$img_size = 'full';
		$sizes = 'style="width:'.$instance['img_width'].'px; height:'.$instance['img_height'].'px;"';
	} else {
		$img_size = $instance['size'];
		$sizes = '';
	}
?>

<ul class="swifty_imgwidget_ul al<?php echo $instance['align'];?>">
	<?php foreach($imgs as $img) :
		$imgsrc = wp_get_attachment_image_src($img['img'],$img_size);
		$alt = get_post_meta($img['img'], '_wp_attachment_image_alt', true);
		if($img['link'] != '') { ?>
			<li><a <?php echo $img_open_in.' '. $rel; ?> href="<?php echo $img['link'];?>"><img src="<?php echo $imgsrc[0];?>" <?php if($alt != '') { echo 'alt="'.esc_attr($alt).'"'; } ?> <?php echo $sizes; ?>/></a>
			<?php if($img['caption'] != '') { echo '<span class="sbcaption">'.$img["caption"].'</span>';} ?>
			</li>
		<?php } else { ?>
			<li><img src="<?php echo $imgsrc[0];?>" <?php if($alt != '') { echo 'alt="'.esc_attr($alt).'"'; } ?> <?php echo $sizes; ?>/>
				<?php if($img['caption'] != '') { echo '<span class="sbcaption">'.$img["caption"].'</span>';} ?>
			</li>
		<?php } ?>

	<?php endforeach; ?>
</ul>

<?php endif; ?>