<?php
if (!function_exists('add_action'))
{
    echo "<h3>an error occured! You may not be able to access this plugin via direct URL...</h3>";
    exit();
}
else if (!defined('ABSPATH'))
{
    echo "<h3>an error occured! You may not be able to access this plugin via direct URL...</h3>";
    exit();
}
?>

<h4><?php echo __('«« Premium Services »»', 'pfmdz') ?></h4>

<ul style="list-style: circle;width: fit-content;margin-left: auto;margin-right: auto;">
    <li><p><?php echo __('Design & Develope exclusive Plugins', 'pfmdz') ?></p></li>
    <li><p><?php echo __('Design & Develope exclusive Themes', 'pfmdz') ?></p></li>
    <li><p><?php echo __('Design & Develope Web-Sites', 'pfmdz') ?></p></li>
    <li><p><?php echo __('Fixing the problems of WordPress sites', 'pfmdz') ?></p></li>
    <li><p><?php echo __('Optimization and speed increase', 'pfmdz') ?></p></li>
    <li><p><?php echo __('Database Optimizations', 'pfmdz') ?></p></li>
    <li><p><?php echo __('Design & Develope Custom-CMS', 'pfmdz') ?></p></li>
</ul>

<p><?php echo __('Any Questions?! just ask...', 'pfmdz') ?></p>

<ul>
    <li><p><?php echo __('Email: mdesign.fa@gmail.com', 'pfmdz') ?></p></li>
    <li><p><?php echo __('Telegram: @g_mdz', 'pfmdz') ?></p></li>
</ul>

<a class="button button-primary" href="https://t.me/g_mdz" target="_blank"><?php echo __('Quick Contact', 'pfmdz') ?></a>