<?php
function magenet_article_tutorial_buttons($direction = 0)
{
    ?>
    <div style="width: 468px; padding-top: 32px; height: 48px;">
        <div>
            <div style="width: 40%; float: left; text-align: left;">
                <?php
                if ($direction >= 0) { ?><span class="btn_prev abp_btn">&lt;&lt; Prev</span><?php }
                elseif ($direction == -2) { ?><span class="btn_prev abp_btn tutorial-close">Skip</span><?php } ?>
            </div>
            <div style="width: 40%; float: right; text-align: right;">
                <?php if ($direction <= 0) { ?>
                    <?php if ($direction == -2) { ?>
                        <span class="btn_next abp_btn tutorial-next">Start tutorial</span>
                        <?php
                    }
                    else {
                        ?>
                        <span class="btn_next abp_btn tutorial-next">Next &gt;&gt;</span>
                    <?php } ?>
                    <?php
                }
                else {
                    ?>
                    <span class="btn_again abp_btn show-magenet-article-tutorial">Watch Again</span>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
}
?>

<div id="mn-a-0" class="magenet-article-tutorial-popup" style="display: none;" title="Congratulations!">
    <strong>Website Article Monetization Plugin</strong> is successfully installed!<br><br>
    To start getting the new content on your site and making profit from it automatically, set up the plugin properly.<br><br>
    Click <strong>"Start tutorial"</strong> to activate the plugin efficiently.
    <?php magenet_article_tutorial_buttons(-2); ?>
</div>

<div id="mn-a-1" class="magenet-article-tutorial-popup" style="display: none" title="Step 1">
    <a href="https://cp.magenet.com/login" target="_blank">Log in to MageNet</a>&nbsp;&nbsp;if you already have <strong>MageNet</strong> account<br><br>
    <div style="text-align: center">or</div><br>
    <a href="http://www.magenet.com/#sign_up" target="_blank">Sign up for MageNet</a>&nbsp;&nbsp;to provide Plugin with articles from our marketplace.
    <?php magenet_article_tutorial_buttons(-1); ?>
</div>

<div id="mn-a-2" class="magenet-article-tutorial-popup" style="display: none" title="Step 2">
    <ul>
        <li><a href="https://cp.magenet.com/sites" target="_blank">Add</a> your site to your <strong>MageNet</strong> account</li>
        <li>Enter the website URL, select the relevant category, choose  Engine/CMS.</li>
        <li>Select  the mode of ads placement: <strong>Automatic</strong> with the help of <strong>WordPress Plugin</strong> (recommended) or <strong>Manual</strong> (tasks should be placed manually).</li>
    </ul>
    <?php magenet_article_tutorial_buttons(); ?>
</div>

<div id="mn-a-3" class="magenet-article-tutorial-popup" style="display: none" title="Step 3">
    <div style="text-align: center; font-weight: bold; font-size: 18px">Confirm your site</div><br/>
    To confirm the website using the <strong>WordPress Plugin</strong> and manage the process of getting tasks automatically,
    select <strong>WordPress Plugin</strong> as a way of confirmation and  follow the instructions provided.
    If you have issues while plugin activation, check <a target="_blank" href="https://cp.magenet.com/view/plugin_error">these instructions</a>.<br/><br/>
    To confirm the website <strong>manually</strong>, select <strong>Manual Install</strong>,
    insert the provided code anywhere on the homepage of the website and press <strong>Confirm&Go to Article Plugin</strong> button.
    After confirmation you can remove this code.
    <?php magenet_article_tutorial_buttons(); ?>
</div>

<div id="mn-a-4" class="magenet-article-tutorial-popup" style="display: none" title="Step 4">
    <ul>
        <li>Go to Plugins > Installed Plugins.</li>
        <li>Look for <strong>"Website Article Monetization By MageNet".</strong></li>
        <li>The plugin should be activated. If not - click <strong>Activate.</strong></li>
        <li>Go to  Website Article Monetization By MageNet <strong>Settings.</strong></li>
    </ul>
    <?php magenet_article_tutorial_buttons(); ?>
</div>

<div id="mn-a-5" class="magenet-article-tutorial-popup" style="display: none" title="Step 5">
    Log in to your MageNet account and visit <a target="_blank" href="https://cp.magenet.com/sites">Your sites</a> dashboard to get the Authorization Key.<br/><br/>
    Click the <strong>Install Article Plugin</strong> button and сopy the Key from the <i>"Confirm your MageNet Key"</i> field in the open popup.
    <?php magenet_article_tutorial_buttons(); ?>
</div>

<div id="mn-a-6" class="magenet-article-tutorial-popup" style="display: none" title="Step 6">
    Paste the copied code Key into the Article Plugin Settings field of your WordPress admin panel.
    <br/><br/>
    Select the category in which you want to publish your articles and click the <strong>‘Save’</strong> button.
    <?php magenet_article_tutorial_buttons(); ?>
</div>

<div id="mn-a-7" class="magenet-article-tutorial-popup" style="display: none" title="Step 7">
    Go to your MageNet account and click the <strong>Confirm</strong> button.
    <?php magenet_article_tutorial_buttons(); ?>
</div>

<div id="mn-a-8" class="magenet-article-tutorial-popup" style="display: none; text-align: center" title="">
    <div class="mn-a-8-content">
        Great job!<br/>
        Plugin is activated successfully!<br/>
        If you have experienced any problems installing the plugin, please contact our <a target="_blank" href="https://cp.magenet.com/site/contact">Support Team.</a>
    </div>
    <?php magenet_article_tutorial_buttons(1); ?>
</div>