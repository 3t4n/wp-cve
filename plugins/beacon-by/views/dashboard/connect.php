
<?php if ($data['has_connected']): ?>
<div class="info beacon-connect-info">
	<i class="fa fa-info-circle"></i>
	<h1> <i class="fa fa-check green"></i> Connected</h1>
	<p>
	Wordpress has successfully connected to your Beacon account:<b> <span class="bn-title"> </span></b>

	</p>

	<span style="display: none;">
	<p>
	<b>Your lead magnets</b>
	<ul class="issues bn-issues"></ul>
	</p>

	<p>
	<button href="#" class="button large bn-refresh">
		<i class="fa fa-refresh"></i>
		Refresh list of lead magnets
	</button>
	</p>
	</span>


	<hr />

	<h1>Want to disconnect?</h1>

	<p class="large">
	</p>

	<form action="?page=beaconby-connect" method="post">
		<input type="hidden" name="disconnect" value="disconnect"/>
		<button type="submit" class="text-button">Disconnect</button>
	</form>
	<br />

</div>
<?php else: ?>
<div class="info">
	<i class="fa fa-info-circle"></i>
	<h1>Connect your Beacon Account</h1>

	<p> Connect WordPress to your Beacon account so you can convert blog posts into lead magnets.  </p>

	<form action="<?php echo BEACONBY_CREATE_TARGET; ?>/auth/wordpress" method="post">
		<input type="hidden" name="blog" value="<?php echo $_SERVER['HTTP_HOST']; ?>" />
		<input type="hidden" name="ref" value="<?php echo Beacon_plugin::getPageURL(); ?>" />
		<button class="button large">Connect</button>
	</form>

	<div class="divider"></div>

	<p class="large flush">I don't have a Beacon account </p>

	<form action="<?php echo BEACONBY_CREATE_TARGET; ?>/auth/register-wordpress" method="post">
		<input type="hidden" name="page" value="<?php echo $_SERVER['HTTP_HOST']; ?>"/>
		<input type="hidden" name="domain" value="<?php echo $_SERVER['PHP_SELF']; ?>"/>
		<button type="submit" class="text-button">Create a free account &gt;</button>
	</form>
	<br />

	<p>
		The connect process will take you to Beacon, to log on, and then redirect you back here. <b>No personal information, such as your email address, will be shared</b>
	</p>


	<hr />
	<br />
	<h1>Having Trouble?</h1>
	<p class="large flush">
	<a href="?page=beaconby-help">Check our help section on connecting manually</a>
	</p>
	

</div>

<?php endif; ?>
