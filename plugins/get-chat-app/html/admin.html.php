<?php
/**
 * Admin Page HTML
*/

$settings = get_option('gcap_settings');
    
if ($settings) {

  /* Get saved settings */
  $otherSettings = (object) array(
    'enabled' => $settings['status'],
    'position' => $settings['position'],
  );

  $whatsappSettings = (object) array(
    'whatsapp' => (!empty($settings['whatsapp']) )? $settings['whatsapp'] : false,
    'mobileNumber' => $settings['mobileNumber'],
    'titleMessage' => html_entity_decode($settings['titleMessage']),
    'welcomeMessage' => stripcslashes(preg_replace('#<br\s*/?>#i', "\n", html_entity_decode($settings['welcomeMessage']))),
  );
  
  $facebookSettings = (object) array(
    'facebook' => (!empty($settings['facebook']) )? $settings['facebook'] : false,
    'facebookPageId' => $settings['facebookPageId'],
    'facebookMessage' => stripcslashes(preg_replace('#<br\s*/?>#i', "\n", html_entity_decode($settings['facebookMessage']))),
    'facebookReplyTime' => html_entity_decode($settings['facebookReplyTime']),
  );
  
  $emailSettings = (object) array(
    'email' => (!empty($settings['email']) )? $settings['email'] : false,
    'gcaEmailAddress' => $settings['gcaEmailAddress'],
    'gcaEmailSubject' => stripcslashes(html_entity_decode($settings['gcaEmailSubject'])),
  );

  $instagramSettings = (object) array(
    'instagram' => (!empty($settings['instagram']) )? $settings['instagram'] : false,
    'gcaInstagramUsername' => $settings['gcaInstagramUsername'],
  );

  $telegramSettings = (object) array(
    'telegram' => (!empty($settings['telegram']) )? $settings['telegram'] : false,
    'gcaTelegramUsername' => $settings['gcaTelegramUsername'],
  );

  $tiktokSettings = (object) array(
    'tiktok' => (!empty($settings['tiktok']) )? $settings['tiktok'] : false,
    'gcaTiktokUsername' => $settings['gcaTiktokUsername'],
  );

  $xSettings = (object) array(
    'x' => (!empty($settings['x']) )? $settings['x'] : false,
    'gcaXUsername' => $settings['gcaXUsername'],
  );

  $linkedinSettings = (object) array(
    'linkedin' => (!empty($settings['linkedin']) )? $settings['linkedin'] : false,
    'gcaLinkedinUsername' => $settings['gcaLinkedinUsername'],
  );

  $phoneSettings = (object) array(
    'phone' => (!empty($settings['phone']) )? $settings['phone'] : false,
    'gcaPhoneNumber' => $settings['gcaPhoneNumber'],
  );

  $customLinkSettings = (object) array(
    'customLink' => (!empty($settings['customLink']) )? $settings['customLink'] : false,
    'gcaCustomLink' => $settings['gcaCustomLink'],
  );

  
} else {

  /* Set default settings*/
  $otherSettings = (object) array(
    'enabled' => '0',
    'position' => 'right',
  );

  $whatsappSettings = (object) array(
    'whatsapp' => true,
    'mobileNumber' => '',
    'titleMessage' => "👋 Chat with us on WhatsApp!",
    'welcomeMessage' => "Hey there!🙌\n\nGet in touch with me by typing a message here. It will go straight to my phone! 🔥\n\n~Your Name",
  );
  
  $facebookSettings = (object) array(
    'facebook' => false,
    'facebookPageId' => '',
    'facebookMessage' => "Hey there!\n\nHow can I help you today?",
    'facebookReplyTime' => "a day",
  );
  
  $emailSettings = (object) array(
    'email' => false,
    'gcaEmailAddress' => '',
    'gcaEmailSubject' => '',
  );

  $instagramSettings = (object) array(
    'instagram' => false,
    'gcaInstagramUsername' => '',
  );

  $telegramSettings = (object) array(
    'telegram' => false,
    'gcaTelegramUsername' => '',
  );

  $tiktokSettings = (object) array(
    'tiktok' => false,
    'gcaTiktokUsername' => '',
  );

  $xSettings = (object) array(
    'x' => false,
    'gcaXUsername' => '',
  );

  $linkedinSettings = (object) array(
    'linkedin' => false,
    'gcaLinkedinUsername' => '',
  );

  $phoneSettings = (object) array(
    'phone' => false,
    'gcaPhoneNumber' => '',
  );

  $customLinkSettings = (object) array(
    'customLink' => false,
    'gcaCustomLink' => '',
  );

}

