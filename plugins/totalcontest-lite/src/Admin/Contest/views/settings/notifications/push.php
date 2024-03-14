<div class="totalcontest-pro-badge-container">
    <div class="totalcontest-settings-item" ng-controller="NotificationsCtrl as $ctrl">
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
                <?php  esc_html_e( 'OneSignal App ID', 'totalcontest' ); ?> - <a href="https://onesignal.com/" target="_blank"><?php  esc_html_e( 'Get one', 'totalcontest' ); ?></a>
            </label>
            <input type="text" class="totalcontest-settings-field-input widefat" disabled>
        </div>
        <div class="totalcontest-settings-field">
            <label class="totalcontest-settings-field-label">
                <?php  esc_html_e( 'OneSignal API Key', 'totalcontest' ); ?>
            </label>
            <input type="text" class="totalcontest-settings-field-input widefat" disabled>
        </div>
        <div class="totalcontest-settings-field">
            <button type="button" class="button button-primary"
                    ng-disabled="$ctrl.pushCompleted || !editor.settings.notifications.push.appId || !editor.settings.notifications.push.apiKey"
                    ng-click="$ctrl.setupPushService()">
                <?php  esc_html_e( 'Setup push notification', 'totalcontest' ); ?>
            </button>
        </div>
    </div>
    <div class="totalcontest-settings-item">
        <p>
            <?php  esc_html_e( 'Send notification when', 'totalcontest' ); ?>
        </p>
        <div class="totalcontest-settings-field">
            <label>
                <input type="checkbox" name="" disabled>
                <?php  esc_html_e( 'New submission', 'totalcontest' ); ?>
            </label>
        </div>
    </div>
    <?php TotalContest( 'upgrade-to-pro' ); ?>
</div>
