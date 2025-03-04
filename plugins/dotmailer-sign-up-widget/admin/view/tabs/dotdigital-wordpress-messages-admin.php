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
		<p><a href="https://support.dotdigital.com/hc/en-gb/articles/212216058-Using-the-dotmailer-WordPress-sign-up-form-plugin-v2#Messages" target="_blank">Find out more...</a></p>
	</div>

</div>
<?php 
