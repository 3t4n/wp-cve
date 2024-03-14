<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
<form name="form1" method="post" action="">
<?php wp_nonce_field( $this->namespace."-backend_nonce", $this->namespace."-backend_nonce", false ); ?>



<h2><?php _e('Login Config', $this->namespace); ?></h2>

<table class="form-table">
<tr valign="top">
<th scope="row"><label for="<?php echo $this->login_field_name; ?>"><?php _e("Add Terms to Login:", $this->namespace); ?></label></th>
<td><select name="<?php echo $this->login_field_name; ?>" id="<?php echo $this->login_field_name; ?>">
<option value="1" <?php  if ($this->options[$this->login_field_name] == 1){ echo 'selected="selected"'; }  ?>>Yes</option>
<option value="0" <?php  if ($this->options[$this->login_field_name] == 0){ echo 'selected="selected"';}  ?>>No</option>
</select>
</td>
</tr>


<?php   if ($this->options[$this->login_field_name] == 1){   ?>


<tr valign="top">
<th scope="row"><label for="<?php echo $this->login_remember_name; ?>"><?php _e("Remember Terms:", $this->namespace); ?></label></th>
<td><select name="<?php echo $this->login_remember_name; ?>" id="<?php echo $this->login_remember_name; ?>">
<option value="1" <?php  if ($this->options[$this->login_remember_name] == 1){ echo 'selected="selected"'; }  ?>>Yes</option>
<option value="0" <?php  if ($this->options[$this->login_remember_name] == 0){ echo 'selected="selected"';}  ?>>No</option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row"><label><?php _e('Login Terms: ', $this->namespace); ?></label></th>
<td><?php $settings = array( 'media_buttons' => false, 'textarea_rows' => 2);
 wp_editor( $this->options[$this->login_message_name], $this->login_message_name, $settings); ?>
(<a href="https://lhero.org/plugins/lh-agree-to-terms/#<?php echo $this->login_message_name; ?>"><?php _e("What does this mean?", $this->namespace); ?></a>)
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="<?php echo $this->login_validity_name; ?>"><?php _e("Login Validity Message;", $this->namespace ); ?></label></th>
<td><input type="text" id="<?php echo $this->login_validity_name; ?>" name="<?php echo $this->login_validity_name; ?>" value="<?php echo $this->options[ $this->login_validity_name ]; ?>" size="50" />(<a href="https://lhero.org/plugins/lh-agree-to-terms/#<?php echo $this->login_validity_name; ?>">What does this mean?</a>)
</td>
</tr>

<?php  }  ?>

</table>


<h2><?php _e('Registration Config', $this->namespace); ?></h2>
<table class="form-table">
<tr valign="top">
<th scope="row"><label for="<?php echo $this->registration_field_name; ?>"><?php _e("Add Terms to registration:", $this->namespace); ?></label></th>
<td><select name="<?php echo $this->registration_field_name; ?>" id="<?php echo $this->registration_field_name; ?>">
<option value="1" <?php  if ($this->options[$this->registration_field_name] == 1){ echo 'selected="selected"'; }  ?>>Yes</option>
<option value="0" <?php  if ($this->options[$this->registration_field_name] == 0){ echo 'selected="selected"';}  ?>>No</option>
</select>
</td>
</tr>

<?php   if ($this->options[$this->registration_field_name] == 1){   ?>




<tr valign="top">
<th scope="row"><label><?php _e('Registration Terms: ', $this->namespace); ?></label></th>
<td><?php $settings = array( 'media_buttons' => false, 'textarea_rows' => 2);
 wp_editor( $this->options[$this->registration_message_name], $this->registration_message_name, $settings); ?>
(<a href="https://lhero.org/plugins/lh-agree-to-terms/#<?php echo $this->registration_message_name; ?>"><?php _e("What does this mean?", $this->namespace); ?></a>)
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="<?php echo $this->registration_validity_name; ?>"><?php _e("Registration Validity Message;", $this->namespace ); ?></label></th>
<td>
<input type="text" id="<?php echo $this->registration_validity_name; ?>" name="<?php echo $this->registration_validity_name; ?>" value="<?php echo $this->options[ $this->registration_validity_name ]; ?>" size="50" />(<a href="https://lhero.org/plugins/lh-agree-to-terms/#<?php echo $this->registration_validity_name; ?>">What does this mean?</a>)
</td>
</tr>

<?php  }  ?>

</table>

<?php submit_button(); ?>
</form>