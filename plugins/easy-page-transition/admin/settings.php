<?php


add_action("admin_menu", "cd_ept_addMenu");

//Register Menu
function cd_ept_addMenu() {
add_menu_page("Page Transition", "Page Transition", 4, "easy-page-transition", "cd_ept_admin_settings", 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTgiIHZpZXdCb3g9IjAgMCAxOCAxOCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZD0iTTAgMEgxMy41VjQuNUgxOFYxOEg0LjVWMTMuNUgwVjBaTTEuODMxNCAxLjgzMTRIMTEuNjY4NlYxMS42Njg2SDEuODMxNFYxLjgzMTRaTTYuMzMxNCAxMy41VjE2LjE2ODZIMTYuMTY4NlY2LjMzMTRIMTMuNVYxMy41SDYuMzMxNFoiIGZpbGw9IiNBMEE1QUEiLz48L3N2Zz4=');
}

//register settings
add_action( 'admin_init', 'cd_ept_register_settings' );
function cd_ept_register_settings() {
    //register our settings
    register_setting( 'easyPageTransition-settings-group', 'easy_page_transition_type' );
    register_setting( 'easyPageTransition-settings-group', 'easy_page_transition_color_selector' );
}

//Admin Settings
function cd_ept_admin_settings() { ?>

  <h1 class="EPT__title">Easy Page Transition</h1>

  <section class="EPT__container">

    <form method="post" action="options.php" class="EPT__form">
      <?php settings_fields( 'easyPageTransition-settings-group' ); ?>
      <?php do_settings_sections( 'easyPageTransition-settings-group' ); ?>

      <div class="EPT__field">
        <label for="EPT__transitionType" class="h3">Transition Type</label>
        <select id="EPT__transitionType" name="easy_page_transition_type" value="<?php echo get_option( 'easy_page_transition_type' ); ?>">
          <?php if(get_option( 'easy_page_transition_type' ) == ''){ ?>
            <option selected value="">Select a Transition Type</option>
            <option value="1">Fade Out / Fade In</option>
            <option value="2">Fade Up</option>
            <option value="3">Fade Down</option>
            <option value="4">Fade Right</option>
            <option value="5">Fade Left</option>
            <option value="6">Swipe Top</option>
            <option value="7">Swipe Bottom</option>
            <option value="8">Swipe Right</option>
            <option value="9">Swipe Left</option>
          <?php }elseif(get_option( 'easy_page_transition_type' ) == 1){ ?>
              <option value="">Select a Transition Type</option>
              <option selected value="1">Fade Out / Fade In</option>
              <option value="2">Fade Up</option>
              <option value="3">Fade Down</option>
              <option value="4">Fade Right</option>
              <option value="5">Fade Left</option>
              <option value="6">Swipe Top</option>
              <option value="7">Swipe Bottom</option>
              <option value="8">Swipe Right</option>
              <option value="9">Swipe Left</option>
          <?php }elseif(get_option( 'easy_page_transition_type' ) == 2){ ?>
            <option value="">Select a Transition Type</option>
            <option value="1">Fade Out / Fade In</option>
            <option selected value="2">Fade Up</option>
            <option value="3">Fade Down</option>
            <option value="4">Fade Right</option>
            <option value="5">Fade Left</option>
            <option value="6">Swipe Top</option>
            <option value="7">Swipe Bottom</option>
            <option value="8">Swipe Right</option>
            <option value="9">Swipe Left</option>
          <?php }elseif(get_option( 'easy_page_transition_type' ) == 3){ ?>
            <option value="">Select a Transition Type</option>
            <option value="1">Fade Out / Fade In</option>
            <option value="2">Fade Up</option>
            <option selected value="3">Fade Down</option>
            <option value="4">Fade Right</option>
            <option value="5">Fade Left</option>
            <option value="6">Swipe Top</option>
            <option value="7">Swipe Bottom</option>
            <option value="8">Swipe Right</option>
            <option value="9">Swipe Left</option>
          <?php }elseif(get_option( 'easy_page_transition_type' ) == 4){ ?>
            <option value="">Select a Transition Type</option>
            <option value="1">Fade Out / Fade In</option>
            <option value="2">Fade Up</option>
            <option value="3">Fade Down</option>
            <option selected value="4">Fade Right</option>
            <option value="5">Fade Left</option>
            <option value="6">Swipe Top</option>
            <option value="7">Swipe Bottom</option>
            <option value="8">Swipe Right</option>
            <option value="9">Swipe Left</option>
          <?php }elseif(get_option( 'easy_page_transition_type' ) == 5){ ?>
            <option value="">Select a Transition Type</option>
            <option value="1">Fade Out / Fade In</option>
            <option value="2">Fade Up</option>
            <option value="3">Fade Down</option>
            <option value="4">Fade Right</option>
            <option selected value="5">Fade Left</option>
            <option value="6">Swipe Top</option>
            <option value="7">Swipe Bottom</option>
            <option value="8">Swipe Right</option>
            <option value="9">Swipe Left</option>
          <?php }elseif(get_option( 'easy_page_transition_type' ) == 6){ ?>
            <option value="">Select a Transition Type</option>
            <option value="1">Fade Out / Fade In</option>
            <option value="2">Fade Up</option>
            <option value="3">Fade Down</option>
            <option value="4">Fade Right</option>
            <option value="5">Fade Left</option>
            <option selected value="6">Swipe Top</option>
            <option value="7">Swipe Bottom</option>
            <option value="8">Swipe Right</option>
            <option value="9">Swipe Left</option>
          <?php }elseif(get_option( 'easy_page_transition_type' ) == 7){ ?>
            <option value="">Select a Transition Type</option>
            <option value="1">Fade Out / Fade In</option>
            <option value="2">Fade Up</option>
            <option value="3">Fade Down</option>
            <option value="4">Fade Right</option>
            <option value="5">Fade Left</option>
            <option value="6">Swipe Top</option>
            <option selected value="7">Swipe Bottom</option>
            <option value="8">Swipe Right</option>
            <option value="9">Swipe Left</option>
          <?php }elseif(get_option( 'easy_page_transition_type' ) == 8){ ?>
            <option value="">Select a Transition Type</option>
            <option value="1">Fade Out / Fade In</option>
            <option value="2">Fade Up</option>
            <option value="3">Fade Down</option>
            <option value="4">Fade Right</option>
            <option value="5">Fade Left</option>
            <option value="6">Swipe Top</option>
            <option value="7">Swipe Bottom</option>
            <option selected value="8">Swipe Right</option>
            <option value="9">Swipe Left</option>
          <?php }elseif(get_option( 'easy_page_transition_type' ) == 9){ ?>
            <option value="">Select a Transition Type</option>
            <option value="1">Fade Out / Fade In</option>
            <option value="2">Fade Up</option>
            <option value="3">Fade Down</option>
            <option value="4">Fade Right</option>
            <option value="5">Fade Left</option>
            <option value="6">Swipe Top</option>
            <option value="7">Swipe Bottom</option>
            <option value="8">Swipe Right</option>
            <option selected value="9">Swipe Left</option>
          <?php } ?>
        </select>

      </div>

      <div class="EPT__field">
        <label for="EPT__colorSelector" class="h3">Color Selector</label>
        <input id="EPT__colorSelector" type="color" name="easy_page_transition_color_selector" value="<?php echo get_option( 'easy_page_transition_color_selector' ); ?>" />
        <p class="EPT__desc">Select a color to use on swipe animations. </p>
      </div>


      <?php submit_button(); ?>

    </form>

  </section>


<?php } ?>
