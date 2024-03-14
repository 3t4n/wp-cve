<?php if(!defined('ABSPATH') || !is_admin()){//no direct or frontend access
    exit;
}
$loading = site_url(version_compare(get_bloginfo('version'), '3.9', '>=') ? '/wp-includes/js/tinymce/skins/lightgray/img/loader.gif' : '/wp-includes/js/tinymce/themes/advanced/skins/default/img/progress.gif');
if('' === get_option('permalink_structure')){
?>
<div class="updated">
  <p><?php _e('Warning: Gator Cache will not Cache Pages without a permalink structure.', 'gator-cache');?>
    <a href="<?php echo self::isMultiSite() ? network_admin_url('options-permalink.php') : admin_url('options-permalink.php');?>" class="button-secondary">Repair</a>
  </p>
</div>
<?php }
$postTypes = get_post_types(array('public'   => true, '_builtin' => false), 'objects');
if($isBbPress = is_plugin_active('bbpress/bbpress.php')){//it's bbpress Jim
    unset($postTypes[bbp_get_reply_post_type()]);//reply won't have a permalink
    unset($postTypes[bbp_get_topic_post_type()]);
    unset($postTypes[bbp_get_forum_post_type()]);
    $postTypes['bbpress'] = new StdClass();//virtual post type
    $postTypes['bbpress']->name = 'bbpress';
    $postTypes['bbpress']->label = 'bbPress';
}
if(defined('WOOCOMMERCE_VERSION')){//woocommerce
    unset($postTypes['product_variation'], $postTypes['shop_coupon']);
}
if(isset($postTypes['wooframework'])){//woothemes
    unset($postTypes['wooframework']);
}
?>
<div class="wrap" id="gc_settings">
  <h2>Gator Cache <?php _e('Settings', 'gator-cache');?></h2>
  <div id="gc_load">
    <img src="<?php echo($loading);?>"/>
    <p>Loading Gator Cache Settings</p>
  </div>
<div id="gci_tabs">
<ul>
<li><a href="#tabs-1"><?php _e('General Settings', 'gator-cache');?></a></li>
<li><a href="#tabs-2"><?php _e('Post Types', 'gator-cache');?></a></li>
<li><a href="#tabs-4"><?php _e('Users', 'gator-cache');?></a></li>
<li><a href="#tabs-7"><?php _e('Custom', 'gator-cache');?></a></li>
<li><a href="#tabs-6"><?php _e('Cache Rules', 'gator-cache');?></a></li>
<li><a href="#tab-debug"><?php _e('Debug', 'gator-cache');?></a></li>
<?php if($showHttp = (!self::isMultiSite() || self::isMainSite())){//not necessary for multisite sub sites?>
<li><a href="#tabs-5"><?php _e('Http', 'gator-cache');?></a></li>
<?php }?>
</ul>
<div id="tabs-7">
  <form id="gci_dir" method="post" action="" autocomplete="off">
    <p class="bmpTxt"><?php _e('Custom Rules for cache exclusion:', 'gator-cache');?></p>
    <p class="result"></p>
    <p>
      <label for="ex_dir"><?php _e('Enter directory or page name to exclude:', 'gator-cache');?></label><br/>
      <input style="margin-bottom:8px" type="text" name="ex_dir" id="ex_dir" value=""/>
      <button class="button-primary"><?php _e('Save', 'gator-cache');?></button><br/>
       <i class="fa fa-question-circle"></i> <?php printf(__('eg: excluding %s will exclude any url that contains %s', 'gator-cache'), '"dynamic-stuff"', '"/dynamic-stuff/"');?>  
    </p>
    <p class="bmpTxt"><?php _e('Excluded Directories and Page paths:', 'gator-cache');?></p>
    <p id="no_ex_dirs"<?php if(empty($options['exclude_paths'])){echo ' style="display:block"';}?>><?php _e('You have no excluded paths', 'gator-cache')?></p>
<ol id="ex_cust" name="ex_cust">
    <?php foreach($options['exclude_paths'] as $dir){?>
      <li data-path="<?php echo $dir?>" class="ui-widget-content"><?php echo $dir?> <i class="fa fa-times-circle"></i></li>
    <?php }?>
</ol>
  </form>
