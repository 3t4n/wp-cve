<div id="totalcontest-upgrade-to-pro" class="wrap totalcontest-page">
    <h1><?php  esc_html_e( 'Upgrade to TotalContest Pro', 'totalcontest' ); ?></h1>
    <p><?php  esc_html_e( 'Enjoy TotalContest features without limits!', 'totalcontest' ); ?></p>

    <div class="totalcontest-pro-benefits">
        <div class="totalcontest-pro-benefits-body">
            <div class="totalcontest-row">
                <div class="totalcontest-column">
                    <img height="64" src="<?php echo esc_attr( $this->env['url'] ); ?>assets/dist/images/editor/customization.svg">
                    <h3>Unlock All Features</h3>
                    <p>Upgrade to pro version and enjoy TotalContest features without any limits.</p>
                </div>
                <div class="totalcontest-column">
                    <img height="64" src="<?php echo esc_attr( $this->env['url'] ); ?>assets/dist/images/activation/updates-on.svg">
                    <h3>Priority Updates</h3>
                    <p>Get new features and improvements regularly and exclusively.</p>
                </div>
                <div class="totalcontest-column">
                    <img height="64" src="<?php echo esc_attr( $this->env['url'] ); ?>assets/dist/images/support/customer-support.svg">
                    <h3>Premium Support</h3>
                    <p>Get access to our five-stars customer support with guaranteed satisfaction.</p>
                </div>
            </div>
        </div>
        <div class="totalcontest-pro-features-comparison">
            <table>
                <thead>
                <tr>
                    <th>Feature</th>
                    <th>Lite</th>
                    <th>Pro</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Unlimited contests</td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr>
                    <td>Unlimited submissions</td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr>
                    <td>Unlimited custom fields</td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr>
                    <td>Supported custom field types</td>
                    <td>3</td>
                    <td>10+</td>
                </tr>
                <tr>
                    <td>Participation and vote limitations</td>
                    <td>Time</td>
                    <td>Time, Membership and Quota</td>
                </tr>
                <tr>
                    <td>Participation and vote frequency controllers</td>
                    <td>Cookies</td>
                    <td>Cookies, IP, Logged in user</td>
                </tr>
                <tr>
                    <td>Structured data</td>
                    <td>&mdash;</td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr>
                    <td>Full checks on page load</td>
                    <td>&mdash;</td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr>
                    <td>Recaptcha by google</td>
                    <td>&mdash;</td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr>
                    <td>Email notification</td>
                    <td>&mdash;</td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr>
                    <td>Push notification</td>
                    <td>&mdash;</td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr>
                    <td>Webhook notification</td>
                    <td>&mdash;</td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr>
                    <td>Priority updates</td>
                    <td>&mdash;</td>
                    <td><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr>
                    <td>Customer support</td>
                    <td>Community</td>
                    <td>Premium</td>
                </tr>
                </tbody>
            </table>

            <div class="totalcontest-pro-features-comparison-expand" onclick="jQuery(this).parent().addClass('expanded')">
                <span>Expand</span>
                <span class="dashicons dashicons-arrow-down-alt2"></span>
            </div>
        </div>
        <div class="totalcontest-pro-benefits-footer">
            <img src="<?php echo esc_attr( $this->env['url'] ); ?>assets/dist/images/general/upgrade.svg" alt="Upgrade">
            <div class="totalcontest-pro-offer">
                <h3>Get TotalContest Pro</h3>
                <p>Plus 12 months of premium support.</p>
            </div>
			<?php
			$url = add_query_arg(
				[
					'utm_source'   => 'in-app',
					'utm_medium'   => 'upgrade-to-pro-page',
					'utm_campaign' => 'totalcontest-lite-to-pro',
				],
				$this->env['links.website']
			);
			?>
            <a href="<?php echo esc_attr( $url ) ?>" target="_blank" class="totalcontest-pro-upgrade-cta">Upgrade Now!</a>
        </div>
    </div>

</div>
