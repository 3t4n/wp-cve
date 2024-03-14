<div class="faq-content">
<div class="wrap-main">

<div class="tab-full">
	<button class="collapsible"><?php echo esc_html( __('How to configure your Google Spreadsheet with WPForms plugin?')); ?></button>
	<div class="content">
		<ul>
			<li><?php echo esc_html( __('Go to the WPForms Menu and click on  Googlesheet .')); ?></li>
			<li><?php echo esc_html( __('Then click on the Google Sheet tab.')); ?></li>
			<li><?php echo esc_html( __('Then go to Form Settings Tab.')); ?></li>
			<li><?php echo esc_html( __('Then Select the form which you want to connect with your spreadsheet.')); ?></li>
			<p>
		<img class="alignnone" src="<?php echo WPFORMS_GOOGLESHEET_URL . 'assets/img/faq-screenshot1.png'; ?>" />
		</p>
			<li><?php echo esc_html( __('Enter Google Sheet name and Google Sheet tab name inside the Google Sheet tab..')); ?></li>
			<p>
		<img class="alignnone" src="<?php echo WPFORMS_GOOGLESHEET_URL . 'assets/img/faq-screenshot2.png'; ?>" />
		</p>
		</ul>
	</div>
</div>
<div class="tab-full">
	<button class="collapsible"><?php echo esc_html( __('Why I am Getting Error 500 After Installing Plugin.')); ?></button>
	<div class="content">
		<p><?php echo esc_html( __('Following are few of the points which will help to debug the issue.')); ?></p>
		<p><?php echo esc_html( __('1) Enable debug by adding&nbsp;following in your wp-config.php file before /* That’s all, stop editing! Happy blogging.')); ?></p>
		<p><?php echo esc_html( __('define(‘WP_DEBUG’, true); define(‘WP_DEBUG_LOG’, true); define(‘SCRIPT_DEBUG’, true); define(‘SAVEQUERIES’, true);')); ?></p>
		<p><?php echo esc_html( __('And then try to activate the plugin again. This will create a debug.log under wp-content folder.&nbsp;Check for WPForms google sheet connector error if so send us the file at support@westerndeal.com')); ?></p>
		<p><?php echo esc_html( __('2) Check the log that is created by WPForms google sheet. For that click “View” from goggle sheet integration page. Let us know if there is any error we will assist you.')); ?></p>
		<span class="cp-load-after-post"></span>
	</div>
</div>

<div class="tab-full">
	<button class="collapsible"><?php echo esc_html( __('How do I get the Google Access Code required in step 3 of Installation?')); ?></button>
	<div class="content">
		<ol>
			<li><?php echo __('On the&nbsp;<code>Admin Panel &gt; WPForms &gt; Google Sheets.</code>'); ?></li>
			<li><?php echo __('Go to “Integration” tab and click on "Get Code" button.'); ?></li>
			<li><?php echo esc_html( __('In a popup Google will ask you to authorize the plugin to connect to your Google Sheets. Authorize it – you may have to log in to your Google account if you aren’t already logged in.')); ?></li>
			<li><?php echo esc_html( __('On the next screen, you should receive the Access Code. Copy it.')); ?></li>
			<li><?php echo __('Now you can paste this code back in the Google Access Code.'); ?></li>
		</ol>
	</div>
</div>

<div class="tab-full">
	<button class="collapsible"><?php echo esc_html( __('Submitted Value is not showing in Googlesheet.')); ?></button>
	<div class="content">
	
	<div class="panel-body toggle-content post-content">
	<p><?php echo esc_html( __('Sometimes it can take a while of spinning before it goes through. But if the entries never show up in your Sheet then one of these things might be the reason:')); ?></p>
	<ol><li><?php echo esc_html( __('Wrong access code ( Check debug log )')); ?></li>
	<li><?php echo esc_html( __('Wrong Sheet name or tab name')); ?></li>
	<li><?php echo esc_html( __('Wrong Column name ( Column names in the WPForms. It cannot have underscore, space or any special characters. )')); ?></li>
	</ol>
	<p><?php echo esc_html( __('Please double-check those items and hopefully getting them right will fix the issue.')); ?></p> 
	<span class="cp-load-after-post"></span>
	</div>
	
	</div>
</div>

<div class="tab-full">
	<button class="collapsible"><?php echo esc_html( __('Why I am not showing WPForms Values(submitted data) in My Googlesheet?')); ?></button>
	<div class="content">
		<p><?php echo esc_html( __('Sometimes it can take a while of spinning before it goes through. But if the entries never show up in your Sheet then one of these things might be the reason:')); ?></p>
		<ul>
			<li><?php echo esc_html( __('Wrong access code ( Check debug log )')); ?></li>
			<li><?php echo esc_html( __('Wrong Sheet name or tab name')); ?></li>
			<li><?php echo esc_html( __('Wrong Column name mapping ( Column names in the WPForms. It cannot have underscore, space or any special characters )')); ?></li>
		</ul>
		<p><?php echo esc_html( __('Please double-check those items and hopefully getting them right will fix the issue.')); ?></p>
		
	</div>
</div>
</div>
</div>
<script>
var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  });
}
</script>