<p><i class="fa fa-info-circle"></i> <?php _e('If you are running a plugin that is not cache friendly or there are certain directories or dynamic pages that should never be cached you may add those here and they will be excluded from your cache.', 'gator-cache');?></p>
<?php if(defined('WOOCOMMERCE_VERSION')){?>
  <p><i class="fa fa-info-circle"></i> <?php printf(__('You do not need to exclude %s cart, checkout or account paths. WooCommerce has built-in support for WordPress page caching.', 'gator-cache'), '<em><strong>WooCommerce</strong></em>');?></p>
<?php }?>
</div>
<div id="tabs-6">
  <form id="gci_ref" method="post" action="" autocomplete="off">
    <p class="bmpTxt"><i class="fa fa-refresh"></i> <?php _e('Automatic refresh rules for posts, pages and selected custom post types:', 'gator-cache');?></p>
    <p>
      <input type="checkbox" name="rf_home" id="rf_home" value="1"<?php if($options['refresh']['home']){echo ' checked="checked"';}?>/> 
      <label for="rf_home"><?php _e('Refresh cached home page when posts are updated or newly published.', 'gator-cache');?></label> 
    </p>
    <p>
      <input type="checkbox" name="rf_archive" id="rf_archive" value="1"<?php if($options['refresh']['archive']){echo ' checked="checked"';}?>/> 
      <label for="rf_archive"><?php _e('Refresh archive pages, such as post category, when new posts in that category are published or updated.', 'gator-cache');?></label> 
    </p>
<?php if(self::hasRecentWidgets()){?>
    <p>
      <input type="checkbox" name="rf_all" id="rf_all" value="1"<?php if($options['refresh']['all']){echo ' checked="checked"';}?>/> 
      <label for="rf_all"><?php _e('Refresh all pages. This is only necessary if your recent posts or custom posts, such as products, widget is on all pages.', 'gator-cache');?></label> 
    </p>
<?php }?>
    <p class="bmpTxt"><i class="fa fa-lock"></i> <?php _e('SSL Settings:', 'gator-cache');?></p>
    <p>
      <input type="checkbox" name="cache_ssl" id="cache_ssl" value="1"<?php if(!$options['skip_ssl']){echo ' checked="checked"';}?>/> 
      <label for="cache_ssl"><?php _e('Cache secure SSL https protocol pages', 'gator-cache');?></label>
    </p>
    <p class="bmpTxt"><i class="fa fa-rss"></i> <?php _e('Feeds:', 'gator-cache');?></p>
    <p>
      <input type="checkbox" name="cache_feeds" id="cache_feeds" value="1"<?php if(!$options['skip_feeds']){echo ' checked="checked"';}?>/> 
      <label for="cache_feeds"><?php echo sprintf('%s <i class="fa fa-info-circle"></i>%s', __('Cache RSS feeds', 'gator-cache'), __('Will be refreshed upon content or comment update', 'gator-cache'));?></label> 
    </p>
    <p class="bmpTxt"><i class="fa fa-download"></i> <?php _e('Cache Warming:', 'gator-cache');?></p>
    <p>
      <input type="checkbox" name="cache_warm" id="cache_warm" value="1"<?php if($options['cache_warm']){echo ' checked="checked"';}?>/> 
      <label for="cache_warm"><?php _e('Regenerate cached content that is automatically refreshed', 'gator-cache');?></label>
    </p>
