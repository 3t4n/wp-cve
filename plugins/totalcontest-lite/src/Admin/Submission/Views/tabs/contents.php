<div class="totalcontest-tab-content" tab="submission>contents">
    <div class="totalcontest-tabs-container">
        <div class="totalcontest-tab-content active">
            <div class="totalcontest-settings-item" ng-repeat="(fieldName, subfields) in settings.contents">
                <div class="totalcontest-settings-field">
                    <label class="totalcontest-settings-field-label">
                        {{fieldName}}
                    </label>
                    <table class="wp-list-table widefat striped">
                        <tr ng-if="subfields.type != 'text'">
                            <td>Thumbnail</td>
                        </tr>
                        <tr ng-if="subfields.type != 'text'">
                            <td><input type="text" class="totalcontest-settings-field-input widefat" ng-model="settings.contents[fieldName].thumbnail.url"></td>
                        </tr>
                        <tr>
                            <td>Preview</td>
                        </tr>
                        <tr>
                            <td><input type="text" class="totalcontest-settings-field-input widefat" ng-model="settings.contents[fieldName].preview"></td>
                        </tr>
                        <tr>
                            <td>Content</td>
                        </tr>
                        <tr>
                            <td ng-if="subfields.type != 'text'"><input type="text" class="totalcontest-settings-field-input widefat" ng-model="settings.contents[fieldName].content"></td>
                            <td ng-if="subfields.type == 'text'"><textarea class="totalcontest-settings-field-input widefat" rows="3" ng-model="settings.contents[fieldName].content"></textarea></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>