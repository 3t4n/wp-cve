<?php

namespace Dotdigital_WordPress_Vendor;

/**
 * Credentials tab view
 *
 * This file is used to display the credentials tab
 *
 * @package    Dotdigital_WordPress
 *
 * @var array $account_info
 * @var Dotdigital_WordPress_Credentials_Admin $view
 * @var Dotdigital_WordPress_Setting_Form $form
 */
use Dotdigital_WordPress\Admin\Page\Tab\Dotdigital_WordPress_Credentials_Admin;
use Dotdigital_WordPress\Includes\Setting\Form\Dotdigital_WordPress_Setting_Form;
?>
<div class="wrap">
	<div class="card w-100 widefat">
		<?php 
$form->render();
?>
	</div>

	<div class="card w-100 widefat">
		<div class="inside">
			<h2>Account details</h2>
			<p><strong>Account holder name:</strong><br>
				<?php 
echo esc_html($account_info['Name'] ?? '--');
?>
			</p>
			<p><strong>Main account email address:</strong><br>
				<?php 
echo esc_html($account_info['MainEmail'] ?? '--');
?>
			</p>
			<p><strong>API calls in last hour:</strong><br>
				<?php 
echo esc_html($account_info['ApiCallsInLastHour'] ?? '--');
?>
			</p>
			<p><strong>API calls remaining:</strong><br>
				<?php 
echo esc_html($account_info['ApiCallsRemaining'] ?? '--');
?>
			</p>
			<p><strong>API endpoint:</strong><br>
				<?php 
echo esc_html($account_info['ApiEndpoint'] ?? '--');
?>
			</p>
		</div>
	</div>
</div>
<?php 
