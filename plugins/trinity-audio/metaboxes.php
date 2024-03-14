<?php
  require_once __DIR__ . '/utils.php';
  require_once __DIR__ . '/inc/common.php';

  add_action('admin_enqueue_scripts', 'trinity_metabox_scripts');

  function trinity_metabox_scripts() {
    wp_enqueue_script('trinity_audio_metaboxes', plugin_dir_url(__FILE__) . 'js/metaboxes.js', [], wp_rand(), true);
    wp_enqueue_style('trinity_audio_styles', plugin_dir_url(__FILE__) . 'dist/styles.css', [], wp_rand());
  }

  if (!empty(trinity_get_install_key())) add_action('add_meta_boxes', 'trinity_add_meta_boxes');

  function trinity_add_meta_boxes() {
    add_meta_box('trinity_audio_box_id', 'Trinity Audio', 'trinity_audio_box_content', ['post'], 'normal', 'high');
  }

  function trinity_meta_tts_enabled($post_id) {
    global $pagenow;

    $is_trinity_enabled_for_post = trinity_is_enabled_for_post($post_id);

    $checked = '';

    if ($pagenow === 'post-new.php') {
      $checked = trinity_get_new_posts_default() ? 'checked' : '';
    }

    if ($is_trinity_enabled_for_post === '1') {
      $checked = 'checked';
    }

    echo "<input type='checkbox' name='" . esc_attr(TRINITY_AUDIO_ENABLED) . "' id='" . esc_attr(TRINITY_AUDIO_ENABLED) . "'" . checked( $checked, 'checked', false ) . '/>';
  }

  function trinity_meta_source_gender($post_id) {
    echo "<select onchange='updateVoiceValue()' name='" . esc_attr(TRINITY_AUDIO_GENDER_ID) . "' id='" . esc_attr(TRINITY_AUDIO_GENDER_ID) . "'>";

    $post_gender = get_post_meta($post_id, TRINITY_AUDIO_GENDER_ID, true);
    $genders     = array_merge(['' => TRINITY_AUDIO_LABEL_DEFAULT], TRINITY_AUDIO_GENDER_ARRAY);

    foreach ($genders as $key => $value) {
      $selected = $post_gender === $key ? 'selected' : '';

      echo "<option value='" . esc_attr($key) . "' " . esc_attr($selected) . '>' . esc_html($value) . '</option>';
    }

    echo '</select>';
  }

  function trinity_meta_source_language($post_id) {
    $languages = trinity_get_languages();

    echo "<input type='hidden' name='" . esc_attr(TRINITY_AUDIO_VOICE_ID) . "' id='" . esc_attr(TRINITY_AUDIO_VOICE_ID) . "' class='trinity-audio-metaboxes-element'/>";
    echo "<select onchange='updateVoiceValue()' name='" . esc_attr(TRINITY_AUDIO_SOURCE_LANGUAGE) . "' id='" . esc_attr(TRINITY_AUDIO_SOURCE_LANGUAGE) . "'>";

    $post_language    = get_post_meta($post_id, TRINITY_AUDIO_SOURCE_LANGUAGE, true);
    $result_languages = array_merge(
      [
        (object)[
          'code' => '',
          'name' => TRINITY_AUDIO_LABEL_DEFAULT,
        ],
      ],
      $languages
    );

    foreach ($result_languages as $lang) {
      $language_code = $lang->code;
      $language_name = $lang->name;

      if (isset($lang->voices)) $voicesEncoded = json_encode($lang->voices);
      else $voicesEncoded = '';


      $selected = $post_language === $language_code ? 'selected' : '';

      echo "<option data-voices='$voicesEncoded' value='" . esc_attr($language_code) . "' " . esc_attr($selected) . '>' . esc_html($language_name) . '</option>';
    }

    echo '</select>';
  }

  function trinity_audio_box_content($post) {
    ?>
    <div id="trinity-metabox">
      <?php
        $nonce = wp_create_nonce('trinity-audio-metabox');
        echo '<input type="hidden" name="' . esc_attr(TRINITY_AUDIO_NONCE_NAME) . '" value="' . esc_attr($nonce) . '" />';
      ?>

      <div class="components-tab-panel__tabs">
        <button type="button" class="components-button is-active"
                data-id="main">
          Main Settings
        </button>
        <button type="button" class="components-button"
                data-id="advanced">
          Advanced Settings
        </button>
      </div>

      <div class="components-tab-panel__tab-content">
        <div data-id="main" class="content is-active">
          <table class="form-table">
            <tr>
              <th style="width: 250px;">
                <label for="<?php echo esc_attr(TRINITY_AUDIO_ENABLED); ?>">
                  Enable Text-To-Speech (Trinity audio):
                </label>
              </th>
              <td>
                <?php trinity_meta_tts_enabled($post->ID); ?>
              </td>
              <td rowspan="3" class="trinity-meta-upgrade-banner">
                <?php
                  trinity_post_management_banner();
                ?>
              </td>
            </tr>
            <tr>
              <th>
                <label for="<?php echo esc_attr(TRINITY_AUDIO_GENDER_ID); ?>">Gender:</label>
              </th>
              <td>
                <?php trinity_meta_source_gender($post->ID); ?>
              </td>
            </tr>
            <tr>
              <th>
                <label for="<?php echo esc_attr(TRINITY_AUDIO_SOURCE_LANGUAGE); ?>">Language:</label>
              </th>
              <td>
                <?php trinity_meta_source_language($post->ID); ?>
              </td>
            </tr>
          </table>
        </div>

        <div data-id="advanced" class="content">
          <p>Please use this section in case you are having issues
            with the player on this post or if instructed by <?php echo TRINITY_AUDIO_SUPPORT_MESSAGE; ?></p>

          <h4 title="Each token represents different text version created for this post">
            <span class="dashicons dashicons-info"></span>
            <span>Current tokens:</span>
          </h4>

          <?php
            $hashes = trinity_ph_get_posthashes_for_post_id($post->ID);
          ?>

          <ul>
            <li>
              <label>Title + content:</label>
              <span
                  class="trinity-meta-title-content"><?php if($hashes) echo esc_html($hashes[TRINITY_AUDIO_POST_META_MAP[TRINITY_AUDIO_TITLE_CONTENT]]); ?></span>
            </li>
          </ul>

          <div class="trinity-submit-wrapper">
            <button class="button">Regenerate token</button>
            <span class="trinity-status-wrapper">
        <span class="status success">
          <span class="dashicons dashicons-yes"
                style="color: green"></span>
          <span>Tokens were generated successfully</span>
        </span>
        <span class="status error">
          <span class="dashicons dashicons-dismiss"
                style="color: red"></span>
          <span>A problem occurred while regenerating tokens. Try again later</span>
        </span>
        <span class="status progress">
          <span class="dashicons dashicons-update"></span>
          <span>Regenerating tokens...</span>
        </span>
      </span>
          </div>
        </div>
      </div>
    </div>
    <?php

    wp_localize_script(
      'trinity_audio_metaboxes',
      'TRINITY_WP_METABOX',
      [
        'postId'                              => $post->ID,
        'TRINITY_AUDIO_POST_META_MAP'         => TRINITY_AUDIO_POST_META_MAP
      ]
    );
  }
