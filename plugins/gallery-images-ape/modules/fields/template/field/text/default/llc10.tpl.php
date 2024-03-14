<div class="field small-10 columns">
	<div class="row">
		<div class="field small-3 columns">
			<?php if ($label) : ?>
				<label class="text-right middle">
					<?php echo $label; ?>
				</label>
			<?php endif; ?>
		</div>

		<div class="field small-9 columns">
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
