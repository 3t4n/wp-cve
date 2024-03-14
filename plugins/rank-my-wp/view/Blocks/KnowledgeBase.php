<?php $page = apply_filters('rkmw_page', RKMW_Classes_Helpers_Tools::getValue('page', '')); ?>
<div class="mt-2">
    <div class="rkmw_knowledge p-2">
        <h4 class="mt-2 text-center">

            <?php echo esc_html__("Knowledge Base", RKMW_PLUGIN_NAME) ?>
            <a href="https://howto.rankmywp.com/" target="_blank">
                <img src="<?php echo RKMW_ASSETS_URL . 'img/settings/knowledge.png' ?>" style="width: 150px;display: block;margin: 0 auto;">
            </a>
        </h4>
        <div>
            <?php if ($page == 'rkmw_dashboard') { ?>
                <ul class="list-group list-group-flush">
                    <?php if (RKMW_Classes_Helpers_Tools::getOption('api') == '') { ?>
                        <li class="list-group-item text-left" >
                            <a href="https://howto.rankmywp.com/kb/install-rank-my-wp-plugin/#connect_to_cloud" target="_blank">How to get API Key from Rank My WP Cloud</a>
                        </li>
                    <?php } ?>
                </ul>
                <div class="text-center m-2">
                    <a href="https://howto.rankmywp.com/" target="_blank">[ go to knowledge base ]</a>
                </div>
            <?php } ?>
            <?php if (strpos($page, 'rkmw_research') !== false) { ?>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-left">
                        <a href="https://howto.rankmywp.com/kb/keyword-research/" target="_blank">How to do a Keyword Research.</a>
                    </li>
                    <li class="list-group-item text-left">
                        <a href="https://howto.rankmywp.com/kb/keyword-research/#find_new_keywords" target="_blank">How to Find New Keywords.</a>
                    </li>
                    <li class="list-group-item text-left">
                        <a href="https://howto.rankmywp.com/kb/keyword-research/#briefcase_add_keyword" target="_blank">How to add Keywords into Briefcase.</a>
                    </li>
                    <li class="list-group-item text-left">
                        <a href="https://howto.rankmywp.com/kb/keyword-research/#briefcase_label" target="_blank">How to add Labels to Keywords.</a>
                    </li>
                    <li class="list-group-item text-left">
                        <a href="https://howto.rankmywp.com/kb/keyword-research/#briefcase_optimize" target="_blank">How to optimize a post with Briefcase.</a>
                    </li>
                    <li class="list-group-item text-left">
                        <a href="https://howto.rankmywp.com/kb/keyword-research/#briefcase_backup_keywords" target="_blank">How to backup/restore Briefcase Keywords.</a>
                    </li>
                </ul>
                <div class="text-center m-2">
                    <a href="https://howto.rankmywp.com/" target="_blank">[ go to knowledge base ]</a>
                </div>
            <?php } ?>
            <?php if (strpos($page, 'rkmw_rankings') !== false) { ?>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-left">
                        <a href="https://howto.rankmywp.com/kb/google-ranking/#add_keyword_ranking" target="_blank">How to add a Keyword in Rankings.</a>
                    </li>
                    <li class="list-group-item text-left">
                        <a href="https://howto.rankmywp.com/kb/google-ranking/#remove_keyword_ranking" target="_blank">How to remove a keyword from Rankings.</a>
                    </li>
                </ul>
                <div class="text-center m-2">
                    <a href="https://howto.rankmywp.com/" target="_blank">[ go to knowledge base ]</a>
                </div>
            <?php } ?>
        </div>
    </div>

</div>