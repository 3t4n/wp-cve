<a id="wordable-plugin-settings-system-report-link" href="#"> Copy Report </a>
<?php $system_report = $this->system_report(); ?>

<pre style="display: none" id="wordable-plugin-settings-system-report-text">
** System Report **<br />
Secret: <?php echo $system_report['secret'] ?><br />
URL: <?php echo $system_report['url'] ?><br />
Admin URL: <?php echo $system_report['admin_url'] ?><br />
Plugin Version: <?php echo $system_report['plugin_version'] ?><br />
WordPress Version: <?php echo $system_report['wordpress_version'] ?><br />
PHP Version: <?php echo $system_report['php_version'] ?><br />
<br />** WordPress Plugins **<br />
<?php foreach($system_report['plugins'] as $installed_plugin) {
   echo esc_html($installed_plugin)."\n<br />";
} ?>
</pre>
