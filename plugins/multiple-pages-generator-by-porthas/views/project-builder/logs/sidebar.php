<div class="block sidebar-block shadowed">
    <div class="sidebar-block-inner-content">
    <?php
        if (mpg_app()->is_premium()) {
        ?>
            <a class="btn btn-success" href="#"><?php _e('PRO version', 'mpg'); ?></a>

        <?php } else { ?>

            <a class="btn btn-primary upgrade-btn" href="<?php echo mpg_app()->get_upgrade_url('logssidebar'); ?>"><?php _e('Upgrade to PRO', 'mpg'); ?></a>
            
            <p><?php mpg_app()->upgrade_notice();?></p>

        <?php } ?>
    </div>
</div>

<div class="block sidebar-block shadowed">

    <h2><?php _e('Need Help?', 'mpg'); ?></h2>
    <div class="sidebar-block-inner-content">
    <h4><?php _e('Create comprehensible internal links', 'mpg') ?></h4>

    <ul>
        <li>
            <div class="number">
                &#10004;</div>
            <p><?php _e('Replace static text with applicable shortcodes from your data source file.', 'mpg');?></p>
        </li>

        <li>
            <div class="number">
                &#10004;</div>
            <p ><?php _e('Make your generated landing pages unique enough so that a user sees value and has positive experience interacting with the page. For best results, use MPG to generate pictures, maps, videos, forms, and other visual content aside from text.', 'mpg');?></p>
        </li>

        <li>
            <div class="number">
                &#10004;</div>
            <p><?php _e('Generate lists of the MPG pages and place them anywhere around your website, where appropriate. The closer to the homepage the better.', 'mpg');?></p>
        </li>

        <li>
            <div class="number">
                &#10004;</div>
            <p><?php _e('For best results, generate in-links so that pages are no more than 3 clicks away from homepage.', 'mpg');?></p>
        </li>
    
    </ul>
    </div>
    <a href="https://docs.themeisle.com/collection/1429-multi-pages-generator" target="_blank" class="sidebar-learn-more"><?php _e('Learn more', 'mpg');?></a>
</div>