<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       www.audioeye.com
 * @since      1.0.0
 *
 * @package    Audioeye
 * @subpackage Audioeye/admin/partials
 */

  $_nonce = wp_create_nonce( 'my_post_form_nonce' );
  $options = get_option('audioeye_config', array());
  $site_hash = $options['site_hash'];
?>

<section class="ae-step-one <?php if ($site_hash) { echo esc_attr('hidden'); } ?>">
  <div class="ae-Card">
    <div class="ae-CardHeader">
      <div class="ae-logo">
        <svg width="86.6" height="20" viewBox="0 0 122 29" fill="none" xmlns="http://www.w3.org/2000/svg">
          <g clip-path="url(#clip0)">
          <path d="M41.9328 20.1252C43.8214 20.1252 45.2268 19.2029 45.7539 17.6657C45.7758 18.4782 45.8417 19.2688 45.9515 19.8617H47.5546C47.313 18.9613 47.2032 17.7316 47.2032 16.0187V13.1639C47.2032 9.97968 45.9295 8.61816 42.9869 8.61816C40.4615 8.61816 38.7486 10.0456 38.6169 12.3074H40.2639C40.3517 10.7483 41.3619 9.89184 43.0089 9.89184C44.8755 9.89184 45.666 10.7922 45.666 13.0541V13.2298L43.2285 13.5152C41.4936 13.7348 40.4176 14.0423 39.627 14.5034C38.7486 15.0524 38.2875 15.9089 38.2875 16.963C38.2875 18.8515 39.7368 20.1252 41.9328 20.1252ZM42.2842 18.8515C40.8129 18.8515 39.8906 18.1049 39.8906 16.919C39.8906 15.7112 40.7031 15.0744 42.5697 14.8109L45.688 14.3717V15.0744C45.688 17.3143 44.3265 18.8515 42.2842 18.8515Z" fill="white"/>
          <path d="M54.0787 20.1263C55.7476 20.1263 57.1311 19.0503 57.6362 17.6009V19.8628H59.0856V8.88281H57.5483V14.8779C57.5483 17.0958 56.3406 18.8087 54.3422 18.8087C52.8489 18.8087 51.8388 17.8425 51.8388 15.6904V8.88281H50.3235V15.8441C50.3235 18.4574 51.3337 20.1263 54.0787 20.1263Z" fill="white"/>
          <path d="M66.3152 20.1264C68.072 20.1264 69.3896 19.1602 70.0264 17.5351V19.8629H71.4758V3.94189H69.9386V10.8812C69.2798 9.34405 68.072 8.61937 66.4908 8.61937C63.7019 8.61937 61.7914 10.991 61.7914 14.3729C61.7914 17.7986 63.5921 20.1264 66.3152 20.1264ZM66.6885 18.7649C64.6462 18.7649 63.4165 17.0959 63.4165 14.3729C63.4165 11.6279 64.6462 9.98089 66.7104 9.98089C68.7527 9.98089 69.9605 11.6498 69.9605 14.1533V14.6144C69.9605 17.052 68.7088 18.7649 66.6885 18.7649Z" fill="white"/>
          <path d="M75.1844 6.90583H76.9193V4.49023H75.1844V6.90583ZM75.2942 19.8622H76.8095V8.88223H75.2942V19.8622Z" fill="white"/>
          <path d="M84.5569 20.1252C87.5874 20.1252 89.5857 17.9512 89.5857 14.3717C89.5857 10.7922 87.5874 8.61816 84.5569 8.61816C81.5045 8.61816 79.5061 10.7922 79.5061 14.3717C79.5061 17.9512 81.5045 20.1252 84.5569 20.1252ZM84.5569 18.7856C82.4268 18.7856 81.1311 17.2265 81.1311 14.3717C81.1311 11.5169 82.4268 9.95772 84.5569 9.95772C86.6651 9.95772 87.9607 11.5169 87.9607 14.3717C87.9607 17.2265 86.6651 18.7856 84.5569 18.7856Z" fill="white"/>
          <path d="M100.757 13.7129C100.757 10.375 99.0657 8.61816 96.123 8.61816C93.0926 8.61816 91.2479 10.8361 91.2479 14.4376C91.2479 18.1268 93.0706 20.1252 96.1889 20.1252C98.4069 20.1252 100.01 18.9833 100.691 16.941L99.3951 16.414C98.9339 17.9072 97.8359 18.7637 96.2987 18.7637C94.1686 18.7637 92.8071 17.2484 92.7851 14.767H100.757V13.7129ZM99.1974 13.5152H92.7851C92.829 11.2534 94.0588 9.84792 96.1011 9.84792C98.0555 9.84792 99.1974 11.0557 99.1974 13.3176V13.5152Z" fill="white"/>
          <path d="M103.288 24.2548C105.111 24.2548 106.319 23.2666 107.307 20.5655L111.589 8.88281H109.942L107.263 16.7006C107.066 17.2935 106.912 17.7986 106.802 18.2158H106.758C106.648 17.7986 106.495 17.2935 106.275 16.7225L103.332 8.88281H101.62L106.012 19.775L105.726 20.5875C105.199 22.0808 104.518 22.8713 103.091 22.8713C102.849 22.8713 102.586 22.8494 102.322 22.8054V24.167C102.652 24.2328 102.959 24.2548 103.288 24.2548Z" fill="white"/>
          <path d="M121.838 13.7129C121.838 10.375 120.147 8.61816 117.205 8.61816C114.174 8.61816 112.329 10.8361 112.329 14.4376C112.329 18.1268 114.152 20.1252 117.27 20.1252C119.488 20.1252 121.092 18.9833 121.772 16.941L120.477 16.414C120.015 17.9072 118.917 18.7637 117.38 18.7637C115.25 18.7637 113.889 17.2484 113.867 14.767H121.838V13.7129ZM120.279 13.5152H113.867C113.911 11.2534 115.14 9.84792 117.183 9.84792C119.137 9.84792 120.279 11.0557 120.279 13.3176V13.5152Z" fill="white"/>
          <path fill-rule="evenodd" clip-rule="evenodd" d="M22.9825 3.17446C20.5542 5.16555 17.448 6.36051 14.0628 6.36051C10.6841 6.36051 7.58347 5.1702 5.15732 3.18605C7.58558 1.19495 10.6917 0 14.077 0C17.4556 0 20.5563 1.19031 22.9825 3.17446Z" fill="#00E3B9"/>
          <path fill-rule="evenodd" clip-rule="evenodd" d="M23.1054 24.8771C20.6615 26.9222 17.513 28.1532 14.0771 28.1532C10.5962 28.1532 7.41046 26.8898 4.95334 24.7966C7.39726 22.7515 10.5457 21.5205 13.9817 21.5205C17.4625 21.5205 20.6483 22.7839 23.1054 24.8771Z" fill="#00E3B9"/>
          <path fill-rule="evenodd" clip-rule="evenodd" d="M24.8913 5.06445C26.9283 7.50609 28.1539 10.6482 28.1539 14.0766C28.1539 17.546 26.8988 20.7223 24.8179 23.1761C22.7809 20.7344 21.5553 17.5923 21.5553 14.1639C21.5553 10.6945 22.8104 7.51828 24.8913 5.06445Z" fill="#00E3B9"/>
          <path fill-rule="evenodd" clip-rule="evenodd" d="M3.21688 5.11963C5.16707 7.53537 6.33478 10.6091 6.33478 13.9555C6.33478 17.3582 5.12749 20.4789 3.11789 22.9126C1.16771 20.4969 0 17.4232 0 14.0768C0 10.6741 1.20729 7.55339 3.21688 5.11963Z" fill="#00E3B9"/>
          </g>
          <defs>
          <clipPath id="clip0">
          <rect width="121.906" height="28.1538" fill="white"/>
          </clipPath>
          </defs>
        </svg>
      </div>

      <h1>Congratulations! Just 2 more steps...</h1>
      <p class="ae-subtitle">Activate AudioEye to enable automated accessibility fixes, your Usability Toolbar, and more!</p>

    </div>

    <div class="ae-CardBody">

      <h2>1. Sign up for AudioEye</h2>
      <div class="ae-indent-content">
        <a class="ae-register-button" href="https://www.audioeye.com/#get-started-free?utm_source=wordpressPlugInIntegration&utm_medium=audioeyeIntegrationReferral" target="_blank" rel="noreferrer">
          <span>Start Your Free Trial</span>
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M14.5 11C14.5 12.9526 14.5 14.0474 14.5 16H5.5V7H10.5M12 5H16.5M16.5 5V9.5M16.5 5L10 11.5" stroke="white" stroke-width="1.25"/>
          </svg>
        </a>
        <p class="ae-login-text">Already have an AudioEye account? Log in to the <a href="https://customer-portal.audioeye.com/login?utm_source=wordpressPlugInIntegration&utm_medium=audioeyeIntegrationReferral" target="_blank" rel="noreferrer">AudioEye Portal<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M16.3 12.2C16.3 14.5431 16.3 15.8569 16.3 18.2H5.5V7.4H11.5M13.3 5H18.7M18.7 5V10.4M18.7 5L10.9 12.8" stroke="#3975BB" stroke-width="1.5"/>
