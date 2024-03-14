<?php

namespace UpsFreeVendor;

/**
 * OAuth field.
 *
 * @var string $authorize_action
 * @var string $revoke_action
 * @var string $field_key
 * @var string $value
 * @var string $field_class
 * @var \Octolize\WooCommerceShipping\Ups\OAuth\TokenOption $token_option
 */
?>
<tr valign="top">
	<th scope="row" class="titledesc">
		<label for="<?php 
echo \esc_attr($this->field_key);
?>"><?php 
echo \wp_kses_post($this->data['title']);
?></label>
	</th>
	<td class="forminp">
		<?php 
if (empty($token_option->get())) {
    ?>
			<a href="<?php 
    echo \esc_url($authorize_action);
    ?>" type="submit"
			   class="button button-primary"><?php 
    echo \esc_html(\__('Authorize', 'flexible-shipping-ups'));
    ?></a>
			<p class="description">
				<?php 
    echo \esc_html(\__('Clicking the button will open up the UPS website. Please provide your credentials and mark the checkbox to connect our plugin with your UPS account.', 'flexible-shipping-ups'));
    ?>
			</p>
		<?php 
} else {
    ?>
			<a href="<?php 
    echo \esc_url($revoke_action);
    ?>" type="submit"
			   class="button"><?php 
    echo \esc_html(\__('Revoke', 'flexible-shipping-ups'));
    ?></a>
			<p class="description">
				<?php 
    echo \esc_html(\__('Clicking the button will disconnect your UPS account from our plugin.', 'flexible-shipping-ups'));
    ?>
				<!-- Expires at: <?php 
    echo $token_option->get_expires_at();
    ?>	-->
				<!-- Expires at: <?php 
    echo \date(\DATE_RFC2822, $token_option->get_expires_at());
    ?> -->
				<!-- Expires in: <?php 
    echo $token_option->get_expires_in();
    ?> -->
				<!-- Issued at: <?php 
    echo $token_option->get_issued_at();
    ?> -->
				<!-- <?php 
    \print_r($token_option->get());
    ?> -->
			</p>
		<?php 
}
?>
		<fieldset>
			<input class="<?php 
echo \esc_attr($field_class);
?>" type="hidden"
				   name="<?php 
echo \esc_attr($field_key);
?>" value="<?php 
echo \esc_attr($value);
?>"/>
		</fieldset>
	</td>
</tr>
<?php 
