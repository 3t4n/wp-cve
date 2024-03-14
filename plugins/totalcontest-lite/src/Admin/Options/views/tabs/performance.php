<div class="totalcontest-tabs-container">
    <div class="totalcontest-tab-content active">
        <div class="totalcontest-settings-item">
            <div class="totalcontest-settings-field">
                <label>
                    <input type="checkbox" name="" ng-model="$ctrl.options.performance.async.enabled">
					<?php  esc_html_e( 'Asynchronous loading', 'totalcontest' ); ?>
                </label>

                <p class="totalcontest-feature-tip"><?php  esc_html_e( 'This can be useful when you would like to bypass cache mechanisms and plugins.', 'totalcontest' ); ?></p>
            </div>
        </div>
        <div class="totalcontest-settings-item">
            <div class="totalcontest-settings-field">
                <label>
                    <input type="checkbox" name="" disabled>
					<?php  esc_html_e( 'Full checks on page load.', 'totalcontest' ); ?>
                    <?php TotalContest( 'upgrade-to-pro' ); ?>
                </label>

                <p class="totalcontest-feature-tip"><?php  esc_html_e( 'This may put high load on your server because TotalContest will hit the database frequently.', 'totalcontest' ); ?></p>
            </div>
        </div>
        <div class="totalcontest-settings-item">
            <div class="totalcontest-settings-field">
                <button type="button" class="button" ng-click="$ctrl.purge('cache')" ng-disabled="$ctrl.isPurging('cache') || $ctrl.isPurged('cache')">
                    <span ng-if="$ctrl.isPurgeReady('cache')"><?php  esc_html_e( 'Clear cache', 'totalcontest' ); ?></span>
                    <span ng-if="$ctrl.isPurging('cache')"><?php  esc_html_e( 'Clearing', 'totalcontest' ); ?></span>
                    <span ng-if="$ctrl.isPurged('cache')"><?php  esc_html_e( 'Cleared', 'totalcontest' ); ?></span>
                </button>
            </div>
        </div>
    </div>
</div>
