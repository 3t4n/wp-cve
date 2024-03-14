<?php if ($this->errors->get_error_codes()): ?>
	<?php $this->error() ?>
<?php endif ?>

<div class="wrap">
	<?php
		$homeurl = "http://www.wpwizardcloak.com/adminpanel/index.php?lite=true&v=".urlencode(PMLC_Plugin::getInstance()->getVersion());
		$contents = @file_get_contents($homeurl);
		if ( ! $contents) {
			?>
			<iframe src='<?php echo $homeurl; ?>' width='600'></iframe><br />
			<?php
		} else {
			echo $contents;
		}
	?>
</div>