</svg></a> to get your Site ID.</p>
      </div>

      <div class="ae-step-one-spacer"></div>

      <h2>2. Enter your AudioEye Site ID</h2>
      <div class="ae-indent-content">
        <p>Every site in your AudioEye account has its own Site ID.<br/>Your Site ID is available under <i>Installation</i>.</p>
        <form class="ae-site-id-form" method="post" enctype="multipart/form-data">
          <input type="hidden" name="action" value="post_first">
          <input type="hidden" name="_nonce" value="<?php echo esc_attr($_nonce); ?>" />
          <div class="site-id-field-container">
            <label class="site-id-field">
              <span>Enter AudioEye Site ID</span>
              <input name="site_hash" value="<?php echo esc_attr($site_hash); ?>"/>
            </label>
            <span class="site-id-field-error">Site ID cannot be empty</span>
          </div>

          <?php
              submit_button( esc_attr__( 'Activate AudioEye', $this->plugin_name ), 'primary', 'submit-name', TRUE );
          ?>
        </form>
      </div>
    </div>
    <a class="ae-help-link" href="https://help.audioeye.com/hc/en-us?utm_source=wordpressPlugInIntegration&utm_medium=audioeyeIntegrationReferral" target="_blank" rel="noreferrer">AudioEye Help Center<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M16.3 12.2C16.3 14.5431 16.3 15.8569 16.3 18.2H5.5V7.4H11.5M13.3 5H18.7M18.7 5V10.4M18.7 5L10.9 12.8" stroke="#3975BB" stroke-width="1.5"/>
      </svg>
    </a>
  </div>
