<?php
  /**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       getlevelten.com/blog/tom
 * @since      1.0.0
 *
 * @package    Intl
 * @subpackage Intl/public/partials
 */
return;
?>
<!-- intel_settings -->
<script>
  var intel_settings = <?php print json_encode(intel()->get_js_settings()) . ';'; ?>
</script>
<!-- end intel_settings ->
<!-- Intelligence tracking code -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
</script>
<script>
  (function(w,d,o,u,b,i,r,a,s,c,t){w['L10iObject']=r;w[r]=w[r]||function(){
      (w[r].q=w[r].q||[]).push(arguments)},t=1*new Date();s='';a='l10i_bt=';d.cookie=a+t+';path=/';c=d.cookie;if(c&&c.indexOf(a)!=-1){u+=i;if(c.indexOf('l10i_s=')==-1){s='?t='+t}}u+=b+s;a=d.createElement(o),b=d.getElementsByTagName(o)[0];a.async=1;a.src=u;b.parentNode.insertBefore(a,b)
  })(window,document,'script','//<?php echo $l10iapi_url; ?>','/js/<?php echo $l10iapi_js_file; ?>','/p/<?php echo esc_html($ga_tid); ?>','io');
  io("ga.create","<?php echo esc_html($ga_tid); ?>","auto",{"name":"l10i","userId":"."});
  io("set", "config", intel_settings.intel.config);
  io("set", intel_settings.intel.pushes.set);
  if (intel_settings.intel.pushes.event) {
    io("event", intel_settings.intel.pushes.event);
  }
</script>
<!-- end Intelligence tracking code -->


$l10iapi_url = intel_get_iapi_url();
$l10iapi_js_ver = get_option('intel_l10iapi_js_ver', INTEL_L10IAPI_JS_VER);