<?php if(self::isJetPackMobile()){?>
    <p class="bmpTxt"><i class="fa fa-fighter-jet"></i> <?php _e('JetPack Mobile:', 'gator-cache');?></p>
    <p>
      <input type="checkbox" name="jp_mobile_cache" id="jp_mobile_cache" value="1"<?php if($options['jp_mobile_cache']){echo ' checked="checked"';}?>/> 
      <label for="jp_mobile_cache"><?php _e('Cache JetPack Mobile Pages', 'gator-cache');?></label>
    </p>
<?php }?>
    <p class="bmpTxt"><i class="fa fa-mobile"></i> <?php _e('Mobile Device Caches:', 'gator-cache');?></p>
    <p>
      <input type="checkbox" class="mobile_opts" name="mobile_cache[mobile]" id="mobile_cache_mobile" value="1"<?php if($options['mobile']['mobile']){echo ' checked="checked"';}?>/> 
      <label for="mobile_cache_mobile"><?php _e('All Mobile (will not distinguish phone or tablet)', 'gator-cache');?></label>
    </p>
    <p>
      <input type="checkbox" class="mobile_opts" name="mobile_cache[phone]" id="mobile_cache_phone" value="1"<?php if($options['mobile']['phone']){echo ' checked="checked"';}?>/> 
      <label for="mobile_cache_phone"><?php _e('Separate Phone Cache', 'gator-cache');?></label>
    </p>
    <p>
      <input type="checkbox" class="mobile_opts" name="mobile_cache[tablet]" id="mobile_cache_tablet" value="1"<?php if($options['mobile']['tablet']){echo ' checked="checked"';}?>/> 
      <label for="mobile_cache_tablet"><?php _e('Separate Tablet Cache', 'gator-cache');?></label>
    </p>
    <p>
      <input type="checkbox" class="mobile_opts" name="mobile_cache[ios]" id="mobile_cache_ios" value="1"<?php if($options['mobile']['ios']){echo ' checked="checked"';}?>/> 
      <label for="mobile_cache_ios"><?php _e('Separate iOS Cache', 'gator-cache');?></label>
    </p>
    <p>
      <input type="checkbox" class="mobile_opts" name="mobile_cache[android]" id="mobile_cache_android" value="1"<?php if($options['mobile']['android']){echo ' checked="checked"';}?>/> 
      <label for="mobile_cache_android"><?php _e('Separate Android Cache', 'gator-cache');?></label>
    </p>
    <p style="margin-bottom:0"><i class="fa fa-info-circle"></i> <?php _e('This will maintain separate caches for mobile devices if you are using WP Mobile Detect or the PHP Mobile Detect library to produce dynamic content.', 'gator-cache');?></p>
    <p style="margin-top:6px"><i class="fa fa-lightbulb-o"></i> <?php _e('Note that for http or htaccess caching, you would need to write your own htaccess rules to determine mobile devices and match to appropriate cache directory.', 'gator-cache');?></p>
    <p class="bmpTxt"><i class="fa fa-exchange"></i> <?php _e('Advanced Settings:', 'gator-cache');?></p>
    <p>
<?php if (function_exists('sys_getloadavg')) {
      _e('Do not expire cache when system load is at this threshold:', 'gator-cache');?> <input type="text" style="width:100px" name="sys_load" value="<?php echo empty($options['sys_load']) ? '' : $options['sys_load'];?>"/> <i class="fa fa-info-circle"></i> calls sys_getloadavg() <i class="fa fa-wifi"></i> <?php $load = sys_getloadavg(); printf(__('Current load: %d', 'gator-cache'), $load[0]);?><br/>
<?php }?>
      <input type="checkbox" name="enable_hooks" id="enable_hooks" value="1"<?php if($options['enable_hooks']){echo ' checked="checked"';}?>/> 
      <label for="enable_hooks"><?php _e('Enabled Hooks. This is only neccessary if you are using custom code that interacts with GatorCache hooks.', 'gator-cache');?></label>
    </p>
    <p style="margin-top:1.5em">
      <button class="button-primary"><?php _e('Update', 'gator-cache');?></button>
      <span class="result"></p>
    </p>
  </form>
<hr/>
<form id="gci_crf" method="post" action="" autocomplete="off">
    <p class="bmpTxt"><?php _e('Custom Refresh Rules:', 'gator-cache');?></p>
    <p class="result"></p>
    <p>
      <label for="rf_dir"><?php _e('Enter directory or page name to refresh on updates:', 'gator-cache');?></label><br/>
      <input style="margin-bottom:8px" type="text" name="rf_dir" id="rf_dir" value=""/>
<?php 
//var_dump($postTypes);
if(!empty($postTypes)){?>
      <select name="rf_type">
        <option value="all"><?php _e('All Post Types', 'gator-cache');?></option>
<?php foreach($postTypes as $type){?>
        <option value="<?php echo $type->name;?>"><?php echo $type->label;?></option>
<?php }?>
      </select>
<?php } else{?>
      <input type="hidden" name="rf_type" value="all"/>
<?php }?>
      <button class="button-primary"><?php _e('Save', 'gator-cache');?></button><br/>
       <i class="fa fa-question-circle"></i> <?php printf(__('eg: adding %s will refresh the post or archive at the url %s when content is published or updated', 'gator-cache'), '"example/stuff"', '"/example/stuff/"');?>  
    </p>
    <p class="bmpTxt"><?php _e('Custom Refresh Directories and Page paths:', 'gator-cache');?></p>
    <p id="no_rf_dirs"<?php if(empty($options['refresh_paths']['all']) && 1 === count($options['refresh_paths'])){echo ' style="display:block"';}?>><?php _e('You have no custom refresh paths', 'gator-cache')?></p>
