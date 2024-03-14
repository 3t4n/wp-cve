<div id="lf_form_container">
    <form method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
        <h3><?= get_option('lf_form_heading'); ?></h3>
        <p id="lf_first_name_block">
            <label class="description" for="lf_first_name"><?= get_option('lf_firstname_field_label'); ?></label>
            <input id="lf_first_name" name="lf_first_name" type="text" maxlength="255" value="<?= (isset($_POST['lf_first_name']) ? esc_attr($_POST['lf_first_name']) : '' ); ?>" required='required' placeholder="<?= get_option('lf_firstname_placeholder_label'); ?>" />
        </p>
        <p id="lf_last_name_block">
            <label class="description" for="lf_last_name"><?= get_option('lf_lastname_field_label'); ?></label>
            <input id="lf_last_name" name="lf_last_name" type="text" maxlength="255" value="<?= (isset($_POST['lf_last_name']) ? esc_attr($_POST['lf_last_name']) : '' ); ?>" required='required' placeholder="<?= get_option('lf_lastname_placeholder_label'); ?>" />
        </p>
        <p id="lf_email_block">
            <label class="description" for="lf_email"><?= get_option('lf_email_field_label'); ?></label>
            <input id="lf_email" name="lf_email" type="email" maxlength="255" value="<?= (isset($_POST['lf_email']) ? esc_attr($_POST['lf_email']) : '' ); ?>" required='required' placeholder="<?= get_option('lf_email_placeholder_label'); ?>" />
        </p>
        <p id="lf_phone_block">
            <label class="description" for="lf_phone"><?= get_option('lf_phone_field_label'); ?></label>
            <input id="lf_phone" name="lf_phone" type="text" maxlength="24" value="<?= (isset($_POST['lf_phone']) ? esc_attr($_POST['lf_phone']) : '' ); ?>" placeholder="<?= get_option('lf_phone_placeholder_label'); ?>" />
        </p>
        <p id="lf_message_block">
            <label class="description" for="lf_message"><?= get_option('lf_message_field_label'); ?></label>
			<textarea id="lf_message" name="lf_message" required='required' placeholder="<?= get_option('lf_message_placeholder_label') ?>"><?= (isset($_POST['lf_message']) ? esc_attr($_POST['lf_message']) : '' ); ?></textarea>
        </p>

		<?php $disclaimer_text = get_option('lf_disclaimer_text'); if(!empty($disclaimer_text)): ?>
		<p id="lf_disclaimer">
			<input type="checkbox" id="lf_disclaimer" name="lf_disclaimer_checkbox" <?= $this->is_selected('i_agree',$_POST['lf_disclaimer_checkbox'],'checkbox') ?> value="i_agree" required='true'/>
			<label class="description" for="lf_disclaimer"><?= $disclaimer_text; ?> *</label>
		</p>
		<?php endif; ?>

        <div id="lf_wrap" style="display:none;">
            <label class="description" for="leave_this_blank">Leave this Blank if are sentient </label>
            <input name="leave_this_blank_url" type="text" value="" id="leave_this_blank"/>
        </div>

		<?php if(get_option('lf_recaptcha_site_key')) { ?>
			<p class="g-recaptcha" data-sitekey="<?= get_option('lf_recaptcha_site_key'); ?>"></p>
		<?php } ?>

        <input type="hidden" name="leave_this_alone" value="<?php echo base64_encode(time()); ?>"/>
        <p class="buttons">
            <input type="hidden" name="form_id" value="1044046" />
            <input id="saveForm" class="button_text" type="submit" name="lf_submit" value="<?php echo get_option('lf_submit_button_text'); ?>" style="background-color: <?php echo get_option('lf_submit_button_color'); ?>; color:<?php echo get_option('lf_submit_button_text_color'); ?>" />
        </p>
    </form>
</div>
