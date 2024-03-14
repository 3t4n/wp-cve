<header class="header-container">
	<img class="cloudways-logo" src="<?php echo esc_url(plugins_url("/../assets/img/cloudways-logo.png", __FILE__)); ?>">
	<img class="blogvault-logo" src="<?php echo esc_url(plugins_url("/../assets/img/blogvault-logo.png", __FILE__)); ?>">
</header>
<main class="text-center">
	<div class="card">
		<form action="<?php echo esc_url($this->bvinfo->appUrl()); ?>/migration/migrate" method="post" name="signup">
			<h1 class="card-title">Migrate your site to Cloudways</h1>
			<p class="sub-heading">
				Don’t have the time? <a href="https://support.cloudways.com/how-to-request-a-managed-application-migration-to-cloudways/"
					target="_blank" rel="noreferrer" class="color-2D38BB">Let us migrate your site for free!</a>
			</p>
			<hr class="my-4">
			<div class="form-content">
				<label class="email-label" required>Email</label>
				<input type="email" name="email" placeholder="Email address" class="email-input">
				<div class="tnc-check text-center mt-2">
					<label class="normal-text horizontal color-0E134F customcheck">
						<input type="hidden" name="bvsrc" value="wpplugin" />
						<input type="hidden" name="migrate" value="cloudways" />
						<input type="checkbox" name="consent" onchange="document.getElementById('migratesubmit').disabled = !this.checked;"
							value="1">
						<span class="checkmark"></span>&nbsp;
						I agree to BlogVault's&nbsp;
						<a href="https://blogvault.net/tos/" class="color-2D38BB">Terms &amp; Conditions</a>
						&nbsp;and&nbsp;
						<a href="https://blogvault.net/privacy/" class="color-2D38BB">Privacy&nbsp;Policy</a>
					</label>
				</div>
			</div>
			<?php echo $this->siteInfoTags(); ?>
			<input type="submit" name="submit" id="migratesubmit" class="migrate-button mt-3" value="MIGRATE" disabled>
		</form>
	</div>
</main>