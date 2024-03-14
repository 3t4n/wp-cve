<?php
/**
 * Demo HTML
*/

?>

<div id='gcapDemo' style='display:none;'>
  <div class="gcapCloseDemo">x</div>
  <div id="gcapWhatsappDemo" class="gcapDemoCard" data-type="whatsapp">
    <div class="gcapMainCardInner">
      <div class="gcapMainCardInnerTop">
        <div class="gcapMainCardInnerTopContent">👋 Chat with us on WhatsApp!</div>
        <div id="gcapMainCardCloseButton" class="gcapMainCardCloseButton"></div>
      </div>
      <div class="gcapMainCardInnerBody">
        <div class="gcapMainCardInnerBodyMessage">Hey there!🙌<br><br>Get in touch with me by typing a message here. It will go straight to my phone! 🔥<br /><br>~Your Name</div>
      </div>
      <div class="gcapMainCardInnerChatBox">
        <textarea class="gcapMainCardInnerChatBoxTextArea"></textarea>
        <span class="gcapMainCardInnerChatBoxSend"></span>
      </div>
    </div>
  </div>

  <div id="gcapFacebookDemo" class="gcapDemoCard" data-type="facebook" style="display: none;">
    <div class="gcapMainCardInner">
      <div class="gcapMainCardInnerTop">
        <div class="gcapMainCardInnerTopContent">Get in touch on Messenger!</div>
        <div class="gcapMainCardInnerTopContentBottom">
          Typically replies within <span class="gcapMainCardInnerTopContentBottomReplyTime">a day</span>
        </div>
        <div id="gcapMainCardCloseButton" class="gcapMainCardCloseButton"></div>
      </div>

      <div class="gcapMainCardInnerBody">
        <div class="gcapMainCardInnerBodyMessage">Hi there!<br><br>Start chatting with us now!</div>
      </div>
      
      <div class="gcapMainCardBottom">
        <div class="gcapMainCardBottomChatButton">Start Chat</div>
      </div>
    </div>
  </div>

  <div id="gcapDemoButtonContainer">
    <div id="gcapDemoButtonContainerInner">
      <div id="gcapWhatsappDemoButton" class="gcapDemoButton" data-type="whatsapp"></div>
      <div id="gcapFacebookDemoButton" class="gcapDemoButton" data-type="facebook" style="display: none;"></div>
      <div id="gcapEmailDemoButton" class="gcapDemoButton gcapButtonOnly" data-type="email" style="display: none;"></div>
      <div id="gcapInstagramDemoButton" class="gcapDemoButton gcapButtonOnly" data-type="instagram" style="display: none;"></div>
      <div id="gcapTelegramDemoButton" class="gcapDemoButton gcapButtonOnly" data-type="telegram" style="display: none;"></div>
      <div id="gcapXDemoButton" class="gcapDemoButton gcapButtonOnly" data-type="x" style="display: none;"></div>
      <div id="gcapTiktokDemoButton" class="gcapDemoButton gcapButtonOnly" data-type="tiktok" style="display: none;"></div>
      <div id="gcapLinkedinDemoButton" class="gcapDemoButton gcapButtonOnly" data-type="linkedin" style="display: none;"></div>
      <div id="gcapPhoneDemoButton" class="gcapDemoButton gcapButtonOnly" data-type="phone" style="display: none;"></div>
      <div id="gcapCustomLinkDemoButton" class="gcapDemoButton gcapButtonOnly" data-type="customLink" style="display: none;"></div>
    </div>
  </div>
</div>