<?php 
printf(
	'<script>window.location.replace("%1$s");window.location.href = "%1$s";</script>', 
	admin_url('post-new.php?post_type=wpape_gallery_theme&wpape_type='.WPAPE_GALLERY_THEME_TYPE_GRID)
);
exit;