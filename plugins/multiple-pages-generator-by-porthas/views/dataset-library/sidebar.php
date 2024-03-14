<div class="">
    <div class="block sidebar-block shadowed">
   <div class="sidebar-block-inner-content">
        <?php
         if (mpg_app()->is_premium()) {
        ?>
            <a href="#" class="btn btn-success"> <?php _e('PRO version', 'mpg'); ?></a>

        <?php } else { ?>

            <a class="btn btn-primary upgrade-btn" href="<?php echo mpg_app()->get_upgrade_url('datasetlibrary');?>"><?php _e('Upgrade to PRO', 'mpg'); ?></a>

        <?php } ?>

        <p style="margin-top:20px;"><?php _e('This plugin allows you to quickly create and effectively edit an infinite number of new pages or posts on your site, using only a spreadsheet.', 'mpg');?></p>
        </div>
    </div>
</div>

<div class="">  
    <div class="block sidebar-block shadowed">

        <h2><?php _e('Need Help?', 'mpg'); ?></h2>
        <div class="sidebar-video-block">
			<h4><?php _e('Welcome to MPG', 'mpg') ?></h4>

			<iframe  height="200" style="max-height: 200px;" src="https://www.youtube.com/embed/tsr_RfLMVYU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        <div class="sidebar-block-inner-content">
        <h4><?php _e('Where to start with MPG', 'mpg'); ?></h4>
        <ul>
            <li>
                <div class="number">1</div>
                <p><?php _e('Select a template to try or build up from or if you are experienced user - start "From scratch".', 'mpg');?></p>
            </li>

            <li>
                <div class="number">2</div>
                <p><?php _e('Set or modify your template entity. Load or modify source URL/file. Adjust URL generation format accordingly to your needs. Add shortcodes from source file to your template to generate unique content.', 'mpg');?></p>
            </li>

            <li>
                <div class="number">3</div>
                <p><?php _e('Enjoy! Don’t forget that editing these pages is as easy as modifying your template file and/or data source.', 'mpg');?></p>
            </li>
        </ul>
        </div>
    </div>
</div>