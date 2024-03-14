<?php

wp_enqueue_script( 'wfty-modal-script', WFFN_Core()->get_plugin_url() . '/modules/thankyou-pages/assets/js/wfty-modal.js', array( 'jquery' ), WFFN_VERSION );
wp_enqueue_style( 'wfty-modal-style', WFFN_Core()->get_plugin_url() . '/modules/thankyou-pages/assets/css/wfty-modal.css', null, WFFN_VERSION );
wp_enqueue_style( 'wfty-modal-common-style', WFFN_Core()->get_plugin_url() . '/modules/thankyou-pages/assets/css/wfty-mb-common.css', null, WFFN_VERSION );

?>

    <div class='' id="wfty_shortcode_help_box" style="display: none;">

        <h3><?php esc_html_e( 'Shortcodes', 'funnel-builder' ); ?></h3>
        <div style="font-size: 1.1em; margin: 5px;"><i><?php esc_html_e( 'Here are set of Shortcodes that can be used on this page.', 'funnel-builder' ); ?> </i></div>

        <h3><strong><?php esc_html_e( 'Personalization Shortcodes', 'funnel-builder' ); ?></strong></h3>

        <table class="table widefat">
            <caption><p style="float: left;"><?php esc_html_e( 'To personalize Thank You Page with different, use these merge tags-', 'funnel-builder' ); ?></p></caption>
            <thead>
            <tr>
                <td width="300"><?php esc_html_e( 'Customer', 'funnel-builder' ); ?></td>
                <td><?php esc_html_e( 'Shortcodes', 'funnel-builder' ); ?></td>
            </tr>
            </thead>
            <tbody>

            <tr>
                <td><?php esc_html_e( 'Customer First Name', 'funnel-builder' ); ?></td>
                <td><input type="text" style="width: 75%;" onClick="this.select()" readonly
                           value='[wfty_customer_first_name]'/>
                </td>
            </tr>

            <tr>
                <td><?php esc_html_e( 'Customer Last Name', 'funnel-builder' ); ?></td>
                <td><input type="text" style="width: 75%;" onClick="this.select()" readonly
                           value='[wfty_customer_last_name]'/>
                </td>
            </tr>

            <tr>
                <td><?php esc_html_e( 'Customer Email', 'funnel-builder' ); ?></td>
                <td><input type="text" style="width: 75%;" onClick="this.select()" readonly
                           value='[wfty_customer_email]'/>
                </td>
            </tr>

            <tr>
                <td><?php esc_html_e( 'Customer Phone Number', 'funnel-builder' ); ?></td>
                <td><input type="text" style="width: 75%;" onClick="this.select()" readonly
                           value='[wfty_customer_phone_number]'/>
                </td>
            </tr>

            <tr>
                <td><?php esc_html_e( 'Order Number', 'funnel-builder' ); ?></td>
                <td><input type="text" style="width: 75%;" onClick="this.select()" readonly
                           value='[wfty_order_number]'/>
                </td>
            </tr>
			<tr>
				<td><?php esc_html_e( 'Order Total', 'funnel-builder' ); ?></td>
				<td><input type="text" style="width: 75%;" onClick="this.select()" readonly
						   value='[wfty_order_total]'/>
				</td>
			</tr>
			<?php
			do_action( 'wffn_addon_help_shortcodes' );
			?>
            </tbody>
        </table>

    </div>
<?php
