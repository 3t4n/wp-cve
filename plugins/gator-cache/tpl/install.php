<?php if(!defined('ABSPATH') || !is_admin()){//no direct or frontend access
    exit;
}
$notices = GatorCache::getNotices();
if($notices->has()){?>
  <div class="updated">
    <p><strong><?php _e('Error Code', 'gator-cache')?> <?php echo $notices->get()->getCode();?></strong>: <?php echo $notices->get()->getMessage();?> <strong><?php _e('Re-installation Required', 'gator-cache')?></strong></p>
  </div>
<?php }
$cacheDir = GatorCache::getPlugin($options, self::$configPath, self::$path)->getInitDir(true);?>
<div class="wrap">
  <h2>Gator Cache <?php _e('Installation', 'gator-cache');?></h2>
  <h3><?php _e('Gator Cache Installation', 'gator-cache');?></h3>
  <div id="gci_result" style="display:none;margin:1em 0;color:forestgreen;font-weight:600"></div>
  <form id="gci_install" method="post" action="">
    <div id="step_1">
      <label><?php printf('<strong>%s</strong>) %s', __('Install', 'gator-cache'), __('Create Cache Directory and Copy Files', 'gator-cache'));?></label>
      <input type="submit" id="gci_btn" name="gci_btn" class="button-primary" style="margin: 1em 0 1em 1em" value="Install"/><br/>
      <input type="checkbox" id="abv_root" name="abv_root" value="1"/> <label for="abv_root"><?php _e('Create Cache Directory Above Document Root', 'gator-cache');?>
      <p id="block_inroot" style="display:none">
        <span style="display:block;margin 1em 0;color:firebrick;font-weight:600"><?php _e('Gator Cache could not create your cache directory, please manually create the directory shown in the path above. If your hosting is set up to only allow document root access, check the box above to create the cache directory in your document root.', 'gator-cache');?></span></br>
      </p>
      <p><i class="fa fa-info-circle"></i> <?php _e('Gator Cache will attempt to install your cache directory in your document root, if it does not already exist.', 'gator-cache');?></p>
    </div>
  </form>
  <script type="text/javascript">
