<style>
    p {
        font-size: 16px;
    }

    h2 {
        font-size: 18px;
    }
</style>
<?php $arr = array('i' => array(), 'a' => array('href' => array(), 'target' => array()));?>

<div class="config-bloc-aff">

    <?php if (is_plugin_active('better-wp-security/better-wp-security.php')) : ?>
    <div class="error ithemes">
        <?php esc_html_e("It seems you are using IThemes Security. Please allow \"PHP in extensions\" or deactivate this plugin for our plugin to work correctly.", 'lws-affiliation') ?>
    </div>
    <?php endif ?>

    <?php if (ini_get('allow_url_fopen')) : ?>
    <h2 class="lws_aff_titre" style="margin-top:0px"><?php esc_html_e('How to use LWS Affiliate?', 'lws-affiliation'); ?>
    </h2>
    <p class="lws_aff_text_p">
        <?php esc_html_e("You can use it from your article or page editor", 'lws-affiliation') ?>.
    </p>
    <ul class="lws_aff_text_p" style="list-style: disc inside; padding-left:30px">
        <li>
            <?php esc_html_e("If you are using the classic WordPress editor, just click on the \"LWS Affiliation\" button on your editor's menu.", 'lws-affiliation') ?><br>
        </li>
        <li>
            <?php esc_html_e("If you are using Gutenberg, the block-based editor, add a \"Classic Editor\" block to your page and click on the \"LWS Affiliation\" button on your editor's menu.", 'lws-affiliation') ?>
        </li>
    </ul>

    <p class="lws_aff_text_p">
        <?php esc_html_e("You can only add one type of each widget on your page. Any attempts to add more will result in an error or in only one of them appearing.", 'lws-affiliation') ?>
        <?php echo(wp_kses(__("For more information, visit our <a href='http://aide.lws.fr/base/Affiliation/Hebergeur-Web/Comment-installer-et-utiliser-le-plugin-Wordpress-Affiliation' target='_blank'>documentation</a>", 'lws-affiliation'), $arr))?>.<br />
    </p>

    <div id="banner_lws_aff"></div>

    <?php if ($has_api) : ?>
    <h2 class="lws_aff_titre lws_aff_titre_deconnexion"><?php esc_html_e('Disconnection', 'lws-affiliation'); ?>
    </h2>
    <form method="POST">
        <p class="lws_aff_text_p lws_aff_connexion_bloc" style="margin:0px">
            <?php esc_html_e("If you want to connect your site to another account, click on this button:", 'lws-affiliation') ?>
            <button class="button_disconnect-aff" type="submit" name="del_config">
                <img src="<?php echo esc_url(plugins_url('/images/deconnexion_blanc.svg', dirname(__DIR__)))?>"
                    alt="Logo Disconnect" width="15px" height="15px"
                    style="vertical-align: text-bottom; margin-right: 5px;"></img>
                <span style="line-height:23px;"><?php esc_html_e("Log out", 'lws-affiliation');?></span>
            </button>
        </p>
    </form>
    <?php else : ?>
    <h2 class="lws_aff_titre"><?php esc_html_e('Connection', 'lws-affiliation'); ?>
    </h2>
    <p class="lws_aff_text_p">
        <?php wp_kses(_e("Enter here your username and password LWS affiliate, this is the login information by which you reach the <a href='https://affiliation.lws-hosting.com' target='_blank'>affiliate panel LWS</a> LWS", 'lws-affiliation'), $arr) ?>.<br />
        <?php wp_kses(_e("If you do not have an affiliate LWS account, you can create one in a few minutes from the <a href='https://affiliation.lws-hosting.com/members/addmember' target='__blank' title='Sign LWS Affiliation'>registration page</a>", 'lws-affiliation'), $arr) ?>.
    </p>
    <form method="post">
        <div class="tagsdiv">
            <input type="text" name="username-aff-lws"
                placeholder="<?php esc_attr(_e('Your affiliate ID', 'lws-affiliation')) ?>"
                class="newtag form-input-tip" />
            <input type="password" name="password-aff-lws"
                placeholder="<?php esc_attr(_e('Your affiliate password', 'lws-affiliation')) ?>"
                class="newtag form-input-tip" />
            <input type="submit" name="validate-config-aff-lws" id="publish" class="lws_aff_connexion_button"
                value="<?php esc_html_e('Submit', 'lws-affiliation') ?>" />
        </div>
    </form>
    <?php endif ?>
    <?php else : ?>
    <div class="error">
        <p class="lws_aff_text_p">
            <?php echo(wp_kses(__("The plugin configuration is impossible because <i>allow_url_fopen</ i> is not active in your hosting", 'lws-affiliation'), $arr)) ?>.<br />
            <?php echo(wp_kses(__("If you are hosted with LWS, please consult this <a href='http://aide.lws.fr/base/Hebergement-web-mutualise/Programmation/Configurer-PHP' target='_blank'>documentation</ a>, otherwise, please contact your provider to enable this option", 'lws-affiliation'), $arr)) ?>.
        </p>
    </div>
    <?php endif ?>
</div>