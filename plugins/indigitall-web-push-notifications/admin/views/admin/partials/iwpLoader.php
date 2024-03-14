<?php
	$loaderImg = isset($loaderImg) ? "<img src='{$loaderImg}' alt='Loading...'>" : '';

?>
<div id="iwp-admin-loader-backdrop" class="iwp-admin-loader-backdrop iwp-hide">
	<div class="iwp-admin-loader"><?php echo($loaderImg); ?></div>
</div>