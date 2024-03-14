<?php
if(!defined('ABSPATH'))
	exit;
	$api_key = esc_html(get_option('copymatic_apikey'));
?>
<div class="wrap">
	<h1>Copymatic Settings</h1>
	<form method="post" action="" novalidate="novalidate" id="copymatic_key_submit">
		<table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"><label for="copymatic_apikey"><?php _e("API Key", "copymatic"); ?></label></th>
                    <td>
                        <input name="copymatic_apikey" type="text" id="copymatic_apikey" value="<?php echo $api_key; ?>" class="regular-text">
                        <p class="description" id="apikey-description">
                        <?php _e("You can find your API key <a href=\"https://copymatic.ai/api-access/\" target=\"_blank\">here</a>.<br>Don't have an API key? Please create an account <a href=\"https://copymatic.ai/\" target=\"_blank\">here</a>.", "copymatic"); ?>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
		<p class="submit"><button type="button" id="submit-copymatic-api-key" class="button button-primary"><?php _e("Save Settings", "copymatic"); ?></button></p>
	</form>
</div>