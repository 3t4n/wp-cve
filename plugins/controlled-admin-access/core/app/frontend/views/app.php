<script>
  var caa_nonces = <?php echo json_encode($nonce); ?>;
  window.logged_in_user_id = <?php echo get_current_user_id(); ?>;
  window.admin_url = '<?php echo admin_url(); ?>';
  window.internal_plugins_cover = '<?php echo plugin_dir_url(__FILE__) . 'internal-plugins-pages.png'; ?>';
  window.login_url = '<?php echo wp_login_url(); ?>';
</script>

<div class="wrap">
    <div id="app"></div>
    <div class="clear"></div>
</div>

<style>
    .notice {
        display:none;
    }
    .wrap {
        margin: 0 20px 0 2px !important;
    }
</style>