</section>

<section class="ae-step-two hidden">
  <div class="ae-Card">
    <div class="ae-CardHeader">
      <div class="ae-logo">
        <svg width="86.6" height="20" viewBox="0 0 122 29" fill="none" xmlns="http://www.w3.org/2000/svg">
          <g clip-path="url(#clip0)">
          <path d="M41.9328 20.1252C43.8214 20.1252 45.2268 19.2029 45.7539 17.6657C45.7758 18.4782 45.8417 19.2688 45.9515 19.8617H47.5546C47.313 18.9613 47.2032 17.7316 47.2032 16.0187V13.1639C47.2032 9.97968 45.9295 8.61816 42.9869 8.61816C40.4615 8.61816 38.7486 10.0456 38.6169 12.3074H40.2639C40.3517 10.7483 41.3619 9.89184 43.0089 9.89184C44.8755 9.89184 45.666 10.7922 45.666 13.0541V13.2298L43.2285 13.5152C41.4936 13.7348 40.4176 14.0423 39.627 14.5034C38.7486 15.0524 38.2875 15.9089 38.2875 16.963C38.2875 18.8515 39.7368 20.1252 41.9328 20.1252ZM42.2842 18.8515C40.8129 18.8515 39.8906 18.1049 39.8906 16.919C39.8906 15.7112 40.7031 15.0744 42.5697 14.8109L45.688 14.3717V15.0744C45.688 17.3143 44.3265 18.8515 42.2842 18.8515Z" fill="white"/>
          <path d="M54.0787 20.1263C55.7476 20.1263 57.1311 19.0503 57.6362 17.6009V19.8628H59.0856V8.88281H57.5483V14.8779C57.5483 17.0958 56.3406 18.8087 54.3422 18.8087C52.8489 18.8087 51.8388 17.8425 51.8388 15.6904V8.88281H50.3235V15.8441C50.3235 18.4574 51.3337 20.1263 54.0787 20.1263Z" fill="white"/>
          <path d="M66.3152 20.1264C68.072 20.1264 69.3896 19.1602 70.0264 17.5351V19.8629H71.4758V3.94189H69.9386V10.8812C69.2798 9.34405 68.072 8.61937 66.4908 8.61937C63.7019 8.61937 61.7914 10.991 61.7914 14.3729C61.7914 17.7986 63.5921 20.1264 66.3152 20.1264ZM66.6885 18.7649C64.6462 18.7649 63.4165 17.0959 63.4165 14.3729C63.4165 11.6279 64.6462 9.98089 66.7104 9.98089C68.7527 9.98089 69.9605 11.6498 69.9605 14.1533V14.6144C69.9605 17.052 68.7088 18.7649 66.6885 18.7649Z" fill="white"/>
          <path d="M75.1844 6.90583H76.9193V4.49023H75.1844V6.90583ZM75.2942 19.8622H76.8095V8.88223H75.2942V19.8622Z" fill="white"/>
          <path d="M84.5569 20.1252C87.5874 20.1252 89.5857 17.9512 89.5857 14.3717C89.5857 10.7922 87.5874 8.61816 84.5569 8.61816C81.5045 8.61816 79.5061 10.7922 79.5061 14.3717C79.5061 17.9512 81.5045 20.1252 84.5569 20.1252ZM84.5569 18.7856C82.4268 18.7856 81.1311 17.2265 81.1311 14.3717C81.1311 11.5169 82.4268 9.95772 84.5569 9.95772C86.6651 9.95772 87.9607 11.5169 87.9607 14.3717C87.9607 17.2265 86.6651 18.7856 84.5569 18.7856Z" fill="white"/>
          <path d="M100.757 13.7129C100.757 10.375 99.0657 8.61816 96.123 8.61816C93.0926 8.61816 91.2479 10.8361 91.2479 14.4376C91.2479 18.1268 93.0706 20.1252 96.1889 20.1252C98.4069 20.1252 100.01 18.9833 100.691 16.941L99.3951 16.414C98.9339 17.9072 97.8359 18.7637 96.2987 18.7637C94.1686 18.7637 92.8071 17.2484 92.7851 14.767H100.757V13.7129ZM99.1974 13.5152H92.7851C92.829 11.2534 94.0588 9.84792 96.1011 9.84792C98.0555 9.84792 99.1974 11.0557 99.1974 13.3176V13.5152Z" fill="white"/>
          <path d="M103.288 24.2548C105.111 24.2548 106.319 23.2666 107.307 20.5655L111.589 8.88281H109.942L107.263 16.7006C107.066 17.2935 106.912 17.7986 106.802 18.2158H106.758C106.648 17.7986 106.495 17.2935 106.275 16.7225L103.332 8.88281H101.62L106.012 19.775L105.726 20.5875C105.199 22.0808 104.518 22.8713 103.091 22.8713C102.849 22.8713 102.586 22.8494 102.322 22.8054V24.167C102.652 24.2328 102.959 24.2548 103.288 24.2548Z" fill="white"/>
          <path d="M121.838 13.7129C121.838 10.375 120.147 8.61816 117.205 8.61816C114.174 8.61816 112.329 10.8361 112.329 14.4376C112.329 18.1268 114.152 20.1252 117.27 20.1252C119.488 20.1252 121.092 18.9833 121.772 16.941L120.477 16.414C120.015 17.9072 118.917 18.7637 117.38 18.7637C115.25 18.7637 113.889 17.2484 113.867 14.767H121.838V13.7129ZM120.279 13.5152H113.867C113.911 11.2534 115.14 9.84792 117.183 9.84792C119.137 9.84792 120.279 11.0557 120.279 13.3176V13.5152Z" fill="white"/>
          <path fill-rule="evenodd" clip-rule="evenodd" d="M22.9825 3.17446C20.5542 5.16555 17.448 6.36051 14.0628 6.36051C10.6841 6.36051 7.58347 5.1702 5.15732 3.18605C7.58558 1.19495 10.6917 0 14.077 0C17.4556 0 20.5563 1.19031 22.9825 3.17446Z" fill="#00E3B9"/>
          <path fill-rule="evenodd" clip-rule="evenodd" d="M23.1054 24.8771C20.6615 26.9222 17.513 28.1532 14.0771 28.1532C10.5962 28.1532 7.41046 26.8898 4.95334 24.7966C7.39726 22.7515 10.5457 21.5205 13.9817 21.5205C17.4625 21.5205 20.6483 22.7839 23.1054 24.8771Z" fill="#00E3B9"/>
          <path fill-rule="evenodd" clip-rule="evenodd" d="M24.8913 5.06445C26.9283 7.50609 28.1539 10.6482 28.1539 14.0766C28.1539 17.546 26.8988 20.7223 24.8179 23.1761C22.7809 20.7344 21.5553 17.5923 21.5553 14.1639C21.5553 10.6945 22.8104 7.51828 24.8913 5.06445Z" fill="#00E3B9"/>
          <path fill-rule="evenodd" clip-rule="evenodd" d="M3.21688 5.11963C5.16707 7.53537 6.33478 10.6091 6.33478 13.9555C6.33478 17.3582 5.12749 20.4789 3.11789 22.9126C1.16771 20.4969 0 17.4232 0 14.0768C0 10.6741 1.20729 7.55339 3.21688 5.11963Z" fill="#00E3B9"/>
          </g>
          <defs>
          <clipPath id="clip0">
          <rect width="121.906" height="28.1538" fill="white"/>
          </clipPath>
          </defs>
        </svg>
      </div>

      <h1>Success! AudioEye has been activated :)</h1>
      <p class="ae-subtitle">It can take up to to 48 hours for data to appear in your dashboard.</p>
    </div>

    <div class="ae-CardBody">

      <h2>AudioEye is running on your site.</h2>
        <div class="ae-image-line">
          <div>
          <svg width="164" height="105" viewBox="0 0 164 105" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="-0.75" y="0.75" width="161.389" height="103.214" rx="3.90397" transform="matrix(-1 0 0 1 162.287 0)" fill="white" stroke="black" stroke-width="1.5"/>
            <path d="M1 16.8706L163.307 16.8706" stroke="black" stroke-width="0.727183"/>
            <ellipse cx="9.7264" cy="8.18984" rx="2.32698" ry="2.33998" fill="black"/>
            <ellipse cx="18.453" cy="8.18984" rx="2.32698" ry="2.33998" fill="black"/>
            <ellipse cx="27.1785" cy="8.18984" rx="2.32698" ry="2.33998" fill="black"/>
            <rect x="101" y="84.8826" width="23" height="2" rx="1" fill="black"/>
            <path d="M126.368 85.4652C126.667 85.6629 126.667 86.1023 126.368 86.2999L122.525 88.8351C122.193 89.0544 121.75 88.816 121.75 88.4177V83.3474C121.75 82.9491 122.193 82.7107 122.525 82.9301L126.368 85.4652Z" fill="black"/>
            <g clip-path="url(#clip1)">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M141.606 75C147.303 75 151.921 79.6179 151.921 85.3144C151.921 91.0108 147.303 95.6287 141.606 95.6287C135.91 95.6287 131.292 91.0108 131.292 85.3144C131.292 79.6179 135.91 75 141.606 75Z" fill="white"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M141.607 75.8169C136.361 75.8148 132.108 80.0655 132.105 85.311C132.103 90.5565 136.354 94.81 141.6 94.8121C146.845 94.8142 151.099 90.5635 151.101 85.318V85.3145C151.103 80.0711 146.853 75.8186 141.61 75.8169H141.607ZM141.607 78.1939C142.524 78.1939 143.268 78.9377 143.268 79.8554C143.268 80.773 142.524 81.5168 141.607 81.5168C140.689 81.5168 139.945 80.773 139.945 79.8554C139.945 78.9377 140.689 78.1939 141.607 78.1939ZM147.104 82.8711L143.662 83.4541C143.439 83.498 143.275 83.6904 143.268 83.9183V84.5605C143.254 85.253 143.317 85.9449 143.457 86.6234L144.58 91.4647C144.701 91.8595 144.478 92.2766 144.083 92.3967C143.978 92.4288 143.866 92.4371 143.757 92.4211C143.43 92.4078 143.153 92.1754 143.083 91.8556L141.778 87.318C141.75 87.2217 141.649 87.1658 141.553 87.1934C141.493 87.2105 141.446 87.2576 141.429 87.318L140.123 91.8556C140.053 92.1754 139.776 92.4078 139.449 92.4211C139.041 92.4815 138.662 92.1994 138.601 91.7914C138.585 91.6818 138.594 91.5705 138.626 91.4647L139.757 86.6339C139.896 85.9553 139.959 85.2635 139.945 84.571V83.9183C139.944 83.6851 139.779 83.4851 139.551 83.4401L136.109 82.8711C135.8 82.817 135.594 82.5235 135.648 82.2149C135.702 81.9064 135.996 81.7001 136.305 81.7542C136.305 81.7542 140.168 82.194 141.607 82.1975C143.045 82.201 146.909 81.7507 146.909 81.7507C147.218 81.6966 147.512 81.9039 147.567 82.2132C147.621 82.5224 147.413 82.817 147.104 82.8711Z" fill="#366EB0"/>
            </g>
            <defs>
            <clipPath id="clip1">
            <rect width="20.9429" height="20.9429" fill="white" transform="translate(131.292 75)"/>
            </clipPath>
            </defs>
            </svg>