$icon = GCAP_PLUGIN_DIR_URL . 'assets/img/whatsapp-icon-30x30.png';

?>

<div class="gcap-admin-page-wrapper">

  <div class="gcap-admin-page-header">
    <div>
      <h1 class="gcap-admin-page-heading"><img class='gcap-admin-icon' src='<?php echo esc_attr($icon); ?>'/>Settings</h1>
      <span id="gcap-including-pro-note" style="display: none;">Including Pro</span>
    </div>
    <button class="gcap-button" id="gcap-show-demo-button">Demo</button>
  </div>

  <div class="gcap-admin-page-body">

    <div id="gcap-settings-saved-note" style="display: none;">
      Settings saved successfully
    </div>

    <!-- Status -->
    <div class="row gcap-settings-row">
      <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
        <label for="gcapEnabled">Status</label>
      </div>
      <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
        <select name='gcapEnabled' id='gcapEnabled'>
          <option value='1' <?php if($otherSettings->enabled == '1') { echo esc_attr('selected'); } else { echo esc_attr(''); } ?>>Enabled</option>
          <option value='0' <?php if($otherSettings->enabled == '0') { echo esc_attr('selected'); } else { echo esc_attr(''); } ?>>Disabled</option>
        </select>
      </div>
    </div>

    <!-- Position -->
    <div class="row gcap-settings-row">
      <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
        <label for="position1">Position on page</label>
      </div>
      <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
        <ul>
          <li>
            <label>
              <input class="" id="position1" type="radio" name="positionRadio" value="left" <?php if($otherSettings->position == 'left'){ echo esc_attr("checked=checked"); } else { echo esc_attr(''); }?>>
              Left
            </label>
          </li>
          <li>
            <label>
              <input class="" id="position1" type="radio" name="positionRadio" value="right" <?php if($otherSettings->position == 'right'){ echo esc_attr("checked=checked"); } else { echo esc_attr(''); }?>>
              Right
            </label>
          </li>
        </ul>
      </div>
    </div>

    <!-- Platform -->
    <div class="row gcap-settings-row">
      <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
        <label for="gcapEnabled">Select a Platform</label>
      </div>
      <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
        <ul class="platforms-list" id="platforms">
          <li>
            <button class="whatsapp-btn platform-btn">
              <input type="checkbox" value="whatsapp" id="whatsappMainBtn" name="displayplatformRadio1" class="whatsappMainBtn platform-option" title="WhatsApp" <?php if($whatsappSettings->whatsapp == 'whatsapp'){ echo esc_attr('checked="checked"'); } else { echo esc_attr(''); } ?>>
            </button>
          </li>
          <li>
            <button class="facebook-btn platform-btn">
              <input type="checkbox" value="facebook" id="facebookMainBtn" name="displayplatformRadio2" class="facebookMainBtn platform-option" title="Facebook Messenger" <?php if($facebookSettings->facebook == 'facebook'){ echo esc_attr('checked="checked"'); } else { echo esc_attr(''); } ?>>
            </button>
          </li>
          <li>
            <button class="email-btn platform-btn">
              <input type="checkbox" value="email" id="emailMainBtn" name="displayplatformRadio3" class="emailMainBtn platform-option" title="Email" <?php if($emailSettings->email == 'email'){ echo esc_attr('checked="checked"'); } else { echo esc_attr(''); } ?>>
            </button>
          </li>
          <li>
            <button class="instagram-btn platform-btn">
              <input type="checkbox" value="instagram" id="instagramMainBtn" name="displayplatformRadio4" class="instagramMainBtn platform-option" title="Instagram" <?php if($instagramSettings->instagram == 'instagram'){ echo esc_attr('checked="checked"'); } else { echo esc_attr(''); } ?>>
            </button>
          </li>
          <li>
            <button class="telegram-btn platform-btn">
              <input type="checkbox" value="telegram" id="telegramMainBtn" name="displayplatformRadio5" class="telegramMainBtn platform-option" title="Telegram" <?php if($telegramSettings->telegram == 'telegram'){ echo esc_attr('checked="checked"'); } else { echo esc_attr(''); } ?>>
            </button>
          </li>

          <li>
            <button class="x-btn platform-btn">
              <input type="checkbox" value="x" id="xMainBtn" name="displayplatformRadio8" class="xMainBtn platform-option" title="X" <?php if($xSettings->x == 'x'){ echo esc_attr('checked="checked"'); } else { echo esc_attr(''); } ?>>
            </button>
          </li>
          <li>
            <button class="tiktok-btn platform-btn">
              <input type="checkbox" value="tiktok" id="tiktokMainBtn" name="displayplatformRadio9" class="tiktokMainBtn platform-option" title="TikTok" <?php if($tiktokSettings->tiktok == 'tiktok'){ echo esc_attr('checked="checked"'); } else { echo esc_attr(''); } ?>>
            </button>
          </li>
          <li>
            <button class="linkedin-btn platform-btn">
              <input type="checkbox" value="linkedin" id="linkedinMainBtn" name="displayplatformRadio7" class="linkedinMainBtn platform-option" title="LinkedIn" <?php if($linkedinSettings->linkedin == 'linkedin'){ echo esc_attr('checked="checked"'); } else { echo esc_attr(''); } ?>>
            </button>
          </li>
          <li>
            <button class="phone-btn platform-btn">
              <input type="checkbox" value="phone" id="phoneMainBtn" name="displayplatformRadio6" class="phoneMainBtn platform-option" title="Phone" <?php if($phoneSettings->phone == 'phone'){ echo esc_attr('checked="checked"'); } else { echo esc_attr(''); } ?>>
            </button>
          </li>
          <li>
            <button class="customLink-btn platform-btn">
              <input type="checkbox" value="customLink" id="customLinkMainBtn" name="displayplatformRadio6" class="customLinkMainBtn platform-option" title="Custom Link" <?php if($customLinkSettings->customLink == 'customLink'){ echo esc_attr('checked="checked"'); } else { echo esc_attr(''); } ?>>
            </button>
          </li>
          
        </ul>
      </div>
    </div>

    <div class="gcap-settings-container" id="gcap-whatsapp-settings-container" data-type="whatsapp" style="display: none;">
      <div class="gcap-settings-container-header">
        <div>
          <div class="gcap-settings-container-icon"></div>
          <h2 class="gcap-settings-container-heading">WhatsApp</h2>
        </div>
        <div>
          <button class="gcap-button gcap-addContact" data-type="whatsapp">+ Add</button>
          <span class="gcap-contact-identifier" data-identifier="1"><div class="gcap-contact-icon"></div>Contact <span class="gcap-contact-number">1</span></span>
        </div>
      </div>
      <div class="row gcap-settings-row gcap-contactRequiredField">
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
          <label for="mobileNumber">Mobile Number</label>
        </div>
        <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
          <input type="text" id="mobileNumber" class="gcapInput" placeholder="Enter your WhatsApp mobile number" value="<?php echo esc_attr($whatsappSettings->mobileNumber); ?>">
          <p class="gcapHelperNote">Example: +27 123456789 (Include your country code)</p>
        </div>
      </div>
      <div class="row gcap-settings-row">
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
          <label for="titleMessage">Chat Box Title</label>
        </div>
        <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
          <input type="text" id="titleMessage" class="gcapInput" placeholder="Enter the title of the chat box" value="<?php echo esc_attr($whatsappSettings->titleMessage); ?>">
        </div>
      </div>
      <div class="row gcap-settings-row">
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
          <label for="welcomeMessage">Chat Message</label>
        </div>
        <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
          <textarea id="welcomeMessage" class="gcapTextarea" placeholder="Enter the chat message"><?php echo esc_textarea($whatsappSettings->welcomeMessage); ?></textarea>
        </div>
      </div>
    </div>

    <div class="gcap-settings-container" id="gcap-facebook-settings-container" data-type="facebook" style="display: none;">
      <div class="gcap-settings-container-header">
        <div>
          <div class="gcap-settings-container-icon"></div>
          <h2 class="gcap-settings-container-heading">Facebook</h2>
        </div>
        <div>
          <button class="gcap-button gcap-addContact" data-type="facebook">+ Add</button>
          <span class="gcap-contact-identifier" data-identifier="1"><div class="gcap-contact-icon"></div>Contact <span class="gcap-contact-number">1</span></span>
        </div>
      </div>
      <div class="row gcap-settings-row gcap-contactRequiredField">
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
          <label for="facebookPageId">Facebook Page ID</label>
        </div>
        <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
          <input type="text" id="facebookPageId" class="gcapInput" placeholder="Enter your Facebook Page ID" value="<?php echo esc_attr($facebookSettings->facebookPageId); ?>">
        </div>
      </div>
      <div class="row gcap-settings-row">
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
          <label for="facebookReplyTime">Estimated Reply Time</label>
        </div>
        <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
          <input type="text" id="facebookReplyTime" class="gcapInput" placeholder="Enter the estimated reply time" value="<?php echo esc_attr($facebookSettings->facebookReplyTime); ?>">
        </div>
      </div>
      <div class="row gcap-settings-row">
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
          <label for="facebookMessage">FacebookChat Message</label>
        </div>
        <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
          <textarea id="facebookMessage" class="gcapTextarea" placeholder="Enter the chat message"><?php echo esc_textarea($facebookSettings->facebookMessage); ?></textarea>
        </div>
      </div>
    </div>

    <div class="gcap-settings-container" id="gcap-email-settings-container" data-type="email" style="display: none;">
      <div class="gcap-settings-container-header">
        <div>
          <div class="gcap-settings-container-icon"></div>
          <h2 class="gcap-settings-container-heading">Email</h2>
        </div>
        <div>
          <button class="gcap-button gcap-addContact" data-type="email">+ Add</button>
          <span class="gcap-contact-identifier" data-identifier="1"><div class="gcap-contact-icon"></div>Email <span class="gcap-contact-number">1</span></span>
        </div>
      </div>
      <div class="gcap-platform-description">
        <p>Note: The Email button will redirect the user to a new window where they can email you.</p>
      </div>
      <div class="row gcap-settings-row gcap-contactRequiredField">
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
          <label for="gcaEmailAddress">Email Address</label>
        </div>
        <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
          <input type="email" id="gcaEmailAddress" class="gcapInput" placeholder="Enter your Email address" value="<?php echo esc_attr($emailSettings->gcaEmailAddress); ?>">
        </div>
      </div>
      <div class="row gcap-settings-row">
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
          <label for="gcaEmailSubject">Email Subject</label>
        </div>
        <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
          <input type="text" id="gcaEmailSubject" class="gcapInput" placeholder="Enter the email subject" value="<?php echo esc_attr($emailSettings->gcaEmailSubject); ?>">
        </div>
      </div>
    </div>

    <div class="gcap-settings-container" id="gcap-instagram-settings-container" data-type="instagram" style="display: none;">
      <div class="gcap-settings-container-header">
        <div>
          <div class="gcap-settings-container-icon"></div>
          <h2 class="gcap-settings-container-heading">Instagram</h2>
        </div>
        <div>
          <button class="gcap-button gcap-addContact" data-type="instagram">+ Add</button>
          <span class="gcap-contact-identifier" data-identifier="1"><div class="gcap-contact-icon"></div>Contact <span class="gcap-contact-number">1</span></span>
        </div>
      </div>
      <div class="gcap-platform-description">
        <p>Note: The Instagram button will redirect the user to your Instagram.</p>
      </div>
      <div class="row gcap-settings-row gcap-contactRequiredField">
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
          <label for="gcaInstagramUsername">Instagram Username</label>
        </div>
        <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
          <input type="text" id="gcaInstagramUsername" class="gcapInput" placeholder="Enter your Instagram username" value="<?php echo esc_attr($instagramSettings->gcaInstagramUsername); ?>">
        </div>
      </div>
    </div>

    <div class="gcap-settings-container" id="gcap-telegram-settings-container" data-type="telegram" style="display: none;">
      <div class="gcap-settings-container-header">
        <div>
          <div class="gcap-settings-container-icon"></div>
          <h2 class="gcap-settings-container-heading">Telegram</h2>
        </div>
        <div>
          <button class="gcap-button gcap-addContact" data-type="telegram">+ Add</button>
          <span class="gcap-contact-identifier" data-identifier="1"><div class="gcap-contact-icon"></div>Contact <span class="gcap-contact-number">1</span></span>
        </div>
      </div>
      <div class="gcap-platform-description">
        <p>Note: The Telegram button will redirect the user to a new window where they can get in touch with you on Telegram.</p>
      </div>
      <div class="row gcap-settings-row gcap-contactRequiredField">
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
          <label for="gcaTelegramUsername">Telegram Username</label>
        </div>
        <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
          <input type="text" id="gcaTelegramUsername" class="gcapInput" placeholder="Enter your Telegram username" value="<?php echo esc_attr($telegramSettings->gcaTelegramUsername); ?>">
        </div>
      </div>
    </div>

    <div class="gcap-settings-container" id="gcap-tiktok-settings-container" data-type="tiktok" style="display: none;">
      <div class="gcap-settings-container-header">
        <div>
          <div class="gcap-settings-container-icon"></div>
          <h2 class="gcap-settings-container-heading">TikTok</h2>
        </div>
        <div>
          <button class="gcap-button gcap-addContact" data-type="tiktok">+ Add</button>
          <span class="gcap-contact-identifier" data-identifier="1"><div class="gcap-contact-icon"></div>Contact <span class="gcap-contact-number">1</span></span>
        </div>
      </div>
      <div class="gcap-platform-description">
        <p>Note: The TikTok button will redirect the user to your TikTok profile.</p>
      </div>
      <div class="row gcap-settings-row gcap-contactRequiredField">
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
          <label for="gcaTiktokUsername">TikTok Username</label>
        </div>
        <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
          <input type="text" id="gcaTiktokUsername" class="gcapInput" placeholder="Enter your TikTok username" value="<?php echo esc_attr($tiktokSettings->gcaTiktokUsername); ?>">
        </div>
      </div>
    </div>

    <div class="gcap-settings-container" id="gcap-x-settings-container" data-type="x" style="display: none;">
      <div class="gcap-settings-container-header">
        <div>
          <div class="gcap-settings-container-icon"></div>
          <h2 class="gcap-settings-container-heading">X</h2>
        </div>
        <div>
          <button class="gcap-button gcap-addContact" data-type="x">+ Add</button>
          <span class="gcap-contact-identifier" data-identifier="1"><div class="gcap-contact-icon"></div>Contact <span class="gcap-contact-number">1</span></span>
        </div>
      </div>
      <div class="row gcap-settings-row gcap-contactRequiredField">
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
          <label for="gcaXUsername">X Username</label>
        </div>
        <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
          <input type="text" id="gcaXUsername" class="gcapInput" placeholder="Enter your X username" value="<?php echo esc_attr($xSettings->gcaXUsername); ?>">
        </div>
      </div>
    </div>

    <div class="gcap-settings-container" id="gcap-linkedin-settings-container" data-type="linkedin" style="display: none;">
      <div class="gcap-settings-container-header">
        <div>
          <div class="gcap-settings-container-icon"></div>
          <h2 class="gcap-settings-container-heading">LinkedIn</h2>
        </div>
        <div>
          <button class="gcap-button gcap-addContact" data-type="linkedin">+ Add</button>
          <span class="gcap-contact-identifier" data-identifier="1"><div class="gcap-contact-icon"></div>Contact <span class="gcap-contact-number">1</span></span>
        </div>
      </div>
      <div class="gcap-platform-description">
        <p>Note: The LinkedIn button will redirect the user to your LinkedIn Profile.</p>
      </div>
      <div class="row gcap-settings-row gcap-contactRequiredField">
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
          <label for="gcaLinkedinUsername">LinkedIn Username</label>
        </div>
        <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
          <input type="text" id="gcaLinkedinUsername" class="gcapInput" placeholder="Enter your LinkedIn username" value="<?php echo esc_attr($linkedinSettings->gcaLinkedinUsername); ?>">
        </div>
      </div>
    </div>

    <div class="gcap-settings-container" id="gcap-phone-settings-container" data-type="phone" style="display: none;">
      <div class="gcap-settings-container-header">
        <div>
          <div class="gcap-settings-container-icon"></div>
          <h2 class="gcap-settings-container-heading">Phone</h2>
        </div>
        <div>
          <button class="gcap-button gcap-addContact" data-type="phone">+ Add</button>
          <span class="gcap-contact-identifier" data-identifier="1"><div class="gcap-contact-icon"></div>Contact <span class="gcap-contact-number">1</span></span>
        </div>
      </div>
      <div class="gcap-platform-description">
        <p>Note: The Phone button will allow the user to directly get in touch with via a call.</p>
      </div>
      <div class="row gcap-settings-row gcap-contactRequiredField">
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
          <label for="gcaPhoneNumber">Phone Number</label>
        </div>
        <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
          <input type="text" id="gcaPhoneNumber" class="gcapInput" placeholder="Enter your Phone number" value="<?php echo esc_attr($phoneSettings->gcaPhoneNumber); ?>">
        </div>
      </div>
    </div>

    <div class="gcap-settings-container" id="gcap-customLink-settings-container" data-type="customLink" style="display: none;">
      <div class="gcap-settings-container-header">
        <div>
          <div class="gcap-settings-container-icon"></div>
          <h2 class="gcap-settings-container-heading">Custom Link</h2>
        </div>
        <div>
          <button class="gcap-button gcap-addContact" data-type="customLink">+ Add</button>
          <span class="gcap-contact-identifier" data-identifier="1"><div class="gcap-contact-icon"></div>Link <span class="gcap-contact-number">1</span></span>
        </div>
      </div>
      <div class="gcap-platform-description">
        <p>Note: The Link button will redirect the user to the link entered below.</p>
      </div>
      <div class="row gcap-settings-row gcap-contactRequiredField">
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
          <label for="gcaCustomLink">Custom Link</label>
        </div>
        <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
          <input type="text" id="gcaCustomLink" class="gcapInput" placeholder="Enter your Link" value="<?php echo esc_attr($customLinkSettings->gcaCustomLink); ?>">
          <p class="gcapHelperNote">Example: https://example.com (Include <em>'https'</em> or <em>'http'</em>)</p>
        </div>
      </div>
    </div>


    <div style="margin-bottom: 50px; text-align: right; display: none;" id="gcapSaveSettingsContainer">
      <button class="gcap-button" id="gcapSaveSettings">Save Settings</button>
    </div>

    <?php
      do_action('gcap_pro_features_show_button', $settings);
      do_action('gcap_pro_features', $settings);
    ?>

    <?php
      do_action('gcap_demo', $settings);
    ?>

  </div>


</div>