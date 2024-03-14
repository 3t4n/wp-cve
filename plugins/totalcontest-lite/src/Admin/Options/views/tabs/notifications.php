<div class="totalcontest-tabs-container">
    <div class="totalcontest-tab-content active totalcontest-pro-badge-container">
        <div class="totalcontest-settings-item">
            <p class="totalcontest-feature-tip" ng-non-bindable><?php  esc_html_e( 'Contest title: {{contest.title}}', 'totalcontest' ); ?></p>
            <p class="totalcontest-feature-tip" ng-non-bindable><?php  esc_html_e( 'Submission title: {{submission.title}}', 'totalcontest' ); ?></p>
            <p class="totalcontest-feature-tip" ng-non-bindable><?php  esc_html_e( 'User IP: {{log.ip}}', 'totalcontest' ); ?></p>
            <p class="totalcontest-feature-tip" ng-non-bindable><?php  esc_html_e( 'User browser: {{log.browser}}', 'totalcontest' ); ?></p>
            <p class="totalcontest-feature-tip" ng-non-bindable><?php  esc_html_e( 'Vote date: {{log.date}}', 'totalcontest' ); ?></p>

            <div class="totalcontest-settings-field">
                <label class="totalcontest-settings-field-label">
					<?php  esc_html_e( 'Title', 'totalcontest' ); ?>
                </label>
                <input type="text" class="totalcontest-settings-field-input widefat" disabled>
            </div>
            <div class="totalcontest-settings-field">
                <label class="totalcontest-settings-field-label">
					<?php  esc_html_e( 'Plain text body', 'totalcontest' ); ?>
                </label>
                <textarea type="text" class="totalcontest-settings-field-input widefat" disabled></textarea>
            </div>
            <div class="totalcontest-settings-field">
                <label class="totalcontest-settings-field-label">
					<?php  esc_html_e( 'HTML template', 'totalcontest' ); ?>
                </label>
                <textarea type="text" class="totalcontest-settings-field-input widefat" disabled rows="10"></textarea>
            </div>
        </div>
        <?php TotalContest( 'upgrade-to-pro' ); ?>
    </div>
</div>
