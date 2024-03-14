<div class="info">
	<i class="fa fa-info-circle"></i>
	<p>
		Create a data capture widget for your lead magnet
	</p>

	<p>
		The widget will appear in your site's sidebar.
		<a href="/wp-admin/widgets.php">Activate & Place your widget</a>
	</p>

	<p>
	<button href="#" class="button large bn-refresh">
		<i class="fa fa-refresh"></i>
		Refresh list of lead magnets
	</button>
	</p>

</div>


<?php if (isset($data['saved'])): ?>
<div class="saved">
	<p>Saved!</p>
</div>
<?php endif; ?>

<div class="unsaved">
	<div class="info">
		<p>Updated widget needs to be saved</p>
		<button class="beacon-promote-save large">SAVE</button>
	</div>
</div>

<div class="step step1">
	<h1>1. Select eBook</h1>
	<ul class="issues"> </ul>
</div>


<div class="step step2">
	<h1>2. Customize</h1>
	<form method="post" id="beacon-promote">

	<input type="hidden" name="url" value="<?php echo $data['url']; ?>" />

	<div class="form-row">
		<label for="headline">Headline</label>
		<input type="text" name="headline" value="<?php echo $data['headline']; ?>" />
	</div>

	<div class="form-row">
		<label for="title">Blurb</label>
		<input type="text" name="title" value="<?php echo $data['title']; ?>" />
	</div>

	<div class="form-row">
		<label for="title">Button text</label>
		<input type="text" name="button" value="<?php echo $data['button']; ?>" />
	</div>

	</form>
</div>


<div class="step step3">	
	<h1>3. Preview</h1>

	<?php
		require( BEACONBY_PLUGIN_PATH . 'views/widget/widget.php' );
	?>

	<div class="note">
	Note: Theme styles will be applied
	</div>

</div>

