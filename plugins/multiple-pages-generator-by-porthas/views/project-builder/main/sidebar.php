<div class="block sidebar-block shadowed">
    <div class="sidebar-block-inner-content">
        <?php
         if (mpg_app()->is_premium()) {
        ?>
            <a class="btn btn-success" href="#"><?php _e('PRO version', 'mpg'); ?></a>

        <?php } else { ?>

            <a class="btn btn-primary upgrade-btn" href="<?php echo mpg_app()->get_upgrade_url('mainsidebar'); ?>"><?php _e('Upgrade to PRO', 'mpg'); ?></a>
            
            <p><?php mpg_app()->upgrade_notice();?></p>

        <?php } ?>

        
    </div>
</div>

<div class="block sidebar-block shadowed">

    <h2><?php _e('Need Help?', 'mpg'); ?></h2>
    <div class="sidebar-video-block">
        <h4><?php _e('Welcome to MPG', 'mpg') ?></h4>

        <iframe height="200" style="max-height: 200px;" src="https://www.youtube.com/embed/tsr_RfLMVYU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
    <div class="sidebar-block-inner-content">
        <h4><?php _e('Setting up your template', 'mpg'); ?></h4>
        <ul>
            <li>
                <div class="number">1</div>
                <p><?php _e('Name your project. Select the entity you wish to generate. Select or create a template for the selected entity to use for generation of new pages.', 'mpg');?></p>
            </li>

            <li>
                <div class="number">2</div>
                <p><?php _e('Setup your source file by either pointing to a publicly shared google doc or uploading a spreadsheet.', 'mpg');?></p>
            </li>

            <li>
                <div class="number">3</div>
                <p><?php _e('Setup URL Generation format by combining desired text with shortcodes.', 'mpg');?></p>
            </li>

            <li>
                <div class="number">4</div>
                <p><?php _e('Update your template page with shortcodes from source file. Generate sitemap and upload it in addition to your regular sitemap via Google Search Console.', 'mpg');?></p>
            </li>

            <li>
                <div class="number">5</div>
                <p><?php _e('Create in-links to new pages by generating list shortcodes in Shortcode tab to increase page authority.', 'mpg');?></p>
            </li>

        </ul>
    </div>
</div>