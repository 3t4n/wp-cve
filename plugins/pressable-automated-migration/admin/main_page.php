<header class="header-container">
	<img class="pressable-logo" src="<?php echo esc_url(plugins_url("/../assets/img/pressable-logo.png", __FILE__)); ?>">
	<img class="blogvault-logo" src="<?php echo esc_url(plugins_url("/../assets/img/blogvault-logo.png", __FILE__)); ?>">
</header>
<main>
	<div class="card">
		<form action="<?php echo esc_url($this->bvinfo->appUrl()); ?>/migration/migrate" method="post" name="signup">
			<h1 class="card-title">Migrate Your Site To Pressable</h1>
			<p>
				The Pressable Automated Migration Plugin makes it easy to move your WordPress sites to the Pressable platform. Simply enter some details about your site's current location and your Pressable information, and this powerful tool will get to work for you.
			</p>
			<p>You can find a complete guide to the automated migration plugin <a href='https://pressable.com/knowledgebase/using-the-pressable-automated-migration-plugin/' target="_blank" rel="noopener noreferrer" class="link-un">in the Pressable Knowledge Base</a>.</p>
			<hr class="my-4">
			<div class="form-content">
				<label class="email-label" required>Email <span class="email-desc">(Used for migration progress notifications)</span></label>
				<br>
				<input type="email" name="email" placeholder="Email address" class="email-input">
				<div class="tnc-check mt-2">
					<label class="normal-text horizontal">
						<input type="hidden" name="bvsrc" value="wpplugin" />
						<input type="hidden" name="migrate" value="pressable" />
						<input type="checkbox" name="consent" onchange="document.getElementById('migratesubmit').disabled = !this.checked;" value="1">
						<span class="checkmark"></span>&nbsp;
						I agree to BlogVault's <a href="https://blogvault.net/tos/">Terms &amp; Conditions</a> and <a href="https://blogvault.net/privacy/">Privacy&nbsp;Policy</a>
					</label>
				</div>
			</div>
			<?php echo $this->siteInfoTags(); ?>
			<input type="submit" name="submit" id="migratesubmit" class="button button-primary" value="Migrate" disabled>
		</form>
	</div>
</main>