</div>
          <p>Look for the AudioEye Toolbar button in the bottom corner of your website.</p>
        </div>

        <div class="ae-image-line ae-image-line-dashboard">
          <div>
            <svg width="163" height="105" viewBox="0 0 163 105" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="-0.75" y="0.75" width="161.389" height="103.214" rx="3.90397" transform="matrix(-1 0 0 1 161.389 0)" fill="white" stroke="black" stroke-width="1.5"/>
              <rect x="30.75" y="30.75" width="16.5" height="51.5" stroke="black" stroke-width="1.5"/>
              <rect x="58.75" y="48.75" width="16.5" height="33.5" stroke="black" stroke-width="1.5"/>
              <rect x="86.75" y="30.75" width="16.5" height="51.5" stroke="black" stroke-width="1.5"/>
              <rect x="114.75" y="48.75" width="16.5" height="33.5" stroke="black" stroke-width="1.5"/>
            </svg>
          </div>
          <p>View your Accessibility Score and other site metrics in your accessibility <a href="https://customer-portal.audioeye.com/login?utm_source=wordpressPlugInIntegration&utm_medium=audioeyeIntegrationReferral" target="_blank" rel="noreferrer">Dashboard<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M16.3 12.2C16.3 14.5431 16.3 15.8569 16.3 18.2H5.5V7.4H11.5M13.3 5H18.7M18.7 5V10.4M18.7 5L10.9 12.8" stroke="#3975BB" stroke-width="1.5"/>
