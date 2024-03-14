<?php

  /*
   *  Settings page : General
   *  Set the general settings of the plugin
   *  Controller : ../controllers/gc_general_controller.class.php
   */

  /**
   * @var $activated string
   */

  $activated_all = get_option('gc_activated_all');
  $activated_from = get_option('gc_activated_from');
  $options_activated = array(
      'seo_activated' => get_option('gc_seo_activated'),
      'sso_activated' => get_option('gc_sso_activated'),
      'overlay_activated' => get_option('gc_overlay_activated'),
      'readonly_activated' => get_option('gc_readonly_activated'),
      'readonly_who' => get_option('gc_readonly_who'),
      'readonly_roles' => get_option('gc_readonly_roles'),
  );
?>

<form id="gc-general" class="gc-form" method="post" action="options.php">
  <input type="hidden" name="gc_action" value="general"/>

  <div class="gc-form-fieldset">
    <!--
    <h4 class="gc-form-legend"><?php _e('Activation', 'graphcomment-comment-system') ?></h4>
    -->

    <div class="gc-form-field gc-checkbox">
      <label>
        <input type="checkbox" name="gc_activated" value="true"
          <?php echo ($activated == true) ? 'checked' : ''; ?>
        /><?php _e('Activate Graphcomment', 'graphcomment-comment-system'); ?>
      </label>
    </div>

    <div class="gc-form-fieldset hide-not-activated" style="display: <?php echo (!$activated) ? 'none' : 'block'; ?>;">

      <div class="gc-form-field gc-radio">
        <label>
          <input type="radio" name="gc_activated_type" value="all"
             <?php echo ($activated_all == true || !$activated) ? 'checked' : ''; ?>
          /><?php _e('Activate Graphcomment on all posts', 'graphcomment-comment-system'); ?>
        </label>
      </div>

      <div class="gc-form-field gc-radio">
        <label>
          <input type="radio" name="gc_activated_type" value="from"
             <?php echo ($activated_from == true) ? 'checked' : ''; ?>
          /><?php _e('Activate Graphcomment on posts from', 'graphcomment-comment-system'); ?>
        </label>
      </div>

      <div class="gc-form-field gc-shifted">
        <input
          type="text" class="form-control" id="datepicker"
          name="gc_activated_from" style="width: 200px;"
          value="<?php echo $activated_from ? $activated_from : date("Y-m-d", time() - date("Z")); ?>"
        />
      </div>

    </div>

  </div>

  <div class="gc-form-fieldset">
    <h4 class="gc-form-legend"><?php _e('Options', 'graphcomment-comment-system') ?></h4>

    <div class="gc-form-field gc-checkbox">
      <input type="checkbox" id="gc_seo_activated" name="gc_seo_activated" value="true"
        <?php echo ($options_activated['seo_activated'] == true) ? 'checked' : ''; ?>
      />
      <label for="gc_seo_activated">
        <?php _e('Activate SEO (Replace by wordpress comment system when SE bot)', 'graphcomment-comment-system'); ?>
      </label>
    </div>

    <div class="gc-form-field gc-checkbox">
        <input type="checkbox" id="gc_sso_activated" name="gc_sso_activated" value="true"
          <?php echo GcParamsService::getInstance()->graphcommentGetWebsiteRights() === 'free' ? 'disabled' : ''; ?>
          <?php echo ($options_activated['sso_activated'] == true) ? 'checked' : ''; ?>
        />
        <label for="gc_sso_activated" style="position: relative;">
          <p style="position: relative;">
            <?php _e('Activate SSO', 'graphcomment-comment-system'); ?>
            <a class="gc-form-help popover-trigger" href="#popover-sso">?</a>
            <br>
            <?php if (GcParamsService::getInstance()->graphcommentGetWebsiteRights() === 'free') { ?>
              <a href="<?php echo admin_url('admin.php?page=graphcomment&url=' . urlencode('/graphcomment/upgrade')); ?>">
                <?php _e('Available Paid Accounts', 'graphcomment-comment-system'); ?>
              </a>
            <?php } ?>
          </p>
          <div class="gc-popover" id="popover-sso">
            <div class="gc-popover-arrow"></div>
            <div class="gc-popover-section">
              <h1 class="gc-popover-head"><?php _e('Popover sso title', 'graphcomment-comment-system'); ?></h1>
              <p class="gc-popover-text"><?php _e('Popover sso desc', 'graphcomment-comment-system'); ?></p>
            </div>
            <div class="gc-popover-section">
              <img class="gc-popover-img" src="<?php echo plugins_url('../../theme/images/popover_sso.png', __FILE__); ?>">
            </div>
          </div>
        </label>
    </div>

    <div class="gc-form-field gc-checkbox">
      <input type="checkbox" id="gc_overlay_activated" name="gc_overlay_activated" value="true"
        <?php echo GcParamsService::getInstance()->graphcommentGetWebsiteRights() === 'free' ? 'disabled' : ''; ?>
        <?php echo ($options_activated['overlay_activated'] == true) ? 'checked' : ''; ?>
      />
      <label for="gc_overlay_activated" style="position: relative">
        <p style="position: relative">
          <?php _e('Activate Overlay', 'graphcomment-comment-system'); ?>
          <a class="gc-form-help popover-trigger" href="#popover-overlay">?</a>
          <br>
          <?php if (GcParamsService::getInstance()->graphcommentGetWebsiteRights() === 'free') { ?>
            <a href="<?php echo admin_url('admin.php?page=graphcomment&url=' . urlencode('/graphcomment/upgrade')); ?>">
              <?php _e('Available Paid Accounts', 'graphcomment-comment-system'); ?>
            </a>
          <?php } ?>
        </p>
        <div class="gc-popover" id="popover-overlay">
          <div class="gc-popover-arrow"></div>
          <div class="gc-popover-section">
            <h1 class="gc-popover-head"><?php _e('Popover overlay title', 'graphcomment-comment-system'); ?></h1>
            <p class="gc-popover-text"><?php _e('Popover overlay desc', 'graphcomment-comment-system'); ?></p>
          </div>
          <div class="gc-popover-section">
            <img class="gc-popover-img" src="<?php echo plugins_url('../../theme/images/popover_overlay.png', __FILE__); ?>">
          </div>
        </div>
      </label>
    </div>

    <div id="gc-overlay-options" class="gc-form-fieldset" style="display: <?php echo !$options_activated['overlay_activated'] ? 'none' : 'block'; ?>">

      <div class="gc-form-field gc-checkbox">
        <label style="font-weight: bold">
          <input type="checkbox" class="form-control" name="gc_overlay_visible"
            <?php echo get_option('gc_overlay_visible') ? 'checked' : ''; ?>
          /><?php _e('Overlay visible at loading time', 'graphcomment-comment-system'); ?>
        </label>
      </div>

      <div class="gc-form-field gc-checkbox">
        <label style="font-weight: bold">
          <input type="checkbox" class="form-control" name="gc_overlay_bubble"
            <?php echo get_option('gc_overlay_bubble') ? 'checked' : ''; ?>
          /><?php _e('Overlay bubble', 'graphcomment-comment-system'); ?>
        </label>
      </div>

      <div class="gc-form-field gc-inline">
        <label>
          <?php _e('Overlay button label', 'graphcomment-comment-system'); ?>
          <input type="text" class="gc-form-control" name="gc_overlay_button_label"
            placeholder="<?php _e('Overlay button placeholder', 'graphcomment-comment-system'); ?>"
            value="<?php echo get_option('gc_overlay_button_label'); ?>"
          />
        </label>
      </div>

      <div class="gc-form-field gc-inline">
        <label>
          <?php _e('Overlay button background', 'graphcomment-comment-system'); ?>
          <div style="position: relative">
            <div class="colorpicker"></div>
            <input type="text" class="form-control" name="gc_overlay_button_background" value="<?php echo get_option('gc_overlay_button_background', '#f35b5b'); ?>" />
          </div>
        </label>
      </div>

      <div class="gc-form-field gc-inline">
        <label>
          <?php _e('Overlay button color', 'graphcomment-comment-system'); ?>
          <div style="position: relative">
            <div class="colorpicker"></div>
            <input type="text" class="form-control" name="gc_overlay_button_color" value="<?php echo get_option('gc_overlay_button_color', '#ffffff'); ?>" />
          </div>
        </label>
      </div>

      <div class="gc-form-field gc-inline">
        <label>
          <?php _e('Overlay width', 'graphcomment-comment-system'); ?>
          <input type="text" class="gc-form-control" name="gc_overlay_width" value="<?php echo get_option('gc_overlay_width', '400'); ?>" />
        </label>
      </div>

      <div class="gc-form-field gc-inline">
        <label>
          <?php _e('Overlay header height', 'graphcomment-comment-system'); ?>
          <input type="text" class="gc-form-control" name="gc_overlay_fixed_header_height" value="<?php echo get_option('gc_overlay_fixed_header_height'); ?>" />
        </label>
      </div>

    </div>

    <div class="gc-form-field gc-checkbox">
      <input id="gc_readonly_checkbox" type="checkbox" name="gc_readonly_activated" value="true"
        <?php echo GcParamsService::getInstance()->graphcommentGetWebsiteRights() === 'free' ? 'disabled' : ''; ?>
        <?php echo ($options_activated['readonly_activated'] == true) ? 'checked' : ''; ?>
      />
      <label for="gc_readonly_checkbox" style="position: relative">
        <p style="position: relative">
          <?php _e('Activate Readonly', 'graphcomment-comment-system'); ?>
          <a class="gc-form-help popover-trigger" href="#popover-readonly">?</a>
          <br>
          <?php if (GcParamsService::getInstance()->graphcommentGetWebsiteRights() === 'free') { ?>
            <a href="<?php echo admin_url('admin.php?page=graphcomment&url=' . urlencode('/graphcomment/upgrade')); ?>">
              <?php _e('Available Paid Accounts', 'graphcomment-comment-system'); ?>
            </a>
          <?php } ?>
        </p>
        <div class="gc-popover" id="popover-readonly">
          <div class="gc-popover-arrow"></div>
          <div class="gc-popover-section">
            <h1 class="gc-popover-head"><?php _e('Popover readonly title', 'graphcomment-comment-system'); ?></h1>
            <p class="gc-popover-text"><?php _e('Popover readonly desc', 'graphcomment-comment-system'); ?></p>
          </div>
          <div class="gc-popover-section">
            <img class="gc-popover-img" src="<?php echo plugins_url('../../theme/images/popover_readonly.png', __FILE__); ?>">
          </div>
        </div>
      </label>
    </div>

    <div id="gc-readonly-options" class="gc-form-fieldset" style="display: <?php echo !$options_activated['readonly_activated'] ? 'none' : 'block'; ?>">

      <div class="gc-form-field gc-radio">
        <label for="readonly_all_users" style="font-weight: bold">
          <input type="radio" class="form-control" id="readonly_all_users" name="gc_readonly_who" value="all"
            <?php echo ($options_activated['readonly_who'] ? $options_activated['readonly_who'] === 'all' : true) ? 'checked' : ''; ?>
          /><?php _e('Readonly all users', 'graphcomment-comment-system'); ?>
        </label>
      </div>

      <div class="gc-form-field gc-radio">
        <label for="readonly_specific_users" style="font-weight: bold">
          <input type="radio" class="form-control" id="readonly_specific_users" name="gc_readonly_who" value="specific"
            <?php echo get_option('gc_readonly_who') === 'specific' ? 'checked' : ''; ?>
          /><?php _e('Readonly specific users', 'graphcomment-comment-system'); ?>
        </label>
      </div>

      <div class="gc-form-field gc-shifted">
        <select id="readonly_roles" name="gc_readonly_roles[]" multiple style="min-height: 120px; min-width: 200px">
          <?php foreach (get_editable_roles() as $role => $infos) { ?>
            <option value="<?php echo $role; ?>" <?php echo is_array($options_activated['readonly_roles']) && in_array($role, $options_activated['readonly_roles']) ? 'selected' : ''; ?>><?php echo $infos['name']; ?></option>
          <?php } ?>
        </select>
      </div>

    </div>

  </div>

  <?php submit_button(); ?>

</form>
