<script type="text/ng-template" id="preview-submission-template">
    <div class="totalcontest-submission-container">
        <div class="totalcontest-submission-preview" ng-bind-html="$ctrl.getPreview(submission.id)"></div>
        <div class="totalcontest-submission-body">
            <table class="wp-list-table widefat striped posts">
                <thead>
                <tr>
                    <th ng-repeat="(fieldName, fieldValue) in submission.fields">{{fieldName}}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td ng-repeat="(fieldName, fieldValue) in submission.fields">{{fieldValue}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</script>