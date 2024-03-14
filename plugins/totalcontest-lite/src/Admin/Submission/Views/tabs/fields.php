<div class="totalcontest-tab-content" tab="submission>fields">
    <div class="totalcontest-tabs-container">
        <div class="totalcontest-tab-content active">
            <div class="totalcontest-settings-item" ng-repeat="(fieldName, fieldValue) in settings.fields" ng-if="['action', 'contestId', 'category'].indexOf(fieldName) === -1">
                <div class="totalcontest-settings-field">
                    <label class="totalcontest-settings-field-label" for="field-{{fieldName}}">
                        {{fieldName}}
                    </label>
                    <input type="text" id="field-{{fieldName}}" class="totalcontest-settings-field-input widefat" ng-model="settings.fields[fieldName]">
                </div>
            </div>
        </div>
    </div>
</div>
