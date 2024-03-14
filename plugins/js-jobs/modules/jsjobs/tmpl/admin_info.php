<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<div id="jsjobsadmin-wrapper">
	<div id="jsjobsadmin-leftmenu">
        <?php  JSJOBSincluder::getClassesInclude('jsjobsadminsidemenu'); ?>
    </div>
    <div id="jsjobsadmin-data">
    <span class="js-admin-title">
        <span class="heading">  
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=jsjobs'), 'dashboard')); ?>"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/back-icon.png" /></a>
            <span class="text-heading"><?php echo __('Information', 'js-jobs'); ?></span>    
        </span>
    </span>
    <span class="js-admin-component"><?php echo __('Component Details', 'js-jobs'); ?></span>
    <div class="detail-part">
        <span class="js-admin-component-detail">    </span>
        <div class="js-admin-info-wrapper">
            <span class="js-admin-info-title"><?php echo __('Created By', 'js-jobs'); ?></span>
            <span class="js-admin-info-vlaue"><?php echo __('Ahmed Bilal', 'js-jobs'); ?></span>
        </div>
        <div class="js-admin-info-wrapper">
            <span class="js-admin-info-title"><?php echo __('Company', 'js-jobs'); ?></span>
            <span class="js-admin-info-vlaue"><?php echo __('Joom Sky', 'js-jobs'); ?></span>
        </div>
        <div class="js-admin-info-wrapper">
            <span class="js-admin-info-title"><?php echo __('Plugins', 'js-jobs'); ?></span>
            <span class="js-admin-info-vlaue"><?php echo __('JS Jobs', 'js-jobs'); ?></span>
        </div>
        <div class="js-admin-info-wrapper">
            <span class="js-admin-info-title"><?php echo __('Version', 'js-jobs'); ?></span>
            <span class="js-admin-info-vlaue"><?php echo __('1.0.4', 'js-jobs'); ?></span>
        </div>
    </div>
    <div class="js-admin-joomsky-wrapper ">
        <span class="js-admin-title">
            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/aboutus_page/logo.png" />
        </span>
        <span class="detail-text">
            <span class="detail-heading">
                <?php echo __('About JoomSky', 'js-jobs'); ?>
            </span>
            <?php echo __('Our philosophy on project development is quite simple. We deliver exactly what you need to ensure the growth and effective running of your business. To do this we undertake a complete analysis of your business needs with you, then conduct thorough research and use our knowledge and expertise of software development programs to identify the products that are most beneficial to your business projects.', 'js-jobs'); ?>
            <span class="js-joomsky-link">
                <a href="http://www.joomsky.com" target="_blank">www.joomsky.com</a>
            </span>
        </span>
    </div>
    <div class="products">
        <div class="components" id="jobs-free">
            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/aboutus_page/joomla.png" />
            <span class="component-text">
                <span class="component-title">
                    <?php echo __('JS Jobs', 'js-jobs'); ?>
                </span>    
                <span class="component-type">
                    <?php echo __('Joomla', 'js-jobs'); ?>
                </span>    
                <span class="component-detail">
                    <?php echo __('JS Jobs for any business, industry body or staffing company wishing to establish a presence on the internet where job seekers can come to view the latest jobs and apply to them.JS Jobs allows you to run your own, unique jobs classifieds service where you or employer can advertise their jobs and jobseekers can upload their Resume', 'js-jobs'); ?>
                </span>    
            </span>
            <span class="info-urls">
                <a class="pro" href="http://www.joomsky.com/products/js-jobs-pro.html">
                    <?php echo __('Pro Download', 'js-jobs'); ?>
                </a>
                <a class="free" href="http://www.joomsky.com/products/js-jobs.html">
                    <?php echo __('Free Download', 'js-jobs'); ?>
                </a>
            </span>
        </div>
        <div class="components" id="autoz-pro">
            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/aboutus_page/wordpress.png" />
            <span class="component-text">
                <span class="component-title">
                    <?php echo __('JS Jobs', 'js-jobs'); ?>
                </span>    
                <span class="component-type">
                    <?php echo __('WordPress', 'js-jobs'); ?>
                </span>    
                <span class="component-detail">
                    <?php echo __('JS Jobs for any business, industry body or staffing company wishing to establish a presence on the internet where job seekers can come to view the latest jobs and apply to them.JS Jobs allows you to run your own, unique jobs classifieds service where you or employer can advertise their jobs and jobseekers can upload their Resumes', 'js-jobs'); ?>
                </span>    
            </span>
            <span class="info-urls">
                <a class="pro" href="http://www.joomsky.com/products/js-jobs-pro-wp.html">
                    <?php echo __('Pro Download', 'js-jobs'); ?>
                </a>
                <a class="free" href="http://www.joomsky.com/products/js-jobs-wp.html">
                    <?php echo __('Free Download', 'js-jobs'); ?>
                </a>
            </span>
        </div>
        <div class="components" id="ticket-free">
            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/aboutus_page/joomla.png" />
            <span class="component-text">
                <span class="component-title">
                    <?php echo __('JS Support Ticket', 'js-jobs'); ?>
                </span>    
                <span class="component-type">
                    <?php echo __('Joomla', 'js-jobs'); ?>
                </span>    
                <span class="component-detail">
                    <?php echo __('JS Support Ticket is a trusted open source ticket system. JS Support ticket is a simple, easy to use, web-based customer support system. User can create ticket from front-end. JS support ticket comes packed with lot features than most of the expensive(and complex) support ticket system on market.', 'js-jobs'); ?>
                </span>    
            </span>
            <span class="info-urls">
                <a class="pro" href="http://www.joomsky.com/products/js-support-ticket-pro-joomla.html">
                    <?php echo __('Pro Download', 'js-jobs'); ?>
                </a>
                <a class="free" href="http://www.joomsky.com/products/js-support-ticket-joomla.html">
                    <?php echo __('Free Download', 'js-jobs'); ?>
                </a>
            </span>
        </div>
        <div class="components" id="autoz-free">
            <img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/aboutus_page/wordpress.png" />
            <span class="component-text">
                <span class="component-title">
                    <?php echo __('JS Help Desk', 'js-jobs'); ?>
                </span>    
                <span class="component-type">
                    <?php echo __('WordPress', 'js-jobs'); ?>
                </span>    
                <span class="component-detail">
                    <?php echo __('JS Help Desk is a trusted open source ticket system. JS Help Desk is a simple, easy to use, web-based customer support system. User can create ticket from front-end. JS help desk comes packed with lot features than most of the expensive(and complex) help desk system on market.', 'js-jobs'); ?>
                </span>    
            </span>
            <span class="info-urls">
                <a class="pro" href="http://www.joomsky.com/products/js-support-ticket-pro-wp.html">
                    <?php echo __('Pro Download', 'js-jobs'); ?>
                </a>
                <a class="free" href="http://www.joomsky.com/products/js-support-ticket-wp.html">
                    <?php echo __('Free Download', 'js-jobs'); ?>
                </a>
            </span>
        </div>
    </div>

</div>
</div>