<ol id="rf_cust" name="rf_cust">
    <?php foreach($options['refresh_paths'] as $type => $dirs){
        foreach($dirs as $dir){?>
      <li data-path="<?php echo $dir?>" class="ui-widget-content"><?php echo($dir . ' (' . $type . ')');?> <i class="fa fa-times-circle"></i></li>
    <?php }}?>
</ol>
  </form>
<?php if(is_plugin_active('wordpress-https/wordpress-https.php')){?>
  <p><i class="fa fa-info-circle"></i> <?php printf(__('When SSL is enabled, Gator Cache is compatible with %s and will cache the pages it secures.', 'gator-cache'), '<em><strong>WordPress HTTPS</strong></em>');?></p>
<?php }?>
</div>
<?php if($showHttp){?>
<div id="tabs-5">
  <form id="gci_http" method="post" action="" autocomplete="off">
    <p><?php _e('Recommended webserver rules for http caching.', 'gator-cache');?></p>
    <p class="bmpTxt"><i class="fa fa-server"></i> <?php _e('Apache Rules:', 'gator-cache');?></p>
<?php $warnLink = __('see below', 'gator-cache');
if(!strstr($cacheDir = $config->get('cache_dir'), ABSPATH)){//cache dir is parallel to doc root, recommended?>
<p><i class="fa fa-info-circle"></i> <?php _e('Add these rules to your apache virtual hosts config file.', 'gator-cache');?></p>
<p><i class="fa fa-exclamation-triangle"></i> <?php _e('These rules will not work in your htaccess file.', 'gator-cache');?></p>
<p><i class="fa fa-question-circle"></i> <?php printf(__('If you do not have access to your apache config, you can move your cache directory to support HTTP caching (%s).', 'gator-cache'), '<a href="#moveCache">' . $warnLink . '</a>');?></p>
<textarea rows="<?php echo($options['skip_ssl'] ? '45' : '67');?>">
# Important - Alias the cache directory (outside of virtual host directory block)
<?php echo 'Alias /gator_cache/ "' . $cacheDir . '/"' . "\n\n";
include self::$path . 'tpl' . DIRECTORY_SEPARATOR . 'http-rules.php';?>
</textarea>
    <p class="result"></p>
    <p><?php _e('Move your cache to the website document root to support HTTP caching with htaccess:', 'gator-cache');?> <button id="cache_move" class="button-primary"><i class="fa fa-share"></i> Move Cache</button></p>
    <a name="moveCache"></a>
<?php }
else{?>
<p><i class="fa fa-info-circle"></i> <?php _e('Copy this block to the very top of your .htaccess above the WordPress rules. Remove any other caching plugin rules.', 'gator-cache');?></p>
<textarea rows="<?php echo($options['skip_ssl'] ? '42' : '64');?>"><?php include self::$path . 'tpl' . DIRECTORY_SEPARATOR . 'http-rules.php';?></textarea>
<?php }?>
  </form>
</div>
<?php }//endif show http?>
<div id="tabs-4">
  <form id="gci_usr" method="post" action="" autocomplete="off">
    <p class="result"></p>
    <p><?php _e('By default, cached pages are not served to logged-in WordPress Users.', 'gator-cache');?></p>
    <p><label for="gci_roles"><?php _e('Cache Pages for the following WordPress User Roles:', 'gator-cache');?></label></p>
    <p>
      <select id="gci_roles" style="width:350px;height:24px" data-placeholder="<?php _e('Select User Roles', 'gator-cache');?>" multiple class="chosen">
<?php
    global $wp_roles;
    if(!isset($wp_roles)){
        $wp_roles = new WP_Roles();
    }
    $out = '';
    foreach($wp_roles->get_names() as $role => $name){
        $out .= '<option value="' . $role . '"';
        if(in_array($role, $options['roles'])){
            $out .= ' selected="selected"';
        }
        $out .= '>' . $name . "</option>\n";
    }
    echo $out;
