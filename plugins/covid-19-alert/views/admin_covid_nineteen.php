<form action="" method="post">

  <?php 

    if ( isset( $_POST['devign_covid_ninteen_submit'] ) ) { 
      devign_covid_ninteen_update_settings();
    }

  ?>
 
  <input 
    type="hidden" 
    name="devign_covid_19_nonce" 
    value="<?php echo wp_create_nonce( 'devign_covid_19_nonce' ); ?>" 
    >

  <div class="wrap devign-content">
    <h1 class="wp-heading-inline">COVID-19 Alerts</h1>
    <hr class="wp-header-end">

    <h2 id="Covid-Tabs" class="nav-tab-wrapper">
      <button class="nav-tab nav-tab-active" data-tab="Display" type="button">Display</button>
      <button class="nav-tab" data-tab="Content" type="button">Content</button>
      <button class="nav-tab" data-tab="Theme" type="button">Theme</button>
      <button class="nav-tab" data-tab="Support" type="button">Support</button>
    </h2>


    <div class="tab-content-area" data-tab="Display" style="display: block;"><!-- Tab Start -->
      <div class="tab-content-wrap">

        <div id="Visibility" class="covid-section">
          <div class="covid-section-header">
            <h3>Visibility</h3>
          </div>
          <div class="covid-section-content">
            <div class="input-group check-group">
              <p class="input-message">Would you like to display the information button on your site?</p>
              <input 
                type="radio" 
                id="devign_covid_ninteen_show_updates_yes" 
                name="devign_covid_ninteen_show_updates" 
                value="yes"
                <?php if ( get_option('devign_covid_ninteen_show_updates') === 'yes' ) : ?>
                  checked
                <?php endif; ?>
                >
              <label for="devign_covid_ninteen_show_updates_yes">Yes</label>
              <input 
                type="radio" 
                id="devign_covid_ninteen_show_updates_no" 
                name="devign_covid_ninteen_show_updates" 
                value="no"
                <?php if ( get_option('devign_covid_ninteen_show_updates') === 'no' ) : ?>
                  checked
                <?php endif; ?>
                >
              <label for="devign_covid_ninteen_show_updates_no">No</label>
            </div>
          </div>
        </div>

        <div id="Positioning" class="covid-section">
          <div class="covid-section-header">
            <h3>Positioning</h3>
          </div>
          <div class="covid-section-content">

            <input 
              type="hidden" 
              name="devign_covid_ninteen_badge_location" 
              value="<?php echo get_option('devign_covid_ninteen_badge_location'); ?>" 
              >

            <p>Please select a positioning location to display the button on your site. By default, the position is set to right.</p>

            <div class="positionArea">

              <?php 
                $leftClasses = array();
                $rightClasses = array();
                $topClasses = array();
                $bottomClasses = array();
                if ( get_option('devign_covid_ninteen_badge_location') === 'left' ) {
                  $leftClasses[] = 'checked ';
                }
                if ( get_option('devign_covid_ninteen_badge_location') === 'right' ) {
                  $rightClasses[] = 'checked ';
                }
                if ( get_option('devign_covid_ninteen_badge_location') === 'top' ) {
                  $topClasses[] = 'checked ';
                }
                if ( get_option('devign_covid_ninteen_badge_location') === 'bottom' ) {
                  $bottomClasses[] = 'checked ';
                }
              ?>

              <div class="leftPos posbtn <?php echo implode( ' ', $leftClasses ); ?>">
                <input 
                  type="radio" 
                  name="devign_covid_ninteen_badge_location" 
                  id="Left" 
                  value="left"
                  <?php if ( get_option('devign_covid_ninteen_badge_location') === 'left' ) : ?>
                    checked
                  <?php endif; ?>
                  >
                <label for="Left">Left</label>
              </div>
              <div class="rightPos posbtn <?php echo implode( ' ', $rightClasses ); ?>">
                <input 
                  type="radio" 
                  name="devign_covid_ninteen_badge_location" 
                  id="Right" 
                  value="right"
                  <?php if ( get_option('devign_covid_ninteen_badge_location') === 'right' ) : ?>
                    checked
                  <?php endif; ?>
                  >
                <label for="Right">Right</label>
              </div>
              <div class="topPos posbtn <?php echo implode( ' ', $topClasses ); ?>">
                <input 
                  type="radio" 
                  name="devign_covid_ninteen_badge_location" 
                  id="Top" 
                  value="top"
                  <?php if ( get_option('devign_covid_ninteen_badge_location') === 'top' ) : ?>
                    checked
                  <?php endif; ?>
                  >
                <label for="Top">Top</label>
              </div>
                <div class="bottomPos posbtn <?php echo implode( ' ', $bottomClasses ); ?>">
                <input 
                  type="radio" 
                  name="devign_covid_ninteen_badge_location" 
                  id="Bottom" 
                  value="bottom"
                  <?php if ( get_option('devign_covid_ninteen_badge_location') === 'bottom' ) : ?>
                    checked
                  <?php endif; ?>
                  >
                <label for="Bottom">Bottom</label>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div><!-- Tab End -->

    <div class="tab-content-area" data-tab="Content"><!-- Tab Start -->
      <div class="tab-content-wrap">

        <div id="Content" class="covid-section">
          <div class="covid-section-header">
            <h3>Content</h3>
          </div>
          <div class="covid-section-content">

            <p>Please fill out the content area with as much information as you need to update your customers, clients and suppliers.</p>

            <div class="input-group">
              <p class="input-message">Heading:</p>
              <?php
                if ( get_option('devign_covid_ninteen_company_heading') ) {
                  $value = get_option('devign_covid_ninteen_company_heading');
                } else {
                    $value = '';
                  }
              ?>
              <input 
                name="devign_covid_ninteen_company_heading" 
                type="text" 
                id="devign_covid_ninteen_company_heading" 
                value="<?php echo $value; ?>"
                class="regular-text" 
                placeholder="e.g. Closure notice, offices closed indefinitely"
                >
            </div>

            <div class="input-group">
              <?php
                if ( get_option('devign_covid_ninteen_company_update') ) {
                  $value = get_option('devign_covid_ninteen_company_update');
                } else {
                    $value = '';
                  }
                $settings = array(
                  'media_buttons' => false,
                  'teeny' => true,
                  'quicktags' => false,
                  'editor_height' => 350, // In pixels, takes precedence and has no default value
                  'textarea_rows' => 20,
                );
                wp_editor( $value, 'devign_covid_ninteen_company_update', $settings );
              ?>
            </div>

            <div class="input-group">
              <p><em><strong>Please note: </strong>For SEO purposes, and to ensure you COVID-19 updates stay relevant and up to date for your customers, it is recommended that you update your content every 7 days.</em></p>
            </div>


          </div>
        </div>

        <div id="MoreInformation" class="covid-section">
          <div class="covid-section-header">
            <h3>More Information</h3>
          </div>
          <div class="covid-section-content">

            <p>Optionally, add a button to link to a local authority webpage that contains more relevant and up to date information about COVID-19 in your area.</p>

            <div class="input-group check-group">
              <p class="input-message">Would you like to add a button to link to a page?</p>
              <input 
                type="radio" 
                id="devign_covid_ninteen_local_authority_show_yes" 
                name="devign_covid_ninteen_local_authority_show" 
                value="yes"
                <?php if ( get_option('devign_covid_ninteen_local_authority_show') === 'yes' ) : ?>
                  checked
                <?php endif; ?>
                >
              <label for="devign_covid_ninteen_local_authority_show_yes">Yes</label>
              <input 
                type="radio" 
                id="devign_covid_ninteen_local_authority_show_no" 
                name="devign_covid_ninteen_local_authority_show" 
                value="no"
                <?php if ( get_option('devign_covid_ninteen_local_authority_show') === 'no' ) : ?>
                  checked
                <?php endif; ?>
                >
              <label for="devign_covid_ninteen_local_authority_show_no">No</label>
            </div>

            <div class="input-group">
              <p class="input-message">Please enter button text here:</p>
              <?php
                if ( get_option('devign_covid_ninteen_local_authority_text') ) {
                  $value = get_option('devign_covid_ninteen_local_authority_text');
                } else {
                    $value = '';
                  }
              ?>
              <input 
                name="devign_covid_ninteen_local_authority_text" 
                type="text" 
                id="devign_covid_ninteen_local_authority_text" 
                value="<?php echo $value; ?>" 
                placeholder="Local COVID-19 Advice"
                class="regular-text" 
                >
            </div>

            <div class="input-group">
              <p class="input-message">Please enter the full link here:</p>
              <?php
                if ( get_option('devign_covid_ninteen_local_authority_link') ) {
                  $value = get_option('devign_covid_ninteen_local_authority_link');
                } else {
                    $value = '';
                  }
              ?>
              <input 
                name="devign_covid_ninteen_local_authority_link" 
                type="text" 
                id="devign_covid_ninteen_local_authority_link" 
                value="<?php echo $value; ?>" 
                class="regular-text" 
                >
            </div>

          </div>
        </div>

        <div id="SpecialAnnouncement" class="covid-section">
          <div class="covid-section-header">
            <h3>Special Announcement</h3>
          </div>
          <div class="covid-section-content">

            <p>Optionally, add suuport for COVID-19 announcements in Google Search. Ensure you complete all fields correctly and with sufficient detail.</p>

            <div class="input-group check-group">
              <p class="input-message">Turn on COVID-19 announcements in Google Search?</p>
              <input 
                type="radio" 
                id="devign_covid_ninteen_google_search_announcement_yes" 
                name="devign_covid_ninteen_google_search_announcement" 
                value="yes"
                <?php if ( get_option('devign_covid_ninteen_google_search_announcement') === 'yes' ) : ?>
                  checked
                <?php endif; ?>
                >
              <label for="devign_covid_ninteen_google_search_announcement_yes">Yes</label>
              <input 
                type="radio" 
                id="devign_covid_ninteen_google_search_announcement_no" 
                name="devign_covid_ninteen_google_search_announcement" 
                value="no"
                <?php if ( get_option('devign_covid_ninteen_google_search_announcement') === 'no' ) : ?>
                  checked
                <?php endif; ?>
                >
              <label for="devign_covid_ninteen_google_search_announcement_no">No</label>
            </div>

            <div class="input-group">
              <p class="input-message">Announcement Coverage:</p>
              <?php
                if ( get_option('devign_covid_ninteen_spatial_coverage') ) {
                  $value = get_option('devign_covid_ninteen_spatial_coverage');
                } else {
                    $value = '';
                  }
              ?>
              <input 
                name="devign_covid_ninteen_spatial_coverage" 
                type="text" 
                id="devign_covid_ninteen_spatial_coverage" 
                value="<?php echo $value; ?>" 
                placeholder="Wakefield, West Yorkshire, UK"
                class="regular-text" 
                >
            </div>

          </div>
        </div>

      </div>
    </div><!-- Tab End -->

    <div class="tab-content-area" data-tab="Theme"><!-- Tab Start -->
      <div class="tab-content-wrap">

        <div id="StickyButtonColor" class="covid-section">
          <div class="covid-section-header">
            <h3>Sticky Button Color</h3>
          </div>
          <div class="covid-section-content">

            <?php
              if ( get_option('devign_covid_ninteen_theme_color') ) {
                $value = get_option('devign_covid_ninteen_theme_color');
              } else {
                  $value = '#df2b00';
                }
            ?>
            <label for="devign_covid_ninteen_theme_color">Theme Color</label>
            <input 
              type="text" 
              name="devign_covid_ninteen_theme_color"
              value="<?php echo $value; ?>"

              >
            
            <br>
            <br>

            <?php
              if ( get_option('devign_covid_ninteen_text_color') ) {
                $value = get_option('devign_covid_ninteen_text_color');
              } else {
                  $value = '#ffffff';
                }
            ?>
            <label for="devign_covid_ninteen_text_color">Text Color</label>
            <input 
              type="text" 
              name="devign_covid_ninteen_text_color"
              value="<?php echo $value; ?>"

              >

          </div>
        </div>

        <div id="PopupColor" class="covid-section">
          <div class="covid-section-header">
            <h3>Popup Color</h3>
          </div>
          <div class="covid-section-content">

            <?php
              if ( get_option('devign_covid_ninteen_background_color') ) {
                $value = get_option('devign_covid_ninteen_background_color');
              } else {
                  $value = '#ffffff';
                }
            ?>
            <label for="devign_covid_ninteen_background_color">Background Color</label>
            <input 
              type="text" 
              name="devign_covid_ninteen_background_color"
              value="<?php echo $value; ?>"

              >
            
            <br>
            <br>

            <?php
              if ( get_option('devign_covid_ninteen_content_text_color') ) {
                $value = get_option('devign_covid_ninteen_content_text_color');
              } else {
                  $value = '#000000';
                }
            ?>
            <label for="devign_covid_ninteen_content_text_color">Content Text Color</label>
            <input 
              type="text" 
              name="devign_covid_ninteen_content_text_color"
              value="<?php echo $value; ?>"

              >

          </div>
        </div>

        <div id="DisplayIcon" class="covid-section">
          <div class="covid-section-header">
            <h3>Display Icon</h3>
          </div>
          <div class="covid-section-content">

            <div class="input-group check-group">
              <p class="input-message">Would you like to display an icon on the button?</p>
              <input 
                type="radio" 
                id="devign_covid_ninteen_show_button_icon_yes" 
                name="devign_covid_ninteen_show_button_icon" 
                value="yes"
                <?php if ( get_option('devign_covid_ninteen_show_button_icon') === 'yes' ) : ?>
                  checked
                <?php endif; ?>
                >
              <label for="devign_covid_ninteen_show_button_icon_yes">Yes</label>
              <input 
                type="radio" 
                id="devign_covid_ninteen_show_button_icon_no" 
                name="devign_covid_ninteen_show_button_icon" 
                value="no"
                <?php if ( get_option('devign_covid_ninteen_show_button_icon') === 'no' ) : ?>
                  checked
                <?php endif; ?>
                >
              <label for="devign_covid_ninteen_show_button_icon_no">No</label>
            </div>

          </div>
        </div>

        <div id="TextInButton" class="covid-section">
          <div class="covid-section-header">
            <h3>Text In Button</h3>
          </div>
          <div class="covid-section-content">

            <div class="input-group">
              <p class="input-message">Please enter the text you wish to display on your button. Default is "COVID-19 Update".</p>
              <br>
              <br>
              <?php
                if ( get_option('devign_covid_ninteen_button_text') ) {
                  $value = get_option('devign_covid_ninteen_button_text');
                } else {
                    $value = '';
                  }
              ?>
              <label for="devign_covid_ninteen_button_text">Max: 16 characters.</label>
              <input 
                name="devign_covid_ninteen_button_text" 
                type="text" 
                id="devign_covid_ninteen_button_text" 
                value="<?php echo $value; ?>" 
                class="regular-text" 
                placeholder="COVID-19 Update"
                maxlength="16"
                >
            </div>

            <div class="input-group check-group">
              <p class="input-message">Display this text on mobile?</p>
              <input 
                type="radio" 
                id="devign_covid_ninteen_button_text_mobile_yes" 
                name="devign_covid_ninteen_button_text_mobile" 
                value="yes"
                <?php if ( get_option('devign_covid_ninteen_button_text_mobile') === 'yes' ) : ?>
                  checked
                <?php endif; ?>
                >
              <label for="devign_covid_ninteen_button_text_mobile_yes">Yes</label>
              <input 
                type="radio" 
                id="devign_covid_ninteen_button_text_mobile_no" 
                name="devign_covid_ninteen_button_text_mobile" 
                value="no"
                <?php if ( get_option('devign_covid_ninteen_button_text_mobile') === 'no' ) : ?>
                  checked
                <?php endif; ?>
                >
              <label for="devign_covid_ninteen_button_text_mobile_no">No</label>
            </div>

          </div>
        </div>

      </div>
    </div><!-- Tab End -->

    <div class="tab-content-area" data-tab="Support"><!-- Tab Start -->
      <div class="tab-content-wrap">

        <div id="Support" class="covid-section">
          <div class="covid-section-header">
            <h3>Support Others</h3>
          </div>
          <div class="covid-section-content">

            <div class="input-group">
              <p>Please help support other businesses by allowing us to display a <strong>"Powered by COVID-19 Updates"</strong> link within the popout. Help spread awareness and help others just like you.</p>
            </div>
            <div class="input-group check-group">
              <p class="input-message">Display an external link on your public facing site?</p>
              <input 
                type="radio" 
                id="devign_covid_ninteen_show_backlink_yes" 
                name="devign_covid_ninteen_show_backlink" 
                value="yes"
                <?php if ( get_option('devign_covid_ninteen_show_backlink') === 'yes' ) : ?>
                  checked
                <?php endif; ?>
                >
              <label for="devign_covid_ninteen_show_backlink_yes">Yes</label>
              <input 
                type="radio" 
                id="devign_covid_ninteen_show_backlink_no" 
                name="devign_covid_ninteen_show_backlink" 
                value="no"
                <?php if ( get_option('devign_covid_ninteen_show_backlink') === 'no' ) : ?>
                  checked
                <?php endif; ?>
                >
              <label for="devign_covid_ninteen_show_backlink_no">No</label>
            </div>

          </div>
        </div>

      </div>
    </div><!-- Tab End -->

    <p class="submit">
      <input 
        type="submit" 
        name="devign_covid_ninteen_submit" 
        id="devign_covid_ninteen_submit" 
        class="button button-primary" 
        value="Update Settings">
      </p>

  </div>

  <div class="wrap devign-advert" style="position: relative;">
    <div class="pluginbanner">
      <div class="pbImage">
        <img src="<?php echo DEVIGN_COVID_19_PLUGIN_PATH.'assets/img/KofiCup.svg'; ?>">
      </div>
      <div class="pbcontent">
        <h2>Enjoying this plugin?</h2>

        <p>If you have found this plugin useful, the cost of a cup of coffee can help support the ongoing development and maintenance of COVID-19 Updates.</p>
        <a class="pbButton" href="https://ko-fi.com/devignstudiosltd" target="_blank">Buy the developers a Coffee</a>
      </div>
      <div class="pbsocials">
        <a href="https://www.facebook.com/devignstudiosltd" target="_blank">
          <span class="dashicons dashicons-facebook-alt"></span>
        </a>
        <a href="https://www.instagram.com/devignstudiosltd/" target="_blank">
          <span class="dashicons dashicons-instagram"></span>
        </a>
        <a href="https://twitter.com/DevignLtd" target="_blank">
          <span class="dashicons dashicons-twitter"></span>
        </a>
      </div>
    </div>
  </div>

</form>