</svg>
</a>.</p>
</div>


      <h2>If you need to re-enter your Site ID...</h2>
      <p class="ae-need-to-reenter-site-id">Your Site ID can be found in the <a href="https://customer-portal.audioeye.com/login?utm_source=wordpressPlugInIntegration&utm_medium=audioeyeIntegrationReferral" target="_blank" rel="noreferrer">AudioEye Portal<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M16.3 12.2C16.3 14.5431 16.3 15.8569 16.3 18.2H5.5V7.4H11.5M13.3 5H18.7M18.7 5V10.4M18.7 5L10.9 12.8" stroke="#3975BB" stroke-width="1.5"/>
</svg>
</a>. Look under <i>Installation</i>.</p>

      <div class="ae-site-id-form-edit-button-container">
        <button class="ae-site-id-form-edit-button">
          Re-enter Site ID
        </button>
      </div>

      <div class="ae-site-id-form-container hidden">
        <form class="ae-site-id-form" method="post" enctype="multipart/form-data">
          <input type="hidden" name="action" value="post_first">
          <input type="hidden" name="_nonce" value="<?php echo esc_attr($_nonce); ?>" />
          <div class="site-id-field-container">
            <label class="site-id-field">
              <span>Enter AudioEye Site ID</span>
              <input name="site_hash" value="<?php echo esc_attr($site_hash); ?>"/>
            </label>
            <span class="site-id-field-error">Site ID cannot be empty</span>
          </div>

          <?php
              submit_button( esc_attr__( 'Update Site ID', $this->plugin_name ), 'primary', 'submit-name', TRUE );
          ?>
        </form>
        <button class="ae-site-id-cancel-button">Cancel</button>
      </div>
    </div>
    <a class="ae-help-link" href="https://help.audioeye.com/hc/en-us?utm_source=wordpressPlugInIntegration&utm_medium=audioeyeIntegrationReferral" target="_blank" rel="noreferrer">AudioEye Help Center<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M16.3 12.2C16.3 14.5431 16.3 15.8569 16.3 18.2H5.5V7.4H11.5M13.3 5H18.7M18.7 5V10.4M18.7 5L10.9 12.8" stroke="#3975BB" stroke-width="1.5"/>
