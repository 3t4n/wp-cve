<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress;

use WP_User;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Add Vat field in user account.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WordPress
 */
class User implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * Fires hooks
     */
    public function hooks()
    {
        \add_action('show_user_profile', [$this, 'add_vat_user_field']);
        \add_action('edit_user_profile', [$this, 'add_vat_user_field']);
        \add_action('personal_options_update', [$this, 'save_vat_user_field']);
        \add_action('edit_user_profile_update', [$this, 'save_vat_user_field']);
    }
    /**
     * @param WP_User $user
     *
     * @internal You should not use this directly from another application
     */
    public function add_vat_user_field(\WP_User $user)
    {
        ?>
		<script id="vat_number_row" type="template/text">
			<tr>
				<th><label for="vat_number"><?php 
        \esc_html_e('VAT Number', 'flexible-invoices');
        ?></label>
				</th>

				<td>
					<input type="text" name="vat_number" id="vat_number" value="<?php 
        echo \esc_attr(\get_the_author_meta('vat_number', $user->ID));
        ?>" class="regular-text"/><br/>
					<span class="description"></span>
				</td>
			</tr>
		</script>
		<script>
			/**
			 * Adds a VAT Number field after the company or name field.
			 */
			jQuery( function ( $ ) {
				let vat_number_field = $('#vat_number_row').html();
				let billing_company_field = $('#billing_company' );
				let billing_last_name_field = $('#billing_last_name' );
				if( billing_company_field.length ) {
					billing_company_field.closest('tr').after( vat_number_field );
				} else {
					if( billing_last_name_field.length ) {
						billing_last_name_field.closest('tr').after( vat_number_field );
					}
				}
			})
		</script>
		<?php 
    }
    /**
     * @param int $user_id
     *
     * @internal You should not use this directly from another application
     */
    public function save_vat_user_field($user_id)
    {
        \check_admin_referer('update-user_' . $user_id);
        if (isset($_POST['vat_number']) && \current_user_can('edit_user', $user_id)) {
            \update_user_meta($user_id, 'vat_number', \sanitize_text_field(\wp_unslash($_POST['vat_number'])));
        }
    }
}
