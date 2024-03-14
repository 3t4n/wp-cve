<form action="options.php" method="post">
       <?php
       settings_fields( 'dvin508-toolbox' );
       $enable = get_option("dvin508_enable_front", 'off');
       ?>
<table style="margin-top:20px;">
    <tr>
        <td style="padding:10px; padding-left:0px;"><strong><label for="tool-508">Start Accessibility Audit:</label></strong> </td>
        <td style="padding:10px;"><input type="checkbox" id="tool-508" name="dvin508_enable_front" <?php echo ($enable == 'on' ? 'checked="checked"': ''); ?>></td>
    </tr>
</table>
<?php submit_button(); ?> 
</form>
<div class="checklist"  style="font-size:16px;">
<h3>How to use</h3>
<ol>
<li>Go to the page that you want to audit while logged in as administrator</li>
<li>Click the icon on the bottom left of your page and choose what you want to test for</li>
<li>The plugin will run an automated test and show you where you are not compliant</li>
<li>This software will also suggest some solutions to help you become compliant</li>
</ol>

<h3>How it works</h3>
This is a single JavaScript file that inserts a small button in the bottom corner of your page.
The toolbar consists of several plugins that each provide their own functionality.
Many of these plugins "annotate" elements on the page. Sometimes to show their existence, other times to point out when something's wrong.
</div>