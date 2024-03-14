<script type="text/ng-template" id="dashboard-my-account-component-template">
    <div class="totalcontest-box totalcontest-box-activation">
        <div class="totalcontest-box-section">
            <div class="totalcontest-row">
                <div class="totalcontest-column">
                    <div class="totalcontest-box-content" ng-if="$ctrl.account.status">
                        <img src="<?php echo esc_attr( $this->env['url'] ); ?>assets/dist/images/activation/updates-on.svg" class="totalcontest-box-activation-image">
                        <div class="totalcontest-box-title"><?php  esc_html_e( 'Account Linked!', 'totalcontest' ); ?></div>
                        <div class="totalcontest-box-description"><?php  esc_html_e( 'Your account has been linked successfully.', 'totalcontest' ); ?></div>
                        <table class="wp-list-table widefat striped">
                            <tr>
                                <td><strong><?php  esc_html_e( 'Linked account', 'totalcontest' ); ?></strong></td>
                            </tr>
                            <tr>
                                <td>{{$ctrl.account.email}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="totalcontest-box-content" ng-if="!$ctrl.account.status">
                        <img src="<?php echo esc_attr( $this->env['url'] ); ?>assets/dist/images/activation/updates-off.svg" class="totalcontest-box-activation-image">
                        <div class="totalcontest-box-title"><?php  esc_html_e( 'Your TotalSuite Account', 'totalcontest' ); ?></div>
                        <div class="totalcontest-box-description"><?php  esc_html_e( 'Link your account purchases using an access token.', 'totalcontest' ); ?></div>
                        <div class="totalcontest-box-composed-form-error" ng-if="$ctrl.error">{{$ctrl.error}}</div>
                        <form class="totalcontest-box-composed-form" ng-submit="$ctrl.validate()">
                            <input type="text" class="totalcontest-box-composed-form-field" placeholder="<?php esc_attr_e( 'Access Token', 'totalcontest' ) ?>" ng-model="$ctrl.account.access_token">
                            <button type="submit" class="button button-primary button-large totalcontest-box-composed-form-button" ng-if="$ctrl.account.access_token" ng-disabled="!$ctrl.account.access_token || $ctrl.isProcessing()">{{
                                $ctrl.isProcessing() ? '<?php  esc_html_e( 'Linking...', 'totalcontest' ); ?>' : '<?php  esc_html_e( 'Connect', 'totalcontest' ); ?>' }}
                            </button>
                            <button type="button" class="button button-primary button-large totalcontest-box-composed-form-button" ng-if="!$ctrl.account.access_token" ng-click="$ctrl.openSignInPopup('<?php echo esc_js( $this->env['links.signin-account'] ); ?>')">
								<?php  esc_html_e( 'Get Access Token', 'totalcontest' ); ?>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="totalcontest-column">
                    <img src="<?php echo esc_attr( $this->env['url'] ); ?>assets/dist/images/activation/how-to.svg" alt="Get license code">
                </div>
            </div>
        </div>
    </div>
</script>