?>
      </select>
    </p>
    <p>
      <input type="checkbox" name="gci_only_user" id="gci_only_user" value="1"<?php if($options['only_user']){echo ' checked="checked"';}?>/> 
      <label for="gci_only_user"><?php _e('Only serve cache to logged in users', 'gator-cache');?></label>
    </p>
    <p><button class="button-primary"><?php _e('Update', 'gator-cache');?></button></p>
    <p><i class="fa fa-lightbulb-o"></i> <?php _e('Unless your theme displays user specific content via ajax, caching logged-in user content is usually not a good idea.', 'gator-cache');?></p>
  </form>
</div>
<div id="tabs-1">
<form id="gci_gen" method="post" action="" autocomplete="off">
  <p class="result"></p>
  <p>
    <input type="checkbox" name="enabled" id="enabled" value="1"<?php if($options['enabled']){echo ' checked="checked"';}?>/> 
    <label for="enabled"><?php _e('Enable Page Cache', 'gator-cache');?></label> 
  </p>
  <p><?php _e('Posts, Pages and selected Custom Post Types will be automatically refreshed when they are updated.', 'gator-cache');?></p>
  <p>
    <label for="gci_on"><?php _e('Cache Lifetime', 'gator-cache');?>*</label>
    <select id="lifetime_val" name="lifetime_val">
      <option value="0"><?php _e('Infinite', 'gator-cache');?></option>
      <?php echo self::rangeSelect(1, 12, $options['lifetime']['value']);?>
    </select>
    <select id="lifetime_unit" name="lifetime_unit">
      <option value="hr"><?php _e('Hours', 'gator-cache');?></option>
      <option value="day"<?php if('day' === $options['lifetime']['unit']){echo ' selected="selected"';}?>><?php _e('Days', 'gator-cache');?></option>
      <option value="week"<?php if('week' === $options['lifetime']['unit']){echo ' selected="selected"';}?>><?php _e('Weeks', 'gator-cache');?></option>
      <option value="month"<?php if('month' === $options['lifetime']['unit']){echo ' selected="selected"';}?>><?php _e('Months', 'gator-cache');?></option>
    </select>
  </p>
  <p><button class="button-primary"><?php _e('Update', 'gator-cache');?></button></p>
  <p>*<?php printf(__('Since pages are automatically refreshed a relatively high or %s lifetime can be set.', 'gator-cache'), '<em>' . __('Infinite', 'gator-cache') . '</em>');?></p>
  <p><i class="fa fa-info-circle"></i> <?php _e('When new posts are published, your cached archive or category pages will be automatically refreshed.', 'gator-cache');?></p>
<!--  <p>
    <input type="checkbox" name="oc_enabled" id="oc_enabled" value="1"<?php if($options['oc_enabled']){echo ' checked="checked"';}?>/> 
    <label for="oc_enabled"><?php _e('Enable Object Cache (Beta)', 'gator-cache');?></label> 
  </p>
  <p><i class="fa fa-flask"></i> <?php _e('New Object Cache available for field testing', 'gator-cache');?></p>-->
  <p><i class="fa fa-info-circle"></i> <?php _e('Unlike other object caches, saves a reference to the object so that state is maintained.', 'gator-cache');?></p>
  <p><i class="fa fa-cog"></i> <em><strong>Gator Cache</strong></em> version <?php echo $options['version'];?></p>
</form>
</div>
<div id="tabs-2">
<form id="gci_cpt" method="post" action="" autocomplete="off">
  <p class="result"></p>
  <p>
    <?php _e('By Default Gator Cache will cache your Posts and Pages', 'gator-cache');?>.
  </p>
<?php
if(empty($postTypes)){?>
  <p><?php _e('Additional post types were not found.', 'gator-cache');?></p>
<?php } else{//there are post types?>
  <p><?php _e('Select any additional Post Types to cache', 'gator-cache');?>:</p>
  <p>
    <select id="post_types" name="post_types" style="width:350px;height:24px" data-placeholder="<?php _e('Select Post Types', 'gator-cache');?>" multiple class="chosen">
<?php $out = '';
    foreach($postTypes as $post_type){
        //var_dump($post_type);
    $out .= '<option value="' . $post_type->name . '"';
        if(in_array($post_type->name, $options['post_types'])){
            $out .= ' selected="selected"';
        }
        $out .= '>' . $post_type->label . "</option>\n";
    }
    echo $out;
?>
    </select>
  </p>
  <p><button class="button-primary"><?php _e('Update', 'gator-cache');?></button></p>
<?php }?>
  <p>*<?php _e('In addition to your regular WordPress posts and pages, you may cache other post types as well, eg WooCommerce Products.', 'gator-cache');?></p>
