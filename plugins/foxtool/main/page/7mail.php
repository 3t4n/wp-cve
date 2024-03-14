<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options; ?>
<h2><?php _e('MAIL', 'foxtool'); ?></h2>
<div class="ft-on">
<label class="nut-fton">
<input class="toggle-checkbox" id="check7" data-target="play7" type="checkbox" name="foxtool_settings[mail]" value="1" <?php if ( isset($foxtool_options['mail']) && 1 == $foxtool_options['mail'] ) echo 'checked="checked"'; ?> />
<span class="ftder"></span></label>
<label class="ft-on-right"><?php _e('ON/OFF', 'foxtool'); ?></label>
</div>

<div id="play7" class="ft-card-mail toggle-div">
	<a class="ft-smtp-a" onclick="ftnone(event, 'ft-smtp')"><i class="fa-regular fa-share"></i> <?php _e('Configure SMTP mail', 'foxtool') ?></a>
	<div id="ft-smtp" style="display:none">
	<h3><i class="fa-regular fa-envelope"></i> <?php _e('Configure SMTP mail', 'foxtool') ?></h3>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[mail-gsmtp1]" value="1" <?php if (isset($foxtool_options['mail-gsmtp1']) && 1 == $foxtool_options['mail-gsmtp1']) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable SMTP mail', 'foxtool'); ?></label>
	</p>
	<p class="ft-note ft-note-red"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Enable SMTP mail to allow SMTP mail to function and send emails when you perform a test', 'foxtool'); ?>
	</p>
	<div class="ft-card-mail-set">
	<a title="Gmail" id="mail1"><img src="<?php echo esc_url(FOXTOOL_URL .'img/gmail.png'); ?>" /></a>
	<a title="Larksuite" id="mail2"><img src="<?php echo esc_url(FOXTOOL_URL .'img/larksuite.png'); ?>" /></a>
	<a title="Yandex" id="mail3"><img src="<?php echo esc_url(FOXTOOL_URL .'img/yandex.png'); ?>" /></a>
	</div>
	
	<p style="font-weight:bold"><?php _e('Sender name', 'foxtool'); ?><p>
	<p><input class="ft-input-big"  placeholder="<?php _e('Sender name', 'foxtool'); ?>" name="foxtool_settings[mail-gsmtp11]" type="text" value="<?php if(!empty($foxtool_options['mail-gsmtp11'])){echo sanitize_text_field($foxtool_options['mail-gsmtp11']);} ?>"/></p>
	
	<p style="font-weight:bold"><?php _e('Sender email address', 'foxtool'); ?><p>
	<p><input class="ft-input-big"  placeholder="name@mail.com" name="foxtool_settings[mail-gsmtp12]" type="text" value="<?php if(!empty($foxtool_options['mail-gsmtp12'])){echo sanitize_text_field($foxtool_options['mail-gsmtp12']);} ?>"/></p>
	
	<p style="font-weight:bold"><?php _e('Account', 'foxtool'); ?><p>
	<p><input class="ft-input-big"  placeholder="<?php _e('Account', 'foxtool'); ?>" name="foxtool_settings[mail-gsmtp13]" type="text" value="<?php if(!empty($foxtool_options['mail-gsmtp13'])){echo sanitize_text_field($foxtool_options['mail-gsmtp13']);} ?>"/></p>
	
	<p style="font-weight:bold"><?php _e('App password', 'foxtool'); ?><p>
	<p><input class="ft-input-big"  placeholder="<?php _e('App password', 'foxtool'); ?>" name="foxtool_settings[mail-gsmtp14]" type="password" value="<?php if(!empty($foxtool_options['mail-gsmtp14'])){echo sanitize_text_field($foxtool_options['mail-gsmtp14']);} ?>"/></p>
	
	<p style="font-weight:bold"><?php _e('SMTP server', 'foxtool'); ?><p>
	<p><input id="mail-sever" class="ft-input-big" name="foxtool_settings[mail-gsmtp15]" type="text" value="<?php if(!empty($foxtool_options['mail-gsmtp15'])){echo sanitize_text_field($foxtool_options['mail-gsmtp15']);} else {echo sanitize_text_field('smtp.gmail.com');} ?>"/></p>
	
	<p style="font-weight:bold"><?php _e('SMTP port', 'foxtool'); ?><p>
	<p><input id="mail-host" class="ft-input-big" style="width:90px;" name="foxtool_settings[mail-gsmtp16]" type="number" value="<?php if(!empty($foxtool_options['mail-gsmtp16'])){echo sanitize_text_field($foxtool_options['mail-gsmtp16']);} else {echo sanitize_text_field('587');} ?>"/></p>
	
	<p style="font-weight:bold"><?php _e('SMTP protocol', 'foxtool'); ?><p>
	<p>
	<?php $styles = array('starttls', 'tls', 'ssl'); ?>
	<select id="mail-check" style="width:90px;" name="foxtool_settings[mail-gsmtp17]"> 
	<?php foreach($styles as $style) { ?> 
	<?php if(isset($foxtool_options['mail-gsmtp17']) && $foxtool_options['mail-gsmtp17'] == $style) { $selected = 'selected="selected"'; } else { $selected = ''; } ?>
	<option value="<?php echo $style; ?>" <?php echo $selected; ?>><?php echo $style; ?></option> 
	<?php } ?> 
	</select>
	</p>
	<p style="font-weight:bold"><?php _e('Authenticate outgoing mail', 'foxtool'); ?><p>
	<p>
	<label class="ft-container"><?php _e('SMTP authentication', 'foxtool'); ?>
	<input id="mail-scuti" type="checkbox" name="foxtool_settings[mail-gsmtp18]" value="1" <?php if (isset($foxtool_options['mail-gsmtp18']) && 1 == $foxtool_options['mail-gsmtp18']) echo 'checked="checked"'; ?> />
	<span class="ft-checkmark"></span></label>
	</p>
	<a id="ft-send-email" href="javascript:void(0)" data-nonce="<?php echo wp_create_nonce('ft-send-email-nonce'); ?>">
		<?php echo get_option('admin_email'); ?><br>
		<?php _e('Send test email to administrator account', 'foxtool'); ?>
	</a>
	<div id="ft-sen-end"></div>
	<div class="edoi" style="display:none"><img src="<?php echo esc_url(FOXTOOL_URL . 'img/load4.gif'); ?>" /> <?php _e('Please wait for the email to be sent', 'foxtool'); ?></div>
	<script>
	// cấu hình email
	function updateFields(buttonId) {
			var serverInput = document.getElementById("mail-sever");
			var hostInput = document.getElementById("mail-host");
			var checkSelect = document.getElementById("mail-check");
			var scutiCheckbox = document.getElementById("mail-scuti");
			switch (buttonId) {
				case "mail1":
					serverInput.value = "smtp.gmail.com";
					hostInput.value = 587;
					checkSelect.value = "tls";
					scutiCheckbox.checked = true;
					break;
				case "mail2":
					serverInput.value = "smtp.larksuite.com";
					hostInput.value = 465;
					checkSelect.value = "ssl";
					scutiCheckbox.checked = true;
					break;
				case "mail3":
					serverInput.value = "smtp.yandex.com";
					hostInput.value = 465;
					checkSelect.value = "ssl";
					scutiCheckbox.checked = true;
					break;
			}
		}
		document.getElementById("mail1").addEventListener("click", function () {
			updateFields("mail1");
		});
		document.getElementById("mail2").addEventListener("click", function () {
			updateFields("mail2");
		});
		document.getElementById("mail3").addEventListener("click", function () {
			updateFields("mail3");
	});
	// Gửi email
	jQuery(document).ready(function($) {
		$('#ft-send-email').click(function(event) {
			event.preventDefault();
			$('.edoi').show();
			var nonce = $('#ft-send-email').data('nonce');
			$.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				type: 'POST',
				data: {
					action: 'ft_send_email',
					nonce: nonce
				},
				success: function(response) {
					$('#ft-sen-end').html('<span>'+ response + '</span>');
					$('.edoi').hide();
				}
			});
		});
	});
	</script>
	</div>
  
  <h3><i class="fa-regular fa-paper-plane-top"></i> <?php _e('Create welcome email for registered users', 'foxtool') ?></h3>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[mail-new1]" value="1" <?php if ( isset($foxtool_options['mail-new1']) && 1 == $foxtool_options['mail-new1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable email sending', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('You need to activate this feature and customize the content below so that new registered users can receive emails', 'foxtool'); ?></p>
	<p>
	<input class="ft-input-big" placeholder="<?php _e('Email subject', 'foxtool') ?>" name="foxtool_settings[mail-new11]" type="text" value="<?php if(!empty($foxtool_options['mail-new11'])){echo sanitize_text_field($foxtool_options['mail-new11']);} ?>"/>
	</p>
	<p>
	<textarea style="height:150px;" class="ft-code-textarea" name="foxtool_settings[mail-new12]" placeholder="<?php _e('Enter email content here', 'foxtool'); ?>"><?php if(!empty($foxtool_options['mail-new12'])){echo esc_textarea($foxtool_options['mail-new12']);} ?></textarea>
	</p>				  
	
  <h3><i class="fa-regular fa-message-captions"></i> <?php _e('Comment notification', 'foxtool') ?></h3>
	<!-- mail comment 1 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[mail-com1]" value="1" <?php if ( isset($foxtool_options['mail-com1']) && 1 == $foxtool_options['mail-com1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Email notification when there a reply', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('If someone replies to a comment, there will be an email notification sent to the commenter', 'foxtool'); ?></p>
	<!-- mail comment 2 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[mail-com2]" value="1" <?php if ( isset($foxtool_options['mail-com2']) && 1 == $foxtool_options['mail-com2'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Email notification when a post has a comment', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Notify the post author when someone comments on the post', 'foxtool'); ?></p>
</div>	