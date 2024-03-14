<div class="totalcontest-settings-item">
    <div class="totalcontest-settings-field">
        <label>
            <input type="checkbox" name="" ng-model="editor.settings.vote.limitations.period.enabled" ng-checked="editor.settings.vote.limitations.period.enabled">
			<?php esc_html_e( 'Time period', 'totalcontest' ); ?>
        </label>
    </div>
</div>
<div class="totalcontest-settings-item-advanced" ng-class="{active: editor.settings.vote.limitations.period.enabled}">
    <div class="totalcontest-settings-item totalcontest-settings-item-inline">
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php esc_html_e( 'Start date', 'totalcontest' ); ?>
                <span class="totalcontest-feature-details" tooltip="<?php esc_attr_e( 'Entries submission will be closed before reaching this date.', 'totalcontest' ); ?>">?</span>
            </label>
            <input type="text" datetime-picker="<?php echo esc_attr( json_encode( [ 'format' => 'Y-m-d H:i', ] ) ); ?>" min="0" step="1" class="totalcontest-settings-field-input widefat" ng-model="editor.settings.vote.limitations.period.start">

            <br><br>

            <label class="totalcontest-settings-field-label">
				<?php esc_html_e( 'Custom message (before the specified date)', 'totalcontest' ); ?>
            </label>
            <input type="text" placeholder="<?php esc_attr_e("Not started yet, {{'\{\{time\}\}'}} left.", 'totalcontest'); ?>" class="totalcontest-settings-field-input widefat" ng-model="editor.settings.vote.limitations.period.startMessage">
            <p ng-non-bindable class="totalcontest-feature-tip"><code>{{time}}</code> <?php esc_html_e( 'will be replaced by the remaining time.', 'totalcontest' ); ?></p>
        </div>

        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
				<?php esc_html_e( 'End date', 'totalcontest' ); ?>
                <span class="totalcontest-feature-details" tooltip="<?php esc_attr_e( 'Entries submission will be closed after reaching this date.', 'totalcontest' ); ?>">?</span>
            </label>
            <input type="text" datetime-picker="<?php echo esc_attr( json_encode( [ 'format' => 'Y-m-d H:i', ] ) ); ?>" min="0" step="1" class="totalcontest-settings-field-input widefat" ng-model="editor.settings.vote.limitations.period.end">

            <br><br>

            <label class="totalcontest-settings-field-label">
				<?php esc_html_e( 'Custom message (after the specified date)', 'totalcontest' ); ?>
            </label>
            <input type="text" placeholder="<?php esc_attr_e("Finished since {{'\{\{time\}\}'}}.", 'totalcontest'); ?>" class="totalcontest-settings-field-input widefat" ng-model="editor.settings.vote.limitations.period.endMessage">
            <p ng-non-bindable class="totalcontest-feature-tip"><code>{{time}}</code> <?php esc_html_e( 'will be replaced by the elapsed time.', 'totalcontest' ); ?></p>
        </div>
    </div>
</div>
<!-- Membership -->
<div class="totalcontest-settings-item">
    <div class="totalcontest-settings-field">
        <label>
            <input type="checkbox" name="" disabled>
			<?php esc_html_e( 'Membership', 'totalcontest' ); ?>
            <?php TotalContest( 'upgrade-to-pro' ); ?>
        </label>
    </div>
</div>


<!-- Quota -->
<div class="totalcontest-settings-item">
    <div class="totalcontest-settings-field">
        <label>
            <input type="checkbox" name="" disabled>
			<?php esc_html_e( 'Quota', 'totalcontest' ); ?>
            <?php TotalContest( 'upgrade-to-pro' ); ?>
        </label>
    </div>
</div>