</svg>
</a>
  </div>
</section>

<section class="ae-step-three <?php if (!$site_hash) { echo esc_attr('hidden'); } ?>">
  <div class="ae-Card">
    <div class="ae-CardHeader">
      <div class="ae-logo">
        <svg width="86.6" height="20" viewBox="0 0 122 29" fill="none" xmlns="http://www.w3.org/2000/svg">
          <g clip-path="url(#clip0)">
          <path d="M41.9328 20.1252C43.8214 20.1252 45.2268 19.2029 45.7539 17.6657C45.7758 18.4782 45.8417 19.2688 45.9515 19.8617H47.5546C47.313 18.9613 47.2032 17.7316 47.2032 16.0187V13.1639C47.2032 9.97968 45.9295 8.61816 42.9869 8.61816C40.4615 8.61816 38.7486 10.0456 38.6169 12.3074H40.2639C40.3517 10.7483 41.3619 9.89184 43.0089 9.89184C44.8755 9.89184 45.666 10.7922 45.666 13.0541V13.2298L43.2285 13.5152C41.4936 13.7348 40.4176 14.0423 39.627 14.5034C38.7486 15.0524 38.2875 15.9089 38.2875 16.963C38.2875 18.8515 39.7368 20.1252 41.9328 20.1252ZM42.2842 18.8515C40.8129 18.8515 39.8906 18.1049 39.8906 16.919C39.8906 15.7112 40.7031 15.0744 42.5697 14.8109L45.688 14.3717V15.0744C45.688 17.3143 44.3265 18.8515 42.2842 18.8515Z" fill="white"/>
          <path d="M54.0787 20.1263C55.7476 20.1263 57.1311 19.0503 57.6362 17.6009V19.8628H59.0856V8.88281H57.5483V14.8779C57.5483 17.0958 56.3406 18.8087 54.3422 18.8087C52.8489 18.8087 51.8388 17.8425 51.8388 15.6904V8.88281H50.3235V15.8441C50.3235 18.4574 51.3337 20.1263 54.0787 20.1263Z" fill="white"/>
          <path d="M66.3152 20.1264C68.072 20.1264 69.3896 19.1602 70.0264 17.5351V19.8629H71.4758V3.94189H69.9386V10.8812C69.2798 9.34405 68.072 8.61937 66.4908 8.61937C63.7019 8.61937 61.7914 10.991 61.7914 14.3729C61.7914 17.7986 63.5921 20.1264 66.3152 20.1264ZM66.6885 18.7649C64.6462 18.7649 63.4165 17.0959 63.4165 14.3729C63.4165 11.6279 64.6462 9.98089 66.7104 9.98089C68.7527 9.98089 69.9605 11.6498 69.9605 14.1533V14.6144C69.9605 17.052 68.7088 18.7649 66.6885 18.7649Z" fill="white"/>
          <path d="M75.1844 6.90583H76.9193V4.49023H75.1844V6.90583ZM75.2942 19.8622H76.8095V8.88223H75.2942V19.8622Z" fill="white"/>
          <path d="M84.5569 20.1252C87.5874 20.1252 89.5857 17.9512 89.5857 14.3717C89.5857 10.7922 87.5874 8.61816 84.5569 8.61816C81.5045 8.61816 79.5061 10.7922 79.5061 14.3717C79.5061 17.9512 81.5045 20.1252 84.5569 20.1252ZM84.5569 18.7856C82.4268 18.7856 81.1311 17.2265 81.1311 14.3717C81.1311 11.5169 82.4268 9.95772 84.5569 9.95772C86.6651 9.95772 87.9607 11.5169 87.9607 14.3717C87.9607 17.2265 86.6651 18.7856 84.5569 18.7856Z" fill="white"/>
          <path d="M100.757 13.7129C100.757 10.375 99.0657 8.61816 96.123 8.61816C93.0926 8.61816 91.2479 10.8361 91.2479 14.4376C91.2479 18.1268 93.0706 20.1252 96.1889 20.1252C98.4069 20.1252 100.01 18.9833 100.691 16.941L99.3951 16.414C98.9339 17.9072 97.8359 18.7637 96.2987 18.7637C94.1686 18.7637 92.8071 17.2484 92.7851 14.767H100.757V13.7129ZM99.1974 13.5152H92.7851C92.829 11.2534 94.0588 9.84792 96.1011 9.84792C98.0555 9.84792 99.1974 11.0557 99.1974 13.3176V13.5152Z" fill="white"/>
          <path d="M103.288 24.2548C105.111 24.2548 106.319 23.2666 107.307 20.5655L111.589 8.88281H109.942L107.263 16.7006C107.066 17.2935 106.912 17.7986 106.802 18.2158H106.758C106.648 17.7986 106.495 17.2935 106.275 16.7225L103.332 8.88281H101.62L106.012 19.775L105.726 20.5875C105.199 22.0808 104.518 22.8713 103.091 22.8713C102.849 22.8713 102.586 22.8494 102.322 22.8054V24.167C102.652 24.2328 102.959 24.2548 103.288 24.2548Z" fill="white"/>
          <path d="M121.838 13.7129C121.838 10.375 120.147 8.61816 117.205 8.61816C114.174 8.61816 112.329 10.8361 112.329 14.4376C112.329 18.1268 114.152 20.1252 117.27 20.1252C119.488 20.1252 121.092 18.9833 121.772 16.941L120.477 16.414C120.015 17.9072 118.917 18.7637 117.38 18.7637C115.25 18.7637 113.889 17.2484 113.867 14.767H121.838V13.7129ZM120.279 13.5152H113.867C113.911 11.2534 115.14 9.84792 117.183 9.84792C119.137 9.84792 120.279 11.0557 120.279 13.3176V13.5152Z" fill="white"/>
          <path fill-rule="evenodd" clip-rule="evenodd" d="M22.9825 3.17446C20.5542 5.16555 17.448 6.36051 14.0628 6.36051C10.6841 6.36051 7.58347 5.1702 5.15732 3.18605C7.58558 1.19495 10.6917 0 14.077 0C17.4556 0 20.5563 1.19031 22.9825 3.17446Z" fill="#00E3B9"/>
          <path fill-rule="evenodd" clip-rule="evenodd" d="M23.1054 24.8771C20.6615 26.9222 17.513 28.1532 14.0771 28.1532C10.5962 28.1532 7.41046 26.8898 4.95334 24.7966C7.39726 22.7515 10.5457 21.5205 13.9817 21.5205C17.4625 21.5205 20.6483 22.7839 23.1054 24.8771Z" fill="#00E3B9"/>
          <path fill-rule="evenodd" clip-rule="evenodd" d="M24.8913 5.06445C26.9283 7.50609 28.1539 10.6482 28.1539 14.0766C28.1539 17.546 26.8988 20.7223 24.8179 23.1761C22.7809 20.7344 21.5553 17.5923 21.5553 14.1639C21.5553 10.6945 22.8104 7.51828 24.8913 5.06445Z" fill="#00E3B9"/>
          <path fill-rule="evenodd" clip-rule="evenodd" d="M3.21688 5.11963C5.16707 7.53537 6.33478 10.6091 6.33478 13.9555C6.33478 17.3582 5.12749 20.4789 3.11789 22.9126C1.16771 20.4969 0 17.4232 0 14.0768C0 10.6741 1.20729 7.55339 3.21688 5.11963Z" fill="#00E3B9"/>
          </g>
          <defs>
          <clipPath id="clip0">
          <rect width="121.906" height="28.1538" fill="white"/>
          </clipPath>
          </defs>
        </svg>
      </div>

      <h1>AudioEye is active</h1>
      <p class="ae-subtitle">AudioEye enables automated accessibility fixes, your Usability Toolbar, and more!</p>

    </div>

    <div class="ae-CardBody">

      <h2>View your site's accessibility</h2>
      <p>Visit your <a href="https://customer-portal.audioeye.com/login?utm_source=wordpressPlugInIntegration&utm_medium=audioeyeIntegrationReferral" target="_blank" rel="noreferrer">AudioEye Dashboard<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M16.3 12.2C16.3 14.5431 16.3 15.8569 16.3 18.2H5.5V7.4H11.5M13.3 5H18.7M18.7 5V10.4M18.7 5L10.9 12.8" stroke="#3975BB" stroke-width="1.5"/>
