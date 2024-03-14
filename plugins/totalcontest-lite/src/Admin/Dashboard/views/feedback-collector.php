<script type="text/ng-template" id="dashboard-feedback-collector-component-template">
    <div class="totalcontest-feedback-collector" ng-if="$ctrl.visible">
        <h3 class="totalcontest-feedback-collector-title"><?php  esc_html_e( 'Feedback', 'totalcontest' ); ?></h3>
        <p class="totalcontest-feedback-collector-description"><?php  esc_html_e( 'Would you like to help us improve TotalContest?', 'totalcontest' ); ?></p>

        <div class="button-group">
            <a target="_blank" href="https://totalsuite.net/product/totalcontest/feedback/?utm_source=feedback-modal&utm_medium=in-app&utm_campaign=totalcontest" ng-click="$ctrl.markFeedbackAsCollected()" class="button button-primary"><?php  esc_html_e( 'Sure', 'totalcontest' ); ?></a>
            <button ng-click="$ctrl.postponeFeedback()" class="button" type="button"><?php  esc_html_e( 'Not now', 'totalcontest' ); ?></button>
        </div>
    </div>
</script>
