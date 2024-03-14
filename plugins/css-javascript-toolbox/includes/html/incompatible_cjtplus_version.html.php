<?php
/**
*
*/

defined('ABSPATH') or die(-1);

add_action( 'admin_footer', 'script' );

function script() { ?>
<script src="<?php echo plugin_dir_url( CJTOOLBOX_PLUGIN_FILE ) . 'framework/js/ajax/cjt-server/cjt-server.js'; ?>"></script>
<script src="<?php echo plugin_dir_url( CJTOOLBOX_PLUGIN_FILE ) . 'framework/js/ajax/cjt-server-queue/cjt-server-queue.js'; ?>"></script>

<input id="cjt-securityToken" type="hidden" value="<?php echo cssJSToolbox::getSecurityToken(); ?>">

<script type="text/javascript">
    jQuery('a.license-key-alt').click(jQuery.proxy(function() {
        var _req = {
            view: 'setup/activation-form',
            component: {
                pluginBase: "css-javascript-toolbox-plus/css-javascript-toolbox-plus.php",
                title: "CJT PLUS"
            },
            TB_iframe: true,
            height: 380
        }

        var _url = CJTServer.getRequestURL('setup', 'activationFormView', _req);

        tb_show('CJT Extension License Activation Form', _url)

    }))

</script>
<?php }
?>

<div style="font-size:14px;font-weight:bold;">
    <h3 style="color: #0073aa;margin-top: 5px;font-size: 1.2em">WARNING: CJT Free and CJT PLUS are <span style="text-decoration: underline">NOT COMPATIBLE</span></h3><span style="font-weight:100;font-size:13px;">Since version 11, both CJT Free (on WordPress.org) and CJT PLUS (premium extension) has had a major UI and framework update to include many new Hooks.  Clearing out your site or browser cache may resolve some issues, but not all.  That’s because you are using an older CJT PLUS (version 10, 9.4 or earlier) with the latest CJT Free version 11 or higher.  As a CJT PLUS owner, you have a number of options available as shown below. <a target="_blank" href="https://css-javascript-toolbox.com/welcome-to-cjt-plus-version-11">We also explain everything in this article on the CJT website</a>.</span>
    <ul style="list-style-type: circle;padding-left: 27px;font-size: 12px;margin-top:10px;">
        <li>You <span style="text-decoration: underline">MUST</span> update CJT PLUS to the latest version 11 or higher.</li>
        <li>If it won't let you update, you may need to either: <a class="license-key-alt" href="#">activate your valid license key</a>, or <a target="_blank" href="https://<?php echo cssJSToolbox::CJT_WEB_SITE_DOMAIN ?>/pricing">purchase a new license key</a> if it has expired.</li>
        <li>If you do not wish to update CJT PLUS to the latest version, then you will need to manually downgrade CJT Free to version 10: <a target="_blank" href="https://downloads.wordpress.org/plugin/css-javascript-toolbox.10.zip">Click to download</a>.</li>
    </ul>
</div>
