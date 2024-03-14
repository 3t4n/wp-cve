<?php
defined('ABSPATH') or die("No direct script access!");
?>
<div class="qc-voice-widgets-admin-panel">
  <h2><?php esc_html_e('Welcome to the Voice Widget for Contact Form 7', 'voice-widgets'); ?></h2>
  <h4><?php esc_html_e('Getting Started', 'voice-widgets'); ?></h4>
</div>
<div class="qc-voice-widgets-accordion">


  <div class="qc-voice-widgets-accordion-item">
    <input type="checkbox" id="accordion2">
    <label for="accordion2" class="qc-voice-widgets-accordion-item-title"><span class="icon"></span><?php esc_html_e('How can I enter voice message field to my contact forms?', 'voice-widgets'); ?></label>
    <div class="qc-voice-widgets-accordion-item-desc">
        <?php esc_html_e('Navigate to Contact form 7->Add/Edit a form in wp-admin and Press the Voice Message button to insert shortcode in the form.', 'voice-widgets'); ?><br><br>
        <img src="<?php echo esc_url( QC_VOICEWIDGET_ASSETS_URL . 'images/screenshot-1.jpg' );?>"/>
    </div>
  </div>

  <div class="qc-voice-widgets-accordion-item">
    <input type="checkbox" id="accordion3">
    <label for="accordion3" class="qc-voice-widgets-accordion-item-title"><span class="icon"></span><?php esc_html_e('How can I set voice field with Contact from 7 email  setting?', 'voice-widgets'); ?></label>
    <div class="qc-voice-widgets-accordion-item-desc">
        <?php esc_html_e('Navigate to Contact form 7->Add/Edit. Select the Mail tab and add [qcwpvoicemessage] this shortcode with your mail body.', 'voice-widgets'); ?><br><br>
        <img src="<?php echo esc_url( QC_VOICEWIDGET_ASSETS_URL .  'images/screenshot-2.png' );?>"/> 
    </div>
  </div>

  <div class="qc-voice-widgets-accordion-item">
    <input type="checkbox" id="accordion4">
    <label for="accordion4" class="qc-voice-widgets-accordion-item-title"><span class="icon"></span><?php esc_html_e('Where Can I Listen to my Voice Message?', 'voice-widgets'); ?></label>
    <div class="qc-voice-widgets-accordion-item-desc">
     <?php esc_html_e('If a user records a voice message, you will receive a link in the email sent by Contact Form 7.  In the Pro Version, the voice messages can be accessed from a sub menu of voice widgets', 'voice-widgets'); ?>
    </div>
  </div>

  <div class="qc-voice-widgets-accordion-item">
    <input type="checkbox" id="accordion5">
    <label for="accordion5" class="qc-voice-widgets-accordion-item-title"><span class="icon"></span><?php esc_html_e('I received a email but not voice message?', 'voice-widgets'); ?></label>
    <div class="qc-voice-widgets-accordion-item-desc">
    <?php esc_html_e('Make sure you add this [qcwpvoicemessage] shortcode to your contact form 7 mail body. Then if a user records a voice message, you will receive a link in the email sent by Contact Form 7', 'voice-widgets'); ?>
    </div>
  </div>

</div>
   