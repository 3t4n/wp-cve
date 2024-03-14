<?php
/*
 * Template Name: SwiftSignature Thanks
 */
get_header();
wp_enqueue_script('swift-timeago', SWIFTSIGN_PLUGIN_URL.'js/jquery.timeago.js', array('jquery'), '', true);
?>
<section id="page-main-content" class="fullwidth main-content">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 page-left">
                <?php
                // Start the loop.
                while (have_posts()) : the_post();
                    // Include the page content template.
                    get_template_part('content', 'page');
                // End the loop.
                endwhile;
                ?>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 page-right smarketing-sidebar">
                <div class="sidebar-widget">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <?php edit_post_link(__('Edit', 'SwiftCloud'), '<footer class="entry-footer"><span class="edit-link">', '</span></footer><!-- .entry-footer -->'); ?>
    </div>
    <div class="swiftChat">
        <div class="chatWebFromBox"></div>
        <div class="chatWebFrom display-none">
            <div class="chatClose">
                <a href="javascript:;" data-toggle="tooltip" data-placement="bottom" data-animation="false" title="Close"><i class="fa fa-close"></i></a>
            </div>
            <div class="chatWebFromTeamChat" id="leaveSitePopupShow">
                <div class="sTeamMsg">
                    <div class="sTeamAvtar" data-toggle="tooltip" data-placement="bottom" data-animation="false" title="SwiftCloud Robot">
                        <img src="<?php echo SWIFTSIGN_PLUGIN_URL ?>/images/BotSmall.gif">
                    </div>
                    <div class="preLoading">
                        <p class="text-center">
                            <img src="<?php echo SWIFTSIGN_PLUGIN_URL ?>/images/22.gif">
                        </p>
                    </div>
                    <div class="msgContent clearfix display-none">
                        <p><i class="fa fa-exclamation-triangle"></i> Heads up! Your form has not been submitted yet.<br/> We don't want you to lose any data.</p>
                        <a href="#" class="btn btn-default"><i class="fa fa-external-link"></i> Leave Site</a>
                        <a href="#" class="btn btn-primary"><i class="fa fa-history"></i> Return to Form</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="poweredBy text-center display-none"><small>Powered by SwiftCloud</small></div>
    </div>
</section>
<?php get_footer(); ?>