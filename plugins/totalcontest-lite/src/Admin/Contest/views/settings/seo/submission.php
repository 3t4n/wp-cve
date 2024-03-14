<div class="totalcontest-settings-item">
    <div class="totalcontest-settings-field">
        <label class="totalcontest-settings-field-label">
			<?php  esc_html_e( 'Title', 'totalcontest' ); ?>
        </label>
        <input type="text" class="totalcontest-settings-field-input widefat" ng-model="editor.settings.seo.submission.title">
    </div>
</div>
<div class="totalcontest-settings-item">
    <div class="totalcontest-settings-field">
        <label class="totalcontest-settings-field-label">
			<?php  esc_html_e( 'Description', 'totalcontest' ); ?>
        </label>
        <textarea rows="3" class="totalcontest-settings-field-input widefat" ng-model="editor.settings.seo.submission.description"></textarea>
        <p class="totalcontest-feature-tip">{{150 - editor.settings.seo.submission.description.length}} <?php  esc_html_e( 'Characters left.', 'totalcontest' ); ?></p>
    </div>
</div>
<div class="totalcontest-settings-item">
    <p><strong><?php  esc_html_e( 'Template variables', 'totalcontest' ); ?></strong></p>
    <p class="totalcontest-feature-tip" ng-non-bindable><?php  esc_html_e( '{{fields.FIELD_NAME}} for form fields.', 'totalcontest' ); ?></p>
    <p class="totalcontest-feature-tip" ng-non-bindable><?php  esc_html_e( '{{date}} for submission date.', 'totalcontest' ); ?></p>
    <p class="totalcontest-feature-tip" ng-non-bindable><?php  esc_html_e( '{{time}} for submission time.', 'totalcontest' ); ?></p>
    <p class="totalcontest-feature-tip" ng-non-bindable><?php  esc_html_e( '{{datetime}} for submission date and time.', 'totalcontest' ); ?></p>
    <p class="totalcontest-feature-tip" ng-non-bindable><?php  esc_html_e( '{{views}} for submission views.', 'totalcontest' ); ?></p>
    <p class="totalcontest-feature-tip" ng-non-bindable><?php  esc_html_e( '{{votes}} for submission votes.', 'totalcontest' ); ?></p>
    <p class="totalcontest-feature-tip" ng-non-bindable><?php  esc_html_e( '{{rate}} for submission rate.', 'totalcontest' ); ?></p>
</div>
