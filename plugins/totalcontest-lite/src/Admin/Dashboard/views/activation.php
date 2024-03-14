<script type="text/ng-template" id="dashboard-activation-component-template">
    <div class="totalcontest-box totalcontest-box-activation">
        <div class="totalcontest-box-section">
            <div class="totalcontest-row">
                <div class="totalcontest-column">
                    <div class="totalcontest-box-content" ng-if="$ctrl.activation.status">
                        <img src="<?php
						echo esc_attr( $this->env['url'] ); ?>assets/dist/images/activation/updates-on.svg"
                             class="totalcontest-box-activation-image">
                        <div class="totalcontest-box-title"><?php
							esc_html_e( 'Product activated!', 'totalcontest' ); ?></div>
                        <div class="totalcontest-box-description"><?php
							esc_html_e( 'You\'re now receiving updates.', 'totalcontest' ); ?></div>
                        <div class="totalcontest-box-composed-form">

                            <table class="wp-list-table widefat striped">
                                <tr>
                                    <td><strong><?php
											esc_html_e( 'Activation code', 'totalcontest' ); ?></strong></td>
                                </tr>
                                <tr>
                                    <td>{{$ctrl.activation.key}}</td>
                                </tr>
                                <tr>
                                    <td><strong><?php
											esc_html_e( 'Licensed to', 'totalcontest' ); ?></strong></td>
                                </tr>
                                <tr>
                                    <td>{{$ctrl.activation.email}}</td>
                                </tr>
                            </table>
                            <form ng-submit="$ctrl.validateDeactivation()">
                                <button type="submit"
                                        class="button button-primary button-large totalcontest-box-composed-form-button w-100"
                                        ng-disabled="!$ctrl.activation.key || !$ctrl.activation.email || $ctrl.isProcessing()"
                                >{{$ctrl.isProcessing() ? '<?php esc_html_e( 'Unlinking
                                    ', '
                                    totalcontest
                                    ' ); ?>'
                                    :
                                    '<?php esc_html_e( '
                                    Unlink
                                    License
                                    ', '
                                    totalcontest
                                    ' ); ?>' }}
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="totalcontest-box-content" ng-if="!$ctrl.activation.status">
                        <img src="<?php
						echo esc_attr( $this->env['url'] ); ?>assets/dist/images/activation/updates-off.svg"
                             class="totalcontest-box-activation-image">
                        <div class="totalcontest-box-title"><?php
							printf( esc_html__( 'Product activation for %s', 'totalcontest' ),
								$this->env['domain'] ); ?></div>
                        <div class="totalcontest-box-description"><?php
							echo wp_kses( __( 'Open <a target="_blank" href="https://codecanyon.net/downloads">downloads page</a>, find the product, click "Download" then select on "License certificate & purchase code (text)".',
								'totalcontest' ),
								[ 'a' => [ 'href' => [], 'target' => [] ] ] ); ?></div>
                        <div class="totalcontest-box-composed-form-error" ng-if="$ctrl.error">{{$ctrl.error}}</div>
                        <form class="totalcontest-box-composed-form" ng-submit="$ctrl.validateActivation()">
                            <input type="text" class="totalcontest-box-composed-form-field"
                                   placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx" ng-model="$ctrl.activation.key">
                            <input type="email" class="totalcontest-box-composed-form-field"
                                   placeholder="email@domain.tld" ng-model="$ctrl.activation.email">
                            <button type="submit"
                                    class="button button-primary button-large totalcontest-box-composed-form-button"
                                    ng-disabled="!$ctrl.activation.key || !$ctrl.activation.email || $ctrl.isProcessing()">
                                {{
                                $ctrl.isProcessing() ? '<?php  esc_html_e( 'Activating
                                ', '
                                totalcontest
                                ' ); ?>'
                                :
                                '<?php  esc_html_e( '
                                Activate
                                ', '
                                totalcontest
                                ' ); ?>' }}
                            </button>
                        </form>
                    </div>
                </div>
                <div class="totalcontest-column">
                    <img src="<?php
					echo esc_attr( $this->env['url'] ); ?>assets/dist/images/activation/how-to.svg"
                         alt="Get license code">
                </div>
            </div>
        </div>
    </div>
</script>
