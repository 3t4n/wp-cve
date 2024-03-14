<script type="text/ng-template" id="feedback-collector-component-template">
    <div class="totalcontest-feedback-collector-wrapper" ng-if="$ctrl.visible">
        <div class="totalcontest-feedback-collector">
            <h3 class="totalcontest-feedback-collector-title"><?php  esc_html_e( 'Feedback', 'totalcontest' ); ?></h3>
            <p class="totalcontest-feedback-collector-question"><?php  esc_html_e( 'How likely are you to recommend our product to your colleagues or friends?', 'totalcontest' ); ?></p>

            <button type="button" ng-click="$ctrl.postponeFeedback()" class="totalcontest-feedback-collector-close">&times;</button>

            <div class="totalcontest-feedback-collector-items">
                <button type="button"
                        class="button"
                        ng-repeat="item in [1,2,3,4,5,6,7,8,9,10]"
                        ng-click="$ctrl.setScore(item)"
                        onclick="setTimeout(function(){document.querySelector('#comment').focus()}, 100);"
                        ng-class="{'button-primary': $ctrl.isScore(item)}">
                    {{item}}
                </button>
            </div>

            <div class="totalcontest-feedback-collector-items">
                <span>Not likely at all</span>
                <span>Extremely likely</span>
            </div>

            <div class="totalcontest-settings-field totalcontest-feedback-collector-comment" ng-if="$ctrl.score">
                <label for="comment" class="totalcontest-settings-field-label" ng-if="$ctrl.score < 10">How can we make it 10 for you? *</label>
                <label for="comment" class="totalcontest-settings-field-label" ng-if="$ctrl.score == 10">Awesome! What you liked the most?</label>
                <textarea id="comment" rows="3" placeholder="Your comment..." class="totalcontest-settings-field-input widefat" ng-model="$ctrl.comment"></textarea>
            </div>

            <div class="totalcontest-settings-field totalcontest-feedback-collector-email" ng-if="$ctrl.score">
                <label for="email" class="totalcontest-settings-field-label">Email</label>
                <input type="email" id="email" rows="3" placeholder="Your email..." class="totalcontest-settings-field-input widefat" ng-model="$ctrl.email" ng-value="'<?php echo esc_attr( get_option( 'admin_email' ) ); ?>'">
            </div>

            <div class="totalcontest-feedback-collector-footer" ng-if="$ctrl.score > 0">
                <button type="button" ng-disabled="$ctrl.score < 10 && !$ctrl.comment" ng-click="$ctrl.markFeedbackAsCollected()" class="button button-large button-primary"><?php  esc_html_e( 'Submit', 'totalcontest' ); ?></button>
            </div>
        </div>
    </div>
</script>
