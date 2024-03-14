<?php

namespace ZPOS\Admin;

use ZPOS\Model;

class User
{
	public function __construct()
	{
		add_action('show_user_profile', [$this, 'render_control']);
		add_action('edit_user_profile', [$this, 'render_control']);
		add_action('personal_options_update', [$this, 'save']);
		add_action('edit_user_profile_update', [$this, 'save']);
	}

	public function render_control(\WP_User $user): void
	{
		?>
				<h2><?php echo esc_html__('Customer billing Tax/VAT'); ?></h2>
				<table class="form-table">
						<tr>
								<th><label><?php echo esc_html(Model\VatControl::get_label()); ?></label></th>
								<td><?php (new Model\BillingVat($user->ID))->render_control(); ?></td>
						</tr>
				</table>
				<?php
	}

	public function save(int $user_id): void
	{
		if (
			!current_user_can('edit_user', $user_id) ||
			empty($_POST['_wpnonce']) ||
			!wp_verify_nonce(sanitize_key(wp_unslash($_POST['_wpnonce'])), "update-user_$user_id")
		) {
			return;
		}

		(new Model\BillingVat($user_id))->save_post_data();
	}
}