</svg>
</a> to manage your site’s accessibility:</p>
      <ul>
        <li>
          <svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19.5366 9.08261H23.8208V26.2197H8.39746V9.08261H12.6817M12.6817 18.508L15.2523 21.0786L20.3934 15.9374M12.6817 11.6532H19.5366V7.3689H12.6817V11.6532Z" stroke="#36A68A" stroke-width="1.46889"/>
          </svg>

          <span>View reports</span>
        </li>
        <li>
          <svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.36914 8.22583H8.226H21.9357H22.7925V9.08269V11.7243C25.224 12.1323 27.0768 14.247 27.0768 16.7944C27.0768 18.317 26.4149 19.6851 25.3631 20.6264V27.0766V28.6777L24.0309 27.7896L21.9357 26.3928L19.8404 27.7896L18.5083 28.6777V27.0766V25.3629H8.226H7.36914V24.5061V9.08269V8.22583ZM18.5083 23.6492V20.6264C17.4565 19.6851 16.7945 18.317 16.7945 16.7944C16.7945 14.247 18.6473 12.1323 21.0788 11.7243V9.93954H9.08285V23.6492H18.5083ZM20.222 21.643V25.4756L21.4604 24.6499L21.9357 24.3331L22.411 24.6499L23.6494 25.4756V21.643C23.1133 21.8324 22.5366 21.9355 21.9357 21.9355C21.3348 21.9355 20.758 21.8324 20.222 21.643ZM21.9357 13.367C20.0428 13.367 18.5083 14.9015 18.5083 16.7944C18.5083 18.6873 20.0428 20.2218 21.9357 20.2218C23.8286 20.2218 25.3631 18.6873 25.3631 16.7944C25.3631 14.9015 23.8286 13.367 21.9357 13.367Z" fill="#36A68A"/>
          </svg>

          <span>Get legal support services</span>
        </li>
        <li>
          <svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19.193 19.7076C22.628 19.6523 25.191 17.1091 25.191 13.7097C25.191 11.1289 24.3367 11.9832 23.4773 12.8528C22.628 13.6919 20.9067 15.4234 20.9067 15.4234L17.4793 11.9959C17.4793 11.9959 19.2108 10.2746 20.0498 9.42538C20.9194 8.56598 20.9194 7.71167 19.193 7.71167C15.7935 7.71167 13.2405 10.2746 13.195 13.7097C13.2306 14.5462 13.195 16.2802 13.195 16.2802C11.5797 17.9057 9.47559 20.0098 8.05387 21.4213C5.54178 23.9436 8.95903 27.3609 11.4813 24.8488C12.8955 23.4243 15.0053 21.3146 16.6224 19.7076C16.6224 19.7076 18.3565 19.672 19.193 19.7076Z" stroke="#36A68A" stroke-width="1.46889"/>
          </svg>

          <span>Configure site options</span>
        </li>
      </ul>

      <h2>If you need to re-enter your Site ID...</h2>
      <p class="ae-need-to-reenter-site-id">Your Site ID can be found in the <a href="https://customer-portal.audioeye.com/login?utm_source=wordpressPlugInIntegration&utm_medium=audioeyeIntegrationReferral" target="_blank" rel="noreferrer">AudioEye Portal<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M16.3 12.2C16.3 14.5431 16.3 15.8569 16.3 18.2H5.5V7.4H11.5M13.3 5H18.7M18.7 5V10.4M18.7 5L10.9 12.8" stroke="#3975BB" stroke-width="1.5"/>
