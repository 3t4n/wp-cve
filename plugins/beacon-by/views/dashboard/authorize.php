<div class="info">
	<i class="fa fa-info-circle"></i>
	<h1>Hey there!</h1>

	<p>
	Before you start creating lead magnemts we would like to take a quick minute to allay any security concerns you may have.
	</p>

	<p>Firstly, this is an official plugin created by the same company that is behind <a href="https://beacon.by">Beacon</a>.</p>

	<p>
	When you create a lead magnet your blog title and description are shared with your Beacon account. <br />
	<b>No private information</b> such as your email address is ever shared.
	</p>

	<p>The plugin also accesses your Beacon account to retrieve a list of your published issues.
	<br />
	This depends on you being logged into your Beacon account 
	</p>

	<!-- <form method="post"> -->
		<!-- <input type="hidden" name="authorize" value="true" /> -->
		<!-- <button class="button large">I understand, let's get started! &raquo;</button> -->
	<!-- </form> -->

	<form action="<?php echo BEACONBY_CREATE_TARGET; ?>/auth/wordpress" method="post">
		<input type="hidden" name="blog" value="<?php echo $_SERVER['HTTP_HOST']; ?>" />
		<input type="hidden" name="ref" value="<?php echo Beacon_plugin::getPageURL(); ?>" />
		<button class="button large">Let's Connect</button>
	</form>

	

</div>