<?php if($isBbPress){?>
  <p><i class="fa fa-info-circle"></i> <?php printf(__('Selecting %s will cache your Forums and Topics. They will always be fresh, since Gator Cache automatically refreshes when topics are added or replies are posted.', 'gator-cache'), '<em><strong>bbPress</strong></em>');?></p>
<?php }?>
</form>
</div>
<div id="tab-debug">
<form id="gci_dbg" method="post" action="" autocomplete="off">
  <p class="result"></p>
  <p>
    <?php _e('Include the cached date in your html source (does not show on web pages)', 'gator-cache');?>.
  </p>
  <p><input type="checkbox" name="debug" id="debug" value="1"<?php if($options['debug']){echo ' checked="checked"';}?>/> <label for="debug"><?php _e('Add debug information', 'gator-cache');?></label>
  <p><button class="button-primary"><?php _e('Update', 'gator-cache');?></button></p>
</form>
<form id="gci_del" method="post" action="" autocomplete="off">
  <p class="result"></p>
  <p><i class="fa fa-bomb"></i> <?php _e('Purge the entire cache. All cache files will be deleted!', 'gator-cache');?> <button class="button-secondary purge"><i class="fa fa-refresh"></i> <?php _e('Purge', 'gator-cache');?></button></p>
</form>
<!-- <form id="gci_del_oc" method="post" action="" autocomplete="off">
  <p class="result"></p>
  <p><i class="fa fa-bomb"></i> <?php _e('Flush the object cache.', 'gator-cache');?> <button class="button-secondary purge"><i class="fa fa-refresh"></i> <?php _e('Flush', 'gator-cache');?></button></p>
