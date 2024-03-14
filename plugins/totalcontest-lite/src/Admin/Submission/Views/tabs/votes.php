<div class="totalcontest-tab-content" tab="submission>votes">
    <div class="totalcontest-tabs-container">
        <div class="totalcontest-tab-content active">

            <div class="totalcontest-settings-item">
                <div class="totalcontest-settings-field">
                    <label class="totalcontest-settings-field-label" for="field-votes"><?php  esc_html_e( 'Votes', 'totalcontest' ); ?></label>
                    <input name="totalcontest[votes]" type="hidden" ng-value="::information.votes">
                    <input name="totalcontest[votes_override]" type="text" id="field-votes" class="totalcontest-settings-field-input widefat" ng-model="information.votes">
                </div>
            </div>

			<?php if ( $this->contest->isRateVoting() ): ?>
                <div class="totalcontest-settings-item" ng-repeat="rating in information.ratings">
                    <div class="totalcontest-settings-field">
                        <label class="totalcontest-settings-field-label">{{rating.name}}</label>
                        <input name="totalcontest[ratings][{{$index}}][votes]" type="hidden" ng-value="::rating.votes">
                        <input name="totalcontest[ratings][{{$index}}][cumulative]" type="hidden" ng-value="::rating.cumulative">
                        <table class="wp-list-table widefat striped">
                            <tr>
                                <td><?php  esc_html_e( 'Votes', 'totalcontest' ); ?></td>
                                <td><?php  esc_html_e( 'Cumulative', 'totalcontest' ); ?></td>
                                <td><?php  esc_html_e( 'Rate', 'totalcontest' ); ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <input name="totalcontest[ratings][{{$index}}][votes_override]" type="number" min="0" class="totalcontest-settings-field-input widefat" ng-model="rating.votes"
                                           ng-change="rating.cumulative = rating.scale * rating.votes < rating.cumulative ? rating.scale * rating.votes : rating.cumulative">
                                </td>
                                <td>
                                    <input name="totalcontest[ratings][{{$index}}][cumulative_override]" type="number" min="0" class="totalcontest-settings-field-input widefat" ng-model="rating.cumulative"
                                           ng-change="rating.cumulative = rating.scale * rating.votes < rating.cumulative ? rating.scale * rating.votes : rating.cumulative">
                                </td>
                                <td>
                                    <input type="text" class="totalcontest-settings-field-input widefat" readonly ng-value="(rating.cumulative / rating.votes).toFixed(1) + ' of ' + rating.scale">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
			<?php endif; ?>
        </div>
    </div>
</div>
