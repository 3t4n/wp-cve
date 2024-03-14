<?php
/**
 * Pro features HTML
*/

?>

<div id='gcapProFeatures' class="gcap-settings-container" style='display:none;'>

  <div class="row gcap-settings-row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
      <div style="display: flex; flex-direction: row; align-items: center">
        <h2 class="gcap-settings-container-heading" style="margin-right: 10px;">Pro features</h2>
        <a href='https://getchat.app/wordpress/?utm_campaign=upgradebtn&utm_source=gcaplugin&utm_medium=click' target="_BLANK" title='Upgrade Now' class='gcap-button gcap-button-upgrade'><strong>Upgrade Now</strong> - ($3.99/mo)</a>
      </div>
    </div>
  </div> 

  <div class="row gcap-settings-row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <p class="gcapMultipleContactsUpsell">Adding <strong>Multiple contacts</strong> for a platform is a Pro feature and will only be available with the Pro version.</p>
    </div>
  </div>

  <div class="row gcap-settings-row">
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
      <label for="encryptedRadio">Encrypt Mobile Number</label>
    </div>
    <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
      <input class="profeature" type="checkbox" id="encrypted" name="encryptedRadio" value="encrypted" disabled='disabled' readonly='readonly'> Ensure mobile number is not indexed by Google
    </div>
  </div>

  <div class="row gcap-settings-row">
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
      <label for="gcaCustomIcon">Custom Icon</label>
    </div>
    <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
      <input id="gcaCustomIcon" type="text" name="gcaCustomIcon" class="gcapInput" value="" disabled='disabled' readonly='readonly'>
      <button class="gcap-button" id="gcap_media_manager" disabled="disabled" readonly="disabled"><?php esc_attr_e( 'Select', 'getchatap' ); ?></button>
    </div>
  </div>

  <div class="row gcap-settings-row">
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
      <label for="hoverRadio">Enable Hover Message</label>
    </div>
    <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
      <ul>
        <li>
          <label>
            <input class="profeature" id="hover1" type="radio" name="hoverRadio" value="no" disabled="disabled" readonly="readonly" checked="">
            No
          </label>
        </li>
        <li>
          <label>
            <input class="profeature" id="hover2" type="radio" name="hoverRadio" value="yes" disabled="disabled" readonly="readonly">
            Yes
          </label>
        </li>
      </ul>
    </div>
  </div>

  <div class="row gcap-settings-row">
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
      <label for="hoverDuration">Hover Message Delay</label>
    </div>
    <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
      <input id="hoverd" class="gcapInput profeature" type="number" name="hoverDuration" value="1" disabled="disabled" readonly="readonly" checked=""> second(s)
    </div>
  </div>

  <div class="row gcap-settings-row">
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
      <label for="hoverMessage">Hover Message</label>
    </div>
    <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
      <input type="text" id="hoverMessage" class="gcapInput profeature" name="hoverMessage" placeholder="Enter the hover message" value="👋 Click here to chat with us" disabled="disabled" readonly="readonly">
    </div>
  </div>

  <div class="row gcap-settings-row">
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
      <label for="displayRadio">Hover Message Platform Display</label>
    </div>
    <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
      <ul>
        <li>
          <label>
            <input class="profeature" id="display1" type="radio" name="displayRadio" value="both" disabled="disabled" readonly="readonly" checked="">
            Both
          </label>
        </li>
        <li>
          <label>
            <input class="profeature" id="display2" type="radio" name="displayRadio" value="desktop" disabled="disabled" readonly="readonly">
            Desktop
          </label>
        </li>
        <li>
          <label>
            <input class="profeature" id="display3" type="radio" name="displayRadio" value="mobile" disabled="disabled" readonly="readonly">
            Mobile
          </label>
        </li>
      </ul>
    </div>
  </div>

  <div class="row gcap-settings-row">
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-5 col-xs-12">
      <label for="displayBrandingRadio">Disable Branding</label>
    </div>
    <div class="col-xl-10 col-lg-8 col-md-8 col-sm-7 col-xs-12">
    <input class="profeature" type="checkbox" id="branding" name="displayBrandingRadio" value="branding" disabled="disabled" readonly="readonly"> Remove 'GetChat.app' link
    </div>
  </div>

  <div id="gcapDemoProHover">
    <div class="gcapMainHover">
      <div class="gcapMainHoverInner">
        <div class="gcapMainHoverInnerMessage">👋 Click here to chat with us</div>
      </div>
    </div>
    <div id="gcapMultiDemoButton" class="gcapDemoProButton gcapButtonOnly"></div>
  </div>    

  <script>
    const gcapIsPro = false;
  </script>

</div>