</form> -->
<p><?php _e('Tech Support Forum:', 'gator-cache');?> <a href="<?php echo WpGatorCache::SUPPORT_LINK;?>" target="_blank"><?php echo WpGatorCache::SUPPORT_LINK;?></a></p>
<p><?php _e('Tech Support Information:', 'gator-cache');?>
<p><?php echo self::getSupportInfo();?></p>
</div>
</div>
<script type="text/javascript">
(function($){
    $('#gci_gen,#gci_usr,#gci_cpt,#gci_dbg,#gci_del,#gci_del_oc,#gci_ref,#gci_dir,#gci_crf').submit(function(e){
        e.preventDefault();
        var res = $(this).find('.result'); 
        res.html('<img src="<?php echo $loading;?>"/>').show();
        var sel = $(this).find($('select.chosen'));
        if(1 === sel.length){
            var form = [{'name':sel.attr('id'),'value': sel.val()}];
            if ('gci_roles' === form[0].name) {
                form.push({'name':'only_user','value':$('#gci_only_user').prop('checked') ? 1 : 0});
            }
        }
        else{
            var form = $(this).serializeArray();
        }
        var action = $(this).attr('id');
        form.push({'name':'action','value':action});
        var btn = $(this).find('button');
        btn.attr('disabled', true);
        $.post(ajaxurl, form, function(data){
            btn.attr('disabled', false);
            if(null === data || 'undefined' === typeof(data.success)){
                res.html('<?php _e('Unspecified Data Error', 'gator-cache');?>');
                return;
            }
            if('1' === data.success){
                res.html('undefined' !== typeof(data.msg) ? data.msg : '<?php _e('Success: Your settings have been saved', 'gator-cache');?>');
                if('undefined' !== typeof(data.xdir)){
                    var context = 'gci_dir' === action ? 'ex' : 'rf';
                    $('#no_' + context + '_dirs').hide();
                    $('#' + context + '_dir').val('');
                    var extra = ('undefined' !== typeof(data.xtype) && 'all' !== data.xtype) ? ' (' + data.xtype + ')' : '';
                    $('#' + context + '_cust').append('<li data-path="' + data.xdir + '" class="ui-widget-content">' + data.xdir + extra + '<i class="fa fa-times-circle"></i></li>');
                }
                return;
            }
            res.html('undefined' === typeof(data.error) ? '<?php _e('Error Saving Settings', 'gator-cache');?>' : data.error);
        },'json').fail(function(xhr, textStatus, errorThrown){
            res.html('<?php _e('Error: Unspecified network error.', 'gator-cache');?>');
            btn.attr('disabled', false);
        });
        return false;
    });
    $('#gci_http').submit(function(e){
        e.preventDefault();
        return false;
    });
    $('#cache_move').click(function(e){
        var rusure = confirm("<?php _e('Are you sure you want to move your cache?', 'gator-cache');?>");
        if(!rusure){
            return false;
        }
        var res = $(this).parents('form').find('.result');
        var btn = $(this);
        btn.attr('disabled', true);
        res.html('<img src="<?php echo $loading;?>"/>').show();
        $.post(ajaxurl, {'action':'gci_mcd'}, function(data){
            btn.attr('disabled', false);
            if(null === data || 'undefined' === typeof(data.success)){
                res.html('<?php _e('Unspecified Data Error', 'gator-cache');?>');
                return;
            }
            if('1' === data.success){
                res.html('undefined' !== typeof(data.msg) ? data.msg : '<?php _e('Cache successfully moved to document root, refreshing', 'gator-cache');?>');
                window.setTimeout(function(){
                        window.location.replace("<?php echo admin_url('admin.php?page=gtr_cache');?>");
                    }, 1000);
                return;
            }
            res.html('undefined' === typeof(data.error) ? '<?php _e('Error moving cache path', 'gator-cache');?>' : data.error);
        }, 'json').fail(function(xhr, textStatus, errorThrown){
            res.html('<?php _e('Error: Unspecified network error.', 'gator-cache');?>');
            btn.attr('disabled', false);
        });
        return false;
    });
    $("#ex_cust,#rf_cust").delegate("i.fa-times-circle", "click", function(e){
        var form = $(this).parents('form');
        var ol = $(this).parents('ol');
        var li = $(this).parents('li');
        var res = form.find('.result');
        var btn = form.find('button');
        var context = 'ex_cust' === ol.attr('id') ? 'ex' : 'rf';
        btn.attr('disabled', true);
        res.html('<img src="<?php echo $loading;?>"/>').show();
        var formData = {'action': 'gci_x' + context};
        formData[context + '_dir'] = li.data('path');
        $.post(ajaxurl, formData, function(data){
            btn.attr('disabled', false);
            if(null === data || 'undefined' === typeof(data.success)){
                res.html('<?php _e('Unspecified Data Error', 'gator-cache');?>');
                return;
            }
            if('1' === data.success){
                res.html('undefined' !== typeof(data.msg) ? data.msg : '<?php _e('The selected path has been removed', 'gator-cache');?>');
                li.remove();
                if(0 === ol.find('li').length){
                    $('#no_' + context + '_dirs').show();
                }
                return;
            }
            res.html('undefined' === typeof(data.error) ? '<?php _e('Error deleting exclusion path', 'gator-cache');?>' : data.error);
        }, 'json').fail(function(xhr, textStatus, errorThrown){
            res.html('<?php _e('Error: Unspecified network error.', 'gator-cache');?>');
            btn.attr('disabled', false);
        });
    });
    $('.mobile_opts').click(function(e){
        var opts = $('.mobile_opts');
        var i = opts.index(this);
        if (0 == i) {
            if ($(this).prop('checked')) {
                opts.eq(1).prop('checked', false);
                opts.eq(2).prop('checked', false);
            }
            else if(!opts.eq(1).prop('checked') && !opts.eq(2).prop('checked')) {
                for (xx = 3; xx < 5; xx++) {
                    opts.eq(xx).prop('checked', false);
                }
            }
        }
        else if (1 == i || 2 == i) {
            if ($(this).prop('checked')) {
                opts.eq(0).prop('checked', false);
            }
            else if(!opts.eq(0).prop('checked') && !opts.eq(2).prop('checked')) {
                for (xx = 3; xx < 5; xx++) {
                    opts.eq(xx).prop('checked', false);
                }
            }
        }
        else if (!opts.eq(0).prop('checked') && !opts.eq(1).prop('checked') && !opts.eq(2).prop('checked')) {
            opts.eq(0).prop('checked', true);
        }
    });
})(jQuery);
  </script>
</div>