(function($){
    $('#gci_install').submit(function(e){
        e.preventDefault();
        $('#gci_result').html('<img src="<?php echo($loading = site_url(version_compare(get_bloginfo('version'), '3.9', '>=') ? '/wp-includes/js/tinymce/skins/lightgray/img/loader.gif' : '/wp-includes/js/tinymce/themes/advanced/skins/default/img/progress.gif'));?>"/>').show();
        var form = $(this).serializeArray();
        form.push({'name':'action','value':'gcinstall'});
        if(!$('#abv_root').prop('checked')){
            form.push({'name':'ndoc_root','value':'1'});
        }
        $('#gci_btn').attr('disabled', true);
        $.post(ajaxurl, form, function(data){
            if(null === data || 'undefined' === typeof(data.success)){
                $('#gci_btn').attr('disabled', false);
                $('#gci_result').html('<?php _e('Unspecified Data Error', 'gator-cache');?>');
                return;
            }
            if('1' === data.success){
                $('#gci_result').html('<?php _e('Gator Cache Successfully Installed, Refreshing', 'gator-cache');?> <img style="vertical-align:middle" src="<?php echo $loading;?>"/>');
                window.setTimeout(function(){
                    window.location.replace("<?php echo admin_url('admin.php?page=gtr_cache');?>");
                }, 1000);
                return;
            }
            $('#gci_btn').attr('disabled', false);
            $('#gci_result').html('undefined' === typeof(data.error) ? '<?php _e('Error Saving Settings', 'gator-cache');?>' : data.error);
            if('undefined' !== typeof(data.code) && ('100' === data.code || '101' === data.code)){
                $('#block_inroot').show();
            }
        },'json').fail(function(xhr, textStatus, errorThrown){
            $('#gci_result').html('<?php _e('Error: Unspecified network error', 'gator-cache');?>.');
            $('#gci_btn').attr('disabled', false);
        });
        return false;
    });
})(jQuery);
  </script>
  <h2><?php _e('Troubleshooting Code Reference Guide', 'gator-cache');?></h3>
  <p><strong><?php _e('Tech Support', 'gator-cache');?></strong>: <strong><a href="<?php echo WpGatorCache::SUPPORT_LINK;?>" target="_blank"><?php echo WpGatorCache::SUPPORT_LINK;?></a></strong></p>
  <p><span style="background:gold"><strong>100</strong> <em><?php _e('Cache Directory could not be created', 'gator-cache');?></em></span> - <?php printf('Manually create the cache directory, <strong>%s</strong>. Change the ownership to <strong>%s</strong>. If this is not possible with your hosting, the permissions can be set to "0777" with your ftp client or file manager.', $cacheDir, $webUser = self::getWebUser());?></p>
  <p><span style="background:gold"><strong>101</strong> <em><?php _e('Cache Directory is not writable', 'gator-cache');?></em></span>  - <?php _e('Change the ownership or permissions as mentioned in Error Code 100.', 'gator-cache');?></p>
  <p><span style="background:gold"><strong>102</strong> <em><?php _e('The Gator Cache config file is not writable', 'gator-cache');?></em></span>  - <?php printf('Change the ownership of <strong>%s</strong> to <strong>%s</strong>. If this is not possible with your hosting, the file permissions should be set to "0777".', ABSPATH . 'gc-config.ini.php', $webUser);?></p>
  <p><span style="background:gold"><strong>103</strong> <em><?php _e('WordPress cache file could not be copied', 'gator-cache');?></em></span>  - <?php printf('%s <strong>%s</strong> %s: <strong>%s</strong>.', __('Manually copy ', 'gator-cache'), self::$path . 'lib/advanced-cache.php', __('to the following directory', 'gator-cache'), WP_CONTENT_DIR . DIRECTORY_SEPARATOR);?><br/>
    <code style="white-space:pre-line;padding-left:0"><?php echo "# cp " . self::$path . 'lib' . DIRECTORY_SEPARATOR . 'advanced-cache.php ' . WP_CONTENT_DIR . DIRECTORY_SEPARATOR;?></code>
  </p>
  <p><span style="background:gold"><strong>104</strong> <em><?php _e('Could not write to your WordPress config file', 'gator-cache');?></em></span>  - <?php printf('%s <strong>wp-config.php</strong> %s:', __('Manually add this line to your ', 'gator-cache'), __('file', 'gator-cache'));?><br/><code>define('WP_CACHE', true);</code><br/><?php _e('Typically this is added after the WP_DEBUG line.', 'gator-cache');?></p>
  <p><span style="background:gold"><strong>106</strong> <em><?php _e('Could not copy config file to your WordPress directory', 'gator-cache');?></em></span>  - <?php _e('Manually copy the configuration file and change permissions:', 'gator-cache');?><br/>
    <code style="white-space:pre-line;padding-left:0"><?php echo "# cp " . self::$path . 'lib' . DIRECTORY_SEPARATOR . 'config.ini.php ' . ($configPath = self::getConfigPath()) . "\n# chown -R " . $webUser . ':' . self::getWebUser(false) . ' ' . $configPath;?></code>
  </p>
  <p><span style="background:gold"><strong>112</strong> <em><?php _e('Could not create multisite config file to your WordPress directory', 'gator-cache');?></em></span>  - <?php _e('Manually create the multisite configuration file and change permissions:', 'gator-cache');?><br/>
    <code style="white-space:pre-line;padding-left:0"><?php echo "# touch " .  ($configPath = ABSPATH . 'gc-blogs.ini.php') . "\n# chown -R " . $webUser . ':' . self::getWebUser(false) . ' ' . $configPath;?></code>
  </p>
  <p><?php _e('Tech Support Information:', 'gator-cache');?>
  <p><?php echo self::getSupportInfo();?></p>
</div>
