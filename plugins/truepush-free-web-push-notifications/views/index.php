<?php
defined('ABSPATH') or  die('Exit');
if (!Truepush_Initialize::is_authorised()) {
     die('Insufficient permissions to access config page.');
}
$tpSettings = Truepush_Initialize::getTpSettings();
?>
<div class="truepush_documantion">
        <header class="tpHeaderContent_section ">
            <nav class="nav">
                <a class="">
                    <img src="<?php echo esc_url(TRUEPUSH_URL."views/images/tp_logo_w.png") ?>" alt="logo">
                </a>
            </nav>
        </header>
        <section id="tp-plugin" >
      <div class="tabs-container" style="max-width:1024px;">
        <nav class="tabs">
          <button class="tabs-item active" onclick="changeTab(event,'London','Setup','Configuration')" id="Setup">Setup</button>
          <button class="tabs-item" onclick="changeTab(event,'Paris','Configuration','Setup')" id="Configuration">Configuration</button>
        </nav>
        <div class="tabs-body">

          <div class="city" id="London">
            <header class="simple-steps">
              <p class="op-30">Simple steps to</p>
              <p>Add web push to your wordpress blog</p>
            </header>
    
            <main class="steps">
              <section class="step-box" data-number="1">
                <h3>Signup</h3>
                <p>
                  <a href="https://app.truepush.com/home/register" target="_blank" class="link"> <strong>Signup</strong></a> for a Truepush account or <a  href="https://app.truepush.com/home/login" target="_blank" class="link"> <strong>Login</strong></a> to your existing account.
                </p>
              </section>
  
              <section class="step-box" data-number="2">
                <h3>Create Project</h3>
                <p>
                  Go to the settings page in your project or create a new project if you are a new user..
                </p>
              </section>
  
              <section class="step-box" data-number="3">
                <h3>Configure</h3>
                <p>
                  You will now find the APP ID and the API Key. This needs to be entered in the account settings under the configuration tab.
                </p>
              </section>
  
              <section class="step-box" data-number="4">
                <h3>Tutorial</h3>
                <p>
                  For a step by step tutorial - <a href="https://www.truepush.com/blog/?page_id=6316&preview=true" target="_blank" class="link"> <strong>Click here</strong></a>
                </p>
              </section>
            </main>
    
            <section class="note">
              <h6>Note:</h6>
              <p>
                If you are newly creating your project/account - You can select your
                opt-in type and settings after you create Truepush account in the
                <a href="https://app.truepush.com/" target="_blank" class="link">app.truepush.com</a>
              </p>
            </section>
    
            <h3 class="op-60">Troubleshooting</h3>
            <p>
              If you face any issues or have questions, write to us at <a href="" class="link">help@truepush.com</a>
               or chat with us..
            </p>
          </div>


          <div id="Paris" class="city" style="display:none">
          <form class="ui form" role="configuration" action="#" method="POST">
                    <?php
                    wp_nonce_field(Truepush_Install::$wpConfigNonceAction, Truepush_Install::$wpConfigNonceKey, true);
                    ?>
            <header class="simple-steps">
              <p>Account settings</p>
            </header>

            <main>

              <section class="flex-between">
                <div class="grow-1">
                  <label for="" class="tp-label">App ID</label>
                  <div>
                    <input type="text" class="tp-input" name="platform_id" placeholder="xxxxxxxxxxxxxxxxxxxxxxxx" value="<?php echo esc_attr($tpSettings['platform_id']); ?> ">
                  </div>
                  <p style="margin: 0"> <span class="tp-ip-helper">Copy and paste the APP ID from the Settings page at <a href="https://app.truepush.com/" class="link">app.truepush.com</a>  </p>
                </div>
                <div class="grow-1">
                  <label for="" class="tp-label">API Token</label>
                  <div>
                    <input type="text" class="tp-input" name="truepush_api_key" placeholder="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" value="<?php echo esc_attr(Truepush_Initialize::maskedApiKey($tpSettings['truepush_api_key'])); ?>">
                  </div>
                  <span class="tp-ip-helper">Copy and paste the API token from the Settings page at <a href="https://app.truepush.com/" class="link">app.truepush.com</a> </span>
                </div>
              </section>

              <section style="margin-top: 50px;">
                <h3>Sent notification</h3>
                <div class="form-row">
                  <span>
                    <input class="tgl tgl-light" id="checkbox2" name="imageFromPost" type="checkbox"  <?php if ($tpSettings['imageFromPost']) { echo "checked"; } ?>>
                    <label class="tgl-btn" for="checkbox2"></label>
                  </span>
                  <label>Use the post's featured image for Chrome's large notification image</label>
                  <span aria-label="Chrome’s Notification icon (large icon) is set to the post’s feature image by default." data-microtip-position="bottom-left" data-microtip-size="large" role="tooltip"><img src="<?php echo esc_url(TRUEPUSH_URL."views/images/dk-ol-info.svg") ?>" alt="" class="v-bottom"></span>
                </div>
              </section>

              <section style="margin-top: 50px;">
                <h3>Welcome notification</h3>

                <section class="flex-between">
                  <div class="grow-1">
                    <div class="form-row">
                      <label for="" class="tp-label">Title</label>
                      <div>
                        <input type="text" class="tp-input"  placeholder="<?php echo esc_attr($tpSettings['welcomeNotificationTitle']); ?>" maxlength="60" name="welcomeNotificationTitle" value="<?php echo esc_attr($tpSettings['welcomeNotificationTitle']); ?>">
                      </div>                  
                    </div>
    
                    <div class="form-row">
                      <label for="" class="tp-label">Message</label>
                      <div>
                        <textarea cols="30" rows="10" class="tp-input" placeholder="<?php echo esc_attr($tpSettings['welcomeNotificationMessage']); ?>" name="welcomeNotificationMessage" maxlength="140" value="<?php echo esc_attr($tpSettings['welcomeNotificationMessage']); ?>"><?php echo esc_attr($tpSettings['welcomeNotificationMessage']); ?></textarea>
                      </div>                  
                    </div>
    
                    <div class="form-row">
                      <label for="" class="tp-label">Url</label>
                      <div>
                        <input type="text" class="tp-input" placeholder="If you leave this blank, we will take your site URL by default" name="welcomeNotificationUrl" value="<?php echo esc_attr($tpSettings['welcomeNotificationUrl']); ?>">
                      </div>                  
                    </div>

                    <div class="form-row">
                      <label for="">
                        <input type="checkbox" id="checkbox8" name="welcomeNotificationUserInteraction" style="width: 18px; height: 18px" <?php if ($tpSettings['welcomeNotificationUserInteraction']) { echo "checked"; } ?>>
                        <span>User interaction reqired for welcome notifications.</span>                        
                        <div class="tp-ip-helper">
                          If activated, the user will see the notification until he either closes, or clicks on the notification.
                        </div>
                      </label>
                    </div>
                  </div>
  
                  <div class="grow-0">
                    <img src="<?php echo esc_url(TRUEPUSH_URL."views/images/notification2.jpg") ?>" alt="" class="" style="margin-top: 30px; margin-bottom: 10px; ">
                    <div class="flex align-items-center">
                      <div class="mr-10">
                        <span>
                          <input class="tgl tgl-light" id="checkbox3" name="welcomeNotification" type="checkbox"  <?php if ($tpSettings['welcomeNotification']) { echo "checked"; } ?>>
                          <label class="tgl-btn" for="checkbox3"></label>
                        </span>
                      </div>
                      <label class="grow-1" style="font-size: 13px;">Send new users a welcome push notification after subscribing</label>
                      <span class="grow-0" aria-label="Send a welcome notification to new subscribers – Customize the message or choose from existing templates." data-microtip-position="bottom-left" data-microtip-size="large" role="tooltip"><img src="<?php echo esc_url(TRUEPUSH_URL."views/images/dk-ol-info.svg") ?>" alt="" class="v-bottom"></span>
                    </div>
                  </div>
                </section>
              </section>

              <section>
                <h3>Additional Settings</h3>
                <div>
                  <input class="tgl tgl-light" id="checkbox4" name="tp_publishNotification" type="checkbox"  <?php if ($tpSettings['tp_publishNotification']) { echo "checked"; } ?>>
                    <label class="tgl-btn" for="checkbox4"></label>
                  </span>
                  <label>When I create a post from the WordPress Editor, send a push notification to my subscribers.</label>
                  <span aria-label="You can disable this in the WordPress Editor too for individual posts." data-microtip-position="bottom-left" data-microtip-size="large" role="tooltip">
                    <img src="<?php echo esc_url(TRUEPUSH_URL."views/images/dk-ol-info.svg") ?>" class="v-bottom" alt="">
                  </span>
                </div>
              </section>

              <button type="submit" class="is-save mt-30" style="cursor:pointer;">Save</button>
            </main>
            </form>
          </div>

        </div>
      </div>
    </section>
    </div>

