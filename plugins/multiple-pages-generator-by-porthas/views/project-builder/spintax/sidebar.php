<div class="block sidebar-block shadowed">
    <div class="sidebar-block-inner-content">

        <?php
         if (mpg_app()->is_premium()) {
        ?>
            <a class="btn btn-success" href="#"><?php _e('PRO version', 'mpg'); ?></a>

        <?php } else { ?>

            <a class="btn btn-primary upgrade-btn" href="<?php echo mpg_app()->get_upgrade_url('spintax'); ?>"><?php _e('Upgrade to PRO', 'mpg'); ?></a>

            <p><?php mpg_app()->upgrade_notice(); ?></p>

        <?php } ?>

    </div>
</div>

<div class="block sidebar-block shadowed">

    <h2><?php _e('Need Help?', 'mpg'); ?></h2>
    <div class="sidebar-block-inner-content">
        <h4><?php _e('Use Spintax to create unique content.', 'mpg') ?></h4>

        <ul>
            <li>
                <div class="number">1</div>
                <p><?php _e('Use the Spintax syntaxes to generate dynamic text for your MPG generates pages.', 'mpg'); ?></p>
            </li>

            <li>
                <div class="number">2</div>
                <p><?php _e('When satisfied, copy the expression and add it to your template, where appropriate.', 'mpg'); ?></p>
            </li>

            <li>
                <div class="number">3</div>
                <p><?php _e('When MPG page is loaded, the text is generated and saved in database forever.', 'mpg'); ?></p>
            </li>

            <li>
                <div class="number">4</div>
                <p><?php _e('Flush cache if you wish to reset remembered spintax expression.', 'mpg'); ?></p>
            </li>

        </ul>
    </div>
</div>