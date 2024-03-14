<div id="rkmw_toolbarblog" class="col-sm-12 m-0 p-0">
    <nav class="navbar navbar-expand-sm" color-on-scroll="500">
        <div class=" container-fluid  ">
            <div class="justify-content-start" id="navigation">
                <ul class="nav navbar-nav mr-auto">
                    <?php
                    $visitedmenu = false;
                    $mainmenu = RKMW_Classes_ObjController::getClass('RKMW_Models_Menu')->getMainMenu();
                    if (RKMW_Classes_Helpers_Tools::getOption('api') <> '' && RKMW_Classes_Helpers_Tools::getOption('onboarding') == RKMW_VERSION) {
                        $visitedmenu = RKMW_Classes_ObjController::getClass('RKMW_Models_Menu')->getVisitedMenu();
                    }
                    $errors = apply_filters('rkmw_seo_errors', 0);

                    if (!empty($mainmenu)) {
                        foreach ($mainmenu as $menuid => $item) {

                            if(isset($item['topmenu']) && !$item['topmenu']){
                                continue;
                            }

                            if (!RKMW_Classes_Helpers_Tools::getMenuVisible($item['topmenu'])) {
                                continue;
                            } elseif (!isset($item['parent'])) {
                                continue;
                            }
                            //make sure the user has the capabilities
                            if (current_user_can($item['capability'])) {
                                if ($menuid <> 'rkmw_dashboard') {
                                    ?>
                                    <li class="nav-item" style="    padding-top: 8px;">
                                        <svg class="separator" height="40" width="20" xmlns="http://www.w3.org/2000/svg">
                                            <?php if(is_rtl()){ ?>
                                                <line stroke="lightgray" stroke-width="1" x1="0" x2="19" y1="40" y2="20"></line>
                                                <line stroke="lightgray" stroke-width="1" x1="0" x2="19" y1="0" y2="20"></line>
                                            <?php }else{ ?>
                                                <line stroke="lightgray" stroke-width="1" x1="0" x2="19" y1="0" y2="20"></line>
                                                <line stroke="lightgray" stroke-width="1" x1="0" x2="19" y1="40" y2="20"></line>
                                            <?php } ?>
                                        </svg>
                                    </li>
                                <?php } ?>
                                <?php $page = apply_filters('rkmw_page', RKMW_Classes_Helpers_Tools::getValue('page', false)); ?>
                                <li class="nav-item <?php echo(($page == $menuid) ? 'active' : '') ?>">
                                    <a class="nav-link" href="<?php echo RKMW_Classes_Helpers_Tools::getAdminUrl($menuid) ?>">
                                        <?php echo($menuid == 'rkmw_dashboard' ? esc_html__("Overview", RKMW_PLUGIN_NAME)  : $item['title']) ?>
                                        <?php echo (($menuid == 'rkmw_dashboard' && $page <> $menuid && $errors) ? '<span class="rkmw_errorcount">' . (int)$errors . '</span>' : '') ?>
                                    </a>

                                </li>
                            <?php }
                        }
                    } ?>
                    <li class="rkmw_help_toolbar">
                        <i class="fa fa-question-circle" onclick="jQuery('.header-search').toggle();"></i></li>
                </ul>
            </div>
        </div>
        <div id="rkmw_btn_toolbar_close" class="m-0 p-0" style="display: none">
            <a href="<?php echo RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_dashboard') ?>" class="btn btn-lg bg-white text-black m-0 mx-2 p-2 px-3 font-weight-bold">X</a>
        </div>
    </nav>
</div>
<noscript><div style="text-align: center; padding: 20px;"><?php echo sprintf(esc_html__("Javascript is disabled on your browser! You need to activate the javascript in order to use %s.", RKMW_PLUGIN_NAME), RKMW_NAME) ?></div><style>#rkmw_preloader { display:none; } #rkmw_wrap #rkmw_focuspages .rkmw_overflow{display: block !important;max-width: 1100px;} .rkmw_alert {top: 0;max-height: 50px;line-height: 20px;}</style></noscript>
<?php RKMW_Classes_ObjController::getClass('RKMW_Core_BlockSearch')->init(); ?>
