<div class="totalcontest-tabs-container">
    <div class="totalcontest-tab-content active">
        <div class="totalcontest-options-debug">
            <table class="wp-list-table widefat totalcontest-options-list">
                <tbody>
                <tr ng-repeat-start="(key, value) in $ctrl.debug track by $index"></tr>
                <tr class="totalcontest-options-list-title">
                    <td colspan="2">{{key}}</td>
                </tr>
                <tr ng-repeat="(subkey, subvalue) in value track by $index" class="totalcontest-options-list-entry">
                    <td>{{subkey}}</td>
                    <td ng-bind-html="subvalue|table:'group'"></td>
                </tr>
                <tr ng-repeat-end></tr>
                </tbody>
            </table>

            <p>
                <button type="button" ng-click="$ctrl.downloadDebugInformation()" class="button button-primary button-large"><?php  esc_html_e( 'Download', 'totalcontest' ); ?></button>
            </p>
        </div>
    </div>
</div>
