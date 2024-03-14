<div class="field small-4 columns">
	<div class="row">
		<?php if ($label) : ?>
			<div class="field small-4 columns">
				<label class="text-right middle">
					<?php echo $label; ?>
				</label>
			</div>
		<?php endif; ?>
		
		<div class="field small-12 columns">
			<input id="<?php echo $id; ?>" <?php echo $attributes; ?>
		       type="text" name="<?php echo $name; ?>"
		       value="<?php echo $value; ?>" >
		</div>
		<?php if ($description) : ?>
			<div class="field small-12 columns">
				<p class="help-text"><?php echo $description; ?></p>
			</div>
		<?php endif; ?>
	</div>
</div>
