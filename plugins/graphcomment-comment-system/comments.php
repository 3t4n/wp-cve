<?php
/**
 * File that contains all the logic regarding the handling of comments
 */

$gc_public_key = GcParamsService::getInstance()->graphcommentGetWebsite();

if (empty($gc_public_key)) {
  $pluginData = get_plugin_data(dirname(__FILE__) . '/graphcomment.php');
  $html_error = "<div class=\"gc-wp-error\">\n";
  $html_error .= "<!-- " . $pluginData['Version'] . " -->\n";
  $html_error .= "  <div class=\"gc-wp-error-inner\">\n";
  $html_error .= "    <strong>" . __('Error', 'graphcomment-comment-system') . ": </strong>";
  $html_error .= __('GraphComment couldn\'t be load because your settings are invalid.', 'graphcomment-comment-system') . ' ';
  $html_error .= __('Please visit your admin panel and go to the GraphComment section and enter a valid website URL/ID.', 'graphcomment-comment-system');
  $html_error .= "  </div>\n";
  $html_error .= "</div>\n";

  echo $html_error;
  return;
}

// sso

$ssoActivated = get_option('gc_sso_activated');
$user = wp_get_current_user();

if ($ssoActivated && $user->ID) {
  $nickname = get_user_meta($user->data->ID, 'nickname', true);
  $data = array(
      'id' => $user->data->ID, // required unique
      'username' => $nickname ? $nickname : $user->data->user_nicename, // required unique
      'email' => $user->data->user_email, // required unique
      'language' => substr(get_user_locale($user->data->ID), 0, 2), //(optionnal) default value : en (codes ISO 639-1)
      'picture' => get_avatar_url($user->data->ID) // (optionnal) full url only
  );

  $privateKey = get_option('gc_api_private_key');
  if ($privateKey) $ssoData = generateSsoData($data, $privateKey);
}

$overlayActivated = get_option('gc_overlay_activated');

// readonly

$readonlyActivated = get_option('gc_readonly_activated');
$readonlyWho = get_option('gc_readonly_who');
$readonlyRoles = get_option('gc_readonly_roles');

$user = wp_get_current_user();
$readonly = false;

if ($readonlyActivated) {
  if ($readonlyWho === 'all') {
    $readonly = true;
  }
  else if ($readonlyWho === 'specific') {
    if ($user) {
      $readonly = true;
      $roles = $user->roles;
      foreach($user->roles as $role) {
        if (in_array($role, $readonlyRoles)) {
          $readonly = false;
        }
      }
    } else {
      $readonly = true;
    }
  }
}

// fixed_header_height

$fixedHeaderHeight = 0;

if (get_option('gc_overlay_fixed_header_height')) {
  $fixedHeaderHeight = get_option('gc_overlay_fixed_header_height');
}
else if (is_admin_bar_showing()) {
  $fixedHeaderHeight = 'window.innerWidth <= 782 ? 46 : 32';
}

?>

<div id="comments"></div>
<div id="graphcomment"></div>
<!-- <?php $pluginData = get_plugin_data(dirname(__FILE__) . '/graphcomment.php'); echo $pluginData['Version']; ?> -->
<script type="text/javascript">
  (function() {
    var __semio__token;
    var __semio__callback;

    var __semio__params = {
      graphcommentId:  '<?php echo $gc_public_key; ?>',
      behaviour: {
        uid: '<?php echo GcParamsService::getInstance()->graphcommentUid(get_post()); ?>',
        readonly: <?php echo $readonly ? 'true' : 'false'; ?>,
      },
      integration: {
        fixedHeaderHeight: <?php echo $fixedHeaderHeight; ?>,
      },
      <?php if ($overlayActivated) { ?>
      sidePanel: {
        visible: <?php echo get_option('gc_overlay_visible') ? 'true' : 'false'; ?>,
        width: <?php echo get_option('gc_overlay_width', '400'); ?>,
        button: {
          label: '<?php echo get_option('gc_overlay_button_label'); ?>',
          color: '<?php echo get_option('gc_overlay_button_color'); ?>',
          background: '<?php echo get_option('gc_overlay_button_background'); ?>',
        },
        bubble: <?php echo get_option('gc_overlay_bubble') ? 'true' : 'false'; ?>,
      },
      <?php } else { ?>
      sidePanel: false,
      <?php } ?>
      statistics: {
        pageTitle: '<?php echo GcParamsService::getInstance()->graphcommentIdenfitierGetPostTitle(get_post()); ?>',
      },
      wordpress: {
        guid: '<?php echo GcParamsService::getInstance()->graphcommentGuid(get_post()); ?>',
        identifier: '<?php echo GcParamsService::getInstance()->graphcommentIdentifierGenerate(get_post()); ?>',
      }
      <?php if ($ssoActivated) { ?>
      ,auth: {
        login: function() {
          window.location.href = "<?php echo wp_login_url($_SERVER['REQUEST_URI']); ?>";
        },
        logout: function() {
          window.location.href = "<?php echo wp_logout_url($_SERVER['REQUEST_URI']); ?>";
        },
        signup: function() {
          window.location.href = "<?php echo wp_login_url($_SERVER['REQUEST_URI']); ?>";
        },
        subscribeToToken: function(cb) {
          if (__semio__token) {
            cb(__semio__token);
          }
          __semio__callback = cb;
        },
      }
      <?php } ?>
    };

    <?php if ($ssoActivated) { ?>
      <?php if ($overlayActivated) { ?>
        var lib = 'gc_sidePanel';
      <?php } else { ?>
        var lib = 'gc';
      <?php } ?>
    <?php } else { ?>
      <?php if ($overlayActivated) { ?>
        var lib = 'gc_sidePanel_graphlogin';
      <?php } else { ?>
        var lib = 'gc_graphlogin';
      <?php } ?>
    <?php } ?>

    function __semio__onload() {
      window['__semio__' + lib](__semio__params)
    }


    (function() {
      var gc = document.createElement('script');
      gc.type = 'text/javascript';
      gc.async = true;
      gc.onload = __semio__onload;
      gc.defer = true;
      gc.src = '<?php echo constant('INTEGRATION_URL'); ?>/' + lib + '.js';
      (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(gc);
    })();


    <?php if ($ssoActivated) { ?>
      function __semio__onload__sso() {
        __semio__helpers_sso({
          graphcommentId: '<?php echo $gc_public_key; ?>',
          publicKey: '<?php echo get_option("gc_api_public_key"); ?>',
          data: '<?php echo isset($ssoData) ? $ssoData : ''; ?>',
          onSuccess: function(token) {
            if (__semio__callback) {
              __semio__callback(token)
            }
            __semio__token = token;
          }
        });
      };

      (function() {
        var gc = document.createElement('script');
        gc.type = 'text/javascript';
        gc.async = true;
        gc.onload = __semio__onload__sso;
        gc.defer = true;
        gc.src = '<?php echo constant('INTEGRATION_URL'); ?>/helpers_sso.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(gc);
      })();
    <?php } ?>

    function __semio__onload__counter() {
      __semio__helpers_counter('<?php echo $gc_public_key; ?>');
    };

    (function() {
      var gc = document.createElement('script');
      gc.type = 'text/javascript';
      gc.async = true;
      gc.onload = __semio__onload__counter;
      gc.defer = true;
      gc.src = '<?php echo constant('INTEGRATION_URL'); ?>/helpers_counter.js';
      (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(gc);
    })();
  })()
</script>
