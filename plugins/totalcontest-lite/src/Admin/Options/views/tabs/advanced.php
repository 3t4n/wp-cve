<div class="totalcontest-tabs-container">
    <div class="totalcontest-tab-content active">
        <div class="totalcontest-settings-item">
            <div class="totalcontest-settings-field">
                <label>
                    <input type="checkbox" name="" ng-model="$ctrl.options.advanced.inlineCss">
					<?php  esc_html_e( 'Always embed CSS with HTML.', 'totalcontest' ); ?>
                </label>

                <p class="totalcontest-feature-tip"><?php  esc_html_e( "This option might be useful when WordPress isn't running on standard filesystem.", 'totalcontest' ); ?></p>
            </div>
        </div>
        <div class="totalcontest-settings-item">
            <div class="totalcontest-settings-field">
                <label>
                    <input type="checkbox" name="" ng-model="$ctrl.options.advanced.altSerialization">
					<?php  esc_html_e( 'Alternative submission serialization.', 'totalcontest' ); ?>
                </label>

                <p class="totalcontest-warning"><?php  esc_html_e( "This breaks the search functionality. Please consult our support team before enabling this.", 'totalcontest' ); ?></p>
            </div>
        </div>
        <div class="totalcontest-settings-item">
            <div class="totalcontest-settings-field">
                <label>
                    <input type="checkbox" name="" ng-model="$ctrl.options.advanced.uninstallAll">
					<?php  esc_html_e( 'Remove all data on uninstall.', 'totalcontest' ); ?>
                </label>

                <p class="totalcontest-warning"><?php  esc_html_e( "Heads up! This will remove all TotalContest data including options, cache files, contests and submissions.", 'totalcontest' ); ?></p>
            </div>
        </div>
    </div>
</div>
