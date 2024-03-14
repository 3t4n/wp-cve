<?php
function mw_dash_settings_page()
{
    ?>
    <div class="container">
        <div class="mweb">
            <?php echo '<a target="_blank" href="https://my.mandegarweb.com/aff.php?aff=62"><img class="mwfc-responsive" src="' . plugins_url('assets/images/mandegarweb.gif', dirname(__FILE__)) . '"></a>'; ?>
            <?php echo '<a target="_blank" href="https://www.zhaket.com/web/mw-font-changer-pro/?affid=AF-61332c0051cb8"><img class="mwfc-responsive" src="' . plugins_url('assets/images/pro.jpg', dirname(__FILE__)) . '"></a>'; ?>
        </div>
        <div class="mwtitle">
            <h2><span class="dashicons dashicons-dashboard"></span>
                <?php _e('MW Font Changer', 'mwfc'); ?>
            </h2>
        </div>
        <div id="mwfc_tabs" class="mwfc_tabs">
            <nav>
                <ul>
                    <li><a href="#section-1"><i class="fa fa-tachometer" aria-hidden="true"></i>
                            <span><?php _e('Dashboard Font', 'mwfc'); ?></span></a></li>
                    <li><a href="#section-2"><i class="fa fa-question" aria-hidden="true"></i>
                            <span><?php _e('Help', 'mwfc'); ?></span></a></li>
                    <li><a href="#section-3"><i class="fa fa-comments" aria-hidden="true"></i>
                            <span><?php _e('Feedback', 'mwfc'); ?></span></a></li>
                    <li><a href="#section-4"><i class="fa fa-comments" aria-hidden="true"></i>
                            <span><?php _e('Pro Version', 'mwfc'); ?></span></a></li>
                </ul>
            </nav>
            <div class="content">
                <section id="section-1">
                    <?php include_once('plugin-dashboard-options.php'); ?>
                </section>
                <section id="section-5">
                    <?php include_once('help.php'); ?>
                </section>
                <section id="section-3">
                    <?php include_once('feedback.php'); ?>
                </section>
                <section id="section-4">
                    <?php include_once('pro.php'); ?>
                </section>
            </div>
            <!-- /content -->
        </div>
        <!-- /tabs -->
    </div>
    <?php echo '<script src="' . plugins_url('assets/js/cbpFWTabs.js', dirname(__FILE__)) . '"></script> '; ?>
    <script>
        new CBPFWTabs(document.getElementById('mwfc_tabs'));
    </script>
    <?php
}