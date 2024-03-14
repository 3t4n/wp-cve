<div class="row ycf-option-row">
	<div class="col-md-2">
		<span>To</span>
	</div>
	<div class="col-md-4">
		<input type="text" class="form-control col-md-2" name="contact-form-send-to-email" value="<?php echo esc_html($contactFormSendToEmail); ?>">
	</div>
</div>
<div class="row ycf-option-row">
	<div class="col-md-2">
		<span>From</span>
	</div>
	<div class="col-md-4">
		<input type="text" class="form-control col-md-2" name="contact-form-send-from-email" value="<?php echo esc_html($contactFormSendFromEmail);?>">
	</div>
</div>
<div class="row ycf-option-row">
	<div class="col-md-2">
		<span>Subject</span>
	</div>
	<div class="col-md-4">
		<input type="text" class="form-control col-md-2" name="contact-form-send-email-subject" value="<?php echo esc_html($contactFormSendEmailSubject);?>">
	</div>
</div>
<div class="row ycf-option-row">
	<div class="col-md-2">
		<span>Message</span>
	</div>
	<div class="col-md-4">
       <?php
            $content = $ycfMessage;
            $editorId = 'ycf-message';
            $settings = array(
               'wpautop' => false,
               'tinymce' => array(
                   'width' => '100%',
               ),
               'textarea_rows' => '6',
               'media_buttons' => true
            );
            wp_editor($content, $editorId, $settings);
       ?>
	</div>
</div>