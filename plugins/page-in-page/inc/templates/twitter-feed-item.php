<div class="twitter-feed-item">
	<div class="feed-container">
		<div class="content-inner">

			<div class="header">
				<a class="account" href="<?php echo $item_user_page_url; ?>" target="_blank">
					<img class="avatar" src="<?php echo $item_avatar; ?>" alt="<?php echo $item_name; ?>" />
					<strong class="fullname"><?php echo $item_name; ?></strong>
					<span class="username"><?php echo $item_username; ?></span>
				</a>

				<small class="time">
					<a href="<?php echo $item_status_link; ?>" target="_blank"><span><?php echo $item_short_date; ?></span></a>
				</small>
			</div>

			<div class="body">
				<p><?php echo $item_message; ?></p>
			</div>

			<div class="footer">
				<span class="metadata"><span><?php echo $item_long_date; ?></span></span> &nbsp; 
				<a class="details" href="<?php echo $item_status_link; ?>" target="_blank">Details</a>
			</div>

		</div>
	</div>

	<div class="footer-foot">
		<a href="<?php echo $item_status_link; ?>" target="_blank"><img src="<?php echo $twitter_logo; ?>"></a>
	</div>

</div>