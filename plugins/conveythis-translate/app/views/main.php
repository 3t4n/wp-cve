<div class="wrap">

    <?php require_once(CONVEY_PLUGIN_ROOT_PATH . 'app/views/layout/expired-message.php');?>

    <div class="settings-block">
        <form method="post" class="conveythis-widget-option-form" action="options.php" class="w-100">
            <?php
                settings_fields('my-plugin-settings-group');
                do_settings_sections('my-plugin-settings-group');
            ?>
            <div class="main-block">
                <!--Head block-->
                <div class="justify-content-between w-100 align-items-center mx-auto">
                    <div class="col-md-2 text-center">
                        <div><a href="https://www.conveythis.com/" target="_blank"><img src="<?php echo CONVEY_PLUGIN_PATH;?>app/widget/images/logo-convey.png" alt="ConveyThis"></a></div>
                    </div>
                </div>
                <!--Separator-->
                <div class="line-grey"></div>

                <?php
                    require_once(CONVEY_PLUGIN_ROOT_PATH . 'app/views/layout/menu.php');
                ?>

                <div class="row col-md-12">

                    <div class="col-md-8 tab-content" id="pills-tabContent">
                        <?php
                            require_once(CONVEY_PLUGIN_ROOT_PATH . 'app/views/page/main-configuration.php');
                            require_once(CONVEY_PLUGIN_ROOT_PATH . 'app/views/page/general-settings.php');
                            require_once(CONVEY_PLUGIN_ROOT_PATH . 'app/views/page/widget-style.php');
                            require_once(CONVEY_PLUGIN_ROOT_PATH . 'app/views/page/block-pages.php');
                            require_once(CONVEY_PLUGIN_ROOT_PATH . 'app/views/page/glossary.php');
                            require_once(CONVEY_PLUGIN_ROOT_PATH . 'app/views/page/links.php');
                            require_once(CONVEY_PLUGIN_ROOT_PATH . 'app/views/page/cache.php');
                        ?>
                    </div>

                    <div class="col-md-4 router-widget" style="display: flex; align-items: center; justify-content: center;">
                        <?php
                            require_once CONVEY_PLUGIN_ROOT_PATH . 'app/views/layout/widget.php';
                        ?>
                    </div>

                </div>


                <!--Separator-->
                <div class="line-grey"></div>

                <div class="btn-box d-flex justify-content-start">
                    <!--Submit button-->
                    <input type="submit" name="submit" id="submit" class="btn btn-primary btn-custom autoSave" value="Save settings">
                </div>

            </div>
        </form>
    </div>

    <div class="my-5" style="font-size: 14px">

        <a href="https://wordpress.org/support/plugin/conveythis-translate/reviews/#postform" target="_blank">
            Love ConveyThis? Give us 5 stars on WordPress.org
        </a>
        <br>
        If you need any help, you can contact us via our live chat at <a href="https://www.conveythis.com/?utm_source=widget&utm_medium=wordpress" target="_blank">www.ConveyThis.com</a> or email us at support@conveythis.com. You can also check our <a href="https://www.conveythis.com/faqs/?utm_source=widget&utm_medium=wordpress" target="_blank">FAQ</a>

    </div>

</div>