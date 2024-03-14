<div class="totalcontest-tabs-container">
    <div class="totalcontest-tab-content active">
        <div class="totalcontest-options-expressions">
            <table class="wp-list-table widefat totalcontest-options-list">
                <tbody>
                <tr ng-repeat-start="expressionsGroup in $ctrl.expressions.original track by $index" class="totalcontest-options-list-title">
                    <td colspan="2">
                        <bdi>{{expressionsGroup.label}}</bdi>
                    </td>
                </tr>
                <tr ng-repeat-start="(rawExpression, expression) in expressionsGroup.expressions track by $index" ng-if="false"></tr>
                <tr ng-repeat="translation in expression.translations track by $index" class="totalcontest-options-list-entry">
                    <td>
                        <bdi>{{translation}}</bdi>
                    </td>
                    <td>
                        <div class="tiny"
                             ng-class="{'totalcontest-processing': $ctrl.isExpressionProcessing(expression), 'totalcontest-successful': $ctrl.isExpressionSaved(expression)}">
                            <input type="text" class="widefat" ng-attr-placeholder="{{translation}}"
                                   ng-model="$ctrl.expressions.user[rawExpression]['translations'][$index]" ng-model-options="{debounce: 1500}"
                                   ng-change="$ctrl.saveExpression(rawExpression, expression)"
                                   dir="auto">
                        </div>
                    </td>
                </tr>
                <tr ng-repeat-end></tr>
                <tr ng-repeat-end></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>