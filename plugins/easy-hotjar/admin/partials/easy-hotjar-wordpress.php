<?php

/**
 * The Easy hotjar WordPress plugin helps you to set up hotjar on your site.
 *
 * @package EHW
 */

?>
<style>
    .easy-wysiwyg-style-head {
        color: #666;
        box-shadow: 0 4px 5px 0 rgba(0,0,0,.04);
        background-color: #ffffff;
    }
    .easy-wysiwyg-style-head h1 {
        color: #000000 !important;
        font-family: HelveticaNeue, 'Helvetica Neue', Helvetica, Arial, Verdana, sans-serif;
    }
    .about-wrap .wp-badge {
        right: 15px;
        background-color: transparent;
        box-shadow: none;
    }
    .about-text {
        color: #666 !important;
    }

    .easy-more {
        margin-top: 15px;
        background: #FFFFFF;
        border: 1px solid #E5E5E5;
        position: relative;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
        padding: 5px 15px;
    }
    .easy-plugins-box {
        background-color: #EEEFFF;
        border: 1px solid #E5E5E5;
        border-top: 0 none;
        position: relative;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
        padding: 15px;
    }
    .easy-bottom {
        background-color: #52ACCC;
        color: #FFFFFF;
        border: 1px solid #FFFFFF;
        border-top: 0 none;
        position: relative;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
        padding: 5px 15px;
    }
    .easy-bottom a {
        color: #FFFFFF;
    }
    .border {
        background: #ffffff;
        box-shadow: 0 1px 4px 0 rgba(0,0,0,.15);
        padding: 20px;
    }
    .nopadding {
        padding-right: 0px !important;
    }
    #wpcontent {
        background-color: #F7F8FA;
    }
    li {
        margin: 10px;
    }
    .center {
        text-align: center !important;
        vertical-align: middle;
        padding: 10px 20px;
    }
    .center .submit {
        text-align: center !important;
    }
    .main-box {
        margin-top: 15px;
    }
</style>
<div class="wrap about-wrap">
    <div class="row easy-wysiwyg-style-head">
        <div class="col-md-12">
            <img src="<?php echo  plugins_url( 'easy-hotjar/admin/img/easyplugins.png' ) ?>" style="float:right; max-height: 140px; margin: 10px" />
            <h1>Easy <img src="<?php echo  plugins_url( 'easy-hotjar/admin/img/header-logo.png' ) ?>" /> WordPress</h1>
            <div class="about-text">Set up <img style="height: 20px" src="<?php echo  plugins_url( 'easy-hotjar/admin/img/header-logo.png' ) ?>" /> in a matter of seconds.</div>
        </div>
    </div>
    <hr/>

    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <div class="main-box">
                    <div class="border">
                        <form method="post" action="options.php" enctype="multipart/form-data">
                            <?php settings_fields( 'ehw' ); ?>
                            <?php do_settings_sections( 'ehw' ); ?>
                            <?php
                            $ehw=get_option('ehw');
                            if (!is_array($ehw)){ $ehw = array(); $ehw['num']=''; $ehw['val']=''; }
                            ?>
                            <h4>Do you have <a href="https://www.hotjar.com" target="_blank">hotjar</a> Account?</h4>
                            <p>If not, you have to register first. <a target="_blank" href="https://www.hotjar.com/r/r59ecf9">Link</a></p>
                            <div class="row">
                                <div class="col-md-4 center">
                                    <h3>1.</h3>
                                    Go to <a href="https://insights.hotjar.com/login" target="_blank">Hotjar</a> website
                                </div>
                                <div class="col-md-4 center">
                                    <h3>2.</h3>
                                    Log In your account<br/>
                                    <img style="max-height: 200px" src="<?php echo  plugins_url( 'easy-hotjar/admin/img/hotjar1.png' ) ?>"/>
                                </div>
                                <div class="col-md-4 center">
                                    <h3>3.</h3>
                                    Go to your Site Dashboard<br/>
                                    <img style="max-height: 200px" src="<?php echo  plugins_url( 'easy-hotjar/admin/img/hotjar2.png' ) ?>"/>
                                </div>
                                <div class="col-md-12 center">
                                    <h3>4.</h3>
                                    Take a look of the URL on your address bar:<br/>
                                    <img style="max-height: 200px" src="<?php echo  plugins_url( 'easy-hotjar/admin/img/hotjar3.png' ) ?>"/>
                                    <br/><br/>
                                    <p>It should be something similar to : https://insights.hotjar.com/sites/<strong>205458</strong>/dashboard </p>
                                    <p>That 6 digits number: <strong>205458</strong> is what you have to enter below </p>
                                </div>
                                <div class="col-md-6 center">
                                    <h3>5.</h3>
                                    Enter your site Id below<br/><br/>
                                    <input type="text" name="ehw[num]" id="ehw[num]" value="<?= $ehw['num']; ?>" maxlength="10" style="width:200px"/>
                                </div>
                                <div class="col-md-6 center">
                                    <h3>6.</h3>
                                    And click Save Changes :)
                                    <?php submit_button(); ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 nopadding">
            <div class="easy-more">
                <h4>More <img src="<?php echo  plugins_url( 'easy-hotjar/admin/img/easyplugins.png' ) ?>"
                                 style="max-height: 70px; margin: 10px" />:</h4>
                <ul>
                    <li>
                        <a href="https://wordpress.org/plugins/easy-admin-menu/" target="_blank">· Easy Admin Menu</a>
                    </li>
                    <li>
                        <a href="https://wordpress.org/plugins/easy-login-form/" target="_blank">· Easy Login Form</a>
                    </li>
                    <li>
                        <a href="https://wordpress.org/plugins/easy-options-page/" target="_blank">· Easy Options Page</a>
                    </li>
                    <li>
                        <a href="https://wordpress.org/plugins/easy-timeout-session/" target="_blank">· Easy Timeout Session</a>
                    </li>
                    <li>
                        <a href="https://wordpress.org/plugins/easy-wysiwyg-style/" target="_blank">· Easy Wysiwyg Style</a>
                    </li>
                    <li>
                        <a href="https://wordpress.org/plugins/easy-hotjar/" target="_blank">· Easy Hotjar</a>
                    </li>
                </ul>
            </div>
            <div class="easy-plugins-box">
                <div class="text-center">
                    <p>This plugin is Free Software and is made available free of charge.</p>
                    <p>If you like the software, please consider a donation.</p>
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" class="">
                        <input type="hidden" name="cmd" value="_s-xclick">
                        <input type="hidden" name="hosted_button_id" value="CHXF6Q9T3YLQU">
                        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                        <img alt="" border="0" src="https://www.paypalobjects.com/es_ES/i/scr/pixel.gif" width="1" height="1">
                    </form>
                </div>
            </div>
            <div class="easy-bottom">
                Created by <a href="http://jokiruiz.com" target="_blank">Joaquín Ruiz</a>
            </div>
        </div>
    </div>
</div>
