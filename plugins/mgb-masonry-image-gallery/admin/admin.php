<?php

// class to add admin submenu page

class MIGB_Admin_Page
{
  /**
   * Constructor
   */
  public function __construct()
  {
    add_action("admin_menu", [$this, "migb_admin_menu"]);

    // enqueue admin assets
    add_action("admin_enqueue_scripts", [$this, "migb_admin_assets"]);
  }

  /**
   * Enqueue admin scripts
   */
  public function migb_admin_assets($screen)
  {
    if ($screen === "tools_page_migb-gallery") {
      wp_enqueue_style(
        "migb-admin-style",
        MIGB_URL . "admin/admin.css",
        [],
        MIGB_VERSION
      );
      // JS
      wp_enqueue_script(
        "migb-admin-script",
        MIGB_URL . "admin/admin.js",
        ["jquery"],
        MIGB_VERSION,
        true
      );
    }
  }

  /**
   * Add admin menu
   */
  public function migb_admin_menu()
  {
    add_submenu_page(
      "tools.php",
      __("Masonry Gallery", "masonry-image-gallery"),
      __("Masonry Gallery", "masonry-image-gallery"),
      "manage_options",
      "migb-gallery",
      [$this, "migb_admin_page"]
    );
  }

  /**
   * Admin page
   */
  public function migb_admin_page()
  {
    ?>
        <div class="migb__wrap">
            <div class="plugin_max_container">
                <div class="plugin__head_container">
                    <div class="plugin_head">
                        <h1 class="plugin_title">
                            <?php _e(
                              "Masonry Image Gallery",
                              "masonry-image-gallery"
                            ); ?>
                        </h1>
                        <p class="plugin_description">
                            <?php _e(
                              "Masonry Image Gallery is a Custom Gutenberg block that allows you to create masonry photos gallery with lightbox with ease in Gutenberg Editor without any coding knowledge",
                              "masonry-image-gallery"
                            ); ?>
                        </p>
                    </div>
                </div>
                <div class="plugin__body_container">
                    <div class="plugin_body">
                        <div class="tabs__panel_container">
                            <div class="tabs__titles">
                                <p class="tab__title active" data-tab="tab1">
                                    <?php _e(
                                      "Help and Support",
                                      "masonry-image-gallery"
                                    ); ?>
                                </p>
                                <p class="tab__title" data-tab="tab2">
                                    <?php _e(
                                      "Changelog",
                                      "masonry-image-gallery"
                                    ); ?>
                                </p>
                            </div>
                            <div class="tabs__container">
                                <div class="tabs__panels">
                                    <div class="tab__panel active" id="tab1">
                                        <div class="tab__panel_flex">
                                            <div class="tab__panel_left">
                                                <h3 class="video__title">
                                                    <?php _e(
                                                      "Video Tutorial",
                                                      "masonry-image-gallery"
                                                    ); ?>
                                                </h3>
                                                <p class="video__description">
                                                    <?php _e(
                                                      "Watch the video tutorial to learn how to use the plugin. It will help you start your own design quickly.",
                                                      "masonry-image-gallery"
                                                    ); ?>
                                                </p>
                                                <div class="video__container">
                                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/-9urH8xNVe4" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                </div>
                                            </div>
                                            <div class="tab__panel_right">
                                                <div class="single__support_panel">
                                                    <h3 class="support__title">
                                                        <?php _e(
                                                          "Report a Bug",
                                                          "masonry-image-gallery"
                                                        ); ?>
                                                    </h3>
                                                    <p class="support__description">
                                                        <?php _e(
                                                          "If you find any issue or have any suggestion, please let me know.",
                                                          "masonry-image-gallery"
                                                        ); ?>
                                                    </p>
                                                    <a href="https://wordpress.org/support/plugin/mgb-masonry-image-gallery/" class="support__link" target="_blank">
                                                        <?php _e(
                                                          "Support",
                                                          "masonry-image-gallery"
                                                        ); ?>
                                                    </a>
                                                </div>
                                                <div class="single__support_panel">
                                                    <h3 class="support__title">
                                                        <?php _e(
                                                          "Spread Your Love",
                                                          "masonry-image-gallery"
                                                        ); ?>
                                                    </h3>
                                                    <p class="support__description">
                                                        <?php _e(
                                                          "If you like this plugin, please share your opinion",
                                                          "masonry-image-gallery"
                                                        ); ?>
                                                    </p>
                                                    <a href="https://wordpress.org/support/plugin/mgb-masonry-image-gallery/reviews/" class="support__link" target="_blank">
                                                        <?php _e(
                                                          "Rate the Plugin",
                                                          "masonry-image-gallery"
                                                        ); ?>
                                                    </a>
                                                </div>
                                                <div class="single__support_panel">
                                                    <h3 class="support__title">
                                                        <?php _e(
                                                          "Similar Blocks",
                                                          "masonry-image-gallery"
                                                        ); ?>
                                                    </h3>
                                                    <p class="support__description">
                                                        <?php _e(
                                                          "Want to get more similar blocks, please visit my website",
                                                          "masonry-image-gallery"
                                                        ); ?>
                                                    </p>
                                                    <a href="https://makegutenblock.com" class="support__link" target="_blank">
                                                        <?php _e(
                                                          "Visit my Website",
                                                          "masonry-image-gallery"
                                                        ); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="custom__block_request">
                                            <h3 class="custom__block_request_title">
                                                <?php _e(
                                                  "Need to Hire Me?",
                                                  "masonry-image-gallery"
                                                ); ?>
                                            </h3>
                                            <p class="custom__block_request_description">
                                                <?php _e(
                                                  "I am available for any freelance projects. Please feel free to share your project detail with me.",
                                                  "masonry-image-gallery"
                                                ); ?>
                                            </p>
                                            <div class="available__links">
                                                <a href="mailto:zbinsaifullah@gmail.com" class="available__link mail" target="_blank">
                                                    <?php _e(
                                                      "Send Email",
                                                      "masonry-image-gallery"
                                                    ); ?>
                                                </a>
                                                <a href="https://makegutenblock.com/contact" class="available__link web" target="_blank">
                                                    <?php _e(
                                                      "Send Message",
                                                      "masonry-image-gallery"
                                                    ); ?>
                                                </a>
                                                <a href="https://www.fiverr.com/devs_zak" class="available__link fiverr" target="_blank">
                                                    <?php _e(
                                                      "Fiverr",
                                                      "masonry-image-gallery"
                                                    ); ?>
                                                </a>
                                                <a href="https://www.upwork.com/freelancers/~010af183b3205dc627" class="available__link upwork" target="_blank">
                                                    <?php _e(
                                                      "UpWork",
                                                      "masonry-image-gallery"
                                                    ); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab__panel" id="tab2">
                                        <div class="change__log_head">
                                            <h3 class="change__log_title">
                                                <?php _e(
                                                  "Changelog",
                                                  "masonry-image-gallery"
                                                ); ?>
                                            </h3>
                                            <p class="change__log_description">
                                                <?php _e(
                                                  "This is the changelog of the plugin. You can see the changes in each version.",
                                                  "masonry-image-gallery"
                                                ); ?>
                                            </p>
                                            <div class="change__notes">
                                                <div class="single__note">
                                                    <span class="info change__note"><?php _e(
                                                      "i",
                                                      "masonry-image-gallery"
                                                    ); ?></span>
                                                    <span class="note__description"><?php _e(
                                                      "Info",
                                                      "masonry-image-gallery"
                                                    ); ?></span>
                                                </div>
                                                <div class="single__note">
                                                    <span class="feature change__note"><?php _e(
                                                      "N",
                                                      "masonry-image-gallery"
                                                    ); ?></span>
                                                    <span class="note__description"><?php _e(
                                                      "New Feature",
                                                      "masonry-image-gallery"
                                                    ); ?></span>
                                                </div>
                                                <div class="single__note">
                                                    <span class="update change__note"><?php _e(
                                                      "U",
                                                      "masonry-image-gallery"
                                                    ); ?></span>
                                                    <span class="note__description"><?php _e(
                                                      "Update",
                                                      "masonry-image-gallery"
                                                    ); ?></span>
                                                </div>
                                                <div class="single__note">
                                                    <span class="fixing change__note"><?php _e(
                                                      "F",
                                                      "masonry-image-gallery"
                                                    ); ?></span>
                                                    <span class="note__description"><?php _e(
                                                      "Issue Fixing",
                                                      "masonry-image-gallery"
                                                    ); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="change__log_body">
                                            <div class="single__log">
                                                <div class="plugin__info">
                                                    <span class="log__version">2.1.0</span>
                                                    <span class="log__date">2022-11-13</span>
                                                </div>
                                                <div class="log__description">
                                                    <span class="change__note feature">U</span>
                                                    <span class="description__text"><?php _e(
                                                      "A great updates with completely different looks and feel.",
                                                      "masonry-image-gallery"
                                                    ); ?></span>
                                                </div>
                                            </div>
                                            <div class="single__log">
                                                <div class="plugin__info">
                                                    <span class="log__version">1.0.0</span>
                                                    <span class="log__date">2021-05-21</span>
                                                </div>
                                                <div class="log__description">
                                                    <span class="change__note info">i</span>
                                                    <span class="description__text"><?php _e(
                                                      "Initial Release",
                                                      "masonry-image-gallery"
                                                    ); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
  }
}

new MIGB_Admin_Page();
