<div id="rkmw_wrap">
    <?php RKMW_Classes_ObjController::getClass('RKMW_Core_BlockToolbar')->init(); ?>
    <?php do_action('rkmw_notices'); ?>
    <div class="d-flex flex-row my-0 bg-white">
        <?php echo RKMW_Classes_ObjController::getClass('RKMW_Models_Menu')->getAdminTabs(RKMW_Classes_Helpers_Tools::getValue('tab', 'research'), 'rkmw_research'); ?>
        <div class="d-flex flex-row flex-nowrap flex-grow-1 bg-white pl-1 pr-1 mr-0">
            <div class="flex-grow-1 mr-2 rkmw_flex">
                <?php do_action('rkmw_form_notices'); ?>

                <div class="card col-sm-12 p-0">
                    <div class="card-body p-2 bg-title rounded-top">
                        <div class="rkmw_help_question float-right">
                            <a href="https://howto.rankmywp.com/kb/keyword-research/#keyword_research" target="_blank"><i class="fa fa-question-circle"></i></a>
                        </div>
                        <div class="rkmw_icons_content p-3 py-4">
                            <i class="rkmw_icons rkmw_kr_icon m-2"></i>
                        </div>
                        <h3 class="card-title"><?php echo esc_html__("Keyword Research", RKMW_PLUGIN_NAME); ?>:</h3>
                        <div class="card-title-description m-2">
                            <?php echo esc_html__("You can now find long-tail keywords that are easy to rank for. Get personalized competition data for each keyword you research, thanks to Rank My WP's Market Intelligence Features.", RKMW_PLUGIN_NAME) ?>
                        </div>
                    </div>
                    <div id="rkmw_settings">
                        <?php do_action('rkmw_subscription_notices'); ?>

                        <div class="rkmw_message rkmw_error" style="display: none"></div>

                        <div class="col-sm-12 p-0 py-3">

                            <?php if (isset($view->error) && $view->error == 'limit_exceeded') { ?>
                                <div class="rkmw_step rkmw_step1 my-2">
                                    <h4 class="rkmw_limit_exceeded text-warning text-center">
                                        <?php echo esc_html__("Hmm, looks like you ran out of researches for today.", RKMW_PLUGIN_NAME) ?>
                                        <a href="<?php echo RKMW_Classes_RemoteController::getCloudLink('account') ?>" class="btn btn-success" target="_blank"><?php echo esc_html__("Check Your Account", RKMW_PLUGIN_NAME) ?></a>
                                    </h4>

                                    <h4 class="text-success text-center mt-5 mb-2"><?php echo esc_html__("Add a keyword to Briefcase", RKMW_PLUGIN_NAME) ?></h4>
                                    <form method="post" class="p-0 m-0">
                                        <div class="col-sm-8 offset-sm-2">
                                            <input type="text" name="keyword" class="form-control mb-2" value="<?php echo RKMW_Classes_Helpers_Tools::getValue('keyword', '') ?>">
                                            <div class="my-2 text-black-50 small text-center"><?php echo esc_html__("It's best if you focus on finding Long-Tail Keywords.", RKMW_PLUGIN_NAME) ?></div>
                                        </div>
                                        <div class="col-sm-12 mt-3 text-center">
                                            <?php RKMW_Classes_Helpers_Tools::setNonce('rkmw_briefcase_addkeyword', 'rkmw_nonce'); ?>
                                            <input type="hidden" name="action" value="rkmw_briefcase_addkeyword"/>
                                            <button type="submit" class="sqd-submit btn btn-success btn-lg px-5">
                                                <?php echo esc_html__("Add to Briefcase", RKMW_PLUGIN_NAME) ?>
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            <?php } else { ?>
                                <div class="rkmw_step rkmw_step1 my-2">
                                    <h4 class="text-success text-center my-4"><?php echo esc_html__("Step 1/4: Enter a starting 2-3 words keyword", RKMW_PLUGIN_NAME) ?></h4>

                                    <div class="col-sm-8 offset-sm-2">
                                        <h6 class="my-2 text-info">
                                            <strong><?php echo esc_html__("Enter a keyword that matches your business", RKMW_PLUGIN_NAME) ?>:</strong>
                                        </h6>
                                        <input type="text" name="rkmw_input_keyword" class="form-control rkmw_input_keyword mb-2" value="<?php echo RKMW_Classes_Helpers_Tools::getValue('keyword', '') ?>">
                                        <input type="hidden" name="post_id" value="<?php echo RKMW_Classes_Helpers_Tools::getValue('post_id', false) ?>">
                                        <div class="my-2 text-black-50 small text-center"><?php echo esc_html__("Focus on finding Long Tail Keywords.", RKMW_PLUGIN_NAME) ?></div>
                                        <h4 class="rkmw_research_error text-warning text-center" style="display: none"><?php echo esc_html__("You need to enter a keyword first", RKMW_PLUGIN_NAME) ?></h4>
                                    </div>
                                    <div class="row col-sm-12 mt-3">
                                        <div class="col-sm-6 text-left">
                                        </div>
                                        <div class="col-sm-6 text-right">
                                            <button type="button" class="sqd-submit btn btn-success btn-lg px-5" onclick="jQuery.rkmw_steps(2)"><?php echo esc_html__("Next", RKMW_PLUGIN_NAME) ?> >></button>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="rkmw_step rkmw_step2 my-2" style="display: none">
                                <h4 class="text-success text-center my-4"><?php echo esc_html__("Step 2/4: Choose a country for your keyword research", RKMW_PLUGIN_NAME) ?></h4>

                                <div class="col-sm-8 offset-sm-2">
                                    <h6 class="my-2 text-info">
                                        <strong><?php echo esc_html__("Select country", RKMW_PLUGIN_NAME) ?>:</strong>
                                    </h6>


                                    <select class="form-control" name="rkmw_select_country">
                                        <option value="com"><?php echo esc_html__("Global Search", RKMW_PLUGIN_NAME) ?></option>
                                        <?php
                                        if (isset($view->countries) && !empty($view->countries)) {
                                            foreach ($view->countries as $key => $country) {
                                                echo '<option value="' . $key . '" ' . (isset($_COOKIE['rkmw_country']) && sanitize_text_field($_COOKIE['rkmw_country']) == $key ? 'selected="selected"' : '') . '>' . $country . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <div class="my-2 text-black-50 small text-center"><?php echo esc_html__("For local SEO you need to select the Country where you run your business", RKMW_PLUGIN_NAME) ?></div>
                                </div>
                                <div class="row col-sm-12 mt-5">

                                    <div class="col-sm-6 text-left">
                                        <button type="button" class="btn btn-link btn-lg" onclick="location.reload();"><?php echo esc_html__("Start Over", RKMW_PLUGIN_NAME) ?></button>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <button type="button" class="sqd-submit btn btn-success btn-lg px-5" onclick="jQuery('.rkmw_step3').rkmw_getSuggested();"><?php echo esc_html__("Next", RKMW_PLUGIN_NAME) ?> >></button>
                                    </div>
                                </div>
                            </div>
                            <div class="rkmw_step rkmw_step3  my-2" style="display: none; min-height: 250px">
                                <h4 class="text-success text-center my-4"><?php echo esc_html__("Step 3/4: Select up to 3 similar keywords from below", RKMW_PLUGIN_NAME) ?></h4>
                                <div class="text-danger text-center my-4" style="display: none"><?php echo esc_html__("Select up to 3 similar keywords and start the research", RKMW_PLUGIN_NAME) ?></div>
                                <div class="col-sm-10 offset-1">
                                    <div class="custom-control custom-checkbox">
                                        <div class="row">
                                            <div class="rkmw_suggested col-sm-5 offset-1 mt-2"></div>
                                            <div class="rkmw_suggested col-sm-5 offset-1 mt-2"></div>
                                        </div>
                                        <div class="row">
                                            <div class="rkmw_suggested col-sm-5 offset-1 mt-2"></div>
                                            <div class="rkmw_suggested col-sm-5 offset-1 mt-2"></div>
                                        </div>
                                        <div class="row">
                                            <div class="rkmw_suggested col-sm-5 offset-1 mt-2"></div>
                                            <div class="rkmw_suggested col-sm-5 offset-1 mt-2"></div>
                                        </div>
                                        <div class="row">
                                            <div class="rkmw_suggested col-sm-5 offset-1 mt-2"></div>
                                            <div class="rkmw_suggested col-sm-5 offset-1 mt-2"></div>
                                        </div>
                                        <div class="row">
                                            <div class="rkmw_suggested col-sm-5 offset-1 mt-2"></div>
                                            <div class="rkmw_suggested col-sm-5 offset-1 mt-2"></div>
                                        </div>
                                        <div class="row">
                                            <div class="rkmw_suggested col-sm-5 offset-1 mt-2"></div>
                                            <div class="rkmw_suggested col-sm-5 offset-1 mt-2"></div>
                                        </div>
                                        <div class="row">
                                            <div class="rkmw_suggested col-sm-5 offset-1 mt-2"></div>
                                            <div class="rkmw_suggested col-sm-5 offset-1 mt-2"></div>
                                        </div>
                                        <div class="row">
                                            <div class="rkmw_suggested col-sm-5 offset-1 mt-2"></div>
                                            <div class="rkmw_suggested col-sm-5 offset-1 mt-2"></div>
                                        </div>
                                    </div>
                                    <h4 class="rkmw_limit_exceeded text-warning text-center" style="display: none">
                                        <?php echo esc_html__("Hmm, looks like you ran out of researches for today.", RKMW_PLUGIN_NAME) ?>
                                        <a href="<?php echo RKMW_Classes_RemoteController::getCloudLink('account') ?>" class="btn btn-success" target="_blank"><?php echo esc_html__("Check Your Account", RKMW_PLUGIN_NAME) ?></a>
                                    </h4>
                                    <h4 class="rkmw_research_error text-warning text-center" style="display: none"><?php echo sprintf(esc_html__("We could not find similar keywords. %sClick on 'Do research'", RKMW_PLUGIN_NAME), '<br />') ?></h4>
                                </div>
                                <div class="row col-sm-12 mt-5">
                                    <div class="col-sm-4 p-2 text-left">
                                        <button type="button" class="btn btn-link btn-lg" onclick="location.reload();"><?php echo esc_html__("Start Over", RKMW_PLUGIN_NAME) ?></button>
                                    </div>
                                    <div class="col-sm-8 mx-0 my-3 p-0 text-right">
                                        <button type="button" class="sqd-submit btn btn-success px-5" onclick="jQuery('.rkmw_step4').rkmw_getResearch(10);"><?php echo esc_html__("Do research", RKMW_PLUGIN_NAME) ?> >></button>
                                    </div>

                                </div>
                            </div>
                            <div class="rkmw_step rkmw_step4 col-sm-12 my-2 px-0" style="display: none; min-height: 230px !important;">
                                <div class="rkmw_loading_steps" style="display: none; ">
                                    <div class="rkmw_loading_step1 rkmw_loading_step"><?php echo esc_html__("Keyword Research in progress. We're doing all of this in real-time. Data is fresh.", RKMW_PLUGIN_NAME) ?></div>
                                    <div class="rkmw_loading_step2 rkmw_loading_step"><?php echo esc_html__("We're now finding 10 alternatives for each keyword you selected.", RKMW_PLUGIN_NAME) ?></div>
                                    <div class="rkmw_loading_step3 rkmw_loading_step"><?php echo esc_html__("For each alternative, we are looking at the top 10 pages ranked on Google for that keyword.", RKMW_PLUGIN_NAME) ?></div>
                                    <div class="rkmw_loading_step4 rkmw_loading_step"><?php echo esc_html__("We are now measuring the web authority of each competing page and comparing it to yours.", RKMW_PLUGIN_NAME) ?></div>
                                    <div class="rkmw_loading_step5 rkmw_loading_step"><?php echo esc_html__("Looking at the monthly search volume for each keyword.", RKMW_PLUGIN_NAME) ?></div>
                                    <div class="rkmw_loading_step6 rkmw_loading_step"><?php echo esc_html__("Analyzing the last 30 days of Google trends for each keyword.", RKMW_PLUGIN_NAME) ?></div>
                                    <div class="rkmw_loading_step7 rkmw_loading_step"><?php echo esc_html__("Seeing how many discussions there are on forums and Twitter for each keyword.", RKMW_PLUGIN_NAME) ?></div>
                                    <div class="rkmw_loading_step8 rkmw_loading_step"><?php echo esc_html__("Piecing all the keywords together now after analyzing each individual keyword.", RKMW_PLUGIN_NAME) ?></div>
                                    <div class="rkmw_loading_step9 rkmw_loading_step"><?php echo esc_html__("Preparing the results.", RKMW_PLUGIN_NAME) ?></div>

                                </div>
                                <h4 class="rkmw_research_success text-success text-center my-2" style="display: none"><?php echo esc_html__("Step 4/4: We found some relevant keywords for you", RKMW_PLUGIN_NAME) ?></h4>
                                <h4 class="rkmw_research_timeout_error text-warning text-center" style="display: none"><?php echo sprintf(esc_html__("Still processing. give it a bit more time, then go to %sResearch History%s. Results will appear there.", RKMW_PLUGIN_NAME), '<a href="' . RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_research', 'history') . '" >', '</a>') ?></h4>
                                <h4 class="rkmw_research_error text-warning text-center" style="display: none"><?php echo esc_html__("Step 4/4: We could not find relevant keywords for you", RKMW_PLUGIN_NAME) ?></h4>

                                <div class="p-1">
                                    <table class="table table-striped table-hover" style="display: none">
                                        <thead>
                                        <tr>
                                            <th><?php echo esc_html__("Keyword", RKMW_PLUGIN_NAME) ?></th>
                                            <th title="<?php echo esc_html__("Country", RKMW_PLUGIN_NAME) ?>"><?php echo esc_html__("Co", RKMW_PLUGIN_NAME) ?></th>
                                            <th>
                                                <i class="fa fa-users" title="<?php echo esc_html__("Competition", RKMW_PLUGIN_NAME) ?>"></i>
                                                <?php echo esc_html__("Competition", RKMW_PLUGIN_NAME) ?>
                                            </th>
                                            <th>
                                                <i class="fa fa-search" title="<?php echo esc_html__("SEO Search Volume", RKMW_PLUGIN_NAME) ?>"></i>
                                                <?php echo esc_html__("Search", RKMW_PLUGIN_NAME) ?>
                                            </th>
                                            <th>
                                                <i class="fa fa-comments-o" title="<?php echo esc_html__("Recent discussions", RKMW_PLUGIN_NAME) ?>"></i>
                                                <?php echo esc_html__("Discussion", RKMW_PLUGIN_NAME) ?>
                                            </th>
                                            <th>
                                                <i class="fa fa-bar-chart" title="<?php echo esc_html__("Trending", RKMW_PLUGIN_NAME) ?>"></i>
                                                <?php echo esc_html__("Trend", RKMW_PLUGIN_NAME) ?>
                                            </th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-6 p-2 text-left">
                                        <button type="button" class="btn btn-link btn-lg" onclick="location.reload();"><?php echo esc_html__("Start Over", RKMW_PLUGIN_NAME) ?></button>
                                    </div>
                                    <div class="col-sm-6 text-right">

                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>
                </div>

                <div class="col-sm-12 text-center m-3">
                    <a href="https://howto.rankmywp.com/kb/find-keywords-and-get-more-search-traffic/" target="_blank"><?php echo esc_html__("How to Find Amazing Keywords and get more search traffic?", RKMW_PLUGIN_NAME) ?></a>
                </div>
            </div>
            <div class="rkmw_col_side sticky">
                <div class="card col-sm-12 p-0">
                    <div class="card-body f-gray-dark p-0">
                        <?php echo RKMW_Classes_ObjController::getClass('RKMW_Core_BlockKnowledgeBase')->init(); ?>
                    </div>


                </div>

                <div class="card col-sm-12 border-0 p-2 text-center">
                    <h5 class="modal-title mb-3"><?php echo esc_html__("Already Have Keywords?", RKMW_PLUGIN_NAME); ?></h5>

                    <div>
                        <a href="<?php echo RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_research', 'briefcase') ?>" class="btn rounded-0 btn-success px-2 mx-2"><?php echo esc_html__("Import Keywords From CSV", RKMW_PLUGIN_NAME); ?></a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>