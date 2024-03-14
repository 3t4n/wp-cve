<div class="totalcontest-settings-item">
    <div class="totalcontest-settings-field">
        <p><?php  esc_html_e( 'Block based on', 'totalcontest' ); ?> <span class="totalcontest-feature-details"
                                                                  tooltip="<?php esc_attr_e( 'The methods used to control rate of voting.', 'totalcontest' ); ?>">?</span>
        </p>
        <div class="totalcontest-settings-field">
            <label> <input type="checkbox" name="" ng-model="editor.settings.vote.frequency.cookies.enabled">
				<?php  esc_html_e( 'Cookies', 'totalcontest' ); ?>
            </label>
        </div>
        <div class="totalcontest-settings-field">
            <label> <input type="checkbox" name="" disabled>
				<?php  esc_html_e( 'IP', 'totalcontest' ); ?>
                <?php TotalContest( 'upgrade-to-pro' ); ?>
            </label>
        </div>
        <div class="totalcontest-settings-field">
            <label> <input type="checkbox" name="" disabled>
				<?php  esc_html_e( 'User', 'totalcontest' ); ?>
                <?php TotalContest( 'upgrade-to-pro' ); ?>
            </label>
        </div>
    </div>
</div>
<div class="totalcontest-settings-item">

    <div class="totalcontest-settings-field">
        <div class="totalcontest-settings-field-label">
			<?php  esc_html_e( 'Vote restrictions', 'totalcontest' ); ?>
        </div>
        <label>
            <input type="checkbox" name="" ng-model="editor.settings.vote.frequency.preventSelfVote" ng-checked="editor.settings.vote.frequency.preventSelfVote">
			<?php esc_html_e( 'Prevent contestants from voting on their own submissions', 'totalcontest' ); ?>
            <span class="totalcontest-feature-details"  tooltip="<?php esc_attr_e( 'This option prevents contestants from voting on their own submissions to make the contest less biased.', 'totalcontest' ); ?>">?</span>
        </label>
    </div>

    <div class="totalcontest-settings-field">
        <label class="totalcontest-settings-field-label">
			<?php  esc_html_e( 'Votes per user', 'totalcontest' ); ?>
            <span class="totalcontest-feature-details"
                  tooltip="<?php esc_attr_e( 'Number of votes per each user.', 'totalcontest' ); ?>">?</span>
        </label>
        <input type="number" min="0" step="1" class="totalcontest-settings-field-input widefat"
               ng-model="editor.settings.vote.frequency.count"
               ng-disabled="!(editor.settings.vote.frequency.cookies || editor.settings.vote.frequency.ip || editor.settings.vote.frequency.user)">
    </div>

    <div class="totalcontest-settings-field">
        <label class="totalcontest-settings-field-label">
			<?php  esc_html_e( 'Votes per submission', 'totalcontest' ); ?>
            <span class="totalcontest-feature-details"
                  tooltip="<?php esc_attr_e( 'Number of allowed votes for the same submission.', 'totalcontest' ); ?>">?</span>
        </label>
        <input type="number" min="0" step="1" class="totalcontest-settings-field-input widefat"
               ng-model="editor.settings.vote.frequency.perItem"
               ng-disabled="!(editor.settings.vote.frequency.cookies || editor.settings.vote.frequency.ip || editor.settings.vote.frequency.user)">
    </div>

    <div class="totalcontest-settings-field" ng-if="editor.hasRequiredCategoryField()">
        <label class="totalcontest-settings-field-label">
			<?php  esc_html_e( 'Votes per category', 'totalcontest' ); ?>
            <span class="totalcontest-feature-details"
                  tooltip="<?php esc_attr_e( 'Number of allowed votes for submissions in the same category.', 'totalcontest' ); ?>">?</span>
        </label>
        <input type="number" min="0" step="1" class="totalcontest-settings-field-input widefat"
               ng-model="editor.settings.vote.frequency.perCategory"
               ng-disabled="!(editor.settings.vote.frequency.cookies || editor.settings.vote.frequency.ip || editor.settings.vote.frequency.user)">

        <p class="totalcontest-feature-tip"><?php  esc_html_e( '0 means unlimited', 'totalcontest' ) ?></p>
    </div>

    <div class="totalcontest-settings-field">
        <label class="totalcontest-settings-field-label">
            <?php  esc_html_e( 'Vote timeout', 'totalcontest' ); ?>
            <span class="totalcontest-feature-details"
                  tooltip="<?php esc_attr_e( 'The period of time that a user must wait until he can vote again.', 'totalcontest' ); ?>">?</span>
        </label>
        <div class="totalcontest-settings-timeout">
            <label class="totalcontest-settings-timeout-value" ng-repeat="(key, value) in editor.presets.timeout">
                <input type="radio" ng-checked="editor.settings.vote.frequency.timeout == key" ng-click="editor.setTimeout('vote', key)" name="voteFrequencyTimeout"/>{{ value }}
            </label>
            <label class="totalcontest-settings-timeout-value">
                <input type="radio" name="voteFrequencyTimeout" ng-checked="editor.isCustomTimeout('vote')" ng-click="!editor.isCustomTimeout('vote') && editor.setTimeout('vote', 1)"/><?php echo  esc_html_e('Custom (minutes)', 'totalcontest'); ?>
            </label>
        </div>
        <input type="number" min="0" step="1" class="totalcontest-settings-field-input widefat"
               ng-model-options="{ updateOn : 'blur' }"
               ng-show="editor.isCustomTimeout('vote')"
               ng-model="editor.settings.vote.frequency.timeout"
               ng-disabled="!(editor.settings.vote.frequency.cookies || editor.settings.vote.frequency.ip || editor.settings.vote.frequency.user)">
        <p class="totalcontest-feature-tip">
            <?php  esc_html_e( 'After this period, users will be able to vote again. To lock the vote permanently, use 0 as a value.', 'totalcontest' ); ?>
        </p>
        <p class="totalcontest-warning" ng-if="editor.settings.contest.frequency.timeout == 0">
            <?php  esc_html_e( 'Heads up! The database will be filled with permanent records which may affect the overall performance.', 'totalcontest' ); ?>
        </p>
    </div>
</div>

