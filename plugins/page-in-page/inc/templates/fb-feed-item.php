<div class="fb-feed-item">
	<div class="top-border"></div>
	<div class="feed-container">
		<div class="content-inner">
			<div class="header">
				<a class="header-logo" href="<?php echo $item_page_link; ?>">
					<img src="<?php echo $item_page_logo; ?>" alt="">
				</a>
				<h5 class="header-title">
					<span> <a href="<?php echo $item_page_link; ?>"><?php echo $item_page_name; ?></a> added a <?php echo $item_type; ?>.</span><br />
					<span class="header-datetime"><?php echo $item_date; ?></span>
				</h5>
			</div>
			<div class="body">
				<div class="message">
					<?php echo $item_message; ?>
				</div>
				<div class="photo-link">
					<a href="<?php echo $item_link_link; ?>" class="fb-clearfix full-link">
						<div class="photo">
							<?php if ($item_picture): ?>
							<img class="img" src="<?php echo $item_picture; ?>" alt="">
							<?php endif; ?>
						</div>
						<div class="photo-text" <?php if (!$item_picture): ?>style="padding-left: 15px;"<?php endif; ?>>
							<div class="title"><?php echo $item_link_name; ?></div>
							<div class="site-desc"><?php echo $item_link_caption; ?></div>
							<div class="desc"><?php echo $item_link_description ?> </div>
						</div>
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="footer">
		<a href="<?php echo $item_fb_link; ?>" target="_blank"><img src="<?php echo $fb_logo; ?>" /></a>
	</div>
</div>