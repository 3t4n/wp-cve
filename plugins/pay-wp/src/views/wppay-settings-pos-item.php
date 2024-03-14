<?php

$defualt_profile = $is_default_profile? '<span class="dashicons dashicons-star-filled wppay-tips" data-tip="'.__('Default currency', 'pay-wp').'"></span>' : '';
$test_mode = $is_test_mode? '<span class="dashicons dashicons-admin-tools wppay-tips" data-tip="'.__('Test mode', 'pay-wp').'"></span>' : '';
?>

<h3 data-currency="<?php echo $currency ?>">
	<span style="display: inline-block; width: 350px"><p><?php echo $defualt_profile.' '.$test_mode; ?> <strong><?php echo $currency; ?> </strong></p></span>
	<span style="display: inline-block; width: 80px; text-align: right; float: right"><a href="#" class="remove_wppay_profile"><span class="dashicons dashicons-trash"></span></a></span>
</h3>
<div>
	<?php require 'wppay-settings-pos-item-form.php'; ?>
</div>
