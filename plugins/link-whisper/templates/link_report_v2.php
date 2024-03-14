<div class="wrap wpil-report-page wpil_styles">
    <?php if( empty( get_option(WPIL_PREMIUM_NOTICE_DISMISSED, '') ) ){ ?>
    <div id="lw_banner">
        <img class="close" src="<?php echo esc_url(WP_INTERNAL_LINKING_PLUGIN_URL . 'images/icon_delete.png'); ?>">
        <div class="title"><?php _e('Upgrade to Link Whisper Premium', 'wpil'); ?></div>
        <div class="features">
            <div><?php _e('+ Add multiple links with pre-selected anchor text in one action!', 'wpil'); ?></div>
            <div><?php _e('+ Improve SEO by adding inbound links to older, less connected pages.', 'wpil'); ?></div>
            <div><?php _e('+ Quickly manage links (add, edit, delete) from the reports page.', 'wpil'); ?></div>
            <div><?php _e('+ Directly edit sentences and modify anchor text or URLs within Link Whisper.', 'wpil'); ?></div>
            <div><?php _e('+ Exclude specific URLs/categories from being suggested as link opportunities.', 'wpil'); ?></div>
            <div><?php _e('+ Optimize for target SEO keywords with suggested relevant links, including import from SEO plugins like Yoast and RankMath.', 'wpil'); ?></div>
            <div><?php _e('+ Connect to Google Search Console for target keywords Google is giving you impressions for.', 'wpil'); ?></div>
            <div><?php _e('+ Automate linking for specified keywords to chosen URLs, with control over frequency.', 'wpil'); ?></div>
            <div><?php _e('+ Change old URLs site-wide to new ones easily with a bulk link changer.', 'wpil'); ?></div>
            <div><?php _e('+ Identify and manage broken links site-wide, with verification over time to ensure accuracy.', 'wpil'); ?></div>
            <div><?php _e('+ Receive linking suggestions across multiple sites with Link Whisper Premium.', 'wpil'); ?></div>
            <div><?php _e('+ Access the related posts widget which can display posts with thumbnails or bullet lists, and automatically prioritize linking orphan pages.', 'wpil'); ?></div>
        </div>
        <a href="<?php echo esc_url(WPIL_STORE_URL . '/upgrade-offer/'); ?>" target="blank"><?php _e('Get $15 Off Link Whisper Premium Now!', 'wpil'); ?></a>
    </div>
    <?php } ?>

    <?=Wpil_Base::showVersion()?>
    <h1 class="wp-heading-inline"><?php _e('Internal Links Report', 'wpil'); ?></h1>
    <?php $user = wp_get_current_user(); ?>
    <hr class="wp-header-end">
    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content" style="position: relative;">
                <?php $user = wp_get_current_user(); ?>
                <form action='' method="post" style="float: right;" id="wpil_report_reset_data_form">
                    <input type="hidden" name="reset" value="1">
                    <input type="hidden" name="reset_data_nonce" value="<?php echo wp_create_nonce($user->ID . 'wpil_reset_report_data'); ?>">
                    <?php 
                        if(!empty(get_transient('wpil_resume_scan_data'))){
                            echo '<a href="javascript:void(0)" class="button-primary wpil-resume-link-scan">' . __('Resume Link Scan', 'wpil') . '</a>';
                        }
                    ?>
                    <button type="submit" class="button-primary">Run Link Scan</button>
                </form>
                <div class="tbl-link-reports">
                    <form>
                        <input type="hidden" name="page" value="link_whisper" />
                        <input type="hidden" name="type" value="links" />
                        <?php $tbl->search_box('Search', 'search_posts'); ?>
                    </form>
                    <?php $tbl->display(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var wpil_admin_url = '<?php echo admin_url()?>';
</script>