</svg>
</a>. Look under <i>Installation</i>.</p>

      <div class="ae-site-id-form-edit-button-container">
        <button class="ae-site-id-form-edit-button">
          Re-enter Site ID
        </button>
      </div>

      <div class="ae-site-id-form-container hidden">
        <form class="ae-site-id-form" method="post" enctype="multipart/form-data">
          <input type="hidden" name="action" value="post_first">
          <input type="hidden" name="_nonce" value="<?php echo esc_attr($_nonce); ?>" />
          <div class="site-id-field-container">
            <label class="site-id-field">
              <span>Enter AudioEye Site ID</span>
              <input name="site_hash" value="<?php echo esc_attr($site_hash); ?>"/>
            </label>
            <span class="site-id-field-error">Site ID cannot be empty</span>
          </div>

          <?php
              submit_button( esc_attr__( 'Activate AudioEye', $this->plugin_name ), 'primary', 'submit-name', TRUE );
          ?>

          <button class="ae-site-id-cancel-button">Cancel</button>
        </form>
      </div>

    </div>
    <a class="ae-help-link" href="https://help.audioeye.com/hc/en-us?utm_source=wordpressPlugInIntegration&utm_medium=audioeyeIntegrationReferral" target="_blank" rel="noreferrer">AudioEye Help Center<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M16.3 12.2C16.3 14.5431 16.3 15.8569 16.3 18.2H5.5V7.4H11.5M13.3 5H18.7M18.7 5V10.4M18.7 5L10.9 12.8" stroke="#3975BB" stroke-width="1.5"/>
      </svg>
    </a>
  </div>
</section>