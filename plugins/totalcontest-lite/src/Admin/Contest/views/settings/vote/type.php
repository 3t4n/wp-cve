<div class="totalcontest-settings-item">
    <div class="totalcontest-settings-field">
        <label class="totalcontest-settings-field-label">
			<?php  esc_html_e( 'Vote type', 'totalcontest' ); ?>
            <span class="totalcontest-feature-details" tooltip="<?php esc_attr_e( 'This option defines how votes are metered.', 'totalcontest' ); ?>">?</span>
        </label>

        <p>
            <label> <input type="radio" name="" value="count" ng-model="editor.settings.vote.type">
				<?php  esc_html_e( 'Count (Incremental)', 'totalcontest' ); ?>
            </label>
        </p>

        <p>
            <label> <input type="radio" name="" value="rate" disabled>
				<?php  esc_html_e( 'Rate (Average)', 'totalcontest' ); ?>
                <?php TotalContest( 'upgrade-to-pro' ); ?>
            </label>
        </p>
        
    </div>
</div>
