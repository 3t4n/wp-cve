<div class="totalcontest-settings-item">
	<div class="totalcontest-settings-field">
		<label class="totalcontest-settings-field-label">
			<?php  esc_html_e( 'Transition', 'totalcontest' ); ?>
		</label>

		<p>
			<label>
				<input type="radio" name="" value="none" ng-model="$root.settings.design.effects.transition">
				<?php  esc_html_e( 'None', 'totalcontest' ); ?>
			</label>
			&nbsp;&nbsp;
			<label>
				<input type="radio" name="" value="fade" ng-model="$root.settings.design.effects.transition">
				<?php  esc_html_e( 'Fade', 'totalcontest' ); ?>
			</label>
			&nbsp;&nbsp;
			<label>
				<input type="radio" name="" value="slide" disabled>
				<?php  esc_html_e( 'Slide', 'totalcontest' ); ?>
                <?php TotalContest( 'upgrade-to-pro' ); ?>
			</label>
		</p>

	</div>
</div>
<div class="totalcontest-settings-item">
	<div class="totalcontest-settings-field">
		<label class="totalcontest-settings-field-label">
			<?php  esc_html_e( 'Animation duration', 'totalcontest' ); ?>
			<span class="totalcontest-feature-details" tooltip="<?php  esc_html_e( 'Animation and transition duration', 'totalcontest' ); ?>">?</span>
		</label>
		<input type="text" class="totalcontest-settings-field-input widefat" ng-model="$root.settings.design.effects.duration">
	</div>
</div>
